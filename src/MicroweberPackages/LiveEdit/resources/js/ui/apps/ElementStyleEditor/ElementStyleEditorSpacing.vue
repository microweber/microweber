

<style>

.mw-ese-holder{
    background-color: #f5f5f5;
    border: 1px solid #cfcfcf;
    transition: .2s;
}

.mw-ese-holder.active{
    background-color: #fff;
    border: 1px solid #0086db;
    box-shadow: 0 0 3px rgba(0, 134, 219, .4);
}



    .mw-ese-margin{

        padding:  35px 50px;
        position: relative;
        display: inline-block;
    }

    .mw-ese-padding{
        width: 100px;
        height: 65px;
        padding: 50px 35px;
        position: relative;
    }
    .mw-element-spacing-editor{
        padding: 10px 20px;
    }

    .mw-element-spacing-editor .input input{
        width: 40px;
        height: 22px;
        border-radius: 3px;
        line-height: 20px;
        border: 1px solid #cfcfcf;
        padding: 0 3px;
        text-align: center;
        font-size: 12px;
    }
    .mw-element-spacing-editor .input{
        position: absolute;

        z-index: 1;
    }
    .mw-ese-top{
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
    .mw-ese-right{
        top: 50%;
        right: -20px;
        transform: translateY(-50%);
    }
    .mw-ese-bottom{
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }
    .mw-ese-left{
        top: 50%;
        left: -20px;
        transform: translateY(-50%);
    }
    .mw-ese-label{
        display: block;
        font-size: 10px;
        text-transform: uppercase;
    }
    .mw-ese-padding .mw-ese-label{
        text-align: center;
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        transform: translateY(-50%);
    }
  </style>

<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M10.6,12l4-4H11V6h7v7h-2V9.4l-4,4V16h8V4H8v8H10.6 M22,2v16H12v4H2V12h4V2H22 M10,14H4v6h6V14z"></path>
        </svg>

        <b class="mw-admin-action-links ms-3" :class="{'active': showSpacing }" v-on:click="toggleSpacing">
            Spacing
        </b>
    </div>

    <div v-if="showSpacing">


    <div class="mw-element-spacing-editor mt-4">
    <span class="mw-ese-label">Margin</span>
    <div class="mw-ese-holder mw-ese-margin">
        <span class="input mw-ese-top"><input type="number" v-model="marginTop"></span>
        <span class="input mw-ese-right"><input type="number" v-model="marginRight"></span>
        <span class="input mw-ese-bottom"><input type="number" v-model="marginBottom"></span>
        <span class="input mw-ese-left"><input type="number" v-model="marginLeft"></span>
        <div class="mw-ese-holder mw-ese-padding">
            <span class="input mw-ese-top"><input type="number" min="0" v-model="paddingTop"></span>
            <span class="input mw-ese-right"><input type="number" min="0" v-model="paddingRight"></span>
            <span class="input mw-ese-bottom"><input type="number" min="0" v-model="paddingBottom"></span>
            <span class="input mw-ese-left"><input type="number" min="0" v-model="paddingLeft"></span>
            <span class="mw-ese-label">Padding</span>
        </div>
    </div>
</div>
</div>

</template>

<script>

export default {


  data() {
    return {
        'showSpacing': false,
      'activeNode': null,
      'isReady': false,

      'marginTop': null,
      'marginRight': null,
      'marginBottom': null,
      'marginLeft': null,

      'paddingTop': null,
      'paddingRight': null,
      'paddingBottom': null,
      'paddingLeft': null,

    };
  },

  methods: {
      toggleSpacing: function () {
        this.showSpacing = !this.showSpacing;
          this.emitter.emit('element-style-editor-show', 'spacing');
    },
    resetAllProperties: function () {
      this.marginTop = null;
      this.marginRight = null;
      this.marginBottom = null;
      this.marginLeft = null;

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
        this.populateCssMargin(css);

          setTimeout(() => {
              this.isReady = true;
          }, 100);
      }
    },

    populateCssPadding: function (css) {
      var padding = css.get.padding(undefined, true);
      this.paddingTop = parseFloat(padding.top);
      this.paddingRight = parseFloat(padding.right);
      this.paddingBottom = parseFloat(padding.bottom);
      this.paddingLeft = parseFloat(padding.left);
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
      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (this.$root.selectedElement) {
              this.populateStyleEditor(this.$root.selectedElement);
          }
      });
      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (elementStyleEditorShow !== 'spacing') {
              this.showSpacing = false;
          }
      });

    // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
    //   this.populateStyleEditor(element)
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

      // Margin-related property watchers
    marginTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginTop', newValue + 'px');
    },
    marginRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginRight', newValue + 'px');
    },
    marginBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginBottom', newValue + 'px');
    },
    marginLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('marginLeft', newValue + 'px');
    },

    // Padding-related property watchers
     paddingTop: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingTop', newValue + 'px');
    },
    paddingRight: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingRight', newValue + 'px');
    },
    paddingBottom: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingBottom', newValue + 'px');
    },
    paddingLeft: function (newValue, oldValue) {
      this.applyPropertyToActiveNode('paddingLeft', newValue + 'px');
    },

  },


}
</script>
