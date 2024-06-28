import MicroweberBaseClass from "../containers/base-class";


export class CategoriesAdminListComponent extends MicroweberBaseClass {

    constructor(target, opt, mode = 'tree') {
        super();
        if(typeof target === "string") {
            target = document.querySelector(target);
        }
        if(!target) {
            return;
        }
        this.target = target;
        this.settings = this.config(opt);
        this.init()
    }

    #deepExtend (destination, source) {
        for (var property in source) {
            if (source[property] && source[property].constructor && source[property].constructor === Object) {
                destination[property] = destination[property] || {};
                arguments.callee(destination[property], source[property]);
            } else {
                destination[property] = source[property];
            }
        }
        return destination;
    };

    config(conf) {
        const defaults = {
            options: treeDataOpts,
            params: {
                show_hidden: true,
                no_limit: true,
                is_shop: 0,
                is_blog: 0,
            }
        };

        const settings = this.#deepExtend({...defaults}, conf);
        return settings;
    }

    init() {
        mw.widget.tree(this.target, this.settings).then(function (instance) {
            console.log(instance)
        });
        //this.instance = await mw.widget.tree(this.target, this.settings);
        //this.dispatch('ready', this.instance);
    }
}



