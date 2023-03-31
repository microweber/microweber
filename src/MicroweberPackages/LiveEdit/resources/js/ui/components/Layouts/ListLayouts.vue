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
                        <li v-on:click="filterCategorySubmit('')">All categories</li>
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

                    <div class="modules-list-block">

                        <TransitionGroup

                            enter-active-class="animate__animated animate__backInLeft"
                            leave-active-class="animate__animated animate__backOutLeft"
                        >
                             <div v-for="(layout,index) in layoutsListFiltered" :key="index + 1" :style="{ transitionDelay: 0.02 * index + 's' }" class="modules-list-block-item modules-list-block-item-is-locked-false">
                                <div class="modules-list-block-item-picture"
                                     :style="'background-image: url('+layout.screenshot+')'"></div>
                                <div class="modules-list-block-item-title">{{layout.title}}</div>
                                <div class="modules-list-block-item-description">
                                    {{layout.description}}
                                </div>
                            </div>
                        </TransitionGroup>

                        <div v-if="!layoutsListFiltered" class="modules-list-block-no-results">
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
@import "//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";
</style>

<script>
import MasonryWall from '@yeger/vue-masonry-wall'

const items = [
    {
        title: 'First',
        description: 'The first item.',
    },
    {
        title: 'Second',
        description: 'The second item.',
    },
]

export default {
    methods: {
        getLayoutsListFromService() {
            return mw.app.layouts.list();
        },
        filterCategorySubmit(category) {
            this.filterCategory = category;
            this.filterLayouts();
        },
        filterLayouts() {
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

            this.layoutsListFiltered = layoutsFiltered;
        }
    },
    components: {},
    mounted() {
        const instance = this;

        mw.app.on('ready', () => {
            this.getLayoutsListFromService().then(function (data) {
                instance.layoutsList = data;
                instance.layoutsListFiltered = data.layouts;
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
            items:items,
            filterKeyword: '',
            filterCategory: '',
            layoutsList: [],
            layoutsListFiltered: [],
            showModal: false
        }
    }
}
</script>
