mw.require('wysiwyg.js');
mw.require('handles.js');

mw.liveedit = {};


mw.require('padding.js');
mw.require('source-edit.js');
mw.require('control_box.js');
mw.require('element_analyzer.js');
mw.require('liveedit_elements.js');
mw.require('live_edit.js');
mw.require('liveedit_widgets.js');
mw.require('state.js');
mw.require('selector.js');

mw.require('liveedit/modules.toolbar.js');
mw.require('liveedit/drag.js');
mw.require('liveedit/drop.regions.js');
mw.require('liveedit/manage.content.js');
mw.require('liveedit/toolbar.js');
mw.require('liveedit/editors.js');
mw.require('liveedit/data.js');
mw.require('liveedit/edit.fields.js');




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





!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);



$(document).ready(function() {
    mw.liveedit.data.init();
    mw.liveEditSelector = new mw.Selector({
        root: document.body,
        autoSelect: false
    });
    mw.$(document.body).on('touchmove mousemove', function(){
        if(mw.liveEditSelector.interactors.active) {
            if( !mw.liveedit.data.get('move', 'hasModuleOrElement') ){
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
        mw.$('body').addClass('touchscreen-device');
    }

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


    mw.drag.create();

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

     mw.liveedit.editFields.handleKeydown();

    mw.dragSTOPCheck = false;

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
        mw.iconSelector._activeElement = el;
        mw.iconSelector.settingsUI();
    });

    mw.on("ComponentClick", function(e, node, type){

        if (type === 'icon'){
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
        mw.$(t[i]).addClass("nodrop");
    }

    mw.$(mwd.body).on("paste", function(e) {
        if(mw.tools.hasClass(e.target, 'plain-text')){
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertHTML", false, text);
        }
    });

    mw.$(mwd.body).on("mousedown mouseup touchstart touchend", function(e) {

        if (e.type === 'mousedown' || e.type === 'touchstart') {
            if (mw.iconSelectorGUI
                && !mw.wysiwyg.elementHasFontIconClass(e.target)
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['tooltip-icon-picker', 'mw-tooltip'])) {
                mw.$(mw.iconSelectorGUI).hide();
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
    mw.$('span.mw-powered-by').on("click", function(e) {
        mw.tools.open_global_module_settings_modal('white_label/admin', 'mw-powered-by');
        return false;
    });

    mw.paddingCTRL = new mw.paddingEditor({

    });


});



/* Toolbar */
mw.tools.addClass(mwd.body, 'mw-live-edit');

$(document).ready(function() {
    mw.wysiwyg.init_editables();
    mw.wysiwyg.prepare();
    mw.wysiwyg.init();
    mw.ea = mw.ea || new mw.ElementAnalyzer();

    mw.$(".edit a, #mw-toolbar-right a").click(function() {
        var el = this;
        if (!el.isContentEditable) {
            if (el.onclick === null) {
                if (!(el.href.indexOf("javascript:") === 0 || el.href == '#' || ($(el).attr("href") && mw.$(el).attr("href").indexOf('#') == 0) || typeof el.attributes['href'] == 'undefined')) {
                    return mw.beforeleave(this.href);
                }
            }
        }
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
                   mw.$("#moduleinbetween").fadeOut(function(){
                       mw.$(this).remove()
                   })
                }, 3000)
            }
        }, 1000)

    });


});


$(window).on("load", function() {
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
            (val != -1 && val != "-1") ? mw.tools.toolbar_sorter(Modules_List_modules, val): '';
        });
        mw.$("#elements_category_selector").change(function() {
            var val = mw.$(this).getDropdownValue();

            if (val == 'all') {
                mw.$(".list-elements li").show();
                return false;
            }
            (val != -1 && val != "-1") ? mw.tools.toolbar_sorter(Modules_List_elements, val): '';
        });




        mw.interval('regular-mode', function(){
            mw.$('.nodrop .allow-drop').addClass('regular-mode');
        })

    }, 100)
});



$(window).on("load", function() {

    mw.wysiwyg.prepareContentEditable();


    mw.$("#history_dd").hover(function() {
        mw.$(this).addClass("hover");
    }, function() {
        mw.$(this).removeClass("hover");
    });
    mw.image.resize.init(".element-image");
    mw.$(mwd.body).mousedown(function(event) {


        if (mw.$(".editor_hover").length === 0) {
            mw.$(mw.wysiwyg.external).empty().css("top", "-9999px");
            mw.$(mwd.body).removeClass('hide_selection');
        }
        if (!mw.$("#history_dd").hasClass("hover")) {
            mw.$("#historycontainer").hide();
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

    mw.tools.sidebar();
    mw.liveedit.toolbar.prepare();
    mw.liveedit.toolbar.fixPad();
    mw.liveedit.editors.prepare();
    mw.liveedit.toolbar.setEditor();

});

$(window).on('resize', function() {
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
    mw.liveedit.toolbar.setEditor();
});


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

            mw.$(save).click(function() {
                mw.$(mwd.body).addClass("loading");
                mw.tools.modal.remove(modal);
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
