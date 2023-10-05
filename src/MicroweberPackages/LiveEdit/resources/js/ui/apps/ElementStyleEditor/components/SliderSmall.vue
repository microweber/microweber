<template>
    <div class="s-field">
        <label>{{ label }} - {{ selectedValue }}</label>
        <div class="s-field-content">
            <div class="mw-multiple-fields">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <v-slider :min="min" :max="max" :step="step" v-model="selectedValue">
                    </v-slider>
                    <span @click="resetValue" class="reset-field tip" data-tipposition="top-right" data-tip="Restore default value">
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
        label: String,
        modelValue: Number, // Rename the prop to modelValue
        min: Number,
        max: Number,
        step: Number,
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
