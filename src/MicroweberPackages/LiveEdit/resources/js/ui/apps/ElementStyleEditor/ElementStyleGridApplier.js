export default class ElementStyleGridApplier {





    static supportsGrid(node) {
        if (node.ownerDocument && node.ownerDocument.defaultView && node.ownerDocument.defaultView.mw && node.ownerDocument.defaultView.mw.__animate) {
            if (node.ownerDocument.defaultView.mw.tools.isEditable(node)) {
                return true;
            }
        }


        return false;

    }


}







