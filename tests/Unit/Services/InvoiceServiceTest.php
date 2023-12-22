<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    public function testCanProcessInvoice(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock  = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock    = $this->createMock(EmailService::class);

        $gatewayServiceMock->method('charge')->willReturn(true);

        // Invoice service
        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Adil'];
        $amount   = 150;

        // Process is called
        $result = $invoiceService->process($customer, $amount);

        // Assert invoice is processed successfully
        $this->assertTrue($result);
    }

    public function testCanSendReceiptEmailWhenInvoiceIsProcessed(): void
    {
        $customer = ['name' => 'Adil'];
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock  = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock    = $this->createMock(EmailService::class);

        $gatewayServiceMock->method('charge')->willReturn(true);

        $emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with($customer, 'receipt');

        // Invoice service
        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $amount   = 150;

        // Process is called
        $result = $invoiceService->process($customer, $amount);

        // Assert invoice is processed successfully
        $this->assertTrue($result);
    }
}
