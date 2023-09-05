
let matches;
const el = document.documentElement;
if(!!el.matches) matches = 'matches';
else if (!!el.matchesSelector) matches = 'matchesSelector';
else if (!!el.mozMatchesSelector) matches = 'mozMatchesSelector';
else if (!!el.webkitMatchesSelector) matches = 'webkitMatchesSelector';

export class DomService {
    static _isBlockCache = {};
    static _fragment;

    static fragment (){
        if(!this._fragment){
            this._fragment = document.createElement('div');
            this._fragment.style.visibility = 'hidden';
            this._fragment.style.position = 'absolute';
            this._fragment.style.width = '1px';
            this._fragment.style.height = '1px';
            document.body.appendChild(this._fragment);
        }
        return this._fragment;
    }

    static matches(node, selector) {
        return node[matches](selector)
    }


    static isBlockLevel (node){
        if(!node || node.nodeType === 3){
            return false;
        }
        var name = node.nodeName;
         if(typeof this._isBlockCache[name] !== 'undefined'){
            return this._isBlockCache[name];
        }
        var test = document.createElement(name);
        this.fragment().appendChild(test);
        this._isBlockCache[name] = getComputedStyle(test).display === 'block';
        this.fragment().removeChild(test);
        return this._isBlockCache[name];
    }

    static firstBlockLevel (el) {
        while(el && el.classList) {
            if(this.isBlockLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    }

    static firstWithBackgroundImage (node) {
        if (!node) {
            return null;
        }
        while(node && node.nodeName !== 'BODY') {
            if (!!node.style.backgroundImage) {
                return node;
            }
            node = node.parentElement;
        }
        return null;
    }

    static hasAnyOfClassesOnNodeOrParent(node, arr) {
        while (node && node.nodeName !== 'BODY') {
            let i = 0, l = arr.length;
            for ( ; i < l ; i++ ) {
                if (node.classList.contains(arr[i])) {
                    return true;
                }
            }
            node = node.parentElement;
        }
        return false;
    }

    static hasParentsWithClass (el, cls) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.classList.contains(cls)) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static hasParentWithId (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static firstParentWithAnyOfClasses(node, arr) {
        while (node && node.nodeName !== 'BODY') {
            let i = 0, l = arr.length;
            for ( ; i < l ; i++ ) {
                if (node.classList.contains(arr[i])) {
                    return node;
                }
            }
            node = node.parentElement;
        }
        return null;
    }


    static firstParentOrCurrentWithTag (el, tag) {
        if (!el || !tag) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static index(el, parent, selector) {
        selector = selector || el.tagName.toLowerCase();
        parent = parent || el.parentNode;
        var all;
        if (parent.constructor === [].constructor) {
            all = parent;
        }
        else {
            all = parent.querySelectorAll(selector)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    }

    static firstParentOrCurrentWithClass (el, cls) {
        if (!el) return false;
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.classList.contains(cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }
    static firstParentOrCurrent (el, selector) {
        if (!el) return false;
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.matches(selector)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static firstParentOrCurrentWithAnyOfClasses (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr.nodeName !== 'BODY') {
            if (!curr) return false;
            if (this.hasAnyOfClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirst (node, arr) {
        let curr = node;
        while (curr && curr.nodeName !== 'BODY') {
            const h1 = curr.classList.contains(arr[0]);
            const h2 = curr.classList.contains(arr[1]);
            if (h1 && h2) {
                return false;
            }
            else {
                if (h1) {
                    return true;
                }
                else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirstOrNone (node, arr) {
        let curr = node;
        while (curr && curr.nodeName !== 'BODY') {

            const h1 = curr.classList.contains(arr[0]);
            const h2 = curr.classList.contains(arr[1]);
            if (h1 && h2) {
                return false;
            } else {
                if (h1) {
                    return true;
                } else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return true;
    }

    static hasAnyOfClasses (node, arr) {
        if (!node) return;
        let i = 0, l = arr.length;
        for (; i < l; i++) {
            if (node.classList.contains(arr[i])) {
                return true;
            }
        }
        return false;
    }

    static offset (node) {
        if(!node) return;
        var off = node.getBoundingClientRect();
        var res = {top: off.top , left: off.left, width: off.width, height: off.height, bottom: off.bottom, right: off.right};;
        res.top += node.ownerDocument.defaultView.scrollY;
        res.bottom += node.ownerDocument.defaultView.scrollY;
        res.left += node.ownerDocument.defaultView.scrollX;
        res.right += node.ownerDocument.defaultView.scrollX;
        return res;
    }
    static parentsOrder (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
            count++;
            i = 0;
            for ( ; i < l; i++) {
                if (curr.classList.contains(arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    }


}
