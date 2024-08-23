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

eval("__webpack_require__.r(__webpack_exports__);\n\n\n\n//import {MWUniversalContainer} from '../../../input/front-end/js/containers/container.js';\n// input/front-endjs/containers/container.js\n\nif(!window.mw ) {\n    window.mw  = {};\n}\n\n\n// window.mw.container = new MWUniversalContainer();\n\n\nmw.required = [] ;\nmw.require = function(url, inHead, key, defered) {\n    if(!url) return;\n    var defer;\n    if(defered) {\n        defer = ' defer ';\n    } else {\n        defer = '   '\n    }\n    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){\n        inHead = inHead || false;\n    }\n    var keyString;\n    if(typeof inHead === 'string'){\n        keyString = ''+inHead;\n        inHead = key || false;\n    }\n    if(typeof key === 'string'){\n        keyString = key;\n    }\n    var toPush = url, urlModified = false;\n    if (!!keyString) {\n        toPush = keyString;\n        urlModified = true\n    }\n    var t = url.split('.').pop();\n    url = url.includes('//') ? url : (t !== \"css\" ? mw.settings.includes_url + \"api/\" + url  :  mw.settings.includes_url + \"css/\" + url);\n    if(!urlModified) toPush = url;\n    if (!~mw.required.indexOf(toPush)) {\n\n      mw.required.push(toPush);\n      url = url.includes(\"?\") ?  url + '&mwv=' + mw.version : url + \"?mwv=\" + mw.version;\n      if(document.querySelector('link[href=\"'+url+'\"],script[src=\"'+url+'\"]') !== null){\n          return\n      }\n\n      var cssRel = \" rel='stylesheet' \";\n\n      if(defered){\n        cssRel = \" rel='preload' as='style' onload='this.onload=null;this.rel=\\'stylesheet\\'' \";\n      }\n\n\n      var string = t !== \"css\" ? \"<script \"+defer+\"  src='\" + url + \"'></script>\" : \"<link \"+cssRel+\" href='\" + url + \"' />\";\n\n          if(typeof $.fn === 'object'){\n              $(document.head).append(string);\n          }\n          else{\n              var el;\n              if( t !== \"css\")  {\n                  el = document.createElement('script');\n                  el.src = url;\n                  el.defer = !!defer;\n                  el.setAttribute('type', 'text/javascript');\n                  document.head.appendChild(el);\n              }\n              else{\n\n                 el = document.createElement('link');\n                 if(defered) {\n                    el.as='style';\n                    el.rel='preload';\n                    el.addEventListener('load', e => el.rel='stylesheet');\n                 } else {\n                    el.rel='stylesheet';\n                 }\n\n\n                 el.href = url;\n                 document.head.appendChild(el);\n              }\n          }\n\n    }\n  };\n\n\n\n\n\n//# sourceURL=webpack://microweber-frontend-assets/./resources/assets/js/core.js?");

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