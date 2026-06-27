<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    // ── Code constants ──────────────────────────────────────────────────────
    const CODE_STRIPE   = 'stripe';
    const CODE_PAYPAL   = 'paypal';

    // ── Mass assignable ─────────────────────────────────────────────────────
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'config',
    ];

    // ── Casts ───────────────────────────────────────────────────────────────
    protected $casts = [
        'is_active' => 'boolean',
        'config'    => 'array',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Scope a query to only include active payment gateways.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
