<?php

namespace App\Services\OrderService;

use App\DTOs\Response\Client\OrderStatusHistoryResponseDto;
use App\Models\Order;
use App\Models\OrderStatusesHistory;
use App\Models\OrderStatus;
use App\Models\User;
use App\Services\OrderService\Api\OrderStatusServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrderStatusService implements OrderStatusServiceInterface
{
    public function changeOrderStatus(Order $order, string $status, ?User $changedBy): void
    {
        $orderStatus = OrderStatus::where('name', $status)->first();

        if (!$orderStatus) {
            throw new \Exception("Invalid status: {$status}");
        }

        $order->order_status_id = $orderStatus->id;
        $order->save();

        OrderStatusesHistory::create([
            'order_id' => $order->id,
            'updated_at' => now(),
            'status' => $orderStatus->id,
            'changed_by' => $changedBy ? $changedBy->id : null,
        ]);
    }

    public function getOrderStatusHistory(Order $order): array
    {
        $history = $order->orderStatusHistory()
            ->orderBy('updated_at', 'desc')
            ->with(['status', 'changedBy'])
            ->get();
        if (!$history instanceof Collection) {
            throw new \Exception("Expected Collection, got " . gettype($history));
        }

        return OrderStatusHistoryResponseDto::fromCollection($history);
    }
}
