<?php

namespace App\Services\Payment\Contracts;

use App\Services\Payment\Dto\PaymentResponse;

interface PaymentGatewayInterface
{
    /**
     * Charge a specific amount.
     */
    public function charge(float $amount, array $data): PaymentResponse;

    /**
     * Refund a specific transaction amount.
     */
    public function refund(string $paymentGatewayId, float $amount): PaymentResponse;

    /**
     * Get the name of the payment gateway.
     */
    public function getName(): string;
}
