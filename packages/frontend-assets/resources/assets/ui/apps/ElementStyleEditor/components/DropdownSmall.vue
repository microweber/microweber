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
    <!--
        task-2026-05-16-28c634: align the dropdown with the rest of
        the ESE field row pattern — label on the LEFT, control on the
        RIGHT (same as ColorPicker / Align / Italic toggle). Was a
        vertical stack (label above the full-width select), which the
        user flagged as looking unfinished next to the side-by-side
        controls. Wrapping in `d-flex justify-content-between
        align-items-center` keeps the wrapper class for spacing
        (margin-bottom, dark-mode hooks) but gives the inner row a
        consistent two-column shape. The select shrinks via
        `dropdown-small-select` to roughly half the row so the label
        has breathing room.
    -->
    <div class="form-control-live-edit-label-wrapper my-4">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <label class="live-edit-label mb-0" v-if="label" v-html="label"></label>
            <select v-model="selectedOption"
                    class="form-control-live-edit-input form-select dropdown-small-select"
                    @input="$emit('update:modelValue', $event.target.value)">
                <option v-for="option in options" :selected="selectedOption === option.key" :value="option.key">
                  {{ option.value }}
                </option>
            </select>
        </div>
    </div>
</template>

<style scoped>
/*
 * task-2026-05-16-28c634: keep the select compact so the label has
 * room. Without a width cap the select would stretch flex-1 and the
 * label would shrink to text-bound, breaking visual rhythm with
 * sibling fields (ColorPicker's 30x30 swatch, Align's icon row).
 */
.dropdown-small-select {
    width: auto;
    min-width: 120px;
    max-width: 60%;
    font-size: 13px;
    padding: 4px 28px 4px 8px;
    height: auto;
}
</style>
