<?php

namespace App\Http\Controllers;

use App\DTOs\Request\InvoiceRequestDto;
use App\Services\PaymentService\Api\PaymentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    )
    {}
    public function createInvoice(Request $request){
        $invoiceDetails = $request->all();
        $invoiceDetails['issuedBy']=Auth::user()->id;
        $invoiceDto = InvoiceRequestDto::fromArray($invoiceDetails);
        $invoiceResponse = $this->paymentService->createInvoice($invoiceDto);
        return response()->json($invoiceResponse,201);
    }

    public function showInvoice(Request $request, int $invoiceId){
        return response()->json($this->paymentService->getInvoiceById($invoiceId),200);
    }

    public function showInvoiceByOrderId(Request $request, int $orderId){
        return response()->json($this->paymentService->getInvoiceByOrderId($orderId),200);
    }

    public function showUnpaidInvoices(Request $request){
        $clientId = Auth::user()->id;
        return response()->json($this->paymentService->getUnpaidInvoicesForClient($clientId),200);
    }
    public function payInvoice(Request $request, int $invoiceId){
        $clientId = Auth::user()->id;
        $this->paymentService->payInvoice($invoiceId,$clientId);
        return response(null,200);
    }
}
