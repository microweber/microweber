/******/ (() => { // webpackBootstrap
(() => {
/*!*******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/libs/rangy/rangy-core.js ***!
  \*******************************************************************/
/*
 Rangy, a cross-browser JavaScript range and selection library
 http://code.google.com/p/rangy/

 Copyright 2012, Tim Down
 Licensed under the MIT license.
 Version: 1.2.3
 Build date: 26 February 2012
*/
window.rangy=function(){function l(p,u){var w=typeof p[u];return w=="function"||!!(w=="object"&&p[u])||w=="unknown"}function K(p,u){return!!(typeof p[u]=="object"&&p[u])}function H(p,u){return typeof p[u]!="undefined"}function I(p){return function(u,w){for(var B=w.length;B--;)if(!p(u,w[B]))return false;return true}}function z(p){return p&&A(p,x)&&v(p,t)}function C(p){window.alert("Rangy not supported in your browser. Reason: "+p);c.initialized=true;c.supported=false}function N(){if(!c.initialized){var p,
u=false,w=false;if(l(document,"createRange")){p=document.createRange();if(A(p,n)&&v(p,i))u=true;p.detach()}if((p=K(document,"body")?document.body:document.getElementsByTagName("body")[0])&&l(p,"createTextRange")){p=p.createTextRange();if(z(p))w=true}!u&&!w&&C("Neither Range nor TextRange are implemented");c.initialized=true;c.features={implementsDomRange:u,implementsTextRange:w};u=k.concat(f);w=0;for(p=u.length;w<p;++w)try{u[w](c)}catch(B){K(window,"console")&&l(window.console,"log")&&window.console.log("Init listener threw an exception. Continuing.",
B)}}}function O(p){this.name=p;this.supported=this.initialized=false}var i=["startContainer","startOffset","endContainer","endOffset","collapsed","commonAncestorContainer","START_TO_START","START_TO_END","END_TO_START","END_TO_END"],n=["setStart","setStartBefore","setStartAfter","setEnd","setEndBefore","setEndAfter","collapse","selectNode","selectNodeContents","compareBoundaryPoints","deleteContents","extractContents","cloneContents","insertNode","surroundContents","cloneRange","toString","detach"],
t=["boundingHeight","boundingLeft","boundingTop","boundingWidth","htmlText","text"],x=["collapse","compareEndPoints","duplicate","getBookmark","moveToBookmark","moveToElementText","parentElement","pasteHTML","select","setEndPoint","getBoundingClientRect"],A=I(l),q=I(K),v=I(H),c={version:"1.2.3",initialized:false,supported:true,util:{isHostMethod:l,isHostObject:K,isHostProperty:H,areHostMethods:A,areHostObjects:q,areHostProperties:v,isTextRange:z},features:{},modules:{},config:{alertOnWarn:false,preferTextRange:false}};
c.fail=C;c.warn=function(p){p="Rangy warning: "+p;if(c.config.alertOnWarn)window.alert(p);else typeof window.console!="undefined"&&typeof window.console.log!="undefined"&&window.console.log(p)};if({}.hasOwnProperty)c.util.extend=function(p,u){for(var w in u)if(u.hasOwnProperty(w))p[w]=u[w]};else C("hasOwnProperty not supported");var f=[],k=[];c.init=N;c.addInitListener=function(p){c.initialized?p(c):f.push(p)};var r=[];c.addCreateMissingNativeApiListener=function(p){r.push(p)};c.createMissingNativeApi=
function(p){p=p||window;N();for(var u=0,w=r.length;u<w;++u)r[u](p)};O.prototype.fail=function(p){this.initialized=true;this.supported=false;throw Error("Module '"+this.name+"' failed to load: "+p);};O.prototype.warn=function(p){c.warn("Module "+this.name+": "+p)};O.prototype.createError=function(p){return Error("Error in Rangy "+this.name+" module: "+p)};c.createModule=function(p,u){var w=new O(p);c.modules[p]=w;k.push(function(B){u(B,w);w.initialized=true;w.supported=true})};c.requireModules=function(p){for(var u=
0,w=p.length,B,V;u<w;++u){V=p[u];B=c.modules[V];if(!B||!(B instanceof O))throw Error("Module '"+V+"' not found");if(!B.supported)throw Error("Module '"+V+"' not supported");}};var L=false;q=function(){if(!L){L=true;c.initialized||N()}};if(typeof window=="undefined")C("No window found");else if(typeof document=="undefined")C("No document found");else{l(document,"addEventListener")&&document.addEventListener("DOMContentLoaded",q,false);if(l(window,"addEventListener"))window.addEventListener("load",
q,false);else l(window,"attachEvent")?window.attachEvent("onload",q):C("Window does not have required addEventListener or attachEvent method");return c}}();
rangy.createModule("DomUtil",function(l,K){function H(c){for(var f=0;c=c.previousSibling;)f++;return f}function I(c,f){var k=[],r;for(r=c;r;r=r.parentNode)k.push(r);for(r=f;r;r=r.parentNode)if(v(k,r))return r;return null}function z(c,f,k){for(k=k?c:c.parentNode;k;){c=k.parentNode;if(c===f)return k;k=c}return null}function C(c){c=c.nodeType;return c==3||c==4||c==8}function N(c,f){var k=f.nextSibling,r=f.parentNode;k?r.insertBefore(c,k):r.appendChild(c);return c}function O(c){if(c.nodeType==9)return c;
else if(typeof c.ownerDocument!="undefined")return c.ownerDocument;else if(typeof c.document!="undefined")return c.document;else if(c.parentNode)return O(c.parentNode);else throw Error("getDocument: no document found for node");}function i(c){if(!c)return"[No node]";return C(c)?'"'+c.data+'"':c.nodeType==1?"<"+c.nodeName+(c.id?' id="'+c.id+'"':"")+">["+c.childNodes.length+"]":c.nodeName}function n(c){this._next=this.root=c}function t(c,f){this.node=c;this.offset=f}function x(c){this.code=this[c];
this.codeName=c;this.message="DOMException: "+this.codeName}var A=l.util;A.areHostMethods(document,["createDocumentFragment","createElement","createTextNode"])||K.fail("document missing a Node creation method");A.isHostMethod(document,"getElementsByTagName")||K.fail("document missing getElementsByTagName method");var q=document.createElement("div");A.areHostMethods(q,["insertBefore","appendChild","cloneNode"])||K.fail("Incomplete Element implementation");A.isHostProperty(q,"innerHTML")||K.fail("Element is missing innerHTML property");
q=document.createTextNode("test");A.areHostMethods(q,["splitText","deleteData","insertData","appendData","cloneNode"])||K.fail("Incomplete Text Node implementation");var v=function(c,f){for(var k=c.length;k--;)if(c[k]===f)return true;return false};n.prototype={_current:null,hasNext:function(){return!!this._next},next:function(){var c=this._current=this._next,f;if(this._current)if(f=c.firstChild)this._next=f;else{for(f=null;c!==this.root&&!(f=c.nextSibling);)c=c.parentNode;this._next=f}return this._current},
detach:function(){this._current=this._next=this.root=null}};t.prototype={equals:function(c){return this.node===c.node&this.offset==c.offset},inspect:function(){return"[DomPosition("+i(this.node)+":"+this.offset+")]"}};x.prototype={INDEX_SIZE_ERR:1,HIERARCHY_REQUEST_ERR:3,WRONG_DOCUMENT_ERR:4,NO_MODIFICATION_ALLOWED_ERR:7,NOT_FOUND_ERR:8,NOT_SUPPORTED_ERR:9,INVALID_STATE_ERR:11};x.prototype.toString=function(){return this.message};l.dom={arrayContains:v,isHtmlNamespace:function(c){var f;return typeof c.namespaceURI==
"undefined"||(f=c.namespaceURI)===null||f=="http://www.w3.org/1999/xhtml"},parentElement:function(c){c=c.parentNode;return c.nodeType==1?c:null},getNodeIndex:H,getNodeLength:function(c){var f;return C(c)?c.length:(f=c.childNodes)?f.length:0},getCommonAncestor:I,isAncestorOf:function(c,f,k){for(f=k?f:f.parentNode;f;)if(f===c)return true;else f=f.parentNode;return false},getClosestAncestorIn:z,isCharacterDataNode:C,insertAfter:N,splitDataNode:function(c,f){var k=c.cloneNode(false);k.deleteData(0,f);
c.deleteData(f,c.length-f);N(k,c);return k},getDocument:O,getWindow:function(c){c=O(c);if(typeof c.defaultView!="undefined")return c.defaultView;else if(typeof c.parentWindow!="undefined")return c.parentWindow;else throw Error("Cannot get a window object for node");},getIframeWindow:function(c){if(typeof c.contentWindow!="undefined")return c.contentWindow;else if(typeof c.contentDocument!="undefined")return c.contentDocument.defaultView;else throw Error("getIframeWindow: No Window object found for iframe element");
},getIframeDocument:function(c){if(typeof c.contentDocument!="undefined")return c.contentDocument;else if(typeof c.contentWindow!="undefined")return c.contentWindow.document;else throw Error("getIframeWindow: No Document object found for iframe element");},getBody:function(c){return A.isHostObject(c,"body")?c.body:c.getElementsByTagName("body")[0]},getRootContainer:function(c){for(var f;f=c.parentNode;)c=f;return c},comparePoints:function(c,f,k,r){var L;if(c==k)return f===r?0:f<r?-1:1;else if(L=z(k,
c,true))return f<=H(L)?-1:1;else if(L=z(c,k,true))return H(L)<r?-1:1;else{f=I(c,k);c=c===f?f:z(c,f,true);k=k===f?f:z(k,f,true);if(c===k)throw Error("comparePoints got to case 4 and childA and childB are the same!");else{for(f=f.firstChild;f;){if(f===c)return-1;else if(f===k)return 1;f=f.nextSibling}throw Error("Should not be here!");}}},inspectNode:i,fragmentFromNodeChildren:function(c){for(var f=O(c).createDocumentFragment(),k;k=c.firstChild;)f.appendChild(k);return f},createIterator:function(c){return new n(c)},
DomPosition:t};l.DOMException=x});
rangy.createModule("DomRange",function(l){function K(a,e){return a.nodeType!=3&&(g.isAncestorOf(a,e.startContainer,true)||g.isAncestorOf(a,e.endContainer,true))}function H(a){return g.getDocument(a.startContainer)}function I(a,e,j){if(e=a._listeners[e])for(var o=0,E=e.length;o<E;++o)e[o].call(a,{target:a,args:j})}function z(a){return new Z(a.parentNode,g.getNodeIndex(a))}function C(a){return new Z(a.parentNode,g.getNodeIndex(a)+1)}function N(a,e,j){var o=a.nodeType==11?a.firstChild:a;if(g.isCharacterDataNode(e))j==
e.length?g.insertAfter(a,e):e.parentNode.insertBefore(a,j==0?e:g.splitDataNode(e,j));else j>=e.childNodes.length?e.appendChild(a):e.insertBefore(a,e.childNodes[j]);return o}function O(a){for(var e,j,o=H(a.range).createDocumentFragment();j=a.next();){e=a.isPartiallySelectedSubtree();j=j.cloneNode(!e);if(e){e=a.getSubtreeIterator();j.appendChild(O(e));e.detach(true)}if(j.nodeType==10)throw new S("HIERARCHY_REQUEST_ERR");o.appendChild(j)}return o}function i(a,e,j){var o,E;for(j=j||{stop:false};o=a.next();)if(a.isPartiallySelectedSubtree())if(e(o)===
false){j.stop=true;return}else{o=a.getSubtreeIterator();i(o,e,j);o.detach(true);if(j.stop)return}else for(o=g.createIterator(o);E=o.next();)if(e(E)===false){j.stop=true;return}}function n(a){for(var e;a.next();)if(a.isPartiallySelectedSubtree()){e=a.getSubtreeIterator();n(e);e.detach(true)}else a.remove()}function t(a){for(var e,j=H(a.range).createDocumentFragment(),o;e=a.next();){if(a.isPartiallySelectedSubtree()){e=e.cloneNode(false);o=a.getSubtreeIterator();e.appendChild(t(o));o.detach(true)}else a.remove();
if(e.nodeType==10)throw new S("HIERARCHY_REQUEST_ERR");j.appendChild(e)}return j}function x(a,e,j){var o=!!(e&&e.length),E,T=!!j;if(o)E=RegExp("^("+e.join("|")+")$");var m=[];i(new q(a,false),function(s){if((!o||E.test(s.nodeType))&&(!T||j(s)))m.push(s)});return m}function A(a){return"["+(typeof a.getName=="undefined"?"Range":a.getName())+"("+g.inspectNode(a.startContainer)+":"+a.startOffset+", "+g.inspectNode(a.endContainer)+":"+a.endOffset+")]"}function q(a,e){this.range=a;this.clonePartiallySelectedTextNodes=
e;if(!a.collapsed){this.sc=a.startContainer;this.so=a.startOffset;this.ec=a.endContainer;this.eo=a.endOffset;var j=a.commonAncestorContainer;if(this.sc===this.ec&&g.isCharacterDataNode(this.sc)){this.isSingleCharacterDataNode=true;this._first=this._last=this._next=this.sc}else{this._first=this._next=this.sc===j&&!g.isCharacterDataNode(this.sc)?this.sc.childNodes[this.so]:g.getClosestAncestorIn(this.sc,j,true);this._last=this.ec===j&&!g.isCharacterDataNode(this.ec)?this.ec.childNodes[this.eo-1]:g.getClosestAncestorIn(this.ec,
j,true)}}}function v(a){this.code=this[a];this.codeName=a;this.message="RangeException: "+this.codeName}function c(a,e,j){this.nodes=x(a,e,j);this._next=this.nodes[0];this._position=0}function f(a){return function(e,j){for(var o,E=j?e:e.parentNode;E;){o=E.nodeType;if(g.arrayContains(a,o))return E;E=E.parentNode}return null}}function k(a,e){if(G(a,e))throw new v("INVALID_NODE_TYPE_ERR");}function r(a){if(!a.startContainer)throw new S("INVALID_STATE_ERR");}function L(a,e){if(!g.arrayContains(e,a.nodeType))throw new v("INVALID_NODE_TYPE_ERR");
}function p(a,e){if(e<0||e>(g.isCharacterDataNode(a)?a.length:a.childNodes.length))throw new S("INDEX_SIZE_ERR");}function u(a,e){if(h(a,true)!==h(e,true))throw new S("WRONG_DOCUMENT_ERR");}function w(a){if(D(a,true))throw new S("NO_MODIFICATION_ALLOWED_ERR");}function B(a,e){if(!a)throw new S(e);}function V(a){return!!a.startContainer&&!!a.endContainer&&!(!g.arrayContains(ba,a.startContainer.nodeType)&&!h(a.startContainer,true))&&!(!g.arrayContains(ba,a.endContainer.nodeType)&&!h(a.endContainer,
true))&&a.startOffset<=(g.isCharacterDataNode(a.startContainer)?a.startContainer.length:a.startContainer.childNodes.length)&&a.endOffset<=(g.isCharacterDataNode(a.endContainer)?a.endContainer.length:a.endContainer.childNodes.length)}function J(a){r(a);if(!V(a))throw Error("Range error: Range is no longer valid after DOM mutation ("+a.inspect()+")");}function ca(){}function Y(a){a.START_TO_START=ia;a.START_TO_END=la;a.END_TO_END=ra;a.END_TO_START=ma;a.NODE_BEFORE=na;a.NODE_AFTER=oa;a.NODE_BEFORE_AND_AFTER=
pa;a.NODE_INSIDE=ja}function W(a){Y(a);Y(a.prototype)}function da(a,e){return function(){J(this);var j=this.startContainer,o=this.startOffset,E=this.commonAncestorContainer,T=new q(this,true);if(j!==E){j=g.getClosestAncestorIn(j,E,true);o=C(j);j=o.node;o=o.offset}i(T,w);T.reset();E=a(T);T.detach();e(this,j,o,j,o);return E}}function fa(a,e,j){function o(m,s){return function(y){r(this);L(y,$);L(d(y),ba);y=(m?z:C)(y);(s?E:T)(this,y.node,y.offset)}}function E(m,s,y){var F=m.endContainer,Q=m.endOffset;
if(s!==m.startContainer||y!==m.startOffset){if(d(s)!=d(F)||g.comparePoints(s,y,F,Q)==1){F=s;Q=y}e(m,s,y,F,Q)}}function T(m,s,y){var F=m.startContainer,Q=m.startOffset;if(s!==m.endContainer||y!==m.endOffset){if(d(s)!=d(F)||g.comparePoints(s,y,F,Q)==-1){F=s;Q=y}e(m,F,Q,s,y)}}a.prototype=new ca;l.util.extend(a.prototype,{setStart:function(m,s){r(this);k(m,true);p(m,s);E(this,m,s)},setEnd:function(m,s){r(this);k(m,true);p(m,s);T(this,m,s)},setStartBefore:o(true,true),setStartAfter:o(false,true),setEndBefore:o(true,
false),setEndAfter:o(false,false),collapse:function(m){J(this);m?e(this,this.startContainer,this.startOffset,this.startContainer,this.startOffset):e(this,this.endContainer,this.endOffset,this.endContainer,this.endOffset)},selectNodeContents:function(m){r(this);k(m,true);e(this,m,0,m,g.getNodeLength(m))},selectNode:function(m){r(this);k(m,false);L(m,$);var s=z(m);m=C(m);e(this,s.node,s.offset,m.node,m.offset)},extractContents:da(t,e),deleteContents:da(n,e),canSurroundContents:function(){J(this);w(this.startContainer);
w(this.endContainer);var m=new q(this,true),s=m._first&&K(m._first,this)||m._last&&K(m._last,this);m.detach();return!s},detach:function(){j(this)},splitBoundaries:function(){J(this);var m=this.startContainer,s=this.startOffset,y=this.endContainer,F=this.endOffset,Q=m===y;g.isCharacterDataNode(y)&&F>0&&F<y.length&&g.splitDataNode(y,F);if(g.isCharacterDataNode(m)&&s>0&&s<m.length){m=g.splitDataNode(m,s);if(Q){F-=s;y=m}else y==m.parentNode&&F>=g.getNodeIndex(m)&&F++;s=0}e(this,m,s,y,F)},normalizeBoundaries:function(){J(this);
var m=this.startContainer,s=this.startOffset,y=this.endContainer,F=this.endOffset,Q=function(U){var R=U.nextSibling;if(R&&R.nodeType==U.nodeType){y=U;F=U.length;U.appendData(R.data);R.parentNode.removeChild(R)}},qa=function(U){var R=U.previousSibling;if(R&&R.nodeType==U.nodeType){m=U;var sa=U.length;s=R.length;U.insertData(0,R.data);R.parentNode.removeChild(R);if(m==y){F+=s;y=m}else if(y==U.parentNode){R=g.getNodeIndex(U);if(F==R){y=U;F=sa}else F>R&&F--}}},ga=true;if(g.isCharacterDataNode(y))y.length==
F&&Q(y);else{if(F>0)(ga=y.childNodes[F-1])&&g.isCharacterDataNode(ga)&&Q(ga);ga=!this.collapsed}if(ga)if(g.isCharacterDataNode(m))s==0&&qa(m);else{if(s<m.childNodes.length)(Q=m.childNodes[s])&&g.isCharacterDataNode(Q)&&qa(Q)}else{m=y;s=F}e(this,m,s,y,F)},collapseToPoint:function(m,s){r(this);k(m,true);p(m,s);if(m!==this.startContainer||s!==this.startOffset||m!==this.endContainer||s!==this.endOffset)e(this,m,s,m,s)}});W(a)}function ea(a){a.collapsed=a.startContainer===a.endContainer&&a.startOffset===
a.endOffset;a.commonAncestorContainer=a.collapsed?a.startContainer:g.getCommonAncestor(a.startContainer,a.endContainer)}function ha(a,e,j,o,E){var T=a.startContainer!==e||a.startOffset!==j,m=a.endContainer!==o||a.endOffset!==E;a.startContainer=e;a.startOffset=j;a.endContainer=o;a.endOffset=E;ea(a);I(a,"boundarychange",{startMoved:T,endMoved:m})}function M(a){this.startContainer=a;this.startOffset=0;this.endContainer=a;this.endOffset=0;this._listeners={boundarychange:[],detach:[]};ea(this)}l.requireModules(["DomUtil"]);
var g=l.dom,Z=g.DomPosition,S=l.DOMException;q.prototype={_current:null,_next:null,_first:null,_last:null,isSingleCharacterDataNode:false,reset:function(){this._current=null;this._next=this._first},hasNext:function(){return!!this._next},next:function(){var a=this._current=this._next;if(a){this._next=a!==this._last?a.nextSibling:null;if(g.isCharacterDataNode(a)&&this.clonePartiallySelectedTextNodes){if(a===this.ec)(a=a.cloneNode(true)).deleteData(this.eo,a.length-this.eo);if(this._current===this.sc)(a=
a.cloneNode(true)).deleteData(0,this.so)}}return a},remove:function(){var a=this._current,e,j;if(g.isCharacterDataNode(a)&&(a===this.sc||a===this.ec)){e=a===this.sc?this.so:0;j=a===this.ec?this.eo:a.length;e!=j&&a.deleteData(e,j-e)}else a.parentNode&&a.parentNode.removeChild(a)},isPartiallySelectedSubtree:function(){return K(this._current,this.range)},getSubtreeIterator:function(){var a;if(this.isSingleCharacterDataNode){a=this.range.cloneRange();a.collapse()}else{a=new M(H(this.range));var e=this._current,
j=e,o=0,E=e,T=g.getNodeLength(e);if(g.isAncestorOf(e,this.sc,true)){j=this.sc;o=this.so}if(g.isAncestorOf(e,this.ec,true)){E=this.ec;T=this.eo}ha(a,j,o,E,T)}return new q(a,this.clonePartiallySelectedTextNodes)},detach:function(a){a&&this.range.detach();this.range=this._current=this._next=this._first=this._last=this.sc=this.so=this.ec=this.eo=null}};v.prototype={BAD_BOUNDARYPOINTS_ERR:1,INVALID_NODE_TYPE_ERR:2};v.prototype.toString=function(){return this.message};c.prototype={_current:null,hasNext:function(){return!!this._next},
next:function(){this._current=this._next;this._next=this.nodes[++this._position];return this._current},detach:function(){this._current=this._next=this.nodes=null}};var $=[1,3,4,5,7,8,10],ba=[2,9,11],aa=[1,3,4,5,7,8,10,11],b=[1,3,4,5,7,8],d=g.getRootContainer,h=f([9,11]),D=f([5,6,10,12]),G=f([6,10,12]),P=document.createElement("style"),X=false;try{P.innerHTML="<b>x</b>";X=P.firstChild.nodeType==3}catch(ta){}l.features.htmlParsingConforms=X;var ka=["startContainer","startOffset","endContainer","endOffset",
"collapsed","commonAncestorContainer"],ia=0,la=1,ra=2,ma=3,na=0,oa=1,pa=2,ja=3;ca.prototype={attachListener:function(a,e){this._listeners[a].push(e)},compareBoundaryPoints:function(a,e){J(this);u(this.startContainer,e.startContainer);var j=a==ma||a==ia?"start":"end",o=a==la||a==ia?"start":"end";return g.comparePoints(this[j+"Container"],this[j+"Offset"],e[o+"Container"],e[o+"Offset"])},insertNode:function(a){J(this);L(a,aa);w(this.startContainer);if(g.isAncestorOf(a,this.startContainer,true))throw new S("HIERARCHY_REQUEST_ERR");
this.setStartBefore(N(a,this.startContainer,this.startOffset))},cloneContents:function(){J(this);var a,e;if(this.collapsed)return H(this).createDocumentFragment();else{if(this.startContainer===this.endContainer&&g.isCharacterDataNode(this.startContainer)){a=this.startContainer.cloneNode(true);a.data=a.data.slice(this.startOffset,this.endOffset);e=H(this).createDocumentFragment();e.appendChild(a);return e}else{e=new q(this,true);a=O(e);e.detach()}return a}},canSurroundContents:function(){J(this);w(this.startContainer);
w(this.endContainer);var a=new q(this,true),e=a._first&&K(a._first,this)||a._last&&K(a._last,this);a.detach();return!e},surroundContents:function(a){L(a,b);if(!this.canSurroundContents())throw new v("BAD_BOUNDARYPOINTS_ERR");var e=this.extractContents();if(a.hasChildNodes())for(;a.lastChild;)a.removeChild(a.lastChild);N(a,this.startContainer,this.startOffset);a.appendChild(e);this.selectNode(a)},cloneRange:function(){J(this);for(var a=new M(H(this)),e=ka.length,j;e--;){j=ka[e];a[j]=this[j]}return a},
toString:function(){J(this);var a=this.startContainer;if(a===this.endContainer&&g.isCharacterDataNode(a))return a.nodeType==3||a.nodeType==4?a.data.slice(this.startOffset,this.endOffset):"";else{var e=[];a=new q(this,true);i(a,function(j){if(j.nodeType==3||j.nodeType==4)e.push(j.data)});a.detach();return e.join("")}},compareNode:function(a){J(this);var e=a.parentNode,j=g.getNodeIndex(a);if(!e)throw new S("NOT_FOUND_ERR");a=this.comparePoint(e,j);e=this.comparePoint(e,j+1);return a<0?e>0?pa:na:e>0?
oa:ja},comparePoint:function(a,e){J(this);B(a,"HIERARCHY_REQUEST_ERR");u(a,this.startContainer);if(g.comparePoints(a,e,this.startContainer,this.startOffset)<0)return-1;else if(g.comparePoints(a,e,this.endContainer,this.endOffset)>0)return 1;return 0},createContextualFragment:X?function(a){var e=this.startContainer,j=g.getDocument(e);if(!e)throw new S("INVALID_STATE_ERR");var o=null;if(e.nodeType==1)o=e;else if(g.isCharacterDataNode(e))o=g.parentElement(e);o=o===null||o.nodeName=="HTML"&&g.isHtmlNamespace(g.getDocument(o).documentElement)&&
g.isHtmlNamespace(o)?j.createElement("body"):o.cloneNode(false);o.innerHTML=a;return g.fragmentFromNodeChildren(o)}:function(a){r(this);var e=H(this).createElement("body");e.innerHTML=a;return g.fragmentFromNodeChildren(e)},toHtml:function(){J(this);var a=H(this).createElement("div");a.appendChild(this.cloneContents());return a.innerHTML},intersectsNode:function(a,e){J(this);B(a,"NOT_FOUND_ERR");if(g.getDocument(a)!==H(this))return false;var j=a.parentNode,o=g.getNodeIndex(a);B(j,"NOT_FOUND_ERR");
var E=g.comparePoints(j,o,this.endContainer,this.endOffset);j=g.comparePoints(j,o+1,this.startContainer,this.startOffset);return e?E<=0&&j>=0:E<0&&j>0},isPointInRange:function(a,e){J(this);B(a,"HIERARCHY_REQUEST_ERR");u(a,this.startContainer);return g.comparePoints(a,e,this.startContainer,this.startOffset)>=0&&g.comparePoints(a,e,this.endContainer,this.endOffset)<=0},intersectsRange:function(a,e){J(this);if(H(a)!=H(this))throw new S("WRONG_DOCUMENT_ERR");var j=g.comparePoints(this.startContainer,
this.startOffset,a.endContainer,a.endOffset),o=g.comparePoints(this.endContainer,this.endOffset,a.startContainer,a.startOffset);return e?j<=0&&o>=0:j<0&&o>0},intersection:function(a){if(this.intersectsRange(a)){var e=g.comparePoints(this.startContainer,this.startOffset,a.startContainer,a.startOffset),j=g.comparePoints(this.endContainer,this.endOffset,a.endContainer,a.endOffset),o=this.cloneRange();e==-1&&o.setStart(a.startContainer,a.startOffset);j==1&&o.setEnd(a.endContainer,a.endOffset);return o}return null},
union:function(a){if(this.intersectsRange(a,true)){var e=this.cloneRange();g.comparePoints(a.startContainer,a.startOffset,this.startContainer,this.startOffset)==-1&&e.setStart(a.startContainer,a.startOffset);g.comparePoints(a.endContainer,a.endOffset,this.endContainer,this.endOffset)==1&&e.setEnd(a.endContainer,a.endOffset);return e}else throw new v("Ranges do not intersect");},containsNode:function(a,e){return e?this.intersectsNode(a,false):this.compareNode(a)==ja},containsNodeContents:function(a){return this.comparePoint(a,
0)>=0&&this.comparePoint(a,g.getNodeLength(a))<=0},containsRange:function(a){return this.intersection(a).equals(a)},containsNodeText:function(a){var e=this.cloneRange();e.selectNode(a);var j=e.getNodes([3]);if(j.length>0){e.setStart(j[0],0);a=j.pop();e.setEnd(a,a.length);a=this.containsRange(e);e.detach();return a}else return this.containsNodeContents(a)},createNodeIterator:function(a,e){J(this);return new c(this,a,e)},getNodes:function(a,e){J(this);return x(this,a,e)},getDocument:function(){return H(this)},
collapseBefore:function(a){r(this);this.setEndBefore(a);this.collapse(false)},collapseAfter:function(a){r(this);this.setStartAfter(a);this.collapse(true)},getName:function(){return"DomRange"},equals:function(a){return M.rangesEqual(this,a)},isValid:function(){return V(this)},inspect:function(){return A(this)}};fa(M,ha,function(a){r(a);a.startContainer=a.startOffset=a.endContainer=a.endOffset=null;a.collapsed=a.commonAncestorContainer=null;I(a,"detach",null);a._listeners=null});l.rangePrototype=ca.prototype;
M.rangeProperties=ka;M.RangeIterator=q;M.copyComparisonConstants=W;M.createPrototypeRange=fa;M.inspect=A;M.getRangeDocument=H;M.rangesEqual=function(a,e){return a.startContainer===e.startContainer&&a.startOffset===e.startOffset&&a.endContainer===e.endContainer&&a.endOffset===e.endOffset};l.DomRange=M;l.RangeException=v});
rangy.createModule("WrappedRange",function(l){function K(i,n,t,x){var A=i.duplicate();A.collapse(t);var q=A.parentElement();z.isAncestorOf(n,q,true)||(q=n);if(!q.canHaveHTML)return new C(q.parentNode,z.getNodeIndex(q));n=z.getDocument(q).createElement("span");var v,c=t?"StartToStart":"StartToEnd";do{q.insertBefore(n,n.previousSibling);A.moveToElementText(n)}while((v=A.compareEndPoints(c,i))>0&&n.previousSibling);c=n.nextSibling;if(v==-1&&c&&z.isCharacterDataNode(c)){A.setEndPoint(t?"EndToStart":"EndToEnd",
i);if(/[\r\n]/.test(c.data)){q=A.duplicate();t=q.text.replace(/\r\n/g,"\r").length;for(t=q.moveStart("character",t);q.compareEndPoints("StartToEnd",q)==-1;){t++;q.moveStart("character",1)}}else t=A.text.length;q=new C(c,t)}else{c=(x||!t)&&n.previousSibling;q=(t=(x||t)&&n.nextSibling)&&z.isCharacterDataNode(t)?new C(t,0):c&&z.isCharacterDataNode(c)?new C(c,c.length):new C(q,z.getNodeIndex(n))}n.parentNode.removeChild(n);return q}function H(i,n){var t,x,A=i.offset,q=z.getDocument(i.node),v=q.body.createTextRange(),
c=z.isCharacterDataNode(i.node);if(c){t=i.node;x=t.parentNode}else{t=i.node.childNodes;t=A<t.length?t[A]:null;x=i.node}q=q.createElement("span");q.innerHTML="&#feff;";t?x.insertBefore(q,t):x.appendChild(q);v.moveToElementText(q);v.collapse(!n);x.removeChild(q);if(c)v[n?"moveStart":"moveEnd"]("character",A);return v}l.requireModules(["DomUtil","DomRange"]);var I,z=l.dom,C=z.DomPosition,N=l.DomRange;if(l.features.implementsDomRange&&(!l.features.implementsTextRange||!l.config.preferTextRange)){(function(){function i(f){for(var k=
t.length,r;k--;){r=t[k];f[r]=f.nativeRange[r]}}var n,t=N.rangeProperties,x,A;I=function(f){if(!f)throw Error("Range must be specified");this.nativeRange=f;i(this)};N.createPrototypeRange(I,function(f,k,r,L,p){var u=f.endContainer!==L||f.endOffset!=p;if(f.startContainer!==k||f.startOffset!=r||u){f.setEnd(L,p);f.setStart(k,r)}},function(f){f.nativeRange.detach();f.detached=true;for(var k=t.length,r;k--;){r=t[k];f[r]=null}});n=I.prototype;n.selectNode=function(f){this.nativeRange.selectNode(f);i(this)};
n.deleteContents=function(){this.nativeRange.deleteContents();i(this)};n.extractContents=function(){var f=this.nativeRange.extractContents();i(this);return f};n.cloneContents=function(){return this.nativeRange.cloneContents()};n.surroundContents=function(f){this.nativeRange.surroundContents(f);i(this)};n.collapse=function(f){this.nativeRange.collapse(f);i(this)};n.cloneRange=function(){return new I(this.nativeRange.cloneRange())};n.refresh=function(){i(this)};n.toString=function(){return this.nativeRange.toString()};
var q=document.createTextNode("test");z.getBody(document).appendChild(q);var v=document.createRange();v.setStart(q,0);v.setEnd(q,0);try{v.setStart(q,1);x=true;n.setStart=function(f,k){this.nativeRange.setStart(f,k);i(this)};n.setEnd=function(f,k){this.nativeRange.setEnd(f,k);i(this)};A=function(f){return function(k){this.nativeRange[f](k);i(this)}}}catch(c){x=false;n.setStart=function(f,k){try{this.nativeRange.setStart(f,k)}catch(r){this.nativeRange.setEnd(f,k);this.nativeRange.setStart(f,k)}i(this)};
n.setEnd=function(f,k){try{this.nativeRange.setEnd(f,k)}catch(r){this.nativeRange.setStart(f,k);this.nativeRange.setEnd(f,k)}i(this)};A=function(f,k){return function(r){try{this.nativeRange[f](r)}catch(L){this.nativeRange[k](r);this.nativeRange[f](r)}i(this)}}}n.setStartBefore=A("setStartBefore","setEndBefore");n.setStartAfter=A("setStartAfter","setEndAfter");n.setEndBefore=A("setEndBefore","setStartBefore");n.setEndAfter=A("setEndAfter","setStartAfter");v.selectNodeContents(q);n.selectNodeContents=
v.startContainer==q&&v.endContainer==q&&v.startOffset==0&&v.endOffset==q.length?function(f){this.nativeRange.selectNodeContents(f);i(this)}:function(f){this.setStart(f,0);this.setEnd(f,N.getEndOffset(f))};v.selectNodeContents(q);v.setEnd(q,3);x=document.createRange();x.selectNodeContents(q);x.setEnd(q,4);x.setStart(q,2);n.compareBoundaryPoints=v.compareBoundaryPoints(v.START_TO_END,x)==-1&v.compareBoundaryPoints(v.END_TO_START,x)==1?function(f,k){k=k.nativeRange||k;if(f==k.START_TO_END)f=k.END_TO_START;
else if(f==k.END_TO_START)f=k.START_TO_END;return this.nativeRange.compareBoundaryPoints(f,k)}:function(f,k){return this.nativeRange.compareBoundaryPoints(f,k.nativeRange||k)};if(l.util.isHostMethod(v,"createContextualFragment"))n.createContextualFragment=function(f){return this.nativeRange.createContextualFragment(f)};z.getBody(document).removeChild(q);v.detach();x.detach()})();l.createNativeRange=function(i){i=i||document;return i.createRange()}}else if(l.features.implementsTextRange){I=function(i){this.textRange=
i;this.refresh()};I.prototype=new N(document);I.prototype.refresh=function(){var i,n,t=this.textRange;i=t.parentElement();var x=t.duplicate();x.collapse(true);n=x.parentElement();x=t.duplicate();x.collapse(false);t=x.parentElement();n=n==t?n:z.getCommonAncestor(n,t);n=n==i?n:z.getCommonAncestor(i,n);if(this.textRange.compareEndPoints("StartToEnd",this.textRange)==0)n=i=K(this.textRange,n,true,true);else{i=K(this.textRange,n,true,false);n=K(this.textRange,n,false,false)}this.setStart(i.node,i.offset);
this.setEnd(n.node,n.offset)};N.copyComparisonConstants(I);var O=function(){return this}();if(typeof O.Range=="undefined")O.Range=I;l.createNativeRange=function(i){i=i||document;return i.body.createTextRange()}}if(l.features.implementsTextRange)I.rangeToTextRange=function(i){if(i.collapsed)return H(new C(i.startContainer,i.startOffset),true);else{var n=H(new C(i.startContainer,i.startOffset),true),t=H(new C(i.endContainer,i.endOffset),false);i=z.getDocument(i.startContainer).body.createTextRange();
i.setEndPoint("StartToStart",n);i.setEndPoint("EndToEnd",t);return i}};I.prototype.getName=function(){return"WrappedRange"};l.WrappedRange=I;l.createRange=function(i){i=i||document;return new I(l.createNativeRange(i))};l.createRangyRange=function(i){i=i||document;return new N(i)};l.createIframeRange=function(i){return l.createRange(z.getIframeDocument(i))};l.createIframeRangyRange=function(i){return l.createRangyRange(z.getIframeDocument(i))};l.addCreateMissingNativeApiListener(function(i){i=i.document;
if(typeof i.createRange=="undefined")i.createRange=function(){return l.createRange(this)};i=i=null})});
rangy.createModule("WrappedSelection",function(l,K){function H(b){return(b||window).getSelection()}function I(b){return(b||window).document.selection}function z(b,d,h){var D=h?"end":"start";h=h?"start":"end";b.anchorNode=d[D+"Container"];b.anchorOffset=d[D+"Offset"];b.focusNode=d[h+"Container"];b.focusOffset=d[h+"Offset"]}function C(b){b.anchorNode=b.focusNode=null;b.anchorOffset=b.focusOffset=0;b.rangeCount=0;b.isCollapsed=true;b._ranges.length=0}function N(b){var d;if(b instanceof k){d=b._selectionNativeRange;
if(!d){d=l.createNativeRange(c.getDocument(b.startContainer));d.setEnd(b.endContainer,b.endOffset);d.setStart(b.startContainer,b.startOffset);b._selectionNativeRange=d;b.attachListener("detach",function(){this._selectionNativeRange=null})}}else if(b instanceof r)d=b.nativeRange;else if(l.features.implementsDomRange&&b instanceof c.getWindow(b.startContainer).Range)d=b;return d}function O(b){var d=b.getNodes(),h;a:if(!d.length||d[0].nodeType!=1)h=false;else{h=1;for(var D=d.length;h<D;++h)if(!c.isAncestorOf(d[0],
d[h])){h=false;break a}h=true}if(!h)throw Error("getSingleElementFromRange: range "+b.inspect()+" did not consist of a single element");return d[0]}function i(b,d){var h=new r(d);b._ranges=[h];z(b,h,false);b.rangeCount=1;b.isCollapsed=h.collapsed}function n(b){b._ranges.length=0;if(b.docSelection.type=="None")C(b);else{var d=b.docSelection.createRange();if(d&&typeof d.text!="undefined")i(b,d);else{b.rangeCount=d.length;for(var h,D=c.getDocument(d.item(0)),G=0;G<b.rangeCount;++G){h=l.createRange(D);
h.selectNode(d.item(G));b._ranges.push(h)}b.isCollapsed=b.rangeCount==1&&b._ranges[0].collapsed;z(b,b._ranges[b.rangeCount-1],false)}}}function t(b,d){var h=b.docSelection.createRange(),D=O(d),G=c.getDocument(h.item(0));G=c.getBody(G).createControlRange();for(var P=0,X=h.length;P<X;++P)G.add(h.item(P));try{G.add(D)}catch(ta){throw Error("addRange(): Element within the specified Range could not be added to control selection (does it have layout?)");}G.select();n(b)}function x(b,d,h){this.nativeSelection=
b;this.docSelection=d;this._ranges=[];this.win=h;this.refresh()}function A(b,d){var h=c.getDocument(d[0].startContainer);h=c.getBody(h).createControlRange();for(var D=0,G;D<rangeCount;++D){G=O(d[D]);try{h.add(G)}catch(P){throw Error("setRanges(): Element within the one of the specified Ranges could not be added to control selection (does it have layout?)");}}h.select();n(b)}function q(b,d){if(b.anchorNode&&c.getDocument(b.anchorNode)!==c.getDocument(d))throw new L("WRONG_DOCUMENT_ERR");}function v(b){var d=
[],h=new p(b.anchorNode,b.anchorOffset),D=new p(b.focusNode,b.focusOffset),G=typeof b.getName=="function"?b.getName():"Selection";if(typeof b.rangeCount!="undefined")for(var P=0,X=b.rangeCount;P<X;++P)d[P]=k.inspect(b.getRangeAt(P));return"["+G+"(Ranges: "+d.join(", ")+")(anchor: "+h.inspect()+", focus: "+D.inspect()+"]"}l.requireModules(["DomUtil","DomRange","WrappedRange"]);l.config.checkSelectionRanges=true;var c=l.dom,f=l.util,k=l.DomRange,r=l.WrappedRange,L=l.DOMException,p=c.DomPosition,u,w,
B=l.util.isHostMethod(window,"getSelection"),V=l.util.isHostObject(document,"selection"),J=V&&(!B||l.config.preferTextRange);if(J){u=I;l.isSelectionValid=function(b){b=(b||window).document;var d=b.selection;return d.type!="None"||c.getDocument(d.createRange().parentElement())==b}}else if(B){u=H;l.isSelectionValid=function(){return true}}else K.fail("Neither document.selection or window.getSelection() detected.");l.getNativeSelection=u;B=u();var ca=l.createNativeRange(document),Y=c.getBody(document),
W=f.areHostObjects(B,f.areHostProperties(B,["anchorOffset","focusOffset"]));l.features.selectionHasAnchorAndFocus=W;var da=f.isHostMethod(B,"extend");l.features.selectionHasExtend=da;var fa=typeof B.rangeCount=="number";l.features.selectionHasRangeCount=fa;var ea=false,ha=true;f.areHostMethods(B,["addRange","getRangeAt","removeAllRanges"])&&typeof B.rangeCount=="number"&&l.features.implementsDomRange&&function(){var b=document.createElement("iframe");b.frameBorder=0;b.style.position="absolute";b.style.left=
"-10000px";Y.appendChild(b);var d=c.getIframeDocument(b);d.open();d.write("<html><head></head><body>12</body></html>");d.close();var h=c.getIframeWindow(b).getSelection(),D=d.documentElement.lastChild.firstChild;d=d.createRange();d.setStart(D,1);d.collapse(true);h.addRange(d);ha=h.rangeCount==1;h.removeAllRanges();var G=d.cloneRange();d.setStart(D,0);G.setEnd(D,2);h.addRange(d);h.addRange(G);ea=h.rangeCount==2;d.detach();G.detach();Y.removeChild(b)}();l.features.selectionSupportsMultipleRanges=ea;
l.features.collapsedNonEditableSelectionsSupported=ha;var M=false,g;if(Y&&f.isHostMethod(Y,"createControlRange")){g=Y.createControlRange();if(f.areHostProperties(g,["item","add"]))M=true}l.features.implementsControlRange=M;w=W?function(b){return b.anchorNode===b.focusNode&&b.anchorOffset===b.focusOffset}:function(b){return b.rangeCount?b.getRangeAt(b.rangeCount-1).collapsed:false};var Z;if(f.isHostMethod(B,"getRangeAt"))Z=function(b,d){try{return b.getRangeAt(d)}catch(h){return null}};else if(W)Z=
function(b){var d=c.getDocument(b.anchorNode);d=l.createRange(d);d.setStart(b.anchorNode,b.anchorOffset);d.setEnd(b.focusNode,b.focusOffset);if(d.collapsed!==this.isCollapsed){d.setStart(b.focusNode,b.focusOffset);d.setEnd(b.anchorNode,b.anchorOffset)}return d};l.getSelection=function(b){b=b||window;var d=b._rangySelection,h=u(b),D=V?I(b):null;if(d){d.nativeSelection=h;d.docSelection=D;d.refresh(b)}else{d=new x(h,D,b);b._rangySelection=d}return d};l.getIframeSelection=function(b){return l.getSelection(c.getIframeWindow(b))};
g=x.prototype;if(!J&&W&&f.areHostMethods(B,["removeAllRanges","addRange"])){g.removeAllRanges=function(){this.nativeSelection.removeAllRanges();C(this)};var S=function(b,d){var h=k.getRangeDocument(d);h=l.createRange(h);h.collapseToPoint(d.endContainer,d.endOffset);b.nativeSelection.addRange(N(h));b.nativeSelection.extend(d.startContainer,d.startOffset);b.refresh()};g.addRange=fa?function(b,d){if(M&&V&&this.docSelection.type=="Control")t(this,b);else if(d&&da)S(this,b);else{var h;if(ea)h=this.rangeCount;
else{this.removeAllRanges();h=0}this.nativeSelection.addRange(N(b));this.rangeCount=this.nativeSelection.rangeCount;if(this.rangeCount==h+1){if(l.config.checkSelectionRanges)if((h=Z(this.nativeSelection,this.rangeCount-1))&&!k.rangesEqual(h,b))b=new r(h);this._ranges[this.rangeCount-1]=b;z(this,b,aa(this.nativeSelection));this.isCollapsed=w(this)}else this.refresh()}}:function(b,d){if(d&&da)S(this,b);else{this.nativeSelection.addRange(N(b));this.refresh()}};g.setRanges=function(b){if(M&&b.length>
1)A(this,b);else{this.removeAllRanges();for(var d=0,h=b.length;d<h;++d)this.addRange(b[d])}}}else if(f.isHostMethod(B,"empty")&&f.isHostMethod(ca,"select")&&M&&J){g.removeAllRanges=function(){try{this.docSelection.empty();if(this.docSelection.type!="None"){var b;if(this.anchorNode)b=c.getDocument(this.anchorNode);else if(this.docSelection.type=="Control"){var d=this.docSelection.createRange();if(d.length)b=c.getDocument(d.item(0)).body.createTextRange()}if(b){b.body.createTextRange().select();this.docSelection.empty()}}}catch(h){}C(this)};
g.addRange=function(b){if(this.docSelection.type=="Control")t(this,b);else{r.rangeToTextRange(b).select();this._ranges[0]=b;this.rangeCount=1;this.isCollapsed=this._ranges[0].collapsed;z(this,b,false)}};g.setRanges=function(b){this.removeAllRanges();var d=b.length;if(d>1)A(this,b);else d&&this.addRange(b[0])}}else{K.fail("No means of selecting a Range or TextRange was found");return false}g.getRangeAt=function(b){if(b<0||b>=this.rangeCount)throw new L("INDEX_SIZE_ERR");else return this._ranges[b]};
var $;if(J)$=function(b){var d;if(l.isSelectionValid(b.win))d=b.docSelection.createRange();else{d=c.getBody(b.win.document).createTextRange();d.collapse(true)}if(b.docSelection.type=="Control")n(b);else d&&typeof d.text!="undefined"?i(b,d):C(b)};else if(f.isHostMethod(B,"getRangeAt")&&typeof B.rangeCount=="number")$=function(b){if(M&&V&&b.docSelection.type=="Control")n(b);else{b._ranges.length=b.rangeCount=b.nativeSelection.rangeCount;if(b.rangeCount){for(var d=0,h=b.rangeCount;d<h;++d)b._ranges[d]=
new l.WrappedRange(b.nativeSelection.getRangeAt(d));z(b,b._ranges[b.rangeCount-1],aa(b.nativeSelection));b.isCollapsed=w(b)}else C(b)}};else if(W&&typeof B.isCollapsed=="boolean"&&typeof ca.collapsed=="boolean"&&l.features.implementsDomRange)$=function(b){var d;d=b.nativeSelection;if(d.anchorNode){d=Z(d,0);b._ranges=[d];b.rangeCount=1;d=b.nativeSelection;b.anchorNode=d.anchorNode;b.anchorOffset=d.anchorOffset;b.focusNode=d.focusNode;b.focusOffset=d.focusOffset;b.isCollapsed=w(b)}else C(b)};else{K.fail("No means of obtaining a Range or TextRange from the user's selection was found");
return false}g.refresh=function(b){var d=b?this._ranges.slice(0):null;$(this);if(b){b=d.length;if(b!=this._ranges.length)return false;for(;b--;)if(!k.rangesEqual(d[b],this._ranges[b]))return false;return true}};var ba=function(b,d){var h=b.getAllRanges(),D=false;b.removeAllRanges();for(var G=0,P=h.length;G<P;++G)if(D||d!==h[G])b.addRange(h[G]);else D=true;b.rangeCount||C(b)};g.removeRange=M?function(b){if(this.docSelection.type=="Control"){var d=this.docSelection.createRange();b=O(b);var h=c.getDocument(d.item(0));
h=c.getBody(h).createControlRange();for(var D,G=false,P=0,X=d.length;P<X;++P){D=d.item(P);if(D!==b||G)h.add(d.item(P));else G=true}h.select();n(this)}else ba(this,b)}:function(b){ba(this,b)};var aa;if(!J&&W&&l.features.implementsDomRange){aa=function(b){var d=false;if(b.anchorNode)d=c.comparePoints(b.anchorNode,b.anchorOffset,b.focusNode,b.focusOffset)==1;return d};g.isBackwards=function(){return aa(this)}}else aa=g.isBackwards=function(){return false};g.toString=function(){for(var b=[],d=0,h=this.rangeCount;d<
h;++d)b[d]=""+this._ranges[d];return b.join("")};g.collapse=function(b,d){q(this,b);var h=l.createRange(c.getDocument(b));h.collapseToPoint(b,d);this.removeAllRanges();this.addRange(h);this.isCollapsed=true};g.collapseToStart=function(){if(this.rangeCount){var b=this._ranges[0];this.collapse(b.startContainer,b.startOffset)}else throw new L("INVALID_STATE_ERR");};g.collapseToEnd=function(){if(this.rangeCount){var b=this._ranges[this.rangeCount-1];this.collapse(b.endContainer,b.endOffset)}else throw new L("INVALID_STATE_ERR");
};g.selectAllChildren=function(b){q(this,b);var d=l.createRange(c.getDocument(b));d.selectNodeContents(b);this.removeAllRanges();this.addRange(d)};g.deleteFromDocument=function(){if(M&&V&&this.docSelection.type=="Control"){for(var b=this.docSelection.createRange(),d;b.length;){d=b.item(0);b.remove(d);d.parentNode.removeChild(d)}this.refresh()}else if(this.rangeCount){b=this.getAllRanges();this.removeAllRanges();d=0;for(var h=b.length;d<h;++d)b[d].deleteContents();this.addRange(b[h-1])}};g.getAllRanges=
function(){return this._ranges.slice(0)};g.setSingleRange=function(b){this.setRanges([b])};g.containsNode=function(b,d){for(var h=0,D=this._ranges.length;h<D;++h)if(this._ranges[h].containsNode(b,d))return true;return false};g.toHtml=function(){var b="";if(this.rangeCount){b=k.getRangeDocument(this._ranges[0]).createElement("div");for(var d=0,h=this._ranges.length;d<h;++d)b.appendChild(this._ranges[d].cloneContents());b=b.innerHTML}return b};g.getName=function(){return"WrappedSelection"};g.inspect=
function(){return v(this)};g.detach=function(){this.win=this.anchorNode=this.focusNode=this.win._rangySelection=null};x.inspect=v;l.Selection=x;l.selectionPrototype=g;l.addCreateMissingNativeApiListener(function(b){if(typeof b.getSelection=="undefined")b.getSelection=function(){return l.getSelection(this)};b=null})});
})();

(() => {
/*!******************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/libs/rangy/rangy-cssclassapplier.js ***!
  \******************************************************************************/
/*
 CSS Class Applier module for Rangy.
 Adds, removes and toggles CSS classes on Ranges and Selections

 Part of Rangy, a cross-browser JavaScript range and selection library
 http://code.google.com/p/rangy/

 Depends on Rangy core.

 Copyright 2012, Tim Down
 Licensed under the MIT license.
 Version: 1.2.3
 Build date: 26 February 2012
*/
rangy.createModule("CssClassApplier",function(i,v){function r(a,b){return a.className&&RegExp("(?:^|\\s)"+b+"(?:\\s|$)").test(a.className)}function s(a,b){if(a.className)r(a,b)||(a.className+=" "+b);else a.className=b}function o(a){return a.split(/\s+/).sort().join(" ")}function w(a,b){return o(a.className)==o(b.className)}function x(a){for(var b=a.parentNode;a.hasChildNodes();)b.insertBefore(a.firstChild,a);b.removeChild(a)}function y(a,b){var c=a.cloneRange();c.selectNodeContents(b);var d=c.intersection(a);
d=d?d.toString():"";c.detach();return d!=""}function z(a){return a.getNodes([3],function(b){return y(a,b)})}function A(a,b){if(a.attributes.length!=b.attributes.length)return false;for(var c=0,d=a.attributes.length,e,f;c<d;++c){e=a.attributes[c];f=e.name;if(f!="class"){f=b.attributes.getNamedItem(f);if(e.specified!=f.specified)return false;if(e.specified&&e.nodeValue!==f.nodeValue)return false}}return true}function B(a,b){for(var c=0,d=a.attributes.length,e;c<d;++c){e=a.attributes[c].name;if(!(b&&
h.arrayContains(b,e))&&a.attributes[c].specified&&e!="class")return true}return false}function C(a){var b;return a&&a.nodeType==1&&((b=a.parentNode)&&b.nodeType==9&&b.designMode=="on"||k(a)&&!k(a.parentNode))}function D(a){return(k(a)||a.nodeType!=1&&k(a.parentNode))&&!C(a)}function E(a){return a&&a.nodeType==1&&!M.test(p(a,"display"))}function N(a){if(a.data.length==0)return true;if(O.test(a.data))return false;switch(p(a.parentNode,"whiteSpace")){case "pre":case "pre-wrap":case "-moz-pre-wrap":return false;
case "pre-line":if(/[\r\n]/.test(a.data))return false}return E(a.previousSibling)||E(a.nextSibling)}function m(a,b,c,d){var e,f=c==0;if(h.isAncestorOf(b,a))return a;if(h.isCharacterDataNode(b))if(c==0){c=h.getNodeIndex(b);b=b.parentNode}else if(c==b.length){c=h.getNodeIndex(b)+1;b=b.parentNode}else throw v.createError("splitNodeAt should not be called with offset in the middle of a data node ("+c+" in "+b.data);var g;g=b;var j=c;g=h.isCharacterDataNode(g)?j==0?!!g.previousSibling:j==g.length?!!g.nextSibling:
true:j>0&&j<g.childNodes.length;if(g){if(!e){e=b.cloneNode(false);for(e.id&&e.removeAttribute("id");f=b.childNodes[c];)e.appendChild(f);h.insertAfter(e,b)}return b==a?e:m(a,e.parentNode,h.getNodeIndex(e),d)}else if(a!=b){e=b.parentNode;b=h.getNodeIndex(b);f||b++;return m(a,e,b,d)}return a}function F(a){var b=a?"nextSibling":"previousSibling";return function(c,d){var e=c.parentNode,f=c[b];if(f){if(f&&f.nodeType==3)return f}else if(d)if((f=e[b])&&f.nodeType==1&&e.tagName==f.tagName&&w(e,f)&&A(e,f))return f[a?
"firstChild":"lastChild"];return null}}function t(a){this.firstTextNode=(this.isElementMerge=a.nodeType==1)?a.lastChild:a;this.textNodes=[this.firstTextNode]}function q(a,b,c){this.cssClass=a;var d,e,f=null;if(typeof b=="object"&&b!==null){c=b.tagNames;f=b.elementProperties;for(d=0;e=P[d++];)if(b.hasOwnProperty(e))this[e]=b[e];d=b.normalize}else d=b;this.normalize=typeof d=="undefined"?true:d;this.attrExceptions=[];d=document.createElement(this.elementTagName);this.elementProperties={};for(var g in f)if(f.hasOwnProperty(g)){if(G.hasOwnProperty(g))g=
G[g];d[g]=f[g];this.elementProperties[g]=d[g];this.attrExceptions.push(g)}this.elementSortedClassName=this.elementProperties.hasOwnProperty("className")?o(this.elementProperties.className+" "+a):a;this.applyToAnyTagName=false;a=typeof c;if(a=="string")if(c=="*")this.applyToAnyTagName=true;else this.tagNames=c.toLowerCase().replace(/^\s\s*/,"").replace(/\s\s*$/,"").split(/\s*,\s*/);else if(a=="object"&&typeof c.length=="number"){this.tagNames=[];d=0;for(a=c.length;d<a;++d)if(c[d]=="*")this.applyToAnyTagName=
true;else this.tagNames.push(c[d].toLowerCase())}else this.tagNames=[this.elementTagName]}i.requireModules(["WrappedSelection","WrappedRange"]);var h=i.dom,H=function(){function a(b,c,d){return c&&d?" ":""}return function(b,c){if(b.className)b.className=b.className.replace(RegExp("(?:^|\\s)"+c+"(?:\\s|$)"),a)}}(),p;if(typeof window.getComputedStyle!="undefined")p=function(a,b){return h.getWindow(a).getComputedStyle(a,null)[b]};else if(typeof document.documentElement.currentStyle!="undefined")p=function(a,
b){return a.currentStyle[b]};else v.fail("No means of obtaining computed style properties found");var k;(function(){k=typeof document.createElement("div").isContentEditable=="boolean"?function(a){return a&&a.nodeType==1&&a.isContentEditable}:function(a){if(!a||a.nodeType!=1||a.contentEditable=="false")return false;return a.contentEditable=="true"||k(a.parentNode)}})();var M=/^inline(-block|-table)?$/i,O=/[^\r\n\t\f \u200B]/,Q=F(false),R=F(true);t.prototype={doMerge:function(){for(var a=[],b,c,d=0,
e=this.textNodes.length;d<e;++d){b=this.textNodes[d];c=b.parentNode;a[d]=b.data;if(d){c.removeChild(b);c.hasChildNodes()||c.parentNode.removeChild(c)}}return this.firstTextNode.data=a=a.join("")},getLength:function(){for(var a=this.textNodes.length,b=0;a--;)b+=this.textNodes[a].length;return b},toString:function(){for(var a=[],b=0,c=this.textNodes.length;b<c;++b)a[b]="'"+this.textNodes[b].data+"'";return"[Merge("+a.join(",")+")]"}};var P=["elementTagName","ignoreWhiteSpace","applyToEditableOnly"],
G={"class":"className"};q.prototype={elementTagName:"span",elementProperties:{},ignoreWhiteSpace:true,applyToEditableOnly:false,hasClass:function(a){return a.nodeType==1&&h.arrayContains(this.tagNames,a.tagName.toLowerCase())&&r(a,this.cssClass)},getSelfOrAncestorWithClass:function(a){for(;a;){if(this.hasClass(a,this.cssClass))return a;a=a.parentNode}return null},isModifiable:function(a){return!this.applyToEditableOnly||D(a)},isIgnorableWhiteSpaceNode:function(a){return this.ignoreWhiteSpace&&a&&
a.nodeType==3&&N(a)},postApply:function(a,b,c){for(var d=a[0],e=a[a.length-1],f=[],g,j=d,I=e,J=0,K=e.length,n,L,l=0,u=a.length;l<u;++l){n=a[l];if(L=Q(n,!c)){if(!g){g=new t(L);f.push(g)}g.textNodes.push(n);if(n===d){j=g.firstTextNode;J=j.length}if(n===e){I=g.firstTextNode;K=g.getLength()}}else g=null}if(a=R(e,!c)){if(!g){g=new t(e);f.push(g)}g.textNodes.push(a)}if(f.length){l=0;for(u=f.length;l<u;++l)f[l].doMerge();b.setStart(j,J);b.setEnd(I,K)}},createContainer:function(a){a=a.createElement(this.elementTagName);
i.util.extend(a,this.elementProperties);s(a,this.cssClass);return a},applyToTextNode:function(a){var b=a.parentNode;if(b.childNodes.length==1&&h.arrayContains(this.tagNames,b.tagName.toLowerCase()))s(b,this.cssClass);else{b=this.createContainer(h.getDocument(a));a.parentNode.insertBefore(b,a);b.appendChild(a)}},isRemovable:function(a){var b;if(b=a.tagName.toLowerCase()==this.elementTagName){if(b=o(a.className)==this.elementSortedClassName){var c;a:{b=this.elementProperties;for(c in b)if(b.hasOwnProperty(c)&&
a[c]!==b[c]){c=false;break a}c=true}b=c&&!B(a,this.attrExceptions)&&this.isModifiable(a)}b=b}return b},undoToTextNode:function(a,b,c){if(!b.containsNode(c)){a=b.cloneRange();a.selectNode(c);if(a.isPointInRange(b.endContainer,b.endOffset)){m(c,b.endContainer,b.endOffset,[b]);b.setEndAfter(c)}if(a.isPointInRange(b.startContainer,b.startOffset))c=m(c,b.startContainer,b.startOffset,[b])}this.isRemovable(c)?x(c):H(c,this.cssClass)},applyToRange:function(a){a.splitBoundaries();var b=z(a);if(b.length){for(var c,
d=0,e=b.length;d<e;++d){c=b[d];!this.isIgnorableWhiteSpaceNode(c)&&!this.getSelfOrAncestorWithClass(c)&&this.isModifiable(c)&&this.applyToTextNode(c)}a.setStart(b[0],0);c=b[b.length-1];a.setEnd(c,c.length);this.normalize&&this.postApply(b,a,false)}},applyToSelection:function(a){a=a||window;a=i.getSelection(a);var b,c=a.getAllRanges();a.removeAllRanges();for(var d=c.length;d--;){b=c[d];this.applyToRange(b);a.addRange(b)}},undoToRange:function(a){a.splitBoundaries();var b=z(a),c,d,e=b[b.length-1];if(b.length){for(var f=
0,g=b.length;f<g;++f){c=b[f];(d=this.getSelfOrAncestorWithClass(c))&&this.isModifiable(c)&&this.undoToTextNode(c,a,d);a.setStart(b[0],0);a.setEnd(e,e.length)}this.normalize&&this.postApply(b,a,true)}},undoToSelection:function(a){a=a||window;a=i.getSelection(a);var b=a.getAllRanges(),c;a.removeAllRanges();for(var d=0,e=b.length;d<e;++d){c=b[d];this.undoToRange(c);a.addRange(c)}},getTextSelectedByRange:function(a,b){var c=b.cloneRange();c.selectNodeContents(a);var d=c.intersection(b);d=d?d.toString():
"";c.detach();return d},isAppliedToRange:function(a){if(a.collapsed)return!!this.getSelfOrAncestorWithClass(a.commonAncestorContainer);else{for(var b=a.getNodes([3]),c=0,d;d=b[c++];)if(!this.isIgnorableWhiteSpaceNode(d)&&y(a,d)&&this.isModifiable(d)&&!this.getSelfOrAncestorWithClass(d))return false;return true}},isAppliedToSelection:function(a){a=a||window;a=i.getSelection(a).getAllRanges();for(var b=a.length;b--;)if(!this.isAppliedToRange(a[b]))return false;return true},toggleRange:function(a){this.isAppliedToRange(a)?
this.undoToRange(a):this.applyToRange(a)},toggleSelection:function(a){this.isAppliedToSelection(a)?this.undoToSelection(a):this.applyToSelection(a)},detach:function(){}};q.util={hasClass:r,addClass:s,removeClass:H,hasSameClasses:w,replaceWithOwnChildren:x,elementsHaveSameNonClassAttributes:A,elementHasNonClassAttributes:B,splitNodeAt:m,isEditableElement:k,isEditingHost:C,isEditable:D};i.CssClassApplier=q;i.createCssClassApplier=function(a,b,c){return new q(a,b,c)}});
})();

(() => {
/*!***********************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/libs/rangy/rangy-selectionsaverestore.js ***!
  \***********************************************************************************/
/*
 Selection save and restore module for Rangy.
 Saves and restores user selections using marker invisible elements in the DOM.

 Part of Rangy, a cross-browser JavaScript range and selection library
 http://code.google.com/p/rangy/

 Depends on Rangy core.

 Copyright 2012, Tim Down
 Licensed under the MIT license.
 Version: 1.2.3
 Build date: 26 February 2012
*/
rangy.createModule("SaveRestore",function(h,m){function n(a,g){var e="selectionBoundary_"+ +new Date+"_"+(""+Math.random()).slice(2),c,f=p.getDocument(a.startContainer),d=a.cloneRange();d.collapse(g);c=f.createElement("span");c.id=e;c.style.lineHeight="0";c.style.display="none";c.className="rangySelectionBoundary";c.appendChild(f.createTextNode(q));d.insertNode(c);d.detach();return c}function o(a,g,e,c){if(a=(a||document).getElementById(e)){g[c?"setStartBefore":"setEndBefore"](a);a.parentNode.removeChild(a)}else m.warn("Marker element has been removed. Cannot restore selection.")}
function r(a,g){return g.compareBoundaryPoints(a.START_TO_START,a)}function k(a,g){var e=(a||document).getElementById(g);e&&e.parentNode.removeChild(e)}h.requireModules(["DomUtil","DomRange","WrappedRange"]);var p=h.dom,q="\ufeff";h.saveSelection=function(a){a=a||window;var g=a.document;if(h.isSelectionValid(a)){var e=h.getSelection(a),c=e.getAllRanges(),f=[],d,j;c.sort(r);for(var b=0,i=c.length;b<i;++b){d=c[b];if(d.collapsed){j=n(d,false);f.push({markerId:j.id,collapsed:true})}else{j=n(d,false);
d=n(d,true);f[b]={startMarkerId:d.id,endMarkerId:j.id,collapsed:false,backwards:c.length==1&&e.isBackwards()}}}for(b=i-1;b>=0;--b){d=c[b];if(d.collapsed)d.collapseBefore((g||document).getElementById(f[b].markerId));else{d.setEndBefore((g||document).getElementById(f[b].endMarkerId));d.setStartAfter((g||document).getElementById(f[b].startMarkerId))}}e.setRanges(c);return{win:a,doc:g,rangeInfos:f,restored:false}}else m.warn("Cannot save selection. This usually happens when the selection is collapsed and the selection document has lost focus.")};
h.restoreSelection=function(a,g){if(!a.restored){for(var e=a.rangeInfos,c=h.getSelection(a.win),f=[],d=e.length,j=d-1,b,i;j>=0;--j){b=e[j];i=h.createRange(a.doc);if(b.collapsed)if(b=(a.doc||document).getElementById(b.markerId)){b.style.display="inline";var l=b.previousSibling;if(l&&l.nodeType==3){b.parentNode.removeChild(b);i.collapseToPoint(l,l.length)}else{i.collapseBefore(b);b.parentNode.removeChild(b)}}else m.warn("Marker element has been removed. Cannot restore selection.");else{o(a.doc,i,b.startMarkerId,
true);o(a.doc,i,b.endMarkerId,false)}d==1&&i.normalizeBoundaries();f[j]=i}if(d==1&&g&&h.features.selectionHasExtend&&e[0].backwards){c.removeAllRanges();c.addRange(f[0],true)}else c.setRanges(f);a.restored=true}};h.removeMarkerElement=k;h.removeMarkers=function(a){for(var g=a.rangeInfos,e=0,c=g.length,f;e<c;++e){f=g[e];if(f.collapsed)k(a.doc,f.markerId);else{k(a.doc,f.startMarkerId);k(a.doc,f.endMarkerId)}}}});
})();

(() => {
/*!*************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/libs/rangy/rangy-serializer.js ***!
  \*************************************************************************/
/*
 Serializer module for Rangy.
 Serializes Ranges and Selections. An example use would be to store a user's selection on a particular page in a
 cookie or local storage and restore it on the user's next visit to the same page.

 Part of Rangy, a cross-browser JavaScript range and selection library
 http://code.google.com/p/rangy/

 Depends on Rangy core.

 Copyright 2012, Tim Down
 Licensed under the MIT license.
 Version: 1.2.3
 Build date: 26 February 2012
*/
rangy.createModule("Serializer",function(g,n){function o(c,a){a=a||[];var b=c.nodeType,e=c.childNodes,d=e.length,f=[b,c.nodeName,d].join(":"),h="",k="";switch(b){case 3:h=c.nodeValue.replace(/</g,"&lt;").replace(/>/g,"&gt;");break;case 8:h="<!--"+c.nodeValue.replace(/</g,"&lt;").replace(/>/g,"&gt;")+"--\>";break;default:h="<"+f+">";k="</>";break}h&&a.push(h);for(b=0;b<d;++b)o(e[b],a);k&&a.push(k);return a}function j(c){c=o(c).join("");return u(c).toString(16)}function l(c,a,b){var e=[],d=c;for(b=
b||i.getDocument(c).documentElement;d&&d!=b;){e.push(i.getNodeIndex(d,true));d=d.parentNode}return e.join("/")+":"+a}function m(c,a,b){if(a)b||i.getDocument(a);else{b=b||document;a=b.documentElement}c=c.split(":");a=a;b=c[0]?c[0].split("/"):[];for(var e=b.length,d;e--;){d=parseInt(b[e],10);if(d<a.childNodes.length)a=a.childNodes[parseInt(b[e],10)];else throw n.createError("deserializePosition failed: node "+i.inspectNode(a)+" has no child with index "+d+", "+e);}return new i.DomPosition(a,parseInt(c[1],
10))}function p(c,a,b){b=b||g.DomRange.getRangeDocument(c).documentElement;if(!i.isAncestorOf(b,c.commonAncestorContainer,true))throw Error("serializeRange: range is not wholly contained within specified root node");c=l(c.startContainer,c.startOffset,b)+","+l(c.endContainer,c.endOffset,b);a||(c+="{"+j(b)+"}");return c}function q(c,a,b){if(a)b=b||i.getDocument(a);else{b=b||document;a=b.documentElement}c=/^([^,]+),([^,\{]+)({([^}]+)})?$/.exec(c);var e=c[4],d=j(a);if(e&&e!==j(a))throw Error("deserializeRange: checksums of serialized range root node ("+
e+") and target root node ("+d+") do not match");e=m(c[1],a,b);a=m(c[2],a,b);b=g.createRange(b);b.setStart(e.node,e.offset);b.setEnd(a.node,a.offset);return b}function r(c,a,b){if(a)b||i.getDocument(a);else{b=b||document;a=b.documentElement}c=/^([^,]+),([^,]+)({([^}]+)})?$/.exec(c)[3];return!c||c===j(a)}function s(c,a,b){c=c||g.getSelection();c=c.getAllRanges();for(var e=[],d=0,f=c.length;d<f;++d)e[d]=p(c[d],a,b);return e.join("|")}function t(c,a,b){if(a)b=b||i.getWindow(a);else{b=b||window;a=b.document.documentElement}c=
c.split("|");for(var e=g.getSelection(b),d=[],f=0,h=c.length;f<h;++f)d[f]=q(c[f],a,b.document);e.setRanges(d);return e}g.requireModules(["WrappedSelection","WrappedRange"]);if(typeof encodeURIComponent=="undefined"||typeof decodeURIComponent=="undefined")n.fail("Global object is missing encodeURIComponent and/or decodeURIComponent method");var u=function(){var c=null;return function(a){for(var b=[],e=0,d=a.length,f;e<d;++e){f=a.charCodeAt(e);if(f<128)b.push(f);else f<2048?b.push(f>>6|192,f&63|128):
b.push(f>>12|224,f>>6&63|128,f&63|128)}a=-1;if(!c){e=[];d=0;for(var h;d<256;++d){h=d;for(f=8;f--;)if((h&1)==1)h=h>>>1^3988292384;else h>>>=1;e[d]=h>>>0}c=e}e=c;d=0;for(f=b.length;d<f;++d){h=(a^b[d])&255;a=a>>>8^e[h]}return(a^-1)>>>0}}(),i=g.dom;g.serializePosition=l;g.deserializePosition=m;g.serializeRange=p;g.deserializeRange=q;g.canDeserializeRange=r;g.serializeSelection=s;g.deserializeSelection=t;g.canDeserializeSelection=function(c,a,b){var e;if(a)e=b?b.document:i.getDocument(a);else{b=b||window;
a=b.document.documentElement}c=c.split("|");b=0;for(var d=c.length;b<d;++b)if(!r(c[b],a,e))return false;return true};g.restoreSelectionFromCookie=function(c){c=c||window;var a;a:{a=c.document.cookie.split(/[;,]/);for(var b=0,e=a.length,d;b<e;++b){d=a[b].split("=");if(d[0].replace(/^\s+/,"")=="rangySerializedSelection")if(d=d[1]){a=decodeURIComponent(d.replace(/\s+$/,""));break a}}a=null}a&&t(a,c.doc)};g.saveSelectionCookie=function(c,a){c=c||window;a=typeof a=="object"?a:{};var b=a.expires?";expires="+
a.expires.toUTCString():"",e=a.path?";path="+a.path:"",d=a.domain?";domain="+a.domain:"",f=a.secure?";secure":"",h=s(g.getSelection(c));c.document.cookie=encodeURIComponent("rangySerializedSelection")+"="+encodeURIComponent(h)+b+e+d+f};g.getElementChecksum=j});
})();

(() => {
/*!******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/beforeleave.js ***!
  \******************************************************************/


mw.liveedit.beforeleave = function(url) {
    var beforeleave_html = "" +
        "<div class='mw-before-leave-container'>" +
        "<p>Leave page by choosing an option</p>" +
        "<span class='mw-ui-btn mw-ui-btn-important'>" + mw.msg.before_leave + "</span>" +
        "<span class='mw-ui-btn mw-ui-btn-notification' >" + mw.msg.save_and_continue + "</span>" +
        "<span class='mw-ui-btn' onclick='mw.dialog.remove(\"modal_beforeleave\")'>" + mw.msg.cancel + "</span>" +
        "</div>";
    if (mw.askusertostay && mw.$(".edit.orig_changed").length > 0) {
        if (document.getElementById('modal_beforeleave') === null) {
            var modal = mw.dialog({
                html: beforeleave_html,
                name: 'modal_beforeleave',
                width: 470,
                height: 230,
                template: 'mw_modal_basic'
            });

            var save = modal.container.querySelector('.mw-ui-btn-notification');
            var go = modal.container.querySelector('.mw-ui-btn-important');

            mw.$(save).click(function() {
                mw.$(document.body).addClass("loading");
                mw.dialog.remove(modal);
                mw.drag.save(undefined, function() {
                    mw.askusertostay = false;
                    window.location.href = url;
                });
            });
            mw.$(go).click(function() {
                mw.askusertostay = false;
                window.location.href = url;
            });
        }

        return false;
    }
}

})();

(() => {
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/data.js ***!
  \***********************************************************/
mw.liveedit.data = {
    _data:{},
    _target: null,
    init: function() {
        var scope = this;
        mw.$(document.body)
        .on('touchmove mousemove', function(e){
            var hasLayout = !!mw.tools.firstMatchesOnNodeOrParent(e.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
            mw.liveedit.data.set('move', 'hasLayout', hasLayout);
            mw.liveedit.data.set('move', 'hasModuleOrElement', mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['module', 'element']));
            if(scope._target !== e.target) {
                scope._target = e.target;
                mw.trigger('Liveedit');
            }
        })
        .on('mouseup touchend', function(e){
            mw.liveedit.data.set('mouseup', 'isIcon', mw.wysiwyg.firstElementThatHasFontIconClass(e.target));
        });


    },
    set: function(action, item, value){
        this._data[action] = this._data[action] || {};
        this._data[action][item] = value;
    },
    get: function(action, item){
        return this._data[action] ? this._data[action][item] : undefined;
    }
};

})();

(() => {
/*!******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/edit.fields.js ***!
  \******************************************************************/
mw.liveedit.editFields = {
    handleKeydown: function() {
        mw.$('.edit').on('keydown', function(e){
            var istab = (e.which || e.keyCode) === 9,
                isShiftTab = istab && e.shiftKey,
                tabOnly = istab && !e.shiftKey,
                target;

            if(istab){
                e.preventDefault();
                target = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
            }
            if(tabOnly){
                if(target.nodeName === 'LI'){
                    var parent = target.parentNode;
                    if(parent.children[0] !== target){
                        var prev = target.previousElementSibling;
                        var ul = document.createElement(parent.nodeName);
                        ul.appendChild(target);
                        prev.appendChild(ul)
                    }
                }
                else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                    target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    nexttd = target.nextElementSibling;
                    if(!!nexttd){
                        mw.wysiwyg.cursorToElement(nexttd, 'start');
                    }
                    else{
                        var nextRow = target.parentNode.nextElementSibling;
                        if(!!nextRow){
                            mw.wysiwyg.cursorToElement(nextRow.querySelector('td'), 'start');
                        }
                    }
                }
                else{
                    mw.wysiwyg.insert_html('&nbsp;&nbsp;');
                }

            }
            else if(isShiftTab){
                if(target.nodeName === 'LI'){
                    var parent = target.parentNode;
                    var isSub = parent.parentNode.nodeName === 'LI';
                    if(isSub){
                        var split = mw.wysiwyg.listSplit(parent, mw.$('li', parent).index(target));

                        var parentLi = parent.parentNode;
                        mw.$(parentLi).after(split.middle);
                        if(!!split.top){
                            mw.$(parentLi).append(split.top);
                        }
                        if(!!split.bottom){
                            mw.$(split.middle).append(split.bottom);
                        }

                        mw.$(parent).remove();
                    }
                }
                else if(target.nodeName === 'TD' || mw.tools.hasParentsWithTag(target, 'td')){
                    var target = target.nodeName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    var nexttd = target.previousElementSibling;
                    if(!!nexttd){
                        mw.wysiwyg.cursorToElement(nexttd, 'start');
                    }
                    else{
                        var nextRow = target.parentNode.previousElementSibling;
                        if(!!nextRow){
                            mw.wysiwyg.cursorToElement(nextRow.querySelector('td:last-child'), 'start');
                        }
                    }
                }
                else{
                    var range = getSelection().getRangeAt(0);
                    clone = range.cloneRange();
                    clone.setStart(range.startContainer, range.startOffset - 2);
                    clone.setEnd(range.startContainer, range.startOffset);
                    var nv = clone.cloneContents().firstChild.nodeValue;
                    var nvcheck = nv.replace(/\s/g,'');
                    if( nvcheck === '' ){
                        clone.deleteContents();
                    }
                }
            }
        });
    }
}

})();

