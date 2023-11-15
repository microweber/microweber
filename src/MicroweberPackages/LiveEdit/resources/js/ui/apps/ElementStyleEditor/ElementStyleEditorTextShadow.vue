<template>
    <div>

        <div class="text-shadow-options">
            <div class="form-group">
                <SliderSmall label="Horizontal Shadow Length" v-model="textShadowOptions.horizontalLength" :min="0"
                             :max="30" :step="1"></SliderSmall>
                <SliderSmall label="Vertical Shadow Length" v-model="textShadowOptions.verticalLength" :min="0"
                             :max="30" :step="1"></SliderSmall>
                <SliderSmall label="Blur Radius" v-model="textShadowOptions.blurRadius" :min="0" :max="30"
                             :step="1"></SliderSmall>

                <ColorPicker v-model="textShadowOptions.shadowColor" :color="textShadowOptions.shadowColor"
                             :label="'Color'" @change="handleTextShadowColorChange"/>
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
            activeNode: null,
            isReady: false,
            showTextShadowOptions: false,

            textShadowOptions: {
                horizontalLength: "",
                verticalLength: "",
                blurRadius: "",
                shadowColor: "",

            },
        };
    },

    mounted() {

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'showTextShadowOptions') {
                this.showTextShadowOptions = false;
            } else {
                this.showTextShadowOptions = true;
            }
        });

        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            this.populateStyleEditor(element)
        });
    },

    watch: {

        textShadowOptions: {
            handler: function (newVal, oldVal) {
                this.applyTextShadow();
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
                mw.top().app.dispatch("mw.elementStyleEditor.applyCssPropertyToNode", {
                    node: this.activeNode,
                    prop: prop,
                    val: val,
                });
            }
        },

        handleTextShadowColorChange(color) {
            if (typeof color !== "string") {
                return;
            }
            this.textShadowOptions.shadowColor = color;
        },

        toggleTextShadow: function () {
            this.showTextShadowOptions = !this.showTextShadowOptions;
        },

        resetAllProperties: function () {
            this.textShadowOptions = {
                horizontalLength: "",
                verticalLength: "",
                blurRadius: "",
                shadowColor: "",
            };
        },

        populateStyleEditor: function (node) {
            if (node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssTextShadow(css);

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },


        populateCssTextShadow: function (css) {
            if (!css || !css.get) return;

            var result = css.get.textShadow();

            for (let i in result) {
                if (typeof result[i] === "number") {
                    result[i] = `${result[i]}`;
                }
            }

            console.log('populateCssTextShadow')
            console.log(result)

            if (result.color) {
                this.textShadowOptions.shadowColor = result.color;
            }
            if (result.offsetX) {
                // Remove "px" from the size
                this.textShadowOptions.horizontalLength = result.offsetX.replace("px", "");
            }
            if (result.offsetY) {
                // Remove "px" from the size
                this.textShadowOptions.verticalLength = result.offsetY.replace("px", "");
            }
            if (result.blurRadius) {
                // Remove "px" from the size
                this.textShadowOptions.blurRadius = result.blurRadius.replace("px", "");
            }
            if (result.spreadRadius) {
                // Remove "px" from the size
                this.textShadowOptions.spreadRadius = result.spreadRadius.replace("px", "");
            }

        },


        applyTextShadow() {
            if (!this.isReady) {
                return;
            }

            const {
                horizontalLength,
                verticalLength,
                blurRadius,
                shadowColor,
                inset,
            } = this.textShadowOptions;

            const textShadowValue = `${horizontalLength}px ${verticalLength}px ${blurRadius}px ${shadowColor}`;
            console.log(textShadowValue)
            this.applyPropertyToActiveNode("textShadow", textShadowValue);
        },
    },
};
</script>
