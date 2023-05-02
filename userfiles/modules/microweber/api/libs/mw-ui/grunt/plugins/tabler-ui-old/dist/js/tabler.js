/*!
* Tabler v1.0.0-beta16 (https://tabler.io)
* @version 1.0.0-beta16
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
*/
(function (factory) {
	typeof define === 'function' && define.amd ? define(factory) :
	factory();
})((function () { 'use strict';

	var e,t,n="function"==typeof Map?new Map:(e=[],t=[],{has:function(t){return e.indexOf(t)>-1},get:function(n){return t[e.indexOf(n)]},set:function(n,o){-1===e.indexOf(n)&&(e.push(n),t.push(o));},delete:function(n){var o=e.indexOf(n);o>-1&&(e.splice(o,1),t.splice(o,1));}}),o=function(e){return new Event(e,{bubbles:!0})};try{new Event("test");}catch(e){o=function(e){var t=document.createEvent("Event");return t.initEvent(e,!0,!1),t};}function r(e){var t=n.get(e);t&&t.destroy();}function i(e){var t=n.get(e);t&&t.update();}var l=null;"undefined"==typeof window||"function"!=typeof window.getComputedStyle?((l=function(e){return e}).destroy=function(e){return e},l.update=function(e){return e}):((l=function(e,t){return e&&Array.prototype.forEach.call(e.length?e:[e],function(e){return function(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName&&!n.has(e)){var t,r=null,i=null,l=null,d=function(){e.clientWidth!==i&&c();},a=function(t){window.removeEventListener("resize",d,!1),e.removeEventListener("input",c,!1),e.removeEventListener("keyup",c,!1),e.removeEventListener("autosize:destroy",a,!1),e.removeEventListener("autosize:update",c,!1),Object.keys(t).forEach(function(n){e.style[n]=t[n];}),n.delete(e);}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",a,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",c,!1),window.addEventListener("resize",d,!1),e.addEventListener("input",c,!1),e.addEventListener("autosize:update",c,!1),e.style.overflowX="hidden",e.style.wordWrap="break-word",n.set(e,{destroy:a,update:c}),"vertical"===(t=window.getComputedStyle(e,null)).resize?e.style.resize="none":"both"===t.resize&&(e.style.resize="horizontal"),r="content-box"===t.boxSizing?-(parseFloat(t.paddingTop)+parseFloat(t.paddingBottom)):parseFloat(t.borderTopWidth)+parseFloat(t.borderBottomWidth),isNaN(r)&&(r=0),c();}function u(t){var n=e.style.width;e.style.width="0px",e.style.width=n,e.style.overflowY=t;}function s(){if(0!==e.scrollHeight){var t=function(e){for(var t=[];e&&e.parentNode&&e.parentNode instanceof Element;)e.parentNode.scrollTop&&t.push({node:e.parentNode,scrollTop:e.parentNode.scrollTop}),e=e.parentNode;return t}(e),n=document.documentElement&&document.documentElement.scrollTop;e.style.height="",e.style.height=e.scrollHeight+r+"px",i=e.clientWidth,t.forEach(function(e){e.node.scrollTop=e.scrollTop;}),n&&(document.documentElement.scrollTop=n);}}function c(){s();var t=Math.round(parseFloat(e.style.height)),n=window.getComputedStyle(e,null),r="content-box"===n.boxSizing?Math.round(parseFloat(n.height)):e.offsetHeight;if(r<t?"hidden"===n.overflowY&&(u("scroll"),s(),r="content-box"===n.boxSizing?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight):"hidden"!==n.overflowY&&(u("hidden"),s(),r="content-box"===n.boxSizing?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight),l!==r){l=r;var i=o("autosize:resized");try{e.dispatchEvent(i);}catch(e){}}}}(e)}),e}).destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],r),e},l.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],i),e});var d=l;

	var elements = document.querySelectorAll('[data-bs-toggle="autosize"]');
	if (elements.length) {
	  elements.forEach(function (element) {
	    d(element);
	  });
	}

	function _typeof(obj) {
	  "@babel/helpers - typeof";
	  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
	    return typeof obj;
	  } : function (obj) {
	    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
	  }, _typeof(obj);
	}
	function _classCallCheck(instance, Constructor) {
	  if (!(instance instanceof Constructor)) {
	    throw new TypeError("Cannot call a class as a function");
	  }
	}
	function _defineProperties(target, props) {
	  for (var i = 0; i < props.length; i++) {
	    var descriptor = props[i];
	    descriptor.enumerable = descriptor.enumerable || false;
	    descriptor.configurable = true;
	    if ("value" in descriptor) descriptor.writable = true;
	    Object.defineProperty(target, descriptor.key, descriptor);
	  }
	}
	function _createClass(Constructor, protoProps, staticProps) {
	  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
	  if (staticProps) _defineProperties(Constructor, staticProps);
	  Object.defineProperty(Constructor, "prototype", {
	    writable: false
	  });
	  return Constructor;
	}
	function _defineProperty(obj, key, value) {
	  if (key in obj) {
	    Object.defineProperty(obj, key, {
	      value: value,
	      enumerable: true,
	      configurable: true,
	      writable: true
	    });
	  } else {
	    obj[key] = value;
	  }
	  return obj;
	}
	function _inherits(subClass, superClass) {
	  if (typeof superClass !== "function" && superClass !== null) {
	    throw new TypeError("Super expression must either be null or a function");
	  }
	  subClass.prototype = Object.create(superClass && superClass.prototype, {
	    constructor: {
	      value: subClass,
	      writable: true,
	      configurable: true
	    }
	  });
	  Object.defineProperty(subClass, "prototype", {
	    writable: false
	  });
	  if (superClass) _setPrototypeOf(subClass, superClass);
	}
	function _getPrototypeOf(o) {
	  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) {
	    return o.__proto__ || Object.getPrototypeOf(o);
	  };
	  return _getPrototypeOf(o);
	}
	function _setPrototypeOf(o, p) {
	  _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) {
	    o.__proto__ = p;
	    return o;
	  };
	  return _setPrototypeOf(o, p);
	}
	function _isNativeReflectConstruct() {
	  if (typeof Reflect === "undefined" || !Reflect.construct) return false;
	  if (Reflect.construct.sham) return false;
	  if (typeof Proxy === "function") return true;
	  try {
	    Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {}));
	    return true;
	  } catch (e) {
	    return false;
	  }
	}
	function _objectWithoutPropertiesLoose(source, excluded) {
	  if (source == null) return {};
	  var target = {};
	  var sourceKeys = Object.keys(source);
	  var key, i;
	  for (i = 0; i < sourceKeys.length; i++) {
	    key = sourceKeys[i];
	    if (excluded.indexOf(key) >= 0) continue;
	    target[key] = source[key];
	  }
	  return target;
	}
	function _objectWithoutProperties(source, excluded) {
	  if (source == null) return {};
	  var target = _objectWithoutPropertiesLoose(source, excluded);
	  var key, i;
	  if (Object.getOwnPropertySymbols) {
	    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
	    for (i = 0; i < sourceSymbolKeys.length; i++) {
	      key = sourceSymbolKeys[i];
	      if (excluded.indexOf(key) >= 0) continue;
	      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
	      target[key] = source[key];
	    }
	  }
	  return target;
	}
	function _assertThisInitialized(self) {
	  if (self === void 0) {
	    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
	  }
	  return self;
	}
	function _possibleConstructorReturn(self, call) {
	  if (call && (typeof call === "object" || typeof call === "function")) {
	    return call;
	  } else if (call !== void 0) {
	    throw new TypeError("Derived constructors may only return object or undefined");
	  }
	  return _assertThisInitialized(self);
	}
	function _createSuper(Derived) {
	  var hasNativeReflectConstruct = _isNativeReflectConstruct();
	  return function _createSuperInternal() {
	    var Super = _getPrototypeOf(Derived),
	        result;
	    if (hasNativeReflectConstruct) {
	      var NewTarget = _getPrototypeOf(this).constructor;
	      result = Reflect.construct(Super, arguments, NewTarget);
	    } else {
	      result = Super.apply(this, arguments);
	    }
	    return _possibleConstructorReturn(this, result);
	  };
	}
	function _superPropBase(object, property) {
	  while (!Object.prototype.hasOwnProperty.call(object, property)) {
	    object = _getPrototypeOf(object);
	    if (object === null) break;
	  }
	  return object;
	}
	function _get() {
	  if (typeof Reflect !== "undefined" && Reflect.get) {
	    _get = Reflect.get.bind();
	  } else {
	    _get = function _get(target, property, receiver) {
	      var base = _superPropBase(target, property);
	      if (!base) return;
	      var desc = Object.getOwnPropertyDescriptor(base, property);
	      if (desc.get) {
	        return desc.get.call(arguments.length < 3 ? target : receiver);
	      }
	      return desc.value;
	    };
	  }
	  return _get.apply(this, arguments);
	}
	function set(target, property, value, receiver) {
	  if (typeof Reflect !== "undefined" && Reflect.set) {
	    set = Reflect.set;
	  } else {
	    set = function set(target, property, value, receiver) {
	      var base = _superPropBase(target, property);
	      var desc;
	      if (base) {
	        desc = Object.getOwnPropertyDescriptor(base, property);
	        if (desc.set) {
	          desc.set.call(receiver, value);
	          return true;
	        } else if (!desc.writable) {
	          return false;
	        }
	      }
	      desc = Object.getOwnPropertyDescriptor(receiver, property);
	      if (desc) {
	        if (!desc.writable) {
	          return false;
	        }
	        desc.value = value;
	        Object.defineProperty(receiver, property, desc);
	      } else {
	        _defineProperty(receiver, property, value);
	      }
	      return true;
	    };
	  }
	  return set(target, property, value, receiver);
	}
	function _set(target, property, value, receiver, isStrict) {
	  var s = set(target, property, value, receiver || target);
	  if (!s && isStrict) {
	    throw new Error('failed to set property');
	  }
	  return value;
	}
	function _slicedToArray(arr, i) {
	  return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest();
	}
	function _toConsumableArray(arr) {
	  return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
	}
	function _arrayWithoutHoles(arr) {
	  if (Array.isArray(arr)) return _arrayLikeToArray(arr);
	}
	function _arrayWithHoles(arr) {
	  if (Array.isArray(arr)) return arr;
	}
	function _iterableToArray(iter) {
	  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
	}
	function _iterableToArrayLimit(arr, i) {
	  var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];
	  if (_i == null) return;
	  var _arr = [];
	  var _n = true;
	  var _d = false;
	  var _s, _e;
	  try {
	    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {
	      _arr.push(_s.value);
	      if (i && _arr.length === i) break;
	    }
	  } catch (err) {
	    _d = true;
	    _e = err;
	  } finally {
	    try {
	      if (!_n && _i["return"] != null) _i["return"]();
	    } finally {
	      if (_d) throw _e;
	    }
	  }
	  return _arr;
	}
	function _unsupportedIterableToArray(o, minLen) {
	  if (!o) return;
	  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
	  var n = Object.prototype.toString.call(o).slice(8, -1);
	  if (n === "Object" && o.constructor) n = o.constructor.name;
	  if (n === "Map" || n === "Set") return Array.from(o);
	  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
	}
	function _arrayLikeToArray(arr, len) {
	  if (len == null || len > arr.length) len = arr.length;
	  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
	  return arr2;
	}
	function _nonIterableSpread() {
	  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
	}
	function _nonIterableRest() {
	  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
	}

	var ChangeDetails = function () {
	  function ChangeDetails(details) {
	    _classCallCheck(this, ChangeDetails);
	    Object.assign(this, {
	      inserted: '',
	      rawInserted: '',
	      skip: false,
	      tailShift: 0
	    }, details);
	  }
	  _createClass(ChangeDetails, [{
	    key: "aggregate",
	    value: function aggregate(details) {
	      this.rawInserted += details.rawInserted;
	      this.skip = this.skip || details.skip;
	      this.inserted += details.inserted;
	      this.tailShift += details.tailShift;
	      return this;
	    }
	  }, {
	    key: "offset",
	    get: function get() {
	      return this.tailShift + this.inserted.length;
	    }
	  }]);
	  return ChangeDetails;
	}();

	function isString(str) {
	  return typeof str === 'string' || str instanceof String;
	}
	var DIRECTION = {
	  NONE: 'NONE',
	  LEFT: 'LEFT',
	  FORCE_LEFT: 'FORCE_LEFT',
	  RIGHT: 'RIGHT',
	  FORCE_RIGHT: 'FORCE_RIGHT'
	};
	function forceDirection(direction) {
	  switch (direction) {
	    case DIRECTION.LEFT:
	      return DIRECTION.FORCE_LEFT;
	    case DIRECTION.RIGHT:
	      return DIRECTION.FORCE_RIGHT;
	    default:
	      return direction;
	  }
	}
	function escapeRegExp(str) {
	  return str.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
	}
	function normalizePrepare(prep) {
	  return Array.isArray(prep) ? prep : [prep, new ChangeDetails()];
	}
	function objectIncludes(b, a) {
	  if (a === b) return true;
	  var arrA = Array.isArray(a),
	      arrB = Array.isArray(b),
	      i;
	  if (arrA && arrB) {
	    if (a.length != b.length) return false;
	    for (i = 0; i < a.length; i++) {
	      if (!objectIncludes(a[i], b[i])) return false;
	    }
	    return true;
	  }
	  if (arrA != arrB) return false;
	  if (a && b && _typeof(a) === 'object' && _typeof(b) === 'object') {
	    var dateA = a instanceof Date,
	        dateB = b instanceof Date;
	    if (dateA && dateB) return a.getTime() == b.getTime();
	    if (dateA != dateB) return false;
	    var regexpA = a instanceof RegExp,
	        regexpB = b instanceof RegExp;
	    if (regexpA && regexpB) return a.toString() == b.toString();
	    if (regexpA != regexpB) return false;
	    var keys = Object.keys(a);
	    for (i = 0; i < keys.length; i++) {
	      if (!Object.prototype.hasOwnProperty.call(b, keys[i])) return false;
	    }
	    for (i = 0; i < keys.length; i++) {
	      if (!objectIncludes(b[keys[i]], a[keys[i]])) return false;
	    }
	    return true;
	  } else if (a && b && typeof a === 'function' && typeof b === 'function') {
	    return a.toString() === b.toString();
	  }
	  return false;
	}

	var ActionDetails = function () {
	  function ActionDetails(value, cursorPos, oldValue, oldSelection) {
	    _classCallCheck(this, ActionDetails);
	    this.value = value;
	    this.cursorPos = cursorPos;
	    this.oldValue = oldValue;
	    this.oldSelection = oldSelection;
	    while (this.value.slice(0, this.startChangePos) !== this.oldValue.slice(0, this.startChangePos)) {
	      --this.oldSelection.start;
	    }
	  }
	  _createClass(ActionDetails, [{
	    key: "startChangePos",
	    get: function get() {
	      return Math.min(this.cursorPos, this.oldSelection.start);
	    }
	  }, {
	    key: "insertedCount",
	    get: function get() {
	      return this.cursorPos - this.startChangePos;
	    }
	  }, {
	    key: "inserted",
	    get: function get() {
	      return this.value.substr(this.startChangePos, this.insertedCount);
	    }
	  }, {
	    key: "removedCount",
	    get: function get() {
	      return Math.max(this.oldSelection.end - this.startChangePos ||
	      this.oldValue.length - this.value.length, 0);
	    }
	  }, {
	    key: "removed",
	    get: function get() {
	      return this.oldValue.substr(this.startChangePos, this.removedCount);
	    }
	  }, {
	    key: "head",
	    get: function get() {
	      return this.value.substring(0, this.startChangePos);
	    }
	  }, {
	    key: "tail",
	    get: function get() {
	      return this.value.substring(this.startChangePos + this.insertedCount);
	    }
	  }, {
	    key: "removeDirection",
	    get: function get() {
	      if (!this.removedCount || this.insertedCount) return DIRECTION.NONE;
	      return (this.oldSelection.end === this.cursorPos || this.oldSelection.start === this.cursorPos) &&
	      this.oldSelection.end === this.oldSelection.start ? DIRECTION.RIGHT : DIRECTION.LEFT;
	    }
	  }]);
	  return ActionDetails;
	}();

	var ContinuousTailDetails = function () {
	  function ContinuousTailDetails() {
	    var value = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	    var from = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
	    var stop = arguments.length > 2 ? arguments[2] : undefined;
	    _classCallCheck(this, ContinuousTailDetails);
	    this.value = value;
	    this.from = from;
	    this.stop = stop;
	  }
	  _createClass(ContinuousTailDetails, [{
	    key: "toString",
	    value: function toString() {
	      return this.value;
	    }
	  }, {
	    key: "extend",
	    value: function extend(tail) {
	      this.value += String(tail);
	    }
	  }, {
	    key: "appendTo",
	    value: function appendTo(masked) {
	      return masked.append(this.toString(), {
	        tail: true
	      }).aggregate(masked._appendPlaceholder());
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        value: this.value,
	        from: this.from,
	        stop: this.stop
	      };
	    },
	    set: function set(state) {
	      Object.assign(this, state);
	    }
	  }, {
	    key: "unshift",
	    value: function unshift(beforePos) {
	      if (!this.value.length || beforePos != null && this.from >= beforePos) return '';
	      var shiftChar = this.value[0];
	      this.value = this.value.slice(1);
	      return shiftChar;
	    }
	  }, {
	    key: "shift",
	    value: function shift() {
	      if (!this.value.length) return '';
	      var shiftChar = this.value[this.value.length - 1];
	      this.value = this.value.slice(0, -1);
	      return shiftChar;
	    }
	  }]);
	  return ContinuousTailDetails;
	}();

	function IMask(el) {
	  var opts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	  return new IMask.InputMask(el, opts);
	}

	var Masked = function () {
	  function Masked(opts) {
	    _classCallCheck(this, Masked);
	    this._value = '';
	    this._update(Object.assign({}, Masked.DEFAULTS, opts));
	    this.isInitialized = true;
	  }
	  _createClass(Masked, [{
	    key: "updateOptions",
	    value: function updateOptions(opts) {
	      if (!Object.keys(opts).length) return;
	      this.withValueRefresh(this._update.bind(this, opts));
	    }
	  }, {
	    key: "_update",
	    value: function _update(opts) {
	      Object.assign(this, opts);
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        _value: this.value
	      };
	    },
	    set: function set(state) {
	      this._value = state._value;
	    }
	  }, {
	    key: "reset",
	    value: function reset() {
	      this._value = '';
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this._value;
	    },
	    set: function set(value) {
	      this.resolve(value);
	    }
	  }, {
	    key: "resolve",
	    value: function resolve(value) {
	      this.reset();
	      this.append(value, {
	        input: true
	      }, '');
	      this.doCommit();
	      return this.value;
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this.value;
	    },
	    set: function set(value) {
	      this.reset();
	      this.append(value, {}, '');
	      this.doCommit();
	    }
	  }, {
	    key: "typedValue",
	    get: function get() {
	      return this.doParse(this.value);
	    },
	    set: function set(value) {
	      this.value = this.doFormat(value);
	    }
	  }, {
	    key: "rawInputValue",
	    get: function get() {
	      return this.extractInput(0, this.value.length, {
	        raw: true
	      });
	    },
	    set: function set(value) {
	      this.reset();
	      this.append(value, {
	        raw: true
	      }, '');
	      this.doCommit();
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      return true;
	    }
	  }, {
	    key: "isFilled",
	    get: function get() {
	      return this.isComplete;
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos(cursorPos, direction) {
	      return cursorPos;
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      return this.value.slice(fromPos, toPos);
	    }
	  }, {
	    key: "extractTail",
	    value: function extractTail() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      return new ContinuousTailDetails(this.extractInput(fromPos, toPos), fromPos);
	    }
	  }, {
	    key: "appendTail",
	    value: function appendTail(tail) {
	      if (isString(tail)) tail = new ContinuousTailDetails(String(tail));
	      return tail.appendTo(this);
	    }
	  }, {
	    key: "_appendCharRaw",
	    value: function _appendCharRaw(ch) {
	      if (!ch) return new ChangeDetails();
	      this._value += ch;
	      return new ChangeDetails({
	        inserted: ch,
	        rawInserted: ch
	      });
	    }
	  }, {
	    key: "_appendChar",
	    value: function _appendChar(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var checkTail = arguments.length > 2 ? arguments[2] : undefined;
	      var consistentState = this.state;
	      var details;
	      var _normalizePrepare = normalizePrepare(this.doPrepare(ch, flags));
	      var _normalizePrepare2 = _slicedToArray(_normalizePrepare, 2);
	      ch = _normalizePrepare2[0];
	      details = _normalizePrepare2[1];
	      details = details.aggregate(this._appendCharRaw(ch, flags));
	      if (details.inserted) {
	        var consistentTail;
	        var appended = this.doValidate(flags) !== false;
	        if (appended && checkTail != null) {
	          var beforeTailState = this.state;
	          if (this.overwrite === true) {
	            consistentTail = checkTail.state;
	            checkTail.unshift(this.value.length);
	          }
	          var tailDetails = this.appendTail(checkTail);
	          appended = tailDetails.rawInserted === checkTail.toString();
	          if (!(appended && tailDetails.inserted) && this.overwrite === 'shift') {
	            this.state = beforeTailState;
	            consistentTail = checkTail.state;
	            checkTail.shift();
	            tailDetails = this.appendTail(checkTail);
	            appended = tailDetails.rawInserted === checkTail.toString();
	          }
	          if (appended && tailDetails.inserted) this.state = beforeTailState;
	        }
	        if (!appended) {
	          details = new ChangeDetails();
	          this.state = consistentState;
	          if (checkTail && consistentTail) checkTail.state = consistentTail;
	        }
	      }
	      return details;
	    }
	  }, {
	    key: "_appendPlaceholder",
	    value: function _appendPlaceholder() {
	      return new ChangeDetails();
	    }
	  }, {
	    key: "_appendEager",
	    value: function _appendEager() {
	      return new ChangeDetails();
	    }
	  }, {
	    key: "append",
	    value: function append(str, flags, tail) {
	      if (!isString(str)) throw new Error('value should be string');
	      var details = new ChangeDetails();
	      var checkTail = isString(tail) ? new ContinuousTailDetails(String(tail)) : tail;
	      if (flags !== null && flags !== void 0 && flags.tail) flags._beforeTailState = this.state;
	      for (var ci = 0; ci < str.length; ++ci) {
	        details.aggregate(this._appendChar(str[ci], flags, checkTail));
	      }
	      if (checkTail != null) {
	        details.tailShift += this.appendTail(checkTail).tailShift;
	      }
	      if (this.eager && flags !== null && flags !== void 0 && flags.input && str) {
	        details.aggregate(this._appendEager());
	      }
	      return details;
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      this._value = this.value.slice(0, fromPos) + this.value.slice(toPos);
	      return new ChangeDetails();
	    }
	  }, {
	    key: "withValueRefresh",
	    value: function withValueRefresh(fn) {
	      if (this._refreshing || !this.isInitialized) return fn();
	      this._refreshing = true;
	      var rawInput = this.rawInputValue;
	      var value = this.value;
	      var ret = fn();
	      this.rawInputValue = rawInput;
	      if (this.value && this.value !== value && value.indexOf(this.value) === 0) {
	        this.append(value.slice(this.value.length), {}, '');
	      }
	      delete this._refreshing;
	      return ret;
	    }
	  }, {
	    key: "runIsolated",
	    value: function runIsolated(fn) {
	      if (this._isolated || !this.isInitialized) return fn(this);
	      this._isolated = true;
	      var state = this.state;
	      var ret = fn(this);
	      this.state = state;
	      delete this._isolated;
	      return ret;
	    }
	  }, {
	    key: "doPrepare",
	    value: function doPrepare(str) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      return this.prepare ? this.prepare(str, this, flags) : str;
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate(flags) {
	      return (!this.validate || this.validate(this.value, this, flags)) && (!this.parent || this.parent.doValidate(flags));
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {
	      if (this.commit) this.commit(this.value, this);
	    }
	  }, {
	    key: "doFormat",
	    value: function doFormat(value) {
	      return this.format ? this.format(value, this) : value;
	    }
	  }, {
	    key: "doParse",
	    value: function doParse(str) {
	      return this.parse ? this.parse(str, this) : str;
	    }
	  }, {
	    key: "splice",
	    value: function splice(start, deleteCount, inserted, removeDirection) {
	      var flags = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {
	        input: true
	      };
	      var tailPos = start + deleteCount;
	      var tail = this.extractTail(tailPos);
	      var oldRawValue;
	      if (this.eager) {
	        removeDirection = forceDirection(removeDirection);
	        oldRawValue = this.extractInput(0, tailPos, {
	          raw: true
	        });
	      }
	      var startChangePos = this.nearestInputPos(start, deleteCount > 1 && start !== 0 && !this.eager ? DIRECTION.NONE : removeDirection);
	      var details = new ChangeDetails({
	        tailShift: startChangePos - start
	      }).aggregate(this.remove(startChangePos));
	      if (this.eager && removeDirection !== DIRECTION.NONE && oldRawValue === this.rawInputValue) {
	        if (removeDirection === DIRECTION.FORCE_LEFT) {
	          var valLength;
	          while (oldRawValue === this.rawInputValue && (valLength = this.value.length)) {
	            details.aggregate(new ChangeDetails({
	              tailShift: -1
	            })).aggregate(this.remove(valLength - 1));
	          }
	        } else if (removeDirection === DIRECTION.FORCE_RIGHT) {
	          tail.unshift();
	        }
	      }
	      return details.aggregate(this.append(inserted, flags, tail));
	    }
	  }, {
	    key: "maskEquals",
	    value: function maskEquals(mask) {
	      return this.mask === mask;
	    }
	  }, {
	    key: "typedValueEquals",
	    value: function typedValueEquals(value) {
	      var tval = this.typedValue;
	      return value === tval || Masked.EMPTY_VALUES.includes(value) && Masked.EMPTY_VALUES.includes(tval) || this.doFormat(value) === this.doFormat(this.typedValue);
	    }
	  }]);
	  return Masked;
	}();
	Masked.DEFAULTS = {
	  format: function format(v) {
	    return v;
	  },
	  parse: function parse(v) {
	    return v;
	  }
	};
	Masked.EMPTY_VALUES = [undefined, null, ''];
	IMask.Masked = Masked;

	function maskedClass(mask) {
	  if (mask == null) {
	    throw new Error('mask property should be defined');
	  }
	  if (mask instanceof RegExp) return IMask.MaskedRegExp;
	  if (isString(mask)) return IMask.MaskedPattern;
	  if (mask instanceof Date || mask === Date) return IMask.MaskedDate;
	  if (mask instanceof Number || typeof mask === 'number' || mask === Number) return IMask.MaskedNumber;
	  if (Array.isArray(mask) || mask === Array) return IMask.MaskedDynamic;
	  if (IMask.Masked && mask.prototype instanceof IMask.Masked) return mask;
	  if (mask instanceof IMask.Masked) return mask.constructor;
	  if (mask instanceof Function) return IMask.MaskedFunction;
	  console.warn('Mask not found for mask', mask);
	  return IMask.Masked;
	}
	function createMask(opts) {
	  if (IMask.Masked && opts instanceof IMask.Masked) return opts;
	  opts = Object.assign({}, opts);
	  var mask = opts.mask;
	  if (IMask.Masked && mask instanceof IMask.Masked) return mask;
	  var MaskedClass = maskedClass(mask);
	  if (!MaskedClass) throw new Error('Masked class is not found for provided mask, appropriate module needs to be import manually before creating mask.');
	  return new MaskedClass(opts);
	}
	IMask.createMask = createMask;

	var _excluded$4 = ["mask"];
	var DEFAULT_INPUT_DEFINITIONS = {
	  '0': /\d/,
	  'a': /[\u0041-\u005A\u0061-\u007A\u00AA\u00B5\u00BA\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05D0-\u05EA\u05F0-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u08A0\u08A2-\u08AC\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097F\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C33\u0C35-\u0C39\u0C3D\u0C58\u0C59\u0C60\u0C61\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D60\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F4\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1877\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191C\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19C1-\u19C7\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312D\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FCC\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA697\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA78E\uA790-\uA793\uA7A0-\uA7AA\uA7F8-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA80-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uABC0-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]/,
	  '*': /./
	};
	var PatternInputDefinition = function () {
	  function PatternInputDefinition(opts) {
	    _classCallCheck(this, PatternInputDefinition);
	    var mask = opts.mask,
	        blockOpts = _objectWithoutProperties(opts, _excluded$4);
	    this.masked = createMask({
	      mask: mask
	    });
	    Object.assign(this, blockOpts);
	  }
	  _createClass(PatternInputDefinition, [{
	    key: "reset",
	    value: function reset() {
	      this.isFilled = false;
	      this.masked.reset();
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      if (fromPos === 0 && toPos >= 1) {
	        this.isFilled = false;
	        return this.masked.remove(fromPos, toPos);
	      }
	      return new ChangeDetails();
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this.masked.value || (this.isFilled && !this.isOptional ? this.placeholderChar : '');
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this.masked.unmaskedValue;
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      return Boolean(this.masked.value) || this.isOptional;
	    }
	  }, {
	    key: "_appendChar",
	    value: function _appendChar(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      if (this.isFilled) return new ChangeDetails();
	      var state = this.masked.state;
	      var details = this.masked._appendChar(ch, flags);
	      if (details.inserted && this.doValidate(flags) === false) {
	        details.inserted = details.rawInserted = '';
	        this.masked.state = state;
	      }
	      if (!details.inserted && !this.isOptional && !this.lazy && !flags.input) {
	        details.inserted = this.placeholderChar;
	      }
	      details.skip = !details.inserted && !this.isOptional;
	      this.isFilled = Boolean(details.inserted);
	      return details;
	    }
	  }, {
	    key: "append",
	    value: function append() {
	      var _this$masked;
	      return (_this$masked = this.masked).append.apply(_this$masked, arguments);
	    }
	  }, {
	    key: "_appendPlaceholder",
	    value: function _appendPlaceholder() {
	      var details = new ChangeDetails();
	      if (this.isFilled || this.isOptional) return details;
	      this.isFilled = true;
	      details.inserted = this.placeholderChar;
	      return details;
	    }
	  }, {
	    key: "_appendEager",
	    value: function _appendEager() {
	      return new ChangeDetails();
	    }
	  }, {
	    key: "extractTail",
	    value: function extractTail() {
	      var _this$masked2;
	      return (_this$masked2 = this.masked).extractTail.apply(_this$masked2, arguments);
	    }
	  }, {
	    key: "appendTail",
	    value: function appendTail() {
	      var _this$masked3;
	      return (_this$masked3 = this.masked).appendTail.apply(_this$masked3, arguments);
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var flags = arguments.length > 2 ? arguments[2] : undefined;
	      return this.masked.extractInput(fromPos, toPos, flags);
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos(cursorPos) {
	      var direction = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : DIRECTION.NONE;
	      var minPos = 0;
	      var maxPos = this.value.length;
	      var boundPos = Math.min(Math.max(cursorPos, minPos), maxPos);
	      switch (direction) {
	        case DIRECTION.LEFT:
	        case DIRECTION.FORCE_LEFT:
	          return this.isComplete ? boundPos : minPos;
	        case DIRECTION.RIGHT:
	        case DIRECTION.FORCE_RIGHT:
	          return this.isComplete ? boundPos : maxPos;
	        case DIRECTION.NONE:
	        default:
	          return boundPos;
	      }
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate() {
	      var _this$masked4, _this$parent;
	      return (_this$masked4 = this.masked).doValidate.apply(_this$masked4, arguments) && (!this.parent || (_this$parent = this.parent).doValidate.apply(_this$parent, arguments));
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {
	      this.masked.doCommit();
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        masked: this.masked.state,
	        isFilled: this.isFilled
	      };
	    },
	    set: function set(state) {
	      this.masked.state = state.masked;
	      this.isFilled = state.isFilled;
	    }
	  }]);
	  return PatternInputDefinition;
	}();

	var PatternFixedDefinition = function () {
	  function PatternFixedDefinition(opts) {
	    _classCallCheck(this, PatternFixedDefinition);
	    Object.assign(this, opts);
	    this._value = '';
	    this.isFixed = true;
	  }
	  _createClass(PatternFixedDefinition, [{
	    key: "value",
	    get: function get() {
	      return this._value;
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this.isUnmasking ? this.value : '';
	    }
	  }, {
	    key: "reset",
	    value: function reset() {
	      this._isRawInput = false;
	      this._value = '';
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this._value.length;
	      this._value = this._value.slice(0, fromPos) + this._value.slice(toPos);
	      if (!this._value) this._isRawInput = false;
	      return new ChangeDetails();
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos(cursorPos) {
	      var direction = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : DIRECTION.NONE;
	      var minPos = 0;
	      var maxPos = this._value.length;
	      switch (direction) {
	        case DIRECTION.LEFT:
	        case DIRECTION.FORCE_LEFT:
	          return minPos;
	        case DIRECTION.NONE:
	        case DIRECTION.RIGHT:
	        case DIRECTION.FORCE_RIGHT:
	        default:
	          return maxPos;
	      }
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this._value.length;
	      var flags = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
	      return flags.raw && this._isRawInput && this._value.slice(fromPos, toPos) || '';
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      return true;
	    }
	  }, {
	    key: "isFilled",
	    get: function get() {
	      return Boolean(this._value);
	    }
	  }, {
	    key: "_appendChar",
	    value: function _appendChar(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var details = new ChangeDetails();
	      if (this._value) return details;
	      var appended = this.char === ch;
	      var isResolved = appended && (this.isUnmasking || flags.input || flags.raw) && (!flags.raw || !this.eager) && !flags.tail;
	      if (isResolved) details.rawInserted = this.char;
	      this._value = details.inserted = this.char;
	      this._isRawInput = isResolved && (flags.raw || flags.input);
	      return details;
	    }
	  }, {
	    key: "_appendEager",
	    value: function _appendEager() {
	      return this._appendChar(this.char, {
	        tail: true
	      });
	    }
	  }, {
	    key: "_appendPlaceholder",
	    value: function _appendPlaceholder() {
	      var details = new ChangeDetails();
	      if (this._value) return details;
	      this._value = details.inserted = this.char;
	      return details;
	    }
	  }, {
	    key: "extractTail",
	    value: function extractTail() {
	      arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      return new ContinuousTailDetails('');
	    }
	  }, {
	    key: "appendTail",
	    value: function appendTail(tail) {
	      if (isString(tail)) tail = new ContinuousTailDetails(String(tail));
	      return tail.appendTo(this);
	    }
	  }, {
	    key: "append",
	    value: function append(str, flags, tail) {
	      var details = this._appendChar(str[0], flags);
	      if (tail != null) {
	        details.tailShift += this.appendTail(tail).tailShift;
	      }
	      return details;
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {}
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        _value: this._value,
	        _isRawInput: this._isRawInput
	      };
	    },
	    set: function set(state) {
	      Object.assign(this, state);
	    }
	  }]);
	  return PatternFixedDefinition;
	}();

	var _excluded$3 = ["chunks"];
	var ChunksTailDetails = function () {
	  function ChunksTailDetails() {
	    var chunks = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
	    var from = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
	    _classCallCheck(this, ChunksTailDetails);
	    this.chunks = chunks;
	    this.from = from;
	  }
	  _createClass(ChunksTailDetails, [{
	    key: "toString",
	    value: function toString() {
	      return this.chunks.map(String).join('');
	    }
	  }, {
	    key: "extend",
	    value: function extend(tailChunk) {
	      if (!String(tailChunk)) return;
	      if (isString(tailChunk)) tailChunk = new ContinuousTailDetails(String(tailChunk));
	      var lastChunk = this.chunks[this.chunks.length - 1];
	      var extendLast = lastChunk && (
	      lastChunk.stop === tailChunk.stop || tailChunk.stop == null) &&
	      tailChunk.from === lastChunk.from + lastChunk.toString().length;
	      if (tailChunk instanceof ContinuousTailDetails) {
	        if (extendLast) {
	          lastChunk.extend(tailChunk.toString());
	        } else {
	          this.chunks.push(tailChunk);
	        }
	      } else if (tailChunk instanceof ChunksTailDetails) {
	        if (tailChunk.stop == null) {
	          var firstTailChunk;
	          while (tailChunk.chunks.length && tailChunk.chunks[0].stop == null) {
	            firstTailChunk = tailChunk.chunks.shift();
	            firstTailChunk.from += tailChunk.from;
	            this.extend(firstTailChunk);
	          }
	        }
	        if (tailChunk.toString()) {
	          tailChunk.stop = tailChunk.blockIndex;
	          this.chunks.push(tailChunk);
	        }
	      }
	    }
	  }, {
	    key: "appendTo",
	    value: function appendTo(masked) {
	      if (!(masked instanceof IMask.MaskedPattern)) {
	        var tail = new ContinuousTailDetails(this.toString());
	        return tail.appendTo(masked);
	      }
	      var details = new ChangeDetails();
	      for (var ci = 0; ci < this.chunks.length && !details.skip; ++ci) {
	        var chunk = this.chunks[ci];
	        var lastBlockIter = masked._mapPosToBlock(masked.value.length);
	        var stop = chunk.stop;
	        var chunkBlock = void 0;
	        if (stop != null && (
	        !lastBlockIter || lastBlockIter.index <= stop)) {
	          if (chunk instanceof ChunksTailDetails ||
	          masked._stops.indexOf(stop) >= 0) {
	            details.aggregate(masked._appendPlaceholder(stop));
	          }
	          chunkBlock = chunk instanceof ChunksTailDetails && masked._blocks[stop];
	        }
	        if (chunkBlock) {
	          var tailDetails = chunkBlock.appendTail(chunk);
	          tailDetails.skip = false;
	          details.aggregate(tailDetails);
	          masked._value += tailDetails.inserted;
	          var remainChars = chunk.toString().slice(tailDetails.rawInserted.length);
	          if (remainChars) details.aggregate(masked.append(remainChars, {
	            tail: true
	          }));
	        } else {
	          details.aggregate(masked.append(chunk.toString(), {
	            tail: true
	          }));
	        }
	      }
	      return details;
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        chunks: this.chunks.map(function (c) {
	          return c.state;
	        }),
	        from: this.from,
	        stop: this.stop,
	        blockIndex: this.blockIndex
	      };
	    },
	    set: function set(state) {
	      var chunks = state.chunks,
	          props = _objectWithoutProperties(state, _excluded$3);
	      Object.assign(this, props);
	      this.chunks = chunks.map(function (cstate) {
	        var chunk = "chunks" in cstate ? new ChunksTailDetails() : new ContinuousTailDetails();
	        chunk.state = cstate;
	        return chunk;
	      });
	    }
	  }, {
	    key: "unshift",
	    value: function unshift(beforePos) {
	      if (!this.chunks.length || beforePos != null && this.from >= beforePos) return '';
	      var chunkShiftPos = beforePos != null ? beforePos - this.from : beforePos;
	      var ci = 0;
	      while (ci < this.chunks.length) {
	        var chunk = this.chunks[ci];
	        var shiftChar = chunk.unshift(chunkShiftPos);
	        if (chunk.toString()) {
	          if (!shiftChar) break;
	          ++ci;
	        } else {
	          this.chunks.splice(ci, 1);
	        }
	        if (shiftChar) return shiftChar;
	      }
	      return '';
	    }
	  }, {
	    key: "shift",
	    value: function shift() {
	      if (!this.chunks.length) return '';
	      var ci = this.chunks.length - 1;
	      while (0 <= ci) {
	        var chunk = this.chunks[ci];
	        var shiftChar = chunk.shift();
	        if (chunk.toString()) {
	          if (!shiftChar) break;
	          --ci;
	        } else {
	          this.chunks.splice(ci, 1);
	        }
	        if (shiftChar) return shiftChar;
	      }
	      return '';
	    }
	  }]);
	  return ChunksTailDetails;
	}();

	var PatternCursor = function () {
	  function PatternCursor(masked, pos) {
	    _classCallCheck(this, PatternCursor);
	    this.masked = masked;
	    this._log = [];
	    var _ref = masked._mapPosToBlock(pos) || (pos < 0 ?
	    {
	      index: 0,
	      offset: 0
	    } :
	    {
	      index: this.masked._blocks.length,
	      offset: 0
	    }),
	        offset = _ref.offset,
	        index = _ref.index;
	    this.offset = offset;
	    this.index = index;
	    this.ok = false;
	  }
	  _createClass(PatternCursor, [{
	    key: "block",
	    get: function get() {
	      return this.masked._blocks[this.index];
	    }
	  }, {
	    key: "pos",
	    get: function get() {
	      return this.masked._blockStartPos(this.index) + this.offset;
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return {
	        index: this.index,
	        offset: this.offset,
	        ok: this.ok
	      };
	    },
	    set: function set(s) {
	      Object.assign(this, s);
	    }
	  }, {
	    key: "pushState",
	    value: function pushState() {
	      this._log.push(this.state);
	    }
	  }, {
	    key: "popState",
	    value: function popState() {
	      var s = this._log.pop();
	      this.state = s;
	      return s;
	    }
	  }, {
	    key: "bindBlock",
	    value: function bindBlock() {
	      if (this.block) return;
	      if (this.index < 0) {
	        this.index = 0;
	        this.offset = 0;
	      }
	      if (this.index >= this.masked._blocks.length) {
	        this.index = this.masked._blocks.length - 1;
	        this.offset = this.block.value.length;
	      }
	    }
	  }, {
	    key: "_pushLeft",
	    value: function _pushLeft(fn) {
	      this.pushState();
	      for (this.bindBlock(); 0 <= this.index; --this.index, this.offset = ((_this$block = this.block) === null || _this$block === void 0 ? void 0 : _this$block.value.length) || 0) {
	        var _this$block;
	        if (fn()) return this.ok = true;
	      }
	      return this.ok = false;
	    }
	  }, {
	    key: "_pushRight",
	    value: function _pushRight(fn) {
	      this.pushState();
	      for (this.bindBlock(); this.index < this.masked._blocks.length; ++this.index, this.offset = 0) {
	        if (fn()) return this.ok = true;
	      }
	      return this.ok = false;
	    }
	  }, {
	    key: "pushLeftBeforeFilled",
	    value: function pushLeftBeforeFilled() {
	      var _this = this;
	      return this._pushLeft(function () {
	        if (_this.block.isFixed || !_this.block.value) return;
	        _this.offset = _this.block.nearestInputPos(_this.offset, DIRECTION.FORCE_LEFT);
	        if (_this.offset !== 0) return true;
	      });
	    }
	  }, {
	    key: "pushLeftBeforeInput",
	    value: function pushLeftBeforeInput() {
	      var _this2 = this;
	      return this._pushLeft(function () {
	        if (_this2.block.isFixed) return;
	        _this2.offset = _this2.block.nearestInputPos(_this2.offset, DIRECTION.LEFT);
	        return true;
	      });
	    }
	  }, {
	    key: "pushLeftBeforeRequired",
	    value: function pushLeftBeforeRequired() {
	      var _this3 = this;
	      return this._pushLeft(function () {
	        if (_this3.block.isFixed || _this3.block.isOptional && !_this3.block.value) return;
	        _this3.offset = _this3.block.nearestInputPos(_this3.offset, DIRECTION.LEFT);
	        return true;
	      });
	    }
	  }, {
	    key: "pushRightBeforeFilled",
	    value: function pushRightBeforeFilled() {
	      var _this4 = this;
	      return this._pushRight(function () {
	        if (_this4.block.isFixed || !_this4.block.value) return;
	        _this4.offset = _this4.block.nearestInputPos(_this4.offset, DIRECTION.FORCE_RIGHT);
	        if (_this4.offset !== _this4.block.value.length) return true;
	      });
	    }
	  }, {
	    key: "pushRightBeforeInput",
	    value: function pushRightBeforeInput() {
	      var _this5 = this;
	      return this._pushRight(function () {
	        if (_this5.block.isFixed) return;
	        _this5.offset = _this5.block.nearestInputPos(_this5.offset, DIRECTION.NONE);
	        return true;
	      });
	    }
	  }, {
	    key: "pushRightBeforeRequired",
	    value: function pushRightBeforeRequired() {
	      var _this6 = this;
	      return this._pushRight(function () {
	        if (_this6.block.isFixed || _this6.block.isOptional && !_this6.block.value) return;
	        _this6.offset = _this6.block.nearestInputPos(_this6.offset, DIRECTION.NONE);
	        return true;
	      });
	    }
	  }]);
	  return PatternCursor;
	}();

	var MaskedRegExp = function (_Masked) {
	  _inherits(MaskedRegExp, _Masked);
	  var _super = _createSuper(MaskedRegExp);
	  function MaskedRegExp() {
	    _classCallCheck(this, MaskedRegExp);
	    return _super.apply(this, arguments);
	  }
	  _createClass(MaskedRegExp, [{
	    key: "_update",
	    value:
	    function _update(opts) {
	      if (opts.mask) opts.validate = function (value) {
	        return value.search(opts.mask) >= 0;
	      };
	      _get(_getPrototypeOf(MaskedRegExp.prototype), "_update", this).call(this, opts);
	    }
	  }]);
	  return MaskedRegExp;
	}(Masked);
	IMask.MaskedRegExp = MaskedRegExp;

	var _excluded$2 = ["_blocks"];
	var MaskedPattern = function (_Masked) {
	  _inherits(MaskedPattern, _Masked);
	  var _super = _createSuper(MaskedPattern);
	  function MaskedPattern() {
	    var opts = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    _classCallCheck(this, MaskedPattern);
	    opts.definitions = Object.assign({}, DEFAULT_INPUT_DEFINITIONS, opts.definitions);
	    return _super.call(this, Object.assign({}, MaskedPattern.DEFAULTS, opts));
	  }
	  _createClass(MaskedPattern, [{
	    key: "_update",
	    value: function _update() {
	      var opts = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	      opts.definitions = Object.assign({}, this.definitions, opts.definitions);
	      _get(_getPrototypeOf(MaskedPattern.prototype), "_update", this).call(this, opts);
	      this._rebuildMask();
	    }
	  }, {
	    key: "_rebuildMask",
	    value: function _rebuildMask() {
	      var _this = this;
	      var defs = this.definitions;
	      this._blocks = [];
	      this._stops = [];
	      this._maskedBlocks = {};
	      var pattern = this.mask;
	      if (!pattern || !defs) return;
	      var unmaskingBlock = false;
	      var optionalBlock = false;
	      for (var i = 0; i < pattern.length; ++i) {
	        if (this.blocks) {
	          var _ret = function () {
	            var p = pattern.slice(i);
	            var bNames = Object.keys(_this.blocks).filter(function (bName) {
	              return p.indexOf(bName) === 0;
	            });
	            bNames.sort(function (a, b) {
	              return b.length - a.length;
	            });
	            var bName = bNames[0];
	            if (bName) {
	              var maskedBlock = createMask(Object.assign({
	                parent: _this,
	                lazy: _this.lazy,
	                eager: _this.eager,
	                placeholderChar: _this.placeholderChar,
	                overwrite: _this.overwrite
	              }, _this.blocks[bName]));
	              if (maskedBlock) {
	                _this._blocks.push(maskedBlock);
	                if (!_this._maskedBlocks[bName]) _this._maskedBlocks[bName] = [];
	                _this._maskedBlocks[bName].push(_this._blocks.length - 1);
	              }
	              i += bName.length - 1;
	              return "continue";
	            }
	          }();
	          if (_ret === "continue") continue;
	        }
	        var char = pattern[i];
	        var isInput = (char in defs);
	        if (char === MaskedPattern.STOP_CHAR) {
	          this._stops.push(this._blocks.length);
	          continue;
	        }
	        if (char === '{' || char === '}') {
	          unmaskingBlock = !unmaskingBlock;
	          continue;
	        }
	        if (char === '[' || char === ']') {
	          optionalBlock = !optionalBlock;
	          continue;
	        }
	        if (char === MaskedPattern.ESCAPE_CHAR) {
	          ++i;
	          char = pattern[i];
	          if (!char) break;
	          isInput = false;
	        }
	        var def = isInput ? new PatternInputDefinition({
	          parent: this,
	          lazy: this.lazy,
	          eager: this.eager,
	          placeholderChar: this.placeholderChar,
	          mask: defs[char],
	          isOptional: optionalBlock
	        }) : new PatternFixedDefinition({
	          char: char,
	          eager: this.eager,
	          isUnmasking: unmaskingBlock
	        });
	        this._blocks.push(def);
	      }
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      return Object.assign({}, _get(_getPrototypeOf(MaskedPattern.prototype), "state", this), {
	        _blocks: this._blocks.map(function (b) {
	          return b.state;
	        })
	      });
	    },
	    set: function set(state) {
	      var _blocks = state._blocks,
	          maskedState = _objectWithoutProperties(state, _excluded$2);
	      this._blocks.forEach(function (b, bi) {
	        return b.state = _blocks[bi];
	      });
	      _set(_getPrototypeOf(MaskedPattern.prototype), "state", maskedState, this, true);
	    }
	  }, {
	    key: "reset",
	    value: function reset() {
	      _get(_getPrototypeOf(MaskedPattern.prototype), "reset", this).call(this);
	      this._blocks.forEach(function (b) {
	        return b.reset();
	      });
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      return this._blocks.every(function (b) {
	        return b.isComplete;
	      });
	    }
	  }, {
	    key: "isFilled",
	    get: function get() {
	      return this._blocks.every(function (b) {
	        return b.isFilled;
	      });
	    }
	  }, {
	    key: "isFixed",
	    get: function get() {
	      return this._blocks.every(function (b) {
	        return b.isFixed;
	      });
	    }
	  }, {
	    key: "isOptional",
	    get: function get() {
	      return this._blocks.every(function (b) {
	        return b.isOptional;
	      });
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {
	      this._blocks.forEach(function (b) {
	        return b.doCommit();
	      });
	      _get(_getPrototypeOf(MaskedPattern.prototype), "doCommit", this).call(this);
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this._blocks.reduce(function (str, b) {
	        return str += b.unmaskedValue;
	      }, '');
	    },
	    set: function set(unmaskedValue) {
	      _set(_getPrototypeOf(MaskedPattern.prototype), "unmaskedValue", unmaskedValue, this, true);
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this._blocks.reduce(function (str, b) {
	        return str += b.value;
	      }, '');
	    },
	    set: function set(value) {
	      _set(_getPrototypeOf(MaskedPattern.prototype), "value", value, this, true);
	    }
	  }, {
	    key: "appendTail",
	    value: function appendTail(tail) {
	      return _get(_getPrototypeOf(MaskedPattern.prototype), "appendTail", this).call(this, tail).aggregate(this._appendPlaceholder());
	    }
	  }, {
	    key: "_appendEager",
	    value: function _appendEager() {
	      var _this$_mapPosToBlock;
	      var details = new ChangeDetails();
	      var startBlockIndex = (_this$_mapPosToBlock = this._mapPosToBlock(this.value.length)) === null || _this$_mapPosToBlock === void 0 ? void 0 : _this$_mapPosToBlock.index;
	      if (startBlockIndex == null) return details;
	      if (this._blocks[startBlockIndex].isFilled) ++startBlockIndex;
	      for (var bi = startBlockIndex; bi < this._blocks.length; ++bi) {
	        var d = this._blocks[bi]._appendEager();
	        if (!d.inserted) break;
	        details.aggregate(d);
	      }
	      return details;
	    }
	  }, {
	    key: "_appendCharRaw",
	    value: function _appendCharRaw(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var blockIter = this._mapPosToBlock(this.value.length);
	      var details = new ChangeDetails();
	      if (!blockIter) return details;
	      for (var bi = blockIter.index;; ++bi) {
	        var _flags$_beforeTailSta, _flags$_beforeTailSta2;
	        var _block = this._blocks[bi];
	        if (!_block) break;
	        var blockDetails = _block._appendChar(ch, Object.assign({}, flags, {
	          _beforeTailState: (_flags$_beforeTailSta = flags._beforeTailState) === null || _flags$_beforeTailSta === void 0 ? void 0 : (_flags$_beforeTailSta2 = _flags$_beforeTailSta._blocks) === null || _flags$_beforeTailSta2 === void 0 ? void 0 : _flags$_beforeTailSta2[bi]
	        }));
	        var skip = blockDetails.skip;
	        details.aggregate(blockDetails);
	        if (skip || blockDetails.rawInserted) break;
	      }
	      return details;
	    }
	  }, {
	    key: "extractTail",
	    value: function extractTail() {
	      var _this2 = this;
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var chunkTail = new ChunksTailDetails();
	      if (fromPos === toPos) return chunkTail;
	      this._forEachBlocksInRange(fromPos, toPos, function (b, bi, bFromPos, bToPos) {
	        var blockChunk = b.extractTail(bFromPos, bToPos);
	        blockChunk.stop = _this2._findStopBefore(bi);
	        blockChunk.from = _this2._blockStartPos(bi);
	        if (blockChunk instanceof ChunksTailDetails) blockChunk.blockIndex = bi;
	        chunkTail.extend(blockChunk);
	      });
	      return chunkTail;
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var flags = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
	      if (fromPos === toPos) return '';
	      var input = '';
	      this._forEachBlocksInRange(fromPos, toPos, function (b, _, fromPos, toPos) {
	        input += b.extractInput(fromPos, toPos, flags);
	      });
	      return input;
	    }
	  }, {
	    key: "_findStopBefore",
	    value: function _findStopBefore(blockIndex) {
	      var stopBefore;
	      for (var si = 0; si < this._stops.length; ++si) {
	        var stop = this._stops[si];
	        if (stop <= blockIndex) stopBefore = stop;else break;
	      }
	      return stopBefore;
	    }
	  }, {
	    key: "_appendPlaceholder",
	    value: function _appendPlaceholder(toBlockIndex) {
	      var _this3 = this;
	      var details = new ChangeDetails();
	      if (this.lazy && toBlockIndex == null) return details;
	      var startBlockIter = this._mapPosToBlock(this.value.length);
	      if (!startBlockIter) return details;
	      var startBlockIndex = startBlockIter.index;
	      var endBlockIndex = toBlockIndex != null ? toBlockIndex : this._blocks.length;
	      this._blocks.slice(startBlockIndex, endBlockIndex).forEach(function (b) {
	        if (!b.lazy || toBlockIndex != null) {
	          var args = b._blocks != null ? [b._blocks.length] : [];
	          var bDetails = b._appendPlaceholder.apply(b, args);
	          _this3._value += bDetails.inserted;
	          details.aggregate(bDetails);
	        }
	      });
	      return details;
	    }
	  }, {
	    key: "_mapPosToBlock",
	    value: function _mapPosToBlock(pos) {
	      var accVal = '';
	      for (var bi = 0; bi < this._blocks.length; ++bi) {
	        var _block2 = this._blocks[bi];
	        var blockStartPos = accVal.length;
	        accVal += _block2.value;
	        if (pos <= accVal.length) {
	          return {
	            index: bi,
	            offset: pos - blockStartPos
	          };
	        }
	      }
	    }
	  }, {
	    key: "_blockStartPos",
	    value: function _blockStartPos(blockIndex) {
	      return this._blocks.slice(0, blockIndex).reduce(function (pos, b) {
	        return pos += b.value.length;
	      }, 0);
	    }
	  }, {
	    key: "_forEachBlocksInRange",
	    value: function _forEachBlocksInRange(fromPos) {
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var fn = arguments.length > 2 ? arguments[2] : undefined;
	      var fromBlockIter = this._mapPosToBlock(fromPos);
	      if (fromBlockIter) {
	        var toBlockIter = this._mapPosToBlock(toPos);
	        var isSameBlock = toBlockIter && fromBlockIter.index === toBlockIter.index;
	        var fromBlockStartPos = fromBlockIter.offset;
	        var fromBlockEndPos = toBlockIter && isSameBlock ? toBlockIter.offset : this._blocks[fromBlockIter.index].value.length;
	        fn(this._blocks[fromBlockIter.index], fromBlockIter.index, fromBlockStartPos, fromBlockEndPos);
	        if (toBlockIter && !isSameBlock) {
	          for (var bi = fromBlockIter.index + 1; bi < toBlockIter.index; ++bi) {
	            fn(this._blocks[bi], bi, 0, this._blocks[bi].value.length);
	          }
	          fn(this._blocks[toBlockIter.index], toBlockIter.index, 0, toBlockIter.offset);
	        }
	      }
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var removeDetails = _get(_getPrototypeOf(MaskedPattern.prototype), "remove", this).call(this, fromPos, toPos);
	      this._forEachBlocksInRange(fromPos, toPos, function (b, _, bFromPos, bToPos) {
	        removeDetails.aggregate(b.remove(bFromPos, bToPos));
	      });
	      return removeDetails;
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos(cursorPos) {
	      var direction = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : DIRECTION.NONE;
	      if (!this._blocks.length) return 0;
	      var cursor = new PatternCursor(this, cursorPos);
	      if (direction === DIRECTION.NONE) {
	        if (cursor.pushRightBeforeInput()) return cursor.pos;
	        cursor.popState();
	        if (cursor.pushLeftBeforeInput()) return cursor.pos;
	        return this.value.length;
	      }
	      if (direction === DIRECTION.LEFT || direction === DIRECTION.FORCE_LEFT) {
	        if (direction === DIRECTION.LEFT) {
	          cursor.pushRightBeforeFilled();
	          if (cursor.ok && cursor.pos === cursorPos) return cursorPos;
	          cursor.popState();
	        }
	        cursor.pushLeftBeforeInput();
	        cursor.pushLeftBeforeRequired();
	        cursor.pushLeftBeforeFilled();
	        if (direction === DIRECTION.LEFT) {
	          cursor.pushRightBeforeInput();
	          cursor.pushRightBeforeRequired();
	          if (cursor.ok && cursor.pos <= cursorPos) return cursor.pos;
	          cursor.popState();
	          if (cursor.ok && cursor.pos <= cursorPos) return cursor.pos;
	          cursor.popState();
	        }
	        if (cursor.ok) return cursor.pos;
	        if (direction === DIRECTION.FORCE_LEFT) return 0;
	        cursor.popState();
	        if (cursor.ok) return cursor.pos;
	        cursor.popState();
	        if (cursor.ok) return cursor.pos;
	        return 0;
	      }
	      if (direction === DIRECTION.RIGHT || direction === DIRECTION.FORCE_RIGHT) {
	        cursor.pushRightBeforeInput();
	        cursor.pushRightBeforeRequired();
	        if (cursor.pushRightBeforeFilled()) return cursor.pos;
	        if (direction === DIRECTION.FORCE_RIGHT) return this.value.length;
	        cursor.popState();
	        if (cursor.ok) return cursor.pos;
	        cursor.popState();
	        if (cursor.ok) return cursor.pos;
	        return this.nearestInputPos(cursorPos, DIRECTION.LEFT);
	      }
	      return cursorPos;
	    }
	  }, {
	    key: "maskedBlock",
	    value: function maskedBlock(name) {
	      return this.maskedBlocks(name)[0];
	    }
	  }, {
	    key: "maskedBlocks",
	    value: function maskedBlocks(name) {
	      var _this4 = this;
	      var indices = this._maskedBlocks[name];
	      if (!indices) return [];
	      return indices.map(function (gi) {
	        return _this4._blocks[gi];
	      });
	    }
	  }]);
	  return MaskedPattern;
	}(Masked);
	MaskedPattern.DEFAULTS = {
	  lazy: true,
	  placeholderChar: '_'
	};
	MaskedPattern.STOP_CHAR = '`';
	MaskedPattern.ESCAPE_CHAR = '\\';
	MaskedPattern.InputDefinition = PatternInputDefinition;
	MaskedPattern.FixedDefinition = PatternFixedDefinition;
	IMask.MaskedPattern = MaskedPattern;

	var MaskedRange = function (_MaskedPattern) {
	  _inherits(MaskedRange, _MaskedPattern);
	  var _super = _createSuper(MaskedRange);
	  function MaskedRange() {
	    _classCallCheck(this, MaskedRange);
	    return _super.apply(this, arguments);
	  }
	  _createClass(MaskedRange, [{
	    key: "_matchFrom",
	    get:
	    function get() {
	      return this.maxLength - String(this.from).length;
	    }
	  }, {
	    key: "_update",
	    value: function _update(opts) {
	      opts = Object.assign({
	        to: this.to || 0,
	        from: this.from || 0,
	        maxLength: this.maxLength || 0
	      }, opts);
	      var maxLength = String(opts.to).length;
	      if (opts.maxLength != null) maxLength = Math.max(maxLength, opts.maxLength);
	      opts.maxLength = maxLength;
	      var fromStr = String(opts.from).padStart(maxLength, '0');
	      var toStr = String(opts.to).padStart(maxLength, '0');
	      var sameCharsCount = 0;
	      while (sameCharsCount < toStr.length && toStr[sameCharsCount] === fromStr[sameCharsCount]) {
	        ++sameCharsCount;
	      }
	      opts.mask = toStr.slice(0, sameCharsCount).replace(/0/g, '\\0') + '0'.repeat(maxLength - sameCharsCount);
	      _get(_getPrototypeOf(MaskedRange.prototype), "_update", this).call(this, opts);
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      return _get(_getPrototypeOf(MaskedRange.prototype), "isComplete", this) && Boolean(this.value);
	    }
	  }, {
	    key: "boundaries",
	    value: function boundaries(str) {
	      var minstr = '';
	      var maxstr = '';
	      var _ref = str.match(/^(\D*)(\d*)(\D*)/) || [],
	          _ref2 = _slicedToArray(_ref, 3),
	          placeholder = _ref2[1],
	          num = _ref2[2];
	      if (num) {
	        minstr = '0'.repeat(placeholder.length) + num;
	        maxstr = '9'.repeat(placeholder.length) + num;
	      }
	      minstr = minstr.padEnd(this.maxLength, '0');
	      maxstr = maxstr.padEnd(this.maxLength, '9');
	      return [minstr, maxstr];
	    }
	  }, {
	    key: "doPrepare",
	    value: function doPrepare(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var details;
	      var _normalizePrepare = normalizePrepare(_get(_getPrototypeOf(MaskedRange.prototype), "doPrepare", this).call(this, ch.replace(/\D/g, ''), flags));
	      var _normalizePrepare2 = _slicedToArray(_normalizePrepare, 2);
	      ch = _normalizePrepare2[0];
	      details = _normalizePrepare2[1];
	      if (!this.autofix || !ch) return ch;
	      var fromStr = String(this.from).padStart(this.maxLength, '0');
	      var toStr = String(this.to).padStart(this.maxLength, '0');
	      var nextVal = this.value + ch;
	      if (nextVal.length > this.maxLength) return '';
	      var _this$boundaries = this.boundaries(nextVal),
	          _this$boundaries2 = _slicedToArray(_this$boundaries, 2),
	          minstr = _this$boundaries2[0],
	          maxstr = _this$boundaries2[1];
	      if (Number(maxstr) < this.from) return fromStr[nextVal.length - 1];
	      if (Number(minstr) > this.to) {
	        if (this.autofix === 'pad' && nextVal.length < this.maxLength) {
	          return ['', details.aggregate(this.append(fromStr[nextVal.length - 1] + ch, flags))];
	        }
	        return toStr[nextVal.length - 1];
	      }
	      return ch;
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate() {
	      var _get2;
	      var str = this.value;
	      var firstNonZero = str.search(/[^0]/);
	      if (firstNonZero === -1 && str.length <= this._matchFrom) return true;
	      var _this$boundaries3 = this.boundaries(str),
	          _this$boundaries4 = _slicedToArray(_this$boundaries3, 2),
	          minstr = _this$boundaries4[0],
	          maxstr = _this$boundaries4[1];
	      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
	        args[_key] = arguments[_key];
	      }
	      return this.from <= Number(maxstr) && Number(minstr) <= this.to && (_get2 = _get(_getPrototypeOf(MaskedRange.prototype), "doValidate", this)).call.apply(_get2, [this].concat(args));
	    }
	  }]);
	  return MaskedRange;
	}(MaskedPattern);
	IMask.MaskedRange = MaskedRange;

	var MaskedDate = function (_MaskedPattern) {
	  _inherits(MaskedDate, _MaskedPattern);
	  var _super = _createSuper(MaskedDate);
	  function MaskedDate(opts) {
	    _classCallCheck(this, MaskedDate);
	    return _super.call(this, Object.assign({}, MaskedDate.DEFAULTS, opts));
	  }
	  _createClass(MaskedDate, [{
	    key: "_update",
	    value: function _update(opts) {
	      if (opts.mask === Date) delete opts.mask;
	      if (opts.pattern) opts.mask = opts.pattern;
	      var blocks = opts.blocks;
	      opts.blocks = Object.assign({}, MaskedDate.GET_DEFAULT_BLOCKS());
	      if (opts.min) opts.blocks.Y.from = opts.min.getFullYear();
	      if (opts.max) opts.blocks.Y.to = opts.max.getFullYear();
	      if (opts.min && opts.max && opts.blocks.Y.from === opts.blocks.Y.to) {
	        opts.blocks.m.from = opts.min.getMonth() + 1;
	        opts.blocks.m.to = opts.max.getMonth() + 1;
	        if (opts.blocks.m.from === opts.blocks.m.to) {
	          opts.blocks.d.from = opts.min.getDate();
	          opts.blocks.d.to = opts.max.getDate();
	        }
	      }
	      Object.assign(opts.blocks, this.blocks, blocks);
	      Object.keys(opts.blocks).forEach(function (bk) {
	        var b = opts.blocks[bk];
	        if (!('autofix' in b) && 'autofix' in opts) b.autofix = opts.autofix;
	      });
	      _get(_getPrototypeOf(MaskedDate.prototype), "_update", this).call(this, opts);
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate() {
	      var _get2;
	      var date = this.date;
	      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
	        args[_key] = arguments[_key];
	      }
	      return (_get2 = _get(_getPrototypeOf(MaskedDate.prototype), "doValidate", this)).call.apply(_get2, [this].concat(args)) && (!this.isComplete || this.isDateExist(this.value) && date != null && (this.min == null || this.min <= date) && (this.max == null || date <= this.max));
	    }
	  }, {
	    key: "isDateExist",
	    value: function isDateExist(str) {
	      return this.format(this.parse(str, this), this).indexOf(str) >= 0;
	    }
	  }, {
	    key: "date",
	    get: function get() {
	      return this.typedValue;
	    },
	    set: function set(date) {
	      this.typedValue = date;
	    }
	  }, {
	    key: "typedValue",
	    get: function get() {
	      return this.isComplete ? _get(_getPrototypeOf(MaskedDate.prototype), "typedValue", this) : null;
	    },
	    set: function set(value) {
	      _set(_getPrototypeOf(MaskedDate.prototype), "typedValue", value, this, true);
	    }
	  }, {
	    key: "maskEquals",
	    value: function maskEquals(mask) {
	      return mask === Date || _get(_getPrototypeOf(MaskedDate.prototype), "maskEquals", this).call(this, mask);
	    }
	  }]);
	  return MaskedDate;
	}(MaskedPattern);
	MaskedDate.DEFAULTS = {
	  pattern: 'd{.}`m{.}`Y',
	  format: function format(date) {
	    if (!date) return '';
	    var day = String(date.getDate()).padStart(2, '0');
	    var month = String(date.getMonth() + 1).padStart(2, '0');
	    var year = date.getFullYear();
	    return [day, month, year].join('.');
	  },
	  parse: function parse(str) {
	    var _str$split = str.split('.'),
	        _str$split2 = _slicedToArray(_str$split, 3),
	        day = _str$split2[0],
	        month = _str$split2[1],
	        year = _str$split2[2];
	    return new Date(year, month - 1, day);
	  }
	};
	MaskedDate.GET_DEFAULT_BLOCKS = function () {
	  return {
	    d: {
	      mask: MaskedRange,
	      from: 1,
	      to: 31,
	      maxLength: 2
	    },
	    m: {
	      mask: MaskedRange,
	      from: 1,
	      to: 12,
	      maxLength: 2
	    },
	    Y: {
	      mask: MaskedRange,
	      from: 1900,
	      to: 9999
	    }
	  };
	};
	IMask.MaskedDate = MaskedDate;

	var MaskElement = function () {
	  function MaskElement() {
	    _classCallCheck(this, MaskElement);
	  }
	  _createClass(MaskElement, [{
	    key: "selectionStart",
	    get:
	    function get() {
	      var start;
	      try {
	        start = this._unsafeSelectionStart;
	      } catch (e) {}
	      return start != null ? start : this.value.length;
	    }
	  }, {
	    key: "selectionEnd",
	    get: function get() {
	      var end;
	      try {
	        end = this._unsafeSelectionEnd;
	      } catch (e) {}
	      return end != null ? end : this.value.length;
	    }
	  }, {
	    key: "select",
	    value: function select(start, end) {
	      if (start == null || end == null || start === this.selectionStart && end === this.selectionEnd) return;
	      try {
	        this._unsafeSelect(start, end);
	      } catch (e) {}
	    }
	  }, {
	    key: "_unsafeSelect",
	    value: function _unsafeSelect(start, end) {}
	  }, {
	    key: "isActive",
	    get: function get() {
	      return false;
	    }
	  }, {
	    key: "bindEvents",
	    value: function bindEvents(handlers) {}
	  }, {
	    key: "unbindEvents",
	    value: function unbindEvents() {}
	  }]);
	  return MaskElement;
	}();
	IMask.MaskElement = MaskElement;

	var HTMLMaskElement = function (_MaskElement) {
	  _inherits(HTMLMaskElement, _MaskElement);
	  var _super = _createSuper(HTMLMaskElement);
	  function HTMLMaskElement(input) {
	    var _this;
	    _classCallCheck(this, HTMLMaskElement);
	    _this = _super.call(this);
	    _this.input = input;
	    _this._handlers = {};
	    return _this;
	  }
	  _createClass(HTMLMaskElement, [{
	    key: "rootElement",
	    get: function get() {
	      var _this$input$getRootNo, _this$input$getRootNo2, _this$input;
	      return (_this$input$getRootNo = (_this$input$getRootNo2 = (_this$input = this.input).getRootNode) === null || _this$input$getRootNo2 === void 0 ? void 0 : _this$input$getRootNo2.call(_this$input)) !== null && _this$input$getRootNo !== void 0 ? _this$input$getRootNo : document;
	    }
	  }, {
	    key: "isActive",
	    get: function get() {
	      return this.input === this.rootElement.activeElement;
	    }
	  }, {
	    key: "_unsafeSelectionStart",
	    get: function get() {
	      return this.input.selectionStart;
	    }
	  }, {
	    key: "_unsafeSelectionEnd",
	    get: function get() {
	      return this.input.selectionEnd;
	    }
	  }, {
	    key: "_unsafeSelect",
	    value: function _unsafeSelect(start, end) {
	      this.input.setSelectionRange(start, end);
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this.input.value;
	    },
	    set: function set(value) {
	      this.input.value = value;
	    }
	  }, {
	    key: "bindEvents",
	    value: function bindEvents(handlers) {
	      var _this2 = this;
	      Object.keys(handlers).forEach(function (event) {
	        return _this2._toggleEventHandler(HTMLMaskElement.EVENTS_MAP[event], handlers[event]);
	      });
	    }
	  }, {
	    key: "unbindEvents",
	    value: function unbindEvents() {
	      var _this3 = this;
	      Object.keys(this._handlers).forEach(function (event) {
	        return _this3._toggleEventHandler(event);
	      });
	    }
	  }, {
	    key: "_toggleEventHandler",
	    value: function _toggleEventHandler(event, handler) {
	      if (this._handlers[event]) {
	        this.input.removeEventListener(event, this._handlers[event]);
	        delete this._handlers[event];
	      }
	      if (handler) {
	        this.input.addEventListener(event, handler);
	        this._handlers[event] = handler;
	      }
	    }
	  }]);
	  return HTMLMaskElement;
	}(MaskElement);
	HTMLMaskElement.EVENTS_MAP = {
	  selectionChange: 'keydown',
	  input: 'input',
	  drop: 'drop',
	  click: 'click',
	  focus: 'focus',
	  commit: 'blur'
	};
	IMask.HTMLMaskElement = HTMLMaskElement;

	var HTMLContenteditableMaskElement = function (_HTMLMaskElement) {
	  _inherits(HTMLContenteditableMaskElement, _HTMLMaskElement);
	  var _super = _createSuper(HTMLContenteditableMaskElement);
	  function HTMLContenteditableMaskElement() {
	    _classCallCheck(this, HTMLContenteditableMaskElement);
	    return _super.apply(this, arguments);
	  }
	  _createClass(HTMLContenteditableMaskElement, [{
	    key: "_unsafeSelectionStart",
	    get:
	    function get() {
	      var root = this.rootElement;
	      var selection = root.getSelection && root.getSelection();
	      var anchorOffset = selection && selection.anchorOffset;
	      var focusOffset = selection && selection.focusOffset;
	      if (focusOffset == null || anchorOffset == null || anchorOffset < focusOffset) {
	        return anchorOffset;
	      }
	      return focusOffset;
	    }
	  }, {
	    key: "_unsafeSelectionEnd",
	    get: function get() {
	      var root = this.rootElement;
	      var selection = root.getSelection && root.getSelection();
	      var anchorOffset = selection && selection.anchorOffset;
	      var focusOffset = selection && selection.focusOffset;
	      if (focusOffset == null || anchorOffset == null || anchorOffset > focusOffset) {
	        return anchorOffset;
	      }
	      return focusOffset;
	    }
	  }, {
	    key: "_unsafeSelect",
	    value: function _unsafeSelect(start, end) {
	      if (!this.rootElement.createRange) return;
	      var range = this.rootElement.createRange();
	      range.setStart(this.input.firstChild || this.input, start);
	      range.setEnd(this.input.lastChild || this.input, end);
	      var root = this.rootElement;
	      var selection = root.getSelection && root.getSelection();
	      if (selection) {
	        selection.removeAllRanges();
	        selection.addRange(range);
	      }
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this.input.textContent;
	    },
	    set: function set(value) {
	      this.input.textContent = value;
	    }
	  }]);
	  return HTMLContenteditableMaskElement;
	}(HTMLMaskElement);
	IMask.HTMLContenteditableMaskElement = HTMLContenteditableMaskElement;

	var _excluded$1 = ["mask"];
	var InputMask = function () {
	  function InputMask(el, opts) {
	    _classCallCheck(this, InputMask);
	    this.el = el instanceof MaskElement ? el : el.isContentEditable && el.tagName !== 'INPUT' && el.tagName !== 'TEXTAREA' ? new HTMLContenteditableMaskElement(el) : new HTMLMaskElement(el);
	    this.masked = createMask(opts);
	    this._listeners = {};
	    this._value = '';
	    this._unmaskedValue = '';
	    this._saveSelection = this._saveSelection.bind(this);
	    this._onInput = this._onInput.bind(this);
	    this._onChange = this._onChange.bind(this);
	    this._onDrop = this._onDrop.bind(this);
	    this._onFocus = this._onFocus.bind(this);
	    this._onClick = this._onClick.bind(this);
	    this.alignCursor = this.alignCursor.bind(this);
	    this.alignCursorFriendly = this.alignCursorFriendly.bind(this);
	    this._bindEvents();
	    this.updateValue();
	    this._onChange();
	  }
	  _createClass(InputMask, [{
	    key: "mask",
	    get: function get() {
	      return this.masked.mask;
	    },
	    set: function set(mask) {
	      if (this.maskEquals(mask)) return;
	      if (!(mask instanceof IMask.Masked) && this.masked.constructor === maskedClass(mask)) {
	        this.masked.updateOptions({
	          mask: mask
	        });
	        return;
	      }
	      var masked = createMask({
	        mask: mask
	      });
	      masked.unmaskedValue = this.masked.unmaskedValue;
	      this.masked = masked;
	    }
	  }, {
	    key: "maskEquals",
	    value: function maskEquals(mask) {
	      var _this$masked;
	      return mask == null || ((_this$masked = this.masked) === null || _this$masked === void 0 ? void 0 : _this$masked.maskEquals(mask));
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this._value;
	    },
	    set: function set(str) {
	      if (this.value === str) return;
	      this.masked.value = str;
	      this.updateControl();
	      this.alignCursor();
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this._unmaskedValue;
	    },
	    set: function set(str) {
	      if (this.unmaskedValue === str) return;
	      this.masked.unmaskedValue = str;
	      this.updateControl();
	      this.alignCursor();
	    }
	  }, {
	    key: "typedValue",
	    get: function get() {
	      return this.masked.typedValue;
	    },
	    set: function set(val) {
	      if (this.masked.typedValueEquals(val)) return;
	      this.masked.typedValue = val;
	      this.updateControl();
	      this.alignCursor();
	    }
	  }, {
	    key: "_bindEvents",
	    value: function _bindEvents() {
	      this.el.bindEvents({
	        selectionChange: this._saveSelection,
	        input: this._onInput,
	        drop: this._onDrop,
	        click: this._onClick,
	        focus: this._onFocus,
	        commit: this._onChange
	      });
	    }
	  }, {
	    key: "_unbindEvents",
	    value: function _unbindEvents() {
	      if (this.el) this.el.unbindEvents();
	    }
	  }, {
	    key: "_fireEvent",
	    value: function _fireEvent(ev) {
	      for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
	        args[_key - 1] = arguments[_key];
	      }
	      var listeners = this._listeners[ev];
	      if (!listeners) return;
	      listeners.forEach(function (l) {
	        return l.apply(void 0, args);
	      });
	    }
	  }, {
	    key: "selectionStart",
	    get: function get() {
	      return this._cursorChanging ? this._changingCursorPos : this.el.selectionStart;
	    }
	  }, {
	    key: "cursorPos",
	    get: function get() {
	      return this._cursorChanging ? this._changingCursorPos : this.el.selectionEnd;
	    },
	    set: function set(pos) {
	      if (!this.el || !this.el.isActive) return;
	      this.el.select(pos, pos);
	      this._saveSelection();
	    }
	  }, {
	    key: "_saveSelection",
	    value: function
	    _saveSelection() {
	      if (this.value !== this.el.value) {
	        console.warn('Element value was changed outside of mask. Syncronize mask using `mask.updateValue()` to work properly.');
	      }
	      this._selection = {
	        start: this.selectionStart,
	        end: this.cursorPos
	      };
	    }
	  }, {
	    key: "updateValue",
	    value: function updateValue() {
	      this.masked.value = this.el.value;
	      this._value = this.masked.value;
	    }
	  }, {
	    key: "updateControl",
	    value: function updateControl() {
	      var newUnmaskedValue = this.masked.unmaskedValue;
	      var newValue = this.masked.value;
	      var isChanged = this.unmaskedValue !== newUnmaskedValue || this.value !== newValue;
	      this._unmaskedValue = newUnmaskedValue;
	      this._value = newValue;
	      if (this.el.value !== newValue) this.el.value = newValue;
	      if (isChanged) this._fireChangeEvents();
	    }
	  }, {
	    key: "updateOptions",
	    value: function updateOptions(opts) {
	      var mask = opts.mask,
	          restOpts = _objectWithoutProperties(opts, _excluded$1);
	      var updateMask = !this.maskEquals(mask);
	      var updateOpts = !objectIncludes(this.masked, restOpts);
	      if (updateMask) this.mask = mask;
	      if (updateOpts) this.masked.updateOptions(restOpts);
	      if (updateMask || updateOpts) this.updateControl();
	    }
	  }, {
	    key: "updateCursor",
	    value: function updateCursor(cursorPos) {
	      if (cursorPos == null) return;
	      this.cursorPos = cursorPos;
	      this._delayUpdateCursor(cursorPos);
	    }
	  }, {
	    key: "_delayUpdateCursor",
	    value: function _delayUpdateCursor(cursorPos) {
	      var _this = this;
	      this._abortUpdateCursor();
	      this._changingCursorPos = cursorPos;
	      this._cursorChanging = setTimeout(function () {
	        if (!_this.el) return;
	        _this.cursorPos = _this._changingCursorPos;
	        _this._abortUpdateCursor();
	      }, 10);
	    }
	  }, {
	    key: "_fireChangeEvents",
	    value: function _fireChangeEvents() {
	      this._fireEvent('accept', this._inputEvent);
	      if (this.masked.isComplete) this._fireEvent('complete', this._inputEvent);
	    }
	  }, {
	    key: "_abortUpdateCursor",
	    value: function _abortUpdateCursor() {
	      if (this._cursorChanging) {
	        clearTimeout(this._cursorChanging);
	        delete this._cursorChanging;
	      }
	    }
	  }, {
	    key: "alignCursor",
	    value: function alignCursor() {
	      this.cursorPos = this.masked.nearestInputPos(this.masked.nearestInputPos(this.cursorPos, DIRECTION.LEFT));
	    }
	  }, {
	    key: "alignCursorFriendly",
	    value: function alignCursorFriendly() {
	      if (this.selectionStart !== this.cursorPos) return;
	      this.alignCursor();
	    }
	  }, {
	    key: "on",
	    value: function on(ev, handler) {
	      if (!this._listeners[ev]) this._listeners[ev] = [];
	      this._listeners[ev].push(handler);
	      return this;
	    }
	  }, {
	    key: "off",
	    value: function off(ev, handler) {
	      if (!this._listeners[ev]) return this;
	      if (!handler) {
	        delete this._listeners[ev];
	        return this;
	      }
	      var hIndex = this._listeners[ev].indexOf(handler);
	      if (hIndex >= 0) this._listeners[ev].splice(hIndex, 1);
	      return this;
	    }
	  }, {
	    key: "_onInput",
	    value: function _onInput(e) {
	      this._inputEvent = e;
	      this._abortUpdateCursor();
	      if (!this._selection) return this.updateValue();
	      var details = new ActionDetails(
	      this.el.value, this.cursorPos,
	      this.value, this._selection);
	      var oldRawValue = this.masked.rawInputValue;
	      var offset = this.masked.splice(details.startChangePos, details.removed.length, details.inserted, details.removeDirection, {
	        input: true,
	        raw: true
	      }).offset;
	      var removeDirection = oldRawValue === this.masked.rawInputValue ? details.removeDirection : DIRECTION.NONE;
	      var cursorPos = this.masked.nearestInputPos(details.startChangePos + offset, removeDirection);
	      if (removeDirection !== DIRECTION.NONE) cursorPos = this.masked.nearestInputPos(cursorPos, DIRECTION.NONE);
	      this.updateControl();
	      this.updateCursor(cursorPos);
	      delete this._inputEvent;
	    }
	  }, {
	    key: "_onChange",
	    value: function _onChange() {
	      if (this.value !== this.el.value) {
	        this.updateValue();
	      }
	      this.masked.doCommit();
	      this.updateControl();
	      this._saveSelection();
	    }
	  }, {
	    key: "_onDrop",
	    value: function _onDrop(ev) {
	      ev.preventDefault();
	      ev.stopPropagation();
	    }
	  }, {
	    key: "_onFocus",
	    value: function _onFocus(ev) {
	      this.alignCursorFriendly();
	    }
	  }, {
	    key: "_onClick",
	    value: function _onClick(ev) {
	      this.alignCursorFriendly();
	    }
	  }, {
	    key: "destroy",
	    value: function destroy() {
	      this._unbindEvents();
	      this._listeners.length = 0;
	      delete this.el;
	    }
	  }]);
	  return InputMask;
	}();
	IMask.InputMask = InputMask;

	var MaskedEnum = function (_MaskedPattern) {
	  _inherits(MaskedEnum, _MaskedPattern);
	  var _super = _createSuper(MaskedEnum);
	  function MaskedEnum() {
	    _classCallCheck(this, MaskedEnum);
	    return _super.apply(this, arguments);
	  }
	  _createClass(MaskedEnum, [{
	    key: "_update",
	    value:
	    function _update(opts) {
	      if (opts.enum) opts.mask = '*'.repeat(opts.enum[0].length);
	      _get(_getPrototypeOf(MaskedEnum.prototype), "_update", this).call(this, opts);
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate() {
	      var _this = this,
	          _get2;
	      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
	        args[_key] = arguments[_key];
	      }
	      return this.enum.some(function (e) {
	        return e.indexOf(_this.unmaskedValue) >= 0;
	      }) && (_get2 = _get(_getPrototypeOf(MaskedEnum.prototype), "doValidate", this)).call.apply(_get2, [this].concat(args));
	    }
	  }]);
	  return MaskedEnum;
	}(MaskedPattern);
	IMask.MaskedEnum = MaskedEnum;

	var MaskedNumber = function (_Masked) {
	  _inherits(MaskedNumber, _Masked);
	  var _super = _createSuper(MaskedNumber);
	  function MaskedNumber(opts) {
	    _classCallCheck(this, MaskedNumber);
	    return _super.call(this, Object.assign({}, MaskedNumber.DEFAULTS, opts));
	  }
	  _createClass(MaskedNumber, [{
	    key: "_update",
	    value: function _update(opts) {
	      _get(_getPrototypeOf(MaskedNumber.prototype), "_update", this).call(this, opts);
	      this._updateRegExps();
	    }
	  }, {
	    key: "_updateRegExps",
	    value: function _updateRegExps() {
	      var start = '^' + (this.allowNegative ? '[+|\\-]?' : '');
	      var midInput = '(0|([1-9]+\\d*))?';
	      var mid = '\\d*';
	      var end = (this.scale ? '(' + escapeRegExp(this.radix) + '\\d{0,' + this.scale + '})?' : '') + '$';
	      this._numberRegExpInput = new RegExp(start + midInput + end);
	      this._numberRegExp = new RegExp(start + mid + end);
	      this._mapToRadixRegExp = new RegExp('[' + this.mapToRadix.map(escapeRegExp).join('') + ']', 'g');
	      this._thousandsSeparatorRegExp = new RegExp(escapeRegExp(this.thousandsSeparator), 'g');
	    }
	  }, {
	    key: "_removeThousandsSeparators",
	    value: function _removeThousandsSeparators(value) {
	      return value.replace(this._thousandsSeparatorRegExp, '');
	    }
	  }, {
	    key: "_insertThousandsSeparators",
	    value: function _insertThousandsSeparators(value) {
	      var parts = value.split(this.radix);
	      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, this.thousandsSeparator);
	      return parts.join(this.radix);
	    }
	  }, {
	    key: "doPrepare",
	    value: function doPrepare(ch) {
	      var _get2;
	      ch = ch.replace(this._mapToRadixRegExp, this.radix);
	      var noSepCh = this._removeThousandsSeparators(ch);
	      for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
	        args[_key - 1] = arguments[_key];
	      }
	      var _normalizePrepare = normalizePrepare((_get2 = _get(_getPrototypeOf(MaskedNumber.prototype), "doPrepare", this)).call.apply(_get2, [this, noSepCh].concat(args))),
	          _normalizePrepare2 = _slicedToArray(_normalizePrepare, 2),
	          prepCh = _normalizePrepare2[0],
	          details = _normalizePrepare2[1];
	      if (ch && !noSepCh) details.skip = true;
	      return [prepCh, details];
	    }
	  }, {
	    key: "_separatorsCount",
	    value: function _separatorsCount(to) {
	      var extendOnSeparators = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
	      var count = 0;
	      for (var pos = 0; pos < to; ++pos) {
	        if (this._value.indexOf(this.thousandsSeparator, pos) === pos) {
	          ++count;
	          if (extendOnSeparators) to += this.thousandsSeparator.length;
	        }
	      }
	      return count;
	    }
	  }, {
	    key: "_separatorsCountFromSlice",
	    value: function _separatorsCountFromSlice() {
	      var slice = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this._value;
	      return this._separatorsCount(this._removeThousandsSeparators(slice).length, true);
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var flags = arguments.length > 2 ? arguments[2] : undefined;
	      var _this$_adjustRangeWit = this._adjustRangeWithSeparators(fromPos, toPos);
	      var _this$_adjustRangeWit2 = _slicedToArray(_this$_adjustRangeWit, 2);
	      fromPos = _this$_adjustRangeWit2[0];
	      toPos = _this$_adjustRangeWit2[1];
	      return this._removeThousandsSeparators(_get(_getPrototypeOf(MaskedNumber.prototype), "extractInput", this).call(this, fromPos, toPos, flags));
	    }
	  }, {
	    key: "_appendCharRaw",
	    value: function _appendCharRaw(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      if (!this.thousandsSeparator) return _get(_getPrototypeOf(MaskedNumber.prototype), "_appendCharRaw", this).call(this, ch, flags);
	      var prevBeforeTailValue = flags.tail && flags._beforeTailState ? flags._beforeTailState._value : this._value;
	      var prevBeforeTailSeparatorsCount = this._separatorsCountFromSlice(prevBeforeTailValue);
	      this._value = this._removeThousandsSeparators(this.value);
	      var appendDetails = _get(_getPrototypeOf(MaskedNumber.prototype), "_appendCharRaw", this).call(this, ch, flags);
	      this._value = this._insertThousandsSeparators(this._value);
	      var beforeTailValue = flags.tail && flags._beforeTailState ? flags._beforeTailState._value : this._value;
	      var beforeTailSeparatorsCount = this._separatorsCountFromSlice(beforeTailValue);
	      appendDetails.tailShift += (beforeTailSeparatorsCount - prevBeforeTailSeparatorsCount) * this.thousandsSeparator.length;
	      appendDetails.skip = !appendDetails.rawInserted && ch === this.thousandsSeparator;
	      return appendDetails;
	    }
	  }, {
	    key: "_findSeparatorAround",
	    value: function _findSeparatorAround(pos) {
	      if (this.thousandsSeparator) {
	        var searchFrom = pos - this.thousandsSeparator.length + 1;
	        var separatorPos = this.value.indexOf(this.thousandsSeparator, searchFrom);
	        if (separatorPos <= pos) return separatorPos;
	      }
	      return -1;
	    }
	  }, {
	    key: "_adjustRangeWithSeparators",
	    value: function _adjustRangeWithSeparators(from, to) {
	      var separatorAroundFromPos = this._findSeparatorAround(from);
	      if (separatorAroundFromPos >= 0) from = separatorAroundFromPos;
	      var separatorAroundToPos = this._findSeparatorAround(to);
	      if (separatorAroundToPos >= 0) to = separatorAroundToPos + this.thousandsSeparator.length;
	      return [from, to];
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var fromPos = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
	      var toPos = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.value.length;
	      var _this$_adjustRangeWit3 = this._adjustRangeWithSeparators(fromPos, toPos);
	      var _this$_adjustRangeWit4 = _slicedToArray(_this$_adjustRangeWit3, 2);
	      fromPos = _this$_adjustRangeWit4[0];
	      toPos = _this$_adjustRangeWit4[1];
	      var valueBeforePos = this.value.slice(0, fromPos);
	      var valueAfterPos = this.value.slice(toPos);
	      var prevBeforeTailSeparatorsCount = this._separatorsCount(valueBeforePos.length);
	      this._value = this._insertThousandsSeparators(this._removeThousandsSeparators(valueBeforePos + valueAfterPos));
	      var beforeTailSeparatorsCount = this._separatorsCountFromSlice(valueBeforePos);
	      return new ChangeDetails({
	        tailShift: (beforeTailSeparatorsCount - prevBeforeTailSeparatorsCount) * this.thousandsSeparator.length
	      });
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos(cursorPos, direction) {
	      if (!this.thousandsSeparator) return cursorPos;
	      switch (direction) {
	        case DIRECTION.NONE:
	        case DIRECTION.LEFT:
	        case DIRECTION.FORCE_LEFT:
	          {
	            var separatorAtLeftPos = this._findSeparatorAround(cursorPos - 1);
	            if (separatorAtLeftPos >= 0) {
	              var separatorAtLeftEndPos = separatorAtLeftPos + this.thousandsSeparator.length;
	              if (cursorPos < separatorAtLeftEndPos || this.value.length <= separatorAtLeftEndPos || direction === DIRECTION.FORCE_LEFT) {
	                return separatorAtLeftPos;
	              }
	            }
	            break;
	          }
	        case DIRECTION.RIGHT:
	        case DIRECTION.FORCE_RIGHT:
	          {
	            var separatorAtRightPos = this._findSeparatorAround(cursorPos);
	            if (separatorAtRightPos >= 0) {
	              return separatorAtRightPos + this.thousandsSeparator.length;
	            }
	          }
	      }
	      return cursorPos;
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate(flags) {
	      var regexp = flags.input ? this._numberRegExpInput : this._numberRegExp;
	      var valid = regexp.test(this._removeThousandsSeparators(this.value));
	      if (valid) {
	        var number = this.number;
	        valid = valid && !isNaN(number) && (
	        this.min == null || this.min >= 0 || this.min <= this.number) && (
	        this.max == null || this.max <= 0 || this.number <= this.max);
	      }
	      return valid && _get(_getPrototypeOf(MaskedNumber.prototype), "doValidate", this).call(this, flags);
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {
	      if (this.value) {
	        var number = this.number;
	        var validnum = number;
	        if (this.min != null) validnum = Math.max(validnum, this.min);
	        if (this.max != null) validnum = Math.min(validnum, this.max);
	        if (validnum !== number) this.unmaskedValue = String(validnum);
	        var formatted = this.value;
	        if (this.normalizeZeros) formatted = this._normalizeZeros(formatted);
	        if (this.padFractionalZeros && this.scale > 0) formatted = this._padFractionalZeros(formatted);
	        this._value = formatted;
	      }
	      _get(_getPrototypeOf(MaskedNumber.prototype), "doCommit", this).call(this);
	    }
	  }, {
	    key: "_normalizeZeros",
	    value: function _normalizeZeros(value) {
	      var parts = this._removeThousandsSeparators(value).split(this.radix);
	      parts[0] = parts[0].replace(/^(\D*)(0*)(\d*)/, function (match, sign, zeros, num) {
	        return sign + num;
	      });
	      if (value.length && !/\d$/.test(parts[0])) parts[0] = parts[0] + '0';
	      if (parts.length > 1) {
	        parts[1] = parts[1].replace(/0*$/, '');
	        if (!parts[1].length) parts.length = 1;
	      }
	      return this._insertThousandsSeparators(parts.join(this.radix));
	    }
	  }, {
	    key: "_padFractionalZeros",
	    value: function _padFractionalZeros(value) {
	      if (!value) return value;
	      var parts = value.split(this.radix);
	      if (parts.length < 2) parts.push('');
	      parts[1] = parts[1].padEnd(this.scale, '0');
	      return parts.join(this.radix);
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this._removeThousandsSeparators(this._normalizeZeros(this.value)).replace(this.radix, '.');
	    },
	    set: function set(unmaskedValue) {
	      _set(_getPrototypeOf(MaskedNumber.prototype), "unmaskedValue", unmaskedValue.replace('.', this.radix), this, true);
	    }
	  }, {
	    key: "typedValue",
	    get: function get() {
	      return Number(this.unmaskedValue);
	    },
	    set: function set(n) {
	      _set(_getPrototypeOf(MaskedNumber.prototype), "unmaskedValue", String(n), this, true);
	    }
	  }, {
	    key: "number",
	    get: function get() {
	      return this.typedValue;
	    },
	    set: function set(number) {
	      this.typedValue = number;
	    }
	  }, {
	    key: "allowNegative",
	    get: function get() {
	      return this.signed || this.min != null && this.min < 0 || this.max != null && this.max < 0;
	    }
	  }, {
	    key: "typedValueEquals",
	    value: function typedValueEquals(value) {
	      return (_get(_getPrototypeOf(MaskedNumber.prototype), "typedValueEquals", this).call(this, value) || MaskedNumber.EMPTY_VALUES.includes(value) && MaskedNumber.EMPTY_VALUES.includes(this.typedValue)) && !(value === 0 && this.value === '');
	    }
	  }]);
	  return MaskedNumber;
	}(Masked);
	MaskedNumber.DEFAULTS = {
	  radix: ',',
	  thousandsSeparator: '',
	  mapToRadix: ['.'],
	  scale: 2,
	  signed: false,
	  normalizeZeros: true,
	  padFractionalZeros: false
	};
	MaskedNumber.EMPTY_VALUES = [].concat(_toConsumableArray(Masked.EMPTY_VALUES), [0]);
	IMask.MaskedNumber = MaskedNumber;

	var MaskedFunction = function (_Masked) {
	  _inherits(MaskedFunction, _Masked);
	  var _super = _createSuper(MaskedFunction);
	  function MaskedFunction() {
	    _classCallCheck(this, MaskedFunction);
	    return _super.apply(this, arguments);
	  }
	  _createClass(MaskedFunction, [{
	    key: "_update",
	    value:
	    function _update(opts) {
	      if (opts.mask) opts.validate = opts.mask;
	      _get(_getPrototypeOf(MaskedFunction.prototype), "_update", this).call(this, opts);
	    }
	  }]);
	  return MaskedFunction;
	}(Masked);
	IMask.MaskedFunction = MaskedFunction;

	var _excluded = ["compiledMasks", "currentMaskRef", "currentMask"];
	var MaskedDynamic = function (_Masked) {
	  _inherits(MaskedDynamic, _Masked);
	  var _super = _createSuper(MaskedDynamic);
	  function MaskedDynamic(opts) {
	    var _this;
	    _classCallCheck(this, MaskedDynamic);
	    _this = _super.call(this, Object.assign({}, MaskedDynamic.DEFAULTS, opts));
	    _this.currentMask = null;
	    return _this;
	  }
	  _createClass(MaskedDynamic, [{
	    key: "_update",
	    value: function _update(opts) {
	      _get(_getPrototypeOf(MaskedDynamic.prototype), "_update", this).call(this, opts);
	      if ('mask' in opts) {
	        this.compiledMasks = Array.isArray(opts.mask) ? opts.mask.map(function (m) {
	          return createMask(m);
	        }) : [];
	      }
	    }
	  }, {
	    key: "_appendCharRaw",
	    value: function _appendCharRaw(ch) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var details = this._applyDispatch(ch, flags);
	      if (this.currentMask) {
	        details.aggregate(this.currentMask._appendChar(ch, this.currentMaskFlags(flags)));
	      }
	      return details;
	    }
	  }, {
	    key: "_applyDispatch",
	    value: function _applyDispatch() {
	      var appended = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var prevValueBeforeTail = flags.tail && flags._beforeTailState != null ? flags._beforeTailState._value : this.value;
	      var inputValue = this.rawInputValue;
	      var insertValue = flags.tail && flags._beforeTailState != null ?
	      flags._beforeTailState._rawInputValue : inputValue;
	      var tailValue = inputValue.slice(insertValue.length);
	      var prevMask = this.currentMask;
	      var details = new ChangeDetails();
	      var prevMaskState = prevMask === null || prevMask === void 0 ? void 0 : prevMask.state;
	      this.currentMask = this.doDispatch(appended, Object.assign({}, flags));
	      if (this.currentMask) {
	        if (this.currentMask !== prevMask) {
	          this.currentMask.reset();
	          if (insertValue) {
	            var d = this.currentMask.append(insertValue, {
	              raw: true
	            });
	            details.tailShift = d.inserted.length - prevValueBeforeTail.length;
	          }
	          if (tailValue) {
	            details.tailShift += this.currentMask.append(tailValue, {
	              raw: true,
	              tail: true
	            }).tailShift;
	          }
	        } else {
	          this.currentMask.state = prevMaskState;
	        }
	      }
	      return details;
	    }
	  }, {
	    key: "_appendPlaceholder",
	    value: function _appendPlaceholder() {
	      var details = this._applyDispatch.apply(this, arguments);
	      if (this.currentMask) {
	        details.aggregate(this.currentMask._appendPlaceholder());
	      }
	      return details;
	    }
	  }, {
	    key: "_appendEager",
	    value: function _appendEager() {
	      var details = this._applyDispatch.apply(this, arguments);
	      if (this.currentMask) {
	        details.aggregate(this.currentMask._appendEager());
	      }
	      return details;
	    }
	  }, {
	    key: "currentMaskFlags",
	    value: function currentMaskFlags(flags) {
	      var _flags$_beforeTailSta, _flags$_beforeTailSta2;
	      return Object.assign({}, flags, {
	        _beforeTailState: ((_flags$_beforeTailSta = flags._beforeTailState) === null || _flags$_beforeTailSta === void 0 ? void 0 : _flags$_beforeTailSta.currentMaskRef) === this.currentMask && ((_flags$_beforeTailSta2 = flags._beforeTailState) === null || _flags$_beforeTailSta2 === void 0 ? void 0 : _flags$_beforeTailSta2.currentMask) || flags._beforeTailState
	      });
	    }
	  }, {
	    key: "doDispatch",
	    value: function doDispatch(appended) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      return this.dispatch(appended, this, flags);
	    }
	  }, {
	    key: "doValidate",
	    value: function doValidate(flags) {
	      return _get(_getPrototypeOf(MaskedDynamic.prototype), "doValidate", this).call(this, flags) && (!this.currentMask || this.currentMask.doValidate(this.currentMaskFlags(flags)));
	    }
	  }, {
	    key: "doPrepare",
	    value: function doPrepare(str) {
	      var flags = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	      var _normalizePrepare = normalizePrepare(_get(_getPrototypeOf(MaskedDynamic.prototype), "doPrepare", this).call(this, str, flags)),
	          _normalizePrepare2 = _slicedToArray(_normalizePrepare, 2),
	          s = _normalizePrepare2[0],
	          details = _normalizePrepare2[1];
	      if (this.currentMask) {
	        var currentDetails;
	        var _normalizePrepare3 = normalizePrepare(_get(_getPrototypeOf(MaskedDynamic.prototype), "doPrepare", this).call(this, s, this.currentMaskFlags(flags)));
	        var _normalizePrepare4 = _slicedToArray(_normalizePrepare3, 2);
	        s = _normalizePrepare4[0];
	        currentDetails = _normalizePrepare4[1];
	        details = details.aggregate(currentDetails);
	      }
	      return [s, details];
	    }
	  }, {
	    key: "reset",
	    value: function reset() {
	      var _this$currentMask;
	      (_this$currentMask = this.currentMask) === null || _this$currentMask === void 0 ? void 0 : _this$currentMask.reset();
	      this.compiledMasks.forEach(function (m) {
	        return m.reset();
	      });
	    }
	  }, {
	    key: "value",
	    get: function get() {
	      return this.currentMask ? this.currentMask.value : '';
	    },
	    set: function set(value) {
	      _set(_getPrototypeOf(MaskedDynamic.prototype), "value", value, this, true);
	    }
	  }, {
	    key: "unmaskedValue",
	    get: function get() {
	      return this.currentMask ? this.currentMask.unmaskedValue : '';
	    },
	    set: function set(unmaskedValue) {
	      _set(_getPrototypeOf(MaskedDynamic.prototype), "unmaskedValue", unmaskedValue, this, true);
	    }
	  }, {
	    key: "typedValue",
	    get: function get() {
	      return this.currentMask ? this.currentMask.typedValue : '';
	    }
	    ,
	    set: function set(value) {
	      var unmaskedValue = String(value);
	      if (this.currentMask) {
	        this.currentMask.typedValue = value;
	        unmaskedValue = this.currentMask.unmaskedValue;
	      }
	      this.unmaskedValue = unmaskedValue;
	    }
	  }, {
	    key: "isComplete",
	    get: function get() {
	      var _this$currentMask2;
	      return Boolean((_this$currentMask2 = this.currentMask) === null || _this$currentMask2 === void 0 ? void 0 : _this$currentMask2.isComplete);
	    }
	  }, {
	    key: "isFilled",
	    get: function get() {
	      var _this$currentMask3;
	      return Boolean((_this$currentMask3 = this.currentMask) === null || _this$currentMask3 === void 0 ? void 0 : _this$currentMask3.isFilled);
	    }
	  }, {
	    key: "remove",
	    value: function remove() {
	      var details = new ChangeDetails();
	      if (this.currentMask) {
	        var _this$currentMask4;
	        details.aggregate((_this$currentMask4 = this.currentMask).remove.apply(_this$currentMask4, arguments))
	        .aggregate(this._applyDispatch());
	      }
	      return details;
	    }
	  }, {
	    key: "state",
	    get: function get() {
	      var _this$currentMask5;
	      return Object.assign({}, _get(_getPrototypeOf(MaskedDynamic.prototype), "state", this), {
	        _rawInputValue: this.rawInputValue,
	        compiledMasks: this.compiledMasks.map(function (m) {
	          return m.state;
	        }),
	        currentMaskRef: this.currentMask,
	        currentMask: (_this$currentMask5 = this.currentMask) === null || _this$currentMask5 === void 0 ? void 0 : _this$currentMask5.state
	      });
	    },
	    set: function set(state) {
	      var compiledMasks = state.compiledMasks,
	          currentMaskRef = state.currentMaskRef,
	          currentMask = state.currentMask,
	          maskedState = _objectWithoutProperties(state, _excluded);
	      this.compiledMasks.forEach(function (m, mi) {
	        return m.state = compiledMasks[mi];
	      });
	      if (currentMaskRef != null) {
	        this.currentMask = currentMaskRef;
	        this.currentMask.state = currentMask;
	      }
	      _set(_getPrototypeOf(MaskedDynamic.prototype), "state", maskedState, this, true);
	    }
	  }, {
	    key: "extractInput",
	    value: function extractInput() {
	      var _this$currentMask6;
	      return this.currentMask ? (_this$currentMask6 = this.currentMask).extractInput.apply(_this$currentMask6, arguments) : '';
	    }
	  }, {
	    key: "extractTail",
	    value: function extractTail() {
	      var _this$currentMask7, _get2;
	      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
	        args[_key] = arguments[_key];
	      }
	      return this.currentMask ? (_this$currentMask7 = this.currentMask).extractTail.apply(_this$currentMask7, args) : (_get2 = _get(_getPrototypeOf(MaskedDynamic.prototype), "extractTail", this)).call.apply(_get2, [this].concat(args));
	    }
	  }, {
	    key: "doCommit",
	    value: function doCommit() {
	      if (this.currentMask) this.currentMask.doCommit();
	      _get(_getPrototypeOf(MaskedDynamic.prototype), "doCommit", this).call(this);
	    }
	  }, {
	    key: "nearestInputPos",
	    value: function nearestInputPos() {
	      var _this$currentMask8, _get3;
	      for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
	        args[_key2] = arguments[_key2];
	      }
	      return this.currentMask ? (_this$currentMask8 = this.currentMask).nearestInputPos.apply(_this$currentMask8, args) : (_get3 = _get(_getPrototypeOf(MaskedDynamic.prototype), "nearestInputPos", this)).call.apply(_get3, [this].concat(args));
	    }
	  }, {
	    key: "overwrite",
	    get: function get() {
	      return this.currentMask ? this.currentMask.overwrite : _get(_getPrototypeOf(MaskedDynamic.prototype), "overwrite", this);
	    },
	    set: function set(overwrite) {
	      console.warn('"overwrite" option is not available in dynamic mask, use this option in siblings');
	    }
	  }, {
	    key: "eager",
	    get: function get() {
	      return this.currentMask ? this.currentMask.eager : _get(_getPrototypeOf(MaskedDynamic.prototype), "eager", this);
	    },
	    set: function set(eager) {
	      console.warn('"eager" option is not available in dynamic mask, use this option in siblings');
	    }
	  }, {
	    key: "maskEquals",
	    value: function maskEquals(mask) {
	      return Array.isArray(mask) && this.compiledMasks.every(function (m, mi) {
	        var _mask$mi;
	        return m.maskEquals((_mask$mi = mask[mi]) === null || _mask$mi === void 0 ? void 0 : _mask$mi.mask);
	      });
	    }
	  }, {
	    key: "typedValueEquals",
	    value: function typedValueEquals(value) {
	      var _this$currentMask9;
	      return Boolean((_this$currentMask9 = this.currentMask) === null || _this$currentMask9 === void 0 ? void 0 : _this$currentMask9.typedValueEquals(value));
	    }
	  }]);
	  return MaskedDynamic;
	}(Masked);
	MaskedDynamic.DEFAULTS = {
	  dispatch: function dispatch(appended, masked, flags) {
	    if (!masked.compiledMasks.length) return;
	    var inputValue = masked.rawInputValue;
	    var inputs = masked.compiledMasks.map(function (m, index) {
	      m.reset();
	      m.append(inputValue, {
	        raw: true
	      });
	      m.append(appended, masked.currentMaskFlags(flags));
	      var weight = m.rawInputValue.length;
	      return {
	        weight: weight,
	        index: index
	      };
	    });
	    inputs.sort(function (i1, i2) {
	      return i2.weight - i1.weight;
	    });
	    return masked.compiledMasks[inputs[0].index];
	  }
	};
	IMask.MaskedDynamic = MaskedDynamic;

	var PIPE_TYPE = {
	  MASKED: 'value',
	  UNMASKED: 'unmaskedValue',
	  TYPED: 'typedValue'
	};
	function createPipe(mask) {
	  var from = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : PIPE_TYPE.MASKED;
	  var to = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : PIPE_TYPE.MASKED;
	  var masked = createMask(mask);
	  return function (value) {
	    return masked.runIsolated(function (m) {
	      m[from] = value;
	      return m[to];
	    });
	  };
	}
	function pipe(value) {
	  for (var _len = arguments.length, pipeArgs = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
	    pipeArgs[_key - 1] = arguments[_key];
	  }
	  return createPipe.apply(void 0, pipeArgs)(value);
	}
	IMask.PIPE_TYPE = PIPE_TYPE;
	IMask.createPipe = createPipe;
	IMask.pipe = pipe;

	try {
	  globalThis.IMask = IMask;
	} catch (e) {}

	var maskElementList = [].slice.call(document.querySelectorAll('[data-mask]'));
	maskElementList.map(function (maskEl) {
	  return new IMask(maskEl, {
	    mask: maskEl.dataset.mask,
	    lazy: maskEl.dataset['mask-visible'] === 'true'
	  });
	});

	var top = 'top';
	var bottom = 'bottom';
	var right = 'right';
	var left = 'left';
	var auto = 'auto';
	var basePlacements = [top, bottom, right, left];
	var start = 'start';
	var end = 'end';
	var clippingParents = 'clippingParents';
	var viewport = 'viewport';
	var popper = 'popper';
	var reference = 'reference';
	var variationPlacements = basePlacements.reduce(function (acc, placement) {
	  return acc.concat([placement + "-" + start, placement + "-" + end]);
	}, []);
	var placements = [].concat(basePlacements, [auto]).reduce(function (acc, placement) {
	  return acc.concat([placement, placement + "-" + start, placement + "-" + end]);
	}, []);
	var beforeRead = 'beforeRead';
	var read = 'read';
	var afterRead = 'afterRead';
	var beforeMain = 'beforeMain';
	var main = 'main';
	var afterMain = 'afterMain';
	var beforeWrite = 'beforeWrite';
	var write = 'write';
	var afterWrite = 'afterWrite';
	var modifierPhases = [beforeRead, read, afterRead, beforeMain, main, afterMain, beforeWrite, write, afterWrite];

	function getNodeName(element) {
	  return element ? (element.nodeName || '').toLowerCase() : null;
	}

	function getWindow(node) {
	  if (node == null) {
	    return window;
	  }
	  if (node.toString() !== '[object Window]') {
	    var ownerDocument = node.ownerDocument;
	    return ownerDocument ? ownerDocument.defaultView || window : window;
	  }
	  return node;
	}

	function isElement$1(node) {
	  var OwnElement = getWindow(node).Element;
	  return node instanceof OwnElement || node instanceof Element;
	}
	function isHTMLElement(node) {
	  var OwnElement = getWindow(node).HTMLElement;
	  return node instanceof OwnElement || node instanceof HTMLElement;
	}
	function isShadowRoot(node) {
	  if (typeof ShadowRoot === 'undefined') {
	    return false;
	  }
	  var OwnElement = getWindow(node).ShadowRoot;
	  return node instanceof OwnElement || node instanceof ShadowRoot;
	}

	function applyStyles(_ref) {
	  var state = _ref.state;
	  Object.keys(state.elements).forEach(function (name) {
	    var style = state.styles[name] || {};
	    var attributes = state.attributes[name] || {};
	    var element = state.elements[name];
	    if (!isHTMLElement(element) || !getNodeName(element)) {
	      return;
	    }
	    Object.assign(element.style, style);
	    Object.keys(attributes).forEach(function (name) {
	      var value = attributes[name];
	      if (value === false) {
	        element.removeAttribute(name);
	      } else {
	        element.setAttribute(name, value === true ? '' : value);
	      }
	    });
	  });
	}
	function effect$2(_ref2) {
	  var state = _ref2.state;
	  var initialStyles = {
	    popper: {
	      position: state.options.strategy,
	      left: '0',
	      top: '0',
	      margin: '0'
	    },
	    arrow: {
	      position: 'absolute'
	    },
	    reference: {}
	  };
	  Object.assign(state.elements.popper.style, initialStyles.popper);
	  state.styles = initialStyles;
	  if (state.elements.arrow) {
	    Object.assign(state.elements.arrow.style, initialStyles.arrow);
	  }
	  return function () {
	    Object.keys(state.elements).forEach(function (name) {
	      var element = state.elements[name];
	      var attributes = state.attributes[name] || {};
	      var styleProperties = Object.keys(state.styles.hasOwnProperty(name) ? state.styles[name] : initialStyles[name]);
	      var style = styleProperties.reduce(function (style, property) {
	        style[property] = '';
	        return style;
	      }, {});
	      if (!isHTMLElement(element) || !getNodeName(element)) {
	        return;
	      }
	      Object.assign(element.style, style);
	      Object.keys(attributes).forEach(function (attribute) {
	        element.removeAttribute(attribute);
	      });
	    });
	  };
	}
	var applyStyles$1 = {
	  name: 'applyStyles',
	  enabled: true,
	  phase: 'write',
	  fn: applyStyles,
	  effect: effect$2,
	  requires: ['computeStyles']
	};

	function getBasePlacement(placement) {
	  return placement.split('-')[0];
	}

	var max = Math.max;
	var min = Math.min;
	var round = Math.round;

	function getUAString() {
	  var uaData = navigator.userAgentData;
	  if (uaData != null && uaData.brands) {
	    return uaData.brands.map(function (item) {
	      return item.brand + "/" + item.version;
	    }).join(' ');
	  }
	  return navigator.userAgent;
	}

	function isLayoutViewport() {
	  return !/^((?!chrome|android).)*safari/i.test(getUAString());
	}

	function getBoundingClientRect(element, includeScale, isFixedStrategy) {
	  if (includeScale === void 0) {
	    includeScale = false;
	  }
	  if (isFixedStrategy === void 0) {
	    isFixedStrategy = false;
	  }
	  var clientRect = element.getBoundingClientRect();
	  var scaleX = 1;
	  var scaleY = 1;
	  if (includeScale && isHTMLElement(element)) {
	    scaleX = element.offsetWidth > 0 ? round(clientRect.width) / element.offsetWidth || 1 : 1;
	    scaleY = element.offsetHeight > 0 ? round(clientRect.height) / element.offsetHeight || 1 : 1;
	  }
	  var _ref = isElement$1(element) ? getWindow(element) : window,
	      visualViewport = _ref.visualViewport;
	  var addVisualOffsets = !isLayoutViewport() && isFixedStrategy;
	  var x = (clientRect.left + (addVisualOffsets && visualViewport ? visualViewport.offsetLeft : 0)) / scaleX;
	  var y = (clientRect.top + (addVisualOffsets && visualViewport ? visualViewport.offsetTop : 0)) / scaleY;
	  var width = clientRect.width / scaleX;
	  var height = clientRect.height / scaleY;
	  return {
	    width: width,
	    height: height,
	    top: y,
	    right: x + width,
	    bottom: y + height,
	    left: x,
	    x: x,
	    y: y
	  };
	}

	function getLayoutRect(element) {
	  var clientRect = getBoundingClientRect(element);
	  var width = element.offsetWidth;
	  var height = element.offsetHeight;
	  if (Math.abs(clientRect.width - width) <= 1) {
	    width = clientRect.width;
	  }
	  if (Math.abs(clientRect.height - height) <= 1) {
	    height = clientRect.height;
	  }
	  return {
	    x: element.offsetLeft,
	    y: element.offsetTop,
	    width: width,
	    height: height
	  };
	}

	function contains(parent, child) {
	  var rootNode = child.getRootNode && child.getRootNode();
	  if (parent.contains(child)) {
	    return true;
	  }
	  else if (rootNode && isShadowRoot(rootNode)) {
	      var next = child;
	      do {
	        if (next && parent.isSameNode(next)) {
	          return true;
	        }
	        next = next.parentNode || next.host;
	      } while (next);
	    }
	  return false;
	}

	function getComputedStyle$1(element) {
	  return getWindow(element).getComputedStyle(element);
	}

	function isTableElement(element) {
	  return ['table', 'td', 'th'].indexOf(getNodeName(element)) >= 0;
	}

	function getDocumentElement(element) {
	  return ((isElement$1(element) ? element.ownerDocument :
	  element.document) || window.document).documentElement;
	}

	function getParentNode(element) {
	  if (getNodeName(element) === 'html') {
	    return element;
	  }
	  return (
	    element.assignedSlot ||
	    element.parentNode || (
	    isShadowRoot(element) ? element.host : null) ||
	    getDocumentElement(element)
	  );
	}

	function getTrueOffsetParent(element) {
	  if (!isHTMLElement(element) ||
	  getComputedStyle$1(element).position === 'fixed') {
	    return null;
	  }
	  return element.offsetParent;
	}
	function getContainingBlock(element) {
	  var isFirefox = /firefox/i.test(getUAString());
	  var isIE = /Trident/i.test(getUAString());
	  if (isIE && isHTMLElement(element)) {
	    var elementCss = getComputedStyle$1(element);
	    if (elementCss.position === 'fixed') {
	      return null;
	    }
	  }
	  var currentNode = getParentNode(element);
	  if (isShadowRoot(currentNode)) {
	    currentNode = currentNode.host;
	  }
	  while (isHTMLElement(currentNode) && ['html', 'body'].indexOf(getNodeName(currentNode)) < 0) {
	    var css = getComputedStyle$1(currentNode);
	    if (css.transform !== 'none' || css.perspective !== 'none' || css.contain === 'paint' || ['transform', 'perspective'].indexOf(css.willChange) !== -1 || isFirefox && css.willChange === 'filter' || isFirefox && css.filter && css.filter !== 'none') {
	      return currentNode;
	    } else {
	      currentNode = currentNode.parentNode;
	    }
	  }
	  return null;
	}
	function getOffsetParent(element) {
	  var window = getWindow(element);
	  var offsetParent = getTrueOffsetParent(element);
	  while (offsetParent && isTableElement(offsetParent) && getComputedStyle$1(offsetParent).position === 'static') {
	    offsetParent = getTrueOffsetParent(offsetParent);
	  }
	  if (offsetParent && (getNodeName(offsetParent) === 'html' || getNodeName(offsetParent) === 'body' && getComputedStyle$1(offsetParent).position === 'static')) {
	    return window;
	  }
	  return offsetParent || getContainingBlock(element) || window;
	}

	function getMainAxisFromPlacement(placement) {
	  return ['top', 'bottom'].indexOf(placement) >= 0 ? 'x' : 'y';
	}

	function within(min$1, value, max$1) {
	  return max(min$1, min(value, max$1));
	}
	function withinMaxClamp(min, value, max) {
	  var v = within(min, value, max);
	  return v > max ? max : v;
	}

	function getFreshSideObject() {
	  return {
	    top: 0,
	    right: 0,
	    bottom: 0,
	    left: 0
	  };
	}

	function mergePaddingObject(paddingObject) {
	  return Object.assign({}, getFreshSideObject(), paddingObject);
	}

	function expandToHashMap(value, keys) {
	  return keys.reduce(function (hashMap, key) {
	    hashMap[key] = value;
	    return hashMap;
	  }, {});
	}

	var toPaddingObject = function toPaddingObject(padding, state) {
	  padding = typeof padding === 'function' ? padding(Object.assign({}, state.rects, {
	    placement: state.placement
	  })) : padding;
	  return mergePaddingObject(typeof padding !== 'number' ? padding : expandToHashMap(padding, basePlacements));
	};
	function arrow(_ref) {
	  var _state$modifiersData$;
	  var state = _ref.state,
	      name = _ref.name,
	      options = _ref.options;
	  var arrowElement = state.elements.arrow;
	  var popperOffsets = state.modifiersData.popperOffsets;
	  var basePlacement = getBasePlacement(state.placement);
	  var axis = getMainAxisFromPlacement(basePlacement);
	  var isVertical = [left, right].indexOf(basePlacement) >= 0;
	  var len = isVertical ? 'height' : 'width';
	  if (!arrowElement || !popperOffsets) {
	    return;
	  }
	  var paddingObject = toPaddingObject(options.padding, state);
	  var arrowRect = getLayoutRect(arrowElement);
	  var minProp = axis === 'y' ? top : left;
	  var maxProp = axis === 'y' ? bottom : right;
	  var endDiff = state.rects.reference[len] + state.rects.reference[axis] - popperOffsets[axis] - state.rects.popper[len];
	  var startDiff = popperOffsets[axis] - state.rects.reference[axis];
	  var arrowOffsetParent = getOffsetParent(arrowElement);
	  var clientSize = arrowOffsetParent ? axis === 'y' ? arrowOffsetParent.clientHeight || 0 : arrowOffsetParent.clientWidth || 0 : 0;
	  var centerToReference = endDiff / 2 - startDiff / 2;
	  var min = paddingObject[minProp];
	  var max = clientSize - arrowRect[len] - paddingObject[maxProp];
	  var center = clientSize / 2 - arrowRect[len] / 2 + centerToReference;
	  var offset = within(min, center, max);
	  var axisProp = axis;
	  state.modifiersData[name] = (_state$modifiersData$ = {}, _state$modifiersData$[axisProp] = offset, _state$modifiersData$.centerOffset = offset - center, _state$modifiersData$);
	}
	function effect$1(_ref2) {
	  var state = _ref2.state,
	      options = _ref2.options;
	  var _options$element = options.element,
	      arrowElement = _options$element === void 0 ? '[data-popper-arrow]' : _options$element;
	  if (arrowElement == null) {
	    return;
	  }
	  if (typeof arrowElement === 'string') {
	    arrowElement = state.elements.popper.querySelector(arrowElement);
	    if (!arrowElement) {
	      return;
	    }
	  }
	  if (!contains(state.elements.popper, arrowElement)) {
	    return;
	  }
	  state.elements.arrow = arrowElement;
	}
	var arrow$1 = {
	  name: 'arrow',
	  enabled: true,
	  phase: 'main',
	  fn: arrow,
	  effect: effect$1,
	  requires: ['popperOffsets'],
	  requiresIfExists: ['preventOverflow']
	};

	function getVariation(placement) {
	  return placement.split('-')[1];
	}

	var unsetSides = {
	  top: 'auto',
	  right: 'auto',
	  bottom: 'auto',
	  left: 'auto'
	};
	function roundOffsetsByDPR(_ref) {
	  var x = _ref.x,
	      y = _ref.y;
	  var win = window;
	  var dpr = win.devicePixelRatio || 1;
	  return {
	    x: round(x * dpr) / dpr || 0,
	    y: round(y * dpr) / dpr || 0
	  };
	}
	function mapToStyles(_ref2) {
	  var _Object$assign2;
	  var popper = _ref2.popper,
	      popperRect = _ref2.popperRect,
	      placement = _ref2.placement,
	      variation = _ref2.variation,
	      offsets = _ref2.offsets,
	      position = _ref2.position,
	      gpuAcceleration = _ref2.gpuAcceleration,
	      adaptive = _ref2.adaptive,
	      roundOffsets = _ref2.roundOffsets,
	      isFixed = _ref2.isFixed;
	  var _offsets$x = offsets.x,
	      x = _offsets$x === void 0 ? 0 : _offsets$x,
	      _offsets$y = offsets.y,
	      y = _offsets$y === void 0 ? 0 : _offsets$y;
	  var _ref3 = typeof roundOffsets === 'function' ? roundOffsets({
	    x: x,
	    y: y
	  }) : {
	    x: x,
	    y: y
	  };
	  x = _ref3.x;
	  y = _ref3.y;
	  var hasX = offsets.hasOwnProperty('x');
	  var hasY = offsets.hasOwnProperty('y');
	  var sideX = left;
	  var sideY = top;
	  var win = window;
	  if (adaptive) {
	    var offsetParent = getOffsetParent(popper);
	    var heightProp = 'clientHeight';
	    var widthProp = 'clientWidth';
	    if (offsetParent === getWindow(popper)) {
	      offsetParent = getDocumentElement(popper);
	      if (getComputedStyle$1(offsetParent).position !== 'static' && position === 'absolute') {
	        heightProp = 'scrollHeight';
	        widthProp = 'scrollWidth';
	      }
	    }
	    offsetParent = offsetParent;
	    if (placement === top || (placement === left || placement === right) && variation === end) {
	      sideY = bottom;
	      var offsetY = isFixed && offsetParent === win && win.visualViewport ? win.visualViewport.height :
	      offsetParent[heightProp];
	      y -= offsetY - popperRect.height;
	      y *= gpuAcceleration ? 1 : -1;
	    }
	    if (placement === left || (placement === top || placement === bottom) && variation === end) {
	      sideX = right;
	      var offsetX = isFixed && offsetParent === win && win.visualViewport ? win.visualViewport.width :
	      offsetParent[widthProp];
	      x -= offsetX - popperRect.width;
	      x *= gpuAcceleration ? 1 : -1;
	    }
	  }
	  var commonStyles = Object.assign({
	    position: position
	  }, adaptive && unsetSides);
	  var _ref4 = roundOffsets === true ? roundOffsetsByDPR({
	    x: x,
	    y: y
	  }) : {
	    x: x,
	    y: y
	  };
	  x = _ref4.x;
	  y = _ref4.y;
	  if (gpuAcceleration) {
	    var _Object$assign;
	    return Object.assign({}, commonStyles, (_Object$assign = {}, _Object$assign[sideY] = hasY ? '0' : '', _Object$assign[sideX] = hasX ? '0' : '', _Object$assign.transform = (win.devicePixelRatio || 1) <= 1 ? "translate(" + x + "px, " + y + "px)" : "translate3d(" + x + "px, " + y + "px, 0)", _Object$assign));
	  }
	  return Object.assign({}, commonStyles, (_Object$assign2 = {}, _Object$assign2[sideY] = hasY ? y + "px" : '', _Object$assign2[sideX] = hasX ? x + "px" : '', _Object$assign2.transform = '', _Object$assign2));
	}
	function computeStyles(_ref5) {
	  var state = _ref5.state,
	      options = _ref5.options;
	  var _options$gpuAccelerat = options.gpuAcceleration,
	      gpuAcceleration = _options$gpuAccelerat === void 0 ? true : _options$gpuAccelerat,
	      _options$adaptive = options.adaptive,
	      adaptive = _options$adaptive === void 0 ? true : _options$adaptive,
	      _options$roundOffsets = options.roundOffsets,
	      roundOffsets = _options$roundOffsets === void 0 ? true : _options$roundOffsets;
	  var commonStyles = {
	    placement: getBasePlacement(state.placement),
	    variation: getVariation(state.placement),
	    popper: state.elements.popper,
	    popperRect: state.rects.popper,
	    gpuAcceleration: gpuAcceleration,
	    isFixed: state.options.strategy === 'fixed'
	  };
	  if (state.modifiersData.popperOffsets != null) {
	    state.styles.popper = Object.assign({}, state.styles.popper, mapToStyles(Object.assign({}, commonStyles, {
	      offsets: state.modifiersData.popperOffsets,
	      position: state.options.strategy,
	      adaptive: adaptive,
	      roundOffsets: roundOffsets
	    })));
	  }
	  if (state.modifiersData.arrow != null) {
	    state.styles.arrow = Object.assign({}, state.styles.arrow, mapToStyles(Object.assign({}, commonStyles, {
	      offsets: state.modifiersData.arrow,
	      position: 'absolute',
	      adaptive: false,
	      roundOffsets: roundOffsets
	    })));
	  }
	  state.attributes.popper = Object.assign({}, state.attributes.popper, {
	    'data-popper-placement': state.placement
	  });
	}
	var computeStyles$1 = {
	  name: 'computeStyles',
	  enabled: true,
	  phase: 'beforeWrite',
	  fn: computeStyles,
	  data: {}
	};

	var passive = {
	  passive: true
	};
	function effect(_ref) {
	  var state = _ref.state,
	      instance = _ref.instance,
	      options = _ref.options;
	  var _options$scroll = options.scroll,
	      scroll = _options$scroll === void 0 ? true : _options$scroll,
	      _options$resize = options.resize,
	      resize = _options$resize === void 0 ? true : _options$resize;
	  var window = getWindow(state.elements.popper);
	  var scrollParents = [].concat(state.scrollParents.reference, state.scrollParents.popper);
	  if (scroll) {
	    scrollParents.forEach(function (scrollParent) {
	      scrollParent.addEventListener('scroll', instance.update, passive);
	    });
	  }
	  if (resize) {
	    window.addEventListener('resize', instance.update, passive);
	  }
	  return function () {
	    if (scroll) {
	      scrollParents.forEach(function (scrollParent) {
	        scrollParent.removeEventListener('scroll', instance.update, passive);
	      });
	    }
	    if (resize) {
	      window.removeEventListener('resize', instance.update, passive);
	    }
	  };
	}
	var eventListeners = {
	  name: 'eventListeners',
	  enabled: true,
	  phase: 'write',
	  fn: function fn() {},
	  effect: effect,
	  data: {}
	};

	var hash$1 = {
	  left: 'right',
	  right: 'left',
	  bottom: 'top',
	  top: 'bottom'
	};
	function getOppositePlacement(placement) {
	  return placement.replace(/left|right|bottom|top/g, function (matched) {
	    return hash$1[matched];
	  });
	}

	var hash = {
	  start: 'end',
	  end: 'start'
	};
	function getOppositeVariationPlacement(placement) {
	  return placement.replace(/start|end/g, function (matched) {
	    return hash[matched];
	  });
	}

	function getWindowScroll(node) {
	  var win = getWindow(node);
	  var scrollLeft = win.pageXOffset;
	  var scrollTop = win.pageYOffset;
	  return {
	    scrollLeft: scrollLeft,
	    scrollTop: scrollTop
	  };
	}

	function getWindowScrollBarX(element) {
	  return getBoundingClientRect(getDocumentElement(element)).left + getWindowScroll(element).scrollLeft;
	}

	function getViewportRect(element, strategy) {
	  var win = getWindow(element);
	  var html = getDocumentElement(element);
	  var visualViewport = win.visualViewport;
	  var width = html.clientWidth;
	  var height = html.clientHeight;
	  var x = 0;
	  var y = 0;
	  if (visualViewport) {
	    width = visualViewport.width;
	    height = visualViewport.height;
	    var layoutViewport = isLayoutViewport();
	    if (layoutViewport || !layoutViewport && strategy === 'fixed') {
	      x = visualViewport.offsetLeft;
	      y = visualViewport.offsetTop;
	    }
	  }
	  return {
	    width: width,
	    height: height,
	    x: x + getWindowScrollBarX(element),
	    y: y
	  };
	}

	function getDocumentRect(element) {
	  var _element$ownerDocumen;
	  var html = getDocumentElement(element);
	  var winScroll = getWindowScroll(element);
	  var body = (_element$ownerDocumen = element.ownerDocument) == null ? void 0 : _element$ownerDocumen.body;
	  var width = max(html.scrollWidth, html.clientWidth, body ? body.scrollWidth : 0, body ? body.clientWidth : 0);
	  var height = max(html.scrollHeight, html.clientHeight, body ? body.scrollHeight : 0, body ? body.clientHeight : 0);
	  var x = -winScroll.scrollLeft + getWindowScrollBarX(element);
	  var y = -winScroll.scrollTop;
	  if (getComputedStyle$1(body || html).direction === 'rtl') {
	    x += max(html.clientWidth, body ? body.clientWidth : 0) - width;
	  }
	  return {
	    width: width,
	    height: height,
	    x: x,
	    y: y
	  };
	}

	function isScrollParent(element) {
	  var _getComputedStyle = getComputedStyle$1(element),
	      overflow = _getComputedStyle.overflow,
	      overflowX = _getComputedStyle.overflowX,
	      overflowY = _getComputedStyle.overflowY;
	  return /auto|scroll|overlay|hidden/.test(overflow + overflowY + overflowX);
	}

	function getScrollParent(node) {
	  if (['html', 'body', '#document'].indexOf(getNodeName(node)) >= 0) {
	    return node.ownerDocument.body;
	  }
	  if (isHTMLElement(node) && isScrollParent(node)) {
	    return node;
	  }
	  return getScrollParent(getParentNode(node));
	}

	function listScrollParents(element, list) {
	  var _element$ownerDocumen;
	  if (list === void 0) {
	    list = [];
	  }
	  var scrollParent = getScrollParent(element);
	  var isBody = scrollParent === ((_element$ownerDocumen = element.ownerDocument) == null ? void 0 : _element$ownerDocumen.body);
	  var win = getWindow(scrollParent);
	  var target = isBody ? [win].concat(win.visualViewport || [], isScrollParent(scrollParent) ? scrollParent : []) : scrollParent;
	  var updatedList = list.concat(target);
	  return isBody ? updatedList :
	  updatedList.concat(listScrollParents(getParentNode(target)));
	}

	function rectToClientRect(rect) {
	  return Object.assign({}, rect, {
	    left: rect.x,
	    top: rect.y,
	    right: rect.x + rect.width,
	    bottom: rect.y + rect.height
	  });
	}

	function getInnerBoundingClientRect(element, strategy) {
	  var rect = getBoundingClientRect(element, false, strategy === 'fixed');
	  rect.top = rect.top + element.clientTop;
	  rect.left = rect.left + element.clientLeft;
	  rect.bottom = rect.top + element.clientHeight;
	  rect.right = rect.left + element.clientWidth;
	  rect.width = element.clientWidth;
	  rect.height = element.clientHeight;
	  rect.x = rect.left;
	  rect.y = rect.top;
	  return rect;
	}
	function getClientRectFromMixedType(element, clippingParent, strategy) {
	  return clippingParent === viewport ? rectToClientRect(getViewportRect(element, strategy)) : isElement$1(clippingParent) ? getInnerBoundingClientRect(clippingParent, strategy) : rectToClientRect(getDocumentRect(getDocumentElement(element)));
	}
	function getClippingParents(element) {
	  var clippingParents = listScrollParents(getParentNode(element));
	  var canEscapeClipping = ['absolute', 'fixed'].indexOf(getComputedStyle$1(element).position) >= 0;
	  var clipperElement = canEscapeClipping && isHTMLElement(element) ? getOffsetParent(element) : element;
	  if (!isElement$1(clipperElement)) {
	    return [];
	  }
	  return clippingParents.filter(function (clippingParent) {
	    return isElement$1(clippingParent) && contains(clippingParent, clipperElement) && getNodeName(clippingParent) !== 'body';
	  });
	}
	function getClippingRect(element, boundary, rootBoundary, strategy) {
	  var mainClippingParents = boundary === 'clippingParents' ? getClippingParents(element) : [].concat(boundary);
	  var clippingParents = [].concat(mainClippingParents, [rootBoundary]);
	  var firstClippingParent = clippingParents[0];
	  var clippingRect = clippingParents.reduce(function (accRect, clippingParent) {
	    var rect = getClientRectFromMixedType(element, clippingParent, strategy);
	    accRect.top = max(rect.top, accRect.top);
	    accRect.right = min(rect.right, accRect.right);
	    accRect.bottom = min(rect.bottom, accRect.bottom);
	    accRect.left = max(rect.left, accRect.left);
	    return accRect;
	  }, getClientRectFromMixedType(element, firstClippingParent, strategy));
	  clippingRect.width = clippingRect.right - clippingRect.left;
	  clippingRect.height = clippingRect.bottom - clippingRect.top;
	  clippingRect.x = clippingRect.left;
	  clippingRect.y = clippingRect.top;
	  return clippingRect;
	}

	function computeOffsets(_ref) {
	  var reference = _ref.reference,
	      element = _ref.element,
	      placement = _ref.placement;
	  var basePlacement = placement ? getBasePlacement(placement) : null;
	  var variation = placement ? getVariation(placement) : null;
	  var commonX = reference.x + reference.width / 2 - element.width / 2;
	  var commonY = reference.y + reference.height / 2 - element.height / 2;
	  var offsets;
	  switch (basePlacement) {
	    case top:
	      offsets = {
	        x: commonX,
	        y: reference.y - element.height
	      };
	      break;
	    case bottom:
	      offsets = {
	        x: commonX,
	        y: reference.y + reference.height
	      };
	      break;
	    case right:
	      offsets = {
	        x: reference.x + reference.width,
	        y: commonY
	      };
	      break;
	    case left:
	      offsets = {
	        x: reference.x - element.width,
	        y: commonY
	      };
	      break;
	    default:
	      offsets = {
	        x: reference.x,
	        y: reference.y
	      };
	  }
	  var mainAxis = basePlacement ? getMainAxisFromPlacement(basePlacement) : null;
	  if (mainAxis != null) {
	    var len = mainAxis === 'y' ? 'height' : 'width';
	    switch (variation) {
	      case start:
	        offsets[mainAxis] = offsets[mainAxis] - (reference[len] / 2 - element[len] / 2);
	        break;
	      case end:
	        offsets[mainAxis] = offsets[mainAxis] + (reference[len] / 2 - element[len] / 2);
	        break;
	    }
	  }
	  return offsets;
	}

	function detectOverflow(state, options) {
	  if (options === void 0) {
	    options = {};
	  }
	  var _options = options,
	      _options$placement = _options.placement,
	      placement = _options$placement === void 0 ? state.placement : _options$placement,
	      _options$strategy = _options.strategy,
	      strategy = _options$strategy === void 0 ? state.strategy : _options$strategy,
	      _options$boundary = _options.boundary,
	      boundary = _options$boundary === void 0 ? clippingParents : _options$boundary,
	      _options$rootBoundary = _options.rootBoundary,
	      rootBoundary = _options$rootBoundary === void 0 ? viewport : _options$rootBoundary,
	      _options$elementConte = _options.elementContext,
	      elementContext = _options$elementConte === void 0 ? popper : _options$elementConte,
	      _options$altBoundary = _options.altBoundary,
	      altBoundary = _options$altBoundary === void 0 ? false : _options$altBoundary,
	      _options$padding = _options.padding,
	      padding = _options$padding === void 0 ? 0 : _options$padding;
	  var paddingObject = mergePaddingObject(typeof padding !== 'number' ? padding : expandToHashMap(padding, basePlacements));
	  var altContext = elementContext === popper ? reference : popper;
	  var popperRect = state.rects.popper;
	  var element = state.elements[altBoundary ? altContext : elementContext];
	  var clippingClientRect = getClippingRect(isElement$1(element) ? element : element.contextElement || getDocumentElement(state.elements.popper), boundary, rootBoundary, strategy);
	  var referenceClientRect = getBoundingClientRect(state.elements.reference);
	  var popperOffsets = computeOffsets({
	    reference: referenceClientRect,
	    element: popperRect,
	    strategy: 'absolute',
	    placement: placement
	  });
	  var popperClientRect = rectToClientRect(Object.assign({}, popperRect, popperOffsets));
	  var elementClientRect = elementContext === popper ? popperClientRect : referenceClientRect;
	  var overflowOffsets = {
	    top: clippingClientRect.top - elementClientRect.top + paddingObject.top,
	    bottom: elementClientRect.bottom - clippingClientRect.bottom + paddingObject.bottom,
	    left: clippingClientRect.left - elementClientRect.left + paddingObject.left,
	    right: elementClientRect.right - clippingClientRect.right + paddingObject.right
	  };
	  var offsetData = state.modifiersData.offset;
	  if (elementContext === popper && offsetData) {
	    var offset = offsetData[placement];
	    Object.keys(overflowOffsets).forEach(function (key) {
	      var multiply = [right, bottom].indexOf(key) >= 0 ? 1 : -1;
	      var axis = [top, bottom].indexOf(key) >= 0 ? 'y' : 'x';
	      overflowOffsets[key] += offset[axis] * multiply;
	    });
	  }
	  return overflowOffsets;
	}

	function computeAutoPlacement(state, options) {
	  if (options === void 0) {
	    options = {};
	  }
	  var _options = options,
	      placement = _options.placement,
	      boundary = _options.boundary,
	      rootBoundary = _options.rootBoundary,
	      padding = _options.padding,
	      flipVariations = _options.flipVariations,
	      _options$allowedAutoP = _options.allowedAutoPlacements,
	      allowedAutoPlacements = _options$allowedAutoP === void 0 ? placements : _options$allowedAutoP;
	  var variation = getVariation(placement);
	  var placements$1 = variation ? flipVariations ? variationPlacements : variationPlacements.filter(function (placement) {
	    return getVariation(placement) === variation;
	  }) : basePlacements;
	  var allowedPlacements = placements$1.filter(function (placement) {
	    return allowedAutoPlacements.indexOf(placement) >= 0;
	  });
	  if (allowedPlacements.length === 0) {
	    allowedPlacements = placements$1;
	  }
	  var overflows = allowedPlacements.reduce(function (acc, placement) {
	    acc[placement] = detectOverflow(state, {
	      placement: placement,
	      boundary: boundary,
	      rootBoundary: rootBoundary,
	      padding: padding
	    })[getBasePlacement(placement)];
	    return acc;
	  }, {});
	  return Object.keys(overflows).sort(function (a, b) {
	    return overflows[a] - overflows[b];
	  });
	}

	function getExpandedFallbackPlacements(placement) {
	  if (getBasePlacement(placement) === auto) {
	    return [];
	  }
	  var oppositePlacement = getOppositePlacement(placement);
	  return [getOppositeVariationPlacement(placement), oppositePlacement, getOppositeVariationPlacement(oppositePlacement)];
	}
	function flip(_ref) {
	  var state = _ref.state,
	      options = _ref.options,
	      name = _ref.name;
	  if (state.modifiersData[name]._skip) {
	    return;
	  }
	  var _options$mainAxis = options.mainAxis,
	      checkMainAxis = _options$mainAxis === void 0 ? true : _options$mainAxis,
	      _options$altAxis = options.altAxis,
	      checkAltAxis = _options$altAxis === void 0 ? true : _options$altAxis,
	      specifiedFallbackPlacements = options.fallbackPlacements,
	      padding = options.padding,
	      boundary = options.boundary,
	      rootBoundary = options.rootBoundary,
	      altBoundary = options.altBoundary,
	      _options$flipVariatio = options.flipVariations,
	      flipVariations = _options$flipVariatio === void 0 ? true : _options$flipVariatio,
	      allowedAutoPlacements = options.allowedAutoPlacements;
	  var preferredPlacement = state.options.placement;
	  var basePlacement = getBasePlacement(preferredPlacement);
	  var isBasePlacement = basePlacement === preferredPlacement;
	  var fallbackPlacements = specifiedFallbackPlacements || (isBasePlacement || !flipVariations ? [getOppositePlacement(preferredPlacement)] : getExpandedFallbackPlacements(preferredPlacement));
	  var placements = [preferredPlacement].concat(fallbackPlacements).reduce(function (acc, placement) {
	    return acc.concat(getBasePlacement(placement) === auto ? computeAutoPlacement(state, {
	      placement: placement,
	      boundary: boundary,
	      rootBoundary: rootBoundary,
	      padding: padding,
	      flipVariations: flipVariations,
	      allowedAutoPlacements: allowedAutoPlacements
	    }) : placement);
	  }, []);
	  var referenceRect = state.rects.reference;
	  var popperRect = state.rects.popper;
	  var checksMap = new Map();
	  var makeFallbackChecks = true;
	  var firstFittingPlacement = placements[0];
	  for (var i = 0; i < placements.length; i++) {
	    var placement = placements[i];
	    var _basePlacement = getBasePlacement(placement);
	    var isStartVariation = getVariation(placement) === start;
	    var isVertical = [top, bottom].indexOf(_basePlacement) >= 0;
	    var len = isVertical ? 'width' : 'height';
	    var overflow = detectOverflow(state, {
	      placement: placement,
	      boundary: boundary,
	      rootBoundary: rootBoundary,
	      altBoundary: altBoundary,
	      padding: padding
	    });
	    var mainVariationSide = isVertical ? isStartVariation ? right : left : isStartVariation ? bottom : top;
	    if (referenceRect[len] > popperRect[len]) {
	      mainVariationSide = getOppositePlacement(mainVariationSide);
	    }
	    var altVariationSide = getOppositePlacement(mainVariationSide);
	    var checks = [];
	    if (checkMainAxis) {
	      checks.push(overflow[_basePlacement] <= 0);
	    }
	    if (checkAltAxis) {
	      checks.push(overflow[mainVariationSide] <= 0, overflow[altVariationSide] <= 0);
	    }
	    if (checks.every(function (check) {
	      return check;
	    })) {
	      firstFittingPlacement = placement;
	      makeFallbackChecks = false;
	      break;
	    }
	    checksMap.set(placement, checks);
	  }
	  if (makeFallbackChecks) {
	    var numberOfChecks = flipVariations ? 3 : 1;
	    var _loop = function _loop(_i) {
	      var fittingPlacement = placements.find(function (placement) {
	        var checks = checksMap.get(placement);
	        if (checks) {
	          return checks.slice(0, _i).every(function (check) {
	            return check;
	          });
	        }
	      });
	      if (fittingPlacement) {
	        firstFittingPlacement = fittingPlacement;
	        return "break";
	      }
	    };
	    for (var _i = numberOfChecks; _i > 0; _i--) {
	      var _ret = _loop(_i);
	      if (_ret === "break") break;
	    }
	  }
	  if (state.placement !== firstFittingPlacement) {
	    state.modifiersData[name]._skip = true;
	    state.placement = firstFittingPlacement;
	    state.reset = true;
	  }
	}
	var flip$1 = {
	  name: 'flip',
	  enabled: true,
	  phase: 'main',
	  fn: flip,
	  requiresIfExists: ['offset'],
	  data: {
	    _skip: false
	  }
	};

	function getSideOffsets(overflow, rect, preventedOffsets) {
	  if (preventedOffsets === void 0) {
	    preventedOffsets = {
	      x: 0,
	      y: 0
	    };
	  }
	  return {
	    top: overflow.top - rect.height - preventedOffsets.y,
	    right: overflow.right - rect.width + preventedOffsets.x,
	    bottom: overflow.bottom - rect.height + preventedOffsets.y,
	    left: overflow.left - rect.width - preventedOffsets.x
	  };
	}
	function isAnySideFullyClipped(overflow) {
	  return [top, right, bottom, left].some(function (side) {
	    return overflow[side] >= 0;
	  });
	}
	function hide(_ref) {
	  var state = _ref.state,
	      name = _ref.name;
	  var referenceRect = state.rects.reference;
	  var popperRect = state.rects.popper;
	  var preventedOffsets = state.modifiersData.preventOverflow;
	  var referenceOverflow = detectOverflow(state, {
	    elementContext: 'reference'
	  });
	  var popperAltOverflow = detectOverflow(state, {
	    altBoundary: true
	  });
	  var referenceClippingOffsets = getSideOffsets(referenceOverflow, referenceRect);
	  var popperEscapeOffsets = getSideOffsets(popperAltOverflow, popperRect, preventedOffsets);
	  var isReferenceHidden = isAnySideFullyClipped(referenceClippingOffsets);
	  var hasPopperEscaped = isAnySideFullyClipped(popperEscapeOffsets);
	  state.modifiersData[name] = {
	    referenceClippingOffsets: referenceClippingOffsets,
	    popperEscapeOffsets: popperEscapeOffsets,
	    isReferenceHidden: isReferenceHidden,
	    hasPopperEscaped: hasPopperEscaped
	  };
	  state.attributes.popper = Object.assign({}, state.attributes.popper, {
	    'data-popper-reference-hidden': isReferenceHidden,
	    'data-popper-escaped': hasPopperEscaped
	  });
	}
	var hide$1 = {
	  name: 'hide',
	  enabled: true,
	  phase: 'main',
	  requiresIfExists: ['preventOverflow'],
	  fn: hide
	};

	function distanceAndSkiddingToXY(placement, rects, offset) {
	  var basePlacement = getBasePlacement(placement);
	  var invertDistance = [left, top].indexOf(basePlacement) >= 0 ? -1 : 1;
	  var _ref = typeof offset === 'function' ? offset(Object.assign({}, rects, {
	    placement: placement
	  })) : offset,
	      skidding = _ref[0],
	      distance = _ref[1];
	  skidding = skidding || 0;
	  distance = (distance || 0) * invertDistance;
	  return [left, right].indexOf(basePlacement) >= 0 ? {
	    x: distance,
	    y: skidding
	  } : {
	    x: skidding,
	    y: distance
	  };
	}
	function offset(_ref2) {
	  var state = _ref2.state,
	      options = _ref2.options,
	      name = _ref2.name;
	  var _options$offset = options.offset,
	      offset = _options$offset === void 0 ? [0, 0] : _options$offset;
	  var data = placements.reduce(function (acc, placement) {
	    acc[placement] = distanceAndSkiddingToXY(placement, state.rects, offset);
	    return acc;
	  }, {});
	  var _data$state$placement = data[state.placement],
	      x = _data$state$placement.x,
	      y = _data$state$placement.y;
	  if (state.modifiersData.popperOffsets != null) {
	    state.modifiersData.popperOffsets.x += x;
	    state.modifiersData.popperOffsets.y += y;
	  }
	  state.modifiersData[name] = data;
	}
	var offset$1 = {
	  name: 'offset',
	  enabled: true,
	  phase: 'main',
	  requires: ['popperOffsets'],
	  fn: offset
	};

	function popperOffsets(_ref) {
	  var state = _ref.state,
	      name = _ref.name;
	  state.modifiersData[name] = computeOffsets({
	    reference: state.rects.reference,
	    element: state.rects.popper,
	    strategy: 'absolute',
	    placement: state.placement
	  });
	}
	var popperOffsets$1 = {
	  name: 'popperOffsets',
	  enabled: true,
	  phase: 'read',
	  fn: popperOffsets,
	  data: {}
	};

	function getAltAxis(axis) {
	  return axis === 'x' ? 'y' : 'x';
	}

	function preventOverflow(_ref) {
	  var state = _ref.state,
	      options = _ref.options,
	      name = _ref.name;
	  var _options$mainAxis = options.mainAxis,
	      checkMainAxis = _options$mainAxis === void 0 ? true : _options$mainAxis,
	      _options$altAxis = options.altAxis,
	      checkAltAxis = _options$altAxis === void 0 ? false : _options$altAxis,
	      boundary = options.boundary,
	      rootBoundary = options.rootBoundary,
	      altBoundary = options.altBoundary,
	      padding = options.padding,
	      _options$tether = options.tether,
	      tether = _options$tether === void 0 ? true : _options$tether,
	      _options$tetherOffset = options.tetherOffset,
	      tetherOffset = _options$tetherOffset === void 0 ? 0 : _options$tetherOffset;
	  var overflow = detectOverflow(state, {
	    boundary: boundary,
	    rootBoundary: rootBoundary,
	    padding: padding,
	    altBoundary: altBoundary
	  });
	  var basePlacement = getBasePlacement(state.placement);
	  var variation = getVariation(state.placement);
	  var isBasePlacement = !variation;
	  var mainAxis = getMainAxisFromPlacement(basePlacement);
	  var altAxis = getAltAxis(mainAxis);
	  var popperOffsets = state.modifiersData.popperOffsets;
	  var referenceRect = state.rects.reference;
	  var popperRect = state.rects.popper;
	  var tetherOffsetValue = typeof tetherOffset === 'function' ? tetherOffset(Object.assign({}, state.rects, {
	    placement: state.placement
	  })) : tetherOffset;
	  var normalizedTetherOffsetValue = typeof tetherOffsetValue === 'number' ? {
	    mainAxis: tetherOffsetValue,
	    altAxis: tetherOffsetValue
	  } : Object.assign({
	    mainAxis: 0,
	    altAxis: 0
	  }, tetherOffsetValue);
	  var offsetModifierState = state.modifiersData.offset ? state.modifiersData.offset[state.placement] : null;
	  var data = {
	    x: 0,
	    y: 0
	  };
	  if (!popperOffsets) {
	    return;
	  }
	  if (checkMainAxis) {
	    var _offsetModifierState$;
	    var mainSide = mainAxis === 'y' ? top : left;
	    var altSide = mainAxis === 'y' ? bottom : right;
	    var len = mainAxis === 'y' ? 'height' : 'width';
	    var offset = popperOffsets[mainAxis];
	    var min$1 = offset + overflow[mainSide];
	    var max$1 = offset - overflow[altSide];
	    var additive = tether ? -popperRect[len] / 2 : 0;
	    var minLen = variation === start ? referenceRect[len] : popperRect[len];
	    var maxLen = variation === start ? -popperRect[len] : -referenceRect[len];
	    var arrowElement = state.elements.arrow;
	    var arrowRect = tether && arrowElement ? getLayoutRect(arrowElement) : {
	      width: 0,
	      height: 0
	    };
	    var arrowPaddingObject = state.modifiersData['arrow#persistent'] ? state.modifiersData['arrow#persistent'].padding : getFreshSideObject();
	    var arrowPaddingMin = arrowPaddingObject[mainSide];
	    var arrowPaddingMax = arrowPaddingObject[altSide];
	    var arrowLen = within(0, referenceRect[len], arrowRect[len]);
	    var minOffset = isBasePlacement ? referenceRect[len] / 2 - additive - arrowLen - arrowPaddingMin - normalizedTetherOffsetValue.mainAxis : minLen - arrowLen - arrowPaddingMin - normalizedTetherOffsetValue.mainAxis;
	    var maxOffset = isBasePlacement ? -referenceRect[len] / 2 + additive + arrowLen + arrowPaddingMax + normalizedTetherOffsetValue.mainAxis : maxLen + arrowLen + arrowPaddingMax + normalizedTetherOffsetValue.mainAxis;
	    var arrowOffsetParent = state.elements.arrow && getOffsetParent(state.elements.arrow);
	    var clientOffset = arrowOffsetParent ? mainAxis === 'y' ? arrowOffsetParent.clientTop || 0 : arrowOffsetParent.clientLeft || 0 : 0;
	    var offsetModifierValue = (_offsetModifierState$ = offsetModifierState == null ? void 0 : offsetModifierState[mainAxis]) != null ? _offsetModifierState$ : 0;
	    var tetherMin = offset + minOffset - offsetModifierValue - clientOffset;
	    var tetherMax = offset + maxOffset - offsetModifierValue;
	    var preventedOffset = within(tether ? min(min$1, tetherMin) : min$1, offset, tether ? max(max$1, tetherMax) : max$1);
	    popperOffsets[mainAxis] = preventedOffset;
	    data[mainAxis] = preventedOffset - offset;
	  }
	  if (checkAltAxis) {
	    var _offsetModifierState$2;
	    var _mainSide = mainAxis === 'x' ? top : left;
	    var _altSide = mainAxis === 'x' ? bottom : right;
	    var _offset = popperOffsets[altAxis];
	    var _len = altAxis === 'y' ? 'height' : 'width';
	    var _min = _offset + overflow[_mainSide];
	    var _max = _offset - overflow[_altSide];
	    var isOriginSide = [top, left].indexOf(basePlacement) !== -1;
	    var _offsetModifierValue = (_offsetModifierState$2 = offsetModifierState == null ? void 0 : offsetModifierState[altAxis]) != null ? _offsetModifierState$2 : 0;
	    var _tetherMin = isOriginSide ? _min : _offset - referenceRect[_len] - popperRect[_len] - _offsetModifierValue + normalizedTetherOffsetValue.altAxis;
	    var _tetherMax = isOriginSide ? _offset + referenceRect[_len] + popperRect[_len] - _offsetModifierValue - normalizedTetherOffsetValue.altAxis : _max;
	    var _preventedOffset = tether && isOriginSide ? withinMaxClamp(_tetherMin, _offset, _tetherMax) : within(tether ? _tetherMin : _min, _offset, tether ? _tetherMax : _max);
	    popperOffsets[altAxis] = _preventedOffset;
	    data[altAxis] = _preventedOffset - _offset;
	  }
	  state.modifiersData[name] = data;
	}
	var preventOverflow$1 = {
	  name: 'preventOverflow',
	  enabled: true,
	  phase: 'main',
	  fn: preventOverflow,
	  requiresIfExists: ['offset']
	};

	function getHTMLElementScroll(element) {
	  return {
	    scrollLeft: element.scrollLeft,
	    scrollTop: element.scrollTop
	  };
	}

	function getNodeScroll(node) {
	  if (node === getWindow(node) || !isHTMLElement(node)) {
	    return getWindowScroll(node);
	  } else {
	    return getHTMLElementScroll(node);
	  }
	}

	function isElementScaled(element) {
	  var rect = element.getBoundingClientRect();
	  var scaleX = round(rect.width) / element.offsetWidth || 1;
	  var scaleY = round(rect.height) / element.offsetHeight || 1;
	  return scaleX !== 1 || scaleY !== 1;
	}
	function getCompositeRect(elementOrVirtualElement, offsetParent, isFixed) {
	  if (isFixed === void 0) {
	    isFixed = false;
	  }
	  var isOffsetParentAnElement = isHTMLElement(offsetParent);
	  var offsetParentIsScaled = isHTMLElement(offsetParent) && isElementScaled(offsetParent);
	  var documentElement = getDocumentElement(offsetParent);
	  var rect = getBoundingClientRect(elementOrVirtualElement, offsetParentIsScaled, isFixed);
	  var scroll = {
	    scrollLeft: 0,
	    scrollTop: 0
	  };
	  var offsets = {
	    x: 0,
	    y: 0
	  };
	  if (isOffsetParentAnElement || !isOffsetParentAnElement && !isFixed) {
	    if (getNodeName(offsetParent) !== 'body' ||
	    isScrollParent(documentElement)) {
	      scroll = getNodeScroll(offsetParent);
	    }
	    if (isHTMLElement(offsetParent)) {
	      offsets = getBoundingClientRect(offsetParent, true);
	      offsets.x += offsetParent.clientLeft;
	      offsets.y += offsetParent.clientTop;
	    } else if (documentElement) {
	      offsets.x = getWindowScrollBarX(documentElement);
	    }
	  }
	  return {
	    x: rect.left + scroll.scrollLeft - offsets.x,
	    y: rect.top + scroll.scrollTop - offsets.y,
	    width: rect.width,
	    height: rect.height
	  };
	}

	function order(modifiers) {
	  var map = new Map();
	  var visited = new Set();
	  var result = [];
	  modifiers.forEach(function (modifier) {
	    map.set(modifier.name, modifier);
	  });
	  function sort(modifier) {
	    visited.add(modifier.name);
	    var requires = [].concat(modifier.requires || [], modifier.requiresIfExists || []);
	    requires.forEach(function (dep) {
	      if (!visited.has(dep)) {
	        var depModifier = map.get(dep);
	        if (depModifier) {
	          sort(depModifier);
	        }
	      }
	    });
	    result.push(modifier);
	  }
	  modifiers.forEach(function (modifier) {
	    if (!visited.has(modifier.name)) {
	      sort(modifier);
	    }
	  });
	  return result;
	}
	function orderModifiers(modifiers) {
	  var orderedModifiers = order(modifiers);
	  return modifierPhases.reduce(function (acc, phase) {
	    return acc.concat(orderedModifiers.filter(function (modifier) {
	      return modifier.phase === phase;
	    }));
	  }, []);
	}

	function debounce(fn) {
	  var pending;
	  return function () {
	    if (!pending) {
	      pending = new Promise(function (resolve) {
	        Promise.resolve().then(function () {
	          pending = undefined;
	          resolve(fn());
	        });
	      });
	    }
	    return pending;
	  };
	}

	function mergeByName(modifiers) {
	  var merged = modifiers.reduce(function (merged, current) {
	    var existing = merged[current.name];
	    merged[current.name] = existing ? Object.assign({}, existing, current, {
	      options: Object.assign({}, existing.options, current.options),
	      data: Object.assign({}, existing.data, current.data)
	    }) : current;
	    return merged;
	  }, {});
	  return Object.keys(merged).map(function (key) {
	    return merged[key];
	  });
	}

	var DEFAULT_OPTIONS = {
	  placement: 'bottom',
	  modifiers: [],
	  strategy: 'absolute'
	};
	function areValidElements() {
	  for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
	    args[_key] = arguments[_key];
	  }
	  return !args.some(function (element) {
	    return !(element && typeof element.getBoundingClientRect === 'function');
	  });
	}
	function popperGenerator(generatorOptions) {
	  if (generatorOptions === void 0) {
	    generatorOptions = {};
	  }
	  var _generatorOptions = generatorOptions,
	      _generatorOptions$def = _generatorOptions.defaultModifiers,
	      defaultModifiers = _generatorOptions$def === void 0 ? [] : _generatorOptions$def,
	      _generatorOptions$def2 = _generatorOptions.defaultOptions,
	      defaultOptions = _generatorOptions$def2 === void 0 ? DEFAULT_OPTIONS : _generatorOptions$def2;
	  return function createPopper(reference, popper, options) {
	    if (options === void 0) {
	      options = defaultOptions;
	    }
	    var state = {
	      placement: 'bottom',
	      orderedModifiers: [],
	      options: Object.assign({}, DEFAULT_OPTIONS, defaultOptions),
	      modifiersData: {},
	      elements: {
	        reference: reference,
	        popper: popper
	      },
	      attributes: {},
	      styles: {}
	    };
	    var effectCleanupFns = [];
	    var isDestroyed = false;
	    var instance = {
	      state: state,
	      setOptions: function setOptions(setOptionsAction) {
	        var options = typeof setOptionsAction === 'function' ? setOptionsAction(state.options) : setOptionsAction;
	        cleanupModifierEffects();
	        state.options = Object.assign({}, defaultOptions, state.options, options);
	        state.scrollParents = {
	          reference: isElement$1(reference) ? listScrollParents(reference) : reference.contextElement ? listScrollParents(reference.contextElement) : [],
	          popper: listScrollParents(popper)
	        };
	        var orderedModifiers = orderModifiers(mergeByName([].concat(defaultModifiers, state.options.modifiers)));
	        state.orderedModifiers = orderedModifiers.filter(function (m) {
	          return m.enabled;
	        });
	        runModifierEffects();
	        return instance.update();
	      },
	      forceUpdate: function forceUpdate() {
	        if (isDestroyed) {
	          return;
	        }
	        var _state$elements = state.elements,
	            reference = _state$elements.reference,
	            popper = _state$elements.popper;
	        if (!areValidElements(reference, popper)) {
	          return;
	        }
	        state.rects = {
	          reference: getCompositeRect(reference, getOffsetParent(popper), state.options.strategy === 'fixed'),
	          popper: getLayoutRect(popper)
	        };
	        state.reset = false;
	        state.placement = state.options.placement;
	        state.orderedModifiers.forEach(function (modifier) {
	          return state.modifiersData[modifier.name] = Object.assign({}, modifier.data);
	        });
	        for (var index = 0; index < state.orderedModifiers.length; index++) {
	          if (state.reset === true) {
	            state.reset = false;
	            index = -1;
	            continue;
	          }
	          var _state$orderedModifie = state.orderedModifiers[index],
	              fn = _state$orderedModifie.fn,
	              _state$orderedModifie2 = _state$orderedModifie.options,
	              _options = _state$orderedModifie2 === void 0 ? {} : _state$orderedModifie2,
	              name = _state$orderedModifie.name;
	          if (typeof fn === 'function') {
	            state = fn({
	              state: state,
	              options: _options,
	              name: name,
	              instance: instance
	            }) || state;
	          }
	        }
	      },
	      update: debounce(function () {
	        return new Promise(function (resolve) {
	          instance.forceUpdate();
	          resolve(state);
	        });
	      }),
	      destroy: function destroy() {
	        cleanupModifierEffects();
	        isDestroyed = true;
	      }
	    };
	    if (!areValidElements(reference, popper)) {
	      return instance;
	    }
	    instance.setOptions(options).then(function (state) {
	      if (!isDestroyed && options.onFirstUpdate) {
	        options.onFirstUpdate(state);
	      }
	    });
	    function runModifierEffects() {
	      state.orderedModifiers.forEach(function (_ref3) {
	        var name = _ref3.name,
	            _ref3$options = _ref3.options,
	            options = _ref3$options === void 0 ? {} : _ref3$options,
	            effect = _ref3.effect;
	        if (typeof effect === 'function') {
	          var cleanupFn = effect({
	            state: state,
	            name: name,
	            instance: instance,
	            options: options
	          });
	          var noopFn = function noopFn() {};
	          effectCleanupFns.push(cleanupFn || noopFn);
	        }
	      });
	    }
	    function cleanupModifierEffects() {
	      effectCleanupFns.forEach(function (fn) {
	        return fn();
	      });
	      effectCleanupFns = [];
	    }
	    return instance;
	  };
	}
	var createPopper$2 = popperGenerator();

	var defaultModifiers$1 = [eventListeners, popperOffsets$1, computeStyles$1, applyStyles$1];
	var createPopper$1 = popperGenerator({
	  defaultModifiers: defaultModifiers$1
	});

	var defaultModifiers = [eventListeners, popperOffsets$1, computeStyles$1, applyStyles$1, offset$1, flip$1, preventOverflow$1, arrow$1, hide$1];
	var createPopper = popperGenerator({
	  defaultModifiers: defaultModifiers
	});

	var Popper = /*#__PURE__*/Object.freeze({
		__proto__: null,
		popperGenerator: popperGenerator,
		detectOverflow: detectOverflow,
		createPopperBase: createPopper$2,
		createPopper: createPopper,
		createPopperLite: createPopper$1,
		top: top,
		bottom: bottom,
		right: right,
		left: left,
		auto: auto,
		basePlacements: basePlacements,
		start: start,
		end: end,
		clippingParents: clippingParents,
		viewport: viewport,
		popper: popper,
		reference: reference,
		variationPlacements: variationPlacements,
		placements: placements,
		beforeRead: beforeRead,
		read: read,
		afterRead: afterRead,
		beforeMain: beforeMain,
		main: main,
		afterMain: afterMain,
		beforeWrite: beforeWrite,
		write: write,
		afterWrite: afterWrite,
		modifierPhases: modifierPhases,
		applyStyles: applyStyles$1,
		arrow: arrow$1,
		computeStyles: computeStyles$1,
		eventListeners: eventListeners,
		flip: flip$1,
		hide: hide$1,
		offset: offset$1,
		popperOffsets: popperOffsets$1,
		preventOverflow: preventOverflow$1
	});

	/*!
	  * Bootstrap v5.2.2 (https://getbootstrap.com/)
	  * Copyright 2011-2022 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
	  * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
	  */
	const MAX_UID = 1000000;
	const MILLISECONDS_MULTIPLIER = 1000;
	const TRANSITION_END = 'transitionend';
	const toType = object => {
	  if (object === null || object === undefined) {
	    return `${object}`;
	  }
	  return Object.prototype.toString.call(object).match(/\s([a-z]+)/i)[1].toLowerCase();
	};
	const getUID = prefix => {
	  do {
	    prefix += Math.floor(Math.random() * MAX_UID);
	  } while (document.getElementById(prefix));
	  return prefix;
	};
	const getSelector = element => {
	  let selector = element.getAttribute('data-bs-target');
	  if (!selector || selector === '#') {
	    let hrefAttribute = element.getAttribute('href');
	    if (!hrefAttribute || !hrefAttribute.includes('#') && !hrefAttribute.startsWith('.')) {
	      return null;
	    }
	    if (hrefAttribute.includes('#') && !hrefAttribute.startsWith('#')) {
	      hrefAttribute = `#${hrefAttribute.split('#')[1]}`;
	    }
	    selector = hrefAttribute && hrefAttribute !== '#' ? hrefAttribute.trim() : null;
	  }
	  return selector;
	};
	const getSelectorFromElement = element => {
	  const selector = getSelector(element);
	  if (selector) {
	    return document.querySelector(selector) ? selector : null;
	  }
	  return null;
	};
	const getElementFromSelector = element => {
	  const selector = getSelector(element);
	  return selector ? document.querySelector(selector) : null;
	};
	const getTransitionDurationFromElement = element => {
	  if (!element) {
	    return 0;
	  }
	  let {
	    transitionDuration,
	    transitionDelay
	  } = window.getComputedStyle(element);
	  const floatTransitionDuration = Number.parseFloat(transitionDuration);
	  const floatTransitionDelay = Number.parseFloat(transitionDelay);
	  if (!floatTransitionDuration && !floatTransitionDelay) {
	    return 0;
	  }
	  transitionDuration = transitionDuration.split(',')[0];
	  transitionDelay = transitionDelay.split(',')[0];
	  return (Number.parseFloat(transitionDuration) + Number.parseFloat(transitionDelay)) * MILLISECONDS_MULTIPLIER;
	};
	const triggerTransitionEnd = element => {
	  element.dispatchEvent(new Event(TRANSITION_END));
	};
	const isElement = object => {
	  if (!object || typeof object !== 'object') {
	    return false;
	  }
	  if (typeof object.jquery !== 'undefined') {
	    object = object[0];
	  }
	  return typeof object.nodeType !== 'undefined';
	};
	const getElement = object => {
	  if (isElement(object)) {
	    return object.jquery ? object[0] : object;
	  }
	  if (typeof object === 'string' && object.length > 0) {
	    return document.querySelector(object);
	  }
	  return null;
	};
	const isVisible = element => {
	  if (!isElement(element) || element.getClientRects().length === 0) {
	    return false;
	  }
	  const elementIsVisible = getComputedStyle(element).getPropertyValue('visibility') === 'visible';
	  const closedDetails = element.closest('details:not([open])');
	  if (!closedDetails) {
	    return elementIsVisible;
	  }
	  if (closedDetails !== element) {
	    const summary = element.closest('summary');
	    if (summary && summary.parentNode !== closedDetails) {
	      return false;
	    }
	    if (summary === null) {
	      return false;
	    }
	  }
	  return elementIsVisible;
	};
	const isDisabled = element => {
	  if (!element || element.nodeType !== Node.ELEMENT_NODE) {
	    return true;
	  }
	  if (element.classList.contains('disabled')) {
	    return true;
	  }
	  if (typeof element.disabled !== 'undefined') {
	    return element.disabled;
	  }
	  return element.hasAttribute('disabled') && element.getAttribute('disabled') !== 'false';
	};
	const findShadowRoot = element => {
	  if (!document.documentElement.attachShadow) {
	    return null;
	  }
	  if (typeof element.getRootNode === 'function') {
	    const root = element.getRootNode();
	    return root instanceof ShadowRoot ? root : null;
	  }
	  if (element instanceof ShadowRoot) {
	    return element;
	  }
	  if (!element.parentNode) {
	    return null;
	  }
	  return findShadowRoot(element.parentNode);
	};
	const noop = () => {};
	const reflow = element => {
	  element.offsetHeight;
	};
	const getjQuery = () => {
	  if (window.jQuery && !document.body.hasAttribute('data-bs-no-jquery')) {
	    return window.jQuery;
	  }
	  return null;
	};
	const DOMContentLoadedCallbacks = [];
	const onDOMContentLoaded = callback => {
	  if (document.readyState === 'loading') {
	    if (!DOMContentLoadedCallbacks.length) {
	      document.addEventListener('DOMContentLoaded', () => {
	        for (const callback of DOMContentLoadedCallbacks) {
	          callback();
	        }
	      });
	    }
	    DOMContentLoadedCallbacks.push(callback);
	  } else {
	    callback();
	  }
	};
	const isRTL = () => document.documentElement.dir === 'rtl';
	const defineJQueryPlugin = plugin => {
	  onDOMContentLoaded(() => {
	    const $ = getjQuery();
	    if ($) {
	      const name = plugin.NAME;
	      const JQUERY_NO_CONFLICT = $.fn[name];
	      $.fn[name] = plugin.jQueryInterface;
	      $.fn[name].Constructor = plugin;
	      $.fn[name].noConflict = () => {
	        $.fn[name] = JQUERY_NO_CONFLICT;
	        return plugin.jQueryInterface;
	      };
	    }
	  });
	};
	const execute = callback => {
	  if (typeof callback === 'function') {
	    callback();
	  }
	};
	const executeAfterTransition = (callback, transitionElement, waitForTransition = true) => {
	  if (!waitForTransition) {
	    execute(callback);
	    return;
	  }
	  const durationPadding = 5;
	  const emulatedDuration = getTransitionDurationFromElement(transitionElement) + durationPadding;
	  let called = false;
	  const handler = ({
	    target
	  }) => {
	    if (target !== transitionElement) {
	      return;
	    }
	    called = true;
	    transitionElement.removeEventListener(TRANSITION_END, handler);
	    execute(callback);
	  };
	  transitionElement.addEventListener(TRANSITION_END, handler);
	  setTimeout(() => {
	    if (!called) {
	      triggerTransitionEnd(transitionElement);
	    }
	  }, emulatedDuration);
	};
	const getNextActiveElement = (list, activeElement, shouldGetNext, isCycleAllowed) => {
	  const listLength = list.length;
	  let index = list.indexOf(activeElement);
	  if (index === -1) {
	    return !shouldGetNext && isCycleAllowed ? list[listLength - 1] : list[0];
	  }
	  index += shouldGetNext ? 1 : -1;
	  if (isCycleAllowed) {
	    index = (index + listLength) % listLength;
	  }
	  return list[Math.max(0, Math.min(index, listLength - 1))];
	};
	const namespaceRegex = /[^.]*(?=\..*)\.|.*/;
	const stripNameRegex = /\..*/;
	const stripUidRegex = /::\d+$/;
	const eventRegistry = {};
	let uidEvent = 1;
	const customEvents = {
	  mouseenter: 'mouseover',
	  mouseleave: 'mouseout'
	};
	const nativeEvents = new Set(['click', 'dblclick', 'mouseup', 'mousedown', 'contextmenu', 'mousewheel', 'DOMMouseScroll', 'mouseover', 'mouseout', 'mousemove', 'selectstart', 'selectend', 'keydown', 'keypress', 'keyup', 'orientationchange', 'touchstart', 'touchmove', 'touchend', 'touchcancel', 'pointerdown', 'pointermove', 'pointerup', 'pointerleave', 'pointercancel', 'gesturestart', 'gesturechange', 'gestureend', 'focus', 'blur', 'change', 'reset', 'select', 'submit', 'focusin', 'focusout', 'load', 'unload', 'beforeunload', 'resize', 'move', 'DOMContentLoaded', 'readystatechange', 'error', 'abort', 'scroll']);
	function makeEventUid(element, uid) {
	  return uid && `${uid}::${uidEvent++}` || element.uidEvent || uidEvent++;
	}
	function getElementEvents(element) {
	  const uid = makeEventUid(element);
	  element.uidEvent = uid;
	  eventRegistry[uid] = eventRegistry[uid] || {};
	  return eventRegistry[uid];
	}
	function bootstrapHandler(element, fn) {
	  return function handler(event) {
	    hydrateObj(event, {
	      delegateTarget: element
	    });
	    if (handler.oneOff) {
	      EventHandler.off(element, event.type, fn);
	    }
	    return fn.apply(element, [event]);
	  };
	}
	function bootstrapDelegationHandler(element, selector, fn) {
	  return function handler(event) {
	    const domElements = element.querySelectorAll(selector);
	    for (let {
	      target
	    } = event; target && target !== this; target = target.parentNode) {
	      for (const domElement of domElements) {
	        if (domElement !== target) {
	          continue;
	        }
	        hydrateObj(event, {
	          delegateTarget: target
	        });
	        if (handler.oneOff) {
	          EventHandler.off(element, event.type, selector, fn);
	        }
	        return fn.apply(target, [event]);
	      }
	    }
	  };
	}
	function findHandler(events, callable, delegationSelector = null) {
	  return Object.values(events).find(event => event.callable === callable && event.delegationSelector === delegationSelector);
	}
	function normalizeParameters(originalTypeEvent, handler, delegationFunction) {
	  const isDelegated = typeof handler === 'string';
	  const callable = isDelegated ? delegationFunction : handler || delegationFunction;
	  let typeEvent = getTypeEvent(originalTypeEvent);
	  if (!nativeEvents.has(typeEvent)) {
	    typeEvent = originalTypeEvent;
	  }
	  return [isDelegated, callable, typeEvent];
	}
	function addHandler(element, originalTypeEvent, handler, delegationFunction, oneOff) {
	  if (typeof originalTypeEvent !== 'string' || !element) {
	    return;
	  }
	  let [isDelegated, callable, typeEvent] = normalizeParameters(originalTypeEvent, handler, delegationFunction);
	  if (originalTypeEvent in customEvents) {
	    const wrapFunction = fn => {
	      return function (event) {
	        if (!event.relatedTarget || event.relatedTarget !== event.delegateTarget && !event.delegateTarget.contains(event.relatedTarget)) {
	          return fn.call(this, event);
	        }
	      };
	    };
	    callable = wrapFunction(callable);
	  }
	  const events = getElementEvents(element);
	  const handlers = events[typeEvent] || (events[typeEvent] = {});
	  const previousFunction = findHandler(handlers, callable, isDelegated ? handler : null);
	  if (previousFunction) {
	    previousFunction.oneOff = previousFunction.oneOff && oneOff;
	    return;
	  }
	  const uid = makeEventUid(callable, originalTypeEvent.replace(namespaceRegex, ''));
	  const fn = isDelegated ? bootstrapDelegationHandler(element, handler, callable) : bootstrapHandler(element, callable);
	  fn.delegationSelector = isDelegated ? handler : null;
	  fn.callable = callable;
	  fn.oneOff = oneOff;
	  fn.uidEvent = uid;
	  handlers[uid] = fn;
	  element.addEventListener(typeEvent, fn, isDelegated);
	}
	function removeHandler(element, events, typeEvent, handler, delegationSelector) {
	  const fn = findHandler(events[typeEvent], handler, delegationSelector);
	  if (!fn) {
	    return;
	  }
	  element.removeEventListener(typeEvent, fn, Boolean(delegationSelector));
	  delete events[typeEvent][fn.uidEvent];
	}
	function removeNamespacedHandlers(element, events, typeEvent, namespace) {
	  const storeElementEvent = events[typeEvent] || {};
	  for (const handlerKey of Object.keys(storeElementEvent)) {
	    if (handlerKey.includes(namespace)) {
	      const event = storeElementEvent[handlerKey];
	      removeHandler(element, events, typeEvent, event.callable, event.delegationSelector);
	    }
	  }
	}
	function getTypeEvent(event) {
	  event = event.replace(stripNameRegex, '');
	  return customEvents[event] || event;
	}
	const EventHandler = {
	  on(element, event, handler, delegationFunction) {
	    addHandler(element, event, handler, delegationFunction, false);
	  },
	  one(element, event, handler, delegationFunction) {
	    addHandler(element, event, handler, delegationFunction, true);
	  },
	  off(element, originalTypeEvent, handler, delegationFunction) {
	    if (typeof originalTypeEvent !== 'string' || !element) {
	      return;
	    }
	    const [isDelegated, callable, typeEvent] = normalizeParameters(originalTypeEvent, handler, delegationFunction);
	    const inNamespace = typeEvent !== originalTypeEvent;
	    const events = getElementEvents(element);
	    const storeElementEvent = events[typeEvent] || {};
	    const isNamespace = originalTypeEvent.startsWith('.');
	    if (typeof callable !== 'undefined') {
	      if (!Object.keys(storeElementEvent).length) {
	        return;
	      }
	      removeHandler(element, events, typeEvent, callable, isDelegated ? handler : null);
	      return;
	    }
	    if (isNamespace) {
	      for (const elementEvent of Object.keys(events)) {
	        removeNamespacedHandlers(element, events, elementEvent, originalTypeEvent.slice(1));
	      }
	    }
	    for (const keyHandlers of Object.keys(storeElementEvent)) {
	      const handlerKey = keyHandlers.replace(stripUidRegex, '');
	      if (!inNamespace || originalTypeEvent.includes(handlerKey)) {
	        const event = storeElementEvent[keyHandlers];
	        removeHandler(element, events, typeEvent, event.callable, event.delegationSelector);
	      }
	    }
	  },
	  trigger(element, event, args) {
	    if (typeof event !== 'string' || !element) {
	      return null;
	    }
	    const $ = getjQuery();
	    const typeEvent = getTypeEvent(event);
	    const inNamespace = event !== typeEvent;
	    let jQueryEvent = null;
	    let bubbles = true;
	    let nativeDispatch = true;
	    let defaultPrevented = false;
	    if (inNamespace && $) {
	      jQueryEvent = $.Event(event, args);
	      $(element).trigger(jQueryEvent);
	      bubbles = !jQueryEvent.isPropagationStopped();
	      nativeDispatch = !jQueryEvent.isImmediatePropagationStopped();
	      defaultPrevented = jQueryEvent.isDefaultPrevented();
	    }
	    let evt = new Event(event, {
	      bubbles,
	      cancelable: true
	    });
	    evt = hydrateObj(evt, args);
	    if (defaultPrevented) {
	      evt.preventDefault();
	    }
	    if (nativeDispatch) {
	      element.dispatchEvent(evt);
	    }
	    if (evt.defaultPrevented && jQueryEvent) {
	      jQueryEvent.preventDefault();
	    }
	    return evt;
	  }
	};
	function hydrateObj(obj, meta) {
	  for (const [key, value] of Object.entries(meta || {})) {
	    try {
	      obj[key] = value;
	    } catch (_unused) {
	      Object.defineProperty(obj, key, {
	        configurable: true,
	        get() {
	          return value;
	        }
	      });
	    }
	  }
	  return obj;
	}
	const elementMap = new Map();
	const Data = {
	  set(element, key, instance) {
	    if (!elementMap.has(element)) {
	      elementMap.set(element, new Map());
	    }
	    const instanceMap = elementMap.get(element);
	    if (!instanceMap.has(key) && instanceMap.size !== 0) {
	      console.error(`Bootstrap doesn't allow more than one instance per element. Bound instance: ${Array.from(instanceMap.keys())[0]}.`);
	      return;
	    }
	    instanceMap.set(key, instance);
	  },
	  get(element, key) {
	    if (elementMap.has(element)) {
	      return elementMap.get(element).get(key) || null;
	    }
	    return null;
	  },
	  remove(element, key) {
	    if (!elementMap.has(element)) {
	      return;
	    }
	    const instanceMap = elementMap.get(element);
	    instanceMap.delete(key);
	    if (instanceMap.size === 0) {
	      elementMap.delete(element);
	    }
	  }
	};
	function normalizeData(value) {
	  if (value === 'true') {
	    return true;
	  }
	  if (value === 'false') {
	    return false;
	  }
	  if (value === Number(value).toString()) {
	    return Number(value);
	  }
	  if (value === '' || value === 'null') {
	    return null;
	  }
	  if (typeof value !== 'string') {
	    return value;
	  }
	  try {
	    return JSON.parse(decodeURIComponent(value));
	  } catch (_unused) {
	    return value;
	  }
	}
	function normalizeDataKey(key) {
	  return key.replace(/[A-Z]/g, chr => `-${chr.toLowerCase()}`);
	}
	const Manipulator = {
	  setDataAttribute(element, key, value) {
	    element.setAttribute(`data-bs-${normalizeDataKey(key)}`, value);
	  },
	  removeDataAttribute(element, key) {
	    element.removeAttribute(`data-bs-${normalizeDataKey(key)}`);
	  },
	  getDataAttributes(element) {
	    if (!element) {
	      return {};
	    }
	    const attributes = {};
	    const bsKeys = Object.keys(element.dataset).filter(key => key.startsWith('bs') && !key.startsWith('bsConfig'));
	    for (const key of bsKeys) {
	      let pureKey = key.replace(/^bs/, '');
	      pureKey = pureKey.charAt(0).toLowerCase() + pureKey.slice(1, pureKey.length);
	      attributes[pureKey] = normalizeData(element.dataset[key]);
	    }
	    return attributes;
	  },
	  getDataAttribute(element, key) {
	    return normalizeData(element.getAttribute(`data-bs-${normalizeDataKey(key)}`));
	  }
	};
	class Config {
	  static get Default() {
	    return {};
	  }
	  static get DefaultType() {
	    return {};
	  }
	  static get NAME() {
	    throw new Error('You have to implement the static method "NAME", for each component!');
	  }
	  _getConfig(config) {
	    config = this._mergeConfigObj(config);
	    config = this._configAfterMerge(config);
	    this._typeCheckConfig(config);
	    return config;
	  }
	  _configAfterMerge(config) {
	    return config;
	  }
	  _mergeConfigObj(config, element) {
	    const jsonConfig = isElement(element) ? Manipulator.getDataAttribute(element, 'config') : {};
	    return { ...this.constructor.Default,
	      ...(typeof jsonConfig === 'object' ? jsonConfig : {}),
	      ...(isElement(element) ? Manipulator.getDataAttributes(element) : {}),
	      ...(typeof config === 'object' ? config : {})
	    };
	  }
	  _typeCheckConfig(config, configTypes = this.constructor.DefaultType) {
	    for (const property of Object.keys(configTypes)) {
	      const expectedTypes = configTypes[property];
	      const value = config[property];
	      const valueType = isElement(value) ? 'element' : toType(value);
	      if (!new RegExp(expectedTypes).test(valueType)) {
	        throw new TypeError(`${this.constructor.NAME.toUpperCase()}: Option "${property}" provided type "${valueType}" but expected type "${expectedTypes}".`);
	      }
	    }
	  }
	}
	const VERSION = '5.2.2';
	class BaseComponent extends Config {
	  constructor(element, config) {
	    super();
	    element = getElement(element);
	    if (!element) {
	      return;
	    }
	    this._element = element;
	    this._config = this._getConfig(config);
	    Data.set(this._element, this.constructor.DATA_KEY, this);
	  }
	  dispose() {
	    Data.remove(this._element, this.constructor.DATA_KEY);
	    EventHandler.off(this._element, this.constructor.EVENT_KEY);
	    for (const propertyName of Object.getOwnPropertyNames(this)) {
	      this[propertyName] = null;
	    }
	  }
	  _queueCallback(callback, element, isAnimated = true) {
	    executeAfterTransition(callback, element, isAnimated);
	  }
	  _getConfig(config) {
	    config = this._mergeConfigObj(config, this._element);
	    config = this._configAfterMerge(config);
	    this._typeCheckConfig(config);
	    return config;
	  }
	  static getInstance(element) {
	    return Data.get(getElement(element), this.DATA_KEY);
	  }
	  static getOrCreateInstance(element, config = {}) {
	    return this.getInstance(element) || new this(element, typeof config === 'object' ? config : null);
	  }
	  static get VERSION() {
	    return VERSION;
	  }
	  static get DATA_KEY() {
	    return `bs.${this.NAME}`;
	  }
	  static get EVENT_KEY() {
	    return `.${this.DATA_KEY}`;
	  }
	  static eventName(name) {
	    return `${name}${this.EVENT_KEY}`;
	  }
	}
	const enableDismissTrigger = (component, method = 'hide') => {
	  const clickEvent = `click.dismiss${component.EVENT_KEY}`;
	  const name = component.NAME;
	  EventHandler.on(document, clickEvent, `[data-bs-dismiss="${name}"]`, function (event) {
	    if (['A', 'AREA'].includes(this.tagName)) {
	      event.preventDefault();
	    }
	    if (isDisabled(this)) {
	      return;
	    }
	    const target = getElementFromSelector(this) || this.closest(`.${name}`);
	    const instance = component.getOrCreateInstance(target);
	    instance[method]();
	  });
	};
	const NAME$f = 'alert';
	const DATA_KEY$a = 'bs.alert';
	const EVENT_KEY$b = `.${DATA_KEY$a}`;
	const EVENT_CLOSE = `close${EVENT_KEY$b}`;
	const EVENT_CLOSED = `closed${EVENT_KEY$b}`;
	const CLASS_NAME_FADE$5 = 'fade';
	const CLASS_NAME_SHOW$8 = 'show';
	class Alert extends BaseComponent {
	  static get NAME() {
	    return NAME$f;
	  }
	  close() {
	    const closeEvent = EventHandler.trigger(this._element, EVENT_CLOSE);
	    if (closeEvent.defaultPrevented) {
	      return;
	    }
	    this._element.classList.remove(CLASS_NAME_SHOW$8);
	    const isAnimated = this._element.classList.contains(CLASS_NAME_FADE$5);
	    this._queueCallback(() => this._destroyElement(), this._element, isAnimated);
	  }
	  _destroyElement() {
	    this._element.remove();
	    EventHandler.trigger(this._element, EVENT_CLOSED);
	    this.dispose();
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Alert.getOrCreateInstance(this);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (data[config] === undefined || config.startsWith('_') || config === 'constructor') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config](this);
	    });
	  }
	}
	enableDismissTrigger(Alert, 'close');
	defineJQueryPlugin(Alert);
	const NAME$e = 'button';
	const DATA_KEY$9 = 'bs.button';
	const EVENT_KEY$a = `.${DATA_KEY$9}`;
	const DATA_API_KEY$6 = '.data-api';
	const CLASS_NAME_ACTIVE$3 = 'active';
	const SELECTOR_DATA_TOGGLE$5 = '[data-bs-toggle="button"]';
	const EVENT_CLICK_DATA_API$6 = `click${EVENT_KEY$a}${DATA_API_KEY$6}`;
	class Button extends BaseComponent {
	  static get NAME() {
	    return NAME$e;
	  }
	  toggle() {
	    this._element.setAttribute('aria-pressed', this._element.classList.toggle(CLASS_NAME_ACTIVE$3));
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Button.getOrCreateInstance(this);
	      if (config === 'toggle') {
	        data[config]();
	      }
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API$6, SELECTOR_DATA_TOGGLE$5, event => {
	  event.preventDefault();
	  const button = event.target.closest(SELECTOR_DATA_TOGGLE$5);
	  const data = Button.getOrCreateInstance(button);
	  data.toggle();
	});
	defineJQueryPlugin(Button);
	const SelectorEngine = {
	  find(selector, element = document.documentElement) {
	    return [].concat(...Element.prototype.querySelectorAll.call(element, selector));
	  },
	  findOne(selector, element = document.documentElement) {
	    return Element.prototype.querySelector.call(element, selector);
	  },
	  children(element, selector) {
	    return [].concat(...element.children).filter(child => child.matches(selector));
	  },
	  parents(element, selector) {
	    const parents = [];
	    let ancestor = element.parentNode.closest(selector);
	    while (ancestor) {
	      parents.push(ancestor);
	      ancestor = ancestor.parentNode.closest(selector);
	    }
	    return parents;
	  },
	  prev(element, selector) {
	    let previous = element.previousElementSibling;
	    while (previous) {
	      if (previous.matches(selector)) {
	        return [previous];
	      }
	      previous = previous.previousElementSibling;
	    }
	    return [];
	  },
	  next(element, selector) {
	    let next = element.nextElementSibling;
	    while (next) {
	      if (next.matches(selector)) {
	        return [next];
	      }
	      next = next.nextElementSibling;
	    }
	    return [];
	  },
	  focusableChildren(element) {
	    const focusables = ['a', 'button', 'input', 'textarea', 'select', 'details', '[tabindex]', '[contenteditable="true"]'].map(selector => `${selector}:not([tabindex^="-"])`).join(',');
	    return this.find(focusables, element).filter(el => !isDisabled(el) && isVisible(el));
	  }
	};
	const NAME$d = 'swipe';
	const EVENT_KEY$9 = '.bs.swipe';
	const EVENT_TOUCHSTART = `touchstart${EVENT_KEY$9}`;
	const EVENT_TOUCHMOVE = `touchmove${EVENT_KEY$9}`;
	const EVENT_TOUCHEND = `touchend${EVENT_KEY$9}`;
	const EVENT_POINTERDOWN = `pointerdown${EVENT_KEY$9}`;
	const EVENT_POINTERUP = `pointerup${EVENT_KEY$9}`;
	const POINTER_TYPE_TOUCH = 'touch';
	const POINTER_TYPE_PEN = 'pen';
	const CLASS_NAME_POINTER_EVENT = 'pointer-event';
	const SWIPE_THRESHOLD = 40;
	const Default$c = {
	  endCallback: null,
	  leftCallback: null,
	  rightCallback: null
	};
	const DefaultType$c = {
	  endCallback: '(function|null)',
	  leftCallback: '(function|null)',
	  rightCallback: '(function|null)'
	};
	class Swipe extends Config {
	  constructor(element, config) {
	    super();
	    this._element = element;
	    if (!element || !Swipe.isSupported()) {
	      return;
	    }
	    this._config = this._getConfig(config);
	    this._deltaX = 0;
	    this._supportPointerEvents = Boolean(window.PointerEvent);
	    this._initEvents();
	  }
	  static get Default() {
	    return Default$c;
	  }
	  static get DefaultType() {
	    return DefaultType$c;
	  }
	  static get NAME() {
	    return NAME$d;
	  }
	  dispose() {
	    EventHandler.off(this._element, EVENT_KEY$9);
	  }
	  _start(event) {
	    if (!this._supportPointerEvents) {
	      this._deltaX = event.touches[0].clientX;
	      return;
	    }
	    if (this._eventIsPointerPenTouch(event)) {
	      this._deltaX = event.clientX;
	    }
	  }
	  _end(event) {
	    if (this._eventIsPointerPenTouch(event)) {
	      this._deltaX = event.clientX - this._deltaX;
	    }
	    this._handleSwipe();
	    execute(this._config.endCallback);
	  }
	  _move(event) {
	    this._deltaX = event.touches && event.touches.length > 1 ? 0 : event.touches[0].clientX - this._deltaX;
	  }
	  _handleSwipe() {
	    const absDeltaX = Math.abs(this._deltaX);
	    if (absDeltaX <= SWIPE_THRESHOLD) {
	      return;
	    }
	    const direction = absDeltaX / this._deltaX;
	    this._deltaX = 0;
	    if (!direction) {
	      return;
	    }
	    execute(direction > 0 ? this._config.rightCallback : this._config.leftCallback);
	  }
	  _initEvents() {
	    if (this._supportPointerEvents) {
	      EventHandler.on(this._element, EVENT_POINTERDOWN, event => this._start(event));
	      EventHandler.on(this._element, EVENT_POINTERUP, event => this._end(event));
	      this._element.classList.add(CLASS_NAME_POINTER_EVENT);
	    } else {
	      EventHandler.on(this._element, EVENT_TOUCHSTART, event => this._start(event));
	      EventHandler.on(this._element, EVENT_TOUCHMOVE, event => this._move(event));
	      EventHandler.on(this._element, EVENT_TOUCHEND, event => this._end(event));
	    }
	  }
	  _eventIsPointerPenTouch(event) {
	    return this._supportPointerEvents && (event.pointerType === POINTER_TYPE_PEN || event.pointerType === POINTER_TYPE_TOUCH);
	  }
	  static isSupported() {
	    return 'ontouchstart' in document.documentElement || navigator.maxTouchPoints > 0;
	  }
	}
	const NAME$c = 'carousel';
	const DATA_KEY$8 = 'bs.carousel';
	const EVENT_KEY$8 = `.${DATA_KEY$8}`;
	const DATA_API_KEY$5 = '.data-api';
	const ARROW_LEFT_KEY$1 = 'ArrowLeft';
	const ARROW_RIGHT_KEY$1 = 'ArrowRight';
	const TOUCHEVENT_COMPAT_WAIT = 500;
	const ORDER_NEXT = 'next';
	const ORDER_PREV = 'prev';
	const DIRECTION_LEFT = 'left';
	const DIRECTION_RIGHT = 'right';
	const EVENT_SLIDE = `slide${EVENT_KEY$8}`;
	const EVENT_SLID = `slid${EVENT_KEY$8}`;
	const EVENT_KEYDOWN$1 = `keydown${EVENT_KEY$8}`;
	const EVENT_MOUSEENTER$1 = `mouseenter${EVENT_KEY$8}`;
	const EVENT_MOUSELEAVE$1 = `mouseleave${EVENT_KEY$8}`;
	const EVENT_DRAG_START = `dragstart${EVENT_KEY$8}`;
	const EVENT_LOAD_DATA_API$3 = `load${EVENT_KEY$8}${DATA_API_KEY$5}`;
	const EVENT_CLICK_DATA_API$5 = `click${EVENT_KEY$8}${DATA_API_KEY$5}`;
	const CLASS_NAME_CAROUSEL = 'carousel';
	const CLASS_NAME_ACTIVE$2 = 'active';
	const CLASS_NAME_SLIDE = 'slide';
	const CLASS_NAME_END = 'carousel-item-end';
	const CLASS_NAME_START = 'carousel-item-start';
	const CLASS_NAME_NEXT = 'carousel-item-next';
	const CLASS_NAME_PREV = 'carousel-item-prev';
	const SELECTOR_ACTIVE = '.active';
	const SELECTOR_ITEM = '.carousel-item';
	const SELECTOR_ACTIVE_ITEM = SELECTOR_ACTIVE + SELECTOR_ITEM;
	const SELECTOR_ITEM_IMG = '.carousel-item img';
	const SELECTOR_INDICATORS = '.carousel-indicators';
	const SELECTOR_DATA_SLIDE = '[data-bs-slide], [data-bs-slide-to]';
	const SELECTOR_DATA_RIDE = '[data-bs-ride="carousel"]';
	const KEY_TO_DIRECTION = {
	  [ARROW_LEFT_KEY$1]: DIRECTION_RIGHT,
	  [ARROW_RIGHT_KEY$1]: DIRECTION_LEFT
	};
	const Default$b = {
	  interval: 5000,
	  keyboard: true,
	  pause: 'hover',
	  ride: false,
	  touch: true,
	  wrap: true
	};
	const DefaultType$b = {
	  interval: '(number|boolean)',
	  keyboard: 'boolean',
	  pause: '(string|boolean)',
	  ride: '(boolean|string)',
	  touch: 'boolean',
	  wrap: 'boolean'
	};
	class Carousel extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._interval = null;
	    this._activeElement = null;
	    this._isSliding = false;
	    this.touchTimeout = null;
	    this._swipeHelper = null;
	    this._indicatorsElement = SelectorEngine.findOne(SELECTOR_INDICATORS, this._element);
	    this._addEventListeners();
	    if (this._config.ride === CLASS_NAME_CAROUSEL) {
	      this.cycle();
	    }
	  }
	  static get Default() {
	    return Default$b;
	  }
	  static get DefaultType() {
	    return DefaultType$b;
	  }
	  static get NAME() {
	    return NAME$c;
	  }
	  next() {
	    this._slide(ORDER_NEXT);
	  }
	  nextWhenVisible() {
	    if (!document.hidden && isVisible(this._element)) {
	      this.next();
	    }
	  }
	  prev() {
	    this._slide(ORDER_PREV);
	  }
	  pause() {
	    if (this._isSliding) {
	      triggerTransitionEnd(this._element);
	    }
	    this._clearInterval();
	  }
	  cycle() {
	    this._clearInterval();
	    this._updateInterval();
	    this._interval = setInterval(() => this.nextWhenVisible(), this._config.interval);
	  }
	  _maybeEnableCycle() {
	    if (!this._config.ride) {
	      return;
	    }
	    if (this._isSliding) {
	      EventHandler.one(this._element, EVENT_SLID, () => this.cycle());
	      return;
	    }
	    this.cycle();
	  }
	  to(index) {
	    const items = this._getItems();
	    if (index > items.length - 1 || index < 0) {
	      return;
	    }
	    if (this._isSliding) {
	      EventHandler.one(this._element, EVENT_SLID, () => this.to(index));
	      return;
	    }
	    const activeIndex = this._getItemIndex(this._getActive());
	    if (activeIndex === index) {
	      return;
	    }
	    const order = index > activeIndex ? ORDER_NEXT : ORDER_PREV;
	    this._slide(order, items[index]);
	  }
	  dispose() {
	    if (this._swipeHelper) {
	      this._swipeHelper.dispose();
	    }
	    super.dispose();
	  }
	  _configAfterMerge(config) {
	    config.defaultInterval = config.interval;
	    return config;
	  }
	  _addEventListeners() {
	    if (this._config.keyboard) {
	      EventHandler.on(this._element, EVENT_KEYDOWN$1, event => this._keydown(event));
	    }
	    if (this._config.pause === 'hover') {
	      EventHandler.on(this._element, EVENT_MOUSEENTER$1, () => this.pause());
	      EventHandler.on(this._element, EVENT_MOUSELEAVE$1, () => this._maybeEnableCycle());
	    }
	    if (this._config.touch && Swipe.isSupported()) {
	      this._addTouchEventListeners();
	    }
	  }
	  _addTouchEventListeners() {
	    for (const img of SelectorEngine.find(SELECTOR_ITEM_IMG, this._element)) {
	      EventHandler.on(img, EVENT_DRAG_START, event => event.preventDefault());
	    }
	    const endCallBack = () => {
	      if (this._config.pause !== 'hover') {
	        return;
	      }
	      this.pause();
	      if (this.touchTimeout) {
	        clearTimeout(this.touchTimeout);
	      }
	      this.touchTimeout = setTimeout(() => this._maybeEnableCycle(), TOUCHEVENT_COMPAT_WAIT + this._config.interval);
	    };
	    const swipeConfig = {
	      leftCallback: () => this._slide(this._directionToOrder(DIRECTION_LEFT)),
	      rightCallback: () => this._slide(this._directionToOrder(DIRECTION_RIGHT)),
	      endCallback: endCallBack
	    };
	    this._swipeHelper = new Swipe(this._element, swipeConfig);
	  }
	  _keydown(event) {
	    if (/input|textarea/i.test(event.target.tagName)) {
	      return;
	    }
	    const direction = KEY_TO_DIRECTION[event.key];
	    if (direction) {
	      event.preventDefault();
	      this._slide(this._directionToOrder(direction));
	    }
	  }
	  _getItemIndex(element) {
	    return this._getItems().indexOf(element);
	  }
	  _setActiveIndicatorElement(index) {
	    if (!this._indicatorsElement) {
	      return;
	    }
	    const activeIndicator = SelectorEngine.findOne(SELECTOR_ACTIVE, this._indicatorsElement);
	    activeIndicator.classList.remove(CLASS_NAME_ACTIVE$2);
	    activeIndicator.removeAttribute('aria-current');
	    const newActiveIndicator = SelectorEngine.findOne(`[data-bs-slide-to="${index}"]`, this._indicatorsElement);
	    if (newActiveIndicator) {
	      newActiveIndicator.classList.add(CLASS_NAME_ACTIVE$2);
	      newActiveIndicator.setAttribute('aria-current', 'true');
	    }
	  }
	  _updateInterval() {
	    const element = this._activeElement || this._getActive();
	    if (!element) {
	      return;
	    }
	    const elementInterval = Number.parseInt(element.getAttribute('data-bs-interval'), 10);
	    this._config.interval = elementInterval || this._config.defaultInterval;
	  }
	  _slide(order, element = null) {
	    if (this._isSliding) {
	      return;
	    }
	    const activeElement = this._getActive();
	    const isNext = order === ORDER_NEXT;
	    const nextElement = element || getNextActiveElement(this._getItems(), activeElement, isNext, this._config.wrap);
	    if (nextElement === activeElement) {
	      return;
	    }
	    const nextElementIndex = this._getItemIndex(nextElement);
	    const triggerEvent = eventName => {
	      return EventHandler.trigger(this._element, eventName, {
	        relatedTarget: nextElement,
	        direction: this._orderToDirection(order),
	        from: this._getItemIndex(activeElement),
	        to: nextElementIndex
	      });
	    };
	    const slideEvent = triggerEvent(EVENT_SLIDE);
	    if (slideEvent.defaultPrevented) {
	      return;
	    }
	    if (!activeElement || !nextElement) {
	      return;
	    }
	    const isCycling = Boolean(this._interval);
	    this.pause();
	    this._isSliding = true;
	    this._setActiveIndicatorElement(nextElementIndex);
	    this._activeElement = nextElement;
	    const directionalClassName = isNext ? CLASS_NAME_START : CLASS_NAME_END;
	    const orderClassName = isNext ? CLASS_NAME_NEXT : CLASS_NAME_PREV;
	    nextElement.classList.add(orderClassName);
	    reflow(nextElement);
	    activeElement.classList.add(directionalClassName);
	    nextElement.classList.add(directionalClassName);
	    const completeCallBack = () => {
	      nextElement.classList.remove(directionalClassName, orderClassName);
	      nextElement.classList.add(CLASS_NAME_ACTIVE$2);
	      activeElement.classList.remove(CLASS_NAME_ACTIVE$2, orderClassName, directionalClassName);
	      this._isSliding = false;
	      triggerEvent(EVENT_SLID);
	    };
	    this._queueCallback(completeCallBack, activeElement, this._isAnimated());
	    if (isCycling) {
	      this.cycle();
	    }
	  }
	  _isAnimated() {
	    return this._element.classList.contains(CLASS_NAME_SLIDE);
	  }
	  _getActive() {
	    return SelectorEngine.findOne(SELECTOR_ACTIVE_ITEM, this._element);
	  }
	  _getItems() {
	    return SelectorEngine.find(SELECTOR_ITEM, this._element);
	  }
	  _clearInterval() {
	    if (this._interval) {
	      clearInterval(this._interval);
	      this._interval = null;
	    }
	  }
	  _directionToOrder(direction) {
	    if (isRTL()) {
	      return direction === DIRECTION_LEFT ? ORDER_PREV : ORDER_NEXT;
	    }
	    return direction === DIRECTION_LEFT ? ORDER_NEXT : ORDER_PREV;
	  }
	  _orderToDirection(order) {
	    if (isRTL()) {
	      return order === ORDER_PREV ? DIRECTION_LEFT : DIRECTION_RIGHT;
	    }
	    return order === ORDER_PREV ? DIRECTION_RIGHT : DIRECTION_LEFT;
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Carousel.getOrCreateInstance(this, config);
	      if (typeof config === 'number') {
	        data.to(config);
	        return;
	      }
	      if (typeof config === 'string') {
	        if (data[config] === undefined || config.startsWith('_') || config === 'constructor') {
	          throw new TypeError(`No method named "${config}"`);
	        }
	        data[config]();
	      }
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API$5, SELECTOR_DATA_SLIDE, function (event) {
	  const target = getElementFromSelector(this);
	  if (!target || !target.classList.contains(CLASS_NAME_CAROUSEL)) {
	    return;
	  }
	  event.preventDefault();
	  const carousel = Carousel.getOrCreateInstance(target);
	  const slideIndex = this.getAttribute('data-bs-slide-to');
	  if (slideIndex) {
	    carousel.to(slideIndex);
	    carousel._maybeEnableCycle();
	    return;
	  }
	  if (Manipulator.getDataAttribute(this, 'slide') === 'next') {
	    carousel.next();
	    carousel._maybeEnableCycle();
	    return;
	  }
	  carousel.prev();
	  carousel._maybeEnableCycle();
	});
	EventHandler.on(window, EVENT_LOAD_DATA_API$3, () => {
	  const carousels = SelectorEngine.find(SELECTOR_DATA_RIDE);
	  for (const carousel of carousels) {
	    Carousel.getOrCreateInstance(carousel);
	  }
	});
	defineJQueryPlugin(Carousel);
	const NAME$b = 'collapse';
	const DATA_KEY$7 = 'bs.collapse';
	const EVENT_KEY$7 = `.${DATA_KEY$7}`;
	const DATA_API_KEY$4 = '.data-api';
	const EVENT_SHOW$6 = `show${EVENT_KEY$7}`;
	const EVENT_SHOWN$6 = `shown${EVENT_KEY$7}`;
	const EVENT_HIDE$6 = `hide${EVENT_KEY$7}`;
	const EVENT_HIDDEN$6 = `hidden${EVENT_KEY$7}`;
	const EVENT_CLICK_DATA_API$4 = `click${EVENT_KEY$7}${DATA_API_KEY$4}`;
	const CLASS_NAME_SHOW$7 = 'show';
	const CLASS_NAME_COLLAPSE = 'collapse';
	const CLASS_NAME_COLLAPSING = 'collapsing';
	const CLASS_NAME_COLLAPSED = 'collapsed';
	const CLASS_NAME_DEEPER_CHILDREN = `:scope .${CLASS_NAME_COLLAPSE} .${CLASS_NAME_COLLAPSE}`;
	const CLASS_NAME_HORIZONTAL = 'collapse-horizontal';
	const WIDTH = 'width';
	const HEIGHT = 'height';
	const SELECTOR_ACTIVES = '.collapse.show, .collapse.collapsing';
	const SELECTOR_DATA_TOGGLE$4 = '[data-bs-toggle="collapse"]';
	const Default$a = {
	  parent: null,
	  toggle: true
	};
	const DefaultType$a = {
	  parent: '(null|element)',
	  toggle: 'boolean'
	};
	class Collapse extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._isTransitioning = false;
	    this._triggerArray = [];
	    const toggleList = SelectorEngine.find(SELECTOR_DATA_TOGGLE$4);
	    for (const elem of toggleList) {
	      const selector = getSelectorFromElement(elem);
	      const filterElement = SelectorEngine.find(selector).filter(foundElement => foundElement === this._element);
	      if (selector !== null && filterElement.length) {
	        this._triggerArray.push(elem);
	      }
	    }
	    this._initializeChildren();
	    if (!this._config.parent) {
	      this._addAriaAndCollapsedClass(this._triggerArray, this._isShown());
	    }
	    if (this._config.toggle) {
	      this.toggle();
	    }
	  }
	  static get Default() {
	    return Default$a;
	  }
	  static get DefaultType() {
	    return DefaultType$a;
	  }
	  static get NAME() {
	    return NAME$b;
	  }
	  toggle() {
	    if (this._isShown()) {
	      this.hide();
	    } else {
	      this.show();
	    }
	  }
	  show() {
	    if (this._isTransitioning || this._isShown()) {
	      return;
	    }
	    let activeChildren = [];
	    if (this._config.parent) {
	      activeChildren = this._getFirstLevelChildren(SELECTOR_ACTIVES).filter(element => element !== this._element).map(element => Collapse.getOrCreateInstance(element, {
	        toggle: false
	      }));
	    }
	    if (activeChildren.length && activeChildren[0]._isTransitioning) {
	      return;
	    }
	    const startEvent = EventHandler.trigger(this._element, EVENT_SHOW$6);
	    if (startEvent.defaultPrevented) {
	      return;
	    }
	    for (const activeInstance of activeChildren) {
	      activeInstance.hide();
	    }
	    const dimension = this._getDimension();
	    this._element.classList.remove(CLASS_NAME_COLLAPSE);
	    this._element.classList.add(CLASS_NAME_COLLAPSING);
	    this._element.style[dimension] = 0;
	    this._addAriaAndCollapsedClass(this._triggerArray, true);
	    this._isTransitioning = true;
	    const complete = () => {
	      this._isTransitioning = false;
	      this._element.classList.remove(CLASS_NAME_COLLAPSING);
	      this._element.classList.add(CLASS_NAME_COLLAPSE, CLASS_NAME_SHOW$7);
	      this._element.style[dimension] = '';
	      EventHandler.trigger(this._element, EVENT_SHOWN$6);
	    };
	    const capitalizedDimension = dimension[0].toUpperCase() + dimension.slice(1);
	    const scrollSize = `scroll${capitalizedDimension}`;
	    this._queueCallback(complete, this._element, true);
	    this._element.style[dimension] = `${this._element[scrollSize]}px`;
	  }
	  hide() {
	    if (this._isTransitioning || !this._isShown()) {
	      return;
	    }
	    const startEvent = EventHandler.trigger(this._element, EVENT_HIDE$6);
	    if (startEvent.defaultPrevented) {
	      return;
	    }
	    const dimension = this._getDimension();
	    this._element.style[dimension] = `${this._element.getBoundingClientRect()[dimension]}px`;
	    reflow(this._element);
	    this._element.classList.add(CLASS_NAME_COLLAPSING);
	    this._element.classList.remove(CLASS_NAME_COLLAPSE, CLASS_NAME_SHOW$7);
	    for (const trigger of this._triggerArray) {
	      const element = getElementFromSelector(trigger);
	      if (element && !this._isShown(element)) {
	        this._addAriaAndCollapsedClass([trigger], false);
	      }
	    }
	    this._isTransitioning = true;
	    const complete = () => {
	      this._isTransitioning = false;
	      this._element.classList.remove(CLASS_NAME_COLLAPSING);
	      this._element.classList.add(CLASS_NAME_COLLAPSE);
	      EventHandler.trigger(this._element, EVENT_HIDDEN$6);
	    };
	    this._element.style[dimension] = '';
	    this._queueCallback(complete, this._element, true);
	  }
	  _isShown(element = this._element) {
	    return element.classList.contains(CLASS_NAME_SHOW$7);
	  }
	  _configAfterMerge(config) {
	    config.toggle = Boolean(config.toggle);
	    config.parent = getElement(config.parent);
	    return config;
	  }
	  _getDimension() {
	    return this._element.classList.contains(CLASS_NAME_HORIZONTAL) ? WIDTH : HEIGHT;
	  }
	  _initializeChildren() {
	    if (!this._config.parent) {
	      return;
	    }
	    const children = this._getFirstLevelChildren(SELECTOR_DATA_TOGGLE$4);
	    for (const element of children) {
	      const selected = getElementFromSelector(element);
	      if (selected) {
	        this._addAriaAndCollapsedClass([element], this._isShown(selected));
	      }
	    }
	  }
	  _getFirstLevelChildren(selector) {
	    const children = SelectorEngine.find(CLASS_NAME_DEEPER_CHILDREN, this._config.parent);
	    return SelectorEngine.find(selector, this._config.parent).filter(element => !children.includes(element));
	  }
	  _addAriaAndCollapsedClass(triggerArray, isOpen) {
	    if (!triggerArray.length) {
	      return;
	    }
	    for (const element of triggerArray) {
	      element.classList.toggle(CLASS_NAME_COLLAPSED, !isOpen);
	      element.setAttribute('aria-expanded', isOpen);
	    }
	  }
	  static jQueryInterface(config) {
	    const _config = {};
	    if (typeof config === 'string' && /show|hide/.test(config)) {
	      _config.toggle = false;
	    }
	    return this.each(function () {
	      const data = Collapse.getOrCreateInstance(this, _config);
	      if (typeof config === 'string') {
	        if (typeof data[config] === 'undefined') {
	          throw new TypeError(`No method named "${config}"`);
	        }
	        data[config]();
	      }
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API$4, SELECTOR_DATA_TOGGLE$4, function (event) {
	  if (event.target.tagName === 'A' || event.delegateTarget && event.delegateTarget.tagName === 'A') {
	    event.preventDefault();
	  }
	  const selector = getSelectorFromElement(this);
	  const selectorElements = SelectorEngine.find(selector);
	  for (const element of selectorElements) {
	    Collapse.getOrCreateInstance(element, {
	      toggle: false
	    }).toggle();
	  }
	});
	defineJQueryPlugin(Collapse);
	const NAME$a = 'dropdown';
	const DATA_KEY$6 = 'bs.dropdown';
	const EVENT_KEY$6 = `.${DATA_KEY$6}`;
	const DATA_API_KEY$3 = '.data-api';
	const ESCAPE_KEY$2 = 'Escape';
	const TAB_KEY$1 = 'Tab';
	const ARROW_UP_KEY$1 = 'ArrowUp';
	const ARROW_DOWN_KEY$1 = 'ArrowDown';
	const RIGHT_MOUSE_BUTTON = 2;
	const EVENT_HIDE$5 = `hide${EVENT_KEY$6}`;
	const EVENT_HIDDEN$5 = `hidden${EVENT_KEY$6}`;
	const EVENT_SHOW$5 = `show${EVENT_KEY$6}`;
	const EVENT_SHOWN$5 = `shown${EVENT_KEY$6}`;
	const EVENT_CLICK_DATA_API$3 = `click${EVENT_KEY$6}${DATA_API_KEY$3}`;
	const EVENT_KEYDOWN_DATA_API = `keydown${EVENT_KEY$6}${DATA_API_KEY$3}`;
	const EVENT_KEYUP_DATA_API = `keyup${EVENT_KEY$6}${DATA_API_KEY$3}`;
	const CLASS_NAME_SHOW$6 = 'show';
	const CLASS_NAME_DROPUP = 'dropup';
	const CLASS_NAME_DROPEND = 'dropend';
	const CLASS_NAME_DROPSTART = 'dropstart';
	const CLASS_NAME_DROPUP_CENTER = 'dropup-center';
	const CLASS_NAME_DROPDOWN_CENTER = 'dropdown-center';
	const SELECTOR_DATA_TOGGLE$3 = '[data-bs-toggle="dropdown"]:not(.disabled):not(:disabled)';
	const SELECTOR_DATA_TOGGLE_SHOWN = `${SELECTOR_DATA_TOGGLE$3}.${CLASS_NAME_SHOW$6}`;
	const SELECTOR_MENU = '.dropdown-menu';
	const SELECTOR_NAVBAR = '.navbar';
	const SELECTOR_NAVBAR_NAV = '.navbar-nav';
	const SELECTOR_VISIBLE_ITEMS = '.dropdown-menu .dropdown-item:not(.disabled):not(:disabled)';
	const PLACEMENT_TOP = isRTL() ? 'top-end' : 'top-start';
	const PLACEMENT_TOPEND = isRTL() ? 'top-start' : 'top-end';
	const PLACEMENT_BOTTOM = isRTL() ? 'bottom-end' : 'bottom-start';
	const PLACEMENT_BOTTOMEND = isRTL() ? 'bottom-start' : 'bottom-end';
	const PLACEMENT_RIGHT = isRTL() ? 'left-start' : 'right-start';
	const PLACEMENT_LEFT = isRTL() ? 'right-start' : 'left-start';
	const PLACEMENT_TOPCENTER = 'top';
	const PLACEMENT_BOTTOMCENTER = 'bottom';
	const Default$9 = {
	  autoClose: true,
	  boundary: 'clippingParents',
	  display: 'dynamic',
	  offset: [0, 2],
	  popperConfig: null,
	  reference: 'toggle'
	};
	const DefaultType$9 = {
	  autoClose: '(boolean|string)',
	  boundary: '(string|element)',
	  display: 'string',
	  offset: '(array|string|function)',
	  popperConfig: '(null|object|function)',
	  reference: '(string|element|object)'
	};
	class Dropdown extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._popper = null;
	    this._parent = this._element.parentNode;
	    this._menu = SelectorEngine.next(this._element, SELECTOR_MENU)[0] || SelectorEngine.prev(this._element, SELECTOR_MENU)[0] || SelectorEngine.findOne(SELECTOR_MENU, this._parent);
	    this._inNavbar = this._detectNavbar();
	  }
	  static get Default() {
	    return Default$9;
	  }
	  static get DefaultType() {
	    return DefaultType$9;
	  }
	  static get NAME() {
	    return NAME$a;
	  }
	  toggle() {
	    return this._isShown() ? this.hide() : this.show();
	  }
	  show() {
	    if (isDisabled(this._element) || this._isShown()) {
	      return;
	    }
	    const relatedTarget = {
	      relatedTarget: this._element
	    };
	    const showEvent = EventHandler.trigger(this._element, EVENT_SHOW$5, relatedTarget);
	    if (showEvent.defaultPrevented) {
	      return;
	    }
	    this._createPopper();
	    if ('ontouchstart' in document.documentElement && !this._parent.closest(SELECTOR_NAVBAR_NAV)) {
	      for (const element of [].concat(...document.body.children)) {
	        EventHandler.on(element, 'mouseover', noop);
	      }
	    }
	    this._element.focus();
	    this._element.setAttribute('aria-expanded', true);
	    this._menu.classList.add(CLASS_NAME_SHOW$6);
	    this._element.classList.add(CLASS_NAME_SHOW$6);
	    EventHandler.trigger(this._element, EVENT_SHOWN$5, relatedTarget);
	  }
	  hide() {
	    if (isDisabled(this._element) || !this._isShown()) {
	      return;
	    }
	    const relatedTarget = {
	      relatedTarget: this._element
	    };
	    this._completeHide(relatedTarget);
	  }
	  dispose() {
	    if (this._popper) {
	      this._popper.destroy();
	    }
	    super.dispose();
	  }
	  update() {
	    this._inNavbar = this._detectNavbar();
	    if (this._popper) {
	      this._popper.update();
	    }
	  }
	  _completeHide(relatedTarget) {
	    const hideEvent = EventHandler.trigger(this._element, EVENT_HIDE$5, relatedTarget);
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    if ('ontouchstart' in document.documentElement) {
	      for (const element of [].concat(...document.body.children)) {
	        EventHandler.off(element, 'mouseover', noop);
	      }
	    }
	    if (this._popper) {
	      this._popper.destroy();
	    }
	    this._menu.classList.remove(CLASS_NAME_SHOW$6);
	    this._element.classList.remove(CLASS_NAME_SHOW$6);
	    this._element.setAttribute('aria-expanded', 'false');
	    Manipulator.removeDataAttribute(this._menu, 'popper');
	    EventHandler.trigger(this._element, EVENT_HIDDEN$5, relatedTarget);
	  }
	  _getConfig(config) {
	    config = super._getConfig(config);
	    if (typeof config.reference === 'object' && !isElement(config.reference) && typeof config.reference.getBoundingClientRect !== 'function') {
	      throw new TypeError(`${NAME$a.toUpperCase()}: Option "reference" provided type "object" without a required "getBoundingClientRect" method.`);
	    }
	    return config;
	  }
	  _createPopper() {
	    if (typeof Popper === 'undefined') {
	      throw new TypeError('Bootstrap\'s dropdowns require Popper (https://popper.js.org)');
	    }
	    let referenceElement = this._element;
	    if (this._config.reference === 'parent') {
	      referenceElement = this._parent;
	    } else if (isElement(this._config.reference)) {
	      referenceElement = getElement(this._config.reference);
	    } else if (typeof this._config.reference === 'object') {
	      referenceElement = this._config.reference;
	    }
	    const popperConfig = this._getPopperConfig();
	    this._popper = createPopper(referenceElement, this._menu, popperConfig);
	  }
	  _isShown() {
	    return this._menu.classList.contains(CLASS_NAME_SHOW$6);
	  }
	  _getPlacement() {
	    const parentDropdown = this._parent;
	    if (parentDropdown.classList.contains(CLASS_NAME_DROPEND)) {
	      return PLACEMENT_RIGHT;
	    }
	    if (parentDropdown.classList.contains(CLASS_NAME_DROPSTART)) {
	      return PLACEMENT_LEFT;
	    }
	    if (parentDropdown.classList.contains(CLASS_NAME_DROPUP_CENTER)) {
	      return PLACEMENT_TOPCENTER;
	    }
	    if (parentDropdown.classList.contains(CLASS_NAME_DROPDOWN_CENTER)) {
	      return PLACEMENT_BOTTOMCENTER;
	    }
	    const isEnd = getComputedStyle(this._menu).getPropertyValue('--bs-position').trim() === 'end';
	    if (parentDropdown.classList.contains(CLASS_NAME_DROPUP)) {
	      return isEnd ? PLACEMENT_TOPEND : PLACEMENT_TOP;
	    }
	    return isEnd ? PLACEMENT_BOTTOMEND : PLACEMENT_BOTTOM;
	  }
	  _detectNavbar() {
	    return this._element.closest(SELECTOR_NAVBAR) !== null;
	  }
	  _getOffset() {
	    const {
	      offset
	    } = this._config;
	    if (typeof offset === 'string') {
	      return offset.split(',').map(value => Number.parseInt(value, 10));
	    }
	    if (typeof offset === 'function') {
	      return popperData => offset(popperData, this._element);
	    }
	    return offset;
	  }
	  _getPopperConfig() {
	    const defaultBsPopperConfig = {
	      placement: this._getPlacement(),
	      modifiers: [{
	        name: 'preventOverflow',
	        options: {
	          boundary: this._config.boundary
	        }
	      }, {
	        name: 'offset',
	        options: {
	          offset: this._getOffset()
	        }
	      }]
	    };
	    if (this._inNavbar || this._config.display === 'static') {
	      Manipulator.setDataAttribute(this._menu, 'popper', 'static');
	      defaultBsPopperConfig.modifiers = [{
	        name: 'applyStyles',
	        enabled: false
	      }];
	    }
	    return { ...defaultBsPopperConfig,
	      ...(typeof this._config.popperConfig === 'function' ? this._config.popperConfig(defaultBsPopperConfig) : this._config.popperConfig)
	    };
	  }
	  _selectMenuItem({
	    key,
	    target
	  }) {
	    const items = SelectorEngine.find(SELECTOR_VISIBLE_ITEMS, this._menu).filter(element => isVisible(element));
	    if (!items.length) {
	      return;
	    }
	    getNextActiveElement(items, target, key === ARROW_DOWN_KEY$1, !items.includes(target)).focus();
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Dropdown.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (typeof data[config] === 'undefined') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config]();
	    });
	  }
	  static clearMenus(event) {
	    if (event.button === RIGHT_MOUSE_BUTTON || event.type === 'keyup' && event.key !== TAB_KEY$1) {
	      return;
	    }
	    const openToggles = SelectorEngine.find(SELECTOR_DATA_TOGGLE_SHOWN);
	    for (const toggle of openToggles) {
	      const context = Dropdown.getInstance(toggle);
	      if (!context || context._config.autoClose === false) {
	        continue;
	      }
	      const composedPath = event.composedPath();
	      const isMenuTarget = composedPath.includes(context._menu);
	      if (composedPath.includes(context._element) || context._config.autoClose === 'inside' && !isMenuTarget || context._config.autoClose === 'outside' && isMenuTarget) {
	        continue;
	      }
	      if (context._menu.contains(event.target) && (event.type === 'keyup' && event.key === TAB_KEY$1 || /input|select|option|textarea|form/i.test(event.target.tagName))) {
	        continue;
	      }
	      const relatedTarget = {
	        relatedTarget: context._element
	      };
	      if (event.type === 'click') {
	        relatedTarget.clickEvent = event;
	      }
	      context._completeHide(relatedTarget);
	    }
	  }
	  static dataApiKeydownHandler(event) {
	    const isInput = /input|textarea/i.test(event.target.tagName);
	    const isEscapeEvent = event.key === ESCAPE_KEY$2;
	    const isUpOrDownEvent = [ARROW_UP_KEY$1, ARROW_DOWN_KEY$1].includes(event.key);
	    if (!isUpOrDownEvent && !isEscapeEvent) {
	      return;
	    }
	    if (isInput && !isEscapeEvent) {
	      return;
	    }
	    event.preventDefault();
	    const getToggleButton = this.matches(SELECTOR_DATA_TOGGLE$3) ? this : SelectorEngine.prev(this, SELECTOR_DATA_TOGGLE$3)[0] || SelectorEngine.next(this, SELECTOR_DATA_TOGGLE$3)[0] || SelectorEngine.findOne(SELECTOR_DATA_TOGGLE$3, event.delegateTarget.parentNode);
	    const instance = Dropdown.getOrCreateInstance(getToggleButton);
	    if (isUpOrDownEvent) {
	      event.stopPropagation();
	      instance.show();
	      instance._selectMenuItem(event);
	      return;
	    }
	    if (instance._isShown()) {
	      event.stopPropagation();
	      instance.hide();
	      getToggleButton.focus();
	    }
	  }
	}
	EventHandler.on(document, EVENT_KEYDOWN_DATA_API, SELECTOR_DATA_TOGGLE$3, Dropdown.dataApiKeydownHandler);
	EventHandler.on(document, EVENT_KEYDOWN_DATA_API, SELECTOR_MENU, Dropdown.dataApiKeydownHandler);
	EventHandler.on(document, EVENT_CLICK_DATA_API$3, Dropdown.clearMenus);
	EventHandler.on(document, EVENT_KEYUP_DATA_API, Dropdown.clearMenus);
	EventHandler.on(document, EVENT_CLICK_DATA_API$3, SELECTOR_DATA_TOGGLE$3, function (event) {
	  event.preventDefault();
	  Dropdown.getOrCreateInstance(this).toggle();
	});
	defineJQueryPlugin(Dropdown);
	const SELECTOR_FIXED_CONTENT = '.fixed-top, .fixed-bottom, .is-fixed, .sticky-top';
	const SELECTOR_STICKY_CONTENT = '.sticky-top';
	const PROPERTY_PADDING = 'padding-right';
	const PROPERTY_MARGIN = 'margin-right';
	class ScrollBarHelper {
	  constructor() {
	    this._element = document.body;
	  }
	  getWidth() {
	    const documentWidth = document.documentElement.clientWidth;
	    return Math.abs(window.innerWidth - documentWidth);
	  }
	  hide() {
	    const width = this.getWidth();
	    this._disableOverFlow();
	    this._setElementAttributes(this._element, PROPERTY_PADDING, calculatedValue => calculatedValue + width);
	    this._setElementAttributes(SELECTOR_FIXED_CONTENT, PROPERTY_PADDING, calculatedValue => calculatedValue + width);
	    this._setElementAttributes(SELECTOR_STICKY_CONTENT, PROPERTY_MARGIN, calculatedValue => calculatedValue - width);
	  }
	  reset() {
	    this._resetElementAttributes(this._element, 'overflow');
	    this._resetElementAttributes(this._element, PROPERTY_PADDING);
	    this._resetElementAttributes(SELECTOR_FIXED_CONTENT, PROPERTY_PADDING);
	    this._resetElementAttributes(SELECTOR_STICKY_CONTENT, PROPERTY_MARGIN);
	  }
	  isOverflowing() {
	    return this.getWidth() > 0;
	  }
	  _disableOverFlow() {
	    this._saveInitialAttribute(this._element, 'overflow');
	    this._element.style.overflow = 'hidden';
	  }
	  _setElementAttributes(selector, styleProperty, callback) {
	    const scrollbarWidth = this.getWidth();
	    const manipulationCallBack = element => {
	      if (element !== this._element && window.innerWidth > element.clientWidth + scrollbarWidth) {
	        return;
	      }
	      this._saveInitialAttribute(element, styleProperty);
	      const calculatedValue = window.getComputedStyle(element).getPropertyValue(styleProperty);
	      element.style.setProperty(styleProperty, `${callback(Number.parseFloat(calculatedValue))}px`);
	    };
	    this._applyManipulationCallback(selector, manipulationCallBack);
	  }
	  _saveInitialAttribute(element, styleProperty) {
	    const actualValue = element.style.getPropertyValue(styleProperty);
	    if (actualValue) {
	      Manipulator.setDataAttribute(element, styleProperty, actualValue);
	    }
	  }
	  _resetElementAttributes(selector, styleProperty) {
	    const manipulationCallBack = element => {
	      const value = Manipulator.getDataAttribute(element, styleProperty);
	      if (value === null) {
	        element.style.removeProperty(styleProperty);
	        return;
	      }
	      Manipulator.removeDataAttribute(element, styleProperty);
	      element.style.setProperty(styleProperty, value);
	    };
	    this._applyManipulationCallback(selector, manipulationCallBack);
	  }
	  _applyManipulationCallback(selector, callBack) {
	    if (isElement(selector)) {
	      callBack(selector);
	      return;
	    }
	    for (const sel of SelectorEngine.find(selector, this._element)) {
	      callBack(sel);
	    }
	  }
	}
	const NAME$9 = 'backdrop';
	const CLASS_NAME_FADE$4 = 'fade';
	const CLASS_NAME_SHOW$5 = 'show';
	const EVENT_MOUSEDOWN = `mousedown.bs.${NAME$9}`;
	const Default$8 = {
	  className: 'modal-backdrop',
	  clickCallback: null,
	  isAnimated: false,
	  isVisible: true,
	  rootElement: 'body'
	};
	const DefaultType$8 = {
	  className: 'string',
	  clickCallback: '(function|null)',
	  isAnimated: 'boolean',
	  isVisible: 'boolean',
	  rootElement: '(element|string)'
	};
	class Backdrop extends Config {
	  constructor(config) {
	    super();
	    this._config = this._getConfig(config);
	    this._isAppended = false;
	    this._element = null;
	  }
	  static get Default() {
	    return Default$8;
	  }
	  static get DefaultType() {
	    return DefaultType$8;
	  }
	  static get NAME() {
	    return NAME$9;
	  }
	  show(callback) {
	    if (!this._config.isVisible) {
	      execute(callback);
	      return;
	    }
	    this._append();
	    const element = this._getElement();
	    if (this._config.isAnimated) {
	      reflow(element);
	    }
	    element.classList.add(CLASS_NAME_SHOW$5);
	    this._emulateAnimation(() => {
	      execute(callback);
	    });
	  }
	  hide(callback) {
	    if (!this._config.isVisible) {
	      execute(callback);
	      return;
	    }
	    this._getElement().classList.remove(CLASS_NAME_SHOW$5);
	    this._emulateAnimation(() => {
	      this.dispose();
	      execute(callback);
	    });
	  }
	  dispose() {
	    if (!this._isAppended) {
	      return;
	    }
	    EventHandler.off(this._element, EVENT_MOUSEDOWN);
	    this._element.remove();
	    this._isAppended = false;
	  }
	  _getElement() {
	    if (!this._element) {
	      const backdrop = document.createElement('div');
	      backdrop.className = this._config.className;
	      if (this._config.isAnimated) {
	        backdrop.classList.add(CLASS_NAME_FADE$4);
	      }
	      this._element = backdrop;
	    }
	    return this._element;
	  }
	  _configAfterMerge(config) {
	    config.rootElement = getElement(config.rootElement);
	    return config;
	  }
	  _append() {
	    if (this._isAppended) {
	      return;
	    }
	    const element = this._getElement();
	    this._config.rootElement.append(element);
	    EventHandler.on(element, EVENT_MOUSEDOWN, () => {
	      execute(this._config.clickCallback);
	    });
	    this._isAppended = true;
	  }
	  _emulateAnimation(callback) {
	    executeAfterTransition(callback, this._getElement(), this._config.isAnimated);
	  }
	}
	const NAME$8 = 'focustrap';
	const DATA_KEY$5 = 'bs.focustrap';
	const EVENT_KEY$5 = `.${DATA_KEY$5}`;
	const EVENT_FOCUSIN$2 = `focusin${EVENT_KEY$5}`;
	const EVENT_KEYDOWN_TAB = `keydown.tab${EVENT_KEY$5}`;
	const TAB_KEY = 'Tab';
	const TAB_NAV_FORWARD = 'forward';
	const TAB_NAV_BACKWARD = 'backward';
	const Default$7 = {
	  autofocus: true,
	  trapElement: null
	};
	const DefaultType$7 = {
	  autofocus: 'boolean',
	  trapElement: 'element'
	};
	class FocusTrap extends Config {
	  constructor(config) {
	    super();
	    this._config = this._getConfig(config);
	    this._isActive = false;
	    this._lastTabNavDirection = null;
	  }
	  static get Default() {
	    return Default$7;
	  }
	  static get DefaultType() {
	    return DefaultType$7;
	  }
	  static get NAME() {
	    return NAME$8;
	  }
	  activate() {
	    if (this._isActive) {
	      return;
	    }
	    if (this._config.autofocus) {
	      this._config.trapElement.focus();
	    }
	    EventHandler.off(document, EVENT_KEY$5);
	    EventHandler.on(document, EVENT_FOCUSIN$2, event => this._handleFocusin(event));
	    EventHandler.on(document, EVENT_KEYDOWN_TAB, event => this._handleKeydown(event));
	    this._isActive = true;
	  }
	  deactivate() {
	    if (!this._isActive) {
	      return;
	    }
	    this._isActive = false;
	    EventHandler.off(document, EVENT_KEY$5);
	  }
	  _handleFocusin(event) {
	    const {
	      trapElement
	    } = this._config;
	    if (event.target === document || event.target === trapElement || trapElement.contains(event.target)) {
	      return;
	    }
	    const elements = SelectorEngine.focusableChildren(trapElement);
	    if (elements.length === 0) {
	      trapElement.focus();
	    } else if (this._lastTabNavDirection === TAB_NAV_BACKWARD) {
	      elements[elements.length - 1].focus();
	    } else {
	      elements[0].focus();
	    }
	  }
	  _handleKeydown(event) {
	    if (event.key !== TAB_KEY) {
	      return;
	    }
	    this._lastTabNavDirection = event.shiftKey ? TAB_NAV_BACKWARD : TAB_NAV_FORWARD;
	  }
	}
	const NAME$7 = 'modal';
	const DATA_KEY$4 = 'bs.modal';
	const EVENT_KEY$4 = `.${DATA_KEY$4}`;
	const DATA_API_KEY$2 = '.data-api';
	const ESCAPE_KEY$1 = 'Escape';
	const EVENT_HIDE$4 = `hide${EVENT_KEY$4}`;
	const EVENT_HIDE_PREVENTED$1 = `hidePrevented${EVENT_KEY$4}`;
	const EVENT_HIDDEN$4 = `hidden${EVENT_KEY$4}`;
	const EVENT_SHOW$4 = `show${EVENT_KEY$4}`;
	const EVENT_SHOWN$4 = `shown${EVENT_KEY$4}`;
	const EVENT_RESIZE$1 = `resize${EVENT_KEY$4}`;
	const EVENT_CLICK_DISMISS = `click.dismiss${EVENT_KEY$4}`;
	const EVENT_MOUSEDOWN_DISMISS = `mousedown.dismiss${EVENT_KEY$4}`;
	const EVENT_KEYDOWN_DISMISS$1 = `keydown.dismiss${EVENT_KEY$4}`;
	const EVENT_CLICK_DATA_API$2 = `click${EVENT_KEY$4}${DATA_API_KEY$2}`;
	const CLASS_NAME_OPEN = 'modal-open';
	const CLASS_NAME_FADE$3 = 'fade';
	const CLASS_NAME_SHOW$4 = 'show';
	const CLASS_NAME_STATIC = 'modal-static';
	const OPEN_SELECTOR$1 = '.modal.show';
	const SELECTOR_DIALOG = '.modal-dialog';
	const SELECTOR_MODAL_BODY = '.modal-body';
	const SELECTOR_DATA_TOGGLE$2 = '[data-bs-toggle="modal"]';
	const Default$6 = {
	  backdrop: true,
	  focus: true,
	  keyboard: true
	};
	const DefaultType$6 = {
	  backdrop: '(boolean|string)',
	  focus: 'boolean',
	  keyboard: 'boolean'
	};
	class Modal extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._dialog = SelectorEngine.findOne(SELECTOR_DIALOG, this._element);
	    this._backdrop = this._initializeBackDrop();
	    this._focustrap = this._initializeFocusTrap();
	    this._isShown = false;
	    this._isTransitioning = false;
	    this._scrollBar = new ScrollBarHelper();
	    this._addEventListeners();
	  }
	  static get Default() {
	    return Default$6;
	  }
	  static get DefaultType() {
	    return DefaultType$6;
	  }
	  static get NAME() {
	    return NAME$7;
	  }
	  toggle(relatedTarget) {
	    return this._isShown ? this.hide() : this.show(relatedTarget);
	  }
	  show(relatedTarget) {
	    if (this._isShown || this._isTransitioning) {
	      return;
	    }
	    const showEvent = EventHandler.trigger(this._element, EVENT_SHOW$4, {
	      relatedTarget
	    });
	    if (showEvent.defaultPrevented) {
	      return;
	    }
	    this._isShown = true;
	    this._isTransitioning = true;
	    this._scrollBar.hide();
	    document.body.classList.add(CLASS_NAME_OPEN);
	    this._adjustDialog();
	    this._backdrop.show(() => this._showElement(relatedTarget));
	  }
	  hide() {
	    if (!this._isShown || this._isTransitioning) {
	      return;
	    }
	    const hideEvent = EventHandler.trigger(this._element, EVENT_HIDE$4);
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    this._isShown = false;
	    this._isTransitioning = true;
	    this._focustrap.deactivate();
	    this._element.classList.remove(CLASS_NAME_SHOW$4);
	    this._queueCallback(() => this._hideModal(), this._element, this._isAnimated());
	  }
	  dispose() {
	    for (const htmlElement of [window, this._dialog]) {
	      EventHandler.off(htmlElement, EVENT_KEY$4);
	    }
	    this._backdrop.dispose();
	    this._focustrap.deactivate();
	    super.dispose();
	  }
	  handleUpdate() {
	    this._adjustDialog();
	  }
	  _initializeBackDrop() {
	    return new Backdrop({
	      isVisible: Boolean(this._config.backdrop),
	      isAnimated: this._isAnimated()
	    });
	  }
	  _initializeFocusTrap() {
	    return new FocusTrap({
	      trapElement: this._element
	    });
	  }
	  _showElement(relatedTarget) {
	    if (!document.body.contains(this._element)) {
	      document.body.append(this._element);
	    }
	    this._element.style.display = 'block';
	    this._element.removeAttribute('aria-hidden');
	    this._element.setAttribute('aria-modal', true);
	    this._element.setAttribute('role', 'dialog');
	    this._element.scrollTop = 0;
	    const modalBody = SelectorEngine.findOne(SELECTOR_MODAL_BODY, this._dialog);
	    if (modalBody) {
	      modalBody.scrollTop = 0;
	    }
	    reflow(this._element);
	    this._element.classList.add(CLASS_NAME_SHOW$4);
	    const transitionComplete = () => {
	      if (this._config.focus) {
	        this._focustrap.activate();
	      }
	      this._isTransitioning = false;
	      EventHandler.trigger(this._element, EVENT_SHOWN$4, {
	        relatedTarget
	      });
	    };
	    this._queueCallback(transitionComplete, this._dialog, this._isAnimated());
	  }
	  _addEventListeners() {
	    EventHandler.on(this._element, EVENT_KEYDOWN_DISMISS$1, event => {
	      if (event.key !== ESCAPE_KEY$1) {
	        return;
	      }
	      if (this._config.keyboard) {
	        event.preventDefault();
	        this.hide();
	        return;
	      }
	      this._triggerBackdropTransition();
	    });
	    EventHandler.on(window, EVENT_RESIZE$1, () => {
	      if (this._isShown && !this._isTransitioning) {
	        this._adjustDialog();
	      }
	    });
	    EventHandler.on(this._element, EVENT_MOUSEDOWN_DISMISS, event => {
	      EventHandler.one(this._element, EVENT_CLICK_DISMISS, event2 => {
	        if (this._element !== event.target || this._element !== event2.target) {
	          return;
	        }
	        if (this._config.backdrop === 'static') {
	          this._triggerBackdropTransition();
	          return;
	        }
	        if (this._config.backdrop) {
	          this.hide();
	        }
	      });
	    });
	  }
	  _hideModal() {
	    this._element.style.display = 'none';
	    this._element.setAttribute('aria-hidden', true);
	    this._element.removeAttribute('aria-modal');
	    this._element.removeAttribute('role');
	    this._isTransitioning = false;
	    this._backdrop.hide(() => {
	      document.body.classList.remove(CLASS_NAME_OPEN);
	      this._resetAdjustments();
	      this._scrollBar.reset();
	      EventHandler.trigger(this._element, EVENT_HIDDEN$4);
	    });
	  }
	  _isAnimated() {
	    return this._element.classList.contains(CLASS_NAME_FADE$3);
	  }
	  _triggerBackdropTransition() {
	    const hideEvent = EventHandler.trigger(this._element, EVENT_HIDE_PREVENTED$1);
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    const isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;
	    const initialOverflowY = this._element.style.overflowY;
	    if (initialOverflowY === 'hidden' || this._element.classList.contains(CLASS_NAME_STATIC)) {
	      return;
	    }
	    if (!isModalOverflowing) {
	      this._element.style.overflowY = 'hidden';
	    }
	    this._element.classList.add(CLASS_NAME_STATIC);
	    this._queueCallback(() => {
	      this._element.classList.remove(CLASS_NAME_STATIC);
	      this._queueCallback(() => {
	        this._element.style.overflowY = initialOverflowY;
	      }, this._dialog);
	    }, this._dialog);
	    this._element.focus();
	  }
	  _adjustDialog() {
	    const isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;
	    const scrollbarWidth = this._scrollBar.getWidth();
	    const isBodyOverflowing = scrollbarWidth > 0;
	    if (isBodyOverflowing && !isModalOverflowing) {
	      const property = isRTL() ? 'paddingLeft' : 'paddingRight';
	      this._element.style[property] = `${scrollbarWidth}px`;
	    }
	    if (!isBodyOverflowing && isModalOverflowing) {
	      const property = isRTL() ? 'paddingRight' : 'paddingLeft';
	      this._element.style[property] = `${scrollbarWidth}px`;
	    }
	  }
	  _resetAdjustments() {
	    this._element.style.paddingLeft = '';
	    this._element.style.paddingRight = '';
	  }
	  static jQueryInterface(config, relatedTarget) {
	    return this.each(function () {
	      const data = Modal.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (typeof data[config] === 'undefined') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config](relatedTarget);
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API$2, SELECTOR_DATA_TOGGLE$2, function (event) {
	  const target = getElementFromSelector(this);
	  if (['A', 'AREA'].includes(this.tagName)) {
	    event.preventDefault();
	  }
	  EventHandler.one(target, EVENT_SHOW$4, showEvent => {
	    if (showEvent.defaultPrevented) {
	      return;
	    }
	    EventHandler.one(target, EVENT_HIDDEN$4, () => {
	      if (isVisible(this)) {
	        this.focus();
	      }
	    });
	  });
	  const alreadyOpen = SelectorEngine.findOne(OPEN_SELECTOR$1);
	  if (alreadyOpen) {
	    Modal.getInstance(alreadyOpen).hide();
	  }
	  const data = Modal.getOrCreateInstance(target);
	  data.toggle(this);
	});
	enableDismissTrigger(Modal);
	defineJQueryPlugin(Modal);
	const NAME$6 = 'offcanvas';
	const DATA_KEY$3 = 'bs.offcanvas';
	const EVENT_KEY$3 = `.${DATA_KEY$3}`;
	const DATA_API_KEY$1 = '.data-api';
	const EVENT_LOAD_DATA_API$2 = `load${EVENT_KEY$3}${DATA_API_KEY$1}`;
	const ESCAPE_KEY = 'Escape';
	const CLASS_NAME_SHOW$3 = 'show';
	const CLASS_NAME_SHOWING$1 = 'showing';
	const CLASS_NAME_HIDING = 'hiding';
	const CLASS_NAME_BACKDROP = 'offcanvas-backdrop';
	const OPEN_SELECTOR = '.offcanvas.show';
	const EVENT_SHOW$3 = `show${EVENT_KEY$3}`;
	const EVENT_SHOWN$3 = `shown${EVENT_KEY$3}`;
	const EVENT_HIDE$3 = `hide${EVENT_KEY$3}`;
	const EVENT_HIDE_PREVENTED = `hidePrevented${EVENT_KEY$3}`;
	const EVENT_HIDDEN$3 = `hidden${EVENT_KEY$3}`;
	const EVENT_RESIZE = `resize${EVENT_KEY$3}`;
	const EVENT_CLICK_DATA_API$1 = `click${EVENT_KEY$3}${DATA_API_KEY$1}`;
	const EVENT_KEYDOWN_DISMISS = `keydown.dismiss${EVENT_KEY$3}`;
	const SELECTOR_DATA_TOGGLE$1 = '[data-bs-toggle="offcanvas"]';
	const Default$5 = {
	  backdrop: true,
	  keyboard: true,
	  scroll: false
	};
	const DefaultType$5 = {
	  backdrop: '(boolean|string)',
	  keyboard: 'boolean',
	  scroll: 'boolean'
	};
	class Offcanvas extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._isShown = false;
	    this._backdrop = this._initializeBackDrop();
	    this._focustrap = this._initializeFocusTrap();
	    this._addEventListeners();
	  }
	  static get Default() {
	    return Default$5;
	  }
	  static get DefaultType() {
	    return DefaultType$5;
	  }
	  static get NAME() {
	    return NAME$6;
	  }
	  toggle(relatedTarget) {
	    return this._isShown ? this.hide() : this.show(relatedTarget);
	  }
	  show(relatedTarget) {
	    if (this._isShown) {
	      return;
	    }
	    const showEvent = EventHandler.trigger(this._element, EVENT_SHOW$3, {
	      relatedTarget
	    });
	    if (showEvent.defaultPrevented) {
	      return;
	    }
	    this._isShown = true;
	    this._backdrop.show();
	    if (!this._config.scroll) {
	      new ScrollBarHelper().hide();
	    }
	    this._element.setAttribute('aria-modal', true);
	    this._element.setAttribute('role', 'dialog');
	    this._element.classList.add(CLASS_NAME_SHOWING$1);
	    const completeCallBack = () => {
	      if (!this._config.scroll || this._config.backdrop) {
	        this._focustrap.activate();
	      }
	      this._element.classList.add(CLASS_NAME_SHOW$3);
	      this._element.classList.remove(CLASS_NAME_SHOWING$1);
	      EventHandler.trigger(this._element, EVENT_SHOWN$3, {
	        relatedTarget
	      });
	    };
	    this._queueCallback(completeCallBack, this._element, true);
	  }
	  hide() {
	    if (!this._isShown) {
	      return;
	    }
	    const hideEvent = EventHandler.trigger(this._element, EVENT_HIDE$3);
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    this._focustrap.deactivate();
	    this._element.blur();
	    this._isShown = false;
	    this._element.classList.add(CLASS_NAME_HIDING);
	    this._backdrop.hide();
	    const completeCallback = () => {
	      this._element.classList.remove(CLASS_NAME_SHOW$3, CLASS_NAME_HIDING);
	      this._element.removeAttribute('aria-modal');
	      this._element.removeAttribute('role');
	      if (!this._config.scroll) {
	        new ScrollBarHelper().reset();
	      }
	      EventHandler.trigger(this._element, EVENT_HIDDEN$3);
	    };
	    this._queueCallback(completeCallback, this._element, true);
	  }
	  dispose() {
	    this._backdrop.dispose();
	    this._focustrap.deactivate();
	    super.dispose();
	  }
	  _initializeBackDrop() {
	    const clickCallback = () => {
	      if (this._config.backdrop === 'static') {
	        EventHandler.trigger(this._element, EVENT_HIDE_PREVENTED);
	        return;
	      }
	      this.hide();
	    };
	    const isVisible = Boolean(this._config.backdrop);
	    return new Backdrop({
	      className: CLASS_NAME_BACKDROP,
	      isVisible,
	      isAnimated: true,
	      rootElement: this._element.parentNode,
	      clickCallback: isVisible ? clickCallback : null
	    });
	  }
	  _initializeFocusTrap() {
	    return new FocusTrap({
	      trapElement: this._element
	    });
	  }
	  _addEventListeners() {
	    EventHandler.on(this._element, EVENT_KEYDOWN_DISMISS, event => {
	      if (event.key !== ESCAPE_KEY) {
	        return;
	      }
	      if (!this._config.keyboard) {
	        EventHandler.trigger(this._element, EVENT_HIDE_PREVENTED);
	        return;
	      }
	      this.hide();
	    });
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Offcanvas.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (data[config] === undefined || config.startsWith('_') || config === 'constructor') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config](this);
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API$1, SELECTOR_DATA_TOGGLE$1, function (event) {
	  const target = getElementFromSelector(this);
	  if (['A', 'AREA'].includes(this.tagName)) {
	    event.preventDefault();
	  }
	  if (isDisabled(this)) {
	    return;
	  }
	  EventHandler.one(target, EVENT_HIDDEN$3, () => {
	    if (isVisible(this)) {
	      this.focus();
	    }
	  });
	  const alreadyOpen = SelectorEngine.findOne(OPEN_SELECTOR);
	  if (alreadyOpen && alreadyOpen !== target) {
	    Offcanvas.getInstance(alreadyOpen).hide();
	  }
	  const data = Offcanvas.getOrCreateInstance(target);
	  data.toggle(this);
	});
	EventHandler.on(window, EVENT_LOAD_DATA_API$2, () => {
	  for (const selector of SelectorEngine.find(OPEN_SELECTOR)) {
	    Offcanvas.getOrCreateInstance(selector).show();
	  }
	});
	EventHandler.on(window, EVENT_RESIZE, () => {
	  for (const element of SelectorEngine.find('[aria-modal][class*=show][class*=offcanvas-]')) {
	    if (getComputedStyle(element).position !== 'fixed') {
	      Offcanvas.getOrCreateInstance(element).hide();
	    }
	  }
	});
	enableDismissTrigger(Offcanvas);
	defineJQueryPlugin(Offcanvas);
	const uriAttributes = new Set(['background', 'cite', 'href', 'itemtype', 'longdesc', 'poster', 'src', 'xlink:href']);
	const ARIA_ATTRIBUTE_PATTERN = /^aria-[\w-]*$/i;
	const SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file|sms):|[^#&/:?]*(?:[#/?]|$))/i;
	const DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[\d+/a-z]+=*$/i;
	const allowedAttribute = (attribute, allowedAttributeList) => {
	  const attributeName = attribute.nodeName.toLowerCase();
	  if (allowedAttributeList.includes(attributeName)) {
	    if (uriAttributes.has(attributeName)) {
	      return Boolean(SAFE_URL_PATTERN.test(attribute.nodeValue) || DATA_URL_PATTERN.test(attribute.nodeValue));
	    }
	    return true;
	  }
	  return allowedAttributeList.filter(attributeRegex => attributeRegex instanceof RegExp).some(regex => regex.test(attributeName));
	};
	const DefaultAllowlist = {
	  '*': ['class', 'dir', 'id', 'lang', 'role', ARIA_ATTRIBUTE_PATTERN],
	  a: ['target', 'href', 'title', 'rel'],
	  area: [],
	  b: [],
	  br: [],
	  col: [],
	  code: [],
	  div: [],
	  em: [],
	  hr: [],
	  h1: [],
	  h2: [],
	  h3: [],
	  h4: [],
	  h5: [],
	  h6: [],
	  i: [],
	  img: ['src', 'srcset', 'alt', 'title', 'width', 'height'],
	  li: [],
	  ol: [],
	  p: [],
	  pre: [],
	  s: [],
	  small: [],
	  span: [],
	  sub: [],
	  sup: [],
	  strong: [],
	  u: [],
	  ul: []
	};
	function sanitizeHtml(unsafeHtml, allowList, sanitizeFunction) {
	  if (!unsafeHtml.length) {
	    return unsafeHtml;
	  }
	  if (sanitizeFunction && typeof sanitizeFunction === 'function') {
	    return sanitizeFunction(unsafeHtml);
	  }
	  const domParser = new window.DOMParser();
	  const createdDocument = domParser.parseFromString(unsafeHtml, 'text/html');
	  const elements = [].concat(...createdDocument.body.querySelectorAll('*'));
	  for (const element of elements) {
	    const elementName = element.nodeName.toLowerCase();
	    if (!Object.keys(allowList).includes(elementName)) {
	      element.remove();
	      continue;
	    }
	    const attributeList = [].concat(...element.attributes);
	    const allowedAttributes = [].concat(allowList['*'] || [], allowList[elementName] || []);
	    for (const attribute of attributeList) {
	      if (!allowedAttribute(attribute, allowedAttributes)) {
	        element.removeAttribute(attribute.nodeName);
	      }
	    }
	  }
	  return createdDocument.body.innerHTML;
	}
	const NAME$5 = 'TemplateFactory';
	const Default$4 = {
	  allowList: DefaultAllowlist,
	  content: {},
	  extraClass: '',
	  html: false,
	  sanitize: true,
	  sanitizeFn: null,
	  template: '<div></div>'
	};
	const DefaultType$4 = {
	  allowList: 'object',
	  content: 'object',
	  extraClass: '(string|function)',
	  html: 'boolean',
	  sanitize: 'boolean',
	  sanitizeFn: '(null|function)',
	  template: 'string'
	};
	const DefaultContentType = {
	  entry: '(string|element|function|null)',
	  selector: '(string|element)'
	};
	class TemplateFactory extends Config {
	  constructor(config) {
	    super();
	    this._config = this._getConfig(config);
	  }
	  static get Default() {
	    return Default$4;
	  }
	  static get DefaultType() {
	    return DefaultType$4;
	  }
	  static get NAME() {
	    return NAME$5;
	  }
	  getContent() {
	    return Object.values(this._config.content).map(config => this._resolvePossibleFunction(config)).filter(Boolean);
	  }
	  hasContent() {
	    return this.getContent().length > 0;
	  }
	  changeContent(content) {
	    this._checkContent(content);
	    this._config.content = { ...this._config.content,
	      ...content
	    };
	    return this;
	  }
	  toHtml() {
	    const templateWrapper = document.createElement('div');
	    templateWrapper.innerHTML = this._maybeSanitize(this._config.template);
	    for (const [selector, text] of Object.entries(this._config.content)) {
	      this._setContent(templateWrapper, text, selector);
	    }
	    const template = templateWrapper.children[0];
	    const extraClass = this._resolvePossibleFunction(this._config.extraClass);
	    if (extraClass) {
	      template.classList.add(...extraClass.split(' '));
	    }
	    return template;
	  }
	  _typeCheckConfig(config) {
	    super._typeCheckConfig(config);
	    this._checkContent(config.content);
	  }
	  _checkContent(arg) {
	    for (const [selector, content] of Object.entries(arg)) {
	      super._typeCheckConfig({
	        selector,
	        entry: content
	      }, DefaultContentType);
	    }
	  }
	  _setContent(template, content, selector) {
	    const templateElement = SelectorEngine.findOne(selector, template);
	    if (!templateElement) {
	      return;
	    }
	    content = this._resolvePossibleFunction(content);
	    if (!content) {
	      templateElement.remove();
	      return;
	    }
	    if (isElement(content)) {
	      this._putElementInTemplate(getElement(content), templateElement);
	      return;
	    }
	    if (this._config.html) {
	      templateElement.innerHTML = this._maybeSanitize(content);
	      return;
	    }
	    templateElement.textContent = content;
	  }
	  _maybeSanitize(arg) {
	    return this._config.sanitize ? sanitizeHtml(arg, this._config.allowList, this._config.sanitizeFn) : arg;
	  }
	  _resolvePossibleFunction(arg) {
	    return typeof arg === 'function' ? arg(this) : arg;
	  }
	  _putElementInTemplate(element, templateElement) {
	    if (this._config.html) {
	      templateElement.innerHTML = '';
	      templateElement.append(element);
	      return;
	    }
	    templateElement.textContent = element.textContent;
	  }
	}
	const NAME$4 = 'tooltip';
	const DISALLOWED_ATTRIBUTES = new Set(['sanitize', 'allowList', 'sanitizeFn']);
	const CLASS_NAME_FADE$2 = 'fade';
	const CLASS_NAME_MODAL = 'modal';
	const CLASS_NAME_SHOW$2 = 'show';
	const SELECTOR_TOOLTIP_INNER = '.tooltip-inner';
	const SELECTOR_MODAL = `.${CLASS_NAME_MODAL}`;
	const EVENT_MODAL_HIDE = 'hide.bs.modal';
	const TRIGGER_HOVER = 'hover';
	const TRIGGER_FOCUS = 'focus';
	const TRIGGER_CLICK = 'click';
	const TRIGGER_MANUAL = 'manual';
	const EVENT_HIDE$2 = 'hide';
	const EVENT_HIDDEN$2 = 'hidden';
	const EVENT_SHOW$2 = 'show';
	const EVENT_SHOWN$2 = 'shown';
	const EVENT_INSERTED = 'inserted';
	const EVENT_CLICK$1 = 'click';
	const EVENT_FOCUSIN$1 = 'focusin';
	const EVENT_FOCUSOUT$1 = 'focusout';
	const EVENT_MOUSEENTER = 'mouseenter';
	const EVENT_MOUSELEAVE = 'mouseleave';
	const AttachmentMap = {
	  AUTO: 'auto',
	  TOP: 'top',
	  RIGHT: isRTL() ? 'left' : 'right',
	  BOTTOM: 'bottom',
	  LEFT: isRTL() ? 'right' : 'left'
	};
	const Default$3 = {
	  allowList: DefaultAllowlist,
	  animation: true,
	  boundary: 'clippingParents',
	  container: false,
	  customClass: '',
	  delay: 0,
	  fallbackPlacements: ['top', 'right', 'bottom', 'left'],
	  html: false,
	  offset: [0, 0],
	  placement: 'top',
	  popperConfig: null,
	  sanitize: true,
	  sanitizeFn: null,
	  selector: false,
	  template: '<div class="tooltip" role="tooltip">' + '<div class="tooltip-arrow"></div>' + '<div class="tooltip-inner"></div>' + '</div>',
	  title: '',
	  trigger: 'hover focus'
	};
	const DefaultType$3 = {
	  allowList: 'object',
	  animation: 'boolean',
	  boundary: '(string|element)',
	  container: '(string|element|boolean)',
	  customClass: '(string|function)',
	  delay: '(number|object)',
	  fallbackPlacements: 'array',
	  html: 'boolean',
	  offset: '(array|string|function)',
	  placement: '(string|function)',
	  popperConfig: '(null|object|function)',
	  sanitize: 'boolean',
	  sanitizeFn: '(null|function)',
	  selector: '(string|boolean)',
	  template: 'string',
	  title: '(string|element|function)',
	  trigger: 'string'
	};
	class Tooltip extends BaseComponent {
	  constructor(element, config) {
	    if (typeof Popper === 'undefined') {
	      throw new TypeError('Bootstrap\'s tooltips require Popper (https://popper.js.org)');
	    }
	    super(element, config);
	    this._isEnabled = true;
	    this._timeout = 0;
	    this._isHovered = null;
	    this._activeTrigger = {};
	    this._popper = null;
	    this._templateFactory = null;
	    this._newContent = null;
	    this.tip = null;
	    this._setListeners();
	    if (!this._config.selector) {
	      this._fixTitle();
	    }
	  }
	  static get Default() {
	    return Default$3;
	  }
	  static get DefaultType() {
	    return DefaultType$3;
	  }
	  static get NAME() {
	    return NAME$4;
	  }
	  enable() {
	    this._isEnabled = true;
	  }
	  disable() {
	    this._isEnabled = false;
	  }
	  toggleEnabled() {
	    this._isEnabled = !this._isEnabled;
	  }
	  toggle() {
	    if (!this._isEnabled) {
	      return;
	    }
	    this._activeTrigger.click = !this._activeTrigger.click;
	    if (this._isShown()) {
	      this._leave();
	      return;
	    }
	    this._enter();
	  }
	  dispose() {
	    clearTimeout(this._timeout);
	    EventHandler.off(this._element.closest(SELECTOR_MODAL), EVENT_MODAL_HIDE, this._hideModalHandler);
	    if (this.tip) {
	      this.tip.remove();
	    }
	    if (this._element.getAttribute('data-bs-original-title')) {
	      this._element.setAttribute('title', this._element.getAttribute('data-bs-original-title'));
	    }
	    this._disposePopper();
	    super.dispose();
	  }
	  show() {
	    if (this._element.style.display === 'none') {
	      throw new Error('Please use show on visible elements');
	    }
	    if (!(this._isWithContent() && this._isEnabled)) {
	      return;
	    }
	    const showEvent = EventHandler.trigger(this._element, this.constructor.eventName(EVENT_SHOW$2));
	    const shadowRoot = findShadowRoot(this._element);
	    const isInTheDom = (shadowRoot || this._element.ownerDocument.documentElement).contains(this._element);
	    if (showEvent.defaultPrevented || !isInTheDom) {
	      return;
	    }
	    if (this.tip) {
	      this.tip.remove();
	      this.tip = null;
	    }
	    const tip = this._getTipElement();
	    this._element.setAttribute('aria-describedby', tip.getAttribute('id'));
	    const {
	      container
	    } = this._config;
	    if (!this._element.ownerDocument.documentElement.contains(this.tip)) {
	      container.append(tip);
	      EventHandler.trigger(this._element, this.constructor.eventName(EVENT_INSERTED));
	    }
	    if (this._popper) {
	      this._popper.update();
	    } else {
	      this._popper = this._createPopper(tip);
	    }
	    tip.classList.add(CLASS_NAME_SHOW$2);
	    if ('ontouchstart' in document.documentElement) {
	      for (const element of [].concat(...document.body.children)) {
	        EventHandler.on(element, 'mouseover', noop);
	      }
	    }
	    const complete = () => {
	      EventHandler.trigger(this._element, this.constructor.eventName(EVENT_SHOWN$2));
	      if (this._isHovered === false) {
	        this._leave();
	      }
	      this._isHovered = false;
	    };
	    this._queueCallback(complete, this.tip, this._isAnimated());
	  }
	  hide() {
	    if (!this._isShown()) {
	      return;
	    }
	    const hideEvent = EventHandler.trigger(this._element, this.constructor.eventName(EVENT_HIDE$2));
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    const tip = this._getTipElement();
	    tip.classList.remove(CLASS_NAME_SHOW$2);
	    if ('ontouchstart' in document.documentElement) {
	      for (const element of [].concat(...document.body.children)) {
	        EventHandler.off(element, 'mouseover', noop);
	      }
	    }
	    this._activeTrigger[TRIGGER_CLICK] = false;
	    this._activeTrigger[TRIGGER_FOCUS] = false;
	    this._activeTrigger[TRIGGER_HOVER] = false;
	    this._isHovered = null;
	    const complete = () => {
	      if (this._isWithActiveTrigger()) {
	        return;
	      }
	      if (!this._isHovered) {
	        tip.remove();
	      }
	      this._element.removeAttribute('aria-describedby');
	      EventHandler.trigger(this._element, this.constructor.eventName(EVENT_HIDDEN$2));
	      this._disposePopper();
	    };
	    this._queueCallback(complete, this.tip, this._isAnimated());
	  }
	  update() {
	    if (this._popper) {
	      this._popper.update();
	    }
	  }
	  _isWithContent() {
	    return Boolean(this._getTitle());
	  }
	  _getTipElement() {
	    if (!this.tip) {
	      this.tip = this._createTipElement(this._newContent || this._getContentForTemplate());
	    }
	    return this.tip;
	  }
	  _createTipElement(content) {
	    const tip = this._getTemplateFactory(content).toHtml();
	    if (!tip) {
	      return null;
	    }
	    tip.classList.remove(CLASS_NAME_FADE$2, CLASS_NAME_SHOW$2);
	    tip.classList.add(`bs-${this.constructor.NAME}-auto`);
	    const tipId = getUID(this.constructor.NAME).toString();
	    tip.setAttribute('id', tipId);
	    if (this._isAnimated()) {
	      tip.classList.add(CLASS_NAME_FADE$2);
	    }
	    return tip;
	  }
	  setContent(content) {
	    this._newContent = content;
	    if (this._isShown()) {
	      this._disposePopper();
	      this.show();
	    }
	  }
	  _getTemplateFactory(content) {
	    if (this._templateFactory) {
	      this._templateFactory.changeContent(content);
	    } else {
	      this._templateFactory = new TemplateFactory({ ...this._config,
	        content,
	        extraClass: this._resolvePossibleFunction(this._config.customClass)
	      });
	    }
	    return this._templateFactory;
	  }
	  _getContentForTemplate() {
	    return {
	      [SELECTOR_TOOLTIP_INNER]: this._getTitle()
	    };
	  }
	  _getTitle() {
	    return this._resolvePossibleFunction(this._config.title) || this._element.getAttribute('data-bs-original-title');
	  }
	  _initializeOnDelegatedTarget(event) {
	    return this.constructor.getOrCreateInstance(event.delegateTarget, this._getDelegateConfig());
	  }
	  _isAnimated() {
	    return this._config.animation || this.tip && this.tip.classList.contains(CLASS_NAME_FADE$2);
	  }
	  _isShown() {
	    return this.tip && this.tip.classList.contains(CLASS_NAME_SHOW$2);
	  }
	  _createPopper(tip) {
	    const placement = typeof this._config.placement === 'function' ? this._config.placement.call(this, tip, this._element) : this._config.placement;
	    const attachment = AttachmentMap[placement.toUpperCase()];
	    return createPopper(this._element, tip, this._getPopperConfig(attachment));
	  }
	  _getOffset() {
	    const {
	      offset
	    } = this._config;
	    if (typeof offset === 'string') {
	      return offset.split(',').map(value => Number.parseInt(value, 10));
	    }
	    if (typeof offset === 'function') {
	      return popperData => offset(popperData, this._element);
	    }
	    return offset;
	  }
	  _resolvePossibleFunction(arg) {
	    return typeof arg === 'function' ? arg.call(this._element) : arg;
	  }
	  _getPopperConfig(attachment) {
	    const defaultBsPopperConfig = {
	      placement: attachment,
	      modifiers: [{
	        name: 'flip',
	        options: {
	          fallbackPlacements: this._config.fallbackPlacements
	        }
	      }, {
	        name: 'offset',
	        options: {
	          offset: this._getOffset()
	        }
	      }, {
	        name: 'preventOverflow',
	        options: {
	          boundary: this._config.boundary
	        }
	      }, {
	        name: 'arrow',
	        options: {
	          element: `.${this.constructor.NAME}-arrow`
	        }
	      }, {
	        name: 'preSetPlacement',
	        enabled: true,
	        phase: 'beforeMain',
	        fn: data => {
	          this._getTipElement().setAttribute('data-popper-placement', data.state.placement);
	        }
	      }]
	    };
	    return { ...defaultBsPopperConfig,
	      ...(typeof this._config.popperConfig === 'function' ? this._config.popperConfig(defaultBsPopperConfig) : this._config.popperConfig)
	    };
	  }
	  _setListeners() {
	    const triggers = this._config.trigger.split(' ');
	    for (const trigger of triggers) {
	      if (trigger === 'click') {
	        EventHandler.on(this._element, this.constructor.eventName(EVENT_CLICK$1), this._config.selector, event => {
	          const context = this._initializeOnDelegatedTarget(event);
	          context.toggle();
	        });
	      } else if (trigger !== TRIGGER_MANUAL) {
	        const eventIn = trigger === TRIGGER_HOVER ? this.constructor.eventName(EVENT_MOUSEENTER) : this.constructor.eventName(EVENT_FOCUSIN$1);
	        const eventOut = trigger === TRIGGER_HOVER ? this.constructor.eventName(EVENT_MOUSELEAVE) : this.constructor.eventName(EVENT_FOCUSOUT$1);
	        EventHandler.on(this._element, eventIn, this._config.selector, event => {
	          const context = this._initializeOnDelegatedTarget(event);
	          context._activeTrigger[event.type === 'focusin' ? TRIGGER_FOCUS : TRIGGER_HOVER] = true;
	          context._enter();
	        });
	        EventHandler.on(this._element, eventOut, this._config.selector, event => {
	          const context = this._initializeOnDelegatedTarget(event);
	          context._activeTrigger[event.type === 'focusout' ? TRIGGER_FOCUS : TRIGGER_HOVER] = context._element.contains(event.relatedTarget);
	          context._leave();
	        });
	      }
	    }
	    this._hideModalHandler = () => {
	      if (this._element) {
	        this.hide();
	      }
	    };
	    EventHandler.on(this._element.closest(SELECTOR_MODAL), EVENT_MODAL_HIDE, this._hideModalHandler);
	  }
	  _fixTitle() {
	    const title = this._element.getAttribute('title');
	    if (!title) {
	      return;
	    }
	    if (!this._element.getAttribute('aria-label') && !this._element.textContent.trim()) {
	      this._element.setAttribute('aria-label', title);
	    }
	    this._element.setAttribute('data-bs-original-title', title);
	    this._element.removeAttribute('title');
	  }
	  _enter() {
	    if (this._isShown() || this._isHovered) {
	      this._isHovered = true;
	      return;
	    }
	    this._isHovered = true;
	    this._setTimeout(() => {
	      if (this._isHovered) {
	        this.show();
	      }
	    }, this._config.delay.show);
	  }
	  _leave() {
	    if (this._isWithActiveTrigger()) {
	      return;
	    }
	    this._isHovered = false;
	    this._setTimeout(() => {
	      if (!this._isHovered) {
	        this.hide();
	      }
	    }, this._config.delay.hide);
	  }
	  _setTimeout(handler, timeout) {
	    clearTimeout(this._timeout);
	    this._timeout = setTimeout(handler, timeout);
	  }
	  _isWithActiveTrigger() {
	    return Object.values(this._activeTrigger).includes(true);
	  }
	  _getConfig(config) {
	    const dataAttributes = Manipulator.getDataAttributes(this._element);
	    for (const dataAttribute of Object.keys(dataAttributes)) {
	      if (DISALLOWED_ATTRIBUTES.has(dataAttribute)) {
	        delete dataAttributes[dataAttribute];
	      }
	    }
	    config = { ...dataAttributes,
	      ...(typeof config === 'object' && config ? config : {})
	    };
	    config = this._mergeConfigObj(config);
	    config = this._configAfterMerge(config);
	    this._typeCheckConfig(config);
	    return config;
	  }
	  _configAfterMerge(config) {
	    config.container = config.container === false ? document.body : getElement(config.container);
	    if (typeof config.delay === 'number') {
	      config.delay = {
	        show: config.delay,
	        hide: config.delay
	      };
	    }
	    if (typeof config.title === 'number') {
	      config.title = config.title.toString();
	    }
	    if (typeof config.content === 'number') {
	      config.content = config.content.toString();
	    }
	    return config;
	  }
	  _getDelegateConfig() {
	    const config = {};
	    for (const key in this._config) {
	      if (this.constructor.Default[key] !== this._config[key]) {
	        config[key] = this._config[key];
	      }
	    }
	    config.selector = false;
	    config.trigger = 'manual';
	    return config;
	  }
	  _disposePopper() {
	    if (this._popper) {
	      this._popper.destroy();
	      this._popper = null;
	    }
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Tooltip.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (typeof data[config] === 'undefined') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config]();
	    });
	  }
	}
	defineJQueryPlugin(Tooltip);
	const NAME$3 = 'popover';
	const SELECTOR_TITLE = '.popover-header';
	const SELECTOR_CONTENT = '.popover-body';
	const Default$2 = { ...Tooltip.Default,
	  content: '',
	  offset: [0, 8],
	  placement: 'right',
	  template: '<div class="popover" role="tooltip">' + '<div class="popover-arrow"></div>' + '<h3 class="popover-header"></h3>' + '<div class="popover-body"></div>' + '</div>',
	  trigger: 'click'
	};
	const DefaultType$2 = { ...Tooltip.DefaultType,
	  content: '(null|string|element|function)'
	};
	class Popover extends Tooltip {
	  static get Default() {
	    return Default$2;
	  }
	  static get DefaultType() {
	    return DefaultType$2;
	  }
	  static get NAME() {
	    return NAME$3;
	  }
	  _isWithContent() {
	    return this._getTitle() || this._getContent();
	  }
	  _getContentForTemplate() {
	    return {
	      [SELECTOR_TITLE]: this._getTitle(),
	      [SELECTOR_CONTENT]: this._getContent()
	    };
	  }
	  _getContent() {
	    return this._resolvePossibleFunction(this._config.content);
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Popover.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (typeof data[config] === 'undefined') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config]();
	    });
	  }
	}
	defineJQueryPlugin(Popover);
	const NAME$2 = 'scrollspy';
	const DATA_KEY$2 = 'bs.scrollspy';
	const EVENT_KEY$2 = `.${DATA_KEY$2}`;
	const DATA_API_KEY = '.data-api';
	const EVENT_ACTIVATE = `activate${EVENT_KEY$2}`;
	const EVENT_CLICK = `click${EVENT_KEY$2}`;
	const EVENT_LOAD_DATA_API$1 = `load${EVENT_KEY$2}${DATA_API_KEY}`;
	const CLASS_NAME_DROPDOWN_ITEM = 'dropdown-item';
	const CLASS_NAME_ACTIVE$1 = 'active';
	const SELECTOR_DATA_SPY = '[data-bs-spy="scroll"]';
	const SELECTOR_TARGET_LINKS = '[href]';
	const SELECTOR_NAV_LIST_GROUP = '.nav, .list-group';
	const SELECTOR_NAV_LINKS = '.nav-link';
	const SELECTOR_NAV_ITEMS = '.nav-item';
	const SELECTOR_LIST_ITEMS = '.list-group-item';
	const SELECTOR_LINK_ITEMS = `${SELECTOR_NAV_LINKS}, ${SELECTOR_NAV_ITEMS} > ${SELECTOR_NAV_LINKS}, ${SELECTOR_LIST_ITEMS}`;
	const SELECTOR_DROPDOWN = '.dropdown';
	const SELECTOR_DROPDOWN_TOGGLE$1 = '.dropdown-toggle';
	const Default$1 = {
	  offset: null,
	  rootMargin: '0px 0px -25%',
	  smoothScroll: false,
	  target: null,
	  threshold: [0.1, 0.5, 1]
	};
	const DefaultType$1 = {
	  offset: '(number|null)',
	  rootMargin: 'string',
	  smoothScroll: 'boolean',
	  target: 'element',
	  threshold: 'array'
	};
	class ScrollSpy extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._targetLinks = new Map();
	    this._observableSections = new Map();
	    this._rootElement = getComputedStyle(this._element).overflowY === 'visible' ? null : this._element;
	    this._activeTarget = null;
	    this._observer = null;
	    this._previousScrollData = {
	      visibleEntryTop: 0,
	      parentScrollTop: 0
	    };
	    this.refresh();
	  }
	  static get Default() {
	    return Default$1;
	  }
	  static get DefaultType() {
	    return DefaultType$1;
	  }
	  static get NAME() {
	    return NAME$2;
	  }
	  refresh() {
	    this._initializeTargetsAndObservables();
	    this._maybeEnableSmoothScroll();
	    if (this._observer) {
	      this._observer.disconnect();
	    } else {
	      this._observer = this._getNewObserver();
	    }
	    for (const section of this._observableSections.values()) {
	      this._observer.observe(section);
	    }
	  }
	  dispose() {
	    this._observer.disconnect();
	    super.dispose();
	  }
	  _configAfterMerge(config) {
	    config.target = getElement(config.target) || document.body;
	    config.rootMargin = config.offset ? `${config.offset}px 0px -30%` : config.rootMargin;
	    if (typeof config.threshold === 'string') {
	      config.threshold = config.threshold.split(',').map(value => Number.parseFloat(value));
	    }
	    return config;
	  }
	  _maybeEnableSmoothScroll() {
	    if (!this._config.smoothScroll) {
	      return;
	    }
	    EventHandler.off(this._config.target, EVENT_CLICK);
	    EventHandler.on(this._config.target, EVENT_CLICK, SELECTOR_TARGET_LINKS, event => {
	      const observableSection = this._observableSections.get(event.target.hash);
	      if (observableSection) {
	        event.preventDefault();
	        const root = this._rootElement || window;
	        const height = observableSection.offsetTop - this._element.offsetTop;
	        if (root.scrollTo) {
	          root.scrollTo({
	            top: height,
	            behavior: 'smooth'
	          });
	          return;
	        }
	        root.scrollTop = height;
	      }
	    });
	  }
	  _getNewObserver() {
	    const options = {
	      root: this._rootElement,
	      threshold: this._config.threshold,
	      rootMargin: this._config.rootMargin
	    };
	    return new IntersectionObserver(entries => this._observerCallback(entries), options);
	  }
	  _observerCallback(entries) {
	    const targetElement = entry => this._targetLinks.get(`#${entry.target.id}`);
	    const activate = entry => {
	      this._previousScrollData.visibleEntryTop = entry.target.offsetTop;
	      this._process(targetElement(entry));
	    };
	    const parentScrollTop = (this._rootElement || document.documentElement).scrollTop;
	    const userScrollsDown = parentScrollTop >= this._previousScrollData.parentScrollTop;
	    this._previousScrollData.parentScrollTop = parentScrollTop;
	    for (const entry of entries) {
	      if (!entry.isIntersecting) {
	        this._activeTarget = null;
	        this._clearActiveClass(targetElement(entry));
	        continue;
	      }
	      const entryIsLowerThanPrevious = entry.target.offsetTop >= this._previousScrollData.visibleEntryTop;
	      if (userScrollsDown && entryIsLowerThanPrevious) {
	        activate(entry);
	        if (!parentScrollTop) {
	          return;
	        }
	        continue;
	      }
	      if (!userScrollsDown && !entryIsLowerThanPrevious) {
	        activate(entry);
	      }
	    }
	  }
	  _initializeTargetsAndObservables() {
	    this._targetLinks = new Map();
	    this._observableSections = new Map();
	    const targetLinks = SelectorEngine.find(SELECTOR_TARGET_LINKS, this._config.target);
	    for (const anchor of targetLinks) {
	      if (!anchor.hash || isDisabled(anchor)) {
	        continue;
	      }
	      const observableSection = SelectorEngine.findOne(anchor.hash, this._element);
	      if (isVisible(observableSection)) {
	        this._targetLinks.set(anchor.hash, anchor);
	        this._observableSections.set(anchor.hash, observableSection);
	      }
	    }
	  }
	  _process(target) {
	    if (this._activeTarget === target) {
	      return;
	    }
	    this._clearActiveClass(this._config.target);
	    this._activeTarget = target;
	    target.classList.add(CLASS_NAME_ACTIVE$1);
	    this._activateParents(target);
	    EventHandler.trigger(this._element, EVENT_ACTIVATE, {
	      relatedTarget: target
	    });
	  }
	  _activateParents(target) {
	    if (target.classList.contains(CLASS_NAME_DROPDOWN_ITEM)) {
	      SelectorEngine.findOne(SELECTOR_DROPDOWN_TOGGLE$1, target.closest(SELECTOR_DROPDOWN)).classList.add(CLASS_NAME_ACTIVE$1);
	      return;
	    }
	    for (const listGroup of SelectorEngine.parents(target, SELECTOR_NAV_LIST_GROUP)) {
	      for (const item of SelectorEngine.prev(listGroup, SELECTOR_LINK_ITEMS)) {
	        item.classList.add(CLASS_NAME_ACTIVE$1);
	      }
	    }
	  }
	  _clearActiveClass(parent) {
	    parent.classList.remove(CLASS_NAME_ACTIVE$1);
	    const activeNodes = SelectorEngine.find(`${SELECTOR_TARGET_LINKS}.${CLASS_NAME_ACTIVE$1}`, parent);
	    for (const node of activeNodes) {
	      node.classList.remove(CLASS_NAME_ACTIVE$1);
	    }
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = ScrollSpy.getOrCreateInstance(this, config);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (data[config] === undefined || config.startsWith('_') || config === 'constructor') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config]();
	    });
	  }
	}
	EventHandler.on(window, EVENT_LOAD_DATA_API$1, () => {
	  for (const spy of SelectorEngine.find(SELECTOR_DATA_SPY)) {
	    ScrollSpy.getOrCreateInstance(spy);
	  }
	});
	defineJQueryPlugin(ScrollSpy);
	const NAME$1 = 'tab';
	const DATA_KEY$1 = 'bs.tab';
	const EVENT_KEY$1 = `.${DATA_KEY$1}`;
	const EVENT_HIDE$1 = `hide${EVENT_KEY$1}`;
	const EVENT_HIDDEN$1 = `hidden${EVENT_KEY$1}`;
	const EVENT_SHOW$1 = `show${EVENT_KEY$1}`;
	const EVENT_SHOWN$1 = `shown${EVENT_KEY$1}`;
	const EVENT_CLICK_DATA_API = `click${EVENT_KEY$1}`;
	const EVENT_KEYDOWN = `keydown${EVENT_KEY$1}`;
	const EVENT_LOAD_DATA_API = `load${EVENT_KEY$1}`;
	const ARROW_LEFT_KEY = 'ArrowLeft';
	const ARROW_RIGHT_KEY = 'ArrowRight';
	const ARROW_UP_KEY = 'ArrowUp';
	const ARROW_DOWN_KEY = 'ArrowDown';
	const CLASS_NAME_ACTIVE = 'active';
	const CLASS_NAME_FADE$1 = 'fade';
	const CLASS_NAME_SHOW$1 = 'show';
	const CLASS_DROPDOWN = 'dropdown';
	const SELECTOR_DROPDOWN_TOGGLE = '.dropdown-toggle';
	const SELECTOR_DROPDOWN_MENU = '.dropdown-menu';
	const NOT_SELECTOR_DROPDOWN_TOGGLE = ':not(.dropdown-toggle)';
	const SELECTOR_TAB_PANEL = '.list-group, .nav, [role="tablist"]';
	const SELECTOR_OUTER = '.nav-item, .list-group-item';
	const SELECTOR_INNER = `.nav-link${NOT_SELECTOR_DROPDOWN_TOGGLE}, .list-group-item${NOT_SELECTOR_DROPDOWN_TOGGLE}, [role="tab"]${NOT_SELECTOR_DROPDOWN_TOGGLE}`;
	const SELECTOR_DATA_TOGGLE = '[data-bs-toggle="tab"], [data-bs-toggle="pill"], [data-bs-toggle="list"]';
	const SELECTOR_INNER_ELEM = `${SELECTOR_INNER}, ${SELECTOR_DATA_TOGGLE}`;
	const SELECTOR_DATA_TOGGLE_ACTIVE = `.${CLASS_NAME_ACTIVE}[data-bs-toggle="tab"], .${CLASS_NAME_ACTIVE}[data-bs-toggle="pill"], .${CLASS_NAME_ACTIVE}[data-bs-toggle="list"]`;
	class Tab extends BaseComponent {
	  constructor(element) {
	    super(element);
	    this._parent = this._element.closest(SELECTOR_TAB_PANEL);
	    if (!this._parent) {
	      return;
	    }
	    this._setInitialAttributes(this._parent, this._getChildren());
	    EventHandler.on(this._element, EVENT_KEYDOWN, event => this._keydown(event));
	  }
	  static get NAME() {
	    return NAME$1;
	  }
	  show() {
	    const innerElem = this._element;
	    if (this._elemIsActive(innerElem)) {
	      return;
	    }
	    const active = this._getActiveElem();
	    const hideEvent = active ? EventHandler.trigger(active, EVENT_HIDE$1, {
	      relatedTarget: innerElem
	    }) : null;
	    const showEvent = EventHandler.trigger(innerElem, EVENT_SHOW$1, {
	      relatedTarget: active
	    });
	    if (showEvent.defaultPrevented || hideEvent && hideEvent.defaultPrevented) {
	      return;
	    }
	    this._deactivate(active, innerElem);
	    this._activate(innerElem, active);
	  }
	  _activate(element, relatedElem) {
	    if (!element) {
	      return;
	    }
	    element.classList.add(CLASS_NAME_ACTIVE);
	    this._activate(getElementFromSelector(element));
	    const complete = () => {
	      if (element.getAttribute('role') !== 'tab') {
	        element.classList.add(CLASS_NAME_SHOW$1);
	        return;
	      }
	      element.removeAttribute('tabindex');
	      element.setAttribute('aria-selected', true);
	      this._toggleDropDown(element, true);
	      EventHandler.trigger(element, EVENT_SHOWN$1, {
	        relatedTarget: relatedElem
	      });
	    };
	    this._queueCallback(complete, element, element.classList.contains(CLASS_NAME_FADE$1));
	  }
	  _deactivate(element, relatedElem) {
	    if (!element) {
	      return;
	    }
	    element.classList.remove(CLASS_NAME_ACTIVE);
	    element.blur();
	    this._deactivate(getElementFromSelector(element));
	    const complete = () => {
	      if (element.getAttribute('role') !== 'tab') {
	        element.classList.remove(CLASS_NAME_SHOW$1);
	        return;
	      }
	      element.setAttribute('aria-selected', false);
	      element.setAttribute('tabindex', '-1');
	      this._toggleDropDown(element, false);
	      EventHandler.trigger(element, EVENT_HIDDEN$1, {
	        relatedTarget: relatedElem
	      });
	    };
	    this._queueCallback(complete, element, element.classList.contains(CLASS_NAME_FADE$1));
	  }
	  _keydown(event) {
	    if (![ARROW_LEFT_KEY, ARROW_RIGHT_KEY, ARROW_UP_KEY, ARROW_DOWN_KEY].includes(event.key)) {
	      return;
	    }
	    event.stopPropagation();
	    event.preventDefault();
	    const isNext = [ARROW_RIGHT_KEY, ARROW_DOWN_KEY].includes(event.key);
	    const nextActiveElement = getNextActiveElement(this._getChildren().filter(element => !isDisabled(element)), event.target, isNext, true);
	    if (nextActiveElement) {
	      nextActiveElement.focus({
	        preventScroll: true
	      });
	      Tab.getOrCreateInstance(nextActiveElement).show();
	    }
	  }
	  _getChildren() {
	    return SelectorEngine.find(SELECTOR_INNER_ELEM, this._parent);
	  }
	  _getActiveElem() {
	    return this._getChildren().find(child => this._elemIsActive(child)) || null;
	  }
	  _setInitialAttributes(parent, children) {
	    this._setAttributeIfNotExists(parent, 'role', 'tablist');
	    for (const child of children) {
	      this._setInitialAttributesOnChild(child);
	    }
	  }
	  _setInitialAttributesOnChild(child) {
	    child = this._getInnerElement(child);
	    const isActive = this._elemIsActive(child);
	    const outerElem = this._getOuterElement(child);
	    child.setAttribute('aria-selected', isActive);
	    if (outerElem !== child) {
	      this._setAttributeIfNotExists(outerElem, 'role', 'presentation');
	    }
	    if (!isActive) {
	      child.setAttribute('tabindex', '-1');
	    }
	    this._setAttributeIfNotExists(child, 'role', 'tab');
	    this._setInitialAttributesOnTargetPanel(child);
	  }
	  _setInitialAttributesOnTargetPanel(child) {
	    const target = getElementFromSelector(child);
	    if (!target) {
	      return;
	    }
	    this._setAttributeIfNotExists(target, 'role', 'tabpanel');
	    if (child.id) {
	      this._setAttributeIfNotExists(target, 'aria-labelledby', `#${child.id}`);
	    }
	  }
	  _toggleDropDown(element, open) {
	    const outerElem = this._getOuterElement(element);
	    if (!outerElem.classList.contains(CLASS_DROPDOWN)) {
	      return;
	    }
	    const toggle = (selector, className) => {
	      const element = SelectorEngine.findOne(selector, outerElem);
	      if (element) {
	        element.classList.toggle(className, open);
	      }
	    };
	    toggle(SELECTOR_DROPDOWN_TOGGLE, CLASS_NAME_ACTIVE);
	    toggle(SELECTOR_DROPDOWN_MENU, CLASS_NAME_SHOW$1);
	    outerElem.setAttribute('aria-expanded', open);
	  }
	  _setAttributeIfNotExists(element, attribute, value) {
	    if (!element.hasAttribute(attribute)) {
	      element.setAttribute(attribute, value);
	    }
	  }
	  _elemIsActive(elem) {
	    return elem.classList.contains(CLASS_NAME_ACTIVE);
	  }
	  _getInnerElement(elem) {
	    return elem.matches(SELECTOR_INNER_ELEM) ? elem : SelectorEngine.findOne(SELECTOR_INNER_ELEM, elem);
	  }
	  _getOuterElement(elem) {
	    return elem.closest(SELECTOR_OUTER) || elem;
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Tab.getOrCreateInstance(this);
	      if (typeof config !== 'string') {
	        return;
	      }
	      if (data[config] === undefined || config.startsWith('_') || config === 'constructor') {
	        throw new TypeError(`No method named "${config}"`);
	      }
	      data[config]();
	    });
	  }
	}
	EventHandler.on(document, EVENT_CLICK_DATA_API, SELECTOR_DATA_TOGGLE, function (event) {
	  if (['A', 'AREA'].includes(this.tagName)) {
	    event.preventDefault();
	  }
	  if (isDisabled(this)) {
	    return;
	  }
	  Tab.getOrCreateInstance(this).show();
	});
	EventHandler.on(window, EVENT_LOAD_DATA_API, () => {
	  for (const element of SelectorEngine.find(SELECTOR_DATA_TOGGLE_ACTIVE)) {
	    Tab.getOrCreateInstance(element);
	  }
	});
	defineJQueryPlugin(Tab);
	const NAME = 'toast';
	const DATA_KEY = 'bs.toast';
	const EVENT_KEY = `.${DATA_KEY}`;
	const EVENT_MOUSEOVER = `mouseover${EVENT_KEY}`;
	const EVENT_MOUSEOUT = `mouseout${EVENT_KEY}`;
	const EVENT_FOCUSIN = `focusin${EVENT_KEY}`;
	const EVENT_FOCUSOUT = `focusout${EVENT_KEY}`;
	const EVENT_HIDE = `hide${EVENT_KEY}`;
	const EVENT_HIDDEN = `hidden${EVENT_KEY}`;
	const EVENT_SHOW = `show${EVENT_KEY}`;
	const EVENT_SHOWN = `shown${EVENT_KEY}`;
	const CLASS_NAME_FADE = 'fade';
	const CLASS_NAME_HIDE = 'hide';
	const CLASS_NAME_SHOW = 'show';
	const CLASS_NAME_SHOWING = 'showing';
	const DefaultType = {
	  animation: 'boolean',
	  autohide: 'boolean',
	  delay: 'number'
	};
	const Default = {
	  animation: true,
	  autohide: true,
	  delay: 5000
	};
	class Toast extends BaseComponent {
	  constructor(element, config) {
	    super(element, config);
	    this._timeout = null;
	    this._hasMouseInteraction = false;
	    this._hasKeyboardInteraction = false;
	    this._setListeners();
	  }
	  static get Default() {
	    return Default;
	  }
	  static get DefaultType() {
	    return DefaultType;
	  }
	  static get NAME() {
	    return NAME;
	  }
	  show() {
	    const showEvent = EventHandler.trigger(this._element, EVENT_SHOW);
	    if (showEvent.defaultPrevented) {
	      return;
	    }
	    this._clearTimeout();
	    if (this._config.animation) {
	      this._element.classList.add(CLASS_NAME_FADE);
	    }
	    const complete = () => {
	      this._element.classList.remove(CLASS_NAME_SHOWING);
	      EventHandler.trigger(this._element, EVENT_SHOWN);
	      this._maybeScheduleHide();
	    };
	    this._element.classList.remove(CLASS_NAME_HIDE);
	    reflow(this._element);
	    this._element.classList.add(CLASS_NAME_SHOW, CLASS_NAME_SHOWING);
	    this._queueCallback(complete, this._element, this._config.animation);
	  }
	  hide() {
	    if (!this.isShown()) {
	      return;
	    }
	    const hideEvent = EventHandler.trigger(this._element, EVENT_HIDE);
	    if (hideEvent.defaultPrevented) {
	      return;
	    }
	    const complete = () => {
	      this._element.classList.add(CLASS_NAME_HIDE);
	      this._element.classList.remove(CLASS_NAME_SHOWING, CLASS_NAME_SHOW);
	      EventHandler.trigger(this._element, EVENT_HIDDEN);
	    };
	    this._element.classList.add(CLASS_NAME_SHOWING);
	    this._queueCallback(complete, this._element, this._config.animation);
	  }
	  dispose() {
	    this._clearTimeout();
	    if (this.isShown()) {
	      this._element.classList.remove(CLASS_NAME_SHOW);
	    }
	    super.dispose();
	  }
	  isShown() {
	    return this._element.classList.contains(CLASS_NAME_SHOW);
	  }
	  _maybeScheduleHide() {
	    if (!this._config.autohide) {
	      return;
	    }
	    if (this._hasMouseInteraction || this._hasKeyboardInteraction) {
	      return;
	    }
	    this._timeout = setTimeout(() => {
	      this.hide();
	    }, this._config.delay);
	  }
	  _onInteraction(event, isInteracting) {
	    switch (event.type) {
	      case 'mouseover':
	      case 'mouseout':
	        {
	          this._hasMouseInteraction = isInteracting;
	          break;
	        }
	      case 'focusin':
	      case 'focusout':
	        {
	          this._hasKeyboardInteraction = isInteracting;
	          break;
	        }
	    }
	    if (isInteracting) {
	      this._clearTimeout();
	      return;
	    }
	    const nextElement = event.relatedTarget;
	    if (this._element === nextElement || this._element.contains(nextElement)) {
	      return;
	    }
	    this._maybeScheduleHide();
	  }
	  _setListeners() {
	    EventHandler.on(this._element, EVENT_MOUSEOVER, event => this._onInteraction(event, true));
	    EventHandler.on(this._element, EVENT_MOUSEOUT, event => this._onInteraction(event, false));
	    EventHandler.on(this._element, EVENT_FOCUSIN, event => this._onInteraction(event, true));
	    EventHandler.on(this._element, EVENT_FOCUSOUT, event => this._onInteraction(event, false));
	  }
	  _clearTimeout() {
	    clearTimeout(this._timeout);
	    this._timeout = null;
	  }
	  static jQueryInterface(config) {
	    return this.each(function () {
	      const data = Toast.getOrCreateInstance(this, config);
	      if (typeof config === 'string') {
	        if (typeof data[config] === 'undefined') {
	          throw new TypeError(`No method named "${config}"`);
	        }
	        data[config](this);
	      }
	    });
	  }
	}
	enableDismissTrigger(Toast);
	defineJQueryPlugin(Toast);

	var bootstrap = /*#__PURE__*/Object.freeze({
		__proto__: null,
		Alert: Alert,
		Button: Button,
		Carousel: Carousel,
		Collapse: Collapse,
		Dropdown: Dropdown,
		Modal: Modal,
		Offcanvas: Offcanvas,
		Popover: Popover,
		ScrollSpy: ScrollSpy,
		Tab: Tab,
		Toast: Toast,
		Tooltip: Tooltip
	});

	var dropdownTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
	dropdownTriggerList.map(function (dropdownTriggerEl) {
	  var options = {
	    boundary: dropdownTriggerEl.getAttribute('data-bs-boundary') === 'viewport' ? document.querySelector('.btn') : 'clippingParents'
	  };
	  return new Dropdown(dropdownTriggerEl, options);
	});

	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	tooltipTriggerList.map(function (tooltipTriggerEl) {
	  var _ref, _tooltipTriggerEl$get;
	  var options = {
	    delay: {
	      show: 50,
	      hide: 50
	    },
	    html: (_ref = tooltipTriggerEl.getAttribute("data-bs-html") === "true") !== null && _ref !== void 0 ? _ref : false,
	    placement: (_tooltipTriggerEl$get = tooltipTriggerEl.getAttribute('data-bs-placement')) !== null && _tooltipTriggerEl$get !== void 0 ? _tooltipTriggerEl$get : 'auto'
	  };
	  return new Tooltip(tooltipTriggerEl, options);
	});

	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
	popoverTriggerList.map(function (popoverTriggerEl) {
	  var _ref, _popoverTriggerEl$get;
	  var options = {
	    delay: {
	      show: 50,
	      hide: 50
	    },
	    html: (_ref = popoverTriggerEl.getAttribute('data-bs-html') === "true") !== null && _ref !== void 0 ? _ref : false,
	    placement: (_popoverTriggerEl$get = popoverTriggerEl.getAttribute('data-bs-placement')) !== null && _popoverTriggerEl$get !== void 0 ? _popoverTriggerEl$get : 'auto'
	  };
	  return new Popover(popoverTriggerEl, options);
	});

	var switchesTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="switch-icon"]'));
	switchesTriggerList.map(function (switchTriggerEl) {
	  switchTriggerEl.addEventListener('click', function (e) {
	    e.stopPropagation();
	    switchTriggerEl.classList.toggle('active');
	  });
	});

	var EnableActivationTabsFromLocationHash = function EnableActivationTabsFromLocationHash() {
	  var locationHash = window.location.hash;
	  if (locationHash) {
	    var tabsList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
	    var matchedTabs = tabsList.filter(function (tab) {
	      return tab.hash === locationHash;
	    });
	    matchedTabs.map(function (tab) {
	      new Tab(tab).show();
	    });
	  }
	};
	EnableActivationTabsFromLocationHash();

	var toastsTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="toast"]'));
	toastsTriggerList.map(function (toastTriggerEl) {
	  return new Toast(toastTriggerEl);
	});

	var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

	var tomSelect_complete_min = {exports: {}};

	(function (module, exports) {
		!function(e,t){module.exports=t();}(commonjsGlobal,(function(){	function e(e,t){e.split(/\s+/).forEach((e=>{t(e);}));}class t{constructor(){this._events=void 0,this._events={};}on(t,i){e(t,(e=>{const t=this._events[e]||[];
		t.push(i),this._events[e]=t;}));}off(t,i){var s=arguments.length;
		0!==s?e(t,(e=>{if(1===s)return void delete this._events[e]
		const t=this._events[e];
		void 0!==t&&(t.splice(t.indexOf(i),1),this._events[e]=t);})):this._events={};}trigger(t,...i){var s=this;
		e(t,(e=>{const t=s._events[e];
		void 0!==t&&t.forEach((e=>{e.apply(s,i);}));}));}}const i=e=>(e=e.filter(Boolean)).length<2?e[0]||"":1==l(e)?"["+e.join("")+"]":"(?:"+e.join("|")+")",s=e=>{if(!o(e))return e.join("")
		let t="",i=0;
		const s=()=>{i>1&&(t+="{"+i+"}");};
		return e.forEach(((n,o)=>{n!==e[o-1]?(s(),t+=n,i=1):i++;})),s(),t},n=e=>{let t=c(e);
		return i(t)},o=e=>new Set(e).size!==e.length,r=e=>(e+"").replace(/([\$\(\)\*\+\.\?\[\]\^\{\|\}\\])/gu,"\\$1"),l=e=>e.reduce(((e,t)=>Math.max(e,a(t))),0),a=e=>c(e).length,c=e=>Array.from(e),d=e=>{if(1===e.length)return [[e]]
		let t=[];
		const i=e.substring(1);
		return d(i).forEach((function(i){let s=i.slice(0);
		s[0]=e.charAt(0)+s[0],t.push(s),s=i.slice(0),s.unshift(e.charAt(0)),t.push(s);})),t},u=[[0,65535]];
		let p,h;
		const g={},f={"/":"⁄∕",0:"߀",a:"ⱥɐɑ",aa:"ꜳ",ae:"æǽǣ",ao:"ꜵ",au:"ꜷ",av:"ꜹꜻ",ay:"ꜽ",b:"ƀɓƃ",c:"ꜿƈȼↄ",d:"đɗɖᴅƌꮷԁɦ",e:"ɛǝᴇɇ",f:"ꝼƒ",g:"ǥɠꞡᵹꝿɢ",h:"ħⱨⱶɥ",i:"ɨı",j:"ɉȷ",k:"ƙⱪꝁꝃꝅꞣ",l:"łƚɫⱡꝉꝇꞁɭ",m:"ɱɯϻ",n:"ꞥƞɲꞑᴎлԉ",o:"øǿɔɵꝋꝍᴑ",oe:"œ",oi:"ƣ",oo:"ꝏ",ou:"ȣ",p:"ƥᵽꝑꝓꝕρ",q:"ꝗꝙɋ",r:"ɍɽꝛꞧꞃ",s:"ßȿꞩꞅʂ",t:"ŧƭʈⱦꞇ",th:"þ",tz:"ꜩ",u:"ʉ",v:"ʋꝟʌ",vy:"ꝡ",w:"ⱳ",y:"ƴɏỿ",z:"ƶȥɀⱬꝣ",hv:"ƕ"};
		for(let e in f){let t=f[e]||"";
		for(let i=0;i<t.length;i++){let s=t.substring(i,i+1);
		g[s]=e;}}const v=new RegExp(Object.keys(g).join("|")+"|[̀-ͯ·ʾʼ]","gu"),m=(e,t="NFKD")=>e.normalize(t),y=e=>c(e).reduce(((e,t)=>e+b(t)),""),b=e=>(e=m(e).toLowerCase().replace(v,(e=>g[e]||"")),m(e,"NFC"));
		const O=e=>{const t={},i=(e,i)=>{const s=t[e]||new Set,o=new RegExp("^"+n(s)+"$","iu");
		i.match(o)||(s.add(r(i)),t[e]=s);};
		for(let t of function*(e){for(const[t,i]of e)for(let e=t;e<=i;e++){let t=String.fromCharCode(e),i=y(t);
		i!=t.toLowerCase()&&(i.length>3||0!=i.length&&(yield {folded:i,composed:t,code_point:e}));}}(e))i(t.folded,t.folded),i(t.folded,t.composed);
		return t},w=e=>{const t=O(e),s={};
		let o=[];
		for(let e in t){let i=t[e];
		i&&(s[e]=n(i)),e.length>1&&o.push(r(e));}o.sort(((e,t)=>t.length-e.length));
		const l=i(o);
		return h=new RegExp("^"+l,"u"),s},_=(e,t=1)=>(t=Math.max(t,e.length-1),i(d(e).map((e=>((e,t=1)=>{let i=0;
		return e=e.map((e=>(p[e]&&(i+=e.length),p[e]||e))),i>=t?s(e):""})(e,t))))),I=(e,t=!0)=>{let n=e.length>1?1:0;
		return i(e.map((e=>{let i=[];
		const o=t?e.length():e.length()-1;
		for(let t=0;t<o;t++)i.push(_(e.substrs[t]||"",n));
		return s(i)})))},S=(e,t)=>{for(const i of t){if(i.start!=e.start||i.end!=e.end)continue
		if(i.substrs.join("")!==e.substrs.join(""))continue
		let t=e.parts;
		const s=e=>{for(const i of t){if(i.start===e.start&&i.substr===e.substr)return !1
		if(1!=e.length&&1!=i.length){if(e.start<i.start&&e.end>i.start)return !0
		if(i.start<e.start&&i.end>e.start)return !0}}return !1};
		if(!(i.parts.filter(s).length>0))return !0}return !1};
		class C{constructor(){this.parts=[],this.substrs=[],this.start=0,this.end=0;}add(e){e&&(this.parts.push(e),this.substrs.push(e.substr),this.start=Math.min(e.start,this.start),this.end=Math.max(e.end,this.end));}last(){return this.parts[this.parts.length-1]}length(){return this.parts.length}clone(e,t){let i=new C,s=JSON.parse(JSON.stringify(this.parts)),n=s.pop();
		for(const e of s)i.add(e);
		let o=t.substr.substring(0,e-n.start),r=o.length;
		return i.add({start:n.start,end:n.start+r,length:r,substr:o}),i}}const A=e=>{	void 0===p&&(p=w(u)),e=y(e);
		let i="",s=[new C];
		for(let t=0;t<e.length;t++){let n=e.substring(t).match(h);
		const o=e.substring(t,t+1),r=n?n[0]:null;
		let l=[],a=new Set;
		for(const e of s){const i=e.last();
		if(!i||1==i.length||i.end<=t)if(r){const i=r.length;
		e.add({start:t,end:t+i,length:i,substr:r}),a.add("1");}else e.add({start:t,end:t+1,length:1,substr:o}),a.add("2");
		else if(r){let s=e.clone(t,i);
		const n=r.length;
		s.add({start:t,end:t+n,length:n,substr:r}),l.push(s);}else a.add("3");}if(l.length>0){l=l.sort(((e,t)=>e.length()-t.length()));
		for(let e of l)S(e,s)||s.push(e);}else if(t>0&&1==a.size&&!a.has("3")){i+=I(s,!1);
		let e=new C;
		const t=s[0];
		t&&e.add(t.last()),s=[e];}}return i+=I(s,!0),i},x=(e,t)=>{if(e)return e[t]},k=(e,t)=>{if(e){for(var i,s=t.split(".");(i=s.shift())&&(e=e[i]););return e}},F=(e,t,i)=>{var s,n;
		return e?(e+="",null==t.regex||-1===(n=e.search(t.regex))?0:(s=t.string.length/e.length,0===n&&(s+=.5),s*i)):0},L=(e,t)=>{var i=e[t];
		if("function"==typeof i)return i
		i&&!Array.isArray(i)&&(e[t]=[i]);},E=(e,t)=>{if(Array.isArray(e))e.forEach(t);
		else for(var i in e)e.hasOwnProperty(i)&&t(e[i],i);},P=(e,t)=>"number"==typeof e&&"number"==typeof t?e>t?1:e<t?-1:0:(e=y(e+"").toLowerCase())>(t=y(t+"").toLowerCase())?1:t>e?-1:0;
		class T{constructor(e,t){this.items=void 0,this.settings=void 0,this.items=e,this.settings=t||{diacritics:!0};}tokenize(e,t,i){if(!e||!e.length)return []
		const s=[],n=e.split(/\s+/);
		var o;
		return i&&(o=new RegExp("^("+Object.keys(i).map(r).join("|")+"):(.*)$")),n.forEach((e=>{let i,n=null,l=null;
		o&&(i=e.match(o))&&(n=i[1],e=i[2]),e.length>0&&(l=this.settings.diacritics?A(e)||null:r(e),l&&t&&(l="\\b"+l)),s.push({string:e,regex:l?new RegExp(l,"iu"):null,field:n});})),s}getScoreFunction(e,t){var i=this.prepareSearch(e,t);
		return this._getScoreFunction(i)}_getScoreFunction(e){const t=e.tokens,i=t.length;
		if(!i)return function(){return 0}
		const s=e.options.fields,n=e.weights,o=s.length,r=e.getAttrFn;
		if(!o)return function(){return 1}
		const l=1===o?function(e,t){const i=s[0].field;
		return F(r(t,i),e,n[i]||1)}:function(e,t){var i=0;
		if(e.field){const s=r(t,e.field);
		!e.regex&&s?i+=1/o:i+=F(s,e,1);}else E(n,((s,n)=>{i+=F(r(t,n),e,s);}));
		return i/o};
		return 1===i?function(e){return l(t[0],e)}:"and"===e.options.conjunction?function(e){var s,n=0;
		for(let i of t){if((s=l(i,e))<=0)return 0
		n+=s;}return n/i}:function(e){var s=0;
		return E(t,(t=>{s+=l(t,e);})),s/i}}getSortFunction(e,t){var i=this.prepareSearch(e,t);
		return this._getSortFunction(i)}_getSortFunction(e){var t,i=[];
		const s=this,n=e.options,o=!e.query&&n.sort_empty?n.sort_empty:n.sort;
		if("function"==typeof o)return o.bind(this)
		const r=function(t,i){return "$score"===t?i.score:e.getAttrFn(s.items[i.id],t)};
		if(o)for(let t of o)(e.query||"$score"!==t.field)&&i.push(t);
		if(e.query){t=!0;
		for(let e of i)if("$score"===e.field){t=!1;
		break}t&&i.unshift({field:"$score",direction:"desc"});}else i=i.filter((e=>"$score"!==e.field));
		return i.length?function(e,t){var s,n;
		for(let o of i){if(n=o.field,s=("desc"===o.direction?-1:1)*P(r(n,e),r(n,t)))return s}return 0}:null}prepareSearch(e,t){const i={};
		var s=Object.assign({},t);
		if(L(s,"sort"),L(s,"sort_empty"),s.fields){L(s,"fields");
		const e=[];
		s.fields.forEach((t=>{"string"==typeof t&&(t={field:t,weight:1}),e.push(t),i[t.field]="weight"in t?t.weight:1;})),s.fields=e;}return {options:s,query:e.toLowerCase().trim(),tokens:this.tokenize(e,s.respect_word_boundaries,i),total:0,items:[],weights:i,getAttrFn:s.nesting?k:x}}search(e,t){var i,s,n=this;
		s=this.prepareSearch(e,t),t=s.options,e=s.query;
		const o=t.score||n._getScoreFunction(s);
		e.length?E(n.items,((e,n)=>{i=o(e),(!1===t.filter||i>0)&&s.items.push({score:i,id:n});})):E(n.items,((e,t)=>{s.items.push({score:1,id:t});}));
		const r=n._getSortFunction(s);
		return r&&s.items.sort(r),s.total=s.items.length,"number"==typeof t.limit&&(s.items=s.items.slice(0,t.limit)),s}}const j=(e,t)=>{if(Array.isArray(e))e.forEach(t);
		else for(var i in e)e.hasOwnProperty(i)&&t(e[i],i);},V=e=>{if(e.jquery)return e[0]
		if(e instanceof HTMLElement)return e
		if(q(e)){var t=document.createElement("template");
		return t.innerHTML=e.trim(),t.content.firstChild}return document.querySelector(e)},q=e=>"string"==typeof e&&e.indexOf("<")>-1,D=(e,t)=>{var i=document.createEvent("HTMLEvents");
		i.initEvent(t,!0,!1),e.dispatchEvent(i);},N=(e,t)=>{Object.assign(e.style,t);},H=(e,...t)=>{var i=M(t);(e=R(e)).map((e=>{i.map((t=>{e.classList.add(t);}));}));},z=(e,...t)=>{var i=M(t);(e=R(e)).map((e=>{i.map((t=>{e.classList.remove(t);}));}));},M=e=>{var t=[];
		return j(e,(e=>{"string"==typeof e&&(e=e.trim().split(/[\11\12\14\15\40]/)),Array.isArray(e)&&(t=t.concat(e));})),t.filter(Boolean)},R=e=>(Array.isArray(e)||(e=[e]),e),B=(e,t,i)=>{if(!i||i.contains(e))for(;e&&e.matches;){if(e.matches(t))return e
		e=e.parentNode;}},K=(e,t=0)=>t>0?e[e.length-1]:e[0],Q=(e,t)=>{if(!e)return -1
		t=t||e.nodeName;
		for(var i=0;e=e.previousElementSibling;)e.matches(t)&&i++;
		return i},G=(e,t)=>{j(t,((t,i)=>{null==t?e.removeAttribute(i):e.setAttribute(i,""+t);}));},U=(e,t)=>{e.parentNode&&e.parentNode.replaceChild(t,e);},J=(e,t)=>{if(null===t)return
		if("string"==typeof t){if(!t.length)return
		t=new RegExp(t,"i");}const i=e=>3===e.nodeType?(e=>{var i=e.data.match(t);
		if(i&&e.data.length>0){var s=document.createElement("span");
		s.className="highlight";
		var n=e.splitText(i.index);
		n.splitText(i[0].length);
		var o=n.cloneNode(!0);
		return s.appendChild(o),U(n,s),1}return 0})(e):((e=>{1!==e.nodeType||!e.childNodes||/(script|style)/i.test(e.tagName)||"highlight"===e.className&&"SPAN"===e.tagName||Array.from(e.childNodes).forEach((e=>{i(e);}));})(e),0);
		i(e);},W="undefined"!=typeof navigator&&/Mac/.test(navigator.userAgent)?"metaKey":"ctrlKey";
		var X={options:[],optgroups:[],plugins:[],delimiter:",",splitOn:null,persist:!0,diacritics:!0,create:null,createOnBlur:!1,createFilter:null,highlight:!0,openOnFocus:!0,shouldOpen:null,maxOptions:50,maxItems:null,hideSelected:null,duplicates:!1,addPrecedence:!1,selectOnTab:!1,preload:null,allowEmptyOption:!1,loadThrottle:300,loadingClass:"loading",dataAttr:null,optgroupField:"optgroup",valueField:"value",labelField:"text",disabledField:"disabled",optgroupLabelField:"label",optgroupValueField:"value",lockOptgroupOrder:!1,sortField:"$order",searchField:["text"],searchConjunction:"and",mode:null,wrapperClass:"ts-wrapper",controlClass:"ts-control",dropdownClass:"ts-dropdown",dropdownContentClass:"ts-dropdown-content",itemClass:"item",optionClass:"option",dropdownParent:null,controlInput:'<input type="text" autocomplete="off" size="1" />',copyClassesToDropdown:!1,placeholder:null,hidePlaceholder:null,shouldLoad:function(e){return e.length>0},render:{}};
		const Y=e=>null==e?null:Z(e),Z=e=>"boolean"==typeof e?e?"1":"0":e+"",ee=e=>(e+"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;"),te=(e,t)=>{var i;
		return function(s,n){var o=this;
		i&&(o.loading=Math.max(o.loading-1,0),clearTimeout(i)),i=setTimeout((function(){i=null,o.loadedSearches[s]=!0,e.call(o,s,n);}),t);}},ie=(e,t,i)=>{var s,n=e.trigger,o={};
		for(s of(e.trigger=function(){var i=arguments[0];
		if(-1===t.indexOf(i))return n.apply(e,arguments)
		o[i]=arguments;},i.apply(e,[]),e.trigger=n,t))s in o&&n.apply(e,o[s]);},se=(e,t=!1)=>{e&&(e.preventDefault(),t&&e.stopPropagation());},ne=(e,t,i,s)=>{e.addEventListener(t,i,s);},oe=(e,t)=>!!t&&(!!t[e]&&1===(t.altKey?1:0)+(t.ctrlKey?1:0)+(t.shiftKey?1:0)+(t.metaKey?1:0)),re=(e,t)=>{const i=e.getAttribute("id");
		return i||(e.setAttribute("id",t),t)},le=e=>e.replace(/[\\"']/g,"\\$&"),ae=(e,t)=>{t&&e.append(t);};
		function ce(e,t){var i=Object.assign({},X,t),s=i.dataAttr,n=i.labelField,o=i.valueField,r=i.disabledField,l=i.optgroupField,a=i.optgroupLabelField,c=i.optgroupValueField,d=e.tagName.toLowerCase(),u=e.getAttribute("placeholder")||e.getAttribute("data-placeholder");
		if(!u&&!i.allowEmptyOption){let t=e.querySelector('option[value=""]');
		t&&(u=t.textContent);}var p,h,g,f,v,m,y={placeholder:u,options:[],optgroups:[],items:[],maxItems:null};
		return "select"===d?(h=y.options,g={},f=1,v=e=>{var t=Object.assign({},e.dataset),i=s&&t[s];
		return "string"==typeof i&&i.length&&(t=Object.assign(t,JSON.parse(i))),t},m=(e,t)=>{var s=Y(e.value);
		if(null!=s&&(s||i.allowEmptyOption)){if(g.hasOwnProperty(s)){if(t){var a=g[s][l];
		a?Array.isArray(a)?a.push(t):g[s][l]=[a,t]:g[s][l]=t;}}else {var c=v(e);
		c[n]=c[n]||e.textContent,c[o]=c[o]||s,c[r]=c[r]||e.disabled,c[l]=c[l]||t,c.$option=e,g[s]=c,h.push(c);}e.selected&&y.items.push(s);}},y.maxItems=e.hasAttribute("multiple")?null:1,j(e.children,(e=>{var t,i,s;
		"optgroup"===(p=e.tagName.toLowerCase())?((s=v(t=e))[a]=s[a]||t.getAttribute("label")||"",s[c]=s[c]||f++,s[r]=s[r]||t.disabled,y.optgroups.push(s),i=s[c],j(t.children,(e=>{m(e,i);}))):"option"===p&&m(e);}))):(()=>{const t=e.getAttribute(s);
		if(t)y.options=JSON.parse(t),j(y.options,(e=>{y.items.push(e[o]);}));
		else {var r=e.value.trim()||"";
		if(!i.allowEmptyOption&&!r.length)return
		const t=r.split(i.delimiter);
		j(t,(e=>{const t={};
		t[n]=e,t[o]=e,y.options.push(t);})),y.items=t;}})(),Object.assign({},X,y,t)}var de=0;
		class ue extends(function(e){return e.plugins={},class extends e{constructor(...e){super(...e),this.plugins={names:[],settings:{},requested:{},loaded:{}};}static define(t,i){e.plugins[t]={name:t,fn:i};}initializePlugins(e){var t,i;
		const s=this,n=[];
		if(Array.isArray(e))e.forEach((e=>{"string"==typeof e?n.push(e):(s.plugins.settings[e.name]=e.options,n.push(e.name));}));
		else if(e)for(t in e)e.hasOwnProperty(t)&&(s.plugins.settings[t]=e[t],n.push(t));
		for(;i=n.shift();)s.require(i);}loadPlugin(t){var i=this,s=i.plugins,n=e.plugins[t];
		if(!e.plugins.hasOwnProperty(t))throw new Error('Unable to find "'+t+'" plugin')
		s.requested[t]=!0,s.loaded[t]=n.fn.apply(i,[i.plugins.settings[t]||{}]),s.names.push(t);}require(e){var t=this,i=t.plugins;
		if(!t.plugins.loaded.hasOwnProperty(e)){if(i.requested[e])throw new Error('Plugin has circular dependency ("'+e+'")')
		t.loadPlugin(e);}return i.loaded[e]}}}(t)){constructor(e,t){var i;
		super(),this.control_input=void 0,this.wrapper=void 0,this.dropdown=void 0,this.control=void 0,this.dropdown_content=void 0,this.focus_node=void 0,this.order=0,this.settings=void 0,this.input=void 0,this.tabIndex=void 0,this.is_select_tag=void 0,this.rtl=void 0,this.inputId=void 0,this._destroy=void 0,this.sifter=void 0,this.isOpen=!1,this.isDisabled=!1,this.isRequired=void 0,this.isInvalid=!1,this.isValid=!0,this.isLocked=!1,this.isFocused=!1,this.isInputHidden=!1,this.isSetup=!1,this.ignoreFocus=!1,this.ignoreHover=!1,this.hasOptions=!1,this.currentResults=void 0,this.lastValue="",this.caretPos=0,this.loading=0,this.loadedSearches={},this.activeOption=null,this.activeItems=[],this.optgroups={},this.options={},this.userOptions={},this.items=[],de++;
		var s=V(e);
		if(s.tomselect)throw new Error("Tom Select already initialized on this element")
		s.tomselect=this,i=(window.getComputedStyle&&window.getComputedStyle(s,null)).getPropertyValue("direction");
		const n=ce(s,t);
		this.settings=n,this.input=s,this.tabIndex=s.tabIndex||0,this.is_select_tag="select"===s.tagName.toLowerCase(),this.rtl=/rtl/i.test(i),this.inputId=re(s,"tomselect-"+de),this.isRequired=s.required,this.sifter=new T(this.options,{diacritics:n.diacritics}),n.mode=n.mode||(1===n.maxItems?"single":"multi"),"boolean"!=typeof n.hideSelected&&(n.hideSelected="multi"===n.mode),"boolean"!=typeof n.hidePlaceholder&&(n.hidePlaceholder="multi"!==n.mode);
		var o=n.createFilter;
		"function"!=typeof o&&("string"==typeof o&&(o=new RegExp(o)),o instanceof RegExp?n.createFilter=e=>o.test(e):n.createFilter=e=>this.settings.duplicates||!this.options[e]),this.initializePlugins(n.plugins),this.setupCallbacks(),this.setupTemplates();
		const r=V("<div>"),l=V("<div>"),a=this._render("dropdown"),c=V('<div role="listbox" tabindex="-1">'),d=this.input.getAttribute("class")||"",u=n.mode;
		var p;
		if(H(r,n.wrapperClass,d,u),H(l,n.controlClass),ae(r,l),H(a,n.dropdownClass,u),n.copyClassesToDropdown&&H(a,d),H(c,n.dropdownContentClass),ae(a,c),V(n.dropdownParent||r).appendChild(a),q(n.controlInput)){p=V(n.controlInput);
		E(["autocorrect","autocapitalize","autocomplete"],(e=>{s.getAttribute(e)&&G(p,{[e]:s.getAttribute(e)});})),p.tabIndex=-1,l.appendChild(p),this.focus_node=p;}else n.controlInput?(p=V(n.controlInput),this.focus_node=p):(p=V("<input/>"),this.focus_node=l);
		this.wrapper=r,this.dropdown=a,this.dropdown_content=c,this.control=l,this.control_input=p,this.setup();}setup(){const e=this,t=e.settings,i=e.control_input,s=e.dropdown,n=e.dropdown_content,o=e.wrapper,l=e.control,a=e.input,c=e.focus_node,d={passive:!0},u=e.inputId+"-ts-dropdown";
		G(n,{id:u}),G(c,{role:"combobox","aria-haspopup":"listbox","aria-expanded":"false","aria-controls":u});
		const p=re(c,e.inputId+"-ts-control"),h="label[for='"+(e=>e.replace(/['"\\]/g,"\\$&"))(e.inputId)+"']",g=document.querySelector(h),f=e.focus.bind(e);
		if(g){ne(g,"click",f),G(g,{for:p});
		const t=re(g,e.inputId+"-ts-label");
		G(c,{"aria-labelledby":t}),G(n,{"aria-labelledby":t});}if(o.style.width=a.style.width,e.plugins.names.length){const t="plugin-"+e.plugins.names.join(" plugin-");
		H([o,s],t);}(null===t.maxItems||t.maxItems>1)&&e.is_select_tag&&G(a,{multiple:"multiple"}),t.placeholder&&G(i,{placeholder:t.placeholder}),!t.splitOn&&t.delimiter&&(t.splitOn=new RegExp("\\s*"+r(t.delimiter)+"+\\s*")),t.load&&t.loadThrottle&&(t.load=te(t.load,t.loadThrottle)),e.control_input.type=a.type,ne(s,"mousemove",(()=>{e.ignoreHover=!1;})),ne(s,"mouseenter",(t=>{var i=B(t.target,"[data-selectable]",s);
		i&&e.onOptionHover(t,i);}),{capture:!0}),ne(s,"click",(t=>{const i=B(t.target,"[data-selectable]");
		i&&(e.onOptionSelect(t,i),se(t,!0));})),ne(l,"click",(t=>{var s=B(t.target,"[data-ts-item]",l);
		s&&e.onItemSelect(t,s)?se(t,!0):""==i.value&&(e.onClick(),se(t,!0));})),ne(c,"keydown",(t=>e.onKeyDown(t))),ne(i,"keypress",(t=>e.onKeyPress(t))),ne(i,"input",(t=>e.onInput(t))),ne(c,"blur",(t=>e.onBlur(t))),ne(c,"focus",(t=>e.onFocus(t))),ne(i,"paste",(t=>e.onPaste(t)));
		const v=t=>{const n=t.composedPath()[0];
		if(!o.contains(n)&&!s.contains(n))return e.isFocused&&e.blur(),void e.inputState()
		n==i&&e.isOpen?t.stopPropagation():se(t,!0);},m=()=>{e.isOpen&&e.positionDropdown();};
		ne(document,"mousedown",v),ne(window,"scroll",m,d),ne(window,"resize",m,d),this._destroy=()=>{document.removeEventListener("mousedown",v),window.removeEventListener("scroll",m),window.removeEventListener("resize",m),g&&g.removeEventListener("click",f);},this.revertSettings={innerHTML:a.innerHTML,tabIndex:a.tabIndex},a.tabIndex=-1,a.insertAdjacentElement("afterend",e.wrapper),e.sync(!1),t.items=[],delete t.optgroups,delete t.options,ne(a,"invalid",(()=>{e.isValid&&(e.isValid=!1,e.isInvalid=!0,e.refreshState());})),e.updateOriginalInput(),e.refreshItems(),e.close(!1),e.inputState(),e.isSetup=!0,a.disabled?e.disable():e.enable(),e.on("change",this.onChange),H(a,"tomselected","ts-hidden-accessible"),e.trigger("initialize"),!0===t.preload&&e.preload();}setupOptions(e=[],t=[]){this.addOptions(e),E(t,(e=>{this.registerOptionGroup(e);}));}setupTemplates(){var e=this,t=e.settings.labelField,i=e.settings.optgroupLabelField,s={optgroup:e=>{let t=document.createElement("div");
		return t.className="optgroup",t.appendChild(e.options),t},optgroup_header:(e,t)=>'<div class="optgroup-header">'+t(e[i])+"</div>",option:(e,i)=>"<div>"+i(e[t])+"</div>",item:(e,i)=>"<div>"+i(e[t])+"</div>",option_create:(e,t)=>'<div class="create">Add <strong>'+t(e.input)+"</strong>&hellip;</div>",no_results:()=>'<div class="no-results">No results found</div>',loading:()=>'<div class="spinner"></div>',not_loading:()=>{},dropdown:()=>"<div></div>"};
		e.settings.render=Object.assign({},s,e.settings.render);}setupCallbacks(){var e,t,i={initialize:"onInitialize",change:"onChange",item_add:"onItemAdd",item_remove:"onItemRemove",item_select:"onItemSelect",clear:"onClear",option_add:"onOptionAdd",option_remove:"onOptionRemove",option_clear:"onOptionClear",optgroup_add:"onOptionGroupAdd",optgroup_remove:"onOptionGroupRemove",optgroup_clear:"onOptionGroupClear",dropdown_open:"onDropdownOpen",dropdown_close:"onDropdownClose",type:"onType",load:"onLoad",focus:"onFocus",blur:"onBlur"};
		for(e in i)(t=this.settings[i[e]])&&this.on(e,t);}sync(e=!0){const t=this,i=e?ce(t.input,{delimiter:t.settings.delimiter}):t.settings;
		t.setupOptions(i.options,i.optgroups),t.setValue(i.items||[],!0),t.lastQuery=null;}onClick(){var e=this;
		if(e.activeItems.length>0)return e.clearActiveItems(),void e.focus()
		e.isFocused&&e.isOpen?e.blur():e.focus();}onMouseDown(){}onChange(){D(this.input,"input"),D(this.input,"change");}onPaste(e){var t=this;
		t.isInputHidden||t.isLocked?se(e):t.settings.splitOn&&setTimeout((()=>{var e=t.inputValue();
		if(e.match(t.settings.splitOn)){var i=e.trim().split(t.settings.splitOn);
		E(i,(e=>{Y(e)&&(this.options[e]?t.addItem(e):t.createItem(e));}));}}),0);}onKeyPress(e){var t=this;
		if(!t.isLocked){var i=String.fromCharCode(e.keyCode||e.which);
		return t.settings.create&&"multi"===t.settings.mode&&i===t.settings.delimiter?(t.createItem(),void se(e)):void 0}se(e);}onKeyDown(e){var t=this;
		if(t.ignoreHover=!0,t.isLocked)9!==e.keyCode&&se(e);
		else {switch(e.keyCode){case 65:if(oe(W,e)&&""==t.control_input.value)return se(e),void t.selectAll()
		break
		case 27:return t.isOpen&&(se(e,!0),t.close()),void t.clearActiveItems()
		case 40:if(!t.isOpen&&t.hasOptions)t.open();
		else if(t.activeOption){let e=t.getAdjacent(t.activeOption,1);
		e&&t.setActiveOption(e);}return void se(e)
		case 38:if(t.activeOption){let e=t.getAdjacent(t.activeOption,-1);
		e&&t.setActiveOption(e);}return void se(e)
		case 13:return void(t.canSelect(t.activeOption)?(t.onOptionSelect(e,t.activeOption),se(e)):(t.settings.create&&t.createItem()||document.activeElement==t.control_input&&t.isOpen)&&se(e))
		case 37:return void t.advanceSelection(-1,e)
		case 39:return void t.advanceSelection(1,e)
		case 9:return void(t.settings.selectOnTab&&(t.canSelect(t.activeOption)&&(t.onOptionSelect(e,t.activeOption),se(e)),t.settings.create&&t.createItem()&&se(e)))
		case 8:case 46:return void t.deleteSelection(e)}t.isInputHidden&&!oe(W,e)&&se(e);}}onInput(e){var t=this;
		if(!t.isLocked){var i=t.inputValue();
		t.lastValue!==i&&(t.lastValue=i,t.settings.shouldLoad.call(t,i)&&t.load(i),t.refreshOptions(),t.trigger("type",i));}}onOptionHover(e,t){this.ignoreHover||this.setActiveOption(t,!1);}onFocus(e){var t=this,i=t.isFocused;
		if(t.isDisabled)return t.blur(),void se(e)
		t.ignoreFocus||(t.isFocused=!0,"focus"===t.settings.preload&&t.preload(),i||t.trigger("focus"),t.activeItems.length||(t.showInput(),t.refreshOptions(!!t.settings.openOnFocus)),t.refreshState());}onBlur(e){if(!1!==document.hasFocus()){var t=this;
		if(t.isFocused){t.isFocused=!1,t.ignoreFocus=!1;
		var i=()=>{t.close(),t.setActiveItem(),t.setCaret(t.items.length),t.trigger("blur");};
		t.settings.create&&t.settings.createOnBlur?t.createItem(null,i):i();}}}onOptionSelect(e,t){var i,s=this;
		t.parentElement&&t.parentElement.matches("[data-disabled]")||(t.classList.contains("create")?s.createItem(null,(()=>{s.settings.closeAfterSelect&&s.close();})):void 0!==(i=t.dataset.value)&&(s.lastQuery=null,s.addItem(i),s.settings.closeAfterSelect&&s.close(),!s.settings.hideSelected&&e.type&&/click/.test(e.type)&&s.setActiveOption(t)));}canSelect(e){return !!(this.isOpen&&e&&this.dropdown_content.contains(e))}onItemSelect(e,t){var i=this;
		return !i.isLocked&&"multi"===i.settings.mode&&(se(e),i.setActiveItem(t,e),!0)}canLoad(e){return !!this.settings.load&&!this.loadedSearches.hasOwnProperty(e)}load(e){const t=this;
		if(!t.canLoad(e))return
		H(t.wrapper,t.settings.loadingClass),t.loading++;
		const i=t.loadCallback.bind(t);
		t.settings.load.call(t,e,i);}loadCallback(e,t){const i=this;
		i.loading=Math.max(i.loading-1,0),i.lastQuery=null,i.clearActiveOption(),i.setupOptions(e,t),i.refreshOptions(i.isFocused&&!i.isInputHidden),i.loading||z(i.wrapper,i.settings.loadingClass),i.trigger("load",e,t);}preload(){var e=this.wrapper.classList;
		e.contains("preloaded")||(e.add("preloaded"),this.load(""));}setTextboxValue(e=""){var t=this.control_input;
		t.value!==e&&(t.value=e,D(t,"update"),this.lastValue=e);}getValue(){return this.is_select_tag&&this.input.hasAttribute("multiple")?this.items:this.items.join(this.settings.delimiter)}setValue(e,t){ie(this,t?[]:["change"],(()=>{this.clear(t),this.addItems(e,t);}));}setMaxItems(e){0===e&&(e=null),this.settings.maxItems=e,this.refreshState();}setActiveItem(e,t){var i,s,n,o,r,l,a=this;
		if("single"!==a.settings.mode){if(!e)return a.clearActiveItems(),void(a.isFocused&&a.showInput())
		if("click"===(i=t&&t.type.toLowerCase())&&oe("shiftKey",t)&&a.activeItems.length){for(l=a.getLastActive(),(n=Array.prototype.indexOf.call(a.control.children,l))>(o=Array.prototype.indexOf.call(a.control.children,e))&&(r=n,n=o,o=r),s=n;s<=o;s++)e=a.control.children[s],-1===a.activeItems.indexOf(e)&&a.setActiveItemClass(e);
		se(t);}else "click"===i&&oe(W,t)||"keydown"===i&&oe("shiftKey",t)?e.classList.contains("active")?a.removeActiveItem(e):a.setActiveItemClass(e):(a.clearActiveItems(),a.setActiveItemClass(e));
		a.hideInput(),a.isFocused||a.focus();}}setActiveItemClass(e){const t=this,i=t.control.querySelector(".last-active");
		i&&z(i,"last-active"),H(e,"active last-active"),t.trigger("item_select",e),-1==t.activeItems.indexOf(e)&&t.activeItems.push(e);}removeActiveItem(e){var t=this.activeItems.indexOf(e);
		this.activeItems.splice(t,1),z(e,"active");}clearActiveItems(){z(this.activeItems,"active"),this.activeItems=[];}setActiveOption(e,t=!0){e!==this.activeOption&&(this.clearActiveOption(),e&&(this.activeOption=e,G(this.focus_node,{"aria-activedescendant":e.getAttribute("id")}),G(e,{"aria-selected":"true"}),H(e,"active"),t&&this.scrollToOption(e)));}scrollToOption(e,t){if(!e)return
		const i=this.dropdown_content,s=i.clientHeight,n=i.scrollTop||0,o=e.offsetHeight,r=e.getBoundingClientRect().top-i.getBoundingClientRect().top+n;
		r+o>s+n?this.scroll(r-s+o,t):r<n&&this.scroll(r,t);}scroll(e,t){const i=this.dropdown_content;
		t&&(i.style.scrollBehavior=t),i.scrollTop=e,i.style.scrollBehavior="";}clearActiveOption(){this.activeOption&&(z(this.activeOption,"active"),G(this.activeOption,{"aria-selected":null})),this.activeOption=null,G(this.focus_node,{"aria-activedescendant":null});}selectAll(){const e=this;
		if("single"===e.settings.mode)return
		const t=e.controlChildren();
		t.length&&(e.hideInput(),e.close(),e.activeItems=t,E(t,(t=>{e.setActiveItemClass(t);})));}inputState(){var e=this;
		e.control.contains(e.control_input)&&(G(e.control_input,{placeholder:e.settings.placeholder}),e.activeItems.length>0||!e.isFocused&&e.settings.hidePlaceholder&&e.items.length>0?(e.setTextboxValue(),e.isInputHidden=!0):(e.settings.hidePlaceholder&&e.items.length>0&&G(e.control_input,{placeholder:""}),e.isInputHidden=!1),e.wrapper.classList.toggle("input-hidden",e.isInputHidden));}hideInput(){this.inputState();}showInput(){this.inputState();}inputValue(){return this.control_input.value.trim()}focus(){var e=this;
		e.isDisabled||(e.ignoreFocus=!0,e.control_input.offsetWidth?e.control_input.focus():e.focus_node.focus(),setTimeout((()=>{e.ignoreFocus=!1,e.onFocus();}),0));}blur(){this.focus_node.blur(),this.onBlur();}getScoreFunction(e){return this.sifter.getScoreFunction(e,this.getSearchOptions())}getSearchOptions(){var e=this.settings,t=e.sortField;
		return "string"==typeof e.sortField&&(t=[{field:e.sortField}]),{fields:e.searchField,conjunction:e.searchConjunction,sort:t,nesting:e.nesting}}search(e){var t,i,s=this,n=this.getSearchOptions();
		if(s.settings.score&&"function"!=typeof(i=s.settings.score.call(s,e)))throw new Error('Tom Select "score" setting must be a function that returns a function')
		return e!==s.lastQuery?(s.lastQuery=e,t=s.sifter.search(e,Object.assign(n,{score:i})),s.currentResults=t):t=Object.assign({},s.currentResults),s.settings.hideSelected&&(t.items=t.items.filter((e=>{let t=Y(e.id);
		return !(t&&-1!==s.items.indexOf(t))}))),t}refreshOptions(e=!0){var t,i,s,n,o,r,l,a,c,d;
		const u={},p=[];
		var h=this,g=h.inputValue();
		const f=g===h.lastQuery||""==g&&null==h.lastQuery;
		var v,m=h.search(g),y=null,b=h.settings.shouldOpen||!1,O=h.dropdown_content;
		for(f&&(y=h.activeOption)&&(c=y.closest("[data-group]")),n=m.items.length,"number"==typeof h.settings.maxOptions&&(n=Math.min(n,h.settings.maxOptions)),n>0&&(b=!0),t=0;t<n;t++){let e=m.items[t];
		if(!e)continue
		let n=e.id,l=h.options[n];
		if(void 0===l)continue
		let a=Z(n),d=h.getOption(a,!0);
		for(h.settings.hideSelected||d.classList.toggle("selected",h.items.includes(a)),o=l[h.settings.optgroupField]||"",i=0,s=(r=Array.isArray(o)?o:[o])&&r.length;i<s;i++){o=r[i],h.optgroups.hasOwnProperty(o)||(o="");
		let e=u[o];
		void 0===e&&(e=document.createDocumentFragment(),p.push(o)),i>0&&(d=d.cloneNode(!0),G(d,{id:l.$id+"-clone-"+i,"aria-selected":null}),d.classList.add("ts-cloned"),z(d,"active"),h.activeOption&&h.activeOption.dataset.value==n&&c&&c.dataset.group===o.toString()&&(y=d)),e.appendChild(d),u[o]=e;}}h.settings.lockOptgroupOrder&&p.sort(((e,t)=>{const i=h.optgroups[e],s=h.optgroups[t];
		return (i&&i.$order||0)-(s&&s.$order||0)})),l=document.createDocumentFragment(),E(p,(e=>{let t=u[e];
		if(!t||!t.children.length)return
		let i=h.optgroups[e];
		if(void 0!==i){let e=document.createDocumentFragment(),s=h.render("optgroup_header",i);
		ae(e,s),ae(e,t);
		let n=h.render("optgroup",{group:i,options:e});
		ae(l,n);}else ae(l,t);})),O.innerHTML="",ae(O,l),h.settings.highlight&&(v=O.querySelectorAll("span.highlight"),Array.prototype.forEach.call(v,(function(e){var t=e.parentNode;
		t.replaceChild(e.firstChild,e),t.normalize();})),m.query.length&&m.tokens.length&&E(m.tokens,(e=>{J(O,e.regex);})));
		var w=e=>{let t=h.render(e,{input:g});
		return t&&(b=!0,O.insertBefore(t,O.firstChild)),t};
		if(h.loading?w("loading"):h.settings.shouldLoad.call(h,g)?0===m.items.length&&w("no_results"):w("not_loading"),(a=h.canCreate(g))&&(d=w("option_create")),h.hasOptions=m.items.length>0||a,b){if(m.items.length>0){if(y||"single"!==h.settings.mode||null==h.items[0]||(y=h.getOption(h.items[0])),!O.contains(y)){let e=0;
		d&&!h.settings.addPrecedence&&(e=1),y=h.selectable()[e];}}else d&&(y=d);
		e&&!h.isOpen&&(h.open(),h.scrollToOption(y,"auto")),h.setActiveOption(y);}else h.clearActiveOption(),e&&h.isOpen&&h.close(!1);}selectable(){return this.dropdown_content.querySelectorAll("[data-selectable]")}addOption(e,t=!1){const i=this;
		if(Array.isArray(e))return i.addOptions(e,t),!1
		const s=Y(e[i.settings.valueField]);
		return null!==s&&!i.options.hasOwnProperty(s)&&(e.$order=e.$order||++i.order,e.$id=i.inputId+"-opt-"+e.$order,i.options[s]=e,i.lastQuery=null,t&&(i.userOptions[s]=t,i.trigger("option_add",s,e)),s)}addOptions(e,t=!1){E(e,(e=>{this.addOption(e,t);}));}registerOption(e){return this.addOption(e)}registerOptionGroup(e){var t=Y(e[this.settings.optgroupValueField]);
		return null!==t&&(e.$order=e.$order||++this.order,this.optgroups[t]=e,t)}addOptionGroup(e,t){var i;
		t[this.settings.optgroupValueField]=e,(i=this.registerOptionGroup(t))&&this.trigger("optgroup_add",i,t);}removeOptionGroup(e){this.optgroups.hasOwnProperty(e)&&(delete this.optgroups[e],this.clearCache(),this.trigger("optgroup_remove",e));}clearOptionGroups(){this.optgroups={},this.clearCache(),this.trigger("optgroup_clear");}updateOption(e,t){const i=this;
		var s,n;
		const o=Y(e),r=Y(t[i.settings.valueField]);
		if(null===o)return
		const l=i.options[o];
		if(null==l)return
		if("string"!=typeof r)throw new Error("Value must be set in option data")
		const a=i.getOption(o),c=i.getItem(o);
		if(t.$order=t.$order||l.$order,delete i.options[o],i.uncacheValue(r),i.options[r]=t,a){if(i.dropdown_content.contains(a)){const e=i._render("option",t);
		U(a,e),i.activeOption===a&&i.setActiveOption(e);}a.remove();}c&&(-1!==(n=i.items.indexOf(o))&&i.items.splice(n,1,r),s=i._render("item",t),c.classList.contains("active")&&H(s,"active"),U(c,s)),i.lastQuery=null;}removeOption(e,t){const i=this;
		e=Z(e),i.uncacheValue(e),delete i.userOptions[e],delete i.options[e],i.lastQuery=null,i.trigger("option_remove",e),i.removeItem(e,t);}clearOptions(e){const t=(e||this.clearFilter).bind(this);
		this.loadedSearches={},this.userOptions={},this.clearCache();
		const i={};
		E(this.options,((e,s)=>{t(e,s)&&(i[s]=e);})),this.options=this.sifter.items=i,this.lastQuery=null,this.trigger("option_clear");}clearFilter(e,t){return this.items.indexOf(t)>=0}getOption(e,t=!1){const i=Y(e);
		if(null===i)return null
		const s=this.options[i];
		if(null!=s){if(s.$div)return s.$div
		if(t)return this._render("option",s)}return null}getAdjacent(e,t,i="option"){var s;
		if(!e)return null
		s="item"==i?this.controlChildren():this.dropdown_content.querySelectorAll("[data-selectable]");
		for(let i=0;i<s.length;i++)if(s[i]==e)return t>0?s[i+1]:s[i-1]
		return null}getItem(e){if("object"==typeof e)return e
		var t=Y(e);
		return null!==t?this.control.querySelector(`[data-value="${le(t)}"]`):null}addItems(e,t){var i=this,s=Array.isArray(e)?e:[e];
		const n=(s=s.filter((e=>-1===i.items.indexOf(e))))[s.length-1];
		s.forEach((e=>{i.isPending=e!==n,i.addItem(e,t);}));}addItem(e,t){ie(this,t?[]:["change","dropdown_close"],(()=>{var i,s;
		const n=this,o=n.settings.mode,r=Y(e);
		if((!r||-1===n.items.indexOf(r)||("single"===o&&n.close(),"single"!==o&&n.settings.duplicates))&&null!==r&&n.options.hasOwnProperty(r)&&("single"===o&&n.clear(t),"multi"!==o||!n.isFull())){if(i=n._render("item",n.options[r]),n.control.contains(i)&&(i=i.cloneNode(!0)),s=n.isFull(),n.items.splice(n.caretPos,0,r),n.insertAtCaret(i),n.isSetup){if(!n.isPending&&n.settings.hideSelected){let e=n.getOption(r),t=n.getAdjacent(e,1);
		t&&n.setActiveOption(t);}n.isPending||n.settings.closeAfterSelect||n.refreshOptions(n.isFocused&&"single"!==o),0!=n.settings.closeAfterSelect&&n.isFull()?n.close():n.isPending||n.positionDropdown(),n.trigger("item_add",r,i),n.isPending||n.updateOriginalInput({silent:t});}(!n.isPending||!s&&n.isFull())&&(n.inputState(),n.refreshState());}}));}removeItem(e=null,t){const i=this;
		if(!(e=i.getItem(e)))return
		var s,n;
		const o=e.dataset.value;
		s=Q(e),e.remove(),e.classList.contains("active")&&(n=i.activeItems.indexOf(e),i.activeItems.splice(n,1),z(e,"active")),i.items.splice(s,1),i.lastQuery=null,!i.settings.persist&&i.userOptions.hasOwnProperty(o)&&i.removeOption(o,t),s<i.caretPos&&i.setCaret(i.caretPos-1),i.updateOriginalInput({silent:t}),i.refreshState(),i.positionDropdown(),i.trigger("item_remove",o,e);}createItem(e=null,t=(()=>{})){3===arguments.length&&(t=arguments[2]),"function"!=typeof t&&(t=()=>{});
		var i,s=this,n=s.caretPos;
		if(e=e||s.inputValue(),!s.canCreate(e))return t(),!1
		s.lock();
		var o=!1,r=e=>{if(s.unlock(),!e||"object"!=typeof e)return t()
		var i=Y(e[s.settings.valueField]);
		if("string"!=typeof i)return t()
		s.setTextboxValue(),s.addOption(e,!0),s.setCaret(n),s.addItem(i),t(e),o=!0;};
		return i="function"==typeof s.settings.create?s.settings.create.call(this,e,r):{[s.settings.labelField]:e,[s.settings.valueField]:e},o||r(i),!0}refreshItems(){var e=this;
		e.lastQuery=null,e.isSetup&&e.addItems(e.items),e.updateOriginalInput(),e.refreshState();}refreshState(){const e=this;
		e.refreshValidityState();
		const t=e.isFull(),i=e.isLocked;
		e.wrapper.classList.toggle("rtl",e.rtl);
		const s=e.wrapper.classList;
		var n;
		s.toggle("focus",e.isFocused),s.toggle("disabled",e.isDisabled),s.toggle("required",e.isRequired),s.toggle("invalid",!e.isValid),s.toggle("locked",i),s.toggle("full",t),s.toggle("input-active",e.isFocused&&!e.isInputHidden),s.toggle("dropdown-active",e.isOpen),s.toggle("has-options",(n=e.options,0===Object.keys(n).length)),s.toggle("has-items",e.items.length>0);}refreshValidityState(){var e=this;
		e.input.validity&&(e.isValid=e.input.validity.valid,e.isInvalid=!e.isValid);}isFull(){return null!==this.settings.maxItems&&this.items.length>=this.settings.maxItems}updateOriginalInput(e={}){const t=this;
		var i,s;
		const n=t.input.querySelector('option[value=""]');
		if(t.is_select_tag){const e=[],r=t.input.querySelectorAll("option:checked").length;
		function o(i,s,o){return i||(i=V('<option value="'+ee(s)+'">'+ee(o)+"</option>")),i!=n&&t.input.append(i),e.push(i),(i!=n||r>0)&&(i.selected=!0),i}t.input.querySelectorAll("option:checked").forEach((e=>{e.selected=!1;})),0==t.items.length&&"single"==t.settings.mode?o(n,"",""):t.items.forEach((n=>{if(i=t.options[n],s=i[t.settings.labelField]||"",e.includes(i.$option)){o(t.input.querySelector(`option[value="${le(n)}"]:not(:checked)`),n,s);}else i.$option=o(i.$option,n,s);}));}else t.input.value=t.getValue();
		t.isSetup&&(e.silent||t.trigger("change",t.getValue()));}open(){var e=this;
		e.isLocked||e.isOpen||"multi"===e.settings.mode&&e.isFull()||(e.isOpen=!0,G(e.focus_node,{"aria-expanded":"true"}),e.refreshState(),N(e.dropdown,{visibility:"hidden",display:"block"}),e.positionDropdown(),N(e.dropdown,{visibility:"visible",display:"block"}),e.focus(),e.trigger("dropdown_open",e.dropdown));}close(e=!0){var t=this,i=t.isOpen;
		e&&(t.setTextboxValue(),"single"===t.settings.mode&&t.items.length&&t.hideInput()),t.isOpen=!1,G(t.focus_node,{"aria-expanded":"false"}),N(t.dropdown,{display:"none"}),t.settings.hideSelected&&t.clearActiveOption(),t.refreshState(),i&&t.trigger("dropdown_close",t.dropdown);}positionDropdown(){if("body"===this.settings.dropdownParent){var e=this.control,t=e.getBoundingClientRect(),i=e.offsetHeight+t.top+window.scrollY,s=t.left+window.scrollX;
		N(this.dropdown,{width:t.width+"px",top:i+"px",left:s+"px"});}}clear(e){var t=this;
		if(t.items.length){var i=t.controlChildren();
		E(i,(e=>{t.removeItem(e,!0);})),t.showInput(),e||t.updateOriginalInput(),t.trigger("clear");}}insertAtCaret(e){const t=this,i=t.caretPos,s=t.control;
		s.insertBefore(e,s.children[i]||null),t.setCaret(i+1);}deleteSelection(e){var t,i,s,n,o,r=this;
		t=e&&8===e.keyCode?-1:1,i={start:(o=r.control_input).selectionStart||0,length:(o.selectionEnd||0)-(o.selectionStart||0)};
		const l=[];
		if(r.activeItems.length)n=K(r.activeItems,t),s=Q(n),t>0&&s++,E(r.activeItems,(e=>l.push(e)));
		else if((r.isFocused||"single"===r.settings.mode)&&r.items.length){const e=r.controlChildren();
		let s;
		t<0&&0===i.start&&0===i.length?s=e[r.caretPos-1]:t>0&&i.start===r.inputValue().length&&(s=e[r.caretPos]),void 0!==s&&l.push(s);}if(!r.shouldDelete(l,e))return !1
		for(se(e,!0),void 0!==s&&r.setCaret(s);l.length;)r.removeItem(l.pop());
		return r.showInput(),r.positionDropdown(),r.refreshOptions(!1),!0}shouldDelete(e,t){const i=e.map((e=>e.dataset.value));
		return !(!i.length||"function"==typeof this.settings.onDelete&&!1===this.settings.onDelete(i,t))}advanceSelection(e,t){var i,s,n=this;
		n.rtl&&(e*=-1),n.inputValue().length||(oe(W,t)||oe("shiftKey",t)?(s=(i=n.getLastActive(e))?i.classList.contains("active")?n.getAdjacent(i,e,"item"):i:e>0?n.control_input.nextElementSibling:n.control_input.previousElementSibling)&&(s.classList.contains("active")&&n.removeActiveItem(i),n.setActiveItemClass(s)):n.moveCaret(e));}moveCaret(e){}getLastActive(e){let t=this.control.querySelector(".last-active");
		if(t)return t
		var i=this.control.querySelectorAll(".active");
		return i?K(i,e):void 0}setCaret(e){this.caretPos=this.items.length;}controlChildren(){return Array.from(this.control.querySelectorAll("[data-ts-item]"))}lock(){this.isLocked=!0,this.refreshState();}unlock(){this.isLocked=!1,this.refreshState();}disable(){var e=this;
		e.input.disabled=!0,e.control_input.disabled=!0,e.focus_node.tabIndex=-1,e.isDisabled=!0,this.close(),e.lock();}enable(){var e=this;
		e.input.disabled=!1,e.control_input.disabled=!1,e.focus_node.tabIndex=e.tabIndex,e.isDisabled=!1,e.unlock();}destroy(){var e=this,t=e.revertSettings;
		e.trigger("destroy"),e.off(),e.wrapper.remove(),e.dropdown.remove(),e.input.innerHTML=t.innerHTML,e.input.tabIndex=t.tabIndex,z(e.input,"tomselected","ts-hidden-accessible"),e._destroy(),delete e.input.tomselect;}render(e,t){var i,s;
		const n=this;
		if("function"!=typeof this.settings.render[e])return null
		if(!(s=n.settings.render[e].call(this,t,ee)))return null
		if(s=V(s),"option"===e||"option_create"===e?t[n.settings.disabledField]?G(s,{"aria-disabled":"true"}):G(s,{"data-selectable":""}):"optgroup"===e&&(i=t.group[n.settings.optgroupValueField],G(s,{"data-group":i}),t.group[n.settings.disabledField]&&G(s,{"data-disabled":""})),"option"===e||"item"===e){const i=Z(t[n.settings.valueField]);
		G(s,{"data-value":i}),"item"===e?(H(s,n.settings.itemClass),G(s,{"data-ts-item":""})):(H(s,n.settings.optionClass),G(s,{role:"option",id:t.$id}),t.$div=s,n.options[i]=t);}return s}_render(e,t){const i=this.render(e,t);
		if(null==i)throw "HTMLElement expected"
		return i}clearCache(){E(this.options,(e=>{e.$div&&(e.$div.remove(),delete e.$div);}));}uncacheValue(e){const t=this.getOption(e);
		t&&t.remove();}canCreate(e){return this.settings.create&&e.length>0&&this.settings.createFilter.call(this,e)}hook(e,t,i){var s=this,n=s[t];
		s[t]=function(){var t,o;
		return "after"===e&&(t=n.apply(s,arguments)),o=i.apply(s,arguments),"instead"===e?o:("before"===e&&(t=n.apply(s,arguments)),t)};}}return ue.define("change_listener",(function(){ne(this.input,"change",(()=>{this.sync();}));})),ue.define("checkbox_options",(function(){var e=this,t=e.onOptionSelect;
		e.settings.hideSelected=!1;
		var i=function(e){setTimeout((()=>{var t=e.querySelector("input");
		t instanceof HTMLInputElement&&(e.classList.contains("selected")?t.checked=!0:t.checked=!1);}),1);};
		e.hook("after","setupTemplates",(()=>{var t=e.settings.render.option;
		e.settings.render.option=(i,s)=>{var n=V(t.call(e,i,s)),o=document.createElement("input");
		o.addEventListener("click",(function(e){se(e);})),o.type="checkbox";
		const r=Y(i[e.settings.valueField]);
		return r&&e.items.indexOf(r)>-1&&(o.checked=!0),n.prepend(o),n};})),e.on("item_remove",(t=>{var s=e.getOption(t);
		s&&(s.classList.remove("selected"),i(s));})),e.on("item_add",(t=>{var s=e.getOption(t);
		s&&i(s);})),e.hook("instead","onOptionSelect",((s,n)=>{if(n.classList.contains("selected"))return n.classList.remove("selected"),e.removeItem(n.dataset.value),e.refreshOptions(),void se(s,!0)
		t.call(e,s,n),i(n);}));})),ue.define("clear_button",(function(e){const t=this,i=Object.assign({className:"clear-button",title:"Clear All",html:e=>`<div class="${e.className}" title="${e.title}">&#10799;</div>`},e);
		t.on("initialize",(()=>{var e=V(i.html(i));
		e.addEventListener("click",(e=>{t.isDisabled||(t.clear(),"single"===t.settings.mode&&t.settings.allowEmptyOption&&t.addItem(""),e.preventDefault(),e.stopPropagation());})),t.control.appendChild(e);}));})),ue.define("drag_drop",(function(){var e=this;
		if(!$.fn.sortable)throw new Error('The "drag_drop" plugin requires jQuery UI "sortable".')
		if("multi"===e.settings.mode){var t=e.lock,i=e.unlock;
		e.hook("instead","lock",(()=>{var i=$(e.control).data("sortable");
		return i&&i.disable(),t.call(e)})),e.hook("instead","unlock",(()=>{var t=$(e.control).data("sortable");
		return t&&t.enable(),i.call(e)})),e.on("initialize",(()=>{var t=$(e.control).sortable({items:"[data-value]",forcePlaceholderSize:!0,disabled:e.isLocked,start:(e,i)=>{i.placeholder.css("width",i.helper.css("width")),t.css({overflow:"visible"});},stop:()=>{t.css({overflow:"hidden"});
		var i=[];
		t.children("[data-value]").each((function(){this.dataset.value&&i.push(this.dataset.value);})),e.setValue(i);}});}));}})),ue.define("dropdown_header",(function(e){const t=this,i=Object.assign({title:"Untitled",headerClass:"dropdown-header",titleRowClass:"dropdown-header-title",labelClass:"dropdown-header-label",closeClass:"dropdown-header-close",html:e=>'<div class="'+e.headerClass+'"><div class="'+e.titleRowClass+'"><span class="'+e.labelClass+'">'+e.title+'</span><a class="'+e.closeClass+'">&times;</a></div></div>'},e);
		t.on("initialize",(()=>{var e=V(i.html(i)),s=e.querySelector("."+i.closeClass);
		s&&s.addEventListener("click",(e=>{se(e,!0),t.close();})),t.dropdown.insertBefore(e,t.dropdown.firstChild);}));})),ue.define("caret_position",(function(){var e=this;
		e.hook("instead","setCaret",(t=>{"single"!==e.settings.mode&&e.control.contains(e.control_input)?(t=Math.max(0,Math.min(e.items.length,t)))==e.caretPos||e.isPending||e.controlChildren().forEach(((i,s)=>{s<t?e.control_input.insertAdjacentElement("beforebegin",i):e.control.appendChild(i);})):t=e.items.length,e.caretPos=t;})),e.hook("instead","moveCaret",(t=>{if(!e.isFocused)return
		const i=e.getLastActive(t);
		if(i){const s=Q(i);
		e.setCaret(t>0?s+1:s),e.setActiveItem(),z(i,"last-active");}else e.setCaret(e.caretPos+t);}));})),ue.define("dropdown_input",(function(){const e=this;
		e.settings.shouldOpen=!0,e.hook("before","setup",(()=>{e.focus_node=e.control,H(e.control_input,"dropdown-input");
		const t=V('<div class="dropdown-input-wrap">');
		t.append(e.control_input),e.dropdown.insertBefore(t,e.dropdown.firstChild);
		const i=V('<input class="items-placeholder" tabindex="-1" />');
		i.placeholder=e.settings.placeholder||"",e.control.append(i);})),e.on("initialize",(()=>{e.control_input.addEventListener("keydown",(t=>{switch(t.keyCode){case 27:return e.isOpen&&(se(t,!0),e.close()),void e.clearActiveItems()
		case 9:e.focus_node.tabIndex=-1;}return e.onKeyDown.call(e,t)})),e.on("blur",(()=>{e.focus_node.tabIndex=e.isDisabled?-1:e.tabIndex;})),e.on("dropdown_open",(()=>{e.control_input.focus();}));
		const t=e.onBlur;
		e.hook("instead","onBlur",(i=>{if(!i||i.relatedTarget!=e.control_input)return t.call(e)})),ne(e.control_input,"blur",(()=>e.onBlur())),e.hook("before","close",(()=>{e.isOpen&&e.focus_node.focus({preventScroll:!0});}));}));})),ue.define("input_autogrow",(function(){var e=this;
		e.on("initialize",(()=>{var t=document.createElement("span"),i=e.control_input;
		t.style.cssText="position:absolute; top:-99999px; left:-99999px; width:auto; padding:0; white-space:pre; ",e.wrapper.appendChild(t);
		for(const e of ["letterSpacing","fontSize","fontFamily","fontWeight","textTransform"])t.style[e]=i.style[e];
		var s=()=>{t.textContent=i.value,i.style.width=t.clientWidth+"px";};
		s(),e.on("update item_add item_remove",s),ne(i,"input",s),ne(i,"keyup",s),ne(i,"blur",s),ne(i,"update",s);}));})),ue.define("no_backspace_delete",(function(){var e=this,t=e.deleteSelection;
		this.hook("instead","deleteSelection",(i=>!!e.activeItems.length&&t.call(e,i)));})),ue.define("no_active_items",(function(){this.hook("instead","setActiveItem",(()=>{})),this.hook("instead","selectAll",(()=>{}));})),ue.define("optgroup_columns",(function(){var e=this,t=e.onKeyDown;
		e.hook("instead","onKeyDown",(i=>{var s,n,o,r;
		if(!e.isOpen||37!==i.keyCode&&39!==i.keyCode)return t.call(e,i)
		e.ignoreHover=!0,r=B(e.activeOption,"[data-group]"),s=Q(e.activeOption,"[data-selectable]"),r&&(r=37===i.keyCode?r.previousSibling:r.nextSibling)&&(n=(o=r.querySelectorAll("[data-selectable]"))[Math.min(o.length-1,s)])&&e.setActiveOption(n);}));})),ue.define("remove_button",(function(e){const t=Object.assign({label:"&times;",title:"Remove",className:"remove",append:!0},e);
		var i=this;
		if(t.append){var s='<a href="javascript:void(0)" class="'+t.className+'" tabindex="-1" title="'+ee(t.title)+'">'+t.label+"</a>";
		i.hook("after","setupTemplates",(()=>{var e=i.settings.render.item;
		i.settings.render.item=(t,n)=>{var o=V(e.call(i,t,n)),r=V(s);
		return o.appendChild(r),ne(r,"mousedown",(e=>{se(e,!0);})),ne(r,"click",(e=>{se(e,!0),i.isLocked||i.shouldDelete([o],e)&&(i.removeItem(o),i.refreshOptions(!1),i.inputState());})),o};}));}})),ue.define("restore_on_backspace",(function(e){const t=this,i=Object.assign({text:e=>e[t.settings.labelField]},e);
		t.on("item_remove",(function(e){if(t.isFocused&&""===t.control_input.value.trim()){var s=t.options[e];
		s&&t.setTextboxValue(i.text.call(t,s));}}));})),ue.define("virtual_scroll",(function(){const e=this,t=e.canLoad,i=e.clearActiveOption,s=e.loadCallback;
		var n,o,r={},l=!1,a=[];
		if(e.settings.shouldLoadMore||(e.settings.shouldLoadMore=()=>{if(n.clientHeight/(n.scrollHeight-n.scrollTop)>.9)return !0
		if(e.activeOption){var t=e.selectable();
		if(Array.from(t).indexOf(e.activeOption)>=t.length-2)return !0}return !1}),!e.settings.firstUrl)throw "virtual_scroll plugin requires a firstUrl() method"
		e.settings.sortField=[{field:"$order"},{field:"$score"}];
		const c=t=>!("number"==typeof e.settings.maxOptions&&n.children.length>=e.settings.maxOptions)&&!(!(t in r)||!r[t]),d=(t,i)=>e.items.indexOf(i)>=0||a.indexOf(i)>=0;
		e.setNextUrl=(e,t)=>{r[e]=t;},e.getUrl=t=>{if(t in r){const e=r[t];
		return r[t]=!1,e}return r={},e.settings.firstUrl.call(e,t)},e.hook("instead","clearActiveOption",(()=>{if(!l)return i.call(e)})),e.hook("instead","canLoad",(i=>i in r?c(i):t.call(e,i))),e.hook("instead","loadCallback",((t,i)=>{if(l){if(o){const i=t[0];
		void 0!==i&&(o.dataset.value=i[e.settings.valueField]);}}else e.clearOptions(d);
		s.call(e,t,i),l=!1;})),e.hook("after","refreshOptions",(()=>{const t=e.lastValue;
		var i;
		c(t)?(i=e.render("loading_more",{query:t}))&&(i.setAttribute("data-selectable",""),o=i):t in r&&!n.querySelector(".no-results")&&(i=e.render("no_more_results",{query:t})),i&&(H(i,e.settings.optionClass),n.append(i));})),e.on("initialize",(()=>{a=Object.keys(e.options),n=e.dropdown_content,e.settings.render=Object.assign({},{loading_more:()=>'<div class="loading-more-results">Loading more results ... </div>',no_more_results:()=>'<div class="no-more-results">No more results</div>'},e.settings.render),n.addEventListener("scroll",(()=>{e.settings.shouldLoadMore.call(e)&&c(e.lastValue)&&(l||(l=!0,e.load.call(e,e.lastValue)));}));}));})),ue}));
	} (tomSelect_complete_min));
	var TomSelect = tomSelect_complete_min.exports;

	var prefix = 'tblr-';
	var hexToRgba = function hexToRgba(hex, opacity) {
	  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	  return result ? "rgba(".concat(parseInt(result[1], 16), ", ").concat(parseInt(result[2], 16), ", ").concat(parseInt(result[3], 16), ", ").concat(opacity, ")") : null;
	};
	var getColor = function getColor(color) {
	  var opacity = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
	  var c = getComputedStyle(document.body).getPropertyValue("--".concat(prefix).concat(color)).trim();
	  if (opacity !== 1) {
	    return hexToRgba(c, opacity);
	  }
	  return c;
	};

	var tabler = /*#__PURE__*/Object.freeze({
		__proto__: null,
		prefix: prefix,
		hexToRgba: hexToRgba,
		getColor: getColor
	});

	globalThis.bootstrap = bootstrap;
	globalThis.tabler = tabler;
	window.TomSelect = TomSelect;

}));
