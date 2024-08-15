<script setup>
defineProps({
    modelValue: String,
    label: String,
    options: Array,
})

// defines what events our component emits
defineEmits(['update:modelValue'])
</script>

<template>
    <div>

        <label class="live-edit-label" v-html="label"></label>
        <div>
            <label class="form-control-live-edit-label-wrapper">
                <select class="form-select form-control-live-edit-input"
                        @input="$emit('update:modelValue', $event.target.value)">
                    <option v-for="option in options" :value="option.key">{{ option.value }}</option>
                </select>
            </label>
        </div>
    </div>
</template>
