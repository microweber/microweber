<template>
  <div>
    <input v-model="borderPosition" placeholder="Border Position">
    <br>
    <input v-model="borderSize" placeholder="Border Size">
    <br>
    <input v-model="borderColor" placeholder="Border Color">
    <br>
    <input v-model="borderStyle" placeholder="Border Style">
    <br>
    <input v-model="borderTopLeftRadius" placeholder="Border Top Left Radius">
    <br>
    <input v-model="borderTopRightRadius" placeholder="Border Top Right Radius">
    <br>
    <input v-model="borderBottomRightRadius" placeholder="Border Bottom Right Radius">
    <br>
    <input v-model="borderBottomLeftRadius" placeholder="Border Bottom Left Radius">
  </div>
</template>

<script>
export default {
  data() {
    return {
      'activeNode': null,
      'isReady': false,
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
      this.applyPropertyToActiveNode('borderSize', newValue);
    },
    borderColor: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderColor', newValue);
    },
    borderStyle: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderStyle', newValue);
    },
    borderTopLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopLeftRadius', newValue);
    },
    borderTopRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderTopRightRadius', newValue);
    },
    borderBottomRightRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomRightRadius', newValue);
    },
    borderBottomLeftRadius: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('borderBottomLeftRadius', newValue);
    },
  },
}
</script>
