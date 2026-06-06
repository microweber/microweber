<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\User\Filament\Pages\AdminProfileRedirectPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI839 — /admin/profile returned the admin 404 because no
 * page/route claimed that slug, even though "My Account" in the user menu
 * already points at the signed-in user's edit page.
 *
 * AdminProfileRedirectPage now claims the `profile` slug inside the admin
 * panel and redirects to the user's own edit page. It registers NO navigation
 * entry (My Account already covers discoverability) — it is purely a
 * URL-resolution shim.
 */
class UserAI839AdminProfileRouteContractTest extends TestCase
{
    #[Test]
    public function admin_profile_route_is_registered(): void
    {
        $this->assertTrue(
            Route::has('filament.admin.pages.profile'),
            'The /admin/profile page route must be registered so the URL resolves instead of 404ing.'
        );
    }

    #[Test]
    public function page_claims_the_profile_slug_and_does_not_register_navigation(): void
    {
        $this->assertSame('profile', AdminProfileRedirectPage::getSlug(),
            'The redirect page must claim the "profile" slug.');
        $this->assertFalse(AdminProfileRedirectPage::shouldRegisterNavigation(),
            'The redirect shim must NOT add a sidebar entry — My Account already covers it.');
    }

    #[Test]
    public function mount_redirects_to_the_users_edit_page(): void
    {
        $src = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/Filament/Pages/AdminProfileRedirectPage.php'
        ));
        $this->assertStringContainsString('$this->redirect(', $src,
            'mount() must redirect rather than render a standalone page.');
        $this->assertStringContainsString("UsersResource::getUrl('edit'", $src,
            'The redirect target must be the signed-in user\'s edit page.');
        // The configurable-admin-prefix fallback must be present (mirrors the
        // proven "My Account" user-menu resolution).
        $this->assertStringContainsString('mw_admin_prefix_url()', $src,
            'A literal admin-prefix fallback must exist for panels without the resource route.');
    }
}
