(function(){
    var domHelp = {
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
        while (curr !== document.body) {
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
        while (curr && curr !== mwd.body) {
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
        while (curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
        while (curr && curr !== document.body) {
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
}
})()