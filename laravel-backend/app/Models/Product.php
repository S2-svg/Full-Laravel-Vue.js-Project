<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_percent',
        'discount_start_at',
        'discount_end_at',
        'stock',
        'image',
    ];

    protected $appends = ['final_price', 'has_discount', 'discount_status'];

    protected $casts = [
        'discount_percent' => 'integer',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'price' => 'float',
    ];

    /**
     * Whether the discount is currently active (within the scheduled window).
     */
    public function getIsDiscountActiveAttribute(): bool
    {
        if ($this->discount_percent <= 0) {
            return false;
        }

        $now = Carbon::now();

        // If start is set and we haven't reached it yet, not active
        if ($this->discount_start_at && $now->lt($this->discount_start_at)) {
            return false;
        }

        // If end is set and we've passed it, not active
        if ($this->discount_end_at && $now->gt($this->discount_end_at)) {
            return false;
        }

        return true;
    }

    /**
     * Get the final price after discount (only applies if discount is active).
     */
    public function getFinalPriceAttribute(): float
    {
        if (!$this->is_discount_active) {
            return (float) $this->price;
        }

        return round($this->price * (1 - $this->discount_percent / 100), 2);
    }

    /**
     * Whether the product has an active discount (visible to customers).
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->is_discount_active;
    }

    /**
     * Human-readable discount status: 'active', 'scheduled', 'expired', or 'none'.
     */
    public function getDiscountStatusAttribute(): string
    {
        if ($this->discount_percent <= 0) {
            return 'none';
        }

        $now = Carbon::now();

        if ($this->discount_end_at && $now->gt($this->discount_end_at)) {
            return 'expired';
        }

        if ($this->discount_start_at && $now->lt($this->discount_start_at)) {
            return 'scheduled';
        }

        return 'active';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
