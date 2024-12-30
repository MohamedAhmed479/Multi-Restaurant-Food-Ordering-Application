<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantBalance extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'restaurant_balances';

    // Relationship with Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Client::class);
    }
}
