<template>
  <div class="form-control-live-edit-label-wrapper d-flex align-items-center">
    <label class="live-edit-label px-0 col-4">{{ label }} {{ selectedValue }}{{ unit }}</label>
    <div class="col-6 ms-4" data-size="medium">
      <v-slider :min="min" :max="max" :step="step" v-model="selectedValue"></v-slider>
      <span @click="resetValue" class="reset-field tip  mw-action-buttons-background-circle-on-hover" data-tipposition="top-right" data-tip="Restore default value">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"></path></svg>
        </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    modelValue: Number, // Rename the prop to modelValue
    min: Number,
    max: Number,
    step: Number,
    unit: String, // Add the unit prop
  },
  data() {
    return {
      selectedValue: this.modelValue, // Use modelValue as the initial value
    };
  },
  methods: {
    resetValue() {
      this.selectedValue = null;
    },
  },
  watch: {
    selectedValue(newValue) {
      // Only emit the 'update:modelValue' event if selectedValue is different from modelValue
      if (newValue !== this.modelValue) {
        this.$emit("update:modelValue", newValue);
      }
    },
    modelValue(newValue) {
      // Update selectedValue when the parent's v-model changes
      this.selectedValue = newValue;
    },
  },
};
</script>
