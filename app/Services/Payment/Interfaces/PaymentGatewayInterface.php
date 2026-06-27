<?php

namespace App\Services\Payment\Interfaces;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\Dto\PaymentResponse;

interface PaymentGatewayInterface
{
    /**
     * Process a payment for a specific order.
     */
    public function process(Order $order, array $paymentDetails): PaymentResponse;

    /**
     * Refund a processed payment.
     */
    public function refund(Payment $payment, float $amount): PaymentResponse;
}
