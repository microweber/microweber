mw.DomTree = function (options) {
    var scope = this;
    this.getNodeIconAndTitle = function (node) {
        var icon = false;
        var title = false;
        if (mw.tools.hasClass(node, 'row')) {
            title = "Row";
            icon = '<span class="mdi mdi-table-row mdi-18px"></span>';
        } else if (
            (
                mw.top().app
                && mw.top().app.templateSettings
                && mw.tools.hasAnyOfClasses(node, mw.top().app.templateSettings.helperClasses.external_grids_col_classes)
            )
            ||
            (
                node.ownerDocument.defaultView &&
                node.ownerDocument.defaultView.mw &&
                typeof node.ownerDocument.defaultView.mw.drag !== 'undefined' &&
                typeof node.ownerDocument.defaultView.mw.drag.external_grids_col_classes !== 'undefined'
                && node.ownerDocument.defaultView.mw.drag
                && mw.tools.hasAnyOfClasses(node, node.ownerDocument.defaultView.mw.drag.external_grids_col_classes)
            )) {
            title = "Column";
            icon = '<span class="mdi mdi-table-column mdi-18px"></span>';
        } else if (node.nodeName === 'H1') {
            title = "Heading 1";
            icon = '<span class="mdi mdi-format-header-1 mdi-18px"></span>';
        } else if (node.nodeName === 'H2') {
            title = "Heading 2";
            icon = '<span class="mdi mdi-format-header-2 mdi-18px"></span>';
        } else if (node.nodeName === 'H3') {
            title = "Heading 3";
            icon = '<span class="mdi mdi-format-header-3 mdi-18px"></span>';
        } else if (node.nodeName === 'H4') {
            title = "Heading 4";
            icon = '<span class="mdi mdi-format-header-4 mdi-18px"></span>';
        } else if (node.nodeName === 'H5') {
            title = "Heading 5";
            icon = '<span class="mdi mdi-format-header-5 mdi-18px"></span>';
        } else if (node.nodeName === 'H6') {
            title = "Heading 6";
            icon = '<span class="mdi mdi-format-header-6 mdi-18px"></span>';
        } else if (node.nodeName === 'P') {
            title = "Paragraph";
            icon = '<span class="mdi mdi-format-paragraph mdi-18px"></span>';
        } else if (node.nodeName === 'SECTION') {
            title = "Section";
            icon = '<span class="mdi mdi-format-section mdi-18px"></span>';
        } else if (node.nodeName === 'IMG') {
            title = "Image";
            icon = '<span class="mdi mdi-file-image-outline mdi-18px"></span>';
        }  else if (['SPAN', 'I'].includes(node.nodeName) && (mw.tools.hasAnyOfClasses(node, ['icon', 'mw-icon', 'mw-micon', 'mdi', 'material-icons']) || node.className.includes('mw-micon-') || node.className.includes('icon-'))) {
            title = "Icon";
            icon = '<span class="mdi mdi-emoticon-wink-outline mdi-18px"></span>';
        }
        var data = {};
        data.icon = icon;
        data.title = title;
        return data;
    },
        this.prepare = function () {
            var defaults = {
                selector: '.edit',
                compactTreeView: false,
                document: document,
                targetDocument: document,
                componentMatch: [
                    {
                        label: function (node) {
                            return 'Edit';
                        },
                        test: function (node) {
                            return mw.tools.hasClass(node, 'edit');
                        }
                    },
                    {
                        label: function (node) {
                            if (mw.top().app.modules) {
                                var info = mw.top().app.modules.getModuleInfo(node.getAttribute('data-type'));
                                var display = '';
                                if (info && info.name) {
                                    display = info.name;
                                }
                                if (info && info.icon) {
                                    display = '<span  style="background-image: url(' + info.icon + ')" class="mw_module_settings_sidebar_icon"></span> ' + display;
                                }

                                return display;

                            }

                            if (!node.ownerDocument.defaultView.mw.live_edit) {
                                return ''
                            }
                            var icon = node.ownerDocument.defaultView.mw.live_edit.getModuleIcon(node.getAttribute('data-type'));
                            return icon + ' ' + node.getAttribute('data-mw-title') || node.getAttribute('data-type');
                        },
                        test: function (node) {
                            return mw.tools.hasClass(node, 'module');
                        }
                    },
                    // {
                    //     label: 'Image',
                    //     test: function (node) {
                    //         return node.nodeName === 'IMG';
                    //     }
                    // },
                    {
                        label: function (node) {


                            return this.getNodeLabel(node);
                        },
                        test: function (node) {
                            return true;
                        }
                    }
                ],
                componentTypes: [
                    {
                        label: 'SafeMode',
                        test: function (node) {
                            return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, ['safe-mode', 'regular-mode']);
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
        return nodeOrTreeNode._value ? nodeOrTreeNode : this.findElementInTree(nodeOrTreeNode);
    };

    this.select = function (node) {
        var el = this.getByNode(node);
        if (el) {
            this.selected(el);
            this.openParents(el);
            this._scrollTo(el);
            this.setSelectedNodeInfoInToggleBox(node);
        }
    };

    this.toggle = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        this[li._opened ? 'close' : 'open'](li);
    };

    this._opened = [];

    this.open = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = true;
        li.classList.add('expand');
        if (this._opened.indexOf(li._value) === -1) {
            this._opened.push(li._value);
        }
    };
    this.close = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = false;
        li.classList.remove('expand');
        var ind = this._opened.indexOf(li._value);
        if (ind !== -1) {
            this._opened.splice(ind, ind)
        }
    };

    this._scrollTo = function (el) {
        setTimeout(function () {
            scope.$holder.stop().animate({
                scrollTop: (scope.$holder.scrollTop() + ($(el).offset().top - scope.$holder.offset().top)) - (scope.$holder.height() / 2 - 10)
            });
        }, 55);
    };

    this.openParents = function (node) {
        node = this._get(node);
        while (node && node !== this.root) {
            if (node.nodeName === 'LI') {
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

    this.setSelectedNodeInfoInToggleBox = function (node) {
        var iconAndTitle = this.getNodeLabel(node);
        if (iconAndTitle) {
            $('#selected-item-info-and-title-single').html(iconAndTitle);
        } else {
            var title = node.nodeName.toLowerCase();

            $('#selected-item-info-and-title-single').html(title);

        }

    };

    this.getByNode = function (el) {
        var all = this.root.querySelectorAll('li');
        var l = all.length, i = 0;
        for (; i < l; i++) {
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
                if (target.nodeName !== 'LI') {
                    target = target.parentNode;
                }
                if (scope._currentTarget !== target) {
                    scope._currentTarget = target;
                    mw.$('li.hover', scope.root).removeClass('hover');
                    target.classList.add('hover');
                    if (scope.settings.onHover) {
                        scope.settings.onHover.call(scope, e, target, target._value);
                    }
                }
            })
            .on('mouseleave', function (e) {
                mw.$('li', scope.root).removeClass('hover');
            })
            .on('click', function (e) {
                var target = e.target;

                if (target.nodeName !== 'LI') {
                    target = mw.tools.firstParentWithTag(target, 'li');
                    scope.toggle(target);
                }
                if (target._selectable) {
                    scope.selected(target);
                    if (target.nodeName === 'LI' && scope.settings.onSelect) {
                        scope.settings.onSelect.call(scope, e, target, target._value);
                    }
                }

            });
    };

    this.map = function (node, treeNode) {
        if (!this._map) {
            this._map = new Map();
        }
        if (!node) {
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
        if (!this.validateNode(item)) {
            return;
        }
        var li = this.document.createElement('li');
        li._value = item;
        li.className = 'mw-domtree-item' + (this._selectedDomNode === item ? ' active' : '');
        var dio = item.children.length ? '<i class="mw-domtree-item-opener"></i>' : '';
        var dtLabel = this.document.createElement('span');
        dtLabel.className = 'mw-domtree-item-label'
        dtLabel.innerHTML = this.getComponentLabel(item)
        li.innerHTML = dio;
        li.appendChild(dtLabel)
        if (typeof scope.settings.canSelect === 'function') {
            var can = scope.settings.canSelect(item, li);
            li.classList.add('selectable-' + can);
            li._selectable = can;
            if (!can) {
                dtLabel.title = mw.lang('Item can not be selected')
            }
        }
        return li;
    };
    this.getNodeLabel = function (node) {
        var id = node.id ? '#' + node.id : '';
        var iconAndTitle = this.getNodeIconAndTitle(node);
        var icon = '';
        var title = node.nodeName.toLowerCase();
        if (iconAndTitle.icon) {
            icon = iconAndTitle.icon;
        }
        if (iconAndTitle.title) {
            title = iconAndTitle.title;
        }

        var display = title;
        if (icon) {
            display = icon + ' ' + title;
        }

        return display;
    };
    this.getComponentLabel = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (; i < all.length; i++) {
            if (all[i].test(node)) {
                return typeof all[i].label === 'string' ? all[i].label : all[i].label.call(this, node);
            }
        }
    };
    this.isComponent = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (; i < all.length; i++) {
            if (all[i].test(node)) {
                return true;
            }
        }
        return false;
    };

    this.validateNode = function (node) {
        if (node.nodeType !== 1) {
            return false;
        }
        var tag = node.nodeName;
        if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'LINK' || tag === 'BR') {
            return false;
        }
        return this.isComponent(node);
    };

    this.create = function () {
        var all = this.targetDocument.querySelectorAll(this.settings.selector);
        var i = 0;
        for (; i < all.length; i++) {
            var item = this.createItem(all[i]);
            if (item) {
                this.root.appendChild(item);
                this.createChildren(all[i], item);
            }
        }
        this.createItemEvents();
        $(this.settings.element).empty().append(this.root).resizable({
            handles: "s",
            start: function (event, ui) {
                ui.element.css('maxHeight', 'none');
            }
        });

        if (this.settings.compactTreeView) {
            $(this.settings.element).before('' +

                '<div class="d-none">' +
                '<span id="selected-item-info-and-title-single"></span>' +
                '<span class="mw-ui-btn mw-ui-btn-small" onclick="mw.$(\'' + this.settings.element + '\').toggle();">' +
                '<i class="mdi mdi-selection-search"></i>' +
                '</span>' +
                '</div>'
            );

            $(this.settings.element).hide();
        }


    };

    this.createChildren = function (node, parent) {
        if (!parent) return;
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
