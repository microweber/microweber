import MicroweberBaseClass from "../../services/containers/base-class";

import {DomService} from "../classes/dom";


export class ElementHandleButtonsVisibility extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;


    }


    shouldShowCloneButtonInMoreButton(target) {
        const isVisible = this.isPlaceholder(target) && (target.classList.contains('cloneable') || target.classList.contains('mw-col'));
        if (!isVisible) {
            var hasCloneable = DomService.hasAnyOfClasses(target, ['cloneable']);
            if (hasCloneable) {
                return true;
            }
            var hasCloneableClassOnParents = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);
            if (hasCloneableClassOnParents) {
                return true;
            }
            // if(this.shouldShowCloneButton(target)) {
            //     return true;
            // }

        }
        return isVisible;

    }
    shouldShowCloneButton(target) {
        const isVisible = this.isPlaceholder(target) && (target.classList.contains('cloneable') || target.classList.contains('mw-col'));
        if (!isVisible) {
            const hasCloneable = DomService.hasAnyOfClasses(target, ['cloneable']);
            if (hasCloneable) {
                return true;
            }

        }
        return isVisible;
    }

    shouldShowMoveBackwardInMoreButton(target) {
        const hasCloneable = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);
        if (hasCloneable) {
            return true;
        }

        return false;
    }

    shouldShowMoveBackwardButton(target) {
        const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
        const prev = target.previousElementSibling;

        const isVisible = this.isPlaceholder(target) && isCloneable && prev;

        return isVisible;
    }
    shouldShowMoveForwardInMoreButton(target) {
        const hasCloneable = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);
        if (hasCloneable) {
            return true;
        }

        return false;
    }

    shouldShowMoveForwardButton(target) {
        const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
        const next = target.nextElementSibling;

        const isVisible = this.isPlaceholder(target) && isCloneable && next;
        return isVisible;
    }

    shouldShowResetElementSizeButton(target) {
        const hasResizedClass = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['mw-resized']);

        if (hasResizedClass) {
            return true;
        }
    }

    shouldShowResetImageSizeButton(target) {
        const hasResizedClass = DomService.hasAnyOfClasses(target, ['mw-resized']);

        if (hasResizedClass) {
            const isImage = target.nodeName === 'IMG';
            if (isImage) {
                return true;
            }
        }
    }


    shouldShowFitImageButton(target) {
        //has class mw-resized
        if (target && target.classList) {
            const isImage = target.nodeName === 'IMG'  ;
            if (isImage) {
                return !target.parentNode.classList.contains('img-as-background');
            }
        }
    }


    shouldShowLinkButton(target) {
        const isImageOrLink = target.nodeName === 'IMG' || target.nodeName === 'A' || target.parentNode && target.parentNode.nodeName === 'A';
        if (isImageOrLink && !this.isPlaceholder(target)) {
            return true;
        }
    }

    shouldShowUnlinkButton(target) {
        const isLinkOrParentWithLink = target.nodeName === 'A' || target.parentNode && target.parentNode.nodeName === 'A';
        if (isLinkOrParentWithLink && !this.isPlaceholder(target)) {
            return true;
        }
    }

    shouldShowStyleEditorButton(target) {
        var selfVisible = true;


        // const isImageOrLink = target.nodeName === 'IMG' || target.nodeName === 'A';
        // if (isImageOrLink && !this.isPlaceholder(target)) {
        //
        //     selfVisible = false;
        //
        // }
        return selfVisible;
    }

    shouldShowBackroundImageEditorButtonOnTheMoreButton(target) {
        var selfVisible = false;
        // element to parent with background image
        const hasBg = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['background-image-holder', 'img-holder']);
        if(hasBg) {
            selfVisible = true;
        }
        return selfVisible;
    }
    shouldShowBackroundImageEditorButton(target) {

        var selfVisible = false;
        // element to parent with background image
        const hasBg = DomService.hasAnyOfClasses(target, ['background-image-holder', 'img-holder']);
        if(hasBg) {
            selfVisible = true;
        }

        return selfVisible;
    }

    shouldShowDragButton(target) {
        var selfVisible = true;
        const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
        const isEdit = target.classList.contains('edit');
        if (isCloneable || isEdit) {
            selfVisible = false;
        }

        if (DomService.hasAnyOfClassesOnNodeOrParent(target, ['img-as-background'])) {
            selfVisible = false;
        }
        return selfVisible;
    }

    shouldShowEditImageButton(element) {
        var selfVisible = false;
        if (element.nodeName === 'IMG') {
            selfVisible = true;
        }
        return selfVisible;
    }

    shouldShowEditButton(target) {
        var selfVisible = true;


        var isCloneable = (target.classList.contains('cloneable') && target.nodeName !== 'IMG');
        if (isCloneable || target.classList.contains('mw-col')) {
            selfVisible = false;
        }

        var newTarget = mw.app.liveEdit.elementHandleContent.settingsTarget.getSettingsTarget(target);
        if (newTarget !== target) {
            selfVisible = true;
        }


        if (target.classList.contains('edit')) {
            if (!!target.innerHTML.trim()) {
                if ((target.getAttribute('field') !== 'title' || target.getAttribute('rel') !== 'title') && !target.classList.contains('plain-text')) {
                    selfVisible = false;
                }
            }
            if (target.querySelector('.module')) {
                selfVisible = false;
            }
        }
        if (target.classList.contains('spacer')) {
            selfVisible = false;
        }
        if (target.classList.contains('no-typing')) {
            selfVisible = false;
        }

        return selfVisible;
    }

    shouldShowInsertModuleButton(target) {
        var selfVisible = true;

        var canDrop = mw.app.liveEdit.elementHandleContent.settingsTarget.canDropInTarget(target);
        if (!canDrop) {
            selfVisible = false;
        }
        return selfVisible;
    }


    shouldShowSettingsButton(target) {
        var selfVisible = true;

        // todo: safe mode for css editor

        const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
        if (isCloneable) {
            selfVisible = true;
        }

        if(target.classList.contains('mw-col')) {
            selfVisible = false;
        } else if (target.classList.contains('spacer')) {
            selfVisible = false;
        } else if(this.isPlaceholder(target)) {
            selfVisible = false;
        } else if(this.shouldShowStyleEditorButton(target)) {
            selfVisible = false;
        }

        return selfVisible;
    }

    shouldShowEditBackgroundColorButton(target) {
        var selfVisible = false;


        if (target.classList.contains('background-color-element') ) {
            selfVisible = true;
        }
        return selfVisible;
    }

    shouldShowDeleteElementButton(target) {
        let selfVisible = true;
        if(target.classList.contains('edit')) {
            selfVisible = false;
        }

        if(!DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
            selfVisible = false;
        }
        return selfVisible;

    }

    isPlaceholder(target) {
        return target.classList.contains('mw-img-placeholder');
    }
}
