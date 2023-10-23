<template>


    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >

    <div v-if="showModal"
         class="mw-le-dialog-block mw-le-layouts-dialog w-100 active"
         style="inset:20px; transform:none; animation-duration: .3s;"
    >


        <div class="modules-list modules-list-defaultModules">
            <div class="mw-le-layouts-dialog-row">

                <div v-if="layoutsList && layoutsList.categories && layoutsList.categories.length > 0" class="mw-le-layouts-dialog-col">
                    <div class="modules-list-search-block input-icon">
                          <span class="input-icon-addon ms-3">

                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                            </span>

                        <input v-model="filterKeyword" type="text" placeholder="Type to Search..." class="modules-list-search-field form-control rounded-0">
                    </div>

                    <ul class="modules-list-categories py-5">

                        <li v-on:click="filterCategorySubmit('')"
                            :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']">
                            All categories
                        </li>

                        <li v-for="categoryName in layoutsList.categories"
                            v-on:click="filterCategorySubmit(categoryName)">

                            <a class="mw-admin-action-links" :class="[categoryName == filterCategory ? 'active animate__animated animate__pulse': '']">
                                {{categoryName}}
                            </a>

                        </li>
                    </ul>
                </div>

                <div :class="[layoutsList.categories.length > 0 ? 'mw-le-layouts-dialog-col' : 'mw-le-layouts-dialog-col-full col-xl-10 mx-auto px-xl-0 px-5']">

<!--                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                        Looking for {{filterKeyword}}
                        <span v-if="filterCategory">
                            in {{filterCategory}}
                        </span>
                    </div>-->

                    <div v-show="layoutsList.categories.length == 0">
                        <div class="modules-list-search-block input-icon" style="margin-top:25px;">
                          <span class="input-icon-addon ms-3">

                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                            </span>

                            <input v-model="filterKeyword" type="text" placeholder="Type to Search..." class="modules-list-search-field form-control rounded-0">
                        </div>
                    </div>

                    <div class="me-5 pe-3 my-3 py-0 col-xl-2 col-md-3 col-12 ms-auto text-end justify-content-end">
                        <div class="btn-group d-flex justify-content-end pe-4 layout-list-buttons">
<!--                            <button
                                type="button"
                                v-on:click="switchLayoutsListTypePreview('masonry')"
                                :class="['btn btn-sm border-0 px-0', layoutsListTypePreview == 'masonry'? 'btn-dark': 'btn-outline-dark']"
                            >
                                <MasonryIcon style="max-width:23px;max-height:23px;" />
                            </button>-->

                            <button
                                type="button"
                                v-on:click="switchLayoutsListTypePreview('list')"
                                :class="['btn btn-sm border-0 px-0', layoutsListTypePreview == 'list'? 'btn-dark': 'btn-outline-dark']"
                            >
                                <GridIcon style="max-width:23px;max-height:23px;" />
                            </button>
                            <button
                                type="button"
                                v-on:click="switchLayoutsListTypePreview('full')"
                                :class="['btn btn-sm border-0 px-0', layoutsListTypePreview == 'full'? 'btn-dark': 'btn-outline-dark']"
                            >
                                <ListIcon style="max-width:23px;max-height:23px;" />
                            </button>

                        </div>
                    </div>

                    <div v-if="layoutsListLoaded && layoutsListTypePreview == 'masonry'" class="modules-list-block-masonry">
                        <MasonryWall
                            :items="layoutsListFiltered"
                            :ssr-columns="1"
                            :column-width="400"
                            :padding="12"
                            :gap="12">
                            <template #default="{ item, index }">
                                <div
                                    v-on:click="insertLayout(item)"
                                    :class="['modules-list-block-item-masonry', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']">

                                    <img :src="item.screenshot" :alt="item.title" />

                                    <div class="modules-list-block-item-masonry-title">{{item.title}}</div>

                                </div>
                            </template>
                        </MasonryWall>
                    </div>

