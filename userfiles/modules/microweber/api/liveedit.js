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


// Liveedit
mw.require('liveedit/modules.toolbar.js');
mw.require('liveedit/drag.js');
mw.require('liveedit/drop.regions.js');
mw.require('liveedit/manage.content.js');
mw.require('liveedit/toolbar.js');
mw.require('liveedit/editors.js');
mw.require('liveedit/data.js');
mw.require('liveedit/edit.fields.js');
mw.require('liveedit/inline.js');
mw.require('liveedit/events.custom.js');
mw.require('liveedit/events.js');
mw.require('liveedit/initready.js');
mw.require('liveedit/initload.js');




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

mw.tools.addClass(mwd.body, 'mw-live-edit');

$(document).ready(function() {

    if (("ontouchstart" in document.documentElement)) {
        mw.$('body').addClass('touchscreen-device');
    }

    mw.liveedit.initReady();
    mw.liveedit.handleEvents();
    mw.liveedit.handleCustomEvents();

});


$(window).on("load", function() {
    mw.liveedit.initLoad();
});

$(window).on('resize', function() {
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
    mw.liveedit.toolbar.setEditor();
});


