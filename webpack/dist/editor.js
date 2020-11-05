/******/ (() => { // webpackBootstrap
(() => {
/*!********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/add.controller.js ***!
  \********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

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
/*!*********************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/api.js ***!
  \*********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */









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
                        var el = mwd.createElement(all[i].getAttribute('data-type') === 'ul' ? 'ul' : 'ol');
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
/*!*********************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/bar.js ***!
  \*********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/controllers.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
                    footer: false
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
                api.fontSize(val.value);
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
                api.fontFamily(val.value);
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
                api.execCommand('insertUnorderedList');
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
                api.execCommand('insertOrderedList');
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
                var ok = MWEditor.core.button({
                    props: {
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.button({
                    props: {
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
/*!**********************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/core.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
                className: 'mw-editor-controller-component mw-editor-controller-component-select'
            }
        });
        var displayValNode = MWEditor.core.button({
            props: {
                className: 'mw-editor-select-display-value',
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

        this.select.on('click', function (e){
            e.stopPropagation();
            var wrapper = mw.tools.firstParentWithClass(this.node, 'mw-editor-wrapper');
            if (wrapper) {
                var edOff = wrapper.getBoundingClientRect();
                var selOff = this.node.getBoundingClientRect();
                this.valueHolder.css({
                    maxHeight: edOff.height - (selOff.top - edOff.top)
                });
            }
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
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/editor.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */










var EditorPredefinedControls = {
    'default': [
        [ 'bold', 'italic', 'underline' ],
    ],
    smallEditorDefault: [
        ['bold', 'italic', '|', 'link']
    ]
};

var MWEditor = function (options) {
    var defaults = {
        regions: null,
        document: document,
        executionDocument: document,
        mode: 'div', // iframe | div | document
        controls: 'default',
        smallEditor: false,
        scripts: [],
        cssFiles: [],
        content: '',
        url: null,
        skin: 'default',
        state: null,
        iframeAreaSelector: null,
        activeClass: 'active-control',
        interactionControls: [
            'image', 'linkTooltip', 'tableManager'
        ],
        language: 'en',
        rootPath: mw.settings.modules_url + 'microweber/api/editor',
        editMode: 'normal', // normal | liveedit
        bar: null,
    };

    this.actionWindow = window;

    options = options || {};

    this.settings = mw.object.extend({}, defaults, options);


    if (typeof this.settings.controls === 'string') {
        this.settings.controls = EditorPredefinedControls[this.settings.controls] || EditorPredefinedControls.default;
    }

    if(!!this.settings.smallEditor) {
        if(this.settings.smallEditor === true) {
            this.settings.smallEditor = EditorPredefinedControls.smallEditorDefault;
        } else if (typeof this.settings.smallEditor === 'string') {
            this.settings.smallEditor = EditorPredefinedControls[this.settings.smallEditor] || EditorPredefinedControls.smallEditorDefault;
        }
    }

    this.document = this.settings.document;

    var scope = this;

    if(!this.settings.selector && this.settings.element){
        this.settings.selector = this.settings.element;
    }

    if(!this.settings.selector && this.settings.mode === 'document'){
        this.settings.selector = this.document.body;
    }
    if(!this.settings.selector){
        console.warn('MWEditor - selector not specified');
        return;
    }

    this.settings.selectorNode = mw.$(this.settings.selector)[0];

    if (this.settings.selectorNode) {
        this.settings.selectorNode.__MWEditor = this;
    }

    this.settings.isTextArea = this.settings.selectorNode.nodeName && this.settings.selectorNode.nodeName === 'TEXTAREA';


    this.getSelection = function () {
        return scope.actionWindow.getSelection();
    };

    this.selection = this.getSelection();

    this._interactionTime = new Date().getTime();

    this.interactionControls = [];
    this.createInteractionControls = function () {
        this.settings.interactionControls.forEach(function(ctrl){
            if (MWEditor.interactionControls[ctrl]) {
                var int = new MWEditor.interactionControls[ctrl](scope, scope);
                if(!int.element){
                    int.element = int.render();
                }
                scope.actionWindow.document.body.appendChild(int.element.node);
                scope.interactionControls.push(int);
            }
        });
    };

    this.lang = function (key) {
        if (MWEditor.i18n[this.settings.language] && MWEditor.i18n[this.settings.language][key]) {
            return  MWEditor.i18n[this.settings.language][key];
        }
        //console.warn(key + ' is not specified for ' + this.settings.language + ' language');
        return key;
    };

    this.require = function () {

    };

    this.addDependencies = function (obj){
        this.controls.forEach(function (ctrl) {
            if (ctrl.dependencies) {
                ctrl.dependencies.forEach(function (dep) {
                    scope.addDependency(dep);
                });
            }
        });
        this.interactionControls.forEach(function (int) {
            if (int.dependencies) {
                int.dependencies.forEach(function (dep) {
                    scope.addDependency(dep);
                });
            }
        });
        var node = scope.actionWindow.document.createElement('link');
        node.href = this.settings.rootPath + '/area-styles.css';
        node.type = 'text/css';
        node.rel = 'stylesheet';
        scope.actionWindow.document.body.appendChild(node);
    };
    this.addDependency = function (obj) {
        targetWindow = obj.targetWindow || scope.actionWindow;
        if (!type) {
            type = url.split('.').pop();
        }
        if(!type || !url) return;
        var node;
        if(type === 'css') {
            node = targetWindow.document.createElement('link');
            node.rel = 'stylesheet';
            node.href = url;
            node.type = 'text/css';
        } else if(type === 'js') {
            node = targetWindow.document.createElement('script');
            node.src = url;
        }
        targetWindow.document.body.appendChild(node);
    };

    this.interactionControlsRun = function (data) {
        scope.interactionControls.forEach(function (ctrl) {
            ctrl.interact(data);
        });
    };


    this.initInteraction = function () {
        var ait = 100,
            currt = new Date().getTime();
        this.interactionData = {};
        $(scope.actionWindow.document).on('selectionchange', function(e){
            $(scope).trigger('selectionchange', [{
                event: e,
                interactionData: scope.interactionData
            }]);
        });
        var max = 78;
        scope.$editArea.on('touchstart touchend click keydown execCommand mousemove touchmove', function(e){
            var eventIsActionLike = e.type === 'click' || e.type === 'execCommand' || e.type === 'keydown';
            var event = e.originaleEvent ? e.originaleEvent : e;
            var localTarget = event.target;

            var wTarget = localTarget;
            if(eventIsActionLike) {
                var shouldCloseSelects = false;
                while (wTarget) {
                    var cc = wTarget.classList;
                    if(cc) {
                        if(cc.contains('mw-editor-controller-component-select')) {
                            break;
                        } else if(cc.contains('mw-bar-control-item-group')) {
                            break;
                        } else if(cc.contains('mw-editor-area')) {
                            shouldCloseSelects = true;
                            break;
                        } else if(cc.contains('mw-editor-frame-area')) {
                            shouldCloseSelects = true;
                            break;
                        } else if(cc.contains('mw-editor-wrapper')) {
                            shouldCloseSelects = true;
                            break;
                        }
                    }
                    wTarget = wTarget.parentNode;
                }
                if(shouldCloseSelects) {
                    MWEditor.core._preSelect();
                }
            }
            var time = new Date().getTime();
            if(eventIsActionLike || (time - scope._interactionTime) > max){
                if (e.pageX) {
                    scope.interactionData.pageX = e.pageX;
                    scope.interactionData.pageY = e.pageY;
                }
                scope._interactionTime = time;
                scope.selection = scope.getSelection();
                if (scope.selection.rangeCount === 0) {
                    return;
                }
                var target = scope.api.elementNode( scope.selection.getRangeAt(0).commonAncestorContainer );
                var css = mw.CSSParser(target);
                var api = scope.api;


                var iterData = {
                    selection: scope.selection,
                    target: target,
                    localTarget: localTarget,
                    isImage: localTarget.nodeName === 'IMG' || target.nodeName === 'IMG',
                    css: css.get,
                    cssNative: css.css,
                    event: event,
                    api: api,
                    scope: scope,
                    isEditable: scope.api.isSelectionEditable(),
                    eventIsActionLike: eventIsActionLike,
                };

                scope.interactionControlsRun(iterData);
                scope.controls.forEach(function (ctrl) {
                    if(ctrl.checkSelection) {
                        ctrl.checkSelection({
                            selection: scope.selection,
                            controller: ctrl,
                            target: target,
                            css: css.get,
                            cssNative: css.css,
                            api: api,
                            eventIsActionLike: eventIsActionLike,
                            scope: scope,
                            isEditable: scope.api.isSelectionEditable()
                        });
                    }
                });
            }
        });
        this.createInteractionControls()
    };

    this._preventEvents = [];
    this.preventEvents = function () {
        var node;
        if(this.area && this._preventEvents.indexOf(this.area.node) === -1) {
            this._preventEvents.push(this.area.node);
            node = this.area.node;
        } else if(scope.$iframeArea && this._preventEvents.indexOf(scope.$iframeArea[0]) === -1) {
            this._preventEvents.push(scope.$iframeArea[0]);
            node = scope.$iframeArea[0];
        }
        var ctrlDown = false;
        var ctrlKey = 17, vKey = 86, cKey = 67, zKey = 90;
        node.onkeydown = function (e) {
            if (e.keyCode === ctrlKey || e.keyCode === 91) {
                ctrlDown = true;
            }
            if ((ctrlDown && e.keyCode === zKey) /*|| (ctrlDown && e.keyCode === vKey)*/ || (ctrlDown && e.keyCode === cKey)) {
                e.preventDefault();
                return false;
            }
        };
        node.onkeyup = function(e) {
            if (e.keyCode === 17 || e.keyCode === 91) {
                ctrlDown = false;
            }
        };
    };
    this.initState = function () {
        this.state = this.settings.state || (new mw.State());
    };

    this.controllerActive = function (node, active) {
        node.classList[active ? 'add' : 'remove'](this.settings.activeClass);
    };

    this.createFrame = function () {
        this.frame = this.document.createElement('iframe');
        this.frame.className = 'mw-editor-frame';
        this.frame.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
        this.frame.allowFullscreen = true;
        this.frame.scrolling = "yes";
        this.frame.width = "100%";
        this.frame.frameBorder = "0";
        if (this.settings.url) {
            this.frame.src = this.settings.url;
        } else {

        }

        $(this.frame).on('load', function () {
            if (!scope.settings.iframeAreaSelector) {
                var area = document.createElement('div');
                area.style.outline = 'none';
                area.className = 'mw-editor-frame-area';
                scope.settings.iframeAreaSelector =  '.' + area.className;
                this.contentWindow.document.body.append(area);
                area.style.minHeight = '100px';
            }
            scope.$iframeArea = $(scope.settings.iframeAreaSelector, this.contentWindow.document);

            scope.$iframeArea.html(scope.settings.content || '');
            scope.$iframeArea.on('input', function () {
                scope.registerChange();
            });
            scope.actionWindow = this.contentWindow;
            scope.$editArea = scope.$iframeArea;
            mw.tools.iframeAutoHeight(scope.frame);

            scope.preventEvents();
            $(scope).trigger('ready');
        });
        this.wrapper.appendChild(this.frame);
    };

    this.createWrapper = function () {
        this.wrapper = this.document.createElement('div');
        this.wrapper.className = 'mw-editor-wrapper mw-editor-' + this.settings.skin;
    };

    this._syncTextArea = function (content) {
        content = content || scope.$editArea.html();
        if (scope.settings.isTextArea) {
            $(scope.settings.selectorNode).val(content);
            $(scope.settings.selectorNode).trigger('change');
        }
    };

    this._registerChangeTimer = null;
    this.registerChange = function (content) {
        clearTimeout(this._registerChangeTimer);
        this._registerChangeTimer = setTimeout(function () {
            content = content || scope.$editArea.html();
            scope._syncTextArea(content);
            $(scope).trigger('change', [content]);
        }, 78);
    };

    this.createArea = function () {
        var content = this.settings.content || '';
        if(!content && this.settings.isTextArea) {
            content = this.settings.selectorNode.value;
        }
        this.area = mw.element({
            props: { className: 'mw-editor-area', innerHTML: content }
        });
        this.area.node.contentEditable = true;
        this.area.node.oninput = function() {
            scope.registerChange();
        };
        this.wrapper.appendChild(this.area.node);
        scope.$editArea = this.area.$node;
        scope.preventEvents();
        $(scope).trigger('ready');
    };

    this.documentMode = function () {
        if(!this.settings.regions) {
            console.warn('Regions are not defined in Document mode.');
            return;
        }
        this.$editArea = $(this.document.body);
        this.wrapper.className += ' mw-editor-wrapper-document-mode';
        mw.$(this.document.body).append(this.wrapper)[0].mwEditor = this;
        $(scope).trigger('ready');
    };

    this.setContent = function (content, trigger) {
        if(typeof trigger === 'undefined'){
            trigger = true;
        }
        this.$editArea.html(content);
        if(trigger){
            scope.registerChange(content);
        }
    };

    this.nativeElement = function (node) {
        return node.node ? node.node : node;
    };

    this.controls = [];
    this.api = MWEditor.api(this);

    this._addControllerGroups = [];
    this.addControllerGroup = function (obj, row, bar) {
        if(!bar) {
            bar = 'bar';
        }
        var group = obj.group;
        var id = mw.id('mw.editor-group-');
        var el = mw.element({
            props: {
                className: 'mw-bar-control-item mw-bar-control-item-group',
                id:id
            }
        });

        var groupel = mw.element({
                props:{
                    className: 'mw-bar-control-item-group-contents'
                }
            });

        var icon = MWEditor.core.button({
            tag:'span',
            props: {
                className: ' mw-editor-group-button',
                innerHTML: '<span class="mw-editor-group-button-caret"></span>'
            }
        });
        if(group.icon) {
            icon.prepend('<span class="' + group.icon + ' mw-editor-group-button-icon"></span>');
            icon.on('click', function () {
                MWEditor.core._preSelect(this.parentNode);
                this.parentNode.classList.toggle('active');
            });

        } else if(group.controller) {
            if(scope.controllers[group.controller]){
                var ctrl = new scope.controllers[group.controller](scope, scope.api, scope);
                scope.controls.push(ctrl);
                icon.prepend(ctrl.element);
                mw.element(icon.get(0).querySelector('.mw-editor-group-button-caret')).on('click', function () {
                    MWEditor.core._preSelect(this.parentNode.parentNode);
                    this.parentNode.parentNode.classList.toggle('active');
                });
            } else if(scope.controllersHelpers[group.controller]){
                groupel.append(this.controllersHelpers[group.controller]());
            }
        }
        el.append(icon);

        groupel.on('click', function (){
            MWEditor.core._preSelect();
        });

        var media;
        obj.group.when = obj.group.when || 9999;
        // at what point group buttons become like dropdown - by default it's always a dropdown
        if (obj.group.when) {
            if (typeof obj.group.when === 'number') {
                media = '(max-width: ' + obj.group.when + 'px)';
            } else {
                media = obj.group.when;
            }
        }



        el.append(groupel);
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        group.controls.forEach(function (name) {
            if(scope.controllers[name]){
                var ctrl = new scope.controllers[name](scope, scope.api, scope);
                scope.controls.push(ctrl);
                groupel.append(ctrl.element);
            } else if(scope.controllersHelpers[name]){
                groupel.append(this.controllersHelpers[name]());
            }
        });

        scope[bar].add(el, row);

        this._addControllerGroups.push({
            el: el,
            row: row,
            obj: obj,
            media: media
        });
        return el;
    };

    this.controlGroupManager = function () {
        var check = function() {
            var i = 0, l = scope._addControllerGroups.length;
            for ( ; i< l ; i++) {
                var item = scope._addControllerGroups[i];
                var media = item.media;
                if(media) {
                    var match = scope.document.defaultView.matchMedia(media);
                    item.el.$node[match.matches ? 'addClass' : 'removeClass']('mw-editor-control-group-media-matches');
                }
            }
        };
        $(window).on('load resize orientationchange', function () {
            check();
        });
        check();
    };

    this.addController = function (name, row, bar) {
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        if (!bar) {
            bar = 'bar';
        }
        if(this.controllers[name]){
            var ctrl = new this.controllers[name](scope, scope.api, scope);
            if (!ctrl.element) {
                ctrl.element = ctrl.render();
            }

            this.controls.push(ctrl);
            this[bar].add(ctrl.element, row);
        } else if(this.controllersHelpers[name]){
            this[bar].add(this.controllersHelpers[name](), row);
        }
    };

    this.createSmallEditor = function () {
        if (!this.settings.smallEditor) {
            return;
        }
        this.smallEditor = mw.element({
            props: {
                className: 'mw-small-editor mw-small-editor-skin-' + this.settings.skin
            }
        });

        this.smallEditorBar = mw.bar();

        this.smallEditor.hide();
        this.smallEditor.append(this.smallEditorBar.bar);
        for (var i1 = 0; i1 < this.settings.smallEditor.length; i1++) {
            var item = this.settings.smallEditor[i1];
            this.smallEditorBar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    scope.addController(item[i2], i1, 'smallEditorBar');
                } else if( typeof item[i2] === 'object') {
                    scope.addControllerGroup(item[i2], i1, 'smallEditorBar');
                }
            }
        }
        scope.$editArea.on('mouseup touchend', function (e, data) {
            if (scope.selection && !scope.selection.isCollapsed) {
                if(!mw.tools.hasParentsWithClass(e.target, 'mw-bar')){
                    scope.smallEditor.css({
                        top: scope.interactionData.pageY - scope.smallEditor.$node.height() - 20,
                        left: scope.interactionData.pageX,
                        display: 'block'
                    });
                }
            } else {
                scope.smallEditor.hide();
            }
        });
        this.actionWindow.document.body.appendChild(this.smallEditor.node);
    };
    this.createBar = function () {
        this.bar = mw.settings.bar || mw.bar();
        for (var i1 = 0; i1 < this.settings.controls.length; i1++) {
            var item = this.settings.controls[i1];
            this.bar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    scope.addController(item[i2], i1);
                } else if( typeof item[i2] === 'object') {
                    scope.addControllerGroup(item[i2], i1);
                }
            }
        }
        this.wrapper.appendChild(this.bar.bar);
    };

    this._onReady = function () {
        $(this).on('ready', function () {
            scope.initInteraction();
            scope.api.execCommand('enableObjectResizing', false, 'false');
            scope.api.execCommand('2D-Position', false, false);
            scope.api.execCommand("enableInlineTableEditing", null, false);
            if(!scope.state.hasRecords()){
                scope.state.record({
                    $initial: true,
                    target: scope.$editArea[0],
                    value: scope.$editArea[0].innerHTML
                });
            }
            scope.settings.regions = scope.settings.regions || scope.$editArea;
            $(scope.settings.regions, scope.actionWindow.document).attr('contenteditable', true);
            if (scope.settings.editMode === 'liveedit') {
                scope.liveEditMode();
            }
            var css = {};
            if(scope.settings.minHeight) {
                css.minHeight = scope.settings.minHeight;
            }
            if(scope.settings.maxHeight) {
                css.maxHeight = scope.settings.maxHeight;
            }
            if(scope.settings.height) {
                css.height = scope.settings.height;
            }
            if(scope.settings.minWidth) {
                css.minWidth = scope.settings.minWidth;
            }
            if(scope.settings.maxWidth) {
                css.maxWidth = scope.settings.maxWidth;
            }
            if(scope.settings.width) {
                css.width = scope.settings.width;
            }
            scope.$editArea.css(css);
            scope.addDependencies();
            scope.createSmallEditor();

        });
    };

    this.liveEditMode = function () {
        this.liveedit = MWEditor.liveeditMode(this.actionWindow.document.body, scope);
    };

    this._initInputRecordTime = null;
    this._initInputRecord = function () {
        $(this).on('change', function (e, html) {
            clearTimeout(scope._initInputRecordTime);
            scope._initInputRecordTime = setTimeout(function () {
                scope.state.record({
                    target: scope.$editArea[0],
                    value: html
                });
            }, 600);

        });
    };

    this.__insertEditor = function () {
        if (this.settings.isTextArea) {
            var el = mw.$(this.settings.selector);
            el[0].mwEditor = this;
            el.hide();
            var areaWrapper = mw.element();
            areaWrapper.node.mwEditor = this;
            el.after(areaWrapper.node);
            areaWrapper.append(this.wrapper);
        } else {
            mw.$(this.settings.selector).append(this.wrapper)[0].mwEditor = this;
        }
    };

    this.init = function () {
        this.controllers = MWEditor.controllers;
        this.controllersHelpers = MWEditor.controllersHelpers;
        this.initState();
        this._onReady();
        this.createWrapper();
        this.createBar();

        if (this.settings.mode === 'div') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        } else if (this.settings.mode === 'document') {
            this.documentMode();
        }
        if (this.settings.mode !== 'document') {
            this._initInputRecord();
            this.__insertEditor();
        }
        this.controlGroupManager();

    };
    this.init();
};

if (window.mw) {
   mw.Editor = function (options){
       options = options || {};
       if(!options.selector && options.element){
           options.selector = options.element;
       }
       if(options.selector){
           if (typeof options.selector === 'string') {
               options.selector = (options.document || document).querySelector(options.selector);
           }
           if (options.selector && options.selector.__MWEditor) {
               console.log(options.selector.__MWEditor)
               return options.selector.__MWEditor;
           }
       }
       return new MWEditor(options);
   };
}



mw.require('autocomplete.js');
mw.require('filepicker.js');

mw.require('form-controls.js');
mw.require('link-editor.js');

//

mw.require('state.js');

/*mw.require('editor/bar.js');
mw.require('editor/api.js');
mw.require('editor/helpers.js');
mw.require('editor/tools.js');
mw.require('editor/core.js');
mw.require('editor/controllers.js');
mw.require('editor/add.controller.js');
mw.require('editor/interaction-controls.js');
mw.require('editor/i18n.js');
mw.require('editor/liveeditmode.js');*/
mw.require('control_box.js');

})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/helpers.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
  !*** ../userfiles/modules/microweber/api/editor/i18n.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
MWEditor.i18n = {
    en: {
        'Change': 'Change',
        'Edit image': 'Edit',
    }
};

})();

