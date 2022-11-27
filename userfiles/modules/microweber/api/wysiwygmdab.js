
var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
};

mw.wysiwyg._manageDeleteAndBackspaceInSafeMode = {
    emptyNode: function (event, node, sel, range) {
        if(!canDestroy(node)) {
            return;
        }
        var todelete = node;
        if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
            todelete = node.parentNode;
        }
        var transfer, transferPosition;
        if (mw.event.is.delete(event)) {
            transfer = todelete.nextElementSibling;
            transferPosition = 'start';
        } else {
            transfer = todelete.previousElementSibling;
            transferPosition = 'end';
        }
        var parent = todelete.parentNode;
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
        $(todelete).remove();
        if(transfer && mw.tools.isEditable(transfer)) {
            setTimeout(function () {
                mw.wysiwyg.cursorToElement(transfer, transferPosition);
            });
        }
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
    },
    nodeBoundaries: function (event, node, sel, range) {
        var isStart = range.startOffset === 0 || !((sel.anchorNode.data || '').substring(0, range.startOffset).replace(/\s/g, ''));
        var curr, content;
        if(mw.event.is.backSpace(event) && isStart && range.collapsed){ // is at the beginning
            curr = node;
            if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                curr = node.parentNode;
            }
            var prev = curr.previousElementSibling;
            if(prev && prev.nodeName === node.nodeName && canDestroy(node)) {
                content = node.innerHTML;
                mw.wysiwyg.cursorToElement(prev, 'end');
                prev.appendChild(range.createContextualFragment(content));
                $(curr).remove();
            }
        } else if(mw.event.is.delete(event)
            && range.collapsed
            && range.startOffset === sel.anchorNode.data.replace(/\s*$/,'').length // is at the end
            && canDestroy(node)){
            curr = node;
            if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                curr = node.parentNode;
            }
            var next = curr.nextElementSibling, deleteParent;
            if(mw.tools.hasAnyOfClasses(next, ['text', 'title'])){
                next = next.firstElementChild;
                deleteParent = true;
            }
            if(next && next.nodeName === curr.nodeName) {
                content = next.innerHTML;
                setTimeout(function(){
                    var parent = deleteParent ? next.parentNode.parentNode : next.parentNode;
                    mw.liveEditState.actionRecord(function() {
                            return {
                                target: parent,
                                value: parent.innerHTML
                            };
                        }, function () {
                            curr.append(range.createContextualFragment(content));
                        }
                    );
                });
            }
        }
    }
};
mw.wysiwyg.manageDeleteAndBackspaceInSafeMode = function (event, sel) {


    var node = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
    var range = sel.getRangeAt(0);
    if(!node.innerText.replace(/\s/gi, '')){
        mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.emptyNode(event, node, sel, range);
        return false;
    }
    // mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
    return true;
};
mw.wysiwyg.manageDeleteAndBackspace = function (event, sel) {
    if (!mw.settings.liveEdit && self === top) {
        return;
    }

    if (mw.event.is.delete(event) || mw.event.is.backSpace(event)) {
        if(!sel.rangeCount) return;
        var r = sel.getRangeAt(0);
        var isSafe = mw.wysiwyg.isSafeMode();

        if(isSafe) {
            return mw.wysiwyg.manageDeleteAndBackspaceInSafeMode(event, sel);
        }

        if (!mw.settings.liveEdit) {
            return true;
        }
        var nextNode = null, nextchar, nextnextchar, nextel;


            if (mw.event.is.delete(event)) {
                nextchar = sel.focusNode.textContent.charAt(sel.focusOffset);
                nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset + 1);
                nextel = sel.focusNode.nextSibling || sel.focusNode.nextElementSibling;

            } else {
                nextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 1);
                nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 2);
                nextel = sel.focusNode.previouSibling || sel.focusNode.previousElementSibling;

            }


            if ((nextchar === ' ' || /\r|\n/.exec(nextchar) !== null) && sel.focusNode.nodeType === 3 && !nextnextchar) {
                event.preventDefault();
                return false;
            }


            if (nextnextchar === '') {


                if (nextchar.replace(/\s/g, '') === '' && r.collapsed) {

                    if (nextel && !mw.ea.helpers.isBlockLevel(nextel) && ( typeof nextel.className === 'undefined' || !nextel.className.trim())) {
                        return true;
                    }
                    else if (nextel && nextel.nodeName !== 'BR') {
                        if (sel.focusNode.nodeName === 'P') {
                            if (event.keyCode === 46) {
                                if (sel.focusNode.nextElementSibling.nodeName === 'P') {
                                    return true;
                                }
                            }
                            if (event.keyCode === 8) {

                                if (sel.focusNode.previousElementSibling.nodeName === 'P') {
                                    return true;
                                }
                            }
                        }
                        event.preventDefault();
                        return false;
                    }

                }
                else if ((focus.previousElementSibling === null && rootfocus.previousElementSibling === null) && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])) {
                    return false;
                }
                else {

                }
            }
            if (nextchar == '') {


                //continue check nodes
                if (mw.event.is.delete(event)) {
                    nextNode = mw.wysiwyg.merge.getNext(sel.focusNode);
                }
                if (mw.event.is.backSpace(event)) {
                    nextNode = mw.wysiwyg.merge.getPrev(sel.focusNode);
                }
                if (mw.wysiwyg.merge.alwaysMergeable(nextNode)) {
                    return true;
                }

                var nonbr = mw.wysiwyg.merge.isInNonbreakable(nextNode);
                if (nonbr) {
                    event.preventDefault();
                    return false;
                }

                if (nextNode.nodeValue == '') {

                }
                if (nextNode !== null && mw.wysiwyg.merge.isMergeable(nextNode)) {
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }
                else {
                    event.preventDefault()
                }
                //  }
                if (nextNode === null) {
                    nextNode = sel.focusNode.parentNode.nextSibling;
                    if (!mw.wysiwyg.merge.isMergeable(nextNode)) {
                        event.preventDefault();
                    }
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }

            } else {

            }


    }

    return true;
};
