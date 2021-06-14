/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./userfiles/modules/microweber/api/liveedit2/analizer.js":
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/analizer.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DroppableElementAnalyzerService": () => (/* binding */ DroppableElementAnalyzerService)
/* harmony export */ });
/* harmony import */ var _element_analizer_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./element-analizer.service */ "./userfiles/modules/microweber/api/liveedit2/element-analizer.service.js");


class DroppableElementAnalyzerService extends _element_analizer_service__WEBPACK_IMPORTED_MODULE_0__.ElementAnalyzerServiceBase  {

    constructor(settings) {
        super(settings);
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

    canMoveModule (node) {
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
        // whether or not "target" can accept elements
        return !!(
            this.canAcceptByClass(target) &&
            this.isEditOrInEdit(target) &&
            this.allowDrop(target));

    }

    canReceiveElements(target) {
        return this.isEdit(target) && this.canAcceptByTag(target);
    }

    dropableElements (){
        return this._dropableElements;
    }

    getIteractionTarget(node) {
        return this.tools.firstWithAyOfClassesOnNodeOrParent(node, [
            this.settings.elementClass,
            this.settings.editClass,
            this.settings.moduleClass,
        ]);
    }

    getTarget (node) {

        const target = this.getIteractionTarget(node);
        if(!target || !this.isEditOrInEdit(node) || !this.allowDrop(node)) {
            return null;
        }
        const res = {
            target,
            canInsert: false,
            beforeAfter: false
        }
        if (this.isEdit(target)) {
            res.canInsert = true;
        } else if(this.isElement(target)) {
            if(this.canAcceptByTag(target)) {
                res.canInsert = true;
            }
            //if(this.canInsertBeforeOrAfter(target)) {
                res.beforeAfter = true;
            //}
        } else if(this.isModule(target)) {
            if(this.canInsertBeforeOrAfter(target)) {
                res.beforeAfter = true;
            } else {
                return null;
            }
        }
        return res;
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







/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/dom.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/dom.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DomService": () => (/* binding */ DomService)
/* harmony export */ });
class DomService {

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
                if (node.classList.contains(arr[i])) {
                    return true;
                }
            }
            node = node.parentElement;
        }
        return false;
    }

    static hasParentWithId (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr !== document.body) {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static firstWithAyOfClassesOnNodeOrParent(node, arr) {
        while (node && node.nodeName !== 'BODY') {
            let i = 0, l = arr.length;
            for ( ; i < l ; i++ ) {
                if (node.classList.contains(arr[i])) {
                    return node;
                }
            }
            node = node.parentElement;
        }
        return null;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirst (node, arr) {
        let curr = node;
        while (curr && curr !== document.body) {
            const h1 = curr.classList.contains(arr[0]);
            const h2 = curr.classList.contains(arr[1]);
            if (h1 && h2) {
                return false;
            }
            else {
                if (h1) {
                    return true;
                }
                else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirstOrNone (node, arr) {
        let curr = node;
        while (curr && curr !== document.body) {
            const h1 = curr.classList.contains(arr[0]);
            const h2 = curr.classList.contains(arr[1]);
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
            if (node.classList.contains(arr[i])) {
                return true;
            }
        }
        return false;
    }

    static offset (node) {
        if(!node) return;
        var off = node.getBoundingClientRect();
        var res = {top: off.top, left: off.left, width: off.width, height: off.height, bottom: off.bottom, right: off.right};;
        res.top += scrollY;
        res.bottom += scrollY;
        res.left += scrollX;
        res.right += scrollX;
        return res;
    }
    static parentsOrder (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
            count++;
            i = 0;
            for ( ; i < l; i++) {
                if (curr.classList.contains(arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    }


}


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/draggable.js":
/*!*****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/draggable.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Draggable": () => (/* binding */ Draggable)
/* harmony export */ });
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/liveedit2/object.service.js");
/* harmony import */ var _analizer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./analizer */ "./userfiles/modules/microweber/api/liveedit2/analizer.js");
/* harmony import */ var _drop_position__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./drop-position */ "./userfiles/modules/microweber/api/liveedit2/drop-position.js");



 

const Draggable = function (options, rootSettings) {
    var defaults = {
        handle: null,
        element: null,
        target: document.body,
        targetDocument: document,
        helper: true
    };
    var scope = this;

    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    var stop = true;

    var scroll = function (step) {
        scope.settings.targetDocument.body.style.scrollBehavior = 'smooth';
        scope.settings.targetDocument.defaultView.scrollTo(0,scrollY + step);
    }

    this.config = function () {
        this.settings = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, defaults, options);
        this.setElement(this.settings.element);
        this.dropIndicator = this.settings.dropIndicator;
    };
    this.setElement = function (node) {
        this.element = mw.element(node)/*.prop('draggable', true)*/.get(0);
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
        if(!this._helper) {
            this._helper = document.createElement('div');
            this._helper.className = 'mw-draggable-helper';
            document.body.appendChild(this._helper);
        }
        if (e === 'create') {
            this._helper.style.top = e.pageY + 'px';
            this._helper.style.left = e.pageX + 'px';
            this._helper.style.width = scope.element.offsetWidth + 'px';
            this._helper.style.height = scope.element.offsetHeight + 'px';

            this._helper.style.display = 'block';
        } else if(e === 'remove' && this._helper) {
            this._helper.style.display = 'none';
        } else if(this.settings.helper && e) {
            this._helper.style.top = e.pageY + 'px';
            this._helper.style.left = e.pageX + 'px';
            this._helper.style.maxWidth = (innerWidth - e.pageX) + 'px';
        }
        return this._helper;
    };

    this.isDragging = false;
    this.dropableService = new _analizer__WEBPACK_IMPORTED_MODULE_1__.DroppableElementAnalyzerService(rootSettings);


    this.dropPosition = _drop_position__WEBPACK_IMPORTED_MODULE_2__.DropPosition;

    this.draggable = function () {
         mw.element(this.settings.target).on('dragover', function (e) {
             scope.target = null;
             scope.action = null;
             if(e.target !== scope.element || !scope.element.contains(e.target)) {
                 var targetAction = scope.dropableService.getTarget(e.target)

                 if(targetAction && targetAction !== scope.element) {
                     const pos = scope.dropPosition(e, targetAction);
                     if(pos) {
                         scope.target = targetAction.target;
                         scope.action = pos.action;
                         scope.dropIndicator.position(scope.target, pos.action + '-' + pos.position)
                     } else {

                         scope.dropIndicator.hide()
                     }

                 } else {
                     scope.dropIndicator.hide()
                 }
                 if (scope.isDragging) {
                     scope.dispatch('dragOver', {element: scope.element, event: e});
                     e.preventDefault();
                 }
             }


        }).on('drop', function (e) {
            if (scope.isDragging) {
                e.preventDefault();
                if (scope.target && scope.action) {
                    mw.element(scope.target)[scope.action](scope.element);
                }

                scope.dispatch('drop', {element: scope.element, event: e});
            }
             scope.dropIndicator.hide();
        });
        this.handle
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
                var scrlStp = 90;
                var step = 5;
                if (e.clientY < scrlStp) {
                    scroll(-step)
                }
                if (e.clientY > (innerHeight - (scrlStp + ( this._helper ? this._helper.offsetHeight + 10 : 0)))) {
                    scroll(step)
                }
                scope.dispatch('drag',{element: scope.element, event: e});
                scope.helper(e)
            })
            .on('dragend', function (e) {
                scope.isDragging = false;
                scope.element.classList.remove('mw-element-is-dragged');
                scope.helper('remove');
                scope.dispatch('dragEnd',{element: scope.element, event: e});
                stop = true;
            });
    };
    this.init();
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/drop-position.js":
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/drop-position.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DropPosition": () => (/* binding */ DropPosition)
/* harmony export */ });
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");



