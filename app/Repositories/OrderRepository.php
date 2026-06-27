<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated orders for a specific user, optionally filtered by status.
     */
    public function getForUser(int $userId, ?string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['items', 'payments'])
            ->where('user_id', $userId)
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create an order along with its items in a single transaction.
     */
    public function createWithItems(array $orderData, array $items): Order
    {
        return DB::transaction(function () use ($orderData, $items): Order {
            /** @var Order $order */
            $order = $this->model->create($orderData);
            $order->items()->createMany($items);

            return $order->load(['items', 'payments']);
        });
    }

    /**
     * Update an order and optionally replace its items in a single transaction.
     */
    public function updateWithItems(int $id, array $orderData, ?array $items): Order
    {
        return DB::transaction(function () use ($id, $orderData, $items): Order {
            /** @var Order $order */
            $order = $this->find($id);
            $order->update($orderData);

            if (!is_null($items)) {
                $order->items()->delete();
                $order->items()->createMany($items);
            }

            return $order->fresh(['items', 'payments']);
        });
    }

    /**
     * Check whether an order has any associated payments.
     */
    public function hasPayments(int $orderId): bool
    {
        return Payment::where('order_id', $orderId)->exists();
    }
}
