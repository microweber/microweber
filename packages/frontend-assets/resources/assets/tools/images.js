mw.image = {
    preloadForAll: function (array, eachCall, callback) {
        var size = array.length, i = 0, count = 0;
        for (; i < size; i++) {
            mw.image.preload(array[i], function (imgWidth, imgHeight) {
                count++;
                if(eachCall) {
                    eachCall.call(this, imgWidth, imgHeight)
                }
                if (count === size) {
                    if (!!callback) callback.call()
                }
            })
        }
    },
    preloadAll: function (array, callback) {
        var size = array.length, i = 0, count = 0;
        for (; i < size; i++) {
            mw.image.preload(array[i], function () {
                count++;
                if (count === size) {
                    callback.call()
                }
            })
        }
    },
    preload: function (url, callback) {
        var img = document.createElement('img');

        img.className = 'semi_hidden';
        img.src = url;
        img.onload = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, img.naturalWidth, img.naturalHeight);
                }
                mw.$(img).remove();
            }, 33);
        }
        img.onerror = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, 0, 0, 'error');
                }
            }, 33);
        }
        document.body.appendChild(img);
    },
    preloadAsLink: function (url, callback) {

        var link = document.createElement("link");
        link.href = url;
        link.rel = "preload";
        link.as = "image";
        link.onload = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img);
                }
            }, 33);
        };
        link.onerror = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img);
                }
            }, 33);
        };
        document.head.appendChild(link);
    },

    description: {
        add: function (text) {
            var img = document.querySelector("img.element-current");
            img.title = text;
        },
        get: function () {
            return document.querySelector("img.element-current").title;
        },
        init: function (id) {
            var area = mw.$(id);
            area.hover(function () {
                area.addClass("desc_area_hover");
            }, function () {
                area.removeClass("desc_area_hover");
            });
            var curr = mw.image.description.get();
            if (!area.hasClass("inited")) {
                area.addClass("inited");
                area.bind("keyup change paste", function () {
                    var val = mw.$(this).val();
                    mw.image.description.add(val);
                });
            }
            area.val(curr);
            area.show();
        }
    },
    settings: function () {
        return mw.dialogIframe({
            url: 'imageeditor',
            template: "mw_modal_basic",
            overlay: true,
            width: '600',
            height: "auto",
            autoHeight: true,
            name: 'mw-image-settings-modal'
        });
    }
};
