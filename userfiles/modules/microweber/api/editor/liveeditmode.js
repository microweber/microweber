
var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element'])
        && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
};




MWEditor.leSave = {
   prepare: function(root){
        if(!root) {
            return null;
        }
       var doc = mw.tools.parseHtml();
       var doc = document.implementation.createHTMLDocument("");
       doc.body.innerHTML = root.innerHTML;

       mw.$('.element-current', doc).removeClass('element-current');
       mw.$('.element-active', doc).removeClass('element-active');
       mw.$('.disable-resize', doc).removeClass('disable-resize');
       mw.$('.mw-webkit-drag-hover-binded', doc).removeClass('mw-webkit-drag-hover-binded');
       mw.$('.module-cat-toggle-Modules', doc).removeClass('module-cat-toggle-Modules');
       mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
       mw.$('-module', doc).removeClass('-module');
       mw.$('.empty-element', doc).remove();
       mw.$('.empty-element', doc).remove();
       mw.$('.edit .ui-resizable-handle', doc).remove();
       mw.$('script', doc).remove();
       mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
       mw.$("[contenteditable]", doc).removeAttr("contenteditable");
       var all = doc.querySelectorAll('[contenteditable]'),
           l = all.length,
           i = 0;
       for (; i < l; i++) {
           all[i].removeAttribute('contenteditable');
       }
       var all1 = doc.querySelectorAll('.module'),
           l1 = all.length,
           i1 = 0;
       for (; i1 < l1; i1++) {
           if (all[i1].querySelector('.edit') === null) {
               all[i1].innerHTML = '';
           }
       }
       return doc;
   },
   htmlAttrValidate:function(edits){
        var final = [];
        $.each(edits, function(){
            var html = this.outerHTML;
            html = html.replace(/url\(&quot;/g, "url('");
            html = html.replace(/jpg&quot;/g, "jpg'");
            html = html.replace(/jpeg&quot;/g, "jpeg'");
            html = html.replace(/png&quot;/g, "png'");
            html = html.replace(/gif&quot;/g, "gif'");
            final.push($(html)[0]);
        })
        return final;
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
        mw.$('.mw-skip-and-remove', body).remove();
        return body;
    },
   getData: function(edits) {
        mw.$(edits).each(function(){
            mw.$('meta', this).remove();
        });

        edits = this.htmlAttrValidate(edits);
        var l = edits.length,
            i = 0,
            helper = {},
            master = {};
        if (l > 0) {
            for (; i < l; i++) {
                helper.item = edits[i];
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    mw.$(helper.item).removeClass('changed');
                    mw.tools.foreachParents(helper.item, function(loop) {
                        var cls = this.className;
                        var rel = mw.tools.mwattr(this, 'rel');
                        if (mw.tools.hasClass(cls, 'edit') && mw.tools.hasClass(cls, 'changed') && (!!rel)) {
                            helper.item = this;
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    var field = !!helper.item.id ? '#'+helper.item.id : '';
                    console.warn('Skipped save: .edit'+field+' element does not have rel attribute.');
                    continue;
                }
                mw.$(helper.item).removeClass('changed orig_changed');
                mw.$(helper.item).removeClass('module-over');

                mw.$('.module-over', helper.item).each(function(){
                    mw.$(this).removeClass('module-over');
                });
                mw.$('[class]', helper.item).each(function(){
                    var cls = this.getAttribute("class");
                    if(typeof cls === 'string'){
                        cls = cls.trim();
                    }
                    if(!cls){
                        this.removeAttribute("class");
                    }
                });
                var content = this.cleanUnwantedTags(helper.item).innerHTML;
                var attr_obj = {};
                var attrs = helper.item.attributes;
                if (attrs.length > 0) {
                    var ai = 0,
                        al = attrs.length;
                    for (; ai < al; ai++) {
                        attr_obj[attrs[ai].nodeName] = attrs[ai].nodeValue;
                    }
                }
                var obj = {
                    attributes: attr_obj,
                    html: content
                };
                var objdata = "field_data_" + i;
                master[objdata] = obj;
            }
        }
        return master;
    }
};

MWEditor.leCore = {};

// methods accessible by scope.liveedit

MWEditor.liveeditMode = function(scope){
    return {

        prepare: {
            titles: function () {
                var t = scope.querySelectorAll('[field="title"]'),
                    l = t.length,
                    i = 0;

                for (; i < l; i++) {
                    mw.$(t[i]).addClass("nodrop");
                }
            }
        },

        isSafeMode: function (el) {
            if (!el) {
                var sel = scope.selection;
                if(!sel.rangeCount) return false;
                var range = sel.getRangeAt(0);
                el = scope.api.elementNode(range.commonAncestorContainer);
            }
            var hasSafe = mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
            var regInsafe = mw.tools.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
            return hasSafe && !regInsafe;
        },
        init: function (body, scope) {
            mw.$(body).on('keydown', function (event) {
                if (event.type === 'keydown') {
                    if (mw.tools.isField(event.target) || !event.target.isContentEditable) {
                        return true;
                    }
                    var sel = scope.selection;
                    if (mw.event.is.enter(event)) {
                        if (MWEditor.liveeditMode.isSafeMode(event.target)) {
                            var isList = mw.tools.firstMatchesOnNodeOrParent(event.target, ['li', 'ul', 'ol']);
                            if (!isList) {
                                event.preventDefault();
                                scope.api.insertHTML('<br>\u200C');
                            }
                        }
                    }
                    if (sel.rangeCount > 0) {
                        var r = sel.getRangeAt(0);
                        if (event.keyCode === 9 && !event.shiftKey && sel.focusNode.parentNode.isContentEditable && sel.isCollapsed) {   /* tab key */
                            scope.api.insertHTML('&nbsp;&nbsp;&nbsp;&nbsp;');
                            return false;
                        }
                        return manageDeleteAndBackspace(event, sel);
                    }
                }
            });
        },
        manageDeleteAndBackspaceInSafeMode : function (event, sel) {
            var node = scope.api.elementNode(sel.focusNode);
            var range = sel.getRangeAt(0);
            if(!node.textContent.replace(/\s/gi, '')){
                MWEditor.liveeditMode._manageDeleteAndBackspaceInSafeMode.emptyNode(event, node, sel, range);
                return false;
            }
            // MWEditor.liveeditMode._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
            return true;
        },
        merge: {
            /* Executes on backspace or delete */
            isMergeable: function (el) {
                if (!el) return false;
                if (el.nodeType === 3) return true;
                var is = false;
                var css =  getComputedStyle(el);
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
                var isnonbreakable = scope.liveedit.merge.isInNonbreakable(curr, dir);
                if (isnonbreakable) {
                    var conts = scope.selection.getRangeAt(0);
                    event.preventDefault();
                    if (next !== null) {
                        if (next.nodeType === 3 && /\r|\n/.exec(next.nodeValue) !== null) {
                            event.preventDefault();
                            return false;
                        }
                        if (dir === 'next') {
                            scope.liveedit.cursorToElement(next);
                        }
                        else {
                            scope.liveedit.cursorToElement(next, 'end');
                        }
                    }
                    else {
                        return false;
                    }
                }
            },
            isInNonbreakable: function (el, dir) {
                var absNext = scope.liveedit.merge.findNextNearest(el, dir);

                if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                    absNext = scope.liveedit.merge.findNextNearest(el, dir, true)
                }

                if (absNext.nodeType === 1) {
                    if (mw.tools.hasAnyOfClasses(absNext, ['nodrop', 'allow-drop'])) {
                        return false;
                    }
                    if (absNext.querySelector('.nodrop', '.allow-drop') !== null) {
                        return false;
                    }
                }
                if (scope.liveedit.merge.alwaysMergeable(absNext) && (scope.liveedit.merge.alwaysMergeable(absNext.firstElementChild) || !absNext.firstElementChild)) {
                    return false;
                }
                if (el.textContent === '') {

                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, dir);
                    if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext)
                    }
                }

                if (el.nodeType === 1 && !!el.textContent.trim()) {
                    return false;
                }
                if (el.nextSibling === null && el.nodeType === 3 && dir == 'next') {
                    var absNext = scope.liveedit.merge.findNextNearest(el, dir);
                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, dir);
                    if (/\r|\n/.exec(absNext.nodeValue) !== null) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext)
                    }

                    if (absNextNext.nodeType === 1) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext) || scope.liveedit.merge.isInNonbreakableClass(absNextNext.firstChild);
                    }
                    else if (absNextNext.nodeType === 3) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }

                if (el.previousSibling === null && el.nodeType === 3 && dir === 'prev') {
                    var absNext = scope.liveedit.merge.findNextNearest(el, 'prev');
                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, 'prev');
                    if (absNextNext.nodeType === 1) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext);
                    }
                    else if (absNextNext.nodeType === 3) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                el = scope.api.elementNode(el);

                var is = scope.liveedit.merge.isInNonbreakableClass(el);
                return is;
            },
            isInNonbreakableClass: function (el, dir) {
                var absNext = scope.liveedit.merge.findNextNearest(el, dir);

                if (el.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) === null) return false;
                el = scope.api.elementNode(el);
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
                            });
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
                if (dir === 'next') {
                    var dosearch = searchElement ? 'nextElementSibling' : 'nextSibling';
                    var next = el[dosearch];
                    if (next === null) {
                        while (el[dosearch] === null) {
                            el = el.parentNode;
                            next = el[dosearch];
                        }
                    }
                }
                else {
                    var dosearch = searchElement ? 'previousElementSibling' : 'previousSibling';
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
                    return scope.liveedit.merge.alwaysMergeable(scope.api.elementNode(el))
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
        _manageDeleteAndBackspaceInSafeMode : {
            emptyNode: function (event, node, sel, range) {
                if(!canDestroy(node)) {
                    return;
                }
                var todelete = node;
                if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                    todelete = node.parentNode;
                }
                var transfer, transferPosition;
                if (mw.event.is.delete(event)) {
                    transfer = todelete.nextElementSibling;
                    transferPosition = 'start';
                } else {
                    transfer = todelete.previousElementSibling;
                    transferPosition = 'end';
                }
                var parent = todelete.parentNode;
                scope.record({
                    target: parent,
                    value: parent.innerHTML
                });
                $(todelete).remove();
                if(transfer && mw.tools.isEditable(transfer)) {
                    setTimeout(function () {
                        scope.liveedit.cursorToElement(transfer, transferPosition);
                    });
                }
                scope.record({
                    target: parent,
                    value: parent.innerHTML
                });
            },
            nodeBoundaries: function (event, node, sel, range) {
                var isStart = range.startOffset === 0 || !((sel.anchorNode.data || '').substring(0, range.startOffset).replace(/\s/g, ''));
                var curr, content;
                if(mw.event.is.backSpace(event) && isStart && range.collapsed){ // is at the beginning
                    curr = node;
                    if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                        curr = node.parentNode;
                    }
                    var prev = curr.previousElementSibling;
                    if(prev && prev.nodeName === node.nodeName && canDestroy(node)) {
                        content = node.innerHTML;
                        scope.liveedit.cursorToElement(prev, 'end');
                        prev.appendChild(range.createContextualFragment(content));
                        $(curr).remove();
                    }
                } else if(mw.event.is.delete(event)
                    && range.collapsed
                    && range.startOffset === sel.anchorNode.data.replace(/\s*$/,'').length // is at the end
                    && canDestroy(node)){
                    curr = node;
                    if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                        curr = node.parentNode;
                    }
                    var next = curr.nextElementSibling, deleteParent;
                    if(mw.tools.hasAnyOfClasses(next, ['text', 'title'])){
                        next = next.firstElementChild;
                        deleteParent = true;
                    }
                    if(next && next.nodeName === curr.nodeName) {
                        content = next.innerHTML;
                        setTimeout(function(){
                            var parent = deleteParent ? next.parentNode.parentNode : next.parentNode;
                            scope.actionRecord(function() {
                                    return {
                                        target: parent,
                                        value: parent.innerHTML
                                    };
                                }, function () {
                                    curr.append(range.createContextualFragment(content));
                                }
                            );
                        });
                    }
                }
            }
        },
        manageDeleteAndBackspace: function (event, sel) {
            if (mw.event.is.delete(event) || mw.event.is.backSpace(event)) {
                if(!sel.rangeCount) return;
                var r = sel.getRangeAt(0);
                var isSafe = scope.liveedit.isSafeMode();

                if(isSafe) {
                    return scope.liveedit.manageDeleteAndBackspaceInSafeMode(event, sel);
                }
                var nextNode = null, nextchar, nextnextchar, nextel;

                if (mw.event.is.delete(event)) {
                    nextchar = sel.focusNode.textContent.charAt(sel.focusOffset);
                    nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset + 1);
                    nextel = sel.focusNode.nextSibling || sel.focusNode.nextElementSibling;

                } else {
                    nextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 1);
                    nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 2);
                    nextel = sel.focusNode.previouSibling || sel.focusNode.previousElementSibling;
                }

                if ((nextchar === ' ' || /\r|\n/.exec(nextchar) !== null) && sel.focusNode.nodeType === 3 && !nextnextchar) {
                    event.preventDefault();
                    return false;
                }

                if (nextnextchar === '') {

                    if (nextchar.replace(/\s/g, '') === '' && r.collapsed) {

                        if (nextel && !mw.tools.isBlockLevel(nextel) && ( typeof nextel.className === 'undefined' || !nextel.className.trim())) {
                            return true;
                        }
                        else if (nextel && nextel.nodeName !== 'BR') {
                            if (sel.focusNode.nodeName === 'P') {
                                if (event.keyCode === 46) {
                                    if (sel.focusNode.nextElementSibling.nodeName === 'P') {
                                        return true;
                                    }
                                }
                                if (event.keyCode === 8) {

                                    if (sel.focusNode.previousElementSibling.nodeName === 'P') {
                                        return true;
                                    }
                                }
                            }
                            event.preventDefault();
                            return false;
                        }

                    }
                    else if (
                        (focus.previousElementSibling === null && rootfocus.previousElementSibling === null)
                        && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])) {
                        return false;
                    }
                }
                if (nextchar === '') {

                    if (mw.event.is.delete(event)) {
                        nextNode = scope.liveedit.merge.getNext(sel.focusNode);
                    }
                    if (mw.event.is.backSpace(event)) {
                        nextNode = scope.liveedit.merge.getPrev(sel.focusNode);
                    }
                    if (scope.liveedit.merge.alwaysMergeable(nextNode)) {
                        return true;
                    }

                    var nonbr = scope.liveedit.merge.isInNonbreakable(nextNode)
                    if (nonbr) {
                        event.preventDefault();
                        return false;
                    }
                    if (nextNode !== null && scope.liveedit.merge.isMergeable(nextNode)) {
                        if (mw.event.is.delete(event)) {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                        }
                        else {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                        }
                    }
                    else {
                        event.preventDefault();
                    }
                    if (nextNode === null) {
                        nextNode = sel.focusNode.parentNode.nextSibling;
                        if (!scope.liveedit.merge.isMergeable(nextNode)) {
                            event.preventDefault();
                        }
                        if (mw.event.is.delete(event)) {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                        }
                        else {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                        }
                    }
                }
            }
            return true;
        }

    };
};






