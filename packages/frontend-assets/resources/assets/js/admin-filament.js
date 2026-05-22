import BaseComponent from "../core/base-class.js";
import AdminFilamentHelpers from "./admin-filament-helpers.js";

export class AdminFilament extends BaseComponent {

    helpers = null;

    constructor() {
        super();

        this.helpers = new AdminFilamentHelpers();

        document.addEventListener('livewire:init', () => {
            this.init();
        });


    }

    init() {

        this.hookOptionSaved();
        this.hookLivewireLoadingState();

        // AI-984 / task-2026-05-22 — Ctrl/Cmd+S global save shortcut.
        // Triggers a click on the first visible Filament submit button so admins
        // can save forms with the familiar keyboard shortcut without scrolling.
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const submitBtn = document.querySelector(
                    'button[type="submit"].fi-btn, .fi-form-actions button[type="submit"]'
                );
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.click();
                }
            }
        });

        // AI-983 / task-2026-05-22 — Live character counter on SEO / meta fields.
        // Injects a small "N/160" counter below any input or textarea that has
        // data-mw-char-limit attribute (set via the field's extraAttributes in PHP).
        // Fires on DOMContentLoaded + Livewire navigation to handle SPA re-renders.
        this.initCharCounters();
        document.addEventListener('livewire:navigated', () => this.initCharCounters());

        // AI-954 / task-2026-05-22 — WCAG 2.4.3 focus trap for Filament modals.
        // Filament v5 ships x-trap.noscroll via Alpine's @alpinejs/focus plugin,
        // which should trap Tab inside the modal. As a defence-in-depth layer, we
        // also apply `inert` to page content behind the modal overlay so non-Alpine
        // focus (e.g. screen-reader virtual cursor) also stays within the dialog.
        this.initModalFocusTrap();

        // AI-972 / task-2026-05-22 — Consistent unsaved-changes guard across all
        // Filament admin forms. Filament's non-SPA setUpUnsavedDataChangesAlert only
        // hooks window.beforeunload (tab close) — it does NOT intercept Livewire's
        // livewire:navigate event (sidebar clicks). This supplement catches the
        // navigate event and prompts using the same savedDataHash check so ALL forms
        // show the prompt consistently, not just on tab close.
        this.initLivewireNavigateUnsavedGuard();

        // AI-942 / task-2026-05-22 — Confirm-on-close for create/edit modals when
        // form data has been entered. Filament action modals (CreateAction, EditAction)
        // have no built-in dirty-state check on Escape/X/Cancel. We intercept the
        // Escape keydown event and any click on .fi-modal-close-btn (X) or
        // .fi-modal-cancel-btn (Cancel) and check for filled inputs inside the modal.
        this.initModalDirtyGuard();

    }

    initModalFocusTrap() {
        // Watch for open Filament modals and apply `inert` to the background page
        // content, ensuring WCAG 2.4.3 compliance even if Alpine's x-trap misses
        // an edge case (e.g. Livewire re-render during dialog open).
        var mainContent = document.querySelector('.fi-main') || document.querySelector('main');
        var sidebar = document.querySelector('.fi-sidebar');

        if (!mainContent) return;

        var obs = new MutationObserver(function () {
            // Detect any open Filament modal: look for .fi-modal-window that is not hidden
            var anyOpen = document.querySelector('.fi-modal-window:not([hidden])');

            if (anyOpen) {
                mainContent && mainContent.setAttribute('inert', '');
                sidebar && sidebar.setAttribute('inert', '');
            } else {
                mainContent && mainContent.removeAttribute('inert');
                sidebar && sidebar.removeAttribute('inert');
            }
        });

        obs.observe(document.body, { childList: true, subtree: true, attributeFilter: ['hidden', 'class'] });
    }

    initModalDirtyGuard() {
        // Detect if an open Filament modal has any filled form inputs.
        // Returns true when at least one text/textarea/select has a non-empty value.
        function modalHasDirtyData(modal) {
            if (!modal) return false;
            var inputs = modal.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], textarea');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].value && inputs[i].value.trim() !== '') return true;
            }
            return false;
        }

        // Intercept Escape key — only when a modal is open and has dirty data.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            var modal = document.querySelector('.fi-modal-window:not([hidden])');
            if (!modal || !modalHasDirtyData(modal)) return;
            if (!confirm('You have unsaved changes. Close this form and lose your work?')) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);  // capture phase so we fire before Alpine/Livewire close handlers

        // Intercept modal close-X and Cancel button clicks.
        document.addEventListener('click', function (e) {
            var closeBtn = e.target.closest('.fi-modal-close-btn, [x-on\\:click*="close"], button[aria-label="Close"]');
            if (!closeBtn) return;
            var modal = closeBtn.closest('.fi-modal-window');
            if (!modal || !modalHasDirtyData(modal)) return;
            if (!confirm('You have unsaved changes. Close this form and lose your work?')) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);  // capture phase
    }

    initLivewireNavigateUnsavedGuard() {
        // Filament's non-SPA mode setUpUnsavedDataChangesAlert listens only to
        // window.beforeunload. When users click sidebar links, Livewire fires
        // a livewire:navigate event instead. This guard intercepts that event
        // and prompts the user if any Livewire component has unsaved form data
        // (detected via the same $wire.data vs savedDataHash comparison
        // that Filament's SPA-mode guard uses).
        document.addEventListener('livewire:navigate', function (e) {
            try {
                var allComponents = Livewire.all ? Livewire.all() : [];
                var hasUnsaved = allComponents.some(function (component) {
                    if (!component.savedDataHash) return false;
                    var hash = window.jsMd5
                        ? window.jsMd5(JSON.stringify(component.data).replace(/\\/g, ''))
                        : null;
                    return hash && hash !== component.savedDataHash;
                });
                if (hasUnsaved && !confirm('You have unsaved changes. Leave this page?')) {
                    e.preventDefault();
                }
            } catch (_) {
                // fail-safe: if the check throws, allow navigation
            }
        });
    }

    hookLivewireLoadingState() {
        // add .mw-livewire-loading class to body when live wire is loading

        Livewire.hook('commit.prepare', ({component, commit}) => {
            this.addLoadingClassToBody();
        })


        // Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
        //     // Runs after commit payloads are compiled, but before a network request is sent...
        //
        //     respond(({ status, response }) => {
        //       //  this.removeLoadingClassToBody();
        //     })
        //
        //     succeed(({ status, json }) => {
        //         this.removeLoadingClassToBody();
        //     })
        //
        //     fail(({ status, content, preventDefault }) => {
        //         this.removeLoadingClassToBody();
        //     })
        // })



        // Livewire.hook('commit', ({component, commit, respond, succeed, fail}) => {
        //
        //     respond(() => {
        //      //   this.removeLoadingClassToBody();
        //     })
        //
        //     succeed(({snapshot, effect}) => {
        //         this.removeLoadingClassToBody();
        //     })
        //
        //     fail(() => {
        //         this.removeLoadingClassToBody();
        //     })
        // })
        //

        Livewire.hook('message.sent', (message, component) => {
            this.addLoadingClassToBody();
        })

        Livewire.hook('message.processed', (message, component) => {
            this.removeLoadingClassToBody();
        })

        // AI-925 / task-2026-05-22 — Livewire tables blank after CSRF mismatch.
        // The custom VerifyCsrfToken middleware returns 400 on token mismatch;
        // standard Laravel returns 419. Both indicate a stale CSRF token. Rather
        // than silently leaving the table blank, we surface a dismissible banner
        // so operators know they need to reload the page.
        Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419 || status === 400) {
                    this.removeLoadingClassToBody();
                    this.showSessionExpiredBanner();
                    preventDefault(); // suppress Livewire's own error handling
                }
            });
        });

        //
        // Livewire.hook('morph.added', ({el, component}) => {
        //    // this.removeLoadingClassToBody();
        // })
        // Livewire.hook('morph.adding', () => {
        //   //  this.addLoadingClassToBody();
        // })
        //
        // Livewire.hook('morph.updated', ({el, component}) => {
        //  //   this.removeLoadingClassToBody();
        // })
        // Livewire.hook('morph.updating', () => {
        //     this.addLoadingClassToBody();
        // })
        // Livewire.hook('morph.removed', ({el, component}) => {
        //   //  this.removeLoadingClassToBody();
        // })
        // Livewire.hook('morph.removing', () => {
        //  //   this.addLoadingClassToBody();
        // })
    }

    showSessionExpiredBanner() {
        // Prevent stacking duplicate banners
        if (document.getElementById('mw-session-expired-banner')) {
            return;
        }
        const banner = document.createElement('div');
        banner.id = 'mw-session-expired-banner';
        banner.setAttribute('role', 'alert');
        banner.innerHTML = `
            <span>Your session has expired.</span>
            <a href="javascript:void(0)" onclick="window.location.reload()" style="font-weight:600;text-decoration:underline;margin-left:8px;">Reload the page</a>
            <button onclick="this.closest('#mw-session-expired-banner').remove()" aria-label="Dismiss" style="margin-left:12px;background:transparent;border:none;cursor:pointer;color:inherit;">✕</button>
        `;
        banner.style.cssText = [
            'position:fixed', 'top:0', 'left:0', 'right:0', 'z-index:99999',
            'background:#dc2626', 'color:#fff', 'padding:10px 20px',
            'display:flex', 'align-items:center', 'justify-content:center',
            'font-size:14px', 'font-family:inherit', 'gap:4px',
        ].join(';');
        document.body.prepend(banner);
    }

    addLoadingClassToBody() {

        document.body.classList.add('mw-livewire-loading');
    }
    timeout = null;
    removeLoadingClassToBody() {

        clearTimeout(this.timeout);
        this.timeout = setTimeout(function () {
            document.body.classList.remove('mw-livewire-loading');
        }, 500);

    }

    hookOptionSaved() {

        Livewire.on('mw-option-saved', function ($event) {
            if ($event.optionGroup !== undefined) {

                    if (typeof top.mw !== 'undefined'
                        && typeof top.mw.top !== 'undefined'
                        && typeof top.mw.top().app !== 'undefined'
                        && typeof top.mw.top().app.liveEdit !== 'undefined'
                    ) {
                        var canvasDocument = mw.top().app.canvas.getDocument();
                        var canvasWindow = mw.top().app.canvas.getWindow();





                        var reloadedWithLiveweire = false;
                        if(canvasWindow.Livewire){
                            //check if is liveweire module and reload it
                            var moduleWireId = canvasDocument.querySelector('#' + $event.optionGroup+ '> [wire\\:id]');
                            if(moduleWireId){
                                moduleWireId = moduleWireId.getAttribute('wire:id');
                                var component = canvasWindow.Livewire.find(moduleWireId);
                                component.$refresh();
                                reloadedWithLiveweire = true;
                            }
                        }
                        if(!reloadedWithLiveweire){
                            top.mw.top().reload_module_everywhere('#' + $event.optionGroup);
                        }


                    }

            }
        });
    }

    // AI-983 / task-2026-05-22 — attach live character counters to admin
    // inputs/textareas that carry data-mw-char-limit attribute. Called on
    // init and after every Livewire navigation so dynamically-mounted fields
    // (e.g. SEO tab fields that render on tab switch) are also instrumented.
    initCharCounters() {
        document.querySelectorAll('[data-mw-char-limit]:not([data-mw-char-counter-wired])').forEach((el) => {
            el.setAttribute('data-mw-char-counter-wired', '1');
            const limit = parseInt(el.getAttribute('data-mw-char-limit'), 10);
            if (!limit || isNaN(limit)) return;

            const counter = document.createElement('span');
            counter.className = 'mw-char-counter';
            counter.setAttribute('aria-live', 'polite');
            counter.style.cssText = 'display:block;font-size:11px;color:#6b7280;text-align:right;margin-top:2px;';

            const update = () => {
                const len = el.value.length;
                counter.textContent = len + ' / ' + limit;
                counter.style.color = len > limit ? '#ef4444' : '#6b7280';
            };

            el.parentNode.insertBefore(counter, el.nextSibling);
            el.addEventListener('input', update);
            update();
        });
    }


}
