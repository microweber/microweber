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
        var scope = this;
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

            rootScope.document.addEventListener('click', function(e){
                var node = el.get(0);
                if(!node.contains(e.target) && !e.target.contains(node)) {
                    el.hide();
                }
            })


            var changeButton = mw.element({
                props: {
                    innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon"><path d="M19,19H5V5H19M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M13.96,12.29L11.21,15.83L9.25,13.47L6.5,17H17.5L13.96,12.29Z" /></svg>',
                    className: 'btn btn-icon',
                    dataset: {
                        tip: rootScope.lang('Change image')
                    }
                }
            });


            var deleteButton = mw.element({
                props: {
                    innerHTML: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon"><title>delete-outline</title><path d="M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z" /></svg>',
                    className: 'btn btn-icon',
                    dataset: {
                        tip: rootScope.lang('Delete image')
                    }
                }
            });
            deleteButton.on('click', function () {

                mw.tools.confirm(mw.msg.del, function() {
                    rootScope.state.unpause();

                    rootScope.state.record({
                        target: rootScope.$editArea[0],
                        value: rootScope.$editArea[0].innerHTML
                    });
                    scope.$target.remove()
                    el.hide();
                    rootScope.api.afterExecCommand(); console.log(rootScope.state)
                    rootScope._syncTextArea();
                });


            })
            changeButton.on('click', function () {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    cancel: function () {
                        dialog.remove()
                    },
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        rootScope.state.unpause();
                        rootScope.state.record({
                            target: rootScope.$editArea[0],
                            value: rootScope.$editArea[0].innerHTML
                        });
                        url = url.toString();
                        scope.$target.attr('src', url);
                        dialog.remove();

                        rootScope.api.afterExecCommand();
                        rootScope._syncTextArea();

                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 860
                })

            });
            var editButton = mw.element({
                props: {
                    innerHTML: '<i class="mdi mdi-image-edit"></i>',
                    className: 'btn btn-link tip',
                    dataset: {
                        tip: rootScope.lang('Edit image')
                    }
                }
            });
            var nav = mw.element({
                props: {
                    className: 'btn-group'
                }
            });
            nav.append(changeButton);
            nav.append(deleteButton);
            el.append(nav);
            // nav.append(editButton);
            this.nodes.push(el.node, changeButton.node, editButton.node);
            scope.__resizing = false;

            if(window.jQuery && jQuery.fn.resizable) {
                jQuery(el.get(0)).resizable({
                    handles: "e,se",
                    start: function( event, ui ) {
                        scope.__resizing = true;

                        rootScope.state.unpause();
                        rootScope.state.record({
                            target: rootScope.$editArea[0],
                            value: rootScope.$editArea[0].innerHTML
                        });



                    },
                    stop: function( event, ui ) {
                        scope.__resizing = false;
                        rootScope.api.afterExecCommand();
                        rootScope._syncTextArea();
                        rootScope.state.record({
                            target: rootScope.$editArea[0],
                            value: rootScope.$editArea[0].innerHTML
                        });

                    },
                    resize: function( event, ui ) {

                        scope.$target.css({width: ui.size.width});
                        var css = scope.$target.offset();
                        var parntRelative = document.body;
                        scope.$target.parents().each(function(){
                            if($(this).css('position') === 'relative'){
                                parntRelative = this;
                                return false;
                            }
                        });
                        if(parntRelative) {

                            css.top = css.top - $(parntRelative).offset().top
                        }
                        el.css({height: scope.$target.get(0).offsetHeight, top: css.top})
                    }
                })
            }
            el.hide();
            return el;
        };
        this.interact = function (data) {
            if(scope.__resizing) {
                return;
            }
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
                // css.height = 80;

                var parntRelative = document.body;
                $target.parents().each(function(){
                    if($(this).css('position') === 'relative'){
                        parntRelative = this;
                        return false;
                    }
                });
                if(parntRelative) {
                    css.left = css.left - $(parntRelative).offset().left
                    css.top = css.top - $(parntRelative).offset().top
                }

                this.element.css(css).show();
            } else {
                this.element.hide();
            }
        };
        this.element = this.render();
    },
    tableManager: function(rootScope){
        var lscope = this;
        lscope.__tableManagerTimeout = null;
        this.interact = function (data) {
            if (!data.eventIsActionLike) { return; }
            var td = mw.tools.firstParentOrCurrentWithTag(data.localTarget, 'td');
            rootScope.document.querySelectorAll('.mw-editor-td-focus').forEach(td => td.classList.remove('mw-editor-td-focus'));
            if (td) {
                clearTimeout(lscope.__tableManagerTimeout);

                lscope.__tableManagerTimeout = setTimeout(() => {
                    td.classList.add('mw-editor-td-focus')
                    var space = 5;
                    var $target = $(td);
                    this.$target = $target;
                    var css = $target.offset();
                    this.element.$node.show();



                    // top right
                    // css.left += $target.outerWidth();
                    // css.top -= (lscope.element.node.offsetHeight);



                    css.left -= (lscope.element.node.offsetWidth)

                    css.left -= ( space);


                    var parntRelative = document.body;
                    lscope.$target.parents().each(function(){
                        if($(this).css('position') === 'relative'){
                            parntRelative = this;
                            return false;
                        }
                    });

                    console.log(parntRelative)
                    if(parntRelative) {

                        css.top = css.top - $(parntRelative).offset().top
                    }




                    this.element.$node.css(css)



                })

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
                tooltip: mw.lang('Insert'),
                placeholder: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M18,14H20V17H23V19H20V22H18V19H15V17H18V14M4,3H18A2,2 0 0,1 20,5V12.08C18.45,11.82 16.92,12.18 15.68,13H12V17H13.08C12.97,17.68 12.97,18.35 13.08,19H4A2,2 0 0,1 2,17V5A2,2 0 0,1 4,3M4,7V11H10V7H4M12,7V11H18V7H12M4,13V17H10V13H4Z" /></svg>'
            });

            insertDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });


                if(e.detail) {
                    lscope[e.detail.value.action](e.detail.value.type);
                }


                lscope._afterAction();
            });
            var deletetDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row', value: {action: 'deleteRow'} },
                    { label: 'Column', value: {action: 'deleteColumn'} },
                ],
                tooltip: mw.lang('Delete'),
                placeholder: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M12.35 20H10V17H12.09C12.21 16.28 12.46 15.61 12.81 15H10V12H14V13.54C14.58 13 15.25 12.61 16 12.35V12H20V12.35C20.75 12.61 21.42 13 22 13.54V5C22 3.9 21.1 3 20 3H4C2.9 3 2 3.9 2 5V20C2 21.1 2.9 22 4 22H13.54C13 21.42 12.61 20.75 12.35 20M16 7H20V10H16V7M10 7H14V10H10V7M8 20H4V17H8V20M8 15H4V12H8V15M8 10H4V7H8V10M14.46 15.88L15.88 14.46L18 16.59L20.12 14.46L21.54 15.88L19.41 18L21.54 20.12L20.12 21.54L18 19.41L15.88 21.54L14.46 20.12L16.59 18L14.46 15.88" /></svg>'

            });

            deletetDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[e.detail.value.action]();
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
