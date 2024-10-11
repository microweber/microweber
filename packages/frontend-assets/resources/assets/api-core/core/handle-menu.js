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

        this.rebuildButtons();

        this.root.parents('.mw-handle-item').hide();

        setTimeout(() => {

            this.root.parents('.mw-handle-item').show()

            for ( ; i < this.buttons.length; i++) {
                const config = this.buttons[i].config;
                const button = this.buttons[i].button;

                if(config && config.onTarget) {

                    config.onTarget(target, button.get(0), scope.options.rootScope);
                }
            }

            if(options.handleScope && options.handleScope.handle && options.handleScope.handle.draggable) {

                options.handleScope.handle.draggable.handleInit();
            }


            if(_target) {
                _target.ownerDocument.querySelectorAll('.mw-le-handle-menu-button-sub-menu').forEach(node => {
                    const allButtons = node.querySelectorAll('.mw-le-handle-menu-button');
                    const allHiddenButtons = node.querySelectorAll('.mw-le-handle-menu-button-hidden');
                    if(allButtons.length === allHiddenButtons.length) {
                        node.parentNode.classList.add('mw-le-handle-menu-button-hidden')
                    } else {
                        node.parentNode.classList.remove('mw-le-handle-menu-button-hidden')
                    }
                })
            }

            this.root.parents('.mw-handle-item').show();


        }, 50)


    };



    this.setTitle = function (title, icon){
        titleText.html(title || '');
        titleIcon.html( icon || '');
    };



    this.buttons = [];
    this.prepareMenu = function(parent) {
        if(!parent) {
            parent = this;
        }
        parent.buttons = [];

    }

    this.getMenu = function(name) {
        for (let i = 0; i < this.options.menus.length; i++) {
            if(this.options.menus[i].name === name){
                return this.options.menus[i].name;
            }
        }

    }
    this.setMenu = function(name, nodes) {
        let found = false;
        for (let i = 0; i < this.options.menus.length; i++) {
            if(this.options.menus[i].name === name){
                this.options.menus[i].nodes = nodes;
                found = true;
            }
        }
        if(!found) {
            this.options.menus.push({
                name, nodes
            })
        }
        this.rebuildButtons()
    }



    this.rebuildButtons = function() {

        this.buttonsHolder.empty();
        this.buildButtons();
    }

    this.buildButtons = function (menu, btnHolder, parent){
        this.prepareMenu(parent);

        btnHolder = btnHolder || this.buttonsHolder;
        menu = menu || this.options.menus;

        if(!menu) {
            return;
        }

        menu.filter(itm => !!itm).forEach(function (itm){
            if(itm.nodes && itm.nodes.forEach) {
                var holder = btnHolder;

                if(itm.holder) {
                    holder = ElementManager({
                        props: {
                            className: 'mw-le-handle-menu-button-holder',
                        }
                    });
                    btnHolder.append(holder);
                }

                itm.nodes.forEach(function (btn){
                    holder.append(scope.button(btn));
                });
            } else if(itm.title || itm.icon) {
                scope.button(itm)
            }
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



        if(conf.icon) {
            var icon = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-icon',
                    innerHTML: conf.icon
                }
            });

            btnContent.append(icon);
        }

        if(conf.title) {
            Tooltip(btnContent, conf.title);
            var btnTitleConf = {
                props: {
                    className: 'mw-le-handle-menu-button-content-title',
                    innerHTML: conf.title
                },

            };
            var btnTitle = ElementManager(btnTitleConf);
            btnContent.append(btnTitle);
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

        const actionEvents = 'mousedown touchstart';
        if(conf.menu) {
            var submenu = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-sub-menu'
                }
            });
            btn.append(submenu);
            scope.buildButtons(conf.menu, submenu, submenu);
            btn.on(actionEvents, function(){
                Array.from(this.ownerDocument.querySelectorAll('.sub-menu-active'))
                .filter(node => node !== this)
                .forEach(node => node.classList.remove('sub-menu-active'));

                this.classList.toggle('sub-menu-active');
            });
        } else if(typeof conf.action === 'function') {
            btn.on(actionEvents, function(){
                Array.from(this.ownerDocument.querySelectorAll('.sub-menu-active'))
                .filter(node => node !== this)
                .forEach(node => node.classList.remove('sub-menu-active'));
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
