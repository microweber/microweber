mw.AfterDrop = function(){


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
                        $(this).remove();
                        mw.notification.error('Error loading module.')
                    }
                }, true);
                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items == true) {
            need_re_init = true;
        }
        if (need_re_init == true) {
            if (!mw.isDrag) {
                if (typeof callback === 'function') {
                    callback.call(this);
                }
            }
        }
        mw.have_new_items = false;
    }


    this.init = function(){
        var scope = this;
        setTimeout(function(){

            mw.$(".mw-drag-current-bottom, .mw-drag-current-top").removeClass('mw-drag-current-bottom mw-drag-current-top')
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver')

            mw.$(".mw_drag_current").each(function(){
                $(this).removeClass('mw_drag_current').css({
                    visibility:'visible'
                })
            });
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver')
            mw.$(".mw-empty").removeClass('mw-empty');
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
    }

    this.dataReset = function(){
        this.data = {
            dropableAction:null,
            currentGrabbed:null,
            target:null,
            dropablePosition:null
        }
    }

    this.options = options || {};
    this.defaults = {
        classes:{
            edit:'edit',
            element:'element',
            module:'module',
            noDrop:'nodrop',
            allowDrop:'allow-drop',
            emptyElement:'mw-empty'

        },
        rows:['mw-row', 'mw-ui-row', 'row'],
        columns:['mw-col', 'mw-ui-col', 'col', 'column', 'columns'],
        columnMatches:'[class*="col-"]',
        rowMatches:'[class*="row-"]',
    }                                   
    this.settings = Object.assign(this.options, this.defaults);

    this.prepare = function(){
        this.cls = this.settings.classes;
        this.initCSS();
    }
    
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
    }


    this._isEditLike = function(node){
        node = node || this.data.target;
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.cls.edit,this.cls.module]);
    }

    this._canDrop = function(node){
        node = node || this.data.target;
        return !mw.tools.hasParentsWithClass(node, this.cls.noDrop) || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.cls.allowDrop, this.cls.noDrop]);
    }

    this._layoutInLayout = function(){
        if(!this.data.currentGrabbed){
            return false;
        }
        var currentGrabbedIsLayout = (this.data.currentGrabbed.getAttribute('data-module-name') == 'layouts' || mw.dragCurrent.getAttribute('data-type') == 'layouts');
        var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(this.data.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
        return {
            target:targetIsLayout,
            result:currentGrabbedIsLayout && !!targetIsLayout
        };
    }

    this.canDrop = function(node){
        node = node || this.data.target;
        return this._isEditLike(node) && this._canDrop(node) && !this._layoutInLayout().result;
    }



    this.analizePosition = function(event, node){
        node = node || this.data.target;
        var height = node.offsetHeight,
            offset = $(node).offset();
        if (event.pageY > offset.top + (height / 2)) {
            this.data.dropablePosition =  'bottom';
        } else {
            this.data.dropablePosition =  'top';
        }
    }
    this.analizeActionOfElement = function(node, pos){
        node = node || this.data.target;
        pos = node || this.data.dropablePosition;
    };
    this.afterAction = function(node, pos){
        if(!this._afterAction){
            this._afterAction = new mw.AfterDrop()
        }

        this._afterAction.init()

    }
    this.dropableHide = function(){

    }
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
                bottom:'after',
            },
            Inside:{
               top:'prepend',
               bottom:'append',
            }
        }

        if(!pos){

            return;
        }


        if(this.helpers.isElement()){
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
    }



    this.helpers = {
        scope:this,
        _isBlockCache:{},
        isBlockLevel:function(node){
            node = node || this.data.target;
            var name = node.nodeName;
            if(this._isBlockCache[name]){
                return this._isBlockCache[name];
            }
            var test = document.createElement(name);
            this.scope.fragment().appendChild(test);
            this._isBlockCache[name] = getComputedStyle(test).display == 'block';
            this.scope.fragment().removeChild(test);
            return this._isBlockCache[name];
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
            is = mw.tools.hasAnyOfClasses(node, scope.settings.rows);
            if(is){
                return is;
            }
            return mw.tools.matches(node, scope.settings.rowMatches);
        },
        isColLike:function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, scope.settings.columns);
            if(is){
                return is;
            }
            return mw.tools.matches(node, scope.settings.columnMatches);
        },
        isLayoutModule:function(node){
            node = node || this.scope.data.target;
            return false;

        },
        noop:function(){}
    }


    this.interactionTarget = function(next){
        node = this.data.target;
        if(next) node = node.parentNode;
        while(!this.helpers.isBlockLevel(node)){
            node = node.parentNode;
        }
        return node;
    }
    this.validateInteractionTarget = function(node){
        node = node || this.data.target;
        var cls = [
            this.cls.edit,
            this.cls.element,
            this.cls.module,
            this.cls.emptyElement
        ];
        while(node !== mwd.body){
            if(mw.tools.hasAnyOfClasses(node, cls)){
                return node;
            }
            node = node.parentNode;
        }
        return false;
    }
    this.on = function(events, listener) {
        events = events.trim().split(' ');
        for (var i=0 ; i<events.length; i++) {
             document.body.addEventListener(events[i], listener, false);
        }
    }
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
                        $(this).remove();
                        mw.notification.error('Error loading module.')
                    }
                }, true);
                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items == true) {
            need_re_init = true;
        }
        if (need_re_init == true) {
            if (!mw.isDrag) {
                if (typeof callback === 'function') {
                    callback.call(this);
                }
            }
        }
        mw.have_new_items = false;
    }
    this.whenUp = function(){
        var scope = this;
        this.on('mouseup touchend', function(){
            if(scope.data.currentGrabbed){
                scope.data.currentGrabbed = null;
            }
        });
    }
    this.fragment = function(){
        if(!this._fragment){
            this._fragment = document.createElement('div');
            this._fragment.style.visibility = 'hidden';
            this._fragment.style.position = 'absolute';
            this._fragment.style.width = '1px';
            this._fragment.style.height = '1px';
            document.body.appendChild(this._fragment)
        }
        return this._fragment;
    }
    this.getTarget = function(){
        var t = this.validateInteractionTarget();
        if(!t){
            return;
        }
        if(this.canDrop(t)) {
            return t;
        }
        else{
            return this.redirect(t);
        }
    }

    this.redirect = function(node){
        node = node || this.data.target;
        var islayOutInLayout = this._layoutInLayout(node);
        if(islayOutInLayout.result){
            var res =  this.validateInteractionTarget(/*node === islayOutInLayout.target ? islayOutInLayout.target.parentNode : */islayOutInLayout.target);
            return  res
        }
        return this.validateInteractionTarget(node.parentNode);
    }

    this.interactionAnalizer = function(e){
        var scope = this;
        mw.dropable.hide();
        if(this.data.currentGrabbed){
            scope.data.target = e.target;
            scope.interactionTarget();
            scope.data.target = scope.getTarget();

            if(scope.data.target){
                    scope.analizePosition(e);
                    scope.analizeAction();
                    mw.dropable.show();
            }
            else{
                    var near = mw.dropables.findNearest(e);
                    if(!!near.element){
                        scope.data.target = near.element;
                        scope.data.dropablePosition = near.position;
                        mw.dropables.findNearestException = true;
                        mw.dropable.show();
                    }
                    else{
                        scope.dataReset();
                    }

            }

            var el = $(scope.data.target);
            mw.currentDragMouseOver = scope.data.target;

            mw.dropables.set(scope.data.dropablePosition, el.offset(), el.height(), el.width());

            if(el[0] && !mw.tools.hasAnyOfClasses(el[0], ['mw-drag-current-'+scope.data.dropablePosition])){
                $('.mw-drag-current-top,.mw-drag-current-bottom').removeClass('mw-drag-current-top mw-drag-current-bottom');
                mw.tools.addClass(el[0], 'mw-drag-current-'+scope.data.dropablePosition)
            }
        }
    }

    this.whenMove = function(){
        var scope = this;
        this.on('mousemove touchmove', function(e){
            scope.interactionAnalizer(e)
        });
    }
    this.init = function(){
        this.fragment();
        this.prepare();
        //this.whenMove();
        //this.whenUp();
    }

    this.init()
}
