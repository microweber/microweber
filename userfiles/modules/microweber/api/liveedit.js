mw.require('wysiwyg.js');
mw.require('handles.js');



mw.require('padding.js');
mw.require('source-edit.js');
mw.require('control_box.js');
mw.require('element_analyzer.js');
mw.require('liveedit_elements.js');
mw.require('live_edit.js');
mw.require('liveedit_widgets.js');
mw.require('state.js');
mw.require('selector.js');
mw.isDrag = false;
mw.resizable_row_width = false;
mw.mouse_over_handle = false;
mw.external_content_dragged = false;

mw.have_new_items = false;

mw.dragCurrent = null;
mw.currentDragMouseOver = null;
mw.liveEditSelectMode = 'element';

mw.modulesClickInsert = true;

mw.mouseDownOnEditor = false;
mw.mouseDownStarted = false;
mw.SmallEditorIsDragging = false;

mw.states = {};
mw.live_edit_module_settings_array = [];

mw.noEditModules = [
    '[type="template_settings"]'
];

mw.liveEditData = {
    move: {}
};



!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);



$(document).ready(function() {
    mw.liveEditSelector = new mw.Selector({
        root: document.body,
        autoSelect: false
    });

    // setInterval(function(){
    //     mw.liveEditSelector.positionSelected();
    // }, 700);

    $(document.body).on('mousemove', function(e){
        mw.liveEditData.move.hasLayout = !!mw.tools.firstMatchesOnNodeOrParent(e.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
        mw.liveEditData.move.hasModuleOrElement = mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['module', 'element']);
        if(mw.liveEditSelector.interactors.active) {
            if( !mw.liveEditData.move.hasModuleOrElement ){
                mw.liveEditSelector.hideItem(mw.liveEditSelector.interactors);
            }
        }
    });

    mw.on('ElementOver ModuleOver', function(e, target){

        if(target.id){
            mw.liveEditSelector.active(true);
            mw.liveEditSelector.setItem(target, mw.liveEditSelector.interactors);
        }

    });

    mw.on("ImageClick ElementClick ModuleClick", function(e, el, originalEvent){
        if(originalEvent) {
            el = mw.tools.firstParentOrCurrentWithAnyOfClasses(originalEvent.target, ['element', 'module'])
        }
        if(el.id) {
            if(mw.liveEditSelector.selected && mw.liveEditSelector.selected[0] === el) {
                return;
            }
            mw.liveEditSelector.select(el);
        }
    });

    if (("ontouchstart" in document.documentElement)) {
        $('body').addClass('touchscreen-device');
    }

    $("#live-edit-dropdown-actions-content a").off('click');

    $(document).on('mousedown touchstart', function(e){
      if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-defaults', 'edit', 'element'])){
        $(".element-current").removeClass("element-current");
      }
    });

    mw.$("span.edit:not('.nodrop')").each(function(){
      mw.tools.setTag(this, 'div');
    });


    mw.$("#toolbar-template-settings").click(function() {
        mw.tools.toggle_template_settings();
    });

    mw.$("#mw-toolbar-css-editor-btn").click(function() {
        mw.tools.open_custom_css_editor();
    });
    mw.$("#mw-toolbar-html-editor-btn").click(function() {
        mw.tools.open_custom_html_editor();
    });

    mw.$("#mw-toolbar-reset-content-editor-btn").click(function() {
        mw.tools.open_reset_content_editor();
    });


    mw.drag.create();

    $(mwd.body).on('keyup', function(e) {
        mw.$(".mw_master_handle").css({
            left: "",
            top: ""
        });
        mw.on.stopWriting(e.target, function() {
            if (mw.tools.hasClass(e.target, 'edit') || mw.tools.hasParentsWithClass(this, 'edit')) {
                mw.liveEditState.record({
                    target:e.target,
                    value:e.target.innerHTML
                });
                mw.drag.saveDraft();
            }
        });
    });

    $(mwd.body).on("keydown", function(e) {

        if (e.keyCode == 83 && e.ctrlKey) {

            if (e.altKey) {
                return;
            }

            if (typeof(mw.settings.live_edit_disable_keyboard_shortcuts) != 'undefined') {
                if (mw.settings.live_edit_disable_keyboard_shortcuts === true) {
                    return;
                }
            }
            mw.event.cancel(e, true);
            mw.drag.save();
        }
    });

     mw.edits = mw.$('.edit');
     mw.edits.on('keydown', function(e){
        var istab = (e.which || e.keyCode) == 9,
            isShiftTab = istab && e.shiftKey,
            tabOnly = istab && !e.shiftKey,
            target;

        if(istab){
            e.preventDefault();
            target = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
        }
        if(tabOnly){
            if(target.nodeName === 'LI'){
                var parent = target.parentNode;
                if(parent.children[0] !== target){
                    var prev = target.previousElementSibling;
                    var ul = document.createElement(parent.nodeName);
                    ul.appendChild(target);
                    prev.appendChild(ul)
                }
            }
            else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                nexttd = target.nextElementSibling;
                if(!!nexttd){
                    mw.wysiwyg.cursorToElement(nexttd, 'start');
                }
                else{
                    var nextRow = target.parentNode.nextElementSibling;
                    if(!!nextRow){
                        mw.wysiwyg.cursorToElement(nextRow.querySelector('td'), 'start');
                    }
                }
            }
            else{
                mw.wysiwyg.insert_html('&nbsp;&nbsp;');
            }

        }
        else if(isShiftTab){
            if(target.nodeName === 'LI'){
                var parent = target.parentNode;
                var isSub = parent.parentNode.nodeName === 'LI';
                if(isSub){
                   var split = mw.wysiwyg.listSplit(parent, $('li', parent).index(target));

                   var parentLi = parent.parentNode;
                   $(parentLi).after(split.middle);
                   if(!!split.top){
                        $(parentLi).append(split.top);
                   }
                   if(!!split.bottom){
                        $(split.middle).append(split.bottom);
                   }

                   $(parent).remove();
                }
            }
            else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                var target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                var nexttd = target.previousElementSibling;
                if(!!nexttd){
                    mw.wysiwyg.cursorToElement(nexttd, 'start');
                }
                else{
                    var nextRow = target.parentNode.previousElementSibling;
                    if(!!nextRow){
                        mw.wysiwyg.cursorToElement(nextRow.querySelector('td:last-child'), 'start');
                    }
                }
            }
            else{
                var range = getSelection().getRangeAt(0);
                clone = range.cloneRange();
                clone.setStart(range.startContainer, range.startOffset - 2);
                clone.setEnd(range.startContainer, range.startOffset);
                var nv = clone.cloneContents().firstChild.nodeValue;
                var nvcheck = nv.replace(/\s/g,'');
                if( nvcheck === '' ){
                    clone.deleteContents();
                }
            }
        }
    });

    mw.dragSTOPCheck = false;

    mw.on("DragHoverOnEmpty", function(e, el) {
        if ($.browser.webkit) {
            var _el = $(el);
            _el.addClass("hover");
            if (!_el.hasClass("mw-webkit-drag-hover-binded")) {
                _el.addClass("mw-webkit-drag-hover-binded");
                _el.mouseleave(function() {
                    _el.removeClass("hover");
                });
            }
        }
    });
    mw.on("IconElementClick", function(e, el) {
        mw.iconSelector._activeElement = el;
        mw.iconSelector.settingsUI();
    });

    mw.on("ComponentClick", function(e, node, type){

        if(type === 'icon'){
            mw.iconSelector._activeElement = node;
            mw.iconSelector.uiHTML();
            mw.iconSelector.settingsUI();
            return;

        }
        if(mw.settings.live_edit_open_module_settings_in_sidebar) {


            mw.log('ComponentClick' + type);
            if (!mw.liveEditSettings) {
                return; // admin mode
            }
            var uitype = type;
            if (type === 'element') {
                uitype = 'none';
            }
            if (type === 'safe-element') {
                //uitype = 'element' ;
                uitype = 'none';
            }
            if (node.nodeName === 'IMG') {
                uitype = 'image';
            }

            if (mw.liveEditSettings.active) {
                if (typeof(mw.sidebarSettingsTabs) != 'undefined') {
                    if (uitype !== 'module') {
                        mw.sidebarSettingsTabs.setLastClicked();
                    } else {
                        mw.sidebarSettingsTabs.set(2);
                    }
                }
                 mw.liveNodeSettings.set(uitype, node);
            }

        }
    });

    mw.on("ElementClick", function(e, el, c) {

        $(".element-current").not(el).removeClass('element-current');
        if (mw.liveEditSelectMode === 'element') {
            $(el).addClass('element-current');

            if(mw.drag.target.canBeEditable(el)){
                mw.wysiwyg.contentEditable(el, true)
            }
        }

        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false)
        });
    });
    mw.on("PlainTextClick", function(e, el) {
        mw.wysiwyg.contentEditable(el, true);
        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false);
        });
    });
    mw.on("TableClick", function(e, el) {
        if (typeof(mw.inline) != 'undefined') {
            mw.inline.tableController(el);
        }
    });

    mw.on("editUserIsTypingForLong", function(node){
        if(typeof(mw.liveEditSettings) != 'undefined'){
            if(mw.liveEditSettings.active){
                mw.liveEditSettings.hide();
            }
        }
    });
    mw.on("TableTdClick", function(e, el) {
        if (typeof(mw.inline) !== 'undefined') {
            mw.inline.setActiveCell(el, e);
            var td_parent_table = mw.tools.firstParentWithTag(el, 'table');
            if (td_parent_table) {
                mw.inline.tableController(td_parent_table);
            }
        }
    });
    var t = mwd.querySelectorAll('[field="title"]'),
        l = t.length,
        i = 0;

    for (; i < l; i++) {
        $(t[i]).addClass("nodrop");
    }

    $(mwd.body).on("paste", function(e) {
        if(mw.tools.hasClass(e.target, 'plain-text')){
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertHTML", false, text);
        }
    });

    $(mwd.body).on("mousedown mouseup touchstart touchend", function(e) {

        if (e.type === 'mousedown' || e.type === 'touchstart') {
            if (mw.iconSelectorGUI
                && !mw.wysiwyg.elementHasFontIconClass(e.target)
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['tooltip-icon-picker', 'mw-tooltip'])) {
                $(mw.iconSelectorGUI).hide();
                mw.iconSelector.hide();
            }
            if (!mw.tools.hasClass(e.target, 'ui-resizable-handle') && !mw.tools.hasParentsWithClass(e.target, 'ui-resizable-handle')) {
                mw.tools.addClass(mwd.body, 'state-element')
            } else {
                mw.tools.removeClass(mwd.body, 'state-element');
            }

            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip-insert-module') && !mw.tools.hasAnyOfClasses(e.target, ['mw-plus-bottom', 'mw-plus-top'])) {
                mw.$('.mw-tooltip-insert-module').remove();
                mw.drag.plus.locked = false;
            }

        } else {
            mw.tools.removeClass(mwd.body, 'state-element');
        }
    });
    $('span.mw-powered-by').on("click", function(e) {
        mw.tools.open_global_module_settings_modal('white_label/admin', 'mw-powered-by');
        return false;
    });

    mw.paddingCTRL = new mw.paddingEditor({

    });

});


