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
        if(el.firstElementChild) {
            var firstChild = el.firstElementChild;

            var isCloneableWithImageAsFirstChild = el.classList && el.classList.contains('cloneable') && firstChild && firstChild.nodeName === 'IMG';
            var isCloneableWithImageAsFirstChildAsBg = el.classList && el.classList.contains('cloneable') && firstChild && firstChild.classList && firstChild.classList.contains('img-as-background') &&firstChild.firstElementChild && firstChild.firstElementChild.nodeName === 'IMG'

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

    deleteElement(el) {


        if(el.nodeName === 'IMG') {
            const $el = mw.element(el)
            const off = $el.offset()
            const display = $el.css('display')
            const newNode = mw.tools.setTag(el, 'div');
            newNode.innerHTML = `<span class="mw-img-placeholder-description">${mw.lang(`Click to set image`)}</span>`;
            newNode.style.display = display !== 'inline' ? display : 'inline-block';
            newNode.style.width = off.width + 'px';
            newNode.style.height = off.height + 'px';
            newNode.className = 'no-element mw-img-placeholder';
            const handle = function (e){
                e.preventDefault();
                e.stopPropagation();
                mw.filePickerDialog( (url) => {
                    if(url) {
                        $(newNode).replaceWith(`<img src="${url}">`);
                    }

                });
            }


            function handleDrop(e) {
 
                e.preventDefault();
                e.stopPropagation();
                let file;
              
                if (e.dataTransfer.items) {
                    file = [...e.dataTransfer.items].find((item, i) => {
                        if (item.kind === "file") {
                            return item.getAsFile();
                        }
                    });
                  
                } else {
                  file = [...e.dataTransfer.files][0];
                }

                if(file) {
                    mw.spinner({element: newNode, decorate: true});
                    mw.uploader().uploadFile(file, function(res){
                        console.log(res)
                        $(newNode).replaceWith(`<img src="${res.url}">`);
                        
                    })
                }
              }

            const del = document.createElement('span');
            del.className = 'mw-img-placeholder-delete';
            del.innerHTML = this.handleIcons.icon('delete');
            newNode.append(del);

            const handleDelete = e => {
                e.stopPropagation();
                newNode.remove();
            }

            del.addEventListener('touchstart', handleDelete);
            del.addEventListener('mousedown', handleDelete);


            newNode.addEventListener('touchstart', handle);
            newNode.addEventListener('mousedown', handle);
            newNode.addEventListener('drop', handleDrop)
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
        if(closestLink){
            var elementForUndo = closestLink.parentNode || closestLink;
            mw.app.registerUndoState(elementForUndo)

            el = closestLink;

            el.removeAttribute('href');
            el.removeAttribute('target');

            var  shouldUnWrap = true;
            if(shouldUnWrap){
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
        mw.app.registerChangedState(el);
        this.proto.elementHandle.set(el);
    }


}
