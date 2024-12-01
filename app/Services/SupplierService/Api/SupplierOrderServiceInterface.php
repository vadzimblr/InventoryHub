<?php

namespace App\Services\SupplierService\Api;

use App\DTOs\Request\SupplierOrderRequestDto;
use App\DTOs\Response\SupplierOrderResponseDto;

interface SupplierOrderServiceInterface
{
    public function placeSupplierOrder(SupplierOrderRequestDto $supplierOrderDto):SupplierOrderResponseDto;
    public function simulateSupplierOrderDelivery(int $orderId): void;
    public function getSupplierOrderById(int $orderId): SupplierOrderResponseDto;
    public function getAllSupplierOrders(int $supplierId, array $filters=[]): array;
    public function deleteAsHandled(int $orderId): void;

}
