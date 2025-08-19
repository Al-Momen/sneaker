<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function deposit(Request $request, $status = 'all')
    {
        $depositData  = $this->depositData($status != 'all' ? $status : null, true);
        $items = $depositData['data'];
        $summery = $depositData['summery'];
        $successful = $summery['successful'];
        $pending = $summery['pending'];
        $rejected = $summery['rejected'];
        $initiated = $summery['initiated'];
        $logCount = $summery['total'];

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.deposit_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Deposits';
        return view('Admin::deposit.log', compact('pageTitle', 'items', 'successful', 'pending', 'rejected', 'initiated'));
    }


    protected function depositData($scope = null, $summery = false)
    {
        $baseQuery = $scope ? Deposit::$scope()->with(['user', 'gateway']) : Deposit::with(['user', 'gateway']);
        $dataQuery =  Deposit::query();
        $summaryQuery = clone $dataQuery;

        $successfulSummery = (clone $summaryQuery)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $pendingSummery = (clone $summaryQuery)->where('status', Status::PAYMENT_PENDING)->sum('amount');
        $rejectedSummery = (clone $summaryQuery)->where('status', Status::PAYMENT_REJECT)->sum('amount');
        $initiatedSummery = (clone $summaryQuery)->where('status', Status::PAYMENT_INITIATE)->sum('amount');
        $totalCount        = $summaryQuery->count();

        $deposits =  $baseQuery->searchable(['trx', 'user:username'])->dateFilter()->latest()->paginate(getPaginate());

        return [
            'data' => $deposits,
            'summery' => [
                'successful' => $successfulSummery,
                'pending' => $pendingSummery,
                'rejected' => $rejectedSummery,
                'initiated' => $initiatedSummery,
                'total' => $totalCount
            ]
        ];
    }

    public function details($id)
    {
        $general = gs();
        $deposit = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = 'Deposit Request of ' . showAmount($deposit->amount) . ' ' . $general->cur_text;
        $details = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('Admin::deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }

    public function successful($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_SUCCESS)->firstOrFail();
        $pageTitle = 'Successful Deposits';
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approve($id)
    {
        $deposit = Deposit::with('order')->where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();
        PaymentController::userDataUpdate($deposit, true);
        $notify[] = ['success', ($deposit->order_id ? 'Payment' : 'Deposit') . ' request approved successfully'];
        return to_route('admin.deposit.log')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|string|max:255'
        ]);
        $deposit = Deposit::with('order')->where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();


        if ($deposit->order_id && $deposit->order) {
            $deposit->order->status = Status::ORDER_PAYMENT_REJECT; // Order payment reject
            $deposit->order->save();
        }

        $type = $deposit->order_id ? 'Payment' : 'Deposit';

        $notifyData = [
            'method_name' => $deposit->gatewayCurrency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amo),
            'amount' => showAmount($deposit->amount),
            'charge' => showAmount($deposit->charge),
            'rate' => showAmount($deposit->rate),
            'rejection_message' => $request->message
        ];


        if ($deposit->order_id && $deposit->order) {
            $notifyData['order_number'] = $deposit->order->order_number;
            notify($deposit->user, 'ORDER_REJECT', $notifyData);
        } else {
            $notifyData['trx'] = $deposit->trx;
            notify($deposit->user, 'DEPOSIT_REJECT', $notifyData);
        }




        $notify[] = ['success', "{$type} request rejected successfully"];
        return  to_route('admin.deposit.log')->withNotify($notify);
    }
}
