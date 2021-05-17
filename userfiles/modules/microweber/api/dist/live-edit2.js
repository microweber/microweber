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
/* harmony import */ var _neighbours__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./neighbours */ "./userfiles/modules/microweber/api/liveedit2/neighbours.js");

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

    this.observe = new _neighbours__WEBPACK_IMPORTED_MODULE_2__.GetPointerTargets();





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
/* harmony export */   "ElementDomNestingService": () => (/* binding */ ElementDomNestingService),
/* harmony export */   "ElementAnalyzerServiceBase": () => (/* binding */ ElementAnalyzerServiceBase),
/* harmony export */   "DropableElementAnalyzerService": () => (/* binding */ DropableElementAnalyzerService),
/* harmony export */   "ElementAnalyzerService": () => (/* binding */ ElementAnalyzerService),
/* harmony export */   "ElementAnalyzer": () => (/* binding */ ElementAnalyzer)
/* harmony export */ });
/* jshint esversion: 6 */
/* globals: mw */





class ElementDomNestingService {

    static firstWithBackgroundImage (node) {
        if (!node) {
            return null;
        }
        while(node && node.nodeName !== 'BODY') {
            if (!!node.style.backgroundImage) {
                return node;
            }
            node = node.parentElement;
        }
        return null;
    }

    static hasAnyOfClassesOnNodeOrParent(node, arr) {
        while (node && node.nodeName !== 'BODY') {
            let i = 0, l = arr.length;
            for ( ; i < l ; i++ ) {
                if (node.classList.has(arr[i])) {
                    return true;
                }
            }
            node = node.parentElement;
        }
        return null;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirstOrNone (node, arr) {
        let curr = node;
        while (curr && curr !== document.body) {
            const h1 = curr.classList.has(arr[0]);
            const h2 = curr.classList.has(curr, arr[1]);
            if (h1 && h2) {
                return false;
            } else {
                if (h1) {
                    return true;
                } else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return true;
    }

    static hasAnyOfClasses (node, arr) {
        if (!node) return;
        let i = 0, l = arr.length;
        for (; i < l; i++) {
            if (node.classList.has(arr[i])) {
                return true;
            }
        }
        return false;
    }


}

class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = ElementDomNestingService;
    }

    isRow (node) {
        return node.classList.has(this.settings.rowClass);
    }

    isModuleButNotLayout (node) {
        return node.dataset.type !== 'layouts';
    }
    isLayout (node) {
        return node.dataset.type === 'layouts';
    }

    isElement (node) {
        return node.classList.has(this.settings.elementClass);
    }

    isEmpty (node) {
        return node.classList.has(this.settings.emptyElementClass);
    }


    isEdit (node) {
        return node.classList.has(this.settings.editClass);
    }

    isInEdit (node) {
        var order = [
            this.settings.editClass,
            this.settings.moduleClass,
        ];
        return this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
    }

    isEditOrInEdit (node) {
        return this.isEdit(node) || this.isInEdit(node);
    }

    isPlainText (node) {
        return node.classList.has(this.settings.plainElementClass);
    }

}

class DropableElementAnalyzerService extends ElementAnalyzerServiceBase  {


    constructor(settings) {
        super();
        this.settings = settings;
        this._tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM'];
        this.init();
    }


    isConfigurable (target) {
        return this.isElement(target) || this.isModule(target) || this.isRow(target);
    }

    isEditableLayout (node) {
        return this.this.isLayout(node) && this.isInEdit(node);
    }

    isEditableModule (node) {
        return this.isModule(node) && this.isInEdit(node);
    }


    canAcceptByClass (node) {
        return this.tools.hasAnyOfClasses(node, this.dropableElements());
    }

    canAcceptByTag (node) {
        if(!node || node.nodeType !== 1) return false;
        return this._tagsCanAccept.indexOf(node.nodeName) !== -1;
    }

    allowDrop (node) {
        return this.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.settings.allowDrop, this.settings.nodrop]);
    }

    canInsertBeforeOrAfter (node) {
        return this.canAccept(node.parentNode);
    }

    canAccept (target) {
        if (this.canAcceptByTag(target) &&
            this.canAcceptByClass(target) &&
            this.isEditOrInEdit(target) &&
            this.allowDrop(target)) {
        }
        return false;
    }

    dropableElements (){
        return this._dropableElements;
    }

    getTarget (node) {
        if (!node || node === this.settings.document.body) return null;
        if (this.canAccept(node)) {
            return node;
        } else {
            return this.getTarget(node.parentElement);
        }
    }

    init () {
        this._dropableElements = [
            this.settings.elementClass,
            this.settings.cloneableClass,
            this.settings.editClass,
            this.settings.moduleClass,
            this.settings.colClass,
            this.settings.allowDrop,
        ];
    }



}

