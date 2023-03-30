<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__jello"
        leave-active-class="animate__animated animate__bounceOutLeft"
    >
    <div v-if="showModal" class="mw-le-dialog-block mw-le-layouts-dialog active">
        <div class="modules-list modules-list-defaultModules">
            <div class="mw-le-layouts-dialog-row">
                <div class="mw-le-layouts-dialog-col">
                    <div class="modules-list-search-block">
                        <input v-model="keyword" type="text" placeholder="Type to Search..." class="modules-list-search-field">
                    </div>
                    <div class="mw-le-layouts-dialog-categories-title">Categories</div>
                    <ul class="modules-list-categories">
                        <li>All categories</li>
                        <li></li>
                        <li>Qkoooo</li>
                    </ul>
                </div>
                <div class="mw-le-layouts-dialog-col">

                    <div v-if="keyword" class="pl-4 mb-3 mt-3">
                        Looking for {{keyword}}
                    </div>

                    <div class="modules-list-block">

                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/templates/template-big/modules/layouts/templates/titles/skin-2.jpg)"></div>
                            <div class="modules-list-block-item-title">Titles 2</div>
                            <div class="modules-list-block-item-description"></div>

                        </div>

                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/templates/template-big/modules/layouts/templates/titles/skin-3.jpg)"></div>
                            <div class="modules-list-block-item-title">Titles 3</div>
                            <div class="modules-list-block-item-description"></div>

                        </div>

                        <div class="modules-list-block-no-results" style="display: none;">Nothing found...</div>
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
        getLayoutsList() {
            return mw.app.modules.list();
        }
    },
    components: {},
    mounted() {
        const instance = this;
        this.getLayoutsList().then(function (data) {
            instance.layoutsList = data;
        });

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'show-layouts') {
                if (this.showModal == false) {
                    this.showModal = true;
                } else {
                    this.showModal = false;
                }
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
