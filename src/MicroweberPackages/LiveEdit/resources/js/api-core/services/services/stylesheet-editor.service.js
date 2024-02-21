import MicroweberBaseClass from "../containers/base-class.js";
import CSSJSON from "../../core/libs/cssjson/cssjson.js";
import getComputedStyle from "@popperjs/core/lib/dom-utils/getComputedStyle";

mw.lib.require('jseldom');

export class StylesheetEditor extends MicroweberBaseClass {
    constructor(config) {
        super();
        this.settings = {
            cssUrl: null,
            saveUrl: mw.settings.api_url + "current_template_save_custom_css",
            document: document,
            ...config
        };


        this.json = null;
        this.css = null;


        this.changed = false;
        this._temp = {children: {}, attributes: {}};
        this.timeOut = null;


        this.init();
    }

    getByUrl(url) {
        return fetch(url).then(response => response.text());
    }

    getLiveeditCSS() {

        if (this.settings.cssUrl) {
            this.getByUrl(this.settings.cssUrl)
                .then(css => {


                    if (/<\/?[a-z][\s\S]*>/i.test(css)) {
                        this.json = {};
                        this._css = '';
                    } else {
                        this.json = CSSJSON.toJSON(css);
                        this._css = css;

                    }

                    //  this.settings.document.dispatchEvent(new Event('ready'));
                })
                .catch(error => console.error('Error fetching CSS:', error));
        } else {
            this.json = {};
            this._css = '';
            this.settings.document.dispatchEvent(new Event('ready'));
        }
    }

    _cssTemp(json) {
        const css = CSSJSON.toCSS(json);



        var tempStylesheetElement = this.settings.document.querySelector('#mw-liveedit-dynamic-temp-style');

        if (tempStylesheetElement == null) {
            mw.app._cssTemp = mw.tools.createStyle('#mw-liveedit-dynamic-temp-style', css, this.settings.document.body);

        } else {
            mw.app._cssTemp._cssTemp = tempStylesheetElement;
        }

        mw.app._cssTemp.id = 'mw-liveedit-dynamic-temp-style';
        if (!mw.app._cssTemp) {

        } else {
            mw.app._cssTemp.innerHTML = css;
        }
    }

    removeSheetRuleProperty(selector, property) {


        this.changed = true;
        const sheets = [
            this.settings.document.querySelector('link#mw-template-settings'),
            this.settings.document.querySelector('#mw-liveedit-dynamic-temp-style'),
            this.settings.document.querySelector('#mw-custom-user-css'),
        ];

        // const sheets = this.settings.document.querySelectorAll('[rel="stylesheet"],style,[type="text/css"]');

        const removePropertyFromSheet = (sheet, property) => {

            if (sheet) {
                var rules;
                if (sheet.cssRules) {
                    rules = sheet.cssRules;
                } else if (sheet.sheet) {
                    rules = sheet.sheet.cssRules;
                }
                if (!rules || !rules.length) {
                    return;
                }
                for (let i = 0, l = rules.length; i < l; i++) {


                    if(rules[i].cssRules && rules[i].cssRules.length) {
                        removePropertyFromSheet(rules[i], property)
                    } else if (rules[i].selectorText === selector) {

                        rules[i].style.removeProperty(property.trim());

                    }
                }
            }
        };

        sheets.forEach((sheet) => {
            removePropertyFromSheet(sheet, property);
        });
    }


    getPropertyForSelector(selector, prop) {

        var canvasDocument = mw.top().app.canvas.getDocument();

        if (selector) {

            if (selector.indexOf(':root') === 0) {
                return getComputedStyle(canvasDocument.querySelector(selector)).getPropertyValue(prop);
            }

            var ActiveNode = canvasDocument.querySelector(selector);
            if (!ActiveNode) {


                var newEl = $.jseldom(selector);

                var holder = canvasDocument.querySelector('#mw-non-existing-temp-element-holder');
                if (!holder) {
                    holder = canvasDocument.createElement('div');
                    holder.id = 'mw-non-existing-temp-element-holder';
                    holder.style.display = 'none';
                    if(canvasDocument && canvasDocument.body) {
                        canvasDocument.body.append(holder);
                    }
                }
                if (newEl) {
                    holder = canvasDocument.getElementById('mw-non-existing-temp-element-holder');
                    holder.append(newEl[0]);
                }
                if(canvasDocument && canvasDocument.body) {
                    ActiveNode = canvasDocument.querySelector(selector);
                }

            }


            if (ActiveNode && ActiveNode && ActiveNode.nodeType === 1) {
                var css = mw.CSSParser(ActiveNode);
                if (css && typeof css.get === 'function') {
                    return css.get(prop);
                }

            }
            return getComputedStyle(canvasDocument.querySelector(selector)).getPropertyValue(prop);

        }

    }


    recordTimeout = {}