let prevY = -1;
let prev = null;

const DropPosition = (e, conf) => {
    if(!e || !conf) {
        return false
    }
    const target = conf.target;
    if( !target || target.nodeType !== 1) return false;
    const x = e.pageX;
    const y = e.pageY;

    /*
    *  conf { canInsert: boolean,  beforeAfter: boolean }
    * */


    //  if(x%2 !== 0) return false;
    const rect = _dom__WEBPACK_IMPORTED_MODULE_0__.DomService.offset(target);
    const res = {};
    const distance = 15;
    if( prevY  === y || !conf || (!conf.canInsert && !conf.beforeAfter)) return false;
    if(conf.canInsert && conf.beforeAfter) {
        if (y >= (rect.top - distance) && y <= (rect.top + distance)) {
            res.position = 'top';
            res.action = 'before';
        } else if ( y >= (rect.top + distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'prepend';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom - distance)) {
            res.position = 'bottom';
            res.action = 'append';
        }  else if ( y >= (rect.top + (rect.height/2)) && y >= (rect.bottom - distance)) {
            res.position = 'bottom';
            res.action = 'after';
        } else {
            return false;
        }
    } else if(conf.beforeAfter) {
        if ( y >= (rect.top - distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'before';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom + distance)) {
            res.position = 'bottom';
            res.action = 'after';
        } else {
            return false;
        }
    }  else if(conf.canInsert) {
        if ( y >= (rect.top - distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'prepend';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom + distance)) {
            res.position = 'bottom';
            res.action = 'append';
        } else {
            return false;
        }
    }

    return res
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/element-analizer.service.js":
/*!********************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/element-analizer.service.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ElementAnalyzerServiceBase": () => (/* binding */ ElementAnalyzerServiceBase)
/* harmony export */ });
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");



