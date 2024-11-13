/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/a-color-picker/dist/acolorpicker.js":
/*!**********************************************************!*\
  !*** ./node_modules/a-color-picker/dist/acolorpicker.js ***!
  \**********************************************************/
/***/ (function(module) {

/*!
 * a-color-picker (https://github.com/narsenico/a-color-picker)
 * 
 * Copyright (c) 2017-2018, Gianfranco Caldi.
 * Released under the MIT License.
 */
!function(e,t){ true?module.exports=t():0}("undefined"!=typeof self?self:this,function(){return function(e){var t={};function r(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,r),o.l=!0,o.exports}return r.m=e,r.c=t,r.d=function(e,t,i){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(r.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(i,o,function(t){return e[t]}.bind(null,o));return i},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=1)}([function(e,t,r){"use strict";
/*!
 * is-plain-object <https://github.com/jonschlinkert/is-plain-object>
 *
 * Copyright (c) 2014-2017, Jon Schlinkert.
 * Released under the MIT License.
 */var i=r(3);function o(e){return!0===i(e)&&"[object Object]"===Object.prototype.toString.call(e)}e.exports=function(e){var t,r;return!1!==o(e)&&"function"==typeof(t=e.constructor)&&!1!==o(r=t.prototype)&&!1!==r.hasOwnProperty("isPrototypeOf")}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.VERSION=t.PALETTE_MATERIAL_CHROME=t.PALETTE_MATERIAL_500=t.COLOR_NAMES=t.getLuminance=t.intToRgb=t.rgbToInt=t.rgbToHsv=t.rgbToHsl=t.hslToRgb=t.rgbToHex=t.parseColor=t.parseColorToHsla=t.parseColorToHsl=t.parseColorToRgba=t.parseColorToRgb=t.from=t.createPicker=void 0;var i=function(){function e(e,t){for(var r=0;r<t.length;r++){var i=t[r];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(t,r,i){return r&&e(t.prototype,r),i&&e(t,i),t}}(),o=function(e,t){if(Array.isArray(e))return e;if(Symbol.iterator in Object(e))return function(e,t){var r=[],i=!0,o=!1,n=void 0;try{for(var s,a=e[Symbol.iterator]();!(i=(s=a.next()).done)&&(r.push(s.value),!t||r.length!==t);i=!0);}catch(e){o=!0,n=e}finally{try{!i&&a.return&&a.return()}finally{if(o)throw n}}return r}(e,t);throw new TypeError("Invalid attempt to destructure non-iterable instance")},n=r(2),s=l(r(0)),a=l(r(4));function l(e){return e&&e.__esModule?e:{default:e}}function c(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function u(e){if(Array.isArray(e)){for(var t=0,r=Array(e.length);t<e.length;t++)r[t]=e[t];return r}return Array.from(e)}
/*!
 * a-color-picker
 * https://github.com/narsenico/a-color-picker
 *
 * Copyright (c) 2017-2019, Gianfranco Caldi.
 * Released under the MIT License.
 */var h="undefined"!=typeof window&&window.navigator.userAgent.indexOf("Edge")>-1,p="undefined"!=typeof window&&window.navigator.userAgent.indexOf("rv:")>-1,d={id:null,attachTo:"body",showHSL:!0,showRGB:!0,showHEX:!0,showAlpha:!1,color:"#ff0000",palette:null,paletteEditable:!1,useAlphaInPalette:"auto",slBarSize:[232,150],hueBarSize:[150,11],alphaBarSize:[150,11]},f="COLOR",g="RGBA_USER",b="HSLA_USER";function v(e,t,r){return e?e instanceof HTMLElement?e:e instanceof NodeList?e[0]:"string"==typeof e?document.querySelector(e):e.jquery?e.get(0):r?t:null:t}function m(e){var t=e.getContext("2d"),r=+e.width,i=+e.height,s=t.createLinearGradient(1,1,1,i-1);return s.addColorStop(0,"white"),s.addColorStop(1,"black"),{setHue:function(e){var o=t.createLinearGradient(1,0,r-1,0);o.addColorStop(0,"hsla("+e+", 100%, 50%, 0)"),o.addColorStop(1,"hsla("+e+", 100%, 50%, 1)"),t.fillStyle=s,t.fillRect(0,0,r,i),t.fillStyle=o,t.globalCompositeOperation="multiply",t.fillRect(0,0,r,i),t.globalCompositeOperation="source-over"},grabColor:function(e,r){return t.getImageData(e,r,1,1).data},findColor:function(e,t,s){var a=(0,n.rgbToHsv)(e,t,s),l=o(a,3),c=l[1],u=l[2];return[c*r,i-u*i]}}}function A(e,t,r){return null===e?t:/^\s*$/.test(e)?r:!!/true|yes|1/i.test(e)||!/false|no|0/i.test(e)&&t}function y(e,t,r){if(null===e)return t;if(/^\s*$/.test(e))return r;var i=e.split(",").map(Number);return 2===i.length&&i[0]&&i[1]?i:t}var k=function(){function e(t,r){if(c(this,e),r?(t=v(t),this.options=Object.assign({},d,r)):t&&(0,s.default)(t)?(this.options=Object.assign({},d,t),t=v(this.options.attachTo)):(this.options=Object.assign({},d),t=v((0,n.nvl)(t,this.options.attachTo))),!t)throw new Error("Container not found: "+this.options.attachTo);!function(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"acp-";if(t.hasAttribute(r+"show-hsl")&&(e.showHSL=A(t.getAttribute(r+"show-hsl"),d.showHSL,!0)),t.hasAttribute(r+"show-rgb")&&(e.showRGB=A(t.getAttribute(r+"show-rgb"),d.showRGB,!0)),t.hasAttribute(r+"show-hex")&&(e.showHEX=A(t.getAttribute(r+"show-hex"),d.showHEX,!0)),t.hasAttribute(r+"show-alpha")&&(e.showAlpha=A(t.getAttribute(r+"show-alpha"),d.showAlpha,!0)),t.hasAttribute(r+"palette-editable")&&(e.paletteEditable=A(t.getAttribute(r+"palette-editable"),d.paletteEditable,!0)),t.hasAttribute(r+"sl-bar-size")&&(e.slBarSize=y(t.getAttribute(r+"sl-bar-size"),d.slBarSize,[232,150])),t.hasAttribute(r+"hue-bar-size")&&(e.hueBarSize=y(t.getAttribute(r+"hue-bar-size"),d.hueBarSize,[150,11]),e.alphaBarSize=e.hueBarSize),t.hasAttribute(r+"palette")){var i=t.getAttribute(r+"palette");switch(i){case"PALETTE_MATERIAL_500":e.palette=n.PALETTE_MATERIAL_500;break;case"PALETTE_MATERIAL_CHROME":case"":e.palette=n.PALETTE_MATERIAL_CHROME;break;default:e.palette=i.split(/[;|]/)}}t.hasAttribute(r+"color")&&(e.color=t.getAttribute(r+"color"))}(this.options,t),this.H=0,this.S=0,this.L=0,this.R=0,this.G=0,this.B=0,this.A=1,this.palette={},this.element=document.createElement("div"),this.options.id&&(this.element.id=this.options.id),this.element.className="a-color-picker",this.element.innerHTML=a.default,t.appendChild(this.element);var i=this.element.querySelector(".a-color-picker-h");this.setupHueCanvas(i),this.hueBarHelper=m(i),this.huePointer=this.element.querySelector(".a-color-picker-h+.a-color-picker-dot");var o=this.element.querySelector(".a-color-picker-sl");this.setupSlCanvas(o),this.slBarHelper=m(o),this.slPointer=this.element.querySelector(".a-color-picker-sl+.a-color-picker-dot"),this.preview=this.element.querySelector(".a-color-picker-preview"),this.setupClipboard(this.preview.querySelector(".a-color-picker-clipbaord")),this.options.showHSL?(this.setupInput(this.inputH=this.element.querySelector(".a-color-picker-hsl>input[nameref=H]")),this.setupInput(this.inputS=this.element.querySelector(".a-color-picker-hsl>input[nameref=S]")),this.setupInput(this.inputL=this.element.querySelector(".a-color-picker-hsl>input[nameref=L]"))):this.element.querySelector(".a-color-picker-hsl").remove(),this.options.showRGB?(this.setupInput(this.inputR=this.element.querySelector(".a-color-picker-rgb>input[nameref=R]")),this.setupInput(this.inputG=this.element.querySelector(".a-color-picker-rgb>input[nameref=G]")),this.setupInput(this.inputB=this.element.querySelector(".a-color-picker-rgb>input[nameref=B]"))):this.element.querySelector(".a-color-picker-rgb").remove(),this.options.showHEX?this.setupInput(this.inputRGBHEX=this.element.querySelector("input[nameref=RGBHEX]")):this.element.querySelector(".a-color-picker-rgbhex").remove(),this.options.paletteEditable||this.options.palette&&this.options.palette.length>0?this.setPalette(this.paletteRow=this.element.querySelector(".a-color-picker-palette")):(this.paletteRow=this.element.querySelector(".a-color-picker-palette"),this.paletteRow.remove()),this.options.showAlpha?(this.setupAlphaCanvas(this.element.querySelector(".a-color-picker-a")),this.alphaPointer=this.element.querySelector(".a-color-picker-a+.a-color-picker-dot")):this.element.querySelector(".a-color-picker-alpha").remove(),this.element.style.width=this.options.slBarSize[0]+"px",this.onValueChanged(f,this.options.color)}return i(e,[{key:"setupHueCanvas",value:function(e){var t=this;e.width=this.options.hueBarSize[0],e.height=this.options.hueBarSize[1];for(var r=e.getContext("2d"),i=r.createLinearGradient(0,0,this.options.hueBarSize[0],0),o=0;o<=1;o+=1/360)i.addColorStop(o,"hsl("+360*o+", 100%, 50%)");r.fillStyle=i,r.fillRect(0,0,this.options.hueBarSize[0],this.options.hueBarSize[1]);var s=function(r){var i=(0,n.limit)(r.clientX-e.getBoundingClientRect().left,0,t.options.hueBarSize[0]),o=Math.round(360*i/t.options.hueBarSize[0]);t.huePointer.style.left=i-7+"px",t.onValueChanged("H",o)},a=function e(){document.removeEventListener("mousemove",s),document.removeEventListener("mouseup",e)};e.addEventListener("mousedown",function(e){s(e),document.addEventListener("mousemove",s),document.addEventListener("mouseup",a)})}},{key:"setupSlCanvas",value:function(e){var t=this;e.width=this.options.slBarSize[0],e.height=this.options.slBarSize[1];var r=function(r){var i=(0,n.limit)(r.clientX-e.getBoundingClientRect().left,0,t.options.slBarSize[0]-1),o=(0,n.limit)(r.clientY-e.getBoundingClientRect().top,0,t.options.slBarSize[1]-1),s=t.slBarHelper.grabColor(i,o);t.slPointer.style.left=i-7+"px",t.slPointer.style.top=o-7+"px",t.onValueChanged("RGB",s)},i=function e(){document.removeEventListener("mousemove",r),document.removeEventListener("mouseup",e)};e.addEventListener("mousedown",function(e){r(e),document.addEventListener("mousemove",r),document.addEventListener("mouseup",i)})}},{key:"setupAlphaCanvas",value:function(e){var t=this;e.width=this.options.alphaBarSize[0],e.height=this.options.alphaBarSize[1];var r=e.getContext("2d"),i=r.createLinearGradient(0,0,e.width-1,0);i.addColorStop(0,"hsla(0, 0%, 50%, 0)"),i.addColorStop(1,"hsla(0, 0%, 50%, 1)"),r.fillStyle=i,r.fillRect(0,0,this.options.alphaBarSize[0],this.options.alphaBarSize[1]);var o=function(r){var i=(0,n.limit)(r.clientX-e.getBoundingClientRect().left,0,t.options.alphaBarSize[0]),o=+(i/t.options.alphaBarSize[0]).toFixed(2);t.alphaPointer.style.left=i-7+"px",t.onValueChanged("ALPHA",o)},s=function e(){document.removeEventListener("mousemove",o),document.removeEventListener("mouseup",e)};e.addEventListener("mousedown",function(e){o(e),document.addEventListener("mousemove",o),document.addEventListener("mouseup",s)})}},{key:"setupInput",value:function(e){var t=this,r=+e.min,i=+e.max,o=e.getAttribute("nameref");e.hasAttribute("select-on-focus")&&e.addEventListener("focus",function(){e.select()}),"text"===e.type?e.addEventListener("change",function(){t.onValueChanged(o,e.value)}):((h||p)&&e.addEventListener("keydown",function(s){"Up"===s.key?(e.value=(0,n.limit)(+e.value+1,r,i),t.onValueChanged(o,e.value),s.returnValue=!1):"Down"===s.key&&(e.value=(0,n.limit)(+e.value-1,r,i),t.onValueChanged(o,e.value),s.returnValue=!1)}),e.addEventListener("change",function(){var s=+e.value;t.onValueChanged(o,(0,n.limit)(s,r,i))}))}},{key:"setupClipboard",value:function(e){var t=this;e.title="click to copy",e.addEventListener("click",function(){e.value=(0,n.parseColor)([t.R,t.G,t.B,t.A],"hexcss4"),e.select(),document.execCommand("copy")})}},{key:"setPalette",value:function(e){var t=this,r="auto"===this.options.useAlphaInPalette?this.options.showAlpha:this.options.useAlphaInPalette,i=null;switch(this.options.palette){case"PALETTE_MATERIAL_500":i=n.PALETTE_MATERIAL_500;break;case"PALETTE_MATERIAL_CHROME":i=n.PALETTE_MATERIAL_CHROME;break;default:i=(0,n.ensureArray)(this.options.palette)}if(this.options.paletteEditable||i.length>0){var o=function(r,i,o){var n=e.querySelector('.a-color-picker-palette-color[data-color="'+r+'"]')||document.createElement("div");n.className="a-color-picker-palette-color",n.style.backgroundColor=r,n.setAttribute("data-color",r),n.title=r,e.insertBefore(n,i),t.palette[r]=!0,o&&t.onPaletteColorAdd(r)},s=function(r,i){r?(e.removeChild(r),t.palette[r.getAttribute("data-color")]=!1,i&&t.onPaletteColorRemove(r.getAttribute("data-color"))):(e.querySelectorAll(".a-color-picker-palette-color[data-color]").forEach(function(t){e.removeChild(t)}),Object.keys(t.palette).forEach(function(e){t.palette[e]=!1}),i&&t.onPaletteColorRemove())};if(i.map(function(e){return(0,n.parseColor)(e,r?"rgbcss4":"hex")}).filter(function(e){return!!e}).forEach(function(e){return o(e)}),this.options.paletteEditable){var a=document.createElement("div");a.className="a-color-picker-palette-color a-color-picker-palette-add",a.innerHTML="+",e.appendChild(a),e.addEventListener("click",function(e){/a-color-picker-palette-add/.test(e.target.className)?e.shiftKey?s(null,!0):o(r?(0,n.parseColor)([t.R,t.G,t.B,t.A],"rgbcss4"):(0,n.rgbToHex)(t.R,t.G,t.B),e.target,!0):/a-color-picker-palette-color/.test(e.target.className)&&(e.shiftKey?s(e.target,!0):t.onValueChanged(f,e.target.getAttribute("data-color")))})}else e.addEventListener("click",function(e){/a-color-picker-palette-color/.test(e.target.className)&&t.onValueChanged(f,e.target.getAttribute("data-color"))})}else e.style.display="none"}},{key:"updatePalette",value:function(e){this.paletteRow.innerHTML="",this.palette={},this.paletteRow.parentElement||this.element.appendChild(this.paletteRow),this.options.palette=e,this.setPalette(this.paletteRow)}},{key:"onValueChanged",value:function(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{silent:!1};switch(e){case"H":this.H=t;var i=(0,n.hslToRgb)(this.H,this.S,this.L),s=o(i,3);this.R=s[0],this.G=s[1],this.B=s[2],this.slBarHelper.setHue(t),this.updatePointerH(this.H),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"S":this.S=t;var a=(0,n.hslToRgb)(this.H,this.S,this.L),l=o(a,3);this.R=l[0],this.G=l[1],this.B=l[2],this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"L":this.L=t;var c=(0,n.hslToRgb)(this.H,this.S,this.L),u=o(c,3);this.R=u[0],this.G=u[1],this.B=u[2],this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"R":this.R=t;var h=(0,n.rgbToHsl)(this.R,this.G,this.B),p=o(h,3);this.H=p[0],this.S=p[1],this.L=p[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"G":this.G=t;var d=(0,n.rgbToHsl)(this.R,this.G,this.B),v=o(d,3);this.H=v[0],this.S=v[1],this.L=v[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"B":this.B=t;var m=(0,n.rgbToHsl)(this.R,this.G,this.B),A=o(m,3);this.H=A[0],this.S=A[1],this.L=A[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGBHEX(this.R,this.G,this.B);break;case"RGB":var y=o(t,3);this.R=y[0],this.G=y[1],this.B=y[2];var k=(0,n.rgbToHsl)(this.R,this.G,this.B),F=o(k,3);this.H=F[0],this.S=F[1],this.L=F[2],this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B);break;case g:var E=o(t,4);this.R=E[0],this.G=E[1],this.B=E[2],this.A=E[3];var H=(0,n.rgbToHsl)(this.R,this.G,this.B),B=o(H,3);this.H=B[0],this.S=B[1],this.L=B[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B),this.updatePointerA(this.A);break;case b:var R=o(t,4);this.H=R[0],this.S=R[1],this.L=R[2],this.A=R[3];var C=(0,n.hslToRgb)(this.H,this.S,this.L),S=o(C,3);this.R=S[0],this.G=S[1],this.B=S[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B),this.updatePointerA(this.A);break;case"RGBHEX":var L=(0,n.cssColorToRgb)(t)||[this.R,this.G,this.B],w=o(L,3);this.R=w[0],this.G=w[1],this.B=w[2];var T=(0,n.rgbToHsl)(this.R,this.G,this.B),x=o(T,3);this.H=x[0],this.S=x[1],this.L=x[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B);break;case f:var G=(0,n.parseColor)(t,"rgba")||[0,0,0,1],I=o(G,4);this.R=I[0],this.G=I[1],this.B=I[2],this.A=I[3];var P=(0,n.rgbToHsl)(this.R,this.G,this.B),D=o(P,3);this.H=D[0],this.S=D[1],this.L=D[2],this.slBarHelper.setHue(this.H),this.updatePointerH(this.H),this.updatePointerSL(this.H,this.S,this.L),this.updateInputHSL(this.H,this.S,this.L),this.updateInputRGB(this.R,this.G,this.B),this.updateInputRGBHEX(this.R,this.G,this.B),this.updatePointerA(this.A);break;case"ALPHA":this.A=t}1===this.A?this.preview.style.backgroundColor="rgb("+this.R+","+this.G+","+this.B+")":this.preview.style.backgroundColor="rgba("+this.R+","+this.G+","+this.B+","+this.A+")",r&&r.silent||this.onchange&&this.onchange(this.preview.style.backgroundColor)}},{key:"onPaletteColorAdd",value:function(e){this.oncoloradd&&this.oncoloradd(e)}},{key:"onPaletteColorRemove",value:function(e){this.oncolorremove&&this.oncolorremove(e)}},{key:"updateInputHSL",value:function(e,t,r){this.options.showHSL&&(this.inputH.value=e,this.inputS.value=t,this.inputL.value=r)}},{key:"updateInputRGB",value:function(e,t,r){this.options.showRGB&&(this.inputR.value=e,this.inputG.value=t,this.inputB.value=r)}},{key:"updateInputRGBHEX",value:function(e,t,r){this.options.showHEX&&(this.inputRGBHEX.value=(0,n.rgbToHex)(e,t,r))}},{key:"updatePointerH",value:function(e){var t=this.options.hueBarSize[0]*e/360;this.huePointer.style.left=t-7+"px"}},{key:"updatePointerSL",value:function(e,t,r){var i=(0,n.hslToRgb)(e,t,r),s=o(i,3),a=s[0],l=s[1],c=s[2],u=this.slBarHelper.findColor(a,l,c),h=o(u,2),p=h[0],d=h[1];p>=0&&(this.slPointer.style.left=p-7+"px",this.slPointer.style.top=d-7+"px")}},{key:"updatePointerA",value:function(e){if(this.options.showAlpha){var t=this.options.alphaBarSize[0]*e;this.alphaPointer.style.left=t-7+"px"}}}]),e}(),F=function(){function e(t){c(this,e),this.name=t,this.listeners=[]}return i(e,[{key:"on",value:function(e){e&&this.listeners.push(e)}},{key:"off",value:function(e){this.listeners=e?this.listeners.filter(function(t){return t!==e}):[]}},{key:"emit",value:function(e,t){for(var r=this.listeners.slice(0),i=0;i<r.length;i++)r[i].apply(t,e)}}]),e}();function E(e,t){var r=new k(e,t),i={change:new F("change"),coloradd:new F("coloradd"),colorremove:new F("colorremove")},s=!0,a={},l={get element(){return r.element},get rgb(){return[r.R,r.G,r.B]},set rgb(e){var t=o(e,3),i=t[0],s=t[1],a=t[2],l=[(0,n.limit)(i,0,255),(0,n.limit)(s,0,255),(0,n.limit)(a,0,255)];i=l[0],s=l[1],a=l[2],r.onValueChanged(g,[i,s,a,1])},get hsl(){return[r.H,r.S,r.L]},set hsl(e){var t=o(e,3),i=t[0],s=t[1],a=t[2],l=[(0,n.limit)(i,0,360),(0,n.limit)(s,0,100),(0,n.limit)(a,0,100)];i=l[0],s=l[1],a=l[2],r.onValueChanged(b,[i,s,a,1])},get rgbhex(){return this.all.hex},get rgba(){return[r.R,r.G,r.B,r.A]},set rgba(e){var t=o(e,4),i=t[0],s=t[1],a=t[2],l=t[3],c=[(0,n.limit)(i,0,255),(0,n.limit)(s,0,255),(0,n.limit)(a,0,255),(0,n.limit)(l,0,1)];i=c[0],s=c[1],a=c[2],l=c[3],r.onValueChanged(g,[i,s,a,l])},get hsla(){return[r.H,r.S,r.L,r.A]},set hsla(e){var t=o(e,4),i=t[0],s=t[1],a=t[2],l=t[3],c=[(0,n.limit)(i,0,360),(0,n.limit)(s,0,100),(0,n.limit)(a,0,100),(0,n.limit)(l,0,1)];i=c[0],s=c[1],a=c[2],l=c[3],r.onValueChanged(b,[i,s,a,l])},get color(){return this.all.toString()},set color(e){r.onValueChanged(f,e)},setColor:function(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];r.onValueChanged(f,e,{silent:t})},get all(){if(s){var e=[r.R,r.G,r.B,r.A],t=r.A<1?"rgba("+r.R+","+r.G+","+r.B+","+r.A+")":n.rgbToHex.apply(void 0,e);(a=(0,n.parseColor)(e,a)).toString=function(){return t},s=!1}return Object.assign({},a)},get onchange(){return i.change&&i.change.listeners[0]},set onchange(e){this.off("change").on("change",e)},get oncoloradd(){return i.coloradd&&i.coloradd.listeners[0]},set oncoloradd(e){this.off("coloradd").on("coloradd",e)},get oncolorremove(){return i.colorremove&&i.colorremove.listeners[0]},set oncolorremove(e){this.off("colorremove").on("colorremove",e)},get palette(){return Object.keys(r.palette).filter(function(e){return r.palette[e]})},set palette(e){r.updatePalette(e)},show:function(){r.element.classList.remove("hidden")},hide:function(){r.element.classList.add("hidden")},toggle:function(){r.element.classList.toggle("hidden")},on:function(e,t){return e&&i[e]&&i[e].on(t),this},off:function(e,t){return e&&i[e]&&i[e].off(t),this},destroy:function(){i.change.off(),i.coloradd.off(),i.colorremove.off(),r.element.remove(),i=null,r=null}};return r.onchange=function(){for(var e=arguments.length,t=Array(e),r=0;r<e;r++)t[r]=arguments[r];s=!0,i.change.emit([l].concat(t),l)},r.oncoloradd=function(){for(var e=arguments.length,t=Array(e),r=0;r<e;r++)t[r]=arguments[r];i.coloradd.emit([l].concat(t),l)},r.oncolorremove=function(){for(var e=arguments.length,t=Array(e),r=0;r<e;r++)t[r]=arguments[r];i.colorremove.emit([l].concat(t),l)},r.element.ctrl=l,l}if("undefined"!=typeof window&&!document.querySelector('head>style[data-source="a-color-picker"]')){var H=r(5).toString(),B=document.createElement("style");B.setAttribute("type","text/css"),B.setAttribute("data-source","a-color-picker"),B.innerHTML=H,document.querySelector("head").appendChild(B)}t.createPicker=E,t.from=function(e,t){var r=function(e){return e?Array.isArray(e)?e:e instanceof HTMLElement?[e]:e instanceof NodeList?[].concat(u(e)):"string"==typeof e?[].concat(u(document.querySelectorAll(e))):e.jquery?e.get():[]:[]}(e).map(function(e,r){var i=E(e,t);return i.index=r,i});return r.on=function(e,t){return r.forEach(function(r){return r.on(e,t)}),this},r.off=function(e){return r.forEach(function(t){return t.off(e)}),this},r},t.parseColorToRgb=n.parseColorToRgb,t.parseColorToRgba=n.parseColorToRgba,t.parseColorToHsl=n.parseColorToHsl,t.parseColorToHsla=n.parseColorToHsla,t.parseColor=n.parseColor,t.rgbToHex=n.rgbToHex,t.hslToRgb=n.hslToRgb,t.rgbToHsl=n.rgbToHsl,t.rgbToHsv=n.rgbToHsv,t.rgbToInt=n.rgbToInt,t.intToRgb=n.intToRgb,t.getLuminance=n.getLuminance,t.COLOR_NAMES=n.COLOR_NAMES,t.PALETTE_MATERIAL_500=n.PALETTE_MATERIAL_500,t.PALETTE_MATERIAL_CHROME=n.PALETTE_MATERIAL_CHROME,t.VERSION="1.2.1"},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.nvl=t.ensureArray=t.limit=t.getLuminance=t.parseColor=t.parseColorToHsla=t.parseColorToHsl=t.cssHslaToHsla=t.cssHslToHsl=t.parseColorToRgba=t.parseColorToRgb=t.cssRgbaToRgba=t.cssRgbToRgb=t.cssColorToRgba=t.cssColorToRgb=t.intToRgb=t.rgbToInt=t.rgbToHsv=t.rgbToHsl=t.hslToRgb=t.rgbToHex=t.PALETTE_MATERIAL_CHROME=t.PALETTE_MATERIAL_500=t.COLOR_NAMES=void 0;var i=function(e,t){if(Array.isArray(e))return e;if(Symbol.iterator in Object(e))return function(e,t){var r=[],i=!0,o=!1,n=void 0;try{for(var s,a=e[Symbol.iterator]();!(i=(s=a.next()).done)&&(r.push(s.value),!t||r.length!==t);i=!0);}catch(e){o=!0,n=e}finally{try{!i&&a.return&&a.return()}finally{if(o)throw n}}return r}(e,t);throw new TypeError("Invalid attempt to destructure non-iterable instance")},o=function(e){return e&&e.__esModule?e:{default:e}}(r(0));function n(e){if(Array.isArray(e)){for(var t=0,r=Array(e.length);t<e.length;t++)r[t]=e[t];return r}return Array.from(e)}var s={aliceblue:"#F0F8FF",antiquewhite:"#FAEBD7",aqua:"#00FFFF",aquamarine:"#7FFFD4",azure:"#F0FFFF",beige:"#F5F5DC",bisque:"#FFE4C4",black:"#000000",blanchedalmond:"#FFEBCD",blue:"#0000FF",blueviolet:"#8A2BE2",brown:"#A52A2A",burlywood:"#DEB887",cadetblue:"#5F9EA0",chartreuse:"#7FFF00",chocolate:"#D2691E",coral:"#FF7F50",cornflowerblue:"#6495ED",cornsilk:"#FFF8DC",crimson:"#DC143C",cyan:"#00FFFF",darkblue:"#00008B",darkcyan:"#008B8B",darkgoldenrod:"#B8860B",darkgray:"#A9A9A9",darkgrey:"#A9A9A9",darkgreen:"#006400",darkkhaki:"#BDB76B",darkmagenta:"#8B008B",darkolivegreen:"#556B2F",darkorange:"#FF8C00",darkorchid:"#9932CC",darkred:"#8B0000",darksalmon:"#E9967A",darkseagreen:"#8FBC8F",darkslateblue:"#483D8B",darkslategray:"#2F4F4F",darkslategrey:"#2F4F4F",darkturquoise:"#00CED1",darkviolet:"#9400D3",deeppink:"#FF1493",deepskyblue:"#00BFFF",dimgray:"#696969",dimgrey:"#696969",dodgerblue:"#1E90FF",firebrick:"#B22222",floralwhite:"#FFFAF0",forestgreen:"#228B22",fuchsia:"#FF00FF",gainsboro:"#DCDCDC",ghostwhite:"#F8F8FF",gold:"#FFD700",goldenrod:"#DAA520",gray:"#808080",grey:"#808080",green:"#008000",greenyellow:"#ADFF2F",honeydew:"#F0FFF0",hotpink:"#FF69B4","indianred ":"#CD5C5C","indigo ":"#4B0082",ivory:"#FFFFF0",khaki:"#F0E68C",lavender:"#E6E6FA",lavenderblush:"#FFF0F5",lawngreen:"#7CFC00",lemonchiffon:"#FFFACD",lightblue:"#ADD8E6",lightcoral:"#F08080",lightcyan:"#E0FFFF",lightgoldenrodyellow:"#FAFAD2",lightgray:"#D3D3D3",lightgrey:"#D3D3D3",lightgreen:"#90EE90",lightpink:"#FFB6C1",lightsalmon:"#FFA07A",lightseagreen:"#20B2AA",lightskyblue:"#87CEFA",lightslategray:"#778899",lightslategrey:"#778899",lightsteelblue:"#B0C4DE",lightyellow:"#FFFFE0",lime:"#00FF00",limegreen:"#32CD32",linen:"#FAF0E6",magenta:"#FF00FF",maroon:"#800000",mediumaquamarine:"#66CDAA",mediumblue:"#0000CD",mediumorchid:"#BA55D3",mediumpurple:"#9370DB",mediumseagreen:"#3CB371",mediumslateblue:"#7B68EE",mediumspringgreen:"#00FA9A",mediumturquoise:"#48D1CC",mediumvioletred:"#C71585",midnightblue:"#191970",mintcream:"#F5FFFA",mistyrose:"#FFE4E1",moccasin:"#FFE4B5",navajowhite:"#FFDEAD",navy:"#000080",oldlace:"#FDF5E6",olive:"#808000",olivedrab:"#6B8E23",orange:"#FFA500",orangered:"#FF4500",orchid:"#DA70D6",palegoldenrod:"#EEE8AA",palegreen:"#98FB98",paleturquoise:"#AFEEEE",palevioletred:"#DB7093",papayawhip:"#FFEFD5",peachpuff:"#FFDAB9",peru:"#CD853F",pink:"#FFC0CB",plum:"#DDA0DD",powderblue:"#B0E0E6",purple:"#800080",rebeccapurple:"#663399",red:"#FF0000",rosybrown:"#BC8F8F",royalblue:"#4169E1",saddlebrown:"#8B4513",salmon:"#FA8072",sandybrown:"#F4A460",seagreen:"#2E8B57",seashell:"#FFF5EE",sienna:"#A0522D",silver:"#C0C0C0",skyblue:"#87CEEB",slateblue:"#6A5ACD",slategray:"#708090",slategrey:"#708090",snow:"#FFFAFA",springgreen:"#00FF7F",steelblue:"#4682B4",tan:"#D2B48C",teal:"#008080",thistle:"#D8BFD8",tomato:"#FF6347",turquoise:"#40E0D0",violet:"#EE82EE",wheat:"#F5DEB3",white:"#FFFFFF",whitesmoke:"#F5F5F5",yellow:"#FFFF00",yellowgreen:"#9ACD32"};function a(e,t,r){return e=+e,isNaN(e)?t:e<t?t:e>r?r:e}function l(e,t){return null==e?t:e}function c(e,t,r){var i=[a(e,0,255),a(t,0,255),a(r,0,255)];return"#"+("000000"+((e=i[0])<<16|(t=i[1])<<8|(r=i[2])).toString(16)).slice(-6)}function u(e,t,r){var i=void 0,o=void 0,n=void 0,s=[a(e,0,360)/360,a(t,0,100)/100,a(r,0,100)/100];if(e=s[0],r=s[2],0==(t=s[1]))i=o=n=r;else{var l=function(e,t,r){return r<0&&(r+=1),r>1&&(r-=1),r<1/6?e+6*(t-e)*r:r<.5?t:r<2/3?e+(t-e)*(2/3-r)*6:e},c=r<.5?r*(1+t):r+t-r*t,u=2*r-c;i=l(u,c,e+1/3),o=l(u,c,e),n=l(u,c,e-1/3)}return[255*i,255*o,255*n].map(Math.round)}function h(e,t,r){var i=[a(e,0,255)/255,a(t,0,255)/255,a(r,0,255)/255];e=i[0],t=i[1],r=i[2];var o=Math.max(e,t,r),n=Math.min(e,t,r),s=void 0,l=void 0,c=(o+n)/2;if(o==n)s=l=0;else{var u=o-n;switch(l=c>.5?u/(2-o-n):u/(o+n),o){case e:s=(t-r)/u+(t<r?6:0);break;case t:s=(r-e)/u+2;break;case r:s=(e-t)/u+4}s/=6}return[360*s,100*l,100*c].map(Math.round)}function p(e,t,r){return e<<16|t<<8|r}function d(e){if(e){var t=s[e.toString().toLowerCase()],r=/^\s*#?((([0-9A-F])([0-9A-F])([0-9A-F]))|(([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})))\s*$/i.exec(t||e)||[],o=i(r,10),n=o[3],a=o[4],l=o[5],c=o[7],u=o[8],h=o[9];if(void 0!==n)return[parseInt(n+n,16),parseInt(a+a,16),parseInt(l+l,16)];if(void 0!==c)return[parseInt(c,16),parseInt(u,16),parseInt(h,16)]}}function f(e){if(e){var t=s[e.toString().toLowerCase()],r=/^\s*#?((([0-9A-F])([0-9A-F])([0-9A-F])([0-9A-F])?)|(([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})?))\s*$/i.exec(t||e)||[],o=i(r,12),n=o[3],a=o[4],l=o[5],c=o[6],u=o[8],h=o[9],p=o[10],d=o[11];if(void 0!==n)return[parseInt(n+n,16),parseInt(a+a,16),parseInt(l+l,16),c?+(parseInt(c+c,16)/255).toFixed(2):1];if(void 0!==u)return[parseInt(u,16),parseInt(h,16),parseInt(p,16),d?+(parseInt(d,16)/255).toFixed(2):1]}}function g(e){if(e){var t=/^rgb\((\d+)[\s,](\d+)[\s,](\d+)\)/i.exec(e)||[],r=i(t,4),o=r[0],n=r[1],s=r[2],l=r[3];return o?[a(n,0,255),a(s,0,255),a(l,0,255)]:void 0}}function b(e){if(e){var t=/^rgba?\((\d+)\s*[\s,]\s*(\d+)\s*[\s,]\s*(\d+)(\s*[\s,]\s*(\d*(.\d+)?))?\)/i.exec(e)||[],r=i(t,6),o=r[0],n=r[1],s=r[2],c=r[3],u=r[5];return o?[a(n,0,255),a(s,0,255),a(c,0,255),a(l(u,1),0,1)]:void 0}}function v(e){if(Array.isArray(e))return[a(e[0],0,255),a(e[1],0,255),a(e[2],0,255),a(l(e[3],1),0,1)];var t=f(e)||b(e);return t&&3===t.length&&t.push(1),t}function m(e){if(e){var t=/^hsl\((\d+)[\s,](\d+)[\s,](\d+)\)/i.exec(e)||[],r=i(t,4),o=r[0],n=r[1],s=r[2],l=r[3];return o?[a(n,0,360),a(s,0,100),a(l,0,100)]:void 0}}function A(e){if(e){var t=/^hsla?\((\d+)\s*[\s,]\s*(\d+)\s*[\s,]\s*(\d+)(\s*[\s,]\s*(\d*(.\d+)?))?\)/i.exec(e)||[],r=i(t,6),o=r[0],n=r[1],s=r[2],c=r[3],u=r[5];return o?[a(n,0,255),a(s,0,255),a(c,0,255),a(l(u,1),0,1)]:void 0}}function y(e){if(Array.isArray(e))return[a(e[0],0,360),a(e[1],0,100),a(e[2],0,100),a(l(e[3],1),0,1)];var t=A(e);return t&&3===t.length&&t.push(1),t}function k(e,t){switch(t){case"rgb":default:return e.slice(0,3);case"rgbcss":return"rgb("+e[0]+", "+e[1]+", "+e[2]+")";case"rgbcss4":return"rgb("+e[0]+", "+e[1]+", "+e[2]+", "+e[3]+")";case"rgba":return e;case"rgbacss":return"rgba("+e[0]+", "+e[1]+", "+e[2]+", "+e[3]+")";case"hsl":return h.apply(void 0,n(e));case"hslcss":return"hsl("+(e=h.apply(void 0,n(e)))[0]+", "+e[1]+", "+e[2]+")";case"hslcss4":var r=h.apply(void 0,n(e));return"hsl("+r[0]+", "+r[1]+", "+r[2]+", "+e[3]+")";case"hsla":return[].concat(n(h.apply(void 0,n(e))),[e[3]]);case"hslacss":var i=h.apply(void 0,n(e));return"hsla("+i[0]+", "+i[1]+", "+i[2]+", "+e[3]+")";case"hex":return c.apply(void 0,n(e));case"hexcss4":return c.apply(void 0,n(e))+("00"+parseInt(255*e[3]).toString(16)).slice(-2);case"int":return p.apply(void 0,n(e))}}t.COLOR_NAMES=s,t.PALETTE_MATERIAL_500=["#F44336","#E91E63","#E91E63","#9C27B0","#9C27B0","#673AB7","#673AB7","#3F51B5","#3F51B5","#2196F3","#2196F3","#03A9F4","#03A9F4","#00BCD4","#00BCD4","#009688","#009688","#4CAF50","#4CAF50","#8BC34A","#8BC34A","#CDDC39","#CDDC39","#FFEB3B","#FFEB3B","#FFC107","#FFC107","#FF9800","#FF9800","#FF5722","#FF5722","#795548","#795548","#9E9E9E","#9E9E9E","#607D8B","#607D8B"],t.PALETTE_MATERIAL_CHROME=["#f44336","#e91e63","#9c27b0","#673ab7","#3f51b5","#2196f3","#03a9f4","#00bcd4","#009688","#4caf50","#8bc34a","#cddc39","#ffeb3b","#ffc107","#ff9800","#ff5722","#795548","#9e9e9e","#607d8b"],t.rgbToHex=c,t.hslToRgb=u,t.rgbToHsl=h,t.rgbToHsv=function(e,t,r){var i=[a(e,0,255)/255,a(t,0,255)/255,a(r,0,255)/255];e=i[0],t=i[1],r=i[2];var o,n=Math.max(e,t,r),s=Math.min(e,t,r),l=void 0,c=n,u=n-s;if(o=0===n?0:u/n,n==s)l=0;else{switch(n){case e:l=(t-r)/u+(t<r?6:0);break;case t:l=(r-e)/u+2;break;case r:l=(e-t)/u+4}l/=6}return[l,o,c]},t.rgbToInt=p,t.intToRgb=function(e){return[e>>16&255,e>>8&255,255&e]},t.cssColorToRgb=d,t.cssColorToRgba=f,t.cssRgbToRgb=g,t.cssRgbaToRgba=b,t.parseColorToRgb=function(e){return Array.isArray(e)?e=[a(e[0],0,255),a(e[1],0,255),a(e[2],0,255)]:d(e)||g(e)},t.parseColorToRgba=v,t.cssHslToHsl=m,t.cssHslaToHsla=A,t.parseColorToHsl=function(e){return Array.isArray(e)?e=[a(e[0],0,360),a(e[1],0,100),a(e[2],0,100)]:m(e)},t.parseColorToHsla=y,t.parseColor=function(e,t){if(t=t||"rgb",null!=e){var r=void 0;if((r=v(e))||(r=y(e))&&(r=[].concat(n(u.apply(void 0,n(r))),[r[3]])))return(0,o.default)(t)?["rgb","rgbcss","rgbcss4","rgba","rgbacss","hsl","hslcss","hslcss4","hsla","hslacss","hex","hexcss4","int"].reduce(function(e,t){return e[t]=k(r,t),e},t||{}):k(r,t.toString().toLowerCase())}},t.getLuminance=function(e,t,r){return.2126*(e=(e/=255)<.03928?e/12.92:Math.pow((e+.055)/1.055,2.4))+.7152*(t=(t/=255)<.03928?t/12.92:Math.pow((t+.055)/1.055,2.4))+.0722*((r/=255)<.03928?r/12.92:Math.pow((r+.055)/1.055,2.4))},t.limit=a,t.ensureArray=function(e){return e?Array.from(e):[]},t.nvl=l},function(e,t,r){"use strict";
/*!
 * isobject <https://github.com/jonschlinkert/isobject>
 *
 * Copyright (c) 2014-2017, Jon Schlinkert.
 * Released under the MIT License.
 */e.exports=function(e){return null!=e&&"object"==typeof e&&!1===Array.isArray(e)}},function(e,t){e.exports='<div class="a-color-picker-row a-color-picker-stack a-color-picker-row-top"> <canvas class="a-color-picker-sl a-color-picker-transparent"></canvas> <div class=a-color-picker-dot></div> </div> <div class=a-color-picker-row> <div class="a-color-picker-stack a-color-picker-transparent a-color-picker-circle"> <div class=a-color-picker-preview> <input class=a-color-picker-clipbaord type=text> </div> </div> <div class=a-color-picker-column> <div class="a-color-picker-cell a-color-picker-stack"> <canvas class=a-color-picker-h></canvas> <div class=a-color-picker-dot></div> </div> <div class="a-color-picker-cell a-color-picker-alpha a-color-picker-stack" show-on-alpha> <canvas class="a-color-picker-a a-color-picker-transparent"></canvas> <div class=a-color-picker-dot></div> </div> </div> </div> <div class="a-color-picker-row a-color-picker-hsl" show-on-hsl> <label>H</label> <input nameref=H type=number maxlength=3 min=0 max=360 value=0> <label>S</label> <input nameref=S type=number maxlength=3 min=0 max=100 value=0> <label>L</label> <input nameref=L type=number maxlength=3 min=0 max=100 value=0> </div> <div class="a-color-picker-row a-color-picker-rgb" show-on-rgb> <label>R</label> <input nameref=R type=number maxlength=3 min=0 max=255 value=0> <label>G</label> <input nameref=G type=number maxlength=3 min=0 max=255 value=0> <label>B</label> <input nameref=B type=number maxlength=3 min=0 max=255 value=0> </div> <div class="a-color-picker-row a-color-picker-rgbhex a-color-picker-single-input" show-on-single-input> <label>HEX</label> <input nameref=RGBHEX type=text select-on-focus> </div> <div class="a-color-picker-row a-color-picker-palette"></div>'},function(e,t,r){var i=r(6);e.exports="string"==typeof i?i:i.toString()},function(e,t,r){(e.exports=r(7)(!1)).push([e.i,"/*!\n * a-color-picker\n * https://github.com/narsenico/a-color-picker\n *\n * Copyright (c) 2017-2018, Gianfranco Caldi.\n * Released under the MIT License.\n */.a-color-picker{background-color:#fff;padding:0;display:inline-flex;flex-direction:column;user-select:none;width:232px;font:400 10px Helvetica,Arial,sans-serif;border-radius:3px;box-shadow:0 0 0 1px rgba(0,0,0,.05),0 2px 4px rgba(0,0,0,.25)}.a-color-picker,.a-color-picker-row,.a-color-picker input{box-sizing:border-box}.a-color-picker-row{padding:15px;display:flex;flex-direction:row;align-items:center;justify-content:space-between;user-select:none}.a-color-picker-row-top{padding:0}.a-color-picker-row:not(:first-child){border-top:1px solid #f5f5f5}.a-color-picker-column{display:flex;flex-direction:column}.a-color-picker-cell{flex:1 1 auto;margin-bottom:4px}.a-color-picker-cell:last-child{margin-bottom:0}.a-color-picker-stack{position:relative}.a-color-picker-dot{position:absolute;width:14px;height:14px;top:0;left:0;background:#fff;pointer-events:none;border-radius:50px;z-index:1000;box-shadow:0 1px 2px rgba(0,0,0,.75)}.a-color-picker-a,.a-color-picker-h,.a-color-picker-sl{cursor:cell}.a-color-picker-a+.a-color-picker-dot,.a-color-picker-h+.a-color-picker-dot{top:-2px}.a-color-picker-a,.a-color-picker-h{border-radius:2px}.a-color-picker-preview{box-sizing:border-box;width:30px;height:30px;user-select:none;border-radius:15px}.a-color-picker-circle{border-radius:50px;border:1px solid #eee}.a-color-picker-hsl,.a-color-picker-rgb,.a-color-picker-single-input{justify-content:space-evenly}.a-color-picker-hsl>label,.a-color-picker-rgb>label,.a-color-picker-single-input>label{padding:0 8px;flex:0 0 auto;color:#969696}.a-color-picker-hsl>input,.a-color-picker-rgb>input,.a-color-picker-single-input>input{text-align:center;padding:2px 0;width:0;flex:1 1 auto;border:1px solid #e0e0e0;line-height:20px}.a-color-picker-hsl>input::-webkit-inner-spin-button,.a-color-picker-rgb>input::-webkit-inner-spin-button,.a-color-picker-single-input>input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.a-color-picker-hsl>input:focus,.a-color-picker-rgb>input:focus,.a-color-picker-single-input>input:focus{border-color:#04a9f4;outline:none}.a-color-picker-transparent{background-image:linear-gradient(-45deg,#cdcdcd 25%,transparent 0),linear-gradient(45deg,#cdcdcd 25%,transparent 0),linear-gradient(-45deg,transparent 75%,#cdcdcd 0),linear-gradient(45deg,transparent 75%,#cdcdcd 0);background-size:11px 11px;background-position:0 0,0 -5.5px,-5.5px 5.5px,5.5px 0}.a-color-picker-sl{border-radius:3px 3px 0 0}.a-color-picker.hide-alpha [show-on-alpha],.a-color-picker.hide-hsl [show-on-hsl],.a-color-picker.hide-rgb [show-on-rgb],.a-color-picker.hide-single-input [show-on-single-input]{display:none}.a-color-picker-clipbaord{width:100%;height:100%;opacity:0;cursor:pointer}.a-color-picker-palette{flex-flow:wrap;flex-direction:row;justify-content:flex-start;padding:10px}.a-color-picker-palette-color{width:15px;height:15px;flex:0 1 15px;margin:3px;box-sizing:border-box;cursor:pointer;border-radius:3px;box-shadow:inset 0 0 0 1px rgba(0,0,0,.1)}.a-color-picker-palette-add{text-align:center;line-height:13px;color:#607d8b}.a-color-picker.hidden{display:none}",""])},function(e,t){e.exports=function(e){var t=[];return t.toString=function(){return this.map(function(t){var r=function(e,t){var r=e[1]||"",i=e[3];if(!i)return r;if(t&&"function"==typeof btoa){var o=function(e){return"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(e))))+" */"}(i),n=i.sources.map(function(e){return"/*# sourceURL="+i.sourceRoot+e+" */"});return[r].concat(n).concat([o]).join("\n")}return[r].join("\n")}(t,e);return t[2]?"@media "+t[2]+"{"+r+"}":r}).join("")},t.i=function(e,r){"string"==typeof e&&(e=[[null,e,""]]);for(var i={},o=0;o<this.length;o++){var n=this[o][0];"number"==typeof n&&(i[n]=!0)}for(o=0;o<e.length;o++){var s=e[o];"number"==typeof s[0]&&i[s[0]]||(r&&!s[2]?s[2]=r:r&&(s[2]="("+s[2]+") and ("+r+")"),t.push(s))}},t}}])});

/***/ }),

/***/ "../frontend-assets-libs/resources/dist/xss/xss.js":
/*!*********************************************************!*\
  !*** ../frontend-assets-libs/resources/dist/xss/xss.js ***!
  \*********************************************************/
/***/ (() => {

(function () {
  function r(e, n, t) {
    function o(i, f) {
      if (!n[i]) {
        if (!e[i]) {
          var c = undefined;
          if (!f && c) return require(i, !0);
          if (u) return u(i, !0);
          var a = new Error("Cannot find module '" + i + "'");
          throw a.code = "MODULE_NOT_FOUND", a;
        }
        var p = n[i] = {
          exports: {}
        };
        e[i][0].call(p.exports, function (r) {
          var n = e[i][1][r];
          return o(n || r);
        }, p, p.exports, r, e, n, t);
      }
      return n[i].exports;
    }
    for (var u = undefined, i = 0; i < t.length; i++) o(t[i]);
    return o;
  }
  return r;
})()({
  1: [function (require, module, exports) {
    /**
     * default settings
     *
     * @author Zongmin Lei<leizongmin@gmail.com>
     */

    var FilterCSS = require("cssfilter").FilterCSS;
    var getDefaultCSSWhiteList = require("cssfilter").getDefaultWhiteList;
    var _ = require("./util");
    function getDefaultWhiteList() {
      return {
        a: ["target", "href", "title"],
        abbr: ["title"],
        address: [],
        area: ["shape", "coords", "href", "alt"],
        article: [],
        aside: [],
        audio: ["autoplay", "controls", "crossorigin", "loop", "muted", "preload", "src"],
        b: [],
        bdi: ["dir"],
        bdo: ["dir"],
        big: [],
        blockquote: ["cite"],
        br: [],
        caption: [],
        center: [],
        cite: [],
        code: [],
        col: ["align", "valign", "span", "width"],
        colgroup: ["align", "valign", "span", "width"],
        dd: [],
        del: ["datetime"],
        details: ["open"],
        div: [],
        dl: [],
        dt: [],
        em: [],
        figcaption: [],
        figure: [],
        font: ["color", "size", "face"],
        footer: [],
        h1: [],
        h2: [],
        h3: [],
        h4: [],
        h5: [],
        h6: [],
        header: [],
        hr: [],
        i: [],
        img: ["src", "alt", "title", "width", "height", "loading"],
        ins: ["datetime"],
        kbd: [],
        li: [],
        mark: [],
        nav: [],
        ol: [],
        p: [],
        pre: [],
        s: [],
        section: [],
        small: [],
        span: [],
        sub: [],
        summary: [],
        sup: [],
        strong: [],
        strike: [],
        table: ["width", "border", "align", "valign"],
        tbody: ["align", "valign"],
        td: ["width", "rowspan", "colspan", "align", "valign"],
        tfoot: ["align", "valign"],
        th: ["width", "rowspan", "colspan", "align", "valign"],
        thead: ["align", "valign"],
        tr: ["rowspan", "align", "valign"],
        tt: [],
        u: [],
        ul: [],
        video: ["autoplay", "controls", "crossorigin", "loop", "muted", "playsinline", "poster", "preload", "src", "height", "width"]
      };
    }
    var defaultCSSFilter = new FilterCSS();

    /**
     * default onTag function
     *
     * @param {String} tag
     * @param {String} html
     * @param {Object} options
     * @return {String}
     */
    function onTag(tag, html, options) {
      // do nothing
    }

    /**
     * default onIgnoreTag function
     *
     * @param {String} tag
     * @param {String} html
     * @param {Object} options
     * @return {String}
     */
    function onIgnoreTag(tag, html, options) {
      // do nothing
    }

    /**
     * default onTagAttr function
     *
     * @param {String} tag
     * @param {String} name
     * @param {String} value
     * @return {String}
     */
    function onTagAttr(tag, name, value) {
      // do nothing
    }

    /**
     * default onIgnoreTagAttr function
     *
     * @param {String} tag
     * @param {String} name
     * @param {String} value
     * @return {String}
     */
    function onIgnoreTagAttr(tag, name, value) {
      // do nothing
    }

    /**
     * default escapeHtml function
     *
     * @param {String} html
     */
    function escapeHtml(html) {
      return html.replace(REGEXP_LT, "&lt;").replace(REGEXP_GT, "&gt;");
    }

    /**
     * default safeAttrValue function
     *
     * @param {String} tag
     * @param {String} name
     * @param {String} value
     * @param {Object} cssFilter
     * @return {String}
     */
    function safeAttrValue(tag, name, value, cssFilter) {
      // unescape attribute value firstly
      value = friendlyAttrValue(value);
      if (name === "href" || name === "src") {
        // filter `href` and `src` attribute
        // only allow the value that starts with `http://` | `https://` | `mailto:` | `/` | `#`
        value = _.trim(value);
        if (value === "#") return "#";
        if (!(value.substr(0, 7) === "http://" || value.substr(0, 8) === "https://" || value.substr(0, 7) === "mailto:" || value.substr(0, 4) === "tel:" || value.substr(0, 11) === "data:image/" || value.substr(0, 6) === "ftp://" || value.substr(0, 2) === "./" || value.substr(0, 3) === "../" || value[0] === "#" || value[0] === "/")) {
          return "";
        }
      } else if (name === "background") {
        // filter `background` attribute (maybe no use)
        // `javascript:`
        REGEXP_DEFAULT_ON_TAG_ATTR_4.lastIndex = 0;
        if (REGEXP_DEFAULT_ON_TAG_ATTR_4.test(value)) {
          return "";
        }
      } else if (name === "style") {
        // `expression()`
        REGEXP_DEFAULT_ON_TAG_ATTR_7.lastIndex = 0;
        if (REGEXP_DEFAULT_ON_TAG_ATTR_7.test(value)) {
          return "";
        }
        // `url()`
        REGEXP_DEFAULT_ON_TAG_ATTR_8.lastIndex = 0;
        if (REGEXP_DEFAULT_ON_TAG_ATTR_8.test(value)) {
          REGEXP_DEFAULT_ON_TAG_ATTR_4.lastIndex = 0;
          if (REGEXP_DEFAULT_ON_TAG_ATTR_4.test(value)) {
            return "";
          }
        }
        if (cssFilter !== false) {
          cssFilter = cssFilter || defaultCSSFilter;
          value = cssFilter.process(value);
        }
      }

      // escape `<>"` before returns
      value = escapeAttrValue(value);
      return value;
    }

    // RegExp list
    var REGEXP_LT = /</g;
    var REGEXP_GT = />/g;
    var REGEXP_QUOTE = /"/g;
    var REGEXP_QUOTE_2 = /&quot;/g;
    var REGEXP_ATTR_VALUE_1 = /&#([a-zA-Z0-9]*);?/gim;
    var REGEXP_ATTR_VALUE_COLON = /&colon;?/gim;
    var REGEXP_ATTR_VALUE_NEWLINE = /&newline;?/gim;
    // var REGEXP_DEFAULT_ON_TAG_ATTR_3 = /\/\*|\*\//gm;
    var REGEXP_DEFAULT_ON_TAG_ATTR_4 = /((j\s*a\s*v\s*a|v\s*b|l\s*i\s*v\s*e)\s*s\s*c\s*r\s*i\s*p\s*t\s*|m\s*o\s*c\s*h\s*a):/gi;
    // var REGEXP_DEFAULT_ON_TAG_ATTR_5 = /^[\s"'`]*(d\s*a\s*t\s*a\s*)\:/gi;
    // var REGEXP_DEFAULT_ON_TAG_ATTR_6 = /^[\s"'`]*(d\s*a\s*t\s*a\s*)\:\s*image\//gi;
    var REGEXP_DEFAULT_ON_TAG_ATTR_7 = /e\s*x\s*p\s*r\s*e\s*s\s*s\s*i\s*o\s*n\s*\(.*/gi;
    var REGEXP_DEFAULT_ON_TAG_ATTR_8 = /u\s*r\s*l\s*\(.*/gi;

    /**
     * escape double quote
     *
     * @param {String} str
     * @return {String} str
     */
    function escapeQuote(str) {
      return str.replace(REGEXP_QUOTE, "&quot;");
    }

    /**
     * unescape double quote
     *
     * @param {String} str
     * @return {String} str
     */
    function unescapeQuote(str) {
      return str.replace(REGEXP_QUOTE_2, '"');
    }

    /**
     * escape html entities
     *
     * @param {String} str
     * @return {String}
     */
    function escapeHtmlEntities(str) {
      return str.replace(REGEXP_ATTR_VALUE_1, function replaceUnicode(str, code) {
        return code[0] === "x" || code[0] === "X" ? String.fromCharCode(parseInt(code.substr(1), 16)) : String.fromCharCode(parseInt(code, 10));
      });
    }

    /**
     * escape html5 new danger entities
     *
     * @param {String} str
     * @return {String}
     */
    function escapeDangerHtml5Entities(str) {
      return str.replace(REGEXP_ATTR_VALUE_COLON, ":").replace(REGEXP_ATTR_VALUE_NEWLINE, " ");
    }

    /**
     * clear nonprintable characters
     *
     * @param {String} str
     * @return {String}
     */
    function clearNonPrintableCharacter(str) {
      var str2 = "";
      for (var i = 0, len = str.length; i < len; i++) {
        str2 += str.charCodeAt(i) < 32 ? " " : str.charAt(i);
      }
      return _.trim(str2);
    }

    /**
     * get friendly attribute value
     *
     * @param {String} str
     * @return {String}
     */
    function friendlyAttrValue(str) {
      str = unescapeQuote(str);
      str = escapeHtmlEntities(str);
      str = escapeDangerHtml5Entities(str);
      str = clearNonPrintableCharacter(str);
      return str;
    }

    /**
     * unescape attribute value
     *
     * @param {String} str
     * @return {String}
     */
    function escapeAttrValue(str) {
      str = escapeQuote(str);
      str = escapeHtml(str);
      return str;
    }

    /**
     * `onIgnoreTag` function for removing all the tags that are not in whitelist
     */
    function onIgnoreTagStripAll() {
      return "";
    }

    /**
     * remove tag body
     * specify a `tags` list, if the tag is not in the `tags` list then process by the specify function (optional)
     *
     * @param {array} tags
     * @param {function} next
     */
    function StripTagBody(tags, next) {
      if (typeof next !== "function") {
        next = function next() {};
      }
      var isRemoveAllTag = !Array.isArray(tags);
      function isRemoveTag(tag) {
        if (isRemoveAllTag) return true;
        return _.indexOf(tags, tag) !== -1;
      }
      var removeList = [];
      var posStart = false;
      return {
        onIgnoreTag: function onIgnoreTag(tag, html, options) {
          if (isRemoveTag(tag)) {
            if (options.isClosing) {
              var ret = "[/removed]";
              var end = options.position + ret.length;
              removeList.push([posStart !== false ? posStart : options.position, end]);
              posStart = false;
              return ret;
            } else {
              if (!posStart) {
                posStart = options.position;
              }
              return "[removed]";
            }
          } else {
            return next(tag, html, options);
          }
        },
        remove: function remove(html) {
          var rethtml = "";
          var lastPos = 0;
          _.forEach(removeList, function (pos) {
            rethtml += html.slice(lastPos, pos[0]);
            lastPos = pos[1];
          });
          rethtml += html.slice(lastPos);
          return rethtml;
        }
      };
    }

    /**
     * remove html comments
     *
     * @param {String} html
     * @return {String}
     */
    function stripCommentTag(html) {
      var retHtml = "";
      var lastPos = 0;
      while (lastPos < html.length) {
        var i = html.indexOf("<!--", lastPos);
        if (i === -1) {
          retHtml += html.slice(lastPos);
          break;
        }
        retHtml += html.slice(lastPos, i);
        var j = html.indexOf("-->", i);
        if (j === -1) {
          break;
        }
        lastPos = j + 3;
      }
      return retHtml;
    }

    /**
     * remove invisible characters
     *
     * @param {String} html
     * @return {String}
     */
    function stripBlankChar(html) {
      var chars = html.split("");
      chars = chars.filter(function (_char) {
        var c = _char.charCodeAt(0);
        if (c === 127) return false;
        if (c <= 31) {
          if (c === 10 || c === 13) return true;
          return false;
        }
        return true;
      });
      return chars.join("");
    }
    exports.whiteList = getDefaultWhiteList();
    exports.getDefaultWhiteList = getDefaultWhiteList;
    exports.onTag = onTag;
    exports.onIgnoreTag = onIgnoreTag;
    exports.onTagAttr = onTagAttr;
    exports.onIgnoreTagAttr = onIgnoreTagAttr;
    exports.safeAttrValue = safeAttrValue;
    exports.escapeHtml = escapeHtml;
    exports.escapeQuote = escapeQuote;
    exports.unescapeQuote = unescapeQuote;
    exports.escapeHtmlEntities = escapeHtmlEntities;
    exports.escapeDangerHtml5Entities = escapeDangerHtml5Entities;
    exports.clearNonPrintableCharacter = clearNonPrintableCharacter;
    exports.friendlyAttrValue = friendlyAttrValue;
    exports.escapeAttrValue = escapeAttrValue;
    exports.onIgnoreTagStripAll = onIgnoreTagStripAll;
    exports.StripTagBody = StripTagBody;
    exports.stripCommentTag = stripCommentTag;
    exports.stripBlankChar = stripBlankChar;
    exports.attributeWrapSign = '"';
    exports.cssFilter = defaultCSSFilter;
    exports.getDefaultCSSWhiteList = getDefaultCSSWhiteList;
  }, {
    "./util": 4,
    "cssfilter": 8
  }],
  2: [function (require, module, exports) {
    /**
     * xss
     *
     * @author Zongmin Lei<leizongmin@gmail.com>
     */

    var DEFAULT = require("./default");
    var parser = require("./parser");
    var FilterXSS = require("./xss");

    /**
     * filter xss function
     *
     * @param {String} html
     * @param {Object} options { whiteList, onTag, onTagAttr, onIgnoreTag, onIgnoreTagAttr, safeAttrValue, escapeHtml }
     * @return {String}
     */
    function filterXSS(html, options) {
      var xss = new FilterXSS(options);
      return xss.process(html);
    }
    exports = module.exports = filterXSS;
    exports.filterXSS = filterXSS;
    exports.FilterXSS = FilterXSS;
    (function () {
      for (var i in DEFAULT) {
        exports[i] = DEFAULT[i];
      }
      for (var j in parser) {
        exports[j] = parser[j];
      }
    })();

    // using `xss` on the browser, output `filterXSS` to the globals
    if (typeof window !== "undefined") {
      window.filterXSS = module.exports;
    }

    // using `xss` on the WebWorker, output `filterXSS` to the globals
    function isWorkerEnv() {
      return typeof self !== "undefined" && typeof DedicatedWorkerGlobalScope !== "undefined" && self instanceof DedicatedWorkerGlobalScope;
    }
    if (isWorkerEnv()) {
      self.filterXSS = module.exports;
    }
  }, {
    "./default": 1,
    "./parser": 3,
    "./xss": 5
  }],
  3: [function (require, module, exports) {
    /**
     * Simple HTML Parser
     *
     * @author Zongmin Lei<leizongmin@gmail.com>
     */

    var _ = require("./util");

    /**
     * get tag name
     *
     * @param {String} html e.g. '<a hef="#">'
     * @return {String}
     */
    function getTagName(html) {
      var i = _.spaceIndex(html);
      var tagName;
      if (i === -1) {
        tagName = html.slice(1, -1);
      } else {
        tagName = html.slice(1, i + 1);
      }
      tagName = _.trim(tagName).toLowerCase();
      if (tagName.slice(0, 1) === "/") tagName = tagName.slice(1);
      if (tagName.slice(-1) === "/") tagName = tagName.slice(0, -1);
      return tagName;
    }

    /**
     * is close tag?
     *
     * @param {String} html 如：'<a hef="#">'
     * @return {Boolean}
     */
    function isClosing(html) {
      return html.slice(0, 2) === "</";
    }

    /**
     * parse input html and returns processed html
     *
     * @param {String} html
     * @param {Function} onTag e.g. function (sourcePosition, position, tag, html, isClosing)
     * @param {Function} escapeHtml
     * @return {String}
     */
    function parseTag(html, onTag, escapeHtml) {
      "use strict";

      var rethtml = "";
      var lastPos = 0;
      var tagStart = false;
      var quoteStart = false;
      var currentPos = 0;
      var len = html.length;
      var currentTagName = "";
      var currentHtml = "";
      chariterator: for (currentPos = 0; currentPos < len; currentPos++) {
        var c = html.charAt(currentPos);
        if (tagStart === false) {
          if (c === "<") {
            tagStart = currentPos;
            continue;
          }
        } else {
          if (quoteStart === false) {
            if (c === "<") {
              rethtml += escapeHtml(html.slice(lastPos, currentPos));
              tagStart = currentPos;
              lastPos = currentPos;
              continue;
            }
            if (c === ">" || currentPos === len - 1) {
              rethtml += escapeHtml(html.slice(lastPos, tagStart));
              currentHtml = html.slice(tagStart, currentPos + 1);
              currentTagName = getTagName(currentHtml);
              rethtml += onTag(tagStart, rethtml.length, currentTagName, currentHtml, isClosing(currentHtml));
              lastPos = currentPos + 1;
              tagStart = false;
              continue;
            }
            if (c === '"' || c === "'") {
              var i = 1;
              var ic = html.charAt(currentPos - i);
              while (ic.trim() === "" || ic === "=") {
                if (ic === "=") {
                  quoteStart = c;
                  continue chariterator;
                }
                ic = html.charAt(currentPos - ++i);
              }
            }
          } else {
            if (c === quoteStart) {
              quoteStart = false;
              continue;
            }
          }
        }
      }
      if (lastPos < len) {
        rethtml += escapeHtml(html.substr(lastPos));
      }
      return rethtml;
    }
    var REGEXP_ILLEGAL_ATTR_NAME = /[^a-zA-Z0-9\\_:.-]/gim;

    /**
     * parse input attributes and returns processed attributes
     *
     * @param {String} html e.g. `href="#" target="_blank"`
     * @param {Function} onAttr e.g. `function (name, value)`
     * @return {String}
     */
    function parseAttr(html, onAttr) {
      "use strict";

      var lastPos = 0;
      var lastMarkPos = 0;
      var retAttrs = [];
      var tmpName = false;
      var len = html.length;
      function addAttr(name, value) {
        name = _.trim(name);
        name = name.replace(REGEXP_ILLEGAL_ATTR_NAME, "").toLowerCase();
        if (name.length < 1) return;
        var ret = onAttr(name, value || "");
        if (ret) retAttrs.push(ret);
      }

      // 逐个分析字符
      for (var i = 0; i < len; i++) {
        var c = html.charAt(i);
        var v, j;
        if (tmpName === false && c === "=") {
          tmpName = html.slice(lastPos, i);
          lastPos = i + 1;
          lastMarkPos = html.charAt(lastPos) === '"' || html.charAt(lastPos) === "'" ? lastPos : findNextQuotationMark(html, i + 1);
          continue;
        }
        if (tmpName !== false) {
          if (i === lastMarkPos) {
            j = html.indexOf(c, i + 1);
            if (j === -1) {
              break;
            } else {
              v = _.trim(html.slice(lastMarkPos + 1, j));
              addAttr(tmpName, v);
              tmpName = false;
              i = j;
              lastPos = i + 1;
              continue;
            }
          }
        }
        if (/\s|\n|\t/.test(c)) {
          html = html.replace(/\s|\n|\t/g, " ");
          if (tmpName === false) {
            j = findNextEqual(html, i);
            if (j === -1) {
              v = _.trim(html.slice(lastPos, i));
              addAttr(v);
              tmpName = false;
              lastPos = i + 1;
              continue;
            } else {
              i = j - 1;
              continue;
            }
          } else {
            j = findBeforeEqual(html, i - 1);
            if (j === -1) {
              v = _.trim(html.slice(lastPos, i));
              v = stripQuoteWrap(v);
              addAttr(tmpName, v);
              tmpName = false;
              lastPos = i + 1;
              continue;
            } else {
              continue;
            }
          }
        }
      }
      if (lastPos < html.length) {
        if (tmpName === false) {
          addAttr(html.slice(lastPos));
        } else {
          addAttr(tmpName, stripQuoteWrap(_.trim(html.slice(lastPos))));
        }
      }
      return _.trim(retAttrs.join(" "));
    }
    function findNextEqual(str, i) {
      for (; i < str.length; i++) {
        var c = str[i];
        if (c === " ") continue;
        if (c === "=") return i;
        return -1;
      }
    }
    function findNextQuotationMark(str, i) {
      for (; i < str.length; i++) {
        var c = str[i];
        if (c === " ") continue;
        if (c === "'" || c === '"') return i;
        return -1;
      }
    }
    function findBeforeEqual(str, i) {
      for (; i > 0; i--) {
        var c = str[i];
        if (c === " ") continue;
        if (c === "=") return i;
        return -1;
      }
    }
    function isQuoteWrapString(text) {
      if (text[0] === '"' && text[text.length - 1] === '"' || text[0] === "'" && text[text.length - 1] === "'") {
        return true;
      } else {
        return false;
      }
    }
    function stripQuoteWrap(text) {
      if (isQuoteWrapString(text)) {
        return text.substr(1, text.length - 2);
      } else {
        return text;
      }
    }
    exports.parseTag = parseTag;
    exports.parseAttr = parseAttr;
  }, {
    "./util": 4
  }],
  4: [function (require, module, exports) {
    module.exports = {
      indexOf: function indexOf(arr, item) {
        var i, j;
        if (Array.prototype.indexOf) {
          return arr.indexOf(item);
        }
        for (i = 0, j = arr.length; i < j; i++) {
          if (arr[i] === item) {
            return i;
          }
        }
        return -1;
      },
      forEach: function forEach(arr, fn, scope) {
        var i, j;
        if (Array.prototype.forEach) {
          return arr.forEach(fn, scope);
        }
        for (i = 0, j = arr.length; i < j; i++) {
          fn.call(scope, arr[i], i, arr);
        }
      },
      trim: function trim(str) {
        if (String.prototype.trim) {
          return str.trim();
        }
        return str.replace(/(^\s*)|(\s*$)/g, "");
      },
      spaceIndex: function spaceIndex(str) {
        var reg = /\s|\n|\t/;
        var match = reg.exec(str);
        return match ? match.index : -1;
      }
    };
  }, {}],
  5: [function (require, module, exports) {
    /**
     * filter xss
     *
     * @author Zongmin Lei<leizongmin@gmail.com>
     */

    var FilterCSS = require("cssfilter").FilterCSS;
    var DEFAULT = require("./default");
    var parser = require("./parser");
    var parseTag = parser.parseTag;
    var parseAttr = parser.parseAttr;
    var _ = require("./util");

    /**
     * returns `true` if the input value is `undefined` or `null`
     *
     * @param {Object} obj
     * @return {Boolean}
     */
    function isNull(obj) {
      return obj === undefined || obj === null;
    }

    /**
     * get attributes for a tag
     *
     * @param {String} html
     * @return {Object}
     *   - {String} html
     *   - {Boolean} closing
     */
    function getAttrs(html) {
      var i = _.spaceIndex(html);
      if (i === -1) {
        return {
          html: "",
          closing: html[html.length - 2] === "/"
        };
      }
      html = _.trim(html.slice(i + 1, -1));
      var isClosing = html[html.length - 1] === "/";
      if (isClosing) html = _.trim(html.slice(0, -1));
      return {
        html: html,
        closing: isClosing
      };
    }

    /**
     * shallow copy
     *
     * @param {Object} obj
     * @return {Object}
     */
    function shallowCopyObject(obj) {
      var ret = {};
      for (var i in obj) {
        ret[i] = obj[i];
      }
      return ret;
    }
    function keysToLowerCase(obj) {
      var ret = {};
      for (var i in obj) {
        if (Array.isArray(obj[i])) {
          ret[i.toLowerCase()] = obj[i].map(function (item) {
            return item.toLowerCase();
          });
        } else {
          ret[i.toLowerCase()] = obj[i];
        }
      }
      return ret;
    }

    /**
     * FilterXSS class
     *
     * @param {Object} options
     *        whiteList (or allowList), onTag, onTagAttr, onIgnoreTag,
     *        onIgnoreTagAttr, safeAttrValue, escapeHtml
     *        stripIgnoreTagBody, allowCommentTag, stripBlankChar
     *        css{whiteList, onAttr, onIgnoreAttr} `css=false` means don't use `cssfilter`
     */
    function FilterXSS(options) {
      options = shallowCopyObject(options || {});
      if (options.stripIgnoreTag) {
        if (options.onIgnoreTag) {
          console.error('Notes: cannot use these two options "stripIgnoreTag" and "onIgnoreTag" at the same time');
        }
        options.onIgnoreTag = DEFAULT.onIgnoreTagStripAll;
      }
      if (options.whiteList || options.allowList) {
        options.whiteList = keysToLowerCase(options.whiteList || options.allowList);
      } else {
        options.whiteList = DEFAULT.whiteList;
      }
      this.attributeWrapSign = options.singleQuotedAttributeValue === true ? "'" : DEFAULT.attributeWrapSign;
      options.onTag = options.onTag || DEFAULT.onTag;
      options.onTagAttr = options.onTagAttr || DEFAULT.onTagAttr;
      options.onIgnoreTag = options.onIgnoreTag || DEFAULT.onIgnoreTag;
      options.onIgnoreTagAttr = options.onIgnoreTagAttr || DEFAULT.onIgnoreTagAttr;
      options.safeAttrValue = options.safeAttrValue || DEFAULT.safeAttrValue;
      options.escapeHtml = options.escapeHtml || DEFAULT.escapeHtml;
      this.options = options;
      if (options.css === false) {
        this.cssFilter = false;
      } else {
        options.css = options.css || {};
        this.cssFilter = new FilterCSS(options.css);
      }
    }

    /**
     * start process and returns result
     *
     * @param {String} html
     * @return {String}
     */
    FilterXSS.prototype.process = function (html) {
      // compatible with the input
      html = html || "";
      html = html.toString();
      if (!html) return "";
      var me = this;
      var options = me.options;
      var whiteList = options.whiteList;
      var onTag = options.onTag;
      var onIgnoreTag = options.onIgnoreTag;
      var onTagAttr = options.onTagAttr;
      var onIgnoreTagAttr = options.onIgnoreTagAttr;
      var safeAttrValue = options.safeAttrValue;
      var escapeHtml = options.escapeHtml;
      var attributeWrapSign = me.attributeWrapSign;
      var cssFilter = me.cssFilter;

      // remove invisible characters
      if (options.stripBlankChar) {
        html = DEFAULT.stripBlankChar(html);
      }

      // remove html comments
      if (!options.allowCommentTag) {
        html = DEFAULT.stripCommentTag(html);
      }

      // if enable stripIgnoreTagBody
      var stripIgnoreTagBody = false;
      if (options.stripIgnoreTagBody) {
        stripIgnoreTagBody = DEFAULT.StripTagBody(options.stripIgnoreTagBody, onIgnoreTag);
        onIgnoreTag = stripIgnoreTagBody.onIgnoreTag;
      }
      var retHtml = parseTag(html, function (sourcePosition, position, tag, html, isClosing) {
        var info = {
          sourcePosition: sourcePosition,
          position: position,
          isClosing: isClosing,
          isWhite: Object.prototype.hasOwnProperty.call(whiteList, tag)
        };

        // call `onTag()`
        var ret = onTag(tag, html, info);
        if (!isNull(ret)) return ret;
        if (info.isWhite) {
          if (info.isClosing) {
            return "</" + tag + ">";
          }
          var attrs = getAttrs(html);
          var whiteAttrList = whiteList[tag];
          var attrsHtml = parseAttr(attrs.html, function (name, value) {
            // call `onTagAttr()`
            var isWhiteAttr = _.indexOf(whiteAttrList, name) !== -1;
            var ret = onTagAttr(tag, name, value, isWhiteAttr);
            if (!isNull(ret)) return ret;
            if (isWhiteAttr) {
              // call `safeAttrValue()`
              value = safeAttrValue(tag, name, value, cssFilter);
              if (value) {
                return name + '=' + attributeWrapSign + value + attributeWrapSign;
              } else {
                return name;
              }
            } else {
              // call `onIgnoreTagAttr()`
              ret = onIgnoreTagAttr(tag, name, value, isWhiteAttr);
              if (!isNull(ret)) return ret;
              return;
            }
          });

          // build new tag html
          html = "<" + tag;
          if (attrsHtml) html += " " + attrsHtml;
          if (attrs.closing) html += " /";
          html += ">";
          return html;
        } else {
          // call `onIgnoreTag()`
          ret = onIgnoreTag(tag, html, info);
          if (!isNull(ret)) return ret;
          return escapeHtml(html);
        }
      }, escapeHtml);

      // if enable stripIgnoreTagBody
      if (stripIgnoreTagBody) {
        retHtml = stripIgnoreTagBody.remove(retHtml);
      }
      return retHtml;
    };
    module.exports = FilterXSS;
  }, {
    "./default": 1,
    "./parser": 3,
    "./util": 4,
    "cssfilter": 8
  }],
  6: [function (require, module, exports) {
    /**
     * cssfilter
     *
     * @author 老雷<leizongmin@gmail.com>
     */

    var DEFAULT = require('./default');
    var parseStyle = require('./parser');
    var _ = require('./util');

    /**
     * 返回值是否为空
     *
     * @param {Object} obj
     * @return {Boolean}
     */
    function isNull(obj) {
      return obj === undefined || obj === null;
    }

    /**
     * 浅拷贝对象
     *
     * @param {Object} obj
     * @return {Object}
     */
    function shallowCopyObject(obj) {
      var ret = {};
      for (var i in obj) {
        ret[i] = obj[i];
      }
      return ret;
    }

    /**
     * 创建CSS过滤器
     *
     * @param {Object} options
     *   - {Object} whiteList
     *   - {Function} onAttr
     *   - {Function} onIgnoreAttr
     *   - {Function} safeAttrValue
     */
    function FilterCSS(options) {
      options = shallowCopyObject(options || {});
      options.whiteList = options.whiteList || DEFAULT.whiteList;
      options.onAttr = options.onAttr || DEFAULT.onAttr;
      options.onIgnoreAttr = options.onIgnoreAttr || DEFAULT.onIgnoreAttr;
      options.safeAttrValue = options.safeAttrValue || DEFAULT.safeAttrValue;
      this.options = options;
    }
    FilterCSS.prototype.process = function (css) {
      // 兼容各种奇葩输入
      css = css || '';
      css = css.toString();
      if (!css) return '';
      var me = this;
      var options = me.options;
      var whiteList = options.whiteList;
      var onAttr = options.onAttr;
      var onIgnoreAttr = options.onIgnoreAttr;
      var safeAttrValue = options.safeAttrValue;
      var retCSS = parseStyle(css, function (sourcePosition, position, name, value, source) {
        var check = whiteList[name];
        var isWhite = false;
        if (check === true) isWhite = check;else if (typeof check === 'function') isWhite = check(value);else if (check instanceof RegExp) isWhite = check.test(value);
        if (isWhite !== true) isWhite = false;

        // 如果过滤后 value 为空则直接忽略
        value = safeAttrValue(name, value);
        if (!value) return;
        var opts = {
          position: position,
          sourcePosition: sourcePosition,
          source: source,
          isWhite: isWhite
        };
        if (isWhite) {
          var ret = onAttr(name, value, opts);
          if (isNull(ret)) {
            return name + ':' + value;
          } else {
            return ret;
          }
        } else {
          var ret = onIgnoreAttr(name, value, opts);
          if (!isNull(ret)) {
            return ret;
          }
        }
      });
      return retCSS;
    };
    module.exports = FilterCSS;
  }, {
    "./default": 7,
    "./parser": 9,
    "./util": 10
  }],
  7: [function (require, module, exports) {
    /**
     * cssfilter
     *
     * @author 老雷<leizongmin@gmail.com>
     */

    function getDefaultWhiteList() {
      // 白名单值说明：
      // true: 允许该属性
      // Function: function (val) { } 返回true表示允许该属性，其他值均表示不允许
      // RegExp: regexp.test(val) 返回true表示允许该属性，其他值均表示不允许
      // 除上面列出的值外均表示不允许
      var whiteList = {};
      whiteList['align-content'] = false; // default: auto
      whiteList['align-items'] = false; // default: auto
      whiteList['align-self'] = false; // default: auto
      whiteList['alignment-adjust'] = false; // default: auto
      whiteList['alignment-baseline'] = false; // default: baseline
      whiteList['all'] = false; // default: depending on individual properties
      whiteList['anchor-point'] = false; // default: none
      whiteList['animation'] = false; // default: depending on individual properties
      whiteList['animation-delay'] = false; // default: 0
      whiteList['animation-direction'] = false; // default: normal
      whiteList['animation-duration'] = false; // default: 0
      whiteList['animation-fill-mode'] = false; // default: none
      whiteList['animation-iteration-count'] = false; // default: 1
      whiteList['animation-name'] = false; // default: none
      whiteList['animation-play-state'] = false; // default: running
      whiteList['animation-timing-function'] = false; // default: ease
      whiteList['azimuth'] = false; // default: center
      whiteList['backface-visibility'] = false; // default: visible
      whiteList['background'] = true; // default: depending on individual properties
      whiteList['background-attachment'] = true; // default: scroll
      whiteList['background-clip'] = true; // default: border-box
      whiteList['background-color'] = true; // default: transparent
      whiteList['background-image'] = true; // default: none
      whiteList['background-origin'] = true; // default: padding-box
      whiteList['background-position'] = true; // default: 0% 0%
      whiteList['background-repeat'] = true; // default: repeat
      whiteList['background-size'] = true; // default: auto
      whiteList['baseline-shift'] = false; // default: baseline
      whiteList['binding'] = false; // default: none
      whiteList['bleed'] = false; // default: 6pt
      whiteList['bookmark-label'] = false; // default: content()
      whiteList['bookmark-level'] = false; // default: none
      whiteList['bookmark-state'] = false; // default: open
      whiteList['border'] = true; // default: depending on individual properties
      whiteList['border-bottom'] = true; // default: depending on individual properties
      whiteList['border-bottom-color'] = true; // default: current color
      whiteList['border-bottom-left-radius'] = true; // default: 0
      whiteList['border-bottom-right-radius'] = true; // default: 0
      whiteList['border-bottom-style'] = true; // default: none
      whiteList['border-bottom-width'] = true; // default: medium
      whiteList['border-collapse'] = true; // default: separate
      whiteList['border-color'] = true; // default: depending on individual properties
      whiteList['border-image'] = true; // default: none
      whiteList['border-image-outset'] = true; // default: 0
      whiteList['border-image-repeat'] = true; // default: stretch
      whiteList['border-image-slice'] = true; // default: 100%
      whiteList['border-image-source'] = true; // default: none
      whiteList['border-image-width'] = true; // default: 1
      whiteList['border-left'] = true; // default: depending on individual properties
      whiteList['border-left-color'] = true; // default: current color
      whiteList['border-left-style'] = true; // default: none
      whiteList['border-left-width'] = true; // default: medium
      whiteList['border-radius'] = true; // default: 0
      whiteList['border-right'] = true; // default: depending on individual properties
      whiteList['border-right-color'] = true; // default: current color
      whiteList['border-right-style'] = true; // default: none
      whiteList['border-right-width'] = true; // default: medium
      whiteList['border-spacing'] = true; // default: 0
      whiteList['border-style'] = true; // default: depending on individual properties
      whiteList['border-top'] = true; // default: depending on individual properties
      whiteList['border-top-color'] = true; // default: current color
      whiteList['border-top-left-radius'] = true; // default: 0
      whiteList['border-top-right-radius'] = true; // default: 0
      whiteList['border-top-style'] = true; // default: none
      whiteList['border-top-width'] = true; // default: medium
      whiteList['border-width'] = true; // default: depending on individual properties
      whiteList['bottom'] = false; // default: auto
      whiteList['box-decoration-break'] = true; // default: slice
      whiteList['box-shadow'] = true; // default: none
      whiteList['box-sizing'] = true; // default: content-box
      whiteList['box-snap'] = true; // default: none
      whiteList['box-suppress'] = true; // default: show
      whiteList['break-after'] = true; // default: auto
      whiteList['break-before'] = true; // default: auto
      whiteList['break-inside'] = true; // default: auto
      whiteList['caption-side'] = false; // default: top
      whiteList['chains'] = false; // default: none
      whiteList['clear'] = true; // default: none
      whiteList['clip'] = false; // default: auto
      whiteList['clip-path'] = false; // default: none
      whiteList['clip-rule'] = false; // default: nonzero
      whiteList['color'] = true; // default: implementation dependent
      whiteList['color-interpolation-filters'] = true; // default: auto
      whiteList['column-count'] = false; // default: auto
      whiteList['column-fill'] = false; // default: balance
      whiteList['column-gap'] = false; // default: normal
      whiteList['column-rule'] = false; // default: depending on individual properties
      whiteList['column-rule-color'] = false; // default: current color
      whiteList['column-rule-style'] = false; // default: medium
      whiteList['column-rule-width'] = false; // default: medium
      whiteList['column-span'] = false; // default: none
      whiteList['column-width'] = false; // default: auto
      whiteList['columns'] = false; // default: depending on individual properties
      whiteList['contain'] = false; // default: none
      whiteList['content'] = false; // default: normal
      whiteList['counter-increment'] = false; // default: none
      whiteList['counter-reset'] = false; // default: none
      whiteList['counter-set'] = false; // default: none
      whiteList['crop'] = false; // default: auto
      whiteList['cue'] = false; // default: depending on individual properties
      whiteList['cue-after'] = false; // default: none
      whiteList['cue-before'] = false; // default: none
      whiteList['cursor'] = false; // default: auto
      whiteList['direction'] = false; // default: ltr
      whiteList['display'] = true; // default: depending on individual properties
      whiteList['display-inside'] = true; // default: auto
      whiteList['display-list'] = true; // default: none
      whiteList['display-outside'] = true; // default: inline-level
      whiteList['dominant-baseline'] = false; // default: auto
      whiteList['elevation'] = false; // default: level
      whiteList['empty-cells'] = false; // default: show
      whiteList['filter'] = false; // default: none
      whiteList['flex'] = false; // default: depending on individual properties
      whiteList['flex-basis'] = false; // default: auto
      whiteList['flex-direction'] = false; // default: row
      whiteList['flex-flow'] = false; // default: depending on individual properties
      whiteList['flex-grow'] = false; // default: 0
      whiteList['flex-shrink'] = false; // default: 1
      whiteList['flex-wrap'] = false; // default: nowrap
      whiteList['float'] = false; // default: none
      whiteList['float-offset'] = false; // default: 0 0
      whiteList['flood-color'] = false; // default: black
      whiteList['flood-opacity'] = false; // default: 1
      whiteList['flow-from'] = false; // default: none
      whiteList['flow-into'] = false; // default: none
      whiteList['font'] = true; // default: depending on individual properties
      whiteList['font-family'] = true; // default: implementation dependent
      whiteList['font-feature-settings'] = true; // default: normal
      whiteList['font-kerning'] = true; // default: auto
      whiteList['font-language-override'] = true; // default: normal
      whiteList['font-size'] = true; // default: medium
      whiteList['font-size-adjust'] = true; // default: none
      whiteList['font-stretch'] = true; // default: normal
      whiteList['font-style'] = true; // default: normal
      whiteList['font-synthesis'] = true; // default: weight style
      whiteList['font-variant'] = true; // default: normal
      whiteList['font-variant-alternates'] = true; // default: normal
      whiteList['font-variant-caps'] = true; // default: normal
      whiteList['font-variant-east-asian'] = true; // default: normal
      whiteList['font-variant-ligatures'] = true; // default: normal
      whiteList['font-variant-numeric'] = true; // default: normal
      whiteList['font-variant-position'] = true; // default: normal
      whiteList['font-weight'] = true; // default: normal
      whiteList['grid'] = false; // default: depending on individual properties
      whiteList['grid-area'] = false; // default: depending on individual properties
      whiteList['grid-auto-columns'] = false; // default: auto
      whiteList['grid-auto-flow'] = false; // default: none
      whiteList['grid-auto-rows'] = false; // default: auto
      whiteList['grid-column'] = false; // default: depending on individual properties
      whiteList['grid-column-end'] = false; // default: auto
      whiteList['grid-column-start'] = false; // default: auto
      whiteList['grid-row'] = false; // default: depending on individual properties
      whiteList['grid-row-end'] = false; // default: auto
      whiteList['grid-row-start'] = false; // default: auto
      whiteList['grid-template'] = false; // default: depending on individual properties
      whiteList['grid-template-areas'] = false; // default: none
      whiteList['grid-template-columns'] = false; // default: none
      whiteList['grid-template-rows'] = false; // default: none
      whiteList['hanging-punctuation'] = false; // default: none
      whiteList['height'] = true; // default: auto
      whiteList['hyphens'] = false; // default: manual
      whiteList['icon'] = false; // default: auto
      whiteList['image-orientation'] = false; // default: auto
      whiteList['image-resolution'] = false; // default: normal
      whiteList['ime-mode'] = false; // default: auto
      whiteList['initial-letters'] = false; // default: normal
      whiteList['inline-box-align'] = false; // default: last
      whiteList['justify-content'] = false; // default: auto
      whiteList['justify-items'] = false; // default: auto
      whiteList['justify-self'] = false; // default: auto
      whiteList['left'] = false; // default: auto
      whiteList['letter-spacing'] = true; // default: normal
      whiteList['lighting-color'] = true; // default: white
      whiteList['line-box-contain'] = false; // default: block inline replaced
      whiteList['line-break'] = false; // default: auto
      whiteList['line-grid'] = false; // default: match-parent
      whiteList['line-height'] = false; // default: normal
      whiteList['line-snap'] = false; // default: none
      whiteList['line-stacking'] = false; // default: depending on individual properties
      whiteList['line-stacking-ruby'] = false; // default: exclude-ruby
      whiteList['line-stacking-shift'] = false; // default: consider-shifts
      whiteList['line-stacking-strategy'] = false; // default: inline-line-height
      whiteList['list-style'] = true; // default: depending on individual properties
      whiteList['list-style-image'] = true; // default: none
      whiteList['list-style-position'] = true; // default: outside
      whiteList['list-style-type'] = true; // default: disc
      whiteList['margin'] = true; // default: depending on individual properties
      whiteList['margin-bottom'] = true; // default: 0
      whiteList['margin-left'] = true; // default: 0
      whiteList['margin-right'] = true; // default: 0
      whiteList['margin-top'] = true; // default: 0
      whiteList['marker-offset'] = false; // default: auto
      whiteList['marker-side'] = false; // default: list-item
      whiteList['marks'] = false; // default: none
      whiteList['mask'] = false; // default: border-box
      whiteList['mask-box'] = false; // default: see individual properties
      whiteList['mask-box-outset'] = false; // default: 0
      whiteList['mask-box-repeat'] = false; // default: stretch
      whiteList['mask-box-slice'] = false; // default: 0 fill
      whiteList['mask-box-source'] = false; // default: none
      whiteList['mask-box-width'] = false; // default: auto
      whiteList['mask-clip'] = false; // default: border-box
      whiteList['mask-image'] = false; // default: none
      whiteList['mask-origin'] = false; // default: border-box
      whiteList['mask-position'] = false; // default: center
      whiteList['mask-repeat'] = false; // default: no-repeat
      whiteList['mask-size'] = false; // default: border-box
      whiteList['mask-source-type'] = false; // default: auto
      whiteList['mask-type'] = false; // default: luminance
      whiteList['max-height'] = true; // default: none
      whiteList['max-lines'] = false; // default: none
      whiteList['max-width'] = true; // default: none
      whiteList['min-height'] = true; // default: 0
      whiteList['min-width'] = true; // default: 0
      whiteList['move-to'] = false; // default: normal
      whiteList['nav-down'] = false; // default: auto
      whiteList['nav-index'] = false; // default: auto
      whiteList['nav-left'] = false; // default: auto
      whiteList['nav-right'] = false; // default: auto
      whiteList['nav-up'] = false; // default: auto
      whiteList['object-fit'] = false; // default: fill
      whiteList['object-position'] = false; // default: 50% 50%
      whiteList['opacity'] = false; // default: 1
      whiteList['order'] = false; // default: 0
      whiteList['orphans'] = false; // default: 2
      whiteList['outline'] = false; // default: depending on individual properties
      whiteList['outline-color'] = false; // default: invert
      whiteList['outline-offset'] = false; // default: 0
      whiteList['outline-style'] = false; // default: none
      whiteList['outline-width'] = false; // default: medium
      whiteList['overflow'] = false; // default: depending on individual properties
      whiteList['overflow-wrap'] = false; // default: normal
      whiteList['overflow-x'] = false; // default: visible
      whiteList['overflow-y'] = false; // default: visible
      whiteList['padding'] = true; // default: depending on individual properties
      whiteList['padding-bottom'] = true; // default: 0
      whiteList['padding-left'] = true; // default: 0
      whiteList['padding-right'] = true; // default: 0
      whiteList['padding-top'] = true; // default: 0
      whiteList['page'] = false; // default: auto
      whiteList['page-break-after'] = false; // default: auto
      whiteList['page-break-before'] = false; // default: auto
      whiteList['page-break-inside'] = false; // default: auto
      whiteList['page-policy'] = false; // default: start
      whiteList['pause'] = false; // default: implementation dependent
      whiteList['pause-after'] = false; // default: implementation dependent
      whiteList['pause-before'] = false; // default: implementation dependent
      whiteList['perspective'] = false; // default: none
      whiteList['perspective-origin'] = false; // default: 50% 50%
      whiteList['pitch'] = false; // default: medium
      whiteList['pitch-range'] = false; // default: 50
      whiteList['play-during'] = false; // default: auto
      whiteList['position'] = false; // default: static
      whiteList['presentation-level'] = false; // default: 0
      whiteList['quotes'] = false; // default: text
      whiteList['region-fragment'] = false; // default: auto
      whiteList['resize'] = false; // default: none
      whiteList['rest'] = false; // default: depending on individual properties
      whiteList['rest-after'] = false; // default: none
      whiteList['rest-before'] = false; // default: none
      whiteList['richness'] = false; // default: 50
      whiteList['right'] = false; // default: auto
      whiteList['rotation'] = false; // default: 0
      whiteList['rotation-point'] = false; // default: 50% 50%
      whiteList['ruby-align'] = false; // default: auto
      whiteList['ruby-merge'] = false; // default: separate
      whiteList['ruby-position'] = false; // default: before
      whiteList['shape-image-threshold'] = false; // default: 0.0
      whiteList['shape-outside'] = false; // default: none
      whiteList['shape-margin'] = false; // default: 0
      whiteList['size'] = false; // default: auto
      whiteList['speak'] = false; // default: auto
      whiteList['speak-as'] = false; // default: normal
      whiteList['speak-header'] = false; // default: once
      whiteList['speak-numeral'] = false; // default: continuous
      whiteList['speak-punctuation'] = false; // default: none
      whiteList['speech-rate'] = false; // default: medium
      whiteList['stress'] = false; // default: 50
      whiteList['string-set'] = false; // default: none
      whiteList['tab-size'] = false; // default: 8
      whiteList['table-layout'] = false; // default: auto
      whiteList['text-align'] = true; // default: start
      whiteList['text-align-last'] = true; // default: auto
      whiteList['text-combine-upright'] = true; // default: none
      whiteList['text-decoration'] = true; // default: none
      whiteList['text-decoration-color'] = true; // default: currentColor
      whiteList['text-decoration-line'] = true; // default: none
      whiteList['text-decoration-skip'] = true; // default: objects
      whiteList['text-decoration-style'] = true; // default: solid
      whiteList['text-emphasis'] = true; // default: depending on individual properties
      whiteList['text-emphasis-color'] = true; // default: currentColor
      whiteList['text-emphasis-position'] = true; // default: over right
      whiteList['text-emphasis-style'] = true; // default: none
      whiteList['text-height'] = true; // default: auto
      whiteList['text-indent'] = true; // default: 0
      whiteList['text-justify'] = true; // default: auto
      whiteList['text-orientation'] = true; // default: mixed
      whiteList['text-overflow'] = true; // default: clip
      whiteList['text-shadow'] = true; // default: none
      whiteList['text-space-collapse'] = true; // default: collapse
      whiteList['text-transform'] = true; // default: none
      whiteList['text-underline-position'] = true; // default: auto
      whiteList['text-wrap'] = true; // default: normal
      whiteList['top'] = false; // default: auto
      whiteList['transform'] = false; // default: none
      whiteList['transform-origin'] = false; // default: 50% 50% 0
      whiteList['transform-style'] = false; // default: flat
      whiteList['transition'] = false; // default: depending on individual properties
      whiteList['transition-delay'] = false; // default: 0s
      whiteList['transition-duration'] = false; // default: 0s
      whiteList['transition-property'] = false; // default: all
      whiteList['transition-timing-function'] = false; // default: ease
      whiteList['unicode-bidi'] = false; // default: normal
      whiteList['vertical-align'] = false; // default: baseline
      whiteList['visibility'] = false; // default: visible
      whiteList['voice-balance'] = false; // default: center
      whiteList['voice-duration'] = false; // default: auto
      whiteList['voice-family'] = false; // default: implementation dependent
      whiteList['voice-pitch'] = false; // default: medium
      whiteList['voice-range'] = false; // default: medium
      whiteList['voice-rate'] = false; // default: normal
      whiteList['voice-stress'] = false; // default: normal
      whiteList['voice-volume'] = false; // default: medium
      whiteList['volume'] = false; // default: medium
      whiteList['white-space'] = false; // default: normal
      whiteList['widows'] = false; // default: 2
      whiteList['width'] = true; // default: auto
      whiteList['will-change'] = false; // default: auto
      whiteList['word-break'] = true; // default: normal
      whiteList['word-spacing'] = true; // default: normal
      whiteList['word-wrap'] = true; // default: normal
      whiteList['wrap-flow'] = false; // default: auto
      whiteList['wrap-through'] = false; // default: wrap
      whiteList['writing-mode'] = false; // default: horizontal-tb
      whiteList['z-index'] = false; // default: auto

      return whiteList;
    }

    /**
     * 匹配到白名单上的一个属性时
     *
     * @param {String} name
     * @param {String} value
     * @param {Object} options
     * @return {String}
     */
    function onAttr(name, value, options) {
      // do nothing
    }

    /**
     * 匹配到不在白名单上的一个属性时
     *
     * @param {String} name
     * @param {String} value
     * @param {Object} options
     * @return {String}
     */
    function onIgnoreAttr(name, value, options) {
      // do nothing
    }
    var REGEXP_URL_JAVASCRIPT = /javascript\s*\:/img;

    /**
     * 过滤属性值
     *
     * @param {String} name
     * @param {String} value
     * @return {String}
     */
    function safeAttrValue(name, value) {
      if (REGEXP_URL_JAVASCRIPT.test(value)) return '';
      return value;
    }
    exports.whiteList = getDefaultWhiteList();
    exports.getDefaultWhiteList = getDefaultWhiteList;
    exports.onAttr = onAttr;
    exports.onIgnoreAttr = onIgnoreAttr;
    exports.safeAttrValue = safeAttrValue;
  }, {}],
  8: [function (require, module, exports) {
    /**
     * cssfilter
     *
     * @author 老雷<leizongmin@gmail.com>
     */

    var DEFAULT = require('./default');
    var FilterCSS = require('./css');

    /**
     * XSS过滤
     *
     * @param {String} css 要过滤的CSS代码
     * @param {Object} options 选项：whiteList, onAttr, onIgnoreAttr
     * @return {String}
     */
    function filterCSS(html, options) {
      var xss = new FilterCSS(options);
      return xss.process(html);
    }

    // 输出
    exports = module.exports = filterCSS;
    exports.FilterCSS = FilterCSS;
    for (var i in DEFAULT) exports[i] = DEFAULT[i];

    // 在浏览器端使用
    if (typeof window !== 'undefined') {
      window.filterCSS = module.exports;
    }
  }, {
    "./css": 6,
    "./default": 7
  }],
  9: [function (require, module, exports) {
    /**
     * cssfilter
     *
     * @author 老雷<leizongmin@gmail.com>
     */

    var _ = require('./util');

    /**
     * 解析style
     *
     * @param {String} css
     * @param {Function} onAttr 处理属性的函数
     *   参数格式： function (sourcePosition, position, name, value, source)
     * @return {String}
     */
    function parseStyle(css, onAttr) {
      css = _.trimRight(css);
      if (css[css.length - 1] !== ';') css += ';';
      var cssLength = css.length;
      var isParenthesisOpen = false;
      var lastPos = 0;
      var i = 0;
      var retCSS = '';
      function addNewAttr() {
        // 如果没有正常的闭合圆括号，则直接忽略当前属性
        if (!isParenthesisOpen) {
          var source = _.trim(css.slice(lastPos, i));
          var j = source.indexOf(':');
          if (j !== -1) {
            var name = _.trim(source.slice(0, j));
            var value = _.trim(source.slice(j + 1));
            // 必须有属性名称
            if (name) {
              var ret = onAttr(lastPos, retCSS.length, name, value, source);
              if (ret) retCSS += ret + '; ';
            }
          }
        }
        lastPos = i + 1;
      }
      for (; i < cssLength; i++) {
        var c = css[i];
        if (c === '/' && css[i + 1] === '*') {
          // 备注开始
          var j = css.indexOf('*/', i + 2);
          // 如果没有正常的备注结束，则后面的部分全部跳过
          if (j === -1) break;
          // 直接将当前位置调到备注结尾，并且初始化状态
          i = j + 1;
          lastPos = i + 1;
          isParenthesisOpen = false;
        } else if (c === '(') {
          isParenthesisOpen = true;
        } else if (c === ')') {
          isParenthesisOpen = false;
        } else if (c === ';') {
          if (isParenthesisOpen) {
            // 在圆括号里面，忽略
          } else {
            addNewAttr();
          }
        } else if (c === '\n') {
          addNewAttr();
        }
      }
      return _.trim(retCSS);
    }
    module.exports = parseStyle;
  }, {
    "./util": 10
  }],
  10: [function (require, module, exports) {
    module.exports = {
      indexOf: function indexOf(arr, item) {
        var i, j;
        if (Array.prototype.indexOf) {
          return arr.indexOf(item);
        }
        for (i = 0, j = arr.length; i < j; i++) {
          if (arr[i] === item) {
            return i;
          }
        }
        return -1;
      },
      forEach: function forEach(arr, fn, scope) {
        var i, j;
        if (Array.prototype.forEach) {
          return arr.forEach(fn, scope);
        }
        for (i = 0, j = arr.length; i < j; i++) {
          fn.call(scope, arr[i], i, arr);
        }
      },
      trim: function trim(str) {
        if (String.prototype.trim) {
          return str.trim();
        }
        return str.replace(/(^\s*)|(\s*$)/g, '');
      },
      trimRight: function trimRight(str) {
        if (String.prototype.trimRight) {
          return str.trimRight();
        }
        return str.replace(/(\s*$)/g, '');
      }
    };
  }, {}]
}, {}, [2]);

/***/ }),

/***/ "./node_modules/tom-select/dist/js/tom-select.complete.js":
/*!****************************************************************!*\
  !*** ./node_modules/tom-select/dist/js/tom-select.complete.js ***!
  \****************************************************************/
/***/ (function(module) {

/**
* Tom Select v2.3.1
* Licensed under the Apache License, Version 2.0 (the "License");
*/

(function (global, factory) {
	 true ? module.exports = factory() :
	0;
})(this, (function () { 'use strict';

	/**
	 * MicroEvent - to make any js object an event emitter
	 *
	 * - pure javascript - server compatible, browser compatible
	 * - dont rely on the browser doms
	 * - super simple - you get it immediatly, no mistery, no magic involved
	 *
	 * @author Jerome Etienne (https://github.com/jeromeetienne)
	 */

	/**
	 * Execute callback for each event in space separated list of event names
	 *
	 */
	function forEvents(events, callback) {
	  events.split(/\s+/).forEach(event => {
	    callback(event);
	  });
	}
	class MicroEvent {
	  constructor() {
	    this._events = void 0;
	    this._events = {};
	  }
	  on(events, fct) {
	    forEvents(events, event => {
	      const event_array = this._events[event] || [];
	      event_array.push(fct);
	      this._events[event] = event_array;
	    });
	  }
	  off(events, fct) {
	    var n = arguments.length;
	    if (n === 0) {
	      this._events = {};
	      return;
	    }
	    forEvents(events, event => {
	      if (n === 1) {
	        delete this._events[event];
	        return;
	      }
	      const event_array = this._events[event];
	      if (event_array === undefined) return;
	      event_array.splice(event_array.indexOf(fct), 1);
	      this._events[event] = event_array;
	    });
	  }
	  trigger(events, ...args) {
	    var self = this;
	    forEvents(events, event => {
	      const event_array = self._events[event];
	      if (event_array === undefined) return;
	      event_array.forEach(fct => {
	        fct.apply(self, args);
	      });
	    });
	  }
	}

	/**
	 * microplugin.js
	 * Copyright (c) 2013 Brian Reavis & contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 * @author Brian Reavis <brian@thirdroute.com>
	 */

	function MicroPlugin(Interface) {
	  Interface.plugins = {};
	  return class extends Interface {
	    constructor(...args) {
	      super(...args);
	      this.plugins = {
	        names: [],
	        settings: {},
	        requested: {},
	        loaded: {}
	      };
	    }
	    /**
	     * Registers a plugin.
	     *
	     * @param {function} fn
	     */
	    static define(name, fn) {
	      Interface.plugins[name] = {
	        'name': name,
	        'fn': fn
	      };
	    }

	    /**
	     * Initializes the listed plugins (with options).
	     * Acceptable formats:
	     *
	     * List (without options):
	     *   ['a', 'b', 'c']
	     *
	     * List (with options):
	     *   [{'name': 'a', options: {}}, {'name': 'b', options: {}}]
	     *
	     * Hash (with options):
	     *   {'a': { ... }, 'b': { ... }, 'c': { ... }}
	     *
	     * @param {array|object} plugins
	     */
	    initializePlugins(plugins) {
	      var key, name;
	      const self = this;
	      const queue = [];
	      if (Array.isArray(plugins)) {
	        plugins.forEach(plugin => {
	          if (typeof plugin === 'string') {
	            queue.push(plugin);
	          } else {
	            self.plugins.settings[plugin.name] = plugin.options;
	            queue.push(plugin.name);
	          }
	        });
	      } else if (plugins) {
	        for (key in plugins) {
	          if (plugins.hasOwnProperty(key)) {
	            self.plugins.settings[key] = plugins[key];
	            queue.push(key);
	          }
	        }
	      }
	      while (name = queue.shift()) {
	        self.require(name);
	      }
	    }
	    loadPlugin(name) {
	      var self = this;
	      var plugins = self.plugins;
	      var plugin = Interface.plugins[name];
	      if (!Interface.plugins.hasOwnProperty(name)) {
	        throw new Error('Unable to find "' + name + '" plugin');
	      }
	      plugins.requested[name] = true;
	      plugins.loaded[name] = plugin.fn.apply(self, [self.plugins.settings[name] || {}]);
	      plugins.names.push(name);
	    }

	    /**
	     * Initializes a plugin.
	     *
	     */
	    require(name) {
	      var self = this;
	      var plugins = self.plugins;
	      if (!self.plugins.loaded.hasOwnProperty(name)) {
	        if (plugins.requested[name]) {
	          throw new Error('Plugin has circular dependency ("' + name + '")');
	        }
	        self.loadPlugin(name);
	      }
	      return plugins.loaded[name];
	    }
	  };
	}

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
	/**
	 * Convert array of strings to a regular expression
	 *	ex ['ab','a'] => (?:ab|a)
	 * 	ex ['a','b'] => [ab]
	 * @param {string[]} chars
	 * @return {string}
	 */
	const arrayToPattern = chars => {
	  chars = chars.filter(Boolean);

	  if (chars.length < 2) {
	    return chars[0] || '';
	  }

	  return maxValueLength(chars) == 1 ? '[' + chars.join('') + ']' : '(?:' + chars.join('|') + ')';
	};
	/**
	 * @param {string[]} array
	 * @return {string}
	 */

	const sequencePattern = array => {
	  if (!hasDuplicates(array)) {
	    return array.join('');
	  }

	  let pattern = '';
	  let prev_char_count = 0;

	  const prev_pattern = () => {
	    if (prev_char_count > 1) {
	      pattern += '{' + prev_char_count + '}';
	    }
	  };

	  array.forEach((char, i) => {
	    if (char === array[i - 1]) {
	      prev_char_count++;
	      return;
	    }

	    prev_pattern();
	    pattern += char;
	    prev_char_count = 1;
	  });
	  prev_pattern();
	  return pattern;
	};
	/**
	 * Convert array of strings to a regular expression
	 *	ex ['ab','a'] => (?:ab|a)
	 * 	ex ['a','b'] => [ab]
	 * @param {Set<string>} chars
	 * @return {string}
	 */

	const setToPattern = chars => {
	  let array = toArray(chars);
	  return arrayToPattern(array);
	};
	/**
	 *
	 * https://stackoverflow.com/questions/7376598/in-javascript-how-do-i-check-if-an-array-has-duplicate-values
	 * @param {any[]} array
	 */

	const hasDuplicates = array => {
	  return new Set(array).size !== array.length;
	};
	/**
	 * https://stackoverflow.com/questions/63006601/why-does-u-throw-an-invalid-escape-error
	 * @param {string} str
	 * @return {string}
	 */

	const escape_regex = str => {
	  return (str + '').replace(/([\$\(\)\*\+\.\?\[\]\^\{\|\}\\])/gu, '\\$1');
	};
	/**
	 * Return the max length of array values
	 * @param {string[]} array
	 *
	 */

	const maxValueLength = array => {
	  return array.reduce((longest, value) => Math.max(longest, unicodeLength(value)), 0);
	};
	/**
	 * @param {string} str
	 */

	const unicodeLength = str => {
	  return toArray(str).length;
	};
	/**
	 * @param {any} p
	 * @return {any[]}
	 */

	const toArray = p => Array.from(p);

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
	/**
	 * Get all possible combinations of substrings that add up to the given string
	 * https://stackoverflow.com/questions/30169587/find-all-the-combination-of-substrings-that-add-up-to-the-given-string
	 * @param {string} input
	 * @return {string[][]}
	 */
	const allSubstrings = input => {
	  if (input.length === 1) return [[input]];
	  /** @type {string[][]} */

	  let result = [];
	  const start = input.substring(1);
	  const suba = allSubstrings(start);
	  suba.forEach(function (subresult) {
	    let tmp = subresult.slice(0);
	    tmp[0] = input.charAt(0) + tmp[0];
	    result.push(tmp);
	    tmp = subresult.slice(0);
	    tmp.unshift(input.charAt(0));
	    result.push(tmp);
	  });
	  return result;
	};

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */

	/**
	 * @typedef {{[key:string]:string}} TUnicodeMap
	 * @typedef {{[key:string]:Set<string>}} TUnicodeSets
	 * @typedef {[[number,number]]} TCodePoints
	 * @typedef {{folded:string,composed:string,code_point:number}} TCodePointObj
	 * @typedef {{start:number,end:number,length:number,substr:string}} TSequencePart
	 */
	/** @type {TCodePoints} */

	const code_points = [[0, 65535]];
	const accent_pat = '[\u0300-\u036F\u{b7}\u{2be}\u{2bc}]';
	/** @type {TUnicodeMap} */

	let unicode_map;
	/** @type {RegExp} */

	let multi_char_reg;
	const max_char_length = 3;
	/** @type {TUnicodeMap} */

	const latin_convert = {};
	/** @type {TUnicodeMap} */

	const latin_condensed = {
	  '/': '⁄∕',
	  '0': '߀',
	  "a": "ⱥɐɑ",
	  "aa": "ꜳ",
	  "ae": "æǽǣ",
	  "ao": "ꜵ",
	  "au": "ꜷ",
	  "av": "ꜹꜻ",
	  "ay": "ꜽ",
	  "b": "ƀɓƃ",
	  "c": "ꜿƈȼↄ",
	  "d": "đɗɖᴅƌꮷԁɦ",
	  "e": "ɛǝᴇɇ",
	  "f": "ꝼƒ",
	  "g": "ǥɠꞡᵹꝿɢ",
	  "h": "ħⱨⱶɥ",
	  "i": "ɨı",
	  "j": "ɉȷ",
	  "k": "ƙⱪꝁꝃꝅꞣ",
	  "l": "łƚɫⱡꝉꝇꞁɭ",
	  "m": "ɱɯϻ",
	  "n": "ꞥƞɲꞑᴎлԉ",
	  "o": "øǿɔɵꝋꝍᴑ",
	  "oe": "œ",
	  "oi": "ƣ",
	  "oo": "ꝏ",
	  "ou": "ȣ",
	  "p": "ƥᵽꝑꝓꝕρ",
	  "q": "ꝗꝙɋ",
	  "r": "ɍɽꝛꞧꞃ",
	  "s": "ßȿꞩꞅʂ",
	  "t": "ŧƭʈⱦꞇ",
	  "th": "þ",
	  "tz": "ꜩ",
	  "u": "ʉ",
	  "v": "ʋꝟʌ",
	  "vy": "ꝡ",
	  "w": "ⱳ",
	  "y": "ƴɏỿ",
	  "z": "ƶȥɀⱬꝣ",
	  "hv": "ƕ"
	};

	for (let latin in latin_condensed) {
	  let unicode = latin_condensed[latin] || '';

	  for (let i = 0; i < unicode.length; i++) {
	    let char = unicode.substring(i, i + 1);
	    latin_convert[char] = latin;
	  }
	}

	const convert_pat = new RegExp(Object.keys(latin_convert).join('|') + '|' + accent_pat, 'gu');
	/**
	 * Initialize the unicode_map from the give code point ranges
	 *
	 * @param {TCodePoints=} _code_points
	 */

	const initialize = _code_points => {
	  if (unicode_map !== undefined) return;
	  unicode_map = generateMap(_code_points || code_points);
	};
	/**
	 * Helper method for normalize a string
	 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/normalize
	 * @param {string} str
	 * @param {string} form
	 */

	const normalize = (str, form = 'NFKD') => str.normalize(form);
	/**
	 * Remove accents without reordering string
	 * calling str.normalize('NFKD') on \u{594}\u{595}\u{596} becomes \u{596}\u{594}\u{595}
	 * via https://github.com/krisk/Fuse/issues/133#issuecomment-318692703
	 * @param {string} str
	 * @return {string}
	 */

	const asciifold = str => {
	  return toArray(str).reduce(
	  /**
	   * @param {string} result
	   * @param {string} char
	   */
	  (result, char) => {
	    return result + _asciifold(char);
	  }, '');
	};
	/**
	 * @param {string} str
	 * @return {string}
	 */

	const _asciifold = str => {
	  str = normalize(str).toLowerCase().replace(convert_pat, (
	  /** @type {string} */
	  char) => {
	    return latin_convert[char] || '';
	  }); //return str;

	  return normalize(str, 'NFC');
	};
	/**
	 * Generate a list of unicode variants from the list of code points
	 * @param {TCodePoints} code_points
	 * @yield {TCodePointObj}
	 */

	function* generator(code_points) {
	  for (const [code_point_min, code_point_max] of code_points) {
	    for (let i = code_point_min; i <= code_point_max; i++) {
	      let composed = String.fromCharCode(i);
	      let folded = asciifold(composed);

	      if (folded == composed.toLowerCase()) {
	        continue;
	      } // skip when folded is a string longer than 3 characters long
	      // bc the resulting regex patterns will be long
	      // eg:
	      // folded صلى الله عليه وسلم length 18 code point 65018
	      // folded جل جلاله length 8 code point 65019


	      if (folded.length > max_char_length) {
	        continue;
	      }

	      if (folded.length == 0) {
	        continue;
	      }

	      yield {
	        folded: folded,
	        composed: composed,
	        code_point: i
	      };
	    }
	  }
	}
	/**
	 * Generate a unicode map from the list of code points
	 * @param {TCodePoints} code_points
	 * @return {TUnicodeSets}
	 */

	const generateSets = code_points => {
	  /** @type {{[key:string]:Set<string>}} */
	  const unicode_sets = {};
	  /**
	   * @param {string} folded
	   * @param {string} to_add
	   */

	  const addMatching = (folded, to_add) => {
	    /** @type {Set<string>} */
	    const folded_set = unicode_sets[folded] || new Set();
	    const patt = new RegExp('^' + setToPattern(folded_set) + '$', 'iu');

	    if (to_add.match(patt)) {
	      return;
	    }

	    folded_set.add(escape_regex(to_add));
	    unicode_sets[folded] = folded_set;
	  };

	  for (let value of generator(code_points)) {
	    addMatching(value.folded, value.folded);
	    addMatching(value.folded, value.composed);
	  }

	  return unicode_sets;
	};
	/**
	 * Generate a unicode map from the list of code points
	 * ae => (?:(?:ae|Æ|Ǽ|Ǣ)|(?:A|Ⓐ|Ａ...)(?:E|ɛ|Ⓔ...))
	 *
	 * @param {TCodePoints} code_points
	 * @return {TUnicodeMap}
	 */

	const generateMap = code_points => {
	  /** @type {TUnicodeSets} */
	  const unicode_sets = generateSets(code_points);
	  /** @type {TUnicodeMap} */

	  const unicode_map = {};
	  /** @type {string[]} */

	  let multi_char = [];

	  for (let folded in unicode_sets) {
	    let set = unicode_sets[folded];

	    if (set) {
	      unicode_map[folded] = setToPattern(set);
	    }

	    if (folded.length > 1) {
	      multi_char.push(escape_regex(folded));
	    }
	  }

	  multi_char.sort((a, b) => b.length - a.length);
	  const multi_char_patt = arrayToPattern(multi_char);
	  multi_char_reg = new RegExp('^' + multi_char_patt, 'u');
	  return unicode_map;
	};
	/**
	 * Map each element of an array from it's folded value to all possible unicode matches
	 * @param {string[]} strings
	 * @param {number} min_replacement
	 * @return {string}
	 */

	const mapSequence = (strings, min_replacement = 1) => {
	  let chars_replaced = 0;
	  strings = strings.map(str => {
	    if (unicode_map[str]) {
	      chars_replaced += str.length;
	    }

	    return unicode_map[str] || str;
	  });

	  if (chars_replaced >= min_replacement) {
	    return sequencePattern(strings);
	  }

	  return '';
	};
	/**
	 * Convert a short string and split it into all possible patterns
	 * Keep a pattern only if min_replacement is met
	 *
	 * 'abc'
	 * 		=> [['abc'],['ab','c'],['a','bc'],['a','b','c']]
	 *		=> ['abc-pattern','ab-c-pattern'...]
	 *
	 *
	 * @param {string} str
	 * @param {number} min_replacement
	 * @return {string}
	 */

	const substringsToPattern = (str, min_replacement = 1) => {
	  min_replacement = Math.max(min_replacement, str.length - 1);
	  return arrayToPattern(allSubstrings(str).map(sub_pat => {
	    return mapSequence(sub_pat, min_replacement);
	  }));
	};
	/**
	 * Convert an array of sequences into a pattern
	 * [{start:0,end:3,length:3,substr:'iii'}...] => (?:iii...)
	 *
	 * @param {Sequence[]} sequences
	 * @param {boolean} all
	 */

	const sequencesToPattern = (sequences, all = true) => {
	  let min_replacement = sequences.length > 1 ? 1 : 0;
	  return arrayToPattern(sequences.map(sequence => {
	    let seq = [];
	    const len = all ? sequence.length() : sequence.length() - 1;

	    for (let j = 0; j < len; j++) {
	      seq.push(substringsToPattern(sequence.substrs[j] || '', min_replacement));
	    }

	    return sequencePattern(seq);
	  }));
	};
	/**
	 * Return true if the sequence is already in the sequences
	 * @param {Sequence} needle_seq
	 * @param {Sequence[]} sequences
	 */


	const inSequences = (needle_seq, sequences) => {
	  for (const seq of sequences) {
	    if (seq.start != needle_seq.start || seq.end != needle_seq.end) {
	      continue;
	    }

	    if (seq.substrs.join('') !== needle_seq.substrs.join('')) {
	      continue;
	    }

	    let needle_parts = needle_seq.parts;
	    /**
	     * @param {TSequencePart} part
	     */

	    const filter = part => {
	      for (const needle_part of needle_parts) {
	        if (needle_part.start === part.start && needle_part.substr === part.substr) {
	          return false;
	        }

	        if (part.length == 1 || needle_part.length == 1) {
	          continue;
	        } // check for overlapping parts
	        // a = ['::=','==']
	        // b = ['::','===']
	        // a = ['r','sm']
	        // b = ['rs','m']


	        if (part.start < needle_part.start && part.end > needle_part.start) {
	          return true;
	        }

	        if (needle_part.start < part.start && needle_part.end > part.start) {
	          return true;
	        }
	      }

	      return false;
	    };

	    let filtered = seq.parts.filter(filter);

	    if (filtered.length > 0) {
	      continue;
	    }

	    return true;
	  }

	  return false;
	};

	class Sequence {
	  constructor() {
	    /** @type {TSequencePart[]} */
	    this.parts = [];
	    /** @type {string[]} */

	    this.substrs = [];
	    this.start = 0;
	    this.end = 0;
	  }
	  /**
	   * @param {TSequencePart|undefined} part
	   */


	  add(part) {
	    if (part) {
	      this.parts.push(part);
	      this.substrs.push(part.substr);
	      this.start = Math.min(part.start, this.start);
	      this.end = Math.max(part.end, this.end);
	    }
	  }

	  last() {
	    return this.parts[this.parts.length - 1];
	  }

	  length() {
	    return this.parts.length;
	  }
	  /**
	   * @param {number} position
	   * @param {TSequencePart} last_piece
	   */


	  clone(position, last_piece) {
	    let clone = new Sequence();
	    let parts = JSON.parse(JSON.stringify(this.parts));
	    let last_part = parts.pop();

	    for (const part of parts) {
	      clone.add(part);
	    }

	    let last_substr = last_piece.substr.substring(0, position - last_part.start);
	    let clone_last_len = last_substr.length;
	    clone.add({
	      start: last_part.start,
	      end: last_part.start + clone_last_len,
	      length: clone_last_len,
	      substr: last_substr
	    });
	    return clone;
	  }

	}
	/**
	 * Expand a regular expression pattern to include unicode variants
	 * 	eg /a/ becomes /aⓐａẚàáâầấẫẩãāăằắẵẳȧǡäǟảåǻǎȁȃạậặḁąⱥɐɑAⒶＡÀÁÂẦẤẪẨÃĀĂẰẮẴẲȦǠÄǞẢÅǺǍȀȂẠẬẶḀĄȺⱯ/
	 *
	 * Issue:
	 *  ﺊﺋ [ 'ﺊ = \\u{fe8a}', 'ﺋ = \\u{fe8b}' ]
	 *	becomes:	ئئ [ 'ي = \\u{64a}', 'ٔ = \\u{654}', 'ي = \\u{64a}', 'ٔ = \\u{654}' ]
	 *
	 *	İĲ = IIJ = ⅡJ
	 *
	 * 	1/2/4
	 *
	 * @param {string} str
	 * @return {string|undefined}
	 */


	const getPattern = str => {
	  initialize();
	  str = asciifold(str);
	  let pattern = '';
	  let sequences = [new Sequence()];

	  for (let i = 0; i < str.length; i++) {
	    let substr = str.substring(i);
	    let match = substr.match(multi_char_reg);
	    const char = str.substring(i, i + 1);
	    const match_str = match ? match[0] : null; // loop through sequences
	    // add either the char or multi_match

	    let overlapping = [];
	    let added_types = new Set();

	    for (const sequence of sequences) {
	      const last_piece = sequence.last();

	      if (!last_piece || last_piece.length == 1 || last_piece.end <= i) {
	        // if we have a multi match
	        if (match_str) {
	          const len = match_str.length;
	          sequence.add({
	            start: i,
	            end: i + len,
	            length: len,
	            substr: match_str
	          });
	          added_types.add('1');
	        } else {
	          sequence.add({
	            start: i,
	            end: i + 1,
	            length: 1,
	            substr: char
	          });
	          added_types.add('2');
	        }
	      } else if (match_str) {
	        let clone = sequence.clone(i, last_piece);
	        const len = match_str.length;
	        clone.add({
	          start: i,
	          end: i + len,
	          length: len,
	          substr: match_str
	        });
	        overlapping.push(clone);
	      } else {
	        // don't add char
	        // adding would create invalid patterns: 234 => [2,34,4]
	        added_types.add('3');
	      }
	    } // if we have overlapping


	    if (overlapping.length > 0) {
	      // ['ii','iii'] before ['i','i','iii']
	      overlapping = overlapping.sort((a, b) => {
	        return a.length() - b.length();
	      });

	      for (let clone of overlapping) {
	        // don't add if we already have an equivalent sequence
	        if (inSequences(clone, sequences)) {
	          continue;
	        }

	        sequences.push(clone);
	      }

	      continue;
	    } // if we haven't done anything unique
	    // clean up the patterns
	    // helps keep patterns smaller
	    // if str = 'r₨㎧aarss', pattern will be 446 instead of 655


	    if (i > 0 && added_types.size == 1 && !added_types.has('3')) {
	      pattern += sequencesToPattern(sequences, false);
	      let new_seq = new Sequence();
	      const old_seq = sequences[0];

	      if (old_seq) {
	        new_seq.add(old_seq.last());
	      }

	      sequences = [new_seq];
	    }
	  }

	  pattern += sequencesToPattern(sequences, true);
	  return pattern;
	};

	/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */

	/**
	 * A property getter resolving dot-notation
	 * @param  {Object}  obj     The root object to fetch property on
	 * @param  {String}  name    The optionally dotted property name to fetch
	 * @return {Object}          The resolved property value
	 */
	const getAttr = (obj, name) => {
	  if (!obj) return;
	  return obj[name];
	};
	/**
	 * A property getter resolving dot-notation
	 * @param  {Object}  obj     The root object to fetch property on
	 * @param  {String}  name    The optionally dotted property name to fetch
	 * @return {Object}          The resolved property value
	 */

	const getAttrNesting = (obj, name) => {
	  if (!obj) return;
	  var part,
	      names = name.split(".");

	  while ((part = names.shift()) && (obj = obj[part]));

	  return obj;
	};
	/**
	 * Calculates how close of a match the
	 * given value is against a search token.
	 *
	 */

	const scoreValue = (value, token, weight) => {
	  var score, pos;
	  if (!value) return 0;
	  value = value + '';
	  if (token.regex == null) return 0;
	  pos = value.search(token.regex);
	  if (pos === -1) return 0;
	  score = token.string.length / value.length;
	  if (pos === 0) score += 0.5;
	  return score * weight;
	};
	/**
	 * Cast object property to an array if it exists and has a value
	 *
	 */

	const propToArray = (obj, key) => {
	  var value = obj[key];
	  if (typeof value == 'function') return value;

	  if (value && !Array.isArray(value)) {
	    obj[key] = [value];
	  }
	};
	/**
	 * Iterates over arrays and hashes.
	 *
	 * ```
	 * iterate(this.items, function(item, id) {
	 *    // invoked for each item
	 * });
	 * ```
	 *
	 */

	const iterate$1 = (object, callback) => {
	  if (Array.isArray(object)) {
	    object.forEach(callback);
	  } else {
	    for (var key in object) {
	      if (object.hasOwnProperty(key)) {
	        callback(object[key], key);
	      }
	    }
	  }
	};
	const cmp = (a, b) => {
	  if (typeof a === 'number' && typeof b === 'number') {
	    return a > b ? 1 : a < b ? -1 : 0;
	  }

	  a = asciifold(a + '').toLowerCase();
	  b = asciifold(b + '').toLowerCase();
	  if (a > b) return 1;
	  if (b > a) return -1;
	  return 0;
	};

	/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */

	/**
	 * sifter.js
	 * Copyright (c) 2013–2020 Brian Reavis & contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 * @author Brian Reavis <brian@thirdroute.com>
	 */

	class Sifter {
	  // []|{};

	  /**
	   * Textually searches arrays and hashes of objects
	   * by property (or multiple properties). Designed
	   * specifically for autocomplete.
	   *
	   */
	  constructor(items, settings) {
	    this.items = void 0;
	    this.settings = void 0;
	    this.items = items;
	    this.settings = settings || {
	      diacritics: true
	    };
	  }

	  /**
	   * Splits a search string into an array of individual
	   * regexps to be used to match results.
	   *
	   */
	  tokenize(query, respect_word_boundaries, weights) {
	    if (!query || !query.length) return [];
	    const tokens = [];
	    const words = query.split(/\s+/);
	    var field_regex;

	    if (weights) {
	      field_regex = new RegExp('^(' + Object.keys(weights).map(escape_regex).join('|') + ')\:(.*)$');
	    }

	    words.forEach(word => {
	      let field_match;
	      let field = null;
	      let regex = null; // look for "field:query" tokens

	      if (field_regex && (field_match = word.match(field_regex))) {
	        field = field_match[1];
	        word = field_match[2];
	      }

	      if (word.length > 0) {
	        if (this.settings.diacritics) {
	          regex = getPattern(word) || null;
	        } else {
	          regex = escape_regex(word);
	        }

	        if (regex && respect_word_boundaries) regex = "\\b" + regex;
	      }

	      tokens.push({
	        string: word,
	        regex: regex ? new RegExp(regex, 'iu') : null,
	        field: field
	      });
	    });
	    return tokens;
	  }

	  /**
	   * Returns a function to be used to score individual results.
	   *
	   * Good matches will have a higher score than poor matches.
	   * If an item is not a match, 0 will be returned by the function.
	   *
	   * @returns {T.ScoreFn}
	   */
	  getScoreFunction(query, options) {
	    var search = this.prepareSearch(query, options);
	    return this._getScoreFunction(search);
	  }
	  /**
	   * @returns {T.ScoreFn}
	   *
	   */


	  _getScoreFunction(search) {
	    const tokens = search.tokens,
	          token_count = tokens.length;

	    if (!token_count) {
	      return function () {
	        return 0;
	      };
	    }

	    const fields = search.options.fields,
	          weights = search.weights,
	          field_count = fields.length,
	          getAttrFn = search.getAttrFn;

	    if (!field_count) {
	      return function () {
	        return 1;
	      };
	    }
	    /**
	     * Calculates the score of an object
	     * against the search query.
	     *
	     */


	    const scoreObject = function () {
	      if (field_count === 1) {
	        return function (token, data) {
	          const field = fields[0].field;
	          return scoreValue(getAttrFn(data, field), token, weights[field] || 1);
	        };
	      }

	      return function (token, data) {
	        var sum = 0; // is the token specific to a field?

	        if (token.field) {
	          const value = getAttrFn(data, token.field);

	          if (!token.regex && value) {
	            sum += 1 / field_count;
	          } else {
	            sum += scoreValue(value, token, 1);
	          }
	        } else {
	          iterate$1(weights, (weight, field) => {
	            sum += scoreValue(getAttrFn(data, field), token, weight);
	          });
	        }

	        return sum / field_count;
	      };
	    }();

	    if (token_count === 1) {
	      return function (data) {
	        return scoreObject(tokens[0], data);
	      };
	    }

	    if (search.options.conjunction === 'and') {
	      return function (data) {
	        var score,
	            sum = 0;

	        for (let token of tokens) {
	          score = scoreObject(token, data);
	          if (score <= 0) return 0;
	          sum += score;
	        }

	        return sum / token_count;
	      };
	    } else {
	      return function (data) {
	        var sum = 0;
	        iterate$1(tokens, token => {
	          sum += scoreObject(token, data);
	        });
	        return sum / token_count;
	      };
	    }
	  }

	  /**
	   * Returns a function that can be used to compare two
	   * results, for sorting purposes. If no sorting should
	   * be performed, `null` will be returned.
	   *
	   * @return function(a,b)
	   */
	  getSortFunction(query, options) {
	    var search = this.prepareSearch(query, options);
	    return this._getSortFunction(search);
	  }

	  _getSortFunction(search) {
	    var implicit_score,
	        sort_flds = [];
	    const self = this,
	          options = search.options,
	          sort = !search.query && options.sort_empty ? options.sort_empty : options.sort;

	    if (typeof sort == 'function') {
	      return sort.bind(this);
	    }
	    /**
	     * Fetches the specified sort field value
	     * from a search result item.
	     *
	     */


	    const get_field = function get_field(name, result) {
	      if (name === '$score') return result.score;
	      return search.getAttrFn(self.items[result.id], name);
	    }; // parse options


	    if (sort) {
	      for (let s of sort) {
	        if (search.query || s.field !== '$score') {
	          sort_flds.push(s);
	        }
	      }
	    } // the "$score" field is implied to be the primary
	    // sort field, unless it's manually specified


	    if (search.query) {
	      implicit_score = true;

	      for (let fld of sort_flds) {
	        if (fld.field === '$score') {
	          implicit_score = false;
	          break;
	        }
	      }

	      if (implicit_score) {
	        sort_flds.unshift({
	          field: '$score',
	          direction: 'desc'
	        });
	      } // without a search.query, all items will have the same score

	    } else {
	      sort_flds = sort_flds.filter(fld => fld.field !== '$score');
	    } // build function


	    const sort_flds_count = sort_flds.length;

	    if (!sort_flds_count) {
	      return null;
	    }

	    return function (a, b) {
	      var result, field;

	      for (let sort_fld of sort_flds) {
	        field = sort_fld.field;
	        let multiplier = sort_fld.direction === 'desc' ? -1 : 1;
	        result = multiplier * cmp(get_field(field, a), get_field(field, b));
	        if (result) return result;
	      }

	      return 0;
	    };
	  }

	  /**
	   * Parses a search query and returns an object
	   * with tokens and fields ready to be populated
	   * with results.
	   *
	   */
	  prepareSearch(query, optsUser) {
	    const weights = {};
	    var options = Object.assign({}, optsUser);
	    propToArray(options, 'sort');
	    propToArray(options, 'sort_empty'); // convert fields to new format

	    if (options.fields) {
	      propToArray(options, 'fields');
	      const fields = [];
	      options.fields.forEach(field => {
	        if (typeof field == 'string') {
	          field = {
	            field: field,
	            weight: 1
	          };
	        }

	        fields.push(field);
	        weights[field.field] = 'weight' in field ? field.weight : 1;
	      });
	      options.fields = fields;
	    }

	    return {
	      options: options,
	      query: query.toLowerCase().trim(),
	      tokens: this.tokenize(query, options.respect_word_boundaries, weights),
	      total: 0,
	      items: [],
	      weights: weights,
	      getAttrFn: options.nesting ? getAttrNesting : getAttr
	    };
	  }

	  /**
	   * Searches through all items and returns a sorted array of matches.
	   *
	   */
	  search(query, options) {
	    var self = this,
	        score,
	        search;
	    search = this.prepareSearch(query, options);
	    options = search.options;
	    query = search.query; // generate result scoring function

	    const fn_score = options.score || self._getScoreFunction(search); // perform search and sort


	    if (query.length) {
	      iterate$1(self.items, (item, id) => {
	        score = fn_score(item);

	        if (options.filter === false || score > 0) {
	          search.items.push({
	            'score': score,
	            'id': id
	          });
	        }
	      });
	    } else {
	      iterate$1(self.items, (_, id) => {
	        search.items.push({
	          'score': 1,
	          'id': id
	        });
	      });
	    }

	    const fn_sort = self._getSortFunction(search);

	    if (fn_sort) search.items.sort(fn_sort); // apply limits

	    search.total = search.items.length;

	    if (typeof options.limit === 'number') {
	      search.items = search.items.slice(0, options.limit);
	    }

	    return search;
	  }

	}

	/**
	 * Iterates over arrays and hashes.
	 *
	 * ```
	 * iterate(this.items, function(item, id) {
	 *    // invoked for each item
	 * });
	 * ```
	 *
	 */
	const iterate = (object, callback) => {
	  if (Array.isArray(object)) {
	    object.forEach(callback);
	  } else {
	    for (var key in object) {
	      if (object.hasOwnProperty(key)) {
	        callback(object[key], key);
	      }
	    }
	  }
	};

	/**
	 * Return a dom element from either a dom query string, jQuery object, a dom element or html string
	 * https://stackoverflow.com/questions/494143/creating-a-new-dom-element-from-an-html-string-using-built-in-dom-methods-or-pro/35385518#35385518
	 *
	 * param query should be {}
	 */
	const getDom = query => {
	  if (query.jquery) {
	    return query[0];
	  }
	  if (query instanceof HTMLElement) {
	    return query;
	  }
	  if (isHtmlString(query)) {
	    var tpl = document.createElement('template');
	    tpl.innerHTML = query.trim(); // Never return a text node of whitespace as the result
	    return tpl.content.firstChild;
	  }
	  return document.querySelector(query);
	};
	const isHtmlString = arg => {
	  if (typeof arg === 'string' && arg.indexOf('<') > -1) {
	    return true;
	  }
	  return false;
	};
	const escapeQuery = query => {
	  return query.replace(/['"\\]/g, '\\$&');
	};

	/**
	 * Dispatch an event
	 *
	 */
	const triggerEvent = (dom_el, event_name) => {
	  var event = document.createEvent('HTMLEvents');
	  event.initEvent(event_name, true, false);
	  dom_el.dispatchEvent(event);
	};

	/**
	 * Apply CSS rules to a dom element
	 *
	 */
	const applyCSS = (dom_el, css) => {
	  Object.assign(dom_el.style, css);
	};

	/**
	 * Add css classes
	 *
	 */
	const addClasses = (elmts, ...classes) => {
	  var norm_classes = classesArray(classes);
	  elmts = castAsArray(elmts);
	  elmts.map(el => {
	    norm_classes.map(cls => {
	      el.classList.add(cls);
	    });
	  });
	};

	/**
	 * Remove css classes
	 *
	 */
	const removeClasses = (elmts, ...classes) => {
	  var norm_classes = classesArray(classes);
	  elmts = castAsArray(elmts);
	  elmts.map(el => {
	    norm_classes.map(cls => {
	      el.classList.remove(cls);
	    });
	  });
	};

	/**
	 * Return arguments
	 *
	 */
	const classesArray = args => {
	  var classes = [];
	  iterate(args, _classes => {
	    if (typeof _classes === 'string') {
	      _classes = _classes.trim().split(/[\11\12\14\15\40]/);
	    }
	    if (Array.isArray(_classes)) {
	      classes = classes.concat(_classes);
	    }
	  });
	  return classes.filter(Boolean);
	};

	/**
	 * Create an array from arg if it's not already an array
	 *
	 */
	const castAsArray = arg => {
	  if (!Array.isArray(arg)) {
	    arg = [arg];
	  }
	  return arg;
	};

	/**
	 * Get the closest node to the evt.target matching the selector
	 * Stops at wrapper
	 *
	 */
	const parentMatch = (target, selector, wrapper) => {
	  if (wrapper && !wrapper.contains(target)) {
	    return;
	  }
	  while (target && target.matches) {
	    if (target.matches(selector)) {
	      return target;
	    }
	    target = target.parentNode;
	  }
	};

	/**
	 * Get the first or last item from an array
	 *
	 * > 0 - right (last)
	 * <= 0 - left (first)
	 *
	 */
	const getTail = (list, direction = 0) => {
	  if (direction > 0) {
	    return list[list.length - 1];
	  }
	  return list[0];
	};

	/**
	 * Return true if an object is empty
	 *
	 */
	const isEmptyObject = obj => {
	  return Object.keys(obj).length === 0;
	};

	/**
	 * Get the index of an element amongst sibling nodes of the same type
	 *
	 */
	const nodeIndex = (el, amongst) => {
	  if (!el) return -1;
	  amongst = amongst || el.nodeName;
	  var i = 0;
	  while (el = el.previousElementSibling) {
	    if (el.matches(amongst)) {
	      i++;
	    }
	  }
	  return i;
	};

	/**
	 * Set attributes of an element
	 *
	 */
	const setAttr = (el, attrs) => {
	  iterate(attrs, (val, attr) => {
	    if (val == null) {
	      el.removeAttribute(attr);
	    } else {
	      el.setAttribute(attr, '' + val);
	    }
	  });
	};

	/**
	 * Replace a node
	 */
	const replaceNode = (existing, replacement) => {
	  if (existing.parentNode) existing.parentNode.replaceChild(replacement, existing);
	};

	/**
	 * highlight v3 | MIT license | Johann Burkard <jb@eaio.com>
	 * Highlights arbitrary terms in a node.
	 *
	 * - Modified by Marshal <beatgates@gmail.com> 2011-6-24 (added regex)
	 * - Modified by Brian Reavis <brian@thirdroute.com> 2012-8-27 (cleanup)
	 */

	const highlight = (element, regex) => {
	  if (regex === null) return;

	  // convet string to regex
	  if (typeof regex === 'string') {
	    if (!regex.length) return;
	    regex = new RegExp(regex, 'i');
	  }

	  // Wrap matching part of text node with highlighting <span>, e.g.
	  // Soccer  ->  <span class="highlight">Soc</span>cer  for regex = /soc/i
	  const highlightText = node => {
	    var match = node.data.match(regex);
	    if (match && node.data.length > 0) {
	      var spannode = document.createElement('span');
	      spannode.className = 'highlight';
	      var middlebit = node.splitText(match.index);
	      middlebit.splitText(match[0].length);
	      var middleclone = middlebit.cloneNode(true);
	      spannode.appendChild(middleclone);
	      replaceNode(middlebit, spannode);
	      return 1;
	    }
	    return 0;
	  };

	  // Recurse element node, looking for child text nodes to highlight, unless element
	  // is childless, <script>, <style>, or already highlighted: <span class="hightlight">
	  const highlightChildren = node => {
	    if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName) && (node.className !== 'highlight' || node.tagName !== 'SPAN')) {
	      Array.from(node.childNodes).forEach(element => {
	        highlightRecursive(element);
	      });
	    }
	  };
	  const highlightRecursive = node => {
	    if (node.nodeType === 3) {
	      return highlightText(node);
	    }
	    highlightChildren(node);
	    return 0;
	  };
	  highlightRecursive(element);
	};

	/**
	 * removeHighlight fn copied from highlight v5 and
	 * edited to remove with(), pass js strict mode, and use without jquery
	 */
	const removeHighlight = el => {
	  var elements = el.querySelectorAll("span.highlight");
	  Array.prototype.forEach.call(elements, function (el) {
	    var parent = el.parentNode;
	    parent.replaceChild(el.firstChild, el);
	    parent.normalize();
	  });
	};

	const KEY_A = 65;
	const KEY_RETURN = 13;
	const KEY_ESC = 27;
	const KEY_LEFT = 37;
	const KEY_UP = 38;
	const KEY_RIGHT = 39;
	const KEY_DOWN = 40;
	const KEY_BACKSPACE = 8;
	const KEY_DELETE = 46;
	const KEY_TAB = 9;
	const IS_MAC = typeof navigator === 'undefined' ? false : /Mac/.test(navigator.userAgent);
	const KEY_SHORTCUT = IS_MAC ? 'metaKey' : 'ctrlKey'; // ctrl key or apple key for ma

	var defaults = {
	  options: [],
	  optgroups: [],
	  plugins: [],
	  delimiter: ',',
	  splitOn: null,
	  // regexp or string for splitting up values from a paste command
	  persist: true,
	  diacritics: true,
	  create: null,
	  createOnBlur: false,
	  createFilter: null,
	  highlight: true,
	  openOnFocus: true,
	  shouldOpen: null,
	  maxOptions: 50,
	  maxItems: null,
	  hideSelected: null,
	  duplicates: false,
	  addPrecedence: false,
	  selectOnTab: false,
	  preload: null,
	  allowEmptyOption: false,
	  //closeAfterSelect: false,
	  refreshThrottle: 300,
	  loadThrottle: 300,
	  loadingClass: 'loading',
	  dataAttr: null,
	  //'data-data',
	  optgroupField: 'optgroup',
	  valueField: 'value',
	  labelField: 'text',
	  disabledField: 'disabled',
	  optgroupLabelField: 'label',
	  optgroupValueField: 'value',
	  lockOptgroupOrder: false,
	  sortField: '$order',
	  searchField: ['text'],
	  searchConjunction: 'and',
	  mode: null,
	  wrapperClass: 'ts-wrapper',
	  controlClass: 'ts-control',
	  dropdownClass: 'ts-dropdown',
	  dropdownContentClass: 'ts-dropdown-content',
	  itemClass: 'item',
	  optionClass: 'option',
	  dropdownParent: null,
	  controlInput: '<input type="text" autocomplete="off" size="1" />',
	  copyClassesToDropdown: false,
	  placeholder: null,
	  hidePlaceholder: null,
	  shouldLoad: function (query) {
	    return query.length > 0;
	  },
	  /*
	  load                 : null, // function(query, callback) { ... }
	  score                : null, // function(search) { ... }
	  onInitialize         : null, // function() { ... }
	  onChange             : null, // function(value) { ... }
	  onItemAdd            : null, // function(value, $item) { ... }
	  onItemRemove         : null, // function(value) { ... }
	  onClear              : null, // function() { ... }
	  onOptionAdd          : null, // function(value, data) { ... }
	  onOptionRemove       : null, // function(value) { ... }
	  onOptionClear        : null, // function() { ... }
	  onOptionGroupAdd     : null, // function(id, data) { ... }
	  onOptionGroupRemove  : null, // function(id) { ... }
	  onOptionGroupClear   : null, // function() { ... }
	  onDropdownOpen       : null, // function(dropdown) { ... }
	  onDropdownClose      : null, // function(dropdown) { ... }
	  onType               : null, // function(str) { ... }
	  onDelete             : null, // function(values) { ... }
	  */

	  render: {
	    /*
	    item: null,
	    optgroup: null,
	    optgroup_header: null,
	    option: null,
	    option_create: null
	    */
	  }
	};

	/**
	 * Converts a scalar to its best string representation
	 * for hash keys and HTML attribute values.
	 *
	 * Transformations:
	 *   'str'     -> 'str'
	 *   null      -> ''
	 *   undefined -> ''
	 *   true      -> '1'
	 *   false     -> '0'
	 *   0         -> '0'
	 *   1         -> '1'
	 *
	 */
	const hash_key = value => {
	  if (typeof value === 'undefined' || value === null) return null;
	  return get_hash(value);
	};
	const get_hash = value => {
	  if (typeof value === 'boolean') return value ? '1' : '0';
	  return value + '';
	};

	/**
	 * Escapes a string for use within HTML.
	 *
	 */
	const escape_html = str => {
	  return (str + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	};

	/**
	 * use setTimeout if timeout > 0 
	 */
	const timeout = (fn, timeout) => {
	  if (timeout > 0) {
	    return setTimeout(fn, timeout);
	  }
	  fn.call(null);
	  return null;
	};

	/**
	 * Debounce the user provided load function
	 *
	 */
	const loadDebounce = (fn, delay) => {
	  var timeout;
	  return function (value, callback) {
	    var self = this;
	    if (timeout) {
	      self.loading = Math.max(self.loading - 1, 0);
	      clearTimeout(timeout);
	    }
	    timeout = setTimeout(function () {
	      timeout = null;
	      self.loadedSearches[value] = true;
	      fn.call(self, value, callback);
	    }, delay);
	  };
	};

	/**
	 * Debounce all fired events types listed in `types`
	 * while executing the provided `fn`.
	 *
	 */
	const debounce_events = (self, types, fn) => {
	  var type;
	  var trigger = self.trigger;
	  var event_args = {};

	  // override trigger method
	  self.trigger = function () {
	    var type = arguments[0];
	    if (types.indexOf(type) !== -1) {
	      event_args[type] = arguments;
	    } else {
	      return trigger.apply(self, arguments);
	    }
	  };

	  // invoke provided function
	  fn.apply(self, []);
	  self.trigger = trigger;

	  // trigger queued events
	  for (type of types) {
	    if (type in event_args) {
	      trigger.apply(self, event_args[type]);
	    }
	  }
	};

	/**
	 * Determines the current selection within a text input control.
	 * Returns an object containing:
	 *   - start
	 *   - length
	 *
	 * Note: "selectionStart, selectionEnd ... apply only to inputs of types text, search, URL, tel and password"
	 * 	- https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/setSelectionRange
	 */
	const getSelection = input => {
	  return {
	    start: input.selectionStart || 0,
	    length: (input.selectionEnd || 0) - (input.selectionStart || 0)
	  };
	};

	/**
	 * Prevent default
	 *
	 */
	const preventDefault = (evt, stop = false) => {
	  if (evt) {
	    evt.preventDefault();
	    if (stop) {
	      evt.stopPropagation();
	    }
	  }
	};

	/**
	 * Add event helper
	 *
	 */
	const addEvent = (target, type, callback, options) => {
	  target.addEventListener(type, callback, options);
	};

	/**
	 * Return true if the requested key is down
	 * Will return false if more than one control character is pressed ( when [ctrl+shift+a] != [ctrl+a] )
	 * The current evt may not always set ( eg calling advanceSelection() )
	 *
	 */
	const isKeyDown = (key_name, evt) => {
	  if (!evt) {
	    return false;
	  }
	  if (!evt[key_name]) {
	    return false;
	  }
	  var count = (evt.altKey ? 1 : 0) + (evt.ctrlKey ? 1 : 0) + (evt.shiftKey ? 1 : 0) + (evt.metaKey ? 1 : 0);
	  if (count === 1) {
	    return true;
	  }
	  return false;
	};

	/**
	 * Get the id of an element
	 * If the id attribute is not set, set the attribute with the given id
	 *
	 */
	const getId = (el, id) => {
	  const existing_id = el.getAttribute('id');
	  if (existing_id) {
	    return existing_id;
	  }
	  el.setAttribute('id', id);
	  return id;
	};

	/**
	 * Returns a string with backslashes added before characters that need to be escaped.
	 */
	const addSlashes = str => {
	  return str.replace(/[\\"']/g, '\\$&');
	};

	/**
	 *
	 */
	const append = (parent, node) => {
	  if (node) parent.append(node);
	};

	function getSettings(input, settings_user) {
	  var settings = Object.assign({}, defaults, settings_user);
	  var attr_data = settings.dataAttr;
	  var field_label = settings.labelField;
	  var field_value = settings.valueField;
	  var field_disabled = settings.disabledField;
	  var field_optgroup = settings.optgroupField;
	  var field_optgroup_label = settings.optgroupLabelField;
	  var field_optgroup_value = settings.optgroupValueField;
	  var tag_name = input.tagName.toLowerCase();
	  var placeholder = input.getAttribute('placeholder') || input.getAttribute('data-placeholder');
	  if (!placeholder && !settings.allowEmptyOption) {
	    let option = input.querySelector('option[value=""]');
	    if (option) {
	      placeholder = option.textContent;
	    }
	  }
	  var settings_element = {
	    placeholder: placeholder,
	    options: [],
	    optgroups: [],
	    items: [],
	    maxItems: null
	  };

	  /**
	   * Initialize from a <select> element.
	   *
	   */
	  var init_select = () => {
	    var tagName;
	    var options = settings_element.options;
	    var optionsMap = {};
	    var group_count = 1;
	    let $order = 0;
	    var readData = el => {
	      var data = Object.assign({}, el.dataset); // get plain object from DOMStringMap
	      var json = attr_data && data[attr_data];
	      if (typeof json === 'string' && json.length) {
	        data = Object.assign(data, JSON.parse(json));
	      }
	      return data;
	    };
	    var addOption = (option, group) => {
	      var value = hash_key(option.value);
	      if (value == null) return;
	      if (!value && !settings.allowEmptyOption) return;

	      // if the option already exists, it's probably been
	      // duplicated in another optgroup. in this case, push
	      // the current group to the "optgroup" property on the
	      // existing option so that it's rendered in both places.
	      if (optionsMap.hasOwnProperty(value)) {
	        if (group) {
	          var arr = optionsMap[value][field_optgroup];
	          if (!arr) {
	            optionsMap[value][field_optgroup] = group;
	          } else if (!Array.isArray(arr)) {
	            optionsMap[value][field_optgroup] = [arr, group];
	          } else {
	            arr.push(group);
	          }
	        }
	      } else {
	        var option_data = readData(option);
	        option_data[field_label] = option_data[field_label] || option.textContent;
	        option_data[field_value] = option_data[field_value] || value;
	        option_data[field_disabled] = option_data[field_disabled] || option.disabled;
	        option_data[field_optgroup] = option_data[field_optgroup] || group;
	        option_data.$option = option;
	        option_data.$order = option_data.$order || ++$order;
	        optionsMap[value] = option_data;
	        options.push(option_data);
	      }
	      if (option.selected) {
	        settings_element.items.push(value);
	      }
	    };
	    var addGroup = optgroup => {
	      var id, optgroup_data;
	      optgroup_data = readData(optgroup);
	      optgroup_data[field_optgroup_label] = optgroup_data[field_optgroup_label] || optgroup.getAttribute('label') || '';
	      optgroup_data[field_optgroup_value] = optgroup_data[field_optgroup_value] || group_count++;
	      optgroup_data[field_disabled] = optgroup_data[field_disabled] || optgroup.disabled;
	      optgroup_data.$order = optgroup_data.$order || ++$order;
	      settings_element.optgroups.push(optgroup_data);
	      id = optgroup_data[field_optgroup_value];
	      iterate(optgroup.children, option => {
	        addOption(option, id);
	      });
	    };
	    settings_element.maxItems = input.hasAttribute('multiple') ? null : 1;
	    iterate(input.children, child => {
	      tagName = child.tagName.toLowerCase();
	      if (tagName === 'optgroup') {
	        addGroup(child);
	      } else if (tagName === 'option') {
	        addOption(child);
	      }
	    });
	  };

	  /**
	   * Initialize from a <input type="text"> element.
	   *
	   */
	  var init_textbox = () => {
	    const data_raw = input.getAttribute(attr_data);
	    if (!data_raw) {
	      var value = input.value.trim() || '';
	      if (!settings.allowEmptyOption && !value.length) return;
	      const values = value.split(settings.delimiter);
	      iterate(values, value => {
	        const option = {};
	        option[field_label] = value;
	        option[field_value] = value;
	        settings_element.options.push(option);
	      });
	      settings_element.items = values;
	    } else {
	      settings_element.options = JSON.parse(data_raw);
	      iterate(settings_element.options, opt => {
	        settings_element.items.push(opt[field_value]);
	      });
	    }
	  };
	  if (tag_name === 'select') {
	    init_select();
	  } else {
	    init_textbox();
	  }
	  return Object.assign({}, defaults, settings_element, settings_user);
	}

	var instance_i = 0;
	class TomSelect extends MicroPlugin(MicroEvent) {
	  constructor(input_arg, user_settings) {
	    super();
	    this.control_input = void 0;
	    this.wrapper = void 0;
	    this.dropdown = void 0;
	    this.control = void 0;
	    this.dropdown_content = void 0;
	    this.focus_node = void 0;
	    this.order = 0;
	    this.settings = void 0;
	    this.input = void 0;
	    this.tabIndex = void 0;
	    this.is_select_tag = void 0;
	    this.rtl = void 0;
	    this.inputId = void 0;
	    this._destroy = void 0;
	    this.sifter = void 0;
	    this.isOpen = false;
	    this.isDisabled = false;
	    this.isReadOnly = false;
	    this.isRequired = void 0;
	    this.isInvalid = false;
	    // @deprecated 1.8
	    this.isValid = true;
	    this.isLocked = false;
	    this.isFocused = false;
	    this.isInputHidden = false;
	    this.isSetup = false;
	    this.ignoreFocus = false;
	    this.ignoreHover = false;
	    this.hasOptions = false;
	    this.currentResults = void 0;
	    this.lastValue = '';
	    this.caretPos = 0;
	    this.loading = 0;
	    this.loadedSearches = {};
	    this.activeOption = null;
	    this.activeItems = [];
	    this.optgroups = {};
	    this.options = {};
	    this.userOptions = {};
	    this.items = [];
	    this.refreshTimeout = null;
	    instance_i++;
	    var dir;
	    var input = getDom(input_arg);
	    if (input.tomselect) {
	      throw new Error('Tom Select already initialized on this element');
	    }
	    input.tomselect = this;

	    // detect rtl environment
	    var computedStyle = window.getComputedStyle && window.getComputedStyle(input, null);
	    dir = computedStyle.getPropertyValue('direction');

	    // setup default state
	    const settings = getSettings(input, user_settings);
	    this.settings = settings;
	    this.input = input;
	    this.tabIndex = input.tabIndex || 0;
	    this.is_select_tag = input.tagName.toLowerCase() === 'select';
	    this.rtl = /rtl/i.test(dir);
	    this.inputId = getId(input, 'tomselect-' + instance_i);
	    this.isRequired = input.required;

	    // search system
	    this.sifter = new Sifter(this.options, {
	      diacritics: settings.diacritics
	    });

	    // option-dependent defaults
	    settings.mode = settings.mode || (settings.maxItems === 1 ? 'single' : 'multi');
	    if (typeof settings.hideSelected !== 'boolean') {
	      settings.hideSelected = settings.mode === 'multi';
	    }
	    if (typeof settings.hidePlaceholder !== 'boolean') {
	      settings.hidePlaceholder = settings.mode !== 'multi';
	    }

	    // set up createFilter callback
	    var filter = settings.createFilter;
	    if (typeof filter !== 'function') {
	      if (typeof filter === 'string') {
	        filter = new RegExp(filter);
	      }
	      if (filter instanceof RegExp) {
	        settings.createFilter = input => filter.test(input);
	      } else {
	        settings.createFilter = value => {
	          return this.settings.duplicates || !this.options[value];
	        };
	      }
	    }
	    this.initializePlugins(settings.plugins);
	    this.setupCallbacks();
	    this.setupTemplates();

	    // Create all elements
	    const wrapper = getDom('<div>');
	    const control = getDom('<div>');
	    const dropdown = this._render('dropdown');
	    const dropdown_content = getDom(`<div role="listbox" tabindex="-1">`);
	    const classes = this.input.getAttribute('class') || '';
	    const inputMode = settings.mode;
	    var control_input;
	    addClasses(wrapper, settings.wrapperClass, classes, inputMode);
	    addClasses(control, settings.controlClass);
	    append(wrapper, control);
	    addClasses(dropdown, settings.dropdownClass, inputMode);
	    if (settings.copyClassesToDropdown) {
	      addClasses(dropdown, classes);
	    }
	    addClasses(dropdown_content, settings.dropdownContentClass);
	    append(dropdown, dropdown_content);
	    getDom(settings.dropdownParent || wrapper).appendChild(dropdown);

	    // default controlInput
	    if (isHtmlString(settings.controlInput)) {
	      control_input = getDom(settings.controlInput);

	      // set attributes
	      var attrs = ['autocorrect', 'autocapitalize', 'autocomplete', 'spellcheck'];
	      iterate$1(attrs, attr => {
	        if (input.getAttribute(attr)) {
	          setAttr(control_input, {
	            [attr]: input.getAttribute(attr)
	          });
	        }
	      });
	      control_input.tabIndex = -1;
	      control.appendChild(control_input);
	      this.focus_node = control_input;

	      // dom element
	    } else if (settings.controlInput) {
	      control_input = getDom(settings.controlInput);
	      this.focus_node = control_input;
	    } else {
	      control_input = getDom('<input/>');
	      this.focus_node = control;
	    }
	    this.wrapper = wrapper;
	    this.dropdown = dropdown;
	    this.dropdown_content = dropdown_content;
	    this.control = control;
	    this.control_input = control_input;
	    this.setup();
	  }

	  /**
	   * set up event bindings.
	   *
	   */
	  setup() {
	    const self = this;
	    const settings = self.settings;
	    const control_input = self.control_input;
	    const dropdown = self.dropdown;
	    const dropdown_content = self.dropdown_content;
	    const wrapper = self.wrapper;
	    const control = self.control;
	    const input = self.input;
	    const focus_node = self.focus_node;
	    const passive_event = {
	      passive: true
	    };
	    const listboxId = self.inputId + '-ts-dropdown';
	    setAttr(dropdown_content, {
	      id: listboxId
	    });
	    setAttr(focus_node, {
	      role: 'combobox',
	      'aria-haspopup': 'listbox',
	      'aria-expanded': 'false',
	      'aria-controls': listboxId
	    });
	    const control_id = getId(focus_node, self.inputId + '-ts-control');
	    const query = "label[for='" + escapeQuery(self.inputId) + "']";
	    const label = document.querySelector(query);
	    const label_click = self.focus.bind(self);
	    if (label) {
	      addEvent(label, 'click', label_click);
	      setAttr(label, {
	        for: control_id
	      });
	      const label_id = getId(label, self.inputId + '-ts-label');
	      setAttr(focus_node, {
	        'aria-labelledby': label_id
	      });
	      setAttr(dropdown_content, {
	        'aria-labelledby': label_id
	      });
	    }
	    wrapper.style.width = input.style.width;
	    if (self.plugins.names.length) {
	      const classes_plugins = 'plugin-' + self.plugins.names.join(' plugin-');
	      addClasses([wrapper, dropdown], classes_plugins);
	    }
	    if ((settings.maxItems === null || settings.maxItems > 1) && self.is_select_tag) {
	      setAttr(input, {
	        multiple: 'multiple'
	      });
	    }
	    if (settings.placeholder) {
	      setAttr(control_input, {
	        placeholder: settings.placeholder
	      });
	    }

	    // if splitOn was not passed in, construct it from the delimiter to allow pasting universally
	    if (!settings.splitOn && settings.delimiter) {
	      settings.splitOn = new RegExp('\\s*' + escape_regex(settings.delimiter) + '+\\s*');
	    }

	    // debounce user defined load() if loadThrottle > 0
	    // after initializePlugins() so plugins can create/modify user defined loaders
	    if (settings.load && settings.loadThrottle) {
	      settings.load = loadDebounce(settings.load, settings.loadThrottle);
	    }
	    addEvent(dropdown, 'mousemove', () => {
	      self.ignoreHover = false;
	    });
	    addEvent(dropdown, 'mouseenter', e => {
	      var target_match = parentMatch(e.target, '[data-selectable]', dropdown);
	      if (target_match) self.onOptionHover(e, target_match);
	    }, {
	      capture: true
	    });

	    // clicking on an option should select it
	    addEvent(dropdown, 'click', evt => {
	      const option = parentMatch(evt.target, '[data-selectable]');
	      if (option) {
	        self.onOptionSelect(evt, option);
	        preventDefault(evt, true);
	      }
	    });
	    addEvent(control, 'click', evt => {
	      var target_match = parentMatch(evt.target, '[data-ts-item]', control);
	      if (target_match && self.onItemSelect(evt, target_match)) {
	        preventDefault(evt, true);
	        return;
	      }

	      // retain focus (see control_input mousedown)
	      if (control_input.value != '') {
	        return;
	      }
	      self.onClick();
	      preventDefault(evt, true);
	    });

	    // keydown on focus_node for arrow_down/arrow_up
	    addEvent(focus_node, 'keydown', e => self.onKeyDown(e));

	    // keypress and input/keyup
	    addEvent(control_input, 'keypress', e => self.onKeyPress(e));
	    addEvent(control_input, 'input', e => self.onInput(e));
	    addEvent(focus_node, 'blur', e => self.onBlur(e));
	    addEvent(focus_node, 'focus', e => self.onFocus(e));
	    addEvent(control_input, 'paste', e => self.onPaste(e));
	    const doc_mousedown = evt => {
	      // blur if target is outside of this instance
	      // dropdown is not always inside wrapper
	      const target = evt.composedPath()[0];
	      if (!wrapper.contains(target) && !dropdown.contains(target)) {
	        if (self.isFocused) {
	          self.blur();
	        }
	        self.inputState();
	        return;
	      }

	      // retain focus by preventing native handling. if the
	      // event target is the input it should not be modified.
	      // otherwise, text selection within the input won't work.
	      // Fixes bug #212 which is no covered by tests
	      if (target == control_input && self.isOpen) {
	        evt.stopPropagation();

	        // clicking anywhere in the control should not blur the control_input (which would close the dropdown)
	      } else {
	        preventDefault(evt, true);
	      }
	    };
	    const win_scroll = () => {
	      if (self.isOpen) {
	        self.positionDropdown();
	      }
	    };
	    addEvent(document, 'mousedown', doc_mousedown);
	    addEvent(window, 'scroll', win_scroll, passive_event);
	    addEvent(window, 'resize', win_scroll, passive_event);
	    this._destroy = () => {
	      document.removeEventListener('mousedown', doc_mousedown);
	      window.removeEventListener('scroll', win_scroll);
	      window.removeEventListener('resize', win_scroll);
	      if (label) label.removeEventListener('click', label_click);
	    };

	    // store original html and tab index so that they can be
	    // restored when the destroy() method is called.
	    this.revertSettings = {
	      innerHTML: input.innerHTML,
	      tabIndex: input.tabIndex
	    };
	    input.tabIndex = -1;
	    input.insertAdjacentElement('afterend', self.wrapper);
	    self.sync(false);
	    settings.items = [];
	    delete settings.optgroups;
	    delete settings.options;
	    addEvent(input, 'invalid', () => {
	      if (self.isValid) {
	        self.isValid = false;
	        self.isInvalid = true;
	        self.refreshState();
	      }
	    });
	    self.updateOriginalInput();
	    self.refreshItems();
	    self.close(false);
	    self.inputState();
	    self.isSetup = true;
	    if (input.disabled) {
	      self.disable();
	    } else if (input.readOnly) {
	      self.setReadOnly(true);
	    } else {
	      self.enable(); //sets tabIndex
	    }

	    self.on('change', this.onChange);
	    addClasses(input, 'tomselected', 'ts-hidden-accessible');
	    self.trigger('initialize');

	    // preload options
	    if (settings.preload === true) {
	      self.preload();
	    }
	  }

	  /**
	   * Register options and optgroups
	   *
	   */
	  setupOptions(options = [], optgroups = []) {
	    // build options table
	    this.addOptions(options);

	    // build optgroup table
	    iterate$1(optgroups, optgroup => {
	      this.registerOptionGroup(optgroup);
	    });
	  }

	  /**
	   * Sets up default rendering functions.
	   */
	  setupTemplates() {
	    var self = this;
	    var field_label = self.settings.labelField;
	    var field_optgroup = self.settings.optgroupLabelField;
	    var templates = {
	      'optgroup': data => {
	        let optgroup = document.createElement('div');
	        optgroup.className = 'optgroup';
	        optgroup.appendChild(data.options);
	        return optgroup;
	      },
	      'optgroup_header': (data, escape) => {
	        return '<div class="optgroup-header">' + escape(data[field_optgroup]) + '</div>';
	      },
	      'option': (data, escape) => {
	        return '<div>' + escape(data[field_label]) + '</div>';
	      },
	      'item': (data, escape) => {
	        return '<div>' + escape(data[field_label]) + '</div>';
	      },
	      'option_create': (data, escape) => {
	        return '<div class="create">Add <strong>' + escape(data.input) + '</strong>&hellip;</div>';
	      },
	      'no_results': () => {
	        return '<div class="no-results">No results found</div>';
	      },
	      'loading': () => {
	        return '<div class="spinner"></div>';
	      },
	      'not_loading': () => {},
	      'dropdown': () => {
	        return '<div></div>';
	      }
	    };
	    self.settings.render = Object.assign({}, templates, self.settings.render);
	  }

	  /**
	   * Maps fired events to callbacks provided
	   * in the settings used when creating the control.
	   */
	  setupCallbacks() {
	    var key, fn;
	    var callbacks = {
	      'initialize': 'onInitialize',
	      'change': 'onChange',
	      'item_add': 'onItemAdd',
	      'item_remove': 'onItemRemove',
	      'item_select': 'onItemSelect',
	      'clear': 'onClear',
	      'option_add': 'onOptionAdd',
	      'option_remove': 'onOptionRemove',
	      'option_clear': 'onOptionClear',
	      'optgroup_add': 'onOptionGroupAdd',
	      'optgroup_remove': 'onOptionGroupRemove',
	      'optgroup_clear': 'onOptionGroupClear',
	      'dropdown_open': 'onDropdownOpen',
	      'dropdown_close': 'onDropdownClose',
	      'type': 'onType',
	      'load': 'onLoad',
	      'focus': 'onFocus',
	      'blur': 'onBlur'
	    };
	    for (key in callbacks) {
	      fn = this.settings[callbacks[key]];
	      if (fn) this.on(key, fn);
	    }
	  }

	  /**
	   * Sync the Tom Select instance with the original input or select
	   *
	   */
	  sync(get_settings = true) {
	    const self = this;
	    const settings = get_settings ? getSettings(self.input, {
	      delimiter: self.settings.delimiter
	    }) : self.settings;
	    self.setupOptions(settings.options, settings.optgroups);
	    self.setValue(settings.items || [], true); // silent prevents recursion

	    self.lastQuery = null; // so updated options will be displayed in dropdown
	  }

	  /**
	   * Triggered when the main control element
	   * has a click event.
	   *
	   */
	  onClick() {
	    var self = this;
	    if (self.activeItems.length > 0) {
	      self.clearActiveItems();
	      self.focus();
	      return;
	    }
	    if (self.isFocused && self.isOpen) {
	      self.blur();
	    } else {
	      self.focus();
	    }
	  }

	  /**
	   * @deprecated v1.7
	   *
	   */
	  onMouseDown() {}

	  /**
	   * Triggered when the value of the control has been changed.
	   * This should propagate the event to the original DOM
	   * input / select element.
	   */
	  onChange() {
	    triggerEvent(this.input, 'input');
	    triggerEvent(this.input, 'change');
	  }

	  /**
	   * Triggered on <input> paste.
	   *
	   */
	  onPaste(e) {
	    var self = this;
	    if (self.isInputHidden || self.isLocked) {
	      preventDefault(e);
	      return;
	    }

	    // If a regex or string is included, this will split the pasted
	    // input and create Items for each separate value
	    if (!self.settings.splitOn) {
	      return;
	    }

	    // Wait for pasted text to be recognized in value
	    setTimeout(() => {
	      var pastedText = self.inputValue();
	      if (!pastedText.match(self.settings.splitOn)) {
	        return;
	      }
	      var splitInput = pastedText.trim().split(self.settings.splitOn);
	      iterate$1(splitInput, piece => {
	        const hash = hash_key(piece);
	        if (hash) {
	          if (this.options[piece]) {
	            self.addItem(piece);
	          } else {
	            self.createItem(piece);
	          }
	        }
	      });
	    }, 0);
	  }

	  /**
	   * Triggered on <input> keypress.
	   *
	   */
	  onKeyPress(e) {
	    var self = this;
	    if (self.isLocked) {
	      preventDefault(e);
	      return;
	    }
	    var character = String.fromCharCode(e.keyCode || e.which);
	    if (self.settings.create && self.settings.mode === 'multi' && character === self.settings.delimiter) {
	      self.createItem();
	      preventDefault(e);
	      return;
	    }
	  }

	  /**
	   * Triggered on <input> keydown.
	   *
	   */
	  onKeyDown(e) {
	    var self = this;
	    self.ignoreHover = true;
	    if (self.isLocked) {
	      if (e.keyCode !== KEY_TAB) {
	        preventDefault(e);
	      }
	      return;
	    }
	    switch (e.keyCode) {
	      // ctrl+A: select all
	      case KEY_A:
	        if (isKeyDown(KEY_SHORTCUT, e)) {
	          if (self.control_input.value == '') {
	            preventDefault(e);
	            self.selectAll();
	            return;
	          }
	        }
	        break;

	      // esc: close dropdown
	      case KEY_ESC:
	        if (self.isOpen) {
	          preventDefault(e, true);
	          self.close();
	        }
	        self.clearActiveItems();
	        return;

	      // down: open dropdown or move selection down
	      case KEY_DOWN:
	        if (!self.isOpen && self.hasOptions) {
	          self.open();
	        } else if (self.activeOption) {
	          let next = self.getAdjacent(self.activeOption, 1);
	          if (next) self.setActiveOption(next);
	        }
	        preventDefault(e);
	        return;

	      // up: move selection up
	      case KEY_UP:
	        if (self.activeOption) {
	          let prev = self.getAdjacent(self.activeOption, -1);
	          if (prev) self.setActiveOption(prev);
	        }
	        preventDefault(e);
	        return;

	      // return: select active option
	      case KEY_RETURN:
	        if (self.canSelect(self.activeOption)) {
	          self.onOptionSelect(e, self.activeOption);
	          preventDefault(e);

	          // if the option_create=null, the dropdown might be closed
	        } else if (self.settings.create && self.createItem()) {
	          preventDefault(e);

	          // don't submit form when searching for a value
	        } else if (document.activeElement == self.control_input && self.isOpen) {
	          preventDefault(e);
	        }
	        return;

	      // left: modifiy item selection to the left
	      case KEY_LEFT:
	        self.advanceSelection(-1, e);
	        return;

	      // right: modifiy item selection to the right
	      case KEY_RIGHT:
	        self.advanceSelection(1, e);
	        return;

	      // tab: select active option and/or create item
	      case KEY_TAB:
	        if (self.settings.selectOnTab) {
	          if (self.canSelect(self.activeOption)) {
	            self.onOptionSelect(e, self.activeOption);

	            // prevent default [tab] behaviour of jump to the next field
	            // if select isFull, then the dropdown won't be open and [tab] will work normally
	            preventDefault(e);
	          }
	          if (self.settings.create && self.createItem()) {
	            preventDefault(e);
	          }
	        }
	        return;

	      // delete|backspace: delete items
	      case KEY_BACKSPACE:
	      case KEY_DELETE:
	        self.deleteSelection(e);
	        return;
	    }

	    // don't enter text in the control_input when active items are selected
	    if (self.isInputHidden && !isKeyDown(KEY_SHORTCUT, e)) {
	      preventDefault(e);
	    }
	  }

	  /**
	   * Triggered on <input> keyup.
	   *
	   */
	  onInput(e) {
	    if (this.isLocked) {
	      return;
	    }
	    const value = this.inputValue();
	    if (this.lastValue === value) return;
	    this.lastValue = value;
	    if (value == '') {
	      this._onInput();
	      return;
	    }
	    if (this.refreshTimeout) {
	      clearTimeout(this.refreshTimeout);
	    }
	    this.refreshTimeout = timeout(() => {
	      this.refreshTimeout = null;
	      this._onInput();
	    }, this.settings.refreshThrottle);
	  }
	  _onInput() {
	    const value = this.lastValue;
	    if (this.settings.shouldLoad.call(this, value)) {
	      this.load(value);
	    }
	    this.refreshOptions();
	    this.trigger('type', value);
	  }

	  /**
	   * Triggered when the user rolls over
	   * an option in the autocomplete dropdown menu.
	   *
	   */
	  onOptionHover(evt, option) {
	    if (this.ignoreHover) return;
	    this.setActiveOption(option, false);
	  }

	  /**
	   * Triggered on <input> focus.
	   *
	   */
	  onFocus(e) {
	    var self = this;
	    var wasFocused = self.isFocused;
	    if (self.isDisabled || self.isReadOnly) {
	      self.blur();
	      preventDefault(e);
	      return;
	    }
	    if (self.ignoreFocus) return;
	    self.isFocused = true;
	    if (self.settings.preload === 'focus') self.preload();
	    if (!wasFocused) self.trigger('focus');
	    if (!self.activeItems.length) {
	      self.inputState();
	      self.refreshOptions(!!self.settings.openOnFocus);
	    }
	    self.refreshState();
	  }

	  /**
	   * Triggered on <input> blur.
	   *
	   */
	  onBlur(e) {
	    if (document.hasFocus() === false) return;
	    var self = this;
	    if (!self.isFocused) return;
	    self.isFocused = false;
	    self.ignoreFocus = false;
	    var deactivate = () => {
	      self.close();
	      self.setActiveItem();
	      self.setCaret(self.items.length);
	      self.trigger('blur');
	    };
	    if (self.settings.create && self.settings.createOnBlur) {
	      self.createItem(null, deactivate);
	    } else {
	      deactivate();
	    }
	  }

	  /**
	   * Triggered when the user clicks on an option
	   * in the autocomplete dropdown menu.
	   *
	   */
	  onOptionSelect(evt, option) {
	    var value,
	      self = this;

	    // should not be possible to trigger a option under a disabled optgroup
	    if (option.parentElement && option.parentElement.matches('[data-disabled]')) {
	      return;
	    }
	    if (option.classList.contains('create')) {
	      self.createItem(null, () => {
	        if (self.settings.closeAfterSelect) {
	          self.close();
	        }
	      });
	    } else {
	      value = option.dataset.value;
	      if (typeof value !== 'undefined') {
	        self.lastQuery = null;
	        self.addItem(value);
	        if (self.settings.closeAfterSelect) {
	          self.close();
	        }
	        if (!self.settings.hideSelected && evt.type && /click/.test(evt.type)) {
	          self.setActiveOption(option);
	        }
	      }
	    }
	  }

	  /**
	   * Return true if the given option can be selected
	   *
	   */
	  canSelect(option) {
	    if (this.isOpen && option && this.dropdown_content.contains(option)) {
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Triggered when the user clicks on an item
	   * that has been selected.
	   *
	   */
	  onItemSelect(evt, item) {
	    var self = this;
	    if (!self.isLocked && self.settings.mode === 'multi') {
	      preventDefault(evt);
	      self.setActiveItem(item, evt);
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Determines whether or not to invoke
	   * the user-provided option provider / loader
	   *
	   * Note, there is a subtle difference between
	   * this.canLoad() and this.settings.shouldLoad();
	   *
	   *	- settings.shouldLoad() is a user-input validator.
	   *	When false is returned, the not_loading template
	   *	will be added to the dropdown
	   *
	   *	- canLoad() is lower level validator that checks
	   * 	the Tom Select instance. There is no inherent user
	   *	feedback when canLoad returns false
	   *
	   */
	  canLoad(value) {
	    if (!this.settings.load) return false;
	    if (this.loadedSearches.hasOwnProperty(value)) return false;
	    return true;
	  }

	  /**
	   * Invokes the user-provided option provider / loader.
	   *
	   */
	  load(value) {
	    const self = this;
	    if (!self.canLoad(value)) return;
	    addClasses(self.wrapper, self.settings.loadingClass);
	    self.loading++;
	    const callback = self.loadCallback.bind(self);
	    self.settings.load.call(self, value, callback);
	  }

	  /**
	   * Invoked by the user-provided option provider
	   *
	   */
	  loadCallback(options, optgroups) {
	    const self = this;
	    self.loading = Math.max(self.loading - 1, 0);
	    self.lastQuery = null;
	    self.clearActiveOption(); // when new results load, focus should be on first option
	    self.setupOptions(options, optgroups);
	    self.refreshOptions(self.isFocused && !self.isInputHidden);
	    if (!self.loading) {
	      removeClasses(self.wrapper, self.settings.loadingClass);
	    }
	    self.trigger('load', options, optgroups);
	  }
	  preload() {
	    var classList = this.wrapper.classList;
	    if (classList.contains('preloaded')) return;
	    classList.add('preloaded');
	    this.load('');
	  }

	  /**
	   * Sets the input field of the control to the specified value.
	   *
	   */
	  setTextboxValue(value = '') {
	    var input = this.control_input;
	    var changed = input.value !== value;
	    if (changed) {
	      input.value = value;
	      triggerEvent(input, 'update');
	      this.lastValue = value;
	    }
	  }

	  /**
	   * Returns the value of the control. If multiple items
	   * can be selected (e.g. <select multiple>), this returns
	   * an array. If only one item can be selected, this
	   * returns a string.
	   *
	   */
	  getValue() {
	    if (this.is_select_tag && this.input.hasAttribute('multiple')) {
	      return this.items;
	    }
	    return this.items.join(this.settings.delimiter);
	  }

	  /**
	   * Resets the selected items to the given value.
	   *
	   */
	  setValue(value, silent) {
	    var events = silent ? [] : ['change'];
	    debounce_events(this, events, () => {
	      this.clear(silent);
	      this.addItems(value, silent);
	    });
	  }

	  /**
	   * Resets the number of max items to the given value
	   *
	   */
	  setMaxItems(value) {
	    if (value === 0) value = null; //reset to unlimited items.
	    this.settings.maxItems = value;
	    this.refreshState();
	  }

	  /**
	   * Sets the selected item.
	   *
	   */
	  setActiveItem(item, e) {
	    var self = this;
	    var eventName;
	    var i, begin, end, swap;
	    var last;
	    if (self.settings.mode === 'single') return;

	    // clear the active selection
	    if (!item) {
	      self.clearActiveItems();
	      if (self.isFocused) {
	        self.inputState();
	      }
	      return;
	    }

	    // modify selection
	    eventName = e && e.type.toLowerCase();
	    if (eventName === 'click' && isKeyDown('shiftKey', e) && self.activeItems.length) {
	      last = self.getLastActive();
	      begin = Array.prototype.indexOf.call(self.control.children, last);
	      end = Array.prototype.indexOf.call(self.control.children, item);
	      if (begin > end) {
	        swap = begin;
	        begin = end;
	        end = swap;
	      }
	      for (i = begin; i <= end; i++) {
	        item = self.control.children[i];
	        if (self.activeItems.indexOf(item) === -1) {
	          self.setActiveItemClass(item);
	        }
	      }
	      preventDefault(e);
	    } else if (eventName === 'click' && isKeyDown(KEY_SHORTCUT, e) || eventName === 'keydown' && isKeyDown('shiftKey', e)) {
	      if (item.classList.contains('active')) {
	        self.removeActiveItem(item);
	      } else {
	        self.setActiveItemClass(item);
	      }
	    } else {
	      self.clearActiveItems();
	      self.setActiveItemClass(item);
	    }

	    // ensure control has focus
	    self.inputState();
	    if (!self.isFocused) {
	      self.focus();
	    }
	  }

	  /**
	   * Set the active and last-active classes
	   *
	   */
	  setActiveItemClass(item) {
	    const self = this;
	    const last_active = self.control.querySelector('.last-active');
	    if (last_active) removeClasses(last_active, 'last-active');
	    addClasses(item, 'active last-active');
	    self.trigger('item_select', item);
	    if (self.activeItems.indexOf(item) == -1) {
	      self.activeItems.push(item);
	    }
	  }

	  /**
	   * Remove active item
	   *
	   */
	  removeActiveItem(item) {
	    var idx = this.activeItems.indexOf(item);
	    this.activeItems.splice(idx, 1);
	    removeClasses(item, 'active');
	  }

	  /**
	   * Clears all the active items
	   *
	   */
	  clearActiveItems() {
	    removeClasses(this.activeItems, 'active');
	    this.activeItems = [];
	  }

	  /**
	   * Sets the selected item in the dropdown menu
	   * of available options.
	   *
	   */
	  setActiveOption(option, scroll = true) {
	    if (option === this.activeOption) {
	      return;
	    }
	    this.clearActiveOption();
	    if (!option) return;
	    this.activeOption = option;
	    setAttr(this.focus_node, {
	      'aria-activedescendant': option.getAttribute('id')
	    });
	    setAttr(option, {
	      'aria-selected': 'true'
	    });
	    addClasses(option, 'active');
	    if (scroll) this.scrollToOption(option);
	  }

	  /**
	   * Sets the dropdown_content scrollTop to display the option
	   *
	   */
	  scrollToOption(option, behavior) {
	    if (!option) return;
	    const content = this.dropdown_content;
	    const height_menu = content.clientHeight;
	    const scrollTop = content.scrollTop || 0;
	    const height_item = option.offsetHeight;
	    const y = option.getBoundingClientRect().top - content.getBoundingClientRect().top + scrollTop;
	    if (y + height_item > height_menu + scrollTop) {
	      this.scroll(y - height_menu + height_item, behavior);
	    } else if (y < scrollTop) {
	      this.scroll(y, behavior);
	    }
	  }

	  /**
	   * Scroll the dropdown to the given position
	   *
	   */
	  scroll(scrollTop, behavior) {
	    const content = this.dropdown_content;
	    if (behavior) {
	      content.style.scrollBehavior = behavior;
	    }
	    content.scrollTop = scrollTop;
	    content.style.scrollBehavior = '';
	  }

	  /**
	   * Clears the active option
	   *
	   */
	  clearActiveOption() {
	    if (this.activeOption) {
	      removeClasses(this.activeOption, 'active');
	      setAttr(this.activeOption, {
	        'aria-selected': null
	      });
	    }
	    this.activeOption = null;
	    setAttr(this.focus_node, {
	      'aria-activedescendant': null
	    });
	  }

	  /**
	   * Selects all items (CTRL + A).
	   */
	  selectAll() {
	    const self = this;
	    if (self.settings.mode === 'single') return;
	    const activeItems = self.controlChildren();
	    if (!activeItems.length) return;
	    self.inputState();
	    self.close();
	    self.activeItems = activeItems;
	    iterate$1(activeItems, item => {
	      self.setActiveItemClass(item);
	    });
	  }

	  /**
	   * Determines if the control_input should be in a hidden or visible state
	   *
	   */
	  inputState() {
	    var self = this;
	    if (!self.control.contains(self.control_input)) return;
	    setAttr(self.control_input, {
	      placeholder: self.settings.placeholder
	    });
	    if (self.activeItems.length > 0 || !self.isFocused && self.settings.hidePlaceholder && self.items.length > 0) {
	      self.setTextboxValue();
	      self.isInputHidden = true;
	    } else {
	      if (self.settings.hidePlaceholder && self.items.length > 0) {
	        setAttr(self.control_input, {
	          placeholder: ''
	        });
	      }
	      self.isInputHidden = false;
	    }
	    self.wrapper.classList.toggle('input-hidden', self.isInputHidden);
	  }

	  /**
	   * Get the input value
	   */
	  inputValue() {
	    return this.control_input.value.trim();
	  }

	  /**
	   * Gives the control focus.
	   */
	  focus() {
	    var self = this;
	    if (self.isDisabled || self.isReadOnly) return;
	    self.ignoreFocus = true;
	    if (self.control_input.offsetWidth) {
	      self.control_input.focus();
	    } else {
	      self.focus_node.focus();
	    }
	    setTimeout(() => {
	      self.ignoreFocus = false;
	      self.onFocus();
	    }, 0);
	  }

	  /**
	   * Forces the control out of focus.
	   *
	   */
	  blur() {
	    this.focus_node.blur();
	    this.onBlur();
	  }

	  /**
	   * Returns a function that scores an object
	   * to show how good of a match it is to the
	   * provided query.
	   *
	   * @return {function}
	   */
	  getScoreFunction(query) {
	    return this.sifter.getScoreFunction(query, this.getSearchOptions());
	  }

	  /**
	   * Returns search options for sifter (the system
	   * for scoring and sorting results).
	   *
	   * @see https://github.com/orchidjs/sifter.js
	   * @return {object}
	   */
	  getSearchOptions() {
	    var settings = this.settings;
	    var sort = settings.sortField;
	    if (typeof settings.sortField === 'string') {
	      sort = [{
	        field: settings.sortField
	      }];
	    }
	    return {
	      fields: settings.searchField,
	      conjunction: settings.searchConjunction,
	      sort: sort,
	      nesting: settings.nesting
	    };
	  }

	  /**
	   * Searches through available options and returns
	   * a sorted array of matches.
	   *
	   */
	  search(query) {
	    var result, calculateScore;
	    var self = this;
	    var options = this.getSearchOptions();

	    // validate user-provided result scoring function
	    if (self.settings.score) {
	      calculateScore = self.settings.score.call(self, query);
	      if (typeof calculateScore !== 'function') {
	        throw new Error('Tom Select "score" setting must be a function that returns a function');
	      }
	    }

	    // perform search
	    if (query !== self.lastQuery) {
	      self.lastQuery = query;
	      result = self.sifter.search(query, Object.assign(options, {
	        score: calculateScore
	      }));
	      self.currentResults = result;
	    } else {
	      result = Object.assign({}, self.currentResults);
	    }

	    // filter out selected items
	    if (self.settings.hideSelected) {
	      result.items = result.items.filter(item => {
	        let hashed = hash_key(item.id);
	        return !(hashed && self.items.indexOf(hashed) !== -1);
	      });
	    }
	    return result;
	  }

	  /**
	   * Refreshes the list of available options shown
	   * in the autocomplete dropdown menu.
	   *
	   */
	  refreshOptions(triggerDropdown = true) {
	    var i, j, k, n, optgroup, optgroups, html, has_create_option, active_group;
	    var create;
	    const groups = {};
	    const groups_order = [];
	    var self = this;
	    var query = self.inputValue();
	    const same_query = query === self.lastQuery || query == '' && self.lastQuery == null;
	    var results = self.search(query);
	    var active_option = null;
	    var show_dropdown = self.settings.shouldOpen || false;
	    var dropdown_content = self.dropdown_content;
	    if (same_query) {
	      active_option = self.activeOption;
	      if (active_option) {
	        active_group = active_option.closest('[data-group]');
	      }
	    }

	    // build markup
	    n = results.items.length;
	    if (typeof self.settings.maxOptions === 'number') {
	      n = Math.min(n, self.settings.maxOptions);
	    }
	    if (n > 0) {
	      show_dropdown = true;
	    }

	    // get fragment for group and the position of the group in group_order
	    const getGroupFragment = (optgroup, order) => {
	      let group_order_i = groups[optgroup];
	      if (group_order_i !== undefined) {
	        let order_group = groups_order[group_order_i];
	        if (order_group !== undefined) {
	          return [group_order_i, order_group.fragment];
	        }
	      }
	      let group_fragment = document.createDocumentFragment();
	      group_order_i = groups_order.length;
	      groups_order.push({
	        fragment: group_fragment,
	        order,
	        optgroup
	      });
	      return [group_order_i, group_fragment];
	    };

	    // render and group available options individually
	    for (i = 0; i < n; i++) {
	      // get option dom element
	      let item = results.items[i];
	      if (!item) continue;
	      let opt_value = item.id;
	      let option = self.options[opt_value];
	      if (option === undefined) continue;
	      let opt_hash = get_hash(opt_value);
	      let option_el = self.getOption(opt_hash, true);

	      // toggle 'selected' class
	      if (!self.settings.hideSelected) {
	        option_el.classList.toggle('selected', self.items.includes(opt_hash));
	      }
	      optgroup = option[self.settings.optgroupField] || '';
	      optgroups = Array.isArray(optgroup) ? optgroup : [optgroup];
	      for (j = 0, k = optgroups && optgroups.length; j < k; j++) {
	        optgroup = optgroups[j];
	        let order = option.$order;
	        let self_optgroup = self.optgroups[optgroup];
	        if (self_optgroup === undefined) {
	          optgroup = '';
	        } else {
	          order = self_optgroup.$order;
	        }
	        const [group_order_i, group_fragment] = getGroupFragment(optgroup, order);

	        // nodes can only have one parent, so if the option is in mutple groups, we need a clone
	        if (j > 0) {
	          option_el = option_el.cloneNode(true);
	          setAttr(option_el, {
	            id: option.$id + '-clone-' + j,
	            'aria-selected': null
	          });
	          option_el.classList.add('ts-cloned');
	          removeClasses(option_el, 'active');

	          // make sure we keep the activeOption in the same group
	          if (self.activeOption && self.activeOption.dataset.value == opt_value) {
	            if (active_group && active_group.dataset.group === optgroup.toString()) {
	              active_option = option_el;
	            }
	          }
	        }
	        group_fragment.appendChild(option_el);
	        if (optgroup != '') {
	          groups[optgroup] = group_order_i;
	        }
	      }
	    }

	    // sort optgroups
	    if (self.settings.lockOptgroupOrder) {
	      groups_order.sort((a, b) => {
	        return a.order - b.order;
	      });
	    }

	    // render optgroup headers & join groups
	    html = document.createDocumentFragment();
	    iterate$1(groups_order, group_order => {
	      let group_fragment = group_order.fragment;
	      let optgroup = group_order.optgroup;
	      if (!group_fragment || !group_fragment.children.length) return;
	      let group_heading = self.optgroups[optgroup];
	      if (group_heading !== undefined) {
	        let group_options = document.createDocumentFragment();
	        let header = self.render('optgroup_header', group_heading);
	        append(group_options, header);
	        append(group_options, group_fragment);
	        let group_html = self.render('optgroup', {
	          group: group_heading,
	          options: group_options
	        });
	        append(html, group_html);
	      } else {
	        append(html, group_fragment);
	      }
	    });
	    dropdown_content.innerHTML = '';
	    append(dropdown_content, html);

	    // highlight matching terms inline
	    if (self.settings.highlight) {
	      removeHighlight(dropdown_content);
	      if (results.query.length && results.tokens.length) {
	        iterate$1(results.tokens, tok => {
	          highlight(dropdown_content, tok.regex);
	        });
	      }
	    }

	    // helper method for adding templates to dropdown
	    var add_template = template => {
	      let content = self.render(template, {
	        input: query
	      });
	      if (content) {
	        show_dropdown = true;
	        dropdown_content.insertBefore(content, dropdown_content.firstChild);
	      }
	      return content;
	    };

	    // add loading message
	    if (self.loading) {
	      add_template('loading');

	      // invalid query
	    } else if (!self.settings.shouldLoad.call(self, query)) {
	      add_template('not_loading');

	      // add no_results message
	    } else if (results.items.length === 0) {
	      add_template('no_results');
	    }

	    // add create option
	    has_create_option = self.canCreate(query);
	    if (has_create_option) {
	      create = add_template('option_create');
	    }

	    // activate
	    self.hasOptions = results.items.length > 0 || has_create_option;
	    if (show_dropdown) {
	      if (results.items.length > 0) {
	        if (!active_option && self.settings.mode === 'single' && self.items[0] != undefined) {
	          active_option = self.getOption(self.items[0]);
	        }
	        if (!dropdown_content.contains(active_option)) {
	          let active_index = 0;
	          if (create && !self.settings.addPrecedence) {
	            active_index = 1;
	          }
	          active_option = self.selectable()[active_index];
	        }
	      } else if (create) {
	        active_option = create;
	      }
	      if (triggerDropdown && !self.isOpen) {
	        self.open();
	        self.scrollToOption(active_option, 'auto');
	      }
	      self.setActiveOption(active_option);
	    } else {
	      self.clearActiveOption();
	      if (triggerDropdown && self.isOpen) {
	        self.close(false); // if create_option=null, we want the dropdown to close but not reset the textbox value
	      }
	    }
	  }

	  /**
	   * Return list of selectable options
	   *
	   */
	  selectable() {
	    return this.dropdown_content.querySelectorAll('[data-selectable]');
	  }

	  /**
	   * Adds an available option. If it already exists,
	   * nothing will happen. Note: this does not refresh
	   * the options list dropdown (use `refreshOptions`
	   * for that).
	   *
	   * Usage:
	   *
	   *   this.addOption(data)
	   *
	   */
	  addOption(data, user_created = false) {
	    const self = this;

	    // @deprecated 1.7.7
	    // use addOptions( array, user_created ) for adding multiple options
	    if (Array.isArray(data)) {
	      self.addOptions(data, user_created);
	      return false;
	    }
	    const key = hash_key(data[self.settings.valueField]);
	    if (key === null || self.options.hasOwnProperty(key)) {
	      return false;
	    }
	    data.$order = data.$order || ++self.order;
	    data.$id = self.inputId + '-opt-' + data.$order;
	    self.options[key] = data;
	    self.lastQuery = null;
	    if (user_created) {
	      self.userOptions[key] = user_created;
	      self.trigger('option_add', key, data);
	    }
	    return key;
	  }

	  /**
	   * Add multiple options
	   *
	   */
	  addOptions(data, user_created = false) {
	    iterate$1(data, dat => {
	      this.addOption(dat, user_created);
	    });
	  }

	  /**
	   * @deprecated 1.7.7
	   */
	  registerOption(data) {
	    return this.addOption(data);
	  }

	  /**
	   * Registers an option group to the pool of option groups.
	   *
	   * @return {boolean|string}
	   */
	  registerOptionGroup(data) {
	    var key = hash_key(data[this.settings.optgroupValueField]);
	    if (key === null) return false;
	    data.$order = data.$order || ++this.order;
	    this.optgroups[key] = data;
	    return key;
	  }

	  /**
	   * Registers a new optgroup for options
	   * to be bucketed into.
	   *
	   */
	  addOptionGroup(id, data) {
	    var hashed_id;
	    data[this.settings.optgroupValueField] = id;
	    if (hashed_id = this.registerOptionGroup(data)) {
	      this.trigger('optgroup_add', hashed_id, data);
	    }
	  }

	  /**
	   * Removes an existing option group.
	   *
	   */
	  removeOptionGroup(id) {
	    if (this.optgroups.hasOwnProperty(id)) {
	      delete this.optgroups[id];
	      this.clearCache();
	      this.trigger('optgroup_remove', id);
	    }
	  }

	  /**
	   * Clears all existing option groups.
	   */
	  clearOptionGroups() {
	    this.optgroups = {};
	    this.clearCache();
	    this.trigger('optgroup_clear');
	  }

	  /**
	   * Updates an option available for selection. If
	   * it is visible in the selected items or options
	   * dropdown, it will be re-rendered automatically.
	   *
	   */
	  updateOption(value, data) {
	    const self = this;
	    var item_new;
	    var index_item;
	    const value_old = hash_key(value);
	    const value_new = hash_key(data[self.settings.valueField]);

	    // sanity checks
	    if (value_old === null) return;
	    const data_old = self.options[value_old];
	    if (data_old == undefined) return;
	    if (typeof value_new !== 'string') throw new Error('Value must be set in option data');
	    const option = self.getOption(value_old);
	    const item = self.getItem(value_old);
	    data.$order = data.$order || data_old.$order;
	    delete self.options[value_old];

	    // invalidate render cache
	    // don't remove existing node yet, we'll remove it after replacing it
	    self.uncacheValue(value_new);
	    self.options[value_new] = data;

	    // update the option if it's in the dropdown
	    if (option) {
	      if (self.dropdown_content.contains(option)) {
	        const option_new = self._render('option', data);
	        replaceNode(option, option_new);
	        if (self.activeOption === option) {
	          self.setActiveOption(option_new);
	        }
	      }
	      option.remove();
	    }

	    // update the item if we have one
	    if (item) {
	      index_item = self.items.indexOf(value_old);
	      if (index_item !== -1) {
	        self.items.splice(index_item, 1, value_new);
	      }
	      item_new = self._render('item', data);
	      if (item.classList.contains('active')) addClasses(item_new, 'active');
	      replaceNode(item, item_new);
	    }

	    // invalidate last query because we might have updated the sortField
	    self.lastQuery = null;
	  }

	  /**
	   * Removes a single option.
	   *
	   */
	  removeOption(value, silent) {
	    const self = this;
	    value = get_hash(value);
	    self.uncacheValue(value);
	    delete self.userOptions[value];
	    delete self.options[value];
	    self.lastQuery = null;
	    self.trigger('option_remove', value);
	    self.removeItem(value, silent);
	  }

	  /**
	   * Clears all options.
	   */
	  clearOptions(filter) {
	    const boundFilter = (filter || this.clearFilter).bind(this);
	    this.loadedSearches = {};
	    this.userOptions = {};
	    this.clearCache();
	    const selected = {};
	    iterate$1(this.options, (option, key) => {
	      if (boundFilter(option, key)) {
	        selected[key] = option;
	      }
	    });
	    this.options = this.sifter.items = selected;
	    this.lastQuery = null;
	    this.trigger('option_clear');
	  }

	  /**
	   * Used by clearOptions() to decide whether or not an option should be removed
	   * Return true to keep an option, false to remove
	   *
	   */
	  clearFilter(option, value) {
	    if (this.items.indexOf(value) >= 0) {
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Returns the dom element of the option
	   * matching the given value.
	   *
	   */
	  getOption(value, create = false) {
	    const hashed = hash_key(value);
	    if (hashed === null) return null;
	    const option = this.options[hashed];
	    if (option != undefined) {
	      if (option.$div) {
	        return option.$div;
	      }
	      if (create) {
	        return this._render('option', option);
	      }
	    }
	    return null;
	  }

	  /**
	   * Returns the dom element of the next or previous dom element of the same type
	   * Note: adjacent options may not be adjacent DOM elements (optgroups)
	   *
	   */
	  getAdjacent(option, direction, type = 'option') {
	    var self = this,
	      all;
	    if (!option) {
	      return null;
	    }
	    if (type == 'item') {
	      all = self.controlChildren();
	    } else {
	      all = self.dropdown_content.querySelectorAll('[data-selectable]');
	    }
	    for (let i = 0; i < all.length; i++) {
	      if (all[i] != option) {
	        continue;
	      }
	      if (direction > 0) {
	        return all[i + 1];
	      }
	      return all[i - 1];
	    }
	    return null;
	  }

	  /**
	   * Returns the dom element of the item
	   * matching the given value.
	   *
	   */
	  getItem(item) {
	    if (typeof item == 'object') {
	      return item;
	    }
	    var value = hash_key(item);
	    return value !== null ? this.control.querySelector(`[data-value="${addSlashes(value)}"]`) : null;
	  }

	  /**
	   * "Selects" multiple items at once. Adds them to the list
	   * at the current caret position.
	   *
	   */
	  addItems(values, silent) {
	    var self = this;
	    var items = Array.isArray(values) ? values : [values];
	    items = items.filter(x => self.items.indexOf(x) === -1);
	    const last_item = items[items.length - 1];
	    items.forEach(item => {
	      self.isPending = item !== last_item;
	      self.addItem(item, silent);
	    });
	  }

	  /**
	   * "Selects" an item. Adds it to the list
	   * at the current caret position.
	   *
	   */
	  addItem(value, silent) {
	    var events = silent ? [] : ['change', 'dropdown_close'];
	    debounce_events(this, events, () => {
	      var item, wasFull;
	      const self = this;
	      const inputMode = self.settings.mode;
	      const hashed = hash_key(value);
	      if (hashed && self.items.indexOf(hashed) !== -1) {
	        if (inputMode === 'single') {
	          self.close();
	        }
	        if (inputMode === 'single' || !self.settings.duplicates) {
	          return;
	        }
	      }
	      if (hashed === null || !self.options.hasOwnProperty(hashed)) return;
	      if (inputMode === 'single') self.clear(silent);
	      if (inputMode === 'multi' && self.isFull()) return;
	      item = self._render('item', self.options[hashed]);
	      if (self.control.contains(item)) {
	        // duplicates
	        item = item.cloneNode(true);
	      }
	      wasFull = self.isFull();
	      self.items.splice(self.caretPos, 0, hashed);
	      self.insertAtCaret(item);
	      if (self.isSetup) {
	        // update menu / remove the option (if this is not one item being added as part of series)
	        if (!self.isPending && self.settings.hideSelected) {
	          let option = self.getOption(hashed);
	          let next = self.getAdjacent(option, 1);
	          if (next) {
	            self.setActiveOption(next);
	          }
	        }

	        // refreshOptions after setActiveOption(),
	        // otherwise setActiveOption() will be called by refreshOptions() with the wrong value
	        if (!self.isPending && !self.settings.closeAfterSelect) {
	          self.refreshOptions(self.isFocused && inputMode !== 'single');
	        }

	        // hide the menu if the maximum number of items have been selected or no options are left
	        if (self.settings.closeAfterSelect != false && self.isFull()) {
	          self.close();
	        } else if (!self.isPending) {
	          self.positionDropdown();
	        }
	        self.trigger('item_add', hashed, item);
	        if (!self.isPending) {
	          self.updateOriginalInput({
	            silent: silent
	          });
	        }
	      }
	      if (!self.isPending || !wasFull && self.isFull()) {
	        self.inputState();
	        self.refreshState();
	      }
	    });
	  }

	  /**
	   * Removes the selected item matching
	   * the provided value.
	   *
	   */
	  removeItem(item = null, silent) {
	    const self = this;
	    item = self.getItem(item);
	    if (!item) return;
	    var i, idx;
	    const value = item.dataset.value;
	    i = nodeIndex(item);
	    item.remove();
	    if (item.classList.contains('active')) {
	      idx = self.activeItems.indexOf(item);
	      self.activeItems.splice(idx, 1);
	      removeClasses(item, 'active');
	    }
	    self.items.splice(i, 1);
	    self.lastQuery = null;
	    if (!self.settings.persist && self.userOptions.hasOwnProperty(value)) {
	      self.removeOption(value, silent);
	    }
	    if (i < self.caretPos) {
	      self.setCaret(self.caretPos - 1);
	    }
	    self.updateOriginalInput({
	      silent: silent
	    });
	    self.refreshState();
	    self.positionDropdown();
	    self.trigger('item_remove', value, item);
	  }

	  /**
	   * Invokes the `create` method provided in the
	   * TomSelect options that should provide the data
	   * for the new item, given the user input.
	   *
	   * Once this completes, it will be added
	   * to the item list.
	   *
	   */
	  createItem(input = null, callback = () => {}) {
	    // triggerDropdown parameter @deprecated 2.1.1
	    if (arguments.length === 3) {
	      callback = arguments[2];
	    }
	    if (typeof callback != 'function') {
	      callback = () => {};
	    }
	    var self = this;
	    var caret = self.caretPos;
	    var output;
	    input = input || self.inputValue();
	    if (!self.canCreate(input)) {
	      callback();
	      return false;
	    }
	    self.lock();
	    var created = false;
	    var create = data => {
	      self.unlock();
	      if (!data || typeof data !== 'object') return callback();
	      var value = hash_key(data[self.settings.valueField]);
	      if (typeof value !== 'string') {
	        return callback();
	      }
	      self.setTextboxValue();
	      self.addOption(data, true);
	      self.setCaret(caret);
	      self.addItem(value);
	      callback(data);
	      created = true;
	    };
	    if (typeof self.settings.create === 'function') {
	      output = self.settings.create.call(this, input, create);
	    } else {
	      output = {
	        [self.settings.labelField]: input,
	        [self.settings.valueField]: input
	      };
	    }
	    if (!created) {
	      create(output);
	    }
	    return true;
	  }

	  /**
	   * Re-renders the selected item lists.
	   */
	  refreshItems() {
	    var self = this;
	    self.lastQuery = null;
	    if (self.isSetup) {
	      self.addItems(self.items);
	    }
	    self.updateOriginalInput();
	    self.refreshState();
	  }

	  /**
	   * Updates all state-dependent attributes
	   * and CSS classes.
	   */
	  refreshState() {
	    const self = this;
	    self.refreshValidityState();
	    const isFull = self.isFull();
	    const isLocked = self.isLocked;
	    self.wrapper.classList.toggle('rtl', self.rtl);
	    const wrap_classList = self.wrapper.classList;
	    wrap_classList.toggle('focus', self.isFocused);
	    wrap_classList.toggle('disabled', self.isDisabled);
	    wrap_classList.toggle('readonly', self.isReadOnly);
	    wrap_classList.toggle('required', self.isRequired);
	    wrap_classList.toggle('invalid', !self.isValid);
	    wrap_classList.toggle('locked', isLocked);
	    wrap_classList.toggle('full', isFull);
	    wrap_classList.toggle('input-active', self.isFocused && !self.isInputHidden);
	    wrap_classList.toggle('dropdown-active', self.isOpen);
	    wrap_classList.toggle('has-options', isEmptyObject(self.options));
	    wrap_classList.toggle('has-items', self.items.length > 0);
	  }

	  /**
	   * Update the `required` attribute of both input and control input.
	   *
	   * The `required` property needs to be activated on the control input
	   * for the error to be displayed at the right place. `required` also
	   * needs to be temporarily deactivated on the input since the input is
	   * hidden and can't show errors.
	   */
	  refreshValidityState() {
	    var self = this;
	    if (!self.input.validity) {
	      return;
	    }
	    self.isValid = self.input.validity.valid;
	    self.isInvalid = !self.isValid;
	  }

	  /**
	   * Determines whether or not more items can be added
	   * to the control without exceeding the user-defined maximum.
	   *
	   * @returns {boolean}
	   */
	  isFull() {
	    return this.settings.maxItems !== null && this.items.length >= this.settings.maxItems;
	  }

	  /**
	   * Refreshes the original <select> or <input>
	   * element to reflect the current state.
	   *
	   */
	  updateOriginalInput(opts = {}) {
	    const self = this;
	    var option, label;
	    const empty_option = self.input.querySelector('option[value=""]');
	    if (self.is_select_tag) {
	      const selected = [];
	      const has_selected = self.input.querySelectorAll('option:checked').length;
	      function AddSelected(option_el, value, label) {
	        if (!option_el) {
	          option_el = getDom('<option value="' + escape_html(value) + '">' + escape_html(label) + '</option>');
	        }

	        // don't move empty option from top of list
	        // fixes bug in firefox https://bugzilla.mozilla.org/show_bug.cgi?id=1725293
	        if (option_el != empty_option) {
	          self.input.append(option_el);
	        }
	        selected.push(option_el);

	        // marking empty option as selected can break validation
	        // fixes https://github.com/orchidjs/tom-select/issues/303
	        if (option_el != empty_option || has_selected > 0) {
	          option_el.selected = true;
	        }
	        return option_el;
	      }

	      // unselect all selected options
	      self.input.querySelectorAll('option:checked').forEach(option_el => {
	        option_el.selected = false;
	      });

	      // nothing selected?
	      if (self.items.length == 0 && self.settings.mode == 'single') {
	        AddSelected(empty_option, "", "");

	        // order selected <option> tags for values in self.items
	      } else {
	        self.items.forEach(value => {
	          option = self.options[value];
	          label = option[self.settings.labelField] || '';
	          if (selected.includes(option.$option)) {
	            const reuse_opt = self.input.querySelector(`option[value="${addSlashes(value)}"]:not(:checked)`);
	            AddSelected(reuse_opt, value, label);
	          } else {
	            option.$option = AddSelected(option.$option, value, label);
	          }
	        });
	      }
	    } else {
	      self.input.value = self.getValue();
	    }
	    if (self.isSetup) {
	      if (!opts.silent) {
	        self.trigger('change', self.getValue());
	      }
	    }
	  }

	  /**
	   * Shows the autocomplete dropdown containing
	   * the available options.
	   */
	  open() {
	    var self = this;
	    if (self.isLocked || self.isOpen || self.settings.mode === 'multi' && self.isFull()) return;
	    self.isOpen = true;
	    setAttr(self.focus_node, {
	      'aria-expanded': 'true'
	    });
	    self.refreshState();
	    applyCSS(self.dropdown, {
	      visibility: 'hidden',
	      display: 'block'
	    });
	    self.positionDropdown();
	    applyCSS(self.dropdown, {
	      visibility: 'visible',
	      display: 'block'
	    });
	    self.focus();
	    self.trigger('dropdown_open', self.dropdown);
	  }

	  /**
	   * Closes the autocomplete dropdown menu.
	   */
	  close(setTextboxValue = true) {
	    var self = this;
	    var trigger = self.isOpen;
	    if (setTextboxValue) {
	      // before blur() to prevent form onchange event
	      self.setTextboxValue();
	      if (self.settings.mode === 'single' && self.items.length) {
	        self.inputState();
	      }
	    }
	    self.isOpen = false;
	    setAttr(self.focus_node, {
	      'aria-expanded': 'false'
	    });
	    applyCSS(self.dropdown, {
	      display: 'none'
	    });
	    if (self.settings.hideSelected) {
	      self.clearActiveOption();
	    }
	    self.refreshState();
	    if (trigger) self.trigger('dropdown_close', self.dropdown);
	  }

	  /**
	   * Calculates and applies the appropriate
	   * position of the dropdown if dropdownParent = 'body'.
	   * Otherwise, position is determined by css
	   */
	  positionDropdown() {
	    if (this.settings.dropdownParent !== 'body') {
	      return;
	    }
	    var context = this.control;
	    var rect = context.getBoundingClientRect();
	    var top = context.offsetHeight + rect.top + window.scrollY;
	    var left = rect.left + window.scrollX;
	    applyCSS(this.dropdown, {
	      width: rect.width + 'px',
	      top: top + 'px',
	      left: left + 'px'
	    });
	  }

	  /**
	   * Resets / clears all selected items
	   * from the control.
	   *
	   */
	  clear(silent) {
	    var self = this;
	    if (!self.items.length) return;
	    var items = self.controlChildren();
	    iterate$1(items, item => {
	      self.removeItem(item, true);
	    });
	    self.inputState();
	    if (!silent) self.updateOriginalInput();
	    self.trigger('clear');
	  }

	  /**
	   * A helper method for inserting an element
	   * at the current caret position.
	   *
	   */
	  insertAtCaret(el) {
	    const self = this;
	    const caret = self.caretPos;
	    const target = self.control;
	    target.insertBefore(el, target.children[caret] || null);
	    self.setCaret(caret + 1);
	  }

	  /**
	   * Removes the current selected item(s).
	   *
	   */
	  deleteSelection(e) {
	    var direction, selection, caret, tail;
	    var self = this;
	    direction = e && e.keyCode === KEY_BACKSPACE ? -1 : 1;
	    selection = getSelection(self.control_input);

	    // determine items that will be removed
	    const rm_items = [];
	    if (self.activeItems.length) {
	      tail = getTail(self.activeItems, direction);
	      caret = nodeIndex(tail);
	      if (direction > 0) {
	        caret++;
	      }
	      iterate$1(self.activeItems, item => rm_items.push(item));
	    } else if ((self.isFocused || self.settings.mode === 'single') && self.items.length) {
	      const items = self.controlChildren();
	      let rm_item;
	      if (direction < 0 && selection.start === 0 && selection.length === 0) {
	        rm_item = items[self.caretPos - 1];
	      } else if (direction > 0 && selection.start === self.inputValue().length) {
	        rm_item = items[self.caretPos];
	      }
	      if (rm_item !== undefined) {
	        rm_items.push(rm_item);
	      }
	    }
	    if (!self.shouldDelete(rm_items, e)) {
	      return false;
	    }
	    preventDefault(e, true);

	    // perform removal
	    if (typeof caret !== 'undefined') {
	      self.setCaret(caret);
	    }
	    while (rm_items.length) {
	      self.removeItem(rm_items.pop());
	    }
	    self.inputState();
	    self.positionDropdown();
	    self.refreshOptions(false);
	    return true;
	  }

	  /**
	   * Return true if the items should be deleted
	   */
	  shouldDelete(items, evt) {
	    const values = items.map(item => item.dataset.value);

	    // allow the callback to abort
	    if (!values.length || typeof this.settings.onDelete === 'function' && this.settings.onDelete(values, evt) === false) {
	      return false;
	    }
	    return true;
	  }

	  /**
	   * Selects the previous / next item (depending on the `direction` argument).
	   *
	   * > 0 - right
	   * < 0 - left
	   *
	   */
	  advanceSelection(direction, e) {
	    var last_active,
	      adjacent,
	      self = this;
	    if (self.rtl) direction *= -1;
	    if (self.inputValue().length) return;

	    // add or remove to active items
	    if (isKeyDown(KEY_SHORTCUT, e) || isKeyDown('shiftKey', e)) {
	      last_active = self.getLastActive(direction);
	      if (last_active) {
	        if (!last_active.classList.contains('active')) {
	          adjacent = last_active;
	        } else {
	          adjacent = self.getAdjacent(last_active, direction, 'item');
	        }

	        // if no active item, get items adjacent to the control input
	      } else if (direction > 0) {
	        adjacent = self.control_input.nextElementSibling;
	      } else {
	        adjacent = self.control_input.previousElementSibling;
	      }
	      if (adjacent) {
	        if (adjacent.classList.contains('active')) {
	          self.removeActiveItem(last_active);
	        }
	        self.setActiveItemClass(adjacent); // mark as last_active !! after removeActiveItem() on last_active
	      }

	      // move caret to the left or right
	    } else {
	      self.moveCaret(direction);
	    }
	  }
	  moveCaret(direction) {}

	  /**
	   * Get the last active item
	   *
	   */
	  getLastActive(direction) {
	    let last_active = this.control.querySelector('.last-active');
	    if (last_active) {
	      return last_active;
	    }
	    var result = this.control.querySelectorAll('.active');
	    if (result) {
	      return getTail(result, direction);
	    }
	  }

	  /**
	   * Moves the caret to the specified index.
	   *
	   * The input must be moved by leaving it in place and moving the
	   * siblings, due to the fact that focus cannot be restored once lost
	   * on mobile webkit devices
	   *
	   */
	  setCaret(new_pos) {
	    this.caretPos = this.items.length;
	  }

	  /**
	   * Return list of item dom elements
	   *
	   */
	  controlChildren() {
	    return Array.from(this.control.querySelectorAll('[data-ts-item]'));
	  }

	  /**
	   * Disables user input on the control. Used while
	   * items are being asynchronously created.
	   */
	  lock() {
	    this.setLocked(true);
	  }

	  /**
	   * Re-enables user input on the control.
	   */
	  unlock() {
	    this.setLocked(false);
	  }

	  /**
	   * Disable or enable user input on the control
	   */
	  setLocked(lock = this.isReadOnly || this.isDisabled) {
	    this.isLocked = lock;
	    this.refreshState();
	  }

	  /**
	   * Disables user input on the control completely.
	   * While disabled, it cannot receive focus.
	   */
	  disable() {
	    this.setDisabled(true);
	    this.close();
	  }

	  /**
	   * Enables the control so that it can respond
	   * to focus and user input.
	   */
	  enable() {
	    this.setDisabled(false);
	  }
	  setDisabled(disabled) {
	    this.focus_node.tabIndex = disabled ? -1 : this.tabIndex;
	    this.isDisabled = disabled;
	    this.input.disabled = disabled;
	    this.control_input.disabled = disabled;
	    this.setLocked();
	  }
	  setReadOnly(isReadOnly) {
	    this.isReadOnly = isReadOnly;
	    this.input.readOnly = isReadOnly;
	    this.control_input.readOnly = isReadOnly;
	    this.setLocked();
	  }

	  /**
	   * Completely destroys the control and
	   * unbinds all event listeners so that it can
	   * be garbage collected.
	   */
	  destroy() {
	    var self = this;
	    var revertSettings = self.revertSettings;
	    self.trigger('destroy');
	    self.off();
	    self.wrapper.remove();
	    self.dropdown.remove();
	    self.input.innerHTML = revertSettings.innerHTML;
	    self.input.tabIndex = revertSettings.tabIndex;
	    removeClasses(self.input, 'tomselected', 'ts-hidden-accessible');
	    self._destroy();
	    delete self.input.tomselect;
	  }

	  /**
	   * A helper method for rendering "item" and
	   * "option" templates, given the data.
	   *
	   */
	  render(templateName, data) {
	    var id, html;
	    const self = this;
	    if (typeof this.settings.render[templateName] !== 'function') {
	      return null;
	    }

	    // render markup
	    html = self.settings.render[templateName].call(this, data, escape_html);
	    if (!html) {
	      return null;
	    }
	    html = getDom(html);

	    // add mandatory attributes
	    if (templateName === 'option' || templateName === 'option_create') {
	      if (data[self.settings.disabledField]) {
	        setAttr(html, {
	          'aria-disabled': 'true'
	        });
	      } else {
	        setAttr(html, {
	          'data-selectable': ''
	        });
	      }
	    } else if (templateName === 'optgroup') {
	      id = data.group[self.settings.optgroupValueField];
	      setAttr(html, {
	        'data-group': id
	      });
	      if (data.group[self.settings.disabledField]) {
	        setAttr(html, {
	          'data-disabled': ''
	        });
	      }
	    }
	    if (templateName === 'option' || templateName === 'item') {
	      const value = get_hash(data[self.settings.valueField]);
	      setAttr(html, {
	        'data-value': value
	      });

	      // make sure we have some classes if a template is overwritten
	      if (templateName === 'item') {
	        addClasses(html, self.settings.itemClass);
	        setAttr(html, {
	          'data-ts-item': ''
	        });
	      } else {
	        addClasses(html, self.settings.optionClass);
	        setAttr(html, {
	          role: 'option',
	          id: data.$id
	        });

	        // update cache
	        data.$div = html;
	        self.options[value] = data;
	      }
	    }
	    return html;
	  }

	  /**
	   * Type guarded rendering
	   *
	   */
	  _render(templateName, data) {
	    const html = this.render(templateName, data);
	    if (html == null) {
	      throw 'HTMLElement expected';
	    }
	    return html;
	  }

	  /**
	   * Clears the render cache for a template. If
	   * no template is given, clears all render
	   * caches.
	   *
	   */
	  clearCache() {
	    iterate$1(this.options, option => {
	      if (option.$div) {
	        option.$div.remove();
	        delete option.$div;
	      }
	    });
	  }

	  /**
	   * Removes a value from item and option caches
	   *
	   */
	  uncacheValue(value) {
	    const option_el = this.getOption(value);
	    if (option_el) option_el.remove();
	  }

	  /**
	   * Determines whether or not to display the
	   * create item prompt, given a user input.
	   *
	   */
	  canCreate(input) {
	    return this.settings.create && input.length > 0 && this.settings.createFilter.call(this, input);
	  }

	  /**
	   * Wraps this.`method` so that `new_fn` can be invoked 'before', 'after', or 'instead' of the original method
	   *
	   * this.hook('instead','onKeyDown',function( arg1, arg2 ...){
	   *
	   * });
	   */
	  hook(when, method, new_fn) {
	    var self = this;
	    var orig_method = self[method];
	    self[method] = function () {
	      var result, result_new;
	      if (when === 'after') {
	        result = orig_method.apply(self, arguments);
	      }
	      result_new = new_fn.apply(self, arguments);
	      if (when === 'instead') {
	        return result_new;
	      }
	      if (when === 'before') {
	        result = orig_method.apply(self, arguments);
	      }
	      return result;
	    };
	  }
	}

	/**
	 * Plugin: "change_listener" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function change_listener () {
	  addEvent(this.input, 'change', () => {
	    this.sync();
	  });
	}

	/**
	 * Plugin: "checkbox_options" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function checkbox_options (userOptions) {
	  var self = this;
	  var orig_onOptionSelect = self.onOptionSelect;
	  self.settings.hideSelected = false;
	  const cbOptions = Object.assign({
	    // so that the user may add different ones as well
	    className: "tomselect-checkbox",
	    // the following default to the historic plugin's values
	    checkedClassNames: undefined,
	    uncheckedClassNames: undefined
	  }, userOptions);
	  var UpdateChecked = function UpdateChecked(checkbox, toCheck) {
	    if (toCheck) {
	      checkbox.checked = true;
	      if (cbOptions.uncheckedClassNames) {
	        checkbox.classList.remove(...cbOptions.uncheckedClassNames);
	      }
	      if (cbOptions.checkedClassNames) {
	        checkbox.classList.add(...cbOptions.checkedClassNames);
	      }
	    } else {
	      checkbox.checked = false;
	      if (cbOptions.checkedClassNames) {
	        checkbox.classList.remove(...cbOptions.checkedClassNames);
	      }
	      if (cbOptions.uncheckedClassNames) {
	        checkbox.classList.add(...cbOptions.uncheckedClassNames);
	      }
	    }
	  };

	  // update the checkbox for an option
	  var UpdateCheckbox = function UpdateCheckbox(option) {
	    setTimeout(() => {
	      var checkbox = option.querySelector('input.' + cbOptions.className);
	      if (checkbox instanceof HTMLInputElement) {
	        UpdateChecked(checkbox, option.classList.contains('selected'));
	      }
	    }, 1);
	  };

	  // add checkbox to option template
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_option = self.settings.render.option;
	    self.settings.render.option = (data, escape_html) => {
	      var rendered = getDom(orig_render_option.call(self, data, escape_html));
	      var checkbox = document.createElement('input');
	      if (cbOptions.className) {
	        checkbox.classList.add(cbOptions.className);
	      }
	      checkbox.addEventListener('click', function (evt) {
	        preventDefault(evt);
	      });
	      checkbox.type = 'checkbox';
	      const hashed = hash_key(data[self.settings.valueField]);
	      UpdateChecked(checkbox, !!(hashed && self.items.indexOf(hashed) > -1));
	      rendered.prepend(checkbox);
	      return rendered;
	    };
	  });

	  // uncheck when item removed
	  self.on('item_remove', value => {
	    var option = self.getOption(value);
	    if (option) {
	      // if dropdown hasn't been opened yet, the option won't exist
	      option.classList.remove('selected'); // selected class won't be removed yet
	      UpdateCheckbox(option);
	    }
	  });

	  // check when item added
	  self.on('item_add', value => {
	    var option = self.getOption(value);
	    if (option) {
	      // if dropdown hasn't been opened yet, the option won't exist
	      UpdateCheckbox(option);
	    }
	  });

	  // remove items when selected option is clicked
	  self.hook('instead', 'onOptionSelect', (evt, option) => {
	    if (option.classList.contains('selected')) {
	      option.classList.remove('selected');
	      self.removeItem(option.dataset.value);
	      self.refreshOptions();
	      preventDefault(evt, true);
	      return;
	    }
	    orig_onOptionSelect.call(self, evt, option);
	    UpdateCheckbox(option);
	  });
	}

	/**
	 * Plugin: "dropdown_header" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function clear_button (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    className: 'clear-button',
	    title: 'Clear All',
	    html: data => {
	      return `<div class="${data.className}" title="${data.title}">&#10799;</div>`;
	    }
	  }, userOptions);
	  self.on('initialize', () => {
	    var button = getDom(options.html(options));
	    button.addEventListener('click', evt => {
	      if (self.isLocked) return;
	      self.clear();
	      if (self.settings.mode === 'single' && self.settings.allowEmptyOption) {
	        self.addItem('');
	      }
	      evt.preventDefault();
	      evt.stopPropagation();
	    });
	    self.control.appendChild(button);
	  });
	}

	/**
	 * Plugin: "drag_drop" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	const insertAfter = (referenceNode, newNode) => {
	  var _referenceNode$parent;
	  (_referenceNode$parent = referenceNode.parentNode) == null || _referenceNode$parent.insertBefore(newNode, referenceNode.nextSibling);
	};
	const insertBefore = (referenceNode, newNode) => {
	  var _referenceNode$parent2;
	  (_referenceNode$parent2 = referenceNode.parentNode) == null || _referenceNode$parent2.insertBefore(newNode, referenceNode);
	};
	const isBefore = (referenceNode, newNode) => {
	  do {
	    var _newNode;
	    newNode = (_newNode = newNode) == null ? void 0 : _newNode.previousElementSibling;
	    if (referenceNode == newNode) {
	      return true;
	    }
	  } while (newNode && newNode.previousElementSibling);
	  return false;
	};
	function drag_drop () {
	  var self = this;
	  if (self.settings.mode !== 'multi') return;
	  var orig_lock = self.lock;
	  var orig_unlock = self.unlock;
	  let sortable = true;
	  let drag_item;

	  /**
	   * Add draggable attribute to item
	   */
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_item = self.settings.render.item;
	    self.settings.render.item = (data, escape) => {
	      const item = getDom(orig_render_item.call(self, data, escape));
	      setAttr(item, {
	        'draggable': 'true'
	      });

	      // prevent doc_mousedown (see tom-select.ts)
	      const mousedown = evt => {
	        if (!sortable) preventDefault(evt);
	        evt.stopPropagation();
	      };
	      const dragStart = evt => {
	        drag_item = item;
	        setTimeout(() => {
	          item.classList.add('ts-dragging');
	        }, 0);
	      };
	      const dragOver = evt => {
	        evt.preventDefault();
	        item.classList.add('ts-drag-over');
	        moveitem(item, drag_item);
	      };
	      const dragLeave = () => {
	        item.classList.remove('ts-drag-over');
	      };
	      const moveitem = (targetitem, dragitem) => {
	        if (dragitem === undefined) return;
	        if (isBefore(dragitem, item)) {
	          insertAfter(targetitem, dragitem);
	        } else {
	          insertBefore(targetitem, dragitem);
	        }
	      };
	      const dragend = () => {
	        var _drag_item;
	        document.querySelectorAll('.ts-drag-over').forEach(el => el.classList.remove('ts-drag-over'));
	        (_drag_item = drag_item) == null || _drag_item.classList.remove('ts-dragging');
	        drag_item = undefined;
	        var values = [];
	        self.control.querySelectorAll(`[data-value]`).forEach(el => {
	          if (el.dataset.value) {
	            let value = el.dataset.value;
	            if (value) {
	              values.push(value);
	            }
	          }
	        });
	        self.setValue(values);
	      };
	      addEvent(item, 'mousedown', mousedown);
	      addEvent(item, 'dragstart', dragStart);
	      addEvent(item, 'dragenter', dragOver);
	      addEvent(item, 'dragover', dragOver);
	      addEvent(item, 'dragleave', dragLeave);
	      addEvent(item, 'dragend', dragend);
	      return item;
	    };
	  });
	  self.hook('instead', 'lock', () => {
	    sortable = false;
	    return orig_lock.call(self);
	  });
	  self.hook('instead', 'unlock', () => {
	    sortable = true;
	    return orig_unlock.call(self);
	  });
	}

	/**
	 * Plugin: "dropdown_header" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function dropdown_header (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    title: 'Untitled',
	    headerClass: 'dropdown-header',
	    titleRowClass: 'dropdown-header-title',
	    labelClass: 'dropdown-header-label',
	    closeClass: 'dropdown-header-close',
	    html: data => {
	      return '<div class="' + data.headerClass + '">' + '<div class="' + data.titleRowClass + '">' + '<span class="' + data.labelClass + '">' + data.title + '</span>' + '<a class="' + data.closeClass + '">&times;</a>' + '</div>' + '</div>';
	    }
	  }, userOptions);
	  self.on('initialize', () => {
	    var header = getDom(options.html(options));
	    var close_link = header.querySelector('.' + options.closeClass);
	    if (close_link) {
	      close_link.addEventListener('click', evt => {
	        preventDefault(evt, true);
	        self.close();
	      });
	    }
	    self.dropdown.insertBefore(header, self.dropdown.firstChild);
	  });
	}

	/**
	 * Plugin: "dropdown_input" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function caret_position () {
	  var self = this;

	  /**
	   * Moves the caret to the specified index.
	   *
	   * The input must be moved by leaving it in place and moving the
	   * siblings, due to the fact that focus cannot be restored once lost
	   * on mobile webkit devices
	   *
	   */
	  self.hook('instead', 'setCaret', new_pos => {
	    if (self.settings.mode === 'single' || !self.control.contains(self.control_input)) {
	      new_pos = self.items.length;
	    } else {
	      new_pos = Math.max(0, Math.min(self.items.length, new_pos));
	      if (new_pos != self.caretPos && !self.isPending) {
	        self.controlChildren().forEach((child, j) => {
	          if (j < new_pos) {
	            self.control_input.insertAdjacentElement('beforebegin', child);
	          } else {
	            self.control.appendChild(child);
	          }
	        });
	      }
	    }
	    self.caretPos = new_pos;
	  });
	  self.hook('instead', 'moveCaret', direction => {
	    if (!self.isFocused) return;

	    // move caret before or after selected items
	    const last_active = self.getLastActive(direction);
	    if (last_active) {
	      const idx = nodeIndex(last_active);
	      self.setCaret(direction > 0 ? idx + 1 : idx);
	      self.setActiveItem();
	      removeClasses(last_active, 'last-active');

	      // move caret left or right of current position
	    } else {
	      self.setCaret(self.caretPos + direction);
	    }
	  });
	}

	/**
	 * Plugin: "dropdown_input" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function dropdown_input () {
	  const self = this;
	  self.settings.shouldOpen = true; // make sure the input is shown even if there are no options to display in the dropdown

	  self.hook('before', 'setup', () => {
	    self.focus_node = self.control;
	    addClasses(self.control_input, 'dropdown-input');
	    const div = getDom('<div class="dropdown-input-wrap">');
	    div.append(self.control_input);
	    self.dropdown.insertBefore(div, self.dropdown.firstChild);

	    // set a placeholder in the select control
	    const placeholder = getDom('<input class="items-placeholder" tabindex="-1" />');
	    placeholder.placeholder = self.settings.placeholder || '';
	    self.control.append(placeholder);
	  });
	  self.on('initialize', () => {
	    // set tabIndex on control to -1, otherwise [shift+tab] will put focus right back on control_input
	    self.control_input.addEventListener('keydown', evt => {
	      //addEvent(self.control_input,'keydown' as const,(evt:KeyboardEvent) =>{
	      switch (evt.keyCode) {
	        case KEY_ESC:
	          if (self.isOpen) {
	            preventDefault(evt, true);
	            self.close();
	          }
	          self.clearActiveItems();
	          return;
	        case KEY_TAB:
	          self.focus_node.tabIndex = -1;
	          break;
	      }
	      return self.onKeyDown.call(self, evt);
	    });
	    self.on('blur', () => {
	      self.focus_node.tabIndex = self.isDisabled ? -1 : self.tabIndex;
	    });

	    // give the control_input focus when the dropdown is open
	    self.on('dropdown_open', () => {
	      self.control_input.focus();
	    });

	    // prevent onBlur from closing when focus is on the control_input
	    const orig_onBlur = self.onBlur;
	    self.hook('instead', 'onBlur', evt => {
	      if (evt && evt.relatedTarget == self.control_input) return;
	      return orig_onBlur.call(self);
	    });
	    addEvent(self.control_input, 'blur', () => self.onBlur());

	    // return focus to control to allow further keyboard input
	    self.hook('before', 'close', () => {
	      if (!self.isOpen) return;
	      self.focus_node.focus({
	        preventScroll: true
	      });
	    });
	  });
	}

	/**
	 * Plugin: "input_autogrow" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function input_autogrow () {
	  var self = this;
	  self.on('initialize', () => {
	    var test_input = document.createElement('span');
	    var control = self.control_input;
	    test_input.style.cssText = 'position:absolute; top:-99999px; left:-99999px; width:auto; padding:0; white-space:pre; ';
	    self.wrapper.appendChild(test_input);
	    var transfer_styles = ['letterSpacing', 'fontSize', 'fontFamily', 'fontWeight', 'textTransform'];
	    for (const style_name of transfer_styles) {
	      // @ts-ignore TS7015 https://stackoverflow.com/a/50506154/697576
	      test_input.style[style_name] = control.style[style_name];
	    }

	    /**
	     * Set the control width
	     *
	     */
	    var resize = () => {
	      test_input.textContent = control.value;
	      control.style.width = test_input.clientWidth + 'px';
	    };
	    resize();
	    self.on('update item_add item_remove', resize);
	    addEvent(control, 'input', resize);
	    addEvent(control, 'keyup', resize);
	    addEvent(control, 'blur', resize);
	    addEvent(control, 'update', resize);
	  });
	}

	/**
	 * Plugin: "input_autogrow" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function no_backspace_delete () {
	  var self = this;
	  var orig_deleteSelection = self.deleteSelection;
	  this.hook('instead', 'deleteSelection', evt => {
	    if (self.activeItems.length) {
	      return orig_deleteSelection.call(self, evt);
	    }
	    return false;
	  });
	}

	/**
	 * Plugin: "no_active_items" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function no_active_items () {
	  this.hook('instead', 'setActiveItem', () => {});
	  this.hook('instead', 'selectAll', () => {});
	}

	/**
	 * Plugin: "optgroup_columns" (Tom Select.js)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function optgroup_columns () {
	  var self = this;
	  var orig_keydown = self.onKeyDown;
	  self.hook('instead', 'onKeyDown', evt => {
	    var index, option, options, optgroup;
	    if (!self.isOpen || !(evt.keyCode === KEY_LEFT || evt.keyCode === KEY_RIGHT)) {
	      return orig_keydown.call(self, evt);
	    }
	    self.ignoreHover = true;
	    optgroup = parentMatch(self.activeOption, '[data-group]');
	    index = nodeIndex(self.activeOption, '[data-selectable]');
	    if (!optgroup) {
	      return;
	    }
	    if (evt.keyCode === KEY_LEFT) {
	      optgroup = optgroup.previousSibling;
	    } else {
	      optgroup = optgroup.nextSibling;
	    }
	    if (!optgroup) {
	      return;
	    }
	    options = optgroup.querySelectorAll('[data-selectable]');
	    option = options[Math.min(options.length - 1, index)];
	    if (option) {
	      self.setActiveOption(option);
	    }
	  });
	}

	/**
	 * Plugin: "remove_button" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function remove_button (userOptions) {
	  const options = Object.assign({
	    label: '&times;',
	    title: 'Remove',
	    className: 'remove',
	    append: true
	  }, userOptions);

	  //options.className = 'remove-single';
	  var self = this;

	  // override the render method to add remove button to each item
	  if (!options.append) {
	    return;
	  }
	  var html = '<a href="javascript:void(0)" class="' + options.className + '" tabindex="-1" title="' + escape_html(options.title) + '">' + options.label + '</a>';
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_item = self.settings.render.item;
	    self.settings.render.item = (data, escape) => {
	      var item = getDom(orig_render_item.call(self, data, escape));
	      var close_button = getDom(html);
	      item.appendChild(close_button);
	      addEvent(close_button, 'mousedown', evt => {
	        preventDefault(evt, true);
	      });
	      addEvent(close_button, 'click', evt => {
	        if (self.isLocked) return;

	        // propagating will trigger the dropdown to show for single mode
	        preventDefault(evt, true);
	        if (self.isLocked) return;
	        if (!self.shouldDelete([item], evt)) return;
	        self.removeItem(item);
	        self.refreshOptions(false);
	        self.inputState();
	      });
	      return item;
	    };
	  });
	}

	/**
	 * Plugin: "restore_on_backspace" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function restore_on_backspace (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    text: option => {
	      return option[self.settings.labelField];
	    }
	  }, userOptions);
	  self.on('item_remove', function (value) {
	    if (!self.isFocused) {
	      return;
	    }
	    if (self.control_input.value.trim() === '') {
	      var option = self.options[value];
	      if (option) {
	        self.setTextboxValue(options.text.call(self, option));
	      }
	    }
	  });
	}

	/**
	 * Plugin: "restore_on_backspace" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function virtual_scroll () {
	  const self = this;
	  const orig_canLoad = self.canLoad;
	  const orig_clearActiveOption = self.clearActiveOption;
	  const orig_loadCallback = self.loadCallback;
	  var pagination = {};
	  var dropdown_content;
	  var loading_more = false;
	  var load_more_opt;
	  var default_values = [];
	  if (!self.settings.shouldLoadMore) {
	    // return true if additional results should be loaded
	    self.settings.shouldLoadMore = () => {
	      const scroll_percent = dropdown_content.clientHeight / (dropdown_content.scrollHeight - dropdown_content.scrollTop);
	      if (scroll_percent > 0.9) {
	        return true;
	      }
	      if (self.activeOption) {
	        var selectable = self.selectable();
	        var index = Array.from(selectable).indexOf(self.activeOption);
	        if (index >= selectable.length - 2) {
	          return true;
	        }
	      }
	      return false;
	    };
	  }
	  if (!self.settings.firstUrl) {
	    throw 'virtual_scroll plugin requires a firstUrl() method';
	  }

	  // in order for virtual scrolling to work,
	  // options need to be ordered the same way they're returned from the remote data source
	  self.settings.sortField = [{
	    field: '$order'
	  }, {
	    field: '$score'
	  }];

	  // can we load more results for given query?
	  const canLoadMore = query => {
	    if (typeof self.settings.maxOptions === 'number' && dropdown_content.children.length >= self.settings.maxOptions) {
	      return false;
	    }
	    if (query in pagination && pagination[query]) {
	      return true;
	    }
	    return false;
	  };
	  const clearFilter = (option, value) => {
	    if (self.items.indexOf(value) >= 0 || default_values.indexOf(value) >= 0) {
	      return true;
	    }
	    return false;
	  };

	  // set the next url that will be
	  self.setNextUrl = (value, next_url) => {
	    pagination[value] = next_url;
	  };

	  // getUrl() to be used in settings.load()
	  self.getUrl = query => {
	    if (query in pagination) {
	      const next_url = pagination[query];
	      pagination[query] = false;
	      return next_url;
	    }

	    // if the user goes back to a previous query
	    // we need to load the first page again
	    self.clearPagination();
	    return self.settings.firstUrl.call(self, query);
	  };

	  // clear pagination
	  self.clearPagination = () => {
	    pagination = {};
	  };

	  // don't clear the active option (and cause unwanted dropdown scroll)
	  // while loading more results
	  self.hook('instead', 'clearActiveOption', () => {
	    if (loading_more) {
	      return;
	    }
	    return orig_clearActiveOption.call(self);
	  });

	  // override the canLoad method
	  self.hook('instead', 'canLoad', query => {
	    // first time the query has been seen
	    if (!(query in pagination)) {
	      return orig_canLoad.call(self, query);
	    }
	    return canLoadMore(query);
	  });

	  // wrap the load
	  self.hook('instead', 'loadCallback', (options, optgroups) => {
	    if (!loading_more) {
	      self.clearOptions(clearFilter);
	    } else if (load_more_opt) {
	      const first_option = options[0];
	      if (first_option !== undefined) {
	        load_more_opt.dataset.value = first_option[self.settings.valueField];
	      }
	    }
	    orig_loadCallback.call(self, options, optgroups);
	    loading_more = false;
	  });

	  // add templates to dropdown
	  //	loading_more if we have another url in the queue
	  //	no_more_results if we don't have another url in the queue
	  self.hook('after', 'refreshOptions', () => {
	    const query = self.lastValue;
	    var option;
	    if (canLoadMore(query)) {
	      option = self.render('loading_more', {
	        query: query
	      });
	      if (option) {
	        option.setAttribute('data-selectable', ''); // so that navigating dropdown with [down] keypresses can navigate to this node
	        load_more_opt = option;
	      }
	    } else if (query in pagination && !dropdown_content.querySelector('.no-results')) {
	      option = self.render('no_more_results', {
	        query: query
	      });
	    }
	    if (option) {
	      addClasses(option, self.settings.optionClass);
	      dropdown_content.append(option);
	    }
	  });

	  // add scroll listener and default templates
	  self.on('initialize', () => {
	    default_values = Object.keys(self.options);
	    dropdown_content = self.dropdown_content;

	    // default templates
	    self.settings.render = Object.assign({}, {
	      loading_more: () => {
	        return `<div class="loading-more-results">Loading more results ... </div>`;
	      },
	      no_more_results: () => {
	        return `<div class="no-more-results">No more results</div>`;
	      }
	    }, self.settings.render);

	    // watch dropdown content scroll position
	    dropdown_content.addEventListener('scroll', () => {
	      if (!self.settings.shouldLoadMore.call(self)) {
	        return;
	      }

	      // !important: this will get checked again in load() but we still need to check here otherwise loading_more will be set to true
	      if (!canLoadMore(self.lastValue)) {
	        return;
	      }

	      // don't call load() too much
	      if (loading_more) return;
	      loading_more = true;
	      self.load.call(self, self.lastValue);
	    });
	  });
	}

	TomSelect.define('change_listener', change_listener);
	TomSelect.define('checkbox_options', checkbox_options);
	TomSelect.define('clear_button', clear_button);
	TomSelect.define('drag_drop', drag_drop);
	TomSelect.define('dropdown_header', dropdown_header);
	TomSelect.define('caret_position', caret_position);
	TomSelect.define('dropdown_input', dropdown_input);
	TomSelect.define('input_autogrow', input_autogrow);
	TomSelect.define('no_backspace_delete', no_backspace_delete);
	TomSelect.define('no_active_items', no_active_items);
	TomSelect.define('optgroup_columns', optgroup_columns);
	TomSelect.define('remove_button', remove_button);
	TomSelect.define('restore_on_backspace', restore_on_backspace);
	TomSelect.define('virtual_scroll', virtual_scroll);

	return TomSelect;

}));
var tomSelect=function(el,opts){return new TomSelect(el,opts);} 
//# sourceMappingURL=tom-select.complete.js.map


/***/ }),

/***/ "./resources/assets/components/richtext-editor.js":
/*!********************************************************!*\
  !*** ./resources/assets/components/richtext-editor.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   RichTextEditor: () => (/* binding */ RichTextEditor)
/* harmony export */ });
/* harmony import */ var _containers_base_class_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../containers/base-class.js */ "./resources/assets/containers/base-class.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }
function _classPrivateMethodInitSpec(e, a) { _checkPrivateRedeclaration(e, a), a.add(e); }
function _checkPrivateRedeclaration(e, t) { if (t.has(e)) throw new TypeError("Cannot initialize the same private elements twice on an object"); }
function _assertClassBrand(e, t, n) { if ("function" == typeof e ? e === t : e.has(t)) return arguments.length < 3 ? t : n; throw new TypeError("Private element is not present on this object"); }


//
//  moved to meta tags in src/MicroweberPackages/MetaTags/Entities/AdminFilamentJsLibsScriptTag.php
//  import tinymce from "tinymce";
// import 'tinymce/themes/silver/theme'
//
//
// import 'tinymce/models/dom';
// import 'tinymce/icons/default/icons';
//
// import 'tinymce/plugins/autoresize/plugin';

// moved to userfiles/modules/microweber/api/libs/tinymce/plugins/mwlink/plugin.min.js
// const MWLink = editor => {
//
//
//     editor.ui.registry.addButton('mwLink', {
//         icon: '<svg viewBox="0 0 24 24"> <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" /></svg>',
//         icon: 'link',
//         onAction: (_) => {
//
//             var linkEditor = new mw.LinkEditor({
//                 mode: 'dialog',
//                 hideTextFied: true
//             });
//
//
//             linkEditor.promise().then(function (data) {
//                 var modal = linkEditor.dialog;
//                 if (data) {
//
//                     editor.execCommand('CreateLink', false, data.url);
//                     modal.remove();
//                 } else {
//                     modal.remove();
//                 }
//             });
//         }
//     });
//
//
// }
var _RichTextEditor_brand = /*#__PURE__*/new WeakSet();
var RichTextEditor = /*#__PURE__*/function (_BaseComponent) {
  function RichTextEditor() {
    var _this;
    var _options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    _classCallCheck(this, RichTextEditor);
    _this = _callSuper(this, RichTextEditor);
    _classPrivateMethodInitSpec(_this, _RichTextEditor_brand);
    _assertClassBrand(_RichTextEditor_brand, _this, _config).call(_this, _options);

    // moved to plugins/mwlink/plugin.min.js
    //
    // this.on('setup', editor => {
    //     MWLink(editor);
    // });

    _this.init();
    return _this;
  }
  _inherits(RichTextEditor, _BaseComponent);
  return _createClass(RichTextEditor, [{
    key: "init",
    value: function init() {
      tinymce.init(this.settings);
    }
  }]);
}(_containers_base_class_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
function _config(options) {
  if (typeof options.target === 'string') {
    options.selector = options.target;
  }
  if (_typeof(options.selector) === 'object') {
    options.target = options.selector;
  }
  this.settings = Object.assign({}, _assertClassBrand(_RichTextEditor_brand, this, _defaultOptions).call(this), options);
}
function _defaultOptions() {
  var _this2 = this;
  return {
    base_url: mw.settings.libs_url + 'tinymce/',
    document_base_url: mw.settings.site_url,
    relative_urls: false,
    remove_script_host: false,
    cache_suffix: '?v=1',
    target: null,
    inline: false,
    promotion: false,
    element_format: 'xhtml',
    extended_valid_elements: 'div[*],module[*]',
    statusbar: false,
    menubar: 'edit insert view format table tools',
    noneditable_class: 'module',
    /* plugins: [
         'noneditable',
         'advlist', 'lists', 'mwLink', 'image', 'charmap', 'preview',
         'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
         'media', 'table', 'help', 'wordcount'
     ],*/
    plugins: ["advlist", "autolink", "lists", "mwlink", "link", "image", "charmap", "preview", "anchor", "searchreplace", "visualblocks", "code", "insertdatetime", "media", "table"],
    toolbar: 'undo redo | blocks | ' + 'bold italic forecolor backcolor | mwlink unlink  | alignleft aligncenter ' + 'alignright alignjustify | fontfamily fontsizeinput | bullist numlist outdent indent | ' + 'removeformat ',
    init_instance_callback: function init_instance_callback(editor) {
      editor.on('Change Undo Redo', function (e) {
        _this2.dispatch('change', tinymce.activeEditor.getContent());
        // this.registerChange(tinymce.activeEditor.getContent())
      });
    },
    setup: function setup(editor) {
      _this2.dispatch("setup", editor);
    }
  };
}

/***/ }),

/***/ "./resources/assets/containers/base-class.js":
/*!***************************************************!*\
  !*** ./resources/assets/containers/base-class.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classPrivateFieldInitSpec(e, t, a) { _checkPrivateRedeclaration(e, t), t.set(e, a); }
function _checkPrivateRedeclaration(e, t) { if (t.has(e)) throw new TypeError("Cannot initialize the same private elements twice on an object"); }
function _classPrivateFieldGet(s, a) { return s.get(_assertClassBrand(s, a)); }
function _assertClassBrand(e, t, n) { if ("function" == typeof e ? e === t : e.has(t)) return arguments.length < 3 ? t : n; throw new TypeError("Private element is not present on this object"); }
var _events = /*#__PURE__*/new WeakMap();
var _styles = /*#__PURE__*/new WeakMap();
var MicroweberBaseClass = /*#__PURE__*/function () {
  function MicroweberBaseClass() {
    _classCallCheck(this, MicroweberBaseClass);
    _classPrivateFieldInitSpec(this, _events, {});
    _classPrivateFieldInitSpec(this, _styles, {});
  }
  return _createClass(MicroweberBaseClass, [{
    key: "on",
    value: function on(e, f) {
      _classPrivateFieldGet(_events, this)[e] ? _classPrivateFieldGet(_events, this)[e].push(f) : _classPrivateFieldGet(_events, this)[e] = [f];
      return this;
    }
  }, {
    key: "off",
    value: function off(e, f) {
      if (!_classPrivateFieldGet(_events, this)[e]) {
        return this;
      }
      if (typeof f === 'function') {
        var index = _classPrivateFieldGet(_events, this)[e].indexOf(f);
        if (index === -1) {
          return this;
        }
        _classPrivateFieldGet(_events, this)[e].splice(index, 1);
      } else {
        _classPrivateFieldGet(_events, this)[e] = [];
      }
      return this;
    }
  }, {
    key: "dispatch",
    value: function dispatch(e, f, f2) {
      _classPrivateFieldGet(_events, this)[e] ? _classPrivateFieldGet(_events, this)[e].forEach(function (c) {
        c.call(this, f);
      }) : '';
      return this;
    }
  }, {
    key: "emit",
    value: function emit(e, f) {
      return this.dispatch(e, f);
    }
  }, {
    key: "css",
    value: function css(id, styles, forced) {
      if (!_classPrivateFieldGet(_styles, this)[id] || forced) {
        _classPrivateFieldGet(_styles, this)[id] = true;
        var style = document.createElement('style');
        style.appendChild(document.createTextNode(styles));
        document.head.appendChild(style);
      }
    }
  }]);
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MicroweberBaseClass);

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/create fake namespace object */
/******/ 	(() => {
/******/ 		var getProto = Object.getPrototypeOf ? (obj) => (Object.getPrototypeOf(obj)) : (obj) => (obj.__proto__);
/******/ 		var leafPrototypes;
/******/ 		// create a fake namespace object
/******/ 		// mode & 1: value is a module id, require it
/******/ 		// mode & 2: merge all properties of value into the ns
/******/ 		// mode & 4: return value when already ns object
/******/ 		// mode & 16: return value when it's Promise-like
/******/ 		// mode & 8|1: behave like require
/******/ 		__webpack_require__.t = function(value, mode) {
/******/ 			if(mode & 1) value = this(value);
/******/ 			if(mode & 8) return value;
/******/ 			if(typeof value === 'object' && value) {
/******/ 				if((mode & 4) && value.__esModule) return value;
/******/ 				if((mode & 16) && typeof value.then === 'function') return value;
/******/ 			}
/******/ 			var ns = Object.create(null);
/******/ 			__webpack_require__.r(ns);
/******/ 			var def = {};
/******/ 			leafPrototypes = leafPrototypes || [null, getProto({}), getProto([]), getProto(getProto)];
/******/ 			for(var current = mode & 2 && value; typeof current == 'object' && !~leafPrototypes.indexOf(current); current = getProto(current)) {
/******/ 				Object.getOwnPropertyNames(current).forEach((key) => (def[key] = () => (value[key])));
/******/ 			}
/******/ 			def['default'] = () => (value);
/******/ 			__webpack_require__.d(ns, def);
/******/ 			return ns;
/******/ 		};
/******/ 	})();
/******/ 	
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
/*!****************************************************!*\
  !*** ./resources/assets/js/admin-filament-libs.js ***!
  \****************************************************/
var a_color_picker__WEBPACK_IMPORTED_MODULE_3___namespace_cache;
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _frontend_assets_libs_resources_dist_xss_xss_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../frontend-assets-libs/resources/dist/xss/xss.js */ "../frontend-assets-libs/resources/dist/xss/xss.js");
/* harmony import */ var tom_select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tom-select */ "./node_modules/tom-select/dist/js/tom-select.complete.js");
/* harmony import */ var _components_richtext_editor_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/richtext-editor.js */ "./resources/assets/components/richtext-editor.js");
/* harmony import */ var a_color_picker__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! a-color-picker */ "./node_modules/a-color-picker/dist/acolorpicker.js");
//libs


// import "jquery";

//import jQuery from 'jquery';
//const jQuery  = (await import("jquery/dist/jquery.js")).default;

//import "../core/mw-require.js";

window.$ = jQuery;
window.jQuery = jQuery;
globalThis.$ = jQuery;
globalThis.jQuery = jQuery;

//await import("jquery-ui/dist/jquery-ui.js");




//
globalThis.TomSelect = tom_select__WEBPACK_IMPORTED_MODULE_1__;
window.TomSelect = tom_select__WEBPACK_IMPORTED_MODULE_1__;

window.AColorPicker = /*#__PURE__*/ (a_color_picker__WEBPACK_IMPORTED_MODULE_3___namespace_cache || (a_color_picker__WEBPACK_IMPORTED_MODULE_3___namespace_cache = __webpack_require__.t(a_color_picker__WEBPACK_IMPORTED_MODULE_3__, 2)));
mw.richTextEditor = function (options) {
  return new _components_richtext_editor_js__WEBPACK_IMPORTED_MODULE_2__.RichTextEditor(options);
};

// import "jquery-ui/dist/jquery-ui.js";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]').content || '').trim()
  }
});
})();

/******/ })()
;