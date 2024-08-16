<template>
    <div v-if="canShowPredefinedStylesClasses">
        <div>
            <div class="d-flex">
                <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
                     style="enable-background:new 0 0 24 24;" xml:space="preserve">
                    <path
                        d="M12.9,3l2,0.4L11.1,21l-2-0.4L12.9,3 M19.6,12L16,8.4V5.6l6.4,6.4L16,18.4v-2.8L19.6,12 M1.6,12L8,5.6v2.8L4.4,12L8,15.6  v2.8L1.6,12z"></path>
                </svg>

                <b class="mw-admin-action-links ms-3" :class="{'active': showPredefinedStylesClasses }"
                   v-on:click="togglePredefinedStylesClasses">
                    Predefined Styles
                </b>
            </div>

            <div v-if="showPredefinedStylesClasses">

                <div class="form-control-live-edit-label-wrapper my-4">

                    <h3 class="predefined-styles-rended" @click="showRadioButtons = !showRadioButtons" v-if="selectedClass && !showRadioButtons" :class="selectedClass">TEXT</h3>
                    <label class="live-edit-label cursor-pointer" @click="showRadioButtons = !showRadioButtons" v-if="!selectedClass && !showRadioButtons">Select a style:</label>

<!--                    <button @click="showRadioButtons = !showRadioButtons">Toggle Radio Buttons</button>-->


                    <div class="row align-items-center justify-content-center gap-3">
                        <div v-if="showRadioButtons" v-for="(classObj, index) in predefinedClasses" :key="index">
                            <div class="mw-live-edit-predefines-styles-box mx-auto">
                                <label :for="classObj">
                                    <input type="radio" :id="classObj" :value="classObj" v-model="selectedClass" @change="applySelectedClass">
                                    <span :class="classObj">TEXT</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


<!--
                <img v-if="predefinedClassesScreenshots && predefinedClassesScreenshots[classObj]" :src="predefinedClassesScreenshots[classObj]" alt="screenshot" width="100" height="100">
-->

                <div class="form-control-live-edit-label-wrapper my-4">
                <EditCssVariables :selectedClass="selectedClass"
                                  :predefinedClassesVariables="predefinedClassesVariables"
                                  @variables-changed="handleVariablesChanged"></EditCssVariables>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import EditCssVariables from './components/EditCssVariables.vue';

