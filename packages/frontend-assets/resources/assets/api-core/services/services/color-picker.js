import MicroweberBaseClass from "../containers/base-class.js";

export class ColorPicker extends MicroweberBaseClass {

    constructor() {
        super();
        this.colorPickerInstances = [];
        this.positionToElement = false;
    }

    setPositionToElement(element) {
        this.positionToElement = element;
    }
    openColorPicker(value, callback = false, node=false) {

        if (this.colorPickerInstances.length > 0) {
            for (let i = 0; i < this.colorPickerInstances.length; i++) {
                this.colorPickerInstances[i].remove();
            }
        }

        let randId = this.generateRandId(10);

        let colorPickerDialog = mw.top().dialog({
            content: '<div id="color-picker-'+randId+'" style="width:232px;min-height:325px;"></div>',
            title: 'Color Picker',
            footer: false,
            width: 260,
            overlayClose: true,
        });

        if(node) {
            colorPickerDialog.positionToElement(node);
        }

        if (colorPickerDialog.dialogContainer) {
            colorPickerDialog.dialogContainer.style.padding = '0px';
        }
        if (colorPickerDialog.overlay) {
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';
            colorPickerDialog.overlay.style.backdropFilter = 'none';
        }


        this.colorPickerInstances.push(colorPickerDialog);

        var options = {
            element: '#color-picker-' + randId,

            onchange: function (color) {
                if (callback) {
                    callback(color);
                }
            }
        };

        if(value == 'rgba(0, 0, 0, 0)'){
            value = '';
        }


        if(!value){
           // options.value = '#000000FF';
        } else {
            options.value = value;
        }


        mw.colorPicker(options);

        return colorPickerDialog;

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
            content: '<div id="color-picker-'+randId+'" style="width:232px;min-height:325px;"></div>',
            title: 'Color Picker',
            footer: false,
            width: 260,
            overlayClose: true,
        });
       colorPickerDialog.positionToElement(this.positionToElement);

        if (colorPickerDialog.dialogContainer) {
            colorPickerDialog.dialogContainer.style.padding = '0px';
        }
        if (colorPickerDialog.overlay) {
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';
            colorPickerDialog.overlay.style.backdropFilter = 'none';
        }

        this.colorPickerInstances.push(colorPickerDialog);



        mw.colorPicker({
            element: colorPickerDialog.dialogContainer.querySelector('#color-picker-' + randId),
            value: target.value,
            onchange: function (color) {

                target.value = color;
                target.dispatchEvent(new Event('input'));

                if (callback) {
                    callback(color);
                }
            }
        });
      //  colorPickerDialog.center();
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
