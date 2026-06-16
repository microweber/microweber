<!--
  task-2026-05-16-66ceca / AI-701 — current-page chip + picker.

  task-2026-05-18-fd85d0 — three improvements:
    1. Border made visible against dark toolbar background.
    2. Recent content loaded immediately on open (not only on search).
    3. Tabs: Pages / Posts / Products.

  task-2026-05-28-1a7899 / AI-1224 — PageChip popover ARIA tabs + touch targets:
    1. tablist gets aria-label="Page picker"; each tab carries id +
       aria-controls + roving tabindex (0 on active, -1 on others).
    2. ArrowLeft/ArrowRight/Home/End move focus and the active tab.
    3. The search + results + footer block becomes a role="tabpanel"
       with aria-labelledby pointing at the active tab.
    4. Current-page rows render as <button aria-current="page"> instead
       of the legacy empty-href anchor (so AT consumes a real disabled
       affordance rather than a no-op link).
    5. Tab/row/CTA min-height: 44px (WCAG 2.5.5 touch target).

  Architecture:

    - Renders a <button class="mw-page-chip"> showing the
      current page title (truncated to 24 chars + ellipsis)
      with a `⌄` chevron suffix.
    - Click toggles a popover anchored below the chip. Popover
      contains:
        - tab bar (Pages / Posts / Products)
        - search input (filters the active tab's list inline)
        - recent-content list loaded immediately on open
        - `+ New <type>` shortcut linking to the admin create form
    - Close via: outside click, ESC, picking an item.
    - Current page title pulled from
      `mw.top().app.canvas.getLiveEditData().content.title`.
    - Listens for `liveEditCanvasLoaded` so title updates on navigation.

  task-2026-05-30-pchip01 / PageChip mobile P1 fix (supersedes
  SOUL #108 "no Teleport" contract for mobile only):
    The live-edit-mobile.css rule
    `.toolbar-col-container:has(.mw-page-chip-wrapper) { display: none }`
    (room-budget hide at <=768px) collapsed the popover to 0x0
    when MainDrawer fired mwOpenPageChip on mobile — the chip
    wrapper itself was inside the hidden ancestor.

    Fix: wrap the popover in <Teleport to="body" :disabled="!isMobile">.
    On mobile the popover renders OUTSIDE the hidden ancestor as a
    full-viewport overlay; on desktop the original in-place mount
    is preserved (Teleport disabled).

    Token-scoping: ESE tokens consumed by the popover MUST resolve
    when the node is mounted under <body> (no .mw-live-edit-page
    ancestor). Every var(--ese-*) in mw-page-chip-popover-* rules
    must carry a literal fallback OR be :root-scoped. Same rule as
    AI-700 MainDrawer + AI-701 (in-place) + AI-697 anchored picker.

    computeAnchor() is desktop-only — gated on !isMobile because
    the chipRect-based horizontal-flip is irrelevant when the
    popover is a position:fixed full-viewport overlay.

  AI-687 (MwField) note: input uses ESE tokens pending AI-687 MwField
  ship (trivial refactor when 1.4 lands).
-->
<template>
    <div class="mw-page-chip-wrapper" ref="root">
        <button
            type="button"
            class="mw-page-chip"
            :class="{ 'mw-page-chip--open': isOpen }"
            :aria-expanded="isOpen ? 'true' : 'false'"
            aria-haspopup="dialog"
            :title="currentPageTitleFull || 'Switch page'"
            @click="toggle()"
        >
            <span class="mw-page-chip__label">{{ currentPageTitleTruncated || 'Homepage' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mw-page-chip__chevron" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>

        <!-- task-2026-05-30-pchip01 — Teleport to body on mobile so
             the popover escapes the hidden .toolbar-col-container ancestor.
             :disabled="!isMobile" preserves the original in-place mount
             on desktop (SOUL #108 contract retained for desktop). -->
        <Teleport to="body" :disabled="!isMobile">
        <div
            v-show="isOpen"
            class="mw-page-chip-popover"
            :class="{
                'mw-page-chip-popover--anchor-right': popoverAnchor === 'right',
                'mw-page-chip-popover--anchor-left': popoverAnchor === 'left',
                'mw-page-chip-popover--mobile': isMobile,
            }"
            role="dialog"
            aria-label="Switch page"
            ref="popover"
            style="display: none;"
        >
            <!-- Tab bar: Pages / Posts / Products
                 task-2026-05-28-1a7899 / AI-1224 — aria-label + IDs +
                 aria-controls + roving tabindex + arrow-key handler. -->
            <div class="mw-page-chip-popover__tabs" role="tablist" aria-label="Page picker">
                <button
                    v-for="(tab, idx) in tabs"
                    :key="tab.key"
                    type="button"
                    class="mw-page-chip-popover__tab"
                    :class="{ 'mw-page-chip-popover__tab--active': activeTab === tab.key }"
                    role="tab"
                    :id="'mw-page-chip-tab-' + tab.key"
                    :aria-controls="'mw-page-chip-tabpanel-' + tab.key"
                    :aria-selected="activeTab === tab.key ? 'true' : 'false'"
                    :tabindex="activeTab === tab.key ? 0 : -1"
                    ref="tabRefs"
                    @click="switchTab(tab.key)"
                    @keydown="onTabKeydown($event, idx)"
                >{{ tab.label }}</button>
            </div>

            <!-- Tabpanel: search + results + footer
                 task-2026-05-28-1a7899 / AI-1224 — role=tabpanel +
                 aria-labelledby pairs the panel with the active tab. -->
            <div
                role="tabpanel"
                :id="'mw-page-chip-tabpanel-' + activeTab"
                :aria-labelledby="'mw-page-chip-tab-' + activeTab"
                tabindex="0"
                class="mw-page-chip-popover__panel"
            >
                <!-- Search input -->
                <div class="mw-page-chip-popover__search">
                    <input
                        type="search"
                        class="mw-page-chip-popover__input"
                        :placeholder="'Search ' + activeTabLabel + '…'"
                        :aria-label="'Search ' + activeTabLabel"
                        v-model="q"
                        @input="onSearchInput"
                        ref="searchInput"
                    />
                </div>

                <!-- Results list
                     task-2026-05-28-1a7899 / AI-1224 — rows with no
                     edit_link render as <button aria-current="page">
                     so AT does not announce a no-op link. -->
                <ul class="mw-page-chip-popover__list" v-if="results.length">
                    <li v-for="item in results" :key="item.id" class="mw-page-chip-popover__item">
                        <button
                            v-if="!item.edit_link"
                            type="button"
                            class="mw-page-chip-popover__link mw-page-chip-popover__link--current"
                            aria-current="page"
                            @click="close()"
                        >
                            <span class="mw-page-chip-popover__item-title">{{ item.title || '(No title)' }}</span>
                        </button>
                        <a v-else :href="item.edit_link" class="mw-page-chip-popover__link" @click="close()">
                            <span class="mw-page-chip-popover__item-title">{{ item.title || '(No title)' }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Loading state -->
                <div v-else-if="isLoading" class="mw-page-chip-popover__empty">
                    Loading…
                </div>

                <!-- Empty / no results -->
                <div v-else-if="q !== '' || hasLoaded" class="mw-page-chip-popover__empty">
                    {{ q !== '' ? ('No ' + activeTabLabel + ' found.') : ('No ' + activeTabLabel + ' yet.') }}
                </div>

                <!-- Footer: New page/post/product shortcut -->
                <div class="mw-page-chip-popover__footer">
                    <a :href="newItemHref" class="mw-page-chip-popover__new-page" @click="close()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>New {{ activeTabSingular }}</span>
                    </a>
                </div>
            </div>
        </div>
        </Teleport>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PageChip',

    data() {
        return {
            isOpen: false,
            currentPageTitleFull: '',
            q: '',
            results: [],
            isLoading: false,
            hasLoaded: false,
            // task-2026-05-18-fd85d0 — active tab key
            activeTab: 'page',
            tabs: [
                { key: 'page',    label: 'Pages',    singular: 'page',    plural: 'pages' },
                { key: 'post',    label: 'Posts',    singular: 'post',    plural: 'posts' },
                { key: 'product', label: 'Products', singular: 'product', plural: 'products' },
            ],
            // task-2026-05-16-77fedf / AI-734 — anchor-flip state.
            popoverAnchor: 'center',
            // task-2026-05-30-pchip01 — drives Teleport :disabled and
            // mobile-overlay class. matchMedia mirrors live-edit-mobile.css
            // breakpoint (max-width: 768px).
            isMobile: false,
            _searchTimer: null,
            _outsideHandler: null,
            _keyHandler: null,
            _resizeHandler: null,
            _mqList: null,
            _mqHandler: null,
        };
    },

    computed: {
        currentPageTitleTruncated() {
            var t = (this.currentPageTitleFull || '').trim();
            if (!t) return '';
            if (t.length <= 24) return t;
            return t.substr(0, 24) + '…';
        },

        activeTabLabel() {
            var tab = this.tabs.find(function (t) { return t.key === this.activeTab; }, this);
            return tab ? tab.plural : 'pages';
        },

        activeTabSingular() {
            var tab = this.tabs.find(function (t) { return t.key === this.activeTab; }, this);
            return tab ? tab.singular : 'page';
        },

        // task-2026-05-18-fd85d0 — per-tab create URL using Filament routes.
        // These are admin routes; mw.settings.admin_url or /admin/ fallback.
        newItemHref() {
            try {
                var base = (window.mw && window.mw.settings && window.mw.settings.admin_url) || '/admin/';
                var routes = {
                    page:    base + 'pages/create',
                    post:    base + 'posts/create',
                    product: base + 'products/create',
                };
                return routes[this.activeTab] || (base + 'contents/create');
            } catch (_) {
                return '/admin/pages/create';
            }
        },

        // Keep newPageHref as a back-compat alias (pinned by contract tests)
        newPageHref() {
            return this.newItemHref;
        },
    },

    methods: {
        readCurrentPageTitle(retriesLeft) {
            // task-2026-06-06-pchiprace — getLiveEditData() is frequently still
            // empty when this runs on mount() and on the first liveEditCanvasLoaded
            // (the canvas content data is populated a beat later). Previously the
            // single early read found nothing and never re-ran, so the chip stuck
            // on the 'Homepage' fallback label for every non-home page. Retry a
            // bounded number of times until the title resolves. getLiveEditData()
            // always returns the CURRENT canvas content, so a late retry can never
            // resurrect a stale title from a previous page.
            if (typeof retriesLeft === 'undefined') retriesLeft = 15; // ~3s @ 200ms
            try {
                var top = window.mw && window.mw.top();
                if (top && top.app && top.app.canvas && typeof top.app.canvas.getLiveEditData === 'function') {
                    var data = top.app.canvas.getLiveEditData();
                    if (data && data.content && data.content.title) {
                        this.currentPageTitleFull = data.content.title;
                        if (data.content.id) this._lastContentId = data.content.id;
                        return;
                    }
                }
            } catch (_) { /* no-op */ }
            if (retriesLeft > 0) {
                var self = this;
                setTimeout(function () { self.readCurrentPageTitle(retriesLeft - 1); }, 200);
            }
        },

        open() {
            // task-2026-05-22-31aeb1 / AI-910 — blur the chip button before
            // opening so the blue :focus-visible ring does not persist after a
            // mouse click. The ring still appears on keyboard-initiated opens
            // (Tab → Enter triggers :focus-visible; blur() here removes the
            // programmatic focus set by the button's native click handler but
            // the browser's :focus-visible heuristic preserves it for keyboard
            // flows). Focus is immediately transferred to the search input below.
            if (this.$el) this.$el.blur();
            this.isOpen = true;
            this.$nextTick(function () {
                // task-2026-05-16-77fedf / AI-734
                this.computeAnchor();
                if (this.$refs.searchInput) this.$refs.searchInput.focus();
            }.bind(this));
            // task-2026-05-18-fd85d0 — load recent content on open
            this.loadRecent();
        },

        // task-2026-05-16-77fedf / AI-734
        // task-2026-05-30-pchip01 — desktop-only. On mobile the popover
        // is a position:fixed full-viewport overlay so chipRect-based
        // horizontal anchor-flip is irrelevant.
        computeAnchor() {
            if (this.isMobile) {
                this.popoverAnchor = 'center';
                return;
            }
            try {
                var root = this.$refs.root;
                if (!root) return;
                var POPOVER_WIDTH = 320;
                var EDGE_MARGIN = 8;
                var chipRect = root.getBoundingClientRect();
                var chipCenterX = chipRect.left + (chipRect.width / 2);
                var centeredPopoverRight = chipCenterX + (POPOVER_WIDTH / 2);
                var centeredPopoverLeft = chipCenterX - (POPOVER_WIDTH / 2);
                // task-2026-06-04-le734 / AI-734 — two-sided anchor flip.
                // Right overflow → hug the chip's right edge (existing).
                // Left overflow (chip near the viewport's left edge) → hug
                // the chip's left edge so the popover's left stays >= 8px.
                // Closes the last AI-734 acceptance gap (left edge >= 8px);
                // the right-flip + mobile teleport overlay already covered
                // the right-edge + mobile cases.
                if (centeredPopoverRight > window.innerWidth - EDGE_MARGIN) {
                    this.popoverAnchor = 'right';
                } else if (centeredPopoverLeft < EDGE_MARGIN) {
                    this.popoverAnchor = 'left';
                } else {
                    this.popoverAnchor = 'center';
                }
            } catch (_) { /* graceful fallback */ }
        },

        onResize() {
            if (this.isOpen) this.computeAnchor();
        },

        close() {
            this.isOpen = false;
            this.q = '';
            this.results = [];
            this.hasLoaded = false;
            this.isLoading = false;
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        // task-2026-05-18-fd85d0 — switch tab, clear search, reload
        switchTab(key) {
            if (this.activeTab === key) return;
            this.activeTab = key;
            this.q = '';
            this.results = [];
            this.hasLoaded = false;
            this.loadRecent();
            this.$nextTick(function () {
                if (this.$refs.searchInput) this.$refs.searchInput.focus();
            }.bind(this));
        },

        // task-2026-05-28-1a7899 / AI-1224 — arrow-key navigation across
        // the tablist (WAI-ARIA Tabs Pattern). ArrowLeft/ArrowRight wrap;
        // Home/End jump to the first/last tab. Focus moves WITH the
        // active-tab change so the roving tabindex carries the user.
        onTabKeydown(event, idx) {
            var key = event.key;
            if (key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Home' && key !== 'End') {
                return;
            }
            event.preventDefault();
            var last = this.tabs.length - 1;
            var nextIdx = idx;
            if (key === 'ArrowLeft') nextIdx = idx === 0 ? last : idx - 1;
            else if (key === 'ArrowRight') nextIdx = idx === last ? 0 : idx + 1;
            else if (key === 'Home') nextIdx = 0;
            else if (key === 'End') nextIdx = last;
            var nextKey = this.tabs[nextIdx].key;
            if (nextKey === this.activeTab) {
                // tablist of length 1, or no-op — still focus the target.
                var sameRef = this.$refs.tabRefs && this.$refs.tabRefs[nextIdx];
                if (sameRef) sameRef.focus();
                return;
            }
            this.activeTab = nextKey;
            this.q = '';
            this.results = [];
            this.hasLoaded = false;
            this.loadRecent();
            this.$nextTick(function () {
                var nextEl = this.$refs.tabRefs && this.$refs.tabRefs[nextIdx];
                if (nextEl) nextEl.focus();
            }.bind(this));
        },

        // task-2026-05-18-fd85d0 — load most recent 8 items for active tab
        loadRecent() {
            this.isLoading = true;
            this.hasLoaded = false;
            var self = this;
            this.fetchResults('', function (items) {
                self.results = items;
                self.isLoading = false;
                self.hasLoaded = true;
            });
        },

        onSearchInput() {
            if (this._searchTimer) clearTimeout(this._searchTimer);
            var self = this;
            if (this.q === '') {
                this.loadRecent();
                return;
            }
            this._searchTimer = setTimeout(function () {
                self.isLoading = true;
                self.fetchResults(self.q, function (items) {
                    self.results = items;
                    self.isLoading = false;
                    self.hasLoaded = true;
                });
            }, 200);
        },

        // task-2026-06-15-pchipedit — build the live-edit "switch to this page"
        // URL for each result. get_content_admin doesn't return edit_link, and
        // the model's edit_link accessor points at the Filament form (wrong for a
        // canvas switcher). The current canvas page keeps an empty edit_link so it
        // renders as the current indicator; everything else becomes a real,
        // navigable <a href="/admin/live-edit?url=…">.
        decorateResultsWithEditLink(items) {
            if (!Array.isArray(items)) return [];
            var liveEditPath = window.location.pathname || '/admin/live-edit';

            var normPath = function (u) {
                if (!u) return '';
                var s = String(u);
                // tolerate single/double URL-encoding of the ?url= param
                for (var i = 0; i < 2; i++) {
                    if (/%[0-9a-fA-F]{2}/.test(s)) {
                        try { s = decodeURIComponent(s); } catch (e) { break; }
                    }
                }
                try { s = new URL(s, window.location.origin).pathname; } catch (e) { /* keep s */ }
                return s.replace(/\/+$/, '');
            };

            var currentPath = '';
            try {
                var cur = new URLSearchParams(window.location.search).get('url') || '';
                currentPath = normPath(cur);
            } catch (e) { /* no-op */ }

            return items.map(function (it) {
                var pub = (it && (it.url || it.original_link)) || '';
                if (pub && currentPath && normPath(pub) === currentPath) {
                    it.edit_link = ''; // the page already open in the canvas
                } else if (pub) {
                    it.edit_link = liveEditPath + '?url=' + encodeURIComponent(pub);
                } else {
                    it.edit_link = '';
                }
                return it;
            });
        },

        fetchResults(keyword, callback) {
            var self = this;
            try {
                var apiBase = (window.mw && window.mw.settings && window.mw.settings.api_url) || '/api/';
                // task-2026-06-06-pchipdraft — do NOT filter is_active here.
                // Content defaults to draft (is_active=0) since AI-777, so a
                // freshly-created page/post was invisible in this switcher — the
                // editor could not navigate back to the very page they just made.
                // The page chip is an editor tool, so it must list drafts too;
                // only deleted content is excluded.
                var url = apiBase
                    + 'get_content_admin?get_extra_data=1'
                    + '&order_by=updated_at desc'
                    + '&is_deleted=0'
                    + '&content_type=' + encodeURIComponent(this.activeTab)
                    + (keyword ? '&keyword=' + encodeURIComponent(keyword) : '')
                    + '&limit=8';
                axios.get(url).then(function (response) {
                    var items = [];
                    if (Array.isArray(response.data)) {
                        items = response.data.slice(0, 8);
                    } else if (response.data && Array.isArray(response.data.data)) {
                        items = response.data.data.slice(0, 8);
                    }
                    // task-2026-06-15-pchipedit — get_content_admin returns raw
                    // content rows WITHOUT an edit_link, so every row used to render
                    // as the no-op "current" button (v-if="!item.edit_link") and
                    // clicking a page did nothing. Build the live-edit switch URL
                    // here from each item's public url; blank it only for the page
                    // already open in the canvas (so it stays the current
                    // indicator). Erring toward a populated edit_link keeps rows
                    // navigable even if the current-page match is imperfect.
                    items = self.decorateResultsWithEditLink(items);
                    callback(items);
                }).catch(function () {
                    callback([]);
                });
            } catch (_) {
                callback([]);
            }
        },

        onOutsideClick(event) {
            if (!this.isOpen) return;
            var root = this.$refs.root;
            if (!root) return;
            if (root.contains(event.target)) return;
            // task-2026-05-30-pchip01 — when the popover is teleported
            // to <body> on mobile, it is no longer inside root.contains().
            // Treat clicks inside the popover ref as inside the chip.
            var popover = this.$refs.popover;
            if (popover && popover.contains(event.target)) return;
            this.close();
        },

        onKeyDown(event) {
            if (!this.isOpen) return;
            if (event.key === 'Escape' || event.keyCode === 27) {
                event.preventDefault();
                this.close();
            }
        },
    },

    mounted() {
        this.readCurrentPageTitle();
        try {
            window.mw.top().app.on('liveEditCanvasLoaded', function () {
                this.readCurrentPageTitle();
            }.bind(this));
        } catch (_) { /* no-op */ }

        this._outsideHandler = this.onOutsideClick.bind(this);
        this._keyHandler = this.onKeyDown.bind(this);
        this._resizeHandler = this.onResize.bind(this);
        document.addEventListener('click', this._outsideHandler, true);
        window.addEventListener('keydown', this._keyHandler);
        window.addEventListener('resize', this._resizeHandler);

        // task-2026-05-30-pchip01 — track viewport via matchMedia so the
        // Teleport flips synchronously when the user rotates or resizes
        // across the 768px breakpoint. Mirrors live-edit-mobile.css.
        try {
            if (window.matchMedia) {
                this._mqList = window.matchMedia('(max-width: 768px)');
                this.isMobile = !!this._mqList.matches;
                this._mqHandler = (e) => { this.isMobile = !!e.matches; };
                if (this._mqList.addEventListener) {
                    this._mqList.addEventListener('change', this._mqHandler);
                } else if (this._mqList.addListener) {
                    this._mqList.addListener(this._mqHandler);
                }
            }
        } catch (_) { /* no-op */ }

        // task-2026-05-17-7a9913 / AI-798 Slice C
        this._openVerbHandler = () => { this.open(); };
        window.addEventListener('mwOpenPageChip', this._openVerbHandler);

        // task-2026-06-06-pchipnav — liveEditCanvasLoaded does NOT fire when the
        // canvas navigates to a different page (e.g. after creating content from
        // the +ADD modal, or following an in-canvas link), so the event listener
        // above misses those navigations and the chip keeps showing the previous
        // page's title. Poll the canvas content id and re-read the title whenever
        // it changes — robust against any navigation mechanism. Reading one JS
        // property every 600ms is negligible.
        this._pageWatchTimer = setInterval(function () {
            try {
                var top = window.mw && window.mw.top();
                if (!top || !top.app || !top.app.canvas || typeof top.app.canvas.getLiveEditData !== 'function') return;
                var data = top.app.canvas.getLiveEditData();
                var id = data && data.content && data.content.id;
                if (id && id !== this._lastContentId) {
                    this._lastContentId = id;
                    this.readCurrentPageTitle();
                }
            } catch (_) { /* no-op */ }
        }.bind(this), 600);
    },

    beforeUnmount() {
        if (this._outsideHandler) document.removeEventListener('click', this._outsideHandler, true);
        if (this._keyHandler) window.removeEventListener('keydown', this._keyHandler);
        if (this._resizeHandler) window.removeEventListener('resize', this._resizeHandler);
        if (this._openVerbHandler) window.removeEventListener('mwOpenPageChip', this._openVerbHandler);
        if (this._mqList && this._mqHandler) {
            if (this._mqList.removeEventListener) {
                this._mqList.removeEventListener('change', this._mqHandler);
            } else if (this._mqList.removeListener) {
                this._mqList.removeListener(this._mqHandler);
            }
        }
        if (this._searchTimer) clearTimeout(this._searchTimer);
        if (this._pageWatchTimer) clearInterval(this._pageWatchTimer);
    },

    beforeDestroy() {
        if (this._outsideHandler) document.removeEventListener('click', this._outsideHandler, true);
        if (this._keyHandler) window.removeEventListener('keydown', this._keyHandler);
        if (this._resizeHandler) window.removeEventListener('resize', this._resizeHandler);
        if (this._openVerbHandler) window.removeEventListener('mwOpenPageChip', this._openVerbHandler);
        if (this._mqList && this._mqHandler) {
            if (this._mqList.removeEventListener) {
                this._mqList.removeEventListener('change', this._mqHandler);
            } else if (this._mqList.removeListener) {
                this._mqList.removeListener(this._mqHandler);
            }
        }
        if (this._searchTimer) clearTimeout(this._searchTimer);
        if (this._pageWatchTimer) clearInterval(this._pageWatchTimer);
    },
};
</script>
