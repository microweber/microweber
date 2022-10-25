/*
*
*  interface data {
        target: Element,
        component: Element,
        isImage: boolean,
        event: Event
    };
*
*
* */

MWEditor.interactionControls = {
    linkTooltip: function (rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.element({
                props: {
                    className: 'mw-editor-link-tooltip'
                }
            });
            var urlElement = mw.element({
                tag: 'a',
                props: {
                    className: 'mw-editor-link-tooltip-url',
                    target: 'blank'
                }
            });
            var urlUnlink = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off',
                }
            });

            urlUnlink.on('click', function () {
                rootScope.api.unlink();
            });

            el.urlElement = urlElement;
            el.urlUnlink = urlUnlink;
            el.append(urlElement);
            el.append(urlUnlink);
            el.target = null;
            el.hide();
            return el;
        };
        this.interact = function (data) {
            var tg = mw.tools.firstParentOrCurrentWithTag(data.target,'a');
            if(!tg) {
                this.element.hide();
                return;
            }
            var $target = $(data.target);
            this.$target = $target;
            var css = $target.offset();
            css.top += $target.height();
            this.element.urlElement.html(data.target.href);
            this.element.urlElement.prop('href', data.target.href);
            this.element.css(css).show();
        };
        this.element = this.render();
    },
    image: function (rootScope) {
        this.nodes = [];
        this.render = function () {
            var scope = this;
            var el = mw.element({
                props: {
                    className: 'mw-editor-image-handle-wrap',
                    tabIndex: -1
                }
            });
            el.on('keydown', function (e){
                if (e.keyCode === 8 || e.keyCode === 46) {
                    if (scope.__target) {
                        scope.__target.remove();
                        el.hide();
                    }
                }
            });


            var changeButton = mw.element({
                props: {
                    innerHTML: '<i class="mdi mdi-folder-multiple-image"></i>',
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
                    dataset: {
                        tip: rootScope.lang('Change image')
                    }
                }
            });
            changeButton.on('click', function () {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        url = url.toString();
                        scope.$target.attr('src', url);
                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false
                })

            });
            var editButton = mw.element({
                props: {
                    innerHTML: '<i class="mdi mdi-image-edit"></i>',
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
                    dataset: {
                        tip: rootScope.lang('Edit image')
                    }
                }
            });
            var nav = mw.element({
                props: {
                    className: 'mw-ui-btn-nav'
                }
            });
            nav.append(changeButton);
            el.append(nav);
            // nav.append(editButton);
            this.nodes.push(el.node, changeButton.node, editButton.node);
            el.hide();
            return el;
        };
        this.interact = function (data) {
            if(mw.tools.firstParentOrCurrentWithClass(data.localTarget, 'mw-editor-image-handle-wrap')) {
                return;
            }
            if(this.nodes.indexOf(data.target) !== -1) {
                this.element.$node.hide();
                return;
            }
            if (data.isImage) {
                var $target = $(data.localTarget);
                this.$target = $target;
                this.__target = $target.get(0);
                var css = $target.offset();
                css.width = $target.outerWidth();
                css.height = $target.outerHeight();
                this.element.css(css).show();
            } else {
                this.element.hide();
            }
        };
        this.element = this.render();
    },
    tableManager: function(rootScope){
        var lscope = this;
        this.interact = function (data) {
            if (!data.eventIsActionLike) { return; }
            var td = mw.tools.firstParentOrCurrentWithTag(data.localTarget, 'td');
            if (td) {
                var $target = $(td);
                this.$target = $target;
                var css = $target.offset();
                css.top -= lscope.element.node.offsetHeight;
                this.element.$node.css(css).show();
            } else {
                this.element.$node.hide();
            }
        };

        this._afterAction = function () {
            this.element.$node.hide();
            rootScope.state.record({
                target: rootScope.$editArea[0],
                value: rootScope.$editArea[0].innerHTML
            });
        };

        this.render = function () {
            var root = mw.element({
                props: {
                    className: 'mw-editor-table-manager'
                }
            });
            var bar = mw.bar();
            bar.createRow();
            root.append(bar.bar);

            var insertDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row Above', value: {action: 'insertRow', type: 'above'} },
                    { label: 'Row Under', value: {action: 'insertRow', type: 'under'} },
                    { label: 'Column on the left', value: {action: 'insertColumn', type: 'left'} },
                    { label: 'Column on the right', value: {action: 'insertColumn', type: 'right'} },
                ],
                placeholder: 'Insert'
            });

            insertDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action](data.value.type);
                lscope._afterAction();
            });
            var deletetDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row', value: {action: 'deleteRow'} },
                    { label: 'Column', value: {action: 'deleteColumn'} },
                ],
                placeholder: 'Delete'
            });

            deletetDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action]();
                lscope._afterAction()
            });

            bar.add(insertDD.root.node);
            bar.add(deletetDD.root.node);
            root.hide();

            return root;
        };

        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            cell.parentNode.remove();
        };


        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = mw.tools.index(cell),
                body = cell.parentNode.parentNode,
                rows = mw.$(body).children('tr'),
                l = rows.length,
                i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                row.getElementsByTagName('td')[index].remove();
            }
        };

        this.getActiveCell = function () {
            var node = rootScope.api.elementNode( rootScope.getSelection().focusNode);
            return mw.tools.firstParentOrCurrentWithTag(node,'td');
        };

        this.insertColumn = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                cell = mw.$(row).children('td')[index];
                if (dir === 'left' || dir === 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir === 'right' || dir === 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        };
        this.insertRow = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length,
                html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            html = '<tr>' + html + '</tr>';
            if (dir === 'under' || dir === 'both') {
                mw.$(parent).after(html)
            }
            if (dir === 'above' || dir === 'both') {
                mw.$(parent).before(html)
            }
        };
        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            mw.$(cell.parentNode).remove();
        };
        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        };

        this.setStyle = function (cls, cell) {
            cell = cell || this.getActiveCell();
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        };
        this.element = this.render();
    }

};
