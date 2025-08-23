<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Constants\Status;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index($status = 'all')
    {
        $user = auth()->user();
        $query = Order::with(['product'])
            ->where('user_id', $user->id)
            ->searchable(['order_number'])
            ->latest();

        switch ($status) {
            case 'initial':
                $query->where('status', Status::ORDER_INITIATE);
                break;
            case 'pending':
                $query->where('status', Status::ORDER_PENDING);
                break;
            case 'approved':
                $query->where('status', Status::ORDER_SUCCESS);
                break;
            case 'processing':
                $query->where('status', Status::ORDER_PROCESSING);
                break;
            case 'delivered':
                $query->where('status', Status::ORDER_DELIVERED);
                break;
            case 'completed':
                $query->where('status', Status::ORDER_COMPLETED);
                break;
            case 'reject':
                $query->where('status', Status::ORDER_REJECT);
                break;
            case 'all':
                $query->whereIn('status', [Status::ORDER_SUCCESS, Status::ORDER_REJECT, Status::ORDER_PROCESSING, Status::ORDER_DELIVERED, Status::ORDER_COMPLETED, Status::ORDER_REJECT, Status::ORDER_INITIATE, Status::ORDER_PENDING]);
                break;
            default:
                break;
        }

        $orders = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.order_data', compact('orders'))->render(),
                'pagination' => $orders->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $orders])->render() : '',
            ]);
        }
        $pageTitle = 'Orders';
        return view('UserTemplate::orders.index', compact('orders', 'pageTitle'));
    }

    public function orderDetails($id)
    {
        $pageTitle = 'Order Details';
        $userId = auth()->id();
        $order = Order::with('products.userAuthor', 'shipping', 'products.adminAuthor')->where('user_id', $userId)
            ->latest()
            ->searchable(['product:title', 'order_number'])
            ->findOrFail($id);
        return view("UserTemplate::orders.details", compact('pageTitle', 'order'));
    }

    public function getOrder($status = 'all')
    {

        $pageTitle = 'Get Order';
        $userId = auth()->id();
        $query = Order::whereHas('products', function ($q) {
            $q->where('author_id', auth()->id())
                ->where('author_type', 2);
        })
            ->with(['products' => function ($q) {
                $q->where('author_id', auth()->id())
                    ->where('author_type', 2)
                    ->with('userAuthor'); // nested relation
            }])
            ->whereIn('status', [1, 3, 4, 5, 6])
            ->searchable(['order_number'])
            ->latest();


        switch ($status) {

            case 'approved':
                $query->where('status', Status::ORDER_SUCCESS);
                break;
            case 'processing':
                $query->where('status', Status::ORDER_PROCESSING);
                break;
            case 'delivered':
                $query->where('status', Status::ORDER_DELIVERED);
                break;
            case 'completed':
                $query->where('status', Status::ORDER_COMPLETED);
                break;
            case 'reject':
                $query->where('status', Status::ORDER_REJECT);
                break;
            case 'all':
                $query->whereIn('status', [Status::ORDER_SUCCESS, Status::ORDER_REJECT, Status::ORDER_PROCESSING, Status::ORDER_DELIVERED, Status::ORDER_COMPLETED]);
                break;
            default:
                break;
        }

        $orders = $query->paginate(getPaginate());
        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.get_order_data', compact('orders'))->render(),
                'pagination' => $orders->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $orders])->render() : '',
            ]);
        }
        $pageTitle = 'Get Orders';
        return view("UserTemplate::orders.get_order", compact('pageTitle', 'orders'));
    }

    public function getOrderDetails($id)
    {
        $pageTitle = 'Order Details';
        $userId = auth()->id();
        $order = Order::with('products.userAuthor', 'shipping', 'products.adminAuthor')
            ->latest()
            ->searchable(['product:title', 'order_number'])
            ->findOrFail($id);

        return view("UserTemplate::orders.get_order_details", compact('pageTitle', 'order'));
    }

    public function vendorStatusChange($status, $id)
    {
        $statusMap = match (true) {
            in_array($status, [3, 4]) => 1,
            $status == 6              => 5,
            default                   => 4,
        };

        $order = Order::where('status', $statusMap)
            ->findOrFail($id);

        if ($status == 3) {
            $this->priceRefund($status, $order->id);
        }
        if ($status == 6) {
            $this->distributeAuthorUserBalance($order->id);
        }


        $order->status = $status;
        $order->save();

        $notify[] = ['success', 'Product status updated successfully'];
        return back()->withNotify($notify);
    }

    public function priceRefund($status, $orderID)
    {
        $order = Order::with('shipping')->findOrFail($orderID);
        $user = User::findOrFail($order->user_id);
        $user->balance += $order->total_price;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Order canceled by' . auth()->user()->username;
        $adminNotification->click_url = urlPath('admin.orders.details', $order->id);
        $adminNotification->save();

        notify($user, "ORDER_CANCELED", [
            'price'        => showAmount($order->total_price),
            'order_number' => $order->order_number,
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $order->user_id;
        $transaction->amount = $order->total_price;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type = '+';
        $transaction->remark = 'order refund';
        $transaction->trx = $order->trx;
        $transaction->save();
        return 0;
    }

    public function distributeAuthorUserBalance($orderId)
    {
        $order = Order::with('products', 'shipping')->find($orderId);
        $productAmount = 0;
        $shippingAdded = false;

        foreach ($order->products as $product) {
            if ($product->author_type == 2) {
              
                // Product author user
                $pivotData = $product->pivot;
                $productAmount += $pivotData->price * $pivotData->quantity;


                if (!$shippingAdded) {
                    $productAmount += $order->shipping->charge;
                    $shippingAdded = true;
                }

                $author = User::where('id', $product->author_id)->first();
                $author->increment('balance', $productAmount);
            }
        }
        return 0;
    }
}
