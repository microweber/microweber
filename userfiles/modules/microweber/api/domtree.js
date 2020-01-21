mw.DomTree = function (options) {
    var defaults = {
        selector: '.edit',
        document: document,
        targetDocument: document,
        componentMatch: [
            {label: 'Edit', test: function (node) { return mw.tools.hasClass(node, 'edit');}},
            {label: 'Module', test: function (node) { return mw.tools.hasClass(node, 'module');}},
            {label: 'Image', test: function (node) { return node.nodeName === 'IMG'; }},
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
                    return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [ 'safe-mode', 'regular-mode']);
                }
            }
        ]
    };
    options = options || {};

    var scope = this;

    this.settings = $.extend({}, defaults, options);

    this.$holder = $(this.settings.element);

    this.document = this.settings.document;
    this.targetDocument = this.settings.targetDocument;

    this._selectedDomNode = null;

    this._scrollTo = function (el) {
        setTimeout(function () {


            // el.scrollIntoView({ behavior: 'smooth', block: 'center' });

            var a1 =  scope.$holder.offset().top;
            var a2 =  $(el).offset().top;

            var to = a1 > a2 ? a1 - a1 : a2 - a1;

            scope.$holder.stop().animate({scrollTop: to });
        }, 55);
    };


    this.select = function (node) {
        var el = this.items.find(function (li) {
            return li._value === node;
        });
        if (el) {
            this.selected(el);
            this.openParents(el);
            this._scrollTo(el);
        }
    };
    this.selected = function (node) {
        if(typeof node === 'undefined') {
            return this._selectedDomNode;
        }
        $(this.items).removeClass('selected');
        node.classList.add('selected');
        this._selectedDomNode = node;
    };

    this._active = null;
    this.active = function (node) {
        if(typeof node === 'undefined'){
            return this._active;
        }
        this._active = node;
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
                for (var i = 0; i<scope.items.length; i++ ){
                    scope.items[i].classList.remove('hover');
                }
                target.classList.add('hover');
                if(scope.settings.onHover) {
                    scope.settings.onHover.call(scope, e, target, target._value);
                }
            }
        })
        .on('mouseleave', function (e) {
            for (var i = 0; i<scope.items.length; i++ ){
                scope.items[i].classList.remove('hover');
            }
        })
        .on('click', function (e) {
            var target = e.target;

            if(target.nodeName === 'I') {
                scope.toggle(target.parentNode)
            } else if(target.nodeName !== 'LI') {
                target = target.parentNode;
            }
            scope.selected(target);
            if(target.nodeName === 'LI' && scope.settings.onSelect) {
                scope.settings.onSelect.call(scope, e, target, target._value);
            }
        });

    };

    this._opened = [];

    this._get = function (nodeOrTreeNode) {
        return nodeOrTreeNode._value ? nodeOrTreeNode : this.findElementInTree(nodeOrTreeNode);
    };

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

    this.toggle = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        this[ li._opened ? 'close' : 'open'](li);
    };

    this.items = [];

    this.register = [];
    this.createItem = function (item) {
        if(this.register.indexOf(item) === -1 && this.validateNode(item)){
            this.register.push(item);
            var li = this.document.createElement('li');
            li._value = item;
            li.className = 'mw-domtree-item';
            var dio = item.children.length ? '<i class="mw-domtree-item-opener"></i>' : '';
            li.innerHTML = dio + '<span class="mw-domtree-item-label">' + this.getComponentLabel(item) + '</span>';
            this.items.push(li);
            return li;
        }
    };

    this.refactorItemDecoration = function (li) {
        $('.mw-domtree-item-opener', li).remove();
        $('li', li).each(function () {
            if(this._value.children.length) {
                $(this).prepend('<i class="mw-domtree-item-opener"></i>');
            }
        });
    };

    this.createList = function () {
        return this.document.createElement('ul');
    };

    this.createRoot = function () {
      this.root = this.createList();
      this.root.className = 'mw-defaults mw-domtree';
    };


    this.firstChild = function (node) {
        var curr = node.children[0], i = 0;
        while(curr) {
            i++;
            if(this.validateNode(curr)){
                return curr;
            }
            curr = node.children[i];
        }
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

    this.addNode = function (node) {
        var item = this.createItem(node);
        var target, action;
        if(node.previousElementSibling){
            target = this.items.find(function (li) {
                return li._value === node.previousElementSibling;
            });
            action = 'after';
        }  else if(node.nextElementSibling){
            target = this.items.find(function (li) {
                return li._value === node.nextElementSibling;
            });
            action = 'before';
        } else if(node.parentNode){
            target = this.items.find(function (li) {
                return li._value === node.parentNode;
            });
            action = 'append';
        }
        $(target)[action](item);
        this.createChildren(node, item);
    };

    this._empty = function (parent) {
        var all = parent.querySelectorAll('*');
        var i = 0;
        for (  ; i < all.length; i++ ) {
            var li = this.findElementInTree(all[i]);
            this.items.splice(this.items.indexOf(li), 1);
            this.register.splice(this.register.indexOf(all[i]), 1);
            if(li) {
                li.parentNode.removeChild(li);
            }
        }
        mw.$(':empty', this.root).remove()
    };

    this.sync = function (parent) {
        this._empty(parent);
        var treeItem = this.findElementInTree(parent);

        this.createChildren(parent, treeItem);
        this.refactorItemDecoration(treeItem);
    };

    this.create = function () {
        this.createRoot();
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
        $(this.settings.element).empty().append(this.root);
    };

    this.findElementInTree = function (node) {
        if(this.register.indexOf(node) !== -1) {
            return this.items.find(function (li) {
                return li._value === node;
            });
        }
    };

    this.create();

};


