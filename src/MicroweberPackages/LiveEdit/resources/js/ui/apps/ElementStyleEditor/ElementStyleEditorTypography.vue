<template>
  <div>
    <input v-model="fontSize" placeholder="Font Size">
    <br>
    <input v-model="fontWeight" placeholder="Font Weight">
    <br>
    <input v-model="fontStyle" placeholder="Font Style">
    <br>
    <input v-model="lineHeight" placeholder="Line Height">
    <br>
    <input v-model="fontFamily" placeholder="Font Family">
    <br>
    <input v-model="color" placeholder="Font Color">
  </div>
</template>

<script>

export default {


  data() {
    return {
      'activeNode': null,
      'isReady': false,
      'textAlign': null,
      'fontSize': null,
      'fontWeight': null,
      'fontStyle': null,
      'lineHeight': null,
      'fontFamily': null,
      'color': null,
      'textDecorationIsBold': null,
      'textDecorationIsItalic': null,
      'textDecorationIsUnderline': null,
      'textDecorationIsStrikethrough': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.fontSize = null;
      this.fontWeight = null;
      this.fontStyle = null;
      this.lineHeight = null;
      this.fontFamily = null;
      this.color = null;
      this.textDecorationIsBold = null;
      this.textDecorationIsItalic = null;
      this.textDecorationIsUnderline = null;
      this.textDecorationIsStrikethrough = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;


        this.populateCssTextAlign(css);
        this.populateCssTextDecoration(css);
        this.populateCssFont(css);

        this.isReady = true;
      }
    },


    populateCssTextAlign: function (css) {
      if (!css || !css.get) return;
      var align = css.get.alignNormalize();
      this.textAlign = align;
    },
    populateCssTextDecoration: function (css) {
      if (!css || !css.get) return;
      var is = css.get.is();

      this.textDecorationIsBold = is.bold;
      this.textDecorationIsItalic = is.italic;
      this.textDecorationIsUnderline = is.underlined;
      this.textDecorationIsStrikethrough = is.striked;

    },

    populateCssFont: function (css) {
      if (!css || !css.get) return;

      var font = css.get.font();

      this.fontSize = font.size;
      this.fontWeight = font.weight;
      this.fontStyle = font.style;
      this.lineHeight = font.lineHeight;
      this.fontFamily = font.family;
      this.color = font.color;

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
    // Font-related property watchers
    fontSize: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontSize', newValue);
    },
    fontWeight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontWeight', newValue);
    },
    fontStyle: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontStyle', newValue);
    },
    lineHeight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('lineHeight', newValue);
    },
    fontFamily: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('fontFamily', newValue);
    },
    color: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('color', newValue);
    },
  },


}
</script>


