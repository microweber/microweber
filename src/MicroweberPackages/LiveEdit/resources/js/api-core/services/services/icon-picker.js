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
        const defaults = {
            iconOptions: {
                color: true,
                size: true,
                reset: true,
            }
        };
        var target = $(targetElementSelector)[0];
        const settings = Object.assign({}, defaults, options, {target});
        
        mw.iconLoader().init();
        var picker = mw.iconPicker(settings);

        console.log(picker)

        const promise = () => new Promise(resolve => {
            picker.target = target;
            picker.on('select', function (data) {
                data.render();
                picker.dialog('hide');
                resolve(data)
            });
            picker.dialog();
        })

        return {
            promise, target, picker
        };
        
       

    }

    removeIcon(targetElementSelector) {
        var target = $(targetElementSelector)[0];
        target.value = '';
        var event = new Event('input');
        target.dispatchEvent(event);
    }
}
