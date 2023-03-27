export class MWServiceProvider {
    #services = {};

    #reserved = ['add', 'get', 'set'];

    get(name) {
        return this.#services[name];
    }

    #_setService(name, service) {
        if(typeof service === 'function') {
            service = new service();
        }
        if(this.#reserved.indexOf(name) === -1) {
            this.#services[name] = service;
            this[name] = service;
            if(typeof service.onAdd === 'function') {
                service.onAdd()
            }
        }
    }
    add(name, service) {
        if(!!name && !!service && !this.get(name)) {
            this.#_setService(name, service)
        }
    }
    set(name, service) {
        if(!!name && !!service) {
            this.#_setService(name, service)
        }
    }
    register(name, service){
        return this.set(name, service);
    }
}
