<?php

namespace App\Services\OrderService;

use App\DTOs\Request\OrderRequestDto;
use App\DTOs\Response\Client\OrderClientResponseDto;
use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService implements Api\OrderServiceInterface
{

    public function placeOrder(OrderRequestDto $orderRequestDto): OrderClientResponseDto
    {
        $clientId = $orderRequestDto->clientId;
        $client = User::find($clientId);
        if(!$client){
            throw new \Exception("Client not found");
        }
        DB::beginTransaction();

        $order = new Order();
        $order->address = $orderRequestDto->address;
        $order->client_id = $orderRequestDto->clientId;
        $orderStatus = OrderStatus::where(['name'=>OrderStatusType::Pending->value])->first();
        if(!$orderStatus){
            throw new \Exception('Order status "'.  OrderStatusType::Pending->value . '" not found');
        }
        $order->order_status_id = $orderStatus->id;
        $order->save();

        $orderItemDtos = $orderRequestDto->orderItems;

        foreach ($orderItemDtos as $orderItemDto) {
            $product = Product::find($orderItemDto->productId);
            if(!$product){
                throw new \Exception("Product with id '{$orderItemDto->productId}' not found");
            }
            $orderItem = new OrderItem();
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $orderItemDto->quantity;
            $orderItem->unit = $product->unit;
            $orderItem->is_whole_unit = $product->is_whole_unit;
            $orderItem->order_id = $order->id;
            $orderItem->save();
        }
        $order->load('orderItems');
        $total_amount = $order->orderItems->sum('total_amount');
        $order->update(['total_amount' => $total_amount]);
        $order->refresh();
        DB::commit();
        return OrderClientResponseDto::fromModel($order);
    }
}
