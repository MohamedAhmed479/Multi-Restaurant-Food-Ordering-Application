<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product')
                    ->withPivot('quantity', 'price', 'total_price')
                    ->withTimestamps();
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);  
    }

}