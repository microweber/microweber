<template>
<div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center gap-2">
    <label class="live-edit-label px-0 col-4">{{ label }}</label>

    <div class="mw-field mw-field-flat" data-size="medium" >
      <span class="mw-field-color-indicator">
        <span
            ref="colorPickerElement"
            class="mw-field-color-indicator-display"
            :style="{ backgroundColor: selectedColor }"
        ></span>
      </span>

      <input readonly
          @click="togglePicker"
          type="text"
          class="colorField unit ready mw-color-picker-field"
          :value="selectedColor"
          @input="handleColorChange"
          autocomplete="off"
          placeholder="#ffffff"
          style="opacity: 0;"
      />
      <span
          class="reset-field tip mw-action-buttons-background-circle-on-hover"
          data-tipposition="top-right"
          data-tip="Restore default value"
          @click="resetColor"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"></path></svg>
      </span>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Color', // Default label text
    },
    color: {
      type: String,
      default: '#ffffff',
    },
  },
  data() {
    return {
      selectedColor: this.color,
    };
  },
  watch: {
    color(newColor) {
      this.selectedColor = newColor;
    },
  },
  methods: {
    handleColorChange(event) {
      const newColor = event.target.value;
      this.selectedColor = newColor;
 

      this.$emit('change', newColor);
    },
    resetColor() {
      this.selectedColor = '';
      this.$emit('change', this.selectedColor);
    },
    togglePicker() {


      let el = this.$refs.colorPickerElement;



      let colorPicker = mw.app.colorPicker.openColorPicker(this.selectedColor, color => {
        this.$props.color = color;
        this.selectedColor = color;
        this.$emit('change', this.$props.color);
      }, el);



    }
  },
};
</script>
