<template>
  <div>
    <input v-model="paddingTop" placeholder="Padding Top">
    <br>
    <input v-model="paddingRight" placeholder="Padding Right">
    <br>
    <input v-model="paddingBottom" placeholder="Padding Bottom">
    <br>
    <input v-model="paddingLeft" placeholder="Padding Left">
  </div>
</template>

<script>
export default {
  data() {
    return {
      'activeNode': null,
      'isReady': false,
      'paddingTop': null,
      'paddingRight': null,
      'paddingBottom': null,
      'paddingLeft': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.paddingTop = null;
      this.paddingRight = null;
      this.paddingBottom = null;
      this.paddingLeft = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssPadding(css);
        this.isReady = true;
      }
    },
    populateCssPadding: function (css) {
      var padding = css.get.padding(undefined, true);
      this.paddingTop = parseFloat(padding.top);
      this.paddingRight = parseFloat(padding.right);
      this.paddingBottom = parseFloat(padding.bottom);
      this.paddingLeft = parseFloat(padding.left);
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
    // Padding-related property watchers
    paddingTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingTop', newValue);
    },
    paddingRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingRight', newValue);
    },
    paddingBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingBottom', newValue);
    },
    paddingLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingLeft', newValue);
    },
  },


}
</script>
