<style>
.mw-field{
    width: 100%;
}
.mw-field.unit input + input{
    width: 40px;
    padding-inline-start: 0;
    padding-inline-end: 0;
    text-align: center;
}
.rouded-corners {
    padding-bottom: 20px;
  }
.rouded-corners .mw-field .mw-field{
    width: 70px;
    margin: 10px 0 5px;
}
.rouded-corners .mw-field .mw-field + .mw-field{
    margin-inline-start: 10px;
}
.angle{
    display: inline-block;
    width: 15px;
    height: 15px;
    border: 1px dotted #ccc;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.angle-top-left{
    border-top-left-radius: 7px;
    border-left: 1px solid #000;
    border-top: 1px solid #000;
}
.angle-top-right{
    border-top-right-radius: 7px;
    border-right: 1px solid #000;
    border-top: 1px solid #000;
}
.angle-bottom-left{
    border-bottom-left-radius: 7px;
    border-left: 1px solid #000;
    border-bottom: 1px solid #000;
}
.angle-bottom-right{
    border-bottom-right-radius: 7px;
    border-right: 1px solid #000;
    border-bottom: 1px solid #000;
}
</style>

<template>

<div class="rouded-corners">
    <label>Rounded Corners</label>
    <div class="s-field-content">
        <div class="mw-field mw-field-flat">
            <div class="mw-multiple-fields">
                <div class="mw-field mw-field-flat">
                    <input type="text" class="regular order-1" v-model="borderTopLeftRadius" />
                    <span class="mw-field mw-field-flat-prepend order-2">
                      <i class="angle angle-top-left"></i>
                    </span>
                </div>
                <div class="mw-field mw-field-flat">
                    <span class="mw-field mw-field-flat-prepend"><i class="angle angle-top-right"></i></span>
                    <input class="regular" type="text" v-model="borderTopRightRadius" />
                  </div>
            </div>
        </div>
        <div class="mw-field mw-field-flat">
            <div class="mw-multiple-fields">
                <div class="mw-field mw-field-flat">
                    <input class="regular order-1" type="text" v-model="borderBottomLeftRadius" />
                    <span class="mw-field mw-field-flat-prepend order-2"><i class="angle angle-bottom-left"></i>
                    </span>
                </div>
                <div class="mw-field mw-field-flat">
                    <span class="mw-field mw-field-flat-prepend"><i class="angle angle-bottom-right"></i></span>
                    <input class="regular" type="text" v-model="borderBottomRightRadius" />
                  </div>
            </div>
        </div>
    </div>
</div>


  <div>
      <Dropdown v-model="borderPosition" :options="borderPositionOptions" label="Border Position" />
    <br>

      <div>
          <div class="mr-4">Border Size - {{borderSize}}</div>
          <div>
              <Slider
                  :min="6"
                  :max="120"
                  :step="1"
                  :merge="1"
                  :tooltips="false"
                  :tooltipPosition="'right'"
                  v-model="borderSize"
              />
          </div>
      </div>

      <div class="d-flex justify-content-between">
          <div class="mr-4">Border Color</div>
          <div>
              <ColorPicker />
          </div>
      </div>

    <br>
    <Dropdown v-model="borderStyle" :options="borderStylesOptions" label="Border Style" />
    <br>
  </div>

</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "../../components/Form/FontPicker.vue";
import ColorPicker from "../../components/Editor/Colors/ColorPicker.vue";
import Slider from '@vueform/slider';

export default {

  components: {Dropdown, Input, FontPicker, ColorPicker, Slider},

  data() {
    return {
      'activeNode': null,
      'isReady': false,

        'borderPositionOptions': [
            { "key": "all", "value": "All" },
            { "key": "top", "value": "Top" },
            { "key": "right", "value": "Right" },
            { "key": "bottom", "value": "Bottom" },
            { "key": "left", "value": "Left" },
        ],
        
      'borderStylesOptions': [
          { "key": "none", "value": "None" },
          { "key": "solid", "value": "Solid" },
          { "key": "dotted", "value": "Dotted" },
          { "key": "dashed", "value": "Dashed" },
          { "key": "double", "value": "Double" },
          { "key": "groove", "value": "Groove" },
          { "key": "ridge", "value": "Ridge" },
          { "key": "inset", "value": "Inset" },
          { "key": "outset", "value": "Outset" }
      ],

      'borderPosition': null,
      'borderSize': null,
      'borderColor': null,
      'borderStyle': null,
      'borderTopLeftRadius': null,
      'borderTopRightRadius': null,
      'borderBottomRightRadius': null,
      'borderBottomLeftRadius': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.borderPosition = null;
      this.borderSize = null;
      this.borderColor = null;
      this.borderStyle = null;
      this.borderTopLeftRadius = null;
      this.borderTopRightRadius = null;
      this.borderBottomRightRadius = null;
      this.borderBottomLeftRadius = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssBorder(css);
        this.populateCssBorderRadius(css);
        this.isReady = true;
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
    // Border-related property watchers
    borderPosition: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderPosition', newValue);
    },
    borderSize: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderSize', newValue + 'px');
    },
    borderColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderColor', newValue);
    },
    borderStyle: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderStyle', newValue);
    },
    borderTopLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopLeftRadius', newValue + 'px');
    },
    borderTopRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopRightRadius', newValue + 'px');
    },
    borderBottomRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomRightRadius', newValue + 'px');
    },
    borderBottomLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomLeftRadius', newValue + 'px');
    },
  },
}
</script>
