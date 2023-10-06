<template>
  <div v-if="hasGrid">
    <div class="s-field">
      <div class="s-field-content">
        <div class="mw-field mw-field-flat" data-size="medium">
          <label>Desktop</label>
          <i class="mdi mdi-monitor"></i>
          <DropdownSmall
              :options="colOptions"
              v-model="selectedColDesktop"
          ></DropdownSmall>
        </div>
        <div class="mw-field mw-field-flat" data-size="medium">
          <label>Tablet</label>
          <i class="mdi mdi-tablet"></i>
          <DropdownSmall
              :options="colOptions"
              v-model="selectedColTablet"
          ></DropdownSmall>
        </div>
        <div class="mw-field mw-field-flat" data-size="medium">
          <label>Mobile</label>
          <i class="mdi mdi-cellphone"></i>
          <DropdownSmall
              :options="colOptions"
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
  components: { DropdownSmall },
  data() {
    return {
      activeGridNode: null,
      isReady: false,
      hasGrid: false,

      colOptions: [],
      selectedColDesktop: '',
      selectedColTablet: '',
      selectedColMobile: '',
      external_grids_col_classes: [
        'col-1',
        'col-2',
        'col-3',
        'col-4',
        'col-5',
        'col-6',
        'col-7',
        'col-8',
        'col-9',
        'col-10',
        'col-11',
        'col-12',
        'col-lg-1',
        'col-lg-2',
        'col-lg-3',
        'col-lg-4',
        'col-lg-5',
        'col-lg-6',
        'col-lg-7',
        'col-lg-8',
        'col-lg-9',
        'col-lg-10',
        'col-lg-11',
        'col-lg-12',
        'col-md-1',
        'col-md-2',
        'col-md-3',
        'col-md-4',
        'col-md-5',
        'col-md-6',
        'col-md-7',
        'col-md-8',
        'col-md-9',
        'col-md-10',
        'col-md-11',
        'col-md-12',
        'col-sm-1',
        'col-sm-2',
        'col-sm-3',
        'col-sm-4',
        'col-sm-5',
        'col-sm-6',
        'col-sm-7',
        'col-sm-8',
        'col-sm-9',
        'col-sm-10',
        'col-sm-11',
        'col-sm-12',
        'col-xs-1',
        'col-xs-2',
        'col-xs-3',
        'col-xs-4',
        'col-xs-5',
        'col-xs-6',
        'col-xs-7',
        'col-xs-8',
        'col-xs-9',
        'col-xs-10',
        'col-xs-11',
        'col-xs-12',
      ],
    };
  },

  methods: {
    resetAllProperties: function () {
      this.hasGrid = null;
    },

    populateStyleEditor: function (node) {
      if (node && node.nodeType === 1) {
        this.isReady = false;
        this.resetAllProperties();

        // Check for grid classes in the active node
        for (const gridClass of this.external_grids_col_classes) {
          if (node.classList.contains(gridClass)) {
            this.hasGrid = true;
            this.activeGridNode = node;
            // Extract the number from the class and set it for all breakpoints
            const colNumber = parseInt(gridClass.split('-').pop());
            this.selectedColDesktop = colNumber.toString();
            this.selectedColTablet = colNumber.toString();
            this.selectedColMobile = colNumber.toString();
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
        for (const gridClass of this.external_grids_col_classes) {
          this.activeGridNode.classList.remove(gridClass);
        }

        // Apply the selected grid classes for all breakpoints
        const selectedGridClass = `col-${val}`;
        this.activeGridNode.classList.add(selectedGridClass);
      }
    },
  },

  mounted() {
    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
      this.populateStyleEditor(element);
    });
  },

  watch: {
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
