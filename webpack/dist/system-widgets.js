/******/ (() => { // webpackBootstrap
(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/block-edit.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.blockEdit = function (options) {
    options = options || {};
    var defaults = {
        element: document.body,
        mode: 'wrap' // wrap | in
    };

    var scope = this;

    var settings = $.extend({}, defaults, options);

    settings.$element = mw.$(settings.element);
    settings.element = settings.$element[0];
    this.settings = settings;
    if(!settings.element) {
        return;
    }

    this.set = function(mode){
        if(mode === 'edit'){
            this.$slider.stop().animate({
                left: '-100%',
                height: this.$editSlide.outerHeight()
            }, function(){
                scope.$holder.addClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        } else {
            this.$slider.stop().animate({
                left: '0',
                height: this.$mainSlide.outerHeight()
            }, function(){
                scope.unEditByElement();
                scope.$holder.removeClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        }
    };

    this.close = function(content){
        this.set();
        $(this).trigger('CloseEdit');
    };
    this.edit = function(content){
        if(content){
            this.$editSlide.empty().append(content);
        }
        this.set('edit');
        $(this).trigger('Edit');
    };

    this._editByElement = null;
    this._editByElementTemp = null;

    this.unEditByElement = function(){
        if(this._editByElement){
            $(this._editByElementTemp).replaceWith(this._editByElement);
            $(this._editByElement).hide()
        }
        this._editByElement = null;
        this._editByElementTemp = null;
    };

    this.editByElement = function(el){
        if(!el){
            return;
        }
        this.unEditByElement();
        this._editByElementTemp = document.createElement('mw-temp');
        this._editByElement = el;
        $(el).before(this._editByElementTemp);
        this.editSlide.appendChild(el);
        $(el).show()
    };
    this.moduleEdit = function(module, params){
        mw.tools.loading(this.holder, 90);
        mw.load_module(module, this.editSlide, function(){
            scope.edit();
            mw.tools.loading(scope.holder, false);
        }, params);
    };

    this.build = function(){
        this.holder = document.createElement('div');
        this.$holder = $(this.holder);
        this.holder.className = 'mw-block-edit-holder';
        this.holder._blockEdit = this;
        this.slider = document.createElement('div');
        this.$slider = $(this.slider);
        this.slider.className = 'mw-block-edit-slider';
        this.mainSlide = document.createElement('div');
        this.$mainSlide = $(this.mainSlide);
        this.editSlide = document.createElement('div');
        this.$editSlide = $(this.editSlide);
        this.mainSlide.className = 'mw-block-edit-main-slide';
        this.editSlide.className = 'mw-block-edit-edit-slide';

        this.slider.appendChild(this.mainSlide);
        this.slider.appendChild(this.editSlide);
        this.holder.appendChild(this.slider);
        //this.settings.$element.before(this.holder);
        // this.mainSlide.appendChild(settings.element);

    };

    this.initMode = function(){
        if(this.settings.mode === 'wrap') {
            this.settings.$element.after(this.holder);
            this.$mainSlide.append(this.settings.$element);
        } else if(this.settings.mode === 'in') {
            this.settings.$element.wrapInner(this.holder);
        }
    };

    this.init = function () {
        this.build();
        this.initMode();
    };

    this.init();

};

mw.blockEdit.get = function(target){
    target = target || '.mw-block-edit-holder';
    target = mw.$(target);
    if(!target[0]) return;
    if(target.hasClass('mw-block-edit-holder')){
        return target[0]._blockEdit;
    } else {
        var node = mw.tools.firstParentWithClass(target[0], 'mw-block-edit-holder') || target[0].querySelector('.mw-block-edit-holder');
        if(node){
            return node._blockEdit;
        }
    }
};

$.fn.mwBlockEdit = function (options) {
    options = options || {};
    return this.each(function(){
        this.mwBlockEdit =  new mw.blockEdit($.extend({}, options, {element: this }));
    });
};

mw.registerComponent('block-edit', function(el){
    var options = mw.components._options(el);
    mw.$(el).mwBlockEdit(options);
});
mw.registerComponent('block-edit-closeButton', function(el){
    mw.$(el).on('click', function(){
        mw.blockEdit.get(this).close();
    });
});
mw.registerComponent('block-edit-editButton', function(el){
    mw.$(el).on('click', function(){
        var options = mw.components._options(this);
        if(options.module){
            mw.blockEdit.get(options.for || this).moduleEdit(options.module);
            return;
        } else if(options.element){
            var el = mw.$(options.element)[0];
            if(el){
                mw.blockEdit.get(options.for || this).editByElement(el);
            }
        }
        mw.blockEdit.get(this).edit();
    });
});

})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/control_box.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.controlBox = function(options){
    var scope = this;
    this.options = options;
    this.defaults = {
        position:'bottom',
        content:'',
        skin:'default',
        id:this.options.id || 'mw-control-box-'+mw.random(),
        closeButton: true
    };
    this.id = this.options.id;
    this.settings = $.extend({}, this.defaults, this.options);
    this.active = false;

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.box.id = this.id;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-boxcontent';
        this.box.appendChild(this.boxContent);
        this.createCloseButton();
        document.body.appendChild(this.box);
    };

    this.createCloseButton = function () {
        if(!this.options.closeButton) return;
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.box.appendChild(this.closeButton);
        this.closeButton.onclick = function(){
            scope.hide();
        };
    };

    this.setContentByUrl = function(){
        var cont = this.settings.content.trim();
        return $.get(cont, function(data){
            scope.boxContent.innerHTML = data;
            scope.settings.content = data;
        });
    };
    this.setContent = function(c){
        var cont = c||this.settings.content.trim();
        this.settings.content = cont;
        if(cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0){
            return this.setContentByUrl()
        }
        this.boxContent.innerHTML = cont;
    };

    this.show = function(){
        this.active = true;
        mw.$(this.box).addClass('active') ;
        mw.$(this).trigger('ControlBoxShow')
    };

    this.init = function(){
        this.build();
        this.setContent();
    };
    this.hide = function(){
        this.active = false;
        mw.$(this.box).removeClass('active');
        mw.$(this).trigger('ControlBoxHide');
    };


    this.toggle = function(){
        this[this.active?'hide':'show']();
    };
    this.init();
};

})();

(() => {
/*!*********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/domtree.js ***!
  \*********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.DomTree = function (options) {
    var scope = this;
    this.prepare = function () {
        var defaults = {
            selector: '.edit',
            document: document,
            targetDocument: document,
            componentMatch: [
                {
                    label:  function (node) {
                        return 'Edit';
                    },
                    test: function (node) {
                        return mw.tools.hasClass(node, 'edit');
                    }
                },
                {
                    label: function (node) {
                        var icon = mw.top().live_edit.getModuleIcon(node.getAttribute('data-type'));
                        return icon + ' ' + node.getAttribute('data-mw-title') || node.getAttribute('data-type');
                    },
                    test: function (node) {
                        return mw.tools.hasClass(node, 'module');
                    }
                },
                {
                    label: 'Image',
                    test: function (node) {
                        return node.nodeName === 'IMG';
                    }
                },
                {
                    label:  function (node) {
                        var id = node.id ? '#' + node.id : '';
                        return node.nodeName.toLowerCase() ;
                    },
                    test: function (node) { return true; }
                }
            ],
            componentTypes: [
                {
                    label: 'SafeMode',
                    test: function (node) {
                        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [ 'safe-mode', 'regular-mode' ]);
                    }
                }
            ]
        };
        options = options || {};

        this.settings = $.extend({}, defaults, options);

        this.$holder = $(this.settings.element);

        this.document = this.settings.document;
        this.targetDocument = this.settings.targetDocument;

        this._selectedDomNode = null;
    };
    this.prepare();

    this.createList = function () {
        return this.document.createElement('ul');
    };

    this.createRoot = function () {
        this.root = this.createList();
        this.root.className = 'mw-defaults mw-domtree';
    };


    this._get = function (nodeOrTreeNode) {
        console.log(nodeOrTreeNode)
        return nodeOrTreeNode._value ? nodeOrTreeNode : this.findElementInTree(nodeOrTreeNode);
    };

    this.select = function (node) {
        var el = this.getByNode(node);
        if (el) {
            this.selected(el);
            this.openParents(el);
            this._scrollTo(el);
        }
    };

    this.toggle = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        this[ li._opened ? 'close' : 'open'](li);
    };

    this._opened = [];

    this.open = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = true;
        li.classList.add('expand');
        if(this._opened.indexOf(li._value) === -1) {
            this._opened.push(li._value);
        }
    };
    this.close = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = false;
        li.classList.remove('expand');
        var ind = this._opened.indexOf(li._value);
        if( ind !== -1 ) {
            this._opened.splice(ind, ind)
        }
    };

    this._scrollTo = function (el) {
        setTimeout(function () {
            scope.$holder.stop().animate({
                scrollTop: (scope.$holder.scrollTop() + ($(el).offset().top - scope.$holder.offset().top)) - (scope.$holder.height()/2 - 10)
            });
        }, 55);
    };

    this.openParents = function (node) {
        node = this._get(node);
        while(node && node !== this.root) {
            if(node.nodeName === 'LI'){
                this.open(node);
            }
            node = node.parentNode;
        }
    };
    this.selected = function (node) {
        if (typeof node === 'undefined') {
            return this._selectedDomNode;
        }
        mw.$('.selected', this.root).removeClass('selected');
        node.classList.add('selected');
        this._selectedDomNode = node;
    };

    this.getByNode = function (el) {
        var all = this.root.querySelectorAll('li');
        var l = all.length, i = 0;
        for ( ; i < l; i++) {
            if (all[i]._value === el) {
                return all[i];
            }
        }
    };

    this.getByTreeNode = function (treeNode) {
        return treeNode._value;
    };

    this.allDomNodes = function () {
        return Array.from(this.map().keys());
    };

    this.allTreeNodes = function () {
        return Array.from(this.map().values());
    };

    this.emptyTreeNode = function (node) {
        var li = this.getByNode(node);
        $(li).empty();
        return li;
    };

    this.refresh = function (node) {
        var item = this.emptyTreeNode(node);
        this.createChildren(node, item);
    };

    this._currentTarget = null;
    this.createItemEvents = function () {
        $(this.root)
            .on('mousemove', function (e) {
                var target = e.target;
                if(target.nodeName !== 'LI') {
                    target = target.parentNode;
                }
                if(scope._currentTarget !== target) {
                    scope._currentTarget = target;
                    mw.$('li.hover', scope.root).removeClass('hover');
                    target.classList.add('hover');
                    if(scope.settings.onHover) {
                        scope.settings.onHover.call(scope, e, target, target._value);
                    }
                }
            })
            .on('mouseleave', function (e) {
                mw.$('li', scope.root).removeClass('hover');
            })
            .on('click', function (e) {
                var target = e.target;

                if(target.nodeName !== 'LI') {
                    target = mw.tools.firstParentWithTag(target, 'li');
                    scope.toggle(target);
                }
                scope.selected(target);
                if(target.nodeName === 'LI' && scope.settings.onSelect) {
                    scope.settings.onSelect.call(scope, e, target, target._value);
                }
            });
    };

    this.map = function (node, treeNode) {
        if (!this._map) {
            this._map = new Map();
        }
        if(!node) {
            return this._map;
        }
        if (!treeNode) {
            return this._map.get(node);
        }
        if (!this._map.has(node)) {
            this._map.set(node, treeNode);
        }
    };

    this.createItem = function (item) {
        if(!this.validateNode(item)) {
            return;
        }
        var li = this.document.createElement('li');
        li._value = item;
        li.className = 'mw-domtree-item' + (this._selectedDomNode === item ? ' active' : '');
        var dio = item.children.length ? '<i class="mw-domtree-item-opener"></i>' : '';
        li.innerHTML = dio + '<span class="mw-domtree-item-label">' + this.getComponentLabel(item) + '</span>';
        return li;
    };

    this.getComponentLabel = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (  ; i < all.length; i++ ) {
            if( all[i].test(node)) {
                return typeof all[i].label === 'string' ? all[i].label : all[i].label.call(this, node);
            }
        }
    };
    this.isComponent = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (  ; i < all.length; i++ ) {
            if( all[i].test(node)) {
                return true;
            }
        }
        return false;
    };

    this.validateNode = function (node) {
        if(node.nodeType !== 1){
            return false;
        }
        var tag = node.nodeName;
        if(tag === 'SCRIPT' || tag === 'STYLE' || tag === 'LINK' || tag === 'BR') {
            return false;
        }
        return this.isComponent(node);
    };

    this.create = function () {
        var all = this.targetDocument.querySelectorAll(this.settings.selector);
        var i = 0;
        for (  ; i < all.length; i++ ) {
            var item = this.createItem(all[i]);
            if(item) {
                this.root.appendChild(item);
                this.createChildren(all[i], item);
            }
        }
        this.createItemEvents();
        $(this.settings.element).empty().append(this.root).resizable({
            handles: "s",
            start: function( event, ui ) {
                ui.element.css('maxHeight', 'none');
            }
        });
    };

    this.createChildren = function (node, parent) {
        if(!parent) return;
        var list = this.createList();
        var curr = node.children[0];
        while (curr) {
            var item = this.createItem(curr);
            if (item) {
                list.appendChild(item);
                if (curr.children.length) {
                    this.createChildren(curr, item);
                }
            }
            curr = curr.nextElementSibling;
        }
        parent.appendChild(list);
    };

    this.init = function () {
        this.createRoot();
        this.create();
    };

    this.init();

};

})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/filemanager.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function (){
    mw.require('filemanager.css');
    var FileManager = function (options) {

        var scope = this;

        options = options || {};

        var defaultRequest = function (params, callback, error) {
            var xhr = new XMLHttpRequest();
            scope.dispatch('beforeRequest', {xhr: xhr, params: params});
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    callback.call(scope, JSON.parse(this.responseText), xhr);
                }
            };
            xhr.open("GET", scope.settings.url, true);
            xhr.send();
        };

        var defaults = {
            multiselect: true,
            options: true,
            element: null,
            query: {
                order: 'asc',
                orderBy: 'name',
                keyword: '',
                display: 'list'
            },
            requestData: defaultRequest,
            url: mw.settings.site_url + 'admin/file-manager/list'
        };

        var _e = {};
        var _viewType = 'list';

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.settings = mw.object.extend({}, defaults, options);

        var table, tableHeader, tableBody;



        var _check = function () {
            return mw.element('<label class="mw-ui-check">' +
                '<input type="checkbox"><span></span>' +
                '</label>');
        };

        var _size = function (item, dc) {
            var bytes = item.size;
            if (typeof bytes === 'undefined' || bytes === null) return '';
            if (bytes === 0) return '0 Bytes';
            var k = 1000,
                dm = dc === undefined ? 2 : dc,
                sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        };



        var _image = function (item) {
            if (item.type === 'folder') {
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-folder"></span>';
            } else if (item.thumbnail) {
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-image" style="background-image: url(' + item.thumbnail + ')"></span>';
            } else {
                var ext = item.name.split('.').pop();
                if(!ext) {
                    ext = item.mimeType;
                }
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-file">' + (ext) + '</span>';
            }
        };

        var createOption = function (item, option) {
            if(!option.match(item)) {
                return '';
            }
            var el = mw.element({
                content: option.label
            });
            el.on('click', function (){
                option.action(item);
            });
            return el;
        };


        var createOptions = function (item) {
            var options = [
                { label: 'Rename', action: function (item) {}, match: function (item) { return true } },
                { label: 'Download', action: function (item) {}, match: function (item) { return item.type === 'file'; } },
                { label: 'Copy url', action: function (item) {}, match: function (item) { return true } },
                { label: 'Delete', action: function (item) {}, match: function (item) { return true } },
            ];
            var el = mw.element().addClass('mw-file-manager-list-item-options');
            el.append(mw.element({tag: 'span', content: '...', props: {tooltip:'options'}}).addClass('mw-file-manager-list-item-options-button'));
            var optsHolder = mw.element().addClass('mw-file-manager-list-item-options-list');
            el.on('click', function (){
                var all = scope.root.get(0).querySelectorAll('.mw-file-manager-list-item-options.active');
                for(var i = 0; i < all.length; i++ ) {
                    if (all[i] !== this) {
                        all[i].classList.remove('active')
                    }
                }
                el.toggleClass('active');
            });
            options.forEach(function (options){
                optsHolder.append(createOption(item, options));
            });
            if(!this.__bodyOptionsClick) {
                this.__bodyOptionsClick = true;
                var bch = function (e) {
                    var curr = e.target;
                    var clicksOption = false;
                  while (curr && curr !== document.body) {
                      if(curr.classList.contains('mw-file-manager-list-item-options')){
                          clicksOption = true;
                          break;
                      }
                      curr = curr.parentNode;
                  }
                  if(!clicksOption) {
                      var all = scope.root.get(0).querySelectorAll('.mw-file-manager-list-item-options.active');
                      for(var i = 0; i < all.length; i++ ) {
                          if (all[i] !== this) {
                              all[i].classList.remove('active')
                          }
                      }
                  }
                };
                document.body.addEventListener('mousedown', bch , false);
            }
            el.append(optsHolder);
            return el;
        };


        var setData = function (data) {
            scope._data = data;
        };

        this.updateData = function (data) {
            setData(data);
            this.dispatch('dataUpdated', data);
        };

        this.getData = function () {
            return this._data;
        };

        this.requestData = function () {
            var params = {};
            var cb = function (res) {
                scope.updateData(res);
            };

            var err = function (er) {

            };

            this.settings.requestData(
                params, cb, err
            );
        };


        this.render = function () {

        };

        var userDate = function (date) {
            var dt = new Date(date);
            return dt.toLocaleString();
        };

        this.find = function (item) {
            if (typeof item === 'number') {

            }
        };

        this.select = function (item) {

        };

        this.singleListView = function (item) {
            var row = mw.element({ tag: 'tr' });
            var cellImage = mw.element({ tag: 'td', content: _image(item)  });
            var cellName = mw.element({ tag: 'td', content: item.name  });
            var cellSize = mw.element({ tag: 'td', content: _size(item) });

            var cellmodified = mw.element({ tag: 'td', content: userDate(item.modified)  });

            if(this.settings.multiselect) {
                var check =  _check();
                check.on('input', function () {

                });
                row.append( mw.element({ tag: 'td', content: check }));
            }

             row
                .append(cellImage)
                .append(cellName)
                .append(cellSize)
                .append(cellmodified);
            if(this.settings.options) {
                var cellOptions = mw.element({ tag: 'td', content: createOptions(item) });
                row.append(cellOptions);
            }


            return row;
        };

        var rows = [];

        var listViewBody = function () {
            rows = [];
            tableBody ? tableBody.remove() : '';
            tableBody =  mw.element({
                tag: 'tbody'
            });
            scope._data.data.forEach(function (item) {
                var row = scope.singleListView(item);
                rows.push({data: item, row: row});
                tableBody.append(row);
            });
            return tableBody;
        };


        this._selected = [];

        var pushUnique = function (obj) {
            if (scope._selected.indexOf(obj) === -1) {
                scope._selected.push(obj);
            }
        };
        var afterSelect = function (obj) {
            rows.forEach(function (rowItem){
                if(rowItem.data === obj) {
                    rowItem.row.find('input').prop('checked', true);
                }
            });
        };


        this.selectAll = function () {
            rows.forEach(function (rowItem){
                scope.select(rowItem.data);
            });
        };

        this.select = function (obj) {
            if (this.settings.multiselect) {
                pushUnique(obj);
            } else {
                this._selected = [obj];
            }
            afterSelect(obj);
        };

        var createListViewHeader = function () {
            var thCheck;
            if (scope.settings.multiselect) {
                var globalcheck = _check();
                globalcheck.on('input', function () {
                    scope.selectAll()
                });
                thCheck = mw.element({ tag: 'th', content: globalcheck  }).addClass('mw-file-manager-select-all-heading');
            }
            var thImage = mw.element({ tag: 'th', content: ''  });
            var thName = mw.element({ tag: 'th', content: '<span>Name</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thSize = mw.element({ tag: 'th', content: '<span>Size</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thModified = mw.element({ tag: 'th', content: '<span>Last modified</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thOptions = mw.element({ tag: 'th', content: ''  });
            var tr = mw.element({
                tag: 'tr',
                content: [thCheck, thImage, thName, thSize, thModified, thOptions]
            });
            tableHeader =  mw.element({
                tag: 'thead',
                content: tr
            });
            return tableHeader;
        };

        var listView = function () {
            table =  mw.element('<table class="mw-file-manager-listview-table" />');
            table
                .append(createListViewHeader())
                .append(listViewBody());
            return table;
        };

        var gridView = function () {
            var grid =  mw.element('<div />');

            return grid;
        };

        this.view = function (type) {
            if(!type) return _viewType;
            _viewType = type;
            var viewblock;
            if (_viewType === 'list') {
                viewblock = listView();
            } else if (_viewType === 'grid') {
                viewblock = gridView();
            }
            if(viewblock) {
                this.root.empty().append(viewblock);
            }
            this.root.dataset('view', _viewType);
        };

        this.refresh = function () {
            if (_viewType === 'list') {
                listViewBody();
            } else if (_viewType === 'grid') {
                this.listView();
            }
        };

        var createRoot = function (){
            scope.root = mw.element({
                props: {
                    className: 'mw-file-manager-root'
                },
                encapsulate: false
            });

        };

        this.init = function (){
            createRoot();
            this.on('dataUpdated', function (res){
                scope.view(_viewType);
            });
            this.requestData();
            if (this.settings.element) {
                mw.element(this.settings.element).empty().append(this.root);
            }
        };

        this.init();
    };

    mw.FileManager = function (options) {
        return new FileManager(options);
    };
})();

})();

(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/filepicker.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

mw.require('uploader.js');


mw.filePicker = function (options) {
    options = options || {};
    var scope = this;
    var $scope = $(this);
    var defaults = {
        components: [
            {type: 'desktop', label: mw.lang('My computer')},
            {type: 'url', label: mw.lang('URL')},
            {type: 'server', label: mw.lang('Uploaded')},
            {type: 'library', label: mw.lang('Media library')}
        ],
        nav: 'tabs', // 'tabs | 'dropdown',
        hideHeader: false,
        dropDownTargetMode: 'self', // 'self', 'dialog'
        element: null,
        footer: true,
        okLabel: mw.lang('OK'),
        cancelLabel: mw.lang('Cancel'),
        uploaderType: 'big', // 'big' | 'small'
        confirm: function (data) {

        },
        cancel: function () {

        },
        label: mw.lang('Media'),
        autoSelect: true, // depending on the component
        boxed: false,
        multiple: false
    };



    this.settings = $.extend(true, {}, defaults, options);

    this.$root = $('<div class="'+ (this.settings.boxed ? ('card mb-3') : '') +' mw-filepicker-root"></div>');
    this.root = this.$root[0];

    $.each(this.settings.components, function (i) {
        this['index'] = i;
    });


    this.components = {
        _$inputWrapper: function (label) {
            var html = '<div class="mw-ui-field-holder">' +
                /*'<label>' + label + '</label>' +*/
                '</div>';
            return mw.$(html);
        },
        url: function () {
            var $input = $('<input class="mw-ui-field w100" placeholder="http://example.com/image.jpg">');
            scope.$urlInput = $input;
            var $wrap = this._$inputWrapper(scope._getComponentObject('url').label);
            $wrap.append($input);
            $input.before('<label class="mw-ui-label">Insert file url</label>');
            $input.on('input', function () {
                var val = this.value.trim();
                scope.setSectionValue(val || null);
                if(scope.settings.autoSelect) {

                    scope.result();
                }
            });
            return $wrap[0];
        },
        _setdesktopType: function () {
            var $zone;
            if(scope.settings.uploaderType === 'big') {
                $zone = $('<div class="mw-file-drop-zone">' +
                    '<div class="mw-file-drop-zone-holder">' +
                    '<div class="mw-file-drop-zone-img"></div>' +
                    '<div class="mw-ui-progress-small"><div class="mw-ui-progress-bar" style="width: 0%"></div></div>' +
                    '<span class="mw-ui-btn mw-ui-btn-rounded mw-ui-btn-info">'+mw.lang('Add file')+'</span> ' +
                    '<p>'+mw.lang('or drop files to upload')+'</p>' +
                    '</div>' +
                    '</div>');
            } else if(scope.settings.uploaderType === 'small') {
                $zone = $('<div class="mw-file-drop-zone mw-file-drop-zone-small mw-file-drop-square-zone"> <div class="mw-file-drop-zone-holder"> <span class="mw-ui-link">Add file</span> <p>or drop file to upload</p> </div> </div>')
            }


            var $el = $(scope.settings.element).eq(0);
            $el.removeClass('mw-filepicker-desktop-type-big mw-filepicker-desktop-type-small');
            $el.addClass('mw-filepicker-desktop-type-' + scope.settings.uploaderType);
            scope.uploaderHolder.empty().append($zone);
        },
        desktop: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('desktop').label);
            scope.uploaderHolder = mw.$('<div class="mw-uploader-type-holder"></div>');
            this._setdesktopType();
            $wrap.append(scope.uploaderHolder);
            scope.uploader = mw.upload({
                element: $wrap[0],
                multiple: scope.settings.multiple,
                accept: scope.settings.accept,
                on: {
                    progress: function (prg) {
                        scope.uploaderHolder.find('.mw-ui-progress-bar').stop().animate({width: prg.percent + '%'}, 'fast');
                    },
                    fileAdded: function (file) {
                        $(scope).trigger('FileAdded', [file]);
                        scope.uploaderHolder.find('.mw-ui-progress-bar').width('1%');
                    },
                    fileUploaded: function (file) {
                        scope.setSectionValue(file);

                        $(scope).trigger('FileUploaded', [file]);
                        if (scope.settings.autoSelect) {
                            scope.result();
                        }
                        if (scope.settings.fileUploaded) {
                            scope.settings.fileUploaded(file);
                        }
                        // scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                    }
                }
            });
            return $wrap[0];
        },
        server: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('server').label);
            /*mw.load_module('files/admin', $wrap, function () {

            }, {'filetype':'images'});*/

            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('server');
                if (type === 'server') {
                    mw.tools.loading(el, true);
                    var fr = mw.tools.moduleFrame('files/admin', {'filetype':'images'});
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    $wrap.append(fr);
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.$(this.contentWindow.document.body).on('click', '.mw-browser-list-file', function () {
                            var url = this.href;
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            });

            return $wrap[0];
        },
        library: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('library').label);
            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('library');
                if (type === 'library') {
                    mw.tools.loading(el, true);
                    var fr = mw.tools.moduleFrame('pictures/media_library');
                    $wrap.append(fr);
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.mw.on.hashParam('select-file', function () {
                            var url = this.toString();
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            })

            /*mw.load_module('pictures/media_library', $wrap);*/
            return $wrap[0];
        }
    };

    this.hideUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).hide();
    };

    this.showUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).show();
    };

    this.desktopUploaderType = function (type) {
        if(!type) return this.settings.uploaderType;
        this.settings.uploaderType = type;
        this.components._setdesktopType();
    };

    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });


    this._navigation = null;
    this.__navigation_first = [];

    this.navigation = function () {
        this._navigationHeader = document.createElement('div');
        this._navigationHeader.className = 'mw-filepicker-component-navigation-header ' + (this.settings.boxed ? 'card-header no-border' : '');
        if (this.settings.hideHeader) {
            this._navigationHeader.style.display = 'none';
        }
        if (this.settings.label) {
            this._navigationHeader.innerHTML = '<h6><strong>' + this.settings.label + '</strong></h6>';
        }
        this._navigationHolder = document.createElement('div');
        if(this.settings.nav === false) {

        }
        else if(this.settings.nav === 'tabs') {
            var ul = $('<nav class="mw-ac-editor-nav" />');
            this.settings.components.forEach(function (item) {
                ul.append('<a href="javascript:;" class="mw-ui-btn-tab" data-type="'+item.type+'">'+item.label+'</a>');
            });
            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(ul[0]);
            setTimeout(function () {
                scope._tabs = mw.tabs({
                    nav: $('a', ul),
                    tabs: $('.mw-filepicker-component-section', scope.$root),
                    activeClass: 'active',
                    onclick: function (el, event, i) {
                        if(scope.__navigation_first.indexOf(i) === -1) {
                            scope.__navigation_first.push(i);
                            $(scope).trigger('$firstOpen', [el, this.dataset.type]);
                        }
                        scope.manageActiveSectionState();
                    }
                });
            }, 78);
        } else if(this.settings.nav === 'dropdown') {
            var select = $('<select class="selectpicker btn-as-link" data-style="btn-sm" data-width="auto" data-title="' + mw.lang('Add file') + '"/>');
            scope._select = select;
            this.settings.components.forEach(function (item) {
                select.append('<option class="nav-item" value="'+item.type+'">'+item.label+'</option>');
            });

            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(select[0]);
            select.on('changed.bs.select', function (e, xval) {
                var val = select.selectpicker('val');
                var componentObject = scope._getComponentObject(val) ;
                var index = scope.settings.components.indexOf(componentObject);
                var items = $('.mw-filepicker-component-section', scope.$root);
                if(scope.__navigation_first.indexOf(val) === -1) {
                    scope.__navigation_first.push(val);
                    $(scope).trigger('$firstOpen', [items.eq(index)[0], val]);
                }
                if(scope.settings.dropDownTargetMode === 'dialog') {
                    var temp = document.createElement('div');
                    var item = items.eq(index);
                    item.before(temp);
                    item.show();
                    var footer = false;
                    if (scope._getComponentObject('url').index === index ) {
                        footer =  document.createElement('div');
                        var footerok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + scope.settings.okLabel + '</button>');
                        var footercancel = $('<button type="button" class="mw-ui-btn">' + scope.settings.cancelLabel + '</button>');
                        footerok.disabled = true;
                        footer.appendChild(footercancel[0]);
                        footer.appendChild(footerok[0]);
                        footer.appendChild(footercancel[0]);
                        footercancel.on('click', function () {
                            scope.__pickDialog.remove();
                        });
                        footerok.on('click', function () {
                            scope.setSectionValue(scope.$urlInput.val().trim() || null);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                            // scope.__pickDialog.remove();
                        });
                    }

                    scope.__pickDialog = mw.dialog({
                        overlay: true,
                        content: item,
                        beforeRemove: function () {
                            $(temp).replaceWith(item);
                            item.hide();
                            scope.__pickDialog = null;
                        },
                        footer: footer
                    });
                } else {
                    items.hide().eq(index).show();
                }
            });
        }
        this.$root.prepend(this._navigationHolder);

    };
    this.__displayControllerByTypeTime = null;

    this.displayControllerByType = function (type) {
        type = (type || '').trim();
        var item = this._getComponentObject(type) ;
        clearTimeout(this.__displayControllerByTypeTime);
        this.__displayControllerByTypeTime = setTimeout(function () {
            if(scope.settings.nav === 'tabs') {
                mw.$('[data-type="'+type+'"]', scope.$root).click();
            } else if(scope.settings.nav === 'dropdown') {
                $(scope._select).selectpicker('val', type);
            }
        }, 10);
    };



    this.footer = function () {
        if(!this.settings.footer || this.settings.autoSelect) return;
        this._navigationFooter = document.createElement('div');
        this._navigationFooter.className = 'mw-ui-form-controllers-footer mw-filepicker-footer ' + (this.settings.boxed ? 'card-footer' : '');
        this.$ok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + this.settings.okLabel + '</button>');
        this.$cancel = $('<button type="button" class="mw-ui-btn ">' + this.settings.cancelLabel + '</button>');
        this._navigationFooter.appendChild(this.$cancel[0]);
        this._navigationFooter.appendChild(this.$ok[0]);
        this.$root.append(this._navigationFooter);
        this.$ok[0].disabled = true;
        this.$ok.on('click', function () {
            scope.result();
        });
        this.$cancel.on('click', function () {
            scope.settings.cancel()
        });
    };

    this.result = function () {
        var activeSection = this.activeSection();
        if(this.settings.onResult) {
            this.settings.onResult.call(this, activeSection._filePickerValue);
        }
         $(scope).trigger('Result', [activeSection._filePickerValue]);
    };

    this.getValue = function () {
        return this.activeSection()._filePickerValue;
    };

    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this._sections = [];
    this.buildComponentSection = function () {
        var main = mw.$('<div class="'+(this.settings.boxed ? 'card-body' : '') +' mw-filepicker-component-section"></div>');
        this.$root.append(main);
        this._sections.push(main[0]);
        return main;
    };

    this.buildComponent = function (component) {
        if(this.components[component.type]) {
            return this.components[component.type]();
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if(component){
                var sec = scope.buildComponentSection();
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        this.buildComponents();
        if(this.settings.nav === 'dropdown') {
            $('.mw-filepicker-component-section', scope.$root).hide().eq(0).show();
        }
        this.footer();
    };

    this.init = function () {
        this.build();
        if (this.settings.element) {
            $(this.settings.element).eq(0).append(this.$root);
        }
        if($.fn.selectpicker) {
            $('select', scope.$root).selectpicker();
        }
    };

    this.hide = function () {
        this.$root.hide();
    };
    this.show = function () {
        this.$root.show();
    };

    this.activeSection = function () {
        return $(this._sections).filter(function (){
            return $(this).css('display') !== 'none';
        })[0];
    };

    this.setSectionValue = function (val) {
         var activeSection = this.activeSection();
         if(activeSection) {
            activeSection._filePickerValue = val;
        }

        if(scope.__pickDialog) {
            scope.__pickDialog.remove();
        }
        this.manageActiveSectionState();
    };
    this.manageActiveSectionState = function () {
        // if user provides value for more than one section, the active value will be the one in the current section
        var activeSection = this.activeSection();
        if (this.$ok && this.$ok[0]) {
            this.$ok[0].disabled = !(activeSection && activeSection._filePickerValue);
        }
    };

    this.init();
};

})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/link-editor.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

