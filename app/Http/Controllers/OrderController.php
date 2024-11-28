<?php

namespace App\Http\Controllers;

use App\DTOs\Request\OrderRequestDto;
use App\Services\OrderService\Api\OrderServiceInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    )
    {}

    public function placeOrder(Request $request){
        $orderDto = OrderRequestDto::fromArray($request->all());
        $responseDto = $this->orderService->placeOrder($orderDto);
        return response($responseDto,200);
    }
}
