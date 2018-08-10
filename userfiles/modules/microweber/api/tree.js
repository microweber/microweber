
/********************************************************


var myTree = new mw.tree({

});


********************************************************/




mw.lib.require('nestedSortable');

(function(){
    var mwtree = function(options){
        var scope = this;

        window.mwtree = (typeof window.mwtree === 'undefined' ? 0 : window.mwtree)+1;

        options.data = options.data || [];
        this.options = options;
        this.options.openedClass = this.options.openedClass || 'opened';
        this.options.selectedClass = this.options.selectedClass || 'selected';
        this.options.skin = this.options.skin || 'default';
        this.options.multiPageSelect =  this.options.multiPageSelect === undefined ? true : this.options.multiPageSelect;
        this.options.saveState = this.options.saveState === undefined ? true : this.options.saveState;
        this.options.sortable = this.options.sortable === undefined ? false : this.options.sortable;


        this.selectedData = [];

        this.json2ul = function(){
            this.list = document.createElement( 'ul' );
            this.options.id = this.options.id || ( 'mw-tree-' + window.mwtree );
            this.list.id = this.options.id;
            this.list.className = 'mw-defaults mw-tree-nav mw-tree-nav-skin-'+this.options.skin;
            this.list._id = 0;
            this.options.data.forEach(function(item){
                var list = scope.getParent(item);
                if(list){
                    list.appendChild(scope.createItem(item));
                }
            });
        }

        this.setData = function(newData){
            this.options.data = newData;
            $(this.list).remove();
            this.init();
        }

        this.saveState = function(){
            if(!this.options.saveState) return;
            var data = [];
            $( 'li.' + this.options.openedClass, this.list  ).each(function(){
                data.push({type:this._data.type, id:this._data.id})
            });

            mw.storage.set(this.options.id, data)
        }

        this.restoreState = function(){
            if(!this.options.saveState) return;
            var data = mw.storage.get(this.options.id);
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
        }

        this.manageUnselected = function(){
            $('input:not(:checked)', this.list).each(function(){
                var li = scope.parentLi(this);
                $(li).removeClass(scope.options.selectedClass)
            });
        }

        this.select = function(li, type){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            li.classList.add(this.options.selectedClass);
            var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
            if(input) input.checked = true;
            this.manageUnselected()
            this.getSelected();
        }

        this.unselect = function(li, type){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            li.classList.remove(this.options.selectedClass);
            var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
            if(input) input.checked = false;
            this.manageUnselected()
            this.getSelected();
        }

        this.isSelected = function(li, type){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
            return input.checked === true;
        }
        this.toggleSelect = function(li, type){
            if(this.isSelected(li, type)){
               this.unselect(li, type)
            }
            else{
               this.select(li, type)
            }
        }

        this.open = function(li, type, _skipsave){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            li.classList.add(this.options.openedClass);
            $(li.children).filter('button').addClass(this.options.openedClass);
            if(!_skipsave) this.saveState()

        }

        this.close = function(li,type, _skipsave){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            li.classList.remove(this.options.openedClass);
            $(li.children).filter('button').removeClass(this.options.openedClass);
            if(!_skipsave) this.saveState()
        }

        this.toggle = function(li, type){
            if(typeof li === 'number'){
                if(!type) return;
                li = this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            li.classList.toggle(this.options.openedClass);
            $(li.children).filter('button').toggleClass(this.options.openedClass);
            this.saveState()
        }

        this.openAll = function(){
            var all = this.list.querySelectorAll('li');
            console.log(all)
            $(all).each(function(){
                scope.open(this, undefined, true);
            });
            this.saveState()
        }

        this.closeAll = function(){
            var all = this.list.querySelectorAll('li.'+this.options.openedClass);
            $(all).each(function(){
                scope.close(this, undefined, true);
            });
            this.saveState()
        }

        this.button = function(){
            var btn = document.createElement('button');
            btn.className = 'mw-tree-toggler';
            btn.onclick = function(){
                scope.toggle(this.parentNode);
            }
            return btn;
        };

        this.addButtons = function(){
            var all = this.list.querySelectorAll('li ul.pre-init'), i=0;
            for( ; i<all.length; i++ ){
                var ul = all[i];
                ul.classList.remove('pre-init');
                $(ul).parent().prepend(this.button());
            }
        }

        this.checkBox = function(element){
            var itype = 'radio';
            if(this.options.multiPageSelect || element._data.type != 'page'){
                itype = 'checkbox';
            }
            var label = document.createElement('x-label');
            var input = document.createElement('input');
            var span = document.createElement('span');
            input.type = itype;
            input._data = element._data;
            input.value = element._data.id;
            input.name = this.list.id;
            label.className = 'mw-ui-check'
            label.appendChild(input)
            label.appendChild(span);
            input.onchange = function(){
                var li = scope.parentLi(this);
                $(li)[this.checked?'addClass':'removeClass'](scope.options.selectedClass)
                var data = scope.getSelected();
                scope.manageUnselected()
                $(scope).trigger('change', [data]);
            }
            return label;
        }

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
        }

        this.getSelected = function(){
            var selected = [];
            var all = this.list.querySelectorAll('input:checked');
            $(all).each(function(){
                selected.push(this._data)
            });
            this.selectedData = selected;
            return selected;
        }

        this.decorate = function(element){
            if(this.options.selectable){
                $(element.querySelector('.mw-tree-item-content')).prepend(this.checkBox(element))
            }

                element.querySelector('.mw-tree-item-content').appendChild(this.contextMenu(element));

            if(this.options.sortable){
                this.sortable()
            }

        }

        this.sortable = function(element){
            $('ul', this.list).nestedSortable({
                items: ".type-category",
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){

                    var old = $.extend({},ui.item[0]._data);
                    var obj = ui.item[0]._data;
                    var objParent = ui.item[0].parentNode.parentNode._data;
                    ui.item[0].dataset.parent_id = objParent.id
                    console.log(ui.item[0].parentNode.parentNode)
                    obj.parent_id = objParent.id
                    obj.parent_type = objParent.type;

                    var newdata = [];
                    $('li', this.list).each(function(){
                       newdata.push(this._data)
                    });
                    scope.options.data = newdata;
                    $(scope.list).remove()
                    scope.init()
                    $(scope).trigger('orderChange', [obj, scope.options.data, old])
                }
            })
        }

        this.contextMenu = function(element){
            var menu = document.createElement('span');
            menu.className = 'mw-tree-context-menu';
            if(this.options.contextMenu){
                $.each(this.options.contextMenu, function(){
                    var menuitem = document.createElement('span');
                    var icon = document.createElement('span');
                    menuitem.title = this.title;
                    menuitem.className = 'mw-tree-context-menu-item';
                    icon.className = this.icon;
                    menuitem.appendChild(icon);
                    menu.appendChild(menuitem);
                    (function(menuitem, element, obj){
                        menuitem.onclick = function(){
                            if(obj.action){
                                obj.action.call(element, element, element._data, menuitem)
                            }
                        }
                    })(menuitem, element, this);
                });
            }
            return menu

        }

        this.rend = function(){
            if(this.options.element){
                var el = $(this.options.element);
                if(el.length!==0){
                    el.empty().append(this.list);
                    $(this).trigger('ready');
                }
            }
        }

        this.createItem = function(item){
            var li = document.createElement('li');
            li.dataset.type = item.type;
            li.dataset.id = item.id;
            li.dataset.parent_id = item.parent_id;
            li.className = 'type-' + item.type + ' subtype-'+ item.subtype;
            var container = document.createElement('span');
            container.className = "mw-tree-item-content";
            container.innerHTML = '<span class="mw-tree-item-title">'+item.title+'</span>';
            li._data = item;
            li.id = item.type+'-'+item.id;
            li.appendChild(container);
            container.onclick = function(){
                if(scope.options.selectable) scope.toggleSelect(li)
            }
            this.decorate(li);
            return li;
        }

        this.createList = function(item){
            var nlist = document.createElement('ul');
            nlist.dataset.type = item.parent_type;
            nlist.dataset.id = item.parent_id;
            nlist.className = 'pre-init';
            return nlist;
        }

        this.getParent = function(item){
            if(!item.parent_id) return this.list;
            var findul = this.list.querySelector('ul[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            var findli = this.list.querySelector('li[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            if(findul){
                return findul;
            }
            else if(findli){
                var nlistwrap = this.createItem(item);
                var nlist = this.createList(item);
                nlist.appendChild(nlistwrap);
                findli.appendChild(nlist);
                return false;
            }
            else{
                var nlistwrap = this.createItem(item);
                var nlist = this.createList(item);
                nlistwrap.appendChild(nlist);
                this.list.appendChild(nlistwrap);
                return false;
            }
        }

        this.init = function(){
            this.json2ul();
            this.addButtons();
            this.rend();
            this.restoreState();
        };

        this.init();
    };
    mw.tree = mwtree;

})();