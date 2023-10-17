<template>

    <div>
        <b class="mw-admin-action-links" v-on:click="toggleRoundedCorners">
            Rounded corners
        </b>
    </div>

    <div v-if="showRoundedCorners">
        <div class="d-flex flex-column gap-3">

            <BorderRadius v-model="borderRadius"></BorderRadius>
        </div>
    </div>
</template>

<script>
import BorderRadius from "./components/BorderRadius.vue";

export default {
    components: {BorderRadius},
    data() {
        return {
            'showRoundedCorners': false,
            'activeNode': null,
            'isReady': false,
            'borderRadius': {
                borderTopLeftRadius: '',
                borderTopRightRadius: '',
                borderBottomLeftRadius: '',
                borderBottomRightRadius: '',
            },


        };
    },
    methods: {
        toggleRoundedCorners: function () {
            this.showRoundedCorners = !this.showRoundedCorners;
            this.emitter.emit('element-style-editor-show', 'roundedCorners');
        },

        resetAllProperties: function () {

            this.borderRadius = {
                borderTopLeftRadius: '',
                borderTopRightRadius: '',
                borderBottomLeftRadius: '',
                borderBottomRightRadius: '',
            }

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssBorderRadius(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateCssBorderRadius: function (css) {
            if (!css || !css.get) return;
            var borderRadius = css.get.radius(true);

            this.borderTopLeftRadius = borderRadius.tl;
            this.borderTopRightRadius = borderRadius.tr;
            this.borderBottomRightRadius = borderRadius.br;
            this.borderBottomLeftRadius = borderRadius.bl;

            this.borderRadius = {
                borderTopLeftRadius: borderRadius.tl,
                borderTopRightRadius: borderRadius.tr,
                borderBottomLeftRadius: borderRadius.bl,
                borderBottomRightRadius: borderRadius.br,
            }
        },

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

    },
    mounted() {

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'roundedCorners') {
                this.showRoundedCorners = false;
            }
        });

        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {

            this.populateStyleEditor(element)

        });

    },
    watch: {
        borderRadius: function (newValue, oldValue) {


            var borderRadiusValue = '';
            if (newValue.borderTopLeftRadius) {
                borderRadiusValue += newValue.borderTopLeftRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderTopRightRadius) {
                borderRadiusValue += newValue.borderTopRightRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderBottomRightRadius) {
                borderRadiusValue += newValue.borderBottomRightRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderBottomLeftRadius) {
                borderRadiusValue += newValue.borderBottomLeftRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }

            this.applyPropertyToActiveNode('border-radius', borderRadiusValue);


        },
    },
};
</script>


