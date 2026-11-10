<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*/

namespace MicroweberPackages\Filament\Providers;


use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Filament\Support\RegistersMwDialogMacro;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;

class MicroweberFilamentServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // Register core Filament v5 panel providers
        $this->app->register(MicroweberFilamentThemeServiceProvider::class);
        $this->app->register(FilamentAdminPanelProvider::class);
    }

    public function boot()
    {
        RegistersMwDialogMacro::register();

        // Global-search entries are now registered by each module's own
        // ServiceProvider via FilamentRegistry::registerGlobalSearchEntry();
        // the old hardcoded GlobalSearchRegistrar has been removed.

        // Register Livewire's JavaScript via a render hook so it is injected at
        // render time (with full data attributes like data-update-uri, data-csrf, etc.).
        // The custom Livewire fork does not register itself with Filament – we do it here.
        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_BEFORE,
            fn (): HtmlString => new HtmlString(
                \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts()
            ),
        );

        // AI chat sidebar: load the standalone sidebar service (window.mw.aiChat +
        // the mw:ai-chat:* event listeners) plus the streaming client, and add a
        // floating trigger that opens the chat for the admin 'general' agent. The
        // same event API powers Live Edit — here it runs without a canvas.
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            function (): HtmlString {
                // Admin PANEL only, admins only. This hook fires on EVERY panel's
                // pages (admin, checkout, profile, vendor) and even the logged-out
                // /admin login screen. The AI chat belongs to the admin panel, so
                // render it only there AND only for an authenticated admin — never
                // on the storefront checkout/profile/vendor panels.
                if (!self::isAdminPanel() || !self::viewerIsAdmin()) {
                    return new HtmlString('');
                }

                // Cache-bust with the built file's mtime so a rebuilt bundle is
                // never served stale from the browser cache (these assets carry
                // no content hash in their filename).
                $ver = function (string $rel): string {
                    $path = public_path($rel);
                    $url = asset($rel);
                    return is_file($path) ? ($url . '?v=' . filemtime($path)) : $url;
                };
                $mwAi = $ver('modules/ai/js/mw-ai.js');
                $sidebar = $ver('vendor/microweber-packages/frontend-assets/build/ai-chat-sidebar.js');
                $title = json_encode('AI assistant');

                // The sidebar scripts load on every admin page (the chat service +
                // streaming client). The TOPBAR trigger button is rendered
                // separately via the USER_MENU_BEFORE hook below so it sits in the
                // topbar next to the notifications bell / avatar — no longer a
                // floating bottom-right bubble. In LIVE EDIT the topbar is hidden
                // (iframe) and the Live-Edit toolbar carries its own AI button, so
                // only the scripts load here. task-2026-11-10.
                $scripts = <<<HTML
<script src="{$mwAi}"></script>
<script src="{$sidebar}"></script>
HTML;

                return new HtmlString($scripts);
            },
        );

        // AI chat TRIGGER in the admin topbar — next to the notifications bell and
        // the user avatar (USER_MENU_BEFORE). Moved here from a floating
        // bottom-right bubble so the AI entry point lives in the topbar chrome.
        // Admin panel + admins only, and never in the Live-Edit iframe (its
        // toolbar carries its own AI button).
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            function (): HtmlString {
                if (!self::isAdminPanel() || !self::viewerIsAdmin()) {
                    return new HtmlString('');
                }
                if (str_contains((string) request()->path(), 'live-edit')) {
                    return new HtmlString('');
                }

                $title = json_encode('AI assistant');

                return new HtmlString(<<<HTML
<button type="button" id="mw-admin-ai-chat-trigger" class="fi-icon-btn mw-admin-ai-chat-topbar"
    aria-label="AI assistant" title="AI assistant"
    style="display:inline-flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:0.5rem;border:none;background:transparent;color:inherit;cursor:pointer;">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
</button>
<script>
(function(){
    var btn = document.getElementById('mw-admin-ai-chat-trigger');
    if (!btn || btn.dataset.mwBound) { return; }
    btn.dataset.mwBound = '1';
    btn.addEventListener('click', function(){
        window.dispatchEvent(new CustomEvent('mw:ai-chat:toggle', { detail: { agentType: 'general', title: {$title}, canvas: false } }));
    });
})();
</script>
HTML);
            },
        );

        // Inject Microweber admin JS assets (admin.js, libs, etc.) into the Filament head.
        // Using FilamentView::registerRenderHook (global) so it fires for all admin pages.
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            function (): HtmlString {
                $renderer = new \MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer();
                return new HtmlString($renderer->getHeadMetaTags());
            },
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            function (): HtmlString {
                $renderer = new \MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer();
                return new HtmlString($renderer->getFooterMetaTags());
            },
        );

        // Roomier list-table rows. Filament sets the record content container's
        // block padding as a Tailwind @layer utility; the theme bundle's own
        // rules are ALSO layered, so even !important cannot outrank it. An
        // UNLAYERED <style> injected after the panel styles wins cleanly — this
        // is the only reliable lever (verified: layered overrides stay at 4px).
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): HtmlString => new HtmlString(
                '<style id="mw-roomier-table-rows">'
                . 'body.fi-panel-admin .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                . 'body.fi-panel-checkout .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                . 'body.fi-panel-profile .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                . 'body.fi-panel-vendor .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                // Custom HasTable pages (vendor/profile "My Orders", "Order
                // History", invoices, …) carry .mw-records-page so their rows
                // are as roomy as the resource-list tables, on any panel.
                . '.mw-records-page .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn{'
                . 'padding-top:16px !important;padding-bottom:16px !important;}'
                // Tablet/mobile row clipping: the record content container is a
                // nowrap flexbox whose first child (columns wrapper) and the
                // growable name column both default to min-width:auto, so a long
                // page/product title refuses to shrink and pushes the trailing
                // status + actions column off the right edge (the row-level ⋮
                // menu becomes unreachable at ~768px and narrower). Restoring
                // min-width:0 down that flex chain lets the already-truncating
                // title cell (.truncate) clip with an ellipsis so every column
                // stays on screen. Harmless on desktop — it only engages when
                // horizontal space is actually constrained.
                // The content container ITSELF is a flex child of .fi-ta-record
                // (sibling to the selection checkbox); without min-width:0 it keeps
                // its content size (e.g. 359px) and overflows the row on mobile —
                // the fix on its children alone wasn't enough. Shrink the container
                // too so the whole record stays within the viewport.
                . '.fi-ta-record-content-ctn{min-width:0;}'
                . '.fi-ta-record-content-ctn > *{min-width:0;}'
                . '.fi-ta-record-content-ctn .fi-ta-col.fi-growable{min-width:0;}'
                . '</style>'
            ),
        );

        // Empty-state cleanup for the in-modal list/repeater tables used by module
        // settings (accordion, tabs, faq, testimonials, posts, custom fields, …).
        // When a table has ZERO rows Filament still renders the bulk-action / sort
        // toolbar band AND the column-header row, leaving a tall empty void with a
        // tiny "No items found" floating in the middle. When empty, drop that
        // toolbar band + the column header, and tighten the empty-state padding so
        // it reads as intentional. Injected UNLAYERED so it beats Filament's own
        // layered table utilities. Scoped to modal/slide-over tables so the
        // full-page admin resource tables are untouched.
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): HtmlString => new HtmlString(
                '<style id="mw-empty-table-cleanup">'
                . '.fi-modal .fi-ta:has(.fi-ta-empty-state) .fi-ta-header-toolbar,'
                . '.mw-livewire-modal-content .fi-ta:has(.fi-ta-empty-state) .fi-ta-header-toolbar,'
                . '.fi-modal .fi-ta:has(.fi-ta-empty-state) thead,'
                . '.mw-livewire-modal-content .fi-ta:has(.fi-ta-empty-state) thead{display:none !important;}'
                . '.fi-modal .fi-ta-empty-state,'
                . '.mw-livewire-modal-content .fi-ta-empty-state{padding-top:28px !important;padding-bottom:28px !important;}'
                . '.fi-modal .fi-ta-empty-state-icon-ctn,'
                . '.mw-livewire-modal-content .fi-ta-empty-state-icon-ctn{width:2.75rem !important;height:2.75rem !important;}'
                // Also collapse the toolbar band on in-modal list tables that have
                // NO search field configured (faq, products, …): Filament still
                // reserves a full-width tinted band that reads as a broken/empty
                // search input with the action button floating beside it. Tables
                // that DO configure search (e.g. slider) keep their search field.
                . '.fi-modal .fi-ta-header-toolbar:not(:has(input:not([type=checkbox]):not([type=hidden]))),'
                . '.mw-livewire-modal-content .fi-ta-header-toolbar:not(:has(input:not([type=checkbox]):not([type=hidden]))){display:none !important;}'
                . '</style>'
            ),
        );

        // Hide the "0" filter-count badge on the table Filter button. Filament's
        // core table view unconditionally sets the trigger badge to the active
        // filter count (`->badge($activeFiltersCount)`), and because Laravel's
        // filled(0) is TRUE (0 is numeric, not blank), a lone "0" badge renders
        // on every unfiltered table — reading as a stray glitch. We cannot fix it
        // via ->filtersTriggerAction() (the core view overwrites the badge at
        // render) and CSS cannot match text, so a tiny scoped script hides the
        // badge container only while it reads "0"/empty and reveals it again as
        // soon as the count is >0. Scoped to the two filter-trigger containers
        // (dropdown + collapsible layouts) so real column/other badges of "0"
        // are untouched; re-runs after Livewire updates so applying/clearing a
        // filter keeps it correct.
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): HtmlString => new HtmlString(<<<'HTML'
<script>
(function () {
    var SEL = '.fi-ta-filters-dropdown .fi-icon-btn-badge-ctn,'
            + '.fi-ta-filters-trigger-action-ctn .fi-icon-btn-badge-ctn';
    function fix() {
        try {
            document.querySelectorAll(SEL).forEach(function (ctn) {
                var t = (ctn.textContent || '').trim();
                ctn.style.display = (t === '' || t === '0') ? 'none' : '';
            });
        } catch (e) {}
    }
    function schedule() { requestAnimationFrame(fix); }
    if (document.readyState !== 'loading') { schedule(); }
    document.addEventListener('DOMContentLoaded', schedule);
    document.addEventListener('livewire:navigated', schedule);
    document.addEventListener('livewire:init', function () {
        try {
            window.Livewire.hook('morph.updated', schedule);
            window.Livewire.hook('morphed', schedule);
            window.Livewire.hook('commit', function (o) { if (o && o.respond) { o.respond(schedule); } });
        } catch (e) {}
    });
})();
</script>
HTML
            ),
        );

        // Register custom Filament panel component views (e.g. layout.live-edit) under 'filament-panels' namespace
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament',
            'filament-panels'
        );

        // Register custom Filament component views under 'mw-filament' namespace
        // This replaces the deprecated 'filament-forms::components.' pattern
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-forms',
            'mw-filament'
        );

        // Register custom Filament table column views - prepend to 'filament-tables' namespace
        // so our custom columns (ClickableColumn, SVGColumn, BadgesColumn, etc.) are found
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-tables',
            'filament-tables'
        );

        // Register custom Filament action/infolist views
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-actions',
            'filament-actions'
        );

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-infolists',
            'filament-infolists'
        );
    }

    /**
     * True only when the current viewer is an authenticated admin. Used to gate
     * the AI chat entry point so it never renders on the logged-out admin login
     * screen (or for a non-admin session). Best-effort / never throws.
     */
    /**
     * True only while rendering inside the admin panel — so the AI chat entry
     * point never appears on the storefront checkout / profile / vendor panels.
     */
    protected static function isAdminPanel(): bool
    {
        try {
            $panel = \Filament\Facades\Filament::getCurrentPanel();

            return $panel !== null && $panel->getId() === 'admin';
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected static function viewerIsAdmin(): bool
    {
        try {
            if (function_exists('is_admin')) {
                return (bool) is_admin();
            }
            $user = auth()->user();

            return $user !== null && (int) ($user->is_admin ?? 0) === 1;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
