<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Fortify\Http\Middleware\RequireTwoFactor;
use MicroweberPackages\Fortify\Tests\TestCase;

class MiddlewareTest extends TestCase
{
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
        $user = $this->createFortifyTestUser();
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
        $user = $this->createFortifyTestUser();
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
        $user = $this->createFortifyTestUser();
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
        $this->cleanupFortifyUser($user);
    }

    public function test_middleware_skips_setup_route(): void
    {
        $user = $this->createFortifyTestUser();
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/two-factor/setup');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());

        config(['microweber-fortify.require2fa_all' => false]);
        $user->delete();
    }

    public function test_middleware_skips_logout_route(): void
    {
        $user = $this->createFortifyTestUser();
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/logout');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());

        config(['microweber-fortify.require2fa_all' => false]);
        $user->delete();
    }

    public function test_middleware_admin_only_allows_non_admin(): void
    {
        // require2fa_admin_only: a non-admin user is NOT forced into 2FA setup.
        $user = $this->createFortifyTestUser(['is_admin' => 0]);
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => false]);
        config(['microweber-fortify.require2fa_admin_only' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/dashboard');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());

        config(['microweber-fortify.require2fa_admin_only' => false]);
        $user->delete();
    }

    public function test_middleware_admin_only_redirects_admin_without_2fa(): void
    {
        // require2fa_admin_only: an admin without 2FA IS redirected to setup.
        $user = $this->createFortifyTestUser(['is_admin' => 1]);
        Auth::login($user);

        config(['microweber-fortify.require2fa_all' => false]);
        config(['microweber-fortify.require2fa_admin_only' => true]);

        $middleware = new RequireTwoFactor();
        $request = Request::create('/dashboard');
        $request->setLaravelSession(app('session.store'));

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertTrue($response->isRedirect());

        config(['microweber-fortify.require2fa_admin_only' => false]);
        $this->cleanupFortifyUser($user);
    }
}