class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = _dom__WEBPACK_IMPORTED_MODULE_0__.DomService;
    }

    isRow (node) {
        return node.classList.contains(this.settings.rowClass);
    }

    isModule (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type !== 'layouts';
    }

    isLayout (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts';
    }

    isElement (node) {
        return node.classList.contains(this.settings.elementClass);
    }

    isEmptyElement (node) {
        return node.classList.contains(this.settings.emptyElementClass);
    }

    isEdit (node) {
        return node.classList.contains(this.settings.editClass);
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
        return node.classList.contains(this.settings.plainElementClass);
    }

    getType(node) {
        if(this.isEdit(node)) {
            return 'edit';
        } else if(this.isElement(node)) {
            return 'element';
        } else if(this.isModule(node)) {
            return 'module';
        }  else if(this.isLayout(node)) {
            return 'layout';
        }
    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/handle-menu.js":
/*!*******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/handle-menu.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HandleMenu": () => (/* binding */ HandleMenu)
/* harmony export */ });
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");


const HandleMenu = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };

    this.createWrapper = function() {
        this.wrapper = document.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handlemenu-' + new Date().getTime());
        this.wrapper.className = 'mw-defaults mw-handlemenu-item ' + (this.options.className || 'mw-handlemenu-type-default');
        this.wrapper.contenteditable = false;

        mw.element(this.wrapper).on('mousedown', function () {
            this.classList.add('mw-handlemenu-item-mouse-down')
        });
        mw.element(document.documentElement).on('mouseup', function () {
            scope.wrapper.classList.remove('mw-handlemenu-item-mouse-down')
        });
        document.body.appendChild(this.wrapper);
    };

    this.create = function() {
        this.createWrapper();
        this.createHandler();

        this.createMenu();
    };

    this.setTitle = function (icon, title) {
        this.handleIcon.innerHTML = icon;
        this.handleTitle.innerHTML = title;
    };

    this.hide = function () {
        mw.element(this.wrapper).hide().removeClass('active');
        this._visible = false;
        return this;
    };

    this.show = function () {
        mw.element(this.wrapper).show();
        this._visible = true;
        return this;
    };

    this.createHandler = function(){
        this.handle = document.createElement('span');
        this.handleIcon = document.createElement('span');
        this.handleTitle = document.createElement('span');
        this.handle.className = 'mw-handlemenu-handler';
        this.handleIcon.dataset.tip = 'Drag to rearrange';
        this.handleIcon.className = 'tip mw-handlemenu-handler-icon';
        this.handleTitle.className = 'mw-handlemenu-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.createButtons();
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            mw.element(scope.wrapper).toggleClass('active');
        };
        mw.element(document.body).on('click', function (e) {
            if(!_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.hasParentWithId(e.target, scope.wrapper.id)){
                mw.element(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = document.createElement('span');
        btn.className = 'mw-handlemenu-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass
            }
            var icon = document.createElement('span');
            icon.className = iconClass + ' mw-handlemenu-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(document.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(data.action){
            btn.onmousedown = function (e) {
                e.preventDefault();
            };
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
                scope.hide()
            };
        }
        return btn;
    };

    this._defaultButtons = [

    ];

    this.createMenuDynamicHolder = function(item){
        var dn = document.createElement('div');
        dn.className = 'mw-handlemenu-menu-dynamic' + (item.className ? ' ' + item.className : '');
        return dn;
    };
    this.createMenu = function(){
        this.menu = document.createElement('div');
        this.menu.className = 'mw-handlemenu-menu ' + (this.options.menuClass ? this.options.menuClass : 'mw-handlemenu-menu-default');
        if (this.options.menu) {
            for (var i = 0; i < this.options.menu.length; i++) {
                if(this.options.menu[i].title !== '{dynamic}') {
                    this.menu.appendChild(this.menuButton(this.options.menu[i])) ;
                }
                else {
                    this.menu.appendChild(this.createMenuDynamicHolder(this.options.menu[i])) ;
                }

            }
        }
        this.wrapper.appendChild(this.menu);
    };
    this.createButton = function(obj){
        var btn = document.createElement('span');
        btn.className = 'tip mdi ' + obj.icon + (obj.className ? ' ' + obj.className : '');
        btn.dataset.tip = obj.title;
        if (obj.hover) {
            btn.addEventListener('mouseenter', obj.hover[0] , false);
            btn.addEventListener('mouseleave', obj.hover[1] , false);
        }
        btn.onclick = function () {
            this.classList.remove('active');
            obj.action(this);
            scope.hide();
        };
        return btn;
    };

    this.createButtons = function(){
        this.buttonsHolder = document.createElement('div');
        this.buttonsHolder.className = 'mw-handlemenu-buttons';
        if (this.options.buttons) {
            for (var i = 0; i < this.options.buttons.length; i++) {
                this.buttonsHolder.appendChild(this.createButton(this.options.buttons[i])) ;
            }
        }
        this.handle.appendChild(this.buttonsHolder);
    };
    this.create();
    this.hide();
}


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
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/liveedit2/object.service.js");
/* harmony import */ var _draggable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./draggable */ "./userfiles/modules/microweber/api/liveedit2/draggable.js");
/* harmony import */ var _interact__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./interact */ "./userfiles/modules/microweber/api/liveedit2/interact.js");
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");





