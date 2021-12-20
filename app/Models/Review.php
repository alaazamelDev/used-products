<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed content
 * @property mixed product_id
 * @property mixed user_id
 * @property mixed user
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'product_id', 'user_id'];


    /***
     * This function returns the product which the review belongs To
     ***/
    public function product(): BelongsTo
    {
        // can be read as follow: this review belongs to "a single" product
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
