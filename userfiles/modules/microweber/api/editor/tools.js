mw.Editor.tools = {
    extend: function(out) {
        out = out || {};
        for (var i = 1; i < arguments.length; i++) {
            var obj = arguments[i];
            if (!obj) {
                continue;
            }
            for (var key in obj) {
                if (obj.hasOwnProperty && obj.hasOwnProperty(key)) {
                    if (typeof obj[key] === 'object'){
                        if(Array.isArray(obj[key])){
                            out[key] = obj[key].slice(0);
                        }
                        else {
                            out[key] = mw.Editor.tools.extend(out[key], obj[key]);
                        }
                    }
                    else {
                        out[key] = obj[key];
                    }
                } else {
                    console.log(obj, arguments)
                }
            }
        }
        return out;
    }
};
