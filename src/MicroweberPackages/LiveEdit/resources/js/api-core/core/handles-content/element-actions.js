import MicroweberBaseClass from "../../services/containers/base-class";
import {Confirm} from "../classes/dialog";
import {ElementManager} from "../classes/element";
import {LinkPicker} from "../../services/services/link-picker";
import {DomService} from "../classes/dom";
import {HandleIcons} from "../handle-icons";
import { func } from "prop-types";

export class ElementActions extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;

        this.handleIcons = new HandleIcons();

    }

    editElement(el) {
        if (el.firstElementChild) {
            var firstChild = el.firstElementChild;

            var isCloneableWithImageAsFirstChild = el.classList && el.classList.contains('cloneable') && firstChild && firstChild.nodeName === 'IMG';
            var isCloneableWithImageAsFirstChildAsBg = el.classList && el.classList.contains('cloneable') && firstChild && firstChild.classList && firstChild.classList.contains('img-as-background') && firstChild.firstElementChild && firstChild.firstElementChild.nodeName === 'IMG'

            if (isCloneableWithImageAsFirstChild) {
                // move the element to the image for edit
                el = firstChild;
            }

            if (isCloneableWithImageAsFirstChildAsBg && firstChild.firstElementChild) {
                // move the element to the image for edit
                el = firstChild.firstElementChild;
            }
        }

        mw.app.editor.dispatch('editNodeRequest', el);
    }

    imagePlaceholder(newNode, css) {

        if (!newNode) {
            newNode = document.createElement('p');
        }


        newNode.innerHTML = `<span class="mw-img-placeholder-description">${mw.lang(`Click to set image`)}</span>`;
        newNode.dataset.type = mw.lang('Picture');

        mw.element(newNode).css(css)

        newNode.className = 'element mw-img-placeholder';


        const getFile = (e) => {
            let file;
            if (e.dataTransfer.items) {
                file = [...e.dataTransfer.items].find((item, i) => {
                    if (item.kind === "file") {
                        return true;
                    }
                });

            } else {
                file = [...e.dataTransfer.files][0];
            }
            return file;
        }


        function handleDragover(e) {

            let file = getFile(e);

            if (file) {
                newNode.classList.add('mw-drag-over')


                e.preventDefault();
            }


        }
    }


    deleteElement(el) {


        // todo: placeholder improvements
        const deletedImagePlaceholder = false;

        if (deletedImagePlaceholder && el.nodeName === 'IMG') {

            var edit = DomService.firstParentWithAnyOfClasses(el, ['edit'])

            mw.app.state.record({
                target: edit,
                value: edit.innerHTML
            });

            const $el = mw.element(el);
            const off = $el.offset();
            const display = $el.css('display');


            const newNode = mw.tools.setTag(el, 'p');

            var css = {
                display: display !== 'inline' ? display : 'inline-block',
                width: off.width,
                height: off.height,

            }

            this.imagePlaceholder(newNode, css);


            mw.app.state.record({
                target: edit,
                value: edit.innerHTML
            });

            return;
        }

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
            mw.app.registerChangedState(el);
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
        mw.app.liveEdit.handles.get('element').set(null);
        mw.app.liveEdit.handles.get('element').set(el);
    }

    removeLink(el) {
        //check if is IMG and is in A tag, then select A tag
        if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
            el = el.parentNode;
        }


        //get the link

        var closestLink = DomService.firstParentOrCurrentWithTag(el, 'a');
        if (closestLink) {
            var elementForUndo = closestLink.parentNode || closestLink;
            mw.app.registerUndoState(elementForUndo)

            el = closestLink;

            el.removeAttribute('href');
            el.removeAttribute('target');

            var shouldUnWrap = true;
            if (shouldUnWrap) {
                //unwrap the link
                var parent = el.parentNode;
                while (el.firstChild) {
                    parent.insertBefore(el.firstChild, el);
                }
                el.remove();
            }
            this.proto.refreshElementHandle(el);
            mw.app.registerChangedState(elementForUndo);
        }


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
            } else if (el.nodeName === 'IMG' && !el.parentNode) {
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
            this.proto.refreshElementHandle(el);

            mw.app.registerChangedState(el);
        });

        newLinkEditor.selectLink(el);

    }

    openElementStyleEditor(el) {
        mw.app.editor.dispatch('editNodeStyleRequest', el);
    }

    moveBackward(el) {
        const prev = el.previousElementSibling;
        if (prev) {
            prev.before(el);
            this.proto.elementHandle.set(el);
            mw.app.registerChangedState(el);
            mw.app.liveEdit.handles.get('element').set(null);
            mw.app.liveEdit.handles.get('element').set(el);
        }
    }

    moveForward(el) {
        const next = el.nextElementSibling;

        if (next) {
            next.after(el);
            this.proto.elementHandle.set(el);
            mw.app.registerChangedState(el);
            mw.app.liveEdit.handles.get('element').set(null);
            mw.app.liveEdit.handles.get('element').set(el);
        }
    }

    resetImageSize(el) {
        mw.app.registerUndoState(el);
        el.style.width = '';
        el.style.height = '';
        el.style.objectFit = '';
        el.dataset.objectFit = '';
        //remove class mw-resized
        el.classList.remove('mw-resized');
        mw.app.registerChangedState(el);
        this.proto.elementHandle.set(el);
    }

    editBackgroundColor(el, selfBtn) {

        var dlg = mw.top().dialog({
            width: 280,
            closeButtonAction: 'hide',
            disableTextSelection: true,
            title: mw.lang('Choose color'),
            overlayClose: true,
            closeOnEscape: false,
        });





        var _pauseSetValue = false;



        var picker = mw.colorPicker({
            // element: tip.get(0),
            element: dlg.container,

            method: 'inline',
            showHEX: false,
            onchange: function (color) {
                // mw.top().app.liveEdit.handles.get('element').getTarget().style.backgroundColor = color;
                if(selfBtn) {
                    selfBtn.querySelector('.mw-le--handle-icon--color-color').style.backgroundColor = color;
                }
                mw.top().app.cssEditor.temp(mw.top().app.liveEdit.handles.get('element').getTarget(), 'background-color', color)

            },

        });


    }

    editImage(element) {
        mw.app.editImageDialog.editImage(element.src, (imgData) => {
            if (typeof imgData !==  'undefined' && imgData.src) {

                element.src = imgData.src

                if (imgData.sizeChanged) {
                    // reset width and height
                    //   element.style.width = '';
                    element.style.height = 'auto';
                }
            }
            mw.top().app.registerChange(element);
            mw.app.liveEdit.play();
        });

    }
    editBackgroundImageOnParent(element) {

        const hasBgOnParent = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['background-image-holder', 'img-holder']);
        if (hasBgOnParent) {
            element = hasBgOnParent;

            var dialog = this.imagePicker(function (res) {
                mw.top().app.registerChange(element);
                var url = res.src ? res.src : res;
                if (!url) return;
                url = url.toString();
                element.style.backgroundImage = `url(${url})`

                mw.app.liveEdit.play();
                dialog.remove();

                mw.top().app.registerChangedState(element);
            });
        }
    }
    editBackgroundImage(element) {

        const hasBgOnParent = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['background-image-holder', 'img-holder']);
        if(hasBgOnParent){
            element = hasBgOnParent;
        }

        mw.top().app.registerUndoState(element);

