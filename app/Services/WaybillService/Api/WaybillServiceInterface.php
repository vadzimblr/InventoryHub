<?php

namespace App\Services\WaybillService\Api;

use App\DTOs\Request\WaybillRequestDto;
use App\DTOs\Response\WaybillResponseDto;

interface WaybillServiceInterface
{
    public function createWaybill(WaybillRequestDto $waybillRequestDto): WaybillResponseDto;
    public function getWaybillById(int $waybillId): WaybillResponseDto;
}
