<template>


  <div>
    <div v-show="showElementSelector">
      <ElementStyleEditorElementSelector></ElementStyleEditorElementSelector>
    </div>

    <div v-show="showTypography">
      <ElementStyleEditorTypography></ElementStyleEditorTypography>
    </div>
    <div v-show="showSpacing">
      <ElementStyleEditorSpacing></ElementStyleEditorSpacing>
    </div>

    <div v-show="showContainer">
      <ElementStyleEditorContainer></ElementStyleEditorContainer>
    </div>

    <div v-show="showGrid">
      <ElementStyleEditorGrid></ElementStyleEditorGrid>
    </div>
    <div v-show="showBackground">
      <ElementStyleEditorBackground></ElementStyleEditorBackground>
    </div>
    <div v-show="showBorder">
      <ElementStyleEditorBorder></ElementStyleEditorBorder>
    </div>

    <div v-show="showAnimations">
      <ElementStyleEditorAnimations></ElementStyleEditorAnimations>
    </div>


    <div v-show="showClassApplier">
      <ElementStyleEditorClassApplier></ElementStyleEditorClassApplier>
    </div>


  </div>

</template>
<style src="./ElementStyleEditor.css"></style>

<script>
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorSpacing from './ElementStyleEditorSpacing.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';
import ElementStyleEditorElementSelector from './ElementStyleEditorElementSelector.vue';
import ElementStyleEditorContainer from './ElementStyleEditorContainer.vue';
import ElementStyleEditorAnimations from './ElementStyleEditorAnimations.vue';
import ElementStyleEditorGrid from './ElementStyleEditorGrid.vue';
import ElementStyleEditorClassApplier from './ElementStyleEditorClassApplier.vue';

export default {
  components: {
    ElementStyleEditorElementSelector,
    ElementStyleEditorTypography,
    ElementStyleEditorSpacing,
    ElementStyleEditorBackground,
    ElementStyleEditorBorder,
    ElementStyleEditorContainer,
    ElementStyleEditorGrid,
    ElementStyleEditorAnimations,
    ElementStyleEditorClassApplier,
  },

  data() {
    return {
      showElementSelector: true,
      showTypography: true,
      showSpacing: true,
      showBackground: true,
      showBorder: true,
      showContainer: true,
      showGrid: true,
      showAnimations: true,
      showClassApplier: true,
    }
  },

  methods: {},
  mounted() {

    mw.top().app.on('cssEditorSettings', (settings) => {

      if (settings.fieldSettings.components) {

        this.showTypography = false;
        this.showSpacing = false;
        this.showBackground = false;
        this.showBorder = false;
        this.showElementSelector = false;
        this.showContainer = false;
        this.showAnimations = false;
        this.showClassApplier = false;

        if (settings.fieldSettings.components.includes('elementSelector')) {
          this.showElementSelector = true;
        }
        if (settings.fieldSettings.components.includes('typography')) {
          this.showTypography = true;
        }
        if (settings.fieldSettings.components.includes('spacing')) {
          this.showSpacing = true;
        }
        if (settings.fieldSettings.components.includes('background')) {
          this.showBackground = true;
        }
        if (settings.fieldSettings.components.includes('border')) {
          this.showBorder = true;
        }
        if (settings.fieldSettings.components.includes('container')) {
          this.showContainer = true;
        }
        if (settings.fieldSettings.components.includes('grid')) {
          this.showGrid = true;
        }

        if (settings.fieldSettings.components.includes('animations')) {
          this.showAnimations = true;
        }

        if (settings.fieldSettings.components.includes('classes')) {
          this.showClassApplier = true;
        }
      }
    });

  },


}
</script>


