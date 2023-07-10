<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
    <div v-if="showModal"
         class="mw-le-dialog-block mw-le-layouts-dialog active"
         style="inset:20px; transform:none; animation-duration: .3s;"
    >

        <div class="modules-list modules-list-defaultModules">
            <div class="mw-le-layouts-dialog-row">
                <div class="mw-le-layouts-dialog-col">
                    <div class="modules-list-search-block input-icon">
                          <span class="input-icon-addon ms-3">

                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                            </span>

                        <input
                            v-model="filterKeyword"
                            v-on:keydown="filterLayouts()"
                            type="text" placeholder="Type to Search..." class="modules-list-search-field form-control rounded-0">
                    </div>

                    <ul class="modules-list-categories py-5">
<!--                        <li
                            v-on:click="filterCategorySubmit('')"
                            :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']"
                        >
                            All categories
                        </li>-->

                        <li></li>
                        <li
                            v-if="layoutsList.categories"
                            :class="[categoryName == filterCategory ? 'active animate__animated animate__pulse': '']"
                            v-for="categoryName in layoutsList.categories"
                            v-on:click="filterCategorySubmit(categoryName)">
                            {{categoryName}}
                        </li>
                    </ul>
                </div>
                <div class="mw-le-layouts-dialog-col">

<!--                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                        Looking for {{filterKeyword}}
                        <span v-if="filterCategory">
                            in {{filterCategory}}
                        </span>
                    </div>-->

                    <div class="me-6 pe-2 mt-4 col-md-3 ms-auto text-end justify-content-end">
                        <div class="btn-group d-flex justify-content-end pr-4 layout-list-buttons">
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
                            <button
                                type="button"
                                v-on:click="switchLayoutsListTypePreview('masonry')"
                                :class="['btn btn-sm border-0 px-0', layoutsListTypePreview == 'masonry'? 'btn-dark': 'btn-outline-dark']"
                            >
                                <MasonryIcon style="max-width:23px;max-height:23px;" />
                            </button>
                        </div>
                    </div>

                    <div v-show="layoutsListLoaded && layoutsListTypePreview == 'masonry'" class="modules-list-block">
                        <MasonryWall :items="layoutsListFiltered"
                                     :ssr-columns="1"
                                     :column-width="400"
                                     :padding="12"
                                     :gap="12">
                            <template #default="{ item, index }">
                                <div
                                    v-on:click="insertLayout(item.template)"
                                    :class="['modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']">

                                    <img :src="item.screenshot" :alt="item.title" />

                                    <div class="modules-list-block-item-title">{{item.title}}</div>

                                </div>
                            </template>
                        </MasonryWall>
                    </div>

                    <LazyList
                        v-if="layoutsListLoaded && (layoutsListTypePreview == 'masonry____' || layoutsListTypePreview == 'list' || layoutsListTypePreview == 'full') && layoutsListFiltered.length > 0"
                        :data="layoutsListFiltered"
                        :itemsPerRender="18"
                        :containerClasses="'modules-list-block modules-list-block-' + layoutsListTypePreview"
                        defaultLoadingColor="#222"
                    >
                        <template
                            v-slot="{item}">
                            <div
                                  v-on:click="insertLayout(item.template)"
                                  :class="['modules-list-block-style-' + layoutsListTypePreview, 'modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']">

                                <div class="modules-list-block-item-picture"
                                     :style="'background-image: url('+item.screenshot+');background-size: cover;'">

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
                            Nothing found...
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </Transition>

    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-dialog-close active"></div>

</template>

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
        switchLayoutsListTypePreview(type) {
            this.layoutsListTypePreview = type;
        },
        insertLayout(template) {
            mw.app.editor.insertLayout({'template':template}, this.layoutInsertLocation);
            this.showModal = false;
        },
        getLayoutsListFromService() {
            return mw.app.layouts.list();
        },
        filterCategorySubmit(category) {
            this.filterCategory = category;
            this.filterLayouts();
        },
        filterLayouts() {

            this.layoutsListLoaded = false;
            let layoutsFiltered = this.layoutsList.layouts;

            if (this.filterKeyword != '' && this.filterKeyword) {
                layoutsFiltered = layoutsFiltered.filter((item) => {
                    return item.title
                        .toUpperCase()
                        .includes(this.filterKeyword.toUpperCase())
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
            this.getLayoutsListFromService().then(function (data) {
                instance.layoutsList = data;
                instance.layoutsListLoaded = true;

                if (!instance.filterCategory) {
                    instance.filterCategorySubmit(data.categories[0]);
                }
            });
            mw.app.editor.on('insertLayoutRequestOnTop',function(element){
                instance.showModal = true;
                instance.layoutInsertLocation = 'top';
                mw.app.registerChangedState(element)
            });
            mw.app.editor.on('insertLayoutRequestOnBottom',function(element){
                instance.showModal = true;
                instance.layoutInsertLocation = 'bottom';
                mw.app.registerChangedState(element)
            });
        });

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'show-layouts') {
                if (instance.showModal == false) {
                    instance.showModal = true;
                } else {
                    instance.showModal = false;
                }
            }
        });

        // Close on Escape
        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                instance.showModal = false;
            }
        });
    },
    data() {
        return {
            items: [
                { title: 'First', description: 'The first item.' },
                { title: 'Second', description: 'The second item.'},
            ],
            filterKeyword: '',
            filterCategory: '',
            layoutsListTypePreview: 'list',
            layoutsList: [],
            layoutsListFiltered: [],
            layoutsListLoaded: false,
            layoutInsertLocation: 'top',
            showModal: false
        }
    }
}
</script>
