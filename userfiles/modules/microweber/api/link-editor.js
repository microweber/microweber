
mw.require('widgets.css');
mw.require('form-controls.js');
mw.lib.require('xss');


    mw.LinkEditor = function(options) {

        var scope = this;
        var defaults = {
            mode: 'dialog',
            controllers: [
                { type: 'url'},
                { type: 'page' },
                { type: 'post' },
                { type: 'file' },
                { type: 'email' },
                { type: 'layout' },
                /*{ type: 'title' },*/
            ],
            title: '<i class="mdi mdi-link mw-link-editor-icon"></i> ' + mw.lang('Link Settings'),
            nav: 'tabs',
            safeMode: true
        };

        this._confirm = [];
        this.onConfirm = function (c) {
            this._confirm.push(c);
        };

        this._cancel = [];
        this.onCancel = function (c) {
            this._cancel.push(c);
        };

        this.setValue = function (data, controller) {
            data = this.cleanData(data);
            controller = controller || 'auto';

            if(controller === 'auto') {
                this.controllers.forEach(function (item){
                    item.controller.setValue(data);
                });
            } else {
                this.controllers.find(function (item){
                    return item.type === controller;
                }).controller.setValue(data);
            }

            return this;
        };

        this.settings =  mw.object.extend({}, defaults, options || {});


        var _filterXSS = function (html){
            var options = {

            };

            return (filterXSS(html, options)) ;
        };
        this.cleanData = function (data) {
            if(scope.settings.safeMode) {
                data = Object.assign({}, data);
                if(data.url) {
                    data.url = _filterXSS(data.url);
                }
                if(data.text) {
                    data.text = _filterXSS(data.text);
                }
            }
            return data;
        };


        this.buildNavigation = function (){
            if(this.settings.nav === 'tabs') {
                this.nav = document.createElement('ul');
                 this.nav.className = 'nav nav-tabs mw-ac-editor-nav border-0 col-4 gap-3 d-flex flex-column';

                var nav = scope.controllers.slice(0, 4);
                var dropdown = scope.controllers.slice(4);

                var handleSelect = function (__for, target) {
                    [].forEach.call(scope.nav.querySelectorAll('li a'), function (item){item.classList.remove('active');});
                    scope.controllers.forEach(function (item){item.controller.root.classList.remove('active');});
                    if(target && target.classList) {
                        target.classList.add('active');
                    }
                    __for.controller.root.classList.add('active');
                    if(scope.dialog) {
                        scope.dialog.center();
                    }
                };

                var createA = function (ctrl, index) {
                    var li =  document.createElement('li');
                    li.className = 'pe-3'
                    var a =  document.createElement('a');
                    a.className = 'mw-admin-action-links mw-adm-liveedit-tabs' + (index === 0 ? ' active' : '');
                    a.innerHTML = ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title);
                    a.__for = ctrl;
                    a.onclick = function (){
                        handleSelect(this.__for, this);
                    };
                    li.appendChild(a)
                    return li;
                };


                nav.forEach(function (ctrl, index){
                    scope.nav.appendChild(createA(ctrl, index));
                });
                this.settings.selectedIndex = this.settings.selectedIndex || 0
                this.nav.children[this.settings.selectedIndex].querySelector('a').click();
                this.root.prepend(this.nav);

                if(dropdown.length) {
                    const dropdownEl = mw.element(`
                        <li class="pe-3 dropdown admin-action-links-dropdown">

                                <a class="mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="dropdown">${mw.lang('More')}</a>
                                <div class="dropdown-menu">

                                </div>

                        </li>

                    `);



                    dropdown.forEach(function (ctrl, index){

                        mw.element('.dropdown-menu', dropdownEl)
                            .append(mw.element({
                                tag: 'span',
                                props: {
                                    className: 'dropdown-item',
                                    __for: ctrl,
                                    innerHTML: ('<i class="' + ctrl.controller.settings.icon + '"></i> '+ctrl.controller.settings.title),
                                    onclick: function () {
                                         handleSelect(this.__for);
                                        mw.element(dropdownEl).hide();
                                    }
                                }
                            }));
                    });
                    this.nav.append(dropdownEl.get(0));





                }
            }

        };

        this.buildControllers = function (){
            this.controllers = [];
            this.settings.controllers.forEach(function (item) {
                if(mw.UIFormControllers[item.type]) {
                    var ctrl = new mw.UIFormControllers[item.type](item.config);
                    scope.root.appendChild(ctrl.root);
                    scope.controllers.push({
                        type: item.type,
                        controller: ctrl
                    });
                    ctrl.onConfirm(function (data){
                        data = scope.cleanData(data);
                        scope._confirm.forEach(function (f){
                            f(data);
                        });
                    });
                    ctrl.onCancel(function (){
                        scope._cancel.forEach(function (f){
                            f();
                        });
                    });
                }

            });
        };
        this.build = function (){
            this.root = document.createElement('div');
            this.root.onclick = function (e) {
                var le2 = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['mw-link-editor-nav-drop-box', 'mw-link-editor-more-button']);
                if(!le2) {
                    mw.element('.mw-link-editor-nav-drop-box').hide();
                }
            };

            this.root.className = 'mw-link-editor-root mw-link-editor-root-inIframe-' + (window.self !== window.top )
            this.buildControllers ();
            if(this.settings.mode === 'dialog') {
                this.dialog = mw.top().dialog({
                    content: this.root,
                    width: 860,
                    skin: 'default mw_modal_live_edit_link_editor_settings',
                    height: 'auto',
                    title: this.settings.title,
                    overflowMode: 'visible',
                    shadow: false
                });
                this.dialog.center();
                this.onConfirm(function (){
                    scope.dialog.remove();
                });
                this.onCancel(function (){
                    scope.dialog.remove();
                });
            } else if(this.settings.mode === 'element') {
                this.settings.element.append(this.root);
            }
        };
        this.init = function(options) {
            this.build();
            this.buildNavigation();
        };
        this.init();
        this.promise = function () {
            return new Promise(function (resolve){
                scope.onConfirm(function (data){
                    resolve(data);
                });
                scope.onCancel(function (){
                    resolve();
                });
            });
        };
    };


