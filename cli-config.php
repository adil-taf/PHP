<?php

declare(strict_types=1);

use App\App;
use App\Container;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;

require_once __DIR__ . '/vendor/autoload.php';

$container = new Container();

$app = new App($container);
$app->boot();

$config = new PhpFile('./migrations.php');

$entityManager = App::entityManager();

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
