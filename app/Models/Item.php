<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'brand_id',
        'category_id',
        'attachment',
        'price',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->user_id = Auth::id();
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
