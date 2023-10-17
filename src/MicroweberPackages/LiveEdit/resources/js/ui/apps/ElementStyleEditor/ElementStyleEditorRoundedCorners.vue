<template>

    <div class="d-flex">

        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M19,19h2v2h-2V19 M19,17h2v-2h-2V17 M3,13h2v-2H3V13 M3,17h2v-2H3V17 M3,9h2V7H3V9 M3,5h2V3H3V5 M7,5h2V3H7V5 M15,21h2v-2  h-2V21 M11,21h2v-2h-2V21 M15,21h2v-2h-2V21 M7,21h2v-2H7V21 M3,21h2v-2H3V21 M21,8c0-2.8-2.2-5-5-5h-5v2h5c1.7,0,3,1.3,3,3v5h2V8z"></path>
        </svg>

        <b class="mw-admin-action-links ms-3" v-on:click="toggleRoundedCorners">
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