const Handle = function (options) {

    var defaults = {

    };


    var scope = this;

    this.settings = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, defaults, options);

    const _e = {};
    this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

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
    let _content = null;
    this.setContent = function (content) {
        if(_content){
            _content.remove()
        }
        _content = content;
        this.wrapper.append(_content);
    }


    this.initDraggable = function () {
      this.draggable = new _draggable__WEBPACK_IMPORTED_MODULE_1__.Draggable({
          handle: this.handle,
          element: null,
          helper: true,
          dropIndicator: this.settings.dropIndicator
      }, options);
        this.draggable.on('dragStart', function () {
            scope.wrapper.addClass('mw-handle-item-dragging');
        })
        this.draggable.on('dragEnd', function () {
            scope.wrapper.removeClass('mw-handle-item-dragging');
        })
    };

    this.set = function (target) {
        if (!target) {
            _currentTarget = null;
            return;
        }
        var off = _dom__WEBPACK_IMPORTED_MODULE_3__.DomService.offset(target);
         this.wrapper.css({
            top: off.top,
            left: off.left,
            width: off.width,
            height: off.height,
        });
        this.show();
        this.draggable.setElement(target);
        if(_currentTarget !== target) {
            _currentTarget = target;
            this.dispatch('targetChange', target);
        }

    };

    this.createHandle = function () {
        this.handle = mw.element({
            tag: 'div',
            props: {
                className: 'mw-defaults mw-handle-item-handle',
                contentEditable: false,
                draggable: true,
                innerHTML: this.settings.title
            }
        });
        this.wrapper.append(this.handle);
    }

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
        mw.element(document.body).on('mouseup touchend', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        document.body.appendChild(this.wrapper.get(0));
    };

    this.createWrapper();
    this.createHandle();
    this.initDraggable();
    if(this.settings.content) {
        this.setContent(this.settings.content)
    }




};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/handles-content/element.js":
/*!*******************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/handles-content/element.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ElementHandleContent": () => (/* binding */ ElementHandleContent)
/* harmony export */ });
/* harmony import */ var _handle_menu__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../handle-menu */ "./userfiles/modules/microweber/api/liveedit2/handle-menu.js");


