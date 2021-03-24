(function (){
    var Neighbours = function (config) {

        var distanceMax = 20;

        function distance(el1, el2) {
            var rect1 = el1.getBoundingClientRect();
            var rect2 = el2.getBoundingClientRect();
            var x1 = rect1.left + rect1.width/2;
            var y1 = rect1.top + rect1.height/2;
            var x2 = rect2.left + rect2.width/2;
            var y2 = rect2.top + rect2.height/2;
            return Math.sqrt(Math.pow(x1 - x2, 2) + Math.pow(y1 - y2, 2));
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

        this.getNeighbours = function (event) {
            var target = event.target;
            return [].concat(getParents(target), getAbove(target), getBelow(target), getChildren(target, target));
        };


    };

    window.Neighbours = Neighbours;

})();
