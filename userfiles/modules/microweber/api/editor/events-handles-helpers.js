class MWEditorEventHandlesHelpers {

    mergeSiblings(element, sibling = true) {

        // // Merge empty links and anchors also.
        // if (!(inlineOnly === false || ['A', 'A'].includes(this.nodeName))) {
        //     return;
        // }

        this.mergeElements(element, sibling, true);
        this.mergeElements(element, sibling);
    }

    mergeElements(element, sibling, isNext) {
        if (sibling && sibling.nodeType === Node.ELEMENT_NODE) {
            // Jumping over empty inline elements, e.g. <b><i></i></b>,
            // queuing them to be moved later.
            const pendingNodes = [];

            while (sibling.childNodes.length === 0 || (sibling.childNodes.length === 1 && sibling.firstChild.nodeType === Node.TEXT_NODE && sibling.firstChild.textContent.trim() === '')) {
                pendingNodes.push(sibling);
                sibling = isNext ? sibling.nextElementSibling : sibling.previousElementSibling;
                if (!sibling || sibling.nodeType !== Node.ELEMENT_NODE)
                    return;
            }

            if (element.isEqualNode(sibling)) {
                // Save the last child to be checked too, to merge things like
                // <b><i></i></b><b><i></i></b> => <b><i></i></b>
                const innerSibling = isNext ? element.lastElementChild : element.firstElementChild;

                // Move pending nodes first into the target element.
                pendingNodes.forEach(node => element.appendChild(node));

                while (sibling.firstChild)
                    element.appendChild(isNext ? sibling.firstChild : sibling.lastChild);

                sibling.parentNode.removeChild(sibling);

                // Now check the last inner child.
                if (innerSibling && innerSibling.nodeType === Node.ELEMENT_NODE)
                    this.mergeSiblings.call(innerSibling);
            }
        }
    }


}
