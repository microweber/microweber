<!--
    task-2026-06-01-ai1205 — AI-1205: Element Style Editor BorderRadius accessible-name gap.
    Pre-fix the 4 corner number inputs (top-left / top-right / bottom-left / bottom-right)
    had no aria-label / no label / no aria-labelledby. AT users heard only "edit, blank"
    while the decorative angle icons carried the visual semantic. WCAG 3.3.2 Level A regression.
    Fix: aria-label + title on each input naming the corner; aria-hidden=true on the
    decorative angle wrapper so the icon is not double-announced.
-->
<template>
  <div class="d-flex justify-content-center align-items-center mw-live-edit-border-radius">
    <div class="rounded-corners my-5 mx-2">

      <div class="s-field-content gap-2">
        <div class="mw-field mw-field-flat">
          <div class="mw-multiple-fields">
            <div class="mw-field mw-field-flat">
              <input
                  type="number"
                  class="regular order-1 text-center"
                  name="borderTopLeftRadius"
                  autocomplete="off"
                  aria-label="Top-left corner radius"
                  title="Top-left corner radius"
                  v-model="selectedBorderRadius.borderTopLeftRadius"
                  @input="updateValues()"
              />
              <span class="mw-field mw-field-flat-prepend order-2" aria-hidden="true">
                <i class="angle angle-top-left"></i>
              </span>
            </div>
            <div class="mw-field mw-field-flat">
              <span class="mw-field mw-field-flat-prepend" aria-hidden="true">
                <i class="angle angle-top-right"></i>
              </span>
              <input
                  class="regular text-center"
                  type="number"
                  name="borderTopRightRadius"
                  autocomplete="off"
                  aria-label="Top-right corner radius"
                  title="Top-right corner radius"
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
                  class="regular order-1 text-center"
                  type="number"
                  name="borderBottomLeftRadius"
                  autocomplete="off"
                  aria-label="Bottom-left corner radius"
                  title="Bottom-left corner radius"
                  v-model="selectedBorderRadius.borderBottomLeftRadius"
                  @input="updateValues()"
              />
              <span class="mw-field mw-field-flat-prepend order-2" aria-hidden="true">
                <i class="angle angle-bottom-left"></i>
              </span>
            </div>
            <div class="mw-field mw-field-flat">
              <span class="mw-field mw-field-flat-prepend" aria-hidden="true">
                <i class="angle angle-bottom-right"></i>
              </span>
              <input
                  class="regular text-center"
                  type="number"
                  min="0"
                  name="borderBottomRightRadius"
                  autocomplete="off"
                  aria-label="Bottom-right corner radius"
                  title="Bottom-right corner radius"
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
