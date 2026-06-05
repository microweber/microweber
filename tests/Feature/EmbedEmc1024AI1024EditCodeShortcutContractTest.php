<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-emc1024 / AI-1024 — Embed module "Edit code" floating-toolbar
 * shortcut. Jira: https://microweber.atlassian.net/browse/AI-1024
 *
 * When an Embed module is selected on the live-edit canvas the floating toolbar
 * now carries an "Edit code" button that opens the module settings and scrolls/
 * focuses the source_code CodeMirror editor directly — skipping the manual
 * "open settings → scroll to textarea" steps. The button is shown ONLY on Embed
 * modules (onTarget gates on type === 'embed').
 *
 * Source:
 *   - handle-icons.js — a 'code' (brackets) glyph.
 *   - module.js primaryMenu — the gated button + moduleSettingsDispatch + a
 *     best-effort focus of [data-mw-codemirror] (the AI-970 Embed editor).
 */
class EmbedEmc1024AI1024EditCodeShortcutContractTest extends TestCase
{
    private string $icons;
    private string $module;

    protected function setUp(): void
    {
        parent::setUp();
        $this->icons = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/handle-icons.js'
        ));
        $this->module = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/handles-content/module.js'
        ));
    }

    #[Test]
    public function code_icon_registered(): void
    {
        $this->assertMatchesRegularExpression(
            "/name:\s*'code'/",
            $this->icons,
            "HandleIcons must register a 'code' glyph for the Edit-code shortcut."
        );
    }

    #[Test]
    public function edit_code_button_present_with_marker_class(): void
    {
        $this->assertStringContainsString("title: 'Edit code'", $this->module,
            'module.js must add an "Edit code" floating-toolbar button.');
        $this->assertStringContainsString("className: 'mw-handle-edit-code-button'", $this->module,
            'The Edit-code button must carry the mw-handle-edit-code-button marker class.');
        $this->assertStringContainsString("handleIcons.icon('code')", $this->module,
            'The Edit-code button must use the code icon.');
    }

    #[Test]
    public function button_gated_to_embed_modules_only(): void
    {
        // onTarget must show the button only when the selected module type is
        // 'embed', and hide it otherwise (so non-Embed modules are unaffected).
        $this->assertMatchesRegularExpression(
            "/String\(type\)\.trim\(\)\s*===\s*'embed'/",
            $this->module,
            'The Edit-code button must gate visibility on type === \'embed\'.'
        );
        $this->assertStringContainsString('mw-le-handle-menu-button-hidden', $this->module,
            'The gate must toggle the standard hidden class for handle-menu buttons.');
    }

    #[Test]
    public function action_opens_settings_and_focuses_code_editor(): void
    {
        // Opens the same settings surface as Edit...
        $this->assertStringContainsString('moduleSettingsDispatch(target)', $this->module,
            'The Edit-code action must open the module settings via moduleSettingsDispatch.');
        // ...then best-effort focuses the AI-970 CodeMirror editor.
        $this->assertStringContainsString("querySelector('[data-mw-codemirror=\"true\"]')", $this->module,
            'The Edit-code action must locate the [data-mw-codemirror] editor to focus it.');
        $this->assertStringContainsString('scrollIntoView', $this->module,
            'The Edit-code action must scroll the code editor into view.');
    }
}
