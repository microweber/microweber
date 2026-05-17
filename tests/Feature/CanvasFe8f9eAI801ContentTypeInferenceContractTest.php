<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-17-fe8f9e / AI-801  AI-780/780a Stage-1 CHANGE.
 * Jira: https://microweber.atlassian.net/browse/AI-801
 *
 * Lineage:
 *   - AI-780 (task-2026-05-17-6d65de)  original type-aware empty state
 *   - AI-780a (task-2026-05-17-4c289e)  5-template rollout
 *   - AI-788 (task-2026-05-17-6027b9)  Stage-1 sister lesson
 *
 * Designer DOM probe of home demo posts module empty state:
 *   data-mw-ai780-content-type: "unknown"        <- should be "post"
 *   fullText: "No content yet\n\n+ Add content"  <- should be type-aware
 *
 * Root cause: $params['content_type'] was the SOLE source for
 * $mwAi780Type resolution. The AI-780/780a contract test sets it
 * explicitly in the DataProvider, so the source-level tests passed
 * (35/125 green). At runtime the posts module renderer doesn't pass
 * 'content_type' through $params  $mwAi780Type stays null  default
 * branch fires.
 *
 * Same Stage-1 sub-case as AI-788 (data shipped, consumer not wired)
 * applied at the params-pipeline layer rather than at a Filament call
 * site. AI-788 designer agreement: "always integration-test the
 * consumer's actual call site, not just the data-loader in isolation."
 *
 * Fix (Path A per designer dispatch): infer the singular content_type
 * from $params['type'] when 'content_type' is missing. The parser at
 * src/MicroweberPackages/App/Utils/ParserLoadModuleTrait.php:405-407
 * populates $params['type'] from <module type="..."> so it's always
 * available. Explicit match-three list (posts/pages/products) keeps
 * the safe singularisation; no naive trailing-s strip.
 *
 * Applied to all 6 templates (default + skin-1 + masonry + dictionary
 * + search + sidebar). Each gets the same inline @php fallback block.
 *
 * Acceptance:
 *   - data-mw-ai780-content-type="post" (NOT "unknown") on the posts
 *     module empty state
 *   - Body copy "No posts yet" / CTA "+ Add post"
 *   - Same path works for pages module  "No pages yet / + Add page"
 *   - Source-level pin in this test for all 6 templates
 */
