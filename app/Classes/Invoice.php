<?php

declare(strict_types=1);

namespace App\Classes;

class Invoice
{
    public function index(): string
    {
        return 'Invoices';
    }

    public function create(): string
    {
        return '<form action="/invoices/create" method="post">
          <label for="amount">Amount:</label>
          <input name="amount" type="text"></form>';
    }

    public function store()
    {
        $amount = $_POST['amount'];
        var_dump($amount);
    }
}
