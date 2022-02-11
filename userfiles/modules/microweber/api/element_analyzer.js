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

        var style = document.createElement('style');
        document.getElementsByTagName('head')[0].appendChild(style);
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
        if (!this.data.currentGrabbed || !document.body.contains(this.data.currentGrabbed)) {
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
            return mw.tools.hasAnyOfClasses(node, ['mw-empty', 'mw-empty-element']);
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
        while(node && node !== document.body){
            if(mw.tools.hasAnyOfClasses(node, cls)){
                if (node.nodeName === 'IMG' && node.parentNode.nodeName === 'PICTURE' ) {
                    node = node.parentNode
                }
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
        if(node === document.body || node.parentNode === document.body) return null;
        return this.getTarget(node.parentNode);
    };

    this.interactionAnalizer = function(e){

        var scope = this;
        mw.dropable.hide();

        if(this.data.currentGrabbed){
            if (e.type.indexOf('touch') !== -1) {
                var coords = mw.event.page(e);
                scope.data.target = document.elementFromPoint(coords.x, coords.y);
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
