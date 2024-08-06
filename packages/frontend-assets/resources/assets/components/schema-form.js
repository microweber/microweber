
import MicroweberBaseClass from "../containers/base-class.js";

class FieldBaseClass extends MicroweberBaseClass {
    constructor() {
        super();
    }

    #value = null;

    $setValue(value, dispatch = true) {
        if(value === this.#value) {
            return this;
        }
        this.#value = value;
        this.dispatch('$change', this.#value);
        if(dispatch) {
            this.dispatch('change', this.#value);
        }

        return this;
    }

    getValue() {
        return this.#value
    }
}


class Select extends FieldBaseClass {
    constructor(options = {}) {
        super();
        const defaults = {
            id: mw.id(`field-`),
            name: mw.id(`field-name-`),
            value: '',
            label: '',
            placeholder: '',
            options: [
                // {value: 1, title: 'Option 1'},
            ],


        }
        this.settings = Object.assign({}, defaults, options)
    }

    setValue(value, dispatch) {
        this.select.value = value;
        this.$setValue(value, dispatch);
    }

    onRendered() {
        this.select.addEventListener('input', e => {
            this.$setValue(this.select.value);
        });
    }

    render() {
        return `
        <div>
            <label class="form-label" for="${this.settings.id}">${this.settings.label}</label>
            <select
                ref="select"
                type="text"
                class="form-select"
                id="${this.settings.id}"
                name="${this.settings.name}"
                ${this.settings.placeholder ? ` placeholder="${this.settings.placeholder}" ` : ``}
                ${this.settings.required ? `  required ` : ``}
                >
                ${this.settings.placeholder ? ` <option selected disabled>${this.settings.placeholder} ` : ``}

                ${this.settings.options.map(obj => `<option ${obj.value == this.settings.value ? ' selected ' : ''} value="${obj.value}">${obj.title}</option>`).join('')}
            </select>
        </div>
        `;
    }
}

class Checkbox extends FieldBaseClass {
    constructor(options = {}) {
        super();
        const defaults = {
            id: mw.id(`field-`),
            name: mw.id(`field-name-`),
            value: [1, 2],
            label: '',

            options: [
                // {value: 1, title: 'Option 1'},
                // {value: 2, title: 'Option 2'},
            ],


        }
        this.settings = Object.assign({}, defaults, options)
    }

    setValue(value, dispatch) {
        const fields = this.checkboxList.querySelectorAll('input');
        for (let i = 0; i < value.length; i++) {
            for (let i2 = 0; i2 < fields.length; i2++) {
                fields[i].checked = fields[i].value == value[i]
            }
        }

        this.$setValue(value, dispatch);
    }
    syncValue() {
        const value = [];
        const fields = this.checkboxList.querySelectorAll('input');
        for (let i = 0; i < fields.length; i++) {
            if(fields[i].checked) {
                value.push(fields[i].value);
            }
        }
        this.$setValue(value, true);
    }

    onRendered() {
        this.checkboxList.querySelectorAll('input').forEach(node => {
            node.addEventListener('input', e => {
                this.syncValue()
            });
        })
    }

    render() {
        return `
        <div>
            <label class="form-label">${this.settings.label}</label>
            <div ref="checkboxList">
                ${this.settings.options.map(obj => {
                    const id = mw.id(`field-`)
                    return `
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="${obj.value}"
                                id="${id}"
                                ${obj.value == this.settings.value ? ' checked ' : ''}>
                            <label class="form-check-label" for="${id}" >
                                ${obj.title}
                            </label>
                        </div>`
                }).join('')}
            </div>
        </div>
        `;
    }
}

class Text extends FieldBaseClass {
    constructor(options = {}) {
        super();
        const defaults = {
            id: mw.id(`field-`),
            name: mw.id(`field-name-`),
            value: '',
            label: '',
            placeholder: '',

        }
        this.settings = Object.assign({}, defaults, options)
    }

    setValue(value, dispatch) {
        this.field.value = value;
        this.$setValue(value, dispatch);
    }

    onRendered() {
        this.field.addEventListener('input', e => {
            this.$setValue(this.field.value);
        });
    }

    render() {
        return `
        <div>
            <label class="form-label" for="${this.settings.id}">${this.settings.label}</label>
            <input
                type="text"
                ref="field"
                class="form-control"
                id="${this.settings.id}"
                name="${this.settings.name}"
                ${this.settings.placeholder ? ` placeholder="${this.settings.placeholder}" ` : ``}
                ${this.settings.required ? `  required ` : ``}
                >
        </div>`;
    }
}



class Email extends FieldBaseClass {

    constructor(options = {}) {
        super();
        const defaults = {
            id: mw.id(`field-`),
            name: mw.id(`field-name-`),
            value: '',
            label: '',
            placeholder: '',

        }
        this.settings = Object.assign({}, defaults, options)
    }

    setValue(value, dispatch) {
        this.field.value = value;
        this.$setValue(value, dispatch);
    }

    onRendered() {
        this.field.addEventListener('input', e => {
            this.$setValue(this.field.value);
        });
    }

    render() {
        return `
            <div>
                <label class="form-label" for="${this.settings.id}">${this.settings.label}</label>
                <input
                    ref="field"
                    type="email"
                    class="form-control"
                    id="${this.settings.id}"
                    name="${this.settings.name}"
                    ${this.settings.placeholder ? ` placeholder="${this.settings.placeholder}" ` : ``}
                    ${this.settings.value ? ` value="${this.settings.value}" ` : ``}
                    ${this.settings.required ? `  required ` : ``}
                >
            </div>
        `;
    }
}


const types = {
    text: Text,
    email: Email,
    select: Select,
    checkbox: Checkbox,
}

export class SchemaForm extends MicroweberBaseClass {
    constructor(options = {}) {
        super();

        const defaults = {
            data: [],
            target: null
        }
        this.settings = Object.assign({}, defaults, options);
        this.fields = [];
        this.root = document.createElement("div");
        this.render();
    }

    getValue() {

        return this.fields.map(field => {
            return {
                name: field.name,
                value: field.getValue(),
            }
        })
    }

    setValue(value, dispatch) {
        for (let i = 0; i < value.length; i++) {
            const field = this.getFieldByName(value[i].name);
            if(field) {
                field.setValue(value[i].value, dispatch);
            }
        }
    }

    getFieldByName(name) {
        return this.fields.find(field => field.name === name);
    }

    renderSingle(curr) {
        const field = new types[curr.type](curr);
        field.name = curr.name;
        const node = mw.element(field.render());
        this.root.appendChild(node.get(0));

        const refs = node.get(0).querySelectorAll('[ref]');
        for (let i = 0; i < refs.length; i++) {
            field[refs[i].getAttribute('ref')] = refs[i]
        }

        if(field.onRendered) {
            field.onRendered()
        }
        field.on('change', val => {
            this.dispatch('change', this.getValue());
        })
        this.fields.push(field);
    }

    render() {
        this.root.innerHTML = '';
        this.fields = [];
        for (let i = 0; i < this.settings.data.length; i++) {
            const curr = this.settings.data[i];
            if(curr.type && types.hasOwnProperty(curr.type)) {
                this.renderSingle(curr);
            }
        }
        if(typeof this.settings.target === "string") {
            this.settings.target = document.querySelector(this.settings.target);
        }
        if(this.settings.target) {

            this.settings.target.appendChild(this.root);
        }
        this.setValue(this.settings.data, false);
        this.dispatch('ready', this.getValue());
    }
}
