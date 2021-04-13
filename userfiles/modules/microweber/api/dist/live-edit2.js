/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./userfiles/modules/microweber/api/liveedit2/@live.js":
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/@live.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "LiveEdit": () => (/* binding */ LiveEdit),
/* harmony export */   "ValidationService": () => (/* binding */ ValidationService)
/* harmony export */ });
/* harmony import */ var _element_types__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./element-types */ "./userfiles/modules/microweber/api/liveedit2/element-types.js");
/* harmony import */ var _handle__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./handle */ "./userfiles/modules/microweber/api/liveedit2/handle.js");

/* jshint esversion: 6 */
/* globals: mw */





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
            })

        };

        this.each = function (c) {
          if(!c) return;
          var i;
          for (i in this.handles) {
              c.call(scope, this.handles[i])
          }
        };

};

const LiveEdit = function (options) {

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

    this.elementAnalyzer = new _element_types__WEBPACK_IMPORTED_MODULE_0__.ElementAnalyzer(this.settings);

    this.handles = new LiveEditHandles({
        handleElement: new _handle__WEBPACK_IMPORTED_MODULE_1__.Handle(),
        handleModule: new _handle__WEBPACK_IMPORTED_MODULE_1__.Handle(),
        handleLayout: new _handle__WEBPACK_IMPORTED_MODULE_1__.Handle()
    });





    this.init = function () {
        mw.element(root).on('mousemove touchmove', function (e) {

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


const ValidationService = function (options) {

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


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/element-types.js":
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/element-types.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ElementAnalyzerService": () => (/* binding */ ElementAnalyzerService),
/* harmony export */   "ElementAnalyzer": () => (/* binding */ ElementAnalyzer)
/* harmony export */ });
/* jshint esversion: 6 */
/* globals: mw */


const ElementAnalyzerService = function (settings) {

    var dropableElements;

    this.isConfigurable = function (target) {
        return this.isElement(target) || this.isModule(target) || this.isRow(target);
    };

    this.isRow = function(node) {
        return mw.tools.hasClass(node, this.settings.rowClass);
    };

    this.isModuleButNotLayout = function(node) {
        return node.dataset.type !== 'layouts';
    };
    this.isLayout = function(node) {
        return node.dataset.type === 'layouts';
    };

    this.isEditableLayout = function(node) {
        return this.this.isLayout(node) && this.isInEdit(node);
    };

    this.isEditableModule = function(node) {
        return this.isModule(node) && this.isInEdit(node);
    };

    this.isElement = function(node) {
        return mw.tools.hasClass(node, this.settings.elementClass);
    };

    this.isEmpty = function(node) {
        return mw.tools.hasClass(node, this.settings.emptyElementClass);
    };

    var _tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM'];

    this.canAcceptByClass = function (node) {
        return mw.tools.hasAnyOfClasses(node, this.dropableElements());
    };

    this.canAcceptByTag = function (node) {
        if(!node || node.nodeType !== 1) return false;
        return _tagsCanAccept.indexOf(node.nodeName) !== -1;
    };

    this.isEdit = function(node) {
        return mw.tools.hasClass(node, this.settings.editClass);
    };

    this.isInEdit = function(node) {
        var order = [
            this.settings.editClass,
            this.settings.moduleClass,
        ];
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
    };

    this.isEditOrInEdit = function (node) {
        return this.isEdit(node) || this.isInEdit(node);
    };

    this.allowDrop = function (node) {
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.settings.allowDrop, this.settings.nodrop]);
    };

    this.canInsertBeforeOrAfter = function (node) {
        return this.canAccept(node.parentNode);
    };


    this.isPlainText = function (node) {
        return mw.tools.hasClass(node, this.settings.plainElementClass);
    };

    this.canAccept = function (target) {
        if (this.canAcceptByTag(target)
            && this.canAcceptByClass(target)
            && this.isEditOrInEdit(target)
            && this.allowDrop(target)) {
        }
        return false;
    };

    this.dropableElements = function (){
        return dropableElements;
    };

    this.getTarget = function (node) {
        if (!node || node === document.body) return null;
        if (this.canAccept(node)) {
            return node;
        } else {
            return this.getTarget(node.parentElement);
        }
    };

    this.init = function () {
        this.settings = settings;
        dropableElements = [
            settings.elementClass,
            settings.cloneableClass,
            settings.editClass,
            settings.moduleClass,
            settings.colClass,
            settings.allowDrop,
        ];
    };


    this.init();
};




const ElementAnalyzer = function (options) {
    options = options || {};

    this.settings = options;

    this.service = new ElementAnalyzerService(this.settings);

};



(function (){
    var MenuItem = function (data, scope) {
        var btn = document.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass;
            }
            var icon = document.createElement('span');
            icon.className = iconClass + ' mw-handle-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(document.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(typeof data.visible === 'function'){
            if(!data.visible()) {
                btn.style.display = 'none';
            }
        }
        if(data.action){
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
            };
        }
        return btn;
    };
    var MenuItems = {
        module: [
            {
                title: 'Edit HTML',
                icon: 'mw-icon-code',
                action: function () {
                    mw.editSource(mw._activeElementOver);
                }
            },
            {
                title: 'Edit Style',
                icon: 'mdi mdi-layers',
                action: function () {
                    mw.liveEditSettings.show();
                    mw.sidebarSettingsTabs.set(3);
                    if(mw.cssEditorSelector){
                        mw.liveEditSelector.active(true);
                        mw.liveEditSelector.select(mw._activeElementOver);
                    } else {
                        mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                            setTimeout(function(){
                                mw.liveEditSelector.active(true);
                                mw.liveEditSelector.select(mw._activeElementOver);
                            }, 333);
                        });
                    }
                    mw.liveEditWidgets.cssEditorInSidebarAccordion();
                }
            },
            {
                title: 'Remove',
                icon: 'mw-icon-bin',
                className:'mw-handle-remove',
                action: function () {
                    mw.drag.delete_element(mw._activeElementOver);
                    mw.handleElement.hide()
                }
            }
        ]

};
    var ElementAnalyzer = function (options) {

    };
})();


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/handle.js":
/*!**************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/handle.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Handle": () => (/* binding */ Handle)
/* harmony export */ });
/* jshint esversion: 6 */
/* globals: mw */

