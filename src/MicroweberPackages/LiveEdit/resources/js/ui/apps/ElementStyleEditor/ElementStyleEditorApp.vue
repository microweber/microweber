<template>

  <div>
    ElementStyleEditorTypography
    <hr>
    <ElementStyleEditorTypography></ElementStyleEditorTypography>
    <hr>
    ElementStyleEditorMargin
    <hr>
    <ElementStyleEditorMargin></ElementStyleEditorMargin>

    <hr>
    ElementStyleEditorPadding
    <hr>
    <ElementStyleEditorPadding></ElementStyleEditorPadding>
    <hr>
    <hr>
    ElementStyleEditorBackground
    <hr>
    <ElementStyleEditorBackground></ElementStyleEditorBackground>
    <hr>
    <hr>
    ElementStyleEditorBorder
    <hr>
    <ElementStyleEditorBorder></ElementStyleEditorBorder>
    <hr>


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


