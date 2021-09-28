
let matches;
const el = document.documentElement;
if(!!el.matches) matches = 'matches';
else if (!!el.matchesSelector) matches = 'matchesSelector';
else if (!!el.mozMatchesSelector) matches = 'mozMatchesSelector';
else if (!!el.webkitMatchesSelector) matches = 'webkitMatchesSelector';

export class DomService {

    static matches(node, selector) {
        return node[matches](selector)
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

    static hasParentWithId (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr !== document.body) {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static firstWithAyOfClassesOnNodeOrParent(node, arr) {
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

    static parentsOrCurrentOrderMatchOrOnlyFirst (node, arr) {
        let curr = node;
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        var res = {top: off.top, left: off.left, width: off.width, height: off.height, bottom: off.bottom, right: off.right};;
        res.top += scrollY;
        res.bottom += scrollY;
        res.left += scrollX;
        res.right += scrollX;
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
