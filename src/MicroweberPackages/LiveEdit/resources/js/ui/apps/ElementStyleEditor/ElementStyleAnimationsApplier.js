export default class ElementStyleAnimationsApplier {


    static previewAnimation(node, animation) {
        var nodeWindow = node.ownerDocument.defaultView;

        var sel = nodeWindow.mw.tools.generateSelectorForNode(node);
        var id = nodeWindow.mw.id('animation');

        var config = Object.assign({selector: sel, id: id}, animation);
        nodeWindow.mw.__animate(config);
    }

    static removeAnimations(node) {
        var nodeWindow = node.ownerDocument.defaultView;
        var targetMw = nodeWindow.mw;
        var sel = nodeWindow.mw.tools.generateSelectorForNode(node);

        var curr = nodeWindow.mw.__pageAnimations.find(function (a) {
            return a.selector === sel;
        });
        if (!curr) {
            return;
        }


        var item = curr;
        var citem = Object.assign({}, item);
        targetMw.__pageAnimations.splice(targetMw.__pageAnimations.indexOf(item), 1);
        Array.from(targetMw.doc.querySelectorAll(citem.selector)).forEach(function (node) {
            if (node.$$mwAnimations && node.$$mwAnimations.length) {
                var i = node.$$mwAnimations.findIndex(function (a) {
                    return a.id === item.id;
                });
                if (i > -1) {
                    node.$$mwAnimations.splice(i, 1);
                }
            }
        });

    }

    static setAnimation(node, animation) {


        var nodeWindow = node.ownerDocument.defaultView;

        var sel = nodeWindow.mw.tools.generateSelectorForNode(node);
        var id = nodeWindow.mw.id('animation');

        if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
        }

        this.removeAnimations(node);

        if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
        }
        var config = Object.assign({selector: sel, id: id}, animation);
        node.$$mwAnimations.push(config)
        nodeWindow.mw.__pageAnimations.push(config)

        nodeWindow.mw.__animate(config);


        if(mw.top().app){
            mw.top().app.registerChange(node);
        }

        return config;
    }


    static supportsAnimations(node) {
        if (node.ownerDocument && node.ownerDocument.defaultView && node.ownerDocument.defaultView.mw && node.ownerDocument.defaultView.mw.__animate) {
            if (node.ownerDocument.defaultView.mw.tools.isEditable(node)) {
                return true;
            }
        }


        return false;

    }

    static getAnimation(node) {
        var nodeWindow = node.ownerDocument.defaultView;
        if (nodeWindow.mw.__pageAnimations) {


            var sel = nodeWindow.mw.tools.generateSelectorForNode(node);

            var curr = nodeWindow.mw.__pageAnimations.find(function (a) {
                return a.selector === sel;
            });
            if (curr) {
                return curr;
            }

        }


        return false;

    }
}







