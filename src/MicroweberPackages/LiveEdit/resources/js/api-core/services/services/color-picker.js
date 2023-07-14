import MicroweberBaseClass from "../containers/base-class.js";

export class ColorPicker extends MicroweberBaseClass {

    constructor() {
        super();
        this.colorPickerInstances = [];
        this.position = {};
    }

    setPosition(x, y) {
        this.position = {
            x: x,
            y: y
        };
    }

    selectColor(targetElementSelector, callback = false) {

        if (this.colorPickerInstances.length > 0) {
            for (let i = 0; i < this.colorPickerInstances.length; i++) {
                this.colorPickerInstances[i].remove();
            }
        }

        var target = $(targetElementSelector)[0];
        let randId = this.generateRandId(10);

        let colorPickerDialog = mw.top().dialog({
            content: '<div id="color-picker-'+randId+'" style="width:232px;height:325px;"></div>',
            title: 'Color Picker',
            footer: false,
            width: 230,
            overlayClose: true,
            position: this.position
        });

        if (colorPickerDialog.dialogContainer) {
            colorPickerDialog.dialogContainer.style.padding = '0px';
        }
        if (colorPickerDialog.overlay) {
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';
        }

        this.colorPickerInstances.push(colorPickerDialog);
        
        mw.top().colorPicker({
            element: '#color-picker-' + randId,
            onchange: function (color) {

                target.value = color;
                target.dispatchEvent(new Event('input'));

                if (callback) {
                    callback(color);
                }
            }
        });

    }

    generateRandId(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    }

}
