export class LivewireHooksReloadModule {
    modulesForReload = [];

    constructor() {

        this.init();
    }
    reloadTimeout = null;

    reloadModule(moduleId) {
        try {
            const topMw = (typeof top !== 'undefined' && top.mw) ? top.mw.top() : (typeof mw !== 'undefined' ? mw.top() : null);
            if (topMw && topMw.app && topMw.app.canvas) {
                const canvasWindow = topMw.app.canvas.getWindow();
                if (canvasWindow && canvasWindow.mw) {
                    canvasWindow.mw.reload_module(moduleId);
                    return;
                }
            }
            // fallback: no canvas (e.g. standalone admin)
            if (typeof mw !== 'undefined' && mw.reload_module) {
                mw.reload_module(moduleId);
            }
        } catch (e) {
            // silently ignore if mw context is not available
        }
    }

    performReload() {

        if (this.reloadTimeout) {
            clearTimeout(this.reloadTimeout);
        }

        this.reloadTimeout = setTimeout(() => {
            if (this.modulesForReload.length > 0) {
                const unique = [...new Set(this.modulesForReload)];
                this.modulesForReload = [];

                unique.forEach(moduleId => {
                    this.reloadModule(moduleId);
                });
            }
        }, 300);
    }

    init() {

        var instance = this;
        document.addEventListener('livewire:initialized', () => {

            // from https://livewire.laravel.com/docs/javascript#request-hooks



            Livewire.hook('request', ({uri, options, payload, respond, succeed, fail}) => {
                // Runs after commit payloads are compiled, but before a network request is sent...

                mw.spinner({
                    element: mw.top().win.document.body,
                    size: 52,
                    decorate: true
                });

                respond(({status, response}) => {
                    // Runs when the response is received...
                    // "response" is the raw HTTP response object
                    // before await response.text() is run...
                    mw.spinner({element: mw.top().win.document.body}).remove();
                })

                succeed(({status, json}) => {

                    if(status === 500) {
                        return;
                    }

                    mw.spinner({element: mw.top().win.document.body}).remove();

                    if (!json.components || !json.components.length) {
                        return;
                    }

                    let modulesForReloadIds = [];

                    json.components.forEach((component) => {

                        // Method 1: effects.dispatches — only fires when the server
                        // explicitly calls $this->dispatch('mw-option-saved', ...)
                        // (e.g. after callMountedAction saves a record). This avoids
                        // triggering on every wire:model.live keystroke commit.
                        if (component.effects && component.effects.dispatches) {
                            component.effects.dispatches.forEach((dispatch) => {
                                if (dispatch.name === 'mw-option-saved' && dispatch.params) {
                                    if (dispatch.params.optionGroup) {
                                        modulesForReloadIds.push('#' + dispatch.params.optionGroup);
                                    }
                                }
                            });
                        }

                        // Method 2: snapshot data — for components that carry an
                        // explicit module/rel identifier (not the broad option_group
                        // which is present on every commit).
                        if (component.snapshot) {
                            try {
                                let snapshot = JSON.parse(component.snapshot);
                                if (snapshot.data) {
                                    if (snapshot.data.moduleId) {
                                        modulesForReloadIds.push('#' + snapshot.data.moduleId);
                                    }
                                    if (snapshot.data.module_id) {
                                        modulesForReloadIds.push('#' + snapshot.data.module_id);
                                    }
                                    if (snapshot.data.module && snapshot.data.optionGroup) {
                                        modulesForReloadIds.push('#' + snapshot.data.optionGroup);
                                    }
                                    if (snapshot.data.relType && snapshot.data.relId) {
                                        modulesForReloadIds.push('#' + snapshot.data.relId);
                                    }
                                    if (snapshot.data.rel_type && snapshot.data.rel_id) {
                                        modulesForReloadIds.push('#' + snapshot.data.rel_id);
                                    }
                                    if (snapshot.data.module) {
                                        modulesForReloadIds.push(snapshot.data.module);
                                    }
                                }
                            } catch (e) {
                                // malformed snapshot — skip
                            }
                        }
                    });

                    if (modulesForReloadIds.length > 0) {
                        modulesForReloadIds.forEach(id => instance.modulesForReload.push(id));
                        instance.performReload();
                    }

                })

                fail(({status, content, preventDefault}) => {
                    // Runs when the response has an error status code...
                    // "preventDefault" allows you to disable Livewire's
                    // default error handling...
                    // "content" is the raw response content...
                    mw.spinner({element: mw.top().win.document.body}).remove();
                })
            })


        })


    }


}





