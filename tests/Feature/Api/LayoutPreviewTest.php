<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression coverage for the layout-preview endpoint.
 *
 * The Insert Layout picker renders thumbnails by loading
 * /api/module/layout-preview in an iframe. When no
 * active_site_template parameter reaches that endpoint the
 * renderer falls back to the globally-configured current_template,
 * which silently drops any skin that only the listed template
 * ships (e.g. Bootstrap's pricing/skin-2). The resulting iframe
 * renders an empty stub, which is what the user sees in the
 * picker as a blank/white thumbnail.
 */
final class LayoutPreviewTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'layout-preview-admin-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        // Force a non-Bootstrap current_template so the preview endpoint
        // can only succeed by honouring the active_site_template parameter.
        $this->seedOption('current_template', 'big2', 'template');
    }

    private function seedOption(string $key, string $value, string $group): void
    {
        $existing = DB::table('options')
            ->where('option_key', $key)
            ->where('option_group', $group)
            ->first();

        if ($existing) {
            DB::table('options')->where('id', $existing->id)->update([
                'option_value' => $value,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('options')->insert([
            'option_key' => $key,
            'option_value' => $value,
            'option_group' => $group,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[Test]
    public function layout_preview_renders_requested_template_skin_not_current_template_fallback(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/api/module/layout-preview?template=pricing__skin-2&active_site_template=Bootstrap');

        $response->assertStatus(200);
        $html = $response->getContent();

        // The Bootstrap pricing/skin-2 blade tags its root section with
        // class "pricing-skin-2" — the fallback empty-skin response does
        // not contain this marker.
        $this->assertStringContainsString('pricing-skin-2', $html,
            'Preview must render the Bootstrap pricing/skin-2 blade, not the empty skin-1 fallback');
        $this->assertStringContainsString('Choose your hosting plan', $html,
            'Preview must render the pricing/skin-2 blade content');

        // Asset URLs must also resolve against the requested template,
        // otherwise the iframe loads the wrong stylesheet.
        $this->assertStringContainsString('/templates/bootstrap/', strtolower($html),
            'Preview must link Bootstrap assets, not the default current_template');
    }

    #[Test]
    public function layout_preview_renders_features_skin_2_from_requested_template(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/api/module/layout-preview?template=features__skin-2&active_site_template=Bootstrap');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('features-skin-2-advantages', $html);
        $this->assertStringContainsString('Why choose our hosting', $html);
    }

    #[Test]
    public function layout_preview_renders_pricing_skin_3_from_requested_template(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/api/module/layout-preview?template=pricing__skin-3&active_site_template=Bootstrap');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('pricing-skin-3', $html);
    }

    #[Test]
    public function layout_preview_rejects_missing_template_param(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/api/module/layout-preview');

        $response->assertStatus(400);
    }

    #[Test]
    public function layout_preview_ignores_unknown_active_site_template(): void
    {
        // An attacker-supplied active_site_template pointing at a
        // non-existent folder must not switch context or touch the
        // filesystem outside templates_dir().
        $response = $this->actingAs($this->admin)
            ->get('/api/module/layout-preview?template=content__skin-1&active_site_template=../../../etc');

        $response->assertStatus(200);
        // No traversal: the rendered HTML must still reference a real
        // template directory under /templates/.
        $this->assertMatchesRegularExpression(
            '#/templates/[a-z0-9_-]+/#i',
            $response->getContent()
        );
    }

    #[Test]
    public function layout_list_appends_active_site_template_to_each_preview_url(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/api/module/list?layout_type=layout&elements_mode=true&group_layouts_by_category=true&active_site_template=Bootstrap');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('layouts', $data);
        $this->assertNotEmpty($data['layouts'], 'Bootstrap must expose at least one layout skin');

        foreach ($data['layouts'] as $layout) {
            // screenshot takes priority over preview_url; only assert on
            // rows that actually use the iframe fallback.
            if (empty($layout['screenshot']) && !empty($layout['preview_url'])) {
                $this->assertStringContainsString(
                    'active_site_template=Bootstrap',
                    $layout['preview_url'],
                    'preview_url must carry the active_site_template so the preview endpoint honours it'
                );
            }
        }
    }
}
