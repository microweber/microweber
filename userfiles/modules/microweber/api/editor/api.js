import {DomService} from "../classes/dom";
import {Check} from "./check";

const rangeWalker = (range, doc) => {
    doc = doc || document;
    let ranges = [];
    let el = range.startContainer;
    let elsToVisit = true;

    var count = 0;

    while (elsToVisit) {
        count++;

        let startOffset = el === range.startContainer ? range.startOffset : 0;
        let endOffset = el === range.endContainer ? range.endOffset : el.textContent.length;
        let r = doc.createRange();
        r.setStart(el, startOffset);
        r.setEnd(el, endOffset);
        ranges.push(r);
        elsToVisit = false;

        while (!elsToVisit && el !== range.endContainer) {
            count++;


            let nextEl = getFirstTextNode(el.nextSibling);
            if (nextEl) {
                el = nextEl;
                elsToVisit = true;
            }
            else {
                if (el.nextSibling) {
                    el = el.nextSibling;
                } else if (el.parentNode) {
                    el = el.parentNode;
                } else {
                    break;
                }
            }
        }
    }
    console.log(count)
    return ranges;
}
const getFirstTextNode = el => {
     if (!el)               return null;
    if (el.nodeType === 3)  return el;

    for (let child of el.childNodes) {
        if (child.nodeType === 3) {
            return child;
        }
        else {
            let textNode = getFirstTextNode(child);
            if (textNode !== null) {
                return textNode;
            }
        }
    }
    return null;
}


