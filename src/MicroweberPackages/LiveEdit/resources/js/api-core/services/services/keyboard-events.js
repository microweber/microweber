import MicroweberBaseClass from "../containers/base-class.js";
import {KeyboardKeysHelper} from "./keyboard-keys-helper";


export class KeyboardEvents extends MicroweberBaseClass {

    keyboardKeysHelper = null;
    constructor() {
        super();
        this.keyboardKeysHelper = new KeyboardKeysHelper();
    }

    onRegister() {
        /*let selfKeyboardEvents = this;
        document.addEventListener('keydown', (e) => {


            if (e.key === "Enter" || e.keyCode === 13) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('enter', e);
                }
            }
            if (e.key === "Escape" || e.keyCode === 27) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('escape', e);
                }
            }
            if (e.key === "Backspace" || e.keyCode === 8) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('backspace', e);
                }
            }
            if (e.key === "Delete" || e.keyCode === 46) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('delete', e);
                }
            }
            if (e.ctrlKey && e.keyCode === 83) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('ctrl+s', e);
                }
            }
            if (e.ctrlKey && e.keyCode === 90) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('ctrl+z', e);
                }
            }
            if (e.ctrlKey && e.keyCode === 89) {
                if (selfKeyboardEvents.dispatch) {
                    selfKeyboardEvents.dispatch('ctrl+y', e);
                }
            }
        });*/

    }


}