class CanvasFe8f9eAI801ContentTypeInferenceContractTest extends TestCase
{
    /**
     * @return string[] RELATIVE paths to the 6 affected template files.
     * DataProvider runs at test-discovery time BEFORE Laravel boots, so
     * base_path() isn't available  resolved per-test instead.
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
    // Group A  AI-801 inference block present in all 6 templates
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('templateCases')]
    public function template_carries_content_type_inference_from_params_type(string $path): void
    {
        $contents = $this->templateContents($path);

        // The inference block must:
        //   (a) be gated on `! $mwAi780Type` (only fires when content_type
        //       wasn't passed explicitly)
        //   (b) use a match expression against $params['type'] ?? null
        //   (c) carry the three safe mappings posts/pages/products
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!\s*\$mwAi780Type\s*\)\s*\{\s*\$mwAi780Type\s*=\s*match\s*\(\s*\$params\[[\'"]type[\'"]\]\s*\?\?\s*null\s*\)\s*\{/',
            $contents,
            basename($path) . ' must guard its inference with `if (! $mwAi780Type)` then `match ($params[\'type\'] ?? null)`.'
        );
        $this->assertMatchesRegularExpression(
            "/'posts'\s*=>\s*'post'/",
            $contents,
            basename($path) . ' must map \'posts\' module type to \'post\' content_type.'
        );
        $this->assertMatchesRegularExpression(
            "/'pages'\s*=>\s*'page'/",
            $contents,
            basename($path) . ' must map \'pages\' module type to \'page\' content_type.'
        );
        $this->assertMatchesRegularExpression(
            "/'products'\s*=>\s*'product'/",
            $contents,
            basename($path) . ' must map \'products\' module type to \'product\' content_type.'
        );
        $this->assertMatchesRegularExpression(
            "/default\s*=>\s*null/",
            $contents,
            basename($path) . ' must default to null for unknown module types (preserves the AI-780 default-branch behaviour).'
        );
    }

    #[Test]
    #[DataProvider('templateCases')]
    public function template_inference_block_precedes_first_branch_check(string $path): void
    {
        // Sanity: the inference block must run BEFORE the original
        // `if ($mwAi780Type === 'post')` cascade so the inferred value
        // is the one tested.
        $contents = $this->templateContents($path);
        $matchIdx = strpos($contents, '$mwAi780Type = match ($params[\'type\']');
        $firstBranchIdx = strpos($contents, "if (\$mwAi780Type === 'post') {");

        $this->assertNotFalse($matchIdx, basename($path) . ' must contain the inference match expression.');
        $this->assertNotFalse($firstBranchIdx, basename($path) . ' must still contain the original first-branch check.');
        $this->assertLessThan(
            $firstBranchIdx,
            $matchIdx,
            basename($path) . ' inference block must run BEFORE the first `if ($mwAi780Type === \'post\')` branch.'
        );
    }

    #[Test]
    #[DataProvider('templateCases')]
    public function template_carries_ai801_marker(string $path): void
    {
        $contents = $this->templateContents($path);
        $this->assertStringContainsString(
            'task-2026-05-17-fe8f9e',
            $contents,
            basename($path) . ' must carry the AI-801 task-id marker.'
        );
        $this->assertStringContainsString(
            'AI-801',
            $contents,
            basename($path) . ' must carry the AI-801 ticket reference.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  default branch preserved (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('templateCases')]
    public function template_default_branch_preserved_for_truly_unknown_types(string $path): void
    {
        // When neither $params['content_type'] nor a matched
        // $params['type'] is present, the default branch must still
        // fire ("No content yet" / "+ Add content"). Pin the cascade
        // structure: the original elseif/else block must still be
        // intact after the inference block runs.
        $contents = $this->templateContents($path);
        $this->assertStringContainsString(
            "} elseif (\$mwAi780Type === 'page') {",
            $contents,
            basename($path) . ' must preserve the original page-branch elseif.'
        );
        $this->assertStringContainsString(
            '} else {',
            $contents,
            basename($path) . ' must preserve the original default-branch else.'
        );
        $this->assertStringContainsString(
            "__('No content yet')",
            $contents,
            basename($path) . ' must preserve the default-branch title "No content yet" copy.'
        );
        $this->assertStringContainsString(
            "data-mw-ai780-content-type=\"{{ e(\$mwAi780Type ?? 'unknown') }}\"",
            $contents,
            basename($path) . ' must preserve the data-mw-ai780-content-type runtime probe attribute.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  parser invariant guard (defence-in-depth)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function parser_still_populates_params_type_from_module_type_attribute(): void
    {
        // The fix relies on `$params['type']` being populated by the
        // module-render parser. Pin the parser invariant so future
        // parser refactors that drop this default break this test
        // BEFORE they hit the runtime defect.
        // Reference: src/MicroweberPackages/App/Utils/ParserLoadModuleTrait.php:405-407
        $parser = (string) file_get_contents(base_path(
            'src/MicroweberPackages/App/Utils/ParserLoadModuleTrait.php'
        ));
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(!isset\\(\\\$attrs\\[\\s*['\"]type['\"]\\s*\\]\\)\\)\\s*\\{\\s*\\\$attrs\\[\\s*['\"]type['\"]\\s*\\]\\s*=\\s*\\\$module_name;\\s*\\}/",
            $parser,
            'ParserLoadModuleTrait must still default $attrs[\'type\'] to $module_name (line ~405-407)  AI-801 inference relies on this invariant. If this assertion fails, the parser layer changed shape and the template-side inference will silently break at runtime.'
        );
    }
}
