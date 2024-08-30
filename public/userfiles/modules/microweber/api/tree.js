
/********************************************************


 var myTree = new mw.tree({

});


 ********************************************************/




(function(){
  /*   mw.lib.require('jqueryui');

    mw.lib.require('nestedSortable');

   mw.require('tree-icons.js'); */


    var mwtree = function(config){

        var scope = this;

        this.config = function(config){

            window.mwtree = (typeof window.mwtree === 'undefined' ? 0 : window.mwtree)+1;

            if(!config.id && typeof config.saveState === undefined){
                config.saveState = false;
            }

            var defaults = {
                data:[],
                openedClass:'opened',
                selectedClass:'selected',
                skin:'default',
                multiPageSelect:true,
                saveState: true,
                stateStorage: {
                    get: function (id) {
                        return mw.storage.get( id);
                    },
                    set: function (id, dataToSave) {
                        mw.storage.set(id, dataToSave);
                    }
                },
                sortable:false,
                resizable:false,
                resizableOn: 'tree', // 'tree' | 'treeParent'
                nestedSortable: false,
                singleSelect: false,
                clickSelect: false,
                selectedData:[],
                skip:[],
                contextMenu:false,
                append:false,
                prepend:false,
                selectable:false,
                selectableNodes:false, // 'singleSelect' | 'singleSelectToggle' | 'multiSelect' | 'multiSelectToggle'
                disableSelectTypes:[],
                filter:false,
                cantSelectTypes: [],
                document: document,
                _tempRender: true,
                filterRemoteURL: null,
                filterRemoteKey: 'keyword',
                toggleSelect: true,
                contextMenuMode: 'inline', // 'inline' | 'dropdown'
            };


            var options = $.extend({}, defaults, config);



            options.element = mw.$(options.element)[0];
            options.data = options.data || [];

            this.options = options;
            this.document = options.document;
            this._selectionChangeDisable = false;

            this.stateStorage = this.options.stateStorage;

            if(this.options.selectedData){
                this.selectedData = this.options.selectedData;
            }
            else{
                this.selectedData = [];
            }
        };
        this.filterLocal = function(val, key){
            key = key || 'title';
            val = (val || '').toLowerCase().trim();


            if(!val){
                this.dispatch( 'searchResults')
                scope.showAll();
            }
            else{
                var hasResults = false;
                scope.options.data.forEach(function(item){
                    if(item[key].toLowerCase().indexOf(val) === -1){
                        scope.hide(item);
                    }
                    else{
                        scope.show(item);
                        hasResults = true;
                    }
                });
                this.dispatch(hasResults ? 'searchResults' : 'searchNoResults')
                mw.$(scope.options.element)[hasResults ? 'removeClass' : 'addClass']('mw-tree-no-results')
            }
        };

        this._filterRemoteTime = null;
        this.filterRemote = function(val, key){
            clearTimeout(this._filterRemoteTime);
            this._filterRemoteTime = setTimeout(function () {
                key = key || 'title';
                val = (val || '').toLowerCase().trim();
                var ts = {};
                ts[scope.options.filterRemoteKey] = val;
                $.get(scope.options.filterRemoteURL, ts, function (data) {
                    scope.setData(data);
                });
            }, 777);
        };

        this.filter = function(val, key){

            if (!!this.options.filterRemoteURL && !!this.options.filterRemoteKey) {
                this.filterRemote(val, key);
            } else {
                this.filterLocal(val, key);
            }
        };

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.search = function(){
            if(this.options.searchInput === true) {
                const inputTemplate = mw.element(`
                <div>
                    <label class="live-edit-label">${mw.lang('Search for page or category')}</label>
                    <div class="form-control-live-edit-label-wrapper">
                        <input type="text" class="form-control-live-edit-input">
                        <span class="form-control-live-edit-bottom-effect"></span>
                    </div>
                </div>

                `);
                this.options.searchInput = inputTemplate.find('input').get(0);


                if(this.options.searchInputClassName){
                    this.options.searchInput.className = this.options.searchInputClassName;
                }

                this.options.searchInput.placeholder = this.options.searchInputPlaceholder || mw.lang('Search');

                if(this.options.searchInputSticky) {
                    minputTemplate.css({
                        position: 'sticky',
                        top: '20px',
                        zIndex: '1',
                        margin: '9px 15px 15px 0',
                        width: '100%',
                        maxWidth: '100%',

                    });
                }

                this.options.searchInput.addEventListener('input', function () {
                    scope.search();
                });

                setTimeout(function (){
                    mw.$(scope.options.element).before(inputTemplate.get(0));

                }, 200)

            }
            this._seachInput = mw.$(this.options.searchInput);
            if(!this._seachInput[0] || this._seachInput[0]._tree) return;
            this._seachInput[0]._tree = this;
            var scope = this;
            this._seachInput.on('input', function(){
                scope.filter(this.value);
            });
        };

        this.skip = function(itemData){
            if(this.options.skip && this.options.skip.length > 0){
                for( var n = 0; n < scope.options.skip.length; n++ ){
                    var item = scope.options.skip[n];

                    var case1 = (item.id == itemData.id && item.type == itemData.type);
                    var case2 = (itemData.parent_id != 0 && itemData.parent_id == item.id && item.type == itemData.type);




                    if(case1 || case2){
                        return true;
                    }
                }
                return false;
            }
        };

        this.prepareData = function(){
            if(typeof this.options.filter === 'object'){
                var final = [], scope = this;
                for( var i in this.options.filter){
                    $.each(this.options.data, function(){
                        if(this[i] && this[i] == scope.options.filter[i]){
                            final.push(this);
                        }
                    });
                }
                this.options.data = final;
            }
        };


        this._postCreated = [];

        this.json2ul = function(){
            this.list = scope.document.createElement( 'ul' );
            this.list._tree = this;
            this.options.id = this.options.id || ( 'mw-tree-' + window.mwtree );
            this.list.id = this.options.id;
            this.list.className = 'mw-defaults mw-tree-nav mw-tree-nav-skin-' + this.options.skin;
            this.list._id = 0;
            this.options.data.forEach(function(item){
                var list = scope.getParent(item);
                if(list){
                    var li = scope.createItem(item);
                    if(li){
                        list.appendChild(li);
                    }
                }
                else if(typeof list === 'undefined'){
                    scope._postCreated.push(item);
                }
            });
            if(this.options._tempRender) {
                this.tempRend();
            }
        };



        this._tempPrepare = function () {
            for (var i=0; i<this._postCreated.length; i++) {
                var it = this._postCreated[i];
                if(it.parent_id !== 0) {
                    var has = this.options.data.find(function (a) {
                        return a.id == it.parent_id; // 1 == '1'
                    });
                    if(!has) {
                        it.parent_id = 0;
                        it.parent_type = "page";
                    }
                }
            }
        };

        this.tempRend = function () {
            this._tempPrepare()
            var curr = scope._postCreated[0];
            var max = 10000, itr = 0;

            while(scope._postCreated.length && itr<max){
                itr++;
                var index = scope._postCreated.indexOf(curr);
                var selector = '#' + scope.options.id + '-' + curr.type + '-'  + curr.id;
                var lastc = selector.charAt(selector.length - 1);
                if( lastc === '.' || lastc === '#') {
                    selector = selector.substring(0, selector.length - 1);
                }
                var it = mw.$(selector)[0];
                if(it){
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                    continue;
                }
                var list = scope.getParent(curr);

                if(list && !$(selector)[0]){
                    var li = scope.createItem(curr);
                    if(li){
                        list.appendChild(li);
                    }
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                }
                else if(typeof list === 'undefined'){
                    curr = scope._postCreated[index+1] || scope._postCreated[0];
                }
            }

        };

        function triggerChange() {
            if(!this._selectionChangeDisable) {
                mw.$(scope).trigger('selectionChange', [scope.selectedData]);
                scope.dispatch('selectionChange', scope.selectedData)
            }
        }

        this.setData = function(newData){
            this.options.data = newData;
            this._postCreated = [];
            this._ids = [];
            this.init();
        };

        this.saveState = function(){
            if(!this.options.saveState) return;
            var data = [];
            mw.$( 'li.' + this.options.openedClass, this.list  ).each(function(){
                if(this._data) {
                    data.push({type:this._data.type, id:this._data.id});
                }
            });

            this.stateStorage.set(this.options.id, data);
        };

        this.restoreState = function(){
            if(!this.options.saveState) return;
            var data = this.stateStorage.get(this.options.id);
            if(!data) return;
            try{
                $.each(data, function(){
                    if(typeof this.id === 'string'){
                        this.id = parseInt(this.id, 10);
                    }
                    scope.open(this.id, this.type);
                });
            }
            catch(e){ }
        };

        this.manageUnselected = function(){
            mw.$('input:not(:checked)', this.list).each(function(){
                var li = scope.parentLi(this);
                mw.$(li).removeClass(scope.options.selectedClass)
            });
        };

        this.analizeLi = function(li){
            if(typeof li === 'string'){
                li = decodeURIComponent(li).trim();
                if(/^\d+$/.test(li)){
                    li = parseInt(li, 10);
                }
                else{
                    return mw.$(li)[0];
                }
            }
            return li;
        };

        this.select = function(li, type, trigger){
            if(typeof trigger === 'undefined') {
                trigger = true;
            }
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.select(this, type, trigger);
                });
                return;
            }
            li = this.get(li, type);
            if(li && li.dataset && this.options.cantSelectTypes.indexOf(li.dataset.type) === -1){
                li.classList.add(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = true;
            }

            this.manageUnselected();
            this.getSelected();

            if (trigger) {
                triggerChange();
            }
        };



        this.unselect = function(li, type, trigger){
            if(typeof trigger === 'undefined') {
                trigger = true;
            }
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.unselect(this, type, trigger);
                });
                return;
            }
            li = this.get(li, type);
            if(li){
                li.classList.remove(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = false;
            }
            this.manageUnselected();
            this.getSelected();
            if (trigger) {
                triggerChange();
            }
        };

        this.get = function(li, type){
            if(typeof li === 'undefined') return false;
            if(li === null) return false;
            if(li.nodeType) return li;
            li = this.analizeLi(li);
            if(typeof li === 'object' && typeof li.id !== 'undefined'){
                return this.get(li.id, li.type);
            }
            if(typeof li === 'object' && li.constructor === Number){
                li = parseInt(li);
            }
            if(typeof li === 'number'){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            if(typeof li === 'string' && /^\d+$/.test(li)){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            return li;
        };

        this.isSelected = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            var input = li.querySelector('input');
            if(!input) return false;
            return input.checked === true;
        };
        this.toggleSelect = function(li, type){
            if(this.isSelected(li, type)){
                this.unselect(li, type);
            }
            else{
                this.select(li, type);
            }
        };

        this.selectAll = function(trigger){
            if(typeof trigger === 'undefined') {
                trigger = true;
            }
            this._selectionChangeDisable = true;

            this.select(this.options.data);
            this._selectionChangeDisable = false;
            if(trigger) {
                triggerChange();
            }
        };

        this.unselectAll = function(trigger){
            if(typeof trigger === 'undefined') {
                trigger = true;
            }
            this._selectionChangeDisable = true;
            this.unselect(this.selectedData);
            this._selectionChangeDisable = false;
            if(trigger) {
                triggerChange();
            }

        };

        this.open = function(li, type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.open(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };
        this.show = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.show(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove('mw-tree-item-hidden');
            mw.$(li).parents("li").each(function(){

                scope.show(this);
                scope.open(this);
            });
            setTimeout(function(li){
                li.classList.remove('mw-tree-item-hidden');
            }, 10, li)
        };

        this.showAll = function(){
            mw.$(this.list.querySelectorAll('li')).removeClass('mw-tree-item-hidden');
        };

        this.hide = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.hide(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add('mw-tree-item-hidden');
        };

        this.hideAll = function(){
            mw.$(this.list.querySelectorAll('li')).addClass('mw-tree-item-hidden');
        };

        this.close = function(li,type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.close(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };

        this.toggle = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            li.classList.toggle(this.options.openedClass);
            this.saveState();
        };

        this.openAll = function(){
            var all = this.list.querySelectorAll('li');
            mw.$(all).each(function(){
                scope.open(this, undefined, true);
            });
            this.saveState();
        };

        this.closeAll = function(){
            var all = this.list.querySelectorAll('li.'+this.options.openedClass);
            mw.$(all).each(function(){
                scope.close(this, undefined, true);
            });
            this.saveState();
        };

        this.button = function(){
            var btn = scope.document.createElement('mwbutton');
            btn.className = 'mw-tree-toggler';

            btn.onclick = function(){
                scope.toggle(mw.tools.firstParentWithTag(this, 'li'));
            };
            return btn;
        };

        this.addButtons = function(){
            var all = this.list.querySelectorAll('li ul.pre-init'), i=0;
            for( ; i<all.length; i++ ){
                var ul = all[i];
                ul.classList.remove('pre-init');
                mw.$(ul).parent().children('.mw-tree-item-content-root').prepend(this.button());
            }
        };

        this.checkBox = function(element){
            if(this.options.cantSelectTypes.indexOf(element.dataset.type) !== -1){
                return scope.document.createTextNode('');
            }
            var itype = 'radio';

            if(this.options.singleSelect){

            }
            else if(this.options.multiPageSelect || element._data.type !== 'page'){
                itype = 'checkbox';
            }

            var label = scope.document.createElement('tree-label');
            var input = scope.document.createElement('input');
            var span = scope.document.createElement('span');
            input.type = itype;
            input._data = element._data;
            input.value = element._data.id;
            input.name = this.list.id;
            input.className = 'form-check-input';
            label.className = 'form-check';

            label.appendChild(input);


            label.appendChild(span);

            /*input.onchange = function(){
                var li = scope.parentLi(this);
                mw.$(li)[this.checked?'addClass':'removeClass'](scope.options.selectedClass)
                var data = scope.getSelected();
                scope.manageUnselected()
                mw.$(scope).trigger('change', [data]);
            }*/
            return label;
        };

        this.parentLi = function(scope){
            if(!scope) return;
            if(scope.nodeName === 'LI'){
                return scope;
            }
            while(scope.parentNode){
                scope = scope.parentNode;
                if(scope.nodeName === 'LI'){
                    return scope;
                }
            }
        };

        this.getSelected = function(){
            var selected = [];
            var all = this.list.querySelectorAll('li.selected');
            mw.$(all).each(function(){
                if(this._data) selected.push(this._data);
            });
            this.selectedData = selected;
            this.options.selectedData = selected;
            return selected;
        };

        this.decorate = function(element){
            var _selectable = this.options.selectable && this.options.disableSelectTypes.indexOf(element._data.type) === -1;
            if(_selectable){
                mw.$(element.querySelector('.mw-tree-item-content')).prepend(this.checkBox(element))
            } else {
                if(!this.options.selectableNodes) {
                    var content = mw.$(element.querySelector('.mw-tree-item-content'))
                    content.css('pointerEvents', 'none')

                    setTimeout((content)=>{
                        content.children().css('pointerEvents', 'all')
                    }, 10, content)

                }

            }
            var cont = element.querySelector('.mw-tree-item-content');
            cont.classList.add('item-selectable-' + _selectable);
            cont.appendChild(this.contextMenu(element));
            this.sortable();
            this.nestedSortable();
        };

        this.resizable = function(){
             if(this.options.resizable){
                var resEl;
                if(this.options.resizableOn === 'tree') {
                    resEl = mw.$(this.list);
                } else if(this.options.resizableOn === 'treeParent') {
                    resEl = mw.$(this.options.element);
                }

                setTimeout(function (){
                    resEl.resizable({
                        maxWidth: 650,
                        minWidth: 200,
                        handles: "e",
                        resize: function () {
                            if( resEl[0].id ) {
                                scope.stateStorage.set('size-' + resEl[0].id, resEl[0].style.width);
                            }
                        }
                    });

                    if(resEl[0].id && scope.stateStorage.get('size-' + resEl[0].id)) {
                        resEl[0].style.width = scope.stateStorage.get('size-' + resEl[0].id) ;
                    }
                }, 300);

            }

        };

        var _orderChangeHandle = function (e, ui){
            setTimeout(function(){
                var old = $.extend({},ui.item[0]._data);
                var obj = ui.item[0]._data;
                var objParent = ui.item[0].parentNode.parentNode._data;
                ui.item[0].dataset.parent_id = objParent ? objParent.id : 0;

                obj.parent_id = objParent ? objParent.id : 0;
                obj.parent_type = objParent ? objParent.id : 'page';
                var newdata = [];
                mw.$('li', scope.list).each(function(){
                    if(this._data) newdata.push(this._data);
                });
                scope.options.data = newdata;
                var local = [];
                mw.$(ui.item[0].parentNode).children('li').each(function(){
                    if(this._data) {
                        local.push(this._data.id);
                    }
                });
                //$(scope.list).remove();
                //scope.init();
                mw.$(scope).trigger('orderChange', [obj, scope.options.data, old, local]);
                scope.dispatch('orderChange', [obj, scope.options.data, old, local]);
            }, 110);
        };

        this.sortable = function(){

            if(this.options.sortable){
                var selector = '.type-category, .type-page';
                if(typeof this.options.sortable === 'string') {
                    selector = this.options.sortable;
                }
                var items = mw.$(this.list);
                mw.$('ul', this.list).each(function () {
                    items.push(this);
                });
                if(this.options.createSortableHandle) {
                    this.options.createSortableHandle(this.list);
                }
                if($.fn.sortable) {
                    items.sortable({
                        items: selector,
                        start: function(){ // firefox triggers click when drag ends
                            scope._disableClick = true;
                        },
                        stop: function(){
                            setTimeout(() => {scope._disableClick = false;}, 78)

                        },
                        axis:'y',
                        listType:'ul',
                        handle: scope.options.sortableHandle || '.mw-tree-item-title',
                        update:function(e, ui){

                            _orderChangeHandle(e, ui)
                        }
                    });
                } else {
                    console.log('$.fn.sortable is not defined')
                }

            }
        };

        this.getSameLevelObjects = function(object){
             var parent = this.get(object).parentElement;
            return Array.from(parent.children).map(function (li) {
                return li._data;
            });
        }
        this.getChildObjects = function(parentObject){
            return Array.from(this.get(parentObject).children).map(function (li) {
                return li._data;
            });
        };

        this.nestedSortable = function(){
            if(typeof this.options.nestedSortable === 'string') {
                mw.$(this.options.nestedSortable, this.list).each(function (){
                    $(this).nestedSortable({
                        items: ".type-category",
                        listType:'ul',
                        handle: scope.options.nestedSortableHandle || '.mw-tree-item-title',
                        update:function(e, ui){

                            _orderChangeHandle(e, ui)
                        },
                        start: function(){ // firefox triggers click when drag ends
                            scope._disableClick = true;
                        },
                        stop: function(){
                            setTimeout(() => {scope._disableClick = false;}, 78)
                        },
                    });
                })
            } else if (this.options.nestedSortable === true) {
                mw.$('ul', this.list).nestedSortable({
                    items: ".type-category",
                    listType:'ul',
                    handle:'.mw-tree-item-title',
                    start: function(){ // firefox triggers click when drag ends
                        scope._disableClick = true;
                    },
                    stop: function(){
                        setTimeout(() => {scope._disableClick = false;}, 78)
                    },
                    update:function(e, ui){
                        _orderChangeHandle(e, ui);
                    }
                });
            }


        };

        var _contextMenuOnce = null;
        this.contextMenu = function(element){
            var menu = scope.document.createElement('span');
            menu.className = 'mw-tree-context-menu';
            if(this.options.contextMenu) {
                var menuButton = scope.document.createElement('span');
                var menuContent = scope.document.createElement('span');
                menuButton.className = 'mw-tree-context-menu-content-button';
                menuButton.innerHTML = ' ';
                menuButton.addEventListener('click', function (e){
                   e.stopImmediatePropagation();
                   Array.from(scope.document.querySelectorAll('.context-menu-active')).forEach(function (node){
                       if(node !== element) {
                           node.classList.remove('context-menu-active');
                       }
                   });
                   element.classList.toggle('context-menu-active');
                   if(scope.options.contextMenuMode === 'dropdown'){

                    scope.document.querySelectorAll('.mw-tree-context-menu-content-active').forEach(function(node){
                        node.classList.remove('mw-tree-context-menu-content-active');
                    })

                    if(element.classList.contains('context-menu-active')){
                        var off = mw.element(menuButton).offset();
                        menuButton.$$menuContent.style.top = off.offsetTop + 'px';
                        menuButton.$$menuContent.style.left = off.offsetLeft + 'px';
                        menuButton.$$menuContent.style.display =  'block';
                        menuButton.$$menuContent.classList.add('mw-tree-context-menu-content-active');

                    } else {
                        menuButton.$$menuContent.style.display =  'none';
                        menuButton.$$menuContent.classList.remove('mw-tree-context-menu-content-active');
                    }
                   }
                });

                menuContent.className = 'mw-tree-context-menu-content mw-tree-context-menu-content-' + this.options.skin + ' mw-tree-context-menu-content-mode-' + this.options.contextMenuMode;
                menu.appendChild(menuButton);
                menuButton.$$menuContent = menuContent;
                menuContent.$$menuButton = menuButton;
                if(this.options.contextMenuMode === 'inline') {
                    menu.appendChild(menuContent);
                } else if(this.options.contextMenuMode === 'dropdown') {
                    scope.document.body.appendChild(menuContent);
                }

                    $.each(this.options.contextMenu, function(){
                    if(!this.filter || this.filter(element._data, element)){
                        var menuitem = scope.document.createElement('span');
                        var icon = scope.document.createElement('span');
                        menuitem.title = this.title;
                        menuitem.innerHTML = iconResolver(this) +  this.title;
                        menuitem.className = ((this.className || '') + ' mw-tree-context-menu-item ').trim();
                        if(this.icon.indexOf('<') === -1) {
                            icon.className = 'mw-tree-context-menu-icon ' + this.icon;
                        } else {
                            icon.className = 'mw-tree-context-menu-icon';
                            icon.innerHTML = this.icon;
                        }

                        menuitem.prepend(icon);
                        menuContent.appendChild(menuitem);
                        (function(menuitem, element, obj){
                            menuitem.onclick = function(event){
                                event.stopImmediatePropagation();
                                if(obj.action){
                                    obj.action.call(element, element, element._data, menuitem)
                                }
                            };
                        })(menuitem, element, this);
                    }

                });
                if(!_contextMenuOnce) {
                    _contextMenuOnce = true;
                    if(scope.document.defaultView) {
                        scope.document.defaultView.addEventListener('scroll', function (e) {
                            Array.from(scope.document.querySelectorAll('.mw-tree-context-menu-content-active')).forEach(function (node) {
                                var off = mw.element(node.$$menuButton).offset();
                                node.style.top = off.offsetTop + 'px';
                                node.style.left = off.offsetLeft + 'px';
                            });
                        })
                    }
                    scope.document.body.addEventListener('click', function (e){
                        var active =  Array.from(scope.document.querySelectorAll('.context-menu-active,.mw-tree-context-menu-content-active'));
                        if(active.length) {
                            if(!scope.list.contains(e.target)) {
                                active.forEach(function (node){
                                    node.classList.remove('context-menu-active', 'mw-tree-context-menu-content-active');
                                });
                            } else {
                                var li = mw.tools.firstParentOrCurrentWithTag(e.target, 'li');
                                if(li) {
                                    active.forEach(function (node){
                                        if(!li.contains(node)) {
                                            node.classList.remove('context-menu-active', 'mw-tree-context-menu-content-active');
                                        }

                                    });
                                }

                            }
                        }

                    })
                }
            }
            return menu

        };

        this.rend = function(){
            if(this.options.element){
                var el = mw.$(this.options.element);
                if(el.length!==0){
                    el.empty().append(this.list);
                }
            }
        };

        this._ids = [];


        var iconResolver = function(item) {
            if(!mw.iconResolver) {
                return ''
            }

            return mw.iconResolver(item.icon) || mw.iconResolver(item.subtype) || mw.iconResolver(item.type)
        }

        this.createItem = function(item){
            var selector = '#'+scope.options.id + '-' + item.type+'-'+item.id;
            if(this._ids.indexOf(selector) !== -1) return false;
            this._ids.push(selector);
            var li = scope.document.createElement('li');
            li.dataset.type = item.type;
            li.dataset.id = item.id;
            li.dataset.parent_id = item.parent_id;
            var skip = this.skip(item);
            li.className = 'type-' + item.type + ' subtype-'+ (item.icon || item.subtype) + ' skip-' + (skip || 'none') + ' tree-item-active-' + (item.is_active !== 0);
            var container = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            var titleNode = document.createElement('span');
            titleNode.className = 'form-check-label mw-tree-item-title';
            titleNode.innerHTML = iconResolver(item) + item.title;
            if(!item.is_active){
                titleNode.title = mw.lang('Category is hidden');
            }

            container.addEventListener('click', function (e){
                if(e.target === container || e.target === titleNode) {
                    scope.dispatch('itemClick', item);
                }
            });

            container.appendChild(titleNode)

            li._data = item;
            li.id = scope.options.id + '-' + item.type+'-'+item.id;
            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            if(!skip){
                container.onclick = function(event){

                    if(scope._disableClick) {
                        return;
                    }

                    var target = event.target;
                    var canSelect = true;
                    if(scope.options.rowSelect === false) {
                        if(target.nodeName !== 'TREE-LABEL') {
                            canSelect = false;
                        }
                    }
                    if(canSelect && (scope.options.selectable || scope.options.selectableNodes)) {
                        if(scope.options.selectableNodes === 'singleSelect' || scope.options.selectableNodes ===  'singleSelectToggle') {

                            scope.unselect(scope.selectedData.filter(function (obj){
                                return li._data.id !== obj.id || li._data.type !== obj.type
                            }), undefined, false)
                        }
                        if(scope.options.toggleSelect || scope.options.selectableNodes === 'toggle') {
                            scope.toggleSelect(li);
                        } else {
                            scope.select(li);
                        }
                    }
                };
                this.decorate(li);
            }


            return li;
        };



        this.additional = function (obj) {
            var li = scope.document.createElement('li');
            li.className = 'mw-tree-additional-item';
            var container = scope.document.createElement('span');
            var containerTitle = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            containerTitle.className = "mw-tree-item-title";
            container.appendChild(containerTitle);

            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            mw.$(containerTitle).append(iconResolver(obj))
            var title = scope.document.createElement('span');
            title.innerHTML = obj.title;
            title.alt = obj.title;
            containerTitle.appendChild(title);
            li.onclick = function (ev) {
                if(obj.action){
                    obj.action.call(this, obj)
                }
            };
            return li;
        }

        this.createList = function(item){
            var nlist = scope.document.createElement('ul');
            nlist.dataset.type = item.parent_type;
            nlist.dataset.id = item.parent_id;
            nlist.className = 'pre-init';
            return nlist;
        };

        this.getParent = function(item, isTemp){
            if(!item.parent_id) return this.list;
            var findul = this.list.querySelector('ul[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            var findli = this.list.querySelector('li[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            if(findul){
                return findul;
            }
            else if(findli){
                var nlistwrap = this.createItem(item);
                if(!nlistwrap) return false;
                var nlist = this.createList(item);
                nlist.appendChild(nlistwrap);
                findli.appendChild(nlist);
                return false;
            }
        };

        this.append = function(){
            if(this.options.append){
                $.each(this.options.append, function(){
                    scope.list.appendChild(scope.additional(this))
                })
            }
        };

        this.prepend = function(){
            if(this.options.prepend){
                $.each(this.options.append, function(){
                    mw.$(scope.list).prepend(scope.additional(this))
                })
            }
        };

        this.addHelperClasses = function(root, level){
            level = (level || 0) + 1;
            root = root || this.list;
            mw.$( root.children ).addClass('level-'+level).each(function(){
                var ch = this.querySelector('ul');
                if(ch){
                    mw.$(this).addClass('has-children')
                    scope.addHelperClasses(ch, level);
                }
            })
        };

        this.loadSelected = function(){
            if(this.selectedData){
                scope.select(this.selectedData, undefined, false);
                if(this.options.openSelectedDataItems){
                    this.selectedData.forEach(obj => {
                        jQuery(this.get(obj)).parents('li').each(function(){
                            scope.open(this)
                        })
                    })
                }
            }
        };
        this.init = function(){

            this.prepareData();
            this.json2ul();
            this.addButtons();
            this.rend();
            this.append();
            this.prepend();
            this.addHelperClasses();
            this.restoreState();
            this.loadSelected();
            this.search();
            this.resizable();
            setTimeout(function(){
                mw.$(scope).trigger('ready');
            }, 78)
        };

        this.config(config);
        this.init();
    };
    mw.tree = mwtree;
    mw.tree.get = function (a) {
        a = mw.$(a)[0];
        if(!a) return;
        if(mw.tools.hasClass(a, 'mw-tree-nav')){
            return a._tree;
        }
        var child = a.querySelector('.mw-tree-nav');
        if(child) return child._tree;
        var parent = mw.tools.firstParentWithClass(a, 'mw-tree-nav');

        if(parent) {
            return parent._tree;
        }
    }


})();
