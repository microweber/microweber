

    mw.tools.richtextEditorSettings = {
        width: '100%',
        height: 'auto',
        addControls: false,
        hideControls: false,
        ready: false
    };

    mw.tools.richtextEditor = function (obj) {
        if (typeof obj.element === 'string') {
            obj.element = mw.$(obj.element)[0];
        }
        if (!obj.element || obj.element === undefined) return false;

        var o = $.extend({}, mw.tools.richtextEditorSettings, obj);
        var frame = document.createElement('iframe');
        frame.richtextEditorSettings = o;
        frame.className = 'mw-fullscreen mw-iframe-editor';
        frame.scrolling = 'no';
        var name = 'mw-editor' + mw.random();
        frame.id = name;
        frame.name = name;
        frame.style.backgroundColor = "white";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('allowtransparency', 'true');
        mw.$(o.element).after(frame);
        mw.$(o.element).hide();
        $.get(mw.external_tool('editor_toolbar'), function (a) {
            if (frame.contentWindow.document === null) {
                return;
            }
            frame.contentWindow.document.open('text/html', 'replace');
            frame.contentWindow.document.write(a);
            frame.contentWindow.document.close();
            frame.contentWindow.editorArea = o.element;
            frame.contentWindow.thisFrame = frame;
            frame.contentWindow.pauseChange = true;
            frame.contentWindow.richtextEditorSettings = o;

            frame.onload = function () {
                var val = o.element.nodeName !== 'TEXTAREA' ? o.element.innerHTML : o.element.value
                frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
                if (!!o.hideControls && o.hideControls.constructor === [].constructor) {
                    var l = o.hideControls.length, i = 0;
                    for (; i < l; i++) {
                        mw.$('.mw_editor_' + o.hideControls[i], frame.contentWindow.document).hide();
                    }
                }
                if (!!o.addControls && (typeof o.addControls === 'string' || typeof o.addControls === 'function' || !!o.addControls.nodeType)) {
                    mw.$('.editor_wrapper', frame.contentWindow.document).append(o.addControls);
                }
                frame.api = frame.contentWindow.mw.wysiwyg;
                if (typeof o.ready === 'function') {
                    o.ready.call(frame, frame.contentWindow.document);
                }
                setTimeout(function () {
                    if (frame.contentWindow) {
                        frame.contentWindow.pauseChange = false;
                    }

                }, frame.contentWindow.SetValueTime);
                mw.$(obj.element).on('sourceChanged', function(e, val){
                    frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
                })
                mw.$(frame.contentWindow.document.getElementById('editor-area')).on('keyup paste change', function(){
                    if (frame.richtextEditorSettings.element.nodeName !== 'TEXTAREA') {
                        frame.richtextEditorSettings.element.innerHTML = this.innerHTML
                    }  else {
                        frame.richtextEditorSettings.element.value = this.innerHTML;
                    }
                })
                frame.contentWindow.mw.tools.createStyle(undefined, '#editor-area{' + (obj.style || '') + '}');
            }
            mw.$(obj.element).on('sourceChanged', function (e, val) {
                frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
            });

        });
        o.width = o.width != 'auto' ? o.width : '100%';
        mw.$(frame).css({width: o.width, height: o.height});
        if(o.height === 'auto') {
            mw.tools.iframeAutoHeight(frame);
        }
        frame.setValue = function (val) {
            frame.contentWindow.pauseChange = true;
            frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
            if (frame.richtextEditorSettings.element.nodeName !== 'TEXTAREA') {
                frame.richtextEditorSettings.element.innerHTML = val
            }
            else {
                frame.richtextEditorSettings.element.value = val;
            }
            frame.value = val;
            frame.contentWindow.pauseChange = false;
        }
        return frame;
    }

    mw.editor = mw.tools.richtextEditor;
