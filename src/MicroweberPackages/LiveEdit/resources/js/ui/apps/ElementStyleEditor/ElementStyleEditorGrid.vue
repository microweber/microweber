<template>
  <div v-if="hasGrid">


    <div class="mb-4 d-flex">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22H4C2.92,22 2,21.1 2,20V4A2,2 0 0,1 4,2M4,4V11H11V4H4M4,20H11V13H4V20M20,20V13H13V20H20M20,4H13V11H20V4Z"></path></svg>

        <b class="mw-admin-action-links ms-3" :class="{'active': showGridSettings }" v-on:click="toggleGridSettings">
        Grid
      </b>
    </div>

    <div v-if="showGridSettings">

      <div class="form-control-live-edit-label-wrapper d-flex align-items-center gap-2">
        <label class="live-edit-label px-0 col-4">Desktop</label>
        <i class="mdi mdi-monitor col-2" style="font-size: 24px;"></i>
        <div class="col-6">
            <DropdownSmall
                :options="colOptionsDesktop"
                v-model="selectedColDesktop"
            ></DropdownSmall>
        </div>
      </div>

      <div class="form-control-live-edit-label-wrapper d-flex align-items-center gap-2">
        <label class="live-edit-label px-0 col-4">Tablet</label>
        <i class="mdi mdi-tablet col-2" style="font-size: 24px;"></i>
        <div class="col-6">
            <DropdownSmall
                :options="colOptionsTablet"
                v-model="selectedColTablet"
            ></DropdownSmall>
        </div>
      </div>

      <div class="form-control-live-edit-label-wrapper d-flex align-items-center gap-2">
        <label class="live-edit-label px-0 col-4">Mobile</label>
        <i class="mdi mdi-cellphone col-2" style="font-size: 24px;"></i>
        <div class="col-6">
            <DropdownSmall
                :options="colOptionsMobile"
                v-model="selectedColMobile"
            ></DropdownSmall>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DropdownSmall from './components/DropdownSmall.vue';

