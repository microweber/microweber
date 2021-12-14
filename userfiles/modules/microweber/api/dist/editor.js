/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./userfiles/modules/microweber/api/classes/css.js":
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/css.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CSSParser": () => (/* binding */ CSSParser)
/* harmony export */ });




const CSSParser = function(el) {
    if(!el || !el.nodeName) return false;
    if(el.nodeName === '#text') return false;



    const css = window.getComputedStyle(el, null);



    var f = {};

    f.display = function(){
        return css.display;
    };

    f.is = function(){
        return {
            bold: parseFloat(css.fontWeight)>600 || css.fontWeight === 'bold' || css.fontWeight === 'bolder',
            italic: css.fontStyle === 'italic'||css.fontStyle === 'oblique',
            underlined: css.textDecoration === 'underline',
            striked: css.textDecoration.indexOf('line-through') === 0,
        };
    };
    f.font = function(){
        if(css === null) return false;
        return {
            size:css.fontSize,
            weight:css.fontWeight,
            style:css.fontStyle,
            height:css.lineHeight,
            family:css.fontFamily,
            color:css.color
        };
    }
    f.alignNormalize = function(){
        if(!!css){
            var a = css.textAlign;
            return a.contains('left')?'left':a.contains('center')?'center':a.contains('justify')?'justify':a.contains('right')?'right':'left';
        }
    }
    f.border = function(parse){
        if(!parse){
            return {
                top:{width:css.borderTopWidth, style:css.borderTopStyle, color:css.borderTopColor},
                left:{width:css.borderLeftWidth, style:css.borderLeftStyle, color:css.borderLeftColor},
                right:{width:css.borderRightWidth, style:css.borderRightStyle, color:css.borderRightColor},
                bottom:{width:css.borderBottomWidth, style:css.borderBottomStyle, color:css.borderBottomColor}
            }
        }
        else{
            return {
                top:{width:parseFloat(css.borderTopWidth), style:css.borderTopStyle, color:css.borderTopColor},
                left:{width:parseFloat(css.borderLeftWidth), style:css.borderLeftStyle, color:css.borderLeftColor},
                right:{width:parseFloat(css.borderRightWidth), style:css.borderRightStyle, color:css.borderRightColor},
                bottom:{width:parseFloat(css.borderBottomWidth), style:css.borderBottomStyle, color:css.borderBottomColor}
            }
        }

    }
    f.width = function(){
        return css.width;
    }
    f.position = function(){
        return css.position;
    }
    f.background = function(){
        return {
            image:css.backgroundImage,
            color:css.backgroundColor,
            position:css.backgroundPosition,
            repeat:css.backgroundRepeat
        }
    }
    f.margin = function(parse, actual){
        if(actual){
            var _parent = el.parentNode;
            var parentOff = mw.$(_parent).offset();
            var elOff = mw.$(el).offset();
            if(elOff.left > parentOff.left && css.marginLeft === css.marginRight && elOff.left - parentOff.left === parseInt(css.marginLeft, 10)){
                return {
                    top:css.marginTop,
                    left:'auto',
                    right:'auto',
                    bottom:css.marginBottom
                };
            }
        }
        if(!parse){
            return {
                top:css.marginTop,
                left:css.marginLeft,
                right:css.marginRight,
                bottom:css.marginBottom
            }
        }
        else{
            return {
                top:parseFloat(css.marginTop),
                left:parseFloat(css.marginLeft),
                right:parseFloat(css.marginRight),
                bottom:parseFloat(css.marginBottom)
            }
        }
    }
    f.padding = function(parse){
        if(!parse){
            return {
                top:css.paddingTop,
                left:css.paddingLeft,
                right:css.paddingRight,
                bottom:css.paddingBottom
            }
        }
        else{
            return {
                top:parseFloat(css.paddingTop),
                left:parseFloat(css.paddingLeft),
                right:parseFloat(css.paddingRight),
                bottom:parseFloat(css.paddingBottom)
            }
        }
    }
    f.opacity = function(){return css.opacity}

    f.radius = function(parse){
        if(!parse){
            return {
                tl:css.borderTopLeftRadius,
                tr:css.borderTopRightRadius,
                br:css.borderBottomRightRadius,
                bl:css.borderBottomLeftRadius
            }
        }
        else{
            return {
                tl:parseFloat(css.borderTopLeftRadius),
                tr:parseFloat(css.borderTopRightRadius),
                br:parseFloat(css.borderBottomRightRadius),
                bl:parseFloat(css.borderBottomLeftRadius)
            }
        }
    }

    f.transform = function(){
        let transform = css['transform'] || css['WebkitTransform'];
        if(transform==="" || transform==="none"){
            return [1, 0, 0, 1, 0, 0];
        }
        else{
            transform = transform.substr(7, transform.length - 8).split(", ");
            return transform;
        }
    }

    f.shadow = function(){
         const shadow = (css['boxShadow'] || css['WebkitBoxShadow']).replace(/, /g, ",").split(" ");
        return {
            color: shadow[0],
            left: shadow[1],
            top: shadow[2],
            blur: shadow[3],
            spread: shadow[3]
        }
    }

    return {
        el: el,
        css: css,
        get: f
    }
}




/***/ }),

/***/ "./userfiles/modules/microweber/api/classes/dialog.js":
/*!************************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/dialog.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Dialog": () => (/* binding */ Dialog),
/* harmony export */   "Confirm": () => (/* binding */ Confirm),
/* harmony export */   "Alert": () => (/* binding */ Alert)
/* harmony export */ });
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");


const dialogFooter = (okLabel, cancelLabel) => {
    const footer = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
        props: {
            className: 'le-dialog-footer'
        }
    });

    const ok = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
        props: {
            className: 'le-btn le-btn-primary le-dialog-footer-ok',
            innerHTML: okLabel || 'OK'
        }
    });

    const cancel = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
        props: {
            className: 'le-btn le-dialog-footer-cancel'
        }
    });

    footer.append(cancel);
    footer.append(ok);

    return  {
        ok, cancel, footer
    }
}

class Dialog {
    constructor(options) {
        options = options || {}
        const defaults = {
            content: null,
            overlay: true,
            document: document
        }
        this.settings = Object.assign({}, defaults, options);
        console.log( this.settings );
        this.build();
        setTimeout(_ => this.open())
    }
    build() {
        this.root = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'le-dialog',

            }
        });
        var closeBtn = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'le-dialog-close',
            }
        });
        closeBtn.on('click', () => {
            this.remove();
        });
        this.container = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'le-dialog-container'
            },
            content: this.settings.content
        })
        this.root.append(closeBtn);
        this.root.append(this.container);
        if(this.settings.footer) {
            this.root.append(this.settings.footer.root || this.settings.footer);
        }
        this.settings.document.body.appendChild(this.root.get(0))
        if (this.settings.overlay) {
            this.overlay()
        }
    }
    open() {
        this.root.addClass('le-dialog-opened')
    }
    remove() {
        this.root.remove()
        if(this.overlay){
            this.overlay.remove()
        }
    }
    overlay() {
        this.overlay = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'le-dialog-overlay'
            }
        })
        this.settings.document.body.appendChild(this.overlay.get(0))
    }

}


const Confirm = function (content, c) {
    const footer = dialogFooter();
    const dialog = new Dialog({
        content, footer
    });
    footer.cancel.on('click', function (){
        dialog.remove()
    })
    footer.ok.on('click', function (){
        if(c){
            c.call()
        }
        dialog.remove()
    });
    return dialog
}

const Alert = function (text) {
    return new Dialog({
        content: text
    })
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/classes/dom.js":
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/dom.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DomService": () => (/* binding */ DomService)
/* harmony export */ });

let matches;
const el = document.documentElement;
if(!!el.matches) matches = 'matches';
else if (!!el.matchesSelector) matches = 'matchesSelector';
else if (!!el.mozMatchesSelector) matches = 'mozMatchesSelector';
else if (!!el.webkitMatchesSelector) matches = 'webkitMatchesSelector';

class DomService {
    static _isBlockCache = {};
    static _fragment;

    static fragment (){
        if(!this._fragment){
            this._fragment = document.createElement('div');
            this._fragment.style.visibility = 'hidden';
            this._fragment.style.position = 'absolute';
            this._fragment.style.width = '1px';
            this._fragment.style.height = '1px';
            document.body.appendChild(this._fragment);
        }
        return this._fragment;
    }

    static matches(node, selector) {
        return node[matches](selector)
    }


    static isBlockLevel (node){
        if(!node || node.nodeType === 3){
            return false;
        }
        var name = node.nodeName;
         if(typeof this._isBlockCache[name] !== 'undefined'){
            return this._isBlockCache[name];
        }
        var test = document.createElement(name);
        this.fragment().appendChild(test);
        this._isBlockCache[name] = getComputedStyle(test).display === 'block';
        this.fragment().removeChild(test);
        return this._isBlockCache[name];
    }

    static firstBlockLevel (el) {
        while(el && el.classList) {
            if(this.isBlockLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    }

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

    static hasParentsWithClass (el, cls) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.classList.contains(cls)) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static hasParentWithId (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr.nodeName !== 'BODY') {
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

    static firstParentOrCurrentWithTag (el, tag) {
        if (!el || !tag) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static index(el, parent, selector) {
        selector = selector || el.tagName.toLowerCase();
        parent = parent || el.parentNode;
        var all;
        if (parent.constructor === [].constructor) {
            all = parent;
        }
        else {
            all = parent.querySelectorAll(selector)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    }

    static firstParentOrCurrentWithClass (el, cls) {
        if (!el) return false;
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.classList.contains(cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }
    static firstParentOrCurrent (el, selector) {
        if (!el) return false;
        var curr = el;
        while (curr && curr.nodeName !== 'BODY') {
            if (curr.matches(selector)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static firstParentOrCurrentWithAnyOfClasses (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr.nodeName !== 'BODY') {
            if (!curr) return false;
            if (this.hasAnyOfClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    }

    static parentsOrCurrentOrderMatchOrOnlyFirst (node, arr) {
        let curr = node;
        while (curr && curr.nodeName !== 'BODY') {
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
        while (curr && curr.nodeName !== 'BODY') {

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
        var res = {top: off.top , left: off.left, width: off.width, height: off.height, bottom: off.bottom, right: off.right};;
        res.top += node.ownerDocument.defaultView.scrollY;
        res.bottom += node.ownerDocument.defaultView.scrollY;
        res.left += node.ownerDocument.defaultView.scrollX;
        res.right += node.ownerDocument.defaultView.scrollX;
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

/***/ "./userfiles/modules/microweber/api/classes/element.js":
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/element.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ElementManager": () => (/* binding */ ElementManager)
/* harmony export */ });
/* harmony import */ var _object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object.service */ "./userfiles/modules/microweber/api/classes/object.service.js");




    var MWElement = function(options, root){
        var scope = this;

        this.isMWElement = true;

        this.toggle = function () {
            this.css('display', this.css('display') === 'none' ? 'block' : 'none');
        };

        this._active = function () {
            return this.nodes[this.nodes.length - 1];
        };

        this.getDocument = function () {
            return this._active().ownerDocument;
        }

        this.getWindow = function () {
            return this.getDocument().defaultView;;
        }

        this.get = function(selector, scope){
            this.nodes = (scope || document).querySelectorAll(selector);
        };

        this.each = function(cb){
            if(this.nodes) {
                for (var i = 0; i < this.nodes.length; i++) {
                    cb.call(this.nodes[i], i);
                }
            } else if(this.node) {
                cb.call(this.node, 0);
            }
            return this;
        };

        this.encapsulate = function () {

        };

        var contentManage = function (content, scope) {
            if (content) {
                if (Array.isArray(content)) {
                    content.forEach(function (el){
                        contentManage(el, scope);
                    });
                } else if (content instanceof MWElement) {
                    scope.append(content);
                } else if (typeof content === 'object') {
                    scope.append(new MWElement(content));
                }
            }
        }

        this.create = function() {
            var el = this.document.createElement(this.settings.tag);
            this.node = el;

            if (this.settings.encapsulate === true) {
                var mode = this.settings.encapsulate === true ? 'open' : this.settings.encapsulate;
                el.attachShadow({
                    mode: mode
                });
            }
            this.nodes = [el];

            if (this.settings.content) {
                contentManage(this.settings.content, this)
            }
        };

        this._specialProps = function(dt, val){
            if(dt === 'tooltip') {
                this.node.dataset[dt] = val;
                return true;
            }
        };

        this.setProps = function(){
            for(var i in this.settings.props) {
                if (i === 'dataset') {
                    for(var dt in this.settings.props[i]) {
                        this.node.dataset[dt] = this.settings.props[i][dt];
                    }
                } else if (i === 'style') {
                    for(var st in this.settings.props[i]) {
                        this.node.style[st] = this.settings.props[i][st];
                    }
                } else {
                    var val = this.settings.props[i];
                    if(!this._specialProps(i, val)) {
                        this.node[i] = val;
                    }
                }
            }
        };

        this.__ = {
            cssNumber: [
                'animationIterationCount',
                'columnCount',
                'fillOpacity',
                'flexGrow',
                'flexShrink',
                'fontWeight',
                'gridArea',
                'gridColumn',
                'gridColumnEnd',
                'gridColumnStart',
                'gridRow',
                'gridRowEnd',
                'gridRowStart',
                'lineHeight',
                'opacity',
                'order',
                'orphans',
                'widows',
                'zIndex',
                'zoom'
            ]
        };

        this._normalizeCSSValue = function (prop, val) {
            if(typeof val === 'number') {
                if(this.__.cssNumber.indexOf(prop) === -1) {
                    val = val + 'px';
                }
            }
            return val;
        };

        this.css = function(css, val){
            if(typeof css === 'string') {
                if(typeof val !== 'undefined'){
                    var nval =  this._normalizeCSSValue(css, val);
                    this.each(function (){
                        this.style[css] = nval;
                    });
                } else {
                    return this.document.defaultView.getComputedStyle(this.node)[css];
                }
            }
            if(typeof css === 'object') {
                for (var i in css) {

                    this.each(function (){
                        this.style[i] = scope._normalizeCSSValue(i, css[i]);
                    });
                }
            }
            return this;
        };

        this.dataset = function(prop, val){
            if(typeof val === 'undefined') {
                return this._active()[prop];
            }
            this.each(function (){
                this.dataset[prop] = val;
            });
            return this;
        };

        this.attr = function(prop, val){
            if(typeof val === 'undefined') {
                return this._active()[prop];
            }
            this.each(function (){
                this.setAttribute(prop, val);
            });
            return this;
        };

        this.val = function(val){
            if(typeof val === 'undefined') {
                return this._active().value;
            }
            this.each(function (){
                this.value = val;
            });
            return this;
        };

        this.prop = function(prop, val){
            var active = this._active();
            if(typeof val === 'undefined') {
                return active[prop];
            }
            if(active[prop] !== val){
                active[prop] = val;
                this.trigger('propChange', [prop, val]);
            }
            return this;
        };

        this.hide = function () {
            return this.each(function (){
                this.style.display = 'none';
            });
        };
        this.show = function () {
            return this.each(function (){
                this.style.display = '';
            });
        };

        this.find = function (sel) {
            var el = mw.element('#r' + new Date().getTime());
            this.each(function (){
                var all = this.querySelectorAll(sel);
                for(var i = 0; i < all.length; i++) {
                    if(el.nodes.indexOf(all[i]) === -1) {
                        el.nodes.push(all[i]);
                    }
                }
            });
            return el;
        };

        this.addClass = function (cls) {
            cls = cls.trim().split(' ');
            return this.each(function (){
                var node = this;
                cls.forEach(function (singleClass){
                    node.classList.add(singleClass);
                });

            });
        };

        this.toggleClass = function (cls) {
            return this.each(function (){
                this.classList.toggle(cls.trim());
            });
        };

        this.removeClass = function (cls) {
            var isArray = Array.isArray(cls);
            if(!isArray) {
                cls = cls.trim();
                var isMultiple = cls.split(' ');
                if(isMultiple.length > 1) {
                    return this.removeClass(isMultiple)
                }
                return this.each(function (){
                    this.classList.remove(cls);
                });
            } else {
                return this.each(function (){
                    var i = 0, l = cls.length;
                    for ( ; i < l; i++) {
                        this.classList.remove(cls[i]);
                    }
                });
            }
        };

        this.remove = function () {
            return this.each(function (){
                this.remove();
            });
        };

        this.empty = function () {
            return this.html('');
        };

        this.html = function (val) {
            if (typeof val === 'undefined') {
                return this._active().innerHTML;
            }
            return this.each(function (){
                this.innerHTML = val;
            });
        };
        this.text = function (val, clean) {
            if(typeof val === 'undefined') {
                return this.node.textContent;
            }
            if(typeof clean === 'undefined') {
                clean = true;
            }
            if (clean) {
                val = this.document.createRange().createContextualFragment(val).textContent;
            }
            this.node.innerHTML = val;
        };

        this._asdom = function (obj) {
            if (typeof obj === 'string') {
                return this.document.createRange().createContextualFragment(obj);
            } else if (obj.node){
                return obj.node;
            }
            else if (obj.nodes){
                return obj.nodes[obj.nodes.length - 1];
            } else {
                return obj;
            }
        };

        this.offset = function () {
            var curr = this._active();
            var win = this.getWindow();
            var rect = curr.getBoundingClientRect();
            rect.offsetTop = rect.top + win.pageYOffset;
            rect.offsetBottom = rect.bottom + win.pageYOffset;
            rect.offsetLeft = rect.left + win.pageXOffset;
            return rect;
        };


        this.width = function (val) {
            if(val) {
                return this.css('width', val);
            }
            return this._active().offsetWidth;
        };

        this.height = function (val) {
            if(val) {
                return this.css('height', val);
            }
            return this._active().offsetHeight;
        };

        this.parent = function () {
            return mw.element(this._active().parentNode);
        };
        this.parents = function (selector) {
            selector = selector || '*';
            var el = this._active();
            var curr = el.parentElement;
            var res = mw.element();
            res.nodes = []
            while (curr) {
                if(curr.matches(selector)) {
                    res.nodes.push(curr);
                }
                curr = curr.parentElement;
            }
            return res;
        };
        this.append = function (el) {

            if (el) {
                this.each(function (){
                    this.append(scope._asdom(el));
                });
            }
            return this;
        };

        this.before = function (el) {
            if (el) {
                this.each(function (){
                    if(this.parentNode){
                        this.parentNode.insertBefore(scope._asdom(el), this);
                    }
                });
            }
            return this;
        };

        this.after = function (el) {
            if (el) {
                this.each(function (){
                    if(this.parentNode) {
                        this.parentNode.insertBefore(scope._asdom(el), this.nextSibling);
                    }
                });
            }
        };

        this.prepend = function (el) {
            if (el) {
                this.each(function (){
                    this.prepend(scope._asdom(el));
                });
            }
            return this;
        };
        this._disabled = false;

        Object.defineProperty(this, "disabled", {
            get : function () { return this._disabled; },
            set : function (value) {
                this._disabled = value;
                this.node.disabled = this._disabled;
                this.node.dataset.disabled = this._disabled;
            }
        });

        this.trigger = function(event, data){
            data = data || {};
            this.each(function (){
                this.dispatchEvent(new CustomEvent(event, {
                    detail: data,
                    cancelable: true,
                    bubbles: true
                }));
                if(scope._on[event]) {
                    scope._on[event].forEach(function(cb){
                        cb.call(this, event, data);
                    });
                }
            });
            return this;
        };

        this.get = function (i) {
            return this.nodes[i];
        };

        this.wrap = function (what) {
            var newEl = DomQuery(what);
            this.before(newEl)
            newEl.append(this)
        }

        this._on = {};
        this.on = function(events, cb){
            events = events.trim().split(' ');
            events.forEach(function (ev) {
                if(!scope._on[ev]) {  scope._on[ev] = []; }
                scope._on[ev].push(cb);
                scope.each(function (){
                    /*this.addEventListener(ev, function(e) {
                        cb.call(scope, e, e.detail, this);
                    }, false);*/
                    this.addEventListener(ev, cb, false);
                });
            });
            return this;
        };
        this.init = function(){
            this.nodes = [];
            this.root = root || document;
            if(this.root instanceof MWElement) {
                this.root = this.root.get(0)
            }
            this._asElement = false;
            this.document =  (this.root.body ? this.root : this.root.ownerDocument);

            options = options || {};

            if(options.nodeName && options.nodeType) {
                this.nodes.push(options);
                this.node = (options);
                options = {};
                this._asElement = true;
            } else if(typeof options === 'string') {
                if(options.indexOf('<') === -1) {

                    this.nodes = Array.prototype.slice.call(this.root.querySelectorAll(options));
                    options = {};
                    this._asElement = true;
                } else if(this.settings && this.settings.content instanceof MWElement) {
                    this.append(this.settings.content);
                }  else if(this.settings && typeof this.settings.content === 'object') {
                    this.append(new MWElement(this.settings.content));
                }else {
                    var el = this._asdom(options);

                    this.nodes = [].slice.call(el.children);
                    this._asElement = true;
                }
            }

            options = options || {};

            var defaults = {
                tag: 'div',
                props: {}
            };

            this.settings = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, defaults, options);

            if(this._asElement) return;
            this.create();
            this.setProps();
        };
        this.init();
    };
const DomQuery = function(options, root){
    return new MWElement(options, root);
};
DomQuery.module = function (name, func) {
    MWElement.prototype[name] = func;
};




const nodeName = 'mw-le-element';
if (window.customElements && !customElements.get(nodeName)) {
    customElements.define( nodeName,
        class extends HTMLElement {
            constructor() {
                super();
            }
        }
    );
}
const ElementManager = (config, root) => {
    if (config instanceof Object && !config.nodeType) {
        config = _object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend({}, config || {}, { tag: config.tag || nodeName });
    }
    return DomQuery(config, root)
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/classes/object.service.js":
/*!********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/object.service.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./userfiles/modules/microweber/api/classes/state.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/state.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "State": () => (/* binding */ State)
/* harmony export */ });
const State = function(options){

    var scope = this;
    var defaults = {
        maxItems: 1000
    };
    this.options = Object.assign({}, defaults, (options || {}));
    this._state = this.options.state || [];
    this._active = null;
    this._activeIndex = -1;

    this.hasNext = false;
    this.hasPrev = false;

    this.state = function(state){
        if(!state){
            return this._state;
        }
        this._state = state;
        return this;
    };
    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]); return this; };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : '';  return this; };


    this.active = function(active){
        if(!active){
            return this._active;
        }
    };

    this.activeIndex = function(activeIndex){
        if(!activeIndex){
            return this._activeIndex;
        }
    };

    this._timeout = null;
    this.timeoutRecord = function(item){
        clearTimeout(this._timeout);
        this._timeout = setTimeout(function(scope, item){
            scope.record(item);
        }, 333, this, item);
    };

    var recentRecordIsEqual = function (item) {
        const curr = scope._state[0];
        if(!curr) return false;
        for (var n in item) {
            if(curr[n] !== item[n]) {
                return false;
            }
        }
        return true;
    };

    this.record = function(item){
        if(this._activeIndex>-1) {
            var i = 0;
            while ( i <  this._activeIndex) {
                this._state.shift();
                i++;
            }
        }
        if (recentRecordIsEqual(item)) {
            return;
        }
        this._state.unshift(item);
        if(this._state.length >= this.options.maxItems) {
            this._state.splice(-1,1);
        }
        this._active = null;
        this._activeIndex = -1;
        this.afterChange(false);
        this.dispatch('record', [this.eventData()]);
        return this;
    };

    this.actionRecord = function(recordGenFunc, action){
        this.record(recordGenFunc());
        action.call();
        this.record(recordGenFunc());
    };

    this.redo = function(){
        this._activeIndex--;
        this._active = this._state[this._activeIndex];
        this.afterChange('stateRedo');
        this.dispatch('redo');
        return this;
    };

    this.undo = function(){
        if(this._activeIndex === -1) {
            this._activeIndex = 1;
        }
        else{
            this._activeIndex++;
        }
        this._active = this._state[this._activeIndex];
        this.afterChange('stateUndo');
        this.dispatch('undo');
        return this;
    };

    this.hasRecords = function(){
        return !!this._state.length;
    };

    this.eventData = function(){
        return {
            hasPrev: this.hasPrev,
            hasNext: this.hasNext,
            active: this.active(),
            activeIndex: this.activeIndex()
        };
    };
    this.afterChange = function(action){
        this.hasNext = true;
        this.hasPrev = true;

        if(action) {
            if(this._activeIndex >= this._state.length) {
                this._activeIndex = this._state.length - 1;
                this._active = this._state[this._activeIndex];
            }
        }

        if(this._activeIndex <= 0) {
            this.hasPrev = false;
        }
        if(this._activeIndex === this._state.length-1 || (this._state.length === 1 && this._state[0].$initial)) {
            this.hasNext = false;
        }

        if(action){

             this.dispatch(action, [this.eventData()]);
        }
        if(action !== false){
            this.dispatch('change', [this.eventData()]);
        }
        return this;
    };

    this.reset = function(){
        this._state = this.options.state || [];
        this.afterChange('reset');
        return this;
    };

    this.clear = function(){
        this._state = [];
        this.afterChange('clear');
        return this;
    };


};


/***/ }),

/***/ "./userfiles/modules/microweber/api/classes/tabs.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/tabs.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Tabs": () => (/* binding */ Tabs)
/* harmony export */ });
class Tabs {
    constructor(options) {
        const defaults = {
            activeClass: 'active'
        }
        this.settings = Object.assign({}, defaults, options || {});
        if(typeof this.settings.tabs === 'string') {
            this.settings.tabs = Array.from(document.querySelectorAll(this.settings.tabs));
        }
        if(typeof this.settings.nav === 'string') {
            this.settings.nav = Array.from(document.querySelectorAll(this.settings.nav))
        }

        this.init();
    }

