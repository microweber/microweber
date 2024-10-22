import MicroweberBaseClass from "../../services/containers/base-class.js";
import {DomService} from "../classes/dom.js";

export class ElementSettingsTarget extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }

    getSettingsTarget(target) {
        if (!target) {
            return target;
        }

        if (!target.firstElementChild) {
            return target;
        }


        var firstChild = target.firstElementChild;
        // var isCloneableImage = (target.classList.contains('cloneable') && target.nodeName === 'IMG');
        var isCloneableWithImageAsFirstChild = target.classList && target.classList.contains('cloneable') && firstChild && firstChild.nodeName === 'IMG';
        var isCloneableWithImageAsFirstChildAsBg = target.classList && target.classList.contains('cloneable') && firstChild && firstChild.classList && firstChild.classList.contains('img-as-background');

        if (isCloneableWithImageAsFirstChild) {
            // move the element to the image for edit
            target = firstChild;
        } else if (isCloneableWithImageAsFirstChildAsBg) {
            // move the element to the image for edit
            target = firstChild.firstElementChild;
        }

        return target;
    }


    canDropInTarget(target) {
        if (!target) {
            return false;
        }

        if (target.classList && target.classList.contains('edit')) {
            const noBlocksInThese = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'li'];
            if (noBlocksInThese.indexOf(target.nodeName.toLowerCase()) !== -1) {
                return false;
            }

        }

        var can = DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);

        if (!can) {
            if (target.classList && target.classList.contains('mw-col')) {
                if (target.firstElementChild && target.firstElementChild.classList.contains('mw-col-container')) {
                    if (target.firstElementChild.firstElementChild && target.firstElementChild.firstElementChild.classList.contains('allow-drop')) {
                        can = true;
                    }
                }
            }
        }
        return can;
    }


    canImageBeAligned(target) {
        if (!target) {
            return false;
        }
        if (target.tagName !== 'IMG') {
            return false;
        }


        var can = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['row']);
        return can;

    }

    canBeFreeDraggableElement(target) {
        if (!target) {
            return false;
        }
        var can = false;
        var node = target;
        if (node && node && node.nodeType === 1) {
            var css = mw.CSSParser(node);

            if (!css || !css.get) return;

            var result = css.get.position();
            if (result) {

                if (result === 'absolute' || result === 'relative' || result === 'fixed' || result === 'sticky') {
                    can = true;
                }

                if (result === 'static' || result === 'initial' || result === 'inherit' || result === 'unset' || result === 'revert') {
                    can = false;
                }
            }

        }
        return can;

    }


}
