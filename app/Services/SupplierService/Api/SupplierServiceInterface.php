<?php

namespace App\Services\SupplierService\Api;

use App\DTOs\Request\SupplierRequestDto;
use App\DTOs\Response\SupplierResponseDto;

interface SupplierServiceInterface
{
    public function getAllSuppliers(): array;
    public function storeSupplier(SupplierRequestDto $supplierRequestDto): SupplierResponseDto;
    public function removeSupplier(int $supplierId): void;
    public function updateSupplier(int $supplierId, SupplierRequestDto $supplierRequestDto): SupplierResponseDto;
}
