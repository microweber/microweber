
mw.require('widgets.css');
mw.require('xss.js');
mw.require('form-controls.js');




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

    if(this.settings.hideTextFied) {
        for (var i = 0; i < this.settings.controllers.length; i++) {
            if(!this.settings.controllers[i].config) {
                this.settings.controllers[i].config = {};
            }
            this.settings.controllers[i].config.text = false;
        }
    }

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
                // data.text = _filterXSS(data.text);
            }
        }
        return data;
    };



    var handleSelect = function (__for, target) {
        // audit-test 2026-05-07 PM TASK-008 / TICKET-MM (Surface 3 — Link Picker tabs):
        // Bootstrap's data-bs-toggle="tab" auto-updates aria-selected/expanded; this
        // imperative handler must do it manually. Per agent-test Gotcha #1, every tab
        // gets aria-selected + tabindex updated, not just visual `.active`. Per
        // ARIA tabs pattern, focus follows the active tab so keyboard users can
        // continue arrow-key navigation from the new tab.
        [].forEach.call(scope.nav.querySelectorAll('li a'), function (item){
            var isActive = item.__for === __for;
            if(isActive) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
            if(item.getAttribute('role') === 'tab') {
                item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                item.setAttribute('tabindex', isActive ? '0' : '-1');
            }
        });
        scope.controllers.forEach(function (item){item.controller.root.classList.remove('active');});
        if(target && target.classList) {
            target.classList.add('active');
            if(target.getAttribute('role') === 'tab' && typeof target.focus === 'function') {
                // Move keyboard focus to the activated tab — required for the
                // ARIA tabs pattern when a click also serves keyboard users.
                target.focus({ preventScroll: true });
            }
        }
        __for.controller.root.classList.add('active');
        if(scope.dialog) {
            scope.dialog.center();
        }
    };


    this.selectController = function(type) {
        var ctrl = scope.controllers.find(c => c.type === type);
        if(ctrl) {
            handleSelect(ctrl)
        }
    }


    this.buildNavigation = function (){
        if(this.settings.nav === 'tabs') {
            this.nav = document.createElement('ul');
            this.nav.className = 'nav nav-tabs mw-ac-editor-nav border-0 col-3 gap-5 d-flex flex-column';

            // audit-test 2026-05-07 PM TASK-008 / TICKET-MM (Surface 3) +
            // Gotcha #3: per-instance id so multiple Link Pickers on one page
            // don't collide on tab/panel id.
            var instanceId = 'mw-link-picker-' + Math.random().toString(36).slice(2, 10);
            this.nav.setAttribute('role', 'tablist');
            this.nav.setAttribute('aria-label', mw.lang('Link type'));

            var nav = scope.controllers;
            var dropdown = [];

            var navTitle = document.createElement('li');
            navTitle.setAttribute('role', 'presentation');
            navTitle.innerHTML = `<label class="form-label font-weight-bold mt-2 p-0">${mw.lang('Choose from')}</label>`;

            scope.nav.appendChild(navTitle)



            var createA = function (ctrl, index) {
                var li =  document.createElement('li');
                li.className = 'pe-3'
                li.setAttribute('role', 'presentation');
                var a =  document.createElement('a');
                a.className = 'mw-admin-action-links mw-adm-liveedit-tabs' + (index === 0 ? ' active' : '');
                // audit-test 2026-05-07 PM TASK-008 / TICKET-MM:
                // ARIA tabs pattern — role="tab", per-instance id + aria-controls
                // back to the panel, aria-selected/tabindex pair (only the active
                // tab is in the Tab order, others reachable via Arrow keys).
                a.setAttribute('role', 'tab');
                a.setAttribute('id', instanceId + '-tab-' + index);
                a.setAttribute('aria-controls', instanceId + '-panel-' + index);
                a.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
                a.setAttribute('tabindex', index === 0 ? '0' : '-1');
                a.innerHTML = ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title);
                a.__for = ctrl;
                // Point the matching panel back at this tab so screen readers
                // announce the relationship in both directions.
                if(ctrl.controller && ctrl.controller.root) {
                    ctrl.controller.root.setAttribute('role', 'tabpanel');
                    ctrl.controller.root.setAttribute('id', instanceId + '-panel-' + index);
                    ctrl.controller.root.setAttribute('aria-labelledby', instanceId + '-tab-' + index);
                    ctrl.controller.root.setAttribute('tabindex', '0');
                }
                a.onclick = function (){
                    handleSelect(this.__for, this);
                };
                li.appendChild(a)
                return li;
            };


            nav.forEach(function (ctrl, index){
                scope.nav.appendChild(createA(ctrl, index));
            });

            // audit-test 2026-05-07 PM TASK-008 / TICKET-MM (Surface 3) +
            // Gotcha #2: Arrow-key navigation on the tablist. Skips when focus
            // is inside the dropdown (`.admin-action-links-dropdown`) so the
            // overflow menu's own keyboard handling still works. Wraps from
            // last tab → first and vice versa, matching the WAI-ARIA tabs
            // pattern. Home/End jump to first/last as a bonus.
            scope.nav.addEventListener('keydown', function (event) {
                if (event.target.closest && event.target.closest('.admin-action-links-dropdown')) {
                    return;
                }
                var key = event.key;
                if (key !== 'ArrowLeft' && key !== 'ArrowRight' &&
                    key !== 'ArrowUp' && key !== 'ArrowDown' &&
                    key !== 'Home' && key !== 'End') {
                    return;
                }
                var tabs = [].slice.call(scope.nav.querySelectorAll('a[role="tab"]'));
                if (tabs.length === 0) {
                    return;
                }
                var current = tabs.indexOf(event.target);
                if (current < 0) {
                    current = tabs.findIndex(function (t) { return t.getAttribute('aria-selected') === 'true'; });
                }
                var next = current;
                if (key === 'ArrowLeft' || key === 'ArrowUp') {
                    next = (current - 1 + tabs.length) % tabs.length;
                } else if (key === 'ArrowRight' || key === 'ArrowDown') {
                    next = (current + 1) % tabs.length;
                } else if (key === 'Home') {
                    next = 0;
                } else if (key === 'End') {
                    next = tabs.length - 1;
                }
                if (next !== current) {
                    event.preventDefault();
                    event.stopPropagation();
                    tabs[next].click();
                }
            });

            this.settings.selectedIndex = this.settings.selectedIndex || 0;


            let navCount = -1;
            for(let i = 0; i < this.nav.children.length; i++) {
                const link = this.nav.children[i].querySelector('a');
                if(link) {
                    navCount++;
                    if(navCount === this.settings.selectedIndex) {
                        link.click()
                    }
                }
            }


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
        this.root.setAttribute('x-data', '{}');
        this.root.setAttribute('x-trap', 'true');
        this.root.onclick = function (e) {
            var le2 = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['mw-link-editor-nav-drop-box', 'mw-link-editor-more-button']);
            if(!le2) {
                mw.element('.mw-link-editor-nav-drop-box').hide();
            }
        };

        this.root.className = 'mw-link-editor-root position-relative mw-link-editor-root-inIframe-' + (window.self !== window.top )

        // AI-84 / TICKET-YY (cycle-95 2026-05-09): close button.
        // Pre-fix: `<span onclick="mw.dialog.get(this).remove()">` —
        // a CSP-violating inline `onclick=` (CSP `script-src 'self'`
        // would block it) AND a non-button element (no keyboard
        // semantics, no aria-label, screen readers announced
        // "image" or nothing). Now a real `<button type="button">`
        // with `aria-label="Close"`, keyboard-activatable, and
        // wired via addEventListener (no inline handler).
        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'x-close-modal-link mw-link-editor-close';
        closeBtn.setAttribute('aria-label', mw.lang('Close'));
        closeBtn.innerHTML = '<i class="mdi mdi-close" aria-hidden="true"></i>';
        closeBtn.addEventListener('click', function () {
            if (scope.dialog && typeof scope.dialog.remove === 'function') {
                scope.dialog.remove();
            }
        });
        this.root.appendChild(closeBtn);

        this.buildControllers ();
        if(this.settings.mode === 'dialog') {
            // AI-84 / TICKET-YY (cycle-95): focus-restore. Capture the
            // element that had focus BEFORE the dialog opened so
            // close-time focus can return to it. Without this, screen-
            // reader users get dropped to <body> after dismissing the
            // dialog, losing keyboard context and (often) needing to
            // tab through the entire admin chrome to get back where
            // they were.
            var previouslyFocused = (function () {
                try {
                    var doc = mw.top().win ? mw.top().win.document : document;
                    return (doc && doc.activeElement) || null;
                } catch (e) {
                    return document.activeElement || null;
                }
            })();

            this.dialog = mw.top().dialog({
                content: this.root,
                width: 1000,
                skin: 'default mw_modal_live_edit_link_editor_settings',
                height: 'auto',
                minHeight: 400,
                title: this.settings.title,
                shadow: false,
            });

            // AI-84 / TICKET-YY (cycle-95): dialog ARIA. Add role,
            // aria-modal, and aria-labelledby on the dialog frame
            // so assistive tech announces "Link Settings dialog" on
            // open and traps the modal-context interaction model.
            // The dialog skin already centers itself; ARIA goes on
            // the outer dialogMain wrapper.
            try {
                if (this.dialog && this.dialog.dialogMain) {
                    var dlgRoot = this.dialog.dialogMain;
                    dlgRoot.setAttribute('role', 'dialog');
                    dlgRoot.setAttribute('aria-modal', 'true');
                    var titleNode = dlgRoot.querySelector('.mw-ui-modal-header, .modal-title, .mw-ui-dialog-title');
                    if (titleNode) {
                        if (!titleNode.id) {
                            titleNode.id = 'mw-link-editor-title-' + Math.random().toString(36).slice(2, 10);
                        }
                        dlgRoot.setAttribute('aria-labelledby', titleNode.id);
                    }
                }
            } catch (e) { /* dialog markup variations across skins; ARIA is a non-blocking enhancement */ }

            this.dialog.center();

            var handleDialogEscapeKey = function (e){
                //if esc key
                if(e.keyCode === 27) {
                    e.stopPropagation();
                    e.preventDefault();

                    scope.dialog.remove();
                }


            };




            if(this.dialog.dialogMain) {
                this.dialog.dialogMain.addEventListener('keydown', handleDialogEscapeKey);
            }


            // AI-84 / TICKET-YY (cycle-95): focus-restore. Wrap
            // dialog.remove() so the element that had focus before
            // the dialog opened is re-focused after the dialog tears
            // down. Guard with try/catch because the previously-
            // focused element may have been removed from the DOM
            // (e.g. if Live Edit redrew the toolbar mid-dialog) or
            // may be inside a now-detached iframe.
            var restoreFocus = function () {
                try {
                    if (previouslyFocused && typeof previouslyFocused.focus === 'function' && previouslyFocused.isConnected !== false) {
                        previouslyFocused.focus({ preventScroll: true });
                    }
                } catch (e) { /* element may have been removed */ }
            };

            this.onConfirm(function (){
                scope.dialog.remove();
                restoreFocus();
            });



            this.onCancel(function (){
                scope.dialog.remove();
                restoreFocus();
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


