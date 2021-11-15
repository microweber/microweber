import {ElementManager} from "../classes/element";

class DefaultFilePickerAdapter {
    constructor(options) {
        if(!options) {
            options = {}
        }
        const defaults = {
            element: null,
            onResult: null
        }
        this.settings = Object.assign({}, defaults, options);
        this.create()
    }

    create() {
        const input = ElementManager({
            tag: 'input',
            props: {
                type: 'file',
                accept: 'image/*'
            }
        })
        input.on('input', () => {
            var reader = new FileReader();
            reader.readAsDataURL(input.get(0).files[0]);
            reader.onload = () => {
                if(this.settings.onResult) {
                    this.settings.onResult.call(this, reader.result)
                }
            };
        });
        this.settings.element.appendChild(input.get(0))
    }
}
