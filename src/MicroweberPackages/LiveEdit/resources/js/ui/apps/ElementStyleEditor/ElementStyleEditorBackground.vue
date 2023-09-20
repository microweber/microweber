<template>
  <div>
    <input v-model="backgroundImage" placeholder="Background Image">
    <br>
    <input v-model="backgroundColor" placeholder="Background Color">
    <br>
    <input v-model="backgroundPosition" placeholder="Background Position">
    <br>
    <input v-model="backgroundRepeat" placeholder="Background Repeat">
  </div>
</template>

<script>
export default {
  data() {
    return {
      'activeNode': null,
      'isReady': false,
      'backgroundImage': null,
      'backgroundColor': null,
      'backgroundPosition': null,
      'backgroundRepeat': null,
    };
  },

  methods: {
    resetAllProperties: function () {
      this.backgroundImage = null;
      this.backgroundColor = null;
      this.backgroundPosition = null;
      this.backgroundRepeat = null;
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
  },
}
</script>
