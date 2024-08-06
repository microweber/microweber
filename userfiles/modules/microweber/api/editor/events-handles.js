class MWEditorEventHandles {
    constructor(scope) {
        this.scope = scope;
    }


    static isVoid(element) {
        const voidElements = ['AREA', 'BASE', 'BR', 'COL', 'COMMAND', 'EMBED', 'HR', 'IMG', 'INPUT', 'KEYGEN', 'LINK', 'META', 'PARAM', 'SOURCE', 'TRACK', 'WBR'];
        var unsupported = ['IMG', 'UL', 'OL', 'DL', ...voidElements];
        return unsupported.indexOf(element.nodeName) !== -1;
    }

    insertBR(e, edit) {
        if (edit && edit.ownerDocument) {

            this.scope.state.record({

                target: edit,
                value: edit.innerHTML
            });
        }
        var sel = this.scope.api.getSelection() ;
        var range = sel.getRangeAt(0);
        var br = range.commonAncestorContainer.ownerDocument.createElement('br');

        range.insertNode(br);
        range = range.cloneRange();

        console.log(1)

        if(!br.nextSibling || !br.nextSibling.nodeValue) {
            console.log(2)
            br.after(document.createTextNode('\u200B'))
        }
        range.selectNode ( br );
        range.collapse(false);


        sel.removeAllRanges();
        sel.addRange(range);


        e.preventDefault();

        if(edit && edit.ownerDocument) {
            this.scope.state.record({
                target: edit,
                value: edit.innerHTML
            });
            edit.classList.add('changed')
        }
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

            if(!node || MWEditorEventHandles.isVoid(node)) {
                return null
            }


            var children = Array
            .from(node.childNodes)
            .filter(node => !MWEditorEventHandles.isVoid(node))

            let child = children[children.length - 1];
            if(!child) {
                const isVoid = MWEditorEventHandles.isVoid(node)

                return isVoid ? null : node;
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




                    if(target && target.nodeType !== 1) {

                        setTimeout(() => this.scope.api.normalizeStyles(), 10)

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



        setTimeout(() => this.scope.api.normalizeStyles(), 110)

     }

     enter(e) {

        if(!mw.top().app || !mw.top().app.canvas ||  !mw.top().app.canvas.getDocument()        ) {
            return;
        }
        if(e && e.shiftKey) {
            return;
        }

        let focusNode = this.scope.api.elementNode(this.scope.getSelection().focusNode);
        let focusActualTarget = this.scope.getActualTarget(focusNode);

        const isNoclone = mw.tools.hasAnyOfClasses(focusActualTarget, ['col', 'row', 'mw-col', 'mw-row']);



        if(isNoclone) {
            const canHasBR = mw.tools.hasAnyOfClasses(focusActualTarget, ['col',  'mw-col', ]);
            const edit = mw.tools.firstParentOrCurrentWithClass(focusActualTarget, 'edit') ||this.scope.$editArea[0];
            if(canHasBR) {
                this.insertBR(e, edit);
            }
            return;
        }

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


            if (!isLi || (isLi && e.shiftKey)) {

               this.insertBR(e);
                return;
            }
        }
    }


}
