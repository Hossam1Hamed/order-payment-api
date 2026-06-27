<?php

namespace App\Services;

use App\Exceptions\OrderNotConfirmedException;
use App\Exceptions\PaymentFailedException;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Services\Payment\PaymentGatewayManager;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
        protected PaymentGatewayManager $gatewayManager
    ) {}

    /**
     * Process a payment for a specific order.
     *
     * @throws OrderNotConfirmedException
     * @throws PaymentFailedException
     */
    public function processPayment(Order $order, array $data): Payment
    {
        // 1. Validate order is confirmed
        if ($order->status !== Order::STATUS_CONFIRMED) {
            throw new OrderNotConfirmedException();
        }

        $method = $data['method'];

        // 2. Resolve gateway via manager
        $gateway = $this->gatewayManager->gateway($method);

        // 3. Call gateway->charge()
        $response = $gateway->charge((float) $order->total, $data);

        // 4. Store result in payments table
        $paymentData = [
            'order_id'           => $order->id,
            'payment_gateway_id' => $response->transactionId ?? 'failed_' . bin2hex(random_bytes(5)),
            'status'             => $response->success ? Payment::STATUS_SUCCESSFUL : Payment::STATUS_FAILED,
            'method'             => $method,
            'gateway_response'   => $response->rawResponse ?? ['error' => $response->errorMessage],
        ];

        $payment = $this->paymentRepository->createPayment($paymentData);

        if (!$response->success) {
            throw new PaymentFailedException(
                message: $response->errorMessage ?? 'Payment processing failed.',
                errors: $response->rawResponse
            );
        }

        return $payment;
    }

    /**
     * Retrieve all payments for a specific order.
     */
    public function getPaymentsForOrder(int $orderId): \Illuminate\Support\Collection
    {
        return $this->paymentRepository->getForOrder($orderId);
    }

    /**
     * Retrieve all payments paginated.
     */
    public function getAllPayments(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->paymentRepository->getAllPaginated($perPage);
    }
}
