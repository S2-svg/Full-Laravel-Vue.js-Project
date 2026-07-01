<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total',
        'vat_total',
    ];

    protected $appends = [
        'grand_total',
    ];

    public function getGrandTotalAttribute(): float
    {
        return (float) $this->total + (float) $this->vat_total;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
