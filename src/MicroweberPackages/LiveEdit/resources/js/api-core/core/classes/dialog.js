import {ElementManager} from "../classes/element.js";

const dialogFooter = (okLabel, cancelLabel) => {




    const footer = ElementManager({
        props: {
            className: 'modal-footer'
        }
    });

    const ok = ElementManager({
        props: {
            className: 'mw-admin-action-links mw-adm-liveedit-tabs me-2',
            innerHTML: okLabel || 'OK'
        }
    });

    const cancel = ElementManager({
        props: {
            className: 'mw-admin-action-links mw-adm-liveedit-tabs ms-2',
            innerHTML: cancelLabel || 'CANCEL'
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

        if(!this.settings.id) {
            this.settings.id = 'mw-le-dialog-' + Date.now()
        }

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
        const html = `


                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            ${this.settings.title ? `<h5 class="modal-title">${this.settings.title}</h5>` : ''}
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-2 mb-2">

                        </div>
                    </div>
                </div>


        `;

        this.root = ElementManager({
            props: {
                className: `modal`,
                tabIndex: -1,
            }
        });
        this.root.html(html);

        var body = this.root.find('.modal-body')
        var content = this.root.find('.modal-content');
        this.container = body;

        body.append(this.settings.content);
        if(this.settings.footer) {
            content.append(this.settings.footer);
        }
        this.settings.document.body.appendChild(this.root.get(0));
        this._modal = new bootstrap.Modal(this.root.get(0), {});
        this.open();
    }

    open() {
        this._modal.show()
    }

    show() {
        this._modal.show()
    }

    hide() {
        this._modal.hide()
    }

    close() {
        this._modal.hide()
    }

    toggle() {
        this._modal.toggle()
    }

    remove() {

        this._modal.hide()
        this._modal.dispose()

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
        content, footer: footer.footer.get(0), title: mw.lang('Remove section')
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
