
/* jshint esversion: 6 */
/* globals: mw */

import {ElementAnalyzer} from './element-types';
import {Handle} from "./handle";
import {GetPointerTargets} from "./neighbours";


const LiveEditHandles = function (handles) {

        this.handles = handles;
        var scope = this;

        this.hide = function(handle) {
            if(handle && this.handles[handle]) {
                this.handles[handle].hide();
            }
            this.each(function (handle){
                handle.hide()
            });

        };
        this.show = function(handle) {
            if(handle && this.handles[handle]) {
                this.handles[handle].show();
            }
            this.each(function (handle){
                handle.show()
            });

        };

        this.each = function (c) {
          if(!c) return;
          var i;
          for (i in this.handles) {
              c.call(scope, this.handles[i])
          }
        };

};

export const LiveEdit = function (options) {

    var scope = this;

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
        },
        root: document.body
    };

    this.settings = mw.object.extend({}, defaults, options);

    var root = this.settings.root;

    this.elementAnalyzer = new ElementAnalyzer(this.settings);

    this.handles = new LiveEditHandles({
        handleElement: new Handle(),
        handleModule: new Handle(),
        handleLayout: new Handle()
    });

    this.observe = new GetPointerTargets();





    this.init = function () {
        mw.element(root).on('mousemove touchmove', function (e) {
            if (e.pageX % 2 === 0) {
                var elements = scope.observe.fromPoint(e.pageX, e.pageY);

            }
         });
    };



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

    this.init();


};

globalThis.LiveEdit = LiveEdit;


export const ValidationService = function (options) {

    options = options || {};
    var defaults = {
        document: document,
        debug: true
    };
    var scope = this;

    var log = function () {
      if(scope.settings.debug) {
        console.warn(arguments);
      }
    };

    this.settings = mw.object.extend({}, defaults, options);

    this.edits = function (root) {
        var all = (root || this.settings.document).getElementsByClassName(options.editClass);
        var i = 0;
        var l = all.length;
        for ( ; i < l; i++) {
            var field = all[i].getAttribute('field') || all[i].dataset.field;
            var rel = all[i].getAttribute('rel') || all[i].dataset.rel;
            if (!field) log(all[i], ' has no attribute field.');
            if (!rel) log(all[i], ' has no attribute rel.');
        }
    };
};
