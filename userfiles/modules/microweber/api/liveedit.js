
mw.liveedit = {};


<?php



$files = array();
$files[] = 'wysiwyg.js';
$files[] = 'handles.js';
$files[] = 'padding.js';
$files[] = 'source-edit.js';
$files[] = 'control_box.js';
$files[] = 'element_analyzer.js';
$files[] = 'liveedit_elements.js';
$files[] = 'liveedit_elements.js';
$files[] = 'live_edit.js';
$files[] = 'live_edit.js';
$files[] = 'liveedit_widgets.js';
$files[] = 'state.js';
$files[] = 'selector.js';
$files[] = 'icon_selector.js';

// Liveedit

$files[] = 'liveedit/modules.toolbar.js';
$files[] = 'liveedit/drag.js';
$files[] = 'liveedit/manage.content.js';
$files[] = 'liveedit/toolbar.js';
$files[] = 'liveedit/editors.js';
$files[] = 'liveedit/data.js';
$files[] = 'liveedit/edit.fields.js';
$files[] = 'liveedit/inline.js';
$files[] = 'liveedit/events.custom.js';
$files[] = 'liveedit/events.js';
$files[] = 'liveedit/initready.js';
$files[] = 'liveedit/widgets.js';
$files[] = 'liveedit/beforeleave.js';
$files[] = 'liveedit/initload.js';
$files[] = 'liveedit/recommend.js';
$files[] = 'liveedit/layoutplus.js';




foreach($files as $file){

    print 'mw.required.push("'.$file.'");'."\n";
}

foreach($files as $file){

   // print 'mw.require("'.$file.'");'."\n";
    print  file_get_contents(__DIR__.DS.$file)."\n";
}
?>





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

mw.tools.addClass(document.body, 'mw-live-edit');

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
    setTimeout(function (){
        mw.wysiwyg.init_editables();
        console.log(1)
    }, 500)

});

$(window).on('resize', function() {
    mw.liveedit.toolbar.setEditor();
});




