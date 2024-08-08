/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/js/core.js":
/*!*************************************!*\
  !*** ./resources/assets/js/core.js ***!
  \*************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n\r\n\r\n\r\n//import {MWUniversalContainer} from '../../../input/front-end/js/containers/container.js';\r\n// input/front-endjs/containers/container.js\r\n\r\nwindow.mw  = {};\r\n// window.mw.container = new MWUniversalContainer();\r\n\r\n\r\nmw.required = [] ;\r\nmw.require = function(url, inHead, key, defered) {\r\n    if(!url) return;\r\n    var defer;\r\n    if(defered) {\r\n        defer = ' defer ';\r\n    } else {\r\n        defer = '   '\r\n    }\r\n    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){\r\n        inHead = inHead || false;\r\n    }\r\n    var keyString;\r\n    if(typeof inHead === 'string'){\r\n        keyString = ''+inHead;\r\n        inHead = key || false;\r\n    }\r\n    if(typeof key === 'string'){\r\n        keyString = key;\r\n    }\r\n    var toPush = url, urlModified = false;\r\n    if (!!keyString) {\r\n        toPush = keyString;\r\n        urlModified = true\r\n    }\r\n    var t = url.split('.').pop();\r\n    url = url.includes('//') ? url : (t !== \"css\" ? mw.settings.includes_url + \"api/\" + url  :  mw.settings.includes_url + \"css/\" + url);\r\n    if(!urlModified) toPush = url;\r\n    if (!~mw.required.indexOf(toPush)) {\r\n\r\n      mw.required.push(toPush);\r\n      url = url.includes(\"?\") ?  url + '&mwv=' + mw.version : url + \"?mwv=\" + mw.version;\r\n      if(document.querySelector('link[href=\"'+url+'\"],script[src=\"'+url+'\"]') !== null){\r\n          return\r\n      }\r\n\r\n      var cssRel = \" rel='stylesheet' \";\r\n\r\n      if(defered){\r\n        cssRel = \" rel='preload' as='style' onload='this.onload=null;this.rel=\\'stylesheet\\'' \";\r\n      }\r\n\r\n\r\n      var string = t !== \"css\" ? \"<script \"+defer+\"  src='\" + url + \"'></script>\" : \"<link \"+cssRel+\" href='\" + url + \"' />\";\r\n\r\n          if(typeof $.fn === 'object'){\r\n              $(mw.head).append(string);\r\n          }\r\n          else{\r\n              var el;\r\n              if( t !== \"css\")  {\r\n                  el = document.createElement('script');\r\n                  el.src = url;\r\n                  el.defer = !!defer;\r\n                  el.setAttribute('type', 'text/javascript');\r\n                  mw.head.appendChild(el);\r\n              }\r\n              else{\r\n\r\n                 el = document.createElement('link');\r\n                 if(defered) {\r\n                    el.as='style';\r\n                    el.rel='preload';\r\n                    el.addEventListener('load', e => el.rel='stylesheet');\r\n                 } else {\r\n                    el.rel='stylesheet';\r\n                 }\r\n\r\n\r\n                 el.href = url;\r\n                 mw.head.appendChild(el);\r\n              }\r\n          }\r\n\r\n    }\r\n  };\r\n\r\n\r\n\r\n\n\n//# sourceURL=webpack://microweber-frontend-assets/./resources/assets/js/core.js?");

/***/ })

/******/ 	});
/************************************************************************/
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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/assets/js/core.js"](0, __webpack_exports__, __webpack_require__);
/******/ 	
/******/ })()
;