mw.require('widgets.css');
mw.require('form-controls.js');


(function(){
    var LinkEditor = function(options) {
        var scope = this;
        var defaults = {
            mode: 'dialog',
            controllers: [
                { type: 'url'},
                { type: 'page' },
                { type: 'post' },
                { type: 'file' },
                { type: 'email' },
                { type: 'layout' },
                /*{ type: 'title' },*/
            ],
            title: '<i class="mdi mdi-link mw-link-editor-icon"></i> ' + mw.lang('Link Settings'),
            nav: 'tabs'
        };

        this._confirm = [];
        this.onConfirm = function (c) {
            this._confirm.push(c);
        };

        this._cancel = [];
        this.onCancel = function (c) {
            this._cancel.push(c);
        };

        this.setValue = function (data, controller) {
            controller = controller || 'auto';

            if(controller === 'auto') {
                this.controllers.forEach(function (item){
                    item.controller.setValue(data);
                });
            } else {
                this.controllers.find(function (item){
                    return item.type === controller;
                }).controller.setValue(data);
            }

            return this;
        };


        this.settings = mw.object.extend({}, defaults, options || {});

        this.buildNavigation = function (){
            if(this.settings.nav === 'tabs') {
                this.nav = document.createElement('nav');
                 this.nav.className = 'mw-ac-editor-nav';

                var nav = scope.controllers.slice(0, 4);
                var dropdown = scope.controllers.slice(4);

                var handleSelect = function (__for, target) {
                    [].forEach.call(scope.nav.children, function (item){item.classList.remove('active');});
                    scope.controllers.forEach(function (item){item.controller.root.classList.remove('active');});
                    if(target && target.classList) {
                        target.classList.add('active');
                    }
                    __for.controller.root.classList.add('active');
                    if(scope.dialog) {
                        scope.dialog.center();
                    }
                };

                var createA = function (ctrl, index) {
                    var a =  document.createElement('a');
                    a.className = 'mw-ui-btn-tab' + (index === 0 ? ' active' : '');
                    a.innerHTML = ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title);
                    a.__for = ctrl;
                    a.onclick = function (){
                        handleSelect(this.__for, this);
                    };
                    return a;
                };


                nav.forEach(function (ctrl, index){
                    scope.nav.appendChild(createA(ctrl, index));
                });
                this.nav.children[0].click();
                this.root.prepend(this.nav);

                if(dropdown.length) {
                    var dropdownElBtn =  document.createElement('div');
                    var dropdownEl =  document.createElement('div');
                      dropdownElBtn.className = 'mw-ui-btn-tab mw-link-editor-more-button';
                      dropdownEl.className = 'mw-link-editor-nav-drop-box';
                      dropdownEl.style.display = 'none';

                    dropdownElBtn.innerHTML = mw.lang('More') + '<i class="mdi mdi-chevron-down"></i>';
                    dropdown.forEach(function (ctrl, index){

                        mw.element(dropdownEl)
                            .append(mw.element({
                                tag: 'span',
                                props: {
                                    className: '',
                                    __for: ctrl,
                                    innerHTML: ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title),
                                    onclick: function () {
                                         handleSelect(this.__for);
                                        mw.element(dropdownEl).hide();
                                    }
                                }
                            }));
                    });
                    this.nav.append(dropdownEl);
                    this.nav.append(dropdownElBtn);
                    dropdownElBtn.onclick = function (){
                        mw.element(dropdownEl).toggle();
                    };

                    dropdownEl.onchange = function () {
                        handleSelect(this.options[this.selectedIndex].__for);
                    };
                    /*setTimeout(function (){
                        if($.fn.selectpicker) {
                            $('.selectpicker').selectpicker();
                        }
                    }, 100)*/
                }
            }

        };

        this.buildControllers = function (){
            this.controllers = [];
            this.settings.controllers.forEach(function (item) {
                if(mw.UIFormControllers[item.type]) {
                    var ctrl = new mw.UIFormControllers[item.type](item.config);
                    scope.root.appendChild(ctrl.root);
                    scope.controllers.push({
                        type: item.type,
                        controller: ctrl
                    });
                    ctrl.onConfirm(function (data){
                        scope._confirm.forEach(function (f){
                            f(data);
                        });
                    });
                    ctrl.onCancel(function (){
                        scope._cancel.forEach(function (f){
                            f();
                        });
                    });
                }

            });
        };
        this.build = function (){
            this.root = document.createElement('div');
            this.root.onclick = function (e) {
                var le2 = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['mw-link-editor-nav-drop-box', 'mw-link-editor-more-button']);
                if(!le2) {
                    mw.element('.mw-link-editor-nav-drop-box').hide();
                }
            };

            this.root.className = 'mw-link-editor-root mw-link-editor-root-inIframe-' + (window.self !== window.top )
            this.buildControllers ();
            if(this.settings.mode === 'dialog') {
                this.dialog = mw.dialog({
                    content: this.root,
                    height: 'auto',
                    title: this.settings.title,
                    overflowMode: 'visible',
                    shadow: false
                });
                this.dialog.center();
                this.onConfirm(function (){
                    scope.dialog.remove();
                });
                this.onCancel(function (){
                    scope.dialog.remove();
                });
            } else if(this.settings.mode === 'element') {
                this.settings.element.append(this.root);
            }
        };
        this.init = function(options) {
            this.build();
            this.buildNavigation();
        };
        this.init();
        this.promise = function () {
            return new Promise(function (resolve){
                scope.onConfirm(function (data){
                    resolve(data);
                });
                scope.onCancel(function (){
                    resolve();
                });
            });
        };
    };
    mw.LinkEditor = LinkEditor;

})();

})();

