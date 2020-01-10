mw.DomTree = function (options) {
    var defaults = {
        className: 'edit',
        document: document
    };

    options = options || {};

    this.settings = $.extend({}, defaults, options);

    this.document = this.settings.document;

    this.data = []; // { element: Node, label: String, children: [] }
    this._checkData = [];

    this.preinit = function () {
        var all = this.document.querySelectorAll('.', this.settings.className);
        var i = 0;
        for (  ; i < all.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(all[i], this.settings.className)) {

                this.data.push({
                    element: all[i],
                    label: 'Edit',
                    children: []
                });
                this._checkData.push(all[i]);
            }
        }
    };

    this.findElementInTree = function (node, data) {
        data = data || this.data;
        var i = 0;
        for (  ; i < data.length; i++ ) {
            if(data[i].element === node) {
                return data[i];
            }
            var child = this.findElementInTree(node, data[i].children);
            if(child) {
                return child;
            }
        }
    };

    this.elementExists = function (node) {
        return this._checkData.indexOf(node) !== -1;
    };

    this.findFirstParent = function (node) {
        while(node && node !== this.document.body){
            var exists = this.elementExists(node);
            if(exists) {
                return this.findElementInTree(node);
            }
            node = node.parentNode;
        }
    };

    this.populateFromElement = function (node) {
        var from = this.findFirstParent(node);
        if(from === node) {
            return;
        }

    };

    this.init = function () {
        this.preinit();
    };

    this.init();

};