export default {
  components: {DropdownSmall},
  data() {
    return {
      activeGridNode: null,
      showGridSettings: false,
      isReady: false,
      hasGrid: false,

      colOptionsDesktop: [
        // {key: null, value: 'None'},
        {key: 'col-1', value: 'col-1'},
        {key: 'col-2', value: 'col-2'},
        {key: 'col-3', value: 'col-3'},
        {key: 'col-4', value: 'col-4'},
        {key: 'col-5', value: 'col-5'},
        {key: 'col-6', value: 'col-6'},
        {key: 'col-7', value: 'col-7'},
        {key: 'col-8', value: 'col-8'},
        {key: 'col-9', value: 'col-9'},
        {key: 'col-10', value: 'col-10'},
        {key: 'col-11', value: 'col-11'},
        {key: 'col-12', value: 'col-12'},
        {key: 'col-lg-1', value: 'col-lg-1'},
        {key: 'col-lg-2', value: 'col-lg-2'},
        {key: 'col-lg-3', value: 'col-lg-3'},
        {key: 'col-lg-4', value: 'col-lg-4'},
        {key: 'col-lg-5', value: 'col-lg-5'},
        {key: 'col-lg-6', value: 'col-lg-6'},
        {key: 'col-lg-7', value: 'col-lg-7'},
        {key: 'col-lg-8', value: 'col-lg-8'},
        {key: 'col-lg-9', value: 'col-lg-9'},
        {key: 'col-lg-10', value: 'col-lg-10'},
        {key: 'col-lg-11', value: 'col-lg-11'},
        {key: 'col-lg-12', value: 'col-lg-12'},
      ],

      colOptionsTablet: [
      // {key: null, value: 'None'},
        {key: 'col-md-1', value: 'col-md-1'},
        {key: 'col-md-2', value: 'col-md-2'},
        {key: 'col-md-3', value: 'col-md-3'},
        {key: 'col-md-4', value: 'col-md-4'},
        {key: 'col-md-5', value: 'col-md-5'},
        {key: 'col-md-6', value: 'col-md-6'},
        {key: 'col-md-7', value: 'col-md-7'},
        {key: 'col-md-8', value: 'col-md-8'},
        {key: 'col-md-9', value: 'col-md-9'},
        {key: 'col-md-10', value: 'col-md-10'},
        {key: 'col-md-11', value: 'col-md-11'},
        {key: 'col-md-12', value: 'col-md-12'},
        {key: 'col-sm-1', value: 'col-sm-1'},
        {key: 'col-sm-2', value: 'col-sm-2'},
        {key: 'col-sm-3', value: 'col-sm-3'},
        {key: 'col-sm-4', value: 'col-sm-4'},
        {key: 'col-sm-5', value: 'col-sm-5'},
        {key: 'col-sm-6', value: 'col-sm-6'},
        {key: 'col-sm-7', value: 'col-sm-7'},
        {key: 'col-sm-8', value: 'col-sm-8'},
        {key: 'col-sm-9', value: 'col-sm-9'},
        {key: 'col-sm-10', value: 'col-sm-10'},
        {key: 'col-sm-11', value: 'col-sm-11'},
        {key: 'col-sm-12', value: 'col-sm-12'},
      ],

      colOptionsMobile: [
      // {key: null, value: 'None'},
        {key: 'col-xs-1', value: 'col-xs-1'},
        {key: 'col-xs-2', value: 'col-xs-2'},
        {key: 'col-xs-3', value: 'col-xs-3'},
        {key: 'col-xs-4', value: 'col-xs-4'},
        {key: 'col-xs-5', value: 'col-xs-5'},
        {key: 'col-xs-6', value: 'col-xs-6'},
        {key: 'col-xs-7', value: 'col-xs-7'},
        {key: 'col-xs-8', value: 'col-xs-8'},
        {key: 'col-xs-9', value: 'col-xs-9'},
        {key: 'col-xs-10', value: 'col-xs-10'},
        {key: 'col-xs-11', value: 'col-xs-11'},
        {key: 'col-xs-12', value: 'col-xs-12'},
      ],

      selectedColDesktop: '',
      selectedColTablet: '',
      selectedColMobile: '',
    };
  },

  methods: {
    toggleGridSettings: function () {
      this.showGridSettings = !this.showGridSettings;
      this.emitter.emit('element-style-editor-show', 'grid');
    },
    resetAllProperties: function () {
      this.hasGrid = null;
    },

    populateStyleEditor: function (node) {
      if (node && node.nodeType === 1) {
        this.isReady = false;
        this.resetAllProperties();

          var hasGridParent = mw.top().app.liveEdit.liveEditHelpers.targetGetFirstColElement(node);
          if (hasGridParent) {
              node = hasGridParent;
          }



        // Check for grid classes in the active node
        for (const gridOption of this.colOptionsDesktop) {
          if (node.classList.contains(gridOption.key)) {
            this.hasGrid = true;
            this.activeGridNode = node;
            // Set the selected class name for Desktop breakpoint
            this.selectedColDesktop = gridOption.key;
            break;
          }
        }
        for (const gridOption of this.colOptionsTablet) {
          if (node.classList.contains(gridOption.key)) {
            this.hasGrid = true;
            this.activeGridNode = node;
            // Set the selected class name for Tablet breakpoint
            this.selectedColTablet = gridOption.key;
            break;
          }
        }
        for (const gridOption of this.colOptionsMobile) {
          if (node.classList.contains(gridOption.key)) {
            this.hasGrid = true;
            this.activeGridNode = node;
            // Set the selected class name for Mobile breakpoint
            this.selectedColMobile = gridOption.key;
            break;
          }
        }





          setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
    },

    applyClassToActiveGridNode: function (val) {
      if (!this.isReady) {
        return;
      }
      if (this.activeGridNode) {
        // Remove any existing grid classes from the node
        for (const gridOption of this.colOptionsDesktop) {
          this.activeGridNode.classList.remove(gridOption.key);
        }
        for (const gridOption of this.colOptionsTablet) {
          this.activeGridNode.classList.remove(gridOption.key);
        }
        for (const gridOption of this.colOptionsMobile) {
          this.activeGridNode.classList.remove(gridOption.key);
        }

        // Apply the selected grid class for the corresponding breakpoint
        const selectedGridOption = [...this.colOptionsDesktop, ...this.colOptionsTablet, ...this.colOptionsMobile].find(option => option.key === val);
        if (selectedGridOption) {
          this.activeGridNode.classList.add(selectedGridOption.key);
        }

        if (mw.top().app) {
          mw.top().app.registerChangedState(this.activeGridNode);
        }
      }
    },
  },

  mounted() {
    // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
    //   this.populateStyleEditor(element);
    // });

      this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
          if (this.$root.selectedElement) {
              this.populateStyleEditor(this.$root.selectedElement);
          }
      });

    this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
      if (elementStyleEditorShow !== 'grid') {
        this.showGridSettings = false;
      }
    });
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



      selectedColDesktop: function (newValue, oldValue) {
      this.applyClassToActiveGridNode(newValue);
    },

    selectedColTablet: function (newValue, oldValue) {
      this.applyClassToActiveGridNode(newValue);
    },

    selectedColMobile: function (newValue, oldValue) {
      this.applyClassToActiveGridNode(newValue);
    },
  },
};
</script>
