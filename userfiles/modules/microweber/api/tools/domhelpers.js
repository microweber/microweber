(function(){
var domHelp = {
    classNamespaceDelete: function (el_obj, namespace, parent, namespacePosition, exception) {
        if (el_obj.element && el_obj.namespace) {
            el = el_obj.element;
            namespace = el_obj.namespace;
            parent = el_obj.parent;
            namespacePosition = el_obj.namespacePosition;
            exceptions = el_obj.exceptions || [];
        }
        else {
            el = el_obj;
            exceptions = [];
        }
        namespacePosition = namespacePosition || 'contains';
        parent = parent || mwd;
        if (el === 'all') {
            var all = parent.querySelectorAll('.edit *'), i = 0, l = all.length;
            for (; i < l; i++) {
                mw.tools.classNamespaceDelete(all[i], namespace, parent, namespacePosition)
            }
            return;
        }
        if (!!el.className && typeof(el.className.split) === 'function') {
            var cls = el.className.split(" "), l = cls.length, i = 0, final = [];
            for (; i < l; i++) {
                if (namespacePosition === 'contains') {
                    if (!cls[i].contains(namespace) || exceptions.indexOf(cls[i]) !== -1) {
                        final.push(cls[i]);
                    }
                }
                else if (namespacePosition === 'starts') {
                    if (cls[i].indexOf(namespace) !== 0) {
                        final.push(cls[i]);
                    }
                }
            }
            el.className = final.join(" ");
        }
    },
    firstWithBackgroundImage: function (node) {
        if (!node) return false;
        if (!!node.style.backgroundImage) return node;
        var final = false;
        mw.tools.foreachParents(node, function (loop) {
            if (!!this.style.backgroundImage) {
                mw.tools.stopLoop(loop);
                final = this;
            }
        });
        return final;
    },

    parentsOrCurrentOrderMatchOrOnlyFirstOrNone: function (node, arr) {
        return !mw.tools.hasAnyOfClassesOnNodeOrParent(node, [arr[1]]) || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, arr)
    },
    parentsOrCurrentOrderMatchOrOnlyFirst: function (node, arr) {
        var curr = node;
        while (curr && curr.classList) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
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
    },
    parentsOrCurrentOrderMatchOrOnlyFirstOrNone: function (node, arr) {
        var curr = node;
        while (curr && curr.classList) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
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
        return true;
    },
    parentsOrCurrentOrderMatch: function (node, arr) {
        var curr = node,
            match = {a: 0, b: 0},
            count = 1,
            hadA = false;
        while (curr && curr.classList) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                if (match.a > 0) {
                    return true;
                }
                return false;
            }
            else {
                if (h1) {
                    match.a = count;
                    hadA = true;
                }
                else if (h2) {
                    match.b = count;
                }
                if (match.b > match.a) {
                    return hadA ? true : false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    parentsOrCurrentOrderMatchOrNone:function(node, arr){
        if(!node) return false;
        var curr = node,
            match = {a: 0, b: 0},
            count = 1,
            hadA = false;
        while (curr && curr.classList) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                if (match.a > 0) {
                    return true;
                }
                return false;
            }
            else {
                if (h1) {
                    match.a = count;
                    hadA = true;
                }
                else if (h2) {
                    match.b = count;
                }
                if (match.b > match.a) {
                    return hadA ? true : false;
                }
            }
            curr = curr.parentNode;
        }
        return match.a === 0 && match.b === 0;
    },
    parentsOrCurrentOrderMatchOrOnlyFirstOrBoth: function (node, arr) {
        var curr = node,
            has1 = false,
            has2 = false;
        while (curr && curr.classList) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                return true;
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
    },
    matchesAnyOnNodeOrParent: function (node, arr) {
        var curr = node;
        while (curr && curr.classList) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    return true;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstMatchesOnNodeOrParent: function (node, arr) {
        if (!arr) return;
        if (typeof arr === 'string') {
            arr = [arr];
        }
        var curr = node;
        while (curr && curr.classList) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    return curr;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    lastMatchesOnNodeOrParent: function (node, arr) {
        if (!arr) return;
        if (typeof arr === 'string') {
            arr = [arr];
        }
        var curr = node, result;
        while (curr && curr.classList) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    result = curr;
                }
            }
            curr = curr.parentNode;
        }
        return result;
    },
    hasAnyOfClassesOnNodeOrParent: function (node, arr) {
        var curr = node;
        while (curr && curr.classList) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.hasClass(curr, arr[i])) {
                    return true;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    hasClass: function (classname, whattosearch) {
        if (classname === null) {
            return false;
        }
        if (typeof classname === 'string') {
            return classname.split(' ').indexOf(whattosearch) > -1;
        }
        else if (typeof classname === 'object') {
            return mw.tools.hasClass(classname.className, whattosearch);
        }
        else {
            return false;
        }
    },
    hasAllClasses: function (node, arr) {
        if (!node) return;
        var has = true;
        var i = 0, nodec = node.className.trim().split(' ');
        for (; i < arr.length; i++) {
            if (nodec.indexOf(arr[i]) === -1) {
                return false;
            }
        }
        return has;
    },
    hasAnyOfClasses: function (node, arr) {
        if (!node) return;
        var i = 0, l = arr.length, cls = node.className;
        for (; i < l; i++) {
            if (mw.tools.hasClass(cls, arr[i])) {
                return true;
            }
        }
        return false;
    },


    hasParentsWithClass: function (el, cls) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (mw.tools.hasClass(curr, cls)) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    hasParentWithId: function (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },

    hasChildrenWithTag: function (el, tag) {
        tag = tag.toLowerCase();
        var has = false;
        mw.tools.foreachChildren(el, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                has = true;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    hasParentsWithTag: function (el, tag) {
        if (!el || !tag) return;
        tag = tag.toLowerCase();
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (curr.nodeName.toLowerCase() === tag) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    hasHeadingParent: function (el) {
        if (!el) return;
        var h = /^(h[1-6])$/i;
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (h.test(curr.nodeName.toLowerCase())) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    addClass: function (el, cls) {
        if (!cls || !el) {
            return false;
        }
        if (el.fn) {
            el = el[0];
            if (!el) {
                return;
            }
        }
        if (typeof cls === 'string') {
            cls = cls.trim();
        }
        if (!el) return;
        var arr = cls.split(" ");
        var i = 0;
        if (arr.length > 1) {
            for (; i < arr.length; i++) {
                mw.tools.addClass(el, arr[i]);
            }
            return;
        }
        if (typeof el === 'object') {
            if (el.classList) {
                el.classList.add(cls);
            }
            else {
                if (!mw.tools.hasClass(el.className, cls)) el.className += (' ' + cls);
            }
        }
        if (typeof el === 'string') {
            if (!mw.tools.hasClass(el, cls)) el += (' ' + cls);
        }
    },
    removeClass: function (el, cls) {
        if (typeof cls === 'string') {
            cls = cls.trim();
        }
        if (!cls || !el) return;
        if (el === null) {
            return false;
        }
        if (el.fn) {
            el = el[0];
            if (!el) {
                return;
            }
        }
        if (typeof el === 'undefined') {
            return false;
        }
        if (el.constructor === [].constructor) {
            var i = 0, l = el.length;
            for (; i < l; i++) {
                mw.tools.removeClass(el[i], cls);
            }
            return;
        }
        if (typeof(cls) === 'object') {
            var arr = cls;
        } else {
            var arr = cls.split(" ");
        }
        var i = 0;
        if (arr.length > 1) {
            for (; i < arr.length; i++) {
                mw.tools.removeClass(el, arr[i]);
            }
            return;
        }
        else if (!arr.length) {
            return;
        }
        if (el.classList && cls) {
            el.classList.remove(cls);
        }
        else {
            if (mw.tools.hasClass(el.className, cls)) el.className = (el.className + ' ').replace(cls + ' ', '').replace(/\s{2,}/g, ' ').trim();
        }

    },
    isEventOnElement: function (event, node) {
        if (event.target === node) {
            return true;
        }
        mw.tools.foreachParents(event.target, function () {
            if (event.target === node) {
                return true;
            }
        });
        return false;
    },
    isEventOnElements: function (event, array) {
        var l = array.length, i = 0;
        for (; i < l; i++) {
            if (event.target === array[i]) {
                return true;
            }
        }
        var isEventOnElements = false;
        mw.tools.foreachParents(event.target, function () {
            var l = array.length, i = 0;
            for (; i < l; i++) {
                if (event.target === array[i]) {
                    isEventOnElements = true;
                }
            }
        });
        return isEventOnElements;
    },
    isEventOnClass: function (event, cls) {
        if (mw.tools.hasClass(event.target, cls) || mw.tools.hasParentsWithClass(event.target, cls)) {
            return true;
        }
        return false;
    },
    firstChildWithClass: function (parent, cls) {
        var toreturn;
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeType === 1 && mw.tools.hasClass(this, cls)) {
                mw.tools.stopLoop(loop);
                toreturn = this;
            }
        });
        return toreturn;
    },
    firstChildWithTag: function (parent, tag) {
        var toreturn;
        var tag = tag.toLowerCase();
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                toreturn = this;
                mw.tools.stopLoop(loop);
            }
        });
        return toreturn;
    },
    hasChildrenWithClass: function (node, cls) {
        var final = false;
        mw.tools.foreachChildren(node, function () {
            if (mw.tools.hasClass(this.className, cls)) {
                final = true;
            }
        });
        return final;
    },
    parentsOrder: function (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node.parentNode;
        while (curr && curr.classList) {
            count++;
            var cls = curr.className;
            i = 0;
            for (; i < l; i++) {
                if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    },
    parentsAndCurrentOrder: function (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node;
        while (curr && curr.classList) {
            count++;
            var cls = curr.className;
            i = 0;
            for (; i < l; i++) {
                if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    },
    firstParentWithClass: function (el, cls) {
        if (!el) return false;
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (curr.classList.contains(cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithClass: function (el, cls) {
        if (!el) return false;
        var curr = el;
        while (curr && curr.classList) {
            if (mw.tools.hasClass(curr, cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstBlockLevel: function (el) {
        while(el && el.classList) {
            if(mw.tools.isBlockLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    },
    firstNotInlineLevel: function (el) {
        if(el.nodeType !== 1) {
            el = el.parentNode
        }
        if(!el) {
            return;
        }
        while(el && el.classList) {
            if(!mw.tools.isInlineLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    },
    firstParentOrCurrentWithId: function (el, id) {
        if (!el) return false;
        var curr = el;
        while (curr && el.classList) {
            if (curr.id === id) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithAllClasses: function (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr.classList) {
            if (mw.tools.hasAllClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithAnyOfClasses: function (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr.classList) {
            if (!curr) return false;
            if (mw.tools.hasAnyOfClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    lastParentWithClass: function (el, cls) {
        if (!el) return;
        var _has = false;
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (mw.tools.hasClass(curr, cls)) {
                _has = curr;
            }
            curr = curr.parentNode;
        }
        return _has;
    },
    firstParentWithTag: function (el, tag) {
        if (!el || !tag) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el.parentNode;
        while (curr && curr.classList) {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithTag: function (el, tag) {
        if (!el || !tag) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el;
        while (curr && curr.classList) {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    generateSelectorForNode: function (node, strict) {
         if(typeof strict === 'undefined') {
            strict = true;
        }
        if (node === null || node.nodeType === 3) {
            return false;
        }
        if (node.nodeName === 'BODY') {
            return 'body';
        }
        if(mw.tools.hasClass(node, 'edit')){
            var field = node.getAttribute('field');
            var rel = node.getAttribute('rel');
            if(field && rel){
                return '.edit[field="'+field+'"][rel="'+rel+'"]';
            }
        }
        if(strict && !node.id) {
            if(!node.classList.contains('edit') && mw.tools.isEditable(node)) {
                node.id = mw.id('mw-selector-');
            }
        }
        if (!!node.id /*&& node.id.indexOf('element_') === -1*/) {
            return '#' + node.id;
        }

        var filter = function(item) {
            return item !== 'changed'
                && item !== 'module-over'
                && item !== 'mw-bg-mask'
                && item !== 'element-current';
        };
        var _final = node.className.trim() ? '.' + node.className.trim().split(' ').filter(filter).join('.') : node.nodeName.toLocaleLowerCase();


        _final = _final.replace(/\.\./g, '.');
        mw.tools.foreachParents(node, function (loop) {
            if (this.id /*&& node.id.indexOf('element_') === -1*/) {
                _final = '#' + this.id + ' > ' + _final;
                mw.tools.stopLoop(loop);
                return false;
            }
            var n;
            if (this.className.trim()) {
                n = this.nodeName.toLocaleLowerCase() + '.' + this.className.trim().split(' ').join('.');
            }
            else {
                n = this.nodeName.toLocaleLowerCase();
            }
            _final = n + ' > ' + _final;
        });
        return _final
            .replace(/.changed/g, '')
            .replace(/.element-current/g, '')
            .replace(/.module-over/g, '');
    }
};

for (var i in domHelp) {
    mw.tools[i] = domHelp[i];
}
})();
