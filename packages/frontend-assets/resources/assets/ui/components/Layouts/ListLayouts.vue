<template>


    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>

    <!-- task-2026-06-13-letele2 — NO Teleport needed. This dialog is position:fixed;
         it was previously teleported to <body> to escape a containing-block trap, but
         the real cause was a slide-in panel (.mw-control-box) whose SHOWN state used
         transform: translateX(0) — a non-none transform re-roots fixed descendants.
         That is fixed (control-box.scss active state → transform:none) and the modal
         mount point (#live-edit-app) now has NO transform/filter/perspective ancestor
         in any device-preview mode (the canvas + control-boxes are SIBLINGS, not
         ancestors). overflow:hidden/clip on .fi-layout/body does NOT clip fixed
         descendants without a transformed ancestor. Verified clickable in mobile
         mode without teleport. Do NOT re-introduce an ancestor transform on
         #live-edit-app's chain, or this modal will be trapped again. -->
    <div v-if="showModal" class="mw-le-overlay active" v-on:click="showModal = false"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >

        <div v-if="showModal"
             role="dialog"
             aria-modal="true"
             aria-labelledby="mw-le-layouts-dialog-title"
             :class="['mw-le-dialog-block mw-le-layouts-dialog w-100 active', pickerSkin === 'add-content' ? 'mw-le-dialog--addcontent' : '', pickerSkin === 'create-page' ? 'mw-le-dialog--createpage' : '', pickerSkin === 'edit-content' ? 'mw-le-dialog--editcontent' : '']"
             style="inset:20px; transform:none; animation-duration: .3s; z-index: 1000;"
        >

            <!-- audit-test 2026-05-07 ESE+picker audit findings #2/#6:
                 - aria-modal="true" + aria-labelledby on the dialog so
                   assistive tech traps focus and announces the title.
                 - Visually-hidden h2 carries the announce-only title;
                   aria-hidden="true" on the &times; glyph so SR reads
                   the close button's aria-label, not "multiplication". -->
            <!-- task-2026-06-04-lehdr — the announce-only title relied on the
                 Bootstrap `.visually-hidden` class, which is NOT loaded in the
                 live-edit canvas context, so the h2 rendered as a visible
                 full-width block that broke out of the modal's top edge (the
                 malformed "Insert layout" header). Use the canonical sr-only
                 inline style (AI-817 pattern) so it is hidden from sight but
                 still read by assistive tech, independent of any stylesheet. -->
            <h2 id="mw-le-layouts-dialog-title"
                style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">{{ $lang('Insert layout') }}</h2>

            <!-- Close Button -->
            <!-- task: the close X sat ~10px below the "All categories · N LAYOUTS"
                 result-header row. The button is a 44px flex-centred tap target, so
                 at top:16px its centred glyph landed at ~28px below the dialog top
                 while the header row centres at ~18px. top:6px re-centres the 44px
                 box on the header row (verified: close centreY == header centreY). -->
            <button
                :aria-label="$lang('Close picker')"
                class="mw-le-dialog-close-btn"
                style="position:absolute;top:6px;right:16px;z-index:10;background:none;border:none;font-size:2rem;line-height:1;cursor:pointer;"
                type="button"
                @click="showModal = false"
            >
                <span aria-hidden="true">&times;</span>
            </button>

            <div v-if="pickerSkin === 'layouts'" class="modules-list modules-list-defaultModules">
                <div class="mw-le-layouts-dialog-row">

                    <div v-if=" layoutsList?.categories?.length" class="mw-le-layouts-dialog-col">


                        <!-- audit-test 2026-05-07 picker audit finding #5:
                             categories function as tabs (click filters
                             which layouts show). Wire role="tablist" /
                             role="tab" / aria-selected so screen readers
                             announce "tabs, Content selected, 12 items"
                             instead of "list of 12 items".

                             AI-716 / task-2026-05-16-c4893b — 17 flat
                             categories split into 3 visual groups:
                               1. All Categories (top, no header)
                               2. Featured (designer-curated 6 high-use)
                               3. All categories (alphabetised remainder)
                             Single <ul role="tablist"> + interleaved
                             <li role="presentation"> group-header rows
                             so the tablist still announces all tabs
                             linearly to screen readers (AT users get
                             the unified semantics; sighted users get
                             the visual grouping). -->
                        <ul class="modules-list-categories py-5"
                            role="tablist"
                            :aria-label="$lang('Layout categories')">

                            <!-- Group 1: All categories — default active.
                                 task-2026-06-04-le1160 / AI-1160 — every desktop
                                 category tab is statically tabindex="0" (not a
                                 roving 0/-1) so all are Tab-reachable + operable.
                                 The prior roving tabindex left inactive tabs at
                                 -1 with no arrow-key handler to reach them, so
                                 the auditor found 0 focusable category links.
                                 Matches the mobile-sheet rail (already all
                                 tabindex="0") for cross-surface consistency. -->
                            <li role="tab"
                                :aria-selected="'' === filterCategory"
                                tabindex="0"
                                :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']"
                                v-on:click="filterCategorySubmit('')"
                                v-on:keydown.enter="filterCategorySubmit('')"
                                v-on:keydown.space.prevent="filterCategorySubmit('')">
                                All categories
                            </li>

                            <!-- Group 2: Featured (designer-curated). -->
                            <li v-if="featuredCategories.length"
                                role="presentation"
                                aria-hidden="true"
                                class="mw-le-layouts-categories-group-header">
                                {{ $lang('Featured') }}
                            </li>
                            <li v-for="categoryName in featuredCategories"
                                role="tab"
                                :key="'featured-' + categoryName"
                                :aria-selected="categoryName === filterCategory"
                                tabindex="0"
                                :data-category="[categoryName ? categoryName.toLowerCase(): '']"
                                v-on:click="filterCategorySubmit(categoryName)"
                                v-on:keydown.enter="filterCategorySubmit(categoryName)"
                                v-on:keydown.space.prevent="filterCategorySubmit(categoryName)">
                                <a :class="[categoryName == filterCategory ? 'active animate__animated animate__pulse': '']"
                                   class="mw-admin-action-links">
                                    {{ categoryName }}
                                </a>
                            </li>

                            <!-- Group 3: Other (alphabetised). task-2026-05-21-3d7892 / AI-873
                                 Renamed from 'All categories' → 'More categories' so the section
                                 header is distinct from the 'All categories' tab in Group 1. -->
                            <li v-if="otherCategories.length"
                                role="presentation"
                                aria-hidden="true"
                                class="mw-le-layouts-categories-group-header">
                                {{ $lang('More categories') }}
                            </li>
                            <li v-for="categoryName in otherCategories"
                                role="tab"
                                :key="'other-' + categoryName"
                                :aria-selected="categoryName === filterCategory"
                                tabindex="0"
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
                                <!-- AI-716 / task-2026-05-16-c4893b — same
                                     3-group hierarchy as the desktop rail
                                     so users get a consistent mental
                                     model across viewports. featuredSet
                                     + alphabetised remainder are computed
                                     once and reused for both surfaces. -->
                                <ul class="modules-list-categories mw-le-layouts-mobile-filter-sheet__list"
                                    role="tablist"
                                    :aria-label="$lang('Layout categories')">
                                    <!-- Group 1: All categories. -->
                                    <li role="tab"
                                        :aria-selected="'' === filterCategory"
                                        :class="['' == filterCategory ? 'active' : '']"
                                        v-on:click="filterCategoryFromMobile('')"
                                        v-on:keydown.enter="filterCategoryFromMobile('')"
                                        v-on:keydown.space.prevent="filterCategoryFromMobile('')"
                                        tabindex="0">
                                        {{ $lang('All categories') }}
                                    </li>
                                    <!-- Group 2: Featured. -->
                                    <li v-if="featuredCategories.length"
                                        role="presentation"
                                        aria-hidden="true"
                                        class="mw-le-layouts-categories-group-header">
                                        {{ $lang('Featured') }}
                                    </li>
                                    <li v-for="categoryName in featuredCategories"
                                        role="tab"
                                        :key="'mobile-featured-' + categoryName"
                                        :aria-selected="categoryName === filterCategory"
                                        :class="[categoryName == filterCategory ? 'active' : '']"
                                        v-on:click="filterCategoryFromMobile(categoryName)"
                                        v-on:keydown.enter="filterCategoryFromMobile(categoryName)"
                                        v-on:keydown.space.prevent="filterCategoryFromMobile(categoryName)"
                                        tabindex="0">
                                        {{ categoryName }}
                                    </li>
                                    <!-- Group 3: Other (alphabetised). task-2026-05-21-3d7892 / AI-873 -->
                                    <li v-if="otherCategories.length"
                                        role="presentation"
                                        aria-hidden="true"
                                        class="mw-le-layouts-categories-group-header">
                                        {{ $lang('More categories') }}
                                    </li>
                                    <li v-for="categoryName in otherCategories"
                                        role="tab"
                                        :key="'mobile-other-' + categoryName"
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

                        <!-- task-2026-05-28-2f5a6c / AI-1145 — masonry gap + padding
                             standardised to 16px (was 12px) so the masonry view
                             matches the .modules-list-block CSS rule used by the
                             list + full views below (also 16px after this ticket).
                             All three views now use a consistent 16px gutter. -->
                        <div v-if="layoutsListLoaded && layoutsListTypePreview == 'masonry'"
                             class="modules-list-block-masonry">
                            <MasonryWall
                                :column-width="400"
                                :gap="16"
                                :items="layoutsListFiltered"
                                :padding="16"
                                :ssr-columns="1">
                                <template #default="{ item, index }">
                                    <!-- task-2026-05-27-de93c9 / AI-1167: aria-label for WCAG
                                         task-2026-05-28-af5d5b / AI-1158: tabindex + keyboard handlers
                                         (Enter/Space) so layout cards meet WCAG 2.1.1 Keyboard. -->
                                    <div
                                        :class="['modules-list-block-item-masonry', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']"
                                        role="button"
                                        tabindex="0"
                                        :data-template="item.template"
                                        :aria-label="'Layout: ' + item.title"
                                        v-on:click="insertLayout(item)"
                                        v-on:keydown.enter.prevent="insertLayout(item)"
                                        v-on:keydown.space.prevent="insertLayout(item)">

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
                                <!-- task-2026-05-27-de93c9 / AI-1167: aria-label for WCAG
                                     task-2026-05-28-af5d5b / AI-1158: tabindex + keyboard handlers
                                     (Enter/Space) so layout cards meet WCAG 2.1.1 Keyboard. -->
                                <div
                                    :class="['modules-list-block-style-' + layoutsListTypePreview, 'modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']"
                                    role="button"
                                    tabindex="0"
                                    :data-template="item.template"
                                    :aria-label="'Layout: ' + item.title"
                                    v-on:click="insertLayout(item)"
                                    v-on:keydown.enter.prevent="insertLayout(item)"
                                    v-on:keydown.space.prevent="insertLayout(item)">

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

            <!-- ── add-content skin (hybrid of mockups 2a + 2b) ────────────── -->
            <div v-if="pickerSkin === 'add-content'" class="mw-le-addcontent">
                <div class="mw-le-addcontent-row">

                    <!-- LEFT RAIL: content types -->
                    <aside class="mw-le-addcontent-rail">
                        <div class="mw-le-addcontent-rail-title">{{ $lang('Add new') }}</div>

                        <div class="mw-le-addcontent-rail-group">{{ $lang('This page') }}</div>
                        <button v-for="t in addContentThisPage" :key="t.key" type="button"
                                class="mw-le-addcontent-rail-item"
                                :class="{ 'is-active': addContentSelectedType === t.key }"
                                @click="addContentSelect(t.key)">
                            <span class="mw-le-addcontent-rail-badge mw-le-addcontent-rail-badge--plus">+</span>
                            <span class="mw-le-addcontent-rail-label">{{ t.label }}</span>
                        </button>

                        <div class="mw-le-addcontent-rail-group">{{ $lang('Site content') }}</div>
                        <button v-for="t in addContentSiteContent" :key="t.key" type="button"
                                class="mw-le-addcontent-rail-item"
                                :class="{ 'is-active': addContentSelectedType === t.key }"
                                @click="addContentSelect(t.key)">
                            <span class="mw-le-addcontent-rail-badge" :style="{ background: t.tint }">{{ t.badge }}</span>
                            <span class="mw-le-addcontent-rail-label">{{ t.label }}</span>
                            <span v-if="t.shortcut" class="mw-le-addcontent-rail-key">{{ t.shortcut }}</span>
                        </button>
                    </aside>

                    <!-- MAIN -->
                    <section class="mw-le-addcontent-main">

                        <!-- BLOCK mode (2b): search + block grid + create-content pills -->
                        <div v-if="addContentSelectedType === 'block'">
                            <div class="mw-le-addcontent-search input-icon">
                                <svg class="mw-le-addcontent-search-ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="search" class="form-control" v-model="filterKeyword"
                                       :aria-label="$lang('Search blocks and content')"
                                       :placeholder="$lang('Search blocks and content')">
                            </div>

                            <div class="mw-le-addcontent-blocks">
                                <button v-for="b in addContentBlocks" :key="b.key" type="button"
                                        class="mw-le-addcontent-block" @click="addContentBlockClick(b)">
                                    <span class="mw-le-addcontent-block-thumb" :class="'mw-le-addcontent-block-thumb--' + b.key"></span>
                                    <span class="mw-le-addcontent-block-label">{{ b.label }}</span>
                                </button>
                            </div>

                            <div class="mw-le-addcontent-allblocks">
                                <button type="button" class="mw-le-addcontent-link" @click="addContentOpenLayouts('')">
                                    {{ $lang('All blocks') }} →
                                </button>
                            </div>

                            <div class="mw-le-addcontent-sep">
                                <span>{{ $lang('Or create site content') }}</span>
                            </div>
                            <div class="mw-le-addcontent-pills">
                                <button v-for="t in addContentSiteContent" :key="'pill-' + t.key" type="button"
                                        class="mw-le-addcontent-pill" @click="addContentSelect(t.key)">
                                    <span class="mw-le-addcontent-pill-dot" :style="{ background: t.tint }"></span>
                                    {{ t.label }}
                                </button>
                            </div>
                        </div>

                        <!-- CONTENT-TYPE mode (2a): description + preview + inline create -->
                        <div v-else-if="addContentActiveType" class="mw-le-addcontent-detail">
                            <h3 class="mw-le-addcontent-detail-title">{{ addContentActiveType.label }}</h3>
                            <p class="mw-le-addcontent-detail-desc">{{ addContentActiveType.description }}</p>

                            <label v-if="addContentActiveType.quickCreate" class="mw-le-addcontent-field">
                                <span class="mw-le-addcontent-field-label">{{ $lang('Title') }}</span>
                                <input type="text" class="mw-le-addcontent-input"
                                       v-model="addContentTitle"
                                       :placeholder="$lang('Untitled') + ' ' + addContentActiveType.label.toLowerCase()"
                                       @keydown.enter.prevent="addContentQuickCreate(addContentActiveType, false)">
                            </label>

                            <div v-if="addContentActiveType.quickCreate && addContentSelectedType !== 'category'" class="mw-le-addcontent-field">
                                <span class="mw-le-addcontent-field-label">{{ $lang('Image') }}</span>
                                <div class="mw-le-ni-image-row">
                                    <button v-if="!niImage" type="button" class="mw-le-ni-add" @click="niPickImage()">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                        {{ $lang('Add image') }}
                                    </button>
                                    <template v-else>
                                        <span class="mw-le-ni-thumb"><img :src="niImage" alt=""></span>
                                        <button type="button" class="mw-le-addcontent-link" @click="niPickImage()">{{ $lang('Change') }}</button>
                                        <button type="button" class="mw-le-addcontent-link mw-le-ni-remove" @click="niRemoveImage()">{{ $lang('Remove') }}</button>
                                    </template>
                                </div>
                            </div>

                            <!-- Page: real create fields inline (no second-step dialog) -->
                            <template v-if="addContentSelectedType === 'page'">
                                <div class="mw-le-addcontent-field">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Start from') }}</span>
                                    <div class="mw-le-cp-starts mw-le-ac-starts">
                                        <button v-for="o in cpStartFromOptions" :key="o.key" type="button"
                                                class="mw-le-cp-start" :class="{ 'is-on': cpStartFrom === o.key }"
                                                @click="cpSetStartFrom(o.key)">
                                            <span class="mw-le-cp-start-thumb" :class="{ ['mw-le-cp-start-thumb--blank']: !o.screenshot }">
                                                <img v-if="o.screenshot" :src="o.screenshot" :alt="o.label" loading="lazy">
                                            </span>
                                            <span class="mw-le-cp-start-label">{{ o.label }}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mw-le-addcontent-field mw-le-ac-menu-row">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Add to main menu') }}</span>
                                    <span class="mw-le-cp-toggle" :class="{ 'is-on': cpAddToMenu }" @click="cpAddToMenu = !cpAddToMenu">
                                        <span class="mw-le-cp-toggle-knob"></span>
                                    </span>
                                </div>
                            </template>

                            <!-- Category: real create fields inline (no second-step handoff) -->
                            <template v-if="addContentSelectedType === 'category'">
                                <div class="mw-le-addcontent-field">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Add to') }}</span>
                                    <!-- Same mw.widget.tree picker the admin category form uses
                                         (pick a parent page → rel_id, or a category → parent_id). -->
                                    <div id="mw-le-cat-tree" class="mw-le-cat-tree" wire:ignore></div>
                                </div>
                                <label class="mw-le-addcontent-field">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Description') }} <span class="mw-le-cp-muted">· {{ $lang('optional') }}</span></span>
                                    <textarea class="mw-le-addcontent-input mw-le-ac-textarea" rows="2" v-model="catDescription"
                                              :placeholder="$lang('What does this group organize?')"></textarea>
                                </label>
                            </template>

                            <div v-if="addContentSelectedType === 'product'" class="mw-le-ni-prices">
                                <label class="mw-le-ni-price-col">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Price') }}</span>
                                    <input type="number" step="0.01" min="0" class="mw-le-addcontent-input" v-model="niPrice" placeholder="0.00">
                                </label>
                                <label class="mw-le-ni-price-col">
                                    <span class="mw-le-addcontent-field-label">{{ $lang('Special price') }}</span>
                                    <input type="number" step="0.01" min="0" class="mw-le-addcontent-input" v-model="niSpecialPrice" placeholder="0.00">
                                </label>
                            </div>
                            <p class="mw-le-addcontent-create-error" v-show="addContentError">{{ addContentError }}</p>

                            <div class="mw-le-addcontent-detail-foot">
                                <span class="mw-le-addcontent-detail-hint">{{ $lang('Opens in the editor after creating') }}</span>
                                <span v-if="addContentActiveType.quickCreate" class="mw-le-addcontent-create-actions">
                                    <button v-if="addContentSelectedType !== 'category'" type="button" class="mw-le-addcontent-draft" :disabled="addContentCreating"
                                            @click="addContentQuickCreate(addContentActiveType, true)">
                                        {{ $lang('Draft') }}
                                    </button>
                                    <button type="button" class="mw-le-addcontent-create" :disabled="addContentCreating"
                                            @click="addContentQuickCreate(addContentActiveType, false)">
                                        <span v-if="!addContentCreating">{{ $lang('Create') }} {{ addContentActiveType.label.toLowerCase() }}</span>
                                        <span v-else>{{ $lang('Creating') }}…</span>
                                    </button>
                                </span>
                                <button v-else type="button" class="mw-le-addcontent-create"
                                        @click="addContentCreate(addContentActiveType)">
                                    {{ $lang('Create') }} {{ addContentActiveType.label.toLowerCase() }}
                                </button>
                            </div>
                        </div>

                    </section>
                </div>
            </div>

            <!-- ── create-page skin (two-pane form + preview) ──────────────── -->
            <div v-if="pickerSkin === 'create-page'" class="mw-le-cp">
                <!-- LEFT: form -->
                <section class="mw-le-cp-form">
                    <div class="mw-le-cp-head">
                        <span class="mw-le-cp-badge">
                            <svg viewBox="0 96 960 960" fill="currentColor" width="17" height="17" aria-hidden="true"><path d="M329.59 801.127h300.82v-50.254H329.59v50.254Zm0-164.871h300.82v-50.255H329.59v50.255Zm-87.025 319.743q-25.788 0-44.176-18.388t-18.388-44.176v-634.87q0-25.788 18.388-44.176t44.176-18.388h337.59l199.844 199.844v497.59q0 25.788-18.388 44.176t-44.176 18.388h-474.87Zm312.462-536.513v-173.23H242.565q-4.616 0-8.462 3.847-3.847 3.846-3.847 8.462v634.87q0 4.616 3.847 8.462 3.846 3.847 8.462 3.847h474.87q4.616 0 8.462-3.847 3.847-3.846 3.847-8.462V419.486H555.027Zm-324.771-173.23v173.23-173.23V905.744 246.256Z"/></svg>
                        </span>
                        <span class="mw-le-cp-head-title">{{ $lang('New page') }}</span>
                    </div>

                    <input type="text" class="mw-le-cp-title-input"
                           v-model="cpTitle" @input="cpOnTitleInput"
                           :placeholder="$lang('Page title')"
                           @keydown.enter.prevent="cpCreate(false)">

                    <div class="mw-le-cp-slug">
                        <span class="mw-le-cp-slug-host">mysite.com/</span>
                        <template v-if="!cpSlugEditing">
                            <span class="mw-le-cp-slug-value">{{ cpEffectiveSlug() }}</span>
                            <button type="button" class="mw-le-cp-link" @click="cpSlugEditing = true">{{ $lang('Edit') }}</button>
                        </template>
                        <template v-else>
                            <input type="text" class="mw-le-cp-slug-input" v-model="cpSlug"
                                   @input="cpSlugEdited = true" @blur="cpSlugEditing = false"
                                   @keydown.enter.prevent="cpSlugEditing = false">
                        </template>
                    </div>

                    <div class="mw-le-cp-field">
                        <div class="mw-le-cp-field-head">
                            <span class="mw-le-cp-label">{{ $lang('Start from') }}</span>
                            <button type="button" class="mw-le-cp-link" @click="cpAllLayouts()">{{ $lang('All layouts') }} →</button>
                        </div>
                        <div class="mw-le-cp-starts">
                            <button v-for="o in cpStartFromOptions" :key="o.key" type="button"
                                    class="mw-le-cp-start" :class="{ 'is-on': cpStartFrom === o.key }"
                                    @click="cpSetStartFrom(o.key)">
                                <span class="mw-le-cp-start-thumb" :class="{ ['mw-le-cp-start-thumb--blank']: !o.screenshot }">
                                    <img v-if="o.screenshot" :src="o.screenshot" :alt="o.label" loading="lazy">
                                </span>
                                <span class="mw-le-cp-start-label">{{ o.label }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="mw-le-cp-field">
                        <div class="mw-le-cp-field-head">
                            <span class="mw-le-cp-label">{{ $lang('Content') }} <span class="mw-le-cp-muted">· {{ $lang('optional') }}</span></span>
                            <button type="button" class="mw-le-cp-link" @click="cpDraftFromTitle()">✦ {{ $lang('Draft from title') }}</button>
                        </div>
                        <textarea class="mw-le-cp-textarea" rows="2" v-model="cpContent"
                                  :placeholder="$lang('Add a few notes or paste text — you can edit everything on the page afterwards.')"></textarea>
                    </div>

                    <div class="mw-le-cp-field">
                        <span class="mw-le-cp-label">{{ $lang('Image') }} <span class="mw-le-cp-muted">· {{ $lang('optional') }}</span></span>
                        <div class="mw-le-ni-image-row">
                            <button v-if="!niImage" type="button" class="mw-le-ni-add" @click="niPickImage()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                {{ $lang('Add image') }}
                            </button>
                            <template v-else>
                                <span class="mw-le-ni-thumb"><img :src="niImage" alt=""></span>
                                <button type="button" class="mw-le-cp-link" @click="niPickImage()">{{ $lang('Change') }}</button>
                                <button type="button" class="mw-le-cp-link mw-le-ni-remove" @click="niRemoveImage()">{{ $lang('Remove') }}</button>
                            </template>
                        </div>
                    </div>

                    <div class="mw-le-cp-rows">
                        <div class="mw-le-cp-row mw-le-cp-row--dropdown">
                            <span class="mw-le-cp-row-main"><span>{{ $lang('Parent page') }}</span></span>
                            <div class="mw-le-cp-parentpick">
                                <button type="button" class="mw-le-cp-row-value mw-le-cp-row-value--btn" @click="cpToggleParent()">
                                    {{ cpParentLabel || $lang('Top level') }} ▾
                                </button>
                                <div v-show="cpParentOpen" class="mw-le-cp-parent-menu">
                                    <input type="text" class="mw-le-cp-parent-search"
                                           :placeholder="$lang('Filter pages') + '…'" v-model="cpParentFilter">
                                    <div class="mw-le-cp-parent-list">
                                        <button type="button" class="mw-le-cp-parent-item" @click="cpPickParent(null)">{{ $lang('Top level') }}</button>
                                        <button v-for="p in cpFilteredParents()" :key="p.id" type="button"
                                                class="mw-le-cp-parent-item" @click="cpPickParent(p)">{{ p.title }}</button>
                                        <div v-if="!cpParentLoaded" class="mw-le-cp-parent-empty">{{ $lang('Loading') }}…</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="mw-le-cp-row">
                            <span class="mw-le-cp-row-main">
                                <span>{{ $lang('Add to main menu') }}</span>
                            </span>
                            <span class="mw-le-cp-toggle" :class="{ 'is-on': cpAddToMenu }" @click="cpAddToMenu = !cpAddToMenu">
                                <span class="mw-le-cp-toggle-knob"></span>
                            </span>
                        </label>
                    </div>

                    <button type="button" class="mw-le-cp-more" @click="cpMoreOpen = !cpMoreOpen">
                        {{ $lang('More settings — visibility, SEO, template') }} ▾
                    </button>
                    <div v-show="cpMoreOpen" class="mw-le-cp-more-body">
                        <p class="mw-le-cp-muted">{{ $lang('Full visibility, SEO and template options open in the editor after creating.') }}</p>
                    </div>
                </section>

                <!-- RIGHT: preview + create -->
                <aside class="mw-le-cp-preview">
                    <div class="mw-le-cp-preview-head">
                        <span>{{ $lang('Preview') }}</span>
                    </div>
                    <div class="mw-le-cp-preview-frame">
                        <img v-if="cpActiveStartFrom().screenshot" class="mw-le-cp-preview-img"
                             :src="cpActiveStartFrom().screenshot" :alt="cpActiveStartFrom().label">
                        <iframe v-else-if="cpActiveStartFrom().previewUrl" class="mw-le-cp-preview-iframe"
                                :src="cpActiveStartFrom().previewUrl" loading="lazy" title="Layout preview"></iframe>
                        <div v-else class="mw-le-cp-skeleton">
                            <span class="sk sk-title"></span>
                            <span class="sk sk-hero"></span>
                            <span class="sk sk-line"></span>
                            <span class="sk sk-line sk-short"></span>
                        </div>
                    </div>
                    <p class="mw-le-cp-preview-desc">
                        <strong>{{ cpActiveStartFrom().label }}</strong> — {{ cpActiveStartFrom().description }}
                    </p>
                    <p class="mw-le-cp-error" v-show="cpError">{{ cpError }}</p>
                    <button type="button" class="mw-le-cp-create" :disabled="cpCreating" @click="cpCreate(false)">
                        <span v-if="!cpCreating">{{ $lang('Create and open editor') }}</span>
                        <span v-else>{{ $lang('Creating') }}…</span>
                    </button>
                    <button type="button" class="mw-le-cp-draft-link" :disabled="cpCreating" @click="cpCreate(true)">
                        {{ $lang('Create as draft') }}
                    </button>
                </aside>
            </div>

            <!-- ── edit-content skin (Page settings) ───────────────────────── -->
            <div v-if="pickerSkin === 'edit-content'" class="mw-le-ec">
                <div class="mw-le-ec-head">
                    <span class="mw-le-ec-badge mw-le-ec-badge--icon" v-html="ecBadgeIcon"></span>
                    <span class="mw-le-ec-head-label">{{ ecTypeLabel }} {{ $lang('settings') }}</span>
                    <span class="mw-le-ec-status" :class="{ 'is-draft': !ecPublished }">
                        <span class="mw-le-ec-status-dot"></span>{{ ecPublished ? $lang('Published') : $lang('Draft') }}
                    </span>
                </div>

                <div v-if="ecLoading" class="mw-le-ec-loading">{{ $lang('Loading') }}…</div>
                <template v-else>
                    <input type="text" class="mw-le-ec-title" v-model="ecTitle" @input="ecTouch('title')"
                           :placeholder="$lang('Untitled')">

                    <div class="mw-le-ec-url">
                        <span class="mw-le-ec-url-host">{{ ecHost }}/</span>
                        <template v-if="!ecSlugEditing">
                            <span class="mw-le-ec-url-slug">{{ ecSlug }}</span>
                            <button type="button" class="mw-le-ec-link" @click="ecSlugEditing = true">{{ $lang('Edit') }}</button>
                        </template>
                        <input v-else type="text" class="mw-le-ec-url-input" v-model="ecSlug"
                               @input="ecTouch('url')" @blur="ecSlugEditing = false" @keydown.enter.prevent="ecSlugEditing = false">
                        <button type="button" class="mw-le-ec-open" @click="ecOpenPage()">{{ $lang('Open page') }} ↗</button>
                    </div>

                    <!-- Design -->
                    <div class="mw-le-ec-row">
                        <span class="mw-le-ec-thumb mw-le-ec-thumb--design"></span>
                        <div class="mw-le-ec-row-main">
                            <div class="mw-le-ec-row-title">{{ $lang('Design') }}</div>
                            <div class="mw-le-ec-row-sub">{{ ecDesign.template }} · {{ ecDesign.layout }}</div>
                        </div>
                        <button type="button" class="mw-le-ec-btn" :class="{ 'is-on': ecDesignOpen }" @click="ecDesignOpen = !ecDesignOpen">{{ ecDesignOpen ? $lang('Done') : $lang('Change') }}</button>
                    </div>
                    <div v-if="ecDesignOpen" class="mw-le-ec-expand">
                        <label class="mw-le-ec-field">
                            <span class="mw-le-ec-field-label">{{ $lang('Template') }}</span>
                            <select class="mw-le-ec-select" v-model="ecTemplateKey" @change="ecOnTemplateChange()">
                                <option v-for="t in ecTemplates" :key="t.dir" :value="t.dir">{{ t.name }}</option>
                            </select>
                        </label>
                        <label class="mw-le-ec-field">
                            <span class="mw-le-ec-field-label">{{ $lang('Layout') }}</span>
                            <select class="mw-le-ec-select" v-model="ecLayoutKey" @change="ecTouch('design')">
                                <option v-for="l in ecLayouts" :key="l.file" :value="l.file">{{ l.name }}</option>
                            </select>
                        </label>
                    </div>

                    <!-- Visibility -->
                    <div class="mw-le-ec-row">
                        <div class="mw-le-ec-row-main">
                            <div class="mw-le-ec-row-title">{{ $lang('Visibility') }}</div>
                            <div class="mw-le-ec-row-sub">
                                <template v-if="ecPublished">{{ $lang('Live since') }} {{ ecLiveDate || '—' }}</template>
                                <template v-else>{{ $lang('Not published yet') }}</template>
                            </div>
                        </div>
                        <div class="mw-le-ec-seg">
                            <button type="button" :class="{ active: ecPublished }" @click="ecSetPublished(true)">{{ $lang('Published') }}</button>
                            <button type="button" :class="{ active: !ecPublished }" @click="ecSetPublished(false)">{{ $lang('Draft') }}</button>
                        </div>
                    </div>

                    <!-- Parent page -->
                    <div class="mw-le-ec-row">
                        <div class="mw-le-ec-row-main">
                            <div class="mw-le-ec-row-title">{{ $lang('Parent page') }}</div>
                            <div class="mw-le-ec-row-sub">{{ ecParentCurrentLabel }}<span v-if="ecIsHome" class="mw-le-ec-muted"> ({{ $lang('is homepage') }})</span></div>
                        </div>
                        <button type="button" class="mw-le-ec-btn" :class="{ 'is-on': ecMoveOpen }" @click="ecToggleMove()">{{ ecMoveOpen ? $lang('Done') : $lang('Change') }}</button>
                    </div>
                    <div v-if="ecMoveOpen" class="mw-le-ec-expand mw-le-ec-expand--col">
                        <div class="mw-le-ec-field">
                            <div class="mw-le-ec-field-head">
                                <span class="mw-le-ec-field-label">{{ $lang('Parent page') }}</span>
                                <span class="mw-le-ec-field-val">{{ ecParentCurrentLabel }}</span>
                            </div>
                            <button type="button" class="mw-le-ec-toplevel" :class="{ 'is-on': !ecParentId }" @click="ecPickParent(0, '')">
                                {{ $lang('Top level') }}
                            </button>
                            <div id="mw-le-ec-parent-tree" class="mw-le-ec-tree" wire:ignore></div>
                        </div>
                    </div>

                    <!-- Show in main menu -->
                    <div class="mw-le-ec-row">
                        <div class="mw-le-ec-row-main">
                            <div class="mw-le-ec-row-title">{{ $lang('Show in main menu') }}</div>
                            <div class="mw-le-ec-row-sub">
                                <template v-if="ecInMenu">{{ $lang('Appears as') }} “{{ ecMenuLabel || ecTitle }}”<template v-if="ecMenuPosition">, {{ $lang('position') }} {{ ecMenuPosition }}</template></template>
                                <template v-else>{{ $lang('Not in the menu') }}</template>
                            </div>
                        </div>
                        <span class="mw-le-cp-toggle" :class="{ 'is-on': ecInMenu }" @click="ecToggleMenu()"><span class="mw-le-cp-toggle-knob"></span></span>
                    </div>

                    <!-- Cover image -->
                    <div class="mw-le-ec-row">
                        <span class="mw-le-ec-thumb" :class="{ 'mw-le-ec-thumb--empty': !ecCover }">
                            <img v-if="ecCover" :src="ecCover.url" alt="">
                        </span>
                        <div class="mw-le-ec-row-main">
                            <div class="mw-le-ec-row-title">{{ $lang('Cover image') }}</div>
                            <div class="mw-le-ec-row-sub">
                                <template v-if="ecCover">{{ ecCover.name }} · {{ $lang('used for sharing previews') }}</template>
                                <template v-else>{{ $lang('No cover image') }}</template>
                            </div>
                        </div>
                        <button type="button" class="mw-le-ec-btn" @click="ecReplaceCover()">{{ ecCover ? $lang('Replace') : $lang('Add') }}</button>
                    </div>

                    <!-- Open in admin (full form — SEO, sharing, custom fields, …) -->
                    <button type="button" class="mw-le-ec-advanced" @click="ecOpenAdvanced()">
                        <span>{{ $lang('Open in admin') }}<small>{{ $lang('Advanced settings — SEO, sharing, custom fields, tags, scheduling') }}</small></span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M17 7H8M17 7v9"/></svg>
                    </button>

                    <p class="mw-le-ec-error" v-show="ecError">{{ ecError }}</p>

                    <div class="mw-le-ec-foot">
                        <button type="button" class="mw-le-ec-delete" @click="ecDelete()">{{ $lang('Delete') }} {{ ecTypeLabel.toLowerCase() }}</button>
                        <span class="mw-le-ec-foot-spacer"></span>
                        <span v-if="ecDirtyCount" class="mw-le-ec-dirty">{{ ecDirtyCount }} {{ ecDirtyCount > 1 ? $lang('unsaved changes') : $lang('unsaved change') }}</span>
                        <button type="button" class="mw-le-ec-cancel" @click="showModal = false">{{ $lang('Cancel') }}</button>
                        <button type="button" class="mw-le-ec-save" :disabled="ecSaving || !ecDirtyCount" @click="ecSave()">
                            {{ ecSaving ? $lang('Saving') + '…' : $lang('Save') }}
                        </button>
                    </div>
                </template>
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

/* ── add-content skin (hybrid 2a + 2b) ──────────────────────────────────── */
.mw-le-layouts-dialog.mw-le-dialog--addcontent {
    inset: auto !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: min(980px, 94vw) !important;
    max-width: 980px !important;
    height: auto !important;
    max-height: 88vh !important;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(24, 36, 51, .28);
}
.mw-le-addcontent {
    --ac-ink: #182433;
    --ac-muted: #77776f;
    --ac-hairline: #e6e6e2;
    --ac-surface: #f4f4f2;
    display: flex;
    min-height: 420px;
    max-height: 88vh;
    color: var(--ac-ink);
}
.mw-le-addcontent-row { display: flex; width: 100%; }

/* rail */
.mw-le-addcontent-rail {
    flex: 0 0 220px;
    border-right: 1px solid var(--ac-hairline);
    background: #fbfbfa;
    padding: 22px 14px;
    overflow-y: auto;
}
.mw-le-addcontent-rail-title { font-size: 16px; font-weight: 700; padding: 0 8px 14px; }
.mw-le-addcontent-rail-group {
    font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: #a7a79f; padding: 14px 8px 6px;
}
.mw-le-addcontent-rail-item {
    display: flex; align-items: center; gap: 10px; width: 100%;
    padding: 8px; border: 0; background: none; border-radius: 8px; cursor: pointer;
    font-size: 14px; font-weight: 500; color: var(--ac-ink); text-align: left;
    transition: background .12s ease;
}
.mw-le-addcontent-rail-item:hover { background: #f1f1ee; }
.mw-le-addcontent-rail-item.is-active { background: #edeeff; }
.mw-le-addcontent-rail-badge {
    flex: none; width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; color: #4a4a63; background: var(--ac-surface);
}
.mw-le-addcontent-rail-badge--plus { background: var(--ac-ink); color: #fff; font-size: 15px; }
.mw-le-addcontent-rail-label { flex: 1 1 auto; }
.mw-le-addcontent-rail-key {
    flex: none; font-size: 11px; font-weight: 600; color: #b3b3ac;
    border: 1px solid var(--ac-hairline); border-radius: 5px; padding: 1px 6px; background: #fff;
}

/* main */
.mw-le-addcontent-main { flex: 1 1 auto; padding: 26px 28px; overflow-y: auto; min-width: 0; }

/* block mode (2b) */
/* Reserve room at the top-right for the absolute close (×) button so it never
   overlaps the full-width search field (× sits at right:16px, ~44px wide). */
.mw-le-addcontent-search { position: relative; margin-bottom: 20px; padding-right: 40px; }
.mw-le-addcontent-search-ico { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #a7a79f; pointer-events: none; }
.mw-le-addcontent-search .form-control {
    width: 100%; min-height: 44px; padding: 10px 14px 10px 42px;
    border: 1px solid var(--ac-hairline); border-radius: 10px; font-size: 14px; background: #fff;
}
.mw-le-addcontent-search .form-control:focus { outline: none; border-color: #b9c2ff; box-shadow: 0 0 0 3px rgba(90, 110, 240, .15); }

.mw-le-addcontent-blocks { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.mw-le-addcontent-block {
    display: flex; flex-direction: column; gap: 8px; padding: 12px;
    border: 1px solid var(--ac-hairline); border-radius: 12px; background: #fff; cursor: pointer;
    transition: border-color .12s ease, box-shadow .12s ease;
}
.mw-le-addcontent-block:hover { border-color: #cfd6ff; box-shadow: 0 2px 10px rgba(24, 36, 51, .1); }
.mw-le-addcontent-block-thumb { display: block; height: 46px; border-radius: 8px; background: var(--ac-surface); }
.mw-le-addcontent-block-label { font-size: 13px; font-weight: 600; text-align: center; }
/* lightweight glyph hints per block */
.mw-le-addcontent-block-thumb--text { background:
    linear-gradient(#c9ccd2,#c9ccd2) 12px 14px/60% 4px no-repeat,
    linear-gradient(#dfe1e5,#dfe1e5) 12px 24px/40% 4px no-repeat, var(--ac-surface); }
.mw-le-addcontent-block-thumb--image,
.mw-le-addcontent-block-thumb--gallery { background: linear-gradient(135deg,#c7cef0,#d7c9ea); }
.mw-le-addcontent-block-thumb--button { background: var(--ac-surface); position: relative; }
.mw-le-addcontent-block-thumb--button::after { content: ''; position: absolute; inset: 15px 22px; background: #182433; border-radius: 5px; }
.mw-le-addcontent-block-thumb--video { background: #182433; }
.mw-le-addcontent-block-thumb--divider { background: var(--ac-surface); position: relative; }
.mw-le-addcontent-block-thumb--divider::after { content: ''; position: absolute; left: 18%; right: 18%; top: 50%; height: 3px; background: #c9ccd2; border-radius: 2px; }
.mw-le-addcontent-block-thumb--columns { background:
    linear-gradient(#cbd0e6,#cbd0e6) 14px 12px/26% 22px no-repeat,
    linear-gradient(#cbd0e6,#cbd0e6) center 12px/26% 22px no-repeat,
    linear-gradient(#cbd0e6,#cbd0e6) right 14px top 12px/26% 22px no-repeat, var(--ac-surface); }
.mw-le-addcontent-block-thumb--form { background:
    linear-gradient(#dfe1e5,#dfe1e5) 12px 14px/70% 6px no-repeat,
    linear-gradient(#dfe1e5,#dfe1e5) 12px 28px/50% 6px no-repeat, var(--ac-surface); }

.mw-le-addcontent-allblocks { text-align: right; margin-top: 14px; }
.mw-le-addcontent-link { background: none; border: 0; color: #4b62d6; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0; }
.mw-le-addcontent-link:hover { text-decoration: underline; }

.mw-le-addcontent-sep { border-top: 1px solid var(--ac-hairline); margin: 20px 0 14px; position: relative; }
.mw-le-addcontent-sep span {
    font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #a7a79f;
    position: relative; top: 14px;
}
.mw-le-addcontent-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 20px; }
.mw-le-addcontent-pill {
    display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px;
    border: 1px solid var(--ac-hairline); border-radius: 999px; background: #fff; cursor: pointer;
    font-size: 13px; font-weight: 600; color: var(--ac-ink);
}
.mw-le-addcontent-pill:hover { background: var(--ac-surface); }
.mw-le-addcontent-pill-dot { width: 14px; height: 14px; border-radius: 50%; background: var(--ac-surface); }

/* content-type mode (2a) */
.mw-le-addcontent-detail { display: flex; flex-direction: column; height: 100%; }
.mw-le-addcontent-detail-title { font-size: 18px; font-weight: 700; margin: 0 0 6px; }
.mw-le-addcontent-detail-desc { font-size: 14px; color: var(--ac-muted); line-height: 1.5; margin: 0 0 18px; max-width: 46ch; }
.mw-le-addcontent-preview {
    flex: 1 1 auto; border: 1px solid var(--ac-hairline); border-radius: 12px; background: #fbfbfa;
    padding: 20px; display: flex; flex-direction: column; gap: 12px; min-height: 180px; margin-bottom: 18px;
}
.mw-le-addcontent-preview .sk { display: block; border-radius: 6px; background: #e4e4e0; }
.mw-le-addcontent-preview .sk-title { height: 14px; width: 55%; background: #d3d3ce; }
.mw-le-addcontent-preview .sk-meta { height: 9px; width: 28%; }
.mw-le-addcontent-preview .sk-hero { height: 96px; width: 100%; background: linear-gradient(135deg,#c7cef0,#d7c9ea); }
.mw-le-addcontent-preview .sk-line { height: 9px; width: 80%; }
.mw-le-addcontent-preview .sk-line.sk-short { width: 55%; }
.mw-le-addcontent-detail-foot { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
.mw-le-addcontent-detail-hint { font-size: 13px; color: var(--ac-muted); }
.mw-le-addcontent-create {
    min-height: 42px; padding: 0 20px; border: 0; border-radius: 10px;
    background: var(--ac-ink); color: #fff; font-size: 14px; font-weight: 600; cursor: pointer;
    text-transform: capitalize;
}
.mw-le-addcontent-create:hover { background: #0f1722; }
.mw-le-addcontent-create[disabled], .mw-le-addcontent-draft[disabled] { opacity: .6; cursor: default; }

/* inline quick-create (2a) */
.mw-le-addcontent-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.mw-le-addcontent-field-label { font-size: 12px; font-weight: 600; color: var(--ac-ink); }
/* Inline Page create: compact Start-from cards + a horizontal main-menu toggle row. */
.mw-le-ac-starts .mw-le-cp-start-thumb { height: 40px; }
.mw-le-ac-menu-row { flex-direction: row; align-items: center; justify-content: space-between; gap: 12px; }
.mw-le-ac-menu-row .mw-le-addcontent-field-label { font-size: 13px; }
/* Inline Category create: mw.tree "Add to" picker + optional description. */
.mw-le-cat-tree {
    border: 1px solid var(--ac-hairline); border-radius: 10px; padding: 8px 10px;
    max-height: 200px; overflow: auto; background: #fff;
}
.mw-le-cat-tree:empty::before { content: 'Loading…'; color: var(--ac-muted, #77776f); font-size: 13px; }
.mw-le-ac-textarea { min-height: 62px; resize: vertical; line-height: 1.45; font-family: inherit; }
.mw-le-addcontent-input {
    width: 100%; min-height: 42px; padding: 9px 12px;
    border: 1px solid var(--ac-hairline); border-radius: 10px; font-size: 14px; background: #fff; color: var(--ac-ink);
}
.mw-le-addcontent-input:focus { outline: none; border-color: #b9c2ff; box-shadow: 0 0 0 3px rgba(90, 110, 240, .15); }
.mw-le-addcontent-create-error { color: #c02a2a; font-size: 12px; margin: -6px 0 12px; }
.mw-le-addcontent-create-actions { display: inline-flex; align-items: center; gap: 8px; }
.mw-le-addcontent-draft {
    min-height: 42px; padding: 0 16px; border: 1px solid var(--ac-hairline); border-radius: 10px;
    background: #fff; color: var(--ac-ink); font-size: 14px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 5px;
}
.mw-le-addcontent-draft::before { content: '✎'; font-size: 12px; color: var(--ac-muted); }
.mw-le-addcontent-draft:hover { background: var(--ac-surface); }

@media (max-width: 720px) {
    .mw-le-addcontent-row { flex-direction: column; }
    .mw-le-addcontent-rail { flex-basis: auto; border-right: 0; border-bottom: 1px solid var(--ac-hairline); }
    .mw-le-addcontent-blocks { grid-template-columns: repeat(2, 1fr); }
}

/* ── create-page skin ───────────────────────────────────────────────────── */
.mw-le-layouts-dialog.mw-le-dialog--createpage {
    inset: auto !important;
    left: 50% !important; top: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: min(760px, 96vw) !important;
    max-width: 760px !important;
    height: auto !important;
    max-height: 92vh !important;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(24, 36, 51, .28);
}
.mw-le-cp {
    --ac-ink: #182433; --ac-muted: #77776f; --ac-hairline: #e6e6e2;
    --ac-surface: #f4f4f2; --ac-accent: #4f63e8;
    display: flex; min-height: 420px; max-height: 92vh; color: var(--ac-ink); text-align: left;
}

/* ── edit-content (Page settings) skin ─────────────────────────────────────── */
.mw-le-layouts-dialog.mw-le-dialog--editcontent {
    inset: auto !important;
    left: 50% !important; top: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: min(680px, 96vw) !important;
    max-width: 680px !important;
    height: auto !important;
    max-height: 92vh !important;
    background: #fff !important;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(24, 36, 51, .28);
}
.mw-le-ec {
    --ac-ink: #182433; --ac-muted: #77776f; --ac-hairline: #ececE8;
    --ac-surface: #f6f6f4; --ac-accent: #4f63e8; --ac-danger: #c02a2a;
    color: var(--ac-ink); text-align: left; padding: 22px 26px 18px;
    max-height: 92vh; overflow-y: auto; display: flex; flex-direction: column;
}
.mw-le-ec-head { display: flex; align-items: center; gap: 9px; margin-bottom: 14px; }
.mw-le-ec-badge {
    width: 26px; height: 26px; border-radius: 7px; background: #eef0fb; color: #4f63e8;
    font-size: 10.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto;
}
.mw-le-ec-badge--icon svg { width: 16px; height: 16px; display: block; }
html.dark .mw-le-ec-badge { background: #2a2e34; color: #aab4ff; }
.mw-le-ec-advanced {
    display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%;
    margin-top: 16px; padding: 12px 14px; border: 1px solid var(--ac-hairline); border-radius: 12px;
    background: #fff; cursor: pointer; font: inherit; text-align: left;
}
.mw-le-ec-advanced:hover { background: #fafafa; border-color: #cfd2d8; }
.mw-le-ec-advanced > span { display: flex; flex-direction: column; font-size: 13.5px; font-weight: 600; color: var(--ac-ink); }
.mw-le-ec-advanced small { font-weight: 400; font-size: 11.5px; color: var(--ac-muted); margin-top: 1px; }
.mw-le-ec-advanced svg { color: var(--ac-muted); flex: 0 0 auto; }
html.dark .mw-le-ec-advanced { background: #22262c; }
.mw-le-ec-head-label { font-size: 13.5px; font-weight: 600; }
.mw-le-ec-status { display: inline-flex; align-items: center; gap: 5px; font-size: 12.5px; color: var(--ac-muted); margin-left: 2px; }
.mw-le-ec-status::before { content: '·'; margin-right: 3px; color: #c9ccd2; }
.mw-le-ec-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #2fb46b; }
.mw-le-ec-status.is-draft .mw-le-ec-status-dot { background: #c9a23b; }
.mw-le-ec-loading { padding: 40px 0; text-align: center; color: var(--ac-muted); }
.mw-le-ec-title {
    width: 100%; border: 0; outline: none; background: transparent; color: var(--ac-ink);
    font-size: 26px; font-weight: 700; letter-spacing: -.01em; padding: 2px 0 6px; margin: 0;
}
.mw-le-ec-title::placeholder { color: #c3c6cc; }
.mw-le-ec-url { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--ac-muted); padding-bottom: 16px; border-bottom: 1px solid var(--ac-hairline); flex-wrap: wrap; }
.mw-le-ec-url-host { color: var(--ac-muted); }
.mw-le-ec-url-slug { color: var(--ac-ink); font-weight: 500; }
.mw-le-ec-url-input { border: 1px solid var(--ac-hairline); border-radius: 7px; padding: 4px 8px; font: inherit; font-size: 13px; color: var(--ac-ink); min-width: 140px; }
.mw-le-ec-link { border: 0; background: transparent; color: var(--ac-accent); cursor: pointer; font: inherit; font-size: 13px; font-weight: 500; padding: 0; }
.mw-le-ec-open { margin-left: auto; border: 0; background: transparent; color: var(--ac-ink); cursor: pointer; font: inherit; font-size: 13px; font-weight: 500; }
.mw-le-ec-open:hover { color: var(--ac-accent); }
.mw-le-ec-row { display: flex; align-items: center; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--ac-hairline); }
.mw-le-ec-row--top { align-items: flex-start; }
.mw-le-ec-row-main { flex: 1 1 auto; min-width: 0; }
.mw-le-ec-row-title { font-size: 14px; font-weight: 600; }
.mw-le-ec-row-sub { font-size: 12.5px; color: var(--ac-muted); margin-top: 2px; overflow: hidden; text-overflow: ellipsis; }
.mw-le-ec-muted { color: #a7abb3; }
.mw-le-ec-thumb {
    flex: 0 0 auto; width: 54px; height: 40px; border-radius: 8px; overflow: hidden; background: var(--ac-surface);
    border: 1px solid var(--ac-hairline); display: inline-flex; align-items: center; justify-content: center;
}
.mw-le-ec-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.mw-le-ec-thumb--design { background: linear-gradient(135deg, #eef0fb 0%, #e3e6f6 100%); position: relative; }
.mw-le-ec-thumb--design::after { content: ''; position: absolute; left: 8px; right: 8px; top: 9px; height: 5px; border-radius: 3px; background: #c7cdf0; box-shadow: 0 9px 0 #d7dbf3; }
.mw-le-ec-thumb--empty { background: repeating-linear-gradient(45deg, #f2f2ef, #f2f2ef 6px, #ededea 6px, #ededea 12px); }
.mw-le-ec-btn {
    flex: 0 0 auto; border: 1px solid var(--ac-hairline); border-radius: 9px; background: #fff; color: var(--ac-ink);
    cursor: pointer; font: inherit; font-size: 13px; font-weight: 500; padding: 7px 14px;
}
.mw-le-ec-btn:hover { border-color: #cfd2d8; background: #fafafa; }
.mw-le-ec-btn.is-on { border-color: var(--ac-ink); background: var(--ac-ink); color: #fff; }
/* inline expand panel (Change design / Move) — a clearly bounded sub-card so
   it never reads as ambiguous against the rows (e.g. Cover's Replace) below. */
.mw-le-ec-expand { display: flex; gap: 12px; margin: 2px 0 14px; padding: 14px; background: var(--ac-surface); border-radius: 12px; border: 1px solid var(--ac-hairline); }
.mw-le-ec-field { flex: 1 1 0; display: flex; flex-direction: column; gap: 5px; min-width: 0; }
.mw-le-ec-field-label { font-size: 11.5px; font-weight: 600; color: var(--ac-muted); }
.mw-le-ec-select {
    width: 100%; min-height: 38px; padding: 8px 10px; border: 1px solid var(--ac-hairline);
    border-radius: 9px; font: inherit; font-size: 13px; background: #fff; color: var(--ac-ink); cursor: pointer;
}
.mw-le-ec-select:focus { outline: none; border-color: #b9c2ff; box-shadow: 0 0 0 3px rgba(90, 110, 240, .15); }
html.dark .mw-le-ec-select { background: #22262c; color: #e8eaed; border-color: #2c3138; }
.mw-le-ec-expand--col { flex-direction: column; gap: 8px; }
.mw-le-ec-field-head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; }
.mw-le-ec-field-val { font-size: 12px; color: var(--ac-ink); font-weight: 500; max-width: 60%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.mw-le-ec-toplevel {
    align-self: flex-start; border: 1px solid var(--ac-hairline); border-radius: 8px; background: #fff;
    color: var(--ac-ink); cursor: pointer; font: inherit; font-size: 12.5px; font-weight: 500; padding: 6px 12px;
}
.mw-le-ec-toplevel.is-on { border-color: var(--ac-ink); box-shadow: inset 0 0 0 1px var(--ac-ink); }
html.dark .mw-le-ec-toplevel { background: #22262c; }
.mw-le-ec-tree { max-height: 220px; overflow: auto; border: 1px solid var(--ac-hairline); border-radius: 10px; padding: 6px 8px; background: #fff; }
html.dark .mw-le-ec-tree { background: #22262c; border-color: #2c3138; }
.mw-le-ec-seg { flex: 0 0 auto; display: inline-flex; background: var(--ac-surface); border-radius: 9px; padding: 3px; }
.mw-le-ec-seg button {
    border: 0; background: transparent; color: var(--ac-muted); cursor: pointer; font: inherit; font-size: 12.5px;
    font-weight: 500; padding: 6px 12px; border-radius: 7px;
}
.mw-le-ec-seg button.active { background: #fff; color: var(--ac-ink); box-shadow: 0 1px 3px rgba(24, 36, 51, .12); }
.mw-le-ec-seg button.active:first-child { color: #1c7a44; }
.mw-le-ec-seo { width: 100%; border: 1px solid var(--ac-hairline); border-radius: 9px; padding: 8px 10px; font: inherit; font-size: 13px; resize: vertical; margin-top: 4px; }
.mw-le-ec-error { color: var(--ac-danger); font-size: 12.5px; margin: 10px 0 0; }
.mw-le-ec-foot { display: flex; align-items: center; gap: 12px; padding-top: 16px; margin-top: 4px; }
.mw-le-ec-foot-spacer { flex: 1 1 auto; }
.mw-le-ec-delete { border: 0; background: transparent; color: var(--ac-danger); cursor: pointer; font: inherit; font-size: 13px; font-weight: 500; padding: 0; }
.mw-le-ec-dirty { font-size: 12.5px; color: var(--ac-muted); }
.mw-le-ec-cancel { border: 0; background: transparent; color: var(--ac-ink); cursor: pointer; font: inherit; font-size: 13.5px; font-weight: 500; padding: 8px 12px; }
.mw-le-ec-save {
    border: 0; border-radius: 10px; background: var(--ac-ink); color: #fff; cursor: pointer; font: inherit;
    font-size: 13.5px; font-weight: 600; padding: 9px 22px;
}
.mw-le-ec-save:hover { background: #0f1722; }
.mw-le-ec-save:disabled { opacity: .5; cursor: default; }
html.dark .mw-le-dialog--editcontent { background: #1b1e22; }
html.dark .mw-le-ec { --ac-ink: #e8eaed; --ac-hairline: #2c3138; --ac-surface: #22262c; color: #e8eaed; }
html.dark .mw-le-ec-btn, html.dark .mw-le-ec-seg button.active { background: #22262c; }
.mw-le-cp-form { flex: 1 1 auto; padding: 26px 28px; overflow-y: auto; min-width: 0; }
.mw-le-cp-preview {
    flex: 0 0 260px; max-width: 260px; background: #fbfbfa;
    border-left: 1px solid var(--ac-hairline); padding: 22px; display: flex; flex-direction: column;
}

/* head */
.mw-le-cp-head { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.mw-le-cp-badge {
    width: 28px; height: 28px; border-radius: 7px; background: #e6ecff; color: #4f63e8;
    display: inline-flex; align-items: center; justify-content: center;
}
.mw-le-cp-badge svg { display: block; }
.mw-le-cp-head-title { font-size: 15px; font-weight: 600; color: var(--ac-muted); }

/* title + slug */
.mw-le-cp-title-input {
    width: 100%; border: 1px solid var(--ac-hairline); background: #f6f6f4;
    border-radius: 12px; padding: 13px 16px; margin-bottom: 12px;
    font-size: 23px; font-weight: 700; color: var(--ac-ink);
    transition: border-color .12s ease, box-shadow .12s ease, background .12s ease;
}
.mw-le-cp-title-input::placeholder { color: #b3b3ac; font-weight: 600; }
.mw-le-cp-title-input:focus { outline: none; background: #fff; border-color: var(--ac-accent); box-shadow: 0 0 0 3px rgba(79, 99, 232, .14); }
.mw-le-cp-slug { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--ac-muted); margin-bottom: 22px; }
.mw-le-cp-slug-host { color: #a7a79f; }
.mw-le-cp-slug-value { color: var(--ac-ink); border-bottom: 1px dashed var(--ac-hairline); }
.mw-le-cp-slug-input { border: 1px solid var(--ac-hairline); border-radius: 6px; padding: 4px 9px; font-size: 13px; background: #f6f6f4; }
.mw-le-cp-slug-input:focus { outline: none; background: #fff; border-color: var(--ac-accent); box-shadow: 0 0 0 3px rgba(79, 99, 232, .12); }
.mw-le-cp-link { background: none; border: 0; padding: 0; color: var(--ac-accent); font-size: 13px; font-weight: 600; cursor: pointer; }
.mw-le-cp-link:hover { text-decoration: underline; }

/* fields */
.mw-le-cp-field { margin-bottom: 20px; }
.mw-le-cp-field-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.mw-le-cp-label { font-size: 13px; font-weight: 600; color: var(--ac-ink); }
.mw-le-cp-muted { color: var(--ac-muted); font-weight: 400; }

/* start-from cards */
.mw-le-cp-starts { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.mw-le-cp-start {
    display: flex; flex-direction: column; gap: 8px; padding: 8px; cursor: pointer;
    border: 1px solid var(--ac-hairline); border-radius: 10px; background: #fff;
    box-shadow: 0 1px 2px rgba(24, 36, 51, .05); transition: border-color .12s, box-shadow .12s;
}
.mw-le-cp-start:hover { border-color: #cfd6ff; box-shadow: 0 3px 10px rgba(24, 36, 51, .1); }
.mw-le-cp-start.is-on { border-color: var(--ac-ink); box-shadow: 0 0 0 1px var(--ac-ink), 0 3px 10px rgba(24, 36, 51, .12); }
.mw-le-cp-start-thumb { display: block; height: 46px; border-radius: 6px; background: var(--ac-surface); overflow: hidden; position: relative; }
.mw-le-cp-start-label { font-size: 12px; font-weight: 600; text-align: center; }
.mw-le-cp-start-thumb--blank { background: var(--ac-surface); }
.mw-le-cp-start-thumb--blank::after { content: ''; position: absolute; left: 10px; right: 10px; top: 12px; height: 4px; background: #dfe1e5; border-radius: 2px; box-shadow: 0 8px 0 #e8e9ec, 0 16px 0 #eceded; }
.mw-le-cp-start-thumb--hero::before { content: ''; position: absolute; left: 8px; right: 8px; top: 7px; height: 20px; background: linear-gradient(135deg,#c7cef0,#d7c9ea); border-radius: 4px; }
.mw-le-cp-start-thumb--hero::after { content: ''; position: absolute; left: 8px; right: 8px; bottom: 7px; height: 4px; background: #dfe1e5; border-radius: 2px; box-shadow: 0 7px 0 #e8e9ec; }
.mw-le-cp-start-thumb--article::after { content: ''; position: absolute; left: 12px; right: 12px; top: 9px; height: 3px; background: #cfd1d6; border-radius: 2px; box-shadow: 0 8px 0 #dfe1e5, 0 15px 0 #dfe1e5, 0 22px 0 #e8e9ec; }
.mw-le-cp-start-thumb--landing::before { content: ''; position: absolute; left: 8px; right: 8px; top: 6px; height: 14px; background: linear-gradient(135deg,#c7cef0,#d7c9ea); border-radius: 3px; }
.mw-le-cp-start-thumb--landing::after { content: ''; position: absolute; left: 8px; right: 8px; bottom: 6px; height: 16px; background: repeating-linear-gradient(90deg,#dfe1e5 0 26%, transparent 26% 33%); border-radius: 3px; }

/* content textarea */
.mw-le-cp-textarea {
    width: 100%; border: 1px solid var(--ac-hairline); border-radius: 10px; padding: 11px 13px;
    font-size: 13px; color: var(--ac-ink); resize: vertical; min-height: 68px; background: #f6f6f4;
    transition: border-color .12s ease, box-shadow .12s ease, background .12s ease;
}
.mw-le-cp-textarea:focus { outline: none; background: #fff; border-color: var(--ac-accent); box-shadow: 0 0 0 3px rgba(79, 99, 232, .12); }
.mw-le-cp-textarea::placeholder { color: #b3b3ac; }

/* option rows */
.mw-le-cp-rows { border-top: 1px solid var(--ac-hairline); margin-top: 4px; }
.mw-le-cp-row {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid var(--ac-hairline); margin: 0; cursor: pointer; font-size: 14px;
}
.mw-le-cp-row-main { display: inline-flex; align-items: center; gap: 10px; }
.mw-le-cp-check { width: 16px; height: 16px; accent-color: var(--ac-accent); }
.mw-le-cp-row-value { font-size: 13px; color: var(--ac-muted); }
.mw-le-cp-row--dropdown { cursor: default; }
.mw-le-cp-parentpick { position: relative; }
.mw-le-cp-row-value--btn { background: none; border: 0; padding: 0; cursor: pointer; font-weight: 600; color: var(--ac-ink); }
.mw-le-cp-parent-menu {
    /* Open UPWARD — the Parent row sits low in the form, so anchoring the menu
       above the trigger keeps it inside the dialog instead of spilling past the
       bottom edge. */
    position: absolute; bottom: calc(100% + 6px); right: 0; z-index: 30; width: 260px; max-width: 80vw;
    background: #fff; border: 1px solid var(--ac-hairline); border-radius: 10px; padding: 8px;
    box-shadow: 0 -12px 30px rgba(24, 36, 51, .18);
}
.mw-le-cp-parent-search { width: 100%; min-height: 34px; padding: 6px 10px; border: 1px solid var(--ac-hairline); border-radius: 8px; font-size: 13px; background: #f6f6f4; }
.mw-le-cp-parent-search:focus { outline: none; background: #fff; border-color: var(--ac-accent); box-shadow: 0 0 0 3px rgba(79, 99, 232, .12); }
.mw-le-cp-parent-list { max-height: 200px; overflow-y: auto; margin-top: 6px; display: flex; flex-direction: column; }
.mw-le-cp-parent-item {
    flex: 0 0 auto; text-align: left; padding: 7px 8px; border: 0; background: none; border-radius: 6px;
    font-size: 13px; color: var(--ac-ink); cursor: pointer; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.mw-le-cp-parent-item:hover { background: var(--ac-surface); }
.mw-le-cp-parent-empty { padding: 8px; font-size: 12px; color: var(--ac-muted); }
.mw-le-cp-toggle { width: 38px; height: 22px; border-radius: 999px; background: #d3d3ce; position: relative; transition: background .15s; flex: none; cursor: pointer; }
.mw-le-cp-toggle.is-on { background: var(--ac-accent); }
.mw-le-cp-toggle-knob { position: absolute; top: 2px; left: 2px; width: 18px; height: 18px; border-radius: 50%; background: #fff; transition: transform .15s; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
.mw-le-cp-toggle.is-on .mw-le-cp-toggle-knob { transform: translateX(16px); }

/* more settings */
.mw-le-cp-more { background: none; border: 0; padding: 14px 0 0; color: var(--ac-muted); font-size: 13px; cursor: pointer; }
.mw-le-cp-more:hover { color: var(--ac-ink); }
.mw-le-cp-more-body { padding-top: 8px; }

/* preview pane */
.mw-le-cp-preview-head { font-size: 13px; font-weight: 600; color: var(--ac-muted); margin-bottom: 12px; }
.mw-le-cp-preview-frame { background: #fff; border: 1px solid var(--ac-hairline); border-radius: 12px; padding: 0; flex: 0 1 auto; overflow: hidden; margin-bottom: 12px; position: relative; min-height: 140px; max-height: 380px; }
.mw-le-cp-preview-frame .mw-le-cp-skeleton { padding: 14px; }
/* fit the FULL width of the layout (no side crop); show the top of the page */
.mw-le-cp-preview-img { display: block; width: 100%; height: auto; }
.mw-le-cp-preview-iframe { display: block; width: 100%; height: 340px; border: 0; background: #fff; }
.mw-le-cp-start-thumb img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }
.mw-le-cp-skeleton { display: flex; flex-direction: column; gap: 10px; }
.mw-le-cp-skeleton .sk { display: block; border-radius: 5px; background: #e4e4e0; }
.mw-le-cp-skeleton .sk-title { height: 12px; width: 40%; background: #d0d0ca; }
.mw-le-cp-skeleton .sk-hero { height: 70px; width: 100%; background: linear-gradient(135deg,#c7cef0,#d7c9ea); }
.mw-le-cp-skeleton .sk-line { height: 8px; width: 90%; }
.mw-le-cp-skeleton .sk-line.sk-short { width: 60%; }
.mw-le-cp-skeleton .sk-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 4px; }
.mw-le-cp-skeleton .sk-grid span { height: 48px; border-radius: 6px; background: #ececea; }
/* per start-from tweaks */
.mw-le-cp-skeleton--blank .sk-hero, .mw-le-cp-skeleton--blank .sk-grid { display: none; }
.mw-le-cp-skeleton--article .sk-hero, .mw-le-cp-skeleton--article .sk-grid { display: none; }
.mw-le-cp-skeleton--article .sk-line { width: 95%; }
.mw-le-cp-skeleton--hero .sk-grid span:nth-child(n) { }
.mw-le-cp-preview-desc { font-size: 12px; color: var(--ac-muted); line-height: 1.5; margin: 0 0 14px; }
.mw-le-cp-error { color: #c02a2a; font-size: 12px; margin: -6px 0 10px; }
.mw-le-cp-create {
    width: 100%; min-height: 44px; border: 0; border-radius: 10px; background: var(--ac-ink); color: #fff;
    font-size: 14px; font-weight: 600; cursor: pointer; margin-bottom: 8px;
}
.mw-le-cp-create:hover { background: #0f1722; }
.mw-le-cp-create[disabled], .mw-le-cp-draft-link[disabled] { opacity: .6; cursor: default; }
.mw-le-cp-draft-link { width: 100%; background: none; border: 0; color: var(--ac-ink); font-size: 13px; font-weight: 600; cursor: pointer; padding: 6px; }
.mw-le-cp-draft-link:hover { text-decoration: underline; }

/* new-item image + price (shared: create-page + add-content inline) */
.mw-le-ni-image-row { display: flex; align-items: center; gap: 10px; }
.mw-le-ni-add {
    display: inline-flex; align-items: center; gap: 7px; min-height: 40px; padding: 0 14px;
    border: 1px dashed #cfcfca; border-radius: 10px; background: transparent; color: #77776f;
    font-size: 13px; font-weight: 600; cursor: pointer;
}
.mw-le-ni-add:hover { border-color: #4f63e8; color: #4f63e8; background: rgba(79, 99, 232, .04); }
.mw-le-ni-thumb { width: 46px; height: 46px; border-radius: 8px; overflow: hidden; background: #f4f4f2; border: 1px solid #e6e6e2; flex: none; }
.mw-le-ni-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.mw-le-ni-remove { color: #d64545 !important; }
.mw-le-ni-prices { display: flex; gap: 12px; margin-bottom: 16px; }
.mw-le-ni-price-col { display: flex; flex-direction: column; gap: 6px; flex: 1 1 0; margin: 0; }

@media (max-width: 720px) {
    .mw-le-cp { flex-direction: column; }
    .mw-le-cp-preview { flex-basis: auto; max-width: none; border-left: 0; border-top: 1px solid var(--ac-hairline); }
    .mw-le-cp-starts { grid-template-columns: repeat(2, 1fr); }
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

        // ── add-content skin ────────────────────────────────────────────────
        // Open THIS modal as the "Add content" dialog (the AddContentButton
        // toolbar action routes here instead of the old Filament modal).
        openAddContentPickerSkin() {
            this.pickerSkin = 'add-content';
            this.addContentSelectedType = 'block';
            this.filterKeyword = '';
            this.niImage = '';
            this.niPrice = '';
            this.niSpecialPrice = '';
            this.showModal = true;
            setTimeout(() => {
                const el = document.querySelector('.mw-le-addcontent-search input');
                if (el) { el.focus(); }
            }, 120);
        },
        addContentTypeObj(key) {
            return this.addContentTypes.find((t) => t.key === (key || this.addContentSelectedType));
        },

        // ── shared: new-item image (attached as media after create) + price ──
        niPickImage() {
            try {
                mw.filePickerDialog({ pickerOptions: { multiple: false } }, (url) => {
                    if (Array.isArray(url)) { url = url[0]; }
                    if (url) { this.niImage = url; }
                });
            } catch (e) { /* picker unavailable */ }
        },
        niRemoveImage() { this.niImage = ''; },

        // ── edit-content (Page settings) ──────────────────────────────────────
        _qsHttp(method, path, body) {
            const base = mw.settings.site_url;
            const token = (document.querySelector('meta[name="csrf-token"]') || {}).content || (mw.settings && mw.settings.csrf) || '';
            const headers = { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token };
            if (body) { headers['Content-Type'] = 'application/json'; }
            return fetch(base + path, { method, headers, credentials: 'include', body: body ? JSON.stringify(body) : undefined });
        },
        openEditContentSkin(id) {
            this.pickerSkin = 'edit-content';
            this.showModal = true;
            this.ecId = id || null;
            this.ecError = '';
            this.ecDirty = {};
            this.ecSeoEditing = false;
            this.ecSlugEditing = false;
            this.ecLoad();
        },
        async ecLoad() {
            if (!this.ecId) { return; }
            this.ecLoading = true;
            try {
                const r = await this._qsHttp('GET', 'api/live-edit/content-settings?id=' + encodeURIComponent(this.ecId));
                const d = await r.json();
                if (!r.ok || !d.success) { this.ecError = this.$lang('Could not load the page settings.'); return; }
                this.ecTitle = d.title || '';
                this.ecSlug = d.url || '';
                this.ecContentType = d.content_type || 'page';
                this.ecPublished = !!d.is_active;
                this.ecIsHome = !!d.is_home;
                this.ecDesign = d.design || { template: '', layout: '' };
                this.ecLiveDate = d.live_date || '';
                this.ecBreadcrumb = d.breadcrumb || [];
                this.ecInMenu = !!d.in_menu;
                this.ecMenuPosition = d.menu_position || null;
                this.ecMenuLabel = d.menu_label || '';
                this.ecCover = d.cover || null;
                this.ecMetaDescription = d.meta_description || '';
                this.ecOpenUrl = d.open_url || '';
                this.ecEditUrl = d.edit_url || '';
                this.ecTemplateKey = d.template_key || '';
                this.ecLayoutKey = d.layout_key || '';
                this.ecTemplates = d.templates || [];
                this.ecLayouts = d.layouts || [];
                this.ecParentId = (d.breadcrumb && d.breadcrumb.length) ? d.breadcrumb[d.breadcrumb.length - 1].id : 0;
                this.ecDesignOpen = false;
                this.ecMoveOpen = false;
                this.ecDirty = {};
            } catch (e) {
                this.ecError = this.$lang('Could not load the page settings.');
            } finally {
                this.ecLoading = false;
            }
        },
        ecTouch(key) { this.ecDirty = Object.assign({}, this.ecDirty, { [key]: true }); },
        async ecOnTemplateChange() {
            this.ecTouch('design');
            try {
                const r = await this._qsHttp('GET', 'api/live-edit/template-layouts?template=' + encodeURIComponent(this.ecTemplateKey));
                const d = await r.json();
                this.ecLayouts = (d && d.layouts) || [];
                // keep current layout if still available, else pick the first
                if (!this.ecLayouts.some((l) => l.file === this.ecLayoutKey)) {
                    this.ecLayoutKey = this.ecLayouts.length ? this.ecLayouts[0].file : '';
                }
            } catch (e) { /* keep existing */ }
        },
        ecToggleMove() {
            this.ecMoveOpen = !this.ecMoveOpen;
            if (this.ecMoveOpen) { this.ecMountParentTree(); }
        },
        // Parent picker uses the same mw.widget.tree as the category picker so a
        // very long page title scrolls inside a fixed box instead of blowing a
        // native <select> off-screen. Only page nodes set the parent.
        ecMountParentTree() {
            var self = this;
            this.$nextTick(function () {
                setTimeout(function () {
                    var el = document.querySelector('#mw-le-ec-parent-tree');
                    if (!el || !(window.mw && mw.widget && mw.widget.tree)) { return; }
                    el.innerHTML = '';
                    var opts = { options: { selectable: true, singleSelect: true } };
                    if (self.ecParentId) { opts.options.selectedData = [{ id: String(self.ecParentId), type: 'page' }]; }
                    try {
                        mw.widget.tree('#mw-le-ec-parent-tree', opts).then(function (tree) {
                            tree.tree.on('selectionChange', function () {
                                var items = tree.tree.getSelected() || [];
                                var pageNode = items.filter(function (it) { return it.type === 'page'; })[0];
                                if (pageNode) { self.ecPickParent(parseInt(pageNode.id, 10), pageNode.title || ''); }
                            });
                        });
                    } catch (e) { /* tree unavailable */ }
                }, 60);
            });
        },
        ecPickParent(id, label) {
            if (parseInt(id, 10) === this.ecId) { return; } // can't parent to self
            this.ecParentId = id || 0;
            this.ecParentLabel = label || '';
            this.ecTouch('parent');
        },
        ecSetPublished(v) { if (this.ecPublished !== v) { this.ecPublished = v; this.ecTouch('is_active'); } },
        ecToggleMenu() { this.ecInMenu = !this.ecInMenu; this.ecTouch('menu'); },
        ecReplaceCover() {
            try {
                mw.filePickerDialog({ pickerOptions: { multiple: false } }, (url) => {
                    if (Array.isArray(url)) { url = url[0]; }
                    if (url) {
                        this.ecCover = { url: url, name: String(url).split('/').pop() };
                        this.ecTouch('cover');
                    }
                });
            } catch (e) { /* picker unavailable */ }
        },
        ecOpenPage() {
            const base = mw.settings.site_url;
            let link = this.ecOpenUrl || this.ecSlug;
            if (link && !/^https?:\/\//i.test(link)) { link = base + String(link).replace(/^\/+/, ''); }
            window.location.href = base + 'admin/live-edit?url=' + encodeURIComponent(link);
        },
        ecOpenAdvanced() {
            if (this.ecEditUrl) { window.open(this.ecEditUrl, '_blank'); }
        },
        async ecDelete() {
            if (!this.ecId) { return; }
            const label = (this.ecTypeLabel || 'page').toLowerCase();
            const ok = window.confirm(this.$lang('Delete this ' + label + '? This cannot be undone.'));
            if (!ok) { return; }
            try {
                await this._qsHttp('POST', 'api/save_content', { id: this.ecId, is_deleted: 1 });
                this.showModal = false;
                window.location.href = mw.settings.site_url + 'admin/live-edit';
            } catch (e) { this.ecError = this.$lang('Could not delete.'); }
        },
        async ecResolveMenuId() {
            if (this.ecMenuId) { return this.ecMenuId; }
            try {
                const r = await this._qsHttp('GET', 'api/module/menus');
                const d = await r.json();
                const items = (d && d.data) ? d.data : (Array.isArray(d) ? d : []);
                const menus = items.filter((m) => m && m.item_type === 'menu');
                const header = menus.find((m) => m.title === 'header_menu') || menus[0];
                this.ecMenuId = header ? header.id : null;
            } catch (e) { this.ecMenuId = null; }
            return this.ecMenuId;
        },
        async ecSave() {
            if (!this.ecId || this.ecSaving) { return; }
            this.ecSaving = true;
            this.ecError = '';
            try {
                // Core fields via the classic session-authed save.
                const payload = {
                    id: this.ecId,
                    title: (this.ecTitle || '').trim() || 'Untitled',
                    url: (this.ecSlug || '').trim(),
                    is_active: this.ecPublished ? 1 : 0,
                    is_deleted: 0,
                };
                if (this.ecDirty.design) {
                    payload.active_site_template = this.ecTemplateKey;
                    payload.layout_file = this.ecLayoutKey;
                }
                if (this.ecDirty.parent) { payload.parent = this.ecParentId || 0; }
                await this._qsHttp('POST', 'api/save_content', payload);

                // Menu add/remove — handled server-side so removal clears every
                // menu link to this content, not just the header menu.
                if (this.ecDirty.menu) {
                    await this._qsHttp('POST', 'api/live-edit/content-menu', { id: this.ecId, in_menu: this.ecInMenu ? 1 : 0 });
                }

                // Cover image attach.
                if (this.ecDirty.cover && this.ecCover && this.ecCover.url) {
                    await this._qsHttp('POST', 'api/save_media', { filename: this.ecCover.url, rel_type: 'content', rel_id: this.ecId, media_type: 'picture' });
                }

                this.ecDirty = {};
                // Refresh the canvas to reflect the change, then close.
                try { mw.app.canvas.reload(); } catch (e) {}
                this.showModal = false;
            } catch (e) {
                this.ecError = this.$lang('Could not save changes.');
            } finally {
                this.ecSaving = false;
            }
        },

        async niAttachImage(contentId) {
            if (!this.niImage || !contentId) { return; }
            try {
                const base = mw.settings.site_url;
                const token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
                await fetch(base + 'api/save_media', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
                    credentials: 'include',
                    body: JSON.stringify({ filename: this.niImage, rel_type: 'content', rel_id: contentId, media_type: 'picture' }),
                });
            } catch (e) { /* best-effort */ }
        },
        async niSavePrice(contentId) {
            if (!contentId) { return; }
            const price = (this.niPrice || '').toString().trim();
            const special = (this.niSpecialPrice || '').toString().trim();
            if (price === '' && special === '') { return; }
            try {
                const base = mw.settings.site_url;
                const token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
                await fetch(base + 'api/save_product_price', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
                    credentials: 'include',
                    body: JSON.stringify({ rel_id: contentId, price: price, special_price: special }),
                });
            } catch (e) { /* best-effort */ }
        },
        // Mount the same mw.widget.tree picker the admin category form uses:
        // a single-select tree of pages + categories, defaulting to the page
        // currently open in the Live Edit canvas. Selecting a page sets rel_id;
        // selecting a category sets parent_id (nest under it).
        catMountTree() {
            this.catRelId = 0;
            this.catParentId = 0;
            let curId = '';
            try {
                const d = mw.top().app.canvas.getLiveEditData();
                if (d && d.content && d.content.id) { curId = String(d.content.id); }
            } catch (e) { /* canvas not ready */ }
            if (curId) { this.catRelId = parseInt(curId, 10) || 0; }
            this.$nextTick(() => {
                setTimeout(async () => {
                    const el = document.querySelector('#mw-le-cat-tree');
                    if (!el || !(window.mw && mw.widget && mw.widget.tree)) { return; }
                    el.innerHTML = '';
                    const opts = { options: { selectable: true, singleSelect: true } };
                    if (curId) { opts.options.selectedData = [{ id: curId, type: 'page' }]; }
                    try {
                        const tree = await mw.widget.tree('#mw-le-cat-tree', opts);
                        this._catTree = tree;
                        tree.tree.on('selectionChange', () => {
                            const items = tree.tree.getSelected() || [];
                            let rel = 0, parent = 0;
                            items.forEach((it) => {
                                if (it.type === 'category') { parent = it.id; }
                                if (it.type === 'page') { rel = it.id; }
                            });
                            this.catParentId = parseInt(parent, 10) || 0;
                            this.catRelId = parseInt(rel, 10) || 0;
                        });
                    } catch (e) { /* tree unavailable */ }
                }, 60);
            });
        },
        addContentSelect(key) {
            this.addContentSelectedType = key;
            // Fresh new-item fields whenever a creatable content type is opened.
            if (key === 'page' || key === 'post' || key === 'product' || key === 'category') {
                this.addContentTitle = '';
                this.niImage = '';
                this.niPrice = '';
                this.niSpecialPrice = '';
                this.addContentError = '';
            }
            // Category creates inline: build the "Add to" options (current page +
            // existing categories) and reset its fields.
            if (key === 'category') {
                this.catDescription = '';
                this.catMountTree();
            }
            // Page now creates inline: seed the real Start-from cards + main-menu
            // id so the fields render with live data (no second-step dialog).
            if (key === 'page') {
                this.cpStartFrom = 'blank';
                this.cpAddToMenu = true;
                this.cpParentId = '';
                this.cpParentLabel = '';
                this.cpParentOpen = false;
                this.cpMenuId = null;
                this.cpResolveMainMenu();
                this.cpBuildStartFrom();
            }
        },
        // Drill the SAME modal from the add-content skin into the layouts grid,
        // optionally pre-filtered to a category (blocks map to categories).
        addContentOpenLayouts(category) {
            this.pickerSkin = 'layouts';
            this.filterCategory = category || '';
            this.filterKeyword = '';
            this.filterLayouts();
            this.$nextTick(() => { this.setupIframeObserver(); });
        },
        addContentBlockClick(block) {
            if (block.module) {
                this.addContentInsertModule(block.module);
            } else {
                this.addContentOpenLayouts(block.layoutCategory || '');
            }
        },
        // Insert a single module block at the bottom of the page content. Falls
        // back to the canvas content root when no explicit target was set (the
        // toolbar Add button opens the picker with no selected element).
        addContentInsertModule(moduleType) {
            let target = this.target;
            try {
                if ((!target || !target.ownerDocument) && mw.app.canvas && mw.app.canvas.getDocument) {
                    const doc = mw.app.canvas.getDocument();
                    target = doc.querySelector('.edit.main-content, .edit[field="content"], .edit');
                }
            } catch (e) { /* canvas not ready */ }
            if (!target) { return; }
            this.showModal = false;
            try {
                mw.app.registerChangedState(target);
                mw.app.editor.insertModule(moduleType, {}, 'bottom', target);
            } catch (e) {
                console.warn('mw add-content: insert module failed', e);
            }
        },
        // "Create <type>" — hand off to the existing create flow. Layout drills
        // into the layouts grid; content types open their create action via the
        // window-event bridge the old picker used (crosses the teleport boundary).
        addContentCreate(type) {
            if (!type) { return; }
            if (type.key === 'block' || type.key === 'layout') {
                this.addContentOpenLayouts(type.layoutCategory || '');
                return;
            }
            // Page/Post/Product create inline in the detail pane (no second step).
            if (type.quickCreate) {
                this.addContentSelect(type.key);
                return;
            }
            this.showModal = false;
            if (type.createAction) {
                window.dispatchEvent(new CustomEvent('liveEditOpenCreateContent', {
                    detail: { action: type.createAction },
                }));
            }
        },
        // Inline quick-create (mockup 2a): create the content with just a title
        // via POST api/content, then open it in Live Edit. draft => is_active 0.
        async addContentQuickCreate(type, draft) {
            if (!type || this.addContentCreating) { return; }
            // Category has its own endpoint (api/category) + navigation.
            if (type.key === 'category') { return this.catCreate(draft); }
            const title = (this.addContentTitle || '').trim() || ('Untitled ' + type.label.toLowerCase());
            this.addContentCreating = true;
            this.addContentError = '';
            try {
                const base = mw.settings.site_url;
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const token = (tokenEl && tokenEl.content) || (mw.settings && mw.settings.csrf) || '';
                // api/save_content = the classic session-authed (web+admin) create;
                // api/content needs the API-token guard (401 with a session cookie).
                const payload = { content_type: type.key, title: title, is_active: draft ? 0 : 1, is_deleted: 0 };
                // Page gets its real inline options: a clean editable page that
                // embeds the chosen Start-from layout, plus the main-menu link.
                if (type.key === 'page') {
                    payload.layout_file = 'clean.blade.php';
                    const startFrom = this.cpActiveStartFrom() || {};
                    if (startFrom.layout && startFrom.layout !== 'clean' && startFrom.key !== 'blank') {
                        payload.content = '<module type="layouts" template="' + startFrom.layout + '" />';
                    }
                    // Draft stays out of the nav; a published page joins the main
                    // menu (save_content needs an ARRAY of menu container ids).
                    if (!draft && this.cpAddToMenu && this.cpMenuId) {
                        payload.add_content_to_menu = [this.cpMenuId];
                    }
                    if (this.cpParentId) { payload.parent = this.cpParentId; }
                }
                const res = await fetch(base + 'api/save_content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload),
                });
                const raw = await res.text();
                let id = null;
                try {
                    const j = JSON.parse(raw);
                    id = (typeof j === 'number') ? j : (j.id || (j.data && j.data.id) || null);
                } catch (e) {
                    id = parseInt(raw, 10) || null;
                }
                if (!res.ok || !id) {
                    this.addContentError = 'Could not create ' + type.label.toLowerCase() + '.';
                    return;
                }

                await this.niAttachImage(id);
                if (type.key === 'product') { await this.niSavePrice(id); }

                // Resolve the new content's url (public show route) to open it in Live Edit.
                let url = '';
                try {
                    const g = await fetch(base + 'api/content/' + id, { credentials: 'include', headers: { Accept: 'application/json' } });
                    const gd = await g.json();
                    url = (gd && gd.data && gd.data.url) || (gd && gd.url) || '';
                } catch (e) { /* fall back to content_id */ }

                this.showModal = false;
                this.addContentTitle = '';
                const target = base + 'admin/live-edit' + (url
                    ? ('?url=' + encodeURIComponent(base + url))
                    : ('?content_id=' + id));
                window.location.href = target;
            } catch (e) {
                this.addContentError = 'Could not create ' + type.label.toLowerCase() + '.';
            } finally {
                this.addContentCreating = false;
            }
        },
        // Inline category create → POST api/category (session-authed admin
        // apiResource store). "Add to" maps to rel_id (a page) or parent_id (a
        // parent category). Draft = hidden/inactive. On success, reopen the
        // category's frontend page in Live Edit via the returned frontend_link.
        async catCreate(draft) {
            if (this.addContentCreating) { return; }
            const title = (this.addContentTitle || '').trim();
            if (!title) {
                this.addContentError = 'Give the category a title first.';
                return;
            }
            this.addContentCreating = true;
            this.addContentError = '';
            try {
                const base = mw.settings.site_url;
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const token = (tokenEl && tokenEl.content) || (mw.settings && mw.settings.csrf) || '';
                const payload = { title: title, is_active: draft ? 0 : 1, is_hidden: draft ? 1 : 0, is_deleted: 0 };
                const notes = (this.catDescription || '').trim();
                if (notes) { payload.description = notes; }
                // Tree single-select: a category parent wins (nest under it,
                // rel_id cleared); otherwise attach to the chosen page.
                if (this.catParentId) {
                    payload.parent_id = this.catParentId;
                    payload.rel_id = 0;
                } else {
                    payload.parent_id = 0;
                    payload.rel_id = this.catRelId || 0;
                }
                const res = await fetch(base + 'api/category', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload),
                });
                const raw = await res.text();
                let data = null;
                try { const j = JSON.parse(raw); data = j.data || j; } catch (e) { data = null; }
                const id = data && data.id;
                if (!res.ok || !id) {
                    this.addContentError = 'Could not create the category.';
                    return;
                }
                this.showModal = false;
                this.addContentTitle = '';
                this.catDescription = '';
                let link = (data && data.frontend_link) || '';
                if (link && !/^https?:\/\//i.test(link)) { link = base + link.replace(/^\/+/, ''); }
                window.location.href = base + 'admin/live-edit' + (link
                    ? ('?url=' + encodeURIComponent(link))
                    : ('?content_id=' + (data.rel_id || id)));
            } catch (e) {
                this.addContentError = 'Could not create the category.';
            } finally {
                this.addContentCreating = false;
            }
        },

        // ── create-page dialog ───────────────────────────────────────────────
        // Build the "Start from" cards from REAL page-level (Content category)
        // layouts — same data + screenshots the admin layout picker uses.
        cpBuildStartFrom() {
            const all = (this.layoutsList && Array.isArray(this.layoutsList.layouts)) ? this.layoutsList.layouts : [];
            const isContent = (l) => Array.isArray(l.categories) ? l.categories.includes('Content') : l.categories === 'Content';
            const content = all.filter(isContent);
            if (!content.length) { return; } // keep the static fallback
            const clean = content.find((l) => l.template === 'clean') || content[0];
            const rest = content.filter((l) => l !== clean).slice(0, 3);
            const picks = [clean].concat(rest).filter(Boolean);
            this.cpStartFromOptions = picks.map((l, i) => ({
                key: l.template,
                label: (i === 0 ? 'Blank' : l.name),
                layout: l.template,
                screenshot: l.screenshot || '',
                previewUrl: l.preview_url || '',
                description: l.description || l.name || '',
            }));
            this.cpStartFrom = this.cpStartFromOptions[0].key;
        },
        openCreatePageSkin() {
            this.pickerSkin = 'create-page';
            this.cpTitle = '';
            this.cpSlug = '';
            this.cpSlugEdited = false;
            this.cpSlugEditing = false;
            this.cpContent = '';
            this.cpStartFrom = 'blank';
            this.cpParentId = '';
            this.cpParentLabel = '';
            this.cpAddToMenu = true;
            this.cpMoreOpen = false;
            this.cpError = '';
            this.cpMenuId = null;
            this.niImage = '';
            this.cpResolveMainMenu();
            // Populate Start-from from real layouts (sets cpStartFrom to a real key).
            this.cpBuildStartFrom();
            this.showModal = true;
            setTimeout(() => {
                const el = document.querySelector('.mw-le-cp-title-input');
                if (el) { el.focus(); }
            }, 120);
        },
        // Resolve the site's main (header) menu id so "Add to main menu" adds the
        // new page to it. save_content adds to menu only when add_content_to_menu
        // is an ARRAY of menu container ids.
        async cpResolveMainMenu() {
            try {
                const base = mw.settings.site_url;
                const r = await fetch(base + 'api/module/menus', { credentials: 'include', headers: { Accept: 'application/json' } });
                const d = await r.json();
                const items = (d && d.data) ? d.data : (Array.isArray(d) ? d : []);
                const menus = items.filter((m) => m && m.item_type === 'menu');
                const header = menus.find((m) => m.title === 'header_menu') || menus[0];
                this.cpMenuId = header ? header.id : null;
            } catch (e) {
                this.cpMenuId = null;
            }
        },
        cpSlugify(text) {
            return (text || '').toString().toLowerCase().trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 120);
        },
        cpOnTitleInput() {
            if (!this.cpSlugEdited) {
                this.cpSlug = this.cpSlugify(this.cpTitle);
            }
        },
        cpEffectiveSlug() {
            return this.cpSlug || this.cpSlugify(this.cpTitle) || 'page-title';
        },
        cpActiveStartFrom() {
            return this.cpStartFromOptions.find((o) => o.key === this.cpStartFrom) || this.cpStartFromOptions[0];
        },
        cpSetStartFrom(key) {
            this.cpStartFrom = key;
        },
        // "Draft from title" — a light client-side starter paragraph (AI draft is
        // a follow-up); only fills an empty content box.
        cpDraftFromTitle() {
            const t = (this.cpTitle || '').trim();
            if (!t) { return; }
            if ((this.cpContent || '').trim()) { return; }
            this.cpContent = 'Welcome to ' + t + '. '
                + 'Use this space to introduce ' + t.toLowerCase() + ' — what it is, who it is for, '
                + 'and why it matters. You can edit everything in the editor after creating the page.';
        },
        // Parent-page dropdown (searchable). Pages come from the public content
        // API filtered client-side to real pages (content_type === 'page').
        cpToggleParent() {
            this.cpParentOpen = !this.cpParentOpen;
            if (this.cpParentOpen && !this.cpParentLoaded) { this.cpLoadParentPages(); }
        },
        async cpLoadParentPages() {
            try {
                const base = mw.settings.site_url;
                const r = await fetch(base + 'api/content?content_type=page&limit=500&is_active=1', {
                    credentials: 'include', headers: { Accept: 'application/json' },
                });
                const d = await r.json();
                const items = (d && d.data) ? d.data : (Array.isArray(d) ? d : []);
                this.cpParentPages = items
                    .filter((x) => x && x.content_type === 'page' && x.title)
                    .map((x) => ({ id: x.id, title: x.title }))
                    .sort((a, b) => String(a.title).localeCompare(String(b.title)));
                this.cpParentLoaded = true;
            } catch (e) {
                this.cpParentPages = [];
            }
        },
        cpFilteredParents() {
            const q = (this.cpParentFilter || '').toLowerCase().trim();
            if (!q) { return this.cpParentPages; }
            return this.cpParentPages.filter((p) => (p.title || '').toLowerCase().includes(q));
        },
        cpPickParent(p) {
            if (p) { this.cpParentId = p.id; this.cpParentLabel = p.title; }
            else { this.cpParentId = ''; this.cpParentLabel = ''; }
            this.cpParentOpen = false;
            this.cpParentFilter = '';
        },
        cpAllLayouts() {
            // Jump to the full layouts picker (Start-from "All layouts →").
            this.pickerSkin = 'layouts';
            this.filterCategory = '';
            this.filterKeyword = '';
            this.filterLayouts();
            this.$nextTick(() => { this.setupIframeObserver(); });
        },
        async cpCreate(draft) {
            if (this.cpCreating) { return; }
            const title = (this.cpTitle || '').trim();
            if (!title) {
                this.cpError = 'Give the page a title first.';
                const el = document.querySelector('.mw-le-cp-title-input');
                if (el) { el.focus(); }
                return;
            }
            this.cpCreating = true;
            this.cpError = '';
            try {
                const base = mw.settings.site_url;
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const token = (tokenEl && tokenEl.content) || (mw.settings && mw.settings.csrf) || '';
                // The page itself is a clean editable page; the chosen "Start
                // from" LAYOUT (a Content-category section) is inserted into it
                // after the new page's canvas loads (see the liveEditCanvasLoaded
                // handler) — the Content skins are section layouts, not page
                // templates, so they can't be used as layout_file.
                const startFrom = this.cpActiveStartFrom() || {};
                const payload = {
                    content_type: 'page',
                    title: title,
                    url: this.cpEffectiveSlug(),
                    // Draft = unpublished (is_active 0) AND kept out of the main
                    // menu — an unpublished page must not leave a live nav link.
                    is_active: draft ? 0 : 1,
                    is_deleted: 0,
                    layout_file: 'clean.blade.php',
                };
                // Add to the main menu only for a published page with the toggle on.
                // save_content expects an ARRAY of menu container ids (an integer 1
                // is silently ignored).
                if (!draft && this.cpAddToMenu && this.cpMenuId) {
                    payload.add_content_to_menu = [this.cpMenuId];
                }
                // Start from a layout = embed the layouts module (referencing the
                // chosen skin) into the page's content field; MW renders it as
                // that layout. Blank/Clean just uses the optional notes.
                const notes = (this.cpContent || '').trim();
                if (startFrom.layout && startFrom.layout !== 'clean' && startFrom.key !== 'blank') {
                    payload.content = '<module type="layouts" template="' + startFrom.layout + '" />'
                        + (notes ? ('<p>' + notes + '</p>') : '');
                } else if (notes) {
                    payload.content = notes;
                }
                if (this.cpParentId) { payload.parent = this.cpParentId; }
                const res = await fetch(base + 'api/save_content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload),
                });
                const raw = await res.text();
                let id = null;
                try {
                    const j = JSON.parse(raw);
                    id = (typeof j === 'number') ? j : (j.id || (j.data && j.data.id) || null);
                } catch (e) { id = parseInt(raw, 10) || null; }
                if (!res.ok || !id) {
                    this.cpError = 'Could not create the page.';
                    return;
                }
                await this.niAttachImage(id);
                let url = '';
                try {
                    const g = await fetch(base + 'api/content/' + id, { credentials: 'include', headers: { Accept: 'application/json' } });
                    const gd = await g.json();
                    url = (gd && gd.data && gd.data.url) || (gd && gd.url) || '';
                } catch (e) { /* fall back */ }
                this.showModal = false;
                window.location.href = base + 'admin/live-edit' + (url
                    ? ('?url=' + encodeURIComponent(base + url))
                    : ('?content_id=' + id));
            } catch (e) {
                this.cpError = 'Could not create the page.';
            } finally {
                this.cpCreating = false;
            }
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
            // Observe every wrapper currently in the DOM. Prefer a direct DOM
            // query over $refs: a `ref` inside `v-for` — here spread across the
            // masonry/list/full sub-trees AND the grouped category sections — does
            // NOT reliably collect every node in Vue 3; in practice $refs.
            // iframeWrappersList held only the LAST wrapper, so only a single
            // preview iframe ever mounted while every other card stayed on its
            // title placeholder. Querying the rendered dialog for the stable
            // [data-mw-iframe-key] hook is authoritative and order-independent.
            var scope = document.querySelector('.mw-le-layouts-dialog') || document;
            var wrappers = Array.prototype.slice.call(scope.querySelectorAll('[data-mw-iframe-key]'));
            // Fallback to the ref arrays in case the markup hook ever changes.
            if (!wrappers.length) {
                wrappers = [].concat(this.$refs.iframeWrappersMasonry || [])
                             .concat(this.$refs.iframeWrappersList || []);
            }
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
                // Always open the layout-insert flows in the layouts skin (the
                // add-content skin may have left pickerSkin flipped).
                instance.pickerSkin = 'layouts';
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
                // task-2026-06-13-letele2 — set the insert target (these handlers
                // previously dropped the passed element, unlike appendLayoutRequestOnBottom,
                // so every caller of insertLayoutRequestOnTop/OnBottom — incl. "Add a
                // block to this page" — opened the picker but inserted nothing).
                instance.target = element;
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
                // task-2026-06-13-letele2 — set the insert target (see OnTop above).
                instance.target = element;
                showModal()
                instance.layoutInsertLocation = 'bottom';
                mw.app.registerChangedState(element);
            });

            // task-2026-09-17-addcontent — open THIS modal as the Add-content
            // dialog (add-content skin). The insert target is the canvas content
            // root so a layout drilled into from here still appends correctly.
            mw.app.editor.on('openAddContentPicker', function (element) {
                instance.target = element || instance.target;
                instance.layoutInsertLocation = 'append';
                instance.openAddContentPickerSkin();
            });
        });

        // Also accept the plain window event the toolbar button dispatches, so
        // the Add-content button opens this picker without the old Filament modal.
        window.addEventListener('openAddContentPicker', function () {
            instance.layoutInsertLocation = 'append';
            instance.openAddContentPickerSkin();
        });

        // task-2026-09-17-createpage — open the two-pane Create-page dialog.
        window.addEventListener('openCreatePageDialog', function () {
            instance.openCreatePageSkin();
        });

        // task-2026-09-22 — open the redesigned "Page settings" (edit content)
        // dialog for a specific content id (passed from the toolbar page chip).
        window.addEventListener('openEditContentDialog', function (e) {
            var id = (e && e.detail && e.detail.contentId) ? e.detail.contentId : null;
            if (id) { instance.openEditContentSkin(parseInt(id, 10)); }
        });

        // Close the create-page Parent dropdown on an outside click (Vue has no
        // @click.outside). mousedown fires before the trigger's @click, and the
        // trigger/menu live inside .mw-le-cp-parentpick so clicking them is not
        // treated as "outside".
        document.addEventListener('mousedown', function (e) {
            if (!instance.cpParentOpen) { return; }
            const inside = e.target && e.target.closest && e.target.closest('.mw-le-cp-parentpick');
            if (!inside) { instance.cpParentOpen = false; }
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

        // task-2026-09-17-addcontent — letter shortcuts in the add-content skin
        // (the P/O/R/I/C/L hints in the rail). Ignored while typing in a field.
        document.addEventListener('keydown', function (evt) {
            if (!instance.showModal || instance.pickerSkin !== 'add-content') { return; }
            const tag = (evt.target && evt.target.tagName) || '';
            if (tag === 'INPUT' || tag === 'TEXTAREA' || evt.metaKey || evt.ctrlKey || evt.altKey) { return; }
            // Image (i) + Layout (l) hidden for now — task-2026-09-22.
            const map = { b: 'block', p: 'page', o: 'post', r: 'product', c: 'category' };
            const key = (evt.key || '').toLowerCase();
            if (map[key]) {
                instance.addContentSelectedType = map[key];
                evt.preventDefault();
            } else if (evt.key === 'Enter') {
                const t = instance.addContentActiveType;
                if (t) { instance.addContentCreate(t); evt.preventDefault(); }
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
        },
        // task: the layout-preview iframes never loaded (cards showed only the
        // title placeholder). The IntersectionObserver that lazy-mounts each
        // preview iframe is set up via filterLayouts()'s $nextTick, which runs at
        // data-load time — while the modal is still CLOSED. The card wrappers are
        // behind v-if="showModal", so at that point they are not in the DOM and
        // the observer observes nothing; opening the modal later never re-ran it,
        // so no preview ever entered "in view" and the iframes stayed unmounted.
        // Re-run the observer once the modal opens and its cards have rendered.
        showModal: function (isOpen) {
            if (isOpen) {
                this.$nextTick(() => {
                    this.setupIframeObserver();
                });
            }
        }
    },
    computed: {
        // AI-716 / task-2026-05-16-c4893b — categories that survive
        // the featuredCategoryNames intersection, kept in the
        // designer-specified spec order (NOT alphabetised). Filters
        // to only those categories actually present in
        // layoutsList.categories so a Featured slot never points
        // at a category with zero layouts.
        featuredCategories() {
            if (!this.layoutsList?.categories?.length) return [];
            const cats = this.layoutsList.categories;
            return this.featuredCategoryNames.filter(name => cats.includes(name));
        },
        // AI-716 — categories NOT in featuredCategoryNames, sorted
        // alphabetically per spec "alphabetised, the remaining 11".
        otherCategories() {
            if (!this.layoutsList?.categories?.length) return [];
            const featuredSet = new Set(this.featuredCategoryNames);
            return this.layoutsList.categories
                .filter(name => !featuredSet.has(name))
                .slice()
                .sort((a, b) => String(a).localeCompare(String(b)));
        },
        // add-content skin: the rail is split into "THIS PAGE" + "SITE CONTENT".
        addContentThisPage() {
            return this.addContentTypes.filter((t) => t.group === 'this-page');
        },
        addContentSiteContent() {
            return this.addContentTypes.filter((t) => t.group === 'content');
        },
        addContentActiveType() {
            return this.addContentTypes.find((t) => t.key === this.addContentSelectedType) || null;
        },
        // ── edit-content computeds ────────────────────────────────────────────
        ecTypeLabel() {
            const map = { page: 'Page', post: 'Post', product: 'Product' };
            return this.$lang(map[this.ecContentType] || 'Page');
        },
        ecBadge() {
            const map = { page: 'Pg', post: 'Po', product: 'Pr' };
            return map[this.ecContentType] || 'Pg';
        },
        ecBadgeIcon() {
            // Real content-type glyphs (same visual family as the create-page
            // dialog's document badge) — page = file, post = article, product = tag.
            const page = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>';
            const post = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16M4 10h16M4 15h10M4 20h7"/></svg>';
            const product = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.6a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.4"/></svg>';
            const map = { page, post, product };
            return map[this.ecContentType] || page;
        },
        ecHost() {
            try {
                const u = new URL(mw.settings.site_url);
                return u.host;
            } catch (e) { return 'mysite.com'; }
        },
        ecBreadcrumbText() {
            const parts = [this.$lang('Site root')].concat((this.ecBreadcrumb || []).map((c) => c.title));
            if (!this.ecBreadcrumb || !this.ecBreadcrumb.length) {
                parts.push(this.ecTitle || this.$lang('Untitled'));
            }
            return parts.join(' › ');
        },
        ecMetaTrunc() {
            const s = (this.ecMetaDescription || '').trim();
            return s.length > 90 ? (s.slice(0, 90) + '…') : s;
        },
        ecDirtyCount() {
            return Object.keys(this.ecDirty || {}).length;
        },
        ecParentCurrentLabel() {
            if (!this.ecParentId) { return this.$lang('Top level'); }
            if (this.ecParentLabel) { return this.ecParentLabel; }
            const b = this.ecBreadcrumb || [];
            const last = b.length ? b[b.length - 1] : null;
            return (last && last.title) ? last.title : ('#' + this.ecParentId);
        },
    },
    data() {
        return {
            licenseKey: '',
            filterKeyword: '',
            filterCategory: '',
            // AI-716 / task-2026-05-16-c4893b — Featured-set is
            // declared here as a data field (NOT hardcoded in
            // template) so PM can re-weight later by editing this
            // one array. Order is meaningful: featured categories
            // render top-to-bottom in this order. Categories not
            // present in `layoutsList.categories` are silently
            // skipped at computed time (so a stale entry doesn't
            // break the rail). The 6 designer-specified high-use
            // categories are listed per spec.
            featuredCategoryNames: [
                'Content',
                'Header',
                'Features',
                'Hero',
                'Call To Action',
                'Gallery',
            ],
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

            // task-2026-09-17-addcontent — this same picker component is reused
            // for the "Add content" dialog via a skin. pickerSkin flips the whole
            // modal body between the layouts grid ('layouts', default) and the
            // hybrid add-content dialog ('add-content'): a content-type rail with
            // a preview/create pane (mockup 2a) + a block grid (mockup 2b).
            pickerSkin: 'layouts',
            addContentSelectedType: 'block',
            // inline quick-create (mockup 2a): Title + Draft/Create for page/post/product
            addContentTitle: '',
            addContentCreating: false,
            addContentError: '',
            // new-item image (page/post/product) + product price fields
            niImage: '',
            niPrice: '',
            niSpecialPrice: '',
            // inline category create: description + mw.tree "Add to" selection
            // (a parent page → rel_id, or a parent category → parent_id)
            catDescription: '',
            catRelId: 0,
            catParentId: 0,

            // ── edit-content (Page settings) skin ─────────────────────────────
            ecId: null,
            ecLoading: false,
            ecSaving: false,
            ecError: '',
            ecTitle: '',
            ecSlug: '',
            ecSlugEditing: false,
            ecContentType: 'page',
            ecPublished: true,
            ecIsHome: false,
            ecDesign: { template: '', layout: '' },
            ecLiveDate: '',
            ecBreadcrumb: [],
            ecInMenu: false,
            ecMenuPosition: null,
            ecMenuLabel: '',
            ecCover: null,
            ecMetaDescription: '',
            ecSeoEditing: false,
            ecOpenUrl: '',
            ecEditUrl: '',
            ecDirty: {},
            ecMenuId: null,
            // Change design (template + layout) + Move (parent)
            ecTemplateKey: '',
            ecLayoutKey: '',
            ecTemplates: [],
            ecLayouts: [],
            ecDesignOpen: false,
            ecMoveOpen: false,
            ecParentId: 0,
            ecParentLabel: '',

            // ── create-page dialog (two-pane form + preview) ──────────────────
            cpTitle: '',
            cpSlug: '',
            cpSlugEdited: false,
            cpSlugEditing: false,
            cpContent: '',
            cpStartFrom: 'blank',
            cpParentId: '',
            cpParentLabel: '',
            cpParentOpen: false,
            cpParentPages: [],
            cpParentLoaded: false,
            cpParentFilter: '',
            cpAddToMenu: true,
            cpMenuId: null,
            cpMoreOpen: false,
            cpCreating: false,
            cpError: '',
            cpStartFromOptions: [
                { key: 'blank',   label: 'Blank',   layout: 'clean',
                  description: 'An empty page — start from scratch.' },
                { key: 'hero',    label: 'Hero',    layout: 'default',
                  description: 'Full-width image, title over it, then an intro and a content grid.' },
                { key: 'article', label: 'Article', layout: 'default',
                  description: 'A centered title and a readable single-column body — great for text.' },
                { key: 'landing', label: 'Landing', layout: 'default',
                  description: 'A hero, feature sections and a call to action — a marketing page.' },
            ],
            addContentTypes: [
                { key: 'block', label: 'Block', group: 'this-page', badge: '+', shortcut: '',
                  description: 'Add a block to this page.' },
                { key: 'page', label: 'Page', group: 'content', badge: 'Pg', shortcut: 'P',
                  createAction: 'addPageAction', tint: '#e6ecff', quickCreate: true,
                  description: 'A standalone page in your site navigation.' },
                { key: 'post', label: 'Post', group: 'content', badge: 'Po', shortcut: 'O',
                  createAction: 'addPostAction', tint: '#e3f5ec', quickCreate: true,
                  description: 'A blog article with a cover image, date and author. It appears in your Blog page and any category you assign.' },
                { key: 'product', label: 'Product', group: 'content', badge: 'Pr', shortcut: 'R',
                  createAction: 'addProductAction', tint: '#ffe9df', quickCreate: true,
                  description: 'A shop product with price, gallery and Add-to-cart.' },
                { key: 'category', label: 'Category', group: 'content', badge: 'Ca', shortcut: 'C',
                  createAction: 'addCategoryAction', tint: '#e0f5f5', quickCreate: true,
                  description: 'A group that organizes your posts or products.' },
                // Image + Layout hidden for now (task-2026-09-22). The handlers
                // (addImageAction handoff, layout → layouts grid) remain in place;
                // re-add these entries to bring the rail options back.
                // { key: 'image', label: 'Image', group: 'content', badge: 'Im', shortcut: 'I',
                //   createAction: 'addImageAction', tint: '#f2e6ff',
                //   description: 'Upload an image straight onto the page.' },
                // { key: 'layout', label: 'Layout', group: 'content', badge: 'La', shortcut: 'L',
                //   layoutCategory: '', tint: '#efe6ff',
                //   description: 'Insert a ready-made section layout from the library.' },
            ],
            // Blocks with a real module type insert that module directly; the
            // rest (no single-module equivalent) drill the SAME modal into the
            // layouts grid filtered to a related category.
            addContentBlocks: [
                { key: 'text', label: 'Text', layoutCategory: 'Text Block' },
                { key: 'image', label: 'Image', layoutCategory: 'Gallery' },
                { key: 'gallery', label: 'Gallery', module: 'pictures' },
                { key: 'button', label: 'Button', module: 'btn' },
                { key: 'columns', label: 'Columns', layoutCategory: 'Grids' },
                { key: 'form', label: 'Form', layoutCategory: 'Contacts' },
                { key: 'video', label: 'Video', module: 'video' },
                { key: 'divider', label: 'Divider', module: 'spacer' },
            ],
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
