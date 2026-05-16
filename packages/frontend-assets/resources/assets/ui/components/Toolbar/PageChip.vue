<!--
  task-2026-05-16-66ceca / AI-701 — current-page chip + picker.

  Replaces the generic `<ContentSearchNav>` "Search content"
  placeholder in the toolbar centre slot with v2's anchored
  page-name dropdown pattern. The chip surface answers two
  questions at once: "what page am I editing?" and "switch
  to another page".

  Architecture:

    - Renders a <button class="mw-page-chip"> showing the
      current page title (truncated to 24 chars + ellipsis)
      with a `⌄` chevron suffix.
    - Click toggles a popover anchored below the chip. Popover
      contains:
        - search input (filters the page list inline)
        - recent-pages list (uses the same content-search API
          as the prior ContentSearchNav — mw.autoComplete with
          get_content_admin endpoint)
        - `+ New page` shortcut linking to the admin
          content/create page form
    - Close via: outside click, ESC, picking an item.
    - Current page title pulled from
      `mw.top().app.canvas.getLiveEditData().content.title`
      (same data Toolbar.vue uses for backToAdminLink).
    - Listens for `liveEditCanvasLoaded` so the title updates
      on canvas navigation.

  Token-scoping note (per SOUL #108 spec-doc-nit): the chip +
  popover render INSIDE Toolbar.vue's overflow context — no
  Teleport. ESE :root tokens resolve through ancestors;
  literal fallbacks on every var() protect environments where
  the ESE stylesheet hasn't loaded.

  AI-687 (MwField) note: designer's spec asks the popover's
  search input to "Reuse the MwField token pattern (AI-687)".
  AI-687 hasn't shipped yet (ESE Phase 1 slice 1.4 pending);
  for now the input uses a basic styled <input> consuming the
  same ESE tokens (--ese-surface / --ese-border / --ese-text
  / --radius-sm / --space-sm / --space-md). Trivial refactor
  to MwField when 1.4 lands.
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
            <span class="mw-page-chip__label">{{ currentPageTitleTruncated || 'Untitled' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mw-page-chip__chevron" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>

        <div
            v-show="isOpen"
            class="mw-page-chip-popover"
            role="dialog"
            aria-label="Switch page"
            ref="popover"
            style="display: none;"
        >
            <div class="mw-page-chip-popover__search">
                <input
                    type="search"
                    class="mw-page-chip-popover__input"
                    placeholder="Search pages…"
                    aria-label="Search pages"
                    v-model="q"
                    @input="search"
                    ref="searchInput"
                />
            </div>

            <ul class="mw-page-chip-popover__list" v-if="results.length">
                <li v-for="item in results" :key="item.id" class="mw-page-chip-popover__item">
                    <a :href="item.edit_link" class="mw-page-chip-popover__link" @click="close()">
                        <span class="mw-page-chip-popover__item-title">{{ item.title }}</span>
                    </a>
                </li>
            </ul>

            <div v-else-if="q !== ''" class="mw-page-chip-popover__empty">
                No pages found.
            </div>

            <div class="mw-page-chip-popover__footer">
                <a :href="newPageHref" class="mw-page-chip-popover__new-page" @click="close()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>New page</span>
                </a>
            </div>
        </div>
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
            _searchTimer: null,
            _outsideHandler: null,
            _keyHandler: null,
        };
    },

    computed: {
        currentPageTitleTruncated() {
            var t = (this.currentPageTitleFull || '').trim();
            if (!t) return '';
            if (t.length <= 24) return t;
            return t.substr(0, 24) + '…';
        },
        newPageHref() {
            try {
                // mw.settings.admin_url provides the trailing-slash
                // admin prefix; fallback to /admin/ if unavailable.
                var adminUrl = (window.mw && window.mw.settings && window.mw.settings.admin_url) || '/admin/';
                // Standard Filament Create-Content URL with
                // content_type=page query param.
                return adminUrl + 'content/create?content_type=page';
            } catch (_) {
                return '/admin/content/create?content_type=page';
            }
        }
    },

    methods: {
        readCurrentPageTitle() {
            try {
                var top = window.mw && window.mw.top();
                if (top && top.app && top.app.canvas && typeof top.app.canvas.getLiveEditData === 'function') {
                    var data = top.app.canvas.getLiveEditData();
                    if (data && data.content && data.content.title) {
                        this.currentPageTitleFull = data.content.title;
                        return;
                    }
                }
            } catch (_) { /* no-op */ }
        },

        open() {
            this.isOpen = true;
            this.$nextTick(() => {
                if (this.$refs.searchInput) this.$refs.searchInput.focus();
            });
        },

        close() {
            this.isOpen = false;
            this.q = '';
            this.results = [];
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        search() {
            if (this._searchTimer) clearTimeout(this._searchTimer);
            var self = this;
            this._searchTimer = setTimeout(function () {
                if (self.q === '') {
                    self.results = [];
                    return;
                }
                self.fetchResults(self.q);
            }, 200);
        },

        fetchResults(keyword) {
            try {
                var apiBase = (window.mw && window.mw.settings && window.mw.settings.api_url) || '/api/';
                // Same endpoint ContentSearchNav.vue used —
                // get_content_admin with keyword filter.
                var url = apiBase
                    + 'get_content_admin?get_extra_data=1'
                    + '&order_by=updated_at desc'
                    + '&is_active=1&is_deleted=0'
                    + '&content_type=page'
                    + '&keyword=' + encodeURIComponent(keyword);
                var self = this;
                axios.get(url).then(function (response) {
                    if (Array.isArray(response.data)) {
                        self.results = response.data.slice(0, 8); // top 8
                    } else if (response.data && Array.isArray(response.data.data)) {
                        self.results = response.data.data.slice(0, 8);
                    } else {
                        self.results = [];
                    }
                }).catch(function () {
                    self.results = [];
                });
            } catch (_) { /* no-op */ }
        },

        onOutsideClick(event) {
            if (!this.isOpen) return;
            var root = this.$refs.root;
            if (!root) return;
            if (!root.contains(event.target)) {
                this.close();
            }
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
            // Re-read on canvas navigation so the chip stays in
            // sync with the current page after the user picks
            // a different one from the popover.
            window.mw.top().app.on('liveEditCanvasLoaded', () => {
                this.readCurrentPageTitle();
            });
        } catch (_) { /* no-op */ }

        this._outsideHandler = this.onOutsideClick.bind(this);
        this._keyHandler = this.onKeyDown.bind(this);
        document.addEventListener('click', this._outsideHandler, true);
        window.addEventListener('keydown', this._keyHandler);
    },

    beforeUnmount() {
        if (this._outsideHandler) document.removeEventListener('click', this._outsideHandler, true);
        if (this._keyHandler) window.removeEventListener('keydown', this._keyHandler);
        if (this._searchTimer) clearTimeout(this._searchTimer);
    },

    beforeDestroy() {
        if (this._outsideHandler) document.removeEventListener('click', this._outsideHandler, true);
        if (this._keyHandler) window.removeEventListener('keydown', this._keyHandler);
        if (this._searchTimer) clearTimeout(this._searchTimer);
    },
};
</script>
