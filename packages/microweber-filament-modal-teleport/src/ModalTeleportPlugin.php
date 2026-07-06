<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;

/**
 * Filament Modal Inert Fix Plugin
 * (package id / namespace keep the historical "modal-teleport" name)
 *
 * Fixes inline form/schema-action modals that render un-clickable because the
 * modal focus-trap marks an ANCESTOR (`.fi-main`) `inert`, which propagates into
 * the modal. The injected JS clears that stray `inert` IN PLACE — no DOM move,
 * so Livewire `wire:model`/`wire:submit` keep working. (DOM teleport and CSS
 * stacking-context overrides were both tried and rejected — see the injected
 * assets view for the full diagnosis.)
 *
 * Usage:
 *   $panel->plugin(ModalTeleportPlugin::make());
 *
 * Supports:
 *   - Centered modals
 *   - SlideOver modals
 *   - Nested/stacked modals (N levels deep, any combination)
 *   - Filament v3 / v4 / v5
 *   - Any Filament project (not Microweber-specific)
 */
class ModalTeleportPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'mw-modal-teleport';
    }

    public function register(Panel $panel): void
    {
        // Register the render hook HERE, not in boot(). Filament flushes a
        // panel's render hooks into the global FilamentView registry during
        // Panel::boot(); a hook added from a plugin's boot() lands after that
        // flush and never renders. register() runs during panel configuration,
        // before the flush, so the hook is picked up.
        //
        // Resolve the CSS path in this PHP class file (__DIR__ is reliable);
        // resolving it inside the Blade would compile to storage/framework/views/
        // and silently break the relative path.
        $cssPath = dirname(__DIR__) . '/resources/css/modal-stacking-context-fix.css';

        $panel->renderHook(
            name: PanelsRenderHook::BODY_END,
            // Render lazily at request time (the view/blade system is fully
            // booted by then; register() may run before it is).
            hook: fn (): HtmlString => new HtmlString(
                view('mw-modal-teleport::modal-teleport-assets', ['cssPath' => $cssPath])->render()
            ),
        );
    }

    public function boot(Panel $panel): void
    {
        // Nothing to do at boot — the render hook is registered in register().
    }
}
