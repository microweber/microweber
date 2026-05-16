<template>


    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" class="mw-le-overlay active" v-on:click="showModal = false"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >

        <div v-if="showModal"
             role="dialog"
             aria-modal="true"
             aria-labelledby="mw-le-layouts-dialog-title"
             class="mw-le-dialog-block mw-le-layouts-dialog w-100 active"
             style="inset:20px; transform:none; animation-duration: .3s; z-index: 1000;"
        >

            <!-- audit-test 2026-05-07 ESE+picker audit findings #2/#6:
                 - aria-modal="true" + aria-labelledby on the dialog so
                   assistive tech traps focus and announces the title.
                 - Visually-hidden h2 carries the announce-only title;
                   aria-hidden="true" on the &times; glyph so SR reads
                   the close button's aria-label, not "multiplication". -->
            <h2 id="mw-le-layouts-dialog-title" class="visually-hidden">{{ $lang('Insert layout') }}</h2>

            <!-- Close Button -->
            <button
                :aria-label="$lang('Close picker')"
                class="mw-le-dialog-close-btn"
                style="position:absolute;top:16px;right:16px;z-index:10;background:none;border:none;font-size:2rem;line-height:1;cursor:pointer;"
                type="button"
                @click="showModal = false"
            >
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="modules-list modules-list-defaultModules">
                <div class="mw-le-layouts-dialog-row">

                    <div v-if=" layoutsList?.categories?.length" class="mw-le-layouts-dialog-col">


                        <!-- audit-test 2026-05-07 picker audit finding #5:
                             categories function as tabs (click filters
                             which layouts show). Wire role="tablist" /
                             role="tab" / aria-selected so screen readers
                             announce "tabs, Content selected, 12 items"
                             instead of "list of 12 items". -->
                        <ul class="modules-list-categories py-5"
                            role="tablist"
                            :aria-label="$lang('Layout categories')">

                            <li role="tab"
                                :aria-selected="'' === filterCategory"
                                :tabindex="'' === filterCategory ? 0 : -1"
                                :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']"
                                v-on:click="filterCategorySubmit('')"
                                v-on:keydown.enter="filterCategorySubmit('')"
                                v-on:keydown.space.prevent="filterCategorySubmit('')">
                                All categories
                            </li>

                            <li v-for="categoryName in layoutsList.categories"
                                role="tab"
                                :aria-selected="categoryName === filterCategory"
                                :tabindex="categoryName === filterCategory ? 0 : -1"
                                :data-category="[categoryName ? categoryName.toLowerCase(): '']"
                                v-on:click="filterCategorySubmit(categoryName)"
                                v-on:keydown.enter="filterCategorySubmit(categoryName)"
                                v-on:keydown.space.prevent="filterCategorySubmit(categoryName)">

                                <a :class="[categoryName == filterCategory ? 'active animate__animated animate__pulse': '']"
                                   class="mw-admin-action-links">
                                    {{ categoryName }}
                                </a>

                            </li>
                        </ul>
                    </div>

                    <div
                        :class="[layoutsList?.categories?.length ? 'mw-le-layouts-dialog-col' : 'mw-le-layouts-dialog-col-full col-xl-10 mx-auto px-xl-0 px-5']">

                        <!--                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                                                Looking for {{filterKeyword}}
                                                <span v-if="filterCategory">
                                                    in {{filterCategory}}
                                                </span>
                                            </div>-->

                        <!-- AI-715 / task-2026-05-16-847083 — section-
                             continuity echo per spec: right pane shows
                             `{Category} · {N layouts}` so the active
                             category from the left rail is mirrored in
                             the content header. Drunk-Designer §3
                             "section continuity" fix. -->
                        <div v-if="layoutsList?.categories?.length"
                             class="mw-le-layouts-result-header"
                             aria-live="polite">
                            <span class="mw-le-layouts-result-header__name">
                                {{ filterCategory || $lang('All categories') }}
                            </span>
                            <span class="mw-le-layouts-result-header__separator" aria-hidden="true">·</span>
                            <span class="mw-le-layouts-result-header__count">
                                {{ layoutsListFiltered?.length || 0 }} {{ (layoutsListFiltered?.length === 1 ? $lang('layout') : $lang('layouts')) }}
                            </span>
                        </div>

                        <div v-show="layoutsList?.categories?.length">
                            <div class="modules-list-search-block input-icon">
                                <!-- audit-test 2026-05-07 picker audit finding #3:
                                     type="search" enables native clear-button (×).
                                     inputmode="search" + enterkeyhint="search" gives
                                     mobile keyboards the "search" submit key.
                                     aria-label gives screen readers a stable label
                                     that survives placeholder localisation. -->
                                <input v-model="filterKeyword" class="modules-list-search-field form-control rounded-0"
                                       type="search"
                                       inputmode="search"
                                       enterkeyhint="search"
                                       :aria-label="$lang('Search layouts')"
                                       v-bind:placeholder="$lang('Type to Search') + '...'">
                                <span class="input-icon-addon list-layouts-search-bar-icon ms-3">

                                    <svg class="icon" fill="none" height="20" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                         viewBox="0 0 24 24"
                                         width="20" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z"
                                                                                             fill="none"
                                                                                             stroke="none"></path><path
                                        d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path
                                        d="M21 21l-6 -6"></path></svg>
                                </span>
                            </div>

                            <!-- AI-714 / task-2026-05-16-8f20b6 — mobile-only
                                 category filter trigger. The desktop rail
                                 (`.mw-le-layouts-dialog-col:first-child`) is
                                 `display: none` on mobile (index.css line ~590);
                                 prior to AI-714, mobile users had zero way to
                                 filter by category. The trigger button shows
                                 the current filter ("All categories" /
                                 selected name) and opens the bottom-sheet on
                                 tap. Visible only at ≤767px via CSS. -->
                            <button v-if="layoutsList?.categories?.length"
                                    type="button"
                                    class="mw-le-layouts-mobile-filter-trigger"
                                    v-on:click="toggleMobileFilter"
                                    :aria-expanded="mobileFilterOpen ? 'true' : 'false'"
                                    aria-controls="mw-le-layouts-mobile-filter-sheet"
                                    :aria-label="$lang('Filter layouts by category')">
                                <svg class="mw-le-layouts-mobile-filter-trigger-icon"
                                     xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="currentColor" aria-hidden="true">
                                    <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z"/>
                                </svg>
                                <span class="mw-le-layouts-mobile-filter-trigger-label">
                                    {{ filterCategory || $lang('All categories') }}
                                </span>
                                <svg class="mw-le-layouts-mobile-filter-trigger-chevron"
                                     xmlns="http://www.w3.org/2000/svg" height="14px" viewBox="0 -960 960 960" width="14px" fill="currentColor" aria-hidden="true">
                                    <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- AI-714 / task-2026-05-16-8f20b6 — mobile-only
                             category filter bottom-sheet. Renders only when
                             `mobileFilterOpen` is true. Hosts a duplicate of
                             the desktop category list (`<ul class="modules-
                             list-categories">`) so the active-state binding
                             stays in sync across surfaces. Tapping a
                             category selects AND closes the sheet
                             (`filterCategoryFromMobile`). Backdrop click
                             dismisses without selection. Visible only at
                             ≤767px via CSS. -->
                        <div v-if="mobileFilterOpen"
                             id="mw-le-layouts-mobile-filter-sheet"
                             class="mw-le-layouts-mobile-filter-sheet"
                             role="dialog"
                             aria-modal="true"
                             :aria-label="$lang('Filter layouts by category')"
                             v-on:keydown.esc.prevent="closeMobileFilter">
                            <div class="mw-le-layouts-mobile-filter-sheet__backdrop"
                                 v-on:click="closeMobileFilter"
                                 aria-hidden="true"></div>
                            <div class="mw-le-layouts-mobile-filter-sheet__panel">
                                <header class="mw-le-layouts-mobile-filter-sheet__header">
                                    <h3 class="mw-le-layouts-mobile-filter-sheet__title">
                                        {{ $lang('Filter by category') }}
                                    </h3>
                                    <button type="button"
                                            class="mw-le-layouts-mobile-filter-sheet__close"
                                            v-on:click="closeMobileFilter"
                                            :aria-label="$lang('Close filter')">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                                    </button>
                                </header>
                                <ul class="modules-list-categories mw-le-layouts-mobile-filter-sheet__list"
                                    role="tablist"
                                    :aria-label="$lang('Layout categories')">
                                    <li role="tab"
                                        :aria-selected="'' === filterCategory"
                                        :class="['' == filterCategory ? 'active' : '']"
                                        v-on:click="filterCategoryFromMobile('')"
                                        v-on:keydown.enter="filterCategoryFromMobile('')"
                                        v-on:keydown.space.prevent="filterCategoryFromMobile('')"
                                        tabindex="0">
                                        {{ $lang('All categories') }}
                                    </li>
                                    <li v-for="categoryName in layoutsList.categories"
                                        role="tab"
                                        :key="'mobile-' + categoryName"
                                        :aria-selected="categoryName === filterCategory"
                                        :class="[categoryName == filterCategory ? 'active' : '']"
                                        v-on:click="filterCategoryFromMobile(categoryName)"
                                        v-on:keydown.enter="filterCategoryFromMobile(categoryName)"
                                        v-on:keydown.space.prevent="filterCategoryFromMobile(categoryName)"
                                        tabindex="0">
                                        {{ categoryName }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!--                    <div class="me-5 pe-3 my-3 py-0 col-xl-2 col-md-3 col-12 ms-auto text-end justify-content-end">-->
                        <!--                        <div class="btn-group d-flex justify-content-end pe-4 layout-list-buttons gap-2">-->
                        <!--                      <button-->
                        <!--                                type="button"-->
                        <!--                                v-on:click="switchLayoutsListTypePreview('masonry')"-->
                        <!--                                :class="['btn', layoutsListTypePreview == 'masonry'? 'btn-dark': 'btn-outline-dark']"-->
                        <!--                            >-->
                        <!--                                <MasonryIcon style="max-width:23px;max-height:23px;" />-->
                        <!--                            </button>-->

                        <!--&lt;!&ndash;                            <button&ndash;&gt;-->
                        <!--&lt;!&ndash;                                type="button"&ndash;&gt;-->
                        <!--&lt;!&ndash;                                v-on:click="switchLayoutsListTypePreview('list')"&ndash;&gt;-->
                        <!--&lt;!&ndash;                                :class="['btn', layoutsListTypePreview == 'list'? 'btn-dark': 'btn-outline-dark']"&ndash;&gt;-->
                        <!--&lt;!&ndash;                            >&ndash;&gt;-->
                        <!--&lt;!&ndash;                                <GridIcon style="max-width:23px;max-height:23px;" />&ndash;&gt;-->
                        <!--&lt;!&ndash;                            </button>&ndash;&gt;-->
                        <!--                            <button-->
                        <!--                                type="button"-->
                        <!--                                v-on:click="switchLayoutsListTypePreview('full')"-->
                        <!--                                :class="['btn', layoutsListTypePreview == 'full'? 'btn-dark': 'btn-outline-dark']"-->
                        <!--                            >-->
                        <!--                                <ListIcon style="max-width:23px;max-height:23px;" />-->
                        <!--                            </button>-->

                        <!--                        </div>-->
                        <!--                    </div>-->

                        <div v-if="layoutsListLoaded && layoutsListTypePreview == 'masonry'"
                             class="modules-list-block-masonry">
                            <MasonryWall
                                :column-width="400"
                                :gap="12"
                                :items="layoutsListFiltered"
                                :padding="12"
                                :ssr-columns="1">
                                <template #default="{ item, index }">
                                    <div
                                        :class="['modules-list-block-item-masonry', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']"
                                        v-on:click="insertLayout(item)">

                                        <div class="layout-image-container">
                                            <img v-if="item.screenshot" :alt="item.title" :src="item.screenshot"/>
                                            <!-- AI-68 / TICKET-OO (cycle-70 2026-05-08):
                                                 don't mount the <iframe> until the wrapper
                                                 scrolls into view. Native loading="lazy"
                                                 still triggers a network request when the
                                                 element is in flow + above-the-fold viewport
                                                 height, so 17 cards mounted simultaneously
                                                 was hammering the server even with the
                                                 attribute. v-if + IntersectionObserver
                                                 guarantees the iframe DOM node only ever
                                                 exists for cards the user is actually
                                                 looking at. -->
                                            <div v-else-if="item.preview_url"
                                                 class="layout-iframe-preview"
                                                 :data-mw-iframe-key="iframeKeyFor(item, index)"
                                                 ref="iframeWrappersMasonry">
                                                <iframe
                                                    v-if="iframeIsInView(iframeKeyFor(item, index))"
                                                    :src="item.preview_url"
                                                    :title="item.title"
                                                    class="layout-preview-iframe"
                                                    loading="lazy"
                                                    scrolling="no"
                                                    frameborder="0"
                                                    sandbox="allow-same-origin allow-scripts"
                                                ></iframe>
                                                <div v-else class="layout-iframe-placeholder">
                                                    <span>{{ item.title }}</span>
                                                </div>
                                            </div>
                                            <div v-else class="layout-no-preview">
                                                <span>{{ item.title }}</span>
                                            </div>

                                            <!-- Module icons overlay for masonry view -->
                                            <div
                                                v-if="item.found_modules && item.found_modules.length > 0 && hasModulesToShow(item)"
                                                class="layout-modules-overlay d-none">
                                                <div v-show="" class="modules-icons-container">
                                                <span
                                                    v-for="moduleName in item.found_modules"
                                                    v-show="!isSkipModule(moduleName)"
                                                    :key="moduleName"
                                                    :title="getModuleDisplayName(moduleName)"
                                                    class="module-icon-wrapper"
                                                    @mouseenter="showModuleTooltip($event, moduleName)"
                                                    @mouseleave="hideModuleTooltip"
                                                >
                                                    <div
                                                        class="module-icon"
                                                        v-html="getModuleIcon(moduleName)"
                                                    ></div>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modules-list-block-item-masonry-title">{{ item.title }}</div>

                                    </div>
                                </template>
                            </MasonryWall>
                        </div>


                        <LazyList
                            v-if="layoutsListLoaded && (layoutsListTypePreview == 'list' || layoutsListTypePreview == 'full') && layoutsListFiltered?.length > 0"
                            :containerClasses="'modules-list-block modules-list-block-' + layoutsListTypePreview"
                            :data="layoutsListFiltered"
                            :itemsPerRender="18"
                            defaultLoadingColor="#222"
                        >
                            <template
                                v-slot="{item}">
                                <div
                                    :class="['modules-list-block-style-' + layoutsListTypePreview, 'modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']"
                                    v-on:click="insertLayout(item)">

                                    <div
                                        v-if="item.screenshot"
                                        :style="'background-image: url('+item.screenshot+');background-size: cover;background-position: center center;'"
                                        class="modules-list-block-item-picture">

                                        <!-- Module icons overlay for list view -->
                                        <div
                                            v-if="item.found_modules && item.found_modules.length > 0 && hasModulesToShow(item)"
                                            class="layout-modules-overlay d-none">
                                            <div class="modules-icons-container">
                                            <span
                                                v-for="moduleName in item.found_modules"
                                                v-show="!isSkipModule(moduleName)"
                                                :key="moduleName"
                                                :title="getModuleDisplayName(moduleName)"
                                                class="module-icon-wrapper"
                                                @mouseenter="showModuleTooltip($event, moduleName)"
                                                @mouseleave="hideModuleTooltip"
                                            >
                                                <div
                                                    class="module-icon"
                                                    v-html="getModuleIcon(moduleName)"
                                                ></div>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- AI-68 / TICKET-OO (cycle-70 2026-05-08):
                                         lazy-mount the iframe via
                                         IntersectionObserver — see the masonry
                                         branch above for the full rationale. -->
                                    <div
                                        v-else-if="item.preview_url"
                                        class="modules-list-block-item-picture layout-iframe-preview"
                                        :data-mw-iframe-key="iframeKeyFor(item, index)"
                                        ref="iframeWrappersList">
                                        <iframe
                                            v-if="iframeIsInView(iframeKeyFor(item, index))"
                                            :src="item.preview_url"
                                            :title="item.title"
                                            class="layout-preview-iframe"
                                            loading="lazy"
                                            scrolling="no"
                                            frameborder="0"
                                            sandbox="allow-same-origin allow-scripts"
                                        ></iframe>
                                        <div v-else class="layout-iframe-placeholder">
                                            <span>{{ item.title }}</span>
                                        </div>
                                    </div>
                                    <div
                                        v-else
                                        class="modules-list-block-item-picture layout-no-preview">
                                        <span>{{ item.title }}</span>
                                    </div>

                                    <div class="modules-list-block-item-title">{{ item.title }}</div>

                                    <div class="modules-list-block-item-description">
                                        {{ item.description }}
                                    </div>
                                </div>
                            </template>
                        </LazyList>

                        <div v-if="layoutsListFiltered?.length == 0" class="modules-list-block">
                            <div class="modules-list-block-no-results">

                                <div v-if="filterCategory?.length > 0 && filterKeyword?.length >0">
                                    Nothing found in <b>{{ filterCategory }}</b> with keyword
                                    <i>"{{ filterKeyword }}"</i>.
                                    <br/>
                                    <br/>
                                    <button class="btn btn-outline-dark btn-sm" type="button"
                                            v-on:click="searchInAll()">
                                        Search in all
                                    </button>
                                </div>
                                <div v-else>
                                    Nothing found.
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </Transition>

</template>

<style>
.wrap-iframe {
    width: 100%;
    height: 100%;
    padding: 0;
    overflow: hidden;
    background: red;
}

.iframe-inside {
    width: 1200px;
    height: 900px;
    border: 0;
    transform: scale(.37);
    transform-origin: 0 0;
}

/* Live iframe preview for layouts without screenshots */
.layout-iframe-preview {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 4px;
    aspect-ratio: 4 / 3;
}

.modules-list-block-item-picture.layout-iframe-preview {
    height: auto;
}

.layout-preview-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 400%; /* 100% / 0.25 scale factor */
    height: 400%;
    border: 0;
    transform: scale(0.25);
    transform-origin: 0 0;
    pointer-events: none;
}

