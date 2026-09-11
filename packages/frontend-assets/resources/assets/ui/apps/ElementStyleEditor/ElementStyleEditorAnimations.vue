<template>
  <!-- task-2026-05-22-48070d / AI-720: removed root v-if="supportsAnimations" so the
       accordion header always renders; empty state shows inside the expanded panel
       when the selected element does not support animations. -->
  <div>

      <div class="d-flex">

          <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="currentColor" d="M4,2A2,2 0 0,0 2,4V14H4V4H14V2H4M8,6A2,2 0 0,0 6,8V18H8V8H18V6H8M20,12V20H12V12H20M20,10H12A2,2 0 0,0 10,12V20A2,2 0 0,0 12,22H20A2,2 0 0,0 22,20V12A2,2 0 0,0 20,10M14,13V19L18,16L14,13Z"></path>
          </svg>

          <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showAnimations }">
              Animations
          </span>
      </div>

      <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
      <div v-if="showAnimations" class="mb-4" @click.stop>

        <template v-if="supportsAnimations">
          <!-- LE redesign (frame 1c) — ~40 enumerated names become a two-tier
               Effect family segmented + grouped Variant dropdown, with When/Speed
               small segmenteds and a non-persisting Preview. The full flat "All
               effects" list + an exact-speed slider survive under More options (it
               re-derives Effect+Variant on selection via the shared v-model, so
               the two never desync). Animations are runtime keyframes → every
               control routes through the MW animation service, not a class. -->

          <!-- Effect family -->
          <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Effect</label>
            <div class="mw-segmented mw-ese-seg">
              <span v-for="e in effects" :key="e.key === null ? 'none' : e.key" class="mw-segmented__cell"
                    :class="{ 'active': activeEffect === e.key, 'is-active': activeEffect === e.key }"
                    role="button" tabindex="0"
                    :aria-pressed="activeEffect === e.key ? 'true' : 'false'"
                    @click="setEffect(e.key)"
                    @keydown.enter.prevent="setEffect(e.key)"
                    @keydown.space.prevent="setEffect(e.key)">{{ e.label }}</span>
            </div>
          </div>

          <template v-if="selectedAnimation">
            <!-- Variant — members of the chosen family. -->
            <DropdownSmall v-model="selectedAnimation" :options="variantOptions" :label="'Variant'"/>

            <!-- When -->
            <div class="form-control-live-edit-label-wrapper">
              <label class="live-edit-label">When</label>
              <div class="mw-segmented mw-ese-seg">
                <span v-for="w in whenPresets" :key="w.key" class="mw-segmented__cell"
                      :class="{ 'active': currentWhen === w.key, 'is-active': currentWhen === w.key }"
                      role="button" tabindex="0"
                      :aria-pressed="currentWhen === w.key ? 'true' : 'false'"
                      @click="setWhen(w.key)"
                      @keydown.enter.prevent="setWhen(w.key)"
                      @keydown.space.prevent="setWhen(w.key)">{{ w.label }}</span>
              </div>
            </div>

            <!-- Speed -->
            <div class="form-control-live-edit-label-wrapper">
              <label class="live-edit-label">Speed</label>
              <div class="mw-segmented mw-ese-seg">
                <span v-for="sp in speedPresets" :key="sp.key" class="mw-segmented__cell"
                      :class="{ 'active': isSpeedActive(sp.s), 'is-active': isSpeedActive(sp.s) }"
                      role="button" tabindex="0"
                      :aria-pressed="isSpeedActive(sp.s) ? 'true' : 'false'"
                      @click="setSpeed(sp.s)"
                      @keydown.enter.prevent="setSpeed(sp.s)"
                      @keydown.space.prevent="setSpeed(sp.s)">{{ sp.label }}</span>
              </div>
            </div>

            <!-- Preview — non-persisting replay. -->
            <div class="form-control-live-edit-label-wrapper">
              <button type="button" class="mw-tool-btn mw-tool-btn--preset mw-ese-preview-btn" @click="preview">Preview</button>
            </div>
          </template>

          <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
              More options
            </summary>
            <SliderSmall v-model="selectedAnimationSpeed" :label="'Exact speed'" :min="0.1" :max="5" :step="0.1" :unit="'s'"/>
            <DropdownSmall v-model="selectedAnimation" :options="animations" :label="'All effects'"/>
          </details>
        </template>

        <!-- AI-720 empty state: selected element does not support animations -->
        <div v-else class="mw-ese-panel-empty-state" aria-live="polite">
          <h3 class="mw-admin-empty-state__heading">No animations available</h3>
          <p class="mw-admin-empty-state__body">Animations apply to sections and modules — select a section in the canvas to add an animation.</p>
        </div>

    </div>

  </div>
