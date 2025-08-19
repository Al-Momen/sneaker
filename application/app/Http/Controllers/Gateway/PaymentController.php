<?php

namespace App\Http\Controllers\Gateway;

use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\Shipping;
use App\Constants\Status;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Add Money';
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    // =========================== Add Deposit Money ===============================
    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = Status::PAYMENT_INITIATE;
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function productPayment(Request $request)
    {
        $request->validate([
            'method_code' => 'nullable|required_unless:gateway,balance',
            'method_code' => 'nullable|required_unless:gateway,balance',
            'firstname'   => 'required|string|max:100',
            'lastname'    => 'required|string|max:100',
            'country'      => 'required|string',
            'mobile_code'  => 'required|numeric',
            'country_code' => 'required|string',
            'email'        => 'required|email|max:150',
            'mobile'       => 'required|numeric',
            'address'      => 'required|string',
            'shipping'     => 'required|numeric',
            'gateway'      => 'required',
        ]);

        // ================================ Check session id exist to product table or not ==============================
        $cart = Session::get('cart');
        $user = auth()->user();

        $ids = collect($cart)->pluck('id')->toArray();
        $validIds = Product::whereIn('id', $ids)->pluck('id')->toArray();
        $missingIds = array_diff($ids, $validIds);
        if (!empty($missingIds)) {
            $notify[] = ['error', 'Invalid gateway' . $missingIds];
            return back()->withErrors($notify)->withInput($request->all());
        }

        // ================================ Total Payment  ==============================
        $products = Product::whereIn('id', $ids)->get();
        $shipping = Shipping::where('id', $request->shipping)->first();
        $total = 0;

        foreach ($cart as $item) {
            $product = $products->firstWhere('id', $item['id']);
            if ($product) {
                $price = discountPrice($product->price, $product->discount);
                $total += floatVal($price) * $item['quantity'];
            }
        }
        $totalPice = $total + ($shipping->charge ?? 0);


        // ================================ Custom Validation ==================================
        if ($request->gateway == 'balance') {
            // check balance
            if ($user->balance < $totalPice) {
                $notify[] = ['error', 'Insufficient Balance'];
                return back()->withNotify($notify);
            }
        } else {
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();

            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withErrors($notify)->withInput($request->all());
            }

            if ($gate->min_amount > $totalPice || $gate->max_amount < $totalPice) {
                $notify[] = ['error', 'Please follow Payment limit'];
                return back()->withErrors($notify)->withInput($request->all());
            }

            $charge = $gate->fixed_charge + ($totalPice * $gate->percent_charge / 100);
            $payable = $totalPice + $charge;
            $final_amo = $payable * $gate->rate;
        }


        // ================================ Create Order ==============================
        $order = new Order();
        $order->user_id = $user->id;
        $order->order_number = getTrx(4);
        $order->total_price = $totalPice;
        $order->shipping_id = $shipping->id;
        $order->first_name = $request->firstname;
        $order->last_name = $request->lastname;
        $order->email = $request->email;
        $order->country = $request->country;
        $order->mobile = $request->mobile;
        $order->address = $request->address;
        $order->status = Status::ORDER_INITIATE; //its Initiated
        $order->save();


        // ================================ Attach Products ==============================
        foreach ($cart as $item) {
            $order->products()->attach($item['id'], [
                'user_id'      => $user->id,
                'order_id'     => $order->id,
                'product_id'   => $item['id'],
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
            ]);
        }

        // =========================================== User Balance Payment =========================================
        if ($request->gateway == 'balance') {

            $order->status = Status::ORDER_SUCCESS; //its Success
            $order->save();

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $order->user_id;
            $adminNotification->title = 'Order request from ' . $order->firstname . $order->lastname;
            $adminNotification->click_url = urlPath('home');
            $adminNotification->save();

            notify($user, 'ORDER_PLACE', [
                'order_number' => $order->order_number,
                'amount' => showAmount($order->total_price),
                'post_balance' => showAmount($user->balance)
            ]);

            $user->balance -= $totalPice;
            $user->save();

            session()->forget('cart');
            $notify[] = ['success', 'Order place has been successfully'];
            return to_route('user.orders.index')->withNotify($notify);
        }


        // ================================ Gateway Payment ==============================
        $data = new Deposit();
        $data->user_id = auth()->id();
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $totalPice;
        $data->order_id = $order->id;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = Status::PAYMENT_INITIATE;
        $data->save();

        notify($user, 'ORDER_REQUEST', [
            'order_number' => $order->order_number,
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate)
        ]);

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $order->user_id;
        $adminNotification->title = 'Order request from ' . $order->firstname . $order->lastname;
        $adminNotification->click_url = urlPath('admin.deposit.successful', $data->id);
        $adminNotification->save();

        session()->forget('cart');
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (isset($data->session)) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public function manualDepositConfirm()
    {
        $url = url()->previous();
        $lastWord = basename($url);
        $track = session()->get('Track');
        session()->put('previous_url', $lastWord);
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {
            $pageTitle = ($lastWord == "checkout") ? "Payment Confirm" : 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {

        $track = session()->get('Track');

        $data = Deposit::with('gateway', 'order')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        // is deposit is order
        if ($data->order_id) {
            $data->order->status = Status::ORDER_PENDING; // Order pending
            $data->order->save();
        }

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = ($data->order_id ? 'Payment' : 'Deposit') . 'request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        $notifyData = [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
        ];

        if ($data->order_id && $data->order) {
            $notifyData['order_number'] = $data->order->order_number;
            notify($data->user, "ORDER_PENDING", $notifyData);
        } else {
            $notifyData['trx'] = $data->trx;
            notify($data->user, "DEPOSIT_REQUEST", $notifyData);
        }

        $type = $data->order_id ? 'Payment' : 'Deposit';
        $notify[] = ['success', "Your {$type} request has been taken"];
        return to_route('user.deposit.history')->withNotify($notify);
    }

    public static function userDataUpdate($deposit, $isManual = null)
    {
        if ($deposit->status == Status::PAYMENT_PENDING || $deposit->status == Status::PAYMENT_INITIATE) {
            $deposit->status = Status::PAYMENT_SUCCESS; // Deposit Approved
            $deposit->save();
            $user = User::find($deposit->user_id);
            if ($deposit->order_id) {
                $deposit->order = $deposit->order;
                $deposit->order->status = Status::ORDER_SUCCESS; // Order Approved
                $deposit->order->save();
            } else {
                $user->balance += $deposit->amount;
                $user->save();
            }

            $type = $deposit->order_id ? 'Payment' : 'Deposit';
            $transaction = new Transaction();
            $transaction->user_id = $deposit->user_id;
            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = $deposit->charge;
            $transaction->trx_type = '+';
            $transaction->details = $type . 'Via' . $deposit->gatewayCurrency()->name;
            $transaction->remark = strtolower($type);
            $transaction->trx = $deposit->trx;
            $transaction->save();

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = $type . 'successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            // ✅ User notification
            $notifyData = [
                'method_name' => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amo),
                'charge'          => showAmount($deposit->charge),
                'rate'            => showAmount($deposit->rate),
                'post_balance'    => showAmount($user->balance),
            ];

            if ($deposit->order_id && $deposit->order) {
                $notifyData['order_number'] = $deposit->order->order_number;
                notify($user, 'ORDER_APPROVE', $notifyData);
            } else {
                $notifyData['trx'] = $deposit->trx;
                notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', $notifyData);
            }
        }
    }

    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }
}
