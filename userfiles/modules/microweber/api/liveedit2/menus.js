export const MenuItem = (data, scope) => {
    var btn = document.createElement('span');
    btn.className = 'mw-handle-menu-item';
    if(data.icon) {
        var iconClass = data.icon;
        if (iconClass.indexOf('mdi-') === 0) {
            iconClass = 'mdi ' + iconClass;
        }
        var icon = document.createElement('span');
        icon.className = iconClass + ' mw-handle-menu-item-icon';
        btn.appendChild(icon);
    }
    btn.appendChild(document.createTextNode(data.title));
    if(data.className){
        btn.className += (' ' + data.className);
    }
    if(data.id){
        btn.id = data.id;
    }
    if(typeof data.visible === 'function'){
        if(!data.visible()) {
            btn.style.display = 'none';
        }
    }
    if(data.action){
        btn.onclick = function (e) {
            e.preventDefault();
            data.action.call(scope, e, this, data);
        };
    }
    return btn;
};

export const MenuItems = {
    module: [
        {
            title: 'Edit HTML',
            icon: 'mw-icon-code',
            action: function () {
                mw.editSource(mw._activeElementOver);
            }
        },
        {
            title: 'Edit Style',
            icon: 'mdi mdi-layers',
            action: function () {
                mw.liveEditSettings.show();
                mw.sidebarSettingsTabs.set(3);
                if(mw.cssEditorSelector){
                    mw.liveEditSelector.active(true);
                    mw.liveEditSelector.select(mw._activeElementOver);
                } else {
                    mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                        setTimeout(function(){
                            mw.liveEditSelector.active(true);
                            mw.liveEditSelector.select(mw._activeElementOver);
                        }, 333);
                    });
                }
                mw.liveEditWidgets.cssEditorInSidebarAccordion();
            }
        },
        {
            title: 'Remove',
            icon: 'mw-icon-bin',
            className:'mw-handle-remove',
            action: function () {
                mw.drag.delete_element(mw._activeElementOver);
                mw.handleElement.hide()
            }
        }
    ]
};