class ElementAnalyzerService {

    constructor(options) {
        this.settings = options;
    }


    canSelect(target) {
        return !!target.id;
    }


}




const ElementAnalyzer = function (options) {
    options = options || {};

    this.settings = options;

    this.dropableService = new DropableElementAnalyzerService(this.settings);
    this.moveService = new ElementAnalyzerService(this.settings);

    this.getTargets = function (targets) {

    };

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
        this.element = mw.element(node).prop('draggable', true).css({
            userSelect: 'none'
        }).get(0);
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

    this.helper2 = function (e) {
        if (e === 'create') {
            var el = mw.element(this.element.outerHTML);
            el.removeAttr('id').find('[id]').removeAttr('id');
            document.body.appendChild(el.get(0));

            this._helper = el.css({position:'absolute', zIndex: 100}).get(0);
        } else if(e === 'remove' && this._helper) {
            this._helper.remove();
            this._helper = null;
        } else if(this.settings.helper && this._helper && e) {
            this._helper.style.top = e.pageY + 'px';
            this._helper.style.left = e.pageX + 'px';
        }
        return this._helper;
    };


    this.helper = function (e) {
        if (e === 'create') {

        } else if(e === 'remove' && this._helper) {

        } else if(this.settings.helper && e) {
            this.element.style.top = e.pageY + 'px';
            this.element.style.left = e.pageX + 'px';
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

window.Draggable = Draggable

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


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/neighbours.js":
/*!******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/neighbours.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "GetPointerTargets": () => (/* binding */ GetPointerTargets)
/* harmony export */ });
/* jshint esversion: 6 */
/* globals: mw */

const GetPointerTargets = function (options) {

    options = options || {};

    var scope = this;

    var defaults = {
        document: document
    };

    this.settings = mw.object.extend({}, defaults, options);

    this.document = this.settings.document;
    this.body = this.document.body;


    var distanceMax = 20;

    function distance(x1, y1, x2, y2) {
        return Math.hypot(x2-x1, y2-y1);
    }

    function isInRange(el1, el2) {
        return distance(el1, el2) <= distanceMax;
    }

    var validateNode = function (node) {
        return node.type === 1;
    };

    var getChildren = function (parent, target) {
        var res = [], curr = parent.firstElementChild;
        while (curr && curr !== target && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            if(curr.children && curr.children.length) {
                res.push.apply(res, getChildren(parent, target));
            }
            curr = validateNode(curr.nextElementSibling);
        }
        return res;
    };

    var getAbove = function(target) {
        var res = [], curr = target.previousElementSibling;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.previousElementSibling;
        }
        return res;
    };

    var getBelow = function(target) {
        var res = [], curr = target.nextElementSibling;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.nextElementSibling;
        }
        return res;
    };

    var getParents = function (target) {
        var res = [], curr = target.parentElement;
        while (curr && isInRange(target, curr)){
            if(validateNode(curr)) {
                res.push(curr);
            }
            curr = curr.parentElement;
        }
        return res;
    };
    this.getParents = getParents;
    this.getBelow = getBelow;

    this.getNeighbours = function (event) {
        var target = event.target;
        return [].concat(getParents(target), getAbove(target), getBelow(target), getChildren(target, target));
    };


    var round5 = function (x){
        return (x % 5) >= 2.5 ? (x / 5) * 5 + 5 : (x / 5) * 5;
    };

    var getNearCoordinates = function (x, y) {
        x = round5(x);
        y = round5(y);
        var res = [];
        var x1 = x - distanceMax;
        var x1Max = x + distanceMax;
        var y1 = y - distanceMax;
        var y1Max = y + distanceMax;
        for ( ; x1 < x1Max; x1 += 5) {
            for ( ; y1 <= y1Max; y1 += 5 ) {
                res.push([x1, y1]);
            }
        }
        return res;
    };

    var addNode = function (el, res) {
        if(el && !!el.parentElement && res.indexOf(el) === -1 && scope.body !== el) {
            res.push(el);
        }
    };

    this.fromPoint = function (x, y) {
        var res = [];
        var el = scope.document.elementFromPoint(x, y);
        if (!el ) return [];
        addNode(el, res);
        var dots = getNearCoordinates(x, y);
        dots.forEach(function (coords){
            addNode(scope.document.elementFromPoint(coords[0], coords[1]), res);
        });
        return res;
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