(() => {
/*!**************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/editors.js ***!
  \**************************************************************/
mw.liveedit.editors = {
  prepare: function() {
      mw.$(window).on("mouseup touchend", function(e) {

          var sel = getSelection();
          if (sel.rangeCount > 0) {
              var range = sel.getRangeAt(0),
                  common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);

              if (mw.tools.hasClass(common, 'edit') || mw.tools.hasParentsWithClass(common, 'edit')) {
                  var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
                  if (nodrop_state) {
                      mw.wysiwyg.enableEditors();
                  } else {
                      mw.wysiwyg.disableEditors();
                  }
              } else {
                  mw.wysiwyg.disableEditors();
              }
          }

          sel = window.getSelection();
          if (sel.rangeCount > 0) {
              var r = sel.getRangeAt(0);
              var cac = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
          }
          if (mw.tools.hasAnyOfClassesOnNodeOrParent(cac, ['edit', 'mw-admin-editor-area']) && (sel.rangeCount > 0 && !sel.getRangeAt(0).collapsed)) {

              if ($.contains(e.target, cac) || $.contains(cac, e.target) || cac === e.target) {
                  setTimeout(function() {
                      var ep = mw.event.page(e);
                      if (cac.isContentEditable && !sel.isCollapsed && !mw.tools.hasClass(cac, 'plain-text') && !mw.tools.hasClass(cac, 'safe-element')) {
                          if (typeof(window.getSelection().getRangeAt(0).getClientRects()[0]) == 'undefined') {
                              return;
                          }
                          mw.smallEditorCanceled = false;
                          var top = ep.y - mw.smallEditor.height() - window.getSelection().getRangeAt(0).getClientRects()[0].height;
                          mw.smallEditor.css({
                              visibility: "visible",
                              opacity: 0.7,
                              top: (top > 55 ? top : 55),
                              left: ep.x + mw.smallEditor.width() < mw.$(window).width() ? ep.x : ($(window).width() - mw.smallEditor.width() - 5)
                          });

                      } else {
                          mw.smallEditorCanceled = true;
                          mw.smallEditor.css({
                              visibility: "hidden"
                          });
                      }

                  }, 33);
              }
          } else {
              if (mw.smallEditor && !mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                      mw.smallEditorCanceled = true;
                      mw.smallEditor.css({
                          visibility: "hidden"
                      });
              }
          }
          setTimeout(function() {
              if (window.getSelection().rangecount > 0 && window.getSelection().getRangeAt(0).collapsed) {
                  if (typeof(mw.smallEditor) != 'undefined') {
                      mw.smallEditorCanceled = true;
                      mw.smallEditor.css({
                          visibility: "hidden"
                      });
                  }
              }
          }, 39);
      });
      mw.smallEditorOff = 150;

      mw.$(window).on("mousemove touchmove touchstart", function(e) {
          if (!!mw.smallEditor && !mw.isDrag && !mw.smallEditorCanceled && !mw.smallEditor.hasClass("editor_hover")) {
              var off = mw.smallEditor.offset();
              var ep = mw.event.page(e);
              if (typeof off !== 'undefined') {
                  if (
                      ((ep.x - mw.smallEditorOff) > (off.left + mw.smallEditor.width()))
                      || ((ep.y - mw.smallEditorOff) > (off.top + mw.smallEditor.height()))
                      || ((ep.x + mw.smallEditorOff) < (off.left)) || ((ep.y + mw.smallEditorOff) < (off.top))) {
                      if (typeof mw.smallEditor !== 'undefined') {
                          mw.smallEditor.css("visibility", "hidden");
                          mw.smallEditorCanceled = true;
                      }
                  }
              }
          }
      });
      mw.$(window).on("scroll", function(e) {
          if (typeof(mw.smallEditor) !== "undefined") {
              mw.smallEditor.css("visibility", "hidden");
              mw.smallEditorCanceled = true;
          }
      });
      mw.$("#live_edit_toolbar, #mw_small_editor").on("mousedown touchstart", function(e) {

          mw.$(".wysiwyg_external").empty();
          if (e.target.nodeName !== 'INPUT' && e.target.nodeName !== 'SELECT' && e.target.nodeName !== 'OPTION' && e.target.nodeName !== 'CHECKBOX') {
              e.preventDefault();
          }
          if (typeof(mw.smallEditor) !== "undefined") {
              if (!mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                  mw.smallEditor.css("visibility", "hidden");
                  mw.smallEditorCanceled = true;
              }
          }
      });
  }
};

})();

