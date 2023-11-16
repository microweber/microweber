<template>
    <div>
        <div class="box-shadow-options">


            <div class="form-group">


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

export default {
    components: {ColorPicker, SliderSmall},

    data() {
        return {
            'activeNode': null,
            'isReady': false,
            boxShadowOptions: {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
            },
        };
    },

    mounted() {
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
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
                mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                    node: this.activeNode,
                    prop: prop,
                    val: val
                });
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

            console.log('populateCssBoxShadow', result)

            if (result.color) {
                this.boxShadowOptions.shadowColor = result.color;
            }
            if (result.offsetX) {
                // Remove "px" from the size
                this.boxShadowOptions.horizontalLength = result.offsetX.replace('px', '');
            }
            if (result.offsetY) {
                // Remove "px" from the size
                this.boxShadowOptions.verticalLength = result.offsetY.replace('px', '');
            }
            if (result.blurRadius) {
                // Remove "px" from the size
                this.boxShadowOptions.blurRadius = result.blurRadius.replace('px', '');
            }
            if (result.spreadRadius) {
                // Remove "px" from the size
                this.boxShadowOptions.spreadRadius = result.spreadRadius.replace('px', '');
            }
            if (result.inset) {
                this.boxShadowOptions.inset = result.inset;
            }

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
            console.log(boxShadowValue);
            this.applyPropertyToActiveNode('boxShadow', boxShadowValue);
        },
    },
};
</script>




