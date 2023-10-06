<script setup>


// defines what events our component emits
defineEmits(['update:modelValue'])
</script>
<script>
export default {
  props: {
    modelValue: String,
    label: String,
    options: Array,
  },
  data() {
    return {
      selectedOption: this.modelValue,
    };
  },
  watch: {
    modelValue(newValue) {
        if (this.selectedOption !== newValue) {
            this.selectedOption = newValue;
        }
    },
  },

  methods: {
      handleInput() {
          // Emit the event only if selectedOption is different from modelValue
          if (this.selectedOption !== this.modelValue) {
              this.$emit('update:modelValue', this.selectedOption);
          }
      },
  },
};
</script>


<template>

  <div class="s-field">

    <label v-if="label" v-html="label"></label>
    <div class="s-field-content">
      <div class="mw-multiple-fields">
        <div class="mw-field mw-field-flat" data-size="medium">
          <select v-model="selectedOption" class="regular" @input="$emit('update:modelValue', $event.target.value)">
            <option v-if="selectedOption" :value="selectedOption">{{ selectedOption }}</option>
            <option v-for="option in options" :selected="selectedOption === option.key" :value="option.key">
              {{ option.value }}
            </option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