(() => {
/*!********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/events.custom.js ***!
  \********************************************************************/
mw.liveedit.handleCustomEvents = function() {
    mw.on('moduleOver ElementOver', function(e, etarget, oevent){
        var target = mw.tools.firstParentOrCurrentWithAnyOfClasses(oevent.target, ['element', 'module']);
        if(target.id){
            mw.liveEditSelector.active(true);
            mw.liveEditSelector.setItem(target, mw.liveEditSelector.interactors);
        }
    });

    mw.$(document.body).on('click', function (e) {
        var target = e.target;
        var can = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, [
           'edit', 'module', 'element'
        ]);
        if(can) {
            var toSelect = mw.tools.firstNotInlineLevel(target);

            mw.liveEditSelector.select(target);

            if(mw.liveEditDomTree) {
                mw.liveEditDomTree.select(mw.wysiwyg.validateCommonAncestorContainer(target));

            }
        }


    });


    mw.on("DragHoverOnEmpty", function(e, el) {
        if ($.browser.webkit) {
            var _el = mw.$(el);
            _el.addClass("hover");
            if (!_el.hasClass("mw-webkit-drag-hover-binded")) {
                _el.addClass("mw-webkit-drag-hover-binded");
                _el.mouseleave(function() {
                    _el.removeClass("hover");
                });
            }
        }
    });
    mw.on("IconElementClick", function(e, el) {
        mw.editorIconPicker.tooltip(el)
        setTimeout(function () {
            mw.wysiwyg.contentEditable(el, false);
        });
    });

    mw.on("ComponentClick", function(e, node, type){

        if (type === 'icon'){
            mw.editorIconPicker.tooltip(node)
            return;

        }
        if(mw.settings.live_edit_open_module_settings_in_sidebar) {
            mw.log('ComponentClick' + type);
            if (!mw.liveEditSettings) {
                return; // admin mode
            }
            var uitype = type;
            if (type === 'element') {
                uitype = 'none';
            }
            if (type === 'safe-element') {
                //uitype = 'element' ;
                uitype = 'none';
            }
            if (node.nodeName === 'IMG') {
                uitype = 'image';
            }

            if (mw.liveEditSettings.active) {
                if (mw.sidebarSettingsTabs) {
                    if (uitype !== 'module') {
                        mw.sidebarSettingsTabs.setLastClicked();
                    } else {
                        mw.sidebarSettingsTabs.set(2);
                    }
                }
                mw.liveNodeSettings.set(uitype, node);
            }

        }
    });

    mw.on("ElementClick", function(e, el, c) {
        mw.$(".element-current").not(el).removeClass('element-current');
        if (mw.liveEditSelectMode === 'element') {
            mw.$(el).addClass('element-current');
        }

        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false)
        });
    });
    mw.on("PlainTextClick", function(e, el) {
        mw.wysiwyg.contentEditable(el, true);
        mw.$('.module').each(function(){
            mw.wysiwyg.contentEditable(this, false);
        });
    });


    mw.on("editUserIsTypingForLong", function(node){
        if(typeof(mw.liveEditSettings) != 'undefined'){
            if(mw.liveEditSettings.active){
                mw.liveEditSettings.hide();
            }
        }
    });
    mw.on("TableTdClick", function(e, el) {
        if (mw.liveedit && mw.liveedit.inline) {
            mw.liveedit.inline.setActiveCell(el, e);
            var td_parent_table = mw.tools.firstParentWithTag(el, 'table');
            if (td_parent_table) {
                mw.liveedit.inline.tableController(td_parent_table);
            }
        }
    });

    mw.on('UserInteraction', function(){
        mw.dropables.userInteractionClasses();
        mw.liveEditSelector.positionSelected();

    });

    mw.on('ElementOver moduleOver', function(e, target){
        var over_target_el = null;
        if(e.type === 'onElementOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['element'])
            if(over_target_el && !mw.tools.hasClass('element-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'element-over')
            }
        } else if(e.type === 'moduleOver'){
            over_target_el = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['module'])
            if(over_target_el && !mw.tools.hasClass('module-over',over_target_el)){
                mw.tools.addClass(over_target_el, 'module-over')
            }
        }
        if(over_target_el){
            mw.$(".element-over,.module-over").not(over_target_el).removeClass('element-over module-over');
        }
    });



    mw.on('CloneableOver', function(e, target, isOverControl){
        mw.drag.onCloneableControl(target, isOverControl)
    });

    var onModuleBetweenModulesTime = null;

    mw.on('ModuleBetweenModules', function(e, el, pos){
        clearTimeout(onModuleBetweenModulesTime);
        onModuleBetweenModulesTime = setTimeout(function(){
            if($("#moduleinbetween").length === 0){
                var tip = mw.tooltip({
                    content:'To drop this element here, select Clean container first',
                    element:el[0],
                    position:pos+'-center',
                    skin:'dark',
                    id:'moduleinbetween'
                });
                setTimeout(function(){
                    mw.$("#moduleinbetween").fadeOut(function(){
                        mw.$(this).remove();
                    });
                }, 3000);
            }
        }, 1000);
    });
};

})();

