<?php

namespace App\Services\PaymentService\Api;

use App\DTOs\Request\InvoiceRequestDto;
use App\DTOs\Response\InvoiceResponseDto;

interface PaymentServiceInterface
{
    public function createInvoice(InvoiceRequestDto $invoiceRequestDto): InvoiceResponseDto;
    public function getInvoiceById(int $invoiceId);
    public function getUnpaidInvoicesForClient(int $clientId): array;
    public function payInvoice(int $invoiceId, int $clientId): void;
}
