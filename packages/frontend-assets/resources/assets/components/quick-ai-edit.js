
import MicroweberBaseClass from "../api-core/services/containers/base-class.js";

class QuickEditGUI {
    constructor(instance){
        this.instance = instance;
    }
    static _text(obj) {
        return `
            <div class="form-control-live-edit-label-wrapper my-4">
                <label class="live-edit-label">${obj.tag}</label>
                <input class="form-control-live-edit-input" value="${obj.text}">
            </div>
        `;
    }

    build(obj, type = 'text') {
        return this[type](obj);
    }

    text(obj) {
        const frag = document.createElement("div");
        frag.innerHTML = QuickEditGUI._text(obj);
        const inp = frag.querySelector("input");
        inp.$$ref = obj;
        inp.addEventListener('input', () => {
            obj.text = inp.value;
            this.instance.dispatch('change', obj)
        });

        return frag.firstElementChild;
    }
}
class QuickEditService extends MicroweberBaseClass {
    constructor(instance) {
        super();
        this.component = instance;
        this.document = this.component.settings.document;
    }

    #editNodeContains(editNode, child) {
        return editNode.contains(child);
    }

    #collectSingle(editNode, toJson, onNode) {
        const nestedEdits = Array.from(editNode.querySelectorAll(this.component.settings.editsSelector));
        return Array
            .from(editNode.querySelectorAll(this.component.settings.nodesSelector))
            .filter(node => {
                return nestedEdits.length === 0 || !nestedEdits.find(edit => this.#editNodeContains(edit, node))
            })
            .map(node => {
                if(!node.id) {
                    node.id = mw.id();
                }

                const curr = {
                    tag: node.nodeName,
                    text: node.textContent,
                    html: node.innerHTML,
                    id: node.id,
                };

                if(!toJson) {
                    curr.node = node;
                }
                if(onNode) {
                    onNode.call(undefined, curr)
                }
                return curr
            });
    }


    collect(edits, toJson, onNode) {
        const result = [];
        edits = edits || Array.from(this.document.querySelectorAll(this.component.settings.editsSelector));

        while (edits.length > 0) {
            const edit = edits[0];
            edits.splice(0,1);
            const children = Array.from(edit.querySelectorAll(this.component.settings.editsSelector));
            const curr = {

                id: edit.id,
                tag: edit.tagName,
                rel: edit.getAttribute('rel'),
                field: edit.getAttribute('field'),
                content: this.#collectSingle(edit, toJson, onNode)
            }
            if(!toJson) {
                curr.node = edit;
            }
            if(children.length) {
                edits = edits.filter(child => children.indexOf(child) === -1);
                curr.children = this.collect(children, toJson, onNode);
            }
            result.push(curr);
            if(onNode) {
                onNode.call(this.instance, curr)
            }

        }
        return result;
    }

    toJSON() {
        return this.collect(undefined, true);
    }

}
export class QuickEditComponent extends MicroweberBaseClass {
    constructor(options = {}) {
        super()
        const defaults = {
            document: mw.top().app.canvas.getDocument(),
            nodesSelector: 'p,h1,h2,h3,h4,h5,h6',
            editsSelector: '.edit'
        }
        this.settings = Object.assign({}, defaults, options);
        this.api = new QuickEditService(this);
        this.gui = new QuickEditGUI(this);

        this.on('change', obj => {
            console.log(obj);
            obj.node.textContent = obj.text
        })
    }

    editor() {
        const editor = this.settings.document.createElement("div");
        this.api.collect(undefined, undefined, obj => {
            if(obj.node.matches(this.settings.nodesSelector)) {
                editor.appendChild(this.gui.build(obj))
            }
        });

        return editor;
    }

    toJSON() {
        return this.api.toJSON();
    }
    collect() {
        return this.api.collect();
    }
}

