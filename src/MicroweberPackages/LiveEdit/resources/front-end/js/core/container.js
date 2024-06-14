import MicroweberBaseClass from "./base-class.js";

export class MWUniversalContainer extends MicroweberBaseClass {

    #modules = {};


    #run(method, instance, data) {
        if(!method || !instance || !instance[method])  {
            return;
        }
        method = instance[method];
     //   method(data);
        method.call(instance,data);
    }

    call(method, data){
        for(let i in this.#modules) {
            if (this.#modules[i][method]) {
                this.#run(method, this.#modules[i], data);
            }
        }
    }
    get(name){
        return this.#modules[name];
    }

    getModules() {
        return this.#modules;
    }

    register(name, classRef) {
        let instance;
        if(typeof classRef === 'function') {
            instance = new classRef();
        } else {
            instance = classRef;
        }

        this.#modules[name] = instance;
        this[name] = instance;
        this.#run('onRegister', instance);
        this.dispatch('register');
    }

    remove(instance) {
        let name;
        if(typeof instance === 'string') {
            name = instance
            instance = this.get(instance);
        }

        if(!name) {
            for(let i in this.#modules) {
                if (this.#modules[i] === instance) {
                    name = i;
                    break;
                }
            }
        }
        delete this.#modules[name];
        delete this[name];
        this.#run('onDestroy', instance);
        this.dispatch('remove');
    }
}
