export class LivewireHooksReloadModule {
    modulesForReload = [];

    constructor() {

        this.init();
    }

    performReload() {
        if (this.modulesForReload.length > 0) {
            // make unique
            this.modulesForReload = [...new Set(this.modulesForReload)];

            console.log('performModulesReload', this.modulesForReload)

            for (let i = 0; i < this.modulesForReload.length; i++) {
                let moduleId = this.modulesForReload[i];
                //mw.reload_module_everywhere('#' + moduleId);
                mw.reload_module_everywhere(moduleId);
                //unset
                this.modulesForReload.splice(i, 1);
            }
        }
    }

    init() {

        var instance = this;
        document.addEventListener('livewire:initialized', () => {

            // from https://livewire.laravel.com/docs/javascript#request-hooks

            Livewire.hook('request', ({uri, options, payload, respond, succeed, fail}) => {
                // Runs after commit payloads are compiled, but before a network request is sent...

                respond(({status, response}) => {
                    // Runs when the response is received...
                    // "response" is the raw HTTP response object
                    // before await response.text() is run...
                })

                succeed(({status, json}) => {

                    if(status === 500) {
                        return;
                    }

                    //let payloadJson = JSON.parse(payload);
                    // console.log('options', options)
                    // console.log('payload', payloadJson)
                    // console.log('status', status)
                    // console.log('status', status)

                    // Runs when the response is received...
                    // "json" is the JSON response object...
                    let modulesForReloadIds = [];
                    if (json.components && json.components.length) {
                        console.log('components', json.components)
                        json.components.forEach((component) => {
                            if (component.snapshot) {
                                let snapshot = JSON.parse(component.snapshot);
                                if (snapshot.data) {
                                    if (snapshot.data.moduleId) {
                                        modulesForReloadIds.push(snapshot.data.moduleId);
                                    } else   if (snapshot.data.module) {
                                        modulesForReloadIds.push(snapshot.data.module);
                                    }
                                }


                            }
                        })
                    }
                    console.log('modulesForReloadIds', modulesForReloadIds)
                    if (modulesForReloadIds.length > 0) {
                        for (let i = 0; i < modulesForReloadIds.length; i++) {
                            let moduleId = modulesForReloadIds[i];
                            instance.modulesForReload.push(moduleId);
                        }
                        instance.performReload();
                    }
                })

                fail(({status, content, preventDefault}) => {
                    // Runs when the response has an error status code...
                    // "preventDefault" allows you to disable Livewire's
                    // default error handling...
                    // "content" is the raw response content...
                })
            })


        })


    }


}





