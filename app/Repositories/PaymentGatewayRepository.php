<?php

namespace App\Repositories;

use App\Models\PaymentGateway;
use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use Illuminate\Support\Collection;

class PaymentGatewayRepository extends BaseRepository implements PaymentGatewayRepositoryInterface
{
    public function __construct(PaymentGateway $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all active payment gateways.
     */
    public function getActiveGateways(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Find a payment gateway by its unique code.
     */
    public function findByCode(string $code): ?PaymentGateway
    {
        /** @var PaymentGateway|null */
        return $this->model->where('code', $code)->first();
    }
}