export default {
    components: {
        EditCssVariables
    },

    data() {
        var predefinedClasses = mw.top().app.templateSettings.predefinedElementStylesManager.getPredefinedClasses();
        var canShowPredefinedStylesClasses = false;
        var predefinedClassesVariables = false;
      //  var predefinedClassesScreenshots = false;
        if (predefinedClasses && predefinedClasses.length > 0) {
            canShowPredefinedStylesClasses = true;
            predefinedClassesVariables = mw.top().app.templateSettings.predefinedElementStylesManager.getPredefinedClassesVaribles(predefinedClasses);
        }

        return {
            canShowPredefinedStylesClasses: canShowPredefinedStylesClasses,
            showPredefinedStylesClasses: false,
            showRadioButtons: false,
            predefinedClassesVariables: predefinedClassesVariables,
        //    predefinedClassesScreenshots: predefinedClassesScreenshots,
            selectedClass: '',
            predefinedClasses: predefinedClasses,
        };
    },
    methods: {
        togglePredefinedStylesClasses() {
            this.showPredefinedStylesClasses = !this.showPredefinedStylesClasses;
            this.emitter.emit('element-style-editor-show', 'predefinedStyleClasses');
        },
        applySelectedClass() {
            if (this.selectedClass && this.activeNode) {
                // Clear existing classes
                var existingClasses = this.activeNode.className;

                //remove all predefined classes
                this.predefinedClasses.forEach(classObj => {
                    if (this.activeNode.classList.contains(classObj)) {
                        this.activeNode.classList.remove(classObj);
                    }
                });

                // add the seleected
                this.activeNode.classList.add(this.selectedClass);
                this.populateCssVariablesEditorForSelectedClass();

this.showRadioButtons = false;
                // Optionally, you can emit an event to indicate changes
                if (mw.top().app) {
                    mw.top().app.registerChangedState(this.activeNode);
                }
            }
        },
        populateStyleEditor(element) {
            if (element && element.nodeType === 1) {
                this.isReady = false;
                this.activeNode = element;
                this.selectedClass = '';
                //get all classes from predefinedClasses and check if eleent has one and set it to active
                this.predefinedClasses.forEach(classObj => {
                    if (element.classList.contains(classObj)) {
                        this.selectedClass = classObj;
                    }
                });
                this.populateCssVariablesEditorForSelectedClass();
                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssVariablesEditorForSelectedClass() {

            if (!this.selectedClass) {
                return;
            }
            //remove vars from other predefined claases if exist
            if(this.predefinedClassesVariables){
                Object.keys(this.predefinedClassesVariables).forEach(key => {
                    if (key !== this.selectedClass) {
                        Object.keys(this.predefinedClassesVariables[key]).forEach(variableKey => {
                         //   this.predefinedClassesVariables[key][variableKey] = '';
                           this.$root.applyPropertyToActiveNode(this.activeNode, variableKey, '');

                        });
                    }
                });
            }



            if (this.selectedClass && this.predefinedClassesVariables[this.selectedClass]) {
                if (this.activeNode) {
                    Object.keys(this.predefinedClassesVariables[this.selectedClass]).forEach(key => {
                        var variableValue = mw.top().app.cssEditor.getPropertyForSelector( this.activeNode, key);


                        if(variableValue){
                            this.predefinedClassesVariables[this.selectedClass][key] = variableValue;
                        }

                    //    this.$root.applyPropertyToActiveNode(this.activeNode, key, this.predefinedClassesVariables[this.selectedClass][key]);
                    });

                }

            }
        },

        handleVariablesChanged(newVariables) {
            if (!this.isReady) {
                return;
            }

            if (this.selectedClass && newVariables && newVariables[this.selectedClass]) {
                this.predefinedClassesVariables[this.selectedClass] = newVariables[this.selectedClass];



                if (this.activeNode) {
                    if(this.predefinedClassesVariables[this.selectedClass]){
                        Object.keys(this.predefinedClassesVariables[this.selectedClass]).forEach(key => {
                            this.$root.applyPropertyToActiveNode(this.activeNode, key, this.predefinedClassesVariables[this.selectedClass][key]);
                        });
                    }
                   // this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);

                }

            }

            //    this.predefinedClassesVariables = newVariables;
            // handle the change here
        }
    },
    mounted() {
        if(this.canShowPredefinedStylesClasses){
            // var predefinedClassesScreenshotsData = mw.top().app.templateSettings.predefinedElementStylesManager.getPredefinedStylesScreenshotUrls();
            // predefinedClassesScreenshotsData.then((data) => {
            //     this.predefinedClassesScreenshots = data;
            // });

            var predefinedClassesStylesheets = mw.top().app.templateSettings.predefinedElementStylesManager.getPredefinedElementStylsheetsFromDocument();
            //append to curent document if stylsheed is not already added

            if(predefinedClassesStylesheets && predefinedClassesStylesheets.length > 0){
                predefinedClassesStylesheets.forEach((stylesheet) => {

                    if(!document.querySelector('link[href="'+stylesheet+'"]')){
                        var link = document.createElement('link');
                        link.href = stylesheet.href;
                        link.rel = 'stylesheet';
                        document.head.appendChild(link);
                    }
                });
            }

        }



        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'predefinedStyleClasses') {
                this.showPredefinedStylesClasses = false;
            }
        });
    },
    watch: {
        // 'predefinedClassesVariables': {
        //     handler() {
        //         console.log('predefinedClassesVariables changed')
        //         console.log(this.predefinedClassesVariables)
        //     },
        //     deep: true
        // },


        '$root.selectedElement': {
            handler(element) {
                if (element) {
                    //is in edit field
                    const isInEditField = mw.top().app.liveEdit.liveEditHelpers.targetIsInEditField(element);
                    const isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(element);
                    if (this.canShowPredefinedStylesClasses) {
                        if (isInEditField && !isInaccessible) {
                            //this.canShowPredefinedStylesClasses = true;
                            this.populateStyleEditor(element);
                        } else {
                            //  this.canShowPredefinedStylesClasses = false;
                        }
                    }
                }
            },
            deep: true
        },
    },
};
</script>
