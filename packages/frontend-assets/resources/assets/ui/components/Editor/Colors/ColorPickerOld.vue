<template>
    <div v-click-away="closePicker">
        <div class="color-picker-badge"
             @click="togglePicker"
             :style="{background: color}"></div>


        <input type="text" v-model="color" @change="triggerChange" />

    </div>
</template>

<style>
.hu-color-picker {
    width: 200px !important;
    right: 0px;
    position: absolute;
    margin-top: 2px;
    z-index: 99;
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


export default {
    components: {},
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

        }
    },
    mounted() {
        mw.top().app.on('mw.elementStyleEditor.closeAllOpenedMenus', () => {
            this.closePicker()
        });
    },

    methods: {
        changeColor(color) {
            this.$props.color = color.hex;
        },
        triggerChange() {
            this.$emit('change', this.$props.color);
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
