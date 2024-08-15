import MicroweberBaseClass from "../containers/base-class.js";


export class ThemeCssVarsEditor extends MicroweberBaseClass {
    constructor() {
        super();

    }

    applyThemeCSSVariablesFromText(cssVariablesText) {
        cssVariablesText = cssVariablesText.trim();
        var cssVariables = cssVariablesText.split(';');

        if (cssVariables && cssVariables.length > 0) {
            cssVariables.forEach(function (variableOriginal) {
                var variablePair = variableOriginal.split(':');

                if (variablePair.length === 2) {
                    var variableName = variablePair[0].trim();
                    var variableValue = variablePair[1].trim().replace(';', '');

                    var ActiveSelector = ':root';
                    var prop = variableName;
                    var value = variableValue;

                    mw.top().app.cssEditor.setPropertyForSelector(ActiveSelector, prop, value, false);

                }
            });
        }
    }

    //Retrieve all --root CSS variables
    getThemeCSSVariables() {

        var targetStylesheetIds = ["theme-style"];

        var cssVariables = this.getAllCSSVariables(targetStylesheetIds);
        return cssVariables;
    }

    getThemeCSSVariablesAsText() {
        var formattedText = '';
        var cssVariables = this.getThemeCSSVariables();

        if (cssVariables && cssVariables.length > 0) {
            cssVariables.forEach(function (variableOriginal) {
                var variableName = variableOriginal.replace('--', '');
                var variableValue = mw.top().app.cssEditor.getPropertyForSelector(':root', variableOriginal);
                formattedText += variableOriginal + ': ' + variableValue + ';\n';
            });
        }

        return formattedText;
    }

    getAllCSSVariables(targetStylesheetIds = null) {
        var canvasDocument = mw.top().app.canvas.getDocument();

        var allStyleSheets = Array.from(canvasDocument.styleSheets);
        var cssVariables = [];

        allStyleSheets.forEach(styleSheet => {


            var skip = false;
            if (targetStylesheetIds && targetStylesheetIds.length > 0) {
                skip = true;
            }

            if (targetStylesheetIds && styleSheet.ownerNode && targetStylesheetIds.includes(styleSheet.ownerNode.id)) {
                skip = false;
            }

            if (!skip) {
                try {
                    var rootRule = [...styleSheet.rules].find(rule => rule.selectorText === ":root");

                    if (rootRule) {
                        var declarations = rootRule.style;
                        for (var i = 0; i < declarations.length; i++) {
                            var property = declarations[i];
                            if (property.startsWith("--")) {
                                cssVariables.push(property);
                            }
                        }
                    }
                } catch (error) {
                    // Handle security exceptions for cross-origin stylesheets
                }
            }


        });


        return cssVariables;
    }

}

export default ThemeCssVarsEditor;
