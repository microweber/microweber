<template>
    <div>
        <Slider v-on:click="sliderUpdate"
                :min="0"
                :max="5"
                v-model="value"
                :merge="1"
                :tooltips="false" />
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
            console.log(this.value);
        }
    },
    data() {
        return {
            value: 4
        }
    }
}
</script>
