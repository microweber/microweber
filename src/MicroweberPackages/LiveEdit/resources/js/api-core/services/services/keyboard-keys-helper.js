import MicroweberBaseClass from "../containers/base-class.js";


export class KeyboardKeysHelper extends MicroweberBaseClass {

    constructor() {
        super();
    }

    isKeyNonInteractive(key) {
        const nonInteractiveKeys = [
            { key: "F1", keyCode: 112 },
            { key: "F2", keyCode: 113 },
            { key: "F3", keyCode: 114 },
            { key: "F4", keyCode: 115 },
            { key: "F5", keyCode: 116 },
            { key: "F6", keyCode: 117 },
            { key: "F7", keyCode: 118 },
            { key: "F8", keyCode: 119 },
            { key: "F9", keyCode: 120 },
            { key: "F10", keyCode: 121 },
            { key: "F11", keyCode: 122 },
            { key: "F12", keyCode: 123 },
            { key: "Arrow Up", keyCode: 38 },
            { key: "Arrow Down", keyCode: 40 },
            { key: "Arrow Left", keyCode: 37 },
            { key: "Arrow Right", keyCode: 39 },
            { key: "Enter/Return", keyCode: 13 },
            { key: "Tab", keyCode: 9 },
            { key: "Escape", keyCode: 27 },
            { key: "Spacebar", keyCode: 32 },
            { key: "Backspace", keyCode: 8 },
            { key: "Shift", keyCode: 16 },
            { key: "Control (Ctrl)", keyCode: 17 },
            { key: "Alt", keyCode: 18 },
            { key: "Caps Lock", keyCode: 20 },
            { key: "Windows Key", keyCode: 91 },
            { key: "Command (Mac)", keyCode: 91 },
            { key: "Option (Mac)", keyCode: 18 }
        ];

        for (const item of nonInteractiveKeys) {
            if (item.key === key) {
                return true;
            }
            if (item.keyCode === key) {
                return true;
            }
        }
        return false;
    }
}






