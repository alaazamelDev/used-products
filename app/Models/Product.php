<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed name
 * @property mixed image_url
 * @property mixed exp_date
 * @property mixed description
 * @property mixed views
 * @property mixed phone_number
 * @property mixed quantity
 * @property mixed price
 * @property mixed category_id
 * @property mixed user_id
 * @property mixed user
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'exp_date',
        'description', 'views', 'phone_number', 'quantity',
        'price', 'category_id', 'user_id'];

    protected $appends = ['new_price', 'is_editable', 'liked', 'likes_count'];

    protected $with = ['reviews'];

    protected $withCount = ['reviews'];

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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class)->orderBy('date');
    }

    /*
     * used to append new price field with response
     * after applying the discount on the original price
     * */
    public function getNewPriceAttribute()
    {
        $newPrice = $this->price;
        $discounts = $this->discounts; // list of discounts
        foreach ($discounts as $discount) {
            if (now() >= Carbon::parse($discount->date)) {
                // we entered the range of this discount, so we apply the appropriate discount
                $newPrice = $this->price - ($this->price * $discount->discount_percentage) / 100;
            }
        }
        return $newPrice;
    }

    public function getLikedAttribute(): bool
    {
        return Like::query()
            ->where('user_id', '=', Auth::id())
            ->where('product_id', '=', $this->id)
            ->exists();
    }

    public function getLikesCountAttribute(): int
    {
        return Like::query()
            ->where('product_id', '=', $this->id)
            ->count();
    }

    /*
     * used to return if the current product is editable
     * by the user which has sent the request or not
     * */
    public function getIsEditableAttribute(): bool
    {
        return $this->user_id == Auth::id();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes');
    }

}