    unsetAll() {
        this.settings.tabs.forEach(tab => {
            tab.classList.remove(this.settings.activeClass)
        })
        this.settings.nav.forEach(nav => {
            nav.classList.remove(this.settings.activeClass)
        })
    }

    set(index) {
        this.unsetAll();
        if (typeof index === 'number') {
            this.settings.tabs[index].classList.add(this.settings.activeClass)
            this.settings.nav[index].classList.add(this.settings.activeClass)
        }
    }

    init() {
         Array.from(this.settings.nav).forEach((tab, index) => {
            tab.addEventListener('click', e => {
                e.preventDefault();
                 this.set(index);
                console.log(index)
                if(this.settings.onclick) {
                    this.settings.onclick.call(tab, tab, e, index)
                }
            })
        })
    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/uploader.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/uploader.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Uploader": () => (/* binding */ Uploader)
/* harmony export */ });


    const Uploader = function( options ) {
        //var upload = function( url, data, callback, type ) {
        options = options || {};
        options.accept = options.accept || options.filetypes;
        var defaults = {
            multiple: false,
            progress: null,
            element: null,
            url: options.url || (mw.settings.site_url + 'plupload'),
            urlParams: {},
            on: {},
            autostart: true,
            async: true,
            accept: '*',
            chunkSize: 1500000,
        };

        var normalizeAccept = function (type) {
            type = (type || '').trim().toLowerCase();
            if(!type) return '*';
            if (type === 'image' || type === 'images') return '.png,.gif,.jpg,.jpeg,.tiff,.bmp,.svg';
            if (type === 'video' || type === 'videos') return '.mp4,.webm,.ogg,.wma,.mov,.wmv';
            if (type === 'document' || type === 'documents') return '.doc,.docx,.log,.pdf,.msg,.odt,.pages,' +
                '.rtf,.tex,.txt,.wpd,.wps,.pps,.ppt,.pptx,.xml,.htm,.html,.xlr,.xls,.xlsx';

            return '*';
        };

        var scope = this;
        this.settings = $.extend({}, defaults, options);
        this.settings.accept = normalizeAccept(this.settings.accept);

        this.getUrl = function () {
            var params = this.urlParams();
            var empty = mw.tools.isEmptyObject(params);
            return this.url() + (empty ? '' : ('?' + $.param(params)));
        };

        this.urlParam = function (param, value) {
            if(typeof value === 'undefined') {
                return this.settings.urlParams[param];
            }
            this.settings.urlParams[param] = value;
        };

        this.urlParams = function (params) {
            if(!params) {
                return this.settings.urlParams;
            }
            this.settings.urlParams = params;
        };

        this.url = function (url) {
            if(!url) {
                return this.settings.url;
            }
            this.settings.url = url;
        };

        this.create = function () {
            this.input = document.createElement('input');
            this.input.multiple = this.settings.multiple;
            this.input.accept = this.settings.accept;
            this.input.type = 'file';
            this.input.className = 'mw-uploader-input';
            this.input.oninput = function () {
                scope.addFiles(this.files);
            };
        };

        this.files = [];
        this._uploading = false;
        this.uploading = function (state) {
            if(typeof state === 'undefined') {
                return this._uploading;
            }
            this._uploading = state;
        };

        this._validateAccept = this.settings.accept
            .toLowerCase()
            .replace(/\*/g, '')
            .replace(/ /g, '')
            .split(',')
            .filter(function (item) {
                return !!item;
            });
        this.validate = function (file) {
            if(!file) return false;
            var ext = '.' + file.name.split('.').pop().toLowerCase();
            if (this._validateAccept.length === 0) {
                return true;
            }
            for (var i = 0; i < this._validateAccept.length; i++) {
                var item =  this._validateAccept[i];
                if(item === ext) {
                    return true;
                }
                else if(file.type.indexOf(item) === 0) {
                    return true;
                }
            }
            return false;

        };

        this.addFile = function (file) {
            if(this.validate(file)) {
                if(!this.files.length || this.settings.multiple){
                    this.files.push(file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                    $(scope).trigger('FileAdded', file);
                } else {
                    this.files = [file];
                    $(scope).trigger('FileAdded', file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                }
            }
        };

        this.addFiles = function (files) {

            if(!files || !files.length) return;

            if(!this.settings.multiple) {
                files = [files[0]];
            }
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    scope.addFile(files[i]);
                }
                if(this.settings.on.filesAdded) {
                    if(this.settings.on.filesAdded(files) === false) {
                        return;
                    }
                }
                $(scope).trigger('FilesAdded', [files]);
                if(this.settings.autostart) {
                    this.uploadFiles();
                }
            }
        };

        this.remove = function () {
            if(this.input.parentNode) {
                this.input.parentNode.removeChild(this.input);
            }
        }

        this.build = function () {
            if(this.settings.element) {
                this.$element = $(this.settings.element);
                this.element = this.$element[0];

                if(this.element) {
                    this.$element/*.empty()*/.append(this.input);
                    var pos = getComputedStyle(this.element).position;
                    if(pos === 'static') {
                        this.element.style.position = 'relative';
                    }
                    this.element.style.overflow = 'hidden';
                }
            }
        };

        this.show = function () {
            this.$element.show();
        };

        this.hide = function () {
            this.$element.hide();
        };

        this.initDropZone = function () {
            if (!!this.settings.dropZone) {
                mw.$(this.settings.dropZone).each(function () {
                    $(this).on('dragover', function (e) {
                        e.preventDefault();
                    }).on('drop', function (e) {
                        var dt = e.dataTransfer || e.originalEvent.dataTransfer;
                        e.preventDefault();
                        if (dt && dt.items) {
                            var files = [];
                            for (var i = 0; i < dt.items.length; i++) {
                                if (dt.items[i].kind === 'file') {
                                    var file = dt.items[i].getAsFile();
                                    files.push(file);
                                }
                            }
                            scope.addFiles(files);
                        } else  if (dt && dt.files)  {
                            scope.addFiles(dt.files);
                        }
                    });
                });
            }
        };


        this.init = function() {
            this.create();
            this.build();
            this.initDropZone();
        };

        this.init();

        this.removeFile = function (file) {
            var i = this.files.indexOf(file);
            if (i > -1) {
                this.files.splice(i, 1);
            }
        };

        this.uploadFile = function (file, done, chunks, _all, _i) {
            return new Promise(function (resolve, reject) {
                chunks = chunks || scope.sliceFile(file);
                _all = _all || chunks.length;
                _i = _i || 0;
                var chunk = chunks.shift();
                var data = {
                    name: file.name,
                    chunk: _i,
                    chunks: _all,
                    file: chunk,
                };
                _i++;
                $(scope).trigger('uploadStart', [data]);

                scope.upload(data, function (res) {
                    var dataProgress;
                    if(chunks.length) {
                        scope.uploadFile(file, done, chunks, _all, _i).then(function (){
                            if (done) {
                                done.call(file, res);
                            }
                            resolve(file);
                        }, function (xhr){
                             if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr);
                            }
                        });
                        dataProgress = {
                            percent: ((100 * _i) / _all).toFixed()
                        };
                        $(scope).trigger('progress', [dataProgress, res]);
                        if(scope.settings.on.progress) {
                            scope.settings.on.progress(dataProgress, res);
                        }

                    } else {
                        dataProgress = {
                            percent: '100'
                        };
                        $(scope).trigger('progress', [dataProgress, res]);
                        if(scope.settings.on.progress) {
                            scope.settings.on.progress(dataProgress, res);
                        }
                        $(scope).trigger('FileUploaded', [res]);
                        if(scope.settings.on.fileUploaded) {
                            scope.settings.on.fileUploaded(res);
                        }
                        if (done) {
                            done.call(file, res);
                        }
                        resolve(file);
                    }
                }, function (req) {
                    if (req.responseJSON && req.responseJSON.error && req.responseJSON.error.message) {
                        mw.notification.warning(req.responseJSON.error.message);
                    }
                    scope.removeFile(file);
                    reject(req)
                });
            });
        };

        this.sliceFile = function(file) {
            var byteIndex = 0;
            var chunks = [];
            var chunksAmount = file.size <= this.settings.chunkSize ? 1 : ((file.size / this.settings.chunkSize) >> 0) + 1;

            for (var i = 0; i < chunksAmount; i ++) {
                var byteEnd = Math.ceil((file.size / chunksAmount) * (i + 1));
                chunks.push(file.slice(byteIndex, byteEnd));
                byteIndex += (byteEnd - byteIndex);
            }

            return chunks;
        };

        this.uploadFiles = function () {
            if (this.settings.async) {
                 if (this.files.length) {
                    this.uploading(true);
                    var file = this.files[0]
                    scope.uploadFile(file)
                        .then(function (){
                        scope.files.shift();
                        scope.uploadFiles();
                    }, function (xhr){
                            scope.removeFile(file);
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });

                } else {
                    this.uploading(false);
                    scope.input.value = '';
                    if(scope.settings.on.filesUploaded) {
                        scope.settings.on.filesUploaded();
                    }
                    $(scope).trigger('FilesUploaded');

                }
            } else {
                var count = 0;
                var all = this.files.length;
                this.uploading(true);
                this.files.forEach(function (file) {
                    scope.uploadFile(file)
                        .then(function (file){
                            count++;
                            scope.uploading(false);
                            if(all === count) {
                                scope.input.value = '';
                                if(scope.settings.on.filesUploaded) {
                                    scope.settings.on.filesUploaded();
                                }
                                $(scope).trigger('FilesUploaded');
                            }
                        }, function (xhr){
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });
                });
            }
        };


        this.upload = function (data, done, onFail) {
            if (!this.settings.url) {
                return;
            }
            var pdata = new FormData();
            $.each(data, function (key, val) {
                pdata.append(key, val);
            });
            if(scope.settings.on.uploadStart) {
                if (scope.settings.on.uploadStart(pdata) === false) {
                    return;
                }
            }

            var xhrOptions = {
                url: this.getUrl(),
                type: 'post',
                processData: false,
                contentType: false,
                data: pdata,
                success: function (data, statusText, xhrReq) {

                    if(xhrReq.status === 200) {
                        if (data && (data.form_data_required || data.form_data_module)) {
                            mw.extradataForm(xhrOptions, data, mw.jqxhr);
                        }
                        else {
                            scope.removeFile(data.file);
                            if(done) {
                                done.call(data, data);
                            }
                        }
                    }

                },
                error:  function(  xhrReq, edata, statusText ) {
                    scope.removeFile(data.file);
                    if (onFail) {
                        onFail.call(xhrReq, xhrReq);
                    }
                },
                dataType: 'json',
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            var percent = (event.loaded / event.total) * 100;
                            if(scope.settings.on.progressNative) {
                                scope.settings.on.progressNative(percent, event);
                            }
                            $(scope).trigger('progressNative', [percent, event]);
                        }
                    });
                    return xhr;
                }
            };

            return mw.jqxhr(xhrOptions);
        };
    };

    mw.upload = function (options) {
        return new Uploader(options);
    };





/***/ }),

