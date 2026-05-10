<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-overlay active"></div>



    <Transition
        enter-active-class="animate__animated animate__zoomIn"
        leave-active-class="animate__animated animate__zoomOut"
    >
        <div v-if="showModal"
             role="dialog" aria-label="Insert module"
             class="mw-le-dialog-block mw-le-modules-dialog active"
             style="animation-duration: .3s;"
        >

            <div class="modules-list modules-list-defaultModules py-3">
                <button type="button" aria-label="Close module list" class="close-modal-button" @click="showModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="modules-list-search-block input-icon px-3 mx-md-3 position-relative">
                    <span class="input-icon-addon me-5 " style="position: absolute; z-index: 10; left: 10px; margin-right: auto; justify-content: start;">

                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>

                    </span>

                    <!-- AI-173 (cycle-150 2026-05-10): switch from
                         type="text" to type="search" so mobile users get
                         the native browser X clear-affordance. Also pin
                         inputmode + enterkeyhint so the on-screen keyboard
                         shows a "search" submit key, and aria-label so
                         screen readers have a stable label that survives
                         placeholder localisation. Pattern mirrors the
                         existing ListLayouts.vue search input. -->
                    <input type="search"
                        autofocus
                           v-model="filterKeyword"
                           inputmode="search"
                           enterkeyhint="search"
                           :aria-label="$lang('Search modules')"
                           v-bind:placeholder="$lang('Type to Search') + '...'"
                           class="js-modules-list-search-input mw-modules-list-search-input form-control mw-modules-list-search-block rounded-0 w-100">

                    <!-- AI-173 (cycle-150 2026-05-10): convert the inline
                         <span> clear-affordance to a real <button> so
                         keyboard activation, focus-visible rings, and
                         button semantics work natively. The inline-style
                         hit target was ~28x28 (14px font + 3px padding) —
                         under WCAG 2.5.5 / iOS HIG 44x44 floor. The new
                         button enforces 44x44 via the
                         .mw-modules-list-search-clear class. The browser's
                         native type="search" X also remains as a
                         redundant clear-affordance — both are kept so
                         users on browsers that hide the native X (Safari)
                         still get a discoverable clear control. -->
                         <button type="button"
                                 v-show="filterKeyword.length > 0"
                                 class="mw-modules-list-search-clear"
                                 :aria-label="$lang('Clear search')"
                                 v-on:click="filterClearKeyword()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.4em" height="1.4em" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"></path>
                            </svg>
                        </button>

                </div>

                <div class="modules-list-block" style="width:100%;padding:20px;">

                    <div v-if="filterKeyword && filterKeyword.trim().length > 0" class="pl-4 mb-3 mt-3">
                        Looking for <strong>{{filterKeyword}}</strong>
                    </div>

                    <div v-if="modulesListFiltered.length > 0" class="row">
                        <div v-for="item in modulesListFiltered"
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
                    <div v-else class="alert" role="alert">
                        <h5 class="text-secondary fw-normal">No results for <strong>{{filterKeyword}}</strong></h5>
                    </div>

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
        getModulesList(cb) {
            return mw.app.modules.list(cb);
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
            mw.app.registerChangedState(edit, true);

            this.showModal = false;

            await mw.app.editor.insertModule(module, options, insertLocation, this.target);


            // mw.top().app.dispatch('moduleInserted', {module, options, insertLocation, target:this.target})

            mw.app.registerChangedState(edit, true)
            this.showModal = false;
        },
        filterModules() {
            let filterKeyword = this.filterKeyword.trim();
            let modulesFiltered = this.modulesList || [];

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

            // Filter out not allowed modules
            this.modulesListFiltered = modulesFiltered.filter(moduleElement => {
                return !notAllowedModules.includes(moduleElement.name);
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

            this.getModulesList(function (data) {
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

            insertModulePosition: 'bottom',
            filterKeyword: '',
            category: '',
            modulesList: [],
            modulesListFiltered: [],
            showModal: false,
            target: null,
            insertModuleMode: 'insertModuleDefault',
        }
    }
}
</script>

<style>
.close-modal-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    cursor: pointer;
    z-index: 100;
    padding: 5px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}



.close-modal-button:hover {
    background-color: rgba(0,0,0,0.1);
}

.modules-list {
    position: relative;
}
</style>
<style>
.close-modal-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    cursor: pointer;
    z-index: 100;
    padding: 5px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}



.close-modal-button:hover {
    background-color: rgba(0,0,0,0.1);
}

.modules-list {
    position: relative;
}

/*
 * AI-173 (cycle-150 2026-05-10) — Module picker search clear button.
 *
 * The previous inline-styled <span> hit target was ~28x28 (1.4em SVG
 * + 3px padding) — under the WCAG 2.5.5 / iOS HIG 44x44 floor.
 *
 * The new <button> uses min-width/min-height:44px with a centered
 * SVG so the visual icon stays the same size but the tappable area
 * is comfortable on mobile. Positioned absolute to overlay the right
 * side of the search input — same anchor point as the previous span.
 *
 * The native type="search" clear cross (Chrome/Edge) still appears
 * AS WELL — those browsers render a small X inside the input. Our
 * button sits to the right of it as the dominant affordance, and on
 * Safari/Firefox where the native X is suppressed our button is the
 * only clear control — guarantees a discoverable mobile clear in
 * every browser.
 */
.mw-modules-list-search-clear {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    /* Offset from the parent's right by ~56px so we don't collide
       with the dialog's close-modal-button (positioned absolute at
       top:10 / right:10 of .modules-list — i.e. right next to the
       search row). 56 = 44 button width + ~12 gap to the close-X.
       The clear-search button sits visually INSIDE the search input
       (because the input is the dominant child of the parent block),
       and the close-the-picker X sits to its right at the dialog
       corner. Two distinct affordances, no overlap. */
    right: 56px;
    z-index: 11;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    min-height: 44px;
    padding: 6px;
    background: transparent;
    border: 0;
    color: #6b7280;
    cursor: pointer;
    border-radius: 6px;
}
.mw-modules-list-search-clear:hover {
    background-color: rgba(0, 0, 0, 0.05);
    color: #111827;
}
.mw-modules-list-search-clear:focus-visible {
    outline: 2px solid rgb(59, 130, 246);
    outline-offset: 2px;
}

/* Hide WebKit's native search clear cross when our explicit button
   is the discoverable one — avoids two visually-competing X glyphs
   stacked in the same corner of the input on Chrome/Edge. NOTE:
   `display: none` is ignored by Chromium on this pseudo-element;
   collapsing the width/height to 0 is the working approach. */
.mw-modules-list-search-input::-webkit-search-cancel-button {
    -webkit-appearance: none;
    appearance: none;
    display: none;
    width: 0;
    height: 0;
}
</style>


