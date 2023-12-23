<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Models\User;
use App\Models\Invoice;
use App\Models\SignUp;

/*
        use App\App;
        use App\View;
        use PDO;*/


class InvoiceController
{
    public function index(): View
    {
        return View::make('invoices/index');
    }

    public function create(): View
    {
        return View::make('invoices/create');
    }

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


    public function download()
    {
        header('Content-Type: application/pdf');
        header('Content-Disposotion: attachment; filename="myfile.pdf"');
        readfile(STORAGE_PATH . '/file.pdf');
    }

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
