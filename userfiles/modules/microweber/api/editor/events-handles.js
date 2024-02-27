class MWEditorEventHandles {
    constructor(scope) {
        this.scope = scope;
    }

    normalize () {
        var sel = this.scope.getSelection();
        if(!sel.rangeCount) {
            return;
        }

        let cmn = this.scope.api.elementNode(sel.getRangeAt(0).commonAncestorContainer);
        if(cmn) {
            cmn = mw.tools.firstBlockLevel(cmn)
        }

        if(!cmn) {
            return;
        }

        cmn.normalize();

        let toUnwrap = [];

        const nodes = cmn.querySelectorAll('span,strong,b,i,em,font');

        const normalizeStyles = ['color'];

        nodes.forEach(node => {
            normalizeStyles.forEach(st => {
                if(node.style[st]) {
                    if(getComputedStyle(node.parentNode)[st] === node.style[st]) {
                        node.style[st] = ''
                    }
                }
            });
            const style = node.getAttribute('style');
            if(!style || !style.trim()) {
                node.removeAttribute('style');
            }
            if(node.nodeName === 'SPAN' && node.attributes.length === 0) {
                toUnwrap.push(node)
            } else if(node.firstChild === node.lastChild
                && !!node.lastChild
                && node.nodeName === node.lastChild.nodeName
                && style === node.lastChild.getAttribute('style') && !node.id && !node.lastChild.id){
                    toUnwrap.push(node)
            }  else if(node.nodeName === node.parentNode.nodeName && node.attributes.length === 0){
                    toUnwrap.push(node)
            }
        });

        while (toUnwrap[0]) {
            if(!toUnwrap[0].ownerDocument) {
                toUnwrap.splice(0, 1);
            } else {
                toUnwrap[0].replaceWith(...toUnwrap[0].childNodes);
                toUnwrap.splice(0, 1);
            }
        }


        var all =  cmn.parentNode.querySelectorAll('*[style*="var"]');

        all.forEach(node => {
            if (node.style) {
                if (node.isContentEditable) {
                    [...node.style].filter(prop => node.style[prop].includes('var(')).forEach(prop => node.style.removeProperty(prop))
                }
            }
        });

    }



     backSpace(e) {



        var sel = this.scope.getSelection();
        var mergeNodeNames = ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P'];
        const getParentHolder = (focusNode) => {
            var prev = null;
            while (focusNode && focusNode.parentNode) {
                if (prev && focusNode.firstChild !== prev) {
                    return null;
                }
                if (focusNode.nodeType === 1 && mergeNodeNames.indexOf(focusNode.nodeName) !== -1) {
                    return focusNode
                }
                prev = focusNode;
                focusNode = focusNode.parentNode;
            }
            return null;
        }

        const deepLastChild = node => {
            if(!node) {
                return null
            }

            var unsupported = ['IMG', 'BR', 'UL', 'OL', 'DL'];

            var children = Array
            .from(node.childNodes)
            .filter(node => unsupported.indexOf(node.nodeName) === -1)

            let child = children[children.length - 1];
            if(!child) {
                return node;
            }
            if(child) {
                return deepLastChild(child)
            }
        }
        const deepFirstChild = node => {
            if(!node) {
                return null
            }

            var unsupported = ['IMG', 'BR', 'UL', 'OL', 'DL'];

            var children = Array
            .from(node.children)
            .filter(node => unsupported.indexOf(node.nodeName) === -1)

            let child = children[0];
            if(!child) {
                return node;
            }
            if(child) {
                return deepLastChild(child)
            }
        }

        if(sel.type === 'Caret') {

            if(sel.focusOffset === 0) {
                var parent = getParentHolder(sel.focusNode);

                if (parent) {
                    const target = deepLastChild(parent.previousElementSibling);




                    if(target.nodeType !== 1) {
                        setTimeout(() => this.normalize(), 10)
                        return
                    }

                    if(target && sel.focusNode.nodeName !== target.nodeName) {
                        this.scope.api.setCursorAtEnd(target);
                        const edit = mw.tools.firstParentOrCurrentWithClass(target, 'edit') || this.scope.$editArea[0];
                        this.scope.state.record({

                            target: edit,
                            value: edit.innerHTML
                        });



                        if(parent.querySelector(target.nodeName) === null) {
                            while (parent.firstChild) {

                                target.appendChild(parent.firstChild)
                            }
                        } else {
                            let ctarget = target
                            while (parent.firstChild) {
                                let curr = parent.firstChild
                                ctarget.after(curr)
                                ctarget = (curr)
                            }
                        }


                        parent.remove();
                        this.scope.state.record({

                            target: edit,
                            value: edit.innerHTML
                        });
                        e.preventDefault();
                    }
                }
            }
        } else if(sel.type === 'Range') {

        }



        this.normalize()

     }

     enter(e) {

        let focusNode = this.scope.api.elementNode(this.scope.getSelection().focusNode);
        let focusActualTarget =this.scope.getActualTarget(focusNode)

        var isSafeMode = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(focusNode, ['safe-mode', 'regular-mode']);

        focusNode.appendChild(document.createTextNode('\u200B'));
        focusNode.focus();
        focusNode.appendChild(document.createTextNode('\u200B'));

        if(!isSafeMode) {


            if(focusNode && focusNode.contentEditable === 'true' && focusNode.parentNode) {


                var pc = focusNode.parentNode.contentEditable;
                focusActualTarget.contentEditable  =  true;
                focusNode.contentEditabdle  =  'inherit';
                focusNode.focus();


                clearTimeout(focusNode.__etimeout);
                focusNode.__etimeout = setTimeout(() => {
                    focusNode.parentNode.contentEditable  =  pc
                    focusNode.contentEditable  =  true;
                    focusNode.focus();

                },  20)

            }

            setTimeout(focusNode => {

                const clean = focusNode => {
                    var parent = focusNode.parentNode;
                    if(parent && parent.children && parent.children.length > 1) {
                        Array.from(parent.children).forEach(node => {
                            if(node && node.id && node.nextElementSibling && node.nextElementSibling.id === node.id) {
                                node.nextElementSibling.id = mw.id();
                                node.nextElementSibling.querySelectorAll('[id]').forEach(node => {
                                    node.id = mw.id();
                                })
                            }
                        })
                    }
                    focusNode.childNodes.forEach(node => {
                        if(node.nodeType === 3 && node.nodeValue === '\u200B') {
                            node.remove()
                        }
                    })
                    if(focusNode.nextElementSibling) {
                        focusNode.nextElementSibling.childNodes.forEach(node => {
                            if(node.nodeType === 3 &&  node.nodeValue === '\u200B') {
                                node.remove()
                            }
                        })
                    }
                }

                if(focusNode) {
                    clean(focusNode)
                }
                if(focusActualTarget) {
                    clean(focusActualTarget)
                }
            },  30, focusNode)


        } else {

            const isLi = mw.tools.firstParentOrCurrentWithTag(focusNode, 'li');
            const edit = mw.tools.firstParentOrCurrentWithClass(focusNode, 'edit') ||this.scope.$editArea[0];


            if (!isLi || (isLi && event.shiftKey)) {

               this.scope.state.record({

                    target: edit,
                    value: edit.innerHTML
                });

                var sel = this.scope.api.getSelection() ;
                var range = sel.getRangeAt(0);
                var br = range.commonAncestorContainer.ownerDocument.createElement('br');

                range.insertNode(br);
                range = range.cloneRange();

                if(!br.nextSibling || !br.nextSibling.nodeValue) {
                    br.after(document.createTextNode('\u200B'))
                }
                range.selectNode ( br );
                range.collapse(false);


                sel.removeAllRanges();
                sel.addRange(range);


                e.preventDefault();
               this.scope.state.record({
                    target: edit,
                    value: edit.innerHTML
                });
                return;
            }
        }
    }


}
