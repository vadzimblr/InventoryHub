<?php

namespace App\Services\PaymentService;

namespace App\Services\PaymentService;

use App\DTOs\Request\BillRequestDto;
use App\DTOs\Request\InvoiceRequestDto;
use App\DTOs\Response\BillResponseDto;
use App\DTOs\Response\InvoiceResponseDto;
use App\Enums\OrderStatusType;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\SupplierOrder;
use App\Services\PaymentService\Api\PaymentServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService implements PaymentServiceInterface
{
    public function createInvoice(InvoiceRequestDto $invoiceRequestDto): InvoiceResponseDto
    {
        $order = Order::find($invoiceRequestDto->orderId);

        if (!$order) {
            throw new \Exception("Order with id {$invoiceRequestDto->orderId} not found");
        }
        $orderStatusProcessing = OrderStatus::where('name', OrderStatusType::Processing->value)->first();
        if($order->order_status_id != $orderStatusProcessing->id) {
            throw new \Exception('You can create invoice only for orders with status {' . $orderStatusProcessing->name . '}');
        }

        $orderStatus = OrderStatus::where('name', OrderStatusType::Unpaid->value)->first();

        if (!$orderStatus) {
            throw new \Exception("Order status 'Unpaid' not found");
        }

        return DB::transaction(function () use ($invoiceRequestDto, $order, $orderStatus) {
            $order->update(['order_status_id' => $orderStatus->id]);
            $order->save();
            $invoice = $invoiceRequestDto->toModel();
            $invoice->save();

            return InvoiceResponseDto::fromModel($invoice);
        });
    }

    public function getInvoiceById(int $invoiceId): InvoiceResponseDto
    {
        $invoice = Invoice::find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice with id {$invoiceId} not found");
        }

        return InvoiceResponseDto::fromModel($invoice);
    }
    public function getInvoicesByClientId(int $clientId): array
    {
        $invoices = Invoice::whereHas('order', function ($query) use ($clientId) {
            $query->where('client_id', $clientId)
                ->whereHas('currentStatus', function ($statusQuery) {
                    $statusQuery->whereIn('name', [
                        OrderStatusType::Paid->value,
                        OrderStatusType::Unpaid->value
                    ]);
                });
        })->get();

        return InvoiceResponseDto::fromModelCollection($invoices);
    }

    public function getInvoiceByOrderId(int $orderId): InvoiceResponseDto
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new \Exception("Order with id {$orderId} not found");
        }

        $invoice = Invoice::where('order_id', $orderId)->first();

        if (!$invoice) {
            throw new \Exception("Invoice for order with id {$orderId} not found");
        }

        return InvoiceResponseDto::fromModel($invoice);
    }

    public function getUnpaidInvoicesForClient(int $clientId): array
    {
        $invoices = Invoice::whereNull('paid_at')
            ->whereHas('order', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->get();

        return InvoiceResponseDto::fromModelCollection($invoices);
    }

    public function payInvoice(int $invoiceId, int $clientId): void
    {
        $invoice = $this->getInvoiceForClient($invoiceId, $clientId);

        if ($invoice->paid_at !== null) {
            throw new \Exception("Invoice with id {$invoiceId} is already paid");
        }

        DB::transaction(function () use ($invoice) {
            $invoice->paid_at = now();
            $invoice->save();

            $this->updateOrderStatusToPaid($invoice->order);
        });
    }

    private function getInvoiceForClient(int $invoiceId, int $clientId): Invoice
    {
        $invoice = Invoice::find($invoiceId);

        if (!$invoice) {
            throw new \Exception("Invoice with id {$invoiceId} not found");
        }

        if (!$invoice->order || $invoice->order->client_id !== $clientId) {
            throw new \Exception("Invoice does not belong to client with id {$clientId}");
        }

        return $invoice;
    }

    private function updateOrderStatusToPaid(Order $order): void
    {
        $orderStatus = OrderStatus::where('name', OrderStatusType::Paid->value)->first();

        if (!$orderStatus) {
            Log::error("Order status 'Paid' not found");
            throw new \Exception("Order status 'Paid' not found");
        }

        $order->update(['order_status_id' => $orderStatus->id]);
    }

    public function getBillById(int $billId): BillResponseDto
    {
        $bill = Bill::find($billId);

        if (!$bill) {
            throw new \Exception("Bill with id {$billId} not found");
        }

        return BillResponseDto::fromModel($bill);
    }

    public function getUnpaidBills(): array
    {
        $unpaidBills = Bill::whereNull('paid_at')
            ->get();

        return BillResponseDto::fromCollectionModels($unpaidBills);
    }

    public function payBill(int $billId): void
    {
        $bill = Bill::find($billId);

        if (!$bill) {
            throw new \Exception("Bill with id {$billId} not found");
        }

        if ($bill->paid_at !== null) {
            throw new \Exception("Bill with id {$billId} is already paid");
        }

        DB::transaction(function () use ($bill) {
            $bill->paid_at = now();
            $bill->save();

            $this->updateSupplierOrderStatusToPaid($bill->supplierOrder);
        });
    }

    private function updateSupplierOrderStatusToPaid(SupplierOrder $supplierOrder): void
    {
        $paidStatus = OrderStatus::where('name', OrderStatusType::Paid->value)->first();

        if (!$paidStatus) {
            Log::error("Order status 'Paid' not found");
            throw new \Exception("Order status 'Paid' not found");
        }

        $supplierOrder->update(['order_status_id' => $paidStatus->id]);
    }
    public function createBill(BillRequestDto $billRequestDto): BillResponseDto
    {
        $bill = $billRequestDto->toModel();
        $supplierOrder = SupplierOrder::find($bill->supplier_order_id);
        if(!$supplierOrder) {
            throw new \Exception('Supplier with id: {$bill->supplier_order_id} order not found');
        }
        $deliveredStatus = OrderStatus::where(['name'=>OrderstatusType::Delivered->value])->first();
        $supplierOrder->order_status_id = $deliveredStatus->id;
        $supplierOrder->save();
        $bill->save();
        return BillResponseDto::fromModel($bill);
    }
}

