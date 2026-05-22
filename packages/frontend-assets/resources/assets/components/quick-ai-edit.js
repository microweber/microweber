import { DomService } from "../api-core/core/classes/dom.js";
import { ElementManager } from "../api-core/core/classes/element.js";
import MicroweberBaseClass from "../api-core/services/containers/base-class.js";
import { Helpers } from "../core/helpers.js";
import { AIChatForm } from "./ai-chat.js";
import { generateSiteInfoWithAI } from "./ai-site-description.service.js";

const elementSchema = {
    type: "object",
    properties: {
        tag: {
            type: "string",
        },
        text: {
            type: "string",
        },

        id: {
            type: "string",
        },
    },
    required: ["tag", "text", "id"],
};

const editSchema = {
    type: "object",
    $id: "schema:edit",
    properties: {
        id: {
            type: "string",
        },
        tag: {
            type: "string",
        },
        rel: {
            type: "string",
        },
        field: {
            type: "string",
        },
        content: {
            type: "array",
            items: elementSchema,
        },
        children: {
            type: "array",
            items: {
                $ref: "schema:edit",
            },
        },
    },
    required: ["tag", "id"],
};

const JSONSchema = {
    $schema: "http://json-schema.org/draft-07/schema#",
    type: "object",
    items: editSchema,
};

const tagMap = {
    P: mw.lang("Paragraph"),
    H1: mw.lang("Heading") + " 1",
    H2: mw.lang("Heading") + " 2",
    H3: mw.lang("Heading") + " 3",
    H4: mw.lang("Heading") + " 4",
    H5: mw.lang("Heading") + " 5",
    H6: mw.lang("Heading") + " 6",
};

/**
 * AI-711 / task-2026-05-16-b0b48f — Quick AI Edit per-section labels.
 *
 * Pre-AI-711 the panel labelled every text row with the verbose
 * `tagMap[obj.tag]` ("Heading 1", "Paragraph", …) so users couldn't
 * tell at a glance which input affected which DOM element. Designer
 * spec DESIGN_AUDIT.md L2.4 wants:
 *   - Heading rows: H1 / H2 / H3 (the tag name, no number — only
 *     one h1 per section by convention).
 *   - Paragraph rows: P1 / P2 / … (resets when a new heading
 *     appears within the section).
 *   - Other elements: BUTTON 1 / LINK 1 / IMAGE ALT 1 (numbered
 *     by type within the heading-bounded subsection).
 *
 * The numbering is per-subsection — encountering a new heading
 * inside a card resets the P / LINK / BUTTON / IMAGE-ALT counters
 * so labels read `H1 / P1 / P2 / H2 / P1` naturally.
 *
 * `computeQuickAiEditLabel(obj, sectionId, state)` mutates `state`
 * (passed in by the caller) so callers can hold their own per-card
 * counter map. Returning the computed string keeps the helper
 * pure-functional from the consumer's POV — just call and assign
 * to `obj.label`.
 */
function computeQuickAiEditLabel(obj, sectionId, state) {
    if (!state[sectionId]) {
        state[sectionId] = { p: 0, link: 0, button: 0, imgAlt: 0 };
    }
    const counters = state[sectionId];
    const tag = obj.tag;

    if (/^H[1-6]$/.test(tag)) {
        // Heading resets all non-heading counters within this
        // section so paragraph numbering restarts under each
        // heading per spec example: H1 / P1 / P2 / H2 / P1.
        state[sectionId] = { p: 0, link: 0, button: 0, imgAlt: 0 };
        return tag;
    }
    if (tag === "A") {
        counters.link += 1;
        return "LINK " + counters.link;
    }
    if (tag === "BUTTON") {
        counters.button += 1;
        return "BUTTON " + counters.button;
    }
    if (tag === "IMG") {
        counters.imgAlt += 1;
        return "IMAGE ALT " + counters.imgAlt;
    }
    if (
        tag === "P" ||
        tag === "SPAN" ||
        tag === "DIV" ||
        tag === "LI" ||
        tag === "BLOCKQUOTE"
    ) {
        counters.p += 1;
        return "P" + counters.p;
    }
    // Fallback: raw tag uppercase (unchanged from pre-AI-711 default
    // when tag wasn't in `tagMap`).
    return tag;
}

class QuickEditGUI {
    constructor(instance) {
        this.instance = instance;
    }

    static _text(obj) {
        // AI-711 / task-2026-05-16-b0b48f — prefer the per-section
        // computed `obj.label` (e.g. "H1", "P1", "P2", "LINK 1")
        // when the caller has populated it; fall back to the legacy
        // verbose tagMap copy for callers that haven't migrated to
        // the per-section counter pattern yet.
        return `
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">${
                    obj.label || tagMap[obj.tag] || obj.tag
                }</label>
                <input class="form-control-live-edit-input" value="${
                    obj.text
                }" id="data-node-id-${obj.id}">
            </div>
        `;
    }

    build(obj, type = "text") {
        const node = this[type](obj);
        return node;
    }