/***/ "./userfiles/modules/microweber/api/editor/check.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/check.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Check": () => (/* binding */ Check)
/* harmony export */ });
const Check = {
    isBold: node => parseFloat(getComputedStyle(node).fontWeight) > 500,

}


/***/ }),

/***/ "./userfiles/modules/microweber/api/system/filepicker.js":
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/filepicker.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "FilePicker": () => (/* binding */ FilePicker)
/* harmony export */ });
/* harmony import */ var _core_uploader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/uploader */ "./userfiles/modules/microweber/api/core/uploader.js");
/* harmony import */ var _classes_tabs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../classes/tabs */ "./userfiles/modules/microweber/api/classes/tabs.js");



const lang = function (key) {
    return key;
}

const FilePicker = function (options) {
    options = options || {};
    var scope = this;
    var defaults = {
        components: [
            {type: 'desktop', label: lang('My computer')},
            {type: 'url', label: lang('URL')},
            {type: 'server', label: lang('Uploaded')},
            {type: 'library', label: lang('Media library')}
        ],
        nav: 'tabs', // 'tabs | 'dropdown',
        hideHeader: false,
        dropDownTargetMode: 'self', // 'self', 'dialog'
        element: null,
        footer: true,
        okLabel: lang('OK'),
        cancelLabel: lang('Cancel'),
        uploaderType: 'big', // 'big' | 'small'
        confirm: function (data) {

        },
        cancel: function () {

        },
        label: lang('Media'),
        autoSelect: true, // depending on the component
        boxed: false,
        multiple: false
    };



    this.settings = $.extend(true, {}, defaults, options);

    this.$root = $('<div class="'+ (this.settings.boxed ? ('card mb-3') : '') +' mw-filepicker-root"></div>');
    this.root = this.$root[0];

    $.each(this.settings.components, function (i) {
        this['index'] = i;
    });


    this.components = {
        _$inputWrapper: function (label) {
            var html = '<div class="mw-ui-field-holder">' +
                /*'<label>' + label + '</label>' +*/
                '</div>';
            return mw.$(html);
        },
        url: function () {
            var $input = $('<input class="mw-ui-field w100" placeholder="http://example.com/image.jpg">');
            scope.$urlInput = $input;
            var $wrap = this._$inputWrapper(scope._getComponentObject('url').label);
            $wrap.append($input);
            $input.before('<label class="mw-ui-label">'+lang('Insert file url')+'</label>');
            $input.on('input', function () {
                var val = this.value.trim();
                scope.setSectionValue(val || null);
                if(scope.settings.autoSelect) {

                    scope.result();
                }
            });
            return $wrap[0];
        },
        _setdesktopType: function () {
            var $zone;
            if(scope.settings.uploaderType === 'big') {
                $zone = $('<div class="mw-file-drop-zone">' +
                    '<div class="mw-file-drop-zone-holder">' +
                    '<div class="mw-file-drop-zone-img"></div>' +
                    '<div class="mw-ui-progress-small"><div class="mw-ui-progress-bar" style="width: 0%"></div></div>' +
                    '<span class="mw-ui-btn mw-ui-btn-rounded mw-ui-btn-info">'+lang('Add file')+'</span> ' +
                    '<p>'+lang('or drop files to upload')+'</p>' +
                    '</div>' +
                    '</div>');
            } else if(scope.settings.uploaderType === 'small') {
                $zone = $('<div class="mw-file-drop-zone mw-file-drop-zone-small mw-file-drop-square-zone"> <div class="mw-file-drop-zone-holder"> <span class="mw-ui-link">'+lang('Add file')+'</span> ' +
                    '<p>'+lang('or drop files to upload')+'</p>' +
                    '</div>' +
                    '</div>')
            }
            var $el = $(scope.settings.element).eq(0);
            $el.removeClass('mw-filepicker-desktop-type-big mw-filepicker-desktop-type-small');
            $el.addClass('mw-filepicker-desktop-type-' + scope.settings.uploaderType);
            scope.uploaderHolder.empty().append($zone);
        },
        desktop: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('desktop').label);
            scope.uploaderHolder = mw.$('<div class="mw-uploader-type-holder"></div>');
            this._setdesktopType();
            $wrap.append(scope.uploaderHolder);
            scope.uploader = new _core_uploader__WEBPACK_IMPORTED_MODULE_0__.Uploader({
                element: $wrap[0],
                multiple: scope.settings.multiple,
                accept: scope.settings.accept,
                on: {
                    progress: function (prg) {
                        scope.uploaderHolder.find('.mw-ui-progress-bar').stop().animate({width: prg.percent + '%'}, 'fast');
                    },
                    fileUploadError: function (file) {
                        $(scope).trigger('FileUploadError', [file]);
                    },
                    fileAdded: function (file) {
                        $(scope).trigger('FileAdded', [file]);
                        scope.uploaderHolder.find('.mw-ui-progress-bar').width('1%');
                    },
                    fileUploaded: function (file) {
                        scope.setSectionValue(file);

                        $(scope).trigger('FileUploaded', [file]);
                        if (scope.settings.autoSelect) {
                            scope.result();
                        }
                        if (scope.settings.fileUploaded) {
                            scope.settings.fileUploaded(file);
                        }
                        if (!scope.settings.multiple) {
                            mw.notification.success('File uploaded');
                            scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                        }
                        // scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                    }
                }
            });
            return $wrap[0];
        },
        server: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('server').label);
            /*mw.load_module('files/admin', $wrap, function () {

            }, {'filetype':'images'});*/

            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('server');
                if (type === 'server') {
                    mw.top().tools.loading(el, true);
                    var fr = document.createElement('iframe');
                    fr.src =  mw.external_tool('module_dialog') + '?module=files/admin';
                    mw.tools.iframeAutoHeight(fr);
                    fr.style.width = '100%';
                    fr.scrolling = 'no';
                    fr.frameBorder = '0';
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.scrolling = 'auto';

                    $wrap.append(fr);
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.document.body.classList.remove('mw-external-loading');
                        this.contentWindow.$(this.contentWindow.document.body).on('click', '.mw-browser-list-file', function () {
                            var url = this.href;
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            });

            return $wrap[0];
        },
        library: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('library').label);
            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('library');
                if (type === 'library') {
                    mw.tools.loading(el, true);
                    var fr = mw.top().tools.moduleFrame('pictures/media_library');
                    $wrap.append(fr);
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.mw.on.hashParam('select-file', function (pval) {
                            var url = pval.toString();
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            })

            /*mw.load_module('pictures/media_library', $wrap);*/
            return $wrap[0];
        }
    };

    this.hideUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).hide();
    };

    this.showUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).show();
    };

    this.desktopUploaderType = function (type) {
        if(!type) return this.settings.uploaderType;
        this.settings.uploaderType = type;
        this.components._setdesktopType();
    };

    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });


    this._navigation = null;
    this.__navigation_first = [];

    this.navigation = function () {
        this._navigationHeader = document.createElement('div');
        this._navigationHeader.className = 'mw-filepicker-component-navigation-header ' + (this.settings.boxed ? 'card-header no-border' : '');
        if (this.settings.hideHeader) {
            this._navigationHeader.style.display = 'none';
        }
        if (this.settings.label) {
            this._navigationHeader.innerHTML = '<h6><strong>' + this.settings.label + '</strong></h6>';
        }
        this._navigationHolder = document.createElement('div');
        if(this.settings.nav === false) {

        }
        else if(this.settings.nav === 'tabs') {
            var ul = $('<nav class="mw-ac-editor-nav" />');
            this.settings.components.forEach(function (item) {
                ul.append('<a href="javascript:;" class="mw-ui-btn-tab" data-type="'+item.type+'">'+item.label+'</a>');
            });
            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(ul[0]);
            setTimeout(function () {
                scope._tabs = new _classes_tabs__WEBPACK_IMPORTED_MODULE_1__.Tabs({
                    nav: ul[0].querySelectorAll('a'),
                    tabs: scope.$root[0].querySelectorAll('.mw-filepicker-component-section'),
                    activeClass: 'active',
                    onclick: function (el, event, i) {
                        if(scope.__navigation_first.indexOf(i) === -1) {
                            scope.__navigation_first.push(i);
                            $(scope).trigger('$firstOpen', [el, this.dataset.type]);
                        }
                        scope.manageActiveSectionState();
                    }
                });
            }, 78);
        } else if(this.settings.nav === 'dropdown') {
            var select = $('<select class="selectpicker btn-as-link" data-style="btn-sm" data-width="auto" data-title="' + lang('Add file') + '"/>');
            scope._select = select;
            this.settings.components.forEach(function (item) {
                select.append('<option class="nav-item" value="'+item.type+'">'+item.label+'</option>');
            });

            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(select[0]);
            select.on('changed.bs.select', function (e, xval) {
                var val = select.selectpicker('val');
                var componentObject = scope._getComponentObject(val) ;
                var index = scope.settings.components.indexOf(componentObject);
                var items = $('.mw-filepicker-component-section', scope.$root);
                if(scope.__navigation_first.indexOf(val) === -1) {
                    scope.__navigation_first.push(val);
                    $(scope).trigger('$firstOpen', [items.eq(index)[0], val]);
                }
                if(scope.settings.dropDownTargetMode === 'dialog') {
                    var temp = document.createElement('div');
                    var item = items.eq(index);
                    item.before(temp);
                    item.show();
                    var footer = false;
                    if (scope._getComponentObject('url').index === index ) {
                        footer =  document.createElement('div');
                        var footerok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + scope.settings.okLabel + '</button>');
                        var footercancel = $('<button type="button" class="mw-ui-btn">' + scope.settings.cancelLabel + '</button>');
                        footerok.disabled = true;
                        footer.appendChild(footercancel[0]);
                        footer.appendChild(footerok[0]);
                        footer.appendChild(footercancel[0]);
                        footercancel.on('click', function () {
                            scope.__pickDialog.remove();
                        });
                        footerok.on('click', function () {
                            scope.setSectionValue(scope.$urlInput.val().trim() || null);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                            // scope.__pickDialog.remove();
                        });
                    }

                    scope.__pickDialog = mw.top().dialog({
                        overlay: true,
                        content: item,
                        beforeRemove: function () {
                            $(temp).replaceWith(item);
                            item.hide();
                            scope.__pickDialog = null;
                        },
                        footer: footer
                    });
                } else {
                    items.hide().eq(index).show();
                }
            });
        }
        this.$root.prepend(this._navigationHolder);

    };
    this.__displayControllerByTypeTime = null;

    this.displayControllerByType = function (type) {
        type = (type || '').trim();
        var item = this._getComponentObject(type) ;
        clearTimeout(this.__displayControllerByTypeTime);
        this.__displayControllerByTypeTime = setTimeout(function () {
            if(scope.settings.nav === 'tabs') {
                mw.$('[data-type="'+type+'"]', scope.$root).click();
            } else if(scope.settings.nav === 'dropdown') {
                $(scope._select).selectpicker('val', type);
            }
        }, 10);
    };



    this.footer = function () {
        if(!this.settings.footer || this.settings.autoSelect) return;
        this._navigationFooter = document.createElement('div');
        this._navigationFooter.className = 'mw-ui-form-controllers-footer mw-filepicker-footer ' + (this.settings.boxed ? 'card-footer' : '');
        this.$ok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + this.settings.okLabel + '</button>');
        this.$cancel = $('<button type="button" class="mw-ui-btn ">' + this.settings.cancelLabel + '</button>');
        this._navigationFooter.appendChild(this.$cancel[0]);
        this._navigationFooter.appendChild(this.$ok[0]);
        this.$root.append(this._navigationFooter);
        this.$ok[0].disabled = true;
        this.$ok.on('click', function () {
            scope.result();
        });
        this.$cancel.on('click', function () {
            scope.settings.cancel()
        });
    };

    this.result = function () {
        var activeSection = this.activeSection();
        if(this.settings.onResult) {
            this.settings.onResult.call(this, activeSection._filePickerValue);
        }
        $(scope).trigger('Result', [activeSection._filePickerValue]);
    };

    this.getValue = function () {
        return this.activeSection()._filePickerValue;
    };

    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this._sections = [];
    this.buildComponentSection = function () {
        var main = mw.$('<div class="'+(this.settings.boxed ? 'card-body' : '') +' mw-filepicker-component-section"></div>');
        this.$root.append(main);
        this._sections.push(main[0]);
        return main;
    };

    this.buildComponent = function (component) {
        if(this.components[component.type]) {
            return this.components[component.type]();
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if(component){
                var sec = scope.buildComponentSection();
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        this.buildComponents();
        if(this.settings.nav === 'dropdown') {
            $('.mw-filepicker-component-section', scope.$root).hide().eq(0).show();
        }
        this.footer();
    };

    this.init = function () {
        this.build();
        if (this.settings.element) {
            $(this.settings.element).eq(0).append(this.$root);
        }
        if($.fn.selectpicker) {
            $('select', scope.$root).selectpicker();
        }
    };

    this.hide = function () {
        this.$root.hide();
    };
    this.show = function () {
        this.$root.show();
    };

    this.activeSection = function () {
        return $(this._sections).filter(function (){
            return $(this).css('display') !== 'none';
        })[0];
    };

    this.setSectionValue = function (val) {
        var activeSection = this.activeSection();
        if(activeSection) {
            activeSection._filePickerValue = val;
        }

        if(scope.__pickDialog) {
            scope.__pickDialog.remove();
        }
        this.manageActiveSectionState();
    };
    this.manageActiveSectionState = function () {
        // if user provides value for more than one section, the active value will be the one in the current section
        var activeSection = this.activeSection();
        if (this.$ok && this.$ok[0]) {
            this.$ok[0].disabled = !(activeSection && activeSection._filePickerValue);
        }
    };

    this.init();
}


mw.filePicker = FilePicker;


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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/editor.js ***!
  \***********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");
/* harmony import */ var _classes_state__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../classes/state */ "./userfiles/modules/microweber/api/classes/state.js");
/* harmony import */ var _classes_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../classes/css */ "./userfiles/modules/microweber/api/classes/css.js");
/* harmony import */ var _classes_dom__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../classes/dom */ "./userfiles/modules/microweber/api/classes/dom.js");
/* harmony import */ var _classes_dialog__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../classes/dialog */ "./userfiles/modules/microweber/api/classes/dialog.js");









var EditorPredefinedControls = {
    'default': [
        [ 'bold', 'italic', 'underline' ],
    ],
    smallEditorDefault: [
        ['bold', 'italic', '|', 'link']
    ]
};

class EditorCore {
    constructor() {
        this.defaults = {
            regions: null,
            notEditableSelector: '.module',
            document: document,
            executionDocument: document,
            mode: 'div', // iframe | div | document
            controls: 'default',
            smallEditor: false,
            scripts: [],
            cssFiles: [],
            content: '',
            url: null,
            skin: 'default',
            state: null,
            iframeAreaSelector: null,
            activeClass: 'active-control',
            interactionControls: [
                'image', 'linkTooltip', 'tableManager'
            ],
            language: 'en',
            rootPath:  'http://localhost/mw2/userfiles/modules/microweber/api/editor',
            editMode: 'normal', // normal | liveedit
            bar: null,
        };
    }
}


class Editor extends EditorCore  {

    constructor(options) {
        super();
        options = options || {};



        this.settings = Object.assign({}, this.defaults, options);

        if (typeof this.settings.controls === 'string') {
            this.settings.controls = EditorPredefinedControls[this.settings.controls] || EditorPredefinedControls.default;
        }

        if(!!this.settings.smallEditor) {
            if(this.settings.smallEditor === true) {
                this.settings.smallEditor = EditorPredefinedControls.smallEditorDefault;
            } else if (typeof this.settings.smallEditor === 'string') {
                this.settings.smallEditor = EditorPredefinedControls[this.settings.smallEditor] || EditorPredefinedControls.smallEditorDefault;
            }
        }

        this.document = this.settings.document;
        this.executionDocument = this.settings.executionDocument;

        this.actionWindow = this.document.defaultView;
        this.executionWindow = this.executionDocument.defaultView;

        if(!this.settings.selector && this.settings.element){
            this.settings.selector = this.settings.element;
        }

        if(!this.settings.selector && this.settings.mode === 'document'){
            this.settings.selector = this.document.body;
        }
        if(!this.settings.selector){
            console.warn('MWEditor - selector not specified');
            return;
        }

        this.settings.selectorNode = $(this.settings.selector)[0];

        if (this.settings.selectorNode) {
            this.settings.selectorNode.__MWEditor = this;
        }

        this.settings.isTextArea = this.settings.selectorNode.nodeName && this.settings.selectorNode.nodeName === 'TEXTAREA';
        this.dialog = function (options) {
            options.document = this.document;

            return new _classes_dialog__WEBPACK_IMPORTED_MODULE_4__.Dialog(options)
        }

        this.selection = this.getSelection();

        this._interactionTime = new Date().getTime();

        this.interactionControls = [];
        this._registerChangeTimer = null;
        this.controls = [];
        this.api = MWEditor.api(this);

        this._addControllerGroups = [];
        this._initInputRecordTime = null;
        this.interactionData = {};
        this.init();

    }



    getSelection () {
        return this.actionWindow.getSelection();
    };


    createInteractionControls () {
        this.settings.interactionControls.forEach((ctrl) => {
            if (MWEditor.interactionControls[ctrl]) {
                var int = new MWEditor.interactionControls[ctrl](this, this);
                if(!int.element){
                    int.element = int.render();
                }
                this.actionWindow.document.body.appendChild(int.element.node);
                this.interactionControls.push(int);
            }
        });
    };

    lang (key)  {
        if (MWEditor.i18n[this.settings.language] && MWEditor.i18n[this.settings.language][key]) {
            return  MWEditor.i18n[this.settings.language][key];
        }
        //console.warn(key + ' is not specified for ' + this.settings.language + ' language');
        return key;
    }



    addDependencies (obj){
        this.controls.forEach((ctrl) => {
            if (ctrl.dependencies) {
                ctrl.dependencies.forEach((dep) => {
                    this.addDependency(dep);
                });
            }
        });
        this.interactionControls.forEach( (int) => {
            if (int.dependencies) {
                int.dependencies.forEach( (dep) => {
                    this.addDependency(dep);
                });
            }
        });
        var node = this.actionWindow.document.createElement('link');
        node.href = this.settings.rootPath + '/area-styles.css';
        node.type = 'text/css';
        node.rel = 'stylesheet';
        this.actionWindow.document.body.appendChild(node);
    };
    addDependency (obj) {
        var targetWindow = obj.targetWindow || this.actionWindow;
        if (!type) {
            type = url.split('.').pop();
        }
        if(!type || !url) return;
        var node;
        if(type === 'css') {
            node = targetWindow.document.createElement('link');
            node.rel = 'stylesheet';
            node.href = url;
            node.type = 'text/css';
        } else if(type === 'js') {
            node = targetWindow.document.createElement('script');
            node.src = url;
        }
        targetWindow.document.body.appendChild(node);
    };

