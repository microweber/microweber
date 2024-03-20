<template>
    <div>
        <div class="box-shadow-options">
            <PredefinedBoxShadowsSelect :predefinedShadows="predefinedShadows"
                                        :selectedShadow="selectedShadow"
                                        @update:selectedShadow="handleShadowChange"/>
            <div class="form-group" v-if="canCustomizeBoxShadowOptions">


                <div v-for="(boxShadowOptionsGroup, index) in boxShadowOptionsGroups" :key="index">
                    <BoxShadowOptionsGroup :boxShadowOptionsGroup="boxShadowOptionsGroup" :index="index"
                                           @changeOptions="handleBoxShadowOptionsChange"/>
                </div>


                <!--                <div v-for="(boxShadowOptionsGroup, index) in boxShadowOptionsGroups" :key="index">
                                    <ColorPicker v-model="boxShadowOptionsGroup.shadowColor"
                                                 v-bind:color=boxShadowOptionsGroup.shadowColor
                                                 :label="'Color'"
                                                 @change="handleBoxShadowColorChange(boxShadowOptionsGroup.shadowColor, index)"/>
                                    <SliderSmall label="Horizontal Shadow Length" v-model="boxShadowOptionsGroup.horizontalLength"
                                                 :min="-300"
                                                 :max="300" :step="1"></SliderSmall>
                                    <SliderSmall label="Vertical Shadow Length" v-model="boxShadowOptionsGroup.verticalLength"
                                                 :min="-300" :max="300" :step="1"></SliderSmall>
                                    <SliderSmall label="Blur Radius" v-model="boxShadowOptionsGroup.blurRadius" :min="0" :max="30"
                                                 :step="1"></SliderSmall>
                                    <SliderSmall label="Spread Radius" v-model="boxShadowOptionsGroup.spreadRadius" :min="0" :max="30"
                                                 :step="1"></SliderSmall>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label for="inset"> Inset</label>
                                            <input style="margin-inline: -15px 8px;"
                                                   type="checkbox"
                                                   id="inset"
                                                   class="form-check-input"
                                                   v-model="boxShadowOptionsGroup.inset"
                                                   @change="applyBoxShadow"
                                            />
                                        </div>
                                    </div>-->
            </div>


            <!--            <div class="form-group" v-if="canCustomizeBoxShadowOptions">


                            <SliderSmall label="Horizontal Shadow Length" v-model="boxShadowOptions.horizontalLength" :min="-300"
                                         :max="300" :step="1"></SliderSmall>
                            <SliderSmall label="Vertical Shadow Length" v-model="boxShadowOptions.verticalLength" :min="-300"
                                         :max="300" :step="1"></SliderSmall>
                            <SliderSmall label="Blur Radius" v-model="boxShadowOptions.blurRadius" :min="0" :max="30"
                                         :step="1"></SliderSmall>
                            <SliderSmall label="Spread Radius" v-model="boxShadowOptions.spreadRadius" :min="0" :max="30"
                                         :step="1"></SliderSmall>


                            <ColorPicker v-model="boxShadowOptions.shadowColor" v-bind:color=boxShadowOptions.shadowColor
                                         :label="'Color'"
                                         @change="handleBoxShadowColorChange"/>

                        </div>


                        <div class="form-group" v-if="canCustomizeBoxShadowOptions">
                            <div class="form-check">
                                <label for="inset"> Inset</label>
                                <input style="margin-inline: -15px 8px;"
                                       type="checkbox"
                                       id="inset"
                                       class="form-check-input"
                                       v-model="boxShadowOptions.inset"
                                       @change="applyBoxShadow"
                                />

                            </div>
                        </div>-->


        </div>
    </div>
</template>

<script>

import ColorPicker from "./components/ColorPicker.vue";

import SliderSmall from "./components/SliderSmall.vue";
import PredefinedBoxShadowsSelect from "./components/PredefinedBoxShadowsSelect.vue";
import BoxShadowOptionsGroup from "./components/BoxShadowOptionsGroup.vue";

