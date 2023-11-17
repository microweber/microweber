<template>

  <div class="d-flex">

    <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
         style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path
                d="M19,19h2v2h-2V19 M19,17h2v-2h-2V17 M3,13h2v-2H3V13 M3,17h2v-2H3V17 M3,9h2V7H3V9 M3,5h2V3H3V5 M7,5h2V3H7V5 M15,21h2v-2  h-2V21 M11,21h2v-2h-2V21 M15,21h2v-2h-2V21 M7,21h2v-2H7V21 M3,21h2v-2H3V21 M21,8c0-2.8-2.2-5-5-5h-5v2h5c1.7,0,3,1.3,3,3v5h2V8z"></path>
        </svg>

    <b class="mw-admin-action-links ms-3" :class="{'active': showRoundedCorners }" v-on:click="toggleRoundedCorners">
      Rounded corners
    </b>
  </div>

  <div v-if="showRoundedCorners">
    <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">

      <label class="live-edit-label" for="borderRadiusSelect">Select predefined border radius:</label>
      <select class="form-control-live-edit-input form-select" id="borderRadiusSelect" v-model="selectedBorderRadius"
              @change="applyPredefinedRadius">
        <option v-for="(radius, key) in predefinedBorderRadiusValues" :key="key" :value="radius.value">{{
            radius.label
          }}
        </option>
      </select>
    </div>

    <div class="d-flex flex-column gap-3">

      <BorderRadius v-model="borderRadius"></BorderRadius>
    </div>
  </div>
</template>

<script>
import BorderRadius from "./components/BorderRadius.vue";

export default {
  components: {BorderRadius},
  data() {
    return {
      'showRoundedCorners': false,
      'activeNode': null,
      'isReady': false,
      'borderRadius': {
        borderTopLeftRadius: '',
        borderTopRightRadius: '',
        borderBottomLeftRadius: '',
        borderBottomRightRadius: '',
      },
      selectedBorderRadius: "",
      predefinedBorderRadiusValues: [
        { label: "None", value: "rounded-none", borderRadius: { tl: 0, tr: 0, bl: 0, br: 0 } },
        { label: "Small", value: "rounded-sm", borderRadius: { tl: 2, tr: 2, bl: 2, br: 2 } },
        { label: "Regular", value: "rounded", borderRadius: { tl: 4, tr: 4, bl: 4, br: 4 } },
        { label: "Medium", value: "rounded-md", borderRadius: { tl: 6, tr: 6, bl: 6, br: 6 } },
        { label: "Large", value: "rounded-lg", borderRadius: { tl: 8, tr: 8, bl: 8, br: 8 } },
        { label: "Extra Large", value: "rounded-xl", borderRadius: { tl: 12, tr: 12, bl: 12, br: 12 } },
        { label: "2XL", value: "rounded-2xl", borderRadius: { tl: 16, tr: 16, bl: 16, br: 16 } },
        { label: "3XL", value: "rounded-3xl", borderRadius: { tl: 24, tr: 24, bl: 24, br: 24 } },
        { label: "3XL", value: "rounded-4xl", borderRadius: { tl: 34, tr: 34, bl: 34, br: 34 } },
        { label: "Full", value: "rounded-full", borderRadius: { tl: 9999, tr: 9999, bl: 9999, br: 9999 } },


      ]

    };
  },
  methods: {
    toggleRoundedCorners: function () {
      this.showRoundedCorners = !this.showRoundedCorners;
      this.emitter.emit('element-style-editor-show', 'roundedCorners');
    },

    applyPredefinedRadius() {
      const selectedRadius = this.predefinedBorderRadiusValues.find(radius => radius.value === this.selectedBorderRadius);

      if (selectedRadius) {
       /// this.borderRadius = selectedRadius.borderRadius;
        const borderRadius = selectedRadius.borderRadius;
        this.applyPropertyToActiveNode('border-top-left-radius', `${borderRadius.tl}px`);
        this.applyPropertyToActiveNode('border-top-right-radius', `${borderRadius.tr}px`);
        this.applyPropertyToActiveNode('border-bottom-left-radius', `${borderRadius.bl}px`);
        this.applyPropertyToActiveNode('border-bottom-right-radius', `${borderRadius.br}px`);

        this.populateStyleEditor(this.activeNode)
      }
    },

    resetAllProperties: function () {

      this.borderRadius = {
        borderTopLeftRadius: '',
        borderTopRightRadius: '',
        borderBottomLeftRadius: '',
        borderBottomRightRadius: '',
      }

    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        var css = mw.CSSParser(node);
        this.isReady = false;
        this.resetAllProperties();
        this.activeNode = node;

        this.populateCssBorderRadius(css);


        setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
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



      // Check if the border-radius is predefined
      const foundRadius = this.predefinedBorderRadiusValues.find((radius) =>
          parseInt(radius.borderRadius.tl) === parseInt(this.borderTopLeftRadius) &&
          parseInt(radius.borderRadius.tr) === parseInt(this.borderTopRightRadius) &&
          parseInt(radius.borderRadius.bl) === parseInt(this.borderBottomLeftRadius) &&
          parseInt(radius.borderRadius.br) === parseInt(this.borderBottomRightRadius)
      );
      if (foundRadius) {
        this.selectedBorderRadius = foundRadius.value;
      } else {
        this.selectedBorderRadius = "custom";
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

      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (this.$root.selectedElement) {
              this.populateStyleEditor(this.$root.selectedElement);
          }
      });


    this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
      if (elementStyleEditorShow !== 'roundedCorners') {
        this.showRoundedCorners = false;
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
    borderRadius: function (newValue, oldValue) {


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
  },
};
</script>


