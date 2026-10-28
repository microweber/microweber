<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\App\Http\Middleware\VerifyCsrfToken;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression guard for the /logout 500.
 *
 * Laravel Fortify registers `POST /logout` (named `logout`); an identical
 * method+URI replaces microweber-user's `POST /logout` in the route collection
 * and drops its name, so the GET confirmation page's `route('logout.submit')`
 * threw RouteNotFoundException. The submit route now lives at `/logout/confirm`
 * so the name survives.
 */
class LogoutRouteTest extends TestCase
{
    use UserTestHelperTrait;

    #[Test]
    public function it_registers_the_logout_submit_route_off_the_fortify_uri(): void
    {
        $this->assertTrue(Route::has('logout.submit'), 'logout.submit route must be registered');
        $this->assertStringContainsString('logout/confirm', route('logout.submit'));
    }

    #[Test]
    public function it_renders_the_logout_confirmation_page_without_a_500(): void
    {
        // No same-site referer -> UserLogoutController@index falls to the
        // confirmation view, which calls route('logout.submit').
        $response = $this->get('/logout');

        $response->assertStatus(200);
        $response->assertSee('logout/confirm');
    }

    #[Test]
    public function it_logs_an_authenticated_user_out_via_the_confirm_submit(): void
    {
        $user = new User();
        $user->username = 'zz_logout_' . uniqid();
        $user->email = $user->username . '@local.test';
        $user->password = bcrypt('secretpass123');
        $user->is_active = 1;
        $user->save();

        Auth::login($user);
        $this->assertTrue(Auth::check());

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('logout.submit'));

        $this->assertFalse(Auth::check(), 'user should be logged out after the confirm submit');

        $user->forceDelete();
    }
}
