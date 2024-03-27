<template>
    <div
        v-if="selectedClass && predefinedClassesVariables[selectedClass] && Object.keys(predefinedClassesVariables[selectedClass]).length > 0">

        <div v-for="(value, key) in predefinedClassesVariables[selectedClass]" :key="key">

            <div v-if="key.includes('color')">
                <ColorPicker v-model="predefinedClassesVariables[selectedClass][key]"
                             @change="predefinedClassesVariables[selectedClass][key] = $event"
                             v-bind:color="predefinedClassesVariables[selectedClass][key]"></ColorPicker>
            </div>
            <div v-else-if="key.includes('size-small')">
                <SliderSmall v-model="predefinedClassesVariables[selectedClass][key]"
                             @update:modelValue="updateValue(key, $event)"
                             :showLabel="false"
                             v-bind:min="0"
                             v-bind:max="30"
                             v-bind:step="1"
                             v-bind:label="key"
                             v-bind:unit="key.includes('px') ? 'px' : ''"></SliderSmall>
            </div>
            <div v-else-if="key.includes('size')">
                <SliderSmall v-model="predefinedClassesVariables[selectedClass][key]"
                             @update:modelValue="updateValue(key, $event)"
                             :showLabel="false"
                             v-bind:min="0"
                             v-bind:max="100"
                             v-bind:step="1"
                             v-bind:label="key"
                             v-bind:unit="key.includes('px') ? 'px' : ''"></SliderSmall>
            </div>
            <div v-else>
                <input type="text" v-model="predefinedClassesVariables[selectedClass][key]">
                <button @click="predefinedClassesVariables[selectedClass][key] = ''">Reset</button>
            </div>


        </div>
    </div>
</template>

<script>
import ColorPicker from "./ColorPicker.vue";
import SliderSmall from "./SliderSmall.vue";

export default {
    components: {ColorPicker, SliderSmall},
    props: ['selectedClass', 'predefinedClassesVariables'],
    methods: {
        updateValue(key, value) {
            if (!key.includes('px')) {
                value += 'px';
            }
            this.predefinedClassesVariables[this.selectedClass][key] = value;
            this.$emit('variables-changed', this.predefinedClassesVariables);
        }
    },
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
