<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Real live-edit regression: drop modules INSIDE a layout region, SAVE, and
 * assert they render on the public page — through the default LayoutProcessor
 * parser, on the Big template.
 *
 * Flow (the exact chain a content editor performs):
 *   1. Seed a page whose content is a single `content/skin-2` layout module.
 *   2. Log in to the admin and open the page in /admin/live-edit.
 *   3. Read the layout's editable region (`rel="module"` field) from the canvas.
 *   4. Append two modules to it (a `features/skin-3` layout + a custom-id btn +
 *      a marker) and persist via the SAME `api/save_edit` endpoint the live-edit
 *      SAVE button posts, with the page's real session + CSRF.
 *   5. Visit the public URL and assert the dropped modules rendered, with no raw
 *      `<module>` tags and no internal placeholder leaks.
 *
 * Prereqs: dev server at 127.0.0.1:8000 sharing this process's DB; Big template.
 */
class LiveEditDropModuleIntoLayoutRenderTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG = 'ledrop-';

    /** @var int[] */
    private array $createdIds = [];

    protected function assertPreConditions(): void
    {
        // Use the already-running site + its DB.
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            try {
                DB::table('content_fields')->where('field', 'like', '%module-layouts-' . $id . '%')->delete();
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {
                // best-effort
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }

    #[Test]
    public function drop_modules_into_layout_then_save_renders_on_public_refresh(): void
    {
        $this->ensureAdminUser();
        $this->ensureBigActive();

        $slug = self::SLUG . substr(md5(microtime(true) . Str::random(6)), 0, 8);
        $marker = 'LEDROP-' . strtoupper(substr(md5($slug), 0, 8));
        $btnId = 'ledrop-btn-' . substr(md5($slug), 0, 6);

        $id = save_content([
            'content_type' => 'page', 'subtype' => 'static',
            'title' => 'LE Drop ' . $slug, 'url' => $slug,
            'active_site_template' => 'Big', 'is_active' => 1,
            'content' => '<h2>' . $marker . '-HOST</h2><module type="layouts" template="content/skin-2"/>',
        ]);
        $this->assertIsNumeric($id, 'save_content failed');
        $this->createdIds[] = (int) $id;

        $this->browse(function (Browser $browser) use ($id, $slug, $marker, $btnId) {
            $this->loginAsAdmin($browser);

            $pageLink = (string) content_link((int) $id);
            $this->assertNotSame('', $pageLink, 'content_link empty');

            // Open the page in live-edit and wait for the canvas iframe.
            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))->pause(4000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Drop two modules into the layout's editable region and SAVE through
            // the real endpoint. Returns the HTTP status of the save.
            $saveStatus = $browser->script("
                return (async function () {
                    try {
                        var iframe = document.querySelector('iframe');
                        var doc = iframe.contentDocument;
                        var region = doc.querySelector('[rel=\"module\"][field^=\"layout-content\"]');
                        if (!region) return 'NO_REGION';
                        var field = region.getAttribute('field');
                        var dropped = '<div class=\"ledrop-marker\">" . $marker . "</div>'
                            + '<module type=\"layouts\" template=\"features/skin-3\"/>'
                            + '<module type=\"btn\" id=\"" . $btnId . "\" template=\"default\"/>';
                        var newHtml = region.innerHTML + dropped;
                        var csrf = (document.querySelector('meta[name=\"csrf-token\"]')||{}).content
                                || (window.mw && mw.options && mw.options.csrf);
                        var fieldData = { field_data_0: {
                            attributes: { rel: 'module', rel_id: '0', field: field },
                            html: newHtml
                        }};
                        var encoded = btoa(unescape(encodeURIComponent(JSON.stringify(fieldData))));
                        var resp = await fetch('/api/save_edit', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: 'data_base64=' + encodeURIComponent(encoded) + '&_token=' + encodeURIComponent(csrf),
                            credentials: 'same-origin'
                        });
                        return String(resp.status);
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            ");
            $this->assertSame('200', (string) ($saveStatus[0] ?? ''), 'live-edit save_edit failed');

            // Refresh the PUBLIC page and assert the dropped modules rendered.
            $browser->visit('/' . $slug)->pause(1500);
            $src = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $src);
            $this->assertStringNotContainsString('Whoops, something went wrong', $src);

            $snap = $browser->script("
                var html = document.documentElement.outerHTML;
                return {
                    marker: document.querySelectorAll('.ledrop-marker').length,
                    btn: document.querySelectorAll('#" . $btnId . "').length,
                    droppedLayout: document.querySelectorAll('[template=\"features/skin-3\"]').length,
                    rawModule: (html.match(/<module\\b/gi) || []).length,
                    leak: (html.match(/mw-protected|mw_replace_back_this|mw-unprocessed-module-tag/g) || []).length
                };
            ")[0];

            $this->assertGreaterThanOrEqual(1, (int) $snap['marker'], 'dropped marker rendered on refresh');
            $this->assertSame(1, (int) $snap['btn'], 'dropped custom-id button rendered on refresh');
            $this->assertGreaterThanOrEqual(1, (int) $snap['droppedLayout'], 'dropped layout module rendered on refresh');
            $this->assertSame(0, (int) $snap['rawModule'], 'no raw <module> survives');
            $this->assertSame(0, (int) $snap['leak'], 'no placeholder leak');
        });
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = 'admin';
            $user->password = Hash::make(self::ADMIN_PASSWORD);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->save();
            return;
        }
        $dirty = false;
        if ((int) $user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int) $user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }

    private function ensureBigActive(): void
    {
        $row = DB::table('options')->where('option_key', 'current_template')
            ->where('option_group', 'template')->first();
        if ($row) {
            if ($row->option_value !== 'Big') {
                DB::table('options')->where('id', $row->id)
                    ->update(['option_value' => 'Big', 'updated_at' => now()]);
            }
            return;
        }
        DB::table('options')->insert([
            'option_key' => 'current_template', 'option_value' => 'Big',
            'option_group' => 'template', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
