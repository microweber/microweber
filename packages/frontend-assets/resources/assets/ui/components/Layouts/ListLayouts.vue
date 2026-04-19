<template>


    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" class="mw-le-overlay active" v-on:click="showModal = false"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >

        <div v-if="showModal"
             role="dialog" aria-label="Insert layout"
             class="mw-le-dialog-block mw-le-layouts-dialog w-100 active"
             style="inset:20px; transform:none; animation-duration: .3s; z-index: 1000; background-color: #ececec;"
        >

            <!-- Close Button -->
            <button
                aria-label="Close"
                class="mw-le-dialog-close-btn"
                style="position:absolute;top:16px;right:16px;z-index:10;background:none;border:none;font-size:2rem;line-height:1;cursor:pointer;"
                type="button"
                @click="showModal = false"
            >
                &times;
            </button>

            <div class="modules-list modules-list-defaultModules">
                <div class="mw-le-layouts-dialog-row">

                    <div v-if=" layoutsList?.categories?.length" class="mw-le-layouts-dialog-col">


                        <ul class="modules-list-categories py-5">

                            <li :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']"
                                v-on:click="filterCategorySubmit('')">
                                All categories
                            </li>

                            <li v-for="categoryName in layoutsList.categories"
                                :data-category="[categoryName ? categoryName.toLowerCase(): '']"
                                v-on:click="filterCategorySubmit(categoryName)">

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

                        <div v-show="layoutsList?.categories?.length">
                            <div class="modules-list-search-block input-icon">
                                <input v-model="filterKeyword" class="modules-list-search-field form-control rounded-0"
                                       type="text"
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
                                            <div v-else-if="item.preview_url" class="layout-iframe-preview">
                                                <iframe
                                                    :src="item.preview_url"
                                                    :title="item.title"
                                                    class="layout-preview-iframe"
                                                    loading="lazy"
                                                    scrolling="no"
                                                    frameborder="0"
                                                    sandbox="allow-same-origin allow-scripts"
                                                ></iframe>
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
                                    <div
                                        v-else-if="item.preview_url"
                                        class="modules-list-block-item-picture layout-iframe-preview">
                                        <iframe
                                            :src="item.preview_url"
                                            :title="item.title"
                                            class="layout-preview-iframe"
                                            loading="lazy"
                                            scrolling="no"
                                            frameborder="0"
                                            sandbox="allow-same-origin allow-scripts"
                                        ></iframe>
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
            layoutsListTypePreview: 'list',
            layoutsList: [],
            layoutsListFiltered: [],
            layoutsListLoaded: false,
            layoutInsertLocation: 'top',
            showModal: false,
            isInserting: false,
            target: undefined,
            siteUrl: ''
        }
    }
}
</script>
