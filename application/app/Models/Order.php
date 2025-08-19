<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot(['quantity', 'product_id', 'order_id', 'price', 'user_id'])
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadge($status)
    {
        $html = '';
        if ($this->status == 0) {
            $html = '<span class="badge badge--primary">' . trans('Initiated') . '</span>';
        } elseif ($this->status == 1) {
            $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
        } elseif ($this->status == 2) {
            $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
        } elseif ($this->status == 3) {
            $html = '<span><span class="badge badge--danger">' . trans('Cancelled') . '</span></span>';
        } elseif ($this->status == 4) {
            $html = '<span><span class="badge badge--info">' . trans('Processing') . '</span></span>';
        } elseif ($this->status == 5) {
            $html = '<span><span class="badge badge--violet">' . trans('Delivered') . '</span></span>';
        } else {
            $html = '<span><span class="badge badge--success">' . trans('Completed') . '</span></span>';
        }
        return $html;
    }
}
