export class MWClassContainer {
    #modules = [];

    #isAsync(func) {
        return typeof func === 'function' && func.constructor.name === 'AsyncFunction';
    }
    #run(method, instance, data) {
        return new Promise(async resolve => {
            if(!method || !instance || !instance[method])  {
                resolve(null)
            }
            method = instance[method];
            let res;
            if (this.#isAsync(method)) {
                res = await method(data);
            } else {
                res = method(data);
            }
            resolve(res);
        });
    }

    call(method, data){
        let i = 0, l = this.#modules.length;
        for( ; i < l; i++) {
            if (this.#modules[i][method]) {
                this.#run(method, this.#modules[i], data);
            }
        }
    }
    getInstanceByName(name){
        let i = 0, l = this.#modules.length;
        for( ; i < l; i++) {
            if (this.#modules[i].name === name || this.#modules[i].constructor.name === name) {
                return this.#modules[i];
            }
        }
    }

    getModules() {
        return this.#modules;
    }

    async register(classRef) {
        const instance = new classRef();
        this.#modules.push(instance);
        await this.#run('onRegister', instance);
    }

    async remove(instance) {
        if(typeof instance === 'string') {
            instance = this.getInstanceByName(instance);
        }
        if(!instance) {
            return;
        }
        const index = this.#modules.indexOf(instance);
        if (index === -1) {
            return;
        }
        this.#modules.splice(index, 1);
        await this.#run('onDestroy', instance);
    }
}