    setPropertyForSelector(sel, prop, val, record = true, skipMedia = false) {

        let media = 'screen, print';

        if (mw.top().app.resolutionMode === 'phone' && !skipMedia) {
            media = '(max-width: 460px)';
        }


        media = `@media ${media}`;

        if (record) {
            var timeoutNameStart = sel + prop + 'Start';
            var timeoutName = sel + prop;



            if(!this.recordTimeout[timeoutNameStart]) {

                var state = mw.top().app.state.state();
                var prev = state[state.length - 1];
                if (prev && (prev.target !== '$liveEditCSS' || (prev.target === '$liveEditCSS' && prev.value.selector !== sel))) {
                    if (mw.app.canvas.getDocument().querySelector(sel)) {
                        this.recordTimeout[timeoutNameStart] = true;
                        mw.top().app.state.record({
                            target: '$liveEditCSS',
                            value: {
                                selector: sel,
                                property: prop,
                                value: getComputedStyle(mw.app.canvas.getDocument().querySelector(sel))[prop]
                            }
                        })
                    }
                }
            }




        }

        this.changed = true;


        if (!this._temp.children[media]) {
            this._temp.children[media] = {
                attributes: {},
                children: {},
            };
        }

        if (!this._temp.children[media].children[sel]) {
            this._temp.children[media].children[sel] = {};
        }

        if (!this._temp.children[media].children[sel].attributes) {
            this._temp.children[media].children[sel].attributes = {};
        }

        this._temp.children[media].children[sel].attributes[prop] = val;

        if (val === '' || val === '!important' || val === undefined || val === null) {
            const prop_val = '';
            this._temp.children[media].children[sel].attributes[prop] = prop_val;
            if (this._temp.children
                && this._temp.children[media]
                && this._temp.children[media].children[sel]
            ) {
                delete this._temp.children[media].children[sel].attributes[prop];
            }
            if (this.json
                && this.json.children
                && this.json.children[media]
                && this.json.children[media].children[sel]
            ) {
                delete this.json.children[media].children[sel].attributes[prop];
            }

            this.removeSheetRuleProperty(sel, prop);
        }


        this._cssTemp(this._temp);

        if (record) {
            clearTimeout(this.recordTimeout[timeoutName]);
            this.recordTimeout[timeoutName] = setTimeout(() => {


                mw.top().app.state.record({
                    target: '$liveEditCSS',
                    value: {
                        selector: sel,
                        property: prop,
                        value: val
                    }
                });
                delete this.recordTimeout[timeoutNameStart];
                delete this.recordTimeout[timeoutName];
            }, 1500);
        }
        mw.top().app.dispatch('setPropertyForSelector', {
            selector: sel,
            property: prop,
            value: val
        });
    }



    style(node, css = {}) {
        for (let i in css) {
            this.temp(node, i, css[i]);
        }
    }

    temp(node, prop, val) {
        val = (val || '').trim();

        if (node.length) {
            node = node[0];
        }
        node.style[prop] = '';
        node.style.removeProperty(prop);

        const sel = mw.tools.generateSelectorForNode(node);

        this.setPropertyForSelector(sel, prop, val)
    }

    _cleanCSSJSON(obj) {
        for (const a in obj) {
            const k = obj[a];
            if (k === '' || k === '!important') {
                delete obj[a];
                return this._cleanCSSJSON(obj);
            } else if (typeof k === 'object') {
                if (Object.keys(k).length === 0) {
                    delete obj[a];
                    return this._cleanCSSJSON(obj);
                } else {
                    obj[a] = this._cleanCSSJSON(obj[a]);
                }
            }
        }
        return obj;
    }

    save(merge = true) {

        var toSave;

        if (merge) {
            toSave = $.extend(true, {}, this.json, this._temp);
        } else {
            toSave = this._temp;
        }

        var assigned = this._cleanCSSJSON(toSave);

        this.json = this._cleanCSSJSON(assigned);

        this._css = CSSJSON.toCSS(this.json).replace(/\.\./g, '.').replace(/\.\./g, '.')

        mw.top().trigger('mw.liveeditCSSEditor.save');
    }

    findBySelector(selector) {
        const json = this.getJSONValue();
        return json.children[selector];
    }

    publish(callback) {
        return new Promise(resolve => {

            const css = {
                css_file_content: this.getValue()
                //css_file_content: this.getValue()

            };


            var liveEditIframeData = mw.top().app.canvas.getLiveEditData();

            if (liveEditIframeData
                && liveEditIframeData.template_name

            ) {
                var template_name = liveEditIframeData.template_name;
                css.active_site_template = template_name;
            }

            if (liveEditIframeData
                && liveEditIframeData.content

            ) {
                var content_id = liveEditIframeData.content.id;
                css.content_id = content_id;
            }

            $.post(this.settings.saveUrl, css)
                .done(res => {
                    this.changed = false;
                    resolve(res);
                    if (callback) {
                        callback.call(this, res);
                    }
                })
                .fail(() => resolve(false));
        });
    }

    publishIfChanged(callback) {

        return new Promise(async resolve => {
            if (this.changed) {
                await this.publish(callback);
            }
            resolve();
        });
    }

    getValue() {
        this.save();
        return this._css;
    }

    getJSONValue() {
        this.save();
        return this.json;
    }

    init() {
        this.getLiveeditCSS();
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

export default StylesheetEditor;
