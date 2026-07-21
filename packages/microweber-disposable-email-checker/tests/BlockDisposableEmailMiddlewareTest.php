<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Tests;

use Illuminate\Http\Request;
use MicroweberPackages\DisposableEmailChecker\Http\Middleware\BlockDisposableEmail;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BlockDisposableEmailMiddlewareTest extends TestCase
{
    #[Test]
    public function middleware_blocks_disposable_email(): void
    {
        $this->expectException(HttpException::class);

        $request = Request::create('/register', 'POST', ['email' => 'test@mailinator.com']);

        /** @var BlockDisposableEmail $middleware */
        $middleware = $this->app->make(BlockDisposableEmail::class);

        $middleware->handle($request, function () {
            $this->fail('Should not reach next middleware');
        });
    }

    #[Test]
    public function middleware_allows_legitimate_email(): void
    {
        $request = Request::create('/register', 'POST', ['email' => 'user@gmail.com']);

        /** @var BlockDisposableEmail $middleware */
        $middleware = $this->app->make(BlockDisposableEmail::class);

        $response = $middleware->handle($request, function ($req) {
            return response('ok');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function middleware_uses_custom_field_name(): void
    {
        $this->expectException(HttpException::class);

        $request = Request::create('/register', 'POST', ['user_email' => 'test@mailinator.com']);

        /** @var BlockDisposableEmail $middleware */
        $middleware = $this->app->make(BlockDisposableEmail::class);

        $middleware->handle($request, function () {
            $this->fail('Should not reach next middleware');
        }, 'user_email');
    }

    #[Test]
    public function middleware_allows_all_when_disabled(): void
    {
        config()->set('disposable-email-checker.enabled', false);

        $request = Request::create('/register', 'POST', ['email' => 'test@mailinator.com']);

        /** @var BlockDisposableEmail $middleware */
        $middleware = $this->app->make(BlockDisposableEmail::class);

        $response = $middleware->handle($request, function ($req) {
            return response('ok');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    #[Test]
    public function middleware_allows_request_without_email_field(): void
    {
        $request = Request::create('/register', 'POST', ['name' => 'John']);

        /** @var BlockDisposableEmail $middleware */
        $middleware = $this->app->make(BlockDisposableEmail::class);

        $response = $middleware->handle($request, function ($req) {
            return response('ok');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
}