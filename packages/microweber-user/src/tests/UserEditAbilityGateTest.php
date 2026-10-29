<?php

declare(strict_types=1);

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression guards for two auth hardenings:
 *  - __check_id_has_ability_to_edit_user() is now a real gate (admin or self),
 *    not an unconditional `return true` — so a non-admin can't delete/edit
 *    another user's row through user_manager.
 *  - api.user_make_logged is POST-only (was Route::any → GET-reachable CSRF).
 */
class UserEditAbilityGateTest extends TestCase
{
    use UserTestHelperTrait;

    private function makeUser(int $isAdmin = 0): User
    {
        $u = new User();
        $u->username = 'zz_gate_' . uniqid();
        $u->email = $u->username . '@local.test';
        $u->password = bcrypt('secretpass123');
        $u->is_admin = $isAdmin;
        $u->is_active = 1;
        $u->save();

        return $u;
    }

    #[Test]
    public function a_non_admin_cannot_delete_another_user(): void
    {
        $attacker = $this->makeUser(0);
        $victim = $this->makeUser(0);

        Auth::login($attacker);
        $this->assertFalse(is_admin());

        app()->user_manager->delete(['id' => $victim->id]);

        $this->assertNotNull(User::find($victim->id), 'victim must still exist — non-admin delete must be refused');

        Auth::logout();
        $victim->forceDelete();
        $attacker->forceDelete();
    }

    #[Test]
    public function a_user_can_act_on_their_own_row(): void
    {
        $user = $this->makeUser(0);
        Auth::login($user);

        app()->user_manager->delete(['id' => $user->id]);

        $this->assertNull(User::find($user->id), 'self-delete must be allowed');
        Auth::logout();
    }

    #[Test]
    public function an_admin_can_delete_another_user(): void
    {
        $victim = $this->makeUser(0);
        $this->actingAsAdmin();

        app()->user_manager->delete(['id' => $victim->id]);

        $this->assertNull(User::find($victim->id), 'admin delete must be allowed');
        Auth::logout();
    }

    #[Test]
    public function user_make_logged_route_is_post_only(): void
    {
        $this->assertTrue(Route::has('api.user_make_logged'));
        $route = Route::getRoutes()->getByName('api.user_make_logged');
        $methods = $route->methods();

        $this->assertContains('POST', $methods);
        $this->assertNotContains('GET', $methods, 'make_logged must not be GET-reachable (CSRF impersonation)');
    }
}
