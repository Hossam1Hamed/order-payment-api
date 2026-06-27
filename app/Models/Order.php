<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // ── Status constants ────────────────────────────────────────────────────
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    // ── Mass assignable ─────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'status',
        'total',
    ];

    // ── Casts ───────────────────────────────────────────────────────────────
    protected $casts = [
        'total'      => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /**
     * An order belongs to a user.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An order has many order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * An order has many payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
