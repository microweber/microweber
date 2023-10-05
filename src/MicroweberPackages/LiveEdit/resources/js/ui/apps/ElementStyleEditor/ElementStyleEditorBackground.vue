<template>
  <div>
    <br>


    <ColorPicker v-model="backgroundColor" v-bind:color=backgroundColor :label="'Background Color'"
                 @change="handleBackgroundColorChange"/>


    <ImagePicker label="Background Image" v-model="backgroundImage" v-bind:file="backgroundImageUrl"
                 @change="handleBackgroundImageChange"/>

    <DropdownSmall v-model="backgroundSize" :options="backgroundSizeOptions" :label="'Background Size'"/>


    <DropdownSmall v-model="backgroundRepeat" :options="backgroundRepeatOptions" :label="'Background Repeat'"/>


    <DropdownSmall v-model="backgroundPosition" :options="backgroundPositionOptions" :label="'Background Position'"/>


      // checked if backgroundClip == text
        <label for="backgroundClip">Background Clip</label>

      <input type="radio"  name="backgroundClip" value="text" v-model="backgroundClip">
      <input type="radio"  name="backgroundClip" value="border-box" v-model="backgroundClip">
      <input type="radio"  name="backgroundClip" value="content-box" v-model="backgroundClip">



  </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';
import ImagePicker from './components/ImagePicker.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import Slider from '@vueform/slider';
import FilePicker from "../../components/Form/FilePicker.vue";

export default {

  components: {ColorPicker, FontPicker, Dropdown, Input, Slider, FilePicker, ImagePicker, DropdownSmall},

  data() {
    return {

      'backgroundPositionOptions': [
        {key: "none", value: "None"},
        {key: "0% 0%", value: "Left Top"},
        {key: "50% 0%", value: "Center Top"},
        {key: "100% 0%", value: "Right Top"},
        {key: "0% 50%", value: "Left Center"},
        {key: "50% 50%", value: "Center Center"},
        {key: "100% 50%", value: "Right Center"},
        {key: "0% 100%", value: "Left Bottom"},
        {key: "50% 100%", value: "Center Bottom"},
        {key: "100% 100%", value: "Right Bottom"}
      ],
      'backgroundRepeatOptions': [
        {key: "none", value: "None"},
        {key: "repeat", value: "repeat"},
        {key: "no-repeat", value: "no-repeat"},
        {key: "repeat-x", value: "repeat horizontally"},
        {key: "repeat-y", value: "repeat vertically"}
      ],
      'backgroundSizeOptions': [
        {key: "none", value: "None"},
        {key: "auto", value: "Auto"},
        {key: "contain", value: "Fit"},
        {key: "cover", value: "Cover"},
        {key: "100% 100%", value: "Scale"}
      ],

      'activeNode': null,
      'isReady': false,
      'backgroundImage': null,
      'backgroundColor': null,
      'backgroundPosition': null,
      'backgroundRepeat': null,
      'backgroundSize': null,
      'backgroundImageUrl': null,
      'backgroundClip': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.backgroundImage = null;
      this.backgroundImageUrl = null;
      this.backgroundColor = null;
      this.backgroundPosition = null;
      this.backgroundRepeat = null;
      this.backgroundSize = null;
      this.backgroundClip = null;
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

      if (bg.image) {
        if (bg.image.indexOf('url(') !== -1) {
          this.backgroundImageUrl = bg.image.replace('url(', '').replace(')', '');
          //also replace "
          this.backgroundImageUrl = this.backgroundImageUrl.replace(/\"/g, "");
        }
      }
      this.backgroundImage = bg.image;
      this.backgroundColor = bg.color;
      this.backgroundPosition = bg.position;
      this.backgroundRepeat = bg.repeat;
      this.backgroundSize = bg.size;
      this.backgroundClip = bg.clip;
    },

    handleBackgroundColorChange: function (color) {

      if (typeof (color) != 'string') {
        return;
      }
      this.backgroundColor = color
    },
    handleBackgroundImageChange: function (url) {
      var urlVal = url;
      if (url && url != '' && url != 'none' && url != 'inherit' && url != 'initial') {
        //check if contain url(
        this.backgroundImageUrl = url;
        if (url.indexOf('url(') === -1) {
          urlVal = 'url(' + url + ')';
        }
      } else {
        this.backgroundImageUrl = '';
      }
      if (urlVal == null) {
        urlVal = 'none';
      }
      this.backgroundImage = urlVal;
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
      backgroundClip: function (newValue, oldValue) {
        if(newValue == 'text'){
            this.applyPropertyToActiveNode('backgroundClip', 'text');
            this.applyPropertyToActiveNode('webkitBackgroundClip', 'text');
            this.applyPropertyToActiveNode('color', 'rgba(0,0,0,0)');
        } else {
            this.applyPropertyToActiveNode('backgroundClip', newValue);
            this.applyPropertyToActiveNode('webkitBackgroundClip', newValue);
            this.applyPropertyToActiveNode('color', '');

        }
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
