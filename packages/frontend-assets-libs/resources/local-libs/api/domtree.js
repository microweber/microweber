


mw.DomTree = function (options) {
    var scope = this;


    const _e = {};
    this.on = (e, f) => {``
        _e[e] ? _e[e].push(f) : (_e[e] = [f])
    };
    this.dispatch = (e, f) => {
        _e[e] ? _e[e].forEach((c) => {
            c.call(this, f);
        }) : '';
    };


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


        if(!title) {
            title = node.nodeName.toLowerCase();
            if(title === 'div') {
                title = getComputedStyle(node).getPropertyValue('display');
            }
        }

        var data = {};

        data.icon = icon;
        data.title = title;

        return data;
    },
        this.prepare = function () {
            var defaults = {
                selector: '.edit',
                // AI-775 Slice A (task-2026-05-17-551f7e) — meaningful-layers filter.
                // When meaningfulOnly is true, the walker uses meaningfulSelector
                // instead of selector for the initial querySelectorAll AND the
                // recursive descent (createChildren). This caps the Layers panel
                // at structural containers only (main-content / module-layouts /
                // module / [data-type] modules) — typical canvas yields <=100
                // rows instead of the 1,787-row blowout from the broad `.edit`
                // walk. Default false to preserve back-compat for any consumer
                // that still wants the full walk.
                meaningfulOnly: false,
                meaningfulSelector: '.main-content, .module-layouts, .module, [data-type]',
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
                        label:   function (node) {
                            if (mw.top().app.modules) {
                                var info =   mw.top().app.modules.getModuleInfo(node.getAttribute('data-type'));


                                var display = '';
                                if (info && info.name) {
                                    display = info.name;
                                }
                                if (info && info.icon) {

                                    if(info.icon.indexOf('<svg') !== -1) {
                                        display = '<span class="mw_module_settings_sidebar_icon mw-module-settings-sidebar-icon-svg">' + info.icon + '</span> ' + display;
                                    } else {
                                        display = '<span  style="background-image: url(' + info.icon + ')" class="mw_module_settings_sidebar_icon mw-module-settings-sidebar-icon-background-image"></span> ' + display;
                                    }

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
        // task-2026-05-28-4b9d88 / AI-1223 — ARIA Tree pattern.
        // Root <ul> declares role="tree" + aria-labelledby pointing
        // at the controlBox <h3 class="mw-control-box-title"> id
        // ("mw-domtree-layers-heading"). Sublists declared role="group"
        // inside createChildren() so AT reads the nested structure.
        this.root.setAttribute('role', 'tree');
        this.root.setAttribute('aria-labelledby', 'mw-domtree-layers-heading');
    };


    this._get = function (nodeOrTreeNode) {
        return nodeOrTreeNode._value ? nodeOrTreeNode : this.findElementInTree(nodeOrTreeNode);
    };

    this.select = function (node) {
        if(node && node.id && node.parentNode && node.ownerDocument) {
            // in case dom is modified from the state manager
            node = node.ownerDocument.getElementById(node.id)
        }
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
        // task-2026-05-28-4b9d88 / AI-1223 — aria-expanded mirror.
        if (li.hasAttribute('aria-expanded')) {
            li.setAttribute('aria-expanded', 'true');
        }
        if (this._opened.indexOf(li._value) === -1) {
            this._opened.push(li._value);
        }
    };
    this.close = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = false;
        li.classList.remove('expand');
        // task-2026-05-28-4b9d88 / AI-1223 — aria-expanded mirror.
        if (li.hasAttribute('aria-expanded')) {
            li.setAttribute('aria-expanded', 'false');
        }
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
        // task-2026-05-28-4b9d88 / AI-1223 — roving tabindex.
        // Only the active treeitem carries tabindex="0"; all others
        // are tabindex="-1" so a single Tab moves into the tree and
        // arrow keys traverse from the active row.
        mw.$('li[tabindex="0"]', this.root).attr('tabindex', '-1');
        if (node && node.setAttribute) {
            node.setAttribute('tabindex', '0');
        }
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

            })
            // task-2026-05-28-4b9d88 / AI-1223 — ARIA Tree keyboard nav.
            // ArrowDown/Up move focus to the next/prev visible treeitem.
            // ArrowRight expands a collapsed node, or focuses the first
            // child when already expanded. ArrowLeft collapses an open
            // node, or focuses the parent treeitem when already closed.
            // Home/End jump to first/last visible treeitem. Enter/Space
            // selects the focused row. Implements the ARIA Authoring
            // Practices tree pattern.
            .on('keydown', function (e) {
                var target = e.target;
                if (!target || target.getAttribute('role') !== 'treeitem') {
                    return;
                }
                var key = e.key;
                var moveFocus = function (next) {
                    if (!next) return;
                    mw.$('li[tabindex="0"]', scope.root).attr('tabindex', '-1');
                    next.setAttribute('tabindex', '0');
                    next.focus();
                };
                var visibleItems = function () {
                    var all = scope.root.querySelectorAll('li[role="treeitem"]');
                    var out = [];
                    for (var i = 0; i < all.length; i++) {
                        var hidden = false;
                        var p = all[i].parentNode;
                        while (p && p !== scope.root) {
                            if (p.nodeName === 'LI' && !p.classList.contains('expand')) {
                                hidden = true;
                                break;
                            }
                            p = p.parentNode;
                        }
                        if (!hidden) out.push(all[i]);
                    }
                    return out;
                };
                if (key === 'ArrowDown') {
                    e.preventDefault();
                    var items = visibleItems();
                    var idx = items.indexOf(target);
                    if (idx !== -1 && idx + 1 < items.length) moveFocus(items[idx + 1]);
                } else if (key === 'ArrowUp') {
                    e.preventDefault();
                    var items = visibleItems();
                    var idx = items.indexOf(target);
                    if (idx > 0) moveFocus(items[idx - 1]);
                } else if (key === 'ArrowRight') {
                    e.preventDefault();
                    if (target.getAttribute('aria-expanded') === 'false') {
                        scope.open(target);
                    } else if (target.getAttribute('aria-expanded') === 'true') {
                        var firstChild = target.querySelector('ul[role="group"] > li[role="treeitem"]');
                        if (firstChild) moveFocus(firstChild);
                    }
                } else if (key === 'ArrowLeft') {
                    e.preventDefault();
                    if (target.getAttribute('aria-expanded') === 'true') {
                        scope.close(target);
                    } else {
                        var parentLi = target.parentNode && target.parentNode.parentNode;
                        if (parentLi && parentLi.nodeName === 'LI' && parentLi.getAttribute('role') === 'treeitem') {
                            moveFocus(parentLi);
                        }
                    }
                } else if (key === 'Home') {
                    e.preventDefault();
                    var items = visibleItems();
                    if (items.length) moveFocus(items[0]);
                } else if (key === 'End') {
                    e.preventDefault();
                    var items = visibleItems();
                    if (items.length) moveFocus(items[items.length - 1]);
                } else if (key === 'Enter' || key === ' ') {
                    e.preventDefault();
                    if (target._selectable) {
                        scope.selected(target);
                        if (scope.settings.onSelect) {
                            scope.settings.onSelect.call(scope, e, target, target._value);
                        }
                    } else if (target.hasAttribute('aria-expanded')) {
                        scope.toggle(target);
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

    /**
     * task-2026-05-16-cf3fa6 / AI-709 — rich element label.
     *
     * Returns "<tag> [.class] ['preview']" HTML for a row. Examples
     * (from designer dispatch):
     *   h1 'Describe your company'
     *   div .hero
     *   p
     *
     * Rules:
     *   - tag is always present (lowercased nodeName)
     *   - first non-system class is appended as " .<class>" if present
     *     (system classes "edit", "module", "selected", "ui-*" excluded
     *      so the row identifies the element, not the framework chrome)
     *   - text preview is the first 24 chars of node.innerText (single-
     *     line, trimmed); only emitted for elements that carry direct
     *     text content (headings, paragraphs, spans)
     *   - final HTML capped to 32 visible chars + ellipsis to keep
     *     row width predictable; the full label is set as title= on
     *     the parent <li> for tooltip access on hover.
     */
    this.getRichElementLabel = function (node) {
        var tag = (node.nodeName || '').toLowerCase();
        var bits = [
            '<span class="mw-domtree-tag">' + this._escapeHtml(tag) + '</span>'
        ];
        var visibleChars = tag.length;

        // First non-system class (.hero, .container, .my-custom-class).
        var systemClasses = ['edit', 'module', 'selected', 'active', 'mw-defaults', 'mw-defaults-element'];
        var firstClass = '';
        if (node.classList && node.classList.length) {
            for (var i = 0; i < node.classList.length; i++) {
                var c = node.classList[i];
                if (systemClasses.indexOf(c) !== -1) continue;
                if (c.indexOf('ui-') === 0) continue;
                if (c.indexOf('selectable-') === 0) continue;
                firstClass = c;
                break;
            }
        }
        if (firstClass) {
            var classText = '.' + firstClass;
            bits.push(
                '<span class="mw-domtree-class">' + this._escapeHtml(classText) + '</span>'
            );
            visibleChars += 1 + classText.length;
        }

        // Inner-text preview (first 24 chars) for elements that
        // carry direct text — headings, paragraphs, spans. Skipped
        // for layout containers (div / section) and modules.
        var previewTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'a', 'li', 'button', 'label'];
        if (previewTags.indexOf(tag) !== -1) {
            var rawText = (node.innerText || node.textContent || '').replace(/\s+/g, ' ').trim();
            if (rawText.length) {
                var preview = rawText.length > 24 ? rawText.substr(0, 24) + '…' : rawText;
                var previewText = "'" + preview + "'";
                bits.push(
                    '<span class="mw-domtree-preview">' + this._escapeHtml(previewText) + '</span>'
                );
                visibleChars += 1 + previewText.length;
            }
        }

        return {
            html: bits.join(' '),
            text: this._buildPlainLabel(tag, firstClass, node, previewTags),
            // Designer spec: truncate at 32 chars + title= tooltip.
            // visibleChars is informational for CSS / future tests.
            visibleChars: visibleChars
        };
    };

    this._escapeHtml = function (s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    this._buildPlainLabel = function (tag, firstClass, node, previewTags) {
        var parts = [tag];
        if (firstClass) parts.push('.' + firstClass);
        if (previewTags.indexOf(tag) !== -1) {
            var rawText = (node.innerText || node.textContent || '').replace(/\s+/g, ' ').trim();
            if (rawText.length) {
                var preview = rawText.length > 24 ? rawText.substr(0, 24) + '…' : rawText;
                parts.push("'" + preview + "'");
            }
        }
        return parts.join(' ');
    };

    this.createItem = function (item) {
        if (!this.validateNode(item)) {
            return;
        }
        var li = this.document.createElement('li');
        li._value = item;
        li.className = 'mw-domtree-item' + (this._selectedDomNode === item ? ' active' : '');
        // task-2026-05-28-4b9d88 / AI-1223 — ARIA Tree pattern.
        // Every <li> declares role="treeitem" + roving tabindex="-1"
        // (initial focus carrier set in create() after the walk).
        // aria-expanded is mirrored on collapsible nodes (open/close).
        // The opener <i> is aria-hidden so AT consumes the row label.
        li.setAttribute('role', 'treeitem');
        li.setAttribute('tabindex', '-1');
        if (item.children.length) {
            li.setAttribute('aria-expanded', 'false');
        }
        var dio = item.children.length ? '<i class="mw-domtree-item-opener" aria-hidden="true"></i>' : '';

        // task-2026-05-16-cf3fa6 / AI-709 — rich element label
        // (icon + <tag> + .class + 'preview') is the row's primary
        // identity. Previously the row showed only the existing
        // .mw-domtree-item-label (which on .edit elements rendered
        // literally as "Edit" — the designer's audit reported "Edit
        // button is the only readable text per row"). The element
        // label now leads; the .mw-domtree-item-label component
        // affordance (Edit / module-name) is right-aligned via CSS.
        var iconAndTitle = this.getNodeIconAndTitle(item);
        var iconHtml = iconAndTitle.icon || '';
        var rich = this.getRichElementLabel(item);

        var dtElementLabel = this.document.createElement('span');
        dtElementLabel.className = 'mw-domtree-item-element-label';
        dtElementLabel.innerHTML =
            '<span class="mw-domtree-item-icon">' + iconHtml + '</span>' + rich.html;
        // Full label as native tooltip (designer spec — "Truncate at
        // 32 chars + title= tooltip").
        li.title = rich.text;

        var dtLabel = this.document.createElement('span');
        dtLabel.className = 'mw-domtree-item-label'
        dtLabel.innerHTML = this.getComponentLabel(item)
        li.innerHTML = dio;
        li.appendChild(dtElementLabel);
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
    this.getComponentLabel =  function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (; i < all.length; i++) {

            if (all[i].test(node)) {
                return typeof all[i].label === 'string' ? all[i].label :   all[i].label.call(this, node);
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
        // AI-775 Slice A (task-2026-05-17-551f7e) — when meaningfulOnly is set,
        // walk only structural containers (meaningfulSelector) instead of every
        // .edit element. Cuts Layers panel cardinality from ~1,787 rows to <=100
        // on the 6-section demo page.
        var walkSelector = this.settings.meaningfulOnly
            ? this.settings.meaningfulSelector
            : this.settings.selector;
        var all = this.targetDocument.querySelectorAll(walkSelector);
        var i = 0;
        for (; i < all.length; i++) {
            var item = this.createItem(all[i]);
            if (item) {
                this.root.appendChild(item);
                this.createChildren(all[i], item);
            }
        }
        this.createItemEvents();
        // task-2026-05-28-4b9d88 / AI-1223 — initial roving focus carrier.
        // The first treeitem gets tabindex="0" so the panel receives focus
        // on a single Tab; arrow keys then traverse the tree from there.
        var firstItem = this.root.querySelector('li[role="treeitem"]');
        if (firstItem) firstItem.setAttribute('tabindex', '0');
        $(this.settings.element).empty().append(this.root);
        if(this.settings.resizable) {
            $(this.settings.element).resizable({
                handles: "s",
                start: function (event, ui) {
                    ui.element.css('maxHeight', 'none');
                }
            });
        }


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
        // task-2026-05-28-4b9d88 / AI-1223 — sublists declare role="group".
        list.setAttribute('role', 'group');
        var curr = node.children[0];
        // AI-775 Slice A (task-2026-05-17-551f7e) — under meaningfulOnly, a
        // child renders only when it matches the meaningfulSelector. Recursion
        // continues into non-matching descendants so we can find a deep
        // meaningful node nested inside markup wrappers — but the non-matching
        // wrapper itself does not appear in the tree.
        var meaningfulOnly = this.settings.meaningfulOnly;
        var meaningfulSelector = this.settings.meaningfulSelector;
        while (curr) {
            var isMeaningful = !meaningfulOnly || (typeof curr.matches === 'function' && curr.matches(meaningfulSelector));
            if (isMeaningful) {
                var item = this.createItem(curr);
                if (item) {
                    list.appendChild(item);
                    if (curr.children.length) {
                        this.createChildren(curr, item);
                    }
                }
            } else if (curr.children.length) {
                // Pass `parent` (not a new item) so meaningful descendants of
                // a non-meaningful wrapper attach to the same enclosing list —
                // collapses pure-markup wrappers without losing depth.
                this.createChildrenInto(curr, list);
            }
            curr = curr.nextElementSibling;
        }
        if(this.settings.sortable) {
            $(list).sortable({
                axis: 'y',
                items: "> li",
                containment: "parent",
                start: ( event, ui ) => {
                    const node = ui.item.get(0);
                    this.dispatch('sortStart', {
                        node,
                        target: node._value,

                    });
                },
                update: ( event, ui ) => {
                    const node = ui.item.get(0);
                    const parent = mw.tools.firstParentWithTag(node, 'li');
                    this.dispatch('sort', {
                        node,
                        target: node._value,
                        targetPrev: node.previousElementSibling?._value,
                        targetNext: node.nextElementSibling?._value,
                        targetParent: parent ? parent._value : null,
                    });
                },
            })
        }

        parent.appendChild(list);
    };

    // AI-775 Slice A (task-2026-05-17-551f7e) — helper used by createChildren
    // when meaningfulOnly is active: walk a non-meaningful wrapper's descendants
    // and append any meaningful matches to an EXISTING parent list (rather than
    // creating a new sublist). Collapses pure-markup wrappers (intermediate
    // <div>s with no .module/.module-layouts/.main-content class) without
    // losing the meaningful nodes nested inside.
    this.createChildrenInto = function (node, parentList) {
        if (!parentList) return;
        var meaningfulSelector = this.settings.meaningfulSelector;
        var curr = node.children[0];
        while (curr) {
            var isMeaningful = typeof curr.matches === 'function' && curr.matches(meaningfulSelector);
            if (isMeaningful) {
                var item = this.createItem(curr);
                if (item) {
                    parentList.appendChild(item);
                    if (curr.children.length) {
                        this.createChildren(curr, item);
                    }
                }
            } else if (curr.children.length) {
                this.createChildrenInto(curr, parentList);
            }
            curr = curr.nextElementSibling;
        }
    };

    this.init = function () {
        this.createRoot();
        this.create();
    };

    this.init();

};