    interactionControlsRun (data) {
        this.interactionControls.forEach(function (ctrl) {
            ctrl.interact(data);
        });
    };

    _observe (e) {
        e = e || {type: 'action'};
        var max = 78;
        var eventIsActionLike = e.type === 'click' || e.type === 'execCommand' || e.type === 'keydown' || e.type === 'action';
        var event = e.originaleEvent ? e.originaleEvent : e;
        var localTarget = event.target;

        if (!e.target) {
            localTarget = this.getSelection().focusNode;
         }
        var wTarget = localTarget;
        if(eventIsActionLike) {
            var shouldCloseSelects = false;
            while (wTarget) {
                var cc = wTarget.classList;
                if(cc) {
                    if(cc.contains('mw-editor-controller-component-select')) {
                        break;
                    } else if(cc.contains('mw-bar-control-item-group')) {
                        break;
                    } else if(cc.contains('mw-editor-area')) {
                        shouldCloseSelects = true;
                        break;
                    } else if(cc.contains('mw-editor-frame-area')) {
                        shouldCloseSelects = true;
                        break;
                    } else if(cc.contains('mw-editor-wrapper')) {
                        shouldCloseSelects = true;
                        break;
                    }
                }
                wTarget = wTarget.parentNode;
            }
            if(shouldCloseSelects) {
                MWEditor.core._preSelect();
            }
        }
        var time = new Date().getTime();
        if(eventIsActionLike || (time - this._interactionTime) > max){
            if (e.pageX) {
                console.log(this.interactionData, this)
                this.interactionData.pageX = e.pageX;
                this.interactionData.pageY = e.pageY;
            }
            this._interactionTime = time;
            this.selection = this.getSelection();
            if (this.selection.rangeCount === 0) {
                return;
            }
            var target = this.api.elementNode( this.selection.getRangeAt(0).commonAncestorContainer );
            var css = (0,_classes_css__WEBPACK_IMPORTED_MODULE_2__.CSSParser)(target);
            var api = this.api;


            var iterData = {
                selection: this.selection,
                target: target,
                localTarget: localTarget,
                isImage: localTarget.nodeName === 'IMG' || target.nodeName === 'IMG',
                css: css.get,
                cssNative: css.css,
                event: event,
                api: api,
                this: this,
                isEditable: this.api.isSelectionEditable(),
                eventIsActionLike: eventIsActionLike,
            };

            this.interactionControlsRun(iterData);
            this.controls.forEach((ctrl) => {
                if(ctrl.checkSelection) {
                    ctrl.checkSelection({
                        selection: this.selection,
                        controller: ctrl,
                        target: target,
                        css: css.get,
                        cssNative: css.css,
                        api: api,
                        eventIsActionLike: eventIsActionLike,
                        this: this,
                        isEditable: this.api.isSelectionEditable()
                    });
                }
            });
        }
    };

    initInteraction () {
        var ait = 100,
            currt = new Date().getTime();

        $(this.actionWindow.document).on('selectionchange', (e) => {
             $(this).trigger('selectionchange', [{
                event: e,
                interactionData: this.interactionData
            }]);
        });

        $(this).on('execCommand', (e) => {
            this._observe(e);
        });
        this.state.on('undo', (e) => {
            setTimeout(() => {
                this._observe(e);
            }, 123);
        });
        this.state.on('redo', (e) => {
            var active = this.state.active();
            var target = active ? active.target : this.getSelection().focusNode();
            setTimeout(() => {
                this._observe(e);
            }, 123);
        });

        this.createInteractionControls();
    };


    preventEvents () {
        if(!this._preventEvents) {
            this._preventEvents = [];
        }

        var node;
        if(this.area && this._preventEvents.indexOf(this.area.node) === -1) {
            this._preventEvents.push(this.area.node);
            node = this.area.node;
        } else if(this.$iframeArea && this._preventEvents.indexOf(this.$iframeArea[0]) === -1) {
            this._preventEvents.push(this.$iframeArea[0]);
            node = this.$iframeArea[0];
        }
        var ctrlDown = false;
        var ctrlKey = 17, vKey = 86, cKey = 67, zKey = 90;
        node.onkeydown = (e) => {
            if (e.keyCode === ctrlKey || e.keyCode === 91) {
                ctrlDown = true;
            }
            if ((ctrlDown && e.keyCode === zKey) /*|| (ctrlDown && e.keyCode === vKey)*/ || (ctrlDown && e.keyCode === cKey)) {
                e.preventDefault();
                return false;
            }
        };
        node.onkeyup = (e) => {
            if (e.keyCode === 17 || e.keyCode === 91) {
                ctrlDown = false;
            }
        };
    };
    initState () {
        this.state = this.settings.state || (new _classes_state__WEBPACK_IMPORTED_MODULE_1__.State());
    };

    controllerActive (node, active) {
        node.classList[active ? 'add' : 'remove'](this.settings.activeClass);
    };

    createFrame () {
        this.frame = this.document.createElement('iframe');
        this.frame.className = 'mw-editor-frame';
        this.frame.allow = 'accelerometer; autoplay; encrypted-media; gyrothis; picture-in-picture';
        this.frame.allowFullscreen = true;
        this.frame.scrolling = "yes";
        this.frame.width = "100%";
        this.frame.frameBorder = "0";
        if (this.settings.url) {
            this.frame.src = this.settings.url;
        } else {

        }

        $(this.frame).on('load', () => {
            if (!this.settings.iframeAreaSelector) {
                var area = document.createElement('div');
                area.style.outline = 'none';
                area.className = 'mw-editor-frame-area';
                this.settings.iframeAreaSelector =  '.' + area.className;
                this.contentWindow.document.body.append(area);
                area.style.minHeight = '100px';
            }
            this.$iframeArea = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.settings.iframeAreaSelector, this.contentWindow.document);

            this.$iframeArea.html(this.settings.content || '');
            this.$iframeArea.on('input', () => {
                this.registerChange();
            });
            this.actionWindow = this.contentWindow;
            this.$editArea = this.$iframeArea;
            mw.tools.iframeAutoHeight(this.frame);

            this.preventEvents();
            $(this).trigger('ready');
        });
        this.wrapper.appendChild(this.frame);
    };

    createWrapper () {
        this.wrapper = this.document.createElement('div');
        this.wrapper.className = 'mw-editor-wrapper mw-editor-' + this.settings.skin;
    };

    _syncTextArea (content) {

        if(this.$editArea){
            $('[contenteditable]', this.$editArea).removeAttr('contenteditable');
        }

        content = content || this.$editArea.html();
        if (this.settings.isTextArea) {
            $(this.settings.selectorNode).val(content);
            $(this.settings.selectorNode).trigger('change');
        }
    };


    registerChange (content) {
        clearTimeout(this._registerChangeTimer);
        this._registerChangeTimer = setTimeout(() => {
            content = content || this.$editArea.html();
            this._syncTextArea(content);
            $(this).trigger('change', [content]);
        }, 78);
    };

    createArea () {
        var content = this.settings.content || '';
        if(!content && this.settings.isTextArea) {
            content = this.settings.selectorNode.value;
        }
        this.area = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: { className: 'mw-editor-area', innerHTML: content }
        });
        this.area.node.contentEditable = true;

        this.area.node.oninput = () => {
            this.registerChange();
        };
        this.wrapper.appendChild(this.area.node);
        this.$editArea = this.area;
        this.preventEvents();
        $(this).trigger('ready');
    };

    documentMode() {
        if(!this.settings.regions) {
            console.warn('Regions are not defined in Document mode.');
            return;
        }
        this.wrapper.className += ' mw-editor-wrapper-document-mode';
        (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.document.body).append(this.wrapper)
        this.document.body.mwEditor = this;
        $(this).trigger('ready');
    };

    setContent (content, trigger) {
        if(typeof trigger === 'undefined'){
            trigger = true;
        }
        this.$editArea.html(content);
        if(trigger){
            this.registerChange(content);
        }
    };

    nativeElement (node) {
        return node.node ? node.node : node;
    };


    addControllerGroup (obj, row, bar) {
        if(!bar) {
            bar = 'bar';
        }
        var group = obj.group;
        var id = mw.id('mw.editor-group-');
        var el = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'mw-bar-control-item mw-bar-control-item-group',
                id:id
            }
        });

        var groupel = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props:{
                    className: 'mw-bar-control-item-group-contents'
                }
            });

        var icon = MWEditor.core.button({
            tag:'span',
            props: {
                className: ' mw-editor-group-button',
                innerHTML: '<span class="mw-editor-group-button-caret"></span>'
            }
        });
        if(group.icon) {
            icon.prepend('<span class="' + group.icon + ' mw-editor-group-button-icon"></span>');
            icon.on('click', () => {
                MWEditor.core._preSelect(this.parentNode);
                this.parentNode.classList.toggle('active');
            });

        } else if(group.controller) {
            if(this.controllers[group.controller]){
                var ctrl = new this.controllers[group.controller](this, this.api, this);
                this.controls.push(ctrl);
                icon.prepend(ctrl.element);
                (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(icon.get(0).querySelector('.mw-editor-group-button-caret')).on('click', () => {
                    MWEditor.core._preSelect(this.parentNode.parentNode);
                    this.parentNode.parentNode.classList.toggle('active');
                });
            } else if(this.controllersHelpers[group.controller]){
                groupel.append(this.controllersHelpers[group.controller]());
            }
        }
        el.append(icon);

        groupel.on('click', () => {
            MWEditor.core._preSelect();
        });

        var media;
        obj.group.when = obj.group.when || 9999;
        // at what point group buttons become like dropdown - by default it's always a dropdown
        if (obj.group.when) {
            if (typeof obj.group.when === 'number') {
                media = '(max-width: ' + obj.group.when + 'px)';
            } else {
                media = obj.group.when;
            }
        }



        el.append(groupel);
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        group.controls.forEach((name) => {
            if(this.controllers[name]){
                var ctrl = new this.controllers[name](this, this.api, this);
                this.controls.push(ctrl);
                groupel.append(ctrl.element);
            } else if(this.controllersHelpers[name]){
                groupel.append(this.controllersHelpers[name]());
            }
        });

        this[bar].add(el, row);

        this._addControllerGroups.push({
            el: el,
            row: row,
            obj: obj,
            media: media
        });
        return el;
    };

    controlGroupManager () {
        var check = () => {
            var i = 0, l = this._addControllerGroups.length;
            for ( ; i< l ; i++) {
                var item = this._addControllerGroups[i];
                var media = item.media;
                if(media) {
                    var match = this.document.defaultView.matchMedia(media);
                     item.el[match.matches ? 'addClass' : 'removeClass']('mw-editor-control-group-media-matches');
                }
            }
        };
        $(window).on('load resize orientationchange', () => {
            check();
        });
        check();
    };

    addController (name, row, bar) {
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        if (!bar) {
            bar = 'bar';
        }
        if(this.controllers[name]){
            var ctrl = new this.controllers[name](this, this.api, this);
            if (!ctrl.element) {
                ctrl.element = ctrl.render();
            }

            this.controls.push(ctrl);
            this[bar].add(ctrl.element, row);
        } else if(this.controllersHelpers[name]){
            this[bar].add(this.controllersHelpers[name](), row);
        }
    };

    createSmallEditor ()  {
        if (!this.settings.smallEditor) {
            return;
        }
        this.smallEditor = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            props: {
                className: 'mw-small-editor mw-small-editor-skin-' + this.settings.skin
            }
        });

        this.smallEditorBar = mw.bar();

        this.smallEditor.hide();
        this.smallEditor.append(this.smallEditorBar.bar);
        for (var i1 = 0; i1 < this.settings.smallEditor.length; i1++) {
            var item = this.settings.smallEditor[i1];
            this.smallEditorBar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    this.addController(item[i2], i1, 'smallEditorBar');
                } else if( typeof item[i2] === 'object') {
                    this.addControllerGroup(item[i2], i1, 'smallEditorBar');
                }
            }
        }
        this.$editArea.on('mouseup touchend',  (e, data) => {
            if (this.selection && !this.selection.isCollapsed) {
                if(!_classes_dom__WEBPACK_IMPORTED_MODULE_3__.DomService.hasParentsWithClass(e.target, 'mw-bar')){
                    this.smallEditor.css({
                        top: this.interactionData.pageY - this.smallEditor.height() - 20,
                        left: this.interactionData.pageX,
                        display: 'block'
                    });
                }
            } else {
                this.smallEditor.hide();
            }
        });
        this.actionWindow.document.body.appendChild(this.smallEditor.node);
    };
    createBar () {
        this.bar = mw.settings.bar || mw.bar();
        for (var i1 = 0; i1 < this.settings.controls.length; i1++) {
            var item = this.settings.controls[i1];
            this.bar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    this.addController(item[i2], i1);
                } else if( typeof item[i2] === 'object') {
                    this.addControllerGroup(item[i2], i1);
                }
            }
        }
        this.wrapper.appendChild(this.bar.bar);
    };

    _onReady  ()  {

        $(this).on('ready', () => {
            this.initInteraction();
            this.api.execCommand('enableObjectResizing', false, 'false');
            this.api.execCommand('2D-Position', false, false);
            this.api.execCommand("enableInlineTableEditing", null, false);

            if(!this.state.hasRecords()){
                this.state.record({
                    $initial: true,
                    target: this.$editArea.get(0),
                    value: this.$editArea.get(0).innerHTML
                });
            }
            this.settings.regions = this.settings.regions || this.$editArea;
            this.$editArea.on('touchstart touchend click keydown execCommand mousemove touchmove', e => this._observe);

            Array.from(this.actionWindow.document.querySelectorAll(this.settings.regions)).forEach((el) => {
                el.contentEditable = false;
                (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(el).on('mousedown touchstart', (e) =>{

                    e.stopPropagation();
                    var curr = _classes_dom__WEBPACK_IMPORTED_MODULE_3__.DomService.firstParentOrCurrent(e.target, this.settings.regions);
                    Array.from(this.actionWindow.document.querySelectorAll(this.settings.regions)).forEach(function (el){
                        el.contentEditable = el === curr;
                    });
                })
            })

            Array.from(this.actionWindow.document.querySelectorAll(this.settings.notEditableSelector)).forEach(function (el){
                el.contentEditable = false;
            })


            if (this.settings.editMode === 'liveedit') {
                this.liveEditMode();
            }
            var css = {};
            if(this.settings.minHeight) {
                css.minHeight = this.settings.minHeight;
            }
            if(this.settings.maxHeight) {
                css.maxHeight = this.settings.maxHeight;
            }
            if(this.settings.height) {
                css.height = this.settings.height;
            }
            if(this.settings.minWidth) {
                css.minWidth = this.settings.minWidth;
            }
            if(this.settings.maxWidth) {
                css.maxWidth = this.settings.maxWidth;
            }
            if(this.settings.width) {
                css.width = this.settings.width;
            }
            this.$editArea.css(css);
            this.addDependencies();
            this.createSmallEditor();

        });
    };

    liveEditMode () {
        this.liveedit = MWEditor.liveeditMode(this.actionWindow.document.body, this);
    };


    _initInputRecord () {
        $(this).on('change',   (e, html) => {
            clearTimeout(this._initInputRecordTime);
            this._initInputRecordTime = setTimeout(() => {
                this.state.record({
                    target: this.$editArea.get(0),
                    value: html
                });
            }, 600);

        });
    };

    __insertEditor (){
        if (this.settings.isTextArea) {
            var el = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.settings.selector);
            el.get(0).mwEditor = this;
            el.hide();
            var areaWrapper = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)();
            areaWrapper.node.mwEditor = this;
            el.after(areaWrapper.node);
            areaWrapper.append(this.wrapper);
        } else {
            (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.settings.selector).append(this.wrapper).get(0).mwEditor = this;
        }
    };

    init () {
        this.controllers = MWEditor.controllers;
        this.controllersHelpers = MWEditor.controllersHelpers;
        this.initState();

        this.createWrapper();
        this.createBar();

        if (this.settings.mode === 'div') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        } else if (this.settings.mode === 'document') {
            this.documentMode();
        }

        this._onReady();

        if(this.settings.iframe) {
            this.actionWindow = this.settings.iframe.contentWindow;
            this.executionDocument = this.settings.iframe.contentWindow.document;
            this.$iframeArea = $(this.settings.iframeAreaSelector, this.executionDocument);
             if(this.executionDocument.readyState === 'complete') {
                this.$iframeArea = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.settings.iframeAreaSelector, this.executionDocument);
                this.$editArea = this.$iframeArea;
                $(this).trigger('ready');
            } else {
                this.actionWindow.addEventListener('load', () => {
                    this.$iframeArea = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.settings.iframeAreaSelector, this.executionDocument);
                    this.$editArea = this.$iframeArea;
                     $(this).trigger('ready');
                })
            }

        }



        if (this.settings.mode !== 'document') {
            this._initInputRecord();
            this.__insertEditor();
        }
        this.controlGroupManager();

    };


 }

if (window.mw) {
   mw.Editor = function (options){
       options = options || {};
       if(!options.selector && options.element){
           options.selector = options.element;
       }
       if(options.selector){
           if (typeof options.selector === 'string') {
               options.selector = (options.document || document).querySelector(options.selector);
           }
           if (options.selector && options.selector.__MWEditor) {
               return options.selector.__MWEditor;
           }
       }
       return new Editor(options);
   };
}

window.MWEditor = function (options) {
    return new Editor(options)
}







})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/bar.js ***!
  \********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");


