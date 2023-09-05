import MicroweberBaseClass from "../../services/containers/base-class";
import {Confirm} from "../classes/dialog";
import {ElementManager} from "../classes/element";
import {LinkPicker} from "../../services/services/link-picker";
import {DomService} from "../classes/dom";

export class ElementActions extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }

    deleteElement(el) {
        if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
            el = el.parentNode;
        }
        if (el.nodeName === 'IMG') {
            var hasImgAsBackgroundAsParent = DomService.hasAnyOfClassesOnNodeOrParent(el, ['img-as-background', 'image-holder']);
            if (hasImgAsBackgroundAsParent) {
                el = DomService.firstParentWithAnyOfClasses(el, ['img-as-background', 'image-holder']);
            }
        }

        var parentEditField = mw.tools.firstParentWithClass(el, 'edit');

        Confirm(ElementManager('<span>Are you sure you want to delete this element?</span>'), () => {
            mw.app.registerChangedState(el)
            el.remove()
            if (parentEditField) {
                mw.app.registerUndoState(parentEditField)
            }
        })
    }

    cloneElement(el) {
        //check if is IMG and cloneable is in A tag, then delete A tag
        if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
            el = el.parentNode;
        }
        mw.app.registerUndoState(el)


        ElementManager(el).after(el.outerHTML);
        var next = el.nextElementSibling;
        if (el.classList.contains('mw-col')) {
            el.style.width = ''
            next.style.width = ''
        }

        this.proto.elementHandle.set(el);
        mw.app.registerChangedState(el);
    }

    editLink(el) {
        //check if is IMG and is in A tag, then select A tag
        if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
            el = el.parentNode;
        }

        mw.app.registerUndoState(el)

        var newLinkEditor = new LinkPicker();
        newLinkEditor.on('selected', (data) => {
            var newUrl = '';
            if (data.url) {
                newUrl = data.url;
            }


            var shouldWrap = false;
            if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName !== 'A') {
                shouldWrap = true;
            } else if (el.nodeName !== 'IMG' && !el.parentNode) {
                shouldWrap = true;
            }
            if (shouldWrap) {
                var wrap = document.createElement('a');
                el.parentNode.insertBefore(wrap, el);
                wrap.appendChild(el);
                el = wrap;
            }
            el.setAttribute('href', newUrl);
            if (data.openInNewWindow) {
                el.setAttribute('target', '_blank');
            } else {
                el.removeAttribute('target');
            }
            mw.app.registerChangedState(el);
        });

        newLinkEditor.selectLink(el);

    }

    moveBackward(el) {
        const prev = el.previousElementSibling;
        if (prev) {
            prev.before(el);
            this.proto.elementHandle.set(el);
            mw.app.registerChangedState(el)
        }
    }

    moveForward(el) {
        const next = el.nextElementSibling;

        if (next) {
            next.after(el);
            this.proto.elementHandle.set(el);
            mw.app.registerChangedState(el)
        }
    }

    resetImageSize(el) {
        mw.app.registerUndoState(el);
        el.style.width = '';
        el.style.height = '';
        mw.app.registerChangedState(el);
        this.proto.elementHandle.set(el);
    }


}
