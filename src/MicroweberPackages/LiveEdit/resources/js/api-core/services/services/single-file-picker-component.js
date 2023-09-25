 
import MicroweberBaseClass from "../containers/base-class";

export class SingleFilePickerComponent extends MicroweberBaseClass {

    constructor(options = {}) {
        super();
        const defaults = {
            canEdit: true,
            canDelete: true,
            element: null,
            template: null,
            document,
            id: mw.id(),
            accept: 'images',
            templatePrepare: () => {
                if (this.file) {
                    this.root.querySelector(`#js-preview-image-wrapper-${this.id}`).style.display = ``;
                    this.root.querySelector(`#js-dropzone-image-${this.id}`).style.display = `none`;
                } else {
                    this.root.querySelector(`#js-preview-image-wrapper-${this.id}`).style.display = `none`;
                    this.root.querySelector(`#js-dropzone-image-${this.id}`).style.display = ``;
                }
            },
        }
        
        this.settings = Object.assign({}, defaults, options);
        this.document = this.settings.document;
        this.element = this.settings.element;
        this.file = this.settings.file;
        this.id = this.settings.id;
        if (typeof this.element === 'string') {
            this.element = this.document.querySelector(this.settings.element);
        }
        this.window = this.settings.document.defaultView;

        this.init();
    }

    templatePrepare() {
        this.settings.templatePrepare.call(this); 
        this.makePreview();
        this.root.querySelectorAll('[data-fpc-action="edit"]').forEach(node => {
            node.disabled = !this.#isImage(this.file);
        });
    
    }

    template() {
        const id = this.id;
        const template = this.settings.template || `
            <div class="mw-filepicker-component" id="root-${id}">
                <div id="js-preview-image-wrapper-${id}" style="display:none">
                    <div class="d-flex justify-content-between">
                        <div data-fpc-action="preview">
                             
                        </div>
                        <div class="d-flex gap-2">
                            <div class="form-control-live-edit-label-wrapper d-flex align-items-center">
                                <button type="button" class="mw-liveedit-button-actions-component btn-sm js-select-file-${id}" data-fpc-action="selectFile">
                                    ${mw.lang('Change')}
                                </button>
                            </div>
                            ${this.settings.canEdit ? ` 
                            <div class="form-control-live-edit-label-wrapper d-flex align-items-center">
                                <button type="button" class="mw-liveedit-button-actions-component"  id="js-edit-image-${id}" title="${mw.lang('Edit')}"  data-fpc-action="edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"> <path d="M22.7 14.3L21.7 15.3L19.7 13.3L20.7 12.3C20.8 12.2 20.9 12.1 21.1 12.1C21.2 12.1 21.4 12.2 21.5 12.3L22.8 13.6C22.9 13.8 22.9 14.1 22.7 14.3M13 19.9V22H15.1L21.2 15.9L19.2 13.9L13 19.9M11.21 15.83L9.25 13.47L6.5 17H13.12L15.66 14.55L13.96 12.29L11.21 15.83M11 19.9V19.05L11.05 19H5V5H19V11.31L21 9.38V5C21 3.9 20.11 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H11V19.9Z"></path></svg>
                                </button>
                            </div>
                            ` : ''}
                            ${this.settings.canDelete ? ` 
                                <div class="form-control-live-edit-label-wrapper d-flex align-items-center">
                                    <button type="button" class="mw-liveedit-button-actions-component js-remove-file-${id}"  data-fpc-action="remove">
                                        <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                                    </button>
                                </div>
                            
                            ` : ''}
                        </div>
                    </div>
                </div>


                <div id="js-dropzone-image-${id}" class="dropzone mw-dropzone js-select-file-${id}" style="display:none" data-fpc-action="selectFile">
                    <div class="d-flex flex-column align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center" style="background:rgba(0,0,0,0.11);color:#000;width:40px;height:40px; border-radius:100%; font-size:28px;">
                            <i class="mdi mdi-plus"></i>
                        </div>
  
                        <div>
                            <span>
                                <b>20MB Max</b>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        this.element.innerHTML = template;

        this.launch()
        this.templatePrepare()


        return this;
         
    }

    #isImage(url) {
        return /^https?:\/\/.+\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url);
    }

    #isVideo(url) {
        return /^https?:\/\/.+\.(mp4|ogg|3gp|webm)$/.test(url);
    }

    #isAudio(url) {
        return /^https?:\/\/.+\.(mp3)$/.test(url);
    }



    previewImage () {
        return `<img src="${this.file}" style="border-radius:4px;">`;
    }

    previewVideo () {
        return `<video width="100px" src="${this.file}" muted loop playsinline style="border-radius:4px;"></video>`;
    }

    previewAudio () {
        return `<video width="100px" src="${this.file}" controls playsinline style="border-radius:4px;"></video>`;
    }

    generatePreview() {
        if(this.#isImage(this.file)){
            return this.previewImage();
        } else if(this.#isVideo(this.file)){
            return this.previewVideo();
        } else if(this.#isAudio(this.file)){
            return this.previewAudio()
        }
        return '';
    }

    makePreview() {
        const previewNodes = this.root.querySelectorAll('[data-fpc-action="preview"]');

        if(!this.file) {
            previewNodes.forEach(node => {
                node.innerHTML = '';
            });
        } else {
            previewNodes.forEach(node => {
                const preview = this.generatePreview();
        
                node.dataset.preview = !!preview;
                node.innerHTML = preview;

            });
        }
    }

    removeFile() {
        const previewNodes = this.root.querySelectorAll('[data-fpc-action="preview"]');
        this.file = null;
        this.root.querySelectorAll('[data-fpc-action="preview"]');
        previewNodes.forEach(node => {
        
            node.innerHTML = '';
        });
        this.templatePrepare();
    }

    #eventsHandles = {
        edit: async (event, node, scope) => {
            var src = await mw.top().app.editImageDialog.editImageUrl(this.file);
            if(src) {
                this.file = src;
                this.templatePrepare();
            }
        },
        remove: (event, node, scope) => {
            this.removeFile();
        },
        selectFile: (event, node, scope) => {
            var dialog;
            var picker = new mw.filePicker({
                type: this.settings.accept,
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                onResult: (res) => {
                    var url = res.src ? res.src : res;
                    if(!url) return;
                    url = url.toString();
                    this.file = url;
                    this.templatePrepare();
                    dialog.remove();
                }
            });
            dialog = mw.top().dialog({
                content: picker.root,
                title: mw.lang('Select file'),
                footer: false,
                width: 860
            })
        }
    };

    handleEvents() {
        this.root.querySelectorAll('[data-fpc-action]').forEach(node => {
            const action = node.dataset.fpcAction.trim();
            if (action && this.#eventsHandles[action]) {
                node.addEventListener('click', e => this.#eventsHandles[action](e, node, this));
            }
        })    
    }

    launch() {
        this.root = this.document.getElementById(`root-${this.id}`);
        this.handleEvents()
    }   


    init() {

        if(!this.element) {
            return;
        }

        
        this.template();
        return this;

    }
} 




