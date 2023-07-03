

import { init } from 'aos';
import { EditorHandles } from '../../../../ui/adapters/module-handle.js';
import {LiveEdit} from '../../../core/@live.js';


import {DomService} from '../../../core/classes/dom.js';
import liveeditCssDist from '../../../core/css/scss/liveedit.scss';
import {ModuleSettings} from "../../services/module-settings";
import {TemplateSettings} from "../../services/template-settings";


export const liveEditComponent = () => {
    const frame = mw.app.get('canvas').getFrame();
    const frameHolder = frame.parentElement;
    const doc = mw.app.get('canvas').getDocument();
    const link = doc.createElement('style');
    link.textContent = liveeditCssDist;

    doc.head.prepend(link);

    const liveEdit = new LiveEdit({
        root: doc.body,
        strict: false,
        mode: 'auto',
        document: doc
    });



    liveEdit.on('insertLayoutRequest', function(){
        mw.app.editor.dispatch('insertLayoutRequest', mw.app.get('liveEdit').handles.get('layout').getTarget());
    });


    mw.app.call('onLiveEditReady');

    mw.app.register('liveEdit', liveEdit);
    mw.app.register('state', mw.liveEditState);

    mw.app.register('editor', EditorHandles );

    mw.app.register('moduleSettings', ModuleSettings);

    mw.app.register('templateSettings', TemplateSettings);// don't remove this
    mw.app.registerUndoState = function(element){
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        if(edit) {
            if(edit.getAttribute('rel') && edit.getAttribute('field')) {
                mw.app.state.record({
                    target: edit,
                    value: edit.innerHTML
                });
            }
        }
    };
    mw.app.registerChange = function(element){
       // mw.app.state.state()[0].target
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        if(edit) {
            if(edit.getAttribute('rel') && edit.getAttribute('field')) {
                edit.classList.add('changed');
            } else {
                return mw.app.registerChange(edit.parentElement);
            }
        }
    };

    mw.app.registerChangedState = function(element){
        mw.app.registerChange(element);
        mw.app.registerUndoState(element);
    };



    const handleUndoRedo = ( data) => {
        if(data.active) {
            var target = data.active.target;
            if(typeof target === 'string'){
                target = doc.querySelector(data.active.target);
            }

            if(!data.active || (!target && !data.active.action)) {

                return;
            }

            if(data.active.action) {
                data.active.action();
            } else if(doc.body.contains(target)) {
                mw.element(target).html(data.active.value);
            } else{
                if(target.id) {
                    mw.element(doc.getElementById(target.id)).html(data.active.value);
                }
            }
            if(data.active.prev) {
                mw.$(data.active.prev).html(data.active.prevValue);
            }
        }
    }


    mw.app.state.on('change',  (data) => {

        handleUndoRedo(data)
    });





    let _inputTimeout = null;
    const initialState = new Map();
    const _inputUnavailable = ['INPUT', 'SELECT', 'TEXTAREA'];
    let _edit = null;

    const body = mw.app.canvas.getDocument().body;




    const handleInput = (e, initial) => {
        if(_inputUnavailable.indexOf(e.target.nodeName) === -1 && e.target.isContentEditable) {
            _edit = DomService.firstParentOrCurrentWithClass(e.target, 'edit');
            if(initial/* && !initialState.get(_edit)*/) {
                initialState.set(_edit, true);
                mw.app.state.record({
                    target: _edit,
                    value: _edit.innerHTML
                })
            }
            clearTimeout(_inputTimeout);
            _inputTimeout = setTimeout(() => {
                if(_edit) {
                    _edit.classList.add('changed');
                    mw.app.state.record({
                        target: _edit,
                        value: _edit.innerHTML
                    })
                }

            }, 200)
        }
    }

        body.addEventListener('input', e => {
            handleInput(e)
        });
        body.addEventListener('beforeinput', e => {
            handleInput(e, true)
        });

    }



