<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Attributes\Get;
use App\View;
use Jenssegers\Blade\Blade;

class InvoiceController
{
    public function __construct(private Blade $blade)
    {
    }

    #[Get('/invoices')]
    public function index(): string
    {
        $invoices = Invoice::query()->where('status', InvoiceStatus::Paid)->get();

        return $this->blade->make('invoices/index', ['invoices' => $invoices])->render();
    }

    #[Get('/invoices/new')]
    public function create()
    {
        $invoice = new Invoice();

        $invoice->invoice_number = 5;
        $invoice->amount         = 20;
        $invoice->user_id = 2;
        $invoice->status         = InvoiceStatus::Paid;

        $invoice->save();

        echo $invoice->id . ', ' . $invoice->created_at->format('m/d/Y');
    }

    #[Get('/invoices/createInvoiceItems')]
    public function invoiceWithItems(): void
    {
        $invoice = new Invoice();

        $invoice->invoice_number = 5;
        $invoice->amount         = 20;
        $invoice->user_id = 2;
        $invoice->status         = InvoiceStatus::Pending;

        $invoice->save();

        $items = [['Item 1', 1, 35], ['Item 2', 2, 5], ['Item 3', 4, 3.75],];
        foreach ($items as [$description, $quantity, $unitPrice]) {
            $item = new InvoiceItem();

            $item->description = $description;
            $item->quantity = $quantity;
            $item->unit_price = $unitPrice;

            $item->invoice()->associate($invoice);

            $item->save();
        }
    }
}
