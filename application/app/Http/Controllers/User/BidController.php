<?php

namespace App\Http\Controllers\user;

use App\Models\Bid;
use App\Models\Product;
use App\Constants\Status;
use App\Models\BidWinner;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class BidController extends Controller
{

    public function auctionProduct($status = 'all')
    {
        $user = auth()->user();
        $query = Product::with(['category', 'firstImage', 'wishlists'])
            ->where('author_id', $user->id)
            ->where('author_type', 2)
            ->where('type', 2)
            ->searchable(['name'])
            ->latest();

        switch ($status) {
            case 'disable':
                $query->where('status', Status::DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::ENABLE);
                break;
            case 'all':
                $query->whereIn('status', [Status::ENABLE, Status::DISABLE]);
                break;
            default:
                break;
        }

        $products = $query->paginate(getPaginate());
        if (request()->ajax()) {
            return response()->json([
                'html' => view('Template::components.user.tables.product_data', compact('products'))->render(),
                'pagination' => $products->hasPages() ? view('Template::components.user.tables.pagination', ['items' => $products])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Products';
        return view('UserTemplate::product.auction', compact('products', 'pageTitle'));
    }

    public function list($id)
    {
        $pageTitle = 'Auction Bid list';
        $bids = Bid::with('product', 'user', 'bidWinner')->where('product_id', $id)
            ->whereHas('product', function ($q) {
                $q->where('author_id', auth()->id())
                    ->where('author_type', 2);
            })
            ->latest()->paginate(getPaginate());
        return view('UserTemplate::bid.list', compact('bids', 'pageTitle'));
    }


    public function winningHistory()
    {
        $pageTitle = 'Winning Bid list';
        $winningBids = BidWinner::with('bid.product')->where('user_id',auth()->id())
            ->latest()->paginate(getPaginate());
        return view('UserTemplate::bid.winning_bid', compact('winningBids', 'pageTitle'));
    }

    public function bid(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'price'      => ['required', 'numeric', 'min:0']
        ]);

        $product = Product::with('bids')->where('id', $request->product_id)->where('status', 1)->first();
        $highestBid = $product->bids->sortByDesc('price')->first()->price ?? $product->min_price;


        $user = auth()->user();

        $bidingCheck = [
            ['condition' => $product->type == 1, 'message' => 'Product is not available to action'],
            ['condition' => $user->balance < $request->price, 'message' => 'You do not have sufficient balance'],
            ['condition' => $highestBid > $request->price, 'message' => 'Your bidding price is lower than product price.'],
            ['condition' => in_array($product->status, [0, 2, 3]), 'message' => 'Bidding not possible now'],
            ['condition' => $product->user_id == $user->id, 'message' => 'You can not bid your auction'],
            ['condition' => $product->start_date > now(), 'message' => 'Auction has not started yet'],
            ['condition' => $product->end_date < now(), 'message' => 'Auction has already ended'],
        ];

        foreach ($bidingCheck as $check) {
            if ($check['condition']) {
                $notify[] = ['error', $check['message']];
                return back()->withNotify($notify);
            }
        }

        $check = Bid::where('user_id', $user->id)->where('product_id', $product->id)->first();
        if ($check) {
            if (intval($check->price) >= intval($request->price)) {
                $notify[] = ['error', 'Update your bid price to a higher amount to participate again.'];
                return redirect()->back()->withNotify($notify);
            }

            // when user two times bidding one product
            $updateAmount = (intval($request->price) - intval($check->price));
            $user->balance -= $updateAmount;
            $user->save();

            // when user two times bidding one product
            $check->price = $request->price;
            $check->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $updateAmount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Subtracted for updating the previous bid';
            $transaction->trx = getTrx();
            $transaction->remark = 'bid';
            $transaction->save();

            $notify[] = ['success', 'Your bid price has been successfully updated.'];
            return redirect()->back()->withNotify($notify);
        }


        $bid = new Bid();
        $bid->product_id = $product->id;
        $bid->user_id    = $user->id;
        $bid->price      = $request->price;
        $bid->save();

        $user->balance -= $request->price;
        $user->save();

        $product->bid_count += 1;
        $product->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $request->price;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type = '-';
        $transaction->details = 'Subtracted for a new bid';
        $transaction->trx = getTrx();
        $transaction->remark = 'bid';
        $transaction->save();

        if ($product->user_id == 0) {
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'A user has placed a bid on your product.';
            $adminNotification->click_url = urlPath('admin.bid.list', $product->id);
            $adminNotification->save();
        }

        $notify[] = ['success', 'Bid placed successfully. Thank you for participating!'];
        return redirect()->back()->withNotify($notify);
    }
}
