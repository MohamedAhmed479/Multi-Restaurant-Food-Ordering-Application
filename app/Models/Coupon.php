<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function carts() {
        return $this->belongsToMany(Coupon::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }

    public function resturant() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
} 
