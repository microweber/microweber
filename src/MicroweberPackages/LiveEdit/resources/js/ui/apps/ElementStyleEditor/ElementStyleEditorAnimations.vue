<template>
    <div v-if="supportsAnimations">

        <DropdownSmall v-model="selectedAnimation" :options="animations" :label="'Animation'"/>


    </div>
</template>

<script>

import DropdownSmall from "./components/DropdownSmall.vue";

export default {
    components: {DropdownSmall},

    data() {
        return {
            'activeNode': null,
            'isReady': false,
            'selectedAnimation': false,
            'supportsAnimations': false,

            'animations': [
                {"key": "none", "value": "None"},
                {"key": "bounce", "value": "Bounce"},
                {"key": "flash", "value": "Flash"},
                {"key": "pulse", "value": "Pulse"},
                {"key": "rubberBand", "value": "Rubber Band"},
                {"key": "shakeX", "value": "Shake X"},
                {"key": "shakeY", "value": "Shake Y"},
                {"key": "headShake", "value": "Head Shake"},
                {"key": "swing", "value": "Swing"},
                {"key": "tada", "value": "Tada"},
                {"key": "wobble", "value": "Wobble"},
                {"key": "jello", "value": "Jello"},
                {"key": "heartBeat", "value": "Heart Beat"},
                {"key": "flip", "value": "Flip"},
                {"key": "flipInX", "value": "Flip In X"},
                {"key": "flipInY", "value": "Flip In Y"},
                {"key": "hinge", "value": "Hinge"},
                {"key": "jackInTheBox", "value": "Jack In The Box"},
                {"key": "rollIn", "value": "Roll In"},
                {"key": "backInDown", "value": "Back In Down"},
                {"key": "backInLeft", "value": "Back In Left"},
                {"key": "backInRight", "value": "Back In Right"},
                {"key": "backInUp", "value": "Back In Up"},
                {"key": "bounceIn", "value": "Bounce In"},
                {"key": "bounceInDown", "value": "Bounce In Down"},
                {"key": "bounceInLeft", "value": "Bounce In Left"},
                {"key": "bounceInRight", "value": "Bounce In Right"},
                {"key": "bounceInUp", "value": "Bounce In Up"},
                {"key": "fadeIn", "value": "Fade In"},
                {"key": "fadeInDown", "value": "Fade In Down"},
                {"key": "fadeInDownBig", "value": "Fade In Down Big"},
                {"key": "fadeInLeft", "value": "Fade In Left"},
                {"key": "fadeInLeftBig", "value": "Fade In Left Big"},
                {"key": "fadeInRight", "value": "Fade In Right"},
                {"key": "fadeInRightBig", "value": "Fade In Right Big"},
                {"key": "fadeInUp", "value": "Fade In Up"},
                {"key": "fadeInUpBig", "value": "Fade In Up Big"},
                {"key": "fadeInTopLeft", "value": "Fade In Top Left"},
                {"key": "fadeInTopRight", "value": "Fade In Top Right"},
                {"key": "fadeInBottomLeft", "value": "Fade In Bottom Left"},
                {"key": "fadeInBottomRight", "value": "Fade In Bottom Right"},
                {"key": "lightSpeedInRight", "value": "LightSpeed In Right"},
                {"key": "lightSpeedInLeft", "value": "LightSpeed In Left"},
                {"key": "rotateIn", "value": "Rotate In"},
                {"key": "rotateInDownLeft", "value": "Rotate In Down Left"},
                {"key": "rotateInDownRight", "value": "Rotate In Down Right"},
                {"key": "zoomIn", "value": "Zoom In"},
                {"key": "zoomInDown", "value": "Zoom In Down"},
                {"key": "zoomInLeft", "value": "Zoom In Left"},
                {"key": "zoomInRight", "value": "Zoom In Right"},
                {"key": "zoomInUp", "value": "Zoom In Up"},
                {"key": "slideInDown", "value": "Slide In Down"},
                {"key": "slideInLeft", "value": "Slide In Left"},
                {"key": "slideInRight", "value": "Slide In Right"},
                {"key": "slideInUp", "value": "Slide In Up"},
            ],
        }
    },
    methods: {
        resetAllProperties: function () {
            this.selectedAnimation = null;
        },

        populateActiveAnimation: function (node) {

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                this.isReady = false;

                this.resetAllProperties();
                this.activeNode = node;
                this.populateActiveAnimation(node);

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
    },

    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            this.populateStyleEditor(element)
        });
    },



}


</script>
