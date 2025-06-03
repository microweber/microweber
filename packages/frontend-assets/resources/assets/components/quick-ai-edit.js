import MicroweberBaseClass from "../api-core/services/containers/base-class.js";
import {AIChatForm} from "./ai-chat.js";


const elementSchema = {
    "type": "object",
    "properties": {
        "tag": {
            "type": "string"
        },
        "text": {
            "type": "string"
        },

        "id": {
            "type": "string"
        }
    },
    "required": [
        "tag",
        "text",

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

    img(obj) {


        const frag = document.createElement("div");

        frag.$$ref = obj;
        frag.innerHTML = `

            <img src="${obj.node.src}">

            <nav>

            </nav>

        `;

        const changeBTN = document.createElement('button');
        changeBTN.innerHTML = mw.lang('Change image');
        const img = frag.querySelector('img')

        const nav = frag.querySelector('nav');
        nav.appendChild(changeBTN);
        img.addEventListener('click', e => {
            obj.node.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});

            mw.top().app.liveEdit.handles.get('element').set(obj.node)

            mw.top().app.liveEdit.handles.get('module').hide();
            mw.top().app.liveEdit.handles.get('layout').hide();
        })
        changeBTN.addEventListener('click', e => {


            let dialog;

            const onResult = data => {
                img.src = data[0];
                obj.node.src = data[0];
                dialog.remove()
            }
            var picker = new mw.filePicker({
                type: 'images',
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                onResult: onResult,
                okLabel: mw.lang('Select image'),
            });
            dialog = mw.top().dialog({
                content: picker.root,
                title: mw.lang('Select image'),
                footer: false,
                width: 860,
            });
            picker.$cancel.on('click', function () {
                dialog.remove()
            })


            $(dialog).on('Remove', () => {


            })
        })


        return frag
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

        inp.addEventListener('focus', e => {
            obj.node.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
            mw.top().app.liveEdit.handles.get('element').set(obj.node);
            mw.top().app.liveEdit.handles.get('module').hide();
            mw.top().app.liveEdit.handles.get('layout').hide();


        })

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
        const skipTags = [ 'IFRAME'];
        return Array
            .from(editNode.querySelectorAll(this.component.settings.nodesSelector))
            .filter(node => {
                return nestedEdits.length === 0 || !nestedEdits.find(edit => this.#editNodeContains(edit, node))
            })
            .map(node => {
                if (skipTags.indexOf(node.nodeName ) !== -1 ){
                    return;
                }

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
                    const onres = onNode.call(this.component, curr, node);
                    if(onres !== false) {
                        return curr;
                    }
                } else {
                    return curr
                }

            })
            .filter(itm => !!itm);
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

            if (onNode) {
                const onres = onNode.call(this.component, curr, edit);
                if(onres !== false) {
                    result.push(curr);
                }
            } else {
                result.push(curr);
            }

        }

        return result;
    }

    collectImages(edits, toJson) {
        const result = [];
        this.collect(edits, toJson, (curr, node) => {
            if(node.nodeName === 'IMG') {
                result.push(curr);
            }
        });
        return result;
    }

    collectTexts(edits, toJson) {
        return this.collect(edits, toJson, (curr, node) => {

            return node.nodeName !== 'IMG';
        });
    }


    toJSON(onNode) {
        return this.collect(undefined, true, onNode);
    }

}




const sendToChat = (messages, messagesOptions) => (MwAi().sendToChat(messages, messagesOptions).then(data => [data, null]).catch(err => [null, err]));
const generateImage =  (messages, messagesOptions) =>(MwAi().generateImage(messages, messagesOptions).then(data => [data, null]).catch(err => [null, err]));


const defaultAiTextAdapter = async (message, options) => {

    if (window.MwAi) {
        let messages = [{role: 'user', content: message}];
        let [res, err] = await sendToChat(messages, options);
        if (res) {
            return res;

        } else {
            return {
                succcess: false,
                message: 'Error'
            }
        }
    }

}