(() => {
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/events.js ***!
  \*************************************************************/
mw.liveedit.handleEvents = function() {
    mw.$(document.body).on('touchmove mousemove', function(e){
        if(mw.liveEditSelector.interactors.active) {
            if( !mw.liveedit.data.get('move', 'hasModuleOrElement')){
                if(e.target !== mw.drag.plusTop && e.target !== mw.drag.plusBottom) {
                    mw.liveEditSelector.hideItem(mw.liveEditSelector.interactors);
                }
            }
        }
    });
    mw.$("#live-edit-dropdown-actions-content a").off('click');

    mw.$(document).on('mousedown touchstart', function(e){
        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-defaults', 'edit', 'element'])){
            mw.$(".element-current").removeClass("element-current");
        }
    });

    mw.$("span.edit:not('.nodrop')").each(function(){
        mw.tools.setTag(this, 'div');
    });


    mw.$("#mw-toolbar-css-editor-btn").click(function() {
        mw.liveedit.widgets.cssEditorDialog();
    });
    mw.$("#mw-toolbar-html-editor-btn").click(function() {
        mw.liveedit.widgets.htmlEditorDialog();
    });

    mw.$("#mw-toolbar-api-clear-cache-btn").click(function() {
        mw.notification.warning("Clearing cache...");
        $.get(mw.settings.api_url + "clearcache", {}, function () {
            mw.notification.warning("Cache is cleared! reloading the page...");
            location.reload();
        });
    });

    mw.$("#mw-toolbar-reset-content-editor-btn").click(function() {
        mw.tools.open_reset_content_editor();
    });
    mw.$(document.body).on('keyup', function(e) {
        mw.$(".mw_master_handle").css({
            left: "",
            top: ""
        });
        /*mw.on.stopWriting(e.target, function() {
            if (mw.tools.hasClass(e.target, 'edit') || mw.tools.hasParentsWithClass(this, 'edit')) {
                mw.liveEditState.record({
                    target:e.target,
                    value:e.target.innerHTML
                });
                mw.drag.saveDraft();
            }
        });*/
    });

    mw.$(document.body).on("keydown", function(e) {

        if (e.keyCode === 83 && e.ctrlKey) {

            if (e.altKey) {
                return;
            }

            if (typeof(mw.settings.live_edit_disable_keyboard_shortcuts) != 'undefined') {
                if (mw.settings.live_edit_disable_keyboard_shortcuts === true) {
                    return;
                }
            }
            mw.event.cancel(e, true);
            mw.drag.save();
        }
    });

    mw.$(document.body).on("paste", function(e) {
        if(mw.tools.hasClass(e.target, 'plain-text')){
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertHTML", false, text);
        }
    });

    mw.$(document.body).on("mousedown mouseup touchstart touchend", function(e) {

        if (e.type === 'mousedown' || e.type === 'touchstart') {
            if (!mw.wysiwyg.elementHasFontIconClass(e.target)
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['tooltip-icon-picker', 'mw-tooltip'])) {

                if(mw.editorIconPicker){
                mw.editorIconPicker.tooltip('hide');
                }
                try {
                    $(mw.liveedit.widgets._iconEditor.tooltip).hide();
                } catch(e) {

                }

            }
            if (!mw.tools.hasClass(e.target, 'ui-resizable-handle') && !mw.tools.hasParentsWithClass(e.target, 'ui-resizable-handle')) {
                mw.tools.addClass(document.body, 'state-element')
            } else {
                mw.tools.removeClass(document.body, 'state-element');
            }

            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip-insert-module') && !mw.tools.hasAnyOfClasses(e.target, ['mw-plus-bottom', 'mw-plus-top'])) {
                mw.$('.mw-tooltip-insert-module').remove();
                mw.drag.plus.locked = false;
            }

        } else {
            mw.tools.removeClass(document.body, 'state-element');
        }
    });
    mw.$('span.mw-powered-by').on("click", function(e) {
        mw.tools.open_global_module_settings_modal('white_label/admin', 'mw-powered-by');
        return false;
    });

    mw.$(".edit a, #mw-toolbar-right a").click(function() {
        var el = this;
        if (!el.isContentEditable) {
            if (el.onclick === null) {
                var href = (el.getAttribute('href') || '').trim();
                if(href) {
                    if (!(href.indexOf("javascript:") === 0 || href.indexOf('#') === 0)) {
                        return mw.liveedit.beforeleave(this.href);
                    }
                }
            }
        }
    });



    mw.on('editChanged', function (target){
        document.querySelector('#main-save-btn').disabled = false;
    });

    mw.on('saveEnd', function (data){
        document.querySelector('#main-save-btn').disabled = true;
    })

};

})();

