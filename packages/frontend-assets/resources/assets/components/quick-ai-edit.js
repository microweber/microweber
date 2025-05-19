import MicroweberBaseClass from "../api-core/services/containers/base-class.js";


const elementSchema = {
    "type": "object",
    "properties": {
        "tag": {
            "type": "string"
        },
        "text": {
            "type": "string"
        },
        "html": {
            "type": "string"
        },
        "id": {
            "type": "string"
        }
    },
    "required": [
        "tag",
        "text",
        "html",
        "id"
    ]
};

const editSchema = {
    "type": "object",
    "$id": "schema:edit",
    "properties": {
        "id": {
            "type": "string"
        },
        "tag": {
            "type": "string"
        },
        "rel": {
            "type": "string"
        },
        "field": {
            "type": "string"
        },
        "content": {
            "type": "array",
            "items": elementSchema
        },
        "children": {
            "type": "array",
            "items": {
                "$ref": "schema:edit"
            }
        }
    },
    "required": [
        "tag",
        "rel",
        "field",
        "id"
    ]
}

const JSONSchema = {
    "$schema": "http://json-schema.org/draft-07/schema#",
    "type": "object",
    "items": editSchema
}


class QuickEditGUI {
    constructor(instance) {
        this.instance = instance;
    }

    static _text(obj) {
        return `
            <div class="form-control-live-edit-label-wrapper my-4">
                <label class="live-edit-label">${obj.tag}</label>
                <input class="form-control-live-edit-input" value="${obj.text}" id="data-node-id-${obj.id}">
            </div>
        `;
    }

    build(obj, type = 'text') {
        const node = this[type](obj)
        return node;
    }


    text(obj) {
        const frag = document.createElement("div");
        frag.innerHTML = QuickEditGUI._text(obj);
        const inp = frag.querySelector("input");
        inp.$$ref = obj;


        obj.node.$$ref = inp;

        inp.addEventListener('input', () => {
            this.instance.pause();
            obj.text = inp.value;
            this.instance.dispatch('change', obj);
            this.instance.play();
        });

        frag.firstElementChild.$$ref = obj;

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

    static node = {
        text: node => node.textContent.trim(),
        html: node => node.innerHTML.trim(),
    }

    #collectSingle(editNode, toJson, onNode) {
        const nestedEdits = Array.from(editNode.querySelectorAll(this.component.settings.editsSelector));
        return Array
            .from(editNode.querySelectorAll(this.component.settings.nodesSelector))
            .filter(node => {
                return nestedEdits.length === 0 || !nestedEdits.find(edit => this.#editNodeContains(edit, node))
            })
            .map(node => {


                var can = mw.app.liveEdit.canBeEditable(node)
                if (!can) {
                    return;
                }


                if (!node.id) {
                    node.id = mw.id();
                }

                const curr = {
                    tag: node.nodeName,
                    text: node.textContent,
                    html: node.innerHTML,
                    id: node.id,
                };

                if (!toJson) {
                    curr.node = node;
                }
                if (onNode) {
                    onNode.call(undefined, curr)
                }
                return curr
            });
    }


    collect(edits, toJson, onNode) {
        const result = [];
        edits = edits || Array.from(this.component.settings.root.querySelectorAll(this.component.settings.editsSelector));

        while (edits.length > 0) {
            const edit = edits[0];
            edits.splice(0, 1);
            const children = Array.from(edit.querySelectorAll(this.component.settings.editsSelector));
            const curr = {

                id: edit.id,
                tag: edit.tagName,
                rel: edit.getAttribute('rel'),
                field: edit.getAttribute('field'),
                content: this.#collectSingle(edit, toJson, onNode)
            }
            if (!toJson) {
                curr.node = edit;
            }
            if (children.length) {
                edits = edits.filter(child => children.indexOf(child) === -1);
                curr.children = this.collect(children, toJson, onNode);
            }
            result.push(curr);
            if (onNode) {
                onNode.call(this.instance, curr)
            }

        }
        return result;
    }


    toJSON() {
        return this.collect(undefined, true);
    }

}

const defaultAiAdapter = async (message, options) => {

    if (window.MwAi) {

        let messages = [{role: 'user', content: message}];

        let messagesOptions = options;

        let res = await MwAi().sendToChat(messages, messagesOptions)
        //  res = JSON.parse(res);

        if (res && res.success && res.success == false && res.message) {
            return {
                succcess: false,
                message: res.message
            };

        }

        if (res && res.data) {
            return {
                succcess: true,
                data: res.data
            };
        }

        return {
            succcess: false,
            message: 'Error'
        }


    }

}



export class QuickEditComponent extends MicroweberBaseClass {
    constructor(options = {}) {
        super()
        const defaults = {
            document: mw.top().app.canvas.getDocument(),
            root: mw.top().app.canvas.getDocument().body,
            nodesSelector: 'h1,h2,h3,h4,h5,h6,p',
            editsSelector: '.edit[rel][field]:not(.module)',
            aiAdapter: defaultAiAdapter
        }
        this.settings = Object.assign({}, defaults, options);
        this.api = new QuickEditService(this);
        this.gui = new QuickEditGUI(this);

        this.aiAdapter = this.settings.aiAdapter;
        this.editMode = 'whole-page'; // Add default edit mode

        this.on('change', obj => {

            obj.node.textContent = obj.text;

            mw.top().app.registerChangedState(obj.node);


        })
    }

