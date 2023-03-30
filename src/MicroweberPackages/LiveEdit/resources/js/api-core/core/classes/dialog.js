import {ElementManager} from "../classes/element.js";

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
        options = options || {};
        const defaults = {
            content: null,
            overlay: true,
            closeOnEscape: true,
            document: document,
            position: 'centered',
            mode: 'fixed'
        };
        this.settings = Object.assign({}, defaults, options);

        this.build();
        setTimeout(_ => this.open())
    }

    #_e = {};

    #removeListener(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            this.remove();
        }
    }

    on(e, f){ this.#_e[e] ? this.#_e[e].push(f) : (this.#_e[e] = [f]) };
    dispatch(e, f){ this.#_e[e] ? this.#_e[e].forEach(c => { c.call(this, f); }) : ''; };


    build() {
        this.root = ElementManager({
            props: {
                className: `le-dialog ${typeof this.settings.position === 'string' ? this.settings.position : ''}`,
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
        });
        this.root.append(closeBtn);
        this.root.append(this.container);
        if(this.settings.footer) {
            this.root.append(this.settings.footer.root || this.settings.footer);
        }
        this.settings.document.body.appendChild(this.root.get(0))
        if (this.settings.closeOnEscape) {
            this.settings.document.addEventListener('keydown',  this.#removeListener) ;
        }
        if (this.settings.overlay) {
            this.overlay();
        }


    }
    open() {
        if(this.settings.position.nodeName && this.settings.position.ownerDocument === this.settings.document) {
            var el = this.settings.position;
            var doc = el.ownerDocument;
            var win = el.ownerDocument.defaultView;
            var off = el.getBoundingClientRect();

            var otop = off.top + win.scrollY;
            var oleft = off.left + el.offsetWidth + win.scrollX;
            var root = this.root.get(0);
            if(otop + root.offsetHeight > win.innerHeight + win.scrollY) {
                otop -= ((otop + root.offsetHeight) - ( win.innerHeight + win.scrollY ) );
            }
            if(oleft + root.offsetWidth  > win.innerWidth) {
                oleft -= ((oleft + root.offsetWidth) - ( win.innerWidth  ));
            }
            this.root.css({
                top: otop,
                left: oleft,
                position: this.settings.mode
            });
        }
        this.root.addClass('le-dialog-opened');
    }

    remove() {

        this.root.on('transitionend', () => {
            this.root.remove();
            if(this.overlay){
                this.overlay.remove();
            }
        });
        this.root.removeClass('le-dialog-opened');
        if (this.settings.closeOnEscape) {
            this.settings.document.removeEventListener('keydown',  this.#removeListener) ;
        }
        this.dispatch('close');

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
        content, footer, position: 'centered'
    });
    footer.cancel.on('click', function (){
        dialog.remove();
    });
    footer.ok.on('click', function (){
        if(c){
            c.call();
        }
        dialog.remove()
    });
    return dialog
}

export const Alert = function (text) {
    return new Dialog({
        content: text
    });
};