const ElementHandleContent = function () {
    this.root = mw.element();
    this.menuHolder = mw.element();
    this.root.append(this.menuHolder)

    this.menu = new _handle_menu__WEBPACK_IMPORTED_MODULE_0__.HandleMenu({
        id: 'mw-handle-item-element',
        className: 'mw-handle-type-default',
        buttons: [
            {
                title: mw.lang('Insert'),
                icon: 'mdi-plus-circle',
                className: 'mw-handle-insert-button',
                hover: [
                    function (e){
                        handleInsertTargetDisplay(mw._activeElementOver, mw.handleElement.positionedAt);
                    },
                    function (e){
                        handleInsertTargetDisplay('hide');
                    }
                ],
                action: function (el) {
                    if (!mw.tools.hasClass(el, 'active')) {
                        mw.tools.addClass(el, 'active');
                        mw.drag.plus.locked = true;
                        mw.$('.mw-tooltip-insert-module').remove();
                        mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';

                        var tooltip = new mw.ToolTip({
                            content: document.getElementById('plus-modules-list').innerHTML,
                            element: el,
                            position: mw.drag.plus.tipPosition(this.currentNode),
                            template: 'mw-tooltip-default mw-tooltip-insert-module',
                            id: 'mw-plus-tooltip-selector',
                            overlay: true
                        });
                        tooltip.on('removed', function () {
                            mw.drag.plus.locked = false;
                        });
                        mw._initHandles.hideAll();

                        var tip = tooltip.tooltip.get(0);
                        setTimeout(function (){
                            $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
                        }, 10);
                        mw.tabs({
                            nav: tip.querySelectorAll('.mw-ui-btn'),
                            tabs: tip.querySelectorAll('.module-bubble-tab'),
                        });

                        mw.$('.mw-ui-searchfield', tip).on('input', function () {
                            var resultsLength = mw.drag.plus.search(this.value, tip);
                            if (resultsLength === 0) {
                                mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                            }
                            else {
                                mw.$(".module-bubble-tab-not-found-message").hide();
                            }
                        });
                        mw.$('#mw-plus-tooltip-selector li').each(function () {
                            this.onclick = function () {
                                var name = mw.$(this).attr('data-module-name');
                                var conf = { class: this.className };
                                if(name === 'layout') {
                                    conf.template = mw.$(this).attr('template');
                                }
                                mw.module.insert(mw._activeElementOver, name, conf, mw.handleElement.positionedAt);
                                mw.wysiwyg.change(mw._activeElementOver)
                                tooltip.remove();
                            };
                        });
                    }
                }
            }
        ],
        menu: [
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
    });

    this.menuHolder.append(this.menu.wrapper)

}


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/handles.js":
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/handles.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Handles": () => (/* binding */ Handles)
/* harmony export */ });
const Handles = function (handles) {

    this.handles = handles;
    this.dragging = false;
    var scope = this;

    this.get = function (handle) {
        return this.handles[handle];
    }

    this.set = function (handle, target){
         this.get(handle).set(target)
    }

    this.hide = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].hide();
        } else {
            this.each(function (name, h){
                h.hide()
            });
        }
    };

    this.hideAllBut = function(handle) {
        this.each(function (name, h){
            if(name !== handle) {
                h.hide()
            }
        });
    };

    this.show = function(handle) {
        if (handle && this.handles[handle]) {
            this.handles[handle].show();
        } else {
            this.each(function (name, handle){
                handle.show();
            });
        }
    };

    this.each = function (c) {
        if(!c) return;
        var i;
        for (i in this.handles) {
            c.call(scope, i, this.handles[i]);
        }
    };

    this.init = function (){
        this.each(function (name, handle){
            handle.draggable.on('dragStart', function (){
                scope.dragging = true;
                scope.hideAllBut(name)
            })
            handle.draggable.on('dragEnd', function (){
                scope.dragging = false;
                handle.show()
            })
        })
    }

    this.init();
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/interact.js":
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/interact.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DropIndicator": () => (/* binding */ DropIndicator)
/* harmony export */ });
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/liveedit2/object.service.js");
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");




