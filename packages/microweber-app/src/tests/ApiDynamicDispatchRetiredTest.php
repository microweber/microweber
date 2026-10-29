<?php

declare(strict_types=1);

namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Middleware\VerifyCsrfToken;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression guard for the retired legacy dynamic API dispatcher.
 *
 * ApiController::api() (the `api/{all}` catch-all) used to resolve a class +
 * method straight from the request URL and invoke it. Its `class_is_already_here`
 * branch did so WITHOUT the $api_exposed allow list, so an unauthenticated
 * request could call an arbitrary public method on any already-loaded class —
 * e.g. UserManager::make_logged(['id' => 1]) -> Auth::loginUsingId(1), a full
 * administrator-session takeover from one anonymous request (CWE-306).
 *
 * CSRF was never a barrier (the token is readable by any visitor), so these
 * tests drop VerifyCsrfToken to model the real attacker who already holds a
 * valid token. The dynamic dispatch is now removed; only the module renderer
 * is still served through api().
 */
class ApiDynamicDispatchRetiredTest extends TestCase
{
    private function makeThrowawayAdmin(): User
    {
        $user = new User();
        $user->username = 'zz_apisec_' . uniqid();
        $user->email = $user->username . '@local.test';
        $user->password = bcrypt('x' . uniqid());
        $user->is_admin = 1;
        $user->is_active = 1;
        $user->save();

        return $user;
    }

    #[Test]
    public function it_does_not_log_in_via_the_legacy_dynamic_make_logged_path(): void
    {
        $admin = $this->makeThrowawayAdmin();

        Auth::logout();
        $this->assertFalse(Auth::check(), 'precondition: request starts as a guest');

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post(
            '/api/MicroweberPackages/User/Services/UserManager/make_logged',
            ['id' => $admin->id]
        );

        // The dispatcher must refuse the call outright...
        $response->assertStatus(403);
        // ...and must NOT have executed make_logged.
        $response->assertDontSee('is_logged');
        $response->assertDontSee('You are logged in');
        // The guest must still be a guest — no session was authenticated.
        $this->assertFalse(Auth::check(), 'guest must not be logged in after the call');
        $this->assertEmpty(user_id(), 'no user id must be established');

        $admin->forceDelete();
    }

    #[Test]
    public function it_rejects_the_pre_rename_user_manager_path_too(): void
    {
        $admin = $this->makeThrowawayAdmin();
        Auth::logout();

        // The path from the original report (class before it moved to \Services\).
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post(
            '/api/MicroweberPackages/User/UserManager/make_logged',
            ['id' => $admin->id]
        );

        $response->assertStatus(403);
        $this->assertFalse(Auth::check());

        $admin->forceDelete();
    }

    #[Test]
    public function it_blocks_arbitrary_already_loaded_class_method_dispatch(): void
    {
        Auth::logout();

        // Any already-loaded class method reached by URL reflection must 403,
        // not execute.
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post(
            '/api/MicroweberPackages/User/Services/UserManager/delete',
            ['id' => 1]
        );

        $response->assertStatus(403);
        $response->assertSee('not defined in the allowed functions list');
    }

    #[Test]
    public function it_still_serves_the_module_renderer_through_api(): void
    {
        // The one surface api() still delegates to is the module renderer; it
        // must not be swept up by the retirement 403.
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post(
            '/api/module',
            ['module' => 'menu']
        );

        $response->assertStatus(200);
        $response->assertDontSee('not defined in the allowed functions list');
    }
}
