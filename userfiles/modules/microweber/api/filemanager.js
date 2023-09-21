(function (){
    mw.require('filemanager.css');
    var FileManager = function (options) {

        var scope = this;

        options = options || {};

        var defaultRequest = function (params, callback, error) {
            var xhr = new XMLHttpRequest();
            if(!params) {
                params = {};
            }
            if(!params.limit) {
                params.limit = 50;
            }

            params.limit = parseFloat(params.limit);

            if(params.limit > 1000) {
                params.limit = 50;
            }

            if(scope.settings.accept) {
                params.filetypes = scope.originalAccept;
                params.extensions = scope.settings.accept;
            }

            scope.dispatch('beforeRequest', {xhr: xhr, params: params});
            xhr.onreadystatechange = function(e) {
                if (this.readyState === 4 && this.status === 200) {
                    callback.call(scope, JSON.parse(this.responseText), xhr);
                } else if(this.status !== 200 && this.readyState === 4) {
                    if(error) {
                        error.call(scope, e);
                    }
                }
            };
            xhr.addEventListener('error', function (e){
                if(error) {
                    error.call(scope, e);
                }
            });
            var url = scope.settings.url + '?' + new URLSearchParams(params || {}).toString();
            xhr.open("GET", url, true);
            xhr.send();
        };

        var plusIcon = function (color, margin) {
            if(!color) {
                color = 'white';
            }
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 472 472" style="width: 14px; margin-inline-end:' + margin + 'px;" xml:space="preserve"><path fill="'+color+'" d="M472 185H287V0H185v185H0v102h185v185h102V287h185V185z"/></svg>';
        };

        var defaultAddFile = function (file) {
            return new Promise(function (resolve, reject){
                var xhr = new XMLHttpRequest();
                scope.dispatch('beforeAddFile', {xhr: xhr, input: input});
                xhr.onreadystatechange = function(e) {
                    if (this.readyState === 4 && this.status === 200) {
                        resolve(JSON.parse(this.responseText));
                    } else if(this.status !== 200) {
                        reject(e);
                    }
                };
                xhr.addEventListener('error', function (e){
                    reject(e);
                });
                var url = scope.settings.url + '?path=' + scope.settings.query.path;
                xhr.open("PUT", url, true);
                var formData = new FormData();
                formData.append("file", file);
                xhr.send(formData);
            });
        };

        this.uploadFile = function (file) {
            return this.settings.addFile(file).then(function (){
                scope.refresh(true);
            });
        };

        this.methods = {
            pc: function (options) {
                var node = document.createElement('span');
                node.innerHTML = options.title;
                var upload = mw.upload({
                    multiple: false,
                    element: node,
                    on: {
                        fileAdded: function () {
                            scope.progress(5);
                        },
                        filesUploaded: function () {
                            scope.refresh(true);
                        },
                        progress: function (val) {
                            scope.progress(val.percent);
                        },
                        fileUploadError: function () {
                            scope.progress(false);
                        }
                    }
                });
                scope.on('pathChanged', function (path) {
                    upload.urlParam('path', path);
                });
                return node;
            },
            createFolder: function (options) {
                var node = document.createElement('span');
                node.innerHTML = options.title;
                node.addEventListener('click', function (){
                    mw.prompt('Folder name', function (val) {
                        var xhr = new XMLHttpRequest();
                        scope.loading(true);
                        xhr.onreadystatechange = function(e) {
                            if (this.readyState === 4 && this.status === 200) {
                                 scope.refresh(true);
                            } else if(this.status !== 200) {

                            }
                            scope.loading(false);
                        };
                        xhr.addEventListener('error', function (e){
                            scope.loading(false);
                        });
                        var params = {
                            path: scope.settings.query.path,
                            name: val,
                            new_folder: 1,
                        };
                        var url = route('api.file-manager.create-folder') + '?' + new URLSearchParams(params).toString();
                        xhr.open("POST", url, true);
                        xhr.send();
                    });
                });

                return node;
            }
        };


        var defaults = {
            multiselect: true,
            selectable: true,
            selectableRow: false,
            canSelectFolder: false,
            selectableFilter: null,
            options: false,
            element: null,
            query: {
                order: 'desc',
                orderBy: 'filemtime',
                path: '/'
            },
            backgroundColor: 'var(--tblr-bg-surface)',
            stickyHeader: false,
            requestData: defaultRequest,
            addFile: defaultAddFile,
            methods: [
                {title: 'Upload file', method: 'pc' },
                {title: 'Create folder', method: 'createFolder' },
            ],
            url: mw.settings.site_url + 'api/file-manager/list',
            viewType: 'list' // 'list' | 'grid'
        };

        var _e = {};


        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        var normalizeAccept = function (type) {

            scope.originalAccept = type;

            type = (type || '').trim().toLowerCase();
            if(!type) return '*';
            if (type === 'image' || type === 'images') return '.png,.gif,.jpg,.jpeg,.bmp,.svg,.ico';
            if (type === 'video' || type === 'videos') return '.mp4,.webm,.ogg,.wma,.mov,.wmv';
            if (type === 'document' || type === 'documents') return '.doc,.docx,.log,.pdf,.msg,.odt,.pages,' +
                '.rtf,.tex,.txt,.wpd,.wps,.pps,.ppt,.pptx,.xml,.htm,.html,.xlr,.xls,.xlsx';

            return '*';
        };

        this.settings = mw.object.extend({}, defaults, options);
        if(this.settings.accept) {
            this.settings.accept = normalizeAccept(this.settings.accept);
        }

        var table, tableHeader, tableBody;

        var _checkName = 'select-fm-' + (new Date().getTime());

        var globalcheck, _pathNode, _backNode;

        var _check = function (name) {
            var input = document.createElement('input');
            input.type = (scope.settings.multiselect ? 'checkbox' : 'radio');
            input.name = name || _checkName;
            input.className = 'form-check-input';
            var root = mw.element('<label class="form-check"><span></span></label>');
            root.prepend(input);

            ['click', 'mousedown', 'mouseup', 'touchstart', 'touchend'].forEach(function (ev){
                root.get(0).addEventListener(ev, function (e){
                   e.stopImmediatePropagation();
                   e.stopPropagation();
                });
            });

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




        var _createPaginationNavLink = function (i, label, curr) {
            if(!label) {
                label = i;
            }
            var lnk = mw.element('<li class="page-item"><a class="page-link">' + label + '</a></li>');

            lnk.find('a').on('click', function () {
                scope.setPage(i);
            });
            if(i === curr) {
                lnk.addClass('active');
            }
            return lnk;
        };



        var _createPaginationNav = function (array, curr, totalPages) {
            var wrapper = mw.element('<div class="pagination" />');
            if(curr > 1){
                wrapper.append(_createPaginationNavLink(1, '&laquo;', curr));
                wrapper.append(_createPaginationNavLink(curr-1, '&lsaquo;', curr));

            }
            array.forEach(function (i){
                if(i <= totalPages) {
                   wrapper.append(_createPaginationNavLink(i, i, curr));
                }
            });
            if(curr < totalPages){
                wrapper.append(_createPaginationNavLink(curr+1, '&rsaquo;'));
                wrapper.append(_createPaginationNavLink(totalPages, '&raquo;'));
            }
            return wrapper;
        }
        var createPagination = function (obj) {
            if(!scope.pagingNavigations) {
                scope.pagingNavigations = {
                    top: mw.element('<div class="mw-file-manager-paging mw-file-manager-paging-top" />'),
                    bottom: mw.element('<div class="mw-file-manager-paging mw-file-manager-paging-bottom" />')
                }
                // scope.tableWrapper.prepend(scope.pagingNavigations.top);
                scope.tableWrapper.append(scope.pagingNavigations.bottom);
            }
            var pg = obj.pagination;
            var array, curr = pg.currentPage;
            if (curr === 1 || curr === 2 || curr === 3) {
                array = [1,2,3,4,5];
            } else {
                array = [curr-2,curr-1,curr,curr+1,curr+2];
            }



            // scope.pagingNavigations.top.empty().append(_createPaginationNav(array, curr, pg.totalPages));
            scope.pagingNavigations.bottom.empty().append(_createPaginationNav(array, curr, pg.totalPages));
        };

        var _image = function (item) {
            if (item.type === 'folder') {
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-folder"><svg xmlns="http://www.w3.org/2000/svg" height="48" fill="currentColor" viewBox="0 96 960 960" width="48"><path d="M141 896q-24 0-42-18.5T81 836V316q0-23 18-41.5t42-18.5h280l60 60h340q23 0 41.5 18.5T881 376v460q0 23-18.5 41.5T821 896H141Zm0-580v520h680V376H456l-60-60H141Zm0 0v520-520Z"/></svg></span>';
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
                content: option.label,
                props: {
                    className: 'dropdown-item'
                }
            });
            el.on('click', function (e){
                e.stopPropagation()
                option.action(item);
            });
            return el;
        };

        var _renameHandle = function (item) {
            mw.prompt(mw.lang('Enter new name'), function(){

            }, item.name);

        };

        var _downloadHandle = function (item) {
            var a = document.createElement("a");
            a.href = item.url;
            a.setAttribute("download", item.name);
            a.click();
        };


        var _copyUrlHandle = function (item) {
            mw.tools.copy(item.url);
        };

        var _deleteHandle = function (items) {
            if(items && !items.length){
                items = [items];
            }
            items = items.map(itm => {
                return itm.path ? itm.path : itm;
            })
            mw.confirm(mw.lang('Are you sure') + '?', function (){
                var xhr = new XMLHttpRequest();
                scope.loading(true);
                xhr.onreadystatechange = function(e) {
                    if (this.readyState === 4 && this.status === 200) {
                        scope.refresh(true);
                    } else if(this.status !== 200) {

                    }
                    scope.loading(false)
                };
                xhr.addEventListener('error', function (e){
                    scope.loading(false)
                });
                var dt = {
                    paths:[...items],

                };
                var url = route('api.file-manager.delete');
                xhr.open("DELETE", url, true);
                var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
                if (tokenFromCookie) {
                    xhr.setRequestHeader('X-XSRF-TOKEN', tokenFromCookie);
                }
            //    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                xhr.setRequestHeader('Content-Type', 'application/json');



                xhr.send(JSON.stringify(dt));
            });
        };




        var _selectedUI = function () {
            if(!scope.settings.selectable || !scope.settings.multiselect) {
                return;
            }
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
             //   { label: 'Rename', action: _renameHandle, match: function (item) { return true } },
                { label: 'Download', action: _downloadHandle, match: function (item) { return item.type === 'file'; } },
                { label: 'Copy url', action: _copyUrlHandle, match: function (item) { return true } },
                { label: 'Delete', action: _deleteHandle, match: function (item) { return true } },
            ];
            var el = mw.element().addClass('mw-file-manager-list-item-options');
            el.append(mw.element({tag: 'span', content: ' <svg xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22" fill="currentColor"><path d="M207.858 624Q188 624 174 609.858q-14-14.141-14-34Q160 556 174.142 542q14.141-14 34-14Q228 528 242 542.142q14 14.141 14 34Q256 596 241.858 610q-14.141 14-34 14Zm272 0Q460 624 446 609.858q-14-14.141-14-34Q432 556 446.142 542q14.141-14 34-14Q500 528 514 542.142q14 14.141 14 34Q528 596 513.858 610q-14.141 14-34 14Zm272 0Q732 624 718 609.858q-14-14.141-14-34Q704 556 718.142 542q14.141-14 34-14Q772 528 786 542.142q14 14.141 14 34Q800 596 785.858 610q-14.141 14-34 14Z"/></svg>', props: {tooltip:'options'}}).addClass('btn btn-sm mw-file-manager-list-item-options-button'));
            var optsHolder = mw.element().addClass('dropdown-menu mw-file-manager-list-item-options-list');
            el.on('click', function (e){
                e.stopPropagation()
                var all = scope.root.get(0).querySelectorAll('.mw-file-manager-list-item-options.active');
                for (var i = 0; i < all.length; i++ ) {
                    if (all[i] !== this) {
                        all[i].classList.remove('active');
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


        var _data = {data: []};

        this.setData = function (data) {
            _data = data;
        };

        this.updateData = function (data) {
            this.setData(data);
            setTimeout(function (){
                _selectedUI();
            }, 100);
            this.dispatch('dataUpdated', data);
        };

        this.getSelectableItems = function () {
            return this.getItems().filter(function (itm){
                return (itm.type !== 'folder' || scope.settings.canSelectFolder) && scope.canSelect(itm);
            });
        };

        this.getItems = function () {
            return this.getData().data;
        };

        this.getData = function () {
            return _data;
        };

        var _progress;
        this.progress = function (state) {

            if(typeof state === 'string') {
                state = Number(state);
            }

            if(!_progress || !_progress.get(0).parentNode) {
                _progress = mw.element({
                    props: {
                        className: 'mw-file-manager-progress'
                    }
                });
                scope.root.prepend(_progress);
            }
            if(state) {
                mw.progress({element: _progress.get(0), action: 'Uploading...'}).set(state);
            } else {
                mw.progress({element: _progress.get(0)}).hide();
            }
            if(state === 100) {
                setTimeout(function (){
                    mw.progress({element: _progress.get(0)}).hide();
                }, 300)
            }
        };

        var _loader;
        this.loading = function (state) {
            if(!_loader || !_loader.get(0).parentNode) {
                _loader = mw.element({
                    props: {
                        className: 'mw-file-manager-spinner'
                    }
                });
                scope.root.prepend(_loader);
            }
            if(state) {
                mw.spinner({element: _loader.get(0), size: 32, decorate: false}).show();
            } else {
                mw.spinner({element: _loader.get(0)}).remove();
            }
        };


        this.isSearch = function () {
            return !!this.settings.query.keyword;
        };

        this.setPage = function (page) {
            this.settings.query.page = page;
            this.requestRender(this.settings.query);
        }

        this.requestData = function (params, cb) {
            this.settings.query = params;
            var _cb = function (data) {

                cb.call(undefined, data);
                createPagination(data)
                if(!data.data || data.data.length === 0) {
                    scope.root.addClass('no-results');
                    if(scope.isSearch()) {
                        _noResultsLabel(mw.lang('Nothing found'));
                        scope. root.addClass('no-results-search');
                    } else {
                        _noResultsLabel(mw.lang('This folder is empty'));
                        scope.root.removeClass('no-results-search');
                    }
                } else {
                    scope.root.removeClass('no-results');
                    scope.root.removeClass('no-results-search');
                }
                scope.loading(false, 1);
            };

            scope.loading(true);
            var err = function (er) {
                scope.loading(false, 2);
            };

            this.settings.requestData(
                params, _cb, err
            );
        };




        var noResultsContentLabel, noResultsContent;

        var _noResultsLabel = function (label) {
            noResultsContentLabel.html(label);
        };


        var _noResultsBlock = function () {

            noResultsContentLabel = mw.element({
                tag: 'h2',
                props: {
                    className: 'mw-file-manager-no-results-label'
                },
            });
            noResultsContent = mw.element( {
                tag: 'div',
                props: {
                    className: 'mw-file-manager-no-results-content',
                },
                content: noResultsContentLabel
            });
            var block = mw.element({
                props: {
                    className: 'mw-file-manager-no-results'
                },
                content: noResultsContent
            });
            scope.creteMethodsNode(noResultsContent.get(0), plusIcon('white', 10) + ' '  + mw.lang('Add'));
            return block;
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
            orderBy: this.settings.query.orderBy || 'filemtime',
            order: this.settings.query.order || 'desc',
        };

        this.requestRender = function (query) {
            this.requestData(query, function (res){
                scope.setData(res);
                scope.renderData();
            });
        }

        this.refresh = function (full){
            if(full) {
                this.requestRender(this.settings.query)
            } else {
                scope.renderData();
            }
        };

        this.sort = function (by, order, _request) {
            if(typeof _request === 'undefined') {
                _request = true;
            }
            if(!order){
                if(by === _activeSort.orderBy) {
                    if(_activeSort.order === 'asc') {
                        order = 'desc';
                    } else {
                        order = 'asc';
                    }
                } else {
                    order = 'asc';
                }
            }
            _activeSort.orderBy = by;
            _activeSort.order = order;
            this.settings.query.orderBy = _activeSort.orderBy;
            this.settings.query.order = _activeSort.order;

            mw.element('[data-sortable]', scope.root).each(function (){
                this.classList.remove('asc', 'desc');
                if(this.dataset.sortable === _activeSort.orderBy) {
                    this.classList.add(_activeSort.order);
                }
            });

            if(_request) {
                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }
        };

        this.search = function (keyword, _request) {
            if(typeof _request === 'undefined') {
                _request = true;
            }

            keyword = (keyword || '').trim();

            if(!keyword){
                mw.element('input', scope.root).val('');
                mw.element('.has-value', scope.root).removeClass('has-value');
                delete this.settings.query.keyword;
                this.sort('filemtime', 'desc', false);
            } else {
                this.settings.query.keyword = keyword;
                this.sort('keyword', 'desc', false);
            }

            mw.element('[data-sortable]', scope.root).each(function (){
                this.classList.remove('asc', 'desc');
                if(this.dataset.sortable === _activeSort.orderBy) {
                    this.classList.add(_activeSort.order);
                }
            });

            if(_request) {
                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }
        };

        this.canSelect = function (item) {
            if(typeof this.settings.selectableFilter === 'function') {
                return this.settings.selectableFilter(item)
            }
            return true;
        };




        this.acceptMatches = function(item) {
            if(item.type === 'folder' || !this.settings.accept  || this.settings.accept === '*') {
                return true;
            }

            const accept = this.settings.accept.split(',');
            const extension = `.${item.name.split('.').pop()}`;
            return accept.indexOf(extension) !== -1 || accept.indexOf(item.mimeType) !== -1

            return true;
        }

        this.singleListView = function (item) {

            var row = mw.element({ tag: 'tr', props: {className: `mw-file-manager-list-item-type--${item.type} mw-file-manager-list-item-matches--${this.acceptMatches(item)}` } });
            var cellImage = mw.element({ tag: 'td', content: _image(item), props: {className: 'mw-file-manager-list-item-thumb-image-cell'}  });
            var cellName = mw.element({ tag: 'td', content: item.name , props: {className: 'mw-file-manager-list-item-name-cell', title: item.name} });
            var cellSize = mw.element({ tag: 'td', content: _size(item), props: {className: 'mw-file-manager-list-item-size-cell'} });

            var cellmodified = mw.element({ tag: 'td', content: userDate(item.modified), props: {className: 'mw-file-manager-list-item-modified-cell'} });
            if(item.type === 'folder') {
                row.on('click', function (){
                    scope.path(scope.path() + '/' + item.name);
                });
            }
            if(this.settings.selectable) {
                if ((this.settings.canSelectFolder || item.type === 'file') && this.canSelect(item)) {
                    var check = _check();
                    if(scope.settings.selectableRow) {
                        row.on('click', function(e){
                            scope[check.input.get(0).checked ? 'unselect' : 'select'](item);
                            _selectedUI();
                            scope.dispatch('selectionChanged', scope.getSelected());
                        });
                    }
                    check.input.on('change', function () {
                         scope[!this.checked ? 'unselect' : 'select'](item);
                        _selectedUI();
                        scope.dispatch('selectionChanged', scope.getSelected());
                    });
                    row.append( mw.element({ tag: 'td', content: check.root, props: {className: 'mw-file-manager-list-item-check-cell'} }));
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
                var cellOptions = mw.element({ tag: 'td', content: createOptions(item) }).addClass('mw-file-manager-options-cell');
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
            scope.renderData();
            return tableBody;
        };

        this.renderData = function (){
            tableBody.empty();
            rows = [];
            this._selected = [];
            if(globalcheck) {
                globalcheck.input.prop('indeterminate', false);
                globalcheck.input.prop('checked', false);
            }

            let isLastFolder = false;
            scope.getItems().forEach(function (item) {
                var row = scope.singleListView(item);

                rows.push({data: item, row: row});
                tableBody.append(row);
                if(!isLastFolder && item.type === 'file') {
                    row.before('<hr class="mw-file-manager-list-item-type--types-delimitter">');
                    isLastFolder = true;
                }
            });
            var folders = tableBody.find('.mw-file-manager-list-item-type--folder');
            if(folders.nodes.length) {
                folders.nodes[folders.nodes.length - 1].classList.add('mw-file-manager-list-item-type--folder-last');
                mw.element(folders.nodes[folders.nodes.length - 1]).after
            }
        };


        this._selected = [];

        var pushUnique = function (obj) {
            if (scope._selected.indexOf(obj) === -1) {
                scope._selected.push(obj);
            }
        };


        var afterSelect = function (obj, state) {
            if(scope.settings.multiselect === false) {
                rows.forEach(function (r){
                    r.row.removeClass('selected');
                });
            }
            var curr = rows.find(function (row){
                return row.data === obj;
            });
            if(curr) {
                curr.row[state ? 'addClass' : 'removeClass']( 'selected' );
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
             return this.getSelectableItems().length === this.getSelected().length;
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
            if(obj.type === 'folder' && !this.settings.canSelectFolder) {
                return;
            }
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

        this.back = function (){
            var curr = this.settings.query.path;
            if(!curr || curr === '/') {
                return;
            }
            curr = curr.split('/');
            curr.pop();
            this.settings.query.path = curr.join('/');
            this.path(this.settings.query.path );
        };



        this.creteSearchNode = function (target) {








              var html = `<div class="row g-2 mw-file-manager-search">
              <div class="col">
                <input type="text" class="form-control" placeholder="Search">
              </div>
              <div class="col-auto">
                <button class="btn btn-icon mw-file-manager-search-button" aria-label="Button">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </button>
              </div>
            </div>`;

            var el = mw.element(html);
            if(target) {
                target.appendChild(el.get(0));
            }
            mw.element('input', el).on('input', function (){
                this.parentNode.classList[!this.value.trim() ? 'remove' : 'add']('has-value');
            });
            mw.element('.mw-file-manager-search-clear', el).on('click', function (){
                scope.search( '', true);
                mw.element('input', el).focus();
            });
            mw.element('.mw-file-manager-search-button', el).on('click', function (){
                scope.search( mw.element('input', el).val().trim() , true);
            });

            mw.element('input', el).on('keydown', function (e){
                if (e.key === 'Enter' || e.keyCode === 13) {
                    scope.search( mw.element('input', el).val().trim(), true);
                }
            });
            return el.get(0);
        };

        this.creteMethodsNode = function (target, label) {
            if(!this.settings.methods) {
                return;
            }
            target = target || document.createElement('div');

            var root = mw.element({
                props: {
                    className: 'mw-file-manager-create-methods-dropdown'
                }
            });
            var addButton = mw.element({
                props: {
                    className: 'btn btn-success mw-file-manager-create-methods-dropdown-add',
                    innerHTML: label || plusIcon('white')
                }
            });

            addButton.on('click', function (){
                this.parentElement.classList.toggle('active');
            });

            addButton.get(0).ownerDocument.body.addEventListener('click', function (e){
                if (!addButton.get(0).contains(e.target)) {
                    addButton.get(0).parentElement.classList.remove('active');
                }
            });

            var selectElContent = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar-actions-content'
                }
            });
            this.settings.methods.forEach(function (method){
                var node = mw.element({
                    props: {
                        className: 'mw-file-manager-add-node'
                    }
                });
                node.append(scope.methods[method.method](method));
                selectElContent.append(node);
            });
            target.appendChild(root.get(0));
            root.append(addButton);
            root.append(selectElContent);
            return target;
        };



        var createTopBar = function (){
            var topBar = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar'
                }
            });

            var selectEl = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar-actions'
                }
            });


            scope.creteMethodsNode(selectEl.get(0));
            scope.creteSearchNode(selectEl.get(0));


            var viewTypeSelectorRoot = mw.element({
                props: {
                    className: 'mw-file-manager-bar-view-type-selector'
                }
            });

            var viewTypeSelector = mw.element(`
            <div class="btn-group">
                <span class="btn  btn-icon" data-view-type="grid" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24" fill="currentColor" class="icon icon-tabler"><path d="M120 536V216h320v320H120Zm0 400V616h320v320H120Zm400-400V216h320v320H520Zm0 400V616h320v320H520ZM200 456h160V296H200v160Zm400 0h160V296H600v160Zm0 400h160V696H600v160Zm-400 0h160V696H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z"/></svg>
                </span>

                <span class="btn btn-icon " data-view-type="list" data-bs-toggle="tooltip" data-bs-placement="top" title="List">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24" fill="currentColor" class="icon icon-tabler"><path d="M360 856v-80h480v80H360Zm0-240v-80h480v80H360Zm0-240v-80h480v80H360ZM200 896q-33 0-56.5-23.5T120 816q0-33 23.5-56.5T200 736q33 0 56.5 23.5T280 816q0 33-23.5 56.5T200 896Zm0-240q-33 0-56.5-23.5T120 576q0-33 23.5-56.5T200 496q33 0 56.5 23.5T280 576q0 33-23.5 56.5T200 656Zm0-240q-33 0-56.5-23.5T120 336q0-33 23.5-56.5T200 256q33 0 56.5 23.5T280 336q0 33-23.5 56.5T200 416Z"/></svg>
                </span>
            </div>`);



          viewTypeSelectorRoot.append(viewTypeSelector);


            viewTypeSelector.find('.btn').on('click', function (val){
                scope.viewType( this.dataset.viewType );
            });

            topBar.append(selectEl);
            selectEl.append(viewTypeSelectorRoot);
            return topBar.get(0);

        };

        var createMainBar = function (){


            _backNode = mw.element({
                tag: 'button',

                props: {
                    className: 'btn btn-link btn-sm',
                    innerHTML: `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
   <path d="M5 12l14 0"></path>
   <path d="M5 12l6 6"></path>
   <path d="M5 12l6 -6"></path>
</svg>
                    ${mw.lang('Back')}`
                }
            });
            _backNode.on('click', function (){
                scope.back();
            });


            _pathNode = mw.element({
                tag: 'ol',
                props: {
                    className: 'breadcrumb mw-file-manager-bar-path'
                }
            });
            var _pathNodeRoot = mw.element({
                props: {
                    className: 'mw-file-manager-bar-path-root'
                }
            });
            _pathNodeRoot.append(_pathNode)

            var bar = mw.element({
                props: {
                    className: 'mw-file-manager-bar'
                }
            });


            var multiSelectMenuTemplate = mw.element(`
                <div class="mw-file-manager--multiselect--context-actions">
                    <button type="button" class="btn btn-icon" data-action="multiSelectDownloadAll" data-bs-toggle="tooltip" data-bs-placement="top" title="Download">

<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24" fill="currentColor"><path d="M240 896q-33 0-56.5-23.5T160 816V696h80v120h480V696h80v120q0 33-23.5 56.5T720 896H240Zm240-160L280 536l56-58 104 104V256h80v326l104-104 56 58-200 200Z"/></svg>

                    </button>
                    <button type="button" class="btn btn-icon btn-danger" data-action="multiSelectDeleteAll"  data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">

                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24" fill="currentColor"><path d="M280 936q-33 0-56.5-23.5T200 856V336h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680 936H280Zm400-600H280v520h400V336ZM360 776h80V416h-80v360Zm160 0h80V416h-80v360ZM280 336v520-520Z"/></svg>

                    </button>
                </div>
            `);


            this.multiSelectDeleteAll = function() {
                const selected = scope.getSelected();
                _deleteHandle(selected)
            }
            this.multiSelectDownloadAll = function() {
                const selected = scope.getSelected().filter(itm => itm.type === 'file');
                selected.forEach(itm => {
                    _downloadHandle(itm)
                })

            }

            bar
                .append(multiSelectMenuTemplate)
                .append(_backNode)
                .append(_pathNodeRoot)

                multiSelectMenuTemplate.get(0).querySelectorAll('.btn').forEach(node => {
                    node.addEventListener('click', e => {
                        this[node.dataset.action]();
                    });
                });


            return bar;
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

            var ths = [thCheck, thImage, thName, thSize, thModified];

            if(scope.settings.options) {
                ths.push(mw.element({ tag: 'th', content: ''  }).addClass('mw-file-manager-options-cell'));
            }

                ths.forEach(function (th){
                    th.css('backgroundColor', scope.settings.backgroundColor);
                    if(typeof scope.settings.stickyHeader === 'number') {
                        th.css('top', scope.settings.stickyHeader);
                    }
                });

            var tr = mw.element({
                tag: 'tr',
                content: ths
            });
            tableHeader =  mw.element({
                tag: 'thead',
                content: tr
            });
            tableHeader.addClass('sticky-' + (scope.settings.stickyHeader !== false && scope.settings.stickyHeader !== undefined));
            tableHeader.css('backgroundColor', scope.settings.backgroundColor);
            thName.dataset('sortable', 'basename').on('click', function (){ scope.sort(this.dataset.sortable) });
            thSize.dataset('sortable', 'filesize').on('click', function (){ scope.sort(this.dataset.sortable) });
            thModified.dataset('sortable', 'filemtime').on('click', function (){ scope.sort(this.dataset.sortable) });

            return tableHeader;
        };

        var _view = function () {
            if(!table) {
                table =  mw.element('<table class="mw-file-manager-view-table" />');
                table.css('backgroundColor', scope.settings.backgroundColor);
                table
                    .append(createListViewHeader())
                    .append(listViewBody());
            } else {
                scope.renderData();
            }
            var tableWrap = mw.element('<div class="mw-file-manager-view-table-wrap" />');
            tableWrap.append(table);
            scope.tableWrapper = tableWrap;
            return tableWrap;
        };

        this.view = function () {
            this.root
                .empty()
                .append(createTopBar())
                .append(createMainBar())
                .append(_view())
                .append(_noResultsBlock());
        };

        var createRoot = function (){
            scope.root = mw.element({
                props: {
                    className: 'mw-file-manager-root'
                }
            });
        };

        this.viewType = function (viewType, _forced) {
            if (!viewType) {
                return this.settings.viewType;
            }
            if(viewType !== this.settings.viewType || _forced) {
                this.settings.viewType = viewType;
                this.root.dataset('view', this.settings.viewType);

                Array.from(this.root.get(0).querySelectorAll('[data-view-type]')).forEach(function(node){
                    node.classList[node.dataset.viewType === viewType ? 'add' : 'remove']('active');
                });
                this.dispatch('viewTypeChange', this.settings.viewType);

            }
        };

        var pathItem = function (path, html){
            var node = document.createElement('li');
            node.className = 'breadcrumb-item';
            node.innerHTML = html || path.split('/').pop();
            node.addEventListener('click', function (e){
                e.preventDefault();
                scope.path(path);
            });
            return node;
        };

        this.path = function (path, _request){
            if(typeof _request === 'undefined') {
                _request = true;
            }
            if(typeof path === 'undefined'){
                return this.settings.query.path;
            }
            path = (path || '').trim();
            this.settings.query.page = 1;
            this.settings.query.path = path;
            delete this.settings.query.keyword;
            mw.element('input', scope.root).val('');
            mw.element('.has-value', scope.root).removeClass('has-value');
            scope.dispatch('pathChanged', this.settings.query.path);
            path = path.split('/').map(function (itm){return itm.trim()}).filter(function (itm){return !!itm});
            _pathNode.empty();

            var showHome = path.length > 0;


            while (path.length) {
                var item = pathItem(path.join('/'))
                _pathNode.prepend(item);
                path.pop();

            }

            if(showHome) {
                _pathNode.prepend(pathItem('', 'Home'));
                if(_backNode) {
                    _backNode.show();
                }

            } else {
                if(_backNode) {
                    _backNode.hide();
                }
            }
            if(_request) {
                scope.sort(scope.settings.query.orderBy, scope.settings.query.order, false);

                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }
            _pathNode.find('.acttive').removeClass('active')
            _pathNode.find('.breadcrumb-item:last-child').addClass('active')

        };

        this.init = function (){
            createRoot();
            this.requestData(this.settings.query, function (data){
                scope.updateData(data);
                scope.view();
                scope.path(scope.settings.query.path, false);
                scope.sort(scope.settings.query.orderBy, scope.settings.query.order, false);
                scope.viewType(scope.settings.viewType, true);
                scope.dispatch('ready');
            });
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
