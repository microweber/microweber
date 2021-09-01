
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
        mw.dropable.hide();
        $(document.body).on('drop', function(e){
            e = e.originalEvent || e;

            if(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                var bg = mw.tools.firstParentOrCurrentWithClass(e.target, 'background-image-holder');
                if(bg) {
                    e.preventDefault();
                    mw.uploader().uploadFile(e.dataTransfer.files[0], function(data){
                        e.target.style.backgroundImage = 'url(' + data.src + ')';
                        mw.wysiwyg.change(e.target);
                    });
                } else if(e.target.nodeName === 'IMG') {
                    e.preventDefault();
                    mw.uploader().uploadFile(e.dataTransfer.files[0], function(data){
                        e.target.src = data.src;
                        mw.wysiwyg.change(e.target);
                    });
                }

            }
        });
    },
    userInteractionClasses:function(){
        var bgHolders = document.querySelectorAll(".edit.background-image-holder, .edit .background-image-holder, .edit[style*='background-image'], .edit [style*='background-image']");
        var noEditModules = document.querySelectorAll('.module' + mw.noEditModules.join(',.module'));
        var edits = document.querySelectorAll('.edit');
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
            var all = mw.ea.helpers.getElementsLike(":not(.element,.noelement)", edits[i2]), i2a = 0;
            var allAllowDrops = edits[i2].querySelectorAll(".allow-drop"), i3a = 0;
            for( ; i3a<allAllowDrops.length; i3a++){
                mw.tools.addClass(allAllowDrops[i3a], 'element');
            }
            for( ; i2a<all.length; i2a++){
                if(!mw.tools.hasClass(all[i2a], 'module')){
                    if(mw.ea.canDrop(all[i2a])/* && !mw.tools.hasClass(all[i2a], 'noelement')*/){
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

        // images
        var allImg = document.querySelectorAll('picture:not(.element,.noelement)');
        var iImg = 0, l = allImg.length;
        for ( ; iImg < l ; iImg++ ) {
            var el = allImg[iImg].querySelector('.element');
            if(el) {
                el.classList.remove('element');
                allImg[iImg].classList.add('element');
            }
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
        if (coords.y > v) {
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
        if(element /*&& this._elementRegister !== element*/) {
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
            mw.trigger("ElementOver", [mw.image.currentResizing[0], ev]);
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
    if (mw.drag.columns.resizing === false ) {
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
          while (!bg || bgp === document.body){
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
        else if (bg && bgCanChange && mw.tools.isEditable(bgTarget)) {
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
