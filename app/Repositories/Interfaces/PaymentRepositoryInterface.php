<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Create a payment record for an order.
     */
    public function createPayment(array $data): Payment;

    /**
     * Get all payments for a specific order.
     */
    public function getForOrder(int $orderId): \Illuminate\Support\Collection;

    /**
     * Get all payments paginated.
     */
    public function getAllPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
