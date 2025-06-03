class MicroweberBaseClass {
    // must be public, due to issue in babel.js
    _events = {};
    on(e, f) {
        this._events[e] ? this._events[e].push(f) : (this._events[e] = [f]);
        return this;
    };
    off(e, f) {
        if(!this._events[e]) {
            return this;
        }
        if(typeof f === 'function') {
            const index = this._events[e].indexOf(f);
            if(index === -1) {
                return this;
            }
            this._events[e].splice(index, 1);
        } else {
            this._events[e] = [];
        }
        return this;
    };

    dispatch (e, f, f2) {
        this._events[e] ? this._events[e].forEach(function (c) {
            c.call(this, f);
        }) : '';
        return this;
    };

    emit (e, f) {
        return this.dispatch(e, f)
    };

    _styles = {};

    css(id, styles, forced) {
        if(!this._styles[id] || forced) {
            this._styles[id] = true;
            const style = document.createElement('style');
            style.appendChild(document.createTextNode(styles));
            document.head.appendChild(style);
        }
    }

}

export default MicroweberBaseClass;
