

import {DomService} from './classes/dom.js';


const isRowLike = function (node) {
    return DomService.matches(node, '.row,[class*="row-"]');
}

const isColumnLIke = function (node) {
    return DomService.matches(node, '.col,[class*="col-"]');
}
let _fragment;
const fragment = function(){
    if(!_fragment){
        _fragment = document.createElement('div');
        _fragment.style.visibility = 'hidden';
        _fragment.style.position = 'absolute';
        _fragment.style.width = '1px';
        _fragment.style.height = '1px';
        document.body.appendChild(_fragment);
    }
    return _fragment;
}
const _isBlockCache = {};
const isBlockLevel = function (node) {
    if(!node || node.nodeType === 3){
        return false;
    }
    var name = node.nodeName;
    if(typeof _isBlockCache[name] !== 'undefined'){
        return _isBlockCache[name];
    }
    var test = document.createElement(name);
    fragment().appendChild(test);
    _isBlockCache[name] = getComputedStyle(test).display === 'block';
    fragment().removeChild(test);
    return _isBlockCache[name];
}




const getElementsLike = (selector, root, scope) => {
    selector = selector || '*';
    var all = root.querySelectorAll(selector), i = 0, final = [];

    for( ; i<all.length; i++){

        if( mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(all[i], ['edit', 'module']) && !isColumnLIke(all[i]) &&
            !isRowLike(all[i]) &&
            !scope.elementAnalyzer.isEdit(all[i]) &&
            isBlockLevel(all[i])){
            final.push(all[i]);
        }
    }

    return final;
};

export const ModeAuto = (scope) => {

    const {
        backgroundImageHolder,
        editClass,
        moduleClass,
        elementClass,
        allowDrop
    } = scope.settings;
    const root = scope.root;
    var selector = '*';
    var bgHolders = root.querySelectorAll('.' + editClass + '.' + backgroundImageHolder + ', .' + editClass + ' .' + backgroundImageHolder + ', .'+editClass+'[style*="background-image"], .'+editClass+' [style*="background-image"]');
    var noEditModules = root.querySelectorAll('.' + moduleClass + scope.settings.unEditableModules.join(',.' + moduleClass));
    var edits = root.querySelectorAll('.' + editClass);
    var i = 0, i1 = 0, i2 = 0;
    for ( ; i < bgHolders.length; i++ ) {
        var curr = bgHolders[i];
        if( scope.elementAnalyzer.isInEdit(curr) ){
            if(!mw.tools.hasClass(curr, moduleClass)) {
                // mw.tools.addClass(curr, editClass);
            }
            if(!curr.style.backgroundImage) {
                curr.style.backgroundImage = 'none';
            }
        }
    }
    for ( ; i1<noEditModules.length; i1++ ) {
        noEditModules[i].classList.remove(moduleClass);
    }
    for ( ; i2 < edits.length; i2++ ) {
        var all = getElementsLike(':not(.' + elementClass + ')', edits[i2], scope), i2a = 0;


        var allAllowDrops = edits[i2].querySelectorAll('img,.' + allowDrop), i3a = 0;
        for( ; i3a < allAllowDrops.length; i3a++){
            if(scope.elementAnalyzer.isInEdit(allAllowDrops[i3a])){
                if (scope.canBeElement(allAllowDrops[i3a])) {
                     allAllowDrops[i3a].classList.add(elementClass);
                }
            }

        }
        for( ; i2a<all.length; i2a++) {
            if(!all[i2a].classList.contains(moduleClass)){
                if(scope.elementAnalyzer.isInEdit(all[i2a])){
                    if (scope.canBeElement(all[i2a])) {
                         all[i2a].classList.add(elementClass);
                    }
                }
            }
        }
    }
};
