/* WYSIWYG Editor */
/* ContentEditable Functions */

mw.require('css_parser.js');
mw.require('icon_selector.js');
mw.require('events.js');

//mw.lib.require('rangy');

classApplier = window.classApplier || [];
if (!Element.prototype.matches) {
    Element.prototype.matches =
        Element.prototype.matchesSelector ||
        Element.prototype.mozMatchesSelector ||
        Element.prototype.msMatchesSelector ||
        Element.prototype.oMatchesSelector ||
        Element.prototype.webkitMatchesSelector ||
        function (s) {
            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                i = matches.length;
            while (--i >= 0 && matches.item(i) !== this) {
            }
            return i > -1;
        };
}

if (typeof Selection.prototype.containsNode === 'undefined') {
    Selection.prototype.containsNode = function (a) {
        if (this.rangeCount === 0) {
            return false;
        }
        var r = this.getRangeAt(0);
        if (r.commonAncestorContainer === a) {
            return true;
        }
        if (r.endContainer === a) {
            return true;
        }
        if (r.startContainer === a) {
            return true;
        }
        if (r.commonAncestorContainer.parentNode === a) {
            return true;
        }
        if (a.nodeType !== 3) {
            var c = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer),
                b = c.querySelectorAll(a.nodeName.toLowerCase()),
                l = b.length,
                i = 0;
            if (l > 0) {
                for (; i < l; i++) {
                    if (b[i] === a) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

if (typeof Range.prototype.querySelector === 'undefined') {
    Range.prototype.querySelector = function (s) {
        var r = this;
        var f = r.extractContents();
        var node = f.querySelector(s);
        r.insertNode(f);
        return node;
    }
}

if (typeof Range.prototype.querySelectorAll === 'undefined') {
    Range.prototype.querySelectorAll = function (s) {
        var r = this;
        var f = r.extractContents();
        var nodes = f.querySelectorAll(s);
        r.insertNode(f);
        return nodes;
    };
}
mw.wysiwyg = {

    isSafeMode: function (el) {
        if (!el) {
            var sel = window.getSelection();
            if(!sel.rangeCount) return false;
            var range = sel.getRangeAt(0);
            el = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        }
        var hasSafe = mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
        var regInsafe = mw.tools.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
        return hasSafe && !regInsafe;
    },
    parseClassApplierSheet: function () {
        var sheet = document.querySelector('link[classApplier]');
        if (sheet !== null) {
            var rules = sheet.sheet.rules;
            for (var i = 0; i < rules.length; i++) {
                if (!rules[i].selectorText) continue;

                var rule = rules[i].selectorText.trim();
                var spl = rule.split('.')
                if (rule.indexOf('.') === 0
                    && rule.indexOf(':') === -1
                    && rule.indexOf('#') === -1
                    && spl.length === 2
                    && rule.indexOf('[') === -1) {
                    classApplier.push(spl[1]);
                }
            }
        }
    },
    initClassApplier: function () {
        this.parseClassApplierSheet();
        var dropdown = mw.$('#format_main ul');
        classApplier.forEach(function (cls, i) {
            dropdown.append('<li value=".' + cls + '"><a href="#"><div class="' + cls + '">Custom ' + i + '</div></a></li>')
        })
    },
    editInsideModule: function (el) {
        el = el.target ? el.target : el;
        var order = mw.tools.parentsOrder(el, ['edit', 'module']);
        if (order.edit < order.module) {
            return true;
        }
        else {
            return false;
        }
    },
    pasteFromWordUI: function () {
        if (!mw.wysiwyg.isSelectionEditable()) return false;
        mw.wysiwyg.save_selection();
        var cleaner = mw.$('<div class="mw-cleaner-block" contenteditable="true"><small class="muted">Paste document here.</small></div>')
        var inserter = mw.$('<span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right">Insert</span>')
        var clean = mw.dialog({
            content: cleaner,
            overlay: true,
            title: 'Paste from word',
            footer: inserter,
            height: 'auto',
            autoHeight: true
        });
        cleaner.on('paste', function () {
            setTimeout(function () {
                cleaner[0].innerHTML = mw.wysiwyg.clean_word(cleaner[0].innerHTML);
            }, 100)

        });
        cleaner.on('click', function () {
            if (!$(this).hasClass('active')) {
                mw.$(this).addClass('active')
                mw.$(this).html('')
            }
        });
        inserter.on('click', function () {
            mw.wysiwyg.restore_selection();
            mw.wysiwyg.insert_html(cleaner.html());
            clean.remove();
        });
        //cleaner.after(inserter)
    },
    globalTarget: document.body,
    allStatements: function (c, f) {
        var sel = window.getSelection(),
            range = sel.getRangeAt(0),
            common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        if (mw.wysiwyg.isSelectionEditable()) {
            if (typeof c === 'function') {
                c.call();
            }
        }
        else {
            if (typeof f === 'function') {
                f.call();
            }
        }
    },
    action: {
        removeformat: function () {
            var sel = window.getSelection();
            var r = sel.getRangeAt(0);
            var c = r.commonAncestorContainer;
            mw.wysiwyg.removeStyles(c, sel);
        }
    },
    removeStyles: function (common, sel) {
        if (!!common.querySelectorAll) {
            var all = common.querySelectorAll('*'), l = all.length, i = 0;
            for (; i < l; i++) {
                var el = all[i];
                if (typeof sel !== 'undefined' && sel.containsNode(el, true)) {
                    mw.$(el).removeAttr("style");
                }
            }
        }
        else {
            mw.wysiwyg.removeStyles(common.parentNode);
        }
    },
    init_editables: function (module) {
        if (window['mwAdmin']) {
            if (typeof module !== 'undefined') {
                mw.wysiwyg.contentEditable(module, false);
                mw.$(module.querySelectorAll(".edit")).each(function () {
                    mw.wysiwyg.contentEditable(this, true);
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                    });
                });
            }
            else {
                var editables = document.querySelectorAll('[contenteditable]'), l = editables.length, x = 0;
                for (; x < l; x++) {
                    mw.wysiwyg.contentEditable(editables[x], 'inherit');
                }
                mw.$(".edit").each(function () {
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                        if (this.querySelectorAll('*').length === 0 && mw.live_edit.hasAbilityToDropElementsInside(this)) {
                            mw.wysiwyg.modify(this, function () {
                                if (!mw.wysiwyg.isSafeMode(this)) {
                                    this.innerHTML = '<p class="element">' + this.innerHTML + '</p>';
                                }
                            });
                        }

                    }, false, true);
                    mw.$(this).mouseenter(function () {
                        if (this.querySelectorAll('*').length === 0 && mw.live_edit.hasAbilityToDropElementsInside(this)) {

                            mw.wysiwyg.modify(this, function () {
                                if (!mw.wysiwyg.isSafeMode(this)) {
                                    this.innerHTML = '<p class="element">' + this.innerHTML + '&nbsp;</p>';
                                }
                            });
                        }
                    });
                });
                mw.$(".empty-element, .ui-resizable-handle").each(function () {
                    mw.wysiwyg.contentEditable(this, false);
                });
                mw.on.moduleReload(function () {
                    mw.wysiwyg.nceui();
                })
            }
        }
    },
    modify: function (el, callback) {
        var curr = mw.askusertostay;
        if (typeof el === 'function') {
            callback = el;
            callback.call();
        }
        else {
            callback.call(el);
        }
        mw.askusertostay = curr;
    },
    fixElements: function (parent) {
        var a = parent.querySelectorAll(".element"), l = a.length;
        i = 0;
        for (; i < l; i++) {
            if (a[i].innerHTML === '' || a[i].innerHTML.replace(/\s+/g, '') === '') {
                a[i].innerHTML = '&zwj;&nbsp;&zwj;';
            }
        }
    },
    removeEditable: function (skip) {
        skip = skip || [];
        if (!mw.is.ie) {
            var i=0, i2,
                all = document.getElementsByClassName('edit'),
                len = all.length;
            for (; i < len; i++) {
                if(skip.length) {
                    var shouldSkip = false;
                    mw.wysiwyg.contentEditable(all[i], false);
                    for (i2=0;i2<skip.length;i2++){
                        if(skip[i2] === all[i]) {
                            shouldSkip = true;
                        }
                    }
                    if(!shouldSkip) {
                        mw.wysiwyg.contentEditable(all[i], false);
                    }

                } else {
                    mw.wysiwyg.contentEditable(all[i], false);
                }

            }
        }
        else {
            mw.$(".edit [contenteditable='true'], .edit").removeAttr('contenteditable');
        }
    },
    _lastCopy: null,
    handleCopyEvent: function (event) {
        this._lastCopy = event.target;
    },
    contentEditableSplitTypes: function (el) {

    },
    contentEditable: function (el, state) {

        if (!el) {
            return;
        }
        if(el.nodeType !== 1){
            el = mw.wysiwyg.validateCommonAncestorContainer(el);
            if (!el) {
                return;
            }
        }
        if(typeof state === 'undefined'){
            return el.contentEditable;
        }
        if (state) {
            mw.on.DOMChangePause = true;
            if (!el._handleCopy) {
                el._handleCopy = true;
                mw.$(el).on('copy', function(ev){
                    mw.wysiwyg.handleCopyEvent(ev);
                });
            }
        }
        if(state === true){
            state = 'true';
        } else if(state === false) {
            state = 'false';
        }
        if(state === 'true'){
            if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'noedit'])){
                state = 'false'
            }
        }
        if(state === 'true'){
            if(mw.wysiwyg.isSafeMode(el)){
            } else {

                el = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['edit', 'regular-mode']);
            }

        }
        if (typeof(mw.liveedit) != 'undefined' && mw.liveedit.data.set('mouseup', 'isIcon')) {
            state = false;
        }

        if(el && el.contentEditable !== state) { // chrome setter needs a check

            el.contentEditable = state;
        }

        mw.on.DOMChangePause = false;
    },

    prepareContentEditable: function () {
        mw.on("EditMouseDown", function (e, el, target, originalEvent) {
            mw.$('.safe-mode').each(function () {
                mw.wysiwyg.contentEditable(this, 'inherit');
            });

            if (!mw.wysiwyg.isSafeMode(target)) {
                if (!mw.is.ie) { //Non IE browser
                    var orderValid = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(originalEvent.target, ['edit', 'module']);
                    mw.$('.safe-mode').each(function () {
                        mw.wysiwyg.contentEditable(this, false);
                    });
                    mw.wysiwyg.contentEditable(target, orderValid);
                }
                else {   // IE browser
                    mw.wysiwyg.removeEditable();
                    var cls = target.className;
                    if (!mw.tools.hasClass(cls, 'empty-element') && !mw.tools.hasClass(cls, 'ui-resizable-handle')) {
                        if (mw.tools.hasParentsWithClass(el, 'module')) {
                            mw.wysiwyg.contentEditable(target, true);
                        }
                        else {
                            if (!mw.tools.hasParentsWithClass(target, "module")) {
                                if (mw.isDragItem(target)) {
                                    mw.wysiwyg.contentEditable(target, true);
                                }
                                else {
                                    mw.tools.foreachParents(target, function (loop) {
                                        if (mw.isDragItem(this)) {
                                            mw.wysiwyg.contentEditable(target, true);
                                            mw.tools.loop[loop] = false;
                                        }
                                    });
                                }
                            }
                        }
                    }
                }
            }
            else {
                var firstBlock = target;
                var blocks = ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'section', 'footer', 'ul', 'ol'];
                var blocksClass = ['safe-element'];
                var po = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(firstBlock, ['edit', 'module']);

                if (po) {
                    if (blocks.indexOf(firstBlock.nodeName.toLocaleLowerCase()) === -1 && !mw.tools.hasAnyOfClassesOnNodeOrParent(firstBlock, blocksClass)) {
                        var cls = [];
                        blocksClass.forEach(function (item) {
                            cls.push('.' + item);
                        });
                        cls = cls.concat(blocks);
                        firstBlock = mw.tools.firstMatchesOnNodeOrParent(firstBlock, cls);
                    }
                    mw.$("[contenteditable='true']").not(firstBlock).attr("contenteditable", "false");
                    mw.wysiwyg.contentEditable(firstBlock, true);
                }

            }


        });
    },
    hide_drag_handles: function () {
        mw.$(".mw-wyswyg-plus-element").hide();
    },
    show_drag_handles: function () {
        mw.$(".mw-wyswyg-plus-element").show();
    },

    _external: function () {
        var external = document.createElement('div');
        external.className = 'wysiwyg_external';
        document.body.appendChild(external);
        return external;
    },
    isSelectionEditable: function (sel) {
        try {
            var activeCase = true;
            if(!sel) {
                activeCase = document.activeElement.nodeName !== 'INPUT' && document.activeElement.nodeName !== 'TEXTAREA'
            }
            var node = (sel || window.getSelection()).focusNode;
            if (node === null) {
                return false;
            }
            if (node.nodeType === 3) {
                node = mw.wysiwyg.validateCommonAncestorContainer(node)
            }
            if (node.nodeType === 1) {
                return activeCase && node.isContentEditable && node.nodeName !== 'INPUT' && node.nodeName !== 'TEXTAREA';
            }

        }
        catch (e) {
            return false;
        }
    },
    insertList: function (a, b, c, elementNode) {
        var parent = elementNode.parentElement;
        if(elementNode.nodeName !== 'UL' && elementNode.nodeName !== 'OL') {
            mw.wysiwyg.formatNative('div')
        }
        var currParent = parent.getAttribute('contenteditable');
        parent.contentEditable = 'true';

        Array.from(parent.querySelectorAll('[contenteditable]')).forEach(function (node) {
            node.removeAttribute('contenteditable')
        })
        setTimeout(function(){
            if(!currParent) {
                parent.removeAttribute('contenteditable')
            } else {
                parent.setAttribute('contenteditable', currParent)
            }

        }, 310)
    },
    insertList2: function (a, b, c, elementNode) {
        var parent = elementNode.parentElement.parentElement;
        var notEdit = !mw.tools.hasClass(elementNode, 'edit');
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
        var ul;
        var paragraph = mw.tools.firstParentOrCurrentWithTag(elementNode, 'p');
        if (paragraph && notEdit) {
            var tag = a === 'insertorderedlist' ? 'ol' : 'ul';
            ul = document.createElement(tag);
            var li = document.createElement('li');
            ul.appendChild(li);
            while (paragraph.firstChild) {
                li.appendChild(paragraph.firstChild);
            }
            paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
            paragraph.remove();

            return;
        } else {
            if (notEdit) {
                var edit = mw.tools.firstParentWithClass(elementNode, 'edit');
                edit.contentEditable = true;
                $('[contenteditable]').not(edit).removeAttr('contenteditable');

                console.log(mw.tools.firstParentWithClass(elementNode, 'edit'));
            }
            document.execCommand(a, b, c);
            if(notEdit) {
                $('[contenteditable]').removeAttr('contenteditable');
            }
            ul = mw.tools.firstParentOrCurrentWithTag(getSelection().focusNode, ['ul', 'ol']);
        }
        if(ul) {
            mw.tools.addClass(ul, 'mw-richtext-list');
        }

        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });

    },
    execCommandFilter: function (a, b, c) {
        var arr = ['justifyCenter', 'justifyFull', 'justifyLeft', 'justifyRight'];
        var align;
        var node = window.getSelection().focusNode;
        var elementNode = mw.tools.firstBlockLevel( mw.wysiwyg.validateCommonAncestorContainer(node));
        var parent = elementNode.parentNode
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
        if (a === 'insertorderedlist' || a === 'insertunorderedlist') {
            this.insertList(a, b, c, elementNode);
            /*            mw.liveEditState.record({
                           target: parent,
                           value: parent.innerHTML
                       });
                       return;*/
        }

        if (mw.wysiwyg.isSafeMode(elementNode) && arr.indexOf(a) !== -1) {
            align = a.split('justify')[1].toLowerCase();
            if (align === 'full') {
                align = 'justify';
            }
            elementNode.style.textAlign = align;
            mw.wysiwyg.change(elementNode);
            mw.liveEditState.record({
                target: parent,
                value: parent.innerHTML
            });
            return false;
        }
        if (mw.is.firefox && arr.indexOf(a) !== -1) {

            if (elementNode.nodeName === 'P') {
                align = a.split('justify')[1].toLowerCase();
                if (align === 'full') {
                    align = 'justify';
                }
                elementNode.style.textAlign = align;
                mw.wysiwyg.change(elementNode)
                mw.liveEditState.record({
                    target: parent,
                    value: parent.innerHTML
                });
                return false;
            }
        }
        return true;
    },
    execCommand: function (a, b, c) {
        document.execCommand('styleWithCss', 'false', false);
        var sel = getSelection();

        var node = sel.focusNode, elementNode;
        if (node) {
            elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        }

        try {  // 0x80004005
            if (document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable(getSelection())) {
                b = b || false;
                c = c || false;

                var before = mw.$(node).clone()[0];
                if (sel.rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
                    document.execCommand(a, b, c);
                }

                if (node !== null && mw.loaded) {
                    mw.wysiwyg.change(node);
                    mw.trigger('execCommand', [a, node, before, elementNode]);
                }
            }
        }
        catch (e) {
        }
    },
    selection: '',
    _do: function (what) {
        mw.wysiwyg.execCommand(what);
        if (typeof mw.wysiwyg.action[what] === 'function') {
            mw.wysiwyg.action[what]();
        }
    },
    save_selected_element: function () {
        mw.$("#mw-text-editor").addClass("editor_hover");
    },
    deselect_selected_element: function () {
        mw.$("#mw-text-editor").removeClass("editor_hover");
    },
    nceui: function () {
        if (mw.settings.liveEdit) {
            mw.wysiwyg.execCommand('enableObjectResizing', false, 'false');
            mw.wysiwyg.execCommand('2D-Position', false, false);
            mw.wysiwyg.execCommand("enableInlineTableEditing", null, false);
        }
    },
    _pasteManager: undefined,
    pasteManager: function (html) {
        html = mw.wysiwyg.clean_word(html)
        mw.wysiwyg._pasteManager = this._pasteManager || document.createElement('div');
        mw.wysiwyg._pasteManager.innerHTML = html;
        mw.$('*', mw.wysiwyg._pasteManager).removeAttr('style');
        return mw.wysiwyg._pasteManager.innerHTML;
    },
    cleanExcel: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;
        mw.$("[style*='mso-spacerun']", parser).remove()
        mw.$("style", parser).remove()
        mw.$('table', parser)
            .width('100%')
            .addClass('mw-wysiwyg-table')
            .removeAttr('width');
        return parser.innerHTML;
    },
    pastedFromExcel: function (clipboard) {
        var html = clipboard.getData('text/html');
        return html.indexOf('ProgId content=Excel.Sheet') !== -1
    },
    areSameLike: function (el1, el2) {
        if (!el1 || !el2) return false;
        if (el1.nodeType !== el2.nodeType) return false;
        if (!!el1.className.trim() || !!el2.className.trim()) {
            return false;
        }

        var css1 = (el1.getAttribute('style') || '').replace(/\s/g, '');
        var css2 = (el2.getAttribute('style') || '').replace(/\s/g, '');

        if (css1 === css2 && el1.nodeName === el2.nodeName) {
            return true;
        }

        return false;
    },
    cleanUnwantedTags: function (body) {
        var scope = this;
        mw.$('*', body).each(function () {
            if (this.nodeName !== 'A' && mw.ea.helpers.isInlineLevel(this) && (this.className.trim && !this.className.trim())) {
                if (scope.areSameLike(this, this.nextElementSibling) && this.nextElementSibling === this.nextSibling) {
                    if (this.nextSibling !== this.nextElementSibling) {
                        this.appendChild(this.nextSibling);
                    }
                    this.innerHTML = this.innerHTML + this.nextElementSibling.innerHTML;
                    this.nextElementSibling.innerHTML = '';
                    this.nextElementSibling.className = 'mw-skip-and-remove';
                }
            }
        });
        mw.$('.mw-skip-and-remove,script', body).remove();
        return body;
    },
    doLocalPaste: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;

        mw.$('[style]', parser).removeAttr('style');
        mw.$('[id]', parser).each(function () {
            this.id = 'dlp-item-' + mw.random();
        });
        mw.wysiwyg.insert_html(parser.innerHTML);
    },
    isLocalPaste: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;
        return (this._lastCopy && this._lastCopy.innerHTML && this._lastCopy.innerHTML.contains(html)) || parser.querySelector('.module,.element,.edit') !== null;
    },
    paste: function (e) {
        var html, clipboard;

        if (!!e.originalEvent) {
            clipboard = e.originalEvent.clipboardData || window.clipboardData;
        }
        else {
            clipboard = e.clipboardData || window.clipboardData;
        }
        if (mw.wysiwyg.isSafeMode(e.target)) {
            if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {
                var text = clipboard.getData('text');
                if(text) {
                    mw.wysiwyg.insert_html(text);
                }
                e.preventDefault();
                return '';
            }
        }
        if (mw.wysiwyg.isLocalPaste(clipboard)) {
            mw.wysiwyg.doLocalPaste(clipboard);
            e.preventDefault();
            return '';
        }


        if (mw.wysiwyg.pastedFromExcel(clipboard)) {
            html = mw.wysiwyg.cleanExcel(clipboard)
            mw.wysiwyg.insert_html(html);
            e.preventDefault();
            return '';
        }


        if (clipboard.files.length > 0) {
            var i = 0;
            for (; i < clipboard.files.length; i++) {
                var item = clipboard.files[i];
                if (item.type.indexOf('image/') != -1) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        mw.wysiwyg.insert_html('<img src="' + (e.target.result) + '">');
                    }
                    reader.readAsDataURL(item)
                }
            }
            e.preventDefault();
        }
        else {
            if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {
                if (!mw.is.ie) {
                    html = clipboard.getData('text/html');
                    var text = clipboard.getData('text');
                    var isPlainText = false;
                    if (!html && text) {
                        isPlainText = true;
                        if (/\r\n/.test(text)) {
                            var wrapper = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                            wrapper = mw.tools.firstMatchesOnNodeOrParent(wrapper, ['.element', 'p', 'div', '.edit'])
                            var tag = wrapper.nodeName.toLowerCase();
                            html = '<' + tag + ' id="element_' + mw.random() + '">' + text.replace(/\r\n/g, "<br>") + '</' + tag + '>';
                        }

                    }
                    else {

                    }
                }
                else {
                    html = clipboard.getData('text');
                }
                if (!!html) {
                    if (mw.form) {
                        var is_link = mw.form.validate.url(html);
                        if (is_link) {
                            html = "<a href='" + html + "'>" + html + "</a>";
                        }
                    }

                    html = mw.wysiwyg.pasteManager(html);

                    mw.wysiwyg.insert_html(html);
                    if (e.target.querySelector) {
                        mw.$(e.target.querySelectorAll('[style*="outline"]')).css({
                            outline: 'none'
                        })
                    }
                    e.preventDefault();

                }
            }
        }
    },
    hasContentFromWord: function (node) {
        if (node.getElementsByTagName("o:p").length > 0 ||
            node.getElementsByTagName("v:shapetype").length > 0 ||
            node.getElementsByTagName("v:path").length > 0 ||
            node.querySelector('.MsoNormal') !== null) {
            return true;
        }
        return false;
    },
    prepare: function () {
        mw.wysiwyg.external = mw.wysiwyg._external();
        mw.$("#liveedit_wysiwyg").on("mousedown mouseup click", function (event) {
            event.preventDefault();
        });
        var items = mw.$(".element").not(".module");
        mw.$(".mw_editor").hover(function () {
            mw.$(this).addClass("editor_hover")
        }, function () {
            mw.$(this).removeClass("editor_hover")
        });
    },
    deselect: function (s) {
        var s = s || window.getSelection();
        s.empty ? s.empty() : s.removeAllRanges();
    },
    editors_disabled: false,
    enableEditors: function () {
        mw.$(".mw_editor, #mw_small_editor").removeClass("mw-editor-disabled");
        mw.wysiwyg.editors_disabled = false;
    },
    disableEditors: function () {
        /*  mw.$(".mw_editor, #mw_small_editor").addClass("mw-editor-disabled");
         mw.wysiwyg.editors_disabled = false;   */
    },
    checkForTextOnlyElements: function (e, method) {
        var e = e || false;
        var method = method || 'selection';
        if (method === 'selection') {
            var sel = window.getSelection();
            var f = sel.focusNode;
            f = mw.tools.hasClass(f, 'edit') ? f : mw.tools.firstParentWithClass(f, 'edit');
            if (f.attributes != undefined && !!f.attributes.field && f.attributes.field.nodeValue == 'title') {
                if (!!e) {
                    mw.event.cancel(e, true);
                }
                return false;
            }
        }
    },
    merge: {
        /* Executes on backspace or delete */
        isMergeable: function (el) {
            if (!el) return false;
            if (el.nodeType === 3) return true;
            var is = false;
            var css =  getComputedStyle(el)

            var display = css.getPropertyValue('display');

            var position = css.getPropertyValue('position');
            var isInline = display === 'inline';
            if (isInline) return true;
            var mergeables = ['p', '.element', 'div:not([class])', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
            mergeables.forEach(function (item) {
                if (el.matches(item)) {
                    is = true;
                }
            });

            if (is) {
                if (el.querySelector('.module') !== null || mw.tools.hasClass(el, 'module')) {
                    is = false;
                }
            }
            return is;
        },
        manageBreakables: function (curr, next, dir, event) {
            var isnonbreakable = mw.wysiwyg.merge.isInNonbreakable(curr, dir);
            if (isnonbreakable) {
                var conts = getSelection().getRangeAt(0);
                event.preventDefault();

                if (next !== null) {

                    if (next.nodeType === 3 && /\r|\n/.exec(next.nodeValue) !== null) {
                        event.preventDefault();
                        return false;
                    }

                    if (dir == 'next') {
                        mw.wysiwyg.cursorToElement(next)
                    }
                    else {
                        mw.wysiwyg.cursorToElement(next, 'end')
                    }
                }
                else {
                    return false;
                }
            }
        },
        isInNonbreakable: function (el, dir) {
            var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

            if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                absNext = mw.wysiwyg.merge.findNextNearest(el, dir, true)
            }

            if (absNext.nodeType === 1) {
                if (mw.tools.hasAnyOfClasses(absNext, ['nodrop', 'allow-drop'])) {
                    return false;
                }
                if (absNext.querySelector('.nodrop', '.allow-drop') !== null) {
                    return false;
                }
            }
            if (mw.wysiwyg.merge.alwaysMergeable(absNext) && (mw.wysiwyg.merge.alwaysMergeable(absNext.firstElementChild) || !absNext.firstElementChild)) {
                return false;
            }
            if (el.textContent == '') {

                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
                if (absNext.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
                }
            }

            if (el.nodeType === 1 && !!el.textContent.trim()) {
                return false;
            }
            if (el.nextSibling === null && el.nodeType === 3 && dir == 'next') {
                var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);
                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
                if (/\r|\n/.exec(absNext.nodeValue) !== null) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
                }

                if (absNextNext.nodeType === 1) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext) || mw.wysiwyg.merge.isInNonbreakableClass(absNextNext.firstChild);
                }
                else if (absNextNext.nodeType === 3) {
                    return true
                }
                else {
                    return false;
                }
            }

            if (el.previousSibling === null && el.nodeType === 3 && dir == 'prev') {
                var absNext = mw.wysiwyg.merge.findNextNearest(el, 'prev');
                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, 'prev');
                if (absNextNext.nodeType === 1) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext);
                }
                else if (absNextNext.nodeType === 3) {
                    return true;
                }
                else {
                    return false;
                }
            }
            el = mw.wysiwyg.validateCommonAncestorContainer(el)

            var is = mw.wysiwyg.merge.isInNonbreakableClass(el)
            return is;

        },
        isInNonbreakableClass: function (el, dir) {
            var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

            if (el.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) === null) return false;
            el = mw.wysiwyg.validateCommonAncestorContainer(el)
            var classes = ['unbreakable', '*col', '*row', '*btn', '*icon', 'module', 'edit'];
            var is = false;
            classes.forEach(function (item) {
                if (item.indexOf('*') === 0) {
                    var item = item.split('*')[1];
                    if (el.className.indexOf(item) !== -1) {
                        is = true;
                    }
                    else {
                        mw.tools.foreachParents(el, function (loop) {
                            if (this.className.indexOf(item) !== -1 && !this.contains(el)) {
                                is = true;
                                mw.tools.stopLoop(loop);
                            }
                            else {

                                is = false;
                                mw.tools.stopLoop(loop);
                            }
                        })
                    }
                }
                else {
                    if (mw.tools.hasClass(el, item) || mw.tools.hasParentsWithClass(el, item)) {
                        is = true;
                    }
                }
            });
            return is;
        },
        getNext: function (curr) {
            var next = curr.nextSibling;
            while (curr !== null && curr.nextSibling === null) {
                curr = curr.parentNode;
                next = curr.nextSibling;
            }
            return next;
        },
        getPrev: function (curr) {
            var next = curr.previousSibling;
            while (curr !== null && curr.previousSibling === null) {
                curr = curr.parentNode;
                next = curr.previousSibling;
            }
            return next;
        },
        findNextNearest: function (el, dir, searchElement) {
            searchElement = typeof searchElement === 'undefined' ? false : true;
            if (dir == 'next') {
                var dosearch = searchElement ? 'nextElementSibling' : 'nextSibling'
                var next = el[dosearch];
                if (next === null) {
                    while (el[dosearch] === null) {
                        el = el.parentNode;
                        next = el[dosearch];

                    }
                }
            }
            else {
                var dosearch = searchElement ? 'previousElementSibling' : 'previousSibling'
                var next = el[dosearch];
                if (next === null) {
                    while (el[dosearch] === null) {
                        el = el.parentNode;
                        next = el[dosearch];

                    }
                }
            }
            return next;
        },
        alwaysMergeable: function (el) {

            if (!el) {
                return false;
            }
            if (el.nodeType === 3) {
                return mw.wysiwyg.merge.alwaysMergeable(mw.wysiwyg.validateCommonAncestorContainer(el))
            }
            if (el.nodeType === 1) {
                if (/^(?:area|br|col|embed|hr|img|input|link|meta|param)$/i.test(el.tagName)) {
                    return true;
                }
                if (/^(?:strong|em|i|b|li)$/i.test(el.tagName)) {
                    return true;
                }
                if (/^(?:span)$/i.test(el.tagName) && !el.className) {
                    return true;
                }
            }

            if (mw.tools.hasClass(el, 'module')) return false;
            if (mw.tools.hasParentsWithClass(el, 'module')) {
                var ord = mw.tools.parentsOrder(el, ['edit', 'module']);
                //todo
            }

            var selectors = [
                    'p.element', 'div.element', 'div:not([class])',
                    'h1.element', 'h2.element', 'h3.element', 'h4.element', 'h5.element', 'h6.element',
                    '.edit  h1', '.edit  h2', '.edit  h3', '.edit  h4', '.edit  h5', '.edit  h6',
                    '.edit p'
                ],
                final = false,
                i = 0;
            for (; i < selectors.length; i++) {
                var item = selectors[i];
                if (el.matches(item)) {
                    final = true;
                    break;
                }
            }

            return final;

        }
    },
    init: function (selector) {

        selector = selector || ".mw_editor_btn";
        var mw_editor_btns = mw.$(selector).not('.ready');
        mw_editor_btns
            .addClass('ready')
            .on("click", function (event) {
                if (mw.wysiwyg.editors_disabled) {
                    return false;
                }
                event.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    var rectarget = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                    rectarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(rectarget, ['element', 'edit']);
                    if(mw.liveEditState){
                        var currState = mw.liveEditState.state()
                        if(currState[0].$id !== 'wysiwyg   '){
                            mw.liveEditState.record({
                                target: rectarget,
                                value: rectarget.innerHTML,
                                $id: 'wysiwyg'
                            });
                        }
                    }

                    var command = mw.$(this).dataset('command');
                    if (!command.contains('custom-')) {
                        mw.wysiwyg._do(command);
                    }
                    else {
                        var name = command.replace('custom-', "");
                        if(name === 'link') {
                            mw.wysiwyg.link(undefined, undefined, getSelection().toString());
                        } else {
                            mw.wysiwyg[name]();
                        }

                    }
                    if(mw.liveEditState){
                        mw.liveEditState.record({
                            target: rectarget,
                            value: rectarget.innerHTML
                        });
                    }

                    mw.wysiwyg.check_selection(event.target);

                }

            });
        mw_editor_btns.hover(function () {
            mw.$(this).addClass("mw_editor_btn_hover");
        }, function () {
            mw.$(this).removeClass("mw_editor_btn_hover");
        });
        if (mw.wysiwyg.ready) return;
        mw.wysiwyg.ready = true;
        mw.$(document.body).on('mouseup', function (event) {
            if (event.target.isContentEditable) {
                if(event.target.nodeName){
                    mw.wysiwyg.check_selection(event.target);
                }
            }
        });
        mw.$(document.body).on('keydown', function (event) {

            if ((event.keyCode == 46 || event.keyCode == 8) && event.type == 'keydown') {
                mw.tools.removeClass(mw.image_resizer, 'active');
                mw.wysiwyg.change('.element-current');
            }
            if (event.type === 'keydown') {

                if (mw.tools.isField(event.target) || !event.target.isContentEditable) {
                    return true;
                }
                var sel = window.getSelection();
                if (mw.event.is.enter(event)) {
                    setTimeout(function () {
                        if(mw.liveEditDomTree) {
                            var focused = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode)
                            mw.liveEditDomTree.refresh(focused.parentNode)
                        }
                    }, 10);
                    if (mw.wysiwyg.isSafeMode(event.target)) {
                        var isList = mw.tools.firstMatchesOnNodeOrParent(event.target, ['li', 'ul', 'ol']);
                        if (!isList) {
                            event.preventDefault();
                            var id = mw.id('mw-br-');
                            mw.wysiwyg.insert_html('<br>\u200C');
                        }
                    }
                }
                if (sel.rangeCount > 0) {
                    var r = sel.getRangeAt(0);
                    if (event.keyCode == 9 && !event.shiftKey && sel.focusNode.parentNode.iscontentEditable && sel.isCollapsed) {   /* tab key */
                        mw.wysiwyg.insert_html('&nbsp;&nbsp;&nbsp;&nbsp;');
                        return false;
                    }
                    return mw.wysiwyg.manageDeleteAndBackspace(event, sel);
                }


            }
        });
        mw.on.tripleClick(document.body, function (target) {
            mw.wysiwyg.select_all(target);
            if (mw.tools.hasParentsWithClass(target, 'element')) {
                //mw.wysiwyg.select_all(mw.tools.firstParentWithClass(target, 'element'));
            }
            var s = window.getSelection();
            if(!s.rangeCount) {
                return;
            }
            var r = s.getRangeAt(0);
            var c = r.cloneContents();
            //var common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
            var common = r.commonAncestorContainer;
            if (common.nodeType === 1) {
                if (mw.tools.hasClass(common, 'element')) {
                    mw.wysiwyg.select_all(common)
                }

            }
            else {
                common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
                if (mw.tools.hasClass(common, 'element')) {
                    mw.wysiwyg.select_element(common)
                }
            }
            var a = common.querySelectorAll('*'), l = a.length, i = 0;
            for (; i < l; i++) {
                if (!!s.containsNode && s.containsNode(a[i], true)) {
                    r.setEndBefore(a[i]);
                    break;
                    return false;
                }
            }
        });


        mw.$(document.body).on('keyup', function (e) {
            mw.smallEditorCanceled = true;
            mw.smallEditor.css({
                visibility: "hidden"
            });
            if (e.target.isContentEditable && !mw.tools.isField(e.target)) {
                mw.wysiwyg.change(e.target)


                if (!document.body.editor_typing_startTime) {
                    document.body.editor_typing_startTime = new Date();
                }


                var started_typing = mw.tools.hasAnyOfClasses(this, ['isTyping']);
                if (!started_typing) {
                    // isTyping class is removed from livedit.js
                    mw.tools.addClass(this, 'isTyping');
                    document.body.editor_typing_startTime = new Date();

                    // mw.tools.addClass(this, 'isTypingStill');

                    // var myVarisTypingStill;
                    //
                    // var myVarisTypingStillTimeoutFunction = function() {
                    //     myVarisTypingStill = setTimeout(function(){
                    //         if(document.body){
                    //             if(!mw.tools.hasAnyOfClasses(document.body, ['isTyping'])){
                    //                 mw.tools.removeClass(document.body, 'isTypingStill');
                    //             }
                    //
                    //         }
                    //     }, 1337);
                    // }
                    //
                    // clearTimeout(myVarisTypingStill);
                    // myVarisTypingStillTimeoutFunction();



                    if(mw._initHandles){
                        mw._initHandles.hideAll();
                    }
                } else {
                    // user is typing
                    started_typing_endTime = new Date();
                    var timeDiff = started_typing_endTime - document.body.editor_typing_startTime; //in ms
                    timeDiff /= 1000;
                    var seconds = Math.round(timeDiff);
                    document.body.editor_typing_seconds = seconds;
                }

                if (document.body.editor_typing_seconds) {
                    //how much seconds user is typing
                    if (document.body.editor_typing_seconds > 10) {
                        mw.trigger('editUserIsTypingForLong', this)
                        document.body.editor_typing_seconds = 0;
                        document.body.editor_typing_startTime = 0;
                    }
                }


                mw.$(this._onCloneableControl).hide();
                if (mw.event.is.enter(e)) {/*

                    mw.$(".element-current").removeClass("element-current");
                    var el = document.querySelectorAll('.edit .element'), l = el.length, i = 0;
                    for (; i < l; i++) {
                        if (!el[i].id) {
                            el[i].id = mw.wysiwyg.createElementId();
                        }
                    }
                    e.preventDefault();
                    if (!e.shiftKey) {
                        var p = mw.wysiwyg.findTagAcrossSelection('p');
                    }
                    var newNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                    if (newNode.id) {
                        newNode.id = mw.wysiwyg.createElementId();
                    }*/
                }
            }

            if (e.target.isContentEditable
                && !e.shiftKey
                && !e.ctrlKey
                && e.keyCode !== 27
                && e.keyCode !== 116
                && e.keyCode !== 17
                && (e.keyCode < 37 || e.keyCode > 40)) {
                mw.wysiwyg.change(e.target);
            }

            if(e && e.target) {
                mw.wysiwyg.check_selection(e.target);
            }

        });
    },
    createElementId: function () {
        return 'mw-element_' + mw.random();
    },
    change: function (el) {
        if (typeof el === 'string') {
            el = document.querySelector(el);
        }
        var target = null;
        if (mw.tools.hasClass(el, 'edit')) {
            mw.tools.addClass(el, 'changed');
            target = el;
            mw.trigger('editChanged', target)
        }
        else if (mw.tools.hasParentsWithClass(el, 'edit')) {
            target = mw.tools.firstParentWithClass(el, 'edit');
            mw.tools.addClass(target, 'changed');
            mw.trigger('editChanged', target)
        }
        if (target !== null) {
            mw.tools.foreachParents(target, function () {
                if (mw.tools.hasClass(this, 'edit')) {
                    mw.tools.addClass(this, 'changed');
                    mw.trigger('editChanged', this)
                }
            });
            mw.askusertostay = true;
            mw.drag.initDraft = true;
        }

    },
    validateCommonAncestorContainer: function (c) {
        if( !c || !c.parentNode || c.parentNode === document.body ){
            return null;
        }
        try {   /* Firefox returns wrong target (div) when you click on a spin-button */
            if (typeof c.querySelector === 'function') {
                return c;
            }
            else {
                return mw.wysiwyg.validateCommonAncestorContainer(c.parentNode);
            }
        }
        catch (e) {
            return null;
        }
    },

    editable: function (el) {
        el = mw.wysiwyg.validateCommonAncestorContainer(el);
        return el.isContentEditable && ['SELECT', 'INPUT', 'TEXTAREA'].indexOf(el.nodeName) === -1;
    },
    getNextNode: function (node) {
        if (node.nextSibling) {
            return node.nextSibling
        } else {
            return this.getNextNode(node.parentNode);
        }
    },
    cursorToElement: function (node, a) {

        if (!node) {
            return false;
        }
        if(mw.tools.hasAnyOfClassesOnNodeOrParent(node, ['safe-element', 'icon', 'mw-icon', 'material-icons', 'mw-wysiwyg-custom-icon'])){
            return;
        }
        mw.wysiwyg.contentEditable(node, true);
        a = (a || 'start').trim();
        var sel = window.getSelection();
        var r = document.createRange();
        sel.removeAllRanges();
        if (a === 'start') {
            r.selectNodeContents(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if (a === 'end') {
            r.selectNodeContents(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if (a === 'before') {
            r.selectNode(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if (a === 'after') {
            var range = document.createRange();
            range.setStartAfter(node);
            range.collapse(true);

            sel.removeAllRanges();
            sel.addRange(range);
        }

    },
    rfapplier: function (tag, classname, style_object) {
        var parent, fnode = getSelection().focusNode;
        var id = mw.id('mw-applier-element-');
        this.execCommand("insertHTML", false, '<'+tag+' '+(classname ? 'class="' + classname + '"' : '')+' id="'+id+'">'+ getSelection()+'</'+tag+'>');
        var $el = mw.$('#' + id);
        if (style_object) {
            $el.css(style_object);
        }
        return $el[0];
    },
    applier: function (tag, classname, style_object) {
        var classname = classname || '';
        if (mw.wysiwyg.isSelectionEditable()) {
            var range = window.getSelection().getRangeAt(0);
            var selectionContents = range.extractContents();
            var el = document.createElement(tag);
            el.className = classname;
            typeof style_object !== 'undefined' ? mw.$(el).css(style_object) : '';
            el.appendChild(selectionContents);
            range.insertNode(el);
            mw.wysiwyg.change(el);
            return el;
        }
    },
    external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        mw.$(mw.wysiwyg.external).css({
            top: offset.top - mw.$(window).scrollTop() + el.height(),
            left: offset.left
        });
        mw.$(mw.wysiwyg.external).html("<iframe src='" + url + "' scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');
        frame.contentWindow.thisframe = frame;
    },
    getExternalData: function (url, cb) {
        var has = mw.storage.get(url);
        if (has) {
            cb.call(has, has)
        }
        else {
            $.get(url, function (data) {
                mw.storage.set(url, data)
                cb.call(data, data)
            })
        }
    },
    todo_external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        mw.$(mw.wysiwyg.external).css({
            top: offset.top - mw.$(window).scrollTop() + el.height(),
            left: offset.left
        });
        mw.$(mw.wysiwyg.external).html("<iframe scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');

        frame.contentWindow.thisframe = frame;
        if (url.indexOf('#') !== -1) {
            frame.src = '#' + url.split('#')[1]
        }

        mw.wysiwyg.getExternalData(url, function (html) {

            frame.contentWindow.document.open();
            frame.contentWindow.document.write(html);
            frame.contentWindow.document.close();
        })
    },
    createelement: function () {
        var el = mw.wysiwyg.applier('div', 'mw_applier element');
    },
    fontcolorpicker: function () {

        mw.wysiwyg._fontcolorpicker.show();
        setTimeout(function () {
            mw.wysiwyg._fontcolorpicker.show();
        }, 20);
    },
    fontbgcolorpicker: function () {

        setTimeout(function () {
            mw.wysiwyg._bgfontcolorpicker.show();
        }, 20);


    },
    fontColor: function (color) {
        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        var rectarget = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
        rectarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(rectarget, ['element', 'edit']);
        if(mw.liveEditState){
            var currState = mw.liveEditState.state()
            if(currState[0].$id !== 'wysiwyg'){
                mw.liveEditState.record({
                    target: rectarget,
                    value: rectarget.innerHTML,
                    $id: 'wysiwyg'
                });
            }
        }
        if (color == 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "foreColor");
        } else {
            document.execCommand("styleWithCSS", null, true);
            mw.wysiwyg.execCommand('forecolor', null, color);
        }
        mw.liveEditState.record({
            target: rectarget,
            value: rectarget.innerHTML,
        });
    },
    fontbg: function (color) {

        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        if (color === 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "backcolor");
        } else {
            document.execCommand("styleWithCSS", null, true);
            mw.wysiwyg.execCommand('backcolor', null, color);
        }
    },
    request_change_bg_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_bg_color: function (color) {
        color = color !== 'transparent' ? '#' + color : color;
        mw.$(".element-current").css("backgroundColor", color);
        mw.wysiwyg.change('.element-current');
    },
    request_border_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_border_color: function (color) {
        if (color != "transparent") {
            mw.$(".element-current").css(mw.border_which + "Color", "#" + color);
            mw.$(".ed_bordercolor_pick span").css("background", "#" + color);
            mw.wysiwyg.change('.element-current');
        }
        else {
            mw.$(".element-current").css(mw.border_which + "Color", "transparent");
            mw.$(".ed_bordercolor_pick span").css("background", "");
            mw.wysiwyg.change('.element-current');
        }
    },
    request_change_shadow_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_shadow_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_shadow_color: function (color) {
        mw.current_element_styles = getComputedStyle(mw.$('.element-current')[0], null);
        if (mw.current_element_styles.boxShadow != "none") {
            var arr = mw.current_element_styles.boxShadow.split(' ');
            var len = arr.length;
            var x = parseFloat(arr[len - 4]);
            var y = parseFloat(arr[len - 3]);
            var blur = parseFloat(arr[len - 2]);
            mw.$(".element-current").css("box-shadow", x + "px " + y + "px " + blur + "px #" + color);
            mw.$(".ed_shadow_color").dataset("color", color);

        }
        else {
            mw.$(".element-current").css("box-shadow", "0px 0px 6px #" + color);
            mw.$(".ed_shadow_color").dataset("color", color);
        }
        mw.wysiwyg.change('.element-current');
    },
    fontFamily: function (font_name) {
        var range = getSelection().getRangeAt(0);
        document.execCommand("styleWithCSS", null, true);

        var el = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        mw.liveEditState.record({
            target: el.parentNode,
            value: el.parentNode.innerHTML
        });
        if (range.collapsed) {

            mw.wysiwyg.select_all(el);
            document.execCommand('fontName', null, font_name);
            range.collapse()
        }
        else {
            document.execCommand('fontName', null, font_name);
        }

        mw.wysiwyg.change(el)
        mw.liveEditState.record({
            target: el.parentNode,
            value: el.parentNode.innerHTML
        });

    },
    nestingFixes: function (root) {  /*
     var root = root || document.body;
     var all = root.querySelectorAll('.mw-span-font-size'),
     l = all.length,
     i=0;
     for( ; i<l; i++ ){
     var el = all[i];
     if(el.firstChild === el.lastChild && el.firstChild.nodeType !== 3){
     // mw.$(el.firstChild).unwrap();
     }
     } */
    },
    lineHeight: function (a) {
        a = a || 'normal';
        a = (typeof a === 'number') ? (a + 'px') : a;
        var r = getSelection().getRangeAt(0).commonAncestorContainer;
        var el = mw.wysiwyg.validateCommonAncestorContainer(r);
        r.style.fontSize = a;
        mw.wysiwyg.change(r);
    },
    fontSize: function (a) {
        var sel = getSelection()
        var r = sel.getRangeAt(0)
        if (sel.isCollapsed) {
            return false;
        }
        mw.wysiwyg.allStatements(function () {
            var node = r.commonAncestorContainer;
            if(node.nodeType !== 1) {
                node = node.parentElement;
            }
            mw.liveEditState.record({
                target: node,
                value: node.innerHTML
            });
            rangy.init();
            var clstemp = 'mw-font-size-' + mw.random();
            var classApplier = rangy.createCssClassApplier("mw-font-size " + clstemp, true);
            classApplier.applyToSelection();

            var all = document.querySelectorAll('.' + clstemp),
                l = all.length,
                i = 0;
            for ( ; i < l; i++ ) {
                all[i].style.fontSize = a + 'px';
                mw.tools.removeClass(all[i], clstemp);
                mw.wysiwyg.change(all[i]);
            }

            mw.$('.edit .mw-font-size').removeClass('mw-font-size');
            mw.liveEditState.record({
                target: node,
                value: node.innerHTML
            });

        });
    },
    fontSizePrompt: function () {
        var size = prompt("Please enter font size", "");

        if (size != null) {
            var a = parseInt(size);
            if (a > 0) {
                this.fontSize(a);
            }
        }
    },
    resetActiveButtons: function () {
        mw.$('.mw_editor_btn_active').removeClass('mw_editor_btn_active');
    },
    setActiveButtons: function (node) {
        mw.require('css_parser.js');
        var css = mw.CSSParser(node);
        if (css && css.get) {
            var font = css.get.font();
            var fam = font.family.split(',').shift();
            var ddval = mw.$(".mw_dropdown_action_font_family");
            if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                mw.$(".mw_dropdown_action_font_family").each(function () {
                    mw.$(this).setDropdownValue(fam);
                })
            }
        }
    },
    setActiveFontSize: function () {
        mw.require('css_parser.js');

        var sel = getSelection();
        var range = sel.getRangeAt(0);
        if(range.collapsed) {
            var node = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
            var css_node_get=mw.CSSParser(node).get;
            if(typeof(css_node_get) !== 'undefined'){
                var size = Math.round(parseFloat(css_node_get.font().size));
            }
            mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(size + 'px')
        } else {
            var curr = range.startContainer;
            var end = range.endContainer;
            var common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
            var size = Math.round(parseFloat(mw.CSSParser(common).get.font().size));
            while (curr && curr !== end) {
                var node = mw.wysiwyg.validateCommonAncestorContainer(curr);
                curr = curr.nextElementSibling;
                var css_node_get=mw.CSSParser(node).get;
                if(typeof(css_node_get) !== 'undefined'){
                    var sizec = Math.round(parseFloat(css_node_get.font().size));
                    if (sizec !== size) {
                        mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(mw.lang('Size'));
                        return;
                    }
                }
            }
            mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(size + 'px')

        }
    },

    listSplit: function (list, index) {
        if (!list || typeof index == 'undefined') return;
        var curr = list.children[index];
        var listtop = document.createElement(list.nodeName);
        var listbottom = document.createElement(list.nodeName);
        var final = {middle: curr}

        for (var itop = 0; itop < index; itop++) {
            listtop.appendChild(list.children[itop])
        }
        for (var ibot = 1; ibot < list.children.length; ibot++) {
            //for(var ibot = index+1; ibot < list.children.length; ibot++){

            listbottom.appendChild(list.children[ibot])
        }

        if (listtop.children.length > 0) {
            final.top = listtop
        }
        if (listbottom.children.length > 0) {
            final.bottom = listbottom
        }
        return final;

    },
    isFormatElement: function (obj) {
        var items = /^(div|h[1-6]|p)$/i;
        return items.test(obj.nodeName);
    },
    decorators: {
        b: '.mw_editor_bold',
        strong: '.mw_editor_bold',
        i: '.mw_editor_italic',
        em: '.mw_editor_italic',
        u: '.mw_editor_underline',
        s: '.mw_editor_strike',
        strike: '.mw_editor_strike'
    },
    setDecorators: function (sel) {
        sel = sel || getSelection();
        var node = sel.focusNode;
        while (node.nodeName !== 'DIV' && node.nodeName !== 'BODY') {
            for (var x in mw.wysiwyg.decorators) {
                if (node.nodeName.toLowerCase() === x) {
                    mw.$(mw.wysiwyg.decorators[x]).addClass('mw_editor_btn_active')
                }
            }
            node = node.parentNode;
        }
    },
    started_checking: false,
    check_selection: function (target) {
        target = target || false;

        mw.require('css_parser.js');
        var activeSet = false;

        if (!mw.wysiwyg.started_checking) {
            mw.wysiwyg.started_checking = true;

            var selection = window.getSelection();

            if (selection.rangeCount > 0) {
                mw.wysiwyg.resetActiveButtons();
                activeSet = true;
                var range = selection.getRangeAt(0);
                var start = range.startContainer;
                var end = range.endContainer;
                var common = range.commonAncestorContainer;
                var children = range.cloneContents().childNodes, i = 0, l = children.length;

                var list = mw.tools.firstParentWithTag(common, ['ul', 'ol']);
                if (!!list) {
                    mw.$('.mw_editor_' + list.nodeName.toLowerCase()).addClass('mw_editor_btn_active');
                }
                if (common.nodeType !== 3) {
                    var commonCSS = mw.CSSParser(common);
                    var align = commonCSS.get.alignNormalize();
                    mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                    mw.$(".mw-align-" + align).addClass('mw_editor_btn_active');
                    for (; i < l; i++) {
                        if(children[i].nodeName){
                            mw.wysiwyg.setActiveButtons(children[i]);
                        }
                    }

                }
                else {
                    if (typeof common.parentElement !== 'undefined' && common.parentElement !== null) {
                        var align = mw.CSSParser(common.parentElement).get.alignNormalize();
                        mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                        mw.$(".mw-align-" + align).addClass('mw_editor_btn_active');
                        mw.wysiwyg.setActiveButtons(common.parentElement);
                    }
                }
                if (mw.wysiwyg.isFormatElement(common)) {
                    var format = common.nodeName.toLowerCase();
                    var ddval = mw.$(".mw_dropdown_action_format");
                    if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                        mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                    }
                }
                else {
                    mw.tools.foreachParents(common, function (loop) {
                        if (mw.wysiwyg.isFormatElement(this)) {
                            var format = this.nodeName.toLowerCase();
                            var ddval = mw.$(".mw_dropdown_action_format");
                            if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                                mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                            }
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                mw.wysiwyg.setActiveFontSize();
                mw.wysiwyg.setDecorators(selection)
            }

            if (!!target && target.nodeName) {

                if(!activeSet) mw.wysiwyg.setActiveButtons(target);
                if (target.tagName === 'A') {
                    mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
                var parent_a = mw.tools.firstParentWithTag(target, 'a');
                if (!!parent_a) {
                    mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
            }
            mw.wysiwyg.started_checking = false;
        }
    },
    link: function (url, node_id, text) {
        mw.require('external_callbacks.js');
        mw.wysiwyg.save_selection();

        var el = node_id ? document.getElementById(node_id) : mw.tools.firstParentWithTag(getSelection().focusNode, 'a');
        var val;
        var sel = getSelection();

        if(el) {
            val = {
                url: url || el.href,
                text: text || el.innerHTML,
                target: el.target === '_blank'
            }

        } else if(!sel.isCollapsed) {
            var html = document.createElement('div');
            if(sel.rangeCount) {
                var frag = sel.getRangeAt(0).cloneContents();
                while (frag.firstChild) {
                    html.append(frag.firstChild);
                }
            }
            val = {
                text: text || html.innerHTML,
                url: url || ''
            }
        }

        new mw.LinkEditor({
            mode: 'dialog'
        })
            .setValue(val)
            .promise()
            .then(function (result){
                mw.wysiwyg.restore_selection();
                console.log(result)
                mw.iframecallbacks.insert_link(result, (result.target ? '_blank' : '_self') , result.text);
                var fc = mw.top().win.getSelection().focusNode;
                if(fc.querySelector) {
                    Array.from(fc.querySelectorAll('a')).forEach(function(link) {
                        //if(mw.tools.isEditable(link)) {
                        link.id = mw.id('mw-link-');
                        //}
                    })
                }
                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(mw.tools.firstParentOrCurrentWithClass(fc.parentElement, 'edit'))
                }

            });

    },

    unlink: function () {
        var sel = window.getSelection();
        if (!sel.isCollapsed) {
            mw.wysiwyg.execCommand('unlink', null, null);
        }
        else {
            var link = mw.wysiwyg.findTagAcrossSelection('a');
            if (!!link) {
                mw.wysiwyg.select_element(link);
                mw.wysiwyg.execCommand('unlink', null, null);
            }
        }
        mw.$(".mw_editor_link").removeClass("mw_editor_btn_active");
    },
    findTagAcrossSelection: function (tag, selection) {
        var selection = selection || window.getSelection();
        if (selection.anchorNode.nodeName.toLowerCase() === tag) {
            return selection.anchorNode;
        }
        var range = selection.getRangeAt(0);
        var common = range.commonAncestorContainer;
        var parent = mw.tools.firstParentWithTag(common, [tag]);
        if (!!parent) {
            return parent;
        }
        if (typeof common.querySelectorAll !== 'undefined') {
            var items = common.querySelectorAll(tag), l = items.length, i = 0, arr = [];
            if (l > 0) {
                for (; i < l; i++) {
                    if (selection.containsNode(items[i], true)) {
                        arr.push(items[i])
                    }
                }
                if (arr.length > 0) {
                    return arr.length === 1 ? arr[0] : arr;
                }
            }
        }
        return false;
    },
    image_link: function (url) {
        mw.$("img.element-current").wrap("<a href='" + url + "'></a>");
        mw.wysiwyg.change('.element-current');
    },
    request_media: function (hash, types) {
        mw.require('external_callbacks.js');
        types = types || false;
        if (hash === '#editimage') {
            types = 'images';
            //hash = 'noop';
        }
        var url = !!types ? "rte_image_editor?types=" + types + '' + hash : "rte_image_editor" + hash;

        url = mw.settings.site_url + 'editor_tools/' + url;
        var sel = mw.wysiwyg.save_selection();
        var modal = mw.top().dialogIframe({
            url: url,
            name: "mw_rte_image",
            width: 460,
            height: 'auto',
            autoHeight:true,
            overlay: true
        }, function(res) {
            if(hash === '#set_bg_image'){
                mw.wysiwyg.set_bg_image(res);
                return;
            }

            mw.wysiwyg.restore_selection();
            mw.require("files.js");

            if(hash === '#editimage') {
                if(mw.image.currentResizing) {
                    if (mw.image.currentResizing[0].nodeName === 'IMG') {
                        mw.image.currentResizing.attr("src", res);
                        mw.image.currentResizing.css('height', 'auto');
                    }
                    else {
                        mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(res) + ')');
                        if(mw.parent().image.currentResizing) {
                            mw.wysiwyg.bgQuotesFix(mw.parent().image.currentResizing[0])
                        }
                    }
                    if(mw.image.currentResizing) {
                        mw.wysiwyg.change(mw.image.currentResizing[0]);
                    }
                    mw.image.currentResizing.load(function () {
                        mw.image.resize.resizerSet(this);
                    });
                }
            }
            else {
                if(res.indexOf('<') !== -1) {
                    mw.wysiwyg.insert_html(res);
                } else {
                    mw.wysiwyg.insertMedia(res);
                }
            }

            this.remove();

        });
    },
    media: function (action) {

        if (mw.settings.liveEdit && typeof mw.target.item === 'undefined') return false;
        action = action || 'insert_html';
        action = action.replace(/#/g, '');

        if (mw.wysiwyg.isSelectionEditable() || mw.$(mw.target.item).hasClass("image_change") || mw.$(mw.target.item.parentNode).hasClass("image_change") || mw.target.item === mw.image_resizer) {
            mw.wysiwyg.save_selection();
            var dialog;
            var handleResult = function (res) {
                mw.wysiwyg.restore_selection();
                var url;
                if(Array.isArray(res)) {
                    url = res[0]
                } else {
                    url = res.src ? res.src : res;
                }


                if(action === 'editimage') {
                    if(mw.image.currentResizing) {
                        if (mw.image.currentResizing[0].nodeName === 'IMG') {
                            mw.image.currentResizing.attr("src", url);
                            mw.image.currentResizing.css('height', 'auto');
                        }
                        else {
                            mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(url) + ')');
                            if(mw.parent().image.currentResizing) {
                                mw.wysiwyg.bgQuotesFix(mw.parent().image.currentResizing[0])
                            }
                        }
                        if(mw.image.currentResizing) {
                            mw.wysiwyg.change(mw.image.currentResizing[0]);
                        }
                        mw.image.currentResizing.load(function () {
                            mw.image.resize.resizerSet(this);
                        });
                    }
                }
                else {

                    mw.wysiwyg.insertMedia(url);
                }
                dialog.remove()
            }


            var picker = new mw.filePicker({
                type: 'images',
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                fileUploaded: function (file) {
                    handleResult(file.src);
                    dialog.remove()
                },
                onResult: handleResult,
                cancel: function () {
                    dialog.remove()
                }
            });
            dialog = mw.dialog({
                content: picker.root,
                title: mw.lang('Select image'),
                footer: false,
                width: 1200
            })


        }

    },
    request_bg_image: function () {
        mw.wysiwyg.request_media('#set_bg_image');
    },
    set_bg_image: function (url) {
        mw.$(".element-current").css("backgroundImage", "url(" + url + ")");
        mw.wysiwyg.change('.element-current');
    },
    insert_html: function (html) {
        var isembed;
        if (typeof html === 'string') {
            isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
        }
        else {
            isembed = false;
        }
        if (isembed) {
            var id = 'frame-' + mw.random();
            var frame = html;
            html = '<span id="' + id + '"></span>';
        }

        mw.wysiwyg.execCommand('insertHTML', false, html);

        if (isembed) {
            el = document.getElementById(id);
            mw.wysiwyg.contentEditable(el.parentNode, false);
            mw.$(el).replaceWith(frame);
        }
        var sel = getSelection();
        if(sel.rangeCount){
            mw.wysiwyg.change(mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer));

        }
    },
    selection_length: function () {
        var n = window.getSelection().getRangeAt(0).cloneContents().childNodes,
            l = n.length,
            i = 0;
        var final = 0;
        for (; i < l; i++) {
            var item = n[i];
            if (item.nodeType === 1) {
                final = final + item.textContent.length;
            }
            else if (item.nodeType === 3) {
                final = final + item.nodeValue.length;
            }
        }
        return final;
    },
    insertMedia: function (url, type) {
        console.log(url, type)
        var ext = url.split('.').pop().toLowerCase().split('?')[0];
        var name = url.split('/').pop().split('?')[0]
        if(!type) {
            if(['png','gif','jpg','jpeg','tiff','bmp','svg', 'webp'].indexOf(ext) !== -1) {
                type = 'image';
            }
            if(['mp4','ogg'].indexOf(ext) !== -1) {
                type = 'video';
            }
        }
        if(type === 'image') {
            return this.insert_image(url);
        } else if(type === 'video') {
            var id = 'image_' + mw.random();
            var img = '<span class="mwembed"><video id="' + id + '" contentEditable="false" src="' + url + '" controls></video></span>';
            mw.wysiwyg.insert_html(img);
        } else {
            var id = 'image_' + mw.random();
            var img = '<a id="' + id + '" contentEditable="true" src="' + url + '">'+name+'</a>';
            mw.wysiwyg.insert_html(img);
        }
    },
    insert_image: function (url) {

        var id = 'image_' + mw.random();
        var img = '<img id="' + id + '" contentEditable="true" class="element" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        mw.settings.liveEdit ? mw.$("#" + id).attr("contenteditable", false) : '';
        mw.$("#" + id).removeAttr("_moz_dirty");
        mw.wysiwyg.change(document.getElementById(id));
        return id;
    },
    save_selection: function () {
        var selection = window.getSelection();
        if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);
        }
        else {
            var range = document.createRange();
            range.selectNode(document.querySelector('.edit .element'));
        }
        mw.wysiwyg.selection = {};
        mw.wysiwyg.selection.sel = selection;
        mw.wysiwyg.selection.range = range;
        mw.wysiwyg.selection.element = mw.$(mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer));
    },
    restore_selection: function () {
        if (!!mw.wysiwyg.selection) {
            mw.wysiwyg.selection.element.attr("contenteditable", "true");
            mw.wysiwyg.selection.element.focus();
            mw.wysiwyg.selection.sel.removeAllRanges();
            mw.wysiwyg.selection.sel.addRange(mw.wysiwyg.selection.range)
        }
    },
    select_all: function (el) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    },
    select_element: function (el) {
        var range = document.createRange();
        try {
            range.selectNode(el);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        } catch (e) {

        }
    },
    formatNative: function (command) {
        var el = mw.wysiwyg.validateCommonAncestorContainer(window.getSelection().focusNode);
        if (mw.wysiwyg.isSafeMode()) {
            mw.$('[contenteditable]').removeAttr('contenteditable');
            var parent = mw.tools.firstBlockLevel(el.parentNode);
            parent.contentEditable = true;
        }
        mw.wysiwyg.execCommand('formatBlock', false, '<' + command + '>');
        mw.wysiwyg.execCommand('formatBlock', false, command );
    },
    format: function (command) {
        var target = mw.wysiwyg.validateCommonAncestorContainer(getSelection().getRangeAt(0).commonAncestorContainer);
        mw.liveEditState.record({
            target: target.parentNode,
            value: target.parentNode.innerHTML
        });
        var el = mw.tools.setTag(target, command);
        mw.wysiwyg.cursorToElement(el, 'end');
        mw.liveEditState.record({
            target: el.parentNode,
            value: el.parentNode.innerHTML
        });
        // return this.formatNative(command);
    },
    fontFamilies: ['Arial', 'Tahoma', 'Verdana', 'Georgia', 'Times New Roman'],
    fontFamiliesExtended: [],
    fontFamiliesTemplate: [],
    initFontSelectorBox: function () {
        mw.wysiwyg.initFontFamilies();

        var l = mw.wysiwyg.fontFamilies.length, i = 0, html = '';
        for (; i < l; i++) {

            html += '<li value="' + mw.wysiwyg.fontFamilies[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamilies[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamilies[i] + '</a></li>'
        }

        var l = mw.wysiwyg.fontFamiliesTemplate.length, i = 0;
        for (; i < l; i++) {
            if (mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesTemplate[i]) === -1 && mw.wysiwyg.fontFamiliesTemplate[i] != '') {
                html += '<li value="' + mw.wysiwyg.fontFamiliesTemplate[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamiliesTemplate[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamiliesTemplate[i] + '</a></li>'
            }
        }
        var l = mw.wysiwyg.fontFamiliesExtended.length, i = 0;
        for (; i < l; i++) {
            if (mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesExtended[i]) === -1 && mw.wysiwyg.fontFamiliesExtended[i] != '') {
                html += '<li value="' + mw.wysiwyg.fontFamiliesExtended[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamiliesExtended[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamiliesExtended[i] + '</a></li>'
            }
        }

        mw.$(".mw_dropdown_action_font_family ul").empty().append(html);

        mw.$(".mw_dropdown_action_font_family").off('change');
        mw.$(".mw_dropdown_action_font_family").on('change', function () {
            var val = mw.$(this).getDropdownValue();
            mw.wysiwyg.fontFamily(val);
        });
        mw.$(".mw_dropdown_action_font_family").each(function () {
            mw.$("[value]", mw.$(this)).on('mousedown touchstart', function (event) {
                mw.$(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                return false;
            });
        });
    },

    initFontFamilies: function () {
        if (window.getComputedStyle(document.body) == null) {
            return;
        }

        var body_font = window.getComputedStyle(document.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
        if (mw.wysiwyg.fontFamilies.indexOf(body_font) === -1) {
            mw.wysiwyg.fontFamilies.push(body_font);
        }

        var scan_for_fonts = ['body', 'html', 'h1', 'h2', 'h3', 'h4', 'h5', 'p', 'a[class]'];

        $.each(scan_for_fonts, function (index, value) {
            var sel = mw.$(document.querySelector(value));
            if (sel.length > 0) {
                var body_font = window.getComputedStyle(sel[0], null).fontFamily.split(',');
                $.each(body_font, function (font_index, fvalue) {
                    var font_value = fvalue;
                    font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
                    if (mw.wysiwyg.fontFamilies.indexOf(font_value) === -1) {
                        mw.wysiwyg.fontFamilies.push(font_value);
                    }
                });
            }
        });
    },
    initExtendedFontFamilies: function (string) {
        var families = [];
        if (typeof(string) == 'string') {
            families = string.split(',')
        } else if (typeof(string) == 'object') {
            families = string
        }
        $.each(families, function (font_index, fvalue) {
            var font_value = fvalue;
            font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
            if (mw.wysiwyg.fontFamilies.indexOf(font_value) === -1 && mw.wysiwyg.fontFamiliesExtended.indexOf(font_value) === -1) {
                mw.wysiwyg.fontFamiliesExtended.push(font_value);
            }
        });
    },
    fontIconFamilies: ['fas', 'fab', 'far', 'fa', 'mw-ui-icon', 'mw-icon', 'material-icons', 'mw-wysiwyg-custom-icon', 'icon', 'mdi'],

    elementHasFontIconClass: function (el) {
        var icon_classes = mw.wysiwyg.fontIconFamilies;
        if (el.tagName === 'I' || el.tagName === 'SPAN') {
            if (mw.tools.hasAnyOfClasses(el, icon_classes)) {
                return true;
            }
            else if (el.className.indexOf('mw-micon-') !== -1) {
                return true;
            }
            else if (el.className.indexOf('mw-icon-') !== -1) {
                return true;
            }
            else {
                return mw.tools.firstParentOrCurrentWithAnyOfClasses(el.parentNode, icon_classes);
            }
        }
    },
    firstElementThatHasFontIconClass: function (el) {
        var icon_classes = mw.wysiwyg.fontIconFamilies.map(function (value) {
            return '.'+value
        });
        icon_classes.push('[class*="mw-micon-"]');
        var p = mw.tools.firstMatchesOnNodeOrParent(el, icon_classes);
        if (p && (p.tagName === 'I' || p.tagName === 'SPAN')) {
            return p;
        }
    },
    elementRemoveFontIconClasses: function (el) {
        var l = mw.wysiwyg.fontIconFamilies.length, i = 0;
        for (; i < l; i++) {
            var search_class = mw.wysiwyg.fontIconFamilies[i]
            mw.tools.classNamespaceDelete(el, search_class + '-');
        }
    },
    iframe_editor: function (textarea, iframe_url, content_to_set) {
        var content_to_set = content_to_set || false;
        var id = mw.$(textarea).attr("id");
        mw.$("#iframe_editor_" + id).remove();
        var url = iframe_url;
        var iframe = document.createElement('iframe');
        iframe.className = 'mw-editor-iframe-loading';
        iframe.id = "iframe_editor_" + id;
        iframe.width = mw.$(textarea).width();
        iframe.height = mw.$(textarea).height();
        iframe.scrolling = "no";
        iframe.setAttribute('frameborder', 0);
        iframe.src = url;
        iframe.style.resize = 'vertical';
        iframe.onload = function () {
            iframe.className = 'mw-editor-iframe-loaded';
            var b = mw.$(this).contents().find(".edit");
            var b = mw.$(this).contents().find("[field='content']")[0];
            if (typeof b != 'undefined' && b !== null) {
                mw.wysiwyg.contentEditable(b, true)
                mw.$(b).on("blur keyup", function () {
                    textarea.value = mw.$(this).html();
                });
                if (!!content_to_set) {
                    mw.$(b).html(content_to_set);
                }
                mw.on.DOMChange(b, function () {
                    textarea.value = mw.$(this).html();
                    mw.askusertostay = true;
                });
            }
        }
        mw.$(textarea).after(iframe);
        mw.$(textarea).hide();
        return iframe;
    },
    word_listitem_get_level: function (item) {
        var msspl = item.getAttribute('style').split('mso-list');
        if (msspl.length > 1) {
            var mssplitems = msspl[1].split(' ');
            for (var i = 0; i < mssplitems.length; i++) {
                if (mssplitems[i].indexOf('level') !== -1) {
                    return parseInt(mssplitems[i].split('level')[1], 10);
                }
            }
        }
    },

    word_list_build: function (lists, count) {
        var i, check = false, max = 0;
        count = count || 0;
        if (count === 0) {
            for (i in lists) {
                var curr = lists[i];
                if (!curr.nodeName || curr.nodeType !== 1) continue;
                var $curr = mw.$(curr);
                lists[i] = mw.tools.setTag(curr, 'li');
            }
        }

        lists.each(function () {
            var num = this.textContent.trim().split('.')[0], check = parseInt(num, 10);
            var curr = mw.$(this);
            if (!curr.attr('data-type')) {
                if (!isNaN(check) && num > 0) {
                    this.innerHTML = this.innerHTML.replace(num + '.', '');
                    curr.attr('data-type', 'ol');
                }
                else {
                    curr.attr('data-type', 'ul');
                }
            }
            if (!this.__done) {
                this.__done = false;
                var level = parseInt($(this).attr('data-level'));
                if (!isNaN(level) && level > max) {
                    max = level;
                }
                if (!isNaN(level) && level > 1) {
                    var prev = this.previousElementSibling;
                    if (!!prev && prev.nodeName == 'LI') {
                        var type = this.getAttribute('data-type');
                        var wrap = document.createElement(type == 'ul' ? 'ul' : 'ol');
                        wrap.setAttribute('data-level', level)
                        mw.$(wrap).append(this);
                        mw.$(wrap).appendTo(prev);
                        check = true;
                    }
                    else if (!!prev && (prev.nodeName == 'UL' || prev.nodeName == 'OL')) {
                        var where = mw.$('li[data-level="' + level + '"]', prev);
                        if (where.length > 0) {
                            where.after(this);
                            check = true;
                        }
                        else {
                            var type = this.getAttribute('data-type');
                            var wrap = document.createElement(type == 'ul' ? 'ul' : 'ol');
                            wrap.setAttribute('data-level', level)
                            mw.$(wrap).append(this);
                            mw.$(wrap).appendTo($('li:last', prev))
                            check = true;
                        }
                    }
                    else if (!prev && (this.parentNode.nodeName != 'UL' && this.parentNode.nodeName != 'OL')) {
                        var $curr = mw.$([this]), curr = this;
                        while ($(curr).next('li[data-level="' + level + '"]').length > 0) {
                            $curr.push($(curr).next('li[data-level="' + level + '"]')[0]);
                            curr = mw.$(curr).next('li[data-level="' + level + '"]')[0];
                        }
                        $curr.wrapAll($curr.eq(0).attr('data-type') == 'ul' ? '<ul></ul>' : '<ol></ol>')
                        check = true;
                    }
                }
            }
        })

        mw.$("ul[data-level!='1'], ol[data-level!='1']").each(function () {
            var level = parseInt($(this).attr('data-level'));
            if (!!this.previousElementSibling) {
                var plevel = parseInt($(this.previousElementSibling).attr('data-level'));
                if (level > plevel) {
                    mw.$('li:last', this.previousElementSibling).append(this)
                    check = true;
                }
            }
        })
        if (count === 0) {
            setTimeout(function () {
                mw.wysiwyg.word_list_build($('li[data-level]'), 1);
                mw.wysiwyg.wrap_li_roots()
            }, 1)
        }
        return lists;
    },
    wrap_li_roots: function () {
        var all = document.querySelectorAll('li[data-level]'), i = 0, has = false;
        for (; i < all.length; i++) {
            var parent = all[i].parentElement.nodeName;
            if (parent != 'OL' && parent != 'UL') {
                has = true;
                var group = mw.$([]), curr = all[i];
                while (!!curr && curr.nodeName == 'LI') {
                    group.push(curr);
                    curr = curr.nextElementSibling;
                }
                var el = document.createElement(all[i].getAttribute('data-type') == 'ul' ? 'ul' : 'ol');
                el.className = 'element';
                group.wrapAll(el)
                break;
            }
        }
        if (has) return mw.wysiwyg.wrap_li_roots()
    },
    isWordHtml: function (html) {
        return html.indexOf('urn:schemas-microsoft-com:office:word') !== -1;
    },
    bgQuotesFix: function (el) {
        el = mw.$(el)[0];
        if (!!el && el.nodeType === 1) {
            var first = el.outerHTML.split('>')[0];
            if (el.style.backgroundImage.indexOf('"') !== -1 && first.indexOf('style="') !== -1) {
                el.attributes.style.nodeValue = el.attributes.style.nodeValue.replace(/\"/g, "'")
            }
        }
    },
    clean_word_list: function (html) {

        if (!mw.wysiwyg.isWordHtml(html)) return html;
        if (html.indexOf('</body>') != -1) {
            html = html.split('</body>')[0]
        }
        var parser = mw.tools.parseHtml(html).body;

        var lists = mw.$('[style*="mso-list:"]', parser);
        lists.each(function () {
            var level = mw.wysiwyg.word_listitem_get_level(this);
            if (!!level) {
                this.setAttribute('data-level', level)
                this.setAttribute('class', 'level-' + level)
            }

        });

        mw.$('[style]', parser).removeAttr('style');

        if (lists.length > 0) {
            lists = mw.wysiwyg.word_list_build(lists);
            var start = mw.$([]);
            mw.$('li', parser).each(function () {
                this.innerHTML = this.innerHTML
                    .replace(/�/g, '')/* Not a dot */
                    .replace(new RegExp(String.fromCharCode(160), "g"), "")
                    .replace(/&nbsp;/gi, '')
                    .replace(/\�/g, '')
                    .replace(/<\/?span[^>]*>/g, "")
                    .replace('�', '');
            });
        }
        return parser.innerHTML;
    },
    clean_word: function (html) {
        html = mw.wysiwyg.clean_word_list(html);
        html = html.replace(/<td([^>]*)>/gi, '<td>');
        html = html.replace(/<table([^>]*)>/gi, '<table cellspacing="0" cellpadding="0" border="1" style="width:100%;" width="100%" class="element">');
        html = html.replace(/<o:p>\s*<\/o:p>/g, '');
        html = html.replace(/<o:p>[\s\S]*?<\/o:p>/g, '&nbsp;');
        html = html.replace(/\s*mso-[^:]+:[^;"]+;?/gi, '');
        html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '');
        html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"");
        html = html.replace(/\s*TEXT-INDENT: 0cm\s*;/gi, '');
        html = html.replace(/\s*TEXT-INDENT: 0cm\s*"/gi, "\"");
        html = html.replace(/\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"");
        html = html.replace(/\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"");
        html = html.replace(/\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"");
        html = html.replace(/\s*tab-stops:[^;"]*;?/gi, '');
        html = html.replace(/\s*tab-stops:[^"]*/gi, '');
        html = html.replace(/\s*face="[^"]*"/gi, '');
        html = html.replace(/\s*face=[^ >]*/gi, '');
        html = html.replace(/\s*FONT-FAMILY:[^;"]*;?/gi, '');
        html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3");
        html = html.replace(/<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi, '');
        html = html.replace(/<(?:META|LINK)[^>]*>\s*/gi, '');
        html = html.replace(/\s*style="\s*"/gi, '');
        html = html.replace(/<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;');
        html = html.replace(/<SPAN\s*[^>]*><\/SPAN>/gi, '');
        html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3");
        html = html.replace(/<SPAN\s*>([\s\S]*?)<\/SPAN>/gi, '$1');
        html = html.replace(/<FONT\s*>([\s\S]*?)<\/FONT>/gi, '$1');
        html = html.replace(/<\\?\?xml[^>]*>/gi, '');
        html = html.replace(/<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi, '');
        html = html.replace(/<\/?\w+:[^>]*>/gi, '');
        html = html.replace(/<\!--[\s\S]*?-->/g, '');
        html = html.replace(/<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;');
        html = html.replace(/<H\d>\s*<\/H\d>/gi, '');
        html = html.replace(/<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/ig, '');
        html = html.replace(/<(\w[^>]*) language=([^ |>]*)([^>]*)/gi, "<$1$3");
        html = html.replace(/<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi, "<$1$3");
        html = html.replace(/<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi, "<$1$3");
        html = html.replace(/<H(\d)([^>]*)>/gi, '<h$1>');
        html = html.replace(/<font size=2>(.*)<\/font>/gi, '$1');
        html = html.replace(/<font size=3>(.*)<\/font>/gi, '$1');
        html = html.replace(/<a name=.*>(.*)<\/a>/gi, '$1');
        html = html.replace(/<H1([^>]*)>/gi, '<H2$1>');
        html = html.replace(/<\/H1\d>/gi, '<\/H2>');
        //html = html.replace(/<span>/gi, '$1');
        html = html.replace(/<\/span\d>/gi, '');
        html = html.replace(/<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>');
        html = html.replace(/<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>');
        return html;
    },
    cleanTables: function (root) {
        var toRemove = "tbody > *:not(tr), thead > *:not(tr), tr > *:not(td)",
            all = root.querySelectorAll(toRemove),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            mw.$(all[i]).remove();
        }
        var tables = root.querySelectorAll('table'),
            l = tables.length,
            i = 0;
        for (; i < l; i++) {
            var item = tables[i],
                l = item.children.length,
                i = 0;
            for (; i < l; i++) {
                var item = item.children[i];
                if (typeof item !== 'undefined' && item.nodeType !== 3) {
                    var name = item.nodeName.toLowerCase();
                    var posibles = "thead tfoot tr tbody col colgroup";
                    if (!posibles.contains(name)) {
                        mw.$(item).remove();
                    }
                }
            }
        }
    },
    cleanHTML: function (root) {
        var root = root || document.body;
        mw.tools.foreachChildren(root, function () {
            if (mw.wysiwyg.hasContentFromWord(this)) {
                this.innerHTML = mw.wysiwyg.clean_word(this.innerHTML);
            }
            mw.wysiwyg.cleanTables(this);
        });
    },
    normalizeBase64Image: function (node, callback) {
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

                mw.wysiwyg.change(node);

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


                mw.wysiwyg.change(node);
                if (typeof callback === 'function') {
                    callback.call(node);
                }
            });
        }
    },
    normalizeBase64Images: function (root, callback) {
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
    },
    documentCommonFonts: function () {
        var checkNodes = $('html, body, h1:first, h2:first, p:first');
        var result = [];
        checkNodes.each(function () {
            var font = $(this).css('fontFamily').split(',')[0].trim();
            if(result.indexOf(font) === -1) {
                result.push(font)
            }
        });
        return result;
    }
}


mw.wysiwyg.dropdowns = function () {
    mw.$(".mw_dropdown_action_font_size").not('.ready').addClass('ready').change(function () {
        var val = mw.$(this).getDropdownValue();
        mw.wysiwyg.fontSize(val);
        mw.$('.mw-dropdown-val', this).append('px');
    });
    mw.$(".mw_dropdown_action_format").not('.ready').addClass('ready').change(function () {

        var val = mw.$(this).getDropdownValue();
        mw.wysiwyg.format(val);
    });
    mw.wysiwyg.initFontSelectorBox();
    mw.$("#wysiwyg_insert").not('.ready').addClass('ready').on("change", function () {
        var fnode = window.getSelection().focusNode;
        var isPlain = mw.tools.firstParentOrCurrentWithClass(fnode, 'plain-text');
        if (mw.wysiwyg.isSelectionEditable()) {
            var val = mw.$(this).getDropdownValue();

            var isTextlike = val === 'icon';
            if (!isTextlike && isPlain) {
                return false;
            }

            if (val === 'hr') {
                mw.wysiwyg._do('InsertHorizontalRule');
            }
            else if (val === 'box') {

                var div = mw.wysiwyg.applier('div', 'mw-ui-box mw-ui-box-content element');
                if (mw.wysiwyg.selection_length() <= 2) {
                    mw.$(div).append("<p>&nbsp;</p>");
                }
            }
            else if (val == 'pre') {
                var div = mw.wysiwyg.applier('pre', '');
                if (mw.wysiwyg.selection_length() <= 2) {
                    mw.$(div).append("&nbsp;");
                }
            } else if (val === 'code') {
                // var div = mw.wysiwyg.applier('code', '');
                var new_insert_html = prompt("Paste your code");
                if (new_insert_html != null) {
                    var div = mw.wysiwyg.applier('code');
                    div.innerHTML = new_insert_html;
                }
            } else if (val === 'insert_html') {
                var new_insert_html = prompt("Paste your html code in the box");
                if (new_insert_html != null) {

                    mw.wysiwyg.insert_html(new_insert_html)
                }
            } else if (val === 'icon') {

                var icdiv = mw.wysiwyg.applier('i');
                icdiv.className = "mw-icon";

                var mode = 3;
                if(mode === 3) {
                    mw.editorIconPicker.tooltip(icdiv)
                }
                if(mode === 2) {
                    var dialog = mw.icons.dialog();
                    $(dialog).on('Result', function(e, res){
                        res.render(res.icon, icdiv);
                        dialog.remove();
                    })
                }
                if(mode === 1) {

                    mw.editorIconPicker.tooltip(icdiv)

                    setTimeout(function () {
                        mw.sidebarSettingsTabs.set(2)
                    }, 10);
                }



            }
            else if (val === 'table') {
                var el = mw.wysiwyg.applier('div', 'element', {width: "100%"});
                el.innerHTML = '<table class="mw-wysiwyg-table"><tbody><tr><td>Lorem Ipsum</td><td  >Lorem Ipsum</td></tr><tr><td  >Lorem Ipsum</td><td  >Lorem Ipsum</td></tr></tbody></table>';

            }
            else if (val === 'quote') {
                var div = mw.wysiwyg.applier('blockquote', 'element');
                mw.$(div).append("<cite>By Lorem Ipsum</cite>");
            }
            //  mw.$(this).setDropdownValue("Insert", true, true, "Insert");
        }
        mw.$(this).find('.mw-dropdown-val').html('insert').find('.mw-dropdown-content').hide()
        mw.$(this).find('.mw-dropdown-content').hide()
    })
};
$(window).on('load', function () {
    mw.editorIconPicker = mw.iconPicker({
        iconOptions: { reset: true }
    });


    mw.editorIconPicker.on('select', function (data){
        data.render();
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });
    mw.editorIconPicker.on('sizeChange', function (size){
        // mw.editorIconPicker.target.style.fontSize = size + 'px';
        mw.editorIconPicker.target.style.setProperty('font-size', size + 'px', 'important');
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');
        mw.wysiwyg.change(mw.editorIconPicker.target)
    })
    mw.editorIconPicker.on('colorChange', function (color){
        // mw.editorIconPicker.target.style.color = color;
        mw.editorIconPicker.target.style.setProperty('color', color, 'important');
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });

    mw.editorIconPicker.on('reset', function (color){
        mw.editorIconPicker.target.style.color = '';
        mw.editorIconPicker.target.style.fontSize = '';
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');

        mw.wysiwyg.change(mw.editorIconPicker.target)
        mw.editorIconPicker.tooltip(mw.editorIconPicker.target)
    });
})
$(mwd).ready(function () {


    mw.wysiwyg.initClassApplier();

    mw.wysiwyg.dropdowns();




    if (!mw.wysiwyg._fontcolorpicker) {
        mw.lib.require('colorpicker');
        mw.wysiwyg._fontcolorpicker = mw.colorPicker({
            element: document.querySelector('#mw_editor_font_color'),
            tip: true,
            showHEX: false,
            onchange: function (color) {
                mw.wysiwyg.fontColor(color)
            }
        });
    }
    if (!mw.wysiwyg._bgfontcolorpicker) {
        mw.wysiwyg._bgfontcolorpicker = mw.colorPicker({
            element: document.querySelector('.mw_editor_font_background_color'),
            tip: true,
            showHEX: false,
            onchange: function (color) {
                mw.wysiwyg.fontbg(color)
            }
        });
    }



    mw.$(document).on('scroll', function () {
        if (mw.wysiwyg._bgfontcolorpicker && mw.wysiwyg._bgfontcolorpicker.settings) {
            mw.tools.tooltip.setPosition(mw.wysiwyg._bgfontcolorpicker.tip, mw.wysiwyg._bgfontcolorpicker.settings.element, mw.wysiwyg._bgfontcolorpicker.settings.position)
            mw.tools.tooltip.setPosition(mw.wysiwyg._fontcolorpicker.tip, mw.wysiwyg._fontcolorpicker.settings.element, mw.wysiwyg._fontcolorpicker.settings.position)
        }

    })


    mw.wysiwyg.nceui();
    mw.smallEditor = mw.$("#mw_small_editor");
    mw.smallEditorCanceled = true;
    mw.bigEditor = mw.$("#mw-text-editor");
    mw.$(document.body).on('mousedown touchstart', function (event) {
        var target = event.target;
        if ($(target).hasClass("element")) {
            mw.trigger("ElementMouseDown", target);
        }
        else if ($(target).parents(".element").length > 0) {
            mw.trigger("ElementMouseDown", mw.$(target).parents(".element")[0]);
        }
        if ($(target).hasClass("edit")) {
            mw.trigger("EditMouseDown", [target, target, event]);
        }
        else if ($(target).parents(".edit").length > 0) {
            mw.trigger("EditMouseDown", [$(target).parents(".edit")[0], target, event]);
        }
        var hp = document.getElementById('mw-history-panel');
        if (hp !== null && hp.style.display != 'none') {
            if (!hp.contains(target)) {
                hp.style.display = 'none';
                mw.$("#history_panel_toggle").removeClass('mw_editor_btn_active');
            }
        }
    });

    mw.wysiwyg.editorFonts = [];


});
$(window).on('load', function () {

    mw.$(this).on('imageSrcChanged', function (e, el, url) {
        mw.require("files.js");

        var node = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['mw-image-holder']);
        if (node) {
            url = mw.files.safeFilename(url);
            var img = node.querySelector('img');
            if(img) {
                img.src = url;
            }
            mw.$(node).css('backgroundImage', 'url(' + url + ')');
        }
    });

    mw.$(window).on("keydown", function (e) {

        if (e.type === 'keydown') {

            if (e.keyCode === 13) {
                var field = mw.tools.mwattr(e.target, 'field');
                if (field === 'title' || mw.tools.hasClass(e.target, 'plain-text')) {
                    e.preventDefault();
                }
            }
            if (e.ctrlKey) {
                var isPlain = mw.tools.firstParentOrCurrentWithClass(e.target, 'plain-text');
                if (!isPlain) {
                    var code = e.keyCode;
                    if (code === 66) {

                        mw.wysiwyg.execCommand('bold');
                        e.preventDefault();
                    }
                    else if (code == 73) {

                        mw.wysiwyg.execCommand('italic');
                        e.preventDefault();
                    }
                    else if (code == 85) {

                        mw.wysiwyg.execCommand('underline');
                        e.preventDefault();
                    }
                }
                else {
                    if (e.keyCode != 65 && e.keyCode != 86) { // ctrl v || a
                        //return false;
                    }

                }
            }
        }
    });
    mw.$(".mw_editor").each(function () {
        mw.tools.dropdown(this);
    });
    var nodes = document.querySelectorAll(".edit"), l = nodes.length, i = 0;
    for (; i < l; i++) {
        var node = nodes[i];
        var rel = mw.tools.mwattr(node, "rel");
        var field = mw.tools.mwattr(node, "field");
        if (field == 'content' && rel == 'content') {
            if (node.querySelector('p') !== null) {
                var node = node.querySelector('p');
            }
            // node.contentEditable = true;
        }
        if (!nodes[i].pasteBinded && !mw.tools.hasParentsWithClass(nodes[i], 'edit')) {
            nodes[i].pasteBinded = true;
            nodes[i].addEventListener("paste", function (e) {

                mw.wysiwyg.paste(e);
                mw.wysiwyg.change(e.target)
            });
        }

    }
    mw.require('wysiwygmdab.js');
});

mw.linkTip = {
    init: function (root) {
        if (root === null || !root) {
            return false;
        }
        mw.$(root).on('click', function (e) {
            var node = mw.linkTip.find(e.target);
            if (!!node) {
                mw.linkTip.tip(node);
                e.preventDefault()
            }
            else {
                mw.$('.mw-link-tip').remove();
            }
        });
    },
    find: function (target) {
        if (mw.tools.hasClass(target, 'module')) {
            return;
        }
        var a = mw.tools.firstMatchesOnNodeOrParent(target, ['a']);
        if(!a) return;

        if (!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(a, ['edit', 'module'])) {
            return;
        }
        return a;
    },
    tip: function (node) {
        if(!node) return;

        var link = document.createElement('a');
        link.href = node.getAttribute('href');
        link.target = '_blank';
        link.className = 'mw-link-tip-link';
        link.innerHTML = node.getAttribute('href');

        var editBtn = document.createElement('span');
        editBtn.className = 'mw-link-tip-edit';
        editBtn.innerHTML = mw.lang('Edit');

        var holder = document.createElement('div');

        holder.appendChild(link);
        holder.append(' - ');
        holder.appendChild(editBtn);

        editBtn.onclick = function(e) {
            e.preventDefault();
            new mw.LinkEditor({
                mode: 'dialog'
            })
                .setValue({url: node.href, text: node.innerHTML, target: node.target === '_blank'})
                .promise()
                .then(function (result){
                    node.href = result.url;
                    node.innerHTML = result.text;
                    node.target = result.target ? '_blank' : '_self';
                    mw.wysiwyg.change(node)
                });
            mw.$('.mw-link-tip').remove();
            return false;
        }

        mw.linkTip._tip = mw.tooltip({content: holder, position: 'bottom-center', skin: 'dark', element: node});
        mw.$(mw.linkTip._tip).addClass('mw-link-tip');

    }
}

