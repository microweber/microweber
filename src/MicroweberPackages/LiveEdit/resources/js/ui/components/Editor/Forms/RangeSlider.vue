<template>
    <div>
        <Slider v-on:click="sliderUpdate"
                :min="8"
                :max="96"
                v-model="value"
                :merge="1"
                :tooltips="false"
                :tooltipPosition="'right'"
        />
    </div>
</template>


<style src="@vueform/slider/themes/default.css"></style>
<script>
import Slider from '@vueform/slider'

export default {
    components: {
        Slider,
    },
    methods: {
        sliderUpdate() {
            this.$emit('change', this.value);
        }
    },
    data() {
        return {
            value: 10
        }
    }
}
</script>
