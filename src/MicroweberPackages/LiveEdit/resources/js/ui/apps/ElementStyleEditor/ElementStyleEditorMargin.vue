<template>
  <div>
    <input v-model="marginTop" placeholder="Margin Top">
    <br>
    <input v-model="marginRight" placeholder="Margin Right">
    <br>
    <input v-model="marginBottom" placeholder="Margin Bottom">
    <br>
    <input v-model="marginLeft" placeholder="Margin Left">
  </div>
</template>
<script>

export default {


  data() {
    return {
      'activeNode': null,
      'isReady': false,
      'marginTop': null,
      'marginRight': null,
      'marginBottom': null,
      'marginLeft': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.marginTop = null;
      this.marginRight = null;
      this.marginBottom = null;
      this.marginLeft = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssMargin(css);
        this.isReady = true;
      }
    },
    populateCssMargin: function (css) {
      if (!css || !css.get) return;
      var margin = css.get.margin(undefined, true);
      this.marginTop = parseFloat(margin.top);
      this.marginRight = parseFloat(margin.right);
      this.marginBottom = parseFloat(margin.bottom);
      this.marginLeft = parseFloat(margin.left);
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
    // Margin-related property watchers
    marginTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginTop', newValue);
    },
    marginRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginRight', newValue);
    },
    marginBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginBottom', newValue);
    },
    marginLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginLeft', newValue);
    },
  },


}
</script>


