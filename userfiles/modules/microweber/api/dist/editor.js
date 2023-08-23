/******/ (() => { // webpackBootstrap
(() => {
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/bar.js ***!
  \********************************************************/
(function(){
    var Bar = function(options) {

        options = options || {};
        var defaults = {
            document: document,
            register: null
        };
        this.settings = $.extend({}, defaults, options);
        this.document = this.settings.document || document;

        this.register = [];

        this.delimiter = function(){
            var el = this.document.createElement('span');
            el.className = 'mw-bar-delimiter';
            return el;
        };

        this.create = function(){
            this.bar = this.document.createElement('div');
            this.bar.className = 'mw-bar';
            this.element = mw.element(this.bar);
        };

        this.rows = [];

        this.createRow = function () {
            var row = this.document.createElement('div');
            row.className = 'mw-bar-row';
            this.rows.push(row);
            this.bar.appendChild(row);
        };
        this.nativeElement = function (node) {
            if(!node) return;
            return node.node ? node.node : node;
        };

        this.add = function (what, row) {
            row = row || 0;
            if(!this.rows[row]) {
                return;
            }
            if(what === '|') {
                this.rows[row].appendChild(this.delimiter());
            } else if(typeof what === 'function') {
                this.rows[row].appendChild(what().node);
            } else {
                var el = this.nativeElement(what);
                if(el) {
                    el.classList.add('mw-bar-control-item')
                    this.rows[row].appendChild(el);
                }

            }
        };

        this.init = function(){
            this.create();
        };
        this.init();
    };
    mw.bar = function(options){
        return new Bar(options);
    };
})();

})();

(() => {
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/api.js ***!
  \********************************************************/









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
            var html = document.createElement('div');
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
                        var el = document.createElement(all[i].getAttribute('data-type') === 'ul' ? 'ul' : 'ol');
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
                            if (!!prev && prev.nodeName === 'LI') {
                                var type = this.getAttribute('data-type');
                                var wrap = document.createElement(type === 'ul' ? 'ul' : 'ol');
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
                                    var wrap = document.createElement(type === 'ul' ? 'ul' : 'ol');
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
                var parser = mw.tools.parseHtml(html).body;

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

            try {   /* Firefox returns wrong target (div) when you click on a spin-button */

                if( !c || !c.parentNode || c.parentNode === document.body ){
                    return null;
                }

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
        isSafeMode: function(el) {
            if (!el) {
                var node = scope.getSelection().focusNode;
                el = scope.api.elementNode(node);
            }
            var hasSafe = mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
            var regInsafe = mw.tools.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
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
        execCommand: function (cmd, def, val) {
             scope.actionWindow.document.execCommand('styleWithCss', 'false', false);
            var sel = scope.getSelection();
            try {  // 0x80004005
                 if (scope.actionWindow.document.queryCommandSupported(cmd) && scope.api.isSelectionEditable()) {
                     def = def || false;
                    val = val || false;
                    if (sel.rangeCount > 0) {
                         var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(mw.tools.firstBlockLevel(node), function () {
                             scope.actionWindow.document.execCommand(cmd, def, val);
                            mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                            mw.$(scope).trigger('execCommand');
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
        lineHeight: function (size) {

            if (scope.api.isSelectionEditable()) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode)
                scope.api.action(mw.tools.firstBlockLevel(el), function () {
                     el.style.lineHeight = size
                });
            }

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
            console.log(this.cleanHTML(html))
            return scope.api.execCommand('insertHTML', false, this.cleanHTML(html));
        },
        insertImage: function (url) {
            var id =  mw.id('image_');
            var img = '<img id="' + id + '" contentEditable="false" class="element" src="' + url + '" />';
            scope.api.insertHTML(img);
            img = mw.$("#" + id);
            img.removeAttr("_moz_dirty");
            return img[0];
        },
        link: function (result) {
            var sel = scope.getSelection();
            var el = scope.api.elementNode(sel.focusNode);
            var elLink = el.nodeName === 'A' ? el : mw.tools.firstParentWithTag(el, 'a');
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
                var link = mw.tools.firstParentOrCurrentWithTag(this.elementNode(sel.focusNode), 'a');
                if (!!link) {
                    this.selectElement(link);
                    this.execCommand('unlink', null, null);
                }
            }
        }
    };
};

})();

(() => {
/*!************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/helpers.js ***!
  \************************************************************/
MWEditor.controllersHelpers = {
    '|' : function () {
        return mw.element({
            tage: 'span',
            props: {
                className: 'mw-bar-delimiter'
            }
        });
    }
};

})();

(() => {
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/tools.js ***!
  \**********************************************************/
MWEditor.tools = {

};

})();

(() => {
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/core.js ***!
  \*********************************************************/
MWEditor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'button',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-button',
                type: 'button'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = $.extend(true, {}, defaults, config);
        return mw.element(settings);
    },
    colorPicker: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = $.extend(true, {}, defaults, config);

        var el = MWEditor.core.button(settings);
        el.addClass('mw-editor-color-picker')
        var input = mw.element({
            tag: 'input',
            props: {
                type: 'color',
                className: 'mw-editor-color-picker-node'
            }
        });
        var time = null;
        input.on('input', function (){
            clearTimeout(time);
            time = setTimeout(function (el, node){
                console.log(node.value)
                el.trigger('change', node.value);
            }, 210, el, this);
        });
        el.append(input);
        return el;
    },
    element: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = $.extend(true, {}, defaults, config);
        var el = mw.element(settings);
        el.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
        return el;
    },

    _dropdownOption: function (data) {
        // data: { label: string, value: any },
        var option = MWEditor.core.element({
            props: {
                className: 'mw-editor-dropdown-option',
                innerHTML: data.label
            }
        });
        option.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
        option.value = data.value;
        return option;
    },
    dropdown: function (options) {
        var lscope = this;
        this.root = MWEditor.core.element();
        this.select = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-component-select',
                tooltip: options.placeholder || null
            }
        });
        var displayValNode = MWEditor.core.button({
            props: {
                className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                innerHTML: options.placeholder || ''
            }
        });

        var valueHolder = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-select-values-holder',

            }
        });
        this.root.value = function (val){
            this.displayValue(val.label);
            this.value(val.value);
        };

        this.root.displayValue = function (val) {
            displayValNode.text(val || options.placeholder || '');
        };

        this.select.append(displayValNode);
        this.select.append(valueHolder);
        this.select.valueHolder = valueHolder;
        for (var i = 0; i < options.data.length; i++) {
            var dt = options.data[i];
            (function (dt){
                var opt = MWEditor.core._dropdownOption(dt);
                opt.on('click', function (){
                    lscope.select.trigger('change', dt);
                });
                valueHolder.append(opt);
            })(dt);

        }
        var curr = lscope.select.get(0);
        this.select.on('click', function (e) {
            e.stopPropagation();
            var wrapper = mw.tools.firstParentWithClass(this, 'mw-editor-wrapper');
            if (wrapper) {
                var edOff = wrapper.getBoundingClientRect();
                var selOff = this.getBoundingClientRect();
                lscope.select.valueHolder.css({
                    maxHeight: edOff.height - (selOff.top - edOff.top)
                });
            }

            mw.element('.mw-editor-controller-component-select').each(function (){
                if (this !== curr ) {
                    this.classList.remove('active');
                }
            });
            mw.element(this).toggleClass('active');
        });
        this.root.append(this.select);
    },
    _preSelect: function (node) {
        var all = document.querySelectorAll('.mw-editor-controller-component-select.active, .mw-bar-control-item-group.active');
        var parent = mw.tools.firstParentOrCurrentWithAnyOfClasses(node ? node.parentNode : null, ['mw-editor-controller-component-select','mw-bar-control-item-group']);
        var i = 0, l = all.length;
        for ( ; i < l; i++) {
            if(!node || (all[i] !== node && all[i] !== parent)) {
                all[i].classList.remove('active');
            }
        }
    }
};

})();

