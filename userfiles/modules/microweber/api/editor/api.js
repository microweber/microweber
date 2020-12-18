mw._editorApi = function (scope) {
    return {
        action: function(targetParent, func) {
            scope.state.record({
                target: targetParent,
                value: targetParent.innerHTML
            });
            func.call();
            setTimeout(function(){
                scope.state.record({
                    target: targetParent,
                    value: targetParent.innerHTML
                });
            }, 78);
        },
        elementNode: function (c) {
            if( !c || !c.parentNode || c.parentNode === document.body ){
                return null;
            }
            try {   /* Firefox returns wrong target (div) when you click on a spin-button */
                if (typeof c.querySelector === 'function') {
                    return c;
                }
                else {
                    return scope.api.elementNode(c.parentNode);
                }
            }
            catch (e) {
                return null;
            }
        },
        fontFamily: function (font_name, sel) {
            var range = (sel || scope.getSelection()).getRangeAt(0);
            scope.api.execCommand("styleWithCSS", null, true);
            if (range.collapsed) {
                var el = scope.api.elementNode(range.commonAncestorContainer);
                scope.api.select_all(el);
                scope.api.execCommand('fontName', null, font_name);
                range.collapse();
            }
            else {
                scope.api.execCommand('fontName', null, font_name);
            }
        },
        select_all: function (el) {
            var range = scope.document.createRange();
            range.selectNodeContents(el);
            var selection = scope.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        },
        isSelectionEditable: function (sel) {
            try {
                var node = (sel || scope.getSelection()).focusNode;
                if (node === null) {
                    return false;
                }
                if (node.nodeType === 1) {
                    return node.isContentEditable;
                }
                else {
                    return node.parentNode.isContentEditable;
                }
            }
            catch (e) {
                return false;
            }
        },
        execCommand: function (a, b, c) {
            scope.actionWindow.document.execCommand('styleWithCss', 'false', false);
            var sel = scope.getSelection();
            try {  // 0x80004005
                if (scope.actionWindow.document.queryCommandSupported(a) && scope.api.isSelectionEditable()) {
                    b = b || false;
                    c = c || false;
                    if (sel.rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
                        var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(mw.tools.firstBlockLevel(node), function () {
                            scope.actionWindow.document.execCommand(a, b, c);
                            mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                        });
                    }
                }
            }
            catch (e) {
            }
        },
        _fontSize: function (size, unit) {
            unit = unit || 'px';
            var fontSize = $('<span/>', {
                'text': scope.getSelection()
            }).css('font-size', size + unit).prop('outerHTML');
            scope.api.execCommand('insertHTML', false, fontSize);
        },
        fontSize: function (size) {
            var sel = scope.getSelection();
            if (sel.isCollapsed) {
                scope.api.select_all(scope.api.elementNode(sel.focusNode));
                sel = scope.getSelection();
            }
            var range = sel.getRangeAt(0),
                common = scope.api.elementNode(range.commonAncestorContainer);
            var nodrop_state = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(common, ['allow-drop', 'nodrop']);
            if (scope.api.isSelectionEditable() && nodrop_state) {
                scope.api._fontSize(size, 'px');
            }
        },
        saveSelection: function () {
            var sel = scope.getSelection();
            scope.api.savedSelection = {
                selection: sel,
                range: sel.getRangeAt(0),
                element: mw.$(scope.api.elementNode(sel.getRangeAt(0).commonAncestorContainer))
            };
        },
        restoreSelection: function () {
            if (scope.api.savedSelection) {
                var sel = scope.getSelection();
                scope.api.savedSelection.element.attr("contenteditable", "true");
                scope.api.savedSelection.element.focus();
                scope.api.savedSelection.selection.removeAllRanges();
                scope.api.savedSelection.selection.addRange(scope.api.savedSelection.range);
            }
        },
        insertHTML: function(html) {
            return scope.api.execCommand('insertHTML', false, html);
        },
        insertImage: function (url) {
            var id =  mw.id('image_');
            var img = '<img id="' + id + '" contentEditable="false" class="element" src="' + url + '" />';
            scope.api.insertHTML(img);
            img = mw.$("#" + id)
            img.removeAttr("_moz_dirty");
            return img[0];
        }
    };
};
