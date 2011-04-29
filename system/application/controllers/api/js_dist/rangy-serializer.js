/*
 Serializer module for Rangy.
 Serializes Ranges and Selections. An example use would be to store a user's selection on a particular page in a
 cookie or local storage and restore it on the user's next visit to the same page.

 Part of Rangy, a cross-browser JavaScript range and selection library
 http://code.google.com/p/rangy/

 Depends on Rangy core.

 Copyright 2011, Tim Down
 Licensed under the MIT license.
 Version: 1.1
 Build date: 8 April 2011
*/
rangy.createModule("Serializer",function(h,n){function o(c,b){b=b||[];var a=c.nodeType,e=c.childNodes,d=e.length,f=[a,c.nodeName,d].join(":"),g="",k="";switch(a){case 3:g=c.nodeValue.replace(/</g,"&lt;").replace(/>/g,"&gt;");break;case 8:g="<!--"+c.nodeValue.replace(/</g,"&lt;").replace(/>/g,"&gt;")+"--\>";break;default:g="<"+f+">";k="</>";break}g&&b.push(g);for(a=0;a<d;++a)o(e[a],b);k&&b.push(k);return b}function j(c){c=o(c).join("");return u(c).toString(16)}function l(c,b,a){var e=[],d=c;for(a=
a||i.getDocument(c).documentElement;d&&d!=a;){e.push(i.getNodeIndex(d,true));d=d.parentNode}return e.join("/")+":"+b}function m(c,b,a){if(b)a||i.getDocument(b);else{a=a||document;b=a.documentElement}c=c.split(":");b=b;a=c[0]?c[0].split("/"):[];for(var e=a.length,d;e--;){d=parseInt(a[e],10);if(d<b.childNodes.length)b=b.childNodes[parseInt(a[e],10)];else throw n.createError("deserializePosition failed: node "+i.inspectNode(b)+" has no child with index "+d+", "+e);}return new i.DomPosition(b,parseInt(c[1],
10))}function p(c,b,a){a=a||h.DomRange.getRangeDocument(c).documentElement;if(!i.isAncestorOf(a,c.commonAncestorContainer,true))throw Error("serializeRange: range is not wholly contained within specified root node");c=l(c.startContainer,c.startOffset,a)+","+l(c.endContainer,c.endOffset,a);b||(c+="{"+j(a)+"}");return c}function q(c,b,a){if(b)a=a||i.getDocument(b);else{a=a||document;b=a.documentElement}var e=/^([^,]+),([^,]+)({([^}]+)})?$/.exec(c);if((c=e[3])&&c!==j(b))throw Error("deserializeRange: checksums of serialized range root node and target root node do not match");
c=m(e[1],b,a);b=m(e[2],b,a);a=h.createRange(a);a.setStart(c.node,c.offset);a.setEnd(b.node,b.offset);return a}function r(c,b,a){if(b)a||i.getDocument(b);else{a=a||document;b=a.documentElement}c=/^([^,]+),([^,]+)({([^}]+)})?$/.exec(c)[3];return!c||c===j(b)}function s(c,b,a){c=c||rangy.getSelection();c=c.getAllRanges();for(var e=[],d=0,f=c.length;d<f;++d)e[d]=p(c[d],b,a);return e.join("|")}function t(c,b,a){if(b)a=a||i.getWindow(b);else{a=a||window;b=a.document.documentElement}c=c.split("|");for(var e=
h.getSelection(a),d=[],f=0,g=c.length;f<g;++f)d[f]=q(c[f],b,a.document);e.setRanges(d);return e}h.requireModules(["WrappedSelection","WrappedRange"]);if(typeof encodeURIComponent=="undefined"||typeof decodeURIComponent=="undefined")n.fail("Global object is missing encodeURIComponent and/or decodeURIComponent method");var u=function(){var c=null;return function(b){for(var a=[],e=0,d=b.length,f;e<d;++e){f=b.charCodeAt(e);if(f<128)a.push(f);else f<2048?a.push(f>>6|192,f&63|128):a.push(f>>12|224,f>>6&
63|128,f&63|128)}b=-1;if(!c){e=[];d=0;for(var g;d<256;++d){g=d;for(f=8;f--;)if((g&1)==1)g=g>>>1^3988292384;else g>>>=1;e[d]=g>>>0}c=e}e=c;d=0;for(f=a.length;d<f;++d){g=(b^a[d])&255;b=b>>>8^e[g]}return(b^-1)>>>0}}(),i=h.dom;h.serializePosition=l;h.deserializePosition=m;h.serializeRange=p;h.deserializeRange=q;h.canDeserializeRange=r;h.serializeSelection=s;h.deserializeSelection=t;h.canDeserializeSelection=function(c,b,a){var e;if(b)e=a?a.document:i.getDocument(b);else{a=a||window;b=a.document.documentElement}c=
c.split("|");a=0;for(var d=c.length;a<d;++a)if(!r(c[a],b,e))return false;return true};h.restoreSelectionFromCookie=function(c){c=c||window;var b;a:{b=c.document.cookie.split(/[;,]/);for(var a=0,e=b.length,d;a<e;++a){d=b[a].split("=");if(d[0].replace(/^\s+/,"")=="rangySerializedSelection")if(d=d[1]){b=decodeURIComponent(d.replace(/\s+$/,""));break a}}b=null}b&&t(b,c.doc)};h.saveSelectionCookie=function(c,b){c=c||window;b=typeof b=="object"?b:{};var a=b.expires?";expires="+b.expires.toUTCString():"",
e=b.path?";path="+b.path:"",d=b.domain?";domain="+b.domain:"",f=b.secure?";secure":"",g=s(rangy.getSelection(c));c.document.cookie=encodeURIComponent("rangySerializedSelection")+"="+encodeURIComponent(g)+a+e+d+f};h.getElementChecksum=j});