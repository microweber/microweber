import {ElementManager} from "../classes/element";

const dialogFooter = (okLabel, cancelLabel) => {
    const footer = ElementManager({
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

    footer.append(cancel);
    footer.append(ok);

    return  {
        ok, cancel, footer
    }
}

export class Dialog {
    constructor(options) {
        options = options || {}
        const defaults = {
            content: null,
            overlay: true,
            document: document
        }
        this.settings = Object.assign({}, defaults, options);
        console.log( this.settings );
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
        if(this.settings.footer) {
            this.root.append(this.settings.footer.root || this.settings.footer);
        }
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


export const Confirm = function (content, c) {
    const footer = dialogFooter();
    const dialog = new Dialog({
        content, footer
    });
    footer.cancel.on('click', function (){
        dialog.remove()
    })
    footer.ok.on('click', function (){
        if(c){
            c.call()
        }
        dialog.remove()
    });
    return dialog
}

export const Alert = function (text) {
    return new Dialog({
        content: text
    })
}
