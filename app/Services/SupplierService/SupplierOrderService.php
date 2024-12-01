<?php

namespace App\Services\SupplierService;

use App\DTOs\Request\SupplierOrderRequestDto;
use App\DTOs\Response\SupplierOrderResponseDto;
use App\Enums\OrderStatusType;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Services\ProductService\Api\ProductServiceInterface;
use App\Services\SupplierService\Api\SupplierOrderServiceInterface;
use Illuminate\Support\Facades\DB;

class SupplierOrderService implements SupplierOrderServiceInterface
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
    )
    {}

    public function placeSupplierOrder(SupplierOrderRequestDto $supplierOrderDto): SupplierOrderResponseDto
    {
        // todo: add orders status history
        $orderStatus = OrderStatus::where('name', OrderStatusType::Pending->value)->first();

        if (!$orderStatus) {
            throw new \Exception("Invalid status: {OrderStatusType::Pending->value}");
        }
        $product = Product::find($supplierOrderDto->productId);
        if (!$product) {
            throw new \Exception("Product not found with ID: {$supplierOrderDto->productId}");
        }

        $supplierOrder = $supplierOrderDto->toModel();

        if($product->supplier_id != $supplierOrder->supplier_id){
            throw new \Exception('Supplier ID does not belong to this supplier');
        }
        $supplierOrder->order_status_id=$orderStatus->id;
        $supplierOrder->total_amount = $product->price * $supplierOrderDto->quantity;
        $supplierOrder->save();
        $supplierOrder->refresh();
        $this->simulateSupplierOrderDelivery($supplierOrder->id);

        return SupplierOrderResponseDto::fromModel($supplierOrder);
    }

    public function simulateSupplierOrderDelivery(int $orderId): void
    {
        $supplierOrder = SupplierOrder::find($orderId);
        if(!$supplierOrder) {
            throw new \Exception("Supplier Order not found");
        }
        $orderStatus = OrderStatus::where('name', OrderStatusType::Delivered->value)->first();

        if (!$orderStatus) {
            throw new \Exception("Invalid status: {OrderStatusType::Pending->value}");
        }
        $supplierOrder->order_status_id = $orderStatus->id;
        $supplierOrder->save();
    }

    public function getSupplierOrderById(int $orderId): SupplierOrderResponseDto
    {
        $supplierOrder = SupplierOrder::find($orderId);
        if(!$supplierOrder) {
            throw new \Exception("Supplier Order with id: {$orderId} not found");
        }
        return SupplierOrderResponseDto::fromModel($supplierOrder);
    }

    public function getAllSupplierOrders(int $supplierId, array $filters = []): array
    {
        $query = SupplierOrder::where('supplier_id', $supplierId);

        if (!empty($filters['status'])) {
            $status = OrderStatus::where('name', $filters['status'])->first();
            if(!$status) {
                throw new \Exception("Invalid status: {$filters['status']}");
            }
            $query->where('order_status_id', $status->id);
        }

        if (!empty($filters['created_from'])) {
            $query->where('created_at', '>=', $filters['created_from']);
        }

        if (!empty($filters['created_to'])) {
            $query->where('created_at', '<=', $filters['created_to']);
        }

        $supplierOrders = $query->get();

        return SupplierOrderResponseDto::fromModelCollection($supplierOrders);
    }

    public function deleteAsHandled(int $orderId): void
    {
        DB::transaction(function () use ($orderId) {
            $supplierOrder = SupplierOrder::find($orderId);

            if (!$supplierOrder) {
                throw new \Exception("Supplier Order with id: {$orderId} not found");
            }

            $this->productService->increaseQuantity($supplierOrder->quantity, $supplierOrder->product_id);
            $registeredStatus = OrderStatus::where(['name' => OrderStatusType::Registered->value])->first();
            $supplierOrder->order_status_id = $registeredStatus->id;
            $supplierOrder->save();
        });
    }
}
