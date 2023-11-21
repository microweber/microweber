<style>
.mw-field {
  width: 100%;
}

.mw-field.unit input + input {
  width: 40px;
  padding-inline-start: 0;
  padding-inline-end: 0;
  text-align: center;
}

.rouded-corners {
  padding-bottom: 20px;
}

.rouded-corners .mw-field .mw-field {
  width: 70px;
  margin: 10px 0 5px;
}

.rouded-corners .mw-field .mw-field + .mw-field {
  margin-inline-start: 10px;
}

.angle {
  display: inline-block;
  width: 15px;
  height: 15px;
  border: 1px dotted #ccc;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.angle-top-left {
  border-top-left-radius: 7px;
  border-left: 1px solid #000;
  border-top: 1px solid #000;
}

.angle-top-right {
  border-top-right-radius: 7px;
  border-right: 1px solid #000;
  border-top: 1px solid #000;
}

.angle-bottom-left {
  border-bottom-left-radius: 7px;
  border-left: 1px solid #000;
  border-bottom: 1px solid #000;
}

.angle-bottom-right {
  border-bottom-right-radius: 7px;
  border-right: 1px solid #000;
  border-bottom: 1px solid #000;
}

.s-field-content {
  display: flex;
  flex-direction: column;
}
</style>

<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M15,21h2v-2h-2 M19,21h2v-2h-2 M7,21h2v-2H7 M11,21h2v-2h-2 M19,17h2v-2h-2 M19,13h2v-2h-2 M3,3v18h2V5h16V3 M19,9h2V7h-2"></path>
        </svg>

        <b class="mw-admin-action-links ms-3" :class="{'active': showBorder }" v-on:click="toggleBorder">
            Border
        </b>
    </div>

    <div v-if="showBorder">
    <DropdownSmall v-model="borderStyle" :options="borderStylesOptions" label="Style"/>


    <DropdownSmall v-model="borderPosition" :options="borderPositionOptions" label="Position"/>

    <SliderSmall label="Size" v-model="borderSize" :min="0" :max="30" :step="1"></SliderSmall>

    <ColorPicker v-model="borderColor" v-bind:color=borderColor :label="'Color'"
                 @change="handleBorderColorChange"/>


</div>

</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";

import Slider from '@vueform/slider';

export default {

  components: {Dropdown, Input, FontPicker, ColorPicker, Slider, DropdownSmall, SliderSmall},

  data() {
    return {
        'showBorder': false,
      'activeNode': null,
      'isReady': false,

      'borderPositionOptions': [
        {"key": "all", "value": "All"},
        {"key": "top", "value": "Top"},
        {"key": "right", "value": "Right"},
        {"key": "bottom", "value": "Bottom"},
        {"key": "left", "value": "Left"},
      ],

      'borderStylesOptions': [
        {"key": "none", "value": 'None'},
        {"key": "solid", "value": "Solid"},
        {"key": "dotted", "value": "Dotted"},
        {"key": "dashed", "value": "Dashed"},
        {"key": "double", "value": "Double"},
        {"key": "groove", "value": "Groove"},
        {"key": "ridge", "value": "Ridge"},
        {"key": "inset", "value": "Inset"},
        {"key": "outset", "value": "Outset"}
      ],

      'borderPosition': null,
      'borderSize': null,
      'borderColor': null,
      'borderImage': null,
      'borderImageUrl': null,
      'borderStyle': null,


    };
  },

  methods: {
    toggleBorder: function () {
      this.showBorder = !this.showBorder;
        this.emitter.emit('element-style-editor-show', 'border');
    },
    handleBorderColorChange(color) {
      if (typeof (color) != 'string') {
        return;
      }
      this.borderColor = color;
    },
    resetAllProperties: function () {
      this.borderPosition = null;
      this.borderSize = null;
      this.borderColor = null;
      this.borderStyle = null;

      this.borderImageUrl = null;
      this.borderImage = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssBorder(css);

          setTimeout(() => {
              this.isReady = true;
          }, 100);
      }
    },
    populateCssBorder: function (css) {
      if (!css || !css.get) return;
      var border = css.get.border(true);

      var frst = {};
      for (var i in border) {
        if (border[i].width !== 0) {
          frst = border[i];
          break;
        }
      }
      var size = frst.width || 0;
      var color = frst.color || 'rgba(0,0,0,1)';
      var style = frst.style || 'none';

      this.borderSize = size;
      this.borderColor = color;
      this.borderStyle = style;
      this.borderPosition = 'all';
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
          if (this.$root.selectedElement) {
              this.populateStyleEditor(this.$root.selectedElement);
          }
      });

      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (elementStyleEditorShow !== 'border') {
              this.showBorder = false;
          }
      });

    // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
    //
    //   this.populateStyleEditor(element)
    //
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




    // Border-related property watchers
    borderImageUrl: function (newValue, oldValue) {
      var borderImageValue = '';
      borderImageValue += 'url(' + newValue + ') ';
      borderImageValue += this.borderSize + ' ';
      //    borderImageValue +=  this.borderStyle + ' ';
      borderImageValue += ' space ';
      this.borderImage = borderImageValue;
    },
    borderImage: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('border-image', newValue);
    },

    borderPosition: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderPosition', newValue);
    },
    borderSize: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('border-width', newValue + 'px');
    },
    borderColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('border-color', newValue);
    },
    borderStyle: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('border-style', newValue);
    },



  },
}
</script>
