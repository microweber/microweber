export function normalizeBase64Image (node, callback) {
    var type, obj;
    if (typeof node.src !== 'undefined' && node.src.indexOf('data:image/') === 0) {
        type = node.src.split('/')[1].split(';')[0];
        obj = {
            file: node.src,
            name: mw.random().toString(36) + "." + type
        }
        $.post(mw.settings.api_url + "media/upload", obj, function (data) {
            data = $.parseJSON(data);
            node.src = data.src;



            mw.top().app.registerChange?.call(undefined, node);

            mw.trigger('imageSrcChanged', [node, node.src]);
            if (typeof callback === 'function') {
                callback.call(node);
            }
        });
    }
    else if (node.style.backgroundImage.indexOf('data:image/') !== -1) {
        var bg = node.style.backgroundImage.replace(/url\(/g, '').replace(/\)/g, '')
        type = bg.split('/')[1].split(';')[0];
        obj = {
            file: bg,
            name: mw.random().toString(36) + "." + type
        };
        $.post(mw.settings.api_url + "media/upload", obj, function (data) {
            data = $.parseJSON(data);
            node.style.backgroundImage = 'url(\'' + data.src + '\')';


            mw.top().app.registerChange?.call(undefined, node);
            if (typeof callback === 'function') {
                callback.call(node);
            }
        });
    }
};

export function normalizeBase64Images  (root, callback) {
    root = root || document.body;
    var all = root.querySelectorAll(".edit img[src*='data:image/'], .edit [style*='data:image/'][style*='background-image'], .mw-editor-area img[src*='data:image/'], .mw-editor-area [style*='data:image/'][style*='background-image']"),
        l = all.length, i = 0, count = 0;
    if (l > 0) {
        var btn = document.getElementById('main-save-btn');
        var btnPrev;
        if(btn){
            btnPrev = btn.disabled;
            btn.disabled = true;
        }
        for (; i < l; i++) {
            mw.tools.addClass(all[i], 'element');
            mw.wysiwyg.normalizeBase64Image(all[i], function (){
                count++;
                if(count === l) {
                    if(typeof callback === 'function') {
                        setTimeout(function(){
                            callback.call();
                        }, 10)
                    }
                    if(btn){
                        btn.disabled = btnPrev;
                    }
                }
            });
        }
    } else {
        if(typeof callback === 'function') {
            setTimeout(function(){
                callback.call();
            }, 10)
        }
    }
};
