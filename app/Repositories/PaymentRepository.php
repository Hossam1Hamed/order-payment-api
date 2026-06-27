<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * Create a payment record for an order.
     */
    public function createPayment(array $data): Payment
    {
        return $this->create($data);
    }

    /**
     * Get all payments for a specific order.
     */
    public function getForOrder(int $orderId): \Illuminate\Support\Collection
    {
        return $this->model->where('order_id', $orderId)->latest()->get();
    }

    /**
     * Get all payments paginated.
     */
    public function getAllPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model->with('order')->latest()->paginate($perPage);
    }
}
