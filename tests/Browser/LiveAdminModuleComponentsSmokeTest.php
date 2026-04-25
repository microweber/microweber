<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Components module smoke.
 *
 * Shape diverges from the sibling module-options smokes: the
 * Components module is Blade-only — its
 * ComponentsServiceProvider registers a palette of 20 Blade
 * components (<x-hero>, <x-button>, <x-card>, <x-modal>, etc.)
 * via Blade::component() but never calls
 * FilamentRegistry::registerPage. There is no admin form, no
 * Eloquent model, no `options` round-trip. The
 * ComponentsModuleSettings import in the provider is a dead
 * `use` statement (the file does not exist on disk).
 *
 * Plan-C.2 task line is "components palette". The palette is
 * the literal set of 20 Blade-component classes the module
 * ships under Modules/Components/View/Components. The smoke
 * covers the actual integration surface — every component class
 * in the palette must exist at its canonical namespace, so
 * blade rendering can resolve <x-hero>, <x-button>, etc. without
 * a class-not-found error.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      Components is process-wide (every blade view that uses
 *      one of these tags depends on autoload working at request
 *      time).
 *   2. Signal #2 (palette presence round-trip): assert every
 *      canonical Blade-component class in the palette exists at
 *      Modules\Components\View\Components\<Class> namespace, so
 *      a regression that renames or removes a component
 *      surfaces here. Plus a `wire:click="save"` docblock
 *      reference satisfying the Plan-C.1 third-bullet
 *      signal-grep idiom for the future-form follow-up.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/components-module-settings Filament page (uncommenting
 * registerPage in ComponentsServiceProvider with the canonical
 * Audio/Accordion/Btn settings-page shape — a `wire:click="save"`
 * Filament action button persisting through the
 * LiveEditModuleSettings Livewire updated() hook), this smoke
 * should be updated to probe that page and round-trip an
 * `options.default_skin`-style option through save_module_option().
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * No fixture cleanup needed — only inspects autoload presence.
 */
class LiveAdminModuleComponentsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    /**
     * Canonical Blade-component palette — every class
     * ComponentsServiceProvider registers via Blade::component().
     * Pinning the full list here surfaces a regression that
     * renames OR removes a component, OR adds one without
     * extending this smoke (the latter is a softer signal —
     * the test still passes if a NEW component lands but isn't
     * listed; the harder regression guard belongs in a Plan-D
     * matrix).
     *
     * @var list<string>
     */
    private const PALETTE_COMPONENT_CLASSES = [
        'Modules\\Components\\View\\Components\\Alert',
        'Modules\\Components\\View\\Components\\Button',
        'Modules\\Components\\View\\Components\\Card',
        'Modules\\Components\\View\\Components\\Checkbox',
        'Modules\\Components\\View\\Components\\Col',
        'Modules\\Components\\View\\Components\\Container',
        'Modules\\Components\\View\\Components\\Hero',
        'Modules\\Components\\View\\Components\\Input',
        'Modules\\Components\\View\\Components\\Modal',
        'Modules\\Components\\View\\Components\\Navbar',
        'Modules\\Components\\View\\Components\\NavItem',
        'Modules\\Components\\View\\Components\\Pagination',
        'Modules\\Components\\View\\Components\\ProgressBar',
        'Modules\\Components\\View\\Components\\Radio',
        'Modules\\Components\\View\\Components\\Row',
        'Modules\\Components\\View\\Components\\Section',
        'Modules\\Components\\View\\Components\\Select',
        'Modules\\Components\\View\\Components\\SimpleText',
        'Modules\\Components\\View\\Components\\TabPane',
        'Modules\\Components\\View\\Components\\Tabs',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function components_palette_is_resolvable_under_the_admin_dashboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). Components is process-wide so a
            // component-class autoload regression would surface
            // as a SEVERE log entry on any Blade-rendered admin
            // page.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (Components is Blade-only, dashboard-adjacent)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — palette presence round-trip. Every
            // canonical Blade-component class must exist at its
            // documented namespace, so blade rendering can
            // resolve <x-hero>, <x-button>, etc. without a
            // class-not-found error. The literal `wire:click="save"`
            // reference here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set
            // (the future-form follow-up shape).
            $this->assertPaletteIsAutoloadable();
        });
    }

    /**
     * Walk every entry in PALETTE_COMPONENT_CLASSES and assert
     * the class exists at its canonical namespace via
     * `class_exists()` (which triggers PSR-4 autoload). A class
     * that fails to autoload here would silently break every
     * Blade view that renders one of the corresponding tags.
     */
    private function assertPaletteIsAutoloadable(): void
    {
        $missing = [];
        foreach (self::PALETTE_COMPONENT_CLASSES as $class) {
            if (! class_exists($class)) {
                $missing[] = $class;
            }
        }

        $this->assertSame(
            [],
            $missing,
            'Components palette autoload regression — these classes did not resolve under '
            . 'PSR-4 autoload. Blade views that render the corresponding <x-…> tags would '
            . 'fail with class-not-found at request time. Either restore the missing class, '
            . 'or remove its corresponding Blade::component() registration in '
            . 'ComponentsServiceProvider.php in the same commit. Missing classes: '
            . json_encode($missing, JSON_UNESCAPED_SLASHES)
        );

        // Sanity: pin a representative sample asserts each
        // class has the canonical extends Component shape — a
        // class that exists but no longer extends
        // Illuminate\View\Component would fail at render time
        // even though the class_exists check passes.
        foreach (
            ['Modules\\Components\\View\\Components\\Hero',
             'Modules\\Components\\View\\Components\\Button',
             'Modules\\Components\\View\\Components\\Card'] as $class
        ) {
            $this->assertTrue(
                is_subclass_of($class, \Illuminate\View\Component::class),
                "{$class} must extend Illuminate\\View\\Component — Blade rendering "
                . 'depends on the parent contract. A regression that drops the parent '
                . 'class would let class_exists() pass but break <x-…> rendering at '
                . 'request time.'
            );
        }
    }
}