    img(obj) {
        const frag = document.createElement("div");

        const scope = this.instance;

        frag.$$ref = obj;
        frag.className = `relative my-4`;
        frag.innerHTML = `

            <img src="${obj.node.src}">

            <nav>

            </nav>

        `;

        const changeBTN = document.createElement("button");
        changeBTN.className =
            "btn btn-dark btn-icon absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2";
        changeBTN.title = mw.lang("Change image");
        changeBTN.innerHTML = mw.top().app.iconService.icon("image-change");
        const img = frag.querySelector("img");

        const nav = frag.querySelector("nav");
        nav.appendChild(changeBTN);
        img.addEventListener("click", (e) => {
            obj.node.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "start",
            });

            mw.top().app.liveEdit.handles.get("element").set(obj.node);

            mw.top().app.liveEdit.handles.get("module").hide();
            mw.top().app.liveEdit.handles.get("layout").hide();
        });
        changeBTN.addEventListener("click", (e) => {
            let dialog;

            const onResult = (data) => {
                scope.pausedSync(true);
                img.src = data[0];
                obj.node.src = data[0];
                dialog.remove();

                scope.unPauseSync();
            };
            var picker = new mw.filePicker({
                type: "images",
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                onResult: onResult,
                okLabel: mw.lang("Select image"),
            });
            dialog = mw.top().dialog({
                content: picker.root,
                title: mw.lang("Select image"),
                footer: false,
                width: 860,
            });
            picker.$cancel.on("click", function () {
                dialog.remove();
            });

            $(dialog).on("Remove", () => {});
        });

        return frag;
    }

    layoutBackground(obj) {
        const scope = this.instance;

        const frag = document.createElement("div");

        frag.$$ref = obj;
        frag.className = `relative flex content-center justify-center`;
        frag.style.minHeight = `100px`;

        const src =
            obj.node.style.backgroundImage
                .slice(4, -1)
                .replace(/"/g, "")
                .replace(/['"]/g, "") ||
            `data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==`;

        frag.innerHTML = `

            <img src="${src}">

            <nav>

            </nav>

        `;

        const changeBTN = document.createElement("button");
        changeBTN.className =
            "btn btn-dark btn-icon absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2";
        changeBTN.title = mw.lang("Change image");
        changeBTN.innerHTML = mw.top().app.iconService.icon("image-change");
        const img = frag.querySelector("img");

        const nav = frag.querySelector("nav");
        nav.appendChild(changeBTN);
        img.addEventListener("click", (e) => {
            obj.node.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "start",
            });

            mw.top().app.liveEdit.handles.get("element").set(obj.node);

            mw.top().app.liveEdit.handles.get("module").hide();
            mw.top().app.liveEdit.handles.get("layout").hide();
        });
        changeBTN.addEventListener("click", (e) => {
            let dialog;

            const onResult = (data) => {
                scope.pausedSync();
                const module = obj.node.closest(".module-background");
                obj.node.style.backgroundImage = `url(${data[0]})`;
                const curr = module.dataset.mwTempOptionSave;
                let json = [];
                if (curr) {
                    try {
                        json = JSON.parse(curr);
                    } catch (e) {
                        json = [];
                    }
                }

                let target = json.find(
                    (o) => o.key === "data-background-image"
                );
                if (!target) {
                    target = {
                        group: module.id,
                        key: "data-background-image",
                        module: "background",
                        value: data[0],
                    };

                    json.push(target);
                } else {
                    target.value = data[0];
                }

                module.setAttribute(
                    "data-mw-temp-option-save",
                    JSON.stringify(json)
                );
                dialog.remove();

                scope.unPauseSync();
            };
            var picker = new mw.filePicker({
                type: "images",
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                onResult: onResult,
                okLabel: mw.lang("Select image"),
            });
            dialog = mw.top().dialog({
                content: picker.root,
                title: mw.lang("Select image"),
                footer: false,
                width: 860,
            });
            picker.$cancel.on("click", function () {
                dialog.remove();
            });

            $(dialog).on("Remove", () => {});
        });

        return frag;
    }

    module(obj) {
        const node = obj.node;
        if (!node) {
            return;
        }

        const frag = ElementManager(`
            <div class="py-2">
                <div class="btn btn-icon module-settings-button gap-2 cursor-pointer justify-start">
                        ${mw.top().app.modules.getModuleIcon(node.dataset.type)}
                        ${node.dataset.title || node.dataset.type}

                </div>
            </div>
        `);

        frag.on("click", function () {
            mw.top()
                .app.canvas.getWindow()
                .mw.tools.scrollTo(node, undefined, 100);

            mw.top().app.editor.dispatch("onModuleSettingsRequest", node);
        });

        frag.$$ref = obj;

        return frag.get(0);
    }

    text(obj) {
        const frag = document.createElement("div");
        frag.innerHTML = QuickEditGUI._text(obj);
        const inp = frag.querySelector("input");
        inp.$$ref = obj;

        obj.node.$$ref = inp;

        inp.addEventListener("input", () => {
            this.instance.pauseSync();
            this.instance.pause();
            obj.text = inp.value;
            this.instance.dispatch("change", obj);
            this.instance.play();
        });

        inp.addEventListener("blur", (e) => {
            this.instance.unPauseSync();
        });
        inp.addEventListener("focus", (e) => {
            obj.node.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center",
            });
            mw.top().app.liveEdit.handles.get("element").set(obj.node);
            mw.top().app.liveEdit.handles.get("module").hide();
            mw.top().app.liveEdit.handles.get("layout").hide();
            this.instance.pauseSync();
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
        text: (node) => node.textContent.trim(),
        html: (node) => node.innerHTML.trim(),
    };

    #collectSingle(editNode, toJson, onNode) {
        const nestedEdits = Array.from(
            editNode.querySelectorAll(this.component.settings.editsSelector)
        );
        const skipTags = ["IFRAME"];
        const skipClasses = [
            "module-layouts",
            "module-spacer",
            "module-background",
        ];

        return Array.from(
            editNode.querySelectorAll(this.component.settings.nodesSelector)
        )
            .filter((node) => {
                return (
                    nestedEdits.length === 0 ||
                    !nestedEdits.find((edit) =>
                        this.#editNodeContains(edit, node)
                    )
                );
            })
            .map((node) => {
                if (skipTags.indexOf(node.nodeName) !== -1) {
                    return;
                }

                if (DomService.hasAnyOfClasses(node, skipClasses)) {
                    return;
                }

                if (!node) {
                    return;
                }
                if (!mw.app.liveEdit) {
                    console.warn(
                        "Live Edit is not initialized, skipping node collection"
                    );
                    return;
                }

                var can =
                    mw.app.liveEdit.canBeEditable(node) ||
                    node.classList.contains("mw-layout-background-node");
                if (!can && !node.classList.contains("module")) {
                    return;
                }

                if (!node.id) {
                    node.id = mw.id();
                }

                const curr = {
                    tag: node.nodeName,
                    text: node.textContent,
                    //html: node.innerHTML,
                    id: node.id,
                };

                if (!toJson) {
                    curr.node = node;
                }
                if (onNode) {
                    const onres = onNode.call(this.component, curr, node);
                    if (onres !== false) {
                        return curr;
                    }
                } else {
                    return curr;
                }
            })
            .filter((itm) => !!itm);
    }

    collectx(edits, toJson, onNode) {
        const result = [];

        const children = Array.from(
            this.component.settings.root.querySelectorAll(
                this.component.settings.editsSelector
            )
        ).filter(
            (node) =>
                node.classList.contains("module") || Helpers.isEditable(node)
        );

        const curr = {
            id: this.component.settings.root.id,

            content: this.#collectSingle(
                this.component.settings.root,
                toJson,
                onNode
            ),
        };
        if (!toJson) {
            curr.node = this.component.settings.root;
        }
        if (children.length) {
            edits = edits.filter((child) => children.indexOf(child) === -1);
            curr.children = this.collect(children, toJson, onNode);
        }

        if (onNode) {
            const onres = onNode.call(
                this.component,
                curr,
                this.component.settings.root
            );
            if (onres !== false) {
                result.push(curr);
            }
        } else {
            result.push(curr);
        }

        return result;
    }

    collect(edits, toJson, onNode) {
        const result = [];
        edits =
            edits ||
            Array.from(
                this.component.settings.root.querySelectorAll(
                    this.component.settings.editsSelector
                )
            );

        if (edits.length === 0) {
            edits = [this.component.settings.root];
        }

        while (edits.length > 0) {
            const edit = edits[0];
            edits.splice(0, 1);
            const children = Array.from(
                edit.querySelectorAll(this.component.settings.editsSelector)
            ).filter(
                (node) =>
                    node.classList.contains("module") ||
                    Helpers.isEditable(node)
            );

            const curr = {
                content: this.#collectSingle(edit, toJson, onNode),
            };
            if (!toJson) {
                curr.node = edit;
            }
            if (children.length) {
                edits = edits.filter((child) => children.indexOf(child) === -1);
                curr.children = this.collect(children, toJson, onNode);
            }

            if (onNode) {
                const onres = onNode.call(this.component, curr, edit);
                if (onres !== false) {
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
            if (
                node.nodeName === "IMG" ||
                node.classList.contains("mw-layout-background-node")
            ) {
                result.push(curr);
            }
        });

        return result;
    }

    collectTexts(edits, toJson) {
        return this.collect(edits, toJson, (curr, node) => {
            return (
                node.nodeName !== "IMG" &&
                node.nodeName !== "SCRIPT" &&
                //      && node.nodeName !== 'DIV'
                node.nodeName !== "STYLE" &&
                !node.classList.contains("module") &&
                !node.classList.contains("mw-layout-background-node") &&
                node.textContent.trim().length > 2
            );
        });
    }

    toJSON(onNode) {
        return this.collect(undefined, true, onNode);
    }
}

