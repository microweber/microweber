export default class ElementStyleAnimationsApplier {
    static getAllAnimations() {


    }

    static previewAnimation(node, animation) {
        var nodeWindow = node.ownerDocument.defaultView;

        var sel = nodeWindow.mw.tools.generateSelectorForNode(node);
        var id = nodeWindow.mw.id('animation');

        var config = Object.assign({selector: sel, id: id}, animation);
        nodeWindow.mw.__animate(config);
    }

    static setAnimation(node, animation) {


        var nodeWindow = node.ownerDocument.defaultView;

        var sel = nodeWindow.mw.tools.generateSelectorForNode(node);
        var id = nodeWindow.mw.id('animation');

        if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
        }

        var curr = node.$$mwAnimations.find(function (item) {
            return item.when === animation.when;
        });

        if (curr) {
            //this.remove(curr.id)
        }


        if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
        }
        var config = Object.assign({selector: sel, id: id}, animation);
        node.$$mwAnimations.push(config)
        nodeWindow.mw.__pageAnimations.push(config)

        nodeWindow.mw.__animate(config)

        return config;
    }


    static supportsAnimations(node) {
        if (node.ownerDocument && node.ownerDocument.defaultView && node.ownerDocument.defaultView.mw && node.ownerDocument.defaultView.mw.__animate) {
            return true;
        }
        return false;

    }

    static getAnimation(node) {
        var nodeWindow = node.ownerDocument.defaultView;
        if (nodeWindow.mw.__pageAnimations) {

            // var curr = mw.__pageAnimations.find(function(a){
            //     return !!anim.id || a.id === anim.id
            // });

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







