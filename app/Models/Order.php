<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function resturant()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    // Relationship with Payment (one-to-many or one-to-one)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRouteKeyName()
    {
        return 'invoice_no';
    }
}
