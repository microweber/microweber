export class Tabs {
    constructor(options) {
        const defaults = {
            activeClass: 'active'
        }
        this.settings = Object.assign({}, defaults, options || {});
        if(typeof this.settings.tabs === 'string') {
            this.settings.tabs = Array.from(document.querySelectorAll(this.settings.tabs));
        }
        if(typeof this.settings.nav === 'string') {
            this.settings.nav = Array.from(document.querySelectorAll(this.settings.nav))
        }

        this.init();
    }

    unsetAll() {
        this.settings.tabs.forEach(tab => {
            tab.classList.remove(this.settings.activeClass)
        })
        this.settings.nav.forEach(nav => {
            nav.classList.remove(this.settings.activeClass)
        })
    }

    set(index) {
        this.unsetAll();
        if (typeof index === 'number') {
            this.settings.tabs[index].classList.add(this.settings.activeClass)
            this.settings.nav[index].classList.add(this.settings.activeClass)
        }
    }

    init() {
         Array.from(this.settings.nav).forEach((tab, index) => {
            tab.addEventListener('click', e => {
                e.preventDefault();
                 this.set(index);
                console.log(index)
                if(this.settings.onclick) {
                    this.settings.onclick.call(tab, tab, e, index)
                }
            })
        })
    }
}
