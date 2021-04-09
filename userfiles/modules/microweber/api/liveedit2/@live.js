
/* jshint esversion: 6 */
/* globals: mw */

import {ElementAnalyzer} from './element-types';


export const LiveEdit = function (options) {

    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    var defaults = {
        elementClass: 'element',
        cloneableClass: 'cloneable',
        editClass: 'edit',
        moduleClass: 'module',
        rowClass: 'mw-row',
        colClass: 'mw-col',
        safeElementClass: 'safe-element',
        plainElementClass: 'plain-text',
        emptyElementClass: 'empty-element',
        nodrop: 'nodrop',
        allowDrop: 'allow-drop',
        unEditableModules: [
            '[type="template_settings"]'
        ],
        frameworksClasses: {
            col: []
        }
    };

    this.settings = mw.object.extend({}, defaults, options);

    this.elementAnalyzer = new ElementAnalyzer(this.settings);

    // action: append, prepend, before, after
    this.insertElement = function (candidate, target, action) {
        this.dispatch('beforeElementInsert', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementInsert', {candidate: candidate, target: target, action: action});
    };

    this.moveElement = function (candidate, target, action) {
        this.dispatch('beforeElementMove', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementMove', {candidate: candidate, target: target, action: action});
    };


};
