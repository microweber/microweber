<template>
    <div v-if="selectedClass && predefinedClassesVariables[selectedClass]">
        <h3>Edit CSS Variables for {{ selectedClass }}</h3>
        <div v-for="(value, key) in predefinedClassesVariables[selectedClass]" :key="key">
            <label>{{ key }}:</label>
            <input type="text" v-model="predefinedClassesVariables[selectedClass][key]">

             <button @click="predefinedClassesVariables[selectedClass][key] = ''">Reset</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['selectedClass', 'predefinedClassesVariables'],

    watch: {
        predefinedClassesVariables: {
            handler(newValue) {

                this.$emit('variables-changed',  newValue);
            },
            deep: true
        }
    }
};
</script>