    #currentEditor = null;
    #observer = null;
    #currentNodes = [];
    #currentEditorNodes = [];

    #paused = false;

    pause() {
        this.#paused = true;
    }

    play() {
        this.#paused = false;
    }


    observer() {
        const config = {characterData: true, childList: true, subtree: true};

        const cache = new WeakMap();

        const getTarget = target => {
            const cached = cache.get(target);
            if (cached) {
                return cached;
            }
            let node = target;
            if (node.nodeType === 3) {
                node = node.parentElement;
            }

            while (!!node && !!node.ownerDocument && !node.matches(this.settings.nodesSelector) && node.nodeName !== 'HTML') {
                node = node.parentElement;
            }


            if (this.#currentNodes.indexOf(node) !== -1) {
                cache.set(target, node);
                return node;
            }

        }


        const callback = (mutationList, observer) => {
            for (const mutation of mutationList) {
                const target = getTarget(mutation.target);
                if (target && target.$$ref && !this.#paused) {
                    target.$$ref.value = target.textContent;
                }
            }
        };


        const observer = new MutationObserver(callback);


        observer.observe(this.settings.root || this.settings.document.body, config);

        this.#observer = observer;


    }

    applyJSON(json = [], extend = true) {
        // Handle case where json is wrapped in a response object
        if (json.data) {
            json = json.data;
        }

        // Handle case where json is a single object rather than an array
        if (!Array.isArray(json)) {
            json = [json];
        }

        const processNode = (node) => {
            // Process content array if it exists
            if (node.content && Array.isArray(node.content)) {
                node.content.forEach(obj => {
                    let skip = true;

                    if (obj && obj.id) {
                        skip = false;
                    }

                    if (!skip) {

                        const input = document.getElementById(`data-node-id-${obj.id}`);
                        const target = this.settings.document.getElementById(`${obj.id}`);

                        console.log(`Input field for ${obj.id}:`, input);
                        console.log(`Target element ${obj.tag}#${obj.id}:`, target);


                        if (input) {
                            input.value = obj.text;
                        } else {
                            console.log(`Input field for ${obj.id} not found`);
                        }

                        if (target) {
                            target.textContent = obj.text;

                            mw.top().app.registerChangedState(target);


                        } else {
                            console.log(`Target element ${obj.tag}#${obj.id} not found in document`);
                        }
                    }
                });
            }

            // Process children recursively
            if (node.children && Array.isArray(node.children)) {
                node.children.forEach(child => {
                    processNode(child);
                });
            }
        };

        // Process the top-level nodes
        json.forEach(node => {
            processNode(node);
        });
    }

