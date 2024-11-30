<?php

namespace App\Http\Controllers;

use App\DTOs\Request\WaybillRequestDto;
use App\DTOs\Response\WaybillResponseDto;
use App\Models\Waybill;
use App\Services\WaybillService\Api\WaybillServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaybillController extends Controller
{
    public function __construct(
        private readonly WaybillServiceInterface $waybillService,
    )
    {}

    public function createWaybill(Request $request){
        $waybillDetails = $request->all();
        $waybillDetails['storekeeperId'] = Auth::user()->id;
        $waybillRequestDto = WaybillRequestDto::fromArray($waybillDetails);
        $waybillResponse = $this->waybillService->createWaybill($waybillRequestDto);
        return response()->json($waybillResponse, 200);
    }
    public function getWaybillById(Request $request, int $waybillId){
        $waybillResponse = $this->waybillService->getWaybillById($waybillId);
        return response()->json($waybillResponse, 200);
    }
    public function getWaybills(Request $request){
        $waybillResponse = WaybillResponseDto::fromModelCollection(Waybill::all());
        return response()->json($waybillResponse, 200);
    }

}
