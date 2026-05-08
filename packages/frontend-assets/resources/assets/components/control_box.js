import BaseComponent from "../containers/base-class.js";

const css = `
    .mw-control-box-content{
        padding: 20px;
    }
    .mw-control-box-title{
        padding: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        display: block;
    }
    .mw-control-box.active {
        z-index: 1;
    }
    .mw-control-box {
        position: fixed;
        max-width: 100%;
        z-index: 99;
    }
    .mw-control-box-default {
        padding: 0;
        transition: all .5s;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    .mw-control-box-default.mw-control-box-right {
        transform: translateX(100%);
    }
    .mw-control-box-default.mw-control-box-right.active {
        transform: translateX(0);
    }
    .mw-control-box-default.mw-control-box-left {
        transform: translateX(-100%);
    }
    .mw-control-box-default.mw-control-box-left.active {
        transform: translateX(0);
    }
        .mw-control-box-default.mw-control-box-bottom {
        transform: translateY(100%);
    }
    .mw-control-box-default.mw-control-box-bottom.active {
        transform: translateY(0);
    }
    .mw-control-box-top {
        top: 0;
        left: 0;
        width: 100%;
    }
    .mw-control-box-bottom {
        bottom: 0;
        left: 0;
        width: 100%;
    }
    .mw-control-box-right {
        top: 0;
        right: 0;
        height: 100%;
    }
    .mw-control-box-left {
        top: 0;
        left: 0;
        height: 100%;
    }
    .mw-control-box {
        position: fixed;
        z-index: 12;
    }
    .mw-control-box-default .mw-control-boxclose:after {
        content: '';
    }
    .mw-control-box-default .mw-control-boxclose {
        position: absolute;
        z-index: 10;
        width: 20px;
        height: 20px;
        text-align: center;
        top: 10px;
        right: 10px;
    }
`;

export class ControlBox extends BaseComponent {
    constructor(options = {}) {
        super(options);
        this.config(options);
        this.init();
    }

    #active = false;

    static hasOpened(side = '') {
        if(side) {
            side = '.mw-control-box-' + side;

            if(side === 'right') {
                 side += ', #general-theme-settings.active'
            }
        }
        return !!mw.top().doc.querySelector('.mw-control-box.active' + side);
    }

    static getInstances(side = '') {
        if(side) {
            side = '.mw-control-box-' + side;
        }
        return Array.from(mw.top().doc.querySelectorAll('.mw-control-box.active' + side)).map(node => node.__instance);
    }

    static hideAll (skip) {
        ControlBox.getInstances().forEach(instance => {
            if(skip) {
                if(skip !== instance) {
                    instance.hide()
                }

            } else {
                instance.hide()
            }
        });
    }


    static hideAllBySide (side, skip) {
        ControlBox.getInstances(side).forEach(instance => {
            if(skip) {
                if(skip !== instance) {
                    instance.hide()
                }

            } else {
                instance.hide()
            }
        });
    }


    hideOthers() {
        ControlBox.hideAll(this);
    }

    config(options) {

        this.defaults = {
            position: 'bottom',
            content: '',
            skin: 'default',
            id:  'mw-control-box-' + mw.random(),
            closeButton: true,
            closeButtonAction: 'hide',

        };
        this.settings = Object.assign({}, this.defaults, options);
        this.id = this.settings.id;
        this.css('mwControlBox', css, false);
    }

    build() {
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.box.__instance = this;
        this.box.id = this.id;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-box-content';
        if(this.settings.width) {
            this.boxContent.style.width = this.settings.width;
        }

        if(this.settings.title) {
            const title = document.createElement("h3");
            title.className = "mw-control-box-title";
            title.innerHTML = this.settings.title;
            this.box.appendChild(title);
        }
        this.box.appendChild(this.boxContent);
        this.createCloseButton();
        document.body.appendChild(this.box);

    }

