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
                <div class="modules-list-search-block d-flex align-items-center justify-content-between">

                    <input type="text"
                           v-model="filterKeyword"
                           v-on:keydown="filterModules()"
                           placeholder="Type to Search..."
                           class="modules-list-search-field">

                    <div v-if="showModal">
                        <span v-on:click="showModal = false" style="cursor:pointer;margin-right: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        </span>
                    </div>

                </div>
                <div class="modules-list-block">

                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                        Looking for {{filterKeyword}}
                    </div>

                    <div v-if="modulesCategoriesList.length > 0" v-for="category in modulesCategoriesList" class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title">
                            <h5>{{category}}</h5>
                        </div>
                        <div v-for="item in modulesListFiltered[category]"
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

            if (moduleItem.as_element) {
                options.as_element = true;
            }

            mw.app.editor.insertModule(module,options);
            this.showModal = false;
        },
        filterModules() {
            let modulesFiltered = this.modulesList;
            if (this.filterKeyword != '' && this.filterKeyword) {
                modulesFiltered = modulesFiltered.filter((item) => {
                    return item.name.toUpperCase().includes(this.filterKeyword.toUpperCase())
                });
            }

            let instance = this;
            instance.modulesListFiltered = [];
            instance.modulesCategoriesList = [];
            modulesFiltered.forEach(function(moduleElement) {

                if (!instance.modulesCategoriesList.includes(moduleElement.categories)) {
                    instance.modulesCategoriesList.push(moduleElement.categories);
                }

                if (!instance.modulesListFiltered[moduleElement.categories]) {
                    instance.modulesListFiltered[moduleElement.categories] = [];
                }
                instance.modulesListFiltered[moduleElement.categories].push(moduleElement);
            });
        }
    },
    components: {},
    mounted() {
        const instance = this;

        mw.app.on('ready', () => {

            this.getModulesList().then(function (data) {
                instance.modulesList = data.modules;
                instance.filterModules();
            });

            mw.app.editor.on('insertModuleRequest', function (el) {
                instance.showModal = true;
                mw.app.registerChangedState(el)
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
            filterKeyword: '',
            category: '',
            modulesList: [],
            modulesListFiltered: [],
            modulesCategoriesList: [],
            showModal: false
        }
    }
}
</script>




