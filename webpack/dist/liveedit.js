/******/ (() => { // webpackBootstrap
(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/beforeleave.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */


mw.liveedit.beforeleave = function(url) {
    var beforeleave_html = "" +
        "<div class='mw-before-leave-container'>" +
        "<p>Leave page by choosing an option</p>" +
        "<span class='mw-ui-btn mw-ui-btn-important'>" + mw.msg.before_leave + "</span>" +
        "<span class='mw-ui-btn mw-ui-btn-notification' >" + mw.msg.save_and_continue + "</span>" +
        "<span class='mw-ui-btn' onclick='mw.dialog.remove(\"modal_beforeleave\")'>" + mw.msg.cancel + "</span>" +
        "</div>";
    if (mw.askusertostay && mw.$(".edit.orig_changed").length > 0) {
        if (mwd.getElementById('modal_beforeleave') === null) {
            var modal = mw.dialog({
                html: beforeleave_html,
                name: 'modal_beforeleave',
                width: 470,
                height: 230,
                template: 'mw_modal_basic'
            });

            var save = modal.container.querySelector('.mw-ui-btn-notification');
            var go = modal.container.querySelector('.mw-ui-btn-important');

            mw.$(save).click(function() {
                mw.$(mwd.body).addClass("loading");
                mw.dialog.remove(modal);
                mw.drag.save(undefined, function() {
                    mw.askusertostay = false;
                    window.location.href = url;
                });
            });
            mw.$(go).click(function() {
                mw.askusertostay = false;
                window.location.href = url;
            });
        }

        return false;
    }
}

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/columns.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.drag = mw.drag || {};
mw.drag.columns = {
    step: 0.8,
    resizing: false,
    prepare: function () {
        mw.drag.columns.resizer = mwd.createElement('div');
        mw.wysiwyg.contentEditable(mw.drag.columns.resizer, false);
        mw.drag.columns.resizer.className = 'unselectable mw-columns-resizer';
        mw.drag.columns.resizer.pos = 0;
        mw.$(mw.drag.columns.resizer).on('mousedown', function () {
            mw.drag.columns.resizing = true;
            mw.drag.columns.resizer.pos = 0;
        });
        mwd.body.appendChild(mw.drag.columns.resizer);

        mw.$(mw.drag.columns.resizer).hide();
    },
    resize: function (e) {
        if (!mw.drag.columns.resizer.curr) return false;
        var w = parseFloat(mw.drag.columns.resizer.curr.style.width);
        var widthParentPixels;
        if (isNaN(w)) {
            w = mw.$(mw.drag.columns.resizer.curr).outerWidth();
            widthParentPixels = mw.$(mw.drag.columns.resizer.curr).parent().outerWidth();
            w = (w / widthParentPixels) * 100;
        }
        var next = mw.drag.columns.nextColumn(mw.drag.columns.resizer.curr);
        if(!next){
            mw.$(mw.drag.columns.resizer).hide();
            return false;
        }

        var w2 = parseFloat(next.style.width);
        if (isNaN(w2)) {
            w2 = mw.$(next).outerWidth();
            widthParentPixels = mw.$(next).parent().outerWidth();
            w2 = (w2 / widthParentPixels) * 100;
         }

        if (mw.drag.columns.resizer.pos < e.pageX) {
            if (w2 < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w - mw.drag.columns.step):(w + mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 + mw.drag.columns.step) : (w2 - mw.drag.columns.step);
            next.style.width =  calc + '%';
        }
        else {
            if (w < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w + mw.drag.columns.step):(w - mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 - mw.drag.columns.step) : (w2 + mw.drag.columns.step);
            next.style.width = calc + '%';
        }
        mw.drag.columns.resizer.pos = e.pageX;
        mw.drag.columns.position(mw.drag.columns.resizer.curr);
        mw.trigger('columnResize', mw.drag.columns.resizer.curr);
    },
    position: function (el) {
        if (!!mw.drag.columns.nextColumn(el)) {
            mw.drag.columns.resizer.curr = el;
            var off = mw.$(el).offset();
            mw.$(mw.drag.columns.resizer).css({
                top: off.top,
                left: mw.tools.isRtl() ? off.left - 10 : off.left + el.offsetWidth - 10,
                height: el.offsetHeight
            }).show();
        }
    },
    init: function () {
        mw.drag.columns.prepare();
        mw.on("ColumnOver", function (e, col) {
            mw.drag.columns.resizer.pos = 0;
            mw.drag.columns.position(col);
        });
        mw.on("ColumnOut", function (e, col) {
            mw.$(mw.drag.columns.resizer).hide();
        });

    },
    nextColumn: function (col) {
        var next = col.nextElementSibling;
        if (next === null) {
            return;
        }
        if (mw.tools.hasClass(next, 'mw-col')) {
            return next;
        }
        else {
            return mw.drag.columns.nextColumn(next);
        }
    }
}
$(mwd).ready(function () {
    mw.$(mwd.body).on('mouseup touchend', function () {
        if (mw.drag.plus.locked) {
            mw.wysiwyg.change(mw.drag.columns.resizer.curr);
        }
        mw.drag.columns.resizing = false;
        mw.drag.plus.locked = false;
        mw.tools.removeClass(mwd.body, 'mw-column-resizing');
    });
    mw.$(mwd.body).on('mousemove touchmove', function (e) {
        if (mw.drag.columns.resizing === true && mw.isDrag === false) {
            mw.drag.columns.resize(e);
            e.preventDefault();
            mw.drag.plus.locked = true;
            mw.tools.addClass(mwd.body, 'mw-column-resizing');
        }
    });
});

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/data.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.data = {
    _data:{},
    _target: null,
    init: function() {
        var scope = this;
        mw.$(document.body)
        .on('touchmove mousemove', function(e){
            var hasLayout = !!mw.tools.firstMatchesOnNodeOrParent(e.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
            mw.liveedit.data.set('move', 'hasLayout', hasLayout);
            mw.liveedit.data.set('move', 'hasModuleOrElement', mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['module', 'element']));
            if(scope._target !== e.target) {
                scope._target = e.target;
                mw.trigger('Liveedit');
            }
        })
        .on('mouseup touchend', function(e){
            mw.liveedit.data.set('mouseup', 'isIcon', mw.wysiwyg.firstElementThatHasFontIconClass(e.target));
        });


    },
    set: function(action, item, value){
        this._data[action] = this._data[action] || {};
        this._data[action][item] = value;
    },
    get: function(action, item){
        return this._data[action] ? this._data[action][item] : undefined;
    }
};

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/drag.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.drag = {
    _fixDeniedParagraphHierarchySelector: ''
    + '.edit p h1,.edit p h2,.edit p h3,'
    + '.edit p h4,.edit p h5,.edit p h6,'
    + '.edit p p,.edit p ul,.edit p ol,'
    + '.edit p header,.edit p form,.edit p article,'
    + '.edit p aside,.edit p blockquote,.edit p footer,.edit p div',
    fixDeniedParagraphHierarchy: function (root) {
        root = root || mwd.body;
        var all = root.querySelectorAll(mw.drag._fixDeniedParagraphHierarchySelector);
        if (all.length) {
            var i = 0;
            for ( ; i < all.length; i++ ) {
                var the_parent = mw.tools.firstParentWithTag(all[i], 'p');
                mw.tools.setTag(the_parent, 'div');
            }
        }
    },
    create_columns: function(selector, $numcols) {
        if (!$(selector).hasClass("active")) {

            mw.$(mw.drag.columns.resizer).hide();

            var id = mw._activeRowOver.id;

            mw.$(selector).addClass("active");
            var $el_id = id !== '' ? id : mw.settings.mw - row_id;

            mw.settings.sortables_created = false;
            var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").length;

            if ($numcols === 0) {
                $numcols = 1;
            }
            $numcols = parseInt($numcols);
            if ($exisintg_num === 0) {
                $exisintg_num = 1;
            }
            if ($numcols !== $exisintg_num) {
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
                            if (i === ($numcols - 1)) {
                                last_after_remove = mw.$(this);

                            } else {
                                if (i > ($numcols - 1)) {
                                    if (this.querySelector('.mw-col-container') !== null) {
                                        mw.tools.foreachChildren(this, function() {
                                            if (mw.tools.hasClass(this.className, 'mw-col-container')) {
                                                fragment.appendChild(this);
                                            }
                                        });
                                    }
                                    mw.$(this).remove();
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

                $exisintg_num = mw.$('#' + $el_id).children(".mw-col").size();
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
                if(dir === 'prev'){
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
            mw.$('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent()
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                var parser = mw.tools.parseHtml(mw.drag._onCloneableControl.__target.outerHTML).body;
                var all = parser.querySelectorAll('[id]'), i = 0;
                for( ; i<all.length; i++){
                    all[i].id = 'mw-cl-id-' + mw.random();
                }
                mw.$(mw.drag._onCloneableControl.__target).after(parser.innerHTML);
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-minus', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).fadeOut(function(){
                    mw.wysiwyg.change(this);
                    mw.$(this).remove();
                    mw.liveEditState.record({
                        target: $t[0],
                        value: $t[0].innerHTML
                    });
                });
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-next', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).next().after(mw.drag._onCloneableControl.__target)
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-prev', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).prev().before(mw.drag._onCloneableControl.__target)
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
        }
        var clc = mw.$(this._onCloneableControl);
        if(target == 'hide'){
            clc.hide();
        }
        else{
            clc.show();
            this._onCloneableControl.__target = target;

            var next = mw.$(this._onCloneableControl.__target).next();
            var prev = mw.$(this._onCloneableControl.__target).prev();
            var el = mw.$(target), off = el.offset();


            if(next.length == 0){
                mw.$('.mw-cloneable-control-next', clc).hide();
            }
            else{
                mw.$('.mw-cloneable-control-next', clc).show();
            }
            if(prev.length == 0){
                mw.$('.mw-cloneable-control-prev', clc).hide();
            }
            else{
                mw.$('.mw-cloneable-control-prev', clc).show();
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
    noop: mwd.createElement('div'),
    create: function() {

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

        mw.$(mwd.body).on('mousemove touchmove', function(event) {

            mw.dragSTOPCheck = false;
            if (!mw.settings.resize_started) {

                mw.emouse = mw.event.page(event);

                mw.mm_target = event.target;
                mw.$mm_target = mw.$(mw.mm_target);

                if (!mw.isDrag) {
                    if (mw.liveEditSelectMode === 'element') {
                        if(mw.tools.distance(mw.handlerMouse.x, mw.handlerMouse.y, mw.emouse.x, mw.emouse.y) > 20) {
                            mw.tools.removeClass(this, 'isTyping');
                            mw.handlerMouse = Object.assign({}, mw.emouse);
                            mw.liveEditHandlers(event)
                        }
                    }
                } else {
                    var sidebar = document.getElementById('live_edit_side_holder');
                    if(sidebar && sidebar.contains && sidebar.contains(mw.mm_target)){
                        mw.dropable.hide();
                        mw.ea.data.target = null;
                    } else {
                        mw.ea.data.currentGrabbed = mw.dragCurrent;
                        mw.tools.removeClass(this, 'isTyping');
                        mw.ea.interactionAnalizer(event);
                        mw.$(".currentDragMouseOver").removeClass("currentDragMouseOver");
                        mw.$(mw.currentDragMouseOver).addClass("currentDragMouseOver");
                    }

                }
            }
        });
        mw.dropables.prepare();

        mw.drag.fix_placeholders(true);
        mw.drag.fixes();

        mw.$(mwd.body).on('mouseup touchend', function(event) {
            mw.mouseDownStarted = false;
            if (mw.isDrag && mw.dropable.is(":hidden")) {
                mw.$(".ui-draggable-dragging").css({
                    top: 0,
                    left: 0
                });
            }
            mw.$(this).removeClass("not-allowed");
        });
        mw.$(mwd.body).on('mousedown touchstart', function(event) {
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
                mw.$(".desc_area").hide();
            }
            if (mw.tools.hasClass(event.target.className, 'mw-open-module-settings')) {

                if(!mw.settings.live_edit_open_module_settings_in_sidebar){
                    mw.drag.module_settings(mw.tools.firstParentOrCurrentWithAnyOfClasses(event.target, ['module']))
                } else {
                    var target = mw.tools.firstParentWithClass(event.target, 'module') ;
                    mw.liveNodeSettings.set('module', target);
                }
            }

            if (!mw.tools.hasParentsWithTag(event.target, 'TABLE') && !mw.tools.hasParentsWithClass(event.target, 'mw-inline-bar')) {
                mw.$(mw.liveedit.inline.tableControl).hide();
                mw.$(".tc-activecell").removeClass('tc-activecell');
            }
        });

    },

    init: function(selector, callback) {
        mw.drag.the_drop();
    },
    properFocus: function(event) {
        var tofocus;
        if (mw.tools.hasClass(event.target, 'mw-row') || mw.tools.hasClass(event.target, 'mw-col')) {
            if (mw.tools.hasClass(event.target, 'mw-col')) {
                tofocus = event.target.querySelector('.mw-col-container');
            } else {
                var i = 0,
                    cols = event.target.children,
                    l = cols.length;
                for (; i < l; i++) {
                    var _cleft = mw.$(cols[i]).offset().left;
                    var ePos = mw.event.page(event);
                    if (_cleft < ePos.x && (_cleft + cols[i].clientWidth) > ePos.x) {
                        tofocus = cols[i].querySelector('.mw-col-container');
                        if (tofocus === null) {
                            cols[i].innerHTML = '<div class="mw-col-container">' + cols[i].innerHTML + '</div>';
                        }
                        tofocus = cols[i].querySelector('.mw-col-container');
                        break;
                    }
                }
            }
            if (!!tofocus && tofocus.querySelector('.element') !== null) {
                var arr = tofocus.querySelectorAll('.element'),
                    l = arr.length;
                tofocus = arr[l - 1];

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
        mw.liveedit.modulesToolbar.init(selector);
    },
    the_drop: function() {



        if (!$(mwd.body).hasClass("bup")) {
            mw.$(mwd.body).addClass("bup");



            mw.$(mwd.body).on("mouseup touchend", function(event) {
                mw.image._dragcurrent = null;
                mw.image._dragparent = null;
                var sliders = mwd.getElementsByClassName("canvas-slider"),
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
                        'plain-text'
                    ];

                    var currentComponent = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, componentsClasses);
                    var fonttarget = mw.liveedit.data.get('mouseup', 'isIcon');

                    if( mw.tools.hasAnyOfClassesOnNodeOrParent(target, componentsClasses)) {
                        if (currentComponent && !fonttarget) {

                            var order = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['safe-mode', 'module']);
                            if(mw.tools.hasClass(currentComponent, 'module')){
                                mw.trigger("ComponentClick", [target, 'module']);
                            }
                            else if (mw.wysiwyg.isSelectionEditable() && !mw.tools.hasAnyOfClasses(target, componentsClasses) && order) {
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

                    if (fonttarget && !mw.tools.hasAnyOfClasses(target, ['element', 'module'])) {
                        if ((fonttarget.tagName === 'I' || fonttarget.tagName === 'SPAN') && !mw.tools.hasParentsWithClass(fonttarget, 'dropdown')) {
                            if(mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(fonttarget, ['edit', 'module'])) {
                                mw.trigger("IconElementClick", fonttarget);
                                mw.trigger("ComponentClick", [fonttarget, 'icon']);
                            }
                        }
                    }

                    if ($(target).hasClass("mw_item")) {
                        mw.trigger("ItemClick", target);
                    } else if (mw.tools.hasParentsWithClass(target, 'mw_item')) {
                        mw.trigger("ItemClick", mw.$(target).parents(".mw_item")[0]);
                    }
                    if (target.tagName === 'IMG' && mw.tools.hasParentsWithClass(target, 'edit')) {
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


                    var isTd =  target.tagName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    if(!!isTd){
                        if(mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['edit', 'module'])){
                            mw.trigger("TableTdClick", target);
                        }
                    }

                    if (mw.tools.hasClass(target, 'mw-empty') || mw.tools.hasParentsWithClass(target, 'mw-empty')) {

                    } else {

                    }
                    if (mw.tools.hasClass(target, 'mw-empty') && target.innerHTML.trim() !== '') {
                        target.className = 'element';
                    }
                    mw.drag.properFocus(event);
                }
                if (mw.isDrag) {
                    mw.isDrag = false;


                    mw.wysiwyg.change(mw.currentDragMouseOver);
                    mw.$(mw.currentDragMouseOver).removeClass("currentDragMouseOver");

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
                                    mw.$(prevDoc.querySelector('.mw_drag_current')).css({
                                        visibility: 'hidden',
                                        opacity: 0
                                    });
                                    rec.prev = prev;
                                    rec.prevValue = prevDoc.body.innerHTML;
                                }

                                mw.liveEditState.record(rec);
                                mw.$(mw.ea.data.target)[mw.ea.data.dropableAction](mw.ea.data.currentGrabbed);




                                setTimeout(function(ed) {
                                    var nrec = {
                                        target: ed,
                                        value: ed.innerHTML
                                    };
                                    if(prev){
                                        var prevDoc = mw.tools.parseHtml(prev.innerHTML);
                                        mw.$(prevDoc.querySelector('.mw_drag_current')).css({
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
                            if(mw.liveEditDomTree) {
                                mw.liveEditDomTree.refresh(mw.ea.data.target.parentNode)
                            }
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
        mw.$(mw.dragCurrent).css({
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
                var el = mw.$(this);
                if (el.children().length === 0 || (el.children('.empty-element').length > 0) || el.children('.ui-draggable-dragging').length > 0) {
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
                mw.drag.fixDeniedParagraphHierarchy();

            });
        }, 222);

        var els = mwd.querySelectorAll('div.element'),
            l = els.length,
            i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                if (els[i].querySelector('p,div,li,h1,h2,h3,h4,h5,h6,figure,img') === null && !mw.tools.hasClass(els[i], 'plain-text')) {
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
        mw.$(selector).each(function() {
            var el = mw.$(this);
            el.children(more_selectors2).each(function() {
                var empty_child = mw.$(this).children('*');
                if (empty_child.size() == 0) {
                    mw.$(this).append('<div class="element" id="mw-element-' + mw.random() + '">' + '</div>');
                    var empty_child = mw.$(this).children("div.element");
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

            return mw.tools.hasAnyOfClasses(el, noelements);
        },
        canBeEditable: function(el) {
            return el.isContentEditable || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit','module']);
        }
    },

    fancynateLoading: function(module) {
        mw.$(module).addClass("module_loading");
        setTimeout(function() {
            mw.$(module).addClass("module_activated");
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
        var tooltip_element = mw.$("#" + element_id);
        var attributes = {};
        var type = type || 'modal';
        $.each(curr.attributes, function(index, attr) {
            attributes[attr.name] = attr.value;
        });
        var data1 = attributes;
        var module_type = null;
        if (data1['data-type']) {
            module_type = data1['data-type'];
            data1['data-type'] = data1['data-type'] + '/admin';
        }
        if (data1['data-module-name']) {
            delete(data1['data-module-name']);
        }
        if (data1['type']) {
            module_type = data1['type'];
            data1['type'] = data1['type'] + '/admin';
        }
        if (module_type != null && view) {
            data1['data-type'] = data1['type'] = module_type + '/' + view;
        }

        if (data1['class']) {
            delete(data1['class']);
        }
        if (data1['style']) {
            delete(data1['style']);
        }
        if (data1.contenteditable) {
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
            var modal = mw.top().dialogIframe({
                url: src,
                width: 532,
                height: 150,
                name: modal_name,
                title: '',
                callback: function() {
                    mw.$(this.container).attr('data-settings-for-module', curr.id);
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
        mw.$($update_element).load_modules(url1, attributes, function() {
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
                mw.$(mw.handleModule).removeClass('mw-active-item');
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
        mw.$("grammarly-btn", data).remove();
        mw.$("grammarly-card", data).remove();
        mw.$("g.gr_", data).each(function(){
            mw.$(this).replaceWith(this.innerHTML);
        });
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("[data-gramm]", data).removeAttr('data-gramm');
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("grammarly-card", data).remove();
        mw.$("grammarly-inline-cards", data).remove();
        mw.$("grammarly-popups", data).remove();
        mw.$("grammarly-extension", data).remove();
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
            dataType: "json",
            success: function (saved_data) {
                if(saved_data && saved_data.new_page_url && !mw.drag.DraftSaving){
                    window.parent.mw.askusertostay = false;
                    window.mw.askusertostay = false;
                    window.location.href  = saved_data.new_page_url;

                }
            }
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
        mw.$('.module-cat-toggle-Modules', doc).removeClass('module-cat-toggle-Modules');
        mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
        mw.$('-module', doc).removeClass('-module');
        mw.$('.empty-element', doc).remove();
        mw.$('.empty-element', doc).remove();
        mw.$('.edit .ui-resizable-handle', doc).remove();
        mw.$('script', doc).remove();

        //var doc = mw.$(doc).find('script').remove();

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
                var content = mw.wysiwyg.cleanUnwantedTags(helper.item).innerHTML;
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
        if (mw.liveedit.cssEditor) {
            mw.liveedit.cssEditor.publishIfChanged();
        }
        if (mw.drag.saveDisabled) return false;
        if(!data){
            var body = mw.drag.parseContent().body,
                edits = body.querySelectorAll('.edit.changed');
            data = mw.drag.collectData(edits);
        }



        if (mw.tools.isEmptyObject(data)) return false;

        mw._liveeditData = data;

        mw.trigger('saveStart', mw._liveeditData);

        var xhr = mw.drag.coreSave(mw._liveeditData);
        xhr.error(function(){

            if(xhr.status == 403){
                var modal = mw.dialog({
                    id : 'save_content_error_iframe_modal',
                    html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
                    width:$(window).width() - 90,
                    height:$(window).height() - 90
                });

                mw.askusertostay = false;

                mw.$("#save_content_error_iframe").ready(function() {
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
                    doc.open();
                    doc.write(xhr.responseText);
                    doc.close();
                    var save_content_error_iframe_reloads = 0;
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;

                    mw.$("#save_content_error_iframe").load(function(){
                        // cloudflare captcha
                        var is_cf =  mw.$('.challenge-form',doc).length;
                        save_content_error_iframe_reloads++;

                        if(is_cf && save_content_error_iframe_reloads == 2){
                            setTimeout(function(){
                                mw.askusertostay = false;
                                mw.$('#save_content_error_iframe_modal').remove();
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

})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/edit.fields.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.editFields = {
    handleKeydown: function() {
        mw.$('.edit').on('keydown', function(e){
            var istab = (e.which || e.keyCode) === 9,
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
                        var split = mw.wysiwyg.listSplit(parent, mw.$('li', parent).index(target));

                        var parentLi = parent.parentNode;
                        mw.$(parentLi).after(split.middle);
                        if(!!split.top){
                            mw.$(parentLi).append(split.top);
                        }
                        if(!!split.bottom){
                            mw.$(split.middle).append(split.bottom);
                        }

                        mw.$(parent).remove();
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
    }
}

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/editors.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.editors = {
  prepare: function() {
      mw.$(window).on("mouseup touchend", function(e) {

          var sel = getSelection();
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

          sel = window.getSelection();
          if (sel.rangeCount > 0) {
              var r = sel.getRangeAt(0);
              var cac = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
          }
          if (mw.tools.hasAnyOfClassesOnNodeOrParent(cac, ['edit', 'mw-admin-editor-area']) && (sel.rangeCount > 0 && !sel.getRangeAt(0).collapsed)) {

              if ($.contains(e.target, cac) || $.contains(cac, e.target) || cac === e.target) {
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
                              left: ep.x + mw.smallEditor.width() < mw.$(window).width() ? ep.x : ($(window).width() - mw.smallEditor.width() - 5)
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
              if (mw.smallEditor && !mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                      mw.smallEditorCanceled = true;
                      mw.smallEditor.css({
                          visibility: "hidden"
                      });
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

      mw.$(window).on("mousemove touchmove touchstart", function(e) {
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
      mw.$(window).on("scroll", function(e) {
          if (typeof(mw.smallEditor) !== "undefined") {
              mw.smallEditor.css("visibility", "hidden");
              mw.smallEditorCanceled = true;
          }
      });
      mw.$("#live_edit_toolbar, #mw_small_editor").on("mousedown touchstart", function(e) {
          e.preventDefault();
          mw.$(".wysiwyg_external").empty();
          if (e.target.nodeName !== 'INPUT' && e.target.nodeName !== 'SELECT' && e.target.nodeName !== 'OPTION' && e.target.nodeName !== 'CHECKBOX') {
              e.preventDefault();
          }
          if (typeof(mw.smallEditor) !== "undefined") {
              if (!mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                  mw.smallEditor.css("visibility", "hidden");
                  mw.smallEditorCanceled = true;
              }
          }
      });
  }
};

})();

(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/element_analyzer.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.AfterDrop = function(){



    this.loadNewModules = function(){
        mw.pauseSave = true;
        var need_re_init = false;
        var all = mw.$(".edit .module-item"), count = 0;
        all.each(function(c) {
            (function (el) {
                var parent = el.parentNode;
                var xhr = mw._({
                    selector: el,
                    done: function(module) {
                        mw.drag.fancynateLoading(module);
                        mw.pauseSave = false;
                        mw.wysiwyg.init_editables();
                        if(mw.liveEditDomTree) {
                            mw.liveEditDomTree.refresh(parent);
                            mw.liveEditDomTree.select(parent);

                        }
                    },
                    fail:function () {
                        mw.$(this).remove();
                        mw.notification.error('Error loading module.');
                    }
                }, true);
               if(xhr) {
                   xhr.always(function () {
                       count++;
                       if(all.length === count) {
                           mw.dragCurrent = null;
                       }
                   });
               }
               else {
                   count++;
               }

                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items === true) {
            need_re_init = true;
        }
        if (need_re_init === true) {
            if (!mw.isDrag) {
                if (typeof callback === 'function') {
                    callback.call(this);
                }
            }
        }
        mw.have_new_items = false;
    };

    this.__timeInit = null;

    this.init = function(){
        var scope = this;
        if(scope.__timeInit){
           clearTimeout(scope.__timeInit);
        }
        scope.__timeInit = setTimeout(function(){

            mw.$(".mw-drag-current-bottom, .mw-drag-current-top").removeClass('mw-drag-current-bottom mw-drag-current-top');
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver');

            mw.$(".mw_drag_current").each(function(){
                mw.$(this).removeClass('mw_drag_current').css({
                    visibility:'visible',
                    opacity:''
                });
            });
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver')
            mw.$(".mw-empty").not(':empty').removeClass('mw-empty');
            scope.loadNewModules()
            mw.dropable.hide().removeClass('mw_dropable_onleaveedit');

        }, 78)
    }


}


/*************************************************************


        Options: Object literal

        Default: {
            classes:{
                edit:'edit',
                element:'element',
                module:'module',
                noDrop:'nodrop', // - disable drop
                allowDrop:'allow-drop' //- enable drop in .nodrop
            }
        }



    mw.analizer = new mw.ElementAnalizer(Options);




*************************************************************/

mw.ElementAnalyzer = function(options){



    this.data = {
        dropableAction:null,
        currentGrabbed:null,
        target:null,
        dropablePosition:null
    };

    this.dataReset = function(){
        this.data = {
            dropableAction:null,
            currentGrabbed:null,
            target:null,
            dropablePosition:null
        }
    };

    this.options = options || {};
    this.defaults = {
        classes:{
            edit: 'edit',
            element: 'element',
            module: 'module',
            noDrop: 'nodrop',
            allowDrop: 'allow-drop',
            emptyElement: 'mw-empty',
            zone: 'mw-zone'
        },
        rows:['mw-row', 'mw-ui-row', 'row'],
        columns:['mw-col', 'mw-ui-col', 'col', 'column', 'columns'],
        columnMatches:'[class*="col-"]',
        rowMatches:'[class*="row-"]',
    };
    this.settings = $.extend({}, this.options, this.defaults);

    this.prepare = function(){
        this.cls = this.settings.classes;
        this.initCSS();
    };

    this.initCSS = function(){
        var css = 'body.dragStart .'+this.cls.noDrop+'{'
            +'pointer-events: none;'
        +'}'
        +'body.dragStart .'+this.cls.allowDrop+'{'
            +'pointer-events: all;'
        +'}';

        var style = mwd.createElement('style');
        mwd.getElementsByTagName('head')[0].appendChild(style);
        style.innerHTML = css;
    };


    this._isEditLike = function(node){
        node = node || this.data.target;
        var case1 = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.cls.edit,this.cls.module]);
        var case2 = mw.tools.hasClass(node, 'module') && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, [this.cls.edit,this.cls.module]);
        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, this.cls.edit);
        return (case1 || case2) && !mw.tools.hasClass(edit, this.cls.noDrop);
    };
    this._canDrop = function(node) {
        node = node || this.data.target;
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.cls.allowDrop, this.cls.noDrop]);
    };

    this._layoutInLayout = function() {
        if (!this.data.currentGrabbed || !mwd.body.contains(this.data.currentGrabbed)) {
            return false;
        }
        var currentGrabbedIsLayout = (this.data.currentGrabbed.getAttribute('data-module-name') === 'layouts' || mw.dragCurrent.getAttribute('data-type') === 'layouts');
        var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(this.data.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
        return {
            target:targetIsLayout,
            result:currentGrabbedIsLayout && !!targetIsLayout
        };
    };

    this.canDrop = function(node){
        node = node || this.data.target;
        var can = (this._isEditLike(node) && this._canDrop(node) && !this._layoutInLayout().result);
        return can;
    };



    this.analizePosition = function(event, node){
        node = node || this.data.target;
        var height = node.offsetHeight,
            offset = mw.$(node).offset();
        if (mw.event.page(event).y > offset.top + (height / 2)) {
            this.data.dropablePosition =  'bottom';
        } else {
            this.data.dropablePosition =  'top';
        }
    };

    this.analizeActionOfElement = function(node, pos){
        node = node || this.data.target;
        pos = node || this.data.dropablePosition;
    };
    this.afterAction = function(node, pos){
        if(!this._afterAction){
            this._afterAction = new mw.AfterDrop();
        }

        this._afterAction.init();

    };
    this.dropableHide = function(){

    };
    this.analizeAction = function(node, pos){
        node = node || this.data.target;
        pos = pos || this.data.dropablePosition;
        if(this.helpers.isEmpty()){
            this.data.dropableAction = 'append';
            return;
        }
        var actions =  {
            Around:{
                top:'before',
                bottom:'after'
            },
            Inside:{
               top:'prepend',
               bottom:'append'
            }
        };

        if(!pos){
            return;
        }



        if(mw.tools.hasClass(node, 'allow-drop')){
            this.data.dropableAction = actions.Inside[pos];
        }
        else if(this.helpers.isElement()){
            this.data.dropableAction = actions.Around[pos];
        }
        else if(this.helpers.isEdit()){
            this.data.dropableAction = actions.Inside[pos];
        }
        else if(this.helpers.isLayoutModule()){
            this.data.dropableAction = actions.Around[pos];
        }
        else if(this.helpers.isModule()){
            this.data.dropableAction = actions.Around[pos];
        }
    };

    this.action = function(event){
        var node = event.target;
        var final = {};
        if(this._isEditLike(node)){
            if(this._canDrop(node)){

            }
        }
    };



    this.helpers = {
        scope:this,
        isBlockLevel:function(node){
            node = node || (this.data ? this.data.target : null);
            return mw.tools.isBlockLevel(node);
        },
        isInlineLevel:function(node){
            node = node || this.data.target;
            return mw.tools.isInlineLevel(node);
        },
        canAccept:function(target, what){
            var accept = target.dataset('accept');
            if(!accept) return true;
            accept = accept.trim().split(',').map(Function.prototype.call, String.prototype.trim);
            var wtype = 'all';
            if(mw.tools.hasClass(what, 'module-layout')){
                wtype = 'layout';
            }
            else if(mw.tools.hasClass(what, 'module')){
                wtype = 'module';
            }
            else if(mw.tools.hasClass(what, 'element')){
                wtype = 'element';
            }
            if(wtype=='all') return true

            return accept.indexOf(wtype) !== -1;
        },
        getBlockElements:function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0; final = [];
            for( ; i<all.length; i++){
                if(this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i])
                }
            }
            return final;
        },
        getElementsLike:function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0; final = [];
            for( ; i<all.length; i++){
                if(!this.scope.helpers.isColLike(all[i]) &&
                    !this.scope.helpers.isRowLike(all[i]) &&
                    !this.scope.helpers.isEdit(all[i]) &&
                    this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i]);
                }
            }
            return final;
        },
        isEdit:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.edit);
        },
        isModule:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.module) && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.scope.cls.module, this.scope.cls.edit]));
        },
        isElement:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.element);
        },
        isEmpty:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, 'mw-empty');
        },
        isRowLike:function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.rows);
            if(is){
                return is;
            }
            return mw.tools.matches(node, this.scope.settings.rowMatches);
        },
        isColLike:function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.columns);
            if(is){
                return is;
            }
            if(mw.tools.hasAnyOfClasses(node, ['mw-col-container', 'mw-ui-col-container'])){
                return false;
            }
            return mw.tools.matches(node, this.scope.settings.columnMatches);
        },
        isLayoutModule:function(node){
            node = node || this.scope.data.target;
            return false;

        },
        noop:function(){}
    };


    this.interactionTarget = function(next){
        node = this.data.target;
        if(next) node = node.parentNode;
        while(node && !this.helpers.isBlockLevel(node)){
            node = node.parentNode;
        }
        return node;
    };
    this.validateInteractionTarget = function(node){
        node = node || this.data.target;
        if (!mw.tools.firstParentOrCurrentWithClass(node, this.cls.edit)) {
           return false;
        }
        var cls = [
            this.cls.edit,
            this.cls.element,
            this.cls.module,
            this.cls.emptyElement
        ];
        while(node && node !== mwd.body){
            if(mw.tools.hasAnyOfClasses(node, cls)){
                return node;
            }
            node = node.parentNode;
        }
        return false;
    };
    this.on = function(events, listener) {
        events = events.trim().split(' ');
        for (var i=0 ; i<events.length; i++) {
             document.body.addEventListener(events[i], listener, false);
        }
    };
    this.loadNewModules = function(){
        mw.pauseSave = true;
        var need_re_init = false;
        mw.$(".edit .module-item").each(function(c) {

            (function (el) {
                var xhr = mw._({
                    selector: el,
                    done: function(module) {
                        mw.drag.fancynateLoading(module);
                        mw.pauseSave = false;
                        mw.wysiwyg.init_editables();
                    },
                    fail:function () {
                        mw.$(this).remove();
                        mw.notification.error('Error loading module.')
                    }
                }, true);
                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items === true) {
            need_re_init = true;
        }
        mw.have_new_items = false;
    };
    this.whenUp = function(){
        var scope = this;
        this.on('mouseup touchend', function(){
            if(scope.data.currentGrabbed){
                scope.data.currentGrabbed = null;
            }
        });
    };

    this.getTarget = function(t){
        t = t || this.validateInteractionTarget();
        if(!t){
            return;
        }
        if (this.canDrop(t)) {
            return t;
        } else {
            return this.redirect(t);
        }
    };

    this.redirect = function(node){
        node = node || this.data.target;
        var islayOutInLayout = this._layoutInLayout(node);
        if(islayOutInLayout.result){
            var res =  this.validateInteractionTarget(/*node === islayOutInLayout.target ? islayOutInLayout.target.parentNode : */islayOutInLayout.target);
            return  res;
        }
        if(node === mwd.body || node.parentNode === mwd.body) return null;
        return this.getTarget(node.parentNode);
    };

    this.interactionAnalizer = function(e){

        var scope = this;
        mw.dropable.hide();

        if(this.data.currentGrabbed){
            if (e.type.indexOf('touch') !== -1) {
                var coords = mw.event.page(e);
                scope.data.target = mwd.elementFromPoint(coords.x, coords.y);
            }
            else {
                scope.data.target = e.target;
            }
            scope.interactionTarget();
            scope.data.target = scope.getTarget();

            if(scope.data.target){
                    scope.analizePosition(e);
                    scope.analizeAction();
                    mw.dropable.show();
            }
            else{

                    var near = mw.dropables.findNearest(e);
                    if(near.element){
                        scope.data.target = near.element;
                        scope.data.dropablePosition = near.position;
                        mw.dropables.findNearestException = true;
                        mw.dropable.show();
                    }
                    else{
                        mw.currentDragMouseOver = null;
                        mw.dropable.hide();
                        scope.dataReset();

                    }

            }

            var el = mw.$(scope.data.target);
            mw.currentDragMouseOver = scope.data.target;

            var edit = mw.tools.firstParentOrCurrentWithClass(mw.currentDragMouseOver, 'edit');
            mw.tools.classNamespaceDelete(mw.dropable[0], 'mw-dropable-tagret-rel-');
            if(edit) {
                mw.tools.addClass(mw.dropable[0], 'mw-dropable-tagret-rel-' + edit.getAttribute('rel'));
                var rel = edit.getAttribute('rel');
                mw.tools.addClass(mw.dropable[0], 'mw-dropable-tagret-rel-' + rel);
            }

            mw.dropables.set(scope.data.dropablePosition, el.offset(), el.height(), el.width());

            if(el[0] && !mw.tools.hasAnyOfClasses(el[0], ['mw-drag-current-'+scope.data.dropablePosition])){
                mw.$('.mw-drag-current-top,.mw-drag-current-bottom').removeClass('mw-drag-current-top mw-drag-current-bottom');
                mw.tools.addClass(el[0], 'mw-drag-current-'+scope.data.dropablePosition)
            }
        }
    };

    this.whenMove = function(){
        var scope = this;
        this.on('mousemove touchmove', function(e){
            scope.interactionAnalizer(e)
        });
    };
    this.init = function(){
        this.prepare();
    };

    this.init();
};

mw.ea = new mw.ElementAnalyzer();

})();

(() => {
/*!*********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/events.custom.js ***!
  \*********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.handleCustomEvents = function() {
    mw.on('moduleOver ElementOver', function(e, etarget, oevent){
        var target = mw.tools.firstParentOrCurrentWithAnyOfClasses(oevent.target, ['element', 'module']);
        if(target.id){
            mw.liveEditSelector.active(true);
            mw.liveEditSelector.setItem(target, mw.liveEditSelector.interactors);
        }
    });

    mw.$(document.body).on('click', function (e) {
        var target = e.target;
        var can = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, [
           'edit', 'module', 'element'
        ]);
        if(can) {
            var toSelect = mw.tools.firstNotInlineLevel(target);

            mw.liveEditSelector.select(target);

            if(mw.liveEditDomTree) {
                mw.liveEditDomTree.select(mw.wysiwyg.validateCommonAncestorContainer(target));

            }
        }


    });


    mw.on("DragHoverOnEmpty", function(e, el) {
        if ($.browser.webkit) {
            var _el = mw.$(el);
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
        mw.editorIconPicker.tooltip(el)
        setTimeout(function () {
            mw.wysiwyg.contentEditable(el, false);
        });
    });

    mw.on("ComponentClick", function(e, node, type){

        if (type === 'icon'){
            mw.editorIconPicker.tooltip(node)
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
                if (mw.sidebarSettingsTabs) {
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
        mw.$(".element-current").not(el).removeClass('element-current');
        if (mw.liveEditSelectMode === 'element') {
            mw.$(el).addClass('element-current');
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


    mw.on("editUserIsTypingForLong", function(node){
        if(typeof(mw.liveEditSettings) != 'undefined'){
            if(mw.liveEditSettings.active){
                mw.liveEditSettings.hide();
            }
        }
    });
    mw.on("TableTdClick", function(e, el) {
        if (mw.liveedit && mw.liveedit.inline) {
            mw.liveedit.inline.setActiveCell(el, e);
            var td_parent_table = mw.tools.firstParentWithTag(el, 'table');
            if (td_parent_table) {
                mw.liveedit.inline.tableController(td_parent_table);
            }
        }
    });

    mw.on('UserInteraction', function(){
        mw.dropables.userInteractionClasses();
        mw.liveEditSelector.positionSelected();

    });

    mw.on('ElementOver moduleOver', function(e, target){
        var over_target_el = null;
        if(e.type === 'onElementOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['element'])
            if(over_target_el && !mw.tools.hasClass('element-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'element-over')
            }
        } else if(e.type === 'moduleOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['module'])
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
                    mw.$("#moduleinbetween").fadeOut(function(){
                        mw.$(this).remove();
                    });
                }, 3000);
            }
        }, 1000);
    });
};

})();

(() => {
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/events.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.handleEvents = function() {
    mw.$(document.body).on('touchmove mousemove', function(e){
        if(mw.liveEditSelector.interactors.active) {
            if( !mw.liveedit.data.get('move', 'hasModuleOrElement')){
                if(e.target !== mw.drag.plusTop && e.target !== mw.drag.plusBottom) {
                    mw.liveEditSelector.hideItem(mw.liveEditSelector.interactors);
                }
            }
        }
    });
    mw.$("#live-edit-dropdown-actions-content a").off('click');

    mw.$(document).on('mousedown touchstart', function(e){
        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-defaults', 'edit', 'element'])){
            mw.$(".element-current").removeClass("element-current");
        }
    });

    mw.$("span.edit:not('.nodrop')").each(function(){
        mw.tools.setTag(this, 'div');
    });


    mw.$("#mw-toolbar-css-editor-btn").click(function() {
        mw.liveedit.widgets.cssEditorDialog();
    });
    mw.$("#mw-toolbar-html-editor-btn").click(function() {
        mw.liveedit.widgets.htmlEditorDialog();
    });

    mw.$("#mw-toolbar-reset-content-editor-btn").click(function() {
        mw.tools.open_reset_content_editor();
    });
    mw.$(mwd.body).on('keyup', function(e) {
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

    mw.$(mwd.body).on("keydown", function(e) {

        if (e.keyCode === 83 && e.ctrlKey) {

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

    mw.$(mwd.body).on("paste", function(e) {
        if(mw.tools.hasClass(e.target, 'plain-text')){
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertHTML", false, text);
        }
    });

    mw.$(mwd.body).on("mousedown mouseup touchstart touchend", function(e) {

        if (e.type === 'mousedown' || e.type === 'touchstart') {
            if (!mw.wysiwyg.elementHasFontIconClass(e.target)
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['tooltip-icon-picker', 'mw-tooltip'])) {

                mw.editorIconPicker.tooltip('hide')
                try{
                    $(mw.liveedit.widgets._iconEditor.tooltip).hide();
                }catch(e){

                }

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
    mw.$('span.mw-powered-by').on("click", function(e) {
        mw.tools.open_global_module_settings_modal('white_label/admin', 'mw-powered-by');
        return false;
    });

    mw.$(".edit a, #mw-toolbar-right a").click(function() {
        var el = this;
        if (!el.isContentEditable) {
            if (el.onclick === null) {
                var href = (el.getAttribute('href') || '').trim();
                if(href) {
                    if (!(href.indexOf("javascript:") === 0 || href.indexOf('#') === 0)) {
                        return mw.liveedit.beforeleave(this.href);
                    }
                }
            }
        }
    });

};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/handles.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.require('selector.js');

var dynamicModulesMenuTime = null;
var dynamicModulesMenu = function(e, el) {
    if(!mw.inaccessibleModules){
        mw.inaccessibleModules = document.createElement('div');
        mw.inaccessibleModules.className = 'mw-ui-btn-nav mwInaccessibleModulesMenu';
        document.body.appendChild(mw.inaccessibleModules);
        mw.$(mw.inaccessibleModules).on('mouseenter', function(){
            this._hovered = true;
        }).on('mouseleave', function(){
            this._hovered = false;
        });
    }

    var $el;
    if(e.type === 'moduleOver' || e.type === 'ModuleClick'){

        var parentModule = mw.tools.lastParentWithClass(el, 'module');
        var childModule = mw.tools.firstChildWithClass(el, 'module');

        $el = mw.$(el);
        if(!!parentModule && ( $el.offset().top - mw.$(parentModule).offset().top) < 10 ){
            el.__disableModuleTrigger = parentModule;
            $el.addClass('inaccessibleModule');
        }
        else if(!!childModule && ( mw.$(childModule).offset().top - $el.offset().top) < 10 ) {
            childModule.__disableModuleTrigger = el;
            mw.$(childModule).addClass('inaccessibleModule');
        }
        else{
            $el.removeClass('inaccessibleModule');
        }
    }


    var modules = mw.$(".inaccessibleModule", el);
    if(modules.length === 0){
        var parent = mw.tools.firstParentWithClass(el, 'module');
        if(parent){
            if(($el.offset().top - mw.$(parent).offset().top) < 10) {
                modules = mw.$([el]);
                el = parent;
                $el = mw.$(el);

            }
        }
    }
    if (e.type === 'ModuleClick') {
        mw.liveEditSelector.select(el);
    }

    if(modules.length && !mw.inaccessibleModules._hovered) {
        mw.inaccessibleModules.innerHTML = '';
    }
    modules.each(function(){
        var span = document.createElement('span');
        span.className = 'mw-handle-item mw-ui-btn mw-ui-btn-small';
        var type = mw.$(this).attr('data-type') || mw.$(this).attr('type');
        if(type){
            var title = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : type;
            title = title.replace(/\_/, ' ');
            span.innerHTML = mw.live_edit.getModuleIcon(type) + title;
            var el = this;
            span.onclick = function(){
                mw.tools.module_settings(el);
            };
            mw.inaccessibleModules.appendChild(span);
        }
    });
    if(modules.length > 0){
        var off = mw.$(el).offset();
        if(mw.tools.collision(el, mw.handleModule.wrapper)){
            off.top = parseFloat(mw.handleModule.wrapper.style.top) + 30;
            off.left = parseFloat(mw.handleModule.wrapper.style.left);
        }
        mw.inaccessibleModules.style.top = off.top + 'px';
        mw.inaccessibleModules.style.left = off.left + 'px';
        clearTimeout(dynamicModulesMenuTime);
        mw.$(mw.inaccessibleModules).show();
    }
    else{
        dynamicModulesMenuTime = setTimeout(function(){
            if(!mw.inaccessibleModules._hovered) {
                mw.$(mw.inaccessibleModules).hide();
            }

        }, 3000);

    }


    return $el[0];

};

var handleDomtreeSync = {};

mw.Handle = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };

    this.createWrapper = function() {
        this.wrapper = mwd.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handle-' + mw.random());
        this.wrapper.className = 'mw-defaults mw-handle-item ' + (this.options.className || 'mw-handle-type-default');
        this.wrapper.contenteditable = false;
        mw.$(this.wrapper).on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        mw.$(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        mwd.body.appendChild(this.wrapper);
    };

    this.create = function() {
        this.createWrapper();
        this.createHandler();
        this.createMenu();
    };

    this.setTitle = function (icon, title) {
        this.handleIcon.innerHTML = icon;
        this.handleTitle.innerHTML = title;
    };

    this.hide = function () {
        mw.$(this.wrapper).hide().removeClass('active');
        this._visible = false;
        return this;
    };

    this.show = function () {
        mw.$(this.wrapper).show();
        this._visible = true;
        return this;
    };

    this.createHandler = function(){
        this.handle = mwd.createElement('span');
        this.handleIcon = mwd.createElement('span');
        this.handleTitle = mwd.createElement('span');
        this.handle.className = 'mw-handle-handler';
        this.handleIcon.className = 'mw-handle-handler-icon';
        this.handleTitle.className = 'mw-handle-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            mw.$(scope.wrapper).toggleClass('active');
        };
        mw.$(mwd.body).on('click', function (e) {
            if(!mw.tools.hasParentWithId(e.target, scope.wrapper.id)){
                mw.$(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = mwd.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var icon = mwd.createElement('span');
            icon.className = data.icon + ' mw-handle-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(mwd.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(data.action){
            btn.onmousedown = function (e) {
                e.preventDefault();
            };
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
            };
        }
        return btn;
    };

    this._defaultButtons = [

    ];

    this.createMenuDynamicHolder = function(item){
        var dn = mwd.createElement('div');
        dn.className = 'mw-handle-menu-dynamic' + (item.className ? ' ' + item.className : '');
        return dn;
    };
    this.createMenu = function(){
        this.menu = mwd.createElement('div');
        this.menu.className = 'mw-handle-menu ' + (this.options.menuClass ? this.options.menuClass : 'mw-handle-menu-default');
        if (this.options.menu) {
            for (var i = 0; i < this.options.menu.length; i++) {
                if(this.options.menu[i].title !== '{dynamic}') {
                    this.menu.appendChild(this.menuButton(this.options.menu[i])) ;
                }
                else {
                    this.menu.appendChild(this.createMenuDynamicHolder(this.options.menu[i])) ;
                }

            }
        }
        this.wrapper.appendChild(this.menu);
    };
    this.create();
    this.hide();
};

mw._activeModuleOver = {
    module: null,
    element: null
};

mw._initHandles = {
    getNodeHandler:function (node) {
        if(mw._activeElementOver === node){
            return mw.handleElement
        } else if(mw._activeModuleOver === node) {
            return mw.handleModule
        } else if(mw._activeRowOver === node) {
            return mw.handleColumns;
        }
    },
    getAllNodes: function (but) {
        var all = [
            mw._activeModuleOver,
            mw._activeRowOver,
            mw._activeElementOver
        ];
        all = all.filter(function (item) {
            return !!item && item.nodeType === 1;
        });
        return all;
    },
    getAll: function (but) {
        var all = [
            mw.handleModule,
            mw.handleColumns,
            mw.handleElement
        ];
        all = but ? all.filter(function (x) {
            return x !== but;
        }) :  all;
        return all.filter(function (item) {
            if(item){
                return item.visible();
            }

        });
    },
    hideAll:function (but) {
        this.getAll(but).forEach(function (item) {
            item.hide();
        });
    },
    collide: function(a, b) {
        return !(
            ((a.y + a.height) < (b.y)) ||
            (a.y > (b.y + b.height)) ||
            ((a.x + a.width) < b.x) ||
            (a.x > (b.x + b.width))
        );
    },
    _manageCollision: false,
    manageCollision:function () {

        var scope = this,
            max = 35,
            skip = [];

        scope.getAll().forEach(function (curr) {
            var master = curr, masterRect;
            //if (skip.indexOf(master) === -1){
            scope.getAll(curr).forEach(function (item) {
                masterRect = master.wrapper.getBoundingClientRect();
                var irect = item.wrapper.getBoundingClientRect();
                if (scope.collide(masterRect, irect)) {
                    skip.push(item);
                    var topMore = item === mw.handleElement ? 10 : 0;
                    item.wrapper.style.top = (parseInt(master.wrapper.style.top, 10) + topMore) + 'px';
                    item.wrapper.style.left = ((parseInt(master.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                    master = curr;
                }
            });
        });

        var cloner = mwd.querySelector('.mw-cloneable-control');
        if(cloner) {
            scope.getAll().forEach(function (curr) {
                masterRect = curr.wrapper.getBoundingClientRect();
                var clonerect = cloner.getBoundingClientRect();

                if (scope.collide(masterRect, clonerect)) {
                    cloner.style.top = curr.wrapper.style.top;
                    cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                }
            });
        }
    },

    elements: function(){
        mw.handleElement = new mw.Handle({
            id: 'mw-handle-item-element',
            className: 'mw-handle-type-element',
            menu: [
                {
                    title: 'Edit HTML',
                    icon: 'mw-icon-code',
                    action: function () {
                        mw.editSource(mw._activeElementOver);
                    }
                },
                {
                    title: 'Edit Style',
                    icon: 'mw-icon-edit',
                    action: function () {
                        mw.liveEditSettings.show();
                        mw.sidebarSettingsTabs.set(3);
                        if(mw.cssEditorSelector){
                            mw.liveEditSelector.active(true);
                            mw.liveEditSelector.select(mw._activeElementOver);
                        } else{
                            mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                                setTimeout(function(){
                                    mw.liveEditSelector.active(true);
                                    mw.liveEditSelector.select(mw._activeElementOver);
                                }, 333);
                            });
                        }
                        mw.liveEditWidgets.cssEditorInSidebarAccordion();
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeElementOver);
                        mw.handleElement.hide()
                    }
                }
            ]
        });

        mw.$(mw.handleElement.wrapper).draggable({
            handle: mw.handleElement.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw._activeElementOver;

                handleDomtreeSync.start = mw.dragCurrent.parentNode;

                if(!mw.dragCurrent.id){
                    mw.dragCurrent.id = 'element_' + mw.random();
                }
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            },
            stop: function() {
                mw.$(mwd.body).removeClass("dragStart");

                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.$(mw.handleElement.wrapper).mouseenter(function() {
        }).click(function() {
            if (!$(mw._activeElementOver).hasClass("element-current")) {
                mw.$(".element-current").removeClass("element-current");

                if (mw._activeElementOver.nodeName === 'IMG') {

                    mw.trigger("ImageClick", mw._activeElementOver);
                } else {
                    mw.trigger("ElementClick", mw._activeElementOver);
                }
            }

        });

        mw.on("ElementOver", function(a, element) {
            mw._activeElementOver = element;
            mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").show();
            if (!mw.ea.canDrop(element)) {
                mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").hide();
                return false;
            }
            var el = mw.$(element);

            var o = el.offset();

            var pleft = parseFloat(el.css("paddingLeft"));
            var left_spacing = o.left;
            if (mw.tools.hasClass(element, 'jumbotron')) {
                left_spacing = left_spacing + pleft;
            }
            if(left_spacing<0){
                left_spacing = 0;
            }
            //todo: another icon
            var isSafe = false; // mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['safe-mode', 'regular-mode']);
            var _icon = isSafe ? '<svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 504.03 440" height="17" class="safe-element-svg"><path fill="green" d="M252,2.89C178.7,2.89,102.4,19.44,102.4,19.44A31.85,31.85,0,0,0,76.76,50.69v95.59c0,165.67,159.7,234.88,159.7,234.88A31.65,31.65,0,0,0,252,385.27a32.05,32.05,0,0,0,15.56-4.11c.06,0,159.69-69.21,159.69-234.88V50.69a31.82,31.82,0,0,0-25.64-31.25S325.33,2.89,252,2.89Zm95.59,95.59a15.94,15.94,0,0,1,11.26,27.2L238.45,246.11a16,16,0,0,1-11.33,4.73,15.61,15.61,0,0,1-11.2-4.73l-55-55a15.93,15.93,0,0,1,22.53-22.53l43.69,43.82L336.34,103.15a16,16,0,0,1,11.27-4.67Zm0,0"/></svg>' : '<span class="mw-icon-drag"></span>';

            var icon = '<span class="mw-handle-element-title-icon '+(isSafe ? 'tip' : '')+'"  '+(isSafe ? ' data-tip="Current element is protected \n  from accidental deletion" data-tipposition="top-left"' : '')+' >'+ _icon +'</span>';

            var title = 'Settings';

            mw.handleElement.setTitle(icon, title);

            if(el.hasClass('allow-drop')){
                mw.handleElement.hide();
            } else{
                mw.handleElement.show();
            }


            mw.$(mw.handleElement.wrapper).css({
                top: o.top - 10,
                left: left_spacing
            }).removeClass('active');

            if(!element.id) {
                element.id = "element_" + mw.random();
            }

            mw.dropable.removeClass("mw_dropable_onleaveedit");
            mw._initHandles.manageCollision();

        });


    },
    modules: function () {

        var handlesModuleConfig = {
            id: 'mw-handle-item-module',
            menu:[
                {
                    title: 'Settings',
                    icon: 'mw-icon-gear',
                    action: function () {
                        mw.drag.module_settings(mw._activeModuleOver,"admin");
                        mw.handleModule.hide();
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mw-icon-arrow-up-b',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'prev');
                        mw.handleModule.hide()
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mw-icon-arrow-down-b',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'next');
                        mw.handleModule.hide()
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },


                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeModuleOver);
                        mw.handleModule.hide();
                    }
                }
            ]
        };
        var handlesModuleConfigActive = {
            id: 'mw-handle-item-module-active',
            menu:[
                {
                    title: 'Settings',
                    icon: 'mw-icon-gear',
                    action: function () {
                        mw.drag.module_settings(getActiveDragCurrent(),"admin");
                        $(mw.handleModuleActive.wrapper).removeClass('active');
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mw-icon-arrow-up-b',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'prev');
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mw-icon-arrow-down-b',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'next');
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },
                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(getActiveDragCurrent());
                        mw.handleModuleActive.hide();
                    }
                }
            ]
        };

        var getActiveDragCurrent = function () {
            //var el = mw.liveEditSelector && mw.liveEditSelector.selected ?  mw.liveEditSelector.selected[0] : null;
            var el = mw.liveEditSelector.activeModule;
            if (el && el.nodeType === 1) {
                return el;
            }
            if(mw.handleModuleActive._target) {
                return mw.handleModuleActive._target;
            }
        };

        var getDragCurrent = function () {
            if(mw._activeModuleOver){
                return mw._activeModuleOver;
            }
        };
        var dragConfig = function (curr, handle) {
            return {
                handle: handle.handleIcon,
                distance:20,
                cursorAt: {
                    //top: -30
                },
                start: function() {
                    mw.isDrag = true;
                    mw.dragCurrent = curr();
                    handleDomtreeSync.start = mw.dragCurrent.parentNode;
                    if (!mw.dragCurrent.id) {
                        mw.dragCurrent.id = 'module_' + mw.random();
                    }
                    if(mw.liveEditTools.isLayout(mw.dragCurrent)){
                        mw.$(mw.dragCurrent).css({
                            opacity:0
                        }).addClass("mw_drag_current");
                    } else {
                        mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                    }

                    mw.trigger("AllLeave");
                    mw.drag.fix_placeholders();
                    mw.$(mwd.body).addClass("dragStart");
                    mw.image_resizer._hide();
                    mw.wysiwyg.change(mw.dragCurrent);
                    mw.smallEditor.css("visibility", "hidden");
                    mw.smallEditorCanceled = true;
                },
                stop: function() {
                    mw.$(mwd.body).removeClass("dragStart");
                    if(mw.liveEditDomTree) {
                        mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                    }
                }
            };
        };

        mw.handleModule = new mw.Handle(handlesModuleConfig);
        mw.handleModuleActive = new mw.Handle(handlesModuleConfigActive);

        mw.handleModule.type = 'hover';
        mw.handleModuleActive.type = 'active';

        mw.handleModule._hideTime = null;
        mw
            .$(mw.handleModule.wrapper)
            .draggable(dragConfig(getDragCurrent, mw.handleModule))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        mw
            .$(mw.handleModuleActive.wrapper)
            .draggable(dragConfig(getActiveDragCurrent, mw.handleModuleActive))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        var positionModuleHandle = function(e, pelement, handle){


            var element ;

            if(handle.type === 'hover') {
                element = dynamicModulesMenu(e, pelement) || pelement;
                mw._activeModuleOver = element;
            } else {
                //pelement = mw.tools.lastMatchesOnNodeOrParent(pelement, ['.module']);

                element = dynamicModulesMenu(e, pelement) || pelement;
                handle._target = pelement;
            }



            mw.$(".mw-handle-menu-dynamic", handle.wrapper).empty();
            mw.$('.mw_handle_module_up,.mw_handle_module_down').hide();
            var $el, hasedit;
            if(element && element.getAttribute('data-type') === 'layouts'){
                $el = mw.$(element);
                hasedit = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst($el[0].parentNode,['edit', 'module']);

                if(hasedit){
                    if($el.prev('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_up').show();
                    }
                    if($el.next('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_down').show();
                    }
                }
            }

            var el = mw.$(element);
            var o = el.offset();
            var width = el.width();
            var pleft = parseFloat(el.css("paddingLeft"));

            var lebar =  mwd.querySelector("#live_edit_toolbar");
            var minTop = lebar ? lebar.offsetHeight : 0;
            if(mw.templateTopFixed) {
                var ex = document.querySelector(mw.templateTopFixed);
                if(ex && !ex.contains(el[0])){
                    minTop += ex.offsetHeight;
                }
            }

            var marginTop =  30;
            var topPos = o.top;

            if(topPos<minTop){
                topPos = minTop;
            }
            var ws = mw.$(window).scrollTop();
            if(topPos<(ws+minTop)){
                topPos=(ws+minTop);
                marginTop =  -15;
                if(el[0].offsetHeight <100){
                    topPos = o.top+el[0].offsetHeight;
                    marginTop =  0;
                }
            }

            var handleLeft = o.left + pleft;
            if (handleLeft < 0) {
                handleLeft = 0;
            }

            var topPosFinal = topPos + marginTop;
            var $lebar = mw.$(lebar), $leoff = $lebar.offset();

            var outheight = el.outerHeight();

            if(topPosFinal < ($leoff.top + $lebar.height())){
                topPosFinal = (o.top + outheight) - (outheight > 100 ? 0 : handle.wrapper.clientHeight);
            }

            if(el.attr('data-type') === 'layouts') {
                topPosFinal = o.top + 10;
                handleLeft = handleLeft + 10;
            }

            clearTimeout(handle._hideTime);
            handle.show();
            mw.$(handle.wrapper)
                .removeClass('active')
                .css({
                    top: topPosFinal,
                    left: handleLeft,
                    //width: width,
                    //marginTop: marginTop
                }).addClass('mw-active-item');




            var canDrag = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element.parentNode, ['edit', 'module'])
                && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop']);
            if(canDrag){
                mw.$(handle.wrapper).removeClass('mw-handle-no-drag');
            } else {
                mw.$(handle.wrapper).addClass('mw-handle-no-drag');
            }
            if(typeof(el) == 'undefined'){
                return;
            }
            var title = el.dataset("mw-title");
            var id = el.attr("id");



            var module_type = (el.dataset("type") || el.attr("type"));
            if(typeof(module_type) == 'undefined'){
                return;
            }

            var cln = el[0].querySelector('.cloneable');
            if(cln || mw.tools.hasClass(el[0], 'cloneable')){
                if(($(cln).offset().top - el.offset().top) < 20){
                    mw.tools.addClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                } else {
                    mw.tools.removeClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                }
            }

            var mod_icon = mw.live_edit.getModuleIcon(module_type);
            var mod_handle_title = (title ? title : mw.msg.settings);
            /*if(module_type === 'layouts'){
                mod_handle_title = '';
            }*/

            handle.setTitle(mod_icon, mod_handle_title);
            if(!handle){
                return;
            }

            mw.tools.classNamespaceDelete(handle, 'module-active-');
            mw.tools.addClass(handle, 'module-active-' + module_type.replace(/\//g, '-'));

            if (mw.live_edit_module_settings_array && mw.live_edit_module_settings_array[module_type]) {

                var new_el = mwd.createElement('div');
                new_el.className = 'mw_edit_settings_multiple_holder';

                var settings = mw.live_edit_module_settings_array[module_type];
                mw.$(settings).each(function () {
                    if (this.view) {
                        var new_el = mwd.createElement('a');
                        new_el.className = 'mw_edit_settings_multiple';
                        new_el.title = this.title;
                        new_el.draggable = 'false';
                        var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                        new_el.id = btn_id;
                        if (this.type && this.type === 'tooltip') {
                            new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + this.view + '", "tooltip"); void(0);';

                        } else {
                            new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + this.view + '"); void(0);';
                        }
                        var icon = '';
                        if (this.icon) {
                            icon = '<i class="mw-edit-module-settings-tooltip-icon ' + this.icon + '"></i>';
                        }
                        new_el.innerHTML =  (icon + '<span class="mw-edit-module-settings-tooltip-btn-title">' + this.title+'</span>');
                        mw.$(".mw_handle_module_spacing", handle.wrapper).append(new_el);
                    }
                });
            } else {

            }

            /*************************************/


            if(!element.id) {
                element.id = "module_" + mw.random();
            }
            mw._initHandles.manageCollision();
        };

        mw.on('ModuleClick', function(e, pelement){
            positionModuleHandle(e, pelement, mw.handleModuleActive);
        });

        mw.on('moduleOver', function (e, pelement) {
            positionModuleHandle(e, pelement, mw.handleModule);
            if(mw._activeModuleOver === mw.handleModuleActive._target) {
                mw.handleModule.hide();
            }

            var nodes = [];
            mw.$('.module', pelement).each(function () {

                var type = this.getAttribute('data-type');

                var hastitle = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : false;
                var icon = mw.live_edit.getModuleIcon(type);
                if(!icon){
                    icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                }
                if (hastitle) {
                    var menuitem = '<span class="mw-handle-menu-item dynamic-submodule-handle" data-module="'+this.id+'">'
                        + icon
                        + hastitle.replace(/_/g, ' ')
                        + '</span>';
                    nodes.push(menuitem);
                }

            });
            var el = mw.$('.mw_handle_module_submodules');
            el.empty();
            $.each(nodes, function () {
                el.append(this);
            });
            mw.$('.text-background', pelement).each(function () {
                var bgEl = this;
                $.each([0,1], function(i){
                    var icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                    var menuitem = mwd.createElement('span');
                    menuitem.className = 'mw-handle-menu-item text-background-handle';
                    menuitem.innerHTML = icon + 'background text';
                    menuitem.__for = bgEl;
                    // menuitem = mw.$(menuitem);
                    el.eq(i).append(menuitem)
                })

            });


            mw.$('.text-background-handle').on('click', function () {
                var ok = mw.$('<button class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info pull-right">OK</button>');
                var cancel = mw.$('<button class="mw-ui-btn mw-ui-btn-medium pull-left">Cancel</button>');
                var footer = mw.$('<div></div>');
                var area = $('<textarea class="mw-ui-field w100" style="height: 200px"/>');
                var node = this.__for;
                area.val(mw.$(node).html());
                footer.append(cancel);
                footer.append(ok);
                var dialog = mw.dialog({
                    content: area,
                    footer: footer
                });
                ok.on('click', function () {
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                    mw.$(node).html(area.val());
                    dialog.remove();
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                });
                cancel.on('click', function () {
                    dialog.remove();
                });
            });
            mw.$('.dynamic-submodule-handle').on('click', function () {
                mw.tools.module_settings('#' + this.dataset.module);
            });
        });
    },
    columns:function(){
        mw.handleColumns = new mw.Handle({
            id: 'mw-handle-item-columns',
            // className:'mw-handle-type-element',
            menu:[
                {
                    title: 'One column',
                    action: function () {
                        mw.drag.create_columns(this,1);
                    }
                },
                {
                    title: '2 columns',
                    action: function () {
                        mw.drag.create_columns(this,2);
                    }
                },
                {
                    title: '3 columns',
                    action: function () {
                        mw.drag.create_columns(this,3);
                    }
                },
                {
                    title: '4 columns',
                    action: function () {
                        mw.drag.create_columns(this,4);
                    }
                },
                {
                    title: '5 columns',
                    action: function () {
                        mw.drag.create_columns(this,5);
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeRowOver, function () {
                            mw.$(mw.drag.columns.resizer).hide();
                            mw.handleColumns.hide();
                        });
                    }
                }
            ]
        });
        mw.handleColumns.setTitle('<span class="mw-handle-columns-icon"></span>', '');

        mw.$(mw.handleColumns.wrapper).draggable({
            handle: mw.handleColumns.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                var curr = mw._activeRowOver ;
                mw.dragCurrent = mw.ea.data.currentGrabbed = curr;
                handleDomtreeSync.start = mw.dragCurrent.parentNode;
                mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'element_' + mw.random() : '';
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
                mw.$(mw.drag.columns.resizer).hide()
            },
            stop: function() {
                mw.$(mwd.body).removeClass("dragStart");
                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.on("RowOver", function(a, element) {

            mw._activeRowOver = element;
            var el = mw.$(element);
            var o = el.offset();
            var width = el.width();
            var pleft = parseFloat(el.css("paddingLeft"));
            var htop = o.top - 35;
            var left = o.left;

            if (htop < 55 && mwd.getElementById('live_edit_toolbar') !== null) {
                htop = 55;
                left = left - 100;
            }
            if (htop < 0 && mwd.getElementById('live_edit_toolbar') === null) {
                htop = 0;
                //   var left = left-50;
            }


            mw.handleColumns.show()

            mw.$(mw.handleColumns.wrapper).css({
                top: htop,
                left: left,
                //width: width
            });
            mw._initHandles.manageCollision();

            var size = mw.$(element).children(".mw-col").length;
            mw.$("a.mw-make-cols").removeClass("active");
            mw.$("a.mw-make-cols").eq(size - 1).addClass("active");
            if(!element.id){
                element.id = "element_row_" + mw.random() ;
            }




        });
    },
    nodeLeave: function () {
        var scope = this;

        mw.on("ElementLeave", function(e, target) {
            mw.handleElement.hide();
        });
        mw.on("ModuleLeave", function(e, target) {
            clearTimeout(mw.handleModule._hideTime);
            mw.handleModule._hideTime = setTimeout(function () {
                mw.handleModule.hide();
            }, 3000);

            //.removeClass('mw-active-item');
        });
        mw.on("RowLeave", function(e, target) {
            //mw.handleColumns.hide();
        });
    }
};




$(document).ready(function () {

    mw._initHandles.modules();
    mw._initHandles.elements();
    mw._initHandles.columns();
    mw._initHandles.nodeLeave();



});

})();

(() => {
/*!********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/image-resize.js ***!
  \********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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

})();

(() => {
/*!****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/initload.js ***!
  \****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.initLoad = function() {
    setTimeout(function(){
        mw.$(".mw-dropdown_type_navigation a").each(function() {
            var el = mw.$(this);
            var li = el.parent();
            el.attr("href", "javascript:;");
            var val = li.dataset("category-id");
            li.attr("value", val);
        });

        mw.$("#module_category_selector").change(function() {
            var val = mw.$(this).getDropdownValue();

            if (val === 'all') {
                mw.$(".list-modules li").show();
                return false;
            }
            (val !== -1 && val !== "-1") ? mw.liveedit.toolbar_sorter(Modules_List_modules, val): '';
        });
        mw.$("#elements_category_selector").change(function() {
            var val = mw.$(this).getDropdownValue();

            if (val === 'all') {
                mw.$(".list-elements li").show();
                return false;
            }
            (val !== -1 && val !== "-1") ? mw.liveedit.toolbar_sorter(Modules_List_elements, val): '';
        });




        mw.interval('regular-mode', function(){
            // mw.$('.row').addClass('nodrop');
            // mw.$('.row .col, .row [class*="col-"]').addClass('allow-drop');
            // mw.$('.nodrop .allow-drop').addClass('regular-mode');
            mw.$('.safe-element[class*="mw-micon-"]').removeClass('safe-element');
        })

    }, 100);


    mw.wysiwyg.prepareContentEditable();

    mw.imageResize.init(".element-image");
    mw.$(mwd.body).on('mousedown touchstart', function(event) {


        if (mw.$(".editor_hover").length === 0) {
            mw.$(mw.wysiwyg.external).empty().css("top", "-9999px");
            mw.$(mwd.body).removeClass('hide_selection');
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


    mw.$(document.body).on('mouseup touchend',function(event) {
        mw.target.item = event.target;
        mw.target.tag = event.target.tagName.toLowerCase();
        mw.mouseDownOnEditor = false;
        mw.SmallEditorIsDragging = false;
        if (!mw.image.isResizing &&
            mw.target.tag !== 'img' &&
            event.target !== mw.image_resizer && !mw.tools.hasClass(mw.target.item.className, 'image_change') && !mw.tools.hasClass(mw.target.item.parentNode.className, 'image_change') && mw.$(mw.image_resizer).hasClass("active")) {
            mw.image_resizer._hide();
        }
    });

    mw.liveedit.toolbar.prepare();
    mw.liveedit.toolbar.fixPad();
    mw.liveedit.editors.prepare();
    mw.liveedit.toolbar.setEditor();
}

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/initready.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.initReady = function() {
    mw.liveedit.data.init();
    mw.liveEditSelector = new mw.Selector({
        root: document.body,
        autoSelect: false
    });

    mw.paddingCTRL = new mw.paddingEditor({

    });

    mw.drag.create();

    mw.liveedit.editFields.handleKeydown();

    mw.dragSTOPCheck = false;

    var t = mwd.querySelectorAll('[field="title"]'),
        l = t.length,
        i = 0;

    for (; i < l; i++) {
        mw.$(t[i]).addClass("nodrop");
    }



    mw.wysiwyg.init_editables();
    mw.wysiwyg.prepare();
    mw.wysiwyg.init();
    mw.ea = mw.ea || new mw.ElementAnalyzer();
};

})();

(() => {
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/inline.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.inline = {
    bar: function (id) {
        if (typeof id === 'undefined') {
            return false;
        }
        if (mw.$("#" + id).length === 0) {
            var bar = mwd.createElement('div');
            bar.id = id;
            mw.wysiwyg.contentEditable(bar, false);
            bar.className = 'mw-defaults mw-inline-bar';
            mwd.body.appendChild(bar);
            return bar;
        }
        else {
            return mw.$("#" + id)[0];
        }
    },
    tableControl: false,
    tableController: function (el, e) {
        if (typeof e !== 'undefined') {
            e.stopPropagation();
        }
        if (mw.liveedit.inline.tableControl === false) {
            mw.liveedit.inline.tableControl = mw.liveedit.inline.bar('mw-inline-tableControl');
            mw.liveedit.inline.tableControl.innerHTML = ''
                + '<ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">'
                + '<li>'
                + '<a href="javascript:;">Insert<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'above\', mw.liveedit.inline.activeCell);">Row Above</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'under\', mw.liveedit.inline.activeCell);">Row Under</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'left\', mw.liveedit.inline.activeCell)">Column on left</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'right\', mw.liveedit.inline.activeCell)">Column on right</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Style<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table\', mw.liveedit.inline.activeCell);">Bordered</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-zebra\', mw.liveedit.inline.activeCell);">Bordered Zebra</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple\', mw.liveedit.inline.activeCell);">Simple</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple-zebra\', mw.liveedit.inline.activeCell);">Simple Zebra</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Delete<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteRow(mw.liveedit.inline.activeCell);">Row</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteColumn(mw.liveedit.inline.activeCell);">Column</a></li>'
                + '</ul>'
                + '</li>'
                + '</ul>';
        }
        var off = mw.$(el).offset();
        mw.$(mw.liveedit.inline.tableControl).css({
            top: off.top - 45,
            left: off.left,
            display: 'block'
        });
    },
    activeCell: null,
    setActiveCell: function (el, event) {
        if (!mw.tools.hasClass(el.className, 'tc-activecell')) {
            mw.$(".tc-activecell").removeClass('tc-activecell');
            mw.$(el).addClass('tc-activecell');
            mw.liveedit.inline.activeCell = el;
        }
    },
    tableManager: {
        insertColumn: function (dir, cell) {
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                var cell = mw.$(row).children('td')[index];
                if (dir == 'left' || dir == 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir == 'right' || dir == 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        },
        insertRow: function (dir, cell) {
            var cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            var dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length, html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            var html = '<tr>' + html + '</tr>';
            if (dir == 'under' || dir == 'both') {
                mw.$(parent).after(html)
            }
            if (dir == 'above' || dir == 'both') {
                mw.$(parent).before(html)
            }
        },
        deleteRow: function (cell) {
            mw.$(cell.parentNode).remove();
        },
        deleteColumn: function (cell) {
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        },
        setStyle: function (cls, cell) {
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        }
    }
}

})();

(() => {
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/layoutplus.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.layoutPlus = {
    create: function(){
        this._top = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-top">Add Layout</span>');
        this._bottom = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-bottom">Add Layout</span>');
        mw.$(document.body).append(this._top).append(this._bottom);

        this._top.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._top.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover');
        });
        this._bottom.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._bottom.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover')
        });
    },
    hide: function () {
        this._top.css({top: -999, left: -999});
        this._bottom.css({top: -999, left: -999});
        this.pause = false;
    },
    pause: false,
    _active: null,
    position:function(){
        var $layout = mw.$(this._active);
        var off = $layout.offset();
        var left = (off.left + ($layout.outerWidth()/2));
        this._top.css({top: off.top - 20, left: left});
        this._bottom.css({top: off.top + $layout.outerHeight(), left: left});
    },
    _prepareList:function (tip, action) {
        var scope = this;
        var items = mw.$('.modules-list li', tip);
        mw.$('input', tip).on('input', function () {
                mw.tools.search(this.value, items, function (found) {
                    $(this)[found?'show':'hide']();
                });
        });
        mw.$('.modules-list li', tip).on('click', function () {
            var id = mw.id('mw-layout-'), el = '<div id="' + id + '"></div>';
            var $active = mw.$(mw.layoutPlus._active);
            $active[action](el);

            var name = $active.attr('data-module-name');
            var template = $(this).attr('template');
            var conf = {class: mw.layoutPlus._active.className, template: template};

            /*mw.liveEditState.record({
                action: function () {
                    mw.$('#' + id).replaceWith('<div id="' + id + '"></div>');
                }
            });*/

            mw.load_module('layouts', '#' + id, function () {
                mw.wysiwyg.change(document.getElementById(id));
                mw.drag.fixes();
                /*mw.liveEditState.record({
                    action: function () {
                        mw.load_module('layouts', '#' + id, undefined, conf);
                    }
                });*/
                setTimeout(function () {
                    mw.drag.fix_placeholders();
                }, 40);
                mw.dropable.hide();
                mw.layoutPlus.mode === 'Dialog' ? mw.dialog.get(tip).remove()  : $(tip).remove();
            }, conf);
            scope.pause = false;
        });
    },
    mode: 'tooltip', //'Dialog',
    initSelector: function () {
        var scope = this;
        this._top.on('click', function () {
            scope.pause = true;
            var tip = new mw[mw.layoutPlus.mode]({
                content: mwd.getElementById('plus-layouts-list').innerHTML,
                element: this,
                position: 'right-center',
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector',
                title: mw.lang('Select layout'),
                width: 400
            });
            scope._prepareList(document.getElementById('mw-plus-tooltip-selector'), 'before');
            $('#mw-plus-tooltip-selector input').focus();
            $('#mw-plus-tooltip-selector').addClass('active');

        });
        this._bottom.on('click', function () {
            scope.pause = true;
            var tip = new mw[mw.layoutPlus.mode]({
                content: mwd.getElementById('plus-layouts-list').innerHTML,
                element: this,
                position: 'right-center',
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector',
                title: mw.lang('Select layout'),
                width: 400
            });
            scope._prepareList(document.getElementById('mw-plus-tooltip-selector'), 'after');
            $('#mw-plus-tooltip-selector input').focus();
            $('#mw-plus-tooltip-selector').addClass('active');
        });

    },
    handle: function () {
        var scope = this;
        mw.$(window).on('resize', function (e) {
            if (scope._active) {
                scope.position();
            }
        });
        mw.on('moduleOver ModuleClick', function (e, module) {
            if (module.dataset.type === 'layouts' && !scope.pause) {
                scope._active = module;
                scope.position();
            } else {
                scope.hide();
            }
        });
    },
    _ready: false,
    init: function () {
        if (!this._ready) {
            this._ready = true;
            this.create();
            this.handle();
            this.initSelector();
        }
    }
};

$(window).on('load', function () {
    mw.layoutPlus.init();
});

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/live_edit.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.live_edit = mw.live_edit || {};

mw.live_edit.registry = mw.live_edit.registry || {};

mw.live_edit.hasAbilityToDropElementsInside = function(target) {
    var items = /^(span|h[1-6]|hr|ul|ol|input|table|b|em|i|a|img|textarea|br|canvas|font|strike|sub|sup|dl|button|small|select|big|abbr|body)$/i;
    if (typeof target === 'string') {
        return !items.test(target);
    }
    if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['allow-drop', 'nodrop'])){
        return false;
    }
    if(mw.tools.hasAnyOfClasses(target, ['plain-text'])){
        return false;
    }
    var x = items.test(target.nodeName);
    if (x) {
        return false;
    }
    if (mw.tools.hasParentsWithClass(target, 'module')) {
        if (mw.tools.hasParentsWithClass(target, 'edit')) {
            return true;
        } else {
            return false;
        }
    } else if (mw.tools.hasClass(target, 'module')) {
        return false;
    }
    return true;
};

mw.require('dialog.js')


mw.live_edit.showSettings = function (a, opts) {

    var liveedit = opts.liveedit || false;
    var mode = opts.mode ||  'modal';

    var view = opts.view || 'admin';
    var module_type;
    if (typeof a === 'string') {
        module_type = a;
        var module_id = a;
        var mod_sel = mw.$(a + ':first');
        if (mod_sel.length > 0) {
            var attr = $(mod_sel).attr('id');
            if (typeof attr !== typeof undefined && attr !== false) {
                attr = !attr.contains("#") ? attr : attr.replace("#", '');
                module_id = attr;
            }
            var attr2 = $(mod_sel).attr('type');
            attr = $(mod_sel).attr('data-type');
            if (typeof attr !== typeof undefined && attr !== false) {
                module_type = attr;
            } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                module_type = attr2;
            }
            a = mod_sel[0]
        }
    }

    var curr = a || $("#mw_handle_module").data("curr");
    if(!curr){
        return;
    }
    if(typeof(curr) === 'undefined'){
        return;
    }
    var attributes = {};

    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var iframe_id_sidebar = 'js-iframe-module-settings-' + curr.id;
    var iframe_id_sidebar_wrapper_id = 'sidebar-frame-wrapper-' + iframe_id_sidebar;

    var data1 = attributes;

    module_type = null;
    if (data1['data-type'] !== undefined) {
        module_type = data1['data-type'];
        data1['data-type'] = data1['data-type'] + '/admin';
    }
    if (data1['data-module-name'] !== undefined) {
        delete(data1['data-module-name']);
    }
    if (data1['type'] !== undefined) {
        module_type = data1['type'];
        data1['type'] = data1['type'] + '/admin';
    }
    if (module_type != null && view !== undefined) {
        data1['data-type'] = data1['type'] = module_type + '/' + view;
    }
    if (typeof data1['class'] !== 'undefined') {
        delete(data1['class']);
    }
    if (typeof data1['style'] !== 'undefined') {
        delete(data1['style']);
    }
    if (typeof data1.contenteditable !== 'undefined') {
        delete(data1.contenteditable);
    }
    data1.live_edit = liveedit;
    data1.module_settings = 'true';
    if (view !== undefined) {
        data1.view = view;
    }
    else {
        data1.view = 'admin';
    }
    if (data1.from_url == undefined) {
        //data1.from_url = mw.top().win.location;
        data1.from_url = window.parent.location;
    }
    var modal_name = 'module-settings-' + curr.id;
    if (typeof(data1.view.hash) == 'function') {
        //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
    }
    //data1.live_edit_sidebar = true;

    var src = mw.settings.site_url + "api/module?" + json2url(data1);

    if (self !== top || /*!mw.liveEditSettings.active || */ mode === 'modal') {
        //remove from sidebar
        $("#" + iframe_id_sidebar).remove();

        //close sidebar
        if(mw.liveEditSettings && mw.liveEditSettings.active){
             mw.liveEditSettings.hide();
        }
        var has = mw.$('#' + modal_name);
        if(has.length){
            var dialog = mw.dialog.get(has[0]);
            dialog.show();
            return dialog;
        }
        var nmodal = mw.dialogIframe({
            url: src,
            width: 532,
            height: 'auto',
            autoHeight:true,
            id: modal_name,
            title:'',
            className: 'mw-dialog-module-settings',
            closeButtonAction: 'remove'
        });

        nmodal.iframe.contentWindow.thismodal = nmodal;
        return nmodal;

    } else {


        if(!mw.liveEditSettings.active){
            mw.liveEditSettings.show();
        }

        if(mw.sidebarSettingsTabs){
            mw.sidebarSettingsTabs.set(2);
        }



        data1.live_edit_sidebar = true;

        var src = mw.settings.site_url + "api/module?" + json2url(data1);


        var mod_settings_iframe_html_fr = '' +
            '<div class="js-module-settings-edit-item-group-frame loading" id="' + iframe_id_sidebar_wrapper_id + '">' +
            '<iframe src="' + src + '" frameborder="0" onload="this.parentNode.classList.remove(\'loading\')">' +
            '</div>';


        var sidebar_title_box = mw.live_edit.getModuleTitleBar(module_type, curr.id);


         var mod_settings_iframe_html = '<div  id="' + iframe_id_sidebar + '" class="js-module-settings-edit-item-group">'
            + sidebar_title_box
            + mod_settings_iframe_html_fr
            + '</div>';


        if (!$("#" + iframe_id_sidebar).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id_sidebar).length) {
            $('.js-module-settings-edit-item-group').hide();
            $("#" + iframe_id_sidebar).attr('data-settings-for-module', curr.id);

            $("#" + iframe_id_sidebar).show();
        }
    }

}


mw.live_edit.getModuleTitleBar = function (module_type, module_id) {

    var mod_icon = mw.live_edit.getModuleIcon(module_type);
    var mod_title = mw.live_edit.getModuleTitle(module_type);
    var mod_descr = mw.live_edit.getModuleDescription(module_type);

    var sidebar_title_box = "<div class='mw_module_settings_sidebar_title_wrapper js-module-titlebar-"+module_id+"'>" + mod_icon;
    sidebar_title_box = sidebar_title_box + "<div class='js-module-sidebar-settings-menu-holder'>" + "</div>";
    sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_title'>" + mod_title + "</div>";

    if (mod_title != mod_descr) {
        //  sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_description'>" + mod_descr + "</div>";
    }
    sidebar_title_box = sidebar_title_box + "</div>";
    return sidebar_title_box;
};

mw.live_edit.getModuleIcon = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].icon) {
        return '<span class="mw_module_settings_sidebar_icon" style="background-image: url(' + mw.live_edit.registry[module_type].icon + ')"></span>';
    }
    else {
        return '<span class="mw-icon-gear"></span>&nbsp;&nbsp;';
    }
};
mw.live_edit.getModuleTitle = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].title) {
        return mw.live_edit.registry[module_type].title;
    } else if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].name) {
        return mw.live_edit.registry[module_type].name;
    }
    else {
        return '';
    }
};
mw.live_edit.getModuleDescription = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].description) != 'undefined') {
        return mw.live_edit.registry[module_type].description;
    }
    else {
        return '';
    }
};



})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/liveedit_elements.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

/**
 * Makes Droppable area
 *
 * @return Dom Element
 */
mw.dropables = {
    prepare: function() {
        var dropable = document.createElement('div');
        dropable.className = 'mw_dropable';
        dropable.innerHTML = '<span class="mw_dropable_arr"></span>';
        document.body.appendChild(dropable);
        mw.dropable = mw.$(dropable);
        mw.dropable.hide = function(){
            return mw.$(this).addClass('mw_dropable_hidden');
        };
        mw.dropable.show = function(){
            return mw.$(this).removeClass('mw_dropable_hidden');
        };
        mw.dropable.hide()
    },
    userInteractionClasses:function(){
        var bgHolders = mwd.querySelectorAll(".edit.background-image-holder, .edit .background-image-holder, .edit[style*='background-image'], .edit [style*='background-image']");
        var noEditModules = mwd.querySelectorAll('.module' + mw.noEditModules.join(',.module'));
        var edits = mwd.querySelectorAll('.edit');
        var i = 0, i1 = 0, i2 = 0;
        for ( ; i<bgHolders.length; i++ ) {
            var curr = bgHolders[i];
            var po = mw.tools.parentsOrder(curr, ['edit', 'module']);
            if(po.module === -1 || (po.edit<po.module && po.edit !== -1)){
                if(!mw.tools.hasClass(curr, 'module')){
                    mw.tools.addClass(curr, 'element');
                }
                curr.style.backgroundImage = curr.style.backgroundImage || 'none';
            }
        }
        for ( ; i1<noEditModules.length; i1++ ) {
            mw.tools.removeClass(noEditModules[i], 'module');
        }
        for ( ; i2<edits.length; i2++ ) {
            var all = mw.ea.helpers.getElementsLike(":not(.element)", edits[i2]), i2a = 0;
            var allAllowDrops = edits[i2].querySelectorAll(".allow-drop"), i3a = 0;
            for( ; i3a<allAllowDrops.length; i3a++){
                mw.tools.addClass(allAllowDrops[i3a], 'element');
            }
            for( ; i2a<all.length; i2a++){
                if(!mw.tools.hasClass(all[i2a], 'module')){
                    if(mw.ea.canDrop(all[i2a])){
                        mw.tools.addClass(all[i2a], 'element');
                    }
                }
            }
        }


        if(document.body.classList){
            var displayEditor = mw.wysiwyg.isSelectionEditable();
            if(!displayEditor){
                var editor = document.querySelector('.mw_editor');
                if(editor && editor.contains(document.activeElement)) displayEditor = true;
            }
            var focusedNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
            var isSafeMode = mw.tools.firstParentOrCurrentWithAnyOfClasses(focusedNode, ['safe-mode']) ;
            var isPlainText = mw.tools.firstParentOrCurrentWithAnyOfClasses(focusedNode, ['plain-text']) ;
            document.body.classList[( displayEditor ? 'add' : 'remove' )]('mw-active-element-iseditable');
            document.body.classList[( isSafeMode ? 'add' : 'remove' )]('mw-active-element-is-in-safe-mode');
            document.body.classList[( isPlainText ? 'add' : 'remove' )]('mw-active-element-is-plain-text');
        }
    },
    findNearest:function(event,selectors){

    selectors = (selectors || mw.drag.section_selectors).slice(0);


    for(var ix = 0 ; i<selectors.length ; ix++){
        selectors[ix] = '.edit ' + selectors[ix].trim();
    }

    selectors = selectors.join(',');

      var coords = { y:99999999 },
          y = mw.event.page(event).y,
          all = document.querySelectorAll(selectors),
          i = 0,
          final = {
            element:null,
            position:null
          };
      for( ; i< all.length; i++){
        var ord = mw.tools.parentsOrder(all[i], ['edit', 'module']);
        if(ord.edit === -1 || ((ord.module !== -1 && ord.edit !== -1 ) && ord.module < ord.edit)){
          continue;
        }
        if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(all[i], ['allow-drop', 'nodrop'])){
          continue;
        }
        var el = mw.$(all[i]), offtop = el.offset().top;
        var v1 = offtop - y;
        var v2 = y - (offtop + el[0].offsetHeight);
        var v = v1 > 0 ? v1 : v2;
        if(coords.y > v){

          final.element = all[i];
        }
        if(coords.y > v && v1 > 0){
          final.position = 'top';
        }
        else if(coords.y > v && v2 > 0){
          final.position = 'bottom';
        }
        if(coords.y > v){

          coords.y = v
        }

      }
      return final;
    },
    display: function(el) {

        el = mw.$(el);
        var offset = el.offset();
        var width = el.outerWidth();
        var height = el.outerHeight();
        mw.dropable.css({
            top: offset.top + height,
            left: offset.left,
            width: width
        });
    },
    set: function(pos, offset, height, width) {
        if (pos === 'top') {

            mw.dropable.css({
                top: offset.top - 2,
                left: offset.left,
                width: width,
                height: ''
            });
            mw.dropable.data("position", "top");
            mw.dropable.addClass("mw_dropable_arr_up");
        } else if (pos === 'bottom') {

            mw.dropable.css({
                top: offset.top + height + 2,
                left: offset.left,
                width: width,
                height: ''
            });
            mw.dropable.data("position", "bottom");
            mw.dropable.removeClass("mw_dropable_arr_up");
            mw.dropable.removeClass("mw_dropable_arr_rigt");
        } else if (pos === 'left') {
            mw.dropable.data("position", 'left');
            mw.dropable.css({
                top: offset.top,
                height: height,
                width: '',
                left: offset.left
            });
        } else if (pos === 'right') {
            mw.dropable.data("position", 'right');
            mw.dropable.addClass("mw_dropable_arr_rigt");
            mw.dropable.css({
                top: offset.top,
                left: offset.left + width,
                height: height,
                width: ''
            });
        }
    }
};


 mw.triggerLiveEditHandlers = {
    cacheEnabled: false,
     reseSetCache: function(key) {
        this[key] = {};
     },
    shouldTrigger:function(key, node) {
        if(!this.cacheEnabled) return true;
        var countMax = 3;
        if(!this[key] || this[key].node !== node) {
            this[key] = {
                node:node,
                count:0
            };
        }
        if(this[key].count < countMax) {
            this[key].count++;
            return true;
        }
        return false;
    },
    _moduleRegister: null,
    module: function(ev){
        targetFrom = ev ? ev.target :  mw.mm_target;
        var module = mw.tools.firstMatchesOnNodeOrParent(targetFrom, '.module:not(.no-settings)');
        //var module = mw.tools.lastMatchesOnNodeOrParent(targetFrom, '.module:not(.no-settings)');
        var triggerTarget =  module.__disableModuleTrigger || module;
        if(module){
            //if(this.shouldTrigger('_moduleRegister', triggerTarget)) {
                mw.trigger("moduleOver", [triggerTarget, ev]);
            //}
        } else {
            if (
                mw.mm_target.id !== 'mw-handle-item-module'
                && mw.mm_target.id !== 'mw-handle-item-module-active'
                && !mw.tools.hasParentWithId(mw.mm_target, 'mw-handle-item-module')
                && !mw.tools.hasParentWithId(mw.mm_target, 'mw-handle-item-module-active')
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(mw.mm_target, ['mwInaccessibleModulesMenu'])) {
                /*if(this._moduleRegister !== null) {*/
                    mw.trigger("ModuleLeave", mw.mm_target);
                    /*this._moduleRegister = null;
                }*/
            }
        }
    },
    cloneable: function () {
        var cloneable = mw.tools.firstParentOrCurrentWithAnyOfClasses(mw.mm_target, ['cloneable', 'mw-cloneable-control']);

        if(!!cloneable){
            if(mw.tools.hasClass(cloneable, 'mw-cloneable-control')){
                mw.trigger("CloneableOver", [mw.drag._onCloneableControl.__target, true]);
            }
            else if(mw.tools.hasParentsWithClass(cloneable, 'mw-cloneable-control')){
                mw.trigger("CloneableOver", [mw.drag._onCloneableControl.__target, true]);
            }
            else{
                mw.trigger("CloneableOver", [cloneable, false]);
            }

        }
        else{
            if(mw.drag._onCloneableControl && mw.mm_target !== mw.drag._onCloneableControl){
                mw.drag._onCloneableControl.style.display = 'none';
            }
        }
    },
    _elementRegister:null,
    element: function(ev) {
        var element = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'element');
        if(element && this._elementRegister !== element) {
            this._elementRegister = element;
            if (!mw.tools.hasClass(element, 'module')
                && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['edit', 'module'])
                    && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop']))) {

                mw.trigger("ElementOver", [element, ev]);
            }
            else /*if(this._elementRegister !== null)*/{
                //if (!mw.tools.firstParentOrCurrentWithId(mw.mm_target, 'mw_handle_element')) {
                    this._elementRegister = null;
                    //mw.trigger("ElementLeave", element);
                //}
            }
        } else if(!element && !mw.tools.firstParentOrCurrentWithId(mw.mm_target, 'mw-handle-item-element')) {
            this._elementRegister = null;
            mw.trigger("ElementLeave");
        }
        if (mw.mm_target === mw.image_resizer && this._elementRegister !== mw.image.currentResizing[0]) {
            this._elementRegister = mw.image.currentResizing[0];
            mw.trigger("ElementOver", mw.image.currentResizing[0]);
        }
    },
    _layoutRegister:null,
    layout: function () {
         var targetLayout = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-layout-root');
         if (targetLayout && this._layoutRegister !== targetLayout) {
             this._layoutRegister = targetLayout;
             mw.trigger("LayoutOver", targetLayout);
         }
    },
     _rowRegister:null,
    row: function () {
         var row = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-row');

         if (row && this._rowRegister !== row) {
             this._rowRegister = row;
             mw.trigger("RowOver", row);
         } else if (this._rowRegister !== null) {
             this._rowRegister = null;
             mw.trigger("RowLeave", mw.mm_target);
         }
    },
     col: function () {
            if (!mw.drag.columns.resizing) {
                var column = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-col');
                if (column) {
                    mw.trigger("ColumnOver", column);
                } else {
                    mw.trigger("ColumnOut", mw.mm_target);
                }
            }
     }
 };
 mw.liveEditHandlers = function(event){
    if ( /*mw.emouse.x % 2 === 0 && */ mw.drag.columns.resizing === false ) {
        mw.triggerLiveEditHandlers.cloneable(event);
        mw.triggerLiveEditHandlers.layout(event);
        mw.triggerLiveEditHandlers.element(event);
        mw.triggerLiveEditHandlers.module(event);
        if (mw.drag.columns.resizing === false && mw.tools.hasParentsWithClass(mw.mm_target, 'edit') && (!mw.tools.hasParentsWithClass(mw.mm_target, 'module') ||
            mw.tools.hasParentsWithClass(mw.mm_target, 'allow-drop'))) {
            mw.triggerLiveEditHandlers.row();
            mw.triggerLiveEditHandlers.col();
        }
    }

    mw.image._dragTxt(event);

    var bg, bgTarget, bgCanChange;
    if(event.target){
      bg = event.target.style && event.target.style.backgroundImage && !mw.tools.hasClass(event.target, 'edit');
      bgTarget = event.target;
      if(!bg){
          var _c = 0, bgp = event.target;
          while (!bg || bgp === mwd.body){
              bgp = bgp.parentNode;
              if(!bgp) {
                  break;
              }
              _c++;
              bg = bgp.style && bgp.style.backgroundImage && !mw.tools.hasClass(bgp, 'edit');
              bgTarget = bgp;
          }
      }
    }

    if(bg){
        bgCanChange = mw.drag.columns.resizing === false
        && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(bgTarget, ['edit','module']) || mw.tools.hasClass(event.target, 'element'));
    }

    if (!mw.image.isResizing && mw.image_resizer) {

        if (event.target.nodeName === 'IMG' && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(event.target, ['edit','module'])) && mw.drag.columns.resizing === false) {
            mw.image_resizer._show();
            mw.imageResize.resizerSet(event.target, false);
        }
        else if (bg && bgCanChange) {
            mw.image_resizer._show();
            mw.imageResize.resizerSet(bgTarget, false);
        }

        else if(mw.tools.hasClass(mw.mm_target, 'mw-image-holder-content')||mw.tools.hasParentsWithClass(mw.mm_target, 'mw-image-holder-content')){
            mw.image_resizer._show();
            mw.imageResize.resizerSet(mw.tools.firstParentWithClass(mw.mm_target, 'mw-image-holder').querySelector('img'), false);
        }
        else {
            if (!event.target.mwImageResizerComponent) {
                if(mw.image_resizer){
                    mw.image_resizer._hide();
                }
            }
        }
    }
};


mw.liveNodeSettings = {
    _working: false,
    set: function (type, el) {
        if (this._working) return;
        this._working = true;
        var scope = this;
        setTimeout(function () {
            scope._working = false;
        }, 78);

        if(this[type]){
            mw.sidebarSettingsTabs.set(2);
            return this[type](el);
        }
    },
    element: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

    },
    none: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

    },
    module: function (el) {
        mw.live_edit.showSettings(undefined, {mode:"sidebar", liveedit:true})
    },
    image: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

        mw.$("#mw-live-edit-sidebar-image-frame")
            .contents()
            .find("#mwimagecurrent")
            .attr("src", el.src)

    },
    initImage: function () {
        var url = mw.external_tool('imageeditor');
        mw.$("#js-live-edit-image-settings-holder").append('<iframe src="' + url + '" frameborder="0" id="mw-live-edit-sidebar-image-frame"></iframe>');
    },
    icon: function () {

    },
    __is_sidebar_opened: function () {
        if (mw.liveEditSettings  &&  mw.liveEditSettings.active) {
            return true;
        }
    }
};

$(document).ready(function(){
    mw.on('liveEditSettingsReady', function(){
        mw.liveNodeSettings.initImage();
    });
});

})();

(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/liveedit_widgets.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveEditWidgets = {
    _cssEditorInSidebarAccordion : null,
    cssEditorInSidebarAccordion : function () {
        if(!this._cssEditorInSidebarAccordion){
            this._cssEditorInSidebarAccordion = mwd.createElement('iframe') ;
            this._cssEditorInSidebarAccordion.id = 'mw-css-editor-sidebar-iframe' ;
            this._cssEditorInSidebarAccordion.src = mw.external_tool('rte_css_editor');
            this._cssEditorInSidebarAccordion.style.opacity = 0;
            this._cssEditorInSidebarAccordion.scrolling = 'no';
            this._cssEditorInSidebarAccordion.frameBorder = 0;
            var holder = mwd.querySelector('#mw-css-editor-sidebar-iframe-holder');
            holder.appendChild(this._cssEditorInSidebarAccordion);
            mw.tools.loading(holder, 90);
            mw.tools.iframeAutoHeight(this._cssEditorInSidebarAccordion);
            this._cssEditorInSidebarAccordion.onload = function () {
                this.contentWindow.document.body.style.padding = 0;
                this.contentWindow.document.body.style.backgroundColor = 'transparent';
                mw.tools.loading(holder, false);
                this.style.opacity = 1;
            };
            mw.$(this._cssEditorInSidebarAccordion)
                .height($(this._cssEditorInSidebarAccordion)
                    .parents('.mw-ui-box').outerHeight() -
                    mw.$(this._cssEditorInSidebarAccordion).parents('.tabitem').outerHeight());
        }
        return this._cssEditorInSidebarAccordion;
    },
    _tplSettings : null,
    loadTemplateSettings: function (url) {
        if (!this._tplSettings) {
            this._tplSettings = mwd.createElement('iframe') ;
            this._tplSettings.id = 'mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame' ;
            this._tplSettings.className = 'mw-live-edit-sidebar-settings-iframe' ;
            this._tplSettings.src = url;
            this._tplSettings.scrolling = 'no';
            this._tplSettings.frameBorder = 0;
            mw.$('#mw-live-edit-sidebar-settings-iframe-holder-template-settings').html(this._tplSettings);
            mw.tools.iframeAutoHeight(this._tplSettings);
            this._tplSettings.onload = function () {
                this.contentWindow.document.querySelector('.mw-module-live-edit-settings').style.padding = 0;
            };
            mw.$(this._tplSettings)
                .height($(this._tplSettings)
                        .parents('.mw-ui-box').outerHeight() -
                    mw.$(this._tplSettings).parents('.tabitem').outerHeight());
        }
        return this._tplSettings;
    }
};

mw.liveEditTools = {
    isLayout: function (node) {
        return (!!node && !!node.getAttribute && (node.getAttribute('data-module-name') === 'layouts' || node.getAttribute('data-type') === 'layouts'));
    }
};

})();

(() => {
/*!****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/liveedit.js ***!
  \****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

mw.liveedit = {};

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

mw.isDragItem = mw.isBlockLevel = function (obj) {
    return mw.ea.helpers.isBlockLevel(obj);
};

!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);

mw.tools.addClass(mwd.body, 'mw-live-edit');

$(document).ready(function() {

    if (("ontouchstart" in document.documentElement)) {
        mw.$('body').addClass('touchscreen-device');
    }

    mw.liveedit.initReady();
    mw.liveedit.handleEvents();
    mw.liveedit.handleCustomEvents();

    mw.liveedit.cssEditor = new mw.liveeditCSSEditor();

});

mw.require('stylesheet.editor.js');
$(window).on("load", function() {
    mw.liveedit.initLoad();
});

$(window).on('resize', function() {
    mw.liveedit.toolbar.setEditor();
});





})();

(() => {
/*!**********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/manage.content.js ***!
  \**********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.manageContent = {
    w: '1220px',
    page: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-page&recommended_parent=" + mw.settings.page_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    category: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=categories/edit_category&live_edit=true&quick_edit=false&id=mw-quick-category&recommended_parent=" + mw.settings.page_id,
            width: '600px',
//            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            overlay: true,
            title: 'New Category',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    edit: function (id, content_type, subtype, parent, category) {
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

        if (id === 0) {
            actionType = 'Add';
        } else {
            actionType = 'Edit';
        }

        var actionOf = 'Content';
        if (content_type === 'post') {
            actionOf = 'Post'
        } else if (content_type === 'page') {
            actionOf = 'Page'
        } else if (content_type === 'product') {
            actionOf = 'Product'
        } else if (content_type === 'category') {
            actionOf = 'Category'
        }

        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit&live_edit=true&quick_edit=false&is-current=true&id=mw-quick-page&content-id=" + id + str,
            width: '800px',
//            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            id: 'quick_page',
            overlay: true,
            title: actionType + ' ' + actionOf,
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    page_2: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/quick_add&live_edit=true&id=mw-new-content-add-ifame",
            width: this.w,
            height: 'auto',
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    post: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-post&subtype=post&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_post',
            overlay: true,
            title: 'New Post'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    product: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-product&subtype=product&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_product',
            overlay: true,
            title: 'New Product'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    }
}

})();

(() => {
/*!***********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/modules.toolbar.js ***!
  \***********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.modulesToolbar = {
    init: function (selector) {
        var items = selector || ".modules-list li[data-module-name]";
        var $items = mw.$(items);
        $items.draggable({
            revert: true,
            revertDuration: 0,
            start: function(a, b) {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw.GlobalModuleListHelper;
                mw.$(mwd.body).addClass("dragStart");
                mw.image_resizer._hide();

            },
            stop: function() {
                mw.isDrag = false;
                mw.pauseSave = true;
                var el = this;
                mw.$(mwd.body).removeClass("dragStart");
                setTimeout(function() {
                    mw.drag.load_new_modules();
                    mw.liveedit.recommend.increase($(mw.dragCurrent).attr("data-module-name"));
                    mw.drag.toolbar_modules(el);
                }, 200);
            }
        });
        $items.on('mouseenter touchstart', function() {
            mw.$(this).draggable("option", "helper", function() {
                var el = $(this);
                var clone = el.clone(true);
                clone.appendTo(mwd.body);
                clone.addClass('mw-module-drag-clone');
                mw.GlobalModuleListHelper = clone[0];
                clone.css({
                    width: el.width(),
                    height: el.height()
                });
                return clone[0];
            });
        });
       /* $items.on("click mousedown mouseup", function(e) {
            e.preventDefault();
            if (e.type === 'click') {
                return false;
            }
            if (e.type === 'mousedown') {
                this.mousedown = true;
            }
            if (e.type === 'mouseup' && e.which === 1 && !!this.mousedown) {
                $items.each(function() {
                    this.mousedown = false;
                });
                if (!mw.isDrag && mww.getSelection().rangeCount > 0 && mwd.querySelector('.mw_modal') === null && mw.modulesClickInsert) {
                    var html = this.outerHTML;
                    mw.wysiwyg.insert_html(html);
                    mw.drag.load_new_modules();
                }
            }
        });*/
    }
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/padding.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.require('tempcss.js');

(function(mw){

    mw.paddingEditor = function( options ) {

        options = options || {};

        var defaults = {
            height: 10
        };

        this.settings = $.extend({}, defaults, options);

        this._pageY = -1;
        this._active = null;
        this._paddingTopDown = false;
        this._paddingBottomDown = false;
        var scope = this;

        this.create = function() {
            this.paddingTop = document.createElement('div');
            this.paddingTop.className = 'mw-padding-ctrl mw-padding-ctrl-top';

            this.paddingBottom = document.createElement('div');
            this.paddingBottom.className = 'mw-padding-ctrl mw-padding-ctrl-bottom';

            this.paddingTop.style.height = this.settings.height + 'px';
            this.paddingBottom.style.height = this.settings.height + 'px';


            // this.paddingBottom.style.visibility = 'hidden';
            // this.paddingTop.style.visibility = 'hidden';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };


        this.record = function() {
            if(!scope._active){
                return;
            }

            var rec_scope = scope._active;
            if(rec_scope.parentNode){
                rec_scope = rec_scope.parentNode;
            }
        //    var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(scope._active.parentNode, ['edit', 'element', 'module']);
            var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(rec_scope, ['edit', 'element', 'module']);
            mw.liveEditState.record({
                target:root,
                value: root.innerHTML
            });
        };



        if(scope && scope._active){

        }
        this.handleMouseMove = function() {
            mw.$(this.paddingTop).on('mousedown touchstart', function(){
                scope._paddingTopDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
            });
            mw.$(this.paddingBottom).on('mousedown touchstart', function(){
                scope._paddingBottomDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
                scope.record();
            });
            mw.$(document.body).on('mouseup touchend', function(){
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';

                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                scope.activeMark(false);
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.event.windowLeave(function (e) {
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';
                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.$(document).on('mousemove touchmove', function(e){
                if(scope._active){
                    var isDown = e.pageY < scope._pageY;
                    var inc = isDown ? scope._pageY - e.pageY : e.pageY - scope._pageY;
                    var curr;
                    if(scope && scope._active && scope._paddingTopDown){
                        scope._working = true;
                        curr = scope._active._currPaddingTop || (parseFloat($(scope._active).css('paddingTop')));

                        if(isDown){
                            scope._active.style.paddingTop = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingTop = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingTop = parseFloat(scope._active.style.paddingTop);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    } else if(scope && scope._active && scope._paddingBottomDown){
                        scope._working = true;
                        curr = scope._active._currPaddingBottom || (parseFloat($(scope._active).css('paddingBottom')));
                        if(isDown){
                            scope._active.style.paddingBottom = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingBottom = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingBottom = parseFloat(scope._active.style.paddingBottom);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    }
                    scope._pageY = e.pageY;
                    scope._activePadding = curr;

                }

                if (scope._active && mw.liveedit.data.get('move', 'hasLayout')) {
                    scope.show();
                    scope.position();
                } else {
                    scope.hide();
                }
            });
        };
        this.show = function(){
            scope.paddingTop.style.display = 'block';
            scope.paddingBottom.style.display = 'block';
        };

        this.hide = function(){
            scope.paddingTop.style.display = 'none';
            scope.paddingBottom.style.display = 'none';
        };

        this.position = function(targetIsLayout) {
            var $el = mw.$(targetIsLayout);
            var off = $el.offset();
            scope._active = targetIsLayout;
            scope.paddingTop.style.top = off.top + 'px';
            scope.paddingBottom.style.top = (off.top + $el.outerHeight() - this.settings.height) + 'px';
        };

        this.selectors = [
            '.mw-padding-gui-element',
            '.mw-padding-control-element',
        ];

        this.prepareSelectors = function(){
            /* var i = 0;
            for( ; i < this.selectors.length; i++){
                if(this.selectors[i].indexOf('[id') === -1){
                    this.selectors[i] += '[id]';
                }
            } */
        };

        this.addSelector = function(selector){
            this.selectors.push(selector);
            this.prepareSelectors();
        };

        this.eventsHandlers = function() {
            mw.on('moduleOver ModuleClick', function(e, el){
                if(!scope._working){
                    var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(el, scope.selectors);
                    if(targetIsLayout){
                        if(mw.tools.hasClass(targetIsLayout, 'module')){
                            var child = mw.$(targetIsLayout).children(scope.selectors.join(','))[0];
                            targetIsLayout = child || targetIsLayout;
                        }
                        scope.position(targetIsLayout);
                    } else {

                    }
                }
            });
        };

        this.init = function() {
            this.create();
            this.eventsHandlers();
            this.handleMouseMove();
            this.prepareSelectors();
            this.hide();
        };

        this.activeMark = function (state) {
            if(typeof state === 'undefined') {
                state = false;
            }
            if(!this._activeMark) {
                this._activeMark = document.createElement('div');
                this._activeMark.className = 'mw-padding-control-active-mark';
                document.body.appendChild(this._activeMark);
            }
            if (state) {
                mw.tools.addClass(this._activeMark, 'active');
                var active = scope._paddingTopDown ? scope.paddingTop : scope.paddingBottom;
                var off = scope._active.getBoundingClientRect();
                this._activeMark.style.left = off.left + 'px';
                this._activeMark.style.width = off.width + 'px';
                this._activeMark.style.height = scope._activePadding + 'px';
                if (scope._paddingTopDown) {
                    this._activeMark.style.top = (off.top + scrollY) + 'px';
                } else {
                    this._activeMark.style.top = ((off.top + scrollY + mw.$(scope._active).outerHeight()) - parseFloat(scope._active.style.paddingBottom)) + 'px';
                }
            } else {
                mw.tools.removeClass(this._activeMark, 'active');
            }
        };

        this.generateCSS = function(obj, media){
            if(!obj || !scope._active) return;

            media = (media || 'all').trim();
            var selector = mw.tools.generateSelectorForNode(scope._active);
            var objCss = '{';
            for (var i in obj) {
                objCss += (i + ':' + obj[i] + ';');
            }
            objCss += '}';
            var css = '@media ' + media  + ' {' + selector + objCss + '}';
            return css;
        };

        this.info = function() {
            if(!this._info){
                this._info = document.createElement('div');
                this._info.className = 'mw-padding-control-info';
                document.body.appendChild(this._info);
            }
            mw.$(this._info).addClass('active');
            var off;
            if (scope._paddingTopDown) {
                off = mw.$(scope.paddingTop).offset();
                this._info.style.top = (off.top + scope.settings.height) + 'px';
                this._info.innerHTML = scope._active.style.paddingTop;
            } else if (scope._paddingBottomDown) {
                off = mw.$(scope.paddingBottom).offset();
                this._info.style.top = (off.top - scope.settings.height - 30) + 'px';
                this._info.innerHTML = scope._active.style.paddingBottom;
            }
            this._info.style.left = (off.left + (scope._active.clientWidth/2)) + 'px';
        };
        this.init();
    };

})(window.mw);

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/plus.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.drag = mw.drag || {};
mw.drag.plus = {
    locked: false,
    disabled: false,
   // mouse_moved: false,
    init: function (holder) {

        if(this.disabled) return;

        mw.drag.plusTop = mwd.querySelector('.mw-plus-top');
        mw.drag.plusBottom = mwd.querySelector('.mw-plus-bottom');

        if(mw.drag.plusTop) {
            mw.drag.plusTop.style.top = -9999 + 'px';
        }
        if(mw.drag.plusBottom) {
            mw.drag.plusBottom.style.top = -9999 + 'px';
        }
        mw.$(holder).on('mousemove touchmove click', function (e) {


            if (mw.drag.plus.locked === false && mw.isDrag === false) {
                if (e.pageY % 2 === 0 && mw.tools.isEditable(e)) {
                    var whichPlus;

                    var node = mw.drag.plus.selectNode(e.target);
                    if(node && e.type === 'mousemove') {
                        var off = $(node).offset();
                        whichPlus = (e.pageY - off.top) > ((off.top + node.offsetHeight) - e.pageY) ? 'top' : 'bottom';
                    }
                    mw.drag.plus.set(node, whichPlus);
                    mw.$(mwd.body).removeClass('editorKeyup');
                }
            }
            else {
                mw.drag.plusTop.style.top = -9999 + 'px';
                mw.drag.plusBottom.style.top = -9999 + 'px';
            }
        });
        mw.$(holder).on('mouseleave', function (e) {
            if (mw.drag.plus.locked === false && (e.target !== mw.drag.plusTop && e.target !== mw.drag.plusBottom) ) {
                mw.drag.plus.set(undefined);
            }
        });
        mw.drag.plus.action();
    },
    selectNode: function (target) {

        if(!target || mw.tools.hasAnyOfClassesOnNodeOrParent(target, ['noplus', 'noedit', 'noplus']) || mw.tools.hasClass(target, 'edit')) {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return;
        }
        var comp = mw.tools.firstMatchesOnNodeOrParent(target, ['.module', '.element', 'p', '.mw-empty']);

        if (comp
            // && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['regular-mode', 'safe-mode'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']))  {
            return comp;
        }
        else {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
    },
    set: function (node, whichPlus) {
            if (typeof node === 'undefined') {
                return;
            }
            var $node = mw.$(node)
            var off = $node.offset(),
                toolbar = mwd.querySelector('#live_edit_toolbar');
            var oleft = Math.max(0, off.left - 10);
            if(toolbar && off.top < toolbar.offsetHeight){
              off.top = toolbar.offsetHeight + 10;
            }
            mw.drag.plusTop.style.top = off.top + 'px';
            mw.drag.plusTop.style.left = oleft + ($node.width()/2) + 'px';
            // mw.drag.plusTop.style.display = 'block';
            mw.drag.plusTop.currentNode = node;
            mw.drag.plusBottom.style.top = (off.top + node.offsetHeight) + 'px';
            mw.drag.plusBottom.style.left = oleft + ($node.width()/2) + 'px';
            if(whichPlus) {
                if(whichPlus === 'top') {
                    mw.drag.plusTop.style.top = -9999 + 'px';

                } else {
                     mw.drag.plusBottom.style.top = -9999 + 'px';
                }
            }
            mw.drag.plusBottom.currentNode = node;
            mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');

    },
    tipPosition: function (node) {
        var off = mw.$(node).offset();
        if (off.top > 130) {
            if ((off.top + node.offsetHeight) < ($(mwd.body).height() - 130)) {
                return 'right-center';
            }
            else {
                return 'right-bottom';
            }
        }
        else {
            return 'right-top';
        }
    },
    action: function () {
        var pls = [mw.drag.plusTop, mw.drag.plusBottom];
        var $pls = mw.$(pls);
        $pls.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-module-plus-hover');
            mw.liveEditSelector.select(mw.drag.plusTop.currentNode);
        });
        $pls.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-module-plus-hover')
        });
        $pls.on('click', function () {
            var other = this === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
            if (!mw.tools.hasClass(this, 'active')) {
                mw.tools.addClass(this, 'active');
                mw.tools.removeClass(other, 'active');
                mw.drag.plus.locked = true;
                mw.$('.mw-tooltip-insert-module').remove();
                mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';
                var tip = new mw.tooltip({
                    content: mwd.getElementById('plus-modules-list').innerHTML,
                    element: this,
                    position: mw.drag.plus.tipPosition(this.currentNode),
                    template: 'mw-tooltip-default mw-tooltip-insert-module',
                    id: 'mw-plus-tooltip-selector'
                });
                setTimeout(function (){
                    $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
                }, 10)
                mw.tabs({
                    nav: tip.querySelectorAll('.mw-ui-btn'),
                    tabs: tip.querySelectorAll('.module-bubble-tab'),
                });

                mw.$('.mw-ui-searchfield', tip).on('keyup paste', function () {
                    var resultsLength = mw.drag.plus.search(this.value, tip);
                    if (resultsLength === 0) {
                        mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                    }
                    else {
                        mw.$(".module-bubble-tab-not-found-message").hide();
                    }
                });
            }
        });
        mw.$('#plus-modules-list li').each(function () {
            var name = mw.$(this).attr('data-module-name');
            if(name === 'layout'){
                var template = mw.$(this).attr('template');
                mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className, template:"'+template+'"})');
            } else {
                mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className})');
            }
        });
    },
    search: function (val, root) {
        var all = root.querySelectorAll('.module_name'),
            l = all.length,
            i = 0;
        val = val.toLowerCase();
        var found = 0;
        var isEmpty = val.replace(/\s+/g, '') === '';
        for (; i < l; i++) {
            var text = all[i].innerHTML.toLowerCase();
            var li = mw.tools.firstParentWithTag(all[i], 'li');
            var filter = (li.dataset.filter || '').trim().toLowerCase();
            if (text.contains(val) || isEmpty) {
                li.style.display = '';
                if (text.contains(val)) found++;
            }
            else if(filter.contains(val)){
                li.style.display = '';
                found++;
            }
            else {
                li.style.display = 'none';
            }
        }
        return found;
    }
};

InsertModule = function (module, cls) {
    var id = 'mwemodule-' + mw.random(), el = '<div id="' + id + '"></div>', action;
    if (mw.drag.plusActive === 'top') {
        action = 'before';
        if(mw.tools.hasClass(mw.drag.plusTop.currentNode, 'allow-drop')) {
            action = 'prepend';
        }
    }
    else if (mw.drag.plusActive === 'bottom') {
        action = 'after';
        if(mw.tools.hasClass(mw.drag.plusTop.currentNode, 'allow-drop')) {
            action = 'append';
        }
    }
    mw.$(mw.drag.plusBottom.currentNode)[action](el);

     mw.load_module(module, '#' + id, function () {
        var node = document.getElementById(id);

        mw.wysiwyg.change(node);

        mw.drag.plus.locked = false;
        mw.drag.fixes();
         setTimeout(function () {
            mw.drag.fix_placeholders();
        }, 40);

        mw.dropable.hide();
    }, cls);
    mw.$('.mw-tooltip').hide();
};



})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/recommend.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.recommend = {
    get: function () {
        var cookie = mw.cookie.getEncoded("recommend");
        if (!cookie) {
            return {}
        }
        else {
            try {
                var val = $.parseJSON(cookie);
            }
            catch (e) {
                return;
            }
            return val;
        }
    },
    increase: function (item_name) {
        var json = this.get() || {};
        var curr = parseFloat(json[item_name]);
        if (isNaN(curr)) {
            json[item_name] = 1;
        }
        else {
            json[item_name] += 1;
        }
        var tostring = JSON.stringify(json);
        mw.cookie.setEncoded("recommend", tostring, false, "/");
    },
    orderRecommendObject: function () {
        var obj = this.get();
        if (!mw.tools.isEmptyObject(obj)) {
            var arr = [];
            for (var x in obj) {
                arr.push(x)
                arr.sort(function (a, b) {
                    return a[1] - b[1]
                })
            }
            return arr;
        }
    },
    set: function () {
        var arr = this.orderRecommendObject(), l = arr.length, i = 0;
        for (; i < l; i++) {
            var m = mw.$('#tab_modules .module-item[data-module-name="' + arr[i] + '"]')[0];
            if (m !== null && typeof m !== 'undefined') {
                mw.$('#tab_modules ul').prepend(m);
            }
        }
    }
};

})();

(() => {
/*!****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/selector.js ***!
  \****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.css = function(element, css){
    for(var i in css){
        element.style[i] = typeof css[i] === 'number' ? css[i] + 'px' : css[i];
    }
}
mw.Selector = function(options) {

    options = options || {};

    var defaults = {
        autoSelect: true,
        document: document,
        toggleSelect: false, // second click unselects element
        strict: false // only match elements that have id
    };

    this.options = $.extend({}, defaults, options);
    this.document = this.options.document;


    this.buildSelector = function(){
        var stop = this.document.createElement('div');
        var sright = this.document.createElement('div');
        var sbottom = this.document.createElement('div');
        var sleft = this.document.createElement('div');

        stop.className = 'mw-selector mw-selector-top';
        sright.className = 'mw-selector mw-selector-right';
        sbottom.className = 'mw-selector mw-selector-bottom';
        sleft.className = 'mw-selector mw-selector-left';

        this.document.body.appendChild(stop);
        this.document.body.appendChild(sright);
        this.document.body.appendChild(sbottom);
        this.document.body.appendChild(sleft);

        this.selectors.push({
            top:stop,
            right:sright,
            bottom:sbottom,
            left:sleft,
            active:false
        });
    };
    this.getFirstNonActiveSelector = function(){
        var i = 0;
        for( ; i <  this.selectors.length; i++){
            if(!this.selectors[i].active){
                return this.selectors[i]
            }
        }
        this.buildSelector();
        return this.selectors[this.selectors.length-1];
    };
    this.deactivateAll = function(){
         var i = 0;
        for( ; i <  this.selectors.length; i++){
            this.selectors[i].active = false;
        }
    };


    this.pause = function(){
        this.active(false);
        this.hideAll();
    };
    this.hideAll = function(){
        var i = 0;
        for( ; i <  this.selectors.length; i++){
            this.hideItem(this.selectors[i]);
        }
        this.hideItem(this.interactors)
    };

    this.hideItem = function(item){

        item.active = false;
        for (var x in item){
            if(!item[x]) continue;
            item[x].style.visibility = 'hidden';
        }
    };
    this.showItem = function(item){
        for (var x in item) {
            if(typeof item[x] === 'boolean' || !item[x].className || item[x].className.indexOf('mw-selector') === -1) continue;
            item[x].style.visibility = 'visible';
        }
    };

    this.buildInteractor = function(){
        var stop = this.document.createElement('div');
        var sright = this.document.createElement('div');
        var sbottom = this.document.createElement('div');
        var sleft = this.document.createElement('div');

        stop.className = 'mw-selector mw-interactor mw-selector-top';
        sright.className = 'mw-selector mw-interactor mw-selector-right';
        sbottom.className = 'mw-selector mw-interactor mw-selector-bottom';
        sleft.className = 'mw-selector mw-interactor mw-selector-left';

        this.document.body.appendChild(stop);
        this.document.body.appendChild(sright);
        this.document.body.appendChild(sbottom);
        this.document.body.appendChild(sleft);

        this.interactors = {
            top:stop,
            right:sright,
            bottom:sbottom,
            left:sleft
        };
    };
    this.isSelected = function(e){
        var target = e.target?e.target:e;
        return this.selected.indexOf(target) !== -1;
    };

    this.unsetItem = function(e){
        var target = e.target?e.target:e;
        for(var i = 0;i<this.selectors.length;i++){
            if(this.selectors[i].active === target){
                this.hideItem(this.selectors[i]);
                break;
            }
        }
        this.selected.splice(this.selected.indexOf(target), 1);
    };

    this.positionSelected = function(){
        for(var i = 0;i<this.selectors.length;i++){
            this.position(this.selectors[i], this.selectors[i].active)
        }
    };
    this.position = function(item, target){
        var off = mw.$(target).offset();
        mw.css(item.top, {
            top:off.top,
            left:off.left,
            width:target.offsetWidth
        });
        mw.css(item.right, {
            top:off.top,
            left:off.left+target.offsetWidth,
            height:target.offsetHeight
        });
        mw.css(item.bottom, {
            top:off.top+target.offsetHeight,
            left:off.left,
            width:target.offsetWidth
        });
        mw.css(item.left, {
            top:off.top,
            left:off.left,
            height:target.offsetHeight
        });
    };

    this.setItem = function(e, item, select, extend){
        if(!e || !this.active()) return;
        var target = e.target ? e.target : e;
        if (this.options.strict) {
            target = mw.tools.firstMatchesOnNodeOrParent(target, ['[id]', '.edit']);
        }
        var validateTarget = !mw.tools.firstMatchesOnNodeOrParent(target, ['.mw-control-box', '.mw-defaults']);
        if(!target || !validateTarget) return;
        if($(target).hasClass('mw-select-skip')){
            return this.setItem(target.parentNode, item, select, extend);
        }
        if(select){
            if(this.options.toggleSelect && this.isSelected(target)){
                this.unsetItem(target);
                return false;
            }
            else{
                if(extend){
                    this.selected.push(target);
                }
                else{
                    this.selected = [target];
                }
                mw.$(this).trigger('select', [this.selected]);
            }

        }


        this.position(item, target);

        item.active = target;

        this.showItem(item);
    };

    this.select = function(e, target){
        if(!e && !target) return;
        if(!e.nodeType){
            target = target || e.target;
        } else{
            target = e;
        }

        if(e.ctrlKey){
            this.setItem(target, this.getFirstNonActiveSelector(), true, true);
        }
        else{
            this.hideAll();
            this.setItem(target, this.selectors[0], true, false);
        }

    };

    this.deselect = function(e, target){
        e.preventDefault();
        target = target || e.target;

        this.unsetItem(target);

    };

    this.init = function(){
        this.buildSelector();
        this.buildInteractor();
        var scope = this;
        mw.$(this.root).on("click", function(e){
            if(scope.options.autoSelect && scope.active()){
                scope.select(e);
            }
        });

        mw.$(this.root).on( "mousemove touchmove touchend", function(e){
            if(scope.options.autoSelect && scope.active()){
                scope.setItem(e, scope.interactors);
            }
        });
        mw.$(this.root).on( 'scroll', function(){
            scope.positionSelected();
        });
        mw.$(window).on('resize orientationchange', function(){
            scope.positionSelected();
        });
    };

    this._active = false;
    this.active = function (state) {
        if(typeof state === 'undefined') {
            return this._active;
        }
        if(this._active !== state) {
            this._active = state;
            mw.$(this).trigger('stateChange', [state]);
        }
    };
    this.selected = [];
    this.selectors = [];
    this.root = options.root;
    this.init();
};

})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/source-edit.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.editSource = function (node) {
    if (!mw._editSource) {
        mw._editSource = {
            wrapper: document.createElement('div'),
            area: document.createElement('textarea'),
            ok: document.createElement('mwbtn'),
            cancel: document.createElement('mwbtn'),
            nav:document.createElement('div'),
            validator:document.createElement('div')
        };
        mw.$(mw._editSource.ok).addClass('mw-ui-btn mw-ui-btn-medium mw-ui-btn-info').html(mw.lang('OK'));
        mw.$(mw._editSource.cancel).addClass('mw-ui-btn mw-ui-btn-medium').html(mw.lang('Cancel'));

        mw._editSource.wrapper.appendChild(mw._editSource.area);
        mw._editSource.wrapper.appendChild(mw._editSource.nav);
        mw._editSource.nav.appendChild(mw._editSource.cancel);
        mw._editSource.nav.appendChild(mw._editSource.ok);
        mw._editSource.nav.className = 'mw-inline-source-editor-buttons';
        mw._editSource.wrapper.className = 'mw-inline-source-editor';
        document.body.appendChild(mw._editSource.wrapper);
        mw.$(mw._editSource.cancel).on('click', function () {
            mw.$(mw._editSource.target).html(mw._editSource.area.value);
            mw.$(mw._editSource.wrapper).removeClass('active');
            mw._editSource.ok.disabled = false;
        });
        mw.$(mw._editSource.area).on('input', function () {
            mw._editSource.validator.innerHTML = mw._editSource.area.value;
            mw._editSource.ok.disabled = mw._editSource.validator.innerHTML !== mw._editSource.area.value;
            mw._editSource.ok.classList[mw._editSource.ok.disabled ? 'add' : 'remove']('disabled');
            var hasErr = mw.$('.mw-inline-source-editor-error', mw._editSource.nav);
            if(mw._editSource.ok.disabled) {
                if(!hasErr.length) {
                    mw.$(mw._editSource.nav).prepend('<span class="mw-inline-source-editor-error">' + mw.lang('Invalid HTML') + '</span>');
                }
            }
            else {
                hasErr.remove()
            }
        });
        mw.$(mw._editSource.ok).on('click', function () {
            if(!mw._editSource.ok.disabled){
                mw.$(mw._editSource.target).html(mw._editSource.area.value);
                mw.$(mw._editSource.wrapper).removeClass('active');
                mw.wysiwyg.change(mw._editSource.target);
            }
        });
    }
    mw._editSource.area.value = node.innerHTML;
    mw._editSource.target = node;
    var $node = mw.$(node), off = $node.offset(), nwidth = $node.outerWidth();
    var sl = mw.$('.mw-live-edit-sidebar-tabs-wrapper').offset();
    if (off.left + nwidth > sl.left) {
        off.left -= ((off.left + nwidth) - sl.left + 10);
    }
    mw.$(mw._editSource.area)
        .height($node.outerHeight())
        .width(nwidth);

    mw.$(mw._editSource.wrapper)
        .css(off)
        .addClass('active');


};

})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/stylesheet.editor.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.require('libs/cssjson/cssjson.js');


mw.liveeditCSSEditor = function (config) {
    var scope = this;
    config = config || {};
    config.document = config.document || document;
    var node = document.querySelector('link[href*="live_edit"]');
    var defaults = {
        cssUrl: node ? node.href : null,
        saveUrl: mw.settings.api_url + "current_template_save_custom_css"
    };
    this.settings = $.extend({}, defaults, config);

    this.json = null;

    this.getByUrl = function (url, callback) {
        return $.get(url, function (css) {
            callback.call(this, css)
        });
    };

    this.getLiveeditCSS = function () {
        if( this.settings.cssUrl ) {
            this.getByUrl( this.settings.cssUrl, function (css) {
                if(/<\/?[a-z][\s\S]*>/i.test(css)) {
                    scope.json = {};
                    scope._css = '';
                } else {
                    scope.json = CSSJSON.toJSON(css);
                    scope._css = css;
                }
                $(scope).trigger('ready');
            });
        }
        else {
            scope.json = {};
            scope._css = '';
            $(scope).trigger('ready');
        }
    };


    this._cssTemp = function (json) {
        var css = CSSJSON.toCSS(json);
        if(!mw.liveedit._cssTemp) {
            mw.liveedit._cssTemp = mw.tools.createStyle('#mw-liveedit-dynamic-temp-style', css, document.body);
            mw.liveedit._cssTemp.id = 'mw-liveedit-dynamic-temp-style';
        } else {
            mw.liveedit._cssTemp.innerHTML = css;
        }
    };

    this.changed = false;
    this._temp = {children: {}, attributes: {}};
    this.temp = function (node, prop, val) {
        this.changed = true;
        var sel = mw.tools.generateSelectorForNode(node);
        if(!this._temp.children[sel]) {
            this._temp.children[sel] = {};
        }
        if (!this._temp.children[sel].attributes ) {
            this._temp.children[sel].attributes = {};
        }
        this._temp.children[sel].attributes[prop] = val;
        this._cssTemp(this._temp);
    };

    this.timeOut = null;

    this.save = function () {
        this.json = $.extend(true, {}, this.json, this._temp);
        this._css = CSSJSON.toCSS(this.json).replace(/\.\./g, '.').replace(/\.\./g, '.');
    };

    this.publish = function (callback) {
        var css = {
            css_file_content: this.getValue()
        };
        $.post(this.settings.saveUrl, css, function (res) {
            scope.changed = false;
            if(callback) {
                callback.call(this, res);
            }
        });
    };

    this.publishIfChanged = function (callback) {
        if(this.changed) {
            this.publish(callback);
        }
    };

    this.getValue = function () {
        this.save();
        return this._css;
    };

    this.init = function () {
        this.getLiveeditCSS();
    };

    this.init();

};


})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/tempcss.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tempCSS = function(options){

    var scope = this;

    options = options || {};

    var defaults = {
        document: document,
        css: {}
    };

    this.settings = $.extend({}, defaults, options);

    this.styleElement = function() {
        if(!this._styleElement){
            this._styleElement = this.settings.document.createElement('style');
            this._styleElement.type = 'text/css';
            this._styleElement.appendChild(document.createTextNode('')); // webkit
            this.settings.document.body.appendChild(this._styleElement);
        }
        return this._styleElement;
    };

    this.modifyObject = function(){

    };

    this.addStyle = function(element, style, media){
        if(!element) return;
        if(element.tagName) {
            element = mw.tools.generateSelectorForNode(element);
        }
    };
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/toolbar.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit.toolbar = {
    fixPad: function () {
        mwd.body.style.paddingTop = parseFloat($(mwd.body).css("paddingTop")) + mw.$("#live_edit_toolbar").height() + 'px';
    },
    setEditor: function(){
        /*mw
            .$(mwd.querySelector('.editor_wrapper_tabled'))
            .css({
                left: mw.$(mwd.querySelector('.toolbar-sections-tabs')).outerWidth(true) + mw.$(mwd.querySelector('.wysiwyg-undo-redo')).outerWidth(true) + 30,
                right: mw.$(mwd.querySelector('#mw-toolbar-right')).outerWidth(true)
            });*/
    },
    prepare: function () {
        mw.$("#liveedit_wysiwyg")
            .on('mousedown touchstart',function() {
                if (mw.$(".mw_editor_btn_hover").length === 0) {
                    mw.mouseDownOnEditor = true;
                    mw.$(this).addClass("hover");
                }
            })
            .on('mouseup touchend',function() {
                mw.mouseDownOnEditor = false;
                mw.$(this).removeClass("hover");
            });
        mw.$(window).scroll(function() {
            if ($(window).scrollTop() > 10) {
                mw.tools.addClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
            } else {
                mw.tools.removeClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
            }

        });
        mw.$("#live_edit_toolbar").hover(function() {
            mw.$(mwd.body).addClass("toolbar-hover");
        }, function() {
            mw.$(mwd.body).removeClass("toolbar-hover");
        });
    },
    editor: {
        init: function () {
            this.ed = mwd.getElementById('liveedit_wysiwyg');
            this.nextBTNS = mw.$(".liveedit_wysiwyg_next");
            this.prevBTNS = mw.$(".liveedit_wysiwyg_prev") ;
        },
        calc: {
            SliderButtonsNeeded: function (parent) {
                var t = {left: false, right: false};
                if (parent == null || !parent) {
                    return;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return t;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b > a) {
                    t.right = true;
                }
                if ($(el).offset().left < mw.$(parent).offset().left) {
                    t.left = true;
                }
                return t;
            },
            SliderNormalize: function (parent) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b < a) {
                    return (a - b);
                }
                return false;
            },
            SliderNext: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return 0;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (a < b) {
                    if ((b - step) > a) {
                        return (curr - step);
                    }
                    else {
                        return curr - (b - a);
                    }
                }
                else {
                    return curr - (b - a);
                }
            },
            SliderPrev: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (curr < 0) {
                    if ((curr + step) < 0) {
                        return (curr + step);
                    }
                    else {
                        return 0;
                    }
                }
                else {
                    return 0;
                }
            }
        },

        step: function () {
            return $(mw.liveedit.toolbar.editor.ed).width();
        },
        denied: false,
        buttons: function () {
            var b = mw.liveedit.toolbar.editor.calc.SliderButtonsNeeded(mw.liveedit.toolbar.editor.ed);
            if (!b) {
                return;
            }
            if (b.left) {
                mw.liveedit.toolbar.editor.prevBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.prevBTNS.removeClass('active');
            }
            if (b.right) {
                mw.liveedit.toolbar.editor.nextBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.nextBTNS.removeClass('active');
            }
        },
        slideLeft: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;
                var to = mw.liveedit.toolbar.editor.calc.SliderPrev(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        slideRight: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;

                var to = mw.liveedit.toolbar.editor.calc.SliderNext(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        fixConvertible: function (who) {
            who = who || ".wysiwyg-convertible";
            who = $(who);
            if (who.length > 1) {
                $(who).each(function () {
                    mw.liveedit.toolbar.editor.fixConvertible(this);
                });
                return false;
            }
            else {
                var w = $(window).width();
                var w1 = who.offset().left + who.width();
                if (w1 > w) {
                    who.css("left", -(w1 - w));
                }
                else {
                    who.css("left", 0);
                }
            }
        }
    }

};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/widgets.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.liveedit = mw.liveedit || {};
mw.liveedit.widgets = {
    htmlEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';
        // window.open(src, "Code editor", "toolbar=no, menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=yes");
        mw.dialogIframe({
            url: src,
            title: 'Code editor',
            height: 'auto',
            width: '95%'
        });
    },
    cssEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
        return mw.dialogIframe({
            url: src,
            // width: 500,
            height:'auto',
            autoHeight: true,
            name: 'mw-css-editor-front',
            title: 'CSS Editor',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    }
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/wysiwyg.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
        var sheet = mwd.querySelector('link[classApplier]');
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
    globalTarget: mwd.body,
    allStatements: function (c, f) {
        var sel = window.getSelection(),
            range = sel.getRangeAt(0),
            common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        //var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
        var nodrop_state = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(common, ['allow-drop', 'nodrop']);

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
                var editables = mwd.querySelectorAll('[contenteditable]'), l = editables.length, x = 0;
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
                        mw.wysiwyg.normalizeBase64Images(this);
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
                all = mwd.getElementsByClassName('edit'),
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
        var external = mwd.createElement('div');
        external.className = 'wysiwyg_external';
        mwd.body.appendChild(external);
        return external;
    },
    isSelectionEditable: function (sel) {
        try {
            var node = (sel || window.getSelection()).focusNode;
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
    execCommandFilter: function (a, b, c) {
        var arr = ['justifyCenter', 'justifyFull', 'justifyLeft', 'justifyRight'];
        var align;
        var node = window.getSelection().focusNode;
        var elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        if (mw.wysiwyg.isSafeMode(elementNode) && arr.indexOf(a) !== -1) {
            align = a.split('justify')[1].toLowerCase();
            if (align === 'full') {
                align = 'justify';
            }
            elementNode.style.textAlign = align;
            mw.wysiwyg.change(elementNode);
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
                return false;
            }
        }
        return true
    },
    execCommand: function (a, b, c) {
        document.execCommand('styleWithCss', 'false', false);
        var sel = getSelection();

        var node = sel.focusNode, elementNode;
        if (node) {
            elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        }

        try {  // 0x80004005
            if (document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable()) {
                b = b || false;
                c = c || false;

                var before = mw.$(node).clone()[0];
                if (sel.rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
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
        mw.$('.mw-skip-and-remove', body).remove();
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
            clipboard = e.originalEvent.clipboardData || mww.clipboardData;
        }
        else {
            clipboard = e.clipboardData || mww.clipboardData;
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
                        mw.wysiwyg.normalizeBase64Images();
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
            var sel = mww.getSelection();
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

                    mw.$(this).removeClass("mw_editor_btn_mousedown");
                    mw.wysiwyg.check_selection(event.target);

                }
                if (event.type === 'mousedown' && !$(this).hasClass('disabled')) {
                    mw.$(this).addClass("mw_editor_btn_mousedown");
                }
            });
        mw_editor_btns.hover(function () {
            mw.$(this).addClass("mw_editor_btn_hover");
        }, function () {
            mw.$(this).removeClass("mw_editor_btn_hover");
        });
        if (mw.wysiwyg.ready) return;
        mw.wysiwyg.ready = true;
        mw.$(mwd.body).on('mouseup', function (event) {
            if (event.target.isContentEditable) {
                if(event.target.nodeName){
                    mw.wysiwyg.check_selection(event.target);
                }
            }
        });
        mw.$(mwd.body).on('keydown', function (event) {

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
        mw.on.tripleClick(mwd.body, function (target) {
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

        mw.$(mwd.body).on('keyup', function (e) {
            mw.smallEditorCanceled = true;
            mw.smallEditor.css({
                visibility: "hidden"
            });
            if (e.target.isContentEditable && !mw.tools.isField(e.target)) {
                mw.wysiwyg.change(e.target)


                if (!mwd.body.editor_typing_startTime) {
                    mwd.body.editor_typing_startTime = new Date();
                }


                var started_typing = mw.tools.hasAnyOfClasses(this, ['isTyping']);
                if (!started_typing) {
                    // isTyping class is removed from livedit.js
                    mw.tools.addClass(this, 'isTyping');
                    mwd.body.editor_typing_startTime = new Date();

                    if(mw._initHandles){
                        mw._initHandles.hideAll();
                    }
                } else {
                    // user is typing
                    started_typing_endTime = new Date();
                    var timeDiff = started_typing_endTime - mwd.body.editor_typing_startTime; //in ms
                    timeDiff /= 1000;
                    var seconds = Math.round(timeDiff);
                    mwd.body.editor_typing_seconds = seconds;
                }

                if (mwd.body.editor_typing_seconds) {
                    //how much seconds user is typing
                    if (mwd.body.editor_typing_seconds > 10) {
                        mw.trigger('editUserIsTypingForLong', this)
                        mwd.body.editor_typing_seconds = 0;
                        mwd.body.editor_typing_startTime = 0;
                    }
                }


                mw.$(this._onCloneableControl).hide();
                if (mw.event.is.enter(e)) {/*

                    mw.$(".element-current").removeClass("element-current");
                    var el = mwd.querySelectorAll('.edit .element'), l = el.length, i = 0;
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
            el = mwd.querySelector(el);
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
        var el = mw.wysiwyg.validateCommonAncestorContainer(el);
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
        var sel = mww.getSelection();
        var r = mwd.createRange();
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
        // var el = mw.wysiwyg.applier('div', 'element', {width: "100%"});
        var parent, fnode = getSelection().focusNode;
        /*if(mw.wysiwyg.isSafeMode(mw.wysiwyg.validateCommonAncestorContainer(fnode))) {
            parent = mw.tools.firstParentWithClass(fnode, 'safe-mode');
            console.log(parent)
            if(parent){
                mw.wysiwyg.contentEditable(parent, true);
                $('[contenteditable]', parent).removeAttr('contenteditable')
            }

        }*/
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
            var el = mwd.createElement(tag);
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
            if(currState[0].$id !== 'wysiwyg   '){
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
        if (range.collapsed) {

            mw.wysiwyg.select_all(el);
            document.execCommand('fontName', null, font_name);
            range.collapse()
        }
        else {
            document.execCommand('fontName', null, font_name);
        }

        mw.wysiwyg.change(el)

    },
    nestingFixes: function (root) {  /*
     var root = root || mwd.body;
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

        if (window.getSelection().isCollapsed) {
            return false;
        }
        mw.wysiwyg.allStatements(function () {

            rangy.init();
            var clstemp = 'mw-font-size-' + mw.random();
            var classApplier = rangy.createCssClassApplier("mw-font-size " + clstemp, true);
            classApplier.applyToSelection();

            var all = mwd.querySelectorAll('.' + clstemp),
                l = all.length,
                i = 0;
            for ( ; i < l; i++ ) {
                all[i].style.fontSize = a + 'px';
                mw.tools.removeClass(all[i], clstemp);
                mw.wysiwyg.change(all[i]);
            }

            mw.$('.edit .mw-font-size').removeClass('mw-font-size')

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
            var family_array = font.family.split(',');
            if (family_array.length == 1) {
                var fam = font.family;

            } else {
                //var fam = mw.tools.getFirstEqualFromTwoArrays(family_array, mw.wysiwyg.editorFonts);
                var fam = family_array.shift();
            }

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


        if (!mw.wysiwyg.started_checking) {
            mw.wysiwyg.started_checking = true;

            var selection = window.getSelection();
            //if (selection.rangeCount > 1) {
            //    var started_typing = mw.tools.hasAnyOfClasses(mwd.body, ['isTyping']);
            //    if(!started_typing){
            //        mw.tools.addClass(mwd.body, 'isTyping');
            //    }
            //}
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


                mw.wysiwyg.setActiveButtons(target);
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
            mw.iframecallbacks.insert_link(result, (result.target ? '_blank' : '_self') , result.text);
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
                        if(parent.mw.image.currentResizing) {
                        mw.wysiwyg.bgQuotesFix(parent.mw.image.currentResizing[0])
                        }
                    }
                    if(mw.image.currentResizing) {
                        mw.wysiwyg.change(mw.image.currentResizing[0]);
                    }
                    mw.image.currentResizing.load(function () {
                        mw.imageResize.resizerSet(this);
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
                var url = res.src ? res.src : res;
                if(action === 'editimage') {
                    if(mw.image.currentResizing) {
                        if (mw.image.currentResizing[0].nodeName === 'IMG') {
                            mw.image.currentResizing.attr("src", url);
                            mw.image.currentResizing.css('height', 'auto');
                        }
                        else {
                            mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(url) + ')');
                            if(parent.mw.image.currentResizing) {
                                mw.wysiwyg.bgQuotesFix(parent.mw.image.currentResizing[0])
                            }
                        }
                        if(mw.image.currentResizing) {
                            mw.wysiwyg.change(mw.image.currentResizing[0]);
                        }
                        mw.image.currentResizing.load(function () {
                            mw.imageResize.resizerSet(this);
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
                footer: false
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
        if (typeof html === 'string') {
            var isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
        }
        else {
            var isembed = false;
        }
        if (isembed) {
            var id = 'frame-' + mw.random();
            var frame = html;
            html = '<span id="' + id + '"></span>';
        }
        if (!!window.MSStream) {
            mw.wysiwyg.restore_selection();
            if (mw.wysiwyg.isSelectionEditable()) {
                var range = window.getSelection().getRangeAt(0);
                var el = mwd.createElement('span');
                el.innerHTML = html;
                range.insertNode(el);
                mw.$(el).replaceWith(el.innerHTML);
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
        var ext = url.split('.').pop().toLowerCase();
        var name = url.split('/').pop()
        if(!type) {
            if(['png','gif','jpg','jpeg','tiff','bmp','svg'].indexOf(ext) !== -1) {
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
        mw.wysiwyg.change(mwd.getElementById(id));
        return id;
    },
    save_selection: function () {
        var selection = window.getSelection();
        if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);
        }
        else {
            var range = mwd.createRange();
            range.selectNode(mwd.querySelector('.edit .element'));
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
        if (window.getComputedStyle(mwd.body) == null) {
            return;
        }

        var body_font = window.getComputedStyle(mwd.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
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
        var iframe = mwd.createElement('iframe');
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
                var el = mwd.createElement(all[i].getAttribute('data-type') == 'ul' ? 'ul' : 'ol');
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
        else if (node.style.backgroundImage.indexOf('data:image/') !== -1) {
            var bg = node.style.backgroundImage.replace(/url\(/g, '').replace(/\)/g, '')
            var type = bg.split('/')[1].split(';')[0];
            var obj = {
                file: bg,
                name: mw.random().toString(36) + "." + type
            };
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                var data = $.parseJSON(data);
                node.style.backgroundImage = 'url(\'' + data.src + '\')';

                if (typeof callback === 'function') {
                    callback.call(node);
                }
                mw.wysiwyg.change(node);
                mw.trigger('nodeBackgroundChanged', [node, node.src])
            });
        }
    },
    normalizeBase64Images: function (root) {
        var root = root || mwd.body;
        var all = root.querySelectorAll(".edit img[src*='data:image/'], .edit [style*='data:image/'][style*='background-image']"),
            l = all.length, i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                mw.tools.addClass(all[i], 'element');
                mw.wysiwyg.normalizeBase64Image(all[i]);
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
mw.disable_selection = function (element) {
    var el = element || ".module";
    el = mw.$(el, ".edit").not(".unselectable");
    el.attr("unselectable", "on");
    el.addClass("unselectable");
    el.on("selectstart", function (event) {
        event.preventDefault();
        return false;
    });
};

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
$(mwd).ready(function () {


    mw.wysiwyg.initClassApplier();

    mw.wysiwyg.dropdowns();

    mw.editorIconPicker = mw.iconPicker({
        iconOptions: { reset: true }
    });


    mw.editorIconPicker.on('select', function (data){
        data.render();
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });
    mw.editorIconPicker.on('sizeChange', function (size){
        mw.editorIconPicker.target.style.fontSize = size + 'px';
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');
        mw.wysiwyg.change(mw.editorIconPicker.target)
    })
    mw.editorIconPicker.on('colorChange', function (color){
        mw.editorIconPicker.target.style.color = color;
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });

    mw.editorIconPicker.on('reset', function (color){
        mw.editorIconPicker.target.style.color = '';
        mw.editorIconPicker.target.style.fontSize = '';
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');

        mw.wysiwyg.change(mw.editorIconPicker.target)
    });


    if (!mw.wysiwyg._fontcolorpicker) {
        mw.lib.require('colorpicker');
        mw.wysiwyg._fontcolorpicker = mw.colorPicker({
            element: document.querySelector('#mw_editor_font_color'),
            tip: true,
            showHEX:false,
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
    mw.$(mwd.body).on('mousedown touchstart', function (event) {
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
                .setValue({url: node.href, text: node.innerHTML})
                .promise()
                .then(function (result){
                    node.href = result.url;
                    node.innerHTML = result.text;
                });
            mw.$('.mw-link-tip').remove();
            return false;
        }

        mw.linkTip._tip = mw.tooltip({content: holder, position: 'bottom-center', skin: 'dark', element: node});
        mw.$(mw.linkTip._tip).addClass('mw-link-tip');

    }
}


})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/liveedit/wysiwygmdab.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
};

mw.wysiwyg._manageDeleteAndBackspaceInSafeMode = {
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
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
        $(todelete).remove();
        if(transfer && mw.tools.isEditable(transfer)) {
            setTimeout(function () {
                mw.wysiwyg.cursorToElement(transfer, transferPosition);
            });
        }
        mw.liveEditState.record({
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
                mw.wysiwyg.cursorToElement(prev, 'end');
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
                    mw.liveEditState.actionRecord(function() {
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
};
mw.wysiwyg.manageDeleteAndBackspaceInSafeMode = function (event, sel) {


    var node = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
    var range = sel.getRangeAt(0);
    if(!node.innerText.replace(/\s/gi, '')){
        mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.emptyNode(event, node, sel, range);
        return false;
    }
    mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
    return true;
};
mw.wysiwyg.manageDeleteAndBackspace = function (event, sel) {
    if (!mw.settings.liveEdit && self === top) {
        return;
    }

    if (mw.event.is.delete(event) || mw.event.is.backSpace(event)) {
        if(!sel.rangeCount) return;
        var r = sel.getRangeAt(0);
        var isSafe = mw.wysiwyg.isSafeMode();

        if(isSafe) {
            return mw.wysiwyg.manageDeleteAndBackspaceInSafeMode(event, sel);
        }

        if (!mw.settings.liveEdit) {
            return true;
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

                    if (nextel && !mw.ea.helpers.isBlockLevel(nextel) && ( typeof nextel.className === 'undefined' || !nextel.className.trim())) {
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
                else if ((focus.previousElementSibling === null && rootfocus.previousElementSibling === null) && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])) {
                    return false;
                }
                else {

                }
            }
            if (nextchar == '') {


                //continue check nodes
                if (mw.event.is.delete(event)) {
                    nextNode = mw.wysiwyg.merge.getNext(sel.focusNode);
                }
                if (mw.event.is.backSpace(event)) {
                    nextNode = mw.wysiwyg.merge.getPrev(sel.focusNode);
                }
                if (mw.wysiwyg.merge.alwaysMergeable(nextNode)) {
                    return true;
                }

                var nonbr = mw.wysiwyg.merge.isInNonbreakable(nextNode);
                if (nonbr) {
                    event.preventDefault();
                    return false;
                }

                if (nextNode.nodeValue == '') {

                }
                if (nextNode !== null && mw.wysiwyg.merge.isMergeable(nextNode)) {
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }
                else {
                    event.preventDefault()
                }
                //  }
                if (nextNode === null) {
                    nextNode = sel.focusNode.parentNode.nextSibling;
                    if (!mw.wysiwyg.merge.isMergeable(nextNode)) {
                        event.preventDefault();
                    }
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }

            } else {

            }


    }

    return true;
};

})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvYmVmb3JlbGVhdmUuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2NvbHVtbnMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2RhdGEuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2RyYWcuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2VkaXQuZmllbGRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9lZGl0b3JzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9lbGVtZW50X2FuYWx5emVyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9ldmVudHMuY3VzdG9tLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9ldmVudHMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2hhbmRsZXMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2ltYWdlLXJlc2l6ZS5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvaW5pdGxvYWQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2luaXRyZWFkeS5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvaW5saW5lLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9sYXlvdXRwbHVzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9saXZlX2VkaXQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L2xpdmVlZGl0X2VsZW1lbnRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9saXZlZWRpdF93aWRnZXRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9saXZlZWRpdC5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvbWFuYWdlLmNvbnRlbnQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L21vZHVsZXMudG9vbGJhci5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvcGFkZGluZy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvcGx1cy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvcmVjb21tZW5kLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC9zZWxlY3Rvci5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvbGl2ZWVkaXQvc291cmNlLWVkaXQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2xpdmVlZGl0L3N0eWxlc2hlZXQuZWRpdG9yLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC90ZW1wY3NzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC90b29sYmFyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC93aWRnZXRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC93eXNpd3lnLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9saXZlZWRpdC93eXNpd3lnbWRhYi5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUN2Q0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7O0FBRVQsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTCxDQUFDOzs7Ozs7Ozs7O0FDM0dEO0FBQ0EsWUFBWTtBQUNaO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUzs7O0FBR1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7OztBQzVCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxtQkFBbUIsZ0JBQWdCO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwrQ0FBK0M7QUFDL0M7QUFDQSwwQkFBMEIsY0FBYztBQUN4QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQixPQUFPO0FBQ3hCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSw2QkFBNkI7QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUNBQXlDO0FBQ3pDO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSw4QkFBOEIsT0FBTztBQUNyQztBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixzQkFBc0IsNkJBQTZCOztBQUVuRDtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2Isc0JBQXNCLDhCQUE4QjtBQUNwRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsd0JBQXdCLHlCQUF5QjtBQUNqRCwwQkFBMEIseUJBQXlCO0FBQ25EO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCOztBQUVBLGFBQWE7QUFDYixLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLHNCQUFzQixjQUFjO0FBQ3BDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOzs7QUFHYjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7O0FBRUEsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsY0FBYyxXQUFXO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixPQUFPO0FBQ3pCOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsOERBQThEO0FBQzlEO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLE9BQU87QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7Ozs7QUFJQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLFNBQVM7QUFDL0I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUM7QUFDckM7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEscUJBQXFCOztBQUVyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUM7QUFDckM7QUFDQTtBQUNBOztBQUVBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5Q0FBeUM7QUFDekM7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQ0FBaUM7QUFDakM7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7O0FBRUEscUJBQXFCOzs7QUFHckI7O0FBRUE7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVCxLQUFLOzs7QUFHTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFDQUFxQyxZQUFZO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhO0FBQ2IsU0FBUzs7QUFFVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixPQUFPO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxrQ0FBa0MsWUFBWTtBQUM5QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTOztBQUVULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsS0FBSzs7QUFFTDs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVCxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUEsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQSxpRUFBaUUsRUFBRTtBQUNuRTtBQUNBO0FBQ0EsYUFBYTtBQUNiLGdCQUFnQjtBQUNoQjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSw0Q0FBNEM7QUFDNUMsMENBQTBDO0FBQzFDLDJDQUEyQztBQUMzQywwQ0FBMEM7QUFDMUMsMENBQTBDO0FBQzFDO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0EsdUJBQXVCO0FBQ3ZCO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMEJBQTBCLFNBQVM7QUFDbkM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSwwRkFBMEYsZ0JBQWdCO0FBQzFHO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNkJBQTZCOztBQUU3QjtBQUNBLHFCQUFxQjs7QUFFckIsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7Ozs7Ozs7OztBQ3pxQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrREFBa0QsTUFBTTtBQUN4RDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7Ozs7Ozs7Ozs7QUN0RkE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsbUJBQW1CO0FBQ25CO0FBQ0E7QUFDQSxlQUFlO0FBQ2Y7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCOztBQUUzQix1QkFBdUI7QUFDdkI7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCO0FBQzNCOztBQUVBLG1CQUFtQjtBQUNuQjtBQUNBLFdBQVc7QUFDWDtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QjtBQUN2QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsdUJBQXVCO0FBQ3ZCO0FBQ0E7QUFDQSxXQUFXO0FBQ1gsT0FBTztBQUNQOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7Ozs7Ozs7Ozs7QUMvR0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvQkFBb0I7QUFDcEI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsU0FBUztBQUNUOzs7QUFHQTs7O0FBR0E7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUlBOzs7OztBQUtBOztBQUVBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtCQUErQjs7QUFFL0I7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSx1REFBdUQ7QUFDdkQsbUNBQW1DO0FBQ25DLFdBQVc7QUFDWCxpREFBaUQ7QUFDakQsa0NBQWtDO0FBQ2xDLFdBQVc7O0FBRVg7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsNkRBQTZEO0FBQzdELGtCQUFrQixjQUFjO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsNkRBQTZEO0FBQzdELGtCQUFrQixjQUFjO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLGlCQUFpQjtBQUN2QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOzs7Ozs7Ozs7O0FDOWhCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOzs7QUFHQSxLQUFLOzs7QUFHTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSx1QkFBdUI7QUFDdkI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7O0FBR0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTs7QUFFQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOzs7O0FBSUw7QUFDQTtBQUNBLEtBQUs7O0FBRUw7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7Ozs7Ozs7Ozs7QUM1S0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7O0FBR0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLOztBQUVMOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDs7Ozs7Ozs7OztBQzNIQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTOztBQUVUOzs7QUFHQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCLDhCQUE4QjtBQUN6RCxvREFBb0QsUUFBUTtBQUM1RDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUNBQWlDO0FBQ2pDLDZCQUE2QjtBQUM3QjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBOztBQUVBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwrQkFBK0I7QUFDL0I7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBLFNBQVM7OztBQUdULEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSw2QkFBNkIsUUFBUTtBQUNyQztBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLDZCQUE2QixRQUFRO0FBQ3JDO0FBQ0EsaUJBQWlCOzs7QUFHakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLDZCQUE2QixRQUFRO0FBQ3JDO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsNkJBQTZCLFFBQVE7QUFDckM7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QixxQkFBcUI7QUFDckI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOzs7QUFHYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7O0FBR2I7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCOzs7OztBQUtqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0pBQStKLFNBQVM7O0FBRXhLLHlCQUF5QjtBQUN6Qiw2R0FBNkcsU0FBUztBQUN0SDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7O0FBRWI7O0FBRUE7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7O0FBRWpCLGFBQWE7OztBQUdiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7O0FBS0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7Ozs7QUFLQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUlBLENBQUM7Ozs7Ozs7Ozs7QUNoakNEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwwR0FBMEcsMlZBQTJWO0FBQ3JjO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQixLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsZ0ZBQWdGOzs7QUFHaEY7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxZQUFZO0FBQ1osS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7Ozs7Ozs7OztBQy9HQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUNBQXlDO0FBQ3pDO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOzs7OztBQUtUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVULEtBQUs7OztBQUdMOztBQUVBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsS0FBSzs7O0FBR0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUNoR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7O0FBRUEsS0FBSzs7QUFFTDs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsVUFBVSxPQUFPO0FBQ2pCO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDL0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDO0FBQ0EsNENBQTRDLCtGQUErRjtBQUMzSSw0Q0FBNEMsK0ZBQStGO0FBQzNJLDRDQUE0QztBQUM1Qyw0Q0FBNEM7QUFDNUM7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDO0FBQ0EsNENBQTRDLHlHQUF5RztBQUNySiw0Q0FBNEMsK0dBQStHO0FBQzNKLDRDQUE0QyxnSEFBZ0g7QUFDNUosNENBQTRDLHNIQUFzSDtBQUNsSztBQUNBO0FBQ0E7QUFDQSx3Q0FBd0M7QUFDeEM7QUFDQSw0Q0FBNEMsb0ZBQW9GO0FBQ2hJLDRDQUE0Qyx1RkFBdUY7QUFDbkk7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQSxpREFBaUQ7QUFDakQ7QUFDQTtBQUNBLGdEQUFnRDtBQUNoRDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekIsbUNBQW1DO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDMUhBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0EsdUJBQXVCLHNCQUFzQjtBQUM3QywwQkFBMEIsc0JBQXNCO0FBQ2hEO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1Qiw4QkFBOEI7QUFDckQsMEJBQTBCLGlEQUFpRDtBQUMzRSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0JBQXdCOztBQUV4QjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWEsRUFBRTs7QUFFZjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQixFQUFFO0FBQ25CO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLENBQUM7Ozs7Ozs7Ozs7QUM1SUQ7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7OztBQUdBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBOztBQUVBLEtBQUs7OztBQUdMO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7Ozs7QUFJQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx3REFBd0QsTUFBTTtBQUM5RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7QUNuUEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGVBQWUsb0JBQW9CO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGVBQWUseUJBQXlCO0FBQ3hDO0FBQ0E7QUFDQSxlQUFlLGlCQUFpQjtBQUNoQztBQUNBO0FBQ0Esa0JBQWtCLDBCQUEwQjtBQUM1QztBQUNBO0FBQ0Esa0JBQWtCLGdCQUFnQjtBQUNsQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7O0FBRUE7OztBQUdBLG9CQUFvQixxQkFBcUI7QUFDekM7QUFDQTs7QUFFQTs7QUFFQSxvQkFBb0IsYUFBYTtBQUNqQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFlBQVksZUFBZTtBQUMzQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscURBQXFEO0FBQ3JEO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEtBQUs7QUFDTDtBQUNBLDhDQUE4Qyw4QkFBOEI7QUFDNUUsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7O0FBRUEsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0wsQ0FBQzs7Ozs7Ozs7OztBQ25hRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7O0FDckRBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLGFBQWEsZ0JBQWdCLHdDQUF3QyxtQkFBbUIsOEVBQThFLHlIQUF5SCw0REFBNEQsOERBQThELDBCQUEwQixXQUFXLG1JQUFtSSwwQkFBMEIsMENBQTBDLHlCQUF5Qix3RUFBd0UseUJBQXlCLFdBQVcsZ0JBQWdCLHNHQUFzRyxZQUFZLDRCQUE0QixXQUFXLGtCQUFrQixzR0FBc0csY0FBYzs7QUFFempDOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQSxDQUFDOzs7Ozs7Ozs7Ozs7OztBQ3hERDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUNySEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUyxFQUFFO0FBQ1g7QUFDQTs7Ozs7Ozs7OztBQzVEQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsbUNBQW1DOztBQUVuQztBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7Ozs7QUFJQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGtCQUFrQiwyQkFBMkI7QUFDN0M7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCOztBQUVyQjtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSwyQkFBMkI7QUFDM0I7QUFDQSxnREFBZ0Q7QUFDaEQ7QUFDQSx3QkFBd0I7QUFDeEIsOENBQThDLDBCQUEwQjtBQUN4RTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLENBQUM7Ozs7Ozs7Ozs7QUNuUkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBFQUEwRSw4Q0FBOEM7QUFDeEgsYUFBYTtBQUNiLDBFQUEwRSxxQkFBcUI7QUFDL0Y7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7Ozs7Ozs7Ozs7O0FDNU5BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxjQUFjLE9BQU87QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUNsREE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSw4QkFBOEI7QUFDOUI7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLGNBQWMsNEJBQTRCO0FBQzFDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsNEJBQTRCO0FBQzFDO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyw0QkFBNEI7QUFDMUM7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esc0JBQXNCLHdCQUF3QjtBQUM5QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHNCQUFzQix3QkFBd0I7QUFDOUM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUNoUUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTs7Ozs7Ozs7OztBQy9EQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0JBQStCOztBQUUvQjs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGtCQUFrQixZQUFZO0FBQzlCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBLHFDQUFxQztBQUNyQztBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7Ozs7Ozs7Ozs7QUN4R0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsK0JBQStCOztBQUUvQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLHdFQUF3RTtBQUN4RTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7OztBQ2pDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYSxFQUFFO0FBQ2YsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwrQkFBK0IsU0FBUztBQUN4QztBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsK0JBQStCLFNBQVM7QUFDeEM7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7Ozs7Ozs7OztBQ3hMQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7Ozs7Ozs7OztBQzNCQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQkFBc0IsT0FBTztBQUM3QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkJBQTJCLGtCQUFrQjtBQUM3Qzs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQixpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLE9BQU87QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2QkFBNkI7QUFDN0I7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBO0FBQ0E7QUFDQSxxR0FBcUc7QUFDckc7QUFDQSw2QkFBNkI7QUFDN0I7QUFDQSxxQkFBcUI7QUFDckIsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBLHVDQUF1QyxNQUFNLEtBQUs7QUFDbEQ7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsU0FBUztBQUMzQjtBQUNBO0FBQ0E7QUFDQSw4QkFBOEIsZUFBZTtBQUM3QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxpQkFBaUI7QUFDakI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGdEQUFnRDs7QUFFaEQ7QUFDQTs7QUFFQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0EsZ0NBQWdDO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0Esc0JBQXNCO0FBQ3RCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUM7QUFDckM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0Esa0JBQWtCLDRCQUE0QjtBQUM5QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLDZDQUE2QztBQUM3QyxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLHNCQUFzQjtBQUN4QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQSxLQUFLO0FBQ0w7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2QkFBNkI7QUFDN0I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6Qjs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpSUFBaUk7QUFDakksc0RBQXNELE1BQU0sTUFBTSxNQUFNO0FBQ3hFO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLDZGQUE2RjtBQUM3RjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBLDJDQUEyQzs7QUFFM0M7QUFDQTtBQUNBLDBCQUEwQixPQUFPO0FBQ2pDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSztBQUNMOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsS0FBSztBQUNMO0FBQ0EsMERBQTBELGNBQWM7QUFDeEU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7O0FBRUE7QUFDQTtBQUNBLFNBQVM7OztBQUdULEtBQUs7QUFDTDtBQUNBLHVCQUF1QixJQUFJO0FBQzNCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDs7QUFFQSx1QkFBdUIsSUFBSTtBQUMzQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxLQUFLO0FBQ0wsbUNBQW1DO0FBQ25DO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVyxLQUFLO0FBQ2hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxtQkFBbUIsT0FBTztBQUMxQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7O0FBRXJCLDBCQUEwQixjQUFjO0FBQ3hDO0FBQ0E7QUFDQSwwQkFBMEIsNkJBQTZCO0FBQ3ZELHFDQUFxQyw2QkFBNkI7O0FBRWxFO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMEJBQTBCLE9BQU87QUFDakM7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7OztBQUlULEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNCQUFzQixPQUFPO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsU0FBUztBQUNULEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOzs7QUFHYjs7QUFFQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGNBQWMsT0FBTzs7QUFFckIsZ0pBQWdKO0FBQ2hKOztBQUVBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0Esb0tBQW9LO0FBQ3BLO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBLG9LQUFvSztBQUNwSztBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVCxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQix1QkFBdUI7QUFDbEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxjQUFjLGdCQUFnQjtBQUM5QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsU0FBUzs7QUFFVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG9DQUFvQztBQUNwQztBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxxSEFBcUg7QUFDckg7QUFDQSw0REFBNEQ7QUFDNUQsNkNBQTZDLElBQUk7QUFDakQsdURBQXVEO0FBQ3ZEO0FBQ0Esb0RBQW9EO0FBQ3BEO0FBQ0EsaURBQWlELEdBQUc7QUFDcEQsd0RBQXdELEdBQUc7QUFDM0QsbURBQW1ELEdBQUc7QUFDdEQsNkNBQTZDLElBQUk7QUFDakQ7QUFDQTtBQUNBO0FBQ0EsK0NBQStDLElBQUk7QUFDbkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvREFBb0QsdUJBQXVCO0FBQzNFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpREFBaUQsaUJBQWlCO0FBQ2xFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxjQUFjLE9BQU87QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBLHNEQUFzRDtBQUN0RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsZ0RBQWdEO0FBQ2hEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixPQUFPO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMOztBQUVBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSwrQ0FBK0M7QUFDL0M7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDRDQUE0QztBQUM1QztBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7Ozs7QUFJQTtBQUNBO0FBQ0EsK0RBQStELGNBQWM7QUFDN0U7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7OztBQUdBOztBQUVBOztBQUVBO0FBQ0Esc0JBQXNCO0FBQ3RCLEtBQUs7OztBQUdMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsS0FBSzs7O0FBR0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEtBQUs7OztBQUdMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDs7O0FBR0EsQ0FBQztBQUNEOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2REFBNkQ7QUFDN0Q7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLFVBQVUsT0FBTztBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsMkJBQTJCLHFDQUFxQztBQUNoRTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7O0FBRUEsc0NBQXNDLHdFQUF3RTtBQUM5Rzs7QUFFQTtBQUNBOzs7Ozs7Ozs7Ozs7QUN6MkZBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLHVFQUF1RTtBQUN2RTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhOztBQUViOzs7QUFHQTs7QUFFQTtBQUNBIiwiZmlsZSI6ImxpdmVlZGl0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiXG5cbm13LmxpdmVlZGl0LmJlZm9yZWxlYXZlID0gZnVuY3Rpb24odXJsKSB7XG4gICAgdmFyIGJlZm9yZWxlYXZlX2h0bWwgPSBcIlwiICtcbiAgICAgICAgXCI8ZGl2IGNsYXNzPSdtdy1iZWZvcmUtbGVhdmUtY29udGFpbmVyJz5cIiArXG4gICAgICAgIFwiPHA+TGVhdmUgcGFnZSBieSBjaG9vc2luZyBhbiBvcHRpb248L3A+XCIgK1xuICAgICAgICBcIjxzcGFuIGNsYXNzPSdtdy11aS1idG4gbXctdWktYnRuLWltcG9ydGFudCc+XCIgKyBtdy5tc2cuYmVmb3JlX2xlYXZlICsgXCI8L3NwYW4+XCIgK1xuICAgICAgICBcIjxzcGFuIGNsYXNzPSdtdy11aS1idG4gbXctdWktYnRuLW5vdGlmaWNhdGlvbicgPlwiICsgbXcubXNnLnNhdmVfYW5kX2NvbnRpbnVlICsgXCI8L3NwYW4+XCIgK1xuICAgICAgICBcIjxzcGFuIGNsYXNzPSdtdy11aS1idG4nIG9uY2xpY2s9J213LmRpYWxvZy5yZW1vdmUoXFxcIm1vZGFsX2JlZm9yZWxlYXZlXFxcIiknPlwiICsgbXcubXNnLmNhbmNlbCArIFwiPC9zcGFuPlwiICtcbiAgICAgICAgXCI8L2Rpdj5cIjtcbiAgICBpZiAobXcuYXNrdXNlcnRvc3RheSAmJiBtdy4kKFwiLmVkaXQub3JpZ19jaGFuZ2VkXCIpLmxlbmd0aCA+IDApIHtcbiAgICAgICAgaWYgKG13ZC5nZXRFbGVtZW50QnlJZCgnbW9kYWxfYmVmb3JlbGVhdmUnKSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nKHtcbiAgICAgICAgICAgICAgICBodG1sOiBiZWZvcmVsZWF2ZV9odG1sLFxuICAgICAgICAgICAgICAgIG5hbWU6ICdtb2RhbF9iZWZvcmVsZWF2ZScsXG4gICAgICAgICAgICAgICAgd2lkdGg6IDQ3MCxcbiAgICAgICAgICAgICAgICBoZWlnaHQ6IDIzMCxcbiAgICAgICAgICAgICAgICB0ZW1wbGF0ZTogJ213X21vZGFsX2Jhc2ljJ1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHZhciBzYXZlID0gbW9kYWwuY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoJy5tdy11aS1idG4tbm90aWZpY2F0aW9uJyk7XG4gICAgICAgICAgICB2YXIgZ28gPSBtb2RhbC5jb250YWluZXIucXVlcnlTZWxlY3RvcignLm13LXVpLWJ0bi1pbXBvcnRhbnQnKTtcblxuICAgICAgICAgICAgbXcuJChzYXZlKS5jbGljayhmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5hZGRDbGFzcyhcImxvYWRpbmdcIik7XG4gICAgICAgICAgICAgICAgbXcuZGlhbG9nLnJlbW92ZShtb2RhbCk7XG4gICAgICAgICAgICAgICAgbXcuZHJhZy5zYXZlKHVuZGVmaW5lZCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmFza3VzZXJ0b3N0YXkgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSB1cmw7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQoZ28pLmNsaWNrKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIG13LmFza3VzZXJ0b3N0YXkgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IHVybDtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbn1cbiIsIm13LmRyYWcgPSBtdy5kcmFnIHx8IHt9O1xyXG5tdy5kcmFnLmNvbHVtbnMgPSB7XHJcbiAgICBzdGVwOiAwLjgsXHJcbiAgICByZXNpemluZzogZmFsc2UsXHJcbiAgICBwcmVwYXJlOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIgPSBtd2QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgbXcud3lzaXd5Zy5jb250ZW50RWRpdGFibGUobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIsIGZhbHNlKTtcclxuICAgICAgICBtdy5kcmFnLmNvbHVtbnMucmVzaXplci5jbGFzc05hbWUgPSAndW5zZWxlY3RhYmxlIG13LWNvbHVtbnMtcmVzaXplcic7XHJcbiAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIucG9zID0gMDtcclxuICAgICAgICBtdy4kKG13LmRyYWcuY29sdW1ucy5yZXNpemVyKS5vbignbW91c2Vkb3duJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBtdy5kcmFnLmNvbHVtbnMucmVzaXppbmcgPSB0cnVlO1xyXG4gICAgICAgICAgICBtdy5kcmFnLmNvbHVtbnMucmVzaXplci5wb3MgPSAwO1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIG13ZC5ib2R5LmFwcGVuZENoaWxkKG13LmRyYWcuY29sdW1ucy5yZXNpemVyKTtcclxuXHJcbiAgICAgICAgbXcuJChtdy5kcmFnLmNvbHVtbnMucmVzaXplcikuaGlkZSgpO1xyXG4gICAgfSxcclxuICAgIHJlc2l6ZTogZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICBpZiAoIW13LmRyYWcuY29sdW1ucy5yZXNpemVyLmN1cnIpIHJldHVybiBmYWxzZTtcclxuICAgICAgICB2YXIgdyA9IHBhcnNlRmxvYXQobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIuY3Vyci5zdHlsZS53aWR0aCk7XHJcbiAgICAgICAgdmFyIHdpZHRoUGFyZW50UGl4ZWxzO1xyXG4gICAgICAgIGlmIChpc05hTih3KSkge1xyXG4gICAgICAgICAgICB3ID0gbXcuJChtdy5kcmFnLmNvbHVtbnMucmVzaXplci5jdXJyKS5vdXRlcldpZHRoKCk7XHJcbiAgICAgICAgICAgIHdpZHRoUGFyZW50UGl4ZWxzID0gbXcuJChtdy5kcmFnLmNvbHVtbnMucmVzaXplci5jdXJyKS5wYXJlbnQoKS5vdXRlcldpZHRoKCk7XHJcbiAgICAgICAgICAgIHcgPSAodyAvIHdpZHRoUGFyZW50UGl4ZWxzKSAqIDEwMDtcclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIG5leHQgPSBtdy5kcmFnLmNvbHVtbnMubmV4dENvbHVtbihtdy5kcmFnLmNvbHVtbnMucmVzaXplci5jdXJyKTtcclxuICAgICAgICBpZighbmV4dCl7XHJcbiAgICAgICAgICAgIG13LiQobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIpLmhpZGUoKTtcclxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgdmFyIHcyID0gcGFyc2VGbG9hdChuZXh0LnN0eWxlLndpZHRoKTtcclxuICAgICAgICBpZiAoaXNOYU4odzIpKSB7XHJcbiAgICAgICAgICAgIHcyID0gbXcuJChuZXh0KS5vdXRlcldpZHRoKCk7XHJcbiAgICAgICAgICAgIHdpZHRoUGFyZW50UGl4ZWxzID0gbXcuJChuZXh0KS5wYXJlbnQoKS5vdXRlcldpZHRoKCk7XHJcbiAgICAgICAgICAgIHcyID0gKHcyIC8gd2lkdGhQYXJlbnRQaXhlbHMpICogMTAwO1xyXG4gICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChtdy5kcmFnLmNvbHVtbnMucmVzaXplci5wb3MgPCBlLnBhZ2VYKSB7XHJcbiAgICAgICAgICAgIGlmICh3MiA8IDEwICYmICFtdy50b29scy5pc1J0bCgpKSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIG13LmRyYWcuY29sdW1ucy5yZXNpemVyLmN1cnIuc3R5bGUud2lkdGggPSBtdy50b29scy5pc1J0bCgpPyh3IC0gbXcuZHJhZy5jb2x1bW5zLnN0ZXApOih3ICsgbXcuZHJhZy5jb2x1bW5zLnN0ZXApICsgJyUnO1xyXG4gICAgICAgICAgICB2YXIgY2FsYyA9IG13LnRvb2xzLmlzUnRsKCkgPyAodzIgKyBtdy5kcmFnLmNvbHVtbnMuc3RlcCkgOiAodzIgLSBtdy5kcmFnLmNvbHVtbnMuc3RlcCk7XHJcbiAgICAgICAgICAgIG5leHQuc3R5bGUud2lkdGggPSAgY2FsYyArICclJztcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIGlmICh3IDwgMTAgJiYgIW13LnRvb2xzLmlzUnRsKCkpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIuY3Vyci5zdHlsZS53aWR0aCA9IG13LnRvb2xzLmlzUnRsKCk/KHcgKyBtdy5kcmFnLmNvbHVtbnMuc3RlcCk6KHcgLSBtdy5kcmFnLmNvbHVtbnMuc3RlcCkgKyAnJSc7XHJcbiAgICAgICAgICAgIHZhciBjYWxjID0gbXcudG9vbHMuaXNSdGwoKSA/ICh3MiAtIG13LmRyYWcuY29sdW1ucy5zdGVwKSA6ICh3MiArIG13LmRyYWcuY29sdW1ucy5zdGVwKTtcclxuICAgICAgICAgICAgbmV4dC5zdHlsZS53aWR0aCA9IGNhbGMgKyAnJSc7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIG13LmRyYWcuY29sdW1ucy5yZXNpemVyLnBvcyA9IGUucGFnZVg7XHJcbiAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnBvc2l0aW9uKG13LmRyYWcuY29sdW1ucy5yZXNpemVyLmN1cnIpO1xyXG4gICAgICAgIG13LnRyaWdnZXIoJ2NvbHVtblJlc2l6ZScsIG13LmRyYWcuY29sdW1ucy5yZXNpemVyLmN1cnIpO1xyXG4gICAgfSxcclxuICAgIHBvc2l0aW9uOiBmdW5jdGlvbiAoZWwpIHtcclxuICAgICAgICBpZiAoISFtdy5kcmFnLmNvbHVtbnMubmV4dENvbHVtbihlbCkpIHtcclxuICAgICAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIuY3VyciA9IGVsO1xyXG4gICAgICAgICAgICB2YXIgb2ZmID0gbXcuJChlbCkub2Zmc2V0KCk7XHJcbiAgICAgICAgICAgIG13LiQobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIpLmNzcyh7XHJcbiAgICAgICAgICAgICAgICB0b3A6IG9mZi50b3AsXHJcbiAgICAgICAgICAgICAgICBsZWZ0OiBtdy50b29scy5pc1J0bCgpID8gb2ZmLmxlZnQgLSAxMCA6IG9mZi5sZWZ0ICsgZWwub2Zmc2V0V2lkdGggLSAxMCxcclxuICAgICAgICAgICAgICAgIGhlaWdodDogZWwub2Zmc2V0SGVpZ2h0XHJcbiAgICAgICAgICAgIH0pLnNob3coKTtcclxuICAgICAgICB9XHJcbiAgICB9LFxyXG4gICAgaW5pdDogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIG13LmRyYWcuY29sdW1ucy5wcmVwYXJlKCk7XHJcbiAgICAgICAgbXcub24oXCJDb2x1bW5PdmVyXCIsIGZ1bmN0aW9uIChlLCBjb2wpIHtcclxuICAgICAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIucG9zID0gMDtcclxuICAgICAgICAgICAgbXcuZHJhZy5jb2x1bW5zLnBvc2l0aW9uKGNvbCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbXcub24oXCJDb2x1bW5PdXRcIiwgZnVuY3Rpb24gKGUsIGNvbCkge1xyXG4gICAgICAgICAgICBtdy4kKG13LmRyYWcuY29sdW1ucy5yZXNpemVyKS5oaWRlKCk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSxcclxuICAgIG5leHRDb2x1bW46IGZ1bmN0aW9uIChjb2wpIHtcclxuICAgICAgICB2YXIgbmV4dCA9IGNvbC5uZXh0RWxlbWVudFNpYmxpbmc7XHJcbiAgICAgICAgaWYgKG5leHQgPT09IG51bGwpIHtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MobmV4dCwgJ213LWNvbCcpKSB7XHJcbiAgICAgICAgICAgIHJldHVybiBuZXh0O1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgcmV0dXJuIG13LmRyYWcuY29sdW1ucy5uZXh0Q29sdW1uKG5leHQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxufVxyXG4kKG13ZCkucmVhZHkoZnVuY3Rpb24gKCkge1xyXG4gICAgbXcuJChtd2QuYm9keSkub24oJ21vdXNldXAgdG91Y2hlbmQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYgKG13LmRyYWcucGx1cy5sb2NrZWQpIHtcclxuICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIuY3Vycik7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIG13LmRyYWcuY29sdW1ucy5yZXNpemluZyA9IGZhbHNlO1xyXG4gICAgICAgIG13LmRyYWcucGx1cy5sb2NrZWQgPSBmYWxzZTtcclxuICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtd2QuYm9keSwgJ213LWNvbHVtbi1yZXNpemluZycpO1xyXG4gICAgfSk7XHJcbiAgICBtdy4kKG13ZC5ib2R5KS5vbignbW91c2Vtb3ZlIHRvdWNobW92ZScsIGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgaWYgKG13LmRyYWcuY29sdW1ucy5yZXNpemluZyA9PT0gdHJ1ZSAmJiBtdy5pc0RyYWcgPT09IGZhbHNlKSB7XHJcbiAgICAgICAgICAgIG13LmRyYWcuY29sdW1ucy5yZXNpemUoZSk7XHJcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgbXcuZHJhZy5wbHVzLmxvY2tlZCA9IHRydWU7XHJcbiAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKG13ZC5ib2R5LCAnbXctY29sdW1uLXJlc2l6aW5nJyk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0pO1xyXG4iLCJtdy5saXZlZWRpdC5kYXRhID0ge1xyXG4gICAgX2RhdGE6e30sXHJcbiAgICBfdGFyZ2V0OiBudWxsLFxyXG4gICAgaW5pdDogZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcclxuICAgICAgICBtdy4kKGRvY3VtZW50LmJvZHkpXHJcbiAgICAgICAgLm9uKCd0b3VjaG1vdmUgbW91c2Vtb3ZlJywgZnVuY3Rpb24oZSl7XHJcbiAgICAgICAgICAgIHZhciBoYXNMYXlvdXQgPSAhIW13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KGUudGFyZ2V0LCBbJ1tkYXRhLW1vZHVsZS1uYW1lPVwibGF5b3V0c1wiXScsICdbZGF0YS10eXBlPVwibGF5b3V0c1wiXSddKTtcclxuICAgICAgICAgICAgbXcubGl2ZWVkaXQuZGF0YS5zZXQoJ21vdmUnLCAnaGFzTGF5b3V0JywgaGFzTGF5b3V0KTtcclxuICAgICAgICAgICAgbXcubGl2ZWVkaXQuZGF0YS5zZXQoJ21vdmUnLCAnaGFzTW9kdWxlT3JFbGVtZW50JywgbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZS50YXJnZXQsIFsnbW9kdWxlJywgJ2VsZW1lbnQnXSkpO1xyXG4gICAgICAgICAgICBpZihzY29wZS5fdGFyZ2V0ICE9PSBlLnRhcmdldCkge1xyXG4gICAgICAgICAgICAgICAgc2NvcGUuX3RhcmdldCA9IGUudGFyZ2V0O1xyXG4gICAgICAgICAgICAgICAgbXcudHJpZ2dlcignTGl2ZWVkaXQnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgLm9uKCdtb3VzZXVwIHRvdWNoZW5kJywgZnVuY3Rpb24oZSl7XHJcbiAgICAgICAgICAgIG13LmxpdmVlZGl0LmRhdGEuc2V0KCdtb3VzZXVwJywgJ2lzSWNvbicsIG13Lnd5c2l3eWcuZmlyc3RFbGVtZW50VGhhdEhhc0ZvbnRJY29uQ2xhc3MoZS50YXJnZXQpKTtcclxuICAgICAgICB9KTtcclxuXHJcblxyXG4gICAgfSxcclxuICAgIHNldDogZnVuY3Rpb24oYWN0aW9uLCBpdGVtLCB2YWx1ZSl7XHJcbiAgICAgICAgdGhpcy5fZGF0YVthY3Rpb25dID0gdGhpcy5fZGF0YVthY3Rpb25dIHx8IHt9O1xyXG4gICAgICAgIHRoaXMuX2RhdGFbYWN0aW9uXVtpdGVtXSA9IHZhbHVlO1xyXG4gICAgfSxcclxuICAgIGdldDogZnVuY3Rpb24oYWN0aW9uLCBpdGVtKXtcclxuICAgICAgICByZXR1cm4gdGhpcy5fZGF0YVthY3Rpb25dID8gdGhpcy5fZGF0YVthY3Rpb25dW2l0ZW1dIDogdW5kZWZpbmVkO1xyXG4gICAgfVxyXG59O1xyXG4iLCJtdy5kcmFnID0ge1xuICAgIF9maXhEZW5pZWRQYXJhZ3JhcGhIaWVyYXJjaHlTZWxlY3RvcjogJydcbiAgICArICcuZWRpdCBwIGgxLC5lZGl0IHAgaDIsLmVkaXQgcCBoMywnXG4gICAgKyAnLmVkaXQgcCBoNCwuZWRpdCBwIGg1LC5lZGl0IHAgaDYsJ1xuICAgICsgJy5lZGl0IHAgcCwuZWRpdCBwIHVsLC5lZGl0IHAgb2wsJ1xuICAgICsgJy5lZGl0IHAgaGVhZGVyLC5lZGl0IHAgZm9ybSwuZWRpdCBwIGFydGljbGUsJ1xuICAgICsgJy5lZGl0IHAgYXNpZGUsLmVkaXQgcCBibG9ja3F1b3RlLC5lZGl0IHAgZm9vdGVyLC5lZGl0IHAgZGl2JyxcbiAgICBmaXhEZW5pZWRQYXJhZ3JhcGhIaWVyYXJjaHk6IGZ1bmN0aW9uIChyb290KSB7XG4gICAgICAgIHJvb3QgPSByb290IHx8IG13ZC5ib2R5O1xuICAgICAgICB2YXIgYWxsID0gcm9vdC5xdWVyeVNlbGVjdG9yQWxsKG13LmRyYWcuX2ZpeERlbmllZFBhcmFncmFwaEhpZXJhcmNoeVNlbGVjdG9yKTtcbiAgICAgICAgaWYgKGFsbC5sZW5ndGgpIHtcbiAgICAgICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgICAgIGZvciAoIDsgaSA8IGFsbC5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgICAgICB2YXIgdGhlX3BhcmVudCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhhbGxbaV0sICdwJyk7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuc2V0VGFnKHRoZV9wYXJlbnQsICdkaXYnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgY3JlYXRlX2NvbHVtbnM6IGZ1bmN0aW9uKHNlbGVjdG9yLCAkbnVtY29scykge1xuICAgICAgICBpZiAoISQoc2VsZWN0b3IpLmhhc0NsYXNzKFwiYWN0aXZlXCIpKSB7XG5cbiAgICAgICAgICAgIG13LiQobXcuZHJhZy5jb2x1bW5zLnJlc2l6ZXIpLmhpZGUoKTtcblxuICAgICAgICAgICAgdmFyIGlkID0gbXcuX2FjdGl2ZVJvd092ZXIuaWQ7XG5cbiAgICAgICAgICAgIG13LiQoc2VsZWN0b3IpLmFkZENsYXNzKFwiYWN0aXZlXCIpO1xuICAgICAgICAgICAgdmFyICRlbF9pZCA9IGlkICE9PSAnJyA/IGlkIDogbXcuc2V0dGluZ3MubXcgLSByb3dfaWQ7XG5cbiAgICAgICAgICAgIG13LnNldHRpbmdzLnNvcnRhYmxlc19jcmVhdGVkID0gZmFsc2U7XG4gICAgICAgICAgICB2YXIgJGV4aXNpbnRnX251bSA9IG13LiQoJyMnICsgJGVsX2lkKS5jaGlsZHJlbihcIi5tdy1jb2xcIikubGVuZ3RoO1xuXG4gICAgICAgICAgICBpZiAoJG51bWNvbHMgPT09IDApIHtcbiAgICAgICAgICAgICAgICAkbnVtY29scyA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAkbnVtY29scyA9IHBhcnNlSW50KCRudW1jb2xzKTtcbiAgICAgICAgICAgIGlmICgkZXhpc2ludGdfbnVtID09PSAwKSB7XG4gICAgICAgICAgICAgICAgJGV4aXNpbnRnX251bSA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoJG51bWNvbHMgIT09ICRleGlzaW50Z19udW0pIHtcbiAgICAgICAgICAgICAgICBpZiAoJG51bWNvbHMgPiAkZXhpc2ludGdfbnVtKSB7IC8vbW9yZSBjb2x1bW5zXG4gICAgICAgICAgICAgICAgICAgIHZhciBpID0gJGV4aXNpbnRnX251bTtcbiAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCAkbnVtY29sczsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV3X2NvbCA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld19jb2wuY2xhc3NOYW1lID0gJ213LWNvbCc7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdfY29sLmlubmVySFRNTCA9ICc8ZGl2IGNsYXNzPVwibXctY29sLWNvbnRhaW5lclwiPjxkaXYgY2xhc3M9XCJtdy1lbXB0eSBlbGVtZW50XCIgaWQ9XCJlbGVtZW50XycrbXcucmFuZG9tKCkrJ1wiPjwvZGl2PjwvZGl2Pic7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKCcjJyArICRlbF9pZCkuYXBwZW5kKG5ld19jb2wpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5maXhfcGxhY2Vob2xkZXJzKHRydWUsICcjJyArICRlbF9pZCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgLy9tdy5yZXNpemFibGVfY29sdW1ucygpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7IC8vbGVzcyBjb2x1bW5zXG4gICAgICAgICAgICAgICAgICAgIHZhciAkY29sc190b19yZW1vdmUgPSAkZXhpc2ludGdfbnVtIC0gJG51bWNvbHM7XG4gICAgICAgICAgICAgICAgICAgIGlmICgkY29sc190b19yZW1vdmUgPiAwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZnJhZ21lbnQgPSBkb2N1bWVudC5jcmVhdGVEb2N1bWVudEZyYWdtZW50KCksXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGFzdF9hZnRlcl9yZW1vdmU7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoJyMnICsgJGVsX2lkKS5jaGlsZHJlbihcIi5tdy1jb2xcIikuZWFjaChmdW5jdGlvbihpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGkgPT09ICgkbnVtY29scyAtIDEpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhc3RfYWZ0ZXJfcmVtb3ZlID0gbXcuJCh0aGlzKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChpID4gKCRudW1jb2xzIC0gMSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLnF1ZXJ5U2VsZWN0b3IoJy5tdy1jb2wtY29udGFpbmVyJykgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5mb3JlYWNoQ2hpbGRyZW4odGhpcywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyh0aGlzLmNsYXNzTmFtZSwgJ213LWNvbC1jb250YWluZXInKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZnJhZ21lbnQuYXBwZW5kQ2hpbGQodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBsYXN0X2NvbnRhaW5lciA9IGxhc3RfYWZ0ZXJfcmVtb3ZlLmZpbmQoXCIubXctY29sLWNvbnRhaW5lclwiKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5vZGVzID0gZnJhZ21lbnQuY2hpbGROb2RlcyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpID0gMCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsID0gbm9kZXMubGVuZ3RoO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBub2RlID0gbm9kZXNbaV07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCgnLmVtcHR5LWVsZW1lbnQsIC51aS1yZXNpemFibGUtaGFuZGxlJywgbm9kZSkucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGFzdF9jb250YWluZXIuYXBwZW5kKG5vZGUuaW5uZXJIVE1MKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgLy9sYXN0X2FmdGVyX3JlbW92ZS5yZXNpemFibGUoXCJkZXN0cm95XCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCgnIycgKyAkZWxfaWQpLmNoaWxkcmVuKFwiLmVtcHR5LWVsZW1lbnRcIikucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnModHJ1ZSwgJyMnICsgJGVsX2lkKTtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgJGV4aXNpbnRnX251bSA9IG13LiQoJyMnICsgJGVsX2lkKS5jaGlsZHJlbihcIi5tdy1jb2xcIikuc2l6ZSgpO1xuICAgICAgICAgICAgICAgIHZhciAkZXFfdyA9IDEwMCAvICRleGlzaW50Z19udW07XG4gICAgICAgICAgICAgICAgdmFyICRlcV93MSA9ICRlcV93O1xuICAgICAgICAgICAgICAgIG13LiQoJyMnICsgJGVsX2lkKS5jaGlsZHJlbihcIi5tdy1jb2xcIikud2lkdGgoJGVxX3cxICsgJyUnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBtdy53eXNpd3lnLm5jZXVpKCk7XG4gICAgfSxcbiAgICByZXBsYWNlOmZ1bmN0aW9uKGVsLCBkaXIsIGNhbGxiYWNrKXtcbiAgICAgICAgdmFyIHByZXYgPSBlbFtkaXJdKCk7XG4gICAgICAgIHZhciB0aGlzT2ZmID0gZWwub2Zmc2V0KCk7XG4gICAgICAgIHZhciBwcmV2T2ZmID0gcHJldi5vZmZzZXQoKTtcbiAgICAgICAgcHJldlxuICAgICAgICAgICAgLmNzcyh7XG4gICAgICAgICAgICAgICAgcG9zaXRpb246J3JlbGF0aXZlJ1xuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5hbmltYXRlKHt0b3A6dGhpc09mZi50b3AtcHJldk9mZi50b3AgfSk7XG5cbiAgICAgICAgZWxcbiAgICAgICAgICAgIC5jc3Moe1xuICAgICAgICAgICAgICAgIHBvc2l0aW9uOidyZWxhdGl2ZSdcbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAuYW5pbWF0ZSh7dG9wOnByZXZPZmYudG9wIC0gdGhpc09mZi50b3B9LCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGlmKGRpciA9PT0gJ3ByZXYnKXtcbiAgICAgICAgICAgICAgICAgICAgcHJldi5iZWZvcmUoZWwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgICAgICBwcmV2LmFmdGVyKGVsKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBlbC5jc3Moeyd0b3AnOiAnJywgJ3Bvc2l0aW9uJzonJ30pXG4gICAgICAgICAgICAgICAgcHJldi5jc3Moeyd0b3AnOiAnJywgJ3Bvc2l0aW9uJzonJ30pO1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKGVsWzBdKVxuICAgICAgICAgICAgICAgIGlmKCEhY2FsbGJhY2spe1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKClcbiAgICAgICAgICAgICAgICAgICAgfSwgMTApXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9KVxuICAgIH0sXG4gICAgZXh0ZXJuYWxfZ3JpZHNfY29sX2NsYXNzZXM6IFsncm93JywgJ2NvbC1sZy0xJywgJ2NvbC1sZy0xMCcsICdjb2wtbGctMTEnLCAnY29sLWxnLTEyJywgJ2NvbC1sZy0yJywgJ2NvbC1sZy0zJywgJ2NvbC1sZy00JywgJ2NvbC1sZy01JywgJ2NvbC1sZy02JywgJ2NvbC1sZy03JywgJ2NvbC1sZy04JywgJ2NvbC1sZy05JywgJ2NvbC1tZC0xJywgJ2NvbC1tZC0xMCcsICdjb2wtbWQtMTEnLCAnY29sLW1kLTEyJywgJ2NvbC1tZC0yJywgJ2NvbC1tZC0zJywgJ2NvbC1tZC00JywgJ2NvbC1tZC01JywgJ2NvbC1tZC02JywgJ2NvbC1tZC03JywgJ2NvbC1tZC04JywgJ2NvbC1tZC05JywgJ2NvbC1zbS0xJywgJ2NvbC1zbS0xMCcsICdjb2wtc20tMTEnLCAnY29sLXNtLTEyJywgJ2NvbC1zbS0yJywgJ2NvbC1zbS0zJywgJ2NvbC1zbS00JywgJ2NvbC1zbS01JywgJ2NvbC1zbS02JywgJ2NvbC1zbS03JywgJ2NvbC1zbS04JywgJ2NvbC1zbS05JywgJ2NvbC14cy0xJywgJ2NvbC14cy0xMCcsICdjb2wteHMtMTEnLCAnY29sLXhzLTEyJywgJ2NvbC14cy0yJywgJ2NvbC14cy0zJywgJ2NvbC14cy00JywgJ2NvbC14cy01JywgJ2NvbC14cy02JywgJ2NvbC14cy03JywgJ2NvbC14cy04JywgJ2NvbC14cy05J10sXG4gICAgZXh0ZXJuYWxfY3NzX25vX2VsZW1lbnRfY2xhc3NlczogWydjb250YWluZXInLCduYXZiYXInLCAnbmF2YmFyLWhlYWRlcicsICduYXZiYXItY29sbGFwc2UnLCAnbmF2YmFyLXN0YXRpYycsICduYXZiYXItc3RhdGljLXRvcCcsICduYXZiYXItZGVmYXVsdCcsICduYXZiYXItdGV4dCcsICduYXZiYXItcmlnaHQnLCAnbmF2YmFyLWNlbnRlcicsICduYXZiYXItbGVmdCcsICduYXYgbmF2YmFyLW5hdicsICdjb2xsYXBzZScsICdoZWFkZXItY29sbGFwc2UnLCAncGFuZWwtaGVhZGluZycsICdwYW5lbC1ib2R5JywgJ3BhbmVsLWZvb3RlciddLFxuICAgIHNlY3Rpb25fc2VsZWN0b3JzOiBbJy5tb2R1bGUtbGF5b3V0cyddLFxuICAgIGV4dGVybmFsX2Nzc19ub19lbGVtZW50X2NvbnRyb2xsX2NsYXNzZXM6IFsnY29udGFpbmVyJywgJ2NvbnRhaW5lci1mbHVpZCcsICdlZGl0Jywnbm9lbGVtZW50Jywnbm8tZWxlbWVudCcsJ2FsbG93LWRyb3AnLCdub2Ryb3AnLCAnbXctb3Blbi1tb2R1bGUtc2V0dGluZ3MnLCdtb2R1bGUtbGF5b3V0cyddLFxuICAgIG9uQ2xvbmVhYmxlQ29udHJvbDpmdW5jdGlvbih0YXJnZXQsIGlzT3ZlckNvbnRyb2wpe1xuICAgICAgICBpZighdGhpcy5fb25DbG9uZWFibGVDb250cm9sKXtcbiAgICAgICAgICAgIHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbCA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbC5jbGFzc05hbWUgPSAnbXctY2xvbmVhYmxlLWNvbnRyb2wnO1xuICAgICAgICAgICAgdmFyIGh0bWwgPSAnJztcbiAgICAgICAgICAgIGh0bWwgKz0gJzxzcGFuIGNsYXNzPVwibXctY2xvbmVhYmxlLWNvbnRyb2wtaXRlbSBtdy1jbG9uZWFibGUtY29udHJvbC1wcmV2XCIgdGl0bGU9XCJNb3ZlIGJhY2t3YXJkXCI+PC9zcGFuPic7XG4gICAgICAgICAgICBodG1sICs9ICc8c3BhbiBjbGFzcz1cIm13LWNsb25lYWJsZS1jb250cm9sLWl0ZW0gbXctY2xvbmVhYmxlLWNvbnRyb2wtcGx1c1wiIHRpdGxlPVwiQ2xvbmVcIj48L3NwYW4+JztcbiAgICAgICAgICAgIGh0bWwgKz0gJzxzcGFuIGNsYXNzPVwibXctY2xvbmVhYmxlLWNvbnRyb2wtaXRlbSBtdy1jbG9uZWFibGUtY29udHJvbC1taW51c1wiIHRpdGxlPVwiUmVtb3ZlXCI+PC9zcGFuPicgO1xuICAgICAgICAgICAgaHRtbCArPSAnPHNwYW4gY2xhc3M9XCJtdy1jbG9uZWFibGUtY29udHJvbC1pdGVtIG13LWNsb25lYWJsZS1jb250cm9sLW5leHRcIiB0aXRsZT1cIk1vdmUgZm9yd2FyZFwiPjwvc3Bhbj4nO1xuICAgICAgICAgICAgdGhpcy5fb25DbG9uZWFibGVDb250cm9sLmlubmVySFRNTCA9IGh0bWw7XG5cbiAgICAgICAgICAgIG13ZC5ib2R5LmFwcGVuZENoaWxkKHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbCk7XG4gICAgICAgICAgICBtdy4kKCcubXctY2xvbmVhYmxlLWNvbnRyb2wtcGx1cycsIHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbCkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB2YXIgJHQgPSBtdy4kKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldCkucGFyZW50KClcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiAkdFswXS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgcGFyc2VyID0gbXcudG9vbHMucGFyc2VIdG1sKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldC5vdXRlckhUTUwpLmJvZHk7XG4gICAgICAgICAgICAgICAgdmFyIGFsbCA9IHBhcnNlci5xdWVyeVNlbGVjdG9yQWxsKCdbaWRdJyksIGkgPSAwO1xuICAgICAgICAgICAgICAgIGZvciggOyBpPGFsbC5sZW5ndGg7IGkrKyl7XG4gICAgICAgICAgICAgICAgICAgIGFsbFtpXS5pZCA9ICdtdy1jbC1pZC0nICsgbXcucmFuZG9tKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQobXcuZHJhZy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KS5hZnRlcihwYXJzZXIuaW5uZXJIVE1MKTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiAkdFswXS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wuX190YXJnZXQpO1xuICAgICAgICAgICAgICAgIG13LmRyYWcub25DbG9uZWFibGVDb250cm9sKCdoaWRlJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQoJy5tdy1jbG9uZWFibGUtY29udHJvbC1taW51cycsIHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbCkub24oJ2NsaWNrJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB2YXIgJHQgPSBtdy4kKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldCkucGFyZW50KCk7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6ICR0WzBdLFxuICAgICAgICAgICAgICAgICAgICB2YWx1ZTogJHRbMF0uaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgbXcuJChtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wuX190YXJnZXQpLmZhZGVPdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZTogJHRbMF0uaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIG13LmRyYWcub25DbG9uZWFibGVDb250cm9sKCdoaWRlJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQoJy5tdy1jbG9uZWFibGUtY29udHJvbC1uZXh0JywgdGhpcy5fb25DbG9uZWFibGVDb250cm9sKS5vbignY2xpY2snLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciAkdCA9IG13LiQobXcuZHJhZy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KS5wYXJlbnQoKTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiAkdFswXS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy4kKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldCkubmV4dCgpLmFmdGVyKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldClcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiAkdFswXS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wuX190YXJnZXQpO1xuICAgICAgICAgICAgICAgIG13LmRyYWcub25DbG9uZWFibGVDb250cm9sKCdoaWRlJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQoJy5tdy1jbG9uZWFibGUtY29udHJvbC1wcmV2JywgdGhpcy5fb25DbG9uZWFibGVDb250cm9sKS5vbignY2xpY2snLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciAkdCA9IG13LiQobXcuZHJhZy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KS5wYXJlbnQoKTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogJHRbMF0sXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOiAkdFswXS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy4kKG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldCkucHJldigpLmJlZm9yZShtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wuX190YXJnZXQpXG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6ICR0WzBdLFxuICAgICAgICAgICAgICAgICAgICB2YWx1ZTogJHRbMF0uaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuZHJhZy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLm9uQ2xvbmVhYmxlQ29udHJvbCgnaGlkZScpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGNsYyA9IG13LiQodGhpcy5fb25DbG9uZWFibGVDb250cm9sKTtcbiAgICAgICAgaWYodGFyZ2V0ID09ICdoaWRlJyl7XG4gICAgICAgICAgICBjbGMuaGlkZSgpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2V7XG4gICAgICAgICAgICBjbGMuc2hvdygpO1xuICAgICAgICAgICAgdGhpcy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0ID0gdGFyZ2V0O1xuXG4gICAgICAgICAgICB2YXIgbmV4dCA9IG13LiQodGhpcy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KS5uZXh0KCk7XG4gICAgICAgICAgICB2YXIgcHJldiA9IG13LiQodGhpcy5fb25DbG9uZWFibGVDb250cm9sLl9fdGFyZ2V0KS5wcmV2KCk7XG4gICAgICAgICAgICB2YXIgZWwgPSBtdy4kKHRhcmdldCksIG9mZiA9IGVsLm9mZnNldCgpO1xuXG5cbiAgICAgICAgICAgIGlmKG5leHQubGVuZ3RoID09IDApe1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy1jbG9uZWFibGUtY29udHJvbC1uZXh0JywgY2xjKS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy1jbG9uZWFibGUtY29udHJvbC1uZXh0JywgY2xjKS5zaG93KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihwcmV2Lmxlbmd0aCA9PSAwKXtcbiAgICAgICAgICAgICAgICBtdy4kKCcubXctY2xvbmVhYmxlLWNvbnRyb2wtcHJldicsIGNsYykuaGlkZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICBtdy4kKCcubXctY2xvbmVhYmxlLWNvbnRyb2wtcHJldicsIGNsYykuc2hvdygpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGxlZnRDZW50ZXIgPSAob2ZmLmxlZnQgPiAwID8gb2ZmLmxlZnQgOiAwKSArIChlbC53aWR0aCgpLzIgLSBjbGMud2lkdGgoKS8yKSA7XG4gICAgICAgICAgICBjbGMuc2hvdygpO1xuICAgICAgICAgICAgaWYoaXNPdmVyQ29udHJvbCl7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY2xjLmNzcyh7XG4gICAgICAgICAgICAgICAgdG9wOiBvZmYudG9wID4gMCA/IG9mZi50b3AgOiAwICxcbiAgICAgICAgICAgICAgICAvL2xlZnQ6IG9mZi5sZWZ0ID4gMCA/IG9mZi5sZWZ0IDogMFxuICAgICAgICAgICAgICAgIGxlZnQ6IGxlZnRDZW50ZXJcbiAgICAgICAgICAgIH0pO1xuXG5cbiAgICAgICAgICAgIHZhciBjbG9uZXIgPSBtd2QucXVlcnlTZWxlY3RvcignLm13LWNsb25lYWJsZS1jb250cm9sJyk7XG4gICAgICAgICAgICBpZihjbG9uZXIpIHtcbiAgICAgICAgICAgICAgICBtdy5faW5pdEhhbmRsZXMuZ2V0QWxsKCkuZm9yRWFjaChmdW5jdGlvbiAoY3Vycikge1xuICAgICAgICAgICAgICAgICAgICBtYXN0ZXJSZWN0ID0gY3Vyci53cmFwcGVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgY2xvbmVyZWN0ID0gY2xvbmVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5faW5pdEhhbmRsZXMuY29sbGlkZShtYXN0ZXJSZWN0LCBjbG9uZXJlY3QpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjbG9uZXIuc3R5bGUudG9wID0gKHBhcnNlRmxvYXQoY3Vyci53cmFwcGVyLnN0eWxlLnRvcCkgKyAxMCkgKyAncHgnO1xuICAgICAgICAgICAgICAgICAgICAgICAgY2xvbmVyLnN0eWxlLmxlZnQgPSAoKHBhcnNlSW50KGN1cnIud3JhcHBlci5zdHlsZS5sZWZ0LCAxMCkgKyBtYXN0ZXJSZWN0LndpZHRoKSArIDEwKSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgfSxcbiAgICBub29wOiBtd2QuY3JlYXRlRWxlbWVudCgnZGl2JyksXG4gICAgY3JlYXRlOiBmdW5jdGlvbigpIHtcblxuICAgICAgICB2YXIgZWRpdHMgPSBtd2QuYm9keS5xdWVyeVNlbGVjdG9yQWxsKFwiLmVkaXRcIiksXG4gICAgICAgICAgICBlbGVuID0gZWRpdHMubGVuZ3RoLFxuICAgICAgICAgICAgZWkgPSAwO1xuICAgICAgICBmb3IgKDsgZWkgPCBlbGVuOyBlaSsrKSB7XG4gICAgICAgICAgICB2YXIgZWxzID0gZWRpdHNbZWldLnF1ZXJ5U2VsZWN0b3JBbGwoJ3AsZGl2LGgxLGgyLGgzLGg0LGg1LGg2LHNlY3Rpb24saW1nJyksXG4gICAgICAgICAgICAgICAgaSA9IDAsXG4gICAgICAgICAgICAgICAgbCA9IGVscy5sZW5ndGg7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IGVsc1tpXTtcblxuICAgICAgICAgICAgICAgIGlmKCAhbXcuZHJhZy50YXJnZXQuY2FuQmVFbGVtZW50KGVsKSApe1xuICAgICAgICAgICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdmFyIGNscyA9IGVsLmNsYXNzTmFtZTtcblxuICAgICAgICAgICAgICAgIHZhciBpc0VsID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChlbCwgWydlbGVtZW50JywgJ21vZHVsZSddKVxuICAgICAgICAgICAgICAgICAgICAmJiAhbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKGVsLCBbJ213LWNvbCcsJ213LWNvbCcsICdtdy1yb3cnLCAnbXctem9uZSddKTtcbiAgICAgICAgICAgICAgICBpZiAoaXNFbCkge1xuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhlbCwgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgbXcuJChcIiNsaXZlX2VkaXRfdG9vbGJhcl9ob2xkZXIgLm1vZHVsZVwiKS5yZW1vdmVDbGFzcyhcIm1vZHVsZVwiKTtcblxuICAgICAgICBtdy5oYW5kbGVyTW91c2UgPSB7XG4gICAgICAgICAgICB4OiAwLFxuICAgICAgICAgICAgeTogMFxuICAgICAgICB9O1xuXG4gICAgICAgIG13LiQobXdkLmJvZHkpLm9uKCdtb3VzZW1vdmUgdG91Y2htb3ZlJywgZnVuY3Rpb24oZXZlbnQpIHtcblxuICAgICAgICAgICAgbXcuZHJhZ1NUT1BDaGVjayA9IGZhbHNlO1xuICAgICAgICAgICAgaWYgKCFtdy5zZXR0aW5ncy5yZXNpemVfc3RhcnRlZCkge1xuXG4gICAgICAgICAgICAgICAgbXcuZW1vdXNlID0gbXcuZXZlbnQucGFnZShldmVudCk7XG5cbiAgICAgICAgICAgICAgICBtdy5tbV90YXJnZXQgPSBldmVudC50YXJnZXQ7XG4gICAgICAgICAgICAgICAgbXcuJG1tX3RhcmdldCA9IG13LiQobXcubW1fdGFyZ2V0KTtcblxuICAgICAgICAgICAgICAgIGlmICghbXcuaXNEcmFnKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5saXZlRWRpdFNlbGVjdE1vZGUgPT09ICdlbGVtZW50Jykge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYobXcudG9vbHMuZGlzdGFuY2UobXcuaGFuZGxlck1vdXNlLngsIG13LmhhbmRsZXJNb3VzZS55LCBtdy5lbW91c2UueCwgbXcuZW1vdXNlLnkpID4gMjApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyh0aGlzLCAnaXNUeXBpbmcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5oYW5kbGVyTW91c2UgPSBPYmplY3QuYXNzaWduKHt9LCBtdy5lbW91c2UpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0SGFuZGxlcnMoZXZlbnQpXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB2YXIgc2lkZWJhciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsaXZlX2VkaXRfc2lkZV9ob2xkZXInKTtcbiAgICAgICAgICAgICAgICAgICAgaWYoc2lkZWJhciAmJiBzaWRlYmFyLmNvbnRhaW5zICYmIHNpZGViYXIuY29udGFpbnMobXcubW1fdGFyZ2V0KSl7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcm9wYWJsZS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5lYS5kYXRhLnRhcmdldCA9IG51bGw7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5lYS5kYXRhLmN1cnJlbnRHcmFiYmVkID0gbXcuZHJhZ0N1cnJlbnQ7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyh0aGlzLCAnaXNUeXBpbmcnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmVhLmludGVyYWN0aW9uQW5hbGl6ZXIoZXZlbnQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5jdXJyZW50RHJhZ01vdXNlT3ZlclwiKS5yZW1vdmVDbGFzcyhcImN1cnJlbnREcmFnTW91c2VPdmVyXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChtdy5jdXJyZW50RHJhZ01vdXNlT3ZlcikuYWRkQ2xhc3MoXCJjdXJyZW50RHJhZ01vdXNlT3ZlclwiKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgbXcuZHJvcGFibGVzLnByZXBhcmUoKTtcblxuICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnModHJ1ZSk7XG4gICAgICAgIG13LmRyYWcuZml4ZXMoKTtcblxuICAgICAgICBtdy4kKG13ZC5ib2R5KS5vbignbW91c2V1cCB0b3VjaGVuZCcsIGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgICAgICBtdy5tb3VzZURvd25TdGFydGVkID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAobXcuaXNEcmFnICYmIG13LmRyb3BhYmxlLmlzKFwiOmhpZGRlblwiKSkge1xuICAgICAgICAgICAgICAgIG13LiQoXCIudWktZHJhZ2dhYmxlLWRyYWdnaW5nXCIpLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgIHRvcDogMCxcbiAgICAgICAgICAgICAgICAgICAgbGVmdDogMFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcyhcIm5vdC1hbGxvd2VkXCIpO1xuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChtd2QuYm9keSkub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgICAgIHZhciB0YXJnZXQgPSBldmVudC50YXJnZXQ7XG4gICAgICAgICAgICBpZiAoJCh0YXJnZXQpLmhhc0NsYXNzKFwiaW1hZ2VfZnJlZV90ZXh0XCIpKSB7XG4gICAgICAgICAgICAgICAgbXcuaW1hZ2UuX2RyYWdjdXJyZW50ID0gdGFyZ2V0O1xuICAgICAgICAgICAgICAgIG13LmltYWdlLl9kcmFncGFyZW50ID0gdGFyZ2V0LnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgICAgdmFyIHBhZ2VQb3MgPSBtdy5ldmVudC5wYWdlKGV2ZW50KTtcbiAgICAgICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnNvckF0LnggPSBwYWdlUG9zLngtIHRhcmdldC5vZmZzZXRMZWZ0O1xuICAgICAgICAgICAgICAgIG13LmltYWdlLl9kcmFnY3Vyc29yQXQueSA9IHBhZ2VQb3MueSAtIHRhcmdldC5vZmZzZXRUb3A7XG4gICAgICAgICAgICAgICAgdGFyZ2V0LnN0YXJ0ZWRZID0gdGFyZ2V0Lm9mZnNldFRvcCAtIHRhcmdldC5wYXJlbnROb2RlLm9mZnNldFRvcDtcbiAgICAgICAgICAgICAgICB0YXJnZXQuc3RhcnRlZFggPSB0YXJnZXQub2Zmc2V0TGVmdCAtIHRhcmdldC5wYXJlbnROb2RlLm9mZnNldExlZnQ7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICgkKFwiLmRlc2NfYXJlYV9ob3ZlclwiKS5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgICAgICBtdy4kKFwiLmRlc2NfYXJlYVwiKS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LmNsYXNzTmFtZSwgJ213LW9wZW4tbW9kdWxlLXNldHRpbmdzJykpIHtcblxuICAgICAgICAgICAgICAgIGlmKCFtdy5zZXR0aW5ncy5saXZlX2VkaXRfb3Blbl9tb2R1bGVfc2V0dGluZ3NfaW5fc2lkZWJhcil7XG4gICAgICAgICAgICAgICAgICAgIG13LmRyYWcubW9kdWxlX3NldHRpbmdzKG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3NlcyhldmVudC50YXJnZXQsIFsnbW9kdWxlJ10pKVxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0YXJnZXQgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyhldmVudC50YXJnZXQsICdtb2R1bGUnKSA7XG4gICAgICAgICAgICAgICAgICAgIG13LmxpdmVOb2RlU2V0dGluZ3Muc2V0KCdtb2R1bGUnLCB0YXJnZXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNQYXJlbnRzV2l0aFRhZyhldmVudC50YXJnZXQsICdUQUJMRScpICYmICFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGV2ZW50LnRhcmdldCwgJ213LWlubGluZS1iYXInKSkge1xuICAgICAgICAgICAgICAgIG13LiQobXcubGl2ZWVkaXQuaW5saW5lLnRhYmxlQ29udHJvbCkuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG13LiQoXCIudGMtYWN0aXZlY2VsbFwiKS5yZW1vdmVDbGFzcygndGMtYWN0aXZlY2VsbCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgIH0sXG5cbiAgICBpbml0OiBmdW5jdGlvbihzZWxlY3RvciwgY2FsbGJhY2spIHtcbiAgICAgICAgbXcuZHJhZy50aGVfZHJvcCgpO1xuICAgIH0sXG4gICAgcHJvcGVyRm9jdXM6IGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgIHZhciB0b2ZvY3VzO1xuICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctcm93JykgfHwgbXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctY29sJykpIHtcbiAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhldmVudC50YXJnZXQsICdtdy1jb2wnKSkge1xuICAgICAgICAgICAgICAgIHRvZm9jdXMgPSBldmVudC50YXJnZXQucXVlcnlTZWxlY3RvcignLm13LWNvbC1jb250YWluZXInKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdmFyIGkgPSAwLFxuICAgICAgICAgICAgICAgICAgICBjb2xzID0gZXZlbnQudGFyZ2V0LmNoaWxkcmVuLFxuICAgICAgICAgICAgICAgICAgICBsID0gY29scy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIF9jbGVmdCA9IG13LiQoY29sc1tpXSkub2Zmc2V0KCkubGVmdDtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGVQb3MgPSBtdy5ldmVudC5wYWdlKGV2ZW50KTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKF9jbGVmdCA8IGVQb3MueCAmJiAoX2NsZWZ0ICsgY29sc1tpXS5jbGllbnRXaWR0aCkgPiBlUG9zLngpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRvZm9jdXMgPSBjb2xzW2ldLnF1ZXJ5U2VsZWN0b3IoJy5tdy1jb2wtY29udGFpbmVyJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodG9mb2N1cyA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbHNbaV0uaW5uZXJIVE1MID0gJzxkaXYgY2xhc3M9XCJtdy1jb2wtY29udGFpbmVyXCI+JyArIGNvbHNbaV0uaW5uZXJIVE1MICsgJzwvZGl2Pic7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB0b2ZvY3VzID0gY29sc1tpXS5xdWVyeVNlbGVjdG9yKCcubXctY29sLWNvbnRhaW5lcicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoISF0b2ZvY3VzICYmIHRvZm9jdXMucXVlcnlTZWxlY3RvcignLmVsZW1lbnQnKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHZhciBhcnIgPSB0b2ZvY3VzLnF1ZXJ5U2VsZWN0b3JBbGwoJy5lbGVtZW50JyksXG4gICAgICAgICAgICAgICAgICAgIGwgPSBhcnIubGVuZ3RoO1xuICAgICAgICAgICAgICAgIHRvZm9jdXMgPSBhcnJbbCAtIDFdO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoISF0b2ZvY3VzKSB7XG4gICAgICAgICAgICAgICAgdmFyIHJhbmdlID0gZG9jdW1lbnQuY3JlYXRlUmFuZ2UoKTtcbiAgICAgICAgICAgICAgICB2YXIgc2VsID0gd2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgICAgIHJhbmdlLnNlbGVjdE5vZGVDb250ZW50cyh0b2ZvY3VzKTtcbiAgICAgICAgICAgICAgICByYW5nZS5jb2xsYXBzZShmYWxzZSk7XG4gICAgICAgICAgICAgICAgc2VsLnJlbW92ZUFsbFJhbmdlcygpO1xuICAgICAgICAgICAgICAgIHNlbC5hZGRSYW5nZShyYW5nZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgdG9vbGJhcl9tb2R1bGVzOiBmdW5jdGlvbihzZWxlY3Rvcikge1xuICAgICAgICBtdy5saXZlZWRpdC5tb2R1bGVzVG9vbGJhci5pbml0KHNlbGVjdG9yKTtcbiAgICB9LFxuICAgIHRoZV9kcm9wOiBmdW5jdGlvbigpIHtcblxuXG5cbiAgICAgICAgaWYgKCEkKG13ZC5ib2R5KS5oYXNDbGFzcyhcImJ1cFwiKSkge1xuICAgICAgICAgICAgbXcuJChtd2QuYm9keSkuYWRkQ2xhc3MoXCJidXBcIik7XG5cblxuXG4gICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5vbihcIm1vdXNldXAgdG91Y2hlbmRcIiwgZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnJlbnQgPSBudWxsO1xuICAgICAgICAgICAgICAgIG13LmltYWdlLl9kcmFncGFyZW50ID0gbnVsbDtcbiAgICAgICAgICAgICAgICB2YXIgc2xpZGVycyA9IG13ZC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwiY2FudmFzLXNsaWRlclwiKSxcbiAgICAgICAgICAgICAgICAgICAgbGVuID0gc2xpZGVycy5sZW5ndGgsXG4gICAgICAgICAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbGVuOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgc2xpZGVyc1tpXS5pc0RyYWcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZigoZXZlbnQudHlwZSA9PT0gJ21vdXNldXAnIHx8IGV2ZW50LnR5cGUgPT09ICd0b3VjaGVuZCcpICYmIG13LmxpdmVFZGl0U2VsZWN0TW9kZSA9PT0gJ25vbmUnKXtcbiAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3RNb2RlID0gJ2VsZW1lbnQnO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoIW13LmlzRHJhZykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgdGFyZ2V0ID0gZXZlbnQudGFyZ2V0O1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29tcG9uZW50c0NsYXNzZXMgPSBbXG4gICAgICAgICAgICAgICAgICAgICAgICAnZWxlbWVudCcsXG4gICAgICAgICAgICAgICAgICAgICAgICAnc2FmZS1lbGVtZW50JyxcbiAgICAgICAgICAgICAgICAgICAgICAgICdtb2R1bGUnLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ3BsYWluLXRleHQnXG4gICAgICAgICAgICAgICAgICAgIF07XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIGN1cnJlbnRDb21wb25lbnQgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXModGFyZ2V0LCBjb21wb25lbnRzQ2xhc3Nlcyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBmb250dGFyZ2V0ID0gbXcubGl2ZWVkaXQuZGF0YS5nZXQoJ21vdXNldXAnLCAnaXNJY29uJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoIG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlc09uTm9kZU9yUGFyZW50KHRhcmdldCwgY29tcG9uZW50c0NsYXNzZXMpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoY3VycmVudENvbXBvbmVudCAmJiAhZm9udHRhcmdldCkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG9yZGVyID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdCh0YXJnZXQsIFsnc2FmZS1tb2RlJywgJ21vZHVsZSddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZihtdy50b29scy5oYXNDbGFzcyhjdXJyZW50Q29tcG9uZW50LCAnbW9kdWxlJykpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiQ29tcG9uZW50Q2xpY2tcIiwgW3RhcmdldCwgJ21vZHVsZSddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAobXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkgJiYgIW13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyh0YXJnZXQsIGNvbXBvbmVudHNDbGFzc2VzKSAmJiBvcmRlcikge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiQ29tcG9uZW50Q2xpY2tcIiwgW3RhcmdldCwgJ2VsZW1lbnQnXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIW13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyh0YXJnZXQsIGNvbXBvbmVudHNDbGFzc2VzKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGN0eXBlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGhhcyA9IGNvbXBvbmVudHNDbGFzc2VzLmZpbHRlcihmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBjdXJyZW50Q29tcG9uZW50LmNsYXNzTGlzdC5jb250YWlucyhpdGVtKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiQ29tcG9uZW50Q2xpY2tcIiwgW2N1cnJlbnRDb21wb25lbnQsIGhhc1swXV0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZWwgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyh0YXJnZXQsICdlbGVtZW50Jyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBzYWZlRWwgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyh0YXJnZXQsICdzYWZlLWVsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBtb2R1bGVFbCA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKHRhcmdldCwgJ21vZHVsZScpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoJCh0YXJnZXQpLmhhc0NsYXNzKFwicGxhaW4tdGV4dFwiKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJQbGFpblRleHRDbGlja1wiLCB0YXJnZXQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmIChlbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJFbGVtZW50Q2xpY2tcIiwgW2VsLCBldmVudF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoc2FmZUVsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIlNhZmVFbGVtZW50Q2xpY2tcIiwgW3NhZmVFbCwgZXZlbnRdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG1vZHVsZUVsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIk1vZHVsZUNsaWNrXCIsIFttb2R1bGVFbCwgZXZlbnRdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmIChmb250dGFyZ2V0ICYmICFtdy50b29scy5oYXNBbnlPZkNsYXNzZXModGFyZ2V0LCBbJ2VsZW1lbnQnLCAnbW9kdWxlJ10pKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoKGZvbnR0YXJnZXQudGFnTmFtZSA9PT0gJ0knIHx8IGZvbnR0YXJnZXQudGFnTmFtZSA9PT0gJ1NQQU4nKSAmJiAhbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhmb250dGFyZ2V0LCAnZHJvcGRvd24nKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKG13LnRvb2xzLnBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3QoZm9udHRhcmdldCwgWydlZGl0JywgJ21vZHVsZSddKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiSWNvbkVsZW1lbnRDbGlja1wiLCBmb250dGFyZ2V0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkNvbXBvbmVudENsaWNrXCIsIFtmb250dGFyZ2V0LCAnaWNvbiddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAoJCh0YXJnZXQpLmhhc0NsYXNzKFwibXdfaXRlbVwiKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkl0ZW1DbGlja1wiLCB0YXJnZXQpO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3ModGFyZ2V0LCAnbXdfaXRlbScpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiSXRlbUNsaWNrXCIsIG13LiQodGFyZ2V0KS5wYXJlbnRzKFwiLm13X2l0ZW1cIilbMF0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmICh0YXJnZXQudGFnTmFtZSA9PT0gJ0lNRycgJiYgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyh0YXJnZXQsICdlZGl0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBvcmRlciA9IG13LnRvb2xzLnBhcmVudHNPcmRlcihtdy5tbV90YXJnZXQsIFsnZWRpdCcsICdtb2R1bGUnXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoKG9yZGVyLm1vZHVsZSA9PSAtMSkgfHwgKG9yZGVyLmVkaXQgPiAtMSAmJiBvcmRlci5lZGl0IDwgb3JkZXIubW9kdWxlKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyh0YXJnZXQsICdtdy1kZWZhdWx0cycpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJJbWFnZUNsaWNrXCIsIHRhcmdldCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0X2VsZW1lbnQodGFyZ2V0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAodGFyZ2V0LnRhZ05hbWUgPT09ICdCT0RZJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkJvZHlDbGlja1wiLCB0YXJnZXQpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgICAgICAgICB2YXIgaXNUZCA9ICB0YXJnZXQudGFnTmFtZSA9PT0gJ1REJyA/IHRhcmdldCA6IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyh0YXJnZXQsICd0ZCcpO1xuICAgICAgICAgICAgICAgICAgICBpZighIWlzVGQpe1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYobXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdCh0YXJnZXQsIFsnZWRpdCcsICdtb2R1bGUnXSkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJUYWJsZVRkQ2xpY2tcIiwgdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyh0YXJnZXQsICdtdy1lbXB0eScpIHx8IG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3ModGFyZ2V0LCAnbXctZW1wdHknKSkge1xuXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3ModGFyZ2V0LCAnbXctZW1wdHknKSAmJiB0YXJnZXQuaW5uZXJIVE1MLnRyaW0oKSAhPT0gJycpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc05hbWUgPSAnZWxlbWVudCc7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5wcm9wZXJGb2N1cyhldmVudCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChtdy5pc0RyYWcpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuaXNEcmFnID0gZmFsc2U7XG5cblxuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5jdXJyZW50RHJhZ01vdXNlT3Zlcik7XG4gICAgICAgICAgICAgICAgICAgIG13LiQobXcuY3VycmVudERyYWdNb3VzZU92ZXIpLnJlbW92ZUNsYXNzKFwiY3VycmVudERyYWdNb3VzZU92ZXJcIik7XG5cbiAgICAgICAgICAgICAgICAgICAgbXcuX2luaXRIYW5kbGVzLmhpZGVBbGwoKTtcblxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYobXcuZWEuZGF0YS50YXJnZXQgJiYgbXcuZWEuZGF0YS5jdXJyZW50R3JhYmJlZCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoISFtdy5lYS5kYXRhLmRyb3BhYmxlQWN0aW9uICYmICEhbXcuZWEuZGF0YS50YXJnZXQgJiYgISFtdy5lYS5kYXRhLmN1cnJlbnRHcmFiYmVkKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGVkID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQ2xhc3MobXcuZWEuZGF0YS50YXJnZXQsICdlZGl0Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBwcmV2ID0gbXcuZWEuZGF0YS5jdXJyZW50R3JhYmJlZC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgcmVjID0ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGFyZ2V0OiBlZCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiBlZC5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYocHJldil7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgcHJldkRvYyA9IG13LnRvb2xzLnBhcnNlSHRtbChwcmV2LmlubmVySFRNTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHByZXZEb2MucXVlcnlTZWxlY3RvcignLm13X2RyYWdfY3VycmVudCcpKS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZpc2liaWxpdHk6ICdoaWRkZW4nLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9wYWNpdHk6IDBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVjLnByZXYgPSBwcmV2O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVjLnByZXZWYWx1ZSA9IHByZXZEb2MuYm9keS5pbm5lckhUTUw7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZChyZWMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG13LmVhLmRhdGEudGFyZ2V0KVttdy5lYS5kYXRhLmRyb3BhYmxlQWN0aW9uXShtdy5lYS5kYXRhLmN1cnJlbnRHcmFiYmVkKTtcblxuXG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKGVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgbnJlYyA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IGVkLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiBlZC5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZihwcmV2KXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgcHJldkRvYyA9IG13LnRvb2xzLnBhcnNlSHRtbChwcmV2LmlubmVySFRNTCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChwcmV2RG9jLnF1ZXJ5U2VsZWN0b3IoJy5td19kcmFnX2N1cnJlbnQnKSkuY3NzKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmlzaWJpbGl0eTogJ2hpZGRlbicsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9wYWNpdHk6IDBcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBucmVjLnByZXYgPSBwcmV2O1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5yZWMucHJldlZhbHVlID0gcHJldkRvYy5ib2R5LmlubmVySFRNTDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKG5yZWMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCA1MCwgZWQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbHNle1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5maXhlcygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnMoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5lYS5hZnRlckFjdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKG13LmxpdmVFZGl0RG9tVHJlZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdERvbVRyZWUucmVmcmVzaChtdy5lYS5kYXRhLnRhcmdldC5wYXJlbnROb2RlKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH0sIDQwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyb3BhYmxlLmhpZGUoKTtcblxuICAgICAgICAgICAgICAgICAgICB9LCA3Nyk7XG5cblxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG13LmhhdmVfbmV3X2l0ZW1zID09IHRydWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmxvYWRfbmV3X21vZHVsZXMoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSwgMTIwKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9IC8vdG9yZW1vdmVcbiAgICB9LFxuXG5cbiAgICAvKipcbiAgICAgKiBWYXJpb3VzIGZpeGVzXG4gICAgICpcbiAgICAgKiBAZXhhbXBsZSBtdy5kcmFnLmZpeGVzKClcbiAgICAgKi9cbiAgICBmaXhlczogZnVuY3Rpb24oKSB7XG4gICAgICAgIG13LiQoXCIuZWRpdCAubXctY29sLCAuZWRpdCAubXctcm93XCIpLmhlaWdodCgnYXV0bycpO1xuICAgICAgICBtdy4kKG13LmRyYWdDdXJyZW50KS5jc3Moe1xuICAgICAgICAgICAgdG9wOiAnJyxcbiAgICAgICAgICAgIGxlZnQ6ICcnXG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgbW9yZV9zZWxlY3RvcnMgPSAnJztcbiAgICAgICAgLy92YXIgY29scyA9IG13LmRyYWcuZXh0ZXJuYWxfZ3JpZHNfY29sX2NsYXNzZXM7XG4gICAgICAgIHZhciBjb2xzID0gW107XG4gICAgICAgIHZhciBpbmRleDtcbiAgICAgICAgZm9yIChpbmRleCA9IGNvbHMubGVuZ3RoIC0gMTsgaW5kZXggPj0gMDsgLS1pbmRleCkge1xuICAgICAgICAgICAgbW9yZV9zZWxlY3RvcnMgKz0gJywuZWRpdCAucm93ID4gLicgKyBjb2xzW2luZGV4XTtcbiAgICAgICAgfVxuICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgbXcuJChcIi5lZGl0IC5tdy1jb2xcIiArIG1vcmVfc2VsZWN0b3JzKS5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICAgICAgaWYgKGVsLmNoaWxkcmVuKCkubGVuZ3RoID09PSAwIHx8IChlbC5jaGlsZHJlbignLmVtcHR5LWVsZW1lbnQnKS5sZW5ndGggPiAwKSB8fCBlbC5jaGlsZHJlbignLnVpLWRyYWdnYWJsZS1kcmFnZ2luZycpLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgZWwuaGVpZ2h0KCdhdXRvJyk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChlbC5oZWlnaHQoKSA8IGVsLnBhcmVudCgpLmhlaWdodCgpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlbC5oZWlnaHQoZWwucGFyZW50KCkuaGVpZ2h0KCkpO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwuaGVpZ2h0KCdhdXRvJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBlbC5jaGlsZHJlbignLmVtcHR5LWVsZW1lbnQnKS5oZWlnaHQoJ2F1dG8nKTtcbiAgICAgICAgICAgICAgICAgICAgZWwuaGVpZ2h0KCdhdXRvJyk7XG4gICAgICAgICAgICAgICAgICAgIGVsLnBhcmVudHMoJy5tdy1yb3c6Zmlyc3QnKS5oZWlnaHQoJ2F1dG8nKVxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGVsLmhlaWdodCgnYXV0bycpO1xuICAgICAgICAgICAgICAgIHZhciBtd3IgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyh0aGlzLCAnbXctcm93Jyk7XG4gICAgICAgICAgICAgICAgaWYgKCEhbXdyKSB7XG4gICAgICAgICAgICAgICAgICAgIG13ci5zdHlsZS5oZWlnaHQgPSAnYXV0byc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LmRyYWcuZml4RGVuaWVkUGFyYWdyYXBoSGllcmFyY2h5KCk7XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LCAyMjIpO1xuXG4gICAgICAgIHZhciBlbHMgPSBtd2QucXVlcnlTZWxlY3RvckFsbCgnZGl2LmVsZW1lbnQnKSxcbiAgICAgICAgICAgIGwgPSBlbHMubGVuZ3RoLFxuICAgICAgICAgICAgaSA9IDA7XG4gICAgICAgIGlmIChsID4gMCkge1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICBpZiAoZWxzW2ldLnF1ZXJ5U2VsZWN0b3IoJ3AsZGl2LGxpLGgxLGgyLGgzLGg0LGg1LGg2LGZpZ3VyZSxpbWcnKSA9PT0gbnVsbCAmJiAhbXcudG9vbHMuaGFzQ2xhc3MoZWxzW2ldLCAncGxhaW4tdGV4dCcpKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICghbXcudG9vbHMuaGFzQ2xhc3MoZWxzW2ldLmNsYXNzTmFtZSwgJ25vZHJvcCcpICYmICFtdy50b29scy5oYXNDbGFzcyhlbHNbaV0uY2xhc3NOYW1lLCAnbXctZW1wdHknKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIW13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlc09uTm9kZU9yUGFyZW50KGVsc1tpXSwgWydzYWZlLW1vZGUnXSkpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsc1tpXS5pbm5lckhUTUwgPSAnPHAgY2xhc3M9XCJlbGVtZW50XCI+JyArIGVsc1tpXS5pbm5lckhUTUwgKyAnPC9wPic7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIC8qKlxuICAgICAqIGZpeF9wbGFjZWhvbGRlcnMgaW4gdGhlIGxheW91dFxuICAgICAqXG4gICAgICogQGV4YW1wbGUgbXcuZHJhZy5maXhfcGxhY2Vob2xkZXJzKGlzSGFyZCAsIHNlbGVjdG9yKVxuICAgICAqL1xuICAgIGZpeF9wbGFjZWhvbGRlcnM6IGZ1bmN0aW9uKGlzSGFyZCwgc2VsZWN0b3IpIHtcbiAgICAgICAgc2VsZWN0b3IgPSBzZWxlY3RvciB8fCAnLmVkaXQgLnJvdyc7XG5cbiAgICAgICAgdmFyIG1vcmVfc2VsZWN0b3JzMiA9ICdkaXYuY29sLW1kJztcbiAgICAgICAgdmFyIGEgPSBtdy5kcmFnLmV4dGVybmFsX2dyaWRzX2NvbF9jbGFzc2VzO1xuICAgICAgICB2YXIgaW5kZXg7XG4gICAgICAgIGZvciAoaW5kZXggPSBhLmxlbmd0aCAtIDE7IGluZGV4ID49IDA7IC0taW5kZXgpIHtcbiAgICAgICAgICAgIG1vcmVfc2VsZWN0b3JzMiArPSAnLGRpdi4nICsgYVtpbmRleF07XG4gICAgICAgIH1cbiAgICAgICAgbXcuJChzZWxlY3RvcikuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICBlbC5jaGlsZHJlbihtb3JlX3NlbGVjdG9yczIpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdmFyIGVtcHR5X2NoaWxkID0gbXcuJCh0aGlzKS5jaGlsZHJlbignKicpO1xuICAgICAgICAgICAgICAgIGlmIChlbXB0eV9jaGlsZC5zaXplKCkgPT0gMCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmFwcGVuZCgnPGRpdiBjbGFzcz1cImVsZW1lbnRcIiBpZD1cIm13LWVsZW1lbnQtJyArIG13LnJhbmRvbSgpICsgJ1wiPicgKyAnPC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlbXB0eV9jaGlsZCA9IG13LiQodGhpcykuY2hpbGRyZW4oXCJkaXYuZWxlbWVudFwiKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG5cbiAgICB9LFxuICAgIC8qKlxuICAgICAqIFJlbW92ZXMgY29udGVudEVkaXRhYmxlIGZvciBBTEwgZWxlbWVudHNcbiAgICAgKlxuICAgICAqIEBleGFtcGxlIG13LmRyYWcuZWRpdF9yZW1vdmUoKTtcbiAgICAgKi9cbiAgICBlZGl0X3JlbW92ZTogZnVuY3Rpb24oKSB7XG5cbiAgICAgICAgLy9cdCAgIFx0bXcuJCgnLmVkaXQgW2NvbnRlbnRlZGl0YWJsZV0nKS5yZW1vdmVBdHRyKFwiY29udGVudGVkaXRhYmxlXCIpO1xuXG4gICAgfSxcblxuICAgIHRhcmdldCA6ICB7XG5cbiAgICAgICAgY2FuQmVFbGVtZW50OiBmdW5jdGlvbih0YXJnZXQpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IHRhcmdldDtcbiAgICAgICAgICAgIHZhciBub2VsZW1lbnRzID0gWydtdy11aS1jb2wnLCAnbXctY29sLWNvbnRhaW5lcicsICdtdy11aS1jb2wtY29udGFpbmVyJ107XG5cbiAgICAgICAgICAgIHZhciBub2VsZW1lbnRzX2JzMyA9IG13LmRyYWcuZXh0ZXJuYWxfZ3JpZHNfY29sX2NsYXNzZXM7XG4gICAgICAgICAgICB2YXIgbm9lbGVtZW50c19leHQgPSBtdy5kcmFnLmV4dGVybmFsX2Nzc19ub19lbGVtZW50X2NsYXNzZXM7XG4gICAgICAgICAgICB2YXIgbm9lbGVtZW50c19kcmFnID0gbXcuZHJhZy5leHRlcm5hbF9jc3Nfbm9fZWxlbWVudF9jb250cm9sbF9jbGFzc2VzO1xuICAgICAgICAgICAgdmFyIHNlY3Rpb25fc2VsZWN0b3JzID0gbXcuZHJhZy5zZWN0aW9uX3NlbGVjdG9ycztcbiAgICAgICAgICAgIHZhciBpY29uX3NlbGVjdG9ycyA9ICBtdy53eXNpd3lnLmZvbnRJY29uRmFtaWxpZXM7XG5cbiAgICAgICAgICAgIG5vZWxlbWVudHMgPSBub2VsZW1lbnRzLmNvbmNhdChub2VsZW1lbnRzX2JzMyk7XG4gICAgICAgICAgICBub2VsZW1lbnRzID0gbm9lbGVtZW50cy5jb25jYXQobm9lbGVtZW50c19leHQpO1xuICAgICAgICAgICAgbm9lbGVtZW50cyA9IG5vZWxlbWVudHMuY29uY2F0KG5vZWxlbWVudHNfZHJhZyk7XG4gICAgICAgICAgICBub2VsZW1lbnRzID0gbm9lbGVtZW50cy5jb25jYXQoc2VjdGlvbl9zZWxlY3RvcnMpO1xuICAgICAgICAgICAgbm9lbGVtZW50cyA9IG5vZWxlbWVudHMuY29uY2F0KGljb25fc2VsZWN0b3JzKTtcblxuICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmhhc0FueU9mQ2xhc3NlcyhlbCwgbm9lbGVtZW50cyk7XG4gICAgICAgIH0sXG4gICAgICAgIGNhbkJlRWRpdGFibGU6IGZ1bmN0aW9uKGVsKSB7XG4gICAgICAgICAgICByZXR1cm4gZWwuaXNDb250ZW50RWRpdGFibGUgfHwgbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChlbCwgWydlZGl0JywnbW9kdWxlJ10pO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIGZhbmN5bmF0ZUxvYWRpbmc6IGZ1bmN0aW9uKG1vZHVsZSkge1xuICAgICAgICBtdy4kKG1vZHVsZSkuYWRkQ2xhc3MoXCJtb2R1bGVfbG9hZGluZ1wiKTtcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIG13LiQobW9kdWxlKS5hZGRDbGFzcyhcIm1vZHVsZV9hY3RpdmF0ZWRcIik7XG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIG13LiQobW9kdWxlKS5yZW1vdmVDbGFzcyhcIm1vZHVsZV9sb2FkaW5nIG1vZHVsZV9hY3RpdmF0ZWRcIik7XG4gICAgICAgICAgICB9LCA1MTApO1xuICAgICAgICB9LCAxNTApO1xuICAgIH0sXG5cbiAgICAvKipcbiAgICAgKiBTY2FucyBmb3IgbmV3IGRyb3BwZWQgbW9kdWxlcyBhbmQgbG9hZHMgdGhlbVxuICAgICAqXG4gICAgICogQGV4YW1wbGUgbXcuZHJhZy5sb2FkX25ld19tb2R1bGVzKClcbiAgICAgKiBAcmV0dXJuIHZvaWRcbiAgICAgKi9cbiAgICBsb2FkX25ld19tb2R1bGVzOiBmdW5jdGlvbihjYWxsYmFjaykge1xuICAgICAgICByZXR1cm4gbXcuZWEuYWZ0ZXJBY3Rpb24oKTtcbiAgICB9LFxuXG4gICAgbW9kdWxlX3ZpZXc6IGZ1bmN0aW9uKHZpZXcpIHtcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZHJhZy5tb2R1bGVfc2V0dGluZ3MoZmFsc2UsIHZpZXcpO1xuICAgICAgICByZXR1cm4gbW9kYWw7XG4gICAgfSxcbiAgICBtb2R1bGVfc2V0dGluZ3M6IGZ1bmN0aW9uKGEsIHZpZXcpIHtcbiAgICAgICAgcmV0dXJuIG13LnRvb2xzLm1vZHVsZV9zZXR0aW5ncyhhLCB2aWV3KTtcbiAgICB9LFxuICAgIGN1cnJlbnRfbW9kdWxlX3NldHRpbmdzX3Rvb2x0aXBfc2hvd19vbl9lbGVtZW50OiBmdW5jdGlvbihlbGVtZW50X2lkLCB2aWV3LCB0eXBlKSB7XG4gICAgICAgIGlmICghZWxlbWVudF9pZCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKG13LiQoJyMnICsgZWxlbWVudF9pZCkubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgY3VyciA9IG13Ll9hY3RpdmVNb2R1bGVPdmVyO1xuICAgICAgICB2YXIgdG9vbHRpcF9lbGVtZW50ID0gbXcuJChcIiNcIiArIGVsZW1lbnRfaWQpO1xuICAgICAgICB2YXIgYXR0cmlidXRlcyA9IHt9O1xuICAgICAgICB2YXIgdHlwZSA9IHR5cGUgfHwgJ21vZGFsJztcbiAgICAgICAgJC5lYWNoKGN1cnIuYXR0cmlidXRlcywgZnVuY3Rpb24oaW5kZXgsIGF0dHIpIHtcbiAgICAgICAgICAgIGF0dHJpYnV0ZXNbYXR0ci5uYW1lXSA9IGF0dHIudmFsdWU7XG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgZGF0YTEgPSBhdHRyaWJ1dGVzO1xuICAgICAgICB2YXIgbW9kdWxlX3R5cGUgPSBudWxsO1xuICAgICAgICBpZiAoZGF0YTFbJ2RhdGEtdHlwZSddKSB7XG4gICAgICAgICAgICBtb2R1bGVfdHlwZSA9IGRhdGExWydkYXRhLXR5cGUnXTtcbiAgICAgICAgICAgIGRhdGExWydkYXRhLXR5cGUnXSA9IGRhdGExWydkYXRhLXR5cGUnXSArICcvYWRtaW4nO1xuICAgICAgICB9XG4gICAgICAgIGlmIChkYXRhMVsnZGF0YS1tb2R1bGUtbmFtZSddKSB7XG4gICAgICAgICAgICBkZWxldGUoZGF0YTFbJ2RhdGEtbW9kdWxlLW5hbWUnXSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGRhdGExWyd0eXBlJ10pIHtcbiAgICAgICAgICAgIG1vZHVsZV90eXBlID0gZGF0YTFbJ3R5cGUnXTtcbiAgICAgICAgICAgIGRhdGExWyd0eXBlJ10gPSBkYXRhMVsndHlwZSddICsgJy9hZG1pbic7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG1vZHVsZV90eXBlICE9IG51bGwgJiYgdmlldykge1xuICAgICAgICAgICAgZGF0YTFbJ2RhdGEtdHlwZSddID0gZGF0YTFbJ3R5cGUnXSA9IG1vZHVsZV90eXBlICsgJy8nICsgdmlldztcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChkYXRhMVsnY2xhc3MnXSkge1xuICAgICAgICAgICAgZGVsZXRlKGRhdGExWydjbGFzcyddKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoZGF0YTFbJ3N0eWxlJ10pIHtcbiAgICAgICAgICAgIGRlbGV0ZShkYXRhMVsnc3R5bGUnXSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGRhdGExLmNvbnRlbnRlZGl0YWJsZSkge1xuICAgICAgICAgICAgZGVsZXRlKGRhdGExLmNvbnRlbnRlZGl0YWJsZSk7XG4gICAgICAgIH1cbiAgICAgICAgZGF0YTEubGl2ZV9lZGl0ID0gJ3RydWUnO1xuICAgICAgICBkYXRhMS5tb2R1bGVfc2V0dGluZ3MgPSAndHJ1ZSc7XG4gICAgICAgIGlmICh2aWV3ICE9IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgZGF0YTEudmlldyA9IHZpZXc7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBkYXRhMS52aWV3ID0gJ2FkbWluJztcbiAgICAgICAgfVxuICAgICAgICBpZiAoZGF0YTEuZnJvbV91cmwgPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICBkYXRhMS5mcm9tX3VybCA9IHdpbmRvdy5wYXJlbnQubG9jYXRpb247XG4gICAgICAgIH1cbiAgICAgICAgdmFyIG1vZGFsX25hbWUgPSAnbW9kdWxlLXNldHRpbmdzLScgKyBjdXJyLmlkO1xuICAgICAgICBpZiAodHlwZW9mKGRhdGExLnZpZXcuaGFzaCkgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgIHZhciBtb2RhbF9uYW1lID0gJ21vZHVsZS1zZXR0aW5ncy0nICsgY3Vyci5pZCArIChkYXRhMS52aWV3Lmhhc2goKSk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAobXcuJCgnIycgKyBtb2RhbF9uYW1lKS5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICB2YXIgbSA9IG13LiQoJyMnICsgbW9kYWxfbmFtZSlbMF07XG4gICAgICAgICAgICBtLnNjcm9sbEludG9WaWV3KCk7XG4gICAgICAgICAgICBtdy50b29scy5oaWdobGlnaHQobSk7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHNyYyA9IG13LnNldHRpbmdzLnNpdGVfdXJsICsgXCJhcGkvbW9kdWxlP1wiICsganNvbjJ1cmwoZGF0YTEpO1xuXG4gICAgICAgIGlmICh0eXBlID09PSAnbW9kYWwnKSB7XG4gICAgICAgICAgICB2YXIgbW9kYWwgPSBtdy50b3AoKS5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgICAgIHVybDogc3JjLFxuICAgICAgICAgICAgICAgIHdpZHRoOiA1MzIsXG4gICAgICAgICAgICAgICAgaGVpZ2h0OiAxNTAsXG4gICAgICAgICAgICAgICAgbmFtZTogbW9kYWxfbmFtZSxcbiAgICAgICAgICAgICAgICB0aXRsZTogJycsXG4gICAgICAgICAgICAgICAgY2FsbGJhY2s6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMuY29udGFpbmVyKS5hdHRyKCdkYXRhLXNldHRpbmdzLWZvci1tb2R1bGUnLCBjdXJyLmlkKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBtb2RhbDtcbiAgICAgICAgfVxuICAgICAgICBpZiAodHlwZSA9PT0gJ3Rvb2x0aXAnKSB7XG4gICAgICAgICAgICB2YXIgaWQgPSAnbXctdG9vbHRpcC1pZnJhbWUtJysgbXcucmFuZG9tKClcbiAgICAgICAgICAgIG13LnRvb2x0aXAoe1xuICAgICAgICAgICAgICAgIGlkOiAnbW9kdWxlLXNldHRpbmdzLXRvb2x0aXAtJyArIG1vZGFsX25hbWUsXG4gICAgICAgICAgICAgICAgZ3JvdXA6ICdtb2R1bGVfc2V0dGluZ3NfdG9vbHRpcF9zaG93X29uX2J0bicsXG4gICAgICAgICAgICAgICAgY2xvc2Vfb25fY2xpY2tfb3V0c2lkZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICBjb250ZW50OiAnPGlmcmFtZSBpZD1cIicraWQrJ1wiIGZyYW1lYm9yZGVyPVwiMFwiIGNsYXNzPVwibXctdG9vbHRpcC1pZnJhbWVcIiBzcmM9XCInICsgc3JjICsgJ1wiIHNjcm9sbGluZz1cImF1dG9cIj48L2lmcmFtZT4nLFxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRvb2x0aXBfZWxlbWVudFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy50b29scy5pZnJhbWVBdXRvSGVpZ2h0KG13ZC5xdWVyeVNlbGVjdG9yKCcjJytpZCkpXG4gICAgICAgIH1cblxuICAgIH0sXG5cbiAgICAvKipcbiAgICAgKiBMb2FkcyB0aGUgbW9kdWxlIGluIHRoZSBnaXZlbiBkb20gZWxlbWVudCBieSB0aGUgJHVwZGF0ZV9lbGVtZW50IHNlbGVjdG9yIC5cbiAgICAgKlxuICAgICAqIEBleGFtcGxlIG13LmRyYWcubG9hZF9tb2R1bGUoJ3VzZXJfbG9naW4nLCAnI2xvZ2luX2JveCcpXG4gICAgICogQHBhcmFtICRtb2R1bGVfbmFtZVxuICAgICAqIEBwYXJhbSAkdXBkYXRlX2VsZW1lbnRcbiAgICAgKiBAcmV0dXJuIHZvaWRcbiAgICAgKi9cbiAgICBsb2FkX21vZHVsZTogZnVuY3Rpb24oJG1vZHVsZV9uYW1lLCAkdXBkYXRlX2VsZW1lbnQpIHtcbiAgICAgICAgdmFyIGF0dHJpYnV0ZXMgPSB7fTtcbiAgICAgICAgYXR0cmlidXRlcy5tb2R1bGUgPSAkbW9kdWxlX25hbWU7XG4gICAgICAgIHZhciB1cmwxID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyAnYXBpL21vZHVsZSc7XG4gICAgICAgIG13LiQoJHVwZGF0ZV9lbGVtZW50KS5sb2FkX21vZHVsZXModXJsMSwgYXR0cmlidXRlcywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICB3aW5kb3cubXdfc29ydGFibGVzX2NyZWF0ZWQgPSBmYWxzZTtcbiAgICAgICAgfSk7XG5cbiAgICB9LFxuICAgIC8qKlxuICAgICAqIERlbGV0ZXMgZWxlbWVudCBieSBpZCBvciBzZWxlY3RvclxuICAgICAqXG4gICAgICogQG1ldGhvZCBtdy5lZGl0LmRlbGV0ZV9lbGVtZW50KGlkb2JqKVxuICAgICAqIEBwYXJhbSBFbGVtZW50IGlkIG9yIHNlbGVjdG9yXG4gICAgICovXG4gICAgZGVsZXRlX2VsZW1lbnQ6IGZ1bmN0aW9uKGlkb2JqLCBjKSB7XG4gICAgICAgIG13LnRvb2xzLmNvbmZpcm0obXcuc2V0dGluZ3Muc29ydGhhbmRsZV9kZWxldGVfY29uZmlybWF0aW9uX3RleHQsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuJChpZG9iaik7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShpZG9iaik7XG4gICAgICAgICAgICB2YXIgZWxwYXJlbnQgPSBlbC5wYXJlbnQoKVxuXG4gICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgdGFyZ2V0OiBlbHBhcmVudFswXSxcbiAgICAgICAgICAgICAgICB2YWx1ZTogZWxwYXJlbnQuaHRtbCgpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGVsLmFkZENsYXNzKFwibXdmYWRlb3V0XCIpO1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBtdy4kKGlkb2JqKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBtdy5oYW5kbGVNb2R1bGUuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG13LiQobXcuaGFuZGxlTW9kdWxlKS5yZW1vdmVDbGFzcygnbXctYWN0aXZlLWl0ZW0nKTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnModHJ1ZSk7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IGVscGFyZW50WzBdLFxuICAgICAgICAgICAgICAgICAgICB2YWx1ZTogZWxwYXJlbnQuaHRtbCgpXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgaWYoYyl7XG4gICAgICAgICAgICAgICAgICAgIGMuY2FsbCgpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSwgMzAwKTtcbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIGdyYW1tYXJseUZpeDpmdW5jdGlvbihodG1sKXtcbiAgICAgICAgdmFyIGRhdGEgPSBtdy50b29scy5wYXJzZUh0bWwoaHRtbCkuYm9keTtcbiAgICAgICAgbXcuJChcImdyYW1tYXJseS1idG5cIiwgZGF0YSkucmVtb3ZlKCk7XG4gICAgICAgIG13LiQoXCJncmFtbWFybHktY2FyZFwiLCBkYXRhKS5yZW1vdmUoKTtcbiAgICAgICAgbXcuJChcImcuZ3JfXCIsIGRhdGEpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIG13LiQodGhpcykucmVwbGFjZVdpdGgodGhpcy5pbm5lckhUTUwpO1xuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChcIltkYXRhLWdyYW1tX2lkXVwiLCBkYXRhKS5yZW1vdmVBdHRyKCdkYXRhLWdyYW1tX2lkJyk7XG4gICAgICAgIG13LiQoXCJbZGF0YS1ncmFtbV1cIiwgZGF0YSkucmVtb3ZlQXR0cignZGF0YS1ncmFtbScpO1xuICAgICAgICBtdy4kKFwiW2RhdGEtZ3JhbW1faWRdXCIsIGRhdGEpLnJlbW92ZUF0dHIoJ2RhdGEtZ3JhbW1faWQnKTtcbiAgICAgICAgbXcuJChcImdyYW1tYXJseS1jYXJkXCIsIGRhdGEpLnJlbW92ZSgpO1xuICAgICAgICBtdy4kKFwiZ3JhbW1hcmx5LWlubGluZS1jYXJkc1wiLCBkYXRhKS5yZW1vdmUoKTtcbiAgICAgICAgbXcuJChcImdyYW1tYXJseS1wb3B1cHNcIiwgZGF0YSkucmVtb3ZlKCk7XG4gICAgICAgIG13LiQoXCJncmFtbWFybHktZXh0ZW5zaW9uXCIsIGRhdGEpLnJlbW92ZSgpO1xuICAgICAgICByZXR1cm4gZGF0YS5pbm5lckhUTUw7XG4gICAgfSxcbiAgICBzYXZpbmc6IGZhbHNlLFxuICAgIGNvcmVTYXZlOiBmdW5jdGlvbihkYXRhKSB7XG4gICAgICAgIGlmICghZGF0YSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAkLmVhY2goZGF0YSwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuaHRtbCA9IG13LmRyYWcuZ3JhbW1hcmx5Rml4KHRoaXMuaHRtbClcbiAgICAgICAgfSk7XG4gICAgICAgIG13LmRyYWcuc2F2aW5nID0gdHJ1ZTtcblxuICAgICAgICAvKioqKioqKioqKioqICBTVEFSVCBiYXNlNjQgICoqKioqKioqKioqKi9cbiAgICAgICAgZGF0YSA9IEpTT04uc3RyaW5naWZ5KGRhdGEpO1xuICAgICAgICBkYXRhID0gYnRvYShlbmNvZGVVUklDb21wb25lbnQoZGF0YSkucmVwbGFjZSgvJShbMC05QS1GXXsyfSkvZyxcbiAgICAgICAgICAgIGZ1bmN0aW9uIHRvU29saWRCeXRlcyhtYXRjaCwgcDEpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gU3RyaW5nLmZyb21DaGFyQ29kZSgnMHgnICsgcDEpO1xuICAgICAgICAgICAgfSkpO1xuICAgICAgICBkYXRhID0ge2RhdGFfYmFzZTY0OmRhdGF9O1xuICAgICAgICAvKioqKioqKioqKioqICBFTkQgYmFzZTY0ICAqKioqKioqKioqKiovXG5cbiAgICAgICAgdmFyIHhociA9IG13LmFqYXgoe1xuICAgICAgICAgICAgdHlwZTogJ1BPU1QnLFxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ3NhdmVfZWRpdCcsXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgZGF0YVR5cGU6IFwianNvblwiLFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHNhdmVkX2RhdGEpIHtcbiAgICAgICAgICAgICAgICBpZihzYXZlZF9kYXRhICYmIHNhdmVkX2RhdGEubmV3X3BhZ2VfdXJsICYmICFtdy5kcmFnLkRyYWZ0U2F2aW5nKXtcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy5hc2t1c2VydG9zdGF5ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIHdpbmRvdy5tdy5hc2t1c2VydG9zdGF5ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmICA9IHNhdmVkX2RhdGEubmV3X3BhZ2VfdXJsO1xuXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICB4aHIuYWx3YXlzKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgbXcuZHJhZy5zYXZpbmcgPSBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiB4aHI7XG4gICAgfSxcbiAgICBwYXJzZUNvbnRlbnQ6IGZ1bmN0aW9uKHJvb3QpIHtcbiAgICAgICAgdmFyIHJvb3QgPSByb290IHx8IG13ZC5ib2R5O1xuICAgICAgICB2YXIgZG9jID0gbXcudG9vbHMucGFyc2VIdG1sKHJvb3QuaW5uZXJIVE1MKTtcbiAgICAgICAgbXcuJCgnLmVsZW1lbnQtY3VycmVudCcsIGRvYykucmVtb3ZlQ2xhc3MoJ2VsZW1lbnQtY3VycmVudCcpO1xuICAgICAgICBtdy4kKCcuZWxlbWVudC1hY3RpdmUnLCBkb2MpLnJlbW92ZUNsYXNzKCdlbGVtZW50LWFjdGl2ZScpO1xuICAgICAgICBtdy4kKCcuZGlzYWJsZS1yZXNpemUnLCBkb2MpLnJlbW92ZUNsYXNzKCdkaXNhYmxlLXJlc2l6ZScpO1xuICAgICAgICBtdy4kKCcubXctd2Via2l0LWRyYWctaG92ZXItYmluZGVkJywgZG9jKS5yZW1vdmVDbGFzcygnbXctd2Via2l0LWRyYWctaG92ZXItYmluZGVkJyk7XG4gICAgICAgIG13LiQoJy5tb2R1bGUtY2F0LXRvZ2dsZS1Nb2R1bGVzJywgZG9jKS5yZW1vdmVDbGFzcygnbW9kdWxlLWNhdC10b2dnbGUtTW9kdWxlcycpO1xuICAgICAgICBtdy4kKCcubXctbW9kdWxlLWRyYWctY2xvbmUnLCBkb2MpLnJlbW92ZUNsYXNzKCdtdy1tb2R1bGUtZHJhZy1jbG9uZScpO1xuICAgICAgICBtdy4kKCctbW9kdWxlJywgZG9jKS5yZW1vdmVDbGFzcygnLW1vZHVsZScpO1xuICAgICAgICBtdy4kKCcuZW1wdHktZWxlbWVudCcsIGRvYykucmVtb3ZlKCk7XG4gICAgICAgIG13LiQoJy5lbXB0eS1lbGVtZW50JywgZG9jKS5yZW1vdmUoKTtcbiAgICAgICAgbXcuJCgnLmVkaXQgLnVpLXJlc2l6YWJsZS1oYW5kbGUnLCBkb2MpLnJlbW92ZSgpO1xuICAgICAgICBtdy4kKCdzY3JpcHQnLCBkb2MpLnJlbW92ZSgpO1xuXG4gICAgICAgIC8vdmFyIGRvYyA9IG13LiQoZG9jKS5maW5kKCdzY3JpcHQnKS5yZW1vdmUoKTtcblxuICAgICAgICBtdy50b29scy5jbGFzc05hbWVzcGFjZURlbGV0ZSgnYWxsJywgJ3VpLScsIGRvYywgJ3N0YXJ0cycpO1xuICAgICAgICBtdy4kKFwiW2NvbnRlbnRlZGl0YWJsZV1cIiwgZG9jKS5yZW1vdmVBdHRyKFwiY29udGVudGVkaXRhYmxlXCIpO1xuICAgICAgICB2YXIgYWxsID0gZG9jLnF1ZXJ5U2VsZWN0b3JBbGwoJ1tjb250ZW50ZWRpdGFibGVdJyksXG4gICAgICAgICAgICBsID0gYWxsLmxlbmd0aCxcbiAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgYWxsW2ldLnJlbW92ZUF0dHJpYnV0ZSgnY29udGVudGVkaXRhYmxlJyk7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGFsbCA9IGRvYy5xdWVyeVNlbGVjdG9yQWxsKCcubW9kdWxlJyksXG4gICAgICAgICAgICBsID0gYWxsLmxlbmd0aCxcbiAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgaWYgKGFsbFtpXS5xdWVyeVNlbGVjdG9yKCcuZWRpdCcpID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgYWxsW2ldLmlubmVySFRNTCA9ICcnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiBkb2M7XG4gICAgfSxcbiAgICBodG1sQXR0clZhbGlkYXRlOmZ1bmN0aW9uKGVkaXRzKXtcbiAgICAgICAgdmFyIGZpbmFsID0gW107XG4gICAgICAgICQuZWFjaChlZGl0cywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHZhciBodG1sID0gdGhpcy5vdXRlckhUTUw7XG4gICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC91cmxcXCgmcXVvdDsvZywgXCJ1cmwoJ1wiKTtcbiAgICAgICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL2pwZyZxdW90Oy9nLCBcImpwZydcIik7XG4gICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9qcGVnJnF1b3Q7L2csIFwianBlZydcIik7XG4gICAgICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9wbmcmcXVvdDsvZywgXCJwbmcnXCIpO1xuICAgICAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvZ2lmJnF1b3Q7L2csIFwiZ2lmJ1wiKTtcbiAgICAgICAgICAgIGZpbmFsLnB1c2goJChodG1sKVswXSk7XG4gICAgICAgIH0pXG4gICAgICAgIHJldHVybiBmaW5hbDtcbiAgICB9LFxuICAgIGNvbGxlY3REYXRhOiBmdW5jdGlvbihlZGl0cykge1xuICAgICAgICBtdy4kKGVkaXRzKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBtdy4kKCdtZXRhJywgdGhpcykucmVtb3ZlKCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGVkaXRzID0gdGhpcy5odG1sQXR0clZhbGlkYXRlKGVkaXRzKTtcbiAgICAgICAgdmFyIGwgPSBlZGl0cy5sZW5ndGgsXG4gICAgICAgICAgICBpID0gMCxcbiAgICAgICAgICAgIGhlbHBlciA9IHt9LFxuICAgICAgICAgICAgbWFzdGVyID0ge307XG4gICAgICAgIGlmIChsID4gMCkge1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICBoZWxwZXIuaXRlbSA9IGVkaXRzW2ldO1xuICAgICAgICAgICAgICAgIHZhciByZWwgPSBtdy50b29scy5td2F0dHIoaGVscGVyLml0ZW0sICdyZWwnKTtcbiAgICAgICAgICAgICAgICBpZiAoIXJlbCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKGhlbHBlci5pdGVtKS5yZW1vdmVDbGFzcygnY2hhbmdlZCcpO1xuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5mb3JlYWNoUGFyZW50cyhoZWxwZXIuaXRlbSwgZnVuY3Rpb24obG9vcCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGNscyA9IHRoaXMuY2xhc3NOYW1lO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHJlbCA9IG13LnRvb2xzLm13YXR0cih0aGlzLCAncmVsJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoY2xzLCAnZWRpdCcpICYmIG13LnRvb2xzLmhhc0NsYXNzKGNscywgJ2NoYW5nZWQnKSAmJiAoISFyZWwpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaGVscGVyLml0ZW0gPSB0aGlzO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLnN0b3BMb29wKGxvb3ApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdmFyIHJlbCA9IG13LnRvb2xzLm13YXR0cihoZWxwZXIuaXRlbSwgJ3JlbCcpO1xuICAgICAgICAgICAgICAgIGlmICghcmVsKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBmaWVsZCA9ICEhaGVscGVyLml0ZW0uaWQgPyAnIycraGVscGVyLml0ZW0uaWQgOiAnJztcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS53YXJuKCdTa2lwcGVkIHNhdmU6IC5lZGl0JytmaWVsZCsnIGVsZW1lbnQgZG9lcyBub3QgaGF2ZSByZWwgYXR0cmlidXRlLicpO1xuICAgICAgICAgICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcuJChoZWxwZXIuaXRlbSkucmVtb3ZlQ2xhc3MoJ2NoYW5nZWQgb3JpZ19jaGFuZ2VkJyk7XG4gICAgICAgICAgICAgICAgbXcuJChoZWxwZXIuaXRlbSkucmVtb3ZlQ2xhc3MoJ21vZHVsZS1vdmVyJyk7XG5cbiAgICAgICAgICAgICAgICBtdy4kKCcubW9kdWxlLW92ZXInLCBoZWxwZXIuaXRlbSkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZUNsYXNzKCdtb2R1bGUtb3ZlcicpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIG13LiQoJ1tjbGFzc10nLCBoZWxwZXIuaXRlbSkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgY2xzID0gdGhpcy5nZXRBdHRyaWJ1dGUoXCJjbGFzc1wiKTtcbiAgICAgICAgICAgICAgICAgICAgaWYodHlwZW9mIGNscyA9PT0gJ3N0cmluZycpe1xuICAgICAgICAgICAgICAgICAgICAgICAgY2xzID0gY2xzLnRyaW0oKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZighY2xzKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlQXR0cmlidXRlKFwiY2xhc3NcIik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgY29udGVudCA9IG13Lnd5c2l3eWcuY2xlYW5VbndhbnRlZFRhZ3MoaGVscGVyLml0ZW0pLmlubmVySFRNTDtcbiAgICAgICAgICAgICAgICB2YXIgYXR0cl9vYmogPSB7fTtcbiAgICAgICAgICAgICAgICB2YXIgYXR0cnMgPSBoZWxwZXIuaXRlbS5hdHRyaWJ1dGVzO1xuICAgICAgICAgICAgICAgIGlmIChhdHRycy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBhaSA9IDAsXG4gICAgICAgICAgICAgICAgICAgICAgICBhbCA9IGF0dHJzLmxlbmd0aDtcbiAgICAgICAgICAgICAgICAgICAgZm9yICg7IGFpIDwgYWw7IGFpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGF0dHJfb2JqW2F0dHJzW2FpXS5ub2RlTmFtZV0gPSBhdHRyc1thaV0ubm9kZVZhbHVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciBvYmogPSB7XG4gICAgICAgICAgICAgICAgICAgIGF0dHJpYnV0ZXM6IGF0dHJfb2JqLFxuICAgICAgICAgICAgICAgICAgICBodG1sOiBjb250ZW50XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB2YXIgb2JqZGF0YSA9IFwiZmllbGRfZGF0YV9cIiArIGk7XG4gICAgICAgICAgICAgICAgbWFzdGVyW29iamRhdGFdID0gb2JqO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiBtYXN0ZXI7XG4gICAgfSxcbiAgICBnZXREYXRhOiBmdW5jdGlvbihyb290KSB7XG4gICAgICAgIHZhciBib2R5ID0gbXcuZHJhZy5wYXJzZUNvbnRlbnQocm9vdCkuYm9keSxcbiAgICAgICAgICAgIGVkaXRzID0gYm9keS5xdWVyeVNlbGVjdG9yQWxsKCcuZWRpdC5jaGFuZ2VkJyksXG4gICAgICAgICAgICBkYXRhID0gbXcuZHJhZy5jb2xsZWN0RGF0YShlZGl0cyk7XG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgIH0sXG5cbiAgICBzYXZlRGlzYWJsZWQ6IGZhbHNlLFxuICAgIGRyYWZ0RGlzYWJsZWQ6IGZhbHNlLFxuICAgIHNhdmU6IGZ1bmN0aW9uKGRhdGEsIHN1Y2Nlc3MsIGZhaWwpIHtcbiAgICAgICAgbXcudHJpZ2dlcignYmVmb3JlU2F2ZVN0YXJ0JywgZGF0YSk7XG4gICAgICAgIGlmIChtdy5saXZlZWRpdC5jc3NFZGl0b3IpIHtcbiAgICAgICAgICAgIG13LmxpdmVlZGl0LmNzc0VkaXRvci5wdWJsaXNoSWZDaGFuZ2VkKCk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG13LmRyYWcuc2F2ZURpc2FibGVkKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIGlmKCFkYXRhKXtcbiAgICAgICAgICAgIHZhciBib2R5ID0gbXcuZHJhZy5wYXJzZUNvbnRlbnQoKS5ib2R5LFxuICAgICAgICAgICAgICAgIGVkaXRzID0gYm9keS5xdWVyeVNlbGVjdG9yQWxsKCcuZWRpdC5jaGFuZ2VkJyk7XG4gICAgICAgICAgICBkYXRhID0gbXcuZHJhZy5jb2xsZWN0RGF0YShlZGl0cyk7XG4gICAgICAgIH1cblxuXG5cbiAgICAgICAgaWYgKG13LnRvb2xzLmlzRW1wdHlPYmplY3QoZGF0YSkpIHJldHVybiBmYWxzZTtcblxuICAgICAgICBtdy5fbGl2ZWVkaXREYXRhID0gZGF0YTtcblxuICAgICAgICBtdy50cmlnZ2VyKCdzYXZlU3RhcnQnLCBtdy5fbGl2ZWVkaXREYXRhKTtcblxuICAgICAgICB2YXIgeGhyID0gbXcuZHJhZy5jb3JlU2F2ZShtdy5fbGl2ZWVkaXREYXRhKTtcbiAgICAgICAgeGhyLmVycm9yKGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgIGlmKHhoci5zdGF0dXMgPT0gNDAzKXtcbiAgICAgICAgICAgICAgICB2YXIgbW9kYWwgPSBtdy5kaWFsb2coe1xuICAgICAgICAgICAgICAgICAgICBpZCA6ICdzYXZlX2NvbnRlbnRfZXJyb3JfaWZyYW1lX21vZGFsJyxcbiAgICAgICAgICAgICAgICAgICAgaHRtbDpcIjxpZnJhbWUgaWQ9J3NhdmVfY29udGVudF9lcnJvcl9pZnJhbWUnIHN0eWxlPSdvdmVyZmxvdy14OmhpZGRlbjtvdmVyZmxvdy15OmF1dG87JyBjbGFzcz0nbXctbW9kYWwtZnJhbWUnID48L2lmcmFtZT5cIixcbiAgICAgICAgICAgICAgICAgICAgd2lkdGg6JCh3aW5kb3cpLndpZHRoKCkgLSA5MCxcbiAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiQod2luZG93KS5oZWlnaHQoKSAtIDkwXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBtdy5hc2t1c2VydG9zdGF5ID0gZmFsc2U7XG5cbiAgICAgICAgICAgICAgICBtdy4kKFwiI3NhdmVfY29udGVudF9lcnJvcl9pZnJhbWVcIikucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBkb2MgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2F2ZV9jb250ZW50X2Vycm9yX2lmcmFtZScpLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgICAgIGRvYy5vcGVuKCk7XG4gICAgICAgICAgICAgICAgICAgIGRvYy53cml0ZSh4aHIucmVzcG9uc2VUZXh0KTtcbiAgICAgICAgICAgICAgICAgICAgZG9jLmNsb3NlKCk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzYXZlX2NvbnRlbnRfZXJyb3JfaWZyYW1lX3JlbG9hZHMgPSAwO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZG9jID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3NhdmVfY29udGVudF9lcnJvcl9pZnJhbWUnKS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuXG4gICAgICAgICAgICAgICAgICAgIG13LiQoXCIjc2F2ZV9jb250ZW50X2Vycm9yX2lmcmFtZVwiKS5sb2FkKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBjbG91ZGZsYXJlIGNhcHRjaGFcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpc19jZiA9ICBtdy4kKCcuY2hhbGxlbmdlLWZvcm0nLGRvYykubGVuZ3RoO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2F2ZV9jb250ZW50X2Vycm9yX2lmcmFtZV9yZWxvYWRzKys7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKGlzX2NmICYmIHNhdmVfY29udGVudF9lcnJvcl9pZnJhbWVfcmVsb2FkcyA9PSAyKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmFza3VzZXJ0b3N0YXkgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCgnI3NhdmVfY29udGVudF9lcnJvcl9pZnJhbWVfbW9kYWwnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCAxNTApO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICB4aHIuc3VjY2VzcyhmdW5jdGlvbihzZGF0YSkge1xuICAgICAgICAgICAgbXcuJCgnLmVkaXQuY2hhbmdlZCcpLnJlbW92ZUNsYXNzKCdjaGFuZ2VkJyk7XG4gICAgICAgICAgICBtdy4kKCcub3JpZ19jaGFuZ2VkJykucmVtb3ZlQ2xhc3MoJ29yaWdfY2hhbmdlZCcpO1xuICAgICAgICAgICAgaWYgKG13ZC5xdWVyeVNlbGVjdG9yKCcuZWRpdC5jaGFuZ2VkJykgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLnNhdmUoKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbXcuYXNrdXNlcnRvc3RheSA9IGZhbHNlO1xuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ3NhdmVFbmQnLCBzZGF0YSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihzdWNjZXNzKXtcbiAgICAgICAgICAgICAgICBzdWNjZXNzLmNhbGwoc2RhdGEpXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSk7XG4gICAgICAgIHhoci5mYWlsKGZ1bmN0aW9uKGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93bikge1xuICAgICAgICAgICAgbXcudHJpZ2dlcignc2F2ZUZhaWxlZCcsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duKTtcbiAgICAgICAgICAgIGlmKGZhaWwpe1xuICAgICAgICAgICAgICAgIGZhaWwuY2FsbChzZGF0YSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiB4aHI7XG4gICAgfSxcbiAgICBzYXZlRHJhZnRPbGQ6ICcnLFxuICAgIERyYWZ0U2F2aW5nOiBmYWxzZSxcbiAgICBpbml0RHJhZnQ6IGZhbHNlLFxuICAgIHNhdmVEcmFmdDogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmIChtdy5kcmFnLmRyYWZ0RGlzYWJsZWQpIHJldHVybiBmYWxzZTtcbiAgICAgICAgaWYgKG13LmRyYWcuRHJhZnRTYXZpbmcpIHJldHVybiBmYWxzZTtcbiAgICAgICAgaWYgKCFtdy5kcmFnLmluaXREcmFmdCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICBpZiAobXdkLmJvZHkudGV4dENvbnRlbnQgIT0gbXcuZHJhZy5zYXZlRHJhZnRPbGQpIHtcbiAgICAgICAgICAgIG13LmRyYWcuc2F2ZURyYWZ0T2xkID0gbXdkLmJvZHkudGV4dENvbnRlbnQ7XG4gICAgICAgICAgICB2YXIgYm9keSA9IG13LmRyYWcucGFyc2VDb250ZW50KCkuYm9keSxcbiAgICAgICAgICAgICAgICBlZGl0cyA9IGJvZHkucXVlcnlTZWxlY3RvckFsbCgnLmVkaXQuY2hhbmdlZCcpLFxuICAgICAgICAgICAgICAgIGRhdGEgPSBtdy5kcmFnLmNvbGxlY3REYXRhKGVkaXRzKTtcbiAgICAgICAgICAgIGlmIChtdy50b29scy5pc0VtcHR5T2JqZWN0KGRhdGEpKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICBkYXRhWydpc19kcmFmdCddID0gdHJ1ZTtcbiAgICAgICAgICAgIG13LmRyYWcuRHJhZnRTYXZpbmcgPSB0cnVlO1xuICAgICAgICAgICAgdmFyIHhociA9IG13LmRyYWcuY29yZVNhdmUoZGF0YSk7XG4gICAgICAgICAgICB4aHIuYWx3YXlzKGZ1bmN0aW9uKG1zZykge1xuICAgICAgICAgICAgICAgIG13LmRyYWcuRHJhZnRTYXZpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLmluaXREcmFmdCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ3NhdmVEcmFmdENvbXBsZXRlZCcpO1xuXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cbn1cbiIsIm13LmxpdmVlZGl0LmVkaXRGaWVsZHMgPSB7XHJcbiAgICBoYW5kbGVLZXlkb3duOiBmdW5jdGlvbigpIHtcclxuICAgICAgICBtdy4kKCcuZWRpdCcpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24oZSl7XHJcbiAgICAgICAgICAgIHZhciBpc3RhYiA9IChlLndoaWNoIHx8IGUua2V5Q29kZSkgPT09IDksXHJcbiAgICAgICAgICAgICAgICBpc1NoaWZ0VGFiID0gaXN0YWIgJiYgZS5zaGlmdEtleSxcclxuICAgICAgICAgICAgICAgIHRhYk9ubHkgPSBpc3RhYiAmJiAhZS5zaGlmdEtleSxcclxuICAgICAgICAgICAgICAgIHRhcmdldDtcclxuXHJcbiAgICAgICAgICAgIGlmKGlzdGFiKXtcclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldCA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihnZXRTZWxlY3Rpb24oKS5mb2N1c05vZGUpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmKHRhYk9ubHkpe1xyXG4gICAgICAgICAgICAgICAgaWYodGFyZ2V0Lm5vZGVOYW1lID09PSAnTEknKXtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgcGFyZW50ID0gdGFyZ2V0LnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYocGFyZW50LmNoaWxkcmVuWzBdICE9PSB0YXJnZXQpe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgcHJldiA9IHRhcmdldC5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KHBhcmVudC5ub2RlTmFtZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHVsLmFwcGVuZENoaWxkKHRhcmdldCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHByZXYuYXBwZW5kQ2hpbGQodWwpXHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgZWxzZSBpZih0YXJnZXQubm9kZU5hbWUgPT09ICdURCcgfHwgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhUYWcodGFyZ2V0LCAndGQnKSl7XHJcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0ID0gdGFyZ2V0Lm5vZGVOYW1lID09PSAnVEQnID8gdGFyZ2V0IDogbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKHRhcmdldCwgJ3RkJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgbmV4dHRkID0gdGFyZ2V0Lm5leHRFbGVtZW50U2libGluZztcclxuICAgICAgICAgICAgICAgICAgICBpZighIW5leHR0ZCl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY3Vyc29yVG9FbGVtZW50KG5leHR0ZCwgJ3N0YXJ0Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGVsc2V7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBuZXh0Um93ID0gdGFyZ2V0LnBhcmVudE5vZGUubmV4dEVsZW1lbnRTaWJsaW5nO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZighIW5leHRSb3cpe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jdXJzb3JUb0VsZW1lbnQobmV4dFJvdy5xdWVyeVNlbGVjdG9yKCd0ZCcpLCAnc3RhcnQnKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2V7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbCgnJm5ic3A7Jm5ic3A7Jyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2UgaWYoaXNTaGlmdFRhYil7XHJcbiAgICAgICAgICAgICAgICBpZih0YXJnZXQubm9kZU5hbWUgPT09ICdMSScpe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBwYXJlbnQgPSB0YXJnZXQucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgaXNTdWIgPSBwYXJlbnQucGFyZW50Tm9kZS5ub2RlTmFtZSA9PT0gJ0xJJztcclxuICAgICAgICAgICAgICAgICAgICBpZihpc1N1Yil7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBzcGxpdCA9IG13Lnd5c2l3eWcubGlzdFNwbGl0KHBhcmVudCwgbXcuJCgnbGknLCBwYXJlbnQpLmluZGV4KHRhcmdldCkpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHBhcmVudExpID0gcGFyZW50LnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQocGFyZW50TGkpLmFmdGVyKHNwbGl0Lm1pZGRsZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCEhc3BsaXQudG9wKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQocGFyZW50TGkpLmFwcGVuZChzcGxpdC50b3ApO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCEhc3BsaXQuYm90dG9tKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoc3BsaXQubWlkZGxlKS5hcHBlbmQoc3BsaXQuYm90dG9tKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChwYXJlbnQpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2UgaWYodGFyZ2V0Lm5vZGVOYW1lID09PSAnVEQnIHx8IG13LnRvb2xzLmhhc1BhcmVudHNXaXRoVGFnKHRhcmdldCwgJ3RkJykpe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciB0YXJnZXQgPSB0YXJnZXQubm9kZU5hbWUgPT09ICdURCcgPyB0YXJnZXQgOiBtdy50b29scy5maXJzdFBhcmVudFdpdGhUYWcodGFyZ2V0LCAndGQnKTtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgbmV4dHRkID0gdGFyZ2V0LnByZXZpb3VzRWxlbWVudFNpYmxpbmc7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYoISFuZXh0dGQpe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmN1cnNvclRvRWxlbWVudChuZXh0dGQsICdzdGFydCcpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgbmV4dFJvdyA9IHRhcmdldC5wYXJlbnROb2RlLnByZXZpb3VzRWxlbWVudFNpYmxpbmc7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKCEhbmV4dFJvdyl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmN1cnNvclRvRWxlbWVudChuZXh0Um93LnF1ZXJ5U2VsZWN0b3IoJ3RkOmxhc3QtY2hpbGQnKSwgJ3N0YXJ0Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciByYW5nZSA9IGdldFNlbGVjdGlvbigpLmdldFJhbmdlQXQoMCk7XHJcbiAgICAgICAgICAgICAgICAgICAgY2xvbmUgPSByYW5nZS5jbG9uZVJhbmdlKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgY2xvbmUuc2V0U3RhcnQocmFuZ2Uuc3RhcnRDb250YWluZXIsIHJhbmdlLnN0YXJ0T2Zmc2V0IC0gMik7XHJcbiAgICAgICAgICAgICAgICAgICAgY2xvbmUuc2V0RW5kKHJhbmdlLnN0YXJ0Q29udGFpbmVyLCByYW5nZS5zdGFydE9mZnNldCk7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIG52ID0gY2xvbmUuY2xvbmVDb250ZW50cygpLmZpcnN0Q2hpbGQubm9kZVZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBudmNoZWNrID0gbnYucmVwbGFjZSgvXFxzL2csJycpO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmKCBudmNoZWNrID09PSAnJyApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjbG9uZS5kZWxldGVDb250ZW50cygpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG59XHJcbiIsIm13LmxpdmVlZGl0LmVkaXRvcnMgPSB7XHJcbiAgcHJlcGFyZTogZnVuY3Rpb24oKSB7XHJcbiAgICAgIG13LiQod2luZG93KS5vbihcIm1vdXNldXAgdG91Y2hlbmRcIiwgZnVuY3Rpb24oZSkge1xyXG5cclxuICAgICAgICAgIHZhciBzZWwgPSBnZXRTZWxlY3Rpb24oKTtcclxuICAgICAgICAgIGlmIChzZWwucmFuZ2VDb3VudCA+IDApIHtcclxuICAgICAgICAgICAgICB2YXIgcmFuZ2UgPSBzZWwuZ2V0UmFuZ2VBdCgwKSxcclxuICAgICAgICAgICAgICAgICAgY29tbW9uID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKTtcclxuXHJcbiAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNvbW1vbiwgJ2VkaXQnKSB8fCBtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGNvbW1vbiwgJ2VkaXQnKSkge1xyXG4gICAgICAgICAgICAgICAgICB2YXIgbm9kcm9wX3N0YXRlID0gIW13LnRvb2xzLmhhc0NsYXNzKGNvbW1vbiwgJ25vZHJvcCcpICYmICFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGNvbW1vbiwgJ25vZHJvcCcpO1xyXG4gICAgICAgICAgICAgICAgICBpZiAobm9kcm9wX3N0YXRlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmVuYWJsZUVkaXRvcnMoKTtcclxuICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZGlzYWJsZUVkaXRvcnMoKTtcclxuICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZGlzYWJsZUVkaXRvcnMoKTtcclxuICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgc2VsID0gd2luZG93LmdldFNlbGVjdGlvbigpO1xyXG4gICAgICAgICAgaWYgKHNlbC5yYW5nZUNvdW50ID4gMCkge1xyXG4gICAgICAgICAgICAgIHZhciByID0gc2VsLmdldFJhbmdlQXQoMCk7XHJcbiAgICAgICAgICAgICAgdmFyIGNhYyA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihyLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKTtcclxuICAgICAgICAgIH1cclxuICAgICAgICAgIGlmIChtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChjYWMsIFsnZWRpdCcsICdtdy1hZG1pbi1lZGl0b3ItYXJlYSddKSAmJiAoc2VsLnJhbmdlQ291bnQgPiAwICYmICFzZWwuZ2V0UmFuZ2VBdCgwKS5jb2xsYXBzZWQpKSB7XHJcblxyXG4gICAgICAgICAgICAgIGlmICgkLmNvbnRhaW5zKGUudGFyZ2V0LCBjYWMpIHx8ICQuY29udGFpbnMoY2FjLCBlLnRhcmdldCkgfHwgY2FjID09PSBlLnRhcmdldCkge1xyXG4gICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgdmFyIGVwID0gbXcuZXZlbnQucGFnZShlKTtcclxuICAgICAgICAgICAgICAgICAgICAgIGlmIChjYWMuaXNDb250ZW50RWRpdGFibGUgJiYgIXNlbC5pc0NvbGxhcHNlZCAmJiAhbXcudG9vbHMuaGFzQ2xhc3MoY2FjLCAncGxhaW4tdGV4dCcpICYmICFtdy50b29scy5oYXNDbGFzcyhjYWMsICdzYWZlLWVsZW1lbnQnKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2Yod2luZG93LmdldFNlbGVjdGlvbigpLmdldFJhbmdlQXQoMCkuZ2V0Q2xpZW50UmVjdHMoKVswXSkgPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvckNhbmNlbGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHRvcCA9IGVwLnkgLSBtdy5zbWFsbEVkaXRvci5oZWlnaHQoKSAtIHdpbmRvdy5nZXRTZWxlY3Rpb24oKS5nZXRSYW5nZUF0KDApLmdldENsaWVudFJlY3RzKClbMF0uaGVpZ2h0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yLmNzcyh7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZpc2liaWxpdHk6IFwidmlzaWJsZVwiLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcGFjaXR5OiAwLjcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRvcDogKHRvcCA+IDU1ID8gdG9wIDogNTUpLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZWZ0OiBlcC54ICsgbXcuc21hbGxFZGl0b3Iud2lkdGgoKSA8IG13LiQod2luZG93KS53aWR0aCgpID8gZXAueCA6ICgkKHdpbmRvdykud2lkdGgoKSAtIG13LnNtYWxsRWRpdG9yLndpZHRoKCkgLSA1KVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3JDYW5jZWxlZCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmlzaWJpbGl0eTogXCJoaWRkZW5cIlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgfSwgMzMpO1xyXG4gICAgICAgICAgICAgIH1cclxuICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgaWYgKG13LnNtYWxsRWRpdG9yICYmICFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGUudGFyZ2V0LCAnbXdfc21hbGxfZWRpdG9yJykpIHtcclxuICAgICAgICAgICAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yQ2FuY2VsZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICB2aXNpYmlsaXR5OiBcImhpZGRlblwiXHJcbiAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICB9XHJcbiAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICAgIGlmICh3aW5kb3cuZ2V0U2VsZWN0aW9uKCkucmFuZ2Vjb3VudCA+IDAgJiYgd2luZG93LmdldFNlbGVjdGlvbigpLmdldFJhbmdlQXQoMCkuY29sbGFwc2VkKSB7XHJcbiAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YobXcuc21hbGxFZGl0b3IpICE9ICd1bmRlZmluZWQnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvckNhbmNlbGVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yLmNzcyh7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgdmlzaWJpbGl0eTogXCJoaWRkZW5cIlxyXG4gICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICB9LCAzOSk7XHJcbiAgICAgIH0pO1xyXG4gICAgICBtdy5zbWFsbEVkaXRvck9mZiA9IDE1MDtcclxuXHJcbiAgICAgIG13LiQod2luZG93KS5vbihcIm1vdXNlbW92ZSB0b3VjaG1vdmUgdG91Y2hzdGFydFwiLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICBpZiAoISFtdy5zbWFsbEVkaXRvciAmJiAhbXcuaXNEcmFnICYmICFtdy5zbWFsbEVkaXRvckNhbmNlbGVkICYmICFtdy5zbWFsbEVkaXRvci5oYXNDbGFzcyhcImVkaXRvcl9ob3ZlclwiKSkge1xyXG4gICAgICAgICAgICAgIHZhciBvZmYgPSBtdy5zbWFsbEVkaXRvci5vZmZzZXQoKTtcclxuICAgICAgICAgICAgICB2YXIgZXAgPSBtdy5ldmVudC5wYWdlKGUpO1xyXG4gICAgICAgICAgICAgIGlmICh0eXBlb2Ygb2ZmICE9PSAndW5kZWZpbmVkJykge1xyXG4gICAgICAgICAgICAgICAgICBpZiAoXHJcbiAgICAgICAgICAgICAgICAgICAgICAoKGVwLnggLSBtdy5zbWFsbEVkaXRvck9mZikgPiAob2ZmLmxlZnQgKyBtdy5zbWFsbEVkaXRvci53aWR0aCgpKSlcclxuICAgICAgICAgICAgICAgICAgICAgIHx8ICgoZXAueSAtIG13LnNtYWxsRWRpdG9yT2ZmKSA+IChvZmYudG9wICsgbXcuc21hbGxFZGl0b3IuaGVpZ2h0KCkpKVxyXG4gICAgICAgICAgICAgICAgICAgICAgfHwgKChlcC54ICsgbXcuc21hbGxFZGl0b3JPZmYpIDwgKG9mZi5sZWZ0KSkgfHwgKChlcC55ICsgbXcuc21hbGxFZGl0b3JPZmYpIDwgKG9mZi50b3ApKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBtdy5zbWFsbEVkaXRvciAhPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvci5jc3MoXCJ2aXNpYmlsaXR5XCIsIFwiaGlkZGVuXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yQ2FuY2VsZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgfVxyXG4gICAgICB9KTtcclxuICAgICAgbXcuJCh3aW5kb3cpLm9uKFwic2Nyb2xsXCIsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgIGlmICh0eXBlb2YobXcuc21hbGxFZGl0b3IpICE9PSBcInVuZGVmaW5lZFwiKSB7XHJcbiAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKFwidmlzaWJpbGl0eVwiLCBcImhpZGRlblwiKTtcclxuICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvckNhbmNlbGVkID0gdHJ1ZTtcclxuICAgICAgICAgIH1cclxuICAgICAgfSk7XHJcbiAgICAgIG13LiQoXCIjbGl2ZV9lZGl0X3Rvb2xiYXIsICNtd19zbWFsbF9lZGl0b3JcIikub24oXCJtb3VzZWRvd24gdG91Y2hzdGFydFwiLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICBtdy4kKFwiLnd5c2l3eWdfZXh0ZXJuYWxcIikuZW1wdHkoKTtcclxuICAgICAgICAgIGlmIChlLnRhcmdldC5ub2RlTmFtZSAhPT0gJ0lOUFVUJyAmJiBlLnRhcmdldC5ub2RlTmFtZSAhPT0gJ1NFTEVDVCcgJiYgZS50YXJnZXQubm9kZU5hbWUgIT09ICdPUFRJT04nICYmIGUudGFyZ2V0Lm5vZGVOYW1lICE9PSAnQ0hFQ0tCT1gnKSB7XHJcbiAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgICAgaWYgKHR5cGVvZihtdy5zbWFsbEVkaXRvcikgIT09IFwidW5kZWZpbmVkXCIpIHtcclxuICAgICAgICAgICAgICBpZiAoIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZS50YXJnZXQsICdtd19zbWFsbF9lZGl0b3InKSkge1xyXG4gICAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvci5jc3MoXCJ2aXNpYmlsaXR5XCIsIFwiaGlkZGVuXCIpO1xyXG4gICAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvckNhbmNlbGVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICB9XHJcbiAgICAgIH0pO1xyXG4gIH1cclxufTtcclxuIiwibXcuQWZ0ZXJEcm9wID0gZnVuY3Rpb24oKXtcclxuXHJcblxyXG5cclxuICAgIHRoaXMubG9hZE5ld01vZHVsZXMgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIG13LnBhdXNlU2F2ZSA9IHRydWU7XHJcbiAgICAgICAgdmFyIG5lZWRfcmVfaW5pdCA9IGZhbHNlO1xyXG4gICAgICAgIHZhciBhbGwgPSBtdy4kKFwiLmVkaXQgLm1vZHVsZS1pdGVtXCIpLCBjb3VudCA9IDA7XHJcbiAgICAgICAgYWxsLmVhY2goZnVuY3Rpb24oYykge1xyXG4gICAgICAgICAgICAoZnVuY3Rpb24gKGVsKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50ID0gZWwucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgICAgIHZhciB4aHIgPSBtdy5fKHtcclxuICAgICAgICAgICAgICAgICAgICBzZWxlY3RvcjogZWwsXHJcbiAgICAgICAgICAgICAgICAgICAgZG9uZTogZnVuY3Rpb24obW9kdWxlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcuZmFuY3luYXRlTG9hZGluZyhtb2R1bGUpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5wYXVzZVNhdmUgPSBmYWxzZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbml0X2VkaXRhYmxlcygpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZihtdy5saXZlRWRpdERvbVRyZWUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0RG9tVHJlZS5yZWZyZXNoKHBhcmVudCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdERvbVRyZWUuc2VsZWN0KHBhcmVudCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgICAgICBmYWlsOmZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLmVycm9yKCdFcnJvciBsb2FkaW5nIG1vZHVsZS4nKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9LCB0cnVlKTtcclxuICAgICAgICAgICAgICAgaWYoeGhyKSB7XHJcbiAgICAgICAgICAgICAgICAgICB4aHIuYWx3YXlzKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICBjb3VudCsrO1xyXG4gICAgICAgICAgICAgICAgICAgICAgIGlmKGFsbC5sZW5ndGggPT09IGNvdW50KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWdDdXJyZW50ID0gbnVsbDtcclxuICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgIGNvdW50Kys7XHJcbiAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBuZWVkX3JlX2luaXQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9KSh0aGlzKTtcclxuICAgICAgICB9KTtcclxuICAgICAgICBpZiAobXcuaGF2ZV9uZXdfaXRlbXMgPT09IHRydWUpIHtcclxuICAgICAgICAgICAgbmVlZF9yZV9pbml0ID0gdHJ1ZTtcclxuICAgICAgICB9XHJcbiAgICAgICAgaWYgKG5lZWRfcmVfaW5pdCA9PT0gdHJ1ZSkge1xyXG4gICAgICAgICAgICBpZiAoIW13LmlzRHJhZykge1xyXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodGhpcyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgbXcuaGF2ZV9uZXdfaXRlbXMgPSBmYWxzZTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5fX3RpbWVJbml0ID0gbnVsbDtcclxuXHJcbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XHJcbiAgICAgICAgaWYoc2NvcGUuX190aW1lSW5pdCl7XHJcbiAgICAgICAgICAgY2xlYXJUaW1lb3V0KHNjb3BlLl9fdGltZUluaXQpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBzY29wZS5fX3RpbWVJbml0ID0gc2V0VGltZW91dChmdW5jdGlvbigpe1xyXG5cclxuICAgICAgICAgICAgbXcuJChcIi5tdy1kcmFnLWN1cnJlbnQtYm90dG9tLCAubXctZHJhZy1jdXJyZW50LXRvcFwiKS5yZW1vdmVDbGFzcygnbXctZHJhZy1jdXJyZW50LWJvdHRvbSBtdy1kcmFnLWN1cnJlbnQtdG9wJyk7XHJcbiAgICAgICAgICAgIG13LiQoXCIuY3VycmVudERyYWdNb3VzZU92ZXJcIikucmVtb3ZlQ2xhc3MoJ2N1cnJlbnREcmFnTW91c2VPdmVyJyk7XHJcblxyXG4gICAgICAgICAgICBtdy4kKFwiLm13X2RyYWdfY3VycmVudFwiKS5lYWNoKGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZUNsYXNzKCdtd19kcmFnX2N1cnJlbnQnKS5jc3Moe1xyXG4gICAgICAgICAgICAgICAgICAgIHZpc2liaWxpdHk6J3Zpc2libGUnLFxyXG4gICAgICAgICAgICAgICAgICAgIG9wYWNpdHk6JydcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgbXcuJChcIi5jdXJyZW50RHJhZ01vdXNlT3ZlclwiKS5yZW1vdmVDbGFzcygnY3VycmVudERyYWdNb3VzZU92ZXInKVxyXG4gICAgICAgICAgICBtdy4kKFwiLm13LWVtcHR5XCIpLm5vdCgnOmVtcHR5JykucmVtb3ZlQ2xhc3MoJ213LWVtcHR5Jyk7XHJcbiAgICAgICAgICAgIHNjb3BlLmxvYWROZXdNb2R1bGVzKClcclxuICAgICAgICAgICAgbXcuZHJvcGFibGUuaGlkZSgpLnJlbW92ZUNsYXNzKCdtd19kcm9wYWJsZV9vbmxlYXZlZWRpdCcpO1xyXG5cclxuICAgICAgICB9LCA3OClcclxuICAgIH1cclxuXHJcblxyXG59XHJcblxyXG5cclxuLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKipcclxuXHJcblxyXG4gICAgICAgIE9wdGlvbnM6IE9iamVjdCBsaXRlcmFsXHJcblxyXG4gICAgICAgIERlZmF1bHQ6IHtcclxuICAgICAgICAgICAgY2xhc3Nlczp7XHJcbiAgICAgICAgICAgICAgICBlZGl0OidlZGl0JyxcclxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6J2VsZW1lbnQnLFxyXG4gICAgICAgICAgICAgICAgbW9kdWxlOidtb2R1bGUnLFxyXG4gICAgICAgICAgICAgICAgbm9Ecm9wOidub2Ryb3AnLCAvLyAtIGRpc2FibGUgZHJvcFxyXG4gICAgICAgICAgICAgICAgYWxsb3dEcm9wOidhbGxvdy1kcm9wJyAvLy0gZW5hYmxlIGRyb3AgaW4gLm5vZHJvcFxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuXHJcblxyXG4gICAgbXcuYW5hbGl6ZXIgPSBuZXcgbXcuRWxlbWVudEFuYWxpemVyKE9wdGlvbnMpO1xyXG5cclxuXHJcblxyXG5cclxuKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cclxuXHJcbm13LkVsZW1lbnRBbmFseXplciA9IGZ1bmN0aW9uKG9wdGlvbnMpe1xyXG5cclxuXHJcblxyXG4gICAgdGhpcy5kYXRhID0ge1xyXG4gICAgICAgIGRyb3BhYmxlQWN0aW9uOm51bGwsXHJcbiAgICAgICAgY3VycmVudEdyYWJiZWQ6bnVsbCxcclxuICAgICAgICB0YXJnZXQ6bnVsbCxcclxuICAgICAgICBkcm9wYWJsZVBvc2l0aW9uOm51bGxcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5kYXRhUmVzZXQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMuZGF0YSA9IHtcclxuICAgICAgICAgICAgZHJvcGFibGVBY3Rpb246bnVsbCxcclxuICAgICAgICAgICAgY3VycmVudEdyYWJiZWQ6bnVsbCxcclxuICAgICAgICAgICAgdGFyZ2V0Om51bGwsXHJcbiAgICAgICAgICAgIGRyb3BhYmxlUG9zaXRpb246bnVsbFxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5vcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcclxuICAgIHRoaXMuZGVmYXVsdHMgPSB7XHJcbiAgICAgICAgY2xhc3Nlczp7XHJcbiAgICAgICAgICAgIGVkaXQ6ICdlZGl0JyxcclxuICAgICAgICAgICAgZWxlbWVudDogJ2VsZW1lbnQnLFxyXG4gICAgICAgICAgICBtb2R1bGU6ICdtb2R1bGUnLFxyXG4gICAgICAgICAgICBub0Ryb3A6ICdub2Ryb3AnLFxyXG4gICAgICAgICAgICBhbGxvd0Ryb3A6ICdhbGxvdy1kcm9wJyxcclxuICAgICAgICAgICAgZW1wdHlFbGVtZW50OiAnbXctZW1wdHknLFxyXG4gICAgICAgICAgICB6b25lOiAnbXctem9uZSdcclxuICAgICAgICB9LFxyXG4gICAgICAgIHJvd3M6Wydtdy1yb3cnLCAnbXctdWktcm93JywgJ3JvdyddLFxyXG4gICAgICAgIGNvbHVtbnM6Wydtdy1jb2wnLCAnbXctdWktY29sJywgJ2NvbCcsICdjb2x1bW4nLCAnY29sdW1ucyddLFxyXG4gICAgICAgIGNvbHVtbk1hdGNoZXM6J1tjbGFzcyo9XCJjb2wtXCJdJyxcclxuICAgICAgICByb3dNYXRjaGVzOidbY2xhc3MqPVwicm93LVwiXScsXHJcbiAgICB9O1xyXG4gICAgdGhpcy5zZXR0aW5ncyA9ICQuZXh0ZW5kKHt9LCB0aGlzLm9wdGlvbnMsIHRoaXMuZGVmYXVsdHMpO1xyXG5cclxuICAgIHRoaXMucHJlcGFyZSA9IGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdGhpcy5jbHMgPSB0aGlzLnNldHRpbmdzLmNsYXNzZXM7XHJcbiAgICAgICAgdGhpcy5pbml0Q1NTKCk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuaW5pdENTUyA9IGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdmFyIGNzcyA9ICdib2R5LmRyYWdTdGFydCAuJyt0aGlzLmNscy5ub0Ryb3ArJ3snXHJcbiAgICAgICAgICAgICsncG9pbnRlci1ldmVudHM6IG5vbmU7J1xyXG4gICAgICAgICsnfSdcclxuICAgICAgICArJ2JvZHkuZHJhZ1N0YXJ0IC4nK3RoaXMuY2xzLmFsbG93RHJvcCsneydcclxuICAgICAgICAgICAgKydwb2ludGVyLWV2ZW50czogYWxsOydcclxuICAgICAgICArJ30nO1xyXG5cclxuICAgICAgICB2YXIgc3R5bGUgPSBtd2QuY3JlYXRlRWxlbWVudCgnc3R5bGUnKTtcclxuICAgICAgICBtd2QuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2hlYWQnKVswXS5hcHBlbmRDaGlsZChzdHlsZSk7XHJcbiAgICAgICAgc3R5bGUuaW5uZXJIVE1MID0gY3NzO1xyXG4gICAgfTtcclxuXHJcblxyXG4gICAgdGhpcy5faXNFZGl0TGlrZSA9IGZ1bmN0aW9uKG5vZGUpe1xyXG4gICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgdmFyIGNhc2UxID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChub2RlLCBbdGhpcy5jbHMuZWRpdCx0aGlzLmNscy5tb2R1bGVdKTtcclxuICAgICAgICB2YXIgY2FzZTIgPSBtdy50b29scy5oYXNDbGFzcyhub2RlLCAnbW9kdWxlJykgJiYgbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChub2RlLnBhcmVudE5vZGUsIFt0aGlzLmNscy5lZGl0LHRoaXMuY2xzLm1vZHVsZV0pO1xyXG4gICAgICAgIHZhciBlZGl0ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKG5vZGUsIHRoaXMuY2xzLmVkaXQpO1xyXG4gICAgICAgIHJldHVybiAoY2FzZTEgfHwgY2FzZTIpICYmICFtdy50b29scy5oYXNDbGFzcyhlZGl0LCB0aGlzLmNscy5ub0Ryb3ApO1xyXG4gICAgfTtcclxuICAgIHRoaXMuX2NhbkRyb3AgPSBmdW5jdGlvbihub2RlKSB7XHJcbiAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5kYXRhLnRhcmdldDtcclxuICAgICAgICByZXR1cm4gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdE9yTm9uZShub2RlLCBbdGhpcy5jbHMuYWxsb3dEcm9wLCB0aGlzLmNscy5ub0Ryb3BdKTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5fbGF5b3V0SW5MYXlvdXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBpZiAoIXRoaXMuZGF0YS5jdXJyZW50R3JhYmJlZCB8fCAhbXdkLmJvZHkuY29udGFpbnModGhpcy5kYXRhLmN1cnJlbnRHcmFiYmVkKSkge1xyXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciBjdXJyZW50R3JhYmJlZElzTGF5b3V0ID0gKHRoaXMuZGF0YS5jdXJyZW50R3JhYmJlZC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbW9kdWxlLW5hbWUnKSA9PT0gJ2xheW91dHMnIHx8IG13LmRyYWdDdXJyZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10eXBlJykgPT09ICdsYXlvdXRzJyk7XHJcbiAgICAgICAgdmFyIHRhcmdldElzTGF5b3V0ID0gbXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQodGhpcy5kYXRhLnRhcmdldCwgWydbZGF0YS1tb2R1bGUtbmFtZT1cImxheW91dHNcIl0nLCAnW2RhdGEtdHlwZT1cImxheW91dHNcIl0nXSk7XHJcbiAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgICAgdGFyZ2V0OnRhcmdldElzTGF5b3V0LFxyXG4gICAgICAgICAgICByZXN1bHQ6Y3VycmVudEdyYWJiZWRJc0xheW91dCAmJiAhIXRhcmdldElzTGF5b3V0XHJcbiAgICAgICAgfTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5jYW5Ecm9wID0gZnVuY3Rpb24obm9kZSl7XHJcbiAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5kYXRhLnRhcmdldDtcclxuICAgICAgICB2YXIgY2FuID0gKHRoaXMuX2lzRWRpdExpa2Uobm9kZSkgJiYgdGhpcy5fY2FuRHJvcChub2RlKSAmJiAhdGhpcy5fbGF5b3V0SW5MYXlvdXQoKS5yZXN1bHQpO1xyXG4gICAgICAgIHJldHVybiBjYW47XHJcbiAgICB9O1xyXG5cclxuXHJcblxyXG4gICAgdGhpcy5hbmFsaXplUG9zaXRpb24gPSBmdW5jdGlvbihldmVudCwgbm9kZSl7XHJcbiAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5kYXRhLnRhcmdldDtcclxuICAgICAgICB2YXIgaGVpZ2h0ID0gbm9kZS5vZmZzZXRIZWlnaHQsXHJcbiAgICAgICAgICAgIG9mZnNldCA9IG13LiQobm9kZSkub2Zmc2V0KCk7XHJcbiAgICAgICAgaWYgKG13LmV2ZW50LnBhZ2UoZXZlbnQpLnkgPiBvZmZzZXQudG9wICsgKGhlaWdodCAvIDIpKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZGF0YS5kcm9wYWJsZVBvc2l0aW9uID0gICdib3R0b20nO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHRoaXMuZGF0YS5kcm9wYWJsZVBvc2l0aW9uID0gICd0b3AnO1xyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5hbmFsaXplQWN0aW9uT2ZFbGVtZW50ID0gZnVuY3Rpb24obm9kZSwgcG9zKXtcclxuICAgICAgICBub2RlID0gbm9kZSB8fCB0aGlzLmRhdGEudGFyZ2V0O1xyXG4gICAgICAgIHBvcyA9IG5vZGUgfHwgdGhpcy5kYXRhLmRyb3BhYmxlUG9zaXRpb247XHJcbiAgICB9O1xyXG4gICAgdGhpcy5hZnRlckFjdGlvbiA9IGZ1bmN0aW9uKG5vZGUsIHBvcyl7XHJcbiAgICAgICAgaWYoIXRoaXMuX2FmdGVyQWN0aW9uKXtcclxuICAgICAgICAgICAgdGhpcy5fYWZ0ZXJBY3Rpb24gPSBuZXcgbXcuQWZ0ZXJEcm9wKCk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB0aGlzLl9hZnRlckFjdGlvbi5pbml0KCk7XHJcblxyXG4gICAgfTtcclxuICAgIHRoaXMuZHJvcGFibGVIaWRlID0gZnVuY3Rpb24oKXtcclxuXHJcbiAgICB9O1xyXG4gICAgdGhpcy5hbmFsaXplQWN0aW9uID0gZnVuY3Rpb24obm9kZSwgcG9zKXtcclxuICAgICAgICBub2RlID0gbm9kZSB8fCB0aGlzLmRhdGEudGFyZ2V0O1xyXG4gICAgICAgIHBvcyA9IHBvcyB8fCB0aGlzLmRhdGEuZHJvcGFibGVQb3NpdGlvbjtcclxuICAgICAgICBpZih0aGlzLmhlbHBlcnMuaXNFbXB0eSgpKXtcclxuICAgICAgICAgICAgdGhpcy5kYXRhLmRyb3BhYmxlQWN0aW9uID0gJ2FwcGVuZCc7XHJcbiAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIGFjdGlvbnMgPSAge1xyXG4gICAgICAgICAgICBBcm91bmQ6e1xyXG4gICAgICAgICAgICAgICAgdG9wOidiZWZvcmUnLFxyXG4gICAgICAgICAgICAgICAgYm90dG9tOidhZnRlcidcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgSW5zaWRlOntcclxuICAgICAgICAgICAgICAgdG9wOidwcmVwZW5kJyxcclxuICAgICAgICAgICAgICAgYm90dG9tOidhcHBlbmQnXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBpZighcG9zKXtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuXHJcblxyXG5cclxuICAgICAgICBpZihtdy50b29scy5oYXNDbGFzcyhub2RlLCAnYWxsb3ctZHJvcCcpKXtcclxuICAgICAgICAgICAgdGhpcy5kYXRhLmRyb3BhYmxlQWN0aW9uID0gYWN0aW9ucy5JbnNpZGVbcG9zXTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZih0aGlzLmhlbHBlcnMuaXNFbGVtZW50KCkpe1xyXG4gICAgICAgICAgICB0aGlzLmRhdGEuZHJvcGFibGVBY3Rpb24gPSBhY3Rpb25zLkFyb3VuZFtwb3NdO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIGlmKHRoaXMuaGVscGVycy5pc0VkaXQoKSl7XHJcbiAgICAgICAgICAgIHRoaXMuZGF0YS5kcm9wYWJsZUFjdGlvbiA9IGFjdGlvbnMuSW5zaWRlW3Bvc107XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2UgaWYodGhpcy5oZWxwZXJzLmlzTGF5b3V0TW9kdWxlKCkpe1xyXG4gICAgICAgICAgICB0aGlzLmRhdGEuZHJvcGFibGVBY3Rpb24gPSBhY3Rpb25zLkFyb3VuZFtwb3NdO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIGlmKHRoaXMuaGVscGVycy5pc01vZHVsZSgpKXtcclxuICAgICAgICAgICAgdGhpcy5kYXRhLmRyb3BhYmxlQWN0aW9uID0gYWN0aW9ucy5Bcm91bmRbcG9zXTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuYWN0aW9uID0gZnVuY3Rpb24oZXZlbnQpe1xyXG4gICAgICAgIHZhciBub2RlID0gZXZlbnQudGFyZ2V0O1xyXG4gICAgICAgIHZhciBmaW5hbCA9IHt9O1xyXG4gICAgICAgIGlmKHRoaXMuX2lzRWRpdExpa2Uobm9kZSkpe1xyXG4gICAgICAgICAgICBpZih0aGlzLl9jYW5Ecm9wKG5vZGUpKXtcclxuXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuXHJcblxyXG4gICAgdGhpcy5oZWxwZXJzID0ge1xyXG4gICAgICAgIHNjb3BlOnRoaXMsXHJcbiAgICAgICAgaXNCbG9ja0xldmVsOmZ1bmN0aW9uKG5vZGUpe1xyXG4gICAgICAgICAgICBub2RlID0gbm9kZSB8fCAodGhpcy5kYXRhID8gdGhpcy5kYXRhLnRhcmdldCA6IG51bGwpO1xyXG4gICAgICAgICAgICByZXR1cm4gbXcudG9vbHMuaXNCbG9ja0xldmVsKG5vZGUpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgaXNJbmxpbmVMZXZlbDpmdW5jdGlvbihub2RlKXtcclxuICAgICAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5kYXRhLnRhcmdldDtcclxuICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmlzSW5saW5lTGV2ZWwobm9kZSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBjYW5BY2NlcHQ6ZnVuY3Rpb24odGFyZ2V0LCB3aGF0KXtcclxuICAgICAgICAgICAgdmFyIGFjY2VwdCA9IHRhcmdldC5kYXRhc2V0KCdhY2NlcHQnKTtcclxuICAgICAgICAgICAgaWYoIWFjY2VwdCkgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgIGFjY2VwdCA9IGFjY2VwdC50cmltKCkuc3BsaXQoJywnKS5tYXAoRnVuY3Rpb24ucHJvdG90eXBlLmNhbGwsIFN0cmluZy5wcm90b3R5cGUudHJpbSk7XHJcbiAgICAgICAgICAgIHZhciB3dHlwZSA9ICdhbGwnO1xyXG4gICAgICAgICAgICBpZihtdy50b29scy5oYXNDbGFzcyh3aGF0LCAnbW9kdWxlLWxheW91dCcpKXtcclxuICAgICAgICAgICAgICAgIHd0eXBlID0gJ2xheW91dCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgZWxzZSBpZihtdy50b29scy5oYXNDbGFzcyh3aGF0LCAnbW9kdWxlJykpe1xyXG4gICAgICAgICAgICAgICAgd3R5cGUgPSAnbW9kdWxlJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIGlmKG13LnRvb2xzLmhhc0NsYXNzKHdoYXQsICdlbGVtZW50Jykpe1xyXG4gICAgICAgICAgICAgICAgd3R5cGUgPSAnZWxlbWVudCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYod3R5cGU9PSdhbGwnKSByZXR1cm4gdHJ1ZVxyXG5cclxuICAgICAgICAgICAgcmV0dXJuIGFjY2VwdC5pbmRleE9mKHd0eXBlKSAhPT0gLTE7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBnZXRCbG9ja0VsZW1lbnRzOmZ1bmN0aW9uKHNlbGVjdG9yLCByb290KXtcclxuICAgICAgICAgICAgcm9vdCA9IHJvb3QgfHwgZG9jdW1lbnQuYm9keTtcclxuICAgICAgICAgICAgc2VsZWN0b3IgPSBzZWxlY3RvciB8fCAnKic7XHJcbiAgICAgICAgICAgIHZhciBhbGwgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpLCBpID0gMDsgZmluYWwgPSBbXTtcclxuICAgICAgICAgICAgZm9yKCA7IGk8YWxsLmxlbmd0aDsgaSsrKXtcclxuICAgICAgICAgICAgICAgIGlmKHRoaXMuc2NvcGUuaGVscGVycy5pc0Jsb2NrTGV2ZWwoYWxsW2ldKSl7XHJcbiAgICAgICAgICAgICAgICAgICAgZmluYWwucHVzaChhbGxbaV0pXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgcmV0dXJuIGZpbmFsO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZ2V0RWxlbWVudHNMaWtlOmZ1bmN0aW9uKHNlbGVjdG9yLCByb290KXtcclxuICAgICAgICAgICAgcm9vdCA9IHJvb3QgfHwgZG9jdW1lbnQuYm9keTtcclxuICAgICAgICAgICAgc2VsZWN0b3IgPSBzZWxlY3RvciB8fCAnKic7XHJcbiAgICAgICAgICAgIHZhciBhbGwgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpLCBpID0gMDsgZmluYWwgPSBbXTtcclxuICAgICAgICAgICAgZm9yKCA7IGk8YWxsLmxlbmd0aDsgaSsrKXtcclxuICAgICAgICAgICAgICAgIGlmKCF0aGlzLnNjb3BlLmhlbHBlcnMuaXNDb2xMaWtlKGFsbFtpXSkgJiZcclxuICAgICAgICAgICAgICAgICAgICAhdGhpcy5zY29wZS5oZWxwZXJzLmlzUm93TGlrZShhbGxbaV0pICYmXHJcbiAgICAgICAgICAgICAgICAgICAgIXRoaXMuc2NvcGUuaGVscGVycy5pc0VkaXQoYWxsW2ldKSAmJlxyXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2NvcGUuaGVscGVycy5pc0Jsb2NrTGV2ZWwoYWxsW2ldKSl7XHJcbiAgICAgICAgICAgICAgICAgICAgZmluYWwucHVzaChhbGxbaV0pO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHJldHVybiBmaW5hbDtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGlzRWRpdDpmdW5jdGlvbihub2RlKXtcclxuICAgICAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5zY29wZS5kYXRhLnRhcmdldDtcclxuICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmhhc0NsYXNzKG5vZGUsIHRoaXMuc2NvcGUuY2xzLmVkaXQpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgaXNNb2R1bGU6ZnVuY3Rpb24obm9kZSl7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuc2NvcGUuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgICAgIHJldHVybiBtdy50b29scy5oYXNDbGFzcyhub2RlLCB0aGlzLnNjb3BlLmNscy5tb2R1bGUpICYmIChtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KG5vZGUsIFt0aGlzLnNjb3BlLmNscy5tb2R1bGUsIHRoaXMuc2NvcGUuY2xzLmVkaXRdKSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBpc0VsZW1lbnQ6ZnVuY3Rpb24obm9kZSl7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuc2NvcGUuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgICAgIHJldHVybiBtdy50b29scy5oYXNDbGFzcyhub2RlLCB0aGlzLnNjb3BlLmNscy5lbGVtZW50KTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGlzRW1wdHk6ZnVuY3Rpb24obm9kZSl7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuc2NvcGUuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgICAgIHJldHVybiBtdy50b29scy5oYXNDbGFzcyhub2RlLCAnbXctZW1wdHknKTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGlzUm93TGlrZTpmdW5jdGlvbihub2RlKXtcclxuICAgICAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5zY29wZS5kYXRhLnRhcmdldDtcclxuICAgICAgICAgICAgdmFyIGlzID0gZmFsc2U7XHJcbiAgICAgICAgICAgIGlmKCFub2RlLmNsYXNzTmFtZSkgcmV0dXJuIGlzO1xyXG4gICAgICAgICAgICBpcyA9IG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyhub2RlLCB0aGlzLnNjb3BlLnNldHRpbmdzLnJvd3MpO1xyXG4gICAgICAgICAgICBpZihpcyl7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gaXM7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLm1hdGNoZXMobm9kZSwgdGhpcy5zY29wZS5zZXR0aW5ncy5yb3dNYXRjaGVzKTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGlzQ29sTGlrZTpmdW5jdGlvbihub2RlKXtcclxuICAgICAgICAgICAgbm9kZSA9IG5vZGUgfHwgdGhpcy5zY29wZS5kYXRhLnRhcmdldDtcclxuICAgICAgICAgICAgdmFyIGlzID0gZmFsc2U7XHJcbiAgICAgICAgICAgIGlmKCFub2RlLmNsYXNzTmFtZSkgcmV0dXJuIGlzO1xyXG4gICAgICAgICAgICBpcyA9IG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyhub2RlLCB0aGlzLnNjb3BlLnNldHRpbmdzLmNvbHVtbnMpO1xyXG4gICAgICAgICAgICBpZihpcyl7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gaXM7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKG5vZGUsIFsnbXctY29sLWNvbnRhaW5lcicsICdtdy11aS1jb2wtY29udGFpbmVyJ10pKXtcclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICByZXR1cm4gbXcudG9vbHMubWF0Y2hlcyhub2RlLCB0aGlzLnNjb3BlLnNldHRpbmdzLmNvbHVtbk1hdGNoZXMpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgaXNMYXlvdXRNb2R1bGU6ZnVuY3Rpb24obm9kZSl7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuc2NvcGUuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuXHJcbiAgICAgICAgfSxcclxuICAgICAgICBub29wOmZ1bmN0aW9uKCl7fVxyXG4gICAgfTtcclxuXHJcblxyXG4gICAgdGhpcy5pbnRlcmFjdGlvblRhcmdldCA9IGZ1bmN0aW9uKG5leHQpe1xyXG4gICAgICAgIG5vZGUgPSB0aGlzLmRhdGEudGFyZ2V0O1xyXG4gICAgICAgIGlmKG5leHQpIG5vZGUgPSBub2RlLnBhcmVudE5vZGU7XHJcbiAgICAgICAgd2hpbGUobm9kZSAmJiAhdGhpcy5oZWxwZXJzLmlzQmxvY2tMZXZlbChub2RlKSl7XHJcbiAgICAgICAgICAgIG5vZGUgPSBub2RlLnBhcmVudE5vZGU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBub2RlO1xyXG4gICAgfTtcclxuICAgIHRoaXMudmFsaWRhdGVJbnRlcmFjdGlvblRhcmdldCA9IGZ1bmN0aW9uKG5vZGUpe1xyXG4gICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgaWYgKCFtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyhub2RlLCB0aGlzLmNscy5lZGl0KSkge1xyXG4gICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIGNscyA9IFtcclxuICAgICAgICAgICAgdGhpcy5jbHMuZWRpdCxcclxuICAgICAgICAgICAgdGhpcy5jbHMuZWxlbWVudCxcclxuICAgICAgICAgICAgdGhpcy5jbHMubW9kdWxlLFxyXG4gICAgICAgICAgICB0aGlzLmNscy5lbXB0eUVsZW1lbnRcclxuICAgICAgICBdO1xyXG4gICAgICAgIHdoaWxlKG5vZGUgJiYgbm9kZSAhPT0gbXdkLmJvZHkpe1xyXG4gICAgICAgICAgICBpZihtdy50b29scy5oYXNBbnlPZkNsYXNzZXMobm9kZSwgY2xzKSl7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbm9kZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBub2RlID0gbm9kZS5wYXJlbnROb2RlO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICB9O1xyXG4gICAgdGhpcy5vbiA9IGZ1bmN0aW9uKGV2ZW50cywgbGlzdGVuZXIpIHtcclxuICAgICAgICBldmVudHMgPSBldmVudHMudHJpbSgpLnNwbGl0KCcgJyk7XHJcbiAgICAgICAgZm9yICh2YXIgaT0wIDsgaTxldmVudHMubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcihldmVudHNbaV0sIGxpc3RlbmVyLCBmYWxzZSk7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuICAgIHRoaXMubG9hZE5ld01vZHVsZXMgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIG13LnBhdXNlU2F2ZSA9IHRydWU7XHJcbiAgICAgICAgdmFyIG5lZWRfcmVfaW5pdCA9IGZhbHNlO1xyXG4gICAgICAgIG13LiQoXCIuZWRpdCAubW9kdWxlLWl0ZW1cIikuZWFjaChmdW5jdGlvbihjKSB7XHJcblxyXG4gICAgICAgICAgICAoZnVuY3Rpb24gKGVsKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgeGhyID0gbXcuXyh7XHJcbiAgICAgICAgICAgICAgICAgICAgc2VsZWN0b3I6IGVsLFxyXG4gICAgICAgICAgICAgICAgICAgIGRvbmU6IGZ1bmN0aW9uKG1vZHVsZSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmZhbmN5bmF0ZUxvYWRpbmcobW9kdWxlKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcucGF1c2VTYXZlID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5pdF9lZGl0YWJsZXMoKTtcclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgICAgIGZhaWw6ZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uZXJyb3IoJ0Vycm9yIGxvYWRpbmcgbW9kdWxlLicpXHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSwgdHJ1ZSk7XHJcbiAgICAgICAgICAgICAgICBuZWVkX3JlX2luaXQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9KSh0aGlzKTtcclxuICAgICAgICB9KTtcclxuICAgICAgICBpZiAobXcuaGF2ZV9uZXdfaXRlbXMgPT09IHRydWUpIHtcclxuICAgICAgICAgICAgbmVlZF9yZV9pbml0ID0gdHJ1ZTtcclxuICAgICAgICB9XHJcbiAgICAgICAgbXcuaGF2ZV9uZXdfaXRlbXMgPSBmYWxzZTtcclxuICAgIH07XHJcbiAgICB0aGlzLndoZW5VcCA9IGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcclxuICAgICAgICB0aGlzLm9uKCdtb3VzZXVwIHRvdWNoZW5kJywgZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgaWYoc2NvcGUuZGF0YS5jdXJyZW50R3JhYmJlZCl7XHJcbiAgICAgICAgICAgICAgICBzY29wZS5kYXRhLmN1cnJlbnRHcmFiYmVkID0gbnVsbDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmdldFRhcmdldCA9IGZ1bmN0aW9uKHQpe1xyXG4gICAgICAgIHQgPSB0IHx8IHRoaXMudmFsaWRhdGVJbnRlcmFjdGlvblRhcmdldCgpO1xyXG4gICAgICAgIGlmKCF0KXtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAodGhpcy5jYW5Ecm9wKHQpKSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0O1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0aGlzLnJlZGlyZWN0KHQpO1xyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5yZWRpcmVjdCA9IGZ1bmN0aW9uKG5vZGUpe1xyXG4gICAgICAgIG5vZGUgPSBub2RlIHx8IHRoaXMuZGF0YS50YXJnZXQ7XHJcbiAgICAgICAgdmFyIGlzbGF5T3V0SW5MYXlvdXQgPSB0aGlzLl9sYXlvdXRJbkxheW91dChub2RlKTtcclxuICAgICAgICBpZihpc2xheU91dEluTGF5b3V0LnJlc3VsdCl7XHJcbiAgICAgICAgICAgIHZhciByZXMgPSAgdGhpcy52YWxpZGF0ZUludGVyYWN0aW9uVGFyZ2V0KC8qbm9kZSA9PT0gaXNsYXlPdXRJbkxheW91dC50YXJnZXQgPyBpc2xheU91dEluTGF5b3V0LnRhcmdldC5wYXJlbnROb2RlIDogKi9pc2xheU91dEluTGF5b3V0LnRhcmdldCk7XHJcbiAgICAgICAgICAgIHJldHVybiAgcmVzO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZihub2RlID09PSBtd2QuYm9keSB8fCBub2RlLnBhcmVudE5vZGUgPT09IG13ZC5ib2R5KSByZXR1cm4gbnVsbDtcclxuICAgICAgICByZXR1cm4gdGhpcy5nZXRUYXJnZXQobm9kZS5wYXJlbnROb2RlKTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5pbnRlcmFjdGlvbkFuYWxpemVyID0gZnVuY3Rpb24oZSl7XHJcblxyXG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XHJcbiAgICAgICAgbXcuZHJvcGFibGUuaGlkZSgpO1xyXG5cclxuICAgICAgICBpZih0aGlzLmRhdGEuY3VycmVudEdyYWJiZWQpe1xyXG4gICAgICAgICAgICBpZiAoZS50eXBlLmluZGV4T2YoJ3RvdWNoJykgIT09IC0xKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgY29vcmRzID0gbXcuZXZlbnQucGFnZShlKTtcclxuICAgICAgICAgICAgICAgIHNjb3BlLmRhdGEudGFyZ2V0ID0gbXdkLmVsZW1lbnRGcm9tUG9pbnQoY29vcmRzLngsIGNvb3Jkcy55KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgIHNjb3BlLmRhdGEudGFyZ2V0ID0gZS50YXJnZXQ7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgc2NvcGUuaW50ZXJhY3Rpb25UYXJnZXQoKTtcclxuICAgICAgICAgICAgc2NvcGUuZGF0YS50YXJnZXQgPSBzY29wZS5nZXRUYXJnZXQoKTtcclxuXHJcbiAgICAgICAgICAgIGlmKHNjb3BlLmRhdGEudGFyZ2V0KXtcclxuICAgICAgICAgICAgICAgICAgICBzY29wZS5hbmFsaXplUG9zaXRpb24oZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuYW5hbGl6ZUFjdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgICAgIG13LmRyb3BhYmxlLnNob3coKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNle1xyXG5cclxuICAgICAgICAgICAgICAgICAgICB2YXIgbmVhciA9IG13LmRyb3BhYmxlcy5maW5kTmVhcmVzdChlKTtcclxuICAgICAgICAgICAgICAgICAgICBpZihuZWFyLmVsZW1lbnQpe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5kYXRhLnRhcmdldCA9IG5lYXIuZWxlbWVudDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuZGF0YS5kcm9wYWJsZVBvc2l0aW9uID0gbmVhci5wb3NpdGlvbjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJvcGFibGVzLmZpbmROZWFyZXN0RXhjZXB0aW9uID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJvcGFibGUuc2hvdygpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBlbHNle1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5jdXJyZW50RHJhZ01vdXNlT3ZlciA9IG51bGw7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyb3BhYmxlLmhpZGUoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuZGF0YVJlc2V0KCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIHZhciBlbCA9IG13LiQoc2NvcGUuZGF0YS50YXJnZXQpO1xyXG4gICAgICAgICAgICBtdy5jdXJyZW50RHJhZ01vdXNlT3ZlciA9IHNjb3BlLmRhdGEudGFyZ2V0O1xyXG5cclxuICAgICAgICAgICAgdmFyIGVkaXQgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyhtdy5jdXJyZW50RHJhZ01vdXNlT3ZlciwgJ2VkaXQnKTtcclxuICAgICAgICAgICAgbXcudG9vbHMuY2xhc3NOYW1lc3BhY2VEZWxldGUobXcuZHJvcGFibGVbMF0sICdtdy1kcm9wYWJsZS10YWdyZXQtcmVsLScpO1xyXG4gICAgICAgICAgICBpZihlZGl0KSB7XHJcbiAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhtdy5kcm9wYWJsZVswXSwgJ213LWRyb3BhYmxlLXRhZ3JldC1yZWwtJyArIGVkaXQuZ2V0QXR0cmlidXRlKCdyZWwnKSk7XHJcbiAgICAgICAgICAgICAgICB2YXIgcmVsID0gZWRpdC5nZXRBdHRyaWJ1dGUoJ3JlbCcpO1xyXG4gICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MobXcuZHJvcGFibGVbMF0sICdtdy1kcm9wYWJsZS10YWdyZXQtcmVsLScgKyByZWwpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBtdy5kcm9wYWJsZXMuc2V0KHNjb3BlLmRhdGEuZHJvcGFibGVQb3NpdGlvbiwgZWwub2Zmc2V0KCksIGVsLmhlaWdodCgpLCBlbC53aWR0aCgpKTtcclxuXHJcbiAgICAgICAgICAgIGlmKGVsWzBdICYmICFtdy50b29scy5oYXNBbnlPZkNsYXNzZXMoZWxbMF0sIFsnbXctZHJhZy1jdXJyZW50LScrc2NvcGUuZGF0YS5kcm9wYWJsZVBvc2l0aW9uXSkpe1xyXG4gICAgICAgICAgICAgICAgbXcuJCgnLm13LWRyYWctY3VycmVudC10b3AsLm13LWRyYWctY3VycmVudC1ib3R0b20nKS5yZW1vdmVDbGFzcygnbXctZHJhZy1jdXJyZW50LXRvcCBtdy1kcmFnLWN1cnJlbnQtYm90dG9tJyk7XHJcbiAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhlbFswXSwgJ213LWRyYWctY3VycmVudC0nK3Njb3BlLmRhdGEuZHJvcGFibGVQb3NpdGlvbilcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy53aGVuTW92ZSA9IGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcclxuICAgICAgICB0aGlzLm9uKCdtb3VzZW1vdmUgdG91Y2htb3ZlJywgZnVuY3Rpb24oZSl7XHJcbiAgICAgICAgICAgIHNjb3BlLmludGVyYWN0aW9uQW5hbGl6ZXIoZSlcclxuICAgICAgICB9KTtcclxuICAgIH07XHJcbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMucHJlcGFyZSgpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmluaXQoKTtcclxufTtcclxuXHJcbm13LmVhID0gbmV3IG13LkVsZW1lbnRBbmFseXplcigpO1xyXG4iLCJtdy5saXZlZWRpdC5oYW5kbGVDdXN0b21FdmVudHMgPSBmdW5jdGlvbigpIHtcbiAgICBtdy5vbignbW9kdWxlT3ZlciBFbGVtZW50T3ZlcicsIGZ1bmN0aW9uKGUsIGV0YXJnZXQsIG9ldmVudCl7XG4gICAgICAgIHZhciB0YXJnZXQgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXMob2V2ZW50LnRhcmdldCwgWydlbGVtZW50JywgJ21vZHVsZSddKTtcbiAgICAgICAgaWYodGFyZ2V0LmlkKXtcbiAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3IuYWN0aXZlKHRydWUpO1xuICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3Rvci5zZXRJdGVtKHRhcmdldCwgbXcubGl2ZUVkaXRTZWxlY3Rvci5pbnRlcmFjdG9ycyk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIG13LiQoZG9jdW1lbnQuYm9keSkub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgdmFyIHRhcmdldCA9IGUudGFyZ2V0O1xuICAgICAgICB2YXIgY2FuID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKHRhcmdldCwgW1xuICAgICAgICAgICAnZWRpdCcsICdtb2R1bGUnLCAnZWxlbWVudCdcbiAgICAgICAgXSk7XG4gICAgICAgIGlmKGNhbikge1xuICAgICAgICAgICAgdmFyIHRvU2VsZWN0ID0gbXcudG9vbHMuZmlyc3ROb3RJbmxpbmVMZXZlbCh0YXJnZXQpO1xuXG4gICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLnNlbGVjdCh0YXJnZXQpO1xuXG4gICAgICAgICAgICBpZihtdy5saXZlRWRpdERvbVRyZWUpIHtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdERvbVRyZWUuc2VsZWN0KG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcih0YXJnZXQpKTtcblxuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cblxuICAgIH0pO1xuXG5cbiAgICBtdy5vbihcIkRyYWdIb3Zlck9uRW1wdHlcIiwgZnVuY3Rpb24oZSwgZWwpIHtcbiAgICAgICAgaWYgKCQuYnJvd3Nlci53ZWJraXQpIHtcbiAgICAgICAgICAgIHZhciBfZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgICAgIF9lbC5hZGRDbGFzcyhcImhvdmVyXCIpO1xuICAgICAgICAgICAgaWYgKCFfZWwuaGFzQ2xhc3MoXCJtdy13ZWJraXQtZHJhZy1ob3Zlci1iaW5kZWRcIikpIHtcbiAgICAgICAgICAgICAgICBfZWwuYWRkQ2xhc3MoXCJtdy13ZWJraXQtZHJhZy1ob3Zlci1iaW5kZWRcIik7XG4gICAgICAgICAgICAgICAgX2VsLm1vdXNlbGVhdmUoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIF9lbC5yZW1vdmVDbGFzcyhcImhvdmVyXCIpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG4gICAgbXcub24oXCJJY29uRWxlbWVudENsaWNrXCIsIGZ1bmN0aW9uKGUsIGVsKSB7XG4gICAgICAgIG13LmVkaXRvckljb25QaWNrZXIudG9vbHRpcChlbClcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZShlbCwgZmFsc2UpO1xuICAgICAgICB9KTtcbiAgICB9KTtcblxuICAgIG13Lm9uKFwiQ29tcG9uZW50Q2xpY2tcIiwgZnVuY3Rpb24oZSwgbm9kZSwgdHlwZSl7XG5cbiAgICAgICAgaWYgKHR5cGUgPT09ICdpY29uJyl7XG4gICAgICAgICAgICBtdy5lZGl0b3JJY29uUGlja2VyLnRvb2x0aXAobm9kZSlcbiAgICAgICAgICAgIHJldHVybjtcblxuICAgICAgICB9XG4gICAgICAgIGlmKG13LnNldHRpbmdzLmxpdmVfZWRpdF9vcGVuX21vZHVsZV9zZXR0aW5nc19pbl9zaWRlYmFyKSB7XG4gICAgICAgICAgICBtdy5sb2coJ0NvbXBvbmVudENsaWNrJyArIHR5cGUpO1xuICAgICAgICAgICAgaWYgKCFtdy5saXZlRWRpdFNldHRpbmdzKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuOyAvLyBhZG1pbiBtb2RlXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgdWl0eXBlID0gdHlwZTtcbiAgICAgICAgICAgIGlmICh0eXBlID09PSAnZWxlbWVudCcpIHtcbiAgICAgICAgICAgICAgICB1aXR5cGUgPSAnbm9uZSc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAodHlwZSA9PT0gJ3NhZmUtZWxlbWVudCcpIHtcbiAgICAgICAgICAgICAgICAvL3VpdHlwZSA9ICdlbGVtZW50JyA7XG4gICAgICAgICAgICAgICAgdWl0eXBlID0gJ25vbmUnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKG5vZGUubm9kZU5hbWUgPT09ICdJTUcnKSB7XG4gICAgICAgICAgICAgICAgdWl0eXBlID0gJ2ltYWdlJztcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKG13LmxpdmVFZGl0U2V0dGluZ3MuYWN0aXZlKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnNpZGViYXJTZXR0aW5nc1RhYnMpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHVpdHlwZSAhPT0gJ21vZHVsZScpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnNpZGViYXJTZXR0aW5nc1RhYnMuc2V0TGFzdENsaWNrZWQoKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnNpZGViYXJTZXR0aW5nc1RhYnMuc2V0KDIpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LmxpdmVOb2RlU2V0dGluZ3Muc2V0KHVpdHlwZSwgbm9kZSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgbXcub24oXCJFbGVtZW50Q2xpY2tcIiwgZnVuY3Rpb24oZSwgZWwsIGMpIHtcbiAgICAgICAgbXcuJChcIi5lbGVtZW50LWN1cnJlbnRcIikubm90KGVsKS5yZW1vdmVDbGFzcygnZWxlbWVudC1jdXJyZW50Jyk7XG4gICAgICAgIGlmIChtdy5saXZlRWRpdFNlbGVjdE1vZGUgPT09ICdlbGVtZW50Jykge1xuICAgICAgICAgICAgbXcuJChlbCkuYWRkQ2xhc3MoJ2VsZW1lbnQtY3VycmVudCcpO1xuICAgICAgICB9XG5cbiAgICAgICAgbXcuJCgnLm1vZHVsZScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRoaXMsIGZhbHNlKVxuICAgICAgICB9KTtcbiAgICB9KTtcbiAgICBtdy5vbihcIlBsYWluVGV4dENsaWNrXCIsIGZ1bmN0aW9uKGUsIGVsKSB7XG4gICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGVsLCB0cnVlKTtcbiAgICAgICAgbXcuJCgnLm1vZHVsZScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRoaXMsIGZhbHNlKTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG5cblxuICAgIG13Lm9uKFwiZWRpdFVzZXJJc1R5cGluZ0ZvckxvbmdcIiwgZnVuY3Rpb24obm9kZSl7XG4gICAgICAgIGlmKHR5cGVvZihtdy5saXZlRWRpdFNldHRpbmdzKSAhPSAndW5kZWZpbmVkJyl7XG4gICAgICAgICAgICBpZihtdy5saXZlRWRpdFNldHRpbmdzLmFjdGl2ZSl7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZXR0aW5ncy5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbiAgICBtdy5vbihcIlRhYmxlVGRDbGlja1wiLCBmdW5jdGlvbihlLCBlbCkge1xuICAgICAgICBpZiAobXcubGl2ZWVkaXQgJiYgbXcubGl2ZWVkaXQuaW5saW5lKSB7XG4gICAgICAgICAgICBtdy5saXZlZWRpdC5pbmxpbmUuc2V0QWN0aXZlQ2VsbChlbCwgZSk7XG4gICAgICAgICAgICB2YXIgdGRfcGFyZW50X3RhYmxlID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKGVsLCAndGFibGUnKTtcbiAgICAgICAgICAgIGlmICh0ZF9wYXJlbnRfdGFibGUpIHtcbiAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC5pbmxpbmUudGFibGVDb250cm9sbGVyKHRkX3BhcmVudF90YWJsZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIG13Lm9uKCdVc2VySW50ZXJhY3Rpb24nLCBmdW5jdGlvbigpe1xuICAgICAgICBtdy5kcm9wYWJsZXMudXNlckludGVyYWN0aW9uQ2xhc3NlcygpO1xuICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLnBvc2l0aW9uU2VsZWN0ZWQoKTtcblxuICAgIH0pO1xuXG4gICAgbXcub24oJ0VsZW1lbnRPdmVyIG1vZHVsZU92ZXInLCBmdW5jdGlvbihlLCB0YXJnZXQpe1xuICAgICAgICB2YXIgb3Zlcl90YXJnZXRfZWwgPSBudWxsO1xuICAgICAgICBpZihlLnR5cGUgPT09ICdvbkVsZW1lbnRPdmVyJyl7XG4gICAgICAgICAgICBvdmVyX3RhcmdldF9lbCA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3Nlcyh0YXJnZXQsIFsnZWxlbWVudCddKVxuICAgICAgICAgICAgaWYob3Zlcl90YXJnZXRfZWwgJiYgIW13LnRvb2xzLmhhc0NsYXNzKCdlbGVtZW50LW92ZXInLG92ZXJfdGFyZ2V0X2VsKSl7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3Mob3Zlcl90YXJnZXRfZWwsICdlbGVtZW50LW92ZXInKVxuICAgICAgICAgICAgfVxuICAgICAgICB9IGVsc2UgaWYoZS50eXBlID09PSAnbW9kdWxlT3Zlcicpe1xuICAgICAgICAgICAgb3Zlcl90YXJnZXRfZWwgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXModGFyZ2V0LCBbJ21vZHVsZSddKVxuICAgICAgICAgICAgaWYob3Zlcl90YXJnZXRfZWwgJiYgIW13LnRvb2xzLmhhc0NsYXNzKCdtb2R1bGUtb3Zlcicsb3Zlcl90YXJnZXRfZWwpKXtcbiAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhvdmVyX3RhcmdldF9lbCwgJ21vZHVsZS1vdmVyJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZihvdmVyX3RhcmdldF9lbCl7XG4gICAgICAgICAgICBtdy4kKFwiLmVsZW1lbnQtb3ZlciwubW9kdWxlLW92ZXJcIikubm90KG92ZXJfdGFyZ2V0X2VsKS5yZW1vdmVDbGFzcygnZWxlbWVudC1vdmVyIG1vZHVsZS1vdmVyJyk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuXG5cbiAgICBtdy5vbignQ2xvbmVhYmxlT3ZlcicsIGZ1bmN0aW9uKGUsIHRhcmdldCwgaXNPdmVyQ29udHJvbCl7XG4gICAgICAgIG13LmRyYWcub25DbG9uZWFibGVDb250cm9sKHRhcmdldCwgaXNPdmVyQ29udHJvbClcbiAgICB9KTtcblxuICAgIHZhciBvbk1vZHVsZUJldHdlZW5Nb2R1bGVzVGltZSA9IG51bGw7XG5cbiAgICBtdy5vbignTW9kdWxlQmV0d2Vlbk1vZHVsZXMnLCBmdW5jdGlvbihlLCBlbCwgcG9zKXtcbiAgICAgICAgY2xlYXJUaW1lb3V0KG9uTW9kdWxlQmV0d2Vlbk1vZHVsZXNUaW1lKTtcbiAgICAgICAgb25Nb2R1bGVCZXR3ZWVuTW9kdWxlc1RpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZigkKFwiI21vZHVsZWluYmV0d2VlblwiKS5sZW5ndGggPT09IDApe1xuICAgICAgICAgICAgICAgIHZhciB0aXAgPSBtdy50b29sdGlwKHtcbiAgICAgICAgICAgICAgICAgICAgY29udGVudDonVG8gZHJvcCB0aGlzIGVsZW1lbnQgaGVyZSwgc2VsZWN0IENsZWFuIGNvbnRhaW5lciBmaXJzdCcsXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6ZWxbMF0sXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOnBvcysnLWNlbnRlcicsXG4gICAgICAgICAgICAgICAgICAgIHNraW46J2RhcmsnLFxuICAgICAgICAgICAgICAgICAgICBpZDonbW9kdWxlaW5iZXR3ZWVuJ1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChcIiNtb2R1bGVpbmJldHdlZW5cIikuZmFkZU91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSwgMzAwMCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sIDEwMDApO1xuICAgIH0pO1xufTtcbiIsIm13LmxpdmVlZGl0LmhhbmRsZUV2ZW50cyA9IGZ1bmN0aW9uKCkge1xuICAgIG13LiQoZG9jdW1lbnQuYm9keSkub24oJ3RvdWNobW92ZSBtb3VzZW1vdmUnLCBmdW5jdGlvbihlKXtcbiAgICAgICAgaWYobXcubGl2ZUVkaXRTZWxlY3Rvci5pbnRlcmFjdG9ycy5hY3RpdmUpIHtcbiAgICAgICAgICAgIGlmKCAhbXcubGl2ZWVkaXQuZGF0YS5nZXQoJ21vdmUnLCAnaGFzTW9kdWxlT3JFbGVtZW50Jykpe1xuICAgICAgICAgICAgICAgIGlmKGUudGFyZ2V0ICE9PSBtdy5kcmFnLnBsdXNUb3AgJiYgZS50YXJnZXQgIT09IG13LmRyYWcucGx1c0JvdHRvbSkge1xuICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLmhpZGVJdGVtKG13LmxpdmVFZGl0U2VsZWN0b3IuaW50ZXJhY3RvcnMpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xuICAgIG13LiQoXCIjbGl2ZS1lZGl0LWRyb3Bkb3duLWFjdGlvbnMtY29udGVudCBhXCIpLm9mZignY2xpY2snKTtcblxuICAgIG13LiQoZG9jdW1lbnQpLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uKGUpe1xuICAgICAgICBpZighbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZS50YXJnZXQsIFsnbXctZGVmYXVsdHMnLCAnZWRpdCcsICdlbGVtZW50J10pKXtcbiAgICAgICAgICAgIG13LiQoXCIuZWxlbWVudC1jdXJyZW50XCIpLnJlbW92ZUNsYXNzKFwiZWxlbWVudC1jdXJyZW50XCIpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBtdy4kKFwic3Bhbi5lZGl0Om5vdCgnLm5vZHJvcCcpXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgbXcudG9vbHMuc2V0VGFnKHRoaXMsICdkaXYnKTtcbiAgICB9KTtcblxuXG4gICAgbXcuJChcIiNtdy10b29sYmFyLWNzcy1lZGl0b3ItYnRuXCIpLmNsaWNrKGZ1bmN0aW9uKCkge1xuICAgICAgICBtdy5saXZlZWRpdC53aWRnZXRzLmNzc0VkaXRvckRpYWxvZygpO1xuICAgIH0pO1xuICAgIG13LiQoXCIjbXctdG9vbGJhci1odG1sLWVkaXRvci1idG5cIikuY2xpY2soZnVuY3Rpb24oKSB7XG4gICAgICAgIG13LmxpdmVlZGl0LndpZGdldHMuaHRtbEVkaXRvckRpYWxvZygpO1xuICAgIH0pO1xuXG4gICAgbXcuJChcIiNtdy10b29sYmFyLXJlc2V0LWNvbnRlbnQtZWRpdG9yLWJ0blwiKS5jbGljayhmdW5jdGlvbigpIHtcbiAgICAgICAgbXcudG9vbHMub3Blbl9yZXNldF9jb250ZW50X2VkaXRvcigpO1xuICAgIH0pO1xuICAgIG13LiQobXdkLmJvZHkpLm9uKCdrZXl1cCcsIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgbXcuJChcIi5td19tYXN0ZXJfaGFuZGxlXCIpLmNzcyh7XG4gICAgICAgICAgICBsZWZ0OiBcIlwiLFxuICAgICAgICAgICAgdG9wOiBcIlwiXG4gICAgICAgIH0pO1xuICAgICAgICBtdy5vbi5zdG9wV3JpdGluZyhlLnRhcmdldCwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZS50YXJnZXQsICdlZGl0JykgfHwgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyh0aGlzLCAnZWRpdCcpKSB7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6ZS50YXJnZXQsXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlOmUudGFyZ2V0LmlubmVySFRNTFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIG13LmRyYWcuc2F2ZURyYWZ0KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0pO1xuXG4gICAgbXcuJChtd2QuYm9keSkub24oXCJrZXlkb3duXCIsIGZ1bmN0aW9uKGUpIHtcblxuICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA4MyAmJiBlLmN0cmxLZXkpIHtcblxuICAgICAgICAgICAgaWYgKGUuYWx0S2V5KSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAodHlwZW9mKG13LnNldHRpbmdzLmxpdmVfZWRpdF9kaXNhYmxlX2tleWJvYXJkX3Nob3J0Y3V0cykgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICBpZiAobXcuc2V0dGluZ3MubGl2ZV9lZGl0X2Rpc2FibGVfa2V5Ym9hcmRfc2hvcnRjdXRzID09PSB0cnVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy5ldmVudC5jYW5jZWwoZSwgdHJ1ZSk7XG4gICAgICAgICAgICBtdy5kcmFnLnNhdmUoKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgbXcuJChtd2QuYm9keSkub24oXCJwYXN0ZVwiLCBmdW5jdGlvbihlKSB7XG4gICAgICAgIGlmKG13LnRvb2xzLmhhc0NsYXNzKGUudGFyZ2V0LCAncGxhaW4tdGV4dCcpKXtcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIHZhciB0ZXh0ID0gKGUub3JpZ2luYWxFdmVudCB8fCBlKS5jbGlwYm9hcmREYXRhLmdldERhdGEoJ3RleHQvcGxhaW4nKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmV4ZWNDb21tYW5kKFwiaW5zZXJ0SFRNTFwiLCBmYWxzZSwgdGV4dCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIG13LiQobXdkLmJvZHkpLm9uKFwibW91c2Vkb3duIG1vdXNldXAgdG91Y2hzdGFydCB0b3VjaGVuZFwiLCBmdW5jdGlvbihlKSB7XG5cbiAgICAgICAgaWYgKGUudHlwZSA9PT0gJ21vdXNlZG93bicgfHwgZS50eXBlID09PSAndG91Y2hzdGFydCcpIHtcbiAgICAgICAgICAgIGlmICghbXcud3lzaXd5Zy5lbGVtZW50SGFzRm9udEljb25DbGFzcyhlLnRhcmdldClcbiAgICAgICAgICAgICAgICAmJiAhbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZS50YXJnZXQsIFsndG9vbHRpcC1pY29uLXBpY2tlcicsICdtdy10b29sdGlwJ10pKSB7XG5cbiAgICAgICAgICAgICAgICBtdy5lZGl0b3JJY29uUGlja2VyLnRvb2x0aXAoJ2hpZGUnKVxuICAgICAgICAgICAgICAgIHRyeXtcbiAgICAgICAgICAgICAgICAgICAgJChtdy5saXZlZWRpdC53aWRnZXRzLl9pY29uRWRpdG9yLnRvb2x0aXApLmhpZGUoKTtcbiAgICAgICAgICAgICAgICB9Y2F0Y2goZSl7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICghbXcudG9vbHMuaGFzQ2xhc3MoZS50YXJnZXQsICd1aS1yZXNpemFibGUtaGFuZGxlJykgJiYgIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZS50YXJnZXQsICd1aS1yZXNpemFibGUtaGFuZGxlJykpIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhtd2QuYm9keSwgJ3N0YXRlLWVsZW1lbnQnKVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtd2QuYm9keSwgJ3N0YXRlLWVsZW1lbnQnKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGUudGFyZ2V0LCAnbXctdG9vbHRpcC1pbnNlcnQtbW9kdWxlJykgJiYgIW13LnRvb2xzLmhhc0FueU9mQ2xhc3NlcyhlLnRhcmdldCwgWydtdy1wbHVzLWJvdHRvbScsICdtdy1wbHVzLXRvcCddKSkge1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy10b29sdGlwLWluc2VydC1tb2R1bGUnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLnBsdXMubG9ja2VkID0gZmFsc2U7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKG13ZC5ib2R5LCAnc3RhdGUtZWxlbWVudCcpO1xuICAgICAgICB9XG4gICAgfSk7XG4gICAgbXcuJCgnc3Bhbi5tdy1wb3dlcmVkLWJ5Jykub24oXCJjbGlja1wiLCBmdW5jdGlvbihlKSB7XG4gICAgICAgIG13LnRvb2xzLm9wZW5fZ2xvYmFsX21vZHVsZV9zZXR0aW5nc19tb2RhbCgnd2hpdGVfbGFiZWwvYWRtaW4nLCAnbXctcG93ZXJlZC1ieScpO1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSk7XG5cbiAgICBtdy4kKFwiLmVkaXQgYSwgI213LXRvb2xiYXItcmlnaHQgYVwiKS5jbGljayhmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIGVsID0gdGhpcztcbiAgICAgICAgaWYgKCFlbC5pc0NvbnRlbnRFZGl0YWJsZSkge1xuICAgICAgICAgICAgaWYgKGVsLm9uY2xpY2sgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICB2YXIgaHJlZiA9IChlbC5nZXRBdHRyaWJ1dGUoJ2hyZWYnKSB8fCAnJykudHJpbSgpO1xuICAgICAgICAgICAgICAgIGlmKGhyZWYpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCEoaHJlZi5pbmRleE9mKFwiamF2YXNjcmlwdDpcIikgPT09IDAgfHwgaHJlZi5pbmRleE9mKCcjJykgPT09IDApKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbXcubGl2ZWVkaXQuYmVmb3JlbGVhdmUodGhpcy5ocmVmKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xuXG59O1xuIiwibXcucmVxdWlyZSgnc2VsZWN0b3IuanMnKTtcblxudmFyIGR5bmFtaWNNb2R1bGVzTWVudVRpbWUgPSBudWxsO1xudmFyIGR5bmFtaWNNb2R1bGVzTWVudSA9IGZ1bmN0aW9uKGUsIGVsKSB7XG4gICAgaWYoIW13LmluYWNjZXNzaWJsZU1vZHVsZXMpe1xuICAgICAgICBtdy5pbmFjY2Vzc2libGVNb2R1bGVzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIG13LmluYWNjZXNzaWJsZU1vZHVsZXMuY2xhc3NOYW1lID0gJ213LXVpLWJ0bi1uYXYgbXdJbmFjY2Vzc2libGVNb2R1bGVzTWVudSc7XG4gICAgICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQobXcuaW5hY2Nlc3NpYmxlTW9kdWxlcyk7XG4gICAgICAgIG13LiQobXcuaW5hY2Nlc3NpYmxlTW9kdWxlcykub24oJ21vdXNlZW50ZXInLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5faG92ZXJlZCA9IHRydWU7XG4gICAgICAgIH0pLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuX2hvdmVyZWQgPSBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgdmFyICRlbDtcbiAgICBpZihlLnR5cGUgPT09ICdtb2R1bGVPdmVyJyB8fCBlLnR5cGUgPT09ICdNb2R1bGVDbGljaycpe1xuXG4gICAgICAgIHZhciBwYXJlbnRNb2R1bGUgPSBtdy50b29scy5sYXN0UGFyZW50V2l0aENsYXNzKGVsLCAnbW9kdWxlJyk7XG4gICAgICAgIHZhciBjaGlsZE1vZHVsZSA9IG13LnRvb2xzLmZpcnN0Q2hpbGRXaXRoQ2xhc3MoZWwsICdtb2R1bGUnKTtcblxuICAgICAgICAkZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgaWYoISFwYXJlbnRNb2R1bGUgJiYgKCAkZWwub2Zmc2V0KCkudG9wIC0gbXcuJChwYXJlbnRNb2R1bGUpLm9mZnNldCgpLnRvcCkgPCAxMCApe1xuICAgICAgICAgICAgZWwuX19kaXNhYmxlTW9kdWxlVHJpZ2dlciA9IHBhcmVudE1vZHVsZTtcbiAgICAgICAgICAgICRlbC5hZGRDbGFzcygnaW5hY2Nlc3NpYmxlTW9kdWxlJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZighIWNoaWxkTW9kdWxlICYmICggbXcuJChjaGlsZE1vZHVsZSkub2Zmc2V0KCkudG9wIC0gJGVsLm9mZnNldCgpLnRvcCkgPCAxMCApIHtcbiAgICAgICAgICAgIGNoaWxkTW9kdWxlLl9fZGlzYWJsZU1vZHVsZVRyaWdnZXIgPSBlbDtcbiAgICAgICAgICAgIG13LiQoY2hpbGRNb2R1bGUpLmFkZENsYXNzKCdpbmFjY2Vzc2libGVNb2R1bGUnKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICAgJGVsLnJlbW92ZUNsYXNzKCdpbmFjY2Vzc2libGVNb2R1bGUnKTtcbiAgICAgICAgfVxuICAgIH1cblxuXG4gICAgdmFyIG1vZHVsZXMgPSBtdy4kKFwiLmluYWNjZXNzaWJsZU1vZHVsZVwiLCBlbCk7XG4gICAgaWYobW9kdWxlcy5sZW5ndGggPT09IDApe1xuICAgICAgICB2YXIgcGFyZW50ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3MoZWwsICdtb2R1bGUnKTtcbiAgICAgICAgaWYocGFyZW50KXtcbiAgICAgICAgICAgIGlmKCgkZWwub2Zmc2V0KCkudG9wIC0gbXcuJChwYXJlbnQpLm9mZnNldCgpLnRvcCkgPCAxMCkge1xuICAgICAgICAgICAgICAgIG1vZHVsZXMgPSBtdy4kKFtlbF0pO1xuICAgICAgICAgICAgICAgIGVsID0gcGFyZW50O1xuICAgICAgICAgICAgICAgICRlbCA9IG13LiQoZWwpO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG4gICAgaWYgKGUudHlwZSA9PT0gJ01vZHVsZUNsaWNrJykge1xuICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLnNlbGVjdChlbCk7XG4gICAgfVxuXG4gICAgaWYobW9kdWxlcy5sZW5ndGggJiYgIW13LmluYWNjZXNzaWJsZU1vZHVsZXMuX2hvdmVyZWQpIHtcbiAgICAgICAgbXcuaW5hY2Nlc3NpYmxlTW9kdWxlcy5pbm5lckhUTUwgPSAnJztcbiAgICB9XG4gICAgbW9kdWxlcy5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBzcGFuID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICBzcGFuLmNsYXNzTmFtZSA9ICdtdy1oYW5kbGUtaXRlbSBtdy11aS1idG4gbXctdWktYnRuLXNtYWxsJztcbiAgICAgICAgdmFyIHR5cGUgPSBtdy4kKHRoaXMpLmF0dHIoJ2RhdGEtdHlwZScpIHx8IG13LiQodGhpcykuYXR0cigndHlwZScpO1xuICAgICAgICBpZih0eXBlKXtcbiAgICAgICAgICAgIHZhciB0aXRsZSA9IG13LmxpdmVfZWRpdC5yZWdpc3RyeVt0eXBlXSA/IG13LmxpdmVfZWRpdC5yZWdpc3RyeVt0eXBlXS50aXRsZSA6IHR5cGU7XG4gICAgICAgICAgICB0aXRsZSA9IHRpdGxlLnJlcGxhY2UoL1xcXy8sICcgJyk7XG4gICAgICAgICAgICBzcGFuLmlubmVySFRNTCA9IG13LmxpdmVfZWRpdC5nZXRNb2R1bGVJY29uKHR5cGUpICsgdGl0bGU7XG4gICAgICAgICAgICB2YXIgZWwgPSB0aGlzO1xuICAgICAgICAgICAgc3Bhbi5vbmNsaWNrID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBtdy50b29scy5tb2R1bGVfc2V0dGluZ3MoZWwpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIG13LmluYWNjZXNzaWJsZU1vZHVsZXMuYXBwZW5kQ2hpbGQoc3Bhbik7XG4gICAgICAgIH1cbiAgICB9KTtcbiAgICBpZihtb2R1bGVzLmxlbmd0aCA+IDApe1xuICAgICAgICB2YXIgb2ZmID0gbXcuJChlbCkub2Zmc2V0KCk7XG4gICAgICAgIGlmKG13LnRvb2xzLmNvbGxpc2lvbihlbCwgbXcuaGFuZGxlTW9kdWxlLndyYXBwZXIpKXtcbiAgICAgICAgICAgIG9mZi50b3AgPSBwYXJzZUZsb2F0KG13LmhhbmRsZU1vZHVsZS53cmFwcGVyLnN0eWxlLnRvcCkgKyAzMDtcbiAgICAgICAgICAgIG9mZi5sZWZ0ID0gcGFyc2VGbG9hdChtdy5oYW5kbGVNb2R1bGUud3JhcHBlci5zdHlsZS5sZWZ0KTtcbiAgICAgICAgfVxuICAgICAgICBtdy5pbmFjY2Vzc2libGVNb2R1bGVzLnN0eWxlLnRvcCA9IG9mZi50b3AgKyAncHgnO1xuICAgICAgICBtdy5pbmFjY2Vzc2libGVNb2R1bGVzLnN0eWxlLmxlZnQgPSBvZmYubGVmdCArICdweCc7XG4gICAgICAgIGNsZWFyVGltZW91dChkeW5hbWljTW9kdWxlc01lbnVUaW1lKTtcbiAgICAgICAgbXcuJChtdy5pbmFjY2Vzc2libGVNb2R1bGVzKS5zaG93KCk7XG4gICAgfVxuICAgIGVsc2V7XG4gICAgICAgIGR5bmFtaWNNb2R1bGVzTWVudVRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZighbXcuaW5hY2Nlc3NpYmxlTW9kdWxlcy5faG92ZXJlZCkge1xuICAgICAgICAgICAgICAgIG13LiQobXcuaW5hY2Nlc3NpYmxlTW9kdWxlcykuaGlkZSgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sIDMwMDApO1xuXG4gICAgfVxuXG5cbiAgICByZXR1cm4gJGVsWzBdO1xuXG59O1xuXG52YXIgaGFuZGxlRG9tdHJlZVN5bmMgPSB7fTtcblxubXcuSGFuZGxlID0gZnVuY3Rpb24ob3B0aW9ucykge1xuXG4gICAgdGhpcy5vcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcblxuICAgIHZhciBzY29wZSA9IHRoaXM7XG5cbiAgICB0aGlzLl92aXNpYmxlID0gdHJ1ZTtcbiAgICB0aGlzLnZpc2libGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLl92aXNpYmxlO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZVdyYXBwZXIgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy53cmFwcGVyID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB0aGlzLndyYXBwZXIuaWQgPSB0aGlzLm9wdGlvbnMuaWQgfHwgKCdtdy1oYW5kbGUtJyArIG13LnJhbmRvbSgpKTtcbiAgICAgICAgdGhpcy53cmFwcGVyLmNsYXNzTmFtZSA9ICdtdy1kZWZhdWx0cyBtdy1oYW5kbGUtaXRlbSAnICsgKHRoaXMub3B0aW9ucy5jbGFzc05hbWUgfHwgJ213LWhhbmRsZS10eXBlLWRlZmF1bHQnKTtcbiAgICAgICAgdGhpcy53cmFwcGVyLmNvbnRlbnRlZGl0YWJsZSA9IGZhbHNlO1xuICAgICAgICBtdy4kKHRoaXMud3JhcHBlcikub24oJ21vdXNlZG93bicsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMsICdtdy1oYW5kbGUtaXRlbS1tb3VzZS1kb3duJyk7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKGRvY3VtZW50KS5vbignbW91c2V1cCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKHNjb3BlLndyYXBwZXIsICdtdy1oYW5kbGUtaXRlbS1tb3VzZS1kb3duJyk7XG4gICAgICAgIH0pO1xuICAgICAgICBtd2QuYm9keS5hcHBlbmRDaGlsZCh0aGlzLndyYXBwZXIpO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmNyZWF0ZVdyYXBwZXIoKTtcbiAgICAgICAgdGhpcy5jcmVhdGVIYW5kbGVyKCk7XG4gICAgICAgIHRoaXMuY3JlYXRlTWVudSgpO1xuICAgIH07XG5cbiAgICB0aGlzLnNldFRpdGxlID0gZnVuY3Rpb24gKGljb24sIHRpdGxlKSB7XG4gICAgICAgIHRoaXMuaGFuZGxlSWNvbi5pbm5lckhUTUwgPSBpY29uO1xuICAgICAgICB0aGlzLmhhbmRsZVRpdGxlLmlubmVySFRNTCA9IHRpdGxlO1xuICAgIH07XG5cbiAgICB0aGlzLmhpZGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13LiQodGhpcy53cmFwcGVyKS5oaWRlKCkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICB0aGlzLl92aXNpYmxlID0gZmFsc2U7XG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH07XG5cbiAgICB0aGlzLnNob3cgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13LiQodGhpcy53cmFwcGVyKS5zaG93KCk7XG4gICAgICAgIHRoaXMuX3Zpc2libGUgPSB0cnVlO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGVIYW5kbGVyID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy5oYW5kbGUgPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICB0aGlzLmhhbmRsZUljb24gPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICB0aGlzLmhhbmRsZVRpdGxlID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgdGhpcy5oYW5kbGUuY2xhc3NOYW1lID0gJ213LWhhbmRsZS1oYW5kbGVyJztcbiAgICAgICAgdGhpcy5oYW5kbGVJY29uLmNsYXNzTmFtZSA9ICdtdy1oYW5kbGUtaGFuZGxlci1pY29uJztcbiAgICAgICAgdGhpcy5oYW5kbGVUaXRsZS5jbGFzc05hbWUgPSAnbXctaGFuZGxlLWhhbmRsZXItdGl0bGUnO1xuXG4gICAgICAgIHRoaXMuaGFuZGxlLmFwcGVuZENoaWxkKHRoaXMuaGFuZGxlSWNvbik7XG4gICAgICAgIHRoaXMuaGFuZGxlLmFwcGVuZENoaWxkKHRoaXMuaGFuZGxlVGl0bGUpO1xuICAgICAgICB0aGlzLndyYXBwZXIuYXBwZW5kQ2hpbGQodGhpcy5oYW5kbGUpO1xuXG4gICAgICAgIHRoaXMuaGFuZGxlVGl0bGUub25jbGljayA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LiQoc2NvcGUud3JhcHBlcikudG9nZ2xlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICB9O1xuICAgICAgICBtdy4kKG13ZC5ib2R5KS5vbignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgaWYoIW13LnRvb2xzLmhhc1BhcmVudFdpdGhJZChlLnRhcmdldCwgc2NvcGUud3JhcHBlci5pZCkpe1xuICAgICAgICAgICAgICAgIG13LiQoc2NvcGUud3JhcHBlcikucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5tZW51QnV0dG9uID0gZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgdmFyIGJ0biA9IG13ZC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgIGJ0bi5jbGFzc05hbWUgPSAnbXctaGFuZGxlLW1lbnUtaXRlbSc7XG4gICAgICAgIGlmKGRhdGEuaWNvbikge1xuICAgICAgICAgICAgdmFyIGljb24gPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgaWNvbi5jbGFzc05hbWUgPSBkYXRhLmljb24gKyAnIG13LWhhbmRsZS1tZW51LWl0ZW0taWNvbic7XG4gICAgICAgICAgICBidG4uYXBwZW5kQ2hpbGQoaWNvbik7XG4gICAgICAgIH1cbiAgICAgICAgYnRuLmFwcGVuZENoaWxkKG13ZC5jcmVhdGVUZXh0Tm9kZShkYXRhLnRpdGxlKSk7XG4gICAgICAgIGlmKGRhdGEuY2xhc3NOYW1lKXtcbiAgICAgICAgICAgIGJ0bi5jbGFzc05hbWUgKz0gKCcgJyArIGRhdGEuY2xhc3NOYW1lKTtcbiAgICAgICAgfVxuICAgICAgICBpZihkYXRhLmlkKXtcbiAgICAgICAgICAgIGJ0bi5pZCA9IGRhdGEuaWQ7XG4gICAgICAgIH1cbiAgICAgICAgaWYoZGF0YS5hY3Rpb24pe1xuICAgICAgICAgICAgYnRuLm9ubW91c2Vkb3duID0gZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgYnRuLm9uY2xpY2sgPSBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBkYXRhLmFjdGlvbi5jYWxsKHNjb3BlLCBlLCB0aGlzLCBkYXRhKTtcbiAgICAgICAgICAgIH07XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGJ0bjtcbiAgICB9O1xuXG4gICAgdGhpcy5fZGVmYXVsdEJ1dHRvbnMgPSBbXG5cbiAgICBdO1xuXG4gICAgdGhpcy5jcmVhdGVNZW51RHluYW1pY0hvbGRlciA9IGZ1bmN0aW9uKGl0ZW0pe1xuICAgICAgICB2YXIgZG4gPSBtd2QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGRuLmNsYXNzTmFtZSA9ICdtdy1oYW5kbGUtbWVudS1keW5hbWljJyArIChpdGVtLmNsYXNzTmFtZSA/ICcgJyArIGl0ZW0uY2xhc3NOYW1lIDogJycpO1xuICAgICAgICByZXR1cm4gZG47XG4gICAgfTtcbiAgICB0aGlzLmNyZWF0ZU1lbnUgPSBmdW5jdGlvbigpe1xuICAgICAgICB0aGlzLm1lbnUgPSBtd2QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHRoaXMubWVudS5jbGFzc05hbWUgPSAnbXctaGFuZGxlLW1lbnUgJyArICh0aGlzLm9wdGlvbnMubWVudUNsYXNzID8gdGhpcy5vcHRpb25zLm1lbnVDbGFzcyA6ICdtdy1oYW5kbGUtbWVudS1kZWZhdWx0Jyk7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMubWVudSkge1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCB0aGlzLm9wdGlvbnMubWVudS5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmKHRoaXMub3B0aW9ucy5tZW51W2ldLnRpdGxlICE9PSAne2R5bmFtaWN9Jykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm1lbnUuYXBwZW5kQ2hpbGQodGhpcy5tZW51QnV0dG9uKHRoaXMub3B0aW9ucy5tZW51W2ldKSkgO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tZW51LmFwcGVuZENoaWxkKHRoaXMuY3JlYXRlTWVudUR5bmFtaWNIb2xkZXIodGhpcy5vcHRpb25zLm1lbnVbaV0pKSA7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy53cmFwcGVyLmFwcGVuZENoaWxkKHRoaXMubWVudSk7XG4gICAgfTtcbiAgICB0aGlzLmNyZWF0ZSgpO1xuICAgIHRoaXMuaGlkZSgpO1xufTtcblxubXcuX2FjdGl2ZU1vZHVsZU92ZXIgPSB7XG4gICAgbW9kdWxlOiBudWxsLFxuICAgIGVsZW1lbnQ6IG51bGxcbn07XG5cbm13Ll9pbml0SGFuZGxlcyA9IHtcbiAgICBnZXROb2RlSGFuZGxlcjpmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICBpZihtdy5fYWN0aXZlRWxlbWVudE92ZXIgPT09IG5vZGUpe1xuICAgICAgICAgICAgcmV0dXJuIG13LmhhbmRsZUVsZW1lbnRcbiAgICAgICAgfSBlbHNlIGlmKG13Ll9hY3RpdmVNb2R1bGVPdmVyID09PSBub2RlKSB7XG4gICAgICAgICAgICByZXR1cm4gbXcuaGFuZGxlTW9kdWxlXG4gICAgICAgIH0gZWxzZSBpZihtdy5fYWN0aXZlUm93T3ZlciA9PT0gbm9kZSkge1xuICAgICAgICAgICAgcmV0dXJuIG13LmhhbmRsZUNvbHVtbnM7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGdldEFsbE5vZGVzOiBmdW5jdGlvbiAoYnV0KSB7XG4gICAgICAgIHZhciBhbGwgPSBbXG4gICAgICAgICAgICBtdy5fYWN0aXZlTW9kdWxlT3ZlcixcbiAgICAgICAgICAgIG13Ll9hY3RpdmVSb3dPdmVyLFxuICAgICAgICAgICAgbXcuX2FjdGl2ZUVsZW1lbnRPdmVyXG4gICAgICAgIF07XG4gICAgICAgIGFsbCA9IGFsbC5maWx0ZXIoZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgIHJldHVybiAhIWl0ZW0gJiYgaXRlbS5ub2RlVHlwZSA9PT0gMTtcbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiBhbGw7XG4gICAgfSxcbiAgICBnZXRBbGw6IGZ1bmN0aW9uIChidXQpIHtcbiAgICAgICAgdmFyIGFsbCA9IFtcbiAgICAgICAgICAgIG13LmhhbmRsZU1vZHVsZSxcbiAgICAgICAgICAgIG13LmhhbmRsZUNvbHVtbnMsXG4gICAgICAgICAgICBtdy5oYW5kbGVFbGVtZW50XG4gICAgICAgIF07XG4gICAgICAgIGFsbCA9IGJ1dCA/IGFsbC5maWx0ZXIoZnVuY3Rpb24gKHgpIHtcbiAgICAgICAgICAgIHJldHVybiB4ICE9PSBidXQ7XG4gICAgICAgIH0pIDogIGFsbDtcbiAgICAgICAgcmV0dXJuIGFsbC5maWx0ZXIoZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgIGlmKGl0ZW0pe1xuICAgICAgICAgICAgICAgIHJldHVybiBpdGVtLnZpc2libGUoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGhpZGVBbGw6ZnVuY3Rpb24gKGJ1dCkge1xuICAgICAgICB0aGlzLmdldEFsbChidXQpLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgIGl0ZW0uaGlkZSgpO1xuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGNvbGxpZGU6IGZ1bmN0aW9uKGEsIGIpIHtcbiAgICAgICAgcmV0dXJuICEoXG4gICAgICAgICAgICAoKGEueSArIGEuaGVpZ2h0KSA8IChiLnkpKSB8fFxuICAgICAgICAgICAgKGEueSA+IChiLnkgKyBiLmhlaWdodCkpIHx8XG4gICAgICAgICAgICAoKGEueCArIGEud2lkdGgpIDwgYi54KSB8fFxuICAgICAgICAgICAgKGEueCA+IChiLnggKyBiLndpZHRoKSlcbiAgICAgICAgKTtcbiAgICB9LFxuICAgIF9tYW5hZ2VDb2xsaXNpb246IGZhbHNlLFxuICAgIG1hbmFnZUNvbGxpc2lvbjpmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcyxcbiAgICAgICAgICAgIG1heCA9IDM1LFxuICAgICAgICAgICAgc2tpcCA9IFtdO1xuXG4gICAgICAgIHNjb3BlLmdldEFsbCgpLmZvckVhY2goZnVuY3Rpb24gKGN1cnIpIHtcbiAgICAgICAgICAgIHZhciBtYXN0ZXIgPSBjdXJyLCBtYXN0ZXJSZWN0O1xuICAgICAgICAgICAgLy9pZiAoc2tpcC5pbmRleE9mKG1hc3RlcikgPT09IC0xKXtcbiAgICAgICAgICAgIHNjb3BlLmdldEFsbChjdXJyKS5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgbWFzdGVyUmVjdCA9IG1hc3Rlci53cmFwcGVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgICAgICAgICAgICAgIHZhciBpcmVjdCA9IGl0ZW0ud3JhcHBlci5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUuY29sbGlkZShtYXN0ZXJSZWN0LCBpcmVjdCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2tpcC5wdXNoKGl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICB2YXIgdG9wTW9yZSA9IGl0ZW0gPT09IG13LmhhbmRsZUVsZW1lbnQgPyAxMCA6IDA7XG4gICAgICAgICAgICAgICAgICAgIGl0ZW0ud3JhcHBlci5zdHlsZS50b3AgPSAocGFyc2VJbnQobWFzdGVyLndyYXBwZXIuc3R5bGUudG9wLCAxMCkgKyB0b3BNb3JlKSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgIGl0ZW0ud3JhcHBlci5zdHlsZS5sZWZ0ID0gKChwYXJzZUludChtYXN0ZXIud3JhcHBlci5zdHlsZS5sZWZ0LCAxMCkgKyBtYXN0ZXJSZWN0LndpZHRoKSArIDEwKSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgIG1hc3RlciA9IGN1cnI7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHZhciBjbG9uZXIgPSBtd2QucXVlcnlTZWxlY3RvcignLm13LWNsb25lYWJsZS1jb250cm9sJyk7XG4gICAgICAgIGlmKGNsb25lcikge1xuICAgICAgICAgICAgc2NvcGUuZ2V0QWxsKCkuZm9yRWFjaChmdW5jdGlvbiAoY3Vycikge1xuICAgICAgICAgICAgICAgIG1hc3RlclJlY3QgPSBjdXJyLndyYXBwZXIuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgICAgICAgICAgICAgdmFyIGNsb25lcmVjdCA9IGNsb25lci5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcblxuICAgICAgICAgICAgICAgIGlmIChzY29wZS5jb2xsaWRlKG1hc3RlclJlY3QsIGNsb25lcmVjdCkpIHtcbiAgICAgICAgICAgICAgICAgICAgY2xvbmVyLnN0eWxlLnRvcCA9IGN1cnIud3JhcHBlci5zdHlsZS50b3A7XG4gICAgICAgICAgICAgICAgICAgIGNsb25lci5zdHlsZS5sZWZ0ID0gKChwYXJzZUludChjdXJyLndyYXBwZXIuc3R5bGUubGVmdCwgMTApICsgbWFzdGVyUmVjdC53aWR0aCkgKyAxMCkgKyAncHgnO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIGVsZW1lbnRzOiBmdW5jdGlvbigpe1xuICAgICAgICBtdy5oYW5kbGVFbGVtZW50ID0gbmV3IG13LkhhbmRsZSh7XG4gICAgICAgICAgICBpZDogJ213LWhhbmRsZS1pdGVtLWVsZW1lbnQnLFxuICAgICAgICAgICAgY2xhc3NOYW1lOiAnbXctaGFuZGxlLXR5cGUtZWxlbWVudCcsXG4gICAgICAgICAgICBtZW51OiBbXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ0VkaXQgSFRNTCcsXG4gICAgICAgICAgICAgICAgICAgIGljb246ICdtdy1pY29uLWNvZGUnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmVkaXRTb3VyY2UobXcuX2FjdGl2ZUVsZW1lbnRPdmVyKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ0VkaXQgU3R5bGUnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1lZGl0JyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNldHRpbmdzLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnNpZGViYXJTZXR0aW5nc1RhYnMuc2V0KDMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYobXcuY3NzRWRpdG9yU2VsZWN0b3Ipe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3IuYWN0aXZlKHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3Iuc2VsZWN0KG13Ll9hY3RpdmVFbGVtZW50T3Zlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2V7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChtdy5saXZlRWRpdFdpZGdldHMuY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uKCkpLm9uKCdsb2FkJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLmFjdGl2ZSh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3Iuc2VsZWN0KG13Ll9hY3RpdmVFbGVtZW50T3Zlcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0sIDMzMyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFdpZGdldHMuY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6ICdSZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1iaW4nLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213LWhhbmRsZS1yZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcuZGVsZXRlX2VsZW1lbnQobXcuX2FjdGl2ZUVsZW1lbnRPdmVyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmhhbmRsZUVsZW1lbnQuaGlkZSgpXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBdXG4gICAgICAgIH0pO1xuXG4gICAgICAgIG13LiQobXcuaGFuZGxlRWxlbWVudC53cmFwcGVyKS5kcmFnZ2FibGUoe1xuICAgICAgICAgICAgaGFuZGxlOiBtdy5oYW5kbGVFbGVtZW50LmhhbmRsZUljb24sXG4gICAgICAgICAgICBjdXJzb3JBdDoge1xuICAgICAgICAgICAgICAgIC8vdG9wOiAtMzBcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBzdGFydDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgbXcuaXNEcmFnID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnQ3VycmVudCA9IG13LmVhLmRhdGEuY3VycmVudEdyYWJiZWQgPSBtdy5fYWN0aXZlRWxlbWVudE92ZXI7XG5cbiAgICAgICAgICAgICAgICBoYW5kbGVEb210cmVlU3luYy5zdGFydCA9IG13LmRyYWdDdXJyZW50LnBhcmVudE5vZGU7XG5cbiAgICAgICAgICAgICAgICBpZighbXcuZHJhZ0N1cnJlbnQuaWQpe1xuICAgICAgICAgICAgICAgICAgICBtdy5kcmFnQ3VycmVudC5pZCA9ICdlbGVtZW50XycgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcuJChtdy5kcmFnQ3VycmVudCkuaW52aXNpYmxlKCkuYWRkQ2xhc3MoXCJtd19kcmFnX2N1cnJlbnRcIik7XG4gICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkFsbExlYXZlXCIpO1xuICAgICAgICAgICAgICAgIG13LmRyYWcuZml4X3BsYWNlaG9sZGVycygpO1xuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLmFkZENsYXNzKFwiZHJhZ1N0YXJ0XCIpO1xuICAgICAgICAgICAgICAgIG13LmltYWdlX3Jlc2l6ZXIuX2hpZGUoKTtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5kcmFnQ3VycmVudCk7XG4gICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKFwidmlzaWJpbGl0eVwiLCBcImhpZGRlblwiKTtcbiAgICAgICAgICAgICAgICBtdy5zbWFsbEVkaXRvckNhbmNlbGVkID0gdHJ1ZTtcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBzdG9wOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5yZW1vdmVDbGFzcyhcImRyYWdTdGFydFwiKTtcblxuICAgICAgICAgICAgICAgIGlmKG13LmxpdmVFZGl0RG9tVHJlZSkge1xuICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdERvbVRyZWUucmVmcmVzaChoYW5kbGVEb210cmVlU3luYy5zdGFydClcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIG13LiQobXcuaGFuZGxlRWxlbWVudC53cmFwcGVyKS5tb3VzZWVudGVyKGZ1bmN0aW9uKCkge1xuICAgICAgICB9KS5jbGljayhmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIGlmICghJChtdy5fYWN0aXZlRWxlbWVudE92ZXIpLmhhc0NsYXNzKFwiZWxlbWVudC1jdXJyZW50XCIpKSB7XG4gICAgICAgICAgICAgICAgbXcuJChcIi5lbGVtZW50LWN1cnJlbnRcIikucmVtb3ZlQ2xhc3MoXCJlbGVtZW50LWN1cnJlbnRcIik7XG5cbiAgICAgICAgICAgICAgICBpZiAobXcuX2FjdGl2ZUVsZW1lbnRPdmVyLm5vZGVOYW1lID09PSAnSU1HJykge1xuXG4gICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJJbWFnZUNsaWNrXCIsIG13Ll9hY3RpdmVFbGVtZW50T3Zlcik7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkVsZW1lbnRDbGlja1wiLCBtdy5fYWN0aXZlRWxlbWVudE92ZXIpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9KTtcblxuICAgICAgICBtdy5vbihcIkVsZW1lbnRPdmVyXCIsIGZ1bmN0aW9uKGEsIGVsZW1lbnQpIHtcbiAgICAgICAgICAgIG13Ll9hY3RpdmVFbGVtZW50T3ZlciA9IGVsZW1lbnQ7XG4gICAgICAgICAgICBtdy4kKFwiLm13X2VkaXRfZGVsZXRlLCAubXdfZWRpdF9kZWxldGVfZWxlbWVudCwgLm13LXNvcnRoYW5kbGUtbW92ZWl0LCAuY29sdW1uX3NlcGFyYXRvcl90aXRsZVwiKS5zaG93KCk7XG4gICAgICAgICAgICBpZiAoIW13LmVhLmNhbkRyb3AoZWxlbWVudCkpIHtcbiAgICAgICAgICAgICAgICBtdy4kKFwiLm13X2VkaXRfZGVsZXRlLCAubXdfZWRpdF9kZWxldGVfZWxlbWVudCwgLm13LXNvcnRoYW5kbGUtbW92ZWl0LCAuY29sdW1uX3NlcGFyYXRvcl90aXRsZVwiKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGVsID0gbXcuJChlbGVtZW50KTtcblxuICAgICAgICAgICAgdmFyIG8gPSBlbC5vZmZzZXQoKTtcblxuICAgICAgICAgICAgdmFyIHBsZWZ0ID0gcGFyc2VGbG9hdChlbC5jc3MoXCJwYWRkaW5nTGVmdFwiKSk7XG4gICAgICAgICAgICB2YXIgbGVmdF9zcGFjaW5nID0gby5sZWZ0O1xuICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGVsZW1lbnQsICdqdW1ib3Ryb24nKSkge1xuICAgICAgICAgICAgICAgIGxlZnRfc3BhY2luZyA9IGxlZnRfc3BhY2luZyArIHBsZWZ0O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYobGVmdF9zcGFjaW5nPDApe1xuICAgICAgICAgICAgICAgIGxlZnRfc3BhY2luZyA9IDA7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAvL3RvZG86IGFub3RoZXIgaWNvblxuICAgICAgICAgICAgdmFyIGlzU2FmZSA9IGZhbHNlOyAvLyBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KGVsZW1lbnQsIFsnc2FmZS1tb2RlJywgJ3JlZ3VsYXItbW9kZSddKTtcbiAgICAgICAgICAgIHZhciBfaWNvbiA9IGlzU2FmZSA/ICc8c3ZnICB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgdmlld0JveD1cIjAgMCA1MDQuMDMgNDQwXCIgaGVpZ2h0PVwiMTdcIiBjbGFzcz1cInNhZmUtZWxlbWVudC1zdmdcIj48cGF0aCBmaWxsPVwiZ3JlZW5cIiBkPVwiTTI1MiwyLjg5QzE3OC43LDIuODksMTAyLjQsMTkuNDQsMTAyLjQsMTkuNDRBMzEuODUsMzEuODUsMCwwLDAsNzYuNzYsNTAuNjl2OTUuNTljMCwxNjUuNjcsMTU5LjcsMjM0Ljg4LDE1OS43LDIzNC44OEEzMS42NSwzMS42NSwwLDAsMCwyNTIsMzg1LjI3YTMyLjA1LDMyLjA1LDAsMCwwLDE1LjU2LTQuMTFjLjA2LDAsMTU5LjY5LTY5LjIxLDE1OS42OS0yMzQuODhWNTAuNjlhMzEuODIsMzEuODIsMCwwLDAtMjUuNjQtMzEuMjVTMzI1LjMzLDIuODksMjUyLDIuODlabTk1LjU5LDk1LjU5YTE1Ljk0LDE1Ljk0LDAsMCwxLDExLjI2LDI3LjJMMjM4LjQ1LDI0Ni4xMWExNiwxNiwwLDAsMS0xMS4zMyw0LjczLDE1LjYxLDE1LjYxLDAsMCwxLTExLjItNC43M2wtNTUtNTVhMTUuOTMsMTUuOTMsMCwwLDEsMjIuNTMtMjIuNTNsNDMuNjksNDMuODJMMzM2LjM0LDEwMy4xNWExNiwxNiwwLDAsMSwxMS4yNy00LjY3Wm0wLDBcIi8+PC9zdmc+JyA6ICc8c3BhbiBjbGFzcz1cIm13LWljb24tZHJhZ1wiPjwvc3Bhbj4nO1xuXG4gICAgICAgICAgICB2YXIgaWNvbiA9ICc8c3BhbiBjbGFzcz1cIm13LWhhbmRsZS1lbGVtZW50LXRpdGxlLWljb24gJysoaXNTYWZlID8gJ3RpcCcgOiAnJykrJ1wiICAnKyhpc1NhZmUgPyAnIGRhdGEtdGlwPVwiQ3VycmVudCBlbGVtZW50IGlzIHByb3RlY3RlZCBcXG4gIGZyb20gYWNjaWRlbnRhbCBkZWxldGlvblwiIGRhdGEtdGlwcG9zaXRpb249XCJ0b3AtbGVmdFwiJyA6ICcnKSsnID4nKyBfaWNvbiArJzwvc3Bhbj4nO1xuXG4gICAgICAgICAgICB2YXIgdGl0bGUgPSAnU2V0dGluZ3MnO1xuXG4gICAgICAgICAgICBtdy5oYW5kbGVFbGVtZW50LnNldFRpdGxlKGljb24sIHRpdGxlKTtcblxuICAgICAgICAgICAgaWYoZWwuaGFzQ2xhc3MoJ2FsbG93LWRyb3AnKSl7XG4gICAgICAgICAgICAgICAgbXcuaGFuZGxlRWxlbWVudC5oaWRlKCk7XG4gICAgICAgICAgICB9IGVsc2V7XG4gICAgICAgICAgICAgICAgbXcuaGFuZGxlRWxlbWVudC5zaG93KCk7XG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgbXcuJChtdy5oYW5kbGVFbGVtZW50LndyYXBwZXIpLmNzcyh7XG4gICAgICAgICAgICAgICAgdG9wOiBvLnRvcCAtIDEwLFxuICAgICAgICAgICAgICAgIGxlZnQ6IGxlZnRfc3BhY2luZ1xuICAgICAgICAgICAgfSkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuXG4gICAgICAgICAgICBpZighZWxlbWVudC5pZCkge1xuICAgICAgICAgICAgICAgIGVsZW1lbnQuaWQgPSBcImVsZW1lbnRfXCIgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgbXcuZHJvcGFibGUucmVtb3ZlQ2xhc3MoXCJtd19kcm9wYWJsZV9vbmxlYXZlZWRpdFwiKTtcbiAgICAgICAgICAgIG13Ll9pbml0SGFuZGxlcy5tYW5hZ2VDb2xsaXNpb24oKTtcblxuICAgICAgICB9KTtcblxuXG4gICAgfSxcbiAgICBtb2R1bGVzOiBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgdmFyIGhhbmRsZXNNb2R1bGVDb25maWcgPSB7XG4gICAgICAgICAgICBpZDogJ213LWhhbmRsZS1pdGVtLW1vZHVsZScsXG4gICAgICAgICAgICBtZW51OltcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnU2V0dGluZ3MnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1nZWFyJyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLm1vZHVsZV9zZXR0aW5ncyhtdy5fYWN0aXZlTW9kdWxlT3ZlcixcImFkbWluXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuaGFuZGxlTW9kdWxlLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ01vdmUgVXAnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1hcnJvdy11cC1iJyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX3VwJyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLnJlcGxhY2UoJChtdy5fYWN0aXZlTW9kdWxlT3ZlciksICdwcmV2Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5oYW5kbGVNb2R1bGUuaGlkZSgpXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6ICdNb3ZlIERvd24nLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1hcnJvdy1kb3duLWInLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213X2hhbmRsZV9tb2R1bGVfZG93bicsXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5yZXBsYWNlKCQobXcuX2FjdGl2ZU1vZHVsZU92ZXIpLCAnbmV4dCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuaGFuZGxlTW9kdWxlLmhpZGUoKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAne2R5bmFtaWN9JyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX3N1Ym1vZHVsZXMnXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAne2R5bmFtaWN9JyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX3NwYWNpbmcnXG4gICAgICAgICAgICAgICAgfSxcblxuXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ1Jlc2V0JyxcbiAgICAgICAgICAgICAgICAgICAgaWNvbjogJ213LWljb24tcmVsb2FkJyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtdy1oYW5kbGUtcmVtb3ZlJyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihtdy5fYWN0aXZlTW9kdWxlT3ZlciAmJiBtdy5fYWN0aXZlTW9kdWxlT3Zlci5pZCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuY29uZmlybV9yZXNldF9tb2R1bGVfYnlfaWQobXcuX2FjdGl2ZU1vZHVsZU92ZXIuaWQpXG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6ICdSZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1iaW4nLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213LWhhbmRsZS1yZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcuZGVsZXRlX2VsZW1lbnQobXcuX2FjdGl2ZU1vZHVsZU92ZXIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuaGFuZGxlTW9kdWxlLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIF1cbiAgICAgICAgfTtcbiAgICAgICAgdmFyIGhhbmRsZXNNb2R1bGVDb25maWdBY3RpdmUgPSB7XG4gICAgICAgICAgICBpZDogJ213LWhhbmRsZS1pdGVtLW1vZHVsZS1hY3RpdmUnLFxuICAgICAgICAgICAgbWVudTpbXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ1NldHRpbmdzJyxcbiAgICAgICAgICAgICAgICAgICAgaWNvbjogJ213LWljb24tZ2VhcicsXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5tb2R1bGVfc2V0dGluZ3MoZ2V0QWN0aXZlRHJhZ0N1cnJlbnQoKSxcImFkbWluXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJChtdy5oYW5kbGVNb2R1bGVBY3RpdmUud3JhcHBlcikucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnTW92ZSBVcCcsXG4gICAgICAgICAgICAgICAgICAgIGljb246ICdtdy1pY29uLWFycm93LXVwLWInLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213X2hhbmRsZV9tb2R1bGVfdXAnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcucmVwbGFjZSgkKGdldEFjdGl2ZURyYWdDdXJyZW50KCkpLCAncHJldicpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnTW92ZSBEb3duJyxcbiAgICAgICAgICAgICAgICAgICAgaWNvbjogJ213LWljb24tYXJyb3ctZG93bi1iJyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX2Rvd24nLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcucmVwbGFjZSgkKGdldEFjdGl2ZURyYWdDdXJyZW50KCkpLCAnbmV4dCcpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAne2R5bmFtaWN9JyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX3N1Ym1vZHVsZXMnXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAne2R5bmFtaWN9JyxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOidtd19oYW5kbGVfbW9kdWxlX3NwYWNpbmcnXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnUmVzZXQnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1yZWxvYWQnLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213LWhhbmRsZS1yZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKG13Ll9hY3RpdmVNb2R1bGVPdmVyICYmIG13Ll9hY3RpdmVNb2R1bGVPdmVyLmlkKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5jb25maXJtX3Jlc2V0X21vZHVsZV9ieV9pZChtdy5fYWN0aXZlTW9kdWxlT3Zlci5pZClcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ1JlbW92ZScsXG4gICAgICAgICAgICAgICAgICAgIGljb246ICdtdy1pY29uLWJpbicsXG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTonbXctaGFuZGxlLXJlbW92ZScsXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5kZWxldGVfZWxlbWVudChnZXRBY3RpdmVEcmFnQ3VycmVudCgpKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmhhbmRsZU1vZHVsZUFjdGl2ZS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICBdXG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGdldEFjdGl2ZURyYWdDdXJyZW50ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgLy92YXIgZWwgPSBtdy5saXZlRWRpdFNlbGVjdG9yICYmIG13LmxpdmVFZGl0U2VsZWN0b3Iuc2VsZWN0ZWQgPyAgbXcubGl2ZUVkaXRTZWxlY3Rvci5zZWxlY3RlZFswXSA6IG51bGw7XG4gICAgICAgICAgICB2YXIgZWwgPSBtdy5saXZlRWRpdFNlbGVjdG9yLmFjdGl2ZU1vZHVsZTtcbiAgICAgICAgICAgIGlmIChlbCAmJiBlbC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKG13LmhhbmRsZU1vZHVsZUFjdGl2ZS5fdGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIG13LmhhbmRsZU1vZHVsZUFjdGl2ZS5fdGFyZ2V0O1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBnZXREcmFnQ3VycmVudCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKG13Ll9hY3RpdmVNb2R1bGVPdmVyKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gbXcuX2FjdGl2ZU1vZHVsZU92ZXI7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIHZhciBkcmFnQ29uZmlnID0gZnVuY3Rpb24gKGN1cnIsIGhhbmRsZSkge1xuICAgICAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgICAgICBoYW5kbGU6IGhhbmRsZS5oYW5kbGVJY29uLFxuICAgICAgICAgICAgICAgIGRpc3RhbmNlOjIwLFxuICAgICAgICAgICAgICAgIGN1cnNvckF0OiB7XG4gICAgICAgICAgICAgICAgICAgIC8vdG9wOiAtMzBcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHN0YXJ0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuaXNEcmFnID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgbXcuZHJhZ0N1cnJlbnQgPSBjdXJyKCk7XG4gICAgICAgICAgICAgICAgICAgIGhhbmRsZURvbXRyZWVTeW5jLnN0YXJ0ID0gbXcuZHJhZ0N1cnJlbnQucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFtdy5kcmFnQ3VycmVudC5pZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZ0N1cnJlbnQuaWQgPSAnbW9kdWxlXycgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBpZihtdy5saXZlRWRpdFRvb2xzLmlzTGF5b3V0KG13LmRyYWdDdXJyZW50KSl7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG13LmRyYWdDdXJyZW50KS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9wYWNpdHk6MFxuICAgICAgICAgICAgICAgICAgICAgICAgfSkuYWRkQ2xhc3MoXCJtd19kcmFnX2N1cnJlbnRcIik7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG13LmRyYWdDdXJyZW50KS5pbnZpc2libGUoKS5hZGRDbGFzcyhcIm13X2RyYWdfY3VycmVudFwiKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJBbGxMZWF2ZVwiKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5maXhfcGxhY2Vob2xkZXJzKCk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLmFkZENsYXNzKFwiZHJhZ1N0YXJ0XCIpO1xuICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKG13LmRyYWdDdXJyZW50KTtcbiAgICAgICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKFwidmlzaWJpbGl0eVwiLCBcImhpZGRlblwiKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3JDYW5jZWxlZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBzdG9wOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoXCJkcmFnU3RhcnRcIik7XG4gICAgICAgICAgICAgICAgICAgIGlmKG13LmxpdmVFZGl0RG9tVHJlZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXREb21UcmVlLnJlZnJlc2goaGFuZGxlRG9tdHJlZVN5bmMuc3RhcnQpXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9O1xuICAgICAgICB9O1xuXG4gICAgICAgIG13LmhhbmRsZU1vZHVsZSA9IG5ldyBtdy5IYW5kbGUoaGFuZGxlc01vZHVsZUNvbmZpZyk7XG4gICAgICAgIG13LmhhbmRsZU1vZHVsZUFjdGl2ZSA9IG5ldyBtdy5IYW5kbGUoaGFuZGxlc01vZHVsZUNvbmZpZ0FjdGl2ZSk7XG5cbiAgICAgICAgbXcuaGFuZGxlTW9kdWxlLnR5cGUgPSAnaG92ZXInO1xuICAgICAgICBtdy5oYW5kbGVNb2R1bGVBY3RpdmUudHlwZSA9ICdhY3RpdmUnO1xuXG4gICAgICAgIG13LmhhbmRsZU1vZHVsZS5faGlkZVRpbWUgPSBudWxsO1xuICAgICAgICBtd1xuICAgICAgICAgICAgLiQobXcuaGFuZGxlTW9kdWxlLndyYXBwZXIpXG4gICAgICAgICAgICAuZHJhZ2dhYmxlKGRyYWdDb25maWcoZ2V0RHJhZ0N1cnJlbnQsIG13LmhhbmRsZU1vZHVsZSkpXG4gICAgICAgICAgICAub24oXCJtb3VzZWRvd25cIiwgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3RNb2RlID0gJ25vbmUnO1xuICAgICAgICAgICAgfSk7XG5cblxuICAgICAgICBtd1xuICAgICAgICAgICAgLiQobXcuaGFuZGxlTW9kdWxlQWN0aXZlLndyYXBwZXIpXG4gICAgICAgICAgICAuZHJhZ2dhYmxlKGRyYWdDb25maWcoZ2V0QWN0aXZlRHJhZ0N1cnJlbnQsIG13LmhhbmRsZU1vZHVsZUFjdGl2ZSkpXG4gICAgICAgICAgICAub24oXCJtb3VzZWRvd25cIiwgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3RNb2RlID0gJ25vbmUnO1xuICAgICAgICAgICAgfSk7XG5cblxuICAgICAgICB2YXIgcG9zaXRpb25Nb2R1bGVIYW5kbGUgPSBmdW5jdGlvbihlLCBwZWxlbWVudCwgaGFuZGxlKXtcblxuXG4gICAgICAgICAgICB2YXIgZWxlbWVudCA7XG5cbiAgICAgICAgICAgIGlmKGhhbmRsZS50eXBlID09PSAnaG92ZXInKSB7XG4gICAgICAgICAgICAgICAgZWxlbWVudCA9IGR5bmFtaWNNb2R1bGVzTWVudShlLCBwZWxlbWVudCkgfHwgcGVsZW1lbnQ7XG4gICAgICAgICAgICAgICAgbXcuX2FjdGl2ZU1vZHVsZU92ZXIgPSBlbGVtZW50O1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvL3BlbGVtZW50ID0gbXcudG9vbHMubGFzdE1hdGNoZXNPbk5vZGVPclBhcmVudChwZWxlbWVudCwgWycubW9kdWxlJ10pO1xuXG4gICAgICAgICAgICAgICAgZWxlbWVudCA9IGR5bmFtaWNNb2R1bGVzTWVudShlLCBwZWxlbWVudCkgfHwgcGVsZW1lbnQ7XG4gICAgICAgICAgICAgICAgaGFuZGxlLl90YXJnZXQgPSBwZWxlbWVudDtcbiAgICAgICAgICAgIH1cblxuXG5cbiAgICAgICAgICAgIG13LiQoXCIubXctaGFuZGxlLW1lbnUtZHluYW1pY1wiLCBoYW5kbGUud3JhcHBlcikuZW1wdHkoKTtcbiAgICAgICAgICAgIG13LiQoJy5td19oYW5kbGVfbW9kdWxlX3VwLC5td19oYW5kbGVfbW9kdWxlX2Rvd24nKS5oaWRlKCk7XG4gICAgICAgICAgICB2YXIgJGVsLCBoYXNlZGl0O1xuICAgICAgICAgICAgaWYoZWxlbWVudCAmJiBlbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS10eXBlJykgPT09ICdsYXlvdXRzJyl7XG4gICAgICAgICAgICAgICAgJGVsID0gbXcuJChlbGVtZW50KTtcbiAgICAgICAgICAgICAgICBoYXNlZGl0ID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdCgkZWxbMF0ucGFyZW50Tm9kZSxbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuXG4gICAgICAgICAgICAgICAgaWYoaGFzZWRpdCl7XG4gICAgICAgICAgICAgICAgICAgIGlmKCRlbC5wcmV2KCdbZGF0YS10eXBlPVwibGF5b3V0c1wiXScpWzBdKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoJy5td19oYW5kbGVfbW9kdWxlX3VwJykuc2hvdygpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmKCRlbC5uZXh0KCdbZGF0YS10eXBlPVwibGF5b3V0c1wiXScpWzBdKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoJy5td19oYW5kbGVfbW9kdWxlX2Rvd24nKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBlbCA9IG13LiQoZWxlbWVudCk7XG4gICAgICAgICAgICB2YXIgbyA9IGVsLm9mZnNldCgpO1xuICAgICAgICAgICAgdmFyIHdpZHRoID0gZWwud2lkdGgoKTtcbiAgICAgICAgICAgIHZhciBwbGVmdCA9IHBhcnNlRmxvYXQoZWwuY3NzKFwicGFkZGluZ0xlZnRcIikpO1xuXG4gICAgICAgICAgICB2YXIgbGViYXIgPSAgbXdkLnF1ZXJ5U2VsZWN0b3IoXCIjbGl2ZV9lZGl0X3Rvb2xiYXJcIik7XG4gICAgICAgICAgICB2YXIgbWluVG9wID0gbGViYXIgPyBsZWJhci5vZmZzZXRIZWlnaHQgOiAwO1xuICAgICAgICAgICAgaWYobXcudGVtcGxhdGVUb3BGaXhlZCkge1xuICAgICAgICAgICAgICAgIHZhciBleCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IobXcudGVtcGxhdGVUb3BGaXhlZCk7XG4gICAgICAgICAgICAgICAgaWYoZXggJiYgIWV4LmNvbnRhaW5zKGVsWzBdKSl7XG4gICAgICAgICAgICAgICAgICAgIG1pblRvcCArPSBleC5vZmZzZXRIZWlnaHQ7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB2YXIgbWFyZ2luVG9wID0gIDMwO1xuICAgICAgICAgICAgdmFyIHRvcFBvcyA9IG8udG9wO1xuXG4gICAgICAgICAgICBpZih0b3BQb3M8bWluVG9wKXtcbiAgICAgICAgICAgICAgICB0b3BQb3MgPSBtaW5Ub3A7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgd3MgPSBtdy4kKHdpbmRvdykuc2Nyb2xsVG9wKCk7XG4gICAgICAgICAgICBpZih0b3BQb3M8KHdzK21pblRvcCkpe1xuICAgICAgICAgICAgICAgIHRvcFBvcz0od3MrbWluVG9wKTtcbiAgICAgICAgICAgICAgICBtYXJnaW5Ub3AgPSAgLTE1O1xuICAgICAgICAgICAgICAgIGlmKGVsWzBdLm9mZnNldEhlaWdodCA8MTAwKXtcbiAgICAgICAgICAgICAgICAgICAgdG9wUG9zID0gby50b3ArZWxbMF0ub2Zmc2V0SGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3AgPSAgMDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBoYW5kbGVMZWZ0ID0gby5sZWZ0ICsgcGxlZnQ7XG4gICAgICAgICAgICBpZiAoaGFuZGxlTGVmdCA8IDApIHtcbiAgICAgICAgICAgICAgICBoYW5kbGVMZWZ0ID0gMDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIHRvcFBvc0ZpbmFsID0gdG9wUG9zICsgbWFyZ2luVG9wO1xuICAgICAgICAgICAgdmFyICRsZWJhciA9IG13LiQobGViYXIpLCAkbGVvZmYgPSAkbGViYXIub2Zmc2V0KCk7XG5cbiAgICAgICAgICAgIHZhciBvdXRoZWlnaHQgPSBlbC5vdXRlckhlaWdodCgpO1xuXG4gICAgICAgICAgICBpZih0b3BQb3NGaW5hbCA8ICgkbGVvZmYudG9wICsgJGxlYmFyLmhlaWdodCgpKSl7XG4gICAgICAgICAgICAgICAgdG9wUG9zRmluYWwgPSAoby50b3AgKyBvdXRoZWlnaHQpIC0gKG91dGhlaWdodCA+IDEwMCA/IDAgOiBoYW5kbGUud3JhcHBlci5jbGllbnRIZWlnaHQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZihlbC5hdHRyKCdkYXRhLXR5cGUnKSA9PT0gJ2xheW91dHMnKSB7XG4gICAgICAgICAgICAgICAgdG9wUG9zRmluYWwgPSBvLnRvcCArIDEwO1xuICAgICAgICAgICAgICAgIGhhbmRsZUxlZnQgPSBoYW5kbGVMZWZ0ICsgMTA7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNsZWFyVGltZW91dChoYW5kbGUuX2hpZGVUaW1lKTtcbiAgICAgICAgICAgIGhhbmRsZS5zaG93KCk7XG4gICAgICAgICAgICBtdy4kKGhhbmRsZS53cmFwcGVyKVxuICAgICAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnYWN0aXZlJylcbiAgICAgICAgICAgICAgICAuY3NzKHtcbiAgICAgICAgICAgICAgICAgICAgdG9wOiB0b3BQb3NGaW5hbCxcbiAgICAgICAgICAgICAgICAgICAgbGVmdDogaGFuZGxlTGVmdCxcbiAgICAgICAgICAgICAgICAgICAgLy93aWR0aDogd2lkdGgsXG4gICAgICAgICAgICAgICAgICAgIC8vbWFyZ2luVG9wOiBtYXJnaW5Ub3BcbiAgICAgICAgICAgICAgICB9KS5hZGRDbGFzcygnbXctYWN0aXZlLWl0ZW0nKTtcblxuXG5cblxuICAgICAgICAgICAgdmFyIGNhbkRyYWcgPSBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KGVsZW1lbnQucGFyZW50Tm9kZSwgWydlZGl0JywgJ21vZHVsZSddKVxuICAgICAgICAgICAgICAgICYmIG13LnRvb2xzLnBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3RPck5vbmUoZWxlbWVudCwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKTtcbiAgICAgICAgICAgIGlmKGNhbkRyYWcpe1xuICAgICAgICAgICAgICAgIG13LiQoaGFuZGxlLndyYXBwZXIpLnJlbW92ZUNsYXNzKCdtdy1oYW5kbGUtbm8tZHJhZycpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy4kKGhhbmRsZS53cmFwcGVyKS5hZGRDbGFzcygnbXctaGFuZGxlLW5vLWRyYWcnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHR5cGVvZihlbCkgPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0aXRsZSA9IGVsLmRhdGFzZXQoXCJtdy10aXRsZVwiKTtcbiAgICAgICAgICAgIHZhciBpZCA9IGVsLmF0dHIoXCJpZFwiKTtcblxuXG5cbiAgICAgICAgICAgIHZhciBtb2R1bGVfdHlwZSA9IChlbC5kYXRhc2V0KFwidHlwZVwiKSB8fCBlbC5hdHRyKFwidHlwZVwiKSk7XG4gICAgICAgICAgICBpZih0eXBlb2YobW9kdWxlX3R5cGUpID09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBjbG4gPSBlbFswXS5xdWVyeVNlbGVjdG9yKCcuY2xvbmVhYmxlJyk7XG4gICAgICAgICAgICBpZihjbG4gfHwgbXcudG9vbHMuaGFzQ2xhc3MoZWxbMF0sICdjbG9uZWFibGUnKSl7XG4gICAgICAgICAgICAgICAgaWYoKCQoY2xuKS5vZmZzZXQoKS50b3AgLSBlbC5vZmZzZXQoKS50b3ApIDwgMjApe1xuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wsICdtdy1tb2R1bGUtbmVhci1jbG9uZWFibGUnKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wsICdtdy1tb2R1bGUtbmVhci1jbG9uZWFibGUnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBtb2RfaWNvbiA9IG13LmxpdmVfZWRpdC5nZXRNb2R1bGVJY29uKG1vZHVsZV90eXBlKTtcbiAgICAgICAgICAgIHZhciBtb2RfaGFuZGxlX3RpdGxlID0gKHRpdGxlID8gdGl0bGUgOiBtdy5tc2cuc2V0dGluZ3MpO1xuICAgICAgICAgICAgLyppZihtb2R1bGVfdHlwZSA9PT0gJ2xheW91dHMnKXtcbiAgICAgICAgICAgICAgICBtb2RfaGFuZGxlX3RpdGxlID0gJyc7XG4gICAgICAgICAgICB9Ki9cblxuICAgICAgICAgICAgaGFuZGxlLnNldFRpdGxlKG1vZF9pY29uLCBtb2RfaGFuZGxlX3RpdGxlKTtcbiAgICAgICAgICAgIGlmKCFoYW5kbGUpe1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgbXcudG9vbHMuY2xhc3NOYW1lc3BhY2VEZWxldGUoaGFuZGxlLCAnbW9kdWxlLWFjdGl2ZS0nKTtcbiAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGhhbmRsZSwgJ21vZHVsZS1hY3RpdmUtJyArIG1vZHVsZV90eXBlLnJlcGxhY2UoL1xcLy9nLCAnLScpKTtcblxuICAgICAgICAgICAgaWYgKG13LmxpdmVfZWRpdF9tb2R1bGVfc2V0dGluZ3NfYXJyYXkgJiYgbXcubGl2ZV9lZGl0X21vZHVsZV9zZXR0aW5nc19hcnJheVttb2R1bGVfdHlwZV0pIHtcblxuICAgICAgICAgICAgICAgIHZhciBuZXdfZWwgPSBtd2QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICAgICAgbmV3X2VsLmNsYXNzTmFtZSA9ICdtd19lZGl0X3NldHRpbmdzX211bHRpcGxlX2hvbGRlcic7XG5cbiAgICAgICAgICAgICAgICB2YXIgc2V0dGluZ3MgPSBtdy5saXZlX2VkaXRfbW9kdWxlX3NldHRpbmdzX2FycmF5W21vZHVsZV90eXBlXTtcbiAgICAgICAgICAgICAgICBtdy4kKHNldHRpbmdzKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRoaXMudmlldykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5ld19lbCA9IG13ZC5jcmVhdGVFbGVtZW50KCdhJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdfZWwuY2xhc3NOYW1lID0gJ213X2VkaXRfc2V0dGluZ3NfbXVsdGlwbGUnO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV3X2VsLnRpdGxlID0gdGhpcy50aXRsZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld19lbC5kcmFnZ2FibGUgPSAnZmFsc2UnO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGJ0bl9pZCA9ICdtd19lZGl0X3NldHRpbmdzX211bHRpcGxlX2J0bl8nICsgbXcucmFuZG9tKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBuZXdfZWwuaWQgPSBidG5faWQ7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy50eXBlICYmIHRoaXMudHlwZSA9PT0gJ3Rvb2x0aXAnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmV3X2VsLmhyZWYgPSAnamF2YXNjcmlwdDptdy5kcmFnLmN1cnJlbnRfbW9kdWxlX3NldHRpbmdzX3Rvb2x0aXBfc2hvd19vbl9lbGVtZW50KFwiJyArIGJ0bl9pZCArICdcIixcIicgKyB0aGlzLnZpZXcgKyAnXCIsIFwidG9vbHRpcFwiKTsgdm9pZCgwKTsnO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld19lbC5ocmVmID0gJ2phdmFzY3JpcHQ6bXcuZHJhZy5tb2R1bGVfc2V0dGluZ3ModW5kZWZpbmVkLFwiJyArIHRoaXMudmlldyArICdcIik7IHZvaWQoMCk7JztcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpY29uID0gJyc7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5pY29uKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWNvbiA9ICc8aSBjbGFzcz1cIm13LWVkaXQtbW9kdWxlLXNldHRpbmdzLXRvb2x0aXAtaWNvbiAnICsgdGhpcy5pY29uICsgJ1wiPjwvaT4nO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3X2VsLmlubmVySFRNTCA9ICAoaWNvbiArICc8c3BhbiBjbGFzcz1cIm13LWVkaXQtbW9kdWxlLXNldHRpbmdzLXRvb2x0aXAtYnRuLXRpdGxlXCI+JyArIHRoaXMudGl0bGUrJzwvc3Bhbj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoXCIubXdfaGFuZGxlX21vZHVsZV9zcGFjaW5nXCIsIGhhbmRsZS53cmFwcGVyKS5hcHBlbmQobmV3X2VsKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cblxuXG4gICAgICAgICAgICBpZighZWxlbWVudC5pZCkge1xuICAgICAgICAgICAgICAgIGVsZW1lbnQuaWQgPSBcIm1vZHVsZV9cIiArIG13LnJhbmRvbSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuX2luaXRIYW5kbGVzLm1hbmFnZUNvbGxpc2lvbigpO1xuICAgICAgICB9O1xuXG4gICAgICAgIG13Lm9uKCdNb2R1bGVDbGljaycsIGZ1bmN0aW9uKGUsIHBlbGVtZW50KXtcbiAgICAgICAgICAgIHBvc2l0aW9uTW9kdWxlSGFuZGxlKGUsIHBlbGVtZW50LCBtdy5oYW5kbGVNb2R1bGVBY3RpdmUpO1xuICAgICAgICB9KTtcblxuICAgICAgICBtdy5vbignbW9kdWxlT3ZlcicsIGZ1bmN0aW9uIChlLCBwZWxlbWVudCkge1xuICAgICAgICAgICAgcG9zaXRpb25Nb2R1bGVIYW5kbGUoZSwgcGVsZW1lbnQsIG13LmhhbmRsZU1vZHVsZSk7XG4gICAgICAgICAgICBpZihtdy5fYWN0aXZlTW9kdWxlT3ZlciA9PT0gbXcuaGFuZGxlTW9kdWxlQWN0aXZlLl90YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBtdy5oYW5kbGVNb2R1bGUuaGlkZSgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB2YXIgbm9kZXMgPSBbXTtcbiAgICAgICAgICAgIG13LiQoJy5tb2R1bGUnLCBwZWxlbWVudCkuZWFjaChmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgICAgICB2YXIgdHlwZSA9IHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLXR5cGUnKTtcblxuICAgICAgICAgICAgICAgIHZhciBoYXN0aXRsZSA9IG13LmxpdmVfZWRpdC5yZWdpc3RyeVt0eXBlXSA/IG13LmxpdmVfZWRpdC5yZWdpc3RyeVt0eXBlXS50aXRsZSA6IGZhbHNlO1xuICAgICAgICAgICAgICAgIHZhciBpY29uID0gbXcubGl2ZV9lZGl0LmdldE1vZHVsZUljb24odHlwZSk7XG4gICAgICAgICAgICAgICAgaWYoIWljb24pe1xuICAgICAgICAgICAgICAgICAgICBpY29uICA9ICc8c3BhbiBjbGFzcz1cIm13LWljb24tZ2VhciBtdy1oYW5kbGUtbWVudS1pdGVtLWljb25cIj48L3NwYW4+JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKGhhc3RpdGxlKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBtZW51aXRlbSA9ICc8c3BhbiBjbGFzcz1cIm13LWhhbmRsZS1tZW51LWl0ZW0gZHluYW1pYy1zdWJtb2R1bGUtaGFuZGxlXCIgZGF0YS1tb2R1bGU9XCInK3RoaXMuaWQrJ1wiPidcbiAgICAgICAgICAgICAgICAgICAgICAgICsgaWNvblxuICAgICAgICAgICAgICAgICAgICAgICAgKyBoYXN0aXRsZS5yZXBsYWNlKC9fL2csICcgJylcbiAgICAgICAgICAgICAgICAgICAgICAgICsgJzwvc3Bhbj4nO1xuICAgICAgICAgICAgICAgICAgICBub2Rlcy5wdXNoKG1lbnVpdGVtKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuJCgnLm13X2hhbmRsZV9tb2R1bGVfc3VibW9kdWxlcycpO1xuICAgICAgICAgICAgZWwuZW1wdHkoKTtcbiAgICAgICAgICAgICQuZWFjaChub2RlcywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGVsLmFwcGVuZCh0aGlzKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuJCgnLnRleHQtYmFja2dyb3VuZCcsIHBlbGVtZW50KS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgYmdFbCA9IHRoaXM7XG4gICAgICAgICAgICAgICAgJC5lYWNoKFswLDFdLCBmdW5jdGlvbihpKXtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGljb24gID0gJzxzcGFuIGNsYXNzPVwibXctaWNvbi1nZWFyIG13LWhhbmRsZS1tZW51LWl0ZW0taWNvblwiPjwvc3Bhbj4nO1xuICAgICAgICAgICAgICAgICAgICB2YXIgbWVudWl0ZW0gPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgICAgICAgICBtZW51aXRlbS5jbGFzc05hbWUgPSAnbXctaGFuZGxlLW1lbnUtaXRlbSB0ZXh0LWJhY2tncm91bmQtaGFuZGxlJztcbiAgICAgICAgICAgICAgICAgICAgbWVudWl0ZW0uaW5uZXJIVE1MID0gaWNvbiArICdiYWNrZ3JvdW5kIHRleHQnO1xuICAgICAgICAgICAgICAgICAgICBtZW51aXRlbS5fX2ZvciA9IGJnRWw7XG4gICAgICAgICAgICAgICAgICAgIC8vIG1lbnVpdGVtID0gbXcuJChtZW51aXRlbSk7XG4gICAgICAgICAgICAgICAgICAgIGVsLmVxKGkpLmFwcGVuZChtZW51aXRlbSlcbiAgICAgICAgICAgICAgICB9KVxuXG4gICAgICAgICAgICB9KTtcblxuXG4gICAgICAgICAgICBtdy4kKCcudGV4dC1iYWNrZ3JvdW5kLWhhbmRsZScpLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgb2sgPSBtdy4kKCc8YnV0dG9uIGNsYXNzPVwibXctdWktYnRuIG13LXVpLWJ0bi1tZWRpdW0gbXctdWktYnRuLWluZm8gcHVsbC1yaWdodFwiPk9LPC9idXR0b24+Jyk7XG4gICAgICAgICAgICAgICAgdmFyIGNhbmNlbCA9IG13LiQoJzxidXR0b24gY2xhc3M9XCJtdy11aS1idG4gbXctdWktYnRuLW1lZGl1bSBwdWxsLWxlZnRcIj5DYW5jZWw8L2J1dHRvbj4nKTtcbiAgICAgICAgICAgICAgICB2YXIgZm9vdGVyID0gbXcuJCgnPGRpdj48L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICB2YXIgYXJlYSA9ICQoJzx0ZXh0YXJlYSBjbGFzcz1cIm13LXVpLWZpZWxkIHcxMDBcIiBzdHlsZT1cImhlaWdodDogMjAwcHhcIi8+Jyk7XG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSB0aGlzLl9fZm9yO1xuICAgICAgICAgICAgICAgIGFyZWEudmFsKG13LiQobm9kZSkuaHRtbCgpKTtcbiAgICAgICAgICAgICAgICBmb290ZXIuYXBwZW5kKGNhbmNlbCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLmFwcGVuZChvayk7XG4gICAgICAgICAgICAgICAgdmFyIGRpYWxvZyA9IG13LmRpYWxvZyh7XG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6IGFyZWEsXG4gICAgICAgICAgICAgICAgICAgIGZvb3RlcjogZm9vdGVyXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgb2sub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IG5vZGUsXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZTogbm9kZS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQobm9kZSkuaHRtbChhcmVhLnZhbCgpKTtcbiAgICAgICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IG5vZGUsXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZTogbm9kZS5pbm5lckhUTUxcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgY2FuY2VsLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy4kKCcuZHluYW1pYy1zdWJtb2R1bGUtaGFuZGxlJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLm1vZHVsZV9zZXR0aW5ncygnIycgKyB0aGlzLmRhdGFzZXQubW9kdWxlKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGNvbHVtbnM6ZnVuY3Rpb24oKXtcbiAgICAgICAgbXcuaGFuZGxlQ29sdW1ucyA9IG5ldyBtdy5IYW5kbGUoe1xuICAgICAgICAgICAgaWQ6ICdtdy1oYW5kbGUtaXRlbS1jb2x1bW5zJyxcbiAgICAgICAgICAgIC8vIGNsYXNzTmFtZTonbXctaGFuZGxlLXR5cGUtZWxlbWVudCcsXG4gICAgICAgICAgICBtZW51OltcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnT25lIGNvbHVtbicsXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5jcmVhdGVfY29sdW1ucyh0aGlzLDEpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnMiBjb2x1bW5zJyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmNyZWF0ZV9jb2x1bW5zKHRoaXMsMik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6ICczIGNvbHVtbnMnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcuY3JlYXRlX2NvbHVtbnModGhpcywzKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICB0aXRsZTogJzQgY29sdW1ucycsXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5jcmVhdGVfY29sdW1ucyh0aGlzLDQpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnNSBjb2x1bW5zJyxcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmNyZWF0ZV9jb2x1bW5zKHRoaXMsNSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgdGl0bGU6ICdSZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBpY29uOiAnbXctaWNvbi1iaW4nLFxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6J213LWhhbmRsZS1yZW1vdmUnLFxuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmRyYWcuZGVsZXRlX2VsZW1lbnQobXcuX2FjdGl2ZVJvd092ZXIsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG13LmRyYWcuY29sdW1ucy5yZXNpemVyKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuaGFuZGxlQ29sdW1ucy5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIF1cbiAgICAgICAgfSk7XG4gICAgICAgIG13LmhhbmRsZUNvbHVtbnMuc2V0VGl0bGUoJzxzcGFuIGNsYXNzPVwibXctaGFuZGxlLWNvbHVtbnMtaWNvblwiPjwvc3Bhbj4nLCAnJyk7XG5cbiAgICAgICAgbXcuJChtdy5oYW5kbGVDb2x1bW5zLndyYXBwZXIpLmRyYWdnYWJsZSh7XG4gICAgICAgICAgICBoYW5kbGU6IG13LmhhbmRsZUNvbHVtbnMuaGFuZGxlSWNvbixcbiAgICAgICAgICAgIGN1cnNvckF0OiB7XG4gICAgICAgICAgICAgICAgLy90b3A6IC0zMFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHN0YXJ0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICBtdy5pc0RyYWcgPSB0cnVlO1xuICAgICAgICAgICAgICAgIHZhciBjdXJyID0gbXcuX2FjdGl2ZVJvd092ZXIgO1xuICAgICAgICAgICAgICAgIG13LmRyYWdDdXJyZW50ID0gbXcuZWEuZGF0YS5jdXJyZW50R3JhYmJlZCA9IGN1cnI7XG4gICAgICAgICAgICAgICAgaGFuZGxlRG9tdHJlZVN5bmMuc3RhcnQgPSBtdy5kcmFnQ3VycmVudC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIG13LmRyYWdDdXJyZW50LmlkID09IFwiXCIgPyBtdy5kcmFnQ3VycmVudC5pZCA9ICdlbGVtZW50XycgKyBtdy5yYW5kb20oKSA6ICcnO1xuICAgICAgICAgICAgICAgIG13LiQobXcuZHJhZ0N1cnJlbnQpLmludmlzaWJsZSgpLmFkZENsYXNzKFwibXdfZHJhZ19jdXJyZW50XCIpO1xuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJBbGxMZWF2ZVwiKTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnMoKTtcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5hZGRDbGFzcyhcImRyYWdTdGFydFwiKTtcbiAgICAgICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9oaWRlKCk7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuZHJhZ0N1cnJlbnQpO1xuICAgICAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yLmNzcyhcInZpc2liaWxpdHlcIiwgXCJoaWRkZW5cIik7XG4gICAgICAgICAgICAgICAgbXcuc21hbGxFZGl0b3JDYW5jZWxlZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgbXcuJChtdy5kcmFnLmNvbHVtbnMucmVzaXplcikuaGlkZSgpXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc3RvcDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoXCJkcmFnU3RhcnRcIik7XG4gICAgICAgICAgICAgICAgaWYobXcubGl2ZUVkaXREb21UcmVlKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0RG9tVHJlZS5yZWZyZXNoKGhhbmRsZURvbXRyZWVTeW5jLnN0YXJ0KVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgbXcub24oXCJSb3dPdmVyXCIsIGZ1bmN0aW9uKGEsIGVsZW1lbnQpIHtcblxuICAgICAgICAgICAgbXcuX2FjdGl2ZVJvd092ZXIgPSBlbGVtZW50O1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuJChlbGVtZW50KTtcbiAgICAgICAgICAgIHZhciBvID0gZWwub2Zmc2V0KCk7XG4gICAgICAgICAgICB2YXIgd2lkdGggPSBlbC53aWR0aCgpO1xuICAgICAgICAgICAgdmFyIHBsZWZ0ID0gcGFyc2VGbG9hdChlbC5jc3MoXCJwYWRkaW5nTGVmdFwiKSk7XG4gICAgICAgICAgICB2YXIgaHRvcCA9IG8udG9wIC0gMzU7XG4gICAgICAgICAgICB2YXIgbGVmdCA9IG8ubGVmdDtcblxuICAgICAgICAgICAgaWYgKGh0b3AgPCA1NSAmJiBtd2QuZ2V0RWxlbWVudEJ5SWQoJ2xpdmVfZWRpdF90b29sYmFyJykgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBodG9wID0gNTU7XG4gICAgICAgICAgICAgICAgbGVmdCA9IGxlZnQgLSAxMDA7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoaHRvcCA8IDAgJiYgbXdkLmdldEVsZW1lbnRCeUlkKCdsaXZlX2VkaXRfdG9vbGJhcicpID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgaHRvcCA9IDA7XG4gICAgICAgICAgICAgICAgLy8gICB2YXIgbGVmdCA9IGxlZnQtNTA7XG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgbXcuaGFuZGxlQ29sdW1ucy5zaG93KClcblxuICAgICAgICAgICAgbXcuJChtdy5oYW5kbGVDb2x1bW5zLndyYXBwZXIpLmNzcyh7XG4gICAgICAgICAgICAgICAgdG9wOiBodG9wLFxuICAgICAgICAgICAgICAgIGxlZnQ6IGxlZnQsXG4gICAgICAgICAgICAgICAgLy93aWR0aDogd2lkdGhcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuX2luaXRIYW5kbGVzLm1hbmFnZUNvbGxpc2lvbigpO1xuXG4gICAgICAgICAgICB2YXIgc2l6ZSA9IG13LiQoZWxlbWVudCkuY2hpbGRyZW4oXCIubXctY29sXCIpLmxlbmd0aDtcbiAgICAgICAgICAgIG13LiQoXCJhLm13LW1ha2UtY29sc1wiKS5yZW1vdmVDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgICAgIG13LiQoXCJhLm13LW1ha2UtY29sc1wiKS5lcShzaXplIC0gMSkuYWRkQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICBpZighZWxlbWVudC5pZCl7XG4gICAgICAgICAgICAgICAgZWxlbWVudC5pZCA9IFwiZWxlbWVudF9yb3dfXCIgKyBtdy5yYW5kb20oKSA7XG4gICAgICAgICAgICB9XG5cblxuXG5cbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBub2RlTGVhdmU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcblxuICAgICAgICBtdy5vbihcIkVsZW1lbnRMZWF2ZVwiLCBmdW5jdGlvbihlLCB0YXJnZXQpIHtcbiAgICAgICAgICAgIG13LmhhbmRsZUVsZW1lbnQuaGlkZSgpO1xuICAgICAgICB9KTtcbiAgICAgICAgbXcub24oXCJNb2R1bGVMZWF2ZVwiLCBmdW5jdGlvbihlLCB0YXJnZXQpIHtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dChtdy5oYW5kbGVNb2R1bGUuX2hpZGVUaW1lKTtcbiAgICAgICAgICAgIG13LmhhbmRsZU1vZHVsZS5faGlkZVRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy5oYW5kbGVNb2R1bGUuaGlkZSgpO1xuICAgICAgICAgICAgfSwgMzAwMCk7XG5cbiAgICAgICAgICAgIC8vLnJlbW92ZUNsYXNzKCdtdy1hY3RpdmUtaXRlbScpO1xuICAgICAgICB9KTtcbiAgICAgICAgbXcub24oXCJSb3dMZWF2ZVwiLCBmdW5jdGlvbihlLCB0YXJnZXQpIHtcbiAgICAgICAgICAgIC8vbXcuaGFuZGxlQ29sdW1ucy5oaWRlKCk7XG4gICAgICAgIH0pO1xuICAgIH1cbn07XG5cblxuXG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcblxuICAgIG13Ll9pbml0SGFuZGxlcy5tb2R1bGVzKCk7XG4gICAgbXcuX2luaXRIYW5kbGVzLmVsZW1lbnRzKCk7XG4gICAgbXcuX2luaXRIYW5kbGVzLmNvbHVtbnMoKTtcbiAgICBtdy5faW5pdEhhbmRsZXMubm9kZUxlYXZlKCk7XG5cblxuXG59KTtcbiIsIm13LmltYWdlUmVzaXplID0ge1xuICAgIHByZXBhcmU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCFtdy5pbWFnZV9yZXNpemVyKSB7XG4gICAgICAgICAgICB2YXIgcmVzaXplciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgcmVzaXplci5jbGFzc05hbWUgPSAnbXctZGVmYXVsdHMgbXdfaW1hZ2VfcmVzaXplcic7XG4gICAgICAgICAgICByZXNpemVyLmlubmVySFRNTCA9ICc8ZGl2IGlkPVwiaW1hZ2UtZWRpdC1uYXZcIj48c3BhbiBvbmNsaWNrPVwibXcud3lzaXd5Zy5tZWRpYShcXCcjZWRpdGltYWdlXFwnKTtcIiBjbGFzcz1cIm13LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIG13LXVpLWJ0bi1pbnZlcnQgbXctdWktYnRuLWljb24gaW1hZ2VfY2hhbmdlIHRpcFwiIGRhdGEtdGlwPVwiJyArIG13Lm1zZy5jaGFuZ2UgKyAnXCI+PHNwYW4gY2xhc3M9XCJtZGkgbWRpLWltYWdlIG1kaS0xOHB4XCI+PC9zcGFuPjwvc3Bhbj48c3BhbiBjbGFzcz1cIm13LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIG13LXVpLWJ0bi1pbnZlcnQgbXctdWktYnRuLWljb24gdGlwIGltYWdlX2NoYW5nZVwiIGlkPVwiaW1hZ2Utc2V0dGluZ3MtYnV0dG9uXCIgZGF0YS10aXA9XCInICsgbXcubXNnLmVkaXQgKyAnXCIgb25jbGljaz1cIm13LmltYWdlLnNldHRpbmdzKCk7XCI+PHNwYW4gY2xhc3M9XCJtZGkgbWRpLXBlbmNpbCBtZGktMThweFwiPjwvc3Bhbj48L3NwYW4+PC9kaXY+JztcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQocmVzaXplcik7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyID0gcmVzaXplcjtcbiAgICAgICAgICAgIG13LmltYWdlX3Jlc2l6ZXJfdGltZSA9IG51bGw7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9zaG93ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dChtdy5pbWFnZV9yZXNpemVyX3RpbWUpXG4gICAgICAgICAgICAgICAgbXcuJChtdy5pbWFnZV9yZXNpemVyKS5hZGRDbGFzcygnYWN0aXZlJylcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9oaWRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LmltYWdlX3Jlc2l6ZXJfdGltZSA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG13LmltYWdlX3Jlc2l6ZXIpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKVxuICAgICAgICAgICAgICAgIH0sIDMwMDApXG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICBtdy4kKHJlc2l6ZXIpLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBpZiAobXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdLm5vZGVOYW1lID09PSAnSU1HJykge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdF9lbGVtZW50KG13LmltYWdlLmN1cnJlbnRSZXNpemluZ1swXSlcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LiQocmVzaXplcikub24oXCJkYmxjbGlja1wiLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubWVkaWEoJyNlZGl0aW1hZ2UnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIG13LiQobXcuaW1hZ2VfcmVzaXplcikucmVzaXphYmxlKHtcbiAgICAgICAgICAgIGhhbmRsZXM6IFwiYWxsXCIsXG4gICAgICAgICAgICBtaW5XaWR0aDogNjAsXG4gICAgICAgICAgICBtaW5IZWlnaHQ6IDYwLFxuICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy5pbWFnZS5pc1Jlc2l6aW5nID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy4kKG13LmltYWdlX3Jlc2l6ZXIpLnJlc2l6YWJsZShcIm9wdGlvblwiLCBcIm1heFdpZHRoXCIsIG13LmltYWdlLmN1cnJlbnRSZXNpemluZy5wYXJlbnQoKS53aWR0aCgpKTtcbiAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKG13LmltYWdlLmN1cnJlbnRSZXNpemluZ1swXSwgJ2VkaXQnKSkuYWRkQ2xhc3MoXCJjaGFuZ2VkXCIpO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHN0b3A6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy5pbWFnZS5pc1Jlc2l6aW5nID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgbXcuZHJhZy5maXhfcGxhY2Vob2xkZXJzKCk7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgcmVzaXplOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIG9mZnNldCA9IG13LmltYWdlLmN1cnJlbnRSZXNpemluZy5vZmZzZXQoKTtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmNzcyhvZmZzZXQpO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGFzcGVjdFJhdGlvOiAxNiAvIDlcbiAgICAgICAgfSk7XG4gICAgICAgIG13LmltYWdlX3Jlc2l6ZXIubXdJbWFnZVJlc2l6ZXJDb21wb25lbnQgPSB0cnVlO1xuICAgICAgICB2YXIgYWxsID0gbXcuaW1hZ2VfcmVzaXplci5xdWVyeVNlbGVjdG9yQWxsKCcqJyksIGwgPSBhbGwubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIGFsbFtpXS5td0ltYWdlUmVzaXplckNvbXBvbmVudCA9IHRydWVcbiAgICB9LFxuICAgIHJlc2l6ZXJTZXQ6IGZ1bmN0aW9uIChlbCwgc2VsZWN0SW1hZ2UpIHtcbiAgICAgICAgc2VsZWN0SW1hZ2UgPSB0eXBlb2Ygc2VsZWN0SW1hZ2UgPT09ICd1bmRlZmluZWQnID8gdHJ1ZSA6IHNlbGVjdEltYWdlO1xuICAgICAgICAvKiAgdmFyIG9yZGVyID0gbXcudG9vbHMucGFyZW50c09yZGVyKGVsLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuICAgICAgICAgaWYoIShvcmRlci5tb2R1bGUgPiAtMSAmJiBvcmRlci5lZGl0ID4gb3JkZXIubW9kdWxlKSAmJiBvcmRlci5lZGl0Pi0xKXsgICAqL1xuXG5cbiAgICAgICAgbXcuJCgnLnVpLXJlc2l6YWJsZS1oYW5kbGUnLCBtdy5pbWFnZV9yZXNpemVyKVtlbC5ub2RlTmFtZSA9PSAnSU1HJyA/ICdzaG93JyA6ICdoaWRlJ10oKVxuXG4gICAgICAgIGVsID0gbXcuJChlbCk7XG4gICAgICAgIHZhciBvZmZzZXQgPSBlbC5vZmZzZXQoKTtcbiAgICAgICAgdmFyIHBhcmVudCA9IGVsLnBhcmVudCgpO1xuICAgICAgICB2YXIgcGFyZW50T2Zmc2V0ID0gcGFyZW50Lm9mZnNldCgpO1xuICAgICAgICBpZihwYXJlbnRbMF0ubm9kZU5hbWUgIT09ICdBJyl7XG4gICAgICAgICAgICBvZmZzZXQudG9wID0gb2Zmc2V0LnRvcCA8IHBhcmVudE9mZnNldC50b3AgPyBwYXJlbnRPZmZzZXQudG9wIDogb2Zmc2V0LnRvcDtcbiAgICAgICAgICAgIG9mZnNldC5sZWZ0ID0gb2Zmc2V0LmxlZnQgPCBwYXJlbnRPZmZzZXQubGVmdCA/IHBhcmVudE9mZnNldC5sZWZ0IDogb2Zmc2V0LmxlZnQ7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHIgPSBtdy4kKG13LmltYWdlX3Jlc2l6ZXIpO1xuICAgICAgICB2YXIgd2lkdGggPSBlbC5vdXRlcldpZHRoKCk7XG4gICAgICAgIHZhciBoZWlnaHQgPSBlbC5vdXRlckhlaWdodCgpO1xuICAgICAgICByLmNzcyh7XG4gICAgICAgICAgICBsZWZ0OiBvZmZzZXQubGVmdCxcbiAgICAgICAgICAgIHRvcDogb2Zmc2V0LnRvcCxcbiAgICAgICAgICAgIHdpZHRoOiB3aWR0aCxcbiAgICAgICAgICAgIGhlaWdodDogbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhlbFswXSwgJ213LWltYWdlLWhvbGRlcicpID8gMSA6IGhlaWdodFxuICAgICAgICB9KTtcbiAgICAgICAgci5hZGRDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgbXcuJChtdy5pbWFnZV9yZXNpemVyKS5yZXNpemFibGUoXCJvcHRpb25cIiwgXCJhbHNvUmVzaXplXCIsIGVsKTtcbiAgICAgICAgbXcuJChtdy5pbWFnZV9yZXNpemVyKS5yZXNpemFibGUoXCJvcHRpb25cIiwgXCJhc3BlY3RSYXRpb1wiLCB3aWR0aCAvIGhlaWdodCk7XG4gICAgICAgIG13LmltYWdlLmN1cnJlbnRSZXNpemluZyA9IGVsO1xuICAgICAgICBpZiAoIWVsWzBdLmNvbnRlbnRFZGl0YWJsZSkge1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5jb250ZW50RWRpdGFibGUoZWxbMF0sIHRydWUpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHNlbGVjdEltYWdlKSB7XG4gICAgICAgICAgICBpZiAoZWxbMF0ucGFyZW50Tm9kZS50YWdOYW1lICE9PSAnQScpIHtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdF9lbGVtZW50KGVsWzBdKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0X2VsZW1lbnQoZWxbMF0ucGFyZW50Tm9kZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG13ZC5nZXRFbGVtZW50QnlJZCgnaW1hZ2Utc2V0dGluZ3MtYnV0dG9uJykgIT09IG51bGwpIHtcbiAgICAgICAgICAgIGlmICghIWVsWzBdLnNyYyAmJiBlbFswXS5zcmMuY29udGFpbnMoJ3VzZXJmaWxlcy9tZWRpYS9waXh1bS8nKSkge1xuICAgICAgICAgICAgICAgIG13ZC5nZXRFbGVtZW50QnlJZCgnaW1hZ2Utc2V0dGluZ3MtYnV0dG9uJykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIG13ZC5nZXRFbGVtZW50QnlJZCgnaW1hZ2Utc2V0dGluZ3MtYnV0dG9uJykuc3R5bGUuZGlzcGxheSA9ICcnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIC8qIH0gKi9cbiAgICB9LFxuICAgIGluaXQ6IGZ1bmN0aW9uIChzZWxlY3Rvcikge1xuICAgICAgICBtdy5pbWFnZV9yZXNpemVyID09IHVuZGVmaW5lZCA/IG13LmltYWdlUmVzaXplLnByZXBhcmUoKSA6ICcnO1xuXG4gICAgICAgIG13Lm9uKFwiSW1hZ2VDbGlja1wiLCBmdW5jdGlvbiAoZSwgZWwpIHtcbiAgICAgICAgICAgIGlmICghbXcuaW1hZ2UuaXNSZXNpemluZyAmJiAhbXcuaXNEcmFnICYmICFtdy5zZXR0aW5ncy5yZXNpemVfc3RhcnRlZCAmJiBlbC50YWdOYW1lID09PSAnSU1HJykge1xuICAgICAgICAgICAgICAgIG13LmltYWdlUmVzaXplLnJlc2l6ZXJTZXQoZWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH1cbn1cbiIsIm13LmxpdmVlZGl0LmluaXRMb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICBtdy4kKFwiLm13LWRyb3Bkb3duX3R5cGVfbmF2aWdhdGlvbiBhXCIpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBtdy4kKHRoaXMpO1xuICAgICAgICAgICAgdmFyIGxpID0gZWwucGFyZW50KCk7XG4gICAgICAgICAgICBlbC5hdHRyKFwiaHJlZlwiLCBcImphdmFzY3JpcHQ6O1wiKTtcbiAgICAgICAgICAgIHZhciB2YWwgPSBsaS5kYXRhc2V0KFwiY2F0ZWdvcnktaWRcIik7XG4gICAgICAgICAgICBsaS5hdHRyKFwidmFsdWVcIiwgdmFsKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgbXcuJChcIiNtb2R1bGVfY2F0ZWdvcnlfc2VsZWN0b3JcIikuY2hhbmdlKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgdmFyIHZhbCA9IG13LiQodGhpcykuZ2V0RHJvcGRvd25WYWx1ZSgpO1xuXG4gICAgICAgICAgICBpZiAodmFsID09PSAnYWxsJykge1xuICAgICAgICAgICAgICAgIG13LiQoXCIubGlzdC1tb2R1bGVzIGxpXCIpLnNob3coKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAodmFsICE9PSAtMSAmJiB2YWwgIT09IFwiLTFcIikgPyBtdy5saXZlZWRpdC50b29sYmFyX3NvcnRlcihNb2R1bGVzX0xpc3RfbW9kdWxlcywgdmFsKTogJyc7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKFwiI2VsZW1lbnRzX2NhdGVnb3J5X3NlbGVjdG9yXCIpLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHZhciB2YWwgPSBtdy4kKHRoaXMpLmdldERyb3Bkb3duVmFsdWUoKTtcblxuICAgICAgICAgICAgaWYgKHZhbCA9PT0gJ2FsbCcpIHtcbiAgICAgICAgICAgICAgICBtdy4kKFwiLmxpc3QtZWxlbWVudHMgbGlcIikuc2hvdygpO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgICh2YWwgIT09IC0xICYmIHZhbCAhPT0gXCItMVwiKSA/IG13LmxpdmVlZGl0LnRvb2xiYXJfc29ydGVyKE1vZHVsZXNfTGlzdF9lbGVtZW50cywgdmFsKTogJyc7XG4gICAgICAgIH0pO1xuXG5cblxuXG4gICAgICAgIG13LmludGVydmFsKCdyZWd1bGFyLW1vZGUnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgLy8gbXcuJCgnLnJvdycpLmFkZENsYXNzKCdub2Ryb3AnKTtcbiAgICAgICAgICAgIC8vIG13LiQoJy5yb3cgLmNvbCwgLnJvdyBbY2xhc3MqPVwiY29sLVwiXScpLmFkZENsYXNzKCdhbGxvdy1kcm9wJyk7XG4gICAgICAgICAgICAvLyBtdy4kKCcubm9kcm9wIC5hbGxvdy1kcm9wJykuYWRkQ2xhc3MoJ3JlZ3VsYXItbW9kZScpO1xuICAgICAgICAgICAgbXcuJCgnLnNhZmUtZWxlbWVudFtjbGFzcyo9XCJtdy1taWNvbi1cIl0nKS5yZW1vdmVDbGFzcygnc2FmZS1lbGVtZW50Jyk7XG4gICAgICAgIH0pXG5cbiAgICB9LCAxMDApO1xuXG5cbiAgICBtdy53eXNpd3lnLnByZXBhcmVDb250ZW50RWRpdGFibGUoKTtcblxuICAgIG13LmltYWdlUmVzaXplLmluaXQoXCIuZWxlbWVudC1pbWFnZVwiKTtcbiAgICBtdy4kKG13ZC5ib2R5KS5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbihldmVudCkge1xuXG5cbiAgICAgICAgaWYgKG13LiQoXCIuZWRpdG9yX2hvdmVyXCIpLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgbXcuJChtdy53eXNpd3lnLmV4dGVybmFsKS5lbXB0eSgpLmNzcyhcInRvcFwiLCBcIi05OTk5cHhcIik7XG4gICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5yZW1vdmVDbGFzcygnaGlkZV9zZWxlY3Rpb24nKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoIW13LnRvb2xzLmhhc0NsYXNzKGV2ZW50LnRhcmdldCwgJ213X2hhbmRsZV9yb3cnKSAmJlxuICAgICAgICAgICAgIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXdfaGFuZGxlX3JvdycpICYmXG4gICAgICAgICAgICAhbXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctcm93JykgJiZcbiAgICAgICAgICAgICFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGV2ZW50LnRhcmdldCwgJ213LXJvdycpKSB7XG5cbiAgICAgICAgICAgIG13LiQoXCIubXctcm93XCIpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5jbGlja2VkID0gZmFsc2U7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctcm93JykpIHtcbiAgICAgICAgICAgIG13LiQoXCIubXctcm93XCIpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMgIT09IGV2ZW50LnRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmNsaWNrZWQgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGV2ZW50LnRhcmdldC5jbGlja2VkID0gdHJ1ZTtcbiAgICAgICAgfSBlbHNlIGlmIChtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGV2ZW50LnRhcmdldCwgJ213LXJvdycpKSB7XG4gICAgICAgICAgICB2YXIgcm93ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctcm93Jyk7XG4gICAgICAgICAgICBtdy4kKFwiLm13LXJvd1wiKS5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIGlmICh0aGlzICE9PSByb3cpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5jbGlja2VkID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByb3cuY2xpY2tlZCA9IHRydWU7XG4gICAgICAgIH1cbiAgICB9KTtcblxuXG4gICAgbXcuJChkb2N1bWVudC5ib2R5KS5vbignbW91c2V1cCB0b3VjaGVuZCcsZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgbXcudGFyZ2V0Lml0ZW0gPSBldmVudC50YXJnZXQ7XG4gICAgICAgIG13LnRhcmdldC50YWcgPSBldmVudC50YXJnZXQudGFnTmFtZS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICBtdy5tb3VzZURvd25PbkVkaXRvciA9IGZhbHNlO1xuICAgICAgICBtdy5TbWFsbEVkaXRvcklzRHJhZ2dpbmcgPSBmYWxzZTtcbiAgICAgICAgaWYgKCFtdy5pbWFnZS5pc1Jlc2l6aW5nICYmXG4gICAgICAgICAgICBtdy50YXJnZXQudGFnICE9PSAnaW1nJyAmJlxuICAgICAgICAgICAgZXZlbnQudGFyZ2V0ICE9PSBtdy5pbWFnZV9yZXNpemVyICYmICFtdy50b29scy5oYXNDbGFzcyhtdy50YXJnZXQuaXRlbS5jbGFzc05hbWUsICdpbWFnZV9jaGFuZ2UnKSAmJiAhbXcudG9vbHMuaGFzQ2xhc3MobXcudGFyZ2V0Lml0ZW0ucGFyZW50Tm9kZS5jbGFzc05hbWUsICdpbWFnZV9jaGFuZ2UnKSAmJiBtdy4kKG13LmltYWdlX3Jlc2l6ZXIpLmhhc0NsYXNzKFwiYWN0aXZlXCIpKSB7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9oaWRlKCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIG13LmxpdmVlZGl0LnRvb2xiYXIucHJlcGFyZSgpO1xuICAgIG13LmxpdmVlZGl0LnRvb2xiYXIuZml4UGFkKCk7XG4gICAgbXcubGl2ZWVkaXQuZWRpdG9ycy5wcmVwYXJlKCk7XG4gICAgbXcubGl2ZWVkaXQudG9vbGJhci5zZXRFZGl0b3IoKTtcbn1cbiIsIm13LmxpdmVlZGl0LmluaXRSZWFkeSA9IGZ1bmN0aW9uKCkge1xyXG4gICAgbXcubGl2ZWVkaXQuZGF0YS5pbml0KCk7XHJcbiAgICBtdy5saXZlRWRpdFNlbGVjdG9yID0gbmV3IG13LlNlbGVjdG9yKHtcclxuICAgICAgICByb290OiBkb2N1bWVudC5ib2R5LFxyXG4gICAgICAgIGF1dG9TZWxlY3Q6IGZhbHNlXHJcbiAgICB9KTtcclxuXHJcbiAgICBtdy5wYWRkaW5nQ1RSTCA9IG5ldyBtdy5wYWRkaW5nRWRpdG9yKHtcclxuXHJcbiAgICB9KTtcclxuXHJcbiAgICBtdy5kcmFnLmNyZWF0ZSgpO1xyXG5cclxuICAgIG13LmxpdmVlZGl0LmVkaXRGaWVsZHMuaGFuZGxlS2V5ZG93bigpO1xyXG5cclxuICAgIG13LmRyYWdTVE9QQ2hlY2sgPSBmYWxzZTtcclxuXHJcbiAgICB2YXIgdCA9IG13ZC5xdWVyeVNlbGVjdG9yQWxsKCdbZmllbGQ9XCJ0aXRsZVwiXScpLFxyXG4gICAgICAgIGwgPSB0Lmxlbmd0aCxcclxuICAgICAgICBpID0gMDtcclxuXHJcbiAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgIG13LiQodFtpXSkuYWRkQ2xhc3MoXCJub2Ryb3BcIik7XHJcbiAgICB9XHJcblxyXG5cclxuXHJcbiAgICBtdy53eXNpd3lnLmluaXRfZWRpdGFibGVzKCk7XHJcbiAgICBtdy53eXNpd3lnLnByZXBhcmUoKTtcclxuICAgIG13Lnd5c2l3eWcuaW5pdCgpO1xyXG4gICAgbXcuZWEgPSBtdy5lYSB8fCBuZXcgbXcuRWxlbWVudEFuYWx5emVyKCk7XHJcbn07XHJcbiIsIm13LmxpdmVlZGl0LmlubGluZSA9IHtcclxuICAgIGJhcjogZnVuY3Rpb24gKGlkKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBpZCA9PT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAobXcuJChcIiNcIiArIGlkKS5sZW5ndGggPT09IDApIHtcclxuICAgICAgICAgICAgdmFyIGJhciA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICAgICAgYmFyLmlkID0gaWQ7XHJcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGJhciwgZmFsc2UpO1xyXG4gICAgICAgICAgICBiYXIuY2xhc3NOYW1lID0gJ213LWRlZmF1bHRzIG13LWlubGluZS1iYXInO1xyXG4gICAgICAgICAgICBtd2QuYm9keS5hcHBlbmRDaGlsZChiYXIpO1xyXG4gICAgICAgICAgICByZXR1cm4gYmFyO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgcmV0dXJuIG13LiQoXCIjXCIgKyBpZClbMF07XHJcbiAgICAgICAgfVxyXG4gICAgfSxcclxuICAgIHRhYmxlQ29udHJvbDogZmFsc2UsXHJcbiAgICB0YWJsZUNvbnRyb2xsZXI6IGZ1bmN0aW9uIChlbCwgZSkge1xyXG4gICAgICAgIGlmICh0eXBlb2YgZSAhPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgaWYgKG13LmxpdmVlZGl0LmlubGluZS50YWJsZUNvbnRyb2wgPT09IGZhbHNlKSB7XHJcbiAgICAgICAgICAgIG13LmxpdmVlZGl0LmlubGluZS50YWJsZUNvbnRyb2wgPSBtdy5saXZlZWRpdC5pbmxpbmUuYmFyKCdtdy1pbmxpbmUtdGFibGVDb250cm9sJyk7XHJcbiAgICAgICAgICAgIG13LmxpdmVlZGl0LmlubGluZS50YWJsZUNvbnRyb2wuaW5uZXJIVE1MID0gJydcclxuICAgICAgICAgICAgICAgICsgJzx1bCBjbGFzcz1cIm13LXVpLWJveCBtdy11aS1uYXZpZ2F0aW9uIG13LXVpLW5hdmlnYXRpb24taG9yaXpvbnRhbFwiPidcclxuICAgICAgICAgICAgICAgICsgJzxsaT4nXHJcbiAgICAgICAgICAgICAgICArICc8YSBocmVmPVwiamF2YXNjcmlwdDo7XCI+SW5zZXJ0PHNwYW4gY2xhc3M9XCJtdy1pY29uLWRyb3Bkb3duXCI+PC9zcGFuPjwvYT4nXHJcbiAgICAgICAgICAgICAgICArICc8dWw+J1xyXG4gICAgICAgICAgICAgICAgKyAnPGxpPjxhIGhyZWY9XCJqYXZhc2NyaXB0OjtcIiBvbmNsaWNrPVwibXcubGl2ZWVkaXQuaW5saW5lLnRhYmxlTWFuYWdlci5pbnNlcnRSb3coXFwnYWJvdmVcXCcsIG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKTtcIj5Sb3cgQWJvdmU8L2E+PC9saT4nXHJcbiAgICAgICAgICAgICAgICArICc8bGk+PGEgaHJlZj1cImphdmFzY3JpcHQ6O1wiIG9uY2xpY2s9XCJtdy5saXZlZWRpdC5pbmxpbmUudGFibGVNYW5hZ2VyLmluc2VydFJvdyhcXCd1bmRlclxcJywgbXcubGl2ZWVkaXQuaW5saW5lLmFjdGl2ZUNlbGwpO1wiPlJvdyBVbmRlcjwvYT48L2xpPidcclxuICAgICAgICAgICAgICAgICsgJzxsaT48YSBocmVmPVwiamF2YXNjcmlwdDo7XCIgb25jbGljaz1cIm13LmxpdmVlZGl0LmlubGluZS50YWJsZU1hbmFnZXIuaW5zZXJ0Q29sdW1uKFxcJ2xlZnRcXCcsIG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKVwiPkNvbHVtbiBvbiBsZWZ0PC9hPjwvbGk+J1xyXG4gICAgICAgICAgICAgICAgKyAnPGxpPjxhIGhyZWY9XCJqYXZhc2NyaXB0OjtcIiBvbmNsaWNrPVwibXcubGl2ZWVkaXQuaW5saW5lLnRhYmxlTWFuYWdlci5pbnNlcnRDb2x1bW4oXFwncmlnaHRcXCcsIG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKVwiPkNvbHVtbiBvbiByaWdodDwvYT48L2xpPidcclxuICAgICAgICAgICAgICAgICsgJzwvdWw+J1xyXG4gICAgICAgICAgICAgICAgKyAnPC9saT4nXHJcbiAgICAgICAgICAgICAgICArICc8bGk+J1xyXG4gICAgICAgICAgICAgICAgKyAnPGEgaHJlZj1cImphdmFzY3JpcHQ6O1wiPlN0eWxlPHNwYW4gY2xhc3M9XCJtdy1pY29uLWRyb3Bkb3duXCI+PC9zcGFuPjwvYT4nXHJcbiAgICAgICAgICAgICAgICArICc8dWw+J1xyXG4gICAgICAgICAgICAgICAgKyAnPGxpPjxhIGhyZWY9XCJqYXZhc2NyaXB0OjtcIiBvbmNsaWNrPVwibXcubGl2ZWVkaXQuaW5saW5lLnRhYmxlTWFuYWdlci5zZXRTdHlsZShcXCdtdy13eXNpd3lnLXRhYmxlXFwnLCBtdy5saXZlZWRpdC5pbmxpbmUuYWN0aXZlQ2VsbCk7XCI+Qm9yZGVyZWQ8L2E+PC9saT4nXHJcbiAgICAgICAgICAgICAgICArICc8bGk+PGEgaHJlZj1cImphdmFzY3JpcHQ6O1wiIG9uY2xpY2s9XCJtdy5saXZlZWRpdC5pbmxpbmUudGFibGVNYW5hZ2VyLnNldFN0eWxlKFxcJ213LXd5c2l3eWctdGFibGUtemVicmFcXCcsIG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKTtcIj5Cb3JkZXJlZCBaZWJyYTwvYT48L2xpPidcclxuICAgICAgICAgICAgICAgICsgJzxsaT48YSBocmVmPVwiamF2YXNjcmlwdDo7XCIgb25jbGljaz1cIm13LmxpdmVlZGl0LmlubGluZS50YWJsZU1hbmFnZXIuc2V0U3R5bGUoXFwnbXctd3lzaXd5Zy10YWJsZS1zaW1wbGVcXCcsIG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKTtcIj5TaW1wbGU8L2E+PC9saT4nXHJcbiAgICAgICAgICAgICAgICArICc8bGk+PGEgaHJlZj1cImphdmFzY3JpcHQ6O1wiIG9uY2xpY2s9XCJtdy5saXZlZWRpdC5pbmxpbmUudGFibGVNYW5hZ2VyLnNldFN0eWxlKFxcJ213LXd5c2l3eWctdGFibGUtc2ltcGxlLXplYnJhXFwnLCBtdy5saXZlZWRpdC5pbmxpbmUuYWN0aXZlQ2VsbCk7XCI+U2ltcGxlIFplYnJhPC9hPjwvbGk+J1xyXG4gICAgICAgICAgICAgICAgKyAnPC91bD4nXHJcbiAgICAgICAgICAgICAgICArICc8L2xpPidcclxuICAgICAgICAgICAgICAgICsgJzxsaT4nXHJcbiAgICAgICAgICAgICAgICArICc8YSBocmVmPVwiamF2YXNjcmlwdDo7XCI+RGVsZXRlPHNwYW4gY2xhc3M9XCJtdy1pY29uLWRyb3Bkb3duXCI+PC9zcGFuPjwvYT4nXHJcbiAgICAgICAgICAgICAgICArICc8dWw+J1xyXG4gICAgICAgICAgICAgICAgKyAnPGxpPjxhIGhyZWY9XCJqYXZhc2NyaXB0OjtcIiBvbmNsaWNrPVwibXcubGl2ZWVkaXQuaW5saW5lLnRhYmxlTWFuYWdlci5kZWxldGVSb3cobXcubGl2ZWVkaXQuaW5saW5lLmFjdGl2ZUNlbGwpO1wiPlJvdzwvYT48L2xpPidcclxuICAgICAgICAgICAgICAgICsgJzxsaT48YSBocmVmPVwiamF2YXNjcmlwdDo7XCIgb25jbGljaz1cIm13LmxpdmVlZGl0LmlubGluZS50YWJsZU1hbmFnZXIuZGVsZXRlQ29sdW1uKG13LmxpdmVlZGl0LmlubGluZS5hY3RpdmVDZWxsKTtcIj5Db2x1bW48L2E+PC9saT4nXHJcbiAgICAgICAgICAgICAgICArICc8L3VsPidcclxuICAgICAgICAgICAgICAgICsgJzwvbGk+J1xyXG4gICAgICAgICAgICAgICAgKyAnPC91bD4nO1xyXG4gICAgICAgIH1cclxuICAgICAgICB2YXIgb2ZmID0gbXcuJChlbCkub2Zmc2V0KCk7XHJcbiAgICAgICAgbXcuJChtdy5saXZlZWRpdC5pbmxpbmUudGFibGVDb250cm9sKS5jc3Moe1xyXG4gICAgICAgICAgICB0b3A6IG9mZi50b3AgLSA0NSxcclxuICAgICAgICAgICAgbGVmdDogb2ZmLmxlZnQsXHJcbiAgICAgICAgICAgIGRpc3BsYXk6ICdibG9jaydcclxuICAgICAgICB9KTtcclxuICAgIH0sXHJcbiAgICBhY3RpdmVDZWxsOiBudWxsLFxyXG4gICAgc2V0QWN0aXZlQ2VsbDogZnVuY3Rpb24gKGVsLCBldmVudCkge1xyXG4gICAgICAgIGlmICghbXcudG9vbHMuaGFzQ2xhc3MoZWwuY2xhc3NOYW1lLCAndGMtYWN0aXZlY2VsbCcpKSB7XHJcbiAgICAgICAgICAgIG13LiQoXCIudGMtYWN0aXZlY2VsbFwiKS5yZW1vdmVDbGFzcygndGMtYWN0aXZlY2VsbCcpO1xyXG4gICAgICAgICAgICBtdy4kKGVsKS5hZGRDbGFzcygndGMtYWN0aXZlY2VsbCcpO1xyXG4gICAgICAgICAgICBtdy5saXZlZWRpdC5pbmxpbmUuYWN0aXZlQ2VsbCA9IGVsO1xyXG4gICAgICAgIH1cclxuICAgIH0sXHJcbiAgICB0YWJsZU1hbmFnZXI6IHtcclxuICAgICAgICBpbnNlcnRDb2x1bW46IGZ1bmN0aW9uIChkaXIsIGNlbGwpIHtcclxuICAgICAgICAgICAgY2VsbCA9IG13LiQoY2VsbClbMF07XHJcbiAgICAgICAgICAgIGlmIChjZWxsID09PSBudWxsKSB7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgZGlyID0gZGlyIHx8ICdyaWdodCc7XHJcbiAgICAgICAgICAgIHZhciByb3dzID0gbXcuJChjZWxsLnBhcmVudE5vZGUucGFyZW50Tm9kZSkuY2hpbGRyZW4oJ3RyJyk7XHJcbiAgICAgICAgICAgIHZhciBpID0gMCwgbCA9IHJvd3MubGVuZ3RoLCBpbmRleCA9IG13LnRvb2xzLmluZGV4KGNlbGwpO1xyXG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgdmFyIHJvdyA9IHJvd3NbaV07XHJcbiAgICAgICAgICAgICAgICB2YXIgY2VsbCA9IG13LiQocm93KS5jaGlsZHJlbigndGQnKVtpbmRleF07XHJcbiAgICAgICAgICAgICAgICBpZiAoZGlyID09ICdsZWZ0JyB8fCBkaXIgPT0gJ2JvdGgnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuJChjZWxsKS5iZWZvcmUoXCI8dGQ+Jm5ic3A7PC90ZD5cIik7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBpZiAoZGlyID09ICdyaWdodCcgfHwgZGlyID09ICdib3RoJykge1xyXG4gICAgICAgICAgICAgICAgICAgIG13LiQoY2VsbCkuYWZ0ZXIoXCI8dGQ+Jm5ic3A7PC90ZD5cIik7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIGluc2VydFJvdzogZnVuY3Rpb24gKGRpciwgY2VsbCkge1xyXG4gICAgICAgICAgICB2YXIgY2VsbCA9IG13LiQoY2VsbClbMF07XHJcbiAgICAgICAgICAgIGlmIChjZWxsID09PSBudWxsKSB7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgdmFyIGRpciA9IGRpciB8fCAndW5kZXInO1xyXG4gICAgICAgICAgICB2YXIgcGFyZW50ID0gY2VsbC5wYXJlbnROb2RlLCBjZWxscyA9IG13LiQocGFyZW50KS5jaGlsZHJlbigndGQnKSwgaSA9IDAsIGwgPSBjZWxscy5sZW5ndGgsIGh0bWwgPSAnJztcclxuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIGh0bWwgKz0gJzx0ZD4mbmJzcDs8L3RkPic7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgdmFyIGh0bWwgPSAnPHRyPicgKyBodG1sICsgJzwvdHI+JztcclxuICAgICAgICAgICAgaWYgKGRpciA9PSAndW5kZXInIHx8IGRpciA9PSAnYm90aCcpIHtcclxuICAgICAgICAgICAgICAgIG13LiQocGFyZW50KS5hZnRlcihodG1sKVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmIChkaXIgPT0gJ2Fib3ZlJyB8fCBkaXIgPT0gJ2JvdGgnKSB7XHJcbiAgICAgICAgICAgICAgICBtdy4kKHBhcmVudCkuYmVmb3JlKGh0bWwpXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIGRlbGV0ZVJvdzogZnVuY3Rpb24gKGNlbGwpIHtcclxuICAgICAgICAgICAgbXcuJChjZWxsLnBhcmVudE5vZGUpLnJlbW92ZSgpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZGVsZXRlQ29sdW1uOiBmdW5jdGlvbiAoY2VsbCkge1xyXG4gICAgICAgICAgICB2YXIgaW5kZXggPSBtdy50b29scy5pbmRleChjZWxsKSwgYm9keSA9IGNlbGwucGFyZW50Tm9kZS5wYXJlbnROb2RlLCByb3dzID0gbXcuJChib2R5KS5jaGlsZHJlbigndHInKSwgbCA9IHJvd3MubGVuZ3RoLCBpID0gMDtcclxuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcclxuICAgICAgICAgICAgICAgIHZhciByb3cgPSByb3dzW2ldO1xyXG4gICAgICAgICAgICAgICAgbXcuJChyb3cuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ3RkJylbaW5kZXhdKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc2V0U3R5bGU6IGZ1bmN0aW9uIChjbHMsIGNlbGwpIHtcclxuICAgICAgICAgICAgdmFyIHRhYmxlID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKGNlbGwsICd0YWJsZScpO1xyXG4gICAgICAgICAgICBtdy50b29scy5jbGFzc05hbWVzcGFjZURlbGV0ZSh0YWJsZSwgJ213LXd5c2l3eWctdGFibGUnKTtcclxuICAgICAgICAgICAgbXcuJCh0YWJsZSkuYWRkQ2xhc3MoY2xzKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuIiwibXcubGF5b3V0UGx1cyA9IHtcbiAgICBjcmVhdGU6IGZ1bmN0aW9uKCl7XG4gICAgICAgIHRoaXMuX3RvcCA9ICQoJzxzcGFuIGNsYXNzPVwibXctZGVmYXVsdHMgbXctbGF5b3V0LXBsdXMgbXctbGF5b3V0LXBsdXMtdG9wXCI+QWRkIExheW91dDwvc3Bhbj4nKTtcbiAgICAgICAgdGhpcy5fYm90dG9tID0gJCgnPHNwYW4gY2xhc3M9XCJtdy1kZWZhdWx0cyBtdy1sYXlvdXQtcGx1cyBtdy1sYXlvdXQtcGx1cy1ib3R0b21cIj5BZGQgTGF5b3V0PC9zcGFuPicpO1xuICAgICAgICBtdy4kKGRvY3VtZW50LmJvZHkpLmFwcGVuZCh0aGlzLl90b3ApLmFwcGVuZCh0aGlzLl9ib3R0b20pO1xuXG4gICAgICAgIHRoaXMuX3RvcC5vbignbW91c2VlbnRlcicsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGRvY3VtZW50LmJvZHksICdib2R5LW13LWxheW91dC1wbHVzLWhvdmVyJyk7XG4gICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLnNlbGVjdChtdy5sYXlvdXRQbHVzLl9hY3RpdmUpO1xuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5fdG9wLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoZG9jdW1lbnQuYm9keSwgJ2JvZHktbXctbGF5b3V0LXBsdXMtaG92ZXInKTtcbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMuX2JvdHRvbS5vbignbW91c2VlbnRlcicsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGRvY3VtZW50LmJvZHksICdib2R5LW13LWxheW91dC1wbHVzLWhvdmVyJyk7XG4gICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdG9yLnNlbGVjdChtdy5sYXlvdXRQbHVzLl9hY3RpdmUpO1xuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5fYm90dG9tLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoZG9jdW1lbnQuYm9keSwgJ2JvZHktbXctbGF5b3V0LXBsdXMtaG92ZXInKVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGhpZGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5fdG9wLmNzcyh7dG9wOiAtOTk5LCBsZWZ0OiAtOTk5fSk7XG4gICAgICAgIHRoaXMuX2JvdHRvbS5jc3Moe3RvcDogLTk5OSwgbGVmdDogLTk5OX0pO1xuICAgICAgICB0aGlzLnBhdXNlID0gZmFsc2U7XG4gICAgfSxcbiAgICBwYXVzZTogZmFsc2UsXG4gICAgX2FjdGl2ZTogbnVsbCxcbiAgICBwb3NpdGlvbjpmdW5jdGlvbigpe1xuICAgICAgICB2YXIgJGxheW91dCA9IG13LiQodGhpcy5fYWN0aXZlKTtcbiAgICAgICAgdmFyIG9mZiA9ICRsYXlvdXQub2Zmc2V0KCk7XG4gICAgICAgIHZhciBsZWZ0ID0gKG9mZi5sZWZ0ICsgKCRsYXlvdXQub3V0ZXJXaWR0aCgpLzIpKTtcbiAgICAgICAgdGhpcy5fdG9wLmNzcyh7dG9wOiBvZmYudG9wIC0gMjAsIGxlZnQ6IGxlZnR9KTtcbiAgICAgICAgdGhpcy5fYm90dG9tLmNzcyh7dG9wOiBvZmYudG9wICsgJGxheW91dC5vdXRlckhlaWdodCgpLCBsZWZ0OiBsZWZ0fSk7XG4gICAgfSxcbiAgICBfcHJlcGFyZUxpc3Q6ZnVuY3Rpb24gKHRpcCwgYWN0aW9uKSB7XG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG4gICAgICAgIHZhciBpdGVtcyA9IG13LiQoJy5tb2R1bGVzLWxpc3QgbGknLCB0aXApO1xuICAgICAgICBtdy4kKCdpbnB1dCcsIHRpcCkub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLnNlYXJjaCh0aGlzLnZhbHVlLCBpdGVtcywgZnVuY3Rpb24gKGZvdW5kKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcylbZm91bmQ/J3Nob3cnOidoaWRlJ10oKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgICAgIG13LiQoJy5tb2R1bGVzLWxpc3QgbGknLCB0aXApLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBpZCA9IG13LmlkKCdtdy1sYXlvdXQtJyksIGVsID0gJzxkaXYgaWQ9XCInICsgaWQgKyAnXCI+PC9kaXY+JztcbiAgICAgICAgICAgIHZhciAkYWN0aXZlID0gbXcuJChtdy5sYXlvdXRQbHVzLl9hY3RpdmUpO1xuICAgICAgICAgICAgJGFjdGl2ZVthY3Rpb25dKGVsKTtcblxuICAgICAgICAgICAgdmFyIG5hbWUgPSAkYWN0aXZlLmF0dHIoJ2RhdGEtbW9kdWxlLW5hbWUnKTtcbiAgICAgICAgICAgIHZhciB0ZW1wbGF0ZSA9ICQodGhpcykuYXR0cigndGVtcGxhdGUnKTtcbiAgICAgICAgICAgIHZhciBjb25mID0ge2NsYXNzOiBtdy5sYXlvdXRQbHVzLl9hY3RpdmUuY2xhc3NOYW1lLCB0ZW1wbGF0ZTogdGVtcGxhdGV9O1xuXG4gICAgICAgICAgICAvKm13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcbiAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCgnIycgKyBpZCkucmVwbGFjZVdpdGgoJzxkaXYgaWQ9XCInICsgaWQgKyAnXCI+PC9kaXY+Jyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7Ki9cblxuICAgICAgICAgICAgbXcubG9hZF9tb2R1bGUoJ2xheW91dHMnLCAnIycgKyBpZCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGlkKSk7XG4gICAgICAgICAgICAgICAgbXcuZHJhZy5maXhlcygpO1xuICAgICAgICAgICAgICAgIC8qbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmxvYWRfbW9kdWxlKCdsYXlvdXRzJywgJyMnICsgaWQsIHVuZGVmaW5lZCwgY29uZik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTsqL1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmZpeF9wbGFjZWhvbGRlcnMoKTtcbiAgICAgICAgICAgICAgICB9LCA0MCk7XG4gICAgICAgICAgICAgICAgbXcuZHJvcGFibGUuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG13LmxheW91dFBsdXMubW9kZSA9PT0gJ0RpYWxvZycgPyBtdy5kaWFsb2cuZ2V0KHRpcCkucmVtb3ZlKCkgIDogJCh0aXApLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSwgY29uZik7XG4gICAgICAgICAgICBzY29wZS5wYXVzZSA9IGZhbHNlO1xuICAgICAgICB9KTtcbiAgICB9LFxuICAgIG1vZGU6ICd0b29sdGlwJywgLy8nRGlhbG9nJyxcbiAgICBpbml0U2VsZWN0b3I6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgdGhpcy5fdG9wLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHNjb3BlLnBhdXNlID0gdHJ1ZTtcbiAgICAgICAgICAgIHZhciB0aXAgPSBuZXcgbXdbbXcubGF5b3V0UGx1cy5tb2RlXSh7XG4gICAgICAgICAgICAgICAgY29udGVudDogbXdkLmdldEVsZW1lbnRCeUlkKCdwbHVzLWxheW91dHMtbGlzdCcpLmlubmVySFRNTCxcbiAgICAgICAgICAgICAgICBlbGVtZW50OiB0aGlzLFxuICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAncmlnaHQtY2VudGVyJyxcbiAgICAgICAgICAgICAgICB0ZW1wbGF0ZTogJ213LXRvb2x0aXAtZGVmYXVsdCBtdy10b29sdGlwLWluc2VydC1tb2R1bGUnLFxuICAgICAgICAgICAgICAgIGlkOiAnbXctcGx1cy10b29sdGlwLXNlbGVjdG9yJyxcbiAgICAgICAgICAgICAgICB0aXRsZTogbXcubGFuZygnU2VsZWN0IGxheW91dCcpLFxuICAgICAgICAgICAgICAgIHdpZHRoOiA0MDBcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgc2NvcGUuX3ByZXBhcmVMaXN0KGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtdy1wbHVzLXRvb2x0aXAtc2VsZWN0b3InKSwgJ2JlZm9yZScpO1xuICAgICAgICAgICAgJCgnI213LXBsdXMtdG9vbHRpcC1zZWxlY3RvciBpbnB1dCcpLmZvY3VzKCk7XG4gICAgICAgICAgICAkKCcjbXctcGx1cy10b29sdGlwLXNlbGVjdG9yJykuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuXG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLl9ib3R0b20ub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2NvcGUucGF1c2UgPSB0cnVlO1xuICAgICAgICAgICAgdmFyIHRpcCA9IG5ldyBtd1ttdy5sYXlvdXRQbHVzLm1vZGVdKHtcbiAgICAgICAgICAgICAgICBjb250ZW50OiBtd2QuZ2V0RWxlbWVudEJ5SWQoJ3BsdXMtbGF5b3V0cy1saXN0JykuaW5uZXJIVE1MLFxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRoaXMsXG4gICAgICAgICAgICAgICAgcG9zaXRpb246ICdyaWdodC1jZW50ZXInLFxuICAgICAgICAgICAgICAgIHRlbXBsYXRlOiAnbXctdG9vbHRpcC1kZWZhdWx0IG13LXRvb2x0aXAtaW5zZXJ0LW1vZHVsZScsXG4gICAgICAgICAgICAgICAgaWQ6ICdtdy1wbHVzLXRvb2x0aXAtc2VsZWN0b3InLFxuICAgICAgICAgICAgICAgIHRpdGxlOiBtdy5sYW5nKCdTZWxlY3QgbGF5b3V0JyksXG4gICAgICAgICAgICAgICAgd2lkdGg6IDQwMFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBzY29wZS5fcHJlcGFyZUxpc3QoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ213LXBsdXMtdG9vbHRpcC1zZWxlY3RvcicpLCAnYWZ0ZXInKTtcbiAgICAgICAgICAgICQoJyNtdy1wbHVzLXRvb2x0aXAtc2VsZWN0b3IgaW5wdXQnKS5mb2N1cygpO1xuICAgICAgICAgICAgJCgnI213LXBsdXMtdG9vbHRpcC1zZWxlY3RvcicpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgfSk7XG5cbiAgICB9LFxuICAgIGhhbmRsZTogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICBtdy4kKHdpbmRvdykub24oJ3Jlc2l6ZScsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICBpZiAoc2NvcGUuX2FjdGl2ZSkge1xuICAgICAgICAgICAgICAgIHNjb3BlLnBvc2l0aW9uKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBtdy5vbignbW9kdWxlT3ZlciBNb2R1bGVDbGljaycsIGZ1bmN0aW9uIChlLCBtb2R1bGUpIHtcbiAgICAgICAgICAgIGlmIChtb2R1bGUuZGF0YXNldC50eXBlID09PSAnbGF5b3V0cycgJiYgIXNjb3BlLnBhdXNlKSB7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2FjdGl2ZSA9IG1vZHVsZTtcbiAgICAgICAgICAgICAgICBzY29wZS5wb3NpdGlvbigpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBzY29wZS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgX3JlYWR5OiBmYWxzZSxcbiAgICBpbml0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICghdGhpcy5fcmVhZHkpIHtcbiAgICAgICAgICAgIHRoaXMuX3JlYWR5ID0gdHJ1ZTtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlKCk7XG4gICAgICAgICAgICB0aGlzLmhhbmRsZSgpO1xuICAgICAgICAgICAgdGhpcy5pbml0U2VsZWN0b3IoKTtcbiAgICAgICAgfVxuICAgIH1cbn07XG5cbiQod2luZG93KS5vbignbG9hZCcsIGZ1bmN0aW9uICgpIHtcbiAgICBtdy5sYXlvdXRQbHVzLmluaXQoKTtcbn0pO1xuIiwibXcubGl2ZV9lZGl0ID0gbXcubGl2ZV9lZGl0IHx8IHt9O1xuXG5tdy5saXZlX2VkaXQucmVnaXN0cnkgPSBtdy5saXZlX2VkaXQucmVnaXN0cnkgfHwge307XG5cbm13LmxpdmVfZWRpdC5oYXNBYmlsaXR5VG9Ecm9wRWxlbWVudHNJbnNpZGUgPSBmdW5jdGlvbih0YXJnZXQpIHtcbiAgICB2YXIgaXRlbXMgPSAvXihzcGFufGhbMS02XXxocnx1bHxvbHxpbnB1dHx0YWJsZXxifGVtfGl8YXxpbWd8dGV4dGFyZWF8YnJ8Y2FudmFzfGZvbnR8c3RyaWtlfHN1YnxzdXB8ZGx8YnV0dG9ufHNtYWxsfHNlbGVjdHxiaWd8YWJicnxib2R5KSQvaTtcbiAgICBpZiAodHlwZW9mIHRhcmdldCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgcmV0dXJuICFpdGVtcy50ZXN0KHRhcmdldCk7XG4gICAgfVxuICAgIGlmKCFtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KHRhcmdldCwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKSl7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgaWYobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKHRhcmdldCwgWydwbGFpbi10ZXh0J10pKXtcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbiAgICB2YXIgeCA9IGl0ZW1zLnRlc3QodGFyZ2V0Lm5vZGVOYW1lKTtcbiAgICBpZiAoeCkge1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuICAgIGlmIChtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKHRhcmdldCwgJ21vZHVsZScpKSB7XG4gICAgICAgIGlmIChtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKHRhcmdldCwgJ2VkaXQnKSkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICB9IGVsc2UgaWYgKG13LnRvb2xzLmhhc0NsYXNzKHRhcmdldCwgJ21vZHVsZScpKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgcmV0dXJuIHRydWU7XG59O1xuXG5tdy5yZXF1aXJlKCdkaWFsb2cuanMnKVxuXG5cbm13LmxpdmVfZWRpdC5zaG93U2V0dGluZ3MgPSBmdW5jdGlvbiAoYSwgb3B0cykge1xuXG4gICAgdmFyIGxpdmVlZGl0ID0gb3B0cy5saXZlZWRpdCB8fCBmYWxzZTtcbiAgICB2YXIgbW9kZSA9IG9wdHMubW9kZSB8fCAgJ21vZGFsJztcblxuICAgIHZhciB2aWV3ID0gb3B0cy52aWV3IHx8ICdhZG1pbic7XG4gICAgdmFyIG1vZHVsZV90eXBlO1xuICAgIGlmICh0eXBlb2YgYSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgbW9kdWxlX3R5cGUgPSBhO1xuICAgICAgICB2YXIgbW9kdWxlX2lkID0gYTtcbiAgICAgICAgdmFyIG1vZF9zZWwgPSBtdy4kKGEgKyAnOmZpcnN0Jyk7XG4gICAgICAgIGlmIChtb2Rfc2VsLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIHZhciBhdHRyID0gJChtb2Rfc2VsKS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBhdHRyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIgIT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgYXR0ciA9ICFhdHRyLmNvbnRhaW5zKFwiI1wiKSA/IGF0dHIgOiBhdHRyLnJlcGxhY2UoXCIjXCIsICcnKTtcbiAgICAgICAgICAgICAgICBtb2R1bGVfaWQgPSBhdHRyO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGF0dHIyID0gJChtb2Rfc2VsKS5hdHRyKCd0eXBlJyk7XG4gICAgICAgICAgICBhdHRyID0gJChtb2Rfc2VsKS5hdHRyKCdkYXRhLXR5cGUnKTtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYXR0ciAhPT0gdHlwZW9mIHVuZGVmaW5lZCAmJiBhdHRyICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgIG1vZHVsZV90eXBlID0gYXR0cjtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAodHlwZW9mIGF0dHIyICE9PSB0eXBlb2YgdW5kZWZpbmVkICYmIGF0dHIyICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgIG1vZHVsZV90eXBlID0gYXR0cjI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBhID0gbW9kX3NlbFswXVxuICAgICAgICB9XG4gICAgfVxuXG4gICAgdmFyIGN1cnIgPSBhIHx8ICQoXCIjbXdfaGFuZGxlX21vZHVsZVwiKS5kYXRhKFwiY3VyclwiKTtcbiAgICBpZighY3Vycil7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG4gICAgaWYodHlwZW9mKGN1cnIpID09PSAndW5kZWZpbmVkJyl7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG4gICAgdmFyIGF0dHJpYnV0ZXMgPSB7fTtcblxuICAgIGlmIChjdXJyICYmIGN1cnIuYXR0cmlidXRlcykge1xuICAgICAgICAkLmVhY2goY3Vyci5hdHRyaWJ1dGVzLCBmdW5jdGlvbiAoaW5kZXgsIGF0dHIpIHtcbiAgICAgICAgICAgIGF0dHJpYnV0ZXNbYXR0ci5uYW1lXSA9IGF0dHIudmFsdWU7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIHZhciBpZnJhbWVfaWRfc2lkZWJhciA9ICdqcy1pZnJhbWUtbW9kdWxlLXNldHRpbmdzLScgKyBjdXJyLmlkO1xuICAgIHZhciBpZnJhbWVfaWRfc2lkZWJhcl93cmFwcGVyX2lkID0gJ3NpZGViYXItZnJhbWUtd3JhcHBlci0nICsgaWZyYW1lX2lkX3NpZGViYXI7XG5cbiAgICB2YXIgZGF0YTEgPSBhdHRyaWJ1dGVzO1xuXG4gICAgbW9kdWxlX3R5cGUgPSBudWxsO1xuICAgIGlmIChkYXRhMVsnZGF0YS10eXBlJ10gIT09IHVuZGVmaW5lZCkge1xuICAgICAgICBtb2R1bGVfdHlwZSA9IGRhdGExWydkYXRhLXR5cGUnXTtcbiAgICAgICAgZGF0YTFbJ2RhdGEtdHlwZSddID0gZGF0YTFbJ2RhdGEtdHlwZSddICsgJy9hZG1pbic7XG4gICAgfVxuICAgIGlmIChkYXRhMVsnZGF0YS1tb2R1bGUtbmFtZSddICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgZGVsZXRlKGRhdGExWydkYXRhLW1vZHVsZS1uYW1lJ10pO1xuICAgIH1cbiAgICBpZiAoZGF0YTFbJ3R5cGUnXSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIG1vZHVsZV90eXBlID0gZGF0YTFbJ3R5cGUnXTtcbiAgICAgICAgZGF0YTFbJ3R5cGUnXSA9IGRhdGExWyd0eXBlJ10gKyAnL2FkbWluJztcbiAgICB9XG4gICAgaWYgKG1vZHVsZV90eXBlICE9IG51bGwgJiYgdmlldyAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGRhdGExWydkYXRhLXR5cGUnXSA9IGRhdGExWyd0eXBlJ10gPSBtb2R1bGVfdHlwZSArICcvJyArIHZpZXc7XG4gICAgfVxuICAgIGlmICh0eXBlb2YgZGF0YTFbJ2NsYXNzJ10gIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIGRlbGV0ZShkYXRhMVsnY2xhc3MnXSk7XG4gICAgfVxuICAgIGlmICh0eXBlb2YgZGF0YTFbJ3N0eWxlJ10gIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIGRlbGV0ZShkYXRhMVsnc3R5bGUnXSk7XG4gICAgfVxuICAgIGlmICh0eXBlb2YgZGF0YTEuY29udGVudGVkaXRhYmxlICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICBkZWxldGUoZGF0YTEuY29udGVudGVkaXRhYmxlKTtcbiAgICB9XG4gICAgZGF0YTEubGl2ZV9lZGl0ID0gbGl2ZWVkaXQ7XG4gICAgZGF0YTEubW9kdWxlX3NldHRpbmdzID0gJ3RydWUnO1xuICAgIGlmICh2aWV3ICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgZGF0YTEudmlldyA9IHZpZXc7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICBkYXRhMS52aWV3ID0gJ2FkbWluJztcbiAgICB9XG4gICAgaWYgKGRhdGExLmZyb21fdXJsID09IHVuZGVmaW5lZCkge1xuICAgICAgICAvL2RhdGExLmZyb21fdXJsID0gbXcudG9wKCkud2luLmxvY2F0aW9uO1xuICAgICAgICBkYXRhMS5mcm9tX3VybCA9IHdpbmRvdy5wYXJlbnQubG9jYXRpb247XG4gICAgfVxuICAgIHZhciBtb2RhbF9uYW1lID0gJ21vZHVsZS1zZXR0aW5ncy0nICsgY3Vyci5pZDtcbiAgICBpZiAodHlwZW9mKGRhdGExLnZpZXcuaGFzaCkgPT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAvL3ZhciBtb2RhbF9uYW1lID0gJ21vZHVsZS1zZXR0aW5ncy0nICsgY3Vyci5pZCArKGRhdGExLnZpZXcuaGFzaCgpKTtcbiAgICB9XG4gICAgLy9kYXRhMS5saXZlX2VkaXRfc2lkZWJhciA9IHRydWU7XG5cbiAgICB2YXIgc3JjID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyBcImFwaS9tb2R1bGU/XCIgKyBqc29uMnVybChkYXRhMSk7XG5cbiAgICBpZiAoc2VsZiAhPT0gdG9wIHx8IC8qIW13LmxpdmVFZGl0U2V0dGluZ3MuYWN0aXZlIHx8ICovIG1vZGUgPT09ICdtb2RhbCcpIHtcbiAgICAgICAgLy9yZW1vdmUgZnJvbSBzaWRlYmFyXG4gICAgICAgICQoXCIjXCIgKyBpZnJhbWVfaWRfc2lkZWJhcikucmVtb3ZlKCk7XG5cbiAgICAgICAgLy9jbG9zZSBzaWRlYmFyXG4gICAgICAgIGlmKG13LmxpdmVFZGl0U2V0dGluZ3MgJiYgbXcubGl2ZUVkaXRTZXR0aW5ncy5hY3RpdmUpe1xuICAgICAgICAgICAgIG13LmxpdmVFZGl0U2V0dGluZ3MuaGlkZSgpO1xuICAgICAgICB9XG4gICAgICAgIHZhciBoYXMgPSBtdy4kKCcjJyArIG1vZGFsX25hbWUpO1xuICAgICAgICBpZihoYXMubGVuZ3RoKXtcbiAgICAgICAgICAgIHZhciBkaWFsb2cgPSBtdy5kaWFsb2cuZ2V0KGhhc1swXSk7XG4gICAgICAgICAgICBkaWFsb2cuc2hvdygpO1xuICAgICAgICAgICAgcmV0dXJuIGRpYWxvZztcbiAgICAgICAgfVxuICAgICAgICB2YXIgbm1vZGFsID0gbXcuZGlhbG9nSWZyYW1lKHtcbiAgICAgICAgICAgIHVybDogc3JjLFxuICAgICAgICAgICAgd2lkdGg6IDUzMixcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgYXV0b0hlaWdodDp0cnVlLFxuICAgICAgICAgICAgaWQ6IG1vZGFsX25hbWUsXG4gICAgICAgICAgICB0aXRsZTonJyxcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ213LWRpYWxvZy1tb2R1bGUtc2V0dGluZ3MnLFxuICAgICAgICAgICAgY2xvc2VCdXR0b25BY3Rpb246ICdyZW1vdmUnXG4gICAgICAgIH0pO1xuXG4gICAgICAgIG5tb2RhbC5pZnJhbWUuY29udGVudFdpbmRvdy50aGlzbW9kYWwgPSBubW9kYWw7XG4gICAgICAgIHJldHVybiBubW9kYWw7XG5cbiAgICB9IGVsc2Uge1xuXG5cbiAgICAgICAgaWYoIW13LmxpdmVFZGl0U2V0dGluZ3MuYWN0aXZlKXtcbiAgICAgICAgICAgIG13LmxpdmVFZGl0U2V0dGluZ3Muc2hvdygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYobXcuc2lkZWJhclNldHRpbmdzVGFicyl7XG4gICAgICAgICAgICBtdy5zaWRlYmFyU2V0dGluZ3NUYWJzLnNldCgyKTtcbiAgICAgICAgfVxuXG5cblxuICAgICAgICBkYXRhMS5saXZlX2VkaXRfc2lkZWJhciA9IHRydWU7XG5cbiAgICAgICAgdmFyIHNyYyA9IG13LnNldHRpbmdzLnNpdGVfdXJsICsgXCJhcGkvbW9kdWxlP1wiICsganNvbjJ1cmwoZGF0YTEpO1xuXG5cbiAgICAgICAgdmFyIG1vZF9zZXR0aW5nc19pZnJhbWVfaHRtbF9mciA9ICcnICtcbiAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwianMtbW9kdWxlLXNldHRpbmdzLWVkaXQtaXRlbS1ncm91cC1mcmFtZSBsb2FkaW5nXCIgaWQ9XCInICsgaWZyYW1lX2lkX3NpZGViYXJfd3JhcHBlcl9pZCArICdcIj4nICtcbiAgICAgICAgICAgICc8aWZyYW1lIHNyYz1cIicgKyBzcmMgKyAnXCIgZnJhbWVib3JkZXI9XCIwXCIgb25sb2FkPVwidGhpcy5wYXJlbnROb2RlLmNsYXNzTGlzdC5yZW1vdmUoXFwnbG9hZGluZ1xcJylcIj4nICtcbiAgICAgICAgICAgICc8L2Rpdj4nO1xuXG5cbiAgICAgICAgdmFyIHNpZGViYXJfdGl0bGVfYm94ID0gbXcubGl2ZV9lZGl0LmdldE1vZHVsZVRpdGxlQmFyKG1vZHVsZV90eXBlLCBjdXJyLmlkKTtcblxuXG4gICAgICAgICB2YXIgbW9kX3NldHRpbmdzX2lmcmFtZV9odG1sID0gJzxkaXYgIGlkPVwiJyArIGlmcmFtZV9pZF9zaWRlYmFyICsgJ1wiIGNsYXNzPVwianMtbW9kdWxlLXNldHRpbmdzLWVkaXQtaXRlbS1ncm91cFwiPidcbiAgICAgICAgICAgICsgc2lkZWJhcl90aXRsZV9ib3hcbiAgICAgICAgICAgICsgbW9kX3NldHRpbmdzX2lmcmFtZV9odG1sX2ZyXG4gICAgICAgICAgICArICc8L2Rpdj4nO1xuXG5cbiAgICAgICAgaWYgKCEkKFwiI1wiICsgaWZyYW1lX2lkX3NpZGViYXIpLmxlbmd0aCkge1xuICAgICAgICAgICAgJChcIiNqcy1saXZlLWVkaXQtbW9kdWxlLXNldHRpbmdzLWl0ZW1zXCIpLmFwcGVuZChtb2Rfc2V0dGluZ3NfaWZyYW1lX2h0bWwpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKCQoXCIjXCIgKyBpZnJhbWVfaWRfc2lkZWJhcikubGVuZ3RoKSB7XG4gICAgICAgICAgICAkKCcuanMtbW9kdWxlLXNldHRpbmdzLWVkaXQtaXRlbS1ncm91cCcpLmhpZGUoKTtcbiAgICAgICAgICAgICQoXCIjXCIgKyBpZnJhbWVfaWRfc2lkZWJhcikuYXR0cignZGF0YS1zZXR0aW5ncy1mb3ItbW9kdWxlJywgY3Vyci5pZCk7XG5cbiAgICAgICAgICAgICQoXCIjXCIgKyBpZnJhbWVfaWRfc2lkZWJhcikuc2hvdygpO1xuICAgICAgICB9XG4gICAgfVxuXG59XG5cblxubXcubGl2ZV9lZGl0LmdldE1vZHVsZVRpdGxlQmFyID0gZnVuY3Rpb24gKG1vZHVsZV90eXBlLCBtb2R1bGVfaWQpIHtcblxuICAgIHZhciBtb2RfaWNvbiA9IG13LmxpdmVfZWRpdC5nZXRNb2R1bGVJY29uKG1vZHVsZV90eXBlKTtcbiAgICB2YXIgbW9kX3RpdGxlID0gbXcubGl2ZV9lZGl0LmdldE1vZHVsZVRpdGxlKG1vZHVsZV90eXBlKTtcbiAgICB2YXIgbW9kX2Rlc2NyID0gbXcubGl2ZV9lZGl0LmdldE1vZHVsZURlc2NyaXB0aW9uKG1vZHVsZV90eXBlKTtcblxuICAgIHZhciBzaWRlYmFyX3RpdGxlX2JveCA9IFwiPGRpdiBjbGFzcz0nbXdfbW9kdWxlX3NldHRpbmdzX3NpZGViYXJfdGl0bGVfd3JhcHBlciBqcy1tb2R1bGUtdGl0bGViYXItXCIrbW9kdWxlX2lkK1wiJz5cIiArIG1vZF9pY29uO1xuICAgIHNpZGViYXJfdGl0bGVfYm94ID0gc2lkZWJhcl90aXRsZV9ib3ggKyBcIjxkaXYgY2xhc3M9J2pzLW1vZHVsZS1zaWRlYmFyLXNldHRpbmdzLW1lbnUtaG9sZGVyJz5cIiArIFwiPC9kaXY+XCI7XG4gICAgc2lkZWJhcl90aXRsZV9ib3ggPSBzaWRlYmFyX3RpdGxlX2JveCArIFwiPGRpdiBjbGFzcz0nbXdfbW9kdWxlX3NldHRpbmdzX3NpZGViYXJfdGl0bGUnPlwiICsgbW9kX3RpdGxlICsgXCI8L2Rpdj5cIjtcblxuICAgIGlmIChtb2RfdGl0bGUgIT0gbW9kX2Rlc2NyKSB7XG4gICAgICAgIC8vICBzaWRlYmFyX3RpdGxlX2JveCA9IHNpZGViYXJfdGl0bGVfYm94ICsgXCI8ZGl2IGNsYXNzPSdtd19tb2R1bGVfc2V0dGluZ3Nfc2lkZWJhcl9kZXNjcmlwdGlvbic+XCIgKyBtb2RfZGVzY3IgKyBcIjwvZGl2PlwiO1xuICAgIH1cbiAgICBzaWRlYmFyX3RpdGxlX2JveCA9IHNpZGViYXJfdGl0bGVfYm94ICsgXCI8L2Rpdj5cIjtcbiAgICByZXR1cm4gc2lkZWJhcl90aXRsZV9ib3g7XG59O1xuXG5tdy5saXZlX2VkaXQuZ2V0TW9kdWxlSWNvbiA9IGZ1bmN0aW9uIChtb2R1bGVfdHlwZSkge1xuICAgIGlmIChtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdICYmIG13LmxpdmVfZWRpdC5yZWdpc3RyeVttb2R1bGVfdHlwZV0uaWNvbikge1xuICAgICAgICByZXR1cm4gJzxzcGFuIGNsYXNzPVwibXdfbW9kdWxlX3NldHRpbmdzX3NpZGViYXJfaWNvblwiIHN0eWxlPVwiYmFja2dyb3VuZC1pbWFnZTogdXJsKCcgKyBtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdLmljb24gKyAnKVwiPjwvc3Bhbj4nO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgcmV0dXJuICc8c3BhbiBjbGFzcz1cIm13LWljb24tZ2VhclwiPjwvc3Bhbj4mbmJzcDsmbmJzcDsnO1xuICAgIH1cbn07XG5tdy5saXZlX2VkaXQuZ2V0TW9kdWxlVGl0bGUgPSBmdW5jdGlvbiAobW9kdWxlX3R5cGUpIHtcbiAgICBpZiAobXcubGl2ZV9lZGl0LnJlZ2lzdHJ5W21vZHVsZV90eXBlXSAmJiBtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdLnRpdGxlKSB7XG4gICAgICAgIHJldHVybiBtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdLnRpdGxlO1xuICAgIH0gZWxzZSBpZiAobXcubGl2ZV9lZGl0LnJlZ2lzdHJ5W21vZHVsZV90eXBlXSAmJiBtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdLm5hbWUpIHtcbiAgICAgICAgcmV0dXJuIG13LmxpdmVfZWRpdC5yZWdpc3RyeVttb2R1bGVfdHlwZV0ubmFtZTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIHJldHVybiAnJztcbiAgICB9XG59O1xubXcubGl2ZV9lZGl0LmdldE1vZHVsZURlc2NyaXB0aW9uID0gZnVuY3Rpb24gKG1vZHVsZV90eXBlKSB7XG4gICAgaWYgKG13LmxpdmVfZWRpdC5yZWdpc3RyeVttb2R1bGVfdHlwZV0gJiYgdHlwZW9mKG13LmxpdmVfZWRpdC5yZWdpc3RyeVttb2R1bGVfdHlwZV0uZGVzY3JpcHRpb24pICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHJldHVybiBtdy5saXZlX2VkaXQucmVnaXN0cnlbbW9kdWxlX3R5cGVdLmRlc2NyaXB0aW9uO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgcmV0dXJuICcnO1xuICAgIH1cbn07XG5cblxuIiwiXG4vKipcbiAqIE1ha2VzIERyb3BwYWJsZSBhcmVhXG4gKlxuICogQHJldHVybiBEb20gRWxlbWVudFxuICovXG5tdy5kcm9wYWJsZXMgPSB7XG4gICAgcHJlcGFyZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBkcm9wYWJsZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBkcm9wYWJsZS5jbGFzc05hbWUgPSAnbXdfZHJvcGFibGUnO1xuICAgICAgICBkcm9wYWJsZS5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJtd19kcm9wYWJsZV9hcnJcIj48L3NwYW4+JztcbiAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChkcm9wYWJsZSk7XG4gICAgICAgIG13LmRyb3BhYmxlID0gbXcuJChkcm9wYWJsZSk7XG4gICAgICAgIG13LmRyb3BhYmxlLmhpZGUgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgcmV0dXJuIG13LiQodGhpcykuYWRkQ2xhc3MoJ213X2Ryb3BhYmxlX2hpZGRlbicpO1xuICAgICAgICB9O1xuICAgICAgICBtdy5kcm9wYWJsZS5zaG93ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHJldHVybiBtdy4kKHRoaXMpLnJlbW92ZUNsYXNzKCdtd19kcm9wYWJsZV9oaWRkZW4nKTtcbiAgICAgICAgfTtcbiAgICAgICAgbXcuZHJvcGFibGUuaGlkZSgpXG4gICAgfSxcbiAgICB1c2VySW50ZXJhY3Rpb25DbGFzc2VzOmZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBiZ0hvbGRlcnMgPSBtd2QucXVlcnlTZWxlY3RvckFsbChcIi5lZGl0LmJhY2tncm91bmQtaW1hZ2UtaG9sZGVyLCAuZWRpdCAuYmFja2dyb3VuZC1pbWFnZS1ob2xkZXIsIC5lZGl0W3N0eWxlKj0nYmFja2dyb3VuZC1pbWFnZSddLCAuZWRpdCBbc3R5bGUqPSdiYWNrZ3JvdW5kLWltYWdlJ11cIik7XG4gICAgICAgIHZhciBub0VkaXRNb2R1bGVzID0gbXdkLnF1ZXJ5U2VsZWN0b3JBbGwoJy5tb2R1bGUnICsgbXcubm9FZGl0TW9kdWxlcy5qb2luKCcsLm1vZHVsZScpKTtcbiAgICAgICAgdmFyIGVkaXRzID0gbXdkLnF1ZXJ5U2VsZWN0b3JBbGwoJy5lZGl0Jyk7XG4gICAgICAgIHZhciBpID0gMCwgaTEgPSAwLCBpMiA9IDA7XG4gICAgICAgIGZvciAoIDsgaTxiZ0hvbGRlcnMubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICB2YXIgY3VyciA9IGJnSG9sZGVyc1tpXTtcbiAgICAgICAgICAgIHZhciBwbyA9IG13LnRvb2xzLnBhcmVudHNPcmRlcihjdXJyLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuICAgICAgICAgICAgaWYocG8ubW9kdWxlID09PSAtMSB8fCAocG8uZWRpdDxwby5tb2R1bGUgJiYgcG8uZWRpdCAhPT0gLTEpKXtcbiAgICAgICAgICAgICAgICBpZighbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgJ21vZHVsZScpKXtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MoY3VyciwgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgY3Vyci5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgPSBjdXJyLnN0eWxlLmJhY2tncm91bmRJbWFnZSB8fCAnbm9uZSc7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZm9yICggOyBpMTxub0VkaXRNb2R1bGVzLmxlbmd0aDsgaTErKyApIHtcbiAgICAgICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKG5vRWRpdE1vZHVsZXNbaV0sICdtb2R1bGUnKTtcbiAgICAgICAgfVxuICAgICAgICBmb3IgKCA7IGkyPGVkaXRzLmxlbmd0aDsgaTIrKyApIHtcbiAgICAgICAgICAgIHZhciBhbGwgPSBtdy5lYS5oZWxwZXJzLmdldEVsZW1lbnRzTGlrZShcIjpub3QoLmVsZW1lbnQpXCIsIGVkaXRzW2kyXSksIGkyYSA9IDA7XG4gICAgICAgICAgICB2YXIgYWxsQWxsb3dEcm9wcyA9IGVkaXRzW2kyXS5xdWVyeVNlbGVjdG9yQWxsKFwiLmFsbG93LWRyb3BcIiksIGkzYSA9IDA7XG4gICAgICAgICAgICBmb3IoIDsgaTNhPGFsbEFsbG93RHJvcHMubGVuZ3RoOyBpM2ErKyl7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MoYWxsQWxsb3dEcm9wc1tpM2FdLCAnZWxlbWVudCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZm9yKCA7IGkyYTxhbGwubGVuZ3RoOyBpMmErKyl7XG4gICAgICAgICAgICAgICAgaWYoIW13LnRvb2xzLmhhc0NsYXNzKGFsbFtpMmFdLCAnbW9kdWxlJykpe1xuICAgICAgICAgICAgICAgICAgICBpZihtdy5lYS5jYW5Ecm9wKGFsbFtpMmFdKSl7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5hZGRDbGFzcyhhbGxbaTJhXSwgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG5cbiAgICAgICAgaWYoZG9jdW1lbnQuYm9keS5jbGFzc0xpc3Qpe1xuICAgICAgICAgICAgdmFyIGRpc3BsYXlFZGl0b3IgPSBtdy53eXNpd3lnLmlzU2VsZWN0aW9uRWRpdGFibGUoKTtcbiAgICAgICAgICAgIGlmKCFkaXNwbGF5RWRpdG9yKXtcbiAgICAgICAgICAgICAgICB2YXIgZWRpdG9yID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLm13X2VkaXRvcicpO1xuICAgICAgICAgICAgICAgIGlmKGVkaXRvciAmJiBlZGl0b3IuY29udGFpbnMoZG9jdW1lbnQuYWN0aXZlRWxlbWVudCkpIGRpc3BsYXlFZGl0b3IgPSB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGZvY3VzZWROb2RlID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGdldFNlbGVjdGlvbigpLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICB2YXIgaXNTYWZlTW9kZSA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3Nlcyhmb2N1c2VkTm9kZSwgWydzYWZlLW1vZGUnXSkgO1xuICAgICAgICAgICAgdmFyIGlzUGxhaW5UZXh0ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKGZvY3VzZWROb2RlLCBbJ3BsYWluLXRleHQnXSkgO1xuICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5jbGFzc0xpc3RbKCBkaXNwbGF5RWRpdG9yID8gJ2FkZCcgOiAncmVtb3ZlJyApXSgnbXctYWN0aXZlLWVsZW1lbnQtaXNlZGl0YWJsZScpO1xuICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5jbGFzc0xpc3RbKCBpc1NhZmVNb2RlID8gJ2FkZCcgOiAncmVtb3ZlJyApXSgnbXctYWN0aXZlLWVsZW1lbnQtaXMtaW4tc2FmZS1tb2RlJyk7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdFsoIGlzUGxhaW5UZXh0ID8gJ2FkZCcgOiAncmVtb3ZlJyApXSgnbXctYWN0aXZlLWVsZW1lbnQtaXMtcGxhaW4tdGV4dCcpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBmaW5kTmVhcmVzdDpmdW5jdGlvbihldmVudCxzZWxlY3RvcnMpe1xuXG4gICAgc2VsZWN0b3JzID0gKHNlbGVjdG9ycyB8fCBtdy5kcmFnLnNlY3Rpb25fc2VsZWN0b3JzKS5zbGljZSgwKTtcblxuXG4gICAgZm9yKHZhciBpeCA9IDAgOyBpPHNlbGVjdG9ycy5sZW5ndGggOyBpeCsrKXtcbiAgICAgICAgc2VsZWN0b3JzW2l4XSA9ICcuZWRpdCAnICsgc2VsZWN0b3JzW2l4XS50cmltKCk7XG4gICAgfVxuXG4gICAgc2VsZWN0b3JzID0gc2VsZWN0b3JzLmpvaW4oJywnKTtcblxuICAgICAgdmFyIGNvb3JkcyA9IHsgeTo5OTk5OTk5OSB9LFxuICAgICAgICAgIHkgPSBtdy5ldmVudC5wYWdlKGV2ZW50KS55LFxuICAgICAgICAgIGFsbCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3JzKSxcbiAgICAgICAgICBpID0gMCxcbiAgICAgICAgICBmaW5hbCA9IHtcbiAgICAgICAgICAgIGVsZW1lbnQ6bnVsbCxcbiAgICAgICAgICAgIHBvc2l0aW9uOm51bGxcbiAgICAgICAgICB9O1xuICAgICAgZm9yKCA7IGk8IGFsbC5sZW5ndGg7IGkrKyl7XG4gICAgICAgIHZhciBvcmQgPSBtdy50b29scy5wYXJlbnRzT3JkZXIoYWxsW2ldLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuICAgICAgICBpZihvcmQuZWRpdCA9PT0gLTEgfHwgKChvcmQubW9kdWxlICE9PSAtMSAmJiBvcmQuZWRpdCAhPT0gLTEgKSAmJiBvcmQubW9kdWxlIDwgb3JkLmVkaXQpKXtcbiAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgfVxuICAgICAgICBpZighbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdE9yTm9uZShhbGxbaV0sIFsnYWxsb3ctZHJvcCcsICdub2Ryb3AnXSkpe1xuICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICB9XG4gICAgICAgIHZhciBlbCA9IG13LiQoYWxsW2ldKSwgb2ZmdG9wID0gZWwub2Zmc2V0KCkudG9wO1xuICAgICAgICB2YXIgdjEgPSBvZmZ0b3AgLSB5O1xuICAgICAgICB2YXIgdjIgPSB5IC0gKG9mZnRvcCArIGVsWzBdLm9mZnNldEhlaWdodCk7XG4gICAgICAgIHZhciB2ID0gdjEgPiAwID8gdjEgOiB2MjtcbiAgICAgICAgaWYoY29vcmRzLnkgPiB2KXtcblxuICAgICAgICAgIGZpbmFsLmVsZW1lbnQgPSBhbGxbaV07XG4gICAgICAgIH1cbiAgICAgICAgaWYoY29vcmRzLnkgPiB2ICYmIHYxID4gMCl7XG4gICAgICAgICAgZmluYWwucG9zaXRpb24gPSAndG9wJztcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmKGNvb3Jkcy55ID4gdiAmJiB2MiA+IDApe1xuICAgICAgICAgIGZpbmFsLnBvc2l0aW9uID0gJ2JvdHRvbSc7XG4gICAgICAgIH1cbiAgICAgICAgaWYoY29vcmRzLnkgPiB2KXtcblxuICAgICAgICAgIGNvb3Jkcy55ID0gdlxuICAgICAgICB9XG5cbiAgICAgIH1cbiAgICAgIHJldHVybiBmaW5hbDtcbiAgICB9LFxuICAgIGRpc3BsYXk6IGZ1bmN0aW9uKGVsKSB7XG5cbiAgICAgICAgZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgdmFyIG9mZnNldCA9IGVsLm9mZnNldCgpO1xuICAgICAgICB2YXIgd2lkdGggPSBlbC5vdXRlcldpZHRoKCk7XG4gICAgICAgIHZhciBoZWlnaHQgPSBlbC5vdXRlckhlaWdodCgpO1xuICAgICAgICBtdy5kcm9wYWJsZS5jc3Moe1xuICAgICAgICAgICAgdG9wOiBvZmZzZXQudG9wICsgaGVpZ2h0LFxuICAgICAgICAgICAgbGVmdDogb2Zmc2V0LmxlZnQsXG4gICAgICAgICAgICB3aWR0aDogd2lkdGhcbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBzZXQ6IGZ1bmN0aW9uKHBvcywgb2Zmc2V0LCBoZWlnaHQsIHdpZHRoKSB7XG4gICAgICAgIGlmIChwb3MgPT09ICd0b3AnKSB7XG5cbiAgICAgICAgICAgIG13LmRyb3BhYmxlLmNzcyh7XG4gICAgICAgICAgICAgICAgdG9wOiBvZmZzZXQudG9wIC0gMixcbiAgICAgICAgICAgICAgICBsZWZ0OiBvZmZzZXQubGVmdCxcbiAgICAgICAgICAgICAgICB3aWR0aDogd2lkdGgsXG4gICAgICAgICAgICAgICAgaGVpZ2h0OiAnJ1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy5kcm9wYWJsZS5kYXRhKFwicG9zaXRpb25cIiwgXCJ0b3BcIik7XG4gICAgICAgICAgICBtdy5kcm9wYWJsZS5hZGRDbGFzcyhcIm13X2Ryb3BhYmxlX2Fycl91cFwiKTtcbiAgICAgICAgfSBlbHNlIGlmIChwb3MgPT09ICdib3R0b20nKSB7XG5cbiAgICAgICAgICAgIG13LmRyb3BhYmxlLmNzcyh7XG4gICAgICAgICAgICAgICAgdG9wOiBvZmZzZXQudG9wICsgaGVpZ2h0ICsgMixcbiAgICAgICAgICAgICAgICBsZWZ0OiBvZmZzZXQubGVmdCxcbiAgICAgICAgICAgICAgICB3aWR0aDogd2lkdGgsXG4gICAgICAgICAgICAgICAgaGVpZ2h0OiAnJ1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy5kcm9wYWJsZS5kYXRhKFwicG9zaXRpb25cIiwgXCJib3R0b21cIik7XG4gICAgICAgICAgICBtdy5kcm9wYWJsZS5yZW1vdmVDbGFzcyhcIm13X2Ryb3BhYmxlX2Fycl91cFwiKTtcbiAgICAgICAgICAgIG13LmRyb3BhYmxlLnJlbW92ZUNsYXNzKFwibXdfZHJvcGFibGVfYXJyX3JpZ3RcIik7XG4gICAgICAgIH0gZWxzZSBpZiAocG9zID09PSAnbGVmdCcpIHtcbiAgICAgICAgICAgIG13LmRyb3BhYmxlLmRhdGEoXCJwb3NpdGlvblwiLCAnbGVmdCcpO1xuICAgICAgICAgICAgbXcuZHJvcGFibGUuY3NzKHtcbiAgICAgICAgICAgICAgICB0b3A6IG9mZnNldC50b3AsXG4gICAgICAgICAgICAgICAgaGVpZ2h0OiBoZWlnaHQsXG4gICAgICAgICAgICAgICAgd2lkdGg6ICcnLFxuICAgICAgICAgICAgICAgIGxlZnQ6IG9mZnNldC5sZWZ0XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSBlbHNlIGlmIChwb3MgPT09ICdyaWdodCcpIHtcbiAgICAgICAgICAgIG13LmRyb3BhYmxlLmRhdGEoXCJwb3NpdGlvblwiLCAncmlnaHQnKTtcbiAgICAgICAgICAgIG13LmRyb3BhYmxlLmFkZENsYXNzKFwibXdfZHJvcGFibGVfYXJyX3JpZ3RcIik7XG4gICAgICAgICAgICBtdy5kcm9wYWJsZS5jc3Moe1xuICAgICAgICAgICAgICAgIHRvcDogb2Zmc2V0LnRvcCxcbiAgICAgICAgICAgICAgICBsZWZ0OiBvZmZzZXQubGVmdCArIHdpZHRoLFxuICAgICAgICAgICAgICAgIGhlaWdodDogaGVpZ2h0LFxuICAgICAgICAgICAgICAgIHdpZHRoOiAnJ1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG5cbiBtdy50cmlnZ2VyTGl2ZUVkaXRIYW5kbGVycyA9IHtcbiAgICBjYWNoZUVuYWJsZWQ6IGZhbHNlLFxuICAgICByZXNlU2V0Q2FjaGU6IGZ1bmN0aW9uKGtleSkge1xuICAgICAgICB0aGlzW2tleV0gPSB7fTtcbiAgICAgfSxcbiAgICBzaG91bGRUcmlnZ2VyOmZ1bmN0aW9uKGtleSwgbm9kZSkge1xuICAgICAgICBpZighdGhpcy5jYWNoZUVuYWJsZWQpIHJldHVybiB0cnVlO1xuICAgICAgICB2YXIgY291bnRNYXggPSAzO1xuICAgICAgICBpZighdGhpc1trZXldIHx8IHRoaXNba2V5XS5ub2RlICE9PSBub2RlKSB7XG4gICAgICAgICAgICB0aGlzW2tleV0gPSB7XG4gICAgICAgICAgICAgICAgbm9kZTpub2RlLFxuICAgICAgICAgICAgICAgIGNvdW50OjBcbiAgICAgICAgICAgIH07XG4gICAgICAgIH1cbiAgICAgICAgaWYodGhpc1trZXldLmNvdW50IDwgY291bnRNYXgpIHtcbiAgICAgICAgICAgIHRoaXNba2V5XS5jb3VudCsrO1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgX21vZHVsZVJlZ2lzdGVyOiBudWxsLFxuICAgIG1vZHVsZTogZnVuY3Rpb24oZXYpe1xuICAgICAgICB0YXJnZXRGcm9tID0gZXYgPyBldi50YXJnZXQgOiAgbXcubW1fdGFyZ2V0O1xuICAgICAgICB2YXIgbW9kdWxlID0gbXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQodGFyZ2V0RnJvbSwgJy5tb2R1bGU6bm90KC5uby1zZXR0aW5ncyknKTtcbiAgICAgICAgLy92YXIgbW9kdWxlID0gbXcudG9vbHMubGFzdE1hdGNoZXNPbk5vZGVPclBhcmVudCh0YXJnZXRGcm9tLCAnLm1vZHVsZTpub3QoLm5vLXNldHRpbmdzKScpO1xuICAgICAgICB2YXIgdHJpZ2dlclRhcmdldCA9ICBtb2R1bGUuX19kaXNhYmxlTW9kdWxlVHJpZ2dlciB8fCBtb2R1bGU7XG4gICAgICAgIGlmKG1vZHVsZSl7XG4gICAgICAgICAgICAvL2lmKHRoaXMuc2hvdWxkVHJpZ2dlcignX21vZHVsZVJlZ2lzdGVyJywgdHJpZ2dlclRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwibW9kdWxlT3ZlclwiLCBbdHJpZ2dlclRhcmdldCwgZXZdKTtcbiAgICAgICAgICAgIC8vfVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgaWYgKFxuICAgICAgICAgICAgICAgIG13Lm1tX3RhcmdldC5pZCAhPT0gJ213LWhhbmRsZS1pdGVtLW1vZHVsZSdcbiAgICAgICAgICAgICAgICAmJiBtdy5tbV90YXJnZXQuaWQgIT09ICdtdy1oYW5kbGUtaXRlbS1tb2R1bGUtYWN0aXZlJ1xuICAgICAgICAgICAgICAgICYmICFtdy50b29scy5oYXNQYXJlbnRXaXRoSWQobXcubW1fdGFyZ2V0LCAnbXctaGFuZGxlLWl0ZW0tbW9kdWxlJylcbiAgICAgICAgICAgICAgICAmJiAhbXcudG9vbHMuaGFzUGFyZW50V2l0aElkKG13Lm1tX3RhcmdldCwgJ213LWhhbmRsZS1pdGVtLW1vZHVsZS1hY3RpdmUnKVxuICAgICAgICAgICAgICAgICYmICFtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChtdy5tbV90YXJnZXQsIFsnbXdJbmFjY2Vzc2libGVNb2R1bGVzTWVudSddKSkge1xuICAgICAgICAgICAgICAgIC8qaWYodGhpcy5fbW9kdWxlUmVnaXN0ZXIgIT09IG51bGwpIHsqL1xuICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiTW9kdWxlTGVhdmVcIiwgbXcubW1fdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICAgICAgLyp0aGlzLl9tb2R1bGVSZWdpc3RlciA9IG51bGw7XG4gICAgICAgICAgICAgICAgfSovXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGNsb25lYWJsZTogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgY2xvbmVhYmxlID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKG13Lm1tX3RhcmdldCwgWydjbG9uZWFibGUnLCAnbXctY2xvbmVhYmxlLWNvbnRyb2wnXSk7XG5cbiAgICAgICAgaWYoISFjbG9uZWFibGUpe1xuICAgICAgICAgICAgaWYobXcudG9vbHMuaGFzQ2xhc3MoY2xvbmVhYmxlLCAnbXctY2xvbmVhYmxlLWNvbnRyb2wnKSl7XG4gICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkNsb25lYWJsZU92ZXJcIiwgW213LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5fX3RhcmdldCwgdHJ1ZV0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZihtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGNsb25lYWJsZSwgJ213LWNsb25lYWJsZS1jb250cm9sJykpe1xuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJDbG9uZWFibGVPdmVyXCIsIFttdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wuX190YXJnZXQsIHRydWVdKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkNsb25lYWJsZU92ZXJcIiwgW2Nsb25lYWJsZSwgZmFsc2VdKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9XG4gICAgICAgIGVsc2V7XG4gICAgICAgICAgICBpZihtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wgJiYgbXcubW1fdGFyZ2V0ICE9PSBtdy5kcmFnLl9vbkNsb25lYWJsZUNvbnRyb2wpe1xuICAgICAgICAgICAgICAgIG13LmRyYWcuX29uQ2xvbmVhYmxlQ29udHJvbC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICBfZWxlbWVudFJlZ2lzdGVyOm51bGwsXG4gICAgZWxlbWVudDogZnVuY3Rpb24oZXYpIHtcbiAgICAgICAgdmFyIGVsZW1lbnQgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyhtdy5tbV90YXJnZXQsICdlbGVtZW50Jyk7XG4gICAgICAgIGlmKGVsZW1lbnQgJiYgdGhpcy5fZWxlbWVudFJlZ2lzdGVyICE9PSBlbGVtZW50KSB7XG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50UmVnaXN0ZXIgPSBlbGVtZW50O1xuICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNDbGFzcyhlbGVtZW50LCAnbW9kdWxlJylcbiAgICAgICAgICAgICAgICAmJiAobXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChlbGVtZW50LCBbJ2VkaXQnLCAnbW9kdWxlJ10pXG4gICAgICAgICAgICAgICAgICAgICYmIG13LnRvb2xzLnBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3RPck5vbmUoZWxlbWVudCwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKSkpIHtcblxuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoXCJFbGVtZW50T3ZlclwiLCBbZWxlbWVudCwgZXZdKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgLyppZih0aGlzLl9lbGVtZW50UmVnaXN0ZXIgIT09IG51bGwpKi97XG4gICAgICAgICAgICAgICAgLy9pZiAoIW13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aElkKG13Lm1tX3RhcmdldCwgJ213X2hhbmRsZV9lbGVtZW50JykpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudFJlZ2lzdGVyID0gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgLy9tdy50cmlnZ2VyKFwiRWxlbWVudExlYXZlXCIsIGVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIC8vfVxuICAgICAgICAgICAgfVxuICAgICAgICB9IGVsc2UgaWYoIWVsZW1lbnQgJiYgIW13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aElkKG13Lm1tX3RhcmdldCwgJ213LWhhbmRsZS1pdGVtLWVsZW1lbnQnKSkge1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudFJlZ2lzdGVyID0gbnVsbDtcbiAgICAgICAgICAgIG13LnRyaWdnZXIoXCJFbGVtZW50TGVhdmVcIik7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG13Lm1tX3RhcmdldCA9PT0gbXcuaW1hZ2VfcmVzaXplciAmJiB0aGlzLl9lbGVtZW50UmVnaXN0ZXIgIT09IG13LmltYWdlLmN1cnJlbnRSZXNpemluZ1swXSkge1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudFJlZ2lzdGVyID0gbXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdO1xuICAgICAgICAgICAgbXcudHJpZ2dlcihcIkVsZW1lbnRPdmVyXCIsIG13LmltYWdlLmN1cnJlbnRSZXNpemluZ1swXSk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIF9sYXlvdXRSZWdpc3RlcjpudWxsLFxuICAgIGxheW91dDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgdmFyIHRhcmdldExheW91dCA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKG13Lm1tX3RhcmdldCwgJ213LWxheW91dC1yb290Jyk7XG4gICAgICAgICBpZiAodGFyZ2V0TGF5b3V0ICYmIHRoaXMuX2xheW91dFJlZ2lzdGVyICE9PSB0YXJnZXRMYXlvdXQpIHtcbiAgICAgICAgICAgICB0aGlzLl9sYXlvdXRSZWdpc3RlciA9IHRhcmdldExheW91dDtcbiAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiTGF5b3V0T3ZlclwiLCB0YXJnZXRMYXlvdXQpO1xuICAgICAgICAgfVxuICAgIH0sXG4gICAgIF9yb3dSZWdpc3RlcjpudWxsLFxuICAgIHJvdzogZnVuY3Rpb24gKCkge1xuICAgICAgICAgdmFyIHJvdyA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKG13Lm1tX3RhcmdldCwgJ213LXJvdycpO1xuXG4gICAgICAgICBpZiAocm93ICYmIHRoaXMuX3Jvd1JlZ2lzdGVyICE9PSByb3cpIHtcbiAgICAgICAgICAgICB0aGlzLl9yb3dSZWdpc3RlciA9IHJvdztcbiAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiUm93T3ZlclwiLCByb3cpO1xuICAgICAgICAgfSBlbHNlIGlmICh0aGlzLl9yb3dSZWdpc3RlciAhPT0gbnVsbCkge1xuICAgICAgICAgICAgIHRoaXMuX3Jvd1JlZ2lzdGVyID0gbnVsbDtcbiAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiUm93TGVhdmVcIiwgbXcubW1fdGFyZ2V0KTtcbiAgICAgICAgIH1cbiAgICB9LFxuICAgICBjb2w6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICghbXcuZHJhZy5jb2x1bW5zLnJlc2l6aW5nKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvbHVtbiA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKG13Lm1tX3RhcmdldCwgJ213LWNvbCcpO1xuICAgICAgICAgICAgICAgIGlmIChjb2x1bW4pIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcihcIkNvbHVtbk92ZXJcIiwgY29sdW1uKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKFwiQ29sdW1uT3V0XCIsIG13Lm1tX3RhcmdldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICB9XG4gfTtcbiBtdy5saXZlRWRpdEhhbmRsZXJzID0gZnVuY3Rpb24oZXZlbnQpe1xuICAgIGlmICggLyptdy5lbW91c2UueCAlIDIgPT09IDAgJiYgKi8gbXcuZHJhZy5jb2x1bW5zLnJlc2l6aW5nID09PSBmYWxzZSApIHtcbiAgICAgICAgbXcudHJpZ2dlckxpdmVFZGl0SGFuZGxlcnMuY2xvbmVhYmxlKGV2ZW50KTtcbiAgICAgICAgbXcudHJpZ2dlckxpdmVFZGl0SGFuZGxlcnMubGF5b3V0KGV2ZW50KTtcbiAgICAgICAgbXcudHJpZ2dlckxpdmVFZGl0SGFuZGxlcnMuZWxlbWVudChldmVudCk7XG4gICAgICAgIG13LnRyaWdnZXJMaXZlRWRpdEhhbmRsZXJzLm1vZHVsZShldmVudCk7XG4gICAgICAgIGlmIChtdy5kcmFnLmNvbHVtbnMucmVzaXppbmcgPT09IGZhbHNlICYmIG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MobXcubW1fdGFyZ2V0LCAnZWRpdCcpICYmICghbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhtdy5tbV90YXJnZXQsICdtb2R1bGUnKSB8fFxuICAgICAgICAgICAgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhtdy5tbV90YXJnZXQsICdhbGxvdy1kcm9wJykpKSB7XG4gICAgICAgICAgICBtdy50cmlnZ2VyTGl2ZUVkaXRIYW5kbGVycy5yb3coKTtcbiAgICAgICAgICAgIG13LnRyaWdnZXJMaXZlRWRpdEhhbmRsZXJzLmNvbCgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgbXcuaW1hZ2UuX2RyYWdUeHQoZXZlbnQpO1xuXG4gICAgdmFyIGJnLCBiZ1RhcmdldCwgYmdDYW5DaGFuZ2U7XG4gICAgaWYoZXZlbnQudGFyZ2V0KXtcbiAgICAgIGJnID0gZXZlbnQudGFyZ2V0LnN0eWxlICYmIGV2ZW50LnRhcmdldC5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgJiYgIW13LnRvb2xzLmhhc0NsYXNzKGV2ZW50LnRhcmdldCwgJ2VkaXQnKTtcbiAgICAgIGJnVGFyZ2V0ID0gZXZlbnQudGFyZ2V0O1xuICAgICAgaWYoIWJnKXtcbiAgICAgICAgICB2YXIgX2MgPSAwLCBiZ3AgPSBldmVudC50YXJnZXQ7XG4gICAgICAgICAgd2hpbGUgKCFiZyB8fCBiZ3AgPT09IG13ZC5ib2R5KXtcbiAgICAgICAgICAgICAgYmdwID0gYmdwLnBhcmVudE5vZGU7XG4gICAgICAgICAgICAgIGlmKCFiZ3ApIHtcbiAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIF9jKys7XG4gICAgICAgICAgICAgIGJnID0gYmdwLnN0eWxlICYmIGJncC5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgJiYgIW13LnRvb2xzLmhhc0NsYXNzKGJncCwgJ2VkaXQnKTtcbiAgICAgICAgICAgICAgYmdUYXJnZXQgPSBiZ3A7XG4gICAgICAgICAgfVxuICAgICAgfVxuICAgIH1cblxuICAgIGlmKGJnKXtcbiAgICAgICAgYmdDYW5DaGFuZ2UgPSBtdy5kcmFnLmNvbHVtbnMucmVzaXppbmcgPT09IGZhbHNlXG4gICAgICAgICYmIChtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KGJnVGFyZ2V0LCBbJ2VkaXQnLCdtb2R1bGUnXSkgfHwgbXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnZWxlbWVudCcpKTtcbiAgICB9XG5cbiAgICBpZiAoIW13LmltYWdlLmlzUmVzaXppbmcgJiYgbXcuaW1hZ2VfcmVzaXplcikge1xuXG4gICAgICAgIGlmIChldmVudC50YXJnZXQubm9kZU5hbWUgPT09ICdJTUcnICYmIChtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KGV2ZW50LnRhcmdldCwgWydlZGl0JywnbW9kdWxlJ10pKSAmJiBtdy5kcmFnLmNvbHVtbnMucmVzaXppbmcgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9zaG93KCk7XG4gICAgICAgICAgICBtdy5pbWFnZVJlc2l6ZS5yZXNpemVyU2V0KGV2ZW50LnRhcmdldCwgZmFsc2UpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKGJnICYmIGJnQ2FuQ2hhbmdlKSB7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9zaG93KCk7XG4gICAgICAgICAgICBtdy5pbWFnZVJlc2l6ZS5yZXNpemVyU2V0KGJnVGFyZ2V0LCBmYWxzZSk7XG4gICAgICAgIH1cblxuICAgICAgICBlbHNlIGlmKG13LnRvb2xzLmhhc0NsYXNzKG13Lm1tX3RhcmdldCwgJ213LWltYWdlLWhvbGRlci1jb250ZW50Jyl8fG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MobXcubW1fdGFyZ2V0LCAnbXctaW1hZ2UtaG9sZGVyLWNvbnRlbnQnKSl7XG4gICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9zaG93KCk7XG4gICAgICAgICAgICBtdy5pbWFnZVJlc2l6ZS5yZXNpemVyU2V0KG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKG13Lm1tX3RhcmdldCwgJ213LWltYWdlLWhvbGRlcicpLnF1ZXJ5U2VsZWN0b3IoJ2ltZycpLCBmYWxzZSk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBpZiAoIWV2ZW50LnRhcmdldC5td0ltYWdlUmVzaXplckNvbXBvbmVudCkge1xuICAgICAgICAgICAgICAgIGlmKG13LmltYWdlX3Jlc2l6ZXIpe1xuICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZV9yZXNpemVyLl9oaWRlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufTtcblxuXG5tdy5saXZlTm9kZVNldHRpbmdzID0ge1xuICAgIF93b3JraW5nOiBmYWxzZSxcbiAgICBzZXQ6IGZ1bmN0aW9uICh0eXBlLCBlbCkge1xuICAgICAgICBpZiAodGhpcy5fd29ya2luZykgcmV0dXJuO1xuICAgICAgICB0aGlzLl93b3JraW5nID0gdHJ1ZTtcbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBzY29wZS5fd29ya2luZyA9IGZhbHNlO1xuICAgICAgICB9LCA3OCk7XG5cbiAgICAgICAgaWYodGhpc1t0eXBlXSl7XG4gICAgICAgICAgICBtdy5zaWRlYmFyU2V0dGluZ3NUYWJzLnNldCgyKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzW3R5cGVdKGVsKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgZWxlbWVudDogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIGlmICghdGhpcy5fX2lzX3NpZGViYXJfb3BlbmVkKCkpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgfSxcbiAgICBub25lOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgaWYgKCF0aGlzLl9faXNfc2lkZWJhcl9vcGVuZWQoKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICB9LFxuICAgIG1vZHVsZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIG13LmxpdmVfZWRpdC5zaG93U2V0dGluZ3ModW5kZWZpbmVkLCB7bW9kZTpcInNpZGViYXJcIiwgbGl2ZWVkaXQ6dHJ1ZX0pXG4gICAgfSxcbiAgICBpbWFnZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIGlmICghdGhpcy5fX2lzX3NpZGViYXJfb3BlbmVkKCkpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIG13LiQoXCIjbXctbGl2ZS1lZGl0LXNpZGViYXItaW1hZ2UtZnJhbWVcIilcbiAgICAgICAgICAgIC5jb250ZW50cygpXG4gICAgICAgICAgICAuZmluZChcIiNtd2ltYWdlY3VycmVudFwiKVxuICAgICAgICAgICAgLmF0dHIoXCJzcmNcIiwgZWwuc3JjKVxuXG4gICAgfSxcbiAgICBpbml0SW1hZ2U6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHVybCA9IG13LmV4dGVybmFsX3Rvb2woJ2ltYWdlZWRpdG9yJyk7XG4gICAgICAgIG13LiQoXCIjanMtbGl2ZS1lZGl0LWltYWdlLXNldHRpbmdzLWhvbGRlclwiKS5hcHBlbmQoJzxpZnJhbWUgc3JjPVwiJyArIHVybCArICdcIiBmcmFtZWJvcmRlcj1cIjBcIiBpZD1cIm13LWxpdmUtZWRpdC1zaWRlYmFyLWltYWdlLWZyYW1lXCI+PC9pZnJhbWU+Jyk7XG4gICAgfSxcbiAgICBpY29uOiBmdW5jdGlvbiAoKSB7XG5cbiAgICB9LFxuICAgIF9faXNfc2lkZWJhcl9vcGVuZWQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKG13LmxpdmVFZGl0U2V0dGluZ3MgICYmICBtdy5saXZlRWRpdFNldHRpbmdzLmFjdGl2ZSkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuICAgIG13Lm9uKCdsaXZlRWRpdFNldHRpbmdzUmVhZHknLCBmdW5jdGlvbigpe1xuICAgICAgICBtdy5saXZlTm9kZVNldHRpbmdzLmluaXRJbWFnZSgpO1xuICAgIH0pO1xufSk7XG4iLCJtdy5saXZlRWRpdFdpZGdldHMgPSB7XHJcbiAgICBfY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uIDogbnVsbCxcclxuICAgIGNzc0VkaXRvckluU2lkZWJhckFjY29yZGlvbiA6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBpZighdGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uKXtcclxuICAgICAgICAgICAgdGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ2lmcmFtZScpIDtcclxuICAgICAgICAgICAgdGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uLmlkID0gJ213LWNzcy1lZGl0b3Itc2lkZWJhci1pZnJhbWUnIDtcclxuICAgICAgICAgICAgdGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uLnNyYyA9IG13LmV4dGVybmFsX3Rvb2woJ3J0ZV9jc3NfZWRpdG9yJyk7XHJcbiAgICAgICAgICAgIHRoaXMuX2Nzc0VkaXRvckluU2lkZWJhckFjY29yZGlvbi5zdHlsZS5vcGFjaXR5ID0gMDtcclxuICAgICAgICAgICAgdGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uLnNjcm9sbGluZyA9ICdubyc7XHJcbiAgICAgICAgICAgIHRoaXMuX2Nzc0VkaXRvckluU2lkZWJhckFjY29yZGlvbi5mcmFtZUJvcmRlciA9IDA7XHJcbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtd2QucXVlcnlTZWxlY3RvcignI213LWNzcy1lZGl0b3Itc2lkZWJhci1pZnJhbWUtaG9sZGVyJyk7XHJcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZCh0aGlzLl9jc3NFZGl0b3JJblNpZGViYXJBY2NvcmRpb24pO1xyXG4gICAgICAgICAgICBtdy50b29scy5sb2FkaW5nKGhvbGRlciwgOTApO1xyXG4gICAgICAgICAgICBtdy50b29scy5pZnJhbWVBdXRvSGVpZ2h0KHRoaXMuX2Nzc0VkaXRvckluU2lkZWJhckFjY29yZGlvbik7XHJcbiAgICAgICAgICAgIHRoaXMuX2Nzc0VkaXRvckluU2lkZWJhckFjY29yZGlvbi5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICB0aGlzLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5zdHlsZS5wYWRkaW5nID0gMDtcclxuICAgICAgICAgICAgICAgIHRoaXMuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5LnN0eWxlLmJhY2tncm91bmRDb2xvciA9ICd0cmFuc3BhcmVudCc7XHJcbiAgICAgICAgICAgICAgICBtdy50b29scy5sb2FkaW5nKGhvbGRlciwgZmFsc2UpO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5zdHlsZS5vcGFjaXR5ID0gMTtcclxuICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgbXcuJCh0aGlzLl9jc3NFZGl0b3JJblNpZGViYXJBY2NvcmRpb24pXHJcbiAgICAgICAgICAgICAgICAuaGVpZ2h0KCQodGhpcy5fY3NzRWRpdG9ySW5TaWRlYmFyQWNjb3JkaW9uKVxyXG4gICAgICAgICAgICAgICAgICAgIC5wYXJlbnRzKCcubXctdWktYm94Jykub3V0ZXJIZWlnaHQoKSAtXHJcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzLl9jc3NFZGl0b3JJblNpZGViYXJBY2NvcmRpb24pLnBhcmVudHMoJy50YWJpdGVtJykub3V0ZXJIZWlnaHQoKSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLl9jc3NFZGl0b3JJblNpZGViYXJBY2NvcmRpb247XHJcbiAgICB9LFxyXG4gICAgX3RwbFNldHRpbmdzIDogbnVsbCxcclxuICAgIGxvYWRUZW1wbGF0ZVNldHRpbmdzOiBmdW5jdGlvbiAodXJsKSB7XHJcbiAgICAgICAgaWYgKCF0aGlzLl90cGxTZXR0aW5ncykge1xyXG4gICAgICAgICAgICB0aGlzLl90cGxTZXR0aW5ncyA9IG13ZC5jcmVhdGVFbGVtZW50KCdpZnJhbWUnKSA7XHJcbiAgICAgICAgICAgIHRoaXMuX3RwbFNldHRpbmdzLmlkID0gJ213LWxpdmUtZWRpdC1zaWRlYmFyLXNldHRpbmdzLWlmcmFtZS1ob2xkZXItdGVtcGxhdGUtc2V0dGluZ3MtZnJhbWUnIDtcclxuICAgICAgICAgICAgdGhpcy5fdHBsU2V0dGluZ3MuY2xhc3NOYW1lID0gJ213LWxpdmUtZWRpdC1zaWRlYmFyLXNldHRpbmdzLWlmcmFtZScgO1xyXG4gICAgICAgICAgICB0aGlzLl90cGxTZXR0aW5ncy5zcmMgPSB1cmw7XHJcbiAgICAgICAgICAgIHRoaXMuX3RwbFNldHRpbmdzLnNjcm9sbGluZyA9ICdubyc7XHJcbiAgICAgICAgICAgIHRoaXMuX3RwbFNldHRpbmdzLmZyYW1lQm9yZGVyID0gMDtcclxuICAgICAgICAgICAgbXcuJCgnI213LWxpdmUtZWRpdC1zaWRlYmFyLXNldHRpbmdzLWlmcmFtZS1ob2xkZXItdGVtcGxhdGUtc2V0dGluZ3MnKS5odG1sKHRoaXMuX3RwbFNldHRpbmdzKTtcclxuICAgICAgICAgICAgbXcudG9vbHMuaWZyYW1lQXV0b0hlaWdodCh0aGlzLl90cGxTZXR0aW5ncyk7XHJcbiAgICAgICAgICAgIHRoaXMuX3RwbFNldHRpbmdzLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIHRoaXMuY29udGVudFdpbmRvdy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubXctbW9kdWxlLWxpdmUtZWRpdC1zZXR0aW5ncycpLnN0eWxlLnBhZGRpbmcgPSAwO1xyXG4gICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICBtdy4kKHRoaXMuX3RwbFNldHRpbmdzKVxyXG4gICAgICAgICAgICAgICAgLmhlaWdodCgkKHRoaXMuX3RwbFNldHRpbmdzKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAucGFyZW50cygnLm13LXVpLWJveCcpLm91dGVySGVpZ2h0KCkgLVxyXG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcy5fdHBsU2V0dGluZ3MpLnBhcmVudHMoJy50YWJpdGVtJykub3V0ZXJIZWlnaHQoKSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLl90cGxTZXR0aW5ncztcclxuICAgIH1cclxufTtcclxuXHJcbm13LmxpdmVFZGl0VG9vbHMgPSB7XHJcbiAgICBpc0xheW91dDogZnVuY3Rpb24gKG5vZGUpIHtcclxuICAgICAgICByZXR1cm4gKCEhbm9kZSAmJiAhIW5vZGUuZ2V0QXR0cmlidXRlICYmIChub2RlLmdldEF0dHJpYnV0ZSgnZGF0YS1tb2R1bGUtbmFtZScpID09PSAnbGF5b3V0cycgfHwgbm9kZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtdHlwZScpID09PSAnbGF5b3V0cycpKTtcclxuICAgIH1cclxufTtcclxuIiwiXG5tdy5saXZlZWRpdCA9IHt9O1xuXG5tdy5pc0RyYWcgPSBmYWxzZTtcbm13LnJlc2l6YWJsZV9yb3dfd2lkdGggPSBmYWxzZTtcbm13Lm1vdXNlX292ZXJfaGFuZGxlID0gZmFsc2U7XG5tdy5leHRlcm5hbF9jb250ZW50X2RyYWdnZWQgPSBmYWxzZTtcblxubXcuaGF2ZV9uZXdfaXRlbXMgPSBmYWxzZTtcblxubXcuZHJhZ0N1cnJlbnQgPSBudWxsO1xubXcuY3VycmVudERyYWdNb3VzZU92ZXIgPSBudWxsO1xubXcubGl2ZUVkaXRTZWxlY3RNb2RlID0gJ2VsZW1lbnQnO1xuXG5tdy5tb2R1bGVzQ2xpY2tJbnNlcnQgPSB0cnVlO1xuXG5tdy5tb3VzZURvd25PbkVkaXRvciA9IGZhbHNlO1xubXcubW91c2VEb3duU3RhcnRlZCA9IGZhbHNlO1xubXcuU21hbGxFZGl0b3JJc0RyYWdnaW5nID0gZmFsc2U7XG5cbm13LnN0YXRlcyA9IHt9O1xubXcubGl2ZV9lZGl0X21vZHVsZV9zZXR0aW5nc19hcnJheSA9IFtdO1xuXG5tdy5ub0VkaXRNb2R1bGVzID0gW1xuICAgICdbdHlwZT1cInRlbXBsYXRlX3NldHRpbmdzXCJdJ1xuXTtcblxubXcuaXNEcmFnSXRlbSA9IG13LmlzQmxvY2tMZXZlbCA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICByZXR1cm4gbXcuZWEuaGVscGVycy5pc0Jsb2NrTGV2ZWwob2JqKTtcbn07XG5cbiFmdW5jdGlvbihhKXtmdW5jdGlvbiBmKGEsYil7aWYoIShhLm9yaWdpbmFsRXZlbnQudG91Y2hlcy5sZW5ndGg+MSkpe2EucHJldmVudERlZmF1bHQoKTt2YXIgYz1hLm9yaWdpbmFsRXZlbnQuY2hhbmdlZFRvdWNoZXNbMF0sZD1kb2N1bWVudC5jcmVhdGVFdmVudChcIk1vdXNlRXZlbnRzXCIpO2QuaW5pdE1vdXNlRXZlbnQoYiwhMCwhMCx3aW5kb3csMSxjLnNjcmVlblgsYy5zY3JlZW5ZLGMuY2xpZW50WCxjLmNsaWVudFksITEsITEsITEsITEsMCxudWxsKSxhLnRhcmdldC5kaXNwYXRjaEV2ZW50KGQpfX1pZihhLnN1cHBvcnQudG91Y2g9XCJvbnRvdWNoZW5kXCJpbiBkb2N1bWVudCxhLnN1cHBvcnQudG91Y2gpe3ZhciBlLGI9YS51aS5tb3VzZS5wcm90b3R5cGUsYz1iLl9tb3VzZUluaXQsZD1iLl9tb3VzZURlc3Ryb3k7Yi5fdG91Y2hTdGFydD1mdW5jdGlvbihhKXt2YXIgYj10aGlzOyFlJiZiLl9tb3VzZUNhcHR1cmUoYS5vcmlnaW5hbEV2ZW50LmNoYW5nZWRUb3VjaGVzWzBdKSYmKGU9ITAsYi5fdG91Y2hNb3ZlZD0hMSxmKGEsXCJtb3VzZW92ZXJcIiksZihhLFwibW91c2Vtb3ZlXCIpLGYoYSxcIm1vdXNlZG93blwiKSl9LGIuX3RvdWNoTW92ZT1mdW5jdGlvbihhKXtlJiYodGhpcy5fdG91Y2hNb3ZlZD0hMCxmKGEsXCJtb3VzZW1vdmVcIikpfSxiLl90b3VjaEVuZD1mdW5jdGlvbihhKXtlJiYoZihhLFwibW91c2V1cFwiKSxmKGEsXCJtb3VzZW91dFwiKSx0aGlzLl90b3VjaE1vdmVkfHxmKGEsXCJjbGlja1wiKSxlPSExKX0sYi5fbW91c2VJbml0PWZ1bmN0aW9uKCl7dmFyIGI9dGhpcztiLmVsZW1lbnQuYmluZCh7dG91Y2hzdGFydDphLnByb3h5KGIsXCJfdG91Y2hTdGFydFwiKSx0b3VjaG1vdmU6YS5wcm94eShiLFwiX3RvdWNoTW92ZVwiKSx0b3VjaGVuZDphLnByb3h5KGIsXCJfdG91Y2hFbmRcIil9KSxjLmNhbGwoYil9LGIuX21vdXNlRGVzdHJveT1mdW5jdGlvbigpe3ZhciBiPXRoaXM7Yi5lbGVtZW50LnVuYmluZCh7dG91Y2hzdGFydDphLnByb3h5KGIsXCJfdG91Y2hTdGFydFwiKSx0b3VjaG1vdmU6YS5wcm94eShiLFwiX3RvdWNoTW92ZVwiKSx0b3VjaGVuZDphLnByb3h5KGIsXCJfdG91Y2hFbmRcIil9KSxkLmNhbGwoYil9fX0oalF1ZXJ5KTtcblxubXcudG9vbHMuYWRkQ2xhc3MobXdkLmJvZHksICdtdy1saXZlLWVkaXQnKTtcblxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG5cbiAgICBpZiAoKFwib250b3VjaHN0YXJ0XCIgaW4gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50KSkge1xuICAgICAgICBtdy4kKCdib2R5JykuYWRkQ2xhc3MoJ3RvdWNoc2NyZWVuLWRldmljZScpO1xuICAgIH1cblxuICAgIG13LmxpdmVlZGl0LmluaXRSZWFkeSgpO1xuICAgIG13LmxpdmVlZGl0LmhhbmRsZUV2ZW50cygpO1xuICAgIG13LmxpdmVlZGl0LmhhbmRsZUN1c3RvbUV2ZW50cygpO1xuXG4gICAgbXcubGl2ZWVkaXQuY3NzRWRpdG9yID0gbmV3IG13LmxpdmVlZGl0Q1NTRWRpdG9yKCk7XG5cbn0pO1xuXG5tdy5yZXF1aXJlKCdzdHlsZXNoZWV0LmVkaXRvci5qcycpO1xuJCh3aW5kb3cpLm9uKFwibG9hZFwiLCBmdW5jdGlvbigpIHtcbiAgICBtdy5saXZlZWRpdC5pbml0TG9hZCgpO1xufSk7XG5cbiQod2luZG93KS5vbigncmVzaXplJywgZnVuY3Rpb24oKSB7XG4gICAgbXcubGl2ZWVkaXQudG9vbGJhci5zZXRFZGl0b3IoKTtcbn0pO1xuXG5cblxuXG4iLCJtdy5saXZlZWRpdC5tYW5hZ2VDb250ZW50ID0ge1xyXG4gICAgdzogJzEyMjBweCcsXHJcbiAgICBwYWdlOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nSWZyYW1lKHtcclxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJtb2R1bGUvP3R5cGU9Y29udGVudC9lZGl0X3BhZ2UmbGl2ZV9lZGl0PXRydWUmcXVpY2tfZWRpdD1mYWxzZSZpZD1tdy1xdWljay1wYWdlJnJlY29tbWVuZGVkX3BhcmVudD1cIiArIG13LnNldHRpbmdzLnBhZ2VfaWQsXHJcbiAgICAgICAgICAgIHdpZHRoOiB0aGlzLncsXHJcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxyXG4gICAgICAgICAgICBhdXRvSGVpZ2h0OiB0cnVlLFxyXG4gICAgICAgICAgICBuYW1lOiAncXVpY2tfcGFnZScsXHJcbiAgICAgICAgICAgIG92ZXJsYXk6IHRydWUsXHJcbiAgICAgICAgICAgIHRpdGxlOiAnTmV3IFBhZ2UnLFxyXG4gICAgICAgICAgICBzY3JvbGxNb2RlOiAnaW5zaWRlJ1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIG13LiQobW9kYWwubWFpbikuYWRkQ2xhc3MoJ213LWFkZC1jb250ZW50LW1vZGFsJyk7XHJcbiAgICB9LFxyXG4gICAgY2F0ZWdvcnk6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB2YXIgbW9kYWwgPSBtdy5kaWFsb2dJZnJhbWUoe1xyXG4gICAgICAgICAgICB1cmw6IG13LnNldHRpbmdzLmFwaV91cmwgKyBcIm1vZHVsZS8/dHlwZT1jYXRlZ29yaWVzL2VkaXRfY2F0ZWdvcnkmbGl2ZV9lZGl0PXRydWUmcXVpY2tfZWRpdD1mYWxzZSZpZD1tdy1xdWljay1jYXRlZ29yeSZyZWNvbW1lbmRlZF9wYXJlbnQ9XCIgKyBtdy5zZXR0aW5ncy5wYWdlX2lkLFxyXG4gICAgICAgICAgICB3aWR0aDogJzYwMHB4JyxcclxuLy8gICAgICAgICAgICB3aWR0aDogdGhpcy53LFxyXG4gICAgICAgICAgICBoZWlnaHQ6ICdhdXRvJyxcclxuICAgICAgICAgICAgYXV0b0hlaWdodDogdHJ1ZSxcclxuICAgICAgICAgICAgbmFtZTogJ3F1aWNrX3BhZ2UnLFxyXG4gICAgICAgICAgICBvdmVybGF5OiB0cnVlLFxyXG4gICAgICAgICAgICB0aXRsZTogJ05ldyBDYXRlZ29yeScsXHJcbiAgICAgICAgICAgIHNjcm9sbE1vZGU6ICdpbnNpZGUnXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbXcuJChtb2RhbC5tYWluKS5hZGRDbGFzcygnbXctYWRkLWNvbnRlbnQtbW9kYWwnKTtcclxuICAgIH0sXHJcbiAgICBlZGl0OiBmdW5jdGlvbiAoaWQsIGNvbnRlbnRfdHlwZSwgc3VidHlwZSwgcGFyZW50LCBjYXRlZ29yeSkge1xyXG4gICAgICAgIHZhciBzdHIgPSBcIlwiO1xyXG5cclxuICAgICAgICBpZiAocGFyZW50KSB7XHJcbiAgICAgICAgICAgIHN0ciA9IFwiJnJlY29tbWVuZGVkX3BhcmVudD1cIiArIHBhcmVudDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChjb250ZW50X3R5cGUpIHtcclxuICAgICAgICAgICAgc3RyID0gc3RyICsgJyZjb250ZW50X3R5cGU9JyArIGNvbnRlbnRfdHlwZTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChjYXRlZ29yeSkge1xyXG4gICAgICAgICAgICBzdHIgPSBzdHIgKyAnJmNhdGVnb3J5PScgKyBjYXRlZ29yeTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChzdWJ0eXBlKSB7XHJcbiAgICAgICAgICAgIHN0ciA9IHN0ciArICcmc3VidHlwZT0nICsgc3VidHlwZTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHZhciBhY3Rpb25UeXBlID0gJyc7XHJcblxyXG4gICAgICAgIGlmIChpZCA9PT0gMCkge1xyXG4gICAgICAgICAgICBhY3Rpb25UeXBlID0gJ0FkZCc7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgYWN0aW9uVHlwZSA9ICdFZGl0JztcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHZhciBhY3Rpb25PZiA9ICdDb250ZW50JztcclxuICAgICAgICBpZiAoY29udGVudF90eXBlID09PSAncG9zdCcpIHtcclxuICAgICAgICAgICAgYWN0aW9uT2YgPSAnUG9zdCdcclxuICAgICAgICB9IGVsc2UgaWYgKGNvbnRlbnRfdHlwZSA9PT0gJ3BhZ2UnKSB7XHJcbiAgICAgICAgICAgIGFjdGlvbk9mID0gJ1BhZ2UnXHJcbiAgICAgICAgfSBlbHNlIGlmIChjb250ZW50X3R5cGUgPT09ICdwcm9kdWN0Jykge1xyXG4gICAgICAgICAgICBhY3Rpb25PZiA9ICdQcm9kdWN0J1xyXG4gICAgICAgIH0gZWxzZSBpZiAoY29udGVudF90eXBlID09PSAnY2F0ZWdvcnknKSB7XHJcbiAgICAgICAgICAgIGFjdGlvbk9mID0gJ0NhdGVnb3J5J1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nSWZyYW1lKHtcclxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJtb2R1bGUvP3R5cGU9Y29udGVudC9lZGl0JmxpdmVfZWRpdD10cnVlJnF1aWNrX2VkaXQ9ZmFsc2UmaXMtY3VycmVudD10cnVlJmlkPW13LXF1aWNrLXBhZ2UmY29udGVudC1pZD1cIiArIGlkICsgc3RyLFxyXG4gICAgICAgICAgICB3aWR0aDogJzgwMHB4JyxcclxuLy8gICAgICAgICAgICB3aWR0aDogdGhpcy53LFxyXG4gICAgICAgICAgICBoZWlnaHQ6ICdhdXRvJyxcclxuICAgICAgICAgICAgYXV0b0hlaWdodDogdHJ1ZSxcclxuICAgICAgICAgICAgbmFtZTogJ3F1aWNrX3BhZ2UnLFxyXG4gICAgICAgICAgICBpZDogJ3F1aWNrX3BhZ2UnLFxyXG4gICAgICAgICAgICBvdmVybGF5OiB0cnVlLFxyXG4gICAgICAgICAgICB0aXRsZTogYWN0aW9uVHlwZSArICcgJyArIGFjdGlvbk9mLFxyXG4gICAgICAgICAgICBzY3JvbGxNb2RlOiAnaW5zaWRlJ1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIG13LiQobW9kYWwubWFpbikuYWRkQ2xhc3MoJ213LWFkZC1jb250ZW50LW1vZGFsJyk7XHJcbiAgICB9LFxyXG4gICAgcGFnZV8yOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nSWZyYW1lKHtcclxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJtb2R1bGUvP3R5cGU9Y29udGVudC9xdWlja19hZGQmbGl2ZV9lZGl0PXRydWUmaWQ9bXctbmV3LWNvbnRlbnQtYWRkLWlmYW1lXCIsXHJcbiAgICAgICAgICAgIHdpZHRoOiB0aGlzLncsXHJcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxyXG4gICAgICAgICAgICBuYW1lOiAncXVpY2tfcGFnZScsXHJcbiAgICAgICAgICAgIG92ZXJsYXk6IHRydWUsXHJcbiAgICAgICAgICAgIHRpdGxlOiAnTmV3IFBhZ2UnLFxyXG4gICAgICAgICAgICBzY3JvbGxNb2RlOiAnaW5zaWRlJ1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIG13LiQobW9kYWwubWFpbikuYWRkQ2xhc3MoJ213LWFkZC1jb250ZW50LW1vZGFsJyk7XHJcbiAgICB9LFxyXG4gICAgcG9zdDogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHZhciBtb2RhbCA9IG13LmRpYWxvZ0lmcmFtZSh7XHJcbiAgICAgICAgICAgIHVybDogbXcuc2V0dGluZ3MuYXBpX3VybCArIFwibW9kdWxlLz90eXBlPWNvbnRlbnQvZWRpdF9wYWdlJmxpdmVfZWRpdD10cnVlJnF1aWNrX2VkaXQ9ZmFsc2UmaWQ9bXctcXVpY2stcG9zdCZzdWJ0eXBlPXBvc3QmcGFyZW50LXBhZ2UtaWQ9XCIgKyBtdy5zZXR0aW5ncy5wYWdlX2lkICsgXCImcGFyZW50LWNhdGVnb3J5LWlkPVwiICsgbXcuc2V0dGluZ3MuY2F0ZWdvcnlfaWQsXHJcbiAgICAgICAgICAgIHdpZHRoOiB0aGlzLncsXHJcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxyXG4gICAgICAgICAgICBhdXRvSGVpZ2h0OiB0cnVlLFxyXG4gICAgICAgICAgICBuYW1lOiAncXVpY2tfcG9zdCcsXHJcbiAgICAgICAgICAgIG92ZXJsYXk6IHRydWUsXHJcbiAgICAgICAgICAgIHRpdGxlOiAnTmV3IFBvc3QnXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbXcuJChtb2RhbC5tYWluKS5hZGRDbGFzcygnbXctYWRkLWNvbnRlbnQtbW9kYWwnKTtcclxuICAgIH0sXHJcbiAgICBwcm9kdWN0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nSWZyYW1lKHtcclxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJtb2R1bGUvP3R5cGU9Y29udGVudC9lZGl0X3BhZ2UmbGl2ZV9lZGl0PXRydWUmcXVpY2tfZWRpdD1mYWxzZSZpZD1tdy1xdWljay1wcm9kdWN0JnN1YnR5cGU9cHJvZHVjdCZwYXJlbnQtcGFnZS1pZD1cIiArIG13LnNldHRpbmdzLnBhZ2VfaWQgKyBcIiZwYXJlbnQtY2F0ZWdvcnktaWQ9XCIgKyBtdy5zZXR0aW5ncy5jYXRlZ29yeV9pZCxcclxuICAgICAgICAgICAgd2lkdGg6IHRoaXMudyxcclxuICAgICAgICAgICAgaGVpZ2h0OiAnYXV0bycsXHJcbiAgICAgICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXHJcbiAgICAgICAgICAgIG5hbWU6ICdxdWlja19wcm9kdWN0JyxcclxuICAgICAgICAgICAgb3ZlcmxheTogdHJ1ZSxcclxuICAgICAgICAgICAgdGl0bGU6ICdOZXcgUHJvZHVjdCdcclxuICAgICAgICB9KTtcclxuICAgICAgICBtdy4kKG1vZGFsLm1haW4pLmFkZENsYXNzKCdtdy1hZGQtY29udGVudC1tb2RhbCcpO1xyXG4gICAgfVxyXG59XHJcbiIsIm13LmxpdmVlZGl0Lm1vZHVsZXNUb29sYmFyID0ge1xyXG4gICAgaW5pdDogZnVuY3Rpb24gKHNlbGVjdG9yKSB7XHJcbiAgICAgICAgdmFyIGl0ZW1zID0gc2VsZWN0b3IgfHwgXCIubW9kdWxlcy1saXN0IGxpW2RhdGEtbW9kdWxlLW5hbWVdXCI7XHJcbiAgICAgICAgdmFyICRpdGVtcyA9IG13LiQoaXRlbXMpO1xyXG4gICAgICAgICRpdGVtcy5kcmFnZ2FibGUoe1xyXG4gICAgICAgICAgICByZXZlcnQ6IHRydWUsXHJcbiAgICAgICAgICAgIHJldmVydER1cmF0aW9uOiAwLFxyXG4gICAgICAgICAgICBzdGFydDogZnVuY3Rpb24oYSwgYikge1xyXG4gICAgICAgICAgICAgICAgbXcuaXNEcmFnID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIG13LmRyYWdDdXJyZW50ID0gbXcuZWEuZGF0YS5jdXJyZW50R3JhYmJlZCA9IG13Lkdsb2JhbE1vZHVsZUxpc3RIZWxwZXI7XHJcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5hZGRDbGFzcyhcImRyYWdTdGFydFwiKTtcclxuICAgICAgICAgICAgICAgIG13LmltYWdlX3Jlc2l6ZXIuX2hpZGUoKTtcclxuXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHN0b3A6IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICAgICAgbXcuaXNEcmFnID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICBtdy5wYXVzZVNhdmUgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgdmFyIGVsID0gdGhpcztcclxuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwiZHJhZ1N0YXJ0XCIpO1xyXG4gICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgICAgICAgICBtdy5kcmFnLmxvYWRfbmV3X21vZHVsZXMoKTtcclxuICAgICAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC5yZWNvbW1lbmQuaW5jcmVhc2UoJChtdy5kcmFnQ3VycmVudCkuYXR0cihcImRhdGEtbW9kdWxlLW5hbWVcIikpO1xyXG4gICAgICAgICAgICAgICAgICAgIG13LmRyYWcudG9vbGJhcl9tb2R1bGVzKGVsKTtcclxuICAgICAgICAgICAgICAgIH0sIDIwMCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgICAgICAkaXRlbXMub24oJ21vdXNlZW50ZXIgdG91Y2hzdGFydCcsIGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICBtdy4kKHRoaXMpLmRyYWdnYWJsZShcIm9wdGlvblwiLCBcImhlbHBlclwiLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgICAgIHZhciBlbCA9ICQodGhpcyk7XHJcbiAgICAgICAgICAgICAgICB2YXIgY2xvbmUgPSBlbC5jbG9uZSh0cnVlKTtcclxuICAgICAgICAgICAgICAgIGNsb25lLmFwcGVuZFRvKG13ZC5ib2R5KTtcclxuICAgICAgICAgICAgICAgIGNsb25lLmFkZENsYXNzKCdtdy1tb2R1bGUtZHJhZy1jbG9uZScpO1xyXG4gICAgICAgICAgICAgICAgbXcuR2xvYmFsTW9kdWxlTGlzdEhlbHBlciA9IGNsb25lWzBdO1xyXG4gICAgICAgICAgICAgICAgY2xvbmUuY3NzKHtcclxuICAgICAgICAgICAgICAgICAgICB3aWR0aDogZWwud2lkdGgoKSxcclxuICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IGVsLmhlaWdodCgpXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIHJldHVybiBjbG9uZVswXTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAvKiAkaXRlbXMub24oXCJjbGljayBtb3VzZWRvd24gbW91c2V1cFwiLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgaWYgKGUudHlwZSA9PT0gJ2NsaWNrJykge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmIChlLnR5cGUgPT09ICdtb3VzZWRvd24nKSB7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm1vdXNlZG93biA9IHRydWU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKGUudHlwZSA9PT0gJ21vdXNldXAnICYmIGUud2hpY2ggPT09IDEgJiYgISF0aGlzLm1vdXNlZG93bikge1xyXG4gICAgICAgICAgICAgICAgJGl0ZW1zLmVhY2goZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5tb3VzZWRvd24gPSBmYWxzZTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgaWYgKCFtdy5pc0RyYWcgJiYgbXd3LmdldFNlbGVjdGlvbigpLnJhbmdlQ291bnQgPiAwICYmIG13ZC5xdWVyeVNlbGVjdG9yKCcubXdfbW9kYWwnKSA9PT0gbnVsbCAmJiBtdy5tb2R1bGVzQ2xpY2tJbnNlcnQpIHtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgaHRtbCA9IHRoaXMub3V0ZXJIVE1MO1xyXG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwoaHRtbCk7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5sb2FkX25ld19tb2R1bGVzKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTsqL1xyXG4gICAgfVxyXG59O1xyXG4iLCJtdy5yZXF1aXJlKCd0ZW1wY3NzLmpzJyk7XG5cbihmdW5jdGlvbihtdyl7XG5cbiAgICBtdy5wYWRkaW5nRWRpdG9yID0gZnVuY3Rpb24oIG9wdGlvbnMgKSB7XG5cbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cbiAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgaGVpZ2h0OiAxMFxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuXG4gICAgICAgIHRoaXMuX3BhZ2VZID0gLTE7XG4gICAgICAgIHRoaXMuX2FjdGl2ZSA9IG51bGw7XG4gICAgICAgIHRoaXMuX3BhZGRpbmdUb3BEb3duID0gZmFsc2U7XG4gICAgICAgIHRoaXMuX3BhZGRpbmdCb3R0b21Eb3duID0gZmFsc2U7XG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG5cbiAgICAgICAgdGhpcy5jcmVhdGUgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHRoaXMucGFkZGluZ1RvcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgdGhpcy5wYWRkaW5nVG9wLmNsYXNzTmFtZSA9ICdtdy1wYWRkaW5nLWN0cmwgbXctcGFkZGluZy1jdHJsLXRvcCc7XG5cbiAgICAgICAgICAgIHRoaXMucGFkZGluZ0JvdHRvbSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgdGhpcy5wYWRkaW5nQm90dG9tLmNsYXNzTmFtZSA9ICdtdy1wYWRkaW5nLWN0cmwgbXctcGFkZGluZy1jdHJsLWJvdHRvbSc7XG5cbiAgICAgICAgICAgIHRoaXMucGFkZGluZ1RvcC5zdHlsZS5oZWlnaHQgPSB0aGlzLnNldHRpbmdzLmhlaWdodCArICdweCc7XG4gICAgICAgICAgICB0aGlzLnBhZGRpbmdCb3R0b20uc3R5bGUuaGVpZ2h0ID0gdGhpcy5zZXR0aW5ncy5oZWlnaHQgKyAncHgnO1xuXG5cbiAgICAgICAgICAgIC8vIHRoaXMucGFkZGluZ0JvdHRvbS5zdHlsZS52aXNpYmlsaXR5ID0gJ2hpZGRlbic7XG4gICAgICAgICAgICAvLyB0aGlzLnBhZGRpbmdUb3Auc3R5bGUudmlzaWJpbGl0eSA9ICdoaWRkZW4nO1xuXG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRoaXMucGFkZGluZ1RvcCk7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRoaXMucGFkZGluZ0JvdHRvbSk7XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLnJlY29yZCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgaWYoIXNjb3BlLl9hY3RpdmUpe1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIHJlY19zY29wZSA9IHNjb3BlLl9hY3RpdmU7XG4gICAgICAgICAgICBpZihyZWNfc2NvcGUucGFyZW50Tm9kZSl7XG4gICAgICAgICAgICAgICAgcmVjX3Njb3BlID0gcmVjX3Njb3BlLnBhcmVudE5vZGU7XG4gICAgICAgICAgICB9XG4gICAgICAgIC8vICAgIHZhciByb290ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKHNjb3BlLl9hY3RpdmUucGFyZW50Tm9kZSwgWydlZGl0JywgJ2VsZW1lbnQnLCAnbW9kdWxlJ10pO1xuICAgICAgICAgICAgdmFyIHJvb3QgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXMocmVjX3Njb3BlLCBbJ2VkaXQnLCAnZWxlbWVudCcsICdtb2R1bGUnXSk7XG4gICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgdGFyZ2V0OnJvb3QsXG4gICAgICAgICAgICAgICAgdmFsdWU6IHJvb3QuaW5uZXJIVE1MXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuXG5cbiAgICAgICAgaWYoc2NvcGUgJiYgc2NvcGUuX2FjdGl2ZSl7XG5cbiAgICAgICAgfVxuICAgICAgICB0aGlzLmhhbmRsZU1vdXNlTW92ZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgbXcuJCh0aGlzLnBhZGRpbmdUb3ApLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdUb3BEb3duID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdE1vZGUgPSAnbm9uZSc7XG4gICAgICAgICAgICAgICAgbXcuJCgnaHRtbCcpLmFkZENsYXNzKCdwYWRkaW5nLWNvbnRyb2wtc3RhcnQnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuJCh0aGlzLnBhZGRpbmdCb3R0b20pLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdCb3R0b21Eb3duID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdE1vZGUgPSAnbm9uZSc7XG4gICAgICAgICAgICAgICAgbXcuJCgnaHRtbCcpLmFkZENsYXNzKCdwYWRkaW5nLWNvbnRyb2wtc3RhcnQnKTtcbiAgICAgICAgICAgICAgICBzY29wZS5yZWNvcmQoKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuJChkb2N1bWVudC5ib2R5KS5vbignbW91c2V1cCB0b3VjaGVuZCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYoc2NvcGUuX3BhZGRpbmdUb3BEb3duIHx8IHNjb3BlLl9wYWRkaW5nQm90dG9tRG93bikge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5yZWNvcmQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3RNb2RlID0gJ2VsZW1lbnQnO1xuXG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdUb3BEb3duID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdCb3R0b21Eb3duID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3dvcmtpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBtdy4kKHNjb3BlLl9pbmZvKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgc2NvcGUuYWN0aXZlTWFyayhmYWxzZSk7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3Rvci5hY3RpdmUodHJ1ZSk7XG4gICAgICAgICAgICAgICAgbXcuJCgnaHRtbCcpLnJlbW92ZUNsYXNzKCdwYWRkaW5nLWNvbnRyb2wtc3RhcnQnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuZXZlbnQud2luZG93TGVhdmUoZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBpZihzY29wZS5fcGFkZGluZ1RvcERvd24gfHwgc2NvcGUuX3BhZGRpbmdCb3R0b21Eb3duKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnJlY29yZCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFNlbGVjdE1vZGUgPSAnZWxlbWVudCc7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdUb3BEb3duID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3BhZGRpbmdCb3R0b21Eb3duID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3dvcmtpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBtdy4kKHNjb3BlLl9pbmZvKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3Rvci5hY3RpdmUodHJ1ZSk7XG4gICAgICAgICAgICAgICAgbXcuJCgnaHRtbCcpLnJlbW92ZUNsYXNzKCdwYWRkaW5nLWNvbnRyb2wtc3RhcnQnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuJChkb2N1bWVudCkub24oJ21vdXNlbW92ZSB0b3VjaG1vdmUnLCBmdW5jdGlvbihlKXtcbiAgICAgICAgICAgICAgICBpZihzY29wZS5fYWN0aXZlKXtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGlzRG93biA9IGUucGFnZVkgPCBzY29wZS5fcGFnZVk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpbmMgPSBpc0Rvd24gPyBzY29wZS5fcGFnZVkgLSBlLnBhZ2VZIDogZS5wYWdlWSAtIHNjb3BlLl9wYWdlWTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGN1cnI7XG4gICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlICYmIHNjb3BlLl9hY3RpdmUgJiYgc2NvcGUuX3BhZGRpbmdUb3BEb3duKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl93b3JraW5nID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGN1cnIgPSBzY29wZS5fYWN0aXZlLl9jdXJyUGFkZGluZ1RvcCB8fCAocGFyc2VGbG9hdCgkKHNjb3BlLl9hY3RpdmUpLmNzcygncGFkZGluZ1RvcCcpKSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKGlzRG93bil7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuX2FjdGl2ZS5zdHlsZS5wYWRkaW5nVG9wID0gKGN1cnIgPD0gMCA/IDAgOiBjdXJyLWluYykgKyAncHgnO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fYWN0aXZlLnN0eWxlLnBhZGRpbmdUb3AgPSAoY3VyciArIGluYykgKyAncHgnO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuX2FjdGl2ZS5fY3VyclBhZGRpbmdUb3AgPSBwYXJzZUZsb2F0KHNjb3BlLl9hY3RpdmUuc3R5bGUucGFkZGluZ1RvcCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5wb3NpdGlvbihzY29wZS5fYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmluZm8oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl9hY3RpdmUuc2V0QXR0cmlidXRlKCdzdGF0aWNkZXNpZ24nLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLmFjdGl2ZU1hcmsodHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShzY29wZS5fYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3IucGF1c2UoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ1BhZGRpbmdDb250cm9sJywgc2NvcGUuX2FjdGl2ZSk7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZihzY29wZSAmJiBzY29wZS5fYWN0aXZlICYmIHNjb3BlLl9wYWRkaW5nQm90dG9tRG93bil7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fd29ya2luZyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICBjdXJyID0gc2NvcGUuX2FjdGl2ZS5fY3VyclBhZGRpbmdCb3R0b20gfHwgKHBhcnNlRmxvYXQoJChzY29wZS5fYWN0aXZlKS5jc3MoJ3BhZGRpbmdCb3R0b20nKSkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoaXNEb3duKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fYWN0aXZlLnN0eWxlLnBhZGRpbmdCb3R0b20gPSAoY3VyciA8PSAwID8gMCA6IGN1cnItaW5jKSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl9hY3RpdmUuc3R5bGUucGFkZGluZ0JvdHRvbSA9IChjdXJyICsgaW5jKSArICdweCc7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fYWN0aXZlLl9jdXJyUGFkZGluZ0JvdHRvbSA9IHBhcnNlRmxvYXQoc2NvcGUuX2FjdGl2ZS5zdHlsZS5wYWRkaW5nQm90dG9tKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnBvc2l0aW9uKHNjb3BlLl9hY3RpdmUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuaW5mbygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuX2FjdGl2ZS5zZXRBdHRyaWJ1dGUoJ3N0YXRpY2Rlc2lnbicsIHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuYWN0aXZlTWFyayh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKHNjb3BlLl9hY3RpdmUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTZWxlY3Rvci5wYXVzZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcignUGFkZGluZ0NvbnRyb2wnLCBzY29wZS5fYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBzY29wZS5fcGFnZVkgPSBlLnBhZ2VZO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5fYWN0aXZlUGFkZGluZyA9IGN1cnI7XG5cbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAoc2NvcGUuX2FjdGl2ZSAmJiBtdy5saXZlZWRpdC5kYXRhLmdldCgnbW92ZScsICdoYXNMYXlvdXQnKSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLnBvc2l0aW9uKCk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuaGlkZSgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLnNob3cgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgc2NvcGUucGFkZGluZ1RvcC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIHNjb3BlLnBhZGRpbmdCb3R0b20uc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5oaWRlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHNjb3BlLnBhZGRpbmdUb3Auc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIHNjb3BlLnBhZGRpbmdCb3R0b20uc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnBvc2l0aW9uID0gZnVuY3Rpb24odGFyZ2V0SXNMYXlvdXQpIHtcbiAgICAgICAgICAgIHZhciAkZWwgPSBtdy4kKHRhcmdldElzTGF5b3V0KTtcbiAgICAgICAgICAgIHZhciBvZmYgPSAkZWwub2Zmc2V0KCk7XG4gICAgICAgICAgICBzY29wZS5fYWN0aXZlID0gdGFyZ2V0SXNMYXlvdXQ7XG4gICAgICAgICAgICBzY29wZS5wYWRkaW5nVG9wLnN0eWxlLnRvcCA9IG9mZi50b3AgKyAncHgnO1xuICAgICAgICAgICAgc2NvcGUucGFkZGluZ0JvdHRvbS5zdHlsZS50b3AgPSAob2ZmLnRvcCArICRlbC5vdXRlckhlaWdodCgpIC0gdGhpcy5zZXR0aW5ncy5oZWlnaHQpICsgJ3B4JztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNlbGVjdG9ycyA9IFtcbiAgICAgICAgICAgICcubXctcGFkZGluZy1ndWktZWxlbWVudCcsXG4gICAgICAgICAgICAnLm13LXBhZGRpbmctY29udHJvbC1lbGVtZW50JyxcbiAgICAgICAgXTtcblxuICAgICAgICB0aGlzLnByZXBhcmVTZWxlY3RvcnMgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgLyogdmFyIGkgPSAwO1xuICAgICAgICAgICAgZm9yKCA7IGkgPCB0aGlzLnNlbGVjdG9ycy5sZW5ndGg7IGkrKyl7XG4gICAgICAgICAgICAgICAgaWYodGhpcy5zZWxlY3RvcnNbaV0uaW5kZXhPZignW2lkJykgPT09IC0xKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RvcnNbaV0gKz0gJ1tpZF0nO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gKi9cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmFkZFNlbGVjdG9yID0gZnVuY3Rpb24oc2VsZWN0b3Ipe1xuICAgICAgICAgICAgdGhpcy5zZWxlY3RvcnMucHVzaChzZWxlY3Rvcik7XG4gICAgICAgICAgICB0aGlzLnByZXBhcmVTZWxlY3RvcnMoKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmV2ZW50c0hhbmRsZXJzID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBtdy5vbignbW9kdWxlT3ZlciBNb2R1bGVDbGljaycsIGZ1bmN0aW9uKGUsIGVsKXtcbiAgICAgICAgICAgICAgICBpZighc2NvcGUuX3dvcmtpbmcpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgdGFyZ2V0SXNMYXlvdXQgPSBtdy50b29scy5maXJzdE1hdGNoZXNPbk5vZGVPclBhcmVudChlbCwgc2NvcGUuc2VsZWN0b3JzKTtcbiAgICAgICAgICAgICAgICAgICAgaWYodGFyZ2V0SXNMYXlvdXQpe1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYobXcudG9vbHMuaGFzQ2xhc3ModGFyZ2V0SXNMYXlvdXQsICdtb2R1bGUnKSl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGNoaWxkID0gbXcuJCh0YXJnZXRJc0xheW91dCkuY2hpbGRyZW4oc2NvcGUuc2VsZWN0b3JzLmpvaW4oJywnKSlbMF07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGFyZ2V0SXNMYXlvdXQgPSBjaGlsZCB8fCB0YXJnZXRJc0xheW91dDtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnBvc2l0aW9uKHRhcmdldElzTGF5b3V0KTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICB0aGlzLmNyZWF0ZSgpO1xuICAgICAgICAgICAgdGhpcy5ldmVudHNIYW5kbGVycygpO1xuICAgICAgICAgICAgdGhpcy5oYW5kbGVNb3VzZU1vdmUoKTtcbiAgICAgICAgICAgIHRoaXMucHJlcGFyZVNlbGVjdG9ycygpO1xuICAgICAgICAgICAgdGhpcy5oaWRlKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5hY3RpdmVNYXJrID0gZnVuY3Rpb24gKHN0YXRlKSB7XG4gICAgICAgICAgICBpZih0eXBlb2Ygc3RhdGUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgc3RhdGUgPSBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKCF0aGlzLl9hY3RpdmVNYXJrKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlTWFyayA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgICAgIHRoaXMuX2FjdGl2ZU1hcmsuY2xhc3NOYW1lID0gJ213LXBhZGRpbmctY29udHJvbC1hY3RpdmUtbWFyayc7XG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZCh0aGlzLl9hY3RpdmVNYXJrKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChzdGF0ZSkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMuX2FjdGl2ZU1hcmssICdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICB2YXIgYWN0aXZlID0gc2NvcGUuX3BhZGRpbmdUb3BEb3duID8gc2NvcGUucGFkZGluZ1RvcCA6IHNjb3BlLnBhZGRpbmdCb3R0b207XG4gICAgICAgICAgICAgICAgdmFyIG9mZiA9IHNjb3BlLl9hY3RpdmUuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlTWFyay5zdHlsZS5sZWZ0ID0gb2ZmLmxlZnQgKyAncHgnO1xuICAgICAgICAgICAgICAgIHRoaXMuX2FjdGl2ZU1hcmsuc3R5bGUud2lkdGggPSBvZmYud2lkdGggKyAncHgnO1xuICAgICAgICAgICAgICAgIHRoaXMuX2FjdGl2ZU1hcmsuc3R5bGUuaGVpZ2h0ID0gc2NvcGUuX2FjdGl2ZVBhZGRpbmcgKyAncHgnO1xuICAgICAgICAgICAgICAgIGlmIChzY29wZS5fcGFkZGluZ1RvcERvd24pIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlTWFyay5zdHlsZS50b3AgPSAob2ZmLnRvcCArIHNjcm9sbFkpICsgJ3B4JztcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmVNYXJrLnN0eWxlLnRvcCA9ICgob2ZmLnRvcCArIHNjcm9sbFkgKyBtdy4kKHNjb3BlLl9hY3RpdmUpLm91dGVySGVpZ2h0KCkpIC0gcGFyc2VGbG9hdChzY29wZS5fYWN0aXZlLnN0eWxlLnBhZGRpbmdCb3R0b20pKSArICdweCc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyh0aGlzLl9hY3RpdmVNYXJrLCAnYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5nZW5lcmF0ZUNTUyA9IGZ1bmN0aW9uKG9iaiwgbWVkaWEpe1xuICAgICAgICAgICAgaWYoIW9iaiB8fCAhc2NvcGUuX2FjdGl2ZSkgcmV0dXJuO1xuXG4gICAgICAgICAgICBtZWRpYSA9IChtZWRpYSB8fCAnYWxsJykudHJpbSgpO1xuICAgICAgICAgICAgdmFyIHNlbGVjdG9yID0gbXcudG9vbHMuZ2VuZXJhdGVTZWxlY3RvckZvck5vZGUoc2NvcGUuX2FjdGl2ZSk7XG4gICAgICAgICAgICB2YXIgb2JqQ3NzID0gJ3snO1xuICAgICAgICAgICAgZm9yICh2YXIgaSBpbiBvYmopIHtcbiAgICAgICAgICAgICAgICBvYmpDc3MgKz0gKGkgKyAnOicgKyBvYmpbaV0gKyAnOycpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgb2JqQ3NzICs9ICd9JztcbiAgICAgICAgICAgIHZhciBjc3MgPSAnQG1lZGlhICcgKyBtZWRpYSAgKyAnIHsnICsgc2VsZWN0b3IgKyBvYmpDc3MgKyAnfSc7XG4gICAgICAgICAgICByZXR1cm4gY3NzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaW5mbyA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgaWYoIXRoaXMuX2luZm8pe1xuICAgICAgICAgICAgICAgIHRoaXMuX2luZm8gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgICAgICB0aGlzLl9pbmZvLmNsYXNzTmFtZSA9ICdtdy1wYWRkaW5nLWNvbnRyb2wtaW5mbyc7XG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZCh0aGlzLl9pbmZvKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LiQodGhpcy5faW5mbykuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgdmFyIG9mZjtcbiAgICAgICAgICAgIGlmIChzY29wZS5fcGFkZGluZ1RvcERvd24pIHtcbiAgICAgICAgICAgICAgICBvZmYgPSBtdy4kKHNjb3BlLnBhZGRpbmdUb3ApLm9mZnNldCgpO1xuICAgICAgICAgICAgICAgIHRoaXMuX2luZm8uc3R5bGUudG9wID0gKG9mZi50b3AgKyBzY29wZS5zZXR0aW5ncy5oZWlnaHQpICsgJ3B4JztcbiAgICAgICAgICAgICAgICB0aGlzLl9pbmZvLmlubmVySFRNTCA9IHNjb3BlLl9hY3RpdmUuc3R5bGUucGFkZGluZ1RvcDtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoc2NvcGUuX3BhZGRpbmdCb3R0b21Eb3duKSB7XG4gICAgICAgICAgICAgICAgb2ZmID0gbXcuJChzY29wZS5wYWRkaW5nQm90dG9tKS5vZmZzZXQoKTtcbiAgICAgICAgICAgICAgICB0aGlzLl9pbmZvLnN0eWxlLnRvcCA9IChvZmYudG9wIC0gc2NvcGUuc2V0dGluZ3MuaGVpZ2h0IC0gMzApICsgJ3B4JztcbiAgICAgICAgICAgICAgICB0aGlzLl9pbmZvLmlubmVySFRNTCA9IHNjb3BlLl9hY3RpdmUuc3R5bGUucGFkZGluZ0JvdHRvbTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuX2luZm8uc3R5bGUubGVmdCA9IChvZmYubGVmdCArIChzY29wZS5fYWN0aXZlLmNsaWVudFdpZHRoLzIpKSArICdweCc7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuaW5pdCgpO1xuICAgIH07XG5cbn0pKHdpbmRvdy5tdyk7XG4iLCJtdy5kcmFnID0gbXcuZHJhZyB8fCB7fTtcbm13LmRyYWcucGx1cyA9IHtcbiAgICBsb2NrZWQ6IGZhbHNlLFxuICAgIGRpc2FibGVkOiBmYWxzZSxcbiAgIC8vIG1vdXNlX21vdmVkOiBmYWxzZSxcbiAgICBpbml0OiBmdW5jdGlvbiAoaG9sZGVyKSB7XG5cbiAgICAgICAgaWYodGhpcy5kaXNhYmxlZCkgcmV0dXJuO1xuXG4gICAgICAgIG13LmRyYWcucGx1c1RvcCA9IG13ZC5xdWVyeVNlbGVjdG9yKCcubXctcGx1cy10b3AnKTtcbiAgICAgICAgbXcuZHJhZy5wbHVzQm90dG9tID0gbXdkLnF1ZXJ5U2VsZWN0b3IoJy5tdy1wbHVzLWJvdHRvbScpO1xuXG4gICAgICAgIGlmKG13LmRyYWcucGx1c1RvcCkge1xuICAgICAgICAgICAgbXcuZHJhZy5wbHVzVG9wLnN0eWxlLnRvcCA9IC05OTk5ICsgJ3B4JztcbiAgICAgICAgfVxuICAgICAgICBpZihtdy5kcmFnLnBsdXNCb3R0b20pIHtcbiAgICAgICAgICAgIG13LmRyYWcucGx1c0JvdHRvbS5zdHlsZS50b3AgPSAtOTk5OSArICdweCc7XG4gICAgICAgIH1cbiAgICAgICAgbXcuJChob2xkZXIpLm9uKCdtb3VzZW1vdmUgdG91Y2htb3ZlIGNsaWNrJywgZnVuY3Rpb24gKGUpIHtcblxuXG4gICAgICAgICAgICBpZiAobXcuZHJhZy5wbHVzLmxvY2tlZCA9PT0gZmFsc2UgJiYgbXcuaXNEcmFnID09PSBmYWxzZSkge1xuICAgICAgICAgICAgICAgIGlmIChlLnBhZ2VZICUgMiA9PT0gMCAmJiBtdy50b29scy5pc0VkaXRhYmxlKGUpKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB3aGljaFBsdXM7XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIG5vZGUgPSBtdy5kcmFnLnBsdXMuc2VsZWN0Tm9kZShlLnRhcmdldCk7XG4gICAgICAgICAgICAgICAgICAgIGlmKG5vZGUgJiYgZS50eXBlID09PSAnbW91c2Vtb3ZlJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG9mZiA9ICQobm9kZSkub2Zmc2V0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB3aGljaFBsdXMgPSAoZS5wYWdlWSAtIG9mZi50b3ApID4gKChvZmYudG9wICsgbm9kZS5vZmZzZXRIZWlnaHQpIC0gZS5wYWdlWSkgPyAndG9wJyA6ICdib3R0b20nO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIG13LmRyYWcucGx1cy5zZXQobm9kZSwgd2hpY2hQbHVzKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoJ2VkaXRvcktleXVwJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgbXcuZHJhZy5wbHVzVG9wLnN0eWxlLnRvcCA9IC05OTk5ICsgJ3B4JztcbiAgICAgICAgICAgICAgICBtdy5kcmFnLnBsdXNCb3R0b20uc3R5bGUudG9wID0gLTk5OTkgKyAncHgnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChob2xkZXIpLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGlmIChtdy5kcmFnLnBsdXMubG9ja2VkID09PSBmYWxzZSAmJiAoZS50YXJnZXQgIT09IG13LmRyYWcucGx1c1RvcCAmJiBlLnRhcmdldCAhPT0gbXcuZHJhZy5wbHVzQm90dG9tKSApIHtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLnBsdXMuc2V0KHVuZGVmaW5lZCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBtdy5kcmFnLnBsdXMuYWN0aW9uKCk7XG4gICAgfSxcbiAgICBzZWxlY3ROb2RlOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG5cbiAgICAgICAgaWYoIXRhcmdldCB8fCBtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudCh0YXJnZXQsIFsnbm9wbHVzJywgJ25vZWRpdCcsICdub3BsdXMnXSkgfHwgbXcudG9vbHMuaGFzQ2xhc3ModGFyZ2V0LCAnZWRpdCcpKSB7XG4gICAgICAgICAgICBtdy5kcmFnLnBsdXNUb3Auc3R5bGUudG9wID0gLTk5OTkgKyAncHgnO1xuICAgICAgICAgICAgbXcuZHJhZy5wbHVzQm90dG9tLnN0eWxlLnRvcCA9IC05OTk5ICsgJ3B4JztcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICB2YXIgY29tcCA9IG13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KHRhcmdldCwgWycubW9kdWxlJywgJy5lbGVtZW50JywgJ3AnLCAnLm13LWVtcHR5J10pO1xuXG4gICAgICAgIGlmIChjb21wXG4gICAgICAgICAgICAvLyAmJiBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0T3JOb25lKHRhcmdldCwgWydyZWd1bGFyLW1vZGUnLCAnc2FmZS1tb2RlJ10pXG4gICAgICAgICAgICAmJiBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0T3JOb25lKHRhcmdldCwgWydhbGxvdy1kcm9wJywgJ25vZHJvcCddKSkgIHtcbiAgICAgICAgICAgIHJldHVybiBjb21wO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgbXcuZHJhZy5wbHVzVG9wLnN0eWxlLnRvcCA9IC05OTk5ICsgJ3B4JztcbiAgICAgICAgICAgIG13LmRyYWcucGx1c0JvdHRvbS5zdHlsZS50b3AgPSAtOTk5OSArICdweCc7XG4gICAgICAgICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBzZXQ6IGZ1bmN0aW9uIChub2RlLCB3aGljaFBsdXMpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2Ygbm9kZSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgJG5vZGUgPSBtdy4kKG5vZGUpXG4gICAgICAgICAgICB2YXIgb2ZmID0gJG5vZGUub2Zmc2V0KCksXG4gICAgICAgICAgICAgICAgdG9vbGJhciA9IG13ZC5xdWVyeVNlbGVjdG9yKCcjbGl2ZV9lZGl0X3Rvb2xiYXInKTtcbiAgICAgICAgICAgIHZhciBvbGVmdCA9IE1hdGgubWF4KDAsIG9mZi5sZWZ0IC0gMTApO1xuICAgICAgICAgICAgaWYodG9vbGJhciAmJiBvZmYudG9wIDwgdG9vbGJhci5vZmZzZXRIZWlnaHQpe1xuICAgICAgICAgICAgICBvZmYudG9wID0gdG9vbGJhci5vZmZzZXRIZWlnaHQgKyAxMDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LmRyYWcucGx1c1RvcC5zdHlsZS50b3AgPSBvZmYudG9wICsgJ3B4JztcbiAgICAgICAgICAgIG13LmRyYWcucGx1c1RvcC5zdHlsZS5sZWZ0ID0gb2xlZnQgKyAoJG5vZGUud2lkdGgoKS8yKSArICdweCc7XG4gICAgICAgICAgICAvLyBtdy5kcmFnLnBsdXNUb3Auc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICBtdy5kcmFnLnBsdXNUb3AuY3VycmVudE5vZGUgPSBub2RlO1xuICAgICAgICAgICAgbXcuZHJhZy5wbHVzQm90dG9tLnN0eWxlLnRvcCA9IChvZmYudG9wICsgbm9kZS5vZmZzZXRIZWlnaHQpICsgJ3B4JztcbiAgICAgICAgICAgIG13LmRyYWcucGx1c0JvdHRvbS5zdHlsZS5sZWZ0ID0gb2xlZnQgKyAoJG5vZGUud2lkdGgoKS8yKSArICdweCc7XG4gICAgICAgICAgICBpZih3aGljaFBsdXMpIHtcbiAgICAgICAgICAgICAgICBpZih3aGljaFBsdXMgPT09ICd0b3AnKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmRyYWcucGx1c1RvcC5zdHlsZS50b3AgPSAtOTk5OSArICdweCc7XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgbXcuZHJhZy5wbHVzQm90dG9tLnN0eWxlLnRvcCA9IC05OTk5ICsgJ3B4JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy5kcmFnLnBsdXNCb3R0b20uY3VycmVudE5vZGUgPSBub2RlO1xuICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoW213LmRyYWcucGx1c1RvcCwgbXcuZHJhZy5wbHVzQm90dG9tXSwgJ2FjdGl2ZScpO1xuXG4gICAgfSxcbiAgICB0aXBQb3NpdGlvbjogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgdmFyIG9mZiA9IG13LiQobm9kZSkub2Zmc2V0KCk7XG4gICAgICAgIGlmIChvZmYudG9wID4gMTMwKSB7XG4gICAgICAgICAgICBpZiAoKG9mZi50b3AgKyBub2RlLm9mZnNldEhlaWdodCkgPCAoJChtd2QuYm9keSkuaGVpZ2h0KCkgLSAxMzApKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuICdyaWdodC1jZW50ZXInO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuICdyaWdodC1ib3R0b20nO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgcmV0dXJuICdyaWdodC10b3AnO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBhY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHBscyA9IFttdy5kcmFnLnBsdXNUb3AsIG13LmRyYWcucGx1c0JvdHRvbV07XG4gICAgICAgIHZhciAkcGxzID0gbXcuJChwbHMpO1xuICAgICAgICAkcGxzLm9uKCdtb3VzZWVudGVyJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MoZG9jdW1lbnQuYm9keSwgJ2JvZHktbXctbW9kdWxlLXBsdXMtaG92ZXInKTtcbiAgICAgICAgICAgIG13LmxpdmVFZGl0U2VsZWN0b3Iuc2VsZWN0KG13LmRyYWcucGx1c1RvcC5jdXJyZW50Tm9kZSk7XG4gICAgICAgIH0pO1xuICAgICAgICAkcGxzLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoZG9jdW1lbnQuYm9keSwgJ2JvZHktbXctbW9kdWxlLXBsdXMtaG92ZXInKVxuICAgICAgICB9KTtcbiAgICAgICAgJHBscy5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgb3RoZXIgPSB0aGlzID09PSBtdy5kcmFnLnBsdXNUb3AgPyBtdy5kcmFnLnBsdXNCb3R0b20gOiBtdy5kcmFnLnBsdXNUb3A7XG4gICAgICAgICAgICBpZiAoIW13LnRvb2xzLmhhc0NsYXNzKHRoaXMsICdhY3RpdmUnKSkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMsICdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhvdGhlciwgJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgIG13LmRyYWcucGx1cy5sb2NrZWQgPSB0cnVlO1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy10b29sdGlwLWluc2VydC1tb2R1bGUnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBtdy5kcmFnLnBsdXNBY3RpdmUgPSB0aGlzID09PSBtdy5kcmFnLnBsdXNUb3AgPyAndG9wJyA6ICdib3R0b20nO1xuICAgICAgICAgICAgICAgIHZhciB0aXAgPSBuZXcgbXcudG9vbHRpcCh7XG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6IG13ZC5nZXRFbGVtZW50QnlJZCgncGx1cy1tb2R1bGVzLWxpc3QnKS5pbm5lckhUTUwsXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRoaXMsXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBtdy5kcmFnLnBsdXMudGlwUG9zaXRpb24odGhpcy5jdXJyZW50Tm9kZSksXG4gICAgICAgICAgICAgICAgICAgIHRlbXBsYXRlOiAnbXctdG9vbHRpcC1kZWZhdWx0IG13LXRvb2x0aXAtaW5zZXJ0LW1vZHVsZScsXG4gICAgICAgICAgICAgICAgICAgIGlkOiAnbXctcGx1cy10b29sdGlwLXNlbGVjdG9yJ1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgICQoJyNtdy1wbHVzLXRvb2x0aXAtc2VsZWN0b3InKS5hZGRDbGFzcygnYWN0aXZlJykuZmluZCgnLm13LXVpLXNlYXJjaGZpZWxkJykuZm9jdXMoKTtcbiAgICAgICAgICAgICAgICB9LCAxMClcbiAgICAgICAgICAgICAgICBtdy50YWJzKHtcbiAgICAgICAgICAgICAgICAgICAgbmF2OiB0aXAucXVlcnlTZWxlY3RvckFsbCgnLm13LXVpLWJ0bicpLFxuICAgICAgICAgICAgICAgICAgICB0YWJzOiB0aXAucXVlcnlTZWxlY3RvckFsbCgnLm1vZHVsZS1idWJibGUtdGFiJyksXG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICBtdy4kKCcubXctdWktc2VhcmNoZmllbGQnLCB0aXApLm9uKCdrZXl1cCBwYXN0ZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHJlc3VsdHNMZW5ndGggPSBtdy5kcmFnLnBsdXMuc2VhcmNoKHRoaXMudmFsdWUsIHRpcCk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChyZXN1bHRzTGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKCcubW9kdWxlLWJ1YmJsZS10YWItbm90LWZvdW5kLW1lc3NhZ2UnKS5odG1sKG13Lm1zZy5ub19yZXN1bHRzX2ZvciArICc6IDxlbT4nICsgdGhpcy52YWx1ZSArICc8L2VtPicpLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoXCIubW9kdWxlLWJ1YmJsZS10YWItbm90LWZvdW5kLW1lc3NhZ2VcIikuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKCcjcGx1cy1tb2R1bGVzLWxpc3QgbGknKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBuYW1lID0gbXcuJCh0aGlzKS5hdHRyKCdkYXRhLW1vZHVsZS1uYW1lJyk7XG4gICAgICAgICAgICBpZihuYW1lID09PSAnbGF5b3V0Jyl7XG4gICAgICAgICAgICAgICAgdmFyIHRlbXBsYXRlID0gbXcuJCh0aGlzKS5hdHRyKCd0ZW1wbGF0ZScpO1xuICAgICAgICAgICAgICAgIG13LiQodGhpcykuYXR0cignb25jbGljaycsICdJbnNlcnRNb2R1bGUoXCInICsgbmFtZSArICdcIiwge2NsYXNzOnRoaXMuY2xhc3NOYW1lLCB0ZW1wbGF0ZTpcIicrdGVtcGxhdGUrJ1wifSknKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hdHRyKCdvbmNsaWNrJywgJ0luc2VydE1vZHVsZShcIicgKyBuYW1lICsgJ1wiLCB7Y2xhc3M6dGhpcy5jbGFzc05hbWV9KScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIHNlYXJjaDogZnVuY3Rpb24gKHZhbCwgcm9vdCkge1xuICAgICAgICB2YXIgYWxsID0gcm9vdC5xdWVyeVNlbGVjdG9yQWxsKCcubW9kdWxlX25hbWUnKSxcbiAgICAgICAgICAgIGwgPSBhbGwubGVuZ3RoLFxuICAgICAgICAgICAgaSA9IDA7XG4gICAgICAgIHZhbCA9IHZhbC50b0xvd2VyQ2FzZSgpO1xuICAgICAgICB2YXIgZm91bmQgPSAwO1xuICAgICAgICB2YXIgaXNFbXB0eSA9IHZhbC5yZXBsYWNlKC9cXHMrL2csICcnKSA9PT0gJyc7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICB2YXIgdGV4dCA9IGFsbFtpXS5pbm5lckhUTUwudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIHZhciBsaSA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhhbGxbaV0sICdsaScpO1xuICAgICAgICAgICAgdmFyIGZpbHRlciA9IChsaS5kYXRhc2V0LmZpbHRlciB8fCAnJykudHJpbSgpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICBpZiAodGV4dC5jb250YWlucyh2YWwpIHx8IGlzRW1wdHkpIHtcbiAgICAgICAgICAgICAgICBsaS5zdHlsZS5kaXNwbGF5ID0gJyc7XG4gICAgICAgICAgICAgICAgaWYgKHRleHQuY29udGFpbnModmFsKSkgZm91bmQrKztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYoZmlsdGVyLmNvbnRhaW5zKHZhbCkpe1xuICAgICAgICAgICAgICAgIGxpLnN0eWxlLmRpc3BsYXkgPSAnJztcbiAgICAgICAgICAgICAgICBmb3VuZCsrO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgbGkuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gZm91bmQ7XG4gICAgfVxufTtcblxuSW5zZXJ0TW9kdWxlID0gZnVuY3Rpb24gKG1vZHVsZSwgY2xzKSB7XG4gICAgdmFyIGlkID0gJ213ZW1vZHVsZS0nICsgbXcucmFuZG9tKCksIGVsID0gJzxkaXYgaWQ9XCInICsgaWQgKyAnXCI+PC9kaXY+JywgYWN0aW9uO1xuICAgIGlmIChtdy5kcmFnLnBsdXNBY3RpdmUgPT09ICd0b3AnKSB7XG4gICAgICAgIGFjdGlvbiA9ICdiZWZvcmUnO1xuICAgICAgICBpZihtdy50b29scy5oYXNDbGFzcyhtdy5kcmFnLnBsdXNUb3AuY3VycmVudE5vZGUsICdhbGxvdy1kcm9wJykpIHtcbiAgICAgICAgICAgIGFjdGlvbiA9ICdwcmVwZW5kJztcbiAgICAgICAgfVxuICAgIH1cbiAgICBlbHNlIGlmIChtdy5kcmFnLnBsdXNBY3RpdmUgPT09ICdib3R0b20nKSB7XG4gICAgICAgIGFjdGlvbiA9ICdhZnRlcic7XG4gICAgICAgIGlmKG13LnRvb2xzLmhhc0NsYXNzKG13LmRyYWcucGx1c1RvcC5jdXJyZW50Tm9kZSwgJ2FsbG93LWRyb3AnKSkge1xuICAgICAgICAgICAgYWN0aW9uID0gJ2FwcGVuZCc7XG4gICAgICAgIH1cbiAgICB9XG4gICAgbXcuJChtdy5kcmFnLnBsdXNCb3R0b20uY3VycmVudE5vZGUpW2FjdGlvbl0oZWwpO1xuXG4gICAgIG13LmxvYWRfbW9kdWxlKG1vZHVsZSwgJyMnICsgaWQsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIG5vZGUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChpZCk7XG5cbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2Uobm9kZSk7XG5cbiAgICAgICAgbXcuZHJhZy5wbHVzLmxvY2tlZCA9IGZhbHNlO1xuICAgICAgICBtdy5kcmFnLmZpeGVzKCk7XG4gICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LmRyYWcuZml4X3BsYWNlaG9sZGVycygpO1xuICAgICAgICB9LCA0MCk7XG5cbiAgICAgICAgbXcuZHJvcGFibGUuaGlkZSgpO1xuICAgIH0sIGNscyk7XG4gICAgbXcuJCgnLm13LXRvb2x0aXAnKS5oaWRlKCk7XG59O1xuXG5cbiIsIm13LmxpdmVlZGl0LnJlY29tbWVuZCA9IHtcclxuICAgIGdldDogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHZhciBjb29raWUgPSBtdy5jb29raWUuZ2V0RW5jb2RlZChcInJlY29tbWVuZFwiKTtcclxuICAgICAgICBpZiAoIWNvb2tpZSkge1xyXG4gICAgICAgICAgICByZXR1cm4ge31cclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgdmFsID0gJC5wYXJzZUpTT04oY29va2llKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBjYXRjaCAoZSkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHJldHVybiB2YWw7XHJcbiAgICAgICAgfVxyXG4gICAgfSxcclxuICAgIGluY3JlYXNlOiBmdW5jdGlvbiAoaXRlbV9uYW1lKSB7XHJcbiAgICAgICAgdmFyIGpzb24gPSB0aGlzLmdldCgpIHx8IHt9O1xyXG4gICAgICAgIHZhciBjdXJyID0gcGFyc2VGbG9hdChqc29uW2l0ZW1fbmFtZV0pO1xyXG4gICAgICAgIGlmIChpc05hTihjdXJyKSkge1xyXG4gICAgICAgICAgICBqc29uW2l0ZW1fbmFtZV0gPSAxO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAganNvbltpdGVtX25hbWVdICs9IDE7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciB0b3N0cmluZyA9IEpTT04uc3RyaW5naWZ5KGpzb24pO1xyXG4gICAgICAgIG13LmNvb2tpZS5zZXRFbmNvZGVkKFwicmVjb21tZW5kXCIsIHRvc3RyaW5nLCBmYWxzZSwgXCIvXCIpO1xyXG4gICAgfSxcclxuICAgIG9yZGVyUmVjb21tZW5kT2JqZWN0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdmFyIG9iaiA9IHRoaXMuZ2V0KCk7XHJcbiAgICAgICAgaWYgKCFtdy50b29scy5pc0VtcHR5T2JqZWN0KG9iaikpIHtcclxuICAgICAgICAgICAgdmFyIGFyciA9IFtdO1xyXG4gICAgICAgICAgICBmb3IgKHZhciB4IGluIG9iaikge1xyXG4gICAgICAgICAgICAgICAgYXJyLnB1c2goeClcclxuICAgICAgICAgICAgICAgIGFyci5zb3J0KGZ1bmN0aW9uIChhLCBiKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGFbMV0gLSBiWzFdXHJcbiAgICAgICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHJldHVybiBhcnI7XHJcbiAgICAgICAgfVxyXG4gICAgfSxcclxuICAgIHNldDogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHZhciBhcnIgPSB0aGlzLm9yZGVyUmVjb21tZW5kT2JqZWN0KCksIGwgPSBhcnIubGVuZ3RoLCBpID0gMDtcclxuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgICAgICB2YXIgbSA9IG13LiQoJyN0YWJfbW9kdWxlcyAubW9kdWxlLWl0ZW1bZGF0YS1tb2R1bGUtbmFtZT1cIicgKyBhcnJbaV0gKyAnXCJdJylbMF07XHJcbiAgICAgICAgICAgIGlmIChtICE9PSBudWxsICYmIHR5cGVvZiBtICE9PSAndW5kZWZpbmVkJykge1xyXG4gICAgICAgICAgICAgICAgbXcuJCgnI3RhYl9tb2R1bGVzIHVsJykucHJlcGVuZChtKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufTtcclxuIiwibXcuY3NzID0gZnVuY3Rpb24oZWxlbWVudCwgY3NzKXtcbiAgICBmb3IodmFyIGkgaW4gY3NzKXtcbiAgICAgICAgZWxlbWVudC5zdHlsZVtpXSA9IHR5cGVvZiBjc3NbaV0gPT09ICdudW1iZXInID8gY3NzW2ldICsgJ3B4JyA6IGNzc1tpXTtcbiAgICB9XG59XG5tdy5TZWxlY3RvciA9IGZ1bmN0aW9uKG9wdGlvbnMpIHtcblxuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuXG4gICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICBhdXRvU2VsZWN0OiB0cnVlLFxuICAgICAgICBkb2N1bWVudDogZG9jdW1lbnQsXG4gICAgICAgIHRvZ2dsZVNlbGVjdDogZmFsc2UsIC8vIHNlY29uZCBjbGljayB1bnNlbGVjdHMgZWxlbWVudFxuICAgICAgICBzdHJpY3Q6IGZhbHNlIC8vIG9ubHkgbWF0Y2ggZWxlbWVudHMgdGhhdCBoYXZlIGlkXG4gICAgfTtcblxuICAgIHRoaXMub3B0aW9ucyA9ICQuZXh0ZW5kKHt9LCBkZWZhdWx0cywgb3B0aW9ucyk7XG4gICAgdGhpcy5kb2N1bWVudCA9IHRoaXMub3B0aW9ucy5kb2N1bWVudDtcblxuXG4gICAgdGhpcy5idWlsZFNlbGVjdG9yID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIHN0b3AgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgc3JpZ2h0ID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgdmFyIHNib3R0b20gPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgc2xlZnQgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuXG4gICAgICAgIHN0b3AuY2xhc3NOYW1lID0gJ213LXNlbGVjdG9yIG13LXNlbGVjdG9yLXRvcCc7XG4gICAgICAgIHNyaWdodC5jbGFzc05hbWUgPSAnbXctc2VsZWN0b3IgbXctc2VsZWN0b3ItcmlnaHQnO1xuICAgICAgICBzYm90dG9tLmNsYXNzTmFtZSA9ICdtdy1zZWxlY3RvciBtdy1zZWxlY3Rvci1ib3R0b20nO1xuICAgICAgICBzbGVmdC5jbGFzc05hbWUgPSAnbXctc2VsZWN0b3IgbXctc2VsZWN0b3ItbGVmdCc7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHN0b3ApO1xuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc3JpZ2h0KTtcbiAgICAgICAgdGhpcy5kb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHNib3R0b20pO1xuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc2xlZnQpO1xuXG4gICAgICAgIHRoaXMuc2VsZWN0b3JzLnB1c2goe1xuICAgICAgICAgICAgdG9wOnN0b3AsXG4gICAgICAgICAgICByaWdodDpzcmlnaHQsXG4gICAgICAgICAgICBib3R0b206c2JvdHRvbSxcbiAgICAgICAgICAgIGxlZnQ6c2xlZnQsXG4gICAgICAgICAgICBhY3RpdmU6ZmFsc2VcbiAgICAgICAgfSk7XG4gICAgfTtcbiAgICB0aGlzLmdldEZpcnN0Tm9uQWN0aXZlU2VsZWN0b3IgPSBmdW5jdGlvbigpe1xuICAgICAgICB2YXIgaSA9IDA7XG4gICAgICAgIGZvciggOyBpIDwgIHRoaXMuc2VsZWN0b3JzLmxlbmd0aDsgaSsrKXtcbiAgICAgICAgICAgIGlmKCF0aGlzLnNlbGVjdG9yc1tpXS5hY3RpdmUpe1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLnNlbGVjdG9yc1tpXVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHRoaXMuYnVpbGRTZWxlY3RvcigpO1xuICAgICAgICByZXR1cm4gdGhpcy5zZWxlY3RvcnNbdGhpcy5zZWxlY3RvcnMubGVuZ3RoLTFdO1xuICAgIH07XG4gICAgdGhpcy5kZWFjdGl2YXRlQWxsID0gZnVuY3Rpb24oKXtcbiAgICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgZm9yKCA7IGkgPCAgdGhpcy5zZWxlY3RvcnMubGVuZ3RoOyBpKyspe1xuICAgICAgICAgICAgdGhpcy5zZWxlY3RvcnNbaV0uYWN0aXZlID0gZmFsc2U7XG4gICAgICAgIH1cbiAgICB9O1xuXG5cbiAgICB0aGlzLnBhdXNlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy5hY3RpdmUoZmFsc2UpO1xuICAgICAgICB0aGlzLmhpZGVBbGwoKTtcbiAgICB9O1xuICAgIHRoaXMuaGlkZUFsbCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgZm9yKCA7IGkgPCAgdGhpcy5zZWxlY3RvcnMubGVuZ3RoOyBpKyspe1xuICAgICAgICAgICAgdGhpcy5oaWRlSXRlbSh0aGlzLnNlbGVjdG9yc1tpXSk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5oaWRlSXRlbSh0aGlzLmludGVyYWN0b3JzKVxuICAgIH07XG5cbiAgICB0aGlzLmhpZGVJdGVtID0gZnVuY3Rpb24oaXRlbSl7XG5cbiAgICAgICAgaXRlbS5hY3RpdmUgPSBmYWxzZTtcbiAgICAgICAgZm9yICh2YXIgeCBpbiBpdGVtKXtcbiAgICAgICAgICAgIGlmKCFpdGVtW3hdKSBjb250aW51ZTtcbiAgICAgICAgICAgIGl0ZW1beF0uc3R5bGUudmlzaWJpbGl0eSA9ICdoaWRkZW4nO1xuICAgICAgICB9XG4gICAgfTtcbiAgICB0aGlzLnNob3dJdGVtID0gZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgIGZvciAodmFyIHggaW4gaXRlbSkge1xuICAgICAgICAgICAgaWYodHlwZW9mIGl0ZW1beF0gPT09ICdib29sZWFuJyB8fCAhaXRlbVt4XS5jbGFzc05hbWUgfHwgaXRlbVt4XS5jbGFzc05hbWUuaW5kZXhPZignbXctc2VsZWN0b3InKSA9PT0gLTEpIGNvbnRpbnVlO1xuICAgICAgICAgICAgaXRlbVt4XS5zdHlsZS52aXNpYmlsaXR5ID0gJ3Zpc2libGUnO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMuYnVpbGRJbnRlcmFjdG9yID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIHN0b3AgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgc3JpZ2h0ID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgdmFyIHNib3R0b20gPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgc2xlZnQgPSB0aGlzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuXG4gICAgICAgIHN0b3AuY2xhc3NOYW1lID0gJ213LXNlbGVjdG9yIG13LWludGVyYWN0b3IgbXctc2VsZWN0b3ItdG9wJztcbiAgICAgICAgc3JpZ2h0LmNsYXNzTmFtZSA9ICdtdy1zZWxlY3RvciBtdy1pbnRlcmFjdG9yIG13LXNlbGVjdG9yLXJpZ2h0JztcbiAgICAgICAgc2JvdHRvbS5jbGFzc05hbWUgPSAnbXctc2VsZWN0b3IgbXctaW50ZXJhY3RvciBtdy1zZWxlY3Rvci1ib3R0b20nO1xuICAgICAgICBzbGVmdC5jbGFzc05hbWUgPSAnbXctc2VsZWN0b3IgbXctaW50ZXJhY3RvciBtdy1zZWxlY3Rvci1sZWZ0JztcblxuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc3RvcCk7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChzcmlnaHQpO1xuICAgICAgICB0aGlzLmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoc2JvdHRvbSk7XG4gICAgICAgIHRoaXMuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChzbGVmdCk7XG5cbiAgICAgICAgdGhpcy5pbnRlcmFjdG9ycyA9IHtcbiAgICAgICAgICAgIHRvcDpzdG9wLFxuICAgICAgICAgICAgcmlnaHQ6c3JpZ2h0LFxuICAgICAgICAgICAgYm90dG9tOnNib3R0b20sXG4gICAgICAgICAgICBsZWZ0OnNsZWZ0XG4gICAgICAgIH07XG4gICAgfTtcbiAgICB0aGlzLmlzU2VsZWN0ZWQgPSBmdW5jdGlvbihlKXtcbiAgICAgICAgdmFyIHRhcmdldCA9IGUudGFyZ2V0P2UudGFyZ2V0OmU7XG4gICAgICAgIHJldHVybiB0aGlzLnNlbGVjdGVkLmluZGV4T2YodGFyZ2V0KSAhPT0gLTE7XG4gICAgfTtcblxuICAgIHRoaXMudW5zZXRJdGVtID0gZnVuY3Rpb24oZSl7XG4gICAgICAgIHZhciB0YXJnZXQgPSBlLnRhcmdldD9lLnRhcmdldDplO1xuICAgICAgICBmb3IodmFyIGkgPSAwO2k8dGhpcy5zZWxlY3RvcnMubGVuZ3RoO2krKyl7XG4gICAgICAgICAgICBpZih0aGlzLnNlbGVjdG9yc1tpXS5hY3RpdmUgPT09IHRhcmdldCl7XG4gICAgICAgICAgICAgICAgdGhpcy5oaWRlSXRlbSh0aGlzLnNlbGVjdG9yc1tpXSk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5zZWxlY3RlZC5zcGxpY2UodGhpcy5zZWxlY3RlZC5pbmRleE9mKHRhcmdldCksIDEpO1xuICAgIH07XG5cbiAgICB0aGlzLnBvc2l0aW9uU2VsZWN0ZWQgPSBmdW5jdGlvbigpe1xuICAgICAgICBmb3IodmFyIGkgPSAwO2k8dGhpcy5zZWxlY3RvcnMubGVuZ3RoO2krKyl7XG4gICAgICAgICAgICB0aGlzLnBvc2l0aW9uKHRoaXMuc2VsZWN0b3JzW2ldLCB0aGlzLnNlbGVjdG9yc1tpXS5hY3RpdmUpXG4gICAgICAgIH1cbiAgICB9O1xuICAgIHRoaXMucG9zaXRpb24gPSBmdW5jdGlvbihpdGVtLCB0YXJnZXQpe1xuICAgICAgICB2YXIgb2ZmID0gbXcuJCh0YXJnZXQpLm9mZnNldCgpO1xuICAgICAgICBtdy5jc3MoaXRlbS50b3AsIHtcbiAgICAgICAgICAgIHRvcDpvZmYudG9wLFxuICAgICAgICAgICAgbGVmdDpvZmYubGVmdCxcbiAgICAgICAgICAgIHdpZHRoOnRhcmdldC5vZmZzZXRXaWR0aFxuICAgICAgICB9KTtcbiAgICAgICAgbXcuY3NzKGl0ZW0ucmlnaHQsIHtcbiAgICAgICAgICAgIHRvcDpvZmYudG9wLFxuICAgICAgICAgICAgbGVmdDpvZmYubGVmdCt0YXJnZXQub2Zmc2V0V2lkdGgsXG4gICAgICAgICAgICBoZWlnaHQ6dGFyZ2V0Lm9mZnNldEhlaWdodFxuICAgICAgICB9KTtcbiAgICAgICAgbXcuY3NzKGl0ZW0uYm90dG9tLCB7XG4gICAgICAgICAgICB0b3A6b2ZmLnRvcCt0YXJnZXQub2Zmc2V0SGVpZ2h0LFxuICAgICAgICAgICAgbGVmdDpvZmYubGVmdCxcbiAgICAgICAgICAgIHdpZHRoOnRhcmdldC5vZmZzZXRXaWR0aFxuICAgICAgICB9KTtcbiAgICAgICAgbXcuY3NzKGl0ZW0ubGVmdCwge1xuICAgICAgICAgICAgdG9wOm9mZi50b3AsXG4gICAgICAgICAgICBsZWZ0Om9mZi5sZWZ0LFxuICAgICAgICAgICAgaGVpZ2h0OnRhcmdldC5vZmZzZXRIZWlnaHRcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuc2V0SXRlbSA9IGZ1bmN0aW9uKGUsIGl0ZW0sIHNlbGVjdCwgZXh0ZW5kKXtcbiAgICAgICAgaWYoIWUgfHwgIXRoaXMuYWN0aXZlKCkpIHJldHVybjtcbiAgICAgICAgdmFyIHRhcmdldCA9IGUudGFyZ2V0ID8gZS50YXJnZXQgOiBlO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLnN0cmljdCkge1xuICAgICAgICAgICAgdGFyZ2V0ID0gbXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQodGFyZ2V0LCBbJ1tpZF0nLCAnLmVkaXQnXSk7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHZhbGlkYXRlVGFyZ2V0ID0gIW13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KHRhcmdldCwgWycubXctY29udHJvbC1ib3gnLCAnLm13LWRlZmF1bHRzJ10pO1xuICAgICAgICBpZighdGFyZ2V0IHx8ICF2YWxpZGF0ZVRhcmdldCkgcmV0dXJuO1xuICAgICAgICBpZigkKHRhcmdldCkuaGFzQ2xhc3MoJ213LXNlbGVjdC1za2lwJykpe1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuc2V0SXRlbSh0YXJnZXQucGFyZW50Tm9kZSwgaXRlbSwgc2VsZWN0LCBleHRlbmQpO1xuICAgICAgICB9XG4gICAgICAgIGlmKHNlbGVjdCl7XG4gICAgICAgICAgICBpZih0aGlzLm9wdGlvbnMudG9nZ2xlU2VsZWN0ICYmIHRoaXMuaXNTZWxlY3RlZCh0YXJnZXQpKXtcbiAgICAgICAgICAgICAgICB0aGlzLnVuc2V0SXRlbSh0YXJnZXQpO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgaWYoZXh0ZW5kKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZWxlY3RlZC5wdXNoKHRhcmdldCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2VsZWN0ZWQgPSBbdGFyZ2V0XTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS50cmlnZ2VyKCdzZWxlY3QnLCBbdGhpcy5zZWxlY3RlZF0pO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH1cblxuXG4gICAgICAgIHRoaXMucG9zaXRpb24oaXRlbSwgdGFyZ2V0KTtcblxuICAgICAgICBpdGVtLmFjdGl2ZSA9IHRhcmdldDtcblxuICAgICAgICB0aGlzLnNob3dJdGVtKGl0ZW0pO1xuICAgIH07XG5cbiAgICB0aGlzLnNlbGVjdCA9IGZ1bmN0aW9uKGUsIHRhcmdldCl7XG4gICAgICAgIGlmKCFlICYmICF0YXJnZXQpIHJldHVybjtcbiAgICAgICAgaWYoIWUubm9kZVR5cGUpe1xuICAgICAgICAgICAgdGFyZ2V0ID0gdGFyZ2V0IHx8IGUudGFyZ2V0O1xuICAgICAgICB9IGVsc2V7XG4gICAgICAgICAgICB0YXJnZXQgPSBlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYoZS5jdHJsS2V5KXtcbiAgICAgICAgICAgIHRoaXMuc2V0SXRlbSh0YXJnZXQsIHRoaXMuZ2V0Rmlyc3ROb25BY3RpdmVTZWxlY3RvcigpLCB0cnVlLCB0cnVlKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICAgdGhpcy5oaWRlQWxsKCk7XG4gICAgICAgICAgICB0aGlzLnNldEl0ZW0odGFyZ2V0LCB0aGlzLnNlbGVjdG9yc1swXSwgdHJ1ZSwgZmFsc2UpO1xuICAgICAgICB9XG5cbiAgICB9O1xuXG4gICAgdGhpcy5kZXNlbGVjdCA9IGZ1bmN0aW9uKGUsIHRhcmdldCl7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdGFyZ2V0ID0gdGFyZ2V0IHx8IGUudGFyZ2V0O1xuXG4gICAgICAgIHRoaXMudW5zZXRJdGVtKHRhcmdldCk7XG5cbiAgICB9O1xuXG4gICAgdGhpcy5pbml0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy5idWlsZFNlbGVjdG9yKCk7XG4gICAgICAgIHRoaXMuYnVpbGRJbnRlcmFjdG9yKCk7XG4gICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG4gICAgICAgIG13LiQodGhpcy5yb290KS5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgaWYoc2NvcGUub3B0aW9ucy5hdXRvU2VsZWN0ICYmIHNjb3BlLmFjdGl2ZSgpKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zZWxlY3QoZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIG13LiQodGhpcy5yb290KS5vbiggXCJtb3VzZW1vdmUgdG91Y2htb3ZlIHRvdWNoZW5kXCIsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgaWYoc2NvcGUub3B0aW9ucy5hdXRvU2VsZWN0ICYmIHNjb3BlLmFjdGl2ZSgpKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zZXRJdGVtKGUsIHNjb3BlLmludGVyYWN0b3JzKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIG13LiQodGhpcy5yb290KS5vbiggJ3Njcm9sbCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBzY29wZS5wb3NpdGlvblNlbGVjdGVkKCk7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKHdpbmRvdykub24oJ3Jlc2l6ZSBvcmllbnRhdGlvbmNoYW5nZScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBzY29wZS5wb3NpdGlvblNlbGVjdGVkKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICB0aGlzLl9hY3RpdmUgPSBmYWxzZTtcbiAgICB0aGlzLmFjdGl2ZSA9IGZ1bmN0aW9uIChzdGF0ZSkge1xuICAgICAgICBpZih0eXBlb2Ygc3RhdGUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fYWN0aXZlO1xuICAgICAgICB9XG4gICAgICAgIGlmKHRoaXMuX2FjdGl2ZSAhPT0gc3RhdGUpIHtcbiAgICAgICAgICAgIHRoaXMuX2FjdGl2ZSA9IHN0YXRlO1xuICAgICAgICAgICAgbXcuJCh0aGlzKS50cmlnZ2VyKCdzdGF0ZUNoYW5nZScsIFtzdGF0ZV0pO1xuICAgICAgICB9XG4gICAgfTtcbiAgICB0aGlzLnNlbGVjdGVkID0gW107XG4gICAgdGhpcy5zZWxlY3RvcnMgPSBbXTtcbiAgICB0aGlzLnJvb3QgPSBvcHRpb25zLnJvb3Q7XG4gICAgdGhpcy5pbml0KCk7XG59O1xuIiwibXcuZWRpdFNvdXJjZSA9IGZ1bmN0aW9uIChub2RlKSB7XHJcbiAgICBpZiAoIW13Ll9lZGl0U291cmNlKSB7XHJcbiAgICAgICAgbXcuX2VkaXRTb3VyY2UgPSB7XHJcbiAgICAgICAgICAgIHdyYXBwZXI6IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpLFxyXG4gICAgICAgICAgICBhcmVhOiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCd0ZXh0YXJlYScpLFxyXG4gICAgICAgICAgICBvazogZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbXdidG4nKSxcclxuICAgICAgICAgICAgY2FuY2VsOiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdtd2J0bicpLFxyXG4gICAgICAgICAgICBuYXY6ZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2JyksXHJcbiAgICAgICAgICAgIHZhbGlkYXRvcjpkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgbXcuJChtdy5fZWRpdFNvdXJjZS5vaykuYWRkQ2xhc3MoJ213LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIG13LXVpLWJ0bi1pbmZvJykuaHRtbChtdy5sYW5nKCdPSycpKTtcclxuICAgICAgICBtdy4kKG13Ll9lZGl0U291cmNlLmNhbmNlbCkuYWRkQ2xhc3MoJ213LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtJykuaHRtbChtdy5sYW5nKCdDYW5jZWwnKSk7XHJcblxyXG4gICAgICAgIG13Ll9lZGl0U291cmNlLndyYXBwZXIuYXBwZW5kQ2hpbGQobXcuX2VkaXRTb3VyY2UuYXJlYSk7XHJcbiAgICAgICAgbXcuX2VkaXRTb3VyY2Uud3JhcHBlci5hcHBlbmRDaGlsZChtdy5fZWRpdFNvdXJjZS5uYXYpO1xyXG4gICAgICAgIG13Ll9lZGl0U291cmNlLm5hdi5hcHBlbmRDaGlsZChtdy5fZWRpdFNvdXJjZS5jYW5jZWwpO1xyXG4gICAgICAgIG13Ll9lZGl0U291cmNlLm5hdi5hcHBlbmRDaGlsZChtdy5fZWRpdFNvdXJjZS5vayk7XHJcbiAgICAgICAgbXcuX2VkaXRTb3VyY2UubmF2LmNsYXNzTmFtZSA9ICdtdy1pbmxpbmUtc291cmNlLWVkaXRvci1idXR0b25zJztcclxuICAgICAgICBtdy5fZWRpdFNvdXJjZS53cmFwcGVyLmNsYXNzTmFtZSA9ICdtdy1pbmxpbmUtc291cmNlLWVkaXRvcic7XHJcbiAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChtdy5fZWRpdFNvdXJjZS53cmFwcGVyKTtcclxuICAgICAgICBtdy4kKG13Ll9lZGl0U291cmNlLmNhbmNlbCkub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBtdy4kKG13Ll9lZGl0U291cmNlLnRhcmdldCkuaHRtbChtdy5fZWRpdFNvdXJjZS5hcmVhLnZhbHVlKTtcclxuICAgICAgICAgICAgbXcuJChtdy5fZWRpdFNvdXJjZS53cmFwcGVyKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIG13Ll9lZGl0U291cmNlLm9rLmRpc2FibGVkID0gZmFsc2U7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbXcuJChtdy5fZWRpdFNvdXJjZS5hcmVhKS5vbignaW5wdXQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIG13Ll9lZGl0U291cmNlLnZhbGlkYXRvci5pbm5lckhUTUwgPSBtdy5fZWRpdFNvdXJjZS5hcmVhLnZhbHVlO1xyXG4gICAgICAgICAgICBtdy5fZWRpdFNvdXJjZS5vay5kaXNhYmxlZCA9IG13Ll9lZGl0U291cmNlLnZhbGlkYXRvci5pbm5lckhUTUwgIT09IG13Ll9lZGl0U291cmNlLmFyZWEudmFsdWU7XHJcbiAgICAgICAgICAgIG13Ll9lZGl0U291cmNlLm9rLmNsYXNzTGlzdFttdy5fZWRpdFNvdXJjZS5vay5kaXNhYmxlZCA/ICdhZGQnIDogJ3JlbW92ZSddKCdkaXNhYmxlZCcpO1xyXG4gICAgICAgICAgICB2YXIgaGFzRXJyID0gbXcuJCgnLm13LWlubGluZS1zb3VyY2UtZWRpdG9yLWVycm9yJywgbXcuX2VkaXRTb3VyY2UubmF2KTtcclxuICAgICAgICAgICAgaWYobXcuX2VkaXRTb3VyY2Uub2suZGlzYWJsZWQpIHtcclxuICAgICAgICAgICAgICAgIGlmKCFoYXNFcnIubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuJChtdy5fZWRpdFNvdXJjZS5uYXYpLnByZXBlbmQoJzxzcGFuIGNsYXNzPVwibXctaW5saW5lLXNvdXJjZS1lZGl0b3ItZXJyb3JcIj4nICsgbXcubGFuZygnSW52YWxpZCBIVE1MJykgKyAnPC9zcGFuPicpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgaGFzRXJyLnJlbW92ZSgpXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgICAgICBtdy4kKG13Ll9lZGl0U291cmNlLm9rKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIGlmKCFtdy5fZWRpdFNvdXJjZS5vay5kaXNhYmxlZCl7XHJcbiAgICAgICAgICAgICAgICBtdy4kKG13Ll9lZGl0U291cmNlLnRhcmdldCkuaHRtbChtdy5fZWRpdFNvdXJjZS5hcmVhLnZhbHVlKTtcclxuICAgICAgICAgICAgICAgIG13LiQobXcuX2VkaXRTb3VyY2Uud3JhcHBlcikucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuX2VkaXRTb3VyY2UudGFyZ2V0KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG4gICAgbXcuX2VkaXRTb3VyY2UuYXJlYS52YWx1ZSA9IG5vZGUuaW5uZXJIVE1MO1xyXG4gICAgbXcuX2VkaXRTb3VyY2UudGFyZ2V0ID0gbm9kZTtcclxuICAgIHZhciAkbm9kZSA9IG13LiQobm9kZSksIG9mZiA9ICRub2RlLm9mZnNldCgpLCBud2lkdGggPSAkbm9kZS5vdXRlcldpZHRoKCk7XHJcbiAgICB2YXIgc2wgPSBtdy4kKCcubXctbGl2ZS1lZGl0LXNpZGViYXItdGFicy13cmFwcGVyJykub2Zmc2V0KCk7XHJcbiAgICBpZiAob2ZmLmxlZnQgKyBud2lkdGggPiBzbC5sZWZ0KSB7XHJcbiAgICAgICAgb2ZmLmxlZnQgLT0gKChvZmYubGVmdCArIG53aWR0aCkgLSBzbC5sZWZ0ICsgMTApO1xyXG4gICAgfVxyXG4gICAgbXcuJChtdy5fZWRpdFNvdXJjZS5hcmVhKVxyXG4gICAgICAgIC5oZWlnaHQoJG5vZGUub3V0ZXJIZWlnaHQoKSlcclxuICAgICAgICAud2lkdGgobndpZHRoKTtcclxuXHJcbiAgICBtdy4kKG13Ll9lZGl0U291cmNlLndyYXBwZXIpXHJcbiAgICAgICAgLmNzcyhvZmYpXHJcbiAgICAgICAgLmFkZENsYXNzKCdhY3RpdmUnKTtcclxuXHJcblxyXG59O1xyXG4iLCJtdy5yZXF1aXJlKCdsaWJzL2Nzc2pzb24vY3NzanNvbi5qcycpO1xyXG5cclxuXHJcbm13LmxpdmVlZGl0Q1NTRWRpdG9yID0gZnVuY3Rpb24gKGNvbmZpZykge1xyXG4gICAgdmFyIHNjb3BlID0gdGhpcztcclxuICAgIGNvbmZpZyA9IGNvbmZpZyB8fCB7fTtcclxuICAgIGNvbmZpZy5kb2N1bWVudCA9IGNvbmZpZy5kb2N1bWVudCB8fCBkb2N1bWVudDtcclxuICAgIHZhciBub2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignbGlua1tocmVmKj1cImxpdmVfZWRpdFwiXScpO1xyXG4gICAgdmFyIGRlZmF1bHRzID0ge1xyXG4gICAgICAgIGNzc1VybDogbm9kZSA/IG5vZGUuaHJlZiA6IG51bGwsXHJcbiAgICAgICAgc2F2ZVVybDogbXcuc2V0dGluZ3MuYXBpX3VybCArIFwiY3VycmVudF90ZW1wbGF0ZV9zYXZlX2N1c3RvbV9jc3NcIlxyXG4gICAgfTtcclxuICAgIHRoaXMuc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIGNvbmZpZyk7XHJcblxyXG4gICAgdGhpcy5qc29uID0gbnVsbDtcclxuXHJcbiAgICB0aGlzLmdldEJ5VXJsID0gZnVuY3Rpb24gKHVybCwgY2FsbGJhY2spIHtcclxuICAgICAgICByZXR1cm4gJC5nZXQodXJsLCBmdW5jdGlvbiAoY3NzKSB7XHJcbiAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodGhpcywgY3NzKVxyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmdldExpdmVlZGl0Q1NTID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGlmKCB0aGlzLnNldHRpbmdzLmNzc1VybCApIHtcclxuICAgICAgICAgICAgdGhpcy5nZXRCeVVybCggdGhpcy5zZXR0aW5ncy5jc3NVcmwsIGZ1bmN0aW9uIChjc3MpIHtcclxuICAgICAgICAgICAgICAgIGlmKC88XFwvP1thLXpdW1xcc1xcU10qPi9pLnRlc3QoY3NzKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmpzb24gPSB7fTtcclxuICAgICAgICAgICAgICAgICAgICBzY29wZS5fY3NzID0gJyc7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmpzb24gPSBDU1NKU09OLnRvSlNPTihjc3MpO1xyXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLl9jc3MgPSBjc3M7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdyZWFkeScpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIHNjb3BlLmpzb24gPSB7fTtcclxuICAgICAgICAgICAgc2NvcGUuX2NzcyA9ICcnO1xyXG4gICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdyZWFkeScpO1xyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG5cclxuICAgIHRoaXMuX2Nzc1RlbXAgPSBmdW5jdGlvbiAoanNvbikge1xyXG4gICAgICAgIHZhciBjc3MgPSBDU1NKU09OLnRvQ1NTKGpzb24pO1xyXG4gICAgICAgIGlmKCFtdy5saXZlZWRpdC5fY3NzVGVtcCkge1xyXG4gICAgICAgICAgICBtdy5saXZlZWRpdC5fY3NzVGVtcCA9IG13LnRvb2xzLmNyZWF0ZVN0eWxlKCcjbXctbGl2ZWVkaXQtZHluYW1pYy10ZW1wLXN0eWxlJywgY3NzLCBkb2N1bWVudC5ib2R5KTtcclxuICAgICAgICAgICAgbXcubGl2ZWVkaXQuX2Nzc1RlbXAuaWQgPSAnbXctbGl2ZWVkaXQtZHluYW1pYy10ZW1wLXN0eWxlJztcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICBtdy5saXZlZWRpdC5fY3NzVGVtcC5pbm5lckhUTUwgPSBjc3M7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmNoYW5nZWQgPSBmYWxzZTtcclxuICAgIHRoaXMuX3RlbXAgPSB7Y2hpbGRyZW46IHt9LCBhdHRyaWJ1dGVzOiB7fX07XHJcbiAgICB0aGlzLnRlbXAgPSBmdW5jdGlvbiAobm9kZSwgcHJvcCwgdmFsKSB7XHJcbiAgICAgICAgdGhpcy5jaGFuZ2VkID0gdHJ1ZTtcclxuICAgICAgICB2YXIgc2VsID0gbXcudG9vbHMuZ2VuZXJhdGVTZWxlY3RvckZvck5vZGUobm9kZSk7XHJcbiAgICAgICAgaWYoIXRoaXMuX3RlbXAuY2hpbGRyZW5bc2VsXSkge1xyXG4gICAgICAgICAgICB0aGlzLl90ZW1wLmNoaWxkcmVuW3NlbF0gPSB7fTtcclxuICAgICAgICB9XHJcbiAgICAgICAgaWYgKCF0aGlzLl90ZW1wLmNoaWxkcmVuW3NlbF0uYXR0cmlidXRlcyApIHtcclxuICAgICAgICAgICAgdGhpcy5fdGVtcC5jaGlsZHJlbltzZWxdLmF0dHJpYnV0ZXMgPSB7fTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5fdGVtcC5jaGlsZHJlbltzZWxdLmF0dHJpYnV0ZXNbcHJvcF0gPSB2YWw7XHJcbiAgICAgICAgdGhpcy5fY3NzVGVtcCh0aGlzLl90ZW1wKTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy50aW1lT3V0ID0gbnVsbDtcclxuXHJcbiAgICB0aGlzLnNhdmUgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgdGhpcy5qc29uID0gJC5leHRlbmQodHJ1ZSwge30sIHRoaXMuanNvbiwgdGhpcy5fdGVtcCk7XHJcbiAgICAgICAgdGhpcy5fY3NzID0gQ1NTSlNPTi50b0NTUyh0aGlzLmpzb24pLnJlcGxhY2UoL1xcLlxcLi9nLCAnLicpLnJlcGxhY2UoL1xcLlxcLi9nLCAnLicpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnB1Ymxpc2ggPSBmdW5jdGlvbiAoY2FsbGJhY2spIHtcclxuICAgICAgICB2YXIgY3NzID0ge1xyXG4gICAgICAgICAgICBjc3NfZmlsZV9jb250ZW50OiB0aGlzLmdldFZhbHVlKClcclxuICAgICAgICB9O1xyXG4gICAgICAgICQucG9zdCh0aGlzLnNldHRpbmdzLnNhdmVVcmwsIGNzcywgZnVuY3Rpb24gKHJlcykge1xyXG4gICAgICAgICAgICBzY29wZS5jaGFuZ2VkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIGlmKGNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKHRoaXMsIHJlcyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5wdWJsaXNoSWZDaGFuZ2VkID0gZnVuY3Rpb24gKGNhbGxiYWNrKSB7XHJcbiAgICAgICAgaWYodGhpcy5jaGFuZ2VkKSB7XHJcbiAgICAgICAgICAgIHRoaXMucHVibGlzaChjYWxsYmFjayk7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmdldFZhbHVlID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHRoaXMuc2F2ZSgpO1xyXG4gICAgICAgIHJldHVybiB0aGlzLl9jc3M7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB0aGlzLmdldExpdmVlZGl0Q1NTKCk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuaW5pdCgpO1xyXG5cclxufTtcclxuXHJcbiIsIm13LnRlbXBDU1MgPSBmdW5jdGlvbihvcHRpb25zKXtcclxuXHJcbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xyXG5cclxuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xyXG5cclxuICAgIHZhciBkZWZhdWx0cyA9IHtcclxuICAgICAgICBkb2N1bWVudDogZG9jdW1lbnQsXHJcbiAgICAgICAgY3NzOiB7fVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcclxuXHJcbiAgICB0aGlzLnN0eWxlRWxlbWVudCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGlmKCF0aGlzLl9zdHlsZUVsZW1lbnQpe1xyXG4gICAgICAgICAgICB0aGlzLl9zdHlsZUVsZW1lbnQgPSB0aGlzLnNldHRpbmdzLmRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3N0eWxlJyk7XHJcbiAgICAgICAgICAgIHRoaXMuX3N0eWxlRWxlbWVudC50eXBlID0gJ3RleHQvY3NzJztcclxuICAgICAgICAgICAgdGhpcy5fc3R5bGVFbGVtZW50LmFwcGVuZENoaWxkKGRvY3VtZW50LmNyZWF0ZVRleHROb2RlKCcnKSk7IC8vIHdlYmtpdFxyXG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzLmRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQodGhpcy5fc3R5bGVFbGVtZW50KTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuX3N0eWxlRWxlbWVudDtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5tb2RpZnlPYmplY3QgPSBmdW5jdGlvbigpe1xyXG5cclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5hZGRTdHlsZSA9IGZ1bmN0aW9uKGVsZW1lbnQsIHN0eWxlLCBtZWRpYSl7XHJcbiAgICAgICAgaWYoIWVsZW1lbnQpIHJldHVybjtcclxuICAgICAgICBpZihlbGVtZW50LnRhZ05hbWUpIHtcclxuICAgICAgICAgICAgZWxlbWVudCA9IG13LnRvb2xzLmdlbmVyYXRlU2VsZWN0b3JGb3JOb2RlKGVsZW1lbnQpO1xyXG4gICAgICAgIH1cclxuICAgIH07XHJcbn07XHJcbiIsIm13LmxpdmVlZGl0LnRvb2xiYXIgPSB7XHJcbiAgICBmaXhQYWQ6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBtd2QuYm9keS5zdHlsZS5wYWRkaW5nVG9wID0gcGFyc2VGbG9hdCgkKG13ZC5ib2R5KS5jc3MoXCJwYWRkaW5nVG9wXCIpKSArIG13LiQoXCIjbGl2ZV9lZGl0X3Rvb2xiYXJcIikuaGVpZ2h0KCkgKyAncHgnO1xyXG4gICAgfSxcclxuICAgIHNldEVkaXRvcjogZnVuY3Rpb24oKXtcclxuICAgICAgICAvKm13XHJcbiAgICAgICAgICAgIC4kKG13ZC5xdWVyeVNlbGVjdG9yKCcuZWRpdG9yX3dyYXBwZXJfdGFibGVkJykpXHJcbiAgICAgICAgICAgIC5jc3Moe1xyXG4gICAgICAgICAgICAgICAgbGVmdDogbXcuJChtd2QucXVlcnlTZWxlY3RvcignLnRvb2xiYXItc2VjdGlvbnMtdGFicycpKS5vdXRlcldpZHRoKHRydWUpICsgbXcuJChtd2QucXVlcnlTZWxlY3RvcignLnd5c2l3eWctdW5kby1yZWRvJykpLm91dGVyV2lkdGgodHJ1ZSkgKyAzMCxcclxuICAgICAgICAgICAgICAgIHJpZ2h0OiBtdy4kKG13ZC5xdWVyeVNlbGVjdG9yKCcjbXctdG9vbGJhci1yaWdodCcpKS5vdXRlcldpZHRoKHRydWUpXHJcbiAgICAgICAgICAgIH0pOyovXHJcbiAgICB9LFxyXG4gICAgcHJlcGFyZTogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIG13LiQoXCIjbGl2ZWVkaXRfd3lzaXd5Z1wiKVxyXG4gICAgICAgICAgICAub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JyxmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgICAgIGlmIChtdy4kKFwiLm13X2VkaXRvcl9idG5faG92ZXJcIikubGVuZ3RoID09PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcubW91c2VEb3duT25FZGl0b3IgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykuYWRkQ2xhc3MoXCJob3ZlclwiKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLm9uKCdtb3VzZXVwIHRvdWNoZW5kJyxmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgICAgIG13Lm1vdXNlRG93bk9uRWRpdG9yID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZUNsYXNzKFwiaG92ZXJcIik7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIG13LiQod2luZG93KS5zY3JvbGwoZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGlmICgkKHdpbmRvdykuc2Nyb2xsVG9wKCkgPiAxMCkge1xyXG4gICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MobXdkLmdldEVsZW1lbnRCeUlkKCdsaXZlX2VkaXRfdG9vbGJhcicpLCAnc2Nyb2xsaW5nJyk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtd2QuZ2V0RWxlbWVudEJ5SWQoJ2xpdmVfZWRpdF90b29sYmFyJyksICdzY3JvbGxpbmcnKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICB9KTtcclxuICAgICAgICBtdy4kKFwiI2xpdmVfZWRpdF90b29sYmFyXCIpLmhvdmVyKGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5hZGRDbGFzcyhcInRvb2xiYXItaG92ZXJcIik7XHJcbiAgICAgICAgfSwgZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwidG9vbGJhci1ob3ZlclwiKTtcclxuICAgICAgICB9KTtcclxuICAgIH0sXHJcbiAgICBlZGl0b3I6IHtcclxuICAgICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZWQgPSBtd2QuZ2V0RWxlbWVudEJ5SWQoJ2xpdmVlZGl0X3d5c2l3eWcnKTtcclxuICAgICAgICAgICAgdGhpcy5uZXh0QlROUyA9IG13LiQoXCIubGl2ZWVkaXRfd3lzaXd5Z19uZXh0XCIpO1xyXG4gICAgICAgICAgICB0aGlzLnByZXZCVE5TID0gbXcuJChcIi5saXZlZWRpdF93eXNpd3lnX3ByZXZcIikgO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgY2FsYzoge1xyXG4gICAgICAgICAgICBTbGlkZXJCdXR0b25zTmVlZGVkOiBmdW5jdGlvbiAocGFyZW50KSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgdCA9IHtsZWZ0OiBmYWxzZSwgcmlnaHQ6IGZhbHNlfTtcclxuICAgICAgICAgICAgICAgIGlmIChwYXJlbnQgPT0gbnVsbCB8fCAhcGFyZW50KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgdmFyIGVsID0gcGFyZW50LmZpcnN0RWxlbWVudENoaWxkO1xyXG4gICAgICAgICAgICAgICAgaWYgKCQocGFyZW50KS53aWR0aCgpID4gbXcuJChlbCkud2lkdGgoKSkgcmV0dXJuIHQ7XHJcbiAgICAgICAgICAgICAgICB2YXIgYSA9IG13LiQocGFyZW50KS5vZmZzZXQoKS5sZWZ0ICsgbXcuJChwYXJlbnQpLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgYiA9IG13LiQoZWwpLm9mZnNldCgpLmxlZnQgKyBtdy4kKGVsKS53aWR0aCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGIgPiBhKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdC5yaWdodCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBpZiAoJChlbCkub2Zmc2V0KCkubGVmdCA8IG13LiQocGFyZW50KS5vZmZzZXQoKS5sZWZ0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdC5sZWZ0ID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHJldHVybiB0O1xyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBTbGlkZXJOb3JtYWxpemU6IGZ1bmN0aW9uIChwYXJlbnQpIHtcclxuICAgICAgICAgICAgICAgIGlmIChwYXJlbnQgPT09IG51bGwgfHwgIXBhcmVudCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHZhciBlbCA9IHBhcmVudC5maXJzdEVsZW1lbnRDaGlsZDtcclxuICAgICAgICAgICAgICAgIHZhciBhID0gbXcuJChwYXJlbnQpLm9mZnNldCgpLmxlZnQgKyBtdy4kKHBhcmVudCkud2lkdGgoKTtcclxuICAgICAgICAgICAgICAgIHZhciBiID0gbXcuJChlbCkub2Zmc2V0KCkubGVmdCArIG13LiQoZWwpLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoYiA8IGEpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gKGEgLSBiKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgU2xpZGVyTmV4dDogZnVuY3Rpb24gKHBhcmVudCwgc3RlcCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHBhcmVudCA9PT0gbnVsbCB8fCAhcGFyZW50KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgdmFyIGVsID0gcGFyZW50LmZpcnN0RWxlbWVudENoaWxkO1xyXG4gICAgICAgICAgICAgICAgaWYgKCQocGFyZW50KS53aWR0aCgpID4gbXcuJChlbCkud2lkdGgoKSkgcmV0dXJuIDA7XHJcbiAgICAgICAgICAgICAgICB2YXIgYSA9IG13LiQocGFyZW50KS5vZmZzZXQoKS5sZWZ0ICsgbXcuJChwYXJlbnQpLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgYiA9IG13LiQoZWwpLm9mZnNldCgpLmxlZnQgKyBtdy4kKGVsKS53aWR0aCgpO1xyXG4gICAgICAgICAgICAgICAgc3RlcCA9IHN0ZXAgfHwgbXcuJChwYXJlbnQpLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgY3VyciA9IHBhcnNlRmxvYXQod2luZG93LmdldENvbXB1dGVkU3R5bGUoZWwsIG51bGwpLmxlZnQpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGEgPCBiKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKChiIC0gc3RlcCkgPiBhKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiAoY3VyciAtIHN0ZXApO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGN1cnIgLSAoYiAtIGEpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBjdXJyIC0gKGIgLSBhKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgU2xpZGVyUHJldjogZnVuY3Rpb24gKHBhcmVudCwgc3RlcCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHBhcmVudCA9PT0gbnVsbCB8fCAhcGFyZW50KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgdmFyIGVsID0gcGFyZW50LmZpcnN0RWxlbWVudENoaWxkO1xyXG4gICAgICAgICAgICAgICAgc3RlcCA9IHN0ZXAgfHwgbXcuJChwYXJlbnQpLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgY3VyciA9IHBhcnNlRmxvYXQod2luZG93LmdldENvbXB1dGVkU3R5bGUoZWwsIG51bGwpLmxlZnQpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGN1cnIgPCAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKChjdXJyICsgc3RlcCkgPCAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiAoY3VyciArIHN0ZXApO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIDA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIDA7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICBzdGVwOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHJldHVybiAkKG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmVkKS53aWR0aCgpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZGVuaWVkOiBmYWxzZSxcclxuICAgICAgICBidXR0b25zOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHZhciBiID0gbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuY2FsYy5TbGlkZXJCdXR0b25zTmVlZGVkKG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmVkKTtcclxuICAgICAgICAgICAgaWYgKCFiKSB7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKGIubGVmdCkge1xyXG4gICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IucHJldkJUTlMuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IucHJldkJUTlMucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmIChiLnJpZ2h0KSB7XHJcbiAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5uZXh0QlROUy5hZGRDbGFzcygnYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5uZXh0QlROUy5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIHNsaWRlTGVmdDogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBpZiAoIW13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmRlbmllZCkge1xyXG4gICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuZGVuaWVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIHZhciBlbCA9IG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmVkLmZpcnN0RWxlbWVudENoaWxkO1xyXG4gICAgICAgICAgICAgICAgdmFyIHRvID0gbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuY2FsYy5TbGlkZXJQcmV2KG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmVkLCBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5zdGVwKCkpO1xyXG4gICAgICAgICAgICAgICAgJChlbCkuYW5pbWF0ZSh7bGVmdDogdG99LCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuZGVuaWVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuYnV0dG9ucygpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIHNsaWRlUmlnaHQ6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgaWYgKCFtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5kZW5pZWQpIHtcclxuICAgICAgICAgICAgICAgIG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLmRlbmllZCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICB2YXIgZWwgPSBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5lZC5maXJzdEVsZW1lbnRDaGlsZDtcclxuXHJcbiAgICAgICAgICAgICAgICB2YXIgdG8gPSBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5jYWxjLlNsaWRlck5leHQobXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuZWQsIG13LmxpdmVlZGl0LnRvb2xiYXIuZWRpdG9yLnN0ZXAoKSk7XHJcbiAgICAgICAgICAgICAgICAkKGVsKS5hbmltYXRlKHtsZWZ0OiB0b30sIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5kZW5pZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5idXR0b25zKCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZml4Q29udmVydGlibGU6IGZ1bmN0aW9uICh3aG8pIHtcclxuICAgICAgICAgICAgd2hvID0gd2hvIHx8IFwiLnd5c2l3eWctY29udmVydGlibGVcIjtcclxuICAgICAgICAgICAgd2hvID0gJCh3aG8pO1xyXG4gICAgICAgICAgICBpZiAod2hvLmxlbmd0aCA+IDEpIHtcclxuICAgICAgICAgICAgICAgICQod2hvKS5lYWNoKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBtdy5saXZlZWRpdC50b29sYmFyLmVkaXRvci5maXhDb252ZXJ0aWJsZSh0aGlzKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgdmFyIHcgPSAkKHdpbmRvdykud2lkdGgoKTtcclxuICAgICAgICAgICAgICAgIHZhciB3MSA9IHdoby5vZmZzZXQoKS5sZWZ0ICsgd2hvLndpZHRoKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodzEgPiB3KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2hvLmNzcyhcImxlZnRcIiwgLSh3MSAtIHcpKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIHdoby5jc3MoXCJsZWZ0XCIsIDApO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufTtcclxuIiwibXcubGl2ZWVkaXQgPSBtdy5saXZlZWRpdCB8fCB7fTtcbm13LmxpdmVlZGl0LndpZGdldHMgPSB7XG4gICAgaHRtbEVkaXRvckRpYWxvZzogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgc3JjID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyAnYXBpL21vZHVsZT9pZD1td19nbG9iYWxfaHRtbF9lZGl0b3ImbGl2ZV9lZGl0PXRydWUmbW9kdWxlX3NldHRpbmdzPXRydWUmdHlwZT1lZGl0b3IvY29kZV9lZGl0b3ImYXV0b3NpemU9dHJ1ZSc7XG4gICAgICAgIC8vIHdpbmRvdy5vcGVuKHNyYywgXCJDb2RlIGVkaXRvclwiLCBcInRvb2xiYXI9bm8sIG1lbnViYXI9bm8sc2Nyb2xsYmFycz15ZXMscmVzaXphYmxlPXllcyxsb2NhdGlvbj1ubyxkaXJlY3Rvcmllcz1ubyxzdGF0dXM9eWVzXCIpO1xuICAgICAgICBtdy5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgdXJsOiBzcmMsXG4gICAgICAgICAgICB0aXRsZTogJ0NvZGUgZWRpdG9yJyxcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgd2lkdGg6ICc5NSUnXG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgY3NzRWRpdG9yRGlhbG9nOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBzcmMgPSBtdy5zZXR0aW5ncy5zaXRlX3VybCArICdhcGkvbW9kdWxlP2lkPW13X2dsb2JhbF9jc3NfZWRpdG9yJmxpdmVfZWRpdD10cnVlJm1vZHVsZV9zZXR0aW5ncz10cnVlJnR5cGU9ZWRpdG9yL2Nzc19lZGl0b3ImYXV0b3NpemU9dHJ1ZSc7XG4gICAgICAgIHJldHVybiBtdy5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgdXJsOiBzcmMsXG4gICAgICAgICAgICAvLyB3aWR0aDogNTAwLFxuICAgICAgICAgICAgaGVpZ2h0OidhdXRvJyxcbiAgICAgICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXG4gICAgICAgICAgICBuYW1lOiAnbXctY3NzLWVkaXRvci1mcm9udCcsXG4gICAgICAgICAgICB0aXRsZTogJ0NTUyBFZGl0b3InLFxuICAgICAgICAgICAgdGVtcGxhdGU6ICdkZWZhdWx0JyxcbiAgICAgICAgICAgIGNlbnRlcjogZmFsc2UsXG4gICAgICAgICAgICByZXNpemU6IHRydWUsXG4gICAgICAgICAgICBkcmFnZ2FibGU6IHRydWVcbiAgICAgICAgfSk7XG4gICAgfVxufTtcbiIsIi8qIFdZU0lXWUcgRWRpdG9yICovXG4vKiBDb250ZW50RWRpdGFibGUgRnVuY3Rpb25zICovXG5cbm13LnJlcXVpcmUoJ2Nzc19wYXJzZXIuanMnKTtcbm13LnJlcXVpcmUoJ2ljb25fc2VsZWN0b3IuanMnKTtcbm13LnJlcXVpcmUoJ2V2ZW50cy5qcycpO1xuXG4vL213LmxpYi5yZXF1aXJlKCdyYW5neScpO1xuXG5jbGFzc0FwcGxpZXIgPSB3aW5kb3cuY2xhc3NBcHBsaWVyIHx8IFtdO1xuaWYgKCFFbGVtZW50LnByb3RvdHlwZS5tYXRjaGVzKSB7XG4gICAgRWxlbWVudC5wcm90b3R5cGUubWF0Y2hlcyA9XG4gICAgICAgIEVsZW1lbnQucHJvdG90eXBlLm1hdGNoZXNTZWxlY3RvciB8fFxuICAgICAgICBFbGVtZW50LnByb3RvdHlwZS5tb3pNYXRjaGVzU2VsZWN0b3IgfHxcbiAgICAgICAgRWxlbWVudC5wcm90b3R5cGUubXNNYXRjaGVzU2VsZWN0b3IgfHxcbiAgICAgICAgRWxlbWVudC5wcm90b3R5cGUub01hdGNoZXNTZWxlY3RvciB8fFxuICAgICAgICBFbGVtZW50LnByb3RvdHlwZS53ZWJraXRNYXRjaGVzU2VsZWN0b3IgfHxcbiAgICAgICAgZnVuY3Rpb24gKHMpIHtcbiAgICAgICAgICAgIHZhciBtYXRjaGVzID0gKHRoaXMuZG9jdW1lbnQgfHwgdGhpcy5vd25lckRvY3VtZW50KS5xdWVyeVNlbGVjdG9yQWxsKHMpLFxuICAgICAgICAgICAgICAgIGkgPSBtYXRjaGVzLmxlbmd0aDtcbiAgICAgICAgICAgIHdoaWxlICgtLWkgPj0gMCAmJiBtYXRjaGVzLml0ZW0oaSkgIT09IHRoaXMpIHtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBpID4gLTE7XG4gICAgICAgIH07XG59XG5cbmlmICh0eXBlb2YgU2VsZWN0aW9uLnByb3RvdHlwZS5jb250YWluc05vZGUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgU2VsZWN0aW9uLnByb3RvdHlwZS5jb250YWluc05vZGUgPSBmdW5jdGlvbiAoYSkge1xuICAgICAgICBpZiAodGhpcy5yYW5nZUNvdW50ID09PSAwKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHIgPSB0aGlzLmdldFJhbmdlQXQoMCk7XG4gICAgICAgIGlmIChyLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyID09PSBhKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoci5lbmRDb250YWluZXIgPT09IGEpIHtcbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChyLnN0YXJ0Q29udGFpbmVyID09PSBhKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoci5jb21tb25BbmNlc3RvckNvbnRhaW5lci5wYXJlbnROb2RlID09PSBhKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoYS5ub2RlVHlwZSAhPT0gMykge1xuICAgICAgICAgICAgdmFyIGMgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoci5jb21tb25BbmNlc3RvckNvbnRhaW5lciksXG4gICAgICAgICAgICAgICAgYiA9IGMucXVlcnlTZWxlY3RvckFsbChhLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCkpLFxuICAgICAgICAgICAgICAgIGwgPSBiLmxlbmd0aCxcbiAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgIGlmIChsID4gMCkge1xuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChiW2ldID09PSBhKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxufVxuXG5pZiAodHlwZW9mIFJhbmdlLnByb3RvdHlwZS5xdWVyeVNlbGVjdG9yID09PSAndW5kZWZpbmVkJykge1xuICAgIFJhbmdlLnByb3RvdHlwZS5xdWVyeVNlbGVjdG9yID0gZnVuY3Rpb24gKHMpIHtcbiAgICAgICAgdmFyIHIgPSB0aGlzO1xuICAgICAgICB2YXIgZiA9IHIuZXh0cmFjdENvbnRlbnRzKCk7XG4gICAgICAgIHZhciBub2RlID0gZi5xdWVyeVNlbGVjdG9yKHMpO1xuICAgICAgICByLmluc2VydE5vZGUoZik7XG4gICAgICAgIHJldHVybiBub2RlO1xuICAgIH1cbn1cblxuaWYgKHR5cGVvZiBSYW5nZS5wcm90b3R5cGUucXVlcnlTZWxlY3RvckFsbCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICBSYW5nZS5wcm90b3R5cGUucXVlcnlTZWxlY3RvckFsbCA9IGZ1bmN0aW9uIChzKSB7XG4gICAgICAgIHZhciByID0gdGhpcztcbiAgICAgICAgdmFyIGYgPSByLmV4dHJhY3RDb250ZW50cygpO1xuICAgICAgICB2YXIgbm9kZXMgPSBmLnF1ZXJ5U2VsZWN0b3JBbGwocyk7XG4gICAgICAgIHIuaW5zZXJ0Tm9kZShmKTtcbiAgICAgICAgcmV0dXJuIG5vZGVzO1xuICAgIH07XG59XG5tdy53eXNpd3lnID0ge1xuXG4gICAgaXNTYWZlTW9kZTogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIGlmICghZWwpIHtcbiAgICAgICAgICAgIHZhciBzZWwgPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICBpZighc2VsLnJhbmdlQ291bnQpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KDApO1xuICAgICAgICAgICAgZWwgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIocmFuZ2UuY29tbW9uQW5jZXN0b3JDb250YWluZXIpO1xuICAgICAgICB9XG4gICAgICAgIHZhciBoYXNTYWZlID0gbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZWwsIFsnc2FmZS1tb2RlJ10pO1xuICAgICAgICB2YXIgcmVnSW5zYWZlID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck5vbmUoZWwsIFsncmVndWxhci1tb2RlJywgJ3NhZmUtbW9kZSddKTtcbiAgICAgICAgcmV0dXJuIGhhc1NhZmUgJiYgIXJlZ0luc2FmZTtcbiAgICB9LFxuICAgIHBhcnNlQ2xhc3NBcHBsaWVyU2hlZXQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHNoZWV0ID0gbXdkLnF1ZXJ5U2VsZWN0b3IoJ2xpbmtbY2xhc3NBcHBsaWVyXScpO1xuICAgICAgICBpZiAoc2hlZXQgIT09IG51bGwpIHtcbiAgICAgICAgICAgIHZhciBydWxlcyA9IHNoZWV0LnNoZWV0LnJ1bGVzO1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBydWxlcy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmICghcnVsZXNbaV0uc2VsZWN0b3JUZXh0KSBjb250aW51ZTtcblxuICAgICAgICAgICAgICAgIHZhciBydWxlID0gcnVsZXNbaV0uc2VsZWN0b3JUZXh0LnRyaW0oKTtcbiAgICAgICAgICAgICAgICB2YXIgc3BsID0gcnVsZS5zcGxpdCgnLicpXG4gICAgICAgICAgICAgICAgaWYgKHJ1bGUuaW5kZXhPZignLicpID09PSAwXG4gICAgICAgICAgICAgICAgICAgICYmIHJ1bGUuaW5kZXhPZignOicpID09PSAtMVxuICAgICAgICAgICAgICAgICAgICAmJiBydWxlLmluZGV4T2YoJyMnKSA9PT0gLTFcbiAgICAgICAgICAgICAgICAgICAgJiYgc3BsLmxlbmd0aCA9PT0gMlxuICAgICAgICAgICAgICAgICAgICAmJiBydWxlLmluZGV4T2YoJ1snKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NBcHBsaWVyLnB1c2goc3BsWzFdKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGluaXRDbGFzc0FwcGxpZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5wYXJzZUNsYXNzQXBwbGllclNoZWV0KCk7XG4gICAgICAgIHZhciBkcm9wZG93biA9IG13LiQoJyNmb3JtYXRfbWFpbiB1bCcpO1xuICAgICAgICBjbGFzc0FwcGxpZXIuZm9yRWFjaChmdW5jdGlvbiAoY2xzLCBpKSB7XG4gICAgICAgICAgICBkcm9wZG93bi5hcHBlbmQoJzxsaSB2YWx1ZT1cIi4nICsgY2xzICsgJ1wiPjxhIGhyZWY9XCIjXCI+PGRpdiBjbGFzcz1cIicgKyBjbHMgKyAnXCI+Q3VzdG9tICcgKyBpICsgJzwvZGl2PjwvYT48L2xpPicpXG4gICAgICAgIH0pXG4gICAgfSxcbiAgICBlZGl0SW5zaWRlTW9kdWxlOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgZWwgPSBlbC50YXJnZXQgPyBlbC50YXJnZXQgOiBlbDtcbiAgICAgICAgdmFyIG9yZGVyID0gbXcudG9vbHMucGFyZW50c09yZGVyKGVsLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuICAgICAgICBpZiAob3JkZXIuZWRpdCA8IG9yZGVyLm1vZHVsZSkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHBhc3RlRnJvbVdvcmRVSTogZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAoIW13Lnd5c2l3eWcuaXNTZWxlY3Rpb25FZGl0YWJsZSgpKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIG13Lnd5c2l3eWcuc2F2ZV9zZWxlY3Rpb24oKTtcbiAgICAgICAgdmFyIGNsZWFuZXIgPSBtdy4kKCc8ZGl2IGNsYXNzPVwibXctY2xlYW5lci1ibG9ja1wiIGNvbnRlbnRlZGl0YWJsZT1cInRydWVcIj48c21hbGwgY2xhc3M9XCJtdXRlZFwiPlBhc3RlIGRvY3VtZW50IGhlcmUuPC9zbWFsbD48L2Rpdj4nKVxuICAgICAgICB2YXIgaW5zZXJ0ZXIgPSBtdy4kKCc8c3BhbiBjbGFzcz1cIm13LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIG13LXVpLWJ0bi1pbnZlcnQgcHVsbC1yaWdodFwiPkluc2VydDwvc3Bhbj4nKVxuICAgICAgICB2YXIgY2xlYW4gPSBtdy5kaWFsb2coe1xuICAgICAgICAgICAgY29udGVudDogY2xlYW5lcixcbiAgICAgICAgICAgIG92ZXJsYXk6IHRydWUsXG4gICAgICAgICAgICB0aXRsZTogJ1Bhc3RlIGZyb20gd29yZCcsXG4gICAgICAgICAgICBmb290ZXI6IGluc2VydGVyLFxuICAgICAgICAgICAgaGVpZ2h0OiAnYXV0bycsXG4gICAgICAgICAgICBhdXRvSGVpZ2h0OiB0cnVlXG4gICAgICAgIH0pO1xuICAgICAgICBjbGVhbmVyLm9uKCdwYXN0ZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGNsZWFuZXJbMF0uaW5uZXJIVE1MID0gbXcud3lzaXd5Zy5jbGVhbl93b3JkKGNsZWFuZXJbMF0uaW5uZXJIVE1MKTtcbiAgICAgICAgICAgIH0sIDEwMClcblxuICAgICAgICB9KTtcbiAgICAgICAgY2xlYW5lci5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoISQodGhpcykuaGFzQ2xhc3MoJ2FjdGl2ZScpKSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hZGRDbGFzcygnYWN0aXZlJylcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmh0bWwoJycpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBpbnNlcnRlci5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnJlc3RvcmVfc2VsZWN0aW9uKCk7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmluc2VydF9odG1sKGNsZWFuZXIuaHRtbCgpKTtcbiAgICAgICAgICAgIGNsZWFuLnJlbW92ZSgpO1xuICAgICAgICB9KTtcbiAgICAgICAgLy9jbGVhbmVyLmFmdGVyKGluc2VydGVyKVxuICAgIH0sXG4gICAgZ2xvYmFsVGFyZ2V0OiBtd2QuYm9keSxcbiAgICBhbGxTdGF0ZW1lbnRzOiBmdW5jdGlvbiAoYywgZikge1xuICAgICAgICB2YXIgc2VsID0gd2luZG93LmdldFNlbGVjdGlvbigpLFxuICAgICAgICAgICAgcmFuZ2UgPSBzZWwuZ2V0UmFuZ2VBdCgwKSxcbiAgICAgICAgICAgIGNvbW1vbiA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihyYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcik7XG4gICAgICAgIC8vdmFyIG5vZHJvcF9zdGF0ZSA9ICFtdy50b29scy5oYXNDbGFzcyhjb21tb24sICdub2Ryb3AnKSAmJiAhbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhjb21tb24sICdub2Ryb3AnKTtcbiAgICAgICAgdmFyIG5vZHJvcF9zdGF0ZSA9IG13LnRvb2xzLnBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3RPck5vbmUoY29tbW9uLCBbJ2FsbG93LWRyb3AnLCAnbm9kcm9wJ10pO1xuXG4gICAgICAgIGlmIChtdy53eXNpd3lnLmlzU2VsZWN0aW9uRWRpdGFibGUoKSAmJiBub2Ryb3Bfc3RhdGUpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYyA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgIGMuY2FsbCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBmID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgZi5jYWxsKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGFjdGlvbjoge1xuICAgICAgICByZW1vdmVmb3JtYXQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzZWwgPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICB2YXIgciA9IHNlbC5nZXRSYW5nZUF0KDApO1xuICAgICAgICAgICAgdmFyIGMgPSByLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyO1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5yZW1vdmVTdHlsZXMoYywgc2VsKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgcmVtb3ZlU3R5bGVzOiBmdW5jdGlvbiAoY29tbW9uLCBzZWwpIHtcbiAgICAgICAgaWYgKCEhY29tbW9uLnF1ZXJ5U2VsZWN0b3JBbGwpIHtcbiAgICAgICAgICAgIHZhciBhbGwgPSBjb21tb24ucXVlcnlTZWxlY3RvckFsbCgnKicpLCBsID0gYWxsLmxlbmd0aCwgaSA9IDA7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IGFsbFtpXTtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIHNlbCAhPT0gJ3VuZGVmaW5lZCcgJiYgc2VsLmNvbnRhaW5zTm9kZShlbCwgdHJ1ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChlbCkucmVtb3ZlQXR0cihcInN0eWxlXCIpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcucmVtb3ZlU3R5bGVzKGNvbW1vbi5wYXJlbnROb2RlKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgaW5pdF9lZGl0YWJsZXM6IGZ1bmN0aW9uIChtb2R1bGUpIHtcbiAgICAgICAgaWYgKHdpbmRvd1snbXdBZG1pbiddKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIG1vZHVsZSAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZShtb2R1bGUsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICBtdy4kKG1vZHVsZS5xdWVyeVNlbGVjdG9yQWxsKFwiLmVkaXRcIikpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZSh0aGlzLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgbXcub24uRE9NQ2hhbmdlKHRoaXMsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHZhciBlZGl0YWJsZXMgPSBtd2QucXVlcnlTZWxlY3RvckFsbCgnW2NvbnRlbnRlZGl0YWJsZV0nKSwgbCA9IGVkaXRhYmxlcy5sZW5ndGgsIHggPSAwO1xuICAgICAgICAgICAgICAgIGZvciAoOyB4IDwgbDsgeCsrKSB7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGVkaXRhYmxlc1t4XSwgJ2luaGVyaXQnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcuJChcIi5lZGl0XCIpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy5vbi5ET01DaGFuZ2UodGhpcywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5xdWVyeVNlbGVjdG9yQWxsKCcqJykubGVuZ3RoID09PSAwICYmIG13LmxpdmVfZWRpdC5oYXNBYmlsaXR5VG9Ecm9wRWxlbWVudHNJbnNpZGUodGhpcykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLm1vZGlmeSh0aGlzLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICghbXcud3lzaXd5Zy5pc1NhZmVNb2RlKHRoaXMpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmlubmVySFRNTCA9ICc8cCBjbGFzcz1cImVsZW1lbnRcIj4nICsgdGhpcy5pbm5lckhUTUwgKyAnPC9wPic7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubm9ybWFsaXplQmFzZTY0SW1hZ2VzKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICB9LCBmYWxzZSwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykubW91c2VlbnRlcihmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5xdWVyeVNlbGVjdG9yQWxsKCcqJykubGVuZ3RoID09PSAwICYmIG13LmxpdmVfZWRpdC5oYXNBYmlsaXR5VG9Ecm9wRWxlbWVudHNJbnNpZGUodGhpcykpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubW9kaWZ5KHRoaXMsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCFtdy53eXNpd3lnLmlzU2FmZU1vZGUodGhpcykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gJzxwIGNsYXNzPVwiZWxlbWVudFwiPicgKyB0aGlzLmlubmVySFRNTCArICcmbmJzcDs8L3A+JztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBtdy4kKFwiLmVtcHR5LWVsZW1lbnQsIC51aS1yZXNpemFibGUtaGFuZGxlXCIpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZSh0aGlzLCBmYWxzZSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgbXcub24ubW9kdWxlUmVsb2FkKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5uY2V1aSgpO1xuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG1vZGlmeTogZnVuY3Rpb24gKGVsLCBjYWxsYmFjaykge1xuICAgICAgICB2YXIgY3VyciA9IG13LmFza3VzZXJ0b3N0YXk7XG4gICAgICAgIGlmICh0eXBlb2YgZWwgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgIGNhbGxiYWNrID0gZWw7XG4gICAgICAgICAgICBjYWxsYmFjay5jYWxsKCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBjYWxsYmFjay5jYWxsKGVsKTtcbiAgICAgICAgfVxuICAgICAgICBtdy5hc2t1c2VydG9zdGF5ID0gY3VycjtcbiAgICB9LFxuICAgIGZpeEVsZW1lbnRzOiBmdW5jdGlvbiAocGFyZW50KSB7XG4gICAgICAgIHZhciBhID0gcGFyZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIuZWxlbWVudFwiKSwgbCA9IGEubGVuZ3RoO1xuICAgICAgICBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIGlmIChhW2ldLmlubmVySFRNTCA9PT0gJycgfHwgYVtpXS5pbm5lckhUTUwucmVwbGFjZSgvXFxzKy9nLCAnJykgPT09ICcnKSB7XG4gICAgICAgICAgICAgICAgYVtpXS5pbm5lckhUTUwgPSAnJnp3ajsmbmJzcDsmendqOyc7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHJlbW92ZUVkaXRhYmxlOiBmdW5jdGlvbiAoc2tpcCkge1xuICAgICAgICBza2lwID0gc2tpcCB8fCBbXTtcbiAgICAgICAgaWYgKCFtdy5pcy5pZSkge1xuICAgICAgICAgICAgdmFyIGk9MCwgaTIsXG4gICAgICAgICAgICAgICAgYWxsID0gbXdkLmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2VkaXQnKSxcbiAgICAgICAgICAgICAgICBsZW4gPSBhbGwubGVuZ3RoO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsZW47IGkrKykge1xuICAgICAgICAgICAgICAgIGlmKHNraXAubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzaG91bGRTa2lwID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGFsbFtpXSwgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKGkyPTA7aTI8c2tpcC5sZW5ndGg7aTIrKyl7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihza2lwW2kyXSA9PT0gYWxsW2ldKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2hvdWxkU2tpcCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaWYoIXNob3VsZFNraXApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGFsbFtpXSwgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZShhbGxbaV0sIGZhbHNlKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13LiQoXCIuZWRpdCBbY29udGVudGVkaXRhYmxlPSd0cnVlJ10sIC5lZGl0XCIpLnJlbW92ZUF0dHIoJ2NvbnRlbnRlZGl0YWJsZScpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBfbGFzdENvcHk6IG51bGwsXG4gICAgaGFuZGxlQ29weUV2ZW50OiBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgdGhpcy5fbGFzdENvcHkgPSBldmVudC50YXJnZXQ7XG4gICAgfSxcbiAgICBjb250ZW50RWRpdGFibGVTcGxpdFR5cGVzOiBmdW5jdGlvbiAoZWwpIHtcblxuICAgIH0sXG4gICAgY29udGVudEVkaXRhYmxlOiBmdW5jdGlvbiAoZWwsIHN0YXRlKSB7XG4gICAgICAgIGlmICghZWwpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBpZihlbC5ub2RlVHlwZSAhPT0gMSl7XG4gICAgICAgICAgICBlbCA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihlbCk7XG4gICAgICAgICAgICBpZiAoIWVsKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGlmKHR5cGVvZiBzdGF0ZSA9PT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgcmV0dXJuIGVsLmNvbnRlbnRFZGl0YWJsZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoc3RhdGUpIHtcbiAgICAgICAgICAgIG13Lm9uLkRPTUNoYW5nZVBhdXNlID0gdHJ1ZTtcbiAgICAgICAgICAgIGlmICghZWwuX2hhbmRsZUNvcHkpIHtcbiAgICAgICAgICAgICAgICBlbC5faGFuZGxlQ29weSA9IHRydWU7XG4gICAgICAgICAgICAgICAgbXcuJChlbCkub24oJ2NvcHknLCBmdW5jdGlvbihldil7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaGFuZGxlQ29weUV2ZW50KGV2KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZihzdGF0ZSA9PT0gdHJ1ZSl7XG4gICAgICAgICAgICBzdGF0ZSA9ICd0cnVlJztcbiAgICAgICAgfSBlbHNlIGlmKHN0YXRlID09PSBmYWxzZSkge1xuICAgICAgICAgICAgc3RhdGUgPSAnZmFsc2UnO1xuICAgICAgICB9XG4gICAgICAgIGlmKHN0YXRlID09PSAndHJ1ZScpe1xuICAgICAgICAgICAgaWYobXcud3lzaXd5Zy5pc1NhZmVNb2RlKGVsKSl7XG4gICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgZWwgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXMoZWwsIFsnZWRpdCcsICdyZWd1bGFyLW1vZGUnXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHR5cGVvZihtdy5saXZlZWRpdCkgIT0gJ3VuZGVmaW5lZCcgJiYgbXcubGl2ZWVkaXQuZGF0YS5zZXQoJ21vdXNldXAnLCAnaXNJY29uJykpIHtcbiAgICAgICAgICAgIHN0YXRlID0gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgaWYoZWwgJiYgZWwuY29udGVudEVkaXRhYmxlICE9PSBzdGF0ZSkgeyAvLyBjaHJvbWUgc2V0dGVyIG5lZWRzIGEgY2hlY2tcblxuICAgICAgICAgICAgZWwuY29udGVudEVkaXRhYmxlID0gc3RhdGU7XG4gICAgICAgIH1cblxuICAgICAgICBtdy5vbi5ET01DaGFuZ2VQYXVzZSA9IGZhbHNlO1xuICAgIH0sXG5cbiAgICBwcmVwYXJlQ29udGVudEVkaXRhYmxlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13Lm9uKFwiRWRpdE1vdXNlRG93blwiLCBmdW5jdGlvbiAoZSwgZWwsIHRhcmdldCwgb3JpZ2luYWxFdmVudCkge1xuICAgICAgICAgICAgbXcuJCgnLnNhZmUtbW9kZScpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRoaXMsICdpbmhlcml0Jyk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgaWYgKCFtdy53eXNpd3lnLmlzU2FmZU1vZGUodGFyZ2V0KSkge1xuICAgICAgICAgICAgICAgIGlmICghbXcuaXMuaWUpIHsgLy9Ob24gSUUgYnJvd3NlclxuICAgICAgICAgICAgICAgICAgICB2YXIgb3JkZXJWYWxpZCA9IG13LnRvb2xzLnBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3Qob3JpZ2luYWxFdmVudC50YXJnZXQsIFsnZWRpdCcsICdtb2R1bGUnXSk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoJy5zYWZlLW1vZGUnKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRoaXMsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRhcmdldCwgb3JkZXJWYWxpZCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgeyAgIC8vIElFIGJyb3dzZXJcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5yZW1vdmVFZGl0YWJsZSgpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgY2xzID0gdGFyZ2V0LmNsYXNzTmFtZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNDbGFzcyhjbHMsICdlbXB0eS1lbGVtZW50JykgJiYgIW13LnRvb2xzLmhhc0NsYXNzKGNscywgJ3VpLXJlc2l6YWJsZS1oYW5kbGUnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZWwsICdtb2R1bGUnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRhcmdldCwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3ModGFyZ2V0LCBcIm1vZHVsZVwiKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAobXcuaXNEcmFnSXRlbSh0YXJnZXQpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNvbnRlbnRFZGl0YWJsZSh0YXJnZXQsIHRydWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHModGFyZ2V0LCBmdW5jdGlvbiAobG9vcCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtdy5pc0RyYWdJdGVtKHRoaXMpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHRhcmdldCwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmxvb3BbbG9vcF0gPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHZhciBmaXJzdEJsb2NrID0gdGFyZ2V0O1xuICAgICAgICAgICAgICAgIHZhciBibG9ja3MgPSBbJ3AnLCAnZGl2JywgJ2gxJywgJ2gyJywgJ2gzJywgJ2g0JywgJ2g1JywgJ2g2JywgJ2hlYWRlcicsICdzZWN0aW9uJywgJ2Zvb3RlcicsICd1bCcsICdvbCddO1xuICAgICAgICAgICAgICAgIHZhciBibG9ja3NDbGFzcyA9IFsnc2FmZS1lbGVtZW50J107XG4gICAgICAgICAgICAgICAgdmFyIHBvID0gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChmaXJzdEJsb2NrLCBbJ2VkaXQnLCAnbW9kdWxlJ10pO1xuXG4gICAgICAgICAgICAgICAgaWYgKHBvKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChibG9ja3MuaW5kZXhPZihmaXJzdEJsb2NrLm5vZGVOYW1lLnRvTG9jYWxlTG93ZXJDYXNlKCkpID09PSAtMSAmJiAhbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZmlyc3RCbG9jaywgYmxvY2tzQ2xhc3MpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgY2xzID0gW107XG4gICAgICAgICAgICAgICAgICAgICAgICBibG9ja3NDbGFzcy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY2xzLnB1c2goJy4nICsgaXRlbSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNscyA9IGNscy5jb25jYXQoYmxvY2tzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZpcnN0QmxvY2sgPSBtdy50b29scy5maXJzdE1hdGNoZXNPbk5vZGVPclBhcmVudChmaXJzdEJsb2NrLCBjbHMpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICBtdy4kKFwiW2NvbnRlbnRlZGl0YWJsZT0ndHJ1ZSddXCIpLm5vdChmaXJzdEJsb2NrKS5hdHRyKFwiY29udGVudGVkaXRhYmxlXCIsIFwiZmFsc2VcIik7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKGZpcnN0QmxvY2ssIHRydWUpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBoaWRlX2RyYWdfaGFuZGxlczogZnVuY3Rpb24gKCkge1xuICAgICAgICBtdy4kKFwiLm13LXd5c3d5Zy1wbHVzLWVsZW1lbnRcIikuaGlkZSgpO1xuICAgIH0sXG4gICAgc2hvd19kcmFnX2hhbmRsZXM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbXcuJChcIi5tdy13eXN3eWctcGx1cy1lbGVtZW50XCIpLnNob3coKTtcbiAgICB9LFxuXG4gICAgX2V4dGVybmFsOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBleHRlcm5hbCA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgZXh0ZXJuYWwuY2xhc3NOYW1lID0gJ3d5c2l3eWdfZXh0ZXJuYWwnO1xuICAgICAgICBtd2QuYm9keS5hcHBlbmRDaGlsZChleHRlcm5hbCk7XG4gICAgICAgIHJldHVybiBleHRlcm5hbDtcbiAgICB9LFxuICAgIGlzU2VsZWN0aW9uRWRpdGFibGU6IGZ1bmN0aW9uIChzZWwpIHtcbiAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgIHZhciBub2RlID0gKHNlbCB8fCB3aW5kb3cuZ2V0U2VsZWN0aW9uKCkpLmZvY3VzTm9kZTtcbiAgICAgICAgICAgIGlmIChub2RlID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKG5vZGUubm9kZVR5cGUgPT09IDEpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbm9kZS5pc0NvbnRlbnRFZGl0YWJsZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHJldHVybiBub2RlLnBhcmVudE5vZGUuaXNDb250ZW50RWRpdGFibGU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgY2F0Y2ggKGUpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgZXhlY0NvbW1hbmRGaWx0ZXI6IGZ1bmN0aW9uIChhLCBiLCBjKSB7XG4gICAgICAgIHZhciBhcnIgPSBbJ2p1c3RpZnlDZW50ZXInLCAnanVzdGlmeUZ1bGwnLCAnanVzdGlmeUxlZnQnLCAnanVzdGlmeVJpZ2h0J107XG4gICAgICAgIHZhciBhbGlnbjtcbiAgICAgICAgdmFyIG5vZGUgPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCkuZm9jdXNOb2RlO1xuICAgICAgICB2YXIgZWxlbWVudE5vZGUgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIobm9kZSk7XG4gICAgICAgIGlmIChtdy53eXNpd3lnLmlzU2FmZU1vZGUoZWxlbWVudE5vZGUpICYmIGFyci5pbmRleE9mKGEpICE9PSAtMSkge1xuICAgICAgICAgICAgYWxpZ24gPSBhLnNwbGl0KCdqdXN0aWZ5JylbMV0udG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIGlmIChhbGlnbiA9PT0gJ2Z1bGwnKSB7XG4gICAgICAgICAgICAgICAgYWxpZ24gPSAnanVzdGlmeSc7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbGVtZW50Tm9kZS5zdHlsZS50ZXh0QWxpZ24gPSBhbGlnbjtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKGVsZW1lbnROb2RlKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAobXcuaXMuZmlyZWZveCAmJiBhcnIuaW5kZXhPZihhKSAhPT0gLTEpIHtcblxuICAgICAgICAgICAgaWYgKGVsZW1lbnROb2RlLm5vZGVOYW1lID09PSAnUCcpIHtcbiAgICAgICAgICAgICAgICBhbGlnbiA9IGEuc3BsaXQoJ2p1c3RpZnknKVsxXS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICAgICAgICAgIGlmIChhbGlnbiA9PT0gJ2Z1bGwnKSB7XG4gICAgICAgICAgICAgICAgICAgIGFsaWduID0gJ2p1c3RpZnknO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbGVtZW50Tm9kZS5zdHlsZS50ZXh0QWxpZ24gPSBhbGlnbjtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShlbGVtZW50Tm9kZSlcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRydWVcbiAgICB9LFxuICAgIGV4ZWNDb21tYW5kOiBmdW5jdGlvbiAoYSwgYiwgYykge1xuICAgICAgICBkb2N1bWVudC5leGVjQ29tbWFuZCgnc3R5bGVXaXRoQ3NzJywgJ2ZhbHNlJywgZmFsc2UpO1xuICAgICAgICB2YXIgc2VsID0gZ2V0U2VsZWN0aW9uKCk7XG5cbiAgICAgICAgdmFyIG5vZGUgPSBzZWwuZm9jdXNOb2RlLCBlbGVtZW50Tm9kZTtcbiAgICAgICAgaWYgKG5vZGUpIHtcbiAgICAgICAgICAgIGVsZW1lbnROb2RlID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKG5vZGUpO1xuICAgICAgICB9XG5cbiAgICAgICAgdHJ5IHsgIC8vIDB4ODAwMDQwMDVcbiAgICAgICAgICAgIGlmIChkb2N1bWVudC5xdWVyeUNvbW1hbmRTdXBwb3J0ZWQoYSkgJiYgbXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkpIHtcbiAgICAgICAgICAgICAgICBiID0gYiB8fCBmYWxzZTtcbiAgICAgICAgICAgICAgICBjID0gYyB8fCBmYWxzZTtcblxuICAgICAgICAgICAgICAgIHZhciBiZWZvcmUgPSBtdy4kKG5vZGUpLmNsb25lKClbMF07XG4gICAgICAgICAgICAgICAgaWYgKHNlbC5yYW5nZUNvdW50ID4gMCAmJiBtdy53eXNpd3lnLmV4ZWNDb21tYW5kRmlsdGVyKGEsIGIsIGMpKSB7XG4gICAgICAgICAgICAgICAgICAgIG13ZC5leGVjQ29tbWFuZChhLCBiLCBjKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAobm9kZSAhPT0gbnVsbCAmJiBtdy5sb2FkZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2Uobm9kZSk7XG4gICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ2V4ZWNDb21tYW5kJywgW2EsIG5vZGUsIGJlZm9yZSwgZWxlbWVudE5vZGVdKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgY2F0Y2ggKGUpIHtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgc2VsZWN0aW9uOiAnJyxcbiAgICBfZG86IGZ1bmN0aW9uICh3aGF0KSB7XG4gICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQod2hhdCk7XG4gICAgICAgIGlmICh0eXBlb2YgbXcud3lzaXd5Zy5hY3Rpb25bd2hhdF0gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuYWN0aW9uW3doYXRdKCk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHNhdmVfc2VsZWN0ZWRfZWxlbWVudDogZnVuY3Rpb24gKCkge1xuICAgICAgICBtdy4kKFwiI213LXRleHQtZWRpdG9yXCIpLmFkZENsYXNzKFwiZWRpdG9yX2hvdmVyXCIpO1xuICAgIH0sXG4gICAgZGVzZWxlY3Rfc2VsZWN0ZWRfZWxlbWVudDogZnVuY3Rpb24gKCkge1xuICAgICAgICBtdy4kKFwiI213LXRleHQtZWRpdG9yXCIpLnJlbW92ZUNsYXNzKFwiZWRpdG9yX2hvdmVyXCIpO1xuICAgIH0sXG4gICAgbmNldWk6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKG13LnNldHRpbmdzLmxpdmVFZGl0KSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmV4ZWNDb21tYW5kKCdlbmFibGVPYmplY3RSZXNpemluZycsIGZhbHNlLCAnZmFsc2UnKTtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoJzJELVBvc2l0aW9uJywgZmFsc2UsIGZhbHNlKTtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoXCJlbmFibGVJbmxpbmVUYWJsZUVkaXRpbmdcIiwgbnVsbCwgZmFsc2UpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBfcGFzdGVNYW5hZ2VyOiB1bmRlZmluZWQsXG4gICAgcGFzdGVNYW5hZ2VyOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICBodG1sID0gbXcud3lzaXd5Zy5jbGVhbl93b3JkKGh0bWwpXG4gICAgICAgIG13Lnd5c2l3eWcuX3Bhc3RlTWFuYWdlciA9IHRoaXMuX3Bhc3RlTWFuYWdlciB8fCBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgbXcud3lzaXd5Zy5fcGFzdGVNYW5hZ2VyLmlubmVySFRNTCA9IGh0bWw7XG4gICAgICAgIG13LiQoJyonLCBtdy53eXNpd3lnLl9wYXN0ZU1hbmFnZXIpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICAgIHJldHVybiBtdy53eXNpd3lnLl9wYXN0ZU1hbmFnZXIuaW5uZXJIVE1MO1xuICAgIH0sXG4gICAgY2xlYW5FeGNlbDogZnVuY3Rpb24gKGNsaXBib2FyZCkge1xuICAgICAgICB2YXIgaHRtbCA9IGNsaXBib2FyZC5nZXREYXRhKCd0ZXh0L2h0bWwnKTtcbiAgICAgICAgdmFyIHBhcnNlciA9IG13LnRvb2xzLnBhcnNlSHRtbChodG1sKS5ib2R5O1xuICAgICAgICBtdy4kKFwiW3N0eWxlKj0nbXNvLXNwYWNlcnVuJ11cIiwgcGFyc2VyKS5yZW1vdmUoKVxuICAgICAgICBtdy4kKFwic3R5bGVcIiwgcGFyc2VyKS5yZW1vdmUoKVxuICAgICAgICBtdy4kKCd0YWJsZScsIHBhcnNlcilcbiAgICAgICAgICAgIC53aWR0aCgnMTAwJScpXG4gICAgICAgICAgICAuYWRkQ2xhc3MoJ213LXd5c2l3eWctdGFibGUnKVxuICAgICAgICAgICAgLnJlbW92ZUF0dHIoJ3dpZHRoJyk7XG4gICAgICAgIHJldHVybiBwYXJzZXIuaW5uZXJIVE1MO1xuICAgIH0sXG4gICAgcGFzdGVkRnJvbUV4Y2VsOiBmdW5jdGlvbiAoY2xpcGJvYXJkKSB7XG4gICAgICAgIHZhciBodG1sID0gY2xpcGJvYXJkLmdldERhdGEoJ3RleHQvaHRtbCcpO1xuICAgICAgICByZXR1cm4gaHRtbC5pbmRleE9mKCdQcm9nSWQgY29udGVudD1FeGNlbC5TaGVldCcpICE9PSAtMVxuICAgIH0sXG4gICAgYXJlU2FtZUxpa2U6IGZ1bmN0aW9uIChlbDEsIGVsMikge1xuICAgICAgICBpZiAoIWVsMSB8fCAhZWwyKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIGlmIChlbDEubm9kZVR5cGUgIT09IGVsMi5ub2RlVHlwZSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICBpZiAoISFlbDEuY2xhc3NOYW1lLnRyaW0oKSB8fCAhIWVsMi5jbGFzc05hbWUudHJpbSgpKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgY3NzMSA9IChlbDEuZ2V0QXR0cmlidXRlKCdzdHlsZScpIHx8ICcnKS5yZXBsYWNlKC9cXHMvZywgJycpO1xuICAgICAgICB2YXIgY3NzMiA9IChlbDIuZ2V0QXR0cmlidXRlKCdzdHlsZScpIHx8ICcnKS5yZXBsYWNlKC9cXHMvZywgJycpO1xuXG4gICAgICAgIGlmIChjc3MxID09PSBjc3MyICYmIGVsMS5ub2RlTmFtZSA9PT0gZWwyLm5vZGVOYW1lKSB7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIGNsZWFuVW53YW50ZWRUYWdzOiBmdW5jdGlvbiAoYm9keSkge1xuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICBtdy4kKCcqJywgYm9keSkuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAodGhpcy5ub2RlTmFtZSAhPT0gJ0EnICYmIG13LmVhLmhlbHBlcnMuaXNJbmxpbmVMZXZlbCh0aGlzKSAmJiAodGhpcy5jbGFzc05hbWUudHJpbSAmJiAhdGhpcy5jbGFzc05hbWUudHJpbSgpKSkge1xuICAgICAgICAgICAgICAgIGlmIChzY29wZS5hcmVTYW1lTGlrZSh0aGlzLCB0aGlzLm5leHRFbGVtZW50U2libGluZykgJiYgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcgPT09IHRoaXMubmV4dFNpYmxpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRoaXMubmV4dFNpYmxpbmcgIT09IHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmFwcGVuZENoaWxkKHRoaXMubmV4dFNpYmxpbmcpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdGhpcy5pbm5lckhUTUwgKyB0aGlzLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUw7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmlubmVySFRNTCA9ICcnO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc05hbWUgPSAnbXctc2tpcC1hbmQtcmVtb3ZlJztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKCcubXctc2tpcC1hbmQtcmVtb3ZlJywgYm9keSkucmVtb3ZlKCk7XG4gICAgICAgIHJldHVybiBib2R5O1xuICAgIH0sXG4gICAgZG9Mb2NhbFBhc3RlOiBmdW5jdGlvbiAoY2xpcGJvYXJkKSB7XG4gICAgICAgIHZhciBodG1sID0gY2xpcGJvYXJkLmdldERhdGEoJ3RleHQvaHRtbCcpO1xuICAgICAgICB2YXIgcGFyc2VyID0gbXcudG9vbHMucGFyc2VIdG1sKGh0bWwpLmJvZHk7XG5cbiAgICAgICAgbXcuJCgnW3N0eWxlXScsIHBhcnNlcikucmVtb3ZlQXR0cignc3R5bGUnKTtcbiAgICAgICAgbXcuJCgnW2lkXScsIHBhcnNlcikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmlkID0gJ2RscC1pdGVtLScgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgfSk7XG4gICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwocGFyc2VyLmlubmVySFRNTCk7XG4gICAgfSxcbiAgICBpc0xvY2FsUGFzdGU6IGZ1bmN0aW9uIChjbGlwYm9hcmQpIHtcbiAgICAgICAgdmFyIGh0bWwgPSBjbGlwYm9hcmQuZ2V0RGF0YSgndGV4dC9odG1sJyk7XG4gICAgICAgIHZhciBwYXJzZXIgPSBtdy50b29scy5wYXJzZUh0bWwoaHRtbCkuYm9keTtcbiAgICAgICAgcmV0dXJuICh0aGlzLl9sYXN0Q29weSAmJiB0aGlzLl9sYXN0Q29weS5pbm5lckhUTUwgJiYgdGhpcy5fbGFzdENvcHkuaW5uZXJIVE1MLmNvbnRhaW5zKGh0bWwpKSB8fCBwYXJzZXIucXVlcnlTZWxlY3RvcignLm1vZHVsZSwuZWxlbWVudCwuZWRpdCcpICE9PSBudWxsO1xuICAgIH0sXG4gICAgcGFzdGU6IGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIHZhciBodG1sLCBjbGlwYm9hcmQ7XG5cbiAgICAgICAgaWYgKCEhZS5vcmlnaW5hbEV2ZW50KSB7XG4gICAgICAgICAgICBjbGlwYm9hcmQgPSBlLm9yaWdpbmFsRXZlbnQuY2xpcGJvYXJkRGF0YSB8fCBtd3cuY2xpcGJvYXJkRGF0YTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGNsaXBib2FyZCA9IGUuY2xpcGJvYXJkRGF0YSB8fCBtd3cuY2xpcGJvYXJkRGF0YTtcbiAgICAgICAgfVxuICAgICAgICBpZiAobXcud3lzaXd5Zy5pc1NhZmVNb2RlKGUudGFyZ2V0KSkge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBjbGlwYm9hcmQgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiBjbGlwYm9hcmQuZ2V0RGF0YSA9PT0gJ2Z1bmN0aW9uJyAmJiBtdy53eXNpd3lnLmVkaXRhYmxlKGUudGFyZ2V0KSkge1xuICAgICAgICAgICAgICAgIHZhciB0ZXh0ID0gY2xpcGJvYXJkLmdldERhdGEoJ3RleHQnKTtcbiAgICAgICAgICAgICAgICBpZih0ZXh0KSB7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwodGV4dCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gJyc7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG13Lnd5c2l3eWcuaXNMb2NhbFBhc3RlKGNsaXBib2FyZCkpIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuZG9Mb2NhbFBhc3RlKGNsaXBib2FyZCk7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICByZXR1cm4gJyc7XG4gICAgICAgIH1cblxuXG4gICAgICAgIGlmIChtdy53eXNpd3lnLnBhc3RlZEZyb21FeGNlbChjbGlwYm9hcmQpKSB7XG4gICAgICAgICAgICBodG1sID0gbXcud3lzaXd5Zy5jbGVhbkV4Y2VsKGNsaXBib2FyZClcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwoaHRtbCk7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICByZXR1cm4gJyc7XG4gICAgICAgIH1cblxuXG4gICAgICAgIGlmIChjbGlwYm9hcmQuZmlsZXMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgdmFyIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBjbGlwYm9hcmQuZmlsZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IGNsaXBib2FyZC5maWxlc1tpXTtcbiAgICAgICAgICAgICAgICBpZiAoaXRlbS50eXBlLmluZGV4T2YoJ2ltYWdlLycpICE9IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByZWFkZXIgPSBuZXcgRmlsZVJlYWRlcigpO1xuICAgICAgICAgICAgICAgICAgICByZWFkZXIub25sb2FkID0gZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwoJzxpbWcgc3JjPVwiJyArIChlLnRhcmdldC5yZXN1bHQpICsgJ1wiPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5ub3JtYWxpemVCYXNlNjRJbWFnZXMoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZWFkZXIucmVhZEFzRGF0YVVSTChpdGVtKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgY2xpcGJvYXJkICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgY2xpcGJvYXJkLmdldERhdGEgPT09ICdmdW5jdGlvbicgJiYgbXcud3lzaXd5Zy5lZGl0YWJsZShlLnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBpZiAoIW13LmlzLmllKSB7XG4gICAgICAgICAgICAgICAgICAgIGh0bWwgPSBjbGlwYm9hcmQuZ2V0RGF0YSgndGV4dC9odG1sJyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0ZXh0ID0gY2xpcGJvYXJkLmdldERhdGEoJ3RleHQnKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGlzUGxhaW5UZXh0ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIGlmICghaHRtbCAmJiB0ZXh0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpc1BsYWluVGV4dCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoL1xcclxcbi8udGVzdCh0ZXh0KSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciB3cmFwcGVyID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGdldFNlbGVjdGlvbigpLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd3JhcHBlciA9IG13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KHdyYXBwZXIsIFsnLmVsZW1lbnQnLCAncCcsICdkaXYnLCAnLmVkaXQnXSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGFnID0gd3JhcHBlci5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGh0bWwgPSAnPCcgKyB0YWcgKyAnIGlkPVwiZWxlbWVudF8nICsgbXcucmFuZG9tKCkgKyAnXCI+JyArIHRleHQucmVwbGFjZSgvXFxyXFxuL2csIFwiPGJyPlwiKSArICc8LycgKyB0YWcgKyAnPic7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBodG1sID0gY2xpcGJvYXJkLmdldERhdGEoJ3RleHQnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKCEhaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICBpZiAobXcuZm9ybSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGlzX2xpbmsgPSBtdy5mb3JtLnZhbGlkYXRlLnVybChodG1sKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChpc19saW5rKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCA9IFwiPGEgaHJlZj0nXCIgKyBodG1sICsgXCInPlwiICsgaHRtbCArIFwiPC9hPlwiO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaHRtbCA9IG13Lnd5c2l3eWcucGFzdGVNYW5hZ2VyKGh0bWwpO1xuXG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuaW5zZXJ0X2h0bWwoaHRtbCk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChlLnRhcmdldC5xdWVyeVNlbGVjdG9yKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKGUudGFyZ2V0LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tzdHlsZSo9XCJvdXRsaW5lXCJdJykpLmNzcyh7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3V0bGluZTogJ25vbmUnXG4gICAgICAgICAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgaGFzQ29udGVudEZyb21Xb3JkOiBmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICBpZiAobm9kZS5nZXRFbGVtZW50c0J5VGFnTmFtZShcIm86cFwiKS5sZW5ndGggPiAwIHx8XG4gICAgICAgICAgICBub2RlLmdldEVsZW1lbnRzQnlUYWdOYW1lKFwidjpzaGFwZXR5cGVcIikubGVuZ3RoID4gMCB8fFxuICAgICAgICAgICAgbm9kZS5nZXRFbGVtZW50c0J5VGFnTmFtZShcInY6cGF0aFwiKS5sZW5ndGggPiAwIHx8XG4gICAgICAgICAgICBub2RlLnF1ZXJ5U2VsZWN0b3IoJy5Nc29Ob3JtYWwnKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgcHJlcGFyZTogZnVuY3Rpb24gKCkge1xuICAgICAgICBtdy53eXNpd3lnLmV4dGVybmFsID0gbXcud3lzaXd5Zy5fZXh0ZXJuYWwoKTtcbiAgICAgICAgbXcuJChcIiNsaXZlZWRpdF93eXNpd3lnXCIpLm9uKFwibW91c2Vkb3duIG1vdXNldXAgY2xpY2tcIiwgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB9KTtcbiAgICAgICAgdmFyIGl0ZW1zID0gbXcuJChcIi5lbGVtZW50XCIpLm5vdChcIi5tb2R1bGVcIik7XG4gICAgICAgIG13LiQoXCIubXdfZWRpdG9yXCIpLmhvdmVyKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LiQodGhpcykuYWRkQ2xhc3MoXCJlZGl0b3JfaG92ZXJcIilcbiAgICAgICAgfSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcyhcImVkaXRvcl9ob3ZlclwiKVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGRlc2VsZWN0OiBmdW5jdGlvbiAocykge1xuICAgICAgICB2YXIgcyA9IHMgfHwgd2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgICAgICBzLmVtcHR5ID8gcy5lbXB0eSgpIDogcy5yZW1vdmVBbGxSYW5nZXMoKTtcbiAgICB9LFxuICAgIGVkaXRvcnNfZGlzYWJsZWQ6IGZhbHNlLFxuICAgIGVuYWJsZUVkaXRvcnM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbXcuJChcIi5td19lZGl0b3IsICNtd19zbWFsbF9lZGl0b3JcIikucmVtb3ZlQ2xhc3MoXCJtdy1lZGl0b3ItZGlzYWJsZWRcIik7XG4gICAgICAgIG13Lnd5c2l3eWcuZWRpdG9yc19kaXNhYmxlZCA9IGZhbHNlO1xuICAgIH0sXG4gICAgZGlzYWJsZUVkaXRvcnM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLyogIG13LiQoXCIubXdfZWRpdG9yLCAjbXdfc21hbGxfZWRpdG9yXCIpLmFkZENsYXNzKFwibXctZWRpdG9yLWRpc2FibGVkXCIpO1xuICAgICAgICAgbXcud3lzaXd5Zy5lZGl0b3JzX2Rpc2FibGVkID0gZmFsc2U7ICAgKi9cbiAgICB9LFxuICAgIGNoZWNrRm9yVGV4dE9ubHlFbGVtZW50czogZnVuY3Rpb24gKGUsIG1ldGhvZCkge1xuICAgICAgICB2YXIgZSA9IGUgfHwgZmFsc2U7XG4gICAgICAgIHZhciBtZXRob2QgPSBtZXRob2QgfHwgJ3NlbGVjdGlvbic7XG4gICAgICAgIGlmIChtZXRob2QgPT09ICdzZWxlY3Rpb24nKSB7XG4gICAgICAgICAgICB2YXIgc2VsID0gbXd3LmdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgdmFyIGYgPSBzZWwuZm9jdXNOb2RlO1xuICAgICAgICAgICAgZiA9IG13LnRvb2xzLmhhc0NsYXNzKGYsICdlZGl0JykgPyBmIDogbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3MoZiwgJ2VkaXQnKTtcbiAgICAgICAgICAgIGlmIChmLmF0dHJpYnV0ZXMgIT0gdW5kZWZpbmVkICYmICEhZi5hdHRyaWJ1dGVzLmZpZWxkICYmIGYuYXR0cmlidXRlcy5maWVsZC5ub2RlVmFsdWUgPT0gJ3RpdGxlJykge1xuICAgICAgICAgICAgICAgIGlmICghIWUpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuZXZlbnQuY2FuY2VsKGUsIHRydWUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG1lcmdlOiB7XG4gICAgICAgIC8qIEV4ZWN1dGVzIG9uIGJhY2tzcGFjZSBvciBkZWxldGUgKi9cbiAgICAgICAgaXNNZXJnZWFibGU6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgaWYgKCFlbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgaWYgKGVsLm5vZGVUeXBlID09PSAzKSByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIHZhciBpcyA9IGZhbHNlO1xuICAgICAgICAgICAgdmFyIGNzcyA9ICBnZXRDb21wdXRlZFN0eWxlKGVsKVxuXG4gICAgICAgICAgICB2YXIgZGlzcGxheSA9IGNzcy5nZXRQcm9wZXJ0eVZhbHVlKCdkaXNwbGF5Jyk7XG5cbiAgICAgICAgICAgIHZhciBwb3NpdGlvbiA9IGNzcy5nZXRQcm9wZXJ0eVZhbHVlKCdwb3NpdGlvbicpO1xuICAgICAgICAgICAgdmFyIGlzSW5saW5lID0gZGlzcGxheSA9PT0gJ2lubGluZSc7XG4gICAgICAgICAgICBpZiAoaXNJbmxpbmUpIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgdmFyIG1lcmdlYWJsZXMgPSBbJ3AnLCAnLmVsZW1lbnQnLCAnZGl2Om5vdChbY2xhc3NdKScsICdoMScsICdoMicsICdoMycsICdoNCcsICdoNScsICdoNiddO1xuICAgICAgICAgICAgbWVyZ2VhYmxlcy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgaWYgKGVsLm1hdGNoZXMoaXRlbSkpIHtcbiAgICAgICAgICAgICAgICAgICAgaXMgPSB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBpZiAoaXMpIHtcbiAgICAgICAgICAgICAgICBpZiAoZWwucXVlcnlTZWxlY3RvcignLm1vZHVsZScpICE9PSBudWxsIHx8IG13LnRvb2xzLmhhc0NsYXNzKGVsLCAnbW9kdWxlJykpIHtcbiAgICAgICAgICAgICAgICAgICAgaXMgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gaXM7XG4gICAgICAgIH0sXG4gICAgICAgIG1hbmFnZUJyZWFrYWJsZXM6IGZ1bmN0aW9uIChjdXJyLCBuZXh0LCBkaXIsIGV2ZW50KSB7XG4gICAgICAgICAgICB2YXIgaXNub25icmVha2FibGUgPSBtdy53eXNpd3lnLm1lcmdlLmlzSW5Ob25icmVha2FibGUoY3VyciwgZGlyKTtcbiAgICAgICAgICAgIGlmIChpc25vbmJyZWFrYWJsZSkge1xuICAgICAgICAgICAgICAgIHZhciBjb250cyA9IGdldFNlbGVjdGlvbigpLmdldFJhbmdlQXQoMCk7XG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgICAgIGlmIChuZXh0ICE9PSBudWxsKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKG5leHQubm9kZVR5cGUgPT09IDMgJiYgL1xccnxcXG4vLmV4ZWMobmV4dC5ub2RlVmFsdWUpICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKGRpciA9PSAnbmV4dCcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY3Vyc29yVG9FbGVtZW50KG5leHQpXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmN1cnNvclRvRWxlbWVudChuZXh0LCAnZW5kJylcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgaXNJbk5vbmJyZWFrYWJsZTogZnVuY3Rpb24gKGVsLCBkaXIpIHtcbiAgICAgICAgICAgIHZhciBhYnNOZXh0ID0gbXcud3lzaXd5Zy5tZXJnZS5maW5kTmV4dE5lYXJlc3QoZWwsIGRpcik7XG5cbiAgICAgICAgICAgIGlmIChhYnNOZXh0Lm5vZGVUeXBlID09PSAzICYmIC9cXHJ8XFxuLy5leGVjKGFic05leHQubm9kZVZhbHVlKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIGFic05leHQgPSBtdy53eXNpd3lnLm1lcmdlLmZpbmROZXh0TmVhcmVzdChlbCwgZGlyLCB0cnVlKVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoYWJzTmV4dC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNBbnlPZkNsYXNzZXMoYWJzTmV4dCwgWydub2Ryb3AnLCAnYWxsb3ctZHJvcCddKSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChhYnNOZXh0LnF1ZXJ5U2VsZWN0b3IoJy5ub2Ryb3AnLCAnLmFsbG93LWRyb3AnKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcubWVyZ2UuYWx3YXlzTWVyZ2VhYmxlKGFic05leHQpICYmIChtdy53eXNpd3lnLm1lcmdlLmFsd2F5c01lcmdlYWJsZShhYnNOZXh0LmZpcnN0RWxlbWVudENoaWxkKSB8fCAhYWJzTmV4dC5maXJzdEVsZW1lbnRDaGlsZCkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoZWwudGV4dENvbnRlbnQgPT0gJycpIHtcblxuICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0TmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGFic05leHQsIGRpcik7XG4gICAgICAgICAgICAgICAgaWYgKGFic05leHQubm9kZVR5cGUgPT0gMyAmJiAvXFxyfFxcbi8uZXhlYyhhYnNOZXh0Lm5vZGVWYWx1ZSkgIT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZUNsYXNzKGFic05leHROZXh0KVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKGVsLm5vZGVUeXBlID09PSAxICYmICEhZWwudGV4dENvbnRlbnQudHJpbSgpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGVsLm5leHRTaWJsaW5nID09PSBudWxsICYmIGVsLm5vZGVUeXBlID09PSAzICYmIGRpciA9PSAnbmV4dCcpIHtcbiAgICAgICAgICAgICAgICB2YXIgYWJzTmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCBkaXIpO1xuICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0TmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGFic05leHQsIGRpcik7XG4gICAgICAgICAgICAgICAgaWYgKC9cXHJ8XFxuLy5leGVjKGFic05leHQubm9kZVZhbHVlKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbXcud3lzaXd5Zy5tZXJnZS5pc0luTm9uYnJlYWthYmxlQ2xhc3MoYWJzTmV4dE5leHQpXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgaWYgKGFic05leHROZXh0Lm5vZGVUeXBlID09PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBtdy53eXNpd3lnLm1lcmdlLmlzSW5Ob25icmVha2FibGVDbGFzcyhhYnNOZXh0TmV4dCkgfHwgbXcud3lzaXd5Zy5tZXJnZS5pc0luTm9uYnJlYWthYmxlQ2xhc3MoYWJzTmV4dE5leHQuZmlyc3RDaGlsZCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKGFic05leHROZXh0Lm5vZGVUeXBlID09PSAzKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoZWwucHJldmlvdXNTaWJsaW5nID09PSBudWxsICYmIGVsLm5vZGVUeXBlID09PSAzICYmIGRpciA9PSAncHJldicpIHtcbiAgICAgICAgICAgICAgICB2YXIgYWJzTmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCAncHJldicpO1xuICAgICAgICAgICAgICAgIHZhciBhYnNOZXh0TmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGFic05leHQsICdwcmV2Jyk7XG4gICAgICAgICAgICAgICAgaWYgKGFic05leHROZXh0Lm5vZGVUeXBlID09PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBtdy53eXNpd3lnLm1lcmdlLmlzSW5Ob25icmVha2FibGVDbGFzcyhhYnNOZXh0TmV4dCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKGFic05leHROZXh0Lm5vZGVUeXBlID09PSAzKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGVsKVxuXG4gICAgICAgICAgICB2YXIgaXMgPSBtdy53eXNpd3lnLm1lcmdlLmlzSW5Ob25icmVha2FibGVDbGFzcyhlbClcbiAgICAgICAgICAgIHJldHVybiBpcztcblxuICAgICAgICB9LFxuICAgICAgICBpc0luTm9uYnJlYWthYmxlQ2xhc3M6IGZ1bmN0aW9uIChlbCwgZGlyKSB7XG4gICAgICAgICAgICB2YXIgYWJzTmV4dCA9IG13Lnd5c2l3eWcubWVyZ2UuZmluZE5leHROZWFyZXN0KGVsLCBkaXIpO1xuXG4gICAgICAgICAgICBpZiAoZWwubm9kZVR5cGUgPT0gMyAmJiAvXFxyfFxcbi8uZXhlYyhhYnNOZXh0Lm5vZGVWYWx1ZSkgPT09IG51bGwpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIGVsID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGVsKVxuICAgICAgICAgICAgdmFyIGNsYXNzZXMgPSBbJ3VuYnJlYWthYmxlJywgJypjb2wnLCAnKnJvdycsICcqYnRuJywgJyppY29uJywgJ21vZHVsZScsICdlZGl0J107XG4gICAgICAgICAgICB2YXIgaXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGNsYXNzZXMuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICAgICAgICAgIGlmIChpdGVtLmluZGV4T2YoJyonKSA9PT0gMCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IGl0ZW0uc3BsaXQoJyonKVsxXTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGVsLmNsYXNzTmFtZS5pbmRleE9mKGl0ZW0pICE9PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaXMgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHMoZWwsIGZ1bmN0aW9uIChsb29wKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHRoaXMuY2xhc3NOYW1lLmluZGV4T2YoaXRlbSkgIT09IC0xICYmICF0aGlzLmNvbnRhaW5zKGVsKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpcyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLnN0b3BMb29wKGxvb3ApO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpcyA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy50b29scy5zdG9wTG9vcChsb29wKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZWwsIGl0ZW0pIHx8IG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZWwsIGl0ZW0pKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpcyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBpcztcbiAgICAgICAgfSxcbiAgICAgICAgZ2V0TmV4dDogZnVuY3Rpb24gKGN1cnIpIHtcbiAgICAgICAgICAgIHZhciBuZXh0ID0gY3Vyci5uZXh0U2libGluZztcbiAgICAgICAgICAgIHdoaWxlIChjdXJyICE9PSBudWxsICYmIGN1cnIubmV4dFNpYmxpbmcgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIG5leHQgPSBjdXJyLm5leHRTaWJsaW5nO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIG5leHQ7XG4gICAgICAgIH0sXG4gICAgICAgIGdldFByZXY6IGZ1bmN0aW9uIChjdXJyKSB7XG4gICAgICAgICAgICB2YXIgbmV4dCA9IGN1cnIucHJldmlvdXNTaWJsaW5nO1xuICAgICAgICAgICAgd2hpbGUgKGN1cnIgIT09IG51bGwgJiYgY3Vyci5wcmV2aW91c1NpYmxpbmcgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgIG5leHQgPSBjdXJyLnByZXZpb3VzU2libGluZztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBuZXh0O1xuICAgICAgICB9LFxuICAgICAgICBmaW5kTmV4dE5lYXJlc3Q6IGZ1bmN0aW9uIChlbCwgZGlyLCBzZWFyY2hFbGVtZW50KSB7XG4gICAgICAgICAgICBzZWFyY2hFbGVtZW50ID0gdHlwZW9mIHNlYXJjaEVsZW1lbnQgPT09ICd1bmRlZmluZWQnID8gZmFsc2UgOiB0cnVlO1xuICAgICAgICAgICAgaWYgKGRpciA9PSAnbmV4dCcpIHtcbiAgICAgICAgICAgICAgICB2YXIgZG9zZWFyY2ggPSBzZWFyY2hFbGVtZW50ID8gJ25leHRFbGVtZW50U2libGluZycgOiAnbmV4dFNpYmxpbmcnXG4gICAgICAgICAgICAgICAgdmFyIG5leHQgPSBlbFtkb3NlYXJjaF07XG4gICAgICAgICAgICAgICAgaWYgKG5leHQgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgd2hpbGUgKGVsW2Rvc2VhcmNoXSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV4dCA9IGVsW2Rvc2VhcmNoXTtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgdmFyIGRvc2VhcmNoID0gc2VhcmNoRWxlbWVudCA/ICdwcmV2aW91c0VsZW1lbnRTaWJsaW5nJyA6ICdwcmV2aW91c1NpYmxpbmcnXG4gICAgICAgICAgICAgICAgdmFyIG5leHQgPSBlbFtkb3NlYXJjaF07XG4gICAgICAgICAgICAgICAgaWYgKG5leHQgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgd2hpbGUgKGVsW2Rvc2VhcmNoXSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgICAgICAgICAgICAgbmV4dCA9IGVsW2Rvc2VhcmNoXTtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIG5leHQ7XG4gICAgICAgIH0sXG4gICAgICAgIGFsd2F5c01lcmdlYWJsZTogZnVuY3Rpb24gKGVsKSB7XG5cbiAgICAgICAgICAgIGlmICghZWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoZWwubm9kZVR5cGUgPT09IDMpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbXcud3lzaXd5Zy5tZXJnZS5hbHdheXNNZXJnZWFibGUobXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGVsKSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChlbC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgIGlmICgvXig/OmFyZWF8YnJ8Y29sfGVtYmVkfGhyfGltZ3xpbnB1dHxsaW5rfG1ldGF8cGFyYW0pJC9pLnRlc3QoZWwudGFnTmFtZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmICgvXig/OnN0cm9uZ3xlbXxpfGJ8bGkpJC9pLnRlc3QoZWwudGFnTmFtZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmICgvXig/OnNwYW4pJC9pLnRlc3QoZWwudGFnTmFtZSkgJiYgIWVsLmNsYXNzTmFtZSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgJ21vZHVsZScpKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhlbCwgJ21vZHVsZScpKSB7XG4gICAgICAgICAgICAgICAgdmFyIG9yZCA9IG13LnRvb2xzLnBhcmVudHNPcmRlcihlbCwgWydlZGl0JywgJ21vZHVsZSddKTtcbiAgICAgICAgICAgICAgICAvL3RvZG9cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIHNlbGVjdG9ycyA9IFtcbiAgICAgICAgICAgICAgICAgICAgJ3AuZWxlbWVudCcsICdkaXYuZWxlbWVudCcsICdkaXY6bm90KFtjbGFzc10pJyxcbiAgICAgICAgICAgICAgICAgICAgJ2gxLmVsZW1lbnQnLCAnaDIuZWxlbWVudCcsICdoMy5lbGVtZW50JywgJ2g0LmVsZW1lbnQnLCAnaDUuZWxlbWVudCcsICdoNi5lbGVtZW50JyxcbiAgICAgICAgICAgICAgICAgICAgJy5lZGl0ICBoMScsICcuZWRpdCAgaDInLCAnLmVkaXQgIGgzJywgJy5lZGl0ICBoNCcsICcuZWRpdCAgaDUnLCAnLmVkaXQgIGg2JyxcbiAgICAgICAgICAgICAgICAgICAgJy5lZGl0IHAnXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBmaW5hbCA9IGZhbHNlLFxuICAgICAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBzZWxlY3RvcnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IHNlbGVjdG9yc1tpXTtcbiAgICAgICAgICAgICAgICBpZiAoZWwubWF0Y2hlcyhpdGVtKSkge1xuICAgICAgICAgICAgICAgICAgICBmaW5hbCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIGZpbmFsO1xuXG4gICAgICAgIH1cbiAgICB9LFxuICAgIGluaXQ6IGZ1bmN0aW9uIChzZWxlY3Rvcikge1xuXG4gICAgICAgIHNlbGVjdG9yID0gc2VsZWN0b3IgfHwgXCIubXdfZWRpdG9yX2J0blwiO1xuICAgICAgICB2YXIgbXdfZWRpdG9yX2J0bnMgPSBtdy4kKHNlbGVjdG9yKS5ub3QoJy5yZWFkeScpO1xuICAgICAgICBtd19lZGl0b3JfYnRuc1xuICAgICAgICAgICAgLmFkZENsYXNzKCdyZWFkeScpXG4gICAgICAgICAgICAub24oXCJjbGlja1wiLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgICAgICAgICBpZiAobXcud3lzaXd5Zy5lZGl0b3JzX2Rpc2FibGVkKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBpZiAoISQodGhpcykuaGFzQ2xhc3MoJ2Rpc2FibGVkJykpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHJlY3RhcmdldCA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihnZXRTZWxlY3Rpb24oKS5mb2N1c05vZGUpO1xuICAgICAgICAgICAgICAgICAgICByZWN0YXJnZXQgPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXMocmVjdGFyZ2V0LCBbJ2VsZW1lbnQnLCAnZWRpdCddKTtcbiAgICAgICAgICAgICAgICAgICAgaWYobXcubGl2ZUVkaXRTdGF0ZSl7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgY3VyclN0YXRlID0gbXcubGl2ZUVkaXRTdGF0ZS5zdGF0ZSgpXG4gICAgICAgICAgICAgICAgICAgICAgICBpZihjdXJyU3RhdGVbMF0uJGlkICE9PSAnd3lzaXd5ZyAgICcpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGFyZ2V0OiByZWN0YXJnZXQsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiByZWN0YXJnZXQuaW5uZXJIVE1MLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkaWQ6ICd3eXNpd3lnJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyIGNvbW1hbmQgPSBtdy4kKHRoaXMpLmRhdGFzZXQoJ2NvbW1hbmQnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFjb21tYW5kLmNvbnRhaW5zKCdjdXN0b20tJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuX2RvKGNvbW1hbmQpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG5hbWUgPSBjb21tYW5kLnJlcGxhY2UoJ2N1c3RvbS0nLCBcIlwiKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKG5hbWUgPT09ICdsaW5rJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubGluayh1bmRlZmluZWQsIHVuZGVmaW5lZCwgZ2V0U2VsZWN0aW9uKCkudG9TdHJpbmcoKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWdbbmFtZV0oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmKG13LmxpdmVFZGl0U3RhdGUpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogcmVjdGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiByZWN0YXJnZXQuaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlQ2xhc3MoXCJtd19lZGl0b3JfYnRuX21vdXNlZG93blwiKTtcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGVja19zZWxlY3Rpb24oZXZlbnQudGFyZ2V0KTtcblxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoZXZlbnQudHlwZSA9PT0gJ21vdXNlZG93bicgJiYgISQodGhpcykuaGFzQ2xhc3MoJ2Rpc2FibGVkJykpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hZGRDbGFzcyhcIm13X2VkaXRvcl9idG5fbW91c2Vkb3duXCIpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICBtd19lZGl0b3JfYnRucy5ob3ZlcihmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy4kKHRoaXMpLmFkZENsYXNzKFwibXdfZWRpdG9yX2J0bl9ob3ZlclwiKTtcbiAgICAgICAgfSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcyhcIm13X2VkaXRvcl9idG5faG92ZXJcIik7XG4gICAgICAgIH0pO1xuICAgICAgICBpZiAobXcud3lzaXd5Zy5yZWFkeSkgcmV0dXJuO1xuICAgICAgICBtdy53eXNpd3lnLnJlYWR5ID0gdHJ1ZTtcbiAgICAgICAgbXcuJChtd2QuYm9keSkub24oJ21vdXNldXAnLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgICAgIGlmIChldmVudC50YXJnZXQuaXNDb250ZW50RWRpdGFibGUpIHtcbiAgICAgICAgICAgICAgICBpZihldmVudC50YXJnZXQubm9kZU5hbWUpe1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoZWNrX3NlbGVjdGlvbihldmVudC50YXJnZXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIG13LiQobXdkLmJvZHkpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24gKGV2ZW50KSB7XG5cbiAgICAgICAgICAgIGlmICgoZXZlbnQua2V5Q29kZSA9PSA0NiB8fCBldmVudC5rZXlDb2RlID09IDgpICYmIGV2ZW50LnR5cGUgPT0gJ2tleWRvd24nKSB7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MobXcuaW1hZ2VfcmVzaXplciwgJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKCcuZWxlbWVudC1jdXJyZW50Jyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoZXZlbnQudHlwZSA9PT0gJ2tleWRvd24nKSB7XG5cbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaXNGaWVsZChldmVudC50YXJnZXQpIHx8ICFldmVudC50YXJnZXQuaXNDb250ZW50RWRpdGFibGUpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciBzZWwgPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICAgICAgaWYgKG13LmV2ZW50LmlzLmVudGVyKGV2ZW50KSkge1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKG13LmxpdmVFZGl0RG9tVHJlZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBmb2N1c2VkID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKHNlbC5mb2N1c05vZGUpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXREb21UcmVlLnJlZnJlc2goZm9jdXNlZC5wYXJlbnROb2RlKVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy53eXNpd3lnLmlzU2FmZU1vZGUoZXZlbnQudGFyZ2V0KSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGlzTGlzdCA9IG13LnRvb2xzLmZpcnN0TWF0Y2hlc09uTm9kZU9yUGFyZW50KGV2ZW50LnRhcmdldCwgWydsaScsICd1bCcsICdvbCddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICghaXNMaXN0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgaWQgPSBtdy5pZCgnbXctYnItJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbCgnPGJyPlxcdTIwMEMnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoc2VsLnJhbmdlQ291bnQgPiAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByID0gc2VsLmdldFJhbmdlQXQoMCk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChldmVudC5rZXlDb2RlID09IDkgJiYgIWV2ZW50LnNoaWZ0S2V5ICYmIHNlbC5mb2N1c05vZGUucGFyZW50Tm9kZS5pc2NvbnRlbnRFZGl0YWJsZSAmJiBzZWwuaXNDb2xsYXBzZWQpIHsgICAvKiB0YWIga2V5ICovXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmluc2VydF9odG1sKCcmbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDsnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbXcud3lzaXd5Zy5tYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2UoZXZlbnQsIHNlbCk7XG4gICAgICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIG13Lm9uLnRyaXBsZUNsaWNrKG13ZC5ib2R5LCBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdF9hbGwodGFyZ2V0KTtcbiAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKHRhcmdldCwgJ2VsZW1lbnQnKSkge1xuICAgICAgICAgICAgICAgIC8vbXcud3lzaXd5Zy5zZWxlY3RfYWxsKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKHRhcmdldCwgJ2VsZW1lbnQnKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgcyA9IHdpbmRvdy5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIGlmKCFzLnJhbmdlQ291bnQpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgciA9IHMuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgIHZhciBjID0gci5jbG9uZUNvbnRlbnRzKCk7XG4gICAgICAgICAgICAvL3ZhciBjb21tb24gPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoci5jb21tb25BbmNlc3RvckNvbnRhaW5lcik7XG4gICAgICAgICAgICB2YXIgY29tbW9uID0gci5jb21tb25BbmNlc3RvckNvbnRhaW5lcjtcbiAgICAgICAgICAgIGlmIChjb21tb24ubm9kZVR5cGUgPT09IDEpIHtcbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoY29tbW9uLCAnZWxlbWVudCcpKSB7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0X2FsbChjb21tb24pXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBjb21tb24gPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoci5jb21tb25BbmNlc3RvckNvbnRhaW5lcik7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNvbW1vbiwgJ2VsZW1lbnQnKSkge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdF9lbGVtZW50KGNvbW1vbilcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgYSA9IGNvbW1vbi5xdWVyeVNlbGVjdG9yQWxsKCcqJyksIGwgPSBhLmxlbmd0aCwgaSA9IDA7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmICghIXMuY29udGFpbnNOb2RlICYmIHMuY29udGFpbnNOb2RlKGFbaV0sIHRydWUpKSB7XG4gICAgICAgICAgICAgICAgICAgIHIuc2V0RW5kQmVmb3JlKGFbaV0pO1xuICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgbXcuJChtd2QuYm9keSkub24oJ2tleXVwJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIG13LnNtYWxsRWRpdG9yQ2FuY2VsZWQgPSB0cnVlO1xuICAgICAgICAgICAgbXcuc21hbGxFZGl0b3IuY3NzKHtcbiAgICAgICAgICAgICAgICB2aXNpYmlsaXR5OiBcImhpZGRlblwiXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmIChlLnRhcmdldC5pc0NvbnRlbnRFZGl0YWJsZSAmJiAhbXcudG9vbHMuaXNGaWVsZChlLnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShlLnRhcmdldClcblxuXG4gICAgICAgICAgICAgICAgaWYgKCFtd2QuYm9keS5lZGl0b3JfdHlwaW5nX3N0YXJ0VGltZSkge1xuICAgICAgICAgICAgICAgICAgICBtd2QuYm9keS5lZGl0b3JfdHlwaW5nX3N0YXJ0VGltZSA9IG5ldyBEYXRlKCk7XG4gICAgICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgICAgICB2YXIgc3RhcnRlZF90eXBpbmcgPSBtdy50b29scy5oYXNBbnlPZkNsYXNzZXModGhpcywgWydpc1R5cGluZyddKTtcbiAgICAgICAgICAgICAgICBpZiAoIXN0YXJ0ZWRfdHlwaW5nKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vIGlzVHlwaW5nIGNsYXNzIGlzIHJlbW92ZWQgZnJvbSBsaXZlZGl0LmpzXG4gICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKHRoaXMsICdpc1R5cGluZycpO1xuICAgICAgICAgICAgICAgICAgICBtd2QuYm9keS5lZGl0b3JfdHlwaW5nX3N0YXJ0VGltZSA9IG5ldyBEYXRlKCk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYobXcuX2luaXRIYW5kbGVzKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Ll9pbml0SGFuZGxlcy5oaWRlQWxsKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAvLyB1c2VyIGlzIHR5cGluZ1xuICAgICAgICAgICAgICAgICAgICBzdGFydGVkX3R5cGluZ19lbmRUaW1lID0gbmV3IERhdGUoKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRpbWVEaWZmID0gc3RhcnRlZF90eXBpbmdfZW5kVGltZSAtIG13ZC5ib2R5LmVkaXRvcl90eXBpbmdfc3RhcnRUaW1lOyAvL2luIG1zXG4gICAgICAgICAgICAgICAgICAgIHRpbWVEaWZmIC89IDEwMDA7XG4gICAgICAgICAgICAgICAgICAgIHZhciBzZWNvbmRzID0gTWF0aC5yb3VuZCh0aW1lRGlmZik7XG4gICAgICAgICAgICAgICAgICAgIG13ZC5ib2R5LmVkaXRvcl90eXBpbmdfc2Vjb25kcyA9IHNlY29uZHM7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgaWYgKG13ZC5ib2R5LmVkaXRvcl90eXBpbmdfc2Vjb25kcykge1xuICAgICAgICAgICAgICAgICAgICAvL2hvdyBtdWNoIHNlY29uZHMgdXNlciBpcyB0eXBpbmdcbiAgICAgICAgICAgICAgICAgICAgaWYgKG13ZC5ib2R5LmVkaXRvcl90eXBpbmdfc2Vjb25kcyA+IDEwKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKCdlZGl0VXNlcklzVHlwaW5nRm9yTG9uZycsIHRoaXMpXG4gICAgICAgICAgICAgICAgICAgICAgICBtd2QuYm9keS5lZGl0b3JfdHlwaW5nX3NlY29uZHMgPSAwO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXdkLmJvZHkuZWRpdG9yX3R5cGluZ19zdGFydFRpbWUgPSAwO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMuX29uQ2xvbmVhYmxlQ29udHJvbCkuaGlkZSgpO1xuICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5lbnRlcihlKSkgey8qXG5cbiAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5lbGVtZW50LWN1cnJlbnRcIikucmVtb3ZlQ2xhc3MoXCJlbGVtZW50LWN1cnJlbnRcIik7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlbCA9IG13ZC5xdWVyeVNlbGVjdG9yQWxsKCcuZWRpdCAuZWxlbWVudCcpLCBsID0gZWwubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICghZWxbaV0uaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbFtpXS5pZCA9IG13Lnd5c2l3eWcuY3JlYXRlRWxlbWVudElkKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICBpZiAoIWUuc2hpZnRLZXkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBwID0gbXcud3lzaXd5Zy5maW5kVGFnQWNyb3NzU2VsZWN0aW9uKCdwJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgdmFyIG5ld05vZGUgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoZ2V0U2VsZWN0aW9uKCkuZm9jdXNOb2RlKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vZGUuaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG5ld05vZGUuaWQgPSBtdy53eXNpd3lnLmNyZWF0ZUVsZW1lbnRJZCgpO1xuICAgICAgICAgICAgICAgICAgICB9Ki9cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChlLnRhcmdldC5pc0NvbnRlbnRFZGl0YWJsZVxuICAgICAgICAgICAgICAgICYmICFlLnNoaWZ0S2V5XG4gICAgICAgICAgICAgICAgJiYgIWUuY3RybEtleVxuICAgICAgICAgICAgICAgICYmIGUua2V5Q29kZSAhPT0gMjdcbiAgICAgICAgICAgICAgICAmJiBlLmtleUNvZGUgIT09IDExNlxuICAgICAgICAgICAgICAgICYmIGUua2V5Q29kZSAhPT0gMTdcbiAgICAgICAgICAgICAgICAmJiAoZS5rZXlDb2RlIDwgMzcgfHwgZS5rZXlDb2RlID4gNDApKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UoZS50YXJnZXQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZihlICYmIGUudGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGVja19zZWxlY3Rpb24oZS50YXJnZXQpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgY3JlYXRlRWxlbWVudElkOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnbXctZWxlbWVudF8nICsgbXcucmFuZG9tKCk7XG4gICAgfSxcbiAgICBjaGFuZ2U6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICBpZiAodHlwZW9mIGVsID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgZWwgPSBtd2QucXVlcnlTZWxlY3RvcihlbCk7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHRhcmdldCA9IG51bGw7XG4gICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgJ2VkaXQnKSkge1xuICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MoZWwsICdjaGFuZ2VkJyk7XG4gICAgICAgICAgICB0YXJnZXQgPSBlbDtcbiAgICAgICAgICAgIG13LnRyaWdnZXIoJ2VkaXRDaGFuZ2VkJywgdGFyZ2V0KVxuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZWwsICdlZGl0JykpIHtcbiAgICAgICAgICAgIHRhcmdldCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKGVsLCAnZWRpdCcpO1xuICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3ModGFyZ2V0LCAnY2hhbmdlZCcpO1xuICAgICAgICAgICAgbXcudHJpZ2dlcignZWRpdENoYW5nZWQnLCB0YXJnZXQpXG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRhcmdldCAhPT0gbnVsbCkge1xuICAgICAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHModGFyZ2V0LCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKHRoaXMsICdlZGl0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3ModGhpcywgJ2NoYW5nZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcignZWRpdENoYW5nZWQnLCB0aGlzKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuYXNrdXNlcnRvc3RheSA9IHRydWU7XG4gICAgICAgICAgICBtdy5kcmFnLmluaXREcmFmdCA9IHRydWU7XG4gICAgICAgIH1cblxuICAgIH0sXG4gICAgdmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcjogZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgaWYoICFjIHx8ICFjLnBhcmVudE5vZGUgfHwgYy5wYXJlbnROb2RlID09PSBkb2N1bWVudC5ib2R5ICl7XG4gICAgICAgICAgICByZXR1cm4gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICB0cnkgeyAgIC8qIEZpcmVmb3ggcmV0dXJucyB3cm9uZyB0YXJnZXQgKGRpdikgd2hlbiB5b3UgY2xpY2sgb24gYSBzcGluLWJ1dHRvbiAqL1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBjLnF1ZXJ5U2VsZWN0b3IgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gYztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHJldHVybiBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoYy5wYXJlbnROb2RlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBjYXRjaCAoZSkge1xuICAgICAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgZWRpdGFibGU6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICB2YXIgZWwgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoZWwpO1xuICAgICAgICByZXR1cm4gZWwuaXNDb250ZW50RWRpdGFibGUgJiYgWydTRUxFQ1QnLCAnSU5QVVQnLCAnVEVYVEFSRUEnXS5pbmRleE9mKGVsLm5vZGVOYW1lKSA9PT0gLTE7XG4gICAgfSxcbiAgICBnZXROZXh0Tm9kZTogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgaWYgKG5vZGUubmV4dFNpYmxpbmcpIHtcbiAgICAgICAgICAgIHJldHVybiBub2RlLm5leHRTaWJsaW5nXG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5nZXROZXh0Tm9kZShub2RlLnBhcmVudE5vZGUpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBjdXJzb3JUb0VsZW1lbnQ6IGZ1bmN0aW9uIChub2RlLCBhKSB7XG5cbiAgICAgICAgaWYgKCFub2RlKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgaWYobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQobm9kZSwgWydzYWZlLWVsZW1lbnQnLCAnaWNvbicsICdtdy1pY29uJywgJ21hdGVyaWFsLWljb25zJywgJ213LXd5c2l3eWctY3VzdG9tLWljb24nXSkpe1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKG5vZGUsIHRydWUpO1xuICAgICAgICBhID0gKGEgfHwgJ3N0YXJ0JykudHJpbSgpO1xuICAgICAgICB2YXIgc2VsID0gbXd3LmdldFNlbGVjdGlvbigpO1xuICAgICAgICB2YXIgciA9IG13ZC5jcmVhdGVSYW5nZSgpO1xuICAgICAgICBzZWwucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgIGlmIChhID09PSAnc3RhcnQnKSB7XG4gICAgICAgICAgICByLnNlbGVjdE5vZGVDb250ZW50cyhub2RlKTtcbiAgICAgICAgICAgIHIuY29sbGFwc2UodHJ1ZSk7XG4gICAgICAgICAgICBzZWwuYWRkUmFuZ2Uocik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoYSA9PT0gJ2VuZCcpIHtcbiAgICAgICAgICAgIHIuc2VsZWN0Tm9kZUNvbnRlbnRzKG5vZGUpO1xuICAgICAgICAgICAgci5jb2xsYXBzZShmYWxzZSk7XG4gICAgICAgICAgICBzZWwuYWRkUmFuZ2Uocik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoYSA9PT0gJ2JlZm9yZScpIHtcbiAgICAgICAgICAgIHIuc2VsZWN0Tm9kZShub2RlKTtcbiAgICAgICAgICAgIHIuY29sbGFwc2UoZmFsc2UpO1xuICAgICAgICAgICAgc2VsLmFkZFJhbmdlKHIpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKGEgPT09ICdhZnRlcicpIHtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IGRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XG4gICAgICAgICAgICByYW5nZS5zZXRTdGFydEFmdGVyKG5vZGUpO1xuICAgICAgICAgICAgcmFuZ2UuY29sbGFwc2UodHJ1ZSk7XG5cbiAgICAgICAgICAgIHNlbC5yZW1vdmVBbGxSYW5nZXMoKTtcbiAgICAgICAgICAgIHNlbC5hZGRSYW5nZShyYW5nZSk7XG4gICAgICAgIH1cblxuICAgIH0sXG4gICAgcmZhcHBsaWVyOiBmdW5jdGlvbiAodGFnLCBjbGFzc25hbWUsIHN0eWxlX29iamVjdCkge1xuICAgICAgICAvLyB2YXIgZWwgPSBtdy53eXNpd3lnLmFwcGxpZXIoJ2RpdicsICdlbGVtZW50Jywge3dpZHRoOiBcIjEwMCVcIn0pO1xuICAgICAgICB2YXIgcGFyZW50LCBmbm9kZSA9IGdldFNlbGVjdGlvbigpLmZvY3VzTm9kZTtcbiAgICAgICAgLyppZihtdy53eXNpd3lnLmlzU2FmZU1vZGUobXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGZub2RlKSkpIHtcbiAgICAgICAgICAgIHBhcmVudCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKGZub2RlLCAnc2FmZS1tb2RlJyk7XG4gICAgICAgICAgICBjb25zb2xlLmxvZyhwYXJlbnQpXG4gICAgICAgICAgICBpZihwYXJlbnQpe1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY29udGVudEVkaXRhYmxlKHBhcmVudCwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgJCgnW2NvbnRlbnRlZGl0YWJsZV0nLCBwYXJlbnQpLnJlbW92ZUF0dHIoJ2NvbnRlbnRlZGl0YWJsZScpXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSovXG4gICAgICAgIHZhciBpZCA9IG13LmlkKCdtdy1hcHBsaWVyLWVsZW1lbnQtJyk7XG4gICAgICAgIHRoaXMuZXhlY0NvbW1hbmQoXCJpbnNlcnRIVE1MXCIsIGZhbHNlLCAnPCcrdGFnKycgJysoY2xhc3NuYW1lID8gJ2NsYXNzPVwiJyArIGNsYXNzbmFtZSArICdcIicgOiAnJykrJyBpZD1cIicraWQrJ1wiPicrIGdldFNlbGVjdGlvbigpKyc8LycrdGFnKyc+Jyk7XG4gICAgICAgIHZhciAkZWwgPSBtdy4kKCcjJyArIGlkKTtcbiAgICAgICAgaWYgKHN0eWxlX29iamVjdCkge1xuICAgICAgICAgICAgJGVsLmNzcyhzdHlsZV9vYmplY3QpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiAkZWxbMF07XG4gICAgfSxcbiAgICBhcHBsaWVyOiBmdW5jdGlvbiAodGFnLCBjbGFzc25hbWUsIHN0eWxlX29iamVjdCkge1xuICAgICAgICB2YXIgY2xhc3NuYW1lID0gY2xhc3NuYW1lIHx8ICcnO1xuICAgICAgICBpZiAobXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkpIHtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IHdpbmRvdy5nZXRTZWxlY3Rpb24oKS5nZXRSYW5nZUF0KDApO1xuICAgICAgICAgICAgdmFyIHNlbGVjdGlvbkNvbnRlbnRzID0gcmFuZ2UuZXh0cmFjdENvbnRlbnRzKCk7XG4gICAgICAgICAgICB2YXIgZWwgPSBtd2QuY3JlYXRlRWxlbWVudCh0YWcpO1xuICAgICAgICAgICAgZWwuY2xhc3NOYW1lID0gY2xhc3NuYW1lO1xuICAgICAgICAgICAgdHlwZW9mIHN0eWxlX29iamVjdCAhPT0gJ3VuZGVmaW5lZCcgPyBtdy4kKGVsKS5jc3Moc3R5bGVfb2JqZWN0KSA6ICcnO1xuICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoc2VsZWN0aW9uQ29udGVudHMpO1xuICAgICAgICAgICAgcmFuZ2UuaW5zZXJ0Tm9kZShlbCk7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShlbCk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGV4dGVybmFsX3Rvb2w6IGZ1bmN0aW9uIChlbCwgdXJsKSB7XG4gICAgICAgIHZhciBlbCA9IG13LiQoZWwpLmVxKDApO1xuICAgICAgICB2YXIgb2Zmc2V0ID0gZWwub2Zmc2V0KCk7XG4gICAgICAgIG13LiQobXcud3lzaXd5Zy5leHRlcm5hbCkuY3NzKHtcbiAgICAgICAgICAgIHRvcDogb2Zmc2V0LnRvcCAtIG13LiQod2luZG93KS5zY3JvbGxUb3AoKSArIGVsLmhlaWdodCgpLFxuICAgICAgICAgICAgbGVmdDogb2Zmc2V0LmxlZnRcbiAgICAgICAgfSk7XG4gICAgICAgIG13LiQobXcud3lzaXd5Zy5leHRlcm5hbCkuaHRtbChcIjxpZnJhbWUgc3JjPSdcIiArIHVybCArIFwiJyBzY3JvbGxpbmc9J25vJyBmcmFtZWJvcmRlcj0nMCcgLz5cIik7XG4gICAgICAgIHZhciBmcmFtZSA9IG13Lnd5c2l3eWcuZXh0ZXJuYWwucXVlcnlTZWxlY3RvcignaWZyYW1lJyk7XG4gICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cudGhpc2ZyYW1lID0gZnJhbWU7XG4gICAgfSxcbiAgICBnZXRFeHRlcm5hbERhdGE6IGZ1bmN0aW9uICh1cmwsIGNiKSB7XG4gICAgICAgIHZhciBoYXMgPSBtdy5zdG9yYWdlLmdldCh1cmwpO1xuICAgICAgICBpZiAoaGFzKSB7XG4gICAgICAgICAgICBjYi5jYWxsKGhhcywgaGFzKVxuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgJC5nZXQodXJsLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgIG13LnN0b3JhZ2Uuc2V0KHVybCwgZGF0YSlcbiAgICAgICAgICAgICAgICBjYi5jYWxsKGRhdGEsIGRhdGEpXG4gICAgICAgICAgICB9KVxuICAgICAgICB9XG4gICAgfSxcbiAgICB0b2RvX2V4dGVybmFsX3Rvb2w6IGZ1bmN0aW9uIChlbCwgdXJsKSB7XG4gICAgICAgIHZhciBlbCA9IG13LiQoZWwpLmVxKDApO1xuICAgICAgICB2YXIgb2Zmc2V0ID0gZWwub2Zmc2V0KCk7XG4gICAgICAgIG13LiQobXcud3lzaXd5Zy5leHRlcm5hbCkuY3NzKHtcbiAgICAgICAgICAgIHRvcDogb2Zmc2V0LnRvcCAtIG13LiQod2luZG93KS5zY3JvbGxUb3AoKSArIGVsLmhlaWdodCgpLFxuICAgICAgICAgICAgbGVmdDogb2Zmc2V0LmxlZnRcbiAgICAgICAgfSk7XG4gICAgICAgIG13LiQobXcud3lzaXd5Zy5leHRlcm5hbCkuaHRtbChcIjxpZnJhbWUgc2Nyb2xsaW5nPSdubycgZnJhbWVib3JkZXI9JzAnIC8+XCIpO1xuICAgICAgICB2YXIgZnJhbWUgPSBtdy53eXNpd3lnLmV4dGVybmFsLnF1ZXJ5U2VsZWN0b3IoJ2lmcmFtZScpO1xuXG4gICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cudGhpc2ZyYW1lID0gZnJhbWU7XG4gICAgICAgIGlmICh1cmwuaW5kZXhPZignIycpICE9PSAtMSkge1xuICAgICAgICAgICAgZnJhbWUuc3JjID0gJyMnICsgdXJsLnNwbGl0KCcjJylbMV1cbiAgICAgICAgfVxuXG4gICAgICAgIG13Lnd5c2l3eWcuZ2V0RXh0ZXJuYWxEYXRhKHVybCwgZnVuY3Rpb24gKGh0bWwpIHtcblxuICAgICAgICAgICAgZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5vcGVuKCk7XG4gICAgICAgICAgICBmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LndyaXRlKGh0bWwpO1xuICAgICAgICAgICAgZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5jbG9zZSgpO1xuICAgICAgICB9KVxuICAgIH0sXG4gICAgY3JlYXRlZWxlbWVudDogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgZWwgPSBtdy53eXNpd3lnLmFwcGxpZXIoJ2RpdicsICdtd19hcHBsaWVyIGVsZW1lbnQnKTtcbiAgICB9LFxuICAgIGZvbnRjb2xvcnBpY2tlcjogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgIG13Lnd5c2l3eWcuX2ZvbnRjb2xvcnBpY2tlci5zaG93KCk7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5fZm9udGNvbG9ycGlja2VyLnNob3coKTtcbiAgICAgICAgfSwgMjApO1xuICAgIH0sXG4gICAgZm9udGJnY29sb3JwaWNrZXI6IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuX2JnZm9udGNvbG9ycGlja2VyLnNob3coKTtcbiAgICAgICAgfSwgMjApO1xuXG5cbiAgICB9LFxuICAgIGZvbnRDb2xvcjogZnVuY3Rpb24gKGNvbG9yKSB7XG4gICAgICAgIGlmICgvXlswLTlBLUZdezMsNn0kL2kudGVzdChjb2xvcikgJiYgIWNvbG9yLmNvbnRhaW5zKFwiI1wiKSkge1xuICAgICAgICAgICAgY29sb3IgPSBcIiNcIiArIGNvbG9yO1xuICAgICAgICB9XG4gICAgICAgIHZhciByZWN0YXJnZXQgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoZ2V0U2VsZWN0aW9uKCkuZm9jdXNOb2RlKTtcbiAgICAgICAgcmVjdGFyZ2V0ID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKHJlY3RhcmdldCwgWydlbGVtZW50JywgJ2VkaXQnXSk7XG4gICAgICAgIGlmKG13LmxpdmVFZGl0U3RhdGUpe1xuICAgICAgICAgICAgdmFyIGN1cnJTdGF0ZSA9IG13LmxpdmVFZGl0U3RhdGUuc3RhdGUoKVxuICAgICAgICAgICAgaWYoY3VyclN0YXRlWzBdLiRpZCAhPT0gJ3d5c2l3eWcgICAnKXtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogcmVjdGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICB2YWx1ZTogcmVjdGFyZ2V0LmlubmVySFRNTCxcbiAgICAgICAgICAgICAgICAgICAgJGlkOiAnd3lzaXd5ZydcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZiAoY29sb3IgPT0gJ25vbmUnKSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmV4ZWNDb21tYW5kKCdyZW1vdmVGb3JtYXQnLCBmYWxzZSwgXCJmb3JlQ29sb3JcIik7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBkb2N1bWVudC5leGVjQ29tbWFuZChcInN0eWxlV2l0aENTU1wiLCBudWxsLCB0cnVlKTtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoJ2ZvcmVjb2xvcicsIG51bGwsIGNvbG9yKTtcbiAgICAgICAgfVxuICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICB0YXJnZXQ6IHJlY3RhcmdldCxcbiAgICAgICAgICAgIHZhbHVlOiByZWN0YXJnZXQuaW5uZXJIVE1MLFxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGZvbnRiZzogZnVuY3Rpb24gKGNvbG9yKSB7XG5cbiAgICAgICAgaWYgKC9eWzAtOUEtRl17Myw2fSQvaS50ZXN0KGNvbG9yKSAmJiAhY29sb3IuY29udGFpbnMoXCIjXCIpKSB7XG4gICAgICAgICAgICBjb2xvciA9IFwiI1wiICsgY29sb3I7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGNvbG9yID09PSAnbm9uZScpIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoJ3JlbW92ZUZvcm1hdCcsIGZhbHNlLCBcImJhY2tjb2xvclwiKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGRvY3VtZW50LmV4ZWNDb21tYW5kKFwic3R5bGVXaXRoQ1NTXCIsIG51bGwsIHRydWUpO1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgnYmFja2NvbG9yJywgbnVsbCwgY29sb3IpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICByZXF1ZXN0X2NoYW5nZV9iZ19jb2xvcjogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIG13Lnd5c2l3eWcuZXh0ZXJuYWxfdG9vbChlbCwgbXcuZXh0ZXJuYWxfdG9vbCgnY29sb3JfcGlja2VyJykgKyAnI2NoYW5nZV9iZ19jb2xvcicpO1xuICAgICAgICBtdy4kKG13Lnd5c2l3eWcuZXh0ZXJuYWwpLmZpbmQoXCJpZnJhbWVcIikud2lkdGgoMjgwKS5oZWlnaHQoMzIwKTtcbiAgICB9LFxuICAgIGNoYW5nZV9iZ19jb2xvcjogZnVuY3Rpb24gKGNvbG9yKSB7XG4gICAgICAgIGNvbG9yID0gY29sb3IgIT09ICd0cmFuc3BhcmVudCcgPyAnIycgKyBjb2xvciA6IGNvbG9yO1xuICAgICAgICBtdy4kKFwiLmVsZW1lbnQtY3VycmVudFwiKS5jc3MoXCJiYWNrZ3JvdW5kQ29sb3JcIiwgY29sb3IpO1xuICAgICAgICBtdy53eXNpd3lnLmNoYW5nZSgnLmVsZW1lbnQtY3VycmVudCcpO1xuICAgIH0sXG4gICAgcmVxdWVzdF9ib3JkZXJfY29sb3I6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICBtdy53eXNpd3lnLmV4dGVybmFsX3Rvb2woZWwsIG13LmV4dGVybmFsX3Rvb2woJ2NvbG9yX3BpY2tlcicpICsgJyNjaGFuZ2VfYm9yZGVyX2NvbG9yJyk7XG4gICAgICAgIG13LiQobXcud3lzaXd5Zy5leHRlcm5hbCkuZmluZChcImlmcmFtZVwiKS53aWR0aCgyODApLmhlaWdodCgzMjApO1xuICAgIH0sXG4gICAgY2hhbmdlX2JvcmRlcl9jb2xvcjogZnVuY3Rpb24gKGNvbG9yKSB7XG4gICAgICAgIGlmIChjb2xvciAhPSBcInRyYW5zcGFyZW50XCIpIHtcbiAgICAgICAgICAgIG13LiQoXCIuZWxlbWVudC1jdXJyZW50XCIpLmNzcyhtdy5ib3JkZXJfd2hpY2ggKyBcIkNvbG9yXCIsIFwiI1wiICsgY29sb3IpO1xuICAgICAgICAgICAgbXcuJChcIi5lZF9ib3JkZXJjb2xvcl9waWNrIHNwYW5cIikuY3NzKFwiYmFja2dyb3VuZFwiLCBcIiNcIiArIGNvbG9yKTtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKCcuZWxlbWVudC1jdXJyZW50Jyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBtdy4kKFwiLmVsZW1lbnQtY3VycmVudFwiKS5jc3MobXcuYm9yZGVyX3doaWNoICsgXCJDb2xvclwiLCBcInRyYW5zcGFyZW50XCIpO1xuICAgICAgICAgICAgbXcuJChcIi5lZF9ib3JkZXJjb2xvcl9waWNrIHNwYW5cIikuY3NzKFwiYmFja2dyb3VuZFwiLCBcIlwiKTtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKCcuZWxlbWVudC1jdXJyZW50Jyk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHJlcXVlc3RfY2hhbmdlX3NoYWRvd19jb2xvcjogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIG13Lnd5c2l3eWcuZXh0ZXJuYWxfdG9vbChlbCwgbXcuZXh0ZXJuYWxfdG9vbCgnY29sb3JfcGlja2VyJykgKyAnI2NoYW5nZV9zaGFkb3dfY29sb3InKTtcbiAgICAgICAgbXcuJChtdy53eXNpd3lnLmV4dGVybmFsKS5maW5kKFwiaWZyYW1lXCIpLndpZHRoKDI4MCkuaGVpZ2h0KDMyMCk7XG4gICAgfSxcbiAgICBjaGFuZ2Vfc2hhZG93X2NvbG9yOiBmdW5jdGlvbiAoY29sb3IpIHtcbiAgICAgICAgbXcuY3VycmVudF9lbGVtZW50X3N0eWxlcyA9IGdldENvbXB1dGVkU3R5bGUobXcuJCgnLmVsZW1lbnQtY3VycmVudCcpWzBdLCBudWxsKTtcbiAgICAgICAgaWYgKG13LmN1cnJlbnRfZWxlbWVudF9zdHlsZXMuYm94U2hhZG93ICE9IFwibm9uZVwiKSB7XG4gICAgICAgICAgICB2YXIgYXJyID0gbXcuY3VycmVudF9lbGVtZW50X3N0eWxlcy5ib3hTaGFkb3cuc3BsaXQoJyAnKTtcbiAgICAgICAgICAgIHZhciBsZW4gPSBhcnIubGVuZ3RoO1xuICAgICAgICAgICAgdmFyIHggPSBwYXJzZUZsb2F0KGFycltsZW4gLSA0XSk7XG4gICAgICAgICAgICB2YXIgeSA9IHBhcnNlRmxvYXQoYXJyW2xlbiAtIDNdKTtcbiAgICAgICAgICAgIHZhciBibHVyID0gcGFyc2VGbG9hdChhcnJbbGVuIC0gMl0pO1xuICAgICAgICAgICAgbXcuJChcIi5lbGVtZW50LWN1cnJlbnRcIikuY3NzKFwiYm94LXNoYWRvd1wiLCB4ICsgXCJweCBcIiArIHkgKyBcInB4IFwiICsgYmx1ciArIFwicHggI1wiICsgY29sb3IpO1xuICAgICAgICAgICAgbXcuJChcIi5lZF9zaGFkb3dfY29sb3JcIikuZGF0YXNldChcImNvbG9yXCIsIGNvbG9yKTtcblxuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgbXcuJChcIi5lbGVtZW50LWN1cnJlbnRcIikuY3NzKFwiYm94LXNoYWRvd1wiLCBcIjBweCAwcHggNnB4ICNcIiArIGNvbG9yKTtcbiAgICAgICAgICAgIG13LiQoXCIuZWRfc2hhZG93X2NvbG9yXCIpLmRhdGFzZXQoXCJjb2xvclwiLCBjb2xvcik7XG4gICAgICAgIH1cbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UoJy5lbGVtZW50LWN1cnJlbnQnKTtcbiAgICB9LFxuICAgIGZvbnRGYW1pbHk6IGZ1bmN0aW9uIChmb250X25hbWUpIHtcbiAgICAgICAgdmFyIHJhbmdlID0gZ2V0U2VsZWN0aW9uKCkuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgZG9jdW1lbnQuZXhlY0NvbW1hbmQoXCJzdHlsZVdpdGhDU1NcIiwgbnVsbCwgdHJ1ZSk7XG4gICAgICAgIHZhciBlbCA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihyYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcik7XG4gICAgICAgIGlmIChyYW5nZS5jb2xsYXBzZWQpIHtcblxuICAgICAgICAgICAgbXcud3lzaXd5Zy5zZWxlY3RfYWxsKGVsKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmV4ZWNDb21tYW5kKCdmb250TmFtZScsIG51bGwsIGZvbnRfbmFtZSk7XG4gICAgICAgICAgICByYW5nZS5jb2xsYXBzZSgpXG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBkb2N1bWVudC5leGVjQ29tbWFuZCgnZm9udE5hbWUnLCBudWxsLCBmb250X25hbWUpO1xuICAgICAgICB9XG5cbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UoZWwpXG5cbiAgICB9LFxuICAgIG5lc3RpbmdGaXhlczogZnVuY3Rpb24gKHJvb3QpIHsgIC8qXG4gICAgIHZhciByb290ID0gcm9vdCB8fCBtd2QuYm9keTtcbiAgICAgdmFyIGFsbCA9IHJvb3QucXVlcnlTZWxlY3RvckFsbCgnLm13LXNwYW4tZm9udC1zaXplJyksXG4gICAgIGwgPSBhbGwubGVuZ3RoLFxuICAgICBpPTA7XG4gICAgIGZvciggOyBpPGw7IGkrKyApe1xuICAgICB2YXIgZWwgPSBhbGxbaV07XG4gICAgIGlmKGVsLmZpcnN0Q2hpbGQgPT09IGVsLmxhc3RDaGlsZCAmJiBlbC5maXJzdENoaWxkLm5vZGVUeXBlICE9PSAzKXtcbiAgICAgLy8gbXcuJChlbC5maXJzdENoaWxkKS51bndyYXAoKTtcbiAgICAgfVxuICAgICB9ICovXG4gICAgfSxcbiAgICBsaW5lSGVpZ2h0OiBmdW5jdGlvbiAoYSkge1xuICAgICAgICBhID0gYSB8fCAnbm9ybWFsJztcbiAgICAgICAgYSA9ICh0eXBlb2YgYSA9PT0gJ251bWJlcicpID8gKGEgKyAncHgnKSA6IGE7XG4gICAgICAgIHZhciByID0gZ2V0U2VsZWN0aW9uKCkuZ2V0UmFuZ2VBdCgwKS5jb21tb25BbmNlc3RvckNvbnRhaW5lcjtcbiAgICAgICAgdmFyIGVsID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKHIpO1xuICAgICAgICByLnN0eWxlLmZvbnRTaXplID0gYTtcbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2Uocik7XG4gICAgfSxcbiAgICBmb250U2l6ZTogZnVuY3Rpb24gKGEpIHtcblxuICAgICAgICBpZiAod2luZG93LmdldFNlbGVjdGlvbigpLmlzQ29sbGFwc2VkKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgbXcud3lzaXd5Zy5hbGxTdGF0ZW1lbnRzKGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgcmFuZ3kuaW5pdCgpO1xuICAgICAgICAgICAgdmFyIGNsc3RlbXAgPSAnbXctZm9udC1zaXplLScgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgIHZhciBjbGFzc0FwcGxpZXIgPSByYW5neS5jcmVhdGVDc3NDbGFzc0FwcGxpZXIoXCJtdy1mb250LXNpemUgXCIgKyBjbHN0ZW1wLCB0cnVlKTtcbiAgICAgICAgICAgIGNsYXNzQXBwbGllci5hcHBseVRvU2VsZWN0aW9uKCk7XG5cbiAgICAgICAgICAgIHZhciBhbGwgPSBtd2QucXVlcnlTZWxlY3RvckFsbCgnLicgKyBjbHN0ZW1wKSxcbiAgICAgICAgICAgICAgICBsID0gYWxsLmxlbmd0aCxcbiAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgIGZvciAoIDsgaSA8IGw7IGkrKyApIHtcbiAgICAgICAgICAgICAgICBhbGxbaV0uc3R5bGUuZm9udFNpemUgPSBhICsgJ3B4JztcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhhbGxbaV0sIGNsc3RlbXApO1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKGFsbFtpXSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIG13LiQoJy5lZGl0IC5tdy1mb250LXNpemUnKS5yZW1vdmVDbGFzcygnbXctZm9udC1zaXplJylcblxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGZvbnRTaXplUHJvbXB0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBzaXplID0gcHJvbXB0KFwiUGxlYXNlIGVudGVyIGZvbnQgc2l6ZVwiLCBcIlwiKTtcblxuICAgICAgICBpZiAoc2l6ZSAhPSBudWxsKSB7XG4gICAgICAgICAgICB2YXIgYSA9IHBhcnNlSW50KHNpemUpO1xuICAgICAgICAgICAgaWYgKGEgPiAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5mb250U2l6ZShhKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgcmVzZXRBY3RpdmVCdXR0b25zOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13LiQoJy5td19lZGl0b3JfYnRuX2FjdGl2ZScpLnJlbW92ZUNsYXNzKCdtd19lZGl0b3JfYnRuX2FjdGl2ZScpO1xuICAgIH0sXG4gICAgc2V0QWN0aXZlQnV0dG9uczogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgbXcucmVxdWlyZSgnY3NzX3BhcnNlci5qcycpO1xuXG4gICAgICAgIHZhciBjc3MgPSBtdy5DU1NQYXJzZXIobm9kZSk7XG4gICAgICAgIGlmIChjc3MgJiYgY3NzLmdldCkge1xuICAgICAgICAgICAgdmFyIGZvbnQgPSBjc3MuZ2V0LmZvbnQoKTtcbiAgICAgICAgICAgIHZhciBmYW1pbHlfYXJyYXkgPSBmb250LmZhbWlseS5zcGxpdCgnLCcpO1xuICAgICAgICAgICAgaWYgKGZhbWlseV9hcnJheS5sZW5ndGggPT0gMSkge1xuICAgICAgICAgICAgICAgIHZhciBmYW0gPSBmb250LmZhbWlseTtcblxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvL3ZhciBmYW0gPSBtdy50b29scy5nZXRGaXJzdEVxdWFsRnJvbVR3b0FycmF5cyhmYW1pbHlfYXJyYXksIG13Lnd5c2l3eWcuZWRpdG9yRm9udHMpO1xuICAgICAgICAgICAgICAgIHZhciBmYW0gPSBmYW1pbHlfYXJyYXkuc2hpZnQoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIGRkdmFsID0gbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9udF9mYW1pbHlcIik7XG4gICAgICAgICAgICBpZiAoZGR2YWwubGVuZ3RoICE9IDAgJiYgZGR2YWwuc2V0RHJvcGRvd25WYWx1ZSAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X2ZhbWlseVwiKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5zZXREcm9wZG93blZhbHVlKGZhbSk7XG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgc2V0QWN0aXZlRm9udFNpemU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbXcucmVxdWlyZSgnY3NzX3BhcnNlci5qcycpO1xuXG4gICAgICAgIHZhciBzZWwgPSBnZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgdmFyIHJhbmdlID0gc2VsLmdldFJhbmdlQXQoMCk7XG4gICAgICAgIGlmKHJhbmdlLmNvbGxhcHNlZCkge1xuICAgICAgICAgICAgdmFyIG5vZGUgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoc2VsLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICB2YXIgY3NzX25vZGVfZ2V0PW13LkNTU1BhcnNlcihub2RlKS5nZXQ7XG4gICAgICAgICAgICBpZih0eXBlb2YoY3NzX25vZGVfZ2V0KSAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgdmFyIHNpemUgPSBNYXRoLnJvdW5kKHBhcnNlRmxvYXQoY3NzX25vZGVfZ2V0LmZvbnQoKS5zaXplKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X3NpemUgLm13LWRyb3Bkb3duLXZhbFwiKS5odG1sKHNpemUgKyAncHgnKVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdmFyIGN1cnIgPSByYW5nZS5zdGFydENvbnRhaW5lcjtcbiAgICAgICAgICAgIHZhciBlbmQgPSByYW5nZS5lbmRDb250YWluZXI7XG4gICAgICAgICAgICB2YXIgY29tbW9uID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKTtcbiAgICAgICAgICAgIHZhciBzaXplID0gTWF0aC5yb3VuZChwYXJzZUZsb2F0KG13LkNTU1BhcnNlcihjb21tb24pLmdldC5mb250KCkuc2l6ZSkpO1xuICAgICAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gZW5kKSB7XG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoY3Vycik7XG4gICAgICAgICAgICAgICAgY3VyciA9IGN1cnIubmV4dEVsZW1lbnRTaWJsaW5nO1xuICAgICAgICAgICAgICAgIHZhciBjc3Nfbm9kZV9nZXQ9bXcuQ1NTUGFyc2VyKG5vZGUpLmdldDtcbiAgICAgICAgICAgICAgICBpZih0eXBlb2YoY3NzX25vZGVfZ2V0KSAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgc2l6ZWMgPSBNYXRoLnJvdW5kKHBhcnNlRmxvYXQoY3NzX25vZGVfZ2V0LmZvbnQoKS5zaXplKSk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChzaXplYyAhPT0gc2l6ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9udF9zaXplIC5tdy1kcm9wZG93bi12YWxcIikuaHRtbChtdy5sYW5nKCdTaXplJykpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9udF9zaXplIC5tdy1kcm9wZG93bi12YWxcIikuaHRtbChzaXplICsgJ3B4JylcblxuICAgICAgICB9XG4gICAgfSxcblxuICAgIGxpc3RTcGxpdDogZnVuY3Rpb24gKGxpc3QsIGluZGV4KSB7XG4gICAgICAgIGlmICghbGlzdCB8fCB0eXBlb2YgaW5kZXggPT0gJ3VuZGVmaW5lZCcpIHJldHVybjtcbiAgICAgICAgdmFyIGN1cnIgPSBsaXN0LmNoaWxkcmVuW2luZGV4XTtcbiAgICAgICAgdmFyIGxpc3R0b3AgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KGxpc3Qubm9kZU5hbWUpO1xuICAgICAgICB2YXIgbGlzdGJvdHRvbSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQobGlzdC5ub2RlTmFtZSk7XG4gICAgICAgIHZhciBmaW5hbCA9IHttaWRkbGU6IGN1cnJ9XG5cbiAgICAgICAgZm9yICh2YXIgaXRvcCA9IDA7IGl0b3AgPCBpbmRleDsgaXRvcCsrKSB7XG4gICAgICAgICAgICBsaXN0dG9wLmFwcGVuZENoaWxkKGxpc3QuY2hpbGRyZW5baXRvcF0pXG4gICAgICAgIH1cbiAgICAgICAgZm9yICh2YXIgaWJvdCA9IDE7IGlib3QgPCBsaXN0LmNoaWxkcmVuLmxlbmd0aDsgaWJvdCsrKSB7XG4gICAgICAgICAgICAvL2Zvcih2YXIgaWJvdCA9IGluZGV4KzE7IGlib3QgPCBsaXN0LmNoaWxkcmVuLmxlbmd0aDsgaWJvdCsrKXtcblxuICAgICAgICAgICAgbGlzdGJvdHRvbS5hcHBlbmRDaGlsZChsaXN0LmNoaWxkcmVuW2lib3RdKVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKGxpc3R0b3AuY2hpbGRyZW4ubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgZmluYWwudG9wID0gbGlzdHRvcFxuICAgICAgICB9XG4gICAgICAgIGlmIChsaXN0Ym90dG9tLmNoaWxkcmVuLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIGZpbmFsLmJvdHRvbSA9IGxpc3Rib3R0b21cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gZmluYWw7XG5cbiAgICB9LFxuICAgIGlzRm9ybWF0RWxlbWVudDogZnVuY3Rpb24gKG9iaikge1xuICAgICAgICB2YXIgaXRlbXMgPSAvXihkaXZ8aFsxLTZdfHApJC9pO1xuICAgICAgICByZXR1cm4gaXRlbXMudGVzdChvYmoubm9kZU5hbWUpO1xuICAgIH0sXG4gICAgZGVjb3JhdG9yczoge1xuICAgICAgICBiOiAnLm13X2VkaXRvcl9ib2xkJyxcbiAgICAgICAgc3Ryb25nOiAnLm13X2VkaXRvcl9ib2xkJyxcbiAgICAgICAgaTogJy5td19lZGl0b3JfaXRhbGljJyxcbiAgICAgICAgZW06ICcubXdfZWRpdG9yX2l0YWxpYycsXG4gICAgICAgIHU6ICcubXdfZWRpdG9yX3VuZGVybGluZScsXG4gICAgICAgIHM6ICcubXdfZWRpdG9yX3N0cmlrZScsXG4gICAgICAgIHN0cmlrZTogJy5td19lZGl0b3Jfc3RyaWtlJ1xuICAgIH0sXG4gICAgc2V0RGVjb3JhdG9yczogZnVuY3Rpb24gKHNlbCkge1xuICAgICAgICBzZWwgPSBzZWwgfHwgZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgIHZhciBub2RlID0gc2VsLmZvY3VzTm9kZTtcbiAgICAgICAgd2hpbGUgKG5vZGUubm9kZU5hbWUgIT09ICdESVYnICYmIG5vZGUubm9kZU5hbWUgIT09ICdCT0RZJykge1xuICAgICAgICAgICAgZm9yICh2YXIgeCBpbiBtdy53eXNpd3lnLmRlY29yYXRvcnMpIHtcbiAgICAgICAgICAgICAgICBpZiAobm9kZS5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpID09PSB4KSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQobXcud3lzaXd5Zy5kZWNvcmF0b3JzW3hdKS5hZGRDbGFzcygnbXdfZWRpdG9yX2J0bl9hY3RpdmUnKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG5vZGUgPSBub2RlLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHN0YXJ0ZWRfY2hlY2tpbmc6IGZhbHNlLFxuICAgIGNoZWNrX3NlbGVjdGlvbjogZnVuY3Rpb24gKHRhcmdldCkge1xuICAgICAgICB0YXJnZXQgPSB0YXJnZXQgfHwgZmFsc2U7XG5cbiAgICAgICAgbXcucmVxdWlyZSgnY3NzX3BhcnNlci5qcycpO1xuXG5cbiAgICAgICAgaWYgKCFtdy53eXNpd3lnLnN0YXJ0ZWRfY2hlY2tpbmcpIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuc3RhcnRlZF9jaGVja2luZyA9IHRydWU7XG5cbiAgICAgICAgICAgIHZhciBzZWxlY3Rpb24gPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICAvL2lmIChzZWxlY3Rpb24ucmFuZ2VDb3VudCA+IDEpIHtcbiAgICAgICAgICAgIC8vICAgIHZhciBzdGFydGVkX3R5cGluZyA9IG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyhtd2QuYm9keSwgWydpc1R5cGluZyddKTtcbiAgICAgICAgICAgIC8vICAgIGlmKCFzdGFydGVkX3R5cGluZyl7XG4gICAgICAgICAgICAvLyAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3MobXdkLmJvZHksICdpc1R5cGluZycpO1xuICAgICAgICAgICAgLy8gICAgfVxuICAgICAgICAgICAgLy99XG4gICAgICAgICAgICBpZiAoc2VsZWN0aW9uLnJhbmdlQ291bnQgPiAwKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5yZXNldEFjdGl2ZUJ1dHRvbnMoKTtcbiAgICAgICAgICAgICAgICB2YXIgcmFuZ2UgPSBzZWxlY3Rpb24uZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICB2YXIgc3RhcnQgPSByYW5nZS5zdGFydENvbnRhaW5lcjtcbiAgICAgICAgICAgICAgICB2YXIgZW5kID0gcmFuZ2UuZW5kQ29udGFpbmVyO1xuICAgICAgICAgICAgICAgIHZhciBjb21tb24gPSByYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcjtcbiAgICAgICAgICAgICAgICB2YXIgY2hpbGRyZW4gPSByYW5nZS5jbG9uZUNvbnRlbnRzKCkuY2hpbGROb2RlcywgaSA9IDAsIGwgPSBjaGlsZHJlbi5sZW5ndGg7XG5cbiAgICAgICAgICAgICAgICB2YXIgbGlzdCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhjb21tb24sIFsndWwnLCAnb2wnXSk7XG4gICAgICAgICAgICAgICAgaWYgKCEhbGlzdCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKCcubXdfZWRpdG9yXycgKyBsaXN0Lm5vZGVOYW1lLnRvTG93ZXJDYXNlKCkpLmFkZENsYXNzKCdtd19lZGl0b3JfYnRuX2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoY29tbW9uLm5vZGVUeXBlICE9PSAzKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBjb21tb25DU1MgPSBtdy5DU1NQYXJzZXIoY29tbW9uKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGFsaWduID0gY29tbW9uQ1NTLmdldC5hbGlnbk5vcm1hbGl6ZSgpO1xuICAgICAgICAgICAgICAgICAgICBtdy4kKFwiLm13X2VkaXRvcl9hbGlnbm1lbnRcIikucmVtb3ZlQ2xhc3MoJ213X2VkaXRvcl9idG5fYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoXCIubXctYWxpZ24tXCIgKyBhbGlnbikuYWRkQ2xhc3MoJ213X2VkaXRvcl9idG5fYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihjaGlsZHJlbltpXS5ub2RlTmFtZSl7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLnNldEFjdGl2ZUJ1dHRvbnMoY2hpbGRyZW5baV0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgY29tbW9uLnBhcmVudEVsZW1lbnQgIT09ICd1bmRlZmluZWQnICYmIGNvbW1vbi5wYXJlbnRFbGVtZW50ICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgYWxpZ24gPSBtdy5DU1NQYXJzZXIoY29tbW9uLnBhcmVudEVsZW1lbnQpLmdldC5hbGlnbk5vcm1hbGl6ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5td19lZGl0b3JfYWxpZ25tZW50XCIpLnJlbW92ZUNsYXNzKCdtd19lZGl0b3JfYnRuX2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5tdy1hbGlnbi1cIiArIGFsaWduKS5hZGRDbGFzcygnbXdfZWRpdG9yX2J0bl9hY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2V0QWN0aXZlQnV0dG9ucyhjb21tb24ucGFyZW50RWxlbWVudCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcuaXNGb3JtYXRFbGVtZW50KGNvbW1vbikpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZvcm1hdCA9IGNvbW1vbi5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZGR2YWwgPSBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb3JtYXRcIik7XG4gICAgICAgICAgICAgICAgICAgIGlmIChkZHZhbC5sZW5ndGggIT0gMCAmJiBkZHZhbC5zZXREcm9wZG93blZhbHVlICE9IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9ybWF0XCIpLnNldERyb3Bkb3duVmFsdWUoZm9ybWF0KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHMoY29tbW9uLCBmdW5jdGlvbiAobG9vcCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcuaXNGb3JtYXRFbGVtZW50KHRoaXMpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGZvcm1hdCA9IHRoaXMubm9kZU5hbWUudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgZGR2YWwgPSBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb3JtYXRcIik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGRkdmFsLmxlbmd0aCAhPSAwICYmIGRkdmFsLnNldERyb3Bkb3duVmFsdWUgIT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoXCIubXdfZHJvcGRvd25fYWN0aW9uX2Zvcm1hdFwiKS5zZXREcm9wZG93blZhbHVlKGZvcm1hdCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLnN0b3BMb29wKGxvb3ApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5zZXRBY3RpdmVGb250U2l6ZSgpO1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2V0RGVjb3JhdG9ycyhzZWxlY3Rpb24pXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICghIXRhcmdldCAmJiB0YXJnZXQubm9kZU5hbWUpIHtcblxuXG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5zZXRBY3RpdmVCdXR0b25zKHRhcmdldCk7XG4gICAgICAgICAgICAgICAgaWYgKHRhcmdldC50YWdOYW1lID09PSAnQScpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChcIi5td19lZGl0b3JfbGlua1wiKS5hZGRDbGFzcygnbXdfZWRpdG9yX2J0bl9hY3RpdmUnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgdmFyIHBhcmVudF9hID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKHRhcmdldCwgJ2EnKTtcbiAgICAgICAgICAgICAgICBpZiAoISFwYXJlbnRfYSkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKFwiLm13X2VkaXRvcl9saW5rXCIpLmFkZENsYXNzKCdtd19lZGl0b3JfYnRuX2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13Lnd5c2l3eWcuc3RhcnRlZF9jaGVja2luZyA9IGZhbHNlO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBsaW5rOiBmdW5jdGlvbiAodXJsLCBub2RlX2lkLCB0ZXh0KSB7XG4gICAgICAgIG13LnJlcXVpcmUoJ2V4dGVybmFsX2NhbGxiYWNrcy5qcycpO1xuICAgICAgICBtdy53eXNpd3lnLnNhdmVfc2VsZWN0aW9uKCk7XG4gICAgICAgIHZhciBlbCA9IG5vZGVfaWQgPyBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChub2RlX2lkKSA6IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhnZXRTZWxlY3Rpb24oKS5mb2N1c05vZGUsICdhJyk7XG4gICAgICAgIHZhciB2YWw7XG4gICAgICAgIHZhciBzZWwgPSBnZXRTZWxlY3Rpb24oKTtcblxuICAgICAgICBpZihlbCkge1xuICAgICAgICAgICAgdmFsID0ge1xuICAgICAgICAgICAgICAgIHVybDogdXJsIHx8IGVsLmhyZWYsXG4gICAgICAgICAgICAgICAgdGV4dDogdGV4dCB8fCBlbC5pbm5lckhUTUwsXG4gICAgICAgICAgICAgICAgdGFyZ2V0OiBlbC50YXJnZXQgPT09ICdfYmxhbmsnXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfSBlbHNlIGlmKCFzZWwuaXNDb2xsYXBzZWQpIHtcbiAgICAgICAgICAgIHZhciBodG1sID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICBpZihzZWwucmFuZ2VDb3VudCkge1xuICAgICAgICAgICAgICAgIHZhciBmcmFnID0gc2VsLmdldFJhbmdlQXQoMCkuY2xvbmVDb250ZW50cygpO1xuICAgICAgICAgICAgICAgIHdoaWxlIChmcmFnLmZpcnN0Q2hpbGQpIHtcbiAgICAgICAgICAgICAgICAgICAgaHRtbC5hcHBlbmQoZnJhZy5maXJzdENoaWxkKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YWwgPSB7XG4gICAgICAgICAgICAgICAgdGV4dDogdGV4dCB8fCBodG1sLmlubmVySFRNTCxcbiAgICAgICAgICAgICAgICB1cmw6IHVybCB8fCAnJ1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgbmV3IG13LkxpbmtFZGl0b3Ioe1xuICAgICAgICAgICAgbW9kZTogJ2RpYWxvZydcbiAgICAgICAgfSlcbiAgICAgICAgLnNldFZhbHVlKHZhbClcbiAgICAgICAgLnByb21pc2UoKVxuICAgICAgICAudGhlbihmdW5jdGlvbiAocmVzdWx0KXtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcucmVzdG9yZV9zZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIG13LmlmcmFtZWNhbGxiYWNrcy5pbnNlcnRfbGluayhyZXN1bHQsIChyZXN1bHQudGFyZ2V0ID8gJ19ibGFuaycgOiAnX3NlbGYnKSAsIHJlc3VsdC50ZXh0KTtcbiAgICAgICAgfSk7XG5cblxuXG4gICAgfSxcblxuICAgIHVubGluazogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgc2VsID0gd2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgICAgICBpZiAoIXNlbC5pc0NvbGxhcHNlZCkge1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgndW5saW5rJywgbnVsbCwgbnVsbCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICB2YXIgbGluayA9IG13Lnd5c2l3eWcuZmluZFRhZ0Fjcm9zc1NlbGVjdGlvbignYScpO1xuICAgICAgICAgICAgaWYgKCEhbGluaykge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0X2VsZW1lbnQobGluayk7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgndW5saW5rJywgbnVsbCwgbnVsbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgbXcuJChcIi5td19lZGl0b3JfbGlua1wiKS5yZW1vdmVDbGFzcyhcIm13X2VkaXRvcl9idG5fYWN0aXZlXCIpO1xuICAgIH0sXG4gICAgZmluZFRhZ0Fjcm9zc1NlbGVjdGlvbjogZnVuY3Rpb24gKHRhZywgc2VsZWN0aW9uKSB7XG4gICAgICAgIHZhciBzZWxlY3Rpb24gPSBzZWxlY3Rpb24gfHwgd2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgICAgICBpZiAoc2VsZWN0aW9uLmFuY2hvck5vZGUubm9kZU5hbWUudG9Mb3dlckNhc2UoKSA9PT0gdGFnKSB7XG4gICAgICAgICAgICByZXR1cm4gc2VsZWN0aW9uLmFuY2hvck5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHJhbmdlID0gc2VsZWN0aW9uLmdldFJhbmdlQXQoMCk7XG4gICAgICAgIHZhciBjb21tb24gPSByYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcjtcbiAgICAgICAgdmFyIHBhcmVudCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhjb21tb24sIFt0YWddKTtcbiAgICAgICAgaWYgKCEhcGFyZW50KSB7XG4gICAgICAgICAgICByZXR1cm4gcGFyZW50O1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YgY29tbW9uLnF1ZXJ5U2VsZWN0b3JBbGwgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICB2YXIgaXRlbXMgPSBjb21tb24ucXVlcnlTZWxlY3RvckFsbCh0YWcpLCBsID0gaXRlbXMubGVuZ3RoLCBpID0gMCwgYXJyID0gW107XG4gICAgICAgICAgICBpZiAobCA+IDApIHtcbiAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICBpZiAoc2VsZWN0aW9uLmNvbnRhaW5zTm9kZShpdGVtc1tpXSwgdHJ1ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGFyci5wdXNoKGl0ZW1zW2ldKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChhcnIubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gYXJyLmxlbmd0aCA9PT0gMSA/IGFyclswXSA6IGFycjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgaW1hZ2VfbGluazogZnVuY3Rpb24gKHVybCkge1xuICAgICAgICBtdy4kKFwiaW1nLmVsZW1lbnQtY3VycmVudFwiKS53cmFwKFwiPGEgaHJlZj0nXCIgKyB1cmwgKyBcIic+PC9hPlwiKTtcbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UoJy5lbGVtZW50LWN1cnJlbnQnKTtcbiAgICB9LFxuICAgIHJlcXVlc3RfbWVkaWE6IGZ1bmN0aW9uIChoYXNoLCB0eXBlcykge1xuICAgICAgICBtdy5yZXF1aXJlKCdleHRlcm5hbF9jYWxsYmFja3MuanMnKTtcbiAgICAgICAgdHlwZXMgPSB0eXBlcyB8fCBmYWxzZTtcbiAgICAgICAgaWYgKGhhc2ggPT09ICcjZWRpdGltYWdlJykge1xuICAgICAgICAgICAgdHlwZXMgPSAnaW1hZ2VzJztcbiAgICAgICAgICAgIC8vaGFzaCA9ICdub29wJztcbiAgICAgICAgfVxuICAgICAgICB2YXIgdXJsID0gISF0eXBlcyA/IFwicnRlX2ltYWdlX2VkaXRvcj90eXBlcz1cIiArIHR5cGVzICsgJycgKyBoYXNoIDogXCJydGVfaW1hZ2VfZWRpdG9yXCIgKyBoYXNoO1xuXG4gICAgICAgIHVybCA9IG13LnNldHRpbmdzLnNpdGVfdXJsICsgJ2VkaXRvcl90b29scy8nICsgdXJsO1xuICAgICAgICB2YXIgc2VsID0gbXcud3lzaXd5Zy5zYXZlX3NlbGVjdGlvbigpO1xuICAgICAgICB2YXIgbW9kYWwgPSBtdy50b3AoKS5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgdXJsOiB1cmwsXG4gICAgICAgICAgICBuYW1lOiBcIm13X3J0ZV9pbWFnZVwiLFxuICAgICAgICAgICAgd2lkdGg6IDQ2MCxcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgYXV0b0hlaWdodDp0cnVlLFxuICAgICAgICAgICAgb3ZlcmxheTogdHJ1ZVxuICAgICAgICB9LCBmdW5jdGlvbihyZXMpIHtcbiAgICAgICAgICAgIGlmKGhhc2ggPT09ICcjc2V0X2JnX2ltYWdlJyl7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5zZXRfYmdfaW1hZ2UocmVzKTtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIG13Lnd5c2l3eWcucmVzdG9yZV9zZWxlY3Rpb24oKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUoXCJmaWxlcy5qc1wiKTtcblxuICAgICAgICAgICAgaWYoaGFzaCA9PT0gJyNlZGl0aW1hZ2UnKSB7XG4gICAgICAgICAgICAgICAgaWYobXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5pbWFnZS5jdXJyZW50UmVzaXppbmdbMF0ubm9kZU5hbWUgPT09ICdJTUcnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZS5jdXJyZW50UmVzaXppbmcuYXR0cihcInNyY1wiLCByZXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nLmNzcygnaGVpZ2h0JywgJ2F1dG8nKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmltYWdlLmN1cnJlbnRSZXNpemluZy5jc3MoXCJiYWNrZ3JvdW5kSW1hZ2VcIiwgJ3VybCgnICsgbXcuZmlsZXMuc2FmZUZpbGVuYW1lKHJlcykgKyAnKScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYocGFyZW50Lm13LmltYWdlLmN1cnJlbnRSZXNpemluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5iZ1F1b3Rlc0ZpeChwYXJlbnQubXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdKVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmKG13LmltYWdlLmN1cnJlbnRSZXNpemluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZS5jdXJyZW50UmVzaXppbmcubG9hZChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZVJlc2l6ZS5yZXNpemVyU2V0KHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBpZihyZXMuaW5kZXhPZignPCcpICE9PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmluc2VydF9odG1sKHJlcyk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRNZWRpYShyZXMpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5yZW1vdmUoKTtcblxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIG1lZGlhOiBmdW5jdGlvbiAoYWN0aW9uKSB7XG5cbiAgICAgICAgaWYgKG13LnNldHRpbmdzLmxpdmVFZGl0ICYmIHR5cGVvZiBtdy50YXJnZXQuaXRlbSA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybiBmYWxzZTtcbiAgICAgICAgYWN0aW9uID0gYWN0aW9uIHx8ICdpbnNlcnRfaHRtbCc7XG4gICAgICAgIGFjdGlvbiA9IGFjdGlvbi5yZXBsYWNlKC8jL2csICcnKTtcblxuICAgICAgICBpZiAobXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkgfHwgbXcuJChtdy50YXJnZXQuaXRlbSkuaGFzQ2xhc3MoXCJpbWFnZV9jaGFuZ2VcIikgfHwgbXcuJChtdy50YXJnZXQuaXRlbS5wYXJlbnROb2RlKS5oYXNDbGFzcyhcImltYWdlX2NoYW5nZVwiKSB8fCBtdy50YXJnZXQuaXRlbSA9PT0gbXcuaW1hZ2VfcmVzaXplcikge1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5zYXZlX3NlbGVjdGlvbigpO1xuICAgICAgICAgICAgdmFyIGRpYWxvZztcbiAgICAgICAgICAgIHZhciBoYW5kbGVSZXN1bHQgPSBmdW5jdGlvbiAocmVzKSB7XG4gICAgICAgICAgICAgICAgdmFyIHVybCA9IHJlcy5zcmMgPyByZXMuc3JjIDogcmVzO1xuICAgICAgICAgICAgICAgIGlmKGFjdGlvbiA9PT0gJ2VkaXRpbWFnZScpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYobXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAobXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdLm5vZGVOYW1lID09PSAnSU1HJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmltYWdlLmN1cnJlbnRSZXNpemluZy5hdHRyKFwic3JjXCIsIHVybCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nLmNzcygnaGVpZ2h0JywgJ2F1dG8nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmltYWdlLmN1cnJlbnRSZXNpemluZy5jc3MoXCJiYWNrZ3JvdW5kSW1hZ2VcIiwgJ3VybCgnICsgbXcuZmlsZXMuc2FmZUZpbGVuYW1lKHVybCkgKyAnKScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmKHBhcmVudC5tdy5pbWFnZS5jdXJyZW50UmVzaXppbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5iZ1F1b3Rlc0ZpeChwYXJlbnQubXcuaW1hZ2UuY3VycmVudFJlc2l6aW5nWzBdKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKG13LmltYWdlLmN1cnJlbnRSZXNpemluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKG13LmltYWdlLmN1cnJlbnRSZXNpemluZ1swXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5pbWFnZS5jdXJyZW50UmVzaXppbmcubG9hZChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuaW1hZ2VSZXNpemUucmVzaXplclNldCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmluc2VydE1lZGlhKHVybCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGRpYWxvZy5yZW1vdmUoKVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHBpY2tlciA9IG5ldyBtdy5maWxlUGlja2VyKHtcbiAgICAgICAgICAgICAgICB0eXBlOiAnaW1hZ2VzJyxcbiAgICAgICAgICAgICAgICBsYWJlbDogZmFsc2UsXG4gICAgICAgICAgICAgICAgYXV0b1NlbGVjdDogZmFsc2UsXG4gICAgICAgICAgICAgICAgZm9vdGVyOiB0cnVlLFxuICAgICAgICAgICAgICAgIF9mcmFtZU1heEhlaWdodDogdHJ1ZSxcbiAgICAgICAgICAgICAgICBmaWxlVXBsb2FkZWQ6IGZ1bmN0aW9uIChmaWxlKSB7XG4gICAgICAgICAgICAgICAgICAgIGhhbmRsZVJlc3VsdChmaWxlLnNyYyk7XG4gICAgICAgICAgICAgICAgICAgIGRpYWxvZy5yZW1vdmUoKVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgb25SZXN1bHQ6IGhhbmRsZVJlc3VsdCxcbiAgICAgICAgICAgICAgICBjYW5jZWw6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBkaWFsb2cgPSBtdy5kaWFsb2coe1xuICAgICAgICAgICAgICAgIGNvbnRlbnQ6IHBpY2tlci5yb290LFxuICAgICAgICAgICAgICAgIHRpdGxlOiBtdy5sYW5nKCdTZWxlY3QgaW1hZ2UnKSxcbiAgICAgICAgICAgICAgICBmb290ZXI6IGZhbHNlXG4gICAgICAgICAgICB9KVxuXG5cbiAgICAgICAgfVxuXG4gICAgfSxcbiAgICByZXF1ZXN0X2JnX2ltYWdlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13Lnd5c2l3eWcucmVxdWVzdF9tZWRpYSgnI3NldF9iZ19pbWFnZScpO1xuICAgIH0sXG4gICAgc2V0X2JnX2ltYWdlOiBmdW5jdGlvbiAodXJsKSB7XG4gICAgICAgIG13LiQoXCIuZWxlbWVudC1jdXJyZW50XCIpLmNzcyhcImJhY2tncm91bmRJbWFnZVwiLCBcInVybChcIiArIHVybCArIFwiKVwiKTtcbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UoJy5lbGVtZW50LWN1cnJlbnQnKTtcbiAgICB9LFxuICAgIGluc2VydF9odG1sOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICBpZiAodHlwZW9mIGh0bWwgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICB2YXIgaXNlbWJlZCA9IGh0bWwuY29udGFpbnMoJzxpZnJhbWUnKSB8fCBodG1sLmNvbnRhaW5zKCc8ZW1iZWQnKSB8fCBodG1sLmNvbnRhaW5zKCc8b2JqZWN0Jyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICB2YXIgaXNlbWJlZCA9IGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChpc2VtYmVkKSB7XG4gICAgICAgICAgICB2YXIgaWQgPSAnZnJhbWUtJyArIG13LnJhbmRvbSgpO1xuICAgICAgICAgICAgdmFyIGZyYW1lID0gaHRtbDtcbiAgICAgICAgICAgIGh0bWwgPSAnPHNwYW4gaWQ9XCInICsgaWQgKyAnXCI+PC9zcGFuPic7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCEhd2luZG93Lk1TU3RyZWFtKSB7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnJlc3RvcmVfc2VsZWN0aW9uKCk7XG4gICAgICAgICAgICBpZiAobXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmFuZ2UgPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCkuZ2V0UmFuZ2VBdCgwKTtcbiAgICAgICAgICAgICAgICB2YXIgZWwgPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgICAgIGVsLmlubmVySFRNTCA9IGh0bWw7XG4gICAgICAgICAgICAgICAgcmFuZ2UuaW5zZXJ0Tm9kZShlbCk7XG4gICAgICAgICAgICAgICAgbXcuJChlbCkucmVwbGFjZVdpdGgoZWwuaW5uZXJIVE1MKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGlmICghZG9jdW1lbnQuc2VsZWN0aW9uKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgnaW5zZXJ0aHRtbCcsIGZhbHNlLCBodG1sKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LnNlbGVjdGlvbi5jcmVhdGVSYW5nZSgpLnBhc3RlSFRNTChodG1sKVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGlmIChpc2VtYmVkKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBtd2QuZ2V0RWxlbWVudEJ5SWQoaWQpO1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5jb250ZW50RWRpdGFibGUoZWwucGFyZW50Tm9kZSwgZmFsc2UpO1xuICAgICAgICAgICAgbXcuJChlbCkucmVwbGFjZVdpdGgoZnJhbWUpO1xuICAgICAgICB9XG4gICAgICAgIHZhciBzZWwgPSBnZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgaWYoc2VsLnJhbmdlQ291bnQpe1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKHNlbC5nZXRSYW5nZUF0KDApLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKSk7XG5cbiAgICAgICAgfVxuICAgIH0sXG4gICAgc2VsZWN0aW9uX2xlbmd0aDogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgbiA9IHdpbmRvdy5nZXRTZWxlY3Rpb24oKS5nZXRSYW5nZUF0KDApLmNsb25lQ29udGVudHMoKS5jaGlsZE5vZGVzLFxuICAgICAgICAgICAgbCA9IG4ubGVuZ3RoLFxuICAgICAgICAgICAgaSA9IDA7XG4gICAgICAgIHZhciBmaW5hbCA9IDA7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICB2YXIgaXRlbSA9IG5baV07XG4gICAgICAgICAgICBpZiAoaXRlbS5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgICAgIGZpbmFsID0gZmluYWwgKyBpdGVtLnRleHRDb250ZW50Lmxlbmd0aDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKGl0ZW0ubm9kZVR5cGUgPT09IDMpIHtcbiAgICAgICAgICAgICAgICBmaW5hbCA9IGZpbmFsICsgaXRlbS5ub2RlVmFsdWUubGVuZ3RoO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmaW5hbDtcbiAgICB9LFxuICAgIGluc2VydE1lZGlhOiBmdW5jdGlvbiAodXJsLCB0eXBlKSB7XG4gICAgICAgIHZhciBleHQgPSB1cmwuc3BsaXQoJy4nKS5wb3AoKS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICB2YXIgbmFtZSA9IHVybC5zcGxpdCgnLycpLnBvcCgpXG4gICAgICAgIGlmKCF0eXBlKSB7XG4gICAgICAgICAgICBpZihbJ3BuZycsJ2dpZicsJ2pwZycsJ2pwZWcnLCd0aWZmJywnYm1wJywnc3ZnJ10uaW5kZXhPZihleHQpICE9PSAtMSkge1xuICAgICAgICAgICAgICAgIHR5cGUgPSAnaW1hZ2UnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoWydtcDQnLCdvZ2cnXS5pbmRleE9mKGV4dCkgIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgdHlwZSA9ICd2aWRlbyc7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYodHlwZSA9PT0gJ2ltYWdlJykge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuaW5zZXJ0X2ltYWdlKHVybCk7XG4gICAgICAgIH0gZWxzZSBpZih0eXBlID09PSAndmlkZW8nKSB7XG4gICAgICAgICAgICB2YXIgaWQgPSAnaW1hZ2VfJyArIG13LnJhbmRvbSgpO1xuICAgICAgICAgICAgdmFyIGltZyA9ICc8c3BhbiBjbGFzcz1cIm13ZW1iZWRcIj48dmlkZW8gaWQ9XCInICsgaWQgKyAnXCIgY29udGVudEVkaXRhYmxlPVwiZmFsc2VcIiBzcmM9XCInICsgdXJsICsgJ1wiIGNvbnRyb2xzPjwvdmlkZW8+PC9zcGFuPic7XG4gICAgICAgICAgICBtdy53eXNpd3lnLmluc2VydF9odG1sKGltZyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB2YXIgaWQgPSAnaW1hZ2VfJyArIG13LnJhbmRvbSgpO1xuICAgICAgICAgICAgdmFyIGltZyA9ICc8YSBpZD1cIicgKyBpZCArICdcIiBjb250ZW50RWRpdGFibGU9XCJ0cnVlXCIgc3JjPVwiJyArIHVybCArICdcIj4nK25hbWUrJzwvYT4nO1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbChpbWcpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBpbnNlcnRfaW1hZ2U6IGZ1bmN0aW9uICh1cmwpIHtcbiAgICAgICAgdmFyIGlkID0gJ2ltYWdlXycgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgdmFyIGltZyA9ICc8aW1nIGlkPVwiJyArIGlkICsgJ1wiIGNvbnRlbnRFZGl0YWJsZT1cInRydWVcIiBjbGFzcz1cImVsZW1lbnRcIiBzcmM9XCInICsgdXJsICsgJ1wiIC8+JztcbiAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbChpbWcpO1xuICAgICAgICBtdy5zZXR0aW5ncy5saXZlRWRpdCA/IG13LiQoXCIjXCIgKyBpZCkuYXR0cihcImNvbnRlbnRlZGl0YWJsZVwiLCBmYWxzZSkgOiAnJztcbiAgICAgICAgbXcuJChcIiNcIiArIGlkKS5yZW1vdmVBdHRyKFwiX21vel9kaXJ0eVwiKTtcbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXdkLmdldEVsZW1lbnRCeUlkKGlkKSk7XG4gICAgICAgIHJldHVybiBpZDtcbiAgICB9LFxuICAgIHNhdmVfc2VsZWN0aW9uOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBzZWxlY3Rpb24gPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgIGlmIChzZWxlY3Rpb24ucmFuZ2VDb3VudCA+IDApIHtcbiAgICAgICAgICAgIHZhciByYW5nZSA9IHNlbGVjdGlvbi5nZXRSYW5nZUF0KDApO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdmFyIHJhbmdlID0gbXdkLmNyZWF0ZVJhbmdlKCk7XG4gICAgICAgICAgICByYW5nZS5zZWxlY3ROb2RlKG13ZC5xdWVyeVNlbGVjdG9yKCcuZWRpdCAuZWxlbWVudCcpKTtcbiAgICAgICAgfVxuICAgICAgICBtdy53eXNpd3lnLnNlbGVjdGlvbiA9IHt9O1xuICAgICAgICBtdy53eXNpd3lnLnNlbGVjdGlvbi5zZWwgPSBzZWxlY3Rpb247XG4gICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0aW9uLnJhbmdlID0gcmFuZ2U7XG4gICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0aW9uLmVsZW1lbnQgPSBtdy4kKG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihyYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lcikpO1xuICAgIH0sXG4gICAgcmVzdG9yZV9zZWxlY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCEhbXcud3lzaXd5Zy5zZWxlY3Rpb24pIHtcbiAgICAgICAgICAgIG13Lnd5c2l3eWcuc2VsZWN0aW9uLmVsZW1lbnQuYXR0cihcImNvbnRlbnRlZGl0YWJsZVwiLCBcInRydWVcIik7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdGlvbi5lbGVtZW50LmZvY3VzKCk7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdGlvbi5zZWwucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgICAgICBtdy53eXNpd3lnLnNlbGVjdGlvbi5zZWwuYWRkUmFuZ2UobXcud3lzaXd5Zy5zZWxlY3Rpb24ucmFuZ2UpXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHNlbGVjdF9hbGw6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICB2YXIgcmFuZ2UgPSBkb2N1bWVudC5jcmVhdGVSYW5nZSgpO1xuICAgICAgICByYW5nZS5zZWxlY3ROb2RlQ29udGVudHMoZWwpO1xuICAgICAgICB2YXIgc2VsZWN0aW9uID0gd2luZG93LmdldFNlbGVjdGlvbigpO1xuICAgICAgICBzZWxlY3Rpb24ucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgIHNlbGVjdGlvbi5hZGRSYW5nZShyYW5nZSk7XG4gICAgfSxcbiAgICBzZWxlY3RfZWxlbWVudDogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHZhciByYW5nZSA9IGRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICByYW5nZS5zZWxlY3ROb2RlKGVsKTtcbiAgICAgICAgICAgIHZhciBzZWxlY3Rpb24gPSB3aW5kb3cuZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICBzZWxlY3Rpb24ucmVtb3ZlQWxsUmFuZ2VzKCk7XG4gICAgICAgICAgICBzZWxlY3Rpb24uYWRkUmFuZ2UocmFuZ2UpO1xuICAgICAgICB9IGNhdGNoIChlKSB7XG5cbiAgICAgICAgfVxuICAgIH0sXG4gICAgZm9ybWF0TmF0aXZlOiBmdW5jdGlvbiAoY29tbWFuZCkge1xuICAgICAgICB2YXIgZWwgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIod2luZG93LmdldFNlbGVjdGlvbigpLmZvY3VzTm9kZSk7XG4gICAgICAgIGlmIChtdy53eXNpd3lnLmlzU2FmZU1vZGUoKSkge1xuICAgICAgICAgICAgbXcuJCgnW2NvbnRlbnRlZGl0YWJsZV0nKS5yZW1vdmVBdHRyKCdjb250ZW50ZWRpdGFibGUnKTtcbiAgICAgICAgICAgIHZhciBwYXJlbnQgPSBtdy50b29scy5maXJzdEJsb2NrTGV2ZWwoZWwucGFyZW50Tm9kZSk7XG4gICAgICAgICAgICBwYXJlbnQuY29udGVudEVkaXRhYmxlID0gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICBtdy53eXNpd3lnLmV4ZWNDb21tYW5kKCdmb3JtYXRCbG9jaycsIGZhbHNlLCAnPCcgKyBjb21tYW5kICsgJz4nKTtcbiAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgnZm9ybWF0QmxvY2snLCBmYWxzZSwgY29tbWFuZCApO1xuICAgIH0sXG4gICAgZm9ybWF0OiBmdW5jdGlvbiAoY29tbWFuZCkge1xuICAgICAgICB2YXIgdGFyZ2V0ID0gbXcud3lzaXd5Zy52YWxpZGF0ZUNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKGdldFNlbGVjdGlvbigpLmdldFJhbmdlQXQoMCkuY29tbW9uQW5jZXN0b3JDb250YWluZXIpO1xuICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICAgICB0YXJnZXQ6IHRhcmdldC5wYXJlbnROb2RlLFxuICAgICAgICAgICAgdmFsdWU6IHRhcmdldC5wYXJlbnROb2RlLmlubmVySFRNTFxuICAgICAgICB9KTtcbiAgICAgICAgdmFyIGVsID0gbXcudG9vbHMuc2V0VGFnKHRhcmdldCwgY29tbWFuZCk7XG4gICAgICAgIG13Lnd5c2l3eWcuY3Vyc29yVG9FbGVtZW50KGVsLCAnZW5kJyk7XG4gICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcbiAgICAgICAgICAgIHRhcmdldDogZWwucGFyZW50Tm9kZSxcbiAgICAgICAgICAgIHZhbHVlOiBlbC5wYXJlbnROb2RlLmlubmVySFRNTFxuICAgICAgICB9KTtcbiAgICAgICAgLy8gcmV0dXJuIHRoaXMuZm9ybWF0TmF0aXZlKGNvbW1hbmQpO1xuICAgIH0sXG4gICAgZm9udEZhbWlsaWVzOiBbJ0FyaWFsJywgJ1RhaG9tYScsICdWZXJkYW5hJywgJ0dlb3JnaWEnLCAnVGltZXMgTmV3IFJvbWFuJ10sXG4gICAgZm9udEZhbWlsaWVzRXh0ZW5kZWQ6IFtdLFxuICAgIGZvbnRGYW1pbGllc1RlbXBsYXRlOiBbXSxcbiAgICBpbml0Rm9udFNlbGVjdG9yQm94OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13Lnd5c2l3eWcuaW5pdEZvbnRGYW1pbGllcygpO1xuXG4gICAgICAgIHZhciBsID0gbXcud3lzaXd5Zy5mb250RmFtaWxpZXMubGVuZ3RoLCBpID0gMCwgaHRtbCA9ICcnO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuXG4gICAgICAgICAgICBodG1sICs9ICc8bGkgdmFsdWU9XCInICsgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNbaV0gKyAnXCI+PGEgc3R5bGU9XCJmb250LWZhbWlseTonICsgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNbaV0gKyAnXCIgaHJlZj1cImphdmFzY3JpcHQ6O1wiPicgKyBtdy53eXNpd3lnLmZvbnRGYW1pbGllc1tpXSArICc8L2E+PC9saT4nXG4gICAgICAgIH1cblxuICAgICAgICB2YXIgbCA9IG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzVGVtcGxhdGUubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIGlmIChtdy53eXNpd3lnLmZvbnRGYW1pbGllcy5pbmRleE9mKG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzVGVtcGxhdGVbaV0pID09PSAtMSAmJiBtdy53eXNpd3lnLmZvbnRGYW1pbGllc1RlbXBsYXRlW2ldICE9ICcnKSB7XG4gICAgICAgICAgICAgICAgaHRtbCArPSAnPGxpIHZhbHVlPVwiJyArIG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzVGVtcGxhdGVbaV0gKyAnXCI+PGEgc3R5bGU9XCJmb250LWZhbWlseTonICsgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNUZW1wbGF0ZVtpXSArICdcIiBocmVmPVwiamF2YXNjcmlwdDo7XCI+JyArIG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzVGVtcGxhdGVbaV0gKyAnPC9hPjwvbGk+J1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHZhciBsID0gbXcud3lzaXd5Zy5mb250RmFtaWxpZXNFeHRlbmRlZC5sZW5ndGgsIGkgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzLmluZGV4T2YobXcud3lzaXd5Zy5mb250RmFtaWxpZXNFeHRlbmRlZFtpXSkgPT09IC0xICYmIG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzRXh0ZW5kZWRbaV0gIT0gJycpIHtcbiAgICAgICAgICAgICAgICBodG1sICs9ICc8bGkgdmFsdWU9XCInICsgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNFeHRlbmRlZFtpXSArICdcIj48YSBzdHlsZT1cImZvbnQtZmFtaWx5OicgKyBtdy53eXNpd3lnLmZvbnRGYW1pbGllc0V4dGVuZGVkW2ldICsgJ1wiIGhyZWY9XCJqYXZhc2NyaXB0OjtcIj4nICsgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNFeHRlbmRlZFtpXSArICc8L2E+PC9saT4nXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X2ZhbWlseSB1bFwiKS5lbXB0eSgpLmFwcGVuZChodG1sKTtcblxuICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X2ZhbWlseVwiKS5vZmYoJ2NoYW5nZScpO1xuICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X2ZhbWlseVwiKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHZhbCA9IG13LiQodGhpcykuZ2V0RHJvcGRvd25WYWx1ZSgpO1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5mb250RmFtaWx5KHZhbCk7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKFwiLm13X2Ryb3Bkb3duX2FjdGlvbl9mb250X2ZhbWlseVwiKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LiQoXCJbdmFsdWVdXCIsIG13LiQodGhpcykpLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgICAgIG13LiQobXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3ModGhpcywgJ213LWRyb3Bkb3duJykpLnNldERyb3Bkb3duVmFsdWUodGhpcy5nZXRBdHRyaWJ1dGUoJ3ZhbHVlJyksIHRydWUpO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9LFxuXG4gICAgaW5pdEZvbnRGYW1pbGllczogZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAod2luZG93LmdldENvbXB1dGVkU3R5bGUobXdkLmJvZHkpID09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBib2R5X2ZvbnQgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShtd2QuYm9keSwgbnVsbCkuZm9udEZhbWlseS5zcGxpdCgnLCcpWzBdLnJlcGxhY2UoLycvZywgXCJcIikucmVwbGFjZSgvXCIvZywgJycpO1xuICAgICAgICBpZiAobXcud3lzaXd5Zy5mb250RmFtaWxpZXMuaW5kZXhPZihib2R5X2ZvbnQpID09PSAtMSkge1xuICAgICAgICAgICAgbXcud3lzaXd5Zy5mb250RmFtaWxpZXMucHVzaChib2R5X2ZvbnQpO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIHNjYW5fZm9yX2ZvbnRzID0gWydib2R5JywgJ2h0bWwnLCAnaDEnLCAnaDInLCAnaDMnLCAnaDQnLCAnaDUnLCAncCcsICdhW2NsYXNzXSddO1xuXG4gICAgICAgICQuZWFjaChzY2FuX2Zvcl9mb250cywgZnVuY3Rpb24gKGluZGV4LCB2YWx1ZSkge1xuICAgICAgICAgICAgdmFyIHNlbCA9IG13LiQoZG9jdW1lbnQucXVlcnlTZWxlY3Rvcih2YWx1ZSkpO1xuICAgICAgICAgICAgaWYgKHNlbC5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgdmFyIGJvZHlfZm9udCA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKHNlbFswXSwgbnVsbCkuZm9udEZhbWlseS5zcGxpdCgnLCcpO1xuICAgICAgICAgICAgICAgICQuZWFjaChib2R5X2ZvbnQsIGZ1bmN0aW9uIChmb250X2luZGV4LCBmdmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZvbnRfdmFsdWUgPSBmdmFsdWU7XG4gICAgICAgICAgICAgICAgICAgIGZvbnRfdmFsdWUgPSBmb250X3ZhbHVlLnJlcGxhY2UoLycvZ2ksIFwiXCIpLnJlcGxhY2UoL1wiL2dpLCAnJyk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy53eXNpd3lnLmZvbnRGYW1pbGllcy5pbmRleE9mKGZvbnRfdmFsdWUpID09PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5mb250RmFtaWxpZXMucHVzaChmb250X3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGluaXRFeHRlbmRlZEZvbnRGYW1pbGllczogZnVuY3Rpb24gKHN0cmluZykge1xuICAgICAgICB2YXIgZmFtaWxpZXMgPSBbXTtcbiAgICAgICAgaWYgKHR5cGVvZihzdHJpbmcpID09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICBmYW1pbGllcyA9IHN0cmluZy5zcGxpdCgnLCcpXG4gICAgICAgIH0gZWxzZSBpZiAodHlwZW9mKHN0cmluZykgPT0gJ29iamVjdCcpIHtcbiAgICAgICAgICAgIGZhbWlsaWVzID0gc3RyaW5nXG4gICAgICAgIH1cbiAgICAgICAgJC5lYWNoKGZhbWlsaWVzLCBmdW5jdGlvbiAoZm9udF9pbmRleCwgZnZhbHVlKSB7XG4gICAgICAgICAgICB2YXIgZm9udF92YWx1ZSA9IGZ2YWx1ZTtcbiAgICAgICAgICAgIGZvbnRfdmFsdWUgPSBmb250X3ZhbHVlLnJlcGxhY2UoLycvZ2ksIFwiXCIpLnJlcGxhY2UoL1wiL2dpLCAnJyk7XG4gICAgICAgICAgICBpZiAobXcud3lzaXd5Zy5mb250RmFtaWxpZXMuaW5kZXhPZihmb250X3ZhbHVlKSA9PT0gLTEgJiYgbXcud3lzaXd5Zy5mb250RmFtaWxpZXNFeHRlbmRlZC5pbmRleE9mKGZvbnRfdmFsdWUpID09PSAtMSkge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZm9udEZhbWlsaWVzRXh0ZW5kZWQucHVzaChmb250X3ZhbHVlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBmb250SWNvbkZhbWlsaWVzOiBbJ2ZhcycsICdmYWInLCAnZmFyJywgJ2ZhJywgJ213LXVpLWljb24nLCAnbXctaWNvbicsICdtYXRlcmlhbC1pY29ucycsICdtdy13eXNpd3lnLWN1c3RvbS1pY29uJywgJ2ljb24nLCAnbWRpJ10sXG5cbiAgICBlbGVtZW50SGFzRm9udEljb25DbGFzczogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHZhciBpY29uX2NsYXNzZXMgPSBtdy53eXNpd3lnLmZvbnRJY29uRmFtaWxpZXM7XG4gICAgICAgIGlmIChlbC50YWdOYW1lID09PSAnSScgfHwgZWwudGFnTmFtZSA9PT0gJ1NQQU4nKSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKGVsLCBpY29uX2NsYXNzZXMpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmIChlbC5jbGFzc05hbWUuaW5kZXhPZignbXctbWljb24tJykgIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmIChlbC5jbGFzc05hbWUuaW5kZXhPZignbXctaWNvbi0nKSAhPT0gLTEpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHJldHVybiBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbnlPZkNsYXNzZXMoZWwucGFyZW50Tm9kZSwgaWNvbl9jbGFzc2VzKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgZmlyc3RFbGVtZW50VGhhdEhhc0ZvbnRJY29uQ2xhc3M6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICB2YXIgaWNvbl9jbGFzc2VzID0gbXcud3lzaXd5Zy5mb250SWNvbkZhbWlsaWVzLm1hcChmdW5jdGlvbiAodmFsdWUpIHtcbiAgICAgICAgICAgIHJldHVybiAnLicrdmFsdWVcbiAgICAgICAgfSk7XG4gICAgICAgIGljb25fY2xhc3Nlcy5wdXNoKCdbY2xhc3MqPVwibXctbWljb24tXCJdJyk7XG4gICAgICAgIHZhciBwID0gbXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQoZWwsIGljb25fY2xhc3Nlcyk7XG4gICAgICAgIGlmIChwICYmIChwLnRhZ05hbWUgPT09ICdJJyB8fCBwLnRhZ05hbWUgPT09ICdTUEFOJykpIHtcbiAgICAgICAgICAgIHJldHVybiBwO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBlbGVtZW50UmVtb3ZlRm9udEljb25DbGFzc2VzOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgdmFyIGwgPSBtdy53eXNpd3lnLmZvbnRJY29uRmFtaWxpZXMubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIHZhciBzZWFyY2hfY2xhc3MgPSBtdy53eXNpd3lnLmZvbnRJY29uRmFtaWxpZXNbaV1cbiAgICAgICAgICAgIG13LnRvb2xzLmNsYXNzTmFtZXNwYWNlRGVsZXRlKGVsLCBzZWFyY2hfY2xhc3MgKyAnLScpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBpZnJhbWVfZWRpdG9yOiBmdW5jdGlvbiAodGV4dGFyZWEsIGlmcmFtZV91cmwsIGNvbnRlbnRfdG9fc2V0KSB7XG4gICAgICAgIHZhciBjb250ZW50X3RvX3NldCA9IGNvbnRlbnRfdG9fc2V0IHx8IGZhbHNlO1xuICAgICAgICB2YXIgaWQgPSBtdy4kKHRleHRhcmVhKS5hdHRyKFwiaWRcIik7XG4gICAgICAgIG13LiQoXCIjaWZyYW1lX2VkaXRvcl9cIiArIGlkKS5yZW1vdmUoKTtcbiAgICAgICAgdmFyIHVybCA9IGlmcmFtZV91cmw7XG4gICAgICAgIHZhciBpZnJhbWUgPSBtd2QuY3JlYXRlRWxlbWVudCgnaWZyYW1lJyk7XG4gICAgICAgIGlmcmFtZS5jbGFzc05hbWUgPSAnbXctZWRpdG9yLWlmcmFtZS1sb2FkaW5nJztcbiAgICAgICAgaWZyYW1lLmlkID0gXCJpZnJhbWVfZWRpdG9yX1wiICsgaWQ7XG4gICAgICAgIGlmcmFtZS53aWR0aCA9IG13LiQodGV4dGFyZWEpLndpZHRoKCk7XG4gICAgICAgIGlmcmFtZS5oZWlnaHQgPSBtdy4kKHRleHRhcmVhKS5oZWlnaHQoKTtcbiAgICAgICAgaWZyYW1lLnNjcm9sbGluZyA9IFwibm9cIjtcbiAgICAgICAgaWZyYW1lLnNldEF0dHJpYnV0ZSgnZnJhbWVib3JkZXInLCAwKTtcbiAgICAgICAgaWZyYW1lLnNyYyA9IHVybDtcbiAgICAgICAgaWZyYW1lLnN0eWxlLnJlc2l6ZSA9ICd2ZXJ0aWNhbCc7XG4gICAgICAgIGlmcmFtZS5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZnJhbWUuY2xhc3NOYW1lID0gJ213LWVkaXRvci1pZnJhbWUtbG9hZGVkJztcbiAgICAgICAgICAgIHZhciBiID0gbXcuJCh0aGlzKS5jb250ZW50cygpLmZpbmQoXCIuZWRpdFwiKTtcbiAgICAgICAgICAgIHZhciBiID0gbXcuJCh0aGlzKS5jb250ZW50cygpLmZpbmQoXCJbZmllbGQ9J2NvbnRlbnQnXVwiKVswXTtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYiAhPSAndW5kZWZpbmVkJyAmJiBiICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jb250ZW50RWRpdGFibGUoYiwgdHJ1ZSlcbiAgICAgICAgICAgICAgICBtdy4kKGIpLm9uKFwiYmx1ciBrZXl1cFwiLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRleHRhcmVhLnZhbHVlID0gbXcuJCh0aGlzKS5odG1sKCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgaWYgKCEhY29udGVudF90b19zZXQpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChiKS5odG1sKGNvbnRlbnRfdG9fc2V0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcub24uRE9NQ2hhbmdlKGIsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdGV4dGFyZWEudmFsdWUgPSBtdy4kKHRoaXMpLmh0bWwoKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuYXNrdXNlcnRvc3RheSA9IHRydWU7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgbXcuJCh0ZXh0YXJlYSkuYWZ0ZXIoaWZyYW1lKTtcbiAgICAgICAgbXcuJCh0ZXh0YXJlYSkuaGlkZSgpO1xuICAgICAgICByZXR1cm4gaWZyYW1lO1xuICAgIH0sXG4gICAgd29yZF9saXN0aXRlbV9nZXRfbGV2ZWw6IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgIHZhciBtc3NwbCA9IGl0ZW0uZ2V0QXR0cmlidXRlKCdzdHlsZScpLnNwbGl0KCdtc28tbGlzdCcpO1xuICAgICAgICBpZiAobXNzcGwubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgdmFyIG1zc3BsaXRlbXMgPSBtc3NwbFsxXS5zcGxpdCgnICcpO1xuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBtc3NwbGl0ZW1zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG1zc3BsaXRlbXNbaV0uaW5kZXhPZignbGV2ZWwnKSAhPT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHBhcnNlSW50KG1zc3BsaXRlbXNbaV0uc3BsaXQoJ2xldmVsJylbMV0sIDEwKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgd29yZF9saXN0X2J1aWxkOiBmdW5jdGlvbiAobGlzdHMsIGNvdW50KSB7XG4gICAgICAgIHZhciBpLCBjaGVjayA9IGZhbHNlLCBtYXggPSAwO1xuICAgICAgICBjb3VudCA9IGNvdW50IHx8IDA7XG4gICAgICAgIGlmIChjb3VudCA9PT0gMCkge1xuICAgICAgICAgICAgZm9yIChpIGluIGxpc3RzKSB7XG4gICAgICAgICAgICAgICAgdmFyIGN1cnIgPSBsaXN0c1tpXTtcbiAgICAgICAgICAgICAgICBpZiAoIWN1cnIubm9kZU5hbWUgfHwgY3Vyci5ub2RlVHlwZSAhPT0gMSkgY29udGludWU7XG4gICAgICAgICAgICAgICAgdmFyICRjdXJyID0gbXcuJChjdXJyKTtcbiAgICAgICAgICAgICAgICBsaXN0c1tpXSA9IG13LnRvb2xzLnNldFRhZyhjdXJyLCAnbGknKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGxpc3RzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIG51bSA9IHRoaXMudGV4dENvbnRlbnQudHJpbSgpLnNwbGl0KCcuJylbMF0sIGNoZWNrID0gcGFyc2VJbnQobnVtLCAxMCk7XG4gICAgICAgICAgICB2YXIgY3VyciA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICBpZiAoIWN1cnIuYXR0cignZGF0YS10eXBlJykpIHtcbiAgICAgICAgICAgICAgICBpZiAoIWlzTmFOKGNoZWNrKSAmJiBudW0gPiAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdGhpcy5pbm5lckhUTUwucmVwbGFjZShudW0gKyAnLicsICcnKTtcbiAgICAgICAgICAgICAgICAgICAgY3Vyci5hdHRyKCdkYXRhLXR5cGUnLCAnb2wnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGN1cnIuYXR0cignZGF0YS10eXBlJywgJ3VsJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKCF0aGlzLl9fZG9uZSkge1xuICAgICAgICAgICAgICAgIHRoaXMuX19kb25lID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgdmFyIGxldmVsID0gcGFyc2VJbnQoJCh0aGlzKS5hdHRyKCdkYXRhLWxldmVsJykpO1xuICAgICAgICAgICAgICAgIGlmICghaXNOYU4obGV2ZWwpICYmIGxldmVsID4gbWF4KSB7XG4gICAgICAgICAgICAgICAgICAgIG1heCA9IGxldmVsO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoIWlzTmFOKGxldmVsKSAmJiBsZXZlbCA+IDEpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHByZXYgPSB0aGlzLnByZXZpb3VzRWxlbWVudFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgICAgIGlmICghIXByZXYgJiYgcHJldi5ub2RlTmFtZSA9PSAnTEknKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdHlwZSA9IHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLXR5cGUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB3cmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0eXBlID09ICd1bCcgPyAndWwnIDogJ29sJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB3cmFwLnNldEF0dHJpYnV0ZSgnZGF0YS1sZXZlbCcsIGxldmVsKVxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCh3cmFwKS5hcHBlbmQodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHdyYXApLmFwcGVuZFRvKHByZXYpO1xuICAgICAgICAgICAgICAgICAgICAgICAgY2hlY2sgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2UgaWYgKCEhcHJldiAmJiAocHJldi5ub2RlTmFtZSA9PSAnVUwnIHx8IHByZXYubm9kZU5hbWUgPT0gJ09MJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciB3aGVyZSA9IG13LiQoJ2xpW2RhdGEtbGV2ZWw9XCInICsgbGV2ZWwgKyAnXCJdJywgcHJldik7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAod2hlcmUubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdoZXJlLmFmdGVyKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNoZWNrID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciB0eXBlID0gdGhpcy5nZXRBdHRyaWJ1dGUoJ2RhdGEtdHlwZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciB3cmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCh0eXBlID09ICd1bCcgPyAndWwnIDogJ29sJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd3JhcC5zZXRBdHRyaWJ1dGUoJ2RhdGEtbGV2ZWwnLCBsZXZlbClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHdyYXApLmFwcGVuZCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHdyYXApLmFwcGVuZFRvKCQoJ2xpOmxhc3QnLCBwcmV2KSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjaGVjayA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAoIXByZXYgJiYgKHRoaXMucGFyZW50Tm9kZS5ub2RlTmFtZSAhPSAnVUwnICYmIHRoaXMucGFyZW50Tm9kZS5ub2RlTmFtZSAhPSAnT0wnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyICRjdXJyID0gbXcuJChbdGhpc10pLCBjdXJyID0gdGhpcztcbiAgICAgICAgICAgICAgICAgICAgICAgIHdoaWxlICgkKGN1cnIpLm5leHQoJ2xpW2RhdGEtbGV2ZWw9XCInICsgbGV2ZWwgKyAnXCJdJykubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRjdXJyLnB1c2goJChjdXJyKS5uZXh0KCdsaVtkYXRhLWxldmVsPVwiJyArIGxldmVsICsgJ1wiXScpWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJyID0gbXcuJChjdXJyKS5uZXh0KCdsaVtkYXRhLWxldmVsPVwiJyArIGxldmVsICsgJ1wiXScpWzBdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgJGN1cnIud3JhcEFsbCgkY3Vyci5lcSgwKS5hdHRyKCdkYXRhLXR5cGUnKSA9PSAndWwnID8gJzx1bD48L3VsPicgOiAnPG9sPjwvb2w+JylcbiAgICAgICAgICAgICAgICAgICAgICAgIGNoZWNrID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcblxuICAgICAgICBtdy4kKFwidWxbZGF0YS1sZXZlbCE9JzEnXSwgb2xbZGF0YS1sZXZlbCE9JzEnXVwiKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBsZXZlbCA9IHBhcnNlSW50KCQodGhpcykuYXR0cignZGF0YS1sZXZlbCcpKTtcbiAgICAgICAgICAgIGlmICghIXRoaXMucHJldmlvdXNFbGVtZW50U2libGluZykge1xuICAgICAgICAgICAgICAgIHZhciBwbGV2ZWwgPSBwYXJzZUludCgkKHRoaXMucHJldmlvdXNFbGVtZW50U2libGluZykuYXR0cignZGF0YS1sZXZlbCcpKTtcbiAgICAgICAgICAgICAgICBpZiAobGV2ZWwgPiBwbGV2ZWwpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCgnbGk6bGFzdCcsIHRoaXMucHJldmlvdXNFbGVtZW50U2libGluZykuYXBwZW5kKHRoaXMpXG4gICAgICAgICAgICAgICAgICAgIGNoZWNrID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG4gICAgICAgIGlmIChjb3VudCA9PT0gMCkge1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy53b3JkX2xpc3RfYnVpbGQoJCgnbGlbZGF0YS1sZXZlbF0nKSwgMSk7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy53cmFwX2xpX3Jvb3RzKClcbiAgICAgICAgICAgIH0sIDEpXG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGxpc3RzO1xuICAgIH0sXG4gICAgd3JhcF9saV9yb290czogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgYWxsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnbGlbZGF0YS1sZXZlbF0nKSwgaSA9IDAsIGhhcyA9IGZhbHNlO1xuICAgICAgICBmb3IgKDsgaSA8IGFsbC5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgdmFyIHBhcmVudCA9IGFsbFtpXS5wYXJlbnRFbGVtZW50Lm5vZGVOYW1lO1xuICAgICAgICAgICAgaWYgKHBhcmVudCAhPSAnT0wnICYmIHBhcmVudCAhPSAnVUwnKSB7XG4gICAgICAgICAgICAgICAgaGFzID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICB2YXIgZ3JvdXAgPSBtdy4kKFtdKSwgY3VyciA9IGFsbFtpXTtcbiAgICAgICAgICAgICAgICB3aGlsZSAoISFjdXJyICYmIGN1cnIubm9kZU5hbWUgPT0gJ0xJJykge1xuICAgICAgICAgICAgICAgICAgICBncm91cC5wdXNoKGN1cnIpO1xuICAgICAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5uZXh0RWxlbWVudFNpYmxpbmc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciBlbCA9IG13ZC5jcmVhdGVFbGVtZW50KGFsbFtpXS5nZXRBdHRyaWJ1dGUoJ2RhdGEtdHlwZScpID09ICd1bCcgPyAndWwnIDogJ29sJyk7XG4gICAgICAgICAgICAgICAgZWwuY2xhc3NOYW1lID0gJ2VsZW1lbnQnO1xuICAgICAgICAgICAgICAgIGdyb3VwLndyYXBBbGwoZWwpXG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGhhcykgcmV0dXJuIG13Lnd5c2l3eWcud3JhcF9saV9yb290cygpXG4gICAgfSxcbiAgICBpc1dvcmRIdG1sOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICByZXR1cm4gaHRtbC5pbmRleE9mKCd1cm46c2NoZW1hcy1taWNyb3NvZnQtY29tOm9mZmljZTp3b3JkJykgIT09IC0xO1xuICAgIH0sXG4gICAgYmdRdW90ZXNGaXg6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICBlbCA9IG13LiQoZWwpWzBdO1xuICAgICAgICBpZiAoISFlbCAmJiBlbC5ub2RlVHlwZSA9PT0gMSkge1xuICAgICAgICAgICAgdmFyIGZpcnN0ID0gZWwub3V0ZXJIVE1MLnNwbGl0KCc+JylbMF07XG4gICAgICAgICAgICBpZiAoZWwuc3R5bGUuYmFja2dyb3VuZEltYWdlLmluZGV4T2YoJ1wiJykgIT09IC0xICYmIGZpcnN0LmluZGV4T2YoJ3N0eWxlPVwiJykgIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgZWwuYXR0cmlidXRlcy5zdHlsZS5ub2RlVmFsdWUgPSBlbC5hdHRyaWJ1dGVzLnN0eWxlLm5vZGVWYWx1ZS5yZXBsYWNlKC9cXFwiL2csIFwiJ1wiKVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICBjbGVhbl93b3JkX2xpc3Q6IGZ1bmN0aW9uIChodG1sKSB7XG5cbiAgICAgICAgaWYgKCFtdy53eXNpd3lnLmlzV29yZEh0bWwoaHRtbCkpIHJldHVybiBodG1sO1xuICAgICAgICBpZiAoaHRtbC5pbmRleE9mKCc8L2JvZHk+JykgIT0gLTEpIHtcbiAgICAgICAgICAgIGh0bWwgPSBodG1sLnNwbGl0KCc8L2JvZHk+JylbMF1cbiAgICAgICAgfVxuICAgICAgICB2YXIgcGFyc2VyID0gbXcudG9vbHMucGFyc2VIdG1sKGh0bWwpLmJvZHk7XG5cbiAgICAgICAgdmFyIGxpc3RzID0gbXcuJCgnW3N0eWxlKj1cIm1zby1saXN0OlwiXScsIHBhcnNlcik7XG4gICAgICAgIGxpc3RzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGxldmVsID0gbXcud3lzaXd5Zy53b3JkX2xpc3RpdGVtX2dldF9sZXZlbCh0aGlzKTtcbiAgICAgICAgICAgIGlmICghIWxldmVsKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zZXRBdHRyaWJ1dGUoJ2RhdGEtbGV2ZWwnLCBsZXZlbClcbiAgICAgICAgICAgICAgICB0aGlzLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCAnbGV2ZWwtJyArIGxldmVsKVxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0pO1xuXG4gICAgICAgIG13LiQoJ1tzdHlsZV0nLCBwYXJzZXIpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG5cbiAgICAgICAgaWYgKGxpc3RzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIGxpc3RzID0gbXcud3lzaXd5Zy53b3JkX2xpc3RfYnVpbGQobGlzdHMpO1xuICAgICAgICAgICAgdmFyIHN0YXJ0ID0gbXcuJChbXSk7XG4gICAgICAgICAgICBtdy4kKCdsaScsIHBhcnNlcikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5pbm5lckhUTUwgPSB0aGlzLmlubmVySFRNTFxuICAgICAgICAgICAgICAgICAgICAucmVwbGFjZSgv77+9L2csICcnKS8qIE5vdCBhIGRvdCAqL1xuICAgICAgICAgICAgICAgICAgICAucmVwbGFjZShuZXcgUmVnRXhwKFN0cmluZy5mcm9tQ2hhckNvZGUoMTYwKSwgXCJnXCIpLCBcIlwiKVxuICAgICAgICAgICAgICAgICAgICAucmVwbGFjZSgvJm5ic3A7L2dpLCAnJylcbiAgICAgICAgICAgICAgICAgICAgLnJlcGxhY2UoL1xc77+9L2csICcnKVxuICAgICAgICAgICAgICAgICAgICAucmVwbGFjZSgvPFxcLz9zcGFuW14+XSo+L2csIFwiXCIpXG4gICAgICAgICAgICAgICAgICAgIC5yZXBsYWNlKCfvv70nLCAnJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcGFyc2VyLmlubmVySFRNTDtcbiAgICB9LFxuICAgIGNsZWFuX3dvcmQ6IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgIGh0bWwgPSBtdy53eXNpd3lnLmNsZWFuX3dvcmRfbGlzdChodG1sKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPHRkKFtePl0qKT4vZ2ksICc8dGQ+Jyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzx0YWJsZShbXj5dKik+L2dpLCAnPHRhYmxlIGNlbGxzcGFjaW5nPVwiMFwiIGNlbGxwYWRkaW5nPVwiMFwiIGJvcmRlcj1cIjFcIiBzdHlsZT1cIndpZHRoOjEwMCU7XCIgd2lkdGg9XCIxMDAlXCIgY2xhc3M9XCJlbGVtZW50XCI+Jyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxvOnA+XFxzKjxcXC9vOnA+L2csICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPG86cD5bXFxzXFxTXSo/PFxcL286cD4vZywgJyZuYnNwOycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqbXNvLVteOl0rOlteO1wiXSs7Py9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqTUFSR0lOOiAwY20gMGNtIDBwdFxccyo7L2dpLCAnJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypNQVJHSU46IDBjbSAwY20gMHB0XFxzKlwiL2dpLCBcIlxcXCJcIik7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoL1xccypURVhULUlOREVOVDogMGNtXFxzKjsvZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKlRFWFQtSU5ERU5UOiAwY21cXHMqXCIvZ2ksIFwiXFxcIlwiKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKlRFWFQtQUxJR046IFteXFxzO10rOz9cIi9naSwgXCJcXFwiXCIpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqUEFHRS1CUkVBSy1CRUZPUkU6IFteXFxzO10rOz9cIi9naSwgXCJcXFwiXCIpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqRk9OVC1WQVJJQU5UOiBbXlxccztdKzs/XCIvZ2ksIFwiXFxcIlwiKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKnRhYi1zdG9wczpbXjtcIl0qOz8vZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKnRhYi1zdG9wczpbXlwiXSovZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKmZhY2U9XCJbXlwiXSpcIi9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqZmFjZT1bXiA+XSovZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvXFxzKkZPTlQtRkFNSUxZOlteO1wiXSo7Py9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KFxcd1tePl0qKSBjbGFzcz0oW14gfD5dKikoW14+XSopL2dpLCBcIjwkMSQzXCIpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88U1RZTEVbXj5dKj5bXFxzXFxTXSo/PFxcL1NUWUxFW14+XSo+L2dpLCAnJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoPzpNRVRBfExJTkspW14+XSo+XFxzKi9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC9cXHMqc3R5bGU9XCJcXHMqXCIvZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFNQQU5cXHMqW14+XSo+XFxzKiZuYnNwO1xccyo8XFwvU1BBTj4vZ2ksICcmbmJzcDsnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFNQQU5cXHMqW14+XSo+PFxcL1NQQU4+L2dpLCAnJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoXFx3W14+XSopIGxhbmc9KFteIHw+XSopKFtePl0qKS9naSwgXCI8JDEkM1wiKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPFNQQU5cXHMqPihbXFxzXFxTXSo/KTxcXC9TUEFOPi9naSwgJyQxJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxGT05UXFxzKj4oW1xcc1xcU10qPyk8XFwvRk9OVD4vZ2ksICckMScpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88XFxcXD9cXD94bWxbXj5dKj4vZ2ksICcnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPHc6W14+XSo+W1xcc1xcU10qPzxcXC93OltePl0qPi9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88XFwvP1xcdys6W14+XSo+L2dpLCAnJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxcXCEtLVtcXHNcXFNdKj8tLT4vZywgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KFV8SXxTVFJJS0UpPiZuYnNwOzxcXC9cXDE+L2csICcmbmJzcDsnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPEhcXGQ+XFxzKjxcXC9IXFxkPi9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KFxcdyspW14+XSpcXHNzdHlsZT1cIlteXCJdKkRJU1BMQVlcXHM/Olxccz9ub25lW1xcc1xcU10qPzxcXC9cXDE+L2lnLCAnJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoXFx3W14+XSopIGxhbmd1YWdlPShbXiB8Pl0qKShbXj5dKikvZ2ksIFwiPCQxJDNcIik7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoXFx3W14+XSopIG9ubW91c2VvdmVyPVwiKFteXFxcIl0qKVwiKFtePl0qKS9naSwgXCI8JDEkM1wiKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPChcXHdbXj5dKikgb25tb3VzZW91dD1cIihbXlxcXCJdKilcIihbXj5dKikvZ2ksIFwiPCQxJDNcIik7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxIKFxcZCkoW14+XSopPi9naSwgJzxoJDE+Jyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxmb250IHNpemU9Mj4oLiopPFxcL2ZvbnQ+L2dpLCAnJDEnKTtcbiAgICAgICAgaHRtbCA9IGh0bWwucmVwbGFjZSgvPGZvbnQgc2l6ZT0zPiguKik8XFwvZm9udD4vZ2ksICckMScpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88YSBuYW1lPS4qPiguKik8XFwvYT4vZ2ksICckMScpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88SDEoW14+XSopPi9naSwgJzxIMiQxPicpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88XFwvSDFcXGQ+L2dpLCAnPFxcL0gyPicpO1xuICAgICAgICAvL2h0bWwgPSBodG1sLnJlcGxhY2UoLzxzcGFuPi9naSwgJyQxJyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzxcXC9zcGFuXFxkPi9naSwgJycpO1xuICAgICAgICBodG1sID0gaHRtbC5yZXBsYWNlKC88KEhcXGQpPjxGT05UW14+XSo+KFtcXHNcXFNdKj8pPFxcL0ZPTlQ+PFxcL1xcMT4vZ2ksICc8JDE+JDI8XFwvJDE+Jyk7XG4gICAgICAgIGh0bWwgPSBodG1sLnJlcGxhY2UoLzwoSFxcZCk+PEVNPihbXFxzXFxTXSo/KTxcXC9FTT48XFwvXFwxPi9naSwgJzwkMT4kMjxcXC8kMT4nKTtcbiAgICAgICAgcmV0dXJuIGh0bWw7XG4gICAgfSxcbiAgICBjbGVhblRhYmxlczogZnVuY3Rpb24gKHJvb3QpIHtcbiAgICAgICAgdmFyIHRvUmVtb3ZlID0gXCJ0Ym9keSA+ICo6bm90KHRyKSwgdGhlYWQgPiAqOm5vdCh0ciksIHRyID4gKjpub3QodGQpXCIsXG4gICAgICAgICAgICBhbGwgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwodG9SZW1vdmUpLFxuICAgICAgICAgICAgbCA9IGFsbC5sZW5ndGgsXG4gICAgICAgICAgICBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIG13LiQoYWxsW2ldKS5yZW1vdmUoKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgdGFibGVzID0gcm9vdC5xdWVyeVNlbGVjdG9yQWxsKCd0YWJsZScpLFxuICAgICAgICAgICAgbCA9IHRhYmxlcy5sZW5ndGgsXG4gICAgICAgICAgICBpID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIHZhciBpdGVtID0gdGFibGVzW2ldLFxuICAgICAgICAgICAgICAgIGwgPSBpdGVtLmNoaWxkcmVuLmxlbmd0aCxcbiAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSBpdGVtLmNoaWxkcmVuW2ldO1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgaXRlbSAhPT0gJ3VuZGVmaW5lZCcgJiYgaXRlbS5ub2RlVHlwZSAhPT0gMykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbmFtZSA9IGl0ZW0ubm9kZU5hbWUudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHBvc2libGVzID0gXCJ0aGVhZCB0Zm9vdCB0ciB0Ym9keSBjb2wgY29sZ3JvdXBcIjtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFwb3NpYmxlcy5jb250YWlucyhuYW1lKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChpdGVtKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgY2xlYW5IVE1MOiBmdW5jdGlvbiAocm9vdCkge1xuICAgICAgICB2YXIgcm9vdCA9IHJvb3QgfHwgbXdkLmJvZHk7XG4gICAgICAgIG13LnRvb2xzLmZvcmVhY2hDaGlsZHJlbihyb290LCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAobXcud3lzaXd5Zy5oYXNDb250ZW50RnJvbVdvcmQodGhpcykpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmlubmVySFRNTCA9IG13Lnd5c2l3eWcuY2xlYW5fd29yZCh0aGlzLmlubmVySFRNTCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy53eXNpd3lnLmNsZWFuVGFibGVzKHRoaXMpO1xuICAgICAgICB9KTtcbiAgICB9LFxuICAgIG5vcm1hbGl6ZUJhc2U2NEltYWdlOiBmdW5jdGlvbiAobm9kZSwgY2FsbGJhY2spIHtcbiAgICAgICAgaWYgKHR5cGVvZiBub2RlLnNyYyAhPT0gJ3VuZGVmaW5lZCcgJiYgbm9kZS5zcmMuaW5kZXhPZignZGF0YTppbWFnZS8nKSA9PT0gMCkge1xuICAgICAgICAgICAgdmFyIHR5cGUgPSBub2RlLnNyYy5zcGxpdCgnLycpWzFdLnNwbGl0KCc7JylbMF07XG4gICAgICAgICAgICB2YXIgb2JqID0ge1xuICAgICAgICAgICAgICAgIGZpbGU6IG5vZGUuc3JjLFxuICAgICAgICAgICAgICAgIG5hbWU6IG13LnJhbmRvbSgpLnRvU3RyaW5nKDM2KSArIFwiLlwiICsgdHlwZVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyBcIm1lZGlhL3VwbG9hZFwiLCBvYmosIGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgdmFyIGRhdGEgPSAkLnBhcnNlSlNPTihkYXRhKTtcbiAgICAgICAgICAgICAgICBub2RlLnNyYyA9IGRhdGEuc3JjO1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChub2RlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2Uobm9kZSk7XG4gICAgICAgICAgICAgICAgbXcudHJpZ2dlcignaW1hZ2VTcmNDaGFuZ2VkJywgW25vZGUsIG5vZGUuc3JjXSlcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKG5vZGUuc3R5bGUuYmFja2dyb3VuZEltYWdlLmluZGV4T2YoJ2RhdGE6aW1hZ2UvJykgIT09IC0xKSB7XG4gICAgICAgICAgICB2YXIgYmcgPSBub2RlLnN0eWxlLmJhY2tncm91bmRJbWFnZS5yZXBsYWNlKC91cmxcXCgvZywgJycpLnJlcGxhY2UoL1xcKS9nLCAnJylcbiAgICAgICAgICAgIHZhciB0eXBlID0gYmcuc3BsaXQoJy8nKVsxXS5zcGxpdCgnOycpWzBdO1xuICAgICAgICAgICAgdmFyIG9iaiA9IHtcbiAgICAgICAgICAgICAgICBmaWxlOiBiZyxcbiAgICAgICAgICAgICAgICBuYW1lOiBtdy5yYW5kb20oKS50b1N0cmluZygzNikgKyBcIi5cIiArIHR5cGVcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICAkLnBvc3QobXcuc2V0dGluZ3MuYXBpX3VybCArIFwibWVkaWEvdXBsb2FkXCIsIG9iaiwgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICB2YXIgZGF0YSA9ICQucGFyc2VKU09OKGRhdGEpO1xuICAgICAgICAgICAgICAgIG5vZGUuc3R5bGUuYmFja2dyb3VuZEltYWdlID0gJ3VybChcXCcnICsgZGF0YS5zcmMgKyAnXFwnKSc7XG5cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwobm9kZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKG5vZGUpO1xuICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ25vZGVCYWNrZ3JvdW5kQ2hhbmdlZCcsIFtub2RlLCBub2RlLnNyY10pXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgbm9ybWFsaXplQmFzZTY0SW1hZ2VzOiBmdW5jdGlvbiAocm9vdCkge1xuICAgICAgICB2YXIgcm9vdCA9IHJvb3QgfHwgbXdkLmJvZHk7XG4gICAgICAgIHZhciBhbGwgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwoXCIuZWRpdCBpbWdbc3JjKj0nZGF0YTppbWFnZS8nXSwgLmVkaXQgW3N0eWxlKj0nZGF0YTppbWFnZS8nXVtzdHlsZSo9J2JhY2tncm91bmQtaW1hZ2UnXVwiKSxcbiAgICAgICAgICAgIGwgPSBhbGwubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgaWYgKGwgPiAwKSB7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGFsbFtpXSwgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLm5vcm1hbGl6ZUJhc2U2NEltYWdlKGFsbFtpXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGRvY3VtZW50Q29tbW9uRm9udHM6IGZ1bmN0aW9uICgpIHtcbiAgICAgIHZhciBjaGVja05vZGVzID0gJCgnaHRtbCwgYm9keSwgaDE6Zmlyc3QsIGgyOmZpcnN0LCBwOmZpcnN0Jyk7XG4gICAgICB2YXIgcmVzdWx0ID0gW107XG4gICAgICAgIGNoZWNrTm9kZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZm9udCA9ICQodGhpcykuY3NzKCdmb250RmFtaWx5Jykuc3BsaXQoJywnKVswXS50cmltKCk7XG4gICAgICAgICAgICBpZihyZXN1bHQuaW5kZXhPZihmb250KSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICByZXN1bHQucHVzaChmb250KVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIHJlc3VsdDtcbiAgICB9XG59XG5tdy5kaXNhYmxlX3NlbGVjdGlvbiA9IGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgdmFyIGVsID0gZWxlbWVudCB8fCBcIi5tb2R1bGVcIjtcbiAgICBlbCA9IG13LiQoZWwsIFwiLmVkaXRcIikubm90KFwiLnVuc2VsZWN0YWJsZVwiKTtcbiAgICBlbC5hdHRyKFwidW5zZWxlY3RhYmxlXCIsIFwib25cIik7XG4gICAgZWwuYWRkQ2xhc3MoXCJ1bnNlbGVjdGFibGVcIik7XG4gICAgZWwub24oXCJzZWxlY3RzdGFydFwiLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0pO1xufTtcblxubXcud3lzaXd5Zy5kcm9wZG93bnMgPSBmdW5jdGlvbiAoKSB7XG4gICAgbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9udF9zaXplXCIpLm5vdCgnLnJlYWR5JykuYWRkQ2xhc3MoJ3JlYWR5JykuY2hhbmdlKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHZhbCA9IG13LiQodGhpcykuZ2V0RHJvcGRvd25WYWx1ZSgpO1xuICAgICAgICBtdy53eXNpd3lnLmZvbnRTaXplKHZhbCk7XG4gICAgICAgIG13LiQoJy5tdy1kcm9wZG93bi12YWwnLCB0aGlzKS5hcHBlbmQoJ3B4Jyk7XG4gICAgfSk7XG4gICAgbXcuJChcIi5td19kcm9wZG93bl9hY3Rpb25fZm9ybWF0XCIpLm5vdCgnLnJlYWR5JykuYWRkQ2xhc3MoJ3JlYWR5JykuY2hhbmdlKGZ1bmN0aW9uICgpIHtcblxuICAgICAgICB2YXIgdmFsID0gbXcuJCh0aGlzKS5nZXREcm9wZG93blZhbHVlKCk7XG4gICAgICAgIG13Lnd5c2l3eWcuZm9ybWF0KHZhbCk7XG4gICAgfSk7XG4gICAgbXcud3lzaXd5Zy5pbml0Rm9udFNlbGVjdG9yQm94KCk7XG4gICAgbXcuJChcIiN3eXNpd3lnX2luc2VydFwiKS5ub3QoJy5yZWFkeScpLmFkZENsYXNzKCdyZWFkeScpLm9uKFwiY2hhbmdlXCIsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGZub2RlID0gd2luZG93LmdldFNlbGVjdGlvbigpLmZvY3VzTm9kZTtcbiAgICAgICAgdmFyIGlzUGxhaW4gPSBtdy50b29scy5maXJzdFBhcmVudE9yQ3VycmVudFdpdGhDbGFzcyhmbm9kZSwgJ3BsYWluLXRleHQnKTtcbiAgICAgICAgaWYgKG13Lnd5c2l3eWcuaXNTZWxlY3Rpb25FZGl0YWJsZSgpKSB7XG4gICAgICAgICAgICB2YXIgdmFsID0gbXcuJCh0aGlzKS5nZXREcm9wZG93blZhbHVlKCk7XG5cbiAgICAgICAgICAgIHZhciBpc1RleHRsaWtlID0gdmFsID09ICdpY29uJztcbiAgICAgICAgICAgIGlmICghaXNUZXh0bGlrZSAmJiBpc1BsYWluKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAodmFsID09ICdocicpIHtcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLl9kbygnSW5zZXJ0SG9yaXpvbnRhbFJ1bGUnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKHZhbCA9PSAnYm94Jykge1xuXG4gICAgICAgICAgICAgICAgdmFyIGRpdiA9IG13Lnd5c2l3eWcuYXBwbGllcignZGl2JywgJ213LXVpLWJveCBtdy11aS1ib3gtY29udGVudCBlbGVtZW50Jyk7XG4gICAgICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcuc2VsZWN0aW9uX2xlbmd0aCgpIDw9IDIpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChkaXYpLmFwcGVuZChcIjxwPiZuYnNwOzwvcD5cIik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZiAodmFsID09ICdwcmUnKSB7XG4gICAgICAgICAgICAgICAgdmFyIGRpdiA9IG13Lnd5c2l3eWcuYXBwbGllcigncHJlJywgJycpO1xuICAgICAgICAgICAgICAgIGlmIChtdy53eXNpd3lnLnNlbGVjdGlvbl9sZW5ndGgoKSA8PSAyKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoZGl2KS5hcHBlbmQoXCImbmJzcDtcIik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIGlmICh2YWwgPT09ICdjb2RlJykge1xuICAgICAgICAgICAgICAgIC8vIHZhciBkaXYgPSBtdy53eXNpd3lnLmFwcGxpZXIoJ2NvZGUnLCAnJyk7XG4gICAgICAgICAgICAgICAgdmFyIG5ld19pbnNlcnRfaHRtbCA9IHByb21wdChcIlBhc3RlIHlvdXIgY29kZVwiKTtcbiAgICAgICAgICAgICAgICBpZiAobmV3X2luc2VydF9odG1sICE9IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRpdiA9IG13Lnd5c2l3eWcuYXBwbGllcignY29kZScpO1xuICAgICAgICAgICAgICAgICAgICBkaXYuaW5uZXJIVE1MID0gbmV3X2luc2VydF9odG1sO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSBpZiAodmFsID09PSAnaW5zZXJ0X2h0bWwnKSB7XG4gICAgICAgICAgICAgICAgdmFyIG5ld19pbnNlcnRfaHRtbCA9IHByb21wdChcIlBhc3RlIHlvdXIgaHRtbCBjb2RlIGluIHRoZSBib3hcIik7XG4gICAgICAgICAgICAgICAgaWYgKG5ld19pbnNlcnRfaHRtbCAhPSBudWxsKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbChuZXdfaW5zZXJ0X2h0bWwpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIGlmICh2YWwgPT09ICdpY29uJykge1xuXG4gICAgICAgICAgICAgICAgdmFyIGljZGl2ID0gbXcud3lzaXd5Zy5hcHBsaWVyKCdpJyk7XG4gICAgICAgICAgICAgICAgaWNkaXYuY2xhc3NOYW1lID0gXCJtdy1pY29uXCI7XG5cbiAgICAgICAgICAgICAgICB2YXIgbW9kZSA9IDM7XG4gICAgICAgICAgICAgICAgaWYobW9kZSA9PT0gMykge1xuICAgICAgICAgICAgICAgICAgICBtdy5lZGl0b3JJY29uUGlja2VyLnRvb2x0aXAoaWNkaXYpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKG1vZGUgPT09IDIpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRpYWxvZyA9IG13Lmljb25zLmRpYWxvZygpO1xuICAgICAgICAgICAgICAgICAgICAkKGRpYWxvZykub24oJ1Jlc3VsdCcsIGZ1bmN0aW9uKGUsIHJlcyl7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXMucmVuZGVyKHJlcy5pY29uLCBpY2Rpdik7XG4gICAgICAgICAgICAgICAgICAgICAgICBkaWFsb2cucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKG1vZGUgPT09IDEpIHtcblxuICAgICAgICAgICAgICAgICAgICBtdy5lZGl0b3JJY29uUGlja2VyLnRvb2x0aXAoaWNkaXYpXG5cbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5zaWRlYmFyU2V0dGluZ3NUYWJzLnNldCgyKVxuICAgICAgICAgICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgICAgICAgICAgfVxuXG5cblxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZiAodmFsID09PSAndGFibGUnKSB7XG4gICAgICAgICAgICAgICAgdmFyIGVsID0gbXcud3lzaXd5Zy5hcHBsaWVyKCdkaXYnLCAnZWxlbWVudCcsIHt3aWR0aDogXCIxMDAlXCJ9KTtcbiAgICAgICAgICAgICAgICBlbC5pbm5lckhUTUwgPSAnPHRhYmxlIGNsYXNzPVwibXctd3lzaXd5Zy10YWJsZVwiPjx0Ym9keT48dHI+PHRkPkxvcmVtIElwc3VtPC90ZD48dGQgID5Mb3JlbSBJcHN1bTwvdGQ+PC90cj48dHI+PHRkICA+TG9yZW0gSXBzdW08L3RkPjx0ZCAgPkxvcmVtIElwc3VtPC90ZD48L3RyPjwvdGJvZHk+PC90YWJsZT4nO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmICh2YWwgPT09ICdxdW90ZScpIHtcbiAgICAgICAgICAgICAgICB2YXIgZGl2ID0gbXcud3lzaXd5Zy5hcHBsaWVyKCdibG9ja3F1b3RlJywgJ2VsZW1lbnQnKTtcbiAgICAgICAgICAgICAgICBtdy4kKGRpdikuYXBwZW5kKFwiPGNpdGU+QnkgTG9yZW0gSXBzdW08L2NpdGU+XCIpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy8gIG13LiQodGhpcykuc2V0RHJvcGRvd25WYWx1ZShcIkluc2VydFwiLCB0cnVlLCB0cnVlLCBcIkluc2VydFwiKTtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKHRoaXMpLmZpbmQoJy5tdy1kcm9wZG93bi12YWwnKS5odG1sKCdpbnNlcnQnKS5maW5kKCcubXctZHJvcGRvd24tY29udGVudCcpLmhpZGUoKVxuICAgICAgICBtdy4kKHRoaXMpLmZpbmQoJy5tdy1kcm9wZG93bi1jb250ZW50JykuaGlkZSgpXG4gICAgfSlcbn07XG4kKG13ZCkucmVhZHkoZnVuY3Rpb24gKCkge1xuXG5cbiAgICBtdy53eXNpd3lnLmluaXRDbGFzc0FwcGxpZXIoKTtcblxuICAgIG13Lnd5c2l3eWcuZHJvcGRvd25zKCk7XG5cbiAgICBtdy5lZGl0b3JJY29uUGlja2VyID0gbXcuaWNvblBpY2tlcih7XG4gICAgICAgIGljb25PcHRpb25zOiB7IHJlc2V0OiB0cnVlIH1cbiAgICB9KTtcblxuXG4gICAgbXcuZWRpdG9ySWNvblBpY2tlci5vbignc2VsZWN0JywgZnVuY3Rpb24gKGRhdGEpe1xuICAgICAgICBkYXRhLnJlbmRlcigpO1xuICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5lZGl0b3JJY29uUGlja2VyLnRhcmdldClcbiAgICB9KTtcbiAgICBtdy5lZGl0b3JJY29uUGlja2VyLm9uKCdzaXplQ2hhbmdlJywgZnVuY3Rpb24gKHNpemUpe1xuICAgICAgICBtdy5lZGl0b3JJY29uUGlja2VyLnRhcmdldC5zdHlsZS5mb250U2l6ZSA9IHNpemUgKyAncHgnO1xuICAgICAgICBtdy50b29scy50b29sdGlwLnNldFBvc2l0aW9uKG13LmVkaXRvckljb25QaWNrZXIuX3Rvb2x0aXAsIG13LmVkaXRvckljb25QaWNrZXIudGFyZ2V0LCAnYm90dG9tLWNlbnRlcicpO1xuICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShtdy5lZGl0b3JJY29uUGlja2VyLnRhcmdldClcbiAgICB9KVxuICAgIG13LmVkaXRvckljb25QaWNrZXIub24oJ2NvbG9yQ2hhbmdlJywgZnVuY3Rpb24gKGNvbG9yKXtcbiAgICAgICAgbXcuZWRpdG9ySWNvblBpY2tlci50YXJnZXQuc3R5bGUuY29sb3IgPSBjb2xvcjtcbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuZWRpdG9ySWNvblBpY2tlci50YXJnZXQpXG4gICAgfSk7XG5cbiAgICBtdy5lZGl0b3JJY29uUGlja2VyLm9uKCdyZXNldCcsIGZ1bmN0aW9uIChjb2xvcil7XG4gICAgICAgIG13LmVkaXRvckljb25QaWNrZXIudGFyZ2V0LnN0eWxlLmNvbG9yID0gJyc7XG4gICAgICAgIG13LmVkaXRvckljb25QaWNrZXIudGFyZ2V0LnN0eWxlLmZvbnRTaXplID0gJyc7XG4gICAgICAgIG13LnRvb2xzLnRvb2x0aXAuc2V0UG9zaXRpb24obXcuZWRpdG9ySWNvblBpY2tlci5fdG9vbHRpcCwgbXcuZWRpdG9ySWNvblBpY2tlci50YXJnZXQsICdib3R0b20tY2VudGVyJyk7XG5cbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UobXcuZWRpdG9ySWNvblBpY2tlci50YXJnZXQpXG4gICAgfSk7XG5cblxuICAgIGlmICghbXcud3lzaXd5Zy5fZm9udGNvbG9ycGlja2VyKSB7XG4gICAgICAgIG13LmxpYi5yZXF1aXJlKCdjb2xvcnBpY2tlcicpO1xuICAgICAgICBtdy53eXNpd3lnLl9mb250Y29sb3JwaWNrZXIgPSBtdy5jb2xvclBpY2tlcih7XG4gICAgICAgICAgICBlbGVtZW50OiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjbXdfZWRpdG9yX2ZvbnRfY29sb3InKSxcbiAgICAgICAgICAgIHRpcDogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dIRVg6ZmFsc2UsXG4gICAgICAgICAgICBvbmNoYW5nZTogZnVuY3Rpb24gKGNvbG9yKSB7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5mb250Q29sb3IoY29sb3IpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICBpZiAoIW13Lnd5c2l3eWcuX2JnZm9udGNvbG9ycGlja2VyKSB7XG4gICAgICAgIG13Lnd5c2l3eWcuX2JnZm9udGNvbG9ycGlja2VyID0gbXcuY29sb3JQaWNrZXIoe1xuICAgICAgICAgICAgZWxlbWVudDogZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLm13X2VkaXRvcl9mb250X2JhY2tncm91bmRfY29sb3InKSxcbiAgICAgICAgICAgIHRpcDogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dIRVg6IGZhbHNlLFxuICAgICAgICAgICAgb25jaGFuZ2U6IGZ1bmN0aW9uIChjb2xvcikge1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZm9udGJnKGNvbG9yKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBtdy4kKGRvY3VtZW50KS5vbignc2Nyb2xsJywgZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAobXcud3lzaXd5Zy5fYmdmb250Y29sb3JwaWNrZXIgJiYgbXcud3lzaXd5Zy5fYmdmb250Y29sb3JwaWNrZXIuc2V0dGluZ3MpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLnRvb2x0aXAuc2V0UG9zaXRpb24obXcud3lzaXd5Zy5fYmdmb250Y29sb3JwaWNrZXIudGlwLCBtdy53eXNpd3lnLl9iZ2ZvbnRjb2xvcnBpY2tlci5zZXR0aW5ncy5lbGVtZW50LCBtdy53eXNpd3lnLl9iZ2ZvbnRjb2xvcnBpY2tlci5zZXR0aW5ncy5wb3NpdGlvbilcbiAgICAgICAgICAgIG13LnRvb2xzLnRvb2x0aXAuc2V0UG9zaXRpb24obXcud3lzaXd5Zy5fZm9udGNvbG9ycGlja2VyLnRpcCwgbXcud3lzaXd5Zy5fZm9udGNvbG9ycGlja2VyLnNldHRpbmdzLmVsZW1lbnQsIG13Lnd5c2l3eWcuX2ZvbnRjb2xvcnBpY2tlci5zZXR0aW5ncy5wb3NpdGlvbilcbiAgICAgICAgfVxuXG4gICAgfSlcblxuXG4gICAgbXcud3lzaXd5Zy5uY2V1aSgpO1xuICAgIG13LnNtYWxsRWRpdG9yID0gbXcuJChcIiNtd19zbWFsbF9lZGl0b3JcIik7XG4gICAgbXcuc21hbGxFZGl0b3JDYW5jZWxlZCA9IHRydWU7XG4gICAgbXcuYmlnRWRpdG9yID0gbXcuJChcIiNtdy10ZXh0LWVkaXRvclwiKTtcbiAgICBtdy4kKG13ZC5ib2R5KS5vbignbW91c2Vkb3duIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgdmFyIHRhcmdldCA9IGV2ZW50LnRhcmdldDtcbiAgICAgICAgaWYgKCQodGFyZ2V0KS5oYXNDbGFzcyhcImVsZW1lbnRcIikpIHtcbiAgICAgICAgICAgIG13LnRyaWdnZXIoXCJFbGVtZW50TW91c2VEb3duXCIsIHRhcmdldCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoJCh0YXJnZXQpLnBhcmVudHMoXCIuZWxlbWVudFwiKS5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBtdy50cmlnZ2VyKFwiRWxlbWVudE1vdXNlRG93blwiLCBtdy4kKHRhcmdldCkucGFyZW50cyhcIi5lbGVtZW50XCIpWzBdKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoJCh0YXJnZXQpLmhhc0NsYXNzKFwiZWRpdFwiKSkge1xuICAgICAgICAgICAgbXcudHJpZ2dlcihcIkVkaXRNb3VzZURvd25cIiwgW3RhcmdldCwgdGFyZ2V0LCBldmVudF0pO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYgKCQodGFyZ2V0KS5wYXJlbnRzKFwiLmVkaXRcIikubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgbXcudHJpZ2dlcihcIkVkaXRNb3VzZURvd25cIiwgWyQodGFyZ2V0KS5wYXJlbnRzKFwiLmVkaXRcIilbMF0sIHRhcmdldCwgZXZlbnRdKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgaHAgPSBtd2QuZ2V0RWxlbWVudEJ5SWQoJ213LWhpc3RvcnktcGFuZWwnKTtcbiAgICAgICAgaWYgKGhwICE9PSBudWxsICYmIGhwLnN0eWxlLmRpc3BsYXkgIT0gJ25vbmUnKSB7XG4gICAgICAgICAgICBpZiAoIWhwLmNvbnRhaW5zKHRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBocC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIG13LiQoXCIjaGlzdG9yeV9wYW5lbF90b2dnbGVcIikucmVtb3ZlQ2xhc3MoJ213X2VkaXRvcl9idG5fYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIG13Lnd5c2l3eWcuZWRpdG9yRm9udHMgPSBbXTtcblxuXG59KTtcbiQod2luZG93KS5vbignbG9hZCcsIGZ1bmN0aW9uICgpIHtcblxuICAgIG13LiQodGhpcykub24oJ2ltYWdlU3JjQ2hhbmdlZCcsIGZ1bmN0aW9uIChlLCBlbCwgdXJsKSB7XG4gICAgICAgIG13LnJlcXVpcmUoXCJmaWxlcy5qc1wiKTtcblxuICAgICAgICB2YXIgbm9kZSA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3NlcyhlbCwgWydtdy1pbWFnZS1ob2xkZXInXSk7XG4gICAgICAgIGlmIChub2RlKSB7XG4gICAgICAgICAgICB1cmwgPSBtdy5maWxlcy5zYWZlRmlsZW5hbWUodXJsKTtcbiAgICAgICAgICAgIHZhciBpbWcgPSBub2RlLnF1ZXJ5U2VsZWN0b3IoJ2ltZycpO1xuICAgICAgICAgICAgaWYoaW1nKSB7XG4gICAgICAgICAgICAgICAgaW1nLnNyYyA9IHVybDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LiQobm9kZSkuY3NzKCdiYWNrZ3JvdW5kSW1hZ2UnLCAndXJsKCcgKyB1cmwgKyAnKScpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBtdy4kKHdpbmRvdykub24oXCJrZXlkb3duXCIsIGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgaWYgKGUudHlwZSA9PT0gJ2tleWRvd24nKSB7XG5cbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XG4gICAgICAgICAgICAgICAgdmFyIGZpZWxkID0gbXcudG9vbHMubXdhdHRyKGUudGFyZ2V0LCAnZmllbGQnKTtcbiAgICAgICAgICAgICAgICBpZiAoZmllbGQgPT09ICd0aXRsZScgfHwgbXcudG9vbHMuaGFzQ2xhc3MoZS50YXJnZXQsICdwbGFpbi10ZXh0JykpIHtcbiAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChlLmN0cmxLZXkpIHtcbiAgICAgICAgICAgICAgICB2YXIgaXNQbGFpbiA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKGUudGFyZ2V0LCAncGxhaW4tdGV4dCcpO1xuICAgICAgICAgICAgICAgIGlmICghaXNQbGFpbikge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29kZSA9IGUua2V5Q29kZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGNvZGUgPT09IDY2KSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoJ2JvbGQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIGlmIChjb2RlID09IDczKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoJ2l0YWxpYycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2UgaWYgKGNvZGUgPT0gODUpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5leGVjQ29tbWFuZCgndW5kZXJsaW5lJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChlLmtleUNvZGUgIT0gNjUgJiYgZS5rZXlDb2RlICE9IDg2KSB7IC8vIGN0cmwgdiB8fCBhXG4gICAgICAgICAgICAgICAgICAgICAgICAvL3JldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG4gICAgbXcuJChcIi5td19lZGl0b3JcIikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgIG13LnRvb2xzLmRyb3Bkb3duKHRoaXMpO1xuICAgIH0pO1xuICAgIHZhciBub2RlcyA9IG13ZC5xdWVyeVNlbGVjdG9yQWxsKFwiLmVkaXRcIiksIGwgPSBub2Rlcy5sZW5ndGgsIGkgPSAwO1xuICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgIHZhciBub2RlID0gbm9kZXNbaV07XG4gICAgICAgIHZhciByZWwgPSBtdy50b29scy5td2F0dHIobm9kZSwgXCJyZWxcIik7XG4gICAgICAgIHZhciBmaWVsZCA9IG13LnRvb2xzLm13YXR0cihub2RlLCBcImZpZWxkXCIpO1xuICAgICAgICBpZiAoZmllbGQgPT0gJ2NvbnRlbnQnICYmIHJlbCA9PSAnY29udGVudCcpIHtcbiAgICAgICAgICAgIGlmIChub2RlLnF1ZXJ5U2VsZWN0b3IoJ3AnKSAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHZhciBub2RlID0gbm9kZS5xdWVyeVNlbGVjdG9yKCdwJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAvLyBub2RlLmNvbnRlbnRFZGl0YWJsZSA9IHRydWU7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFub2Rlc1tpXS5wYXN0ZUJpbmRlZCAmJiAhbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhub2Rlc1tpXSwgJ2VkaXQnKSkge1xuICAgICAgICAgICAgbm9kZXNbaV0ucGFzdGVCaW5kZWQgPSB0cnVlO1xuICAgICAgICAgICAgbm9kZXNbaV0uYWRkRXZlbnRMaXN0ZW5lcihcInBhc3RlXCIsIGZ1bmN0aW9uIChlKSB7XG5cbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLnBhc3RlKGUpO1xuICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuY2hhbmdlKGUudGFyZ2V0KVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgIH1cbiAgICBtdy5yZXF1aXJlKCd3eXNpd3lnbWRhYi5qcycpO1xufSk7XG5cbm13LmxpbmtUaXAgPSB7XG4gICAgaW5pdDogZnVuY3Rpb24gKHJvb3QpIHtcbiAgICAgICAgaWYgKHJvb3QgPT09IG51bGwgfHwgIXJvb3QpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKHJvb3QpLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICB2YXIgbm9kZSA9IG13LmxpbmtUaXAuZmluZChlLnRhcmdldCk7XG4gICAgICAgICAgICBpZiAoISFub2RlKSB7XG4gICAgICAgICAgICAgICAgbXcubGlua1RpcC50aXAobm9kZSk7XG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy4kKCcubXctbGluay10aXAnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBmaW5kOiBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyh0YXJnZXQsICdtb2R1bGUnKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIHZhciBhID0gbXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQodGFyZ2V0LCBbJ2EnXSk7XG4gICAgICAgIGlmKCFhKSByZXR1cm47XG5cbiAgICAgICAgaWYgKCFtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KGEsIFsnZWRpdCcsICdtb2R1bGUnXSkpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYTtcbiAgICB9LFxuICAgIHRpcDogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgaWYoIW5vZGUpIHJldHVybjtcblxuICAgICAgICB2YXIgbGluayA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2EnKTtcbiAgICAgICAgbGluay5ocmVmID0gbm9kZS5nZXRBdHRyaWJ1dGUoJ2hyZWYnKTtcbiAgICAgICAgbGluay50YXJnZXQgPSAnX2JsYW5rJztcbiAgICAgICAgbGluay5jbGFzc05hbWUgPSAnbXctbGluay10aXAtbGluayc7XG4gICAgICAgIGxpbmsuaW5uZXJIVE1MID0gbm9kZS5nZXRBdHRyaWJ1dGUoJ2hyZWYnKTtcblxuICAgICAgICB2YXIgZWRpdEJ0biA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICAgICAgZWRpdEJ0bi5jbGFzc05hbWUgPSAnbXctbGluay10aXAtZWRpdCc7XG4gICAgICAgIGVkaXRCdG4uaW5uZXJIVE1MID0gbXcubGFuZygnRWRpdCcpO1xuXG4gICAgICAgIHZhciBob2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcblxuICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGluayk7XG4gICAgICAgIGhvbGRlci5hcHBlbmQoJyAtICcpO1xuICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQoZWRpdEJ0bik7XG5cbiAgICAgICAgZWRpdEJ0bi5vbmNsaWNrID0gZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgbmV3IG13LkxpbmtFZGl0b3Ioe1xuICAgICAgICAgICAgICAgIG1vZGU6ICdkaWFsb2cnXG4gICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC5zZXRWYWx1ZSh7dXJsOiBub2RlLmhyZWYsIHRleHQ6IG5vZGUuaW5uZXJIVE1MfSlcbiAgICAgICAgICAgICAgICAucHJvbWlzZSgpXG4gICAgICAgICAgICAgICAgLnRoZW4oZnVuY3Rpb24gKHJlc3VsdCl7XG4gICAgICAgICAgICAgICAgICAgIG5vZGUuaHJlZiA9IHJlc3VsdC51cmw7XG4gICAgICAgICAgICAgICAgICAgIG5vZGUuaW5uZXJIVE1MID0gcmVzdWx0LnRleHQ7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy4kKCcubXctbGluay10aXAnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIG13LmxpbmtUaXAuX3RpcCA9IG13LnRvb2x0aXAoe2NvbnRlbnQ6IGhvbGRlciwgcG9zaXRpb246ICdib3R0b20tY2VudGVyJywgc2tpbjogJ2RhcmsnLCBlbGVtZW50OiBub2RlfSk7XG4gICAgICAgIG13LiQobXcubGlua1RpcC5fdGlwKS5hZGRDbGFzcygnbXctbGluay10aXAnKTtcblxuICAgIH1cbn1cblxuIiwiXHJcbnZhciBjYW5EZXN0cm95ID0gZnVuY3Rpb24gKGV2ZW50KSB7XHJcbiAgICB2YXIgdGFyZ2V0ID0gZXZlbnQudGFyZ2V0O1xyXG4gICAgcmV0dXJuICFtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChldmVudCwgWydzYWZlLWVsZW1lbnQnXSlcclxuICAgICAgICAgICAgJiYgbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdE9yTm9uZSh0YXJnZXQsIFsnYWxsb3ctZHJvcCcsICdub2Ryb3AnXSk7XHJcbn07XHJcblxyXG5tdy53eXNpd3lnLl9tYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2VJblNhZmVNb2RlID0ge1xyXG4gICAgZW1wdHlOb2RlOiBmdW5jdGlvbiAoZXZlbnQsIG5vZGUsIHNlbCwgcmFuZ2UpIHtcclxuICAgICAgICBpZighY2FuRGVzdHJveShub2RlKSkge1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciB0b2RlbGV0ZSA9IG5vZGU7XHJcbiAgICAgICAgaWYobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKG5vZGUucGFyZW50Tm9kZSwgWyd0ZXh0JywgJ3RpdGxlJ10pKXtcclxuICAgICAgICAgICAgdG9kZWxldGUgPSBub2RlLnBhcmVudE5vZGU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciB0cmFuc2ZlciwgdHJhbnNmZXJQb3NpdGlvbjtcclxuICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSkge1xyXG4gICAgICAgICAgICB0cmFuc2ZlciA9IHRvZGVsZXRlLm5leHRFbGVtZW50U2libGluZztcclxuICAgICAgICAgICAgdHJhbnNmZXJQb3NpdGlvbiA9ICdzdGFydCc7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgdHJhbnNmZXIgPSB0b2RlbGV0ZS5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xyXG4gICAgICAgICAgICB0cmFuc2ZlclBvc2l0aW9uID0gJ2VuZCc7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciBwYXJlbnQgPSB0b2RlbGV0ZS5wYXJlbnROb2RlO1xyXG4gICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcclxuICAgICAgICAgICAgdGFyZ2V0OiBwYXJlbnQsXHJcbiAgICAgICAgICAgIHZhbHVlOiBwYXJlbnQuaW5uZXJIVE1MXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgJCh0b2RlbGV0ZSkucmVtb3ZlKCk7XHJcbiAgICAgICAgaWYodHJhbnNmZXIgJiYgbXcudG9vbHMuaXNFZGl0YWJsZSh0cmFuc2ZlcikpIHtcclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBtdy53eXNpd3lnLmN1cnNvclRvRWxlbWVudCh0cmFuc2ZlciwgdHJhbnNmZXJQb3NpdGlvbik7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XHJcbiAgICAgICAgICAgIHRhcmdldDogcGFyZW50LFxyXG4gICAgICAgICAgICB2YWx1ZTogcGFyZW50LmlubmVySFRNTFxyXG4gICAgICAgIH0pO1xyXG4gICAgfSxcclxuICAgIG5vZGVCb3VuZGFyaWVzOiBmdW5jdGlvbiAoZXZlbnQsIG5vZGUsIHNlbCwgcmFuZ2UpIHtcclxuICAgICAgICB2YXIgaXNTdGFydCA9IHJhbmdlLnN0YXJ0T2Zmc2V0ID09PSAwIHx8ICEoKHNlbC5hbmNob3JOb2RlLmRhdGEgfHwgJycpLnN1YnN0cmluZygwLCByYW5nZS5zdGFydE9mZnNldCkucmVwbGFjZSgvXFxzL2csICcnKSk7XHJcbiAgICAgICAgdmFyIGN1cnIsIGNvbnRlbnQ7XHJcbiAgICAgICAgaWYobXcuZXZlbnQuaXMuYmFja1NwYWNlKGV2ZW50KSAmJiBpc1N0YXJ0ICYmIHJhbmdlLmNvbGxhcHNlZCl7IC8vIGlzIGF0IHRoZSBiZWdpbm5pbmdcclxuICAgICAgICAgICAgY3VyciA9IG5vZGU7XHJcbiAgICAgICAgICAgIGlmKG13LnRvb2xzLmhhc0FueU9mQ2xhc3Nlcyhub2RlLnBhcmVudE5vZGUsIFsndGV4dCcsICd0aXRsZSddKSl7XHJcbiAgICAgICAgICAgICAgICBjdXJyID0gbm9kZS5wYXJlbnROb2RlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHZhciBwcmV2ID0gY3Vyci5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xyXG4gICAgICAgICAgICBpZihwcmV2ICYmIHByZXYubm9kZU5hbWUgPT09IG5vZGUubm9kZU5hbWUgJiYgY2FuRGVzdHJveShub2RlKSkge1xyXG4gICAgICAgICAgICAgICAgY29udGVudCA9IG5vZGUuaW5uZXJIVE1MO1xyXG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5jdXJzb3JUb0VsZW1lbnQocHJldiwgJ2VuZCcpO1xyXG4gICAgICAgICAgICAgICAgcHJldi5hcHBlbmRDaGlsZChyYW5nZS5jcmVhdGVDb250ZXh0dWFsRnJhZ21lbnQoY29udGVudCkpO1xyXG4gICAgICAgICAgICAgICAgJChjdXJyKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0gZWxzZSBpZihtdy5ldmVudC5pcy5kZWxldGUoZXZlbnQpXHJcbiAgICAgICAgICAgICYmIHJhbmdlLmNvbGxhcHNlZFxyXG4gICAgICAgICAgICAmJiByYW5nZS5zdGFydE9mZnNldCA9PT0gc2VsLmFuY2hvck5vZGUuZGF0YS5yZXBsYWNlKC9cXHMqJC8sJycpLmxlbmd0aCAvLyBpcyBhdCB0aGUgZW5kXHJcbiAgICAgICAgICAgICYmIGNhbkRlc3Ryb3kobm9kZSkpe1xyXG4gICAgICAgICAgICBjdXJyID0gbm9kZTtcclxuICAgICAgICAgICAgaWYobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKG5vZGUucGFyZW50Tm9kZSwgWyd0ZXh0JywgJ3RpdGxlJ10pKXtcclxuICAgICAgICAgICAgICAgIGN1cnIgPSBub2RlLnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgdmFyIG5leHQgPSBjdXJyLm5leHRFbGVtZW50U2libGluZywgZGVsZXRlUGFyZW50O1xyXG4gICAgICAgICAgICBpZihtdy50b29scy5oYXNBbnlPZkNsYXNzZXMobmV4dCwgWyd0ZXh0JywgJ3RpdGxlJ10pKXtcclxuICAgICAgICAgICAgICAgIG5leHQgPSBuZXh0LmZpcnN0RWxlbWVudENoaWxkO1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlUGFyZW50ID0gdHJ1ZTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBpZihuZXh0ICYmIG5leHQubm9kZU5hbWUgPT09IGN1cnIubm9kZU5hbWUpIHtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgPSBuZXh0LmlubmVySFRNTDtcclxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgcGFyZW50ID0gZGVsZXRlUGFyZW50ID8gbmV4dC5wYXJlbnROb2RlLnBhcmVudE5vZGUgOiBuZXh0LnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5hY3Rpb25SZWNvcmQoZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogcGFyZW50LFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiBwYXJlbnQuaW5uZXJIVE1MXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9LCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJyLmFwcGVuZChyYW5nZS5jcmVhdGVDb250ZXh0dWFsRnJhZ21lbnQoY29udGVudCkpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59O1xyXG5tdy53eXNpd3lnLm1hbmFnZURlbGV0ZUFuZEJhY2tzcGFjZUluU2FmZU1vZGUgPSBmdW5jdGlvbiAoZXZlbnQsIHNlbCkge1xyXG5cclxuXHJcbiAgICB2YXIgbm9kZSA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihzZWwuZm9jdXNOb2RlKTtcclxuICAgIHZhciByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KDApO1xyXG4gICAgaWYoIW5vZGUuaW5uZXJUZXh0LnJlcGxhY2UoL1xccy9naSwgJycpKXtcclxuICAgICAgICBtdy53eXNpd3lnLl9tYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2VJblNhZmVNb2RlLmVtcHR5Tm9kZShldmVudCwgbm9kZSwgc2VsLCByYW5nZSk7XHJcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgfVxyXG4gICAgbXcud3lzaXd5Zy5fbWFuYWdlRGVsZXRlQW5kQmFja3NwYWNlSW5TYWZlTW9kZS5ub2RlQm91bmRhcmllcyhldmVudCwgbm9kZSwgc2VsLCByYW5nZSk7XHJcbiAgICByZXR1cm4gdHJ1ZTtcclxufTtcclxubXcud3lzaXd5Zy5tYW5hZ2VEZWxldGVBbmRCYWNrc3BhY2UgPSBmdW5jdGlvbiAoZXZlbnQsIHNlbCkge1xyXG4gICAgaWYgKCFtdy5zZXR0aW5ncy5saXZlRWRpdCAmJiBzZWxmID09PSB0b3ApIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKG13LmV2ZW50LmlzLmRlbGV0ZShldmVudCkgfHwgbXcuZXZlbnQuaXMuYmFja1NwYWNlKGV2ZW50KSkge1xyXG4gICAgICAgIGlmKCFzZWwucmFuZ2VDb3VudCkgcmV0dXJuO1xyXG4gICAgICAgIHZhciByID0gc2VsLmdldFJhbmdlQXQoMCk7XHJcbiAgICAgICAgdmFyIGlzU2FmZSA9IG13Lnd5c2l3eWcuaXNTYWZlTW9kZSgpO1xyXG5cclxuICAgICAgICBpZihpc1NhZmUpIHtcclxuICAgICAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcubWFuYWdlRGVsZXRlQW5kQmFja3NwYWNlSW5TYWZlTW9kZShldmVudCwgc2VsKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICghbXcuc2V0dGluZ3MubGl2ZUVkaXQpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHZhciBuZXh0Tm9kZSA9IG51bGwsIG5leHRjaGFyLCBuZXh0bmV4dGNoYXIsIG5leHRlbDtcclxuXHJcblxyXG4gICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuZGVsZXRlKGV2ZW50KSkge1xyXG4gICAgICAgICAgICAgICAgbmV4dGNoYXIgPSBzZWwuZm9jdXNOb2RlLnRleHRDb250ZW50LmNoYXJBdChzZWwuZm9jdXNPZmZzZXQpO1xyXG4gICAgICAgICAgICAgICAgbmV4dG5leHRjaGFyID0gc2VsLmZvY3VzTm9kZS50ZXh0Q29udGVudC5jaGFyQXQoc2VsLmZvY3VzT2Zmc2V0ICsgMSk7XHJcbiAgICAgICAgICAgICAgICBuZXh0ZWwgPSBzZWwuZm9jdXNOb2RlLm5leHRTaWJsaW5nIHx8IHNlbC5mb2N1c05vZGUubmV4dEVsZW1lbnRTaWJsaW5nO1xyXG5cclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIG5leHRjaGFyID0gc2VsLmZvY3VzTm9kZS50ZXh0Q29udGVudC5jaGFyQXQoc2VsLmZvY3VzT2Zmc2V0IC0gMSk7XHJcbiAgICAgICAgICAgICAgICBuZXh0bmV4dGNoYXIgPSBzZWwuZm9jdXNOb2RlLnRleHRDb250ZW50LmNoYXJBdChzZWwuZm9jdXNPZmZzZXQgLSAyKTtcclxuICAgICAgICAgICAgICAgIG5leHRlbCA9IHNlbC5mb2N1c05vZGUucHJldmlvdVNpYmxpbmcgfHwgc2VsLmZvY3VzTm9kZS5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xyXG5cclxuICAgICAgICAgICAgfVxyXG5cclxuXHJcbiAgICAgICAgICAgIGlmICgobmV4dGNoYXIgPT09ICcgJyB8fCAvXFxyfFxcbi8uZXhlYyhuZXh0Y2hhcikgIT09IG51bGwpICYmIHNlbC5mb2N1c05vZGUubm9kZVR5cGUgPT09IDMgJiYgIW5leHRuZXh0Y2hhcikge1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgfVxyXG5cclxuXHJcbiAgICAgICAgICAgIGlmIChuZXh0bmV4dGNoYXIgPT09ICcnKSB7XHJcblxyXG5cclxuICAgICAgICAgICAgICAgIGlmIChuZXh0Y2hhci5yZXBsYWNlKC9cXHMvZywgJycpID09PSAnJyAmJiByLmNvbGxhcHNlZCkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAobmV4dGVsICYmICFtdy5lYS5oZWxwZXJzLmlzQmxvY2tMZXZlbChuZXh0ZWwpICYmICggdHlwZW9mIG5leHRlbC5jbGFzc05hbWUgPT09ICd1bmRlZmluZWQnIHx8ICFuZXh0ZWwuY2xhc3NOYW1lLnRyaW0oKSkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGVsc2UgaWYgKG5leHRlbCAmJiBuZXh0ZWwubm9kZU5hbWUgIT09ICdCUicpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHNlbC5mb2N1c05vZGUubm9kZU5hbWUgPT09ICdQJykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGV2ZW50LmtleUNvZGUgPT09IDQ2KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHNlbC5mb2N1c05vZGUubmV4dEVsZW1lbnRTaWJsaW5nLm5vZGVOYW1lID09PSAnUCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGV2ZW50LmtleUNvZGUgPT09IDgpIHtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHNlbC5mb2N1c05vZGUucHJldmlvdXNFbGVtZW50U2libGluZy5ub2RlTmFtZSA9PT0gJ1AnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKChmb2N1cy5wcmV2aW91c0VsZW1lbnRTaWJsaW5nID09PSBudWxsICYmIHJvb3Rmb2N1cy5wcmV2aW91c0VsZW1lbnRTaWJsaW5nID09PSBudWxsKSAmJiBtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChyb290Zm9jdXMsIFsnbm9kcm9wJywgJ2FsbG93LWRyb3AnXSkpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNlIHtcclxuXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKG5leHRjaGFyID09ICcnKSB7XHJcblxyXG5cclxuICAgICAgICAgICAgICAgIC8vY29udGludWUgY2hlY2sgbm9kZXNcclxuICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5kZWxldGUoZXZlbnQpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbmV4dE5vZGUgPSBtdy53eXNpd3lnLm1lcmdlLmdldE5leHQoc2VsLmZvY3VzTm9kZSk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBpZiAobXcuZXZlbnQuaXMuYmFja1NwYWNlKGV2ZW50KSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG5leHROb2RlID0gbXcud3lzaXd5Zy5tZXJnZS5nZXRQcmV2KHNlbC5mb2N1c05vZGUpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgaWYgKG13Lnd5c2l3eWcubWVyZ2UuYWx3YXlzTWVyZ2VhYmxlKG5leHROb2RlKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIHZhciBub25iciA9IG13Lnd5c2l3eWcubWVyZ2UuaXNJbk5vbmJyZWFrYWJsZShuZXh0Tm9kZSk7XHJcbiAgICAgICAgICAgICAgICBpZiAobm9uYnIpIHtcclxuICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBpZiAobmV4dE5vZGUubm9kZVZhbHVlID09ICcnKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgaWYgKG5leHROb2RlICE9PSBudWxsICYmIG13Lnd5c2l3eWcubWVyZ2UuaXNNZXJnZWFibGUobmV4dE5vZGUpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKG13LmV2ZW50LmlzLmRlbGV0ZShldmVudCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5tZXJnZS5tYW5hZ2VCcmVha2FibGVzKHNlbC5mb2N1c05vZGUsIG5leHROb2RlLCAnbmV4dCcsIGV2ZW50KVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5tZXJnZS5tYW5hZ2VCcmVha2FibGVzKHNlbC5mb2N1c05vZGUsIG5leHROb2RlLCAncHJldicsIGV2ZW50KVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KClcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIC8vICB9XHJcbiAgICAgICAgICAgICAgICBpZiAobmV4dE5vZGUgPT09IG51bGwpIHtcclxuICAgICAgICAgICAgICAgICAgICBuZXh0Tm9kZSA9IHNlbC5mb2N1c05vZGUucGFyZW50Tm9kZS5uZXh0U2libGluZztcclxuICAgICAgICAgICAgICAgICAgICBpZiAoIW13Lnd5c2l3eWcubWVyZ2UuaXNNZXJnZWFibGUobmV4dE5vZGUpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5kZWxldGUoZXZlbnQpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubWVyZ2UubWFuYWdlQnJlYWthYmxlcyhzZWwuZm9jdXNOb2RlLCBuZXh0Tm9kZSwgJ25leHQnLCBldmVudClcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcubWVyZ2UubWFuYWdlQnJlYWthYmxlcyhzZWwuZm9jdXNOb2RlLCBuZXh0Tm9kZSwgJ3ByZXYnLCBldmVudClcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG5cclxuICAgICAgICAgICAgfVxyXG5cclxuXHJcbiAgICB9XHJcblxyXG4gICAgcmV0dXJuIHRydWU7XHJcbn07XHJcbiJdLCJzb3VyY2VSb290IjoiIn0=