<template>
    <div>
        <label>Predefined Shadows:</label>
        <select :value="selectedShadow" @change="selectPredefinedShadow">
            <option v-for="(shadow, index) in predefinedShadows" :key="index" :value="shadow.value">
                {{ shadow.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props: {
        predefinedShadows: Array,
        selectedShadow: String
    },
    methods: {
        selectPredefinedShadow(event) {
            const selectedShadow = event.target.value;
            this.$emit('update:selectedShadow', selectedShadow);
        }
    }
};
</script>
