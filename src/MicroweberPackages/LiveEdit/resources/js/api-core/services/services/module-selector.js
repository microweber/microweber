import { ModulesList } from "./../../core/le2/modules-list.js";
import { CommandDialog } from "./commands-dialog.js";


var _modulesCache;

var _modulesDataLoader = function (modulesDialog) {
    var modulesList = new ModulesList({
        data: _modulesCache
    });
    modulesList.createCategorized().then(function () {
        modulesDialog.append(modulesList.root)

    })
}




export const moduleSelector = () => {
    var cmmodulesDialog = new CommandDialog('mw-le-modules-dialog');
    var modulesDialog = cmmodulesDialog.dialog;


    mw.$('#mw-plus-tooltip-selector li').each(function () {
        this.onclick = function () {
            var name = mw.$(this).attr('data-module-name');
            var conf = {class: this.className};
            if (name === 'layout') {
                conf.template = mw.$(this).attr('template');
            }

            mw.module.insert(mw._activeElementOver, name, conf, mw.handleElement.positionedAt, mw.liveEditState);
            mw.wysiwyg.change(mw._activeElementOver)
            tooltip.remove();
        };
    });


    if (_modulesCache) {
        _modulesDataLoader(modulesDialog)
    } else {
        mw.spinner({
            element: modulesDialog.get(0),
            decorate: true
        });
        /* demo */
        fetch(`${mw.settings.site_url}api/module/list?layout_type=module`)
            .then(function (data) {
                return data.json();
            }).then(function (data) {
            _modulesCache = data;
            _modulesDataLoader(modulesDialog)
            mw.spinner({
                element: modulesDialog.get(0),
                decorate: true
            }).remove()
        })
    }
}
