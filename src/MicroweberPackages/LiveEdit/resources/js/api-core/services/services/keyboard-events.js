import MicroweberBaseClass from "../containers/base-class.js";


export class KeyboardEvents extends MicroweberBaseClass {

    constructor() {
        super();
    }

    onRegister() {
        document.addEventListener('keydown', (e) => {
            if (e.key === "Enter" || e.keyCode === 13) {
                this.dispatch('enter', e);
            }
            if (e.key === "Escape" || e.keyCode === 27) {
                this.dispatch('escape', e);
            }
            if (e.key === "Backspace" || e.keyCode === 8) {
                this.dispatch('backspace', e);
            }
            if (e.key === "Delete" || e.keyCode === 46) {
                this.dispatch('delete', e);
            }
            if (e.ctrlKey && e.keyCode === 83) {
                this.dispatch('ctrl+s', e);
            }
            if (e.ctrlKey && e.keyCode === 90) {
                this.dispatch('ctrl+z', e);
            }
            if (e.ctrlKey && e.keyCode === 89) {
                this.dispatch('ctrl+y', e);
            }
        });
    }


}





