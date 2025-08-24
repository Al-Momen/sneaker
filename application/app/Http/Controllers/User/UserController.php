<?php

namespace App\Http\Controllers\User;

use App\Models\Form;
use App\Models\Order;
use App\Models\Review;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\Wishlist;
use App\Constants\Status;
use App\Models\BidWinner;
use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Lib\GoogleAuthenticator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle                     = 'Dashboard';
        $user                          = auth()->user();
        $productQuery                  = Product::where('author_id', $user->id)->where('author_type', 2);
        $transactionQuery              = Transaction::where('user_id', $user->id);
        $data['totalWithdrawalsMoney'] = Withdrawal::where('user_id', $user->id)->sum('final_amount');
        $data['totalTickets']          = SupportTicket::where('user_id', $user->id)->count();
        $data['total_products']        = (clone $productQuery)->where('type', 1)->count();
        $data['total_auctions']        = (clone $productQuery)->where('type', 2)->count();
        $data['total_winner_bids']     = BidWinner::where('user_id', $user->id)->count();
        $data['wishlists']             = Wishlist::where('user_id', $user->id)->count();
        $data['totalDepositMoney']     = (clone $transactionQuery)->where('remark', 'balance_add')->sum('amount');
        $latestTransaction             = (clone $transactionQuery)->take(5)->latest()->get();


        // order graph
        $monthlyData = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->where('user_id', $user->id)
            ->whereIn('status', [1, 2])
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total_orders', 'month');

        $months     = [];
        $quantities = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[]     = date('M', mktime(0, 0, 0, $i, 1));
            $quantities[] = $monthlyData[$i] ?? 0;
        }

        $monthlyMyOrders = [
            'months'     => $months,
            'quantities' => $quantities
        ];


        $monthlyOrderVendorData = Order::query()
            ->whereHas('products', function ($q) use ($user) {
                $q->where('author_id', $user->id)
                    ->where('author_type', 2);
            })
            ->where('status', '!=', 0)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('month')
            ->pluck('total_orders', 'month');

           

        $months     = [];
        $quantities = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[]     = date('M', mktime(0, 0, 0, $i, 1));
            $quantities[] = $monthlyOrderVendorData[$i] ?? 0;
        }

        $monthlyVendorOrders = [
            'months'     => $months,
            'quantities' => $quantities
        ];

        return view('UserTemplate::dashboard', compact('pageTitle', 'monthlyMyOrders', 'monthlyVendorOrders', 'user', 'data', 'latestTransaction'));
    }

    public function depositHistory($status = 'all')
    {
        $query = Deposit::where('user_id', auth()->id())
            ->with(['gateway'])
            ->searchable(['trx'])
            ->latest();

        switch ($status) {
            case 'initial':
                $query->where('status', Status::PAYMENT_INITIATE);
                break;
            case 'pending':
                $query->where('status', Status::PAYMENT_PENDING);
                break;
            case 'approved':
                $query->where('status', Status::PAYMENT_SUCCESS);
                break;
            case 'reject':
                $query->where('status', Status::PAYMENT_REJECT);
                break;
            case 'all':
                $query->whereIn('status', [Status::PAYMENT_SUCCESS, Status::PAYMENT_REJECT, Status::PAYMENT_INITIATE, Status::PAYMENT_PENDING]);
                break;
            default:
                break;
        }

        $deposits = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.deposit_history_data', compact('deposits'))->render(),
                'pagination' => $deposits->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $deposits])->render() : '',
            ]);
        }

        $pageTitle = 'Payment History';
        return view('UserTemplate::deposit_history', compact('deposits', 'pageTitle'));
    }



    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('UserTemplate::twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        return view('UserTemplate::transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('UserTemplate::kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('UserTemplate::kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
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

    public function userData()
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view('UserTemplate::user_data', compact('pageTitle', 'user'));
    }



    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => $user->address->country,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function reviewStore(Request $request)
    {



        $auth = auth()->user();
        $product_id = $request->product_id;
        $product = Product::find($product_id);

        if (!$product) {
            $notify[] = ['error', 'Product not found'];
            return back()->withNotify($notify);
        }

        $existingReview = Review::where('user_id', $auth->id)
            ->where('product_id', $product_id)
            ->first();

        if ($existingReview) {
            $notify[] = ['error', 'You have already submitted a review for this product'];
            return back()->withNotify($notify);
        }

        $isOrder = Order::whereHas('products', function ($q) use ($product_id) {
            $q->where('product_id', $product_id);
        })->where('user_id', $auth->id)
            ->where('status', 2)
            ->exists();


        if (!$isOrder) {
            $notify[] = ['error', 'Please purchase this product first before reviewing it'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'star' => 'required|numeric|min:1|max:5'
        ]);

        $review = new Review();
        $review->product_id = $product_id;
        $review->user_id = $auth->id;
        $review->message = $request->review;
        $review->rating = $request->star;
        $review->save();

        $reviews = $product->reviews()->get();
        $reviewCount = $reviews->count();
        $totalRating = $reviews->sum('rating');
        $newAverageRating = $totalRating / $reviewCount;

        // Update review_count and average_rating
        $product->review_count = $reviewCount;
        $product->average_rating = $newAverageRating;
        $product->save();

        $notify[] = ['success', 'Review submitted successfully'];
        return back()->withNotify($notify);
    }

    // wishlist
    public function toggleWishlist(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Please log in to your account']);
        }

        $productId = $request->productId;

        if ($productId) {
            $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($wishlist) {
                $wishlist->delete();
                return response()->json([
                    'message' => 'Removed from Wishlist'
                ], 200);
            } else {
                $wishlist = new Wishlist();
                $wishlist->user_id = $userId;
                $wishlist->product_id = $productId;
                $wishlist->save();
                return response()->json([
                    'message' => 'Added to Wishlist'
                ], 200);
            }
        }

        return response()->json(['error' => 'No valid item to add or remove from Wishlist']);
    }

    public function getWishlist(Request $request)
    {
        $pageTitle = 'Wishlist';
        $wishlists = Wishlist::with(['product.firstImage'])->where('user_id', auth()->id())->searchable(['product:name'])->latest()->paginate(getPaginate());
        return view('UserTemplate::wishlist.index', compact('pageTitle', 'wishlists'));
    }

    public function removeWishlist(Request $request)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->where('id', $request->id)->first();
        $wishlist->delete();

        $notify[] = ['success', 'Wishlist has been removed'];
        return back()->withNotify($notify);
    }
}