(() => {
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/initload.js ***!
  \***************************************************************/
mw.liveedit.initLoad = function() {
    setTimeout(function(){
        mw.$(".mw-dropdown_type_navigation a").each(function() {
            var el = mw.$(this);
            var li = el.parent();
            el.attr("href", "javascript:;");
            var val = li.dataset("category-id");
            li.attr("value", val);
        });

        mw.$("#module_category_selector").change(function() {
            var val = mw.$(this).getDropdownValue();

            if (val === 'all') {
                mw.$(".list-modules li").show();
                return false;
            }
            (val !== -1 && val !== "-1") ? mw.liveedit.toolbar_sorter(Modules_List_modules, val): '';
        });
        mw.$("#elements_category_selector").change(function() {
            var val = mw.$(this).getDropdownValue();

            if (val === 'all') {
                mw.$(".list-elements li").show();
                return false;
            }
            (val !== -1 && val !== "-1") ? mw.liveedit.toolbar_sorter(Modules_List_elements, val): '';
        });




        mw.interval('regular-mode', function(){
            // mw.$('.row').addClass('nodrop');
            // mw.$('.row .col, .row [class*="col-"]').addClass('allow-drop');
            // mw.$('.nodrop .allow-drop').addClass('regular-mode');
            mw.$('.safe-element[class*="mw-micon-"]').removeClass('safe-element');
        })

    }, 100);


    mw.wysiwyg.prepareContentEditable();

    mw.image.resize.init(".element-image");
    mw.$(document.body).on('mousedown touchstart', function(event) {


        if (mw.$(".editor_hover").length === 0) {
            mw.$(mw.wysiwyg.external).empty().css("top", "-9999px");
            mw.$(document.body).removeClass('hide_selection');
        }
        if (!mw.tools.hasClass(event.target, 'mw_handle_row') &&
            !mw.tools.hasParentsWithClass(event.target, 'mw_handle_row') &&
            !mw.tools.hasClass(event.target, 'mw-row') &&
            !mw.tools.hasParentsWithClass(event.target, 'mw-row')) {

            mw.$(".mw-row").each(function() {
                this.clicked = false;
            });
        }
        if (mw.tools.hasClass(event.target, 'mw-row')) {
            mw.$(".mw-row").each(function() {
                if (this !== event.target) {
                    this.clicked = false;
                }
            });
            event.target.clicked = true;
        } else if (mw.tools.hasParentsWithClass(event.target, 'mw-row')) {
            var row = mw.tools.firstParentWithClass(event.target, 'mw-row');
            mw.$(".mw-row").each(function() {
                if (this !== row) {
                    this.clicked = false;
                }
            });
            row.clicked = true;
        }
    });


    mw.$(document.body).on('mouseup touchend',function(event) {
        mw.target.item = event.target;
        mw.target.tag = event.target.tagName.toLowerCase();
        mw.mouseDownOnEditor = false;
        mw.SmallEditorIsDragging = false;
        if (!mw.image.isResizing &&
            mw.target.tag !== 'img' &&
            event.target !== mw.image_resizer && !mw.tools.hasClass(mw.target.item.className, 'image_change') && !mw.tools.hasClass(mw.target.item.parentNode.className, 'image_change') && mw.$(mw.image_resizer).hasClass("active")) {
            mw.image_resizer._hide();
        }
    });

    mw.liveedit.toolbar.prepare();
    mw.liveedit.toolbar.fixPad();
    mw.liveedit.editors.prepare();
    mw.liveedit.toolbar.setEditor();
}

})();

