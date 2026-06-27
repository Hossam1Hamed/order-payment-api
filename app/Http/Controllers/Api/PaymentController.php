<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected OrderService $orderService
    ) {}

    /**
     * GET /api/payments
     * Get all payments paginated.
     */
    public function index(Request $request): JsonResponse
    {
        $payments = $this->paymentService->getAllPayments(
            perPage: (int) $request->query('per_page', 15)
        );

        return $this->successResponse(
            data: $payments,
            message: 'All payments retrieved successfully'
        );
    }

    /**
     * GET /api/orders/{order}/payments
     * Get payments for a specific order.
     */
    public function orderPayments(int $orderId): JsonResponse
    {
        $order = $this->orderService->findOrder($orderId);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        $payments = $this->paymentService->getPaymentsForOrder($orderId);

        return $this->successResponse(
            data: $payments,
            message: 'Payments for this order retrieved successfully'
        );
    }

    /**
     * POST /api/orders/{order}/payments
     * Process a payment for a specific order.
     */
    public function store(StorePaymentRequest $request, int $orderId): JsonResponse
    {
        $order = $this->orderService->findOrder($orderId);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        $payment = $this->paymentService->processPayment($order, $request->validated());

        return $this->createdResponse(
            data: $payment,
            message: 'Payment processed successfully'
        );
    }
}
