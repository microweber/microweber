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
            var url = scope.settings.url + '?' + new URLSearchParams(params || {}).toString();
            xhr.open("GET", url, true);
            xhr.send();
        };

        var defaults = {
            multiselect: true,
            selectable: true,
            canSelectFolder: false,
            options: true,
            element: null,
            query: {
                order: 'asc',
                sort: 'date',
                keyword: '',
            },
            requestData: defaultRequest,
            url: mw.settings.site_url + 'api/file-manager/list',
            viewType: 'list' // 'list' | 'grid'
        };

        var _e = {};


        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.settings = mw.object.extend({}, defaults, options);

        var table, tableHeader, tableBody;
        var _viewType = this.settings.viewType;

        var _checkName = 'select-fm-' + (new Date().getTime());

        var globalcheck;

        var _check = function (name) {
            var input = document.createElement('input');
            input.type = (scope.settings.multiselect ? 'checkbox' : 'radio');
            input.name = name || _checkName;
            var root = mw.element('<label class="mw-ui-check"><span></span></label>');
            root.prepend(input);
            return {
                root: root,
                input: mw.element(input),
            };
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

        var _renameHandle = function (item) {
            mw.prompt(mw.lang('Enter new name'), function(){

            }, item.name);

        };

        var _downloadHandle = function (item) {

        };


        var _copyUrlHandle = function (item) {
            mw.tools.copy(item.url);
        };

        var _deleteHandle = function (item) {
            mw.confirm(mw.lang('Are you sure') + '?', function (){

            });
        };

        var _selectedUI = function () {
            scope.root.removeClass('mw-fm-all-selected', 'mw-fm-none-selected', 'mw-fm-part-selected');
            if(scope.areAllSelected()) {
                scope.root.addClass('mw-fm-all-selected');
                globalcheck.input.prop('checked', true);
                globalcheck.input.prop('indeterminate', false);
            } else if(scope.areNoneSelected()) {
                scope.root.addClass('mw-fm-none-selected');
                globalcheck.input.prop('checked', false);
                globalcheck.input.prop('indeterminate', false);
            }  else {
                scope.root.addClass('mw-fm-part-selected');
                globalcheck.input.prop('checked', false);
                globalcheck.input.prop('indeterminate', true);
            }
        };


        var createOptions = function (item) {
            var options = [
                { label: 'Rename', action: _renameHandle, match: function (item) { return true } },
                { label: 'Download', action: _downloadHandle, match: function (item) { return item.type === 'file'; } },
                { label: 'Copy url', action: _copyUrlHandle, match: function (item) { return true } },
                { label: 'Delete', action: _deleteHandle, match: function (item) { return true } },
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
            setTimeout(function (){
                _selectedUI();
            }, 100);
            this.dispatch('dataUpdated', data);
        };

        this.getItems = function () {
            return this.getData().data;
        };

        this.getData = function () {
            return this._data;
        };

        this.requestData = function (params) {
            this.settings.query = params;
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


        var _activeSort = {
            sort: this.settings.query.sort || 'date',
            order: this.settings.query.order || 'asc',
        };

        this.sort = function (by, order, _request) {
            if(typeof _request === 'undefined') {
                _request = true;
            }
            if(!order){
                if(by === _activeSort.sort) {
                    if(_activeSort.order === 'asc') {
                        order = 'desc';
                    } else {
                        order = 'asc';
                    }
                } else {
                    order = 'asc';
                }
            }
            _activeSort.sort = by;
            _activeSort.order = order;
            this.settings.query.sort = _activeSort.sort;
            this.settings.query.order = _activeSort.order;

            mw.element('[data-sortable]', this.root).each(function (){
                this.classList.remove('asc', 'desc');
                if(this.dataset.sortable === _activeSort.sort) {
                    this.classList.add(_activeSort.order);
                }
            });


            if(_request) {
                this.requestData(this.settings.query);
            }
        };

        this.singleListView = function (item) {
            var row = mw.element({ tag: 'tr' });
            var cellImage = mw.element({ tag: 'td', content: _image(item)  });
            var cellName = mw.element({ tag: 'td', content: item.name  });
            var cellSize = mw.element({ tag: 'td', content: _size(item) });




            var cellmodified = mw.element({ tag: 'td', content: userDate(item.modified)  });






            if(this.settings.selectable) {
                if (this.settings.canSelectFolder || item.type === 'file') {
                    var check =  _check();
                    check.input.on('change', function () {
                         scope[!this.checked ? 'unselect' : 'select'](item);
                        _selectedUI();
                    });
                    row.append( mw.element({ tag: 'td', content: check.root }));
                } else {
                    row.append( mw.element({ tag: 'td'  }));
                }


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
            scope.getItems().forEach(function (item) {
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






        var afterSelect = function (obj, state) {
            var curr = rows.find(function (row){
                return row.data === obj;
            });
            if(curr) {
                var input = curr.row.find('input');
                input.prop('checked', state);
            }
            _selectedUI();
        };


        this.getSelected = function () {
            return this._selected;
        };


        this.areNoneSelected = function () {
            return this.getSelected().length === 0;
        };

        this.areAllSelected = function () {
             return this.getItems().length === this.getSelected().length;
        };

        this.selectAll = function () {
            rows.forEach(function (rowItem){
                scope.select(rowItem.data);
            });
        };
        this.selectNone = function () {
            rows.forEach(function (rowItem){
                scope.unselect(rowItem.data);
            });
        };

        this.selectAllToggle = function () {
            if(this.areAllSelected()){
                this.selectNone();
            } else {
                this.selectAll();
            }
        };

        this.select = function (obj) {

            if (this.settings.multiselect) {
                pushUnique(obj);
            } else {
                this._selected = [obj];
            }
            afterSelect(obj, true);
        };

        this.unselect = function (obj) {
            this._selected.splice(this._selected.indexOf(obj), 1);
            afterSelect(obj, false);
        };

        var createListViewHeader = function () {
            var thCheck;
            if (scope.settings.selectable && scope.settings.multiselect ) {
                globalcheck = _check('select-fm-global-' + (new Date().getTime()));
                globalcheck.root.addClass('mw-file-manager-select-all-check');
                globalcheck.input.on('input', function () {
                    scope.selectAllToggle();
                });
                thCheck = mw.element({ tag: 'th', content: globalcheck.root  }).addClass('mw-file-manager-select-all-heading');
            } else {
                thCheck = mw.element({ tag: 'th', }).addClass('mw-file-manager-select-all-heading');
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


            thName.dataset('sortable', 'name').on('click', function (){ scope.sort(this.dataset.sortable) });
            thSize.dataset('sortable', 'size').on('click', function (){ scope.sort(this.dataset.sortable) });
            thModified.dataset('sortable', 'modified').on('click', function (){ scope.sort(this.dataset.sortable) });

            return tableHeader;
        };

        var _view = function () {
            table =  mw.element('<table class="mw-file-manager-view-table" />');
            table
                .append(createListViewHeader())
                .append(listViewBody());
            return table;
        };



        this.view = function (type) {
            if(!type) return _viewType;
            _viewType = type;
            var viewblock = _view(type);
            if(viewblock) {
                this.root.empty().append(viewblock);
            }
            this.root.dataset('view', _viewType);
        };


        var createRoot = function (){
            scope.root = mw.element({
                props: {
                    className: 'mw-file-manager-root'
                },
                /*encapsulate: false*/
            });
        };

        this.init = function (){
            createRoot();
            this.on('dataUpdated', function (res){
                scope.view(_viewType);
            });
            this.sort(this.settings.query.sort, this.settings.query.order, false);
            this.requestData(this.settings.query);
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