const DropIndicator = function (options) {

    options = options || {};

    var defaults = {
        template: 'default'
    };

    this.settings = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, defaults, options);

    this._indicator = null;

    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.hide = function () {
        this._indicator.addClass('mw-drop-indicator-hidden');
    };

    this.show = function () {
        this._indicator.removeClass('mw-drop-indicator-hidden');
    };

    var positions = [
        'before-top', 'prepend-top',
        'after-bottom', 'append-bottom'
    ];


    const positionsPrefix = 'mw-drop-indicator-position-';

    var positionsClasses = positions.map(function (cls){ return positionsPrefix + cls });

    this.position = function (rect, position) {
        this._indicator.removeClass(positionsClasses);
        if(!rect || !position) return;
            if(rect.nodeType === 1) {
                rect = _dom__WEBPACK_IMPORTED_MODULE_1__.DomService.offset(rect);
            }
        this._indicator.addClass(positionsPrefix + position);
        this._indicator.css({
            height: rect.height,
            left: rect.left,
            top: rect.top,
            width: rect.width,
        });
        this.show();
        $('.mw-drop-indicator-block').html(position)
    };

    this.make = function () {
        this._indicator = mw.element();
        this._indicator.html('<div class="mw-drop-indicator-block"><div class="mw-drop-indicator-pin"></div></div>');
        this._indicator.addClass('mw-drop-indicator mw-drop-indicator-template-' + this.settings.template);
        this.hide();
        document.body.appendChild(this._indicator.get(0));
    };

    this.init = function (){
        this.make();
    };

    this.init();

};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/mode-auto.js":
/*!*****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/mode-auto.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ModeAuto": () => (/* binding */ ModeAuto)
/* harmony export */ });
const getElementsLike = (selector, root) => {
    root = root || document.body;
    selector = selector || '*';
    var all = root.querySelectorAll(selector), i = 0, final = [];
    for( ; i<all.length; i++){
        if(!undefined.scope.helpers.isColLike(all[i]) &&
            !undefined.scope.helpers.isRowLike(all[i]) &&
            !undefined.scope.helpers.isEdit(all[i]) &&
            undefined.scope.helpers.isBlockLevel(all[i])){
            final.push(all[i]);
        }
    }
    return final;
};

