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
     * Cached discount state to avoid re-computing Carbon::now() multiple times
     * per model instance (called by final_price, has_discount, discount_status).
     *
     * Stores one of: 'active', 'expired', 'scheduled', 'none', or null (uncomputed).
     */
    protected ?string $cachedDiscountState = null;

    /**
     * Compute the discount state once and cache it on the instance.
     */
    protected function computeDiscountState(): string
    {
        if ($this->cachedDiscountState !== null) {
            return $this->cachedDiscountState;
        }

        if ($this->discount_percent <= 0) {
            return $this->cachedDiscountState = 'none';
        }

        $now = Carbon::now();

        if ($this->discount_end_at && $now->gt($this->discount_end_at)) {
            return $this->cachedDiscountState = 'expired';
        }

        if ($this->discount_start_at && $now->lt($this->discount_start_at)) {
            return $this->cachedDiscountState = 'scheduled';
        }

        return $this->cachedDiscountState = 'active';
    }

    /**
     * Whether the discount is currently active (within the scheduled window).
     */
    public function getIsDiscountActiveAttribute(): bool
    {
        return $this->computeDiscountState() === 'active';
    }

    /**
     * Get the final price after discount (only applies if discount is active).
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->computeDiscountState() !== 'active') {
            return (float) $this->price;
        }

        return round($this->price * (1 - $this->discount_percent / 100), 2);
    }

    /**
     * Whether the product has an active discount (visible to customers).
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->computeDiscountState() === 'active';
    }

    /**
     * Human-readable discount status: 'active', 'scheduled', 'expired', or 'none'.
     */
    public function getDiscountStatusAttribute(): string
    {
        return $this->computeDiscountState();
    }

    /**
     * Reset the cached discount state (useful if the model's discount fields are modified after loading).
     */
    public function resetDiscountState(): void
    {
        $this->cachedDiscountState = null;
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
