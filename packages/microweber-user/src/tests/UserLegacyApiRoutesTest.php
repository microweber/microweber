<?php

declare(strict_types=1);

namespace MicroweberPackages\User\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserLegacyApiRoutesTest extends TestCase
{
    use UserTestHelperTrait;

    #[Test]
    public function it_checks_is_logged_route_when_guest(): void
    {
        auth()->logout();

        $response = $this->get(route('api.is_logged'));
        $response->assertSuccessful();

        $content = trim((string) $response->getContent());
        // false / empty / "false" / 0 / "" are all acceptable guest responses
        $this->assertTrue(
            $content === ''
            || $content === 'false'
            || $content === '0'
            || $content === '""'
            || $content === 'null',
            'Expected guest is_logged response, got: ' . var_export($content, true)
        );
    }

    #[Test]
    public function it_checks_is_logged_route_when_admin(): void
    {
        $this->loginAsAdmin();

        $response = $this->get(route('api.is_logged'));
        $response->assertSuccessful();
        $this->assertTrue($response->json() === true);
    }

    #[Test]
    public function it_rejects_search_authors_for_guests(): void
    {
        $response = $this->call('POST', route('api.users.search_authors'), ['kw' => 'admin']);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not access admin search_authors, status=' . $response->status()
        );
    }

    #[Test]
    public function it_search_authors_as_admin(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('api.users.search_authors'), [
            'kw' => '',
            'limit' => 5,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertIsArray($data);
    }

    #[Test]
    public function it_rejects_user_make_logged_for_guests(): void
    {
        $response = $this->post(route('api.user_make_logged'), ['id' => 1]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302, 422], true),
            'Guest must not access user_make_logged, status=' . $response->status()
        );
    }

    #[Test]
    public function it_validates_verify_email_link_requires_key(): void
    {
        $response = $this->get(route('api.users.verify_email_link'));

        $this->assertTrue(
            in_array($response->status(), [302, 422, 400], true),
            'Missing key should fail validation, status=' . $response->status()
        );
    }

    #[Test]
    public function it_accepts_user_send_forgot_password_with_email(): void
    {
        $this->_disableCaptcha();

        $response = $this->post(route('api.user_send_forgot_password'), [
            'email' => 'nobody-exists-' . uniqid() . '@example.com',
        ]);

        // Endpoint should respond (success or soft error), not 500
        $this->assertNotEquals(500, $response->status());
        $this->assertTrue($response->status() < 500);
    }

    #[Test]
    public function it_sessionless_is_logged_route_exists(): void
    {
        $response = $this->get(route('api_nosession.is_logged'));
        $this->assertTrue(
            $response->status() < 500,
            'api_nosession is_logged should not 500, status=' . $response->status()
        );
    }

    #[Test]
    public function it_legacy_user_register_route_is_registered(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('api.user_register'),
            'Legacy api.user_register route must be registered'
        );
    }

    #[Test]
    public function it_rejects_register_email_send_test_for_guests(): void
    {
        $response = $this->post(route('api.users.register_email_send_test'));

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not access register_email_send_test, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_forgot_password_email_send_test_for_guests(): void
    {
        $response = $this->post(route('api.users.forgot_password_email_send_test'));

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not access forgot_password_email_send_test, status=' . $response->status()
        );
    }
}
