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


}
