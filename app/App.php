<?php

declare(strict_types=1);

namespace App;

use App\Exception\RouteNotFoundException;
use App\Interfaces\EmailValidationInterface;
use App\Services\PaymentGatewayServiceInterface;
use App\Services\PaymentGatewayService;
use App\Services\Emailable;
use App\Services\AbstractApi;
use Dotenv\Dotenv;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;
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

    public function boot(): static
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->config = new Config($_ENV);

        static::$db = new DB($this->config->db ?? []);

        $this->container->set(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
        $this->container->set(MailerInterface::class, fn() => new CustomMailer($this->config->mailer['dsn']));
        $this->container->set(
            EmailValidationInterface::class,
            fn() => new Emailable\EmailValidationService($this->config->apiKeys['emailable'])
        );

        //$this->container->set(
        //    EmailValidationInterface::class,
        //    fn() => new AbstractApi\EmailValidationService($this->config->apiKeys['abstract_api_email_validation'])
        //);

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
