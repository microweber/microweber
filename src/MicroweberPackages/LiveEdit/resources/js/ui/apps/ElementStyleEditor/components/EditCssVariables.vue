<template>
    <div v-if="selectedClass && predefinedClassesVariables[selectedClass] && Object.keys(predefinedClassesVariables[selectedClass]).length > 0">
        <h3>Edit CSS Variables for {{ selectedClass }}</h3>
        <div v-for="(value, key) in predefinedClassesVariables[selectedClass]" :key="key">
            <label>{{ key }}:</label>

            <div v-if="key.includes('color')">
                <ColorPicker  v-model="predefinedClassesVariables[selectedClass][key]"
                              @change="predefinedClassesVariables[selectedClass][key] = $event"
                              v-bind:color="predefinedClassesVariables[selectedClass][key]"></ColorPicker>
            </div>
            <div  v-else>
                <input type="text" v-model="predefinedClassesVariables[selectedClass][key]">
                <button @click="predefinedClassesVariables[selectedClass][key] = ''">Reset</button>
            </div>



        </div>
    </div>
</template>

<script>
import ColorPicker from "./ColorPicker.vue";

export default {
    components: {ColorPicker},
    props: ['selectedClass', 'predefinedClassesVariables'],

    watch: {
        predefinedClassesVariables: {
            handler(newValue) {

                this.$emit('variables-changed', newValue);
            },
            deep: true
        }
    }
};
</script>
