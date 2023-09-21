<template>

  <div>
    <ElementStyleEditorTypography></ElementStyleEditorTypography>
    <ElementStyleEditorSpacing></ElementStyleEditorSpacing>
    <ElementStyleEditorBackground></ElementStyleEditorBackground>
    <ElementStyleEditorBorder></ElementStyleEditorBorder>
  </div>

</template>

<script>
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorSpacing from './ElementStyleEditorSpacing.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';

export default {
  components: {
    ElementStyleEditorTypography,
    ElementStyleEditorSpacing,
    ElementStyleEditorBackground,
    ElementStyleEditorBorder,
  },

  data() {
    return {}
  },

  methods: {},
  mounted() {


  },


}
</script>


