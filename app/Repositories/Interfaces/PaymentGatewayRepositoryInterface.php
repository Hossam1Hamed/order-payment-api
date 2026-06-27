<?php

namespace App\Repositories\Interfaces;

use App\Models\PaymentGateway;
use Illuminate\Support\Collection;

interface PaymentGatewayRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all active payment gateways.
     */
    public function getActiveGateways(): Collection;

    /**
     * Find a payment gateway by its unique code.
     */
    public function findByCode(string $code): ?PaymentGateway;
}