(() => {
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/initready.js ***!
  \****************************************************************/
mw.liveedit.initReady = function() {
    mw.liveedit.data.init();
    mw.liveEditSelector = new mw.Selector({
        root: document.body,
        autoSelect: false
    });

    mw.paddingCTRL = new mw.paddingEditor({

    });

    mw.drag.create();

    mw.liveedit.editFields.handleKeydown();

    mw.dragSTOPCheck = false;

    var t = document.querySelectorAll('[field="title"]'),
        l = t.length,
        i = 0;

    for (; i < l; i++) {
        mw.$(t[i]).addClass("nodrop");
    }



    mw.wysiwyg.init_editables();
    mw.wysiwyg.prepare();
    mw.wysiwyg.init();
    mw.ea = mw.ea || new mw.ElementAnalyzer();
};

})();

(() => {
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/inline.js ***!
  \*************************************************************/
mw.liveedit.inline = {
    bar: function (id) {
        if (typeof id === 'undefined') {
            return false;
        }
        if (mw.$("#" + id).length === 0) {
            var bar = document.createElement('div');
            bar.id = id;
            mw.wysiwyg.contentEditable(bar, false);
            bar.className = 'mw-defaults mw-inline-bar';
            document.body.appendChild(bar);
            return bar;
        }
        else {
            return mw.$("#" + id)[0];
        }
    },
    tableControl: false,
    tableController: function (el, e) {
        if (typeof e !== 'undefined') {
            e.stopPropagation();
        }
        if (mw.liveedit.inline.tableControl === false) {
            mw.liveedit.inline.tableControl = mw.liveedit.inline.bar('mw-inline-tableControl');
            mw.liveedit.inline.tableControl.innerHTML = ''
                + '<ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">'
                + '<li>'
                + '<a href="javascript:;">Insert<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'above\', mw.liveedit.inline.activeCell);">Row Above</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'under\', mw.liveedit.inline.activeCell);">Row Under</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'left\', mw.liveedit.inline.activeCell)">Column on left</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'right\', mw.liveedit.inline.activeCell)">Column on right</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Style<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table\', mw.liveedit.inline.activeCell);">Bordered</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-zebra\', mw.liveedit.inline.activeCell);">Bordered Zebra</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple\', mw.liveedit.inline.activeCell);">Simple</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple-zebra\', mw.liveedit.inline.activeCell);">Simple Zebra</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Delete<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteRow(mw.liveedit.inline.activeCell);">Row</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteColumn(mw.liveedit.inline.activeCell);">Column</a></li>'
                + '</ul>'
                + '</li>'
                + '</ul>';
        }
        var off = mw.$(el).offset();
        mw.$(mw.liveedit.inline.tableControl).css({
            top: off.top - 45,
            left: off.left,
            display: 'block'
        });
    },
    activeCell: null,
    setActiveCell: function (el, event) {
        if (!mw.tools.hasClass(el.className, 'tc-activecell')) {
            mw.$(".tc-activecell").removeClass('tc-activecell');
            mw.$(el).addClass('tc-activecell');
            mw.liveedit.inline.activeCell = el;
        }
    },
    tableManager: {
        insertColumn: function (dir, cell) {
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                var cell = mw.$(row).children('td')[index];
                if (dir == 'left' || dir == 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir == 'right' || dir == 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        },
        insertRow: function (dir, cell) {
            var cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            var dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length, html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            var html = '<tr>' + html + '</tr>';
            if (dir == 'under' || dir == 'both') {
                mw.$(parent).after(html)
            }
            if (dir == 'above' || dir == 'both') {
                mw.$(parent).before(html)
            }
        },
        deleteRow: function (cell) {
            mw.$(cell.parentNode).remove();
        },
        deleteColumn: function (cell) {
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        },
        setStyle: function (cls, cell) {
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        }
    }
}

})();

(() => {
/*!*****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/layoutplus.js ***!
  \*****************************************************************/
mw.layoutPlus = {
    create: function(){
        this._top = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-top">Add Layout</span>');
        this._bottom = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-bottom">Add Layout</span>');
        mw.$(document.body).append(this._top).append(this._bottom);

        this._top.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._top.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover');
        });
        this._bottom.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._bottom.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover')
        });
    },
    hide: function () {
        this._top.css({top: -999, left: -999});
        this._bottom.css({top: -999, left: -999});
        this.pause = false;
    },
    pause: false,
    _active: null,
    position:function(){
        var $layout = mw.$(this._active);
        var off = $layout.offset();
        var left = (off.left + ($layout.outerWidth()/2));
        this._top.css({top: off.top - 20, left: left});
        this._bottom.css({top: off.top + $layout.outerHeight(), left: left});
    },
    _prepareList:function (tip, action) {
        var scope = this;
        var items = mw.$('.modules-list li', tip);
        mw.$('input', tip).on('input', function () {
                mw.tools.search(this.value, items, function (found) {
                    $(this)[found?'show':'hide']();
                });
        });
        mw.$('.modules-list li', tip).on('click', function () {
            var id = mw.id('mw-layout-'), el = '<div id="' + id + '"></div>';
            var $active = mw.$(mw.layoutPlus._active);
            $active[action](el);

            var name = $active.attr('data-module-name');
            var template = $(this).attr('template');
            var conf = {class: mw.layoutPlus._active.className, template: template};

            /*mw.liveEditState.record({
                action: function () {
                    mw.$('#' + id).replaceWith('<div id="' + id + '"></div>');
                }
            });*/

            mw.load_module('layouts', '#' + id, function () {
                mw.wysiwyg.change(document.getElementById(id));
                mw.drag.fixes();
                /*mw.liveEditState.record({
                    action: function () {
                        mw.load_module('layouts', '#' + id, undefined, conf);
                    }
                });*/
                setTimeout(function () {
                    mw.drag.fix_placeholders();
                }, 40);
                mw.dropable.hide();
                mw.layoutPlus.mode === 'Dialog' ? mw.dialog.get(tip).remove()  : $(tip).remove();
            }, conf);
            scope.pause = false;
        });
    },
    mode: 'Dialog', //'tooltip', 'Dialog',
    showSelectorUI: function (el) {
        var scope = this;
        scope.pause = true;
        var tip = new mw[mw.layoutPlus.mode]({
            content: document.getElementById('plus-layouts-list').innerHTML,
            element: el,
            position: 'right-center',
            template: 'mw-tooltip-default mw-tooltip-insert-module',
            id: 'mw-plus-tooltip-selector',
            title: mw.lang('Select layout'),
            width: 800,
            overlay: true
        });
        scope._prepareList(document.getElementById('mw-plus-tooltip-selector'), 'before');
        $('#mw-plus-tooltip-selector input').focus();
        $('#mw-plus-tooltip-selector').addClass('active');
    },
    initSelector: function () {
        var scope = this;
        this._top.on('click', function () {
            scope.showSelectorUI(this);
        });
        this._bottom.on('click', function () {
            scope.showSelectorUI(this);
        });

    },
    handle: function () {
        var scope = this;
        mw.$(window).on('resize', function (e) {
            if (scope._active) {
                scope.position();
            }
        });
        mw.on('moduleOver ModuleClick', function (e, module) {
            if (module.dataset.type === 'layouts' && !scope.pause) {
                scope._active = module;
                scope.position();
            } else {
                scope.hide();
            }
        });
    },
    _ready: false,
    init: function () {
        if (!this._ready) {
            this._ready = true;
            this.create();
            this.handle();
            this.initSelector();
        }
    }
};

