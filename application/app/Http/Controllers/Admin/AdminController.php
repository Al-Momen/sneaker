<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Product;
use App\Lib\CurlRequest;
use App\Constants\Status;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Rules\FileTypeValidate;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use App\Models\BidWinner;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pageTitle = 'Dashboard';

        $userQuery = User::query();
        $withdrawQuery = Withdrawal::query();
        $depositQuery = Deposit::query();
        $productQuery = Product::query();

        $widget = [];

        $widget['total_user']                = (clone $userQuery)->count();
        $widget['banned_users']              = (clone $userQuery)->banned()->count();
        $widget['verified_users']            = (clone $userQuery)->active()->count();
        $widget['email_unverified_users']    = (clone $userQuery)->emailUnverified()->count();
        $widget['mobile_unverified_users']   = (clone $userQuery)->mobileUnverified()->count();
        $widget['all_products']              = (clone $productQuery)->count();
        $widget['banned_percent']            = ($widget['total_user'] > 0) ? number_format((($widget['banned_users'] / $widget['total_user']) * 100), 2) : 0;
        $widget['verified_percent']          = ($widget['total_user'] > 0) ? number_format((($widget['verified_users'] / $widget['total_user']) * 100), 2) : 0;
        $widget['email_unverified_percent']  = ($widget['total_user'] > 0) ? number_format((($widget['email_unverified_users'] / $widget['total_user']) * 100), 2) : 0;
        $widget['mobile_unverified_percent'] = ($widget['total_user'] > 0) ? number_format((($widget['mobile_unverified_users'] / $widget['total_user']) * 100), 2) : 0;
        $widget['total_products']            = (clone $productQuery)->where('type', 1)->count();
        $widget['total_products_percent']    = ($widget['all_products'] > 0) ? number_format((($widget['total_products'] / $widget['all_products']) * 100), 2) : 0;
        $widget['total_auctions']            = (clone $productQuery)->where('type', 2)->count();
        $widget['total_auctions_percent']    = ($widget['all_products'] > 0) ? number_format((($widget['total_auctions'] / $widget['all_products']) * 100), 2) : 0;
        $widget['total_deposit_amount']      = (clone $depositQuery)->successful()->sum('amount');
        $widget['total_withdraw_amount']     = (clone $withdrawQuery)->approved()->sum('amount');
        $widget['total_orders']              = Order::where('status', "!=", 0)->count();
        $widget['total_winner_bids']         = BidWinner::count();
        $transactionQuery                    = Transaction::query();
        $widget['plus_transactions']         = (clone $transactionQuery)->where('trx_type', '+')->count();
        $widget['minus_transactions']        = (clone $transactionQuery)->where('trx_type', '-')->count();
        $transactions                        = (clone $transactionQuery)->latest()->take(4)->get();
        $tickets                             = SupportTicket::with('user')->where('status', Status::TICKET_OPEN)->latest()->limit(5)->get();


        $allMonths = collect(range(1, Carbon::now()->subMonths(1)->format('m')))->mapWithKeys(function ($month) {
            $name = date('F', mktime(0, 0, 0, $month, 1));
            return [$month => $name];
        });


        $depositsRaw = Deposit::selectRaw("SUM(amount) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
            ->whereYear('created_at', now()->year)
            ->where('status', Status::PAYMENT_SUCCESS)
            ->groupBy('month_name', 'month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');


        $depositsChart = [
            'labels' => $allMonths->values(),
            'values' => $allMonths->keys()->map(function ($month) use ($depositsRaw) {
                return optional($depositsRaw->get($month))->amount ?? 0;
            }),
        ];


        $withdrawalsRaw = Withdrawal::selectRaw("SUM(amount) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
            ->whereYear('created_at', now()->year)
            ->where('status', Status::PAYMENT_SUCCESS)
            ->groupBy('month_name', 'month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $withdrawalsChart = [
            'labels' => $allMonths->values(),
            'values' => $allMonths->keys()->map(function ($month) use ($withdrawalsRaw) {
                return optional($withdrawalsRaw->get($month))->amount ?? 0;
            }),
        ];


        $ordersRaw = Order::selectRaw("SUM(total_price) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
            ->whereYear('created_at', now()->year)
            ->where('status', Status::ORDER_SUCCESS)
            ->groupBy('month_name', 'month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');


        $ordersChart = [
            'labels' => $allMonths->values(),
            'values' => $allMonths->keys()->map(function ($month) use ($ordersRaw) {
                return optional($ordersRaw->get($month))->amount ?? 0;
            }),
        ];


        $myOrdersRaw = Order::query()
            ->whereHas('products', function ($q) {
                $q->where('author_id', auth('admin')->id())
                    ->where('author_type', 1);
            })->selectRaw("SUM(total_price) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
            ->whereYear('created_at', now()->year)
            ->where('status', Status::ORDER_SUCCESS)
            ->groupBy('month_name', 'month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');


        $myOrdersChart = [
            'labels' => $allMonths->values(),
            'values' => $allMonths->keys()->map(function ($month) use ($myOrdersRaw) {
                return optional($myOrdersRaw->get($month))->amount ?? 0;
            }),
        ];


        $newTickets = SupportTicket::with('user')->orderBy('created_at', 'desc')->whereStatus(0)->limit(5)->get();
        return view('Admin::dashboard', compact('pageTitle', 'widget', 'withdrawalsChart', 'depositsChart', 'ordersChart', 'myOrdersChart', 'tickets', 'transactions'));
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('Admin::profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile has been updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('Admin::profile', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $pageTitle = 'Notifications';
        return view('Admin::notifications', compact('pageTitle', 'notifications'));
    }


    public function notificationRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->read_status = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function readAll()
    {
        AdminNotification::where('read_status', Status::NO)->update([
            'read_status' => Status::YES
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
