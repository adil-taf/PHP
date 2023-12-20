<?php

declare(strict_types=1);

use App\App;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use App\Router;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');


$router = new Router();

$router
    ->get('/', [HomeController::class, 'index'])
    ->get('/download', [HomeController::class, 'download'])
    ->post('/upload', [HomeController::class, 'upload'])
    ->get('/invoices', [InvoiceController::class, 'index'])
    ->get('/invoices/create', [InvoiceController::class, 'create'])
    ->post('/invoices/create', [InvoiceController::class, 'store']);

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    [
    'host' => $_ENV['DB_HOST'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS'],
    'database' => $_ENV['DB_DATABASE'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'mysql'
    ]
))->run();
