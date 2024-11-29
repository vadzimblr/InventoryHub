<?php

namespace App\Http\Controllers;

use App\DTOs\Response\Client\OrderClientResponseDto;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatusType;
use App\Services\OrderService\Api\OrderStatusServiceInterface;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    private OrderStatusServiceInterface $orderStatusService;

    public function __construct(OrderStatusServiceInterface $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * Accountant: Get all orders in specific status
     */
    public function getOrdersByStatus(Request $request, string $status)
    {
        $orders = Order::with('client')
            ->whereHas('currentStatus', function ($query) use ($status) {
                $query->where('name', $status);
            })
            ->get();

        return response()->json(OrderClientResponseDto::fromCollection($orders));
    }

    public function updateOrderStatus(Request $request, int $orderId)
    {
        $user = $request->user();
        $newStatus = $request->input('status');

        if (!OrderStatusType::tryFrom($newStatus)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $order = Order::findOrFail($orderId);

        $this->orderStatusService->changeOrderStatus($order, $newStatus, $user);

        return response(null,200);
    }

    /**
     * Warehouse Manager: Mark order as shipped
     */
    public function markOrderAsShipped(Request $request, int $orderId)
    {
        $user = Auth::user();
        $order = Order::findOrFail($orderId);

        if ($order->currentStatus->name !== OrderStatusType::Picking->value) {
            return response()->json(['error' => 'Order is not ready for shipping'], 400);
        }

        $this->orderStatusService->changeOrderStatus($order, OrderStatusType::Shipped->value, $user);

        return response(null,200);
    }

    /**
     * Client: View order history
     */
    public function getOrderHistory(Request $request, int $orderId)
    {
        $user = $request->user();
        $order = Order::where('id', $orderId)
            ->where('client_id', $user->id)
            ->firstOrFail();

        $history = $this->orderStatusService->getOrderStatusHistory($order);

        return response()->json($history);
    }
}
