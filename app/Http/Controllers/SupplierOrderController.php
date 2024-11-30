<?php

namespace App\Http\Controllers;

use App\DTOs\Request\SupplierOrderRequestDto;
use App\Services\SupplierService\Api\SupplierOrderServiceInterface;
use App\Services\SupplierService\Api\SupplierServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierOrderController extends Controller
{
    public function __construct(
        private readonly SupplierOrderServiceInterface $supplierOrderService
    )
    {}
    public function placeSupplierOrder(Request $request)
    {
        $supplierOrderDetails = $request->all();
        $supplierOrderDetails['managerId']=Auth::user()->id;
        $supplierOrderRequestDto = SupplierOrderRequestDto::fromArray($supplierOrderDetails);
        $responseDto = $this->supplierOrderService->placeSupplierOrder($supplierOrderRequestDto);
        return response()->json($responseDto,200);
    }
    public function getSupplierOrderById(int $orderId)
    {
        try {
            $responseDto = $this->supplierOrderService->getSupplierOrderById($orderId);
            return response()->json($responseDto, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function getAllSupplierOrders(Request $request, int $supplierId)
    {
        $filters = $request->all();

        try {
            $supplierOrders = $this->supplierOrderService->getAllSupplierOrders($supplierId, $filters);
            return response()->json($supplierOrders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
