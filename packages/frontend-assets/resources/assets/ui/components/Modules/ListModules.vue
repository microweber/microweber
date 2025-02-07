<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>



    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
        <div v-if="showModal"

             class="mw-le-dialog-block mw-le-modules-dialog active"
             style="animation-duration: .3s;"
        >

            <div class="modules-list modules-list-defaultModules py-3">

                <div class="modules-list-search-block input-icon px-3 mx-md-3 position-relative">
                    <span class="input-icon-addon me-5 " style="position: absolute; z-index: 10; left: 10px; margin-right: auto; justify-content: start;">

                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>

                    </span>

                    <input type="text"
                        autofocus
                           v-model="filterKeyword"
                           v-bind:placeholder="$lang('Type to Search') + '...'"
                           class="js-modules-list-search-input form-control mw-modules-list-search-block rounded-0 w-100">

                         <span v-show="filterKeyword.length > 0" style="position: absolute; cursor: pointer;color: #aeaeae;top: 25px;right: 23px;padding: 3px;" v-on:click="filterClearKeyword()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.4em" height="1.4em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"></path>
                            </svg>
                        </span>

                </div>

                <div class="modules-list-block" style="width:100%;padding:20px;">

                    <div v-if="filterKeyword && filterKeyword.trim().length > 0" class="pl-4 mb-3 mt-3">
                        Looking for <strong>{{filterKeyword}}</strong>
                    </div>

                    <div v-if="modulesCategoriesList.length > 0" v-for="category in modulesCategoriesList" class="modules-list-block-category-section">
                        <div class="w-100 pt-3 text-capitalize px-3">
                            <h4 class="mb-2">{{category}}</h4>
                        </div>
                        <div v-for="item in modulesListFiltered[category]"
                             class="col-sm-6 col-12 px-3 mb-1 mw-modules-list-block-item d-flex align-items-center p-2 modules-list-block-item-is-locked-false cursor-pointer"
                             v-on:click="insertModule(item)"
                             :title="item.name"
                             :aria-label="item.name"
                             data-bs-placement="top">
                            <div class="modules-list-block-item-picture"
                                  :style="{ backgroundImage: `url(${item.icon})` }"></div>
                            <div class="modules-list-block-item-title">
                                {{ item.name }}
                            </div>
                            <div class="modules-list-block-item-description">{{ item.description }}</div>
                        </div>
                    </div>
                    <h1 v-else>

                        <div class="alert " role="alert">

                            <h5 class="text-secondary fw-normal">No results for <strong>{{filterKeyword}}</strong></h5>
                        </div>
                    </h1>

                    <div class="modules-list-block-no-results" style="display: none;">Nothing found...</div>

                </div>
            </div>


        </div>
    </Transition>

</template>

<script>
import { ElementManager } from '../../../api-core/core/classes/element';




export default {
    methods: {
        modal() {
            this.showModal = true;
            this.filterModules();
        },
        getModulesList() {
            return mw.app.modules.list();
        },
        insertModule(moduleItem) {
            return this[this.insertModuleMode](moduleItem)
        },
        async insertFreeModule(moduleItem) {
            var module = moduleItem.module;
            var options = {};

            if (moduleItem.as_element) {
                options.as_element = true;
            }




            if(this.target.classList.contains('mw-free-layout-container')) {
                this.target = this.target.querySelector('.edit')
            }

            const edit = mw.top().tools.firstParentOrCurrentWithClass(this.target, 'edit')
            mw.app.registerChangedState(edit, true);
            this.showModal = false;
            let itm = await mw.app.editor.insertModule(module, options, 'bottom', this.target, 'append');



            const nodesToWrap = ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P'];


            const id = mw.id('free-element-');

            if(nodesToWrap.indexOf(itm.nodeName) !== -1) {
                const wrapper = ElementManager(`<div></div>`);
                ElementManager(itm).after(wrapper);
                wrapper.append(itm)
                itm = wrapper.get(0)

            }

            // mw.top().app.freeDraggableElementManager.freeLayoutNodes(this.target)

            if(!itm.id) {
                itm.id = id
            }

            mw.top().app.freeDraggableElementManager.initLayouts();
            mw.top().app.freeDraggableElementManager.freeElement(itm,  mw.top().tools.firstParentOrCurrentWithClass(this.target, 'mw-free-layout-container'));

            mw.app.registerChangedState(edit, true)


        },
        filterClearKeyword() {
            this.filterKeyword = '';
            this.filterModules();
        },
        async insertModuleDefault(moduleItem) {
            var module = moduleItem.module;
            var options = {};

            if (moduleItem.as_element) {
                options.as_element = true;
            }
            var insertLocation = this.insertModulePosition;

            const edit = mw.top().tools.firstParentOrCurrentWithClass(this.target, 'edit')
            mw.app.registerChangedState(edit, true)

            await mw.app.editor.insertModule(module, options, insertLocation, this.target);

            mw.app.registerChangedState(edit, true)
            this.showModal = false;
        },
        filterModules() {

            let filterKeyword = this.filterKeyword.trim();
            let modulesFiltered = this.modulesList;

            let notAllowedModules = [];



            if(this.insertModuleMode === 'insertFreeModule') {
                notAllowedModules = [
                    'Empty Element', 'Spacer', 'Multiple Columns'
                ]
            }


            if (filterKeyword != '' && filterKeyword) {
                modulesFiltered = modulesFiltered.filter((item) => {
                    if(notAllowedModules.includes(item.name)){
                        return false;
                    }
                    return item.keywords.toUpperCase().includes(filterKeyword.toUpperCase())
                });
            }

            let instance = this;
            instance.modulesListFiltered = [];
            instance.modulesCategoriesList = [];
            modulesFiltered.forEach(function(moduleElement) {



                if(notAllowedModules.includes(moduleElement.name)){
                    return;
                }

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
    watch: {
        filterKeyword(value) {
            this.filterModules();
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


            mw.app.editor.on('insertFreeModuleRequest',   (el) => {
                this.target = el || null;
                this.insertModuleMode = 'insertFreeModule';
                instance.modal();



                setTimeout(() => {
                    $('.mw-modules-list-search-block').focus()
                }, 78)
            });

            mw.app.editor.on('insertModuleRequest',   (el) => {
                this.target = el || null;



                const isFree = mw.tools.firstParentOrCurrentWithClass(el, 'mw-free-layout-container');


                if(isFree) {
                    this.insertModuleMode = 'insertFreeModule';
                      this.target = isFree;
                } else {
                    this.insertModuleMode = 'insertModuleDefault';
                }

                instance.modal();

                setTimeout(() => {
                    $('.mw-modules-list-search-block').focus()
                }, 78)
            });

        });


        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'show-modules') {
                if (instance.showModal == false) {
                    instance.modal();
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

            insertModulePosition: 'top',  // insert module on top or bottom
            filterKeyword: '',
            category: '',
            modulesList: [],
            modulesListFiltered: [],
            modulesCategoriesList: [],
            showModal: false,
            target: null,
            insertModuleMode: 'insertModuleDefault',
        }
    }
}
</script>




