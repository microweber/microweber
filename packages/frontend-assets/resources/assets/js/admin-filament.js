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

        // AI-970 / task-2026-05-22-dc3963 — CodeMirror syntax highlighting for the
        // Embed module settings source_code textarea. Loads CodeMirror core + HTML/CSS/JS
        // mode files from the already-published vendor path, then initialises an editor
        // on any textarea that carries data-mw-codemirror="true". A MutationObserver
        // watches for the attribute data-mw-code-type changing (Livewire updates it when
        // the code_type Select changes) and swaps the CodeMirror mode in place.
        this.initEmbedCodeMirror();

    }

    initModalFocusTrap() {
        // Watch for open Filament modals and apply `inert` to the background page
        // content, ensuring WCAG 2.4.3 compliance even if Alpine's x-trap misses
        // an edge case (e.g. Livewire re-render during dialog open).
        var obs = new MutationObserver(function () {
            var mainContent = document.querySelector('.fi-main') || document.querySelector('main');
            var sidebar = document.querySelector('.fi-sidebar');
            if (!mainContent) return;

            // Filament modals use .fi-modal-open class when open (set by Alpine x-bind).
            // The previous selector `.fi-modal-window:not([hidden])` was wrong because
            // Filament hides modals via Alpine x-show (display:none), not the HTML
            // hidden attribute — so closed modals matched and inert was always set.
            var anyOpen = document.querySelector('.fi-modal.fi-modal-open');

            if (anyOpen) {
                mainContent.setAttribute('inert', '');
                if (sidebar) sidebar.setAttribute('inert', '');
            } else {
                mainContent.removeAttribute('inert');
                if (sidebar) sidebar.removeAttribute('inert');
            }
        });

        obs.observe(document.body, { childList: true, subtree: true, attributeFilter: ['hidden', 'class', 'style'] });
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

    // AI-970 / task-2026-05-22-dc3963 — CodeMirror syntax highlighting for any
    // textarea that carries data-mw-codemirror="true" (set by EmbedModuleSettings).
    // Loads the core CodeMirror bundle plus xml/css/javascript/htmlmixed mode files
    // from the vendor path (published by frontend-assets-libs build) then wraps the
    // textarea with a full editor. A single MutationObserver covers two concerns:
    //   1. childList — initialise new textareas added by Livewire re-renders.
    //   2. attributeFilter data-mw-code-type — swap CodeMirror mode when the
    //      code_type Select changes (Livewire updates this attribute reactively).
    // Blur validation (HTML/CSS/JS syntax check) is wired to CodeMirror's own blur
    // event so it works even though the original textarea is hidden by CodeMirror.
    initEmbedCodeMirror() {
        const CM_BASE = '/vendor/microweber-packages/frontend-assets-libs/codemirror/';

        const modeFor = (codeType) => {
            if (codeType === 'javascript') return 'javascript';
            if (codeType === 'css') return 'css';
            return 'htmlmixed';   // default covers HTML + embedded CSS/JS
        };

        const validateSyntax = (code, codeType) => {
            if (!code.trim()) return '';
            try {
                if (codeType === 'javascript') { new Function(code); }
                else if (codeType === 'css') { var s = new CSSStyleSheet(); s.replaceSync(code); }
                else {
                    var d = (new DOMParser()).parseFromString(code, 'text/html');
                    if (d.querySelector('parsererror')) throw new Error('Invalid HTML');
                }
            } catch (e) { return 'Syntax error: ' + e.message; }
            return '';
        };

        const showError = (cm, msg) => {
            var wrap = cm.getWrapperElement();
            var fieldWrap = wrap && wrap.closest ? wrap.closest('.fi-fo-field-wrp') : null;
            if (!fieldWrap) return;
            var err = fieldWrap.querySelector('.mw-embed-validation-error');
            if (!err) {
                err = document.createElement('p');
                err.className = 'mw-embed-validation-error';
                err.style.cssText = 'color:#dc2626;font-size:12px;margin-top:4px;';
                fieldWrap.appendChild(err);
            }
            err.textContent = msg;
        };

        const initOnTextarea = (textarea) => {
            if (textarea._mwCm) return;  // already initialised
            if (!window.CodeMirror) return;

            var codeType = textarea.getAttribute('data-mw-code-type') || 'html';
            var cm = window.CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                lineWrapping: true,
                mode: modeFor(codeType),
                indentUnit: 2,
                tabSize: 2,
                indentWithTabs: false,
            });

            // Sync CodeMirror value back to the hidden textarea so Livewire picks it up.
            cm.on('change', function () {
                textarea.value = cm.getValue();
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // Validate on blur (mirrors the old x-on:blur handler).
            cm.on('blur', function () {
                var type = textarea.getAttribute('data-mw-code-type') || 'html';
                showError(cm, validateSyntax(cm.getValue(), type));
            });

            textarea._mwCm = cm;
        };

        const scanAndInit = () => {
            document.querySelectorAll('[data-mw-codemirror="true"]').forEach(initOnTextarea);
        };

        const scriptLoaded = () => {
            scanAndInit();
        };

        const loadCodeMirror = () => {
            if (window.CodeMirror) { scriptLoaded(); return; }

            // Inject stylesheet once.
            if (!document.querySelector('link[data-mw-cm-css]')) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = CM_BASE + 'codemirror.css';
                link.setAttribute('data-mw-cm-css', '1');
                document.head.appendChild(link);
            }

            // Load scripts sequentially: core → xml → css → javascript → htmlmixed.
            var chain = ['codemirror.js', 'xml.js', 'css.js', 'javascript.js', 'htmlmixed.js'];
            var idx = 0;
            var loadNext = () => {
                if (idx >= chain.length) { scriptLoaded(); return; }
                var src = CM_BASE + chain[idx++];
                if (document.querySelector('script[data-mw-cm-src="' + src + '"]')) {
                    loadNext(); return;
                }
                var s = document.createElement('script');
                s.setAttribute('data-mw-cm-src', src);
                s.src = src;
                s.onload = loadNext;
                s.onerror = loadNext;
                document.head.appendChild(s);
            };
            loadNext();
        };

        // Observe DOM: new textareas AND code-type attribute changes.
        var obs = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.type === 'childList') {
                    m.addedNodes.forEach(function (node) {
                        if (node.nodeType !== 1) return;
                        var targets = [];
                        if (node.matches && node.matches('[data-mw-codemirror="true"]')) {
                            targets.push(node);
                        } else if (node.querySelectorAll) {
                            targets = Array.from(node.querySelectorAll('[data-mw-codemirror="true"]'));
                        }
                        targets.forEach(function (t) {
                            if (window.CodeMirror) initOnTextarea(t);
                        });
                    });
                } else if (m.type === 'attributes' && m.attributeName === 'data-mw-code-type') {
                    var el = m.target;
                    if (el._mwCm) {
                        el._mwCm.setOption('mode', modeFor(el.getAttribute('data-mw-code-type') || 'html'));
                    }
                }
            });
        });
        obs.observe(document.body, {
            childList: true,
            subtree: true,
            attributeFilter: ['data-mw-code-type'],
        });

        // Load CodeMirror (and init existing textareas) on first call.
        loadCodeMirror();
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
            if (typeof el.value !== 'string') return;

            const counter = document.createElement('span');
            counter.className = 'mw-char-counter';
            counter.setAttribute('aria-live', 'polite');
            counter.style.cssText = 'display:block;font-size:11px;color:#6b7280;text-align:right;margin-top:2px;';

            const update = () => {
                const len = (el.value || '').length;
                counter.textContent = len + ' / ' + limit;
                counter.style.color = len > limit ? '#ef4444' : '#6b7280';
            };

            el.parentNode.insertBefore(counter, el.nextSibling);
            el.addEventListener('input', update);
            update();
        });
    }


}
