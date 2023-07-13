import MicroweberBaseClass from "../containers/base-class.js";

export class ColorPicker extends MicroweberBaseClass {

    selectColor(targetElementSelector, callback = false) {

        var target = $(targetElementSelector)[0];

        let randId = this.generateRandId(10);

        let colorPickerDialog = mw.top().dialog({
            content: '<div id="color-picker-'+randId+'"></div>',
            title: 'Color Picker',
            footer: false,
            width: 230,
            overlayClose: true
        });
        if (colorPickerDialog.dialogContainer) {
            colorPickerDialog.dialogContainer.style.padding = '0px';
        }
        if (colorPickerDialog.overlay) {
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';
        }

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

}
