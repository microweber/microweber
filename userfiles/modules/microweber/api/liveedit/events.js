// this file is deprecated

/*


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
    mw.$(document).on('mousedown touchstart', function(e){
        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-defaults', 'edit', 'element'])){
            mw.$(".element-current").removeClass("element-current");
        }
    });

    mw.$("span.edit:not('.nodrop')").each(function(){
        mw.tools.setTag(this, 'div');
    });


    /!*mw.$("#mw-toolbar-css-editor-btn").click(function() {
        mw.liveedit.widgets.cssEditorDialog();
    });*!/
    mw.$("#mw-toolbar-html-editor-btn").click(function() {
        mw.liveedit.widgets.htmlEditorDialog();
    });

    mw.$("#mw-toolbar-api-clear-cache-btn").click(function() {
        mw.notification.warning("Clearing cache...");
        $.get(mw.settings.api_url + "clearcache", {}, function () {
            mw.notification.warning("Cache is cleared! reloading the page...");
            location.reload();
        });
    });

    mw.$("#mw-toolbar-reset-content-editor-btn").click(function() {
        mw.tools.open_reset_content_editor();
    });
    mw.$(document.body).on('keyup', function(e) {
        mw.$(".mw_master_handle").css({
            left: "",
            top: ""
        });
        /!*mw.on.stopWriting(e.target, function() {
            if (mw.tools.hasClass(e.target, 'edit') || mw.tools.hasParentsWithClass(this, 'edit')) {
                mw.liveEditState.record({
                    target:e.target,
                    value:e.target.innerHTML
                });
                mw.drag.saveDraft();
            }
        });*!/
    });

    mw.$(document.body).on("keydown", function(e) {

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

    mw.$(document.body).on("paste", function(e) {
        if(mw.tools.hasClass(e.target, 'plain-text')){
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertHTML", false, text);
        }
    });

    mw.$(document.body).on("mousedown mouseup touchstart touchend", function(e) {

        if (e.type === 'mousedown' || e.type === 'touchstart') {
            if (!mw.wysiwyg.elementHasFontIconClass(e.target)
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['tooltip-icon-picker', 'mw-tooltip'])) {

                if(mw.editorIconPicker){
                mw.editorIconPicker.tooltip('hide');
                }
                try {
                    $(mw.liveedit.widgets._iconEditor.tooltip).hide();
                } catch(e) {

                }

            }
            if (!mw.tools.hasClass(e.target, 'ui-resizable-handle') && !mw.tools.hasParentsWithClass(e.target, 'ui-resizable-handle')) {
                mw.tools.addClass(document.body, 'state-element')
            } else {
                mw.tools.removeClass(document.body, 'state-element');
            }

            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip-insert-module') && !mw.tools.hasAnyOfClasses(e.target, ['mw-plus-bottom', 'mw-plus-top'])) {
                mw.$('.mw-tooltip-insert-module').remove();
                mw.drag.plus.locked = false;
            }

        } else {
            mw.tools.removeClass(document.body, 'state-element');
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



    mw.on('editChanged', function (target){
        document.querySelector('#main-save-btn').disabled = false;
    });

    mw.on('saveEnd', function (data){
        document.querySelector('#main-save-btn').disabled = true;
    })

};
*/
