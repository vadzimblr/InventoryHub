<?php

namespace App\Services\SupplierService;

use App\DTOs\Request\SupplierRequestDto;
use App\DTOs\Response\SupplierResponseDto;
use App\Models\Supplier;
use App\Services\SupplierService\Api;
use App\Services\SupplierService\Api\SupplierServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierService implements SupplierServiceInterface
{

    public function getAllSuppliers(): array
    {
        $suppliers = Supplier::all();
        return SupplierResponseDto::collectionFromModels($suppliers);
    }

    public function storeSupplier(SupplierRequestDto $supplierRequestDto): SupplierResponseDto
    {
        $supplier = new Supplier();
        $supplier->name = $supplierRequestDto->name;
        $supplier->address = $supplierRequestDto->address;
        $supplier->save();
        return SupplierResponseDto::fromModel($supplier);
    }

    public function removeSupplier(int $supplierId): void
    {
        $supplier = Supplier::where('id', $supplierId)?->first();
        if($supplier === null) {
            throw new ModelNotFoundException("Supplier not found");
        }
        $supplier->delete();
    }

    public function updateSupplier(int $supplierId, SupplierRequestDto $supplierRequestDto): SupplierResponseDto
    {
        $supplier = Supplier::where('id', $supplierId)?->first();
        if($supplier === null) {
            throw new ModelNotFoundException("Supplier not found");
        }
        $supplier->name = $supplierRequestDto->name;
        $supplier->address = $supplierRequestDto->address;
        $supplier->save();
        return SupplierResponseDto::fromModel($supplier);
    }
}
