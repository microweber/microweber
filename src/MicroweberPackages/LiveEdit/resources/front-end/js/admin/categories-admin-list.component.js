import MicroweberBaseClass from "../containers/base-class";
import {CategoriesAdminListBulkService} from "./categories-admin-list.bulk.service";


class CategoriesAdminListService {

}



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

        this.init();
    }

    #deepExtend (destination, source) {
        return mw.object.extend(true, {}, destination, source);
    };

    config(conf) {
        const defaults = {
            params: {
                show_hidden: true,
                no_limit: true,
                is_shop: 0,
                is_blog: 0,
            },
            options: {
                selectable: true,
                rowSelect : false,
                singleSelect: false,
                multiPageSelect: false,
                disableSelectTypes: ['page'],
                saveState: false,
                searchInput: document.getElementById('category-tree-search'),
                skin: 'category-manager',
            }
        };

        const settings = this.#deepExtend({...defaults}, conf);
        return settings;
    }

    init() {
        return new Promise(resolve => {
            mw.widget.tree(this.target, this.settings, 'tree').then((instance) => {
                this.instance = instance;
                this.tree = this.instance.tree;
                this.tags = this.instance.tags;
                this.treeTags = this.instance.treeTags;


                this.bulkService = new CategoriesAdminListBulkService(this);

                const dropdown = document.createElement("div");


                this.dropdown = this.bulkService.dropdown(dropdown, this.settings.dropdown);

                this.dropdown.disabled(this.bulkService.selectedCategories.length === 0)
                this.tree.on('selectionChange', _ => this.dropdown.disabled(this.bulkService.selectedCategories.length === 0));





                this.tree.list.parentNode.insertBefore(dropdown, this.tree.list);


                resolve(this.instance);
                this.dispatch('ready', this.instance);
            });
        })
    }
}



