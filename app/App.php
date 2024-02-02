<?php

declare(strict_types=1);

namespace App;

use App\Exception\RouteNotFoundException;
use App\Services\PaymentGatewayServiceInterface;
use Dotenv\Dotenv;
use App\Services\PaymentGatewayService;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class App
{
    private static DB $db;
    private static EntityManager $entityManager;
    private Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array $request = [],
    ) {
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public static function entityManager(): EntityManager
    {
        return static::$entityManager;
    }

    public function boot(): static
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->config = new Config($_ENV);

        static::$db = new DB($this->config->db ?? []);

        static::$entityManager = EntityManager::create(
            static::$db->getParams(),
            Setup::createAttributeMetadataConfiguration([__DIR__ . '/Entity'])
        );

        $this->container->set(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
        $this->container->set(MailerInterface::class, fn() => new CustomMailer($this->config->mailer['dsn']));

        return $this;
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo View::make('error/404');
        }
    }
}
