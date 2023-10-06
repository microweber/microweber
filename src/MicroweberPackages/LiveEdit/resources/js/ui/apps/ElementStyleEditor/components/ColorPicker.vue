<template>
  <div class="s-field">
    <label>{{ label }}</label>
     <div class="s-field-content">
      <div class="mw-multiple-fields">
        <div class="mw-field mw-field-flat" data-size="medium" >

          <span class="mw-field-color-indicator">
            <span
ref="colorPickerElement"
                class="mw-field-color-indicator-display"
                :style="{ backgroundColor: selectedColor }"
            ></span>
          </span>

          <input
              @click="togglePicker"
              type="text"
              class="colorField unit ready mw-color-picker-field"
              :value="selectedColor"
              @input="handleColorChange"
              autocomplete="off"
              placeholder="#ffffff"
          />
          <span
              class="reset-field tip"
              data-tipposition="top-right"
              data-tip="Restore default value"
              @click="resetColor"
          >
            <i class="mdi mdi-history"></i>
          </span>
        </div>
      </div>
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
