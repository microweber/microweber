<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__backInLeft"
        leave-active-class="animate__animated animate__backOutLeft"
    >
        <div v-if="showModal"

             class="mw-le-dialog-block mw-le-modules-dialog active"
             style="inset:20px;transform:none;"
        >
            <div class="modules-list modules-list-defaultModules">
                <div class="modules-list-search-block">

                    <input type="text"
                           placeholder="Type to Search..."
                           class="modules-list-search-field">

                    <div v-if="showModal">
                        <button v-on:click="showModal = false" type="button" class="btn btn-danger">
                            Close Modal
                        </button>
                    </div>

                </div>
                <div class="modules-list-block">


                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>TODO Fix categories</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/highlight_code/highlight_code.svg)"></div>
                            <div class="modules-list-block-item-title">highlight_code</div>
                            <div class="modules-list-block-item-description">highlight_code</div>

                        </div>
                    </div>


                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>other</h5></div>
                        <div v-for="item in modulesList"
                             class="modules-list-block-item modules-list-block-item-is-locked-false"
                             v-on:click="insertModule(item)">
                            <div class="modules-list-block-item-picture"
                                 :style="{ backgroundImage: `url(${item.icon})` }"></div>
                            <div class="modules-list-block-item-title">{{ item.name }}</div>
                            <div class="modules-list-block-item-description">{{ item.description }}</div>
                        </div>
                    </div>


                    <div class="modules-list-block-no-results" style="display: none;">Nothing found...</div>

                </div>
            </div>


        </div>
    </Transition>

</template>


<script>
export default {
    methods: {
        getModulesList() {
            return mw.app.modules.list();
        },
        insertModule(moduleItem) {
            var module = moduleItem.module;
            var options = {};
            if(moduleItem.as_element){
                options.as_element = true;
            }

            mw.app.editor.insertModule(module,options);
            this.showModal = false;
        }
    },
    components: {},
    mounted() {
        const instance = this;

        mw.app.on('ready', () => {
            this.getModulesList().then(function (data) {
                instance.modulesList = data.modules;
            });

            mw.app.editor.on('insertModuleRequest', function (el) {
                console.log(el);
                instance.showModal = true;
            });

        });


        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'show-modules') {
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
            modulesList: null,
            showModal: false
        }
    }
}
</script>




