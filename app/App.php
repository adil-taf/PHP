<?php

declare(strict_types=1);

namespace App;

use App\Exception\RouteNotFoundException;

class App
{
    private static \PDO $db;

    public function __construct(protected Router $router, protected array $request, protected array $config)
    {
        try {
            static::$db = new \PDO(
                $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['user'],
                $config['pass']
            );
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public static function db(): \PDO
    {
        return static::$db;
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
