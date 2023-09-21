<template>

  <div>
    <ElementStyleEditorTypography></ElementStyleEditorTypography>
    <ElementStyleEditorMargin></ElementStyleEditorMargin>
    <ElementStyleEditorPadding></ElementStyleEditorPadding>
    <ElementStyleEditorBackground></ElementStyleEditorBackground>
    <ElementStyleEditorBorder></ElementStyleEditorBorder>
  </div>

</template>

<script>
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorMargin from './ElementStyleEditorMargin.vue';
import ElementStyleEditorPadding from './ElementStyleEditorPadding.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';

export default {
  components: {
    ElementStyleEditorTypography,
    ElementStyleEditorPadding,
    ElementStyleEditorBackground,
    ElementStyleEditorBorder,
    ElementStyleEditorMargin
  },

  data() {
    return {}
  },

  methods: {},
  mounted() {


  },


}
</script>