</template>

<style scoped>

.animations-selector {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 40px 0;
    margin: 0 7px;
    padding-inline-end: 10px;
    max-height: calc(var(--top100vh) / 2);
    min-height: 200px;
    overflow: auto;
    scrollbar-width: thin;
}



.animation-item-wrapper{
    width: calc(40% - 20px);
    aspect-ratio: 1 / 1;
    position: relative;
}
.animation-item{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-repeat: no-repeat;
    background-position: center;
    background-size: calc(100% - 15px) auto;
    cursor: pointer;
    background-color: rgba(122, 122, 122, 0.377);
    border: 4px solid transparent;

}
.animation-item.active {
    border-color: currentColor;
}

.animation-title {
    position: absolute;
    inset-inline: 0;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    text-align: center;
}

</style>

<script>

import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";
import ElementStyleAnimationsApplier from "./ElementStyleAnimationsApplier";

let demoAnimation = null;


const viewSize = () => {
    document.querySelector(':root').style.setProperty('--top100vh', mw.top().win.innerHeight + 'px')
}

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
          {key: null, value: 'None'},
        {"key": "onAppear", "value": "When element appears on screen"},
        {"key": "onHover", "value": "When mouse is over"},
        {"key": "onClick", "value": "When element is clicked"},
      ],

      // LE redesign — Effect families (default variant each), When + Speed presets.
      'effects': [
        {key: null, label: 'None', default: null},
        {key: 'fade', label: 'Fade', default: 'fadeIn'},
        {key: 'slide', label: 'Slide', default: 'slideInUp'},
        {key: 'bounce', label: 'Bounce', default: 'bounceIn'},
        {key: 'attention', label: 'Attention', default: 'pulse'},
      ],
      'whenPresets': [
        {key: 'onAppear', label: 'On appear'},
        {key: 'onHover', label: 'On hover'},
        {key: 'onClick', label: 'On click'},
      ],
      'speedPresets': [
        {key: 'slow', label: 'Slow', s: 2},
        {key: 'normal', label: 'Normal', s: 1},
        {key: 'fast', label: 'Fast', s: 0.5},
      ],
      // Attention-seekers (family = 'attention'); everything else is derived by
      // its fadeIn/slideIn/bounceIn prefix, or 'other' (All effects only).
      'attentionKeys': ['bounce', 'flash', 'pulse', 'rubberBand', 'shakeX', 'shakeY',
        'headShake', 'swing', 'tada', 'wobble', 'jello', 'heartBeat'],

      'animations': [
          {key: null, value: 'None'},
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
        /*{"key": "rotateInDownLeft", "value": "Rotate In Down Left"},
        {"key": "rotateInDownRight", "value": "Rotate In Down Right"},
        {"key": "zoomIn", "value": "Zoom In"},
        {"key": "zoomInDown", "value": "Zoom In Down"},
        {"key": "zoomInLeft", "value": "Zoom In Left"},
        {"key": "zoomInRight", "value": "Zoom In Right"},
        {"key": "zoomInUp", "value": "Zoom In Up"},*/
        {"key": "slideInDown", "value": "Slide In Down"},
        {"key": "slideInLeft", "value": "Slide In Left"},
        {"key": "slideInRight", "value": "Slide In Right"},
        {"key": "slideInUp", "value": "Slide In Up"},
      ],
    }
  },
  computed: {
      // LE redesign — derive the Effect family from the selected animation so the
      // segmented, Variant dropdown and All-effects escape hatch never desync.
      activeEffect: function () {
          return this._familyOf(this.selectedAnimation);
      },
      variantOptions: function () {
          var fam = this.activeEffect;
          if (!fam || fam === 'other') {
              // still let the current 'other' pick show as its own single option
              if (this.selectedAnimation) {
                  var one = this.animations.find(a => a.key === this.selectedAnimation);
                  return one ? [one] : [];
              }
              return [];
          }
          var self = this;
          return this.animations.filter(a => a.key && self._familyOf(a.key) === fam);
      },
      currentWhen: function () {
          return this.selectedAnimationWhenAppear || 'onAppear';
      },
  },

  methods: {
      toggleAnimations: function () {
          this.showAnimations = !this.showAnimations;
          this.emitter.emit('element-style-editor-show', 'animations');
      },
      // LE redesign — family detection + primary-control setters.
      _familyOf: function (key) {
          if (!key) return null;
          if (this.attentionKeys.indexOf(key) !== -1) return 'attention';
          if (key.indexOf('fadeIn') === 0) return 'fade';
          if (key.indexOf('slideIn') === 0) return 'slide';
          if (key.indexOf('bounceIn') === 0) return 'bounce';
          return 'other';
      },
      setEffect: function (family) {
          if (!family) {
              this.selectedAnimation = null; // watcher → removeAnimations
              return;
          }
          var e = this.effects.find(x => x.key === family);
          // keep the current variant if it already belongs to this family
          if (this._familyOf(this.selectedAnimation) !== family) {
              this.selectedAnimation = e ? e.default : null;
          }
      },
      setWhen: function (key) {
          this.selectedAnimationWhenAppear = key;
      },
      setSpeed: function (s) {
          this.selectedAnimationSpeed = s;
      },
      isSpeedActive: function (s) {
          return parseFloat(this.selectedAnimationSpeed) === s;
      },
      preview: function () {
          if (!this.activeNode || !this.selectedAnimation) return;
          ElementStyleAnimationsApplier.previewAnimation(this.activeNode, {
              animation: this.selectedAnimation,
              speed: this.selectedAnimationSpeed || 1,
              when: this.selectedAnimationWhenAppear || 'onAppear',
          });
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

    demo(e) {
        clearTimeout(demoAnimation);
        const triggers = ['mouseenter', 'touchstart'];
        if (triggers.indexOf(e.type) !== -1) {
            const target = e.target;
            demoAnimation = setTimeout((target) => {
                const animation = target.dataset.animation.trim();
                target.classList.add('animate__animated', `animate__${animation}`);

                setTimeout( () => {

                    target.classList.remove('animate__animated', `animate__${animation}`);

                }, 600);

            }, 300, target);
        }
    },
    selectAnimation(animation) {

        this.selectedAnimation = animation;
    },
    setAnimation: function () {

      if (this.activeNode) {

        // LE redesign — None / cleared selection removes the animation.
        if (!this.selectedAnimation) {
          ElementStyleAnimationsApplier.removeAnimations(this.activeNode);
          try { if (mw.top().app) mw.top().app.registerChange(this.activeNode); } catch (e) { /* noop */ }
          return;
        }

        var speed = this.selectedAnimationSpeed ? this.selectedAnimationSpeed : 1;
        var when = this.selectedAnimationWhenAppear ? this.selectedAnimationWhenAppear : 'onAppear';
        var animation = {
          animation: this.selectedAnimation,
          speed: speed,
          when: when,
        }


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

  beforeUnmount() {
    mw.top().win.removeEventListener('resize', viewSize);
  },
  mounted() {
        mw.top().win.addEventListener('resize', viewSize);
        viewSize()

      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (elementStyleEditorShow !== 'animations') {
              this.showAnimations = false;
          } else {
              this.showBackground = true;
              if (this.$root.selectedElement) {
                  this.populateStyleEditor(this.$root.selectedElement);
              }
          }
      });

    // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
    //   var document = element.ownerDocument;
    //   var documentWindow = element.ownerDocument.defaultView;
    //
    //
    //   this.populateStyleEditor(element)
    // });
  },


  watch: {

      '$root.selectedElement': {
          handler: function (element) {
              if(element) {
                  this.populateStyleEditor(element);
              }
          },
          deep: true
      },


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
