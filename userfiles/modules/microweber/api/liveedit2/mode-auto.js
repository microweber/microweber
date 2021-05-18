const getElementsLike = (selector, root) => {
    root = root || document.body;
    selector = selector || '*';
    var all = root.querySelectorAll(selector), i = 0, final = [];
    for( ; i<all.length; i++){
        if(!this.scope.helpers.isColLike(all[i]) &&
            !this.scope.helpers.isRowLike(all[i]) &&
            !this.scope.helpers.isEdit(all[i]) &&
            this.scope.helpers.isBlockLevel(all[i])){
            final.push(all[i]);
        }
    }
    return final;
};

export const ModeAuto = (root, selector, config, domService, dropableService) => {
    const {
        backgroundImageHolder,
        editClass,
        moduleClass,
        elementClass,
        allowDrop
    } = config;
    root = root || document.body;
    selector = selector || '*';
    var bgHolders = root.querySelectorAll('.' + editClass + '.' + backgroundImageHolder + ', .' + editClass + ' .' + backgroundImageHolder + ', .'+editClass+'[style*="background-image"], .'+editClass+' [style*="background-image"]');
    var noEditModules = root.querySelectorAll('.' + moduleClass + mw.noEditModules.join(',.' + moduleClass));
    var edits = root.querySelectorAll('.edit');
    var i = 0, i1 = 0, i2 = 0;
    for ( ; i < bgHolders.length; i++ ) {
        var curr = bgHolders[i];
        var po = mw.tools.parentsOrder(curr, [editClass, moduleClass]);
        if(po.module === -1 || (po.edit < po.module && po.edit !== -1)){
            if(!mw.tools.hasClass(curr, moduleClass)){
                mw.tools.addClass(curr, editClass);
            }
            curr.style.backgroundImage = curr.style.backgroundImage || 'none';
        }
    }
    for ( ; i1<noEditModules.length; i1++ ) {
        noEditModules[i].classList.remove(moduleClass);
    }
    for ( ; i2 < edits.length; i2++ ) {
        var all = getElementsLike(':not(.' + elementClass + ')', edits[i2]), i2a = 0;
        var allAllowDrops = edits[i2].querySelectorAll('.' + allowDrop), i3a = 0;
        for( ; i3a < allAllowDrops.length; i3a++){
            allAllowDrops[i3a].classList.add(elementClass);
        }
        for( ; i2a<all.length; i2a++) {
            if(!all[i2a].classList.contains(moduleClass)){
                if(dropableService.canAccept(all[i2a])){
                    all[i2a].classList.add( elementClass );
                }
            }
        }
    }
};
