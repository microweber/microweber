
mw.require('widgets.css');

(function(){
    var LinkEditor = function(options) {
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
            title: mw.lang('Link Settings'),
            nav: 'tabs'
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
            controller = controller || 'url';
            this.controllers.find(function (item){
                return item.type === controller;
            }).controller.setValue(data);
        };


        this.settings = extend({}, defaults, options || {});

        this.buildNavigation = function (){
            if(this.settings.nav === 'tabs') {
                this.nav = document.createElement('nav');
                this.nav.className = 'nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3';

                var nav = scope.controllers.slice(0, 4);
                var dropdown = scope.controllers.slice(4);

                var handleSelect = function (__for, target) {
                    [].forEach.call(scope.nav.children, function (item){item.classList.remove('active');});
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
                    var a =  document.createElement('a');
                    a.className = 'btn btn-outline-secondary justify-content-center' + (index === 0 ? ' active' : '');
                    a.innerHTML = ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title);
                    a.__for = ctrl;
                    a.onclick = function (){
                        handleSelect(this.__for, this);
                    };
                    return a;
                };


                nav.forEach(function (ctrl, index){
                    scope.nav.appendChild(createA(ctrl, index));
                });
                this.nav.children[0].click();
                this.root.prepend(this.nav);

                if(dropdown.length) {
                    var dropdownElBtn =  document.createElement('span');
                    var dropdownEl =  document.createElement('div');
                      dropdownElBtn.className = 'btn dropdown-toggle bs-placeholder';
                      dropdownEl.className = 'mw-link-editor-nav-drop-box';
                      dropdownEl.style.display = 'none';

                    dropdownElBtn.innerHTML = mw.lang('More');
                    dropdown.forEach(function (ctrl, index){

                        mw.element(dropdownEl)
                            .append(mw.element({
                                tag: 'span',
                                props: {
                                    className: '',
                                    __for: ctrl,
                                    innerHTML: ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title),
                                    onclick: function () {
                                         handleSelect(this.__for);
                                        mw.element(dropdownEl).hide();
                                    }
                                }
                            }));
                    });
                    this.nav.append(dropdownEl);
                    this.nav.append(dropdownElBtn);
                    dropdownElBtn.onclick = function (){
                        mw.element(dropdownEl).toggle();
                    };

                    dropdownEl.onchange = function () {
                        handleSelect(this.options[this.selectedIndex].__for);
                    };
                    /*setTimeout(function (){
                        if($.fn.selectpicker) {
                            $('.selectpicker').selectpicker();
                        }
                    }, 100)*/
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


            this.buildControllers ();
            if(this.settings.mode === 'dialog') {
                this.dialog = mw.dialog({
                    content: this.root,
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
    mw.LinkEditor = LinkEditor;

})();