const sendToChat = (messages, messagesOptions) =>
    MwAi()
        .sendToChat(messages, messagesOptions)
        .then((data) => [data, null])
        .catch((err) => [null, err]);
const generateImage = (messages, messagesOptions) =>
    MwAi()
        .generateImage(messages, messagesOptions)
        .then((data) => [data, null])
        .catch((err) => [null, err]);

const defaultAiTextAdapter = async (message, options) => {
    if (window.MwAi) {
        let messages = [{ role: "user", content: message }];
        let [res, err] = await sendToChat(messages, options);
        if (res) {
            return res;
        } else {
            return {
                succcess: false,
                message: "Error",
            };
        }
    }
};

const defaultAiImagesAdapter = async (
    message,
    numberOfImages = 1,
    messagesOptions
) => {
    let messages = [{ role: "user", content: message }];
    const arr = Array.from({ length: numberOfImages }).map(() =>
        generateImage(messages, messagesOptions)
    );

    let res = await Promise.all(arr);

    res = res.map((itm) => itm[0]).filter((itm) => !!itm);

    if (res) {
        return res;
    } else {
        return {
            succcess: false,
            message: "Error",
        };
    }
};

export class QuickEditComponent extends MicroweberBaseClass {
    constructor(options = {}) {
        super();

        const skipSelector = ".mw-skip-quick-edit";

        const defaults = {
            document: mw.top().app.canvas.getDocument(),
            root: mw.top().app.canvas.getDocument().body,
            nodesSelector:
                '.module,[data-quick-edit="true"],h1,h2,h3,h4,h5,h6,p,li,font,b,img,small,span,.mw-layout-background-node[style*="background-image"][style*="url("]',
            editsSelector:
                ".edit[rel][field]:not(.module,script," + skipSelector + ")",
            aiTextAdapter: defaultAiTextAdapter,
            aiImagesAdapter: defaultAiImagesAdapter,
            siteInfoAdapter: generateSiteInfoWithAI,
            chatOptions: true,
        };

        this.settings = Object.assign({}, defaults, options);
        this.settings.nodesSelector = this.settings.nodesSelector
            .split(",")
            .join(":not(" + skipSelector + "),");
        this.settings.nodesSelector += ":not(" + skipSelector + ")";
        this.api = new QuickEditService(this);
        this.gui = new QuickEditGUI(this);

        this.aiTextAdapter = this.settings.aiTextAdapter;
        this.aiImagesAdapter = this.settings.aiImagesAdapter;
        this.siteInfoAdapter = this.settings.siteInfoAdapter;
        this.editMode = "whole-page"; // Add default edit mode

        this.on("change", (obj) => {
            if (!obj.node.ownerDocument && obj.node.id) {
                obj.node = mw
                    .top()
                    .app.canvas.getDocument()
                    .getElementById(obj.node.id);
            }

            obj.node.textContent = obj.text;

            mw.top().app.registerChangedState(obj.node);
        });

        mw.top().app.on("editChanged", this.editChangeSyncHandle);
        mw.top().app.on("stateChange", this.editChangeSyncHandle);
        mw.top().app.on("layoutCloned", this.editChangeSyncHandle);
        mw.top().app.on("layoutDeleted", this.editChangeSyncHandle);
        mw.top().app.on("moduleReloaded", this.editChangeSyncHandle);
        mw.top().app.on("onModuleReloaded", this.editChangeSyncHandle);

        //mw.top().app.canvas.on('liveEditCanvasLoaded', this.editChangeSyncHandle);

        this.isGlobal =
            this.settings.root === this.settings.root.ownerDocument.body;

        this.on("formSubmit", () => {
            if (this.aiChatForm) {
                this.ai(this.aiChatForm.area.value.trim());
            }
        });
    }

