<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\View;
use App\Models\User;
use App\Models\Invoice;
use App\Models\SignUp;

class InvoiceController
{
    #[Get('/invoices')]
    public function index(): View
    {
        return View::make('invoices/index');
    }

    #[Get('/invoices/create')]
    public function create(): View
    {
        return View::make('invoices/create');
    }

    #[Post('/invoices/create')]
    public function store()
    {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $amount = (float) $_POST['amount'];

        $email = 'adil.tafs@gmail.com';
        $name = 'adil tafs';
        $amount = 150;


        $userModel = new User();
        $invoiceModel = new Invoice();
        $invoiceId = (new SignUp($userModel, $invoiceModel))->register(
            ['email' => $email,'name' => $name],
            ['amount' => $amount]
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