const defaultAiImagesAdapter = async (message, numberOfImages = 1, messagesOptions) => {

        let messages = [{role: 'user', content: message}];
        const arr = Array.from({length: numberOfImages}).map(() => generateImage(messages, messagesOptions))

        let res = await Promise.all(arr);

        res = res.map(itm => itm[0]).filter(itm => !!itm);

        if (res) {
            return res;

        } else {
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
            nodesSelector: 'h1,h2,h3,h4,h5,h6,p,img',
            editsSelector: '.edit[rel][field]:not(.module)',
            aiTextAdapter: defaultAiTextAdapter,
            aiImagesAdapter: defaultAiImagesAdapter,
        }
        this.settings = Object.assign({}, defaults, options);
        this.api = new QuickEditService(this);
        this.gui = new QuickEditGUI(this);

        this.aiTextAdapter = this.settings.aiTextAdapter;
        this.aiImagesAdapter = this.settings.aiImagesAdapter;
        this.editMode = 'whole-page'; // Add default edit mode

        this.on('change', obj => {

            obj.node.textContent = obj.text;

            mw.top().app.registerChangedState(obj.node);


        })

        top.p = this;
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

    applyImages(images = []) {
        const canvasNodes = this.canvasNodes.filter(node => node.nodeName === 'IMG')
        const editorNodes = this.editorNodes.filter(node => node.$$ref.tag === 'IMG')
        images.forEach((img, i) => {
            canvasNodes[i].src = img;
            editorNodes[i].querySelector('img').src = img;
            mw.top().app.registerChangedState(canvasNodes[i]);
        })
    }

    applyJSON(json = [], extend = true) {

        // Handle case where json is wrapped in a response object
        if (json.success === true && json.data) {
            json = json.data;
        }

        // Handle direct data property
        if (json.data) {
            json = json.data;
        }

        // Check for content array at the top level
        if (json.content && Array.isArray(json.content)) {
            json = json.content;
        }
        if (json.items && Array.isArray(json.items)) {
            json = json.items;
        }

        // Handle case where json is a single object rather than an array
        if (!Array.isArray(json)) {
            json = [json];
        }

        // Enhanced recursive function to process nodes at any depth
        const processAllNodesAtAnyDepth = (node) => {
            // Base case: if node is null or undefined, just return
            if (!node) return;

            // 1. Process this individual node if it has text content
            if (node.id && node.text !== undefined) {
                const input = document.getElementById(`data-node-id-${node.id}`);
                const target = this.settings.document.getElementById(`${node.id}`);



                if (input) {
                    input.value = node.text;

                }

                if (target) {
                    target.textContent = node.text;
                    mw.top().app.registerChangedState(target);

                }
            }

            // 2. Check if this is a content array directly
            if (Array.isArray(node)) {
                node.forEach(item => processAllNodesAtAnyDepth(item));
                return;
            }

            // 3. Process content property (if it exists)
            if (node.content) {
                if (Array.isArray(node.content)) {
                    // Process array of content
                    node.content.forEach(item => processAllNodesAtAnyDepth(item));
                } else {
                    // Process single content object
                    processAllNodesAtAnyDepth(node.content);
                }
            }

            // 4. Process children property (if it exists)
            if (node.children) {
                if (Array.isArray(node.children)) {
                    // Process array of children
                    node.children.forEach(child => processAllNodesAtAnyDepth(child));
                } else {
                    // Process single child object
                    processAllNodesAtAnyDepth(node.children);
                }
            }
        };

        // Process all top-level nodes using the enhanced recursive function
        json.forEach(node => {
            processAllNodesAtAnyDepth(node);
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
                let type = 'text';
                if (obj.tag === 'IMG') {
                    type = 'img'
                }
                const node = this.gui.build(obj, type);
                enodes.push(node);
                nodes.push(obj.node);

                // Get the parent section for grouping
                const parentEdit = obj.node.closest('.edit');
                let parentEditClosesIdElement = parentEdit.closest('id');
                let parentEditClosesId = null;
                if (parentEditClosesIdElement) {
                    parentEditClosesId = parentEditClosesId.id;
                }

                const sectionId = parentEdit ? parentEdit.getAttribute('field') + parentEdit.getAttribute('rel') + parentEditClosesId : 'default';

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
            section.className = 'quick-ai-card';
            section.dataset.sectionId = sectionId; // Store section ID for future reference

            // Create container for styled HR-like header with text in the middle
            const header = document.createElement('div');
            header.className = 'quick-ai-card-header';




            // Create the text element - use the title from fieldGroups
            const headerText = document.createElement('span');
            headerText.className = 'header-text fw-bold text-secondary';
            headerText.textContent = fieldGroups[sectionId].title;



            // Assemble the header

            header.appendChild(headerText);


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

        editor.appendChild(this.aiGUI());

        this.canvasNodes = nodes;
        this.editorNodes = enodes;

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

    collectTexts(edits, toJson) {
        return this.api.collectTexts(edits, toJson)
    }

    collectImages(edits, toJson) {
        return this.api.collectImages(edits, toJson)
    }

    aiGUI() {
        const chatOptions = [
            {id: 'images', content: mw.lang('Regenerate Images')},
            {id: 'text', content: mw.lang('Regenerate texts')},
            {id: 'all', content: mw.lang('Regenerate texts and images'), selected: false},
        ];
        const aiChatForm = new AIChatForm({
            chatOptions,
        });

        this.aiChatForm = aiChatForm;

        this.chatOption = 'all';


        aiChatForm.on("chatOptionChange", value => {
            this.chatOption = value;
        });

        aiChatForm.on("submit", async value => {

            const val = value.trim();
            aiChatForm.disable();
            await this.ai(val);
            aiChatForm.enable();
        });


        return aiChatForm.form;
    }

    #aiPending = false

    async ai(about) {
        console.log(this.collectTexts());
        if (this.#aiPending) {
            return;
        }

        this.#aiPending = true;
        this.dispatch('aiRequestStart');

        const message = `Using the existing object IDS,
        By using this schema: \n ${JSON.stringify(this.schema())} \n

        Write text to this object and populate the content and children file of the schema items,

        You are a website content writer, and you must write the text in a way that is relevant to the object,

        The website subject is: ${about}

        You must write the text for the website and will the existing object IDs with the text,
        Expand on the subject and try to fill and write relevant information in the existing text


        critical: do not change element IDS,
        critical: do not change the structure of the schema,
        critical: use only the existing object IDS,
        critical: do not assign any new object IDS,
        critical: return only valid json object,
        critical: do not change the schema structure,
        critical: do not change the existing object IDS,
        critical: keep the existing object IDS,
        critical: write text in the existing object IDS's content text node




        content: \n ${JSON.stringify(this.collectTexts(undefined, true))}



You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Use the content, populate the content and children of the items  with text ... write the text in the content and children with the existing object IDs and the text in the 'text' node for each object with the existing object IDS }

"""


        `;

        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).show();

        let messageOptions = {};
        //messageOptions.schema = this.schema();
        //   messageOptions.schema = editSchema;
        messageOptions.schema = editSchema;


        if(this.chatOption === 'all' || this.chatOption === 'text') {
            let textRes = await this.aiTextAdapter(message, messageOptions);

            if (textRes.success  && textRes.data?.content) {
                this.applyJSON(textRes.data.content);

            } else if (textRes.success && textRes.data) {
                this.applyJSON(textRes.data);

            } else {
                console.error(textRes.message);
            }
        }



        if(this.chatOption === 'all' || this.chatOption === 'images') {
            let imageRes = await this.aiImagesAdapter(about, this.collectImages(undefined, true).length);
            this.applyImages(imageRes)

        }



        mw.top().spinner(({element: mw.top().doc.body, size: 60, decorate: true})).remove();

        this.#aiPending = false;
        this.dispatch('aiRequestEnd');
    }


}
