class MicroweberBaseClass {
    #events = {};
    on(e, f) {
        this.#events[e] ? this.#events[e].push(f) : (this.#events[e] = [f])
    };
    off(e, f) {
        if(!this.#events[e]) {
            return;
        }
        if(typeof f === 'function') {
            const index = this.#events[e].indexOf(f);
            if(index === -1) {
                return;
            }
            this.#events[e].splice(index, 1);
        } else {
            this.#events[e] = [];
        }
    };

    dispatch (e, f) {
        this.#events[e] ? this.#events[e].forEach(function (c) {
            c.call(this, f);
        }) : '';
    };

    emit (e, f) {
        return this.dispatch(e, f)
    };

    generateRandId(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    };

}

export default MicroweberBaseClass;
