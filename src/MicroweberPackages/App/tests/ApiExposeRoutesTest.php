<?php

declare(strict_types=1);

namespace MicroweberPackages\App\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiExposeRoutesTest extends TestCase
{
    #[Test]
    public function it_lists_api_index_routes(): void
    {
        $response = $this->get(route('api.api_index'));
        $response->assertSuccessful();
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    #[Test]
    public function it_api_index_debug_flag_returns_structure(): void
    {
        $response = $this->get(route('api.api_index', ['debug' => 1]));
        $response->assertSuccessful();
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('count', $data);
        $this->assertArrayHasKey('routes', $data);
    }

    #[Test]
    public function it_sessionless_api_index_works(): void
    {
        $response = $this->get(route('api_nosession.api_index'));
        $response->assertSuccessful();
    }

    #[Test]
    public function it_rejects_mw_install_market_item_for_guests(): void
    {
        $response = $this->post(route('api.mw_install_market_item'), [
            'require_name' => 'some/package',
        ]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not install market items, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_mw_apply_updates_for_guests(): void
    {
        $response = $this->post(route('api.mw_apply_updates'));

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not apply updates, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_mw_send_anonymous_server_data_for_guests(): void
    {
        $response = $this->post(route('api.mw_send_anonymous_server_data'));

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not send anonymous server data, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_save_language_file_content_for_guests(): void
    {
        $response = $this->post(route('api.save_language_file_content'), [
            'namespace' => 'global',
        ]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not save language files, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_send_lang_form_to_microweber_for_guests(): void
    {
        $response = $this->post(route('api.send_lang_form_to_microweber'), [
            'translations' => [],
        ]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not send lang form, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_composer_install_package_for_non_admin_when_installed(): void
    {
        auth()->logout();

        // Unit-level authorize check (avoids legacy must_have_access output buffers).
        $request = \MicroweberPackages\App\Http\Requests\MwComposerInstallPackageRequest::create(
            '/api/mw_composer_install_package_by_name',
            'POST',
            ['require_name' => 'some/package']
        );
        $request->setContainer(app());
        $this->assertFalse(
            $request->authorize(),
            'Guest authorize() must be false when the site is already installed'
        );

        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('api.mw_composer_install_package_by_name'),
            'Route must be registered'
        );
    }

    #[Test]
    public function it_admin_can_hit_send_anonymous_server_data_route(): void
    {
        $this->loginAsAdmin();

        // May fail downstream (network), but route + auth must not 401/403
        $response = $this->post(route('api.mw_send_anonymous_server_data'), [
            'function_name' => 'test',
        ]);

        $this->assertNotEquals(401, $response->status());
        $this->assertNotEquals(403, $response->status());
    }
}