(function(){
    var Bar = function(options) {

        options = options || {};
        var defaults = {
            document: document,
            register: null
        };
        this.settings = Object.assign({}, defaults, options);
        this.document = this.settings.document || document;

        this.register = [];

        this.delimiter = function(){
            var el = this.document.createElement('span');
            el.className = 'mw-bar-delimiter';
            return el;
        };

        this.create = function(){
            this.bar = this.document.createElement('div');
            this.bar.className = 'mw-bar';
            this.element = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)(this.bar);
        };

        this.rows = [];

        this.createRow = function () {
            var row = this.document.createElement('div');
            row.className = 'mw-bar-row';
            this.rows.push(row);
            this.bar.appendChild(row);
        };
        this.nativeElement = function (node) {
            if(!node) return;
            return node.node ? node.node : node;
        };

        this.add = function (what, row) {
            row = row || 0;
            if(!this.rows[row]) {
                return;
            }
            if(what === '|') {
                this.rows[row].appendChild(this.delimiter());
            } else if(typeof what === 'function') {
                this.rows[row].appendChild(what().node);
            } else {
                var el = this.nativeElement(what);
                if(el) {
                    el.classList.add('mw-bar-control-item')
                    this.rows[row].appendChild(el);
                }

            }
        };

        this.init = function(){
            this.create();
        };
        this.init();
    };
    mw.bar = function(options){
        return new Bar(options);
    };
})();

})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/api.js ***!
  \********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/dom */ "./userfiles/modules/microweber/api/classes/dom.js");
/* harmony import */ var _check__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./check */ "./userfiles/modules/microweber/api/editor/check.js");



const rangeWalker = (range, doc) => {
    doc = doc || document;
    let ranges = [];
    let el = range.startContainer;
    let elsToVisit = true;

    var count = 0;

    while (elsToVisit) {
        count++;

        let startOffset = el === range.startContainer ? range.startOffset : 0;
        let endOffset = el === range.endContainer ? range.endOffset : el.textContent.length;
        let r = doc.createRange();
        r.setStart(el, startOffset);
        r.setEnd(el, endOffset);
        ranges.push(r);
        elsToVisit = false;

        while (!elsToVisit && el !== range.endContainer) {
            count++;


            let nextEl = getFirstTextNode(el.nextSibling);
            if (nextEl) {
                el = nextEl;
                elsToVisit = true;
            }
            else {
                if (el.nextSibling) {
                    el = el.nextSibling;
                } else if (el.parentNode) {
                    el = el.parentNode;
                } else {
                    break;
                }
            }
        }
    }
    console.log(count)
    return ranges;
}
const getFirstTextNode = el => {
     if (!el)               return null;
    if (el.nodeType === 3)  return el;

    for (let child of el.childNodes) {
        if (child.nodeType === 3) {
            return child;
        }
        else {
            let textNode = getFirstTextNode(child);
            if (textNode !== null) {
                return textNode;
            }
        }
    }
    return null;
}


MWEditor.api = function (scope) {
    return {
        getSelection: function () {
            return scope.getSelection();
        },
        eachRange: function (c){
            var sel = scope.getSelection();
            if(sel.rangeCount && c) {
                for(var i = 0; i < sel.rangeCount; i++) {
                    var range = sel.getRangeAt(i);
                    c.call(scope, range);
                }
            }
        },
        getSelectionHTML: function (){
            var sel = scope.getSelection();
            var html = scope.actionWindow.document.createElement('div');
            if(sel.rangeCount) {
                var frag = sel.getRangeAt(0).cloneContents();
                while (frag.firstChild) {
                    html.append(frag.firstChild);
                }
            }
            return html.innerHTML;
        },
        cleanWord: function (content) {
            var wrapListRoots = function () {
                var all = scope.$editArea.querySelectorAll('li[data-level]'), i = 0, has = false;
                for (; i < all.length; i++) {
                    var parent = all[i].parentElement.nodeName;
                    if (parent !== 'OL' && parent !== 'UL') {
                        has = true;
                        var group = mw.$([]), curr = all[i];
                        while (!!curr && curr.nodeName === 'LI') {
                            group.push(curr);
                            curr = curr.nextElementSibling;
                        }
                        var el = scope.actionWindow.document.createElement(all[i].getAttribute('data-type') === 'ul' ? 'ul' : 'ol');
                        el.className = 'element';
                        group.wrapAll(el);
                        break;
                    }
                }
                if (has) return wrapListRoots();
            };

            var buildWordList = function (lists, count) {
                var i, check = false, max = 0;
                count = count || 0;
                if (count === 0) {
                    for (i in lists) {
                        var curr = lists[i];
                        if (!curr.nodeName || curr.nodeType !== 1) continue;

                        var el = document.createElement('li');
                        mw.tools.copyAttributes(node, el);
                        while (curr.firstChild) {
                            el.appendChild(curr.firstChild);
                        }
                        curr.replaceWith(el);
                        lists[i] = el;
                    }
                }

                lists.each(function () {
                    var num = this.textContent.trim().split('.')[0], check = parseInt(num, 10);
                    var curr = mw.$(this);
                    if (!curr.attr('data-type')) {
                        if (!isNaN(check) && num > 0) {
                            this.innerHTML = this.innerHTML.replace(num + '.', '');
                            curr.attr('data-type', 'ol');
                        }
                        else {
                            curr.attr('data-type', 'ul');
                        }
                    }
                    if (!this.__done) {
                        this.__done = false;
                        var level = parseInt($(this).attr('data-level'));
                        if (!isNaN(level) && level > max) {
                            max = level;
                        }
                        if (!isNaN(level) && level > 1) {
                            var prev = this.previousElementSibling;
                            if (!!prev && prev.nodeName === 'LI') {
                                var type = this.getAttribute('data-type');
                                var wrap = scope.actionWindow.document.createElement(type === 'ul' ? 'ul' : 'ol');
                                wrap.setAttribute('data-level', level);
                                mw.$(wrap).append(this);
                                mw.$(wrap).appendTo(prev);
                                check = true;
                            }
                            else if (!!prev && (prev.nodeName === 'UL' || prev.nodeName === 'OL')) {
                                var where = mw.$('li[data-level="' + level + '"]', prev);
                                if (where.length > 0) {
                                    where.after(this);
                                    check = true;
                                }
                                else {
                                    var type = this.getAttribute('data-type');
                                    var wrap = scope.actionWindow.document.createElement(type === 'ul' ? 'ul' : 'ol');
                                    wrap.setAttribute('data-level', level)
                                    mw.$(wrap).append(this);
                                    mw.$(wrap).appendTo($('li:last', prev))
                                    check = true;
                                }
                            }
                            else if (!prev && (this.parentNode.nodeName !== 'UL' && this.parentNode.nodeName !== 'OL')) {
                                var $curr = mw.$([this]), curr = this;
                                while ($(curr).next('li[data-level="' + level + '"]').length > 0) {
                                    $curr.push($(curr).next('li[data-level="' + level + '"]')[0]);
                                    curr = mw.$(curr).next('li[data-level="' + level + '"]')[0];
                                }
                                $curr.wrapAll($curr.eq(0).attr('data-type') === 'ul' ? '<ul></ul>' : '<ol></ol>')
                                check = true;
                            }
                        }
                    }
                });

                mw.$("ul[data-level!='1'], ol[data-level!='1']").each(function () {
                    var level = parseInt($(this).attr('data-level'));
                    if (!!this.previousElementSibling) {
                        var plevel = parseInt($(this.previousElementSibling).attr('data-level'));
                        if (level > plevel) {
                            mw.$('li:last', this.previousElementSibling).append(this)
                            check = true;
                        }
                    }
                });
                if (count === 0) {
                    setTimeout(function () {
                        buildWordList($('li[data-level]'), 1);
                        wrapListRoots();
                    }, 1);
                }
                return lists;
            };

            var word_listitem_get_level = function (item) {
                var msspl = item.getAttribute('style').split('mso-list');
                if (msspl.length > 1) {
                    var mssplitems = msspl[1].split(' ');
                    for (var i = 0; i < mssplitems.length; i++) {
                        if (mssplitems[i].indexOf('level') !== -1) {
                            return parseInt(mssplitems[i].split('level')[1], 10);
                        }
                    }
                }
            };

            var isWordHtml = function (html) {
                return html.indexOf('urn:schemas-microsoft-com:office:word') !== -1;
            };

            var _cleanWordList = function (html) {

                if (!isWordHtml(html)) return html;
                if (html.indexOf('</body>') !== -1) {
                    html = html.split('</body>')[0];
                }
                var parser = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.parseHtml(html).body;

                var lists = mw.$('[style*="mso-list:"]', parser);
                lists.each(function () {
                    var level = word_listitem_get_level(this);
                    if (!!level) {
                        this.setAttribute('data-level', level)
                        this.setAttribute('class', 'level-' + level)
                    }

                });

                mw.$('[style]', parser).removeAttr('style');

                if (lists.length > 0) {
                    lists = buildWordList(lists);
                    var start = mw.$([]);
                    mw.$('li', parser).each(function () {
                        this.innerHTML = this.innerHTML
                            .replace(/�/g, '')/* Not a dot */
                            .replace(new RegExp(String.fromCharCode(160), "g"), "")
                            .replace(/&nbsp;/gi, '')
                            .replace(/\�/g, '')
                            .replace(/<\/?span[^>]*>/g, "")
                            .replace('�', '');
                    });
                }
                return parser.innerHTML;
            };

            var cleanWord = function (html) {
                html = _cleanWordList(html);
                html = html.replace(/<td([^>]*)>/gi, '<td>');
                html = html.replace(/<table([^>]*)>/gi, '<table cellspacing="0" cellpadding="0" border="1" style="width:100%;" width="100%" class="element">');
                html = html.replace(/<o:p>\s*<\/o:p>/g, '');
                html = html.replace(/<o:p>[\s\S]*?<\/o:p>/g, '&nbsp;');
                html = html.replace(/\s*mso-[^:]+:[^;"]+;?/gi, '');
                html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '');
                html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"");
                html = html.replace(/\s*TEXT-INDENT: 0cm\s*;/gi, '');
                html = html.replace(/\s*TEXT-INDENT: 0cm\s*"/gi, "\"");
                html = html.replace(/\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"");
                html = html.replace(/\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"");
                html = html.replace(/\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"");
                html = html.replace(/\s*tab-stops:[^;"]*;?/gi, '');
                html = html.replace(/\s*tab-stops:[^"]*/gi, '');
                html = html.replace(/\s*face="[^"]*"/gi, '');
                html = html.replace(/\s*face=[^ >]*/gi, '');
                html = html.replace(/\s*FONT-FAMILY:[^;"]*;?/gi, '');
                html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3");
                html = html.replace(/<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi, '');
                html = html.replace(/<(?:META|LINK)[^>]*>\s*/gi, '');
                html = html.replace(/\s*style="\s*"/gi, '');
                html = html.replace(/<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;');
                html = html.replace(/<SPAN\s*[^>]*><\/SPAN>/gi, '');
                html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3");
                html = html.replace(/<SPAN\s*>([\s\S]*?)<\/SPAN>/gi, '$1');
                html = html.replace(/<FONT\s*>([\s\S]*?)<\/FONT>/gi, '$1');
                html = html.replace(/<\\?\?xml[^>]*>/gi, '');
                html = html.replace(/<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi, '');
                html = html.replace(/<\/?\w+:[^>]*>/gi, '');
                html = html.replace(/<\!--[\s\S]*?-->/g, '');
                html = html.replace(/<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;');
                html = html.replace(/<H\d>\s*<\/H\d>/gi, '');
                html = html.replace(/<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/ig, '');
                html = html.replace(/<(\w[^>]*) language=([^ |>]*)([^>]*)/gi, "<$1$3");
                html = html.replace(/<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi, "<$1$3");
                html = html.replace(/<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi, "<$1$3");
                html = html.replace(/<H(\d)([^>]*)>/gi, '<h$1>');
                html = html.replace(/<font size=2>(.*)<\/font>/gi, '$1');
                html = html.replace(/<font size=3>(.*)<\/font>/gi, '$1');
                html = html.replace(/<a name=.*>(.*)<\/a>/gi, '$1');
                html = html.replace(/<H1([^>]*)>/gi, '<H2$1>');
                html = html.replace(/<\/H1\d>/gi, '<\/H2>');
                //html = html.replace(/<span>/gi, '$1');
                html = html.replace(/<\/span\d>/gi, '');
                html = html.replace(/<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>');
                html = html.replace(/<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>');
                return html;
            };

            var cleanTables = function (root) {
                var toRemove = "tbody > *:not(tr), thead > *:not(tr), tr > *:not(td)",
                    all = root.querySelectorAll(toRemove),
                    l = all.length,
                    i = 0;
                for (; i < l; i++) {
                    mw.$(all[i]).remove();
                }
                var tables = root.querySelectorAll('table'),
                    l = tables.length,
                    i = 0;
                for (; i < l; i++) {
                    var item = tables[i],
                        l = item.children.length,
                        i = 0;
                    for (; i < l; i++) {
                        var item = item.children[i];
                        if (typeof item !== 'undefined' && item.nodeType !== 3) {
                            var name = item.nodeName.toLowerCase();
                            var posibles = "thead tfoot tr tbody col colgroup";
                            if (!posibles.contains(name)) {
                                mw.$(item).remove();
                            }
                        }
                    }
                }
            };
            return cleanWord(content)

        },
        action: function(targetParent, func) {

            scope.state.record({
                target: targetParent,
                value: targetParent.innerHTML
            });
             func.call();

            setTimeout(function(){
                scope.state.record({
                    target: targetParent,
                    value: targetParent.innerHTML
                });
            }, 78);
        },
        elementNode: function (c) {
            if( !c || !c.parentNode || c.parentNode === document.body ){
                return null;
            }
            try {   /* Firefox returns wrong target (div) when you click on a spin-button */
                if (typeof c.querySelector === 'function') {
                    return c;
                }
                else {
                    return scope.api.elementNode(c.parentNode);
                }
            }
            catch (e) {
                return null;
            }
        },
        bold: function () {
            var opt = {
                css: {'font-weight': 'bold'},
                className: 'format-bold',
                fragmentModifier: function (frag) {
                    var el = frag.querySelector('b,strong,.format-bold')
                    while (el) {
                        el.replaceWith(...el.childNodes);
                        el = frag.querySelector('b,strong,.format-bold')
                    }
                },
            }
            scope.api.domCommand('cssApplier', opt);
        },
        unBold: function () {
            var opt = {
                css: {'font-weight': 'normal'},
                className: 'format-unbold',
                rangeModify: function (range) {
                    if( range.startContainer === range.endContainer && _check__WEBPACK_IMPORTED_MODULE_1__.Check.isBold(range.startContainer) ) {
                        while(range.startContainer.firstChild) {
                            range.startContainer.parentNode.insertBefore(range.startContainer.firstChild, range.startContainer)
                        }
                    }
                    var el = frag.querySelector('b,strong,.format-bold')
                    while (el) {
                        el.replaceWith(...el.childNodes);
                        el = frag.querySelector('b,strong,.format-bold')
                    }
                }
            }
             scope.api.domCommand('cssApplier', opt);

        },
        boldToggle: function (){
            scope.api.execCommand('bold');
        },
        boldToggleNew: function (){
            const range = scope.api.getSelection().getRangeAt(0);
            const ranges = rangeWalker(range, scope.executionDocument);
            let isBoldLike = false;

            ranges.forEach(range => {
                const el = scope.api.elementNode(range.commonAncestorContainer);
                if(_check__WEBPACK_IMPORTED_MODULE_1__.Check.isBold(el)) {
                    isBoldLike = true;
                }
            })

            if(isBoldLike) {
                scope.api.unBold();
            } else {
                scope.api.bold();
            }
        },
        fontFamily: function (font_name, sel) {
            var range = (sel || scope.getSelection()).getRangeAt(0);
            scope.api.execCommand("styleWithCSS", null, true);
            if (range.collapsed) {
                var el = scope.api.elementNode(range.commonAncestorContainer);
                scope.api.select_all(el);
                scope.api.execCommand('fontName', null, font_name);
                range.collapse();
            }
            else {
                scope.api.execCommand('fontName', null, font_name);
            }
        },
        selectAll: function (el) {
            var range = scope.document.createRange();
            range.selectNodeContents(el);
            var selection = scope.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        },
        selectElement: function (el) {
            var range = scope.document.createRange();
            try {
                range.selectNode(el);
                var selection = scope.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            } catch (e) {

            }
        },
        isSelectionEditable: function (sel) {
            try {
                var node = (sel || scope.getSelection()).focusNode;
                if (node === null) {
                    return false;
                }
                if (node.nodeType === 1) {
                    return node.isContentEditable;
                }
                else {
                    return node.parentNode.isContentEditable;
                }
            }
            catch (e) {
                return false;
            }
        },
        getTextNodes: function (root, target){
            if(!target) target = [];
            var curr = root.firstChild;
            while (curr) {
                if(curr.nodeType === 3) {
                    target.push(curr);
                } else if(curr.nodeType === 1){
                    scope.api.getTextNodes(curr, target)
                }
                curr = curr.nextSibling;
            }
            return target;
        },
        classApplier: function (className) {
            var sel = scope.getSelection();
            var range = sel.getRangeAt(0);
            var frag = range.cloneContents();
            var nodes = scope.api.getTextNodes(frag).filter(function (node){ return !!node });
            nodes.forEach(function (node){
                var el = scope.actionWindow.document.createElement('span');
                el.className = 'mw-richtext-classApplier ' + className;
                el.textContent = node.textContent;
                node.parentNode.replaceChild(el, node);
            });
            range.deleteContents()
            range.insertNode(frag)
        },

        cssApplier: function (options) {


            const {css, className, fragmentModifier} = options;
            let styles = '';
            if (typeof css === 'object') {
                for (let i in css) {
                    styles += (i + ':' + css[i] + ';');
                }
            } else if(typeof css === 'string') {
                styles = css;
            }
            let sel = scope.getSelection();
            let el = scope.api.elementNode(sel.focusNode);
            let range = sel.getRangeAt(0);
            let ranges = rangeWalker(range, scope.executionDocument);

            console.log(ranges.length)

            if(styles || className) {
                ranges.forEach(range => {
                    if(options.rangeModify) {
                        options.rangeModify(range)
                    }
                    let el = document.createElement('span');
                    el.className = 'mw-richtext-cssApplier' + (!!className ? (' ' + className) : '');
                    el.setAttribute('style', styles);
                    range.surroundContents(el);
                })
            }


            setTimeout(function (){
                 var tr = Array.from(scope.actionWindow.document.querySelectorAll('.mw-richtext-cssApplier:empty'));


                while(tr[0]) {
                    tr[0].remove()
                    tr.splice(0, 1)
                }
                var all = scope.actionWindow.document.querySelectorAll('.mw-richtext-cssApplier .mw-richtext-cssApplier');
                var i = 0; var l = all.length;
                for ( ; i < l;  i++ ) {
                    if(all[i].outerHTML === all[i].parentNode.innerHTML) {
                        all[i].parentNode.parentNode.replaceChild(all[i], all[i].parentNode);
                        return;
                    }
                }
            }, 100)

        },
        _old_cssApplier: function (options) {
            const {css, className, fragmentModifier} = options;
            var styles = '';
            if (typeof css === 'object') {
                for (var i in css) {
                    styles += (i + ':' + css[i] + ';');
                }
            } else if(typeof css === 'string') {
                styles = css;
            }
            var sel = scope.getSelection();
            var el = scope.api.elementNode(sel.focusNode);
            var range = sel.getRangeAt(0);
            var frag = range.cloneContents();
            if(typeof fragmentModifier === 'function') {
                fragmentModifier(frag)
            }
            var nodes = scope.api.getTextNodes(frag).filter(function (node){ return !!node });
            if(styles || className) {
                nodes.forEach(function (node){
                    var el = scope.actionWindow.document.createElement('span');
                    el.className = 'mw-richtext-cssApplier' + (!!className ? (' ' + className) : '');
                    el.setAttribute('style', styles);
                    el.textContent = node.textContent;
                    node.parentNode.replaceChild(el, node);
                });
            }
            range.deleteContents();
            range.insertNode(frag);
        },
        isSafeMode: function(el) {
            if (!el) {
                var node = scope.getSelection().focusNode;
                el = scope.api.elementNode(node);
            }
            var hasSafe = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
            var regInsafe = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
            return hasSafe && !regInsafe;
        },
        _execCommandCustom: {
            removeFormat: function (cmd, def, val) {
                scope.actionWindow.document.execCommand(cmd, def, val);
                var sel = scope.getSelection();
                var r = sel.getRangeAt(0);
                var common = r.commonAncestorContainer;
                var all = common.querySelectorAll('*'), l = all.length, i = 0;
                for ( ; i < l; i++ ) {
                    var el = all[i];
                    if (typeof sel !== 'undefined' && sel.containsNode(el, true)) {
                        all[i].removeAttribute('style');
                    }
                }
            }
        },
        domCommand: function (method, options) {
            var sel = scope.getSelection();

            try {  // 0x80004005
                if (  scope.api.isSelectionEditable()) {
                    if (sel.rangeCount > 0) {
                        var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(_classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstBlockLevel(node), function () {
                             scope.api[method].call(scope.api, options);
                            mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                            mw.$(scope).trigger('execCommand');
                            scope.registerChange();
                        });
                    }
                }
            }
            catch (e) {
            }
        },
        execCommand: function (cmd, def, val) {
             scope.actionWindow.document.execCommand('styleWithCss', 'false', false);
            var sel = scope.getSelection();
            try {  // 0x80004005
                if (scope.actionWindow.document.queryCommandSupported(cmd) && scope.api.isSelectionEditable()) {
                    def = def || false;
                    val = val || false;
                    if (sel.rangeCount > 0) {
                        var node = scope.api.elementNode(sel.focusNode);
                        scope.api.action(_classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstBlockLevel(node), function () {
                            scope.actionWindow.document.execCommand(cmd, def, val);
                            $(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand');
                            $(scope).trigger('execCommand');
                        });
                    }
                }
            }
            catch (e) {
            }
        },
        _fontSize: function (size, unit) {
            unit = unit || 'px';

            scope.api.domCommand('cssApplier', {
                css: {
                    'font-size': size + unit
                }
            });
        },
        lineHeight: function (size) {

            if (scope.api.isSelectionEditable()) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode)
                scope.api.action(_classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstBlockLevel(el), function () {
                     el.style.lineHeight = size
                });
            }

        },
        fontSize: function (size) {
            var sel = scope.getSelection();
            if (sel.isCollapsed) {
                scope.api.selectAll(scope.api.elementNode(sel.focusNode));
                sel = scope.getSelection();
            }
            var range = sel.getRangeAt(0),
                common = scope.api.elementNode(range.commonAncestorContainer);
            var nodrop_state = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(common, ['allow-drop', 'nodrop']);
            if (scope.api.isSelectionEditable() && nodrop_state) {
                scope.api._fontSize(size, 'px');
            }
        },
        saveSelection: function () {
            var sel = scope.getSelection();
            scope.api.savedSelection = {
                selection: sel,
                range: sel.getRangeAt(0),
                element: scope.api.elementNode(sel.getRangeAt(0).commonAncestorContainer)
            };
        },
        restoreSelection: function () {
            if (scope.api.savedSelection) {
                var sel = scope.getSelection();
                _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstParentOrCurrentWithAnyOfClasses(scope.api.savedSelection.element, ['edit', 'safe-element']).contentEditable = true;
                scope.api.savedSelection.element.focus();
                scope.api.savedSelection.selection.removeAllRanges();
                scope.api.savedSelection.selection.addRange(scope.api.savedSelection.range);
            }
        },
        _cleaner: document.createElement('div'),
        cleanHTML: function(html) {
             this._cleaner.innerHTML = html;
            var elements = Array.prototype.slice.call(this._cleaner.querySelectorAll('iframe,script,noscript'));
            while (elements.length) {
                elements[0].remove();
                elements.shift();
            }
            return this._cleaner.innerHTML;
        },
        insertHTML: function(html) {
            return scope.api.execCommand('insertHTML', false, this.cleanHTML(html));
        },
        insertImage: function (url) {
            var id =   ('image_' + new Date().getTime());
            var img = '<img id="' + id + '" contentEditable="false" class="element" src="' + url + '" />';
            scope.api.insertHTML(img);
            img = mw.$("#" + id);
            img.removeAttr("_moz_dirty");
            return img[0];
        },
        link: function (result) {
            var sel = scope.getSelection();
            var el = scope.api.elementNode(sel.focusNode);
            var elLink = el.nodeName === 'A' ? el : _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstParentWithTag(el, 'a');
            if (elLink) {
                elLink.href = result.url;
                if (result.text && result.text !== elLink.innerHTML) {
                    elLink.innerHTML = result.text;
                }
            } else {
                scope.api.insertHTML('<a href="'+ result.url +'">'+ (result.text || (sel.toString().trim()) || result.url) +'</a>');
            }
        },
        unlink: function () {
            var sel = scope.getSelection();
            if (!sel.isCollapsed) {
                this.execCommand('unlink', null, null);
            }
            else {
                var link = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstParentOrCurrentWithTag(this.elementNode(sel.focusNode), 'a');
                if (!!link) {
                    this.selectElement(link);
                    this.execCommand('unlink', null, null);
                }
            }
        }
    };
};

})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/helpers.js ***!
  \************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");


MWEditor.controllersHelpers = {
    '|' : function () {
        return (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
            tage: 'span',
            props: {
                className: 'mw-bar-delimiter'
            }
        });
    }
};

})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/tools.js ***!
  \**********************************************************/
MWEditor.tools = { };



})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/core.js ***!
  \*********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_object_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/object.service */ "./userfiles/modules/microweber/api/classes/object.service.js");
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");
/* harmony import */ var _classes_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../classes/dom */ "./userfiles/modules/microweber/api/classes/dom.js");



