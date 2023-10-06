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

    <div>
        <b class="mw-admin-action-links" v-on:click="toggleBorder">
            Border
        </b>
    </div>

    <div v-if="showBorder">

  <div class="d-flex flex-column gap-3">

      <BorderRadius v-model="borderRadius"></BorderRadius>



      <DropdownSmall v-model="borderPosition" :options="borderPositionOptions" label="Border Position"/>


    <SliderSmall label="Border Size" v-model="borderSize" :min="0" :max="120" :step="5"></SliderSmall>


    <ColorPicker v-model="borderColor" v-bind:color=borderColor :label="'Border Color'"
                 @change="handleBorderColorChange"/>


    <div>
      <DropdownSmall v-model="borderStyle" :options="borderStylesOptions" label="Border Style"/>
    </div>

  </div>
    </div>

</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";
import BorderRadius from "./components/BorderRadius.vue";

import Slider from '@vueform/slider';

export default {

  components: {Dropdown, Input, FontPicker, ColorPicker, Slider, DropdownSmall, SliderSmall, BorderRadius},

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
        {"key": "none", "value": "None"},
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
      'borderRadius': {
        borderTopLeftRadius: '',
        borderTopRightRadius: '',
        borderBottomLeftRadius: '',
        borderBottomRightRadius: '',
      },
      'borderTopLeftRadius': null,
      'borderTopRightRadius': null,
      'borderBottomRightRadius': null,
      'borderBottomLeftRadius': null,
    };
  },

  methods: {
    toggleBorder: function () {
      this.showBorder = !this.showBorder;
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
      this.borderRadius = {
        borderTopLeftRadius: '',
        borderTopRightRadius: '',
        borderBottomLeftRadius: '',
        borderBottomRightRadius: '',
      }
      this.borderTopLeftRadius = null;
      this.borderTopRightRadius = null;
      this.borderBottomRightRadius = null;
      this.borderBottomLeftRadius = null;
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
        this.populateCssBorderRadius(css);


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

    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {

      this.populateStyleEditor(element)

    });

  },

  watch: {
    borderRadius: function (newValue, oldValue) {

      //Proxy(Object) {borderTopLeftRadius: '', borderTopRightRadius: '', borderBottomLeftRadius: '', borderBottomRightRadius: ''}

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


    // borderTopLeftRadius: function (newValue, oldValue) {
    //   this.applyPropertyToActiveNode('borderTopLeftRadius', newValue + 'px');
    // },
    // borderTopRightRadius: function (newValue, oldValue) {
    //   this.applyPropertyToActiveNode('borderTopRightRadius', newValue + 'px');
    // },
    // borderBottomRightRadius: function (newValue, oldValue) {
    //   this.applyPropertyToActiveNode('borderBottomRightRadius', newValue + 'px');
    // },
    // borderBottomLeftRadius: function (newValue, oldValue) {
    //   this.applyPropertyToActiveNode('borderBottomLeftRadius', newValue + 'px');
    // },
  },
}
</script>
