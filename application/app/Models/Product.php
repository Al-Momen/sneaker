<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'features' => 'object',
    ];

    public function userAuthor()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function adminAuthor()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }


    public function getAuthorAttribute()
    {
        if ($this->author_type == 1) {
            return $this->adminAuthor;
        }
        return $this->userAuthor;
    }

    public function getAuthorNameAttribute()
    {
        if ($this->author_type == 1 && $this->adminAuthor) {
            return $this->adminAuthor->name;
        }

        if ($this->author_type == 2 && $this->userAuthor) {
            return $this->userAuthor->firstname . ' ' . $this->userAuthor->lastname;
        }

        return null;
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->oldest();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('quantity')->withTimestamps(); // optional
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot(['quantity', 'product_id', 'order_id', 'price', 'user_id'])
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function statusBadge($status)
    {
        $html = '';
        if ($this->status == 1) {
            $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
        } elseif($this->type == 2 && $this->type == 2) {
            $html = '<span class="badge badge--danger">' . trans('Expired') . '</span>';
        } else {
            $html = '<span class="badge badge--warning">' . trans('Inactive') . '</span>';
        }

        return $html;
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
