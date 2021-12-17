<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'exp_date',
        'description', 'views', 'phone_number', 'quantity',
        'price', 'category_id', 'user_id'];


    /**
     * Returns category which this product belongs To
     **/
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Returns list of reviews which are related to this product
     **/
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Returns user which owns this product
     **/
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