MWEditor.core = {
    button: function(config) {
        config = config || {};
        var defaults = {
            tag: 'button',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-button',
                type: 'button'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = _classes_object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend(true, {}, defaults, config);
        return (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)(settings);
    },
    field: function(config) {
        config = config || {};
        var defaults = {
            tag: 'input',
            props: {
                className: 'mdi mw-editor-controller-component mw-editor-controller-field',
                type: config.type || 'text'
            }
        };
        if (config.props && config.props.className){
            config.props.className = defaults.props.className + ' ' + config.props.className;
        }
        var settings = _classes_object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend(true, {}, defaults, config);
        return (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)(settings);
    },
    colorPicker: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = _classes_object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend(true, {}, defaults, config);

        var el = MWEditor.core.button(settings);
        el.addClass('mw-editor-color-picker')
        var input = (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)({
            tag: 'input',
            props: {
                type: 'color',
                className: 'mw-editor-color-picker-node'
            }
        });
        var time = null;
        input.on('input', function (){
            clearTimeout(time);
            time = setTimeout(function (el, node){
                console.log(node.value)
                el.trigger('change', node.value);
            }, 210, el, this);
        });
        el.append(input);
        return el;
    },
    element: function(config) {
        config = config || {};
        var defaults = {
            props: {
                className: 'mw-editor-controller-component'
            }
        };
        var settings = _classes_object_service__WEBPACK_IMPORTED_MODULE_0__.ObjectService.extend(true, {}, defaults, config);
        var el = (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)(settings);
        el.on('mousedown touchstart', function (e) {
            var tg = e.target.nodeName;

            var shouldPrevent = tg !== 'INPUT';
            if( shouldPrevent ) {
                e.preventDefault();
            }
        });
        return el;
    },

    _dropdownOption: function (data) {
        // data: { label: string, value: any },
        var option = MWEditor.core.element({
            props: {
                className: 'mw-editor-dropdown-option',
                innerHTML: data.label
            }
        });
        option.on('mousedown touchstart', function (e) {
            e.preventDefault();
        });
        option.value = data.value;
        return option;
    },
    number: function (options) {
        this.root = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-component-number',
                tooltip: options.placeholder || null
            }
        });
        var scope = this;


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        var valueNode = MWEditor.core.element({
            tag: 'input',
            props: {
                type: 'number',
                className: 'mw-editor-controller-component-number-value',
                min: options.min || 1,
                max: options.max || 1000,

            }
        });
        valueNode.on('input', function (){
            scope.dispatch('change', scope.value())
        })
        this.value = function (val) {
            if(typeof val === 'undefined' || val === null) {
                return valueNode.get(0).value;
            }
            valueNode.get(0).value = val;
        }
        var minus = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-number-minus',

            }
        }).on('mousedown touchstart', function (){
            valueNode.get(0).value = parseFloat(valueNode.get(0).value) - 1
            scope.dispatch('change', scope.value())
        });
        var plus = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-number-plus',

            }
        }).on('mousedown touchstart', function (){

            valueNode.get(0).value =  parseFloat(valueNode.get(0).value) + 1
            scope.dispatch('change', scope.value())
        });
        this.root.append(minus)
        this.root.append(valueNode)
        this.root.append(plus)
    },
    dropdown: function (options) {
        var lscope = this;
        this.root = MWEditor.core.element();
        this.select = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component mw-editor-controller-component-select',
                tooltip: options.placeholder || null
            }
        });
        if(!options.displayMode) {
            options.displayMode = 'button';
        }
        var displayValNode
        if(options.displayMode === "button") {
            displayValNode = MWEditor.core.button({
                props: {
                    className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value',
                    innerHTML: options.placeholder || ''
                }
            });
        } else if(options.displayMode === "field") {
            displayValNode = MWEditor.core.field({
                props: {
                    className: (options.icon ? 'mdi-' + options.icon + ' ' : '') + 'mw-editor-select-display-value-field',
                    placeholder: options.placeholder || ''
                }
            });
            setTimeout(function (){
                displayValNode.wrap({
                    props: {
                        className: 'mw-editor-select-display-value'
                    }
                })
            }, 10)
        }


        var valueHolder = MWEditor.core.element({
            props: {
                className: 'mw-editor-controller-component-select-values-holder',

            }
        });
        this.root.value = function (val){
            this.displayValue(val.label);
            this.value(val.value);
        };

        this.root.displayValue = function (val) {
            var md;
            if(options.displayMode === "button") {
                md = 'html';

            } else if(options.displayMode === "field") {
                md = 'val';
            }
            displayValNode[md](val || options.placeholder || '');
        };

        this.select.append(displayValNode);
        this.select.append(valueHolder);
        this.select.valueHolder = valueHolder;
        this.options = [];
        for (var i = 0; i < options.data.length; i++) {
            var dt = options.data[i];
            (function (dt){
                var opt = MWEditor.core._dropdownOption(dt);
                opt.on('click', function (){
                    lscope.select.trigger('change', dt);
                });
                lscope.options.push({
                    element: opt,
                    data: dt
                })
                valueHolder.append(opt);
            })(dt);

        }
        var curr = lscope.select.get(0);
        this.select.on('click', function (e) {
            e.stopPropagation();
            var wrapper = _classes_dom__WEBPACK_IMPORTED_MODULE_2__.DomService.firstParentOrCurrentWithClass(this, 'mw-editor-wrapper');
            if (wrapper) {
                var edOff = wrapper.getBoundingClientRect();
                var selOff = this.getBoundingClientRect();
                lscope.select.valueHolder.css({
                    maxHeight: edOff.height - (selOff.top - edOff.top)
                });
            }

            (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)('.mw-editor-controller-component-select').each(function (){
                if (this !== curr ) {
                    this.classList.remove('active');
                }
            });
            (0,_classes_element__WEBPACK_IMPORTED_MODULE_1__.ElementManager)(this).toggleClass('active');
        });
        this.root.append(this.select);
    },
    _preSelect: function (node) {
        var all = document.querySelectorAll('.mw-editor-controller-component-select.active, .mw-bar-control-item-group.active');
        var parent = _classes_dom__WEBPACK_IMPORTED_MODULE_2__.DomService.firstParentOrCurrentWithAnyOfClasses(node ? node.parentNode : null, ['mw-editor-controller-component-select','mw-bar-control-item-group']);
        var i = 0, l = all.length;
        for ( ; i < l; i++) {
            if(!node || (all[i] !== node && all[i] !== parent)) {
                all[i].classList.remove('active');
            }
        }
    }
};

})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/controllers.js ***!
  \****************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/dom */ "./userfiles/modules/microweber/api/classes/dom.js");
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");
/* harmony import */ var _system_filepicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../system/filepicker */ "./userfiles/modules/microweber/api/system/filepicker.js");
/* harmony import */ var _classes_dialog__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../classes/dialog */ "./userfiles/modules/microweber/api/classes/dialog.js");





