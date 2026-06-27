<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Create a payment record for an order.
     */
    public function createPayment(array $data): Payment;
}
