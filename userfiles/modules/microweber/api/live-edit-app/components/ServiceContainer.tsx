class ServiceContainer {
    private id: string;
    private modules: [];

    constructor(id: string) {
        this.id = id;
        this.modules = [];
    }

    public register(classInstance: any): void {
        // @ts-ignore
        this.modules.push(classInstance);
        if (typeof classInstance.init === 'function') {
            classInstance.init();
        }
    }

    public getInstance(moduleName: string): any {
        return this.modules.find((module) => module.name === moduleName);
    }

    public call(moduleName: string, methodName: string, params: any): void {
        const module = this.getInstance(moduleName);
        if (module && module[methodName]) {
            module[methodName](params);
        }
    }

    public has(moduleName: string): boolean {
        if (this.getInstance(moduleName)) {
            return true;
        }
        return false;
    }

    public get(moduleName: string): any {
        const module = this.getInstance(moduleName);
        if (module) {
            return module;
        }
        return null;
    }
}


export default ServiceContainer;
