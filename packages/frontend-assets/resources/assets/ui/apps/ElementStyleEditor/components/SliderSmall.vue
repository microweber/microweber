<template>
  <!--
    task-2026-05-16-b68ce4: restructured SliderSmall layout. Previously:
      - the number input lived INSIDE the `<label>`, making the
        label-wrapping `<input>` pattern stack as inline text +
        editable input — visually cramped and confusing for click
        targets;
      - the reset button was `position: absolute` over the slider,
        overlapping the rightmost portion of the track.
    Restructured as two explicit rows:
      Row 1: [LABEL]  ........... [number input] [unit] [reset]
      Row 2: [================slider================]
    All three right-side widgets (input, unit, reset) sit in a flex
    cluster so they justify to the row end without overlapping the
    slider below. Reset is no longer absolutely positioned.
  -->
  <div class="mw-live-edit-slider-small form-control-live-edit-label-wrapper">
    <div v-if="showLabel" class="d-flex justify-content-between align-items-center gap-2 slider-small-header">
        <label class="live-edit-label mb-0">{{ label }}</label>
        <div class="d-flex align-items-center gap-1 slider-small-controls">
            <input
              type="number"
              class="form-control-input-range-slider"
              v-model.number="selectedValue"
              :min="min"
              :max="max"
              :step="step"
              @blur="validateValue"
              :aria-label="label"
            />
            <span v-if="unit" class="slider-small-unit">{{ unit }}</span>
            <button @click="resetValue" type="button" class="reset-field-btn"
                    title="Restore default value" aria-label="Restore default value">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="14" viewBox="0 -960 960 960" width="14" aria-hidden="true"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"></path></svg>
            </button>
        </div>
    </div>
    <div data-size="medium" class="slider-small-track-row">
      <v-slider :min="min" :max="max" :step="step" v-model="selectedValue"></v-slider>
    </div>
  </div>
</template>

<script>
export default {
  props: {
      showLabel: {
          type: Boolean,
          default: true
      },
    label: String,
    modelValue: Number, // Rename the prop to modelValue
    min: Number,
    max: Number,
    step: Number,
    unit: String, // Add the unit prop
  },
  data() {
    return {
      // task-2026-05-05-854d66 (QW1) — coerce non-finite incoming
      // values (e.g. parseFloat(undefined) → NaN) to null before
      // binding to v-model. Without this, v-model.number renders
      // the literal string "NaN" in the numeric input on first
      // selection of a fresh element, which the Drunk-Designer
      // audit flagged as the highest-trust visible bug.
      selectedValue: Number.isFinite(this.modelValue) ? this.modelValue : null,
    };
  },
  methods: {
    resetValue() {
      this.selectedValue = null;
    },
    validateValue() {
      // task-2026-05-05-854d66 (QW1) — also catch NaN here in case
      // the user types a non-numeric value into the input. Treat
      // NaN/Infinity the same as "unset".
      if (typeof this.selectedValue === 'number' && !Number.isFinite(this.selectedValue)) {
        this.selectedValue = null;
        return;
      }
      if (this.selectedValue !== null && this.selectedValue !== undefined) {
        if (this.min !== undefined && this.selectedValue < this.min) {
          this.selectedValue = this.min;
        }
        if (this.max !== undefined && this.selectedValue > this.max) {
          this.selectedValue = this.max;
        }
      }
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
      // Update selectedValue when the parent's v-model changes.
      // Coerce non-finite to null to prevent NaN rendering.
      this.selectedValue = Number.isFinite(newValue) ? newValue : null;
    },
  },
};
</script>

<style scoped>
/*
 * task-2026-05-05-d2ce0f: the original `background: rgba(255, 255,
 * 255, 0.95)` made every slider render as a solid white block on
 * the dark Element Style Editor panel — what the user reported as
 * "broken sliders". Drop the white bg + use currentColor for the
 * input border/text so the sliders pick up the surrounding dark
 * theme automatically. The container is now transparent and uses
 * a thin token-aware divider instead of a card.
 */
.mw-live-edit-slider-small {
  position: relative;
  padding: 4px 0;
  background: transparent;
  border-radius: 0;
  box-shadow: none;
  border: none;
}

.slider-small-header {
  margin-bottom: 2px;
}

.slider-small-unit {
  font-size: 11px;
  opacity: 0.6;
  min-width: 18px;
  text-align: start;
}

.form-control-input-range-slider {
  width: 56px !important;
  height: 22px !important;
  padding: 2px 4px !important;
  border: 1px solid rgba(127, 127, 127, 0.25) !important;
  opacity: 0.9;
  border-radius: 4px !important;
  font-size: 12px !important;
  font-weight: 500 !important;
  color: inherit !important;
  background: transparent !important;
  text-align: center !important;
  box-shadow: none !important;
}

.form-control-input-range-slider:focus {
  outline: none !important;
  opacity: 1;
  border-color: rgba(24, 36, 51, 0.4) !important;
  background: rgba(24, 36, 51, 0.08) !important;
}

.form-control-input-range-slider:hover {
  opacity: 1;
  border-color: rgba(127, 127, 127, 0.45) !important;
}

.form-control-input-range-slider::placeholder {
  color: #a0aec0 !important;
  font-size: 11px !important;
}

/* Chrome, Safari, Edge, Opera */
.form-control-input-range-slider::-webkit-outer-spin-button,
.form-control-input-range-slider::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
.form-control-input-range-slider[type=number] {
  -moz-appearance: textfield;
}

.slider-small-track-row {
  position: relative;
  padding: 4px 2px 6px;
}

/*
 * task-2026-05-16-b68ce4: reset button is now a real button in the
 * header-row flex cluster (no longer absolute over the slider). The
 * button sits next to the number input + unit, keeping the slider
 * track unobstructed.
 */
.reset-field-btn {
  width: 22px;
  height: 22px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  cursor: pointer;
  transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
  color: currentColor;
  opacity: 0.55;
  background: transparent;
  border: none;
  padding: 0;
}

.reset-field-btn:hover {
  opacity: 1;
  background: rgba(127, 127, 127, 0.12);
}

.reset-field-btn:focus-visible {
  opacity: 1;
  outline: 2px solid rgba(24, 36, 51, 0.4);
  outline-offset: 1px;
}

.reset-field-btn svg {
  transition: transform 0.25s ease;
}

.reset-field-btn:hover svg {
  transform: rotate(-180deg);
}
</style>
