(function (){
    var InteractionService = function (settings, rootNode) {
        this.settings = settings;

        rootNode = rootNode || document.body;

        var doc = rootNode.ownerDocument;

        var _e = {};
        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        var handleMove = function (e) {
            var tartet = e.target;


        };


        this.init = function () {
            rootNode.addEventListener("mousemove", function (event){
                handleMove(event);
            });
            rootNode.addEventListener("touchmove", function (event){
                handleMove(event);
            });
        };

        this.init();

    };
})();