setTimeout(function(){

    mw.gridComponents =  mw.drag.external_grids_row_classes.concat(mw.drag.external_grids_col_classes);
    mw.gridComponents.push('mw-row','mw-ui-row','wrap_blocks_index', 'element');
}, 50);

mw.drag = {
    create_columns: function(selector, $numcols) {

        if (!$(selector).hasClass("active")) {

            $(mw.drag.columns.resizer).hide()

            var id = mw._activeRowOver.id;

            $(selector).addClass("active")
            var $el_id = id != '' ? id : mw.settings.mw - row_id;

            mw.settings.sortables_created = false;
            var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").length;

            if ($numcols == 0) {
                var $numcols = 1;
            }
            var $numcols = parseInt($numcols);
            if ($exisintg_num == 0) {
                $exisintg_num = 1;
            }
            if ($numcols != $exisintg_num) {
                if ($numcols > $exisintg_num) { //more columns
                    var i = $exisintg_num;
                    for (; i < $numcols; i++) {
                        var new_col = mwd.createElement('div');
                        new_col.className = 'mw-col';
                        new_col.innerHTML = '<div class="mw-col-container"><div class="mw-empty element" id="element_'+mw.random()+'"></div></div>';
                        mw.$('#' + $el_id).append(new_col);
                        mw.drag.fix_placeholders(true, '#' + $el_id);
                    }
                    //mw.resizable_columns();
                } else { //less columns
                    var $cols_to_remove = $exisintg_num - $numcols;
                    if ($cols_to_remove > 0) {

                        var fragment = document.createDocumentFragment(),
                            last_after_remove;

                        mw.$('#' + $el_id).children(".mw-col").each(function(i) {
                            if (i == ($numcols - 1)) {
                                last_after_remove = $(this);

                            } else {
                                if (i > ($numcols - 1)) {
                                    if (this.querySelector('.mw-col-container') !== null) {
                                        mw.tools.foreachChildren(this, function() {
                                            if (mw.tools.hasClass(this.className, 'mw-col-container')) {
                                                fragment.appendChild(this);
                                            }
                                        });
                                    }
                                    $(this).remove();
                                }
                            }
                        });
                        var last_container = last_after_remove.find(".mw-col-container");

                        var nodes = fragment.childNodes,
                            i = 0,
                            l = nodes.length;

                        for (; i < l; i++) {
                            var node = nodes[i];
                            mw.$('.empty-element, .ui-resizable-handle', node).remove();
                            last_container.append(node.innerHTML);
                        }

                        //last_after_remove.resizable("destroy");
                        mw.$('#' + $el_id).children(".empty-element").remove();
                        mw.drag.fix_placeholders(true, '#' + $el_id);

                    }
                }

                var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").size();
                var $eq_w = 100 / $exisintg_num;
                var $eq_w1 = $eq_w;
                mw.$('#' + $el_id).children(".mw-col").width($eq_w1 + '%');
            }
        }
        mw.wysiwyg.nceui();
    },
    replace:function(el, dir, callback){
      var prev = el[dir]();
       var thisOff = el.offset();
        var prevOff = prev.offset();
        prev
        .css({
          position:'relative'
        })
        .animate({top:thisOff.top-prevOff.top });

        el
        .css({
          position:'relative'
        })
        .animate({top:prevOff.top - thisOff.top}, function(){
          if(dir == 'prev'){
            prev.before(el);
          }
          else{
            prev.after(el);
          }

          el.css({'top': '', 'position':''})
          prev.css({'top': '', 'position':''});
          mw.wysiwyg.change(el[0])
          if(!!callback){
            setTimeout(function(){
              callback.call()
            }, 10)
          }

        })
    },
    external_grids_row_classes: ['row'],
    external_grids_col_classes: ['row', 'col-lg-1', 'col-lg-10', 'col-lg-11', 'col-lg-12', 'col-lg-2', 'col-lg-3', 'col-lg-4', 'col-lg-5', 'col-lg-6', 'col-lg-7', 'col-lg-8', 'col-lg-9', 'col-md-1', 'col-md-10', 'col-md-11', 'col-md-12', 'col-md-2', 'col-md-3', 'col-md-4', 'col-md-5', 'col-md-6', 'col-md-7', 'col-md-8', 'col-md-9', 'col-sm-1', 'col-sm-10', 'col-sm-11', 'col-sm-12', 'col-sm-2', 'col-sm-3', 'col-sm-4', 'col-sm-5', 'col-sm-6', 'col-sm-7', 'col-sm-8', 'col-sm-9', 'col-xs-1', 'col-xs-10', 'col-xs-11', 'col-xs-12', 'col-xs-2', 'col-xs-3', 'col-xs-4', 'col-xs-5', 'col-xs-6', 'col-xs-7', 'col-xs-8', 'col-xs-9'],
    external_css_no_element_classes: ['container','navbar', 'navbar-header', 'navbar-collapse', 'navbar-static', 'navbar-static-top', 'navbar-default', 'navbar-text', 'navbar-right', 'navbar-center', 'navbar-left', 'nav navbar-nav', 'collapse', 'header-collapse', 'panel-heading', 'panel-body', 'panel-footer'],
    section_selectors: ['.module-layouts'],
    external_css_no_element_controll_classes: ['container', 'container-fluid', 'edit','noelement','no-element','allow-drop','nodrop', 'mw-open-module-settings','module-layouts'],
    onCloneableControl:function(target, isOverControl){
      if(!this._onCloneableControl){
        this._onCloneableControl = mwd.createElement('div');
        this._onCloneableControl.className = 'mw-cloneable-control';
        var html = '';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-prev" title="Move backward"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-plus" title="Clone"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-minus" title="Remove"></span>' ;
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-next" title="Move forward"></span>';
        this._onCloneableControl.innerHTML = html;

        mwd.body.appendChild(this._onCloneableControl);
        $('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent()
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            var parser = mw.tools.parseHtml(mw.drag._onCloneableControl.__target.outerHTML).body;
            var all = parser.querySelectorAll('[id]'), i = 0;
            for( ; i<all.length; i++){
                all[i].id = 'mw-cl-id-' + mw.random();
            }
          $(mw.drag._onCloneableControl.__target).after(parser.innerHTML);
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
          mw.wysiwyg.change(mw.drag._onCloneableControl.__target)
        });
        $('.mw-cloneable-control-minus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
          $(mw.drag._onCloneableControl.__target).fadeOut(function(){
              mw.wysiwyg.change(this);
            $(this).remove();
              mw.liveEditState.record({
                  target: $t[0],
                  value: $t[0].innerHTML
              });
          });
        });
        $('.mw-cloneable-control-next', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
           $(mw.drag._onCloneableControl.__target).next().after(mw.drag._onCloneableControl.__target)
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
           mw.wysiwyg.change(mw.drag._onCloneableControl.__target)
        });
        $('.mw-cloneable-control-prev', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
           $(mw.drag._onCloneableControl.__target).prev().before(mw.drag._onCloneableControl.__target)
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
           mw.wysiwyg.change(mw.drag._onCloneableControl.__target)
        });
      }
      var clc = $(this._onCloneableControl);
      if(target == 'hide'){
        clc.hide();
      }
      else{
        clc.show();
        this._onCloneableControl.__target = target;

        var next = $(this._onCloneableControl.__target).next();
        var prev = $(this._onCloneableControl.__target).prev();
        var el = $(target), off = el.offset();


        if(next.length == 0){
          $('.mw-cloneable-control-next', clc).hide();
        }
        else{
          $('.mw-cloneable-control-next', clc).show();
        }
        if(prev.length == 0){
          $('.mw-cloneable-control-prev', clc).hide();
        }
        else{
          $('.mw-cloneable-control-prev', clc).show();
        }
        var leftCenter = (off.left > 0 ? off.left : 0) + (el.width()/2 - clc.width()/2) ;
        clc.show();
          if(isOverControl){
              return;
          }
          clc.css({
              top: off.top > 0 ? off.top : 0 ,
              //left: off.left > 0 ? off.left : 0
              left: leftCenter
          });


          var cloner = mwd.querySelector('.mw-cloneable-control');
          if(cloner) {
              mw._initHandles.getAll().forEach(function (curr) {
                  masterRect = curr.wrapper.getBoundingClientRect();
                  var clonerect = cloner.getBoundingClientRect();

                  if (mw._initHandles.collide(masterRect, clonerect)) {
                      cloner.style.top = (parseFloat(curr.wrapper.style.top) + 10) + 'px';
                      cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                  }
              });
          }
      }

    },
    dropOutsideDistance: 25,
    columnout: false,
    noop: mwd.createElement('div'),
    create: function() {
        mw.top_half = false;
        var edits = mwd.body.querySelectorAll(".edit"),
            elen = edits.length,
            ei = 0;
        for (; ei < elen; ei++) {
            var els = edits[ei].querySelectorAll('p,div,h1,h2,h3,h4,h5,h6,section,img'),
                i = 0,
                l = els.length;
            for (; i < l; i++) {
                var el = els[i];

                if( !mw.drag.target.canBeElement(el) ){
                     continue;
                }
                var cls = el.className;

                var isEl = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['element', 'module'])
                        && !mw.tools.hasAnyOfClasses(el, ['mw-col','mw-col', 'mw-row', 'mw-zone']);
                if (isEl) {
                    mw.tools.addClass(el, 'element');
                }
            }
        }
        mw.$("#live_edit_toolbar_holder .module").removeClass("module");

        mw.handlerMouse = {
            x: 0,
            y: 0
        };

        $(mwd.body).on('mousemove touchmove', function(event) {

            mw.dragSTOPCheck = false;
            if (!mw.settings.resize_started) {

                mw.emouse = mw.event.page(event);

                mw.mm_target = event.target;
                mw.$mm_target = $(mw.mm_target);

                if (!mw.isDrag) {
                    if (mw.liveEditSelectMode === 'element') {
                        if(mw.tools.distance(mw.handlerMouse.x, mw.handlerMouse.y, mw.emouse.x, mw.emouse.y) > 20) {
                            mw.tools.removeClass(this, 'isTyping');
                            mw.handlerMouse = Object.assign({}, mw.emouse);
                            mw.liveEditHandlers(event)
                        }
                    }
                } else {
                    mw.ea.data.currentGrabbed = mw.dragCurrent;
                    //if( (mw.emouse.x+mw.emouse.y) % 2 === 0 ) {
                        mw.tools.removeClass(this, 'isTyping');
                        mw.ea.interactionAnalizer(event);
                        $(".currentDragMouseOver").removeClass("currentDragMouseOver");
                        $(mw.currentDragMouseOver).addClass("currentDragMouseOver");
                    //}
                }
            }
        });
        mw.dropables.prepare();

        mw.drag.fix_placeholders(true);
        mw.drag.fixes();

        $(mwd.body).on('mouseup touchend', function(event) {

            mw.mouseDownStarted = false;
            if (mw.isDrag && mw.dropable.is(":hidden")) {
                mw.$(".ui-draggable-dragging").css({
                    top: 0,
                    left: 0
                });
            }
            $(this).removeClass("not-allowed");
        });

        $(mwd.body).on('mousedown touchstart', function(event) {
            var target = event.target;

            if ($(target).hasClass("image_free_text")) {
                mw.image._dragcurrent = target;
                mw.image._dragparent = target.parentNode;
                var pagePos = mw.event.page(event);
                mw.image._dragcursorAt.x = pagePos.x- target.offsetLeft;
                mw.image._dragcursorAt.y = pagePos.y - target.offsetTop;
                target.startedY = target.offsetTop - target.parentNode.offsetTop;
                target.startedX = target.offsetLeft - target.parentNode.offsetLeft;
            }

            if ($(".desc_area_hover").length === 0) {
                $(".desc_area").hide();
            }
            if (mw.tools.hasClass(event.target.className, 'mw-open-module-settings')) {

                if(!mw.settings.live_edit_open_module_settings_in_sidebar){
                    mw.drag.module_settings(mw.tools.firstParentOrCurrentWithAnyOfClasses(event.target, ['module']))
                } else {


                        //var id = mwd.tools.firstParentWithClass(event.target, 'module').id;
                    var target = mw.tools.firstParentWithClass(event.target, 'module') ;
                    mw.liveNodeSettings.set('module', target);
                    //mw.liveNodeSettings.set('module', event.target);
                }
            }

            if (!mw.tools.hasParentsWithTag(event.target, 'TABLE') && !mw.tools.hasParentsWithClass(event.target, 'mw-inline-bar')) {
                $(mw.inline.tableControl).hide();
                mw.$(".tc-activecell").removeClass('tc-activecell');
            }
            if (!mw.isDrag && mw.tools.hasClass(target, 'mw-empty')) {

            }
        });

        mw.on("ItemLeave", function(e, target) {
            $(mw.handle_item).css({
                top: "",
                left: ""
            });
        });
    },

    init: function(selector, callback) {
        if (!mw.handle_item) {

            $(mwd.body).append(mw.settings.handles.module);
            $(mwd.body).append(mw.settings.handles.row);
            $(mwd.body).append(mw.settings.handles.element);
            $(mwd.body).append(mw.settings.handles.item);


            mw.handle_element = mwd.getElementById('mw_handle_element');
            mw.handle_item = '';

            var pd = '<div class="mw-module-quick-options">'
                + '<span class="mw-module-quick-options-menu"></span>'
                +  '<div class="mw-module-quick-options-content">'
                +  '<label>Top spacing</label>'
                +  '<input type="range">'
                +  '<label>Bottom spacing</label>'
                +  '<input type="range">'
                +  '<label>Top and bottom</label>'
                +  '<input type="range">'
                +  '</div></div>';


            $(mw.handle_item).mouseenter(function() {
                var el = $(this);
                var curr = el.data("curr");
                curr.id = 'item_' + mw.random();
                el.draggable("option", "helper", function() {
                    var clone = $(curr).clone(true);
                    clone.css({
                        width: $(curr).width(),
                        height: $(curr).height()
                    });
                    return clone;
                });
            }).click(function() {
                var curr = $(this).data("curr");
                if (!$(curr).hasClass("element-current")) {
                    mw.trigger("ItemClick", curr);
                }
            });




            $(mw.handle_item).draggable({
                cursorAt: {
                    top: -30
                },
                start: function() {
                    mw.isDrag = true;
                    var curr = $(mw.handle_item).data("curr");
                    mw.dragCurrent =mw.ea.data.currentGrabbed = curr;
                    $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                    mw.trigger("AllLeave");
                    mw.drag.fix_placeholders();
                    $(mwd.body).addClass("dragStart");
                    mw.image_resizer._hide();
                    mw.wysiwyg.change(mw.dragCurrent);
                },
                stop: function() {
                    $(mwd.body).removeClass("dragStart");
                }
            });

        }
        mw.drag.the_drop();




    },
    properFocus: function(event) {
        if (mw.tools.hasClass(event.target, 'mw-row') || mw.tools.hasClass(event.target, 'mw-col')) {
            if (mw.tools.hasClass(event.target, 'mw-col')) {
                var tofocus = event.target.querySelector('.mw-col-container');
            } else {
                var i = 0,
                    cols = event.target.children,
                    l = cols.length;
                for (; i < l; i++) {
                    var _cleft = $(cols[i]).offset().left;
                    var ePos = mw.event.page(event);
                    if (_cleft < ePos.x && (_cleft + cols[i].clientWidth) > ePos.x) {
                        var tofocus = cols[i].querySelector('.mw-col-container');
                        if (tofocus === null) {
                            cols[i].innerHTML = '<div class="mw-col-container">' + cols[i].innerHTML + '</div>';
                        }
                        var tofocus = cols[i].querySelector('.mw-col-container');
                        break;
                    }
                }
            }
            if (!!tofocus && tofocus.querySelector('.element') !== null) {
                var arr = tofocus.querySelectorAll('.element'),
                    l = arr.length;
                var tofocus = arr[l - 1];

            }
            if (!!tofocus) {
                var range = document.createRange();
                var sel = window.getSelection();
                range.selectNodeContents(tofocus);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);

            }
        }
    },

    toolbar_modules: function(selector) {
        var items = selector || ".modules-list li[data-module-name]";
        mw.$(items).draggable({
            revert: true,
            /*cursorAt: {
                top: -30
            },*/
            revertDuration: 0,
            start: function(a, b) {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw.GlobalModuleListHelper;
                $(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();
            },
            stop: function() {
                mw.isDrag = false;
                mw.pauseSave = true;
                var el = this;
                $(mwd.body).removeClass("dragStart");
                setTimeout(function() {
                    mw.drag.load_new_modules();
                    mw.recommend.increase($(mw.dragCurrent).attr("data-module-name"));
                    mw.drag.toolbar_modules(el);
                }, 200);
            }
        });
        mw.$(items).mouseenter(function() {
            $(this).draggable("option", "helper", function() {
                var clone = $(this).clone(true);
                clone.appendTo(mwd.body);
                mw.GlobalModuleListHelper = clone[0];
                return clone[0];
            });
        });
        mw.$(items).on("click mousedown mouseup", function(e) {
            e.preventDefault();
            if (e.type === 'click') {
                return false;
            }
            if (e.type === 'mousedown') {
                this.mousedown = true;
            }
            if (e.type === 'mouseup' && e.which === 1 && !!this.mousedown) {
                mw.$(items).each(function() {
                    this.mousedown = false;
                });
                if (!mw.isDrag && mww.getSelection().rangeCount > 0 && mwd.querySelector('.mw_modal') === null && mw.modulesClickInsert) {
                    var html = this.outerHTML;
                    mw.wysiwyg.insert_html(html);
                    mw.drag.load_new_modules();
                }
            }
        });
    },
    the_drop: function() {
        if (!$(mwd.body).hasClass("bup")) {
            $(mwd.body).addClass("bup");

            $(mwd.body).on("mouseup touchend", function(event) {
                mw.image._dragcurrent = null;
                mw.image._dragparent = null;
                var sliders = mwd.getElementsByClassName("canvas-slider"),cam
                    len = sliders.length,
                    i = 0;
                for (; i < len; i++) {
                    sliders[i].isDrag = false;
                }

                if((event.type === 'mouseup' || event.type === 'touchend') && mw.liveEditSelectMode === 'none'){
                    mw.liveEditSelectMode = 'element';
                }
                if (!mw.isDrag) {
                    var target = event.target;
                    var componentsClasses = [
                        'element',
                        'safe-element',
                        'module',
                        /*
                        'mw_edit_settings',
                        'mw_master_handle',
                        'mw_handle_module_arrow',
                        'mw-element-name-handle',
                        'mw-sorthandle-module',
                        'mw-sorthandle-col',
                        */
                        'plain-text'
                    ];

                    var currentComponent = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, componentsClasses);
                    var fonttarget = mw.wysiwyg.firstElementThatHasFontIconClass(target);

                    if( mw.tools.hasAnyOfClassesOnNodeOrParent(target, componentsClasses)) {
                        if (currentComponent && !fonttarget) {
                            var isSafeMode = false;
                            var order = mw.tools.parentsOrder(target, ['safe-mode', 'module']);
                            if(mw.tools.hasClass(currentComponent, 'module')){
                                mw.trigger("ComponentClick", [target, 'module']);
                            }
                            else if (mw.wysiwyg.isSelectionEditable() && !mw.tools.hasAnyOfClasses(target, componentsClasses) && order['safe-mode'] < order['module']) {
                                mw.trigger("ComponentClick", [target, 'element']);
                            }
                            else {
                                if (!mw.tools.hasAnyOfClasses(target, componentsClasses)) {
                                    var ctype;
                                    var has = componentsClasses.filter(function (item) {
                                        return currentComponent.classList.contains(item)
                                    });
                                    mw.trigger("ComponentClick", [currentComponent, has[0]]);
                               }
                            }
                        }

                        var el = mw.tools.firstParentOrCurrentWithClass(target, 'element');

                        var safeEl = mw.tools.firstParentOrCurrentWithClass(target, 'safe-element');
                        var moduleEl = mw.tools.firstParentOrCurrentWithClass(target, 'module');

                        if ($(target).hasClass("plain-text")) {
                            mw.trigger("PlainTextClick", target);
                        } else if (el) {
                            mw.trigger("ElementClick", [el, event]);
                        }

                        if (safeEl) {
                            mw.trigger("SafeElementClick", [safeEl, event]);
                        }

                        if (moduleEl) {
                            mw.trigger("ModuleClick", [moduleEl, event]);
                        }
                    }

                    if (fonttarget && !mw.tools.hasAnyOfClasses(target, ['element','module'])) {
                        if ((fonttarget.tagName == 'I' || fonttarget.tagName == 'SPAN') && mw.tools.hasParentsWithClass(fonttarget, 'edit') && !mw.tools.hasParentsWithClass(fonttarget, 'dropdown')) {
                            if (!mw.tools.hasParentsWithClass(fonttarget, 'module')) {
                                mw.trigger("IconElementClick", fonttarget);
                                mw.trigger("ComponentClick", [fonttarget, 'icon']);
                            }
                            else {
                                if (mw.wysiwyg.editInsideModule(fonttarget)) {
                                    mw.trigger("IconElementClick", fonttarget);
                                    mw.trigger("ComponentClick", [fonttarget, 'icon']);
                                }
                            }
                        }
                    }

                    if ($(target).hasClass("mw_item")) {
                        mw.trigger("ItemClick", target);
                    } else if (mw.tools.hasParentsWithClass(target, 'mw_item')) {
                        mw.trigger("ItemClick", $(target).parents(".mw_item")[0]);
                    }
                    if (target.tagName == 'IMG' && mw.tools.hasParentsWithClass(target, 'edit')) {
                        var order = mw.tools.parentsOrder(mw.mm_target, ['edit', 'module']);
                        if ((order.module == -1) || (order.edit > -1 && order.edit < order.module)) {
                            if (!mw.tools.hasParentsWithClass(target, 'mw-defaults')) {
                                mw.trigger("ImageClick", target);
                            }
                            mw.wysiwyg.select_element(target);
                        }
                    }
                    if (target.tagName === 'BODY') {
                        mw.trigger("BodyClick", target);
                    }

                    if (target.tagName === 'TABLE' && mw.tools.hasParentsWithClass(target, 'edit') && !mw.tools.hasParentsWithClass(target, 'module')) {
                        mw.trigger("TableClick", target);
                    }
                    if (target.tagName === 'TD' && mw.tools.hasParentsWithClass(target, 'edit')  && !mw.tools.hasParentsWithClass(target, 'module')) {
                        mw.trigger("TableTdClick", target);
                    }
                    if (mw.tools.hasClass(target, 'mw-empty') || mw.tools.hasParentsWithClass(target, 'mw-empty')) {
                        // mw.$("#modules-and-layouts").addClass("hovered");
                    } else {
                        if (!mw.tools.hasClass(target, 'modules-and-layouts-holder') && !mw.tools.hasParentsWithClass(target, 'modules-and-layouts-holder')) {
                            mw.$("#modules-and-layouts").removeClass("hovered");
                        }
                    }
                    if (mw.tools.hasClass(target, 'mw-empty') && target.innerHTML.trim() !== '') {
                        target.className = 'element';
                    }
                }
                if (!mw.isDrag) {
                    mw.drag.properFocus(event);
                }
                if (mw.isDrag) {
                    mw.isDrag = false;


                    mw.wysiwyg.change(mw.currentDragMouseOver);
                    $(mw.currentDragMouseOver).removeClass("currentDragMouseOver");

                    mw._initHandles.hideAll();

                    setTimeout(function() {
                        if(mw.ea.data.target && mw.ea.data.currentGrabbed){
                            if(!!mw.ea.data.dropableAction && !!mw.ea.data.target && !!mw.ea.data.currentGrabbed){
                                var ed = mw.tools.firstParentOrCurrentWithClass(mw.ea.data.target, 'edit');
                                var prev = mw.ea.data.currentGrabbed.parentNode;
                                var rec = {
                                    target: ed,
                                    value: ed.innerHTML
                                };
                                if(prev){
                                    var prevDoc = mw.tools.parseHtml(prev.innerHTML);
                                    $(prevDoc.querySelector('.mw_drag_current')).css({
                                        visibility: 'hidden',
                                        opacity: 0
                                    });
                                    rec.prev = prev;
                                    rec.prevValue = prevDoc.body.innerHTML;
                                }

                                mw.liveEditState.record(rec);
                                $(mw.ea.data.target)[mw.ea.data.dropableAction](mw.ea.data.currentGrabbed);

                                setTimeout(function(ed) {
                                    var nrec = {
                                        target: ed,
                                        value: ed.innerHTML
                                    };
                                    if(prev){
                                        var prevDoc = mw.tools.parseHtml(prev.innerHTML);
                                        $(prevDoc.querySelector('.mw_drag_current')).css({
                                            visibility: 'hidden',
                                            opacity: 0
                                        });
                                        nrec.prev = prev;
                                        nrec.prevValue = prevDoc.body.innerHTML;
                                    }
                                    mw.liveEditState.record(nrec);
                                }, 50, ed);
                            }
                            else{

                            }
                        }
                        mw.drag.fixes();
                        setTimeout(function() {
                            mw.drag.fix_placeholders();
                            mw.ea.afterAction();
                        }, 40);
                        mw.dropable.hide();

                    }, 77);


                    setTimeout(function () {

                        if (mw.have_new_items == true) {
                            mw.drag.load_new_modules();
                        }
                    }, 120)
                }
            });
        } //toremove
    },


     /**
     * Various fixes
     *
     * @example mw.drag.fixes()
     */
    fixes: function() {
        mw.$(".edit .mw-col, .edit .mw-row").height('auto');
        $(mw.dragCurrent).css({
            top: '',
            left: ''
        });
        var more_selectors = '';
        //var cols = mw.drag.external_grids_col_classes;
        var cols = [];
        var index;
        for (index = cols.length - 1; index >= 0; --index) {
            more_selectors += ',.edit .row > .' + cols[index];
        }
        setTimeout(function() {
            mw.$(".edit .mw-col" + more_selectors).each(function() {
                var el = $(this);
                if (el.children().length == 0 || (el.children('.empty-element').length > 0) || el.children('.ui-draggable-dragging').length > 0) {
                    el.height('auto');
                    if (el.height() < el.parent().height()) {
                        el.height(el.parent().height());
                    } else {
                        el.height('auto');
                    }
                } else {
                    el.children('.empty-element').height('auto');
                    el.height('auto');
                    el.parents('.mw-row:first').height('auto')
                }

                el.height('auto');
                var mwr = mw.tools.firstParentWithClass(this, 'mw-row');
                if (!!mwr) {
                    mwr.style.height = 'auto';
                }
                mw.tools.fixDeniedParagraphHierarchy();

            });
        }, 222);

        var els = mwd.querySelectorAll('div.element'),
            l = els.length,
            i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                if (els[i].querySelector('p,div,li,h1,h2,h3,h4,h5,h6') === null && !mw.tools.hasClass(els[i], 'plain-text')) {
                    if (!mw.tools.hasClass(els[i].className, 'nodrop') && !mw.tools.hasClass(els[i].className, 'mw-empty')) {
                        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(els[i], ['safe-mode'])){
                          els[i].innerHTML = '<p class="element">' + els[i].innerHTML + '</p>';
                        }
                    }
                }
            }
        }
    },
    /**
     * fix_placeholders in the layout
     *
     * @example mw.drag.fix_placeholders(isHard , selector)
     */
    fix_placeholders: function(isHard, selector) {
        selector = selector || '.edit .row';

        var more_selectors2 = 'div.col-md';
        var a = mw.drag.external_grids_col_classes;
        var index;
        for (index = a.length - 1; index >= 0; --index) {
            more_selectors2 += ',div.' + a[index];
        }
        $(selector).each(function() {
            var el = $(this);
            el.children(more_selectors2).each(function() {
                var empty_child = $(this).children('*');
                if (empty_child.size() == 0) {
                    $(this).append('<div class="element" id="mw-element-' + mw.random() + '">' + '</div>');
                    var empty_child = $(this).children("div.element");
                }
            });
        });

    },
    /**
     * Removes contentEditable for ALL elements
     *
     * @example mw.drag.edit_remove();
     */
    edit_remove: function() {

        //	   	mw.$('.edit [contenteditable]').removeAttr("contenteditable");

    },

   target :  {

        canBeElement: function(target) {
            var yesno = true;
            var el = target;
            var noelements = ['mw-ui-col', 'mw-col-container', 'mw-ui-col-container'];

            var noelements_bs3 = mw.drag.external_grids_col_classes;
            var noelements_ext = mw.drag.external_css_no_element_classes;
            var noelements_drag = mw.drag.external_css_no_element_controll_classes;
            var section_selectors = mw.drag.section_selectors;
            var icon_selectors =  mw.wysiwyg.fontIconFamilies;

            noelements = noelements.concat(noelements_bs3);
            noelements = noelements.concat(noelements_ext);
            noelements = noelements.concat(noelements_drag);
            noelements = noelements.concat(section_selectors);
            noelements = noelements.concat(icon_selectors);

            if (mw.tools.hasAnyOfClasses(el, noelements)) {
                yesno = false;
            }
            return yesno;
        },
        canBeEditable: function(target) {
            var noyes = false;
            var el = target;
            if( !el.isContentEditable && !mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']) ) {
                var order = mw.tools.parentsOrder(el, ['edit','module']);
                if(order.module === -1 && order.edit !== -1){
                    noyes = true;
                }
                if(order.module > order.edit){
                    noyes = true;
                }
            }
            return noyes;
        }
   },

    fancynateLoading: function(module) {
        mw.$(module).addClass("module_loading");
        setTimeout(function() {
            $(module).addClass("module_activated");
            setTimeout(function() {
                mw.$(module).removeClass("module_loading module_activated");
            }, 510);
        }, 150);
    },

    /**
     * Scans for new dropped modules and loads them
     *
     * @example mw.drag.load_new_modules()
     * @return void
     */
    load_new_modules: function(callback) {
        return mw.ea.afterAction();
    },

    module_view: function(view) {
        var modal = mw.drag.module_settings(false, view);
        return modal;
    },
    module_settings: function(a, view) {
        return mw.tools.module_settings(a, view);
    },
    current_module_settings_tooltip_show_on_element: function(element_id, view, type) {
        if (!element_id) {
            return;
        }

        if (mw.$('#' + element_id).length === 0) {
            return;
        }

        var curr = mw._activeModuleOver;
        var tooltip_element = $("#" + element_id);
        var attributes = {};
        var type = type || 'modal';
        $.each(curr.attributes, function(index, attr) {
            attributes[attr.name] = attr.value;
        });
        var data1 = attributes;
        var module_type = null;
        if (data1['data-type'] != undefined) {
            module_type = data1['data-type'];
            data1['data-type'] = data1['data-type'] + '/admin';
        }
        if (data1['data-module-name'] != undefined) {
            delete(data1['data-module-name']);
        }
        if (data1['type'] != undefined) {
            module_type = data1['type'];
            data1['type'] = data1['type'] + '/admin';
        }
        if (module_type != null && view != undefined) {
            data1['data-type'] = data1['type'] = module_type + '/' + view;
        }

        if (typeof data1['class'] != 'undefined') {
            delete(data1['class']);
        }
        if (typeof data1['style'] != 'undefined') {
            delete(data1['style']);
        }
        if (typeof data1.contenteditable != 'undefined') {
            delete(data1.contenteditable);
        }
        data1.live_edit = 'true';
        data1.module_settings = 'true';
        if (view != undefined) {
            data1.view = view;
        } else {
            data1.view = 'admin';
        }
        if (data1.from_url == undefined) {
            data1.from_url = window.parent.location;
        }
        var modal_name = 'module-settings-' + curr.id;
        if (typeof(data1.view.hash) === 'function') {
            var modal_name = 'module-settings-' + curr.id + (data1.view.hash());
        }

        if (mw.$('#' + modal_name).length > 0) {
            var m = mw.$('#' + modal_name)[0];
            m.scrollIntoView();
            mw.tools.highlight(m);
            return false;
        }
        var src = mw.settings.site_url + "api/module?" + json2url(data1);

        if (type === 'modal') {
            var modal = top.mw.tools.modal.frame({
                url: src,
                width: 532,
                height: 150,
                name: modal_name,
                title: '',
                callback: function() {
                    $(this.container).attr('data-settings-for-module', curr.id);
                }
            });
            return modal;
        }
        if (type === 'tooltip') {
            var id = 'mw-tooltip-iframe-'+ mw.random()
            mw.tooltip({
                id: 'module-settings-tooltip-' + modal_name,
                group: 'module_settings_tooltip_show_on_btn',
                close_on_click_outside: true,
                content: '<iframe id="'+id+'" frameborder="0" class="mw-tooltip-iframe" src="' + src + '" scrolling="auto"></iframe>',
                element: tooltip_element
            });
            mw.tools.iframeAutoHeight(mwd.querySelector('#'+id))
        }

    },
    ModuleSettingsPopupLoaded: function(id) {

        mw.$("#" + id + " .mw_option_field").on("change blur", function() {

            var refresh_modules11 = $(this).attr('data-refresh');

            if (refresh_modules11 == undefined) {
                var refresh_modules11 = $(this).attr('data-reload');
            }

            if (refresh_modules11 == undefined) {
                var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                var refresh_modules11 = '#' + refresh_modules11;
            }

            var mname = $(this).parents('.module:first').attr('data-type');
            var og = $(this).attr('data-module-id');
            if (og == undefined) {
                var og = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module')
            }
            if (this.type === 'checkbox') {
                var val = '';
                var items = mw.$('input[name="' + this.name + '"]');
                for (var i = 0; i < items.length; i++) {
                    var _val = items[i].value;
                    var val = items[i].checked === true ? (val === '' ? _val : val + ", " + _val) : val;
                }
            } else {
                val = this.value
            }
            var o_data = {
                option_key: $(this).attr('name'),
                option_group: og,
                option_value: val
                    // chkboxes:checkboxes_obj
            };
            if (mname !== undefined) {
                o_data.module = mname;
            }
            $.ajax({
                type: "POST",
                url: mw.settings.site_url + "api/save_option",
                data: o_data,
                success: function() {
                    if (refresh_modules11 !== undefined && refresh_modules11 !== '') {
                        refresh_modules11 = refresh_modules11.toString();
                        if (window.mw !== undefined) {
                            if (window.mw.reload_module != undefined) {
                                window.mw.reload_module(refresh_modules11);
                                window.mw.reload_module('#' + refresh_modules11);
                            }
                        }
                    }
                }
            });
        });
    },
    /**
     * Loads the module in the given dom element by the $update_element selector .
     *
     * @example mw.drag.load_module('user_login', '#login_box')
     * @param $module_name
     * @param $update_element
     * @return void
     */
    load_module: function($module_name, $update_element) {
        var attributes = {};
        attributes.module = $module_name;
        var url1 = mw.settings.site_url + 'api/module';
        $($update_element).load_modules(url1, attributes, function() {
            window.mw_sortables_created = false;
        });

    },
    /**
     * Deletes element by id or selector
     *
     * @method mw.edit.delete_element(idobj)
     * @param Element id or selector
     */
    delete_element: function(idobj, c) {
        mw.tools.confirm(mw.settings.sorthandle_delete_confirmation_text, function() {
            var el = mw.$(idobj);
            mw.wysiwyg.change(idobj);
            var elparent = el.parent()

            mw.liveEditState.record({
                target: elparent[0],
                value: elparent.html()
            });
            el.addClass("mwfadeout");
            setTimeout(function() {
                mw.$(idobj).remove();
                mw.handleModule.hide();
                $(mw.handleModule).removeClass('mw-active-item');
                mw.drag.fix_placeholders(true);
                mw.liveEditState.record({
                    target: elparent[0],
                    value: elparent.html()
                });
                if(c){
                    c.call()
                }
            }, 300);
        });
    },

    grammarlyFix:function(html){
      var data = mw.tools.parseHtml(html).body;
      $("grammarly-btn", data).remove();
      $("grammarly-card", data).remove();
      $("g.gr_", data).each(function(){
        $(this).replaceWith(this.innerHTML);
      });
      $("[data-gramm_id]", data).removeAttr('data-gramm_id');
      $("[data-gramm]", data).removeAttr('data-gramm');
      $("[data-gramm_id]", data).removeAttr('data-gramm_id');
      return data.innerHTML;
    },
    saving: false,
    coreSave: function(data) {
        if (!data) return false;
        $.each(data, function(){
          this.html = mw.drag.grammarlyFix(this.html)
        });
        mw.drag.saving = true;

        /************  START base64  ************/
        data = JSON.stringify(data);
        data = btoa(encodeURIComponent(data).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
        data = {data_base64:data};
        /************  END base64  ************/

        var xhr = mw.ajax({
            type: 'POST',
            url: mw.settings.api_url + 'save_edit',
            data: data,
            dataType: "json"
        });

        xhr.error(function(jqXHR, textStatus, errorThrown){

        });

        xhr.always(function() {
            mw.drag.saving = false;
        });
        return xhr;
    },
    parseContent: function(root) {
        var root = root || mwd.body;
        var doc = mw.tools.parseHtml(root.innerHTML);
        mw.$('.element-current', doc).removeClass('element-current');
        mw.$('.element-active', doc).removeClass('element-active');
        mw.$('.disable-resize', doc).removeClass('disable-resize');
        mw.$('.mw-webkit-drag-hover-binded', doc).removeClass('mw-webkit-drag-hover-binded');
        mw.$('.empty-element', doc).remove();
        mw.$('.empty-element', doc).remove();
        mw.$('.edit .ui-resizable-handle', doc).remove();
        mw.$('script', doc).remove();

        //var doc = $(doc).find('script').remove();

        mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
        mw.$("[contenteditable]", doc).removeAttr("contenteditable");
        var all = doc.querySelectorAll('[contenteditable]'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            all[i].removeAttribute('contenteditable');
        }
        var all = doc.querySelectorAll('.module'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            if (all[i].querySelector('.edit') === null) {
                all[i].innerHTML = '';
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
    collectData: function(edits) {
        $(edits).each(function(){
          $('meta', this).remove();
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
                $(helper.item).removeClass('changed orig_changed');
                $(helper.item).removeClass('module-over');

                $('.module-over', helper.item).each(function(){
                    $(this).removeClass('module-over');
                });
                $('[class]', helper.item).each(function(){
                    var cls = this.getAttribute("class");
                    if(typeof cls === 'string'){
                        cls = cls.trim();
                    }
                    if(!cls){
                        this.removeAttribute("class");
                    }
                });
                var content = mw.wysiwyg.cleanUnwantedTags(helper.item).innerHTML;
                var attr_obj = {};
                var attrs = helper.item.attributes;
                if (attrs.length > 0) {
                    var ai = 0,
                        al = attrs.length
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
    },
    getData: function(root) {
        var body = mw.drag.parseContent(root).body,
            edits = body.querySelectorAll('.edit.changed'),
            data = mw.drag.collectData(edits);
        return data;
    },

    saveDisabled: false,
    draftDisabled: false,
    save: function(data, success, fail) {
        mw.trigger('beforeSaveStart', data);
        if (typeof saveStaticElementsStyles === 'function') {
            saveStaticElementsStyles();
        }
        if (mw.drag.saveDisabled) return false;
        if(typeof(data) == 'undefined'){
          var body = mw.drag.parseContent().body,
            edits = body.querySelectorAll('.edit.changed'),
            data = mw.drag.collectData(edits);
        }



        if (mw.tools.isEmptyObject(data)) return false;

        mw._liveeditData = data;

        mw.trigger('saveStart', mw._liveeditData);

        var xhr = mw.drag.coreSave(mw._liveeditData);
        xhr.error(function(){

            if(xhr.status == 403){
                var modal = mw.modal({
                    id : 'save_content_error_iframe_modal',
                    html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
                    width:$(window).width() - 90,
                    height:$(window).height() - 90
                });

                mw.askusertostay = false;

                $("#save_content_error_iframe").ready(function() {
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
                    doc.open();
                    doc.write(xhr.responseText);
                    doc.close();
                    var save_content_error_iframe_reloads = 0;
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;

                    $("#save_content_error_iframe").load(function(){
                        // cloudflare captcha
                        var is_cf =  $('.challenge-form',doc).length;
                        save_content_error_iframe_reloads++;

                        if(is_cf && save_content_error_iframe_reloads == 2){
                            setTimeout(function(){
                                mw.askusertostay = false;
                                $('#save_content_error_iframe_modal').remove();
                            }, 150);

                        }
                    });

                });
            }
        });
        xhr.success(function(sdata) {
            mw.$('.edit.changed').removeClass('changed');
            mw.$('.orig_changed').removeClass('orig_changed');
            if (mwd.querySelector('.edit.changed') !== null) {
                mw.drag.save();
            } else {
                mw.askusertostay = false;
                mw.trigger('saveEnd', sdata);
            }
            if(success){
              success.call(sdata)
            }

        });
        xhr.fail(function(jqXHR, textStatus, errorThrown) {
            mw.trigger('saveFailed', textStatus, errorThrown);
            if(fail){
              fail.call(sdata)
            }
        });
        return xhr;
    },
    saveDraftOld: '',
    DraftSaving: false,
    initDraft: false,
    saveDraft: function() {
        if (mw.drag.draftDisabled) return false;
        if (mw.drag.DraftSaving) return false;
        if (!mw.drag.initDraft) return false;
        if (mwd.body.textContent != mw.drag.saveDraftOld) {
            mw.drag.saveDraftOld = mwd.body.textContent;
            var body = mw.drag.parseContent().body,
                edits = body.querySelectorAll('.edit.changed'),
                data = mw.drag.collectData(edits);
            if (mw.tools.isEmptyObject(data)) return false;
            data['is_draft'] = true;
            mw.drag.DraftSaving = true;
            var xhr = mw.drag.coreSave(data);
            xhr.always(function(msg) {
                mw.drag.DraftSaving = false;
                mw.drag.initDraft = false;
                mw.trigger('saveDraftCompleted');

            });
        }
    }
}
mw.pcWidthExtend = function(selector, howMuch, cache, final, len) {
    if (final < 3) {

        selector.eq(len - 1).width((cache[len - 1] + final) + "%");
    } else {
        var final = 0;
        selector.each(function(i) {
            var el = $(this);
            cache[i] = cache[i] + howMuch;
            final += cache[i];
            el.width(cache[i] + "%");
        });
        if (final < 100) {
            mw.pcWidthExtend(selector, howMuch, cache, final, len);
        }
    }
}
mw.px2pc = function(row) {

    var cache = [];
    var row = $(row);
    var width = row.width();
    var cols = row.children(".mw-col");
    var len = cols.length;
    cols.each(function() {
        var el = $(this);
        var w = (( /*Math.floor*/ (el.width() / width * 100)));
        cache.push(w);
        el.css({
            width: w + "%"
        });
    });
    //check them after
    mwcsum = 0;
    var x = 0;
    for (; x < len; x++) {
        mwcsum += cache[x];
    }
    var final = 100 - mwcsum;
    if (final != 0) {

        mw.pcWidthExtend(cols, final / len, cache, final, len);
    }
}
mw.delete_column = function(which) {
    if (confirm(mw.settings.sorthandle_delete_confirmation_text)) {
        var row = $(which).parents(".mw-row").eq(0);
        var col = $(which).parents(".mw-col").eq(0);
        if (col.next(".mw-col").length == 0) {
            col.prev(".mw-col").resizable("destroy");
        }
        col.remove();
        if (row.find(".mw-col").length == 0) {
            row.remove();
        } else {
            mw.px2pc(row[0]);
        }
    }
}


mw.drop_regions = {
    enabled: mw.settings.regions,
    ContainsDisabledSideClass: function(el) {
        var cls = ['edit', 'mw-col', 'mw-row', 'mw-col-container'],
            i = 0,
            l = cls.length;
        var elcls = el.className;
        if (elcls == '') {
            return true
        }
        for (; i < l; i++) {
            if (mw.tools.hasClass(elcls, cls[i])) {
                return true;
            }
        }
        return false;
    },
    dropTimeout: null,
    global_drop_is_in_region: false,
    which: 'none',
    create: function(element) {
        var el = $(element);
        var height = el.height();
        var width = el.width();
        var offset = el.offset();
        var region_left = {
            l: offset.left,
            r: offset.left + width * 0.1,
            t: offset.top,
            b: offset.top + height
        }
        var region_right = {
            l: offset.left + width - width * 0.1,
            r: offset.left + width,
            t: offset.top,
            b: offset.top + height
        }
        return {
            left: region_left,
            right: region_right
        }
    },
    is_in_region: function(regions, event) {

        var l = regions.left;
        var r = regions.right;
        var ep = mw.event.page(event);
        var mx = ep.x;
        var my = ep.y;
        if (mx > l.l && mx < l.r && my > l.t && my < l.b) {
            return 'left';
        } else if (mx > r.l && mx < r.r && my > r.t && my < r.b) {
            return 'right';
        } else {
            return 'none'
        }
    },
    init: function(element, event, callback) {

        if (mw.drop_regions.dropTimeout == null) {
            mw.drop_regions.dropTimeout = setTimeout(function() {
                if (mw.drop_regions.enabled) {
                    var regions = mw.drop_regions.create(element);
                    var is_in_region = mw.drop_regions.is_in_region(regions, event);
                    if (is_in_region == 'left' && !mw.drop_regions.ContainsDisabledSideClass(element)) {

                        callback.call(this, 'left');
                        mw.drop_regions.global_drop_is_in_region = true;
                        mw.drop_regions.which = 'left';
                    } else if (is_in_region == 'right' && !mw.drop_regions.ContainsDisabledSideClass(element)) {
                        callback.call(this, 'right');
                        mw.drop_regions.global_drop_is_in_region = true;
                        mw.drop_regions.which = 'right';
                    } else {
                        mw.drop_regions.global_drop_is_in_region = false;
                        mw.drop_regions.which = 'none';
                    }
                } else {
                    mw.drop_regions.global_drop_is_in_region = false;
                    mw.drop_regions.which = 'none';
                }
                mw.drop_regions.dropTimeout = null;
            }, 37);
        }
    }
}






/* Toolbar */
mw.tools.addClass(mwd.body, 'mw-live-edit')
mw.designTool = {
    position: function(rel) {
        var rel = rel || mw.$("#design_bnav");

        if (rel.length == 0) {
            return false;

        }
        var ww = $(window).width();
        var wh = $(window).height();

        var off = rel.offset();
        var w = rel.width();
        var h = rel.height();

        var sumWidth = off.left + w;
        var sumHeight = off.top + h;

        sumWidth > ww ? rel.css('left', ww - w - 20) : '';
        sumHeight > wh ? rel.css('top', wh - h - 20) : '';
    },
    setSize:function(){
      $(".ts_main_ul").css('maxHeight', innerHeight - 100);
      var root =  mw.$("#design_bnav"), off = root.offset();
      if(off.top < 100){
        root.css('top', 100);
      }
      if((off.top + root.height()) > innerHeight){
        root.css('top', 100);
      }
    }
};
$(window).on("load", function() {
    setTimeout(function(){
    mw.$(".mw-dropdown_type_navigation a").each(function() {
        var el = $(this);
        var li = el.parent();
        el.attr("href", "javascript:;");
        var val = li.dataset("category-id");
        li.attr("value", val);
    });

    mw.$("#module_category_selector").change(function() {
        var val = $(this).getDropdownValue();

        if (val == 'all') {
            mw.$(".list-modules li").show();
            return false;
        }
        (val != -1 && val != "-1") ? mw.tools.toolbar_sorter(Modules_List_modules, val): '';
    });
    mw.$("#elements_category_selector").change(function() {
        var val = $(this).getDropdownValue();

        if (val == 'all') {
            mw.$(".list-elements li").show();
            return false;
        }
        (val != -1 && val != "-1") ? mw.tools.toolbar_sorter(Modules_List_elements, val): '';
    });
    mw.$("#design_bnav, .mw_ex_tools").addClass(mw.cookie.ui("designtool"));
    var design_pos = mw.cookie.ui("designtool_position");

    if (design_pos != "") {
        var design_pos = design_pos.split("|");
        mw.$("#design_bnav").css({
            top: design_pos[0] + "px",
            left: design_pos[1] + "px"
        });
    }

    mw.designTool.position();
    mw.$(".mw_ex_tools").click(function() {

        var rel = mw.$($(this).attr("href"));

        rel.toggleClass('active');

        $(this).toggleClass('active');

        mw.cookie.ui("designtool", rel.hasClass("active") ? "active" : "");

        mw.designTool.position(rel);

        return false;
    });

    mw.$(".ts_main_a,.ed_label").on('click', function(){
      $(this).next().slideToggle(function(){
        mw.designTool.setSize()
      })
    });


    mw.$(".toolbar_bnav").hover(function() {
        $(this).addClass("toolbar_bnav_hover");
    }, function() {
        $(this).removeClass("toolbar_bnav_hover");
    });


    }, 100)



});


$.fn.notmouseenter = function() {
    return this.filter(function() {
        var el = $(el);
        var events = el.data("events");
        return (events == undefined || events.mouseover == undefined || events.mouseover[0].origType != 'mouseenter');
    });
};

$.fn.notclick = function() {
    return this.filter(function() {
        var el = $(el);
        var events = el.data("events");
        return (events == undefined || events.click == undefined);
    });
};
$.expr[':'].isHidden = function(obj, index, meta, stack) {
    return mw.is.invisible(obj);
};
$.expr[':'].isVisible = function(obj, index, meta, stack) {
    return window.getComputedStyle(obj, null).visibility === 'visible';
};
PagesFrameSRCSet = false;
$(document).ready(function() {
    mw.wysiwyg.init_editables();
    mw.wysiwyg.prepare();
    mw.wysiwyg.init();
    mw.ea = mw.ea || new mw.ElementAnalyzer();

    mw.$(".edit a, #mw-toolbar-right a").click(function() {
        var el = this;
        if (!el.isContentEditable) {
            if (el.onclick === null) {
                if (!(el.href.indexOf("javascript:") === 0 || el.href == '#' || ($(el).attr("href") && $(el).attr("href").indexOf('#') == 0) || typeof el.attributes['href'] == 'undefined')) {
                    return mw.beforeleave(this.href);
                }
            }
        }
    });
    $(window).scroll(function() {
        if ($(window).scrollTop() > 10) {
            mw.tools.addClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
        } else {
            mw.tools.removeClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
        }

    });
    mw.$("#live_edit_toolbar").hover(function() {
        $(mwd.body).addClass("toolbar-hover");
    }, function() {
        $(mwd.body).removeClass("toolbar-hover");
    });
    mw.$("#modules-and-layouts").click(function(e) {
        if (e.target === this) {
            $(this).addClass("hovered");
        }
    });


    mw.on('UserInteraction', function(){
        mw.dropables.userInteractionClasses();
        mw.liveEditSelector.positionSelected();

    });



    mw.on('ElementOver moduleOver', function(e, target){
        var over_target_el = null
        if(e.type === 'onElementOver'){
            var over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['element'])
            if(over_target_el && !mw.tools.hasClass('element-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'element-over')
            }
         } else if(e.type === 'moduleOver'){
            var over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['module'])
            if(over_target_el && !mw.tools.hasClass('module-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'module-over')
            }
        }
        if(over_target_el){
            mw.$(".element-over,.module-over").not(over_target_el).removeClass('element-over module-over');
        }
    });



    mw.on('CloneableOver', function(e, target, isOverControl){
      mw.drag.onCloneableControl(target, isOverControl)
    });

    var onModuleBetweenModulesTime = null;

    mw.on('ModuleBetweenModules', function(e, el, pos){
        clearTimeout(onModuleBetweenModulesTime);
        onModuleBetweenModulesTime = setTimeout(function(){
            if($("#moduleinbetween").length === 0){
                var tip = mw.tooltip({
                    content:'To drop this element here, select Clean container first',
                    element:el[0],
                    position:pos+'-center',
                    skin:'dark',
                    id:'moduleinbetween'
                });
                setTimeout(function(){
                   $("#moduleinbetween").fadeOut(function(){
                       $(this).remove()
                   })
                }, 3000)
            }
        }, 1000)

    })


});

mw.toolbar = {
    fixPad: function() {
        mwd.body.style.paddingTop = mw.toolbar.minTop + mw.$("#live_edit_toolbar").height() /*+ mw.$("#modules-and-layouts").height()*/ + 'px';
    },
    setComponents: function(a) {
        mw.$("#modules-and-layouts, .tst-modules").addClass("active");
        modules_switcher.value = '';
        mw.$("#modules-and-layouts .module-item").show();
        mw.$(".modules_bar_slide_left").hide();
        mw.$(".modules_bar").scrollLeft(0);
        mw.cookie.ui("#modules-and-layouts,#tab_modules,.tst-modules", "true");
        mw.$(".modules-layouts-menu .create-content-dropdown-list").hide();
        if (a == 'layouts') {
            mw.$(modules_switcher).dataset("for", "layouts");
            mw.$(modules_switcher).attr("placeholder", "Layouts");
            $(modules_switcher).focus();
            modules_switcher.searchIn = 'Modules_List_elements';
            mw.tools.addClass(tab_layouts, 'active');
            mw.tools.removeClass(tab_modules, 'active');
        } else if (a == 'modules') {
            mw.$(modules_switcher).dataset("for", "modules");
            mw.$(modules_switcher).attr("placeholder", "Modules");
            $(modules_switcher).focus();
            modules_switcher.searchIn = 'Modules_List_modules';
            mw.tools.addClass(tab_modules, 'active');
            mw.tools.removeClass(tab_layouts, 'active');
        }
    },
    ComponentsShow: function(what) {
        mw.toolbar.setComponents(what);
        var mod_switch = mwd.getElementById('mod_switch');
        if (what == 'layouts') {
            mod_switch.innerHTML = mw.msg.switch_to_modules;
            $(mod_switch).dataset("action", 'modules');
        } else {
            mod_switch.innerHTML = mw.msg.switch_to_layouts;
            $(mod_switch).dataset("action", 'layouts');
        }
        $(mwd.getElementById('modules-and-layouts')).addClass('hovered');

    },
    toolbar_searh: function(obj, value) {
        var value = value.toLowerCase();
        mw.$(".modules_bar").scrollLeft(0);
        for (var item in obj) {
            var child_object = obj[item];
            var id = child_object.id;
            var title = child_object.title.toLowerCase();
            var description = child_object.description || false;
            var item = $(document.getElementById(id));
            if (title.contains(value) || (!!description && description.toLowerCase().contains(value))) {
                item.show();
            } else {
                item.hide();
            }
        }
    },
    tip: function(el, txt) {
        if (!mw.toolbar.tooltip) {
            mw.toolbar.tooltip = mw.tooltip({});
        }
    }
}
mw.setLiveEditor = function() {
    $(mwd.querySelector('.editor_wrapper_tabled')).css({
        left: $(mwd.querySelector('.toolbar-sections-tabs')).outerWidth(true) + $(mwd.querySelector('.wysiwyg-undo-redo')).outerWidth(true) + 30,
        right: $(mwd.querySelector('#mw-toolbar-right')).outerWidth(true)
        //left: 0,
        //width: $(window).width() - $(mwd.querySelector('.toolbar-sections-tabs')).outerWidth(true) - $(mwd.querySelector('#mw-toolbar-right')).outerWidth(true)
    })
}
$(window).on("load", function() {

    mw.wysiwyg.prepareContentEditable();


    mw.$("#history_dd").hover(function() {
        $(this).addClass("hover");
    }, function() {
        $(this).removeClass("hover");
    });
    mw.image.resize.init(".element-image");
    $(mwd.body).mousedown(function(event) {


        if (mw.$(".editor_hover").length === 0) {
            $(mw.wysiwyg.external).empty().css("top", "-9999px");
            $(mwd.body).removeClass('hide_selection');
        }
        if (!mw.$("#history_dd").hasClass("hover")) {
            $("#historycontainer").hide();
        }
        if (mw.$(".toolbars-search.active").length === 0) {
            mw.$(".mw-autocomplete-cats").hide();
        } else {
            mw.$(".mw-autocomplete-cats").show();
        }
        if (!mw.tools.hasClass(event.target, 'mw_handle_row') &&
            !mw.tools.hasParentsWithClass(event.target, 'mw_handle_row') &&
            !mw.tools.hasClass(event.target, 'mw-row') &&
            !mw.tools.hasParentsWithClass(event.target, 'mw-row')) {

            mw.$(".mw-row").each(function() {
                this.clicked = false;
            });
        }
        if (mw.tools.hasClass(event.target, 'mw-row')) {
            mw.$(".mw-row").each(function() {
                if (this !== event.target) {
                    this.clicked = false;
                }
            });
            event.target.clicked = true;
        } else if (mw.tools.hasParentsWithClass(event.target, 'mw-row')) {
            var row = mw.tools.firstParentWithClass(event.target, 'mw-row');
            mw.$(".mw-row").each(function() {
                if (this !== row) {
                    this.clicked = false;
                }
            });
            row.clicked = true;
        }
    });

    mw.$("#liveedit_wysiwyg").on('mousedown touchstart',function() {
        if (mw.$(".mw_editor_btn_hover").length == 0) {
            mw.mouseDownOnEditor = true;
            $(this).addClass("hover");
        }
    });
    mw.$("#liveedit_wysiwyg").on('mouseup touchend',function() {
        mw.mouseDownOnEditor = false;
        $(this).removeClass("hover");
    });

    $(document.body).on('mouseup touchend',function(event) {
        mw.target.item = event.target;
        mw.target.tag = event.target.tagName.toLowerCase();
        mw.mouseDownOnEditor = false;
        mw.SmallEditorIsDragging = false;
        if (!mw.image.isResizing &&
            mw.target.tag != 'img' &&
            event.target !== mw.image_resizer && !mw.tools.hasClass(mw.target.item.className, 'image_change') && !mw.tools.hasClass(mw.target.item.parentNode.className, 'image_change') && $(mw.image_resizer).hasClass("active")) {
            mw.image_resizer._hide();
        }
    });

    mw.tools.sidebar();

    if (typeof mw.hasDraft === 'object') {
        var html = "" +
            "<div class='hasdraft'>" +
            "<p>Load last Draft?</p>" +
            "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-green' onclick='mw.history.load(\"" + mw.hasDraft.draft + "\")'>Yes</span>" +
            "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-red' onclick='$(this.parentNode).remove();'>No</span>" +
            "</div>";

        mw.$("#mw_tabs_small").after(html);

        setTimeout(function() {
            mw.$(".hasdraft").addClass("active");
        }, 10000);

    }
    mw.toolbar.fixPad();
    /*  WYSIWYG */
    $(window).on("mouseup touchend", function(e) {

        var sel = window.getSelection();
        if (sel.rangeCount > 0) {
            var range = sel.getRangeAt(0),
                common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);

            if (mw.tools.hasClass(common, 'edit') || mw.tools.hasParentsWithClass(common, 'edit')) {
                var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
                if (nodrop_state) {
                    mw.wysiwyg.enableEditors();
                } else {
                    mw.wysiwyg.disableEditors();
                }
            } else {
                mw.wysiwyg.disableEditors();
            }
        }

        var sel = window.getSelection();
        if (sel.rangeCount > 0) {
            var r = sel.getRangeAt(0);

            var cac = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
        }

        // if((mw.tools.hasParentsWithClass(e.target, 'edit') || mw.tools.hasClass(e.target, 'edit') ||  mw.tools.hasParentsWithClass(e.target, 'mw-admin-editor-area')) && (sel.rangeCount > 0 && !sel.getRangeAt(0).collapsed)){
        if ((sel.rangeCount > 0) && ((mw.tools.hasParentsWithClass(cac, 'edit') || mw.tools.hasClass(cac, 'edit') || mw.tools.hasParentsWithClass(cac, 'mw-admin-editor-area')) && (sel.rangeCount > 0 && !sel.getRangeAt(0).collapsed))) {

            if (sel.rangeCount > 0 && ($.contains(e.target, cac) || $.contains(cac, e.target) || cac === e.target)) {
                setTimeout(function() {

                    var ep = mw.event.page(e);

                    if (cac.isContentEditable && !sel.isCollapsed && !mw.tools.hasClass(cac, 'plain-text') && !mw.tools.hasClass(cac, 'safe-element')) {
                        if (typeof(window.getSelection().getRangeAt(0).getClientRects()[0]) == 'undefined') {
                            return;
                        }
                        mw.smallEditorCanceled = false;
                        var top = ep.y - mw.smallEditor.height() - window.getSelection().getRangeAt(0).getClientRects()[0].height;
                        mw.smallEditor.css({
                            visibility: "visible",
                            opacity: 0.7,
                            top: (top > 55 ? top : 55),
                            left: ep.x + mw.smallEditor.width() < $(window).width() ? ep.x : ($(window).width() - mw.smallEditor.width() - 5)
                        });

                    } else {
                        mw.smallEditorCanceled = true;
                        mw.smallEditor.css({
                            visibility: "hidden"
                        });
                    }

                }, 33);
            }
        } else {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {

                if (typeof(mw.smallEditor) != 'undefined') {

                    mw.smallEditorCanceled = true;
                    mw.smallEditor.css({
                        visibility: "hidden"
                    });
                }
            }
        }
        setTimeout(function() {
            if (window.getSelection().rangecount > 0 && window.getSelection().getRangeAt(0).collapsed) {
                if (typeof(mw.smallEditor) != 'undefined') {
                    mw.smallEditorCanceled = true;
                    mw.smallEditor.css({
                        visibility: "hidden"
                    });
                }
            }
        }, 39);
    });
    mw.smallEditorOff = 150;

    $(window).on("mousemove touchmove touchstart", function(e) {
        if (!!mw.smallEditor && !mw.isDrag && !mw.smallEditorCanceled && !mw.smallEditor.hasClass("editor_hover")) {
            var off = mw.smallEditor.offset();
            var ep = mw.event.page(e);
            if (typeof off !== 'undefined') {
                if (
                    ((ep.x - mw.smallEditorOff) > (off.left + mw.smallEditor.width()))
                    || ((ep.y - mw.smallEditorOff) > (off.top + mw.smallEditor.height()))
                    || ((ep.x + mw.smallEditorOff) < (off.left)) || ((ep.y + mw.smallEditorOff) < (off.top))) {
                    if (typeof mw.smallEditor !== 'undefined') {
                        mw.smallEditor.css("visibility", "hidden");
                        mw.smallEditorCanceled = true;
                    }
                }
            }
        }
    });
    $(window).on("scroll", function(e) {
        if (typeof(mw.smallEditor) != "undefined") {
            mw.smallEditor.css("visibility", "hidden");
            mw.smallEditorCanceled = true;
        }
    });
    mw.$("#live_edit_toolbar, #mw_small_editor").on("mousedown", function(e) {
       $(".wysiwyg_external").empty()
        if (e.target.nodeName != 'INPUT' && e.target.nodeName != 'SELECT' && e.target.nodeName != 'OPTION' && e.target.nodeName != 'CHECKBOX') {
            e.preventDefault();
        }
        if (typeof(mw.smallEditor) != "undefined") {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            }
        }
    });
    mw.setLiveEditor();
    /*  /WYSIWYG */

});

$(window).resize(function() {
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
    mw.designTool.position();
    mw.setLiveEditor();
});

mw.preview = function() {
    var url = mw.url.removeHash(window.location.href);
    url = mw.url.set_param('preview', true, url);
    window.open(url, '_blank');
    window.focus();
};


mw.quick = {
    w: '80%',
    h: '90%',
    page: function() {
        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-page&recommended_parent=" + mw.settings.page_id,
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_page',
            overlay: true,
            title: 'New Page'
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    },
    category: function() {
        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=categories/edit_category&live_edit=true&quick_edit=false&id=mw-quick-category&recommended_parent=" + mw.settings.page_id,
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_page',
            overlay: true,
            title: 'New Category'
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    },
    edit: function(id, content_type, subtype, parent, category) {
        var str = "";

        if (parent) {
            str = "&recommended_parent=" + parent;
        }

        if (content_type) {
            str = str + '&content_type=' + content_type;
        }

        if (category) {
            str = str + '&category=' + category;
        }

        if (subtype) {
            str = str + '&subtype=' + subtype;
        }

        var actionType = '';

        if(id === 0){
            actionType = 'Add';
        }else{
            actionType = 'Edit';
        }

        var actionOf = 'Content';
        if(content_type === 'post'){
            actionOf = 'Post'
        }else if(content_type === 'page'){
            actionOf = 'Page'
        }else if(content_type === 'product'){
            actionOf = 'Product'
        }else if(content_type === 'category'){
            actionOf = 'Category'
        }

        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=content/edit&live_edit=true&quick_edit=false&is-current=true&id=mw-quick-page&content-id=" + id + str,
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_page',
            overlay: true,
            title: actionType + ' ' + actionOf
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    },
    page_2: function() {
        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=content/quick_add&live_edit=true&id=mw-new-content-add-ifame",
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_page',
            overlay: true,
            title: 'New Page'
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    },
    post: function() {
        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-post&subtype=post&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_post',
            overlay: true,
            title: 'New Post'
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    },
    product: function() {
        var modal = mw.tools.modal.frame({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-product&subtype=product&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: mw.quick.w,
            height: mw.quick.h,
            name: 'quick_product',
            overlay: true,
            title: 'New Product'
        });
        $(modal.main).addClass('mw-add-content-modal');
        modal.overlay.style.backgroundColor = "white";
    }
}


mw.beforeleave = function(url) {
    var beforeleave_html = "" +
        "<div class='mw-before-leave-container'>" +
        "<p>Leave page by choosing an option</p>" +
        "<span class='mw-ui-btn mw-ui-btn-important'>" + mw.msg.before_leave + "</span>" +
        "<span class='mw-ui-btn mw-ui-btn-notification' >" + mw.msg.save_and_continue + "</span>" +
        "<span class='mw-ui-btn' onclick='mw.tools.modal.remove(\"modal_beforeleave\")'>" + mw.msg.cancel + "</span>" +
        "</div>";
    if (mw.askusertostay && mw.$(".edit.orig_changed").length > 0) {
        if (mwd.getElementById('modal_beforeleave') === null) {
            var modal = mw.tools.modal.init({
                html: beforeleave_html,
                name: 'modal_beforeleave',
                width: 470,
                height: 230,
                template: 'mw_modal_basic'
            });

            var save = modal.container.querySelector('.mw-ui-btn-notification');
            var go = modal.container.querySelector('.mw-ui-btn-important');

            $(save).click(function() {
                $(mwd.body).addClass("loading");
                mw.tools.modal.remove(modal);
                mw.drag.save(undefined, function() {
                    mw.askusertostay = false;
                    window.location.href = url;
                });
            });
            $(go).click(function() {
                mw.askusertostay = false;
                window.location.href = url;
            });
        }

        return false;
    }
}
