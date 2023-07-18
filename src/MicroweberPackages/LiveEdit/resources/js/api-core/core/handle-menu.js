import {ElementManager} from "./classes/element.js";
import {Tooltip} from "./tooltip.js";

export const HandleMenu = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.isVisible = function () {
        return this._visible;
    };

    this.show = function (){
        this._visible = true;
        this.root.addClass("mw-le-handle-menu-visible")
    };

    this.hide = function (){
        this._visible = false;
        this.root.removeClass("mw-le-handle-menu-visible")
    };

    this.create = function(){
        this.root = ElementManager({
            props: {
                className: 'mw-le-handle-menu',
                id: scope.options.id || 'mw-le-handle-menu-' + new Date().getTime()
            }
        });
        this.buttonsHolder = ElementManager({
            props: {
                className: 'mw-le-handle-menu-buttons'
            }
        });

        this.root.append(this.buttonsHolder);
    };

    var _title, titleText, titleIcon;

    var createTitle = function () {
        _title = ElementManager({
            props: {
                className: 'mw-le-handle-menu-title'
            }
        });
        titleText = ElementManager({
            props: {
                className: 'mw-le-handle-menu-title-text'
            }
        });
        titleIcon = ElementManager({
            props: {
                className: 'mw-le-handle-menu-title-icon'
            }
        });
        _title.append(titleText);
        _title.append(titleIcon);
        scope.root.prepend(_title);
        scope.title = _title
    };

    var _target = null;

    this.getTarget = function (){
        return _target;
    };

    this.setTarget = function (target) {
         
        _target = target;
        var i = 0;

        this.rebuildButtons()
       
 
        for ( ; i < this.buttons.length; i++) {
            
            if(this.buttons[i].config.onTarget) {
                this.buttons[i].config.onTarget(target, this.buttons[i].button.get(0), scope.options.rootScope);
            }
        }
    };



    this.setTitle = function (title, icon){
        titleText.html(title || '');
        titleIcon.html( icon || '');
    };

 


    this.prepareMenu = function() {
        this.buttons = [];
        ;(this.options.menus || []).forEach(function(itm){
            ;(itm.nodes || []).forEach(function(menuItem){
                scope.buttons.push(menuItem)
            })
        })
    }

    

    this.rebuildButtons = function() {
        this.prepareMenu()
        this.buttonsHolder.empty();
        this.buildButtons();
    }

    this.buildButtons = function (menu, btnHolder){
        this.prepareMenu();
        btnHolder = btnHolder || this.buttonsHolder;
        menu = menu || this.options.buttons;
        if(!menu) {
            return;
        }
        menu.forEach(function (btn){
            btnHolder.append(scope.button(btn));
        });
    };

    this.button = function (conf){
 
        var btn = ElementManager({
            props: {
                className: 'mw-le-handle-menu-button' + (conf.className ? ' ' + conf.className : '')
            }
        });
        var btnContenConf = {
            props: {
                className: 'mw-le-handle-menu-button-content'
            }
        };
        var btnContent = ElementManager(btnContenConf);

        if(conf.title) {
            Tooltip(btnContent, conf.title);
        }

        if(conf.icon) {
            var icon = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-icon',
                    innerHTML: conf.icon
                }
            });

            btnContent.append(icon);
        }
        if(conf.text) {
            var text = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-text',
                    innerHTML: conf.text
                }
            });

            btnContent.append(text);
        }


        btn.append(btnContent);
        this.buttons.push({
            button: btn,
            config: conf,
        });
        if(conf.menu) {
            var submenu = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-sub-menu'
                }
            });
            btn.append(submenu);
            scope.buildButtons(conf.menu, submenu);
            btn.on('click', function(){
                this.classList.toggle('sub-menu-active');
            });
        } else if(typeof conf.action === 'function') {
            btn.on('click', function(){
                conf.action(scope.getTarget(), btn.get(0));
            });
        }
        return btn;
    };

    this.init = function () {
        this.create();
        createTitle();
        this.setTitle(scope.options.title, scope.options.icon);
        this.buildButtons();
        this.hide();

    }
    this.init()


}
