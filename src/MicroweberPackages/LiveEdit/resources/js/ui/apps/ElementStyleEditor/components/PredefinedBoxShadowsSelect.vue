<template>
    <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2">

        <select :value="selectedShadow" @change="selectPredefinedShadow" style="display: none;">
            <option v-for="(shadow, index) in predefinedShadows" :key="index" :value="shadow.value"
                    :style="{ boxShadow: shadow.value || 'none' }">

                {{ shadow.name }}

            </option>
        </select>

            <div class="row">
                <div
                    class="card "
                    :class="{ active: selectedShadow ===  shadow.value}"
                    @click="selectPredefinedShadow"
                    v-for="(shadow, index) in predefinedShadows"
                    :key="index"
                    :value="shadow.value"
                    :style="{ boxShadow: shadow.value || 'none', width: '50px', height: '50px' }">
                </div>
            </div>

    </div>
</template>

<style scoped>

    .row{
        gap: 22px;
        align-items: center;
        justify-content: center;
    }
    .card.active{
        background: rgb(0 128 0 / 12%);
        transition: background-color .3s;
    }
    .card::after{
        content: '\2713';
        color: green;
        font-weight: bold;
        font-size: 0;
    }
    .card.active::after{
        transition: font-size .3s;
        font-size: 24px;
    }
    .card{
        min-height: auto;
        display: flex;
        align-items: center;
        justify-content: center;

    }
</style>

<script>
export default {
    props: {
        predefinedShadows: Array,
        selectedShadow: String
    },
    methods: {
        selectPredefinedShadow(event) {
            const selectedShadow = event.target.value || event.target.getAttribute('value');
            this.$emit('update:selectedShadow', selectedShadow);

        }
    }
};
</script>
