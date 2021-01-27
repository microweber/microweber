/******/ (() => { // webpackBootstrap
(() => {
/*!************************************!*\
  !*** ../gui-css-editor/domtree.js ***!
  \************************************/
mw.DomTree = function (options) {
    var scope = this;
    this.prepare = function () {
        var defaults = {
            selector: '.edit',
            document: document,
            targetDocument: document,
            componentMatch: [
                {
                    label:  function (node) {
                        return 'Edit';
                    },
                    test: function (node) {
                        return mw.tools.hasClass(node, 'edit');
                    }
                },
                {
                    label: function (node) {
                        var icon = mw.top().live_edit.getModuleIcon(node.getAttribute('data-type'));
                        return icon + ' ' + node.getAttribute('data-mw-title') || node.getAttribute('data-type');
                    },
                    test: function (node) {
                        return mw.tools.hasClass(node, 'module');
                    }
                },
                {
                    label: 'Image',
                    test: function (node) {
                        return node.nodeName === 'IMG';
                    }
                },
                {
                    label:  function (node) {
                        var id = node.id ? '#' + node.id : '';
                        return node.nodeName.toLowerCase() ;
                    },
                    test: function (node) { return true; }
                }
            ],
            componentTypes: [
                {
                    label: 'SafeMode',
                    test: function (node) {
                        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [ 'safe-mode', 'regular-mode' ]);
                    }
                }
            ]
        };
        options = options || {};

        this.settings = $.extend({}, defaults, options);

        this.$holder = $(this.settings.element);

        this.document = this.settings.document;
        this.targetDocument = this.settings.targetDocument;

        this._selectedDomNode = null;
    };
    this.prepare();

    this.createList = function () {
        return this.document.createElement('ul');
    };

    this.createRoot = function () {
        this.root = this.createList();
        this.root.className = 'mw-defaults mw-domtree';
    };


    this._get = function (nodeOrTreeNode) {
        return nodeOrTreeNode._value ? nodeOrTreeNode : this.findElementInTree(nodeOrTreeNode);
    };

    this.select = function (node) {
        var el = this.getByNode(node);
        if (el) {
            this.selected(el);
            this.openParents(el);
            this._scrollTo(el);
        }
    };

    this.toggle = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        this[ li._opened ? 'close' : 'open'](li);
    };

    this._opened = [];

    this.open = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = true;
        li.classList.add('expand');
        if(this._opened.indexOf(li._value) === -1) {
            this._opened.push(li._value);
        }
    };
    this.close = function (nodeOrTreeNode) {
        var li = this._get(nodeOrTreeNode);
        li._opened = false;
        li.classList.remove('expand');
        var ind = this._opened.indexOf(li._value);
        if( ind !== -1 ) {
            this._opened.splice(ind, ind)
        }
    };

    this._scrollTo = function (el) {
        setTimeout(function () {
            scope.$holder.stop().animate({
                scrollTop: (scope.$holder.scrollTop() + ($(el).offset().top - scope.$holder.offset().top)) - (scope.$holder.height()/2 - 10)
            });
        }, 55);
    };

    this.openParents = function (node) {
        node = this._get(node);
        while(node && node !== this.root) {
            if(node.nodeName === 'LI'){
                this.open(node);
            }
            node = node.parentNode;
        }
    };
    this.selected = function (node) {
        if (typeof node === 'undefined') {
            return this._selectedDomNode;
        }
        mw.$('.selected', this.root).removeClass('selected');
        node.classList.add('selected');
        this._selectedDomNode = node;
    };

    this.getByNode = function (el) {
        var all = this.root.querySelectorAll('li');
        var l = all.length, i = 0;
        for ( ; i < l; i++) {
            if (all[i]._value === el) {
                return all[i];
            }
        }
    };

    this.getByTreeNode = function (treeNode) {
        return treeNode._value;
    };

    this.allDomNodes = function () {
        return Array.from(this.map().keys());
    };

    this.allTreeNodes = function () {
        return Array.from(this.map().values());
    };

    this.emptyTreeNode = function (node) {
        var li = this.getByNode(node);
        $(li).empty();
        return li;
    };

    this.refresh = function (node) {
        var item = this.emptyTreeNode(node);
        this.createChildren(node, item);
    };

    this._currentTarget = null;
    this.createItemEvents = function () {
        $(this.root)
            .on('mousemove', function (e) {
                var target = e.target;
                if(target.nodeName !== 'LI') {
                    target = target.parentNode;
                }
                if(scope._currentTarget !== target) {
                    scope._currentTarget = target;
                    mw.$('li.hover', scope.root).removeClass('hover');
                    target.classList.add('hover');
                    if(scope.settings.onHover) {
                        scope.settings.onHover.call(scope, e, target, target._value);
                    }
                }
            })
            .on('mouseleave', function (e) {
                mw.$('li', scope.root).removeClass('hover');
            })
            .on('click', function (e) {
                var target = e.target;

                if(target.nodeName !== 'LI') {
                    target = mw.tools.firstParentWithTag(target, 'li');
                    scope.toggle(target);
                }
                scope.selected(target);
                if(target.nodeName === 'LI' && scope.settings.onSelect) {
                    scope.settings.onSelect.call(scope, e, target, target._value);
                }
            });
    };

    this.map = function (node, treeNode) {
        if (!this._map) {
            this._map = new Map();
        }
        if(!node) {
            return this._map;
        }
        if (!treeNode) {
            return this._map.get(node);
        }
        if (!this._map.has(node)) {
            this._map.set(node, treeNode);
        }
    };

    this.createItem = function (item) {
        if(!this.validateNode(item)) {
            return;
        }
        var li = this.document.createElement('li');
        li._value = item;
        li.className = 'mw-domtree-item' + (this._selectedDomNode === item ? ' active' : '');
        var dio = item.children.length ? '<i class="mw-domtree-item-opener"></i>' : '';
        li.innerHTML = dio + '<span class="mw-domtree-item-label">' + this.getComponentLabel(item) + '</span>';
        return li;
    };

    this.getComponentLabel = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (  ; i < all.length; i++ ) {
            if( all[i].test(node)) {
                return typeof all[i].label === 'string' ? all[i].label : all[i].label.call(this, node);
            }
        }
    };
    this.isComponent = function (node) {
        var all = this.settings.componentMatch, i = 0;
        for (  ; i < all.length; i++ ) {
            if( all[i].test(node)) {
                return true;
            }
        }
        return false;
    };

    this.validateNode = function (node) {
        if(node.nodeType !== 1){
            return false;
        }
        var tag = node.nodeName;
        if(tag === 'SCRIPT' || tag === 'STYLE' || tag === 'LINK' || tag === 'BR') {
            return false;
        }
        return this.isComponent(node);
    };

    this.create = function () {
        var all = this.targetDocument.querySelectorAll(this.settings.selector);
        var i = 0;
        for (  ; i < all.length; i++ ) {
            var item = this.createItem(all[i]);
            if(item) {
                this.root.appendChild(item);
                this.createChildren(all[i], item);
            }
        }
        this.createItemEvents();
        $(this.settings.element).empty().append(this.root).resizable({
            handles: "s",
            start: function( event, ui ) {
                ui.element.css('maxHeight', 'none');
            }
        });
    };

    this.createChildren = function (node, parent) {
        if(!parent) return;
        var list = this.createList();
        var curr = node.children[0];
        while (curr) {
            var item = this.createItem(curr);
            if (item) {
                list.appendChild(item);
                if (curr.children.length) {
                    this.createChildren(curr, item);
                }
            }
            curr = curr.nextElementSibling;
        }
        parent.appendChild(list);
    };

    this.init = function () {
        this.createRoot();
        this.create();
    };

    this.init();

};

})();

(() => {
/*!**********************************************!*\
  !*** ../gui-css-editor/stylesheet.editor.js ***!
  \**********************************************/
mw.require('libs/cssjson/cssjson.js');


mw.liveeditCSSEditor = function (config) {
    var scope = this;
    config = config || {};
    config.document = config.document || document;
    var node = document.querySelector('link[href*="live_edit"]');
    var defaults = {
        cssUrl: node ? node.href : null,
        saveUrl: mw.settings.api_url + "current_template_save_custom_css"
    };
    this.settings = $.extend({}, defaults, config);

    this.json = null;

    this.getByUrl = function (url, callback) {
        return $.get(url, function (css) {
            callback.call(this, css)
        });
    };

    this.getLiveeditCSS = function () {
        if( this.settings.cssUrl ) {
            this.getByUrl( this.settings.cssUrl, function (css) {
                if(/<\/?[a-z][\s\S]*>/i.test(css)) {
                    scope.json = {};
                    scope._css = '';
                } else {
                    scope.json = CSSJSON.toJSON(css);
                    scope._css = css;
                }
                $(scope).trigger('ready');
            });
        }
        else {
            scope.json = {};
            scope._css = '';
            $(scope).trigger('ready');
        }
    };


    this._cssTemp = function (json) {
        var css = CSSJSON.toCSS(json);
        if(!mw.liveedit._cssTemp) {
            mw.liveedit._cssTemp = mw.tools.createStyle('#mw-liveedit-dynamic-temp-style', css, document.body);
            mw.liveedit._cssTemp.id = 'mw-liveedit-dynamic-temp-style';
        } else {
            mw.liveedit._cssTemp.innerHTML = css;
        }
    };

    this.changed = false;
    this._temp = {children: {}, attributes: {}};
    this.temp = function (node, prop, val) {
        this.changed = true;
        if(node.length) {
            node = node[0];
        }
        var sel = mw.tools.generateSelectorForNode(node);
        if(!this._temp.children[sel]) {
            this._temp.children[sel] = {};
        }
        if (!this._temp.children[sel].attributes ) {
            this._temp.children[sel].attributes = {};
        }
        this._temp.children[sel].attributes[prop] = val;
        this._cssTemp(this._temp);
    };

    this.timeOut = null;

    this.save = function () {
        this.json = $.extend(true, {}, this.json, this._temp);
        this._css = CSSJSON.toCSS(this.json).replace(/\.\./g, '.').replace(/\.\./g, '.');
    };

    this.publish = function (callback) {
        var css = {
            css_file_content: this.getValue()
        };
        $.post(this.settings.saveUrl, css, function (res) {
            scope.changed = false;
            if(callback) {
                callback.call(this, res);
            }
        });
    };

    this.publishIfChanged = function (callback) {
        if(this.changed) {
            this.publish(callback);
        }
    };

    this.getValue = function () {
        this.save();
        return this._css;
    };

    this.init = function () {
        this.getLiveeditCSS();
    };

    this.init();

};


})();

(() => {
/*!***********************************!*\
  !*** ../libs/rangy/rangy-core.js ***!
  \***********************************/
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
/*!**********************************************!*\
  !*** ../libs/rangy/rangy-cssclassapplier.js ***!
  \**********************************************/
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
/*!***************************************************!*\
  !*** ../libs/rangy/rangy-selectionsaverestore.js ***!
  \***************************************************/
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
/*!*****************************************!*\
  !*** ../libs/rangy/rangy-serializer.js ***!
  \*****************************************/
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
/*!****************************!*\
  !*** ../liveedit/@drag.js ***!
  \****************************/
mw.drag = {
    _fixDeniedParagraphHierarchySelector: ''
    + '.edit p h1,.edit p h2,.edit p h3,'
    + '.edit p h4,.edit p h5,.edit p h6,'
    + '.edit p p,.edit p ul,.edit p ol,'
    + '.edit p header,.edit p form,.edit p article,'
    + '.edit p aside,.edit p blockquote,.edit p footer,.edit p div',
    fixDeniedParagraphHierarchy: function (root) {
        root = root || document.body;
        var all = root.querySelectorAll(mw.drag._fixDeniedParagraphHierarchySelector);
        if (all.length) {
            var i = 0;
            for ( ; i < all.length; i++ ) {
                var the_parent = mw.tools.firstParentWithTag(all[i], 'p');
                mw.tools.setTag(the_parent, 'div');
            }
        }
    },
    create_columns: function(selector, $numcols) {
        if (!$(selector).hasClass("active")) {

            mw.$(mw.drag.columns.resizer).hide();

            var id = mw._activeRowOver.id;

            mw.$(selector).addClass("active");
            var $el_id = id !== '' ? id : mw.settings.mw - row_id;

            mw.settings.sortables_created = false;
            var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").length;

            if ($numcols === 0) {
                $numcols = 1;
            }
            $numcols = parseInt($numcols);
            if ($exisintg_num === 0) {
                $exisintg_num = 1;
            }
            if ($numcols !== $exisintg_num) {
                if ($numcols > $exisintg_num) { //more columns
                    var i = $exisintg_num;
                    for (; i < $numcols; i++) {
                        var new_col = document.createElement('div');
                        new_col.className = 'mw-col';
                        new_col.innerHTML = '<div class="mw-col-container"><div class="mw-empty element" id="element_'+mw.random()+'"></div></div>';
                        mw.$('#' + $el_id).append(new_col);
                        mw.drag.fix_placeholders(true, '#' + $el_id);
                    }
                    //mw.resizable_columns();
                } else { //less columns
                    var $cols_to_remove = $exisintg_num - $numcols;
                    if ($cols_to_remove > 0) {
                        var fragment = document.createDocumentFragment(),
                            last_after_remove;

                        mw.$('#' + $el_id).children(".mw-col").each(function(i) {
                            if (i === ($numcols - 1)) {
                                last_after_remove = mw.$(this);

                            } else {
                                if (i > ($numcols - 1)) {
                                    if (this.querySelector('.mw-col-container') !== null) {
                                        mw.tools.foreachChildren(this, function() {
                                            if (mw.tools.hasClass(this.className, 'mw-col-container')) {
                                                fragment.appendChild(this);
                                            }
                                        });
                                    }
                                    mw.$(this).remove();
                                }
                            }
                        });
                        var last_container = last_after_remove.find(".mw-col-container");

                        var nodes = fragment.childNodes,
                            i = 0,
                            l = nodes.length;

                        for (; i < l; i++) {
                            var node = nodes[i];
                            mw.$('.empty-element, .ui-resizable-handle', node).remove();
                            last_container.append(node.innerHTML);
                        }

                        //last_after_remove.resizable("destroy");
                        mw.$('#' + $el_id).children(".empty-element").remove();
                        mw.drag.fix_placeholders(true, '#' + $el_id);

                    }
                }

                $exisintg_num = mw.$('#' + $el_id).children(".mw-col").size();
                var $eq_w = 100 / $exisintg_num;
                var $eq_w1 = $eq_w;
                mw.$('#' + $el_id).children(".mw-col").width($eq_w1 + '%');
            }
        }
        mw.wysiwyg.nceui();
    },
    replace:function(el, dir, callback){
        var prev = el[dir]();
        var thisOff = el.offset();
        var prevOff = prev.offset();
        prev
            .css({
                position:'relative'
            })
            .animate({top:thisOff.top-prevOff.top });

        el
            .css({
                position:'relative'
            })
            .animate({top:prevOff.top - thisOff.top}, function(){
                if(dir === 'prev'){
                    prev.before(el);
                }
                else{
                    prev.after(el);
                }

                el.css({'top': '', 'position':''})
                prev.css({'top': '', 'position':''});
                mw.wysiwyg.change(el[0])
                if(!!callback){
                    setTimeout(function(){
                        callback.call()
                    }, 10)
                }

            })
    },
    external_grids_col_classes: ['row', 'col-lg-1', 'col-lg-10', 'col-lg-11', 'col-lg-12', 'col-lg-2', 'col-lg-3', 'col-lg-4', 'col-lg-5', 'col-lg-6', 'col-lg-7', 'col-lg-8', 'col-lg-9', 'col-md-1', 'col-md-10', 'col-md-11', 'col-md-12', 'col-md-2', 'col-md-3', 'col-md-4', 'col-md-5', 'col-md-6', 'col-md-7', 'col-md-8', 'col-md-9', 'col-sm-1', 'col-sm-10', 'col-sm-11', 'col-sm-12', 'col-sm-2', 'col-sm-3', 'col-sm-4', 'col-sm-5', 'col-sm-6', 'col-sm-7', 'col-sm-8', 'col-sm-9', 'col-xs-1', 'col-xs-10', 'col-xs-11', 'col-xs-12', 'col-xs-2', 'col-xs-3', 'col-xs-4', 'col-xs-5', 'col-xs-6', 'col-xs-7', 'col-xs-8', 'col-xs-9'],
    external_css_no_element_classes: ['container','navbar', 'navbar-header', 'navbar-collapse', 'navbar-static', 'navbar-static-top', 'navbar-default', 'navbar-text', 'navbar-right', 'navbar-center', 'navbar-left', 'nav navbar-nav', 'collapse', 'header-collapse', 'panel-heading', 'panel-body', 'panel-footer'],
    section_selectors: ['.module-layouts'],
    external_css_no_element_controll_classes: ['container', 'container-fluid', 'edit','noelement','no-element','allow-drop','nodrop', 'mw-open-module-settings','module-layouts'],
    onCloneableControl:function(target, isOverControl){
        if(!this._onCloneableControl){
            this._onCloneableControl = document.createElement('div');
            this._onCloneableControl.className = 'mw-cloneable-control';
            var html = '';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-prev" title="Move backward"></span>';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-plus" title="Clone"></span>';
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-minus" title="Remove"></span>' ;
            html += '<span class="mw-cloneable-control-item mw-cloneable-control-next" title="Move forward"></span>';
            this._onCloneableControl.innerHTML = html;

            document.body.appendChild(this._onCloneableControl);
            mw.$('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent()
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                var parser = mw.tools.parseHtml(mw.drag._onCloneableControl.__target.outerHTML).body;
                var all = parser.querySelectorAll('[id]'), i = 0;
                for( ; i<all.length; i++){
                    all[i].id = 'mw-cl-id-' + mw.random();
                }
                mw.$(mw.drag._onCloneableControl.__target).after(parser.innerHTML);
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-minus', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).fadeOut(function(){
                    mw.wysiwyg.change(this);
                    mw.$(this).remove();
                    mw.liveEditState.record({
                        target: $t[0],
                        value: $t[0].innerHTML
                    });
                });
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-next', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).next().after(mw.drag._onCloneableControl.__target)
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
            mw.$('.mw-cloneable-control-prev', this._onCloneableControl).on('click', function(){
                var $t = mw.$(mw.drag._onCloneableControl.__target).parent();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.$(mw.drag._onCloneableControl.__target).prev().before(mw.drag._onCloneableControl.__target)
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
                mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
                mw.drag.onCloneableControl('hide');
            });
        }
        var clc = mw.$(this._onCloneableControl);
        if(target == 'hide'){
            clc.hide();
        }
        else{
            clc.show();
            this._onCloneableControl.__target = target;

            var next = mw.$(this._onCloneableControl.__target).next();
            var prev = mw.$(this._onCloneableControl.__target).prev();
            var el = mw.$(target), off = el.offset();


            if(next.length == 0){
                mw.$('.mw-cloneable-control-next', clc).hide();
            }
            else{
                mw.$('.mw-cloneable-control-next', clc).show();
            }
            if(prev.length == 0){
                mw.$('.mw-cloneable-control-prev', clc).hide();
            }
            else{
                mw.$('.mw-cloneable-control-prev', clc).show();
            }
            var leftCenter = (off.left > 0 ? off.left : 0) + (el.width()/2 - clc.width()/2) ;
            clc.show();
            if(isOverControl){
                return;
            }
            clc.css({
                top: off.top > 0 ? off.top : 0 ,
                //left: off.left > 0 ? off.left : 0
                left: leftCenter
            });


            var cloner = document.querySelector('.mw-cloneable-control');
            if(cloner) {
                mw._initHandles.getAll().forEach(function (curr) {
                    masterRect = curr.wrapper.getBoundingClientRect();
                    var clonerect = cloner.getBoundingClientRect();

                    if (mw._initHandles.collide(masterRect, clonerect)) {
                        cloner.style.top = (parseFloat(curr.wrapper.style.top) + 10) + 'px';
                        cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                    }
                });
            }
        }

    },
    noop: document.createElement('div'),
    create: function() {

        var edits = document.body.querySelectorAll(".edit"),
            elen = edits.length,
            ei = 0;
        for (; ei < elen; ei++) {
            var els = edits[ei].querySelectorAll('p,div,h1,h2,h3,h4,h5,h6,section,img'),
                i = 0,
                l = els.length;
            for (; i < l; i++) {
                var el = els[i];

                if( !mw.drag.target.canBeElement(el) ){
                    continue;
                }
                var cls = el.className;

                var isEl = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['element', 'module'])
                    && !mw.tools.hasAnyOfClasses(el, ['mw-col','mw-col', 'mw-row', 'mw-zone']);
                if (isEl) {
                    mw.tools.addClass(el, 'element');
                }
            }
        }
        mw.$("#live_edit_toolbar_holder .module").removeClass("module");

        mw.handlerMouse = {
            x: 0,
            y: 0
        };

        mw.$(document.body).on('mousemove touchmove', function(event) {

            mw.dragSTOPCheck = false;
            if (!mw.settings.resize_started) {

                mw.emouse = mw.event.page(event);

                /*var targetNode;
                targetNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                if (targetNode && targetNode.isContentEditable) {
                    mw.tools.addClass(this, 'isTyping');
                    return;
                }*/

                mw.mm_target = event.target;
                mw.$mm_target = mw.$(mw.mm_target);

                if (!mw.isDrag) {
                    if (mw.liveEditSelectMode === 'element') {
                        //if(mw.tools.distance(mw.handlerMouse.x, mw.handlerMouse.y, mw.emouse.x, mw.emouse.y) > 20) {

                            mw.tools.removeClass(this, 'isTyping');
                            mw.handlerMouse = Object.assign({}, mw.emouse);
                            mw.liveEditHandlers(event);


                        //}
                    }
                } else {
                    var sidebar = document.getElementById('live_edit_side_holder');
                    if(sidebar && sidebar.contains && sidebar.contains(mw.mm_target)){
                        mw.dropable.hide();
                        mw.ea.data.target = null;
                    } else {
                        mw.ea.data.currentGrabbed = mw.dragCurrent;
                        mw.tools.removeClass(this, 'isTyping');
                        mw.ea.interactionAnalizer(event);
                        mw.$(".currentDragMouseOver").removeClass("currentDragMouseOver");
                        mw.$(mw.currentDragMouseOver).addClass("currentDragMouseOver");
                    }

                }
            }
        });
        mw.dropables.prepare();

        mw.drag.fix_placeholders(true);
        mw.drag.fixes();

        mw.$(document.body).on('mouseup touchend', function(event) {
            mw.mouseDownStarted = false;
            if (mw.isDrag && mw.dropable.is(":hidden")) {
                mw.$(".ui-draggable-dragging").css({
                    top: 0,
                    left: 0
                });
            }
            mw.$(this).removeClass("not-allowed");
        });
        mw.$(document.body).on('mousedown touchstart', function(event) {
            var target = event.target;
            if ($(target).hasClass("image_free_text")) {
                mw.image._dragcurrent = target;
                mw.image._dragparent = target.parentNode;
                var pagePos = mw.event.page(event);
                mw.image._dragcursorAt.x = pagePos.x- target.offsetLeft;
                mw.image._dragcursorAt.y = pagePos.y - target.offsetTop;
                target.startedY = target.offsetTop - target.parentNode.offsetTop;
                target.startedX = target.offsetLeft - target.parentNode.offsetLeft;
            }

            if ($(".desc_area_hover").length === 0) {
                mw.$(".desc_area").hide();
            }
            if (mw.tools.hasClass(event.target.className, 'mw-open-module-settings')) {

                if(!mw.settings.live_edit_open_module_settings_in_sidebar){
                    mw.drag.module_settings(mw.tools.firstParentOrCurrentWithAnyOfClasses(event.target, ['module']))
                } else {
                    var target = mw.tools.firstParentWithClass(event.target, 'module') ;
                    mw.liveNodeSettings.set('module', target);
                }
            }

            if (!mw.tools.hasParentsWithTag(event.target, 'TABLE') && !mw.tools.hasParentsWithClass(event.target, 'mw-inline-bar')) {
                mw.$(mw.liveedit.inline.tableControl).hide();
                mw.$(".tc-activecell").removeClass('tc-activecell');
            }
        });

    },

    init: function(selector, callback) {
        mw.drag.the_drop();
    },
    properFocus: function(event) {
        var tofocus;
        if (mw.tools.hasClass(event.target, 'mw-row') || mw.tools.hasClass(event.target, 'mw-col')) {
            if (mw.tools.hasClass(event.target, 'mw-col')) {
                tofocus = event.target.querySelector('.mw-col-container');
            } else {
                var i = 0,
                    cols = event.target.children,
                    l = cols.length;
                for (; i < l; i++) {
                    var _cleft = mw.$(cols[i]).offset().left;
                    var ePos = mw.event.page(event);
                    if (_cleft < ePos.x && (_cleft + cols[i].clientWidth) > ePos.x) {
                        tofocus = cols[i].querySelector('.mw-col-container');
                        if (tofocus === null) {
                            cols[i].innerHTML = '<div class="mw-col-container">' + cols[i].innerHTML + '</div>';
                        }
                        tofocus = cols[i].querySelector('.mw-col-container');
                        break;
                    }
                }
            }
            if (!!tofocus && tofocus.querySelector('.element') !== null) {
                var arr = tofocus.querySelectorAll('.element'),
                    l = arr.length;
                tofocus = arr[l - 1];

            }
            if (!!tofocus) {
                var range = document.createRange();
                var sel = window.getSelection();
                range.selectNodeContents(tofocus);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    },

    toolbar_modules: function(selector) {
        mw.liveedit.modulesToolbar.init(selector);
    },
    the_drop: function() {



        if (!$(document.body).hasClass("bup")) {
            mw.$(document.body).addClass("bup");



            mw.$(document.body).on("mouseup touchend", function(event) {
                mw.image._dragcurrent = null;
                mw.image._dragparent = null;
                var sliders = document.getElementsByClassName("canvas-slider"),
                    len = sliders.length,
                    i = 0;
                for (; i < len; i++) {
                    sliders[i].isDrag = false;
                }

                if((event.type === 'mouseup' || event.type === 'touchend') && mw.liveEditSelectMode === 'none'){
                    mw.liveEditSelectMode = 'element';
                }
                if (!mw.isDrag) {
                    var target = event.target;
                    var componentsClasses = [
                        'element',
                        'safe-element',
                        'module',
                        'plain-text'
                    ];

                    var currentComponent = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, componentsClasses);
                    var fonttarget = mw.liveedit.data.get('mouseup', 'isIcon');

                    if( mw.tools.hasAnyOfClassesOnNodeOrParent(target, componentsClasses)) {
                        if (currentComponent && !fonttarget) {

                            var order = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['safe-mode', 'module']);
                            if(mw.tools.hasClass(currentComponent, 'module')){
                                mw.trigger("ComponentClick", [target, 'module']);
                            }
                            else if (mw.wysiwyg.isSelectionEditable() && !mw.tools.hasAnyOfClasses(target, componentsClasses) && order) {
                                mw.trigger("ComponentClick", [target, 'element']);
                            }
                            else {
                                if (!mw.tools.hasAnyOfClasses(target, componentsClasses)) {
                                    var ctype;
                                    var has = componentsClasses.filter(function (item) {
                                        return currentComponent.classList.contains(item)
                                    });
                                    mw.trigger("ComponentClick", [currentComponent, has[0]]);
                                }
                            }
                        }

                        var el = mw.tools.firstParentOrCurrentWithClass(target, 'element');

                        var safeEl = mw.tools.firstParentOrCurrentWithClass(target, 'safe-element');
                        var moduleEl = mw.tools.firstParentOrCurrentWithClass(target, 'module');

                        if ($(target).hasClass("plain-text")) {
                            mw.trigger("PlainTextClick", target);
                        } else if (el) {
                            mw.trigger("ElementClick", [el, event]);
                        }

                        if (safeEl) {
                            mw.trigger("SafeElementClick", [safeEl, event]);
                        }

                        if (moduleEl) {
                            mw.trigger("ModuleClick", [moduleEl, event]);
                        }
                    }

                    if (fonttarget && !mw.tools.hasAnyOfClasses(target, ['element', 'module'])) {
                        if ((fonttarget.tagName === 'I' || fonttarget.tagName === 'SPAN') && !mw.tools.hasParentsWithClass(fonttarget, 'dropdown')) {
                            if(mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(fonttarget, ['edit', 'module'])) {
                                mw.trigger("IconElementClick", fonttarget);
                                mw.trigger("ComponentClick", [fonttarget, 'icon']);
                            }
                        }
                    }

                    if ($(target).hasClass("mw_item")) {
                        mw.trigger("ItemClick", target);
                    } else if (mw.tools.hasParentsWithClass(target, 'mw_item')) {
                        mw.trigger("ItemClick", mw.$(target).parents(".mw_item")[0]);
                    }
                    if (target.tagName === 'IMG' && mw.tools.hasParentsWithClass(target, 'edit')) {
                        var order = mw.tools.parentsOrder(mw.mm_target, ['edit', 'module']);
                        if ((order.module == -1) || (order.edit > -1 && order.edit < order.module)) {
                            if (!mw.tools.hasParentsWithClass(target, 'mw-defaults')) {
                                mw.trigger("ImageClick", target);
                            }
                            mw.wysiwyg.select_element(target);
                        }
                    }
                    if (target.tagName === 'BODY') {
                        mw.trigger("BodyClick", target);
                    }


                    var isTd =  target.tagName === 'TD' ? target : mw.tools.firstParentWithTag(target, 'td');
                    if(!!isTd){
                        if(mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['edit', 'module'])){
                            mw.trigger("TableTdClick", target);
                        }
                    }

                    if (mw.tools.hasClass(target, 'mw-empty') || mw.tools.hasParentsWithClass(target, 'mw-empty')) {

                    } else {

                    }
                    if (mw.tools.hasClass(target, 'mw-empty') && target.innerHTML.trim() !== '') {
                        target.className = 'element';
                    }
                    mw.drag.properFocus(event);
                }
                if (mw.isDrag) {
                    mw.isDrag = false;


                    mw.wysiwyg.change(mw.currentDragMouseOver);
                    mw.$(mw.currentDragMouseOver).removeClass("currentDragMouseOver");

                    mw._initHandles.hideAll();

                    setTimeout(function() {
                        if(mw.ea.data.target && mw.ea.data.currentGrabbed){
                            if(!!mw.ea.data.dropableAction && !!mw.ea.data.target && !!mw.ea.data.currentGrabbed){
                                var ed = mw.tools.firstParentOrCurrentWithClass(mw.ea.data.target, 'edit');
                                var prev = mw.ea.data.currentGrabbed.parentNode;
                                var rec = {
                                    target: ed,
                                    value: ed.innerHTML
                                };
                                if(prev){
                                    var prevDoc = mw.tools.parseHtml(prev.innerHTML);
                                    mw.$(prevDoc.querySelector('.mw_drag_current')).css({
                                        visibility: 'hidden',
                                        opacity: 0
                                    });
                                    rec.prev = prev;
                                    rec.prevValue = prevDoc.body.innerHTML;
                                }

                                mw.liveEditState.record(rec);
                                mw.$(mw.ea.data.target)[mw.ea.data.dropableAction](mw.ea.data.currentGrabbed);




                                setTimeout(function(ed) {
                                    var nrec = {
                                        target: ed,
                                        value: ed.innerHTML
                                    };
                                    if(prev){
                                        var prevDoc = mw.tools.parseHtml(prev.innerHTML);
                                        mw.$(prevDoc.querySelector('.mw_drag_current')).css({
                                            visibility: 'hidden',
                                            opacity: 0
                                        });
                                        nrec.prev = prev;
                                        nrec.prevValue = prevDoc.body.innerHTML;
                                    }
                                    mw.liveEditState.record(nrec);
                                }, 50, ed);
                            }
                            else{

                            }
                        }
                        mw.drag.fixes();
                        setTimeout(function() {
                            mw.drag.fix_placeholders();
                            mw.ea.afterAction();
                            if(mw.liveEditDomTree) {
                                if(mw.ea.data.target) {
                                    mw.liveEditDomTree.refresh(mw.ea.data.target.parentNode)
                                }
                            }
                        }, 40);
                        mw.dropable.hide();

                    }, 77);


                    setTimeout(function () {

                        if (mw.have_new_items) {
                            mw.drag.load_new_modules();
                        }
                    }, 120)
                }
            });
        } //toremove
    },


    /**
     * Various fixes
     *
     * @example mw.drag.fixes()
     */
    fixes: function() {
        mw.$(".edit .mw-col, .edit .mw-row").height('auto');
        mw.$(mw.dragCurrent).css({
            top: '',
            left: ''
        });
        var more_selectors = '';
        //var cols = mw.drag.external_grids_col_classes;
        var cols = [];
        var index;
        for (index = cols.length - 1; index >= 0; --index) {
            more_selectors += ',.edit .row > .' + cols[index];
        }
        setTimeout(function() {
            mw.$(".edit .mw-col" + more_selectors).each(function() {
                var el = mw.$(this);
                if (el.children().length === 0 || (el.children('.empty-element').length > 0) || el.children('.ui-draggable-dragging').length > 0) {
                    el.height('auto');
                    if (el.height() < el.parent().height()) {
                        el.height(el.parent().height());
                    } else {
                        el.height('auto');
                    }
                } else {
                    el.children('.empty-element').height('auto');
                    el.height('auto');
                    el.parents('.mw-row:first').height('auto')
                }

                el.height('auto');
                var mwr = mw.tools.firstParentWithClass(this, 'mw-row');
                if (!!mwr) {
                    mwr.style.height = 'auto';
                }
                mw.drag.fixDeniedParagraphHierarchy();

            });
        }, 222);

        var els = document.querySelectorAll('div.element'),
            l = els.length,
            i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                if (els[i].querySelector('p,div,li,h1,h2,h3,h4,h5,h6,figure,img') === null && !mw.tools.hasClass(els[i], 'plain-text')) {
                    if (!mw.tools.hasClass(els[i].className, 'nodrop') && !mw.tools.hasClass(els[i].className, 'mw-empty')) {
                        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(els[i], ['safe-mode'])){
                            els[i].innerHTML = '<p class="element">' + els[i].innerHTML + '</p>';
                        }
                    }
                }
            }
        }
    },
    /**
     * fix_placeholders in the layout
     *
     * @example mw.drag.fix_placeholders(isHard , selector)
     */
    fix_placeholders: function(isHard, selector) {
        selector = selector || '.edit .row';

        var more_selectors2 = 'div.col-md';
        var a = mw.drag.external_grids_col_classes;
        var index;
        for (index = a.length - 1; index >= 0; --index) {
            more_selectors2 += ',div.' + a[index];
        }
        mw.$(selector).each(function() {
            var el = mw.$(this);
            el.children(more_selectors2).each(function() {
                var empty_child = mw.$(this).children('*');
                if (empty_child.size() == 0) {
                    mw.$(this).append('<div class="element" id="mw-element-' + mw.random() + '">' + '</div>');
                    var empty_child = mw.$(this).children("div.element");
                }
            });
        });

    },
    /**
     * Removes contentEditable for ALL elements
     *
     * @example mw.drag.edit_remove();
     */
    edit_remove: function() {

        //	   	mw.$('.edit [contenteditable]').removeAttr("contenteditable");

    },

    target :  {

        canBeElement: function(target) {
            var el = target;
            var noelements = ['mw-ui-col', 'mw-col-container', 'mw-ui-col-container'];

            var noelements_bs3 = mw.drag.external_grids_col_classes;
            var noelements_ext = mw.drag.external_css_no_element_classes;
            var noelements_drag = mw.drag.external_css_no_element_controll_classes;
            var section_selectors = mw.drag.section_selectors;
            var icon_selectors =  mw.wysiwyg.fontIconFamilies;

            noelements = noelements.concat(noelements_bs3);
            noelements = noelements.concat(noelements_ext);
            noelements = noelements.concat(noelements_drag);
            noelements = noelements.concat(section_selectors);
            noelements = noelements.concat(icon_selectors);

            return mw.tools.hasAnyOfClasses(el, noelements);
        },
        canBeEditable: function(el) {
            return el.isContentEditable || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit','module']);
        }
    },

    fancynateLoading: function(module) {
        mw.$(module).addClass("module_loading");
        setTimeout(function() {
            mw.$(module).addClass("module_activated");
            setTimeout(function() {
                mw.$(module).removeClass("module_loading module_activated");
            }, 510);
        }, 150);
    },

    /**
     * Scans for new dropped modules and loads them
     *
     * @example mw.drag.load_new_modules()
     * @return void
     */
    load_new_modules: function(callback) {
        return mw.ea.afterAction();
    },

    module_view: function(view) {
        var modal = mw.drag.module_settings(false, view);
        return modal;
    },
    module_settings: function(a, view) {
        return mw.tools.module_settings(a, view);
    },
    current_module_settings_tooltip_show_on_element: function(element_id, view, type) {
        if (!element_id) {
            return;
        }

        if (mw.$('#' + element_id).length === 0) {
            return;
        }

        var curr = mw._activeModuleOver;
        var tooltip_element = mw.$("#" + element_id);
        var attributes = {};
        var type = type || 'modal';
        $.each(curr.attributes, function(index, attr) {
            attributes[attr.name] = attr.value;
        });
        var data1 = attributes;
        var module_type = null;
        if (data1['data-type']) {
            module_type = data1['data-type'];
            data1['data-type'] = data1['data-type'] + '/admin';
        }
        if (data1['data-module-name']) {
            delete(data1['data-module-name']);
        }
        if (data1['type']) {
            module_type = data1['type'];
            data1['type'] = data1['type'] + '/admin';
        }
        if (module_type != null && view) {
            data1['data-type'] = data1['type'] = module_type + '/' + view;
        }

        if (data1['class']) {
            delete(data1['class']);
        }
        if (data1['style']) {
            delete(data1['style']);
        }
        if (data1.contenteditable) {
            delete(data1.contenteditable);
        }
        data1.live_edit = 'true';
        data1.module_settings = 'true';
        if (view != undefined) {
            data1.view = view;
        } else {
            data1.view = 'admin';
        }
        if (data1.from_url == undefined) {
            data1.from_url = window.parent.location;
        }
        var modal_name = 'module-settings-' + curr.id;
        if (typeof(data1.view.hash) === 'function') {
            var modal_name = 'module-settings-' + curr.id + (data1.view.hash());
        }

        if (mw.$('#' + modal_name).length > 0) {
            var m = mw.$('#' + modal_name)[0];
            m.scrollIntoView();
            mw.tools.highlight(m);
            return false;
        }
        var src = mw.settings.site_url + "api/module?" + json2url(data1);

        if (type === 'modal') {
            var modal = mw.top().dialogIframe({
                url: src,
                width: 532,
                height: 150,
                name: modal_name,
                title: '',
                callback: function() {
                    mw.$(this.container).attr('data-settings-for-module', curr.id);
                }
            });
            return modal;
        }
        if (type === 'tooltip') {
            var id = 'mw-tooltip-iframe-'+ mw.random()
            mw.tooltip({
                id: 'module-settings-tooltip-' + modal_name,
                group: 'module_settings_tooltip_show_on_btn',
                close_on_click_outside: true,
                content: '<iframe id="'+id+'" frameborder="0" class="mw-tooltip-iframe" src="' + src + '" scrolling="auto"></iframe>',
                element: tooltip_element
            });
            mw.tools.iframeAutoHeight(document.querySelector('#'+id))
        }

    },

    /**
     * Loads the module in the given dom element by the $update_element selector .
     *
     * @example mw.drag.load_module('user_login', '#login_box')
     * @param $module_name
     * @param $update_element
     * @return void
     */
    load_module: function($module_name, $update_element) {
        var attributes = {};
        attributes.module = $module_name;
        var url1 = mw.settings.site_url + 'api/module';
        mw.$($update_element).load_modules(url1, attributes, function() {
            window.mw_sortables_created = false;
        });

    },
    /**
     * Deletes element by id or selector
     *
     * @method mw.edit.delete_element(idobj)
     * @param Element id or selector
     */
    delete_element: function(idobj, c) {
        mw.tools.confirm(mw.settings.sorthandle_delete_confirmation_text, function() {
            var el = mw.$(idobj);
            mw.wysiwyg.change(idobj);
            var elparent = el.parent()

            mw.liveEditState.record({
                target: elparent[0],
                value: elparent.html()
            });
            el.addClass("mwfadeout");
            setTimeout(function() {
                mw.$(idobj).remove();
                mw.handleModule.hide();
                mw.$(mw.handleModule).removeClass('mw-active-item');
                mw.drag.fix_placeholders(true);
                mw.liveEditState.record({
                    target: elparent[0],
                    value: elparent.html()
                });
                if(c){
                    c.call()
                }
            }, 300);
        });
    },

    grammarlyFix:function(html){
        var data = mw.tools.parseHtml(html).body;
        mw.$("grammarly-btn", data).remove();
        mw.$("grammarly-card", data).remove();
        mw.$("g.gr_", data).each(function(){
            mw.$(this).replaceWith(this.innerHTML);
        });
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("[data-gramm]", data).removeAttr('data-gramm');
        mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
        mw.$("grammarly-card", data).remove();
        mw.$("grammarly-inline-cards", data).remove();
        mw.$("grammarly-popups", data).remove();
        mw.$("grammarly-extension", data).remove();
        return data.innerHTML;
    },
    saving: false,
    coreSave: function(data) {
        if (!data) return false;
        $.each(data, function(){
            this.html = mw.drag.grammarlyFix(this.html)
        });
        mw.drag.saving = true;

        /************  START base64  ************/
        data = JSON.stringify(data);
        data = btoa(encodeURIComponent(data).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
            }));
        data = {data_base64:data};
        /************  END base64  ************/

        var xhr = mw.ajax({
            type: 'POST',
            url: mw.settings.api_url + 'save_edit',
            data: data,
            dataType: "json",
            success: function (saved_data) {
                if(saved_data && saved_data.new_page_url && !mw.drag.DraftSaving){
                    window.parent.mw.askusertostay = false;
                    window.mw.askusertostay = false;
                    window.location.href  = saved_data.new_page_url;

                }
            }
        });

        xhr.always(function() {
            mw.drag.saving = false;
        });
        return xhr;
    },
    parseContent: function(root) {
        var root = root || document.body;
        var doc = mw.tools.parseHtml(root.innerHTML);
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

        //var doc = mw.$(doc).find('script').remove();

        mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
        mw.$("[contenteditable]", doc).removeAttr("contenteditable");
        var all = doc.querySelectorAll('[contenteditable]'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            all[i].removeAttribute('contenteditable');
        }
        var all = doc.querySelectorAll('.module'),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            if (all[i].querySelector('.edit') === null) {
                all[i].innerHTML = '';
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
    collectData: function(edits) {
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
                var content = mw.wysiwyg.cleanUnwantedTags(helper.item).innerHTML;
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
    },
    getData: function(root) {
        var body = mw.drag.parseContent(root).body,
            edits = body.querySelectorAll('.edit.changed'),
            data = mw.drag.collectData(edits);
        return data;
    },

    saveDisabled: false,
    draftDisabled: false,
    save: function(data, success, fail) {
        mw.trigger('beforeSaveStart', data);
        if (mw.liveedit.cssEditor) {
            mw.liveedit.cssEditor.publishIfChanged();
        }
        if (mw.drag.saveDisabled) return false;
        if(!data){
            var body = mw.drag.parseContent().body,
                edits = body.querySelectorAll('.edit.changed');
            data = mw.drag.collectData(edits);
        }



        if (mw.tools.isEmptyObject(data)) return false;

        mw._liveeditData = data;

        mw.trigger('saveStart', mw._liveeditData);

        var xhr = mw.drag.coreSave(mw._liveeditData);
        xhr.error(function(){

            if(xhr.status == 403){
                var modal = mw.dialog({
                    id : 'save_content_error_iframe_modal',
                    html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
                    width:$(window).width() - 90,
                    height:$(window).height() - 90
                });

                mw.askusertostay = false;

                mw.$("#save_content_error_iframe").ready(function() {
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
                    doc.open();
                    doc.write(xhr.responseText);
                    doc.close();
                    var save_content_error_iframe_reloads = 0;
                    var doc = document.getElementById('save_content_error_iframe').contentWindow.document;

                    mw.$("#save_content_error_iframe").load(function(){
                        // cloudflare captcha
                        var is_cf =  mw.$('.challenge-form',doc).length;
                        save_content_error_iframe_reloads++;

                        if(is_cf && save_content_error_iframe_reloads == 2){
                            setTimeout(function(){
                                mw.askusertostay = false;
                                mw.$('#save_content_error_iframe_modal').remove();
                            }, 150);

                        }
                    });

                });
            }
        });
        xhr.success(function(sdata) {
            mw.$('.edit.changed').removeClass('changed');
            mw.$('.orig_changed').removeClass('orig_changed');
            if (document.querySelector('.edit.changed') !== null) {
                mw.drag.save();
            } else {
                mw.askusertostay = false;
                mw.trigger('saveEnd', sdata);
            }
            if(success){
                success.call(sdata)
            }

        });
        xhr.fail(function(jqXHR, textStatus, errorThrown) {
            mw.trigger('saveFailed', textStatus, errorThrown);
            if(fail){
                fail.call(sdata)
            }
        });
        return xhr;
    },
    saveDraftOld: '',
    DraftSaving: false,
    initDraft: false,
    saveDraft: function() {
        if (mw.drag.draftDisabled) return false;
        if (mw.drag.DraftSaving) return false;
        if (!mw.drag.initDraft) return false;
        if (document.body.textContent != mw.drag.saveDraftOld) {
            mw.drag.saveDraftOld = document.body.textContent;
            var body = mw.drag.parseContent().body,
                edits = body.querySelectorAll('.edit.changed'),
                data = mw.drag.collectData(edits);
            if (mw.tools.isEmptyObject(data)) return false;
            data['is_draft'] = true;
            mw.drag.DraftSaving = true;
            var xhr = mw.drag.coreSave(data);
            xhr.always(function(msg) {
                mw.drag.DraftSaving = false;
                mw.drag.initDraft = false;
                mw.trigger('saveDraftCompleted');

            });
        }
    }
}

})();

(() => {
/*!********************************!*\
  !*** ../liveedit/@liveedit.js ***!
  \********************************/
mw.liveedit = {};
mw.isDrag = false;
mw.resizable_row_width = false;
mw.mouse_over_handle = false;
mw.external_content_dragged = false;

mw.have_new_items = false;

mw.dragCurrent = null;
mw.currentDragMouseOver = null;
mw.liveEditSelectMode = 'element';

mw.modulesClickInsert = true;

mw.mouseDownOnEditor = false;
mw.mouseDownStarted = false;
mw.SmallEditorIsDragging = false;

mw.states = {};
mw.live_edit_module_settings_array = [];

mw.noEditModules = [
    '[type="template_settings"]'
];

mw.isDragItem = mw.isBlockLevel = function (obj) {
    return mw.ea.helpers.isBlockLevel(obj);
};



!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);

mw.tools.addClass(document.body, 'mw-live-edit');

$(document).ready(function() {

    if (("ontouchstart" in document.documentElement)) {
        mw.$('body').addClass('touchscreen-device');
    }

    mw.liveedit.initReady();
    mw.liveedit.handleEvents();
    mw.liveedit.handleCustomEvents();

    mw.liveedit.cssEditor = new mw.liveeditCSSEditor();

});

$(window).on("load", function() {
    mw.liveedit.initLoad();
});

$(window).on('resize', function() {
    mw.liveedit.toolbar.setEditor();
});





})();

(() => {
/*!**********************************!*\
  !*** ../liveedit/beforeleave.js ***!
  \**********************************/


mw.liveedit.beforeleave = function(url) {
    var beforeleave_html = "" +
        "<div class='mw-before-leave-container'>" +
        "<p>Leave page by choosing an option</p>" +
        "<span class='mw-ui-btn mw-ui-btn-important'>" + mw.msg.before_leave + "</span>" +
        "<span class='mw-ui-btn mw-ui-btn-notification' >" + mw.msg.save_and_continue + "</span>" +
        "<span class='mw-ui-btn' onclick='mw.dialog.remove(\"#modal_beforeleave\")'>" + mw.msg.cancel + "</span>" +
        "</div>";
    if (mw.askusertostay && mw.$(".edit.orig_changed").length > 0) {
        if (document.getElementById('modal_beforeleave') === null) {
            var modal = mw.dialog({
                html: beforeleave_html,
                name: 'modal_beforeleave',
                width: 470,
                height: 230
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
/*!********************************!*\
  !*** ../liveedit/cloneable.js ***!
  \********************************/
mw.drag.onCloneableControl = function(target, isOverControl){
    if(!this._onCloneableControl){
        this._onCloneableControl = document.createElement('div');
        this._onCloneableControl.className = 'mw-cloneable-control';
        var html = '';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-prev tip" data-tip="Move backward"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-plus tip" data-tip="Clone"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-minus tip" data-tip="Remove"></span>' ;
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-next tip" data-tip="Move forward"></span>';
        this._onCloneableControl.innerHTML = html;

        document.body.appendChild(this._onCloneableControl);
        $('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent()
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            var parser = mw.tools.parseHtml(mw.drag._onCloneableControl.__target.outerHTML).body;
            var all = parser.querySelectorAll('[id]'), i = 0;
            for( ; i < all.length; i++){
                all[i].id = 'mw-cl-id-' + mw.random();
            }
            $(mw.drag._onCloneableControl.__target).after(parser.innerHTML);
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-minus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).fadeOut(function(){
                mw.wysiwyg.change(this);
                $(this).remove();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
            });
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-next', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).next().after(mw.drag._onCloneableControl.__target)
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-prev', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).prev().before(mw.drag._onCloneableControl.__target);
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
    }
    var clc = $(this._onCloneableControl);
    if(target === 'hide'){
        clc.hide();
    }
    else{
        clc.show();
        this._onCloneableControl.__target = target;

        var next = $(this._onCloneableControl.__target).next();
        var prev = $(this._onCloneableControl.__target).prev();
        var el = $(target), off = el.offset();


        if(next.length === 0){
            $('.mw-cloneable-control-next', clc).hide();
        }
        else{
            $('.mw-cloneable-control-next', clc).show();
        }
        if(prev.length === 0){
            $('.mw-cloneable-control-prev', clc).hide();
        }
        else{
            $('.mw-cloneable-control-prev', clc).show();
        }
        var leftCenter = (off.left > 0 ? off.left : 0) + (el.width()/2 - clc.width()/2) ;
        clc.show();
        if(isOverControl){
            return;
        }
        clc.css({
            top: off.top > 0 ? off.top : 0 ,
            left: leftCenter
        });


        var cloner = document.querySelector('.mw-cloneable-control');
        if(cloner) {
            mw._initHandles.getAll().forEach(function (curr) {
                masterRect = curr.wrapper.getBoundingClientRect();
                var clonerect = cloner.getBoundingClientRect();

                if (mw._initHandles.collide(masterRect, clonerect)) {
                    cloner.style.top = (parseFloat(curr.wrapper.style.top) + 10) + 'px';
                    cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                }
            });
        }
    }

}

})();

(() => {
/*!******************************!*\
  !*** ../liveedit/columns.js ***!
  \******************************/
mw.drag.columns = {
    step: 0.8,
    resizing: false,
    prepare: function () {
        mw.drag.columns.resizer = document.createElement('div');
        mw.wysiwyg.contentEditable(mw.drag.columns.resizer, false);
        mw.drag.columns.resizer.className = 'unselectable mw-columns-resizer';
        mw.drag.columns.resizer.pos = 0;
        mw.$(mw.drag.columns.resizer).on('mousedown', function () {
            mw.drag.columns.resizing = true;
            mw.drag.columns.resizer.pos = 0;
        });
        document.body.appendChild(mw.drag.columns.resizer);

        mw.$(mw.drag.columns.resizer).hide();
    },
    resize: function (e) {
        if (!mw.drag.columns.resizer.curr) return false;
        var w = parseFloat(mw.drag.columns.resizer.curr.style.width);
        var widthParentPixels;
        if (isNaN(w)) {
            w = mw.$(mw.drag.columns.resizer.curr).outerWidth();
            widthParentPixels = mw.$(mw.drag.columns.resizer.curr).parent().outerWidth();
            w = (w / widthParentPixels) * 100;
        }
        var next = mw.drag.columns.nextColumn(mw.drag.columns.resizer.curr);
        if(!next){
            mw.$(mw.drag.columns.resizer).hide();
            return false;
        }

        var w2 = parseFloat(next.style.width);
        if (isNaN(w2)) {
            w2 = mw.$(next).outerWidth();
            widthParentPixels = mw.$(next).parent().outerWidth();
            w2 = (w2 / widthParentPixels) * 100;
         }

        if (mw.drag.columns.resizer.pos < e.pageX) {
            if (w2 < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w - mw.drag.columns.step):(w + mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 + mw.drag.columns.step) : (w2 - mw.drag.columns.step);
            next.style.width =  calc + '%';
        }
        else {
            if (w < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w + mw.drag.columns.step):(w - mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 - mw.drag.columns.step) : (w2 + mw.drag.columns.step);
            next.style.width = calc + '%';
        }
        mw.drag.columns.resizer.pos = e.pageX;
        mw.drag.columns.position(mw.drag.columns.resizer.curr);
        mw.trigger('columnResize', mw.drag.columns.resizer.curr);
    },
    position: function (el) {
        if (!!mw.drag.columns.nextColumn(el)) {
            mw.drag.columns.resizer.curr = el;
            var off = mw.$(el).offset();
            mw.$(mw.drag.columns.resizer).css({
                top: off.top,
                left: mw.tools.isRtl() ? off.left - 10 : off.left + el.offsetWidth - 10,
                height: el.offsetHeight
            }).show();
        }
    },
    init: function () {
        mw.drag.columns.prepare();
        mw.on("ColumnOver", function (e, col) {
            mw.drag.columns.resizer.pos = 0;
            mw.drag.columns.position(col);
        });
        mw.on("ColumnOut", function (e, col) {
            mw.$(mw.drag.columns.resizer).hide();
        });

    },
    nextColumn: function (col) {
        var next = col.nextElementSibling;
        if (next === null) {
            return;
        }
        if (mw.tools.hasClass(next, 'mw-col')) {
            return next;
        }
        else {
            return mw.drag.columns.nextColumn(next);
        }
    }
}
$(mwd).ready(function () {
    mw.$(document.body).on('mouseup touchend', function () {
        if (mw.drag.plus.locked) {
            mw.wysiwyg.change(mw.drag.columns.resizer.curr);
        }
        mw.drag.columns.resizing = false;
        mw.drag.plus.locked = false;
        mw.tools.removeClass(document.body, 'mw-column-resizing');
    });
    mw.$(document.body).on('mousemove touchmove', function (e) {
        if (mw.drag.columns.resizing === true && mw.isDrag === false) {
            mw.drag.columns.resize(e);
            e.preventDefault();
            mw.drag.plus.locked = true;
            mw.tools.addClass(document.body, 'mw-column-resizing');
        }
    });
});

})();

(() => {
/*!***************************!*\
  !*** ../liveedit/data.js ***!
  \***************************/
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
/*!**********************************!*\
  !*** ../liveedit/edit.fields.js ***!
  \**********************************/
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
/*!***************************************!*\
  !*** ../liveedit/editor_externals.js ***!
  \***************************************/


    mw.$(window).on('load', function(){
      mw.$(document.body).removeClass('mw-external-loading');
    });











})();

(() => {
/*!******************************!*\
  !*** ../liveedit/editors.js ***!
  \******************************/
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
/*!***************************************!*\
  !*** ../liveedit/element_analyzer.js ***!
  \***************************************/
mw.AfterDrop = function(){



    this.loadNewModules = function(){
        mw.pauseSave = true;
        var need_re_init = false;
        var all = mw.$(".edit .module-item"), count = 0;
        all.each(function(c) {
            (function (el) {
                var parent = el.parentNode;
                var xhr = mw._({
                    selector: el,
                    done: function(module) {
                        mw.drag.fancynateLoading(module);
                        mw.pauseSave = false;
                        mw.wysiwyg.init_editables();
                        if(mw.liveEditDomTree) {
                            mw.liveEditDomTree.refresh(parent);
                            mw.liveEditDomTree.select(parent);

                        }
                    },
                    fail:function () {
                        mw.$(this).remove();
                        mw.notification.error('Error loading module.');
                    }
                }, true);
               if(xhr) {
                   xhr.always(function () {
                       count++;
                       if(all.length === count) {
                           mw.dragCurrent = null;
                       }
                   });
               }
               else {
                   count++;
               }

                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items === true) {
            need_re_init = true;
        }
        if (need_re_init === true) {
            if (!mw.isDrag) {
                if (typeof callback === 'function') {
                    callback.call(this);
                }
            }
        }
        mw.have_new_items = false;
    };

    this.__timeInit = null;

    this.init = function(){
        var scope = this;
        if(scope.__timeInit){
           clearTimeout(scope.__timeInit);
        }
        scope.__timeInit = setTimeout(function(){

            mw.$(".mw-drag-current-bottom, .mw-drag-current-top").removeClass('mw-drag-current-bottom mw-drag-current-top');
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver');

            mw.$(".mw_drag_current").each(function(){
                mw.$(this).removeClass('mw_drag_current').css({
                    visibility:'visible',
                    opacity:''
                });
            });
            mw.$(".currentDragMouseOver").removeClass('currentDragMouseOver')
            mw.$(".mw-empty").not(':empty').removeClass('mw-empty');
            scope.loadNewModules()
            mw.dropable.hide().removeClass('mw_dropable_onleaveedit');

        }, 78)
    }


}


/*************************************************************


        Options: Object literal

        Default: {
            classes:{
                edit:'edit',
                element:'element',
                module:'module',
                noDrop:'nodrop', // - disable drop
                allowDrop:'allow-drop' //- enable drop in .nodrop
            }
        }



    mw.analizer = new mw.ElementAnalizer(Options);




*************************************************************/

mw.ElementAnalyzer = function(options){



    this.data = {
        dropableAction:null,
        currentGrabbed:null,
        target:null,
        dropablePosition:null
    };

    this.dataReset = function(){
        this.data = {
            dropableAction:null,
            currentGrabbed:null,
            target:null,
            dropablePosition:null
        }
    };

    this.options = options || {};
    this.defaults = {
        classes:{
            edit: 'edit',
            element: 'element',
            module: 'module',
            noDrop: 'nodrop',
            allowDrop: 'allow-drop',
            emptyElement: 'mw-empty',
            zone: 'mw-zone'
        },
        rows:['mw-row', 'mw-ui-row', 'row'],
        columns:['mw-col', 'mw-ui-col', 'col', 'column', 'columns'],
        columnMatches:'[class*="col-"]',
        rowMatches:'[class*="row-"]',
    };
    this.settings = $.extend({}, this.options, this.defaults);

    this.prepare = function(){
        this.cls = this.settings.classes;
        this.initCSS();
    };

    this.initCSS = function(){
        var css = 'body.dragStart .'+this.cls.noDrop+'{'
            +'pointer-events: none;'
        +'}'
        +'body.dragStart .'+this.cls.allowDrop+'{'
            +'pointer-events: all;'
        +'}';

        var style = document.createElement('style');
        document.getElementsByTagName('head')[0].appendChild(style);
        style.innerHTML = css;
    };


    this._isEditLike = function(node){
        node = node || this.data.target;
        var case1 = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.cls.edit,this.cls.module]);
        var case2 = mw.tools.hasClass(node, 'module') && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, [this.cls.edit,this.cls.module]);
        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, this.cls.edit);
        return (case1 || case2) && !mw.tools.hasClass(edit, this.cls.noDrop);
    };
    this._canDrop = function(node) {
        node = node || this.data.target;
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.cls.allowDrop, this.cls.noDrop]);
    };

    this._layoutInLayout = function() {
        if (!this.data.currentGrabbed || !document.body.contains(this.data.currentGrabbed)) {
            return false;
        }
        var currentGrabbedIsLayout = (this.data.currentGrabbed.getAttribute('data-module-name') === 'layouts' || mw.dragCurrent.getAttribute('data-type') === 'layouts');
        var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(this.data.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
        return {
            target:targetIsLayout,
            result:currentGrabbedIsLayout && !!targetIsLayout
        };
    };

    this.canDrop = function(node){
        node = node || this.data.target;
        var can = (this._isEditLike(node) && this._canDrop(node) && !this._layoutInLayout().result);
        return can;
    };



    this.analizePosition = function(event, node){
        node = node || this.data.target;
        var height = node.offsetHeight,
            offset = mw.$(node).offset();
        if (mw.event.page(event).y > offset.top + (height / 2)) {
            this.data.dropablePosition =  'bottom';
        } else {
            this.data.dropablePosition =  'top';
        }
    };

    this.analizeActionOfElement = function(node, pos){
        node = node || this.data.target;
        pos = node || this.data.dropablePosition;
    };
    this.afterAction = function(node, pos){
        if(!this._afterAction){
            this._afterAction = new mw.AfterDrop();
        }

        this._afterAction.init();

    };
    this.dropableHide = function(){

    };
    this.analizeAction = function(node, pos){
        node = node || this.data.target;
        pos = pos || this.data.dropablePosition;
        if(this.helpers.isEmpty()){
            this.data.dropableAction = 'append';
            return;
        }
        var actions =  {
            Around:{
                top:'before',
                bottom:'after'
            },
            Inside:{
               top:'prepend',
               bottom:'append'
            }
        };

        if(!pos){
            return;
        }



        if(mw.tools.hasClass(node, 'allow-drop')){
            this.data.dropableAction = actions.Inside[pos];
        }
        else if(this.helpers.isElement()){
            this.data.dropableAction = actions.Around[pos];
        }
        else if(this.helpers.isEdit()){
            this.data.dropableAction = actions.Inside[pos];
        }
        else if(this.helpers.isLayoutModule()){
            this.data.dropableAction = actions.Around[pos];
        }
        else if(this.helpers.isModule()){
            this.data.dropableAction = actions.Around[pos];
        }
    };

    this.action = function(event){
        var node = event.target;
        var final = {};
        if(this._isEditLike(node)){
            if(this._canDrop(node)){

            }
        }
    };



    this.helpers = {
        scope:this,
        isBlockLevel:function(node){
            node = node || (this.data ? this.data.target : null);
            return mw.tools.isBlockLevel(node);
        },
        isInlineLevel:function(node){
            node = node || this.data.target;
            return mw.tools.isInlineLevel(node);
        },
        canAccept:function(target, what){
            var accept = target.dataset('accept');
            if(!accept) return true;
            accept = accept.trim().split(',').map(Function.prototype.call, String.prototype.trim);
            var wtype = 'all';
            if(mw.tools.hasClass(what, 'module-layout')){
                wtype = 'layout';
            }
            else if(mw.tools.hasClass(what, 'module')){
                wtype = 'module';
            }
            else if(mw.tools.hasClass(what, 'element')){
                wtype = 'element';
            }
            if(wtype=='all') return true

            return accept.indexOf(wtype) !== -1;
        },
        getBlockElements:function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0; final = [];
            for( ; i<all.length; i++){
                if(this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i])
                }
            }
            return final;
        },
        getElementsLike:function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0; final = [];
            for( ; i<all.length; i++){
                if(!this.scope.helpers.isColLike(all[i]) &&
                    !this.scope.helpers.isRowLike(all[i]) &&
                    !this.scope.helpers.isEdit(all[i]) &&
                    this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i]);
                }
            }
            return final;
        },
        isEdit:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.edit);
        },
        isModule:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.module) && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.scope.cls.module, this.scope.cls.edit]));
        },
        isElement:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.element);
        },
        isEmpty:function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, 'mw-empty');
        },
        isRowLike:function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.rows);
            if(is){
                return is;
            }
            return mw.tools.matches(node, this.scope.settings.rowMatches);
        },
        isColLike:function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.columns);
            if(is){
                return is;
            }
            if(mw.tools.hasAnyOfClasses(node, ['mw-col-container', 'mw-ui-col-container'])){
                return false;
            }
            return mw.tools.matches(node, this.scope.settings.columnMatches);
        },
        isLayoutModule:function(node){
            node = node || this.scope.data.target;
            return false;

        },
        noop:function(){}
    };


    this.interactionTarget = function(next){
        node = this.data.target;
        if(next) node = node.parentNode;
        while(node && !this.helpers.isBlockLevel(node)){
            node = node.parentNode;
        }
        return node;
    };
    this.validateInteractionTarget = function(node){
        node = node || this.data.target;
        if (!mw.tools.firstParentOrCurrentWithClass(node, this.cls.edit)) {
           return false;
        }
        var cls = [
            this.cls.edit,
            this.cls.element,
            this.cls.module,
            this.cls.emptyElement
        ];
        while(node && node !== document.body){
            if(mw.tools.hasAnyOfClasses(node, cls)){
                return node;
            }
            node = node.parentNode;
        }
        return false;
    };
    this.on = function(events, listener) {
        events = events.trim().split(' ');
        for (var i=0 ; i<events.length; i++) {
             document.body.addEventListener(events[i], listener, false);
        }
    };
    this.loadNewModules = function(){
        mw.pauseSave = true;
        var need_re_init = false;
        mw.$(".edit .module-item").each(function(c) {

            (function (el) {
                var xhr = mw._({
                    selector: el,
                    done: function(module) {
                        mw.drag.fancynateLoading(module);
                        mw.pauseSave = false;
                        mw.wysiwyg.init_editables();
                    },
                    fail:function () {
                        mw.$(this).remove();
                        mw.notification.error('Error loading module.')
                    }
                }, true);
                need_re_init = true;
            })(this);
        });
        if (mw.have_new_items === true) {
            need_re_init = true;
        }
        mw.have_new_items = false;
    };
    this.whenUp = function(){
        var scope = this;
        this.on('mouseup touchend', function(){
            if(scope.data.currentGrabbed){
                scope.data.currentGrabbed = null;
            }
        });
    };

    this.getTarget = function(t){
        t = t || this.validateInteractionTarget();
        if(!t){
            return;
        }
        if (this.canDrop(t)) {
            return t;
        } else {
            return this.redirect(t);
        }
    };

    this.redirect = function(node){
        node = node || this.data.target;
        var islayOutInLayout = this._layoutInLayout(node);
        if(islayOutInLayout.result){
            var res =  this.validateInteractionTarget(/*node === islayOutInLayout.target ? islayOutInLayout.target.parentNode : */islayOutInLayout.target);
            return  res;
        }
        if(node === document.body || node.parentNode === document.body) return null;
        return this.getTarget(node.parentNode);
    };

    this.interactionAnalizer = function(e){

        var scope = this;
        mw.dropable.hide();

        if(this.data.currentGrabbed){
            if (e.type.indexOf('touch') !== -1) {
                var coords = mw.event.page(e);
                scope.data.target = document.elementFromPoint(coords.x, coords.y);
            }
            else {
                scope.data.target = e.target;
            }
            scope.interactionTarget();
            scope.data.target = scope.getTarget();

            if(scope.data.target){
                    scope.analizePosition(e);
                    scope.analizeAction();
                    mw.dropable.show();
            }
            else{

                    var near = mw.dropables.findNearest(e);
                    if(near.element){
                        scope.data.target = near.element;
                        scope.data.dropablePosition = near.position;
                        mw.dropables.findNearestException = true;
                        mw.dropable.show();
                    }
                    else{
                        mw.currentDragMouseOver = null;
                        mw.dropable.hide();
                        scope.dataReset();

                    }

            }

            var el = mw.$(scope.data.target);
            mw.currentDragMouseOver = scope.data.target;

            var edit = mw.tools.firstParentOrCurrentWithClass(mw.currentDragMouseOver, 'edit');
            mw.tools.classNamespaceDelete(mw.dropable[0], 'mw-dropable-tagret-rel-');
            if(edit) {
                mw.tools.addClass(mw.dropable[0], 'mw-dropable-tagret-rel-' + edit.getAttribute('rel'));
                var rel = edit.getAttribute('rel');
                mw.tools.addClass(mw.dropable[0], 'mw-dropable-tagret-rel-' + rel);
            }

            mw.dropables.set(scope.data.dropablePosition, el.offset(), el.height(), el.width());

            if(el[0] && !mw.tools.hasAnyOfClasses(el[0], ['mw-drag-current-'+scope.data.dropablePosition])){
                mw.$('.mw-drag-current-top,.mw-drag-current-bottom').removeClass('mw-drag-current-top mw-drag-current-bottom');
                mw.tools.addClass(el[0], 'mw-drag-current-'+scope.data.dropablePosition)
            }
        }
    };

    this.whenMove = function(){
        var scope = this;
        this.on('mousemove touchmove', function(e){
            scope.interactionAnalizer(e)
        });
    };
    this.init = function(){
        this.prepare();
    };

    this.init();
};

mw.ea = new mw.ElementAnalyzer();

})();

(() => {
/*!************************************!*\
  !*** ../liveedit/events.custom.js ***!
  \************************************/
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
/*!*****************************!*\
  !*** ../liveedit/events.js ***!
  \*****************************/
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

    mw.$("#mw-toolbar-reset-content-editor-btn").click(function() {
        mw.tools.open_reset_content_editor();
    });
    mw.$(document.body).on('keyup', function(e) {
        mw.$(".mw_master_handle").css({
            left: "",
            top: ""
        });
        mw.on.stopWriting(e.target, function() {
            if (mw.tools.hasClass(e.target, 'edit') || mw.tools.hasParentsWithClass(this, 'edit')) {
                mw.liveEditState.record({
                    target:e.target,
                    value:e.target.innerHTML
                });
                mw.drag.saveDraft();
            }
        });
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

                mw.editorIconPicker.tooltip('hide');
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

};

})();

(() => {
/*!*****************************************!*\
  !*** ../liveedit/external_callbacks.js ***!
  \*****************************************/
mw.iframecallbacks = {
    noop: function() {

    },
    insert_link: function (url, target, link_content) {
        if(url.length){
            target = url[1];
            link_content = url[2];
            url = url[0];
        } else if(url.url ) {
            url= url.url;
            target = target || url.target || '_self';
            link_content = link_content || url.text || '_self';
        }
        url = url.trim();
        var contains = false;
        var arr = ['mailto:', 'tel:', 'skype:', 'sms:', 'geopoint:', 'whatsapp:'],
            i = 0;
        for( ; i < arr.length; i++ ){
            if(url.indexOf(arr[i]) === 0){
                contains = true;
            }
        }
        if (!contains && !!url) {
            //url = url.indexOf("http") === 0 ? url : (location.protocol + "//" + url);
        }
        target = target || '_self';

        var link_inner_text = false;
        if(link_content){
            link_inner_text = link_content;
        }

        var sel, range, a;

        sel = getSelection();
        if(!sel.rangeCount){
            return;
        }

        range = sel.getRangeAt(0);
        var jqAction = url ? 'attr' : 'removeAttr';

        mw.wysiwyg.change(range.startContainer);

        if (!!mw.current_element && mw.current_element.nodeName === 'IMG') {
            if (mw.current_element.parentNode.nodeName !== 'A') {
                a = document.createElement('a');
                if(url){
                    a.href = url;
                }
                a.target = target;

                mw.$(mw.current_element).wrap(a);
            }
            else {
                mw.$(mw.current_element.parentNode)[jqAction]('href', url);

                mw.current_element.parentNode.target = target;
            }
        }


        if (range.commonAncestorContainer.nodeName === 'A') {
            mw.$(range.commonAncestorContainer)[jqAction]("href", url).attr("target", target);
            if(link_inner_text){
                mw.$(range.commonAncestorContainer).html(link_inner_text);
            }
            return false;
        }


        var start = range.startContainer;


        if (window.getSelection().isCollapsed) {

            if (!!mw.current_element && mw.current_element.nodeName !== 'A') {
                if (mw.tools.hasChildrenWithTag(mw.current_element, 'a')) {
                    mw.$(mw.tools.firstChildWithTag(mw.current_element, 'a'))[jqAction]("href", url).attr("target", target);
                    if(link_inner_text){
                        mw.$(mw.tools.firstChildWithTag(mw.current_element, 'a')).html(link_inner_text);
                    }
                    return false;
                }
            } else if (!!mw.current_element && mw.current_element.nodeName === 'A') {
                mw.$(mw.current_element).attr("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.current_element).html(link_inner_text);
                }
                return false;
            }

            if (mw.tools.hasParentsWithTag(start, 'a')) {
                mw.$(mw.tools.firstParentWithTag(start, 'a'))[jqAction]("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.tools.firstParentWithTag(start, 'a')).html(link_inner_text);
                }
                return false;
            }
            if (mw.tools.hasChildrenWithTag(start, 'a')) {
                mw.$(mw.tools.firstChildWithTag(start, 'a'))[jqAction]("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.tools.firstChildWithTag(start, 'a')).html(link_inner_text);
                }
                return false;
            }

        }


        var link = mw.wysiwyg.findTagAcrossSelection('a');
        if (!!link) {
            mw.$(link)[jqAction]("href", url);
            mw.$(link).attr("target", target);
            if(link_inner_text){
                mw.$(link).html(link_inner_text);
            }
        }
        else {
            if (!window.getSelection().isCollapsed) {
                a = document.createElement('a');
                a.href = url;
                a.target = target;
                sel = window.getSelection();
                range = sel.getRangeAt(0);
                try {
                    range.surroundContents(a);
                }
                catch (e) {
                    mw.wysiwyg.execCommand("CreateLink", false, url);
                }
            }
            else {

                var html = '<a href="' + url + '" target="' + target + '">' + (link_inner_text ? link_inner_text : url) + '</a>';
                mw.wysiwyg.insert_html(html);
            }
        }
        if(link_content && a) {
            a.innerHTML = link_content
        }
    },

    set_bg_image: function (url) {
        return mw.wysiwyg.set_bg_image(url);
    },
    fontColor: function (color) {
        return mw.wysiwyg.fontColor(color);
    },
    fontbg: function (color) {
        return mw.wysiwyg.fontbg(color);
    },
    change_bg_color: function (color) {
        return mw.wysiwyg.change_bg_color(color);
    },
    change_border_color: function (color) {
        return mw.wysiwyg.change_border_color(color);
    },
    change_shadow_color: function (color) {
        return mw.wysiwyg.change_shadow_color(color);
    },

    add_link_to_menu: function () {

    },
    editlink: function (a, b) {
        mw.wysiwyg.restore_selection();
        var link = mw.wysiwyg.findTagAcrossSelection('a');
        link.href = a;

        mw.wysiwyg.change(link);

    }

}








})();

(() => {
/*!******************************!*\
  !*** ../liveedit/handles.js ***!
  \******************************/

var _handleInsertTargetDisplay;
var handleInsertTargetDisplay = function (target, pos) {
     if(!_handleInsertTargetDisplay) {
        _handleInsertTargetDisplay = mw.element('<div class="mw-handle-insert-target-display" />');
        mw.element(document.body).append(_handleInsertTargetDisplay);
    }
    if (target === 'hide'){
       _handleInsertTargetDisplay
           .removeClass('mw-handle-insert-target-display-top')
           .removeClass('mw-handle-insert-target-display-bottom')
           .hide();
       return;
    }   else {
        _handleInsertTargetDisplay.show();
    }

    var $el = $(target);
    var off = $el.offset();
    var css = { left: off.left, width: $el.outerWidth()};
    if (pos === 'top') {
        css.top = off.top;
    } else if(pos === 'bottom') {
        css.top = off.top + $el.outerHeight();
    }
    _handleInsertTargetDisplay
        .removeClass('mw-handle-insert-target-display-top')
        .removeClass('mw-handle-insert-target-display-bottom')
        .addClass(pos === 'top' ? 'mw-handle-insert-target-display-top' : 'mw-handle-insert-target-display-bottom')
        .css(css);
}

var dynamicModulesMenuTime = null;
var dynamicModulesMenu = function(e, el) {
    if(!mw.inaccessibleModules){
        mw.inaccessibleModules = document.createElement('div');
        mw.inaccessibleModules.className = 'mw-ui-btn-nav mwInaccessibleModulesMenu';
        document.body.appendChild(mw.inaccessibleModules);
        mw.$(mw.inaccessibleModules).on('mouseenter', function(){
            this._hovered = true;
        }).on('mouseleave', function(){
            this._hovered = false;
        });
    }

    var $el;
    if(e.type === 'moduleOver' || e.type === 'ModuleClick'){

        var parentModule = mw.tools.lastParentWithClass(el, 'module');
        var childModule = mw.tools.firstChildWithClass(el, 'module');

        $el = mw.$(el);
        if(!!parentModule && ( $el.offset().top - mw.$(parentModule).offset().top) < 10 ){
            el.__disableModuleTrigger = parentModule;
            $el.addClass('inaccessibleModule');
        }
        else if(!!childModule && ( mw.$(childModule).offset().top - $el.offset().top) < 10 ) {
            childModule.__disableModuleTrigger = el;
            mw.$(childModule).addClass('inaccessibleModule');
        }
        else{
            $el.removeClass('inaccessibleModule');
        }
    }


    var modules = mw.$(".inaccessibleModule", el);
    if(modules.length === 0){
        var parent = mw.tools.firstParentWithClass(el, 'module');
        if(parent){
            if(($el.offset().top - mw.$(parent).offset().top) < 10) {
                modules = mw.$([el]);
                el = parent;
                $el = mw.$(el);

            }
        }
    }
    if (e.type === 'ModuleClick') {
        mw.liveEditSelector.select(el);
    }

    if(modules.length && !mw.inaccessibleModules._hovered) {
        mw.inaccessibleModules.innerHTML = '';
    }
    modules.each(function(){
        var span = document.createElement('span');
        span.className = 'mw-handle-item mw-ui-btn mw-ui-btn-small';
        var type = mw.$(this).attr('data-type') || mw.$(this).attr('type');
        if(type){
            var title = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : type;
            title = title.replace(/\_/, ' ');
            span.innerHTML = mw.live_edit.getModuleIcon(type) + title;
            var el = this;
            span.onclick = function(){
                mw.tools.module_settings(el);
            };
            mw.inaccessibleModules.appendChild(span);
        }
    });
    if(modules.length > 0){
        var off = mw.$(el).offset();
        if(mw.tools.collision(el, mw.handleModule.wrapper)){
            off.top = parseFloat(mw.handleModule.wrapper.style.top);
            off.left = parseFloat(mw.handleModule.wrapper.style.left);
        }
        mw.inaccessibleModules.style.top = off.top + 'px';
        mw.inaccessibleModules.style.left = off.left + 'px';
        clearTimeout(dynamicModulesMenuTime);
        mw.$(mw.inaccessibleModules).show();
    }
    else{
        dynamicModulesMenuTime = setTimeout(function(){
            if(!mw.inaccessibleModules._hovered) {
                mw.$(mw.inaccessibleModules).hide();
            }

        }, 3000);

    }


    return $el[0];

};

var handleDomtreeSync = {};

mw.Handle = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };

    this.createWrapper = function() {
        this.wrapper = document.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handle-' + mw.random());
        this.wrapper.className = 'mw-defaults mw-handle-item ' + (this.options.className || 'mw-handle-type-default');
        this.wrapper.contenteditable = false;

        mw.$(this.wrapper).on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        mw.$(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
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
        mw.$(this.wrapper).hide().removeClass('active');
        this._visible = false;
        return this;
    };

    this.show = function () {
        mw.$(this.wrapper).show();
        this._visible = true;
        return this;
    };

    this.createHandler = function(){
        this.handle = document.createElement('span');
        this.handleIcon = document.createElement('span');
        this.handleTitle = document.createElement('span');
        this.handle.className = 'mw-handle-handler';
        this.handleIcon.dataset.tip = 'Drag to rearrange';
        this.handleIcon.className = 'tip mw-handle-handler-icon';
        this.handleTitle.className = 'mw-handle-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.createButtons();
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            mw.$(scope.wrapper).toggleClass('active');
        };
        mw.$(document.body).on('click', function (e) {
            if(!mw.tools.hasParentWithId(e.target, scope.wrapper.id)){
                mw.$(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = document.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass
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
        dn.className = 'mw-handle-menu-dynamic' + (item.className ? ' ' + item.className : '');
        return dn;
    };
    this.createMenu = function(){
        this.menu = document.createElement('div');
        this.menu.className = 'mw-handle-menu ' + (this.options.menuClass ? this.options.menuClass : 'mw-handle-menu-default');
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
            mw.tools.removeClass(this, 'active')
            obj.action(this);
            scope.hide();
        };
        return btn;
    };

    this.createButtons = function(){
        this.buttonsHolder = document.createElement('div');
        this.buttonsHolder.className = 'mw-handle-buttons';
        if (this.options.buttons) {
            for (var i = 0; i < this.options.buttons.length; i++) {
                this.buttonsHolder.appendChild(this.createButton(this.options.buttons[i])) ;
            }
        }
         this.handle.appendChild(this.buttonsHolder);
    };
    this.create();
    this.hide();
};

mw._activeModuleOver = {
    module: null,
    element: null
};

mw._initHandles = {
    getNodeHandler:function (node) {
        if(mw._activeElementOver === node){
            return mw.handleElement
        } else if(mw._activeModuleOver === node) {
            return mw.handleModule
        } else if(mw._activeRowOver === node) {
            return mw.handleColumns;
        }
    },
    getAllNodes: function (but) {
        var all = [
            mw._activeModuleOver,
            mw._activeRowOver,
            mw._activeElementOver
        ];
        all = all.filter(function (item) {
            return !!item && item.nodeType === 1;
        });
        return all;
    },
    getAll: function (but) {
        var all = [
            mw.handleModule,
            mw.handleModuleActive,
            mw.handleColumns,
            mw.handleElement
        ];
        all = but ? all.filter(function (x) {
            return x !== but;
        }) :  all;
        return all.filter(function (item) {
            if(item){
                return item.visible();
            }

        });
    },
    hideAll:function (but) {
        this.getAll(but).forEach(function (item) {
            item.hide();
        });
    },
    collide: function(a, b) {
        return !(
            ((a.y + a.height) < (b.y)) ||
            (a.y > (b.y + b.height)) ||
            ((a.x + a.width) < b.x) ||
            (a.x > (b.x + b.width))
        );
    },
    _manageCollision: false,
    manageCollision:function () {

        var scope = this,
            max = 35,
            skip = [];

        scope.getAll().forEach(function (curr) {
            var master = curr, masterRect;
            //if (skip.indexOf(master) === -1){
            scope.getAll(curr).forEach(function (item) {
                masterRect = master.wrapper.getBoundingClientRect();
                var irect = item.wrapper.getBoundingClientRect();
                if (scope.collide(masterRect, irect)) {
                    skip.push(item);
                    var topMore = item === mw.handleElement ? 10 : 0;
                    item.wrapper.style.top = (parseInt(master.wrapper.style.top, 10) + topMore) + 'px';
                    item.wrapper.style.left = ((parseInt(master.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                    master = curr;
                }
            });
        });

        var cloner = document.querySelector('.mw-cloneable-control');
        if(cloner) {
            scope.getAll().forEach(function (curr) {
                masterRect = curr.wrapper.getBoundingClientRect();
                var clonerect = cloner.getBoundingClientRect();

                if (scope.collide(masterRect, clonerect)) {
                    cloner.style.top = curr.wrapper.style.top;
                    cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                }
            });
        }
    },

    elements: function(){
        mw.handleElement = new mw.Handle({
            id: 'mw-handle-item-element',
            className: 'mw-handle-type-default',
            buttons: [
                    {
                        title: mw.lang('Insert'),
                        icon: 'mdi-plus-circle',
                        className: 'mw-handle-insert-button',
                        hover: [
                            function (e){  handleInsertTargetDisplay(mw._activeElementOver, mw.handleElement.positionedAt)  },
                            function (e){  handleInsertTargetDisplay('hide')  }
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
                    icon: 'mw-icon-edit',
                    action: function () {
                        mw.liveEditSettings.show();
                        mw.sidebarSettingsTabs.set(3);
                        if(mw.cssEditorSelector){
                            mw.liveEditSelector.active(true);
                            mw.liveEditSelector.select(mw._activeElementOver);
                        } else{
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

        mw.$(mw.handleElement.wrapper).on('mouseenter', function () {
            mw.liveEditSelector.select(mw._activeElementOver);
        });
        mw.$(mw.handleElement.wrapper).draggable({
            handle: mw.handleElement.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw._activeElementOver;

                handleDomtreeSync.start = mw.dragCurrent.parentNode;

                if(!mw.dragCurrent.id){
                    mw.dragCurrent.id = 'element_' + mw.random();
                }
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            },
            stop: function() {
                mw.$(document.body).removeClass("dragStart");

                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.$(mw.handleElement.wrapper).on('click', function() {
            if (!$(mw._activeElementOver).hasClass("element-current")) {
                mw.$(".element-current").removeClass("element-current");

                if (mw._activeElementOver.nodeName === 'IMG') {

                    mw.trigger("ImageClick", mw._activeElementOver);
                } else {
                    mw.trigger("ElementClick", mw._activeElementOver);
                }
            }

        });

        mw.on("ElementOver", function(a, element, originalEvent) {
            mw._activeElementOver = element;
            mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").show();
            if (!mw.ea.canDrop(element)) {
                mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").hide();
                return false;
            }
            var el = mw.$(element);

            var o = el.offset();

            var pleft = parseFloat(el.css("paddingLeft"));
            var left_spacing = o.left;
            if (mw.tools.hasClass(element, 'jumbotron')) {
                left_spacing = left_spacing + pleft;

            }
            // Centered: left_spacing += (el.width()/2 - mw.handleElement.wrapper.offsetWidth/2);
            if(left_spacing<0){
                left_spacing = 0;
            }
            //todo: another icon
            var isSafe = false; // mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['safe-mode', 'regular-mode']);
            var _icon = isSafe ? '<svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 504.03 440" height="17" class="safe-element-svg"><path fill="green" d="M252,2.89C178.7,2.89,102.4,19.44,102.4,19.44A31.85,31.85,0,0,0,76.76,50.69v95.59c0,165.67,159.7,234.88,159.7,234.88A31.65,31.65,0,0,0,252,385.27a32.05,32.05,0,0,0,15.56-4.11c.06,0,159.69-69.21,159.69-234.88V50.69a31.82,31.82,0,0,0-25.64-31.25S325.33,2.89,252,2.89Zm95.59,95.59a15.94,15.94,0,0,1,11.26,27.2L238.45,246.11a16,16,0,0,1-11.33,4.73,15.61,15.61,0,0,1-11.2-4.73l-55-55a15.93,15.93,0,0,1,22.53-22.53l43.69,43.82L336.34,103.15a16,16,0,0,1,11.27-4.67Zm0,0"/></svg>' : '<span class="mdi mdi-drag tip" data-tip="' + mw.lang('') + '"></span>';

            var icon = '<span class="mw-handle-element-title-icon '+(isSafe ? 'tip' : '')+'"  '+(isSafe ? ' data-tip="Current element is protected \n  from accidental deletion" data-tipposition="top-left"' : '')+' >'+ _icon +'</span>';

            var title = '<i class="mdi mdi-cog mw-handle-handler-settings-icon"></i>';

            mw.handleElement.setTitle(icon, title);

            if(el.hasClass('allow-drop')){
                mw.handleElement.hide();
            } else{
                mw.handleElement.show();
            }
            mw.handleElement.positionedAt = 'top';
            var posTop = o.top - 30;
            var elHeight = el.height();
            if (originalEvent.pageY > (o.top + elHeight/2)) {
                posTop = o.top + elHeight;
                mw.handleElement.positionedAt = 'bottom';
            }

            mw.$(mw.handleElement.wrapper).css({
                top: posTop,
                left: left_spacing
            }).removeClass('active');

            if(!element.id) {
                element.id = "element_" + mw.random();
            }

            mw.dropable.removeClass("mw_dropable_onleaveedit");
            mw._initHandles.manageCollision();

        });


    },
    modules: function () {
        var handlesModulesButtons = [
            {
                title: mw.lang('Edit'),
                icon: 'mdi-pencil',
                action: function () {
                    mw.drag.module_settings(mw._activeModuleOver,"admin");
                    mw.handleModule.hide();
                }
            },
            {
                title: mw.lang('Insert'),
                className: 'mw-handle-insert-button',
                icon: 'mdi-plus-circle',
                hover: [
                    function (e) {
                        handleInsertTargetDisplay(mw._activeModuleOver, mw.handleModule.positionedAt);
                    },
                    function (e) {
                        handleInsertTargetDisplay('hide');
                    }
                ],
                action: function (node) {
                    if(mw.handleModule.isLayout) {
                        mw.layoutPlus.showSelectorUI(node);
                    } else {
                        mw.drag.plus.rendModules(node);
                    }

                }
            },
        ];

        var handlesModuleConfig = {
            id: 'mw-handle-item-module',
            buttons: handlesModulesButtons,
            menu: [
                {
                    title: mw.lang('Edit'),
                    icon: 'mdi-pencil',
                    action: function () {
                        mw.drag.module_settings(mw._activeModuleOver,"admin");
                        mw.handleModule.hide();
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mdi mdi-chevron-double-up',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'prev');
                        mw.handleModule.hide()
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mdi mdi-chevron-double-down',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'next');
                        mw.handleModule.hide()
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },
                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeModuleOver);
                        mw.handleModule.hide();
                    }
                }
            ]
        };
        var handlesModuleConfigActive = {
            id: 'mw-handle-item-module-active',
            buttons: handlesModulesButtons,
            menu: [
                {
                    title: 'Settings',
                    icon: 'mw-icon-gear',
                    action: function () {
                        mw.drag.module_settings(getActiveDragCurrent(),"admin");
                        $(mw.handleModuleActive.wrapper).removeClass('active');
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mdi mdi-chevron-double-up',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'prev');
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mdi mdi-chevron-double-down',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'next');
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },
                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(getActiveDragCurrent());
                        mw.handleModuleActive.hide();
                    }
                }
            ]
        };

        var getActiveDragCurrent = function () {
            //var el = mw.liveEditSelector && mw.liveEditSelector.selected ?  mw.liveEditSelector.selected[0] : null;
            var el = mw.liveEditSelector.activeModule;
            if (el && el.nodeType === 1) {
                return el;
            }
            if(mw.handleModuleActive._target) {
                return mw.handleModuleActive._target;
            }
        };

        var getDragCurrent = function () {
            if(mw._activeModuleOver){
                return mw._activeModuleOver;
            }
        };
        var dragConfig = function (curr, handle) {
            return {
                handle: handle.handleIcon,
                distance:20,
                cursorAt: {
                    //top: -30
                },
                start: function() {
                    mw.isDrag = true;
                    mw.dragCurrent = curr();
                    handleDomtreeSync.start = mw.dragCurrent.parentNode;
                    if (!mw.dragCurrent.id) {
                        mw.dragCurrent.id = 'module_' + mw.random();
                    }
                    if(mw.liveEditTools.isLayout(mw.dragCurrent)){
                        mw.$(mw.dragCurrent).css({
                            opacity:0
                        }).addClass("mw_drag_current");
                    } else {
                        mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                    }

                    mw.trigger("AllLeave");
                    mw.drag.fix_placeholders();
                    mw.$(document.body).addClass("dragStart");
                    mw.image_resizer._hide();
                    mw.wysiwyg.change(mw.dragCurrent);
                    mw.smallEditor.css("visibility", "hidden");
                    mw.smallEditorCanceled = true;
                },
                stop: function() {
                    mw.$(document.body).removeClass("dragStart");
                    if(mw.liveEditDomTree) {
                        mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                    }
                }
            };
        };

        mw.handleModule = new mw.Handle(handlesModuleConfig);
        mw.handleModuleActive = new mw.Handle(handlesModuleConfigActive);

        mw.handleModule.type = 'hover';
        mw.handleModuleActive.type = 'active';

        mw.handleModule._hideTime = null;
        mw
            .$(mw.handleModule.wrapper)
            .draggable(dragConfig(getDragCurrent, mw.handleModule))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        mw
            .$(mw.handleModuleActive.wrapper)
            .draggable(dragConfig(getActiveDragCurrent, mw.handleModuleActive))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        var positionModuleHandle = function(e, pelement, handle, event){

            handle._element = pelement

            var element ;

            if(handle.type === 'hover') {
                element = dynamicModulesMenu(e, pelement) || pelement;
                mw._activeModuleOver = element;
            } else {
                //pelement = mw.tools.lastMatchesOnNodeOrParent(pelement, ['.module']);

                element = dynamicModulesMenu(e, pelement) || pelement;
                handle._target = pelement;
            }



            mw.$(".mw-handle-menu-dynamic", handle.wrapper).empty();
            mw.$('.mw_handle_module_up,.mw_handle_module_down').hide();
            var $el, hasedit;
            var isLayout = element && element.getAttribute('data-type') === 'layouts';
            handle.isLayout = isLayout;
            handle.handle.classList[isLayout ? 'add' : 'remove']('mw-handle-target-layout');
            if(isLayout){

                $el = mw.$(element);
                hasedit = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst($el[0].parentNode,['edit', 'module']);

                if(hasedit){
                    if($el.prev('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_up').show();
                    }
                    if($el.next('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_down').show();
                    }
                }
            }

            var el = mw.$(element);
            var o = el.offset();
            var width = el.width();
            var pleft = parseFloat(el.css("paddingLeft"));

            var lebar =  document.querySelector("#live_edit_toolbar");
            var minTop = lebar ? lebar.offsetHeight : 0;
            if(mw.templateTopFixed) {
                var ex = document.querySelector(mw.templateTopFixed);
                if(ex && !ex.contains(el[0])){
                    minTop += ex.offsetHeight;
                }
            }

            var marginTop =  30;
            var topPos = o.top;

            if(topPos<minTop){
                topPos = minTop;
                marginTop = 0
            }
            var ws = mw.$(window).scrollTop();
            if(topPos<(ws+minTop)){
                topPos=(ws+minTop);
                // marginTop =  -15;
                if(el[0].offsetHeight <100){
                    topPos = o.top+el[0].offsetHeight;
                    marginTop =  0;
                }
            }

            var handleLeft = o.left + pleft;
            if (handleLeft < 0) {
                handleLeft = 0;
            }

            var topPosFinal = topPos //+ marginTop;
            var $lebar = mw.$(lebar), $leoff = $lebar.offset();

            var outheight = el.outerHeight();

            if(topPosFinal < ($leoff.top + $lebar.height())){
                topPosFinal = (o.top + outheight) - (outheight > 100 ? 0 : handle.wrapper.clientHeight);
            }

            if(el.attr('data-type') === 'layouts') {
                topPosFinal = o.top + 10;
                handleLeft = handleLeft + 10;
            }
            var elHeight = el.height();

            handle.positionedAt = 'top';
            if (event.pageY > (o.top + elHeight/2)) {
                topPosFinal += elHeight;
                handle.positionedAt = 'bottom';
            }

            clearTimeout(handle._hideTime);
            handle.show();
            mw.$(handle.wrapper)
                .removeClass('active')
                .css({
                    top: topPosFinal,
                    left: handleLeft,
                    //width: width,
                    //marginTop: marginTop
                }).addClass('mw-active-item');




            var canDrag = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element.parentNode, ['edit', 'module'])
                && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop']);
            if(canDrag){
                mw.$(handle.wrapper).removeClass('mw-handle-no-drag');
            } else {
                mw.$(handle.wrapper).addClass('mw-handle-no-drag');
            }
            if ( !el ) {
                return;
            }
            var title = el.dataset("mw-title");
            var id = el.attr("id");



            var module_type = (el.dataset("type") || el.attr("type"));
            if(typeof(module_type) == 'undefined'){
                return;
            }

            var cln = el[0].querySelector('.cloneable');
            if(cln || mw.tools.hasClass(el[0], 'cloneable')){
                if(($(cln).offset().top - el.offset().top) < 20){
                    mw.tools.addClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                } else {
                    mw.tools.removeClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                }
            }

            var mod_icon = mw.live_edit.getModuleIcon(module_type);
            var mod_handle_title = (title ? title : mw.msg.settings);
            /*if(module_type === 'layouts'){
                mod_handle_title = '';
            }*/

            handle.setTitle(mod_icon, mod_handle_title);
            handle.handleTitle.dataset.tip = mw.lang('Module') + ': ' + mod_handle_title
            handle.handleTitle.classList.add('tip');
             if(!handle) {
                return;
            }

            mw.tools.classNamespaceDelete(handle, 'module-active-');
            mw.tools.addClass(handle, 'module-active-' + module_type.replace(/\//g, '-'));

            if (mw.live_edit_module_settings_array && mw.live_edit_module_settings_array[module_type]) {

                var new_el = document.createElement('div');
                new_el.className = 'mw_edit_settings_multiple_holder';

                var settings = mw.live_edit_module_settings_array[module_type];
                mw.$(settings).each(function () {
                    if (this.view) {
                        var new_el = document.createElement('a');
                        new_el.className = 'mw_edit_settings_multiple';
                        new_el.title = this.title;
                        new_el.draggable = 'false';
                        var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                        new_el.id = btn_id;
                        if (this.type && this.type === 'tooltip') {
                            new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + this.view + '", "tooltip"); void(0);';

                        } else {
                            new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + this.view + '"); void(0);';
                        }
                        var icon = '';
                        if (this.icon) {
                            icon = '<i class="mw-edit-module-settings-tooltip-icon ' + this.icon + '"></i>';
                        }
                        new_el.innerHTML =  (icon + '<span class="mw-edit-module-settings-tooltip-btn-title">' + this.title+'</span>');
                        mw.$(".mw_handle_module_spacing", handle.wrapper).append(new_el);
                    }
                });
            } else {

            }

            /*************************************/


            if(!element.id) {
                element.id = "module_" + mw.random();
            }
            mw._initHandles.manageCollision();
        };

        mw.on('ModuleClick', function(e, pelement, event){
            mw.handleModule.hide();
            positionModuleHandle(e, pelement, mw.handleModuleActive, event);

        });

        mw.on('moduleOver', function (e, pelement, event) {
            if(mw.handleModuleActive._element === pelement) {
                return;
            }
            positionModuleHandle(e, pelement, mw.handleModule, event);
            if(mw._activeModuleOver === mw.handleModuleActive._target) {
                mw.handleModule.hide();
            }

            var nodes = [];
            mw.$('.module', pelement).each(function () {

                var type = this.getAttribute('data-type');

                var hastitle = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : false;
                var icon = mw.live_edit.getModuleIcon(type);
                if(!icon){
                    icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                }
                if (hastitle) {
                    var menuitem = '<span class="mw-handle-menu-item dynamic-submodule-handle" data-module="'+this.id+'">'
                        + icon
                        + hastitle.replace(/_/g, ' ')
                        + '</span>';
                    nodes.push(menuitem);
                }

            });
            var el = mw.$('.mw_handle_module_submodules');
            el.empty();
            $.each(nodes, function () {
                el.append(this);
            });
            mw.$('.text-background', pelement).each(function () {
                var bgEl = this;
                $.each([0,1], function(i){
                    var icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                    var menuitem = document.createElement('span');
                    menuitem.className = 'mw-handle-menu-item text-background-handle';
                    menuitem.innerHTML = icon + 'background text';
                    menuitem.__for = bgEl;
                    // menuitem = mw.$(menuitem);
                    el.eq(i).append(menuitem)
                })

            });


            mw.$('.text-background-handle').on('click', function () {
                var ok = mw.$('<button class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info pull-right">OK</button>');
                var cancel = mw.$('<button class="mw-ui-btn mw-ui-btn-medium pull-left">Cancel</button>');
                var footer = mw.$('<div></div>');
                var area = $('<textarea class="mw-ui-field w100" style="height: 200px"/>');
                var node = this.__for;
                area.val(mw.$(node).html());
                footer.append(cancel);
                footer.append(ok);
                var dialog = mw.dialog({
                    content: area,
                    footer: footer
                });
                ok.on('click', function () {
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                    mw.$(node).html(area.val());
                    dialog.remove();
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                });
                cancel.on('click', function () {
                    dialog.remove();
                });
            });
            mw.$('.dynamic-submodule-handle').on('click', function () {
                mw.tools.module_settings('#' + this.dataset.module);
            });
        });
    },
    columns:function(){
        mw.handleColumns = new mw.Handle({
            id: 'mw-handle-item-columns',
            // className:'mw-handle-type-element',
            menu:[
                {
                    title: 'One column',
                    action: function () {
                        mw.drag.create_columns(this,1);
                    }
                },
                {
                    title: '2 columns',
                    action: function () {
                        mw.drag.create_columns(this,2);
                    }
                },
                {
                    title: '3 columns',
                    action: function () {
                        mw.drag.create_columns(this,3);
                    }
                },
                {
                    title: '4 columns',
                    action: function () {
                        mw.drag.create_columns(this,4);
                    }
                },
                {
                    title: '5 columns',
                    action: function () {
                        mw.drag.create_columns(this,5);
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeRowOver, function () {
                            mw.$(mw.drag.columns.resizer).hide();
                            mw.handleColumns.hide();
                        });
                    }
                }
            ]
        });
        mw.handleColumns.setTitle('<span class="mw-handle-columns-icon"></span>', '');

        mw.$(mw.handleColumns.wrapper).draggable({
            handle: mw.handleColumns.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                var curr = mw._activeRowOver ;
                mw.dragCurrent = mw.ea.data.currentGrabbed = curr;
                handleDomtreeSync.start = mw.dragCurrent.parentNode;
                mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'element_' + mw.random() : '';
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
                mw.$(mw.drag.columns.resizer).hide()
            },
            stop: function() {
                mw.$(document.body).removeClass("dragStart");
                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.on("RowOver", function(a, element) {

            mw._activeRowOver = element;
            var el = mw.$(element);
            var o = el.offset();
            var htop = o.top - 35;
            var left = o.left;

            if (htop < 55 && document.getElementById('live_edit_toolbar') !== null) {
                htop = 55;
                left = left - 100;
            }
            if (htop < 0 && document.getElementById('live_edit_toolbar') === null) {
                htop = 0;
            }


            mw.handleColumns.show()

            mw.$(mw.handleColumns.wrapper).css({
                top: htop,
                left: left,
                //width: width
            });
            mw._initHandles.manageCollision();

            var size = mw.$(element).children(".mw-col").length;
            mw.$("a.mw-make-cols").removeClass("active");
            mw.$("a.mw-make-cols").eq(size - 1).addClass("active");
            if(!element.id){
                element.id = "element_row_" + mw.random() ;
            }




        });
    },
    nodeLeave: function () {
        var scope = this;

        mw.on("ElementLeave", function(e, target) {
            mw.handleElement.hide();
        });
        mw.on("ModuleLeave", function(e, target) {
            clearTimeout(mw.handleModule._hideTime);
            mw.handleModule._hideTime = setTimeout(function () {
                mw.handleModule.hide();
            }, 3000);

            //.removeClass('mw-active-item');
        });
        mw.on("RowLeave", function(e, target) {
            //mw.handleColumns.hide();
        });
    }
};




$(document).ready(function () {

    mw._initHandles.modules();
    mw._initHandles.elements();
    mw._initHandles.columns();
    mw._initHandles.nodeLeave();



});

})();

(() => {
/*!*******************************!*\
  !*** ../liveedit/initload.js ***!
  \*******************************/
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
/*!********************************!*\
  !*** ../liveedit/initready.js ***!
  \********************************/
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
/*!*****************************!*\
  !*** ../liveedit/inline.js ***!
  \*****************************/
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
/*!*********************************!*\
  !*** ../liveedit/layoutplus.js ***!
  \*********************************/
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
/*!********************************!*\
  !*** ../liveedit/live_edit.js ***!
  \********************************/
mw.live_edit = mw.live_edit || {};

mw.live_edit.registry = mw.live_edit.registry || {};

mw.live_edit.hasAbilityToDropElementsInside = function(target) {
    var items = /^(span|h[1-6]|hr|ul|ol|input|table|b|em|i|a|img|textarea|br|canvas|font|strike|sub|sup|dl|button|small|select|big|abbr|body)$/i;
    if (typeof target === 'string') {
        return !items.test(target);
    }
    if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['allow-drop', 'nodrop'])){
        return false;
    }
    if(mw.tools.hasAnyOfClasses(target, ['plain-text'])){
        return false;
    }
    var x = items.test(target.nodeName);
    if (x) {
        return false;
    }
    if (mw.tools.hasParentsWithClass(target, 'module')) {
        if (mw.tools.hasParentsWithClass(target, 'edit')) {
            return true;
        } else {
            return false;
        }
    } else if (mw.tools.hasClass(target, 'module')) {
        return false;
    }
    return true;
};



mw.live_edit.showSettings = function (a, opts) {

    var liveedit = opts.liveedit || false;
    var mode = opts.mode ||  'modal';

    var view = opts.view || 'admin';
    var module_type;
    if (typeof a === 'string') {
        module_type = a;
        var module_id = a;
        var mod_sel = mw.$(a + ':first');
        if (mod_sel.length > 0) {
            var attr = $(mod_sel).attr('id');
            if (typeof attr !== typeof undefined && attr !== false) {
                attr = !attr.contains("#") ? attr : attr.replace("#", '');
                module_id = attr;
            }
            var attr2 = $(mod_sel).attr('type');
            attr = $(mod_sel).attr('data-type');
            if (typeof attr !== typeof undefined && attr !== false) {
                module_type = attr;
            } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                module_type = attr2;
            }
            a = mod_sel[0]
        }
    }

    var curr = a || $("#mw_handle_module").data("curr");
    if(!curr){
        return;
    }
    if(typeof(curr) === 'undefined'){
        return;
    }
    var attributes = {};

    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var iframe_id_sidebar = 'js-iframe-module-settings-' + curr.id;
    var iframe_id_sidebar_wrapper_id = 'sidebar-frame-wrapper-' + iframe_id_sidebar;

    var data1 = attributes;

    module_type = null;
    if (data1['data-type'] !== undefined) {
        module_type = data1['data-type'];
        data1['data-type'] = data1['data-type'] + '/admin';
    }
    if (data1['data-module-name'] !== undefined) {
        delete(data1['data-module-name']);
    }
    if (data1['type'] !== undefined) {
        module_type = data1['type'];
        data1['type'] = data1['type'] + '/admin';
    }
    if (module_type != null && view !== undefined) {
        data1['data-type'] = data1['type'] = module_type + '/' + view;
    }
    if (typeof data1['class'] !== 'undefined') {
        delete(data1['class']);
    }
    if (typeof data1['style'] !== 'undefined') {
        delete(data1['style']);
    }
    if (typeof data1.contenteditable !== 'undefined') {
        delete(data1.contenteditable);
    }
    data1.live_edit = liveedit;
    data1.module_settings = 'true';
    if (view !== undefined) {
        data1.view = view;
    }
    else {
        data1.view = 'admin';
    }
    if (data1.from_url == undefined) {
        //data1.from_url = mw.top().win.location;
        data1.from_url = window.parent.location;
    }
    var modal_name = 'module-settings-' + curr.id;
    if (typeof(data1.view.hash) == 'function') {
        //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
    }
    //data1.live_edit_sidebar = true;

    var src = mw.settings.site_url + "api/module?" + json2url(data1);

    if (self !== top || /*!mw.liveEditSettings.active || */ mode === 'modal') {
        //remove from sidebar
        $("#" + iframe_id_sidebar).remove();

        //close sidebar
        if(mw.liveEditSettings && mw.liveEditSettings.active){
             mw.liveEditSettings.hide();
        }
        var has = mw.$('#' + modal_name);
        if(has.length){
            var dialog = mw.dialog.get(has[0]);
            dialog.show();
            return dialog;
        }
        var nmodal = mw.dialogIframe({
            url: src,
            width: 532,
            height: 'auto',
            autoHeight:true,
            id: modal_name,
            title:'',
            className: 'mw-dialog-module-settings',
            closeButtonAction: 'remove'
        });

        nmodal.iframe.contentWindow.thismodal = nmodal;
        return nmodal;

    } else {


        if(!mw.liveEditSettings.active){
            mw.liveEditSettings.show();
        }

        if(mw.sidebarSettingsTabs){
            mw.sidebarSettingsTabs.set(2);
        }



        data1.live_edit_sidebar = true;

        var src = mw.settings.site_url + "api/module?" + json2url(data1);


        var mod_settings_iframe_html_fr = '' +
            '<div class="js-module-settings-edit-item-group-frame loading" id="' + iframe_id_sidebar_wrapper_id + '">' +
            '<iframe src="' + src + '" frameborder="0" onload="this.parentNode.classList.remove(\'loading\')">' +
            '</div>';


        var sidebar_title_box = mw.live_edit.getModuleTitleBar(module_type, curr.id);


         var mod_settings_iframe_html = '<div  id="' + iframe_id_sidebar + '" class="js-module-settings-edit-item-group">'
            + sidebar_title_box
            + mod_settings_iframe_html_fr
            + '</div>';


        if (!$("#" + iframe_id_sidebar).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id_sidebar).length) {
            $('.js-module-settings-edit-item-group').hide();
            $("#" + iframe_id_sidebar).attr('data-settings-for-module', curr.id);

            $("#" + iframe_id_sidebar).show();
        }
    }

};


mw.live_edit.getModuleTitleBar = function (module_type, module_id) {

    var mod_icon = mw.live_edit.getModuleIcon(module_type);
    var mod_title = mw.live_edit.getModuleTitle(module_type);
    var mod_descr = mw.live_edit.getModuleDescription(module_type);

    var sidebar_title_box = "<div class='mw_module_settings_sidebar_title_wrapper js-module-titlebar-"+module_id+"'>" + mod_icon;
    sidebar_title_box = sidebar_title_box + "<div class='js-module-sidebar-settings-menu-holder'>" + "</div>";
    sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_title'>" + mod_title + "</div>";

    if (mod_title != mod_descr) {
        //  sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_description'>" + mod_descr + "</div>";
    }
    sidebar_title_box = sidebar_title_box + "</div>";
    return sidebar_title_box;
};

mw.live_edit.getModuleIcon = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].icon) {
        return '<span class="mw_module_settings_sidebar_icon" style="background-image: url(' + mw.live_edit.registry[module_type].icon + ')"></span>';
    }
    else {
        return '<span class="mw-icon-gear"></span>&nbsp;&nbsp;';
    }
};
mw.live_edit.getModuleTitle = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].title) {
        return mw.live_edit.registry[module_type].title;
    } else if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].name) {
        return mw.live_edit.registry[module_type].name;
    }
    else {
        return '';
    }
};
mw.live_edit.getModuleDescription = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].description) != 'undefined') {
        return mw.live_edit.registry[module_type].description;
    }
    else {
        return '';
    }
};



})();

(() => {
/*!****************************************!*\
  !*** ../liveedit/liveedit_elements.js ***!
  \****************************************/

/**
 * Makes Droppable area
 *
 * @return Dom Element
 */
mw.dropables = {
    prepare: function() {
        var dropable = document.createElement('div');
        dropable.className = 'mw_dropable';
        dropable.innerHTML = '<span class="mw_dropable_arr"></span>';
        document.body.appendChild(dropable);
        mw.dropable = mw.$(dropable);
        mw.dropable.hide = function(){
            return mw.$(this).addClass('mw_dropable_hidden');
        };
        mw.dropable.show = function(){
            return mw.$(this).removeClass('mw_dropable_hidden');
        };
        mw.dropable.hide()
    },
    userInteractionClasses:function(){
        var bgHolders = document.querySelectorAll(".edit.background-image-holder, .edit .background-image-holder, .edit[style*='background-image'], .edit [style*='background-image']");
        var noEditModules = document.querySelectorAll('.module' + mw.noEditModules.join(',.module'));
        var edits = document.querySelectorAll('.edit');
        var i = 0, i1 = 0, i2 = 0;
        for ( ; i<bgHolders.length; i++ ) {
            var curr = bgHolders[i];
            var po = mw.tools.parentsOrder(curr, ['edit', 'module']);
            if(po.module === -1 || (po.edit<po.module && po.edit !== -1)){
                if(!mw.tools.hasClass(curr, 'module')){
                    mw.tools.addClass(curr, 'element');
                }
                curr.style.backgroundImage = curr.style.backgroundImage || 'none';
            }
        }
        for ( ; i1<noEditModules.length; i1++ ) {
            mw.tools.removeClass(noEditModules[i], 'module');
        }
        for ( ; i2<edits.length; i2++ ) {
            var all = mw.ea.helpers.getElementsLike(":not(.element)", edits[i2]), i2a = 0;
            var allAllowDrops = edits[i2].querySelectorAll(".allow-drop"), i3a = 0;
            for( ; i3a<allAllowDrops.length; i3a++){
                mw.tools.addClass(allAllowDrops[i3a], 'element');
            }
            for( ; i2a<all.length; i2a++){
                if(!mw.tools.hasClass(all[i2a], 'module')){
                    if(mw.ea.canDrop(all[i2a])){
                        mw.tools.addClass(all[i2a], 'element');
                    }
                }
            }
        }


        if(document.body.classList){
            var displayEditor = mw.wysiwyg.isSelectionEditable();
            if(!displayEditor){
                var editor = document.querySelector('.mw_editor');
                if(editor && editor.contains(document.activeElement)) displayEditor = true;
            }
            var focusedNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
            var isSafeMode = mw.tools.firstParentOrCurrentWithAnyOfClasses(focusedNode, ['safe-mode']) ;
            var isPlainText = mw.tools.firstParentOrCurrentWithAnyOfClasses(focusedNode, ['plain-text']) ;
            document.body.classList[( displayEditor ? 'add' : 'remove' )]('mw-active-element-iseditable');
            document.body.classList[( isSafeMode ? 'add' : 'remove' )]('mw-active-element-is-in-safe-mode');
            document.body.classList[( isPlainText ? 'add' : 'remove' )]('mw-active-element-is-plain-text');
        }
    },
    findNearest:function(event,selectors){

    selectors = (selectors || mw.drag.section_selectors).slice(0);


    for(var ix = 0 ; i<selectors.length ; ix++){
        selectors[ix] = '.edit ' + selectors[ix].trim();
    }

    selectors = selectors.join(',');

      var coords = { y:99999999 },
          y = mw.event.page(event).y,
          all = document.querySelectorAll(selectors),
          i = 0,
          final = {
            element:null,
            position:null
          };
      for( ; i< all.length; i++){
        var ord = mw.tools.parentsOrder(all[i], ['edit', 'module']);
        if(ord.edit === -1 || ((ord.module !== -1 && ord.edit !== -1 ) && ord.module < ord.edit)){
          continue;
        }
        if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(all[i], ['allow-drop', 'nodrop'])){
          continue;
        }
        var el = mw.$(all[i]), offtop = el.offset().top;
        var v1 = offtop - y;
        var v2 = y - (offtop + el[0].offsetHeight);
        var v = v1 > 0 ? v1 : v2;
        if(coords.y > v){

          final.element = all[i];
        }
        if(coords.y > v && v1 > 0){
          final.position = 'top';
        }
        else if(coords.y > v && v2 > 0){
          final.position = 'bottom';
        }
        if(coords.y > v){

          coords.y = v
        }

      }
      return final;
    },
    display: function(el) {

        el = mw.$(el);
        var offset = el.offset();
        var width = el.outerWidth();
        var height = el.outerHeight();
        mw.dropable.css({
            top: offset.top + height,
            left: offset.left,
            width: width
        });
    },
    set: function(pos, offset, height, width) {
        if (pos === 'top') {

            mw.dropable.css({
                top: offset.top - 2,
                left: offset.left,
                width: width,
                height: ''
            });
            mw.dropable.data("position", "top");
            mw.dropable.addClass("mw_dropable_arr_up");
        } else if (pos === 'bottom') {

            mw.dropable.css({
                top: offset.top + height + 2,
                left: offset.left,
                width: width,
                height: ''
            });
            mw.dropable.data("position", "bottom");
            mw.dropable.removeClass("mw_dropable_arr_up");
            mw.dropable.removeClass("mw_dropable_arr_rigt");
        } else if (pos === 'left') {
            mw.dropable.data("position", 'left');
            mw.dropable.css({
                top: offset.top,
                height: height,
                width: '',
                left: offset.left
            });
        } else if (pos === 'right') {
            mw.dropable.data("position", 'right');
            mw.dropable.addClass("mw_dropable_arr_rigt");
            mw.dropable.css({
                top: offset.top,
                left: offset.left + width,
                height: height,
                width: ''
            });
        }
    }
};


 mw.triggerLiveEditHandlers = {
    cacheEnabled: false,
     reseSetCache: function(key) {
        this[key] = {};
     },
    shouldTrigger:function(key, node) {
        if(!this.cacheEnabled) return true;
        var countMax = 3;
        if(!this[key] || this[key].node !== node) {
            this[key] = {
                node:node,
                count:0
            };
        }
        if(this[key].count < countMax) {
            this[key].count++;
            return true;
        }
        return false;
    },
    _moduleRegister: null,
    module: function(ev){
        targetFrom = ev ? ev.target :  mw.mm_target;
        var module = mw.tools.firstMatchesOnNodeOrParent(targetFrom, '.module:not(.no-settings)');
        //var module = mw.tools.lastMatchesOnNodeOrParent(targetFrom, '.module:not(.no-settings)');
        var triggerTarget =  module.__disableModuleTrigger || module;
        if(module){
            //if(this.shouldTrigger('_moduleRegister', triggerTarget)) {
                mw.trigger("moduleOver", [triggerTarget, ev]);
            //}
        } else {
            if (
                mw.mm_target.id !== 'mw-handle-item-module'
                && mw.mm_target.id !== 'mw-handle-item-module-active'
                && !mw.tools.hasParentWithId(mw.mm_target, 'mw-handle-item-module')
                && !mw.tools.hasParentWithId(mw.mm_target, 'mw-handle-item-module-active')
                && !mw.tools.hasAnyOfClassesOnNodeOrParent(mw.mm_target, ['mwInaccessibleModulesMenu'])) {
                /*if(this._moduleRegister !== null) {*/
                    mw.trigger("ModuleLeave", mw.mm_target);
                    /*this._moduleRegister = null;
                }*/
            }
        }
    },
    cloneable: function () {
        var cloneable = mw.tools.firstParentOrCurrentWithAnyOfClasses(mw.mm_target, ['cloneable', 'mw-cloneable-control']);

        if(!!cloneable){
            if(mw.tools.hasClass(cloneable, 'mw-cloneable-control')){
                mw.trigger("CloneableOver", [mw.drag._onCloneableControl.__target, true]);
            }
            else if(mw.tools.hasParentsWithClass(cloneable, 'mw-cloneable-control')){
                mw.trigger("CloneableOver", [mw.drag._onCloneableControl.__target, true]);
            }
            else{
                mw.trigger("CloneableOver", [cloneable, false]);
            }

        }
        else{
            if(mw.drag._onCloneableControl && mw.mm_target !== mw.drag._onCloneableControl){
                mw.drag._onCloneableControl.style.display = 'none';
            }
        }
    },
    _elementRegister:null,
    element: function(ev) {
        var element = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'element');
        if(element /*&& this._elementRegister !== element*/) {
            this._elementRegister = element;
            if (!mw.tools.hasClass(element, 'module')
                && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['edit', 'module'])
                    && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop']))) {

                mw.trigger("ElementOver", [element, ev]);
            }
            else /*if(this._elementRegister !== null)*/{
                //if (!mw.tools.firstParentOrCurrentWithId(mw.mm_target, 'mw_handle_element')) {
                    this._elementRegister = null;
                    //mw.trigger("ElementLeave", element);
                //}
            }
        } else if(!element && !mw.tools.firstParentOrCurrentWithId(mw.mm_target, 'mw-handle-item-element')) {
            this._elementRegister = null;
            mw.trigger("ElementLeave");
        }
        if (mw.mm_target === mw.image_resizer && this._elementRegister !== mw.image.currentResizing[0]) {
            this._elementRegister = mw.image.currentResizing[0];
            mw.trigger("ElementOver", [mw.image.currentResizing[0], ev]);
        }
    },
    _layoutRegister:null,
    layout: function () {
         var targetLayout = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-layout-root');
         if (targetLayout && this._layoutRegister !== targetLayout) {
             this._layoutRegister = targetLayout;
             mw.trigger("LayoutOver", targetLayout);
         }
    },
     _rowRegister:null,
    row: function () {
         var row = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-row');

         if (row && this._rowRegister !== row) {
             this._rowRegister = row;
             mw.trigger("RowOver", row);
         } else if (this._rowRegister !== null) {
             this._rowRegister = null;
             mw.trigger("RowLeave", mw.mm_target);
         }
    },
     col: function () {
            if (!mw.drag.columns.resizing) {
                var column = mw.tools.firstParentOrCurrentWithClass(mw.mm_target, 'mw-col');
                if (column) {
                    mw.trigger("ColumnOver", column);
                } else {
                    mw.trigger("ColumnOut", mw.mm_target);
                }
            }
     }
 };
 mw.liveEditHandlers = function(event){
    if (mw.drag.columns.resizing === false ) {
        mw.triggerLiveEditHandlers.cloneable(event);
        mw.triggerLiveEditHandlers.layout(event);
        mw.triggerLiveEditHandlers.element(event);
        mw.triggerLiveEditHandlers.module(event);
        if (mw.drag.columns.resizing === false && mw.tools.hasParentsWithClass(mw.mm_target, 'edit') && (!mw.tools.hasParentsWithClass(mw.mm_target, 'module') ||
            mw.tools.hasParentsWithClass(mw.mm_target, 'allow-drop'))) {
            mw.triggerLiveEditHandlers.row();
            mw.triggerLiveEditHandlers.col();
        }
    }

    mw.image._dragTxt(event);

    var bg, bgTarget, bgCanChange;
    if(event.target){
      bg = event.target.style && event.target.style.backgroundImage && !mw.tools.hasClass(event.target, 'edit');
      bgTarget = event.target;
      if(!bg){
          var _c = 0, bgp = event.target;
          while (!bg || bgp === document.body){
              bgp = bgp.parentNode;
              if(!bgp) {
                  break;
              }
              _c++;
              bg = bgp.style && bgp.style.backgroundImage && !mw.tools.hasClass(bgp, 'edit');
              bgTarget = bgp;
          }
      }
    }

    if(bg){
        bgCanChange = mw.drag.columns.resizing === false
        && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(bgTarget, ['edit','module']) || mw.tools.hasClass(event.target, 'element'));
    }

    if (!mw.image.isResizing && mw.image_resizer) {

        if (event.target.nodeName === 'IMG' && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(event.target, ['edit','module'])) && mw.drag.columns.resizing === false) {
            mw.image_resizer._show();
            mw.image.resize.resizerSet(event.target, false);
        }
        else if (bg && bgCanChange) {
            mw.image_resizer._show();
            mw.image.resize.resizerSet(bgTarget, false);
        }

        else if(mw.tools.hasClass(mw.mm_target, 'mw-image-holder-content')||mw.tools.hasParentsWithClass(mw.mm_target, 'mw-image-holder-content')){
            mw.image_resizer._show();
            mw.image.resize.resizerSet(mw.tools.firstParentWithClass(mw.mm_target, 'mw-image-holder').querySelector('img'), false);
        }
        else {
            if (!event.target.mwImageResizerComponent) {
                if(mw.image_resizer){
                    mw.image_resizer._hide();
                }
            }
        }
    }
};


mw.liveNodeSettings = {
    _working: false,
    set: function (type, el) {
        if (this._working) return;
        this._working = true;
        var scope = this;
        setTimeout(function () {
            scope._working = false;
        }, 78);

        if(this[type]){
            mw.sidebarSettingsTabs.set(2);
            return this[type](el);
        }
    },
    element: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

    },
    none: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

    },
    module: function (el) {
        mw.live_edit.showSettings(undefined, {mode:"sidebar", liveedit:true})
    },
    image: function (el) {
        if (!this.__is_sidebar_opened()) {
            return;
        }

        mw.$("#mw-live-edit-sidebar-image-frame")
            .contents()
            .find("#mwimagecurrent")
            .attr("src", el.src)

    },
    initImage: function () {
        var url = mw.external_tool('imageeditor');
        mw.$("#js-live-edit-image-settings-holder").append('<iframe src="' + url + '" frameborder="0" id="mw-live-edit-sidebar-image-frame"></iframe>');
    },
    icon: function () {

    },
    __is_sidebar_opened: function () {
        if (mw.liveEditSettings  &&  mw.liveEditSettings.active) {
            return true;
        }
    }
};

$(document).ready(function(){
    mw.on('liveEditSettingsReady', function(){
        mw.liveNodeSettings.initImage();
    });
});

})();

(() => {
/*!***************************************!*\
  !*** ../liveedit/liveedit_widgets.js ***!
  \***************************************/
mw.liveEditWidgets = {
    _cssEditorInSidebarAccordion : null,
    cssEditorInSidebarAccordion : function () {
        if(!this._cssEditorInSidebarAccordion){
            this._cssEditorInSidebarAccordion = document.createElement('iframe') ;
            this._cssEditorInSidebarAccordion.id = 'mw-css-editor-sidebar-iframe' ;
            this._cssEditorInSidebarAccordion.src = mw.external_tool('rte_css_editor');
            this._cssEditorInSidebarAccordion.style.opacity = 0;
            this._cssEditorInSidebarAccordion.scrolling = 'no';
            this._cssEditorInSidebarAccordion.frameBorder = 0;
            var holder = document.querySelector('#mw-css-editor-sidebar-iframe-holder');
            holder.appendChild(this._cssEditorInSidebarAccordion);
            mw.tools.loading(holder, 90);
            mw.tools.iframeAutoHeight(this._cssEditorInSidebarAccordion);
            this._cssEditorInSidebarAccordion.onload = function () {
                this.contentWindow.document.body.style.padding = 0;
                this.contentWindow.document.body.style.backgroundColor = 'transparent';
                mw.tools.loading(holder, false);
                this.style.opacity = 1;
            };
            mw.$(this._cssEditorInSidebarAccordion)
                .height($(this._cssEditorInSidebarAccordion)
                    .parents('.mw-ui-box').outerHeight() -
                    mw.$(this._cssEditorInSidebarAccordion).parents('.tabitem').outerHeight());
        }
        return this._cssEditorInSidebarAccordion;
    },
    _tplSettings : null,
    loadTemplateSettings: function (url) {
        if (!this._tplSettings) {
            this._tplSettings = document.createElement('iframe') ;
            this._tplSettings.id = 'mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame' ;
            this._tplSettings.className = 'mw-live-edit-sidebar-settings-iframe' ;
            this._tplSettings.src = url;
            this._tplSettings.scrolling = 'no';
            this._tplSettings.frameBorder = 0;
            mw.$('#mw-live-edit-sidebar-settings-iframe-holder-template-settings').html(this._tplSettings);
            mw.tools.iframeAutoHeight(this._tplSettings);
            this._tplSettings.onload = function () {
                this.contentWindow.document.querySelector('.mw-module-live-edit-settings').style.padding = 0;
            };
            mw.$(this._tplSettings)
                .height($(this._tplSettings)
                        .parents('.mw-ui-box').outerHeight() -
                    mw.$(this._tplSettings).parents('.tabitem').outerHeight());
        }
        return this._tplSettings;
    }
};

mw.liveEditTools = {
    isLayout: function (node) {
        return (!!node && !!node.getAttribute && (node.getAttribute('data-module-name') === 'layouts' || node.getAttribute('data-type') === 'layouts'));
    }
};

})();

(() => {
/*!*************************************!*\
  !*** ../liveedit/manage.content.js ***!
  \*************************************/
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
/*!**************************************!*\
  !*** ../liveedit/modules.toolbar.js ***!
  \**************************************/
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
/*!******************************!*\
  !*** ../liveedit/padding.js ***!
  \******************************/

(function(mw){

    mw.paddingEditor = function( options ) {

        options = options || {};

        var defaults = {
            height: 10
        };

        this.settings = $.extend({}, defaults, options);

        this._pageY = -1;
        this._active = null;
        this._paddingTopDown = false;
        this._paddingBottomDown = false;
        var scope = this;

        this.create = function() {
            this.paddingTop = document.createElement('div');
            this.paddingTop.className = 'mw-padding-ctrl mw-padding-ctrl-top';

            this.paddingBottom = document.createElement('div');
            this.paddingBottom.className = 'mw-padding-ctrl mw-padding-ctrl-bottom';

            this.paddingTop.style.height = this.settings.height + 'px';
            this.paddingBottom.style.height = this.settings.height + 'px';


            // this.paddingBottom.style.visibility = 'hidden';
            // this.paddingTop.style.visibility = 'hidden';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };


        this.record = function() {
            if(!scope._active){
                return;
            }

            var rec_scope = scope._active;
            if(rec_scope.parentNode){
                rec_scope = rec_scope.parentNode;
            }
        //    var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(scope._active.parentNode, ['edit', 'element', 'module']);
            var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(rec_scope, ['edit', 'element', 'module']);
            mw.liveEditState.record({
                target:root,
                value: root.innerHTML
            });
        };



        if(scope && scope._active){

        }
        this.handleMouseMove = function() {
            mw.$(this.paddingTop).on('mousedown touchstart', function(){
                scope._paddingTopDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
            });
            mw.$(this.paddingBottom).on('mousedown touchstart', function(){
                scope._paddingBottomDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
                scope.record();
            });
            mw.$(document.body).on('mouseup touchend', function(){
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';

                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                scope.activeMark(false);
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.event.windowLeave(function (e) {
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';
                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.$(document).on('mousemove touchmove', function(e){
                if(scope._active){
                    var isDown = e.pageY < scope._pageY;
                    var inc = isDown ? scope._pageY - e.pageY : e.pageY - scope._pageY;
                    var curr;
                    if(scope && scope._active && scope._paddingTopDown){
                        scope._working = true;
                        curr = scope._active._currPaddingTop || (parseFloat($(scope._active).css('paddingTop')));

                        if(isDown){
                            scope._active.style.paddingTop = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingTop = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingTop = parseFloat(scope._active.style.paddingTop);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    } else if(scope && scope._active && scope._paddingBottomDown){
                        scope._working = true;
                        curr = scope._active._currPaddingBottom || (parseFloat($(scope._active).css('paddingBottom')));
                        if(isDown){
                            scope._active.style.paddingBottom = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingBottom = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingBottom = parseFloat(scope._active.style.paddingBottom);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    }
                    scope._pageY = e.pageY;
                    scope._activePadding = curr;

                }

                if (scope._active && mw.liveedit.data.get('move', 'hasLayout')) {
                    scope.show();
                    scope.position();
                } else {
                    scope.hide();
                }
            });
        };
        this.show = function(){
            scope.paddingTop.style.display = 'block';
            scope.paddingBottom.style.display = 'block';
        };

        this.hide = function(){
            scope.paddingTop.style.display = 'none';
            scope.paddingBottom.style.display = 'none';
        };

        this.position = function(targetIsLayout) {
            var $el = mw.$(targetIsLayout);
            var off = $el.offset();
            scope._active = targetIsLayout;
            scope.paddingTop.style.top = off.top + 'px';
            scope.paddingBottom.style.top = (off.top + $el.outerHeight() - this.settings.height) + 'px';
        };

        this.selectors = [
            '.mw-padding-gui-element',
            '.mw-padding-control-element',
        ];

        this.prepareSelectors = function(){
            /* var i = 0;
            for( ; i < this.selectors.length; i++){
                if(this.selectors[i].indexOf('[id') === -1){
                    this.selectors[i] += '[id]';
                }
            } */
        };

        this.addSelector = function(selector){
            this.selectors.push(selector);
            this.prepareSelectors();
        };

        this.eventsHandlers = function() {
            mw.on('moduleOver ModuleClick', function(e, el){
                if(!scope._working){
                    var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(el, scope.selectors);
                    if(targetIsLayout){
                        if(mw.tools.hasClass(targetIsLayout, 'module')){
                            var child = mw.$(targetIsLayout).children(scope.selectors.join(','))[0];
                            targetIsLayout = child || targetIsLayout;
                        }
                        scope.position(targetIsLayout);
                    } else {

                    }
                }
            });
        };

        this.init = function() {
            this.create();
            this.eventsHandlers();
            this.handleMouseMove();
            this.prepareSelectors();
            this.hide();
        };

        this.activeMark = function (state) {
            if(typeof state === 'undefined') {
                state = false;
            }
            if(!this._activeMark) {
                this._activeMark = document.createElement('div');
                this._activeMark.className = 'mw-padding-control-active-mark';
                document.body.appendChild(this._activeMark);
            }
            if (state) {
                mw.tools.addClass(this._activeMark, 'active');
                var active = scope._paddingTopDown ? scope.paddingTop : scope.paddingBottom;
                var off = scope._active.getBoundingClientRect();
                this._activeMark.style.left = off.left + 'px';
                this._activeMark.style.width = off.width + 'px';
                this._activeMark.style.height = scope._activePadding + 'px';
                if (scope._paddingTopDown) {
                    this._activeMark.style.top = (off.top + scrollY) + 'px';
                } else {
                    this._activeMark.style.top = ((off.top + scrollY + mw.$(scope._active).outerHeight()) - parseFloat(scope._active.style.paddingBottom)) + 'px';
                }
            } else {
                mw.tools.removeClass(this._activeMark, 'active');
            }
        };

        this.generateCSS = function(obj, media){
            if(!obj || !scope._active) return;

            media = (media || 'all').trim();
            var selector = mw.tools.generateSelectorForNode(scope._active);
            var objCss = '{';
            for (var i in obj) {
                objCss += (i + ':' + obj[i] + ';');
            }
            objCss += '}';
            var css = '@media ' + media  + ' {' + selector + objCss + '}';
            return css;
        };

        this.info = function() {
            if(!this._info){
                this._info = document.createElement('div');
                this._info.className = 'mw-padding-control-info';
                document.body.appendChild(this._info);
            }
            mw.$(this._info).addClass('active');
            var off;
            if (scope._paddingTopDown) {
                off = mw.$(scope.paddingTop).offset();
                this._info.style.top = (off.top + scope.settings.height) + 'px';
                this._info.innerHTML = scope._active.style.paddingTop;
            } else if (scope._paddingBottomDown) {
                off = mw.$(scope.paddingBottom).offset();
                this._info.style.top = (off.top - scope.settings.height - 30) + 'px';
                this._info.innerHTML = scope._active.style.paddingBottom;
            }
            this._info.style.left = (off.left + (scope._active.clientWidth/2)) + 'px';
        };
        this.init();
    };

})(window.mw);

})();

(() => {
/*!***************************!*\
  !*** ../liveedit/plus.js ***!
  \***************************/
mw.drag.plus = {
    locked: false,
    disabled: false,
   // mouse_moved: false,
    init: function (holder) {

        if(this.disabled) return;

        mw.drag.plusTop = document.querySelector('.mw-plus-top');
        mw.drag.plusBottom = document.querySelector('.mw-plus-bottom');

        if(mw.drag.plusTop) {
            mw.drag.plusTop.style.top = -9999 + 'px';
        }
        if(mw.drag.plusBottom) {
            mw.drag.plusBottom.style.top = -9999 + 'px';
        }
        mw.$(holder).on('mousemove touchmove click', function (e) {


            if (mw.drag.plus.locked === false && mw.isDrag === false) {
                if (e.pageY % 2 === 0 && mw.tools.isEditable(e)) {
                    var whichPlus;

                    var node = mw.drag.plus.selectNode(e.target);
                    if(node && e.type === 'mousemove') {
                        var off = $(node).offset();
                        whichPlus = (e.pageY - off.top) > ((off.top + node.offsetHeight) - e.pageY) ? 'top' : 'bottom';
                    }
                    mw.drag.plus.set(node, whichPlus);
                    mw.$(document.body).removeClass('editorKeyup');
                }
            }
            else {
                mw.drag.plusTop.style.top = -9999 + 'px';
                mw.drag.plusBottom.style.top = -9999 + 'px';
            }
        });
        mw.$(holder).on('mouseleave', function (e) {
            if (mw.drag.plus.locked === false && (e.target !== mw.drag.plusTop && e.target !== mw.drag.plusBottom) ) {
                mw.drag.plus.set(undefined);
            }
        });
        mw.drag.plus.action();
    },
    selectNode: function (target) {

        if(!target || mw.tools.hasAnyOfClassesOnNodeOrParent(target, ['noplus', 'noedit', 'noplus']) || mw.tools.hasClass(target, 'edit')) {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return;
        }
        var comp = mw.tools.firstMatchesOnNodeOrParent(target, ['.module', '.element', 'p', '.mw-empty']);

        if (comp
            // && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['regular-mode', 'safe-mode'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']))  {
            return comp;
        }
        else {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
    },
    set: function (node, whichPlus) {
            if (typeof node === 'undefined') {
                return;
            }
            var $node = mw.$(node)
            var off = $node.offset(),
                toolbar = document.querySelector('#live_edit_toolbar');
            var oleft = Math.max(0, off.left - 10);
            if(toolbar && off.top < toolbar.offsetHeight){
              off.top = toolbar.offsetHeight + 10;
            }
            mw.drag.plusTop.style.top = off.top + 'px';
            mw.drag.plusTop.style.left = oleft + ($node.width()/2) + 'px';
            // mw.drag.plusTop.style.display = 'block';
            mw.drag.plusTop.currentNode = node;
            mw.drag.plusBottom.style.top = (off.top + node.offsetHeight) + 'px';
            mw.drag.plusBottom.style.left = oleft + ($node.width()/2) + 'px';
            if(whichPlus) {
                if(whichPlus === 'top') {
                    mw.drag.plusTop.style.top = -9999 + 'px';

                } else {
                     mw.drag.plusBottom.style.top = -9999 + 'px';
                }
            }
            mw.drag.plusBottom.currentNode = node;
            mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');

    },
    tipPosition: function (node) {
        return 'right-center';
        var off = mw.$(node).offset();
        if (off.top > 130) {
            if ((off.top + node.offsetHeight) < ($(document.body).height() - 130)) {
                return 'right-center';
            }
            else {
                return 'right-bottom';
            }
        }
        else {
            return 'right-top';
        }
    },
    rendModules: function (el) {
        var other = el === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
        if (!mw.tools.hasClass(el, 'active')) {
            mw.tools.addClass(el, 'active');
            mw.tools.removeClass(other, 'active');
            mw.drag.plus.locked = true;
            mw.$('.mw-tooltip-insert-module').remove();
            mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';
            var tip = new mw.tooltip({
                content: document.getElementById('plus-modules-list').innerHTML,
                element: el,
                position: mw.drag.plus.tipPosition(this.currentNode),
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector'
            });
            setTimeout(function (){
                $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
            }, 10)
            mw.tabs({
                nav: tip.querySelectorAll('.mw-ui-btn'),
                tabs: tip.querySelectorAll('.module-bubble-tab'),
            });

            mw.$('.mw-ui-searchfield', tip).on('keyup paste', function () {
                var resultsLength = mw.drag.plus.search(this.value, tip);
                if (resultsLength === 0) {
                    mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                }
                else {
                    mw.$(".module-bubble-tab-not-found-message").hide();
                }
            });
            mw.$('#plus-modules-list li').each(function () {
                var name = mw.$(this).attr('data-module-name');
                if(name === 'layout'){
                    var template = mw.$(this).attr('template');
                    mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className, template:"'+template+'"})');
                } else {
                    mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className})');
                }
            });

        }
    },
    action: function () {
        var pls = [mw.drag.plusTop, mw.drag.plusBottom];
        var $pls = mw.$(pls);
        $pls.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-module-plus-hover');
            mw.liveEditSelector.select(mw.drag.plusTop.currentNode);
        });
        $pls.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-module-plus-hover')
        });
        $pls.on('click', function () {
            mw.drag.plus.rendModules(this)
        });

    },
    search: function (val, root) {
        var all = root.querySelectorAll('.module_name'),
            l = all.length,
            i = 0;
        val = val.toLowerCase();
        var found = 0;
        var isEmpty = val.replace(/\s+/g, '') === '';
        for (; i < l; i++) {
            var text = all[i].innerHTML.toLowerCase();
            var li = mw.tools.firstParentWithTag(all[i], 'li');
            var filter = (li.dataset.filter || '').trim().toLowerCase();
            if (text.contains(val) || isEmpty) {
                li.style.display = '';
                if (text.contains(val)) found++;
            }
            else if(filter.contains(val)){
                li.style.display = '';
                found++;
            }
            else {
                li.style.display = 'none';
            }
        }
        return found;
    }
};


var insertModule = function (target, module, config, pos) {
    return new Promise(function (resolve) {
        pos = pos || 'bottom';
        var action;
        var id = mw.id('mw-module-'), el = '<div id="' + id + '"></div>';
        if (pos === 'top') {
            action = 'before';
            if(mw.tools.hasClass(target, 'allow-drop')) {
                action = 'prepend';
            }
        } else if (pos === 'bottom') {
            action = 'after';
            if(mw.tools.hasClass(target, 'allow-drop')) {
                action = 'append';
            }
        }
        mw.$(mw.drag.plusBottom.currentNode)[action](el);
        mw.load_module(module, '#' + id, function () {
            resolve(this);
        }, config);
    });
};

InsertModule = function (module, cls, action) {

    var position = mw.drag.plusActive === 'top' ? 'top' : 'bottom';

    insertModule(mw.drag.plusTop.currentNode, module, cls, position).then(function (el) {
        mw.wysiwyg.change(el);
        mw.drag.plus.locked = false;
        mw.drag.fixes();
        setTimeout(function () { mw.drag.fix_placeholders(); }, 40);
        mw.dropable.hide();
    });
    mw.$('.mw-tooltip').hide();

};



})();

(() => {
/*!*******************************!*\
  !*** ../liveedit/selector.js ***!
  \*******************************/
(function (){
    var css = function(element, css){
        for(var i in css){
            element.style[i] = typeof css[i] === 'number' ? css[i] + 'px' : css[i];
        }
    };

    mw.Selector = function(options) {

        options = options || {};

        var defaults = {
            autoSelect: true,
            document: document,
            toggleSelect: false, // second click unselects element
            strict: false // only match elements that have id
        };

        this.options = $.extend({}, defaults, options);
        this.document = this.options.document;


        this.buildSelector = function(){
            var stop = this.document.createElement('div');
            var sright = this.document.createElement('div');
            var sbottom = this.document.createElement('div');
            var sleft = this.document.createElement('div');

            stop.className = 'mw-selector mw-selector-top';
            sright.className = 'mw-selector mw-selector-right';
            sbottom.className = 'mw-selector mw-selector-bottom';
            sleft.className = 'mw-selector mw-selector-left';

            this.document.body.appendChild(stop);
            this.document.body.appendChild(sright);
            this.document.body.appendChild(sbottom);
            this.document.body.appendChild(sleft);

            this.selectors.push({
                top:stop,
                right:sright,
                bottom:sbottom,
                left:sleft,
                active:false
            });
        };
        this.getFirstNonActiveSelector = function(){
            var i = 0;
            for( ; i <  this.selectors.length; i++){
                if(!this.selectors[i].active){
                    return this.selectors[i]
                }
            }
            this.buildSelector();
            return this.selectors[this.selectors.length-1];
        };
        this.deactivateAll = function(){
            var i = 0;
            for( ; i <  this.selectors.length; i++){
                this.selectors[i].active = false;
            }
        };


        this.pause = function(){
            this.active(false);
            this.hideAll();
        };
        this.hideAll = function(){
            var i = 0;
            for( ; i <  this.selectors.length; i++){
                this.hideItem(this.selectors[i]);
            }
            this.hideItem(this.interactors)
        };

        this.hideItem = function(item){

            item.active = false;
            for (var x in item){
                if(!item[x]) continue;
                item[x].style.visibility = 'hidden';
            }
        };
        this.showItem = function(item){
            for (var x in item) {
                if(typeof item[x] === 'boolean' || !item[x].className || item[x].className.indexOf('mw-selector') === -1) continue;
                item[x].style.visibility = 'visible';
            }
        };

        this.buildInteractor = function(){
            var stop = this.document.createElement('div');
            var sright = this.document.createElement('div');
            var sbottom = this.document.createElement('div');
            var sleft = this.document.createElement('div');

            stop.className = 'mw-selector mw-interactor mw-selector-top';
            sright.className = 'mw-selector mw-interactor mw-selector-right';
            sbottom.className = 'mw-selector mw-interactor mw-selector-bottom';
            sleft.className = 'mw-selector mw-interactor mw-selector-left';

            this.document.body.appendChild(stop);
            this.document.body.appendChild(sright);
            this.document.body.appendChild(sbottom);
            this.document.body.appendChild(sleft);

            this.interactors = {
                top:stop,
                right:sright,
                bottom:sbottom,
                left:sleft
            };
        };
        this.isSelected = function(e){
            var target = e.target?e.target:e;
            return this.selected.indexOf(target) !== -1;
        };

        this.unsetItem = function(e){
            var target = e.target?e.target:e;
            for(var i = 0;i<this.selectors.length;i++){
                if(this.selectors[i].active === target){
                    this.hideItem(this.selectors[i]);
                    break;
                }
            }
            this.selected.splice(this.selected.indexOf(target), 1);
        };

        this.positionSelected = function(){
            for(var i = 0;i<this.selectors.length;i++){
                this.position(this.selectors[i], this.selectors[i].active)
            }
        };
        this.position = function(item, target){
            if( !target ) {
                return;
            }
            var off = mw.$(target).offset();
            css(item.top, {
                top:off.top,
                left:off.left,
                width:target.offsetWidth
            });
            css(item.right, {
                top:off.top,
                left:off.left+target.offsetWidth,
                height:target.offsetHeight
            });
            css(item.bottom, {
                top:off.top+target.offsetHeight,
                left:off.left,
                width:target.offsetWidth
            });
            css(item.left, {
                top:off.top,
                left:off.left,
                height:target.offsetHeight
            });
        };

        this.setItem = function(e, item, select, extend){
            if(!e || !this.active()) return;
            var target = e.target ? e.target : e;
            if (this.options.strict) {
                target = mw.tools.firstMatchesOnNodeOrParent(target, ['[id]', '.edit']);
            }
            var validateTarget = !mw.tools.firstMatchesOnNodeOrParent(target, ['.mw-control-box', '.mw-defaults']);
            if(!target || !validateTarget) return;
            if($(target).hasClass('mw-select-skip')){
                return this.setItem(target.parentNode, item, select, extend);
            }
            if(select){
                if(this.options.toggleSelect && this.isSelected(target)){
                    this.unsetItem(target);
                    return false;
                }
                else{
                    if(extend){
                        this.selected.push(target);
                    }
                    else{
                        this.selected = [target];
                    }
                    mw.$(this).trigger('select', [this.selected]);
                }

            }


            this.position(item, target);

            item.active = target;

            this.showItem(item);
        };

        this.select = function(e, target){
            if(!e && !target) return;
            if(!e.nodeType){
                target = target || e.target;
            } else{
                target = e;
            }

            if(e.ctrlKey){
                this.setItem(target, this.getFirstNonActiveSelector(), true, true);
            }
            else{
                this.hideAll();
                this.setItem(target, this.selectors[0], true, false);
            }

        };

        this.deselect = function(e, target){
            e.preventDefault();
            target = target || e.target;

            this.unsetItem(target);

        };

        this.init = function(){
            this.buildSelector();
            this.buildInteractor();
            var scope = this;
            mw.$(this.root).on("click", function(e){
                if(scope.options.autoSelect && scope.active()){
                    scope.select(e);
                }
            });

            mw.$(this.root).on( "mousemove touchmove touchend", function(e){
                if(scope.options.autoSelect && scope.active()){
                    scope.setItem(e, scope.interactors);
                }
            });
            mw.$(this.root).on( 'scroll', function(){
                scope.positionSelected();
            });
            mw.$(window).on('resize orientationchange', function(){
                scope.positionSelected();
            });
        };

        this._active = false;
        this.active = function (state) {
            if(typeof state === 'undefined') {
                return this._active;
            }
            if(this._active !== state) {
                this._active = state;
                mw.$(this).trigger('stateChange', [state]);
            }
        };
        this.selected = [];
        this.selectors = [];
        this.root = options.root;
        this.init();
    };

})()

})();

(() => {
/*!**********************************!*\
  !*** ../liveedit/source-edit.js ***!
  \**********************************/
mw.editSource = function (node) {
    if (!mw._editSource) {
        mw._editSource = {
            wrapper: document.createElement('div'),
            area: document.createElement('textarea'),
            ok: document.createElement('mwbtn'),
            cancel: document.createElement('mwbtn'),
            nav:document.createElement('div'),
            validator:document.createElement('div')
        };
        mw.$(mw._editSource.ok).addClass('mw-ui-btn mw-ui-btn-medium mw-ui-btn-info').html(mw.lang('OK'));
        mw.$(mw._editSource.cancel).addClass('mw-ui-btn mw-ui-btn-medium').html(mw.lang('Cancel'));

        mw._editSource.wrapper.appendChild(mw._editSource.area);
        mw._editSource.wrapper.appendChild(mw._editSource.nav);
        mw._editSource.nav.appendChild(mw._editSource.cancel);
        mw._editSource.nav.appendChild(mw._editSource.ok);
        mw._editSource.nav.className = 'mw-inline-source-editor-buttons';
        mw._editSource.wrapper.className = 'mw-inline-source-editor';
        document.body.appendChild(mw._editSource.wrapper);
        mw.$(mw._editSource.cancel).on('click', function () {
            mw.$(mw._editSource.target).html(mw._editSource.area.value);
            mw.$(mw._editSource.wrapper).removeClass('active');
            mw._editSource.ok.disabled = false;
        });
        mw.$(mw._editSource.area).on('input', function () {
            mw._editSource.validator.innerHTML = mw._editSource.area.value;
            mw._editSource.ok.disabled = mw._editSource.validator.innerHTML !== mw._editSource.area.value;
            mw._editSource.ok.classList[mw._editSource.ok.disabled ? 'add' : 'remove']('disabled');
            var hasErr = mw.$('.mw-inline-source-editor-error', mw._editSource.nav);
            if(mw._editSource.ok.disabled) {
                if(!hasErr.length) {
                    mw.$(mw._editSource.nav).prepend('<span class="mw-inline-source-editor-error">' + mw.lang('Invalid HTML') + '</span>');
                }
            }
            else {
                hasErr.remove()
            }
        });
        mw.$(mw._editSource.ok).on('click', function () {
            if(!mw._editSource.ok.disabled){
                mw.$(mw._editSource.target).html(mw._editSource.area.value);
                mw.$(mw._editSource.wrapper).removeClass('active');
                mw.wysiwyg.change(mw._editSource.target);
            }
        });
    }
    mw._editSource.area.value = node.innerHTML;
    mw._editSource.target = node;
    var $node = mw.$(node), off = $node.offset(), nwidth = $node.outerWidth();
    var sl = mw.$('.mw-live-edit-sidebar-tabs-wrapper').offset();
    if (off.left + nwidth > sl.left) {
        off.left -= ((off.left + nwidth) - sl.left + 10);
    }
    mw.$(mw._editSource.area)
        .height($node.outerHeight())
        .width(nwidth);

    mw.$(mw._editSource.wrapper)
        .css(off)
        .addClass('active');


};

})();

(() => {
/*!******************************!*\
  !*** ../liveedit/tempcss.js ***!
  \******************************/
mw.tempCSS = function(options){

    var scope = this;

    options = options || {};

    var defaults = {
        document: document,
        css: {}
    };

    this.settings = $.extend({}, defaults, options);

    this.styleElement = function() {
        if(!this._styleElement){
            this._styleElement = this.settings.document.createElement('style');
            this._styleElement.type = 'text/css';
            this._styleElement.appendChild(document.createTextNode('')); // webkit
            this.settings.document.body.appendChild(this._styleElement);
        }
        return this._styleElement;
    };

    this.modifyObject = function(){

    };

    this.addStyle = function(element, style, media){
        if(!element) return;
        if(element.tagName) {
            element = mw.tools.generateSelectorForNode(element);
        }
    };
};

})();

(() => {
/*!******************************!*\
  !*** ../liveedit/toolbar.js ***!
  \******************************/
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
/*!******************************!*\
  !*** ../liveedit/widgets.js ***!
  \******************************/
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

(() => {
/*!******************************!*\
  !*** ../liveedit/wysiwyg.js ***!
  \******************************/
/* WYSIWYG Editor */
/* ContentEditable Functions */


classApplier = window.classApplier || [];
if (!Element.prototype.matches) {
    Element.prototype.matches =
        Element.prototype.matchesSelector ||
        Element.prototype.mozMatchesSelector ||
        Element.prototype.msMatchesSelector ||
        Element.prototype.oMatchesSelector ||
        Element.prototype.webkitMatchesSelector ||
        function (s) {
            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                i = matches.length;
            while (--i >= 0 && matches.item(i) !== this) {
            }
            return i > -1;
        };
}

if (typeof Selection.prototype.containsNode === 'undefined') {
    Selection.prototype.containsNode = function (a) {
        if (this.rangeCount === 0) {
            return false;
        }
        var r = this.getRangeAt(0);
        if (r.commonAncestorContainer === a) {
            return true;
        }
        if (r.endContainer === a) {
            return true;
        }
        if (r.startContainer === a) {
            return true;
        }
        if (r.commonAncestorContainer.parentNode === a) {
            return true;
        }
        if (a.nodeType !== 3) {
            var c = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer),
                b = c.querySelectorAll(a.nodeName.toLowerCase()),
                l = b.length,
                i = 0;
            if (l > 0) {
                for (; i < l; i++) {
                    if (b[i] === a) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

if (typeof Range.prototype.querySelector === 'undefined') {
    Range.prototype.querySelector = function (s) {
        var r = this;
        var f = r.extractContents();
        var node = f.querySelector(s);
        r.insertNode(f);
        return node;
    }
}

if (typeof Range.prototype.querySelectorAll === 'undefined') {
    Range.prototype.querySelectorAll = function (s) {
        var r = this;
        var f = r.extractContents();
        var nodes = f.querySelectorAll(s);
        r.insertNode(f);
        return nodes;
    };
}
mw.wysiwyg = {

    isSafeMode: function (el) {
        if (!el) {
            var sel = window.getSelection();
            if(!sel.rangeCount) return false;
            var range = sel.getRangeAt(0);
            el = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        }
        var hasSafe = mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['safe-mode']);
        var regInsafe = mw.tools.parentsOrCurrentOrderMatchOrNone(el, ['regular-mode', 'safe-mode']);
        return hasSafe && !regInsafe;
    },
    parseClassApplierSheet: function () {
        var sheet = document.querySelector('link[classApplier]');
        if (sheet !== null) {
            var rules = sheet.sheet.rules;
            for (var i = 0; i < rules.length; i++) {
                if (!rules[i].selectorText) continue;

                var rule = rules[i].selectorText.trim();
                var spl = rule.split('.')
                if (rule.indexOf('.') === 0
                    && rule.indexOf(':') === -1
                    && rule.indexOf('#') === -1
                    && spl.length === 2
                    && rule.indexOf('[') === -1) {
                    classApplier.push(spl[1]);
                }
            }
        }
    },
    initClassApplier: function () {
        this.parseClassApplierSheet();
        var dropdown = mw.$('#format_main ul');
        classApplier.forEach(function (cls, i) {
            dropdown.append('<li value=".' + cls + '"><a href="#"><div class="' + cls + '">Custom ' + i + '</div></a></li>')
        })
    },
    editInsideModule: function (el) {
        el = el.target ? el.target : el;
        var order = mw.tools.parentsOrder(el, ['edit', 'module']);
        if (order.edit < order.module) {
            return true;
        }
        else {
            return false;
        }
    },
    pasteFromWordUI: function () {
        if (!mw.wysiwyg.isSelectionEditable()) return false;
        mw.wysiwyg.save_selection();
        var cleaner = mw.$('<div class="mw-cleaner-block" contenteditable="true"><small class="muted">Paste document here.</small></div>')
        var inserter = mw.$('<span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right">Insert</span>')
        var clean = mw.dialog({
            content: cleaner,
            overlay: true,
            title: 'Paste from word',
            footer: inserter,
            height: 'auto',
            autoHeight: true
        });
        cleaner.on('paste', function () {
            setTimeout(function () {
                cleaner[0].innerHTML = mw.wysiwyg.clean_word(cleaner[0].innerHTML);
            }, 100)

        });
        cleaner.on('click', function () {
            if (!$(this).hasClass('active')) {
                mw.$(this).addClass('active')
                mw.$(this).html('')
            }
        });
        inserter.on('click', function () {
            mw.wysiwyg.restore_selection();
            mw.wysiwyg.insert_html(cleaner.html());
            clean.remove();
        });
        //cleaner.after(inserter)
    },
    globalTarget: document.body,
    allStatements: function (c, f) {
        var sel = window.getSelection(),
            range = sel.getRangeAt(0),
            common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        //var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
        var nodrop_state = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(common, ['allow-drop', 'nodrop']);

        if (mw.wysiwyg.isSelectionEditable() && nodrop_state) {
            if (typeof c === 'function') {
                c.call();
            }
        }
        else {
            if (typeof f === 'function') {
                f.call();
            }
        }
    },
    action: {
        removeformat: function () {
            var sel = window.getSelection();
            var r = sel.getRangeAt(0);
            var c = r.commonAncestorContainer;
            mw.wysiwyg.removeStyles(c, sel);
        }
    },
    removeStyles: function (common, sel) {
        if (!!common.querySelectorAll) {
            var all = common.querySelectorAll('*'), l = all.length, i = 0;
            for (; i < l; i++) {
                var el = all[i];
                if (typeof sel !== 'undefined' && sel.containsNode(el, true)) {
                    mw.$(el).removeAttr("style");
                }
            }
        }
        else {
            mw.wysiwyg.removeStyles(common.parentNode);
        }
    },
    init_editables: function (module) {
        if (window['mwAdmin']) {
            if (typeof module !== 'undefined') {
                mw.wysiwyg.contentEditable(module, false);
                mw.$(module.querySelectorAll(".edit")).each(function () {
                    mw.wysiwyg.contentEditable(this, true);
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                    });
                });
            }
            else {
                var editables = document.querySelectorAll('[contenteditable]'), l = editables.length, x = 0;
                for (; x < l; x++) {
                    mw.wysiwyg.contentEditable(editables[x], 'inherit');
                }
                mw.$(".edit").each(function () {
                    mw.on.DOMChange(this, function () {
                        mw.wysiwyg.change(this);
                        if (this.querySelectorAll('*').length === 0 && mw.live_edit.hasAbilityToDropElementsInside(this)) {
                            mw.wysiwyg.modify(this, function () {
                                if (!mw.wysiwyg.isSafeMode(this)) {
                                    this.innerHTML = '<p class="element">' + this.innerHTML + '</p>';
                                }
                            });
                        }
                        mw.wysiwyg.normalizeBase64Images(this);
                    }, false, true);
                    mw.$(this).mouseenter(function () {
                        if (this.querySelectorAll('*').length === 0 && mw.live_edit.hasAbilityToDropElementsInside(this)) {

                            mw.wysiwyg.modify(this, function () {
                                if (!mw.wysiwyg.isSafeMode(this)) {
                                    this.innerHTML = '<p class="element">' + this.innerHTML + '&nbsp;</p>';
                                }
                            });
                        }
                    });
                });
                mw.$(".empty-element, .ui-resizable-handle").each(function () {
                    mw.wysiwyg.contentEditable(this, false);
                });
                mw.on.moduleReload(function () {
                    mw.wysiwyg.nceui();
                })
            }
        }
    },
    modify: function (el, callback) {
        var curr = mw.askusertostay;
        if (typeof el === 'function') {
            callback = el;
            callback.call();
        }
        else {
            callback.call(el);
        }
        mw.askusertostay = curr;
    },
    fixElements: function (parent) {
        var a = parent.querySelectorAll(".element"), l = a.length;
        i = 0;
        for (; i < l; i++) {
            if (a[i].innerHTML === '' || a[i].innerHTML.replace(/\s+/g, '') === '') {
                a[i].innerHTML = '&zwj;&nbsp;&zwj;';
            }
        }
    },
    removeEditable: function (skip) {
        skip = skip || [];
             var i=0, i2,
                all = document.getElementsByClassName('edit'),
                len = all.length;
            for (; i < len; i++) {
                if(skip.length) {
                    var shouldSkip = false;
                    mw.wysiwyg.contentEditable(all[i], false);
                    for (i2=0;i2<skip.length;i2++){
                        if(skip[i2] === all[i]) {
                            shouldSkip = true;
                        }
                    }
                    if(!shouldSkip) {
                        mw.wysiwyg.contentEditable(all[i], false);
                    }

                } else {
                    mw.wysiwyg.contentEditable(all[i], false);
                }

            }

    },
    _lastCopy: null,
    handleCopyEvent: function (event) {
        this._lastCopy = event.target;
    },
    contentEditableSplitTypes: function (el) {

    },
    contentEditable: function (el, state) {
        if (!el) {
            return;
        }
        if(el.nodeType !== 1){
            el = mw.wysiwyg.validateCommonAncestorContainer(el);
            if (!el) {
                return;
            }
        }
        if(typeof state === 'undefined'){
            return el.contentEditable;
        }
        if (state) {
            mw.on.DOMChangePause = true;
            if (!el._handleCopy) {
                el._handleCopy = true;
                mw.$(el).on('copy', function(ev){
                    mw.wysiwyg.handleCopyEvent(ev);
                });
            }
        }
        if(state === true){
            state = 'true';
        } else if(state === false) {
            state = 'false';
        }
        if(state === 'true'){
            if(mw.wysiwyg.isSafeMode(el)){
            } else {

                el = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['edit', 'regular-mode']);
            }
        }
        if (typeof(mw.liveedit) != 'undefined' && mw.liveedit.data.set('mouseup', 'isIcon')) {
            state = false;
        }
        if(el && el.contentEditable !== state) { // chrome setter needs a check

            el.contentEditable = state;
        }

        mw.on.DOMChangePause = false;
    },

    prepareContentEditable: function () {
        mw.on("EditMouseDown", function (e, el, target, originalEvent) {
            mw.$('.safe-mode').each(function () {
                mw.wysiwyg.contentEditable(this, 'inherit');
            });

            if (!mw.wysiwyg.isSafeMode(target)) {

                    var orderValid = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(originalEvent.target, ['edit', 'module']);
                    mw.$('.safe-mode').each(function () {
                        mw.wysiwyg.contentEditable(this, false);
                    });
                    mw.wysiwyg.contentEditable(target, orderValid);

            }
            else {
                var firstBlock = target;
                var blocks = ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'section', 'footer', 'ul', 'ol'];
                var blocksClass = ['safe-element'];
                var po = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(firstBlock, ['edit', 'module']);

                if (po) {
                    if (blocks.indexOf(firstBlock.nodeName.toLocaleLowerCase()) === -1 && !mw.tools.hasAnyOfClassesOnNodeOrParent(firstBlock, blocksClass)) {
                        var cls = [];
                        blocksClass.forEach(function (item) {
                            cls.push('.' + item);
                        });
                        cls = cls.concat(blocks);
                        firstBlock = mw.tools.firstMatchesOnNodeOrParent(firstBlock, cls);
                    }
                     mw.$("[contenteditable='true']").not(firstBlock).attr("contenteditable", "false");
                    mw.wysiwyg.contentEditable(firstBlock, true);
                }

            }


        });
    },
    hide_drag_handles: function () {
        mw.$(".mw-wyswyg-plus-element").hide();
    },
    show_drag_handles: function () {
        mw.$(".mw-wyswyg-plus-element").show();
    },

    _external: function () {
        var external = document.createElement('div');
        external.className = 'wysiwyg_external';
        document.body.appendChild(external);
        return external;
    },
    isSelectionEditable: function (sel) {
        try {
            var node = (sel || window.getSelection()).focusNode;
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
    execCommandFilter: function (a, b, c) {
        var arr = ['justifyCenter', 'justifyFull', 'justifyLeft', 'justifyRight'];
        var align;
        var node = window.getSelection().focusNode;
        var elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        if (a === 'insertorderedlist' || a === 'insertunorderedlist') {
            var paragraph = mw.tools.firstParentOrCurrentWithTag(elementNode, 'p');
            if (paragraph) {
                var tag = a === 'insertorderedlist' ? 'ol' : 'ul';
                var ul = document.createElement(tag);
                var li = document.createElement('li');
                ul.appendChild(li);
                while (paragraph.firstChild) {
                    li.appendChild(paragraph.firstChild);
                }
                paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                paragraph.remove();
                return;
            }
        }



        if (mw.wysiwyg.isSafeMode(elementNode) && arr.indexOf(a) !== -1) {
            align = a.split('justify')[1].toLowerCase();
            if (align === 'full') {
                align = 'justify';
            }
            elementNode.style.textAlign = align;
            mw.wysiwyg.change(elementNode);
            return false;
        }


        if (elementNode.nodeName === 'P') {
            align = a.split('justify')[1].toLowerCase();
            if (align === 'full') {
                align = 'justify';
            }
            elementNode.style.textAlign = align;
            mw.wysiwyg.change(elementNode);
            return false;
        }
        return true;
    },
    execCommand: function (a, b, c) {
        document.execCommand('styleWithCss', 'false', false);
        var sel = getSelection();

        var node = sel.focusNode, elementNode;
        if (node) {
            elementNode = mw.wysiwyg.validateCommonAncestorContainer(node);
        }

        try {  // 0x80004005
            if (document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable()) {
                b = b || false;
                c = c || false;

                var before = mw.$(node).clone()[0];
                if (sel.rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
                    document.execCommand(a, b, c);
                }

                if (node !== null && mw.loaded) {
                    mw.wysiwyg.change(node);
                    mw.trigger('execCommand', [a, node, before, elementNode]);
                }
            }
        }
        catch (e) {
        }
    },
    selection: '',
    _do: function (what) {
        mw.wysiwyg.execCommand(what);
        if (typeof mw.wysiwyg.action[what] === 'function') {
            mw.wysiwyg.action[what]();
        }
    },
    save_selected_element: function () {
        mw.$("#mw-text-editor").addClass("editor_hover");
    },
    deselect_selected_element: function () {
        mw.$("#mw-text-editor").removeClass("editor_hover");
    },
    nceui: function () {
        if (mw.settings.liveEdit) {
            mw.wysiwyg.execCommand('enableObjectResizing', false, 'false');
            mw.wysiwyg.execCommand('2D-Position', false, false);
            mw.wysiwyg.execCommand("enableInlineTableEditing", null, false);
        }
    },
    _pasteManager: undefined,
    pasteManager: function (html) {
        html = mw.wysiwyg.clean_word(html)
        mw.wysiwyg._pasteManager = this._pasteManager || document.createElement('div');
        mw.wysiwyg._pasteManager.innerHTML = html;
        mw.$('*', mw.wysiwyg._pasteManager).removeAttr('style');
        return mw.wysiwyg._pasteManager.innerHTML;
    },
    cleanExcel: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;
        mw.$("[style*='mso-spacerun']", parser).remove()
        mw.$("style", parser).remove()
        mw.$('table', parser)
            .width('100%')
            .addClass('mw-wysiwyg-table')
            .removeAttr('width');
        return parser.innerHTML;
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
    doLocalPaste: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;

        mw.$('[style]', parser).removeAttr('style');
        mw.$('[id]', parser).each(function () {
            this.id = 'dlp-item-' + mw.random();
        });
        mw.wysiwyg.insert_html(parser.innerHTML);
    },
    isLocalPaste: function (clipboard) {
        var html = clipboard.getData('text/html');
        var parser = mw.tools.parseHtml(html).body;
        return (this._lastCopy && this._lastCopy.innerHTML && this._lastCopy.innerHTML.contains(html)) || parser.querySelector('.module,.element,.edit') !== null;
    },
    paste: function (e) {
        var html, clipboard;

        if (!!e.originalEvent) {
            clipboard = e.originalEvent.clipboardData || window.clipboardData;
        }
        else {
            clipboard = e.clipboardData || window.clipboardData;
        }
        if (mw.wysiwyg.isSafeMode(e.target)) {
            if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {
                var text = clipboard.getData('text');
                if(text) {
                    mw.wysiwyg.insert_html(text);
                }
                e.preventDefault();
                return '';
            }
        }
        if (mw.wysiwyg.isLocalPaste(clipboard)) {
            mw.wysiwyg.doLocalPaste(clipboard);
            e.preventDefault();
            return '';
        }


        if (mw.wysiwyg.pastedFromExcel(clipboard)) {
            html = mw.wysiwyg.cleanExcel(clipboard)
            mw.wysiwyg.insert_html(html);
            e.preventDefault();
            return '';
        }


        if (clipboard.files.length > 0) {
            var i = 0;
            for (; i < clipboard.files.length; i++) {
                var item = clipboard.files[i];
                if (item.type.indexOf('image/') != -1) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        mw.wysiwyg.insert_html('<img src="' + (e.target.result) + '">');
                        mw.wysiwyg.normalizeBase64Images();
                    }
                    reader.readAsDataURL(item)
                }
            }
            e.preventDefault();
        }
        else {
            if (typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)) {

                    html = clipboard.getData('text/html');
                    var text = clipboard.getData('text');
                    var isPlainText = false;
                    if (!html && text) {
                        isPlainText = true;
                        if (/\r\n/.test(text)) {
                            var wrapper = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                            wrapper = mw.tools.firstMatchesOnNodeOrParent(wrapper, ['.element', 'p', 'div', '.edit'])
                            var tag = wrapper.nodeName.toLowerCase();
                            html = '<' + tag + ' id="element_' + mw.random() + '">' + text.replace(/\r\n/g, "<br>") + '</' + tag + '>';
                        }

                    }
                    else {

                    }

                if (!!html) {
                    if (mw.form) {
                        var is_link = mw.form.validate.url(html);
                        if (is_link) {
                            html = "<a href='" + html + "'>" + html + "</a>";
                        }
                    }

                    html = mw.wysiwyg.pasteManager(html);

                    mw.wysiwyg.insert_html(html);
                    if (e.target.querySelector) {
                        mw.$(e.target.querySelectorAll('[style*="outline"]')).css({
                            outline: 'none'
                        })
                    }
                    e.preventDefault();

                }
            }
        }
    },
    hasContentFromWord: function (node) {
        if (node.getElementsByTagName("o:p").length > 0 ||
            node.getElementsByTagName("v:shapetype").length > 0 ||
            node.getElementsByTagName("v:path").length > 0 ||
            node.querySelector('.MsoNormal') !== null) {
            return true;
        }
        return false;
    },
    prepare: function () {
        mw.wysiwyg.external = mw.wysiwyg._external();
        mw.$("#liveedit_wysiwyg").on("mousedown mouseup click", function (event) {
            event.preventDefault();
        });
        var items = mw.$(".element").not(".module");
        mw.$(".mw_editor").hover(function () {
            mw.$(this).addClass("editor_hover")
        }, function () {
            mw.$(this).removeClass("editor_hover")
        });
    },
    deselect: function (s) {
        var s = s || window.getSelection();
        s.empty ? s.empty() : s.removeAllRanges();
    },
    editors_disabled: false,
    enableEditors: function () {
        mw.$(".mw_editor, #mw_small_editor").removeClass("mw-editor-disabled");
        mw.wysiwyg.editors_disabled = false;
    },
    disableEditors: function () {
        /*  mw.$(".mw_editor, #mw_small_editor").addClass("mw-editor-disabled");
         mw.wysiwyg.editors_disabled = false;   */
    },
    checkForTextOnlyElements: function (e, method) {
        var e = e || false;
        var method = method || 'selection';
        if (method === 'selection') {
            var sel = window.getSelection();
            var f = sel.focusNode;
            f = mw.tools.hasClass(f, 'edit') ? f : mw.tools.firstParentWithClass(f, 'edit');
            if (f.attributes != undefined && !!f.attributes.field && f.attributes.field.nodeValue == 'title') {
                if (!!e) {
                    mw.event.cancel(e, true);
                }
                return false;
            }
        }
    },
    merge: {
        /* Executes on backspace or delete */
        isMergeable: function (el) {
            if (!el) return false;
            if (el.nodeType === 3) return true;
            var is = false;
            var css =  getComputedStyle(el)

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
            var isnonbreakable = mw.wysiwyg.merge.isInNonbreakable(curr, dir);
            if (isnonbreakable) {
                var conts = getSelection().getRangeAt(0);
                event.preventDefault();

                if (next !== null) {

                    if (next.nodeType === 3 && /\r|\n/.exec(next.nodeValue) !== null) {
                        event.preventDefault();
                        return false;
                    }

                    if (dir == 'next') {
                        mw.wysiwyg.cursorToElement(next)
                    }
                    else {
                        mw.wysiwyg.cursorToElement(next, 'end')
                    }
                }
                else {
                    return false;
                }
            }
        },
        isInNonbreakable: function (el, dir) {
            var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

            if (absNext.nodeType === 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                absNext = mw.wysiwyg.merge.findNextNearest(el, dir, true)
            }

            if (absNext.nodeType === 1) {
                if (mw.tools.hasAnyOfClasses(absNext, ['nodrop', 'allow-drop'])) {
                    return false;
                }
                if (absNext.querySelector('.nodrop', '.allow-drop') !== null) {
                    return false;
                }
            }
            if (mw.wysiwyg.merge.alwaysMergeable(absNext) && (mw.wysiwyg.merge.alwaysMergeable(absNext.firstElementChild) || !absNext.firstElementChild)) {
                return false;
            }
            if (el.textContent == '') {

                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
                if (absNext.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) !== null) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
                }
            }

            if (el.nodeType === 1 && !!el.textContent.trim()) {
                return false;
            }
            if (el.nextSibling === null && el.nodeType === 3 && dir == 'next') {
                var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);
                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, dir);
                if (/\r|\n/.exec(absNext.nodeValue) !== null) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext)
                }

                if (absNextNext.nodeType === 1) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext) || mw.wysiwyg.merge.isInNonbreakableClass(absNextNext.firstChild);
                }
                else if (absNextNext.nodeType === 3) {
                    return true
                }
                else {
                    return false;
                }
            }

            if (el.previousSibling === null && el.nodeType === 3 && dir == 'prev') {
                var absNext = mw.wysiwyg.merge.findNextNearest(el, 'prev');
                var absNextNext = mw.wysiwyg.merge.findNextNearest(absNext, 'prev');
                if (absNextNext.nodeType === 1) {
                    return mw.wysiwyg.merge.isInNonbreakableClass(absNextNext);
                }
                else if (absNextNext.nodeType === 3) {
                    return true;
                }
                else {
                    return false;
                }
            }
            el = mw.wysiwyg.validateCommonAncestorContainer(el)

            var is = mw.wysiwyg.merge.isInNonbreakableClass(el)
            return is;

        },
        isInNonbreakableClass: function (el, dir) {
            var absNext = mw.wysiwyg.merge.findNextNearest(el, dir);

            if (el.nodeType == 3 && /\r|\n/.exec(absNext.nodeValue) === null) return false;
            el = mw.wysiwyg.validateCommonAncestorContainer(el)
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
                        })
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
            if (dir == 'next') {
                var dosearch = searchElement ? 'nextElementSibling' : 'nextSibling'
                var next = el[dosearch];
                if (next === null) {
                    while (el[dosearch] === null) {
                        el = el.parentNode;
                        next = el[dosearch];

                    }
                }
            }
            else {
                var dosearch = searchElement ? 'previousElementSibling' : 'previousSibling'
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
                return mw.wysiwyg.merge.alwaysMergeable(mw.wysiwyg.validateCommonAncestorContainer(el))
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
                //todo
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
    init: function (selector) {

        selector = selector || ".mw_editor_btn";
        var mw_editor_btns = mw.$(selector).not('.ready');
        mw_editor_btns
            .addClass('ready')
            .on("click", function (event) {
                if (mw.wysiwyg.editors_disabled) {
                    return false;
                }
                event.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    var rectarget = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                    rectarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(rectarget, ['element', 'edit']);
                    if(mw.liveEditState){
                        var currState = mw.liveEditState.state()
                        if(currState[0].$id !== 'wysiwyg   '){
                            mw.liveEditState.record({
                                target: rectarget,
                                value: rectarget.innerHTML,
                                $id: 'wysiwyg'
                            });
                        }
                    }

                    var command = mw.$(this).dataset('command');
                    if (!command.contains('custom-')) {
                        mw.wysiwyg._do(command);
                    }
                    else {
                        var name = command.replace('custom-', "");
                        if(name === 'link') {
                            mw.wysiwyg.link(undefined, undefined, getSelection().toString());
                        } else {
                            mw.wysiwyg[name]();
                        }

                    }
                    if(mw.liveEditState){
                        mw.liveEditState.record({
                            target: rectarget,
                            value: rectarget.innerHTML
                        });
                    }

                    mw.$(this).removeClass("mw_editor_btn_mousedown");
                    mw.wysiwyg.check_selection(event.target);

                }
                if (event.type === 'mousedown' && !$(this).hasClass('disabled')) {
                    mw.$(this).addClass("mw_editor_btn_mousedown");
                }
            });
        mw_editor_btns.hover(function () {
            mw.$(this).addClass("mw_editor_btn_hover");
        }, function () {
            mw.$(this).removeClass("mw_editor_btn_hover");
        });
        if (mw.wysiwyg.ready) return;
        mw.wysiwyg.ready = true;
        mw.$(document.body).on('mouseup', function (event) {
            if (event.target.isContentEditable) {
                if(event.target.nodeName){
                    mw.wysiwyg.check_selection(event.target);
                }
            }
        });
        mw.$(document.body).on('keydown', function (event) {

            if ((event.keyCode == 46 || event.keyCode == 8) && event.type == 'keydown') {
                mw.tools.removeClass(mw.image_resizer, 'active');
                mw.wysiwyg.change('.element-current');
            }
            if (event.type === 'keydown') {

                if (mw.tools.isField(event.target) || !event.target.isContentEditable) {
                    return true;
                }
                var sel = window.getSelection();
                if (mw.event.is.enter(event)) {
                    setTimeout(function () {
                        if(mw.liveEditDomTree) {
                            var focused = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode)
                            mw.liveEditDomTree.refresh(focused.parentNode)
                        }
                    }, 10);
                    if (mw.wysiwyg.isSafeMode(event.target)) {
                        var isList = mw.tools.firstMatchesOnNodeOrParent(event.target, ['li', 'ul', 'ol']);
                        if (!isList) {
                            event.preventDefault();
                            var id = mw.id('mw-br-');
                            mw.wysiwyg.insert_html('<br>\u200C');
                        }
                    }
                }
                if (sel.rangeCount > 0) {
                    var r = sel.getRangeAt(0);
                    if (event.keyCode == 9 && !event.shiftKey && sel.focusNode.parentNode.iscontentEditable && sel.isCollapsed) {   /* tab key */
                        mw.wysiwyg.insert_html('&nbsp;&nbsp;&nbsp;&nbsp;');
                        return false;
                    }
                    return mw.wysiwyg.manageDeleteAndBackspace(event, sel);
                }


            }
        });
        mw.on.tripleClick(document.body, function (target) {
            mw.wysiwyg.select_all(target);
            if (mw.tools.hasParentsWithClass(target, 'element')) {
                //mw.wysiwyg.select_all(mw.tools.firstParentWithClass(target, 'element'));
            }
            var s = window.getSelection();
            if(!s.rangeCount) {
                return;
            }
            var r = s.getRangeAt(0);
            var c = r.cloneContents();
            //var common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
            var common = r.commonAncestorContainer;
            if (common.nodeType === 1) {
                if (mw.tools.hasClass(common, 'element')) {
                    mw.wysiwyg.select_all(common)
                }

            }
            else {
                common = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
                if (mw.tools.hasClass(common, 'element')) {
                    mw.wysiwyg.select_element(common)
                }
            }
            var a = common.querySelectorAll('*'), l = a.length, i = 0;
            for (; i < l; i++) {
                if (!!s.containsNode && s.containsNode(a[i], true)) {
                    r.setEndBefore(a[i]);
                    break;
                    return false;
                }
            }
        });


        mw.$(document.body).on('keyup', function (e) {
            mw.smallEditorCanceled = true;
            mw.smallEditor.css({
                visibility: "hidden"
            });
            if (e.target.isContentEditable && !mw.tools.isField(e.target)) {
                mw.wysiwyg.change(e.target)


                if (!document.body.editor_typing_startTime) {
                    document.body.editor_typing_startTime = new Date();
                }


                var started_typing = mw.tools.hasAnyOfClasses(this, ['isTyping']);
                if (!started_typing) {
                    // isTyping class is removed from livedit.js
                    mw.tools.addClass(this, 'isTyping');
                    document.body.editor_typing_startTime = new Date();

                   // mw.tools.addClass(this, 'isTypingStill');

                    // var myVarisTypingStill;
                    //
                    // var myVarisTypingStillTimeoutFunction = function() {
                    //     myVarisTypingStill = setTimeout(function(){
                    //         if(document.body){
                    //             if(!mw.tools.hasAnyOfClasses(document.body, ['isTyping'])){
                    //                 mw.tools.removeClass(document.body, 'isTypingStill');
                    //             }
                    //
                    //         }
                    //     }, 1337);
                    // }
                    //
                    // clearTimeout(myVarisTypingStill);
                    // myVarisTypingStillTimeoutFunction();



                    if(mw._initHandles){
                        mw._initHandles.hideAll();
                    }
                } else {
                    // user is typing
                    started_typing_endTime = new Date();
                    var timeDiff = started_typing_endTime - document.body.editor_typing_startTime; //in ms
                    timeDiff /= 1000;
                    var seconds = Math.round(timeDiff);
                    document.body.editor_typing_seconds = seconds;
                }

                if (document.body.editor_typing_seconds) {
                    //how much seconds user is typing
                    if (document.body.editor_typing_seconds > 10) {
                        mw.trigger('editUserIsTypingForLong', this)
                        document.body.editor_typing_seconds = 0;
                        document.body.editor_typing_startTime = 0;
                    }
                }


                mw.$(this._onCloneableControl).hide();
                if (mw.event.is.enter(e)) {/*

                    mw.$(".element-current").removeClass("element-current");
                    var el = document.querySelectorAll('.edit .element'), l = el.length, i = 0;
                    for (; i < l; i++) {
                        if (!el[i].id) {
                            el[i].id = mw.wysiwyg.createElementId();
                        }
                    }
                    e.preventDefault();
                    if (!e.shiftKey) {
                        var p = mw.wysiwyg.findTagAcrossSelection('p');
                    }
                    var newNode = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
                    if (newNode.id) {
                        newNode.id = mw.wysiwyg.createElementId();
                    }*/
                }
            }

            if (e.target.isContentEditable
                && !e.shiftKey
                && !e.ctrlKey
                && e.keyCode !== 27
                && e.keyCode !== 116
                && e.keyCode !== 17
                && (e.keyCode < 37 || e.keyCode > 40)) {
                mw.wysiwyg.change(e.target);
            }

            if(e && e.target) {
                mw.wysiwyg.check_selection(e.target);
            }

        });
    },
    createElementId: function () {
        return 'mw-element_' + mw.random();
    },
    change: function (el) {
        if (typeof el === 'string') {
            el = document.querySelector(el);
        }
        var target = null;
        if (mw.tools.hasClass(el, 'edit')) {
            mw.tools.addClass(el, 'changed');
            target = el;
            mw.trigger('editChanged', target)
        }
        else if (mw.tools.hasParentsWithClass(el, 'edit')) {
            target = mw.tools.firstParentWithClass(el, 'edit');
            mw.tools.addClass(target, 'changed');
            mw.trigger('editChanged', target)
        }
        if (target !== null) {
            mw.tools.foreachParents(target, function () {
                if (mw.tools.hasClass(this, 'edit')) {
                    mw.tools.addClass(this, 'changed');
                    mw.trigger('editChanged', this)
                }
            });
            mw.askusertostay = true;
            mw.drag.initDraft = true;
        }

    },
    validateCommonAncestorContainer: function (c) {
        if( !c || !c.parentNode || c.parentNode === document.body ){
            return null;
        }
        try {   /* Firefox returns wrong target (div) when you click on a spin-button */
            if (typeof c.querySelector === 'function') {
                return c;
            }
            else {
                return mw.wysiwyg.validateCommonAncestorContainer(c.parentNode);
            }
        }
        catch (e) {
            return null;
        }
    },

    editable: function (el) {
        var el = mw.wysiwyg.validateCommonAncestorContainer(el);
        return el.isContentEditable && ['SELECT', 'INPUT', 'TEXTAREA'].indexOf(el.nodeName) === -1;
    },
    getNextNode: function (node) {
        if (node.nextSibling) {
            return node.nextSibling
        } else {
            return this.getNextNode(node.parentNode);
        }
    },
    cursorToElement: function (node, a) {

        if (!node) {
            return false;
        }
        if(mw.tools.hasAnyOfClassesOnNodeOrParent(node, ['safe-element', 'icon', 'mw-icon', 'material-icons', 'mw-wysiwyg-custom-icon'])){
            return;
        }
        mw.wysiwyg.contentEditable(node, true);
        a = (a || 'start').trim();
        var sel = window.getSelection();
        var r = document.createRange();
        sel.removeAllRanges();
        if (a === 'start') {
            r.selectNodeContents(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if (a === 'end') {
            r.selectNodeContents(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if (a === 'before') {
            r.selectNode(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if (a === 'after') {
            var range = document.createRange();
            range.setStartAfter(node);
            range.collapse(true);

            sel.removeAllRanges();
            sel.addRange(range);
        }

    },
    rfapplier: function (tag, classname, style_object) {
        var parent, fnode = getSelection().focusNode;
        var id = mw.id('mw-applier-element-');
        this.execCommand("insertHTML", false, '<'+tag+' '+(classname ? 'class="' + classname + '"' : '')+' id="'+id+'">'+ getSelection()+'</'+tag+'>');
        var $el = mw.$('#' + id);
        if (style_object) {
            $el.css(style_object);
        }
        return $el[0];
    },
    applier: function (tag, classname, style_object) {
        var classname = classname || '';
        if (mw.wysiwyg.isSelectionEditable()) {
            var range = window.getSelection().getRangeAt(0);
            var selectionContents = range.extractContents();
            var el = document.createElement(tag);
            el.className = classname;
            typeof style_object !== 'undefined' ? mw.$(el).css(style_object) : '';
            el.appendChild(selectionContents);
            range.insertNode(el);
            mw.wysiwyg.change(el);
            return el;
        }
    },
    external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        mw.$(mw.wysiwyg.external).css({
            top: offset.top - mw.$(window).scrollTop() + el.height(),
            left: offset.left
        });
        mw.$(mw.wysiwyg.external).html("<iframe src='" + url + "' scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');
        frame.contentWindow.thisframe = frame;
    },
    getExternalData: function (url, cb) {
        var has = mw.storage.get(url);
        if (has) {
            cb.call(has, has)
        }
        else {
            $.get(url, function (data) {
                mw.storage.set(url, data)
                cb.call(data, data)
            })
        }
    },
    todo_external_tool: function (el, url) {
        var el = mw.$(el).eq(0);
        var offset = el.offset();
        mw.$(mw.wysiwyg.external).css({
            top: offset.top - mw.$(window).scrollTop() + el.height(),
            left: offset.left
        });
        mw.$(mw.wysiwyg.external).html("<iframe scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');

        frame.contentWindow.thisframe = frame;
        if (url.indexOf('#') !== -1) {
            frame.src = '#' + url.split('#')[1]
        }

        mw.wysiwyg.getExternalData(url, function (html) {

            frame.contentWindow.document.open();
            frame.contentWindow.document.write(html);
            frame.contentWindow.document.close();
        })
    },
    createelement: function () {
        var el = mw.wysiwyg.applier('div', 'mw_applier element');
    },
    fontcolorpicker: function () {

        mw.wysiwyg._fontcolorpicker.show();
        setTimeout(function () {
            mw.wysiwyg._fontcolorpicker.show();
        }, 20);
    },
    fontbgcolorpicker: function () {

        setTimeout(function () {
            mw.wysiwyg._bgfontcolorpicker.show();
        }, 20);


    },
    fontColor: function (color) {
        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        var rectarget = mw.wysiwyg.validateCommonAncestorContainer(getSelection().focusNode);
        rectarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(rectarget, ['element', 'edit']);
        if(mw.liveEditState){
            var currState = mw.liveEditState.state()
            if(currState[0].$id !== 'wysiwyg   '){
                mw.liveEditState.record({
                    target: rectarget,
                    value: rectarget.innerHTML,
                    $id: 'wysiwyg'
                });
            }
        }
        if (color == 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "foreColor");
        } else {
            document.execCommand("styleWithCSS", null, true);
            mw.wysiwyg.execCommand('forecolor', null, color);
        }
        mw.liveEditState.record({
            target: rectarget,
            value: rectarget.innerHTML,
        });
    },
    fontbg: function (color) {

        if (/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
        }
        if (color === 'none') {
            mw.wysiwyg.execCommand('removeFormat', false, "backcolor");
        } else {
            document.execCommand("styleWithCSS", null, true);
            mw.wysiwyg.execCommand('backcolor', null, color);
        }
    },
    request_change_bg_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_bg_color: function (color) {
        color = color !== 'transparent' ? '#' + color : color;
        mw.$(".element-current").css("backgroundColor", color);
        mw.wysiwyg.change('.element-current');
    },
    request_border_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_border_color: function (color) {
        if (color != "transparent") {
            mw.$(".element-current").css(mw.border_which + "Color", "#" + color);
            mw.$(".ed_bordercolor_pick span").css("background", "#" + color);
            mw.wysiwyg.change('.element-current');
        }
        else {
            mw.$(".element-current").css(mw.border_which + "Color", "transparent");
            mw.$(".ed_bordercolor_pick span").css("background", "");
            mw.wysiwyg.change('.element-current');
        }
    },
    request_change_shadow_color: function (el) {
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_shadow_color');
        mw.$(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_shadow_color: function (color) {
        mw.current_element_styles = getComputedStyle(mw.$('.element-current')[0], null);
        if (mw.current_element_styles.boxShadow != "none") {
            var arr = mw.current_element_styles.boxShadow.split(' ');
            var len = arr.length;
            var x = parseFloat(arr[len - 4]);
            var y = parseFloat(arr[len - 3]);
            var blur = parseFloat(arr[len - 2]);
            mw.$(".element-current").css("box-shadow", x + "px " + y + "px " + blur + "px #" + color);
            mw.$(".ed_shadow_color").dataset("color", color);

        }
        else {
            mw.$(".element-current").css("box-shadow", "0px 0px 6px #" + color);
            mw.$(".ed_shadow_color").dataset("color", color);
        }
        mw.wysiwyg.change('.element-current');
    },
    fontFamily: function (font_name) {
        var range = getSelection().getRangeAt(0);
        document.execCommand("styleWithCSS", null, true);
        var el = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        if (range.collapsed) {

            mw.wysiwyg.select_all(el);
            document.execCommand('fontName', null, font_name);
            range.collapse()
        }
        else {
            document.execCommand('fontName', null, font_name);
        }

        mw.wysiwyg.change(el)

    },
    nestingFixes: function (root) {  /*
     var root = root || document.body;
     var all = root.querySelectorAll('.mw-span-font-size'),
     l = all.length,
     i=0;
     for( ; i<l; i++ ){
     var el = all[i];
     if(el.firstChild === el.lastChild && el.firstChild.nodeType !== 3){
     // mw.$(el.firstChild).unwrap();
     }
     } */
    },
    lineHeight: function (a) {
        a = a || 'normal';
        a = (typeof a === 'number') ? (a + 'px') : a;
        var r = getSelection().getRangeAt(0).commonAncestorContainer;
        var el = mw.wysiwyg.validateCommonAncestorContainer(r);
        r.style.fontSize = a;
        mw.wysiwyg.change(r);
    },
    fontSize: function (a) {

        if (window.getSelection().isCollapsed) {
            return false;
        }
        mw.wysiwyg.allStatements(function () {

            rangy.init();
            var clstemp = 'mw-font-size-' + mw.random();
            var classApplier = rangy.createCssClassApplier("mw-font-size " + clstemp, true);
            classApplier.applyToSelection();

            var all = document.querySelectorAll('.' + clstemp),
                l = all.length,
                i = 0;
            for ( ; i < l; i++ ) {
                all[i].style.fontSize = a + 'px';
                mw.tools.removeClass(all[i], clstemp);
                mw.wysiwyg.change(all[i]);
            }

            mw.$('.edit .mw-font-size').removeClass('mw-font-size')

        });
    },
    fontSizePrompt: function () {
        var size = prompt("Please enter font size", "");

        if (size != null) {
            var a = parseInt(size);
            if (a > 0) {
                this.fontSize(a);
            }
        }
    },
    resetActiveButtons: function () {
        mw.$('.mw_editor_btn_active').removeClass('mw_editor_btn_active');
    },
    setActiveButtons: function (node) {

        var css = mw.CSSParser(node);
        if (css && css.get) {
            var font = css.get.font();
            var family_array = font.family.split(',');
            if (family_array.length == 1) {
                var fam = font.family;

            } else {
                //var fam = mw.tools.getFirstEqualFromTwoArrays(family_array, mw.wysiwyg.editorFonts);
                var fam = family_array.shift();
            }

            var ddval = mw.$(".mw_dropdown_action_font_family");
            if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                mw.$(".mw_dropdown_action_font_family").each(function () {
                    mw.$(this).setDropdownValue(fam);
                })
            }
        }
    },
    setActiveFontSize: function () {

        var sel = getSelection();
        var range = sel.getRangeAt(0);
        if(range.collapsed) {
            var node = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
            var css_node_get=mw.CSSParser(node).get;
            if(typeof(css_node_get) !== 'undefined'){
            var size = Math.round(parseFloat(css_node_get.font().size));
            }
            mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(size + 'px')
        } else {
            var curr = range.startContainer;
            var end = range.endContainer;
            var common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
            var size = Math.round(parseFloat(mw.CSSParser(common).get.font().size));
            while (curr && curr !== end) {
                var node = mw.wysiwyg.validateCommonAncestorContainer(curr);
                curr = curr.nextElementSibling;
                var css_node_get=mw.CSSParser(node).get;
                if(typeof(css_node_get) !== 'undefined'){
                    var sizec = Math.round(parseFloat(css_node_get.font().size));
                    if (sizec !== size) {
                        mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(mw.lang('Size'));
                        return;
                    }
                }
            }
            mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(size + 'px')

        }
    },

    listSplit: function (list, index) {
        if (!list || typeof index == 'undefined') return;
        var curr = list.children[index];
        var listtop = document.createElement(list.nodeName);
        var listbottom = document.createElement(list.nodeName);
        var final = {middle: curr}

        for (var itop = 0; itop < index; itop++) {
            listtop.appendChild(list.children[itop])
        }
        for (var ibot = 1; ibot < list.children.length; ibot++) {
            //for(var ibot = index+1; ibot < list.children.length; ibot++){

            listbottom.appendChild(list.children[ibot])
        }

        if (listtop.children.length > 0) {
            final.top = listtop
        }
        if (listbottom.children.length > 0) {
            final.bottom = listbottom
        }
        return final;

    },
    isFormatElement: function (obj) {
        var items = /^(div|h[1-6]|p)$/i;
        return items.test(obj.nodeName);
    },
    decorators: {
        b: '.mw_editor_bold',
        strong: '.mw_editor_bold',
        i: '.mw_editor_italic',
        em: '.mw_editor_italic',
        u: '.mw_editor_underline',
        s: '.mw_editor_strike',
        strike: '.mw_editor_strike'
    },
    setDecorators: function (sel) {
        sel = sel || getSelection();
        var node = sel.focusNode;
        while (node.nodeName !== 'DIV' && node.nodeName !== 'BODY') {
            for (var x in mw.wysiwyg.decorators) {
                if (node.nodeName.toLowerCase() === x) {
                    mw.$(mw.wysiwyg.decorators[x]).addClass('mw_editor_btn_active')
                }
            }
            node = node.parentNode;
        }
    },
    started_checking: false,
    check_selection: function (target) {
        target = target || false;

        if (!mw.wysiwyg.started_checking) {
            mw.wysiwyg.started_checking = true;

            var selection = window.getSelection();

            if (selection.rangeCount > 0) {
                mw.wysiwyg.resetActiveButtons();
                var range = selection.getRangeAt(0);
                var start = range.startContainer;
                var end = range.endContainer;
                var common = range.commonAncestorContainer;
                var children = range.cloneContents().childNodes, i = 0, l = children.length;

                var list = mw.tools.firstParentWithTag(common, ['ul', 'ol']);
                if (!!list) {
                    mw.$('.mw_editor_' + list.nodeName.toLowerCase()).addClass('mw_editor_btn_active');
                }
                if (common.nodeType !== 3) {
                    var commonCSS = mw.CSSParser(common);
                    var align = commonCSS.get.alignNormalize();
                    mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                    mw.$(".mw-align-" + align).addClass('mw_editor_btn_active');
                    for (; i < l; i++) {
                        if(children[i].nodeName){
                        mw.wysiwyg.setActiveButtons(children[i]);
                        }
                    }

                }
                else {
                    if (typeof common.parentElement !== 'undefined' && common.parentElement !== null) {
                        var align = mw.CSSParser(common.parentElement).get.alignNormalize();
                        mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                        mw.$(".mw-align-" + align).addClass('mw_editor_btn_active');
                        mw.wysiwyg.setActiveButtons(common.parentElement);
                    }
                }
                if (mw.wysiwyg.isFormatElement(common)) {
                    var format = common.nodeName.toLowerCase();
                    var ddval = mw.$(".mw_dropdown_action_format");
                    if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                        mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                    }
                }
                else {
                    mw.tools.foreachParents(common, function (loop) {
                        if (mw.wysiwyg.isFormatElement(this)) {
                            var format = this.nodeName.toLowerCase();
                            var ddval = mw.$(".mw_dropdown_action_format");
                            if (ddval.length != 0 && ddval.setDropdownValue != undefined) {
                                mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                            }
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                mw.wysiwyg.setActiveFontSize();
                mw.wysiwyg.setDecorators(selection)
            }

            if (!!target && target.nodeName) {


                mw.wysiwyg.setActiveButtons(target);
                if (target.tagName === 'A') {
                    mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
                var parent_a = mw.tools.firstParentWithTag(target, 'a');
                if (!!parent_a) {
                    mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
            }
            mw.wysiwyg.started_checking = false;
        }
    },
    link: function (url, node_id, text) {
        mw.require('external_callbacks.js');
        mw.wysiwyg.save_selection();
        var el = node_id ? document.getElementById(node_id) : mw.tools.firstParentWithTag(getSelection().focusNode, 'a');
        var val;
        var sel = getSelection();

        if(el) {
            val = {
                url: url || el.href,
                text: text || el.innerHTML,
                target: el.target === '_blank'
            }

        } else if(!sel.isCollapsed) {
            var html = document.createElement('div');
            if(sel.rangeCount) {
                var frag = sel.getRangeAt(0).cloneContents();
                while (frag.firstChild) {
                    html.append(frag.firstChild);
                }
            }
            val = {
                text: text || html.innerHTML,
                url: url || ''
            }
        }

        new mw.LinkEditor({
            mode: 'dialog'
        })
        .setValue(val)
        .promise()
        .then(function (result){
            mw.wysiwyg.restore_selection();
            mw.iframecallbacks.insert_link(result, (result.target ? '_blank' : '_self') , result.text);
        });



    },

    unlink: function () {
        var sel = window.getSelection();
        if (!sel.isCollapsed) {
            mw.wysiwyg.execCommand('unlink', null, null);
        }
        else {
            var link = mw.wysiwyg.findTagAcrossSelection('a');
            if (!!link) {
                mw.wysiwyg.select_element(link);
                mw.wysiwyg.execCommand('unlink', null, null);
            }
        }
        mw.$(".mw_editor_link").removeClass("mw_editor_btn_active");
    },
    findTagAcrossSelection: function (tag, selection) {
        var selection = selection || window.getSelection();
        if (selection.anchorNode.nodeName.toLowerCase() === tag) {
            return selection.anchorNode;
        }
        var range = selection.getRangeAt(0);
        var common = range.commonAncestorContainer;
        var parent = mw.tools.firstParentWithTag(common, [tag]);
        if (!!parent) {
            return parent;
        }
        if (typeof common.querySelectorAll !== 'undefined') {
            var items = common.querySelectorAll(tag), l = items.length, i = 0, arr = [];
            if (l > 0) {
                for (; i < l; i++) {
                    if (selection.containsNode(items[i], true)) {
                        arr.push(items[i])
                    }
                }
                if (arr.length > 0) {
                    return arr.length === 1 ? arr[0] : arr;
                }
            }
        }
        return false;
    },
    image_link: function (url) {
        mw.$("img.element-current").wrap("<a href='" + url + "'></a>");
        mw.wysiwyg.change('.element-current');
    },
    request_media: function (hash, types) {
        mw.require('external_callbacks.js');
        types = types || false;
        if (hash === '#editimage') {
            types = 'images';
            //hash = 'noop';
        }
        var url = !!types ? "rte_image_editor?types=" + types + '' + hash : "rte_image_editor" + hash;

        url = mw.settings.site_url + 'editor_tools/' + url;
        var sel = mw.wysiwyg.save_selection();
        var modal = mw.top().dialogIframe({
            url: url,
            name: "mw_rte_image",
            width: 460,
            height: 'auto',
            autoHeight:true,
            overlay: true
        }, function(res) {
            if(hash === '#set_bg_image'){
                mw.wysiwyg.set_bg_image(res);
                return;
            }

            mw.wysiwyg.restore_selection();


            if(hash === '#editimage') {
                if(mw.image.currentResizing) {
                    if (mw.image.currentResizing[0].nodeName === 'IMG') {
                        mw.image.currentResizing.attr("src", res);
                        mw.image.currentResizing.css('height', 'auto');
                    }
                    else {
                        mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(res) + ')');
                        if(parent.mw.image.currentResizing) {
                        mw.wysiwyg.bgQuotesFix(parent.mw.image.currentResizing[0])
                        }
                    }
                    if(mw.image.currentResizing) {
                        mw.wysiwyg.change(mw.image.currentResizing[0]);
                    }
                    mw.image.currentResizing.load(function () {
                        mw.image.resize.resizerSet(this);
                    });
                }
            }
            else {
                if(res.indexOf('<') !== -1) {
                    mw.wysiwyg.insert_html(res);
                } else {
                    mw.wysiwyg.insertMedia(res);
                }
            }

            this.remove();

        });
    },
    media: function (action) {

        if (mw.settings.liveEdit && typeof mw.target.item === 'undefined') return false;
        action = action || 'insert_html';
        action = action.replace(/#/g, '');

        if (mw.wysiwyg.isSelectionEditable() || mw.$(mw.target.item).hasClass("image_change") || mw.$(mw.target.item.parentNode).hasClass("image_change") || mw.target.item === mw.image_resizer) {
            mw.wysiwyg.save_selection();
            var dialog;
            var handleResult = function (res) {
                var url = res.src ? res.src : res;
                if(action === 'editimage') {
                    if(mw.image.currentResizing) {
                        if (mw.image.currentResizing[0].nodeName === 'IMG') {
                            mw.image.currentResizing.attr("src", url);
                            mw.image.currentResizing.css('height', 'auto');
                        }
                        else {
                            mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(url) + ')');
                            if(parent.mw.image.currentResizing) {
                                mw.wysiwyg.bgQuotesFix(parent.mw.image.currentResizing[0])
                            }
                        }
                        if(mw.image.currentResizing) {
                            mw.wysiwyg.change(mw.image.currentResizing[0]);
                        }
                        mw.image.currentResizing.load(function () {
                            mw.image.resize.resizerSet(this);
                        });
                    }
                }
                else {
                    mw.wysiwyg.insertMedia(url);
                }
                dialog.remove()
            }
            var picker = new mw.filePicker({
                type: 'images',
                label: false,
                autoSelect: false,
                footer: true,
                _frameMaxHeight: true,
                fileUploaded: function (file) {
                    handleResult(file.src);
                    dialog.remove()
                },
                onResult: handleResult,
                cancel: function () {
                    dialog.remove()
                }
            });
            dialog = mw.dialog({
                content: picker.root,
                title: mw.lang('Select image'),
                footer: false,
                width: 1200
            })


        }

    },
    request_bg_image: function () {
        mw.wysiwyg.request_media('#set_bg_image');
    },
    set_bg_image: function (url) {
        mw.$(".element-current").css("backgroundImage", "url(" + url + ")");
        mw.wysiwyg.change('.element-current');
    },
    insert_html: function (html) {
        if (typeof html === 'string') {
            var isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
        }
        else {
            var isembed = false;
        }
        if (isembed) {
            var id = 'frame-' + mw.random();
            var frame = html;
            html = '<span id="' + id + '"></span>';
        }
        if (!!window.MSStream) {
            mw.wysiwyg.restore_selection();
            if (mw.wysiwyg.isSelectionEditable()) {
                var range = window.getSelection().getRangeAt(0);
                var el = document.createElement('span');
                el.innerHTML = html;
                range.insertNode(el);
                mw.$(el).replaceWith(el.innerHTML);
            }
        }
        else {
            if (!document.selection) {
                mw.wysiwyg.execCommand('inserthtml', false, html);
            }
            else {
                document.selection.createRange().pasteHTML(html)
            }
        }
        if (isembed) {
            var el = document.getElementById(id);
            mw.wysiwyg.contentEditable(el.parentNode, false);
            mw.$(el).replaceWith(frame);
        }
        var sel = getSelection();
        if(sel.rangeCount){
            mw.wysiwyg.change(mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer));

        }
    },
    selection_length: function () {
        var n = window.getSelection().getRangeAt(0).cloneContents().childNodes,
            l = n.length,
            i = 0;
        var final = 0;
        for (; i < l; i++) {
            var item = n[i];
            if (item.nodeType === 1) {
                final = final + item.textContent.length;
            }
            else if (item.nodeType === 3) {
                final = final + item.nodeValue.length;
            }
        }
        return final;
    },
    insertMedia: function (url, type) {
        var ext = url.split('.').pop().toLowerCase();
        var name = url.split('/').pop()
        if(!type) {
            if(['png','gif','jpg','jpeg','tiff','bmp','svg'].indexOf(ext) !== -1) {
                type = 'image';
            }
            if(['mp4','ogg'].indexOf(ext) !== -1) {
                type = 'video';
            }
        }
        if(type === 'image') {
            return this.insert_image(url);
        } else if(type === 'video') {
            var id = 'image_' + mw.random();
            var img = '<span class="mwembed"><video id="' + id + '" contentEditable="false" src="' + url + '" controls></video></span>';
            mw.wysiwyg.insert_html(img);
        } else {
            var id = 'image_' + mw.random();
            var img = '<a id="' + id + '" contentEditable="true" src="' + url + '">'+name+'</a>';
            mw.wysiwyg.insert_html(img);
        }
    },
    insert_image: function (url) {
        var id = 'image_' + mw.random();
        var img = '<img id="' + id + '" contentEditable="true" class="element" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        mw.settings.liveEdit ? mw.$("#" + id).attr("contenteditable", false) : '';
        mw.$("#" + id).removeAttr("_moz_dirty");
        mw.wysiwyg.change(document.getElementById(id));
        return id;
    },
    save_selection: function () {
        var selection = window.getSelection();
        if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);
        }
        else {
            var range = document.createRange();
            range.selectNode(document.querySelector('.edit .element'));
        }
        mw.wysiwyg.selection = {};
        mw.wysiwyg.selection.sel = selection;
        mw.wysiwyg.selection.range = range;
        mw.wysiwyg.selection.element = mw.$(mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer));
    },
    restore_selection: function () {
        if (!!mw.wysiwyg.selection) {
            mw.wysiwyg.selection.element.attr("contenteditable", "true");
            mw.wysiwyg.selection.element.focus();
            mw.wysiwyg.selection.sel.removeAllRanges();
            mw.wysiwyg.selection.sel.addRange(mw.wysiwyg.selection.range)
        }
    },
    select_all: function (el) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    },
    select_element: function (el) {
        var range = document.createRange();
        try {
            range.selectNode(el);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        } catch (e) {

        }
    },
    formatNative: function (command) {
        var el = mw.wysiwyg.validateCommonAncestorContainer(window.getSelection().focusNode);
        if (mw.wysiwyg.isSafeMode()) {
            mw.$('[contenteditable]').removeAttr('contenteditable');
            var parent = mw.tools.firstBlockLevel(el.parentNode);
            parent.contentEditable = true;
        }
        mw.wysiwyg.execCommand('formatBlock', false, '<' + command + '>');
        mw.wysiwyg.execCommand('formatBlock', false, command );
    },
    format: function (command) {
        var target = mw.wysiwyg.validateCommonAncestorContainer(getSelection().getRangeAt(0).commonAncestorContainer);
        mw.liveEditState.record({
            target: target.parentNode,
            value: target.parentNode.innerHTML
        });
        var el = mw.tools.setTag(target, command);
        mw.wysiwyg.cursorToElement(el, 'end');
        mw.liveEditState.record({
            target: el.parentNode,
            value: el.parentNode.innerHTML
        });
        // return this.formatNative(command);
    },
    fontFamilies: ['Arial', 'Tahoma', 'Verdana', 'Georgia', 'Times New Roman'],
    fontFamiliesExtended: [],
    fontFamiliesTemplate: [],
    initFontSelectorBox: function () {
        mw.wysiwyg.initFontFamilies();

        var l = mw.wysiwyg.fontFamilies.length, i = 0, html = '';
        for (; i < l; i++) {

            html += '<li value="' + mw.wysiwyg.fontFamilies[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamilies[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamilies[i] + '</a></li>'
        }

        var l = mw.wysiwyg.fontFamiliesTemplate.length, i = 0;
        for (; i < l; i++) {
            if (mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesTemplate[i]) === -1 && mw.wysiwyg.fontFamiliesTemplate[i] != '') {
                html += '<li value="' + mw.wysiwyg.fontFamiliesTemplate[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamiliesTemplate[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamiliesTemplate[i] + '</a></li>'
            }
        }
        var l = mw.wysiwyg.fontFamiliesExtended.length, i = 0;
        for (; i < l; i++) {
            if (mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesExtended[i]) === -1 && mw.wysiwyg.fontFamiliesExtended[i] != '') {
                html += '<li value="' + mw.wysiwyg.fontFamiliesExtended[i] + '"><a style="font-family:' + mw.wysiwyg.fontFamiliesExtended[i] + '" href="javascript:;">' + mw.wysiwyg.fontFamiliesExtended[i] + '</a></li>'
            }
        }

        mw.$(".mw_dropdown_action_font_family ul").empty().append(html);

        mw.$(".mw_dropdown_action_font_family").off('change');
        mw.$(".mw_dropdown_action_font_family").on('change', function () {
            var val = mw.$(this).getDropdownValue();
            mw.wysiwyg.fontFamily(val);
        });
        mw.$(".mw_dropdown_action_font_family").each(function () {
            mw.$("[value]", mw.$(this)).on('mousedown touchstart', function (event) {
                mw.$(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                return false;
            });
        });
    },

    initFontFamilies: function () {
        if (window.getComputedStyle(document.body) == null) {
            return;
        }

        var body_font = window.getComputedStyle(document.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
        if (mw.wysiwyg.fontFamilies.indexOf(body_font) === -1) {
            mw.wysiwyg.fontFamilies.push(body_font);
        }

        var scan_for_fonts = ['body', 'html', 'h1', 'h2', 'h3', 'h4', 'h5', 'p', 'a[class]'];

        $.each(scan_for_fonts, function (index, value) {
            var sel = mw.$(document.querySelector(value));
            if (sel.length > 0) {
                var body_font = window.getComputedStyle(sel[0], null).fontFamily.split(',');
                $.each(body_font, function (font_index, fvalue) {
                    var font_value = fvalue;
                    font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
                    if (mw.wysiwyg.fontFamilies.indexOf(font_value) === -1) {
                        mw.wysiwyg.fontFamilies.push(font_value);
                    }
                });
            }
        });
    },
    initExtendedFontFamilies: function (string) {
        var families = [];
        if (typeof(string) == 'string') {
            families = string.split(',')
        } else if (typeof(string) == 'object') {
            families = string
        }
        $.each(families, function (font_index, fvalue) {
            var font_value = fvalue;
            font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
            if (mw.wysiwyg.fontFamilies.indexOf(font_value) === -1 && mw.wysiwyg.fontFamiliesExtended.indexOf(font_value) === -1) {
                mw.wysiwyg.fontFamiliesExtended.push(font_value);
            }
        });
    },
    fontIconFamilies: ['fas', 'fab', 'far', 'fa', 'mw-ui-icon', 'mw-icon', 'material-icons', 'mw-wysiwyg-custom-icon', 'icon', 'mdi'],

    elementHasFontIconClass: function (el) {
        var icon_classes = mw.wysiwyg.fontIconFamilies;
        if (el.tagName === 'I' || el.tagName === 'SPAN') {
            if (mw.tools.hasAnyOfClasses(el, icon_classes)) {
                return true;
            }
            else if (el.className.indexOf('mw-micon-') !== -1) {
                return true;
            }
            else if (el.className.indexOf('mw-icon-') !== -1) {
                return true;
            }
            else {
                return mw.tools.firstParentOrCurrentWithAnyOfClasses(el.parentNode, icon_classes);
            }
        }
    },
    firstElementThatHasFontIconClass: function (el) {
        var icon_classes = mw.wysiwyg.fontIconFamilies.map(function (value) {
            return '.'+value
        });
        icon_classes.push('[class*="mw-micon-"]');
        var p = mw.tools.firstMatchesOnNodeOrParent(el, icon_classes);
        if (p && (p.tagName === 'I' || p.tagName === 'SPAN')) {
            return p;
        }
    },
    elementRemoveFontIconClasses: function (el) {
        var l = mw.wysiwyg.fontIconFamilies.length, i = 0;
        for (; i < l; i++) {
            var search_class = mw.wysiwyg.fontIconFamilies[i]
            mw.tools.classNamespaceDelete(el, search_class + '-');
        }
    },
    iframe_editor: function (textarea, iframe_url, content_to_set) {
        var content_to_set = content_to_set || false;
        var id = mw.$(textarea).attr("id");
        mw.$("#iframe_editor_" + id).remove();
        var url = iframe_url;
        var iframe = document.createElement('iframe');
        iframe.className = 'mw-editor-iframe-loading';
        iframe.id = "iframe_editor_" + id;
        iframe.width = mw.$(textarea).width();
        iframe.height = mw.$(textarea).height();
        iframe.scrolling = "no";
        iframe.setAttribute('frameborder', 0);
        iframe.src = url;
        iframe.style.resize = 'vertical';
        iframe.onload = function () {
            iframe.className = 'mw-editor-iframe-loaded';
            var b = mw.$(this).contents().find(".edit");
            var b = mw.$(this).contents().find("[field='content']")[0];
            if (typeof b != 'undefined' && b !== null) {
                mw.wysiwyg.contentEditable(b, true)
                mw.$(b).on("blur keyup", function () {
                    textarea.value = mw.$(this).html();
                });
                if (!!content_to_set) {
                    mw.$(b).html(content_to_set);
                }
                mw.on.DOMChange(b, function () {
                    textarea.value = mw.$(this).html();
                    mw.askusertostay = true;
                });
            }
        }
        mw.$(textarea).after(iframe);
        mw.$(textarea).hide();
        return iframe;
    },
    word_listitem_get_level: function (item) {
        var msspl = item.getAttribute('style').split('mso-list');
        if (msspl.length > 1) {
            var mssplitems = msspl[1].split(' ');
            for (var i = 0; i < mssplitems.length; i++) {
                if (mssplitems[i].indexOf('level') !== -1) {
                    return parseInt(mssplitems[i].split('level')[1], 10);
                }
            }
        }
    },

    word_list_build: function (lists, count) {
        var i, check = false, max = 0;
        count = count || 0;
        if (count === 0) {
            for (i in lists) {
                var curr = lists[i];
                if (!curr.nodeName || curr.nodeType !== 1) continue;
                var $curr = mw.$(curr);
                lists[i] = mw.tools.setTag(curr, 'li');
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
                    if (!!prev && prev.nodeName == 'LI') {
                        var type = this.getAttribute('data-type');
                        var wrap = document.createElement(type == 'ul' ? 'ul' : 'ol');
                        wrap.setAttribute('data-level', level)
                        mw.$(wrap).append(this);
                        mw.$(wrap).appendTo(prev);
                        check = true;
                    }
                    else if (!!prev && (prev.nodeName == 'UL' || prev.nodeName == 'OL')) {
                        var where = mw.$('li[data-level="' + level + '"]', prev);
                        if (where.length > 0) {
                            where.after(this);
                            check = true;
                        }
                        else {
                            var type = this.getAttribute('data-type');
                            var wrap = document.createElement(type == 'ul' ? 'ul' : 'ol');
                            wrap.setAttribute('data-level', level)
                            mw.$(wrap).append(this);
                            mw.$(wrap).appendTo($('li:last', prev))
                            check = true;
                        }
                    }
                    else if (!prev && (this.parentNode.nodeName != 'UL' && this.parentNode.nodeName != 'OL')) {
                        var $curr = mw.$([this]), curr = this;
                        while ($(curr).next('li[data-level="' + level + '"]').length > 0) {
                            $curr.push($(curr).next('li[data-level="' + level + '"]')[0]);
                            curr = mw.$(curr).next('li[data-level="' + level + '"]')[0];
                        }
                        $curr.wrapAll($curr.eq(0).attr('data-type') == 'ul' ? '<ul></ul>' : '<ol></ol>')
                        check = true;
                    }
                }
            }
        })

        mw.$("ul[data-level!='1'], ol[data-level!='1']").each(function () {
            var level = parseInt($(this).attr('data-level'));
            if (!!this.previousElementSibling) {
                var plevel = parseInt($(this.previousElementSibling).attr('data-level'));
                if (level > plevel) {
                    mw.$('li:last', this.previousElementSibling).append(this)
                    check = true;
                }
            }
        })
        if (count === 0) {
            setTimeout(function () {
                mw.wysiwyg.word_list_build($('li[data-level]'), 1);
                mw.wysiwyg.wrap_li_roots()
            }, 1)
        }
        return lists;
    },
    wrap_li_roots: function () {
        var all = document.querySelectorAll('li[data-level]'), i = 0, has = false;
        for (; i < all.length; i++) {
            var parent = all[i].parentElement.nodeName;
            if (parent != 'OL' && parent != 'UL') {
                has = true;
                var group = mw.$([]), curr = all[i];
                while (!!curr && curr.nodeName == 'LI') {
                    group.push(curr);
                    curr = curr.nextElementSibling;
                }
                var el = document.createElement(all[i].getAttribute('data-type') == 'ul' ? 'ul' : 'ol');
                el.className = 'element';
                group.wrapAll(el)
                break;
            }
        }
        if (has) return mw.wysiwyg.wrap_li_roots()
    },
    isWordHtml: function (html) {
        return html.indexOf('urn:schemas-microsoft-com:office:word') !== -1;
    },
    bgQuotesFix: function (el) {
        el = mw.$(el)[0];
        if (!!el && el.nodeType === 1) {
            var first = el.outerHTML.split('>')[0];
            if (el.style.backgroundImage.indexOf('"') !== -1 && first.indexOf('style="') !== -1) {
                el.attributes.style.nodeValue = el.attributes.style.nodeValue.replace(/\"/g, "'")
            }
        }
    },
    clean_word_list: function (html) {

        if (!mw.wysiwyg.isWordHtml(html)) return html;
        if (html.indexOf('</body>') != -1) {
            html = html.split('</body>')[0]
        }
        var parser = mw.tools.parseHtml(html).body;

        var lists = mw.$('[style*="mso-list:"]', parser);
        lists.each(function () {
            var level = mw.wysiwyg.word_listitem_get_level(this);
            if (!!level) {
                this.setAttribute('data-level', level)
                this.setAttribute('class', 'level-' + level)
            }

        });

        mw.$('[style]', parser).removeAttr('style');

        if (lists.length > 0) {
            lists = mw.wysiwyg.word_list_build(lists);
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
    },
    clean_word: function (html) {
        html = mw.wysiwyg.clean_word_list(html);
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
    },
    cleanTables: function (root) {
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
    },
    cleanHTML: function (root) {
        var root = root || document.body;
        mw.tools.foreachChildren(root, function () {
            if (mw.wysiwyg.hasContentFromWord(this)) {
                this.innerHTML = mw.wysiwyg.clean_word(this.innerHTML);
            }
            mw.wysiwyg.cleanTables(this);
        });
    },
    normalizeBase64Image: function (node, callback) {
        if (typeof node.src !== 'undefined' && node.src.indexOf('data:image/') === 0) {
            var type = node.src.split('/')[1].split(';')[0];
            var obj = {
                file: node.src,
                name: mw.random().toString(36) + "." + type
            }
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                var data = $.parseJSON(data);
                node.src = data.src;
                if (typeof callback === 'function') {
                    callback.call(node);
                }
                mw.wysiwyg.change(node);
                mw.trigger('imageSrcChanged', [node, node.src])
            });
        }
        else if (node.style.backgroundImage.indexOf('data:image/') !== -1) {
            var bg = node.style.backgroundImage.replace(/url\(/g, '').replace(/\)/g, '')
            var type = bg.split('/')[1].split(';')[0];
            var obj = {
                file: bg,
                name: mw.random().toString(36) + "." + type
            };
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                var data = $.parseJSON(data);
                node.style.backgroundImage = 'url(\'' + data.src + '\')';

                if (typeof callback === 'function') {
                    callback.call(node);
                }
                mw.wysiwyg.change(node);
                mw.trigger('nodeBackgroundChanged', [node, node.src])
            });
        }
    },
    normalizeBase64Images: function (root) {
        var root = root || document.body;
        var all = root.querySelectorAll(".edit img[src*='data:image/'], .edit [style*='data:image/'][style*='background-image']"),
            l = all.length, i = 0;
        if (l > 0) {
            for (; i < l; i++) {
                mw.tools.addClass(all[i], 'element');
                mw.wysiwyg.normalizeBase64Image(all[i]);
            }
        }
    },
    documentCommonFonts: function () {
      var checkNodes = $('html, body, h1:first, h2:first, p:first');
      var result = [];
        checkNodes.each(function () {
            var font = $(this).css('fontFamily').split(',')[0].trim();
            if(result.indexOf(font) === -1) {
                result.push(font)
            }
        });
        return result;
    }
}
mw.disable_selection = function (element) {
    var el = element || ".module";
    el = mw.$(el, ".edit").not(".unselectable");
    el.attr("unselectable", "on");
    el.addClass("unselectable");
    el.on("selectstart", function (event) {
        event.preventDefault();
        return false;
    });
};

mw.wysiwyg.dropdowns = function () {
    mw.$(".mw_dropdown_action_font_size").not('.ready').addClass('ready').change(function () {
        var val = mw.$(this).getDropdownValue();
        mw.wysiwyg.fontSize(val);
        mw.$('.mw-dropdown-val', this).append('px');
    });
    mw.$(".mw_dropdown_action_format").not('.ready').addClass('ready').change(function () {

        var val = mw.$(this).getDropdownValue();
        mw.wysiwyg.format(val);
    });
    mw.wysiwyg.initFontSelectorBox();
    mw.$("#wysiwyg_insert").not('.ready').addClass('ready').on("change", function () {
        var fnode = window.getSelection().focusNode;
        var isPlain = mw.tools.firstParentOrCurrentWithClass(fnode, 'plain-text');
        if (mw.wysiwyg.isSelectionEditable()) {
            var val = mw.$(this).getDropdownValue();

            var isTextlike = val == 'icon';
            if (!isTextlike && isPlain) {
                return false;
            }

            if (val == 'hr') {
                mw.wysiwyg._do('InsertHorizontalRule');
            }
            else if (val == 'box') {

                var div = mw.wysiwyg.applier('div', 'mw-ui-box mw-ui-box-content element');
                if (mw.wysiwyg.selection_length() <= 2) {
                    mw.$(div).append("<p>&nbsp;</p>");
                }
            }
            else if (val == 'pre') {
                var div = mw.wysiwyg.applier('pre', '');
                if (mw.wysiwyg.selection_length() <= 2) {
                    mw.$(div).append("&nbsp;");
                }
            } else if (val === 'code') {
                // var div = mw.wysiwyg.applier('code', '');
                var new_insert_html = prompt("Paste your code");
                if (new_insert_html != null) {
                    var div = mw.wysiwyg.applier('code');
                    div.innerHTML = new_insert_html;
                }
            } else if (val === 'insert_html') {
                var new_insert_html = prompt("Paste your html code in the box");
                if (new_insert_html != null) {

                    mw.wysiwyg.insert_html(new_insert_html)
                }
            } else if (val === 'icon') {

                var icdiv = mw.wysiwyg.applier('i');
                icdiv.className = "mw-icon";

                var mode = 3;
                if(mode === 3) {
                    mw.editorIconPicker.tooltip(icdiv)
                }
                if(mode === 2) {
                    var dialog = mw.icons.dialog();
                    $(dialog).on('Result', function(e, res){
                        res.render(res.icon, icdiv);
                        dialog.remove();
                    })
                }
                if(mode === 1) {

                    mw.editorIconPicker.tooltip(icdiv)

                    setTimeout(function () {
                        mw.sidebarSettingsTabs.set(2)
                    }, 10);
                }



            }
            else if (val === 'table') {
                var el = mw.wysiwyg.applier('div', 'element', {width: "100%"});
                el.innerHTML = '<table class="mw-wysiwyg-table"><tbody><tr><td>Lorem Ipsum</td><td  >Lorem Ipsum</td></tr><tr><td  >Lorem Ipsum</td><td  >Lorem Ipsum</td></tr></tbody></table>';

            }
            else if (val === 'quote') {
                var div = mw.wysiwyg.applier('blockquote', 'element');
                mw.$(div).append("<cite>By Lorem Ipsum</cite>");
            }
            //  mw.$(this).setDropdownValue("Insert", true, true, "Insert");
        }
        mw.$(this).find('.mw-dropdown-val').html('insert').find('.mw-dropdown-content').hide()
        mw.$(this).find('.mw-dropdown-content').hide()
    })
};
$(mwd).ready(function () {


    mw.wysiwyg.initClassApplier();

    mw.wysiwyg.dropdowns();

    mw.editorIconPicker = mw.iconPicker({
        iconOptions: { reset: true }
    });


    mw.editorIconPicker.on('select', function (data){
        data.render();
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });
    mw.editorIconPicker.on('sizeChange', function (size){
        mw.editorIconPicker.target.style.fontSize = size + 'px';
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');
        mw.wysiwyg.change(mw.editorIconPicker.target)
    })
    mw.editorIconPicker.on('colorChange', function (color){
        mw.editorIconPicker.target.style.color = color;
        mw.wysiwyg.change(mw.editorIconPicker.target)
    });

    mw.editorIconPicker.on('reset', function (color){
        mw.editorIconPicker.target.style.color = '';
        mw.editorIconPicker.target.style.fontSize = '';
        mw.tools.tooltip.setPosition(mw.editorIconPicker._tooltip, mw.editorIconPicker.target, 'bottom-center');

        mw.wysiwyg.change(mw.editorIconPicker.target)
    });


    if (!mw.wysiwyg._fontcolorpicker) {
        mw.lib.require('colorpicker');
        mw.wysiwyg._fontcolorpicker = mw.colorPicker({
            element: document.querySelector('#mw_editor_font_color'),
            tip: true,
            showHEX:false,
            onchange: function (color) {
                mw.wysiwyg.fontColor(color)
            }
        });
    }
    if (!mw.wysiwyg._bgfontcolorpicker) {
        mw.wysiwyg._bgfontcolorpicker = mw.colorPicker({
            element: document.querySelector('.mw_editor_font_background_color'),
            tip: true,
            showHEX: false,
            onchange: function (color) {
                mw.wysiwyg.fontbg(color)
            }
        });
    }

    mw.$(document).on('scroll', function () {
        if (mw.wysiwyg._bgfontcolorpicker && mw.wysiwyg._bgfontcolorpicker.settings) {
            mw.tools.tooltip.setPosition(mw.wysiwyg._bgfontcolorpicker.tip, mw.wysiwyg._bgfontcolorpicker.settings.element, mw.wysiwyg._bgfontcolorpicker.settings.position)
            mw.tools.tooltip.setPosition(mw.wysiwyg._fontcolorpicker.tip, mw.wysiwyg._fontcolorpicker.settings.element, mw.wysiwyg._fontcolorpicker.settings.position)
        }

    })


    mw.wysiwyg.nceui();
    mw.smallEditor = mw.$("#mw_small_editor");
    mw.smallEditorCanceled = true;
    mw.bigEditor = mw.$("#mw-text-editor");
    mw.$(document.body).on('mousedown touchstart', function (event) {
        var target = event.target;
        if ($(target).hasClass("element")) {
            mw.trigger("ElementMouseDown", target);
        }
        else if ($(target).parents(".element").length > 0) {
            mw.trigger("ElementMouseDown", mw.$(target).parents(".element")[0]);
        }
        if ($(target).hasClass("edit")) {
            mw.trigger("EditMouseDown", [target, target, event]);
        }
        else if ($(target).parents(".edit").length > 0) {
            mw.trigger("EditMouseDown", [$(target).parents(".edit")[0], target, event]);
        }
        var hp = document.getElementById('mw-history-panel');
        if (hp !== null && hp.style.display != 'none') {
            if (!hp.contains(target)) {
                hp.style.display = 'none';
                mw.$("#history_panel_toggle").removeClass('mw_editor_btn_active');
            }
        }
    });

    mw.wysiwyg.editorFonts = [];


});
$(window).on('load', function () {

    mw.$(this).on('imageSrcChanged', function (e, el, url) {


        var node = mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['mw-image-holder']);
        if (node) {
            url = mw.files.safeFilename(url);
            var img = node.querySelector('img');
            if(img) {
                img.src = url;
            }
            mw.$(node).css('backgroundImage', 'url(' + url + ')');
        }
    });

    mw.$(window).on("keydown", function (e) {

        if (e.type === 'keydown') {

            if (e.keyCode === 13) {
                var field = mw.tools.mwattr(e.target, 'field');
                if (field === 'title' || mw.tools.hasClass(e.target, 'plain-text')) {
                    e.preventDefault();
                }
            }
            if (e.ctrlKey) {
                var isPlain = mw.tools.firstParentOrCurrentWithClass(e.target, 'plain-text');
                if (!isPlain) {
                    var code = e.keyCode;
                    if (code === 66) {

                        mw.wysiwyg.execCommand('bold');
                        e.preventDefault();
                    }
                    else if (code == 73) {

                        mw.wysiwyg.execCommand('italic');
                        e.preventDefault();
                    }
                    else if (code == 85) {

                        mw.wysiwyg.execCommand('underline');
                        e.preventDefault();
                    }
                }
                else {
                    if (e.keyCode != 65 && e.keyCode != 86) { // ctrl v || a
                        //return false;
                    }

                }
            }
        }
    });
    mw.$(".mw_editor").each(function () {
        mw.tools.dropdown(this);
    });
    var nodes = document.querySelectorAll(".edit"), l = nodes.length, i = 0;
    for (; i < l; i++) {
        var node = nodes[i];
        var rel = mw.tools.mwattr(node, "rel");
        var field = mw.tools.mwattr(node, "field");
        if (field == 'content' && rel == 'content') {
            if (node.querySelector('p') !== null) {
                var node = node.querySelector('p');
            }
            // node.contentEditable = true;
        }
        if (!nodes[i].pasteBinded && !mw.tools.hasParentsWithClass(nodes[i], 'edit')) {
            nodes[i].pasteBinded = true;
            nodes[i].addEventListener("paste", function (e) {

                mw.wysiwyg.paste(e);
                mw.wysiwyg.change(e.target)
            });
        }

    }
});

mw.linkTip = {
    init: function (root) {
        if (root === null || !root) {
            return false;
        }
        mw.$(root).on('click', function (e) {
            var node = mw.linkTip.find(e.target);
            if (!!node) {
                mw.linkTip.tip(node);
                e.preventDefault()
            }
            else {
                mw.$('.mw-link-tip').remove();
            }
        });
    },
    find: function (target) {
        if (mw.tools.hasClass(target, 'module')) {
            return;
        }
        var a = mw.tools.firstMatchesOnNodeOrParent(target, ['a']);
        if(!a) return;

        if (!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(a, ['edit', 'module'])) {
            return;
        }
        return a;
    },
    tip: function (node) {
        if(!node) return;

        var link = document.createElement('a');
        link.href = node.getAttribute('href');
        link.target = '_blank';
        link.className = 'mw-link-tip-link';
        link.innerHTML = node.getAttribute('href');

        var editBtn = document.createElement('span');
        editBtn.className = 'mw-link-tip-edit';
        editBtn.innerHTML = mw.lang('Edit');

        var holder = document.createElement('div');

        holder.appendChild(link);
        holder.append(' - ');
        holder.appendChild(editBtn);

        editBtn.onclick = function(e) {
            e.preventDefault();
            new mw.LinkEditor({
                mode: 'dialog'
            })
                .setValue({url: node.href, text: node.innerHTML, target: node.target === '_blank'})
                .promise()
                .then(function (result){
                    node.href = result.url;
                    node.innerHTML = result.text;
                });
            mw.$('.mw-link-tip').remove();
            return false;
        }

        mw.linkTip._tip = mw.tooltip({content: holder, position: 'bottom-center', skin: 'dark', element: node});
        mw.$(mw.linkTip._tip).addClass('mw-link-tip');

    }
}


})();

(() => {
/*!**********************************!*\
  !*** ../liveedit/wysiwygmdab.js ***!
  \**********************************/

var canDestroy = function (event) {
    var target = event.target;
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(event, ['safe-element'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
};

mw.wysiwyg._manageDeleteAndBackspaceInSafeMode = {
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
        mw.liveEditState.record({
            target: parent,
            value: parent.innerHTML
        });
        $(todelete).remove();
        if(transfer && mw.tools.isEditable(transfer)) {
            setTimeout(function () {
                mw.wysiwyg.cursorToElement(transfer, transferPosition);
            });
        }
        mw.liveEditState.record({
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
                mw.wysiwyg.cursorToElement(prev, 'end');
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
                    mw.liveEditState.actionRecord(function() {
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
};
mw.wysiwyg.manageDeleteAndBackspaceInSafeMode = function (event, sel) {


    var node = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
    var range = sel.getRangeAt(0);
    if(!node.innerText.replace(/\s/gi, '')){
        mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.emptyNode(event, node, sel, range);
        return false;
    }
    mw.wysiwyg._manageDeleteAndBackspaceInSafeMode.nodeBoundaries(event, node, sel, range);
    return true;
};
mw.wysiwyg.manageDeleteAndBackspace = function (event, sel) {
    if (!mw.settings.liveEdit && self === top) {
        return;
    }

    if (mw.event.is.delete(event) || mw.event.is.backSpace(event)) {
        if(!sel.rangeCount) return;
        var r = sel.getRangeAt(0);
        var isSafe = mw.wysiwyg.isSafeMode();

        if(isSafe) {
            return mw.wysiwyg.manageDeleteAndBackspaceInSafeMode(event, sel);
        }

        if (!mw.settings.liveEdit) {
            return true;
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

                    if (nextel && !mw.ea.helpers.isBlockLevel(nextel) && ( typeof nextel.className === 'undefined' || !nextel.className.trim())) {
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
                else if ((focus.previousElementSibling === null && rootfocus.previousElementSibling === null) && mw.tools.hasAnyOfClassesOnNodeOrParent(rootfocus, ['nodrop', 'allow-drop'])) {
                    return false;
                }
                else {

                }
            }
            if (nextchar == '') {


                //continue check nodes
                if (mw.event.is.delete(event)) {
                    nextNode = mw.wysiwyg.merge.getNext(sel.focusNode);
                }
                if (mw.event.is.backSpace(event)) {
                    nextNode = mw.wysiwyg.merge.getPrev(sel.focusNode);
                }
                if (mw.wysiwyg.merge.alwaysMergeable(nextNode)) {
                    return true;
                }

                var nonbr = mw.wysiwyg.merge.isInNonbreakable(nextNode);
                if (nonbr) {
                    event.preventDefault();
                    return false;
                }

                if (nextNode.nodeValue == '') {

                }
                if (nextNode !== null && mw.wysiwyg.merge.isMergeable(nextNode)) {
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }
                else {
                    event.preventDefault()
                }
                //  }
                if (nextNode === null) {
                    nextNode = sel.focusNode.parentNode.nextSibling;
                    if (!mw.wysiwyg.merge.isMergeable(nextNode)) {
                        event.preventDefault();
                    }
                    if (mw.event.is.delete(event)) {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'next', event)
                    }
                    else {
                        mw.wysiwyg.merge.manageBreakables(sel.focusNode, nextNode, 'prev', event)
                    }
                }

            } else {

            }


    }

    return true;
};

})();

(() => {
/*!***************************************!*\
  !*** ../tools/system-tools/base64.js ***!
  \***************************************/
mw.tools.base64 = {
// private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
// public method for encoding
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = mw.tools.base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
// public method for decoding
    decode: function (input) {
        if (typeof input == 'undefined') {
            return;
        }
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = mw.tools.base64._utf8_decode(output);
        return output;
    },
// private method for UTF-8 encoding
    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    },
// private method for UTF-8 decoding
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
})();

(() => {
/*!********************************************!*\
  !*** ../tools/system-tools/image-tools.js ***!
  \********************************************/
mw.image.description =  {
    add: function (text) {
        var img = document.querySelector("img.element-current");
        img.title = text;
    },
    get: function () {
        return document.querySelector("img.element-current").title;
    },
    init: function (id) {
        var area = mw.$(id);
        area.hover(function () {
            area.addClass("desc_area_hover");
        }, function () {
            area.removeClass("desc_area_hover");
        });
        var curr = mw.image.description.get();
        if (!area.hasClass("inited")) {
            area.addClass("inited");
            area.bind("keyup change paste", function () {
                var val = mw.$(this).val();
                mw.image.description.add(val);
            });
        }
        area.val(curr);
        area.show();
    }
};

mw.image.settings = function () {
    return mw.dialogIframe({
        url: 'imageeditor',
        template: "mw_modal_basic",
        overlay: true,
        width: '600',
        height: "auto",
        autoHeight: true,
        name: 'mw-image-settings-modal'
    });
};


(function (){
    var image = {
        isResizing: false,
        currentResizing: null,
        resize: {
            create_resizer: function () {
                if (!mw.image_resizer) {
                    var resizer = document.createElement('div');
                    resizer.className = 'mw-defaults mw_image_resizer';
                    resizer.innerHTML = '<div id="image-edit-nav"><span onclick="mw.wysiwyg.media(\'#editimage\');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon image_change tip" data-tip="' + mw.msg.change + '"><span class="mdi mdi-image mdi-18px"></span></span><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon tip image_change" id="image-settings-button" data-tip="' + mw.msg.edit + '" onclick="mw.image.settings();"><span class="mdi mdi-pencil mdi-18px"></span></span></div>';
                    document.body.appendChild(resizer);
                    mw.image_resizer = resizer;
                    mw.image_resizer_time = null;
                    mw.image_resizer._show = function () {
                        clearTimeout(mw.image_resizer_time);
                        mw.$(mw.image_resizer).addClass('active');
                    };
                    mw.image_resizer._hide = function () {
                        clearTimeout(mw.image_resizer_time);
                        mw.image_resizer_time = setTimeout(function () {
                            mw.$(mw.image_resizer).removeClass('active');
                        }, 3000)
                    };

                    mw.$(resizer).on("click", function (e) {
                        if (mw.image.currentResizing[0].nodeName === 'IMG') {
                            mw.wysiwyg.select_element(mw.image.currentResizing[0]);
                        }
                    });
                    mw.$(resizer).on("dblclick", function (e) {
                        mw.wysiwyg.media('#editimage');
                    });
                }
            },
            prepare: function () {
                mw.image.resize.create_resizer();
                mw.$(mw.image_resizer).resizable({
                    handles: "all",
                    minWidth: 60,
                    minHeight: 60,
                    start: function () {
                        mw.image.isResizing = true;
                        mw.$(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
                        mw.$(mw.tools.firstParentWithClass(mw.image.currentResizing[0], 'edit')).addClass("changed");
                    },
                    stop: function () {
                        mw.image.isResizing = false;
                        mw.drag.fix_placeholders();
                    },
                    resize: function () {
                        var offset = mw.image.currentResizing.offset();
                        mw.$(this).css(offset);
                    },
                    aspectRatio: 16 / 9
                });
                mw.image_resizer.mwImageResizerComponent = true;
                var all = mw.image_resizer.querySelectorAll('*'), l = all.length, i = 0;
                for (; i < l; i++) all[i].mwImageResizerComponent = true
            },
            resizerSet: function (el, selectImage) {
                var selectImage = typeof selectImage === 'undefined' ? true : selectImage;
                /*  var order = mw.tools.parentsOrder(el, ['edit', 'module']);
                 if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){   */


                mw.$('.ui-resizable-handle', mw.image_resizer)[el.nodeName == 'IMG' ? 'show' : 'hide']()

                var el = mw.$(el);
                var offset = el.offset();
                var parent = el.parent();
                var parentOffset = parent.offset();
                if(parent[0].nodeName !== 'A'){
                    offset.top = offset.top < parentOffset.top ? parentOffset.top : offset.top;
                    offset.left = offset.left < parentOffset.left ? parentOffset.left : offset.left;
                }
                var r = mw.$(mw.image_resizer);
                var width = el.outerWidth();
                var height = el.outerHeight();
                r.css({
                    left: offset.left,
                    top: offset.top,
                    width: width,
                    height: mw.tools.hasParentsWithClass(el[0], 'mw-image-holder') ? 1 : height
                });
                r.addClass("active");
                mw.$(mw.image_resizer).resizable("option", "alsoResize", el);
                mw.$(mw.image_resizer).resizable("option", "aspectRatio", width / height);
                mw.image.currentResizing = el;
                if (!el[0].contentEditable) {
                    mw.wysiwyg.contentEditable(el[0], true);
                }

                if (selectImage) {
                    if (el[0].parentNode.tagName !== 'A') {
                        mw.wysiwyg.select_element(el[0]);
                    }
                    else {
                        mw.wysiwyg.select_element(el[0].parentNode);
                    }
                }
                if (document.getElementById('image-settings-button') !== null) {
                    if (!!el[0].src && el[0].src.contains('userfiles/media/pixum/')) {
                        document.getElementById('image-settings-button').style.display = 'none';
                    }
                    else {
                        document.getElementById('image-settings-button').style.display = '';
                    }
                }
                /* } */
            },
            init: function (selector) {
                mw.image_resizer == undefined ? mw.image.resize.prepare() : '';

                mw.on("ImageClick", function (e, el) {
                    if (!mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName === 'IMG') {
                        mw.image.resize.resizerSet(el);
                    }
                })
            }
        },

        _isrotating: false,
        rotate: function (img_object, angle) {
            if (!mw.image.Rotator) {
                mw.image.Rotator = document.createElement('canvas');
                mw.image.Rotator.style.top = '-9999px';
                mw.image.Rotator.style.left = '-9999px';
                mw.image.Rotator.style.position = 'absolute';
                mw.image.RotatorContext = mw.image.Rotator.getContext('2d');
                document.body.appendChild(mw.image.Rotator);
            }
            if (!mw.image._isrotating) {
                mw.image._isrotating = true;
                img_object = img_object || document.querySelector("img.element-current");
                if (img_object === null) {
                    return false;
                }
                mw.image.preload(img_object.src, function () {
                    if (!img_object.src.contains("base64")) {
                        var currDomain = mw.url.getDomain(window.location.href);
                        var srcDomain = mw.url.getDomain(img_object.src);
                        if (currDomain !== srcDomain) {
                            mw.tools.alert("This action is allowed for images on the same domain.");
                            return false;
                        }
                    }
                    var angle = angle || 90;
                    var image = mw.$(this);
                    var w = this.naturalWidth;
                    var h = this.naturalHeight;
                    var contextWidth = w;
                    var contextHeight = h;
                    var x = 0;
                    var y = 0;
                    switch (angle) {
                        case 90:
                            contextWidth = h;
                            contextHeight = w;
                            y = -h;
                            break;
                        case 180:
                            x = -w;
                            y = -h;
                            break;
                        case 270:
                            contextWidth = h;
                            contextHeight = w;
                            x = -w;
                            break;
                        default:
                            contextWidth = h;
                            contextHeight = w;
                            y = -h;
                    }
                    mw.image.Rotator.setAttribute('width', contextWidth);
                    mw.image.Rotator.setAttribute('height', contextHeight);
                    mw.image.RotatorContext.rotate(angle * Math.PI / 180);
                    mw.image.RotatorContext.drawImage(img_object, x, y);
                    var data = mw.image.Rotator.toDataURL("image/png");
                    img_object.src = data;
                    mw.image._isrotating = false;
                    if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(img_object);
                });
            }
        },
        grayscale: function (node) {
            var node = node || document.querySelector("img.element-current");
            if (node === null) {
                return false;
            }
            mw.image.preload(node.src, function () {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                canvas.width = this.naturalWidth;
                canvas.height = this.naturalHeight;
                ctx.drawImage(node, 0, 0);
                var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
                for (var y = 0; y < imgPixels.height; y++) {
                    for (var x = 0; x < imgPixels.width; x++) {
                        var i = (y * 4) * imgPixels.width + x * 4; //Why is this multiplied by 4?
                        var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
                        imgPixels.data[i] = avg;
                        imgPixels.data[i + 1] = avg;
                        imgPixels.data[i + 2] = avg;
                    }
                }
                ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
                node.src = canvas.toDataURL();
                if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
            })
        },
        vr: [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
        vg: [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
        vb: [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199],
        vintage: function (node) {
            var node = node || document.querySelector("img.element-current");
            if (node === null) {
                return false;
            }
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            mw.image.preload(node.src, function (w, h) {
                canvas.width = w;
                canvas.height = h;
                ctx.drawImage(node, 0, 0);
                var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height), l = imageData.data.length, i = 0;
                for (; i < l; i += 4) {
                    imageData.data[i] = mw.image.vr[imageData.data[i]];
                    imageData.data[i + 1] = mw.image.vg[imageData.data[i + 1]];
                    imageData.data[i + 2] = mw.image.vb[imageData.data[i + 2]];
                    if (noise > 0) {
                        var noise = Math.round(noise - Math.random() * noise), j = 0;
                        for (; j < 3; j++) {
                            var iPN = noise + imageData.data[i + j];
                            imageData.data[i + j] = (iPN > 255) ? 255 : iPN;
                        }
                    }
                }
                ctx.putImageData(imageData, 0, 0);
                node.src = canvas.toDataURL();
                if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
                mw.$(canvas).remove()
            });
        },
        _dragActivated: false,
        _dragcurrent: null,
        _dragparent: null,
        _dragcursorAt: {x: 0, y: 0},
        _dragTxt: function (e) {
            if (mw.image._dragcurrent !== null) {
                mw.image._dragcursorAt.x = e.pageX - mw.image._dragcurrent.offsetLeft;
                mw.image._dragcursorAt.y = e.pageY - mw.image._dragcurrent.offsetTop;
                var x = e.pageX - mw.image._dragparent.offsetLeft - mw.image._dragcurrent.startedX - mw.image._dragcursorAt.x;
                var y = e.pageY - mw.image._dragparent.offsetTop - mw.image._dragcurrent.startedY - mw.image._dragcursorAt.y;
                mw.image._dragcurrent.style.top = y + 'px';
                mw.image._dragcurrent.style.left = x + 'px';
            }
        }
    };

    for (var i in image) {
        mw.image[i] = image[i];
    }
})();

})();

(() => {
/*!************************************************!*\
  !*** ../tools/system-tools/modules-dialogs.js ***!
  \************************************************/
(function(){
    var systemDialogs = {
        moduleFrame: function(type, params, autoHeight){
            if(typeof autoHeight === 'undefined') {
                autoHeight = true;
            }
            params = params || {};
            if(!type) return;

            var frame = document.createElement('iframe');
            frame.className = 'mw-editor-frame';
            frame.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
            frame.allowFullscreen = true;
            frame.scrolling = "yes";
            frame.width = "100%";
            frame.frameBorder = "0";
            frame.src = mw.external_tool('module') + '?type=' + type + '&params=' + $.param(params).split('&').join(',');
            if(autoHeight) {
                mw.tools.iframeAutoHeight(frame)
            }
            return frame;
        },
          confirm_reset_module_by_id: function (module_id) {
        if (confirm("Are you sure you want to reset this module?")) {
            var is_a_preset = mw.$('#'+module_id).attr('data-module-original-id');
            var is_a_preset_attrs = mw.$('#'+module_id).attr('data-module-original-attrs');
            if(is_a_preset){
                var orig_attrs_decoded = JSON.parse(window.atob(is_a_preset_attrs));
                if (orig_attrs_decoded) {
                    mw.$('#'+module_id).removeAttr('data-module-original-id');
                    mw.$('#'+module_id).removeAttr('data-module-original-attrs');
                    mw.$('#'+module_id).attr(orig_attrs_decoded).reload_module();

                    if(  mw.top().win.module_settings_modal_reference_preset_editor_thismodal ){
                        mw.top().win.module_settings_modal_reference_preset_editor_thismodal.remove();
                    }
                 }
                 return;
            }

            var data = {};
            data.modules_ids = [module_id];

            var childs_arr = [];

            mw.$('#'+module_id).andSelf().find('.edit').each(function (i) {
                var some_child = {};

                mw.tools.removeClass(this, 'changed')
                some_child.rel = mw.$(this).attr('rel');
                some_child.field = mw.$(this).attr('field');

                childs_arr.push(some_child);

            });


            window.mw.on.DOMChangePause = true;

            if (childs_arr.length) {
                $.ajax({
                    type: "POST",
                   // dataType: "json",
                    //processData: false,
                    url: mw.settings.api_url + "content/reset_edit",
                    data: {reset:childs_arr}
                  //  success: success,
                  //  dataType: dataType
                });
           }


           //data-module-original-attrs

            $.ajax({
                type: "POST",
                // dataType: "json",
                //processData: false,
                url: mw.settings.api_url + "content/reset_modules_settings",
                data: data,
                success: function(){

                    setTimeout(function () {


                        mw.$('#'+module_id).removeAttr('data-module-original-id');
                        mw.reload_module('#'+module_id);
                        window.mw.on.DOMChangePause = false;

                    }, 1000);

                 },
            });
        }
    },
    open_reset_content_editor: function (root_element_id) {

        var src = mw.settings.site_url + 'api/module?id=mw_global_reset_content_editor&live_edit=true&module_settings=true&type=editor/reset_content&autosize=true';

        if(typeof(root_element_id) != 'undefined') {
            var src = src + '&root_element_id='+root_element_id;
        }

        // mw.dialogIframe({
        var modal = mw.dialogIframe({
            url: src,
            // width: 500,
            // height: mw.$(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-reset-content-editor-front',
            title: 'Reset content',
            template: 'default',
            center: false,
            resize: true,
            autosize: true,
            autoHeight: true,
            draggable: true
        });
    },
    open_global_module_settings_modal: function (module_type, module_id, modalOptions, additional_params) {


        var params = {};
        params.id = module_id;
        params.live_edit = true;
        params.module_settings = true;
        params.type = module_type;
        params.autosize = false;

        var params_url = $.extend({}, params, additional_params);

        var src = mw.settings.site_url + "api/module?" + json2url(params_url);


        modalOptions = modalOptions || {};

        var defaultOpts = {
            url: src,
            // width: 500,
            height: 'auto',
            autoHeight: true,
            name: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        };

        var settings = $.extend({}, defaultOpts, modalOptions);

        // return mw.dialogIframe(settings);
        return mw.top().dialogIframe(settings);
    },
    open_module_modal: function (module_type, params, modalOptions) {

        var id = mw.id('module-modal-');
        var id_content = id + '-content';
        modalOptions = modalOptions || {};

        var settings = $.extend({}, {
            content: '<div class="module-modal-content" id="' + id_content + '"></div>',
            id: id
        }, modalOptions, {skin: 'default'});

        var xhr = false;
        var openiframe = false;
        if (typeof (settings.iframe) != 'undefined' && settings.iframe) {
            openiframe = true;
        }
        if (openiframe) {

            var additional_params = {};
            additional_params.type = module_type;
            var params_url = $.extend({}, params, additional_params);
            var src = mw.settings.site_url + "api/module?" + json2url(params_url);


            var settings = {
                url: src,
                name: 'mw-module-settings-editor-front',
                title: 'Settings',
                center: false,
                resize: true,
                draggable: true,
                height:'auto',
                autoHeight: true
            };
            return mw.top().dialogIframe(settings);

        } else {
            delete settings.skin;
            delete settings.template;
            settings.height = 'auto';
            settings.autoHeight = true;
            settings.encapsulate = false;
            var modal = mw.dialog(settings);
            xhr = mw.load_module(module_type, '#' + id_content, function(){
                setTimeout(function(){
                    modal.center();
                },333)
            }, params);
        }


        return {
            xhr: xhr,
            modal: modal,
        }
    }
    };

    for (var i in systemDialogs) {
        mw.tools[i] = systemDialogs[i];
    }
})()

})();

(() => {
/*!***************************************!*\
  !*** ../tools/widgets/colorpicker.js ***!
  \***************************************/
mw._colorPickerDefaults = {
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
};

mw._colorPicker = function (options) {
    mw.lib.require('colorpicker');
    if (!mw.tools.colorPickerColors) {
        mw.tools.colorPickerColors = [];

        // var colorpicker_els = mw.top().$("body *");
        // if(colorpicker_els.length > 0){
        //     colorpicker_els.each(function () {
        //         var css = parent.getComputedStyle(this, null);
        //         if (css !== null) {
        //             if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
        //                 mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color));
        //             }
        //             if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
        //                 mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor));
        //             }
        //         }
        //     });
        // }

    }
    var proto = this;
    if (!options) {
        return false;
    }

    var settings = $.extend({}, mw._colorPickerDefaults, options);

    if (settings.element === undefined || settings.element === null) {
        return false;
    }



    var $el = mw.$(settings.element);
    if ($el[0] === undefined) {
        return false;
    }
    if($el[0].mwcolorPicker) {
        return $el[0].mwcolorPicker;
    }


    $el[0].mwcolorPicker = this;
    this.element = $el[0];
    if ($el[0].mwToolTipBinded !== undefined) {
        return false;
    }
    if (!settings.method) {
        if (this.element.nodeName === 'DIV') {
            settings.method = 'inline';
        }
    }
    this.settings = settings;

    $el[0].mwToolTipBinded = true;
    var sett = {
        showAlpha: true,
        showHSL: false,
        showRGB: false,
        showHEX: true,
        palette: mw.tools.colorPickerColors
    };

    if(settings.value) {
        sett.color = settings.value
    }
    if(typeof settings.showRGB !== 'undefined') {
        sett.showRGB = settings.showRGB
    }
    if(typeof settings.showHEX !== 'undefined') {
        sett.showHEX = settings.showHEX
    }

    if(typeof settings.showHSL !== 'undefined') {
        sett.showHSL = settings.showHSL
    }
    var frame;
    if (settings.method === 'inline') {

        sett.attachTo = $el[0];

        frame = AColorPicker.createPicker(sett);
        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                var val = val === 'transparent' ? val : '#' + val;
                $el.val(val);
            }
        }

    }
    else {
        var tip = mw.tooltip(settings), $tip = mw.$(tip).hide();
        this.tip = tip;

        mw.$('.mw-tooltip-content', tip).empty();
        sett.attachTo = mw.$('.mw-tooltip-content', tip)[0]

        frame = AColorPicker.createPicker(sett);

        frame.onchange = function (data) {

            if(frame.pause) {
                return;
            }

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                $el.val(data.color);
            }
        };
        if ($el[0].nodeName === 'INPUT') {
            $el.on('focus', function (e) {
                if(this.value.trim()){
                    frame.pause = true;
                    frame.color = this.value;
                    setTimeout(function () {
                        frame.pause = false;
                    });
                }
                mw.$(tip).show();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        else {
            $el.on('click', function (e) {
                mw.$(tip).toggle();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        var documents = [document];
        if (self !== top){
            documents.push(top.document);
        }
        mw.$(documents).on('click', function (e) {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip') && e.target !== $el[0]) {
                mw.$(tip).hide();
            }
        });
        if ($el[0].nodeName === 'INPUT') {
            $el.bind('blur', function () {
                //$(tip).hide();
            });
        }
    }
    if (this.tip) {
        this.show = function () {
            mw.$(this.tip).show();
            mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position)
        };
        this.hide = function () {
            mw.$(this.tip).hide()
        };
        this.toggle = function () {
            var tip = mw.$(this.tip);
            if (tip.is(':visible')) {
                this.hide()
            }
            else {
                $el.focus();
                this.show()
            }
        }
    }

};
mw.colorPicker = function (o) {

    return new mw._colorPicker(o);
};

})();

(() => {
/*!**********************************!*\
  !*** ../tools/widgets/dialog.js ***!
  \**********************************/
(function (mw) {



    mw.dialog = function (options) {
        return new mw.Dialog(options);
    };


    mw.dialogIframe = function (options, cres) {
        options.pauseInit = true;
        var attr = 'frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen';
        if (options.autoHeight) {
            attr += ' scrolling="no"';
            options.height = 'auto';
        }
        options.content = '<iframe src="' + mw.external_tool(options.url.trim()) + '" ' + attr + '><iframe>';
        options.className = ('mw-dialog-iframe mw-dialog-iframe-loading ' + (options.className || '')).trim();
        options.className += (options.autoHeight ? ' mw-dialog-iframe-autoheight' : '');
        var dialog = new mw.Dialog(options, cres);
        dialog.iframe = dialog.dialogContainer.querySelector('iframe');
        mw.tools.loading(dialog.dialogContainer, 90);



        setTimeout(function () {
            var frame = dialog.dialogContainer.querySelector('iframe');
            frame.style.minHeight = 0; // reset in case of conflicts
            if (options.autoHeight) {
                mw.tools.iframeAutoHeight(frame, {dialog: dialog});
            } else{
                $(frame).height(options.height - 60);
                frame.style.position = 'relative';
            }
            mw.$(frame).on('load', function () {
                mw.tools.loading(dialog.dialogContainer, false);
                setTimeout(function () {
                    dialog.center();
                    mw.$(frame).on('bodyResize', function () {
                        dialog.center();
                    });
                    dialog.dialogMain.classList.remove('mw-dialog-iframe-loading');
                    frame.contentWindow.thismodal = dialog;
                    if (options.autoHeight) {
                        mw.tools.iframeAutoHeight(frame, {dialog: dialog});
                    }
                }, 78);
                if (mw.tools.canAccessIFrame(frame)) {
                    mw.$(frame.contentWindow.document).on('keydown', function (e) {
                        if (mw.event.is.escape(e) && !mw.event.targetIsField(e)) {
                            if(mw.top().__dialogs && mw.top().__dialogs.length){
                                var dlg = mw.top().__dialogs;
                                dlg[dlg.length - 1]._doCloseButton();
                                $(dlg[dlg.length - 1]).trigger('closedByUser');
                            }
                            else {
                                if (dialog.options.closeOnEscape) {
                                    dialog._doCloseButton();
                                    $(dialog).trigger('closedByUser');
                                }
                            }
                        }
                    });
                }
                if(typeof options.onload === 'function') {
                    options.onload.call(dialog);
                }
            });
        }, 12);
        return dialog;
    };

    /** @deprecated */
    mw.modal = mw.dialog;
    mw.modalFrame = mw.dialogIframe;

    mw.dialog.remove = function (selector) {
        return mw.dialog.get(selector).remove();
    };

    mw.dialog.get = function (selector) {
        selector = selector || '.mw-dialog';
        var $el = mw.$(selector);
        var el = $el[0];

        if(!el) return false;

        if(el._dialog) {
            return el._dialog;
        }
        var child_cont = el.querySelector('.mw-dialog-holder');
        var parent_cont = $el.parents(".mw-dialog-holder:first");
        if (child_cont) {
            return child_cont._dialog;
        }
        else if (parent_cont.length !== 0) {
            return parent_cont[0]._dialog;
        }
        else if (window.thismodal) {
            return thismodal;
        }
        else {
             // deprecated
            child_cont = el.querySelector('.mw_modal');
            parent_cont = $el.parents(".mw_modal:first");
            if(child_cont) {
                return child_cont.modal;
            } else if (parent_cont.length !== 0) {
                return parent_cont[0].modal;
            }
            return false;
        }
    };


    mw.Dialog = function (options, cres) {

        var scope = this;

        options = options || {};
        options.content = options.content || options.html || '';

        if(!options.height && typeof options.autoHeight === 'undefined') {
            options.height = 'auto';
            options.autoHeight = true;
        }

        var defaults = {
            skin: 'default',
            overlay: true,
            overlayClose: false,
            autoCenter: true,
            root: document,
            id: mw.id('mw-dialog-'),
            content: '',
            closeOnEscape: true,
            closeButton: true,
            closeButtonAppendTo: '.mw-dialog-header',
            closeButtonAction: 'remove', // 'remove' | 'hide'
            draggable: true,
            scrollMode: 'inside', // 'inside' | 'window',
            centerMode: 'intuitive', // 'intuitive' | 'center'
            containment: 'window',
            overflowMode: 'auto', // 'auto' | 'hidden' | 'visible'
        };

        this.options = $.extend({}, defaults, options, {
            skin: 'default'
        });

        this.id = this.options.id;
        var exist = document.getElementById(this.id);
        if (exist) {
            return exist._dialog;
        }

        this.hasBeenCreated = function () {
            return this.options.root.getElementById(this.id) !== null;
        };

        if (this.hasBeenCreated()) {
            return this.options.root.getElementById(this.id)._dialog;
        }

        if(!mw.top().__dialogs ) {
            mw.top().__dialogs = [];
        }
        if (!mw.top().__dialogsData) {
            mw.top().__dialogsData = {};
        }


        if (!mw.top().__dialogsData._esc) {
            mw.top().__dialogsData._esc = true;
            mw.$(document).on('keydown', function (e) {
                if (mw.event.is.escape(e)) {
                    var dlg = mw.top().__dialogs[mw.top().__dialogs.length - 1];
                    if (dlg && dlg.options && dlg.options.closeOnEscape) {
                        dlg._doCloseButton();
                    }
                }
            });
        }

        mw.top().__dialogs.push(this);

        this.draggable = function () {
            if (this.options.draggable && $.fn.draggable) {
                var $holder = mw.$(this.dialogHolder);
                $holder.draggable({
                    handle: this.options.draggableHandle || '.mw-dialog-header',
                    start: function () {
                        $holder.addClass('mw-dialog-drag-start');
                        scope._dragged = true;
                    },
                    stop: function () {
                        $holder.removeClass('mw-dialog-drag-start');
                    },
                    containment: scope.options.containment,
                    iframeFix: true
                });
            }
        };

        this.header = function () {
            this.dialogHeader = this.options.root.createElement('div');
            this.dialogHeader.className = 'mw-dialog-header';
            if (this.options.title || this.options.header) {
                this.dialogHeader.innerHTML = '<div class="mw-dialog-title">' + (this.options.title || this.options.header) + '</div>';
            }
        };

        this.footer = function (content) {
            this.dialogFooter = this.options.root.createElement('div');
            this.dialogFooter.className = 'mw-dialog-footer';
            if (this.options.footer) {
                $(this.dialogFooter).append(this.options.footer);
            }
        };

        this.title = function (title) {
            var root = mw.$('.mw-dialog-title', this.dialogHeader);
            if (typeof title === 'undefined') {
                return root.html();
            } else {
                if (root[0]) {
                    root.html(title);
                }
                else {
                    mw.$(this.dialogHeader).prepend('<div class="mw-dialog-title">' + title + '</div>');
                }
            }
        };


        this.build = function () {
            this.dialogMain = this.options.root.createElement('div');

            this.dialogMain.id = this.id;
            var cls = 'mw-dialog mw-dialog-scroll-mode-' + this.options.scrollMode
                + ' mw-dialog-skin-' + this.options.skin
                + ' mw-dialog-overflowMode-' + this.options.overflowMode;
            cls += (!this.options.className ? '' : (' ' + this.options.className));
            this.dialogMain.className = cls;
            this.dialogMain._dialog = this;

            this.dialogHolder = this.options.root.createElement('div');
            this.dialogHolder.id = 'mw-dialog-holder-' + this.id;


            this.dialogHolder._dialog = this;

            this.header();
            this.footer();
            this.draggable();



            this.dialogContainer = this.options.root.createElement('div');
            this.dialogContainer._dialog = this;

            // TODO: obsolate
            this.container = this.dialogContainer;


            this.dialogContainer.className = 'mw-dialog-container';
            this.dialogHolder.className = 'mw-dialog-holder';

            var cont = this.options.content;
            if(this.options.shadow) {
                this.shadow = this.dialogContainer.attachShadow({
                    mode: 'open'
                });
                if(typeof cont === 'string') {
                    this.shadow.innerHTML = (cont);
                } else {
                    this.shadow.appendChild(cont);
                }
            } else {
                mw.$(this.dialogContainer).append(cont);
            }


            if (this.options.encapsulate) {
                this.iframe = cont;
                this.iframe.style.display = '';
            }

            this.dialogHolder.appendChild(this.dialogHeader);
            this.dialogHolder.appendChild(this.dialogContainer);
            this.dialogHolder.appendChild(this.dialogFooter);

            this.closeButton = this.options.root.createElement('div');
            this.closeButton.className = 'mw-dialog-close';

            this.closeButton.$scope = this;

            this.closeButton.onclick = function () {
                this.$scope[this.$scope.options.closeButtonAction]();
                $(this.$scope).trigger('closedByUser');
            };
            this.main = mw.$(this.dialogContainer); // obsolete
            this.main.width = this.width;

            this.width(this.options.width || 600);
            this.height(this.options.height || 320);

            this.options.root.body.appendChild(this.dialogMain);
            this.dialogMain.appendChild(this.dialogHolder);
            if (this.options.closeButtonAppendTo) {
                mw.$(this.options.closeButtonAppendTo, this.dialogMain).append(this.closeButton)
            }
            else {
                this.dialogHolder.appendChild(this.closeButton);

            }
            this.dialogOverlay();
            return this;
        };

        this._doCloseButton = function() {
            this[this.options.closeButtonAction]();
        };

        this.containmentManage = function () {
            if (scope.options.containment === 'window') {
                if (scope.options.scrollMode === 'inside') {
                    var rect = this.dialogHolder.getBoundingClientRect();
                    var $win = mw.$(window);
                    var sctop = $win.scrollTop();
                    var height = $win.height();
                    if (rect.top < sctop || (sctop + height) > (rect.top + rect.height)) {
                        this.center();
                    }
                }
            }
        };

        this.dialogOverlay = function () {
            this.overlay = this.options.root.createElement('div');
            this.overlay.className = 'mw-dialog-overlay';
            this.overlay.$scope = this;
            if (this.options.overlay === true) {
                this.dialogMain.appendChild(this.overlay);
            }
            mw.$(this.overlay).on('click', function () {
                if (this.$scope.options.overlayClose === true) {
                    this.$scope._doCloseButton();
                    $(this.$scope).trigger('closedByUser');
                }
            });
            return this;
        };

        this._afterSize = function() {
            if(mw._iframeDetector) {
                mw._iframeDetector.pause = true;
                var frame = window.frameElement;
                if(frame && parent !== top){
                    var height = this.dialogContainer.scrollHeight + this.dialogHeader.scrollHeight;
                    if($(frame).height() < height) {
                        frame.style.height = ((height + 100) - this.dialogHeader.offsetHeight - this.dialogFooter.offsetHeight) + 'px';
                        if(window.thismodal){
                            thismodal.height(height + 100);
                        }

                    }
                }
            }
        };

        this.show = function () {
            mw.$(this.dialogMain).find('iframe').each(function(){
                this._intPause = false;
            });
            mw.$(this.dialogMain).addClass('active');
            this.center();
            this._afterSize();
            mw.$(this).trigger('Show');
            mw.trigger('mwDialogShow', this);
            return this;
        };

        this._hideStart = false;
        this.hide = function () {
            if (!this._hideStart) {
                this._hideStart = true;
                mw.$(this.dialogMain).find('iframe').each(function(){
                    this._intPause = true;
                });
                setTimeout(function () {
                    scope._hideStart = false;
                }, 300);
                mw.$(this.dialogMain).removeClass('active');
                if(mw._iframeDetector) {
                    mw._iframeDetector.pause = false;
                }
                mw.$(this).trigger('Hide');
                mw.trigger('mwDialogHide', this);
            }
            return this;
        };

        this.remove = function () {
            this.hide();
            mw.removeInterval('iframe-' + this.id);
            mw.$(this).trigger('BeforeRemove');
            if (typeof this.options.beforeRemove === 'function') {
                this.options.beforeRemove.call(this, this)
            }
            mw.$(this.dialogMain).remove();
            if(this.options.onremove) {
                this.options.onremove()
            }
            mw.$(this).trigger('Remove');
            mw.trigger('mwDialogRemove', this);
            for (var i = 0; i < mw.top().__dialogs.length; i++) {
                if (mw.top().__dialogs[i] === this) {
                    mw.top().__dialogs.splice(i, 1);
                    break;
                }
            }
            return this;
        };

        this.destroy = this.remove;

        this._prevHeight = -1;
        this._dragged = false;

        this.center = function (width, height) {
            var $holder = mw.$(this.dialogHolder), $window = mw.$(window);
            var holderHeight = height || $holder.outerHeight();
            var holderWidth = width || $holder.outerWidth();
            var dtop, css = {};

            if (this.options.centerMode === 'intuitive' && this._prevHeight < holderHeight) {
                dtop = $window.height() / 2 - holderHeight / 2;
            } else if (this.options.centerMode === 'center') {
                dtop = $window.height() / 2 - holderHeight / 2;
            }

            if (!scope._dragged) {
                css.left = $window.outerWidth() / 2 - holderWidth / 2;
            } else {
                css.left = parseFloat($holder.css('left'));
            }

            if(css.left + holderWidth > $window.width()){
                css.left = css.left - ((css.left + holderWidth) - $window.width());
            }

            if (dtop) {
                css.top = dtop > 0 ? dtop : 0;
            }

            if(window !== mw.top().win && document.body.scrollHeight > mw.top().win.innerHeight){
                $win = $(mw.top());

                css.top = $(document).scrollTop() + 50;
                var off = $(window.frameElement).offset();
                if(off.top < 0) {
                    css.top += Math.abs(off.top);
                }
                if(window.thismodal) {
                    css.top += thismodal.dialogContainer.scrollTop;
                }

            }


            $holder.css(css);
            this._prevHeight = holderHeight;


            this._afterSize();
            mw.$(this).trigger('dialogCenter');

            return this;
        };

        this.width = function (width) {
            if(!width) {
                return mw.$(this.dialogHolder).outerWidth();
            }
            mw.$(this.dialogHolder).width(width);
            this._afterSize();
        };
        this.height = function (height) {
            if(!height) {
                return mw.$(this.dialogHolder).outerHeight();
            }
            mw.$(this.dialogHolder).height(height);
            this._afterSize();
        };
        this.resize = function (width, height) {
            if (typeof width !== 'undefined') {
                this.width(width);
            }
            if (typeof height !== 'undefined') {
                this.height(height);
            }
            this.center(width, height);
        };
        this.content = function (content) {
            this.options.content = content || '';
            $(this.dialogContainer).empty().append(this.options.content);
            return this;
        };

        this.result = function(result, doClose) {
            this.value = result;
            if(this.options.onResult){
                this.options.onResult.call( this, result );
            }
            if (cres) {
                cres.call( this, result );
            }
            $(this).trigger('Result', [result]);
            if(doClose){
                this._doCloseButton();
            }
        };


        this.contentMaxHeight = function () {
            var scope = this;
            if (this.options.scrollMode === 'inside') {
                mw.interval('iframe-' + this.id, function () {
                    var max = mw.$(window).height() - scope.dialogHeader.clientHeight - scope.dialogFooter.clientHeight - 40;
                    scope.dialogContainer.style.maxHeight = max + 'px';
                    scope.containmentManage();
                });
            }
        };
        this.init = function () {
            this.build();
            this.contentMaxHeight();
            this.center();
            this.show();
            if (this.options.autoCenter) {
                (function (scope) {
                    mw.$(window).on('resize orientationchange load', function () {
                        scope.contentMaxHeight();
                        scope.center();
                    });
                })(this);
            }
            if (!this.options.pauseInit) {
                mw.$(this).trigger('Init');
            }
            setTimeout(function(){
                scope.center();
                setTimeout(function(){
                    scope.center();
                    setTimeout(function(){
                        scope.center();
                    }, 3000);
                }, 333);
            }, 78);


            return this;
        };
        this.init();

    };

    mw.Dialog.elementIsInDialog = function (node) {
        return mw.tools.firstParentWithClass(node, 'mw-dialog');
    };




})(window.mw);


(function () {
    function scoped() {
        var all = document.querySelectorAll('style[scoped]'), i = 0;

        try {
            for( ; i < all.length; i++ ) {
                var parent = all[i].parentNode;
                parent.id = parent.id || mw.id('scoped-id-');
                var prefix = '#' + parent.id + ' ';
                var rules = all[i].sheet.rules;
                var r = 0;
                for ( ; r < rules.length; r++) {
                    var newRule = prefix + rules[r].cssText;
                    all[i].sheet.deleteRule(r);
                    all[i].sheet.insertRule(newRule, r);
                }
                all[i].removeAttribute('scoped');
            }
        }
        catch(error) {

        }


    }
    scoped();
    $(window).on('load', function () {
        scoped();
    });
})();



})();

(() => {
/*!***************************************!*\
  !*** ../tools/widgets/elementedit.js ***!
  \***************************************/
mw.tools.elementEdit = function (el, textonly, callback, fieldClass) {
    if (!el || el.querySelector('.mw-live-edit-input') !== null) {
        return;
    }
    textonly = (typeof textonly === 'undefined') ? true : textonly;
    var input = document.createElement('span');
    input.className = (fieldClass || "") + ' mw-live-edit-input mw-liveedit-field';
    input.contentEditable = true;
    var $input = $(input);
    if (textonly === true) {
        input.innerHTML = el.textContent;
        input.onblur = function () {
            var val = $input.text();
            var ischanged = true;
            setTimeout(function () {
                mw.$(el).text(val);
                if (typeof callback === 'function' && ischanged) {
                    callback.call(val, el);
                }
            }, 3);
        };
        input.onkeydown = function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                mw.$(el).text($input.text());
                if (typeof callback === 'function') {
                    callback.call($input.text(), el);
                }
                return false;
            }
        }
    }
    else {
        input.innerHTML = el.innerHTML;
        input.onblur = function () {
            var val = this.innerHTML;
            var ischanged = this.changed === true;
            setTimeout(function () {
                el.innerHTML = val;
                if (typeof callback === 'function' && ischanged) {
                    callback.call(val, el);
                }
            }, 3)
        }
        input.onkeydown = function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                var val = this.innerHTML;
                el.innerHTML = val;
                if (typeof callback === 'function') {
                    callback.call(val, el);
                }
                return false;
            }
        }
    }
    mw.$(el).empty().append(input);
    $input.focus();
    input.changed = false;
    $input.change(function () {
        this.changed = true;
    });
    return input;
}

})();

(() => {
/*!***********************************!*\
  !*** ../tools/widgets/gallery.js ***!
  \***********************************/
(function(){
    mw.require('modal.css');

    var Gallery = function (array, startFrom) {
        startFrom = startFrom || 0;

        this.currentIndex = startFrom;

        this.data = array;
        var scope = this;

        this._template = function () {
            var el = document.createElement('div');
            el.className = 'mw-gallery';
            el.innerHTML = '' +
            '<div class="">' +
                '<div class="mw-gallery-overlay"></div>' +
                '<div class="mw-gallery-content"></div>' +
                '<div class="mw-gallery-prev"></div>' +
                '<div class="mw-gallery-next"></div>' +
                '<div class="mw-gallery-controls">' +
                    '<span class="mw-gallery-control-play"></span>' +
                    /*'<span class="mw-gallery-control-fullscreen"></span>' +*/
                    '<span class="mw-gallery-control-close"></span>' +
                '</div>' +
            '</div>';
            return el;
        };

        this.createSingle = function (item, i) {
            var el = document.createElement('div');
            el.className = 'mw-gallery-item mw-gallery-item-' + i + (startFrom === i ? ' active' : '');
            var desc = !item.description ? '' : '<div class="mw-gallery-item-description">'+item.description+'</div>';
            el.innerHTML = '<div class="mw-gallery-item-image"><img src="'+(item.image || item.url || item.src)+'"></div>' + desc;
            this.container.appendChild(el);
            return el;
        };

        this.next = function () {
            this.currentIndex++;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = 0;
            }
            this.goto(this.currentIndex);
        };

        this.prev = function () {
            this.currentIndex--;
            if(!this._items[this.currentIndex]) {
                this.currentIndex = this._items.length - 1;
            }
            this.goto(this.currentIndex);
        };

        this.goto = function (i) {
            if(i > -1 && i < this._items.length) {
                this.currentIndex = i;
                this._items.forEach(function (item, i){
                    item.classList.remove('active');
                    if(i === scope.currentIndex) {
                        item.classList.add('active');
                    }
                });
            }
        };

        this.paused = true;

        this.pause = function () {
            this.paused = true;
            clearTimeout(this.playInterval);
            mw.tools.loading(this.template, false, );
        };

        this.playInterval = null;
        this._play = function () {
            if(this.paused) return;
            mw.tools.loading(this.template, 100, 'slow');
            this.playInterval = setTimeout(function (){
                mw.tools.loading(scope.template, 'hide');
                scope.next();
                scope._play();
            },5000);
        };

        this.play = function () {
            this.next();
            this.paused = false;
            this._play();
        };

        this._items = [];

        this.createHandles = function () {
            this.template.querySelector('.mw-gallery-prev').onclick = function (){ scope.pause(); scope.prev(); };
            this.template.querySelector('.mw-gallery-next').onclick = function (){ scope.pause(); scope.next(); };
            this.template.querySelector('.mw-gallery-control-close').onclick = function (){ scope.remove(); };
            this.template.querySelector('.mw-gallery-control-play').onclick = function (){
                scope[scope.paused ? 'play' : 'pause']();
                this.classList[scope.paused ? 'remove' : 'add']('pause');
            };
        };

        this.createItems = function () {
            this.data.forEach(function (item, i ){
                scope._items.push(scope.createSingle(item, i));
            });
        };

        this.init = function () {
            this.template = this._template();
            document.body.appendChild(this.template);
            this.container = this.template.querySelector('.mw-gallery-content');
            this.createItems();
            this.createHandles();
        };

        this.remove = function () {
            this.template.remove();
        };

        this.init();
    };

    mw.gallery = function (array, startFrom){
        return new Gallery(array, startFrom);
    };

    // obsolate:
    mw.tools.gallery = {
        init: mw.gallery
    };
})();

})();

(() => {
/*!****************************************!*\
  !*** ../tools/widgets/iframe-tools.js ***!
  \****************************************/
mw.tools.createAutoHeight = function() {
    if(window.thismodal && thismodal.iframe) {
        mw.tools.iframeAutoHeight(thismodal.iframe);
    }
    else if(mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
        mw.tools.iframeAutoHeight(mw.top().win.frameElement);
    } else if(window.top !== window) {
        mw.top().$('iframe').each(function(){
            try{
                if(this.contentWindow === window) {
                    mw.tools.iframeAutoHeight(this);
                }
            } catch(e){}
        })
    }
};

mw.tools.moduleFrame = function(type, template){
    return mw.dialogIframe({
        url: mw.external_tool('module_dialog') + '?module=' + type + (template ? ('&template=' + template) : ''),
        width: 532,
        height: 'auto',
        autoHeight:true,
        title: type,
        className: 'mw-dialog-module-settings',
        closeButtonAction: 'remove'
    });
};


mw.tools.iframeAutoHeight = function(frame, opt){

    frame = mw.$(frame)[0];
    if(!frame) return;

    var _detector = document.createElement('div');
    _detector.className = 'mw-iframe-auto-height-detector';
    _detector.id = mw.id();

    var insertDetector = function() {
        if(frame.contentWindow && frame.contentWindow.document && frame.contentWindow.document.body){
            var det = frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector');
            if(!det){
                frame.contentWindow.document.body.appendChild(_detector);
            } else if(det !== frame.contentWindow.document.body.lastChild){
                frame.contentWindow.document.body.appendChild(det);
            }
            if(frame.contentWindow.mw) {
                frame.contentWindow.mw._iframeDetector = _detector;
            }

        }
    };



    setTimeout(function(){
        insertDetector();
    }, 100);
    frame.scrolling="no";
    frame.style.minHeight = 0 + 'px';
    mw.$(frame).on('load resize', function(){

        if(!mw.tools.canAccessIFrame(frame)) {
            console.log('Iframe can not be accessed.', frame);
            return;
        }
        if(!frame.contentWindow.document.body){
            return;
        }
        if(!!frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector')){
            return;
        }
        insertDetector();
    });
    var offset = function () {
        return _detector.getBoundingClientRect().top;
    };
    frame._intPause = false;
    frame._int = setInterval(function(){
        if(!frame._intPause && frame.parentNode && frame.contentWindow && frame.contentWindow.document.body){
            var calc = offset() + _detector.offsetHeight;
            frame._currHeight = frame._currHeight || 0;
            if(calc && calc !== frame._currHeight ){
                frame._currHeight = calc;
                frame.style.height = Math.max(calc) + 'px';
                var scroll = Math.max(frame.contentWindow.document.documentElement.scrollHeight, frame.contentWindow.document.body.scrollHeight);
                if(scroll > frame._currHeight) {
                    frame._currHeight = scroll;
                    frame.style.height = scroll + 'px';
                }
                mw.$(frame).trigger('bodyResize');
            }
        } else if(!frame.parentElement){
            clearInterval(frame._int);
        }
        else {
            //clearInterval(frame._int);
        }
    }, 77);

};

})();

(() => {
/*!**************************************!*\
  !*** ../tools/widgets/responsive.js ***!
  \**************************************/
mw.responsive = {
    table: function (selector, options) {
        options = options || {};
        mw.$(selector).each(function () {
            var cls = 'responsive-table-' + mw.random();
            mw.tools.addClass(this, cls);
            var el = mw.$(this);
            el.wrap('<div class="mw-responsive-table-wrapper"></div>');
            if (options.minWidth) {
                el.css('minWidth', options.minWidth)
            }
            if (!el.hasClass('mw-mobile-table')) {
                el.addClass('mw-mobile-table');
            }
        });
    }
};

})();

(() => {
/*!**********************************!*\
  !*** ../tools/widgets/select.js ***!
  \**********************************/
/*
    options.data = [
        {
            title: string,
            value: any,
            icon?: string,
            selected?: boolean
        }
    ]

 */


mw.Select = function(options) {
    var defaults = {
        data: [],
        skin: 'default',
        multiple: false,
        autocomplete: false,
        mobileAutocomplete: false,
        showSelected: false,
        document: document,
        size: 'normal',
        color: 'default',
        dropMode: 'over', // 'over' | 'push'
        placeholder: mw.lang('Select'),
        tags: false, // only if multiple is set to true,
        ajaxMode: {
            paginationParam: 'page',
            searchParam: 'keyword',
            endpoint: null,
            method: 'get'
        }
    };
    options  = options || {};
    this.settings = $.extend({}, defaults, options);



    if(this.settings.ajaxMode && !this.settings.ajaxMode.endpoint){
        this.settings.ajaxMode = false;
    }

    this.$element = $(this.settings.element).eq(0);
    this.element = this.$element[0];
    if(!this.element) {
        return;
    }

    this._rootInputMode = this.element.nodeName === 'INPUT';

    if(this.element._mwSelect) {
        return this.element._mwSelect;
    }

    var scope = this;
    this.document = this.settings.document;

    this._value = null;


    this.getLabel = function(item) {
        return item.title || item.name || item.label || item.value;
    };

    this._loading = false;

    this._page = 1;

    this.nextPage = function () {
        this._page++;
    };

    this.page = function (state) {
        if (typeof state === 'undefined') {
            return this._page;
        }
        this._page = state;
    };

    this.loading = function (state) {
        if (typeof state === 'undefined') {
            return this._state;
        }
        this._state = state;
        mw.tools.classNamespaceDelete(this.root, 'mw-select-loading-');
        mw.tools.addClass(this.root, 'mw-select-loading-' + state);
    };

    this.xhr = null;

    this.ajaxFilter = function (val, callback) {
        if (this.xhr) {
            this.xhr.abort();
        }
        val = (val || '').trim().toLowerCase();
        var params = { };
        params[this.settings.ajaxMode.searchParam] = val;
        params = (this.settings.ajaxMode.endpoint.indexOf('?') === -1 ? '?' : '&' ) + $.param(params);
        this.xhr = $[this.settings.ajaxMode.method](this.settings.ajaxMode.endpoint + params, function (data) {
            callback.call(this, data);
            scope.xhr = null;
        });
    };

    this.filter = function (val) {
        val = (val || '').trim().toLowerCase();
        if (this.settings.ajaxMode) {
            this.loading(true);
            this.ajaxFilter(val, function (data) {
                scope.setData(data.data);
                if(data.data && data.data.length){
                    scope.open();
                } else {
                    scope.close();
                }
                scope.loading(false);
            });
        } else {
            var all = this.root.querySelectorAll('.mw-select-option'), i = 0;
            if (!val) {
                for( ; i< all.length; i++) {
                    all[i].style.display = '';
                }
            } else {
                for( ; i< all.length; i++) {
                    all[i].style.display = this.getLabel(all[i].$value).toLowerCase().indexOf(val) !== -1 ? '' : 'none';
                }
            }
        }
    };

    this.setPlaceholder = function (plval) {
        /*plval = plval || this.settings.placeholder;
        if(scope.settings.autocomplete){
            $('.mw-ui-invisible-field', this.root).attr('placeholder', plval);
        } else {
            $('.mw-ui-btn-content', this.root).html(plval);
        }*/
        return this.displayValue(plval)
    };
    this.displayValue = function(plval){
        if(!plval && !this.settings.multiple && this.value()) {
            plval = scope.getLabel(this.value());
        }
        plval = plval || this.settings.placeholder;
        if(!scope._displayValue) {
            scope._displayValue = scope.document.createElement('span');
            scope._displayValue.className = 'mw-select-display-value mw-ui-size-' + this.settings.size;
            $('.mw-select-value', this.root).append(scope._displayValue)
        }
        if(this._rootInputMode){
            scope._displayValue.innerHTML = '&nbsp';
            $('input.mw-ui-invisible-field', this.root).val(plval);

        } else {
            scope._displayValue.innerHTML = plval + this.__indicateNumber();

        }
    };

    this.__indicateNumber = function () {
        if(this.settings.multiple && this.value() && this.value().length){
            return "<span class='mw-select-indicate-number'>" + this.value().length + "</span>";
        } else {
            return "<span class='mw-select-indicate-number mw-select-indicate-number-empty'>" + 0 + "</span>";
        }
    };

    this.rend = {

        option: function(item){
            var oh = scope.document.createElement('label');
            oh.$value = item;
            oh.className = 'mw-select-option';
            if (scope.settings.multiple) {
                oh.className = 'mw-ui-check mw-select-option';
                oh.innerHTML =  '<input type="checkbox"><span></span><span>'+scope.getLabel(item)+'</span>';

                $('input', oh).on('change', function () {
                    this.checked ? scope.valueAdd(oh.$value) : scope.valueRemove(oh.$value)
                });
            } else {
                oh.innerHTML = scope.getLabel(item);
                oh.onclick = function () {
                    scope.value(oh.$value)
                };
            }

            return oh;
        },
        value: function() {
            var tag = 'span', cls = 'mw-ui-btn';
            if(scope.settings.autocomplete){
                tag = 'span';
                cls = 'mw-ui-field'
            }
            var oh = scope.document.createElement(tag);
            oh.className = cls + ' mw-ui-size-' + scope.settings.size + ' mw-ui-bg-' + scope.settings.color + ' mw-select-value';

            if(scope.settings.autocomplete){
                oh.innerHTML = '<input class="mw-ui-invisible-field mw-ui-field-' + scope.settings.size + '">';
            } else {
                oh.innerHTML = '<span class="mw-ui-btn-content"></span>';
            }

            if(scope.settings.autocomplete){
                $('input', oh)
                    .on('input focus', function () {
                        scope.filter(this.value);
                        if(scope._rootInputMode) {
                            scope.element.value = this.value;
                            $(scope.element).trigger('input change')
                        }
                    })
                    .on('focus', function () {
                        if(scope.settings.data && scope.settings.data.length) {
                            scope.open();
                        }
                    }).on('focus blur input', function () {
                    var hasVal = !!this.value.trim();
                    mw.tools[hasVal ? 'addClass' : 'removeClass'](scope.root, 'mw-select-has-value')
                });
            } else {
                oh.onclick = function () {
                    scope.toggle();
                };
            }
            return oh;
        },
        options: function(){
            scope.optionsHolder = scope.document.createElement('div');
            scope.holder = scope.document.createElement('div');
            scope.optionsHolder.className = 'mw-select-options';
            $.each(scope.settings.data, function(){
                scope.holder.appendChild(scope.rend.option(this))
            });
            scope.optionsHolder.appendChild(scope.holder);
            return scope.optionsHolder;
        },
        root: function () {
            scope.root = scope.document.createElement('div');
            scope.root.className = 'mw-select mw-select-dropmode-' + scope.settings.dropMode + ' mw-select-multiple-' + scope.settings.multiple;

            return scope.root;
        }
    };

    this.state = 'closed';

    this.open = function () {
        this.state = 'opened';
        mw.tools.addClass(scope.root, 'active');
        mw.Select._register.forEach(function (item) {
            if(item !== scope) {
                item.close()
            }
        });
    };

    this.close = function () {
        this.state = 'closed';
        mw.tools.removeClass(scope.root, 'active');
    };

    this.tags = function () {
        if(!this._tags) {
            if(this.settings.multiple && this.settings.tags) {
                var holder = scope.document.createElement('div');
                holder.className = 'mw-select-tags';
                this._tags = new mw.tags({element:holder, data:scope._value || [], color: this.settings.tagsColor, size: this.settings.tagsSize || 'small'})
                $(this.optionsHolder).prepend(holder);
                mw.$(this._tags).on('tagRemoved', function (e, tag) {
                    scope.valueRemove(tag)
                })
            }
        } else {
            this._tags.setData(scope._value)
        }
        this.displayValue()
    };


    this.toggle = function () {
        if (this.state === 'closed') {
            this.open();
        } else {
            this.close();
        }
    };


    this._valueGet = function (val) {
        if(typeof val === 'number') {
            val = this.settings.data.find(function (item) {
                return item.id === val;
            })
        }
        return val;
    };



    this.valueAdd = function(val){
        if (!val) return;
        val = this._valueGet(val);
        if (!val) return;
        if (!this._value) {
            this._value = []
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (!exists) {
            this._value.push(val);
            $(this.root.querySelectorAll('.mw-select-option')).each(function () {
                if(this.$value === val) {
                    this.querySelector('input').checked = true;
                }
            });
        }

        this.afterChange();
    };

    this.afterChange = function () {
        this.tags();
        this.displayValue();
        if($.isArray(this._value)) {
            $.each(this._value, function () {

            });
            $(this.root.querySelectorAll('.mw-select-option')).each(function () {
                if(scope._value.indexOf(this.$value) !== -1) {
                    mw.tools.addClass(this, 'active')
                }
                else {
                    mw.tools.removeClass(this, 'active')
                }
            });
        }
        $(this).trigger('change', [this._value]);
    };

    this.valueRemove = function(val) {
        if (!val) return;
        val = this._valueGet(val);
        if (!val) return;
        if (!this._value) {
            this._value = [];
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (exists) {
            this._value.splice(this._value.indexOf(exists), 1);
        }
        $(this.root.querySelectorAll('.mw-select-option')).each(function () {
            if(this.$value === val) {
                this.querySelector('input').checked = false;
            }
        });
        this.afterChange()
    };

    this._valueToggle = function(val){
        if (!val) return;
        if (!this._value) {
            this._value = [];
        }
        var exists = this._value.find(function (item) {
            return item.id === val.id;
        });
        if (exists) {
            this._value.splice(this._value.indexOf(exists), 1);
        } else {
            this._value.push(val);
        }
        this.afterChange();
    };

    this.value = function(val){
        if(!val) return this._value;
        val = this._valueGet(val);
        if (!val) return;
        if(this.settings.multiple){
            this._valueToggle(val)
        }
        else {
            this._value = val;
            $('.mw-ui-invisible-field', this.root).val(this.getLabel(val))
            this.close();
        }

        this.afterChange()
    };

    this.setData = function (data) {
        $(scope.holder).empty();
        scope.settings.data = data;
        $.each(scope.settings.data, function(){
            scope.holder.appendChild(scope.rend.option(this))
        });
        return scope.holder;
    };

    this.addData = function (data) {
        $.each(data, function(){
            scope.settings.data.push(this);
            scope.holder.appendChild(scope.rend.option(this));
        });
        return scope.holder;
    };

    this.build = function () {
        this.rend.root();
        this.root.appendChild(this.rend.value());
        this.root.appendChild(this.rend.options());
        if (this._rootInputMode) {
            this.element.type = 'hidden';
            this.$element.before(this.root);
        } else {
            this.$element.html(this.root);
        }
        this.setPlaceholder();
        mw.Select._register.push(this);
    };

    this.init = function () {
        this.build();
        this.element._mwSelect = this;
    };



    this.init();


};

mw.Select._register = [];


$(document).ready(function () {
    $(document).on('mousedown touchstart', function (e) {
        if(!mw.tools.firstParentOrCurrentWithClass(e.target, 'mw-select')){
            mw.Select._register.forEach(function (item) {
                item.close();
            });
        }
    });
});


mw.select = function(options) {
    return new mw.Select(options);
};

})();

(() => {
/*!***********************************!*\
  !*** ../tools/widgets/spinner.js ***!
  \***********************************/
mw.Spinner = function(options){
    if(!options || !options.element){
        return;
    }
    this.$element = $(options.element);
    if(!this.$element.length) return;
    this.element = this.$element[0];
    if(this.element._mwSpinner){
        return this.element._mwSpinner;
    }
    this.element._mwSpinner = this;
    this.options = options;

    this.options.size = this.options.size || 20;
    this.options.color = this.options.color || '#4592ff';
    this.options.insertMode = this.options.insertMode || 'append';

    this.color = function(val){
        if(!val) {
            return this.options.color;
        }
        this.options.color = val;
        this.$spinner.find('circle').css({
            stroke: this.options.color
        });
    };

    this.size = function(val){
        if(!val) {
            return this.options.size;
        }
        this.options.size = parseFloat(val);
        this.$spinner.css({
            width: this.options.size,
            height: this.options.size
        });
    };

    this.create = function(){
        this.$spinner = $('<div class="mw-spinner mw-spinner-mode-' + this.options.insertMode + '" style="display: none;"><svg viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg></div>');
        this.size(this.options.size);
        this.color(this.options.color);
        this.$element[this.options.insertMode](this.$spinner);
        this.show();
        return this;
    };

    this.show = function(){
        this.$spinner.show();
        this.$element.addClass('has-mw-spinner');
        if(this.options.decorate) {
            this.$element.addClass('mw-spinner-decorate');
        }
        return this;
    };

    this.hide = function(){
        this.$spinner.hide();
        this.$element.removeClass('has-mw-spinner');
        if(this.options.decorate) {
            this.$element.removeClass('mw-spinner-decorate');
        }
        return this;
    };

    this.remove = function(){
        this.hide();
        this.$spinner.remove();
        delete this.element._mwSpinner;
    };

    this.create().show();

};

mw.spinner = function(options){
    return new mw.Spinner(options);
};

})();

(() => {
/*!***********************************!*\
  !*** ../tools/widgets/storage.js ***!
  \***********************************/
mw.storage = {
    init: function () {
        if (window.location.href.indexOf('data:') === 0 || !('localStorage' in mww) || /* IE Security configurations */ typeof mww['localStorage'] === 'undefined') return false;
        var lsmw = localStorage.getItem("mw");
        if (typeof lsmw === 'undefined' || lsmw === null) {
            lsmw = localStorage.setItem("mw", "{}");
        }
        this.change("INIT");
        return lsmw;
    },
    set: function (key, val) {
        if (!('localStorage' in mww)) return false;
        var curr = JSON.parse(localStorage.getItem("mw"));
        curr[key] = val;
        var a = localStorage.setItem("mw", JSON.stringify(curr));
        mw.storage.change("CALL", key, val);
        return a;
    },
    get: function (key) {
        if (!('localStorage' in mww)) return false;
        var curr = JSON.parse(localStorage.getItem("mw"));
        return curr[key];
    },
    _keys: {},
    change: function (key, callback, other) {
        if (!('localStorage' in mww)) return false;
        if (key === 'INIT' && 'addEventListener' in document) {
            addEventListener('storage', function (e) {
                if (e.key === 'mw') {
                    var _new = JSON.parse(e.newValue || {});
                    var _old = JSON.parse(e.oldValue || {});
                    var diff = mw.tools.getDiff(_new, _old);
                    for (var t in diff) {
                        if (t in mw.storage._keys) {
                            var i = 0, l = mw.storage._keys[t].length;
                            for (; i < l; i++) {
                                mw.storage._keys[t][i].call(diff[t]);
                            }
                        }
                    }
                }
            }, false);
        }
        else if (key === 'CALL') {
            if (!document.isHidden() && typeof mw.storage._keys[callback] !== 'undefined') {
                var i = 0, l = mw.storage._keys[callback].length;
                for (; i < l; i++) {
                    mw.storage._keys[callback][i].call(other);
                }
            }
        }
        else {
            if (key in mw.storage._keys) {
                mw.storage._keys[key].push(callback);
            }
            else {
                mw.storage._keys[key] = [callback];
            }
        }
    }
};
mw.storage.init();

})();

(() => {
/*!***************************************!*\
  !*** ../tools/widgets/uiaccordion.js ***!
  \***************************************/
mw.uiAccordion = function (options) {
    if (!options) return;
    options.element = options.element || '.mw-accordion';

    var scope = this;



    this.getContents = function () {
        this.contents = this.root.children(this.options.contentSelector);
        if (!this.contents.length) {
            this.contents = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.contentSelector)[0];
                if (title) {
                    scope.contents.push(title)
                }
            });
        }
    }
    this.getTitles = function () {
        this.titles = this.root.children(this.options.titleSelector);
        if (!this.titles.length) {
            this.titles = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.titleSelector)[0];
                if (title) {
                    scope.titles.push(title)
                }
            });
        }
    };

    this.prepare = function (options) {
        var defaults = {
            multiple: false,
            itemSelector: ".mw-accordion-item,mw-accordion-item",
            titleSelector: ".mw-accordion-title,mw-accordion-title",
            contentSelector: ".mw-accordion-content,mw-accordion-content",
            openFirst: true,
            toggle: true
        };
        this.options = $.extend({}, defaults, options);

        this.root = mw.$(this.options.element).not('.mw-accordion-ready').eq(0);
        if (!this.root.length) return;
        this.root.addClass('mw-accordion-ready');
        this.root[0].uiAccordion = this;
        this.getTitles();
        this.getContents();

    };

    this.getItem = function (q) {
        var item;
        if (typeof q === 'number') {
            item = this.contents.eq(q)
        }
        else {
            item = mw.$(q);
        }
        return item;
    };

    this.set = function (index) {
        var item = this.getItem(index);
        if (!this.options.multiple) {
            this.contents.not(item)
                .slideUp()
                .removeClass('active')
                .prev()
                .removeClass('active')
                .parents('.mw-accordion-item').eq(0)
                .removeClass('active');
        }
        item.stop()
            .slideDown()
            .addClass('active')
            .prev()
            .addClass('active')
            .parents('.mw-accordion-item').eq(0)
            .addClass('active');
        mw.$(this).trigger('accordionSet', [item]);
    };

    this.unset = function (index) {
        if (typeof index === 'undefined') return;
        var item = this.getItem(index);
        item.stop()
            .slideUp()
            .removeClass('active')
            .prev()
            .removeClass('active')
            .parents('.mw-accordion-item').eq(0)
            .removeClass('active');
        ;
        mw.$(this).trigger('accordionUnset', [item]);
    }

    this.toggle = function (index) {
        var item = this.getItem(index);
        if (item.hasClass('active')) {
            if (this.options.toggle) {
                this.unset(item)
            }
        }
        else {
            this.set(item)
        }
    }

    this.init = function (options) {
        this.prepare(options);
        if(typeof(this.contents) !== 'undefined'){
            this.contents.hide()
        }

        if (this.options.openFirst) {
            if(typeof(this.contents) !== 'undefined'){
                this.contents.eq(0).show().addClass('active')
            }
            if(typeof(this.titles) !== 'undefined'){
                this.titles.eq(0).addClass('active').parent('.mw-accordion-item').addClass('active');
            }
        }
        if(typeof(this.titles) !== 'undefined') {
            this.titles.on('click', function () {
                scope.toggle(scope.titles.index(this));
            });
        }
    }

    this.init(options);

};


mw.tabAccordion = function (options, accordion) {
    if (!options) return;
    var scope = this;
    this.options = options;

    this.options.breakPoint = this.options.breakPoint || 800;
    this.options.activeClass = this.options.activeClass || 'active-info';


    this.buildAccordion = function () {
        this.accordion = accordion || new mw.uiAccordion(this.options);
    }

    this.breakPoint = function () {
        if (matchMedia("(max-width: " + this.options.breakPoint + "px)").matches) {
            mw.$(this.nav).hide();
            mw.$(this.accordion.titles).show();
        }
        else {
            mw.$(this.nav).show();
            mw.$(this.accordion.titles).hide();
        }
    }

    this.createTabButton = function (content, index) {
        this.buttons = this.buttons || mw.$();
        var btn = document.createElement('span');
        this.buttons.push(btn)
        var size = (this.options.tabsSize ? ' mw-ui-btn-' + this.options.tabsSize : '');
        var color = (this.options.tabsColor ? ' mw-ui-btn-' + this.options.tabsColor : '');
        var active = (index === 0 ? (' ' + this.options.activeClass) : '');
        btn.className = 'mw-ui-btn' + size + color + active;
        btn.innerHTML = content;
        btn.onclick = function () {
            scope.buttons.removeClass(scope.options.activeClass).eq(index).addClass(scope.options.activeClass);
            scope.accordion.set(index);
        }
        return btn;
    }

    this.createTabs = function () {
        this.nav = document.createElement('div');
        this.nav.className = 'mw-ui-btn-nav mw-ui-btn-nav-tabs';
        mw.$(this.accordion.titles)
            .each(function (i) {
                scope.nav.appendChild(scope.createTabButton($(this).html(), i))
            })
            .hide();
        mw.$(this.accordion.root).before(this.nav)
    }

    this.init = function () {
        this.buildAccordion();
        this.createTabs();
        this.breakPoint();
        mw.$(window).on('load resize orientationchange', function () {
            scope.breakPoint();
        });
    };

    this.init();
};

})();

(() => {
/*!**********************************!*\
  !*** ../widgets/autocomplete.js ***!
  \**********************************/
mw.autoComplete = function(options){
    var scope = this;
    this.prepare = function(options){
        options = options || {};
        if(!options.data && !options.ajaxConfig) return;
        var defaults = {
            size:'normal',
            multiple:false,
            map: { title:'title', value:'id' },
            titleDecorator: function (title, data) {
                return title;
            }
        };
        this.options = $.extend({}, defaults, options);
        this.options.element = mw.$(this.options.element)[0];
        if(!this.options.element){
            return;
        }
        this.element = this.options.element;
        this.data = this.options.data;
        this.searchTime = null;
        this.searchTimeout = this.options.data ? 0 : 500;
        this.results = [];
        this.map = this.options.map;
        this.selected = this.options.selected || [];
    };

    this.createValueHolder = function(){
        this.valueHolder = document.createElement('div');
        this.valueHolder.className = 'mw-autocomplete-value';
        return this.valueHolder;
    };
    this.createListHolder = function(){
        this.listHolder = document.createElement('ul');
        this.listHolder.className = 'mw-ui-box mw-ui-navigation mw-autocomplete-list';
        this.listHolder.style.display = 'none';
        return this.listHolder;
    };

    this.createWrapper = function(){
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'mw-ui-field w100 mw-autocomplete mw-autocomplete-multiple-' + this.options.multiple;
        return this.wrapper;
    };

    this.createField = function(){
        this.inputField = document.createElement('input');
        this.inputField.className = 'mw-ui-invisible-field mw-autocomplete-field mw-ui-field-' + this.options.size;
        if(this.options.placeholder){
            this.inputField.placeholder = this.options.placeholder;
        }
        mw.$(this.inputField).on('input focus', function(e){
            var val = e.type === 'focus' ? '' : this.value;
            scope.search(val);
        });
        return this.inputField;
    };

    this.buildUI = function(){
        this.createWrapper();
        this.wrapper.appendChild(this.createValueHolder());
        this.wrapper.appendChild(this.createField());
        this.wrapper.appendChild(this.createListHolder());
        this.element.appendChild(this.wrapper);
    };

    this.createListItem = function(data){
        var li = document.createElement('li');
        li.value = this.dataValue(data);
        var img = this.dataImage(data);

        mw.$(li)
        .append( '<a href="javascript:;">'+this.dataTitle(data)+'</a>' )
        .on('click', function(){
            scope.select(data);
        });
        if(img){
            mw.$('a',li).prepend(img);
        }
        return li;
    };

    this.uniqueValue = function(){
        var uniqueIds = [], final = [];
        this.selected.forEach(function(item){
            var val = scope.dataValue(item);
            if(uniqueIds.indexOf(val) === -1){
                uniqueIds.push(val);
                final.push(item);
            }
        });
        this.selected = final;
    };

    this.select = function(item){
        if(this.options.multiple){
            this.selected.push(item);
        }
        else{
            this.selected = [item];
        }
        this.rendSelected();
        if(!this.options.multiple){
            this.listHolder.style.display = 'none';
        }
        mw.$(this).trigger('change', [this.selected]);
    };

    this.rendSingle = function(){
        var item = this.selected[0];
        this.inputField.value = item ? item[this.map.title] : '';
        this.valueHolder.innerHTML = '';
        var img = this.dataImage(item);
        if(img){
            this.valueHolder.appendChild(img);
        }

    };

    this.rendSelected = function(){
        if(this.options.multiple){
            this.uniqueValue();
            this.chips.setData(this.selected);
        }
        else{
            this.rendSingle();
        }
    };

    this.rendResults = function(){
        mw.$(this.listHolder).empty().show();
        $.each(this.results, function(){
            scope.listHolder.appendChild(scope.createListItem(this));
        });
    };

    this.dataValue = function(data){
        if(!data) return;
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value];
        }
    };
    this.dataImage = function(data){
        if(!data) return;
        if(data.picture){
            data.image = data.picture;
        }
        if(data.image){
            var img = document.createElement('span');
            img.className = 'mw-autocomplete-img';
            img.style.backgroundImage = 'url(' + data.image + ')';
            return img;
        }
    };
    this.dataTitle = function(data){
        if (!data) return;
        var title;
        if (typeof data === 'string') {
            title = data;
        }
        else {
            title = data[this.map.title];
        }

        return this.options.titleDecorator(title, data);
    };

    this.searchRemote = function(val){
        var config = mw.tools.cloneObject(this.options.ajaxConfig);

        if(config.data){
            if(typeof config.data === 'string'){
                config.data = config.data.replace('${val}',val);
            }
            else{
               $.each(config.data, function(key,value){

                    if(value.indexOf && value.indexOf('${val}') !==-1 ){
                        config.data[key] = value.replace('${val}', val);
                    }
               });
            }
        }
        if(config.url){
            config.url = config.url.replace('${val}',val);
        }
        var xhr = $.ajax(config);
        xhr.done(function(data){
            if(data.data){
                scope.data = data.data;
            }
            else{
               scope.data = data;
            }
            scope.results = scope.data;
            scope.rendResults();
        })
        .always(function(){
            scope.searching = false;
        });
    };

    this.searchLocal = function(val){

        this.results = [];
        var toSearch;
        $.each(this.data, function(){
           if(typeof this === 'string'){
                toSearch = this.toLowerCase();
           }
           else{
               toSearch = this[scope.map.title].toLowerCase();
           }
           if(toSearch.indexOf(val) !== -1){
            scope.results.push(this);
           }
        });
       this.rendResults();
       scope.searching = false;
    };

    this.search = function(val){
        if(scope.searching) return;
        val = val || '';
        val = val.trim().toLowerCase();

        if(this.options.data){
            this.searchLocal(val);
        }
        else{
            clearTimeout(this.searchTime);
            setTimeout(function(){
                scope.searching = true;
                scope.searchRemote(val);
            }, this.searchTimeout);
        }
    };

    this.init = function(){
        this.prepare(options);
        this.buildUI();
        if(this.options.multiple){
            this.chips = new mw.chips({
                element:this.valueHolder,
                data:[]
            });
        }
        this.rendSelected();
        this.handleEvents();
    };

    this.handleEvents = function(){
        mw.$(document.body).on('click', function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-autocomplete')){
                scope.listHolder.style.display = 'none';
            }
        });
    };


    this.init();

};

})();

(() => {
/*!********************************!*\
  !*** ../widgets/block-edit.js ***!
  \********************************/
mw.blockEdit = function (options) {
    options = options || {};
    var defaults = {
        element: document.body,
        mode: 'wrap' // wrap | in
    };

    var scope = this;

    var settings = $.extend({}, defaults, options);

    settings.$element = mw.$(settings.element);
    settings.element = settings.$element[0];
    this.settings = settings;
    if(!settings.element) {
        return;
    }

    this.set = function(mode){
        if(mode === 'edit'){
            this.$slider.stop().animate({
                left: '-100%',
                height: this.$editSlide.outerHeight()
            }, function(){
                scope.$holder.addClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        } else {
            this.$slider.stop().animate({
                left: '0',
                height: this.$mainSlide.outerHeight()
            }, function(){
                scope.unEditByElement();
                scope.$holder.removeClass('mw-block-edit-editing');
                scope.$slider.height('auto');
            });
        }
    };

    this.close = function(content){
        this.set();
        $(this).trigger('CloseEdit');
    };
    this.edit = function(content){
        if(content){
            this.$editSlide.empty().append(content);
        }
        this.set('edit');
        $(this).trigger('Edit');
    };

    this._editByElement = null;
    this._editByElementTemp = null;

    this.unEditByElement = function(){
        if(this._editByElement){
            $(this._editByElementTemp).replaceWith(this._editByElement);
            $(this._editByElement).hide()
        }
        this._editByElement = null;
        this._editByElementTemp = null;
    };

    this.editByElement = function(el){
        if(!el){
            return;
        }
        this.unEditByElement();
        this._editByElementTemp = document.createElement('mw-temp');
        this._editByElement = el;
        $(el).before(this._editByElementTemp);
        this.editSlide.appendChild(el);
        $(el).show()
    };
    this.moduleEdit = function(module, params){
        mw.tools.loading(this.holder, 90);
        mw.load_module(module, this.editSlide, function(){
            scope.edit();
            mw.tools.loading(scope.holder, false);
        }, params);
    };

    this.build = function(){
        this.holder = document.createElement('div');
        this.$holder = $(this.holder);
        this.holder.className = 'mw-block-edit-holder';
        this.holder._blockEdit = this;
        this.slider = document.createElement('div');
        this.$slider = $(this.slider);
        this.slider.className = 'mw-block-edit-slider';
        this.mainSlide = document.createElement('div');
        this.$mainSlide = $(this.mainSlide);
        this.editSlide = document.createElement('div');
        this.$editSlide = $(this.editSlide);
        this.mainSlide.className = 'mw-block-edit-main-slide';
        this.editSlide.className = 'mw-block-edit-edit-slide';

        this.slider.appendChild(this.mainSlide);
        this.slider.appendChild(this.editSlide);
        this.holder.appendChild(this.slider);
        //this.settings.$element.before(this.holder);
        // this.mainSlide.appendChild(settings.element);

    };

    this.initMode = function(){
        if(this.settings.mode === 'wrap') {
            this.settings.$element.after(this.holder);
            this.$mainSlide.append(this.settings.$element);
        } else if(this.settings.mode === 'in') {
            this.settings.$element.wrapInner(this.holder);
        }
    };

    this.init = function () {
        this.build();
        this.initMode();
    };

    this.init();

};

mw.blockEdit.get = function(target){
    target = target || '.mw-block-edit-holder';
    target = mw.$(target);
    if(!target[0]) return;
    if(target.hasClass('mw-block-edit-holder')){
        return target[0]._blockEdit;
    } else {
        var node = mw.tools.firstParentWithClass(target[0], 'mw-block-edit-holder') || target[0].querySelector('.mw-block-edit-holder');
        if(node){
            return node._blockEdit;
        }
    }
};

$.fn.mwBlockEdit = function (options) {
    options = options || {};
    return this.each(function(){
        this.mwBlockEdit =  new mw.blockEdit($.extend({}, options, {element: this }));
    });
};

mw.registerComponent('block-edit', function(el){
    var options = mw.components._options(el);
    mw.$(el).mwBlockEdit(options);
});
mw.registerComponent('block-edit-closeButton', function(el){
    mw.$(el).on('click', function(){
        mw.blockEdit.get(this).close();
    });
});
mw.registerComponent('block-edit-editButton', function(el){
    mw.$(el).on('click', function(){
        var options = mw.components._options(this);
        if(options.module){
            mw.blockEdit.get(options.for || this).moduleEdit(options.module);
            return;
        } else if(options.element){
            var el = mw.$(options.element)[0];
            if(el){
                mw.blockEdit.get(options.for || this).editByElement(el);
            }
        }
        mw.blockEdit.get(this).edit();
    });
});

})();

(() => {
/*!*********************************!*\
  !*** ../widgets/control_box.js ***!
  \*********************************/
mw.controlBox = function(options){
    var scope = this;
    this.options = options;
    this.defaults = {
        position:'bottom',
        content:'',
        skin:'default',
        id:this.options.id || 'mw-control-box-'+mw.random(),
        closeButton: true
    };
    this.id = this.options.id;
    this.settings = $.extend({}, this.defaults, this.options);
    this.active = false;

    this.build = function(){
        this.box = document.createElement('div');
        this.box.className = 'mw-control-box mw-control-box-' + this.settings.position + ' mw-control-box-' + this.settings.skin;
        this.box.id = this.id;
        this.boxContent = document.createElement('div');
        this.boxContent.className = 'mw-control-boxcontent';
        this.box.appendChild(this.boxContent);
        this.createCloseButton();
        document.body.appendChild(this.box);
    };

    this.createCloseButton = function () {
        if(!this.options.closeButton) return;
        this.closeButton = document.createElement('span');
        this.closeButton.className = 'mw-control-boxclose';
        this.box.appendChild(this.closeButton);
        this.closeButton.onclick = function(){
            scope.hide();
        };
    };

    this.setContentByUrl = function(){
        var cont = this.settings.content.trim();
        return $.get(cont, function(data){
            scope.boxContent.innerHTML = data;
            scope.settings.content = data;
        });
    };
    this.setContent = function(c){
        var cont = c||this.settings.content.trim();
        this.settings.content = cont;
        if(cont.indexOf('http://') === 0 || cont.indexOf('https://') === 0){
            return this.setContentByUrl()
        }
        this.boxContent.innerHTML = cont;
    };

    this.show = function(){
        this.active = true;
        mw.$(this.box).addClass('active') ;
        mw.$(this).trigger('ControlBoxShow')
    };

    this.init = function(){
        this.build();
        this.setContent();
    };
    this.hide = function(){
        this.active = false;
        mw.$(this.box).removeClass('active');
        mw.$(this).trigger('ControlBoxHide');
    };


    this.toggle = function(){
        this[this.active?'hide':'show']();
    };
    this.init();
};

})();

(() => {
/*!***********************************!*\
  !*** ../widgets/form-controls.js ***!
  \***********************************/



mw.IconClassResolver = function ($for) {
    if (!$for) {
        return '';
    }
    switch ($for) {
        case 'shop': $for = 'mdi mdi-shopping'; break;
        case 'website': $for = 'mdi mdi-earth'; break;
        case 'module': $for = 'mdi mdi-view-grid-plus'; break;
        case 'marketplace': $for = 'mdi mdi-fruit-cherries'; break;
        case 'users': $for = 'mdi mdi-account-multiple'; break;
        case 'post': $for = 'mdi mdi-text'; break;
        case 'page': $for = 'mdi mdi-shopping'; break;
        case 'static': $for = 'mdi mdi-shopping'; break;
        case 'category': $for = 'mdi mdi-folder'; break;
        case 'product': $for = 'mdi mdi-shopping'; break;

        default: $for = '';
    }
    return $for;
};

mw.controlFields = {
    __id: new Date().getTime(),
    _id: function () {
        this.__id++;
        return 'le-' + this.__id;
    },
    _label: function (conf) {
        var id = conf.id || this._id();
        var label = document.createElement('label');
        label.className = conf.className || 'mw-ui-label';
        label.innerHTML = conf.label || conf.content || '';
        label.htmlFor = id;
        return label;
    },
    _button: function (conf){
        var id = conf.id || this._id();
        var button = document.createElement('button');
        button.type = conf.type || 'button';
        button.className = 'mw-ui-btn btn-' + conf.size + ' btn-' + conf.color;
        button.innerHTML = (conf.label || conf.content);
        return button;
    },
    _wrap: function () {
        var el =  document.createElement('div');
        el.className = 'mw-ui-field-holder';
        [].forEach.call(arguments, function (content) {
            if (typeof content === 'string') {
                el.innerHTML += content;
            } else {
                el.append(content);
            }
        });
        return el;
        // return '<div class="form-group">' + content + '</div>';
    },
    _description: function (conf) {
        return conf.description ? ('<small class="text-muted d-block mb-2">' + conf.description + '</small>') : '';
    },
    field: function (conf) {
        conf = conf || {};
        var placeholder = conf.placeholder ? ('placeholder="' + conf.placeholder + '"') : '';
        var id = (conf.id || this._id());
        id =  (' id="' + id + '" ');
        var name = conf.name ? ('name="' + conf.name + '"') : '';
        conf.type = conf.type || 'text';
        var required = conf.required ? ('required') : '';

        return this._wrap(
            this._label(conf),
            this._description(conf),
            '<input type="'+conf.type+'" '+placeholder + '  ' + id + ' ' + name + ' ' + required + ' class="mw-ui-field w100">'
        );
    },
    checkbox: function (conf) {
        conf = conf || {};
        conf.className = conf.className || 'custom-control-label';
        var id = (conf.id || this._id());
        conf.id = id;
        id =  (' id="' + id + '" ');
        var name = conf.name ? ('name="' + conf.name + '"') : '';
        var required = conf.required ? ('required') : '';
        return  this._wrap(
            '<label class="mw-ui-check">' +
            '<input type="checkbox" ' + id + ' ' + name + ' ' + required + '>' +
            '<span></span><span>' + (conf.label || conf.content || '') + '</span>' +
            '</label>');
    },
    radio: function (conf) {
        conf = conf || {};
        var id = (conf.id || this._id());
        id =  (' id="' + id + '" ');
        var value =  (' value="' + conf.value + '" ');
        var name = conf.name ? ('name="' + conf.name + '"') : '';
        var required = conf.required ? ('required') : '';
        return  this._wrap(
            '<label class="mw-ui-check">' +
            '<input type="radio" ' + id + ' ' + name + ' ' + required + ' ' + value + '>' +
                '<span></span><span>' + (conf.label || conf.content || '') + '</span>' +
            '</label>');
    },
    select: function (conf) {
        conf = conf || {};
        var id = (conf.id || this._id());
        id =  (' id="' + id + '" ');
        var name = conf.name ? ('name="' + conf.name + '"') : '';
        var required = conf.required ? ('required') : '';
        var multiple = conf.multiple ? ('multiple') : '';

        var options = (conf.options || []).map(function (item){
            return '<option value="'+ item.value +'">'+(item.title||item.name||item.label||item.value)+'</option>';
        }).join('');

        return  this._wrap(
            this._label(conf) +
            '<select class="selectpicker" ' + multiple + '  ' + id + ' ' + name + ' ' + required + '>' +
            options +
            '</select>' );
    }
};

mw.emitter = {
    _events: {},
    _onNative: function (node, type, callback) {
        type.trim().split(' ').forEach(function (ev) {
            node.addEventListener(ev, callback);
        });
    },
    on: function (event, callback, c) {
        if(!event) return;
        if(Array.isArray(event)) {
            for(var i = 0; i < event.length; i++) {
                this.on(event[i], callback, c);
            }
            return;
        }
        if(event.nodeName) {
            return this._onNative(event, callback, c);
        }
        if (!this._events[event]){
            this._events[event] = [];
        }
        this._events[event].push(callback);
    },
    dispatch: function(event, data) {
        if (this._events[event]) {
            this._events[event].forEach(function(handler) {
                handler(data);
            });
        }
    }
};

(function(){
    var UIFormControllers = {
        _title: function (conf, root) {
            var title = mw.element({
                tag: 'h5',
                props: {
                    className: 'mw-ui-form-controller-title',
                    innerHTML: '<strong>' + conf.title + '</strong>'
                }
            });
            mw.element(root).append(title);
        },
        footer: function () {
            var data = {};
            data.ok =  mw.controlFields._button({content: mw.lang('OK'), color: 'primary'});
            data.cancel =  mw.controlFields._button({content: mw.lang('Cancel')});
            data.root = mw.controlFields._wrap(data.cancel, data.ok);
            data.root.className = 'mw-ui-form-controllers-footer';
            return data;
        },
        title: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                icon: 'mdi mdi-view-compact-outline',
                // icon: 'mdi mdi-format-page-break',
                title: 'Page title'
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
            this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.link === true) options.link = defaults.link;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';

            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }

            UIFormControllers._title(this.settings, root)


            var count = 0;
            var html = [];
            this.url = '';
            var available_elements = document.createElement('div');
            available_elements.className = 'mw-ui-box mw-ui-box-content';
            var rname = mw.controlFields._id();
            mw.top().$("h1,h12,h3,h4,h5,h6", mw.top().win.document.body).each(function () {
                if(!!this.id || mw.tools.isEditable(this)){
                    if(!this.id) {
                        this.id = mw.id('mw-title-element-');
                    }
                    count++;
                    html.push({id: this.id, text: this.textContent});
                    var li = mw.controlFields.radio({
                        label:  '<strong>' + this.nodeName + '</strong> - ' + this.textContent,
                        name: rname,
                        id: mw.controlFields._id(),
                        value: '#' + this.id
                    });
                    mw.element(available_elements).append(li);
                    li.querySelector('input').oninput = function () {
                        scope.url = this.value;
                        scope.valid();
                    };
                }

            });

            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(available_elements);


            var textField = holder.querySelector('[name="text"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                if(!this.url) {
                    return false;
                }

                return true;
            };

            var footer = UIFormControllers.footer();

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
            };

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                val.url = this.url
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };


            mw.emitter.on([textField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },
        layout: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                icon: 'mdi mdi-view-compact-outline',
                // icon: 'mdi mdi-format-page-break',
                title: 'Page block'
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
             this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.link === true) options.link = defaults.link;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';

            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
            UIFormControllers._title(this.settings, root)

            var layoutsData = [];
            var layouts = mw.top().$('.module[data-type="layouts"]');
            layouts.each(function () {
                layoutsData.push({
                    name: this.getAttribute('template').split('.')[0],
                    element: this,
                    id: this.id
                });
            });
            var list = $('<div />');
            this.link = '';
            var radioName = mw.id();
            $.each(layoutsData, function(){
                var li = mw.controlFields.radio({
                    label: this.name,
                    name: radioName,
                    id: mw.controlFields._id(),
                    value: this.id
                });
                var el = this.element;
                $(li).find('input').on('click', function(){
                    mw.top().tools.scrollTo(el);
                    scope.link = mw.top().win.location.href.split('#')[0] + '#mw@' + el.id;
                    console.log(scope.link)
                    scope.valid();
                });
                list.append(li);
            });
            if(layoutsData.length > 0){
                $('.page-layout-btn').show();
            }

            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(list[0]);


            var textField = holder.querySelector('[name="text"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                } else if(!this.link) {
                    return false;
                }

                return true;
            };

            var footer = UIFormControllers.footer();

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
              };

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                  return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };


            mw.emitter.on([textField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                 scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },
        email: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                link: {
                    label: mw.lang('Email'),
                    description: mw.lang('Type email address in the field'),
                    placeholder: "hello@example.com",
                },
                target: {
                    label: mw.lang('Open the link in a new window')
                },
                icon: 'mdi mdi-email-outline',
                title: 'Email'
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
            this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.link === true) options.link = defaults.link;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';
            UIFormControllers._title(this.settings, root)
            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
            if (options.link) {
                _linkUrl = mw.controlFields.field({
                    label: options.link.label,
                    description: options.link.description,
                    placeholder: options.link.placeholder,
                    name: 'url'
                });
            }

            if (options.target) {
                _target = mw.controlFields.checkbox({
                    label: options.target.label,
                    name: 'target'
                });
            }


            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(_linkUrl);
            holder.append(_target);


            var textField = holder.querySelector('[name="text"]');
            var urlField = holder.querySelector('[name="url"]');
            var targetField = holder.querySelector('[name="target"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                if(urlField && !urlField.value) {
                    return false;
                }

                return urlField.validity;
            };

            var footer = UIFormControllers.footer();

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
                if(urlField) urlField.value = (val.url || '');
                if(targetField)  targetField.checked = val.target;
            };

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                if(urlField) val.url = 'mailto:' + urlField.value;
                if(targetField) val.target = targetField.checked;
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };


            mw.emitter.on([textField, urlField, targetField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },

        post: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                target: {
                    label: mw.lang('Open the link in a new window')
                },
                url: {
                    label: mw.lang('Search for content')
                },
                icon: 'mdi mdi-format-list-bulleted-square',
                title: 'Post/category',
                dataUrl: function () {
                    try {
                        return mw.settings.site_url + "api/get_content_admin";
                    } catch (e) {
                        return null;
                    }
                }
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
            this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';
            UIFormControllers._title(this.settings, root);
            var treeEl = document.createElement('div');
            treeEl.className = 'form-group mw-link-editor-posts-search';

            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
            var url =  this.settings.dataUrl;
            url = typeof url === 'function' ? url() : url;

            this.autoComplete = new mw.autoComplete({
                element: treeEl,
                titleDecorator: function (title, data) {
                    var type = data.subtype === 'static' ? 'page' : data.subtype;
                    return '<span class=" tip '+mw.IconClassResolver(data.subtype)+'" data-tip="' + type + '"></span>' + title;
                },
                ajaxConfig: {
                    method: 'post',
                    url: url,
                    data: {
                        limit: '5',
                        keyword: '${val}',
                        order_by: 'updated_at desc',
                        search_in_fields: 'title',
                    }
                }
            });


            var label = mw.controlFields._label({
                content: options.url.label
            });

            setTimeout(function (){
                mw.element(treeEl).before(label);
            }, 10)

            if (options.target) {
                _target = mw.controlFields.checkbox({
                    label: options.target.label,
                    name: 'target'
                });
            }


            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(treeEl);
            holder.append(_target);


            var textField = holder.querySelector('[name="text"]');
            var targetField = holder.querySelector('[name="target"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                return true;
            };

            var footer = UIFormControllers.footer();

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                var getSelected = this.autoComplete.selected[0];
                val.url = getSelected ? getSelected.url : '';
                val.data = getSelected;
                if(targetField) val.target = targetField.checked;
                return val;
            };

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
                if(targetField) targetField.checked = !!val.target;
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };

            $(this.autoComplete).on("change", function(e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });
            mw.emitter.on([textField, targetField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },
        page: function (options) {
            var scope = this;
             var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                target: {
                    label: mw.lang('Open the link in a new window')
                },
                icon: 'mdi mdi-file-link-outline',
                title: 'My website',
                dataUrl: function () {
                    try {
                        return mw.top().settings.api_url + 'content/get_admin_js_tree_json';
                    } catch (e) {
                        return null;
                    }
                }
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
            this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';
            UIFormControllers._title(this.settings, root)
            var treeEl = document.createElement('div');
            treeEl.className = 'form-group';
            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
             var url = typeof this.settings.dataUrl === 'function' ? this.settings.dataUrl() : this.settings.dataUrl;
            mw.require('tree.js')
            $.getJSON(url, function (res){
                scope.tree = new mw.tree({
                    data: res,
                    element: treeEl,
                    sortable: false,
                    selectable: true,
                    singleSelect: true
                });
                scope.tree.on("selectionChange", function(selection){
                    if (textField && selection && selection[0]) {
                        textField.value = selection[0].title;
                    }
                    if(scope.valid()) {
                        scope._onChange.forEach(function (f){
                            f(scope.getValue());
                        });


                    }
                });
            });

            if (options.target) {
                _target = mw.controlFields.checkbox({
                    label: options.target.label,
                    name: 'target'
                });
            }


            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(treeEl);
            holder.append(_target);


            var textField = holder.querySelector('[name="text"]');
            var targetField = holder.querySelector('[name="target"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                return true;
            };

            var footer = UIFormControllers.footer();

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                var getSelected = this.tree.getSelected()[0];
                val.url = getSelected ? getSelected.url : '';
                val.data = getSelected;
                if(targetField) val.target = targetField.checked;
                return val;
            };

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
                if(targetField) targetField.checked = val.target;
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };

            mw.emitter.on([textField, targetField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },
        file: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },

                target: {
                    label: mw.lang('Open the link in a new window')
                },
                icon: 'mdi mdi-paperclip',
                title: 'File',
                dataUrl: function () {
                    try {
                        return mw.settings.api_url + 'content/get_admin_js_tree_json';
                    } catch (e) {
                        return null;
                    }
                }
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
             this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';
            UIFormControllers._title(this.settings, root)
            var treeEl = document.createElement('div');
            treeEl.className = 'form-group';
            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
             var url =  this.settings.dataUrl;
            url = typeof url === 'function' ? url() : url;
             scope.filepicker = new mw.filePicker({

                element: treeEl,
                nav: 'tabs',
                label: false
            });
            treeEl.append(mw.controlFields._label({content: 'Select file'}))
            if (options.target) {
                _target = mw.controlFields.checkbox({
                    label: options.target.label,
                    name: 'target'
                });
            }


            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(treeEl);
            holder.append(_target);


            var textField = holder.querySelector('[name="text"]');
            var targetField = holder.querySelector('[name="target"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                return !!this.filepicker.getValue();
            };

            var footer = UIFormControllers.footer();

            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                var url = this.filepicker.getValue();
                val.url = typeof url === 'object' ? (url.src || url.url) : url;
                val.data = (url.src || url.url || null);
                if(targetField) val.target = targetField.checked;
                return val;
            };

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
                if(targetField) targetField.checked = !!val.target;
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };



            $(this.filepicker).on('Result', function (e, res) {
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });
            mw.emitter.on([textField, targetField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        },

        url: function (options) {
            var scope = this;
            var defaults = {
                text: {
                    label: mw.lang('Link text'),
                    description: mw.lang('Selected text for the link.'),
                },
                link: {
                    label: mw.lang('Website URL'),
                    description: mw.lang('Type the website URL to link it'),
                    placeholder: "http://",
                },
                target: {
                    label: mw.lang('Open the link in a new window')
                },
                icon: 'mdi mdi-web',
                title: 'URL'
            };
            options =  mw.object.extend(true, {}, defaults, (options || {}));
            this.settings = options;
            if (options.text === true) options.text = defaults.text;
            if (options.link === true) options.link = defaults.link;
            if (options.target === true) options.target = defaults.target;

            var root = document.createElement('div');
            root.className = 'mw-ui-form-controller-root';
            var _linkText = '', _linkUrl = '', _target = '';
            UIFormControllers._title(this.settings, root)
            if (options.text) {
                _linkText = mw.controlFields.field({
                    label: options.text.label,
                    description: options.text.description,
                    name: 'text'
                });
            }
            if (options.link) {
                _linkUrl = mw.controlFields.field({
                    label: options.link.label,
                    description: options.link.description,
                    placeholder: options.link.placeholder,
                    name: 'url'
                });
            }

            if (options.target) {
                _target = mw.controlFields.checkbox({
                    label: options.target.label,
                    name: 'target'
                });
            }


            var holder = document.createElement('div');
            holder.append(_linkText);
            holder.append(_linkUrl);
            holder.append(_target);


            var textField = holder.querySelector('[name="text"]');
            var urlField = holder.querySelector('[name="url"]');
            var targetField = holder.querySelector('[name="target"]');

            this.valid = function () {
                var res = this.isValid();
                footer.ok.disabled = !res;
                return res;
            };

            this.isValid = function () {
                if(textField && !textField.value) {
                    return false;
                }
                if(urlField && !urlField.value) {
                    return false;
                }

                return true;
            };

            var footer = UIFormControllers.footer();

            this.setValue = function (val) {
                val = val || {};
                if(textField) textField.value = val.text || '';
                if(urlField) urlField.value = val.url  || '';
                if(targetField) targetField.checked = val.target  ;
            }
            this.getValue = function () {
                var val = {};
                if(textField) val.text = textField.value;
                if(urlField) val.url = urlField.value;
                if(targetField) val.target = targetField.checked;
                return val;
            };

            this._onChange = [];
            this.onChange = function (c) {
                this._onChange.push(c);
            };

            this._confirm = [];
            this.onConfirm = function (c) {
                this._confirm.push(c);
            };

            this._cancel = [];
            this.onCancel = function (c) {
                this._cancel.push(c);
            };


            mw.emitter.on([textField, urlField, targetField], 'input', function (e){
                if(scope.valid()) {
                    scope._onChange.forEach(function (f){
                        f(scope.getValue());
                    });
                }
            });

            mw.emitter.on(footer.ok, 'click', function (e){
                scope._confirm.forEach(function (f){
                    f(scope.getValue());
                });
            });

            mw.emitter.on(footer.cancel, 'click', function (e){
                scope._cancel.forEach(function (f){
                    f();
                });
            });

            root.append(holder);

            root.append(footer.root);

            this.valid();

            this.root = root;
        }
    };

    mw.UIFormControllers = UIFormControllers;
})();

})();

(() => {
/*!*********************************!*\
  !*** ../widgets/link-editor.js ***!
  \*********************************/

mw.require('widgets.css');
 

    mw.LinkEditor = function(options) {
        var scope = this;
        var defaults = {
            mode: 'dialog',
            controllers: [
                { type: 'url'},
                { type: 'page' },
                { type: 'post' },
                { type: 'file' },
                { type: 'email' },
                { type: 'layout' },
                /*{ type: 'title' },*/
            ],
            title: '<i class="mdi mdi-link mw-link-editor-icon"></i> ' + mw.lang('Link Settings'),
            nav: 'tabs'
        };

        this._confirm = [];
        this.onConfirm = function (c) {
            this._confirm.push(c);
        };

        this._cancel = [];
        this.onCancel = function (c) {
            this._cancel.push(c);
        };

        this.setValue = function (data, controller) {
            controller = controller || 'auto';

            if(controller === 'auto') {
                this.controllers.forEach(function (item){
                    item.controller.setValue(data);
                });
            } else {
                this.controllers.find(function (item){
                    return item.type === controller;
                }).controller.setValue(data);
            }

            return this;
        };
console.log( mw.top().settings, this, this.settings );

        this.settings =  mw.object.extend({}, defaults, options || {});

        console.log( mw.top().settings );

        this.buildNavigation = function (){
            if(this.settings.nav === 'tabs') {
                this.nav = document.createElement('nav');
                 this.nav.className = 'mw-ac-editor-nav';

                var nav = scope.controllers.slice(0, 4);
                var dropdown = scope.controllers.slice(4);

                var handleSelect = function (__for, target) {
                    [].forEach.call(scope.nav.children, function (item){item.classList.remove('active');});
                    scope.controllers.forEach(function (item){item.controller.root.classList.remove('active');});
                    if(target && target.classList) {
                        target.classList.add('active');
                    }
                    __for.controller.root.classList.add('active');
                    if(scope.dialog) {
                        scope.dialog.center();
                    }
                };

                var createA = function (ctrl, index) {
                    var a =  document.createElement('a');
                    a.className = 'mw-ui-btn-tab' + (index === 0 ? ' active' : '');
                    a.innerHTML = ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title);
                    a.__for = ctrl;
                    a.onclick = function (){
                        handleSelect(this.__for, this);
                    };
                    return a;
                };


                nav.forEach(function (ctrl, index){
                    scope.nav.appendChild(createA(ctrl, index));
                });
                this.nav.children[0].click();
                this.root.prepend(this.nav);

                if(dropdown.length) {
                    var dropdownElBtn =  document.createElement('div');
                    var dropdownEl =  document.createElement('div');
                      dropdownElBtn.className = 'mw-ui-btn-tab mw-link-editor-more-button';
                      dropdownEl.className = 'mw-link-editor-nav-drop-box';
                      dropdownEl.style.display = 'none';

                    dropdownElBtn.innerHTML = mw.lang('More') + '<i class="mdi mdi-chevron-down"></i>';
                    dropdown.forEach(function (ctrl, index){

                        mw.element(dropdownEl)
                            .append(mw.element({
                                tag: 'span',
                                props: {
                                    className: '',
                                    __for: ctrl,
                                    innerHTML: ('<i class="'+ctrl.controller.settings.icon+'"></i> '+ctrl.controller.settings.title),
                                    onclick: function () {
                                         handleSelect(this.__for);
                                        mw.element(dropdownEl).hide();
                                    }
                                }
                            }));
                    });
                    this.nav.append(dropdownEl);
                    this.nav.append(dropdownElBtn);
                    dropdownElBtn.onclick = function (){
                        mw.element(dropdownEl).toggle();
                    };

                    dropdownEl.onchange = function () {
                        handleSelect(this.options[this.selectedIndex].__for);
                    };
                    /*setTimeout(function (){
                        if($.fn.selectpicker) {
                            $('.selectpicker').selectpicker();
                        }
                    }, 100)*/
                }
            }

        };

        this.buildControllers = function (){
            this.controllers = [];
            this.settings.controllers.forEach(function (item) {
                if(mw.UIFormControllers[item.type]) {
                    var ctrl = new mw.UIFormControllers[item.type](item.config);
                    scope.root.appendChild(ctrl.root);
                    scope.controllers.push({
                        type: item.type,
                        controller: ctrl
                    });
                    ctrl.onConfirm(function (data){
                        scope._confirm.forEach(function (f){
                            f(data);
                        });
                    });
                    ctrl.onCancel(function (){
                        scope._cancel.forEach(function (f){
                            f();
                        });
                    });
                }

            });
        };
        this.build = function (){
            this.root = document.createElement('div');
            this.root.onclick = function (e) {
                var le2 = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['mw-link-editor-nav-drop-box', 'mw-link-editor-more-button']);
                if(!le2) {
                    mw.element('.mw-link-editor-nav-drop-box').hide();
                }
            };

            this.root.className = 'mw-link-editor-root mw-link-editor-root-inIframe-' + (window.self !== window.top )
            this.buildControllers ();
            if(this.settings.mode === 'dialog') {
                this.dialog = mw.dialog({
                    content: this.root,
                    height: 'auto',
                    title: this.settings.title,
                    overflowMode: 'visible',
                    shadow: false
                });
                this.dialog.center();
                this.onConfirm(function (){
                    scope.dialog.remove();
                });
                this.onCancel(function (){
                    scope.dialog.remove();
                });
            } else if(this.settings.mode === 'element') {
                this.settings.element.append(this.root);
            }
        };
        this.init = function(options) {
            this.build();
            this.buildNavigation();
        };
        this.init();
        this.promise = function () {
            return new Promise(function (resolve){
                scope.onConfirm(function (data){
                    resolve(data);
                });
                scope.onCancel(function (){
                    resolve();
                });
            });
        };
    };



})();

(() => {
/*!**************************!*\
  !*** ../widgets/tags.js ***!
  \**************************/


mw.coreIcons = {
    category:'mw-icon-category',
    page:'mw-icon-page',
    home:'mw-icon-home',
    shop:'mai-market2',
    post:'mai-post'
};


mw.tags = mw.chips = function(options){

    "use strict";

    options.element = mw.$(options.element)[0];
    options.size = options.size || 'sm';

    this.options = options;
    this.options.map = this.options.map || { title: 'title', value: 'id', image: 'image', icon: 'icon' };
    this.map = this.options.map;
    var scope = this;
    /*
        data: [
            {title:'Some tag', icon:'<i class="icon"></i>'},
            {title:'Some tag', icon:'icon', image:'http://some-image/jpg.png'},
            {title:'Some tag', color:'warn'},
        ]
    */


    this.refresh = function(){
        mw.$(scope.options.element).empty();
        this.rend();
    };

    this.setData = function(data){
        this.options.data = data;
        this.refresh();
    };
    this.rend = function(){
        $.each(this.options.data, function(i){
            var data = $.extend({index:i}, this);
            scope.options.element.appendChild(scope.tag(data));
        });
        if(this.options.inputField) {
            scope.options.element.appendChild(this.addInputField());
        }
    };

    this.addInputField = function () {
        this._field = document.createElement('input');
        this._field.className = 'mw-ui-invisible-field mw-ui-field-' + this.options.size;
        this._field.onkeydown = function (e) {
            if(mw.event.is.enter(e)) {
                var val = scope._field.value.trim();
                if(val) {
                    scope.addTag({
                        title: val
                    });
                }
            }
        };
        return this._field;
    };



    this.dataValue = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value]
        }
    };

    this.dataImage = function(data){
        if(data[this.map.image]){
            var img = document.createElement('span');
            img.className = 'mw-ui-btn-img';
            img.style.backgroundImage = 'url('+data.image+')';
            return img;
        }
    };

    this.dataTitle = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.title];
        }
    };

    this.dataIcon = function(data){
        if(typeof data === 'string'){
            return;
        }
        else{
            return data[this.map.icon]
        }
    };


     this.createImage = function (config) {
         var img = this.dataImage(config);
        if(img){
            return img;
        }
     };
     this.createIcon = function (config) {
        var ic = this.dataIcon(config);

        if(!ic && config.type){
            ic = mw.coreIcons[config.type];

        }
        var icon;
        if(typeof ic === 'string' && ic.indexOf('<') === -1){
            icon = document.createElement('i');
            icon.className = ic;
        }
        else{
            icon = ic;
        }

        return mw.$(icon)[0];
     };

     this.removeTag = function (index) {
        var item = this.options.data[index];
        this.options.data.splice(index,1);
        this.refresh();
        mw.$(scope).trigger('tagRemoved', [item, this.options.data]);
        mw.$(scope).trigger('change', [item, this.options.data]);
     };

    this.addTag = function(data, index){
        index = typeof index === 'number' ? index : this.options.data.length;
        this.options.data.splice( index, 0, data );
        this.refresh();
        mw.$(scope).trigger('tagAdded', [data, this.options.data]);
        mw.$(scope).trigger('change', [data, this.options.data]);
    };

     this.tag = function (options) {
            var config = {
                close:true,
                tagBtnClass:'btn btn-' + this.options.size
            };

            $.extend(config, options);

         config.tagBtnClass +=  ' mb-2 mr-2 btn';

         if (this.options.outline){
             config.tagBtnClass +=  '-outline';
         }

         if (this.options.color){
             config.tagBtnClass +=  '-' + this.options.color;
         }



         if(this.options.rounded){
             config.tagBtnClass +=  ' btn-rounded';
         }


            var tag_holder = document.createElement('span');
            var tag_close = document.createElement('span');

            tag_close._index = config.index;
            tag_holder._index = config.index;
            tag_holder._config = config;
            tag_holder.dataset.index = config.index;

            tag_holder.className = config.tagBtnClass;

             if(options.image){

             }

            tag_holder.innerHTML = '<span class="tag-label-content">' + this.dataTitle(config) + '</span>';

             if(typeof this.options.disableItem === 'function') {
                 if(this.options.disableItem(config)){
                     tag_holder.className += ' disabled';
                 }
             }
             if(typeof this.options.hideItem === 'function') {
                 if(this.options.hideItem(config)){
                     tag_holder.className += ' hidden';
                 }
             }

            var icon = this.createIcon(config);

            var image = this.createImage(config);

             if(image){
                 mw.$(tag_holder).prepend(image);
             }
             if(icon){
                 mw.$(tag_holder).prepend(icon);
             }


            tag_holder.onclick = function (e) {
                if(e.target !== tag_close){
                    mw.$(scope).trigger('tagClick', [this._config, this._index, this])
                }
            };

            tag_close.className = 'mw-icon-close ml-1';
            if(config.close){
                tag_close.onclick = function () {
                    scope.removeTag(this._index);
                };
            }
            tag_holder.appendChild(tag_close);
            return tag_holder;
        };

     this.init = function () {
         this.rend();
         $(this.options.element).on('click', function (e) {
             if(e.target === scope.options.element){
                 $('input', this).focus();
             }
         })
     };
    this.init();
};

mw.treeTags = mw.treeChips = function(options){
    this.options = options;
    this.options.on = this.options.on || {};
    var scope = this;

    var tagsHolder = options.tagsHolder || mw.$('<div class="mw-tree-tag-tags-holder"></div>');
    var treeHolder = options.treeHolder || mw.$('<div class="mw-tree-tag-tree-holder"></div>');

    var treeSettings = $.extend({}, this.options, {element:treeHolder});
    var tagsSettings = $.extend({}, this.options, {element:tagsHolder, data:this.options.selectedData || []});

    this.tree = new mw.tree(treeSettings);

    this.tags = new mw.tags(tagsSettings);

    mw.$( this.options.element ).append(tagsHolder);
    mw.$( this.options.element ).append(treeHolder);

     mw.$(this.tags).on('tagRemoved', function(event, item){
         scope.tree.unselect(item);
     });
     mw.$(this.tree).on('selectionChange', function(event, selectedData){
        scope.tags.setData(selectedData);
        if (scope.options.on.selectionChange) {
            scope.options.on.selectionChange(selectedData)
        }
    });

};

})();

(() => {
/*!**************************!*\
  !*** ../widgets/tree.js ***!
  \**************************/

/********************************************************


 var myTree = new mw.tree({

});


 ********************************************************/




(function(){




    var mwtree = function(config){

        var scope = this;

        this.config = function(config){

            window.mwtree = (typeof window.mwtree === 'undefined' ? 0 : window.mwtree)+1;

            if(!config.id && typeof config.saveState === undefined){
                config.saveState = false;
            }

            var defaults = {
                data:[],
                openedClass:'opened',
                selectedClass:'selected',
                skin:'default',
                multiPageSelect:true,
                saveState:true,
                stateStorage: {
                    get: function (id) {
                        return mw.storage.get( id);
                    },
                    set: function (id, dataToSave) {
                        mw.storage.set(id, dataToSave);
                    }
                },
                sortable:false,
                nestedSortable:false,
                singleSelect:false,
                selectedData:[],
                skip:[],
                contextMenu:false,
                append:false,
                prepend:false,
                selectable:false,
                filter:false,
                cantSelectTypes: [],
                document: document,
                _tempRender: true,
                filterRemoteURL: null,
                filterRemoteKey: 'keyword',
            };


            var options = $.extend({}, defaults, config);



            options.element = mw.$(options.element)[0];
            options.data = options.data || [];

            this.options = options;
            this.document = options.document;
            this._selectionChangeDisable = false;

            this.stateStorage = this.options.stateStorage;

            if(this.options.selectedData){
                this.selectedData = this.options.selectedData;
            }
            else{
                this.selectedData = [];
            }
        };
        this.filterLocal = function(val, key){
            key = key || 'title';
            val = (val || '').toLowerCase().trim();
            if(!val){
                scope.showAll();
            }
            else{
                scope.options.data.forEach(function(item){
                    if(item[key].toLowerCase().indexOf(val) === -1){
                        scope.hide(item);
                    }
                    else{
                        scope.show(item);
                    }
                });
            }
        };

        this._filterRemoteTime = null;
        this.filterRemote = function(val, key){
            clearTimeout(this._filterRemoteTime);
            this._filterRemoteTime = setTimeout(function () {
                key = key || 'title';
                val = (val || '').toLowerCase().trim();
                var ts = {};
                ts[scope.options.filterRemoteKey] = val;
                $.get(scope.options.filterRemoteURL, ts, function (data) {
                    scope.setData(data);
                });
            }, 777);
        };

        this.filter = function(val, key){
            if (!!this.options.filterRemoteURL && !!this.options.filterRemoteKey) {
                this.filterRemote(val, key);
            } else {
                this.filterLocal(val, key);
            }
        };

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.search = function(){
            this._seachInput = mw.$(this.options.searchInput);
            if(!this._seachInput[0] || this._seachInput[0]._tree) return;
            this._seachInput[0]._tree = this;
            var scope = this;
            this._seachInput.on('input', function(){
                scope.filter(this.value);
            });
        };
        this.skip = function(itemData){
            if(this.options.skip && this.options.skip.length>0){
                for( var n=0; n<scope.options.skip.length; n++ ){
                    var item = scope.options.skip[n];
                    var case1 = (item.id == itemData.id && item.type == itemData.type);
                    var case2 = (itemData.parent_id == item.id && item.type == itemData.type);
                    if(case1 ||case2){
                        return true;
                    }
                }
                return false;
            }
        };
        this.prepareData = function(){
            if(typeof this.options.filter === 'object'){
                var final = [], scope = this;
                for( var i in this.options.filter){
                    $.each(this.options.data, function(){
                        if(this[i] && this[i] == scope.options.filter[i]){
                            final.push(this);
                        }
                    });
                }
                this.options.data = final;
            }
        };


        this._postCreated = [];

        this.json2ul = function(){
            this.list = scope.document.createElement( 'ul' );
            this.list._tree = this;
            this.options.id = this.options.id || ( 'mw-tree-' + window.mwtree );
            this.list.id = this.options.id;
            this.list.className = 'mw-defaults mw-tree-nav mw-tree-nav-skin-' + this.options.skin;
            this.list._id = 0;
            this.options.data.forEach(function(item){
                var list = scope.getParent(item);
                if(list){
                    var li = scope.createItem(item);
                    if(li){
                        list.appendChild(li);
                    }
                }
                else if(typeof list === 'undefined'){
                    scope._postCreated.push(item);
                }
            });
            if(this.options._tempRender) {
                this.tempRend();
            }
        };



        this._tempPrepare = function () {
            for (var i=0; i<this._postCreated.length; i++) {
                var it = this._postCreated[i];
                if(it.parent_id !== 0) {
                    var has = this.options.data.find(function (a) {
                        return a.id ==  it.parent_id; // 1 == '1'
                    });
                    if(!has) {
                        it.parent_id = 0;
                        it.parent_type = "page";
                    }
                }
            }
        };

        this.tempRend = function () {
            this._tempPrepare()
            var curr = scope._postCreated[0];
            var max = 10000, itr = 0;

            while(scope._postCreated.length && itr<max){
                itr++;
                var index = scope._postCreated.indexOf(curr);
                var selector = '#' + scope.options.id + '-' + curr.type + '-'  + curr.id;
                var lastc = selector.charAt(selector.length - 1);
                if( lastc === '.' || lastc === '#') {
                    selector = selector.substring(0, selector.length - 1);
                }
                var it = mw.$(selector)[0];
                if(it){
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                    continue;
                }
                var list = scope.getParent(curr);

                if(list && !$(selector)[0]){
                    var li = scope.createItem(curr);
                    if(li){
                        list.appendChild(li);
                    }
                    scope._postCreated.splice(index, 1);
                    curr = scope._postCreated[0];
                }
                else if(typeof list === 'undefined'){
                    curr = scope._postCreated[index+1] || scope._postCreated[0];
                }
            }

        };

        function triggerChange() {
            if(!this._selectionChangeDisable) {
                mw.$(scope).trigger('selectionChange', [scope.selectedData]);
                scope.dispatch('selectionChange', scope.selectedData)
            }
        }

        this.setData = function(newData){
            this.options.data = newData;
            this._postCreated = [];
            this._ids = [];
            this.init();
        };

        this.saveState = function(){
            if(!this.options.saveState) return;
            var data = [];
            mw.$( 'li.' + this.options.openedClass, this.list  ).each(function(){
                if(this._data) {
                    data.push({type:this._data.type, id:this._data.id})
                }
            });

            this.stateStorage.set(this.options.id, data);
        };

        this.restoreState = function(){
            if(!this.options.saveState) return;
            var data = this.stateStorage.get(this.options.id);
            if(!data) return;
            try{
                $.each(data, function(){
                    if(typeof this.id === 'string'){
                        this.id = parseInt(this.id, 10);
                    }
                    scope.open(this.id, this.type);
                });
            }
            catch(e){ }
        };

        this.manageUnselected = function(){
            mw.$('input:not(:checked)', this.list).each(function(){
                var li = scope.parentLi(this);
                mw.$(li).removeClass(scope.options.selectedClass)
            });
        };

        this.analizeLi = function(li){
            if(typeof li === 'string'){
                li = decodeURIComponent(li).trim();
                if(/^\d+$/.test(li)){
                    li = parseInt(li, 10);
                }
                else{
                    return mw.$(li)[0];
                }
            }
            return li;
        };

        this.select = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.select(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li && this.options.cantSelectTypes.indexOf(li.dataset.type) === -1){
                li.classList.add(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = true;
            }

            this.manageUnselected();
            this.getSelected();
            triggerChange();
        };



        this.unselect = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.unselect(this);
                });
                return;
            }
            li = this.get(li, type);
            if(li){
                li.classList.remove(this.options.selectedClass);
                var input = li.querySelector('input');
                if(input) input.checked = false;
            }
            this.manageUnselected();
            this.getSelected();
            triggerChange();
        };

        this.get = function(li, type){
            if(typeof li === 'undefined') return false;
            if(li === null) return false;
            if(li.nodeType) return li;
            li = this.analizeLi(li);
            if(typeof li === 'object' && typeof li.id !== 'undefined'){
                return this.get(li.id, li.type);
            }
            if(typeof li === 'object' && li.constructor === Number){
                li = parseInt(li);
            }
            if(typeof li === 'number'){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            if(typeof li === 'string' && /^\d+$/.test(li)){
                if(!type) return;
                return this.list.querySelector('li[data-type="'+type+'"][data-id="'+li+'"]');
            }
            //if(!li) {console.warn('List item not defined:', li, type)}
            return li;
        };

        this.isSelected = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            var input = li.querySelector('input');
            if(!input) return false;
            return input.checked === true;
        };
        this.toggleSelect = function(li, type){
            if(this.isSelected(li, type)){
                this.unselect(li, type)
            }
            else{
                this.select(li, type)
            }
        };

        this.selectAll = function(){
            this._selectionChangeDisable = true;
            this.select(this.options.data);
            this._selectionChangeDisable = false;
            triggerChange()
        };

        this.unselectAll = function(){
            this._selectionChangeDisable = true;
            this.unselect(this.selectedData);
            this._selectionChangeDisable = false;
            triggerChange()
        };

        this.open = function(li, type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.open(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };
        this.show = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.show(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove('mw-tree-item-hidden');
            mw.$(li).parents(".mw-tree-item-hidden").removeClass('mw-tree-item-hidden').each(function(){
                scope.open(this);
            });
        };

        this.showAll = function(){
            mw.$(this.list.querySelectorAll('li')).removeClass('mw-tree-item-hidden');
        };

        this.hide = function(li, type){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.hide(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.add('mw-tree-item-hidden');
        };

        this.hideAll = function(){
            mw.$(this.list.querySelectorAll('li')).addClass('mw-tree-item-hidden');
        };

        this.close = function(li,type, _skipsave){
            if(Array.isArray(li)){
                $.each(li, function(){
                    scope.close(this);
                });
                return;
            }
            li = this.get(li, type);
            if(!li) return;
            li.classList.remove(this.options.openedClass);
            if(!_skipsave) this.saveState();
        };

        this.toggle = function(li, type){
            li = this.get(li, type);
            if(!li) return;
            li.classList.toggle(this.options.openedClass);
            this.saveState();
        };

        this.openAll = function(){
            var all = this.list.querySelectorAll('li');
            mw.$(all).each(function(){
                scope.open(this, undefined, true);
            });
            this.saveState();
        };

        this.closeAll = function(){
            var all = this.list.querySelectorAll('li.'+this.options.openedClass);
            mw.$(all).each(function(){
                scope.close(this, undefined, true);
            });
            this.saveState();
        };

        this.button = function(){
            var btn = scope.document.createElement('mwbutton');
            btn.className = 'mw-tree-toggler';
            btn.onclick = function(){
                scope.toggle(mw.tools.firstParentWithTag(this, 'li'));
            };
            return btn;
        };

        this.addButtons = function(){
            var all = this.list.querySelectorAll('li ul.pre-init'), i=0;
            for( ; i<all.length; i++ ){
                var ul = all[i];
                ul.classList.remove('pre-init');
                mw.$(ul).parent().children('.mw-tree-item-content-root').prepend(this.button());
            }
        };

        this.checkBox = function(element){
            if(this.options.cantSelectTypes.indexOf(element.dataset.type) !== -1){
                return scope.document.createTextNode('');
            }
            var itype = 'radio';
            if(this.options.singleSelect){

            }
            else if(this.options.multiPageSelect || element._data.type !== 'page'){
                itype = 'checkbox';
            }
            var label = scope.document.createElement('tree-label');
            var input = scope.document.createElement('input');
            var span = scope.document.createElement('span');
            input.type = itype;
            input._data = element._data;
            input.value = element._data.id;
            input.name = this.list.id;
            label.className = 'mw-ui-check';

            label.appendChild(input);


            label.appendChild(span);

            /*input.onchange = function(){
                var li = scope.parentLi(this);
                mw.$(li)[this.checked?'addClass':'removeClass'](scope.options.selectedClass)
                var data = scope.getSelected();
                scope.manageUnselected()
                mw.$(scope).trigger('change', [data]);
            }*/
            return label;
        };

        this.parentLi = function(scope){
            if(!scope) return;
            if(scope.nodeName === 'LI'){
                return scope;
            }
            while(scope.parentNode){
                scope = scope.parentNode;
                if(scope.nodeName === 'LI'){
                    return scope;
                }
            }
        };

        this.getSelected = function(){
            var selected = [];
            var all = this.list.querySelectorAll('li.selected');
            mw.$(all).each(function(){
                if(this._data) selected.push(this._data);
            });
            this.selectedData = selected;
            this.options.selectedData = selected;
            return selected;
        };

        this.decorate = function(element){
            if(this.options.selectable){
                mw.$(element.querySelector('.mw-tree-item-content')).prepend(this.checkBox(element))
            }

            element.querySelector('.mw-tree-item-content').appendChild(this.contextMenu(element));

            if(this.options.sortable){
                this.sortable();
            }
            if(this.options.nestedSortable){
                this.nestedSortable();
            }

        };

        this.sortable = function(element){
            var items = mw.$(this.list);
            mw.$('ul', this.list).each(function () {
                items.push(this);
            });
            items.sortable({
                items: ".type-category, .type-page",
                axis:'y',
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){
                    setTimeout(function(){
                        var old = $.extend({},ui.item[0]._data);
                        var obj = ui.item[0]._data;
                        var objParent = ui.item[0].parentNode.parentNode._data;
                        ui.item[0].dataset.parent_id = objParent ? objParent.id : 0;

                        obj.parent_id = objParent ? objParent.id : 0;
                        obj.parent_type = objParent ? objParent.id : 'page';
                        var newdata = [];
                        mw.$('li', scope.list).each(function(){
                            if(this._data) newdata.push(this._data)
                        });
                        scope.options.data = newdata;
                        var local = [];
                        mw.$(ui.item[0].parentNode).children('li').each(function(){
                            if(this._data) {
                                local.push(this._data.id);
                            }
                        });
                        //$(scope.list).remove();
                        //scope.init();
                        mw.$(scope).trigger('orderChange', [obj, scope.options.data, old, local])
                    }, 110);

                }
            });
        };
        this.nestedSortable = function(element){
            mw.$('ul', this.list).nestedSortable({
                items: ".type-category",
                listType:'ul',
                handle:'.mw-tree-item-title',
                update:function(e, ui){

                }
            })
        };

        this.contextMenu = function(element){
            var menu = scope.document.createElement('span');
            menu.className = 'mw-tree-context-menu';
            if(this.options.contextMenu){
                $.each(this.options.contextMenu, function(){
                    var menuitem = scope.document.createElement('span');
                    var icon = scope.document.createElement('span');
                    menuitem.title = this.title;
                    menuitem.className = 'mw-tree-context-menu-item';
                    icon.className = this.icon;
                    menuitem.appendChild(icon);
                    menu.appendChild(menuitem);
                    (function(menuitem, element, obj){
                        menuitem.onclick = function(){
                            if(obj.action){
                                obj.action.call(element, element, element._data, menuitem)
                            }
                        }
                    })(menuitem, element, this);
                });
            }
            return menu

        };

        this.rend = function(){
            if(this.options.element){
                var el = mw.$(this.options.element);
                if(el.length!==0){
                    el.empty().append(this.list);
                }
            }
        };

        this._ids = [];

        this._createSingle = function (item) {

        }

        this.createItem = function(item){
            var selector = '#'+scope.options.id + '-' + item.type+'-'+item.id;
            if(this._ids.indexOf(selector) !== -1) return false;
            this._ids.push(selector);
            var li = scope.document.createElement('li');
            li.dataset.type = item.type;
            li.dataset.id = item.id;
            li.dataset.parent_id = item.parent_id;
            var skip = this.skip(item);
            li.className = 'type-' + item.type + ' subtype-'+ item.subtype + ' skip-' + (skip || 'none');
            var container = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            container.innerHTML = '<span class="mw-tree-item-title">'+item.title+'</span>';

            li._data = item;
            li.id = scope.options.id + '-' + item.type+'-'+item.id;
            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            if(!skip){
                container.onclick = function(){
                    if(scope.options.selectable) scope.toggleSelect(li)
                };
                this.decorate(li);
            }


            return li;
        };



        this.additional = function (obj) {
            var li = scope.document.createElement('li');
            li.className = 'mw-tree-additional-item';
            var container = scope.document.createElement('span');
            var containerTitle = scope.document.createElement('span');
            container.className = "mw-tree-item-content";
            containerTitle.className = "mw-tree-item-title";
            container.appendChild(containerTitle);

            li.appendChild(container);
            $(container).wrap('<span class="mw-tree-item-content-root"></span>')
            if(obj.icon){
                if(obj.icon.indexOf('</') === -1){
                    var icon = scope.document.createElement('span');
                    icon.className = 'mw-tree-aditional-item-icon ' + obj.icon;
                    containerTitle.appendChild(icon);
                }
                else{
                    mw.$(containerTitle).append(obj.icon)
                }

            }
            var title = scope.document.createElement('span');
            title.innerHTML = obj.title;
            containerTitle.appendChild(title);
            li.onclick = function (ev) {
                if(obj.action){
                    obj.action.call(this, obj)
                }
            };
            return li;
        }

        this.createList = function(item){
            var nlist = scope.document.createElement('ul');
            nlist.dataset.type = item.parent_type;
            nlist.dataset.id = item.parent_id;
            nlist.className = 'pre-init';
            return nlist;
        };

        this.getParent = function(item, isTemp){
            if(!item.parent_id) return this.list;
            var findul = this.list.querySelector('ul[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            var findli = this.list.querySelector('li[data-type="'+item.parent_type+'"][data-id="'+item.parent_id+'"]');
            if(findul){
                return findul;
            }
            else if(findli){
                var nlistwrap = this.createItem(item);
                if(!nlistwrap) return false;
                var nlist = this.createList(item);
                nlist.appendChild(nlistwrap);
                findli.appendChild(nlist);
                return false;
            }
        };

        this.append = function(){
            if(this.options.append){
                $.each(this.options.append, function(){
                    scope.list.appendChild(scope.additional(this))
                })
            }
        };

        this.prepend = function(){
            if(this.options.prepend){
                $.each(this.options.append, function(){
                    mw.$(scope.list).prepend(scope.additional(this))
                })
            }
        };

        this.addHelperClasses = function(root, level){
            level = (level || 0) + 1;
            root = root || this.list;
            mw.$( root.children ).addClass('level-'+level).each(function(){
                var ch = this.querySelector('ul');
                if(ch){
                    mw.$(this).addClass('has-children')
                    scope.addHelperClasses(ch, level);
                }
            })
        };

        this.loadSelected = function(){
            if(this.selectedData){
                scope.select(this.selectedData);
            }
        };
        this.init = function(){

            this.prepareData();
            this.json2ul();
            this.addButtons();
            this.rend();
            this.append();
            this.prepend();
            this.addHelperClasses();
            this.restoreState();
            this.loadSelected();
            this.search();
            setTimeout(function(){
                mw.$(scope).trigger('ready');
            }, 78)
        };

        this.config(config);
        this.init();
    };
    mw.tree = mwtree;
    mw.tree.get = function (a) {
        a = mw.$(a)[0];
        if(!a) return;
        if(mw.tools.hasClass(a, 'mw-tree-nav')){
            return a._tree;
        }
        var child = a.querySelector('.mw-tree-nav');
        if(child) return child._tree;
        var parent = mw.tools.firstParentWithClass(a, 'mw-tree-nav');

        if(parent) {
            return parent._tree;
        }
    }


})();

})();

/******/ })()
;
//# sourceMappingURL=liveedit.js.map