    editor() {
        const editor = document.createElement("div");
        const nodes = [];
        const enodes = [];

        // Create a container to hold the organized edit fields
        const editFieldsContainer = document.createElement('div');
        editFieldsContainer.className = 'edit-fields-container';

        // Group objects by their parent edit section
        const fieldGroups = {};

        this.api.collect(undefined, undefined, obj => {
            if (obj.node.matches(this.settings.nodesSelector)) {
                const node = this.gui.build(obj);
                enodes.push(node);
                nodes.push(obj.node);

                // Get the parent section for grouping
                const parentEdit = obj.node.closest('.edit');
                let parentEditClosesIdElement = parentEdit.closest('id');
                let parentEditClosesId = null;
                if(parentEditClosesIdElement){
                    parentEditClosesId  =   parentEditClosesId.id;
                }

                const sectionId = parentEdit ? parentEdit.getAttribute('field') + parentEdit.getAttribute('rel') +parentEditClosesId : 'default';

                const sectionTitle = parentEdit ?
                    (parentEdit.getAttribute('id')
                        || parentEdit.getAttribute('field')
                        || parentEdit.getAttribute('rel')
                        || parentEdit.getAttribute('title')
                        || 'Content Elements'
                    ) :
                    'Content Elements';
                // Create the group if it doesn't exist
                if (!fieldGroups[sectionId]) {
                    fieldGroups[sectionId] = {
                        title: sectionTitle,
                        nodes: []
                    };
                }

                fieldGroups[sectionId].nodes.push(node);
            }
        });

        // Create card sections for each group
        Object.keys(fieldGroups).forEach(sectionId => {
            const section = document.createElement('div');
            section.className = 'card mb-4';
            section.dataset.sectionId = sectionId; // Store section ID for future reference

            // Create container for styled HR-like header with text in the middle
            const header = document.createElement('div');
            header.className = 'section-header-divider text-center position-relative my-3';

            // Apply the styling for the container and pseudo-elements
            header.style.cssText = `
                display: flex;
                align-items: center;
                margin: 15px 0;
                text-align: center;
            `;

            // Create the before line
            const beforeDiv = document.createElement('div');
            beforeDiv.className = 'header-line-before';
            beforeDiv.style.cssText = `
                flex-grow: 1;
                height: 1px;
                background-color: #dee2e6;
                margin-right: 15px;
            `;

            // Create the text element - use the title from fieldGroups
            const headerText = document.createElement('span');
            headerText.className = 'header-text fw-bold text-secondary';
            headerText.textContent = fieldGroups[sectionId].title;

            // Create the after line
            const afterDiv = document.createElement('div');
            afterDiv.className = 'header-line-after';
            afterDiv.style.cssText = `
                flex-grow: 1;
                height: 1px;
                background-color: #dee2e6;
                margin-left: 15px;
            `;

            // Assemble the header
            header.appendChild(beforeDiv);
            header.appendChild(headerText);
            header.appendChild(afterDiv);

            const body = document.createElement('div');
            body.className = 'card-body';

            fieldGroups[sectionId].nodes.forEach(field => {
                body.appendChild(field);
            });

            section.appendChild(header);
            section.appendChild(body);
            editFieldsContainer.appendChild(section);
        });

        // Add the organized fields container to the editor
        editor.appendChild(editFieldsContainer);

        this.#currentEditor = editor;
        this.#currentEditorNodes = enodes;
        this.#currentNodes = nodes;

        this.observer();

        editor.appendChild(this.aiGUI())

        return editor;
    }

    destroyEditor() {


        this.#currentEditorNodes = [];
        if (this.#currentEditor) {
            this.#currentEditor.remove();
        }

        this.#currentEditor = null;
        if (this.#observer) {
            this.#observer.disconnect();
        }

        this.#observer = null;
    }

    schema() {
        return JSONSchema;
    }

    toJSON() {
        return this.api.toJSON();
    }

    collect() {
        return this.api.collect();
    }