const ModeAuto = (root, selector, config, domService, dropableService) => {
    const {
        backgroundImageHolder,
        editClass,
        moduleClass,
        elementClass,
        allowDrop
    } = config;
    root = root || document.body;
    selector = selector || '*';
    var bgHolders = root.querySelectorAll('.' + editClass + '.' + backgroundImageHolder + ', .' + editClass + ' .' + backgroundImageHolder + ', .'+editClass+'[style*="background-image"], .'+editClass+' [style*="background-image"]');
    var noEditModules = root.querySelectorAll('.' + moduleClass + mw.noEditModules.join(',.' + moduleClass));
    var edits = root.querySelectorAll('.edit');
    var i = 0, i1 = 0, i2 = 0;
    for ( ; i < bgHolders.length; i++ ) {
        var curr = bgHolders[i];
        var po = mw.tools.parentsOrder(curr, [editClass, moduleClass]);
        if(po.module === -1 || (po.edit < po.module && po.edit !== -1)){
            if(!mw.tools.hasClass(curr, moduleClass)){
                mw.tools.addClass(curr, editClass);
            }
            curr.style.backgroundImage = curr.style.backgroundImage || 'none';
        }
    }
    for ( ; i1<noEditModules.length; i1++ ) {
        noEditModules[i].classList.remove(moduleClass);
    }
    for ( ; i2 < edits.length; i2++ ) {
        var all = getElementsLike(':not(.' + elementClass + ')', edits[i2]), i2a = 0;
        var allAllowDrops = edits[i2].querySelectorAll('.' + allowDrop), i3a = 0;
        for( ; i3a < allAllowDrops.length; i3a++){
            allAllowDrops[i3a].classList.add(elementClass);
        }
        for( ; i2a<all.length; i2a++) {
            if(!all[i2a].classList.contains(moduleClass)){
                if(dropableService.canAccept(all[i2a])){
                    all[i2a].classList.add( elementClass );
                }
            }
        }
    }
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/object.service.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/object.service.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ObjectService": () => (/* binding */ ObjectService)
/* harmony export */ });
class ObjectService {
    static extend () {
        const extended = {};
        let deep = false;
        let i = 0;
        const l = arguments.length;

        if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
            deep = arguments[0];
            i++;
        }
        const merge = function (obj) {
            for ( const prop in obj ) {
                if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                    if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                        extended[prop] = ObjectService.extend( true, extended[prop], obj[prop] );
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };
        for ( ; i < l; i++ ) {
            const obj = arguments[i];
            merge(obj);
        }
        return extended;

    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/liveedit2/pointer.js":
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/pointer.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "GetPointerTargets": () => (/* binding */ GetPointerTargets)
/* harmony export */ });
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/liveedit2/object.service.js");
/* harmony import */ var _dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom */ "./userfiles/modules/microweber/api/liveedit2/dom.js");



const GetPointerTargets = function(options)  {

    options = options || {};

    this.tools = _dom__WEBPACK_IMPORTED_MODULE_1__.DomService;

    var scope = this;

    var defaults = {
        exceptions: ['mw-handle-item']
    };

    this.settings = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, defaults, options);

    if ( this.settings.root.nodeType === 9 ) {
        this.document = this.settings.root;
    } else {
        this.document = this.settings.root.ownerDocument;
    }
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

    this.fromEvent = function (e) {
        if(!scope.tools.hasAnyOfClassesOnNodeOrParent(e.target, this.settings.exceptions)) {
            return this.fromPoint(e.pageX, e.pageY - scrollY);
        }
        return []
    }
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
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
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
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit2/@live.js ***!
  \*************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "LiveEdit": () => (/* binding */ LiveEdit)
