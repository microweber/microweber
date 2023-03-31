import MicroweberBaseClass from "../containers/base-class.js";


export class KeyboardEvents extends MicroweberBaseClass {
    is = {
        comma: function (e) {
            return e.keyCode === 188;
        },
        enter: function (e) {
            return e.key === "Enter" || e.keyCode === 13;
        },
        escape: function (e) {
            return e.key === "Escape" || e.keyCode === 27;
        },
        backSpace: function (e) {
            return e.key === "Backspace" || e.keyCode === 8;
        },
        delete: function (e) {
            return e.key === "Delete" || e.keyCode === 46;
        }
    }

    constructor() {
        super();
    }

    onRegister() {
        document.addEventListener('keydown', (e) => {

            if(this.is.escape(e)) {
                alert ('escape');
            }
            this.dispatch('keydown', e);
        });
    }


}