/*
        if(element.style.backgroundImage) {
        var bg = element.style.backgroundImage.trim().split('url(')[1];
        if (bg) {

            bg = bg.split(')')[0]
                .trim()
                .split('"')
                .join('');

            }
        }*/

        var dialog = this.imagePicker(function (res) {
            mw.top().app.registerChange(element);
            var url = res.src ? res.src : res;
            if (!url) return;
            url = url.toString();
            element.style.backgroundImage = `url(${url})`

            mw.app.liveEdit.play();
            dialog.remove();

            mw.top().app.registerChangedState(element);
        });
    }
     imagePicker(onResult) {
        var dialog;
        var picker = new mw.filePicker({
            type: 'images',
            label: false,
            autoSelect: false,
            footer: true,
            _frameMaxHeight: true,
            onResult: onResult
        });
        dialog = mw.top().dialog({
            content: picker.root,
            title: mw.lang('Select image'),
            footer: false,
            width: 860,


        });
        picker.$cancel.on('click', function () {
            dialog.remove()
        })


        $(dialog).on('Remove', () => {

            mw.app.liveEdit.play();
            const target = mw.top().app.liveEdit.handles.get('element').getTarget();
            mw.top().app.liveEdit.handles.get('element').set(null);
            mw.top().app.liveEdit.handles.get('element').set(target);
        })
        return dialog;
    }


}
