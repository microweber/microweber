<template>
  <div class="d-flex justify-content-center align-items-center">
    <div class="rounded-corners">
      <label>Rounded Corners</label>
      <div class="s-field-content">
        <div class="mw-field mw-field-flat">
          <div class="mw-multiple-fields">
            <div class="mw-field mw-field-flat">
              <input
                  type="number"
                  class="regular order-1"
                  v-model="selectedBorderRadius.borderTopLeftRadius"
                  @input="updateValues()"
              />
              <span class="mw-field mw-field-flat-prepend order-2">
                <i class="angle angle-top-left"></i>
              </span>
            </div>
            <div class="mw-field mw-field-flat">
              <span class="mw-field mw-field-flat-prepend">
                <i class="angle angle-top-right"></i>
              </span>
              <input
                  class="regular"
                  type="number"
                  v-model="selectedBorderRadius.borderTopRightRadius"
                  @input="updateValues()"
              />
            </div>
          </div>
        </div>
        <div class="mw-field mw-field-flat">
          <div class="mw-multiple-fields">
            <div class="mw-field mw-field-flat">
              <input
                  class="regular order-1"
                  type="number"
                  v-model="selectedBorderRadius.borderBottomLeftRadius"
                  @input="updateValues()"
              />
              <span class="mw-field mw-field-flat-prepend order-2">
                <i class="angle angle-bottom-left"></i>
              </span>
            </div>
            <div class="mw-field mw-field-flat">
              <span class="mw-field mw-field-flat-prepend">
                <i class="angle angle-bottom-right"></i>
              </span>
              <input
                  class="regular"
                  type="number"
                  v-model="selectedBorderRadius.borderBottomRightRadius"
                  @input="updateValues()"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: Object, // Receive an object with radius values as a prop
  },
  data() {
    return {
      selectedBorderRadius: {
        borderTopLeftRadius: '',
        borderTopRightRadius: '',
        borderBottomLeftRadius: '',
        borderBottomRightRadius: '',
      },
    };
  },
  watch: {
    modelValue(newValue) {
      if (newValue) {
        this.selectedBorderRadius = { ...newValue };
      }
    },
  },
  methods: {
    updateValues() {
      this.$emit('update:modelValue', this.selectedBorderRadius);
    },
  },
};
</script>