(() => {
/*!**************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/interaction-controls.js ***!
  \**************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
            var $target = mw.element(data.target);
            this.$target = $target;
            var css = $target.offset();
            css.top += $target.outerHeight();
            this.element.urlElement.html(data.target.href);
            this.element.urlElement.prop('href', data.target.href);
            this.element.$node.css(css).show();
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
                    innerHTML: '<i class="mdi mdi-folder-multiple-image"></i>',
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
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
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
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
                this.element.$node.css(css).show();
            } else {
                this.element.$node.hide();
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
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/liveeditmode.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element']) && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
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

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/editor/tools.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
MWEditor.tools = {

};

})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvZWRpdG9yL2FkZC5jb250cm9sbGVyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9lZGl0b3IvYXBpLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9lZGl0b3IvYmFyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9lZGl0b3IvY29udHJvbGxlcnMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2VkaXRvci9jb3JlLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9lZGl0b3IvZWRpdG9yLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9lZGl0b3IvaGVscGVycy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvZWRpdG9yL2kxOG4uanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2VkaXRvci9pbnRlcmFjdGlvbi1jb250cm9scy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvZWRpdG9yL2xpdmVlZGl0bW9kZS5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvZWRpdG9yL3Rvb2xzLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhOztBQUViO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjs7QUFFQTtBQUNBLFNBQVM7O0FBRVQ7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ25FQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSw4QkFBOEIsb0JBQW9CO0FBQ2xEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLHNCQUFzQixnQkFBZ0I7QUFDdEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxtQ0FBbUMsdUJBQXVCO0FBQzFEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxpQkFBaUI7O0FBRWpCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDO0FBQzVDO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsNkhBQTZIO0FBQzdIO0FBQ0Esb0VBQW9FO0FBQ3BFLHFEQUFxRCxJQUFJO0FBQ3pELCtEQUErRDtBQUMvRDtBQUNBLDREQUE0RDtBQUM1RDtBQUNBLHlEQUF5RCxHQUFHO0FBQzVELGdFQUFnRSxHQUFHO0FBQ25FLDJEQUEyRCxHQUFHO0FBQzlELHFEQUFxRCxJQUFJO0FBQ3pEO0FBQ0E7QUFDQTtBQUNBLHVEQUF1RCxJQUFJO0FBQzNEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNERBQTRELHVCQUF1QjtBQUNuRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseURBQXlELGlCQUFpQjtBQUMxRTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQkFBc0IsT0FBTztBQUM3QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLE9BQU87QUFDN0I7QUFDQTtBQUNBO0FBQ0EsMEJBQTBCLE9BQU87QUFDakM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx1QkFBdUIsT0FBTztBQUM5QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDeGRBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1DQUFtQztBQUNuQzs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOzs7Ozs7Ozs7O0FDakVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhLG1EQUFtRDtBQUNoRSxhQUFhLHlEQUF5RDtBQUN0RSxhQUFhLHNEQUFzRDtBQUNuRSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSwyQkFBMkIsd0JBQXdCO0FBQ25EO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQSxpQkFBaUI7OztBQUdqQixhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUIseUJBQXlCO0FBQzlDLHFCQUFxQiwyQkFBMkI7QUFDaEQscUJBQXFCLDJCQUEyQjtBQUNoRCxxQkFBcUIsMkJBQTJCO0FBQ2hELHFCQUFxQiwyQkFBMkI7QUFDaEQscUJBQXFCLDJCQUEyQjtBQUNoRCxxQkFBcUIsMkJBQTJCO0FBQ2hELHFCQUFxQiwyQkFBMkI7QUFDaEQscUJBQXFCLDJCQUEyQjtBQUNoRCxxQkFBcUIsMkJBQTJCO0FBQ2hEO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7O0FBRUE7QUFDQSxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLHVDQUF1QztBQUNwRCxhQUFhLGlDQUFpQztBQUM5QyxhQUFhLCtCQUErQjtBQUM1QyxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCLGdDQUFnQztBQUMzRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCLG1DQUFtQztBQUN4RCxxQkFBcUIsdUNBQXVDO0FBQzVEO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7Ozs7QUFJTDs7Ozs7Ozs7OztBQ2hvQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx3Q0FBd0M7QUFDeEM7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx3Q0FBd0M7O0FBRXhDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7O0FBRUw7QUFDQSxrQkFBa0IsNEJBQTRCO0FBQzlDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsdUJBQXVCLHlCQUF5QjtBQUNoRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsYUFBYTs7QUFFYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxlQUFlLE9BQU87QUFDdEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ3JJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQSx1Q0FBdUM7OztBQUd2QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxVQUFVLGlCQUFpQixXQUFXO0FBQ2hGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esb0JBQW9CO0FBQ3BCLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsU0FBUzs7QUFFVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUJBQW1CLE9BQU87QUFDMUI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDs7QUFFQTtBQUNBO0FBQ0Esd0JBQXdCLHVDQUF1QztBQUMvRDtBQUNBO0FBQ0EsNEJBQTRCLGtCQUFrQjtBQUM5QztBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLHdCQUF3QixvQ0FBb0M7QUFDNUQ7QUFDQTtBQUNBLDRCQUE0QixrQkFBa0I7QUFDOUM7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTs7QUFFYixTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUNBQXFDO0FBQ3JDOzs7Ozs7Ozs7O0FDenRCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOzs7Ozs7Ozs7O0FDVEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDTEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCOztBQUVqQixhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSwwQ0FBMEMsUUFBUTtBQUNsRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EscUJBQXFCLDZCQUE2QixtQ0FBbUMsRUFBRTtBQUN2RixxQkFBcUIsNkJBQTZCLG1DQUFtQyxFQUFFO0FBQ3ZGLHFCQUFxQixzQ0FBc0MscUNBQXFDLEVBQUU7QUFDbEcscUJBQXFCLHVDQUF1QyxzQ0FBc0MsRUFBRTtBQUNwRztBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxxQkFBcUIsdUJBQXVCLG9CQUFvQixFQUFFO0FBQ2xFLHFCQUFxQiwwQkFBMEIsdUJBQXVCLEVBQUU7QUFDeEU7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0EsaURBQWlEO0FBQ2pEO0FBQ0E7QUFDQSxnREFBZ0Q7QUFDaEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekIsbUNBQW1DO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7Ozs7Ozs7Ozs7QUNsVEE7QUFDQTtBQUNBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWEsT0FBTztBQUNwQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYSxTQUFTO0FBQ3RCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBO0FBQ0E7QUFDQSw0Q0FBNEM7QUFDNUMsMENBQTBDO0FBQzFDLDJDQUEyQztBQUMzQywwQ0FBMEM7QUFDMUMsMENBQTBDO0FBQzFDO0FBQ0EsU0FBUztBQUNUO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QjtBQUN2QjtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBCQUEwQixTQUFTO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLHNCQUFzQixPQUFPO0FBQzdCO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0lBQXNJO0FBQ3RJLHdEQUF3RCxNQUFNLE1BQU0sTUFBTTtBQUMxRTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2QkFBNkI7QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQkFBc0Isc0JBQXNCO0FBQzVDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLCtFQUErRTtBQUMvRTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlDQUFpQztBQUNqQztBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7QUNsb0JBOztBQUVBIiwiZmlsZSI6ImVkaXRvci5qcyIsInNvdXJjZXNDb250ZW50IjpbIlxuLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKipcbiAqXG4gICAgICAgIE1XRWRpdG9yLmFkZENvbnRyb2xsZXIoXG4gICAgICAgICAgICAndW5kZXJsaW5lJyxcbiAgICAgICAgICAgIGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgfSwgZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICk7XG5cbiAgICAgICAgTVdFZGl0b3IuYWRkQ29udHJvbGxlcih7XG4gICAgICAgICAgICBuYW1lOiAndW5kZXJsaW5lJyxcbiAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgY2hlY2tTZWxlY3Rpb246IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuXG4gKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovXG5cblxuTVdFZGl0b3IuYWRkQ29udHJvbGxlciA9IGZ1bmN0aW9uIChuYW1lLCByZW5kZXIsIGNoZWNrU2VsZWN0aW9uLCBkZXBlbmRlbmNpZXMpIHtcbiAgICBpZiAoTVdFZGl0b3IuY29udHJvbGxlcnNbbmFtZV0pIHtcbiAgICAgICAgY29uc29sZS53YXJuKG5hbWUgKyAnIGFscmVhZHkgZGVmaW5lZCcpO1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIGlmICh0eXBlb2YgbmFtZSA9PT0gJ29iamVjdCcpIHtcbiAgICAgICAgdmFyIG9iaiA9IG5hbWU7XG4gICAgICAgIG5hbWUgPSBvYmoubmFtZTtcbiAgICAgICAgcmVuZGVyID0gb2JqLnJlbmRlcjtcbiAgICAgICAgY2hlY2tTZWxlY3Rpb24gPSBvYmouY2hlY2tTZWxlY3Rpb247XG4gICAgICAgIGRlcGVuZGVuY2llcyA9IG9iai5kZXBlbmRlbmNpZXM7XG4gICAgfVxuICAgIGlmKE1XRWRpdG9yLmNvbnRyb2xsZXJzW25hbWVdKSB7XG4gICAgICAgIGNvbnNvbGUud2FybihuYW1lICsgJyBjb250cm9sbGVyIGlzIGFscmVhZHkgcmVnaXN0ZXJlZCBpbiB0aGUgZWRpdG9yJyk7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG4gICAgTVdFZGl0b3IuY29udHJvbGxlcnNbbmFtZV0gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMucmVuZGVyID0gcmVuZGVyO1xuICAgICAgICBpZihjaGVja1NlbGVjdGlvbikge1xuICAgICAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGNoZWNrU2VsZWN0aW9uO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgICAgIHRoaXMuZGVwZW5kZW5jaWVzID0gZGVwZW5kZW5jaWVzO1xuICAgIH07XG59O1xuXG5cbk1XRWRpdG9yLmFkZEludGVyYWN0aW9uQ29udHJvbGxlciA9IGZ1bmN0aW9uIChuYW1lLCByZW5kZXIsIGludGVyYWN0LCBkZXBlbmRlbmNpZXMpIHtcbiAgICBpZiAoTVdFZGl0b3IuY29udHJvbGxlcnNbbmFtZV0pIHtcbiAgICAgICAgY29uc29sZS53YXJuKG5hbWUgKyAnIGFscmVhZHkgZGVmaW5lZCcpO1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIGlmICh0eXBlb2YgbmFtZSA9PT0gJ29iamVjdCcpIHtcbiAgICAgICAgdmFyIG9iaiA9IG5hbWU7XG4gICAgICAgIG5hbWUgPSBvYmoubmFtZTtcbiAgICAgICAgcmVuZGVyID0gb2JqLnJlbmRlcjtcbiAgICAgICAgaW50ZXJhY3QgPSBvYmouaW50ZXJhY3Q7XG4gICAgICAgIGRlcGVuZGVuY2llcyA9IG9iai5kZXBlbmRlbmNpZXM7XG4gICAgfVxuICAgIGlmKE1XRWRpdG9yLmludGVyYWN0aW9uQ29udHJvbHNbbmFtZV0pIHtcbiAgICAgICAgY29uc29sZS53YXJuKG5hbWUgKyAnIGNvbnRyb2xsZXIgaXMgYWxyZWFkeSByZWdpc3RlcmVkIGluIHRoZSBlZGl0b3InKVxuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIE1XRWRpdG9yLmludGVyYWN0aW9uQ29udHJvbHNbbmFtZV0gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMucmVuZGVyID0gcmVuZGVyO1xuICAgICAgICBpZihpbnRlcmFjdCkge1xuICAgICAgICAgICAgdGhpcy5pbnRlcmFjdCA9IGludGVyYWN0O1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgICAgIHRoaXMuZGVwZW5kZW5jaWVzID0gZGVwZW5kZW5jaWVzO1xuICAgIH07XG59O1xuIiwiXG5cblxuXG5cblxuXG5cblxuTVdFZGl0b3IuYXBpID0gZnVuY3Rpb24gKHNjb3BlKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgICAgZ2V0U2VsZWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gc2NvcGUuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgIH0sXG4gICAgICAgIGVhY2hSYW5nZTogZnVuY3Rpb24gKGMpe1xuICAgICAgICAgICAgdmFyIHNlbCA9IHNjb3BlLmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgaWYoc2VsLnJhbmdlQ291bnQgJiYgYykge1xuICAgICAgICAgICAgICAgIGZvcih2YXIgaSA9IDA7IGkgPCBzZWwucmFuZ2VDb3VudDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KGkpO1xuICAgICAgICAgICAgICAgICAgICBjLmNhbGwoc2NvcGUsIHJhbmdlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIGdldFNlbGVjdGlvbkhUTUw6IGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgdmFyIHNlbCA9IHNjb3BlLmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgdmFyIGh0bWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGlmKHNlbC5yYW5nZUNvdW50KSB7XG4gICAgICAgICAgICAgICAgdmFyIGZyYWcgPSBzZWwuZ2V0UmFuZ2VBdCgwKS5jbG9uZUNvbnRlbnRzKCk7XG4gICAgICAgICAgICAgICAgd2hpbGUgKGZyYWcuZmlyc3RDaGlsZCkge1xuICAgICAgICAgICAgICAgICAgICBodG1sLmFwcGVuZChmcmFnLmZpcnN0Q2hpbGQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBodG1sLmlubmVySFRNTDtcbiAgICAgICAgfSxcbiAgICAgICAgY2xlYW5Xb3JkOiBmdW5jdGlvbiAoY29udGVudCkge1xuICAgICAgICAgICAgdmFyIHdyYXBMaXN0Um9vdHMgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGFsbCA9IHNjb3BlLiRlZGl0QXJlYS5xdWVyeVNlbGVjdG9yQWxsKCdsaVtkYXRhLWxldmVsXScpLCBpID0gMCwgaGFzID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgZm9yICg7IGkgPCBhbGwubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHBhcmVudCA9IGFsbFtpXS5wYXJlbnRFbGVtZW50Lm5vZGVOYW1lO1xuICAgICAgICAgICAgICAgICAgICBpZiAocGFyZW50ICE9PSAnT0wnICYmIHBhcmVudCAhPT0gJ1VMJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgaGFzID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBncm91cCA9IG13LiQoW10pLCBjdXJyID0gYWxsW2ldO1xuICAgICAgICAgICAgICAgICAgICAgICAgd2hpbGUgKCEhY3VyciAmJiBjdXJyLm5vZGVOYW1lID09PSAnTEknKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZ3JvdXAucHVzaChjdXJyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5uZXh0RWxlbWVudFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZWwgPSBtd2QuY3JlYXRlRWxlbWVudChhbGxbaV0uZ2V0QXR0cmlidXRlKCdkYXRhLXR5cGUnKSA9PT0gJ3VsJyA/ICd1bCcgOiAnb2wnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9ICdlbGVtZW50JztcbiAgICAgICAgICAgICAgICAgICAgICAgIGdyb3VwLndyYXBBbGwoZWwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKGhhcykgcmV0dXJuIHdyYXBMaXN0Um9vdHMoKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBidWlsZFdvcmRMaXN0ID0gZnVuY3Rpb24gKGxpc3RzLCBjb3VudCkge1xuICAgICAgICAgICAgICAgIHZhciBpLCBjaGVjayA9IGZhbHNlLCBtYXggPSAwO1xuICAgICAgICAgICAgICAgIGNvdW50ID0gY291bnQgfHwgMDtcbiAgICAgICAgICAgICAgICBpZiAoY291bnQgPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgZm9yIChpIGluIGxpc3RzKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgY3VyciA9IGxpc3RzW2ldO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCFjdXJyLm5vZGVOYW1lIHx8IGN1cnIubm9kZVR5cGUgIT09IDEpIGNvbnRpbnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyICRjdXJyID0gbXcuJChjdXJyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGxpc3RzW2ldID0gbXcudG9vbHMuc2V0VGFnKGN1cnIsICdsaScpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgbGlzdHMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBudW0gPSB0aGlzLnRleHRDb250ZW50LnRyaW0oKS5zcGxpdCgnLicpWzBdLCBjaGVjayA9IHBhcnNlSW50KG51bSwgMTApO1xuICAgICAgICAgICAgICAgICAgICB2YXIgY3VyciA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgIGlmICghY3Vyci5hdHRyKCdkYXRhLXR5cGUnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCFpc05hTihjaGVjaykgJiYgbnVtID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdGhpcy5pbm5lckhUTUwucmVwbGFjZShudW0gKyAnLicsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJyLmF0dHIoJ2RhdGEtdHlwZScsICdvbCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY3Vyci5hdHRyKCdkYXRhLXR5cGUnLCAndWwnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAoIXRoaXMuX19kb25lKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl9fZG9uZSA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGxldmVsID0gcGFyc2VJbnQoJCh0aGlzKS5hdHRyKCdkYXRhLWxldmVsJykpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCFpc05hTihsZXZlbCkgJiYgbGV2ZWwgPiBtYXgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXggPSBsZXZlbDtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICghaXNOYU4obGV2ZWwpICYmIGxldmVsID4gMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBwcmV2ID0gdGhpcy5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghIXByZXYgJiYgcHJldi5ub2RlTmFtZSA9PT0gJ0xJJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdHlwZSA9IHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLXR5cGUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHdyYXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KHR5cGUgPT09ICd1bCcgPyAndWwnIDogJ29sJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdyYXAuc2V0QXR0cmlidXRlKCdkYXRhLWxldmVsJywgbGV2ZWwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHdyYXApLmFwcGVuZCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCh3cmFwKS5hcHBlbmRUbyhwcmV2KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2hlY2sgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbHNlIGlmICghIXByZXYgJiYgKHByZXYubm9kZU5hbWUgPT09ICdVTCcgfHwgcHJldi5ub2RlTmFtZSA9PT0gJ09MJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHdoZXJlID0gbXcuJCgnbGlbZGF0YS1sZXZlbD1cIicgKyBsZXZlbCArICdcIl0nLCBwcmV2KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHdoZXJlLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdoZXJlLmFmdGVyKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2hlY2sgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHR5cGUgPSB0aGlzLmdldEF0dHJpYnV0ZSgnZGF0YS10eXBlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgd3JhcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQodHlwZSA9PT0gJ3VsJyA/ICd1bCcgOiAnb2wnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdyYXAuc2V0QXR0cmlidXRlKCdkYXRhLWxldmVsJywgbGV2ZWwpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHdyYXApLmFwcGVuZCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQod3JhcCkuYXBwZW5kVG8oJCgnbGk6bGFzdCcsIHByZXYpKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2hlY2sgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsc2UgaWYgKCFwcmV2ICYmICh0aGlzLnBhcmVudE5vZGUubm9kZU5hbWUgIT09ICdVTCcgJiYgdGhpcy5wYXJlbnROb2RlLm5vZGVOYW1lICE9PSAnT0wnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgJGN1cnIgPSBtdy4kKFt0aGlzXSksIGN1cnIgPSB0aGlzO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aGlsZSAoJChjdXJyKS5uZXh0KCdsaVtkYXRhLWxldmVsPVwiJyArIGxldmVsICsgJ1wiXScpLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICRjdXJyLnB1c2goJChjdXJyKS5uZXh0KCdsaVtkYXRhLWxldmVsPVwiJyArIGxldmVsICsgJ1wiXScpWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGN1cnIgPSBtdy4kKGN1cnIpLm5leHQoJ2xpW2RhdGEtbGV2ZWw9XCInICsgbGV2ZWwgKyAnXCJdJylbMF07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJGN1cnIud3JhcEFsbCgkY3Vyci5lcSgwKS5hdHRyKCdkYXRhLXR5cGUnKSA9PT0gJ3VsJyA/ICc8dWw+PC91bD4nIDogJzxvbD48L29sPicpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNoZWNrID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgIG13LiQoXCJ1bFtkYXRhLWxldmVsIT0nMSddLCBvbFtkYXRhLWxldmVsIT0nMSddXCIpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbGV2ZWwgPSBwYXJzZUludCgkKHRoaXMpLmF0dHIoJ2RhdGEtbGV2ZWwnKSk7XG4gICAgICAgICAgICAgICAgICAgIGlmICghIXRoaXMucHJldmlvdXNFbGVtZW50U2libGluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHBsZXZlbCA9IHBhcnNlSW50KCQodGhpcy5wcmV2aW91c0VsZW1lbnRTaWJsaW5nKS5hdHRyKCdkYXRhLWxldmVsJykpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGxldmVsID4gcGxldmVsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCgnbGk6bGFzdCcsIHRoaXMucHJldmlvdXNFbGVtZW50U2libGluZykuYXBwZW5kKHRoaXMpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY2hlY2sgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgaWYgKGNvdW50ID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgYnVpbGRXb3JkTGlzdCgkKCdsaVtkYXRhLWxldmVsXScpLCAxKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHdyYXBMaXN0Um9vdHMoKTtcbiAgICAgICAgICAgICAgICAgICAgfSwgMSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBsaXN0cztcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciB3b3JkX2xpc3RpdGVtX2dldF9sZXZlbCA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgdmFyIG1zc3BsID0gaXRlbS5nZXRBdHRyaWJ1dGUoJ3N0eWxlJykuc3BsaXQoJ21zby1saXN0Jyk7XG4gICAgICAgICAgICAgICAgaWYgKG1zc3BsLmxlbmd0aCA+IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIG1zc3BsaXRlbXMgPSBtc3NwbFsxXS5zcGxpdCgnICcpO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG1zc3BsaXRlbXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtc3NwbGl0ZW1zW2ldLmluZGV4T2YoJ2xldmVsJykgIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHBhcnNlSW50KG1zc3BsaXRlbXNbaV0uc3BsaXQoJ2xldmVsJylbMV0sIDEwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBpc1dvcmRIdG1sID0gZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gaHRtbC5pbmRleE9mKCd1cm46c2NoZW1hcy1taWNyb3NvZnQtY29tOm9mZmljZTp3b3JkJykgIT09IC0xO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIF9jbGVhbldvcmRMaXN0ID0gZnVuY3Rpb24gKGh0bWwpIHtcblxuICAgICAgICAgICAgICAgIGlmICghaXNXb3JkSHRtbChodG1sKSkgcmV0dXJuIGh0bWw7XG4gICAgICAgICAgICAgICAgaWYgKGh0bWwuaW5kZXhPZignPC9ib2R5PicpICE9PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5zcGxpdCgnPC9ib2R5PicpWzBdO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgcGFyc2VyID0gbXcudG9vbHMucGFyc2VIdG1sKGh0bWwpLmJvZHk7XG5cbiAgICAgICAgICAgICAgICB2YXIgbGlzdHMgPSBtdy4kKCdbc3R5bGUqPVwibXNvLWxpc3Q6XCJdJywgcGFyc2VyKTtcbiAgICAgICAgICAgICAgICBsaXN0cy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGxldmVsID0gd29yZF9saXN0aXRlbV9nZXRfbGV2ZWwodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgIGlmICghIWxldmVsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnNldEF0dHJpYnV0ZSgnZGF0YS1sZXZlbCcsIGxldmVsKVxuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgJ2xldmVsLScgKyBsZXZlbClcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBtdy4kKCdbc3R5bGVdJywgcGFyc2VyKS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuXG4gICAgICAgICAgICAgICAgaWYgKGxpc3RzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgbGlzdHMgPSBidWlsZFdvcmRMaXN0KGxpc3RzKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHN0YXJ0ID0gbXcuJChbXSk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoJ2xpJywgcGFyc2VyKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdGhpcy5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAucmVwbGFjZSgv77+9L2csICcnKS8qIE5vdCBhIGRvdCAqL1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC5yZXBsYWNlKG5ldyBSZWdFeHAoU3RyaW5nLmZyb21DaGFyQ29kZSgxNjApLCBcImdcIiksIFwiXCIpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLnJlcGxhY2UoLyZuYnNwOy9naSwgJycpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLnJlcGxhY2UoL1xc77+9L2csICcnKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC5yZXBsYWNlKC88XFwvP3NwYW5bXj5dKj4vZywgXCJcIilcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAucmVwbGFjZSgn77+9JywgJycpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHBhcnNlci5pbm5lckhUTUw7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgY2xlYW5Xb3JkID0gZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICBodG1sID0gX2NsZWFuV29yZExpc3QoaHRtbCk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPHRkKFtePl0qKT4vZ2ksICc8dGQ+Jyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPHRhYmxlKFtePl0qKT4vZ2ksICc8dGFibGUgY2VsbHNwYWNpbmc9XCIwXCIgY2VsbHBhZGRpbmc9XCIwXCIgYm9yZGVyPVwiMVwiIHN0eWxlPVwid2lkdGg6MTAwJTtcIiB3aWR0aD1cIjEwMCVcIiBjbGFzcz1cImVsZW1lbnRcIj4nKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88bzpwPlxccyo8XFwvbzpwPi9nLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPG86cD5bXFxzXFxTXSo/PFxcL286cD4vZywgJyZuYnNwOycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccyptc28tW146XSs6W147XCJdKzs/L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKk1BUkdJTjogMGNtIDBjbSAwcHRcXHMqOy9naSwgJycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypNQVJHSU46IDBjbSAwY20gMHB0XFxzKlwiL2dpLCBcIlxcXCJcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKlRFWFQtSU5ERU5UOiAwY21cXHMqOy9naSwgJycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypURVhULUlOREVOVDogMGNtXFxzKlwiL2dpLCBcIlxcXCJcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKlRFWFQtQUxJR046IFteXFxzO10rOz9cIi9naSwgXCJcXFwiXCIpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypQQUdFLUJSRUFLLUJFRk9SRTogW15cXHM7XSs7P1wiL2dpLCBcIlxcXCJcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKkZPTlQtVkFSSUFOVDogW15cXHM7XSs7P1wiL2dpLCBcIlxcXCJcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKnRhYi1zdG9wczpbXjtcIl0qOz8vZ2ksICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqdGFiLXN0b3BzOlteXCJdKi9naSwgJycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypmYWNlPVwiW15cIl0qXCIvZ2ksICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqZmFjZT1bXiA+XSovZ2ksICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqRk9OVC1GQU1JTFk6W147XCJdKjs/L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPChcXHdbXj5dKikgY2xhc3M9KFteIHw+XSopKFtePl0qKS9naSwgXCI8JDEkM1wiKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88U1RZTEVbXj5dKj5bXFxzXFxTXSo/PFxcL1NUWUxFW14+XSo+L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPCg/Ok1FVEF8TElOSylbXj5dKj5cXHMqL2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKnN0eWxlPVwiXFxzKlwiL2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFNQQU5cXHMqW14+XSo+XFxzKiZuYnNwO1xccyo8XFwvU1BBTj4vZ2ksICcmbmJzcDsnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88U1BBTlxccypbXj5dKj48XFwvU1BBTj4vZ2ksICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KFxcd1tePl0qKSBsYW5nPShbXiB8Pl0qKShbXj5dKikvZ2ksIFwiPCQxJDNcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFNQQU5cXHMqPihbXFxzXFxTXSo/KTxcXC9TUEFOPi9naSwgJyQxJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPEZPTlRcXHMqPihbXFxzXFxTXSo/KTxcXC9GT05UPi9naSwgJyQxJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFxcXFw/XFw/eG1sW14+XSo+L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPHc6W14+XSo+W1xcc1xcU10qPzxcXC93OltePl0qPi9naSwgJycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxcXC8/XFx3KzpbXj5dKj4vZ2ksICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88XFwhLS1bXFxzXFxTXSo/LS0+L2csICcnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KFV8SXxTVFJJS0UpPiZuYnNwOzxcXC9cXDE+L2csICcmbmJzcDsnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88SFxcZD5cXHMqPFxcL0hcXGQ+L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPChcXHcrKVtePl0qXFxzc3R5bGU9XCJbXlwiXSpESVNQTEFZXFxzPzpcXHM/bm9uZVtcXHNcXFNdKj88XFwvXFwxPi9pZywgJycpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoXFx3W14+XSopIGxhbmd1YWdlPShbXiB8Pl0qKShbXj5dKikvZ2ksIFwiPCQxJDNcIik7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPChcXHdbXj5dKikgb25tb3VzZW92ZXI9XCIoW15cXFwiXSopXCIoW14+XSopL2dpLCBcIjwkMSQzXCIpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoXFx3W14+XSopIG9ubW91c2VvdXQ9XCIoW15cXFwiXSopXCIoW14+XSopL2dpLCBcIjwkMSQzXCIpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxIKFxcZCkoW14+XSopPi9naSwgJzxoJDE+Jyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPGZvbnQgc2l6ZT0yPiguKik8XFwvZm9udD4vZ2ksICckMScpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxmb250IHNpemU9Mz4oLiopPFxcL2ZvbnQ+L2dpLCAnJDEnKTtcbiAgICAgICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88YSBuYW1lPS4qPiguKik8XFwvYT4vZ2ksICckMScpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxIMShbXj5dKik+L2dpLCAnPEgyJDE+Jyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFxcL0gxXFxkPi9naSwgJzxcXC9IMj4nKTtcbiAgICAgICAgICAgICAgICAvL2h0bWwgPSBodG1sLnJlcGxhY2UoLzxzcGFuPi9naSwgJyQxJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFxcL3NwYW5cXGQ+L2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPChIXFxkKT48Rk9OVFtePl0qPihbXFxzXFxTXSo/KTxcXC9GT05UPjxcXC9cXDE+L2dpLCAnPCQxPiQyPFxcLyQxPicpO1xuICAgICAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoSFxcZCk+PEVNPihbXFxzXFxTXSo/KTxcXC9FTT48XFwvXFwxPi9naSwgJzwkMT4kMjxcXC8kMT4nKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gaHRtbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBjbGVhblRhYmxlcyA9IGZ1bmN0aW9uIChyb290KSB7XG4gICAgICAgICAgICAgICAgdmFyIHRvUmVtb3ZlID0gXCJ0Ym9keSA+ICo6bm90KHRyKSwgdGhlYWQgPiAqOm5vdCh0ciksIHRyID4gKjpub3QodGQpXCIsXG4gICAgICAgICAgICAgICAgICAgIGFsbCA9IHJvb3QucXVlcnlTZWxlY3RvckFsbCh0b1JlbW92ZSksXG4gICAgICAgICAgICAgICAgICAgIGwgPSBhbGwubGVuZ3RoLFxuICAgICAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKGFsbFtpXSkucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciB0YWJsZXMgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwoJ3RhYmxlJyksXG4gICAgICAgICAgICAgICAgICAgIGwgPSB0YWJsZXMubGVuZ3RoLFxuICAgICAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IHRhYmxlc1tpXSxcbiAgICAgICAgICAgICAgICAgICAgICAgIGwgPSBpdGVtLmNoaWxkcmVuLmxlbmd0aCxcbiAgICAgICAgICAgICAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSBpdGVtLmNoaWxkcmVuW2ldO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBpdGVtICE9PSAndW5kZWZpbmVkJyAmJiBpdGVtLm5vZGVUeXBlICE9PSAzKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5hbWUgPSBpdGVtLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHBvc2libGVzID0gXCJ0aGVhZCB0Zm9vdCB0ciB0Ym9keSBjb2wgY29sZ3JvdXBcIjtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIXBvc2libGVzLmNvbnRhaW5zKG5hbWUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoaXRlbSkucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgcmV0dXJuIGNsZWFuV29yZChjb250ZW50KVxuXG4gICAgICAgIH0sXG4gICAgICAgIGFjdGlvbjogZnVuY3Rpb24odGFyZ2V0UGFyZW50LCBmdW5jKSB7XG4gICAgICAgICAgICBzY29wZS5zdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgIHRhcmdldDogdGFyZ2V0UGFyZW50LFxuICAgICAgICAgICAgICAgIHZhbHVlOiB0YXJnZXRQYXJlbnQuaW5uZXJIVE1MXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGZ1bmMuY2FsbCgpO1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHNjb3BlLnN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogdGFyZ2V0UGFyZW50LFxuICAgICAgICAgICAgICAgICAgICB2YWx1ZTogdGFyZ2V0UGFyZW50LmlubmVySFRNTFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSwgNzgpO1xuICAgICAgICB9LFxuICAgICAgICBlbGVtZW50Tm9kZTogZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgIGlmKCAhYyB8fCAhYy5wYXJlbnROb2RlIHx8IGMucGFyZW50Tm9kZSA9PT0gZG9jdW1lbnQuYm9keSApe1xuICAgICAgICAgICAgICAgIHJldHVybiBudWxsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdHJ5IHsgICAvKiBGaXJlZm94IHJldHVybnMgd3JvbmcgdGFyZ2V0IChkaXYpIHdoZW4geW91IGNsaWNrIG9uIGEgc3Bpbi1idXR0b24gKi9cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGMucXVlcnlTZWxlY3RvciA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gYztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBzY29wZS5hcGkuZWxlbWVudE5vZGUoYy5wYXJlbnROb2RlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjYXRjaCAoZSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBudWxsO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBmb250RmFtaWx5OiBmdW5jdGlvbiAoZm9udF9uYW1lLCBzZWwpIHtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IChzZWwgfHwgc2NvcGUuZ2V0U2VsZWN0aW9uKCkpLmdldFJhbmdlQXQoMCk7XG4gICAgICAgICAgICBzY29wZS5hcGkuZXhlY0NvbW1hbmQoXCJzdHlsZVdpdGhDU1NcIiwgbnVsbCwgdHJ1ZSk7XG4gICAgICAgICAgICBpZiAocmFuZ2UuY29sbGFwc2VkKSB7XG4gICAgICAgICAgICAgICAgdmFyIGVsID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKTtcbiAgICAgICAgICAgICAgICBzY29wZS5hcGkuc2VsZWN0X2FsbChlbCk7XG4gICAgICAgICAgICAgICAgc2NvcGUuYXBpLmV4ZWNDb21tYW5kKCdmb250TmFtZScsIG51bGwsIGZvbnRfbmFtZSk7XG4gICAgICAgICAgICAgICAgcmFuZ2UuY29sbGFwc2UoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHNjb3BlLmFwaS5leGVjQ29tbWFuZCgnZm9udE5hbWUnLCBudWxsLCBmb250X25hbWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBzZWxlY3RBbGw6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgdmFyIHJhbmdlID0gc2NvcGUuZG9jdW1lbnQuY3JlYXRlUmFuZ2UoKTtcbiAgICAgICAgICAgIHJhbmdlLnNlbGVjdE5vZGVDb250ZW50cyhlbCk7XG4gICAgICAgICAgICB2YXIgc2VsZWN0aW9uID0gc2NvcGUuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICBzZWxlY3Rpb24ucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgICAgICBzZWxlY3Rpb24uYWRkUmFuZ2UocmFuZ2UpO1xuICAgICAgICB9LFxuICAgICAgICBzZWxlY3RFbGVtZW50OiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IHNjb3BlLmRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgIHJhbmdlLnNlbGVjdE5vZGUoZWwpO1xuICAgICAgICAgICAgICAgIHZhciBzZWxlY3Rpb24gPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICBzZWxlY3Rpb24ucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgICAgICAgICAgc2VsZWN0aW9uLmFkZFJhbmdlKHJhbmdlKTtcbiAgICAgICAgICAgIH0gY2F0Y2ggKGUpIHtcblxuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBpc1NlbGVjdGlvbkVkaXRhYmxlOiBmdW5jdGlvbiAoc2VsKSB7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgIHZhciBub2RlID0gKHNlbCB8fCBzY29wZS5nZXRTZWxlY3Rpb24oKSkuZm9jdXNOb2RlO1xuICAgICAgICAgICAgICAgIGlmIChub2RlID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKG5vZGUubm9kZVR5cGUgPT09IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG5vZGUuaXNDb250ZW50RWRpdGFibGU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbm9kZS5wYXJlbnROb2RlLmlzQ29udGVudEVkaXRhYmxlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGNhdGNoIChlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBpc1NhZmVNb2RlOiBmdW5jdGlvbihlbCkge1xuICAgICAgICAgICAgaWYgKCFlbCkge1xuICAgICAgICAgICAgICAgIHZhciBub2RlID0gc2NvcGUuZ2V0U2VsZWN0aW9uKCkuZm9jdXNOb2RlO1xuICAgICAgICAgICAgICAgIGVsID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKG5vZGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGhhc1NhZmUgPSBtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChlbCwgWydzYWZlLW1vZGUnXSk7XG4gICAgICAgICAgICB2YXIgcmVnSW5zYWZlID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck5vbmUoZWwsIFsncmVndWxhci1tb2RlJywgJ3NhZmUtbW9kZSddKTtcbiAgICAgICAgICAgIHJldHVybiBoYXNTYWZlICYmICFyZWdJbnNhZmU7XG4gICAgICAgIH0sXG4gICAgICAgIF9leGVjQ29tbWFuZEN1c3RvbToge1xuICAgICAgICAgICAgcmVtb3ZlRm9ybWF0OiBmdW5jdGlvbiAoY21kLCBkZWYsIHZhbCkge1xuICAgICAgICAgICAgICAgIHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5leGVjQ29tbWFuZChjbWQsIGRlZiwgdmFsKTtcbiAgICAgICAgICAgICAgICB2YXIgc2VsID0gc2NvcGUuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICAgICAgdmFyIHIgPSBzZWwuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICB2YXIgY29tbW9uID0gci5jb21tb25BbmNlc3RvckNvbnRhaW5lcjtcbiAgICAgICAgICAgICAgICB2YXIgYWxsID0gY29tbW9uLnF1ZXJ5U2VsZWN0b3JBbGwoJyonKSwgbCA9IGFsbC5sZW5ndGgsIGkgPSAwO1xuICAgICAgICAgICAgICAgIGZvciAoIDsgaSA8IGw7IGkrKyApIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGVsID0gYWxsW2ldO1xuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIHNlbCAhPT0gJ3VuZGVmaW5lZCcgJiYgc2VsLmNvbnRhaW5zTm9kZShlbCwgdHJ1ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGFsbFtpXS5yZW1vdmVBdHRyaWJ1dGUoJ3N0eWxlJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIGV4ZWNDb21tYW5kOiBmdW5jdGlvbiAoY21kLCBkZWYsIHZhbCkge1xuICAgICAgICAgICAgIHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5leGVjQ29tbWFuZCgnc3R5bGVXaXRoQ3NzJywgJ2ZhbHNlJywgZmFsc2UpO1xuICAgICAgICAgICAgdmFyIHNlbCA9IHNjb3BlLmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgdHJ5IHsgIC8vIDB4ODAwMDQwMDVcbiAgICAgICAgICAgICAgICAgaWYgKHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5xdWVyeUNvbW1hbmRTdXBwb3J0ZWQoY21kKSAmJiBzY29wZS5hcGkuaXNTZWxlY3Rpb25FZGl0YWJsZSgpKSB7XG4gICAgICAgICAgICAgICAgICAgICBkZWYgPSBkZWYgfHwgZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIHZhbCA9IHZhbCB8fCBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHNlbC5yYW5nZUNvdW50ID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBub2RlID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKHNlbC5mb2N1c05vZGUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuYXBpLmFjdGlvbihtdy50b29scy5maXJzdEJsb2NrTGV2ZWwobm9kZSksIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuYWN0aW9uV2luZG93LmRvY3VtZW50LmV4ZWNDb21tYW5kKGNtZCwgZGVmLCB2YWwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoc2NvcGUuc2V0dGluZ3MuaWZyYW1lQXJlYVNlbGVjdG9yLCBzY29wZS5hY3Rpb25XaW5kb3cuZG9jdW1lbnQpLnRyaWdnZXIoJ2V4ZWNDb21tYW5kJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcignZXhlY0NvbW1hbmQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY2F0Y2ggKGUpIHtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgX2ZvbnRTaXplOiBmdW5jdGlvbiAoc2l6ZSwgdW5pdCkge1xuICAgICAgICAgICAgdW5pdCA9IHVuaXQgfHwgJ3B4JztcbiAgICAgICAgICAgIHZhciBmb250U2l6ZSA9ICQoJzxzcGFuLz4nLCB7XG4gICAgICAgICAgICAgICAgJ3RleHQnOiBzY29wZS5nZXRTZWxlY3Rpb24oKVxuICAgICAgICAgICAgfSkuY3NzKCdmb250LXNpemUnLCBzaXplICsgdW5pdCkucHJvcCgnb3V0ZXJIVE1MJyk7XG4gICAgICAgICAgICBzY29wZS5hcGkuZXhlY0NvbW1hbmQoJ2luc2VydEhUTUwnLCBmYWxzZSwgZm9udFNpemUpO1xuICAgICAgICB9LFxuICAgICAgICBmb250U2l6ZTogZnVuY3Rpb24gKHNpemUpIHtcbiAgICAgICAgICAgIHZhciBzZWwgPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIGlmIChzZWwuaXNDb2xsYXBzZWQpIHtcbiAgICAgICAgICAgICAgICBzY29wZS5hcGkuc2VsZWN0X2FsbChzY29wZS5hcGkuZWxlbWVudE5vZGUoc2VsLmZvY3VzTm9kZSkpO1xuICAgICAgICAgICAgICAgIHNlbCA9IHNjb3BlLmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHJhbmdlID0gc2VsLmdldFJhbmdlQXQoMCksXG4gICAgICAgICAgICAgICAgY29tbW9uID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKTtcbiAgICAgICAgICAgIHZhciBub2Ryb3Bfc3RhdGUgPSBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0T3JOb25lKGNvbW1vbiwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKTtcbiAgICAgICAgICAgIGlmIChzY29wZS5hcGkuaXNTZWxlY3Rpb25FZGl0YWJsZSgpICYmIG5vZHJvcF9zdGF0ZSkge1xuICAgICAgICAgICAgICAgIHNjb3BlLmFwaS5fZm9udFNpemUoc2l6ZSwgJ3B4Jyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIHNhdmVTZWxlY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzZWwgPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIHNjb3BlLmFwaS5zYXZlZFNlbGVjdGlvbiA9IHtcbiAgICAgICAgICAgICAgICBzZWxlY3Rpb246IHNlbCxcbiAgICAgICAgICAgICAgICByYW5nZTogc2VsLmdldFJhbmdlQXQoMCksXG4gICAgICAgICAgICAgICAgZWxlbWVudDogbXcuJChzY29wZS5hcGkuZWxlbWVudE5vZGUoc2VsLmdldFJhbmdlQXQoMCkuY29tbW9uQW5jZXN0b3JDb250YWluZXIpKVxuICAgICAgICAgICAgfTtcbiAgICAgICAgfSxcbiAgICAgICAgcmVzdG9yZVNlbGVjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKHNjb3BlLmFwaS5zYXZlZFNlbGVjdGlvbikge1xuICAgICAgICAgICAgICAgIHZhciBzZWwgPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICBzY29wZS5hcGkuc2F2ZWRTZWxlY3Rpb24uZWxlbWVudC5hdHRyKFwiY29udGVudGVkaXRhYmxlXCIsIFwidHJ1ZVwiKTtcbiAgICAgICAgICAgICAgICBzY29wZS5hcGkuc2F2ZWRTZWxlY3Rpb24uZWxlbWVudC5mb2N1cygpO1xuICAgICAgICAgICAgICAgIHNjb3BlLmFwaS5zYXZlZFNlbGVjdGlvbi5zZWxlY3Rpb24ucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgICAgICAgICAgc2NvcGUuYXBpLnNhdmVkU2VsZWN0aW9uLnNlbGVjdGlvbi5hZGRSYW5nZShzY29wZS5hcGkuc2F2ZWRTZWxlY3Rpb24ucmFuZ2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBpbnNlcnRIVE1MOiBmdW5jdGlvbihodG1sKSB7XG4gICAgICAgICAgICByZXR1cm4gc2NvcGUuYXBpLmV4ZWNDb21tYW5kKCdpbnNlcnRIVE1MJywgZmFsc2UsIGh0bWwpO1xuICAgICAgICB9LFxuICAgICAgICBpbnNlcnRJbWFnZTogZnVuY3Rpb24gKHVybCkge1xuICAgICAgICAgICAgdmFyIGlkID0gIG13LmlkKCdpbWFnZV8nKTtcbiAgICAgICAgICAgIHZhciBpbWcgPSAnPGltZyBpZD1cIicgKyBpZCArICdcIiBjb250ZW50RWRpdGFibGU9XCJmYWxzZVwiIGNsYXNzPVwiZWxlbWVudFwiIHNyYz1cIicgKyB1cmwgKyAnXCIgLz4nO1xuICAgICAgICAgICAgc2NvcGUuYXBpLmluc2VydEhUTUwoaW1nKTtcbiAgICAgICAgICAgIGltZyA9IG13LiQoXCIjXCIgKyBpZCk7XG4gICAgICAgICAgICBpbWcucmVtb3ZlQXR0cihcIl9tb3pfZGlydHlcIik7XG4gICAgICAgICAgICByZXR1cm4gaW1nWzBdO1xuICAgICAgICB9LFxuICAgICAgICBsaW5rOiBmdW5jdGlvbiAocmVzdWx0KSB7XG4gICAgICAgICAgICB2YXIgc2VsID0gc2NvcGUuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICB2YXIgZWwgPSBzY29wZS5hcGkuZWxlbWVudE5vZGUoc2VsLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICB2YXIgZWxMaW5rID0gZWwubm9kZU5hbWUgPT09ICdBJyA/IGVsIDogbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKGVsLCAnYScpO1xuICAgICAgICAgICAgaWYgKGVsTGluaykge1xuICAgICAgICAgICAgICAgIGVsTGluay5ocmVmID0gcmVzdWx0LnVybDtcbiAgICAgICAgICAgICAgICBpZiAocmVzdWx0LnRleHQgJiYgcmVzdWx0LnRleHQgIT09IGVsTGluay5pbm5lckhUTUwpIHtcbiAgICAgICAgICAgICAgICAgICAgZWxMaW5rLmlubmVySFRNTCA9IHJlc3VsdC50ZXh0O1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgc2NvcGUuYXBpLmluc2VydEhUTUwoJzxhIGhyZWY9XCInKyByZXN1bHQudXJsICsnXCI+JysgKHJlc3VsdC50ZXh0IHx8IChzZWwudG9TdHJpbmcoKS50cmltKCkpIHx8IHJlc3VsdC51cmwpICsnPC9hPicpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICB1bmxpbms6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzZWwgPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIGlmICghc2VsLmlzQ29sbGFwc2VkKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5leGVjQ29tbWFuZCgndW5saW5rJywgbnVsbCwgbnVsbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICB2YXIgbGluayA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aFRhZyh0aGlzLmVsZW1lbnROb2RlKHNlbC5mb2N1c05vZGUpLCAnYScpO1xuICAgICAgICAgICAgICAgIGlmICghIWxpbmspIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RFbGVtZW50KGxpbmspO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmV4ZWNDb21tYW5kKCd1bmxpbmsnLCBudWxsLCBudWxsKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9O1xufTtcbiIsIihmdW5jdGlvbigpe1xuICAgIHZhciBCYXIgPSBmdW5jdGlvbihvcHRpb25zKSB7XG5cbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgIGRvY3VtZW50OiBkb2N1bWVudCxcbiAgICAgICAgICAgIHJlZ2lzdGVyOiBudWxsXG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuICAgICAgICB0aGlzLmRvY3VtZW50ID0gdGhpcy5zZXR0aW5ncy5kb2N1bWVudCB8fCBkb2N1bWVudDtcblxuICAgICAgICB0aGlzLnJlZ2lzdGVyID0gW107XG5cbiAgICAgICAgdGhpcy5kZWxpbWl0ZXIgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIGVsID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAnbXctYmFyLWRlbGltaXRlcic7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5jcmVhdGUgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5iYXIgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgdGhpcy5iYXIuY2xhc3NOYW1lID0gJ213LWJhcic7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQgPSBtdy5lbGVtZW50KHRoaXMuYmFyKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJvd3MgPSBbXTtcblxuICAgICAgICB0aGlzLmNyZWF0ZVJvdyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciByb3cgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgcm93LmNsYXNzTmFtZSA9ICdtdy1iYXItcm93JztcbiAgICAgICAgICAgIHRoaXMucm93cy5wdXNoKHJvdyk7XG4gICAgICAgICAgICB0aGlzLmJhci5hcHBlbmRDaGlsZChyb3cpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLm5hdGl2ZUVsZW1lbnQgPSBmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICAgICAgaWYoIW5vZGUpIHJldHVybjtcbiAgICAgICAgICAgIHJldHVybiBub2RlLm5vZGUgPyBub2RlLm5vZGUgOiBub2RlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWRkID0gZnVuY3Rpb24gKHdoYXQsIHJvdykge1xuICAgICAgICAgICAgcm93ID0gcm93IHx8IDA7XG4gICAgICAgICAgICBpZighdGhpcy5yb3dzW3Jvd10pIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZih3aGF0ID09PSAnfCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLnJvd3Nbcm93XS5hcHBlbmRDaGlsZCh0aGlzLmRlbGltaXRlcigpKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZih0eXBlb2Ygd2hhdCA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgIHRoaXMucm93c1tyb3ddLmFwcGVuZENoaWxkKHdoYXQoKS5ub2RlKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdmFyIGVsID0gdGhpcy5uYXRpdmVFbGVtZW50KHdoYXQpO1xuICAgICAgICAgICAgICAgIGlmKGVsKSB7XG4gICAgICAgICAgICAgICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoJ213LWJhci1jb250cm9sLWl0ZW0nKVxuICAgICAgICAgICAgICAgICAgICB0aGlzLnJvd3Nbcm93XS5hcHBlbmRDaGlsZChlbCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlKCk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuaW5pdCgpO1xuICAgIH07XG4gICAgbXcuYmFyID0gZnVuY3Rpb24ob3B0aW9ucyl7XG4gICAgICAgIHJldHVybiBuZXcgQmFyKG9wdGlvbnMpO1xuICAgIH07XG59KSgpO1xuIiwiTVdFZGl0b3IuY29udHJvbGxlcnMgPSB7XG4gICAgYWxpZ246IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yb290ID0gTVdFZGl0b3IuY29yZS5lbGVtZW50KCk7XG4gICAgICAgIHRoaXMucm9vdC4kbm9kZS5hZGRDbGFzcygnbXctZWRpdG9yLXN0YXRlLWNvbXBvbmVudCBtdy1lZGl0b3Itc3RhdGUtY29tcG9uZW50LWFsaWduJyk7XG4gICAgICAgIHRoaXMuYnV0dG9ucyA9IFtdO1xuXG4gICAgICAgIHZhciBhcnIgPSBbXG4gICAgICAgICAgICB7YWxpZ246ICdsZWZ0JywgaWNvbjogJ2xlZnQnLCBhY3Rpb246ICdqdXN0aWZ5TGVmdCd9LFxuICAgICAgICAgICAge2FsaWduOiAnY2VudGVyJywgaWNvbjogJ2NlbnRlcicsIGFjdGlvbjogJ2p1c3RpZnlDZW50ZXInfSxcbiAgICAgICAgICAgIHthbGlnbjogJ3JpZ2h0JywgaWNvbjogJ3JpZ2h0JywgYWN0aW9uOiAnanVzdGlmeVJpZ2h0J30sXG4gICAgICAgICAgICB7YWxpZ246ICdqdXN0aWZ5JywgaWNvbjogJ2p1c3RpZnknLCBhY3Rpb246ICdqdXN0aWZ5RnVsbCd9XG4gICAgICAgIF07XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIGFyci5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWZvcm1hdC1hbGlnbi0nICsgaXRlbS5pY29uXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgICAgICBhcGkuZXhlY0NvbW1hbmQoaXRlbS5hY3Rpb24pO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHNjb3BlLnJvb3QuYXBwZW5kKGVsKTtcbiAgICAgICAgICAgICAgICBzY29wZS5idXR0b25zLnB1c2goZWwpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gc2NvcGUucm9vdDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIHZhciBhbGlnbiA9IG9wdC5jc3MuYWxpZ25Ob3JtYWxpemUoKTtcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpPCB0aGlzLmJ1dHRvbnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICB2YXIgc3RhdGUgPSBhcnJbaV0uYWxpZ24gPT09IGFsaWduO1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKHRoaXMuYnV0dG9uc1tpXS5ub2RlLCBzdGF0ZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICBib2xkOiBmdW5jdGlvbiAoc2NvcGUsIGFwaSwgcm9vdFNjb3BlKSB7XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZm9ybWF0LWJvbGQnLFxuICAgICAgICAgICAgICAgICAgICB0b29sdGlwOiByb290U2NvcGUubGFuZygnQm9sZCcpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnYm9sZCcpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBpZihvcHQuY3NzLmlzKCkuYm9sZCkge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgdHJ1ZSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgb3B0LmNvbnRyb2xsZXIuZWxlbWVudC5ub2RlLmRpc2FibGVkID0gIW9wdC5hcGkuaXNTZWxlY3Rpb25FZGl0YWJsZShvcHQuc2VsZWN0aW9uKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgIHN0cmlrZVRocm91Z2g6IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ21kaS1mb3JtYXQtc3RyaWtldGhyb3VnaCcsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6IHJvb3RTY29wZS5sYW5nKCdTdHJpa2UgdGhyb3VnaCcpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGVsLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgYXBpLmV4ZWNDb21tYW5kKCdzdHJpa2VUaHJvdWdoJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIGlmKG9wdC5jc3MuaXMoKS5zdHJpa2VkKSB7XG4gICAgICAgICAgICAgICAgcm9vdFNjb3BlLmNvbnRyb2xsZXJBY3RpdmUob3B0LmNvbnRyb2xsZXIuZWxlbWVudC5ub2RlLCB0cnVlKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgcm9vdFNjb3BlLmNvbnRyb2xsZXJBY3RpdmUob3B0LmNvbnRyb2xsZXIuZWxlbWVudC5ub2RlLCBmYWxzZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgaXRhbGljOiBmdW5jdGlvbihzY29wZSwgYXBpLCByb290U2NvcGUpe1xuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZm9ybWF0LWl0YWxpYycsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6IHJvb3RTY29wZS5sYW5nKCdJdGFsaWMnKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWwub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBhcGkuZXhlY0NvbW1hbmQoJ2l0YWxpYycpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICAgICAgaWYob3B0LmNzcy5pcygpLml0YWxpYykge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgdHJ1ZSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgJ3VuZGVybGluZSc6IGZ1bmN0aW9uKHNjb3BlLCBhcGksIHJvb3RTY29wZSl7XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ21kaS1mb3JtYXQtdW5kZXJsaW5lJyxcbiAgICAgICAgICAgICAgICAgICAgdG9vbHRpcDogcm9vdFNjb3BlLmxhbmcoJ1VuZGVybGluZScpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgndW5kZXJsaW5lJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgICAgICBpZihvcHQuY3NzLmlzKCkudW5kZXJsaW5lZCkge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgdHJ1ZSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5jb250cm9sbGVyQWN0aXZlKG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZSwgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgJ2ltYWdlJzogZnVuY3Rpb24oc2NvcGUsIGFwaSwgcm9vdFNjb3BlKXtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWZvbGRlci1tdWx0aXBsZS1pbWFnZScsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6IHJvb3RTY29wZS5sYW5nKCdJbnNlcnQgSW1hZ2UnKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWwub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICB2YXIgZGlhbG9nO1xuICAgICAgICAgICAgICAgIHZhciBwaWNrZXIgPSBuZXcgbXcuZmlsZVBpY2tlcih7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdpbWFnZXMnLFxuICAgICAgICAgICAgICAgICAgICBsYWJlbDogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIGF1dG9TZWxlY3Q6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBmb290ZXI6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgIF9mcmFtZU1heEhlaWdodDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgb25SZXN1bHQ6IGZ1bmN0aW9uIChyZXMpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB1cmwgPSByZXMuc3JjID8gcmVzLnNyYyA6IHJlcztcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCF1cmwpIHJldHVybjtcbiAgICAgICAgICAgICAgICAgICAgICAgIHVybCA9IHVybC50b1N0cmluZygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYXBpLmluc2VydEltYWdlKHVybCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBkaWFsb2cucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBkaWFsb2cgPSBtdy50b3AoKS5kaWFsb2coe1xuICAgICAgICAgICAgICAgICAgICBjb250ZW50OiBwaWNrZXIucm9vdCxcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6IG13LmxhbmcoJ1NlbGVjdCBpbWFnZScpLFxuICAgICAgICAgICAgICAgICAgICBmb290ZXI6IGZhbHNlXG4gICAgICAgICAgICAgICAgfSlcblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgbGluazogZnVuY3Rpb24oc2NvcGUsIGFwaSwgcm9vdFNjb3BlKXtcblxuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktbGluaycsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6IHJvb3RTY29wZS5sYW5nKCdJbnNlcnQgbGluaycpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGVsLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgYXBpLnNhdmVTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICB2YXIgc2VsID0gc2NvcGUuZ2V0U2VsZWN0aW9uKCk7XG5cbiAgICAgICAgICAgICAgICB2YXIgdGFyZ2V0ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKHNlbC5mb2N1c05vZGUsICdhJyk7XG5cbiAgICAgICAgICAgICAgICB2YXIgdmFsO1xuICAgICAgICAgICAgICAgIGlmKHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB1cmw6IHRhcmdldC5ocmVmLFxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dDogdGFyZ2V0LmlubmVySFRNTCxcbiAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogdGFyZ2V0LnRhcmdldCA9PT0gJ19ibGFuaydcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIXNlbC5pc0NvbGxhcHNlZCkge1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB1cmw6ICcnLFxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dDogYXBpLmdldFNlbGVjdGlvbkhUTUwoKSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdmFyIGxpbmtFZGl0b3IgPSBuZXcgbXcuTGlua0VkaXRvcih7XG4gICAgICAgICAgICAgICAgICAgIG1vZGU6ICdkaWFsb2cnLFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIGlmKHZhbCkge1xuICAgICAgICAgICAgICAgICAgICBsaW5rRWRpdG9yLnNldFZhbHVlKHZhbCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgbGlua0VkaXRvci5wcm9taXNlKCkudGhlbihmdW5jdGlvbiAoZGF0YSl7XG4gICAgICAgICAgICAgICAgICAgIHZhciBtb2RhbCA9IGxpbmtFZGl0b3IuZGlhbG9nO1xuICAgICAgICAgICAgICAgICAgICBpZihkYXRhKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBhcGkucmVzdG9yZVNlbGVjdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYXBpLmxpbmsoZGF0YSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtb2RhbC5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG1vZGFsLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG5cblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgZm9udFNpemU6IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIHZhciBjc3MgPSBvcHQuY3NzO1xuICAgICAgICAgICAgdmFyIGZvbnQgPSBjc3MuZm9udCgpO1xuICAgICAgICAgICAgdmFyIHNpemUgPSBmb250LnNpemU7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50LmRpc3BsYXlWYWx1ZShzaXplKTtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKCk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGRyb3Bkb3duID0gbmV3IE1XRWRpdG9yLmNvcmUuZHJvcGRvd24oe1xuICAgICAgICAgICAgICAgIGRhdGE6IFtcbiAgICAgICAgICAgICAgICAgICAgeyBsYWJlbDogJzhweCcsIHZhbHVlOiA4IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcxMHB4JywgdmFsdWU6IDEwIH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcxMnB4JywgdmFsdWU6IDEyIH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcxNHB4JywgdmFsdWU6IDE0IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcxNnB4JywgdmFsdWU6IDE2IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcxOHB4JywgdmFsdWU6IDE4IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcyMHB4JywgdmFsdWU6IDIwIH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcyMnB4JywgdmFsdWU6IDIyIH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcyNHB4JywgdmFsdWU6IDI0IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICcyMnB4JywgdmFsdWU6IDIyIH0sXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogcm9vdFNjb3BlLmxhbmcoJ0ZvbnQgU2l6ZScpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGRyb3Bkb3duLnNlbGVjdC5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUsIHZhbCkge1xuICAgICAgICAgICAgICAgIGFwaS5mb250U2l6ZSh2YWwudmFsdWUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZHJvcGRvd24ucm9vdDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgIGZvcm1hdDogZnVuY3Rpb24gKHNjb3BlLCBhcGksIHJvb3RTY29wZSkge1xuXG4gICAgICAgIHRoaXMuX2F2YWlsYWJsZVRhZ3MgPSBbXG4gICAgICAgICAgICB7IGxhYmVsOiAnPGgxPlRpdGxlPC9oMT4nLCB2YWx1ZTogJ2gxJyB9LFxuICAgICAgICAgICAgeyBsYWJlbDogJzxoMj5UaXRsZTwvaDI+JywgdmFsdWU6ICdoMicgfSxcbiAgICAgICAgICAgIHsgbGFiZWw6ICc8aDM+VGl0bGU8L2gzPicsIHZhbHVlOiAnaDMnIH0sXG4gICAgICAgICAgICB7IGxhYmVsOiAnPGg0PlRpdGxlPC9oND4nLCB2YWx1ZTogJ2g0JyB9LFxuICAgICAgICAgICAgeyBsYWJlbDogJzxoNT5UaXRsZTwvaDU+JywgdmFsdWU6ICdoNScgfSxcbiAgICAgICAgICAgIHsgbGFiZWw6ICc8aDY+VGl0bGU8L2g2PicsIHZhbHVlOiAnaDYnIH0sXG4gICAgICAgICAgICB7IGxhYmVsOiAnUGFyYWdyYXBoJywgdmFsdWU6ICdwJyB9LFxuICAgICAgICAgICAgeyBsYWJlbDogJ0Jsb2NrJywgdmFsdWU6ICdkaXYnIH0sXG4gICAgICAgICAgICB7IGxhYmVsOiAnUHJlIGZvcm1hdGVkJywgdmFsdWU6ICdwcmUnIH1cbiAgICAgICAgXTtcblxuICAgICAgICB0aGlzLmF2YWlsYWJsZVRhZ3MgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZih0aGlzLl9fYXZhaWxhYmxlVGFncykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLl9fYXZhaWxhYmxlVGFncztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuX19hdmFpbGFibGVUYWdzID0gdGhpcy5fYXZhaWxhYmxlVGFncy5tYXAoZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gaXRlbS52YWx1ZTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuYXZhaWxhYmxlVGFncygpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZ2V0VGFnRGlzcGxheU5hbWUgPSBmdW5jdGlvbiAodGFnKSB7XG4gICAgICAgICAgICB0YWcgPSAodGFnIHx8ICcnKS50cmltKCkudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIGlmKCF0YWcpIHJldHVybjtcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgdGhpcy5fYXZhaWxhYmxlVGFncy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmKHRoaXMuX2F2YWlsYWJsZVRhZ3NbaV0udmFsdWUgPT09IHRhZykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5fYXZhaWxhYmxlVGFnc1tpXS5sYWJlbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IG9wdC5hcGkuZWxlbWVudE5vZGUob3B0LnNlbGVjdGlvbi5mb2N1c05vZGUpO1xuICAgICAgICAgICAgdmFyIHBhcmVudEVsID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoVGFnKGVsLCB0aGlzLmF2YWlsYWJsZVRhZ3MoKSk7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50LmRpc3BsYXlWYWx1ZShwYXJlbnRFbCA/IHRoaXMuZ2V0VGFnRGlzcGxheU5hbWUocGFyZW50RWwubm9kZU5hbWUpIDogJycpO1xuICAgICAgICAgICAgb3B0LmNvbnRyb2xsZXIuZWxlbWVudC5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUoKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZHJvcGRvd24gPSBuZXcgTVdFZGl0b3IuY29yZS5kcm9wZG93bih7XG4gICAgICAgICAgICAgICAgZGF0YTogdGhpcy5fYXZhaWxhYmxlVGFncyxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogcm9vdFNjb3BlLmxhbmcoJ0Zvcm1hdCcpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGRyb3Bkb3duLnNlbGVjdC5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUsIHZhbCkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnZm9ybWF0QmxvY2snLCBmYWxzZSwgZS5kZXRhaWwudmFsdWUpO1xuICAgICAgICAgICAgICAgIC8qdmFyIHNlbCA9IHNjb3BlLmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgICAgIHZhciByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KDApO1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KHZhbC52YWx1ZSk7XG5cbiAgICAgICAgICAgICAgICB2YXIgZGlzYWJsZVNlbGVjdGlvbiA9IHRydWU7XG5cbiAgICAgICAgICAgICAgICBpZihzZWwuaXNDb2xsYXBzZWQgfHwgZGlzYWJsZVNlbGVjdGlvbikge1xuICAgICAgICAgICAgICAgICAgICB2YXIgc2VsZWN0aW9uRWxlbWVudCA9IGFwaS5lbGVtZW50Tm9kZShzZWwuZm9jdXNOb2RlKTtcbiAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUuJGVkaXRBcmVhWzBdICE9PSBzZWxlY3Rpb25FbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5zZXRUYWcoc2VsZWN0aW9uRWxlbWVudCwgdmFsLnZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHdoaWxlIChzZWxlY3Rpb25FbGVtZW50LmZpcnN0Q2hpbGQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbC5hcHBlbmRDaGlsZChzZWxlY3Rpb25FbGVtZW50LmZpcnN0Q2hpbGQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgc2VsZWN0aW9uRWxlbWVudC5hcHBlbmRDaGlsZChlbCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgdmFyIG5ld1JhbmdlID0gc2NvcGUuYWN0aW9uV2luZG93LmRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XG4gICAgICAgICAgICAgICAgICAgIG5ld1JhbmdlLnNldFN0YXJ0KHNlbC5hbmNob3JOb2RlLCBzZWwuYW5jaG9yT2Zmc2V0KTtcbiAgICAgICAgICAgICAgICAgICAgbmV3UmFuZ2UuY29sbGFwc2UodHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIHNlbC5yZW1vdmVBbGxSYW5nZXMoKTtcbiAgICAgICAgICAgICAgICAgICAgc2VsLmFkZFJhbmdlKHJhbmdlKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICByYW5nZS5zdXJyb3VuZENvbnRlbnRzKGVsKTtcbiAgICAgICAgICAgICAgICB9Ki9cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIGRyb3Bkb3duLnJvb3Q7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICBmb250U2VsZWN0b3I6IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIHZhciBjc3MgPSBvcHQuY3NzO1xuICAgICAgICAgICAgICAgIHZhciBmb250ID0gY3NzLmZvbnQoKTtcbiAgICAgICAgICAgICAgICB2YXIgZmFtaWx5X2FycmF5ID0gZm9udC5mYW1pbHkuc3BsaXQoJywnKSwgZmFtO1xuICAgICAgICAgICAgICAgIGlmIChmYW1pbHlfYXJyYXkubGVuZ3RoID09PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIGZhbSA9IGZvbnQuZmFtaWx5O1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGZhbSA9IGZhbWlseV9hcnJheS5zaGlmdCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBmYW0gPSBmYW0ucmVwbGFjZSgvWydcIl0rL2csICcnKTtcbiAgICAgICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50LmRpc3BsYXlWYWx1ZShmYW0pO1xuICAgICAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKCk7XG5cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZHJvcGRvd24gPSBuZXcgTVdFZGl0b3IuY29yZS5kcm9wZG93bih7XG4gICAgICAgICAgICAgICAgZGF0YTogW1xuICAgICAgICAgICAgICAgICAgICB7IGxhYmVsOiAnQXJpYWwgMScsIHZhbHVlOiAnQXJpYWwnIH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdWZXJkYW5hIDEnLCB2YWx1ZTogJ1ZlcmRhbmEnIH0sXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogcm9vdFNjb3BlLmxhbmcoJ0ZvbnQnKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBkcm9wZG93bi5zZWxlY3Qub24oJ2NoYW5nZScsIGZ1bmN0aW9uIChlLCB2YWwsIGIpIHtcbiAgICAgICAgICAgICAgICBhcGkuZm9udEZhbWlseSh2YWwudmFsdWUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZHJvcGRvd24ucm9vdDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgIHVuZG9SZWRvOiBmdW5jdGlvbihzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLnJvb3QgPSBNV0VkaXRvci5jb3JlLmVsZW1lbnQoKTtcbiAgICAgICAgICAgIHRoaXMucm9vdC5hZGRDbGFzcygnbXctdWktYnRuLW5hdiBtdy1lZGl0b3Itc3RhdGUtY29tcG9uZW50JylcbiAgICAgICAgICAgIHZhciB1bmRvID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ21kaS11bmRvJyxcbiAgICAgICAgICAgICAgICAgICAgdG9vbHRpcDogcm9vdFNjb3BlLmxhbmcoJ1VuZG8nKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdW5kby5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5zdGF0ZS51bmRvKCk7XG4gICAgICAgICAgICAgICAgcm9vdFNjb3BlLl9zeW5jVGV4dEFyZWEoKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB2YXIgcmVkbyA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktcmVkbycsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6IHJvb3RTY29wZS5sYW5nKCdSZWRvJylcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJlZG8ub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICByb290U2NvcGUuc3RhdGUucmVkbygpO1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5fc3luY1RleHRBcmVhKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRoaXMucm9vdC5ub2RlLmFwcGVuZENoaWxkKHVuZG8ubm9kZSk7XG4gICAgICAgICAgICB0aGlzLnJvb3Qubm9kZS5hcHBlbmRDaGlsZChyZWRvLm5vZGUpO1xuICAgICAgICAgICAgJChyb290U2NvcGUuc3RhdGUpLm9uKCdzdGF0ZVJlY29yZCcsIGZ1bmN0aW9uKGUsIGRhdGEpe1xuICAgICAgICAgICAgICAgIHVuZG8ubm9kZS5kaXNhYmxlZCA9ICFkYXRhLmhhc05leHQ7XG4gICAgICAgICAgICAgICAgcmVkby5ub2RlLmRpc2FibGVkID0gIWRhdGEuaGFzUHJldjtcbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAub24oJ3N0YXRlVW5kbyBzdGF0ZVJlZG8nLCBmdW5jdGlvbihlLCBkYXRhKXtcbiAgICAgICAgICAgICAgICBpZighZGF0YS5hY3RpdmUgfHwgIWRhdGEuYWN0aXZlLnRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICB1bmRvLm5vZGUuZGlzYWJsZWQgPSAhZGF0YS5oYXNOZXh0O1xuICAgICAgICAgICAgICAgICAgICByZWRvLm5vZGUuZGlzYWJsZWQgPSAhZGF0YS5oYXNQcmV2O1xuICAgICAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5ib2R5LmNvbnRhaW5zKGRhdGEuYWN0aXZlLnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChkYXRhLmFjdGl2ZS50YXJnZXQpLmh0bWwoZGF0YS5hY3RpdmUudmFsdWUpO1xuICAgICAgICAgICAgICAgIH0gZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgaWYoZGF0YS5hY3RpdmUudGFyZ2V0LmlkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5nZXRFbGVtZW50QnlJZChkYXRhLmFjdGl2ZS50YXJnZXQuaWQpKS5odG1sKGRhdGEuYWN0aXZlLnZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZihkYXRhLmFjdGl2ZS5wcmV2KSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoZGF0YS5hY3RpdmUucHJldikuaHRtbChkYXRhLmFjdGl2ZS5wcmV2VmFsdWUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAvLyBtdy5kcmFnLmxvYWRfbmV3X21vZHVsZXMoKTtcbiAgICAgICAgICAgICAgICB1bmRvLm5vZGUuZGlzYWJsZWQgPSAhZGF0YS5oYXNOZXh0O1xuICAgICAgICAgICAgICAgIHJlZG8ubm9kZS5kaXNhYmxlZCA9ICFkYXRhLmhhc1ByZXY7XG4gICAgICAgICAgICAgICAgJChzY29wZSkudHJpZ2dlcihlLnR5cGUsIFtkYXRhXSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciBkYXRhID0gcm9vdFNjb3BlLnN0YXRlLmV2ZW50RGF0YSgpO1xuICAgICAgICAgICAgICAgIHVuZG8ubm9kZS5kaXNhYmxlZCA9ICFkYXRhLmhhc05leHQ7XG4gICAgICAgICAgICAgICAgcmVkby5ub2RlLmRpc2FibGVkID0gIWRhdGEuaGFzUHJldjtcbiAgICAgICAgICAgIH0sIDc4KTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLnJvb3Q7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICAndWwnOiBmdW5jdGlvbihzY29wZSwgYXBpLCByb290U2NvcGUpe1xuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZm9ybWF0LWxpc3QtYnVsbGV0ZWQnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnaW5zZXJ0VW5vcmRlcmVkTGlzdCcpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgJ29sJzogZnVuY3Rpb24oc2NvcGUsIGFwaSwgcm9vdFNjb3BlKXtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWZvcm1hdC1saXN0LW51bWJlcmVkIHRpcCcsXG4gICAgICAgICAgICAgICAgICAgICdkYXRhLXRpcCc6ICdPcmRlcmVkIGxpc3QnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnaW5zZXJ0T3JkZXJlZExpc3QnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmNoZWNrU2VsZWN0aW9uID0gZnVuY3Rpb24gKG9wdCkge1xuICAgICAgICAgICAgb3B0LmNvbnRyb2xsZXIuZWxlbWVudC5ub2RlLmRpc2FibGVkID0gIW9wdC5hcGkuaXNTZWxlY3Rpb25FZGl0YWJsZShvcHQuc2VsZWN0aW9uKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgICdpbmRlbnQnOiBmdW5jdGlvbihzY29wZSwgYXBpLCByb290U2NvcGUpe1xuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZm9ybWF0LWluZGVudC1pbmNyZWFzZScsXG4gICAgICAgICAgICAgICAgICAgICdkYXRhLXRpcCc6ICdJbmRlbnQnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnaW5kZW50Jyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICAnb3V0ZGVudCc6IGZ1bmN0aW9uKHNjb3BlLCBhcGksIHJvb3RTY29wZSl7XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ21kaS1mb3JtYXQtaW5kZW50LWRlY3JlYXNlJyxcbiAgICAgICAgICAgICAgICAgICAgJ2RhdGEtdGlwJzogJ0luZGVudCdcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGVsLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgYXBpLmV4ZWNDb21tYW5kKCdvdXRkZW50Jyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICByZW1vdmVGb3JtYXQ6IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWZvcm1hdC1jbGVhcicsXG4gICAgICAgICAgICAgICAgICAgIHRvb2x0aXA6ICdSZW1vdmUgRm9ybWF0J1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWwub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBhcGkuZXhlY0NvbW1hbmQoJ3JlbW92ZUZvcm1hdCcpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuY2hlY2tTZWxlY3Rpb24gPSBmdW5jdGlvbiAob3B0KSB7XG4gICAgICAgICAgICBvcHQuY29udHJvbGxlci5lbGVtZW50Lm5vZGUuZGlzYWJsZWQgPSAhb3B0LmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKG9wdC5zZWxlY3Rpb24pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSB0aGlzLnJlbmRlcigpO1xuICAgIH0sXG4gICAgdW5saW5rOiBmdW5jdGlvbiAoc2NvcGUsIGFwaSwgcm9vdFNjb3BlKSB7XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5idXR0b24oe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ21kaS1saW5rLW9mZicsIHRvb2x0aXA6ICdVbmxpbmsnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgndW5saW5rJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICB0ZXh0Q29sb3I6IGZ1bmN0aW9uIChzY29wZSwgYXBpLCByb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBNV0VkaXRvci5jb3JlLmNvbG9yUGlja2VyKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZm9ybWF0LWNvbG9yLXRleHQnLCB0b29sdGlwOiAnVGV4dCBjb2xvcidcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGVsLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSwgdmFsKSB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2codmFsKVxuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnZm9yZUNvbG9yJywgZmFsc2UsIHZhbCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICB0ZXh0QmFja2dyb3VuZENvbG9yOiBmdW5jdGlvbiAoc2NvcGUsIGFwaSwgcm9vdFNjb3BlKSB7XG4gICAgICAgIHRoaXMucmVuZGVyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGVsID0gTVdFZGl0b3IuY29yZS5jb2xvclBpY2tlcih7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWZvcm1hdC1jb2xvci1maWxsJywgdG9vbHRpcDogJ1RleHQgYmFja2dyb3VuZCBjb2xvcidcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGVsLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSwgdmFsKSB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZSwgdmFsKVxuICAgICAgICAgICAgICAgIGFwaS5leGVjQ29tbWFuZCgnYmFja2NvbG9yJywgZmFsc2UsIHZhbCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICB0YWJsZTogZnVuY3Rpb24gKHNjb3BlLCBhcGksIHJvb3RTY29wZSkge1xuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktdGFibGUtbGFyZ2UnLCB0b29sdGlwOiAnSW5zZXJ0IFRhYmxlJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWwub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBhcGkuaW5zZXJ0SFRNTCgnPHRhYmxlIGNsYXNzPVwibXctdWktdGFibGVcIiBib3JkZXI9XCIxXCIgd2lkdGg9XCIxMDAlXCI+PHRyPjx0ZD48L3RkPjx0ZD48L3RkPjwvdHI+PHRyPjx0ZD48L3RkPjx0ZD48L3RkPjwvdHI+PC90YWJsZT4nKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmNoZWNrU2VsZWN0aW9uID0gZnVuY3Rpb24gKG9wdCkge1xuICAgICAgICAgICAgb3B0LmNvbnRyb2xsZXIuZWxlbWVudC5ub2RlLmRpc2FibGVkID0gIW9wdC5hcGkuaXNTZWxlY3Rpb25FZGl0YWJsZShvcHQuc2VsZWN0aW9uKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgIHdvcmRQYXN0ZTogZnVuY3Rpb24gKHNjb3BlLCBhcGksIHJvb3RTY29wZSkge1xuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtZGktZmlsZS13b3JkJywgdG9vbHRpcDogJ1Bhc3RlIGZyb20gV29yZCdcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGVsLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgYXBpLnNhdmVTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICB2YXIgZGlhbG9nO1xuICAgICAgICAgICAgICAgIHZhciBvayA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlubmVySFRNTDogcm9vdFNjb3BlLmxhbmcoJ09LJylcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHZhciBjYW5jZWwgPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpbm5lckhUTUw6IHJvb3RTY29wZS5sYW5nKCdDYW5jZWwnKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgdmFyIGNsZWFuRWwgPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnRFZGl0YWJsZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgIGF1dG9mb2N1czogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0eWxlOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiAnMjUwcHgnXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgZm9vdGVyID0gbXcuZWxlbWVudCgpO1xuICAgICAgICAgICAgICAgIG9rLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29udGVudCA9IGNsZWFuRWwuaHRtbCgpLnRyaW0oKTtcbiAgICAgICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBhcGkucmVzdG9yZVNlbGVjdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICBpZihjb250ZW50KXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGFwaS5pbnNlcnRIVE1MKGFwaS5jbGVhbldvcmQoY29udGVudCkpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBmb290ZXIuYXBwZW5kKGNhbmNlbCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLmFwcGVuZChvayk7XG4gICAgICAgICAgICAgICAgZGlhbG9nID0gbXcuZGlhbG9nKHtcbiAgICAgICAgICAgICAgICAgICAgY29udGVudDogY2xlYW5FbC5ub2RlLFxuICAgICAgICAgICAgICAgICAgICBmb290ZXI6IGZvb3Rlci5ub2RlXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5jaGVja1NlbGVjdGlvbiA9IGZ1bmN0aW9uIChvcHQpIHtcbiAgICAgICAgICAgIG9wdC5jb250cm9sbGVyLmVsZW1lbnQubm9kZS5kaXNhYmxlZCA9ICFvcHQuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUob3B0LnNlbGVjdGlvbik7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcblxuXG5cbn07XG4iLCJNV0VkaXRvci5jb3JlID0ge1xuICAgIGJ1dHRvbjogZnVuY3Rpb24oY29uZmlnKSB7XG4gICAgICAgIGNvbmZpZyA9IGNvbmZpZyB8fCB7fTtcbiAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgdGFnOiAnYnV0dG9uJyxcbiAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpIG13LWVkaXRvci1jb250cm9sbGVyLWNvbXBvbmVudCBtdy1lZGl0b3ItY29udHJvbGxlci1idXR0b24nLFxuICAgICAgICAgICAgICAgIHR5cGU6ICdidXR0b24nXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIGlmIChjb25maWcucHJvcHMgJiYgY29uZmlnLnByb3BzLmNsYXNzTmFtZSl7XG4gICAgICAgICAgICBjb25maWcucHJvcHMuY2xhc3NOYW1lID0gZGVmYXVsdHMucHJvcHMuY2xhc3NOYW1lICsgJyAnICsgY29uZmlnLnByb3BzLmNsYXNzTmFtZTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgc2V0dGluZ3MgPSAkLmV4dGVuZCh0cnVlLCB7fSwgZGVmYXVsdHMsIGNvbmZpZyk7XG4gICAgICAgIHJldHVybiBtdy5lbGVtZW50KHNldHRpbmdzKTtcbiAgICB9LFxuICAgIGNvbG9yUGlja2VyOiBmdW5jdGlvbihjb25maWcpIHtcbiAgICAgICAgY29uZmlnID0gY29uZmlnIHx8IHt9O1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWVkaXRvci1jb250cm9sbGVyLWNvbXBvbmVudCdcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdmFyIHNldHRpbmdzID0gJC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCBjb25maWcpO1xuXG4gICAgICAgIHZhciBlbCA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHNldHRpbmdzKTtcbiAgICAgICAgZWwuYWRkQ2xhc3MoJ213LWVkaXRvci1jb2xvci1waWNrZXInKVxuICAgICAgICB2YXIgaW5wdXQgPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgIHRhZzogJ2lucHV0JyxcbiAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgdHlwZTogJ2NvbG9yJyxcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy1lZGl0b3ItY29sb3ItcGlja2VyLW5vZGUnXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgdGltZSA9IG51bGw7XG4gICAgICAgIGlucHV0Lm9uKCdpbnB1dCcsIGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgY2xlYXJUaW1lb3V0KHRpbWUpO1xuICAgICAgICAgICAgdGltZSA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKGVsLCBub2RlKXtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhub2RlLnZhbHVlKVxuICAgICAgICAgICAgICAgIGVsLnRyaWdnZXIoJ2NoYW5nZScsIG5vZGUudmFsdWUpO1xuICAgICAgICAgICAgfSwgMjEwLCBlbCwgdGhpcyk7XG4gICAgICAgIH0pO1xuICAgICAgICBlbC5hcHBlbmQoaW5wdXQpO1xuICAgICAgICByZXR1cm4gZWw7XG4gICAgfSxcbiAgICBlbGVtZW50OiBmdW5jdGlvbihjb25maWcpIHtcbiAgICAgICAgY29uZmlnID0gY29uZmlnIHx8IHt9O1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWVkaXRvci1jb250cm9sbGVyLWNvbXBvbmVudCdcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdmFyIHNldHRpbmdzID0gJC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCBjb25maWcpO1xuICAgICAgICB2YXIgZWwgPSBtdy5lbGVtZW50KHNldHRpbmdzKTtcbiAgICAgICAgZWwub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiBlbDtcbiAgICB9LFxuXG4gICAgX2Ryb3Bkb3duT3B0aW9uOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAvLyBkYXRhOiB7IGxhYmVsOiBzdHJpbmcsIHZhbHVlOiBhbnkgfSxcbiAgICAgICAgdmFyIG9wdGlvbiA9IE1XRWRpdG9yLmNvcmUuZWxlbWVudCh7XG4gICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWVkaXRvci1kcm9wZG93bi1vcHRpb24nLFxuICAgICAgICAgICAgICAgIGlubmVySFRNTDogZGF0YS5sYWJlbFxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgb3B0aW9uLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIH0pO1xuICAgICAgICBvcHRpb24udmFsdWUgPSBkYXRhLnZhbHVlO1xuICAgICAgICByZXR1cm4gb3B0aW9uO1xuICAgIH0sXG4gICAgZHJvcGRvd246IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgIHZhciBsc2NvcGUgPSB0aGlzO1xuICAgICAgICB0aGlzLnJvb3QgPSBNV0VkaXRvci5jb3JlLmVsZW1lbnQoKTtcbiAgICAgICAgdGhpcy5zZWxlY3QgPSBNV0VkaXRvci5jb3JlLmVsZW1lbnQoe1xuICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy1lZGl0b3ItY29udHJvbGxlci1jb21wb25lbnQgbXctZWRpdG9yLWNvbnRyb2xsZXItY29tcG9uZW50LXNlbGVjdCdcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHZhciBkaXNwbGF5VmFsTm9kZSA9IE1XRWRpdG9yLmNvcmUuYnV0dG9uKHtcbiAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctZWRpdG9yLXNlbGVjdC1kaXNwbGF5LXZhbHVlJyxcbiAgICAgICAgICAgICAgICBpbm5lckhUTUw6IG9wdGlvbnMucGxhY2Vob2xkZXIgfHwgJydcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgdmFyIHZhbHVlSG9sZGVyID0gTVdFZGl0b3IuY29yZS5lbGVtZW50KHtcbiAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctZWRpdG9yLWNvbnRyb2xsZXItY29tcG9uZW50LXNlbGVjdC12YWx1ZXMtaG9sZGVyJyxcblxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5yb290LnZhbHVlID0gZnVuY3Rpb24gKHZhbCl7XG4gICAgICAgICAgICB0aGlzLmRpc3BsYXlWYWx1ZSh2YWwubGFiZWwpO1xuICAgICAgICAgICAgdGhpcy52YWx1ZSh2YWwudmFsdWUpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucm9vdC5kaXNwbGF5VmFsdWUgPSBmdW5jdGlvbiAodmFsKSB7XG4gICAgICAgICAgICBkaXNwbGF5VmFsTm9kZS50ZXh0KHZhbCB8fCBvcHRpb25zLnBsYWNlaG9sZGVyIHx8ICcnKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNlbGVjdC5hcHBlbmQoZGlzcGxheVZhbE5vZGUpO1xuICAgICAgICB0aGlzLnNlbGVjdC5hcHBlbmQodmFsdWVIb2xkZXIpO1xuICAgICAgICB0aGlzLnNlbGVjdC52YWx1ZUhvbGRlciA9IHZhbHVlSG9sZGVyO1xuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IG9wdGlvbnMuZGF0YS5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgdmFyIGR0ID0gb3B0aW9ucy5kYXRhW2ldO1xuICAgICAgICAgICAgKGZ1bmN0aW9uIChkdCl7XG4gICAgICAgICAgICAgICAgdmFyIG9wdCA9IE1XRWRpdG9yLmNvcmUuX2Ryb3Bkb3duT3B0aW9uKGR0KTtcbiAgICAgICAgICAgICAgICBvcHQub24oJ2NsaWNrJywgZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgIGxzY29wZS5zZWxlY3QudHJpZ2dlcignY2hhbmdlJywgZHQpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHZhbHVlSG9sZGVyLmFwcGVuZChvcHQpO1xuICAgICAgICAgICAgfSkoZHQpO1xuXG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnNlbGVjdC5vbignY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgdmFyIHdyYXBwZXIgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyh0aGlzLm5vZGUsICdtdy1lZGl0b3Itd3JhcHBlcicpO1xuICAgICAgICAgICAgaWYgKHdyYXBwZXIpIHtcbiAgICAgICAgICAgICAgICB2YXIgZWRPZmYgPSB3cmFwcGVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgICAgICAgICAgICAgIHZhciBzZWxPZmYgPSB0aGlzLm5vZGUuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgICAgICAgICAgICAgdGhpcy52YWx1ZUhvbGRlci5jc3Moe1xuICAgICAgICAgICAgICAgICAgICBtYXhIZWlnaHQ6IGVkT2ZmLmhlaWdodCAtIChzZWxPZmYudG9wIC0gZWRPZmYudG9wKVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuZWxlbWVudCh0aGlzKS50b2dnbGVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLnJvb3QuYXBwZW5kKHRoaXMuc2VsZWN0KTtcbiAgICB9LFxuICAgIF9wcmVTZWxlY3Q6IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIHZhciBhbGwgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubXctZWRpdG9yLWNvbnRyb2xsZXItY29tcG9uZW50LXNlbGVjdC5hY3RpdmUsIC5tdy1iYXItY29udHJvbC1pdGVtLWdyb3VwLmFjdGl2ZScpO1xuICAgICAgICB2YXIgcGFyZW50ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKG5vZGUgPyBub2RlLnBhcmVudE5vZGUgOiBudWxsLCBbJ213LWVkaXRvci1jb250cm9sbGVyLWNvbXBvbmVudC1zZWxlY3QnLCdtdy1iYXItY29udHJvbC1pdGVtLWdyb3VwJ10pO1xuICAgICAgICB2YXIgaSA9IDAsIGwgPSBhbGwubGVuZ3RoO1xuICAgICAgICBmb3IgKCA7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIGlmKCFub2RlIHx8IChhbGxbaV0gIT09IG5vZGUgJiYgYWxsW2ldICE9PSBwYXJlbnQpKSB7XG4gICAgICAgICAgICAgICAgYWxsW2ldLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufTtcbiIsIlxuXG5cblxuXG5cblxuXG5cblxudmFyIEVkaXRvclByZWRlZmluZWRDb250cm9scyA9IHtcbiAgICAnZGVmYXVsdCc6IFtcbiAgICAgICAgWyAnYm9sZCcsICdpdGFsaWMnLCAndW5kZXJsaW5lJyBdLFxuICAgIF0sXG4gICAgc21hbGxFZGl0b3JEZWZhdWx0OiBbXG4gICAgICAgIFsnYm9sZCcsICdpdGFsaWMnLCAnfCcsICdsaW5rJ11cbiAgICBdXG59O1xuXG52YXIgTVdFZGl0b3IgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgcmVnaW9uczogbnVsbCxcbiAgICAgICAgZG9jdW1lbnQ6IGRvY3VtZW50LFxuICAgICAgICBleGVjdXRpb25Eb2N1bWVudDogZG9jdW1lbnQsXG4gICAgICAgIG1vZGU6ICdkaXYnLCAvLyBpZnJhbWUgfCBkaXYgfCBkb2N1bWVudFxuICAgICAgICBjb250cm9sczogJ2RlZmF1bHQnLFxuICAgICAgICBzbWFsbEVkaXRvcjogZmFsc2UsXG4gICAgICAgIHNjcmlwdHM6IFtdLFxuICAgICAgICBjc3NGaWxlczogW10sXG4gICAgICAgIGNvbnRlbnQ6ICcnLFxuICAgICAgICB1cmw6IG51bGwsXG4gICAgICAgIHNraW46ICdkZWZhdWx0JyxcbiAgICAgICAgc3RhdGU6IG51bGwsXG4gICAgICAgIGlmcmFtZUFyZWFTZWxlY3RvcjogbnVsbCxcbiAgICAgICAgYWN0aXZlQ2xhc3M6ICdhY3RpdmUtY29udHJvbCcsXG4gICAgICAgIGludGVyYWN0aW9uQ29udHJvbHM6IFtcbiAgICAgICAgICAgICdpbWFnZScsICdsaW5rVG9vbHRpcCcsICd0YWJsZU1hbmFnZXInXG4gICAgICAgIF0sXG4gICAgICAgIGxhbmd1YWdlOiAnZW4nLFxuICAgICAgICByb290UGF0aDogbXcuc2V0dGluZ3MubW9kdWxlc191cmwgKyAnbWljcm93ZWJlci9hcGkvZWRpdG9yJyxcbiAgICAgICAgZWRpdE1vZGU6ICdub3JtYWwnLCAvLyBub3JtYWwgfCBsaXZlZWRpdFxuICAgICAgICBiYXI6IG51bGwsXG4gICAgfTtcblxuICAgIHRoaXMuYWN0aW9uV2luZG93ID0gd2luZG93O1xuXG4gICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cbiAgICB0aGlzLnNldHRpbmdzID0gbXcub2JqZWN0LmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuXG5cbiAgICBpZiAodHlwZW9mIHRoaXMuc2V0dGluZ3MuY29udHJvbHMgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgIHRoaXMuc2V0dGluZ3MuY29udHJvbHMgPSBFZGl0b3JQcmVkZWZpbmVkQ29udHJvbHNbdGhpcy5zZXR0aW5ncy5jb250cm9sc10gfHwgRWRpdG9yUHJlZGVmaW5lZENvbnRyb2xzLmRlZmF1bHQ7XG4gICAgfVxuXG4gICAgaWYoISF0aGlzLnNldHRpbmdzLnNtYWxsRWRpdG9yKSB7XG4gICAgICAgIGlmKHRoaXMuc2V0dGluZ3Muc21hbGxFZGl0b3IgPT09IHRydWUpIHtcbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3Muc21hbGxFZGl0b3IgPSBFZGl0b3JQcmVkZWZpbmVkQ29udHJvbHMuc21hbGxFZGl0b3JEZWZhdWx0O1xuICAgICAgICB9IGVsc2UgaWYgKHR5cGVvZiB0aGlzLnNldHRpbmdzLnNtYWxsRWRpdG9yID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncy5zbWFsbEVkaXRvciA9IEVkaXRvclByZWRlZmluZWRDb250cm9sc1t0aGlzLnNldHRpbmdzLnNtYWxsRWRpdG9yXSB8fCBFZGl0b3JQcmVkZWZpbmVkQ29udHJvbHMuc21hbGxFZGl0b3JEZWZhdWx0O1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgdGhpcy5kb2N1bWVudCA9IHRoaXMuc2V0dGluZ3MuZG9jdW1lbnQ7XG5cbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xuXG4gICAgaWYoIXRoaXMuc2V0dGluZ3Muc2VsZWN0b3IgJiYgdGhpcy5zZXR0aW5ncy5lbGVtZW50KXtcbiAgICAgICAgdGhpcy5zZXR0aW5ncy5zZWxlY3RvciA9IHRoaXMuc2V0dGluZ3MuZWxlbWVudDtcbiAgICB9XG5cbiAgICBpZighdGhpcy5zZXR0aW5ncy5zZWxlY3RvciAmJiB0aGlzLnNldHRpbmdzLm1vZGUgPT09ICdkb2N1bWVudCcpe1xuICAgICAgICB0aGlzLnNldHRpbmdzLnNlbGVjdG9yID0gdGhpcy5kb2N1bWVudC5ib2R5O1xuICAgIH1cbiAgICBpZighdGhpcy5zZXR0aW5ncy5zZWxlY3Rvcil7XG4gICAgICAgIGNvbnNvbGUud2FybignTVdFZGl0b3IgLSBzZWxlY3RvciBub3Qgc3BlY2lmaWVkJyk7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICB0aGlzLnNldHRpbmdzLnNlbGVjdG9yTm9kZSA9IG13LiQodGhpcy5zZXR0aW5ncy5zZWxlY3RvcilbMF07XG5cbiAgICBpZiAodGhpcy5zZXR0aW5ncy5zZWxlY3Rvck5vZGUpIHtcbiAgICAgICAgdGhpcy5zZXR0aW5ncy5zZWxlY3Rvck5vZGUuX19NV0VkaXRvciA9IHRoaXM7XG4gICAgfVxuXG4gICAgdGhpcy5zZXR0aW5ncy5pc1RleHRBcmVhID0gdGhpcy5zZXR0aW5ncy5zZWxlY3Rvck5vZGUubm9kZU5hbWUgJiYgdGhpcy5zZXR0aW5ncy5zZWxlY3Rvck5vZGUubm9kZU5hbWUgPT09ICdURVhUQVJFQSc7XG5cblxuICAgIHRoaXMuZ2V0U2VsZWN0aW9uID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gc2NvcGUuYWN0aW9uV2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgIH07XG5cbiAgICB0aGlzLnNlbGVjdGlvbiA9IHRoaXMuZ2V0U2VsZWN0aW9uKCk7XG5cbiAgICB0aGlzLl9pbnRlcmFjdGlvblRpbWUgPSBuZXcgRGF0ZSgpLmdldFRpbWUoKTtcblxuICAgIHRoaXMuaW50ZXJhY3Rpb25Db250cm9scyA9IFtdO1xuICAgIHRoaXMuY3JlYXRlSW50ZXJhY3Rpb25Db250cm9scyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5zZXR0aW5ncy5pbnRlcmFjdGlvbkNvbnRyb2xzLmZvckVhY2goZnVuY3Rpb24oY3RybCl7XG4gICAgICAgICAgICBpZiAoTVdFZGl0b3IuaW50ZXJhY3Rpb25Db250cm9sc1tjdHJsXSkge1xuICAgICAgICAgICAgICAgIHZhciBpbnQgPSBuZXcgTVdFZGl0b3IuaW50ZXJhY3Rpb25Db250cm9sc1tjdHJsXShzY29wZSwgc2NvcGUpO1xuICAgICAgICAgICAgICAgIGlmKCFpbnQuZWxlbWVudCl7XG4gICAgICAgICAgICAgICAgICAgIGludC5lbGVtZW50ID0gaW50LnJlbmRlcigpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBzY29wZS5hY3Rpb25XaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChpbnQuZWxlbWVudC5ub2RlKTtcbiAgICAgICAgICAgICAgICBzY29wZS5pbnRlcmFjdGlvbkNvbnRyb2xzLnB1c2goaW50KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMubGFuZyA9IGZ1bmN0aW9uIChrZXkpIHtcbiAgICAgICAgaWYgKE1XRWRpdG9yLmkxOG5bdGhpcy5zZXR0aW5ncy5sYW5ndWFnZV0gJiYgTVdFZGl0b3IuaTE4blt0aGlzLnNldHRpbmdzLmxhbmd1YWdlXVtrZXldKSB7XG4gICAgICAgICAgICByZXR1cm4gIE1XRWRpdG9yLmkxOG5bdGhpcy5zZXR0aW5ncy5sYW5ndWFnZV1ba2V5XTtcbiAgICAgICAgfVxuICAgICAgICAvL2NvbnNvbGUud2FybihrZXkgKyAnIGlzIG5vdCBzcGVjaWZpZWQgZm9yICcgKyB0aGlzLnNldHRpbmdzLmxhbmd1YWdlICsgJyBsYW5ndWFnZScpO1xuICAgICAgICByZXR1cm4ga2V5O1xuICAgIH07XG5cbiAgICB0aGlzLnJlcXVpcmUgPSBmdW5jdGlvbiAoKSB7XG5cbiAgICB9O1xuXG4gICAgdGhpcy5hZGREZXBlbmRlbmNpZXMgPSBmdW5jdGlvbiAob2JqKXtcbiAgICAgICAgdGhpcy5jb250cm9scy5mb3JFYWNoKGZ1bmN0aW9uIChjdHJsKSB7XG4gICAgICAgICAgICBpZiAoY3RybC5kZXBlbmRlbmNpZXMpIHtcbiAgICAgICAgICAgICAgICBjdHJsLmRlcGVuZGVuY2llcy5mb3JFYWNoKGZ1bmN0aW9uIChkZXApIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuYWRkRGVwZW5kZW5jeShkZXApO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5pbnRlcmFjdGlvbkNvbnRyb2xzLmZvckVhY2goZnVuY3Rpb24gKGludCkge1xuICAgICAgICAgICAgaWYgKGludC5kZXBlbmRlbmNpZXMpIHtcbiAgICAgICAgICAgICAgICBpbnQuZGVwZW5kZW5jaWVzLmZvckVhY2goZnVuY3Rpb24gKGRlcCkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5hZGREZXBlbmRlbmN5KGRlcCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgbm9kZSA9IHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdsaW5rJyk7XG4gICAgICAgIG5vZGUuaHJlZiA9IHRoaXMuc2V0dGluZ3Mucm9vdFBhdGggKyAnL2FyZWEtc3R5bGVzLmNzcyc7XG4gICAgICAgIG5vZGUudHlwZSA9ICd0ZXh0L2Nzcyc7XG4gICAgICAgIG5vZGUucmVsID0gJ3N0eWxlc2hlZXQnO1xuICAgICAgICBzY29wZS5hY3Rpb25XaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChub2RlKTtcbiAgICB9O1xuICAgIHRoaXMuYWRkRGVwZW5kZW5jeSA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgdGFyZ2V0V2luZG93ID0gb2JqLnRhcmdldFdpbmRvdyB8fCBzY29wZS5hY3Rpb25XaW5kb3c7XG4gICAgICAgIGlmICghdHlwZSkge1xuICAgICAgICAgICAgdHlwZSA9IHVybC5zcGxpdCgnLicpLnBvcCgpO1xuICAgICAgICB9XG4gICAgICAgIGlmKCF0eXBlIHx8ICF1cmwpIHJldHVybjtcbiAgICAgICAgdmFyIG5vZGU7XG4gICAgICAgIGlmKHR5cGUgPT09ICdjc3MnKSB7XG4gICAgICAgICAgICBub2RlID0gdGFyZ2V0V2luZG93LmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2xpbmsnKTtcbiAgICAgICAgICAgIG5vZGUucmVsID0gJ3N0eWxlc2hlZXQnO1xuICAgICAgICAgICAgbm9kZS5ocmVmID0gdXJsO1xuICAgICAgICAgICAgbm9kZS50eXBlID0gJ3RleHQvY3NzJztcbiAgICAgICAgfSBlbHNlIGlmKHR5cGUgPT09ICdqcycpIHtcbiAgICAgICAgICAgIG5vZGUgPSB0YXJnZXRXaW5kb3cuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc2NyaXB0Jyk7XG4gICAgICAgICAgICBub2RlLnNyYyA9IHVybDtcbiAgICAgICAgfVxuICAgICAgICB0YXJnZXRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChub2RlKTtcbiAgICB9O1xuXG4gICAgdGhpcy5pbnRlcmFjdGlvbkNvbnRyb2xzUnVuID0gZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgc2NvcGUuaW50ZXJhY3Rpb25Db250cm9scy5mb3JFYWNoKGZ1bmN0aW9uIChjdHJsKSB7XG4gICAgICAgICAgICBjdHJsLmludGVyYWN0KGRhdGEpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG5cbiAgICB0aGlzLmluaXRJbnRlcmFjdGlvbiA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGFpdCA9IDEwMCxcbiAgICAgICAgICAgIGN1cnJ0ID0gbmV3IERhdGUoKS5nZXRUaW1lKCk7XG4gICAgICAgIHRoaXMuaW50ZXJhY3Rpb25EYXRhID0ge307XG4gICAgICAgICQoc2NvcGUuYWN0aW9uV2luZG93LmRvY3VtZW50KS5vbignc2VsZWN0aW9uY2hhbmdlJywgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdzZWxlY3Rpb25jaGFuZ2UnLCBbe1xuICAgICAgICAgICAgICAgIGV2ZW50OiBlLFxuICAgICAgICAgICAgICAgIGludGVyYWN0aW9uRGF0YTogc2NvcGUuaW50ZXJhY3Rpb25EYXRhXG4gICAgICAgICAgICB9XSk7XG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgbWF4ID0gNzg7XG4gICAgICAgIHNjb3BlLiRlZGl0QXJlYS5vbigndG91Y2hzdGFydCB0b3VjaGVuZCBjbGljayBrZXlkb3duIGV4ZWNDb21tYW5kIG1vdXNlbW92ZSB0b3VjaG1vdmUnLCBmdW5jdGlvbihlKXtcbiAgICAgICAgICAgIHZhciBldmVudElzQWN0aW9uTGlrZSA9IGUudHlwZSA9PT0gJ2NsaWNrJyB8fCBlLnR5cGUgPT09ICdleGVjQ29tbWFuZCcgfHwgZS50eXBlID09PSAna2V5ZG93bic7XG4gICAgICAgICAgICB2YXIgZXZlbnQgPSBlLm9yaWdpbmFsZUV2ZW50ID8gZS5vcmlnaW5hbGVFdmVudCA6IGU7XG4gICAgICAgICAgICB2YXIgbG9jYWxUYXJnZXQgPSBldmVudC50YXJnZXQ7XG5cbiAgICAgICAgICAgIHZhciB3VGFyZ2V0ID0gbG9jYWxUYXJnZXQ7XG4gICAgICAgICAgICBpZihldmVudElzQWN0aW9uTGlrZSkge1xuICAgICAgICAgICAgICAgIHZhciBzaG91bGRDbG9zZVNlbGVjdHMgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB3aGlsZSAod1RhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY2MgPSB3VGFyZ2V0LmNsYXNzTGlzdDtcbiAgICAgICAgICAgICAgICAgICAgaWYoY2MpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKGNjLmNvbnRhaW5zKCdtdy1lZGl0b3ItY29udHJvbGxlci1jb21wb25lbnQtc2VsZWN0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZihjYy5jb250YWlucygnbXctYmFyLWNvbnRyb2wtaXRlbS1ncm91cCcpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYoY2MuY29udGFpbnMoJ213LWVkaXRvci1hcmVhJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzaG91bGRDbG9zZVNlbGVjdHMgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmKGNjLmNvbnRhaW5zKCdtdy1lZGl0b3ItZnJhbWUtYXJlYScpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2hvdWxkQ2xvc2VTZWxlY3RzID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZihjYy5jb250YWlucygnbXctZWRpdG9yLXdyYXBwZXInKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNob3VsZENsb3NlU2VsZWN0cyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgd1RhcmdldCA9IHdUYXJnZXQucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYoc2hvdWxkQ2xvc2VTZWxlY3RzKSB7XG4gICAgICAgICAgICAgICAgICAgIE1XRWRpdG9yLmNvcmUuX3ByZVNlbGVjdCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0aW1lID0gbmV3IERhdGUoKS5nZXRUaW1lKCk7XG4gICAgICAgICAgICBpZihldmVudElzQWN0aW9uTGlrZSB8fCAodGltZSAtIHNjb3BlLl9pbnRlcmFjdGlvblRpbWUpID4gbWF4KXtcbiAgICAgICAgICAgICAgICBpZiAoZS5wYWdlWCkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5pbnRlcmFjdGlvbkRhdGEucGFnZVggPSBlLnBhZ2VYO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5pbnRlcmFjdGlvbkRhdGEucGFnZVkgPSBlLnBhZ2VZO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBzY29wZS5faW50ZXJhY3Rpb25UaW1lID0gdGltZTtcbiAgICAgICAgICAgICAgICBzY29wZS5zZWxlY3Rpb24gPSBzY29wZS5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUuc2VsZWN0aW9uLnJhbmdlQ291bnQgPT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgdGFyZ2V0ID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKCBzY29wZS5zZWxlY3Rpb24uZ2V0UmFuZ2VBdCgwKS5jb21tb25BbmNlc3RvckNvbnRhaW5lciApO1xuICAgICAgICAgICAgICAgIHZhciBjc3MgPSBtdy5DU1NQYXJzZXIodGFyZ2V0KTtcbiAgICAgICAgICAgICAgICB2YXIgYXBpID0gc2NvcGUuYXBpO1xuXG5cbiAgICAgICAgICAgICAgICB2YXIgaXRlckRhdGEgPSB7XG4gICAgICAgICAgICAgICAgICAgIHNlbGVjdGlvbjogc2NvcGUuc2VsZWN0aW9uLFxuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHRhcmdldCxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxUYXJnZXQ6IGxvY2FsVGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICBpc0ltYWdlOiBsb2NhbFRhcmdldC5ub2RlTmFtZSA9PT0gJ0lNRycgfHwgdGFyZ2V0Lm5vZGVOYW1lID09PSAnSU1HJyxcbiAgICAgICAgICAgICAgICAgICAgY3NzOiBjc3MuZ2V0LFxuICAgICAgICAgICAgICAgICAgICBjc3NOYXRpdmU6IGNzcy5jc3MsXG4gICAgICAgICAgICAgICAgICAgIGV2ZW50OiBldmVudCxcbiAgICAgICAgICAgICAgICAgICAgYXBpOiBhcGksXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlOiBzY29wZSxcbiAgICAgICAgICAgICAgICAgICAgaXNFZGl0YWJsZTogc2NvcGUuYXBpLmlzU2VsZWN0aW9uRWRpdGFibGUoKSxcbiAgICAgICAgICAgICAgICAgICAgZXZlbnRJc0FjdGlvbkxpa2U6IGV2ZW50SXNBY3Rpb25MaWtlLFxuICAgICAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgICAgICBzY29wZS5pbnRlcmFjdGlvbkNvbnRyb2xzUnVuKGl0ZXJEYXRhKTtcbiAgICAgICAgICAgICAgICBzY29wZS5jb250cm9scy5mb3JFYWNoKGZ1bmN0aW9uIChjdHJsKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmKGN0cmwuY2hlY2tTZWxlY3Rpb24pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGN0cmwuY2hlY2tTZWxlY3Rpb24oe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNlbGVjdGlvbjogc2NvcGUuc2VsZWN0aW9uLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRyb2xsZXI6IGN0cmwsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGFyZ2V0OiB0YXJnZXQsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY3NzOiBjc3MuZ2V0LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNzc05hdGl2ZTogY3NzLmNzcyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhcGk6IGFwaSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBldmVudElzQWN0aW9uTGlrZTogZXZlbnRJc0FjdGlvbkxpa2UsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGU6IHNjb3BlLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlzRWRpdGFibGU6IHNjb3BlLmFwaS5pc1NlbGVjdGlvbkVkaXRhYmxlKClcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLmNyZWF0ZUludGVyYWN0aW9uQ29udHJvbHMoKVxuICAgIH07XG5cbiAgICB0aGlzLl9wcmV2ZW50RXZlbnRzID0gW107XG4gICAgdGhpcy5wcmV2ZW50RXZlbnRzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgbm9kZTtcbiAgICAgICAgaWYodGhpcy5hcmVhICYmIHRoaXMuX3ByZXZlbnRFdmVudHMuaW5kZXhPZih0aGlzLmFyZWEubm9kZSkgPT09IC0xKSB7XG4gICAgICAgICAgICB0aGlzLl9wcmV2ZW50RXZlbnRzLnB1c2godGhpcy5hcmVhLm5vZGUpO1xuICAgICAgICAgICAgbm9kZSA9IHRoaXMuYXJlYS5ub2RlO1xuICAgICAgICB9IGVsc2UgaWYoc2NvcGUuJGlmcmFtZUFyZWEgJiYgdGhpcy5fcHJldmVudEV2ZW50cy5pbmRleE9mKHNjb3BlLiRpZnJhbWVBcmVhWzBdKSA9PT0gLTEpIHtcbiAgICAgICAgICAgIHRoaXMuX3ByZXZlbnRFdmVudHMucHVzaChzY29wZS4kaWZyYW1lQXJlYVswXSk7XG4gICAgICAgICAgICBub2RlID0gc2NvcGUuJGlmcmFtZUFyZWFbMF07XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGN0cmxEb3duID0gZmFsc2U7XG4gICAgICAgIHZhciBjdHJsS2V5ID0gMTcsIHZLZXkgPSA4NiwgY0tleSA9IDY3LCB6S2V5ID0gOTA7XG4gICAgICAgIG5vZGUub25rZXlkb3duID0gZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IGN0cmxLZXkgfHwgZS5rZXlDb2RlID09PSA5MSkge1xuICAgICAgICAgICAgICAgIGN0cmxEb3duID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICgoY3RybERvd24gJiYgZS5rZXlDb2RlID09PSB6S2V5KSAvKnx8IChjdHJsRG93biAmJiBlLmtleUNvZGUgPT09IHZLZXkpKi8gfHwgKGN0cmxEb3duICYmIGUua2V5Q29kZSA9PT0gY0tleSkpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICBub2RlLm9ua2V5dXAgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSAxNyB8fCBlLmtleUNvZGUgPT09IDkxKSB7XG4gICAgICAgICAgICAgICAgY3RybERvd24gPSBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICB9O1xuICAgIHRoaXMuaW5pdFN0YXRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLnN0YXRlID0gdGhpcy5zZXR0aW5ncy5zdGF0ZSB8fCAobmV3IG13LlN0YXRlKCkpO1xuICAgIH07XG5cbiAgICB0aGlzLmNvbnRyb2xsZXJBY3RpdmUgPSBmdW5jdGlvbiAobm9kZSwgYWN0aXZlKSB7XG4gICAgICAgIG5vZGUuY2xhc3NMaXN0W2FjdGl2ZSA/ICdhZGQnIDogJ3JlbW92ZSddKHRoaXMuc2V0dGluZ3MuYWN0aXZlQ2xhc3MpO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZUZyYW1lID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLmZyYW1lID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpZnJhbWUnKTtcbiAgICAgICAgdGhpcy5mcmFtZS5jbGFzc05hbWUgPSAnbXctZWRpdG9yLWZyYW1lJztcbiAgICAgICAgdGhpcy5mcmFtZS5hbGxvdyA9ICdhY2NlbGVyb21ldGVyOyBhdXRvcGxheTsgZW5jcnlwdGVkLW1lZGlhOyBneXJvc2NvcGU7IHBpY3R1cmUtaW4tcGljdHVyZSc7XG4gICAgICAgIHRoaXMuZnJhbWUuYWxsb3dGdWxsc2NyZWVuID0gdHJ1ZTtcbiAgICAgICAgdGhpcy5mcmFtZS5zY3JvbGxpbmcgPSBcInllc1wiO1xuICAgICAgICB0aGlzLmZyYW1lLndpZHRoID0gXCIxMDAlXCI7XG4gICAgICAgIHRoaXMuZnJhbWUuZnJhbWVCb3JkZXIgPSBcIjBcIjtcbiAgICAgICAgaWYgKHRoaXMuc2V0dGluZ3MudXJsKSB7XG4gICAgICAgICAgICB0aGlzLmZyYW1lLnNyYyA9IHRoaXMuc2V0dGluZ3MudXJsO1xuICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgIH1cblxuICAgICAgICAkKHRoaXMuZnJhbWUpLm9uKCdsb2FkJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKCFzY29wZS5zZXR0aW5ncy5pZnJhbWVBcmVhU2VsZWN0b3IpIHtcbiAgICAgICAgICAgICAgICB2YXIgYXJlYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgICAgIGFyZWEuc3R5bGUub3V0bGluZSA9ICdub25lJztcbiAgICAgICAgICAgICAgICBhcmVhLmNsYXNzTmFtZSA9ICdtdy1lZGl0b3ItZnJhbWUtYXJlYSc7XG4gICAgICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3MuaWZyYW1lQXJlYVNlbGVjdG9yID0gICcuJyArIGFyZWEuY2xhc3NOYW1lO1xuICAgICAgICAgICAgICAgIHRoaXMuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5LmFwcGVuZChhcmVhKTtcbiAgICAgICAgICAgICAgICBhcmVhLnN0eWxlLm1pbkhlaWdodCA9ICcxMDBweCc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBzY29wZS4kaWZyYW1lQXJlYSA9ICQoc2NvcGUuc2V0dGluZ3MuaWZyYW1lQXJlYVNlbGVjdG9yLCB0aGlzLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQpO1xuXG4gICAgICAgICAgICBzY29wZS4kaWZyYW1lQXJlYS5odG1sKHNjb3BlLnNldHRpbmdzLmNvbnRlbnQgfHwgJycpO1xuICAgICAgICAgICAgc2NvcGUuJGlmcmFtZUFyZWEub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHNjb3BlLnJlZ2lzdGVyQ2hhbmdlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHNjb3BlLmFjdGlvbldpbmRvdyA9IHRoaXMuY29udGVudFdpbmRvdztcbiAgICAgICAgICAgIHNjb3BlLiRlZGl0QXJlYSA9IHNjb3BlLiRpZnJhbWVBcmVhO1xuICAgICAgICAgICAgbXcudG9vbHMuaWZyYW1lQXV0b0hlaWdodChzY29wZS5mcmFtZSk7XG5cbiAgICAgICAgICAgIHNjb3BlLnByZXZlbnRFdmVudHMoKTtcbiAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ3JlYWR5Jyk7XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLndyYXBwZXIuYXBwZW5kQ2hpbGQodGhpcy5mcmFtZSk7XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlV3JhcHBlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy53cmFwcGVyID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgdGhpcy53cmFwcGVyLmNsYXNzTmFtZSA9ICdtdy1lZGl0b3Itd3JhcHBlciBtdy1lZGl0b3ItJyArIHRoaXMuc2V0dGluZ3Muc2tpbjtcbiAgICB9O1xuXG4gICAgdGhpcy5fc3luY1RleHRBcmVhID0gZnVuY3Rpb24gKGNvbnRlbnQpIHtcbiAgICAgICAgY29udGVudCA9IGNvbnRlbnQgfHwgc2NvcGUuJGVkaXRBcmVhLmh0bWwoKTtcbiAgICAgICAgaWYgKHNjb3BlLnNldHRpbmdzLmlzVGV4dEFyZWEpIHtcbiAgICAgICAgICAgICQoc2NvcGUuc2V0dGluZ3Muc2VsZWN0b3JOb2RlKS52YWwoY29udGVudCk7XG4gICAgICAgICAgICAkKHNjb3BlLnNldHRpbmdzLnNlbGVjdG9yTm9kZSkudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5fcmVnaXN0ZXJDaGFuZ2VUaW1lciA9IG51bGw7XG4gICAgdGhpcy5yZWdpc3RlckNoYW5nZSA9IGZ1bmN0aW9uIChjb250ZW50KSB7XG4gICAgICAgIGNsZWFyVGltZW91dCh0aGlzLl9yZWdpc3RlckNoYW5nZVRpbWVyKTtcbiAgICAgICAgdGhpcy5fcmVnaXN0ZXJDaGFuZ2VUaW1lciA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgY29udGVudCA9IGNvbnRlbnQgfHwgc2NvcGUuJGVkaXRBcmVhLmh0bWwoKTtcbiAgICAgICAgICAgIHNjb3BlLl9zeW5jVGV4dEFyZWEoY29udGVudCk7XG4gICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29udGVudF0pO1xuICAgICAgICB9LCA3OCk7XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlQXJlYSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGNvbnRlbnQgPSB0aGlzLnNldHRpbmdzLmNvbnRlbnQgfHwgJyc7XG4gICAgICAgIGlmKCFjb250ZW50ICYmIHRoaXMuc2V0dGluZ3MuaXNUZXh0QXJlYSkge1xuICAgICAgICAgICAgY29udGVudCA9IHRoaXMuc2V0dGluZ3Muc2VsZWN0b3JOb2RlLnZhbHVlO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuYXJlYSA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgcHJvcHM6IHsgY2xhc3NOYW1lOiAnbXctZWRpdG9yLWFyZWEnLCBpbm5lckhUTUw6IGNvbnRlbnQgfVxuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5hcmVhLm5vZGUuY29udGVudEVkaXRhYmxlID0gdHJ1ZTtcbiAgICAgICAgdGhpcy5hcmVhLm5vZGUub25pbnB1dCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgc2NvcGUucmVnaXN0ZXJDaGFuZ2UoKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy53cmFwcGVyLmFwcGVuZENoaWxkKHRoaXMuYXJlYS5ub2RlKTtcbiAgICAgICAgc2NvcGUuJGVkaXRBcmVhID0gdGhpcy5hcmVhLiRub2RlO1xuICAgICAgICBzY29wZS5wcmV2ZW50RXZlbnRzKCk7XG4gICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJ3JlYWR5Jyk7XG4gICAgfTtcblxuICAgIHRoaXMuZG9jdW1lbnRNb2RlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBpZighdGhpcy5zZXR0aW5ncy5yZWdpb25zKSB7XG4gICAgICAgICAgICBjb25zb2xlLndhcm4oJ1JlZ2lvbnMgYXJlIG5vdCBkZWZpbmVkIGluIERvY3VtZW50IG1vZGUuJyk7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy4kZWRpdEFyZWEgPSAkKHRoaXMuZG9jdW1lbnQuYm9keSk7XG4gICAgICAgIHRoaXMud3JhcHBlci5jbGFzc05hbWUgKz0gJyBtdy1lZGl0b3Itd3JhcHBlci1kb2N1bWVudC1tb2RlJztcbiAgICAgICAgbXcuJCh0aGlzLmRvY3VtZW50LmJvZHkpLmFwcGVuZCh0aGlzLndyYXBwZXIpWzBdLm13RWRpdG9yID0gdGhpcztcbiAgICAgICAgJChzY29wZSkudHJpZ2dlcigncmVhZHknKTtcbiAgICB9O1xuXG4gICAgdGhpcy5zZXRDb250ZW50ID0gZnVuY3Rpb24gKGNvbnRlbnQsIHRyaWdnZXIpIHtcbiAgICAgICAgaWYodHlwZW9mIHRyaWdnZXIgPT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgIHRyaWdnZXIgPSB0cnVlO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuJGVkaXRBcmVhLmh0bWwoY29udGVudCk7XG4gICAgICAgIGlmKHRyaWdnZXIpe1xuICAgICAgICAgICAgc2NvcGUucmVnaXN0ZXJDaGFuZ2UoY29udGVudCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5uYXRpdmVFbGVtZW50ID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgcmV0dXJuIG5vZGUubm9kZSA/IG5vZGUubm9kZSA6IG5vZGU7XG4gICAgfTtcblxuICAgIHRoaXMuY29udHJvbHMgPSBbXTtcbiAgICB0aGlzLmFwaSA9IE1XRWRpdG9yLmFwaSh0aGlzKTtcblxuICAgIHRoaXMuX2FkZENvbnRyb2xsZXJHcm91cHMgPSBbXTtcbiAgICB0aGlzLmFkZENvbnRyb2xsZXJHcm91cCA9IGZ1bmN0aW9uIChvYmosIHJvdywgYmFyKSB7XG4gICAgICAgIGlmKCFiYXIpIHtcbiAgICAgICAgICAgIGJhciA9ICdiYXInO1xuICAgICAgICB9XG4gICAgICAgIHZhciBncm91cCA9IG9iai5ncm91cDtcbiAgICAgICAgdmFyIGlkID0gbXcuaWQoJ213LmVkaXRvci1ncm91cC0nKTtcbiAgICAgICAgdmFyIGVsID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWJhci1jb250cm9sLWl0ZW0gbXctYmFyLWNvbnRyb2wtaXRlbS1ncm91cCcsXG4gICAgICAgICAgICAgICAgaWQ6aWRcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgdmFyIGdyb3VwZWwgPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICBwcm9wczp7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWJhci1jb250cm9sLWl0ZW0tZ3JvdXAtY29udGVudHMnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgdmFyIGljb24gPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICB0YWc6J3NwYW4nLFxuICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICcgbXctZWRpdG9yLWdyb3VwLWJ1dHRvbicsXG4gICAgICAgICAgICAgICAgaW5uZXJIVE1MOiAnPHNwYW4gY2xhc3M9XCJtdy1lZGl0b3ItZ3JvdXAtYnV0dG9uLWNhcmV0XCI+PC9zcGFuPidcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIGlmKGdyb3VwLmljb24pIHtcbiAgICAgICAgICAgIGljb24ucHJlcGVuZCgnPHNwYW4gY2xhc3M9XCInICsgZ3JvdXAuaWNvbiArICcgbXctZWRpdG9yLWdyb3VwLWJ1dHRvbi1pY29uXCI+PC9zcGFuPicpO1xuICAgICAgICAgICAgaWNvbi5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgTVdFZGl0b3IuY29yZS5fcHJlU2VsZWN0KHRoaXMucGFyZW50Tm9kZSk7XG4gICAgICAgICAgICAgICAgdGhpcy5wYXJlbnROb2RlLmNsYXNzTGlzdC50b2dnbGUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfSBlbHNlIGlmKGdyb3VwLmNvbnRyb2xsZXIpIHtcbiAgICAgICAgICAgIGlmKHNjb3BlLmNvbnRyb2xsZXJzW2dyb3VwLmNvbnRyb2xsZXJdKXtcbiAgICAgICAgICAgICAgICB2YXIgY3RybCA9IG5ldyBzY29wZS5jb250cm9sbGVyc1tncm91cC5jb250cm9sbGVyXShzY29wZSwgc2NvcGUuYXBpLCBzY29wZSk7XG4gICAgICAgICAgICAgICAgc2NvcGUuY29udHJvbHMucHVzaChjdHJsKTtcbiAgICAgICAgICAgICAgICBpY29uLnByZXBlbmQoY3RybC5lbGVtZW50KTtcbiAgICAgICAgICAgICAgICBtdy5lbGVtZW50KGljb24uZ2V0KDApLnF1ZXJ5U2VsZWN0b3IoJy5tdy1lZGl0b3ItZ3JvdXAtYnV0dG9uLWNhcmV0JykpLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgTVdFZGl0b3IuY29yZS5fcHJlU2VsZWN0KHRoaXMucGFyZW50Tm9kZS5wYXJlbnROb2RlKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnROb2RlLnBhcmVudE5vZGUuY2xhc3NMaXN0LnRvZ2dsZSgnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9IGVsc2UgaWYoc2NvcGUuY29udHJvbGxlcnNIZWxwZXJzW2dyb3VwLmNvbnRyb2xsZXJdKXtcbiAgICAgICAgICAgICAgICBncm91cGVsLmFwcGVuZCh0aGlzLmNvbnRyb2xsZXJzSGVscGVyc1tncm91cC5jb250cm9sbGVyXSgpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbC5hcHBlbmQoaWNvbik7XG5cbiAgICAgICAgZ3JvdXBlbC5vbignY2xpY2snLCBmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgIE1XRWRpdG9yLmNvcmUuX3ByZVNlbGVjdCgpO1xuICAgICAgICB9KTtcblxuICAgICAgICB2YXIgbWVkaWE7XG4gICAgICAgIG9iai5ncm91cC53aGVuID0gb2JqLmdyb3VwLndoZW4gfHwgOTk5OTtcbiAgICAgICAgLy8gYXQgd2hhdCBwb2ludCBncm91cCBidXR0b25zIGJlY29tZSBsaWtlIGRyb3Bkb3duIC0gYnkgZGVmYXVsdCBpdCdzIGFsd2F5cyBhIGRyb3Bkb3duXG4gICAgICAgIGlmIChvYmouZ3JvdXAud2hlbikge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBvYmouZ3JvdXAud2hlbiA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICBtZWRpYSA9ICcobWF4LXdpZHRoOiAnICsgb2JqLmdyb3VwLndoZW4gKyAncHgpJztcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbWVkaWEgPSBvYmouZ3JvdXAud2hlbjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG5cblxuICAgICAgICBlbC5hcHBlbmQoZ3JvdXBlbCk7XG4gICAgICAgIHJvdyA9IHR5cGVvZiByb3cgIT09ICd1bmRlZmluZWQnID8gcm93IDogIHRoaXMuc2V0dGluZ3MuY29udHJvbHMubGVuZ3RoIC0gMTtcbiAgICAgICAgZ3JvdXAuY29udHJvbHMuZm9yRWFjaChmdW5jdGlvbiAobmFtZSkge1xuICAgICAgICAgICAgaWYoc2NvcGUuY29udHJvbGxlcnNbbmFtZV0pe1xuICAgICAgICAgICAgICAgIHZhciBjdHJsID0gbmV3IHNjb3BlLmNvbnRyb2xsZXJzW25hbWVdKHNjb3BlLCBzY29wZS5hcGksIHNjb3BlKTtcbiAgICAgICAgICAgICAgICBzY29wZS5jb250cm9scy5wdXNoKGN0cmwpO1xuICAgICAgICAgICAgICAgIGdyb3VwZWwuYXBwZW5kKGN0cmwuZWxlbWVudCk7XG4gICAgICAgICAgICB9IGVsc2UgaWYoc2NvcGUuY29udHJvbGxlcnNIZWxwZXJzW25hbWVdKXtcbiAgICAgICAgICAgICAgICBncm91cGVsLmFwcGVuZCh0aGlzLmNvbnRyb2xsZXJzSGVscGVyc1tuYW1lXSgpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgc2NvcGVbYmFyXS5hZGQoZWwsIHJvdyk7XG5cbiAgICAgICAgdGhpcy5fYWRkQ29udHJvbGxlckdyb3Vwcy5wdXNoKHtcbiAgICAgICAgICAgIGVsOiBlbCxcbiAgICAgICAgICAgIHJvdzogcm93LFxuICAgICAgICAgICAgb2JqOiBvYmosXG4gICAgICAgICAgICBtZWRpYTogbWVkaWFcbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiBlbDtcbiAgICB9O1xuXG4gICAgdGhpcy5jb250cm9sR3JvdXBNYW5hZ2VyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgY2hlY2sgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHZhciBpID0gMCwgbCA9IHNjb3BlLl9hZGRDb250cm9sbGVyR3JvdXBzLmxlbmd0aDtcbiAgICAgICAgICAgIGZvciAoIDsgaTwgbCA7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBpdGVtID0gc2NvcGUuX2FkZENvbnRyb2xsZXJHcm91cHNbaV07XG4gICAgICAgICAgICAgICAgdmFyIG1lZGlhID0gaXRlbS5tZWRpYTtcbiAgICAgICAgICAgICAgICBpZihtZWRpYSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbWF0Y2ggPSBzY29wZS5kb2N1bWVudC5kZWZhdWx0Vmlldy5tYXRjaE1lZGlhKG1lZGlhKTtcbiAgICAgICAgICAgICAgICAgICAgaXRlbS5lbC4kbm9kZVttYXRjaC5tYXRjaGVzID8gJ2FkZENsYXNzJyA6ICdyZW1vdmVDbGFzcyddKCdtdy1lZGl0b3ItY29udHJvbC1ncm91cC1tZWRpYS1tYXRjaGVzJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICAkKHdpbmRvdykub24oJ2xvYWQgcmVzaXplIG9yaWVudGF0aW9uY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgY2hlY2soKTtcbiAgICAgICAgfSk7XG4gICAgICAgIGNoZWNrKCk7XG4gICAgfTtcblxuICAgIHRoaXMuYWRkQ29udHJvbGxlciA9IGZ1bmN0aW9uIChuYW1lLCByb3csIGJhcikge1xuICAgICAgICByb3cgPSB0eXBlb2Ygcm93ICE9PSAndW5kZWZpbmVkJyA/IHJvdyA6ICB0aGlzLnNldHRpbmdzLmNvbnRyb2xzLmxlbmd0aCAtIDE7XG4gICAgICAgIGlmICghYmFyKSB7XG4gICAgICAgICAgICBiYXIgPSAnYmFyJztcbiAgICAgICAgfVxuICAgICAgICBpZih0aGlzLmNvbnRyb2xsZXJzW25hbWVdKXtcbiAgICAgICAgICAgIHZhciBjdHJsID0gbmV3IHRoaXMuY29udHJvbGxlcnNbbmFtZV0oc2NvcGUsIHNjb3BlLmFwaSwgc2NvcGUpO1xuICAgICAgICAgICAgaWYgKCFjdHJsLmVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICBjdHJsLmVsZW1lbnQgPSBjdHJsLnJlbmRlcigpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmNvbnRyb2xzLnB1c2goY3RybCk7XG4gICAgICAgICAgICB0aGlzW2Jhcl0uYWRkKGN0cmwuZWxlbWVudCwgcm93KTtcbiAgICAgICAgfSBlbHNlIGlmKHRoaXMuY29udHJvbGxlcnNIZWxwZXJzW25hbWVdKXtcbiAgICAgICAgICAgIHRoaXNbYmFyXS5hZGQodGhpcy5jb250cm9sbGVyc0hlbHBlcnNbbmFtZV0oKSwgcm93KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZVNtYWxsRWRpdG9yID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAoIXRoaXMuc2V0dGluZ3Muc21hbGxFZGl0b3IpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLnNtYWxsRWRpdG9yID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LXNtYWxsLWVkaXRvciBtdy1zbWFsbC1lZGl0b3Itc2tpbi0nICsgdGhpcy5zZXR0aW5ncy5za2luXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuc21hbGxFZGl0b3JCYXIgPSBtdy5iYXIoKTtcblxuICAgICAgICB0aGlzLnNtYWxsRWRpdG9yLmhpZGUoKTtcbiAgICAgICAgdGhpcy5zbWFsbEVkaXRvci5hcHBlbmQodGhpcy5zbWFsbEVkaXRvckJhci5iYXIpO1xuICAgICAgICBmb3IgKHZhciBpMSA9IDA7IGkxIDwgdGhpcy5zZXR0aW5ncy5zbWFsbEVkaXRvci5sZW5ndGg7IGkxKyspIHtcbiAgICAgICAgICAgIHZhciBpdGVtID0gdGhpcy5zZXR0aW5ncy5zbWFsbEVkaXRvcltpMV07XG4gICAgICAgICAgICB0aGlzLnNtYWxsRWRpdG9yQmFyLmNyZWF0ZVJvdygpO1xuICAgICAgICAgICAgZm9yICh2YXIgaTIgPSAwOyBpMiA8IGl0ZW0ubGVuZ3RoOyBpMisrKSB7XG4gICAgICAgICAgICAgICAgaWYoIHR5cGVvZiBpdGVtW2kyXSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuYWRkQ29udHJvbGxlcihpdGVtW2kyXSwgaTEsICdzbWFsbEVkaXRvckJhcicpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiggdHlwZW9mIGl0ZW1baTJdID09PSAnb2JqZWN0Jykge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5hZGRDb250cm9sbGVyR3JvdXAoaXRlbVtpMl0sIGkxLCAnc21hbGxFZGl0b3JCYXInKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgc2NvcGUuJGVkaXRBcmVhLm9uKCdtb3VzZXVwIHRvdWNoZW5kJywgZnVuY3Rpb24gKGUsIGRhdGEpIHtcbiAgICAgICAgICAgIGlmIChzY29wZS5zZWxlY3Rpb24gJiYgIXNjb3BlLnNlbGVjdGlvbi5pc0NvbGxhcHNlZCkge1xuICAgICAgICAgICAgICAgIGlmKCFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGUudGFyZ2V0LCAnbXctYmFyJykpe1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5zbWFsbEVkaXRvci5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgdG9wOiBzY29wZS5pbnRlcmFjdGlvbkRhdGEucGFnZVkgLSBzY29wZS5zbWFsbEVkaXRvci4kbm9kZS5oZWlnaHQoKSAtIDIwLFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogc2NvcGUuaW50ZXJhY3Rpb25EYXRhLnBhZ2VYLFxuICAgICAgICAgICAgICAgICAgICAgICAgZGlzcGxheTogJ2Jsb2NrJ1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHNjb3BlLnNtYWxsRWRpdG9yLmhpZGUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMuYWN0aW9uV2luZG93LmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQodGhpcy5zbWFsbEVkaXRvci5ub2RlKTtcbiAgICB9O1xuICAgIHRoaXMuY3JlYXRlQmFyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLmJhciA9IG13LnNldHRpbmdzLmJhciB8fCBtdy5iYXIoKTtcbiAgICAgICAgZm9yICh2YXIgaTEgPSAwOyBpMSA8IHRoaXMuc2V0dGluZ3MuY29udHJvbHMubGVuZ3RoOyBpMSsrKSB7XG4gICAgICAgICAgICB2YXIgaXRlbSA9IHRoaXMuc2V0dGluZ3MuY29udHJvbHNbaTFdO1xuICAgICAgICAgICAgdGhpcy5iYXIuY3JlYXRlUm93KCk7XG4gICAgICAgICAgICBmb3IgKHZhciBpMiA9IDA7IGkyIDwgaXRlbS5sZW5ndGg7IGkyKyspIHtcbiAgICAgICAgICAgICAgICBpZiggdHlwZW9mIGl0ZW1baTJdID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5hZGRDb250cm9sbGVyKGl0ZW1baTJdLCBpMSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKCB0eXBlb2YgaXRlbVtpMl0gPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmFkZENvbnRyb2xsZXJHcm91cChpdGVtW2kyXSwgaTEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICB0aGlzLndyYXBwZXIuYXBwZW5kQ2hpbGQodGhpcy5iYXIuYmFyKTtcbiAgICB9O1xuXG4gICAgdGhpcy5fb25SZWFkeSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgJCh0aGlzKS5vbigncmVhZHknLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBzY29wZS5pbml0SW50ZXJhY3Rpb24oKTtcbiAgICAgICAgICAgIHNjb3BlLmFwaS5leGVjQ29tbWFuZCgnZW5hYmxlT2JqZWN0UmVzaXppbmcnLCBmYWxzZSwgJ2ZhbHNlJyk7XG4gICAgICAgICAgICBzY29wZS5hcGkuZXhlY0NvbW1hbmQoJzJELVBvc2l0aW9uJywgZmFsc2UsIGZhbHNlKTtcbiAgICAgICAgICAgIHNjb3BlLmFwaS5leGVjQ29tbWFuZChcImVuYWJsZUlubGluZVRhYmxlRWRpdGluZ1wiLCBudWxsLCBmYWxzZSk7XG4gICAgICAgICAgICBpZighc2NvcGUuc3RhdGUuaGFzUmVjb3JkcygpKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICAkaW5pdGlhbDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0OiBzY29wZS4kZWRpdEFyZWFbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiBzY29wZS4kZWRpdEFyZWFbMF0uaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBzY29wZS5zZXR0aW5ncy5yZWdpb25zID0gc2NvcGUuc2V0dGluZ3MucmVnaW9ucyB8fCBzY29wZS4kZWRpdEFyZWE7XG4gICAgICAgICAgICAkKHNjb3BlLnNldHRpbmdzLnJlZ2lvbnMsIHNjb3BlLmFjdGlvbldpbmRvdy5kb2N1bWVudCkuYXR0cignY29udGVudGVkaXRhYmxlJywgdHJ1ZSk7XG4gICAgICAgICAgICBpZiAoc2NvcGUuc2V0dGluZ3MuZWRpdE1vZGUgPT09ICdsaXZlZWRpdCcpIHtcbiAgICAgICAgICAgICAgICBzY29wZS5saXZlRWRpdE1vZGUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciBjc3MgPSB7fTtcbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLm1pbkhlaWdodCkge1xuICAgICAgICAgICAgICAgIGNzcy5taW5IZWlnaHQgPSBzY29wZS5zZXR0aW5ncy5taW5IZWlnaHQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5tYXhIZWlnaHQpIHtcbiAgICAgICAgICAgICAgICBjc3MubWF4SGVpZ2h0ID0gc2NvcGUuc2V0dGluZ3MubWF4SGVpZ2h0O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoc2NvcGUuc2V0dGluZ3MuaGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgY3NzLmhlaWdodCA9IHNjb3BlLnNldHRpbmdzLmhlaWdodDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLm1pbldpZHRoKSB7XG4gICAgICAgICAgICAgICAgY3NzLm1pbldpZHRoID0gc2NvcGUuc2V0dGluZ3MubWluV2lkdGg7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy5tYXhXaWR0aCkge1xuICAgICAgICAgICAgICAgIGNzcy5tYXhXaWR0aCA9IHNjb3BlLnNldHRpbmdzLm1heFdpZHRoO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoc2NvcGUuc2V0dGluZ3Mud2lkdGgpIHtcbiAgICAgICAgICAgICAgICBjc3Mud2lkdGggPSBzY29wZS5zZXR0aW5ncy53aWR0aDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHNjb3BlLiRlZGl0QXJlYS5jc3MoY3NzKTtcbiAgICAgICAgICAgIHNjb3BlLmFkZERlcGVuZGVuY2llcygpO1xuICAgICAgICAgICAgc2NvcGUuY3JlYXRlU21hbGxFZGl0b3IoKTtcblxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5saXZlRWRpdE1vZGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMubGl2ZWVkaXQgPSBNV0VkaXRvci5saXZlZWRpdE1vZGUodGhpcy5hY3Rpb25XaW5kb3cuZG9jdW1lbnQuYm9keSwgc2NvcGUpO1xuICAgIH07XG5cbiAgICB0aGlzLl9pbml0SW5wdXRSZWNvcmRUaW1lID0gbnVsbDtcbiAgICB0aGlzLl9pbml0SW5wdXRSZWNvcmQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICQodGhpcykub24oJ2NoYW5nZScsIGZ1bmN0aW9uIChlLCBodG1sKSB7XG4gICAgICAgICAgICBjbGVhclRpbWVvdXQoc2NvcGUuX2luaXRJbnB1dFJlY29yZFRpbWUpO1xuICAgICAgICAgICAgc2NvcGUuX2luaXRJbnB1dFJlY29yZFRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzY29wZS5zdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHNjb3BlLiRlZGl0QXJlYVswXSxcbiAgICAgICAgICAgICAgICAgICAgdmFsdWU6IGh0bWxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0sIDYwMCk7XG5cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuX19pbnNlcnRFZGl0b3IgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICh0aGlzLnNldHRpbmdzLmlzVGV4dEFyZWEpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcy5zZXR0aW5ncy5zZWxlY3Rvcik7XG4gICAgICAgICAgICBlbFswXS5td0VkaXRvciA9IHRoaXM7XG4gICAgICAgICAgICBlbC5oaWRlKCk7XG4gICAgICAgICAgICB2YXIgYXJlYVdyYXBwZXIgPSBtdy5lbGVtZW50KCk7XG4gICAgICAgICAgICBhcmVhV3JhcHBlci5ub2RlLm13RWRpdG9yID0gdGhpcztcbiAgICAgICAgICAgIGVsLmFmdGVyKGFyZWFXcmFwcGVyLm5vZGUpO1xuICAgICAgICAgICAgYXJlYVdyYXBwZXIuYXBwZW5kKHRoaXMud3JhcHBlcik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBtdy4kKHRoaXMuc2V0dGluZ3Muc2VsZWN0b3IpLmFwcGVuZCh0aGlzLndyYXBwZXIpWzBdLm13RWRpdG9yID0gdGhpcztcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMuY29udHJvbGxlcnMgPSBNV0VkaXRvci5jb250cm9sbGVycztcbiAgICAgICAgdGhpcy5jb250cm9sbGVyc0hlbHBlcnMgPSBNV0VkaXRvci5jb250cm9sbGVyc0hlbHBlcnM7XG4gICAgICAgIHRoaXMuaW5pdFN0YXRlKCk7XG4gICAgICAgIHRoaXMuX29uUmVhZHkoKTtcbiAgICAgICAgdGhpcy5jcmVhdGVXcmFwcGVyKCk7XG4gICAgICAgIHRoaXMuY3JlYXRlQmFyKCk7XG5cbiAgICAgICAgaWYgKHRoaXMuc2V0dGluZ3MubW9kZSA9PT0gJ2RpdicpIHtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlQXJlYSgpO1xuICAgICAgICB9IGVsc2UgaWYgKHRoaXMuc2V0dGluZ3MubW9kZSA9PT0gJ2lmcmFtZScpIHtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlRnJhbWUoKTtcbiAgICAgICAgfSBlbHNlIGlmICh0aGlzLnNldHRpbmdzLm1vZGUgPT09ICdkb2N1bWVudCcpIHtcbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnRNb2RlKCk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRoaXMuc2V0dGluZ3MubW9kZSAhPT0gJ2RvY3VtZW50Jykge1xuICAgICAgICAgICAgdGhpcy5faW5pdElucHV0UmVjb3JkKCk7XG4gICAgICAgICAgICB0aGlzLl9faW5zZXJ0RWRpdG9yKCk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jb250cm9sR3JvdXBNYW5hZ2VyKCk7XG5cbiAgICB9O1xuICAgIHRoaXMuaW5pdCgpO1xufTtcblxuaWYgKHdpbmRvdy5tdykge1xuICAgbXcuRWRpdG9yID0gZnVuY3Rpb24gKG9wdGlvbnMpe1xuICAgICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuICAgICAgIGlmKCFvcHRpb25zLnNlbGVjdG9yICYmIG9wdGlvbnMuZWxlbWVudCl7XG4gICAgICAgICAgIG9wdGlvbnMuc2VsZWN0b3IgPSBvcHRpb25zLmVsZW1lbnQ7XG4gICAgICAgfVxuICAgICAgIGlmKG9wdGlvbnMuc2VsZWN0b3Ipe1xuICAgICAgICAgICBpZiAodHlwZW9mIG9wdGlvbnMuc2VsZWN0b3IgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICAgICBvcHRpb25zLnNlbGVjdG9yID0gKG9wdGlvbnMuZG9jdW1lbnQgfHwgZG9jdW1lbnQpLnF1ZXJ5U2VsZWN0b3Iob3B0aW9ucy5zZWxlY3Rvcik7XG4gICAgICAgICAgIH1cbiAgICAgICAgICAgaWYgKG9wdGlvbnMuc2VsZWN0b3IgJiYgb3B0aW9ucy5zZWxlY3Rvci5fX01XRWRpdG9yKSB7XG4gICAgICAgICAgICAgICBjb25zb2xlLmxvZyhvcHRpb25zLnNlbGVjdG9yLl9fTVdFZGl0b3IpXG4gICAgICAgICAgICAgICByZXR1cm4gb3B0aW9ucy5zZWxlY3Rvci5fX01XRWRpdG9yO1xuICAgICAgICAgICB9XG4gICAgICAgfVxuICAgICAgIHJldHVybiBuZXcgTVdFZGl0b3Iob3B0aW9ucyk7XG4gICB9O1xufVxuXG5cblxubXcucmVxdWlyZSgnYXV0b2NvbXBsZXRlLmpzJyk7XG5tdy5yZXF1aXJlKCdmaWxlcGlja2VyLmpzJyk7XG5cbm13LnJlcXVpcmUoJ2Zvcm0tY29udHJvbHMuanMnKTtcbm13LnJlcXVpcmUoJ2xpbmstZWRpdG9yLmpzJyk7XG5cbi8vXG5cbm13LnJlcXVpcmUoJ3N0YXRlLmpzJyk7XG5cbi8qbXcucmVxdWlyZSgnZWRpdG9yL2Jhci5qcycpO1xubXcucmVxdWlyZSgnZWRpdG9yL2FwaS5qcycpO1xubXcucmVxdWlyZSgnZWRpdG9yL2hlbHBlcnMuanMnKTtcbm13LnJlcXVpcmUoJ2VkaXRvci90b29scy5qcycpO1xubXcucmVxdWlyZSgnZWRpdG9yL2NvcmUuanMnKTtcbm13LnJlcXVpcmUoJ2VkaXRvci9jb250cm9sbGVycy5qcycpO1xubXcucmVxdWlyZSgnZWRpdG9yL2FkZC5jb250cm9sbGVyLmpzJyk7XG5tdy5yZXF1aXJlKCdlZGl0b3IvaW50ZXJhY3Rpb24tY29udHJvbHMuanMnKTtcbm13LnJlcXVpcmUoJ2VkaXRvci9pMThuLmpzJyk7XG5tdy5yZXF1aXJlKCdlZGl0b3IvbGl2ZWVkaXRtb2RlLmpzJyk7Ki9cbm13LnJlcXVpcmUoJ2NvbnRyb2xfYm94LmpzJyk7XG4iLCJNV0VkaXRvci5jb250cm9sbGVyc0hlbHBlcnMgPSB7XHJcbiAgICAnfCcgOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgcmV0dXJuIG13LmVsZW1lbnQoe1xyXG4gICAgICAgICAgICB0YWdlOiAnc3BhbicsXHJcbiAgICAgICAgICAgIHByb3BzOiB7XHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy1iYXItZGVsaW1pdGVyJ1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcbn07XHJcbiIsIk1XRWRpdG9yLmkxOG4gPSB7XHJcbiAgICBlbjoge1xyXG4gICAgICAgICdDaGFuZ2UnOiAnQ2hhbmdlJyxcclxuICAgICAgICAnRWRpdCBpbWFnZSc6ICdFZGl0JyxcclxuICAgIH1cclxufTtcclxuIiwiLypcbipcbiogIGludGVyZmFjZSBkYXRhIHtcbiAgICAgICAgdGFyZ2V0OiBFbGVtZW50LFxuICAgICAgICBjb21wb25lbnQ6IEVsZW1lbnQsXG4gICAgICAgIGlzSW1hZ2U6IGJvb2xlYW4sXG4gICAgICAgIGV2ZW50OiBFdmVudFxuICAgIH07XG4qXG4qXG4qICovXG5cbk1XRWRpdG9yLmludGVyYWN0aW9uQ29udHJvbHMgPSB7XG4gICAgbGlua1Rvb2x0aXA6IGZ1bmN0aW9uIChyb290U2NvcGUpIHtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctZWRpdG9yLWxpbmstdG9vbHRpcCdcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHZhciB1cmxFbGVtZW50ID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICAgICAgdGFnOiAnYScsXG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctZWRpdG9yLWxpbmstdG9vbHRpcC11cmwnLFxuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6ICdibGFuaydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHZhciB1cmxVbmxpbmsgPSBNV0VkaXRvci5jb3JlLmJ1dHRvbih7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWRpLWxpbmstb2ZmJyxcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdXJsVW5saW5rLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByb290U2NvcGUuYXBpLnVubGluaygpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGVsLnVybEVsZW1lbnQgPSB1cmxFbGVtZW50O1xuICAgICAgICAgICAgZWwudXJsVW5saW5rID0gdXJsVW5saW5rO1xuICAgICAgICAgICAgZWwuYXBwZW5kKHVybEVsZW1lbnQpO1xuICAgICAgICAgICAgZWwuYXBwZW5kKHVybFVubGluayk7XG4gICAgICAgICAgICBlbC50YXJnZXQgPSBudWxsO1xuICAgICAgICAgICAgZWwuaGlkZSgpO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmludGVyYWN0ID0gZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIHZhciB0ZyA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aFRhZyhkYXRhLnRhcmdldCwnYScpO1xuICAgICAgICAgICAgaWYoIXRnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5lbGVtZW50LmhpZGUoKTtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgJHRhcmdldCA9IG13LmVsZW1lbnQoZGF0YS50YXJnZXQpO1xuICAgICAgICAgICAgdGhpcy4kdGFyZ2V0ID0gJHRhcmdldDtcbiAgICAgICAgICAgIHZhciBjc3MgPSAkdGFyZ2V0Lm9mZnNldCgpO1xuICAgICAgICAgICAgY3NzLnRvcCArPSAkdGFyZ2V0Lm91dGVySGVpZ2h0KCk7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQudXJsRWxlbWVudC5odG1sKGRhdGEudGFyZ2V0LmhyZWYpO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LnVybEVsZW1lbnQucHJvcCgnaHJlZicsIGRhdGEudGFyZ2V0LmhyZWYpO1xuICAgICAgICAgICAgdGhpcy5lbGVtZW50LiRub2RlLmNzcyhjc3MpLnNob3coKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuICAgIGltYWdlOiBmdW5jdGlvbiAocm9vdFNjb3BlKSB7XG4gICAgICAgIHRoaXMubm9kZXMgPSBbXTtcbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuZWxlbWVudCh7XG4gICAgICAgICAgICAgICAgcHJvcHM6IHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctZWRpdG9yLWltYWdlLWhhbmRsZS13cmFwJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIGNoYW5nZUJ1dHRvbiA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGlubmVySFRNTDogJzxpIGNsYXNzPVwibWRpIG1kaS1mb2xkZXItbXVsdGlwbGUtaW1hZ2VcIj48L2k+JyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctdWktYnRuIG13LXVpLWJ0bi1tZWRpdW0gdGlwJyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YXNldDoge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGlwOiByb290U2NvcGUubGFuZygnQ2hhbmdlIGltYWdlJylcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgY2hhbmdlQnV0dG9uLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgZGlhbG9nO1xuICAgICAgICAgICAgICAgIHZhciBwaWNrZXIgPSBuZXcgbXcuZmlsZVBpY2tlcih7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdpbWFnZXMnLFxuICAgICAgICAgICAgICAgICAgICBsYWJlbDogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIGF1dG9TZWxlY3Q6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBmb290ZXI6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgIF9mcmFtZU1heEhlaWdodDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgb25SZXN1bHQ6IGZ1bmN0aW9uIChyZXMpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB1cmwgPSByZXMuc3JjID8gcmVzLnNyYyA6IHJlcztcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCF1cmwpIHJldHVybjtcbiAgICAgICAgICAgICAgICAgICAgICAgIHVybCA9IHVybC50b1N0cmluZygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuJHRhcmdldC5hdHRyKCdzcmMnLCB1cmwpO1xuICAgICAgICAgICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgZGlhbG9nID0gbXcudG9wKCkuZGlhbG9nKHtcbiAgICAgICAgICAgICAgICAgICAgY29udGVudDogcGlja2VyLnJvb3QsXG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiBtdy5sYW5nKCdTZWxlY3QgaW1hZ2UnKSxcbiAgICAgICAgICAgICAgICAgICAgZm9vdGVyOiBmYWxzZVxuICAgICAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIGVkaXRCdXR0b24gPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBpbm5lckhUTUw6ICc8aSBjbGFzcz1cIm1kaSBtZGktaW1hZ2UtZWRpdFwiPjwvaT4nLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy11aS1idG4gbXctdWktYnRuLW1lZGl1bSB0aXAnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhc2V0OiB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aXA6IHJvb3RTY29wZS5sYW5nKCdFZGl0IGltYWdlJylcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIG5hdiA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LXVpLWJ0bi1uYXYnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBuYXYuYXBwZW5kKGNoYW5nZUJ1dHRvbik7XG4gICAgICAgICAgICBlbC5hcHBlbmQobmF2KTtcbiAgICAgICAgICAgIC8vIG5hdi5hcHBlbmQoZWRpdEJ1dHRvbik7XG4gICAgICAgICAgICB0aGlzLm5vZGVzLnB1c2goZWwubm9kZSwgY2hhbmdlQnV0dG9uLm5vZGUsIGVkaXRCdXR0b24ubm9kZSk7XG4gICAgICAgICAgICBlbC5oaWRlKCk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuaW50ZXJhY3QgPSBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgaWYobXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQ2xhc3MoZGF0YS5sb2NhbFRhcmdldCwgJ213LWVkaXRvci1pbWFnZS1oYW5kbGUtd3JhcCcpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYodGhpcy5ub2Rlcy5pbmRleE9mKGRhdGEudGFyZ2V0KSAhPT0gLTEpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsZW1lbnQuJG5vZGUuaGlkZSgpO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChkYXRhLmlzSW1hZ2UpIHtcbiAgICAgICAgICAgICAgICB2YXIgJHRhcmdldCA9ICQoZGF0YS5sb2NhbFRhcmdldCk7XG4gICAgICAgICAgICAgICAgdGhpcy4kdGFyZ2V0ID0gJHRhcmdldDtcbiAgICAgICAgICAgICAgICB2YXIgY3NzID0gJHRhcmdldC5vZmZzZXQoKTtcbiAgICAgICAgICAgICAgICBjc3Mud2lkdGggPSAkdGFyZ2V0Lm91dGVyV2lkdGgoKTtcbiAgICAgICAgICAgICAgICBjc3MuaGVpZ2h0ID0gJHRhcmdldC5vdXRlckhlaWdodCgpO1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC4kbm9kZS5jc3MoY3NzKS5zaG93KCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC4kbm9kZS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZWxlbWVudCA9IHRoaXMucmVuZGVyKCk7XG4gICAgfSxcbiAgICB0YWJsZU1hbmFnZXI6IGZ1bmN0aW9uKHJvb3RTY29wZSl7XG4gICAgICAgIHZhciBsc2NvcGUgPSB0aGlzO1xuICAgICAgICB0aGlzLmludGVyYWN0ID0gZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIGlmICghZGF0YS5ldmVudElzQWN0aW9uTGlrZSkgeyByZXR1cm47IH1cbiAgICAgICAgICAgIHZhciB0ZCA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aFRhZyhkYXRhLmxvY2FsVGFyZ2V0LCAndGQnKTtcbiAgICAgICAgICAgIGlmICh0ZCkge1xuICAgICAgICAgICAgICAgIHZhciAkdGFyZ2V0ID0gJCh0ZCk7XG4gICAgICAgICAgICAgICAgdGhpcy4kdGFyZ2V0ID0gJHRhcmdldDtcbiAgICAgICAgICAgICAgICB2YXIgY3NzID0gJHRhcmdldC5vZmZzZXQoKTtcbiAgICAgICAgICAgICAgICBjc3MudG9wIC09IGxzY29wZS5lbGVtZW50Lm5vZGUub2Zmc2V0SGVpZ2h0O1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC4kbm9kZS5jc3MoY3NzKS5zaG93KCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudC4kbm9kZS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5fYWZ0ZXJBY3Rpb24gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuJG5vZGUuaGlkZSgpO1xuICAgICAgICAgICAgcm9vdFNjb3BlLnN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgdGFyZ2V0OiByb290U2NvcGUuJGVkaXRBcmVhWzBdLFxuICAgICAgICAgICAgICAgIHZhbHVlOiByb290U2NvcGUuJGVkaXRBcmVhWzBdLmlubmVySFRNTFxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5yZW5kZXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgcm9vdCA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWVkaXRvci10YWJsZS1tYW5hZ2VyJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIGJhciA9IG13LmJhcigpO1xuICAgICAgICAgICAgYmFyLmNyZWF0ZVJvdygpO1xuICAgICAgICAgICAgcm9vdC5hcHBlbmQoYmFyLmJhcik7XG5cbiAgICAgICAgICAgIHZhciBpbnNlcnRERCA9IG5ldyBNV0VkaXRvci5jb3JlLmRyb3Bkb3duKHtcbiAgICAgICAgICAgICAgICBkYXRhOiBbXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdSb3cgQWJvdmUnLCB2YWx1ZToge2FjdGlvbjogJ2luc2VydFJvdycsIHR5cGU6ICdhYm92ZSd9IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdSb3cgVW5kZXInLCB2YWx1ZToge2FjdGlvbjogJ2luc2VydFJvdycsIHR5cGU6ICd1bmRlcid9IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdDb2x1bW4gb24gdGhlIGxlZnQnLCB2YWx1ZToge2FjdGlvbjogJ2luc2VydENvbHVtbicsIHR5cGU6ICdsZWZ0J30gfSxcbiAgICAgICAgICAgICAgICAgICAgeyBsYWJlbDogJ0NvbHVtbiBvbiB0aGUgcmlnaHQnLCB2YWx1ZToge2FjdGlvbjogJ2luc2VydENvbHVtbicsIHR5cGU6ICdyaWdodCd9IH0sXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogJ0luc2VydCdcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBpbnNlcnRERC5zZWxlY3Qub24oJ2NoYW5nZScsIGZ1bmN0aW9uIChlLCBkYXRhLCBub2RlKSB7XG4gICAgICAgICAgICAgICAgcm9vdFNjb3BlLnN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogcm9vdFNjb3BlLiRlZGl0QXJlYVswXSxcbiAgICAgICAgICAgICAgICAgICAgdmFsdWU6IHJvb3RTY29wZS4kZWRpdEFyZWFbMF0uaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgbHNjb3BlW2RhdGEudmFsdWUuYWN0aW9uXShkYXRhLnZhbHVlLnR5cGUpO1xuICAgICAgICAgICAgICAgIGxzY29wZS5fYWZ0ZXJBY3Rpb24oKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIGRlbGV0ZXRERCA9IG5ldyBNV0VkaXRvci5jb3JlLmRyb3Bkb3duKHtcbiAgICAgICAgICAgICAgICBkYXRhOiBbXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdSb3cnLCB2YWx1ZToge2FjdGlvbjogJ2RlbGV0ZVJvdyd9IH0sXG4gICAgICAgICAgICAgICAgICAgIHsgbGFiZWw6ICdDb2x1bW4nLCB2YWx1ZToge2FjdGlvbjogJ2RlbGV0ZUNvbHVtbid9IH0sXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogJ0RlbGV0ZSdcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBkZWxldGV0REQuc2VsZWN0Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSwgZGF0YSwgbm9kZSkge1xuICAgICAgICAgICAgICAgIHJvb3RTY29wZS5zdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHJvb3RTY29wZS4kZWRpdEFyZWFbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiByb290U2NvcGUuJGVkaXRBcmVhWzBdLmlubmVySFRNTFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIGxzY29wZVtkYXRhLnZhbHVlLmFjdGlvbl0oKTtcbiAgICAgICAgICAgICAgICBsc2NvcGUuX2FmdGVyQWN0aW9uKClcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBiYXIuYWRkKGluc2VydERELnJvb3Qubm9kZSk7XG4gICAgICAgICAgICBiYXIuYWRkKGRlbGV0ZXRERC5yb290Lm5vZGUpO1xuICAgICAgICAgICAgcm9vdC5oaWRlKCk7XG5cbiAgICAgICAgICAgIHJldHVybiByb290O1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZGVsZXRlUm93ID0gZnVuY3Rpb24gKGNlbGwpIHtcbiAgICAgICAgICAgIGNlbGwgPSBjZWxsIHx8IHRoaXMuZ2V0QWN0aXZlQ2VsbCgpO1xuICAgICAgICAgICAgY2VsbC5wYXJlbnROb2RlLnJlbW92ZSgpO1xuICAgICAgICB9O1xuXG5cbiAgICAgICAgdGhpcy5kZWxldGVDb2x1bW4gPSBmdW5jdGlvbiAoY2VsbCkge1xuICAgICAgICAgICAgY2VsbCA9IGNlbGwgfHwgdGhpcy5nZXRBY3RpdmVDZWxsKCk7XG4gICAgICAgICAgICB2YXIgaW5kZXggPSBtdy50b29scy5pbmRleChjZWxsKSxcbiAgICAgICAgICAgICAgICBib2R5ID0gY2VsbC5wYXJlbnROb2RlLnBhcmVudE5vZGUsXG4gICAgICAgICAgICAgICAgcm93cyA9IG13LiQoYm9keSkuY2hpbGRyZW4oJ3RyJyksXG4gICAgICAgICAgICAgICAgbCA9IHJvd3MubGVuZ3RoLFxuICAgICAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICB2YXIgcm93ID0gcm93c1tpXTtcbiAgICAgICAgICAgICAgICByb3cuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ3RkJylbaW5kZXhdLnJlbW92ZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZ2V0QWN0aXZlQ2VsbCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBub2RlID0gcm9vdFNjb3BlLmFwaS5lbGVtZW50Tm9kZSggcm9vdFNjb3BlLmdldFNlbGVjdGlvbigpLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICByZXR1cm4gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoVGFnKG5vZGUsJ3RkJyk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pbnNlcnRDb2x1bW4gPSBmdW5jdGlvbiAoZGlyLCBjZWxsKSB7XG4gICAgICAgICAgICBjZWxsID0gY2VsbCB8fCB0aGlzLmdldEFjdGl2ZUNlbGwoKTtcbiAgICAgICAgICAgIGNlbGwgPSBtdy4kKGNlbGwpWzBdO1xuICAgICAgICAgICAgaWYgKGNlbGwgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBkaXIgPSBkaXIgfHwgJ3JpZ2h0JztcbiAgICAgICAgICAgIHZhciByb3dzID0gbXcuJChjZWxsLnBhcmVudE5vZGUucGFyZW50Tm9kZSkuY2hpbGRyZW4oJ3RyJyk7XG4gICAgICAgICAgICB2YXIgaSA9IDAsIGwgPSByb3dzLmxlbmd0aCwgaW5kZXggPSBtdy50b29scy5pbmRleChjZWxsKTtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdmFyIHJvdyA9IHJvd3NbaV07XG4gICAgICAgICAgICAgICAgY2VsbCA9IG13LiQocm93KS5jaGlsZHJlbigndGQnKVtpbmRleF07XG4gICAgICAgICAgICAgICAgaWYgKGRpciA9PT0gJ2xlZnQnIHx8IGRpciA9PT0gJ2JvdGgnKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoY2VsbCkuYmVmb3JlKFwiPHRkPiZuYnNwOzwvdGQ+XCIpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoZGlyID09PSAncmlnaHQnIHx8IGRpciA9PT0gJ2JvdGgnKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoY2VsbCkuYWZ0ZXIoXCI8dGQ+Jm5ic3A7PC90ZD5cIik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmluc2VydFJvdyA9IGZ1bmN0aW9uIChkaXIsIGNlbGwpIHtcbiAgICAgICAgICAgIGNlbGwgPSBjZWxsIHx8IHRoaXMuZ2V0QWN0aXZlQ2VsbCgpO1xuICAgICAgICAgICAgY2VsbCA9IG13LiQoY2VsbClbMF07XG4gICAgICAgICAgICBpZiAoY2VsbCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGRpciA9IGRpciB8fCAndW5kZXInO1xuICAgICAgICAgICAgdmFyIHBhcmVudCA9IGNlbGwucGFyZW50Tm9kZSwgY2VsbHMgPSBtdy4kKHBhcmVudCkuY2hpbGRyZW4oJ3RkJyksIGkgPSAwLCBsID0gY2VsbHMubGVuZ3RoLFxuICAgICAgICAgICAgICAgIGh0bWwgPSAnJztcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaHRtbCArPSAnPHRkPiZuYnNwOzwvdGQ+JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGh0bWwgPSAnPHRyPicgKyBodG1sICsgJzwvdHI+JztcbiAgICAgICAgICAgIGlmIChkaXIgPT09ICd1bmRlcicgfHwgZGlyID09PSAnYm90aCcpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHBhcmVudCkuYWZ0ZXIoaHRtbClcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChkaXIgPT09ICdhYm92ZScgfHwgZGlyID09PSAnYm90aCcpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHBhcmVudCkuYmVmb3JlKGh0bWwpXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZGVsZXRlUm93ID0gZnVuY3Rpb24gKGNlbGwpIHtcbiAgICAgICAgICAgIGNlbGwgPSBjZWxsIHx8IHRoaXMuZ2V0QWN0aXZlQ2VsbCgpO1xuICAgICAgICAgICAgbXcuJChjZWxsLnBhcmVudE5vZGUpLnJlbW92ZSgpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmRlbGV0ZUNvbHVtbiA9IGZ1bmN0aW9uIChjZWxsKSB7XG4gICAgICAgICAgICBjZWxsID0gY2VsbCB8fCB0aGlzLmdldEFjdGl2ZUNlbGwoKTtcbiAgICAgICAgICAgIHZhciBpbmRleCA9IG13LnRvb2xzLmluZGV4KGNlbGwpLCBib2R5ID0gY2VsbC5wYXJlbnROb2RlLnBhcmVudE5vZGUsIHJvd3MgPSBtdy4kKGJvZHkpLmNoaWxkcmVuKCd0cicpLCBsID0gcm93cy5sZW5ndGgsIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICB2YXIgcm93ID0gcm93c1tpXTtcbiAgICAgICAgICAgICAgICBtdy4kKHJvdy5nZXRFbGVtZW50c0J5VGFnTmFtZSgndGQnKVtpbmRleF0pLnJlbW92ZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2V0U3R5bGUgPSBmdW5jdGlvbiAoY2xzLCBjZWxsKSB7XG4gICAgICAgICAgICBjZWxsID0gY2VsbCB8fCB0aGlzLmdldEFjdGl2ZUNlbGwoKTtcbiAgICAgICAgICAgIHZhciB0YWJsZSA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhjZWxsLCAndGFibGUnKTtcbiAgICAgICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKHRhYmxlLCAnbXctd3lzaXd5Zy10YWJsZScpO1xuICAgICAgICAgICAgbXcuJCh0YWJsZSkuYWRkQ2xhc3MoY2xzKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gdGhpcy5yZW5kZXIoKTtcbiAgICB9XG5cbn07XG4iLCJcbnZhciBjYW5EZXN0cm95ID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgdmFyIHRhcmdldCA9IGV2ZW50LnRhcmdldDtcbiAgICByZXR1cm4gIW13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlc09uTm9kZU9yUGFyZW50KGV2ZW50LCBbJ3NhZmUtZWxlbWVudCddKSAmJiBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0T3JOb25lKHRhcmdldCwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKTtcbn07XG5cblxuXG5cbk1XRWRpdG9yLmxlU2F2ZSA9IHtcbiAgIHByZXBhcmU6IGZ1bmN0aW9uKHJvb3Qpe1xuICAgICAgICBpZighcm9vdCkge1xuICAgICAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgICAgIH1cbiAgICAgICB2YXIgZG9jID0gbXcudG9vbHMucGFyc2VIdG1sKCk7XG4gICAgICAgdmFyIGRvYyA9IGRvY3VtZW50LmltcGxlbWVudGF0aW9uLmNyZWF0ZUhUTUxEb2N1bWVudChcIlwiKTtcbiAgICAgICBkb2MuYm9keS5pbm5lckhUTUwgPSByb290LmlubmVySFRNTDtcblxuICAgICAgIG13LiQoJy5lbGVtZW50LWN1cnJlbnQnLCBkb2MpLnJlbW92ZUNsYXNzKCdlbGVtZW50LWN1cnJlbnQnKTtcbiAgICAgICBtdy4kKCcuZWxlbWVudC1hY3RpdmUnLCBkb2MpLnJlbW92ZUNsYXNzKCdlbGVtZW50LWFjdGl2ZScpO1xuICAgICAgIG13LiQoJy5kaXNhYmxlLXJlc2l6ZScsIGRvYykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGUtcmVzaXplJyk7XG4gICAgICAgbXcuJCgnLm13LXdlYmtpdC1kcmFnLWhvdmVyLWJpbmRlZCcsIGRvYykucmVtb3ZlQ2xhc3MoJ213LXdlYmtpdC1kcmFnLWhvdmVyLWJpbmRlZCcpO1xuICAgICAgIG13LiQoJy5tb2R1bGUtY2F0LXRvZ2dsZS1Nb2R1bGVzJywgZG9jKS5yZW1vdmVDbGFzcygnbW9kdWxlLWNhdC10b2dnbGUtTW9kdWxlcycpO1xuICAgICAgIG13LiQoJy5tdy1tb2R1bGUtZHJhZy1jbG9uZScsIGRvYykucmVtb3ZlQ2xhc3MoJ213LW1vZHVsZS1kcmFnLWNsb25lJyk7XG4gICAgICAgbXcuJCgnLW1vZHVsZScsIGRvYykucmVtb3ZlQ2xhc3MoJy1tb2R1bGUnKTtcbiAgICAgICBtdy4kKCcuZW1wdHktZWxlbWVudCcsIGRvYykucmVtb3ZlKCk7XG4gICAgICAgbXcuJCgnLmVtcHR5LWVsZW1lbnQnLCBkb2MpLnJlbW92ZSgpO1xuICAgICAgIG13LiQoJy5lZGl0IC51aS1yZXNpemFibGUtaGFuZGxlJywgZG9jKS5yZW1vdmUoKTtcbiAgICAgICBtdy4kKCdzY3JpcHQnLCBkb2MpLnJlbW92ZSgpO1xuICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKCdhbGwnLCAndWktJywgZG9jLCAnc3RhcnRzJyk7XG4gICAgICAgbXcuJChcIltjb250ZW50ZWRpdGFibGVdXCIsIGRvYykucmVtb3ZlQXR0cihcImNvbnRlbnRlZGl0YWJsZVwiKTtcbiAgICAgICB2YXIgYWxsID0gZG9jLnF1ZXJ5U2VsZWN0b3JBbGwoJ1tjb250ZW50ZWRpdGFibGVdJyksXG4gICAgICAgICAgIGwgPSBhbGwubGVuZ3RoLFxuICAgICAgICAgICBpID0gMDtcbiAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICBhbGxbaV0ucmVtb3ZlQXR0cmlidXRlKCdjb250ZW50ZWRpdGFibGUnKTtcbiAgICAgICB9XG4gICAgICAgdmFyIGFsbDEgPSBkb2MucXVlcnlTZWxlY3RvckFsbCgnLm1vZHVsZScpLFxuICAgICAgICAgICBsMSA9IGFsbC5sZW5ndGgsXG4gICAgICAgICAgIGkxID0gMDtcbiAgICAgICBmb3IgKDsgaTEgPCBsMTsgaTErKykge1xuICAgICAgICAgICBpZiAoYWxsW2kxXS5xdWVyeVNlbGVjdG9yKCcuZWRpdCcpID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICBhbGxbaTFdLmlubmVySFRNTCA9ICcnO1xuICAgICAgICAgICB9XG4gICAgICAgfVxuICAgICAgIHJldHVybiBkb2M7XG4gICB9LFxuICAgaHRtbEF0dHJWYWxpZGF0ZTpmdW5jdGlvbihlZGl0cyl7XG4gICAgICAgIHZhciBmaW5hbCA9IFtdO1xuICAgICAgICAkLmVhY2goZWRpdHMsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICB2YXIgaHRtbCA9IHRoaXMub3V0ZXJIVE1MO1xuICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvdXJsXFwoJnF1b3Q7L2csIFwidXJsKCdcIik7XG4gICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9qcGcmcXVvdDsvZywgXCJqcGcnXCIpO1xuICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvanBlZyZxdW90Oy9nLCBcImpwZWcnXCIpO1xuICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvcG5nJnF1b3Q7L2csIFwicG5nJ1wiKTtcbiAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL2dpZiZxdW90Oy9nLCBcImdpZidcIik7XG4gICAgICAgICAgICBmaW5hbC5wdXNoKCQoaHRtbClbMF0pO1xuICAgICAgICB9KVxuICAgICAgICByZXR1cm4gZmluYWw7XG4gICB9LFxuICAgIHBhc3RlZEZyb21FeGNlbDogZnVuY3Rpb24gKGNsaXBib2FyZCkge1xuICAgICAgICB2YXIgaHRtbCA9IGNsaXBib2FyZC5nZXREYXRhKCd0ZXh0L2h0bWwnKTtcbiAgICAgICAgcmV0dXJuIGh0bWwuaW5kZXhPZignUHJvZ0lkIGNvbnRlbnQ9RXhjZWwuU2hlZXQnKSAhPT0gLTFcbiAgICB9LFxuICAgIGFyZVNhbWVMaWtlOiBmdW5jdGlvbiAoZWwxLCBlbDIpIHtcbiAgICAgICAgaWYgKCFlbDEgfHwgIWVsMikgcmV0dXJuIGZhbHNlO1xuICAgICAgICBpZiAoZWwxLm5vZGVUeXBlICE9PSBlbDIubm9kZVR5cGUpIHJldHVybiBmYWxzZTtcbiAgICAgICAgaWYgKCEhZWwxLmNsYXNzTmFtZS50cmltKCkgfHwgISFlbDIuY2xhc3NOYW1lLnRyaW0oKSkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGNzczEgPSAoZWwxLmdldEF0dHJpYnV0ZSgnc3R5bGUnKSB8fCAnJykucmVwbGFjZSgvXFxzL2csICcnKTtcbiAgICAgICAgdmFyIGNzczIgPSAoZWwyLmdldEF0dHJpYnV0ZSgnc3R5bGUnKSB8fCAnJykucmVwbGFjZSgvXFxzL2csICcnKTtcblxuICAgICAgICBpZiAoY3NzMSA9PT0gY3NzMiAmJiBlbDEubm9kZU5hbWUgPT09IGVsMi5ub2RlTmFtZSkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSxcbiAgICBjbGVhblVud2FudGVkVGFnczogZnVuY3Rpb24gKGJvZHkpIHtcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgbXcuJCgnKicsIGJvZHkpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKHRoaXMubm9kZU5hbWUgIT09ICdBJyAmJiBtdy5lYS5oZWxwZXJzLmlzSW5saW5lTGV2ZWwodGhpcykgJiYgKHRoaXMuY2xhc3NOYW1lLnRyaW0gJiYgIXRoaXMuY2xhc3NOYW1lLnRyaW0oKSkpIHtcbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUuYXJlU2FtZUxpa2UodGhpcywgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcpICYmIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nID09PSB0aGlzLm5leHRTaWJsaW5nKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLm5leHRTaWJsaW5nICE9PSB0aGlzLm5leHRFbGVtZW50U2libGluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5hcHBlbmRDaGlsZCh0aGlzLm5leHRTaWJsaW5nKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmlubmVySFRNTCA9IHRoaXMuaW5uZXJIVE1MICsgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSAnJztcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NOYW1lID0gJ213LXNraXAtYW5kLXJlbW92ZSc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgbXcuJCgnLm13LXNraXAtYW5kLXJlbW92ZScsIGJvZHkpLnJlbW92ZSgpO1xuICAgICAgICByZXR1cm4gYm9keTtcbiAgICB9LFxuICAgZ2V0RGF0YTogZnVuY3Rpb24oZWRpdHMpIHtcbiAgICAgICAgbXcuJChlZGl0cykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgbXcuJCgnbWV0YScsIHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICBlZGl0cyA9IHRoaXMuaHRtbEF0dHJWYWxpZGF0ZShlZGl0cyk7XG4gICAgICAgIHZhciBsID0gZWRpdHMubGVuZ3RoLFxuICAgICAgICAgICAgaSA9IDAsXG4gICAgICAgICAgICBoZWxwZXIgPSB7fSxcbiAgICAgICAgICAgIG1hc3RlciA9IHt9O1xuICAgICAgICBpZiAobCA+IDApIHtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaGVscGVyLml0ZW0gPSBlZGl0c1tpXTtcbiAgICAgICAgICAgICAgICB2YXIgcmVsID0gbXcudG9vbHMubXdhdHRyKGhlbHBlci5pdGVtLCAncmVsJyk7XG4gICAgICAgICAgICAgICAgaWYgKCFyZWwpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChoZWxwZXIuaXRlbSkucmVtb3ZlQ2xhc3MoJ2NoYW5nZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHMoaGVscGVyLml0ZW0sIGZ1bmN0aW9uKGxvb3ApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBjbHMgPSB0aGlzLmNsYXNzTmFtZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciByZWwgPSBtdy50b29scy5td2F0dHIodGhpcywgJ3JlbCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNscywgJ2VkaXQnKSAmJiBtdy50b29scy5oYXNDbGFzcyhjbHMsICdjaGFuZ2VkJykgJiYgKCEhcmVsKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGhlbHBlci5pdGVtID0gdGhpcztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5zdG9wTG9vcChsb29wKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciByZWwgPSBtdy50b29scy5td2F0dHIoaGVscGVyLml0ZW0sICdyZWwnKTtcbiAgICAgICAgICAgICAgICBpZiAoIXJlbCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgZmllbGQgPSAhIWhlbHBlci5pdGVtLmlkID8gJyMnK2hlbHBlci5pdGVtLmlkIDogJyc7XG4gICAgICAgICAgICAgICAgICAgIGNvbnNvbGUud2FybignU2tpcHBlZCBzYXZlOiAuZWRpdCcrZmllbGQrJyBlbGVtZW50IGRvZXMgbm90IGhhdmUgcmVsIGF0dHJpYnV0ZS4nKTtcbiAgICAgICAgICAgICAgICAgICAgY29udGludWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQoaGVscGVyLml0ZW0pLnJlbW92ZUNsYXNzKCdjaGFuZ2VkIG9yaWdfY2hhbmdlZCcpO1xuICAgICAgICAgICAgICAgIG13LiQoaGVscGVyLml0ZW0pLnJlbW92ZUNsYXNzKCdtb2R1bGUtb3ZlcicpO1xuXG4gICAgICAgICAgICAgICAgbXcuJCgnLm1vZHVsZS1vdmVyJywgaGVscGVyLml0ZW0pLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcygnbW9kdWxlLW92ZXInKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy4kKCdbY2xhc3NdJywgaGVscGVyLml0ZW0pLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGNscyA9IHRoaXMuZ2V0QXR0cmlidXRlKFwiY2xhc3NcIik7XG4gICAgICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBjbHMgPT09ICdzdHJpbmcnKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNscyA9IGNscy50cmltKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaWYoIWNscyl7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZUF0dHJpYnV0ZShcImNsYXNzXCIpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRlbnQgPSB0aGlzLmNsZWFuVW53YW50ZWRUYWdzKGhlbHBlci5pdGVtKS5pbm5lckhUTUw7XG4gICAgICAgICAgICAgICAgdmFyIGF0dHJfb2JqID0ge307XG4gICAgICAgICAgICAgICAgdmFyIGF0dHJzID0gaGVscGVyLml0ZW0uYXR0cmlidXRlcztcbiAgICAgICAgICAgICAgICBpZiAoYXR0cnMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgYWkgPSAwLFxuICAgICAgICAgICAgICAgICAgICAgICAgYWwgPSBhdHRycy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgICAgIGZvciAoOyBhaSA8IGFsOyBhaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBhdHRyX29ialthdHRyc1thaV0ubm9kZU5hbWVdID0gYXR0cnNbYWldLm5vZGVWYWx1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgb2JqID0ge1xuICAgICAgICAgICAgICAgICAgICBhdHRyaWJ1dGVzOiBhdHRyX29iaixcbiAgICAgICAgICAgICAgICAgICAgaHRtbDogY29udGVudFxuICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgdmFyIG9iamRhdGEgPSBcImZpZWxkX2RhdGFfXCIgKyBpO1xuICAgICAgICAgICAgICAgIG1hc3RlcltvYmpkYXRhXSA9IG9iajtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gbWFzdGVyO1xuICAgIH1cbn07XG5cbk1XRWRpdG9yLmxlQ29yZSA9IHt9O1xuXG4vLyBtZXRob2RzIGFjY2Vzc2libGUgYnkgc2NvcGUubGl2ZWVkaXRcblxuTVdFZGl0b3IubGl2ZWVkaXRNb2RlID0gZnVuY3Rpb24oc2NvcGUpe1xuICAgIHJldHVybiB7XG5cbiAgICAgICAgcHJlcGFyZToge1xuICAgICAgICAgICAgdGl0bGVzOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHQgPSBzY29wZS5xdWVyeVNlbGVjdG9yQWxsKCdbZmllbGQ9XCJ0aXRsZVwiXScpLFxuICAgICAgICAgICAgICAgICAgICBsID0gdC5sZW5ndGgsXG4gICAgICAgICAgICAgICAgICAgIGkgPSAwO1xuXG4gICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0W2ldKS5hZGRDbGFzcyhcIm5vZHJvcFwiKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG5cbiAgICAgICAgaXNTYWZlTW9kZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgICAgICBpZiAoIWVsKSB7XG4gICAgICAgICAgICAgICAgdmFyIHNlbCA9IHNjb3BlLnNlbGVjdGlvbjtcbiAgICAgICAgICAgICAgICBpZighc2VsLnJhbmdlQ291bnQpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB2YXIgcmFuZ2UgPSBzZWwuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICBlbCA9IHNjb3BlLmFwaS5lbGVtZW50Tm9kZShyYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcik7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgaGFzU2FmZSA9IG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlc09uTm9kZU9yUGFyZW50KGVsLCBbJ3NhZmUtbW9kZSddKTtcbiAgICAgICAgICAgIHZhciByZWdJbnNhZmUgPSBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yTm9uZShlbCwgWydyZWd1bGFyLW1vZGUnLCAnc2FmZS1tb2RlJ10pO1xuICAgICAgICAgICAgcmV0dXJuIGhhc1NhZmUgJiYgIXJlZ0luc2FmZTtcbiAgICAgICAgfSxcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKGJvZHksIHNjb3BlKSB7XG4gICAgICAgICAgICBtdy4kKGJvZHkpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICAgICAgaWYgKGV2ZW50LnR5cGUgPT09ICdrZXlkb3duJykge1xuICAgICAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaXNGaWVsZChldmVudC50YXJnZXQpIHx8ICFldmVudC50YXJnZXQuaXNDb250ZW50RWRpdGFibGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHZhciBzZWwgPSBzY29wZS5zZWxlY3Rpb247XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5lbnRlcihldmVudCkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChNV0VkaXRvci5saXZlZWRpdE1vZGUuaXNTYWZlTW9kZShldmVudC50YXJnZXQpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGlzTGlzdCA9IG13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KGV2ZW50LnRhcmdldCwgWydsaScsICd1bCcsICdvbCddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIWlzTGlzdCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5hcGkuaW5zZXJ0SFRNTCgnPGJyPlxcdTIwMEMnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaWYgKHNlbC5yYW5nZUNvdW50ID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHIgPSBzZWwuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChldmVudC5rZXlDb2RlID09PSA5ICYmICFldmVudC5zaGlmdEtleSAmJiBzZWwuZm9jdXNOb2RlLnBhcmVudE5vZGUuaXNDb250ZW50RWRpdGFibGUgJiYgc2VsLmlzQ29sbGFwc2VkKSB7ICAgLyogdGFiIGtleSAqL1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmFwaS5pbnNlcnRIVE1MKCcmbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDsnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbWFuYWdlRGVsZXRlQW5kQmFja3NwYWNlKGV2ZW50LCBzZWwpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG4gICAgICAgIG1hbmFnZURlbGV0ZUFuZEJhY2tzcGFjZUluU2FmZU1vZGUgOiBmdW5jdGlvbiAoZXZlbnQsIHNlbCkge1xuICAgICAgICAgICAgdmFyIG5vZGUgPSBzY29wZS5hcGkuZWxlbWVudE5vZGUoc2VsLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICB2YXIgcmFuZ2UgPSBzZWwuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgIGlmKCFub2RlLnRleHRDb250ZW50LnJlcGxhY2UoL1xccy9naSwgJycpKXtcbiAgICAgICAgICAgICAgICBNV0VkaXRvci5saXZlZWRpdE1vZGUuX21hbmFnZURlbGV0ZUFuZEJhY2tzcGFjZUluU2FmZU1vZGUuZW1wdHlOb2RlKGV2ZW50LCBub2RlLCBzZWwsIHJhbmdlKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBNV0VkaXRvci5saXZlZWRpdE1vZGUuX21hbmFnZURlbGV0ZUFuZEJhY2tzcGFjZUluU2FmZU1vZGUubm9kZUJvdW5kYXJpZXMoZXZlbnQsIG5vZGUsIHNlbCwgcmFuZ2UpO1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH0sXG4gICAgICAgIG1lcmdlOiB7XG4gICAgICAgICAgICAvKiBFeGVjdXRlcyBvbiBiYWNrc3BhY2Ugb3IgZGVsZXRlICovXG4gICAgICAgICAgICBpc01lcmdlYWJsZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgICAgICAgICAgaWYgKCFlbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIGlmIChlbC5ub2RlVHlwZSA9PT0gMykgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgdmFyIGlzID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgdmFyIGNzcyA9ICBnZXRDb21wdXRlZFN0eWxlKGVsKTtcbiAgICAgICAgICAgICAgICB2YXIgZGlzcGxheSA9IGNzcy5nZXRQcm9wZXJ0eVZhbHVlKCdkaXNwbGF5Jyk7XG4gICAgICAgICAgICAgICAgdmFyIHBvc2l0aW9uID0gY3NzLmdldFByb3BlcnR5VmFsdWUoJ3Bvc2l0aW9uJyk7XG4gICAgICAgICAgICAgICAgdmFyIGlzSW5saW5lID0gZGlzcGxheSA9PT0gJ2lubGluZSc7XG4gICAgICAgICAgICAgICAgaWYgKGlzSW5saW5lKSByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICB2YXIgbWVyZ2VhYmxlcyA9IFsncCcsICcuZWxlbWVudCcsICdkaXY6bm90KFtjbGFzc10pJywgJ2gxJywgJ2gyJywgJ2gzJywgJ2g0JywgJ2g1JywgJ2g2J107XG4gICAgICAgICAgICAgICAgbWVyZ2VhYmxlcy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChlbC5tYXRjaGVzKGl0ZW0pKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpcyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgIGlmIChpcykge1xuICAgICAgICAgICAgICAgICAgICBpZiAoZWwucXVlcnlTZWxlY3RvcignLm1vZHVsZScpICE9PSBudWxsIHx8IG13LnRvb2xzLmhhc0NsYXNzKGVsLCAnbW9kdWxlJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlzID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIGlzO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIG1hbmFnZUJyZWFrYWJsZXM6IGZ1bmN0aW9uIChjdXJyLCBuZXh0LCBkaXIsIGV2ZW50KSB7XG4gICAgICAgICAgICAgICAgdmFyIGlzbm9uYnJlYWthYmxlID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZShjdXJyLCBkaXIpO1xuICAgICAgICAgICAgICAgIGlmIChpc25vbmJyZWFrYWJsZSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29udHMgPSBzY29wZS5zZWxlY3Rpb24uZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5leHQgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChuZXh0Lm5vZGVUeXBlID09PSAzICYmIC9cXHJ8XFxuLy5leGVjKG5leHQubm9kZVZhbHVlKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGRpciA9PT0gJ25leHQnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUubGl2ZWVkaXQuY3Vyc29yVG9FbGVtZW50KG5leHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUubGl2ZWVkaXQuY3Vyc29yVG9FbGVtZW50KG5leHQsICdlbmQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBpc0luTm9uYnJlYWthYmxlOiBmdW5jdGlvbiAoZWwsIGRpcikge1xuICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0ID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCBkaXIpO1xuXG4gICAgICAgICAgICAgICAgaWYgKGFic05leHQubm9kZVR5cGUgPT09IDMgJiYgL1xccnxcXG4vLmV4ZWMoYWJzTmV4dC5ub2RlVmFsdWUpICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIGFic05leHQgPSBzY29wZS5saXZlZWRpdC5tZXJnZS5maW5kTmV4dE5lYXJlc3QoZWwsIGRpciwgdHJ1ZSlcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoYWJzTmV4dC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKGFic05leHQsIFsnbm9kcm9wJywgJ2FsbG93LWRyb3AnXSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAoYWJzTmV4dC5xdWVyeVNlbGVjdG9yKCcubm9kcm9wJywgJy5hbGxvdy1kcm9wJykgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUubGl2ZWVkaXQubWVyZ2UuYWx3YXlzTWVyZ2VhYmxlKGFic05leHQpICYmIChzY29wZS5saXZlZWRpdC5tZXJnZS5hbHdheXNNZXJnZWFibGUoYWJzTmV4dC5maXJzdEVsZW1lbnRDaGlsZCkgfHwgIWFic05leHQuZmlyc3RFbGVtZW50Q2hpbGQpKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKGVsLnRleHRDb250ZW50ID09PSAnJykge1xuXG4gICAgICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0TmV4dCA9IHNjb3BlLmxpdmVlZGl0Lm1lcmdlLmZpbmROZXh0TmVhcmVzdChhYnNOZXh0LCBkaXIpO1xuICAgICAgICAgICAgICAgICAgICBpZiAoYWJzTmV4dC5ub2RlVHlwZSA9PT0gMyAmJiAvXFxyfFxcbi8uZXhlYyhhYnNOZXh0Lm5vZGVWYWx1ZSkgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBzY29wZS5saXZlZWRpdC5tZXJnZS5pc0luTm9uYnJlYWthYmxlQ2xhc3MoYWJzTmV4dE5leHQpXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoZWwubm9kZVR5cGUgPT09IDEgJiYgISFlbC50ZXh0Q29udGVudC50cmltKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoZWwubmV4dFNpYmxpbmcgPT09IG51bGwgJiYgZWwubm9kZVR5cGUgPT09IDMgJiYgZGlyID09ICduZXh0Jykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgYWJzTmV4dCA9IHNjb3BlLmxpdmVlZGl0Lm1lcmdlLmZpbmROZXh0TmVhcmVzdChlbCwgZGlyKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGFic05leHROZXh0ID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuZmluZE5leHROZWFyZXN0KGFic05leHQsIGRpcik7XG4gICAgICAgICAgICAgICAgICAgIGlmICgvXFxyfFxcbi8uZXhlYyhhYnNOZXh0Lm5vZGVWYWx1ZSkgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBzY29wZS5saXZlZWRpdC5tZXJnZS5pc0luTm9uYnJlYWthYmxlQ2xhc3MoYWJzTmV4dE5leHQpXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAoYWJzTmV4dE5leHQubm9kZVR5cGUgPT09IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBzY29wZS5saXZlZWRpdC5tZXJnZS5pc0luTm9uYnJlYWthYmxlQ2xhc3MoYWJzTmV4dE5leHQpIHx8IHNjb3BlLmxpdmVlZGl0Lm1lcmdlLmlzSW5Ob25icmVha2FibGVDbGFzcyhhYnNOZXh0TmV4dC5maXJzdENoaWxkKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIGlmIChhYnNOZXh0TmV4dC5ub2RlVHlwZSA9PT0gMykge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoZWwucHJldmlvdXNTaWJsaW5nID09PSBudWxsICYmIGVsLm5vZGVUeXBlID09PSAzICYmIGRpciA9PT0gJ3ByZXYnKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0ID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCAncHJldicpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgYWJzTmV4dE5leHQgPSBzY29wZS5saXZlZWRpdC5tZXJnZS5maW5kTmV4dE5lYXJlc3QoYWJzTmV4dCwgJ3ByZXYnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGFic05leHROZXh0Lm5vZGVUeXBlID09PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2NvcGUubGl2ZWVkaXQubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZUNsYXNzKGFic05leHROZXh0KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIGlmIChhYnNOZXh0TmV4dC5ub2RlVHlwZSA9PT0gMykge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWwgPSBzY29wZS5hcGkuZWxlbWVudE5vZGUoZWwpO1xuXG4gICAgICAgICAgICAgICAgdmFyIGlzID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZUNsYXNzKGVsKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gaXM7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgaXNJbk5vbmJyZWFrYWJsZUNsYXNzOiBmdW5jdGlvbiAoZWwsIGRpcikge1xuICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0ID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCBkaXIpO1xuXG4gICAgICAgICAgICAgICAgaWYgKGVsLm5vZGVUeXBlID09PSAzICYmIC9cXHJ8XFxuLy5leGVjKGFic05leHQubm9kZVZhbHVlKSA9PT0gbnVsbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIGVsID0gc2NvcGUuYXBpLmVsZW1lbnROb2RlKGVsKTtcbiAgICAgICAgICAgICAgICB2YXIgY2xhc3NlcyA9IFsndW5icmVha2FibGUnLCAnKmNvbCcsICcqcm93JywgJypidG4nLCAnKmljb24nLCAnbW9kdWxlJywgJ2VkaXQnXTtcbiAgICAgICAgICAgICAgICB2YXIgaXMgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBjbGFzc2VzLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGl0ZW0uaW5kZXhPZignKicpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IGl0ZW0uc3BsaXQoJyonKVsxXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChlbC5jbGFzc05hbWUuaW5kZXhPZihpdGVtKSAhPT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpcyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5mb3JlYWNoUGFyZW50cyhlbCwgZnVuY3Rpb24gKGxvb3ApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHRoaXMuY2xhc3NOYW1lLmluZGV4T2YoaXRlbSkgIT09IC0xICYmICF0aGlzLmNvbnRhaW5zKGVsKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaXMgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuc3RvcExvb3AobG9vcCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpcyA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuc3RvcExvb3AobG9vcCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgaXRlbSkgfHwgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhlbCwgaXRlbSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpcyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICByZXR1cm4gaXM7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZ2V0TmV4dDogZnVuY3Rpb24gKGN1cnIpIHtcbiAgICAgICAgICAgICAgICB2YXIgbmV4dCA9IGN1cnIubmV4dFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgd2hpbGUgKGN1cnIgIT09IG51bGwgJiYgY3Vyci5uZXh0U2libGluZyA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgICAgICBuZXh0ID0gY3Vyci5uZXh0U2libGluZztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIG5leHQ7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZ2V0UHJldjogZnVuY3Rpb24gKGN1cnIpIHtcbiAgICAgICAgICAgICAgICB2YXIgbmV4dCA9IGN1cnIucHJldmlvdXNTaWJsaW5nO1xuICAgICAgICAgICAgICAgIHdoaWxlIChjdXJyICE9PSBudWxsICYmIGN1cnIucHJldmlvdXNTaWJsaW5nID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgICAgICAgIG5leHQgPSBjdXJyLnByZXZpb3VzU2libGluZztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIG5leHQ7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZmluZE5leHROZWFyZXN0OiBmdW5jdGlvbiAoZWwsIGRpciwgc2VhcmNoRWxlbWVudCkge1xuICAgICAgICAgICAgICAgIHNlYXJjaEVsZW1lbnQgPSB0eXBlb2Ygc2VhcmNoRWxlbWVudCA9PT0gJ3VuZGVmaW5lZCcgPyBmYWxzZSA6IHRydWU7XG4gICAgICAgICAgICAgICAgaWYgKGRpciA9PT0gJ25leHQnKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBkb3NlYXJjaCA9IHNlYXJjaEVsZW1lbnQgPyAnbmV4dEVsZW1lbnRTaWJsaW5nJyA6ICduZXh0U2libGluZyc7XG4gICAgICAgICAgICAgICAgICAgIHZhciBuZXh0ID0gZWxbZG9zZWFyY2hdO1xuICAgICAgICAgICAgICAgICAgICBpZiAobmV4dCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgd2hpbGUgKGVsW2Rvc2VhcmNoXSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsID0gZWwucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXh0ID0gZWxbZG9zZWFyY2hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB2YXIgZG9zZWFyY2ggPSBzZWFyY2hFbGVtZW50ID8gJ3ByZXZpb3VzRWxlbWVudFNpYmxpbmcnIDogJ3ByZXZpb3VzU2libGluZyc7XG4gICAgICAgICAgICAgICAgICAgIHZhciBuZXh0ID0gZWxbZG9zZWFyY2hdO1xuICAgICAgICAgICAgICAgICAgICBpZiAobmV4dCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgd2hpbGUgKGVsW2Rvc2VhcmNoXSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsID0gZWwucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXh0ID0gZWxbZG9zZWFyY2hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBuZXh0O1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGFsd2F5c01lcmdlYWJsZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgICAgICAgICAgaWYgKCFlbCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChlbC5ub2RlVHlwZSA9PT0gMykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2NvcGUubGl2ZWVkaXQubWVyZ2UuYWx3YXlzTWVyZ2VhYmxlKHNjb3BlLmFwaS5lbGVtZW50Tm9kZShlbCkpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChlbC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAoL14oPzphcmVhfGJyfGNvbHxlbWJlZHxocnxpbWd8aW5wdXR8bGlua3xtZXRhfHBhcmFtKSQvaS50ZXN0KGVsLnRhZ05hbWUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAoL14oPzpzdHJvbmd8ZW18aXxifGxpKSQvaS50ZXN0KGVsLnRhZ05hbWUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAoL14oPzpzcGFuKSQvaS50ZXN0KGVsLnRhZ05hbWUpICYmICFlbC5jbGFzc05hbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgJ21vZHVsZScpKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZWwsICdtb2R1bGUnKSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgb3JkID0gbXcudG9vbHMucGFyZW50c09yZGVyKGVsLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgc2VsZWN0b3JzID0gW1xuICAgICAgICAgICAgICAgICAgICAgICAgJ3AuZWxlbWVudCcsICdkaXYuZWxlbWVudCcsICdkaXY6bm90KFtjbGFzc10pJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICdoMS5lbGVtZW50JywgJ2gyLmVsZW1lbnQnLCAnaDMuZWxlbWVudCcsICdoNC5lbGVtZW50JywgJ2g1LmVsZW1lbnQnLCAnaDYuZWxlbWVudCcsXG4gICAgICAgICAgICAgICAgICAgICAgICAnLmVkaXQgIGgxJywgJy5lZGl0ICBoMicsICcuZWRpdCAgaDMnLCAnLmVkaXQgIGg0JywgJy5lZGl0ICBoNScsICcuZWRpdCAgaDYnLFxuICAgICAgICAgICAgICAgICAgICAgICAgJy5lZGl0IHAnXG4gICAgICAgICAgICAgICAgICAgIF0sXG4gICAgICAgICAgICAgICAgICAgIGZpbmFsID0gZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgc2VsZWN0b3JzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpdGVtID0gc2VsZWN0b3JzW2ldO1xuICAgICAgICAgICAgICAgICAgICBpZiAoZWwubWF0Y2hlcyhpdGVtKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZmluYWwgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZpbmFsO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBfbWFuYWdlRGVsZXRlQW5kQmFja3NwYWNlSW5TYWZlTW9kZSA6IHtcbiAgICAgICAgICAgIGVtcHR5Tm9kZTogZnVuY3Rpb24gKGV2ZW50LCBub2RlLCBzZWwsIHJhbmdlKSB7XG4gICAgICAgICAgICAgICAgaWYoIWNhbkRlc3Ryb3kobm9kZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgdG9kZWxldGUgPSBub2RlO1xuICAgICAgICAgICAgICAgIGlmKG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyhub2RlLnBhcmVudE5vZGUsIFsndGV4dCcsICd0aXRsZSddKSl7XG4gICAgICAgICAgICAgICAgICAgIHRvZGVsZXRlID0gbm9kZS5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgdHJhbnNmZXIsIHRyYW5zZmVyUG9zaXRpb247XG4gICAgICAgICAgICAgICAgaWYgKG13LmV2ZW50LmlzLmRlbGV0ZShldmVudCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdHJhbnNmZXIgPSB0b2RlbGV0ZS5uZXh0RWxlbWVudFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgICAgIHRyYW5zZmVyUG9zaXRpb24gPSAnc3RhcnQnO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRyYW5zZmVyID0gdG9kZWxldGUucHJldmlvdXNFbGVtZW50U2libGluZztcbiAgICAgICAgICAgICAgICAgICAgdHJhbnNmZXJQb3NpdGlvbiA9ICdlbmQnO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50ID0gdG9kZWxldGUucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICBzY29wZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHBhcmVudCxcbiAgICAgICAgICAgICAgICAgICAgdmFsdWU6IHBhcmVudC5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAkKHRvZGVsZXRlKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBpZih0cmFuc2ZlciAmJiBtdy50b29scy5pc0VkaXRhYmxlKHRyYW5zZmVyKSkge1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmxpdmVlZGl0LmN1cnNvclRvRWxlbWVudCh0cmFuc2ZlciwgdHJhbnNmZXJQb3NpdGlvbik7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBzY29wZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHBhcmVudCxcbiAgICAgICAgICAgICAgICAgICAgdmFsdWU6IHBhcmVudC5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBub2RlQm91bmRhcmllczogZnVuY3Rpb24gKGV2ZW50LCBub2RlLCBzZWwsIHJhbmdlKSB7XG4gICAgICAgICAgICAgICAgdmFyIGlzU3RhcnQgPSByYW5nZS5zdGFydE9mZnNldCA9PT0gMCB8fCAhKChzZWwuYW5jaG9yTm9kZS5kYXRhIHx8ICcnKS5zdWJzdHJpbmcoMCwgcmFuZ2Uuc3RhcnRPZmZzZXQpLnJlcGxhY2UoL1xccy9nLCAnJykpO1xuICAgICAgICAgICAgICAgIHZhciBjdXJyLCBjb250ZW50O1xuICAgICAgICAgICAgICAgIGlmKG13LmV2ZW50LmlzLmJhY2tTcGFjZShldmVudCkgJiYgaXNTdGFydCAmJiByYW5nZS5jb2xsYXBzZWQpeyAvLyBpcyBhdCB0aGUgYmVnaW5uaW5nXG4gICAgICAgICAgICAgICAgICAgIGN1cnIgPSBub2RlO1xuICAgICAgICAgICAgICAgICAgICBpZihtdy50b29scy5oYXNBbnlPZkNsYXNzZXMobm9kZS5wYXJlbnROb2RlLCBbJ3RleHQnLCAndGl0bGUnXSkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgY3VyciA9IG5vZGUucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB2YXIgcHJldiA9IGN1cnIucHJldmlvdXNFbGVtZW50U2libGluZztcbiAgICAgICAgICAgICAgICAgICAgaWYocHJldiAmJiBwcmV2Lm5vZGVOYW1lID09PSBub2RlLm5vZGVOYW1lICYmIGNhbkRlc3Ryb3kobm9kZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgPSBub2RlLmlubmVySFRNTDtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmxpdmVlZGl0LmN1cnNvclRvRWxlbWVudChwcmV2LCAnZW5kJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcmV2LmFwcGVuZENoaWxkKHJhbmdlLmNyZWF0ZUNvbnRleHR1YWxGcmFnbWVudChjb250ZW50KSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKGN1cnIpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmKG13LmV2ZW50LmlzLmRlbGV0ZShldmVudClcbiAgICAgICAgICAgICAgICAgICAgJiYgcmFuZ2UuY29sbGFwc2VkXG4gICAgICAgICAgICAgICAgICAgICYmIHJhbmdlLnN0YXJ0T2Zmc2V0ID09PSBzZWwuYW5jaG9yTm9kZS5kYXRhLnJlcGxhY2UoL1xccyokLywnJykubGVuZ3RoIC8vIGlzIGF0IHRoZSBlbmRcbiAgICAgICAgICAgICAgICAgICAgJiYgY2FuRGVzdHJveShub2RlKSl7XG4gICAgICAgICAgICAgICAgICAgIGN1cnIgPSBub2RlO1xuICAgICAgICAgICAgICAgICAgICBpZihtdy50b29scy5oYXNBbnlPZkNsYXNzZXMobm9kZS5wYXJlbnROb2RlLCBbJ3RleHQnLCAndGl0bGUnXSkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgY3VyciA9IG5vZGUucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB2YXIgbmV4dCA9IGN1cnIubmV4dEVsZW1lbnRTaWJsaW5nLCBkZWxldGVQYXJlbnQ7XG4gICAgICAgICAgICAgICAgICAgIGlmKG13LnRvb2xzLmhhc0FueU9mQ2xhc3NlcyhuZXh0LCBbJ3RleHQnLCAndGl0bGUnXSkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV4dCA9IG5leHQuZmlyc3RFbGVtZW50Q2hpbGQ7XG4gICAgICAgICAgICAgICAgICAgICAgICBkZWxldGVQYXJlbnQgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmKG5leHQgJiYgbmV4dC5ub2RlTmFtZSA9PT0gY3Vyci5ub2RlTmFtZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCA9IG5leHQuaW5uZXJIVE1MO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBwYXJlbnQgPSBkZWxldGVQYXJlbnQgPyBuZXh0LnBhcmVudE5vZGUucGFyZW50Tm9kZSA6IG5leHQucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5hY3Rpb25SZWNvcmQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogcGFyZW50LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiBwYXJlbnQuaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJyLmFwcGVuZChyYW5nZS5jcmVhdGVDb250ZXh0dWFsRnJhZ21lbnQoY29udGVudCkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBtYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2U6IGZ1bmN0aW9uIChldmVudCwgc2VsKSB7XG4gICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSB8fCBtdy5ldmVudC5pcy5iYWNrU3BhY2UoZXZlbnQpKSB7XG4gICAgICAgICAgICAgICAgaWYoIXNlbC5yYW5nZUNvdW50KSByZXR1cm47XG4gICAgICAgICAgICAgICAgdmFyIHIgPSBzZWwuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICB2YXIgaXNTYWZlID0gc2NvcGUubGl2ZWVkaXQuaXNTYWZlTW9kZSgpO1xuXG4gICAgICAgICAgICAgICAgaWYoaXNTYWZlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBzY29wZS5saXZlZWRpdC5tYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2VJblNhZmVNb2RlKGV2ZW50LCBzZWwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB2YXIgbmV4dE5vZGUgPSBudWxsLCBuZXh0Y2hhciwgbmV4dG5leHRjaGFyLCBuZXh0ZWw7XG5cbiAgICAgICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSkge1xuICAgICAgICAgICAgICAgICAgICBuZXh0Y2hhciA9IHNlbC5mb2N1c05vZGUudGV4dENvbnRlbnQuY2hhckF0KHNlbC5mb2N1c09mZnNldCk7XG4gICAgICAgICAgICAgICAgICAgIG5leHRuZXh0Y2hhciA9IHNlbC5mb2N1c05vZGUudGV4dENvbnRlbnQuY2hhckF0KHNlbC5mb2N1c09mZnNldCArIDEpO1xuICAgICAgICAgICAgICAgICAgICBuZXh0ZWwgPSBzZWwuZm9jdXNOb2RlLm5leHRTaWJsaW5nIHx8IHNlbC5mb2N1c05vZGUubmV4dEVsZW1lbnRTaWJsaW5nO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbmV4dGNoYXIgPSBzZWwuZm9jdXNOb2RlLnRleHRDb250ZW50LmNoYXJBdChzZWwuZm9jdXNPZmZzZXQgLSAxKTtcbiAgICAgICAgICAgICAgICAgICAgbmV4dG5leHRjaGFyID0gc2VsLmZvY3VzTm9kZS50ZXh0Q29udGVudC5jaGFyQXQoc2VsLmZvY3VzT2Zmc2V0IC0gMik7XG4gICAgICAgICAgICAgICAgICAgIG5leHRlbCA9IHNlbC5mb2N1c05vZGUucHJldmlvdVNpYmxpbmcgfHwgc2VsLmZvY3VzTm9kZS5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICgobmV4dGNoYXIgPT09ICcgJyB8fCAvXFxyfFxcbi8uZXhlYyhuZXh0Y2hhcikgIT09IG51bGwpICYmIHNlbC5mb2N1c05vZGUubm9kZVR5cGUgPT09IDMgJiYgIW5leHRuZXh0Y2hhcikge1xuICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgaWYgKG5leHRuZXh0Y2hhciA9PT0gJycpIHtcblxuICAgICAgICAgICAgICAgICAgICBpZiAobmV4dGNoYXIucmVwbGFjZSgvXFxzL2csICcnKSA9PT0gJycgJiYgci5jb2xsYXBzZWQpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG5leHRlbCAmJiAhbXcudG9vbHMuaXNCbG9ja0xldmVsKG5leHRlbCkgJiYgKCB0eXBlb2YgbmV4dGVsLmNsYXNzTmFtZSA9PT0gJ3VuZGVmaW5lZCcgfHwgIW5leHRlbC5jbGFzc05hbWUudHJpbSgpKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAobmV4dGVsICYmIG5leHRlbC5ub2RlTmFtZSAhPT0gJ0JSJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChzZWwuZm9jdXNOb2RlLm5vZGVOYW1lID09PSAnUCcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGV2ZW50LmtleUNvZGUgPT09IDQ2KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoc2VsLmZvY3VzTm9kZS5uZXh0RWxlbWVudFNpYmxpbmcubm9kZU5hbWUgPT09ICdQJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChldmVudC5rZXlDb2RlID09PSA4KSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChzZWwuZm9jdXNOb2RlLnByZXZpb3VzRWxlbWVudFNpYmxpbmcubm9kZU5hbWUgPT09ICdQJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAoXG4gICAgICAgICAgICAgICAgICAgICAgICAoZm9jdXMucHJldmlvdXNFbGVtZW50U2libGluZyA9PT0gbnVsbCAmJiByb290Zm9jdXMucHJldmlvdXNFbGVtZW50U2libGluZyA9PT0gbnVsbClcbiAgICAgICAgICAgICAgICAgICAgICAgICYmIG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlc09uTm9kZU9yUGFyZW50KHJvb3Rmb2N1cywgWydub2Ryb3AnLCAnYWxsb3ctZHJvcCddKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChuZXh0Y2hhciA9PT0gJycpIHtcblxuICAgICAgICAgICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV4dE5vZGUgPSBzY29wZS5saXZlZWRpdC5tZXJnZS5nZXROZXh0KHNlbC5mb2N1c05vZGUpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5iYWNrU3BhY2UoZXZlbnQpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXh0Tm9kZSA9IHNjb3BlLmxpdmVlZGl0Lm1lcmdlLmdldFByZXYoc2VsLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaWYgKHNjb3BlLmxpdmVlZGl0Lm1lcmdlLmFsd2F5c01lcmdlYWJsZShuZXh0Tm9kZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIG5vbmJyID0gc2NvcGUubGl2ZWVkaXQubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZShuZXh0Tm9kZSlcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5vbmJyKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmIChuZXh0Tm9kZSAhPT0gbnVsbCAmJiBzY29wZS5saXZlZWRpdC5tZXJnZS5pc01lcmdlYWJsZShuZXh0Tm9kZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5kZWxldGUoZXZlbnQpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUubGl2ZWVkaXQubWVyZ2UubWFuYWdlQnJlYWthYmxlcyhzZWwuZm9jdXNOb2RlLCBuZXh0Tm9kZSwgJ25leHQnLCBldmVudClcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmxpdmVlZGl0Lm1lcmdlLm1hbmFnZUJyZWFrYWJsZXMoc2VsLmZvY3VzTm9kZSwgbmV4dE5vZGUsICdwcmV2JywgZXZlbnQpXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmIChuZXh0Tm9kZSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV4dE5vZGUgPSBzZWwuZm9jdXNOb2RlLnBhcmVudE5vZGUubmV4dFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoIXNjb3BlLmxpdmVlZGl0Lm1lcmdlLmlzTWVyZ2VhYmxlKG5leHROb2RlKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmxpdmVlZGl0Lm1lcmdlLm1hbmFnZUJyZWFrYWJsZXMoc2VsLmZvY3VzTm9kZSwgbmV4dE5vZGUsICduZXh0JywgZXZlbnQpXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5saXZlZWRpdC5tZXJnZS5tYW5hZ2VCcmVha2FibGVzKHNlbC5mb2N1c05vZGUsIG5leHROb2RlLCAncHJldicsIGV2ZW50KVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cblxuICAgIH07XG59O1xuXG5cblxuXG5cblxuIiwiTVdFZGl0b3IudG9vbHMgPSB7XG5cbn07XG4iXSwic291cmNlUm9vdCI6IiJ9