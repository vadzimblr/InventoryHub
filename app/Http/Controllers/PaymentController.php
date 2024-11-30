<?php

namespace App\Http\Controllers;

use App\DTOs\Request\BillRequestDto;
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
    public function showInvoicesByClientId(Request $request, int $clientId){
        return response()->json($this->paymentService->getInvoicesByClientId($clientId),200);
    }

    public function createBill(Request $request)
    {
        $billDetails = $request->all();
        $billDetails['accountantId'] = Auth::user()->id;
        $billDto = BillRequestDto::fromArray($billDetails);
        $billResponse = $this->paymentService->createBill($billDto);

        return response()->json($billResponse, 201);
    }

    public function showBillById(int $billId)
    {
        $billResponse = $this->paymentService->getBillById($billId);
        return response()->json($billResponse, 200);
    }

    public function showUnpaidBills(Request $request)
    {
        $unpaidBills = $this->paymentService->getUnpaidBills();
        return response()->json($unpaidBills, 200);
    }

    public function payBill(Request $request, int $billId)
    {
        $this->paymentService->payBill($billId);
        return response(null, 200);
    }

}