<!--                    <div v-if="layoutsListLoaded && (layoutsListTypePreview == 'list')">
                        <div>
                            <iframe style="width:100%;height:100vh;" :src="[siteUrl + 'preview-layouts?&no_editmode=y&category=' + filterCategory]"></iframe>
                        </div>
                    </div>-->

                    <LazyList
                        v-if="layoutsListLoaded && (layoutsListTypePreview == 'list' || layoutsListTypePreview == 'full') && layoutsListFiltered.length > 0"
                        :data="layoutsListFiltered"
                        :itemsPerRender="18"
                        :containerClasses="'modules-list-block modules-list-block-' + layoutsListTypePreview"
                        defaultLoadingColor="#222"
                    >
                        <template
                            v-slot="{item}">
                            <div
                                  v-on:click="insertLayout(item)"
                                  :class="['modules-list-block-style-' + layoutsListTypePreview, 'modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']">

                                <div class="modules-list-block-item-picture"
                                     :style="'background-image: url('+item.screenshot+');background-size: cover;background-position: center center;'">
                                </div>

                                <div class="modules-list-block-item-title">{{item.title}}</div>

                                <div class="modules-list-block-item-description">
                                    {{item.description}}
                                </div>
                            </div>
                        </template>
                    </LazyList>

                    <div v-if="layoutsListFiltered.length == 0" class="modules-list-block">
                        <div class="modules-list-block-no-results">

                            <div v-if="filterCategory.length > 0 && filterKeyword.length >0">
                                Nothing found in <b>{{filterCategory}}</b> with keyword <i>"{{filterKeyword}}"</i>.
                                <br />
                                <br />
                                <button v-on:click="searchInAll()" type="button" class="btn btn-outline-dark btn-sm">
                                    Search in all
                                </button>
                            </div>
                            <div v-else >
                                Nothing found.
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </Transition>

    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-dialog-close active"></div>

</template>

<style>
.wrap-iframe
{
    width: 100%;
    height: 100%;
    padding: 0;
    overflow: hidden;
    background: red;
}

.iframe-inside
{
    width: 1200px;
    height: 900px;
    border: 0;
    transform: scale(.37);
    transform-origin: 0 0;
}

</style>

<script>
import GridIcon from "../Icons/GridIcon.vue";
import ListIcon from '../Icons/ListIcon.vue';
import MasonryIcon from "../Icons/MasonryIcon.vue";
import LazyList from '../Optimizations/LazyLoadList/LazyList.vue';
import MasonryWall from '@yeger/vue-masonry-wall'
import { HomeIcon } from '@heroicons/vue/outline'

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
            if(this.isInserting) {
              return;
            }
            let template = false;
            if (layout.template) {
                template = layout.template;
            }
            if(!target) {
                target = this.$data.target
            }

            if (layout.locked) {

                var attrsForSettings = {};

                attrsForSettings.live_edit = true;
                attrsForSettings.module_settings = true;
                attrsForSettings.id = 'mw_unlock_package_modal';
                attrsForSettings.type = 'unlock-package'; 
                attrsForSettings.iframe = true;
                attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;

                var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

                var dialog = mw.top().dialogIframe({
                    url: src,
                    autoHeight: true,
                    width: 800,
                    skin: 'square_clean',
                });
                dialog.dialogHeader.style.display = 'none';
                dialog.iframe.addEventListener('load', () => {
                    dialog.iframe.contentWindow.document.getElementById('js-modal-livewire-ui-close').addEventListener('click', () => {
                        dialog.remove();
                    });
                });

                return;
            }

           this.showModal = false;

            mw.app.editor.insertLayout({'template':template}, this.layoutInsertLocation, target);

            this.$data.target = undefined;
            setTimeout(() => {
                this.isInserting = false;
            }, 300);
        },

        getLayoutsListFromService() {
            return mw.app.layouts.list();
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
        }
    },
    mounted() {
        const instance = this;

        mw.app.on('ready', () => {

            this.siteUrl = mw.settings.site_url;

            this.getLayoutsListFromService().then(function (data) {
                instance.layoutsList = data;
                instance.layoutsListLoaded = true;
                instance.filterLayouts();
            });
            mw.app.editor.on('insertLayoutRequestOnTop',function(element){
                instance.showModal = true;
                instance.layoutInsertLocation = 'top';
                mw.app.registerChangedState(element);
            });

            mw.app.editor.on('appendLayoutRequestOnBottom',function(element){
                instance.target = element;
                instance.showModal = true;
                instance.layoutInsertLocation = 'append';
                mw.app.registerChangedState(element);

            })
            mw.app.editor.on('insertLayoutRequestOnBottom',function(element){
                instance.showModal = true;
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
