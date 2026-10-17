<?php

declare(strict_types=1);

namespace MicroweberPackages\User\tests;

use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Characterization tests pinning the CURRENT behaviour of UserManager's
 * NON-AUTH read/url bands (finders, profile display, URL builders). These lock
 * behaviour in place so those bands can be extracted into Concerns traits
 * without regressions. Auth/login/credential methods are deliberately NOT
 * covered here and are NOT being moved. Read-only — no mutation.
 */
class UserManagerCharacterizationTest extends TestCase
{
    private function um()
    {
        return app()->user_manager;
    }

    private function anyUserId(): int
    {
        $u = User::query()->orderBy('id')->first();
        $this->assertNotNull($u, 'test DB must contain at least one user');
        return (int) $u->id;
    }

    #[Test]
    public function count_returns_a_positive_int(): void
    {
        $c = $this->um()->count();
        $this->assertIsInt($c);
        $this->assertGreaterThan(0, $c);
    }

    #[Test]
    public function get_by_id_resolves_the_requested_user(): void
    {
        $id = $this->anyUserId();
        $user = $this->um()->get_by_id($id);
        $resolvedId = is_array($user) ? ($user['id'] ?? null) : ($user->id ?? null);
        $this->assertEquals($id, $resolvedId);
    }

    #[Test]
    public function name_and_nice_name_return_strings_for_a_real_user(): void
    {
        $id = $this->anyUserId();
        $this->assertIsString($this->um()->name($id));
        $this->assertIsString($this->um()->nice_name($id));
    }

    #[Test]
    public function has_picture_returns_a_bool(): void
    {
        $this->assertIsBool($this->um()->has_picture($this->anyUserId()));
    }

    #[Test]
    public function url_builders_return_non_empty_strings_with_expected_segments(): void
    {
        $um = $this->um();
        foreach (['register_url', 'login_url', 'logout_url', 'profile_url', 'forgot_password_url'] as $m) {
            $url = $um->{$m}();
            $this->assertIsString($url, "$m must return a string");
            $this->assertNotSame('', $url, "$m must be non-empty");
        }
        $this->assertStringContainsString('login', $um->login_url());
        $this->assertStringContainsString('logout', $um->logout_url());
    }
}
