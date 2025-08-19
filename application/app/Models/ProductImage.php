<?php

namespace App\Models;

use App\Models\Color;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'image', 'color_id'];
    protected $casts = [
        'image' => 'array',
    ];
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
