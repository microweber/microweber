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
    }
}
mw.wysiwyg = {
  parseClassApplierSheet:function(){
    var sheet = mwd.querySelector('link[classApplier]');
    if(sheet!==null){
      var rules = sheet.sheet.rules;
      for(var i = 0; i< rules.length; i++){
        if(!rules[i].selectorText) continue;

        var rule = rules[i].selectorText.trim();
        var spl = rule.split('.')
        if(rule.indexOf('.') === 0
        && rule.indexOf(':') === -1
        && rule.indexOf('#') === -1
        && spl.length === 2
        && rule.indexOf('[') === -1){
          classApplier.push(spl[1]);
        }
      }
    }
  },
  initClassApplier:function(){
    this.parseClassApplierSheet();
    var dropdown = $('#format_main ul');
    classApplier.forEach(function(cls, i){
      dropdown.append('<li value=".'+cls+'"><a href="#"><div class="'+cls+'">Custom '+i+'</div></a></li>')
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
        var cleaner = $('<div class="mw-cleaner-block" contenteditable="true"><small class="muted">Paste document here.</small></div>')
        var inserter = $('<span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right">Insert</span>')
        var clean = mw.modal({
            content: cleaner,
            overlay: true,
            title: 'Paste from word'
        });
        cleaner.on('paste', function () {
            setTimeout(function () {
                cleaner[0].innerHTML = mw.wysiwyg.clean_word(cleaner[0].innerHTML);
            }, 100)

        });
        cleaner.on('click', function () {
            if (!$(this).hasClass('active')) {
                $(this).addClass('active')
                $(this).html('')
            }
        });
        inserter.on('click', function () {
            mw.wysiwyg.restore_selection();
            mw.wysiwyg.insert_html(cleaner.html());
            clean.remove();
        });
        cleaner.after(inserter)
    },
    globalTarget: mwd.body,
    allStatements: function (c, f) {
        var sel = window.getSelection(),
            range = sel.getRangeAt(0),
            common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        //var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
        var nodrop_state = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(common, ['allow-drop', 'nodrop']);

        if (mw.wysiwyg.isSelectionEditable() && nodrop_state) {
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
                    $(el).removeAttr("style");
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
                module.contentEditable = false;
                $(module.querySelectorAll(".edit")).each(function () {
                    this.contentEditable = true;
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                    });
                });
            }
            else {
                var editables = mwd.querySelectorAll('[contenteditable]'), l = editables.length, x = 0;
                for (; x < l; x++) {
                    editables[x].contentEditable = 'inherit';
                }
                mw.$(".edit").each(function () {
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                        if (this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {
                            mw.wysiwyg.modify(this, function () {
                              if(!mw.tools.hasAnyOfClassesOnNodeOrParent(els[i], ['safe-mode'])){
                                this.innerHTML = '<p class="element">' + this.innerHTML + '</p>';
                              }
                            });
                        }
                        mw.wysiwyg.normalizeBase64Images(this);
                    }, false, true);
                    $(this).mouseenter(function () {
                        if (this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {

                            mw.wysiwyg.modify(this, function () {
                              if(!mw.tools.hasAnyOfClassesOnNodeOrParent(els[i], ['safe-mode'])){
                                this.innerHTML = '<p class="element">' + this.innerHTML + '&nbsp;</p>';
                              }
                            });
                        }
                    })
                });
                mw.$(".empty-element, .ui-resizable-handle").each(function () {
                    this.contentEditable = false;
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
            var callback = el;
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
            if (a[i].innerHTML == '' || a[i].innerHTML.replace(/\s+/g, '') == '') {
                a[i].innerHTML = '&zwj;&nbsp;&zwj;';

            }
        }
    },
    removeEditable: function () {
        if (!mw.is.ie) {
            var i,
                all = mwd.getElementsByClassName('edit'),
                len = all.length;
            for (; i < len; i++) {
                all[i].contentEditable = false;
            }
        }
        else {
            mw.$(".edit [contenteditable='true'], .edit").removeAttr('contenteditable');
        }
    },
    validateEditForIE: function (target) {
        if ($(target).hasClass("edit")) {
            return true;
        }
        var arr = [];
        mw.tools.foreachParents(target, function (loop) {
            arr.push(this.className);
            if ($(this).hasClass("module")) {
                mw.tools.stopLoop(loop)
            }
        });
    },
    prepareContentEditable: function () {
        mw.on("EditMouseDown", function (e, el, target, originalEvent) {
            mw.wysiwyg.removeEditable();
            mw.$(".edit").attr("contentEditable", "false");
            var _el = $(el);
            if(!mw.tools.hasAnyOfClassesOnNodeOrParent(target, ['safe-mode'])){
              _el.attr("contentEditable", "true").find('[contenteditable="false"]').not('.module').removeAttr('contenteditable');
               if (!mw.is.ie) { //Non IE browser

                var orderValid = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(originalEvent.target, ['edit', 'module']);

                el.contentEditable = orderValid;
            }
            else {   // IE browser
                mw.wysiwyg.removeEditable();
                var cls = target.className;
                if (!mw.tools.hasClass(cls, 'empty-element') && !mw.tools.hasClass(cls, 'ui-resizable-handle')) {
                    if (mw.tools.hasParentsWithClass(el, 'module')) {
                        target.contentEditable = true;
                    }
                    else {
                        if (!mw.tools.hasParentsWithClass(target, "module")) {
                            if (mw.isDragItem(target)) {
                                target.contentEditable = true;
                            }
                            else {
                                mw.tools.foreachParents(target, function (loop) {
                                    if (mw.isDragItem(this)) {
                                        this.contentEditable = true;
                                        mw.tools.loop[loop] = false;
                                    }
                                });
                            }
                        }
                    }
                }
            } 
            }
            else{
              var firstBlock = target;
              var blocks = ['p','div','h1','h2','h3','h4','h5','h6', 'header','section','footer', 'ul', 'ol'];
              var blocksClass = ['safe-element'];
              var po = mw.tools.parentsOrder(firstBlock, ['edit', 'module']);

              if(po.module == -1 || po.module > po.edit){

                if( blocks.indexOf(firstBlock.nodeName.toLocaleLowerCase()) === -1 && !mw.tools.hasAnyOfClassesOnNodeOrParent(firstBlock, blocksClass)){
                  var cls = [];
                  blocksClass.forEach(function(item){
                    cls.push('.'+item)
                  });
                  cls = cls.concat(blocks);
                  firstBlock = mw.tools.firstMatchesOnNodeOrParent(firstBlock, cls);
                }
                mw.$('[contenteditable]').not(firstBlock).removeAttr('contenteditable')
                firstBlock.contentEditable = true;
              }
              else{
                  mw.$('[contenteditable]').removeAttr('contenteditable')
              }

            }


        });
    },
    hide_drag_handles: function () {
        mw.$(".mw-wyswyg-plus-element,.mw_handle_row").hide();
    },
    show_drag_handles: function (){
        mw.$(".mw-wyswyg-plus-element,.mw_handle_row").show();
    },

    _external: function () {
        var external = mwd.createElement('div');
        external.className = 'wysiwyg_external';
        mwd.body.appendChild(external);
        return external;
    },
    isSelectionEditable: function () {
        try {
            var node = window.getSelection().focusNode;
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
    execCommandFilter:function(a, b, c){
      var arr = ['justifyCenter','justifyFull','justifyLeft','justifyRight'];
      if(mw.is.firefox && arr.indexOf(a) !== -1){
        var node = window.getSelection().focusNode;
        var elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        if(elementNode.nodeName == 'P'){
          var align = a.split('justify')[1].toLocaleString();
          if(align == 'full') {
            align = 'justify';
          }
          elementNode.style.textAlign = align
          return false;
        }
      }
      return true
    },
    execCommand: function (a, b, c) {
        var fnode = window.getSelection().focusNode;

        if ((fnode !== null) && (mw.tools.hasClass(fnode, 'plain-text') || mw.tools.hasClass(fnode.parentNode, 'plain-text') || mw.tools.hasParentsWithClass(fnode.parentNode, 'plain-text'))) {
            return false;
        }
        try {  // 0x80004005
            if (document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable()) {
                var b = b || false;
                var c = c || false;
                var node = window.getSelection().focusNode;
                var elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
                var before = $(node).clone()[0];
                if (window.getSelection().rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
                    mwd.execCommand(a, b, c);
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
    nceui: function () {  //remove defaults for browser's content editable tools

        if (mw.settings.liveEdit) {
            mw.wysiwyg.execCommand('enableObjectResizing', false, 'false');
            mw.wysiwyg.execCommand('2D-Position', false, false);
            mw.wysiwyg.execCommand("enableInlineTableEditing", null, false);
        }
    },
    _pasteManager:undefined,
    pasteManager:function(html){
      html = mw.wysiwyg.clean_word(html)
      mw.wysiwyg._pasteManager = this._pasteManager || document.createElement('div');
      mw.wysiwyg._pasteManager.innerHTML = html;
      $('*',  mw.wysiwyg._pasteManager).removeAttr('style');
      return  mw.wysiwyg._pasteManager.innerHTML;
    },
    cleanExcel:function(clipboard){
      var html = clipboard.getData('text/html');
      var parser = mw.tools.parseHtml(html).body;
      $("[style*='mso-spacerun']", parser).remove()
      $("style", parser).remove()
      $('table', parser)
        .width('100%')
        .addClass('mw-wysiwyg-table')
        .removeAttr('width');
      return parser.innerHTML;
    },
    pastedFromExcel:function(clipboard){
      var html = clipboard.getData('text/html');
      return html.indexOf('ProgId content=Excel.Sheet') !== -1
    },
    doLocalPaste:function(clipboard){
      var html =  clipboard.getData('text/html');
      var parser = mw.tools.parseHtml(html).body;
      mw.$('[id]', parser).each(function(){
        this.id = 'dlp-item-'+mw.random();
      });
      mw.wysiwyg.insert_html(parser.innerHTML);
    },
    isLocalPaste:function(clipboard){
      var html =  clipboard.getData('text/html');
      var parser = mw.tools.parseHtml(html).body;
      return parser.querySelector('.module,.element,.edit') !== null;
    },
    paste: function (e) {

        if(!!e.originalEvent){
          var clipboard = e.originalEvent.clipboardData || mww.clipboardData;
        }
        else{
          var clipboard = e.clipboardData || mww.clipboardData;
        }
        
        if(mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['safe-mode'])){
          if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {
            var text = clipboard.getData('text');
            mw.wysiwyg.insert_html(text);
            e.preventDefault()
            return false;
          }

        }

        if(mw.wysiwyg.isLocalPaste(clipboard)){
          mw.wysiwyg.doLocalPaste(clipboard);
          e.preventDefault();
          return false;
        }

       if(mw.wysiwyg.pastedFromExcel(clipboard)){
         var html = mw.wysiwyg.cleanExcel(clipboard)
          mw.wysiwyg.insert_html(html);
          e.preventDefault();
          return false;
       }

       if(clipboard.files.length > 0){
        var i = 0;
        for( ; i < clipboard.files.length; i++){
          var item = clipboard.files[i];
          if(item.type.indexOf('image/') != -1){
            var reader = new FileReader();
            reader.onload = function(e){
              mw.wysiwyg.insert_html('<img src="'+(e.target.result)+'">');
              mw.wysiwyg.normalizeBase64Images();
            }
            reader.readAsDataURL(item)
          }
        }
        e.preventDefault();
       }
       else{
          if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {
              if (!mw.is.ie) {
                  //var html = clipboard.getData('text/plain');
                  var html =  clipboard.getData('text/html');
              }
              else {
                  var html = clipboard.getData('text');
              }

              if (!!html) {
                  if (typeof mw.form != 'undefined') {
                      var is_link = mw.form.validate.url(html);
                      if (is_link) {
                          var html = "<a href='" + html + "'>" + html + "</a>";
                      }
                  }

                  html = mw.wysiwyg.pasteManager(html);
                  mw.wysiwyg.insert_html(html);
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
            event.preventDefault()
        });
        var items = mw.$(".element").not(".module");
        mw.$(".mw_editor").hover(function () {
            $(this).addClass("editor_hover")
        }, function () {
            $(this).removeClass("editor_hover")
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
        if (method == 'selection') {
            var sel = mww.getSelection();
            var f = sel.focusNode;
            var f = mw.tools.hasClass(f, 'edit') ? f : mw.tools.firstParentWithClass(f, 'edit');
            if (f.attributes != undefined && !!f.attributes.field && f.attributes.field.nodeValue == 'title') {
                if (!!e) {
                    mw.event.cancel(e, true);
                }
                return false;
            }
        }
    },
    merge:{ /* Executes on backspace or delete */
      isMergeable : function (el) {
          if (!el) return false;
          if (el.nodeType === 3) return true;
          var is = false;

          var display = getComputedStyle(el).getPropertyValue('display');

          var position = getComputedStyle(el).getPropertyValue('position');
          var isInline = display == 'inline';
          if(isInline) return true;
          var mergeables = ['p', '.element', 'div:not([class])', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
          mergeables.forEach(function (item) {
              if (el.matches(item)) {
                  is = true;
              }
          });

          if(is){
              if(el.querySelector('.module') !== null || mw.tools.hasClass(el, 'module')){
                  is = false;
              }
          }
          return is;
      },
      manageBreakables : function(curr, next, dir, event){
          var isnonbreakable = mw.wysiwyg.merge.isInNonbreakable(curr, dir);
             if(isnonbreakable){
                var conts = getSelection().getRangeAt(0);
                event.preventDefault();

              if(next !== null){

                if(next.nodeType === 3 && /\r|\n/.exec(next.nodeValue) !== null){
                    event.preventDefault();
                    return false;
                }

                if(dir == 'next'){
                  mw.wysiwyg.cursorToElement(next)
                }
                else{
                  mw.wysiwyg.cursorToElement(next, 'end')
                }
              }
              else{
                return false;
              }
          }
      },
      isInNonbreakable: function(el, dir){
          var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

          if(absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null){
            absNext = mw.wysiwyg.merge.findNextNearest(el, dir, true)
          }

          if(absNext.nodeType === 1){
            if(mw.tools.hasAnyOfClasses(absNext, ['nodrop', 'allow-drop'])){
              return false;
            }
            if(absNext.querySelector('.nodrop', '.allow-drop') !== null){
              return false;
            }
          }
          if( mw.wysiwyg.merge.alwaysMergable(absNext) && (mw.wysiwyg.merge.alwaysMergable(absNext.firstElementChild) || !absNext.firstElementChild) ){
            return false;
          }
      if(el.textContent == ''){

          var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
          if(absNext.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) !== null){
            return  mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
          }
      }

        if(el.nodeType === 1 && !!el.textContent.trim()){
          return false;
        }
        if(el.nextSibling === null && el.nodeType === 3 && dir =='next'){
          var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);
          var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
          if(/\r|\n/.exec(absNext.nodeValue) !== null){
            return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
          }

          if(absNextNext.nodeType === 1){
            return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext) || mw.wysiwyg.merge.isInNonbreakableClass(absNextNext.firstChild);
          }
           else if(absNextNext.nodeType === 3){
            return true
          }
          else{
            return false;
          }
        }

        if(el.previousSibling === null && el.nodeType === 3 && dir =='prev'){
          var absNext = mw.wysiwyg.merge.findNextNearest(el, 'prev');
          var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, 'prev');
          if(absNextNext.nodeType === 1){
            return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext);
          }
          else if(absNextNext.nodeType === 3){
            return true;
          }
          else{
            return false;
          }
        }
          el = mw.wysiwyg.validateCommonAncestorContainer(el)

          var is = mw.wysiwyg.merge.isInNonbreakableClass(el)
          return is;

      },
      isInNonbreakableClass : function(el, dir){
        var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

        if(el.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) === null) return false;
        el = mw.wysiwyg.validateCommonAncestorContainer(el)
          var classes = ['unbreakable', '*col', '*row', '*btn', '*icon', 'module', 'edit'];
          var is = false;
          classes.forEach(function(item){
             if(item.indexOf('*') === 0){
                 var item = item.split('*')[1];
                  if(el.className.indexOf(item) !== -1){
                      is = true;
                  }
                  else{
                      mw.tools.foreachParents(el, function(loop){
                          if(this.className.indexOf(item) !== -1 && !this.contains(el)){
                              is = true;
                              mw.tools.stopLoop(loop);
                          }
                          else{

                            is = false;
                            mw.tools.stopLoop(loop);
                          }
                      })
                  }
             }
             else{
              if(mw.tools.hasClass(el, item) || mw.tools.hasParentsWithClass(el, item) ){
                  is = true;
              }
             }
          });
          return is;
      },
      getNext : function(curr){
          var next = curr.nextSibling;
          while(curr !== null && curr.nextSibling === null){
              curr = curr.parentNode;
              next = curr.nextSibling;
          }
          return next;
      },
      getPrev : function(curr){
          var next = curr.previousSibling;
          while(curr !== null && curr.previousSibling === null){
              curr = curr.parentNode;
              next = curr.previousSibling;
          }
          return next;
      },
      findNextNearest : function(el, dir, searchElement){
        searchElement = typeof searchElement === 'undefined' ? false : true;
        if(dir =='next'){
          var dosearch = searchElement ? 'nextElementSibling' : 'nextSibling'
          var next = el[dosearch];
          if(next === null){
            while( el[dosearch] === null ){
              el = el.parentNode;
              next =  el[dosearch];

            }
          }
        }
        else{
          var dosearch = searchElement ? 'previousElementSibling' : 'previousSibling'
           var next = el[dosearch];
            if(next === null){
              while(el[dosearch] === null){
                el = el.parentNode;
                next = el[dosearch];

              }
            }
        }
        return next;
      },
      alwaysMergable : function(el){

        if(!el){
          return false;
        }
        if(el.nodeType === 3){
          return mw.wysiwyg.merge.alwaysMergable(mw.wysiwyg.validateCommonAncestorContainer(el))
        }
        if(el.nodeType === 1){
          if(/^(?:area|br|col|embed|hr|img|input|link|meta|param)$/i.test(el.tagName)){
            return true;
          }
          if(/^(?:strong|em|i|b|li)$/i.test(el.tagName)){
            return true;
          }
          if(/^(?:span)$/i.test(el.tagName) && !el.className){
            return true;
          }
        }

        if(mw.tools.hasClass(el, 'module')) return false;
        if(mw.tools.hasParentsWithClass(el, 'module')){
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
        for( ; i < selectors.length ; i++){
          var item = selectors[i];
          if(el.matches(item)){
            final = true;
            break;
          }
        }

        return final;

      }
    },
    init: function (selector) {

        var selector = selector || ".mw_editor_btn";
        var mw_editor_btns = mw.$(selector);
        mw_editor_btns.on("mouseup touchend mousedown touchstart", function (event) {
            if (mw.wysiwyg.editors_disabled) {
                return false;
            }
            event.preventDefault();

            if ((event.type == 'mouseup' || event.type == 'touchend') && !$(this).hasClass('disabled')) {
                var command = $(this).dataset('command');
                if (!command.contains('custom-')) {
                    mw.wysiwyg._do(command);
                }
                else {
                    var name = command.replace('custom-', "");
                    mw.wysiwyg[name]();
                }
                $(this).removeClass("mw_editor_btn_mousedown");
                 mw.wysiwyg.check_selection(event.target);

            }
            if (event.type == 'mousedown' && !$(this).hasClass('disabled')) {
                $(this).addClass("mw_editor_btn_mousedown");
            }
        });
        mw_editor_btns.hover(function () {
            $(this).addClass("mw_editor_btn_hover");
        }, function () {
            $(this).removeClass("mw_editor_btn_hover");
        });
        $(mwd.body).on('mouseup', function (event) {
            if (event.target.isContentEditable) {
                mw.wysiwyg.check_selection(event.target);
            }
        });
        $(mwd.body).on('noop keydown', function (event) {

            //var textnodes = ['br', 'p', 'b', 'span', 'i', 'em', 'small', 'del', 'ins', 'sub', 'sup','font'];
            //var blocknodes = ['section', 'div', 'table'];

            //var display_blocks = ['block', 'table', 'flexbox'];

            if ((event.keyCode == 46 || event.keyCode == 8) && event.type == 'keydown') {
                mw.tools.removeClass(mw.image_resizer, 'active');
                mw.wysiwyg.change('.element-current');

            }

            if (event.type == 'keydown') {

                if (mw.tools.isField(event.target) || !event.target.isContentEditable) {

                    return true;
                }
                var sel = window.getSelection();
                if (event.keyCode == 13) {

                  if(mw.tools.hasAnyOfClassesOnNodeOrParent(event.target, ['safe-mode'])){

                    var isList = mw.tools.firstMatchesOnNodeOrParent(event.target, ['li', 'ul', 'ol'])
                    if(!isList){
                      event.preventDefault(); 
                      if(event.shiftKey){
                        mw.wysiwyg.insert_html('<br>');
                      }
                      else{
                        mw.wysiwyg.insert_html('<br><br>');
                      }
                    }


                  }

                /*
                 mw.wysiwyg.checkForTextOnlyElements(event);
                 if(event.target.isContentEditable && !mw.tools.isField(event.target)){
                 var commonName = mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer).nodeName;
                 if(commonName!='P' && !event.ctrlKey && !event.shiftKey){
                 var id = 'temp'+mw.random();
                 mw.wysiwyg.insert_html('<b id="'+id+'">&nbsp;</b>');
                 var br = mwd.createElement('br');
                 br.id = id;
                 mw.$("#"+id).replaceWith(br);
                 mw.wysiwyg.cursorToElement(br, 'after');
                 event.preventDefault();
                 return false;
                 }
                 }  */
                }
                if (sel.rangeCount > 0) {
                    var r = sel.getRangeAt(0);
                    if (event.keyCode == 9 && !event.shiftKey && sel.focusNode.parentNode.iscontentEditable && sel.isCollapsed) {   /* tab key */
                        mw.wysiwyg.insert_html('&nbsp;&nbsp;&nbsp;&nbsp;');
                        return false;
                    }
                    if (event.keyCode == 46 || event.keyCode == 8) {

                        if (!mw.settings.liveEdit) {
                            return true;
                        }
                        var nextNode = null;

                        if (event.keyCode == 46 || event.keyCode == 8) {
                            if (event.keyCode == 46) {
                                var nextchar = sel.focusNode.textContent.charAt(sel.focusOffset);
                                var nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset + 1);
                                var nextel = sel.focusNode.nextSibling || sel.focusNode.nextElementSibling;

                            } else {
                                var nextchar = sel.focusNode.textContent.charAt(sel.focusOffset-1);
                                var nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset-2);
                                var nextel = sel.focusNode.previouSibling || sel.focusNode.previousElementSibling;

                            }



                            if((nextchar == ' ' || /\r|\n/.exec(nextchar) !== null) && sel.focusNode.nodeType === 3 && !nextnextchar ){
                              event.preventDefault()

                                return false;
                            }


                            if(nextnextchar == ''){

                               if(nextchar.replace(/\s/g,'') == '' && r.collapsed){
                                if(nextel && nextel.nodeName != 'BR'){
                                  event.preventDefault()
                                  return false;
                                }

                               }
                               else if((focus.previousElementSibling === null && rootfocus.previousElementSibling === null) && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])){
                                return false;
                               }
                               else{
                                   
                               }
                            }
                            if (nextchar == '' ) {
                                 

                                    //continue check nodes
                                    if (event.keyCode == 46) {
                                        nextNode = mw.wysiwyg.merge.getNext(sel.focusNode);

                                    }
                                    if (event.keyCode == 8) {
                                        nextNode = mw.wysiwyg.merge.getPrev(sel.focusNode);
                                    }
                                    if(mw.wysiwyg.merge.alwaysMergable(nextNode)){
                                      return true;
                                    }



                                    var nonbr = mw.wysiwyg.merge.isInNonbreakable(nextNode)
                                    if(nonbr){
                                      event.preventDefault();
                                      return false;
                                    }

                                    if(nextNode.nodeValue == ''){

                                    }

                                    if (nextNode !== null && mw.wysiwyg.merge.isMergeable(nextNode)) {

                                    if (event.keyCode == 46) {

                                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                                    }
                                    else{

                                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)

                                    }
                                    }
                                    else {
                                        event.preventDefault()
                                    }
                              //  }
                              if(nextNode === null){
                                  nextNode = sel.focusNode.parentNode.nextSibling;
                                  if(!mw.wysiwyg.merge.isMergeable(nextNode)){
                                      event.preventDefault();
                                  }
                                  if (event.keyCode == 46) {
                                    mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                                  }
                                  else{
                                    mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                                  }

                              }

                            } else {

                            }
                        }

                    }
                }
            }
        });
        mw.on.tripleClick(mwd.body, function (target) {
            mw.wysiwyg.select_all(target);
            if (mw.tools.hasParentsWithClass(target, 'element')) {
                //mw.wysiwyg.select_all(mw.tools.firstParentWithClass(target, 'element'));
            }
            var s = window.getSelection();
            var r = s.getRangeAt(0);
            var c = r.cloneContents();
            //var common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
            var common = r.commonAncestorContainer;
            if(common.nodeType === 1){
              if(mw.tools.hasClass(common, 'element')){
                mw.wysiwyg.select_all(common)
              }

            }
            else{
               common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
               if(mw.tools.hasClass(common, 'element')){
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
        $(mwd.body).keyup(function (e) {
            mw.smallEditorCanceled = true;
            mw.smallEditor.css({
                visibility: "hidden"
            });
            if (e.target.isContentEditable && !mw.tools.isField(e.target)) {
                mw.wysiwyg.change(e.target)
                mw.tools.addClass(this, 'isTyping');
                $(this._onCloneableControl).hide()
                if (mw.tools.isEmpty(e.target)) {
                    e.target.innerHTML = '&zwnj;&nbsp;';
                }
                if (e.keyCode == 13) {

                    mw.$(".element-current").removeClass("element-current");
                    var el = mwd.querySelectorAll('.edit .element'), l = el.length, i = 0;
                    for (; i < l; i++) {
                        if (el[i].id == '') {
                            el[i].id = mw.wysiwyg.createElementId();
                        }
                    }
                    e.preventDefault();
                    if (!e.shiftKey) {
                        var p = mw.wysiwyg.findTagAcrossSelection('p');
                    }
                    var newNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                    if(newNode.id){
                        newNode.id = mw.wysiwyg.createElementId(); 
                    }
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

            mw.wysiwyg.check_selection(e.target);

        });
    },
    createElementId : function(){
        return 'mw-element_' + mw.random();
    },
    change: function (el) {
        if (typeof el === 'string') {
            var el = mwd.querySelector(el);
        }
        var target = null;
        if (mw.tools.hasClass(el, 'edit')) {
            mw.tools.addClass(el, 'changed');
            var target = el;
            mw.trigger('editChanged', target)
        }
        else if (mw.tools.hasParentsWithClass(el, 'edit')) {
            var target = mw.tools.firstParentWithClass(el, 'edit');
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
        var el = mw.wysiwyg.validateCommonAncestorContainer(el);
        return el.isContentEditable;
    },
    cursorToElement: function (node, a) {
        if (node === null) {
            return false;
        }
        if (!node) {
            return false;
        }
        var a = a || 'start';
        var sel = mww.getSelection();
        var r = mwd.createRange();
        sel.removeAllRanges();
        if (a == 'start') {
            r.selectNodeContents(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if (a == 'end') {
            r.selectNodeContents(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if (a == 'before') {
            r.selectNode(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if (a == 'after') {
            r.selectNode(node);
            r.collapse(false);
            sel.addRange(r);
        }
    },
    applier: function (tag, classname, style_object) {
        var classname = classname || '';
        if (mw.wysiwyg.isSelectionEditable()) {
            var range = window.getSelection().getRangeAt(0);
            var selectionContents = range.extractContents();
            var el = mwd.createElement(tag);
            el.className = classname;
            typeof style_object !== 'undefined' ? $(el).css(style_object) : '';
            el.appendChild(selectionContents);
            range.insertNode(el);
            mw.wysiwyg.change(el);
            return el;
        }
    },
    external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        $(mw.wysiwyg.external).css({
            top: offset.top - $(window).scrollTop() + el.height(),
            left: offset.left
        });
        $(mw.wysiwyg.external).html("<iframe src='" + url + "' scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');
        frame.contentWindow.thisframe = frame;
    },
    getExternalData:function(url, cb){
      var has = mw.storage.get(url);
      if(has){
        cb.call(has,has)
      }
      else{
        $.get(url, function(data){
          mw.storage.set(url, data)
          cb.call(data,data)
        })
      }
    },
    todo_external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        $(mw.wysiwyg.external).css({
            top: offset.top - $(window).scrollTop() + el.height(),
            left: offset.left
        });
        $(mw.wysiwyg.external).html("<iframe scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');

        frame.contentWindow.thisframe = frame;
        if(url.indexOf('#') !== -1){
          frame.src = '#'+ url.split('#')[1]
        }

        mw.wysiwyg.getExternalData(url, function(html){

          frame.contentWindow.document.open();
          frame.contentWindow.document.write(html);
          frame.contentWindow.document.close();
        })
    },
    createelement: function () {
        var el = mw.wysiwyg.applier('div', 'mw_applier element');
    },
    fontcolorpicker: function () {
        var el = "#mw_editor_font_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontColor");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontbgcolorpicker: function () {
        var el = ".mw_editor_font_background_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontbg");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontColor: function (color) {
        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        if (color == 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "foreColor");
        } else {
            mw.wysiwyg.execCommand('forecolor', null, color);
        }
    },
    fontbg: function (color) {
        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        if (color == 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "backcolor");
        } else {
            mw.wysiwyg.execCommand('backcolor', null, color);
        }
    },
    request_change_bg_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_bg_color: function (color) {
        var color = color != 'transparent' ? '#' + color : color;
        mw.$(".element-current").css("backgroundColor", color);
        mw.wysiwyg.change('.element-current');
    },
    request_border_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_border_color: function (color) {
        if (color != "transparent") {
            mw.$(".element-current").css(mw.border_which + "Color", "#" + color);
            $(".ed_bordercolor_pick span").css("background", "#" + color);
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
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
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
        mw.wysiwyg.execCommand('fontname', null, font_name);
    },
    nestingFixes: function (root) {  /*
     var root = root || mwd.body;
     var all = root.querySelectorAll('.mw-span-font-size'),
     l = all.length,
     i=0;
     for( ; i<l; i++ ){
     var el = all[i];
     if(el.firstChild === el.lastChild && el.firstChild.nodeType !== 3){
     // $(el.firstChild).unwrap();
     }
     } */
    },
    lineHeight: function (a) {
      a = a || 'normal';
      a = (typeof a === 'number') ? (a+'px') : a;
      var r = getSelection().getRangeAt(0).commonAncestorContainer;
      var el = mw.wysiwyg.validateCommonAncestorContainer(r);
      r.style.fontSize = a;
      mw.wysiwyg.change(r);
    },
    fontSize: function (a) {
        if (window.getSelection().isCollapsed) {
            return false;
        }
        mw.wysiwyg.allStatements(function () {
            rangy.init();
            var clstemp = 'mw-font-size-' + mw.random();
            var classApplier = rangy.createCssClassApplier("mw-font-size " + clstemp, true);
            classApplier.applyToSelection();

            var icons = mw.wysiwyg.findTagAcrossSelection('i');
            if (icons != false) {
                mw.tools.addClass(icons, "mw-font-size " + clstemp);
            }

            var all = mwd.querySelectorAll('.' + clstemp),
                l = all.length,
                i = 0;
            for (; i < l; i++) {
                all[i].style.fontSize = a + 'px';
                mw.tools.removeClass(all[i], clstemp);
                mw.wysiwyg.change(all[i]);
            }
            if (icons != false) {
                //var empty_spans = $(icons).children('span.mw-font-size');
                //$(empty_spans).each(function (index) {
                //    if ($(this).html() == '&nbsp;') {
                //        $(this).remove()
                //    }
                //});
            }

            $('.edit .mw-font-size').removeClass('mw-font-size ')

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
        var css = mw.CSSParser(node);
        if (typeof css.get !== 'undefined') {
            var font = css.get.font();
            var family_array = font.family.split(',');
            if (family_array.length == 1) {
                var fam = font.family;

            } else {
                //var fam = mw.tools.getFirstEqualFromTwoArrays(family_array, mw.wysiwyg.editorFonts);
                var fam = family_array.shift();
            }

            var ddval = mw.$(".mw_dropdown_action_font_family");
            if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                mw.$(".mw_dropdown_action_font_family").each(function(){
                  $(this).setDropdownValue(fam);
                })
            }
        }
    },
    setActiveFontSize: function (node) {
        var size = Math.round(parseFloat(mw.CSSParser(node).get.font().size));
        var ddval = mw.$(".mw_dropdown_action_font_size");
        if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
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
    decorators:{
      b:'.mw_editor_bold',
      strong:'.mw_editor_bold',
      i:'.mw_editor_italic',
      em:'.mw_editor_italic',
      u:'.mw_editor_underline',
      s:'.mw_editor_strike',
      strike:'.mw_editor_strike'
    },
    setDecorators:function(sel){
      sel = sel || getSelection();
      var node = sel.focusNode;
      while(node.nodeName != 'DIV' && node.nodeName != 'BODY'){
        for(var x in mw.wysiwyg.decorators){
          if(node.nodeName.toLowerCase() == x){
            mw.$(mw.wysiwyg.decorators[x]).addClass('mw_editor_btn_active')
          }
        }
        node = node.parentNode;
      }
    },
    started_checking: false,
    check_selection: function (target) {
        var target = target || false;
        if (!mw.wysiwyg.started_checking) {
            mw.wysiwyg.started_checking = true;

            var selection = window.getSelection();

            if (selection.rangeCount > 0) {
                mw.wysiwyg.resetActiveButtons();
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
                        mw.wysiwyg.setActiveButtons(children[i]);
                    }
                    mw.wysiwyg.setActiveFontSize(common);
                }
                else {
                    if (typeof common.parentElement !== 'undefined' && common.parentElement !== null) {
                        var align = mw.CSSParser(common.parentElement).get.alignNormalize();
                        mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                        mw.$(".mw-align-" + align).addClass('mw_editor_btn_active');
                        mw.wysiwyg.setActiveButtons(common.parentElement);
                        mw.wysiwyg.setActiveFontSize(common.parentElement);
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
                mw.wysiwyg.setDecorators(selection)
            }

            if (!!target) {
                mw.wysiwyg.setActiveButtons(target);
                if (target.tagName == 'A') {
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
    link: function (prepolulate, node_id) {
        // mw.wysiwyg.save_selection();
        var modal = mw.tools.modal.frame({
            url: "rte_link_editor",
            title: "Edit link",
            name: "mw_rte_link",
            template: 'basic',
            width: 500,
            height: 300
        });
        mw.$('iframe', modal.main).on('change', function (a, b, c, e) {
            mw.iframecallbacks[b](c, e);
        });

        var link = false;
        if (typeof(prepolulate) != 'undefined') {
            link = prepolulate;
        }

        if (!!link) {
            modal.main.find("iframe").load(function () {
                $(this).contents().find("#customweburl").val(link);
                if (typeof(node_id) != 'undefined') {
                    var link_text_value = $('#' + node_id).text();
                    link_text_value = $.trim(link_text_value);
                    if (link_text_value != '') {
                        $(this).contents().find("#customweburl_text").val(link_text_value);
                        $(this).contents().find("#customweburl_text_field_holder").show();
                    }
                }

            })
        }
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
            return parent
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
        var types = types || false;
        if (hash == '#editimage') {
            var types = 'images';
        }
        var url = !!types ? "rte_image_editor?types=" + types + '' + hash : "rte_image_editor" + hash;

        var url = mw.settings.site_url + 'editor_tools/' + url;
        var modal = mw.tools.modal.frame({
            url: url,
            name: "mw_rte_image",
            width: 430,
            height: 230,
            template: 'mw_modal_basic',
            overlay: true
        });
        modal.overlay.style.backgroundColor = 'white';
    },
    media: function (hash) {
        if (mw.settings.liveEdit && typeof mw.target.item === 'undefined') return false;
        var hash = hash || '#insert_html';
        console.log(hash, $("#mw_rte_image").length)
        if ($("#mw_rte_image").length > 0) {
            $("#mw_rte_image").remove();
        }
        else {
            if (mw.wysiwyg.isSelectionEditable() || mw.$(mw.target.item).hasClass("image_change") || mw.$(mw.target.item.parentNode).hasClass("image_change") || mw.target.item === mw.image_resizer) {
                mw.wysiwyg.save_selection();
                mw.wysiwyg.request_media(hash);
                $(".mw_overlay").on('mousedown touchstart', function(e){
                  e.preventDefault()
                  e.stopPropagation()
                })
            }
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
        if (typeof html === 'string') {
            var isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
        }
        else {
            var isembed = false;
        }
        if (isembed) {
            var id = 'frame-' + mw.random();
            var frame = html;
            var html = '<span id="' + id + '"></span>';
        }
        if (!!window.MSStream) {
            mw.wysiwyg.restore_selection();
            if (mw.wysiwyg.isSelectionEditable()) {
                var range = window.getSelection().getRangeAt(0);
                var el = mwd.createElement('span');
                el.innerHTML = html;
                range.insertNode(el);
                $(el).replaceWith(el.innerHTML);
            }
        }
        else {
            if (!document.selection) {
                mw.wysiwyg.execCommand('inserthtml', false, html);
            }
            else {
                document.selection.createRange().pasteHTML(html)
            }
        }
        if (isembed) {
            var el = mwd.getElementById(id);
            el.parentNode.contentEditable = false;
            $(el).replaceWith(frame);
        }
        mw.wysiwyg.change(mw.wysiwyg.validateCommonAncestorContainer(window.getSelection().getRangeAt(0).commonAncestorContainer));
    },
    selection_length: function () {
        var n = window.getSelection().getRangeAt(0).cloneContents().childNodes,
            l = n.length,
            i = 0;
        var final = 0;
        for (; i < l; i++) {
            var item = n[i];
            if (item.nodeType === 1) {
                var final = final + item.textContent.length;
            }
            else if (item.nodeType === 3) {
                var final = final + item.nodeValue.length;
            }
        }
        return final;
    },
    insert_image: function (url) {
        var id = 'image_' + mw.random();
        var img = '<img id="' + id + '" contentEditable="true" class="element" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        mw.settings.liveEdit ? mw.$("#" + id).attr("contenteditable", false) : '';
        mw.$("#" + id).removeAttr("_moz_dirty");
        mw.wysiwyg.save_selection();
        mw.wysiwyg.change(mwd.getElementById(id));
        return id;
    },
    save_selection: function (a) {
        var a = a || false;
        var selection = window.getSelection();
        if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);
        }
        else {
            var range = mwd.createRange();
            range.selectNode(mwd.querySelector('.edit .element'));
        }
        mw.wysiwyg.selection = {}
        mw.wysiwyg.selection.sel = selection;
        mw.wysiwyg.selection.range = range;
        mw.wysiwyg.selection.element = $(mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer));
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
    format: function (command) {
              var el = mw.wysiwyg.validateCommonAncestorContainer(window.getSelection().focusNode);
        classApplier.forEach(function(c){
          mw.tools.removeClass(el,c)
        })
      if(command.indexOf('.') === 0){
        var cls = command.split('.')[1];

        mw.tools.addClass(el,cls)
        /*rangy.init();
        var classApplier = rangy.createCssClassApplier(cls, true);
        classApplier.applyToSelection(); */
      }
      else{
        if (!window.MSStream) {
            if (command == 'code_text') {
                mw.wysiwyg.execCommand("insertHTML", false, "<code>" + document.getSelection() + "</code>");
            } else {
                mw.wysiwyg.execCommand('FormatBlock', false, '<' + command + '>');
            }
        }
        else {
            var sel = window.getSelection();
            if (mw.wysiwyg.isSelectionEditable()) {
                var c = mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer);
                mw.tools.setTag(c, command);
                mw.wysiwyg.change(c)
            }
        }
      }

    },
    _undo: false,
    _redo: false,
    undoredo: false,
    undoRedoFixes: function () {
        mw.wysiwyg.undoredo = true;
        mw.askusertostay = true;
        var curr = mw.historyActive;
        var len = mw.tools.objLenght(mw.undoHistory);
        if (typeof mw.undoHistory[curr] === 'undefined' && curr > 0) {
            mw.$(".mw_editor_undo").addClass("disabled");
            mw.$(".mw_editor_redo").removeClass("disabled");
        }
        if (typeof mw.undoHistory[curr] === 'undefined' && curr < 0) {
            mw.$(".mw_editor_undo").removeClass("disabled");
            mw.$(".mw_editor_redo").addClass("disabled");
        }
        if (typeof mw.undoHistory[curr] !== 'undefined' && curr > 0 && curr < len) {
            mw.$(".mw_editor_undo").removeClass("disabled");
            mw.$(".mw_editor_redo").removeClass("disabled");
        }
    },
    historyUndo: function () {
        mw.wysiwyg.undoredo = true;
        mw.askusertostay = true;
        if (typeof mw.undoHistory === 'object') {
            var len = mw.tools.objLenght(mw.undoHistory);
            if (len > 0) {
                var active = mw.historyActive++;
            }
            mw.history.load(mw.undoHistory[active]);
            mw.wysiwyg.undoRedoFixes();
        }
    },
    historyRedo: function () {
        if (typeof mw.undoHistory === 'object') {
            var len = mw.tools.objLenght(mw.undoHistory);
            if (len > 0) {
                var active = mw.historyActive--;
            }
            mw.history.load(mw.undoHistory[active]);
            mw.wysiwyg.undoRedoFixes();
        }
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

        $(".mw_dropdown_action_font_family").off('change');
        $(".mw_dropdown_action_font_family").on('change', function () {
            var val = $(this).getDropdownValue();
            mw.wysiwyg.fontFamily(val);
        });
        $(".mw_dropdown_action_font_family").each(function () {
            mw.$("[value]", $(this)).on('mousedown touchstart', function (event) {
                $(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                return false;
            });
        });
    },

    initFontFamilies: function () {
        if (window.getComputedStyle(mwd.body) == null) {
            return;
        }

        var body_font = window.getComputedStyle(mwd.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
        if (mw.wysiwyg.fontFamilies.indexOf(body_font) === -1) {
            mw.wysiwyg.fontFamilies.push(body_font);
        }

        var scan_for_fonts = ['h1', 'h2', 'h3', 'h4', 'h5', 'p', 'a[class]'];

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
    fontIconFamilies: ['fa', 'mw-ui-icon', 'mw-icon', 'material-icons', 'mw-wysiwyg-custom-icon', 'icon'],

    elementHasFontIconClass: function (el) {
        var icon_classes = mw.wysiwyg.fontIconFamilies;
        if (el.tagName == 'I' || el.tagName == 'SPAN') {
            if (mw.tools.hasAnyOfClasses(el, icon_classes)) {
                return true;
            }
            else {
              var p = mw.tools.firstParentOrCurrentWithAnyOfClasses(el.parentNode, icon_classes);
              if(p){
                return p;
              }
            }
        }
    },
    firstElementThatHasFontIconClass: function (el) {
      var icon_classes = mw.wysiwyg.fontIconFamilies;
      var p = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, icon_classes);
      if(p && (p.tagName == 'I' || p.tagName == 'SPAN')){
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
        var id = $(textarea).attr("id");
        $("#iframe_editor_" + id).remove();
        var url = iframe_url;
        var iframe = mwd.createElement('iframe');
        iframe.className = 'mw-editor-iframe-loading';
        iframe.id = "iframe_editor_" + id;
        iframe.width = $(textarea).width();
        iframe.height = $(textarea).height();
        iframe.scrolling = "no";
        iframe.setAttribute('frameborder', 0);
        iframe.src = url;
        iframe.style.resize = 'vertical';
        iframe.onload = function () {
            iframe.className = 'mw-editor-iframe-loaded';
            var b = $(this).contents().find(".edit");
            var b = $(this).contents().find("[field='content']")[0];
            if (typeof b != 'undefined' && b !== null) {
                b.contentEditable = true;
                $(b).on("blur keyup", function () {
                    textarea.value = $(this).html();
                });
                if (!!content_to_set) {
                    $(b).html(content_to_set);
                }
                mw.on.DOMChange(b, function () {
                    textarea.value = $(this).html();
                    mw.askusertostay = true;
                });
            }
        }
        $(textarea).after(iframe);
        $(textarea).hide();
        return iframe;
    },
    word_listitem_get_level:function(item){
      var msspl = item.getAttribute('style').split('mso-list');
      if(msspl.length > 1){
        var mssplitems = msspl[1].split(' ');
        for(var i=0; i<mssplitems.length; i++){
          if(mssplitems[i].indexOf('level') !== -1){
            return  parseInt(mssplitems[i].split('level')[1], 10);
          }
        }
      }
    },

    word_list_build:function(lists, count){
      var i, count = count || 0, check = false, max = 0;
      if(count === 0){
        for( i in lists ){
          var curr = lists[i];
          if(!curr.nodeName || curr.nodeType !== 1) continue;
          var $curr = $(curr);
          lists[i] = mw.tools.setTag(curr, 'li');
        }
      }

      lists.each(function(){
       var num = this.innerText.trim().split('.')[0], check=parseInt(num, 10);
        var curr = $(this);
        if(!curr.attr('data-type')){
          if(!isNaN(check) && num>0){
            this.innerHTML = this.innerHTML.replace(num+'.', '');
            curr.attr('data-type', 'ol');
          }
          else{
            curr.attr('data-type', 'ul');
          }
        }
        if(!this.__done){
          this.__done = false;
          var level = parseInt($(this).attr('data-level'));
          if(!isNaN(level) && level>max){
            max = level;
          }
          if(!isNaN(level)&&level>1){
            var prev = this.previousElementSibling;
            if(!!prev && prev.nodeName == 'LI'){
              var type = this.getAttribute('data-type');
              var wrap = document.createElement(type=='ul'?'ul':'ol');
              wrap.setAttribute('data-level', level)
              $(wrap).append(this);
              $(wrap).appendTo(prev);
              check = true;
            }
            else if(!!prev && (prev.nodeName == 'UL'||prev.nodeName == 'OL')){
              var where = $('li[data-level="'+level+'"]', prev) ;
              if(where.length>0){
                where.after(this);
                check = true;
              }
              else{
                var type = this.getAttribute('data-type');
                var wrap = document.createElement(type=='ul'?'ul':'ol');
                wrap.setAttribute('data-level', level)
                $(wrap).append(this);
                $(wrap).appendTo($('li:last', prev))
                check = true;
              }
            }
            else if(!prev && (this.parentNode.nodeName != 'UL' && this.parentNode.nodeName != 'OL')){
              var $curr = $([this]), curr = this;
              while($(curr).next('li[data-level="'+level+'"]').length > 0){
                 $curr.push($(curr).next('li[data-level="'+level+'"]')[0]);
                 curr = $(curr).next('li[data-level="'+level+'"]')[0];
              }
              $curr.wrapAll($curr.eq(0).attr('data-type') == 'ul' ? '<ul></ul>':'<ol></ol>')
              check = true;
            }
          }
        }
      })

      $("ul[data-level!='1'], ol[data-level!='1']").each(function(){
        var level = parseInt($(this).attr('data-level'));
        if(!!this.previousElementSibling){
          var plevel = parseInt($(this.previousElementSibling).attr('data-level'));
          if(level > plevel){
            $('li:last', this.previousElementSibling).append(this)
            check = true;
          }
        }
      })
      if(count===0){
        setTimeout(function(){
          mw.wysiwyg.word_list_build($('li[data-level]'), 1);
          mw.wysiwyg.wrap_li_roots()
        }, 1)
      }
      return lists;
    },
    wrap_li_roots:function(){
      var all = document.querySelectorAll('li[data-level]'), i = 0, has = false;
      for( ; i<all.length ; i++){
        var parent = all[i].parentElement.nodeName;
        if(parent != 'OL' && parent != 'UL'){
          has = true;
          var group = $([]), curr = all[i];
          while(!!curr && curr.nodeName == 'LI'){
            group.push(curr);
            curr = curr.nextElementSibling;
          }
          var el = mwd.createElement(all[i].getAttribute('data-type') == 'ul' ? 'ul':'ol');
          el.className = 'element';
          group.wrapAll(el)
          break;
        }
      }
      if(has) return mw.wysiwyg.wrap_li_roots()
    },
    isWordHtml:function(html){
      return html.indexOf('urn:schemas-microsoft-com:office:word') !== -1;
    },
    bgQuotesFix:function(el){
      el = $(el)[0];
      if(!!el && el.nodeType === 1){
        var first = el.outerHTML.split('>')[0];
        if( el.style.backgroundImage.indexOf('"') !== -1 && first.indexOf('style="') !== -1 ){
          el.attributes.style.nodeValue = el.attributes.style.nodeValue.replace(/\"/g, "'")
        }
      }
    },
    clean_word_list: function (html) {

      if(!mw.wysiwyg.isWordHtml(html)) return html;
      if(html.indexOf('</body>') != -1){
        html = html.split('</body>')[0]
      }
      var parser = mw.tools.parseHtml(html).body;

      var lists = mw.$('[style*="mso-list:"]', parser);
      lists.each(function(){
        var level = mw.wysiwyg.word_listitem_get_level(this);
        if(!!level){
          this.setAttribute('data-level', level)
          this.setAttribute('class', 'level-'+level)
        }

      });

      mw.$('[style]', parser).removeAttr('style');

      if(lists.length > 0){
        lists = mw.wysiwyg.word_list_build(lists);
        var start = $([]) ;
        mw.$('li', parser).each(function(){
          this.innerHTML = this.innerHTML
            .replace(/�/g, '')/* Not a dot */
            .replace(new RegExp(String.fromCharCode(160), "g"), "")
            .replace(/&nbsp;/gi, '')
            .replace(/\�/g,'')
            .replace(/<\/?span[^>]*>/g,"")
            .replace('�','');
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
            $(all[i]).remove();
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
                        $(item).remove();
                    }
                }
            }
        }
    },
    cleanHTML: function (root) {
        var root = root || mwd.body;
        mw.tools.foreachChildren(root, function () {
            if (mw.wysiwyg.hasContentFromWord(this)) {
                this.innerHTML = mw.wysiwyg.clean_word(this.innerHTML);
            }
            mw.wysiwyg.cleanTables(this);
        });
    },
    normalizeBase64Image: function (node, callback) {
        if (typeof node.src !== 'undefined' && node.src.indexOf('data:image/') === 0) {
            var type = node.src.split('/')[1].split(';')[0];
            var obj = {
                file: node.src,
                name: mw.random().toString(36) + "." + type
            }
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                var data = $.parseJSON(data);
                node.src = data.src;
                if (typeof callback === 'function') {
                    callback.call(node);
                }
                mw.wysiwyg.change(node);
                mw.trigger('imageSrcChanged', [node, node.src])
            });
        }
    },
    normalizeBase64Images: function (root) {
        var root = root || mwd.body;
        var all = root.querySelectorAll(".edit img[src*='data:image/']"), l = all.length, i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                mw.tools.addClass(all[i], 'element');
                mw.wysiwyg.normalizeBase64Image(all[i]);
            }
        }
    }
}
mw.disable_selection = function (element) {
    var el = element || ".module";
    var el = $(el, ".edit").not(".unselectable");
    el.attr("unselectable", "on");
    el.addClass("unselectable");
    el.on("selectstart", function (event) {
        event.preventDefault();
        return false;
    });
}
$(mwd).ready(function () {
    //$(".mw_dropdown_action_font_family").on('change', function(){
    //    var val = $(this).getDropdownValue();
    //    mw.wysiwyg.fontFamily(val);
    //});

    mw.wysiwyg.initClassApplier()

    mw.$(".mw_dropdown_action_font_size").change(function () {
        var val = $(this).getDropdownValue();
        mw.wysiwyg.fontSize(val);
        mw.$('.mw-dropdown-val', this).append('px');
    });
    mw.$(".mw_dropdown_action_format").change(function () {
        var val = $(this).getDropdownValue();
        mw.wysiwyg.format(val);
    });
    mw.wysiwyg.nceui();
    mw.smallEditor = mw.$("#mw_small_editor");
    mw.smallEditorCanceled = true;
    mw.bigEditor = mw.$("#mw-text-editor");
    $(mwd.body).mousedown(function (event) {
        var target = event.target;
        if ($(target).hasClass("element")) {
            mw.trigger("ElementMouseDown", target);
        }
        else if ($(target).parents(".element").length > 0) {
            mw.trigger("ElementMouseDown", $(target).parents(".element")[0]);
        }
        if ($(target).hasClass("edit")) {
            mw.trigger("EditMouseDown", [target, target, event]);
        }
        else if ($(target).parents(".edit").length > 0) {
            mw.trigger("EditMouseDown", [$(target).parents(".edit")[0], target, event]);
        }
        var hp = mwd.getElementById('mw-history-panel');
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

mw.wysiwyg.initFontSelectorBox();

  /*mw.$(".edit").on('paste', function(e){
    mw.wysiwyg.paste(e)
  }) */

    $(this).on('imageSrcChanged', function (e, el, url) {
        if ($(el).parent().hasClass('mw-image-holder')) {
            var url = mw.files.safeFilename(url)
            $(el).parent().css('backgroundImage', 'url(' + url + ')')
        }
    })
    mw.$("#wysiwyg_insert").on("change", function () {
        var fnode = window.getSelection().focusNode;
        var isPlain = mw.tools.hasClass(fnode, 'plain-text') || mw.tools.hasClass(fnode.parentNode, 'plain-text')
        if (mw.wysiwyg.isSelectionEditable()) {
            var val = $(this).getDropdownValue();

            var isTextlike = val == 'icon';
            if (!isTextlike && isPlain) {
                return false;
            }

            if (val == 'hr') {
                mw.wysiwyg._do('InsertHorizontalRule');
            }
            else if (val == 'box') {

                var div = mw.wysiwyg.applier('div', 'mw-ui-box mw-ui-box-content element');
                if (mw.wysiwyg.selection_length() <= 2) {
                    $(div).append("<p>&nbsp;</p>");
                }
            }
            else if (val == 'pre') {
                var div = mw.wysiwyg.applier('pre', '');
                if (mw.wysiwyg.selection_length() <= 2) {
                    $(div).append("&nbsp;");
                }
            } else if (val == 'code') {
                // var div = mw.wysiwyg.applier('code', '');
                var new_insert_html = prompt("Paste your code");
                if (new_insert_html != null) {
                    var div = mw.wysiwyg.applier('code');
                    div.innerHTML = new_insert_html;
                }
            } else if (val == 'insert_html') {
                var new_insert_html = prompt("Paste your html code in the box");
                if (new_insert_html != null) {
                    var div = mw.wysiwyg.applier('div');
                    div.innerHTML = new_insert_html;
                }
            } else if (val == 'icon') {
                var new_insert_html = '';
                if (new_insert_html != null) {
                    var div = mw.wysiwyg.applier('i');
                    div.innerHTML = new_insert_html;
                    div.className = "mw-icon mw-icon-mw"
                }
            }
            else if (val === 'table') {
                var el = mw.wysiwyg.applier('div', 'element', {width: "100%"});
                //el.innerHTML = '<table class="mw-wysiwyg-table"><tbody><tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr><tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr></tbody></table>';
                el.innerHTML = '<table class="mw-wysiwyg-table"><tbody><tr><td>Lorem Ipsum</td><td  >Lorem Ipsum</td></tr><tr><td  >Lorem Ipsum</td><td  >Lorem Ipsum</td></tr></tbody></table>';

                el.querySelector('table').setAttribute('onclick', 'mw.inline.tableController(this, event);');
            }
            else if (val === 'quote') {
                var div = mw.wysiwyg.applier('blockquote', 'element');
                $(div).append("<cite>By Lorem Ipsum</cite>");
            }
            //  $(this).setDropdownValue("Insert", true, true, "Insert");
        }
    });

    $(window).on("keydown mousedown mouseup", function (e) {

        if (e.type == 'keydown') {
            var isPlain = mw.tools.hasClass(e.target, 'plain-text');
            if (e.keyCode == 13) {
                var field = mw.tools.mwattr(e.target, 'field');
                if (field == 'title' || isPlain) {
                    e.preventDefault();
                }
            }
            if (e.ctrlKey) {
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
    var nodes = mwd.querySelectorAll(".edit"), l = nodes.length, i = 0;
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
});
mw.linkTip = {
    init: function (root) {
        if (root === null || !root) {
            return false;
        }
        mw.$(root).on('mousedown', function (e) {
            var node = mw.linkTip.find(e.target);
            if (!!node) {
                mw.linkTip.tip(node);
            }
            else {
                mw.$('.mw-link-tip').hide();
            }
        });
    },
    find: function (target) {
      var module = mw.tools.firstMatchesOnNodeOrParent(target, ['.module']);
        if (mw.tools.hasClass(target, 'module')) {
            return ;
        }
        else if (mw.tools.hasParentsWithClass(target, 'module')) {
          var po = mw.tools.parentsOrder(target, ['edit', 'module'])
          if(po.edit === -1 ) return;
          if(po.edit > po.module ) return;
        }
        if (target.nodeName === 'A') {
            return target;
        }
        else if (mw.tools.hasParentsWithTag(target, 'a')) {
            return mw.tools.firstParentWithTag(target, 'a');
        }
        else {
            return undefined;
        }
    },
    tip: function (node) {

        var link_id = null;
        if (typeof(node) == 'object' && (typeof(node.id) == null) || node.id == '') {
            link_id = 'mw-link-id-' + mw.random()
            node.id = link_id;
        }
        if (typeof(node) == 'object' && (typeof(node.id) != null) || node.id != '') {
            var link_id = node.id;
        }
        if (!mw.linkTip._tip) {

            var href_target = '_self';

            if (node.href.indexOf(location.host) !== -1) {
                //different page
                var href_target = '_blank';
            }

            var content = '<a href="' + node.href + '" target="' + href_target + '"  edit-id="' + link_id + '" class="mw-link-tip-link">' + node.href + '</a><span>-</span><a edit-href="' + node.href + '" edit-id="' + link_id + '" href="javascript:;" class="mw-link-tip-edit">Edit</a>';
            mw.linkTip._tip = mw.tooltip({content: content, position: 'bottom-center', skin: 'dark', element: node});
            $(mw.linkTip._tip).addClass('mw-link-tip');
            mw.$('.mw-link-tip-edit, .mw-link-tip-link-edit').click(function () {
                var prepolulate = '';
                var node_id = null;
                if ($(this).hasClass('mw-link-tip-edit')) {
                    prepolulate = $(this).attr('edit-href');
                    node_id = $(this).attr('edit-id');
                } else {
                    prepolulate = $(this).attr('href');
                    node_id = $(this).attr('edit-id');

                }

                mw.wysiwyg.link(prepolulate, node_id);
                mw.$('.mw-link-tip').hide();
                return false;
            });
        }
        else {
            mw.$('.mw-link-tip-link', mw.linkTip._tip).attr('href', node.href).html(node.href);
            mw.$('.mw-link-tip-link', mw.linkTip._tip).attr('edit-id', link_id);
            mw.$('.mw-link-tip-edit', mw.linkTip._tip).attr('edit-href', node.href);
            mw.$('.mw-link-tip-edit', mw.linkTip._tip).attr('edit-id', link_id);
            mw.tools.tooltip.setPosition(mw.linkTip._tip, node, 'bottom-center');
            mw.$('.mw-link-tip').show();
        }
    }
}