$(window).on('load', function () {
    mw.layoutPlus.init();
});

})();

(() => {
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/manage.content.js ***!
  \*********************************************************************/
mw.liveedit.manageContent = {
    //w: '95%',
   //  w: '1220px',
     w: '985px',
   // w: 'auto',
    page: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-page&recommended_parent=" + mw.settings.page_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    category: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=categories/edit_category&live_edit=true&quick_edit=false&id=mw-quick-category&recommended_parent=" + mw.settings.page_id,
          //  width: '600px',
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            overlay: true,
            title: 'New Category',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    edit: function (id, content_type, subtype, parent, category) {
        var str = "";

        if (parent) {
            str = "&recommended_parent=" + parent;
        }

        if (content_type) {
            str = str + '&content_type=' + content_type;
        }

        if (category) {
            str = str + '&category=' + category;
        }

        if (subtype) {
            str = str + '&subtype=' + subtype;
        }

        var actionType = '';

        if (id === 0) {
            actionType = 'Add';
        } else {
            actionType = 'Edit';
        }

        var actionOf = 'Content';
        if (content_type === 'post') {
            actionOf = 'Post'
        } else if (content_type === 'page') {
            actionOf = 'Page'
        } else if (content_type === 'product') {
            actionOf = 'Product'
        } else if (content_type === 'category') {
            actionOf = 'Category'
        }

        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit&live_edit=true&quick_edit=false&is-current=true&id=mw-quick-page&content-id=" + id + str,
       //     width: '800px',
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_page',
            id: 'quick_page',
            overlay: true,
            title: actionType + ' ' + actionOf,
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    page_2: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/quick_add&live_edit=true&id=mw-new-content-add-ifame",
            width: this.w,
            height: 'auto',
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    post: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-post&subtype=post&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_post',
            overlay: true,
            title: 'New Post'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    product: function () {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-product&subtype=product&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight: true,
            name: 'quick_product',
            overlay: true,
            title: 'New Product'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    }
}

})();

(() => {
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/modules.toolbar.js ***!
  \**********************************************************************/
mw.liveedit.modulesToolbar = {
    init: function (selector) {
        var items = selector || ".modules-list li[data-module-name]";
        var $items = mw.$(items);
        $items.on('mouseup touchend', function (){
            if(!document.body.classList.contains('dragStart') && !this.classList.contains('module-item-layout')) {
                if(mw.liveEditSelector.selected[0]) {
                    mw.element(mw.liveEditSelector.selected[0]).after(this.outerHTML);
                    setTimeout(function (){
                        mw.drag.load_new_modules();
                        mw.tools.scrollTo(mw.liveEditSelector.selected[0].nextElementSibling, undefined, 200)
                    }, 78)
                } else {
                    mw.notification.warning('Select element from the page or drag the <b>' + this.dataset.filter + '</b> to the desired place');
                }
            }
        });
        $items.draggable({
            revert: true,
            revertDuration: 0,
            distance: 20,
            start: function(a, b) {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw.GlobalModuleListHelper;
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();

            },
            stop: function() {
                mw.isDrag = false;
                mw.pauseSave = true;
                var el = this;
                mw.$(document.body).removeClass("dragStart");
                setTimeout(function() {
                    mw.drag.load_new_modules();
                    mw.liveedit.recommend.increase($(mw.dragCurrent).attr("data-module-name"));
                    mw.drag.toolbar_modules(el);
                }, 200);
            }
        });
        $items.on('mouseenter touchstart', function() {
            mw.$(this).draggable("option", "helper", function() {
                var el = $(this);
                var clone = el.clone(true);
                clone.appendTo(document.body);
                clone.addClass('mw-module-drag-clone');
                mw.GlobalModuleListHelper = clone[0];
                clone.css({
                    width: el.width(),
                    height: el.height()
                });
                return clone[0];
            });
        });

    }
};

})();

(() => {
/*!**************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/toolbar.js ***!
  \**************************************************************/
mw.liveedit.toolbar = {
    fixPad: function () {
        document.body.style.paddingTop = parseFloat($(document.body).css("paddingTop")) + mw.$("#live_edit_toolbar").height() + 'px';
    },
    setEditor: function(){

    },
    prepare: function () {
        mw.$("#liveedit_wysiwyg")
            .on('mousedown touchstart',function() {
                if (mw.$(".mw_editor_btn_hover").length === 0) {
                    mw.mouseDownOnEditor = true;
                    mw.$(this).addClass("hover");
                }
            })
            .on('mouseup touchend',function() {
                mw.mouseDownOnEditor = false;
                mw.$(this).removeClass("hover");
            });
        mw.$(window).scroll(function() {
            if ($(window).scrollTop() > 10) {
                mw.tools.addClass(document.getElementById('live_edit_toolbar'), 'scrolling');
            } else {
                mw.tools.removeClass(document.getElementById('live_edit_toolbar'), 'scrolling');
            }

        });
        mw.$("#live_edit_toolbar").hover(function() {
            mw.$(document.body).addClass("toolbar-hover");
        }, function() {
            mw.$(document.body).removeClass("toolbar-hover");
        });
    },
    editor: {
        init: function () {
            this.ed = document.getElementById('liveedit_wysiwyg');
            this.nextBTNS = mw.$(".liveedit_wysiwyg_next");
            this.prevBTNS = mw.$(".liveedit_wysiwyg_prev") ;
        },
        calc: {
            SliderButtonsNeeded: function (parent) {
                var t = {left: false, right: false};
                if (parent == null || !parent) {
                    return;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return t;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b > a) {
                    t.right = true;
                }
                if ($(el).offset().left < mw.$(parent).offset().left) {
                    t.left = true;
                }
                return t;
            },
            SliderNormalize: function (parent) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b < a) {
                    return (a - b);
                }
                return false;
            },
            SliderNext: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return 0;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (a < b) {
                    if ((b - step) > a) {
                        return (curr - step);
                    }
                    else {
                        return curr - (b - a);
                    }
                }
                else {
                    return curr - (b - a);
                }
            },
            SliderPrev: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (curr < 0) {
                    if ((curr + step) < 0) {
                        return (curr + step);
                    }
                    else {
                        return 0;
                    }
                }
                else {
                    return 0;
                }
            }
        },

        step: function () {
            return $(mw.liveedit.toolbar.editor.ed).width();
        },
        denied: false,
        buttons: function () {
            var b = mw.liveedit.toolbar.editor.calc.SliderButtonsNeeded(mw.liveedit.toolbar.editor.ed);
            if (!b) {
                return;
            }
            if (b.left) {
                mw.liveedit.toolbar.editor.prevBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.prevBTNS.removeClass('active');
            }
            if (b.right) {
                mw.liveedit.toolbar.editor.nextBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.nextBTNS.removeClass('active');
            }
        },
        slideLeft: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;
                var to = mw.liveedit.toolbar.editor.calc.SliderPrev(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        slideRight: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;

                var to = mw.liveedit.toolbar.editor.calc.SliderNext(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        fixConvertible: function (who) {
            who = who || ".wysiwyg-convertible";
            who = $(who);
            if (who.length > 1) {
                $(who).each(function () {
                    mw.liveedit.toolbar.editor.fixConvertible(this);
                });
                return false;
            }
            else {
                var w = $(window).width();
                var w1 = who.offset().left + who.width();
                if (w1 > w) {
                    who.css("left", -(w1 - w));
                }
                else {
                    who.css("left", 0);
                }
            }
        }
    }

};

})();

(() => {
/*!**************************************************************!*\
  !*** ./userfiles/modules/microweber/api/liveedit/widgets.js ***!
  \**************************************************************/
mw.liveedit = mw.liveedit || {};
mw.liveedit.widgets = {
    htmlEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';
        // window.open(src, "Code editor", "toolbar=no, menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=yes");
        mw.dialogIframe({
            url: src,
            title: 'Code editor',
            height: 'auto',
            width: '95%'
        });
    },
    cssEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
        return mw.dialogIframe({
            url: src,
            // width: 500,
            height:'auto',
            autoHeight: true,
            name: 'mw-css-editor-front',
            title: 'CSS Editor',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    }
};

})();

/******/ })()
;
//# sourceMappingURL=liveedit.js.map