MWEditor.controllers = {
    align: function (scope, api, rootScope) {
        this.root = MWEditor.core.element();
        this.root.addClass('mw-editor-state-component mw-editor-state-component-align');
        this.buttons = [];

        var arr = [
            {align: 'left', icon: 'left', action: 'justifyLeft'},
            {align: 'center', icon: 'center', action: 'justifyCenter'},
            {align: 'right', icon: 'right', action: 'justifyRight'},
            {align: 'justify', icon: 'justify', action: 'justifyFull'}
        ];
        this.render = function () {
            var scope = this;
            arr.forEach(function (item) {
                var el = MWEditor.core.button({
                    props: {
                        className: 'mdi-format-align-' + item.icon
                    }
                });
                el.on('mousedown touchstart', function (e) {
                    api.execCommand(item.action);
                });
                scope.root.append(el);
                scope.buttons.push(el);
            });
            return scope.root;
        };
        this.checkSelection = function (opt) {
            var align = opt.css.alignNormalize();
            for (var i = 0; i< this.buttons.length; i++) {
                var state = arr[i].align === align;
                rootScope.controllerActive(this.buttons[i].node, state);
            }
        };
        this.element = this.render();
    },
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-bold',
                    tooltip: rootScope.lang('Bold')
                }
            });
            el.on('mousedown touchstart', function (e) {
                // api.execCommand('bold');

                api.boldToggle()


            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().bold) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    strikeThrough: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-strikethrough',
                    tooltip: rootScope.lang('Strike through')
                }
            });

            el.on('mousedown touchstart', function (e) {
                api.execCommand('strikeThrough');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().striked) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    italic: function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-italic',
                    tooltip: rootScope.lang('Italic')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('italic');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().italic) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },
    'underline': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-underline',
                    tooltip: rootScope.lang('Underline')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('underline');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().underlined) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },

    image: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-folder-multiple-image',
                    tooltip: rootScope.lang('Insert Image')
                }
            });
            el.on('click', function (e) {
                var dialog;
                var picker = new _system_filepicker__WEBPACK_IMPORTED_MODULE_2__.FilePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        url = url.toString();
                        api.insertImage(url);
                        dialog.remove();
                    }
                });
                dialog = rootScope.dialog({
                    content: picker.root,
                    title:  'Select image',
                    footer: false,

                });

            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    link: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link',
                    tooltip: rootScope.lang('Insert link')
                }
            });

            el.on('click', function (e) {
                api.saveSelection();
                var sel = scope.getSelection();

                var target;
                if(sel.focusNode.nodeName === 'A') {
                    target = sel.focusNode;
                } else {
                    var curr = sel.focusNode;
                    while(curr && curr.nodeName){
                        if(curr.nodeName === 'A') {
                            target = curr;
                            break;
                        } else {
                            curr = curr.parentNode;
                        }
                    }
                }

                var val;
                if(target) {
                    val = {
                        url: target.href,
                        text: target.innerHTML,
                        target: target.target === '_blank'
                    };
                } else if(!sel.isCollapsed) {
                    val = {
                        url: '',
                        text: api.getSelectionHTML(),
                        target: false
                    };
                }
                var linkEditor = new mw.LinkEditor({
                    mode: 'dialog',
                });
                if(val) {
                    linkEditor.setValue(val);
                }

                linkEditor.promise().then(function (data){
                    var modal = linkEditor.dialog;
                    if(data) {
                        api.restoreSelection();
                        api.link(data);
                        modal.remove();
                    } else {
                        modal.remove();
                    }
                });


            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    fontSize: function (scope, api, rootScope) {
        this.checkSelection2 = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;
            console.log(opt.controller)
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var ctrl = new MWEditor.core.number({});
            ctrl.on('change', function (val) {

                api.fontSize(val);
            })
            return ctrl.root;
        }
        this.render2 = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: '8px', value: 8 },
                    { label: '10px', value: 10 },
                    { label: '12px', value: 12 },
                    { label: '14px', value: 14 },
                    { label: '16px', value: 16 },
                    { label: '18px', value: 18 },
                    { label: '20px', value: 20 },
                    { label: '22px', value: 22 },
                    { label: '24px', value: 24 },
                    { label: '28px', value: 28 },
                    { label: '32px', value: 32 },
                    { label: '36px', value: 36 },
                    { label: '42px', value: 42 },
                ],
                placeholder: rootScope.lang('Font Size'),
                displayMode: 'field'
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.fontSize(val.value);
                }
            });

            return dropdown.root;
        };
        this.element = this.render();
    },
    lineHeight: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.height;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                icon: 'format-line-spacing',
                data: [
                    { label: 'normal', value: 'normal' },
                    { label: '14px', value:'14px' },
                    { label: '16px', value:'16px' },
                    { label: '19px', value:'19px' },
                    { label: '21px', value:'21px' },
                    { label: '24px', value:'24px' },
                    { label: '25px', value:'25px' },
                    { label: '27px', value:'27px' },
                    { label: '30px', value:'30px' },
                    { label: '35px', value:'35px' },
                    { label: '40px', value:'40px' },
                    { label: '45px', value:'45px' },
                    { label: '50px', value:'50px' },
                    { label: '55px', value:'55px' },
                    { label: '60px', value:'60px' },
                ],
                placeholder: rootScope.lang('Line height')
            });
            dropdown.select.on('change', function (e, val) {
                api.lineHeight(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    format: function (scope, api, rootScope) {

        this._availableTags = [
            { label: '<h1>Title</h1>', value: 'h1' },
            { label: '<h2>Title</h2>', value: 'h2' },
            { label: '<h3>Title</h3>', value: 'h3' },
            { label: '<h4>Title</h4>', value: 'h4' },
            { label: '<h5>Title</h5>', value: 'h5' },
            { label: '<h6>Title</h6>', value: 'h6' },
            { label: 'Paragraph', value: 'p' },
            { label: 'Block', value: 'div' },
            { label: 'Pre formated', value: 'pre' }
        ];

        this.availableTags = function () {
            if(this.__availableTags) {
                return this.__availableTags;
            }
            this.__availableTags = this._availableTags.map(function (item) {
                return item.value;
            });
            return this.availableTags();
        };

        this.getTagDisplayName = function (tag) {
            tag = (tag || '').trim().toLowerCase();
            if(!tag) return;
            for (var i = 0; i < this._availableTags.length; i++) {
                if(this._availableTags[i].value === tag) {
                    return this._availableTags[i].label;
                }
            }
        };

        this.checkSelection = function (opt) {
            var el = opt.api.elementNode(opt.selection.focusNode);
            var parentEl = _classes_dom__WEBPACK_IMPORTED_MODULE_0__.DomService.firstParentOrCurrentWithTag(el, this.availableTags());
            opt.controller.element.displayValue(parentEl ? this.getTagDisplayName(parentEl.nodeName) : '');
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: this._availableTags,
                placeholder: rootScope.lang('Format')
            });
            dropdown.select.on('change', function (e, val) {
                api.execCommand('formatBlock', false, e.detail.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    fontSelector: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
                var font = css.font();
                var family_array = font.family.split(','), fam;
                if (family_array.length === 1) {
                    fam = font.family;
                } else {
                    fam = family_array.shift();
                }
                fam = fam.replace(/['"]+/g, '');
                opt.controller.element.displayValue(fam);
                opt.controller.element.disabled = !opt.api.isSelectionEditable();

        };

        this.render = function () {
            var fonts = rootScope.settings.fonts || [
                { label: 'Arial', value: 'Arial, sans-serif' },
                { label: 'Verdana', value: 'Verdana, sans-serif' },
                { label: 'Helvetica', value: 'Helvetica, sans-serif' },
                { label: 'Times New Roman', value: 'Times New Roman, serif' },
                { label: 'Georgia', value: 'Georgia, serif' },
                { label: 'Courier New', value: 'Courier New, monospace' },
                { label: 'Brush Script MT', value: 'Brush Script MT, cursive' },
            ];

            if(rootScope.settings.addFonts && rootScope.settings.addFonts.length) {
                fonts = [...fonts, ...rootScope.settings.addFonts]
            }


            var dropdown = new MWEditor.core.dropdown({
                data: fonts,
                placeholder: rootScope.lang('Font')
            });
            dropdown.options.forEach(function (item){
                item.element.css('fontFamily', item.data.value);
            });
            dropdown.select.on('change', function (e, val, b) {
                 api.fontFamily(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.addClass('mw-ui-btn-nav mw-editor-state-component')
            var undo = MWEditor.core.button({
                props: {
                    className: 'mdi-undo',
                    tooltip: rootScope.lang('Undo')
                }
            });
            undo.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
                rootScope._syncTextArea();
            });

            var redo = MWEditor.core.button({
                props: {
                    className: 'mdi-redo',
                    tooltip: rootScope.lang('Redo')
                }
            });
            redo.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
                rootScope._syncTextArea();
            });
            this.root.node.appendChild(undo.node);
            this.root.node.appendChild(redo.node);
            rootScope.state.on('stateRecord', function(e, data){
                console.log(e, data)
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            })
            .on('stateUndo stateRedo', function(e, data){
                if(!data.active || !data.active.target) {
                    undo.node.disabled = !data.hasNext;
                    redo.node.disabled = !data.hasPrev;
                    return;
                }
                if(scope.actionWindow.document.body.contains(data.active.target)) {
                    mw.$(data.active.target).html(data.active.value);
                } else{
                    if(data.active.target.id) {
                        mw.$(scope.actionWindow.document.getElementById(data.active.target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
                // mw.drag.load_new_modules();
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
                $(scope).trigger(e.type, [data]);
            });
            setTimeout(function () {
                var data = rootScope.state.eventData();
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            }, 78);
            return this.root;
        };
        this.element = this.render();
    },
    'ul': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-bulleted'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ul');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(node.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertUnorderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'ol': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-numbered tip',
                    'data-tip': 'Ordered list'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ol');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(paragraph.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertOrderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'indent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-increase',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('indent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'outdent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-decrease',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('outdent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    removeFormat: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-clear',
                    tooltip: 'Remove Format'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('removeFormat');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    unlink: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off', tooltip: 'Unlink'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('unlink');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-text', tooltip: 'Text color'
                }
            });
            el.on('change', function (e, val) {
                api.execCommand('foreColor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textBackgroundColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-fill', tooltip: 'Text background color'
                }
            });
            el.on('change', function (e, val) {
                api.execCommand('backcolor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-table-large', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.insertHTML('<table class="mw-ui-table" border="1" width="100%"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    wordPaste: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-file-word', tooltip: 'Paste from Word'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.saveSelection();
                var dialog;
                var ok = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn mw-ui-btn-info',
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn',
                        innerHTML: rootScope.lang('Cancel')
                    }
                });
                var cleanEl = mw.element({
                    props: {
                        contentEditable: true,
                        autofocus: true,
                        style: {
                            height: '250px'
                        }
                    }
                });

                var footer = mw.element();
                cancel.on('click', function (){
                    dialog.remove();
                })
                ok.on('click', function (){
                    var content = cleanEl.html().trim();
                    dialog.remove();
                    api.restoreSelection();
                    if(content){
                        api.insertHTML(api.cleanWord(content));
                    }

                });
                footer.append(cancel);
                footer.append(ok);
                dialog = mw.dialog({
                    content: cleanEl.node,
                    footer: footer.node
                });
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },

    mobilePreview: function(scope, api, rootScope){
         this.render = function () {
            var el = MWEditor.core.element({
                props: {
                    className: 'mobilePreview-block',
                 }
            })
             var phone = MWEditor.core.button({
                 props: {
                     className: 'mdi-cellphone',
                     tooltip: rootScope.lang('Phone'),
                 }
             });
             phone.on('mousedown touchstart', function (e) {
                 scope.executionDocument.defaultView.frameElement.style.width = '400px';
             });

             var tablet = MWEditor.core.button({
                 props: {
                     className: 'mdi-tablet-android',
                     tooltip: rootScope.lang('Tablet'),
                 }
             });
             tablet.on('mousedown touchstart', function (e) {
                 scope.executionDocument.defaultView.frameElement.style.width = '800px';
             });

             var pc = MWEditor.core.button({
                 props: {
                     className: 'mdi-laptop',
                     tooltip: rootScope.lang('Desktop'),
                 }
             });
             pc.on('mousedown touchstart', function (e) {
                 scope.executionDocument.defaultView.frameElement.style.width = '100%';
             });
             el.append(phone)
             el.append(tablet)
             el.append(pc)
            return el;
        };

        this.element = this.render();
    },

};

})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!*******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/add.controller.js ***!
  \*******************************************************************/

/*************************************************************
 *
        MWEditor.addController(
            'underline',
            function () {

            }, function () {

            }
        );

        MWEditor.addController({
            name: 'underline',
            render: function () {

            },
            checkSelection: function () {

            }
        })

 **************************************************************/



MWEditor.controllers.editSource = function (scope, api, rootScope) {
    this.render = function () {

        var scope = this;
        var el = MWEditor.core.button({
            props: {
                className: 'mdi mdi-xml',
                tooltip:  'Edit source'
            }
        });
        el.on('mousedown touchstart', function (e) {

            var ok = mw.element('<span class="mw-ui-btn mw-ui-btn-info">'+mw.lang('OK')+'</span>');
            var cancel = mw.element('<span class="mw-ui-btn">'+mw.lang('Cancel')+'</span>');
            var area = mw.element({ tag: 'textarea', props: {
                    className: 'mw-ui-field',
                }});
            area.css({
                height: 400
            })
            area.val(rootScope.$editArea.html());
            var footer = mw.element();
            footer.append(cancel).append(ok)
            var dialog = mw.dialog({
                overlay: true,
                content: area.get(0),
                footer: footer.get(0),
                title: mw.lang('Edit source')
            });

            cancel.on('click', function (){
                dialog.remove()
            });
            ok.on('click', function (){
                rootScope.setContent(area.val(), true);
                dialog.remove();
            });

        });
        return el;
    };
    this.checkSelection = function () {
        return true;
    };
};


MWEditor.addController = function (name, render, checkSelection, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
        dependencies = obj.dependencies;
    }
    if(MWEditor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor');
        return;
    }
    MWEditor.controllers[name] = function () {
        this.render = render;
        if(checkSelection) {
            this.checkSelection = checkSelection;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};


MWEditor.addInteractionController = function (name, render, interact, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        interact = obj.interact;
        dependencies = obj.dependencies;
    }
    if(MWEditor.interactionControls[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    MWEditor.interactionControls[name] = function () {
        this.render = render;
        if(interact) {
            this.interact = interact;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};

})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!*************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/interaction-controls.js ***!
  \*************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/element */ "./userfiles/modules/microweber/api/classes/element.js");
/* harmony import */ var _classes_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../classes/dom */ "./userfiles/modules/microweber/api/classes/dom.js");
/* harmony import */ var _system_filepicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../system/filepicker */ "./userfiles/modules/microweber/api/system/filepicker.js");






/*
*
*  interface data {
        target: Element,
        component: Element,
        isImage: boolean,
        event: Event
    };
*
*
* */

MWEditor.interactionControls = {
    linkTooltip: function (rootScope) {
        this.render = function () {
            var scope = this;
            var el = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    className: 'mw-editor-link-tooltip'
                }
            });
            var urlElement = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                tag: 'a',
                props: {
                    className: 'mw-editor-link-tooltip-url',
                    target: 'blank'
                }
            });
            var urlUnlink = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off',
                }
            });

            urlUnlink.on('click', function () {
                rootScope.api.unlink();
            });

            el.urlElement = urlElement;
            el.urlUnlink = urlUnlink;
            el.append(urlElement);
            el.append(urlUnlink);
            el.target = null;
            el.hide();
            return el;
        };
        this.interact = function (data) {
            var tg = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.firstParentOrCurrentWithTag(data.target,'a');
            if(!tg) {
                this.element.hide();
                return;
            }
            var $target = $(data.target);
            this.$target = $target;
            var css = $target.offset();
            css.top += $target.height();
            this.element.urlElement.html(data.target.href);
            this.element.urlElement.prop('href', data.target.href);
            this.element.css(css).show();
        };
        this.element = this.render();
    },
    image: function (rootScope) {

        this.nodes = [];
        this.render = function () {
            var scope = this;
            var el = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    className: 'mw-editor-image-handle-wrap'
                }
            });
            var changeButton = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    innerHTML: '<i class="mdi mdi-folder-multiple-image"></i>',
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
                    dataset: {
                        tip: rootScope.lang('Change image')
                    }
                }
            });
            changeButton.on('click', function () {
                var dialog;
                var picker = new _system_filepicker__WEBPACK_IMPORTED_MODULE_2__.FilePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        url = url.toString();
                        scope.$target.attr('src', url);
                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false
                })

            });
            var editButton = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    innerHTML: '<i class="mdi mdi-image-edit"></i>',
                    className: 'mw-ui-btn mw-ui-btn-medium tip',
                    dataset: {
                        tip: rootScope.lang('Edit image')
                    }
                }
            });
            var nav = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    className: 'mw-ui-btn-nav'
                }
            });
            nav.append(changeButton);
            el.append(nav);
            // nav.append(editButton);
            this.nodes.push(el.node, changeButton.node, editButton.node);
            el.hide();
            return el;
        };
        this.interact = function (data) {

            if(_classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.firstParentOrCurrentWithClass(data.localTarget, 'mw-editor-image-handle-wrap')) {
                return;
            }
            if(this.nodes.indexOf(data.target) !== -1) {
                this.element.hide();
                return;
            }
            if (data.isImage) {
                var $target = $(data.localTarget);
                this.$target = $target;
                var css = $target.offset();
                css.width = $target.outerWidth();
                css.height = $target.outerHeight();
                this.element.css(css).show();
                console.log( this.element)

            } else {
                this.element.hide();
            }
        };
        this.element = this.render();
    },
    tableManager: function(rootScope){
        var lscope = this;
        this.interact = function (data) {
            if (!data.eventIsActionLike) { return; }
            var td = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.firstParentOrCurrentWithTag(data.localTarget, 'td');
            if (td) {
                var $target = $(td);
                this.$target = $target;
                var css = $target.offset();
                css.top -= lscope.element.node.offsetHeight;
                this.element.$node.css(css).show();
            } else {
                this.element.hide();
            }
        };

        this._afterAction = function () {
            this.element.hide();
            rootScope.state.record({
                target: rootScope.$editArea[0],
                value: rootScope.$editArea[0].innerHTML
            });
        };

        this.render = function () {
            var root = (0,_classes_element__WEBPACK_IMPORTED_MODULE_0__.ElementManager)({
                props: {
                    className: 'mw-editor-table-manager'
                }
            });
            var bar = mw.bar();
            bar.createRow();
            root.append(bar.bar);

            var insertDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row Above', value: {action: 'insertRow', type: 'above'} },
                    { label: 'Row Under', value: {action: 'insertRow', type: 'under'} },
                    { label: 'Column on the left', value: {action: 'insertColumn', type: 'left'} },
                    { label: 'Column on the right', value: {action: 'insertColumn', type: 'right'} },
                ],
                placeholder: 'Insert'
            });

            insertDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action](data.value.type);
                lscope._afterAction();
            });
            var deletetDD = new MWEditor.core.dropdown({
                data: [
                    { label: 'Row', value: {action: 'deleteRow'} },
                    { label: 'Column', value: {action: 'deleteColumn'} },
                ],
                placeholder: 'Delete'
            });

            deletetDD.select.on('change', function (e, data, node) {
                rootScope.state.record({
                    target: rootScope.$editArea[0],
                    value: rootScope.$editArea[0].innerHTML
                });
                lscope[data.value.action]();
                lscope._afterAction()
            });

            bar.add(insertDD.root.node);
            bar.add(deletetDD.root.node);
            root.hide();

            return root;
        };

        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            cell.parentNode.remove();
        };


        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.index(cell),
                body = cell.parentNode.parentNode,
                rows = mw.$(body).children('tr'),
                l = rows.length,
                i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                row.getElementsByTagName('td')[index].remove();
            }
        };

        this.getActiveCell = function () {
            var node = rootScope.api.elementNode( rootScope.getSelection().focusNode);
            return _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.firstParentOrCurrentWithTag(node,'td');
        };

        this.insertColumn = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                cell = mw.$(row).children('td')[index];
                if (dir === 'left' || dir === 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir === 'right' || dir === 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        };
        this.insertRow = function (dir, cell) {
            cell = cell || this.getActiveCell();
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length,
                html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            html = '<tr>' + html + '</tr>';
            if (dir === 'under' || dir === 'both') {
                mw.$(parent).after(html)
            }
            if (dir === 'above' || dir === 'both') {
                mw.$(parent).before(html)
            }
        };
        this.deleteRow = function (cell) {
            cell = cell || this.getActiveCell();
            mw.$(cell.parentNode).remove();
        };
        this.deleteColumn = function (cell) {
            cell = cell || this.getActiveCell();
            var index = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        };

        this.setStyle = function (cls, cell) {
            cell = cell || this.getActiveCell();
            var table = _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.firstParentWithTag(cell, 'table');
            _classes_dom__WEBPACK_IMPORTED_MODULE_1__.DomService.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        };
        this.element = this.render();
    }

};

})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/i18n.js ***!
  \*********************************************************/
MWEditor.i18n = {
    en: {
        'Change': 'Change',
        'Edit image': 'Edit',
    }
};

})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!*****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/editor/liveeditmode.js ***!
  \*****************************************************************/

var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element'])
        && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
};




