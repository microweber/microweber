<?php

use Tests\TestCase;

/**
 * Contract test — AI-970 / task-2026-05-22-dc3963
 *
 * Verifies that the Embed module settings textarea carries the data attributes
 * required for admin-filament.js to initialise CodeMirror syntax highlighting,
 * and that the admin.js bundle ships the initEmbedCodeMirror method plus the
 * sequential mode-file loading chain (xml → css → javascript → htmlmixed).
 *
 * Two-layer selector-self-match guard applied per project protocol:
 * Layer 1 (belt): PHP + JS comments stripped before assertions.
 * Layer 2 (suspenders): prose in this docblock avoids literal source tokens.
 */
class EmbedDc3963AI970CodeMirrorHighlightContractTest extends TestCase
{
    private string $settingsSrc;
    private string $settingsExecutable;

    private string $adminJsSrc;
    private string $adminJsExecutable;

    private string $adminBundle;
    private string $configSrc;

    protected function setUp(): void
    {
        parent::setUp();

        $settings = (string) file_get_contents(base_path('Modules/Embed/Filament/EmbedModuleSettings.php'));
        $this->settingsSrc = $settings;
        // Strip PHP block comments
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $settings);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);
        $this->settingsExecutable = $stripped;

        $adminJs = (string) file_get_contents(base_path('packages/frontend-assets/resources/assets/js/admin-filament.js'));
        $this->adminJsSrc = $adminJs;
        $stripped2 = preg_replace('~/\*[\s\S]*?\*/~s', '', $adminJs);
        $stripped2 = preg_replace('~//[^\n]*~', '', $stripped2);
        $this->adminJsExecutable = $stripped2;

        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/admin.js');
        $this->adminBundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';

        $this->configSrc = (string) file_get_contents(base_path('packages/frontend-assets-libs/config-common.js'));
    }

    // ── Group A: EmbedModuleSettings.php data attributes ─────────────────────

    public function test_textarea_carries_data_mw_codemirror_attribute(): void
    {
        $this->assertStringContainsString(
            "'data-mw-codemirror'",
            $this->settingsExecutable,
            'source_code textarea must carry data-mw-codemirror signal attribute'
        );
    }

    public function test_textarea_carries_reactive_data_mw_code_type_attribute(): void
    {
        $this->assertStringContainsString(
            "'data-mw-code-type'",
            $this->settingsExecutable,
            'source_code textarea must carry reactive data-mw-code-type attribute'
        );
    }

    public function test_data_mw_code_type_reads_from_get_for_reactivity(): void
    {
        $this->assertStringContainsString(
            "get('options.code_type')",
            $this->settingsExecutable,
            'data-mw-code-type must be driven by Get to update when code_type Select changes'
        );
    }

    public function test_textarea_extraInputAttributes_is_a_closure(): void
    {
        // Reactive attributes require a closure form, not a plain array
        $this->assertMatchesRegularExpression(
            '~->extraInputAttributes\s*\(\s*fn\s*\(~',
            $this->settingsExecutable,
            'extraInputAttributes must use a closure (fn) so data-mw-code-type is reactive'
        );
    }

    // ── Group B: admin-filament.js method structure ────────────────────────

    public function test_initEmbedCodeMirror_method_exists(): void
    {
        $this->assertStringContainsString(
            'initEmbedCodeMirror()',
            $this->adminJsSrc,
            'admin-filament.js must define an initEmbedCodeMirror() method'
        );
    }

    public function test_initEmbedCodeMirror_called_from_init(): void
    {
        // Find the init() method body and check the call is present
        $initPos = strrpos($this->adminJsExecutable, 'init()');
        $this->assertNotFalse($initPos, 'init() method not found');
        $slice = substr($this->adminJsExecutable, $initPos, 3000);
        $this->assertStringContainsString(
            'this.initEmbedCodeMirror()',
            $slice,
            'initEmbedCodeMirror() must be called from init()'
        );
    }

    public function test_sequential_mode_file_chain_present(): void
    {
        $src = $this->adminJsSrc;
        $this->assertStringContainsString('xml.js', $src, 'xml mode file must be in load chain');
        $this->assertStringContainsString('css.js', $src, 'css mode file must be in load chain');
        $this->assertStringContainsString('javascript.js', $src, 'javascript mode file must be in load chain');
        $this->assertStringContainsString('htmlmixed.js', $src, 'htmlmixed mode file must be in load chain');
    }

    public function test_htmlmixed_is_default_mode(): void
    {
        $this->assertStringContainsString(
            "'htmlmixed'",
            $this->adminJsExecutable,
            "htmlmixed must be the default CodeMirror mode for HTML code type"
        );
    }

    public function test_mutation_observer_watches_data_mw_code_type(): void
    {
        $this->assertStringContainsString(
            "'data-mw-code-type'",
            $this->adminJsExecutable,
            'MutationObserver must watch data-mw-code-type attribute changes for mode switching'
        );
    }

    public function test_codemirror_blur_validation_in_js(): void
    {
        $this->assertStringContainsString(
            'validateSyntax',
            $this->adminJsExecutable,
            'Blur-time syntax validation must be wired in JS (not on the hidden textarea element)'
        );
    }

    public function test_livewire_sync_via_input_event(): void
    {
        $this->assertStringContainsString(
            "new Event('input'",
            $this->adminJsExecutable,
            'CodeMirror changes must dispatch an input event so Livewire wire:model stays in sync'
        );
    }

    // ── Group C: built bundle runtime probe ───────────────────────────────

    public function test_built_bundle_contains_initEmbedCodeMirror(): void
    {
        if ($this->adminBundle === '') {
            $this->markTestSkipped('admin.js bundle not built — run npm run build in packages/frontend-assets');
        }
        $this->assertStringContainsString(
            'initEmbedCodeMirror',
            $this->adminBundle,
            'Built admin.js bundle must include initEmbedCodeMirror'
        );
    }

    public function test_built_bundle_contains_htmlmixed_mode_string(): void
    {
        if ($this->adminBundle === '') {
            $this->markTestSkipped('admin.js bundle not built');
        }
        $this->assertStringContainsString(
            'htmlmixed',
            $this->adminBundle,
            'Built admin.js bundle must reference htmlmixed mode'
        );
    }

    // ── Group D: config-common.js mode files in build ─────────────────────

    public function test_config_common_includes_xml_mode(): void
    {
        $this->assertStringContainsString(
            'mode/xml/xml.js',
            $this->configSrc,
            'config-common.js must include xml.js mode for htmlmixed dependency'
        );
    }

    public function test_config_common_includes_css_mode(): void
    {
        $this->assertStringContainsString(
            'mode/css/css.js',
            $this->configSrc,
            'config-common.js must include css.js mode for CSS and htmlmixed'
        );
    }

    public function test_config_common_includes_javascript_mode(): void
    {
        $this->assertStringContainsString(
            'mode/javascript/javascript.js',
            $this->configSrc,
            'config-common.js must include javascript.js mode'
        );
    }

    public function test_config_common_includes_htmlmixed_mode(): void
    {
        $this->assertStringContainsString(
            'mode/htmlmixed/htmlmixed.js',
            $this->configSrc,
            'config-common.js must include htmlmixed.js mode'
        );
    }

    // ── Group E: public vendor files published ────────────────────────────

    public function test_public_codemirror_xml_mode_exists(): void
    {
        $path = base_path('public/vendor/microweber-packages/frontend-assets-libs/codemirror/xml.js');
        $this->assertFileExists($path, 'xml.js mode file must be published to public vendor');
    }

    public function test_public_codemirror_css_mode_exists(): void
    {
        $path = base_path('public/vendor/microweber-packages/frontend-assets-libs/codemirror/css.js');
        $this->assertFileExists($path, 'css.js mode file must be published to public vendor');
    }

    public function test_public_codemirror_javascript_mode_exists(): void
    {
        $path = base_path('public/vendor/microweber-packages/frontend-assets-libs/codemirror/javascript.js');
        $this->assertFileExists($path, 'javascript.js mode file must be published to public vendor');
    }

    public function test_public_codemirror_htmlmixed_mode_exists(): void
    {
        $path = base_path('public/vendor/microweber-packages/frontend-assets-libs/codemirror/htmlmixed.js');
        $this->assertFileExists($path, 'htmlmixed.js mode file must be published to public vendor');
    }

    // ── Group F: markers and task-id ─────────────────────────────────────

    public function test_task_id_marker_in_settings(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-dc3963',
            $this->settingsSrc,
            'EmbedModuleSettings must carry the AI-970 task-id marker'
        );
    }

    public function test_task_id_marker_in_admin_js(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-dc3963',
            $this->adminJsSrc,
            'admin-filament.js must carry the AI-970 task-id marker'
        );
    }

    public function test_config_common_marker_present(): void
    {
        $this->assertStringContainsString(
            'AI-970',
            $this->configSrc,
            'config-common.js must carry an AI-970 marker comment near the mode file entries'
        );
    }
}
