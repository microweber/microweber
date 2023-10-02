<template>
  <div>
    <input v-model="backgroundImage" placeholder="Background Image">
    <br>

      <div class="d-flex justify-content-between">
          <div class="mr-4">Background Color</div>
          <div>
             <ColorPicker v-model="backgroundColor" v-bind:color=backgroundColor   :label="'Background Color'" @change="handleBackgroundColorChange" />
          </div>
      </div>


      <div>
          <Dropdown v-model="backgroundSize" :options="backgroundSizeOptions" :label="'Background Size'"/>
      </div>

      <div>
      <Dropdown v-model="backgroundRepeat" :options="backgroundRepeatOptions" :label="'Background Repeat'"/>
  </div>

      <div>
          <Dropdown v-model="backgroundPosition" :options="backgroundPositionOptions" :label="'Background Position'"/>
      </div>

  </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "../../components/Form/FontPicker.vue";
import ColorPicker from "../../components/Editor/Colors/ColorPicker.vue";
import Slider from '@vueform/slider';

export default {

    components: {ColorPicker, FontPicker, Dropdown, Input, Slider},

  data() {
    return {

        'backgroundPositionOptions': [
            { key: "none", value: "None" },
            { key: "0% 0%", value: "Left Top" },
            { key: "50% 0%", value: "Center Top" },
            { key: "100% 0%", value: "Right Top" },
            { key: "0% 50%", value: "Left Center" },
            { key: "50% 50%", value: "Center Center" },
            { key: "100% 50%", value: "Right Center" },
            { key: "0% 100%", value: "Left Bottom" },
            { key: "50% 100%", value: "Center Bottom" },
            { key: "100% 100%", value: "Right Bottom" }
        ],
        'backgroundRepeatOptions': [
            { key: "none", value: "None" },
            { key: "repeat", value: "repeat" },
            { key: "no-repeat", value: "no-repeat" },
            { key: "repeat-x", value: "repeat horizontally" },
            { key: "repeat-y", value: "repeat vertically" }
        ],
        'backgroundSizeOptions': [
            { key: "none", value: "None" },
            { key: "auto", value: "Auto" },
            { key: "contain", value: "Fit" },
            { key: "cover", value: "Cover" },
            { key: "100% 100%", value: "Scale" }
        ],

      'activeNode': null,
      'isReady': false,
      'backgroundImage': null,
      'backgroundColor': null,
      'backgroundPosition': null,
      'backgroundRepeat': null,
      'backgroundSize': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.backgroundImage = null;
      this.backgroundColor = null;
      this.backgroundPosition = null;
      this.backgroundRepeat = null;
      this.backgroundSize = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssBackground(css);
        this.isReady = true;
      }
    },
    populateCssBackground: function (css) {
      if (!css || !css.get) return;
      var bg = css.get.background();
      this.backgroundImage = bg.image;
      this.backgroundColor = bg.color;
      this.backgroundPosition = bg.position;
      this.backgroundRepeat = bg.repeat;
      this.backgroundSize = bg.size;
    },

    handleBackgroundColorChange: function (color) {
       this.applyPropertyToActiveNode('backgroundColor', color)
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

    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {

      this.populateStyleEditor(element)

    });

  },

  watch: {
    // Background-related property watchers
    backgroundImage: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundImage', newValue);
    },
    backgroundColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundColor', newValue);
    },
    backgroundPosition: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundPosition', newValue);
    },
    backgroundRepeat: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('backgroundRepeat', newValue);
    },
      backgroundSize: function (newValue, oldValue) {
          this.applyPropertyToActiveNode('backgroundSize', newValue);
      },
  },
}
</script>
