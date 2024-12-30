<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    public function resturant()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }


    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id','id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')
                    ->withPivot('quantity', 'price', 'total_price')
                    ->withTimestamps();
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->usingSeparator('-');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function (Product $product) {
            $product->code  = 'PRD-' . strtoupper(Str::random(6));
        });
    }
}