(() => {
/*!*****************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/module_settings.js ***!
  \*****************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.moduleSettings = function(options){
    /*
        options:

            data: [Object],
            element: NodeElement || selector string || jQuery array,
            schema: mw.propEditor.schema,
            key: String
            group: String,
            autoSave: Boolean

    */

    var defaults = {
        sortable: true,
        autoSave: true
    };

    if(!options.schema || !options.data || !options.element) return;

    this.options = $.extend({}, defaults, options);

    this.options.element = mw.$(this.options.element)[0];
    this.value = this.options.data.slice();

    var scope = this;

    this.items = [];

    if(!this.options.element) return;

    this.createItemHolderHeader = function(i){
        if(this.options.header){
            var header = document.createElement('div');
            header.className = "mw-ui-box-header";
            header.innerHTML = this.options.header.replace(/{count}/g, '<span class="mw-module-settings-box-index">'+(i+1)+'</span>');
            mw.$(header).on('click', function(){
                mw.$(this).next().slideToggle();
            });
            return header;

        }
    };
    this.headerAnalize = function(header){
        mw.$("[data-action='remove']", header).on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            $(mw.tools.firstParentOrCurrentWithAnyOfClasses(this, ['mw-module-settings-box'])).remove();
            scope.refactorDomPosition();
            scope.autoSave();
        });
    };
    this.createItemHolder = function(i){
        i = i || 0;
        var holder = document.createElement('div');
        var holderin = document.createElement('div');
        holder.className = 'mw-ui-box mw-module-settings-box';
        holderin.className = 'mw-ui-box-content mw-module-settings-box-content';
        holderin.style.display = 'none';
        holder.appendChild(holderin);
        if(!this.options.element.children) {
            this.options.element.appendChild(holder);
        } else if (!this.options.element.children[i]){
            this.options.element.appendChild(holder);
        } else if (this.options.element.children[i]){
            $(this.options.element.children[i]).before(holder);
        }


        return holder;
    };

    this.addNew = function(pos, method){
        method = method || 'new';
        pos = pos || 0;
        var _new;

        var val = this.value[0];

        if(val) {
            _new = mw.tools.cloneObject(JSON.parse(JSON.stringify(this.value[0])));

        } else {
            _new = {};
        }


        if(_new.title) {
            _new.title += ' - new';
        } else if(_new.name) {
            _new.name += ' - new';
        }
        if(method === 'new'){
            $.each(this.options.schema, function(){
                if(this.value) {
                    if(typeof this.value === 'function') {
                        _new[this.id] = this.value();
                    } else {
                        _new[this.id] = this.value;
                    }
                }
            });
        }

        this.value.splice(pos, 0, _new);
        this.createItem(_new, pos);
    };

    this.remove = function(pos){
        if(typeof pos === 'undefined') return;
        this.value.splice(pos, 1);
        this.items.splice(pos, 1);
        mw.$(this.options.element).children().eq(pos).animate({opacity: 0, height: 0}, function(){
            mw.$(this).remove();
        });
        mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
    };

    this.createItem = function(curr, i){
        var box = this.createItemHolder(i);
        var header = this.createItemHolderHeader(i);
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: box.querySelector('.mw-ui-box-content')
        });
        mw.$(box).prepend(header);
        this.headerAnalize(header);
        this.items.push(item);
        item.options.element._prop = item;
        item.setValue(curr);
        mw.$(item).on('change', function(){
            $.each(item.getValue(), function(a, b){
                // todo: faster approach
                var index = mw.$(box).parent().children('.mw-module-settings-box').index(box);
                scope.value[index][a] = b;
            });
            $('[data-bind]', header).each(function () {
                var val = item.getValue();
                var bind = this.dataset.bind;
                if(val[bind]){
                    this.innerHTML = val[bind];
                } else {
                    this.innerHTML = this.dataset.orig;
                }
            });
            mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
        });
        $('[data-bind]', header).each(function () {
            var val = item.getValue();
            var bind = this.dataset.bind;
            this.dataset.orig = this.innerHTML;
            if(val[bind]){
                this.innerHTML = val[bind];
            }
        });
    };

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save();
            }, 500);
        }
    };

    this.refactorDomPosition = function(){
        scope.items = [];
        scope.value = [];
        mw.$(".mw-module-settings-box-index", this.options.element).each(function (i) {
            mw.$(this).text(i+1);
        });
        mw.$('.mw-module-settings-box-content', this.options.element).each(function(i){
            scope.items.push(this._prop);
            scope.value.push(this._prop.getValue());
        });
        mw.$(scope).trigger('change', [scope.value]);
    };

    this.create = function(){
        this.value.forEach(function(curr, i){
            scope.createItem(curr, i);
        });
        if(this.options.sortable && $.fn.sortable){
            var conf = {
                update: function (event, ui) {
                    setTimeout(function(){
                        scope.refactorDomPosition();
                        scope.autoSave();
                    }, 10);
                },
                handle:this.options.header ? '.mw-ui-box-header' : undefined,
                axis:'y'
            };
            if(typeof this.options.sortable === 'object'){
                conf = $.extend({}, conf, this.options.sortable);
            }
            mw.$(this.options.element).sortable(conf);
        }
    };

    this.init = function(){
        this.create();
    };

    this.save = function(){
        var key = (this.options.key || this.options.option_key);
        var group = (this.options.group || this.options.option_group);
        if( key && group){
            var options = {
                group:this.options.group,
                key:this.options.key,
                value:this.toString()
            };
            mw.options.saveOption(options, function(){
                mw.notification.msg(scope.savedMessage || mw.msg.settingsSaved)
            });
        }
        else{
            if(!key){
                console.warn('Option key is not defined.');
            }
            if(!group){
                console.warn('Option group is not defined.');
            }
        }

    };


    this.toString = function(){
        return JSON.stringify(this.value);
    };

    this.init();
};

})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-widgets/prop_editor.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.require('editor.js')
mw.propEditor = {
    addInterface:function(name, func){
        this.interfaces[name] = this.interfaces[name] || func;
    },
    getRootElement: function(node){
        if(node.nodeName !== 'IFRAME') return node;
        return $(node).contents().find('body')[0];
    },
    helpers:{
        wrapper:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-field-holder prop-ui-field-holder';
            return el;
        },
        buttonNav:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-btn-nav prop-ui-field-holder';
            return el;
        },
        quatroWrapper:function(cls){
            var el = document.createElement('div');
            el.className = cls || 'prop-ui-field-quatro';
            return el;
        },
        label:function(content){
            var el = document.createElement('label');
            el.className = 'control-label d-block prop-ui-label';
            el.innerHTML = content;
            return el;
        },
        button:function(content){
            var el = document.createElement('button');
            el.className = 'mw-ui-btn';
            el.innerHTML = content;
            return el;
        },
        field: function(val, type, options){
            type = type || 'text';
            var el;
            if(type === 'select'){
                el = document.createElement('select');
                if(options && options.length){
                    var option = document.createElement('option');
                        option.innerHTML = 'Choose...';
                        option.value = '';
                        el.appendChild(option);
                    for(var i=0;i<options.length;i++){
                        var opt = document.createElement('option');
                        if(typeof options[i] === 'string' || typeof options[i] === 'number'){
                            opt.innerHTML = options[i];
                            opt.value = options[i];
                        }
                        else{
                            opt.innerHTML = options[i].title;
                            opt.value = options[i].value;
                        }
                        el.appendChild(opt);
                    }
                }
            }
            else if(type === 'textarea'){
                el = document.createElement('textarea');
            } else{
                el = document.createElement('input');
                try { // IE11 throws error on html5 types
                    el.type = type;
                } catch (err) {
                    el.type = 'text';
                }

            }

            el.className = 'form-control prop-ui-field';
            el.value = val;
            return el;
        },
        fieldPack:function(label, type){
            var field = mw.propEditor.helpers.field('', type);
            var holder = mw.propEditor.helpers.wrapper();
            label = mw.propEditor.helpers.label(label);
            holder.appendChild(label)
            holder.appendChild(field);
            return{
                label:label,
                holder:holder,
                field:field
            }
        }
    },
    rend:function(element, rend){

        element = mw.propEditor.getRootElement(element);
        for(var i=0;i<rend.length;i++){
            element.appendChild(rend[i].node);
        }
    },
    schema:function(options){
        this._after = [];
        this.setSchema = function(schema){
            this.options.schema = schema;
            this._rend = [];
            this._valSchema = this._valSchema || {};
            for(var i =0; i< this.options.schema.length; i++){
                var item = this.options.schema[i];
                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this._cache.push(item)
                    var curr = new mw.propEditor.interfaces[item.interface](this, item);
                    this._rend.push(curr);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                }
            }
            $(this.rootHolder).html(' ').addClass('mw-prop-editor-root');
            mw.propEditor.rend(this.rootHolder, this._rend);
        };
        this.updateSchema = function(schema){
            var final = [];
            for(var i =0; i<schema.length;i++){
                var item = schema[i];

                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this.options.schema.push(item);
                    this._cache.push(item)
                    var create = new mw.propEditor.interfaces[item.interface](this, item);
                    this._rend.push(create);
                    final.push(create);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                    //this.rootHolder.appendChild(create.node);
                }
            }
            return final;
        };
        this.setValue = function(val){
            if(!val){
                return;
            }
            for(var i in val){
                var rend = this.getRendById(i);
                if(!!rend){
                    rend.setValue(val[i]);
                }
            }
        };
        this.getValue = function(){
            return this._valSchema;
        };
        this.disable = function(){
            this.disabled = true;
            $(this.rootHolder).addClass('disabled');
        };
        this.enable = function(){
            this.disabled = false;
            $(this.rootHolder).removeClass('disabled');
        };
        this.getRendById = function(id) {
            for(var i in this._rend) {
                if(this._rend[i].id === id) {
                    return this._rend[i];
                }
            }
        };
        this._cache = [];
        this.options = options;
        this.options.element = typeof this.options.element === 'string' ? document.querySelector(options.element) : this.options.element;
        this.rootHolder = mw.propEditor.getRootElement(this.options.element);
        this.setSchema(this.options.schema);

        this._after.forEach(function (value) {
            value.items.forEach(function (item) {
                value.node.appendChild(item.node);
            });
        });

        mw.trigger('ComponentsLaunch');
    },

    interfaces:{
        quatro:function(proto, config){
            //"2px 4px 8px 122px"
            var holder = mw.propEditor.helpers.quatroWrapper('mw-css-editor-group');

            for(var i = 0; i<4; i++){
                var item = mw.propEditor.helpers.fieldPack(config.label[i], 'size');
                holder.appendChild(item.holder);
                item.field.oninput = function(){
                    var final = '';
                    var all = holder.querySelectorAll('input'), i = 0;
                    for( ; i<all.length; i++){
                        var unit = all[i].dataset.unit || '';
                        final+= ' ' + all[i].value + unit ;
                    }
                    proto._valSchema[config.id] = final.trim();
                     $(proto).trigger('change', [config.id, final.trim()]);
                };
            }
            this.node = holder;
            this.setValue = function(value){
                value = value.trim();
                var arr = value.split(' ');
                var all = holder.querySelectorAll('input'), i = 0;
                for( ; i<all.length; i++){
                    all[i].value = parseInt(arr[i], 10);
                    if(typeof arr[i] === 'undefined'){
                        arr[i] = '';
                    }
                    var unit = arr[i].replace(/[0-9]/g, '');
                    all[i].dataset.unit = unit;
                }
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hr:function(proto, config){
            var el = document.createElement('hr');
            el.className = ' ';
            this.node = el;
        },
        block: function(proto, config){
            var node = document.createElement('div');
            if(typeof config.content === 'string') {
                node.innerHTML = config.content;
            } else {
                var newItems = proto.updateSchema(config.content);
                proto._after.push({node: node, items: newItems});
            }
            if(config.class){
                node.className = config.class;
            }
            this.node = node;
        },
        size:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            this.field = field;
            config.autocomplete = config.autocomplete || ['auto'];

            var holder = mw.propEditor.helpers.wrapper();
            var buttonNav = mw.propEditor.helpers.buttonNav();
            var label = mw.propEditor.helpers.label(config.label);
            var scope = this;
            var dtlist = document.createElement('datalist');
            dtlist.id = 'mw-datalist-' + mw.random();
            config.autocomplete.forEach(function (value) {
                var option = document.createElement('option');
                option.value = value;
                dtlist.appendChild(option)
            });

            this.field.setAttribute('list', dtlist.id);
            document.body.appendChild(dtlist);

            this._makeVal = function(){
                if(field.value === 'auto'){
                    return 'auto';
                }
                return field.value + field.dataset.unit;
            };

            var unitSelector = mw.propEditor.helpers.field('', 'select', [
                'px', '%', 'rem', 'em', 'vh', 'vw', 'ex', 'cm', 'mm', 'in', 'pt', 'pc', 'ch'
            ]);
            this.unitSelector = unitSelector;
            $(holder).addClass('prop-ui-field-holder-size');
            $(unitSelector)
                .val('px')
                .addClass('prop-ui-field-unit');
            unitSelector.onchange = function(){
                field.dataset.unit = $(this).val() || 'px';

                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            $(unitSelector).on('change', function(){

            });

            holder.appendChild(label);
            buttonNav.appendChild(field);
            buttonNav.appendChild(unitSelector);
            holder.appendChild(buttonNav);

            field.oninput = function(){

                proto._valSchema[config.id] = this.value + this.dataset.unit;
                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            this.node = holder;
            this.setValue = function(value){
                var an = parseInt(value, 10);
                field.value = isNaN(an) ? value : an;
                proto._valSchema[config.id] = value;
                var unit = value.replace(/[0-9]/g, '').replace(/\./g, '');
                field.dataset.unit = unit;
                $(unitSelector).val(unit);
            };
            this.id = config.id;

        },
        text:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }
            var field = mw.propEditor.helpers.field(val, 'text');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hidden:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }

            var field = mw.propEditor.helpers.field(val, 'hidden');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        shadow: function(proto, config){
            var scope = this;

            this.fields = {
                position : mw.propEditor.helpers.field('', 'select', [{title:'Outside', value: ''}, {title:'Inside', value: 'inset'}]),
                x : mw.propEditor.helpers.field('', 'number'),
                y : mw.propEditor.helpers.field('', 'number'),
                blur : mw.propEditor.helpers.field('', 'number'),
                spread : mw.propEditor.helpers.field('', 'number'),
                color : mw.propEditor.helpers.field('', 'text')
            };

            this.fields.position.placeholder = 'Position';
            this.fields.x.placeholder = 'X offset';
            this.fields.y.placeholder = 'Y offset';
            this.fields.blur.placeholder = 'Blur';
            this.fields.spread.placeholder = 'Spread';
            this.fields.color.placeholder = 'Color';
            this.fields.color.dataset.options = 'position: ' + (config.pickerPosition || 'bottom-center');
            //$(this.fields.color).addClass('mw-color-picker');
            mw.colorPicker({
                element:this.fields.color,
                position:'top-left',
                onchange:function(color){
                    $(scope.fields.color).trigger('change', color)
                    scope.fields.color.style.backgroundColor = color;
                    scope.fields.color.style.color = mw.color.isDark(color) ? 'white' : 'black';
                }
            });

            var labelPosition = mw.propEditor.helpers.label('Position');
            var labelX = mw.propEditor.helpers.label('X offset');
            var labelY = mw.propEditor.helpers.label('Y offset');
            var labelBlur = mw.propEditor.helpers.label('Blur');
            var labelSpread = mw.propEditor.helpers.label('Spread');
            var labelColor = mw.propEditor.helpers.label('Color');

            var wrapPosition = mw.propEditor.helpers.wrapper();
            var wrapX = mw.propEditor.helpers.wrapper();
            var wrapY = mw.propEditor.helpers.wrapper();
            var wrapBlur = mw.propEditor.helpers.wrapper();
            var wrapSpread = mw.propEditor.helpers.wrapper();
            var wrapColor = mw.propEditor.helpers.wrapper();



            this.$fields = $();

            $.each(this.fields, function(){
                scope.$fields.push(this);
            });

            $(this.$fields).on('input change', function(){
                var val = ($(scope.fields.position).val() || '')
                    + ' ' + (scope.fields.x.value || 0) + 'px'
                    + ' ' + (scope.fields.y.value || 0) + 'px'
                    + ' ' + (scope.fields.blur.value || 0) + 'px'
                    + ' ' + (scope.fields.spread.value || 0) + 'px'
                    + ' ' + (scope.fields.color.value || 'rgba(0,0,0,.5)');
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, val]);
            });


            var holder = mw.propEditor.helpers.wrapper();

            var label = mw.propEditor.helpers.label(config.label ? config.label : '');
            if(config.label){
                holder.appendChild(label);
            }
            var row1 = mw.propEditor.helpers.wrapper();
            var row2 = mw.propEditor.helpers.wrapper();
            row1.className = 'mw-css-editor-group';
            row2.className = 'mw-css-editor-group';


            wrapPosition.appendChild(labelPosition);
            wrapPosition.appendChild(this.fields.position);
            row1.appendChild(wrapPosition);

            wrapX.appendChild(labelX);
            wrapX.appendChild(this.fields.x);
            row1.appendChild(wrapX);


            wrapY.appendChild(labelY);
            wrapY.appendChild(this.fields.y);
            row1.appendChild(wrapY);

            wrapColor.appendChild(labelColor);
            wrapColor.appendChild(this.fields.color);
            row2.appendChild(wrapColor);

            wrapBlur.appendChild(labelBlur);
            wrapBlur.appendChild(this.fields.blur);
            row2.appendChild(wrapBlur);

            wrapSpread.appendChild(labelSpread);
            wrapSpread.appendChild(this.fields.spread);
            row2.appendChild(wrapSpread);

            holder.appendChild(row1);
            holder.appendChild(row2);

            $(this.fields).each(function () {
                $(this).on('input change', function(){
                    proto._valSchema[config.id] = this.value;
                    $(proto).trigger('change', [config.id, this.value]);
                });
            });


            this.node = holder;
            this.setValue = function(value){
                var parse = this.parseShadow(value);
                $.each(parse, function (key, val) {
                    scope.fields[key].value = this;
                });
                proto._valSchema[config.id] = value;
            };
            this.parseShadow = function(shadow){
                var inset = false;
                if(shadow.indexOf('inset') !== -1){
                    inset = true;
                }
                var arr = shadow.replace('inset', '').trim().replace(/\s{2,}/g, ' ').split(' ');
                var sh = {
                    position: inset ? 'in' : 'out',
                    x:0,
                    y: 0,
                    blur: 0,
                    spread: 0,
                    color: 'transparent'
                };
                if(!arr[2]){
                    return sh;
                }
                sh.x = arr[0];
                sh.y = arr[1];
                sh.blur = (!isNaN(parseInt(arr[2], 10))?arr[2]:'0px');
                sh.spread = (!isNaN(parseInt(arr[3], 10))?arr[3]:'0px');
                sh.color = isNaN(parseInt(arr[arr.length-1])) ? arr[arr.length-1] : 'transparent';
                return sh;
            };
            this.id = config.id;
        },
        number:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'number');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue=function(value){
                field.value = parseInt(value, 10);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        color:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            if(field.type !== 'color'){
                mw.colorPicker({
                    element:field,
                    position: config.position || 'bottom-center',
                    onchange:function(){
                        $(proto).trigger('change', [config.id, field.value]);
                    }
                });
            }
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            }
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id
        },
        select:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'select', config.options);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.onchange = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id;
        },
        file:function(proto, config){
            if(config.multiple === true){
                config.multiple = 99;
            }
            if(!config.multiple){
                config.multiple = 1;
            }
            var scope = this;
            var createButton = function(imageUrl, i, proto){
                imageUrl = imageUrl || '';
                var el = document.createElement('div');
                el.className = 'upload-button-prop mw-ui-box mw-ui-box-content';
                var btn =  document.createElement('span');
                btn.className = ('mw-ui-btn');
                btn.innerHTML = ('<span class="mw-icon-upload"></span>');
                btn.style.backgroundSize = 'cover';
                btn.style.backgroundColor = 'transparent';
                el.style.backgroundSize = 'cover';
                btn._value = imageUrl;
                btn._index = i;
                if(imageUrl){
                    el.style.backgroundImage = 'url(' + imageUrl + ')';
                }
                btn.onclick = function(){
                    mw.fileWindow({
                        types:'images',
                        change:function(url){
                            if(!url) return;
                            url = url.toString();
                            proto._valSchema[config.id] = proto._valSchema[config.id] || [];
                            proto._valSchema[config.id][btn._index] = url;
                            el.style.backgroundImage = 'url(' + url + ')';
                            btn._value = url;
                            scope.refactor();
                        }
                    });
                };
                var close = document.createElement('span');
                close.className = 'mw-badge mw-badge-important';
                close.innerHTML = '<span class="mw-icon-close"></span>';

                close.onclick = function(e){
                    scope.remove(el);
                    e.preventDefault();
                    e.stopPropagation();
                };
                el.appendChild(close);
                el.appendChild(btn);
                return el;
            };

            this.remove = function (i) {
                if(typeof i === 'number'){
                    $('.upload-button-prop', el).eq(i).remove();
                }
                else{
                    $(i).remove();
                }
                scope.refactor();
            };

            this.addImageButton = function(){
                if(config.multiple){
                    this.addBtn = document.createElement('div');
                    this.addBtn.className = 'mw-ui-link';
                    //this.addBtn.innerHTML = '<span class="mw-icon-plus"></span>';
                    this.addBtn.innerHTML = mw.msg.addImage;
                    this.addBtn.onclick = function(){
                        el.appendChild(createButton(undefined, 0, proto));
                        scope.manageAddImageButton();
                    };
                    holder.appendChild(this.addBtn);
                }
            };

            this.manageAddImageButton = function(){
                var isVisible = $('.upload-button-prop', this.node).length < config.multiple;
                this.addBtn.style.display = isVisible ? 'inline-block' : 'none';
            };

            var btn = createButton(undefined, 0, proto);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            var el = document.createElement('div');
            el.className = 'mw-ui-box-content';
            el.appendChild(btn);
            holder.appendChild(el);

            this.addImageButton();
            this.manageAddImageButton();

            if($.fn.sortable){
                $(el).sortable({
                    update:function(){
                        scope.refactor();
                    }
                });
            }



            this.refactor = function () {
                var val = [];
                $('.mw-ui-btn', el).each(function(){
                    val.push(this._value);
                });
                this.manageAddImageButton();
                if(val.length === 0){
                    val = [''];
                }
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, proto._valSchema[config.id]]);
            };

            this.node = holder;
            this.setValue = function(value){
                value = value || [''];
                proto._valSchema[config.id] = value;
                $('.upload-button-prop', holder).remove();
                if(typeof value === 'string'){
                    el.appendChild(createButton(value, 0, proto));
                }
                else{
                    $.each(value, function (index) {
                        el.appendChild(createButton(this, index, proto));
                    });
                }

                this.manageAddImageButton();
            };
            this.id = config.id;
        },
        icon: function(proto, config){
            var holder = mw.propEditor.helpers.wrapper();

            var el = document.createElement('span');
            el.className = "mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-outline";
            var elTarget = document.createElement('i');

/*            var selector = mw.iconSelector.iconDropdown(holder, {
                onchange: function (ic) {
                    proto._valSchema[config.id] = ic;
                    $(proto).trigger('change', [config.id, ic]);
                },
                mode: 'relative',
                value: ''
            });*/

            el.onclick = function () {
                picker.dialog();
            };
            mw.iconLoader().init();
            var picker = mw.iconPicker({iconOptions: false});
            picker.target = elTarget;
            picker.on('select', function (data) {
                data.render();
                proto._valSchema[config.id] = picker.target.outerHTML;
                $(proto).trigger('change', [config.id, picker.target.outerHTML]);
                picker.dialog('hide');
             });

            var label = mw.propEditor.helpers.label(config.label);

            $(el).prepend(elTarget);
            $(holder).prepend(el);
            $(holder).prepend(label);

            this.node = holder;
            this.setValue = function(value){
                if(picker && picker.value) {
                    picker.value(value);

                }
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;

        },
        richtext:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'textarea');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            $(field).on('change', function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            });
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                this.editor.setContent(value, true);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
            var defaults = {
                height: 120,
                mode: 'div',
                smallEditor: false,
                controls: [
                    [
                        'bold', 'italic',
                        {
                            group: {
                                icon: 'mdi xmdi-format-bold',
                                controls: ['underline', 'strikeThrough', 'removeFormat']
                            }
                        },

                        '|', 'align', '|', 'textColor', 'textBackgroundColor', '|', 'link', 'unlink'
                    ],
                ]
            };
            config.options = config.options || {};
            this.editor = mw.Editor($.extend({}, defaults, config.options, {selector: field}));
        }
    }
};

})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXdpZGdldHMvYmxvY2stZWRpdC5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXdpZGdldHMvY29udHJvbF9ib3guanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3N5c3RlbS13aWRnZXRzL2RvbXRyZWUuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3N5c3RlbS13aWRnZXRzL2ZpbGVtYW5hZ2VyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0td2lkZ2V0cy9maWxlcGlja2VyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0td2lkZ2V0cy9saW5rLWVkaXRvci5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXdpZGdldHMvbW9kdWxlX3NldHRpbmdzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0td2lkZ2V0cy9wcm9wX2VkaXRvci5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiOzs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLDhCQUE4Qjs7QUFFOUI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSx3REFBd0QsWUFBWSxlQUFlO0FBQ25GLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMLENBQUM7QUFDRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMLENBQUM7Ozs7Ozs7Ozs7QUN2S0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtCQUErQjtBQUMvQjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUN4RUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckIsMkNBQTJDLGFBQWE7QUFDeEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLG1DQUFtQzs7QUFFbkM7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsZUFBZSxPQUFPO0FBQ3RCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGdCQUFnQixnQkFBZ0I7QUFDaEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxnQkFBZ0IsZ0JBQWdCO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnQkFBZ0IsZ0JBQWdCO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7Ozs7Ozs7OztBQzlTQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLDZDQUE2Qyx5QkFBeUI7QUFDdEU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsbUNBQW1DO0FBQ25DLHlDQUF5QyxvQ0FBb0MsaUJBQWlCLEVBQUUsT0FBTzs7QUFFdkcsMkNBQTJDOztBQUUzQzs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7O0FBR0E7QUFDQTtBQUNBLGlCQUFpQiw0Q0FBNEMsMEJBQTBCLGNBQWMsRUFBRTtBQUN2RyxpQkFBaUIsOENBQThDLDBCQUEwQiw2QkFBNkIsRUFBRSxFQUFFO0FBQzFILGlCQUFpQiw4Q0FBOEMsMEJBQTBCLGNBQWMsRUFBRTtBQUN6RyxpQkFBaUIsNENBQTRDLDBCQUEwQixjQUFjLEVBQUU7QUFDdkc7QUFDQTtBQUNBLGtDQUFrQyxxQ0FBcUMsbUJBQW1CO0FBQzFGO0FBQ0E7QUFDQTtBQUNBLDhCQUE4QixnQkFBZ0I7QUFDOUM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvQ0FBb0MsZ0JBQWdCO0FBQ3BEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0Esa0NBQWtDLFlBQVk7QUFDOUMsd0NBQXdDLG9DQUFvQztBQUM1RSx1Q0FBdUMsaUNBQWlDO0FBQ3hFLHVDQUF1QyxrQ0FBa0M7O0FBRXpFLDJDQUEyQywrQ0FBK0M7O0FBRTFGO0FBQ0E7QUFDQTs7QUFFQSxpQkFBaUI7QUFDakIsd0NBQXdDLDRCQUE0QjtBQUNwRTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4Q0FBOEMsMENBQTBDO0FBQ3hGO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsMkJBQTJCLHFCQUFxQjtBQUNoRDtBQUNBLGFBQWE7QUFDYjtBQUNBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7O0FBR0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsc0NBQXNDLG1DQUFtQztBQUN6RTtBQUNBLHNDQUFzQywwQkFBMEI7QUFDaEUscUNBQXFDLDJDQUEyQztBQUNoRixxQ0FBcUMsMkNBQTJDO0FBQ2hGLHlDQUF5QyxvREFBb0Q7QUFDN0Ysd0NBQXdDLDBCQUEwQjtBQUNsRTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsYUFBYTs7QUFFYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7Ozs7Ozs7Ozs7QUNyV0Q7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWEsK0NBQStDO0FBQzVELGFBQWEsbUNBQW1DO0FBQ2hELGFBQWEsMkNBQTJDO0FBQ3hELGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTO0FBQ1Q7O0FBRUEsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQSxxQ0FBcUM7O0FBRXJDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEtBQUs7OztBQUdMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlGQUF5Rix5QkFBeUI7QUFDbEgscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQSxhQUFhLEdBQUcsb0JBQW9CLEVBQUU7O0FBRXRDO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0VBQWtFLG9CQUFvQjtBQUN0RjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQSxhQUFhOztBQUViLDhEQUE4RDtBQUM5RDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxLQUFLOzs7QUFHTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxnREFBZ0Q7QUFDaEQsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7O0FBRWI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6Qjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBLHFCQUFxQjtBQUNyQixpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLFNBQVM7QUFDVDs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7O0FDL2FBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQixhQUFhO0FBQzlCLGlCQUFpQixlQUFlO0FBQ2hDLGlCQUFpQixlQUFlO0FBQ2hDLGlCQUFpQixlQUFlO0FBQ2hDLGlCQUFpQixnQkFBZ0I7QUFDakMsaUJBQWlCLGlCQUFpQjtBQUNsQyxtQkFBbUIsZ0JBQWdCO0FBQ25DO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCOztBQUVBO0FBQ0E7OztBQUdBLDJDQUEyQyx5QkFBeUI7O0FBRXBFO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSx3RUFBd0UsaUNBQWlDO0FBQ3pHLDhEQUE4RCxpREFBaUQ7QUFDL0c7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZCQUE2QjtBQUM3QixxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7QUFDekIscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QixxQkFBcUI7QUFDckI7O0FBRUEsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUEsQ0FBQzs7Ozs7Ozs7OztBQzdNRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLDhCQUE4Qjs7QUFFOUI7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZEQUE2RCxNQUFNO0FBQ25FO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBOzs7QUFHQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSwrREFBK0Qsc0JBQXNCO0FBQ3JGO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckIsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0NBQWtDO0FBQ2xDO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7QUM1T0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFD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O0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTs7QUFFQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOzs7QUFHYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhOzs7QUFHYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUVBQXlFLEdBQUc7QUFDNUU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYSxFQUFFOztBQUVmO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDLG1CQUFtQjtBQUMzRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxjQUFjOztBQUVkOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCOztBQUV6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0NBQStDLDZCQUE2QixnQkFBZ0I7QUFDNUY7QUFDQTtBQUNBIiwiZmlsZSI6InN5c3RlbS13aWRnZXRzLmpzIiwic291cmNlc0NvbnRlbnQiOlsibXcuYmxvY2tFZGl0ID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcclxuICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xyXG4gICAgdmFyIGRlZmF1bHRzID0ge1xyXG4gICAgICAgIGVsZW1lbnQ6IGRvY3VtZW50LmJvZHksXHJcbiAgICAgICAgbW9kZTogJ3dyYXAnIC8vIHdyYXAgfCBpblxyXG4gICAgfTtcclxuXHJcbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xyXG5cclxuICAgIHZhciBzZXR0aW5ncyA9ICQuZXh0ZW5kKHt9LCBkZWZhdWx0cywgb3B0aW9ucyk7XHJcblxyXG4gICAgc2V0dGluZ3MuJGVsZW1lbnQgPSBtdy4kKHNldHRpbmdzLmVsZW1lbnQpO1xyXG4gICAgc2V0dGluZ3MuZWxlbWVudCA9IHNldHRpbmdzLiRlbGVtZW50WzBdO1xyXG4gICAgdGhpcy5zZXR0aW5ncyA9IHNldHRpbmdzO1xyXG4gICAgaWYoIXNldHRpbmdzLmVsZW1lbnQpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgdGhpcy5zZXQgPSBmdW5jdGlvbihtb2RlKXtcclxuICAgICAgICBpZihtb2RlID09PSAnZWRpdCcpe1xyXG4gICAgICAgICAgICB0aGlzLiRzbGlkZXIuc3RvcCgpLmFuaW1hdGUoe1xyXG4gICAgICAgICAgICAgICAgbGVmdDogJy0xMDAlJyxcclxuICAgICAgICAgICAgICAgIGhlaWdodDogdGhpcy4kZWRpdFNsaWRlLm91dGVySGVpZ2h0KClcclxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgICAgIHNjb3BlLiRob2xkZXIuYWRkQ2xhc3MoJ213LWJsb2NrLWVkaXQtZWRpdGluZycpO1xyXG4gICAgICAgICAgICAgICAgc2NvcGUuJHNsaWRlci5oZWlnaHQoJ2F1dG8nKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgdGhpcy4kc2xpZGVyLnN0b3AoKS5hbmltYXRlKHtcclxuICAgICAgICAgICAgICAgIGxlZnQ6ICcwJyxcclxuICAgICAgICAgICAgICAgIGhlaWdodDogdGhpcy4kbWFpblNsaWRlLm91dGVySGVpZ2h0KClcclxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgICAgIHNjb3BlLnVuRWRpdEJ5RWxlbWVudCgpO1xyXG4gICAgICAgICAgICAgICAgc2NvcGUuJGhvbGRlci5yZW1vdmVDbGFzcygnbXctYmxvY2stZWRpdC1lZGl0aW5nJyk7XHJcbiAgICAgICAgICAgICAgICBzY29wZS4kc2xpZGVyLmhlaWdodCgnYXV0bycpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuY2xvc2UgPSBmdW5jdGlvbihjb250ZW50KXtcclxuICAgICAgICB0aGlzLnNldCgpO1xyXG4gICAgICAgICQodGhpcykudHJpZ2dlcignQ2xvc2VFZGl0Jyk7XHJcbiAgICB9O1xyXG4gICAgdGhpcy5lZGl0ID0gZnVuY3Rpb24oY29udGVudCl7XHJcbiAgICAgICAgaWYoY29udGVudCl7XHJcbiAgICAgICAgICAgIHRoaXMuJGVkaXRTbGlkZS5lbXB0eSgpLmFwcGVuZChjb250ZW50KTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5zZXQoJ2VkaXQnKTtcclxuICAgICAgICAkKHRoaXMpLnRyaWdnZXIoJ0VkaXQnKTtcclxuICAgIH07XHJcblxyXG4gICAgdGhpcy5fZWRpdEJ5RWxlbWVudCA9IG51bGw7XHJcbiAgICB0aGlzLl9lZGl0QnlFbGVtZW50VGVtcCA9IG51bGw7XHJcblxyXG4gICAgdGhpcy51bkVkaXRCeUVsZW1lbnQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIGlmKHRoaXMuX2VkaXRCeUVsZW1lbnQpe1xyXG4gICAgICAgICAgICAkKHRoaXMuX2VkaXRCeUVsZW1lbnRUZW1wKS5yZXBsYWNlV2l0aCh0aGlzLl9lZGl0QnlFbGVtZW50KTtcclxuICAgICAgICAgICAgJCh0aGlzLl9lZGl0QnlFbGVtZW50KS5oaWRlKClcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5fZWRpdEJ5RWxlbWVudCA9IG51bGw7XHJcbiAgICAgICAgdGhpcy5fZWRpdEJ5RWxlbWVudFRlbXAgPSBudWxsO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmVkaXRCeUVsZW1lbnQgPSBmdW5jdGlvbihlbCl7XHJcbiAgICAgICAgaWYoIWVsKXtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICB0aGlzLnVuRWRpdEJ5RWxlbWVudCgpO1xyXG4gICAgICAgIHRoaXMuX2VkaXRCeUVsZW1lbnRUZW1wID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbXctdGVtcCcpO1xyXG4gICAgICAgIHRoaXMuX2VkaXRCeUVsZW1lbnQgPSBlbDtcclxuICAgICAgICAkKGVsKS5iZWZvcmUodGhpcy5fZWRpdEJ5RWxlbWVudFRlbXApO1xyXG4gICAgICAgIHRoaXMuZWRpdFNsaWRlLmFwcGVuZENoaWxkKGVsKTtcclxuICAgICAgICAkKGVsKS5zaG93KClcclxuICAgIH07XHJcbiAgICB0aGlzLm1vZHVsZUVkaXQgPSBmdW5jdGlvbihtb2R1bGUsIHBhcmFtcyl7XHJcbiAgICAgICAgbXcudG9vbHMubG9hZGluZyh0aGlzLmhvbGRlciwgOTApO1xyXG4gICAgICAgIG13LmxvYWRfbW9kdWxlKG1vZHVsZSwgdGhpcy5lZGl0U2xpZGUsIGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAgIHNjb3BlLmVkaXQoKTtcclxuICAgICAgICAgICAgbXcudG9vbHMubG9hZGluZyhzY29wZS5ob2xkZXIsIGZhbHNlKTtcclxuICAgICAgICB9LCBwYXJhbXMpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmJ1aWxkID0gZnVuY3Rpb24oKXtcclxuICAgICAgICB0aGlzLmhvbGRlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgIHRoaXMuJGhvbGRlciA9ICQodGhpcy5ob2xkZXIpO1xyXG4gICAgICAgIHRoaXMuaG9sZGVyLmNsYXNzTmFtZSA9ICdtdy1ibG9jay1lZGl0LWhvbGRlcic7XHJcbiAgICAgICAgdGhpcy5ob2xkZXIuX2Jsb2NrRWRpdCA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5zbGlkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICB0aGlzLiRzbGlkZXIgPSAkKHRoaXMuc2xpZGVyKTtcclxuICAgICAgICB0aGlzLnNsaWRlci5jbGFzc05hbWUgPSAnbXctYmxvY2stZWRpdC1zbGlkZXInO1xyXG4gICAgICAgIHRoaXMubWFpblNsaWRlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgdGhpcy4kbWFpblNsaWRlID0gJCh0aGlzLm1haW5TbGlkZSk7XHJcbiAgICAgICAgdGhpcy5lZGl0U2xpZGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICB0aGlzLiRlZGl0U2xpZGUgPSAkKHRoaXMuZWRpdFNsaWRlKTtcclxuICAgICAgICB0aGlzLm1haW5TbGlkZS5jbGFzc05hbWUgPSAnbXctYmxvY2stZWRpdC1tYWluLXNsaWRlJztcclxuICAgICAgICB0aGlzLmVkaXRTbGlkZS5jbGFzc05hbWUgPSAnbXctYmxvY2stZWRpdC1lZGl0LXNsaWRlJztcclxuXHJcbiAgICAgICAgdGhpcy5zbGlkZXIuYXBwZW5kQ2hpbGQodGhpcy5tYWluU2xpZGUpO1xyXG4gICAgICAgIHRoaXMuc2xpZGVyLmFwcGVuZENoaWxkKHRoaXMuZWRpdFNsaWRlKTtcclxuICAgICAgICB0aGlzLmhvbGRlci5hcHBlbmRDaGlsZCh0aGlzLnNsaWRlcik7XHJcbiAgICAgICAgLy90aGlzLnNldHRpbmdzLiRlbGVtZW50LmJlZm9yZSh0aGlzLmhvbGRlcik7XHJcbiAgICAgICAgLy8gdGhpcy5tYWluU2xpZGUuYXBwZW5kQ2hpbGQoc2V0dGluZ3MuZWxlbWVudCk7XHJcblxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmluaXRNb2RlID0gZnVuY3Rpb24oKXtcclxuICAgICAgICBpZih0aGlzLnNldHRpbmdzLm1vZGUgPT09ICd3cmFwJykge1xyXG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzLiRlbGVtZW50LmFmdGVyKHRoaXMuaG9sZGVyKTtcclxuICAgICAgICAgICAgdGhpcy4kbWFpblNsaWRlLmFwcGVuZCh0aGlzLnNldHRpbmdzLiRlbGVtZW50KTtcclxuICAgICAgICB9IGVsc2UgaWYodGhpcy5zZXR0aW5ncy5tb2RlID09PSAnaW4nKSB7XHJcbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MuJGVsZW1lbnQud3JhcElubmVyKHRoaXMuaG9sZGVyKTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB0aGlzLmJ1aWxkKCk7XHJcbiAgICAgICAgdGhpcy5pbml0TW9kZSgpO1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmluaXQoKTtcclxuXHJcbn07XHJcblxyXG5tdy5ibG9ja0VkaXQuZ2V0ID0gZnVuY3Rpb24odGFyZ2V0KXtcclxuICAgIHRhcmdldCA9IHRhcmdldCB8fCAnLm13LWJsb2NrLWVkaXQtaG9sZGVyJztcclxuICAgIHRhcmdldCA9IG13LiQodGFyZ2V0KTtcclxuICAgIGlmKCF0YXJnZXRbMF0pIHJldHVybjtcclxuICAgIGlmKHRhcmdldC5oYXNDbGFzcygnbXctYmxvY2stZWRpdC1ob2xkZXInKSl7XHJcbiAgICAgICAgcmV0dXJuIHRhcmdldFswXS5fYmxvY2tFZGl0O1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICB2YXIgbm9kZSA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKHRhcmdldFswXSwgJ213LWJsb2NrLWVkaXQtaG9sZGVyJykgfHwgdGFyZ2V0WzBdLnF1ZXJ5U2VsZWN0b3IoJy5tdy1ibG9jay1lZGl0LWhvbGRlcicpO1xyXG4gICAgICAgIGlmKG5vZGUpe1xyXG4gICAgICAgICAgICByZXR1cm4gbm9kZS5fYmxvY2tFZGl0O1xyXG4gICAgICAgIH1cclxuICAgIH1cclxufTtcclxuXHJcbiQuZm4ubXdCbG9ja0VkaXQgPSBmdW5jdGlvbiAob3B0aW9ucykge1xyXG4gICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XHJcbiAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdGhpcy5td0Jsb2NrRWRpdCA9ICBuZXcgbXcuYmxvY2tFZGl0KCQuZXh0ZW5kKHt9LCBvcHRpb25zLCB7ZWxlbWVudDogdGhpcyB9KSk7XHJcbiAgICB9KTtcclxufTtcclxuXHJcbm13LnJlZ2lzdGVyQ29tcG9uZW50KCdibG9jay1lZGl0JywgZnVuY3Rpb24oZWwpe1xyXG4gICAgdmFyIG9wdGlvbnMgPSBtdy5jb21wb25lbnRzLl9vcHRpb25zKGVsKTtcclxuICAgIG13LiQoZWwpLm13QmxvY2tFZGl0KG9wdGlvbnMpO1xyXG59KTtcclxubXcucmVnaXN0ZXJDb21wb25lbnQoJ2Jsb2NrLWVkaXQtY2xvc2VCdXR0b24nLCBmdW5jdGlvbihlbCl7XHJcbiAgICBtdy4kKGVsKS5vbignY2xpY2snLCBmdW5jdGlvbigpe1xyXG4gICAgICAgIG13LmJsb2NrRWRpdC5nZXQodGhpcykuY2xvc2UoKTtcclxuICAgIH0pO1xyXG59KTtcclxubXcucmVnaXN0ZXJDb21wb25lbnQoJ2Jsb2NrLWVkaXQtZWRpdEJ1dHRvbicsIGZ1bmN0aW9uKGVsKXtcclxuICAgIG13LiQoZWwpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgdmFyIG9wdGlvbnMgPSBtdy5jb21wb25lbnRzLl9vcHRpb25zKHRoaXMpO1xyXG4gICAgICAgIGlmKG9wdGlvbnMubW9kdWxlKXtcclxuICAgICAgICAgICAgbXcuYmxvY2tFZGl0LmdldChvcHRpb25zLmZvciB8fCB0aGlzKS5tb2R1bGVFZGl0KG9wdGlvbnMubW9kdWxlKTtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH0gZWxzZSBpZihvcHRpb25zLmVsZW1lbnQpe1xyXG4gICAgICAgICAgICB2YXIgZWwgPSBtdy4kKG9wdGlvbnMuZWxlbWVudClbMF07XHJcbiAgICAgICAgICAgIGlmKGVsKXtcclxuICAgICAgICAgICAgICAgIG13LmJsb2NrRWRpdC5nZXQob3B0aW9ucy5mb3IgfHwgdGhpcykuZWRpdEJ5RWxlbWVudChlbCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgbXcuYmxvY2tFZGl0LmdldCh0aGlzKS5lZGl0KCk7XHJcbiAgICB9KTtcclxufSk7XHJcbiIsIm13LmNvbnRyb2xCb3ggPSBmdW5jdGlvbihvcHRpb25zKXtcclxuICAgIHZhciBzY29wZSA9IHRoaXM7XHJcbiAgICB0aGlzLm9wdGlvbnMgPSBvcHRpb25zO1xyXG4gICAgdGhpcy5kZWZhdWx0cyA9IHtcclxuICAgICAgICBwb3NpdGlvbjonYm90dG9tJyxcclxuICAgICAgICBjb250ZW50OicnLFxyXG4gICAgICAgIHNraW46J2RlZmF1bHQnLFxyXG4gICAgICAgIGlkOnRoaXMub3B0aW9ucy5pZCB8fCAnbXctY29udHJvbC1ib3gtJyttdy5yYW5kb20oKSxcclxuICAgICAgICBjbG9zZUJ1dHRvbjogdHJ1ZVxyXG4gICAgfTtcclxuICAgIHRoaXMuaWQgPSB0aGlzLm9wdGlvbnMuaWQ7XHJcbiAgICB0aGlzLnNldHRpbmdzID0gJC5leHRlbmQoe30sIHRoaXMuZGVmYXVsdHMsIHRoaXMub3B0aW9ucyk7XHJcbiAgICB0aGlzLmFjdGl2ZSA9IGZhbHNlO1xyXG5cclxuICAgIHRoaXMuYnVpbGQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMuYm94ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgdGhpcy5ib3guY2xhc3NOYW1lID0gJ213LWNvbnRyb2wtYm94IG13LWNvbnRyb2wtYm94LScgKyB0aGlzLnNldHRpbmdzLnBvc2l0aW9uICsgJyBtdy1jb250cm9sLWJveC0nICsgdGhpcy5zZXR0aW5ncy5za2luO1xyXG4gICAgICAgIHRoaXMuYm94LmlkID0gdGhpcy5pZDtcclxuICAgICAgICB0aGlzLmJveENvbnRlbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICB0aGlzLmJveENvbnRlbnQuY2xhc3NOYW1lID0gJ213LWNvbnRyb2wtYm94Y29udGVudCc7XHJcbiAgICAgICAgdGhpcy5ib3guYXBwZW5kQ2hpbGQodGhpcy5ib3hDb250ZW50KTtcclxuICAgICAgICB0aGlzLmNyZWF0ZUNsb3NlQnV0dG9uKCk7XHJcbiAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZCh0aGlzLmJveCk7XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuY3JlYXRlQ2xvc2VCdXR0b24gPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYoIXRoaXMub3B0aW9ucy5jbG9zZUJ1dHRvbikgcmV0dXJuO1xyXG4gICAgICAgIHRoaXMuY2xvc2VCdXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5jbGFzc05hbWUgPSAnbXctY29udHJvbC1ib3hjbG9zZSc7XHJcbiAgICAgICAgdGhpcy5ib3guYXBwZW5kQ2hpbGQodGhpcy5jbG9zZUJ1dHRvbik7XHJcbiAgICAgICAgdGhpcy5jbG9zZUJ1dHRvbi5vbmNsaWNrID0gZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgc2NvcGUuaGlkZSgpO1xyXG4gICAgICAgIH07XHJcbiAgICB9O1xyXG5cclxuICAgIHRoaXMuc2V0Q29udGVudEJ5VXJsID0gZnVuY3Rpb24oKXtcclxuICAgICAgICB2YXIgY29udCA9IHRoaXMuc2V0dGluZ3MuY29udGVudC50cmltKCk7XHJcbiAgICAgICAgcmV0dXJuICQuZ2V0KGNvbnQsIGZ1bmN0aW9uKGRhdGEpe1xyXG4gICAgICAgICAgICBzY29wZS5ib3hDb250ZW50LmlubmVySFRNTCA9IGRhdGE7XHJcbiAgICAgICAgICAgIHNjb3BlLnNldHRpbmdzLmNvbnRlbnQgPSBkYXRhO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxuICAgIHRoaXMuc2V0Q29udGVudCA9IGZ1bmN0aW9uKGMpe1xyXG4gICAgICAgIHZhciBjb250ID0gY3x8dGhpcy5zZXR0aW5ncy5jb250ZW50LnRyaW0oKTtcclxuICAgICAgICB0aGlzLnNldHRpbmdzLmNvbnRlbnQgPSBjb250O1xyXG4gICAgICAgIGlmKGNvbnQuaW5kZXhPZignaHR0cDovLycpID09PSAwIHx8IGNvbnQuaW5kZXhPZignaHR0cHM6Ly8nKSA9PT0gMCl7XHJcbiAgICAgICAgICAgIHJldHVybiB0aGlzLnNldENvbnRlbnRCeVVybCgpXHJcbiAgICAgICAgfVxyXG4gICAgICAgIHRoaXMuYm94Q29udGVudC5pbm5lckhUTUwgPSBjb250O1xyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLnNob3cgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMuYWN0aXZlID0gdHJ1ZTtcclxuICAgICAgICBtdy4kKHRoaXMuYm94KS5hZGRDbGFzcygnYWN0aXZlJykgO1xyXG4gICAgICAgIG13LiQodGhpcykudHJpZ2dlcignQ29udHJvbEJveFNob3cnKVxyXG4gICAgfTtcclxuXHJcbiAgICB0aGlzLmluaXQgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMuYnVpbGQoKTtcclxuICAgICAgICB0aGlzLnNldENvbnRlbnQoKTtcclxuICAgIH07XHJcbiAgICB0aGlzLmhpZGUgPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHRoaXMuYWN0aXZlID0gZmFsc2U7XHJcbiAgICAgICAgbXcuJCh0aGlzLmJveCkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xyXG4gICAgICAgIG13LiQodGhpcykudHJpZ2dlcignQ29udHJvbEJveEhpZGUnKTtcclxuICAgIH07XHJcblxyXG5cclxuICAgIHRoaXMudG9nZ2xlID0gZnVuY3Rpb24oKXtcclxuICAgICAgICB0aGlzW3RoaXMuYWN0aXZlPydoaWRlJzonc2hvdyddKCk7XHJcbiAgICB9O1xyXG4gICAgdGhpcy5pbml0KCk7XHJcbn07XHJcbiIsIm13LkRvbVRyZWUgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgIHZhciBzY29wZSA9IHRoaXM7XG4gICAgdGhpcy5wcmVwYXJlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBzZWxlY3RvcjogJy5lZGl0JyxcbiAgICAgICAgICAgIGRvY3VtZW50OiBkb2N1bWVudCxcbiAgICAgICAgICAgIHRhcmdldERvY3VtZW50OiBkb2N1bWVudCxcbiAgICAgICAgICAgIGNvbXBvbmVudE1hdGNoOiBbXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogIGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gJ0VkaXQnO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB0ZXN0OiBmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmhhc0NsYXNzKG5vZGUsICdlZGl0Jyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgaWNvbiA9IG13LnRvcCgpLmxpdmVfZWRpdC5nZXRNb2R1bGVJY29uKG5vZGUuZ2V0QXR0cmlidXRlKCdkYXRhLXR5cGUnKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gaWNvbiArICcgJyArIG5vZGUuZ2V0QXR0cmlidXRlKCdkYXRhLW13LXRpdGxlJykgfHwgbm9kZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtdHlwZScpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB0ZXN0OiBmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmhhc0NsYXNzKG5vZGUsICdtb2R1bGUnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0ltYWdlJyxcbiAgICAgICAgICAgICAgICAgICAgdGVzdDogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBub2RlLm5vZGVOYW1lID09PSAnSU1HJztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogIGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgaWQgPSBub2RlLmlkID8gJyMnICsgbm9kZS5pZCA6ICcnO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG5vZGUubm9kZU5hbWUudG9Mb3dlckNhc2UoKSA7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHRlc3Q6IGZ1bmN0aW9uIChub2RlKSB7IHJldHVybiB0cnVlOyB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIGNvbXBvbmVudFR5cGVzOiBbXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ1NhZmVNb2RlJyxcbiAgICAgICAgICAgICAgICAgICAgdGVzdDogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBtdy50b29scy5wYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0KG5vZGUsIFsgJ3NhZmUtbW9kZScsICdyZWd1bGFyLW1vZGUnIF0pO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXVxuICAgICAgICB9O1xuICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcblxuICAgICAgICB0aGlzLnNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcblxuICAgICAgICB0aGlzLiRob2xkZXIgPSAkKHRoaXMuc2V0dGluZ3MuZWxlbWVudCk7XG5cbiAgICAgICAgdGhpcy5kb2N1bWVudCA9IHRoaXMuc2V0dGluZ3MuZG9jdW1lbnQ7XG4gICAgICAgIHRoaXMudGFyZ2V0RG9jdW1lbnQgPSB0aGlzLnNldHRpbmdzLnRhcmdldERvY3VtZW50O1xuXG4gICAgICAgIHRoaXMuX3NlbGVjdGVkRG9tTm9kZSA9IG51bGw7XG4gICAgfTtcbiAgICB0aGlzLnByZXBhcmUoKTtcblxuICAgIHRoaXMuY3JlYXRlTGlzdCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndWwnKTtcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGVSb290ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLnJvb3QgPSB0aGlzLmNyZWF0ZUxpc3QoKTtcbiAgICAgICAgdGhpcy5yb290LmNsYXNzTmFtZSA9ICdtdy1kZWZhdWx0cyBtdy1kb210cmVlJztcbiAgICB9O1xuXG5cbiAgICB0aGlzLl9nZXQgPSBmdW5jdGlvbiAobm9kZU9yVHJlZU5vZGUpIHtcbiAgICAgICAgY29uc29sZS5sb2cobm9kZU9yVHJlZU5vZGUpXG4gICAgICAgIHJldHVybiBub2RlT3JUcmVlTm9kZS5fdmFsdWUgPyBub2RlT3JUcmVlTm9kZSA6IHRoaXMuZmluZEVsZW1lbnRJblRyZWUobm9kZU9yVHJlZU5vZGUpO1xuICAgIH07XG5cbiAgICB0aGlzLnNlbGVjdCA9IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIHZhciBlbCA9IHRoaXMuZ2V0QnlOb2RlKG5vZGUpO1xuICAgICAgICBpZiAoZWwpIHtcbiAgICAgICAgICAgIHRoaXMuc2VsZWN0ZWQoZWwpO1xuICAgICAgICAgICAgdGhpcy5vcGVuUGFyZW50cyhlbCk7XG4gICAgICAgICAgICB0aGlzLl9zY3JvbGxUbyhlbCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbiAobm9kZU9yVHJlZU5vZGUpIHtcbiAgICAgICAgdmFyIGxpID0gdGhpcy5fZ2V0KG5vZGVPclRyZWVOb2RlKTtcbiAgICAgICAgdGhpc1sgbGkuX29wZW5lZCA/ICdjbG9zZScgOiAnb3BlbiddKGxpKTtcbiAgICB9O1xuXG4gICAgdGhpcy5fb3BlbmVkID0gW107XG5cbiAgICB0aGlzLm9wZW4gPSBmdW5jdGlvbiAobm9kZU9yVHJlZU5vZGUpIHtcbiAgICAgICAgdmFyIGxpID0gdGhpcy5fZ2V0KG5vZGVPclRyZWVOb2RlKTtcbiAgICAgICAgbGkuX29wZW5lZCA9IHRydWU7XG4gICAgICAgIGxpLmNsYXNzTGlzdC5hZGQoJ2V4cGFuZCcpO1xuICAgICAgICBpZih0aGlzLl9vcGVuZWQuaW5kZXhPZihsaS5fdmFsdWUpID09PSAtMSkge1xuICAgICAgICAgICAgdGhpcy5fb3BlbmVkLnB1c2gobGkuX3ZhbHVlKTtcbiAgICAgICAgfVxuICAgIH07XG4gICAgdGhpcy5jbG9zZSA9IGZ1bmN0aW9uIChub2RlT3JUcmVlTm9kZSkge1xuICAgICAgICB2YXIgbGkgPSB0aGlzLl9nZXQobm9kZU9yVHJlZU5vZGUpO1xuICAgICAgICBsaS5fb3BlbmVkID0gZmFsc2U7XG4gICAgICAgIGxpLmNsYXNzTGlzdC5yZW1vdmUoJ2V4cGFuZCcpO1xuICAgICAgICB2YXIgaW5kID0gdGhpcy5fb3BlbmVkLmluZGV4T2YobGkuX3ZhbHVlKTtcbiAgICAgICAgaWYoIGluZCAhPT0gLTEgKSB7XG4gICAgICAgICAgICB0aGlzLl9vcGVuZWQuc3BsaWNlKGluZCwgaW5kKVxuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMuX3Njcm9sbFRvID0gZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2NvcGUuJGhvbGRlci5zdG9wKCkuYW5pbWF0ZSh7XG4gICAgICAgICAgICAgICAgc2Nyb2xsVG9wOiAoc2NvcGUuJGhvbGRlci5zY3JvbGxUb3AoKSArICgkKGVsKS5vZmZzZXQoKS50b3AgLSBzY29wZS4kaG9sZGVyLm9mZnNldCgpLnRvcCkpIC0gKHNjb3BlLiRob2xkZXIuaGVpZ2h0KCkvMiAtIDEwKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sIDU1KTtcbiAgICB9O1xuXG4gICAgdGhpcy5vcGVuUGFyZW50cyA9IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIG5vZGUgPSB0aGlzLl9nZXQobm9kZSk7XG4gICAgICAgIHdoaWxlKG5vZGUgJiYgbm9kZSAhPT0gdGhpcy5yb290KSB7XG4gICAgICAgICAgICBpZihub2RlLm5vZGVOYW1lID09PSAnTEknKXtcbiAgICAgICAgICAgICAgICB0aGlzLm9wZW4obm9kZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBub2RlID0gbm9kZS5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgfTtcbiAgICB0aGlzLnNlbGVjdGVkID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBub2RlID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX3NlbGVjdGVkRG9tTm9kZTtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKCcuc2VsZWN0ZWQnLCB0aGlzLnJvb3QpLnJlbW92ZUNsYXNzKCdzZWxlY3RlZCcpO1xuICAgICAgICBub2RlLmNsYXNzTGlzdC5hZGQoJ3NlbGVjdGVkJyk7XG4gICAgICAgIHRoaXMuX3NlbGVjdGVkRG9tTm9kZSA9IG5vZGU7XG4gICAgfTtcblxuICAgIHRoaXMuZ2V0QnlOb2RlID0gZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHZhciBhbGwgPSB0aGlzLnJvb3QucXVlcnlTZWxlY3RvckFsbCgnbGknKTtcbiAgICAgICAgdmFyIGwgPSBhbGwubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgZm9yICggOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICBpZiAoYWxsW2ldLl92YWx1ZSA9PT0gZWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gYWxsW2ldO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMuZ2V0QnlUcmVlTm9kZSA9IGZ1bmN0aW9uICh0cmVlTm9kZSkge1xuICAgICAgICByZXR1cm4gdHJlZU5vZGUuX3ZhbHVlO1xuICAgIH07XG5cbiAgICB0aGlzLmFsbERvbU5vZGVzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gQXJyYXkuZnJvbSh0aGlzLm1hcCgpLmtleXMoKSk7XG4gICAgfTtcblxuICAgIHRoaXMuYWxsVHJlZU5vZGVzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gQXJyYXkuZnJvbSh0aGlzLm1hcCgpLnZhbHVlcygpKTtcbiAgICB9O1xuXG4gICAgdGhpcy5lbXB0eVRyZWVOb2RlID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgdmFyIGxpID0gdGhpcy5nZXRCeU5vZGUobm9kZSk7XG4gICAgICAgICQobGkpLmVtcHR5KCk7XG4gICAgICAgIHJldHVybiBsaTtcbiAgICB9O1xuXG4gICAgdGhpcy5yZWZyZXNoID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgdmFyIGl0ZW0gPSB0aGlzLmVtcHR5VHJlZU5vZGUobm9kZSk7XG4gICAgICAgIHRoaXMuY3JlYXRlQ2hpbGRyZW4obm9kZSwgaXRlbSk7XG4gICAgfTtcblxuICAgIHRoaXMuX2N1cnJlbnRUYXJnZXQgPSBudWxsO1xuICAgIHRoaXMuY3JlYXRlSXRlbUV2ZW50cyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgJCh0aGlzLnJvb3QpXG4gICAgICAgICAgICAub24oJ21vdXNlbW92ZScsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgdmFyIHRhcmdldCA9IGUudGFyZ2V0O1xuICAgICAgICAgICAgICAgIGlmKHRhcmdldC5ub2RlTmFtZSAhPT0gJ0xJJykge1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQgPSB0YXJnZXQucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYoc2NvcGUuX2N1cnJlbnRUYXJnZXQgIT09IHRhcmdldCkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5fY3VycmVudFRhcmdldCA9IHRhcmdldDtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCgnbGkuaG92ZXInLCBzY29wZS5yb290KS5yZW1vdmVDbGFzcygnaG92ZXInKTtcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0LmNsYXNzTGlzdC5hZGQoJ2hvdmVyJyk7XG4gICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLm9uSG92ZXIpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnNldHRpbmdzLm9uSG92ZXIuY2FsbChzY29wZSwgZSwgdGFyZ2V0LCB0YXJnZXQuX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAub24oJ21vdXNlbGVhdmUnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIG13LiQoJ2xpJywgc2NvcGUucm9vdCkucmVtb3ZlQ2xhc3MoJ2hvdmVyJyk7XG4gICAgICAgICAgICB9KVxuICAgICAgICAgICAgLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgdmFyIHRhcmdldCA9IGUudGFyZ2V0O1xuXG4gICAgICAgICAgICAgICAgaWYodGFyZ2V0Lm5vZGVOYW1lICE9PSAnTEknKSB7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyh0YXJnZXQsICdsaScpO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS50b2dnbGUodGFyZ2V0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgc2NvcGUuc2VsZWN0ZWQodGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXQubm9kZU5hbWUgPT09ICdMSScgJiYgc2NvcGUuc2V0dGluZ3Mub25TZWxlY3QpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3Mub25TZWxlY3QuY2FsbChzY29wZSwgZSwgdGFyZ2V0LCB0YXJnZXQuX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5tYXAgPSBmdW5jdGlvbiAobm9kZSwgdHJlZU5vZGUpIHtcbiAgICAgICAgaWYgKCF0aGlzLl9tYXApIHtcbiAgICAgICAgICAgIHRoaXMuX21hcCA9IG5ldyBNYXAoKTtcbiAgICAgICAgfVxuICAgICAgICBpZighbm9kZSkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX21hcDtcbiAgICAgICAgfVxuICAgICAgICBpZiAoIXRyZWVOb2RlKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fbWFwLmdldChub2RlKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoIXRoaXMuX21hcC5oYXMobm9kZSkpIHtcbiAgICAgICAgICAgIHRoaXMuX21hcC5zZXQobm9kZSwgdHJlZU5vZGUpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlSXRlbSA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgIGlmKCF0aGlzLnZhbGlkYXRlTm9kZShpdGVtKSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIHZhciBsaSA9IHRoaXMuZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbGknKTtcbiAgICAgICAgbGkuX3ZhbHVlID0gaXRlbTtcbiAgICAgICAgbGkuY2xhc3NOYW1lID0gJ213LWRvbXRyZWUtaXRlbScgKyAodGhpcy5fc2VsZWN0ZWREb21Ob2RlID09PSBpdGVtID8gJyBhY3RpdmUnIDogJycpO1xuICAgICAgICB2YXIgZGlvID0gaXRlbS5jaGlsZHJlbi5sZW5ndGggPyAnPGkgY2xhc3M9XCJtdy1kb210cmVlLWl0ZW0tb3BlbmVyXCI+PC9pPicgOiAnJztcbiAgICAgICAgbGkuaW5uZXJIVE1MID0gZGlvICsgJzxzcGFuIGNsYXNzPVwibXctZG9tdHJlZS1pdGVtLWxhYmVsXCI+JyArIHRoaXMuZ2V0Q29tcG9uZW50TGFiZWwoaXRlbSkgKyAnPC9zcGFuPic7XG4gICAgICAgIHJldHVybiBsaTtcbiAgICB9O1xuXG4gICAgdGhpcy5nZXRDb21wb25lbnRMYWJlbCA9IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIHZhciBhbGwgPSB0aGlzLnNldHRpbmdzLmNvbXBvbmVudE1hdGNoLCBpID0gMDtcbiAgICAgICAgZm9yICggIDsgaSA8IGFsbC5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgIGlmKCBhbGxbaV0udGVzdChub2RlKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0eXBlb2YgYWxsW2ldLmxhYmVsID09PSAnc3RyaW5nJyA/IGFsbFtpXS5sYWJlbCA6IGFsbFtpXS5sYWJlbC5jYWxsKHRoaXMsIG5vZGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcbiAgICB0aGlzLmlzQ29tcG9uZW50ID0gZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgdmFyIGFsbCA9IHRoaXMuc2V0dGluZ3MuY29tcG9uZW50TWF0Y2gsIGkgPSAwO1xuICAgICAgICBmb3IgKCAgOyBpIDwgYWxsLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgaWYoIGFsbFtpXS50ZXN0KG5vZGUpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH07XG5cbiAgICB0aGlzLnZhbGlkYXRlTm9kZSA9IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIGlmKG5vZGUubm9kZVR5cGUgIT09IDEpe1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIHZhciB0YWcgPSBub2RlLm5vZGVOYW1lO1xuICAgICAgICBpZih0YWcgPT09ICdTQ1JJUFQnIHx8IHRhZyA9PT0gJ1NUWUxFJyB8fCB0YWcgPT09ICdMSU5LJyB8fCB0YWcgPT09ICdCUicpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gdGhpcy5pc0NvbXBvbmVudChub2RlKTtcbiAgICB9O1xuXG4gICAgdGhpcy5jcmVhdGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBhbGwgPSB0aGlzLnRhcmdldERvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwodGhpcy5zZXR0aW5ncy5zZWxlY3Rvcik7XG4gICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgZm9yICggIDsgaSA8IGFsbC5sZW5ndGg7IGkrKyApIHtcbiAgICAgICAgICAgIHZhciBpdGVtID0gdGhpcy5jcmVhdGVJdGVtKGFsbFtpXSk7XG4gICAgICAgICAgICBpZihpdGVtKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5yb290LmFwcGVuZENoaWxkKGl0ZW0pO1xuICAgICAgICAgICAgICAgIHRoaXMuY3JlYXRlQ2hpbGRyZW4oYWxsW2ldLCBpdGVtKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICB0aGlzLmNyZWF0ZUl0ZW1FdmVudHMoKTtcbiAgICAgICAgJCh0aGlzLnNldHRpbmdzLmVsZW1lbnQpLmVtcHR5KCkuYXBwZW5kKHRoaXMucm9vdCkucmVzaXphYmxlKHtcbiAgICAgICAgICAgIGhhbmRsZXM6IFwic1wiLFxuICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uKCBldmVudCwgdWkgKSB7XG4gICAgICAgICAgICAgICAgdWkuZWxlbWVudC5jc3MoJ21heEhlaWdodCcsICdub25lJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZUNoaWxkcmVuID0gZnVuY3Rpb24gKG5vZGUsIHBhcmVudCkge1xuICAgICAgICBpZighcGFyZW50KSByZXR1cm47XG4gICAgICAgIHZhciBsaXN0ID0gdGhpcy5jcmVhdGVMaXN0KCk7XG4gICAgICAgIHZhciBjdXJyID0gbm9kZS5jaGlsZHJlblswXTtcbiAgICAgICAgd2hpbGUgKGN1cnIpIHtcbiAgICAgICAgICAgIHZhciBpdGVtID0gdGhpcy5jcmVhdGVJdGVtKGN1cnIpO1xuICAgICAgICAgICAgaWYgKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICBsaXN0LmFwcGVuZENoaWxkKGl0ZW0pO1xuICAgICAgICAgICAgICAgIGlmIChjdXJyLmNoaWxkcmVuLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmNyZWF0ZUNoaWxkcmVuKGN1cnIsIGl0ZW0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLm5leHRFbGVtZW50U2libGluZztcbiAgICAgICAgfVxuICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQobGlzdCk7XG4gICAgfTtcblxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5jcmVhdGVSb290KCk7XG4gICAgICAgIHRoaXMuY3JlYXRlKCk7XG4gICAgfTtcblxuICAgIHRoaXMuaW5pdCgpO1xuXG59O1xuIiwiKGZ1bmN0aW9uICgpe1xuICAgIG13LnJlcXVpcmUoJ2ZpbGVtYW5hZ2VyLmNzcycpO1xuICAgIHZhciBGaWxlTWFuYWdlciA9IGZ1bmN0aW9uIChvcHRpb25zKSB7XG5cbiAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcblxuICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcblxuICAgICAgICB2YXIgZGVmYXVsdFJlcXVlc3QgPSBmdW5jdGlvbiAocGFyYW1zLCBjYWxsYmFjaywgZXJyb3IpIHtcbiAgICAgICAgICAgIHZhciB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcbiAgICAgICAgICAgIHNjb3BlLmRpc3BhdGNoKCdiZWZvcmVSZXF1ZXN0Jywge3hocjogeGhyLCBwYXJhbXM6IHBhcmFtc30pO1xuICAgICAgICAgICAgeGhyLm9ucmVhZHlzdGF0ZWNoYW5nZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLnJlYWR5U3RhdGUgPT09IDQgJiYgdGhpcy5zdGF0dXMgPT09IDIwMCkge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKHNjb3BlLCBKU09OLnBhcnNlKHRoaXMucmVzcG9uc2VUZXh0KSwgeGhyKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgeGhyLm9wZW4oXCJHRVRcIiwgc2NvcGUuc2V0dGluZ3MudXJsLCB0cnVlKTtcbiAgICAgICAgICAgIHhoci5zZW5kKCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgbXVsdGlzZWxlY3Q6IHRydWUsXG4gICAgICAgICAgICBvcHRpb25zOiB0cnVlLFxuICAgICAgICAgICAgZWxlbWVudDogbnVsbCxcbiAgICAgICAgICAgIHF1ZXJ5OiB7XG4gICAgICAgICAgICAgICAgb3JkZXI6ICdhc2MnLFxuICAgICAgICAgICAgICAgIG9yZGVyQnk6ICduYW1lJyxcbiAgICAgICAgICAgICAgICBrZXl3b3JkOiAnJyxcbiAgICAgICAgICAgICAgICBkaXNwbGF5OiAnbGlzdCdcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICByZXF1ZXN0RGF0YTogZGVmYXVsdFJlcXVlc3QsXG4gICAgICAgICAgICB1cmw6IG13LnNldHRpbmdzLnNpdGVfdXJsICsgJ2FkbWluL2ZpbGUtbWFuYWdlci9saXN0J1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBfZSA9IHt9O1xuICAgICAgICB2YXIgX3ZpZXdUeXBlID0gJ2xpc3QnO1xuXG4gICAgICAgIHRoaXMub24gPSBmdW5jdGlvbiAoZSwgZikgeyBfZVtlXSA/IF9lW2VdLnB1c2goZikgOiAoX2VbZV0gPSBbZl0pIH07XG4gICAgICAgIHRoaXMuZGlzcGF0Y2ggPSBmdW5jdGlvbiAoZSwgZikgeyBfZVtlXSA/IF9lW2VdLmZvckVhY2goZnVuY3Rpb24gKGMpeyBjLmNhbGwodGhpcywgZik7IH0pIDogJyc7IH07XG5cbiAgICAgICAgdGhpcy5zZXR0aW5ncyA9IG13Lm9iamVjdC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcblxuICAgICAgICB2YXIgdGFibGUsIHRhYmxlSGVhZGVyLCB0YWJsZUJvZHk7XG5cblxuXG4gICAgICAgIHZhciBfY2hlY2sgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gbXcuZWxlbWVudCgnPGxhYmVsIGNsYXNzPVwibXctdWktY2hlY2tcIj4nICtcbiAgICAgICAgICAgICAgICAnPGlucHV0IHR5cGU9XCJjaGVja2JveFwiPjxzcGFuPjwvc3Bhbj4nICtcbiAgICAgICAgICAgICAgICAnPC9sYWJlbD4nKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgX3NpemUgPSBmdW5jdGlvbiAoaXRlbSwgZGMpIHtcbiAgICAgICAgICAgIHZhciBieXRlcyA9IGl0ZW0uc2l6ZTtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYnl0ZXMgPT09ICd1bmRlZmluZWQnIHx8IGJ5dGVzID09PSBudWxsKSByZXR1cm4gJyc7XG4gICAgICAgICAgICBpZiAoYnl0ZXMgPT09IDApIHJldHVybiAnMCBCeXRlcyc7XG4gICAgICAgICAgICB2YXIgayA9IDEwMDAsXG4gICAgICAgICAgICAgICAgZG0gPSBkYyA9PT0gdW5kZWZpbmVkID8gMiA6IGRjLFxuICAgICAgICAgICAgICAgIHNpemVzID0gWydCeXRlcycsICdLQicsICdNQicsICdHQicsICdUQicsICdQQicsICdFQicsICdaQicsICdZQiddLFxuICAgICAgICAgICAgICAgIGkgPSBNYXRoLmZsb29yKE1hdGgubG9nKGJ5dGVzKSAvIE1hdGgubG9nKGspKTtcbiAgICAgICAgICAgIHJldHVybiBwYXJzZUZsb2F0KChieXRlcyAvIE1hdGgucG93KGssIGkpKS50b0ZpeGVkKGRtKSkgKyAnICcgKyBzaXplc1tpXTtcbiAgICAgICAgfTtcblxuXG5cbiAgICAgICAgdmFyIF9pbWFnZSA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICBpZiAoaXRlbS50eXBlID09PSAnZm9sZGVyJykge1xuICAgICAgICAgICAgICAgIHJldHVybiAnPHNwYW4gY2xhc3M9XCJtdy1maWxlLW1hbmFnZXItbGlzdC1pdGVtLXRodW1iIG13LWZpbGUtbWFuYWdlci1saXN0LWl0ZW0tdGh1bWItZm9sZGVyXCI+PC9zcGFuPic7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKGl0ZW0udGh1bWJuYWlsKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuICc8c3BhbiBjbGFzcz1cIm13LWZpbGUtbWFuYWdlci1saXN0LWl0ZW0tdGh1bWIgbXctZmlsZS1tYW5hZ2VyLWxpc3QtaXRlbS10aHVtYi1pbWFnZVwiIHN0eWxlPVwiYmFja2dyb3VuZC1pbWFnZTogdXJsKCcgKyBpdGVtLnRodW1ibmFpbCArICcpXCI+PC9zcGFuPic7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHZhciBleHQgPSBpdGVtLm5hbWUuc3BsaXQoJy4nKS5wb3AoKTtcbiAgICAgICAgICAgICAgICBpZighZXh0KSB7XG4gICAgICAgICAgICAgICAgICAgIGV4dCA9IGl0ZW0ubWltZVR5cGU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiAnPHNwYW4gY2xhc3M9XCJtdy1maWxlLW1hbmFnZXItbGlzdC1pdGVtLXRodW1iIG13LWZpbGUtbWFuYWdlci1saXN0LWl0ZW0tdGh1bWItZmlsZVwiPicgKyAoZXh0KSArICc8L3NwYW4+JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgY3JlYXRlT3B0aW9uID0gZnVuY3Rpb24gKGl0ZW0sIG9wdGlvbikge1xuICAgICAgICAgICAgaWYoIW9wdGlvbi5tYXRjaChpdGVtKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiAnJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciBlbCA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIGNvbnRlbnQ6IG9wdGlvbi5sYWJlbFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBlbC5vbignY2xpY2snLCBmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBvcHRpb24uYWN0aW9uKGl0ZW0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH07XG5cblxuICAgICAgICB2YXIgY3JlYXRlT3B0aW9ucyA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICB2YXIgb3B0aW9ucyA9IFtcbiAgICAgICAgICAgICAgICB7IGxhYmVsOiAnUmVuYW1lJywgYWN0aW9uOiBmdW5jdGlvbiAoaXRlbSkge30sIG1hdGNoOiBmdW5jdGlvbiAoaXRlbSkgeyByZXR1cm4gdHJ1ZSB9IH0sXG4gICAgICAgICAgICAgICAgeyBsYWJlbDogJ0Rvd25sb2FkJywgYWN0aW9uOiBmdW5jdGlvbiAoaXRlbSkge30sIG1hdGNoOiBmdW5jdGlvbiAoaXRlbSkgeyByZXR1cm4gaXRlbS50eXBlID09PSAnZmlsZSc7IH0gfSxcbiAgICAgICAgICAgICAgICB7IGxhYmVsOiAnQ29weSB1cmwnLCBhY3Rpb246IGZ1bmN0aW9uIChpdGVtKSB7fSwgbWF0Y2g6IGZ1bmN0aW9uIChpdGVtKSB7IHJldHVybiB0cnVlIH0gfSxcbiAgICAgICAgICAgICAgICB7IGxhYmVsOiAnRGVsZXRlJywgYWN0aW9uOiBmdW5jdGlvbiAoaXRlbSkge30sIG1hdGNoOiBmdW5jdGlvbiAoaXRlbSkgeyByZXR1cm4gdHJ1ZSB9IH0sXG4gICAgICAgICAgICBdO1xuICAgICAgICAgICAgdmFyIGVsID0gbXcuZWxlbWVudCgpLmFkZENsYXNzKCdtdy1maWxlLW1hbmFnZXItbGlzdC1pdGVtLW9wdGlvbnMnKTtcbiAgICAgICAgICAgIGVsLmFwcGVuZChtdy5lbGVtZW50KHt0YWc6ICdzcGFuJywgY29udGVudDogJy4uLicsIHByb3BzOiB7dG9vbHRpcDonb3B0aW9ucyd9fSkuYWRkQ2xhc3MoJ213LWZpbGUtbWFuYWdlci1saXN0LWl0ZW0tb3B0aW9ucy1idXR0b24nKSk7XG4gICAgICAgICAgICB2YXIgb3B0c0hvbGRlciA9IG13LmVsZW1lbnQoKS5hZGRDbGFzcygnbXctZmlsZS1tYW5hZ2VyLWxpc3QtaXRlbS1vcHRpb25zLWxpc3QnKTtcbiAgICAgICAgICAgIGVsLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIHZhciBhbGwgPSBzY29wZS5yb290LmdldCgwKS5xdWVyeVNlbGVjdG9yQWxsKCcubXctZmlsZS1tYW5hZ2VyLWxpc3QtaXRlbS1vcHRpb25zLmFjdGl2ZScpO1xuICAgICAgICAgICAgICAgIGZvcih2YXIgaSA9IDA7IGkgPCBhbGwubGVuZ3RoOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChhbGxbaV0gIT09IHRoaXMpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGFsbFtpXS5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsLnRvZ2dsZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgb3B0aW9ucy5mb3JFYWNoKGZ1bmN0aW9uIChvcHRpb25zKXtcbiAgICAgICAgICAgICAgICBvcHRzSG9sZGVyLmFwcGVuZChjcmVhdGVPcHRpb24oaXRlbSwgb3B0aW9ucykpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBpZighdGhpcy5fX2JvZHlPcHRpb25zQ2xpY2spIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9fYm9keU9wdGlvbnNDbGljayA9IHRydWU7XG4gICAgICAgICAgICAgICAgdmFyIGJjaCA9IGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBjdXJyID0gZS50YXJnZXQ7XG4gICAgICAgICAgICAgICAgICAgIHZhciBjbGlja3NPcHRpb24gPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IGRvY3VtZW50LmJvZHkpIHtcbiAgICAgICAgICAgICAgICAgICAgICBpZihjdXJyLmNsYXNzTGlzdC5jb250YWlucygnbXctZmlsZS1tYW5hZ2VyLWxpc3QtaXRlbS1vcHRpb25zJykpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICBjbGlja3NPcHRpb24gPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgIGlmKCFjbGlja3NPcHRpb24pIHtcbiAgICAgICAgICAgICAgICAgICAgICB2YXIgYWxsID0gc2NvcGUucm9vdC5nZXQoMCkucXVlcnlTZWxlY3RvckFsbCgnLm13LWZpbGUtbWFuYWdlci1saXN0LWl0ZW0tb3B0aW9ucy5hY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICBmb3IodmFyIGkgPSAwOyBpIDwgYWxsLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoYWxsW2ldICE9PSB0aGlzKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICBhbGxbaV0uY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJylcbiAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYWRkRXZlbnRMaXN0ZW5lcignbW91c2Vkb3duJywgYmNoICwgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWwuYXBwZW5kKG9wdHNIb2xkZXIpO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9O1xuXG5cbiAgICAgICAgdmFyIHNldERhdGEgPSBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgc2NvcGUuX2RhdGEgPSBkYXRhO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudXBkYXRlRGF0YSA9IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICBzZXREYXRhKGRhdGEpO1xuICAgICAgICAgICAgdGhpcy5kaXNwYXRjaCgnZGF0YVVwZGF0ZWQnLCBkYXRhKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmdldERhdGEgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fZGF0YTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJlcXVlc3REYXRhID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHBhcmFtcyA9IHt9O1xuICAgICAgICAgICAgdmFyIGNiID0gZnVuY3Rpb24gKHJlcykge1xuICAgICAgICAgICAgICAgIHNjb3BlLnVwZGF0ZURhdGEocmVzKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBlcnIgPSBmdW5jdGlvbiAoZXIpIHtcblxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncy5yZXF1ZXN0RGF0YShcbiAgICAgICAgICAgICAgICBwYXJhbXMsIGNiLCBlcnJcbiAgICAgICAgICAgICk7XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLnJlbmRlciA9IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICB9O1xuXG4gICAgICAgIHZhciB1c2VyRGF0ZSA9IGZ1bmN0aW9uIChkYXRlKSB7XG4gICAgICAgICAgICB2YXIgZHQgPSBuZXcgRGF0ZShkYXRlKTtcbiAgICAgICAgICAgIHJldHVybiBkdC50b0xvY2FsZVN0cmluZygpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZmluZCA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIGl0ZW0gPT09ICdudW1iZXInKSB7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNlbGVjdCA9IGZ1bmN0aW9uIChpdGVtKSB7XG5cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnNpbmdsZUxpc3RWaWV3ID0gZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgIHZhciByb3cgPSBtdy5lbGVtZW50KHsgdGFnOiAndHInIH0pO1xuICAgICAgICAgICAgdmFyIGNlbGxJbWFnZSA9IG13LmVsZW1lbnQoeyB0YWc6ICd0ZCcsIGNvbnRlbnQ6IF9pbWFnZShpdGVtKSAgfSk7XG4gICAgICAgICAgICB2YXIgY2VsbE5hbWUgPSBtdy5lbGVtZW50KHsgdGFnOiAndGQnLCBjb250ZW50OiBpdGVtLm5hbWUgIH0pO1xuICAgICAgICAgICAgdmFyIGNlbGxTaXplID0gbXcuZWxlbWVudCh7IHRhZzogJ3RkJywgY29udGVudDogX3NpemUoaXRlbSkgfSk7XG5cbiAgICAgICAgICAgIHZhciBjZWxsbW9kaWZpZWQgPSBtdy5lbGVtZW50KHsgdGFnOiAndGQnLCBjb250ZW50OiB1c2VyRGF0ZShpdGVtLm1vZGlmaWVkKSAgfSk7XG5cbiAgICAgICAgICAgIGlmKHRoaXMuc2V0dGluZ3MubXVsdGlzZWxlY3QpIHtcbiAgICAgICAgICAgICAgICB2YXIgY2hlY2sgPSAgX2NoZWNrKCk7XG4gICAgICAgICAgICAgICAgY2hlY2sub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgcm93LmFwcGVuZCggbXcuZWxlbWVudCh7IHRhZzogJ3RkJywgY29udGVudDogY2hlY2sgfSkpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgcm93XG4gICAgICAgICAgICAgICAgLmFwcGVuZChjZWxsSW1hZ2UpXG4gICAgICAgICAgICAgICAgLmFwcGVuZChjZWxsTmFtZSlcbiAgICAgICAgICAgICAgICAuYXBwZW5kKGNlbGxTaXplKVxuICAgICAgICAgICAgICAgIC5hcHBlbmQoY2VsbG1vZGlmaWVkKTtcbiAgICAgICAgICAgIGlmKHRoaXMuc2V0dGluZ3Mub3B0aW9ucykge1xuICAgICAgICAgICAgICAgIHZhciBjZWxsT3B0aW9ucyA9IG13LmVsZW1lbnQoeyB0YWc6ICd0ZCcsIGNvbnRlbnQ6IGNyZWF0ZU9wdGlvbnMoaXRlbSkgfSk7XG4gICAgICAgICAgICAgICAgcm93LmFwcGVuZChjZWxsT3B0aW9ucyk7XG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgcmV0dXJuIHJvdztcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgcm93cyA9IFtdO1xuXG4gICAgICAgIHZhciBsaXN0Vmlld0JvZHkgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByb3dzID0gW107XG4gICAgICAgICAgICB0YWJsZUJvZHkgPyB0YWJsZUJvZHkucmVtb3ZlKCkgOiAnJztcbiAgICAgICAgICAgIHRhYmxlQm9keSA9ICBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICB0YWc6ICd0Ym9keSdcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgc2NvcGUuX2RhdGEuZGF0YS5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgdmFyIHJvdyA9IHNjb3BlLnNpbmdsZUxpc3RWaWV3KGl0ZW0pO1xuICAgICAgICAgICAgICAgIHJvd3MucHVzaCh7ZGF0YTogaXRlbSwgcm93OiByb3d9KTtcbiAgICAgICAgICAgICAgICB0YWJsZUJvZHkuYXBwZW5kKHJvdyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiB0YWJsZUJvZHk7XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLl9zZWxlY3RlZCA9IFtdO1xuXG4gICAgICAgIHZhciBwdXNoVW5pcXVlID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgICAgICAgaWYgKHNjb3BlLl9zZWxlY3RlZC5pbmRleE9mKG9iaikgPT09IC0xKSB7XG4gICAgICAgICAgICAgICAgc2NvcGUuX3NlbGVjdGVkLnB1c2gob2JqKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdmFyIGFmdGVyU2VsZWN0ID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgICAgICAgcm93cy5mb3JFYWNoKGZ1bmN0aW9uIChyb3dJdGVtKXtcbiAgICAgICAgICAgICAgICBpZihyb3dJdGVtLmRhdGEgPT09IG9iaikge1xuICAgICAgICAgICAgICAgICAgICByb3dJdGVtLnJvdy5maW5kKCdpbnB1dCcpLnByb3AoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuXG4gICAgICAgIHRoaXMuc2VsZWN0QWxsID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcm93cy5mb3JFYWNoKGZ1bmN0aW9uIChyb3dJdGVtKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zZWxlY3Qocm93SXRlbS5kYXRhKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2VsZWN0ID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgICAgICAgaWYgKHRoaXMuc2V0dGluZ3MubXVsdGlzZWxlY3QpIHtcbiAgICAgICAgICAgICAgICBwdXNoVW5pcXVlKG9iaik7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuX3NlbGVjdGVkID0gW29ial07XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBhZnRlclNlbGVjdChvYmopO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBjcmVhdGVMaXN0Vmlld0hlYWRlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciB0aENoZWNrO1xuICAgICAgICAgICAgaWYgKHNjb3BlLnNldHRpbmdzLm11bHRpc2VsZWN0KSB7XG4gICAgICAgICAgICAgICAgdmFyIGdsb2JhbGNoZWNrID0gX2NoZWNrKCk7XG4gICAgICAgICAgICAgICAgZ2xvYmFsY2hlY2sub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5zZWxlY3RBbGwoKVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHRoQ2hlY2sgPSBtdy5lbGVtZW50KHsgdGFnOiAndGgnLCBjb250ZW50OiBnbG9iYWxjaGVjayAgfSkuYWRkQ2xhc3MoJ213LWZpbGUtbWFuYWdlci1zZWxlY3QtYWxsLWhlYWRpbmcnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0aEltYWdlID0gbXcuZWxlbWVudCh7IHRhZzogJ3RoJywgY29udGVudDogJycgIH0pO1xuICAgICAgICAgICAgdmFyIHRoTmFtZSA9IG13LmVsZW1lbnQoeyB0YWc6ICd0aCcsIGNvbnRlbnQ6ICc8c3Bhbj5OYW1lPC9zcGFuPicgIH0pLmFkZENsYXNzKCdtdy1maWxlLW1hbmFnZXItc29ydGFibGUtdGFibGUtaGVhZGVyJyk7XG4gICAgICAgICAgICB2YXIgdGhTaXplID0gbXcuZWxlbWVudCh7IHRhZzogJ3RoJywgY29udGVudDogJzxzcGFuPlNpemU8L3NwYW4+JyAgfSkuYWRkQ2xhc3MoJ213LWZpbGUtbWFuYWdlci1zb3J0YWJsZS10YWJsZS1oZWFkZXInKTtcbiAgICAgICAgICAgIHZhciB0aE1vZGlmaWVkID0gbXcuZWxlbWVudCh7IHRhZzogJ3RoJywgY29udGVudDogJzxzcGFuPkxhc3QgbW9kaWZpZWQ8L3NwYW4+JyAgfSkuYWRkQ2xhc3MoJ213LWZpbGUtbWFuYWdlci1zb3J0YWJsZS10YWJsZS1oZWFkZXInKTtcbiAgICAgICAgICAgIHZhciB0aE9wdGlvbnMgPSBtdy5lbGVtZW50KHsgdGFnOiAndGgnLCBjb250ZW50OiAnJyAgfSk7XG4gICAgICAgICAgICB2YXIgdHIgPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICB0YWc6ICd0cicsXG4gICAgICAgICAgICAgICAgY29udGVudDogW3RoQ2hlY2ssIHRoSW1hZ2UsIHRoTmFtZSwgdGhTaXplLCB0aE1vZGlmaWVkLCB0aE9wdGlvbnNdXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRhYmxlSGVhZGVyID0gIG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIHRhZzogJ3RoZWFkJyxcbiAgICAgICAgICAgICAgICBjb250ZW50OiB0clxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gdGFibGVIZWFkZXI7XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGxpc3RWaWV3ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGFibGUgPSAgbXcuZWxlbWVudCgnPHRhYmxlIGNsYXNzPVwibXctZmlsZS1tYW5hZ2VyLWxpc3R2aWV3LXRhYmxlXCIgLz4nKTtcbiAgICAgICAgICAgIHRhYmxlXG4gICAgICAgICAgICAgICAgLmFwcGVuZChjcmVhdGVMaXN0Vmlld0hlYWRlcigpKVxuICAgICAgICAgICAgICAgIC5hcHBlbmQobGlzdFZpZXdCb2R5KCkpO1xuICAgICAgICAgICAgcmV0dXJuIHRhYmxlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBncmlkVmlldyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBncmlkID0gIG13LmVsZW1lbnQoJzxkaXYgLz4nKTtcblxuICAgICAgICAgICAgcmV0dXJuIGdyaWQ7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy52aWV3ID0gZnVuY3Rpb24gKHR5cGUpIHtcbiAgICAgICAgICAgIGlmKCF0eXBlKSByZXR1cm4gX3ZpZXdUeXBlO1xuICAgICAgICAgICAgX3ZpZXdUeXBlID0gdHlwZTtcbiAgICAgICAgICAgIHZhciB2aWV3YmxvY2s7XG4gICAgICAgICAgICBpZiAoX3ZpZXdUeXBlID09PSAnbGlzdCcpIHtcbiAgICAgICAgICAgICAgICB2aWV3YmxvY2sgPSBsaXN0VmlldygpO1xuICAgICAgICAgICAgfSBlbHNlIGlmIChfdmlld1R5cGUgPT09ICdncmlkJykge1xuICAgICAgICAgICAgICAgIHZpZXdibG9jayA9IGdyaWRWaWV3KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZih2aWV3YmxvY2spIHtcbiAgICAgICAgICAgICAgICB0aGlzLnJvb3QuZW1wdHkoKS5hcHBlbmQodmlld2Jsb2NrKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMucm9vdC5kYXRhc2V0KCd2aWV3JywgX3ZpZXdUeXBlKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJlZnJlc2ggPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAoX3ZpZXdUeXBlID09PSAnbGlzdCcpIHtcbiAgICAgICAgICAgICAgICBsaXN0Vmlld0JvZHkoKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoX3ZpZXdUeXBlID09PSAnZ3JpZCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmxpc3RWaWV3KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIGNyZWF0ZVJvb3QgPSBmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgIHNjb3BlLnJvb3QgPSBtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy1maWxlLW1hbmFnZXItcm9vdCdcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGVuY2Fwc3VsYXRlOiBmYWxzZVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmluaXQgPSBmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgIGNyZWF0ZVJvb3QoKTtcbiAgICAgICAgICAgIHRoaXMub24oJ2RhdGFVcGRhdGVkJywgZnVuY3Rpb24gKHJlcyl7XG4gICAgICAgICAgICAgICAgc2NvcGUudmlldyhfdmlld1R5cGUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnJlcXVlc3REYXRhKCk7XG4gICAgICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5lbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgbXcuZWxlbWVudCh0aGlzLnNldHRpbmdzLmVsZW1lbnQpLmVtcHR5KCkuYXBwZW5kKHRoaXMucm9vdCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5pbml0KCk7XG4gICAgfTtcblxuICAgIG13LkZpbGVNYW5hZ2VyID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICAgICAgcmV0dXJuIG5ldyBGaWxlTWFuYWdlcihvcHRpb25zKTtcbiAgICB9O1xufSkoKTtcbiIsIlxubXcucmVxdWlyZSgndXBsb2FkZXIuanMnKTtcblxuXG5tdy5maWxlUGlja2VyID0gZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcbiAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgIHZhciAkc2NvcGUgPSAkKHRoaXMpO1xuICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgY29tcG9uZW50czogW1xuICAgICAgICAgICAge3R5cGU6ICdkZXNrdG9wJywgbGFiZWw6IG13LmxhbmcoJ015IGNvbXB1dGVyJyl9LFxuICAgICAgICAgICAge3R5cGU6ICd1cmwnLCBsYWJlbDogbXcubGFuZygnVVJMJyl9LFxuICAgICAgICAgICAge3R5cGU6ICdzZXJ2ZXInLCBsYWJlbDogbXcubGFuZygnVXBsb2FkZWQnKX0sXG4gICAgICAgICAgICB7dHlwZTogJ2xpYnJhcnknLCBsYWJlbDogbXcubGFuZygnTWVkaWEgbGlicmFyeScpfVxuICAgICAgICBdLFxuICAgICAgICBuYXY6ICd0YWJzJywgLy8gJ3RhYnMgfCAnZHJvcGRvd24nLFxuICAgICAgICBoaWRlSGVhZGVyOiBmYWxzZSxcbiAgICAgICAgZHJvcERvd25UYXJnZXRNb2RlOiAnc2VsZicsIC8vICdzZWxmJywgJ2RpYWxvZydcbiAgICAgICAgZWxlbWVudDogbnVsbCxcbiAgICAgICAgZm9vdGVyOiB0cnVlLFxuICAgICAgICBva0xhYmVsOiBtdy5sYW5nKCdPSycpLFxuICAgICAgICBjYW5jZWxMYWJlbDogbXcubGFuZygnQ2FuY2VsJyksXG4gICAgICAgIHVwbG9hZGVyVHlwZTogJ2JpZycsIC8vICdiaWcnIHwgJ3NtYWxsJ1xuICAgICAgICBjb25maXJtOiBmdW5jdGlvbiAoZGF0YSkge1xuXG4gICAgICAgIH0sXG4gICAgICAgIGNhbmNlbDogZnVuY3Rpb24gKCkge1xuXG4gICAgICAgIH0sXG4gICAgICAgIGxhYmVsOiBtdy5sYW5nKCdNZWRpYScpLFxuICAgICAgICBhdXRvU2VsZWN0OiB0cnVlLCAvLyBkZXBlbmRpbmcgb24gdGhlIGNvbXBvbmVudFxuICAgICAgICBib3hlZDogZmFsc2UsXG4gICAgICAgIG11bHRpcGxlOiBmYWxzZVxuICAgIH07XG5cblxuXG4gICAgdGhpcy5zZXR0aW5ncyA9ICQuZXh0ZW5kKHRydWUsIHt9LCBkZWZhdWx0cywgb3B0aW9ucyk7XG5cbiAgICB0aGlzLiRyb290ID0gJCgnPGRpdiBjbGFzcz1cIicrICh0aGlzLnNldHRpbmdzLmJveGVkID8gKCdjYXJkIG1iLTMnKSA6ICcnKSArJyBtdy1maWxlcGlja2VyLXJvb3RcIj48L2Rpdj4nKTtcbiAgICB0aGlzLnJvb3QgPSB0aGlzLiRyb290WzBdO1xuXG4gICAgJC5lYWNoKHRoaXMuc2V0dGluZ3MuY29tcG9uZW50cywgZnVuY3Rpb24gKGkpIHtcbiAgICAgICAgdGhpc1snaW5kZXgnXSA9IGk7XG4gICAgfSk7XG5cblxuICAgIHRoaXMuY29tcG9uZW50cyA9IHtcbiAgICAgICAgXyRpbnB1dFdyYXBwZXI6IGZ1bmN0aW9uIChsYWJlbCkge1xuICAgICAgICAgICAgdmFyIGh0bWwgPSAnPGRpdiBjbGFzcz1cIm13LXVpLWZpZWxkLWhvbGRlclwiPicgK1xuICAgICAgICAgICAgICAgIC8qJzxsYWJlbD4nICsgbGFiZWwgKyAnPC9sYWJlbD4nICsqL1xuICAgICAgICAgICAgICAgICc8L2Rpdj4nO1xuICAgICAgICAgICAgcmV0dXJuIG13LiQoaHRtbCk7XG4gICAgICAgIH0sXG4gICAgICAgIHVybDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyICRpbnB1dCA9ICQoJzxpbnB1dCBjbGFzcz1cIm13LXVpLWZpZWxkIHcxMDBcIiBwbGFjZWhvbGRlcj1cImh0dHA6Ly9leGFtcGxlLmNvbS9pbWFnZS5qcGdcIj4nKTtcbiAgICAgICAgICAgIHNjb3BlLiR1cmxJbnB1dCA9ICRpbnB1dDtcbiAgICAgICAgICAgIHZhciAkd3JhcCA9IHRoaXMuXyRpbnB1dFdyYXBwZXIoc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCgndXJsJykubGFiZWwpO1xuICAgICAgICAgICAgJHdyYXAuYXBwZW5kKCRpbnB1dCk7XG4gICAgICAgICAgICAkaW5wdXQuYmVmb3JlKCc8bGFiZWwgY2xhc3M9XCJtdy11aS1sYWJlbFwiPkluc2VydCBmaWxlIHVybDwvbGFiZWw+Jyk7XG4gICAgICAgICAgICAkaW5wdXQub24oJ2lucHV0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSB0aGlzLnZhbHVlLnRyaW0oKTtcbiAgICAgICAgICAgICAgICBzY29wZS5zZXRTZWN0aW9uVmFsdWUodmFsIHx8IG51bGwpO1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmF1dG9TZWxlY3QpIHtcblxuICAgICAgICAgICAgICAgICAgICBzY29wZS5yZXN1bHQoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiAkd3JhcFswXTtcbiAgICAgICAgfSxcbiAgICAgICAgX3NldGRlc2t0b3BUeXBlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgJHpvbmU7XG4gICAgICAgICAgICBpZihzY29wZS5zZXR0aW5ncy51cGxvYWRlclR5cGUgPT09ICdiaWcnKSB7XG4gICAgICAgICAgICAgICAgJHpvbmUgPSAkKCc8ZGl2IGNsYXNzPVwibXctZmlsZS1kcm9wLXpvbmVcIj4nICtcbiAgICAgICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJtdy1maWxlLWRyb3Atem9uZS1ob2xkZXJcIj4nICtcbiAgICAgICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJtdy1maWxlLWRyb3Atem9uZS1pbWdcIj48L2Rpdj4nICtcbiAgICAgICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJtdy11aS1wcm9ncmVzcy1zbWFsbFwiPjxkaXYgY2xhc3M9XCJtdy11aS1wcm9ncmVzcy1iYXJcIiBzdHlsZT1cIndpZHRoOiAwJVwiPjwvZGl2PjwvZGl2PicgK1xuICAgICAgICAgICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJtdy11aS1idG4gbXctdWktYnRuLXJvdW5kZWQgbXctdWktYnRuLWluZm9cIj4nK213LmxhbmcoJ0FkZCBmaWxlJykrJzwvc3Bhbj4gJyArXG4gICAgICAgICAgICAgICAgICAgICc8cD4nK213LmxhbmcoJ29yIGRyb3AgZmlsZXMgdG8gdXBsb2FkJykrJzwvcD4nICtcbiAgICAgICAgICAgICAgICAgICAgJzwvZGl2PicgK1xuICAgICAgICAgICAgICAgICAgICAnPC9kaXY+Jyk7XG4gICAgICAgICAgICB9IGVsc2UgaWYoc2NvcGUuc2V0dGluZ3MudXBsb2FkZXJUeXBlID09PSAnc21hbGwnKSB7XG4gICAgICAgICAgICAgICAgJHpvbmUgPSAkKCc8ZGl2IGNsYXNzPVwibXctZmlsZS1kcm9wLXpvbmUgbXctZmlsZS1kcm9wLXpvbmUtc21hbGwgbXctZmlsZS1kcm9wLXNxdWFyZS16b25lXCI+IDxkaXYgY2xhc3M9XCJtdy1maWxlLWRyb3Atem9uZS1ob2xkZXJcIj4gPHNwYW4gY2xhc3M9XCJtdy11aS1saW5rXCI+QWRkIGZpbGU8L3NwYW4+IDxwPm9yIGRyb3AgZmlsZSB0byB1cGxvYWQ8L3A+IDwvZGl2PiA8L2Rpdj4nKVxuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIHZhciAkZWwgPSAkKHNjb3BlLnNldHRpbmdzLmVsZW1lbnQpLmVxKDApO1xuICAgICAgICAgICAgJGVsLnJlbW92ZUNsYXNzKCdtdy1maWxlcGlja2VyLWRlc2t0b3AtdHlwZS1iaWcgbXctZmlsZXBpY2tlci1kZXNrdG9wLXR5cGUtc21hbGwnKTtcbiAgICAgICAgICAgICRlbC5hZGRDbGFzcygnbXctZmlsZXBpY2tlci1kZXNrdG9wLXR5cGUtJyArIHNjb3BlLnNldHRpbmdzLnVwbG9hZGVyVHlwZSk7XG4gICAgICAgICAgICBzY29wZS51cGxvYWRlckhvbGRlci5lbXB0eSgpLmFwcGVuZCgkem9uZSk7XG4gICAgICAgIH0sXG4gICAgICAgIGRlc2t0b3A6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciAkd3JhcCA9IHRoaXMuXyRpbnB1dFdyYXBwZXIoc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCgnZGVza3RvcCcpLmxhYmVsKTtcbiAgICAgICAgICAgIHNjb3BlLnVwbG9hZGVySG9sZGVyID0gbXcuJCgnPGRpdiBjbGFzcz1cIm13LXVwbG9hZGVyLXR5cGUtaG9sZGVyXCI+PC9kaXY+Jyk7XG4gICAgICAgICAgICB0aGlzLl9zZXRkZXNrdG9wVHlwZSgpO1xuICAgICAgICAgICAgJHdyYXAuYXBwZW5kKHNjb3BlLnVwbG9hZGVySG9sZGVyKTtcbiAgICAgICAgICAgIHNjb3BlLnVwbG9hZGVyID0gbXcudXBsb2FkKHtcbiAgICAgICAgICAgICAgICBlbGVtZW50OiAkd3JhcFswXSxcbiAgICAgICAgICAgICAgICBtdWx0aXBsZTogc2NvcGUuc2V0dGluZ3MubXVsdGlwbGUsXG4gICAgICAgICAgICAgICAgYWNjZXB0OiBzY29wZS5zZXR0aW5ncy5hY2NlcHQsXG4gICAgICAgICAgICAgICAgb246IHtcbiAgICAgICAgICAgICAgICAgICAgcHJvZ3Jlc3M6IGZ1bmN0aW9uIChwcmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnVwbG9hZGVySG9sZGVyLmZpbmQoJy5tdy11aS1wcm9ncmVzcy1iYXInKS5zdG9wKCkuYW5pbWF0ZSh7d2lkdGg6IHByZy5wZXJjZW50ICsgJyUnfSwgJ2Zhc3QnKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZmlsZUFkZGVkOiBmdW5jdGlvbiAoZmlsZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgJChzY29wZSkudHJpZ2dlcignRmlsZUFkZGVkJywgW2ZpbGVdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnVwbG9hZGVySG9sZGVyLmZpbmQoJy5tdy11aS1wcm9ncmVzcy1iYXInKS53aWR0aCgnMSUnKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZmlsZVVwbG9hZGVkOiBmdW5jdGlvbiAoZmlsZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuc2V0U2VjdGlvblZhbHVlKGZpbGUpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCdGaWxlVXBsb2FkZWQnLCBbZmlsZV0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHNjb3BlLnNldHRpbmdzLmF1dG9TZWxlY3QpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5yZXN1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChzY29wZS5zZXR0aW5ncy5maWxlVXBsb2FkZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXR0aW5ncy5maWxlVXBsb2FkZWQoZmlsZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBzY29wZS51cGxvYWRlckhvbGRlci5maW5kKCcubXctZmlsZS1kcm9wLXpvbmUtaW1nJykuY3NzKCdiYWNrZ3JvdW5kSW1hZ2UnLCAndXJsKCcrZmlsZS5zcmMrJyknKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuICR3cmFwWzBdO1xuICAgICAgICB9LFxuICAgICAgICBzZXJ2ZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciAkd3JhcCA9IHRoaXMuXyRpbnB1dFdyYXBwZXIoc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCgnc2VydmVyJykubGFiZWwpO1xuICAgICAgICAgICAgLyptdy5sb2FkX21vZHVsZSgnZmlsZXMvYWRtaW4nLCAkd3JhcCwgZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICB9LCB7J2ZpbGV0eXBlJzonaW1hZ2VzJ30pOyovXG5cbiAgICAgICAgICAgICQoc2NvcGUpLm9uKCckZmlyc3RPcGVuJywgZnVuY3Rpb24gKGUsIGVsLCB0eXBlKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvbXAgPSBzY29wZS5fZ2V0Q29tcG9uZW50T2JqZWN0KCdzZXJ2ZXInKTtcbiAgICAgICAgICAgICAgICBpZiAodHlwZSA9PT0gJ3NlcnZlcicpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMubG9hZGluZyhlbCwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBmciA9IG13LnRvb2xzLm1vZHVsZUZyYW1lKCdmaWxlcy9hZG1pbicsIHsnZmlsZXR5cGUnOidpbWFnZXMnfSk7XG4gICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLl9mcmFtZU1heEhlaWdodCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZnIuc3R5bGUubWF4SGVpZ2h0ID0gJzYwdmgnO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnIuc2Nyb2xsaW5nID0gJ3llcyc7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgJHdyYXAuYXBwZW5kKGZyKTtcbiAgICAgICAgICAgICAgICAgICAgZnIub25sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMubG9hZGluZyhlbCwgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jb250ZW50V2luZG93LiQodGhpcy5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkpLm9uKCdjbGljaycsICcubXctYnJvd3Nlci1saXN0LWZpbGUnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHVybCA9IHRoaXMuaHJlZjtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXRTZWN0aW9uVmFsdWUodXJsKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoc2NvcGUuc2V0dGluZ3MuYXV0b1NlbGVjdCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5yZXN1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgcmV0dXJuICR3cmFwWzBdO1xuICAgICAgICB9LFxuICAgICAgICBsaWJyYXJ5OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgJHdyYXAgPSB0aGlzLl8kaW5wdXRXcmFwcGVyKHNjb3BlLl9nZXRDb21wb25lbnRPYmplY3QoJ2xpYnJhcnknKS5sYWJlbCk7XG4gICAgICAgICAgICAkKHNjb3BlKS5vbignJGZpcnN0T3BlbicsIGZ1bmN0aW9uIChlLCBlbCwgdHlwZSkge1xuICAgICAgICAgICAgICAgIHZhciBjb21wID0gc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCgnbGlicmFyeScpO1xuICAgICAgICAgICAgICAgIGlmICh0eXBlID09PSAnbGlicmFyeScpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMubG9hZGluZyhlbCwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBmciA9IG13LnRvb2xzLm1vZHVsZUZyYW1lKCdwaWN0dXJlcy9tZWRpYV9saWJyYXJ5Jyk7XG4gICAgICAgICAgICAgICAgICAgICR3cmFwLmFwcGVuZChmcik7XG4gICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLl9mcmFtZU1heEhlaWdodCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZnIuc3R5bGUubWF4SGVpZ2h0ID0gJzYwdmgnO1xuICAgICAgICAgICAgICAgICAgICAgICAgZnIuc2Nyb2xsaW5nID0gJ3llcyc7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZnIub25sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMubG9hZGluZyhlbCwgZmFsc2UpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jb250ZW50V2luZG93Lm13Lm9uLmhhc2hQYXJhbSgnc2VsZWN0LWZpbGUnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHVybCA9IHRoaXMudG9TdHJpbmcoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXRTZWN0aW9uVmFsdWUodXJsKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoc2NvcGUuc2V0dGluZ3MuYXV0b1NlbGVjdCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5yZXN1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KVxuXG4gICAgICAgICAgICAvKm13LmxvYWRfbW9kdWxlKCdwaWN0dXJlcy9tZWRpYV9saWJyYXJ5JywgJHdyYXApOyovXG4gICAgICAgICAgICByZXR1cm4gJHdyYXBbMF07XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5oaWRlVXBsb2FkZXJzID0gZnVuY3Rpb24gKHR5cGUpIHtcbiAgICAgICAgbXcuJCgnLm13LWZpbGVwaWNrZXItY29tcG9uZW50LXNlY3Rpb24nLCB0aGlzLiRyb290KS5oaWRlKCk7XG4gICAgfTtcblxuICAgIHRoaXMuc2hvd1VwbG9hZGVycyA9IGZ1bmN0aW9uICh0eXBlKSB7XG4gICAgICAgIG13LiQoJy5tdy1maWxlcGlja2VyLWNvbXBvbmVudC1zZWN0aW9uJywgdGhpcy4kcm9vdCkuc2hvdygpO1xuICAgIH07XG5cbiAgICB0aGlzLmRlc2t0b3BVcGxvYWRlclR5cGUgPSBmdW5jdGlvbiAodHlwZSkge1xuICAgICAgICBpZighdHlwZSkgcmV0dXJuIHRoaXMuc2V0dGluZ3MudXBsb2FkZXJUeXBlO1xuICAgICAgICB0aGlzLnNldHRpbmdzLnVwbG9hZGVyVHlwZSA9IHR5cGU7XG4gICAgICAgIHRoaXMuY29tcG9uZW50cy5fc2V0ZGVza3RvcFR5cGUoKTtcbiAgICB9O1xuXG4gICAgdGhpcy5zZXR0aW5ncy5jb21wb25lbnRzID0gdGhpcy5zZXR0aW5ncy5jb21wb25lbnRzLmZpbHRlcihmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICByZXR1cm4gISFzY29wZS5jb21wb25lbnRzW2l0ZW0udHlwZV07XG4gICAgfSk7XG5cblxuICAgIHRoaXMuX25hdmlnYXRpb24gPSBudWxsO1xuICAgIHRoaXMuX19uYXZpZ2F0aW9uX2ZpcnN0ID0gW107XG5cbiAgICB0aGlzLm5hdmlnYXRpb24gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMuX25hdmlnYXRpb25IZWFkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgdGhpcy5fbmF2aWdhdGlvbkhlYWRlci5jbGFzc05hbWUgPSAnbXctZmlsZXBpY2tlci1jb21wb25lbnQtbmF2aWdhdGlvbi1oZWFkZXIgJyArICh0aGlzLnNldHRpbmdzLmJveGVkID8gJ2NhcmQtaGVhZGVyIG5vLWJvcmRlcicgOiAnJyk7XG4gICAgICAgIGlmICh0aGlzLnNldHRpbmdzLmhpZGVIZWFkZXIpIHtcbiAgICAgICAgICAgIHRoaXMuX25hdmlnYXRpb25IZWFkZXIuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgfVxuICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5sYWJlbCkge1xuICAgICAgICAgICAgdGhpcy5fbmF2aWdhdGlvbkhlYWRlci5pbm5lckhUTUwgPSAnPGg2PjxzdHJvbmc+JyArIHRoaXMuc2V0dGluZ3MubGFiZWwgKyAnPC9zdHJvbmc+PC9oNj4nO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuX25hdmlnYXRpb25Ib2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgaWYodGhpcy5zZXR0aW5ncy5uYXYgPT09IGZhbHNlKSB7XG5cbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmKHRoaXMuc2V0dGluZ3MubmF2ID09PSAndGFicycpIHtcbiAgICAgICAgICAgIHZhciB1bCA9ICQoJzxuYXYgY2xhc3M9XCJtdy1hYy1lZGl0b3ItbmF2XCIgLz4nKTtcbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MuY29tcG9uZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgdWwuYXBwZW5kKCc8YSBocmVmPVwiamF2YXNjcmlwdDo7XCIgY2xhc3M9XCJtdy11aS1idG4tdGFiXCIgZGF0YS10eXBlPVwiJytpdGVtLnR5cGUrJ1wiPicraXRlbS5sYWJlbCsnPC9hPicpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLl9uYXZpZ2F0aW9uSG9sZGVyLmFwcGVuZENoaWxkKHRoaXMuX25hdmlnYXRpb25IZWFkZXIpO1xuICAgICAgICAgICAgdGhpcy5fbmF2aWdhdGlvbkhlYWRlci5hcHBlbmRDaGlsZCh1bFswXSk7XG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBzY29wZS5fdGFicyA9IG13LnRhYnMoe1xuICAgICAgICAgICAgICAgICAgICBuYXY6ICQoJ2EnLCB1bCksXG4gICAgICAgICAgICAgICAgICAgIHRhYnM6ICQoJy5tdy1maWxlcGlja2VyLWNvbXBvbmVudC1zZWN0aW9uJywgc2NvcGUuJHJvb3QpLFxuICAgICAgICAgICAgICAgICAgICBhY3RpdmVDbGFzczogJ2FjdGl2ZScsXG4gICAgICAgICAgICAgICAgICAgIG9uY2xpY2s6IGZ1bmN0aW9uIChlbCwgZXZlbnQsIGkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLl9fbmF2aWdhdGlvbl9maXJzdC5pbmRleE9mKGkpID09PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl9fbmF2aWdhdGlvbl9maXJzdC5wdXNoKGkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoc2NvcGUpLnRyaWdnZXIoJyRmaXJzdE9wZW4nLCBbZWwsIHRoaXMuZGF0YXNldC50eXBlXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5tYW5hZ2VBY3RpdmVTZWN0aW9uU3RhdGUoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSwgNzgpO1xuICAgICAgICB9IGVsc2UgaWYodGhpcy5zZXR0aW5ncy5uYXYgPT09ICdkcm9wZG93bicpIHtcbiAgICAgICAgICAgIHZhciBzZWxlY3QgPSAkKCc8c2VsZWN0IGNsYXNzPVwic2VsZWN0cGlja2VyIGJ0bi1hcy1saW5rXCIgZGF0YS1zdHlsZT1cImJ0bi1zbVwiIGRhdGEtd2lkdGg9XCJhdXRvXCIgZGF0YS10aXRsZT1cIicgKyBtdy5sYW5nKCdBZGQgZmlsZScpICsgJ1wiLz4nKTtcbiAgICAgICAgICAgIHNjb3BlLl9zZWxlY3QgPSBzZWxlY3Q7XG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzLmNvbXBvbmVudHMuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICAgICAgICAgIHNlbGVjdC5hcHBlbmQoJzxvcHRpb24gY2xhc3M9XCJuYXYtaXRlbVwiIHZhbHVlPVwiJytpdGVtLnR5cGUrJ1wiPicraXRlbS5sYWJlbCsnPC9vcHRpb24+Jyk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdGhpcy5fbmF2aWdhdGlvbkhvbGRlci5hcHBlbmRDaGlsZCh0aGlzLl9uYXZpZ2F0aW9uSGVhZGVyKTtcbiAgICAgICAgICAgIHRoaXMuX25hdmlnYXRpb25IZWFkZXIuYXBwZW5kQ2hpbGQoc2VsZWN0WzBdKTtcbiAgICAgICAgICAgIHNlbGVjdC5vbignY2hhbmdlZC5icy5zZWxlY3QnLCBmdW5jdGlvbiAoZSwgeHZhbCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSBzZWxlY3Quc2VsZWN0cGlja2VyKCd2YWwnKTtcbiAgICAgICAgICAgICAgICB2YXIgY29tcG9uZW50T2JqZWN0ID0gc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCh2YWwpIDtcbiAgICAgICAgICAgICAgICB2YXIgaW5kZXggPSBzY29wZS5zZXR0aW5ncy5jb21wb25lbnRzLmluZGV4T2YoY29tcG9uZW50T2JqZWN0KTtcbiAgICAgICAgICAgICAgICB2YXIgaXRlbXMgPSAkKCcubXctZmlsZXBpY2tlci1jb21wb25lbnQtc2VjdGlvbicsIHNjb3BlLiRyb290KTtcbiAgICAgICAgICAgICAgICBpZihzY29wZS5fX25hdmlnYXRpb25fZmlyc3QuaW5kZXhPZih2YWwpID09PSAtMSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5fX25hdmlnYXRpb25fZmlyc3QucHVzaCh2YWwpO1xuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlKS50cmlnZ2VyKCckZmlyc3RPcGVuJywgW2l0ZW1zLmVxKGluZGV4KVswXSwgdmFsXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLmRyb3BEb3duVGFyZ2V0TW9kZSA9PT0gJ2RpYWxvZycpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHRlbXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSBpdGVtcy5lcShpbmRleCk7XG4gICAgICAgICAgICAgICAgICAgIGl0ZW0uYmVmb3JlKHRlbXApO1xuICAgICAgICAgICAgICAgICAgICBpdGVtLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZvb3RlciA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICBpZiAoc2NvcGUuX2dldENvbXBvbmVudE9iamVjdCgndXJsJykuaW5kZXggPT09IGluZGV4ICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9vdGVyID0gIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGZvb3Rlcm9rID0gJCgnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJtdy11aS1idG4gbXctdWktYnRuLWluZm9cIj4nICsgc2NvcGUuc2V0dGluZ3Mub2tMYWJlbCArICc8L2J1dHRvbj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBmb290ZXJjYW5jZWwgPSAkKCc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cIm13LXVpLWJ0blwiPicgKyBzY29wZS5zZXR0aW5ncy5jYW5jZWxMYWJlbCArICc8L2J1dHRvbj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvb3Rlcm9rLmRpc2FibGVkID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvb3Rlci5hcHBlbmRDaGlsZChmb290ZXJjYW5jZWxbMF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9vdGVyLmFwcGVuZENoaWxkKGZvb3Rlcm9rWzBdKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvb3Rlci5hcHBlbmRDaGlsZChmb290ZXJjYW5jZWxbMF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgZm9vdGVyY2FuY2VsLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fX3BpY2tEaWFsb2cucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvb3Rlcm9rLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5zZXRTZWN0aW9uVmFsdWUoc2NvcGUuJHVybElucHV0LnZhbCgpLnRyaW0oKSB8fCBudWxsKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoc2NvcGUuc2V0dGluZ3MuYXV0b1NlbGVjdCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5yZXN1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gc2NvcGUuX19waWNrRGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBzY29wZS5fX3BpY2tEaWFsb2cgPSBtdy5kaWFsb2coe1xuICAgICAgICAgICAgICAgICAgICAgICAgb3ZlcmxheTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6IGl0ZW0sXG4gICAgICAgICAgICAgICAgICAgICAgICBiZWZvcmVSZW1vdmU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKHRlbXApLnJlcGxhY2VXaXRoKGl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl9fcGlja0RpYWxvZyA9IG51bGw7XG4gICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICAgICAgZm9vdGVyOiBmb290ZXJcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgaXRlbXMuaGlkZSgpLmVxKGluZGV4KS5zaG93KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy4kcm9vdC5wcmVwZW5kKHRoaXMuX25hdmlnYXRpb25Ib2xkZXIpO1xuXG4gICAgfTtcbiAgICB0aGlzLl9fZGlzcGxheUNvbnRyb2xsZXJCeVR5cGVUaW1lID0gbnVsbDtcblxuICAgIHRoaXMuZGlzcGxheUNvbnRyb2xsZXJCeVR5cGUgPSBmdW5jdGlvbiAodHlwZSkge1xuICAgICAgICB0eXBlID0gKHR5cGUgfHwgJycpLnRyaW0oKTtcbiAgICAgICAgdmFyIGl0ZW0gPSB0aGlzLl9nZXRDb21wb25lbnRPYmplY3QodHlwZSkgO1xuICAgICAgICBjbGVhclRpbWVvdXQodGhpcy5fX2Rpc3BsYXlDb250cm9sbGVyQnlUeXBlVGltZSk7XG4gICAgICAgIHRoaXMuX19kaXNwbGF5Q29udHJvbGxlckJ5VHlwZVRpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmKHNjb3BlLnNldHRpbmdzLm5hdiA9PT0gJ3RhYnMnKSB7XG4gICAgICAgICAgICAgICAgbXcuJCgnW2RhdGEtdHlwZT1cIicrdHlwZSsnXCJdJywgc2NvcGUuJHJvb3QpLmNsaWNrKCk7XG4gICAgICAgICAgICB9IGVsc2UgaWYoc2NvcGUuc2V0dGluZ3MubmF2ID09PSAnZHJvcGRvd24nKSB7XG4gICAgICAgICAgICAgICAgJChzY29wZS5fc2VsZWN0KS5zZWxlY3RwaWNrZXIoJ3ZhbCcsIHR5cGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LCAxMCk7XG4gICAgfTtcblxuXG5cbiAgICB0aGlzLmZvb3RlciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYoIXRoaXMuc2V0dGluZ3MuZm9vdGVyIHx8IHRoaXMuc2V0dGluZ3MuYXV0b1NlbGVjdCkgcmV0dXJuO1xuICAgICAgICB0aGlzLl9uYXZpZ2F0aW9uRm9vdGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHRoaXMuX25hdmlnYXRpb25Gb290ZXIuY2xhc3NOYW1lID0gJ213LXVpLWZvcm0tY29udHJvbGxlcnMtZm9vdGVyIG13LWZpbGVwaWNrZXItZm9vdGVyICcgKyAodGhpcy5zZXR0aW5ncy5ib3hlZCA/ICdjYXJkLWZvb3RlcicgOiAnJyk7XG4gICAgICAgIHRoaXMuJG9rID0gJCgnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJtdy11aS1idG4gbXctdWktYnRuLWluZm9cIj4nICsgdGhpcy5zZXR0aW5ncy5va0xhYmVsICsgJzwvYnV0dG9uPicpO1xuICAgICAgICB0aGlzLiRjYW5jZWwgPSAkKCc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cIm13LXVpLWJ0biBcIj4nICsgdGhpcy5zZXR0aW5ncy5jYW5jZWxMYWJlbCArICc8L2J1dHRvbj4nKTtcbiAgICAgICAgdGhpcy5fbmF2aWdhdGlvbkZvb3Rlci5hcHBlbmRDaGlsZCh0aGlzLiRjYW5jZWxbMF0pO1xuICAgICAgICB0aGlzLl9uYXZpZ2F0aW9uRm9vdGVyLmFwcGVuZENoaWxkKHRoaXMuJG9rWzBdKTtcbiAgICAgICAgdGhpcy4kcm9vdC5hcHBlbmQodGhpcy5fbmF2aWdhdGlvbkZvb3Rlcik7XG4gICAgICAgIHRoaXMuJG9rWzBdLmRpc2FibGVkID0gdHJ1ZTtcbiAgICAgICAgdGhpcy4kb2sub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2NvcGUucmVzdWx0KCk7XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLiRjYW5jZWwub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2NvcGUuc2V0dGluZ3MuY2FuY2VsKClcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMucmVzdWx0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgYWN0aXZlU2VjdGlvbiA9IHRoaXMuYWN0aXZlU2VjdGlvbigpO1xuICAgICAgICBpZih0aGlzLnNldHRpbmdzLm9uUmVzdWx0KSB7XG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzLm9uUmVzdWx0LmNhbGwodGhpcywgYWN0aXZlU2VjdGlvbi5fZmlsZVBpY2tlclZhbHVlKTtcbiAgICAgICAgfVxuICAgICAgICAgJChzY29wZSkudHJpZ2dlcignUmVzdWx0JywgW2FjdGl2ZVNlY3Rpb24uX2ZpbGVQaWNrZXJWYWx1ZV0pO1xuICAgIH07XG5cbiAgICB0aGlzLmdldFZhbHVlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5hY3RpdmVTZWN0aW9uKCkuX2ZpbGVQaWNrZXJWYWx1ZTtcbiAgICB9O1xuXG4gICAgdGhpcy5fZ2V0Q29tcG9uZW50T2JqZWN0ID0gZnVuY3Rpb24gKHR5cGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuc2V0dGluZ3MuY29tcG9uZW50cy5maW5kKGZ1bmN0aW9uIChjb21wKSB7XG4gICAgICAgICAgICByZXR1cm4gY29tcC50eXBlICYmIGNvbXAudHlwZSA9PT0gdHlwZTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuX3NlY3Rpb25zID0gW107XG4gICAgdGhpcy5idWlsZENvbXBvbmVudFNlY3Rpb24gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBtYWluID0gbXcuJCgnPGRpdiBjbGFzcz1cIicrKHRoaXMuc2V0dGluZ3MuYm94ZWQgPyAnY2FyZC1ib2R5JyA6ICcnKSArJyBtdy1maWxlcGlja2VyLWNvbXBvbmVudC1zZWN0aW9uXCI+PC9kaXY+Jyk7XG4gICAgICAgIHRoaXMuJHJvb3QuYXBwZW5kKG1haW4pO1xuICAgICAgICB0aGlzLl9zZWN0aW9ucy5wdXNoKG1haW5bMF0pO1xuICAgICAgICByZXR1cm4gbWFpbjtcbiAgICB9O1xuXG4gICAgdGhpcy5idWlsZENvbXBvbmVudCA9IGZ1bmN0aW9uIChjb21wb25lbnQpIHtcbiAgICAgICAgaWYodGhpcy5jb21wb25lbnRzW2NvbXBvbmVudC50eXBlXSkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuY29tcG9uZW50c1tjb21wb25lbnQudHlwZV0oKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICB0aGlzLmJ1aWxkQ29tcG9uZW50cyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgJC5lYWNoKHRoaXMuc2V0dGluZ3MuY29tcG9uZW50cywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGNvbXBvbmVudCA9IHNjb3BlLmJ1aWxkQ29tcG9uZW50KHRoaXMpO1xuICAgICAgICAgICAgaWYoY29tcG9uZW50KXtcbiAgICAgICAgICAgICAgICB2YXIgc2VjID0gc2NvcGUuYnVpbGRDb21wb25lbnRTZWN0aW9uKCk7XG4gICAgICAgICAgICAgICAgc2VjLmFwcGVuZChjb21wb25lbnQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgdGhpcy5idWlsZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5uYXZpZ2F0aW9uKCk7XG4gICAgICAgIHRoaXMuYnVpbGRDb21wb25lbnRzKCk7XG4gICAgICAgIGlmKHRoaXMuc2V0dGluZ3MubmF2ID09PSAnZHJvcGRvd24nKSB7XG4gICAgICAgICAgICAkKCcubXctZmlsZXBpY2tlci1jb21wb25lbnQtc2VjdGlvbicsIHNjb3BlLiRyb290KS5oaWRlKCkuZXEoMCkuc2hvdygpO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZm9vdGVyKCk7XG4gICAgfTtcblxuICAgIHRoaXMuaW5pdCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5idWlsZCgpO1xuICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5lbGVtZW50KSB7XG4gICAgICAgICAgICAkKHRoaXMuc2V0dGluZ3MuZWxlbWVudCkuZXEoMCkuYXBwZW5kKHRoaXMuJHJvb3QpO1xuICAgICAgICB9XG4gICAgICAgIGlmKCQuZm4uc2VsZWN0cGlja2VyKSB7XG4gICAgICAgICAgICAkKCdzZWxlY3QnLCBzY29wZS4kcm9vdCkuc2VsZWN0cGlja2VyKCk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5oaWRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLiRyb290LmhpZGUoKTtcbiAgICB9O1xuICAgIHRoaXMuc2hvdyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy4kcm9vdC5zaG93KCk7XG4gICAgfTtcblxuICAgIHRoaXMuYWN0aXZlU2VjdGlvbiA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuICQodGhpcy5fc2VjdGlvbnMpLmZpbHRlcihmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgIHJldHVybiAkKHRoaXMpLmNzcygnZGlzcGxheScpICE9PSAnbm9uZSc7XG4gICAgICAgIH0pWzBdO1xuICAgIH07XG5cbiAgICB0aGlzLnNldFNlY3Rpb25WYWx1ZSA9IGZ1bmN0aW9uICh2YWwpIHtcbiAgICAgICAgIHZhciBhY3RpdmVTZWN0aW9uID0gdGhpcy5hY3RpdmVTZWN0aW9uKCk7XG4gICAgICAgICBpZihhY3RpdmVTZWN0aW9uKSB7XG4gICAgICAgICAgICBhY3RpdmVTZWN0aW9uLl9maWxlUGlja2VyVmFsdWUgPSB2YWw7XG4gICAgICAgIH1cblxuICAgICAgICBpZihzY29wZS5fX3BpY2tEaWFsb2cpIHtcbiAgICAgICAgICAgIHNjb3BlLl9fcGlja0RpYWxvZy5yZW1vdmUoKTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLm1hbmFnZUFjdGl2ZVNlY3Rpb25TdGF0ZSgpO1xuICAgIH07XG4gICAgdGhpcy5tYW5hZ2VBY3RpdmVTZWN0aW9uU3RhdGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vIGlmIHVzZXIgcHJvdmlkZXMgdmFsdWUgZm9yIG1vcmUgdGhhbiBvbmUgc2VjdGlvbiwgdGhlIGFjdGl2ZSB2YWx1ZSB3aWxsIGJlIHRoZSBvbmUgaW4gdGhlIGN1cnJlbnQgc2VjdGlvblxuICAgICAgICB2YXIgYWN0aXZlU2VjdGlvbiA9IHRoaXMuYWN0aXZlU2VjdGlvbigpO1xuICAgICAgICBpZiAodGhpcy4kb2sgJiYgdGhpcy4kb2tbMF0pIHtcbiAgICAgICAgICAgIHRoaXMuJG9rWzBdLmRpc2FibGVkID0gIShhY3RpdmVTZWN0aW9uICYmIGFjdGl2ZVNlY3Rpb24uX2ZpbGVQaWNrZXJWYWx1ZSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5pbml0KCk7XG59O1xuIiwiXG5tdy5yZXF1aXJlKCd3aWRnZXRzLmNzcycpO1xubXcucmVxdWlyZSgnZm9ybS1jb250cm9scy5qcycpO1xuXG5cbihmdW5jdGlvbigpe1xuICAgIHZhciBMaW5rRWRpdG9yID0gZnVuY3Rpb24ob3B0aW9ucykge1xuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBtb2RlOiAnZGlhbG9nJyxcbiAgICAgICAgICAgIGNvbnRyb2xsZXJzOiBbXG4gICAgICAgICAgICAgICAgeyB0eXBlOiAndXJsJ30sXG4gICAgICAgICAgICAgICAgeyB0eXBlOiAncGFnZScgfSxcbiAgICAgICAgICAgICAgICB7IHR5cGU6ICdwb3N0JyB9LFxuICAgICAgICAgICAgICAgIHsgdHlwZTogJ2ZpbGUnIH0sXG4gICAgICAgICAgICAgICAgeyB0eXBlOiAnZW1haWwnIH0sXG4gICAgICAgICAgICAgICAgeyB0eXBlOiAnbGF5b3V0JyB9LFxuICAgICAgICAgICAgICAgIC8qeyB0eXBlOiAndGl0bGUnIH0sKi9cbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICB0aXRsZTogJzxpIGNsYXNzPVwibWRpIG1kaS1saW5rIG13LWxpbmstZWRpdG9yLWljb25cIj48L2k+ICcgKyBtdy5sYW5nKCdMaW5rIFNldHRpbmdzJyksXG4gICAgICAgICAgICBuYXY6ICd0YWJzJ1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX2NvbmZpcm0gPSBbXTtcbiAgICAgICAgdGhpcy5vbkNvbmZpcm0gPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgdGhpcy5fY29uZmlybS5wdXNoKGMpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX2NhbmNlbCA9IFtdO1xuICAgICAgICB0aGlzLm9uQ2FuY2VsID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgIHRoaXMuX2NhbmNlbC5wdXNoKGMpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbiAoZGF0YSwgY29udHJvbGxlcikge1xuICAgICAgICAgICAgY29udHJvbGxlciA9IGNvbnRyb2xsZXIgfHwgJ2F1dG8nO1xuXG4gICAgICAgICAgICBpZihjb250cm9sbGVyID09PSAnYXV0bycpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmNvbnRyb2xsZXJzLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pe1xuICAgICAgICAgICAgICAgICAgICBpdGVtLmNvbnRyb2xsZXIuc2V0VmFsdWUoZGF0YSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuY29udHJvbGxlcnMuZmluZChmdW5jdGlvbiAoaXRlbSl7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBpdGVtLnR5cGUgPT09IGNvbnRyb2xsZXI7XG4gICAgICAgICAgICAgICAgfSkuY29udHJvbGxlci5zZXRWYWx1ZShkYXRhKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLnNldHRpbmdzID0gbXcub2JqZWN0LmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMgfHwge30pO1xuXG4gICAgICAgIHRoaXMuYnVpbGROYXZpZ2F0aW9uID0gZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICBpZih0aGlzLnNldHRpbmdzLm5hdiA9PT0gJ3RhYnMnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5uYXYgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCduYXYnKTtcbiAgICAgICAgICAgICAgICAgdGhpcy5uYXYuY2xhc3NOYW1lID0gJ213LWFjLWVkaXRvci1uYXYnO1xuXG4gICAgICAgICAgICAgICAgdmFyIG5hdiA9IHNjb3BlLmNvbnRyb2xsZXJzLnNsaWNlKDAsIDQpO1xuICAgICAgICAgICAgICAgIHZhciBkcm9wZG93biA9IHNjb3BlLmNvbnRyb2xsZXJzLnNsaWNlKDQpO1xuXG4gICAgICAgICAgICAgICAgdmFyIGhhbmRsZVNlbGVjdCA9IGZ1bmN0aW9uIChfX2ZvciwgdGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgICAgIFtdLmZvckVhY2guY2FsbChzY29wZS5uYXYuY2hpbGRyZW4sIGZ1bmN0aW9uIChpdGVtKXtpdGVtLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO30pO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5jb250cm9sbGVycy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKXtpdGVtLmNvbnRyb2xsZXIucm9vdC5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTt9KTtcbiAgICAgICAgICAgICAgICAgICAgaWYodGFyZ2V0ICYmIHRhcmdldC5jbGFzc0xpc3QpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBfX2Zvci5jb250cm9sbGVyLnJvb3QuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgIGlmKHNjb3BlLmRpYWxvZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuZGlhbG9nLmNlbnRlcigpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgICAgIHZhciBjcmVhdGVBID0gZnVuY3Rpb24gKGN0cmwsIGluZGV4KSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBhID0gIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2EnKTtcbiAgICAgICAgICAgICAgICAgICAgYS5jbGFzc05hbWUgPSAnbXctdWktYnRuLXRhYicgKyAoaW5kZXggPT09IDAgPyAnIGFjdGl2ZScgOiAnJyk7XG4gICAgICAgICAgICAgICAgICAgIGEuaW5uZXJIVE1MID0gKCc8aSBjbGFzcz1cIicrY3RybC5jb250cm9sbGVyLnNldHRpbmdzLmljb24rJ1wiPjwvaT4gJytjdHJsLmNvbnRyb2xsZXIuc2V0dGluZ3MudGl0bGUpO1xuICAgICAgICAgICAgICAgICAgICBhLl9fZm9yID0gY3RybDtcbiAgICAgICAgICAgICAgICAgICAgYS5vbmNsaWNrID0gZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBoYW5kbGVTZWxlY3QodGhpcy5fX2ZvciwgdGhpcyk7XG4gICAgICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBhO1xuICAgICAgICAgICAgICAgIH07XG5cblxuICAgICAgICAgICAgICAgIG5hdi5mb3JFYWNoKGZ1bmN0aW9uIChjdHJsLCBpbmRleCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLm5hdi5hcHBlbmRDaGlsZChjcmVhdGVBKGN0cmwsIGluZGV4KSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgdGhpcy5uYXYuY2hpbGRyZW5bMF0uY2xpY2soKTtcbiAgICAgICAgICAgICAgICB0aGlzLnJvb3QucHJlcGVuZCh0aGlzLm5hdik7XG5cbiAgICAgICAgICAgICAgICBpZihkcm9wZG93bi5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRyb3Bkb3duRWxCdG4gPSAgZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBkcm9wZG93bkVsID0gIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgICAgICAgICAgIGRyb3Bkb3duRWxCdG4uY2xhc3NOYW1lID0gJ213LXVpLWJ0bi10YWIgbXctbGluay1lZGl0b3ItbW9yZS1idXR0b24nO1xuICAgICAgICAgICAgICAgICAgICAgIGRyb3Bkb3duRWwuY2xhc3NOYW1lID0gJ213LWxpbmstZWRpdG9yLW5hdi1kcm9wLWJveCc7XG4gICAgICAgICAgICAgICAgICAgICAgZHJvcGRvd25FbC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuXG4gICAgICAgICAgICAgICAgICAgIGRyb3Bkb3duRWxCdG4uaW5uZXJIVE1MID0gbXcubGFuZygnTW9yZScpICsgJzxpIGNsYXNzPVwibWRpIG1kaS1jaGV2cm9uLWRvd25cIj48L2k+JztcbiAgICAgICAgICAgICAgICAgICAgZHJvcGRvd24uZm9yRWFjaChmdW5jdGlvbiAoY3RybCwgaW5kZXgpe1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5lbGVtZW50KGRyb3Bkb3duRWwpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLmFwcGVuZChtdy5lbGVtZW50KHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGFnOiAnc3BhbicsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHByb3BzOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICcnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgX19mb3I6IGN0cmwsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpbm5lckhUTUw6ICgnPGkgY2xhc3M9XCInK2N0cmwuY29udHJvbGxlci5zZXR0aW5ncy5pY29uKydcIj48L2k+ICcrY3RybC5jb250cm9sbGVyLnNldHRpbmdzLnRpdGxlKSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9uY2xpY2s6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaGFuZGxlU2VsZWN0KHRoaXMuX19mb3IpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LmVsZW1lbnQoZHJvcGRvd25FbCkuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSkpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5uYXYuYXBwZW5kKGRyb3Bkb3duRWwpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm5hdi5hcHBlbmQoZHJvcGRvd25FbEJ0bik7XG4gICAgICAgICAgICAgICAgICAgIGRyb3Bkb3duRWxCdG4ub25jbGljayA9IGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuZWxlbWVudChkcm9wZG93bkVsKS50b2dnbGUoKTtcbiAgICAgICAgICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgICAgICAgICBkcm9wZG93bkVsLm9uY2hhbmdlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaGFuZGxlU2VsZWN0KHRoaXMub3B0aW9uc1t0aGlzLnNlbGVjdGVkSW5kZXhdLl9fZm9yKTtcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICAgICAgLypzZXRUaW1lb3V0KGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoJC5mbi5zZWxlY3RwaWNrZXIpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcuc2VsZWN0cGlja2VyJykuc2VsZWN0cGlja2VyKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0sIDEwMCkqL1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYnVpbGRDb250cm9sbGVycyA9IGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgdGhpcy5jb250cm9sbGVycyA9IFtdO1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncy5jb250cm9sbGVycy5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICAgICAgaWYobXcuVUlGb3JtQ29udHJvbGxlcnNbaXRlbS50eXBlXSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY3RybCA9IG5ldyBtdy5VSUZvcm1Db250cm9sbGVyc1tpdGVtLnR5cGVdKGl0ZW0uY29uZmlnKTtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUucm9vdC5hcHBlbmRDaGlsZChjdHJsLnJvb3QpO1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5jb250cm9sbGVycy5wdXNoKHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHR5cGU6IGl0ZW0udHlwZSxcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRyb2xsZXI6IGN0cmxcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIGN0cmwub25Db25maXJtKGZ1bmN0aW9uIChkYXRhKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLl9jb25maXJtLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGYoZGF0YSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgIGN0cmwub25DYW5jZWwoZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5fY2FuY2VsLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGYoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmJ1aWxkID0gZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICB0aGlzLnJvb3QgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRoaXMucm9vdC5vbmNsaWNrID0gZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICB2YXIgbGUyID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQW55T2ZDbGFzc2VzKGUudGFyZ2V0LCBbJ213LWxpbmstZWRpdG9yLW5hdi1kcm9wLWJveCcsICdtdy1saW5rLWVkaXRvci1tb3JlLWJ1dHRvbiddKTtcbiAgICAgICAgICAgICAgICBpZighbGUyKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmVsZW1lbnQoJy5tdy1saW5rLWVkaXRvci1uYXYtZHJvcC1ib3gnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5yb290LmNsYXNzTmFtZSA9ICdtdy1saW5rLWVkaXRvci1yb290IG13LWxpbmstZWRpdG9yLXJvb3QtaW5JZnJhbWUtJyArICh3aW5kb3cuc2VsZiAhPT0gd2luZG93LnRvcCApXG4gICAgICAgICAgICB0aGlzLmJ1aWxkQ29udHJvbGxlcnMgKCk7XG4gICAgICAgICAgICBpZih0aGlzLnNldHRpbmdzLm1vZGUgPT09ICdkaWFsb2cnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5kaWFsb2cgPSBtdy5kaWFsb2coe1xuICAgICAgICAgICAgICAgICAgICBjb250ZW50OiB0aGlzLnJvb3QsXG4gICAgICAgICAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgICAgICAgICB0aXRsZTogdGhpcy5zZXR0aW5ncy50aXRsZSxcbiAgICAgICAgICAgICAgICAgICAgb3ZlcmZsb3dNb2RlOiAndmlzaWJsZScsXG4gICAgICAgICAgICAgICAgICAgIHNoYWRvdzogZmFsc2VcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB0aGlzLmRpYWxvZy5jZW50ZXIoKTtcbiAgICAgICAgICAgICAgICB0aGlzLm9uQ29uZmlybShmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHRoaXMub25DYW5jZWwoZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmRpYWxvZy5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0gZWxzZSBpZih0aGlzLnNldHRpbmdzLm1vZGUgPT09ICdlbGVtZW50Jykge1xuICAgICAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MuZWxlbWVudC5hcHBlbmQodGhpcy5yb290KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24ob3B0aW9ucykge1xuICAgICAgICAgICAgdGhpcy5idWlsZCgpO1xuICAgICAgICAgICAgdGhpcy5idWlsZE5hdmlnYXRpb24oKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pbml0KCk7XG4gICAgICAgIHRoaXMucHJvbWlzZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbiAocmVzb2x2ZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUub25Db25maXJtKGZ1bmN0aW9uIChkYXRhKXtcbiAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShkYXRhKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBzY29wZS5vbkNhbmNlbChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZSgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG4gICAgfTtcbiAgICBtdy5MaW5rRWRpdG9yID0gTGlua0VkaXRvcjtcblxufSkoKTtcbiIsIm13Lm1vZHVsZVNldHRpbmdzID0gZnVuY3Rpb24ob3B0aW9ucyl7XG4gICAgLypcbiAgICAgICAgb3B0aW9uczpcblxuICAgICAgICAgICAgZGF0YTogW09iamVjdF0sXG4gICAgICAgICAgICBlbGVtZW50OiBOb2RlRWxlbWVudCB8fCBzZWxlY3RvciBzdHJpbmcgfHwgalF1ZXJ5IGFycmF5LFxuICAgICAgICAgICAgc2NoZW1hOiBtdy5wcm9wRWRpdG9yLnNjaGVtYSxcbiAgICAgICAgICAgIGtleTogU3RyaW5nXG4gICAgICAgICAgICBncm91cDogU3RyaW5nLFxuICAgICAgICAgICAgYXV0b1NhdmU6IEJvb2xlYW5cblxuICAgICovXG5cbiAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgIHNvcnRhYmxlOiB0cnVlLFxuICAgICAgICBhdXRvU2F2ZTogdHJ1ZVxuICAgIH07XG5cbiAgICBpZighb3B0aW9ucy5zY2hlbWEgfHwgIW9wdGlvbnMuZGF0YSB8fCAhb3B0aW9ucy5lbGVtZW50KSByZXR1cm47XG5cbiAgICB0aGlzLm9wdGlvbnMgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuXG4gICAgdGhpcy5vcHRpb25zLmVsZW1lbnQgPSBtdy4kKHRoaXMub3B0aW9ucy5lbGVtZW50KVswXTtcbiAgICB0aGlzLnZhbHVlID0gdGhpcy5vcHRpb25zLmRhdGEuc2xpY2UoKTtcblxuICAgIHZhciBzY29wZSA9IHRoaXM7XG5cbiAgICB0aGlzLml0ZW1zID0gW107XG5cbiAgICBpZighdGhpcy5vcHRpb25zLmVsZW1lbnQpIHJldHVybjtcblxuICAgIHRoaXMuY3JlYXRlSXRlbUhvbGRlckhlYWRlciA9IGZ1bmN0aW9uKGkpe1xuICAgICAgICBpZih0aGlzLm9wdGlvbnMuaGVhZGVyKXtcbiAgICAgICAgICAgIHZhciBoZWFkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGhlYWRlci5jbGFzc05hbWUgPSBcIm13LXVpLWJveC1oZWFkZXJcIjtcbiAgICAgICAgICAgIGhlYWRlci5pbm5lckhUTUwgPSB0aGlzLm9wdGlvbnMuaGVhZGVyLnJlcGxhY2UoL3tjb3VudH0vZywgJzxzcGFuIGNsYXNzPVwibXctbW9kdWxlLXNldHRpbmdzLWJveC1pbmRleFwiPicrKGkrMSkrJzwvc3Bhbj4nKTtcbiAgICAgICAgICAgIG13LiQoaGVhZGVyKS5vbignY2xpY2snLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIG13LiQodGhpcykubmV4dCgpLnNsaWRlVG9nZ2xlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBoZWFkZXI7XG5cbiAgICAgICAgfVxuICAgIH07XG4gICAgdGhpcy5oZWFkZXJBbmFsaXplID0gZnVuY3Rpb24oaGVhZGVyKXtcbiAgICAgICAgbXcuJChcIltkYXRhLWFjdGlvbj0ncmVtb3ZlJ11cIiwgaGVhZGVyKS5vbignY2xpY2snLCBmdW5jdGlvbihlKXtcbiAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAkKG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3Nlcyh0aGlzLCBbJ213LW1vZHVsZS1zZXR0aW5ncy1ib3gnXSkpLnJlbW92ZSgpO1xuICAgICAgICAgICAgc2NvcGUucmVmYWN0b3JEb21Qb3NpdGlvbigpO1xuICAgICAgICAgICAgc2NvcGUuYXV0b1NhdmUoKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbiAgICB0aGlzLmNyZWF0ZUl0ZW1Ib2xkZXIgPSBmdW5jdGlvbihpKXtcbiAgICAgICAgaSA9IGkgfHwgMDtcbiAgICAgICAgdmFyIGhvbGRlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2YXIgaG9sZGVyaW4gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgaG9sZGVyLmNsYXNzTmFtZSA9ICdtdy11aS1ib3ggbXctbW9kdWxlLXNldHRpbmdzLWJveCc7XG4gICAgICAgIGhvbGRlcmluLmNsYXNzTmFtZSA9ICdtdy11aS1ib3gtY29udGVudCBtdy1tb2R1bGUtc2V0dGluZ3MtYm94LWNvbnRlbnQnO1xuICAgICAgICBob2xkZXJpbi5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQoaG9sZGVyaW4pO1xuICAgICAgICBpZighdGhpcy5vcHRpb25zLmVsZW1lbnQuY2hpbGRyZW4pIHtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5lbGVtZW50LmFwcGVuZENoaWxkKGhvbGRlcik7XG4gICAgICAgIH0gZWxzZSBpZiAoIXRoaXMub3B0aW9ucy5lbGVtZW50LmNoaWxkcmVuW2ldKXtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5lbGVtZW50LmFwcGVuZENoaWxkKGhvbGRlcik7XG4gICAgICAgIH0gZWxzZSBpZiAodGhpcy5vcHRpb25zLmVsZW1lbnQuY2hpbGRyZW5baV0pe1xuICAgICAgICAgICAgJCh0aGlzLm9wdGlvbnMuZWxlbWVudC5jaGlsZHJlbltpXSkuYmVmb3JlKGhvbGRlcik7XG4gICAgICAgIH1cblxuXG4gICAgICAgIHJldHVybiBob2xkZXI7XG4gICAgfTtcblxuICAgIHRoaXMuYWRkTmV3ID0gZnVuY3Rpb24ocG9zLCBtZXRob2Qpe1xuICAgICAgICBtZXRob2QgPSBtZXRob2QgfHwgJ25ldyc7XG4gICAgICAgIHBvcyA9IHBvcyB8fCAwO1xuICAgICAgICB2YXIgX25ldztcblxuICAgICAgICB2YXIgdmFsID0gdGhpcy52YWx1ZVswXTtcblxuICAgICAgICBpZih2YWwpIHtcbiAgICAgICAgICAgIF9uZXcgPSBtdy50b29scy5jbG9uZU9iamVjdChKU09OLnBhcnNlKEpTT04uc3RyaW5naWZ5KHRoaXMudmFsdWVbMF0pKSk7XG5cbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIF9uZXcgPSB7fTtcbiAgICAgICAgfVxuXG5cbiAgICAgICAgaWYoX25ldy50aXRsZSkge1xuICAgICAgICAgICAgX25ldy50aXRsZSArPSAnIC0gbmV3JztcbiAgICAgICAgfSBlbHNlIGlmKF9uZXcubmFtZSkge1xuICAgICAgICAgICAgX25ldy5uYW1lICs9ICcgLSBuZXcnO1xuICAgICAgICB9XG4gICAgICAgIGlmKG1ldGhvZCA9PT0gJ25ldycpe1xuICAgICAgICAgICAgJC5lYWNoKHRoaXMub3B0aW9ucy5zY2hlbWEsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYodGhpcy52YWx1ZSkge1xuICAgICAgICAgICAgICAgICAgICBpZih0eXBlb2YgdGhpcy52YWx1ZSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgX25ld1t0aGlzLmlkXSA9IHRoaXMudmFsdWUoKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIF9uZXdbdGhpcy5pZF0gPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICB0aGlzLnZhbHVlLnNwbGljZShwb3MsIDAsIF9uZXcpO1xuICAgICAgICB0aGlzLmNyZWF0ZUl0ZW0oX25ldywgcG9zKTtcbiAgICB9O1xuXG4gICAgdGhpcy5yZW1vdmUgPSBmdW5jdGlvbihwb3Mpe1xuICAgICAgICBpZih0eXBlb2YgcG9zID09PSAndW5kZWZpbmVkJykgcmV0dXJuO1xuICAgICAgICB0aGlzLnZhbHVlLnNwbGljZShwb3MsIDEpO1xuICAgICAgICB0aGlzLml0ZW1zLnNwbGljZShwb3MsIDEpO1xuICAgICAgICBtdy4kKHRoaXMub3B0aW9ucy5lbGVtZW50KS5jaGlsZHJlbigpLmVxKHBvcykuYW5pbWF0ZSh7b3BhY2l0eTogMCwgaGVpZ2h0OiAwfSwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlKCk7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCdjaGFuZ2UnLCBbc2NvcGUudmFsdWUvKiwgc2NvcGUudmFsdWVbaV0qL10pO1xuICAgIH07XG5cbiAgICB0aGlzLmNyZWF0ZUl0ZW0gPSBmdW5jdGlvbihjdXJyLCBpKXtcbiAgICAgICAgdmFyIGJveCA9IHRoaXMuY3JlYXRlSXRlbUhvbGRlcihpKTtcbiAgICAgICAgdmFyIGhlYWRlciA9IHRoaXMuY3JlYXRlSXRlbUhvbGRlckhlYWRlcihpKTtcbiAgICAgICAgdmFyIGl0ZW0gPSBuZXcgbXcucHJvcEVkaXRvci5zY2hlbWEoe1xuICAgICAgICAgICAgc2NoZW1hOiB0aGlzLm9wdGlvbnMuc2NoZW1hLFxuICAgICAgICAgICAgZWxlbWVudDogYm94LnF1ZXJ5U2VsZWN0b3IoJy5tdy11aS1ib3gtY29udGVudCcpXG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKGJveCkucHJlcGVuZChoZWFkZXIpO1xuICAgICAgICB0aGlzLmhlYWRlckFuYWxpemUoaGVhZGVyKTtcbiAgICAgICAgdGhpcy5pdGVtcy5wdXNoKGl0ZW0pO1xuICAgICAgICBpdGVtLm9wdGlvbnMuZWxlbWVudC5fcHJvcCA9IGl0ZW07XG4gICAgICAgIGl0ZW0uc2V0VmFsdWUoY3Vycik7XG4gICAgICAgIG13LiQoaXRlbSkub24oJ2NoYW5nZScsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAkLmVhY2goaXRlbS5nZXRWYWx1ZSgpLCBmdW5jdGlvbihhLCBiKXtcbiAgICAgICAgICAgICAgICAvLyB0b2RvOiBmYXN0ZXIgYXBwcm9hY2hcbiAgICAgICAgICAgICAgICB2YXIgaW5kZXggPSBtdy4kKGJveCkucGFyZW50KCkuY2hpbGRyZW4oJy5tdy1tb2R1bGUtc2V0dGluZ3MtYm94JykuaW5kZXgoYm94KTtcbiAgICAgICAgICAgICAgICBzY29wZS52YWx1ZVtpbmRleF1bYV0gPSBiO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAkKCdbZGF0YS1iaW5kXScsIGhlYWRlcikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IGl0ZW0uZ2V0VmFsdWUoKTtcbiAgICAgICAgICAgICAgICB2YXIgYmluZCA9IHRoaXMuZGF0YXNldC5iaW5kO1xuICAgICAgICAgICAgICAgIGlmKHZhbFtiaW5kXSl7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdmFsW2JpbmRdO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdGhpcy5kYXRhc2V0Lm9yaWc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy4kKHNjb3BlKS50cmlnZ2VyKCdjaGFuZ2UnLCBbc2NvcGUudmFsdWUvKiwgc2NvcGUudmFsdWVbaV0qL10pO1xuICAgICAgICB9KTtcbiAgICAgICAgJCgnW2RhdGEtYmluZF0nLCBoZWFkZXIpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHZhbCA9IGl0ZW0uZ2V0VmFsdWUoKTtcbiAgICAgICAgICAgIHZhciBiaW5kID0gdGhpcy5kYXRhc2V0LmJpbmQ7XG4gICAgICAgICAgICB0aGlzLmRhdGFzZXQub3JpZyA9IHRoaXMuaW5uZXJIVE1MO1xuICAgICAgICAgICAgaWYodmFsW2JpbmRdKXtcbiAgICAgICAgICAgICAgICB0aGlzLmlubmVySFRNTCA9IHZhbFtiaW5kXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHRoaXMuX2F1dG9TYXZlVGltZSA9IG51bGw7XG4gICAgdGhpcy5hdXRvU2F2ZSA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5hdXRvU2F2ZSl7XG4gICAgICAgICAgICBjbGVhclRpbWVvdXQodGhpcy5fYXV0b1NhdmVUaW1lKTtcbiAgICAgICAgICAgIHRoaXMuX2F1dG9TYXZlVGltZSA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBzY29wZS5zYXZlKCk7XG4gICAgICAgICAgICB9LCA1MDApO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHRoaXMucmVmYWN0b3JEb21Qb3NpdGlvbiA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHNjb3BlLml0ZW1zID0gW107XG4gICAgICAgIHNjb3BlLnZhbHVlID0gW107XG4gICAgICAgIG13LiQoXCIubXctbW9kdWxlLXNldHRpbmdzLWJveC1pbmRleFwiLCB0aGlzLm9wdGlvbnMuZWxlbWVudCkuZWFjaChmdW5jdGlvbiAoaSkge1xuICAgICAgICAgICAgbXcuJCh0aGlzKS50ZXh0KGkrMSk7XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKCcubXctbW9kdWxlLXNldHRpbmdzLWJveC1jb250ZW50JywgdGhpcy5vcHRpb25zLmVsZW1lbnQpLmVhY2goZnVuY3Rpb24oaSl7XG4gICAgICAgICAgICBzY29wZS5pdGVtcy5wdXNoKHRoaXMuX3Byb3ApO1xuICAgICAgICAgICAgc2NvcGUudmFsdWUucHVzaCh0aGlzLl9wcm9wLmdldFZhbHVlKCkpO1xuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChzY29wZSkudHJpZ2dlcignY2hhbmdlJywgW3Njb3BlLnZhbHVlXSk7XG4gICAgfTtcblxuICAgIHRoaXMuY3JlYXRlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy52YWx1ZS5mb3JFYWNoKGZ1bmN0aW9uKGN1cnIsIGkpe1xuICAgICAgICAgICAgc2NvcGUuY3JlYXRlSXRlbShjdXJyLCBpKTtcbiAgICAgICAgfSk7XG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5zb3J0YWJsZSAmJiAkLmZuLnNvcnRhYmxlKXtcbiAgICAgICAgICAgIHZhciBjb25mID0ge1xuICAgICAgICAgICAgICAgIHVwZGF0ZTogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5yZWZhY3RvckRvbVBvc2l0aW9uKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5hdXRvU2F2ZSgpO1xuICAgICAgICAgICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBoYW5kbGU6dGhpcy5vcHRpb25zLmhlYWRlciA/ICcubXctdWktYm94LWhlYWRlcicgOiB1bmRlZmluZWQsXG4gICAgICAgICAgICAgICAgYXhpczoneSdcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBpZih0eXBlb2YgdGhpcy5vcHRpb25zLnNvcnRhYmxlID09PSAnb2JqZWN0Jyl7XG4gICAgICAgICAgICAgICAgY29uZiA9ICQuZXh0ZW5kKHt9LCBjb25mLCB0aGlzLm9wdGlvbnMuc29ydGFibGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuJCh0aGlzLm9wdGlvbnMuZWxlbWVudCkuc29ydGFibGUoY29uZik7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgdGhpcy5pbml0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdGhpcy5jcmVhdGUoKTtcbiAgICB9O1xuXG4gICAgdGhpcy5zYXZlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIGtleSA9ICh0aGlzLm9wdGlvbnMua2V5IHx8IHRoaXMub3B0aW9ucy5vcHRpb25fa2V5KTtcbiAgICAgICAgdmFyIGdyb3VwID0gKHRoaXMub3B0aW9ucy5ncm91cCB8fCB0aGlzLm9wdGlvbnMub3B0aW9uX2dyb3VwKTtcbiAgICAgICAgaWYoIGtleSAmJiBncm91cCl7XG4gICAgICAgICAgICB2YXIgb3B0aW9ucyA9IHtcbiAgICAgICAgICAgICAgICBncm91cDp0aGlzLm9wdGlvbnMuZ3JvdXAsXG4gICAgICAgICAgICAgICAga2V5OnRoaXMub3B0aW9ucy5rZXksXG4gICAgICAgICAgICAgICAgdmFsdWU6dGhpcy50b1N0cmluZygpXG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgbXcub3B0aW9ucy5zYXZlT3B0aW9uKG9wdGlvbnMsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLm1zZyhzY29wZS5zYXZlZE1lc3NhZ2UgfHwgbXcubXNnLnNldHRpbmdzU2F2ZWQpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICAgaWYoIWtleSl7XG4gICAgICAgICAgICAgICAgY29uc29sZS53YXJuKCdPcHRpb24ga2V5IGlzIG5vdCBkZWZpbmVkLicpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoIWdyb3VwKXtcbiAgICAgICAgICAgICAgICBjb25zb2xlLndhcm4oJ09wdGlvbiBncm91cCBpcyBub3QgZGVmaW5lZC4nKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgfTtcblxuXG4gICAgdGhpcy50b1N0cmluZyA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIHJldHVybiBKU09OLnN0cmluZ2lmeSh0aGlzLnZhbHVlKTtcbiAgICB9O1xuXG4gICAgdGhpcy5pbml0KCk7XG59O1xuIiwibXcucmVxdWlyZSgnZWRpdG9yLmpzJylcbm13LnByb3BFZGl0b3IgPSB7XG4gICAgYWRkSW50ZXJmYWNlOmZ1bmN0aW9uKG5hbWUsIGZ1bmMpe1xuICAgICAgICB0aGlzLmludGVyZmFjZXNbbmFtZV0gPSB0aGlzLmludGVyZmFjZXNbbmFtZV0gfHwgZnVuYztcbiAgICB9LFxuICAgIGdldFJvb3RFbGVtZW50OiBmdW5jdGlvbihub2RlKXtcbiAgICAgICAgaWYobm9kZS5ub2RlTmFtZSAhPT0gJ0lGUkFNRScpIHJldHVybiBub2RlO1xuICAgICAgICByZXR1cm4gJChub2RlKS5jb250ZW50cygpLmZpbmQoJ2JvZHknKVswXTtcbiAgICB9LFxuICAgIGhlbHBlcnM6e1xuICAgICAgICB3cmFwcGVyOmZ1bmN0aW9uKCl7XG4gICAgICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9ICdtdy11aS1maWVsZC1ob2xkZXIgcHJvcC11aS1maWVsZC1ob2xkZXInO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9LFxuICAgICAgICBidXR0b25OYXY6ZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHZhciBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgZWwuY2xhc3NOYW1lID0gJ213LXVpLWJ0bi1uYXYgcHJvcC11aS1maWVsZC1ob2xkZXInO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9LFxuICAgICAgICBxdWF0cm9XcmFwcGVyOmZ1bmN0aW9uKGNscyl7XG4gICAgICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9IGNscyB8fCAncHJvcC11aS1maWVsZC1xdWF0cm8nO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9LFxuICAgICAgICBsYWJlbDpmdW5jdGlvbihjb250ZW50KXtcbiAgICAgICAgICAgIHZhciBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2xhYmVsJyk7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAnY29udHJvbC1sYWJlbCBkLWJsb2NrIHByb3AtdWktbGFiZWwnO1xuICAgICAgICAgICAgZWwuaW5uZXJIVE1MID0gY29udGVudDtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfSxcbiAgICAgICAgYnV0dG9uOmZ1bmN0aW9uKGNvbnRlbnQpe1xuICAgICAgICAgICAgdmFyIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnYnV0dG9uJyk7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAnbXctdWktYnRuJztcbiAgICAgICAgICAgIGVsLmlubmVySFRNTCA9IGNvbnRlbnQ7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH0sXG4gICAgICAgIGZpZWxkOiBmdW5jdGlvbih2YWwsIHR5cGUsIG9wdGlvbnMpe1xuICAgICAgICAgICAgdHlwZSA9IHR5cGUgfHwgJ3RleHQnO1xuICAgICAgICAgICAgdmFyIGVsO1xuICAgICAgICAgICAgaWYodHlwZSA9PT0gJ3NlbGVjdCcpe1xuICAgICAgICAgICAgICAgIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc2VsZWN0Jyk7XG4gICAgICAgICAgICAgICAgaWYob3B0aW9ucyAmJiBvcHRpb25zLmxlbmd0aCl7XG4gICAgICAgICAgICAgICAgICAgIHZhciBvcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdvcHRpb24nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbi5pbm5lckhUTUwgPSAnQ2hvb3NlLi4uJztcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbi52YWx1ZSA9ICcnO1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQob3B0aW9uKTtcbiAgICAgICAgICAgICAgICAgICAgZm9yKHZhciBpPTA7aTxvcHRpb25zLmxlbmd0aDtpKyspe1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG9wdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ29wdGlvbicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYodHlwZW9mIG9wdGlvbnNbaV0gPT09ICdzdHJpbmcnIHx8IHR5cGVvZiBvcHRpb25zW2ldID09PSAnbnVtYmVyJyl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3B0LmlubmVySFRNTCA9IG9wdGlvbnNbaV07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3B0LnZhbHVlID0gb3B0aW9uc1tpXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3B0LmlubmVySFRNTCA9IG9wdGlvbnNbaV0udGl0bGU7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3B0LnZhbHVlID0gb3B0aW9uc1tpXS52YWx1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLmFwcGVuZENoaWxkKG9wdCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmKHR5cGUgPT09ICd0ZXh0YXJlYScpe1xuICAgICAgICAgICAgICAgIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndGV4dGFyZWEnKTtcbiAgICAgICAgICAgIH0gZWxzZXtcbiAgICAgICAgICAgICAgICBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgdHJ5IHsgLy8gSUUxMSB0aHJvd3MgZXJyb3Igb24gaHRtbDUgdHlwZXNcbiAgICAgICAgICAgICAgICAgICAgZWwudHlwZSA9IHR5cGU7XG4gICAgICAgICAgICAgICAgfSBjYXRjaCAoZXJyKSB7XG4gICAgICAgICAgICAgICAgICAgIGVsLnR5cGUgPSAndGV4dCc7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9ICdmb3JtLWNvbnRyb2wgcHJvcC11aS1maWVsZCc7XG4gICAgICAgICAgICBlbC52YWx1ZSA9IHZhbDtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfSxcbiAgICAgICAgZmllbGRQYWNrOmZ1bmN0aW9uKGxhYmVsLCB0eXBlKXtcbiAgICAgICAgICAgIHZhciBmaWVsZCA9IG13LnByb3BFZGl0b3IuaGVscGVycy5maWVsZCgnJywgdHlwZSk7XG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGxhYmVsKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChsYWJlbClcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChmaWVsZCk7XG4gICAgICAgICAgICByZXR1cm57XG4gICAgICAgICAgICAgICAgbGFiZWw6bGFiZWwsXG4gICAgICAgICAgICAgICAgaG9sZGVyOmhvbGRlcixcbiAgICAgICAgICAgICAgICBmaWVsZDpmaWVsZFxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICByZW5kOmZ1bmN0aW9uKGVsZW1lbnQsIHJlbmQpe1xuXG4gICAgICAgIGVsZW1lbnQgPSBtdy5wcm9wRWRpdG9yLmdldFJvb3RFbGVtZW50KGVsZW1lbnQpO1xuICAgICAgICBmb3IodmFyIGk9MDtpPHJlbmQubGVuZ3RoO2krKyl7XG4gICAgICAgICAgICBlbGVtZW50LmFwcGVuZENoaWxkKHJlbmRbaV0ubm9kZSk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHNjaGVtYTpmdW5jdGlvbihvcHRpb25zKXtcbiAgICAgICAgdGhpcy5fYWZ0ZXIgPSBbXTtcbiAgICAgICAgdGhpcy5zZXRTY2hlbWEgPSBmdW5jdGlvbihzY2hlbWEpe1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLnNjaGVtYSA9IHNjaGVtYTtcbiAgICAgICAgICAgIHRoaXMuX3JlbmQgPSBbXTtcbiAgICAgICAgICAgIHRoaXMuX3ZhbFNjaGVtYSA9IHRoaXMuX3ZhbFNjaGVtYSB8fCB7fTtcbiAgICAgICAgICAgIGZvcih2YXIgaSA9MDsgaTwgdGhpcy5vcHRpb25zLnNjaGVtYS5sZW5ndGg7IGkrKyl7XG4gICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSB0aGlzLm9wdGlvbnMuc2NoZW1hW2ldO1xuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiB0aGlzLl92YWxTY2hlbWFbaXRlbS5pZF0gPT09ICd1bmRlZmluZWQnICYmIHRoaXMuX2NhY2hlLmluZGV4T2YoaXRlbSkgPT09IC0xKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fY2FjaGUucHVzaChpdGVtKVxuICAgICAgICAgICAgICAgICAgICB2YXIgY3VyciA9IG5ldyBtdy5wcm9wRWRpdG9yLmludGVyZmFjZXNbaXRlbS5pbnRlcmZhY2VdKHRoaXMsIGl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9yZW5kLnB1c2goY3Vycik7XG4gICAgICAgICAgICAgICAgICAgIGlmKGl0ZW0uaWQpe1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fdmFsU2NoZW1hW2l0ZW0uaWRdID0gdGhpcy5fdmFsU2NoZW1hW2l0ZW0uaWRdIHx8ICcnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgJCh0aGlzLnJvb3RIb2xkZXIpLmh0bWwoJyAnKS5hZGRDbGFzcygnbXctcHJvcC1lZGl0b3Itcm9vdCcpO1xuICAgICAgICAgICAgbXcucHJvcEVkaXRvci5yZW5kKHRoaXMucm9vdEhvbGRlciwgdGhpcy5fcmVuZCk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMudXBkYXRlU2NoZW1hID0gZnVuY3Rpb24oc2NoZW1hKXtcbiAgICAgICAgICAgIHZhciBmaW5hbCA9IFtdO1xuICAgICAgICAgICAgZm9yKHZhciBpID0wOyBpPHNjaGVtYS5sZW5ndGg7aSsrKXtcbiAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IHNjaGVtYVtpXTtcblxuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiB0aGlzLl92YWxTY2hlbWFbaXRlbS5pZF0gPT09ICd1bmRlZmluZWQnICYmIHRoaXMuX2NhY2hlLmluZGV4T2YoaXRlbSkgPT09IC0xKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5vcHRpb25zLnNjaGVtYS5wdXNoKGl0ZW0pO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9jYWNoZS5wdXNoKGl0ZW0pXG4gICAgICAgICAgICAgICAgICAgIHZhciBjcmVhdGUgPSBuZXcgbXcucHJvcEVkaXRvci5pbnRlcmZhY2VzW2l0ZW0uaW50ZXJmYWNlXSh0aGlzLCBpdGVtKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fcmVuZC5wdXNoKGNyZWF0ZSk7XG4gICAgICAgICAgICAgICAgICAgIGZpbmFsLnB1c2goY3JlYXRlKTtcbiAgICAgICAgICAgICAgICAgICAgaWYoaXRlbS5pZCl7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl92YWxTY2hlbWFbaXRlbS5pZF0gPSB0aGlzLl92YWxTY2hlbWFbaXRlbS5pZF0gfHwgJyc7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgLy90aGlzLnJvb3RIb2xkZXIuYXBwZW5kQ2hpbGQoY3JlYXRlLm5vZGUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBmaW5hbDtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5zZXRWYWx1ZSA9IGZ1bmN0aW9uKHZhbCl7XG4gICAgICAgICAgICBpZighdmFsKXtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdmFsKXtcbiAgICAgICAgICAgICAgICB2YXIgcmVuZCA9IHRoaXMuZ2V0UmVuZEJ5SWQoaSk7XG4gICAgICAgICAgICAgICAgaWYoISFyZW5kKXtcbiAgICAgICAgICAgICAgICAgICAgcmVuZC5zZXRWYWx1ZSh2YWxbaV0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5nZXRWYWx1ZSA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5fdmFsU2NoZW1hO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmRpc2FibGUgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5kaXNhYmxlZCA9IHRydWU7XG4gICAgICAgICAgICAkKHRoaXMucm9vdEhvbGRlcikuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZW5hYmxlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuZGlzYWJsZWQgPSBmYWxzZTtcbiAgICAgICAgICAgICQodGhpcy5yb290SG9sZGVyKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5nZXRSZW5kQnlJZCA9IGZ1bmN0aW9uKGlkKSB7XG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdGhpcy5fcmVuZCkge1xuICAgICAgICAgICAgICAgIGlmKHRoaXMuX3JlbmRbaV0uaWQgPT09IGlkKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLl9yZW5kW2ldO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5fY2FjaGUgPSBbXTtcbiAgICAgICAgdGhpcy5vcHRpb25zID0gb3B0aW9ucztcbiAgICAgICAgdGhpcy5vcHRpb25zLmVsZW1lbnQgPSB0eXBlb2YgdGhpcy5vcHRpb25zLmVsZW1lbnQgPT09ICdzdHJpbmcnID8gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihvcHRpb25zLmVsZW1lbnQpIDogdGhpcy5vcHRpb25zLmVsZW1lbnQ7XG4gICAgICAgIHRoaXMucm9vdEhvbGRlciA9IG13LnByb3BFZGl0b3IuZ2V0Um9vdEVsZW1lbnQodGhpcy5vcHRpb25zLmVsZW1lbnQpO1xuICAgICAgICB0aGlzLnNldFNjaGVtYSh0aGlzLm9wdGlvbnMuc2NoZW1hKTtcblxuICAgICAgICB0aGlzLl9hZnRlci5mb3JFYWNoKGZ1bmN0aW9uICh2YWx1ZSkge1xuICAgICAgICAgICAgdmFsdWUuaXRlbXMuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSkge1xuICAgICAgICAgICAgICAgIHZhbHVlLm5vZGUuYXBwZW5kQ2hpbGQoaXRlbS5ub2RlKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcblxuICAgICAgICBtdy50cmlnZ2VyKCdDb21wb25lbnRzTGF1bmNoJyk7XG4gICAgfSxcblxuICAgIGludGVyZmFjZXM6e1xuICAgICAgICBxdWF0cm86ZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICAvL1wiMnB4IDRweCA4cHggMTIycHhcIlxuICAgICAgICAgICAgdmFyIGhvbGRlciA9IG13LnByb3BFZGl0b3IuaGVscGVycy5xdWF0cm9XcmFwcGVyKCdtdy1jc3MtZWRpdG9yLWdyb3VwJyk7XG5cbiAgICAgICAgICAgIGZvcih2YXIgaSA9IDA7IGk8NDsgaSsrKXtcbiAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IG13LnByb3BFZGl0b3IuaGVscGVycy5maWVsZFBhY2soY29uZmlnLmxhYmVsW2ldLCAnc2l6ZScpO1xuICAgICAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChpdGVtLmhvbGRlcik7XG4gICAgICAgICAgICAgICAgaXRlbS5maWVsZC5vbmlucHV0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGZpbmFsID0gJyc7XG4gICAgICAgICAgICAgICAgICAgIHZhciBhbGwgPSBob2xkZXIucXVlcnlTZWxlY3RvckFsbCgnaW5wdXQnKSwgaSA9IDA7XG4gICAgICAgICAgICAgICAgICAgIGZvciggOyBpPGFsbC5sZW5ndGg7IGkrKyl7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdW5pdCA9IGFsbFtpXS5kYXRhc2V0LnVuaXQgfHwgJyc7XG4gICAgICAgICAgICAgICAgICAgICAgICBmaW5hbCs9ICcgJyArIGFsbFtpXS52YWx1ZSArIHVuaXQgO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IGZpbmFsLnRyaW0oKTtcbiAgICAgICAgICAgICAgICAgICAgICQocHJvdG8pLnRyaWdnZXIoJ2NoYW5nZScsIFtjb25maWcuaWQsIGZpbmFsLnRyaW0oKV0pO1xuICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIHZhbHVlID0gdmFsdWUudHJpbSgpO1xuICAgICAgICAgICAgICAgIHZhciBhcnIgPSB2YWx1ZS5zcGxpdCgnICcpO1xuICAgICAgICAgICAgICAgIHZhciBhbGwgPSBob2xkZXIucXVlcnlTZWxlY3RvckFsbCgnaW5wdXQnKSwgaSA9IDA7XG4gICAgICAgICAgICAgICAgZm9yKCA7IGk8YWxsLmxlbmd0aDsgaSsrKXtcbiAgICAgICAgICAgICAgICAgICAgYWxsW2ldLnZhbHVlID0gcGFyc2VJbnQoYXJyW2ldLCAxMCk7XG4gICAgICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBhcnJbaV0gPT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGFycltpXSA9ICcnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHZhciB1bml0ID0gYXJyW2ldLnJlcGxhY2UoL1swLTldL2csICcnKTtcbiAgICAgICAgICAgICAgICAgICAgYWxsW2ldLmRhdGFzZXQudW5pdCA9IHVuaXQ7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IHZhbHVlO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIHRoaXMuaWQgPSBjb25maWcuaWQ7XG4gICAgICAgIH0sXG4gICAgICAgIGhyOmZ1bmN0aW9uKHByb3RvLCBjb25maWcpe1xuICAgICAgICAgICAgdmFyIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaHInKTtcbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9ICcgJztcbiAgICAgICAgICAgIHRoaXMubm9kZSA9IGVsO1xuICAgICAgICB9LFxuICAgICAgICBibG9jazogZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgbm9kZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgaWYodHlwZW9mIGNvbmZpZy5jb250ZW50ID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgICAgIG5vZGUuaW5uZXJIVE1MID0gY29uZmlnLmNvbnRlbnQ7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHZhciBuZXdJdGVtcyA9IHByb3RvLnVwZGF0ZVNjaGVtYShjb25maWcuY29udGVudCk7XG4gICAgICAgICAgICAgICAgcHJvdG8uX2FmdGVyLnB1c2goe25vZGU6IG5vZGUsIGl0ZW1zOiBuZXdJdGVtc30pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoY29uZmlnLmNsYXNzKXtcbiAgICAgICAgICAgICAgICBub2RlLmNsYXNzTmFtZSA9IGNvbmZpZy5jbGFzcztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMubm9kZSA9IG5vZGU7XG4gICAgICAgIH0sXG4gICAgICAgIHNpemU6ZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgZmllbGQgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQoJycsICd0ZXh0Jyk7XG4gICAgICAgICAgICB0aGlzLmZpZWxkID0gZmllbGQ7XG4gICAgICAgICAgICBjb25maWcuYXV0b2NvbXBsZXRlID0gY29uZmlnLmF1dG9jb21wbGV0ZSB8fCBbJ2F1dG8nXTtcblxuICAgICAgICAgICAgdmFyIGhvbGRlciA9IG13LnByb3BFZGl0b3IuaGVscGVycy53cmFwcGVyKCk7XG4gICAgICAgICAgICB2YXIgYnV0dG9uTmF2ID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmJ1dHRvbk5hdigpO1xuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGR0bGlzdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RhdGFsaXN0Jyk7XG4gICAgICAgICAgICBkdGxpc3QuaWQgPSAnbXctZGF0YWxpc3QtJyArIG13LnJhbmRvbSgpO1xuICAgICAgICAgICAgY29uZmlnLmF1dG9jb21wbGV0ZS5mb3JFYWNoKGZ1bmN0aW9uICh2YWx1ZSkge1xuICAgICAgICAgICAgICAgIHZhciBvcHRpb24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdvcHRpb24nKTtcbiAgICAgICAgICAgICAgICBvcHRpb24udmFsdWUgPSB2YWx1ZTtcbiAgICAgICAgICAgICAgICBkdGxpc3QuYXBwZW5kQ2hpbGQob3B0aW9uKVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuZmllbGQuc2V0QXR0cmlidXRlKCdsaXN0JywgZHRsaXN0LmlkKTtcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoZHRsaXN0KTtcblxuICAgICAgICAgICAgdGhpcy5fbWFrZVZhbCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgaWYoZmllbGQudmFsdWUgPT09ICdhdXRvJyl7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnYXV0byc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBmaWVsZC52YWx1ZSArIGZpZWxkLmRhdGFzZXQudW5pdDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciB1bml0U2VsZWN0b3IgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQoJycsICdzZWxlY3QnLCBbXG4gICAgICAgICAgICAgICAgJ3B4JywgJyUnLCAncmVtJywgJ2VtJywgJ3ZoJywgJ3Z3JywgJ2V4JywgJ2NtJywgJ21tJywgJ2luJywgJ3B0JywgJ3BjJywgJ2NoJ1xuICAgICAgICAgICAgXSk7XG4gICAgICAgICAgICB0aGlzLnVuaXRTZWxlY3RvciA9IHVuaXRTZWxlY3RvcjtcbiAgICAgICAgICAgICQoaG9sZGVyKS5hZGRDbGFzcygncHJvcC11aS1maWVsZC1ob2xkZXItc2l6ZScpO1xuICAgICAgICAgICAgJCh1bml0U2VsZWN0b3IpXG4gICAgICAgICAgICAgICAgLnZhbCgncHgnKVxuICAgICAgICAgICAgICAgIC5hZGRDbGFzcygncHJvcC11aS1maWVsZC11bml0Jyk7XG4gICAgICAgICAgICB1bml0U2VsZWN0b3Iub25jaGFuZ2UgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGZpZWxkLmRhdGFzZXQudW5pdCA9ICQodGhpcykudmFsKCkgfHwgJ3B4JztcblxuICAgICAgICAgICAgICAgICQocHJvdG8pLnRyaWdnZXIoJ2NoYW5nZScsIFtjb25maWcuaWQsIHNjb3BlLl9tYWtlVmFsKCldKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgICQodW5pdFNlbGVjdG9yKS5vbignY2hhbmdlJywgZnVuY3Rpb24oKXtcblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChsYWJlbCk7XG4gICAgICAgICAgICBidXR0b25OYXYuYXBwZW5kQ2hpbGQoZmllbGQpO1xuICAgICAgICAgICAgYnV0dG9uTmF2LmFwcGVuZENoaWxkKHVuaXRTZWxlY3Rvcik7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQoYnV0dG9uTmF2KTtcblxuICAgICAgICAgICAgZmllbGQub25pbnB1dCA9IGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB0aGlzLnZhbHVlICsgdGhpcy5kYXRhc2V0LnVuaXQ7XG4gICAgICAgICAgICAgICAgJChwcm90bykudHJpZ2dlcignY2hhbmdlJywgW2NvbmZpZy5pZCwgc2NvcGUuX21ha2VWYWwoKV0pO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5ub2RlID0gaG9sZGVyO1xuICAgICAgICAgICAgdGhpcy5zZXRWYWx1ZSA9IGZ1bmN0aW9uKHZhbHVlKXtcbiAgICAgICAgICAgICAgICB2YXIgYW4gPSBwYXJzZUludCh2YWx1ZSwgMTApO1xuICAgICAgICAgICAgICAgIGZpZWxkLnZhbHVlID0gaXNOYU4oYW4pID8gdmFsdWUgOiBhbjtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWx1ZTtcbiAgICAgICAgICAgICAgICB2YXIgdW5pdCA9IHZhbHVlLnJlcGxhY2UoL1swLTldL2csICcnKS5yZXBsYWNlKC9cXC4vZywgJycpO1xuICAgICAgICAgICAgICAgIGZpZWxkLmRhdGFzZXQudW5pdCA9IHVuaXQ7XG4gICAgICAgICAgICAgICAgJCh1bml0U2VsZWN0b3IpLnZhbCh1bml0KTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLmlkID0gY29uZmlnLmlkO1xuXG4gICAgICAgIH0sXG4gICAgICAgIHRleHQ6ZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgdmFsID0gJyc7XG4gICAgICAgICAgICBpZihjb25maWcudmFsdWUpe1xuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBjb25maWcudmFsdWUgPT09ICdmdW5jdGlvbicpe1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBjb25maWcudmFsdWUoKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBjb25maWcudmFsdWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGZpZWxkID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKHZhbCwgJ3RleHQnKTtcbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGFiZWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKGZpZWxkKTtcbiAgICAgICAgICAgIGZpZWxkLm9uaW5wdXQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgJChwcm90bykudHJpZ2dlcignY2hhbmdlJywgW2NvbmZpZy5pZCwgdGhpcy52YWx1ZV0pO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIHRoaXMubm9kZSA9IGhvbGRlcjtcbiAgICAgICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbih2YWx1ZSl7XG4gICAgICAgICAgICAgICAgZmllbGQudmFsdWUgPSB2YWx1ZTtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWx1ZTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLmlkID0gY29uZmlnLmlkO1xuICAgICAgICB9LFxuICAgICAgICBoaWRkZW46ZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgdmFsID0gJyc7XG4gICAgICAgICAgICBpZihjb25maWcudmFsdWUpe1xuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBjb25maWcudmFsdWUgPT09ICdmdW5jdGlvbicpe1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBjb25maWcudmFsdWUoKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBjb25maWcudmFsdWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB2YXIgZmllbGQgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQodmFsLCAnaGlkZGVuJyk7XG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIHZhciBsYWJlbCA9IG13LnByb3BFZGl0b3IuaGVscGVycy5sYWJlbChjb25maWcubGFiZWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKGxhYmVsKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChmaWVsZCk7XG4gICAgICAgICAgICBmaWVsZC5vbmlucHV0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgICQocHJvdG8pLnRyaWdnZXIoJ2NoYW5nZScsIFtjb25maWcuaWQsIHRoaXMudmFsdWVdKTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIGZpZWxkLnZhbHVlID0gdmFsdWU7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdmFsdWU7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5pZCA9IGNvbmZpZy5pZDtcbiAgICAgICAgfSxcbiAgICAgICAgc2hhZG93OiBmdW5jdGlvbihwcm90bywgY29uZmlnKXtcbiAgICAgICAgICAgIHZhciBzY29wZSA9IHRoaXM7XG5cbiAgICAgICAgICAgIHRoaXMuZmllbGRzID0ge1xuICAgICAgICAgICAgICAgIHBvc2l0aW9uIDogbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKCcnLCAnc2VsZWN0JywgW3t0aXRsZTonT3V0c2lkZScsIHZhbHVlOiAnJ30sIHt0aXRsZTonSW5zaWRlJywgdmFsdWU6ICdpbnNldCd9XSksXG4gICAgICAgICAgICAgICAgeCA6IG13LnByb3BFZGl0b3IuaGVscGVycy5maWVsZCgnJywgJ251bWJlcicpLFxuICAgICAgICAgICAgICAgIHkgOiBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQoJycsICdudW1iZXInKSxcbiAgICAgICAgICAgICAgICBibHVyIDogbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKCcnLCAnbnVtYmVyJyksXG4gICAgICAgICAgICAgICAgc3ByZWFkIDogbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKCcnLCAnbnVtYmVyJyksXG4gICAgICAgICAgICAgICAgY29sb3IgOiBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQoJycsICd0ZXh0JylcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuZmllbGRzLnBvc2l0aW9uLnBsYWNlaG9sZGVyID0gJ1Bvc2l0aW9uJztcbiAgICAgICAgICAgIHRoaXMuZmllbGRzLngucGxhY2Vob2xkZXIgPSAnWCBvZmZzZXQnO1xuICAgICAgICAgICAgdGhpcy5maWVsZHMueS5wbGFjZWhvbGRlciA9ICdZIG9mZnNldCc7XG4gICAgICAgICAgICB0aGlzLmZpZWxkcy5ibHVyLnBsYWNlaG9sZGVyID0gJ0JsdXInO1xuICAgICAgICAgICAgdGhpcy5maWVsZHMuc3ByZWFkLnBsYWNlaG9sZGVyID0gJ1NwcmVhZCc7XG4gICAgICAgICAgICB0aGlzLmZpZWxkcy5jb2xvci5wbGFjZWhvbGRlciA9ICdDb2xvcic7XG4gICAgICAgICAgICB0aGlzLmZpZWxkcy5jb2xvci5kYXRhc2V0Lm9wdGlvbnMgPSAncG9zaXRpb246ICcgKyAoY29uZmlnLnBpY2tlclBvc2l0aW9uIHx8ICdib3R0b20tY2VudGVyJyk7XG4gICAgICAgICAgICAvLyQodGhpcy5maWVsZHMuY29sb3IpLmFkZENsYXNzKCdtdy1jb2xvci1waWNrZXInKTtcbiAgICAgICAgICAgIG13LmNvbG9yUGlja2VyKHtcbiAgICAgICAgICAgICAgICBlbGVtZW50OnRoaXMuZmllbGRzLmNvbG9yLFxuICAgICAgICAgICAgICAgIHBvc2l0aW9uOid0b3AtbGVmdCcsXG4gICAgICAgICAgICAgICAgb25jaGFuZ2U6ZnVuY3Rpb24oY29sb3Ipe1xuICAgICAgICAgICAgICAgICAgICAkKHNjb3BlLmZpZWxkcy5jb2xvcikudHJpZ2dlcignY2hhbmdlJywgY29sb3IpXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmZpZWxkcy5jb2xvci5zdHlsZS5iYWNrZ3JvdW5kQ29sb3IgPSBjb2xvcjtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuZmllbGRzLmNvbG9yLnN0eWxlLmNvbG9yID0gbXcuY29sb3IuaXNEYXJrKGNvbG9yKSA/ICd3aGl0ZScgOiAnYmxhY2snO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICB2YXIgbGFiZWxQb3NpdGlvbiA9IG13LnByb3BFZGl0b3IuaGVscGVycy5sYWJlbCgnUG9zaXRpb24nKTtcbiAgICAgICAgICAgIHZhciBsYWJlbFggPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMubGFiZWwoJ1ggb2Zmc2V0Jyk7XG4gICAgICAgICAgICB2YXIgbGFiZWxZID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKCdZIG9mZnNldCcpO1xuICAgICAgICAgICAgdmFyIGxhYmVsQmx1ciA9IG13LnByb3BFZGl0b3IuaGVscGVycy5sYWJlbCgnQmx1cicpO1xuICAgICAgICAgICAgdmFyIGxhYmVsU3ByZWFkID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKCdTcHJlYWQnKTtcbiAgICAgICAgICAgIHZhciBsYWJlbENvbG9yID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKCdDb2xvcicpO1xuXG4gICAgICAgICAgICB2YXIgd3JhcFBvc2l0aW9uID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIHZhciB3cmFwWCA9IG13LnByb3BFZGl0b3IuaGVscGVycy53cmFwcGVyKCk7XG4gICAgICAgICAgICB2YXIgd3JhcFkgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIHdyYXBCbHVyID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIHZhciB3cmFwU3ByZWFkID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIHZhciB3cmFwQ29sb3IgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuXG5cblxuICAgICAgICAgICAgdGhpcy4kZmllbGRzID0gJCgpO1xuXG4gICAgICAgICAgICAkLmVhY2godGhpcy5maWVsZHMsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgc2NvcGUuJGZpZWxkcy5wdXNoKHRoaXMpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICQodGhpcy4kZmllbGRzKS5vbignaW5wdXQgY2hhbmdlJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB2YXIgdmFsID0gKCQoc2NvcGUuZmllbGRzLnBvc2l0aW9uKS52YWwoKSB8fCAnJylcbiAgICAgICAgICAgICAgICAgICAgKyAnICcgKyAoc2NvcGUuZmllbGRzLngudmFsdWUgfHwgMCkgKyAncHgnXG4gICAgICAgICAgICAgICAgICAgICsgJyAnICsgKHNjb3BlLmZpZWxkcy55LnZhbHVlIHx8IDApICsgJ3B4J1xuICAgICAgICAgICAgICAgICAgICArICcgJyArIChzY29wZS5maWVsZHMuYmx1ci52YWx1ZSB8fCAwKSArICdweCdcbiAgICAgICAgICAgICAgICAgICAgKyAnICcgKyAoc2NvcGUuZmllbGRzLnNwcmVhZC52YWx1ZSB8fCAwKSArICdweCdcbiAgICAgICAgICAgICAgICAgICAgKyAnICcgKyAoc2NvcGUuZmllbGRzLmNvbG9yLnZhbHVlIHx8ICdyZ2JhKDAsMCwwLC41KScpO1xuICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IHZhbDtcbiAgICAgICAgICAgICAgICAkKHByb3RvKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29uZmlnLmlkLCB2YWxdKTtcbiAgICAgICAgICAgIH0pO1xuXG5cbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuXG4gICAgICAgICAgICB2YXIgbGFiZWwgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMubGFiZWwoY29uZmlnLmxhYmVsID8gY29uZmlnLmxhYmVsIDogJycpO1xuICAgICAgICAgICAgaWYoY29uZmlnLmxhYmVsKXtcbiAgICAgICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGFiZWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHJvdzEgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIHJvdzIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgcm93MS5jbGFzc05hbWUgPSAnbXctY3NzLWVkaXRvci1ncm91cCc7XG4gICAgICAgICAgICByb3cyLmNsYXNzTmFtZSA9ICdtdy1jc3MtZWRpdG9yLWdyb3VwJztcblxuXG4gICAgICAgICAgICB3cmFwUG9zaXRpb24uYXBwZW5kQ2hpbGQobGFiZWxQb3NpdGlvbik7XG4gICAgICAgICAgICB3cmFwUG9zaXRpb24uYXBwZW5kQ2hpbGQodGhpcy5maWVsZHMucG9zaXRpb24pO1xuICAgICAgICAgICAgcm93MS5hcHBlbmRDaGlsZCh3cmFwUG9zaXRpb24pO1xuXG4gICAgICAgICAgICB3cmFwWC5hcHBlbmRDaGlsZChsYWJlbFgpO1xuICAgICAgICAgICAgd3JhcFguYXBwZW5kQ2hpbGQodGhpcy5maWVsZHMueCk7XG4gICAgICAgICAgICByb3cxLmFwcGVuZENoaWxkKHdyYXBYKTtcblxuXG4gICAgICAgICAgICB3cmFwWS5hcHBlbmRDaGlsZChsYWJlbFkpO1xuICAgICAgICAgICAgd3JhcFkuYXBwZW5kQ2hpbGQodGhpcy5maWVsZHMueSk7XG4gICAgICAgICAgICByb3cxLmFwcGVuZENoaWxkKHdyYXBZKTtcblxuICAgICAgICAgICAgd3JhcENvbG9yLmFwcGVuZENoaWxkKGxhYmVsQ29sb3IpO1xuICAgICAgICAgICAgd3JhcENvbG9yLmFwcGVuZENoaWxkKHRoaXMuZmllbGRzLmNvbG9yKTtcbiAgICAgICAgICAgIHJvdzIuYXBwZW5kQ2hpbGQod3JhcENvbG9yKTtcblxuICAgICAgICAgICAgd3JhcEJsdXIuYXBwZW5kQ2hpbGQobGFiZWxCbHVyKTtcbiAgICAgICAgICAgIHdyYXBCbHVyLmFwcGVuZENoaWxkKHRoaXMuZmllbGRzLmJsdXIpO1xuICAgICAgICAgICAgcm93Mi5hcHBlbmRDaGlsZCh3cmFwQmx1cik7XG5cbiAgICAgICAgICAgIHdyYXBTcHJlYWQuYXBwZW5kQ2hpbGQobGFiZWxTcHJlYWQpO1xuICAgICAgICAgICAgd3JhcFNwcmVhZC5hcHBlbmRDaGlsZCh0aGlzLmZpZWxkcy5zcHJlYWQpO1xuICAgICAgICAgICAgcm93Mi5hcHBlbmRDaGlsZCh3cmFwU3ByZWFkKTtcblxuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKHJvdzEpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKHJvdzIpO1xuXG4gICAgICAgICAgICAkKHRoaXMuZmllbGRzKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLm9uKCdpbnB1dCBjaGFuZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgICAgICAkKHByb3RvKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29uZmlnLmlkLCB0aGlzLnZhbHVlXSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuXG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIHZhciBwYXJzZSA9IHRoaXMucGFyc2VTaGFkb3codmFsdWUpO1xuICAgICAgICAgICAgICAgICQuZWFjaChwYXJzZSwgZnVuY3Rpb24gKGtleSwgdmFsKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmZpZWxkc1trZXldLnZhbHVlID0gdGhpcztcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWx1ZTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLnBhcnNlU2hhZG93ID0gZnVuY3Rpb24oc2hhZG93KXtcbiAgICAgICAgICAgICAgICB2YXIgaW5zZXQgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBpZihzaGFkb3cuaW5kZXhPZignaW5zZXQnKSAhPT0gLTEpe1xuICAgICAgICAgICAgICAgICAgICBpbnNldCA9IHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHZhciBhcnIgPSBzaGFkb3cucmVwbGFjZSgnaW5zZXQnLCAnJykudHJpbSgpLnJlcGxhY2UoL1xcc3syLH0vZywgJyAnKS5zcGxpdCgnICcpO1xuICAgICAgICAgICAgICAgIHZhciBzaCA9IHtcbiAgICAgICAgICAgICAgICAgICAgcG9zaXRpb246IGluc2V0ID8gJ2luJyA6ICdvdXQnLFxuICAgICAgICAgICAgICAgICAgICB4OjAsXG4gICAgICAgICAgICAgICAgICAgIHk6IDAsXG4gICAgICAgICAgICAgICAgICAgIGJsdXI6IDAsXG4gICAgICAgICAgICAgICAgICAgIHNwcmVhZDogMCxcbiAgICAgICAgICAgICAgICAgICAgY29sb3I6ICd0cmFuc3BhcmVudCdcbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIGlmKCFhcnJbMl0pe1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2g7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHNoLnggPSBhcnJbMF07XG4gICAgICAgICAgICAgICAgc2gueSA9IGFyclsxXTtcbiAgICAgICAgICAgICAgICBzaC5ibHVyID0gKCFpc05hTihwYXJzZUludChhcnJbMl0sIDEwKSk/YXJyWzJdOicwcHgnKTtcbiAgICAgICAgICAgICAgICBzaC5zcHJlYWQgPSAoIWlzTmFOKHBhcnNlSW50KGFyclszXSwgMTApKT9hcnJbM106JzBweCcpO1xuICAgICAgICAgICAgICAgIHNoLmNvbG9yID0gaXNOYU4ocGFyc2VJbnQoYXJyW2Fyci5sZW5ndGgtMV0pKSA/IGFyclthcnIubGVuZ3RoLTFdIDogJ3RyYW5zcGFyZW50JztcbiAgICAgICAgICAgICAgICByZXR1cm4gc2g7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5pZCA9IGNvbmZpZy5pZDtcbiAgICAgICAgfSxcbiAgICAgICAgbnVtYmVyOmZ1bmN0aW9uKHByb3RvLCBjb25maWcpe1xuICAgICAgICAgICAgdmFyIGZpZWxkID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKCcnLCAnbnVtYmVyJyk7XG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcbiAgICAgICAgICAgIHZhciBsYWJlbCA9IG13LnByb3BFZGl0b3IuaGVscGVycy5sYWJlbChjb25maWcubGFiZWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKGxhYmVsKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChmaWVsZCk7XG4gICAgICAgICAgICBmaWVsZC5vbmlucHV0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgICQocHJvdG8pLnRyaWdnZXIoJ2NoYW5nZScsIFtjb25maWcuaWQsIHRoaXMudmFsdWVdKTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlPWZ1bmN0aW9uKHZhbHVlKXtcbiAgICAgICAgICAgICAgICBmaWVsZC52YWx1ZSA9IHBhcnNlSW50KHZhbHVlLCAxMCk7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdmFsdWU7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5pZCA9IGNvbmZpZy5pZDtcbiAgICAgICAgfSxcbiAgICAgICAgY29sb3I6ZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgZmllbGQgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMuZmllbGQoJycsICd0ZXh0Jyk7XG4gICAgICAgICAgICBpZihmaWVsZC50eXBlICE9PSAnY29sb3InKXtcbiAgICAgICAgICAgICAgICBtdy5jb2xvclBpY2tlcih7XG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6ZmllbGQsXG4gICAgICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBjb25maWcucG9zaXRpb24gfHwgJ2JvdHRvbS1jZW50ZXInLFxuICAgICAgICAgICAgICAgICAgICBvbmNoYW5nZTpmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICAgICAgJChwcm90bykudHJpZ2dlcignY2hhbmdlJywgW2NvbmZpZy5pZCwgZmllbGQudmFsdWVdKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGhvbGRlciA9IG13LnByb3BFZGl0b3IuaGVscGVycy53cmFwcGVyKCk7XG4gICAgICAgICAgICB2YXIgbGFiZWwgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMubGFiZWwoY29uZmlnLmxhYmVsKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChsYWJlbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQoZmllbGQpO1xuICAgICAgICAgICAgZmllbGQub25pbnB1dCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdGhpcy52YWx1ZTtcbiAgICAgICAgICAgICAgICAkKHByb3RvKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29uZmlnLmlkLCB0aGlzLnZhbHVlXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIGZpZWxkLnZhbHVlID0gdmFsdWU7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdmFsdWVcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLmlkID0gY29uZmlnLmlkXG4gICAgICAgIH0sXG4gICAgICAgIHNlbGVjdDpmdW5jdGlvbihwcm90bywgY29uZmlnKXtcbiAgICAgICAgICAgIHZhciBmaWVsZCA9IG13LnByb3BFZGl0b3IuaGVscGVycy5maWVsZCgnJywgJ3NlbGVjdCcsIGNvbmZpZy5vcHRpb25zKTtcbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGFiZWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKGZpZWxkKTtcbiAgICAgICAgICAgIGZpZWxkLm9uY2hhbmdlID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB0aGlzLnZhbHVlO1xuICAgICAgICAgICAgICAgICQocHJvdG8pLnRyaWdnZXIoJ2NoYW5nZScsIFtjb25maWcuaWQsIHRoaXMudmFsdWVdKTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIGZpZWxkLnZhbHVlID0gdmFsdWU7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdmFsdWVcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLmlkID0gY29uZmlnLmlkO1xuICAgICAgICB9LFxuICAgICAgICBmaWxlOmZ1bmN0aW9uKHByb3RvLCBjb25maWcpe1xuICAgICAgICAgICAgaWYoY29uZmlnLm11bHRpcGxlID09PSB0cnVlKXtcbiAgICAgICAgICAgICAgICBjb25maWcubXVsdGlwbGUgPSA5OTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKCFjb25maWcubXVsdGlwbGUpe1xuICAgICAgICAgICAgICAgIGNvbmZpZy5tdWx0aXBsZSA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGNyZWF0ZUJ1dHRvbiA9IGZ1bmN0aW9uKGltYWdlVXJsLCBpLCBwcm90byl7XG4gICAgICAgICAgICAgICAgaW1hZ2VVcmwgPSBpbWFnZVVybCB8fCAnJztcbiAgICAgICAgICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAndXBsb2FkLWJ1dHRvbi1wcm9wIG13LXVpLWJveCBtdy11aS1ib3gtY29udGVudCc7XG4gICAgICAgICAgICAgICAgdmFyIGJ0biA9ICBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgICAgICAgICAgYnRuLmNsYXNzTmFtZSA9ICgnbXctdWktYnRuJyk7XG4gICAgICAgICAgICAgICAgYnRuLmlubmVySFRNTCA9ICgnPHNwYW4gY2xhc3M9XCJtdy1pY29uLXVwbG9hZFwiPjwvc3Bhbj4nKTtcbiAgICAgICAgICAgICAgICBidG4uc3R5bGUuYmFja2dyb3VuZFNpemUgPSAnY292ZXInO1xuICAgICAgICAgICAgICAgIGJ0bi5zdHlsZS5iYWNrZ3JvdW5kQ29sb3IgPSAndHJhbnNwYXJlbnQnO1xuICAgICAgICAgICAgICAgIGVsLnN0eWxlLmJhY2tncm91bmRTaXplID0gJ2NvdmVyJztcbiAgICAgICAgICAgICAgICBidG4uX3ZhbHVlID0gaW1hZ2VVcmw7XG4gICAgICAgICAgICAgICAgYnRuLl9pbmRleCA9IGk7XG4gICAgICAgICAgICAgICAgaWYoaW1hZ2VVcmwpe1xuICAgICAgICAgICAgICAgICAgICBlbC5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UgPSAndXJsKCcgKyBpbWFnZVVybCArICcpJztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgYnRuLm9uY2xpY2sgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBtdy5maWxlV2luZG93KHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHR5cGVzOidpbWFnZXMnLFxuICAgICAgICAgICAgICAgICAgICAgICAgY2hhbmdlOmZ1bmN0aW9uKHVybCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoIXVybCkgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHVybCA9IHVybC50b1N0cmluZygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSB8fCBbXTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF1bYnRuLl9pbmRleF0gPSB1cmw7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZWwuc3R5bGUuYmFja2dyb3VuZEltYWdlID0gJ3VybCgnICsgdXJsICsgJyknO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJ0bi5fdmFsdWUgPSB1cmw7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUucmVmYWN0b3IoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB2YXIgY2xvc2UgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XG4gICAgICAgICAgICAgICAgY2xvc2UuY2xhc3NOYW1lID0gJ213LWJhZGdlIG13LWJhZGdlLWltcG9ydGFudCc7XG4gICAgICAgICAgICAgICAgY2xvc2UuaW5uZXJIVE1MID0gJzxzcGFuIGNsYXNzPVwibXctaWNvbi1jbG9zZVwiPjwvc3Bhbj4nO1xuXG4gICAgICAgICAgICAgICAgY2xvc2Uub25jbGljayA9IGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5yZW1vdmUoZWwpO1xuICAgICAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICBlbC5hcHBlbmRDaGlsZChjbG9zZSk7XG4gICAgICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoYnRuKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLnJlbW92ZSA9IGZ1bmN0aW9uIChpKSB7XG4gICAgICAgICAgICAgICAgaWYodHlwZW9mIGkgPT09ICdudW1iZXInKXtcbiAgICAgICAgICAgICAgICAgICAgJCgnLnVwbG9hZC1idXR0b24tcHJvcCcsIGVsKS5lcShpKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgJChpKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgc2NvcGUucmVmYWN0b3IoKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuYWRkSW1hZ2VCdXR0b24gPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGlmKGNvbmZpZy5tdWx0aXBsZSl7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYWRkQnRuID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYWRkQnRuLmNsYXNzTmFtZSA9ICdtdy11aS1saW5rJztcbiAgICAgICAgICAgICAgICAgICAgLy90aGlzLmFkZEJ0bi5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJtdy1pY29uLXBsdXNcIj48L3NwYW4+JztcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5hZGRCdG4uaW5uZXJIVE1MID0gbXcubXNnLmFkZEltYWdlO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFkZEJ0bi5vbmNsaWNrID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLmFwcGVuZENoaWxkKGNyZWF0ZUJ1dHRvbih1bmRlZmluZWQsIDAsIHByb3RvKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS5tYW5hZ2VBZGRJbWFnZUJ1dHRvbigpO1xuICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQodGhpcy5hZGRCdG4pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMubWFuYWdlQWRkSW1hZ2VCdXR0b24gPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBpc1Zpc2libGUgPSAkKCcudXBsb2FkLWJ1dHRvbi1wcm9wJywgdGhpcy5ub2RlKS5sZW5ndGggPCBjb25maWcubXVsdGlwbGU7XG4gICAgICAgICAgICAgICAgdGhpcy5hZGRCdG4uc3R5bGUuZGlzcGxheSA9IGlzVmlzaWJsZSA/ICdpbmxpbmUtYmxvY2snIDogJ25vbmUnO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIGJ0biA9IGNyZWF0ZUJ1dHRvbih1bmRlZmluZWQsIDAsIHByb3RvKTtcbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGFiZWwpO1xuICAgICAgICAgICAgdmFyIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICBlbC5jbGFzc05hbWUgPSAnbXctdWktYm94LWNvbnRlbnQnO1xuICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoYnRuKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmRDaGlsZChlbCk7XG5cbiAgICAgICAgICAgIHRoaXMuYWRkSW1hZ2VCdXR0b24oKTtcbiAgICAgICAgICAgIHRoaXMubWFuYWdlQWRkSW1hZ2VCdXR0b24oKTtcblxuICAgICAgICAgICAgaWYoJC5mbi5zb3J0YWJsZSl7XG4gICAgICAgICAgICAgICAgJChlbCkuc29ydGFibGUoe1xuICAgICAgICAgICAgICAgICAgICB1cGRhdGU6ZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnJlZmFjdG9yKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cblxuXG5cbiAgICAgICAgICAgIHRoaXMucmVmYWN0b3IgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IFtdO1xuICAgICAgICAgICAgICAgICQoJy5tdy11aS1idG4nLCBlbCkuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICB2YWwucHVzaCh0aGlzLl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgdGhpcy5tYW5hZ2VBZGRJbWFnZUJ1dHRvbigpO1xuICAgICAgICAgICAgICAgIGlmKHZhbC5sZW5ndGggPT09IDApe1xuICAgICAgICAgICAgICAgICAgICB2YWwgPSBbJyddO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWw7XG4gICAgICAgICAgICAgICAgJChwcm90bykudHJpZ2dlcignY2hhbmdlJywgW2NvbmZpZy5pZCwgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdXSk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIHZhbHVlID0gdmFsdWUgfHwgWycnXTtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWx1ZTtcbiAgICAgICAgICAgICAgICAkKCcudXBsb2FkLWJ1dHRvbi1wcm9wJywgaG9sZGVyKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBpZih0eXBlb2YgdmFsdWUgPT09ICdzdHJpbmcnKXtcbiAgICAgICAgICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoY3JlYXRlQnV0dG9uKHZhbHVlLCAwLCBwcm90bykpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgICAgICAkLmVhY2godmFsdWUsIGZ1bmN0aW9uIChpbmRleCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoY3JlYXRlQnV0dG9uKHRoaXMsIGluZGV4LCBwcm90bykpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB0aGlzLm1hbmFnZUFkZEltYWdlQnV0dG9uKCk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5pZCA9IGNvbmZpZy5pZDtcbiAgICAgICAgfSxcbiAgICAgICAgaWNvbjogZnVuY3Rpb24ocHJvdG8sIGNvbmZpZyl7XG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLndyYXBwZXIoKTtcblxuICAgICAgICAgICAgdmFyIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgICAgICAgICAgZWwuY2xhc3NOYW1lID0gXCJtdy11aS1idG4gbXctdWktYnRuLW1lZGl1bSBtdy11aS1idG4tbm90aWZpY2F0aW9uIG13LXVpLWJ0bi1vdXRsaW5lXCI7XG4gICAgICAgICAgICB2YXIgZWxUYXJnZXQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpJyk7XG5cbi8qICAgICAgICAgICAgdmFyIHNlbGVjdG9yID0gbXcuaWNvblNlbGVjdG9yLmljb25Ecm9wZG93bihob2xkZXIsIHtcbiAgICAgICAgICAgICAgICBvbmNoYW5nZTogZnVuY3Rpb24gKGljKSB7XG4gICAgICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IGljO1xuICAgICAgICAgICAgICAgICAgICAkKHByb3RvKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29uZmlnLmlkLCBpY10pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgbW9kZTogJ3JlbGF0aXZlJyxcbiAgICAgICAgICAgICAgICB2YWx1ZTogJydcbiAgICAgICAgICAgIH0pOyovXG5cbiAgICAgICAgICAgIGVsLm9uY2xpY2sgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcGlja2VyLmRpYWxvZygpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIG13Lmljb25Mb2FkZXIoKS5pbml0KCk7XG4gICAgICAgICAgICB2YXIgcGlja2VyID0gbXcuaWNvblBpY2tlcih7aWNvbk9wdGlvbnM6IGZhbHNlfSk7XG4gICAgICAgICAgICBwaWNrZXIudGFyZ2V0ID0gZWxUYXJnZXQ7XG4gICAgICAgICAgICBwaWNrZXIub24oJ3NlbGVjdCcsIGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgZGF0YS5yZW5kZXIoKTtcbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSBwaWNrZXIudGFyZ2V0Lm91dGVySFRNTDtcbiAgICAgICAgICAgICAgICAkKHByb3RvKS50cmlnZ2VyKCdjaGFuZ2UnLCBbY29uZmlnLmlkLCBwaWNrZXIudGFyZ2V0Lm91dGVySFRNTF0pO1xuICAgICAgICAgICAgICAgIHBpY2tlci5kaWFsb2coJ2hpZGUnKTtcbiAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG5cbiAgICAgICAgICAgICQoZWwpLnByZXBlbmQoZWxUYXJnZXQpO1xuICAgICAgICAgICAgJChob2xkZXIpLnByZXBlbmQoZWwpO1xuICAgICAgICAgICAgJChob2xkZXIpLnByZXBlbmQobGFiZWwpO1xuXG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIGlmKHBpY2tlciAmJiBwaWNrZXIudmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgICAgcGlja2VyLnZhbHVlKHZhbHVlKTtcblxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBwcm90by5fdmFsU2NoZW1hW2NvbmZpZy5pZF0gPSB2YWx1ZTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICB0aGlzLmlkID0gY29uZmlnLmlkO1xuXG4gICAgICAgIH0sXG4gICAgICAgIHJpY2h0ZXh0OmZ1bmN0aW9uKHByb3RvLCBjb25maWcpe1xuICAgICAgICAgICAgdmFyIGZpZWxkID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmZpZWxkKCcnLCAndGV4dGFyZWEnKTtcbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBtdy5wcm9wRWRpdG9yLmhlbHBlcnMud3JhcHBlcigpO1xuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcucHJvcEVkaXRvci5oZWxwZXJzLmxhYmVsKGNvbmZpZy5sYWJlbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kQ2hpbGQobGFiZWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZENoaWxkKGZpZWxkKTtcbiAgICAgICAgICAgICQoZmllbGQpLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHByb3RvLl92YWxTY2hlbWFbY29uZmlnLmlkXSA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgJChwcm90bykudHJpZ2dlcignY2hhbmdlJywgW2NvbmZpZy5pZCwgdGhpcy52YWx1ZV0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLm5vZGUgPSBob2xkZXI7XG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24odmFsdWUpe1xuICAgICAgICAgICAgICAgIGZpZWxkLnZhbHVlID0gdmFsdWU7XG4gICAgICAgICAgICAgICAgdGhpcy5lZGl0b3Iuc2V0Q29udGVudCh2YWx1ZSwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgcHJvdG8uX3ZhbFNjaGVtYVtjb25maWcuaWRdID0gdmFsdWU7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgdGhpcy5pZCA9IGNvbmZpZy5pZDtcbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICBoZWlnaHQ6IDEyMCxcbiAgICAgICAgICAgICAgICBtb2RlOiAnZGl2JyxcbiAgICAgICAgICAgICAgICBzbWFsbEVkaXRvcjogZmFsc2UsXG4gICAgICAgICAgICAgICAgY29udHJvbHM6IFtcbiAgICAgICAgICAgICAgICAgICAgW1xuICAgICAgICAgICAgICAgICAgICAgICAgJ2JvbGQnLCAnaXRhbGljJyxcbiAgICAgICAgICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBncm91cDoge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpY29uOiAnbWRpIHhtZGktZm9ybWF0LWJvbGQnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250cm9sczogWyd1bmRlcmxpbmUnLCAnc3RyaWtlVGhyb3VnaCcsICdyZW1vdmVGb3JtYXQnXVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH0sXG5cbiAgICAgICAgICAgICAgICAgICAgICAgICd8JywgJ2FsaWduJywgJ3wnLCAndGV4dENvbG9yJywgJ3RleHRCYWNrZ3JvdW5kQ29sb3InLCAnfCcsICdsaW5rJywgJ3VubGluaydcbiAgICAgICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBdXG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgY29uZmlnLm9wdGlvbnMgPSBjb25maWcub3B0aW9ucyB8fCB7fTtcbiAgICAgICAgICAgIHRoaXMuZWRpdG9yID0gbXcuRWRpdG9yKCQuZXh0ZW5kKHt9LCBkZWZhdWx0cywgY29uZmlnLm9wdGlvbnMsIHtzZWxlY3RvcjogZmllbGR9KSk7XG4gICAgICAgIH1cbiAgICB9XG59O1xuIl0sInNvdXJjZVJvb3QiOiIifQ==