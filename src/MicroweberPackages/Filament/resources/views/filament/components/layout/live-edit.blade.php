@php
    use Filament\Support\Enums\Width as MaxWidth;
@endphp
@push('scripts')

<script>
    addEventListener('DOMContentLoaded', () => {

        const adminLink = document.getElementById('mw-live-edit-toolbar-back-to-admin-link');
    if(adminLink) {
       adminLink.addEventListener('click', e => {
            e.preventDefault();
             mw.app.liveEditWidgets.toggleAdminSidebar()

        });
    }

    });
</script>

{{-- task-2026-05-18-76a360 — Alpine.data() factory for the Add Content
     modal MUST be registered at INITIAL page load, not inside the modal
     Blade view. Filament/Livewire morph-inserts the modal HTML when the
     +ADD action mounts, but embedded <script> tags from morph-inserted
     HTML DO NOT EXECUTE in the browser — so the in-modal AI-790 script
     never ran, the factory never registered, and `x-data="addContentModal"`
     failed with "addContentModal is not defined" leaving cards invisible.

     Fix: register the factory HERE in @push('scripts') so it rides the
     initial admin-frame page render, then `Alpine.data('addContentModal',
     factory)` is globally available by the time Filament mounts the
     modal subtree. Dual-path init (eager + alpine:init) covers both
     script-loaded-before-Alpine + script-loaded-after-Alpine cases. --}}
<script>
    (function () {
        const factory = () => ({
            q: '',

            // task-2026-05-16-de4ce4 / AI-694 — filter accepts BOTH
            // display:none (legacy x-show path) AND visibility:hidden
            // (the new no-reflow path).
            visibleCards() {
                return Array.from(this.$root.querySelectorAll('[data-mw-add-content-card]'))
                    .filter(el => {
                        const s = window.getComputedStyle(el);
                        return s.display !== 'none' && s.visibility !== 'hidden';
                    });
            },

            focusFirstVisibleCard() {
                const cards = this.visibleCards();
                if (cards.length) cards[0].focus();
            },

            focusLastVisibleCard() {
                const cards = this.visibleCards();
                if (cards.length) cards[cards.length - 1].focus();
            },

            focusNextCard(current) {
                const cards = this.visibleCards();
                const i = cards.indexOf(current);
                if (i === -1 || i === cards.length - 1) {
                    this.$refs.search.focus();
                } else {
                    cards[i + 1].focus();
                }
            },

            focusPrevCard(current) {
                const cards = this.visibleCards();
                const i = cards.indexOf(current);
                if (i === -1 || i === 0) {
                    this.$refs.search.focus();
                } else {
                    cards[i - 1].focus();
                }
            },

            activateFirstVisibleCard() {
                const cards = this.visibleCards();
                if (cards.length === 1) {
                    cards[0].click();
                } else if (cards.length > 1) {
                    cards[0].focus();
                } else if (this.q !== '') {
                    // task-2026-05-16-de4ce4 / AI-694 — zero-match ENTER
                    // fallback: ENTER still activates the primary "Add a
                    // block" card so users never hit an ENTER dead-end.
                    const primary = this.$root.querySelector(
                        '[data-mw-add-content-card][data-mw-add-content-group=' + JSON.stringify('primary') + ']'
                    );
                    if (primary) primary.click();
                }
            },

            visibleCount() {
                return this.visibleCards().length;
            },

            // task-2026-05-16-968a71 / AI-692 / §A2-3: group-aware
            // visibility helper for the two-group layout.
            hasVisibleCardsInGroup(group) {
                const cards = Array.from(
                    this.$root.querySelectorAll('[data-mw-add-content-card][data-mw-add-content-group=' + JSON.stringify(group) + ']')
                );
                if (cards.length === 0) return false;
                if (this.q === '') return true;
                const needle = this.q.toLowerCase();
                return cards.some(c => (c.dataset.mwAddContentHaystack || '').includes(needle));
            },

            resultAnnouncement() {
                const total = this.$root.querySelectorAll('[data-mw-add-content-card]').length;
                const shown = this.visibleCount();
                if (this.q === '') return '';
                if (shown === 0) return 'No results.';
                if (shown === total) return 'All ' + total + ' options visible.';
                if (shown === 1) return '1 result.';
                return shown + ' results.';
            },
        });

        const register = () => {
            if (typeof window.Alpine === 'undefined' || typeof window.Alpine.data !== 'function') {
                return;
            }
            if (window.__mwAddContentModalRegistered) return;
            window.__mwAddContentModalRegistered = true;
            window.Alpine.data('addContentModal', factory);

            // task-2026-05-22-6eb365 / AI-752 — DOM-already-live guard.
            // Edge case: if the modal is already in the DOM when the factory
            // registers (e.g. on Livewire full-page component reload), Alpine
            // has already processed the element and found no factory, leaving
            // it with visible unprocessed x-data. Manually re-init the subtree.
            const existingModal = document.querySelector('[x-data="addContentModal"]');
            if (existingModal && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(existingModal);
            }
        };

        // Eager: if Alpine is already running by the time this script
        // body executes, register immediately. Otherwise wait for
        // alpine:init. Dual-path covers both bundle-order outcomes.
        if (typeof window.Alpine !== 'undefined' && typeof window.Alpine.data === 'function') {
            register();
        } else {
            document.addEventListener('alpine:init', register);
        }

        // task-2026-05-22-6eb365 / AI-752 — Livewire navigation listener.
        // Livewire v4 `navigate` feature re-renders the admin frame without
        // a full page reload, resetting window.__mwAddContentModalRegistered
        // to undefined without re-executing the scripts-push block. Adding a
        // `livewire:navigated` listener re-registers the factory so the modal
        // stays functional after Livewire-driven panel navigation.
        document.addEventListener('livewire:navigated', function () {
            window.__mwAddContentModalRegistered = false;
            register();
        });
    })();
