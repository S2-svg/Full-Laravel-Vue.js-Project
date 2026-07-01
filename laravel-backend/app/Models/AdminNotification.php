<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'message',
        'order_id',
        'product_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ── Scopes ──

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ── Relationships ──

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ── Low-Stock Check ──

    /**
     * Check all products and create low-stock notifications for any
     * product whose stock has dropped to or below the given threshold.
     *
     * Skips products that already have an unread low_stock notification
     * so the admin is not spammed.
     */
    public static function checkLowStock(int $threshold = 5): void
    {
        $lowStockProducts = Product::where('stock', '<=', $threshold)->get();

        // Get product IDs that already have an unread low_stock notification
        $alreadyNotified = static::unread()
            ->ofType('low_stock')
            ->whereNotNull('product_id')
            ->pluck('product_id')
            ->unique()
            ->all();

        foreach ($lowStockProducts as $product) {
            if (in_array($product->id, $alreadyNotified)) {
                continue;
            }

            $label = $product->stock === 0 ? 'Out of stock' : 'Low stock';

            static::create([
                'type'       => 'low_stock',
                'message'    => "{$label}: {$product->name} — {$product->stock} left",
                'product_id' => $product->id,
            ]);
        }
    }
}
