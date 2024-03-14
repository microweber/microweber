<template>
    <div v-if="canShowListStyleClassApplier">
        <div>

            <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path
                        d="M360-200v-80h480v80H360Zm0-240v-80h480v80H360Zm0-240v-80h480v80H360ZM200-160q-33 0-56.5-23.5T120-240q0-33 23.5-56.5T200-320q33 0 56.5 23.5T280-240q0 33-23.5 56.5T200-160Zm0-240q-33 0-56.5-23.5T120-480q0-33 23.5-56.5T200-560q33 0 56.5 23.5T280-480q0 33-23.5 56.5T200-400Zm0-240q-33 0-56.5-23.5T120-720q0-33 23.5-56.5T200-800q33 0 56.5 23.5T280-720q0 33-23.5 56.5T200-640Z"/>
                </svg>

                <b class="mw-admin-action-links ms-3" :class="{'active': showUlUlListElementClasses }"
                   v-on:click="toggleShowUlClasses">
                    List Style
                </b>
            </div>

            <div v-if="showUlUlListElementClasses">
                <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">
                    <label class="live-edit-label w-100">Select a style:</label>
                    <div v-for="(classObj, index) in predefinedUlClasses" :key="index" class="form-check mw-live-edit-list-style-editor-wrapper">
                        <input class="form-check-input d-none" type="radio" :id="'ulClassRadio' + index"
                               :value="classObj.key" v-model="selectedClass"  @change="applySelectedClass">

                        <label class="live-edit-label" :for="'ulClassRadio' + index">


                            {{ classObj.value }}


                            <div>
                                <div>
                                    <ul class="mb-0" :class="classObj.key">
                                        <li>Item 1</li>
                                        <li>Item 2</li>
                                        <li>Item 3</li>
                                    </ul>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

<!--
                <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">
                    <label class="live-edit-label" for="ulClassesSelect">Select a class:</label>
                    <select class="form-control-live-edit-input form-select" id="ulClassesSelect"
                            v-model="selectedClass"
                            @change="applySelectedClass">
                        <option v-for="(classObj, index) in predefinedUlClasses" :key="index" :value="classObj.key">
                            {{ classObj.value }}
                        </option>
                    </select>
                </div>
-->


            </div>

        </div>
    </div>
</template>

<script>

export default {

    data() {
        return {
            canShowListStyleClassApplier: false,
            showUlUlListElementClasses: false,
            activeNode: null,
            selectedClass: null,
            isReady: false,

            classesToKeep: [],
            predefinedUlClasses: [
                {key: null, value: "Default"},
                {key: "mw-default-layout-un-ordered-checked", value: "Checked"},
                {key: "mw-default-layout-un-ordered-arrows", value: "Arrow"},
                {key: "mw-default-layout-un-ordered-t1", value: "Style 1"},
                {key: "mw-default-layout-un-ordered-t2", value: "Style 2"},
                {key: "mw-default-layout-un-ordered-t3", value: "Style 3"},
                {key: "mw-default-layout-un-ordered-t4", value: "Style 4"},

            ],
            classes: []
        };
    },
    methods: {
        toggleShowUlClasses: function () {
            this.showUlUlListElementClasses = !this.showUlUlListElementClasses;
            this.emitter.emit('element-style-editor-show', 'ulClasses');
        },

        applySelectedClass() {
            if (this.selectedClass && this.activeNode) {
                // Clear existing classes
                this.activeNode.className = '';


                //remove predefined classes from the node
                this.predefinedUlClasses.forEach(classObj => {
                    this.activeNode.classList.remove(classObj.key);
                });

                // Add selected class

                this.activeNode.classList.add(this.selectedClass);

                // Optionally, you can emit an event to indicate changes
                if (mw.top().app) {
                    mw.top().app.registerChangedState(this.activeNode);
                }
            }
        },

        applyClasses() {
            if (!this.isReady) {
                return;
            }
            if (this.activeNode) {
                // Remove any existing classes from the active node
                this.activeNode.className = '';

                // Add the new classes to the active node from classes array
                this.classes.forEach(className => {
                    if (className) {
                        this.activeNode.classList.add(className);
                    }
                });


                if (mw.top().app) {
                    mw.top().app.registerChangedState(this.activeNode);
                }
            }
        },

        populateStyleEditor(element) {
            if (element && element.nodeType === 1) {
                this.isReady = false;


                //get 1st ul
                var ul = mw.tools.firstParentOrCurrentWithTag(element, ['ul', 'ol']);

                if (ul) {
                    element = ul;
                } else {
                    return;
                }

                this.activeNode = element;


                if (this.activeNode.className) {
                    // Populate the classes array with the existing classes of the active node
                    this.classes = this.activeNode.className
                        .split(' ')
                        .filter(className => !!className)
                        .filter(className => {
                            // Filter out classes that start with "animate__"
                            if (className.startsWith('animate__')) {
                                return false;
                            }
                            return !this.classesToKeep.includes(className);
                        });
                    //select predefined class if exists
                    this.predefinedUlClasses.forEach(classObj => {
                        if (this.activeNode.classList.contains(classObj.key)) {
                            this.selectedClass = classObj.key;
                        }
                    });


                }


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        }

    },
    mounted() {
        this.classesToKeep = mw.top().app.liveEdit.getClassesToKeep()


        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'ulClasses') {
                this.showUlUlListElementClasses = false;
            }
        });

    },
    watch: {
        '$root.selectedElement': {
            handler: function (element) {
                if (element) {

                    //is in edit field
                    var isInEditField = mw.top().app.liveEdit.liveEditHelpers.targetIsInEditField(element);
                    var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(element);
                    var ul = mw.tools.firstParentOrCurrentWithTag(element, ['ul', 'ol']);


                    if (ul && isInEditField && !isInaccessible) {
                        this.canShowListStyleClassApplier = true;
                        this.populateStyleEditor(element);

                    } else {
                        this.canShowListStyleClassApplier = false;
                    }


                }
            },
            deep: true
        },
        classes(newValue, oldValue) {
            // Apply the classes whenever the classes array changes
            this.applyClasses();
        },


    },
};
</script>