    #editChangeSyncHandle = (edit) => {
        this.sync(edit);
    };

    editChangeSyncHandle = this.#editChangeSyncHandle.bind(this);

    #currentEditor = null;
    #observer = null;
    #currentNodes = [];
    #currentEditorNodes = [];

    #paused = false;

    #syncTimer = null;
    #pauseSyncTimer = null;

    #pausedSync = false;

    pausedSync(state) {
        if (typeof state === "boolean") {
            this.#pausedSync = state;
        }
        return this.#pausedSync;
    }

    pauseSync() {
        clearTimeout(this.#pauseSyncTimer);
        this.pausedSync(true);
    }

    unPauseSync() {
        clearTimeout(this.#pauseSyncTimer);
        this.#pauseSyncTimer = setTimeout(() => {
            this.pausedSync(false);
        }, 300);
    }

    sync(edit) {
        if (this.settings.disableSync) {
            return;
        }
        clearTimeout(this.#syncTimer);
        this.#syncTimer = setTimeout(() => {
            if (this.isGlobal || edit === this.settings.root) {
                let shoultSync =
                    !mw
                        .top()
                        .app.canvas.getDocument()
                        .documentElement.classList.contains("le-dragiing") &&
                    !this.pausedSync();
                if (shoultSync) {
                    try {
                        if (
                            this._editorNode &&
                            this._editorNode.parentElement
                        ) {
                            const editorParent = this._editorNode.parentElement;
                            editorParent.appendChild(this.editor());
                        }
                    } catch (e) {
                        console.error("Error appending editor:", e);
                    }
                }
            }
        }, 100);
    }

    pause() {
        this.#paused = true;
    }

    play() {
        this.#paused = false;
    }

    observer() {
        const config = { characterData: true, childList: true, subtree: true };

        const cache = new WeakMap();

        const getTarget = (target) => {
            const cached = cache.get(target);
            if (cached) {
                return cached;
            }
            let node = target;
            if (node.nodeType === 3) {
                node = node.parentElement;
            }

            while (
                !!node &&
                !!node.ownerDocument &&
                !node.matches(this.settings.nodesSelector) &&
                node.nodeName !== "HTML"
            ) {
                node = node.parentElement;
            }

            if (this.#currentNodes.indexOf(node) !== -1) {
                cache.set(target, node);
                return node;
            }
        };

        const callback = (mutationList, observer) => {
            for (const mutation of mutationList) {
                const target = getTarget(mutation.target);
                if (target && target.$$ref && !this.#paused) {
                    target.$$ref.value = target.textContent;
                }
            }
        };

        const observer = new MutationObserver(callback);

        observer.observe(
            this.settings.root || this.settings.document.body,
            config
        );

        this.#observer = observer;
    }

    applyImages(images = []) {
        const canvasNodes = this.canvasNodes.filter((node) => {
            return (
                node.nodeName === "IMG" ||
                node.classList.contains("mw-layout-background-node")
            );
        });
        const editorNodes = this.editorNodes.filter((node) => {
            return (
                node.$$ref &&
                (node.$$ref.tag === "IMG" ||
                    node.$$ref.node.classList.contains(
                        "mw-layout-background-node"
                    ))
            );
        });

        images.forEach((img, i) => {
            let url;
            if (img.url) {
                url = img.url;
            } else if (img.data && img.data.url) {
                url = img.data.url;
            } else {
                url = img;
            }
            const canvasNode = canvasNodes[i];
            if (canvasNode.nodeName === "IMG") {
                canvasNode.src = url;
            } else {
                canvasNode.style.backgroundImage = `url(${url})`;
                const module = canvasNode.closest(".module-background");

                const curr = module.dataset.mwTempOptionSave;
                let json = [];
                if (curr) {
                    try {
                        json = JSON.parse(curr);
                    } catch (e) {
                        json = [];
                    }
                }

                let target = json.find(
                    (o) => o.key === "data-background-image"
                );
                if (!target) {
                    target = {
                        group: module.id,
                        key: "data-background-image",
                        module: "background",
                        value: url,
                    };

                    json.push(target);
                } else {
                    target.value = url;
                }

                module.setAttribute(
                    "data-mw-temp-option-save",
                    JSON.stringify(json)
                );
            }

            editorNodes[i].querySelector("img").src = url;
            mw.top().app.registerChangedState(canvasNodes[i]);
        });
    }

    applyJSON(json = [], extend = true) {
        // Helper to process a single content/child item
        const processContentItem = (item) => {
            if (item && item.id && typeof item.text !== "undefined") {
                const input = document.getElementById(
                    `data-node-id-${item.id}`
                );
                const target = this.settings.document.getElementById(
                    `${item.id}`
                );
                if (input) input.value = item.text;
                if (target) {
                    target.textContent = item.text;
                    mw.top().app.registerChangedState(target);
                }
            }
        };

        // Recursively process nodes, supporting both arrays and objects with .items
        const processNode = (node) => {
            if (!node || typeof node !== "object") return;
            // If node is an array, process each element
            if (Array.isArray(node)) {
                node.forEach(processNode);
                return;
            }
            // If node has 'items' (as in children/items), process those
            if (Array.isArray(node.items)) {
                node.items.forEach(processNode);
            }
            // If node has 'content', process those
            if (Array.isArray(node.content)) {
                node.content.forEach(processContentItem);
                node.content.forEach(processNode);
            }
            // If node has 'children', process those (could be array or object with items)
            if (node.children) {
                if (Array.isArray(node.children)) {
                    node.children.forEach(processNode);
                } else if (Array.isArray(node.children.items)) {
                    node.children.items.forEach(processNode);
                }
            }
            // Also process the node itself if it has id/text
            processContentItem(node);
        };

        // Unwrap response if needed
        let data = json;
        if (
            json &&
            typeof json === "object" &&
            json.success === true &&
            json.data
        ) {
            data = json.data;
        }

        // If data has children.items (as in your example), process those
        if (data && data.children && Array.isArray(data.children.items)) {
            processNode(data.children.items);
        } else if (Array.isArray(data)) {
            processNode(data);
        } else if (typeof data === "object" && data !== null) {
            processNode(data);
        }
    }

    getType(obj) {
        let type = "text";
        if (obj.tag === "IMG") {
            type = "img";
        } else if (obj.node.classList.contains("mw-layout-background-node")) {
            type = "layoutBackground";
        } else if (obj.node.classList.contains("module")) {
            type = "module";
        }

        return type;
    }

    editor() {
        if (this._editorNode) {
            this._editorNode.remove();
        }
        const editor = document.createElement("div");
        this._editorNode = editor;
        const nodes = [];
        const enodes = [];

        // Create a container to hold the organized edit fields
        const editFieldsContainer = document.createElement("div");
        editFieldsContainer.className = "edit-fields-container";

        // Group objects by their parent edit section
        const fieldGroups = {};

        // AI-711 / task-2026-05-16-b0b48f — per-section label counters.
        // The closure-scoped `quickAiEditLabelState` holds one counter
        // bag per sectionId; `computeQuickAiEditLabel` mutates it as
        // each obj is visited in DOM order so paragraph numbering
        // restarts under each heading per designer spec L2.4.
        const quickAiEditLabelState = {};

        this.api.collect(undefined, undefined, (obj) => {
            if (obj.node.matches(this.settings.nodesSelector)) {
                const type = this.getType(obj);

                // Get the parent section for grouping
                let parentEdit = obj.node.closest(".edit");
                let sectionId, sectionTitle;
                if (parentEdit) {
                    let parentEditClosesIdElement = parentEdit.closest("id");
                    let parentEditClosesId = null;
                    if (parentEditClosesIdElement) {
                        parentEditClosesId = parentEditClosesIdElement.id;
                    }

                    if (type === "layoutBackground") {
                        const parent = obj.node.closest(".module-background");
                        parentEdit =
                            parent.parentElement.querySelector(".edit");
                    }

                    sectionId = parentEdit
                        ? parentEdit.getAttribute("field") +
                          parentEdit.getAttribute("rel") +
                          parentEditClosesId
                        : "default";

                    sectionTitle = parentEdit
                        ? parentEdit.getAttribute("id") ||
                          parentEdit.getAttribute("field") ||
                          parentEdit.getAttribute("rel") ||
                          parentEdit.getAttribute("title") ||
                          "Content Elements"
                        : "Content Elements";
                } else {
                    sectionId = "default";
                    sectionTitle = "Content Elements";
                }

                // AI-711: compute the per-section label BEFORE building
                // the editor field so `_text(obj)` can read it from
                // `obj.label`. Mutates `quickAiEditLabelState[sectionId]`
                // as a side effect.
                obj.label = computeQuickAiEditLabel(
                    obj,
                    sectionId,
                    quickAiEditLabelState
                );

                const node = this.gui.build(obj, type);
                enodes.push(node);
                nodes.push(obj.node);

                // Create the group if it doesn't exist
                if (!fieldGroups[sectionId]) {
                    fieldGroups[sectionId] = {
                        title: sectionTitle,
                        // task-2026-05-22-902abc / AI-902 — type sub-groups inside each section card
                        typeGroups: {
                            headings: { nodes: [] },
                            body:     { nodes: [] },
                            ctas:     { nodes: [] },
                            other:    { nodes: [] },
                        },
                        parentEdit,
                    };
                }

                // Route each field to its type sub-group
                const _typeKey = /^H[1-6]$/.test(obj.tag) ? 'headings'
                    : (obj.tag === 'P' || obj.tag === 'SPAN' || obj.tag === 'DIV' || obj.tag === 'LI' || obj.tag === 'BLOCKQUOTE') ? 'body'
                    : (obj.tag === 'BUTTON' || obj.tag === 'A') ? 'ctas'
                    : 'other';
                fieldGroups[sectionId].typeGroups[_typeKey].nodes.push(node);
            }
        });

        // task-2026-05-22-902abc / AI-902 — create section cards with type sub-groups
        const _typeGroupOrder = ['headings', 'body', 'ctas', 'other'];
        const _typeGroupLabels = {
            headings: mw.lang('Headings'),
            body:     mw.lang('Body text'),
            ctas:     mw.lang('Buttons / CTAs'),
            other:    mw.lang('Other'),
        };

        Object.keys(fieldGroups).forEach((sectionId) => {
            const section = document.createElement("div");
            section.className = "quick-ai-card";
            section.dataset.sectionId = sectionId;
            section.$$edit = fieldGroups[sectionId].parentEdit;

            _typeGroupOrder.forEach((typeKey) => {
                const tg = fieldGroups[sectionId].typeGroups[typeKey];
                if (!tg.nodes.length) return; // skip empty type groups

                const groupEl = document.createElement('div');
                groupEl.className = 'quick-ai-type-group quick-ai-type-group--' + typeKey;
                groupEl.dataset.open = 'true'; // default all expanded

                const headerEl = document.createElement('button');
                headerEl.type = 'button';
                headerEl.className = 'quick-ai-type-group-header';
                headerEl.textContent = _typeGroupLabels[typeKey];
                headerEl.setAttribute('aria-expanded', 'true');
                headerEl.addEventListener('click', function () {
                    const isOpen = groupEl.dataset.open === 'true';
                    groupEl.dataset.open = isOpen ? 'false' : 'true';
                    headerEl.setAttribute('aria-expanded', String(!isOpen));
                });

                const bodyEl = document.createElement('div');
                bodyEl.className = 'quick-ai-type-group-body';
                tg.nodes.forEach((field) => bodyEl.appendChild(field));

                groupEl.appendChild(headerEl);
                groupEl.appendChild(bodyEl);
                section.appendChild(groupEl);
            });

            editFieldsContainer.appendChild(section);
        });

        // task-2026-05-22-902abc / AI-902 — no-selection empty state
        if (!editFieldsContainer.firstChild) {
            const _emptyEl = document.createElement('div');
            _emptyEl.className = 'mw-quick-ai-edit-empty-state';
            _emptyEl.innerHTML =
                '<div class="mw-quick-ai-edit-empty-state__icon" aria-hidden="true">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">' +
                    '<path d="M3 12h18M3 6h18M3 18h18"/>' +
                    '</svg>' +
                '</div>' +
                '<p class="mw-quick-ai-edit-empty-state__text">' +
                    mw.lang('Click a section on the canvas to see editable fields') +
                '</p>';
            editFieldsContainer.appendChild(_emptyEl);
        }

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

        mw.top().app.off("editChanged", this.editChangeSyncHandle);
        mw.top().app.off("stateChange", this.editChangeSyncHandle);

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
        return this.api.collectTexts(edits, toJson);
    }

    collectImages(edits, toJson) {
        return this.api.collectImages(edits, toJson);
    }

    aiGUI() {
        let chatOptions = null;
        if (this.settings.chatOptions === true) {
            chatOptions = [
                {
                    id: "images",
                    content: mw.lang("Regenerate Images"),
                    icon: mw.top().app.iconService.icon("image-change"),
                    selected: false,
                },
                {
                    id: "text",
                    content: mw.lang("Regenerate texts"),
                    icon: mw.top().app.iconService.icon("text"),
                    selected: false,
                },
                {
                    id: "all",
                    content: mw.lang("Regenerate texts & images"),
                    icon: null,
                    selected: true,
                },
            ];
        } else if (Array.isArray(this.settings.chatOptions)) {
            chatOptions = this.settings.chatOptions;
        }

        const aiChatForm = new AIChatForm({
            chatOptions,
            submitOnEnter: this.settings.submitOnEnter || false,
        });

        this.aiChatForm = aiChatForm;

        this.chatOption = "all";

        aiChatForm.on("chatOptionChange", (value) => {
            this.chatOption = value;
        });

        aiChatForm.on("submit", async (value) => {
            const val = value.trim();
            this.dispatch("submit", val);

            aiChatForm.disable();
            await this.ai(val);
            aiChatForm.enable();
        });

        return aiChatForm.form;
    }

    #aiPending = false;

    async ai(about) {
        if (this.#aiPending) {
            return;
        }

        this.#aiPending = true;
        this.dispatch("aiRequestStart");

        const texts = JSON.stringify(this.collectTexts(undefined, true));

        const message = `
        You are a website content writer, and you must write the text in a way that is relevant to the user's request,



        You are a website content writer, and you must write the text in a way that is relevant to the object,


        You must rewrite the text of the website to the new subject,






        You must write the text for the website and fill the existing object IDs with the text,
        If the user ask for translation, the existing text must be translated in the new language,
        If the user mention a translation language, you must translate the existing text in that language,
        If the user ask for a different subject, you must rewrite the existing text to be relevant to the new subject,
        If the user ask for a different tone, you must rewrite the existing text to be relevant to the new tone,
        If the user ask for a different style, you must rewrite the existing text to be relevant to the new style,
        If the user ask for a different audience, you must rewrite the existing text to be relevant to the new audience,
        If the user ask for a different purpose, you must rewrite the existing text to be relevant to the new purpose

        Expand on the subject and try to fill and write relevant information in the existing text,




        The website subject and user request is about: ${about}



You must respond ONLY with the JSON schema with the structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   {

    "children": {
                    "type": "array",
                    "items": {
                 ......  please put the content here, with the new text in the content and children objects

                 to this content object: \\n ${texts}



                    }
                }

    }
}
]
"""

        Write text to this object and populate the content and children file of the schema items using the existing object IDS,


        note: use only the existing object frpm thje content IDS,

        note: write text in the existing object IDS's content node

        note: write text in content objects and children objects text nodes,



        task: write text in content objects and children objects text nodes,










        Write text about:

         ${about}








and add to the schema the content and children objects with the new text in the content and children objects text nodes




       `;
        //    Use this general schema of reference of what to expect: \\\\n ${JSON.stringify(this.schema())} \\\\n
        //By using this schema: \\n ${JSON.stringify(this.schema())} \\n

        mw.top().spinnerProgress({}).show();

        let messageOptions = {};
        //messageOptions.schema = this.schema();
        //   messageOptions.schema = editSchema;
        messageOptions.schema = editSchema;

        let retryCount = 0;
        const maxRetry = 2;
        const scope = this;

        const getTexts = async () => {
            retryCount++;
            let textRes = await this.aiTextAdapter(message, messageOptions);
            if (textRes.succcess === false) {
                if (retryCount < maxRetry) {
                    return await getTexts();
                }
                return;
            }
            let resData;
            if (
                textRes.data.content?.length === 0 &&
                textRes.data.children?.length > 0
            ) {
                resData = textRes.data.children;
            } else if (textRes.success && textRes.data?.content) {
                resData = textRes.data.content;
            } else if (textRes.success && textRes.data) {
                resData = textRes.data;
            }

            if (resData) {
                // Pass the entire response to applyJSON - it will handle the structure internally
                scope.applyJSON(textRes);
            } else if (retryCount < maxRetry) {
                await getTexts();
            }
        };

        let totalSteps = 0;
        if (this.chatOption === "all" || this.chatOption === "text") {
            totalSteps++;
        }
        if (this.settings.generateSiteInfo) {
            totalSteps++;
        }
        if (this.chatOption === "all" || this.chatOption === "images") {
            totalSteps++;
        }

        const step = Math.round(100 / totalSteps);

        let currentStep = -1;

        if (this.chatOption === "all" || this.chatOption === "text") {
            currentStep++;
            mw.top()
                .spinnerProgress({})
                .set(currentStep * step, mw.lang("Generating texts") + "...");
            await getTexts();
        }

        if (this.settings.generateSiteInfo) {
            currentStep++;
            mw.top()
                .spinnerProgress({})
                .set(
                    currentStep * step,
                    mw.lang("Generating site info") + "..."
                );
            await this.siteInfoAdapter(about);
        }

        if (this.chatOption === "all" || this.chatOption === "images") {
            currentStep++;
            mw.top()
                .spinnerProgress({})
                .set(currentStep * step, mw.lang("Generating images") + "...");

            const images = this.collectImages(undefined, true);
            const imageResults = [];

            // Process each image individually with contextual prompts
            for (let i = 0; i < images.length; i++) {
                const image = images[i];

                // Find the closest text element to this image
                let contextText = "";
                const imageNode = this.settings.document.getElementById(
                    image.id
                );

                if (imageNode) {
                    // Look for text in the same parent container
                    let parent = imageNode.parentElement;
                    while (parent && !contextText) {
                        // Find text elements within the same container
                        const textElements = parent.querySelectorAll(
                            "h1, h2, h3, h4, h5, h6, p"
                        );
                        for (const textEl of textElements) {
                            const text = textEl.textContent.trim();
                            if (
                                text &&
                                text.length > 10 &&
                                !textEl.contains(imageNode)
                            ) {
                                contextText = text;
                                break;
                            }
                        }

                        // If no text found, try parent's parent (but limit the search)
                        if (
                            !contextText &&
                            parent.parentElement &&
                            parent !== this.settings.root
                        ) {
                            parent = parent.parentElement;
                        } else {
                            break;
                        }
                    }

                    // If still no context found, look for nearby text elements
                    if (!contextText) {
                        const allTextElements =
                            this.settings.root.querySelectorAll(
                                "h1, h2, h3, h4, h5, h6, p"
                            );
                        for (const textEl of allTextElements) {
                            const text = textEl.textContent.trim();
                            if (text && text.length > 10) {
                                contextText = text;
                                break;
                            }
                        }
                    }
                }

                // Create contextual prompt for this specific image
                let images_prompt = `Generate a image asset for background or picture image related to the subject: ${about}`;

                if (contextText) {
                    images_prompt += `
                    The image should be represent the a photorealistic interpretation of the mood of : "${contextText.substring(
                        0,
                        200
                    )}"
                    `;
                }

                images_prompt += `

                Important: Do not put text inside the image.




                 "
                `;

                // Update progress for each image
                const imageProgress = Math.round(
                    currentStep * step + ((i + 1) / images.length) * step
                );

                mw.top()
                    .spinnerProgress({})
                    .set(
                        imageProgress,
                        mw.lang("Generating image") +
                            ` ${i + 1}/${images.length}...`
                    );

                try {
                    let imageRes = await this.aiImagesAdapter(images_prompt, 1);
                    if (imageRes && imageRes.length > 0) {
                        imageResults.push(imageRes[0]);
                    }
                } catch (error) {
                    console.error("Error generating image:", error);
                    // Continue with the next image even if one fails
                }
            }

            // Apply all generated images at once
            if (imageResults.length > 0) {
                this.applyImages(imageResults);
            }
        }

        mw.top().spinnerProgress({}).set(100, mw.lang("Done"));

        this.#aiPending = false;
        this.dispatch("aiRequestEnd");
        setTimeout(() => {
            mw.top().spinnerProgress({}).hide();
        }, 1500);
    }
}
