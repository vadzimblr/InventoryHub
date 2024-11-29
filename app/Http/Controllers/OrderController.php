<?php

namespace App\Http\Controllers;

use App\DTOs\Request\OrderRequestDto;
use App\Services\OrderService\Api\OrderServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    )
    {}

    public function placeOrder(Request $request){
        $params = $request->all();
        $params['clientId'] = Auth::id();
        $orderDto = OrderRequestDto::fromArray($params);
        $responseDto = $this->orderService->placeOrder($orderDto);
        return response($responseDto,200);
    }
    public function showOrderDetails(Request $request, int $orderId){
        $userId = Auth::user()->id;
        $responseDto = $this->orderService->showOrderDetails($userId, $orderId);
        return response($responseDto,200);
    }
    public function showAllOrders(Request $request){
        $userId = Auth::user()->id;
        $responseDtos = $this->orderService->getAllOrders($userId);
        return response($responseDtos,200);
    }
}
