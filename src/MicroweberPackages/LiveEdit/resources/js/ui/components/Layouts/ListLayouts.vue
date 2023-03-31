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
                        <input v-model="keyword" v-on:change="searchResult" type="text" placeholder="Type to Search..." class="modules-list-search-field">
                    </div>
                    <div class="mw-le-layouts-dialog-categories-title">Categories</div>
                    <ul class="modules-list-categories">
                        <li>All categories</li>
                        <li></li>
                    </ul>
                </div>
                <div class="mw-le-layouts-dialog-col">

                    <div v-if="keyword" class="pl-4 mb-3 mt-3">
                        Looking for {{keyword}}
                    </div>

                    <div class="modules-list-block">

                        <div v-for="layout in getLayoutsList()" class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 :style="'background-image: url('+layout.screenshot+')'"></div>
                            <div class="modules-list-block-item-title">{{layout.title}}</div>
                            <div class="modules-list-block-item-description">
                                {{layout.description}}
                            </div>
                        </div>

                        <div v-if="!getLayoutsList()" class="modules-list-block-no-results">
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
export default {
    methods: {
        getLayoutsListFromService() {
            return mw.app.layouts.list();
        },
        getLayoutsList() {
            let tempLayoutsList = this.layoutsList;

            if (this.keyword != '' && this.keyword) {
                tempLayoutsList = tempLayoutsList.filter((item) => {
                    return item.title
                        .toUpperCase()
                        .includes(this.keyword.toUpperCase())
                });
            }

            return tempLayoutsList;
        }
    },
    components: {},
    mounted() {
        const instance = this;

        mw.app.on('ready', () => {
            this.getLayoutsListFromService().then(function (data) {
                instance.layoutsList = data;
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
            keyword: '',
            category: '',
            layoutsList: null,
            showModal: false
        }
    }
}
</script>
