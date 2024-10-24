<template>
   <div class="form-control-live-edit-label-wrapper my-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <label class="live-edit-label">
                    {{ label }}
                </label>
            </div>
            <div>
                <button @click="togglePicker" ref="colorPickerButton"  class="picker-button" type="button">Pick color</button>
            </div>
        </div>
    </div>

</template>

<style scoped>
    .picker-button{
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: transparent;
        font-size: 0px;
        cursor: pointer;
        border: 1px solid rgb(202, 202, 202);
        outline: none;
        box-shadow: none;
        font-size: 0;
    }
</style>

<script>
export default {
    emits: ['change'],
    props: {
        label: {
            type: String,
            default: 'Color', // Default label text
        },
        color: {
            type: String,
            default: '#ffffff',
        },
    },
    data() {

        var colorHex = this.getHexColorDisplayValueText(this.color)

        return {
            selectedColor: this.color,

            selectedColorHex: colorHex,
        };
    },
    watch: {
        color(newColor) {
            this.selectedColor = newColor;
            this.setHexColorDisplay(newColor);
        },
    },
    methods: {
        getHexColorDisplayValueText(newColor) {
            if (newColor == 'revert-layer') {
                return '';
            }
            if (newColor == 'none') {
                return '';
            }
            if (newColor == 'currentColor') {
                return '';
            }
            if (newColor == 'rgb(0 0 0 / 0%)') {
                return 'transparent';
            }
            //check fi string lie green, etc
            if (newColor.includes('rgb') || newColor.includes('rgba')) {
                var colorHex = mw.color.rgbOrRgbaToHex(newColor)
                if (colorHex == '#00000000') {
                    return '';
                }
                return colorHex;
            }
            return newColor;

        },

        setHexColorDisplay(newColor) {
            this.$refs.colorPickerButton.style.backgroundColor = newColor;
            this.selectedColorHex = this.getHexColorDisplayValueText(newColor);
        },
        handleColorChange(event) {
            const newColor = event.target.value;
            this.setNewColor(newColor);
        },
        setNewColor(newColor) {
            this.selectedColor = newColor;

            this.setHexColorDisplay(newColor);


            this.$refs.colorPickerButton.style.backgroundColor = newColor;


            this.$emit('change', newColor);
        },
        resetColor() {
            this.selectedColor = '';
            this.$emit('change', this.selectedColor);
            this.$refs.colorPickerButton.style.backgroundColor = 'transparent';
        },
        togglePicker() {
            let el = this.$refs.colorPickerElement;
            let colorPicker = mw.app.colorPicker.openColorPicker(this.selectedColor, color => {
                this.setNewColor(color);
            }, el);
        }
    }
}

</script>
