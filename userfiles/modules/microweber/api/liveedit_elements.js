
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
        mw.dropable = $(dropable);
        mw.dropable.on("mouseenter", function() {
            $(this).hide();
        });

    },



    userInteractionClasses:function(){
        var bgHolders = mwd.querySelectorAll(".edit.background-image-holder, .edit .background-image-holder, .edit[style*='background-image'], .edit [style*='background-image']");
        var noEditModules = mwd.querySelectorAll('.module' + mw.noEditModules.join(',.module'));
        var edits = mwd.querySelectorAll('.edit');
        var i = 0, i1 = 0, i2 = 0;
        for ( ; i<bgHolders.length; i++ ) {
            var curr = bgHolders[i];
            var po = mw.tools.parentsOrder(curr, ['edit', 'module']);
            if(po.module === -1 || (po.edit<po.module && po.edit != -1)){
                mw.tools.addClass(curr, 'element');
                curr.style.backgroundImage = curr.style.backgroundImage || 'none';
            }
        }
        for ( ; i1<noEditModules.length; i1++ ) {
            mw.tools.removeClass(noEditModules[i], 'module')
        }
        for ( ; i2<edits.length; i2++ ) {
            var all = mw.ea.helpers.getElementsLike(":not(.element)", edits[i2]), i2a = 0;
            for( ; i2a<all.length; i2a++){
                if(mw.ea.canDrop(all[i2a])){
                    mw.tools.addClass(all[i2a], 'element')
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

    var selectors = (selectors || mw.drag.section_selectors).slice(0);


    for(var i = 0 ; i<selectors.length ; i++){
        selectors[i] = '.edit ' + selectors[i].trim()
    }


    selectors = selectors.join(',');


      //return $( event.target ).closest( '.edit section' )
      var coords = { y:99999999 },
          y = event.pageY,
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
        if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(all[i], ['allow-drop', 'nodrop'])){
          continue;

        }
        var el = $(all[i]), offtop = el.offset().top;
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

        el = $(el);
        var offset = el.offset();
        var width = el.outerWidth();
        var height = el.outerHeight();
        if (mw.drop_regions.global_drop_is_in_region) {

        } else {
            mw.dropable.css({
                top: offset.top + height,
                left: offset.left,
                width: width
            });
        }
    },
    set: function(pos, offset, height, width) {
        if (pos === 'top') {
            mw.top_half = true;
            mw.dropable.css({
                top: offset.top - 2,
                left: offset.left,
                width: width,
                height: ''
            });
            mw.dropable.data("position", "top");
            mw.dropable.addClass("mw_dropable_arr_up");
        } else if (pos === 'bottom') {
            mw.top_half = false;
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
}


 mw.liveEditHandlers = function(event){

    if ( mw.emouse.x % 2 === 0 && mw.drag.columns.resizing === false ) {

        var cloneable = mw.tools.firstParentOrCurrentWithAnyOfClasses(mw.mm_target, ['cloneable', 'mw-cloneable-control']);

        if(!!cloneable){
          if(mw.tools.hasClass(cloneable, 'mw-cloneable-control')){
            mw.trigger("CloneableOver", mw.drag._onCloneableControl.__target);
          }
          else if(mw.tools.hasParentsWithClass(cloneable, 'mw-cloneable-control')){
            mw.trigger("CloneableOver", mw.drag._onCloneableControl.__target);
          }
          else{
            mw.trigger("CloneableOver", cloneable);
          }

        }
        else{
          if(mw.drag._onCloneableControl && mw.mm_target !== mw.drag._onCloneableControl){
            $(mw.drag._onCloneableControl).hide()
          }
        }

        if(mw.tools.hasClass(mw.mm_target, 'mw-layout-root')){
            mw.trigger("LayoutOver", mw.mm_target);
        }
        else if(mw.tools.hasParentsWithClass(mw.mm_target, 'mw-layout-root')){
            mw.trigger("LayoutOver", mw.tools.lastParentWithClass(mw.mm_target, 'mw-layout-root'));
        }
        if (mw.$mm_target.hasClass("element") && !mw.$mm_target.hasClass("module")
            && (!mw.tools.hasParentsWithClass(mw.mm_target, 'module') ||(mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(mw.mm_target, ['edit', 'module']))
                && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(mw.mm_target, ['allow-drop', 'nodrop']) || !mw.tools.hasParentsWithClass(mw.mm_target, 'nodrop')))) {
            mw.trigger("ElementOver", mw.mm_target);
        } else if (mw.$mm_target.parents(".element").length > 0 && !mw.tools.hasParentsWithClass(mw.mm_target, 'module')) {
            mw.trigger("ElementOver", mw.mm_target);
            //mw.trigger("ElementOver", mw.$mm_target.parents(".element:first")[0]);
        } else if (mw.mm_target.id != 'mw_handle_element' && mw.$mm_target.parents("#mw_handle_element").length == 0) {
            mw.trigger("ElementLeave", mw.mm_target);
        }
        if (mw.$mm_target.hasClass("module") && !mw.$mm_target.hasClass("no-settings")) {
          if(!mw.mm_target.__disableModuleTrigger){
            mw.trigger("moduleOver", mw.mm_target);
          }
          else{
             mw.trigger("moduleOver", mw.mm_target.__disableModuleTrigger);
          }


        } else if (mw.tools.hasParentsWithClass(mw.mm_target, 'module')) {
            var _parentmodule = mw.tools.firstParentWithClass(mw.mm_target, 'module');
            if (!mw.tools.hasClass(_parentmodule, "no-settings") && !_parentmodule.__disableModuleTrigger) {
                mw.trigger("moduleOver", _parentmodule);
            }
            else{
             mw.trigger("moduleOver", _parentmodule.__disableModuleTrigger);
          }

        } else if (mw.mm_target.id != 'mw_handle_module' && mw.$mm_target.parents("#mw_handle_module").length == 0) {
            mw.trigger("ModuleLeave", mw.mm_target);
        }
        if (mw.mm_target === mw.image_resizer) {
            mw.trigger("ElementOver", mw.image.currentResizing[0]);
        }

        if (mw.drag.columns.resizing === false && mw.tools.hasParentsWithClass(mw.mm_target, 'edit') && (!mw.tools.hasParentsWithClass(mw.mm_target, 'module') ||
                mw.tools.hasParentsWithClass(mw.mm_target, 'allow-drop'))) {

            //trigger on row
            if (mw.$mm_target.hasClass("mw-row")) {
                mw.trigger("RowOver", mw.mm_target);
            } else if (mw.tools.hasParentsWithClass(mw.mm_target, 'mw-row')) {
                mw.trigger("RowOver", mw.tools.firstParentWithClass(mw.mm_target, 'mw-row'));
            } else if (mw.mm_target.id != 'mw_handle_row' && mw.$mm_target.parents("#mw_handle_row").length == 0) {
                mw.trigger("RowLeave", mw.mm_target);
            }

            //onColumn

            if (mw.drag.columns.resizing === false && mw.tools.hasClass(mw.mm_target, 'mw-col')) {
                mw.drag.columnout = false;
                mw.trigger("ColumnOver", mw.mm_target);
            } else if (mw.drag.columns.resizing === false && mw.tools.hasParentsWithClass(mw.mm_target, 'mw-col')) {
                mw.drag.columnout = false;
                mw.trigger("ColumnOver", mw.tools.firstParentWithClass(mw.mm_target, 'mw-col'));
            } else {
                if (!mw.drag.columnout && !mw.tools.hasClass(mw.mm_target, 'mw-columns-resizer')) {
                    mw.drag.columnout = true;
                    mw.trigger("ColumnOut", mw.mm_target)
                }

            }
        }
        if (mw.$mm_target.parents(".edit,.mw_master_handle").length == 0) {
            if (!mw.$mm_target.hasClass(".edit") && !mw.$mm_target.hasClass("mw_master_handle")) {
                //mw.trigger("AllLeave", mw.mm_target);
            }
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
            mw.image.resize.resizerSet(event.target, false);
        }
        else if (bg && bgCanChange) {
            mw.image_resizer._show();
            mw.image.resize.resizerSet(bgTarget, false);
        }

        else if(mw.tools.hasClass(mw.mm_target, 'mw-image-holder-content')||mw.tools.hasParentsWithClass(mw.mm_target, 'mw-image-holder-content')){
            mw.image_resizer._show();
            mw.image.resize.resizerSet(mw.tools.firstParentWithClass(mw.mm_target, 'mw-image-holder').querySelector('img'), false);
        }
        else {
            if (!event.target.mwImageResizerComponent) {
                if(mw.image_resizer){
                    mw.image_resizer._hide();
                }
            }
        }
    }
}


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
        $('.mw-live-edit-component-options')
            .hide()
            .filter('#js-live-edit-side-wysiwyg-editor-holder')
            .show();
    },
    none: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }
        $('.mw-live-edit-component-options')
            .hide()
    },
    module: function (el) {
        if (this.__is_sidebar_opened()) {
            // $('.mw-live-edit-component-options')
            //     .hide()
            //     .filter('#js-live-edit-module-settings-holder')
            //     .show();
        }
        mw.live_edit.showSettings(undefined, {mode:"sidebar", liveedit:true})
      //   mw.drag.module_settings();


    },
    image: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

        mw.$("#mw-live-edit-sidebar-image-frame")
            .contents()
            .find("#mwimagecurrent")
            .attr("src", el.src)
        $('.mw-live-edit-component-options')
            .hide()
            .filter('#js-live-edit-image-settings-holder')
            .show()
    },
    initImage: function () {
        var url = mw.external_tool('imageeditor');
        $("#js-live-edit-image-settings-holder").append('<iframe src="' + url + '" frameborder="0" id="mw-live-edit-sidebar-image-frame"></iframe>');
    },
    icon: function () {
        mw.iconSelector.settingsUI(true);
        $('.mw-live-edit-component-options')
            .hide()
            .filter('#js-live-edit-icon-settings-holder')
            .show();

    },

    __is_sidebar_opened: function () {

        if (mw.liveEditSettings  &&  mw.liveEditSettings.active) {
            return true;
        }
    }
}


$(document).ready(function(){
    mw.on('liveEditSettingsReady', function(){
        mw.liveNodeSettings.initImage();
    });

})