export default {
    components: {ColorPicker, SliderSmall, PredefinedBoxShadowsSelect, BoxShadowOptionsGroup},

    data() {
        var predefinedShadows = mw.top().app.templateSettings.getPredefinedBoxShadows();

        return {
            'activeNode': null,
            'isReady': false,
            selectedShadow: '',
            canCustomizeBoxShadowOptions: false,
            boxShadowOptions: {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
            },
            boxShadowOptionsGroups: {
                0: {
                    horizontalLength: '',
                    verticalLength: '',
                    blurRadius: '',
                    spreadRadius: '',
                    shadowColor: '',
                    inset: '',
                }
            },
            predefinedShadows: predefinedShadows
        };
    },

    mounted() {
        this.emitter.on("element-style-editor-show", () => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });


        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'showBoxShadowOptions') {
                this.showBoxShadowOptions = false;
            } else {
                this.showBoxShadowOptions = true;
            }
        });


        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //   this.populateStyleEditor(element)
        // });
    },
    watch: {

        '$root.selectedElement': {
            handler: function (element) {
                if (element) {

                    this.populateStyleEditor(element);
                }
            },
            deep: true
        },


        boxShadowOptions: {
            handler: function (newVal, oldVal) {
                this.applyBoxShadow();
            },
            deep: true,
        },
    },
    methods: {
        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
            }
        },
        handleBoxShadowOptionsChange(updatedOptions, index) {
            this.boxShadowOptionsGroups[index] = updatedOptions;

        },
        handleBoxShadowColorChange(color, index) {
            if (typeof (color) != 'string') {
                return;
            }
            //   this.boxShadowOptions.shadowColor = color;
            this.boxShadowOptionsGroups[index].shadowColor = color;
            this.applyBoxShadow();
        },

        toggleBoxShadow: function () {
            this.showBoxShadowOptions = !this.showBoxShadowOptions;
            this.emitter.emit('element-style-editor-show', 'boxShadowOptions');
        },

        resetAllProperties: function () {
            this.selectedShadow = '';
            this.boxShadowOptions = {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
            }

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssBoxShadow(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssBoxShadow: function (css) {
            if (!css || !css.get) return;
            if (!this.activeNode || !this.activeNode.style) return;


            var boxShadowVal = getComputedStyle(this.activeNode)
            boxShadowVal = boxShadowVal.getPropertyValue('box-shadow');


            if (boxShadowVal === '' || boxShadowVal === 'none' || boxShadowVal === 'initial' || boxShadowVal === 'unset' || boxShadowVal === 'inherit' || boxShadowVal === '0px 0px 0px 0px rgba(0,0,0,0)') {
                this.selectedShadow = '';
                return;
            } else {
                if (this.predefinedShadows.some(shadow => shadow.value === boxShadowVal)) {
                    this.selectedShadow = boxShadowVal;
                } else {
                    this.selectedShadow = 'custom';
                }
                //this.selectedShadow = boxShadowVal;
                //this.selectedShadow = boxShadowVal;
                //check if in predefined
                // if (this.predefinedShadows.some(shadow => shadow.value === boxShadowVal)) {
                //     this.selectedShadow = boxShadowVal;
                // } else if (this.predefinedShadows.some(
                //     shadow => shadow.value.replace('0px', '') === boxShadowVal || shadow.value.replace('0px 0px 0px 0px', 'none') === boxShadowVal
                // )) {
                //     this.selectedShadow = boxShadowVal;
                // } else {
                //     this.selectedShadow = 'custom';
                // }
            }

            // var result = css.get.boxShadow();
            //
            // for (let i in result) {
            //     if (typeof result[i] === 'number') {
            //         result[i] = `${result[i]}`;
            //     }
            // }
            //
            // var resultCss = '';
            //
            // if (result.offsetX) {
            //     resultCss += result.offsetX.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.horizontalLength = result.offsetX.replace('px', '');
            // }
            // if (result.offsetY) {
            //     resultCss += ' ' + result.offsetY.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.verticalLength = result.offsetY.replace('px', '');
            // }
            // if (result.blurRadius) {
            //     resultCss += ' ' + result.blurRadius.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.blurRadius = result.blurRadius.replace('px', '');
            // }
            // if (result.spreadRadius) {
            //     resultCss += ' ' + result.spreadRadius.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.spreadRadius = result.spreadRadius.replace('px', '');
            // }
            // if (result.color) {
            //     resultCss += ' ' + result.color;
            //     this.boxShadowOptions.shadowColor = result.color;
            // }
            // if (result.inset) {
            //     if (result.inset === true) {
            //         resultCss += ' ' + 'inset';
            //     }
            //     this.boxShadowOptions.inset = result.inset;
            // }
            // var resultCssTrim = resultCss.replace(/\s+/g, ' '); // replace double space
            //
            // resultCssTrim = resultCssTrim.trim();
            //
            // if (this.predefinedShadows.some(shadow => shadow.value === resultCssTrim)) {
            //     this.selectedShadow = resultCssTrim;
            // } else if (resultCssTrim === '' || resultCssTrim === 'none' || resultCssTrim === 'initial' || resultCssTrim === 'unset' || resultCssTrim === 'inherit' || resultCssTrim === '0px 0px 0px 0px rgba(0,0,0,0)') {
            //     this.selectedShadow = '';
            // } else {
            //     this.selectedShadow = 'custom';
            // }

        },
        handleShadowChange(selectedShadow) {
            if (!this.isReady) {
                return;
            }
            this.selectedShadow = selectedShadow;

            if (this.selectedShadow === '') {
                this.resetAllProperties();
                this.applyBoxShadow();
                return;
            }
            if (this.selectedShadow === 'custom') {
                return;
            }

            // this.applyPropertyToActiveNode('boxShadow', selectedShadow);

            // this.canCustomizeBoxShadowOptions = false;
            //  this.canCustomizeBoxShadowOptions = 1;
            var selected = this.selectedShadow;
            var parseShadowValues = this.parseShadowValues(selected);

            this.applyPropertyToActiveNode('boxShadow', selectedShadow);

            if (this.canCustomizeBoxShadowOptions) {
                if (parseShadowValues.length > 0) {
                    this.boxShadowOptionsGroups = parseShadowValues;
                }
            }
            //
            //
            // //if more than 1 shadow
            // if (parseShadowValues.length > 1) {
            //
            //     this.canCustomizeBoxShadowOptions = false;
            //     return;
            // }
            //
            //
            // if(parseShadowValues[0]){
            //     this.canCustomizeBoxShadowOptions = true;
            //     this.boxShadowOptions.horizontalLength = parseShadowValues[0].horizontalLength.replace(/px/g, '');;
            //     this.boxShadowOptions.verticalLength = parseShadowValues[0].verticalLength.replace(/px/g, '');;
            //     this.boxShadowOptions.blurRadius = parseShadowValues[0].blurRadius.replace(/px/g, '');;
            //     this.boxShadowOptions.spreadRadius = parseShadowValues[0].spreadRadius.replace(/px/g, '');;
            //     this.boxShadowOptions.shadowColor = parseShadowValues[0].shadowColor;
            //     this.boxShadowOptions.inset = parseShadowValues[0].inset;
            //
            //     this.applyBoxShadow();
            // }


        },

        parseShadowValues(shadowString) {
            var shadowValues = mw.top().app.templateSettings.cssBoxShadowsParser.parseBoxShadowValues(shadowString);

            return shadowValues;
        },


        applyBoxShadow() {

            if (!this.isReady) {
                return;
            }

            const {
                horizontalLength,
                verticalLength,
                blurRadius,
                spreadRadius,
                shadowColor,
                inset,
            } = this.boxShadowOptions;


            //  const boxShadowValue = `${inset ? 'inset ' : ''}${horizontalLength} ${verticalLength} ${blurRadius} ${spreadRadius} ${shadowColor}`;
            const boxShadowValue = `${inset ? 'inset ' : ''}${horizontalLength}px ${verticalLength}px ${blurRadius}px ${spreadRadius}px ${shadowColor}`;

            // You can emit this value or perform other actions based on your requirements
            this.applyPropertyToActiveNode('boxShadow', boxShadowValue);
        },
    },
};
</script>




