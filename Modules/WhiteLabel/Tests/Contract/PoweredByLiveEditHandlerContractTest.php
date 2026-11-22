<?php

declare(strict_types=1);

namespace Modules\WhiteLabel\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the "edit the Powered-by / White Label footer in
 * Live Edit" affordance.
 *
 * The footer credit is emitted by powered_by_link() as a plain
 * span.mw-powered-by (not a module element), so it only becomes editable
 * because the canvas script (live-edit-page-scripts.js) binds a handler that
 * opens the White Label settings. That handler was once disabled and the footer
 * silently stopped responding to a double-click. It also must route through the
 * modern settings flow, not the deprecated iframe route whose type argument no
 * longer resolves to a Filament settings page.
 *
 * This pins both so neither regresses. Source-based (pre-boot); the executable
 * source is comment-stripped in setUp so the absence assertion cannot match this
 * file's own prose nor the handler's explanatory comments.
 */
final class PoweredByLiveEditHandlerContractTest extends TestCase
{
    private string $code = '';

    protected function setUp(): void
    {
        $root = dirname(__DIR__, 4);
        $path = $root . '/packages/frontend-assets/resources/assets/live-edit/live-edit-page-scripts.js';
        $this->assertFileExists($path, 'live-edit-page-scripts.js (canvas script) is missing.');

        $src = (string) file_get_contents($path);

        // Strip block and line comments so neither the handler's own comment
        // prose nor a stray reference can satisfy the assertions below.
        $src = preg_replace('~/\*.*?\*/~s', '', $src) ?? $src;
        $src = preg_replace('~(^|[^:])//.*$~m', '$1', $src) ?? $src;

        $this->code = $src;
    }

    #[Test]
    public function double_clicking_the_powered_by_footer_opens_white_label_settings(): void
    {
        $this->assertStringContainsString('.mw-powered-by', $this->code,
            'The canvas must bind a handler to the powered-by footer.');
        $this->assertMatchesRegularExpression('~dblclick~', $this->code,
            'The powered-by footer must be editable on double-click (module parity).');
        $this->assertStringContainsString('white_label', $this->code,
            'The handler must resolve the white_label settings component.');
    }

    #[Test]
    public function handler_uses_the_modern_module_settings_flow(): void
    {
        // The modern flow dispatches openModuleSettingsAction on the top window,
        // where AdminLiveEditPage mounts the registered settings component.
        $this->assertStringContainsString('openModuleSettingsAction', $this->code,
            'The handler must dispatch openModuleSettingsAction (modern settings flow).');
        $this->assertStringContainsString('liveEditModuleSettingsComponents', $this->code,
            'The handler must resolve the component from the top-window settings map.');
    }

    #[Test]
    public function handler_does_not_use_the_dead_legacy_settings_route(): void
    {
        // The deprecated call opened live_edit.module_settings with a module type
        // that no longer maps to a Filament page, so it silently did nothing.
        $this->assertStringNotContainsString('open_global_module_settings_modal', $this->code,
            'The powered-by handler must not call the deprecated global module-settings modal.');
    }
}
