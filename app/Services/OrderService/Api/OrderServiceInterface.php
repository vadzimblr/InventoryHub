<?php

namespace App\Services\OrderService\Api;

use App\DTOs\Request\OrderRequestDto;
use App\DTOs\Response\Client\OrderClientResponseDto;

interface OrderServiceInterface
{
    public function placeOrder(OrderRequestDto $orderRequestDto): OrderClientResponseDto;
}
