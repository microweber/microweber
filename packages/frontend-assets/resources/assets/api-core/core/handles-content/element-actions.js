import MicroweberBaseClass from "../../services/containers/base-class.js";
import {Confirm} from "../classes/dialog.js";
import {ElementManager} from "../classes/element.js";
import {LinkPicker} from "../../services/services/link-picker.js";
import {DomService} from "../classes/dom.js";
import {HandleIcons} from "../handle-icons.js";


export class ElementActions extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;

        this.handleIcons = new HandleIcons();


    }


    editElement(el) {
        if (el && el.firstElementChild) {
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


        mw.confirm('<span>Are you sure you want to delete this element?</span>', () => {
            var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['regular-mode', 'edit', 'safe-mode']);

            if (edit) {
                mw.app.registerSyncAction(edit, true);
            }
            el.remove()
            if (edit) {

                mw.app.liveEdit.handles.get('element').set(null);
            }
        })
    }

    cloneElementFirstClonableParent(target) {

        var hasCloneableClassOnParents = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);

        if (hasCloneableClassOnParents) {
            return this.cloneElement(hasCloneableClassOnParents);
        }
    }

    cloneElement(el) {
        mw.top().app.liveEdit.stopTyping();

        //check if is IMG and cloneable if its in A tag, then delete A tag
        if (el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
            el = el.parentNode;
        }
        mw.top().app.registerSyncAction(el);

        ElementManager(el).after(el.outerHTML);
        var next = el.nextElementSibling;
        if (el.classList.contains('mw-col')) {
            el.style.width = ''
            next.style.width = ''
        }
        mw.top().app.cssEditor.cloneNodeStyles(el, next);
        var newNode = ElementManager(el).next().get(0);
        newNode.id = mw.id();


        mw.top().app.cssEditor.syncEach('[id]', el, newNode, function (from, to) {
            to.id = mw.id();
            mw.top().app.cssEditor.cloneNodeStyles(from, to);
        });


        this.proto.elementHandle.set(el);

        mw.app.liveEdit.handles.get('element').set(null);
        mw.app.liveEdit.handles.get('element').set(el);


        mw.top().app.domTree.tree.refresh(newNode.parentNode)
        mw.top().app.domTree.tree.select(newNode)


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

    moveBackwardFirstClonableParent(target) {
        const hasCloneable = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);
        if (hasCloneable) {
            this.moveBackward(hasCloneable);
        }
    }

    moveBackward(el) {
        const prev = el.previousElementSibling;
        if (prev) {
            mw.app.registerSyncAction(el);
            prev.before(el);
            this.proto.elementHandle.set(el);

            mw.app.liveEdit.handles.get('element').set(null);
            mw.app.liveEdit.handles.get('element').set(el);
        }
    }

    moveForwardFirstClonableParent(target) {
        const hasCloneable = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['cloneable', 'mw-col']);
        if (hasCloneable) {
            this.moveForward(hasCloneable);
        }
    }

    moveForward(el) {
        const next = el.nextElementSibling;

        if (next) {
            mw.app.registerSyncAction(el);
            next.after(el);
            this.proto.elementHandle.set(el);
            mw.app.registerChangedState(el);
            mw.app.liveEdit.handles.get('element').set(null);
            mw.app.liveEdit.handles.get('element').set(el);
        }
    }

    resetElementSizeOnSelfOfParent(el) {
        const hasResizedClass = DomService.firstParentOrCurrentWithAnyOfClasses(el, ['mw-resized']);
        if (hasResizedClass) {
            el = hasResizedClass;
        }


        mw.app.registerUndoState(el);
        el.style.width = '';
        el.style.height = '';
        el.style.objectFit = '';
        el.dataset.objectFit = '';
        //remove class mw-resized
        el.classList.remove('mw-resized');
        mw.app.registerChangedState(el);
        const sel = mw.tools.generateSelectorForNode(el);
        mw.top().app.cssEditor.setPropertyForSelector(sel, 'width', '', true, true);
        mw.top().app.cssEditor.setPropertyForSelector(sel, 'height', '', true, true);
        this.proto.elementHandle.set(el);
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
        mw.app.registerSyncAction(el);

        var picker = mw.colorPicker({
            // element: tip.get(0),
            element: dlg.container,

            method: 'inline',
            showHEX: true,
            onchange: function (color) {
                // mw.top().app.liveEdit.handles.get('element').getTarget().style.backgroundColor = color;
                if (selfBtn) {
                    selfBtn.querySelector('.mw-le--handle-icon--color-color').style.backgroundColor = color;
                }
                var target = mw.top().app.liveEdit.handles.get('element').getTarget();
                mw.top().app.cssEditor.temp(target, 'background-color', color);
                mw.top().app.registerChangedState(target);


            },

        });


    }

    editImage(element) {
        var dialog = this.imagePicker(function (res) {


            var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode', 'edit', 'safe-mode']);

            if (edit) {
                mw.app.registerSyncAction(edit, true);
            }
            var url = res.src ? res.src : res;
            if (!url) return;
            url = url.toString();
            element.src = url;
            element.style.objectFit = '';
            element.style.width = 'auto';
            element.style.height = 'auto';

            mw.app.liveEdit.play();
            dialog.remove();
        });
    }

    editImageWithAI(element) {

        function toDataURL(image, callback) {

            const img = new Image();
            img.crossOrigin = "anonymous";

            const size = 200;

            let maxWidth = size, maxHeight = size


            img.onload = function () {

                const originalWidth = img.width;
                const originalHeight = img.height;


                const aspectRatio = originalWidth / originalHeight;

                let newWidth, newHeight;


                if (originalWidth > maxWidth || originalHeight > maxHeight) {
                    if (originalWidth / maxWidth > originalHeight / maxHeight) {

                        newWidth = maxWidth;
                        newHeight = maxWidth / aspectRatio;
                    } else {

                        newHeight = maxHeight;
                        newWidth = maxHeight * aspectRatio;
                    }
                } else {

                    newWidth = originalWidth;
                    newHeight = originalHeight;
                }


                const canvas = document.createElement('canvas');
                canvas.width = newWidth;
                canvas.height = newHeight;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, newWidth, newHeight);


                const base64String = canvas.toDataURL();


                callback(base64String);
            };

            img.src = image;
        }

        const content = `
            <div>
                <img id="ai-image-editor--image" src="${element.src}" style="max-height:300px">
                <textarea id="ai-image-editor--field"></textarea>
                <br>
                <button disabled type="button" id="ai-image-editor--button">Submit</button>
                <br><br>
            </div>
        `;

        const dlg = mw.dialog({
            content
        });

        const img = dlg.dialogContainer.querySelector('#ai-image-editor--image');
        const area = dlg.dialogContainer.querySelector('#ai-image-editor--field');
        const button = dlg.dialogContainer.querySelector('#ai-image-editor--button');

        area.addEventListener('input', e => {
            button.disabled = !area.value.trim();
        });
        button.addEventListener('click', e => {
            toDataURL(img.src, b64 => {
                $.post(mw.settings.site_url + 'api/ai/editImage', {
                    prompt: area.value,
                    url: b64
                }, data => {
                    img.src = 'data:image/png;base64,' + data.resp.data
                })
            })
        })

    }

    editImageWithEditor(element) {
        mw.app.editImageDialog.editImage(element.src, (imgData) => {
            if (typeof imgData !== 'undefined' && imgData.src) {
                var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode', 'edit', 'safe-mode']);

                if (edit) {
                    mw.app.registerSyncAction(edit, true);
                }

                element.src = imgData.src

                if (imgData.sizeChanged) {

                    element.style.height = 'auto';
                }
            }

            mw.app.liveEdit.play();
        });

    }

    editBackgroundImageOnParent(element) {

        const hasBgOnParent = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['background-image-holder', 'img-holder']);
        if (hasBgOnParent) {
            element = hasBgOnParent;

            var dialog = this.imagePicker(function (res) {
                var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode', 'edit', 'safe-mode']);
                if (edit) {
                    mw.top().app.registerChangedState(edit);
                }
                var url = res.src ? res.src : res;
                if (!url) return;
                url = url.toString();
                element.style.backgroundImage = `url(${url})`

                mw.app.liveEdit.play();
                dialog.remove();

                if (edit) {
                    mw.top().app.registerChangedState(edit);
                }
            });
        }
    }

    editBackgroundImage(element) {

        const hasBgOnParent = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['background-image-holder', 'img-holder']);
        if (hasBgOnParent) {
            element = hasBgOnParent;
        }
        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode', 'edit', 'safe-mode']);
        if (edit) {
            mw.top().app.registerChangedState(edit);
        }

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
            var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode', 'edit', 'safe-mode']);
            if (edit) {
                mw.top().app.registerChangedState(edit);
            }
            var url = res.src ? res.src : res;
            if (!url) return;
            url = url.toString();
            element.style.backgroundImage = null;
            //element.style.backgroundImage = `url('${url}')`

            mw.top().app.cssEditor.temp(element, 'background-image', `url('${url}')`);

            mw.app.liveEdit.play();
            dialog.remove();

            if (edit) {
                mw.top().app.registerChangedState(edit);
            }
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
            onResult: onResult,
            okLabel: mw.lang('Select image'),
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


    alignImage(element, align) {

        mw.app.registerUndoState(element);
        const flexOnParent = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['element']);
        if (!flexOnParent) {
            return;
        }

        if (flexOnParent.classList) {
            flexOnParent.classList.remove('d-flex', 'justify-content-center', 'justify-content-end', 'justify-content-start');
        }
        if (align == 'left') {
            flexOnParent.classList.add('d-flex', 'justify-content-start');
        } else if (align == 'center') {
            flexOnParent.classList.add('d-flex', 'justify-content-center');
        } else if (align == 'right') {
            flexOnParent.classList.add('d-flex', 'justify-content-end');
        } else if (align == 'clear') {
            flexOnParent.classList.remove('d-flex', 'justify-content-center', 'justify-content-end', 'justify-content-start');
        }

        mw.app.registerChangedState(element);
    }

    makeFreeDraggableElement(element) {
        var elementToMakeDraggable = mw.app.freeDraggableElementTools.getTargetNode(element)
        if (!elementToMakeDraggable) {
            return;

        }

        mw.app.freeDraggableElementManager.makeFreeDraggableElement(elementToMakeDraggable);
    }

    destroyFreeDraggableElement(element) {
        mw.app.freeDraggableElementManager.destroyFreeDraggableElement(element);
    }
}
