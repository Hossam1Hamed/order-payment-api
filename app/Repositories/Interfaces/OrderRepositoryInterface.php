<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated orders for a specific user, optionally filtered by status.
     */
    public function getForUser(int $userId, ?string $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create an order along with its items in a single transaction.
     */
    public function createWithItems(array $orderData, array $items): Order;

    /**
     * Update an order and optionally replace its items in a single transaction.
     */
    public function updateWithItems(int $id, array $orderData, ?array $items): Order;

    /**
     * Check whether an order has any associated payments.
     */
    public function hasPayments(int $orderId): bool;
}
