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

    ft.append(cancel)
    ft.append(ok)

    return  {
        ok, cancel
    }
}

class Dialog {
    constructor(options) {
        this.document = document;
        options = options || {}
        const defaults = {
            content: null
        }
        this.settings = Object.assign({}, defaults, options);
        this.build();
        this.open();
    }
    build() {
        this.root = ElementManager({
            props: {
                className: 'le-dialog'
            }
        })
        this.container = ElementManager({
            props: {
                className: 'le-dialog'
            },
            content: this.settings.content
        })
        this.root.append(this.container);
        this.settings.document.body.appendChild(this.root.get(0))
    }
    open() {
        this.root.addClass('le-dialog-opened')
    }
    remove() {
        this.root.remove()
    }
}


export const Confirm = function (q) {

}

export const Alert = function (text) {
    return new Dialog({
        content: text
    })
}
