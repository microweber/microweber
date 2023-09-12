import { EditorHandles } from '../../../../ui/adapters/module-handle.js';
import {LiveEdit} from '../../../core/@live.js';
import {DomService} from '../../../core/classes/dom.js';
import liveeditCssDist from '../../../core/css/scss/liveedit.scss';
import {ModuleSettings} from "../../services/module-settings";
import {TemplateSettings} from "../../services/template-settings";
import {LiveEditSpacer} from "./live-edit-spacer";
import {LiveEditUndoRedoHandler} from   "./live-edit-undo-redo-handler";
import LiveEditImageDialog from "./live-edit-image-dialog";
import {LiveEditLayoutBackground} from "./live-edit-layout-background";
import LiveEditFontManager from "./live-edit-font-manager";


export const liveEditComponent = () => {
    const frame = mw.app.canvas.getFrame();
    const frameHolder = frame.parentElement;
    const doc = mw.app.canvas.getDocument();
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
        mw.app.editor.dispatch('insertLayoutRequest', mw.app.liveEdit.handles.get('layout').getTarget());
    });

    liveEdit.on('insertLayoutRequestOnTop', function(){
        mw.app.editor.dispatch('insertLayoutRequestOnTop', mw.app.liveEdit.handles.get('layout').getTarget());
    });

    mw.app.on('moduleReloaded', function(){
        const layoutTarget = mw.top().app.liveEdit.handles.get('layout').getTarget();

        console.log(1, layoutTarget)
         
        if(layoutTarget && !layoutTarget.parentElement) {
            
            mw.top().app.liveEdit.handles.get('layout').set(null);
            mw.top().app.liveEdit.handles.get('layout').set(mw.app.canvas.getDocument().getElementById(layoutTarget.id));

            console.log(mw.app.canvas.getDocument().getElementById(layoutTarget.id))
        }
    });

    liveEdit.on('insertLayoutRequestOnBottom', function(){
        mw.app.editor.dispatch('insertLayoutRequestOnBottom', mw.app.liveEdit.handles.get('layout').getTarget());
    });
    mw.app.templateSettings = new TemplateSettings();


    mw.app.liveEdit =liveEdit;
    mw.app.editor = new EditorHandles();
    mw.app.moduleSettings = new ModuleSettings();

 //   mw.app.register('liveEdit', liveEdit);
    mw.app.register('state', mw.liveEditState);

  //  mw.app.state =mw.liveEditState;
    mw.app.editImageDialog =  new LiveEditImageDialog();
    mw.app.layoutBackground =  new LiveEditLayoutBackground();

    mw.app.fontManager =  new LiveEditFontManager();



    mw.app.spacer = new LiveEditSpacer();
    mw.app.undoHandler = new LiveEditUndoRedoHandler();



    mw.app.registerUndoState = function(element){
        return mw.app.undoHandler.registerUndoState(element);
    };
    mw.app.registerChange = function(element){
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        if(edit) {
            if(edit.getAttribute('rel') && edit.getAttribute('field')) {
                edit.classList.add('changed');
                mw.app.dispatch('editChanged', edit);

            } else {
                return mw.app.registerChange(edit.parentElement);
            }
        }
    };
    mw.app.registerAskUserToStay = function (toStay = true) {
        var liveEditIframe = (mw.app.canvas.getWindow());
        if (liveEditIframe
            && liveEditIframe.mw) {
            liveEditIframe.mw.askusertostay = toStay;
        }
    };
    mw.app.registerChangedState = function(element){
        mw.app.registerChange(element);
        mw.app.registerUndoState(element);
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
                if(_edit) {
                    _edit.classList.add('changed');
                    mw.app.state.record({
                        target: _edit,
                        value: _edit.innerHTML
                    })
                    mw.app.registerAskUserToStay(e.target);
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


     mw.app.dispatch('onLiveEditReady');

    }