</script>

 <style>
    /* task-2026-05-16-02760c: Filament v5 ships Tailwind v4, which uses
       the separate CSS `translate` property for its `-translate-x-full`
       utility on the sidebar. The slide-in animation below drives
       `transform`, so we must also reset `translate` to `none` —
       otherwise `translate: -100% !important` stays applied and the
       sidebar drawer renders off-screen at x=-280 even when `.active`
       sets the identity transform. User-visible symptom before the fix:
       clicking the live-edit toolbar hamburger added `.active` but no
       menu content appeared (sidebar was rendering off-screen left). */
    .fi-sidebar{
        position: absolute  !important;
        top:0;
        left:0;
        translate: none !important;
        transform: translateX(-100%) !important;
        transition: var(--toolbar-height-animation-speed) !important;
        z-index: 101 !important;
    }
     .fi-sidebar.active{

        transform: translateX(0%) !important;

    }

    /* task-2026-05-26-da6eab: Filament modals inside the live-edit layout
       suffer from a hit-testing issue — the iframe inside
       #live-edit-frame-holder absorbs pointer events that should reach
       position:fixed modal children. Primary fix: disable pointer events
       on the frame holder while any Filament modal is open so clicks and
       keyboard focus reach modal inputs natively. The JS below is a
       fallback rerouter for any residual cases not covered by the CSS. */
    .mw-live-edit-page:has(.fi-modal.fi-modal-open) #live-edit-frame-holder {
        pointer-events: none !important;
    }
 </style>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            var openModal = document.querySelector('.fi-modal.fi-modal-open');
            if (!openModal) return;
            if (openModal.contains(e.target)) return;

            var x = e.clientX;
            var y = e.clientY;
            var best = null;
            var bestDepth = -1;

            function walk(el, depth) {
                var rect = el.getBoundingClientRect();
                if (rect.width === 0 || rect.height === 0) return;
                if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) return;
                if (el.tagName === 'BUTTON' || el.tagName === 'A' ||
                    el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' ||
                    el.tagName === 'SELECT' ||
                    el.hasAttribute('wire:click') ||
                    el.hasAttribute('x-on:click') ||
                    el.hasAttribute('x-on:click.prevent') ||
                    el.classList.contains('fi-modal-close-overlay') ||
                    el.classList.contains('fi-modal-close-btn')) {
                    if (depth > bestDepth) {
                        best = el;
                        bestDepth = depth;
                    }
                }
                for (var i = 0; i < el.children.length; i++) {
                    walk(el.children[i], depth + 1);
                }
            }

            for (var i = 0; i < openModal.children.length; i++) {
                walk(openModal.children[i], 0);
            }

            if (best) {
                e.stopPropagation();
                e.preventDefault();
                best.click();
                // task-2026-05-28: for typeable elements also call .focus()
                // so keyboard events are routed to the element for typing.
                // Synthetic .click() alone does not reliably focus inputs.
                if (best.tagName === 'INPUT' || best.tagName === 'TEXTAREA' || best.tagName === 'SELECT') {
                    best.focus();
                }
            }
        }, true);
    });
 </script>
