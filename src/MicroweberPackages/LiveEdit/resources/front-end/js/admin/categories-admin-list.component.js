import MicroweberBaseClass from "../containers/base-class";


class CategoriesAdminListService {

}

class CategoriesAdminListBulkService {
    constructor(instance) {
        this.tree = instance.tree;
        this.selectedPages = [];
        this.selectedCategories = [];
        this.syncSelected();
        this.tree.on('selectionChange', () => this.syncSelected());

    }

    syncSelected() {
        const pages = [];
        const categories = [];
        const selected = this.tree.getSelected();

        for (let i = 0; i < selected.length; i++) {
            const item = selected[i];
            if (item.type === 'category') {
                categories.push(item.id);
            } else if (item.type === 'page') {
                pages.push(item.id);
            }
        }

        this.selectedPages = pages;
        this.selectedCategories = categories;

    }

    dropdown(target, options = {}) {

        const defaults = {
            data: [
                {id: 'moveToCategory', title: mw.lang('Move To Category')},
                {id: 'hide', title: mw.lang('Make Hidden'), },
                {id: 'show', title: mw.lang('Make Visible')},
                {id: 'delete', title: mw.lang('Delete')},
            ]
        };
        const dropdown =  mw.dropdow({element: target, ...Object.assign({}, defaults, options)})

        return dropdown;
    }


    async moveToCategory() {

        function categoryBulkMoveExec(selectedIds) {
            mw.tools.confirm("Are you sure you want to move the selected data?", function () {
                var dialog = mw.dialog.get('#pick-categories');
                var tree = mw.tree.get('#pick-categories');
                var selected = tree.getSelected();
                var data = {
                    categories: []
                };
                selected.forEach(function (item) {
                    if (item.type === 'category') {
                        data.categories.push(item.id);
                    } else if (item.type === 'page') {
                        data.rel_id = item.id;
                    }
                });

                mw.spinner({
                    element: dialog.dialogContainer
                })

                $.ajax({
                    url: route('api.category.move-bulk'),
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        moveToParentIds: data.categories,
                        moveToRelId: data.rel_id
                    },
                    success: function (data) {
                        mw.reload_module('categories/manage');
                        mw.notification.success('<?php _ejs("Categories are moved."); ?>.');
                        mw.top().trigger('pagesTreeRefresh');

                        dialog.remove();
                    }
                });

            });
        }


        const treeParams = {}


        if(this.instance.settings.params && this.instance.settings.params.is_shop) {
            treeParams.is_shop = 1;
        } else {
            treeParams.is_blog = 1;
        }




          var btn = document.createElement('button');
          btn.disabled = true;
          btn.className = 'mw-ui-btn';
          btn.innerHTML = mw.lang('Move categories');
          btn.onclick = function (ev) {
              categoryBulkMoveExec(selectedIds);
          };
          var dialog = mw.dialog({
              height: 'auto',
              autoHeight: true,
              id: 'pick-categories',
              footer: btn,
              title: mw.lang('Move to selected category')
          });
          var treeSettings = {
              data: data,
              element: dialog.dialogContainer,
              sortable: false,
              selectable: true,
              singleSelect: true,
              searchInput: true,
          }
          var inst = await mw.widget.tree(dialog.dialogContainer, {options: treeSettings, params: treeParams});
          const tree = inst.tree;
          tree.on("selectionChange", function () {

              btn.disabled = tree.getSelected().length === 0;

              var selected = tree.getSelected();
              if(tree.options.singleSelect === false && treeSettings.selected.length){
                  var hasPage = selected.find(function (item){
                      return item.type === 'page';
                  });

                  if(typeof hasPage === 'undefined'){
                      var category = selected[0];
                       tree.select(category.parent_id, 'page', true);
                  }
              }
          });



    }


    delete() {
        mw.tools.confirm(mw.lang('Are you sure you want to delete the selected categories'), function() {
            $.ajax({
                url: route('api.category.delete-bulk'),
                type: 'DELETE',
                data: {ids: selectedCategories},
                success: function (data) {
                    mw.reload_module('categories/manage');
                    mw.notification.success('<?php _ejs("Categories are deleted."); ?>.');
                    mw.top().trigger('pagesTreeRefresh');
                }
            });
        });
    }
    makeHidden() {
        mw.tools.confirm(mw.lang('Are you sure you want to make hidden the selected categories?'), function() {
            $.ajax({
                url: route('api.category.hidden-bulk'),
                type: 'POST',
                data: {ids: selectedCategories},
                success: function (data) {
                    mw.reload_module('categories/manage');
                    mw.notification.success(mw.lang('Categories are hidden'));
                    mw.top().trigger('pagesTreeRefresh');
                }
            });
        });
    }
    makeVisible() {
        mw.confirm(mw.lang('Are you sure you want to make visible the selected categories'),  () => {
            $.ajax({
                url: route('api.category.visible-bulk'),
                type: 'POST',
                data: {ids: this.selectedCategories},
                success: function (data) {
                    mw.reload_module('categories/manage');
                    mw.notification.success(mw.lang('Categories are visible'));
                    mw.top().trigger('pagesTreeRefresh');
                }
            });
        });
    }
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
        this.bulkService = new CategoriesAdminListBulkService(this);
        this.target = target;
        this.settings = this.config(opt);
        console.log("", this.settings);
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
                allowPageSelect: false,
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
            mw.widget.tree(this.target, this.settings).then((instance) => {
                this.instance = instance;
                this.tree = this.instance.tree;
                this.tags = this.instance.tags;
                this.treeTags = this.instance.treeTags;
                resolve(this.instance);
                this.dispatch('ready', this.instance);
            });
        })
    }
}



