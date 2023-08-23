import MicroweberBaseClass from "../containers/base-class.js";

export class IconPicker extends MicroweberBaseClass {
    selectIcon(targetElementSelector) {
        var target = $(targetElementSelector)[0];
        mw.iconLoader().init();
        var picker = mw.iconPicker({iconOptions: false});
        picker.target = document.createElement('i');
        picker.on('select', function (data) {
            data.render();
            target.value = picker.target.outerHTML
            var event = new Event('input');
            target.dispatchEvent(event);

            picker.dialog('hide');
        });
        picker.dialog();
    }

    pickIcon(targetElementSelector, options = {}) {
        const defaults = {iconOptions: true};
        const settings = Object.assign({}, defaults, options);
        return new Promise(resolve => {
            var target = $(targetElementSelector)[0];
            mw.iconLoader().init();
            var picker = mw.iconPicker(settings);
            picker.target = target;
            picker.on('select', function (data) {
                data.render();
                picker.dialog('hide');
                resolve(data)
            });
            picker.dialog();
        })

    }

    removeIcon(targetElementSelector) {
        var target = $(targetElementSelector)[0];
        target.value = '';
        var event = new Event('input');
        target.dispatchEvent(event);
    }
}
