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
    },    data() {
        var colorValue = this.color || '#ffffff';
        var colorHex = this.getHexColorDisplayValueText(colorValue);

        return {
            selectedColor: colorValue,
            selectedColorHex: colorHex,
        };
    },watch: {
        color(newColor) {
            this.selectedColor = newColor;
            this.setHexColorDisplay(newColor);
        },
    },
    mounted() {
        // Set initial color display when component is mounted
        this.$nextTick(() => {
            this.setHexColorDisplay(this.color);
        });
    },
    methods: {
        getHexColorDisplayValueText(newColor) {
            if(!newColor) {
                return 'transparent';
            }
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

            if (newColor.includes('rgb') || newColor.includes('rgba')) {
                var colorHex = mw.color.rgbOrRgbaToHex(newColor)
                if (colorHex == '#00000000') {
                    return '';
                }
                return colorHex;
            }
            return newColor;

        },        setHexColorDisplay(newColor) {
            if (this.$refs.colorPickerButton) {
                if (!newColor || newColor === 'transparent' || newColor === '') {
                    this.$refs.colorPickerButton.style.backgroundColor = 'transparent';
                } else {
                    this.$refs.colorPickerButton.style.backgroundColor = newColor;
                }
            }
            this.selectedColorHex = this.getHexColorDisplayValueText(newColor);
        },
        handleColorChange(event) {
            const newColor = event.target.value;
            this.setNewColor(newColor);
        },        setNewColor(newColor) {
            this.selectedColor = newColor;
            this.setHexColorDisplay(newColor);
            this.$emit('change', newColor);
        },        resetColor() {
            this.selectedColor = '';
            this.setHexColorDisplay('');
            this.$emit('change', this.selectedColor);
        },
        togglePicker() {
            let el = this.$refs.colorPickerButton;
            let colorPicker = mw.app.colorPicker.openColorPicker(this.selectedColor, color => {
                this.setNewColor(color);
            }, el);
        }
    }
}

</script>