MWEditor.api = function (scope) {
    return {
        getSelection: function () {
            return scope.getSelection();
        },
        eachRange: function (c){
            var sel = scope.getSelection();
            if(sel.rangeCount && c) {
                for(var i = 0; i < sel.rangeCount; i++) {
                    var range = sel.getRangeAt(i);
                    c.call(scope, range);
                }
            }
        },
        getSelectionHTML: function (){
            var sel = scope.getSelection();
            var html = scope.actionWindow.document.createElement('div');
            if(sel.rangeCount) {
                var frag = sel.getRangeAt(0).cloneContents();
                while (frag.firstChild) {
                    html.append(frag.firstChild);
                }
            }
            return html.innerHTML;
        },
        cleanWord: function (content) {
            var wrapListRoots = function () {
                var all = scope.$editArea.querySelectorAll('li[data-level]'), i = 0, has = false;
                for (; i < all.length; i++) {
                    var parent = all[i].parentElement.nodeName;
                    if (parent !== 'OL' && parent !== 'UL') {
                        has = true;
                        var group = mw.$([]), curr = all[i];
                        while (!!curr && curr.nodeName === 'LI') {
                            group.push(curr);
                            curr = curr.nextElementSibling;
                        }
                        var el = scope.actionWindow.document.createElement(all[i].getAttribute('data-type') === 'ul' ? 'ul' : 'ol');
                        el.className = 'element';
                        group.wrapAll(el);
                        break;
                    }
                }
                if (has) return wrapListRoots();
            };

            var buildWordList = function (lists, count) {
                var i, check = false, max = 0;
                count = count || 0;
                if (count === 0) {
                    for (i in lists) {
                        var curr = lists[i];
                        if (!curr.nodeName || curr.nodeType !== 1) continue;

                        var el = document.createElement('li');
                        mw.tools.copyAttributes(node, el);
                        while (curr.firstChild) {
                            el.appendChild(curr.firstChild);
                        }
                        curr.replaceWith(el);
                        lists[i] = el;
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
                            if (!!prev && prev.nodeName === 'LI') {
                                var type = this.getAttribute('data-type');
                                var wrap = scope.actionWindow.document.createElement(type === 'ul' ? 'ul' : 'ol');
                                wrap.setAttribute('data-level', level);
                                mw.$(wrap).append(this);
                                mw.$(wrap).appendTo(prev);
                                check = true;
                            }
                            else if (!!prev && (prev.nodeName === 'UL' || prev.nodeName === 'OL')) {
                                var where = mw.$('li[data-level="' + level + '"]', prev);
                                if (where.length > 0) {
                                    where.after(this);
                                    check = true;
                                }
                                else {
                                    var type = this.getAttribute('data-type');
                                    var wrap = scope.actionWindow.document.createElement(type === 'ul' ? 'ul' : 'ol');
                                    wrap.setAttribute('data-level', level)
                                    mw.$(wrap).append(this);
                                    mw.$(wrap).appendTo($('li:last', prev))
                                    check = true;
                                }
                            }
                            else if (!prev && (this.parentNode.nodeName !== 'UL' && this.parentNode.nodeName !== 'OL')) {
                                var $curr = mw.$([this]), curr = this;
                                while ($(curr).next('li[data-level="' + level + '"]').length > 0) {
                                    $curr.push($(curr).next('li[data-level="' + level + '"]')[0]);
                                    curr = mw.$(curr).next('li[data-level="' + level + '"]')[0];
                                }
                                $curr.wrapAll($curr.eq(0).attr('data-type') === 'ul' ? '<ul></ul>' : '<ol></ol>')
                                check = true;
                            }
                        }
                    }
                });

                mw.$("ul[data-level!='1'], ol[data-level!='1']").each(function () {
                    var level = parseInt($(this).attr('data-level'));
                    if (!!this.previousElementSibling) {
                        var plevel = parseInt($(this.previousElementSibling).attr('data-level'));
                        if (level > plevel) {
                            mw.$('li:last', this.previousElementSibling).append(this)
                            check = true;
                        }
                    }
                });
                if (count === 0) {
                    setTimeout(function () {
                        buildWordList($('li[data-level]'), 1);
                        wrapListRoots();
                    }, 1);
                }
                return lists;
            };

            var word_listitem_get_level = function (item) {
                var msspl = item.getAttribute('style').split('mso-list');
                if (msspl.length > 1) {
                    var mssplitems = msspl[1].split(' ');
                    for (var i = 0; i < mssplitems.length; i++) {
                        if (mssplitems[i].indexOf('level') !== -1) {
                            return parseInt(mssplitems[i].split('level')[1], 10);
                        }
                    }
                }
            };

            var isWordHtml = function (html) {
                return html.indexOf('urn:schemas-microsoft-com:office:word') !== -1;
            };

            var _cleanWordList = function (html) {

                if (!isWordHtml(html)) return html;
                if (html.indexOf('</body>') !== -1) {
                    html = html.split('</body>')[0];
                }
                var parser = DomService.parseHtml(html).body;

                var lists = mw.$('[style*="mso-list:"]', parser);
                lists.each(function () {
                    var level = word_listitem_get_level(this);
                    if (!!level) {
                        this.setAttribute('data-level', level)
                        this.setAttribute('class', 'level-' + level)
                    }

                });

                mw.$('[style]', parser).removeAttr('style');

                if (lists.length > 0) {
                    lists = buildWordList(lists);
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
            };

            var cleanWord = function (html) {
                html = _cleanWordList(html);
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
            };

            var cleanTables = function (root) {
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
            };
            return cleanWord(content)

        },
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
        bold: function () {
            var opt = {
                css: {'font-weight': 'bold'},
                className: 'format-bold',
                fragmentModifier: function (frag) {
                    var el = frag.querySelector('b,strong,.format-bold')
                    while (el) {
                        el.replaceWith(...el.childNodes);
                        el = frag.querySelector('b,strong,.format-bold')
                    }
                },
            }
            scope.api.domCommand('cssApplier', opt);
        },
        unBold: function () {
            var opt = {
                css: {'font-weight': 'normal'},
                className: 'format-unbold',
                rangeModify: function (range) {
                    if( range.startContainer === range.endContainer && Check.isBold(range.startContainer) ) {
                        while(range.startContainer.firstChild) {
                            range.startContainer.parentNode.insertBefore(range.startContainer.firstChild, range.startContainer)
                        }
                    }
                    var el = frag.querySelector('b,strong,.format-bold')
                    while (el) {
                        el.replaceWith(...el.childNodes);
                        el = frag.querySelector('b,strong,.format-bold')
                    }
                }
            }
             scope.api.domCommand('cssApplier', opt);

        },
        boldToggle: function (){
            scope.api.execCommand('bold');
        },
        boldToggleNew: function (){
            const range = scope.api.getSelection().getRangeAt(0);
            const ranges = rangeWalker(range, scope.executionDocument);
            let isBoldLike = false;

            ranges.forEach(range => {
                const el = scope.api.elementNode(range.commonAncestorContainer);
                if(Check.isBold(el)) {
                    isBoldLike = true;
                }
            })

            if(isBoldLike) {
                scope.api.unBold();
            } else {
                scope.api.bold();
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
        selectAll: function (el) {
            var range = scope.document.createRange();
            range.selectNodeContents(el);
            var selection = scope.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        },
        selectElement: function (el) {
            var range = scope.document.createRange();
            try {
                range.selectNode(el);
                var selection = scope.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            } catch (e) {

            }
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
        getTextNodes: function (root, target){
            if(!target) target = [];
            var curr = root.firstChild;
            while (curr) {
                if(curr.nodeType === 3) {
                    target.push(curr);
                } else if(curr.nodeType === 1){
                    scope.api.getTextNodes(curr, target)
                }
                curr = curr.nextSibling;
            }
            return target;
        },
        classApplier: function (className) {
            var sel = scope.getSelection();
            var range = sel.getRangeAt(0);
            var frag = range.cloneContents();
            var nodes = scope.api.getTextNodes(frag).filter(function (node){ return !!node });
            nodes.forEach(function (node){
                var el = scope.actionWindow.document.createElement('span');
                el.className = 'mw-richtext-classApplier ' + className;
                el.textContent = node.textContent;
                node.parentNode.replaceChild(el, node);
            });
            range.deleteContents()
            range.insertNode(frag)
        },

        cssApplier: function (options) {


            const {css, className, fragmentModifier} = options;
            let styles = '';
            if (typeof css === 'object') {
                for (let i in css) {
                    styles += (i + ':' + css[i] + ';');
                }
            } else if(typeof css === 'string') {
                styles = css;
            }
            let sel = scope.getSelection();
            let el = scope.api.elementNode(sel.focusNode);
            let range = sel.getRangeAt(0);
            let ranges = rangeWalker(range, scope.executionDocument);

            console.log(ranges.length)

            if(styles || className) {
                ranges.forEach(range => {
                    if(options.rangeModify) {
                        options.rangeModify(range)
                    }
                    let el = document.createElement('span');
                    el.className = 'mw-richtext-cssApplier' + (!!className ? (' ' + className) : '');
                    el.setAttribute('style', styles);
                    range.surroundContents(el);
                })
            }


            setTimeout(function (){
                 var tr = Array.from(scope.actionWindow.document.querySelectorAll('.mw-richtext-cssApplier:empty'));


                while(tr[0]) {
                    tr[0].remove()
                    tr.splice(0, 1)
                }
                var all = scope.actionWindow.document.querySelectorAll('.mw-richtext-cssApplier .mw-richtext-cssApplier');
                var i = 0; var l = all.length;
                for ( ; i < l;  i++ ) {
                    if(all[i].outerHTML === all[i].parentNode.innerHTML) {
                        all[i].parentNode.parentNode.replaceChild(all[i], all[i].parentNode);
                        return;
                    }
                }
            }, 100)

        },
        _old_cssApplier: function (options) {
            const {css, className, fragmentModifier} = options;
            var styles = '';
            if (typeof css === 'object') {
                for (var i in css) {
                    styles += (i + ':' + css[i] + ';');
                }
            } else if(typeof css === 'string') {
                styles = css;
            }
            var sel = scope.getSelection();
            var el = scope.api.elementNode(sel.focusNode);
            var range = sel.getRangeAt(0);
            var frag = range.cloneContents();
            if(typeof fragmentModifier === 'function') {
                fragmentModifier(frag)
            }
            var nodes = scope.api.getTextNodes(frag).filter(function (node){ return !!node });
            if(styles || className) {
                nodes.forEach(function (node){
                    var el = scope.actionWindow.document.createElement('span');
                    el.className = 'mw-richtext-cssApplier' + (!!className ? (' ' + className) : '');
                    el.setAttribute('style', styles);
                    el.textContent = node.textContent;
                    node.parentNode.replaceChild(el, node);
                });
            }
            range.deleteContents();
            range.insertNode(frag);
        },
        isSafeMode: function(el) {
            if (!el) {
                var node = scope.getSelection().focusNode;
                el = scope.api.elementNode(node);
            }
            var hasSafe = DomService.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
            var regInsafe = DomService.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
            return hasSafe && !regInsafe;
        },
        _execCommandCustom: {
            removeFormat: function (cmd, def, val) {
                scope.actionWindow.document.execCommand(cmd, def, val);
                var sel = scope.getSelection();
                var r = sel.getRangeAt(0);
                var common = r.commonAncestorContainer;
                var all = common.querySelectorAll('*'), l = all.length, i = 0;
                for ( ; i < l; i++ ) {
                    var el = all[i];
                    if (typeof sel !== 'undefined' && sel.containsNode(el, true)) {
                        all[i].removeAttribute('style');
                    }
                }
            }
        },
        domCommand: function (method, options) {
            var sel = scope.getSelection();

            try {  // 0x80004005
                if (  scope.api.isSelectionEditable()) {
                    if (sel.rangeCount > 0) {
                        var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(DomService.firstBlockLevel(node), function () {
                             scope.api[method].call(scope.api, options);
                            mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                            mw.$(scope).trigger('execCommand');
                            scope.registerChange();
                        });
                    }
                }
            }
            catch (e) {
            }
        },
        execCommand: function (cmd, def, val) {
             scope.actionWindow.document.execCommand('styleWithCss', 'false', false);
            var sel = scope.getSelection();
            try {  // 0x80004005
                if (scope.actionWindow.document.queryCommandSupported(cmd) && scope.api.isSelectionEditable()) {
                    def = def || false;
                    val = val || false;
                    if (sel.rangeCount > 0) {
                        var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(DomService.firstBlockLevel(node), function () {
                            scope.actionWindow.document.execCommand(cmd, def, val);
                            $(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                            $(scope).trigger('execCommand');
                        });
                    }
                }
            }
            catch (e) {
            }
        },
        _fontSize: function (size, unit) {
            unit = unit || 'px';

            scope.api.domCommand('cssApplier', {
                css: {
                    'font-size': size + unit
                }
            });
        },
        lineHeight: function (size) {

            if (scope.api.isSelectionEditable()) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode)
                scope.api.action(DomService.firstBlockLevel(el), function () {
                     el.style.lineHeight = size
                });
            }

        },
        fontSize: function (size) {
            var sel = scope.getSelection();
            if (sel.isCollapsed) {
                scope.api.selectAll(scope.api.elementNode(sel.focusNode));
                sel = scope.getSelection();
            }
            var range = sel.getRangeAt(0),
                common = scope.api.elementNode(range.commonAncestorContainer);
            var nodrop_state = DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(common, ['allow-drop', 'nodrop']);
            if (scope.api.isSelectionEditable() && nodrop_state) {
                scope.api._fontSize(size, 'px');
            }
        },
        saveSelection: function () {
            var sel = scope.getSelection();
            scope.api.savedSelection = {
                selection: sel,
                range: sel.getRangeAt(0),
                element: scope.api.elementNode(sel.getRangeAt(0).commonAncestorContainer)
            };
        },
        restoreSelection: function () {
            if (scope.api.savedSelection) {
                var sel = scope.getSelection();
                DomService.firstParentOrCurrentWithAnyOfClasses(scope.api.savedSelection.element, ['edit', 'safe-element']).contentEditable = true;
                scope.api.savedSelection.element.focus();
                scope.api.savedSelection.selection.removeAllRanges();
                scope.api.savedSelection.selection.addRange(scope.api.savedSelection.range);
            }
        },
        _cleaner: document.createElement('div'),
        cleanHTML: function(html) {
             this._cleaner.innerHTML = html;
            var elements = Array.prototype.slice.call(this._cleaner.querySelectorAll('iframe,script,noscript'));
            while (elements.length) {
                elements[0].remove();
                elements.shift();
            }
            return this._cleaner.innerHTML;
        },
        insertHTML: function(html) {
            return scope.api.execCommand('insertHTML', false, this.cleanHTML(html));
        },
        insertImage: function (url) {
            var id =   ('image_' + new Date().getTime());
            var img = '<img id="' + id + '" contentEditable="false" class="element" src="' + url + '" />';
            scope.api.insertHTML(img);
            img = mw.$("#" + id);
            img.removeAttr("_moz_dirty");
            return img[0];
        },
        link: function (result) {
            var sel = scope.getSelection();
            var el = scope.api.elementNode(sel.focusNode);
            var elLink = el.nodeName === 'A' ? el : DomService.firstParentWithTag(el, 'a');
            if (elLink) {
                elLink.href = result.url;
                if (result.text && result.text !== elLink.innerHTML) {
                    elLink.innerHTML = result.text;
                }
            } else {
                scope.api.insertHTML('<a href="'+ result.url +'">'+ (result.text || (sel.toString().trim()) || result.url) +'</a>');
            }
        },
        unlink: function () {
            var sel = scope.getSelection();
            if (!sel.isCollapsed) {
                this.execCommand('unlink', null, null);
            }
            else {
                var link = DomService.firstParentOrCurrentWithTag(this.elementNode(sel.focusNode), 'a');
                if (!!link) {
                    this.selectElement(link);
                    this.execCommand('unlink', null, null);
                }
            }
        }
    };
};
