<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Services\ContentModuleEmptyState;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-17-fe8f9e / AI-801 — AI-780/780a Stage-1 CHANGE.
 * Jira: https://microweber.atlassian.net/browse/AI-801
 *
 * Lineage:
 *   - AI-780 (task-2026-05-17-6d65de) — original type-aware empty state
 *   - AI-780a (task-2026-05-17-4c289e) — 5-template rollout
 *   - AI-801 (task-2026-05-17-fe8f9e) — infer content_type from $params['type']
 *   - **task-2026-06-07-pmprod (CHANGE)** — the inference + copy + markup that
 *     used to be a copy-pasted inline `@php` block in all 6 templates was
 *     EXTRACTED to \Modules\Content\Services\ContentModuleEmptyState (logic)
 *     plus modules.content::partials.module-empty-state (markup). The six
 *     templates now just `@include` the shared partial. This test was
 *     updated in place (pin-evolution) to pin the new architecture:
 *       - the per-template assertions now verify DELEGATION to the partial;
 *       - the inference behaviour (including the 'shop/products' module type,
 *         which the old inline `match` never matched) is pinned on the
 *         service via ContentModuleEmptyState::resolveType();
 *       - the parser invariant (Group C) is unchanged — the service still
 *         relies on $params['type'] being populated by the module parser.
 *
 * Acceptance (unchanged behaviour, new home):
 *   - data-mw-content-type="post" (NOT "unknown") on the posts module empty state
 *   - posts module → "No posts yet"; pages → "No pages yet"; products → "No products yet"
 *   - 'shop/products' (ProductsModule::$module) resolves to 'product'
 */
class CanvasFe8f9eAI801ContentTypeInferenceContractTest extends TestCase
{
    /**
     * @return string[] RELATIVE paths to the 6 affected template files.
     * DataProvider runs at test-discovery time BEFORE Laravel boots, so
     * base_path() isn't available — resolved per-test instead.
     */
    public static function templateCases(): array
    {
        return [
            'default.blade.php'    => ['Modules/Content/resources/views/templates/default.blade.php'],
            'skin-1.blade.php'     => ['Modules/Content/resources/views/templates/skin-1.blade.php'],
            'masonry.blade.php'    => ['Modules/Content/resources/views/templates/masonry.blade.php'],
            'dictionary.blade.php' => ['Modules/Content/resources/views/templates/dictionary.blade.php'],
            'search.blade.php'     => ['Modules/Content/resources/views/templates/search.blade.php'],
            'sidebar.blade.php'    => ['Modules/Content/resources/views/templates/sidebar.blade.php'],
        ];
    }

    private function templateContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — every list template delegates to the shared partial
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('templateCases')]
    public function template_delegates_empty_state_to_shared_partial(string $path): void
    {
        $contents = $this->templateContents($path);

        $this->assertStringContainsString(
            "@include('modules.content::partials.module-empty-state'",
            $contents,
            basename($path) . ' must render the shared empty-state partial.'
        );

        // The inline inference/markup must be GONE — it now lives in the
        // service + partial. Pins the de-duplication (task-2026-06-07-pmprod).
        $this->assertStringNotContainsString(
            'mwEmptyType = match',
            $contents,
            basename($path) . ' must NOT carry the inline inference match block anymore.'
        );
        $this->assertStringNotContainsString(
            'mw-canvas-empty-state__title',
            $contents,
            basename($path) . ' must NOT carry the inline empty-state markup anymore.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — the inference + default-branch behaviour lives in the service
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function service_infers_singular_content_type_from_module_type(): void
    {
        // posts/pages/products map to their singular content_type; the
        // path-namespaced product module type 'shop/products' (the AI-801
        // CHANGE runtime gap — the old inline match only checked 'products')
        // also resolves to 'product'.
        $this->assertSame('post', ContentModuleEmptyState::resolveType(['type' => 'posts']));
        $this->assertSame('page', ContentModuleEmptyState::resolveType(['type' => 'pages']));
        $this->assertSame('product', ContentModuleEmptyState::resolveType(['type' => 'products']));
        $this->assertSame('product', ContentModuleEmptyState::resolveType(['type' => 'shop/products']));
    }

    #[Test]
    public function service_defaults_to_null_for_unknown_types_preserving_generic_branch(): void
    {
        $this->assertNull(ContentModuleEmptyState::resolveType(['type' => 'somethingelse']));
        $this->assertNull(ContentModuleEmptyState::resolveType([]));

        // Unknown type → generic "No content yet" view-model (AI-780 default branch).
        $vm = ContentModuleEmptyState::resolve(['type' => 'somethingelse']);
        $this->assertSame('No content yet', $vm['title']);
    }

    #[Test]
    public function explicit_content_type_still_wins_over_type_inference(): void
    {
        // AI-780 contract: an explicit content_type is authoritative.
        $this->assertSame('post', ContentModuleEmptyState::resolveType(['content_type' => 'post', 'type' => 'pages']));
    }

    #[Test]
    public function shared_partial_emits_the_runtime_probe_attribute(): void
    {
        // The designer DOM probe reads data-mw-content-type; it now lives in
        // the shared partial, defaulting to 'unknown' when type is null.
        $partial = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/partials/module-empty-state.blade.php'
        ));
        $this->assertStringContainsString(
            "data-mw-content-type=\"{{ e(\$mwEmpty['type'] ?? 'unknown') }}\"",
            $partial,
            'The shared partial must preserve the data-mw-content-type runtime probe attribute.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — parser invariant guard (defence-in-depth, unchanged)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function parser_still_populates_params_type_from_module_type_attribute(): void
    {
        // The inference relies on `$params['type']` being populated by the
        // module-render parser. Pin the parser invariant so future parser
        // refactors that drop this default break this test BEFORE they hit
        // the runtime defect.
        // Reference: src/MicroweberPackages/App/Utils/ParserLoadModuleTrait.php:405-407
        $parser = (string) file_get_contents(base_path(
            'src/MicroweberPackages/App/Utils/ParserLoadModuleTrait.php'
        ));
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(!isset\\(\\\$attrs\\[\\s*['\"]type['\"]\\s*\\]\\)\\)\\s*\\{\\s*\\\$attrs\\[\\s*['\"]type['\"]\\s*\\]\\s*=\\s*\\\$module_name;\\s*\\}/",
            $parser,
            'ParserLoadModuleTrait must still default $attrs[\'type\'] to $module_name (line ~405-407) — the empty-state inference relies on this invariant.'
        );
    }
}
