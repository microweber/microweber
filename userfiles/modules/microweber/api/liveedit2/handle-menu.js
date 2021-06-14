import {DomService} from "./dom";

export const HandleMenu = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };

    this.createWrapper = function() {
        this.wrapper = document.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handlemenu-' + new Date().getTime());
        this.wrapper.className = 'mw-defaults mw-handlemenu-item ' + (this.options.className || 'mw-handlemenu-type-default');
        this.wrapper.contenteditable = false;

        mw.element(this.wrapper).on('mousedown', function () {
            this.classList.add('mw-handlemenu-item-mouse-down')
        });
        mw.element(document.documentElement).on('mouseup', function () {
            scope.wrapper.classList.remove('mw-handlemenu-item-mouse-down')
        });
        document.body.appendChild(this.wrapper);
    };

    this.create = function() {
        this.createWrapper();
        this.createHandler();

        this.createMenu();
    };

    this.setTitle = function (icon, title) {
        this.handleIcon.innerHTML = icon;
        this.handleTitle.innerHTML = title;
    };

    this.hide = function () {
        mw.element(this.wrapper).hide().removeClass('active');
        this._visible = false;
        return this;
    };

    this.show = function () {
        mw.element(this.wrapper).show();
        this._visible = true;
        return this;
    };

    this.createHandler = function(){
        this.handle = document.createElement('span');
        this.handleIcon = document.createElement('span');
        this.handleTitle = document.createElement('span');
        this.handle.className = 'mw-handlemenu-handler';
        this.handleIcon.dataset.tip = 'Drag to rearrange';
        this.handleIcon.className = 'tip mw-handlemenu-handler-icon';
        this.handleTitle.className = 'mw-handlemenu-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.createButtons();
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            mw.element(scope.wrapper).toggleClass('active');
        };
        mw.element(document.body).on('click', function (e) {
            if(!DomService.hasParentWithId(e.target, scope.wrapper.id)){
                mw.element(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = document.createElement('span');
        btn.className = 'mw-handlemenu-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass
            }
            var icon = document.createElement('span');
            icon.className = iconClass + ' mw-handlemenu-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(document.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(data.action){
            btn.onmousedown = function (e) {
                e.preventDefault();
            };
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
                scope.hide()
            };
        }
        return btn;
    };

    this._defaultButtons = [

    ];

    this.createMenuDynamicHolder = function(item){
        var dn = document.createElement('div');
        dn.className = 'mw-handlemenu-menu-dynamic' + (item.className ? ' ' + item.className : '');
        return dn;
    };
    this.createMenu = function(){
        this.menu = document.createElement('div');
        this.menu.className = 'mw-handlemenu-menu ' + (this.options.menuClass ? this.options.menuClass : 'mw-handlemenu-menu-default');
        if (this.options.menu) {
            for (var i = 0; i < this.options.menu.length; i++) {
                if(this.options.menu[i].title !== '{dynamic}') {
                    this.menu.appendChild(this.menuButton(this.options.menu[i])) ;
                }
                else {
                    this.menu.appendChild(this.createMenuDynamicHolder(this.options.menu[i])) ;
                }

            }
        }
        this.wrapper.appendChild(this.menu);
    };
    this.createButton = function(obj){
        var btn = document.createElement('span');
        btn.className = 'tip mdi ' + obj.icon + (obj.className ? ' ' + obj.className : '');
        btn.dataset.tip = obj.title;
        if (obj.hover) {
            btn.addEventListener('mouseenter', obj.hover[0] , false);
            btn.addEventListener('mouseleave', obj.hover[1] , false);
        }
        btn.onclick = function () {
            this.classList.remove('active');
            obj.action(this);
            scope.hide();
        };
        return btn;
    };

    this.createButtons = function(){
        this.buttonsHolder = document.createElement('div');
        this.buttonsHolder.className = 'mw-handlemenu-buttons';
        if (this.options.buttons) {
            for (var i = 0; i < this.options.buttons.length; i++) {
                this.buttonsHolder.appendChild(this.createButton(this.options.buttons[i])) ;
            }
        }
        this.handle.appendChild(this.buttonsHolder);
    };
    this.create();
    this.hide();
}
