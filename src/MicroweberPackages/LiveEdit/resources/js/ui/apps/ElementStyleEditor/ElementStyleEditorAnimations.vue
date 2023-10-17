<template>
  <div v-if="supportsAnimations">

      <div class="mb-4 d-flex">

          <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24">
              <path fill="currentColor" d="M4,2A2,2 0 0,0 2,4V14H4V4H14V2H4M8,6A2,2 0 0,0 6,8V18H8V8H18V6H8M20,12V20H12V12H20M20,10H12A2,2 0 0,0 10,12V20A2,2 0 0,0 12,22H20A2,2 0 0,0 22,20V12A2,2 0 0,0 20,10M14,13V19L18,16L14,13Z"></path>
          </svg>

          <b class="mw-admin-action-links ms-3" v-on:click="toggleAnimations">
              Animations
          </b>
      </div>

      <div v-if="showAnimations">

      <DropdownSmall v-model="selectedAnimation" :options="animations" :label="'Animation'"/>

    <div v-if="selectedAnimation">
      <DropdownSmall v-model="selectedAnimationWhenAppear" :options="animationsAppear" :label="'When'"/>

      <SliderSmall v-model="selectedAnimationSpeed" :label="'Speed'" :min="0.1" :max="5" :step="0.1" :unit="'s'"/>


    </div>
    </div>

  </div>
</template>

<script>

import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";
import ElementStyleAnimationsApplier from "./ElementStyleAnimationsApplier";

export default {
  components: {DropdownSmall, SliderSmall},

  data() {
    return {
        'showAnimations': false,
      'activeNode': null,
      'isReady': false,
      'selectedAnimation': false,
      'selectedAnimationSpeed': false,
      'selectedAnimationWhenAppear': false,
      'supportsAnimations': false,

      'animationsAppear': [
        {"key": "none", "value": "None"},
        {"key": "onAppear", "value": "When element appears on screen"},
        {"key": "onHover", "value": "When mouse is over"},
        {"key": "onClick", "value": "When element is clicked"},
      ],

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
      toggleAnimations: function () {
          this.showAnimations = !this.showAnimations;
          this.emitter.emit('element-style-editor-show', 'animations');
      },
    resetAllProperties: function () {
      this.selectedAnimation = null;
      this.selectedAnimationSpeed = 1;
      this.selectedAnimationWhenAppear = null;
    },

    populateActiveAnimation: function (node) {
      var animationData = ElementStyleAnimationsApplier.getAnimation(node);
      if (!animationData) {
        this.resetAllProperties();
        return;
      }

      if (animationData.animation) {
        this.selectedAnimation = animationData.animation;
      } else {
        this.selectedAnimation = null;
      }
      if (animationData.speed) {
        this.selectedAnimationSpeed = animationData.speed;
      } else {
        this.selectedAnimationSpeed = null;
      }
      if (animationData.when) {
        this.selectedAnimationWhenAppear = animationData.when;
      } else {
        this.selectedAnimationWhenAppear = null;
      }
    },
    setAnimation: function () {
      if (this.activeNode) {

        var speed = this.selectedAnimationSpeed ? this.selectedAnimationSpeed : 1;
        var when = this.selectedAnimationWhenAppear ? this.selectedAnimationWhenAppear : 'onAppear';
        var animation = {
          animation: this.selectedAnimation,
          speed: speed,
          when: when,
        }

        mw.log(animation)
        ElementStyleAnimationsApplier.setAnimation(this.activeNode, animation);

      }
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        this.isReady = false;

        this.resetAllProperties();
        this.activeNode = node;
        this.supportsAnimations = ElementStyleAnimationsApplier.supportsAnimations(node);
        this.populateActiveAnimation(node);

        setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
    },
  },

  mounted() {

      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (elementStyleEditorShow !== 'animations') {
              this.showAnimations = false;
          }
      });

    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
      var document = element.ownerDocument;
      var documentWindow = element.ownerDocument.defaultView;


      this.populateStyleEditor(element)
    });
  },


  watch: {
    selectedAnimation: function (val) {
      if (!this.isReady) {
        return;
      }
      this.setAnimation()
    },

    selectedAnimationSpeed: function (val) {
      if (!this.isReady) {
        return;
      }
      this.setAnimation()
    },
    selectedAnimationWhenAppear: function (val) {
      if (!this.isReady) {
        return;
      }
      this.setAnimation()
    }
  }


}


</script>
