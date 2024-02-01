<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\InvoiceStatus;
use App\Attributes\Get;
use App\Attributes\Post;
use App\View;
use App\Models\User;
use App\Models\Invoice;
use App\Models\SignUp;
use App\Services\InvoiceService;

class InvoiceController
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    #[Get('/invoices')]
    public function index(): View
    {
        $invoices = (new Invoice())->all(InvoiceStatus::Paid);

        return View::make('invoices/index', ['invoices' => $invoices]);
    }

    #[Get('/invoices/process')]
    public function process(): View
    {
        $this->invoiceService->process([], 25);

        return View::make('invoices/process');
    }

    #[Get('/invoices/create')]
    public function create(): View
    {
        return View::make('invoices/create');
    }

    #[Get('/invoices/createInvoiceItems')]
    public function createInvoiceItems(): void
    {
        $items = [['Item 1', 1, 15],['Item 2', 2, 7.5],['Item 3', 4, 3.75]];

        $amount = 45;
        $invoiceNumber = '1';
        $invoiceStatus = InvoiceStatus::Pending;
        $userId = 2;

        $invoiceModel = new Invoice();
        $invoiceModel->createInvoiceWithItems($items, $amount, $invoiceNumber, $invoiceStatus, $userId);
    }

    #[Post('/invoices/create')]
    public function store()
    {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $amount = (float) $_POST['amount'];
        $invoiceNumber = (int) $_POST['invoice_number'];
        $status = InvoiceStatus::from((int) $_POST['status']);

        $userModel = new User();
        $invoiceModel = new Invoice();
        $invoiceId = (new SignUp($userModel, $invoiceModel))->register(
            ['email' => $email,'name' => $name],
            ['invoiceNumber' => $invoiceNumber, 'amount' => $amount, 'status' => $status]
        );

        return View::make('invoices/create', ['invoice' => $invoiceModel->find($invoiceId)]);
    }

    #[Get('/download')]
    public function download()
    {
        header('Content-Type: application/pdf');
        header('Content-Disposotion: attachment; filename="myfile.pdf"');
        readfile(STORAGE_PATH . '/file.pdf');
    }

    #[Post('/upload')]
    public function upload()
    {
        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        move_uploaded_file(
            $_FILES['receipt']['tmp_name'],
            $filePath
        );

        header('Location: /');
        exit;
    }
}
