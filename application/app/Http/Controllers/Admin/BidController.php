<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use App\Models\BidWinner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BidController extends Controller
{
    public function productBids($id)
    {
        $pageTitle = 'Auction Bid list';
        $bids = Bid::with('product', 'user', 'bidWinner')->where('product_id', $id)
            ->whereHas('product', function ($q) {
                $q->where('author_id', auth('admin')->id())
                    ->where('author_type', 1);
            })
            ->latest()->paginate(getPaginate());
        return view('Admin::bid.list', compact('bids', 'pageTitle'));
    }


    public function bidWinner()
    {
        $pageTitle = 'Winning Bid list';
        $winningBids = BidWinner::with('bid.product')
            ->latest()->paginate(getPaginate());
        return view('Admin::bid.winning_bid', compact('winningBids', 'pageTitle'));
    }
}
