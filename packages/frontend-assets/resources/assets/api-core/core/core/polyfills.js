if (typeof Object.assign !== 'function') {
    Object.defineProperty(Object, "assign", {
        value: function assign(target) {
            'use strict';
            if (target === null || target === undefined) {
                throw new TypeError('Cannot convert undefined or null to object');
            }
            var to = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];
                if (nextSource !== null && nextSource !== undefined) {
                    for (var nextKey in nextSource) {
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}


if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}
if (Array.prototype.indexOf === undefined) {
    Array.prototype.indexOf = function(obj) {
        var i=0, l=this.length;
        for ( ; i < l; i++) {
            if (this[i] === obj) {
                return i;
            }
        }
        return -1;
    }
}

String.prototype.contains = function(a) {
    return !!~this.indexOf(a);
};
