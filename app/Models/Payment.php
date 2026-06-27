<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // ── Status constants ────────────────────────────────────────────────────
    const STATUS_PENDING    = 'pending';
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_FAILED     = 'failed';

    // ── Method constants ────────────────────────────────────────────────────
    const METHOD_CREDIT_CARD    = 'credit_card';
    const METHOD_PAYPAL         = 'paypal';
    const METHOD_STRIPE         = 'stripe';
    const METHOD_BANK_TRANSFER  = 'bank_transfer';

    // ── Mass assignable ─────────────────────────────────────────────────────
    protected $fillable = [
        'order_id',
        'payment_gateway_id',
        'status',
        'method',
        'gateway_response',
    ];

    // ── Casts ───────────────────────────────────────────────────────────────
    protected $casts = [
        'gateway_response' => 'array',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    /**
     * A payment belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESSFUL;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
