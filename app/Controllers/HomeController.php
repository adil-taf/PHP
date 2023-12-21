<?php

declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\View;
use PDO;
use App\Models\User;
use App\Models\Invoice;
use App\Models\SignUp;

class HomeController
{
    public function index(): View
    {
        $email = 'adil@taf.com';
        $name = 'Adil Taf';
        $amount = 25;

        $userModel = new User();
        $invoiceModel = new Invoice();
        $invoiceId = (new SignUp($userModel, $invoiceModel))->register(
            ['email' => $email,'name' => $name],
            ['amount' => $amount]
        );

        return View::make('index', ['invoice' => $invoiceModel->find($invoiceId)]);
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
