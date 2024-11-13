/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
/*!*************************************!*\
  !*** ./resources/assets/js/core.js ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
//import {MWUniversalContainer} from '../../../input/front-end/js/containers/container.js';
// input/front-endjs/containers/container.js

if (!window.mw) {
  window.mw = {};
}

// window.mw.container = new MWUniversalContainer();

mw.required = [];
mw.require = function (url, inHead, key, defered) {
  if (!url) return;
  var defer;
  if (defered) {
    defer = ' defer ';
  } else {
    defer = '   ';
  }
  if (typeof inHead === 'boolean' || typeof inHead === 'undefined') {
    inHead = inHead || false;
  }
  var keyString;
  if (typeof inHead === 'string') {
    keyString = '' + inHead;
    inHead = key || false;
  }
  if (typeof key === 'string') {
    keyString = key;
  }
  var toPush = url,
    urlModified = false;
  if (!!keyString) {
    toPush = keyString;
    urlModified = true;
  }
  var t = url.split('.').pop();
  url = url.includes('//') ? url : t !== "css" ? mw.settings.includes_url + "api/" + url : mw.settings.includes_url + "css/" + url;
  if (!urlModified) toPush = url;
  if (!~mw.required.indexOf(toPush)) {
    mw.required.push(toPush);
    url = url.includes("?") ? url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
    if (document.querySelector('link[href="' + url + '"],script[src="' + url + '"]') !== null) {
      return;
    }
    var cssRel = " rel='stylesheet' ";
    var string = t !== "css" ? "<script " + defer + "  src='" + url + "'></script>" : "<link " + cssRel + " href='" + url + "' />";
    if (_typeof($.fn) === 'object') {
      $(document.head).append(string);
    } else {
      var el;
      if (t !== "css") {
        el = document.createElement('script');
        el.src = url;
        el.defer = !!defer;
        el.setAttribute('type', 'text/javascript');
        document.head.appendChild(el);
      } else {
        el = document.createElement('link');
        if (defered) {
          el.as = 'style';
          el.rel = 'preload';
          el.addEventListener('load', function (e) {
            return el.rel = 'stylesheet';
          });
        } else {
          el.rel = 'stylesheet';
        }
        el.href = url;
        document.head.appendChild(el);
      }
    }
  }
};
/******/ })()
;