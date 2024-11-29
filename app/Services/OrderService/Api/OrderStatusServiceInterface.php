<?php

namespace App\Services\OrderService\Api;

use App\Models\Order;
use App\Models\User;

interface OrderStatusServiceInterface
{

    public function changeOrderStatus(Order $order, string $status, ?User $changedBy): void;
    public function getOrderStatusHistory(Order $order): array;
}
