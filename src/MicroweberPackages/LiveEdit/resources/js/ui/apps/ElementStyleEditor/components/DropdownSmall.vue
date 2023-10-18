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
    <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">
        <label class="live-edit-label px-0 col" v-if="label" v-html="label"></label>
          <select v-model="selectedOption" class="form-control-live-edit-input form-select" @input="$emit('update:modelValue', $event.target.value)">
                <option v-if="selectedOption" :value="selectedOption">{{ selectedOption }}</option>
                <option v-for="option in options" :selected="selectedOption === option.key" :value="option.key">
                  {{ option.value }}
                </option>
          </select>
  </div>
</template>