.modules-list-block-item-masonry .layout-iframe-preview {
    aspect-ratio: 4 / 3;
}

.modules-list-block-item-masonry .layout-preview-iframe {
    width: 400%;
    height: 400%;
    transform: scale(0.25);
}

.modules-list-block-style-list .layout-iframe-preview {
    aspect-ratio: 7 / 5;
}

.modules-list-block-style-list .layout-preview-iframe {
    width: 500%; /* 100% / 0.2 scale factor */
    height: 500%;
    transform: scale(0.2);
}

.modules-list-block-style-full .layout-iframe-preview {
    aspect-ratio: 7 / 6;
}

.modules-list-block-style-full .layout-preview-iframe {
    width: 400%;
    height: 400%;
    transform: scale(0.25);
}

/* Fallback for layouts with no screenshot and no preview */
.layout-no-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    padding: 20px;
    min-height: 120px;
    border-radius: 4px;
}

.modules-list-block-item-picture.layout-no-preview {
    height: 100%;
}

/* Layout image container positioning */
.layout-image-container {
    position: relative;
    display: inline-block;
    width: 100%;
}

/* Module icons overlay - positioned in top left corner */
.layout-modules-overlay {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 10;
    border-radius: 6px;
    backdrop-filter: blur(4px);
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Show overlay on hover */
.modules-list-block-item-masonry:hover .layout-modules-overlay,
.modules-list-block-item:hover .layout-modules-overlay {
    opacity: 1;
}

/* Always show overlay if there are modules (optional) */
.layout-modules-overlay {
    opacity: 1;
}

.modules-icons-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    align-items: center;
}

