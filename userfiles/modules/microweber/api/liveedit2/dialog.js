import {ElementManager} from "./element";

const dialogFooter = (okLabel, cancelLabel) => {
    const ft = ElementManager({
        props: {
            className: 'le-dialog-footer'
        }
    });

    const ok = ElementManager({
        props: {
            className: 'le-btn le-btn-primary le-dialog-footer-ok',
            innerHTML: okLabel || 'OK'
        }
    });

    const cancel = ElementManager({
        props: {
            className: 'le-btn le-dialog-footer-cancel'
        }
    });

    ft.append(cancel);
    ft.append(ok);

    return  {
        ok, cancel
    }
}

export class Dialog {
    constructor(options) {
        this.document = document;
        options = options || {}
        const defaults = {
            content: null,
            overlay: true
        }
        this.settings = Object.assign({}, defaults, options);
        this.build();
        setTimeout(_ => this.open())
    }
    build() {
        this.root = ElementManager({
            props: {
                className: 'le-dialog',

            }
        });
        var closeBtn = ElementManager({
            props: {
                className: 'le-dialog-close',
            }
        });
        closeBtn.on('click', () => {
            this.remove();
        });
        this.container = ElementManager({
            props: {
                className: 'le-dialog-container'
            },
            content: this.settings.content
        })
        this.root.append(closeBtn);
        this.root.append(this.container);
        this.settings.document.body.appendChild(this.root.get(0))
        if (this.settings.overlay) {
            this.overlay()
        }
    }
    open() {
        this.root.addClass('le-dialog-opened')
    }
    remove() {
        this.root.remove()
        if(this.overlay){
            this.overlay.remove()
        }
    }
    overlay() {
        this.overlay = ElementManager({
            props: {
                className: 'le-dialog-overlay'
            }
        })
        this.settings.document.body.appendChild(this.overlay.get(0))
    }
    
}


export const Confirm = function (q) {

}

export const Alert = function (text) {
    return new Dialog({
        content: text
    })
}
