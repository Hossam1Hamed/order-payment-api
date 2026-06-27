<?php

namespace App\Services;

use App\Exceptions\OrderHasPaymentsException;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
    ) {}

    /**
     * Return a paginated list of orders for the authenticated user.
     */
    public function listOrders(int $userId, ?string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getForUser($userId, $status, $perPage);
    }

    /**
     * Find a single order by ID (with relationships loaded).
     */
    public function findOrder(int $id): ?Order
    {
        /** @var Order|null */
        return $this->orderRepository->find($id)?->load(['items', 'payments']);
    }

    /**
     * Create a new order.
     * Total is automatically calculated from the provided items.
     */
    public function createOrder(int $userId, array $data): Order
    {
        $items = $data['items'];

        $total = collect($items)->sum(
            fn(array $item): float => $item['quantity'] * $item['price'],
        );

        return $this->orderRepository->createWithItems(
            orderData: [
                'user_id' => $userId,
                'status'  => Order::STATUS_PENDING,
                'total'   => $total,
            ],
            items: $items,
        );
    }

    /**
     * Update an existing order.
     * If items are provided, the total is recalculated automatically.
     */
    public function updateOrder(int $id, array $data): Order
    {
        $orderData = [];
        $items     = $data['items'] ?? null;

        if (isset($data['status'])) {
            $orderData['status'] = $data['status'];
        }

        if (!is_null($items)) {
            $orderData['total'] = collect($items)->sum(
                fn(array $item): float => $item['quantity'] * $item['price'],
            );
        }

        return $this->orderRepository->updateWithItems($id, $orderData, $items);
    }

    /**
     * Delete an order.
     *
     * @throws OrderHasPaymentsException if the order has associated payments.
     */
    public function deleteOrder(int $id): void
    {
        if ($this->orderRepository->hasPayments($id)) {
            throw new OrderHasPaymentsException();
        }

        $this->orderRepository->delete($id);
    }
}