@endpush
<x-filament-panels::layout.base :livewire="$livewire">
    {{-- The sidebar is after the page content in the markup to fix issues with page content overlapping dropdown content from the sidebar. --}}
    {{-- AI-116 / TICKET-CI (cycle-107 2026-05-09): scoping class.
         Live-edit-specific CSS rules in
         packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css
         are scoped under `.mw-live-edit-page` so they don't leak to
         non-live-edit Filament panels (Posts list, Settings, etc.).
         The class is added here on the layout root so every page
         rendered through `filament-panels::components.layout.live-edit`
         picks it up automatically. --}}
    <div
        class="fi-layout mw-live-edit-page flex min-h-screen w-full flex-row-reverse overflow-x-clip"
    >
        <div
            @if (filament()->isSidebarCollapsibleOnDesktop())
                x-data="{}"
            x-bind:class="{
                    'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
                }"
            x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @elseif (filament()->isSidebarFullyCollapsibleOnDesktop())
                x-data="{}"
            x-bind:class="{
                    'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
                }"
            x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @elseif (! (filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop() || filament()->hasTopNavigation() || (! filament()->hasNavigation())))
                x-data="{}"
            x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @endif
            @class([
                'fi-main-ctn w-screen flex-1 flex-col',
                'h-full opacity-0 transition-all' => filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop(),
                'opacity-0' => ! (filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop() || filament()->hasTopNavigation() || (! filament()->hasNavigation())),
                'flex' => filament()->hasTopNavigation() || (! filament()->hasNavigation()),
            ])
        >

            <main
                @class([
                    ' ',
                    match ($maxContentWidth ??= (filament()->getMaxContentWidth() ?? MaxWidth::SevenExtraLarge)) {
                        MaxWidth::ExtraSmall, 'xs' => 'max-w-xs',
                        MaxWidth::Small, 'sm' => 'max-w-sm',
                        MaxWidth::Medium, 'md' => 'max-w-md',
                        MaxWidth::Large, 'lg' => 'max-w-lg',
                        MaxWidth::ExtraLarge, 'xl' => 'max-w-xl',
                        MaxWidth::TwoExtraLarge, '2xl' => 'max-w-2xl',
                        MaxWidth::ThreeExtraLarge, '3xl' => 'max-w-3xl',
                        MaxWidth::FourExtraLarge, '4xl' => 'max-w-4xl',
                        MaxWidth::FiveExtraLarge, '5xl' => 'max-w-5xl',
                        MaxWidth::SixExtraLarge, '6xl' => 'max-w-6xl',
                        MaxWidth::SevenExtraLarge, '7xl' => 'max-w-7xl',
                        MaxWidth::Full, 'full' => 'max-w-full',
                        MaxWidth::MinContent, 'min' => 'max-w-min',
                        MaxWidth::MaxContent, 'max' => 'max-w-max',
                        MaxWidth::FitContent, 'fit' => 'max-w-fit',
                        MaxWidth::Prose, 'prose' => 'max-w-prose',
                        MaxWidth::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
                        MaxWidth::ScreenMedium, 'screen-md' => 'max-w-screen-md',
                        MaxWidth::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
                        MaxWidth::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
                        MaxWidth::ScreenTwoExtraLarge, 'screen-2xl' => 'xmax-w-screen-2xl',
                        default => $maxContentWidth,
                    },
                ])
            >
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $livewire->getRenderHookScopes()) }}

                {{ $slot }}

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $livewire->getRenderHookScopes()) }}
            </main>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}
        </div>

        @if (filament()->hasNavigation())
            <div
                x-cloak
                x-data="{}"
                x-on:click="$store.sidebar.close()"
                x-show="$store.sidebar.isOpen"
                x-transition.opacity.300ms
                class="fi-sidebar-close-overlay fixed inset-0 z-30 bg-gray-950/50 transition duration-500 dark:bg-gray-950/75 lg:hidden"
                role="button"
                aria-label="Close sidebar"
            ></div>

            @livewire(filament()->getSidebarLivewireComponent())

        {{--
          task-2026-05-16-505ed5 / AI-708 — STALE controlBox removed.

          Previously this file created a SECOND controlBox titled
          "Template Style Editor" via `new mw.controlBox({ title:
          mw.lang('Template Style Editor'), id:
          'mw-live-edit-templateSettings-editor-box', ... })`. It
          duplicated the `template-settings-teleport-widget` controlBox
          created in `packages/frontend-assets/resources/assets/api-core/
          services/bootstrap.js`. The duplicate was unreachable —
          nothing calls `.show()` on `mw.top().app.templateSettingsBox`
          anywhere in the codebase — but its <h3 class="mw-control-box-
          title"> still mounted simultaneously alongside the live
          controlBox's <h3>, plus RightSidebar.vue's own <h3>. Three
          near-duplicate headings in one viewport (designer DOM-probe
          via Playwright, SOUL #110 audit).

          What was removed:
            - the <style> rule for #mw-live-edit-templateSettings-editor-box
              (CSS-only — no other surface referenced this id)
            - the dead <template>...</template> block (HTML <template>
              tag is inert; never executed)
            - the <script> block that created the controlBox and stored
              it as `mw.top().app.templateSettingsBox`

          The wrapper div `#live-edit-global-template-settings-component-
          wrapper` is kept (still hidden) as a back-compat DOM hook in
          case external admin code references the id. Its purpose was
          to be moved INTO the (now-removed) controlBox; with that
          controlBox gone, the wrapper sits empty + hidden = no visual
          effect.
        --}}
        <div id="live-edit-global-template-settings-component-wrapper" style="display:none;"></div>



            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        let activeSidebarItem = document.querySelector(
                            '.fi-main-sidebar .fi-sidebar-item.fi-active',
                        )

                        if (
                            !activeSidebarItem ||
                            activeSidebarItem.offsetParent === null
                        ) {
                            activeSidebarItem = document.querySelector(
                                '.fi-main-sidebar .fi-sidebar-group.fi-active',
                            )
                        }

                        if (
                            !activeSidebarItem ||
                            activeSidebarItem.offsetParent === null
                        ) {
                            return
                        }

                        const sidebarWrapper = document.querySelector(
                            '.fi-main-sidebar .fi-sidebar-nav',
                        )

                        if (!sidebarWrapper) {
                            return
                        }

                        sidebarWrapper.scrollTo(
                            0,
                            activeSidebarItem.offsetTop -
                            window.innerHeight / 2,
                        )
                    }, 10)
                })
            </script>
        @endif
    </div>
    @livewire('microweber-livewire-modal')
    @include('microweber-live-edit::partials.filament-mw-dialog-scripts')
</x-filament-panels::layout.base>
