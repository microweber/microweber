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
                        top.mw.top().reload_module_everywhere('#' + $event.optionGroup);
                    }

            }
        });
    }


}
