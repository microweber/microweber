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

}

export default MicroweberBaseClass;
