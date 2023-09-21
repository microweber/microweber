<script setup>
defineProps({
    modelValue: String
})

// defines what events our component emits
defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label class="live-edit-label" v-html="label"></label>
        <div>
            <label class="form-control-live-edit-label-wrapper">
                <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="form-control-live-edit-input">
                <span class="form-control-live-edit-bottom-effect"></span>
            </label>
        </div>
    </div>
</template>
