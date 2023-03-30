import { layoutSelector } from "./layout-selector.js";
import { moduleSelector } from "./module-selector.js"

export const Commands = {
    cssEditor: function () {
        document.getElementById('css-editor-template').classList.toggle('active')
    },
    themeEditor: function () {
        document.getElementById('general-theme-settings').classList.toggle('active')
    },
    insertModule: function () {
        return moduleSelector();
    },
    insertLayout: function () {
        return layoutSelector();
    }
}