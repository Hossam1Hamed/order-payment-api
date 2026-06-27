<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    /**
     * GET /api/orders
     * Returns paginated orders for the authenticated user.
     * Accepts optional query param: ?status=pending|confirmed|cancelled
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->listOrders(
            userId:  auth()->id(),
            status:  $request->query('status'),
            perPage: (int) $request->query('per_page', 15),
        );

        return $this->successResponse(
            data: $orders,
            message: 'Orders retrieved successfully',
        );
    }

    /**
     * GET /api/orders/{id}
     * Returns a single order (with items and payments).
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->findOrder($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        return $this->successResponse(
            data: $order,
            message: 'Order retrieved successfully',
        );
    }

    /**
     * POST /api/orders
     * Creates a new order for the authenticated user.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder(
            userId: auth()->id(),
            data:   $request->validated(),
        );

        return $this->createdResponse(
            data: $order,
            message: 'Order created successfully',
        );
    }

    /**
     * PUT/PATCH /api/orders/{id}
     * Updates status and/or replaces items.
     */
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->findOrder($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        $order = $this->orderService->updateOrder($id, $request->validated());

        return $this->successResponse(
            data: $order,
            message: 'Order updated successfully',
        );
    }

    /**
     * DELETE /api/orders/{id}
     * Deletes an order — rejected if it has associated payments.
     */
    public function destroy(int $id): JsonResponse
    {
        $order = $this->orderService->findOrder($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        $this->orderService->deleteOrder($id);

        return $this->noContentResponse();
    }
}
