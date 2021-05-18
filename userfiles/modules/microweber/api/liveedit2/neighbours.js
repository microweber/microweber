export const GetPointerTargets = (options) => {

    options = options || {};

    var scope = this;

    var defaults = {
        document: document
    };

    this.settings = mw.object.extend({}, defaults, options);

    this.document = this.settings.document;
    this.body = this.document.body;


    var distanceMax = 20;

    function distance(x1, y1, x2, y2) {
        return Math.hypot(x2-x1, y2-y1);
    }

    function isInRange(el1, el2) {
        return distance(el1, el2) <= distanceMax;
    }

    var validateNode = function (node) {
        return node.type === 1;
    };

    var getChildren = function (parent, target) {
        var res = [], curr = parent.firstElementChild;
        while (curr && curr !== target && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            if(curr.children && curr.children.length) {
                res.push.apply(res, getChildren(parent, target));
            }
            curr = validateNode(curr.nextElementSibling);
        }
        return res;
    };

    var getAbove = function(target) {
        var res = [], curr = target.previousElementSibling;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.previousElementSibling;
        }
        return res;
    };

    var getBelow = function(target) {
        var res = [], curr = target.nextElementSibling;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.nextElementSibling;
        }
        return res;
    };

    var getParents = function (target) {
        var res = [], curr = target.parentElement;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.parentElement;
        }
        return res;
    };
    this.getParents = getParents;
    this.getBelow = getBelow;

    this.getNeighbours = function (event) {
        var target = event.target;
        return [].concat(getParents(target), getAbove(target), getBelow(target), getChildren(target, target));
    };


    var round5 = function (x){
        return (x % 5) >= 2.5 ? (x / 5) * 5 + 5 : (x / 5) * 5;
    };

    var getNearCoordinates = function (x, y) {
        x = round5(x);
        y = round5(y);
        var res = [];
        var x1 = x - distanceMax;
        var x1Max = x + distanceMax;
        var y1 = y - distanceMax;
        var y1Max = y + distanceMax;
        for ( ; x1 < x1Max; x1 += 5) {
            for ( ; y1 <= y1Max; y1 += 5 ) {
                res.push([x1, y1]);
            }
        }
        return res;
    };

    var addNode = function (el, res) {
        if(el && !!el.parentElement && res.indexOf(el) === -1 && scope.body !== el) {
            res.push(el);
        }
    };

    this.fromPoint = function (x, y) {
        var res = [];
        var el = scope.document.elementFromPoint(x, y);
        if (!el ) return [];
        addNode(el, res);
        var dots = getNearCoordinates(x, y);
        dots.forEach(function (coords){
            addNode(scope.document.elementFromPoint(coords[0], coords[1]), res);
        });
        return res;
    };
};


