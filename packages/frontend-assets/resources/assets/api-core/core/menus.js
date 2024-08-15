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

 