/* harmony export */ });
/* harmony import */ var _handle__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./handle */ "./userfiles/modules/microweber/api/liveedit2/handle.js");
/* harmony import */ var _pointer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./pointer */ "./userfiles/modules/microweber/api/liveedit2/pointer.js");
/* harmony import */ var _mode_auto__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mode-auto */ "./userfiles/modules/microweber/api/liveedit2/mode-auto.js");
/* harmony import */ var _handles__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./handles */ "./userfiles/modules/microweber/api/liveedit2/handles.js");
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/liveedit2/object.service.js");
/* harmony import */ var _analizer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./analizer */ "./userfiles/modules/microweber/api/liveedit2/analizer.js");
/* harmony import */ var _interact__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./interact */ "./userfiles/modules/microweber/api/liveedit2/interact.js");
/* harmony import */ var _handles_content_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./handles-content/element */ "./userfiles/modules/microweber/api/liveedit2/handles-content/element.js");


 





 
 
  // import "./css/liveedit.scss";


class LiveEdit {

    constructor(options) {

        const scope = this;
        const _e = {};
        this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

        var defaults = {
            elementClass: 'element',
            backgroundImageHolder: 'background-image-holder',
            cloneableClass: 'cloneable',
            editClass: 'edit',
            moduleClass: 'module',
/*            rowClass: 'mw-row',
            colClass: 'mw-col',
            safeElementClass: 'safe-element',
            plainElementClass: 'plain-text',
            emptyElementClass: 'empty-element',*/
            nodrop: 'nodrop',
            allowDrop: 'allow-drop',
            unEditableModules: [
                '[type="template_settings"]'
            ],
            frameworksClasses: {
                col: ['col', 'mw-col']
            },
            document: document,
            root: document.body,
            strict: false // todo: element and modules should be dropped only in layouts
        };

        this.settings = _object_service__WEBPACK_IMPORTED_MODULE_4__.ObjectService.extend({}, defaults, options);

        this.root = this.settings.root;

        this.elementAnalyzer = new _analizer__WEBPACK_IMPORTED_MODULE_5__.DroppableElementAnalyzerService(this.settings);

        this.dropIndicator = new _interact__WEBPACK_IMPORTED_MODULE_6__.DropIndicator();


        const elementhandleContent = new _handles_content_element__WEBPACK_IMPORTED_MODULE_7__.ElementHandleContent()

        this.handles = new _handles__WEBPACK_IMPORTED_MODULE_3__.Handles({
            element: new _handle__WEBPACK_IMPORTED_MODULE_0__.Handle({...this.settings, title: 'Element', dropIndicator: this.dropIndicator, content: elementhandleContent.root}),
            module: new _handle__WEBPACK_IMPORTED_MODULE_0__.Handle({...this.settings, title: 'module:', dropIndicator: this.dropIndicator}),
            layout: new _handle__WEBPACK_IMPORTED_MODULE_0__.Handle({...this.settings, title: 'layout', dropIndicator: this.dropIndicator})
        });

        this.handles.get('element').on('targetChange', function (target) {
            console.log(target);
        })

        this.handles.get('module').on('targetChange', function (target) {
            console.log('module', target);
        })

        this.observe = new _pointer__WEBPACK_IMPORTED_MODULE_1__.GetPointerTargets(this.settings);
        //this.dropIndicator = new DropIndicator();

        this.init();
    }

    init() {

         mw.element(this.root).on('mousemove touchmove', (e) => {
                if (e.pageX % 2 === 0) {
                    const elements = this.observe.fromEvent(e);
                    const first = elements[0];
                    if(first) {
                       const type = this.elementAnalyzer.getType(first);
                       if(type && type !== 'edit') {
                           this.handles.set(type, elements[0])
                           if(type === 'element') {
                               this.handles.hide('module')
                           } else if(type === 'module') {
                               this.handles.hide('element')
                           }
                       }
                    }
                }
         });
    };

    // action: append, prepend, before, after
    insertElement (candidate, target, action) {
        this.dispatch('beforeElementInsert', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementInsert', {candidate: candidate, target: target, action: action});
    };

    moveElement (candidate, target, action) {
        this.dispatch('beforeElementMove', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementMove', {candidate: candidate, target: target, action: action});
    };

}

globalThis.LiveEdit = LiveEdit;

})();

/******/ })()
;
//# sourceMappingURL=live-edit2.js.map