.module-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.9);
    transition: all 0.2s ease;
    cursor: help;
}

.module-icon-wrapper:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
}

.module-icon {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
}

.module-icon svg {
    width: 100%;
    height: 100%;
    fill: currentColor;
}




.dark .module-icon {
    color: #fff;
}

/* Ensure icons don't interfere with click events */
.layout-modules-overlay {
    pointer-events: none;
}

.module-icon-wrapper {
    pointer-events: auto;
}
</style>

<script>
import GridIcon from "../Icons/GridIcon.vue";
import ListIcon from '../Icons/ListIcon.vue';
import MasonryIcon from "../Icons/MasonryIcon.vue";
import LazyList from '../Optimizations/LazyLoadList/LazyList.vue';
import MasonryWall from '@yeger/vue-masonry-wall'
import {HomeIcon} from '@heroicons/vue/outline'

export default {
    components: {
        GridIcon,
        MasonryIcon,
        MasonryWall,
        LazyList,
        ListIcon
    },

    methods: {
        closeLicenseModal() {
            // this.showLicenseModal = false;
            // mw.top().dialog.get(this.$refs.unlockPremiumLayout).remove();
        },
        switchLayoutsListTypePreview(type) {
            this.layoutsListTypePreview = type;
            // AI-68 (cycle-70): the iframe wrappers belong to a
            // different sub-tree (masonry vs list/full); re-observe
            // after the new layout renders.
            this.$nextTick(() => {
                this.setupIframeObserver();
            });
        },
        insertLayout(layout, target) {
            if (this.isInserting) {
                return;
            }
            let template = false;
            if (layout.template) {
                template = layout.template;
            }
            if (!target) {
                target = this.$data.target
            }
            var liveEditIframeData = mw.top().app.canvas.getLiveEditData();
            if (layout.locked) {

                var attrsForSettings = {};
                attrsForSettings.live_edit = true;
                attrsForSettings.module_settings = true;
                attrsForSettings.id = 'mw_unlock_package_modal';
                // attrsForSettings.type = 'unlock-package/index';
                attrsForSettings.type = 'editor/unlock_package';
                attrsForSettings.iframe = true;
                attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;
                // attrsForSettings.rel_type='layout';

                if (liveEditIframeData && liveEditIframeData.template_name) {
                    attrsForSettings.template_name = liveEditIframeData.template_name;
                }
                if (liveEditIframeData && liveEditIframeData.template_composer && liveEditIframeData.template_composer.name) {
                    attrsForSettings.package_name = liveEditIframeData.template_composer.name;
                }

                var dialog = mw.app.moduleSettings.openSettingsModal(attrsForSettings, attrsForSettings.id, 'Unlock package')


//
//                 var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);
//
//                 var dialog = mw.top().dialogIframe({
//                     url: src,
//                     height: 'auto',
//                     width: 800,
//                     className: 'mw-unlock-package-modal',
//                     closeOnEscape: true,
//                     overlay: true,
//                     overlayClose: true
//                 });
//                 dialog.dialogHeader.style.display = 'none';
//                 dialog.iframe.addEventListener('load', () => {
//                     dialog.iframe.contentWindow.document.getElementById('js-modal-livewire-ui-close').addEventListener('click', () => {
//                         dialog.remove();
//                     });
//                 });

                return;
            }

            this.showModal = false;

            mw.app.editor.insertLayout({'template': template}, this.layoutInsertLocation, target);

            this.$data.target = undefined;
            setTimeout(() => {
                this.isInserting = false;
            }, 300);
        },

        getLayoutsListFromService(cache) {
            return mw.app.layouts.list(cache);
        },
        filterClearKeyword() {
            this.filterKeyword = '';
            this.filterLayouts();
        },
        searchInAll() {
            this.filterCategory = '';
            this.filterLayouts();
        },
        filterCategorySubmit(category) {
            this.filterCategory = category;
            this.filterLayouts();
        },
        // AI-714 / task-2026-05-16-8f20b6 — mobile filter helpers.
        // Tapping a category inside the mobile bottom-sheet should
        // select AND close the sheet so the user lands back on the
        // filtered grid in one gesture.
        toggleMobileFilter() {
            this.mobileFilterOpen = !this.mobileFilterOpen;
        },
        closeMobileFilter() {
            this.mobileFilterOpen = false;
        },
        filterCategoryFromMobile(category) {
            this.filterCategorySubmit(category);
            this.closeMobileFilter();
        },
        filterLayouts() {

            this.layoutsListLoaded = false;
            let layoutsFiltered = this.layoutsList.layouts;

            if (this.filterKeyword != '' && this.filterKeyword) {
                let filterKeyword = this.filterKeyword.toUpperCase();
                filterKeyword = filterKeyword.trim();
                layoutsFiltered = layoutsFiltered.filter((item) => {
                    return item.title
                        .toUpperCase()
                        .includes(filterKeyword)
                });
            }

            if (this.filterCategory != '' && this.filterCategory) {
                layoutsFiltered = layoutsFiltered.filter((item) => {
                    if (item.categories) {
                        return item.categories
                            .toUpperCase()
                            .includes(this.filterCategory.toUpperCase());
                    }
                });
            }

            this.layoutsListLoaded = true;
            this.layoutsListFiltered = layoutsFiltered;

            // AI-68 (cycle-70): re-attach the IntersectionObserver after
            // the next render tick so newly-rendered iframe wrappers
            // get observed. Using $nextTick keeps us in lockstep with
            // Vue's render cycle without sleep loops.
            this.$nextTick(() => {
                this.setupIframeObserver();
            });
        },

        // Get module icon from Microweber's module system
        getModuleIcon(moduleName) {
            // Check if Microweber's module system is available
            if (window.mw?.top()?.app?.modules) {
                const icon = window.mw.top().app.modules.getModuleIcon(moduleName);
                if (icon) {
                    return icon;
                }
            }

            // Fallback icons for common modules
            const fallbackIcons = {
                'background': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 11H7l3 3 3-3h-2V8h-2v3zm4-6h2v2h-2V5zm0 2h2v2h-2V7zm0 2h2v2h-2V9zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2zm0 2h2v2h-2v-2z"/></svg>',
                'spacer': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 21V3h2v18H8zM14 21V3h2v18h-2z"/></svg>',
                'btn': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
                'menu': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
                'content': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 17H4v-2h10v2zm6-8H4V7h16v2zM4 15h16v-2H4v2zM4 5v2h16V5H4z"/></svg>',
                'text': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 4v3h5.5v12h3V7H19V4H5z"/></svg>',
                'image': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>',
                'gallery': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11.5-6L8 13.5 5.5 10 2 16h16l-5.5-7.5z"/></svg>',
                'contact': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>',
                'testimonials': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 17h2v-2H7v2zm0-4h2v-2H7v2zm0-4h2V7H7v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2z"/></svg>',
                'shop': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
                'posts': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>',
                'social': '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'
            };

            return fallbackIcons[moduleName] || '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
        },

        // Get human-readable module name
        getModuleDisplayName(moduleName) {
            const displayNames = {
                'background': 'Background',
                'spacer': 'Spacer',
                'btn': 'Button',
                'menu': 'Menu',
                'content': 'Content',
                'text': 'Text',
                'image': 'Image',
                'gallery': 'Gallery',
                'contact': 'Contact Form',
                'testimonials': 'Testimonials',
                'shop': 'Shop',
                'posts': 'Posts',
                'social': 'Social'
            };

            return displayNames[moduleName] || moduleName.charAt(0).toUpperCase() + moduleName.slice(1);
        },

        // Show tooltip for module
        showModuleTooltip(event, moduleName) {
            // You can implement a more sophisticated tooltip system here
            // For now, the title attribute handles the tooltip
        },
        hasModulesToShow(modulesItem) {
            if (!modulesItem.found_modules) {
                return false;
            }

            // Check if found_modules is an array
            if (Array.isArray(modulesItem.found_modules)) {
                for (let moduleName of modulesItem.found_modules) {
                    if (!this.isSkipModule(moduleName)) {
                        return true;
                    }
                }
            } else {
                // Check if found_modules is an object
                for (let moduleName in modulesItem.found_modules) {
                    if (!this.isSkipModule(moduleName)) {
                        return true;
                    }
                }
            }

            return false;
        },

        // Show tooltip for module
        isSkipModule(moduleName) {


            var namesToSkip = ['background', 'spacer'];

            return namesToSkip.indexOf(moduleName) !== -1;
        },

        // Hide tooltip
        hideModuleTooltip() {
            // Tooltip cleanup if needed
        },

        // AI-68 / TICKET-OO (cycle-70 2026-05-08): lazy iframe mount —
        // helpers below. Stable per-item key so vue's reactive map
        // tracks the same identity as the user re-filters / re-scrolls.
        // Falls back to the item's array index when nothing else is
        // unique.
        iframeKeyFor(item, index) {
            if (item == null) {
                return 'mw-iframe-' + index;
            }
            return 'mw-iframe-' + (item.id ?? item.layout_file ?? item.preview_url ?? index);
        },

        iframeIsInView(key) {
            return !!this.iframesInView[key];
        },

        setupIframeObserver() {
            // No-op outside browsers (SSR / unit tests).
            if (typeof window === 'undefined' || typeof IntersectionObserver === 'undefined') {
                return;
            }
            if (this.iframeObserver) {
                this.iframeObserver.disconnect();
            }
            const self = this;
            this.iframeObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    const key = entry.target.getAttribute('data-mw-iframe-key');
                    if (!key) return;
                    if (!self.iframesInView[key]) {
                        // Vue 3 reactivity: assignment on a plain object
                        // works because we declared iframesInView in
                        // data() — no need for $set.
                        self.iframesInView[key] = true;
                    }
                    // Once in view, stop observing this node — next
                    // mount of the same iframe (if the user re-opens
                    // the picker) gets a fresh observation cycle.
                    self.iframeObserver.unobserve(entry.target);
                });
            }, {
                root: null,            // viewport-relative; the dialog
                                        // is full-screen so this is fine.
                rootMargin: '200px 0px', // start fetching just before
                                          // the iframe enters view.
                threshold: 0.01,
            });
            // Observe every wrapper that's currently in the DOM. Both
            // the masonry and list templates use refs collected as
            // arrays.
            const wrappers = []
                .concat(this.$refs.iframeWrappersMasonry || [])
                .concat(this.$refs.iframeWrappersList || []);
            wrappers.forEach(function (el) {
                if (el && el.nodeType === 1) {
                    self.iframeObserver.observe(el);
                }
            });
        }
    },
    mounted() {
        const instance = this;

        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            this.getLayoutsListFromService(false).then(function (data) {
                instance.layoutsList = data;
                instance.layoutsListLoaded = true;
                instance.filterLayouts();
            });
        });

        mw.app.on('ready', () => {

            const showModal = () => {
                instance.showModal = true;
                setTimeout(() => {
                    const searchField = document.querySelector('.mw-le-layouts-dialog input.modules-list-search-field');

                    if (searchField) {
                        searchField.focus()
                    }
                }, 100);
            }

            this.siteUrl = mw.settings.site_url;

            this.getLayoutsListFromService().then(function (data) {
                instance.layoutsList = data;
                instance.layoutsListLoaded = true;
                instance.filterLayouts();
            });
            mw.app.editor.on('insertLayoutRequestOnTop', function (element) {
                showModal()
                instance.layoutInsertLocation = 'top';
                mw.app.registerChangedState(element);
            });

            mw.app.editor.on('appendLayoutRequestOnBottom', function (element) {
                instance.target = element;
                showModal()
                instance.layoutInsertLocation = 'append';
                mw.app.registerChangedState(element);

            })
            mw.app.editor.on('insertLayoutRequestOnBottom', function (element) {
                showModal()
                instance.layoutInsertLocation = 'bottom';
                mw.app.registerChangedState(element);
            });
        });

        // this.emitter.on("live-edit-ui-show", show => {
        //
        // });

        // Close on Escape

        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                instance.showModal = false;
            }
        });
    },
    watch: {
        filterKeyword: function (newValue, oldValue) {
            console.log("filter keyword:" + newValue);
            this.filterLayouts();
        },
        filterCategory: function (newValue, oldValue) {
            console.log("filter category:" + newValue);
            this.filterLayouts();
        }
    },
    data() {
        return {
            licenseKey: '',
            filterKeyword: '',
            filterCategory: '',
            // AI-714 / task-2026-05-16-8f20b6 — mobile category filter
            // bottom-sheet state. Desktop uses the inline `.mw-le-layouts-
            // dialog-col:first-child` rail; mobile (≤767px) gets the
            // rail collapsed to a `Filter` trigger button that opens
            // this bottom-sheet. State drives both `aria-expanded` on
            // the trigger and `v-if` on the sheet panel.
            mobileFilterOpen: false,
            layoutsListTypePreview: 'list',
            layoutsList: [],
            layoutsListFiltered: [],
            layoutsListLoaded: false,
            layoutInsertLocation: 'top',
            showModal: false,
            isInserting: false,
            target: undefined,
            siteUrl: '',
            // AI-68 / TICKET-OO (cycle-70 2026-05-08): lazy iframe mount.
            // Map { iframeKey => true } for iframes that have entered
            // the viewport at least once. Once true, stays true (we
            // never un-mount an iframe the user has already seen — that
            // would re-trigger the costly load if they scrolled back).
            iframesInView: {},
            // IntersectionObserver instance, retained so beforeUnmount
            // can disconnect cleanly. Sentinel `null` until mounted.
            iframeObserver: null,
        }
    },
    beforeUnmount() {
        // AI-68: stop watching the DOM so the observer doesn't leak
        // across modal show/hide cycles. The masonry/list refs are
        // re-created every time the dialog reopens so re-attaching is
        // cheap.
        if (this.iframeObserver) {
            this.iframeObserver.disconnect();
            this.iframeObserver = null;
        }
    }
}
</script>
