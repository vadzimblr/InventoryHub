<?php

namespace App\Http\Controllers;

use App\DTOs\Request\SupplierRequestDto;
use App\Services\SupplierService\Api\SupplierServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(
        private readonly SupplierServiceInterface $supplierService
    )
    {
    }

    public function showAllSuppliers(Request $request): JsonResponse
    {
        return response()->json($this->supplierService->getAllSuppliers(), 200);
    }
    public function addSupplier(Request $request): JsonResponse{
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $supplierReqDto = new SupplierRequestDto(
            name: $validated['name'],
            address: $validated['address']
        );
        $supplierResDto = $this->supplierService->storeSupplier($supplierReqDto);
        return response()->json($supplierResDto, 201);
    }
    public function deleteSupplier(Request $request): JsonResponse{
        $id = $request->route('id');
        $this->supplierService->removeSupplier($id);
        return response()->json(204);
    }
    public function updateSupplier(Request $request): JsonResponse{
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $id = $request->route('id');
        $supplierReqDto = new SupplierRequestDto(
            name: $validated['name'],
            address: $validated['address']
        );
        $supplierResDto = $this->supplierService->updateSupplier($id, $supplierReqDto);
        return response()->json($supplierResDto, 200);
    }
}
