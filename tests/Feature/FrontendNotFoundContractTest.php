<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Guards the public-frontend 404 contract enforced inside
 * `MicroweberPackages\App\Http\Controllers\FrontendController`.
 *
 * Contract:
 *   - Anonymous visitors hitting a URL that does not resolve to a
 *     content row, a module, a custom view, or a template file MUST
 *     receive an HTTP 404 response. A "soft" 200 with a blank or
 *     fallback body is unacceptable — search engines + uptime
 *     monitors rely on the status code.
 *   - Logged-in admin users hitting the same URL MUST NOT get a
 *     404. The frontend reuses that same code path for the inline
 *     "create new page" affordance, so the admin sees a 200 with the
 *     live-edit shell.
 *
 * Both branches are decided by `is_admin()` in
 * `FrontendController::frontend()` (search for `show_404_to_non_admin`).
 * Regressions in either direction would be invisible to the admin
 * who builds the site (they always test logged-in) and break SEO /
 * monitoring for every anonymous visitor.
 */
class FrontendNotFoundContractTest extends TestCase
{
    /**
     * Use a marker-prefixed slug that is extremely unlikely to clash
     * with any real content row even on a heavily-seeded database.
     * Each test run randomises the suffix so re-runs against the same
     * DB never get a stale match.
     */
    private function nonExistentUrl(): string
    {
        return '/frontend-404-contract-test-' . uniqid('', true);
    }

    #[Test]
    public function anonymous_visitor_hitting_a_non_existent_url_receives_a_404(): void
    {
        $response = $this->get($this->nonExistentUrl());

        $response->assertStatus(
            404,
            'Anonymous visitors hitting an unresolved URL MUST receive a 404 — '
            . 'a soft 200 with a fallback body would silently lie to crawlers / '
            . 'uptime monitors. The decision lives in '
            . 'FrontendController::frontend() under the `show_404_to_non_admin '
            . 'and !$is_admin` guard.'
        );
    }

    #[Test]
    public function logged_in_admin_hitting_the_same_url_does_not_receive_a_404(): void
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
            'email' => 'frontend-404-contract-test-' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($admin)->get($this->nonExistentUrl());

        $this->assertNotSame(
            404,
            $response->getStatusCode(),
            'Logged-in admin users MUST NOT see a 404 on an unresolved URL — '
            . 'the same code path serves the inline "create new page" live-edit '
            . 'affordance, and a 404 here would silently break the operator '
            . 'workflow the moment they land on a yet-to-be-created slug.'
        );
    }
}
