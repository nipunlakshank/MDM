<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->user_id = Auth::id();
        });
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
