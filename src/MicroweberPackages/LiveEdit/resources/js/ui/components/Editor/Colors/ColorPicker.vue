<template>
    <div v-click-away="closePicker">
        <div class="color-picker-badge"
                @click="togglePicker"
             :style="{background: color}"></div>

        <ColorPicker
                theme="light"
                     v-if="showPicker"
                     :value="color"
                     :sucker-hide="false"
                     :sucker-canvas="suckerCanvas"
                     :sucker-area="suckerArea"
                     @click="triggerChange"
                     @openSucker="openSucker"
                     @changeColor="changeColor"
                     @close="togglePicker" />
    </div>
</template>

<style>
.hu-color-picker {
    width: 200px!important;
    right: 0px;
    position: absolute;
    margin-top: 2px;
}
.color-picker-badge {
    width: 30px;
    height: 30px;
    background: #ddd;
    color: #fff;
    text-align: center;
    line-height: 30px;
    border-radius: 100%;
    cursor: pointer;
    border: 1px solid #e0e0e0;
}
</style>

<script>
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'

export default {
    components: {
        ColorPicker,
    },
    props: {
        color: {
            type: String,
            default: '#000000'
        },
        name: {
            type: String,
            default: 'color'
        }
    },
    data() {
        return {
            showPicker: false,
            suckerCanvas: null,
            suckerArea: [],
            isSucking: false,
        }
    },
    methods: {
        changeColor(color) {
            this.$props.color = color.hex;
        },
        triggerChange(){
            this.$emit('change', this.$props.color);
        },
        openSucker(isOpen) {
            if (isOpen) {
                // ... canvas be created
                // this.suckerCanvas = canvas
                // this.suckerArea = [x1, y1, x2, y2]
            } else {
               //  this.suckerCanvas && this.suckerCanvas.remove
            }
        },
        closePicker() {
            this.showPicker = false;
        },
        togglePicker() {
            this.showPicker = !this.showPicker;
        }
    }
}
</script>