(() => {
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/controllers.js ***!
  \****************************************************************/
MWEditor.controllers = {
    align: function (scope, api, rootScope) {
        this.root = MWEditor.core.element();
        this.root.$node.addClass('mw-editor-state-component mw-editor-state-component-align');
        this.buttons = [];

        var arr = [
            {align: 'left', icon: 'left', action: 'justifyLeft'},
            {align: 'center', icon: 'center', action: 'justifyCenter'},
            {align: 'right', icon: 'right', action: 'justifyRight'},
            {align: 'justify', icon: 'justify', action: 'justifyFull'}
        ];
        this.render = function () {
            var scope = this;
            arr.forEach(function (item) {
                var el = MWEditor.core.button({
                    props: {
                        className: 'mdi-format-align-' + item.icon
                    }
                });
                el.on('mousedown touchstart', function (e) {
                    api.execCommand(item.action);
                });
                scope.root.append(el);
                scope.buttons.push(el);
            });
            return scope.root;
        };
        this.checkSelection = function (opt) {
            var align = opt.css.alignNormalize();
            for (var i = 0; i< this.buttons.length; i++) {
                var state = arr[i].align === align;
                rootScope.controllerActive(this.buttons[i].node, state);
            }
        };
        this.element = this.render();
    },
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-bold',
                    tooltip: rootScope.lang('Bold')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('bold');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().bold) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    strikeThrough: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-strikethrough',
                    tooltip: rootScope.lang('Strike through')
                }
            });

            el.on('mousedown touchstart', function (e) {
                api.execCommand('strikeThrough');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().striked) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    italic: function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-italic',
                    tooltip: rootScope.lang('Italic')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('italic');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().italic) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },
    'underline': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-underline',
                    tooltip: rootScope.lang('Underline')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('underline');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().underlined) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },
    'image': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-folder-multiple-image',
                    tooltip: rootScope.lang('Insert Image')
                }
            });
            el.on('click', function (e) {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        url = url.toString();
                        api.insertImage(url);
                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 860
                })

            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    link: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link',
                    tooltip: rootScope.lang('Insert link')
                }
            });

            el.on('click', function (e) {
                api.saveSelection();
                var sel = scope.getSelection();

                var target = mw.tools.firstParentWithTag(sel.focusNode, 'a');

                var val;
                if(target) {
                    val = {
                        url: target.href,
                        text: target.innerHTML,
                        target: target.target === '_blank'
                    };
                } else if(!sel.isCollapsed) {
                    val = {
                        url: '',
                        text: api.getSelectionHTML(),
                        target: false
                    };
                }
                var linkEditor = new mw.LinkEditor({
                    mode: 'dialog',
                });
                if(val) {
                    linkEditor.setValue(val);
                }

                linkEditor.promise().then(function (data){
                    var modal = linkEditor.dialog;
                    if(data) {
                        api.restoreSelection();
                        api.link(data);
                        modal.remove();
                    } else {
                        modal.remove();
                    }
                });


            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    fontSize: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: '8px', value: 8 },
                    { label: '10px', value: 10 },
                    { label: '12px', value: 12 },
                    { label: '14px', value: 14 },
                    { label: '16px', value: 16 },
                    { label: '18px', value: 18 },
                    { label: '20px', value: 20 },
                    { label: '22px', value: 22 },
                    { label: '24px', value: 24 },
                    { label: '22px', value: 22 },
                ],
                placeholder: rootScope.lang('Font Size')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.fontSize(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    lineHeight: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.height;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                icon: 'format-line-spacing',
                data: [
                    { label: 'normal', value: 'normal' },
                    { label: '14px', value:'14px' },
                    { label: '16px', value:'16px' },
                    { label: '19px', value:'19px' },
                    { label: '21px', value:'21px' },
                    { label: '24px', value:'24px' },
                    { label: '25px', value:'25px' },
                    { label: '27px', value:'27px' },
                    { label: '30px', value:'30px' },
                    { label: '35px', value:'35px' },
                    { label: '40px', value:'40px' },
                    { label: '45px', value:'45px' },
                    { label: '50px', value:'50px' },
                    { label: '55px', value:'55px' },
                    { label: '60px', value:'60px' },
                ],
                placeholder: rootScope.lang('Line height')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.lineHeight(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    format: function (scope, api, rootScope) {

        this._availableTags = [
            { label: '<h1>Title</h1>', value: 'h1' },
            { label: '<h2>Title</h2>', value: 'h2' },
            { label: '<h3>Title</h3>', value: 'h3' },
            { label: '<h4>Title</h4>', value: 'h4' },
            { label: '<h5>Title</h5>', value: 'h5' },
            { label: '<h6>Title</h6>', value: 'h6' },
            { label: 'Paragraph', value: 'p' },
            { label: 'Block', value: 'div' },
            { label: 'Pre formated', value: 'pre' }
        ];

        this.availableTags = function () {
            if(this.__availableTags) {
                return this.__availableTags;
            }
            this.__availableTags = this._availableTags.map(function (item) {
                return item.value;
            });
            return this.availableTags();
        };

        this.getTagDisplayName = function (tag) {
            tag = (tag || '').trim().toLowerCase();
            if(!tag) return;
            for (var i = 0; i < this._availableTags.length; i++) {
                if(this._availableTags[i].value === tag) {
                    return this._availableTags[i].label;
                }
            }
        };

        this.checkSelection = function (opt) {
            var el = opt.api.elementNode(opt.selection.focusNode);
            var parentEl = mw.tools.firstParentOrCurrentWithTag(el, this.availableTags());
            opt.controller.element.displayValue(parentEl ? this.getTagDisplayName(parentEl.nodeName) : '');
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: this._availableTags,
                placeholder: rootScope.lang('Format')
            });
            dropdown.select.on('change', function (e, val) {
                api.execCommand('formatBlock', false, e.detail.value);
                /*var sel = scope.getSelection();
                var range = sel.getRangeAt(0);
                var el = scope.actionWindow.document.createElement(val.value);

                var disableSelection = true;

                if(sel.isCollapsed || disableSelection) {
                    var selectionElement = api.elementNode(sel.focusNode);
                    if(scope.$editArea[0] !== selectionElement) {
                        mw.tools.setTag(selectionElement, val.value);
                    } else {
                        while (selectionElement.firstChild) {
                            el.appendChild(selectionElement.firstChild);
                        }
                        selectionElement.appendChild(el);
                    }
                    var newRange = scope.actionWindow.document.createRange();
                    newRange.setStart(sel.anchorNode, sel.anchorOffset);
                    newRange.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else {
                    range.surroundContents(el);
                }*/
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    fontSelector: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
                var font = css.font();
                var family_array = font.family.split(','), fam;
                if (family_array.length === 1) {
                    fam = font.family;
                } else {
                    fam = family_array.shift();
                }
                fam = fam.replace(/['"]+/g, '');
                opt.controller.element.displayValue(fam);
                opt.controller.element.disabled = !opt.api.isSelectionEditable();

        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'Arial 1', value: 'Arial' },
                    { label: 'Verdana 1', value: 'Verdana' },
                ],
                placeholder: rootScope.lang('Font')
            });
            dropdown.select.on('change', function (e, val, b) {
                if(val) {
                    api.fontFamily(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.addClass('mw-ui-btn-nav mw-editor-state-component')
            var undo = MWEditor.core.button({
                props: {
                    className: 'mdi-undo',
                    tooltip: rootScope.lang('Undo')
                }
            });
            undo.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
                rootScope._syncTextArea();
            });

            var redo = MWEditor.core.button({
                props: {
                    className: 'mdi-redo',
                    tooltip: rootScope.lang('Redo')
                }
            });
            redo.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
                rootScope._syncTextArea();
            });
            this.root.node.appendChild(undo.node);
            this.root.node.appendChild(redo.node);
            $(rootScope.state).on('stateRecord', function(e, data){
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            })
            .on('stateUndo stateRedo', function(e, data){
                if(!data.active || !data.active.target) {
                    undo.node.disabled = !data.hasNext;
                    redo.node.disabled = !data.hasPrev;
                    return;
                }
                if(scope.actionWindow.document.body.contains(data.active.target)) {
                    mw.$(data.active.target).html(data.active.value);
                } else{
                    if(data.active.target.id) {
                        mw.$(scope.actionWindow.document.getElementById(data.active.target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
                // mw.drag.load_new_modules();
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
                $(scope).trigger(e.type, [data]);
            });
            setTimeout(function () {
                var data = rootScope.state.eventData();
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            }, 78);
            return this.root;
        };
        this.element = this.render();
    },
    'ul': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-bulleted'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ul');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(node.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertUnorderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'ol': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-numbered tip',
                    'data-tip': 'Ordered list'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ol');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(paragraph.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertOrderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'indent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-increase',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('indent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'outdent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-decrease',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('outdent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    removeFormat: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-clear',
                    tooltip: 'Remove Format'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('removeFormat');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    unlink: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off', tooltip: 'Unlink'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('unlink');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-text', tooltip: 'Text color'
                }
            });
            el.on('change', function (e, val) {
                console.log(val)
                api.execCommand('foreColor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textBackgroundColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-fill', tooltip: 'Text background color'
                }
            });
            el.on('change', function (e, val) {
                console.log(e, val)
                api.execCommand('backcolor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-table-large', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.insertHTML('<table class="mw-ui-table" border="1" width="100%"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    wordPaste: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-file-word', tooltip: 'Paste from Word'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.saveSelection();
                var dialog;
                var ok = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'btn btn-primary',
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'btn',
                        innerHTML: rootScope.lang('Cancel')
                    }
                });
                var cleanEl = mw.element({
                    props: {
                        contentEditable: true,
                        autofocus: true,
                        style: {
                            height: '250px'
                        }
                    }
                });

                var footer = mw.element();
                cancel.on('click', function (){
                    dialog.remove();
                })
                ok.on('click', function (){
                    var content = cleanEl.html().trim();
                    dialog.remove();
                    api.restoreSelection();
                    if(content){
                        api.insertHTML(api.cleanWord(content));
                    }

                });
                footer.append(cancel);
                footer.append(ok);
                dialog = mw.dialog({
                    content: cleanEl.node,
                    footer: footer.node
                });
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },



};

})();