    aiGUI(prompt = '') {

        // Create a sticky chat container
        const editor = document.createElement("div");
        editor.className = "sticky-ai-chat-container";
        editor.style.position = "sticky";
        editor.style.bottom = "0";
        editor.style.background = "#fff";
        editor.style.padding = "0px";
        editor.style.borderTop = "1px solid #e5e5e5";
        editor.style.zIndex = "2";

        // Create dropdown for edit mode
        const modeSelector = `
            <div class="form-control-live-edit-label-wrapper my-4">
                <label class="live-edit-label">${mw.lang('Edit Mode')}</label>
                <div class="custom-select w-full">
                    <select class="form-control-live-edit-input edit-mode-selector">
                        <option value="whole-page">${mw.lang('Whole Page')}</option>
                        <option value="current-layout">${mw.lang('Current Layout')}</option>
                    </select>
                </div>
            </div>
        `;

        editor.innerHTML = `
            ${modeSelector}
            <div class="form-control-live-edit-label-wrapper my-4">
                <label class="live-edit-label">${mw.lang('Enter topic')}</label>
                <textarea class="form-control-live-edit-input" placeholder="${mw.lang('Car rental company')}">${prompt}</textarea>
            </div>

            <button type="button" class="btn btn-dark w-full live-edit-toolbar-buttons">${mw.lang('Submit')}</button>
        `;


        // Organize edit fields into card sections
        const organizeEditorContent = () => {
            // Get all existing edit field wrappers
            const editFields = Array.from(editor.querySelectorAll('.form-control-live-edit-label-wrapper')).filter(el => !el.closest('.sticky-ai-chat-container'));

            if (!editFields.length) return;

            // Remove existing edit fields
            editFields.forEach(field => field.remove());

            // Create sections for edit fields
            const editFieldsContainer = document.createElement('div');
            editFieldsContainer.className = 'edit-fields-container';

            // Group fields by their parent edit node (use $$ref to find the original object)
            const fieldGroups = {};
            editFields.forEach(field => {
                const ref = field.$$ref;
                const parentId = ref && ref.node ? (ref.node.closest('.edit') || {id: 'default'}).id : 'default';

                // Initialize the group object with title if it doesn't exist
                if (!fieldGroups[parentId]) {
                    fieldGroups[parentId] = {
                        title: parentId !== 'default' ? `Section: ${parentId}` : 'Content Elements',
                        nodes: []
                    };
                }

                fieldGroups[parentId].nodes.push(field);
            });

            // Create card sections for each group
            Object.keys(fieldGroups).forEach(groupId => {
                const section = document.createElement('div');
                section.className = 'card mb-4';
                section.dataset.sectionId = groupId; // Store section ID for future reference

                // Create container for styled HR-like header with text in the middle
                const header = document.createElement('div');
                header.className = 'section-header-divider text-center position-relative my-3';

                // Apply the styling for the container and pseudo-elements
                header.style.cssText = `
                    display: flex;
                    align-items: center;
                    margin: 15px 0;
                    text-align: center;
                `;

                // Create the before line
                const beforeDiv = document.createElement('div');
                beforeDiv.className = 'header-line-before';
                beforeDiv.style.cssText = `
                    flex-grow: 1;
                    height: 1px;
                    background-color: #dee2e6;
                    margin-right: 15px;
                `;

                // Create the text element - use the title from fieldGroups
                const headerText = document.createElement('span');
                headerText.className = 'header-text fw-bold text-secondary';
                headerText.textContent = fieldGroups[groupId].title;

                // Create the after line
                const afterDiv = document.createElement('div');
                afterDiv.className = 'header-line-after';
                afterDiv.style.cssText = `
                    flex-grow: 1;
                    height: 1px;
                    background-color: #dee2e6;
                    margin-left: 15px;
                `;

                // Assemble the header
                header.appendChild(beforeDiv);
                header.appendChild(headerText);
                header.appendChild(afterDiv);

                const body = document.createElement('div');
                body.className = 'card-body';

                fieldGroups[groupId].nodes.forEach(field => {
                    body.appendChild(field);
                });

                section.appendChild(header);
                section.appendChild(body);
                editFieldsContainer.appendChild(section);
            });

            // Insert before the sticky container
            editor.insertBefore(editFieldsContainer, editor);
        };

        // Apply organization after the editor content is populated
        setTimeout(organizeEditorContent, 100);

        const field = editor.querySelector('textarea');
        const button = editor.querySelector('button');
        const modeSelect = editor.querySelector('.edit-mode-selector');

        // Set the select to the current editMode value
        modeSelect.value = this.editMode;

        // Update the editMode when the select changes
        modeSelect.addEventListener("change", () => {
            this.editMode = modeSelect.value;
        });

        button.addEventListener("click", () => {
            const val = field.value.trim();
            this.ai(val);
        });

        button.disabled = !field.value.trim();

        field.addEventListener("input", () => button.disabled = !field.value.trim());
        field.addEventListener("focus", () => button.disabled = !field.value.trim());

        this.on('aiRequestStart', () => {
            button.disabled = true;
        });

        this.on('aiRequestEnd', () => {
            button.disabled = !field.value.trim();
        });

        return editor;
    }

    #aiPending = false

    async ai(about) {
        if (this.#aiPending) {
            return;
        }

        this.#aiPending = true;
        this.dispatch('aiRequestStart');

        const message = `Using the existing object IDS,
        By using this schema: \n ${JSON.stringify(this.schema())} \n
        Write text to this object and populate the schema,
        You are a website content writer, and you must write the text in a way that is relevant to the object,

        The website subject is: ${about}
        The edit mode is: ${this.editMode}

        You must write the text for the website and will the existing object IDs with the text,
        Expand on the subject and try to fill and write relevant information in the existing text


        do not change element IDS,
        do not change the structure of the schema,
        use only the existing object IDS,
        do not assign any new object IDS,
        return only valid json object,
        populate the existing object IDS: \n ${JSON.stringify(this.toJSON())}



You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Populated Schema Definition with the items filled with text ... populate the schema with the existing object IDs and the text  }

"""


        `;

        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).show();

        let messageOptions = {};
        //  messageOptions.schema = this.schema();
        messageOptions.schema = editSchema;


        let res = await this.aiAdapter(message, messageOptions);

        if (res.success && res.data) {
            this.applyJSON(res.data);
        } else {
            console.error(res.message);
        }

        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).remove();
        this.#aiPending = false;
        this.dispatch('aiRequestEnd');
    }
}


