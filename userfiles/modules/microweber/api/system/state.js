import {State} from '../classes/state';

(function(){
    if(mw.liveEditState) return;
    mw.State = State
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = mw.$(mw.liveEditState);

    var ui = mw.$('<div class="mw-ui-btn-nav"></div>'),
        undo = document.createElement('span'),
        redo = document.createElement('span');
    undo.className = 'mw-ui-btn mw-ui-btn-medium';
    undo.innerHTML = '<span class="mw-icon-reply"></span>';
    redo.className = 'mw-ui-btn mw-ui-btn-medium';
    redo.innerHTML = '<span class="mw-icon-forward"></span>';

    undo.onclick = function(){
        mw.liveEditState.undo();
    };
    redo.onclick = function(){
        mw.liveEditState.redo();
    };

    ui.append(undo);
    ui.append(redo);

    mw.$(document).ready(function(){
        var idata = mw.liveEditState.eventData();

        mw.$(undo)[!idata.hasNext?'addClass':'removeClass']('disabled');
        mw.$(redo)[!idata.hasPrev?'addClass':'removeClass']('disabled');

        /*undo.disabled = !idata.hasNext;
        redo.disabled = !idata.hasPrev;*/

        var edits = document.querySelectorAll('.edit'), editstime = null;

        for ( var i = 0; i < edits.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(this, 'edit')) {
                edits[i].addEventListener('beforeinput', function (e) {
                    var sel = getSelection();
                    var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                     if(target) {
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                    }
                });
                edits[i].addEventListener('input', function (e) {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        if(!target) return;
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                        this.__initialRecord = false;
                });
            }
        }

        mw.$liveEditState.on('stateRecord', function(e, data){
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });
        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){

            if(data.active) {
                var target = data.active.target;
                if(typeof target === 'string'){
                    target = document.querySelector(data.active.target);
                }

                if(!data.active || (!target && !data.active.action)) {
                    mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
                    mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
                    return;
                }
                if(data.active.action) {
                    data.active.action();
                } else if(document.body.contains(target)) {
                    mw.$(target).html(data.active.value);
                } else{
                    if(target.id) {
                        mw.$(document.getElementById(target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
            }
            mw.drag.load_new_modules();
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });

        mw.$('#history_panel_toggle,#history_dd,.mw_editor_undo,.mw_editor_redo').remove();
        mw.$('.wysiwyg-cell-undo-redo').eq(0).prepend(ui);

        mw.element(document.body).on('keydown', function(e) {
            if( e.key )  {
                var key = e.key.toLowerCase();
                if (e.ctrlKey && key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    mw.liveEditState.undo();
                } else if ((e.ctrlKey && key === 'y') || (e.ctrlKey && e.shiftKey && key === 'z')) {
                    e.preventDefault();
                    mw.liveEditState.redo();
                }
            }

        });
    });
})();