(() => {
/*!*******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/add.controller.js ***!
  \*******************************************************************/

/*************************************************************
 *
        MWEditor.addController(
            'underline',
            function () {

            }, function () {

            }
        );

        MWEditor.addController({
            name: 'underline',
            render: function () {

            },
            checkSelection: function () {

            }
        })

 **************************************************************/



MWEditor.controllers.editSource = function (scope, api, rootScope) {
    this.render = function () {

        var scope = this;
        var el = MWEditor.core.button({
            props: {
                className: 'mdi mdi-xml',
                tooltip:  'Edit source'
            }
        });
        el.on('mousedown touchstart', function (e) {

            var ok = mw.element('<span class="mw-ui-btn mw-ui-btn-info">'+mw.lang('OK')+'</span>');
            var cancel = mw.element('<span class="mw-ui-btn">'+mw.lang('Cancel')+'</span>');
            var area = mw.element({ tag: 'textarea', props: {
                    className: 'mw-ui-field',
                }});
            area.css({
                height: 400
            })
            area.val(rootScope.$editArea.html());
            var footer = mw.element();
            footer.append(cancel).append(ok)
            var dialog = mw.dialog({
                overlay: true,
                content: area.get(0),
                footer: footer.get(0),
                title: mw.lang('Edit source')
            });

            cancel.on('click', function (){
                dialog.remove()
            });
            ok.on('click', function (){
                rootScope.setContent(area.val(), true);
                dialog.remove();
            });

        });
        return el;
    };
    this.checkSelection = function () {
        return true;
    };
};


MWEditor.addController = function (name, render, checkSelection, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
        dependencies = obj.dependencies;
    }
    if(MWEditor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor');
        return;
    }
    MWEditor.controllers[name] = function () {
        this.render = render;
        if(checkSelection) {
            this.checkSelection = checkSelection;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};


MWEditor.addInteractionController = function (name, render, interact, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        interact = obj.interact;
        dependencies = obj.dependencies;
    }
    if(MWEditor.interactionControls[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    MWEditor.interactionControls[name] = function () {
        this.render = render;
        if(interact) {
            this.interact = interact;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};

})();

(() => {
/*!*************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/interaction-controls.js ***!
  \*************************************************************************/
/*
*
*  interface data {
        target: Element,
        component: Element,
        isImage: boolean,
        event: Event
    };
*
*
* */

MWEditor.interactionControls = {
    linkTooltip: function (rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.element({
                props: {
                    className: 'mw-editor-link-tooltip'
                }
            });
            var urlElement = mw.element({
                tag: 'a',
                props: {
                    className: 'mw-editor-link-tooltip-url',
                    target: 'blank'
                }
            });
            var urlUnlink = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off',
                }
            });

            urlUnlink.on('click', function () {
                rootScope.api.unlink();
            });

            el.urlElement = urlElement;
            el.urlUnlink = urlUnlink;
            el.append(urlElement);
            el.append(urlUnlink);
            el.target = null;
            el.hide();
            return el;
        };
        this.interact = function (data) {
            var tg = mw.tools.firstParentOrCurrentWithTag(data.target,'a');
            if(!tg) {
                this.element.hide();
                return;
            }
            var $target = $(data.target);
            this.$target = $target;
            var css = $target.offset();
            css.top += $target.height();
            this.element.urlElement.html(data.target.href);
            this.element.urlElement.prop('href', data.target.href);
            this.element.css(css).show();
        };
        this.element = this.render();
    },
    image: function (rootScope) {
        this.nodes = [];
        this.render = function () {
            var scope = this;
            var el = mw.element({
                props: {
                    className: 'mw-editor-image-handle-wrap'
                }
            });
            var changeButton = mw.element({
                props: {
                    innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon"><path d="M19,19H5V5H19M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M13.96,12.29L11.21,15.83L9.25,13.47L6.5,17H17.5L13.96,12.29Z" /></svg>',
                    className: 'btn btn-icon',
                    dataset: {
                        tip: rootScope.lang('Change image')
                    }
                }
            });
            changeButton.on('click', function () {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        url = url.toString();
                        scope.$target.attr('src', url);
                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false
                })

            });
            var editButton = mw.element({
                props: {
                    innerHTML: '<i class="mdi mdi-image-edit"></i>',
                    className: 'btn btn-link tip',
                    dataset: {
                        tip: rootScope.lang('Edit image')
                    }
                }
            });
            var nav = mw.element({
                props: {
                    className: 'mw-ui-btn-nav'
                }
            });
            nav.append(changeButton);
            el.append(nav);
            // nav.append(editButton);
            this.nodes.push(el.node, changeButton.node, editButton.node);
            el.hide();
            return el;
        };
        this.interact = function (data) {
            if(mw.tools.firstParentOrCurrentWithClass(data.localTarget, 'mw-editor-image-handle-wrap')) {
                return;
            }
            if(this.nodes.indexOf(data.target) !== -1) {
                this.element.$node.hide();
                return;
            }
            if (data.isImage) {
                var $target = $(data.localTarget);
                this.$target = $target;
                var css = $target.offset();
                css.width = $target.outerWidth();
                css.height = $target.outerHeight();
                this.element.css(css).show();
            } else {
                this.element.hide();
            }
        };
        this.element = this.render();
    },
    tableManager: function(rootScope){
        var lscope = this;
        this.interact = function (data) {
            if (!data.eventIsActionLike) { return; }
            var td = mw.tools.firstParentOrCurrentWithTag(data.localTarget, 'td');
            if (td) {
                var $target = $(td);
                this.$target = $target;
                var css = $target.offset();
                css.top -= lscope.element.node.offsetHeight;
                this.element.$node.css(css).show();
            } else {
                this.element.$node.hide();
            }
        };

        this._afterAction = function () {
            this.element.$node.hide();
            rootScope.state.record({
                target: rootScope.$editArea[0],
                value: rootScope.$editArea[0].innerHTML
            });
        };

        this.render = function () {
            var root = mw.element({
                props: {
                    className: 'mw-editor-table-manager'
                }
            });
            var bar = mw.bar();
            bar.createRow();
            root.append(bar.bar);

            var insertDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row Above', value: {action: 'insertRow', type: 'above'} },
                    { label: 'Row Under', value: {action: 'insertRow', type: 'under'} },
                    { label: 'Column on the left', value: {action: 'insertColumn', type: 'left'} },
                    { label: 'Column on the right', value: {action: 'insertColumn', type: 'right'} },
                ],
                placeholder: 'Insert'
            });

            insertDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action](data.value.type);
                lscope._afterAction();
            });
            var deletetDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row', value: {action: 'deleteRow'} },
                    { label: 'Column', value: {action: 'deleteColumn'} },
                ],
                placeholder: 'Delete'
            });

            deletetDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action]();
                lscope._afterAction()
            });

            bar.add(insertDD.root.node);
            bar.add(deletetDD.root.node);
            root.hide();

            return root;
        };

        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            cell.parentNode.remove();
        };


        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = mw.tools.index(cell),
                body = cell.parentNode.parentNode,
                rows = mw.$(body).children('tr'),
                l = rows.length,
                i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                row.getElementsByTagName('td')[index].remove();
            }
        };

        this.getActiveCell = function () {
            var node = rootScope.api.elementNode( rootScope.getSelection().focusNode);
            return mw.tools.firstParentOrCurrentWithTag(node,'td');
        };

        this.insertColumn = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                cell = mw.$(row).children('td')[index];
                if (dir === 'left' || dir === 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir === 'right' || dir === 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        };
        this.insertRow = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length,
                html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            html = '<tr>' + html + '</tr>';
            if (dir === 'under' || dir === 'both') {
                mw.$(parent).after(html)
            }
            if (dir === 'above' || dir === 'both') {
                mw.$(parent).before(html)
            }
        };
        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            mw.$(cell.parentNode).remove();
        };
        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        };

        this.setStyle = function (cls, cell) {
            cell = cell || this.getActiveCell();
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        };
        this.element = this.render();
    }

};

})();

(() => {
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/i18n.js ***!
  \*********************************************************/
MWEditor.i18n = {
    en: {
        'Change': 'Change',
        'Edit image': 'Edit',
    }
};

})();

(() => {
/*!*****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/liveeditmode.js ***!
  \*****************************************************************/

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
            MWEditor.liveeditMode._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
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







})();

/******/ })()
;
//# sourceMappingURL=editor.js.map
