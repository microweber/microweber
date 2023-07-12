import MicroweberBaseClass from "../containers/base-class.js";

export class ColorPicker extends MicroweberBaseClass {

    selectColor(targetElementSelector, callback = false) {

        var target = $(targetElementSelector)[0];

        let colorPickerDialog = mw.top().dialog({
            content: '<div id="color-picker-{{$md5name}}"></div>',
            title: 'Color Picker',
            footer: false,
            width: 230,
            overlayClose: true
        });
        colorPickerDialog.dialogContainer.style.padding = '0px';
        colorPickerDialog.overlay.style.backgroundColor = 'transparent';

        mw.top().colorPicker({
            element: '#color-picker-{{$md5name}}',
            onchange: function (color) {

                // element.style.background = color;

                target.value = color;
                target.dispatchEvent(new Event('input'));

                if (callback) {
                    callback(color); 
                }
            }
        });


    }

}
