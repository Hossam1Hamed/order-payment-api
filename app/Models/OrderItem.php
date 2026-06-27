<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // ── Mass assignable ─────────────────────────────────────────────────────
    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'price',
    ];

    // ── Casts ───────────────────────────────────────────────────────────────
    protected $casts = [
        'quantity'   => 'integer',
        'price'      => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /**
     * An order item belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Calculate the subtotal for this line item.
     */
    public function subtotal(): float
    {
        return $this->quantity * $this->price;
    }
}