const Draggable = function (options) {
    var defaults = {
        handle: null,
        element: null,
        target: document.body,
        helper: true
    };
    var scope = this;

    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.config = function () {
        this.settings = mw.object.extend({}, defaults, options);
        this.setElement(this.settings.element);
    };
    this.setElement = function (node) {
        this.element = node;
        if(!this.settings.handle) {
            this.settings.handle = this.settings.element;
        }
        this.handle = this.settings.handle;
    };

    this.setTargets = function (targets) {
        this.targets = mw.element(targets);
    };

    this.addTarget = function (target) {
        this.targets.push(target);
    };

    this.init = function () {
        this.config();
        this.draggable();
    };

    this.helper = function (e) {
        if (e === 'create') {
            var el = mw.element(this.element.outerHTML);
            el.removeAttr('id').find('[id]').removeAttr('id');
            document.body.appendChild(el.get(0));
            this._helper = el.get(0);
        } else if(e === 'remove' && this._helper) {
            this._helper.remove();
            this._helper = null;
        } else if(this.settings.helper && this._helper && e) {
            this._helper.style.top = e.pageY + 'px';
            this._helper.style.left = e.pageX + 'px';
        }
        return this._helper;
    };

    this.isDragging = false;


    this.draggable = function () {
        this.handle.draggable = true;
        mw.element(this.settings.target).on('dragover', function (e) {
            if (scope.isDragging) {
                scope.dispatch('dragOver', {element: scope.element, event: e});
                e.preventDefault();
            }

        }).on('drop', function (e) {
            if (scope.isDragging) {
                e.preventDefault();
                scope.dispatch('drop', {element: scope.element, event: e});
            }
        });
        mw.element(this.settings.handle)
            .on('dragstart', function (e) {
                scope.isDragging = true;
                if(!scope.element.id) {
                    scope.element.id = ('mw-element-' + new Date().getTime());
                }
                scope.element.classList.add('mw-element-is-dragged');
                e.dataTransfer.setData("text", scope.element.id);
                scope.helper('create');
                scope.dispatch('dragStart',{element: scope.element, event: e});
            })
            .on('drag', function (e) {
                scope.helper(e);
                scope.dispatch('drag',{element: scope.element, event: e});
            })
            .on('dragend', function (e) {
                scope.isDragging = false;
                scope.element.classList.remove('mw-element-is-dragged');
                scope.helper();
                scope.helper('remove');
                scope.dispatch('dragEnd',{element: scope.element, event: e});
            });
    };
    this.init();
};



const Handle = function (options) {

    var defaults = {

    };


    var scope = this;

    this.settings = mw.object.extend({}, defaults, options);

    var _visible = true;
    var _currentTarget = null;

    this.isVisible = function () {
        return _visible;
    };

    this.show = function () {
        _visible = true;
        this.wrapper.removeClass('mw-handle-item-hidden');
    };
    this.hide = function () {
        _visible = false;
        this.wrapper.addClass('mw-handle-item-hidden');
    };

    this.initDraggable = function () {
      this.draggable = new Draggable({
          handle: this.wrapper
      });
    };
    this.set = function (target) {
        if (!target) {
            _currentTarget = null;
            return;
        }
        var off = target.getBoundingClientRect();
        this.wrapper.css({
            top: off.top,
            left: off.left,
            width: off.width,
            height: off.height,
        });
        this.show();
        this.draggable.setElement(target);
        _currentTarget = target;
    };

    this.createWrapper = function() {
        this.wrapper = mw.element({
            tag: 'div',
            props: {
                className: 'mw-defaults mw-handle-item ' + (this.settings.className || 'mw-handle-type-default'),
                contentEditable: false,
                id: this.settings.id || ('mw-handle-' + new Date().getTime())
            }
        });

        this.wrapper.on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        mw.$(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });

        document.body.appendChild(this.wrapper);
    };



};


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	// startup
/******/ 	// Load entry module
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/liveedit2/@live.js");
/******/ 	// This entry module used 'exports' so it can't be inlined
/******/ })()
;
//# sourceMappingURL=live-edit2.js.map