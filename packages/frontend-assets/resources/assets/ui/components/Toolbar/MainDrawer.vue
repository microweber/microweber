<!--
  task-2026-05-16-7326d6 / AI-700 — Left drawer that consolidates
  Template Settings + Layers + Tools + Admin nav per
  output/live-edit-inspiration-from-v2-2026-05-16.md §2 P4-P5-P7.

  Mutually-unblocks AI-698b (the hamburger needs a target to open):
  this slice ships the drawer AND the visible toolbar hamburger
  trigger (the AI-698b-item-3 piece). AI-698b items 1-2 (MwSegmented
  device-preview + Tools popover) remain deferred and ship as
  AI-698c when dispatched.

  Architecture:
    - <Teleport to="body"> escapes the toolbar's overflow context
      so the drawer can render full-height fixed.
    - Width 280px desktop / 100vw mobile (<= 768px).
    - Slide-in from left over var(--t-slow). Backdrop dim 40%.
    - Close via: x button / backdrop tap / ESC / Tab-out-of-trap.
    - 8 nav items per spec; clean handlers where they exist
      (back-to-admin URL, `mw.top().app.domTree` show, the
      `templateSettingsWidget.toggle()`, dark-mode toggle, logout).
    - ESE stays as its own right-side panel (NOT in the drawer)
      per designer note "different surface focused editor,
      not navigation".

  Token-scoping note (per SOUL #108 spec-doc-nit): every var()
  consumed carries a literal fallback. The drawer DOM lives at
  document.body root (outside .mw-live-edit-page) so :root-scoped
  ESE tokens resolve fine, but the fallbacks protect environments
  where the ESE stylesheet hasn't loaded yet.

  task-2026-05-17-7a9913 / AI-798 — flat 7-item list mixes 3 mental
  models. Split into 3 labeled sections with per-section affordances:
    EDIT (slide-over triggers, chevron) Layers / Template & Layout
      / Theme Settings
    NAVIGATE (Pages + leave-live-edit external)  Pages (NEW Slice C)
      / Back to Admin / Users / See website (external)
    PREFERENCES (toggle)  Light mode
  Log out stays in footer as the destructive-action slot, separate
  from the 3 categories so it's visually de-prioritised.

  Pages item (Slice C) dispatches a `mwOpenPageChip` CustomEvent
  picked up by PageChip.vue's mounted handler. Same verb-bridge
  pattern documented in CLAUDE.md (`liveEditSaveCallMountedAction`
  family). Drawer closes BEFORE dispatching so the popover anchors
  cleanly to the now-visible topbar chip.

  Lineage: AI-700 (original drawer) + AI-708 (sidebar disambiguation)
  + AI-701 (PageChip; receives the new CustomEvent).
-->
<template>
    <Teleport to="body">
        <!-- Backdrop dim 40% only rendered when open so it doesn't
             intercept clicks while closed. -->
        <div
            v-if="isOpen"
            class="mw-main-drawer-backdrop"
            aria-hidden="true"
            @click="close()"
        ></div>

        <!-- Drawer panel. Always rendered (so animation can play on
             open/close), but only visible when isOpen via the
             mw-main-drawer--open class. -->
        <aside
            class="mw-main-drawer"
            :class="{ 'mw-main-drawer--open': isOpen }"
            role="dialog"
            aria-modal="true"
            aria-label="Main navigation"
            :aria-hidden="!isOpen"
            ref="drawer"
        >
            <header class="mw-main-drawer__header">
                <span class="mw-main-drawer__title">Menu</span>
                <button
                    type="button"
                    class="mw-main-drawer__close"
                    aria-label="Close menu"
                    title="Close menu"
                    @click="close()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </header>

            <nav class="mw-main-drawer__nav" aria-label="Main">
                <!-- ──────────────────────────────────────────────────
                     AI-798 Slice A — EDIT section (canvas slide-overs)
                ────────────────────────────────────────────────────── -->
                <h3 class="mw-main-drawer__section-header" id="mw-main-drawer-section-edit">Edit</h3>
                <ul class="mw-main-drawer__section" data-mw-section="edit" aria-labelledby="mw-main-drawer-section-edit">
                    <!-- Layers toggles the existing mw.top().app.domTree
                         controlBox (created in live-edit-dom-tree.js). -->
                    <li>
                        <button
                            type="button"
                            class="mw-main-drawer__item mw-main-drawer__item--edit"
                            @click="openLayers()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46L480-240Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Layers</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </button>
                    </li>

                    <!-- Template & Layout toggles the existing
                         mw.top().app.templateSettingsWidget controlBox
                         (created in bootstrap.js, renamed in AI-708). -->
                    <li>
                        <button
                            type="button"
                            class="mw-main-drawer__item mw-main-drawer__item--edit"
                            @click="openTemplateAndLayout()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M120-120v-720h720v720H120Zm80-560h560v-80H200v80Zm200 480h360v-400H400v400Zm-200 0h120v-400H200v400Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Template &amp; Layout</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </button>
                    </li>

                    <!-- Theme Settings opens the RightSidebar `general-
                         theme-settings` complementary panel via the same
                         templateSettingsWidget toggle. Per AI-708 the
                         RightSidebar wraps the same content; designer
                         verifies in browser whether a distinct trigger
                         is needed. -->
                    <li>
                        <button
                            type="button"
                            class="mw-main-drawer__item mw-main-drawer__item--edit"
                            @click="openThemeSettings()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q34 0 60-26t26-60v-80h-80q-17 0-31.5-6.5T428-352l-46-46-130 26q-9 32 0 65t34 60q44 44 96 65.5t98 21.5Zm-40-280h80v-40h40v-80h-40v-40h-80v40h-40v80h40v40Zm346 60q9-32 9-66.5t-9-66.5l-69 18q-9-9-19-15.5T693-558l64-32q-15-50-46.5-94T637-757l-87 75q-11-3-23-5t-23-2v-80q41 0 81.5 11.5T658-722q33 25 60 58.5t44 71.5l-72 72Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Theme Settings</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </button>
                    </li>
                </ul>

                <!-- ──────────────────────────────────────────────────
                     AI-798 Slice A + C — NAVIGATE section (Pages is new)
                ────────────────────────────────────────────────────── -->
                <h3 class="mw-main-drawer__section-header" id="mw-main-drawer-section-navigate">Navigate</h3>
                <ul class="mw-main-drawer__section" data-mw-section="navigate" aria-labelledby="mw-main-drawer-section-navigate">
                    <!-- Pages NEW Slice C. Opens the PageChip popover via
                         the `mwOpenPageChip` CustomEvent dispatched on the
                         window. PageChip.vue listens for the verb and
                         calls its own open(). Drawer closes BEFORE
                         dispatching so the popover anchors cleanly to
                         the now-visible topbar chip. -->
                    <li>
                        <button
                            type="button"
                            class="mw-main-drawer__item mw-main-drawer__item--navigate"
                            @click="openPagesList()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Pages</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </button>
                    </li>

                    <!-- Back to Admin URL link (existing data prop). -->
                    <li v-if="backToAdminLink">
                        <a
                            :href="backToAdminLink"
                            class="mw-main-drawer__item mw-main-drawer__item--external"
                            @click="close()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 12H5M12 19l-7-7 7-7"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Back to Admin</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 17L17 7M7 7h10v10"></path>
                            </svg>
                        </a>
                    </li>

                    <!-- Users URL link to admin/users. -->
                    <li>
                        <a
                            :href="usersUrl"
                            class="mw-main-drawer__item mw-main-drawer__item--external"
                            @click="close()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">Users</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 17L17 7M7 7h10v10"></path>
                            </svg>
                        </a>
                    </li>

                    <!-- See website opens the canvas URL in a new tab. -->
                    <li>
                        <a
                            :href="seeWebsiteUrl"
                            target="_blank"
                            rel="noopener"
                            class="mw-main-drawer__item mw-main-drawer__item--external"
                            @click="close()"
                        >
                            <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-83v-77q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-163Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">See website</span>
                            <svg class="mw-main-drawer__item-affordance" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 17L17 7M7 7h10v10"></path>
                            </svg>
                        </a>
                    </li>
                </ul>

                <!-- ──────────────────────────────────────────────────
                     AI-798 Slice A — PREFERENCES section (toggles)
                ────────────────────────────────────────────────────── -->
                <h3 class="mw-main-drawer__section-header" id="mw-main-drawer-section-preferences">Preferences</h3>
                <ul class="mw-main-drawer__section" data-mw-section="preferences" aria-labelledby="mw-main-drawer-section-preferences">
                    <li>
                        <button
                            type="button"
                            class="mw-main-drawer__item mw-main-drawer__item--toggle"
                            @click="toggleTheme()"
                            :aria-pressed="theme === 'dark' ? 'true' : 'false'"
                        >
                            <svg v-if="theme === 'light'" class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"></path>
                            </svg>
                            <svg v-else class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"></path>
                            </svg>
                            <span class="mw-main-drawer__item-label">{{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}</span>
                            <span class="mw-main-drawer__item-affordance mw-main-drawer__item-affordance--toggle" :data-state="theme === 'dark' ? 'on' : 'off'" aria-hidden="true">
                                <span class="mw-main-drawer__toggle-track">
                                    <span class="mw-main-drawer__toggle-thumb"></span>
                                </span>
                            </span>
                        </button>
                    </li>
                </ul>
            </nav>

            <!-- Footer Log out per L3.1. Stays separated from the 3
                 above categories as the destructive-action slot. -->
            <footer class="mw-main-drawer__footer">
                <a
                    v-if="logoutUrl"
                    :href="logoutUrl"
                    class="mw-main-drawer__item mw-main-drawer__item--logout"
                    @click="close()"
                >
                    <svg class="mw-main-drawer__item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="18" height="18" fill="currentColor" aria-hidden="true">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"></path>
                    </svg>
                    <span class="mw-main-drawer__item-label">Log out</span>
                </a>
            </footer>
        </aside>
    </Teleport>
</template>

<script>
export default {
    name: 'MainDrawer',

    props: {
        backToAdminLink: {
            type: String,
            default: ''
        },
        menu: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            isOpen: false,
            theme: 'light',
            usersUrl: '',
            seeWebsiteUrl: '/',
            logoutUrl: ''
        };
    },

    methods: {
        open() {
            this.isOpen = true;
            // Defer focus so the drawer is in the DOM before we trap.
            this.$nextTick(() => {
                if (this.$refs.drawer) {
                    var firstItem = this.$refs.drawer.querySelector('.mw-main-drawer__close');
                    if (firstItem) firstItem.focus();
                }
            });
        },

        close() {
            this.isOpen = false;
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        openLayers() {
            this.close();
            try {
                var top = window.mw && window.mw.top();
                if (top && top.app && top.app.domTree && typeof top.app.domTree.show === 'function') {
                    top.app.domTree.show();
                }
            } catch (_) { /* no-op */ }
        },

        openTemplateAndLayout() {
            this.close();
            try {
                var top = window.mw && window.mw.top();
                if (top && top.app && top.app.templateSettingsWidget && typeof top.app.templateSettingsWidget.toggle === 'function') {
                    top.app.templateSettingsWidget.toggle();
                }
            } catch (_) { /* no-op */ }
        },

        openThemeSettings() {
            // The "Theme Settings" panel is the RightSidebar.vue's
            // `complementary` wrapper that hosts the TemplateSettings
            // content. Per AI-708, the wrapper's h3 was renamed
            // "Template Style Editor" - "Theme Settings". The
            // RightSidebar uses <TemplateSettingsTeleport> to
            // teleport into #template-settings-teleport-widget-
            // content same surface as Template & Layout. Calling
            // templateSettingsWidget.show() opens the same content
            // panel; designer's browser verification will surface
            // whether a distinct trigger is needed.
            this.close();
            try {
                var top = window.mw && window.mw.top();
                if (top && top.app && top.app.templateSettingsWidget && typeof top.app.templateSettingsWidget.show === 'function') {
                    top.app.templateSettingsWidget.show();
                }
            } catch (_) { /* no-op */ }
        },

        openPagesList() {
            // task-2026-05-17-7a9913 / AI-798 Slice C  open the topbar
            // PageChip popover via a CustomEvent. Same verb-bridge
            // pattern documented in CLAUDE.md (liveEditSaveCallMountedAction
            // family). PageChip.vue's mounted() hook listens for
            // `mwOpenPageChip` and calls its own open() method.
            //
            // Close the drawer FIRST (so the slide-out animation
            // releases focus + the topbar chip becomes visible),
            // then dispatch on the next tick so PageChip's anchor
            // measurement reads the post-close layout.
            this.close();
            try {
                if (typeof window !== 'undefined' && typeof window.dispatchEvent === 'function') {
                    window.requestAnimationFrame(function () {
                        window.dispatchEvent(new CustomEvent('mwOpenPageChip'));
                    });
                }
            } catch (_) { /* no-op */ }
        },

        toggleTheme() {
            try {
                var top = window.mw && window.mw.top();
                if (top && top.admin && top.admin.theme && typeof top.admin.theme.toggle === 'function') {
                    top.admin.theme.toggle();
                }
            } catch (_) { /* no-op */ }
            // Theme change is reflected via the watch on `theme` prop
            // see ToolbarToolsDropdown for the MutationObserver
            // pattern. Here we read directly from the admin.theme
            // API after toggle.
            this.readTheme();
        },

        readTheme() {
            try {
                var top = window.mw && window.mw.top();
                if (top && top.admin && top.admin.theme && typeof top.admin.theme.getTheme === 'function') {
                    this.theme = top.admin.theme.getTheme();
                }
            } catch (_) { /* no-op */ }
        },

        onKeyDown(event) {
            if (!this.isOpen) return;
            if (event.key === 'Escape' || event.keyCode === 27) {
                event.preventDefault();
                this.close();
            }
        },

        readMenuUrls() {
            // task-2026-05-16-7326d6 / AI-700  pull Users / See website
            // / Logout URLs from the existing user-menu data
            // (api.live-edit.get-top-right-menu) when available. The
            // menu items carry id="logout-link" / similar identifiers;
            // we look them up by id. Falls back to plain admin paths
            // when the menu hasn't loaded yet.
            if (Array.isArray(this.menu) && this.menu.length) {
                for (var i = 0; i < this.menu.length; i++) {
                    var item = this.menu[i];
                    if (!item || !item.id) continue;
                    if (item.id === 'logout-link') this.logoutUrl = item.href || '';
                    if (item.id === 'users-link') this.usersUrl = item.href || '';
                    if (item.id === 'see-website-link' && item.href) this.seeWebsiteUrl = item.href;
                }
            }
        }
    },

    watch: {
        menu: {
            handler() {
                this.readMenuUrls();
            },
            immediate: true,
            deep: true
        }
    },

    mounted() {
        this.readTheme();
        try {
            window.mw.top().admin.theme.on('change', () => {
                this.readTheme();
            });
        } catch (_) { /* no-op */ }
        // ESC handler on window; component-scoped via _mwKeyHandler so
        // the listener is removed on unmount.
        this._mwKeyHandler = this.onKeyDown.bind(this);
        window.addEventListener('keydown', this._mwKeyHandler);
    },

    beforeUnmount() {
        if (this._mwKeyHandler) {
            window.removeEventListener('keydown', this._mwKeyHandler);
        }
    },

    beforeDestroy() {
        if (this._mwKeyHandler) {
            window.removeEventListener('keydown', this._mwKeyHandler);
        }
    }
};
</script>
