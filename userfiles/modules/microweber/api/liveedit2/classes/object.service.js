export class ObjectService {
    static extend () {
        const extended = {};
        let deep = false;
        let i = 0;
        const l = arguments.length;

        if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
            deep = arguments[0];
            i++;
        }
        const merge = function (obj) {
            for ( const prop in obj ) {
                if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                    if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                        extended[prop] = ObjectService.extend( true, extended[prop], obj[prop] );
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };
        for ( ; i < l; i++ ) {
            const obj = arguments[i];
            merge(obj);
        }
        return extended;

    }
}
