<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Fortify\Http\Middleware\RequireTwoFactor;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Fortify\Tests\TestCase;

class MiddlewareTest extends TestCase
{
    private function createTestUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'username' => 'mwtest_' . uniqid(),
            'email' => 'mwtest_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ], $overrides));
    }

    public function test_middleware_allows_guest(): void
    {
        Auth::logout();
        $middleware = new RequireTwoFactor();
        $request = Request::create('/test');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_middleware_allows_user_when_2fa_not_required(): void
    {
        $user = $this->createTestUser();
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => false]);
        config(['microweber-fortify.require2fa_admin_only' => false]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/test');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());

        $user->delete();
    }

    public function test_middleware_redirects_when_2fa_required_but_not_setup(): void
    {
        $user = $this->createTestUser();
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/dashboard');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertTrue($response->isRedirect());

        config(['microweber-fortify.require2fa_all' => false]);
        $user->delete();
    }

    public function test_middleware_allows_when_2fa_already_setup(): void
    {
        $user = $this->createTestUser();
        $user->forceFill([
            'two_factor_secret' => encrypt('testsecret'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        Auth::login($user);
        config(['microweber-fortify.require2fa_all' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/dashboard');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());

        config(['microweber-fortify.require2fa_all' => false]);
        $user->forceFill(['two_factor_secret' => null, 'two_factor_confirmed_at' => null])->save();
        $user->delete();
    }
}