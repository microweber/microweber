mw.liveedit.editFields = {
    handleKeydown: function() {
        mw.$('.edit').on('keydown', function(e){
            var istab = (e.which || e.keyCode) === 9,
                isShiftTab = istab && e.shiftKey,
                tabOnly = istab && !e.shiftKey,
                target;

            if(istab){
                e.preventDefault();
                target = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
            }
            if(tabOnly){
                if(target.nodeName === 'LI'){
                    var parent = target.parentNode;
                    if(parent.children[0] !== target){
                        var prev = target.previousElementSibling;
                        var ul = document.createElement(parent.nodeName);
                        ul.appendChild(target);
                        prev.appendChild(ul)
                    }
                }
                else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                    target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    nexttd = target.nextElementSibling;
                    if(!!nexttd){
                        mw.wysiwyg.cursorToElement(nexttd, 'start');
                    }
                    else{
                        var nextRow = target.parentNode.nextElementSibling;
                        if(!!nextRow){
                            mw.wysiwyg.cursorToElement(nextRow.querySelector('td'), 'start');
                        }
                    }
                }
                else{
                    mw.wysiwyg.insert_html('&nbsp;&nbsp;');
                }

            }
            else if(isShiftTab){
                if(target.nodeName === 'LI'){
                    var parent = target.parentNode;
                    var isSub = parent.parentNode.nodeName === 'LI';
                    if(isSub){
                        var split = mw.wysiwyg.listSplit(parent, mw.$('li', parent).index(target));

                        var parentLi = parent.parentNode;
                        mw.$(parentLi).after(split.middle);
                        if(!!split.top){
                            mw.$(parentLi).append(split.top);
                        }
                        if(!!split.bottom){
                            mw.$(split.middle).append(split.bottom);
                        }

                        mw.$(parent).remove();
                    }
                }
                else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                    var target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    var nexttd = target.previousElementSibling;
                    if(!!nexttd){
                        mw.wysiwyg.cursorToElement(nexttd, 'start');
                    }
                    else{
                        var nextRow = target.parentNode.previousElementSibling;
                        if(!!nextRow){
                            mw.wysiwyg.cursorToElement(nextRow.querySelector('td:last-child'), 'start');
                        }
                    }
                }
                else{
                    var range = getSelection().getRangeAt(0);
                    clone = range.cloneRange();
                    clone.setStart(range.startContainer, range.startOffset - 2);
                    clone.setEnd(range.startContainer, range.startOffset);
                    var nv = clone.cloneContents().firstChild.nodeValue;
                    var nvcheck = nv.replace(/\s/g,'');
                    if( nvcheck === '' ){
                        clone.deleteContents();
                    }
                }
            }
        });
    }
}
