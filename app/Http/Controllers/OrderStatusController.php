<?php

namespace App\Http\Controllers;

use App\DTOs\Response\Client\OrderClientResponseDto;
use App\DTOs\Response\OrderResponseDto;
use App\DTOs\Response\SupplierOrderResponseDto;
use App\Models\OrderStatus;
use App\Models\SupplierOrder;
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
    public function getProcessingOrderById(Request $request, int $id){
        $order = Order::with('client')
            ->whereHas('currentStatus', function ($query) use ($id){
                $query->where('name', OrderStatusType::Processing->value);
            })
            ->where('id', $id)
            ->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(OrderClientResponseDto::fromModel($order));
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
    public function getDeliveredSupplierOrders(Request $request)
    {
        $deliveredStatus = OrderStatus::where('name', OrderStatusType::Delivered->value)->first();

        if (!$deliveredStatus) {
            return response()->json(['error' => 'Delivered status not found.'], 404);
        }

        $supplierOrders = SupplierOrder::where('order_status_id', $deliveredStatus->id)->get();

        if ($supplierOrders->isEmpty()) {
            return response()->json(['message' => 'No delivered supplier orders found.'], 200);
        }

        return response()->json(SupplierOrderResponseDto::fromModelCollection($supplierOrders));
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
    public function getPendingOrders(Request $request)
    {
        $orders = Order::with('currentStatus')
            ->whereHas('currentStatus', function ($query) {
                $query->where('name', OrderStatusType::Pending->value);
            })
            ->get();

        return response()->json(OrderResponseDto::fromCollection($orders));
    }
    public function markOrderAsProcessing(Request $request, int $orderId){
        $user = Auth::user();
        $order = Order::findOrFail($orderId);

        if ($order->currentStatus->name !== OrderStatusType::Pending->value) {
            return response()->json(['error' => 'Order is not ready for shipping'], 400);
        }

        $this->orderStatusService->changeOrderStatus($order, OrderStatusType::Processing->value, $user);

        return response(null,200);
    }
    public function markOrderAsCancelled(Request $request, int $orderId){
        $user = Auth::user();
        $order = Order::findOrFail($orderId);
        $this->orderStatusService->changeOrderStatus($order, OrderStatusType::Cancelled->value, $user);

        return response(null,200);
    }
}
