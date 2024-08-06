class MicroweberBaseClass {
    #events = {};
    on(e, f) {
        this.#events[e] ? this.#events[e].push(f) : (this.#events[e] = [f]);
        return this;
    };
    off(e, f) {
        if(!this.#events[e]) {
            return this;
        }
        if(typeof f === 'function') {
            const index = this.#events[e].indexOf(f);
            if(index === -1) {
                return this;
            }
            this.#events[e].splice(index, 1);
        } else {
            this.#events[e] = [];
        }
        return this;
    };

    dispatch (e, f, f2) {
        this.#events[e] ? this.#events[e].forEach(function (c) {
            c.call(this, f);
        }) : '';
        return this;
    };

    emit (e, f) {
        return this.dispatch(e, f)
    };

    #styles = {};

    css(id, styles, forced) {
        if(!this.#styles[id] || forced) {
            this.#styles[id] = true;
            const style = document.createElement('style');
            style.appendChild(document.createTextNode(styles));
            document.head.appendChild(style);
        }
    }

}

export default MicroweberBaseClass;
