import MicroweberBaseClass from "../containers/base-class.js";
import CSSJSON from "../../core/libs/cssjson/cssjson.js";


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
        if (!mw.app._cssTemp) {
            mw.app._cssTemp = mw.tools.createStyle('#mw-liveedit-dynamic-temp-style', css, this.settings.document.body);
            mw.app._cssTemp.id = 'mw-liveedit-dynamic-temp-style';
        } else {
            mw.app._cssTemp.innerHTML = css;
        }
    }

    removeSheetRuleProperty(selector, property) {
        const css = this.settings.document.querySelector('link#mw-template-settings');
        const css2 = this.settings.document.querySelector('#mw-liveedit-dynamic-temp-style');
        const sheet1 = css ? css.sheet : null;
        const sheet2 = css2 ? css2.sheet : null;

        const removePropertyFromSheet = (sheet) => {
            if (sheet) {
                for (let i = 0, l = sheet.cssRules.length; i < l; i++) {
                    if (sheet.cssRules[i].selectorText === selector) {
                        sheet.cssRules[i].style.removeProperty(property);
                    }
                }
            }
        };

        removePropertyFromSheet(sheet1);
        removePropertyFromSheet(sheet2);
    }

    temp(node, prop, val) {
        val = (val || '').trim();
        this.changed = true;

        if (node.length) {
            node = node[0];
        }

        const sel = mw.tools.generateSelectorForNode(node);

        if (!this._temp.children[sel]) {
            this._temp.children[sel] = {};
        }

        if (!this._temp.children[sel].attributes) {
            this._temp.children[sel].attributes = {};
        }

        this._temp.children[sel].attributes[prop] = val;

        if (val === '' || val === '!important') {
            const prop_val = '';
            this._temp.children[sel].attributes[prop] = prop_val;
            this.removeSheetRuleProperty(sel, prop);
        }

        this._cssTemp(this._temp);
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

    save() {


        var orig = this.json;
        var orig_css = CSSJSON.toCSS(orig).replace(/\.\./g, '.').replace(/\.\./g, '.');

        var assigned2 = this.deepMerge({},  this._temp,orig);
        //var assigned = Object.assign({}, orig, this._temp);

        this.json = this._cleanCSSJSON(assigned2);

        var new_css = CSSJSON.toCSS(this.json).replace(/\.\./g, '.').replace(/\.\./g, '.')
        this._css = orig_css + "\n" + new_css;


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

    deepMerge(obj1, obj2) {
        const merged = {...obj1};

        for (const key in obj2) {
            if (obj2.hasOwnProperty(key)) {
                if (typeof obj2[key] === 'object' && obj2[key] !== null && !Array.isArray(obj2[key])) {
                    if (!merged.hasOwnProperty(key) || typeof merged[key] !== 'object' || merged[key] === null || Array.isArray(merged[key])) {
                        merged[key] = {...obj2[key]};
                    } else {
                        merged[key] = this.deepMerge(merged[key], obj2[key]);
                    }
                } else {
                    merged[key] = obj2[key];
                }
            }
        }

        return merged;
    }


}

export default StylesheetEditor;
