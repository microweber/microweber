<template>
    <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">
        <label class="live-edit-label px-0 col-4">Shadow:</label>
        <select :value="selectedShadow" @change="selectPredefinedShadow">
            <option v-for="(shadow, index) in predefinedShadows" :key="index" :value="shadow.value"
                    :style="{ boxShadow: shadow.value }">

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
