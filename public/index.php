<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$router = new App\Router();

$router->register(
    '/',
    function () {
        echo 'Home';
    }
);
$router->register(
    '/invoices',
    function () {
        echo 'Invoices';
    }
);

echo $router->resolve($_SERVER['REQUEST_URI']);
