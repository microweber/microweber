import BaseComponent from "../containers/base-class.js";

const css = `


    .mw-preview-iframe {
        width: 200%;
        transform: scale(.5);
        top: 0;
        position: absolute;
        left: 0;
        transform-origin: 0 0;
        border: 1px solid silver;
        transition: .3s;
    }
    .mw-template-preview-container{
        width: 100%;
        position: relative;
    }
    .mw-template-preview-container.preview-in-self {
        height: calc(80vh - 80px);
    }
    .mw-template-preview-container.preview-in-self .mw-preview-iframe{
        height: calc(160vh - 160px) !important;
    }

    .mw-template-preview-container.preview-in-iframe {
        height: 800px;
    }
    .mw-template-preview-container.preview-in-iframe iframe {
        height: 1600px !important;
    }

    .mw-template-preview-container.has-mw-spinner iframe{
        opacity: 0;
    }


`;

export class TemplatePreview extends BaseComponent {
    constructor(options = {}) {
        super();
        const defaults = {
            element: null,
            document: document,
            id: mw.id(),
            className: 'mw-preview-iframe'
        }
        this.settings = Object.assign({}, defaults, options);
        if(typeof this.settings.element === "string") {
            this.element = document.querySelector(this.settings.element);
        } else {
            this.element = this.settings.element;
        }
        this.css('TemplatePreview', css)

    }

    createFrame() {
        var frame = document.createElement('iframe');
        this.frame = frame;
        frame.src = this.url;
        frame.className =  this.settings.className;
        frame.id = this.settings.id;
        frame.tabIndex = -1;
        frame.frameborder = 0;
        frame.addEventListener('load', () => {
            this.afterLoad();
            this.frame.contentWindow.document.documentElement.className = 'mw-template-document-preview';
        })
        return frame
    }

    set () {
        var framewindow = this.frame.contentWindow;
        framewindow.scrollTo(0, 0);
    }
    afterLoad () {
        this.set();
        mw.spinner({element: this.element}).remove();
        this.dispatch('iframeLoaded', {url: this.url});
    }
    rend (url) {
        this.url = url;
        const curr = this.element.querySelector('iframe');
        if (curr) {
            curr.remove();
        }
        if (self !== top) {
            this.element.classList.add('preview-in-iframe');
        } else {
            this.element.classList.add('preview-in-self');
        }
        mw.spinner({element: this.element, size: 32}).show();
        const frame = this.createFrame(this.url);
        this.element.classList.add('mw-template-preview-container');
        this.element.append(frame);
        return frame;
    }
}


