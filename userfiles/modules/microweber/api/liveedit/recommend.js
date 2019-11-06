mw.liveedit.recommend = {
    get: function () {
        var cookie = mw.cookie.getEncoded("recommend");
        if (!cookie) {
            return {}
        }
        else {
            try {
                var val = $.parseJSON(cookie);
            }
            catch (e) {
                return;
            }
            return val;
        }
    },
    increase: function (item_name) {
        var json = this.get() || {};
        var curr = parseFloat(json[item_name]);
        if (isNaN(curr)) {
            json[item_name] = 1;
        }
        else {
            json[item_name] += 1;
        }
        var tostring = JSON.stringify(json);
        mw.cookie.setEncoded("recommend", tostring, false, "/");
    },
    orderRecommendObject: function () {
        var obj = this.get();
        if (!mw.tools.isEmptyObject(obj)) {
            var arr = [];
            for (var x in obj) {
                arr.push(x)
                arr.sort(function (a, b) {
                    return a[1] - b[1]
                })
            }
            return arr;
        }
    },
    set: function () {
        var arr = this.orderRecommendObject(), l = arr.length, i = 0;
        for (; i < l; i++) {
            var m = mw.$('#tab_modules .module-item[data-module-name="' + arr[i] + '"]')[0];
            if (m !== null && typeof m !== 'undefined') {
                mw.$('#tab_modules ul').prepend(m);
            }
        }
    }
};
