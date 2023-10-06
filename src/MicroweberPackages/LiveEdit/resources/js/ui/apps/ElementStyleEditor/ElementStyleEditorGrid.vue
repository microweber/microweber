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
  components: {DropdownSmall},
  data() {
    return {
      activeGridNode: null,
      isReady: false,
      hasGrid: false,
      gridType: null,
      colOptions: [
        {value: '2', label: 'col-2'},
        {value: '3', label: 'col-3'},
        {value: '4', label: 'col-4'},
        {value: '5', label: 'col-5'},
        {value: '6', label: 'col-6'},
        {value: '7', label: 'col-7'},
        {value: '8', label: 'col-8'},
        {value: '9', label: 'col-9'},
        {value: '10', label: 'col-10'},
        {value: '11', label: 'col-11'},
        {value: '12', label: 'col-12'},
      ],
      selectedColDesktop: '',
      selectedColTablet: '',
      selectedColMobile: '',
    };
  },

  methods: {
    resetAllProperties: function () {
      this.hasGrid = null;
      this.gridType = null;
    },

    populateStyleEditor: function (node) {
      if (node && node && node.nodeType === 1) {
        this.isReady = false;
        this.resetAllProperties();
        var GridNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, [
          'col-md',
          'col-sm',
          'col-lg',
          'col-xl',
          'col-xxl',
        ]);

        if (GridNode && mw.tools.isEditable(GridNode)) {
          if (GridNode) {
            this.hasGrid = true;
            this.activeGridNode = GridNode;
            this.populateCssGridForNode(GridNode);
          }
        }

        setTimeout(() => {
          this.isReady = true;
        }, 100);
      }
    },

    populateCssGridForNode: function (node) {

    },

    applyClassToActiveGridNode: function (val) {
      if (!this.isReady) {
        return;
      }
      if (this.activeGridNode) {
        // Apply the selected class to the active grid node.

      }
    },
  },

  mounted() {
    mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
      this.populateStyleEditor(element);
    });
  },

  watch: {
    gridType: function (newValue, oldValue) {
      this.applyClassToActiveGridNode(newValue);
    },
  },
};
</script>