MWEditor.leSave = {
   prepare: function(root){
        if(!root) {
            return null;
        }
       var doc = mw.tools.parseHtml();
       var doc = document.implementation.createHTMLDocument("");
       doc.body.innerHTML = root.innerHTML;

       mw.$('.element-current', doc).removeClass('element-current');
       mw.$('.element-active', doc).removeClass('element-active');
       mw.$('.disable-resize', doc).removeClass('disable-resize');
       mw.$('.mw-webkit-drag-hover-binded', doc).removeClass('mw-webkit-drag-hover-binded');
       mw.$('.module-cat-toggle-Modules', doc).removeClass('module-cat-toggle-Modules');
       mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
       mw.$('-module', doc).removeClass('-module');
       mw.$('.empty-element', doc).remove();
       mw.$('.empty-element', doc).remove();
       mw.$('.edit .ui-resizable-handle', doc).remove();
       mw.$('script', doc).remove();
       mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
       mw.$("[contenteditable]", doc).removeAttr("contenteditable");
       var all = doc.querySelectorAll('[contenteditable]'),
           l = all.length,
           i = 0;
       for (; i < l; i++) {
           all[i].removeAttribute('contenteditable');
       }
       var all1 = doc.querySelectorAll('.module'),
           l1 = all.length,
           i1 = 0;
       for (; i1 < l1; i1++) {
           if (all[i1].querySelector('.edit') === null) {
               all[i1].innerHTML = '';
           }
       }
       return doc;
   },
   htmlAttrValidate:function(edits){
        var final = [];
        $.each(edits, function(){
            var html = this.outerHTML;
            html = html.replace(/url\(&quot;/g, "url('");
            html = html.replace(/jpg&quot;/g, "jpg'");
            html = html.replace(/jpeg&quot;/g, "jpeg'");
            html = html.replace(/png&quot;/g, "png'");
            html = html.replace(/gif&quot;/g, "gif'");
            final.push($(html)[0]);
        })
        return final;
   },
    pastedFromExcel: function (clipboard) {
        var html = clipboard.getData('text/html');
        return html.indexOf('ProgId content=Excel.Sheet') !== -1
    },
    areSameLike: function (el1, el2) {
        if (!el1 || !el2) return false;
        if (el1.nodeType !== el2.nodeType) return false;
        if (!!el1.className.trim() || !!el2.className.trim()) {
            return false;
        }

        var css1 = (el1.getAttribute('style') || '').replace(/\s/g, '');
        var css2 = (el2.getAttribute('style') || '').replace(/\s/g, '');

        if (css1 === css2 && el1.nodeName === el2.nodeName) {
            return true;
        }

        return false;
    },
    cleanUnwantedTags: function (body) {
        var scope = this;
        mw.$('*', body).each(function () {
            if (this.nodeName !== 'A' && mw.ea.helpers.isInlineLevel(this) && (this.className.trim && !this.className.trim())) {
                if (scope.areSameLike(this, this.nextElementSibling) && this.nextElementSibling === this.nextSibling) {
                    if (this.nextSibling !== this.nextElementSibling) {
                        this.appendChild(this.nextSibling);
                    }
                    this.innerHTML = this.innerHTML + this.nextElementSibling.innerHTML;
                    this.nextElementSibling.innerHTML = '';
                    this.nextElementSibling.className = 'mw-skip-and-remove';
                }
            }
        });
        mw.$('.mw-skip-and-remove', body).remove();
        return body;
    },
   getData: function(edits) {
        mw.$(edits).each(function(){
            mw.$('meta', this).remove();
        });

        edits = this.htmlAttrValidate(edits);
        var l = edits.length,
            i = 0,
            helper = {},
            master = {};
        if (l > 0) {
            for (; i < l; i++) {
                helper.item = edits[i];
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    mw.$(helper.item).removeClass('changed');
                    mw.tools.foreachParents(helper.item, function(loop) {
                        var cls = this.className;
                        var rel = mw.tools.mwattr(this, 'rel');
                        if (mw.tools.hasClass(cls, 'edit') && mw.tools.hasClass(cls, 'changed') && (!!rel)) {
                            helper.item = this;
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                var rel = mw.tools.mwattr(helper.item, 'rel');
                if (!rel) {
                    var field = !!helper.item.id ? '#'+helper.item.id : '';
                    console.warn('Skipped save: .edit'+field+' element does not have rel attribute.');
                    continue;
                }
                mw.$(helper.item).removeClass('changed orig_changed');
                mw.$(helper.item).removeClass('module-over');

                mw.$('.module-over', helper.item).each(function(){
                    mw.$(this).removeClass('module-over');
                });
                mw.$('[class]', helper.item).each(function(){
                    var cls = this.getAttribute("class");
                    if(typeof cls === 'string'){
                        cls = cls.trim();
                    }
                    if(!cls){
                        this.removeAttribute("class");
                    }
                });
                var content = this.cleanUnwantedTags(helper.item).innerHTML;
                var attr_obj = {};
                var attrs = helper.item.attributes;
                if (attrs.length > 0) {
                    var ai = 0,
                        al = attrs.length;
                    for (; ai < al; ai++) {
                        attr_obj[attrs[ai].nodeName] = attrs[ai].nodeValue;
                    }
                }
                var obj = {
                    attributes: attr_obj,
                    html: content
                };
                var objdata = "field_data_" + i;
                master[objdata] = obj;
            }
        }
        return master;
    }
};

MWEditor.leCore = {};

// methods accessible by scope.liveedit

MWEditor.liveeditMode = function(scope){
    return {

        prepare: {
            titles: function () {
                var t = scope.querySelectorAll('[field="title"]'),
                    l = t.length,
                    i = 0;

                for (; i < l; i++) {
                    mw.$(t[i]).addClass("nodrop");
                }
            }
        },

        isSafeMode: function (el) {
            if (!el) {
                var sel = scope.selection;
                if(!sel.rangeCount) return false;
                var range = sel.getRangeAt(0);
                el = scope.api.elementNode(range.commonAncestorContainer);
            }
            var hasSafe = mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
            var regInsafe = mw.tools.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
            return hasSafe && !regInsafe;
        },
        init: function (body, scope) {
            mw.$(body).on('keydown', function (event) {
                if (event.type === 'keydown') {
                    if (mw.tools.isField(event.target) || !event.target.isContentEditable) {
                        return true;
                    }
                    var sel = scope.selection;
                    if (mw.event.is.enter(event)) {
                        if (MWEditor.liveeditMode.isSafeMode(event.target)) {
                            var isList = mw.tools.firstMatchesOnNodeOrParent(event.target, ['li', 'ul', 'ol']);
                            if (!isList) {
                                event.preventDefault();
                                scope.api.insertHTML('<br>\u200C');
                            }
                        }
                    }
                    if (sel.rangeCount > 0) {
                        var r = sel.getRangeAt(0);
                        if (event.keyCode === 9 && !event.shiftKey && sel.focusNode.parentNode.isContentEditable && sel.isCollapsed) {   /* tab key */
                            scope.api.insertHTML('&nbsp;&nbsp;&nbsp;&nbsp;');
                            return false;
                        }
                        return manageDeleteAndBackspace(event, sel);
                    }
                }
            });
        },
        manageDeleteAndBackspaceInSafeMode : function (event, sel) {
            var node = scope.api.elementNode(sel.focusNode);
            var range = sel.getRangeAt(0);
            if(!node.textContent.replace(/\s/gi, '')){
                MWEditor.liveeditMode._manageDeleteAndBackspaceInSafeMode.emptyNode(event, node, sel, range);
                return false;
            }
            MWEditor.liveeditMode._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
            return true;
        },
        merge: {
            /* Executes on backspace or delete */
            isMergeable: function (el) {
                if (!el) return false;
                if (el.nodeType === 3) return true;
                var is = false;
                var css =  getComputedStyle(el);
                var display = css.getPropertyValue('display');
                var position = css.getPropertyValue('position');
                var isInline = display === 'inline';
                if (isInline) return true;
                var mergeables = ['p', '.element', 'div:not([class])', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                mergeables.forEach(function (item) {
                    if (el.matches(item)) {
                        is = true;
                    }
                });

                if (is) {
                    if (el.querySelector('.module') !== null || mw.tools.hasClass(el, 'module')) {
                        is = false;
                    }
                }
                return is;
            },
            manageBreakables: function (curr, next, dir, event) {
                var isnonbreakable = scope.liveedit.merge.isInNonbreakable(curr, dir);
                if (isnonbreakable) {
                    var conts = scope.selection.getRangeAt(0);
                    event.preventDefault();
                    if (next !== null) {
                        if (next.nodeType === 3 && /\r|\n/.exec(next.nodeValue) !== null) {
                            event.preventDefault();
                            return false;
                        }
                        if (dir === 'next') {
                            scope.liveedit.cursorToElement(next);
                        }
                        else {
                            scope.liveedit.cursorToElement(next, 'end');
                        }
                    }
                    else {
                        return false;
                    }
                }
            },
            isInNonbreakable: function (el, dir) {
                var absNext = scope.liveedit.merge.findNextNearest(el, dir);

                if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                    absNext = scope.liveedit.merge.findNextNearest(el, dir, true)
                }

                if (absNext.nodeType === 1) {
                    if (mw.tools.hasAnyOfClasses(absNext, ['nodrop', 'allow-drop'])) {
                        return false;
                    }
                    if (absNext.querySelector('.nodrop', '.allow-drop') !== null) {
                        return false;
                    }
                }
                if (scope.liveedit.merge.alwaysMergeable(absNext) && (scope.liveedit.merge.alwaysMergeable(absNext.firstElementChild) || !absNext.firstElementChild)) {
                    return false;
                }
                if (el.textContent === '') {

                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, dir);
                    if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext)
                    }
                }

                if (el.nodeType === 1 && !!el.textContent.trim()) {
                    return false;
                }
                if (el.nextSibling === null && el.nodeType === 3 && dir == 'next') {
                    var absNext = scope.liveedit.merge.findNextNearest(el, dir);
                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, dir);
                    if (/\r|\n/.exec(absNext.nodeValue) !== null) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext)
                    }

                    if (absNextNext.nodeType === 1) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext) || scope.liveedit.merge.isInNonbreakableClass(absNextNext.firstChild);
                    }
                    else if (absNextNext.nodeType === 3) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }

                if (el.previousSibling === null && el.nodeType === 3 && dir === 'prev') {
                    var absNext = scope.liveedit.merge.findNextNearest(el, 'prev');
                    var absNextNext = scope.liveedit.merge.findNextNearest(absNext, 'prev');
                    if (absNextNext.nodeType === 1) {
                        return scope.liveedit.merge.isInNonbreakableClass(absNextNext);
                    }
                    else if (absNextNext.nodeType === 3) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                el = scope.api.elementNode(el);

                var is = scope.liveedit.merge.isInNonbreakableClass(el);
                return is;
            },
            isInNonbreakableClass: function (el, dir) {
                var absNext = scope.liveedit.merge.findNextNearest(el, dir);

                if (el.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) === null) return false;
                el = scope.api.elementNode(el);
                var classes = ['unbreakable', '*col', '*row', '*btn', '*icon', 'module', 'edit'];
                var is = false;
                classes.forEach(function (item) {
                    if (item.indexOf('*') === 0) {
                        var item = item.split('*')[1];
                        if (el.className.indexOf(item) !== -1) {
                            is = true;
                        }
                        else {
                            mw.tools.foreachParents(el, function (loop) {
                                if (this.className.indexOf(item) !== -1 && !this.contains(el)) {
                                    is = true;
                                    mw.tools.stopLoop(loop);
                                }
                                else {
                                    is = false;
                                    mw.tools.stopLoop(loop);
                                }
                            });
                        }
                    }
                    else {
                        if (mw.tools.hasClass(el, item) || mw.tools.hasParentsWithClass(el, item)) {
                            is = true;
                        }
                    }
                });
                return is;
            },
            getNext: function (curr) {
                var next = curr.nextSibling;
                while (curr !== null && curr.nextSibling === null) {
                    curr = curr.parentNode;
                    next = curr.nextSibling;
                }
                return next;
            },
            getPrev: function (curr) {
                var next = curr.previousSibling;
                while (curr !== null && curr.previousSibling === null) {
                    curr = curr.parentNode;
                    next = curr.previousSibling;
                }
                return next;
            },
            findNextNearest: function (el, dir, searchElement) {
                searchElement = typeof searchElement === 'undefined' ? false : true;
                if (dir === 'next') {
                    var dosearch = searchElement ? 'nextElementSibling' : 'nextSibling';
                    var next = el[dosearch];
                    if (next === null) {
                        while (el[dosearch] === null) {
                            el = el.parentNode;
                            next = el[dosearch];
                        }
                    }
                }
                else {
                    var dosearch = searchElement ? 'previousElementSibling' : 'previousSibling';
                    var next = el[dosearch];
                    if (next === null) {
                        while (el[dosearch] === null) {
                            el = el.parentNode;
                            next = el[dosearch];
                        }
                    }
                }
                return next;
            },
            alwaysMergeable: function (el) {
                if (!el) {
                    return false;
                }
                if (el.nodeType === 3) {
                    return scope.liveedit.merge.alwaysMergeable(scope.api.elementNode(el))
                }
                if (el.nodeType === 1) {
                    if (/^(?:area|br|col|embed|hr|img|input|link|meta|param)$/i.test(el.tagName)) {
                        return true;
                    }
                    if (/^(?:strong|em|i|b|li)$/i.test(el.tagName)) {
                        return true;
                    }
                    if (/^(?:span)$/i.test(el.tagName) && !el.className) {
                        return true;
                    }
                }
                if (mw.tools.hasClass(el, 'module')) return false;
                if (mw.tools.hasParentsWithClass(el, 'module')) {
                    var ord = mw.tools.parentsOrder(el, ['edit', 'module']);
                }
                var selectors = [
                        'p.element', 'div.element', 'div:not([class])',
                        'h1.element', 'h2.element', 'h3.element', 'h4.element', 'h5.element', 'h6.element',
                        '.edit  h1', '.edit  h2', '.edit  h3', '.edit  h4', '.edit  h5', '.edit  h6',
                        '.edit p'
                    ],
                    final = false,
                    i = 0;
                for (; i < selectors.length; i++) {
                    var item = selectors[i];
                    if (el.matches(item)) {
                        final = true;
                        break;
                    }
                }
                return final;
            }
        },
        _manageDeleteAndBackspaceInSafeMode : {
            emptyNode: function (event, node, sel, range) {
                if(!canDestroy(node)) {
                    return;
                }
                var todelete = node;
                if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                    todelete = node.parentNode;
                }
                var transfer, transferPosition;
                if (mw.event.is.delete(event)) {
                    transfer = todelete.nextElementSibling;
                    transferPosition = 'start';
                } else {
                    transfer = todelete.previousElementSibling;
                    transferPosition = 'end';
                }
                var parent = todelete.parentNode;
                scope.record({
                    target: parent,
                    value: parent.innerHTML
                });
                $(todelete).remove();
                if(transfer && mw.tools.isEditable(transfer)) {
                    setTimeout(function () {
                        scope.liveedit.cursorToElement(transfer, transferPosition);
                    });
                }
                scope.record({
                    target: parent,
                    value: parent.innerHTML
                });
            },
            nodeBoundaries: function (event, node, sel, range) {
                var isStart = range.startOffset === 0 || !((sel.anchorNode.data || '').substring(0, range.startOffset).replace(/\s/g, ''));
                var curr, content;
                if(mw.event.is.backSpace(event) && isStart && range.collapsed){ // is at the beginning
                    curr = node;
                    if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                        curr = node.parentNode;
                    }
                    var prev = curr.previousElementSibling;
                    if(prev && prev.nodeName === node.nodeName && canDestroy(node)) {
                        content = node.innerHTML;
                        scope.liveedit.cursorToElement(prev, 'end');
                        prev.appendChild(range.createContextualFragment(content));
                        $(curr).remove();
                    }
                } else if(mw.event.is.delete(event)
                    && range.collapsed
                    && range.startOffset === sel.anchorNode.data.replace(/\s*$/,'').length // is at the end
                    && canDestroy(node)){
                    curr = node;
                    if(mw.tools.hasAnyOfClasses(node.parentNode, ['text', 'title'])){
                        curr = node.parentNode;
                    }
                    var next = curr.nextElementSibling, deleteParent;
                    if(mw.tools.hasAnyOfClasses(next, ['text', 'title'])){
                        next = next.firstElementChild;
                        deleteParent = true;
                    }
                    if(next && next.nodeName === curr.nodeName) {
                        content = next.innerHTML;
                        setTimeout(function(){
                            var parent = deleteParent ? next.parentNode.parentNode : next.parentNode;
                            scope.actionRecord(function() {
                                    return {
                                        target: parent,
                                        value: parent.innerHTML
                                    };
                                }, function () {
                                    curr.append(range.createContextualFragment(content));
                                }
                            );
                        });
                    }
                }
            }
        },
        manageDeleteAndBackspace: function (event, sel) {
            if (mw.event.is.delete(event) || mw.event.is.backSpace(event)) {
                if(!sel.rangeCount) return;
                var r = sel.getRangeAt(0);
                var isSafe = scope.liveedit.isSafeMode();

                if(isSafe) {
                    return scope.liveedit.manageDeleteAndBackspaceInSafeMode(event, sel);
                }
                var nextNode = null, nextchar, nextnextchar, nextel;

                if (mw.event.is.delete(event)) {
                    nextchar = sel.focusNode.textContent.charAt(sel.focusOffset);
                    nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset + 1);
                    nextel = sel.focusNode.nextSibling || sel.focusNode.nextElementSibling;

                } else {
                    nextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 1);
                    nextnextchar = sel.focusNode.textContent.charAt(sel.focusOffset - 2);
                    nextel = sel.focusNode.previouSibling || sel.focusNode.previousElementSibling;
                }

                if ((nextchar === ' ' || /\r|\n/.exec(nextchar) !== null) && sel.focusNode.nodeType === 3 && !nextnextchar) {
                    event.preventDefault();
                    return false;
                }

                if (nextnextchar === '') {

                    if (nextchar.replace(/\s/g, '') === '' && r.collapsed) {

                        if (nextel && !mw.tools.isBlockLevel(nextel) && ( typeof nextel.className === 'undefined' || !nextel.className.trim())) {
                            return true;
                        }
                        else if (nextel && nextel.nodeName !== 'BR') {
                            if (sel.focusNode.nodeName === 'P') {
                                if (event.keyCode === 46) {
                                    if (sel.focusNode.nextElementSibling.nodeName === 'P') {
                                        return true;
                                    }
                                }
                                if (event.keyCode === 8) {

                                    if (sel.focusNode.previousElementSibling.nodeName === 'P') {
                                        return true;
                                    }
                                }
                            }
                            event.preventDefault();
                            return false;
                        }

                    }
                    else if (
                        (focus.previousElementSibling === null && rootfocus.previousElementSibling === null)
                        && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])) {
                        return false;
                    }
                }
                if (nextchar === '') {

                    if (mw.event.is.delete(event)) {
                        nextNode = scope.liveedit.merge.getNext(sel.focusNode);
                    }
                    if (mw.event.is.backSpace(event)) {
                        nextNode = scope.liveedit.merge.getPrev(sel.focusNode);
                    }
                    if (scope.liveedit.merge.alwaysMergeable(nextNode)) {
                        return true;
                    }

                    var nonbr = scope.liveedit.merge.isInNonbreakable(nextNode)
                    if (nonbr) {
                        event.preventDefault();
                        return false;
                    }
                    if (nextNode !== null && scope.liveedit.merge.isMergeable(nextNode)) {
                        if (mw.event.is.delete(event)) {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                        }
                        else {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                        }
                    }
                    else {
                        event.preventDefault();
                    }
                    if (nextNode === null) {
                        nextNode = sel.focusNode.parentNode.nextSibling;
                        if (!scope.liveedit.merge.isMergeable(nextNode)) {
                            event.preventDefault();
                        }
                        if (mw.event.is.delete(event)) {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                        }
                        else {
                            scope.liveedit.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                        }
                    }
                }
            }
            return true;
        }

    };
};







})();

/******/ })()
;
//# sourceMappingURL=editor.js.map