    position(position) {
        if (typeof position === 'undefined') {
            return this.settings.position;
        }
        this.box.classList.remove('mw-control-box-' + this.settings.position);
        this.settings.position = position;
        this.box.classList.add('mw-control-box-' + this.settings.position);
        return this.settings.position;
    }

    createCloseButton() {
        if (!this.settings.closeButton) return;
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.closeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>';
        this.box.appendChild(this.closeButton);



        this.closeButton.addEventListener("click", e => {
            if(typeof this.settings.closeButtonAction === 'function') {
                this.settings.closeButtonAction.call(this);
            } else if(typeof this.settings.closeButtonAction === 'string') {
                this[this.settings.closeButtonAction]();
            }

        });
    }

    setContentByUrl() {
        const cont = this.settings.content.trim();
        return $.get(cont, (data) => {
            this.boxContent.innerHTML = data;
            this.settings.content = data;
        });
    }

    setContent(c) {
        const cont = c || this.settings.content.trim();
        this.settings.content = cont;
        if (cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0) {
            return this.setContentByUrl();
        }
        this.boxContent.innerHTML = cont;
    }

    visible() {
        return this.#active;
    }

    hidden() {
        return !this.visible();
    }

    zIndexManager() {
        let i = 0 ,
        all = document.querySelectorAll('.mw-control-box.active, #general-theme-settings.active'),
        max = 0;
        for ( ; i < all.length; i++) {
            if(all[i] === this.box) {
                continue;
            }
            const css = getComputedStyle(all[i]);
            const zi = parseFloat(css.zIndex);
            if(!isNaN(zi) && zi > max) {
                max = zi;
            }
        }
        if(max) {
            max++;
            this.box.style.zIndex = max;
        }

    }

    show() {
        ControlBox.hideAllBySide(this.settings.position, this);
        this.#active = true;

        // AI-70 / TICKET-LL (cycle-83 2026-05-08): clear display:none
        // BEFORE adding the .active class so the .5s slide-in
        // transition has a non-display:none starting frame to
        // animate from. Without the rAF tick, browsers collapse
        // display:none → display:block + transform: translateX(0)
        // into a single paint and the user sees no animation.
        if (this.box) {
            this.box.style.display = '';
        }
        // rAF + a tiny rAF ensures the layout has flushed and the
        // browser will treat the next class change as a transition
        // start.
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                mw.$(this.box).addClass('active');
                this.zIndexManager();
            });
        });

        this.dispatch('show');
    }

    init() {
        this.build();
        this.setContent();
    }

    hide() {
        this.#active = false;
        mw.$(this.box).removeClass('active');

        // AI-70 / TICKET-LL (cycle-83 2026-05-08): after the .5s
        // slide-out transition completes, set display:none so the
        // panel is removed from layout AND the GPU composite layer
        // is dropped. Three off-screen .mw-control-box-right
        // instances at x=1280 were:
        //   - causing wrong querySelector(.mw-control-box-right)
        //     returns (returned the FIRST instance, not the active
        //     one — the JS that read content from the active panel
        //     read stale content from the first hidden one)
        //   - leaking layout into the 1024-1279px viewport range
        //     where the negative-x position math depends on viewport
        //     width
        //   - holding three full-height GPU composite layers
        //     simultaneously (~315k px² each)
        // Tied to the .5s transition in the embedded css block
        // above (`.mw-control-box-default { transition: all .5s }`).
        // setTimeout fires after the slide-out completes; if a
        // subsequent show() call clears display:none mid-fade, the
        // onTransitionEnd handler is a no-op (this.#active flips
        // back to true).
        const HIDE_TRANSITION_MS = 550; // .5s + 50ms safety margin
        setTimeout(() => {
            // Re-check #active in case the user re-opened the
            // panel before the timer fired.
            if (!this.#active && this.box) {
                this.box.style.display = 'none';
            }
        }, HIDE_TRANSITION_MS);

        this.dispatch('hide');
    }

    remove() {
        this.hide();
        mw.$(this.box).remove();
        this.dispatch('remove');
    }

    toggle() {
        this[this.#active ? 'hide' : 'show']();
    }
}
