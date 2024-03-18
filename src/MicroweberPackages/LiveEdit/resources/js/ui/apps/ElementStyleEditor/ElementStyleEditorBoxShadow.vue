<template>
    <div>
        <div class="box-shadow-options">


            <div class="form-group">


                <PredefinedBoxShadowsSelect :predefinedShadows="predefinedShadows"
                                            :selectedShadow="selectedShadow"
                                            @update:selectedShadow="handleShadowChange"/>

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
            <div class="form-group">
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
            </div>


        </div>
    </div>
</template>

<script>

import ColorPicker from "./components/ColorPicker.vue";

import SliderSmall from "./components/SliderSmall.vue";
import PredefinedBoxShadowsSelect from "./components/PredefinedBoxShadowsSelect.vue";

export default {
    components: {ColorPicker, SliderSmall, PredefinedBoxShadowsSelect},

    data() {
        var predefinedShadows = mw.top().app.templateSettings.getPredefinedBoxShadows();

        return {
            'activeNode': null,
            'isReady': false,
            selectedShadow: '',
            boxShadowOptions: {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
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

        handleBoxShadowColorChange(color) {
            if (typeof (color) != 'string') {
                return;
            }
            this.boxShadowOptions.shadowColor = color;
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

            var result = css.get.boxShadow();

            for (let i in result) {
                if (typeof result[i] === 'number') {
                    result[i] = `${result[i]}`;
                }
            }

            var resultCss = '';

            if (result.offsetX) {
                resultCss += result.offsetX.replace('px', '') + 'px ';
                // Remove "px" from the size
                this.boxShadowOptions.horizontalLength = result.offsetX.replace('px', '');
            }
            if (result.offsetY) {
                resultCss += ' ' + result.offsetY.replace('px', '') + 'px ';
                // Remove "px" from the size
                this.boxShadowOptions.verticalLength = result.offsetY.replace('px', '');
            }
            if (result.blurRadius) {
                resultCss += ' ' + result.blurRadius.replace('px', '') + 'px ';
                // Remove "px" from the size
                this.boxShadowOptions.blurRadius = result.blurRadius.replace('px', '');
            }
            if (result.spreadRadius) {
                resultCss += ' ' + result.spreadRadius.replace('px', '') + 'px ';
                // Remove "px" from the size
                this.boxShadowOptions.spreadRadius = result.spreadRadius.replace('px', '');
            }
            if (result.color) {
                resultCss += ' ' + result.color;
                this.boxShadowOptions.shadowColor = result.color;
            }
            if (result.inset) {
                if (result.inset === true) {
                    resultCss += ' ' + 'inset';
                }
                this.boxShadowOptions.inset = result.inset;
            }
            var resultCssTrim = resultCss.replace(/\s+/g, ' '); // replace double space

            resultCssTrim = resultCssTrim.trim();

            if (this.predefinedShadows.some(shadow => shadow.value === resultCssTrim)) {
                this.selectedShadow = resultCssTrim;
            } else if (resultCssTrim === '' || resultCssTrim === 'none' || resultCssTrim === 'initial' || resultCssTrim === 'unset' || resultCssTrim === 'inherit' || resultCssTrim === '0px 0px 0px 0px rgba(0,0,0,0)') {
                this.selectedShadow = '';
            } else {
                this.selectedShadow = 'custom';
            }

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


            // Split the selected shadow value and update corresponding properties
            // Replace 'px' with an empty string
            var selected = this.selectedShadow.replace(/px/g, '');

            var parseShadow = this.parseShadowValues(selected);

            // Split the shadow value into an array
            // const shadowValues = selected.split(' ');
            //
            // // Set the properties based on the split values
            // this.boxShadowOptions.horizontalLength = shadowValues[0];
            // this.boxShadowOptions.verticalLength = shadowValues[1];
            // this.boxShadowOptions.blurRadius = shadowValues[2];
            // this.boxShadowOptions.spreadRadius = shadowValues[3]; // Set spread radius
            // this.boxShadowOptions.shadowColor = shadowValues[4];
            //
            // // Check if there is an inset property
            // if (shadowValues.length > 5) {
            //     this.boxShadowOptions.inset = shadowValues[5];
            // } else {
            //     this.boxShadowOptions.inset = ''; // Reset inset if not present
            // }

            //  Apply the updated shadow
            // this.boxShadowOptions

            this.boxShadowOptions.horizontalLength = parseShadow.horizontalLength;
            this.boxShadowOptions.verticalLength = parseShadow.verticalLength;
            this.boxShadowOptions.blurRadius = parseShadow.blurRadius;
            this.boxShadowOptions.spreadRadius = parseShadow.spreadRadius;
            this.boxShadowOptions.shadowColor = parseShadow.shadowColor;
            this.boxShadowOptions.inset = parseShadow.inset;


            this.applyBoxShadow();
        },

        parseShadowValues(shadowString) {
            // Split the shadow string by spaces
            const shadowValues = shadowString.split(' ');

            // Extract color information
            let colorIndex = shadowValues.findIndex(val => val.startsWith("rgba"));
            const shadowColor = colorIndex !== -1 ? shadowValues[colorIndex] : '';

            // Extract inset property
            let inset = '';
            if (shadowString.includes("inset")) {
                inset = 'inset';
            }

            // Set default values for other properties
            let horizontalLength = '';
            let verticalLength = '';
            let blurRadius = '';
            let spreadRadius = '';

            // If color information is found, parse other values accordingly
            if (colorIndex !== -1) {
                // Parse the values for horizontalLength, verticalLength, blurRadius, and spreadRadius
                const valuesString = shadowValues.slice(0, colorIndex).join(' ');
                const values = valuesString.split(/px|\s+/).filter(val => val !== '');

                if (values.length >= 1) horizontalLength = values[0];
                if (values.length >= 2) verticalLength = values[1];
                if (values.length >= 3) blurRadius = values[2];
                if (values.length >= 4) spreadRadius = values[3] || '';
            }

            // Construct and return the parsed shadow values
            return {
                horizontalLength,
                verticalLength,
                blurRadius,
                spreadRadius,
                shadowColor,
                inset
            };
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




