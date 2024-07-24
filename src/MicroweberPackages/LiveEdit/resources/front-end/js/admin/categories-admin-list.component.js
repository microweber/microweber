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

        return this.init();
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
                sortable: true,
                selectable: true,
                rowSelect : false,
                singleSelect: false,
                multiPageSelect: false,
                disableSelectTypes: ['page'],
                saveState: false,
                searchInput: document.getElementById('category-tree-search'),
                skin: 'category-manager',


                contextMenu: [
                    {
                        title: 'Add category',

                        icon: 'add-subcategory-icon-tree',
                        action: function (element, data, menuitem) {
                            var loc = 'https://demo.microweber.org/v2/admin/category/create?parent_page_id=' + data.id;
                            if (data.type === 'page') {
                                if(data.is_shop === 1){
                                    loc = 'https://demo.microweber.org/v2/admin/shop/category/create?parent_page_id=' + data.id;
                                }
                            }

                            window.location.href = loc;

                        },
                        filter: function (obj, node) {
                            return obj.type === 'page';
                        },
                        className: ''
                    },
                    {
                        title: 'View',

                        icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>',
                        action: function (element, data, menuitem) {
                            top.location.href = data.url;
                        },
                        filter: function (obj, node) {
                            return obj.type === 'category';
                        },
                        className: ''
                    },
                    {
                        title: 'Edit',

                        icon: '<svg class="tblr-body-color" fill="currentColor" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg>',
                        action: function (element, data, menuitem) {
                            self.location.href = data.admin_edit_url;
                        },
                        filter: function (obj, node) {
                            return obj.type === 'category';
                        },
                        className: ''
                    },
                    {
                        title: 'Delete',
                        icon: '<svg class="  text-danger" fill="currentColor" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
                        action: function (element, data, menuitem) {
                            if (data.type === 'category') {
                                mw.content.deleteCategory(data.id, function () {
                                    $(element).fadeOut(function () {
                                        $(element).remove()
                                    })
                                }, false);
                            }
                        },
                        filter: function (obj, node) {
                            return obj.type === 'category';
                        },
                        className: ''
                    }


                ]

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



