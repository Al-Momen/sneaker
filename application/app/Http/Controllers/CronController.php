<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\User;
use App\Models\Winner;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function winners()
    {
        $products = Product::where('type', 1)->where('status', 1)->where('expired_at', '<', now())->take(20)->get();
        foreach ($products as $product) {
            $heightBid = Bid::where('product_id', $product->id)->orderBy('price', 'desc')->first();
            $winner = User::findOrFail($heightBid->bidder_id);
            $bids = Bid::whereNot('id', $heightBid->id)->where('product_id', $product->id)->where('product_creator_id', $product->user_id)->get();

            if ($product->user_id != 0) {
                $owner = User::findOrFail($product->user_id);
                $owner->balance += $heightBid->price;
                $owner->save();


                notify($owner, 'AUCTION_ENDED_OWNER_NOTIFICATION', [
                    'auction_owner' => $owner->fullname,
                    'auction_product_name' => $product->title,
                    'winner_bid_price' => showAmount($heightBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($bids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->title), 'id' => $product->id])
                ]);
            }

            if ($winner) {
                notify($winner, 'AUCTION_WINNER_NOTIFICATION', [
                    'auction_product_name' => $product->title,
                    'winner_bid_price' => showAmount($heightBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($bids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->title), 'id' => $product->id])
                ]);
            }

            foreach ($bids as $bidder) {
                $participant = User::findOrFail($bidder->bidder_id);

                $participant->balance += $bidder->price;
                $participant->save();


                notify($participant, 'NON_WINNER_AUCTION_NOTIFICATION', [
                    'participant_name' => $participant->fullname,
                    'auction_product_name' => $product->title,
                    'winner_bid_price' => showAmount($heightBid->price, 2),
                    'winner_name' => $winner->fullname,
                    'auction_concluded_date' => now(),
                    'total_bids' => ($bids->count() + 1),
                    'link' => route('product.details', ['slug' => Str::slug($product->title), 'id' => $product->id])
                ]);
            }

            $winnerData = new Winner();
            $winnerData->user_id = $winner->id;
            $winnerData->product_id = $product->id;
            $winnerData->product_owner_id = $product->user_id;
            $winnerData->bid_id = $heightBid->id;
            $winnerData->status = 0;
            $winnerData->save();


            $product->status = 2;
            $product->save();
        }
    }


    // Reduce product price based on interval and logic'
    public function reducePrice()
    {
        // Only fetch products that match the conditions
        $products = Product::where(function ($query) {
            $query->whereNotNull('last_price')
                ->orWhereNotNull('interval_type')
                ->orWhereNotNull('interval_time');
        })
        ->where('status', 1)
        ->where('type', 2)
        ->whereColumn('less_price', '<', 'price')
        ->get();
       

        Log::info($products);

        $updatedCount = 0;

        foreach ($products as $product) {
            $totalMinutes = $this->calculateIntervalInMinutes($product->interval_type,$product->interval_time);

            if ($product->last_price >= $product->price) {
                continue;
            }

            $lastDate = $product->last_date ?? $product->created_at;
            
            if ($totalMinutes && Carbon::parse($lastDate)->diffInMinutes(now()) <= $totalMinutes) {
                continue;
            }

            $newPrice = $product->price - $product->less_price;
            $product->price = ($newPrice > $product->last_price) ? $newPrice : $product->last_price;
            $product->last_date = now();
            $product->save();
            $updatedCount++;
        }
    }

    function calculateIntervalInMinutes(int $type, int $time): int
    {
        switch ($type) {
            case 1: 
                return $time;
            case 2: 
                return $time * 60;
            case 3: 
                return $time * 1440; 
            case 4: 
                return $time * 43200; 
            default:
                return 0;
        }
    }
}
