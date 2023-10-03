<template>

    <input type="hidden" ref="colorpickerinput" v-model="selectedColor" @input="triggerChangeSelectedColor"/>
    <div class="color-picker-badge"

         @click="togglePicker"
         :style="{background: color}"></div>


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

            selectedColor: this.$props.color
        }
    },
    mounted() {
        mw.top().app.on('mw.elementStyleEditor.closeAllOpenedMenus', () => {
            this.closePicker()
        });
    },

    watch: {
        color(newColor) {
            this.selectedColor = newColor;
        },
    },

    methods: {
        changeColor(color) {
            this.selectedColor = color.hex;
            this.$props.color = color.hex;
        },
        triggerChangeSelectedColor() {

            this.$props.color = this.selectedColor;

            this.$emit('change', this.$props.color);
        },
        triggerChange() {


            this.$emit('change', this.$props.color);
        },


        closePicker() {
            this.showPicker = false;
        },
        togglePicker() {


            let colorPicker = mw.app.colorPicker.openColorPicker(this.selectedColor, color => {
                this.$props.color = color;
                this.$emit('change', this.$props.color);

            });
            // var left = this.$refs.colorpickerinput.getBoundingClientRect().left
            // var top = this.$refs.colorpickerinput.getBoundingClientRect().top
            //
            // // if (self !== top) {
            // //     // Get a reference to the top-level window (parent window)
            // //     const topWindow = window.top;
            // //
            // //     const rect = this.$refs.colorpickerinput.getBoundingClientRect();
            // //     const iframeRect =  topWindow.document.querySelector('iframe').getBoundingClientRect();
            // //     var left = rect.left - iframeRect.left + topWindow.scrollX;
            // //     var top = rect.top - iframeRect.top + topWindow.scrollY;
            // // }
            // colorPicker.position( left, top);


            this.showPicker = !this.showPicker;
        }
    }
}
</script>
