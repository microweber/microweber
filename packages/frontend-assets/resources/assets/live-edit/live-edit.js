import { EditorHandles } from '../ui/adapters/module-handle.js';
import {LiveEdit} from '../api-core/core/@live.js';
import {DomService} from '../api-core/core/classes/dom.js';
//import liveeditCssDist from '../../../core/css/scss/liveedit.scss';
import {ModuleSettings} from "../api-core/services/services/module-settings.js";
import {TemplateSettings} from "../api-core/services/services/template-settings.js";
import {WyswygEditor} from "../api-core/services/services/wyswyg-editor.js";
import {LiveEditSpacer} from "./live-edit-spacer.js";
import {LiveEditUndoRedoHandler} from   "./live-edit-undo-redo-handler.js";
import LiveEditImageDialog from "./live-edit-image-dialog.js";
import {LiveEditLayoutBackground} from "./live-edit-layout-background.js";
import LiveEditFontManager from "./live-edit-font-manager.js";
import LiveEditWidgetsService from "./live-edit-widgets-service.js";
import { FreeDraggableElementManager } from '../api-core/core/handles-content/free-draggable-element-manager.js';


export const liveEditComponent = () => {

    const doc = mw.app.canvas.getDocument();


    const liveEdit = new LiveEdit({
        root: doc.body,
        strict: false,
        mode: 'auto',
        document: doc
    });

    liveEdit.on('insertLayoutRequest', function(){
        mw.app.editor.dispatch('insertLayoutRequest', mw.app.liveEdit.handles.get('layout').getTarget());
    });

    liveEdit.on('insertLayoutRequestOnTop', function(){
        mw.app.editor.dispatch('insertLayoutRequestOnTop', mw.app.liveEdit.handles.get('layout').getTarget());
    });

    mw.app.on('moduleReloaded', function(){
        const layoutTarget = mw.top().app.liveEdit.handles.get('layout').getTarget();
        setTimeout(function(){
            if(layoutTarget) {
                mw.top().app.liveEdit.handles.get('layout').set(null);
                mw.top().app.liveEdit.handles.get('layout').set(mw.app.canvas.getDocument().getElementById(layoutTarget.id));
            }
        }, 1200)
    });

    liveEdit.on('insertLayoutRequestOnBottom', function(){
        mw.app.editor.dispatch('insertLayoutRequestOnBottom', mw.app.liveEdit.handles.get('layout').getTarget());
    });
    mw.app.templateSettings = new TemplateSettings();


    mw.app.liveEdit = liveEdit;
    mw.app.liveEditWidgets = new LiveEditWidgetsService();

    mw.app.editor = new EditorHandles();
    mw.app.moduleSettings = new ModuleSettings();

 //   mw.app.register('liveEdit', liveEdit);
    mw.app.register('state', mw.liveEditState);

  //  mw.app.state =mw.liveEditState;
    mw.app.editImageDialog =  new LiveEditImageDialog();
    mw.app.layoutBackground =  new LiveEditLayoutBackground();
    mw.app.freeDraggableElementManager =  new FreeDraggableElementManager();
    mw.app.freeDraggableElementTools =  FreeDraggableElementManager;
    mw.app.wyswygEditor =  new WyswygEditor();

    mw.app.fontManager =  new LiveEditFontManager();



    mw.app.spacer = new LiveEditSpacer();
    mw.app.undoHandler = new LiveEditUndoRedoHandler();



    mw.app.registerUndoState = function(element, isNow = false){
        let method = 'registerUndoState';
        if(isNow) {
            method = 'registerUndoStateNow';
        }
        return mw.app.undoHandler[method](element);
    };
    mw.app.registerChange = function(element){
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        if(edit) {
            mw.tools.foreachParents(edit, function () {
                if (mw.tools.hasClass(this, 'edit')) {
                    mw.tools.addClass(this, 'changed')
                }
            });
         //   var editParents = mw.tools.firstParentOrCurrentWithClass(edit, 'edit');


            if(edit.getAttribute('rel') && edit.getAttribute('field')) {
                edit.classList.add('changed');
                mw.app.dispatch('editChanged', edit);

            } else {
                return mw.app.registerChange(edit.parentElement);
            }
        }
        if(mw.app.liveEdit) {

            // mw.app.liveEdit.handles.reposition();
        }
    };
    mw.app.registerAskUserToStay = function (toStay = true) {
        var liveEditIframe = (mw.app.canvas.getWindow());
        if (liveEditIframe
            && liveEditIframe.mw) {
            liveEditIframe.mw.askusertostay = toStay;
        }
    };
    mw.app.registerSyncAction = function(element, isNow){
        mw.app.registerChangedState(element, isNow);
        setTimeout(function(){
            mw.app.registerChangedState(element, isNow);
        }, 110);
    };

    mw.app.registerChangedState = function(element, isNow){




        mw.app.registerChange(element);
        mw.app.registerUndoState(element, isNow);
        mw.app.registerAskUserToStay(true);


    };







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
                mw.app.registerAskUserToStay(e.target);
            }
            clearTimeout(_inputTimeout);
            _inputTimeout = setTimeout(() => {
                if(_edit && _edit.classList) {
                    _edit.classList.add('changed');
                    mw.app.state.record({
                        target: _edit,
                        value: _edit.innerHTML
                    })
                    mw.app.registerAskUserToStay(e.target);
                }

            }, 1200)
        }
    }

        body.addEventListener('input', e => {
            handleInput(e)
        });
        body.addEventListener('beforeinput', e => {
            handleInput(e, true)
        });


        mw.app.canvas.getDocument().querySelectorAll('[data-layout-container]').forEach(node => {
            liveEdit.layoutHandleContent.layoutActions.afterLayoutChange(node)
        })


        mw.app.on('$resizeStop', () => {
            mw.top().app.liveEdit.layoutHandleContent.positionButtons(mw.top().app.liveEdit.handles.get('layout').getTarget())
            mw.top().app.liveEdit.layoutHandleContent.plusTop.hide()
            mw.top().app.liveEdit.layoutHandleContent.plusBottom.hide()
        })


     mw.app.dispatch('onLiveEditReady');

    }



