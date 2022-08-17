
mw.require('options.js')

mw.drag = {
    _fixDeniedParagraphHierarchySelector: ''
    + '.edit p h1,.edit p h2,.edit p h3,'
    + '.edit p h4,.edit p h5,.edit p h6,'
    + '.edit p p,.edit p ul,.edit p ol,'
    + '.edit p header,.edit p form,.edit p article,'
    + '.edit p aside,.edit p blockquote,.edit p footer,.edit p div',
    fixDeniedParagraphHierarchy: function (root) {
        root = root || document.body;
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
                        var new_col = document.createElement('div');
                        new_col.className = 'mw-col';
                        new_col.innerHTML = '<div class="mw-col-container"><div class="mw-empty-element element" id="element_'+mw.random()+'"></div></div>';
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
    external_grids_col_classes: [
        'col-1',
        'col-2',
        'col-3',
        'col-4',
        'col-5',
        'col-6',
        'col-7',
        'col-8',
        'col-9',
        'col-10',
        'col-11',
        'col-12',
        'col-lg-1',
        'col-lg-2',
        'col-lg-3',
        'col-lg-4',
        'col-lg-5',
        'col-lg-6',
        'col-lg-7',
        'col-lg-8',
        'col-lg-9',
        'col-lg-10',
        'col-lg-11',
        'col-lg-12',
        'col-md-1',
        'col-md-2',
        'col-md-3',
        'col-md-4',
        'col-md-5',
        'col-md-6',
        'col-md-7',
        'col-md-8',
        'col-md-9',
        'col-md-10',
        'col-md-11',
        'col-md-12',
        'col-sm-1',
        'col-sm-2',
        'col-sm-3',
        'col-sm-4',
        'col-sm-5',
        'col-sm-6',
        'col-sm-7',
        'col-sm-8',
        'col-sm-9',
        'col-sm-10',
        'col-sm-11',
        'col-sm-12',
        'col-xs-1',
        'col-xs-2',
        'col-xs-3',
        'col-xs-4',
        'col-xs-5',
        'col-xs-6',
        'col-xs-7',
        'col-xs-8',
        'col-xs-9',
        'col-xs-10',
        'col-xs-11',
        'col-xs-12',
        'row'
    ],
    external_css_no_element_classes: ['container','navbar', 'navbar-header', 'navbar-collapse', 'navbar-static', 'navbar-static-top', 'navbar-default', 'navbar-text', 'navbar-right', 'navbar-center', 'navbar-left', 'nav navbar-nav', 'collapse', 'header-collapse', 'panel-heading', 'panel-body', 'panel-footer'],
    section_selectors: ['.module-layouts'],
    external_css_no_element_controll_classes: ['container', 'container-fluid', 'edit','noelement', 'no-element', 'mw-skip', 'allow-drop', 'nodrop', 'mw-open-module-settings','module-layouts'],
    onCloneableControl:function(target, isOverControl){
        if(!this._onCloneableControl){
            this._onCloneableControl = document.createElement('div');
            this._onCloneableControl.className = 'mw-cloneable-control';
            var html = '';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-prev" title="Move backward"></span>';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-plus" title="Clone"></span>';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-minus" title="Remove"></span>' ;
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-next" title="Move forward"></span>';
            this._onCloneableControl.innerHTML = html;

            document.body.appendChild(this._onCloneableControl);
            mw.$('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
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

            off.top  +=  (target.dataset.handleOffset ? Number(target.dataset.handleOffset) : 0)


            if(next.length === 0){
                mw.$('.mw-cloneable-control-next', clc).hide();
            }
            else{
                mw.$('.mw-cloneable-control-next', clc).show();
            }
            if(prev.length === 0){
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


            var cloner = document.querySelector('.mw-cloneable-control');
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
    noop: document.createElement('div'),
    create: function() {

        var edits = document.body.querySelectorAll(".edit"),
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

        mw.$(document.body).on('mousemove touchmove', function(event) {

            mw.dragSTOPCheck = false;
            if (!mw.settings.resize_started) {

                mw.emouse = mw.event.page(event);

                /*var targetNode;
                targetNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                if (targetNode && targetNode.isContentEditable) {
                    mw.tools.addClass(this, 'isTyping');
                    return;
                }*/

                mw.mm_target = event.target;
                mw.$mm_target = mw.$(mw.mm_target);

                if (!mw.isDrag) {
                    if (mw.liveEditSelectMode === 'element') {
                        //if(mw.tools.distance(mw.handlerMouse.x, mw.handlerMouse.y, mw.emouse.x, mw.emouse.y) > 20) {

                            mw.tools.removeClass(this, 'isTyping');
                            mw.handlerMouse = Object.assign({}, mw.emouse);
                            mw.liveEditHandlers(event);


                        //}
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

        mw.$(document.body).on('mouseup touchend', function(event) {
            mw.mouseDownStarted = false;
            if (mw.isDrag && mw.dropable.is(":hidden")) {
                mw.$(".ui-draggable-dragging").css({
                    top: 0,
                    left: 0
                });
            }
            mw.$(this).removeClass("not-allowed");
        });
        mw.$(document.body).on('mousedown touchstart', function(event) {
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



        if (!$(document.body).hasClass("bup")) {
            mw.$(document.body).addClass("bup");



            mw.$(document.body).on("mouseup touchend", function(event) {
                mw.image._dragcurrent = null;
                mw.image._dragparent = null;
                var sliders = document.getElementsByClassName("canvas-slider"),
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
                        var moduleEl = mw.tools.firstMatchesOnNodeOrParent(target, ['.module:not(.no-settings):not(.inaccessibleModule)']);

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


                        if((!el && !safeEl && !moduleEl) || target.nodeName !== 'IMG') {
                            mw.$(mw.image_resizer).removeClass('active')                        }
                    } else {
                         mw.$(mw.image_resizer).removeClass('active')
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
                    mw.image_resizer._hide();
                    if (target.tagName === 'IMG' && mw.tools.hasParentsWithClass(target, 'edit')) {
                        var order = mw.tools.parentsOrder(mw.mm_target, ['edit', 'module']);
                        if ((order.module == -1) || (order.edit > -1 && order.edit < order.module)) {
                            if (!mw.tools.hasParentsWithClass(target, 'mw-defaults')) {
                                // mw.trigger("ImageClick", target);
                            } else {
                                mw.image_resizer._hide();
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
                                if(mw.ea.data.target) {
                                    mw.liveEditDomTree.refresh(mw.ea.data.target.parentNode)
                                }
                            }
                        }, 40);
                        mw.dropable.hide();

                    }, 77);


                    setTimeout(function () {

                        if (mw.have_new_items) {
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

        var els = document.querySelectorAll('div.element'),
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
            data1.from_url = mw.parent().win.location.href;
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
            mw.tools.iframeAutoHeight(document.querySelector('#'+id))
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
            var elparent = el.parent();

            if(el[0].nodeName === 'IMG' && elparent[0].nodeName === 'PICTURE') {
                el = el.parent();
                elparent = el.parent();
            }

            mw.liveEditState.record({
                target: elparent[0],
                value: elparent.html()
            });

                mw.$(el).remove();
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

        });
    },

    animationsClearFix:function(body){
        mw.$('[class*="animate__"]').each(function () {
            mw.tools.classNamespaceDelete(this, 'animate__');
        });
        return body;
    },
    grammarlyFix:function(data){

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
        return data;
    },
    saving: false,
    coreSave: function(data) {
        if (!data) return false;
        $.each(data, function(){
            var body = mw.tools.parseHtml(this.html).body;
            mw.drag.grammarlyFix(body);
            mw.drag.animationsClearFix(body);
            this.html = body.innerHTML;
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
                    window.mw.parent().askusertostay = false;
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
        var root = root || document.body;
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

        var animations = (mw.__pageAnimations || []).filter(function (item) {
            return item.animation !== 'none'
        })

        var options = {
            group: 'template',
            key: 'animations-global',
            value: JSON.stringify(animations)
        };
        mw.options.saveOption(options, function(){

        });


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
                    doc = document.getElementById('save_content_error_iframe').contentWindow.document;

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
            if (document.querySelector('.edit.changed') !== null) {
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

        if (document.body.textContent !== mw.drag.saveDraftOld) {
            mw.drag.DraftSaving = true;
            mw.wysiwyg.normalizeBase64Images(undefined, function (){
                mw.drag.saveDraftOld = document.body.textContent;
                var body = mw.drag.parseContent().body,
                    edits = body.querySelectorAll('.edit.changed'),
                    data = mw.drag.collectData(edits);
                if (mw.tools.isEmptyObject(data)) { mw.drag.DraftSaving = false; return false };
                data['is_draft'] = true;

                var xhr = mw.drag.coreSave(data);
                xhr.always(function(msg) {
                    mw.drag.DraftSaving = false;
                    mw.drag.initDraft = false;
                    mw.trigger('saveDraftCompleted');

                });
            })

        }
    }
}
