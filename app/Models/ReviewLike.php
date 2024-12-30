<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewLike extends Model
{
    use HasFactory;

    protected $guarded = [];

    // علاقة مع المراجعات
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // علاقة مع المستخدمين
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
