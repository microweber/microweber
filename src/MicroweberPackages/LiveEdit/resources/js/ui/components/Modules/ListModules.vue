<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
        <div v-if="showModal"

             class="mw-le-dialog-block mw-le-modules-dialog active"
             style="inset:35%; top:20%; transform:none; animation-duration: .3s;"
        >

            <div class="modules-list modules-list-defaultModules py-3">

                <div class="modules-list-search-block input-icon px-3 mx-md-3">
                    <span class="input-icon-addon ms-3">

                      <svg fill="none" xmlns="http://www.w3.org/2000/svg" class="icon" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                    </span>

                    <input type="text"
                           v-model="filterKeyword"
                           v-on:keydown="filterModules()"
                           placeholder="Type to Search..."
                           class="form-control mw-modules-list-search-block rounded-0">
                </div>

                <div class="modules-list-block">

                    <div v-if="filterKeyword" class="pl-4 mb-3 mt-3">
                        Looking for {{filterKeyword}}
                    </div>

                    <div v-if="modulesCategoriesList.length > 0" v-for="category in modulesCategoriesList" class="modules-list-block-category-section">
                        <div class="w-100 pt-3 text-capitalize px-3">
                            <h4 class="mb-2">{{category}}</h4>
                        </div>
                        <div v-for="item in modulesListFiltered[category]"
                             class="col-sm-6 col-12 px-3 mb-1 mw-modules-list-block-item d-flex align-items-center p-2 modules-list-block-item-is-locked-false cursor-pointer"
                             v-on:click="insertModule(item)"
                             v-tooltip data-bs-toggle="tooltip" :aria-label="item.name" data-bs-placement="top" :data-bs-original-title="item.name">
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




