<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\BidWinner;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class CronController extends Controller
{
    public function winners()
    {
        $products = Product::where('type', 2)->where('status', 1)->where('end_date', '<', now())->get();
        foreach ($products as $product) {
            $highestBid = Bid::with('user')->where('product_id', $product->id)->orderBy('price', 'desc')->first();
            $winner   = $highestBid ? $highestBid->user : '';
            $allBids = Bid::whereNot('id', $highestBid->id)->where('product_id', $product->id)->get();
            
            if ($product->author_type == 2) {
                $owner = User::find($product->author_id);
                $owner->balance += $highestBid->price;
                $owner->save();
                
                notify($owner, 'AUCTION_ENDED_OWNER_NOTIFICATION', [
                    'auction_owner' => $owner->fullname,
                    'auction_product_name' => $product->name,
                    'winner_bid_price' => showAmount($highestBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($allBids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->name), 'id' => $product->id])
                ]);
            }
            
            if ($winner) {
                notify($winner, 'AUCTION_WINNER_NOTIFICATION', [
                    'auction_product_name' => $product->name,
                    'winner_bid_price' => showAmount($highestBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($allBids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->name), 'id' => $product->id])
                ]);
            }

            foreach ($allBids as $bidder) {
                $participant = User::findOrFail($bidder->user_id);
                $participant->balance += $bidder->price;
                $participant->save();

                notify($participant, 'NON_WINNER_AUCTION_NOTIFICATION', [
                    'participant_name' => $participant->fullname,
                    'auction_product_name' => $product->name,
                    'winner_bid_price' => showAmount($highestBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($allBids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->name), 'id' => $product->id])
                ]);
            }

            $winnerData = new BidWinner();
            $winnerData->user_id = $winner->id;
            $winnerData->product_id = $product->id;
            $winnerData->bid_id = $highestBid->id;
            $winnerData->status = 0;
            $winnerData->save();

            $product->status = 2; // ========================= auction expired ======================================
            $product->save();
        }
    }



}
