/**
 * @deprecated The method should not be used
 */

import { ModulesList } from "./../../core/le2/modules-list.js";
import { CommandDialog } from "./commands-dialog.js";

var _layotsCache;

/**
 * @deprecated The method should not be used
 */
var _layoutsDataLoader = function (cmmodulesDialog) {
    var modulesList = new ModulesList({
        data: _layotsCache
    });


    modulesList.create().then(function () {
        var grid = mw.element({
            props: {
                className: 'mw-le-layouts-dialog-row'
            }
        });
        var colSidebar = mw.element({
            props: {
                className: 'mw-le-layouts-dialog-col'
            }
        });
        var colContent = mw.element({
            props: {
                className: 'mw-le-layouts-dialog-col'
            }
        });
        grid.append(colSidebar);
        grid.append(colContent);
        mw.element(modulesList.root).append(grid);
        colSidebar.append(modulesList.searchBlock);

        var categoriesTitle = mw.element({
            props: {
                innerHTML: 'Categories',
                className: 'mw-le-layouts-dialog-categories-title'
            }
        });
        colSidebar.append(categoriesTitle);
        colSidebar.append(modulesList.categoriesNavigation);
        colContent.append(modulesList.modulesList);

        cmmodulesDialog.append(modulesList.root);


    })
}

export const layoutSelector = () => {

    var cmmodulesDialog = new CommandDialog('mw-le-layouts-dialog')
    var layOutsDialog = cmmodulesDialog.dialog;


    if (_layotsCache) {
        _layoutsDataLoader(layOutsDialog);
        return;
    }

    mw.spinner({
        element: layOutsDialog.get(0),
        decorate: true
    })


    fetch(`${mw.settings.site_url}api/module/list?layout_type=layout&elements_mode=true&group_layouts_by_category=true`)
        .then(function (data) {
            return data.json();
        }).then(function (data) {
        _layotsCache = data;
        _layoutsDataLoader(layOutsDialog)
        mw.spinner({
            element: layOutsDialog.get(0),
            decorate: true
        }).remove()
    })
}
