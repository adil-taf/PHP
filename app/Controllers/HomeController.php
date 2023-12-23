<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\InvoiceService;
use App\App;
use App\View;

class HomeController
{
    public function index(): View
    {
        App::$container->get(InvoiceService::class)->process([], 25);
        return View::make('index');
    }
}
