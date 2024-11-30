<?php

namespace App\Services\WaybillService;

use App\DTOs\Request\WaybillRequestDto;
use App\DTOs\Response\WaybillResponseDto;
use App\Enums\OrderStatusType;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Waybill;
use App\Services\ProductService\Api\ProductServiceInterface;
use Illuminate\Support\Facades\DB;

readonly class WaybillService implements Api\WaybillServiceInterface
{
    public function __construct(
        private ProductServiceInterface $productService,
    ) {
    }

    public function createWaybill(WaybillRequestDto $waybillRequestDto): WaybillResponseDto
    {
        return DB::transaction(function () use ($waybillRequestDto) {
            $invoice = Invoice::findOrFail($waybillRequestDto->invoiceId);

            $order = Order::findOrFail($invoice->order_id);
            $order->load('currentStatus');
            if ($order->currentStatus->name === OrderStatusType::Delivered->value) {
                throw new \Exception("Заказ уже доставлен.");
            }

            $deliveredStatus = OrderStatus::where('name', OrderStatusType::Delivered->value)->firstOrFail();

            $orderItems = OrderItem::where('order_id', $order->id)->get();
            $orderItems->each(function (OrderItem $item) {
                $quantity = $item->quantity;
                $productId = $item->product_id;
                $this->productService->decreaseQuantity($quantity,$productId);
            });

            $order->update(['order_status_id' => $deliveredStatus->id]);

            $waybill = $waybillRequestDto->toModel();
            $waybill->handoff_time = now();
            $waybill->save();
            $waybill->refresh();

            return WaybillResponseDto::fromModel($waybill);
        });
    }

    public function getWaybillById(int $waybillId): WaybillResponseDto
    {
        $waybill = Waybill::findOrFail($waybillId);
        return WaybillResponseDto::fromModel($waybill);
    }
}
