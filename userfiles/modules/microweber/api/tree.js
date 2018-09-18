
/********************************************************


var myTree = new mw.tree({

});


********************************************************/




mw.lib.require('nestedsortable');

(function(){
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
                saveState:true,
                sortable:false,
                nestedSortable:false,
                singleSelect:false,
                selectedData:[],
                skip:[],
                contextMenu:false,
                append:false,
                prepend:false,
                selectable:false,
                filter:false
            };

            var options = $.extend({}, defaults, config);



            options.element = $(options.element)[0];

            this.options = options;

            if(this.options.selectedData){
                this.selectedData = this.options.selectedData;
            }
            else{
                this.selectedData = [];
            }
        };
        this.skip = function(itemData){
            if(this.options.skip && this.options.skip.length>0){
                loopSKIP:
                for( var n=0; n<scope.options.skip.length; n++ ){
                    var item = scope.options.skip[n];
                    var case1 = (item.id == itemData.id && item.type == itemData.type);
                    var case2 = (itemData.parent_id == item.id && item.type == itemData.type);
                    if(case1 ||case2){
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
        };

        this.setData = function(newData){
            this.options.data = newData;
            $(this.list).remove();
            this.init();
        };

        this.saveState = function(){
            if(!this.options.saveState) return;
            var data = [];
            $( 'li.' + this.options.openedClass, this.list  ).each(function(){
                data.push({type:this._data.type, id:this._data.id})
            });

            mw.storage.set(this.options.id, data);
        };

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
        };

        this.manageUnselected = function(){
            $('input:not(:checked)', this.list).each(function(){
                var li = scope.parentLi(this);
                $(li).removeClass(scope.options.selectedClass)
            });
        };

        this.analizeLi = function(li){
            if(typeof li === 'string'){
                if(/^\d+$/.test(li)){
                    li = parseInt(li, 10);
                }
                else{
                    return mw.$(li);
                }
            }
            return li;
        };

        this.select = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.select(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li){
                li.classList.add(this.options.selectedClass);
                var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
                if(input) input.checked = true;
            }

            this.manageUnselected();
            this.getSelected();
            $(scope).trigger('selectionChange', [scope.selectedData]);
        };

        this.unselect = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.unselect(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li){
                li.classList.remove(this.options.selectedClass);
                var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
                if(input) input.checked = false;
            }
            this.manageUnselected();
            this.getSelected();
            $(scope).trigger('selectionChange', [scope.selectedData]);
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
            //if(!li) {console.warn('List item not defined:', li, type)}
            return li;
        }

        this.isSelected = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            var input = $(li.children).filter('.mw-tree-item-content').find('input')[0];
            return input.checked === true;
        };
        this.toggleSelect = function(li, type){
            if(this.isSelected(li, type)){
               this.unselect(li, type)
            }
            else{
               this.select(li, type)
            }
        };

        this.unselectAll = function(){
            return this.unselect(this.selectedData);
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
            $(li.children).filter('mwbutton').addClass(this.options.openedClass);
            if(!_skipsave) this.saveState()

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
            $(li.children).filter('mwbutton').removeClass(this.options.openedClass);
            if(!_skipsave) this.saveState()
        };

        this.toggle = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            li.classList.toggle(this.options.openedClass);
            $(li.children).filter('mwbutton').toggleClass(this.options.openedClass);
            this.saveState()
        };

        this.openAll = function(){
            var all = this.list.querySelectorAll('li');
            $(all).each(function(){
                scope.open(this, undefined, true);
            });
            this.saveState()
        };

        this.closeAll = function(){
            var all = this.list.querySelectorAll('li.'+this.options.openedClass);
            $(all).each(function(){
                scope.close(this, undefined, true);
            });
            this.saveState()
        };

        this.button = function(){
            var btn = document.createElement('mwbutton');
            btn.className = 'mw-tree-toggler';
            btn.onclick = function(){
                scope.toggle(this.parentNode);
            };
            return btn;
        };

        this.addButtons = function(){
            var all = this.list.querySelectorAll('li ul.pre-init'), i=0;
            for( ; i<all.length; i++ ){
                var ul = all[i];
                ul.classList.remove('pre-init');
                $(ul).parent().prepend(this.button());
            }
        };

        this.checkBox = function(element){
            var itype = 'radio';
            if(this.options.singleSelect){

            }
            else if(this.options.multiPageSelect || element._data.type !== 'page'){
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

            /*input.onchange = function(){
                var li = scope.parentLi(this);
                $(li)[this.checked?'addClass':'removeClass'](scope.options.selectedClass)
                var data = scope.getSelected();
                scope.manageUnselected()
                console.log(data,00919)
                $(scope).trigger('change', [data]);
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
            $(all).each(function(){
                if(this._data) selected.push(this._data);
            });
            this.selectedData = selected;
            this.options.selectedData = selected;
            return selected;
        };

        this.decorate = function(element){
            if(this.options.selectable){
                $(element.querySelector('.mw-tree-item-content')).prepend(this.checkBox(element))
            }

            element.querySelector('.mw-tree-item-content').appendChild(this.contextMenu(element));

            if(this.options.sortable){
                this.sortable()
            }
            if(this.options.nestedSortable){
                this.nestedSortable()
            }

        };

        this.sortable = function(element){
            $('ul', this.list).sortable({
                items: ".type-category",
                axis:'y',
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){
                    setTimeout(function(){
                        var old = $.extend({},ui.item[0]._data);
                        var obj = ui.item[0]._data;
                        var objParent = ui.item[0].parentNode.parentNode._data;
                        ui.item[0].dataset.parent_id = objParent.id

                        obj.parent_id = objParent.id;
                        obj.parent_type = objParent.type;
                        var newdata = [];
                        $('li', scope.list).each(function(){
                            if(this._data) newdata.push(this._data)
                        });
                        scope.options.data = newdata;
                        var local = [];
                        $(ui.item[0].parentNode.querySelectorAll('li')).each(function(){
                            local.push(this._data.id);
                        });
                        $(scope.list).remove();
                        scope.init();
                        $(scope).trigger('orderChange', [obj, scope.options.data, old, local])
                    }, 10);

                }
            });
        };
        this.nestedSortable = function(element){
            $('ul', this.list).nestedSortable({
                items: ".type-category",
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){
                    var old = $.extend({},ui.item[0]._data);
                    var obj = ui.item[0]._data;
                    var objParent = ui.item[0].parentNode.parentNode._data;
                    ui.item[0].dataset.parent_id = objParent.id;
                    obj.parent_id = objParent.id;
                    obj.parent_type = objParent.type;
                    var newdata = [];
                    $('li', scope.list).each(function(){
                        if(this._data) newdata.push(this._data)
                    });
                    scope.options.data = newdata;
                    $(scope.list).remove();
                    scope.init();
                    $(scope).trigger('orderChange', [obj, scope.options.data, old])
                }
            })
        };

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

        };

        this.rend = function(){
            if(this.options.element){
                var el = $(this.options.element);
                if(el.length!==0){
                    el.empty().append(this.list);
                }
            }
        };

        this.createItem = function(item){
            var li = document.createElement('li');
            li.dataset.type = item.type;
            li.dataset.id = item.id;
            li.dataset.parent_id = item.parent_id;
            var skip = this.skip(item);
            li.className = 'type-' + item.type + ' subtype-'+ item.subtype + ' skip-' + skip;
            var container = document.createElement('span');
            container.className = "mw-tree-item-content";
            container.innerHTML = '<span class="mw-tree-item-title">'+item.title+'</span>';
            li._data = item;
            li.id = scope.options.id + '-' + item.type+'-'+item.id;
            li.appendChild(container);
            if(!skip){
                container.onclick = function(){
                    if(scope.options.selectable) scope.toggleSelect(li)
                };
                this.decorate(li);
            }


            return li;
        };

        this.createList = function(item){
            var nlist = document.createElement('ul');
            nlist.dataset.type = item.parent_type;
            nlist.dataset.id = item.parent_id;
            nlist.className = 'pre-init';
            return nlist;
        };

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
        };

        this.additional = function (obj) {
            var li = document.createElement('li');
            li.className = 'mw-tree-additional-item';
            var container = document.createElement('span');
            var containerTitle = document.createElement('span');
            container.className = "mw-tree-item-content";
            containerTitle.className = "mw-tree-item-title";
            container.appendChild(containerTitle);
            li.appendChild(container);
            if(obj.icon){
                if(obj.icon.indexOf('</') === -1){
                    var icon = document.createElement('span');
                    icon.className = obj.icon;
                    containerTitle.appendChild(icon)
                }
                else{
                    $(containerTitle).append(obj.icon)
                }

            }
            var title = document.createElement('span');
            title.innerHTML = obj.title;
            containerTitle.appendChild(title);
            li.onclick = function (ev) {
                if(obj.action){
                    obj.action.call(this, obj)
                }
            };
            return li;
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
                    $(scope.list).prepend(scope.additional(this))
                })
            }
        };

        this.addHelperClasses = function(root, level){
            level = (level || 0) + 1;
            root = root || this.list;
           $( root.children ).addClass('level-'+level).each(function(){
               var ch = this.querySelector('ul');
                if(ch){
                    $(this).addClass('has-children')
                    scope.addHelperClasses(ch, level);
                }
           })
        }

        this.loadSelected = function(){
            if(this.selectedData){
                scope.select(this.selectedData);
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
            setTimeout(function(){
                $(scope).trigger('ready');
            }, 78)
        };

        this.config(config);
        this.init();
    };
    mw.tree = mwtree;

})();