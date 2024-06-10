mw.object = {
    extend: function () {
        var extended = {};
        var deep = false;
        var i = 0;
        var l = arguments.length;

        if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
            deep = arguments[0];
            i++;
        }
        var merge = function (obj) {
            for ( var prop in obj ) {
                if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                    if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                        extended[prop] = mw.object.extend( true, extended[prop], obj[prop] );
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };
        for ( ; i < l; i++ ) {
            var obj = arguments[i];
            merge(obj);
        }
        return extended;

    }
};
