import {DomService} from '../classes/dom';
import {ElementManager} from "../classes/element";
import {Tooltip} from "./tooltip";

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
    }

    this.hide = function (){
        this._visible = false;
        this.root.removeClass("mw-le-handle-menu-visible")
    }

    this.create = function(){
        this.root = ElementManager({
            props: {
                className: 'mw-le-handle-menu',
                id: scope.options.id || 'mw-le-handle-menu-' + new Date().getTime()
            }
        })
        this.buttonsHolder = ElementManager({
            props: {
                className: 'mw-le-handle-menu-buttons'
            }
        })

        this.root.append(this.buttonsHolder);
    }
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
    }
    var _target = null;

    this.getTarget = function (){
        return _target;
    }

    this.setTarget = function (target) {
        _target = target;
        var i = 0;
        for ( ; i < this.buttons.length; i++) {
            if(this.buttons[i].config.onTarget) {
                this.buttons[i].config.onTarget(target, this.buttons[i].button.get(0), scope.options.rootScope);
            }
        }
    }



    this.setTitle = function (title, icon){
        titleText.html(title || '');
        titleIcon.html( icon || '');
    }
    this.buttons = [];

    this.buildButtons = function (menu, btnHolder){
        btnHolder = btnHolder || this.buttonsHolder;
        menu = menu || this.options.buttons;
        menu.forEach(function (btn){
            btnHolder.append(scope.button(btn));
        })
    }
    this.button = function (conf){
        /*
        * {
                title: mw.lang('Settings1212'),
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z" /></svg>',
                className: 'mw-handle-insert-button',
                menu: [

                ],
            },
        *
        * */
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
            })
            btnContent.append(icon);
        }
        if(conf.text) {
            var text = ElementManager({
                props: {
                    className: 'mw-le-handle-menu-button-text',
                    innerHTML: conf.text
                }
            })
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
            scope.buildButtons(conf.menu, submenu)
            btn.on('click', function(){
                this.classList.toggle('sub-menu-active');
            })
        } else if(typeof conf.action === 'function') {
            btn.on('click', function(){
                conf.action(scope.getTarget(), btn.get(0), scope.options.rootScope)
            })
        }
        return btn;
    }

    this.init = function () {
        this.create()
        createTitle();
        this.setTitle(scope.options.title, scope.options.icon);
        this.buildButtons();
        this.hide();

    }
    this.init()


}
