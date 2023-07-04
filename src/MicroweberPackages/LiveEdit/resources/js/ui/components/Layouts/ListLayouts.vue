<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__backInLeft"
        leave-active-class="animate__animated animate__backOutLeft"
    >
    <div v-if="showModal"
         class="mw-le-dialog-block mw-le-layouts-dialog active"
         style="inset:20px;transform:none;"
    >

        <div class="modules-list modules-list-defaultModules">
            <div class="mw-le-layouts-dialog-row">
                <div class="mw-le-layouts-dialog-col">
                    <div class="modules-list-search-block">
                        <input
                            v-model="filterKeyword"
                            v-on:keydown="filterLayouts()"
                            type="text" placeholder="Type to Search..." class="modules-list-search-field">
                    </div>
                    <div class="mw-le-layouts-dialog-categories-title">Categories</div>
                    <ul class="modules-list-categories pb-5">
                        <li
                            v-on:click="filterCategorySubmit('')"
                            :class="['' == filterCategory ? 'active animate__animated animate__pulse': '']"
                        >
                            All categories
                        </li>

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

                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                        Looking for {{filterKeyword}}
                        <span v-if="filterCategory">
                            in {{filterCategory}}
                        </span>
                    </div>

                    <div class="pr-4 mt-3">
                        <div class="d-flex justify-content-end pr-4 layout-list-buttons">
                            <button
                                type="button"
                                v-on:click="layoutsListTypePreview = 'list'"
                                :class="['btn btn-sm btn-rounded mr-1', layoutsListTypePreview == 'list'? 'btn-primary': 'btn-dark']"
                            >
                                <GridIcon style="max-width:23px;max-height:23px;" />
                            </button>
                            <button
                                type="button"
                                v-on:click="layoutsListTypePreview = 'full'"
                                :class="['btn btn-sm btn-rounded', layoutsListTypePreview == 'full'? 'btn-primary': 'btn-dark']"
                            >
                                <ListIcon style="max-width:23px;max-height:23px;" />
                            </button>
                            <button
                                type="button"
                                v-on:click="layoutsListTypePreview = 'masonry'"
                                :class="['btn btn-sm btn-rounded mr-1', layoutsListTypePreview == 'masonry'? 'btn-primary': 'btn-dark']"
                            >
                                <MasonryIcon style="max-width:23px;max-height:23px;" />
                            </button>
                        </div>
                    </div>


                    <div v-if="layoutsListLoaded && layoutsListTypePreview == 'masonry'" class="modules-list-block">
                        <MasonryWall :items="layoutsListFiltered"
                                     :ssr-columns="1"
                                     :column-width="200"
                                     :padding="22"
                                     :gap="22">
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
                        v-if="layoutsListLoaded && (layoutsListTypePreview == 'list' || layoutsListTypePreview == 'full') && layoutsListFiltered.length > 0"
                        :data="layoutsListFiltered"
                        :itemsPerRender="18"
                        containerClasses="modules-list-block"
                        defaultLoadingColor="#222"
                    >
                        <template
                            v-slot="{item}">
                            <div
                                  v-on:click="insertLayout(item.template)"
                                  :style="[layoutsListTypePreview == 'full' ? 'width:100%;height:300px': 'width:300px;height:160px']"
                                  :class="['modules-list-block-item', item.locked ? 'modules-list-block-item-is-locked-true' : 'modules-list-block-item-is-locked-false']">

                                <div class="modules-list-block-item-picture"
                                     :style="'background-image: url('+item.screenshot+');background-size: contain;'">

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

<style>
.layout-list-buttons svg {
    fill:#FFFFFF;
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
                instance.layoutsListFiltered = data.layouts;
                instance.layoutsListLoaded = true;
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
