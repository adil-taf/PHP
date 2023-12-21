<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Router;
use PHPUnit\Framework\TestCase;
use App\Exceptions\RouteNotFoundException;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = new Router();
    }

    public function testRouterRegisters(): void
    {
        $this->router->register('get', '/users', ['Users','index']);
        $this->router->register('post', '/users', ['Users','submit']);

        $expected = [
          'get' => [
            '/users' => ['Users','index'],
          ],
          'post' => [
            '/users' => ['Users','submit'],
          ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function testRouterRegistersGetRoute(): void
    {
        $this->router->get('/users', ['Users','index']);

        $expected = [
          'get' => [
            '/users' => ['Users','index'],
          ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function testRouterRegistersPostRoute(): void
    {
        $this->router->post('/users', ['Users','submit']);

        $expected = [
          'post' => [
            '/users' => ['Users','submit']
          ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function testNoRoutesWhenRouterIsCreated(): void
    {
        $this->assertEmpty((new Router())->routes());
    }

    /**
     * @test
     * @dataProvider \Tests\DataProviders\RouterDataProvider::routeNotFoundCasses
     */
    public function testRouterThrowsRouteNotFoundException(
        string $requestUri,
        string $requestMethod
    ): void {
        $users = new class (){
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->post('/users', [$users::class,'submit']);
        $this->router->get('/users', ['Users','index']);

        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    public function testRouterResolvesRouteFromClosure(): void
    {
        $this->router->get('/users', fn()=>'Test');

        $this->assertEquals(
            'Test',
            $this->router->resolve('/users', 'get')
        );
    }

    public function testRouterResolvesRoute()
    {
        $users = new class (){
            public function index(): array
            {
                return [1,2,3];
            }
        };

        $this->router->get('/users', [$users::class,'index']);

        $this->assertSame(
            [1,2,3],
            $this->router->resolve('/users', 'get')
        );
    }
}
