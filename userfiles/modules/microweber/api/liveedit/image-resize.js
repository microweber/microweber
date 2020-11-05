mw.imageResize = {
    prepare: function () {
        if (!mw.image_resizer) {
            var resizer = document.createElement('div');
            resizer.className = 'mw-defaults mw_image_resizer';
            resizer.innerHTML = '<div id="image-edit-nav"><span onclick="mw.wysiwyg.media(\'#editimage\');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon image_change tip" data-tip="' + mw.msg.change + '"><span class="mdi mdi-image mdi-18px"></span></span><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon tip image_change" id="image-settings-button" data-tip="' + mw.msg.edit + '" onclick="mw.image.settings();"><span class="mdi mdi-pencil mdi-18px"></span></span></div>';
            document.body.appendChild(resizer);
            mw.image_resizer = resizer;
            mw.image_resizer_time = null;
            mw.image_resizer._show = function () {
                clearTimeout(mw.image_resizer_time)
                mw.$(mw.image_resizer).addClass('active')
            };
            mw.image_resizer._hide = function () {
                mw.image_resizer_time = setTimeout(function () {
                    mw.$(mw.image_resizer).removeClass('active')
                }, 3000)
            };

            mw.$(resizer).on("click", function (e) {
                if (mw.image.currentResizing[0].nodeName === 'IMG') {
                    mw.wysiwyg.select_element(mw.image.currentResizing[0])
                }
            });
            mw.$(resizer).on("dblclick", function (e) {
                mw.wysiwyg.media('#editimage');
            });
        }
        mw.$(mw.image_resizer).resizable({
            handles: "all",
            minWidth: 60,
            minHeight: 60,
            start: function () {
                mw.image.isResizing = true;
                mw.$(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
                mw.$(mw.tools.firstParentWithClass(mw.image.currentResizing[0], 'edit')).addClass("changed");
            },
            stop: function () {
                mw.image.isResizing = false;
                mw.drag.fix_placeholders();
            },
            resize: function () {
                var offset = mw.image.currentResizing.offset();
                mw.$(this).css(offset);
            },
            aspectRatio: 16 / 9
        });
        mw.image_resizer.mwImageResizerComponent = true;
        var all = mw.image_resizer.querySelectorAll('*'), l = all.length, i = 0;
        for (; i < l; i++) all[i].mwImageResizerComponent = true
    },
    resizerSet: function (el, selectImage) {
        selectImage = typeof selectImage === 'undefined' ? true : selectImage;
        /*  var order = mw.tools.parentsOrder(el, ['edit', 'module']);
         if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){   */


        mw.$('.ui-resizable-handle', mw.image_resizer)[el.nodeName == 'IMG' ? 'show' : 'hide']()

        el = mw.$(el);
        var offset = el.offset();
        var parent = el.parent();
        var parentOffset = parent.offset();
        if(parent[0].nodeName !== 'A'){
            offset.top = offset.top < parentOffset.top ? parentOffset.top : offset.top;
            offset.left = offset.left < parentOffset.left ? parentOffset.left : offset.left;
        }
        var r = mw.$(mw.image_resizer);
        var width = el.outerWidth();
        var height = el.outerHeight();
        r.css({
            left: offset.left,
            top: offset.top,
            width: width,
            height: mw.tools.hasParentsWithClass(el[0], 'mw-image-holder') ? 1 : height
        });
        r.addClass("active");
        mw.$(mw.image_resizer).resizable("option", "alsoResize", el);
        mw.$(mw.image_resizer).resizable("option", "aspectRatio", width / height);
        mw.image.currentResizing = el;
        if (!el[0].contentEditable) {
            mw.wysiwyg.contentEditable(el[0], true);
        }

        if (selectImage) {
            if (el[0].parentNode.tagName !== 'A') {
                mw.wysiwyg.select_element(el[0]);
            }
            else {
                mw.wysiwyg.select_element(el[0].parentNode);
            }
        }
        if (mwd.getElementById('image-settings-button') !== null) {
            if (!!el[0].src && el[0].src.contains('userfiles/media/pixum/')) {
                mwd.getElementById('image-settings-button').style.display = 'none';
            }
            else {
                mwd.getElementById('image-settings-button').style.display = '';
            }
        }
        /* } */
    },
    init: function (selector) {
        mw.image_resizer == undefined ? mw.imageResize.prepare() : '';

        mw.on("ImageClick", function (e, el) {
            if (!mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName === 'IMG') {
                mw.imageResize.resizerSet(el);
            }
        })
    }
}
