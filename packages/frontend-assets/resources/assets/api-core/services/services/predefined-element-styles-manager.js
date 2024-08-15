import axios from "axios";


export default class PredefinedElementStylesManager {

    getPredefinedClasses() {
        var predefinedClasses = [
            //   'one'
        ];

        var classesFromDocument = this.getPredefinedElementClassesFromDocument();
        if (classesFromDocument.length > 0) {
            for (var i = 0; i < classesFromDocument.length; i++) {
                if (predefinedClasses.indexOf(classesFromDocument[i]) === -1) {
                    predefinedClasses.push(classesFromDocument[i]);
                }
            }
        }

        if(predefinedClasses.length > 0){
            //add default class 1st to reset
            predefinedClasses.unshift('predefined-style-none');
        }


        return predefinedClasses;
    }

    getPredefinedStylesScreenshotUrls() {
        var apiUrlForSkinPrefeniedStylesPrevew = mw.settings.api_url + 'predefined_element_styles_get_previews';

        return axios.get(apiUrlForSkinPrefeniedStylesPrevew).then(function (response) {
            return response.data;
        });

    }

    getPredefinedClassesVaribles(predefinedClasses) {
        if (predefinedClasses.length == 0) {
            return [];
        }

        var cssVariables = {};
        var stylesheets = this.getPredefinedElementStylsheetsFromDocument();
        for (var i = 0; i < predefinedClasses.length; i++) {
            var className = predefinedClasses[i];
            var variables = this.getVariablesFromClass(stylesheets, className);

            //merge variables
            if (cssVariables[className]) {
                for (const key in cssVariables[className]) {
                    if (cssVariables[className].hasOwnProperty(key)) {
                        const element = cssVariables[className][key];
                        variables[key] = element;
                    }
                }
            } else {
                cssVariables[className] = variables;
            }

            //   cssVariables[className] = variables;
        }
        return cssVariables;

    }

    getPredefinedElementClassesFromDocument() {
        var stylesheets = this.getPredefinedElementStylsheetsFromDocument();
        var classes = this.getClassesFromStylesheets(stylesheets);
        return classes;
    }

    getPredefinedElementStylsheetsFromDocument() {
        var canvasDocument = mw.top().app.canvas.getDocument();
        var stylesheets = canvasDocument.querySelectorAll('link[rel="stylesheet"][predefined-element-stylesheet-classes]');
        return stylesheets;
    }

    getClassesFromStylesheets(stylesheets) {
        if (!stylesheets) {
            return [];
        }

        var classes = [];
        for (var i = 0; i < stylesheets.length; i++) {
            var stylesheet = stylesheets[i];
            var rules = stylesheet.sheet.cssRules;
            for (var j = 0; j < rules.length; j++) {
                var rule = rules[j];
                if (rule.selectorText) {
                    var selector = rule.selectorText;
                    var selectorClasses = selector.split('.');
                    for (var k = 0; k < selectorClasses.length; k++) {
                        var selectorClass = selectorClasses[k];

                        //skup :befor and :After and other pseude classes

                        if (selectorClass.indexOf(':') !== -1) {
                            continue;
                        }
                        //skip empty class
                        if (selectorClass === '') {
                            continue;
                        }

                        //if (selectorClass.indexOf('predefined-style-') !== -1) {
                        classes.push(selectorClass);


                        //   }
                    }
                }
            }
        }
        return classes;
    }


    getVariablesFromClass(styleSheets, className) {
        // Get all stylesheets of the document
        // var styleSheets = document.styleSheets;
        var variables = {};

        // Iterate through each stylesheet
        for (var i = 0; i < styleSheets.length; i++) {
            var styleSheet = styleSheets[i];

            try {
                // Access CSS rules
                var cssRules = styleSheet.sheet.cssRules || styleSheet.sheet.rules || styleSheet.cssRules;

                // Iterate through each rule
                for (var j = 0; j < cssRules.length; j++) {
                    var rule = cssRules[j];

                    // Check if it's a class rule and matches the specified className
                    if (rule.type === 1 && rule.selectorText === '.' + className) {
                        // Extract variables
                        var style = rule.style;
                        for (var k = 0; k < style.length; k++) {
                            var propertyName = style[k];
                            if (propertyName.startsWith("--")) {
                                variables[propertyName] = style.getPropertyValue(propertyName);
                            }
                        }
                    }
                }
            } catch (err) {
                // Accessing some stylesheets might throw a security error
                console.error('Error accessing stylesheet: ' + styleSheet.href, err);
            }
        }

        return variables;
    }


}
