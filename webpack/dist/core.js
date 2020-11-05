/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "../userfiles/modules/microweber/api/core/components.js":
/*!**************************************************************!*\
  !*** ../userfiles/modules/microweber/api/core/components.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
/***/ (() => {

    mw.components = {
    _rangeOnce: false,
    'range': function(el){
        mw.lib.require('jqueryui');
        var options = this._options(el);
        var defaults = {
            range: 'min',
            animate: "fast"
        };
        var ex = {}, render = el;
        if(el.nodeName === 'INPUT'){
            el._pauseChange = false;
            el.type = 'text';
            render = document.createElement('div');
            $(el).removeClass('mw-range');
            $(render).addClass('mw-range');
            $(el).after(render);
            ex = {
                slide: function( event, ui ) {
                    el._pauseChange = true;
                   $(el).val(ui.value).trigger('change').trigger('input');
                    setTimeout(function () {
                        el._pauseChange = false;
                    }, 78);
                }
            };

        }
        var settings = $.extend({}, defaults, options, ex);
        if(el.min){
            settings.min = parseFloat(el.min);
        }
        if(el.max){
            settings.max = parseFloat(el.max);
        }
        if(el.value){
            settings.value = parseFloat(el.value);
        }
        mw.$(render)
            .slider(settings)
            .on('mousedown touchstart', function(){
                mw.$(this).addClass('active');
            });
        $(el).on('input', function(){
            mw.$(render).slider( "value", this.value );
        });
        if(!mw.components._rangeOnce) {
            mw.components._rangeOnce = true;
            mw.$(document.body).on('mouseup touchend', function(){
                mw.$('.mw-range.active').removeClass('active');
            });
        }
    },
    'color-picker': function(el){
        var options = this._options(el);
        var defaults = {
            position: 'bottom-center'
        };
        var settings = $.extend({}, defaults, options);
        var nav = document.createElement('div');
        nav.className = 'mw-ui-btn-nav mw-color-picker-holder';
        var view = document.createElement('div');
        view.className = 'mw-ui-btn';
        view.innerHTML = '<span class="mw-ui-btn-img"></span>';
        nav.appendChild(view);
        var inputEl;
        if(el.nodeName === 'INPUT'){
            inputEl = el;
            mw.$(el).addClass('mw-ui-field').after(nav);
            nav.appendChild(el);
            mw.$('.mw-ui-btn-img', view).css("background-color", el.value);
        }

        inputEl._time = null;
        var picker = mw.colorPicker({
            element:inputEl,
            position:settings.position,
            onchange:function(color){
                mw.$('.mw-ui-btn-img', view).css("background-color", color);
                mw.$(inputEl).trigger('change');
            }
        });
        mw.$(view).on("click", function(){
            setTimeout(function(){
                picker.toggle();
            }, 10);
        });
    },
    'file-uploader':function(el){
        var options = this._options(el);
        var defaults = {
            element: el
        };
        var settings = $.extend({}, defaults, options);
        var ch = mw.$(el).attr("onchange");

        mw.fileWindow({
            types:'media',
            change:function(url){
                try{
                    eval(ch);
                }
                catch(err){}
            }
        });
    },
    'modules-tabs':function(el){
        var options = this._options(el);
        options.breakPoint = 100; //makes accordion if less then 100px
        if(window.live_edit_sidebar) {
            mw.$(el).addClass('mw-accordion-window-height')
            options.breakPoint = 800; //makes accordion if less then 800px
        }
        var accordion = this.accordion(el);
        var tb = new mw.tabAccordion(options, accordion);
    },
    'tab-accordion':function(el){
       var options = this._options(el);
       var accordion = this.accordion(el);
       var tb = new mw.tabAccordion(options, accordion);
    },
    accordion:function(el){
        if(!el || el._accordion) return;
        if(!$(el).is(':visible')){
            setTimeout(function(){
                mw.components.accordion(el);
            }, 777, el);
            return;
        }
        el._accordion = true;
        var options = this._options(el);
        var settings = $.extend(options, {element:el})
        var accordion = new mw.uiAccordion(settings);
        if($(el).hasClass('mw-accordion-window-height')){
            accordion._setHeight = function(){
                var max =  mw.$(window).height() - (accordion.root.offset().top - mw.$(window).scrollTop());
                accordion.root.css('height', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('height', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        if($(el).hasClass('mw-accordion-full-height')){
            accordion._setHeight = function(){
                var max = Math.min($(el).parent().height(), mw.$(window).height());
                accordion.root.css('maxHeight', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('maxHeight', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        return accordion;
    },
    postSearch: function (el) {
        var defaults = {keyword: el.value, limit: 4};
        el._setValue = function (id) {
            mw.tools.ajaxSearch(this._settings, function () {

            });
        };

        el = mw.$(el);
        var options = JSON.parse(el.attr("data-options") || '{}');
        settings = $.extend({}, defaults, options);
        el[0]._settings = settings;

        el.wrap("<div class='mw-component-post-search'></div>");
        el.after("<ul></ul>");

        el.on("input focus blur", function (event) {

            if (!el[0].is_searching) {
                var val = el.val();
                if (event.type == 'blur') {
                    mw.$(this).next('ul').hide();
                    return false;
                }
                if (event.type == 'focus') {
                    if ($(this).next('ul').html()) {
                        mw.$(this).next('ul').show()
                    }
                    return false;
                }
                el[0].is_searching = true;

                this._settings.keyword = this.value;
                mw.$('ul', el).empty("")
                el.parent().addClass("loading");
                mw.tools.ajaxSearch(this._settings, function () {
                    var lis = [];
                    var json = this;
                    for (var item in json) {
                        var obj = json[item];
                        if (typeof obj === 'object') {
                            var li = document.createElement("li");
                            li._value = obj;
                            li.innerHTML = obj.title;
                            mw.$(li).on("mousedown touchstart", function () {
                                el.val(this._value.title);

                                el[0]._value = this._value;

                                el.trigger('postSelected', [this._value]);
                                mw.$(this.parentNode).hide()
                            })

                            lis.push(li);
                        }
                    }
                    el.parent().removeClass("loading");
                    var ul = el.parent().find("ul");
                    ul.empty().append(lis).show();
                    el[0].is_searching = false;
                });
            }
        });
        el.trigger("postSearchReady");
    },
    _options: function (el) {
        return mw.tools.elementOptions(el);
    },
    _init: function () {
        mw.$('.mw-field input[type="range"]').addClass('mw-range');
        mw.$('[data-mwcomponent]').each(function () {
            var component = mw.$(this).attr("data-mwcomponent");
            if (mw.components[component]) {
                mw.components[component](this);
                mw.$(this).removeAttr('data-mwcomponent')
            }
        });
        $.each(this, function(key){
            if(key.indexOf('_') === -1){
                mw.$('.mw-'+key+', mw-'+key).not(".mw-component-ready").each(function () {
                    mw.$(this).addClass('mw-component-ready');
                    mw.components[key](this);
                });
            }
        });
    }
};

$(document).ready(function () {
    mw.components._init();
});

$(window).on('load', function () {
    mw.components._init();
});

    mw.on('ComponentsLaunch', function () {
        mw.components._init();
    });

    mw.on('mwDialogShow', function () {
        setTimeout(function () {
            mw.components._init();
        }, 110);
    });

$(window).on('ajaxStop', function () {
    setTimeout(function () {
        mw.components._init();
    }, 100);
});

mw.registerComponent = function(name, func){
    if(mw.components[name]){
        console.warn('Component ' + name + ' already exists.');
        return;
    }
    mw.components[name] = func;
};


/***/ }),

/***/ "../userfiles/modules/microweber/api/core/element.js":
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/core/element.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
/***/ (() => {

(function(){

    var MWElement = function(options, root){
        var scope = this;


        this.toggle = function () {
            this.css('display', this.css('display') === 'none' ? 'block' : 'none');
        };

        this._active = function () {
            return this.nodes[this.nodes.length - 1];
        };

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

        this.create = function(){
            var el = this.document.createElement(this.settings.tag);
            this.node = el;

            if (this.settings.encapsulate) {
                var mode = this.settings.encapsulate === true ? 'open' : this.settings.encapsulate;
                el.attachShadow({
                    mode: mode
                });
            }
            this.nodes = [el];
            if (this.settings.content) {
                if (Array.isArray(this.settings.content)) {
                    this.settings.content.forEach(function (el){
                        scope.append(el);
                    })
                } else {
                    this.append(this.settings.content);
                }
            }
            this.$node = $(el);
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
                    this.node.style[i] = this._normalizeCSSValue(i, css[i]);
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
            return this.each(function (){
                this.classList.remove(cls.trim());
            });
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
                    this.parentNode.insertBefore(scope._asdom(el), this);
                });
            }
            return this;
        };

        this.after = function (el) {
            if (el) {
                this.each(function (){
                    this.parentNode.insertBefore(scope._asdom(el), this.nextSibling);
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
                /*this.dispatchEvent(new CustomEvent(event, {
                    detail: data,
                    cancelable: true,
                    bubbles: true
                }));*/
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
                } else {
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

            this.settings = $.extend({}, defaults, options);

            if(this._asElement) return;
            this.create();
            this.setProps();
         };
        this.init();
    };
    mw.element = function(options){
        return new MWElement(options);
    };
    mw.element.module = function (name, func) {
        MWElement.prototype[name] = func;
    };

})();


/***/ }),

/***/ "../userfiles/modules/microweber/api/core/events.js":
/*!**********************************************************!*\
  !*** ../userfiles/modules/microweber/api/core/events.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: top-level-this-exports */
/***/ (function() {

mw.require('url.js');

mw.hash = function(b){ return b === undefined ? window.location.hash : window.location.hash = b; };

mw.on = function(eventName, callback){
    eventName = eventName.trim()
    $.each(eventName.split(' '), function(){
        mw.$(mw._on._eventsRegister).on(this.toString(), callback);
    });
};
mw.trigger = function(eventName, paramsArray){
    return mw.$([mww, mw._on._eventsRegister]).trigger(eventName, paramsArray);
};


mw._on = {
  _eventsRegister:{},
  onmodules : {},
  moduleReload : function(id, c, trigger){
      var exists;
     if(trigger){
          exists = typeof mw.on.onmodules[id] !== 'undefined';
          if(exists){
            var i = 0, l = mw.on.onmodules[id].length;
            for( ; i < l; i++){
               mw.on.onmodules[id][i].call(mwd.getElementById(id));
            }
          }
        return false;
     }
     if(mw.is.func(c)){
       exists = typeof mw.on.onmodules[id] !== 'undefined';
       if(exists){
          mw.on.onmodules[id].push(c);
       }
       else{
         mw.on.onmodules[id] = [c];
       }
     }
     else if(c==='off'){
        exists = typeof mw.on.onmodules[id] !== 'undefined';
        if(exists){
          mw.on.onmodules[id] = [];
        }
     }
  },
  _hashrec : {},
  _hashparams : this._hashparams || [],
  _hashparam_funcs : [],
  hashParam : function(param, callback, trigger, isManual){

    if(isManual){
        var index = mw.on._hashparams.indexOf(param);
        if (mw.on._hashparam_funcs[index] !== undefined){
          mw.on._hashparam_funcs[index].call(false);
        }
        return false;
    }
    if(trigger === true){
        var index = mw.on._hashparams.indexOf(param);

        if(index !== -1){
          var hash = mw.hash();
          var params = mw.url.getHashParams(hash);

          if(typeof params[param] === 'string' && mw.on._hashparam_funcs[index] !== undefined){
              mw.on._hashparam_funcs[index].call(decodeURIComponent(params[param]));

          }
        }
    }
    else{
        mw.on._hashparams.push(param);
        mw.on._hashparam_funcs.push(callback);
    }
},
hashParamEventInit:function(){
  var hash = mw.hash();
  var params = mw.url.getHashParams(hash);

  if(hash==='' || hash==='#' || hash ==='#?'){
    var len = mw.on._hashparams.length, i=0;
    for( ; i < len; i++){
        mw.on.hashParam(mw.on._hashparams[i], "", true);
    }
  }
  else{
    for(var x in params){
        if(params[x] !== mw.on._hashrec[x] || typeof mw.on._hashrec[x] === 'undefined'){
            mw.on.hashParam(x, "", true);
        }
    }
  }

  mw.on._hashrec = params;
},
DOMChangePause:false,
DOMChangeTime:1500,
DOMChange:function(element, callback, attr, a){
    attr = attr || false;
    a = a || false;

    element.addEventListener("input", function(e){
        if( !mw.on.DOMChangePause ) {
            if(!a){
                callback.call(this);
            }
            else{
                clearTimeout(element._int);
                element._int = setTimeout(function(){
                    callback.call(element);
                }, mw.on.DOMChangeTime);
            }

        }
    }, false);

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    if(typeof MutationObserver === 'function'){
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation){
            if( !mw.on.DOMChangePause ) {
                callback.call(mutation.target);
            }
          });
        });
        var config = { attributes: attr, childList: true, characterData: true };
        observer.observe(element, config);
    } else {
        element.addEventListener("DOMCharacterDataModified", function(e){
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }

            }
        }, false);
        element.addEventListener("DOMNodeInserted", function(e){

            if(/*mw.tools.hasClass(e.target, 'element') || */mw.tools.hasClass(e.target, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')){
                return false;
            }
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }
            }
        }, false);

        if(attr){
            element.addEventListener("DOMAttrModified", function(e){

                var attr = e.attrName;
                if(attr !== "contenteditable"){
                    if( !mw.on.DOMChangePause ) {
                        if(!a){
                            callback.call(this);
                        }
                        else{
                            clearTimeout(element._int);
                            element._int = setTimeout(function(){
                                callback.call(element);
                            }, mw.on.DOMChangeTime);
                        }
                    }
                }
            }, false);
        }
    }

 },
 stopWriting:function(el, c){
    if(el === null || typeof el === 'undefined'){ return false; }
    if(!el.onstopWriting){
      el.onstopWriting = null;
    }
    clearTimeout(el.onstopWriting);
    el.onstopWriting = setTimeout(function(){
        c.call(el);
    }, 400);
 },
 scrollBarOnBottom : function(obj, distance, callback){
    if(typeof obj === 'function'){
       callback = obj;
       obj =  window;
       distance = 0;
    }
    if(typeof distance === 'function'){
      callback = distance;
      distance = 0;
    }
    obj._pauseCallback = false;
    obj.pauseScrollCallback = function(){ obj._pauseCallback = true;}
    obj.continueScrollCallback = function(){ obj._pauseCallback = false;}
    mw.$(obj).scroll(function(e){
      var h = obj === window ? mwd.body.scrollHeight : obj.scrollHeight;
      var calc = h - mw.$(obj).scrollTop() - mw.$(obj).height();
      if(calc <= distance && !obj._pauseCallback){
        callback.call(obj);
      }
    });
  },
  tripleClick : function(el, callback){
      var t, timeout = 199;
      el = el || window;
      el.addEventListener("dblclick", function () {
          t = setTimeout(function () {
              t = null;
          }, timeout);
      });
      el.addEventListener("click", function (e) {
          if (t) {
              clearTimeout(t);
              t = null;
              callback.call(el, e.target);
          }
      });
  },
  transitionEnd:function(el,callback){
    mw.$(el).bind('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd otransitionend', function(){
        callback.call(el);
    });
  },
  ones:{ },
  one:function(name, c, trigger, isDone){
    if(trigger !== true){
      if(mw.on.ones[name] === undefined){
         mw.on.ones[name] = [c]
      }
      else{
         mw.on.ones[name].push(c);
      }
    }
    else{
       if(mw.on.ones[name] !== undefined){
          var i=0, l = mw.on.ones[name].length;
          for( ; i<l; i++){
              if(isDone === true){
                mw.on.ones[name][i].call('ready', 'ready');
              }
              else{
                mw.on.ones[name][i].call('start', 'start');
              }
          }
       }
    }
  },
  userIteractionInitRegister: new Date().getTime(),
  userIteractionInit: function(){
      var max = 378;
      mw.$(mwd).on('mousemove touchstart click keydown resize ajaxStop', function(){
          var time = new Date().getTime();
          if((time - mw._on.userIteractionInitRegister) > max){
              mw._on.userIteractionInitRegister = time;
              mw.trigger('UserInteraction');
          }
      });
  }
};

for(var x in mw._on) mw.on[x] = mw._on[x];



mw.hashHistory = [window.location.hash]

mw.prevHash = function(){
  var prev = mw.hashHistory[mw.hashHistory.length - 2];
  return prev !== undefined ? prev : '';
};



$(window).on("hashchange load", function(event){
    if(event.type === 'load'){
        mw._on.userIteractionInit();
    }

    mw.on.hashParamEventInit();

   var hash =  mw.hash();

   var isMWHash = hash.replace(/\#/g, '').indexOf('mw@') === 0;
   if (isMWHash) {
       var MWHash = hash.replace(/\#/g, '').replace('mw@', '');
       var el = document.getElementById(MWHash);
       if(el) {
           mw.tools.scrollTo(el);
       }
   }
   if(hash.contains("showpostscat")){
      mw.$("html").addClass("showpostscat");
   }
   else{
      mw.$("html").removeClass("showpostscat");
   }


   if (event.type === 'hashchange') {
     mw.hashHistory.push(mw.hash());
     var size = mw.hashHistory.length;
     var changes = mw.url.whichHashParamsHasBeenRemoved(mw.hashHistory[size-1], mw.hashHistory[size-2]), l=changes.length, i=0;
     if (l>0) {
       for( ; i < l; i++ ){
          mw.on.hashParam(changes[i], "", true, true);
       }
     }
   }
});


mw.event = {
    windowLeave: function(c) {
      document.documentElement.addEventListener('mouseout', function(e) {
          if (!e.relatedTarget && !e.toElement && c) {
              c.call(document.body, e);
          }
      });
    },
    cancel:function(e, prevent){
    prevent === true ? e.preventDefault() : '';
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
    },
    key:function(e,key){
        return (e.keyCode === parseFloat(key));
    },
    page: function (e) {
      e = e.originalEvent || e;
      if (e.type.indexOf('touch') !== 0) {
        return {
            x: e.pageX,
            y: e.pageY
        };
      } else {
          return {
              x: e.changedTouches[0].pageX,
              y: e.changedTouches[0].pageY
          };
      }
    },
    targetIsField: function(e) {
        e = e.originalEvent || e;
        var t = e.target;
        return t.nodeName === 'INPUT' ||
            t.nodeName === 'textarea' ||
            t.nodeName === 'select';
    },
    get: function(e) {
        return e.originalEvent || e;
    },
    keyCode: function(e) {
        e = mw.event.get(e);
        return e.keyCode || e.which;
    },
    isKeyCode: function(e, code){
        return this.keyCode(e) === code;
    },
    is: {
      enter: function (e) {
        e = mw.event.get(e);
        return e.key === "Enter" || mw.event.isKeyCode(e, 13);
      },
      escape: function (e) {
          e = mw.event.get(e);
          return e.key === "Escape" || mw.event.isKeyCode(e, 27);
      },
      backSpace : function (e) {
        e = mw.event.get(e);
        return e.key === "Backspace" || mw.event.isKeyCode(e, 8);
      },
      delete: function (e) {
          e = mw.event.get(e);
          return e.key === "Delete" || mw.event.isKeyCode(e, 46);
      }
    }
};














/***/ }),

/***/ "../userfiles/modules/microweber/api/core/libs.js":
/*!********************************************************!*\
  !*** ../userfiles/modules/microweber/api/core/libs.js ***!
  \********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
/***/ (() => {

mw.settings.libs = {
    jqueryui: [
        function () {
            mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.js');
            mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.css');
        }
    ],
    morris: ['morris.css', 'raphael.js', 'morris.js'],
    rangy: ['rangy-core.js', 'rangy-cssclassapplier.js', 'rangy-selectionsaverestore.js', 'rangy-serializer.js'],
    highlight: [

        'highlight.min.js',
        'highlight.min.css'

    ],
    bootstrap2: [
        function () {
            var v = mwd.querySelector('meta[name="viewport"]');
            if (v === null) {
                v = mwd.createElement('meta');
                v.name = "viewport";
            }
            v.content = "width=device-width, initial-scale=1.0";
            mwhead.appendChild(v);
        },
        'css/bootstrap.min.css',
        'css/bootstrap-responsive.min.css',
        'js/bootstrap.min.js'
    ],
    bootstrap3: [
        function () {
            mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
            var v = mwd.querySelector('meta[name="viewport"]');
            if (v === null) {
                v = mwd.createElement('meta');
                v.name = "viewport";
            }
            v.content = "width=device-width, initial-scale=1.0";
            mwhead.appendChild(v);
        },
        'css/bootstrap.min.css',
        'js/bootstrap.min.js'
    ],
    bootstrap4: [
        function () {
            mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/css/bootstrap.min.css');
            mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/popper.min.js');
            mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/bootstrap.min.js');
            mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');
        }
    ],
    microweber_ui: [
        function () {
            mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/ui/css/main.css');
            mw.require(mw.settings.libs_url + 'mw-ui/assets/ui/plugins/css/plugins.min.css');
            mw.require(mw.settings.libs_url + 'mw-ui/assets/ui/plugins/js/plugins.js');
        }


    ],
    mwui: [
        function () {
            // mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/main.css');
            // mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/css/plugins.min.css');
            // mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/mw.css');
            //The files above are added in default.css
            mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/js/plugins.js');
        }


    ],
    mwui_init: [
        function () {
            mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/js/ui.js');
        }
    ],
    flag_icons: [
        function () {
            mw.require(mw.settings.libs_url + 'flag-icon-css' + '/css/flag-icon.min.css');

        }
    ],
    font_awesome: [
        function () {
            mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');

        }
    ],
    font_awesome5: [
        function () {
            mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');

        }
    ],
    bxslider: [
        function () {
            mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.min.js', true);
            mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.css', true);

        }
    ],
    collapse_nav: [
        function () {
            mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.js', true);
            mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.css', true);

        }
    ],
    slick: [
        function () {
            mw.require(mw.settings.libs_url + 'slick' + '/slick.css', true);
            mw.moduleCSS(mw.settings.libs_url + 'slick' + '/slick-theme.css');
            mw.require(mw.settings.libs_url + 'slick' + '/slick.min.js', true);
        }
    ],
    bootstrap_datepicker: [
        function () {
            mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/css/bootstrap-datepicker3.css', true);
            mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/js/bootstrap-datepicker.js', true);
        }
    ],
    bootstrap_datetimepicker: [
        function () {
            mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/css/bootstrap-datetimepicker.min.css', true);
            mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/js/bootstrap-datetimepicker.min.js', true);
        }
    ],
    bootstrap3ns: [
        function () {

            //var bootstrap_enabled = (typeof $().modal == 'function');
            var bootstrap_enabled = (typeof $ != 'undefined' && typeof $.fn != 'undefined' && typeof $.fn.emulateTransitionEnd != 'undefined');

            if (!bootstrap_enabled) {
                mw.require(mw.settings.libs_url + 'bootstrap3' + '/js/bootstrap.min.js');
                //var bootstrap_enabled = (typeof $().modal == 'function');
                //if (bootstrap_enabled == false) {
                mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/bootstrap.min.css');
                mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
            }
            // }
        }
    ],
    bootstrap_select: [
        function () {
            //var bootstrap_enabled = (typeof $().modal == 'function');
            //if (!bootstrap_enabled == false) {
            mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/js/bootstrap-select.min.js');
            mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/css/bootstrap-select.min.css');
            //}
        }
    ],
    bootstrap_tags: [
        function () {

            // var bootstrap_enabled = (typeof $().modal == 'function');
            //if (!bootstrap_enabled == false) {
            mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.jquery.js');
            mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.bundle.min.js');
            mw.require(mw.settings.libs_url + 'typeahead' + '/bloodhound.js');
            mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.css');
            mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.js');
            //} else {
            //mw.log("You must load bootstrap to use bootstrap_tags");
            //}

        }
    ],
    chosen: [
        function () {
            mw.require(mw.settings.libs_url + 'chosen' + '/chosen.jquery.min.js');
            mw.require(mw.settings.libs_url + 'chosen' + '/chosen.min.css', true);
        }
    ],
    validation: [
        function () {
            mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/jquery.validationEngine.js');
            mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/languages/jquery.validationEngine-en.js');
            mw.require(mw.settings.libs_url + 'jquery_validation' + '/css/validationEngine.jquery.css');
        }
    ],

    fitty: [
        function () {
            mw.require(mw.settings.libs_url + 'fitty' + '/dist/fitty.min.js');
            /*$(document).ready(function () {
             fitty('.fitty-element');
             });*/
        }
    ],
    flatstrap3: [
        function () {
            var v = mwd.querySelector('meta[name="viewport"]');
            if (v === null) {
                v = mwd.createElement('meta');
                v.name = "viewport";
            }
            v.content = "width=device-width, initial-scale=1.0";
            mwhead.appendChild(v);
        },
        'css/bootstrap.min.css',
        'js/bootstrap.min.js'
    ],
    datepicker: [
        'datepicker.min.js',
        'datepicker.min.css'
    ],
    datetimepicker: [
        'jquery.datetimepicker.full.min.js',
        'jquery.datetimepicker.min.css'
    ],

    nestedSortable: [
        function () {
            mw.require(mw.settings.libs_url + 'nestedsortable' + '/jquery.mjs.nestedSortable.js');
        }
    ],
    colorpicker: [
        function () {
            mw.require(mw.settings.includes_url + 'api' + '/color.js');
            mw.require(mw.settings.libs_url + 'acolorpicker' + '/acolorpicker.js');
        }
    ],
    material_icons: [
        function () {
            mw.require(mw.settings.libs_url + 'material_icons' + '/material_icons.css');
        }
    ],
    materialDesignIcons: [
        function () {
            mw.require('css/fonts/materialdesignicons/css/materialdesignicons.min.css');
        }
    ],
    mw_icons_mind: [
        function () {
            mw.require('fonts/mw-icons-mind/line/style.css');
            mw.require('fonts/mw-icons-mind/solid/style.css');
        }
    ],
    apexcharts: [
        'apexcharts.min.js',
        'apexcharts.css'
    ]
};

mw.lib = {
    _required: [],
    require: function (name) {
        if (mw.lib._required.indexOf(name) !== -1) {
            return false;
        }
        mw.lib._required.push(name);
        if (typeof mw.settings.libs[name] === 'undefined') return false;
        if (mw.settings.libs[name].constructor !== [].constructor) return false;
        var path = mw.settings.libs_url + name + '/',
            arr = mw.settings.libs[name],
            l = arr.length,
            i = 0,
            c = 0;
        for (; i < l; i++) {
            (typeof arr[i] === 'string') ? mw.require(path + arr[i], true) : (typeof arr[i] === 'function') ? arr[i].call() : '';
        }
    },
    get: function (name, done, error) {
        if (mw.lib._required.indexOf(name) !== -1) {
            if (typeof done === 'function') {
                done.call();
            }
            return false;
        }

        if (typeof mw.settings.libs[name] === 'undefined') return false;
        if (mw.settings.libs[name].constructor !== [].constructor) return false;
        mw.lib._required.push(name);
        var path = mw.settings.libs_url + name + '/',
            arr = mw.settings.libs[name],
            l = arr.length,
            i = 0,
            c = 1;
        for (; i < l; i++) {
            var xhr = $.cachedScript(path + arr[i]);
            xhr.done(function () {
                c++;
                if (c === l) {
                    if (typeof done === 'function') {
                        done.call();
                    }
                }
            });
            xhr.fail(function (jqxhr, settings, exception) {

                if (typeof error === 'function') {
                    error.call(jqxhr, settings, exception);
                }

            });
        }
    }
};


/***/ }),

/***/ "../userfiles/modules/microweber/api/core/response.js":
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/core/response.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
/***/ (() => {

mw.response = function(form, data, messages_at_the_bottom){
    messages_at_the_bottom = messages_at_the_bottom || false;
    if(data == null  ||  typeof data == 'undefined' ){
        return false;
    }

    data = mw.tools.toJSON(data);
    if(typeof data === 'undefined'){
        return false;
    }

    if(typeof data.error !== 'undefined'){
        mw._response.error(form, data, messages_at_the_bottom);
        return false;
    }
    else if(typeof data.success !== 'undefined'){
        mw._response.success(form, data, messages_at_the_bottom);
        return true;
    }
    else if(typeof data.warning !== 'undefined'){
        mw._response.warning(form, data, messages_at_the_bottom);
        return false;
    }
    else{
        return false;
    }
};

mw._response = {
    error:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'error');
        mw._response.createHTML(data.error, err_holder);
    },
    success:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'success');
        mw._response.createHTML(data.success, err_holder);
    },
    warning:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'warning');
        mw._response.createHTML(data.warning, err_holder);
    },
    msgHolder : function(form, type, method){
        method = method || 'append';
        var err_holder = form.find(".mw-checkout-response:first");
        var err_holder2 = form.find(".alert:first");
        if(err_holder.length === 0){
            err_holder = err_holder2;
        }
        if(err_holder.length === 0){
            err_holder = mwd.createElement('div');
            form[method](err_holder);
        }

        var bootrap_error_type = 'default';
        if(type === 'error'){
            bootrap_error_type = 'danger';
        } else if(type === 'done'){
            bootrap_error_type = 'info';
        }


        $(err_holder).empty().attr("class", 'alert alert-' + type + ' alert-' + bootrap_error_type + ' ');
        return err_holder;
    },
    createHTML:function(data, holder){
        var i, html = "";


        if(typeof data === 'string'){
            html+= data.toString();
        }
        else{
            for( i in data){
                if(typeof data[i] === 'string'){
                    html+='<li>'+data[i]+'</li>';
                }
                else if(typeof data[i] === 'object'){
                    $.each(data[i], function(){
                        html+='<li>'+this+'</li>';
                    })
                }
            }
        }
        mw.$(holder).eq(0).append('<ul class="mw-error-list">'+html+'</ul>');
        mw.$(holder).eq(0).show();
    }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// startup
/******/ 	// Load entry module
/******/ 	__webpack_modules__["../userfiles/modules/microweber/api/core/components.js"]();
/******/ 	__webpack_modules__["../userfiles/modules/microweber/api/core/element.js"]();
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	__webpack_modules__["../userfiles/modules/microweber/api/core/events.js"]();
/******/ 	__webpack_modules__["../userfiles/modules/microweber/api/core/libs.js"]();
/******/ 	__webpack_modules__["../userfiles/modules/microweber/api/core/response.js"]();
/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29yZS9jb21wb25lbnRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9jb3JlL2VsZW1lbnQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2NvcmUvZXZlbnRzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9jb3JlL2xpYnMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2NvcmUvcmVzcG9uc2UuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrL3dlYnBhY2svc3RhcnR1cCJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1CQUFtQjtBQUNuQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBO0FBQ0Esa0NBQWtDO0FBQ2xDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtDQUFrQztBQUNsQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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Q0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckIsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsOEJBQThCLGdCQUFnQjtBQUM5QztBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7QUFFakIsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFF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TDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ3JZQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGVBQWU7QUFDZjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxFQUFFO0FBQ2hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxjQUFjLE9BQU87QUFDckI7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7O0FBRUE7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7QUMxU0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7OztVQ3pGQTtVQUNBO1VBQ0E7VUFDQTtVQUNBO1VBQ0E7VUFDQTtVQUNBIiwiZmlsZSI6ImNvcmUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgICAgbXcuY29tcG9uZW50cyA9IHtcbiAgICBfcmFuZ2VPbmNlOiBmYWxzZSxcbiAgICAncmFuZ2UnOiBmdW5jdGlvbihlbCl7XG4gICAgICAgIG13LmxpYi5yZXF1aXJlKCdqcXVlcnl1aScpO1xuICAgICAgICB2YXIgb3B0aW9ucyA9IHRoaXMuX29wdGlvbnMoZWwpO1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICByYW5nZTogJ21pbicsXG4gICAgICAgICAgICBhbmltYXRlOiBcImZhc3RcIlxuICAgICAgICB9O1xuICAgICAgICB2YXIgZXggPSB7fSwgcmVuZGVyID0gZWw7XG4gICAgICAgIGlmKGVsLm5vZGVOYW1lID09PSAnSU5QVVQnKXtcbiAgICAgICAgICAgIGVsLl9wYXVzZUNoYW5nZSA9IGZhbHNlO1xuICAgICAgICAgICAgZWwudHlwZSA9ICd0ZXh0JztcbiAgICAgICAgICAgIHJlbmRlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgJChlbCkucmVtb3ZlQ2xhc3MoJ213LXJhbmdlJyk7XG4gICAgICAgICAgICAkKHJlbmRlcikuYWRkQ2xhc3MoJ213LXJhbmdlJyk7XG4gICAgICAgICAgICAkKGVsKS5hZnRlcihyZW5kZXIpO1xuICAgICAgICAgICAgZXggPSB7XG4gICAgICAgICAgICAgICAgc2xpZGU6IGZ1bmN0aW9uKCBldmVudCwgdWkgKSB7XG4gICAgICAgICAgICAgICAgICAgIGVsLl9wYXVzZUNoYW5nZSA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgJChlbCkudmFsKHVpLnZhbHVlKS50cmlnZ2VyKCdjaGFuZ2UnKS50cmlnZ2VyKCdpbnB1dCcpO1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLl9wYXVzZUNoYW5nZSA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9LCA3OCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcblxuICAgICAgICB9XG4gICAgICAgIHZhciBzZXR0aW5ncyA9ICQuZXh0ZW5kKHt9LCBkZWZhdWx0cywgb3B0aW9ucywgZXgpO1xuICAgICAgICBpZihlbC5taW4pe1xuICAgICAgICAgICAgc2V0dGluZ3MubWluID0gcGFyc2VGbG9hdChlbC5taW4pO1xuICAgICAgICB9XG4gICAgICAgIGlmKGVsLm1heCl7XG4gICAgICAgICAgICBzZXR0aW5ncy5tYXggPSBwYXJzZUZsb2F0KGVsLm1heCk7XG4gICAgICAgIH1cbiAgICAgICAgaWYoZWwudmFsdWUpe1xuICAgICAgICAgICAgc2V0dGluZ3MudmFsdWUgPSBwYXJzZUZsb2F0KGVsLnZhbHVlKTtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKHJlbmRlcilcbiAgICAgICAgICAgIC5zbGlkZXIoc2V0dGluZ3MpXG4gICAgICAgICAgICAub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAkKGVsKS5vbignaW5wdXQnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgbXcuJChyZW5kZXIpLnNsaWRlciggXCJ2YWx1ZVwiLCB0aGlzLnZhbHVlICk7XG4gICAgICAgIH0pO1xuICAgICAgICBpZighbXcuY29tcG9uZW50cy5fcmFuZ2VPbmNlKSB7XG4gICAgICAgICAgICBtdy5jb21wb25lbnRzLl9yYW5nZU9uY2UgPSB0cnVlO1xuICAgICAgICAgICAgbXcuJChkb2N1bWVudC5ib2R5KS5vbignbW91c2V1cCB0b3VjaGVuZCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgbXcuJCgnLm13LXJhbmdlLmFjdGl2ZScpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfSxcbiAgICAnY29sb3ItcGlja2VyJzogZnVuY3Rpb24oZWwpe1xuICAgICAgICB2YXIgb3B0aW9ucyA9IHRoaXMuX29wdGlvbnMoZWwpO1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBwb3NpdGlvbjogJ2JvdHRvbS1jZW50ZXInXG4gICAgICAgIH07XG4gICAgICAgIHZhciBzZXR0aW5ncyA9ICQuZXh0ZW5kKHt9LCBkZWZhdWx0cywgb3B0aW9ucyk7XG4gICAgICAgIHZhciBuYXYgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgbmF2LmNsYXNzTmFtZSA9ICdtdy11aS1idG4tbmF2IG13LWNvbG9yLXBpY2tlci1ob2xkZXInO1xuICAgICAgICB2YXIgdmlldyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICB2aWV3LmNsYXNzTmFtZSA9ICdtdy11aS1idG4nO1xuICAgICAgICB2aWV3LmlubmVySFRNTCA9ICc8c3BhbiBjbGFzcz1cIm13LXVpLWJ0bi1pbWdcIj48L3NwYW4+JztcbiAgICAgICAgbmF2LmFwcGVuZENoaWxkKHZpZXcpO1xuICAgICAgICB2YXIgaW5wdXRFbDtcbiAgICAgICAgaWYoZWwubm9kZU5hbWUgPT09ICdJTlBVVCcpe1xuICAgICAgICAgICAgaW5wdXRFbCA9IGVsO1xuICAgICAgICAgICAgbXcuJChlbCkuYWRkQ2xhc3MoJ213LXVpLWZpZWxkJykuYWZ0ZXIobmF2KTtcbiAgICAgICAgICAgIG5hdi5hcHBlbmRDaGlsZChlbCk7XG4gICAgICAgICAgICBtdy4kKCcubXctdWktYnRuLWltZycsIHZpZXcpLmNzcyhcImJhY2tncm91bmQtY29sb3JcIiwgZWwudmFsdWUpO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5wdXRFbC5fdGltZSA9IG51bGw7XG4gICAgICAgIHZhciBwaWNrZXIgPSBtdy5jb2xvclBpY2tlcih7XG4gICAgICAgICAgICBlbGVtZW50OmlucHV0RWwsXG4gICAgICAgICAgICBwb3NpdGlvbjpzZXR0aW5ncy5wb3NpdGlvbixcbiAgICAgICAgICAgIG9uY2hhbmdlOmZ1bmN0aW9uKGNvbG9yKXtcbiAgICAgICAgICAgICAgICBtdy4kKCcubXctdWktYnRuLWltZycsIHZpZXcpLmNzcyhcImJhY2tncm91bmQtY29sb3JcIiwgY29sb3IpO1xuICAgICAgICAgICAgICAgIG13LiQoaW5wdXRFbCkudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKHZpZXcpLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICBwaWNrZXIudG9nZ2xlKCk7XG4gICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgJ2ZpbGUtdXBsb2FkZXInOmZ1bmN0aW9uKGVsKXtcbiAgICAgICAgdmFyIG9wdGlvbnMgPSB0aGlzLl9vcHRpb25zKGVsKTtcbiAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgZWxlbWVudDogZWxcbiAgICAgICAgfTtcbiAgICAgICAgdmFyIHNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcbiAgICAgICAgdmFyIGNoID0gbXcuJChlbCkuYXR0cihcIm9uY2hhbmdlXCIpO1xuXG4gICAgICAgIG13LmZpbGVXaW5kb3coe1xuICAgICAgICAgICAgdHlwZXM6J21lZGlhJyxcbiAgICAgICAgICAgIGNoYW5nZTpmdW5jdGlvbih1cmwpe1xuICAgICAgICAgICAgICAgIHRyeXtcbiAgICAgICAgICAgICAgICAgICAgZXZhbChjaCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGNhdGNoKGVycil7fVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgICdtb2R1bGVzLXRhYnMnOmZ1bmN0aW9uKGVsKXtcbiAgICAgICAgdmFyIG9wdGlvbnMgPSB0aGlzLl9vcHRpb25zKGVsKTtcbiAgICAgICAgb3B0aW9ucy5icmVha1BvaW50ID0gMTAwOyAvL21ha2VzIGFjY29yZGlvbiBpZiBsZXNzIHRoZW4gMTAwcHhcbiAgICAgICAgaWYod2luZG93LmxpdmVfZWRpdF9zaWRlYmFyKSB7XG4gICAgICAgICAgICBtdy4kKGVsKS5hZGRDbGFzcygnbXctYWNjb3JkaW9uLXdpbmRvdy1oZWlnaHQnKVxuICAgICAgICAgICAgb3B0aW9ucy5icmVha1BvaW50ID0gODAwOyAvL21ha2VzIGFjY29yZGlvbiBpZiBsZXNzIHRoZW4gODAwcHhcbiAgICAgICAgfVxuICAgICAgICB2YXIgYWNjb3JkaW9uID0gdGhpcy5hY2NvcmRpb24oZWwpO1xuICAgICAgICB2YXIgdGIgPSBuZXcgbXcudGFiQWNjb3JkaW9uKG9wdGlvbnMsIGFjY29yZGlvbik7XG4gICAgfSxcbiAgICAndGFiLWFjY29yZGlvbic6ZnVuY3Rpb24oZWwpe1xuICAgICAgIHZhciBvcHRpb25zID0gdGhpcy5fb3B0aW9ucyhlbCk7XG4gICAgICAgdmFyIGFjY29yZGlvbiA9IHRoaXMuYWNjb3JkaW9uKGVsKTtcbiAgICAgICB2YXIgdGIgPSBuZXcgbXcudGFiQWNjb3JkaW9uKG9wdGlvbnMsIGFjY29yZGlvbik7XG4gICAgfSxcbiAgICBhY2NvcmRpb246ZnVuY3Rpb24oZWwpe1xuICAgICAgICBpZighZWwgfHwgZWwuX2FjY29yZGlvbikgcmV0dXJuO1xuICAgICAgICBpZighJChlbCkuaXMoJzp2aXNpYmxlJykpe1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIG13LmNvbXBvbmVudHMuYWNjb3JkaW9uKGVsKTtcbiAgICAgICAgICAgIH0sIDc3NywgZWwpO1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGVsLl9hY2NvcmRpb24gPSB0cnVlO1xuICAgICAgICB2YXIgb3B0aW9ucyA9IHRoaXMuX29wdGlvbnMoZWwpO1xuICAgICAgICB2YXIgc2V0dGluZ3MgPSAkLmV4dGVuZChvcHRpb25zLCB7ZWxlbWVudDplbH0pXG4gICAgICAgIHZhciBhY2NvcmRpb24gPSBuZXcgbXcudWlBY2NvcmRpb24oc2V0dGluZ3MpO1xuICAgICAgICBpZigkKGVsKS5oYXNDbGFzcygnbXctYWNjb3JkaW9uLXdpbmRvdy1oZWlnaHQnKSl7XG4gICAgICAgICAgICBhY2NvcmRpb24uX3NldEhlaWdodCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgdmFyIG1heCA9ICBtdy4kKHdpbmRvdykuaGVpZ2h0KCkgLSAoYWNjb3JkaW9uLnJvb3Qub2Zmc2V0KCkudG9wIC0gbXcuJCh3aW5kb3cpLnNjcm9sbFRvcCgpKTtcbiAgICAgICAgICAgICAgICBhY2NvcmRpb24ucm9vdC5jc3MoJ2hlaWdodCcsIG1heCk7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRlbnRfbWF4ID0gbWF4IC0gKGFjY29yZGlvbi50aXRsZXMubGVuZ3RoICogYWNjb3JkaW9uLnRpdGxlcy5lcSgwKS5vdXRlckhlaWdodCgpKTtcbiAgICAgICAgICAgICAgICBhY2NvcmRpb24uY29udGVudHMuY3NzKCdoZWlnaHQnLCBjb250ZW50X21heCk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgYWNjb3JkaW9uLl9zZXRIZWlnaHQoKTtcbiAgICAgICAgICAgIG13LiQod2luZG93KS5vbignbG9hZCByZXNpemUnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGFjY29yZGlvbi5fc2V0SGVpZ2h0KCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmKHdpbmRvdyAhPT0gdG9wKXtcbiAgICAgICAgICAgICAgICBtdy4kKHRvcCkub24oJ2xvYWQgcmVzaXplJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgYWNjb3JkaW9uLl9zZXRIZWlnaHQoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZigkKGVsKS5oYXNDbGFzcygnbXctYWNjb3JkaW9uLWZ1bGwtaGVpZ2h0Jykpe1xuICAgICAgICAgICAgYWNjb3JkaW9uLl9zZXRIZWlnaHQgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHZhciBtYXggPSBNYXRoLm1pbigkKGVsKS5wYXJlbnQoKS5oZWlnaHQoKSwgbXcuJCh3aW5kb3cpLmhlaWdodCgpKTtcbiAgICAgICAgICAgICAgICBhY2NvcmRpb24ucm9vdC5jc3MoJ21heEhlaWdodCcsIG1heCk7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRlbnRfbWF4ID0gbWF4IC0gKGFjY29yZGlvbi50aXRsZXMubGVuZ3RoICogYWNjb3JkaW9uLnRpdGxlcy5lcSgwKS5vdXRlckhlaWdodCgpKTtcbiAgICAgICAgICAgICAgICBhY2NvcmRpb24uY29udGVudHMuY3NzKCdtYXhIZWlnaHQnLCBjb250ZW50X21heCk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgYWNjb3JkaW9uLl9zZXRIZWlnaHQoKTtcbiAgICAgICAgICAgIG13LiQod2luZG93KS5vbignbG9hZCByZXNpemUnLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIGFjY29yZGlvbi5fc2V0SGVpZ2h0KCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmKHdpbmRvdyAhPT0gdG9wKXtcbiAgICAgICAgICAgICAgICBtdy4kKHRvcCkub24oJ2xvYWQgcmVzaXplJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgYWNjb3JkaW9uLl9zZXRIZWlnaHQoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYWNjb3JkaW9uO1xuICAgIH0sXG4gICAgcG9zdFNlYXJjaDogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHZhciBkZWZhdWx0cyA9IHtrZXl3b3JkOiBlbC52YWx1ZSwgbGltaXQ6IDR9O1xuICAgICAgICBlbC5fc2V0VmFsdWUgPSBmdW5jdGlvbiAoaWQpIHtcbiAgICAgICAgICAgIG13LnRvb2xzLmFqYXhTZWFyY2godGhpcy5fc2V0dGluZ3MsIGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgdmFyIG9wdGlvbnMgPSBKU09OLnBhcnNlKGVsLmF0dHIoXCJkYXRhLW9wdGlvbnNcIikgfHwgJ3t9Jyk7XG4gICAgICAgIHNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zKTtcbiAgICAgICAgZWxbMF0uX3NldHRpbmdzID0gc2V0dGluZ3M7XG5cbiAgICAgICAgZWwud3JhcChcIjxkaXYgY2xhc3M9J213LWNvbXBvbmVudC1wb3N0LXNlYXJjaCc+PC9kaXY+XCIpO1xuICAgICAgICBlbC5hZnRlcihcIjx1bD48L3VsPlwiKTtcblxuICAgICAgICBlbC5vbihcImlucHV0IGZvY3VzIGJsdXJcIiwgZnVuY3Rpb24gKGV2ZW50KSB7XG5cbiAgICAgICAgICAgIGlmICghZWxbMF0uaXNfc2VhcmNoaW5nKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IGVsLnZhbCgpO1xuICAgICAgICAgICAgICAgIGlmIChldmVudC50eXBlID09ICdibHVyJykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLm5leHQoJ3VsJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChldmVudC50eXBlID09ICdmb2N1cycpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCQodGhpcykubmV4dCgndWwnKS5odG1sKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykubmV4dCgndWwnKS5zaG93KClcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsWzBdLmlzX3NlYXJjaGluZyA9IHRydWU7XG5cbiAgICAgICAgICAgICAgICB0aGlzLl9zZXR0aW5ncy5rZXl3b3JkID0gdGhpcy52YWx1ZTtcbiAgICAgICAgICAgICAgICBtdy4kKCd1bCcsIGVsKS5lbXB0eShcIlwiKVxuICAgICAgICAgICAgICAgIGVsLnBhcmVudCgpLmFkZENsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgICAgICBtdy50b29scy5hamF4U2VhcmNoKHRoaXMuX3NldHRpbmdzLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBsaXMgPSBbXTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGpzb24gPSB0aGlzO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKHZhciBpdGVtIGluIGpzb24pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBvYmogPSBqc29uW2l0ZW1dO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBvYmogPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGxpID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImxpXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxpLl92YWx1ZSA9IG9iajtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsaS5pbm5lckhUTUwgPSBvYmoudGl0bGU7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcuJChsaSkub24oXCJtb3VzZWRvd24gdG91Y2hzdGFydFwiLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsLnZhbCh0aGlzLl92YWx1ZS50aXRsZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxbMF0uX3ZhbHVlID0gdGhpcy5fdmFsdWU7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZWwudHJpZ2dlcigncG9zdFNlbGVjdGVkJywgW3RoaXMuX3ZhbHVlXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQodGhpcy5wYXJlbnROb2RlKS5oaWRlKClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGlzLnB1c2gobGkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsLnBhcmVudCgpLnJlbW92ZUNsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHVsID0gZWwucGFyZW50KCkuZmluZChcInVsXCIpO1xuICAgICAgICAgICAgICAgICAgICB1bC5lbXB0eSgpLmFwcGVuZChsaXMpLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgZWxbMF0uaXNfc2VhcmNoaW5nID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBlbC50cmlnZ2VyKFwicG9zdFNlYXJjaFJlYWR5XCIpO1xuICAgIH0sXG4gICAgX29wdGlvbnM6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICByZXR1cm4gbXcudG9vbHMuZWxlbWVudE9wdGlvbnMoZWwpO1xuICAgIH0sXG4gICAgX2luaXQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbXcuJCgnLm13LWZpZWxkIGlucHV0W3R5cGU9XCJyYW5nZVwiXScpLmFkZENsYXNzKCdtdy1yYW5nZScpO1xuICAgICAgICBtdy4kKCdbZGF0YS1td2NvbXBvbmVudF0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBjb21wb25lbnQgPSBtdy4kKHRoaXMpLmF0dHIoXCJkYXRhLW13Y29tcG9uZW50XCIpO1xuICAgICAgICAgICAgaWYgKG13LmNvbXBvbmVudHNbY29tcG9uZW50XSkge1xuICAgICAgICAgICAgICAgIG13LmNvbXBvbmVudHNbY29tcG9uZW50XSh0aGlzKTtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZUF0dHIoJ2RhdGEtbXdjb21wb25lbnQnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgJC5lYWNoKHRoaXMsIGZ1bmN0aW9uKGtleSl7XG4gICAgICAgICAgICBpZihrZXkuaW5kZXhPZignXycpID09PSAtMSl7XG4gICAgICAgICAgICAgICAgbXcuJCgnLm13LScra2V5KycsIG13LScra2V5KS5ub3QoXCIubXctY29tcG9uZW50LXJlYWR5XCIpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmFkZENsYXNzKCdtdy1jb21wb25lbnQtcmVhZHknKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuY29tcG9uZW50c1trZXldKHRoaXMpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG59O1xuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgbXcuY29tcG9uZW50cy5faW5pdCgpO1xufSk7XG5cbiQod2luZG93KS5vbignbG9hZCcsIGZ1bmN0aW9uICgpIHtcbiAgICBtdy5jb21wb25lbnRzLl9pbml0KCk7XG59KTtcblxuICAgIG13Lm9uKCdDb21wb25lbnRzTGF1bmNoJywgZnVuY3Rpb24gKCkge1xuICAgICAgICBtdy5jb21wb25lbnRzLl9pbml0KCk7XG4gICAgfSk7XG5cbiAgICBtdy5vbignbXdEaWFsb2dTaG93JywgZnVuY3Rpb24gKCkge1xuICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LmNvbXBvbmVudHMuX2luaXQoKTtcbiAgICAgICAgfSwgMTEwKTtcbiAgICB9KTtcblxuJCh3aW5kb3cpLm9uKCdhamF4U3RvcCcsIGZ1bmN0aW9uICgpIHtcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbXcuY29tcG9uZW50cy5faW5pdCgpO1xuICAgIH0sIDEwMCk7XG59KTtcblxubXcucmVnaXN0ZXJDb21wb25lbnQgPSBmdW5jdGlvbihuYW1lLCBmdW5jKXtcbiAgICBpZihtdy5jb21wb25lbnRzW25hbWVdKXtcbiAgICAgICAgY29uc29sZS53YXJuKCdDb21wb25lbnQgJyArIG5hbWUgKyAnIGFscmVhZHkgZXhpc3RzLicpO1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIG13LmNvbXBvbmVudHNbbmFtZV0gPSBmdW5jO1xufTtcbiIsIihmdW5jdGlvbigpe1xuXG4gICAgdmFyIE1XRWxlbWVudCA9IGZ1bmN0aW9uKG9wdGlvbnMsIHJvb3Qpe1xuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuXG5cbiAgICAgICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmNzcygnZGlzcGxheScsIHRoaXMuY3NzKCdkaXNwbGF5JykgPT09ICdub25lJyA/ICdibG9jaycgOiAnbm9uZScpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuX2FjdGl2ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLm5vZGVzW3RoaXMubm9kZXMubGVuZ3RoIC0gMV07XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5nZXQgPSBmdW5jdGlvbihzZWxlY3Rvciwgc2NvcGUpe1xuICAgICAgICAgICAgdGhpcy5ub2RlcyA9IChzY29wZSB8fCBkb2N1bWVudCkucXVlcnlTZWxlY3RvckFsbChzZWxlY3Rvcik7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lYWNoID0gZnVuY3Rpb24oY2Ipe1xuICAgICAgICAgICAgaWYodGhpcy5ub2Rlcykge1xuICAgICAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgdGhpcy5ub2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICBjYi5jYWxsKHRoaXMubm9kZXNbaV0sIGkpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSBpZih0aGlzLm5vZGUpIHtcbiAgICAgICAgICAgICAgICBjYi5jYWxsKHRoaXMubm9kZSwgMCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmVuY2Fwc3VsYXRlID0gZnVuY3Rpb24gKCkge1xuXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5jcmVhdGUgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdmFyIGVsID0gdGhpcy5kb2N1bWVudC5jcmVhdGVFbGVtZW50KHRoaXMuc2V0dGluZ3MudGFnKTtcbiAgICAgICAgICAgIHRoaXMubm9kZSA9IGVsO1xuXG4gICAgICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5lbmNhcHN1bGF0ZSkge1xuICAgICAgICAgICAgICAgIHZhciBtb2RlID0gdGhpcy5zZXR0aW5ncy5lbmNhcHN1bGF0ZSA9PT0gdHJ1ZSA/ICdvcGVuJyA6IHRoaXMuc2V0dGluZ3MuZW5jYXBzdWxhdGU7XG4gICAgICAgICAgICAgICAgZWwuYXR0YWNoU2hhZG93KHtcbiAgICAgICAgICAgICAgICAgICAgbW9kZTogbW9kZVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5ub2RlcyA9IFtlbF07XG4gICAgICAgICAgICBpZiAodGhpcy5zZXR0aW5ncy5jb250ZW50KSB7XG4gICAgICAgICAgICAgICAgaWYgKEFycmF5LmlzQXJyYXkodGhpcy5zZXR0aW5ncy5jb250ZW50KSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNldHRpbmdzLmNvbnRlbnQuZm9yRWFjaChmdW5jdGlvbiAoZWwpe1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuYXBwZW5kKGVsKTtcbiAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFwcGVuZCh0aGlzLnNldHRpbmdzLmNvbnRlbnQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuJG5vZGUgPSAkKGVsKTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl9zcGVjaWFsUHJvcHMgPSBmdW5jdGlvbihkdCwgdmFsKXtcbiAgICAgICAgICAgIGlmKGR0ID09PSAndG9vbHRpcCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLm5vZGUuZGF0YXNldFtkdF0gPSB2YWw7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5zZXRQcm9wcyA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdGhpcy5zZXR0aW5ncy5wcm9wcykge1xuICAgICAgICAgICAgICAgIGlmIChpID09PSAnZGF0YXNldCcpIHtcbiAgICAgICAgICAgICAgICAgICAgZm9yKHZhciBkdCBpbiB0aGlzLnNldHRpbmdzLnByb3BzW2ldKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5vZGUuZGF0YXNldFtkdF0gPSB0aGlzLnNldHRpbmdzLnByb3BzW2ldW2R0XTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoaSA9PT0gJ3N0eWxlJykge1xuICAgICAgICAgICAgICAgICAgICBmb3IodmFyIHN0IGluIHRoaXMuc2V0dGluZ3MucHJvcHNbaV0pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubm9kZS5zdHlsZVtzdF0gPSB0aGlzLnNldHRpbmdzLnByb3BzW2ldW3N0XTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB2YWwgPSB0aGlzLnNldHRpbmdzLnByb3BzW2ldO1xuICAgICAgICAgICAgICAgICAgICBpZighdGhpcy5fc3BlY2lhbFByb3BzKGksIHZhbCkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubm9kZVtpXSA9IHZhbDtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl9fID0ge1xuICAgICAgICAgICAgY3NzTnVtYmVyOiBbXG4gICAgICAgICAgICAgICAgJ2FuaW1hdGlvbkl0ZXJhdGlvbkNvdW50JyxcbiAgICAgICAgICAgICAgICAnY29sdW1uQ291bnQnLFxuICAgICAgICAgICAgICAgICdmaWxsT3BhY2l0eScsXG4gICAgICAgICAgICAgICAgJ2ZsZXhHcm93JyxcbiAgICAgICAgICAgICAgICAnZmxleFNocmluaycsXG4gICAgICAgICAgICAgICAgJ2ZvbnRXZWlnaHQnLFxuICAgICAgICAgICAgICAgICdncmlkQXJlYScsXG4gICAgICAgICAgICAgICAgJ2dyaWRDb2x1bW4nLFxuICAgICAgICAgICAgICAgICdncmlkQ29sdW1uRW5kJyxcbiAgICAgICAgICAgICAgICAnZ3JpZENvbHVtblN0YXJ0JyxcbiAgICAgICAgICAgICAgICAnZ3JpZFJvdycsXG4gICAgICAgICAgICAgICAgJ2dyaWRSb3dFbmQnLFxuICAgICAgICAgICAgICAgICdncmlkUm93U3RhcnQnLFxuICAgICAgICAgICAgICAgICdsaW5lSGVpZ2h0JyxcbiAgICAgICAgICAgICAgICAnb3BhY2l0eScsXG4gICAgICAgICAgICAgICAgJ29yZGVyJyxcbiAgICAgICAgICAgICAgICAnb3JwaGFucycsXG4gICAgICAgICAgICAgICAgJ3dpZG93cycsXG4gICAgICAgICAgICAgICAgJ3pJbmRleCcsXG4gICAgICAgICAgICAgICAgJ3pvb20nXG4gICAgICAgICAgICBdXG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5fbm9ybWFsaXplQ1NTVmFsdWUgPSBmdW5jdGlvbiAocHJvcCwgdmFsKSB7XG4gICAgICAgICAgICBpZih0eXBlb2YgdmFsID09PSAnbnVtYmVyJykge1xuICAgICAgICAgICAgICAgIGlmKHRoaXMuX18uY3NzTnVtYmVyLmluZGV4T2YocHJvcCkgPT09IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhbCA9IHZhbCArICdweCc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmNzcyA9IGZ1bmN0aW9uKGNzcywgdmFsKXtcbiAgICAgICAgICAgIGlmKHR5cGVvZiBjc3MgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICAgICAgaWYodHlwZW9mIHZhbCAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgICAgICAgICB2YXIgbnZhbCA9ICB0aGlzLl9ub3JtYWxpemVDU1NWYWx1ZShjc3MsIHZhbCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuc3R5bGVbY3NzXSA9IG52YWw7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmRvY3VtZW50LmRlZmF1bHRWaWV3LmdldENvbXB1dGVkU3R5bGUodGhpcy5ub2RlKVtjc3NdO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHR5cGVvZiBjc3MgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgZm9yICh2YXIgaSBpbiBjc3MpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ub2RlLnN0eWxlW2ldID0gdGhpcy5fbm9ybWFsaXplQ1NTVmFsdWUoaSwgY3NzW2ldKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmRhdGFzZXQgPSBmdW5jdGlvbihwcm9wLCB2YWwpe1xuICAgICAgICAgICAgaWYodHlwZW9mIHZhbCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5fYWN0aXZlKClbcHJvcF07XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLmVhY2goZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgdGhpcy5kYXRhc2V0W3Byb3BdID0gdmFsO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmF0dHIgPSBmdW5jdGlvbihwcm9wLCB2YWwpe1xuICAgICAgICAgICAgaWYodHlwZW9mIHZhbCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5fYWN0aXZlKClbcHJvcF07XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLmVhY2goZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgdGhpcy5zZXRBdHRyaWJ1dGUocHJvcCwgdmFsKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5wcm9wID0gZnVuY3Rpb24ocHJvcCwgdmFsKXtcbiAgICAgICAgICAgIHZhciBhY3RpdmUgPSB0aGlzLl9hY3RpdmUoKTtcbiAgICAgICAgICAgIGlmKHR5cGVvZiB2YWwgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGFjdGl2ZVtwcm9wXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKGFjdGl2ZVtwcm9wXSAhPT0gdmFsKXtcbiAgICAgICAgICAgICAgICBhY3RpdmVbcHJvcF0gPSB2YWw7XG4gICAgICAgICAgICAgICAgdGhpcy50cmlnZ2VyKCdwcm9wQ2hhbmdlJywgW3Byb3AsIHZhbF0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5oaWRlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICB0aGlzLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5zaG93ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICB0aGlzLnN0eWxlLmRpc3BsYXkgPSAnJztcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuZmluZCA9IGZ1bmN0aW9uIChzZWwpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IG13LmVsZW1lbnQoJyNyJyArIG5ldyBEYXRlKCkuZ2V0VGltZSgpKTtcbiAgICAgICAgICAgIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICB2YXIgYWxsID0gdGhpcy5xdWVyeVNlbGVjdG9yQWxsKHNlbCk7XG4gICAgICAgICAgICAgICAgZm9yKHZhciBpID0gMDsgaSA8IGFsbC5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICBpZihlbC5ub2Rlcy5pbmRleE9mKGFsbFtpXSkgPT09IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlbC5ub2Rlcy5wdXNoKGFsbFtpXSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmFkZENsYXNzID0gZnVuY3Rpb24gKGNscykge1xuICAgICAgICAgICAgIGNscyA9IGNscy50cmltKCkuc3BsaXQoJyAnKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgdmFyIG5vZGUgPSB0aGlzO1xuICAgICAgICAgICAgICAgIGNscy5mb3JFYWNoKGZ1bmN0aW9uIChzaW5nbGVDbGFzcyl7XG4gICAgICAgICAgICAgICAgICAgIG5vZGUuY2xhc3NMaXN0LmFkZChzaW5nbGVDbGFzcyk7XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudG9nZ2xlQ2xhc3MgPSBmdW5jdGlvbiAoY2xzKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIHRoaXMuY2xhc3NMaXN0LnRvZ2dsZShjbHMudHJpbSgpKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucmVtb3ZlQ2xhc3MgPSBmdW5jdGlvbiAoY2xzKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIHRoaXMuY2xhc3NMaXN0LnJlbW92ZShjbHMudHJpbSgpKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucmVtb3ZlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5lbXB0eSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLmh0bWwoJycpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaHRtbCA9IGZ1bmN0aW9uICh2YWwpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgdmFsID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLl9hY3RpdmUoKS5pbm5lckhUTUw7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIHRoaXMuaW5uZXJIVE1MID0gdmFsO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMudGV4dCA9IGZ1bmN0aW9uICh2YWwsIGNsZWFuKSB7XG4gICAgICAgICAgICBpZih0eXBlb2YgdmFsID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLm5vZGUudGV4dENvbnRlbnQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZih0eXBlb2YgY2xlYW4gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgY2xlYW4gPSB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGNsZWFuKSB7XG4gICAgICAgICAgICAgICAgdmFsID0gdGhpcy5kb2N1bWVudC5jcmVhdGVSYW5nZSgpLmNyZWF0ZUNvbnRleHR1YWxGcmFnbWVudCh2YWwpLnRleHRDb250ZW50O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5ub2RlLmlubmVySFRNTCA9IHZhbDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl9hc2RvbSA9IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2Ygb2JqID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmRvY3VtZW50LmNyZWF0ZVJhbmdlKCkuY3JlYXRlQ29udGV4dHVhbEZyYWdtZW50KG9iaik7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKG9iai5ub2RlKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gb2JqLm5vZGU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmIChvYmoubm9kZXMpe1xuICAgICAgICAgICAgICAgIHJldHVybiBvYmoubm9kZXNbb2JqLm5vZGVzLmxlbmd0aCAtIDFdO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gb2JqO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG5cbiAgICAgICAgdGhpcy53aWR0aCA9IGZ1bmN0aW9uICh2YWwpIHtcbiAgICAgICAgICAgIGlmKHZhbCkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmNzcygnd2lkdGgnLCB2YWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX2FjdGl2ZSgpLm9mZnNldFdpZHRoO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaGVpZ2h0ID0gZnVuY3Rpb24gKHZhbCkge1xuICAgICAgICAgICAgaWYodmFsKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuY3NzKCdoZWlnaHQnLCB2YWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX2FjdGl2ZSgpLm9mZnNldEhlaWdodDtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnBhcmVudCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHJldHVybiBtdy5lbGVtZW50KHRoaXMuX2FjdGl2ZSgpLnBhcmVudE5vZGUpO1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmFwcGVuZCA9IGZ1bmN0aW9uIChlbCkge1xuXG4gICAgICAgICAgICBpZiAoZWwpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVhY2goZnVuY3Rpb24gKCl7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYXBwZW5kKHNjb3BlLl9hc2RvbShlbCkpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5iZWZvcmUgPSBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIGlmIChlbCkge1xuICAgICAgICAgICAgICAgIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnROb2RlLmluc2VydEJlZm9yZShzY29wZS5fYXNkb20oZWwpLCB0aGlzKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWZ0ZXIgPSBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIGlmIChlbCkge1xuICAgICAgICAgICAgICAgIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnROb2RlLmluc2VydEJlZm9yZShzY29wZS5fYXNkb20oZWwpLCB0aGlzLm5leHRTaWJsaW5nKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnByZXBlbmQgPSBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIGlmIChlbCkge1xuICAgICAgICAgICAgICAgIHRoaXMuZWFjaChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wcmVwZW5kKHNjb3BlLl9hc2RvbShlbCkpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuX2Rpc2FibGVkID0gZmFsc2U7XG5cbiAgICAgICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KHRoaXMsIFwiZGlzYWJsZWRcIiwge1xuICAgICAgICAgICAgZ2V0IDogZnVuY3Rpb24gKCkgeyByZXR1cm4gdGhpcy5fZGlzYWJsZWQ7IH0sXG4gICAgICAgICAgICBzZXQgOiBmdW5jdGlvbiAodmFsdWUpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9kaXNhYmxlZCA9IHZhbHVlO1xuICAgICAgICAgICAgICAgIHRoaXMubm9kZS5kaXNhYmxlZCA9IHRoaXMuX2Rpc2FibGVkO1xuICAgICAgICAgICAgICAgIHRoaXMubm9kZS5kYXRhc2V0LmRpc2FibGVkID0gdGhpcy5fZGlzYWJsZWQ7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMudHJpZ2dlciA9IGZ1bmN0aW9uKGV2ZW50LCBkYXRhKXtcbiAgICAgICAgICAgIGRhdGEgPSBkYXRhIHx8IHt9O1xuICAgICAgICAgICAgdGhpcy5lYWNoKGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgIC8qdGhpcy5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChldmVudCwge1xuICAgICAgICAgICAgICAgICAgICBkZXRhaWw6IGRhdGEsXG4gICAgICAgICAgICAgICAgICAgIGNhbmNlbGFibGU6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgIGJ1YmJsZXM6IHRydWVcbiAgICAgICAgICAgICAgICB9KSk7Ki9cbiAgICAgICAgICAgICAgICBpZihzY29wZS5fb25bZXZlbnRdKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLl9vbltldmVudF0uZm9yRWFjaChmdW5jdGlvbihjYil7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYi5jYWxsKHRoaXMsIGV2ZW50LCBkYXRhKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmdldCA9IGZ1bmN0aW9uIChpKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5ub2Rlc1tpXTtcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl9vbiA9IHt9O1xuICAgICAgICB0aGlzLm9uID0gZnVuY3Rpb24oZXZlbnRzLCBjYil7XG4gICAgICAgICAgICBldmVudHMgPSBldmVudHMudHJpbSgpLnNwbGl0KCcgJyk7XG4gICAgICAgICAgICBldmVudHMuZm9yRWFjaChmdW5jdGlvbiAoZXYpIHtcbiAgICAgICAgICAgICAgICBpZighc2NvcGUuX29uW2V2XSkgeyAgc2NvcGUuX29uW2V2XSA9IFtdOyB9XG4gICAgICAgICAgICAgICAgc2NvcGUuX29uW2V2XS5wdXNoKGNiKTtcbiAgICAgICAgICAgICAgICBzY29wZS5lYWNoKGZ1bmN0aW9uICgpe1xuICAgICAgICAgICAgICAgICAgICAvKnRoaXMuYWRkRXZlbnRMaXN0ZW5lcihldiwgZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2IuY2FsbChzY29wZSwgZSwgZS5kZXRhaWwsIHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICB9LCBmYWxzZSk7Ki9cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5hZGRFdmVudExpc3RlbmVyKGV2LCBjYiwgZmFsc2UpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5pbml0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMubm9kZXMgPSBbXTtcbiAgICAgICAgICAgIHRoaXMucm9vdCA9IHJvb3QgfHwgZG9jdW1lbnQ7XG4gICAgICAgICAgICB0aGlzLl9hc0VsZW1lbnQgPSBmYWxzZTtcbiAgICAgICAgICAgIHRoaXMuZG9jdW1lbnQgPSAgKHRoaXMucm9vdC5ib2R5ID8gdGhpcy5yb290IDogdGhpcy5yb290Lm93bmVyRG9jdW1lbnQpO1xuXG4gICAgICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcblxuICAgICAgICAgICAgaWYob3B0aW9ucy5ub2RlTmFtZSAmJiBvcHRpb25zLm5vZGVUeXBlKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5ub2Rlcy5wdXNoKG9wdGlvbnMpO1xuICAgICAgICAgICAgICAgIHRoaXMubm9kZSA9IChvcHRpb25zKTtcbiAgICAgICAgICAgICAgICBvcHRpb25zID0ge307XG4gICAgICAgICAgICAgICAgdGhpcy5fYXNFbGVtZW50ID0gdHJ1ZTtcbiAgICAgICAgICAgIH0gZWxzZSBpZih0eXBlb2Ygb3B0aW9ucyA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICBpZihvcHRpb25zLmluZGV4T2YoJzwnKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ub2RlcyA9IEFycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKHRoaXMucm9vdC5xdWVyeVNlbGVjdG9yQWxsKG9wdGlvbnMpKTtcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9ucyA9IHt9O1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9hc0VsZW1lbnQgPSB0cnVlO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlbCA9IHRoaXMuX2FzZG9tKG9wdGlvbnMpO1xuXG4gICAgICAgICAgICAgICAgICAgIHRoaXMubm9kZXMgPSBbXS5zbGljZS5jYWxsKGVsLmNoaWxkcmVuKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fYXNFbGVtZW50ID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuXG4gICAgICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICAgICAgdGFnOiAnZGl2JyxcbiAgICAgICAgICAgICAgICBwcm9wczoge31cbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIG9wdGlvbnMpO1xuXG4gICAgICAgICAgICBpZih0aGlzLl9hc0VsZW1lbnQpIHJldHVybjtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlKCk7XG4gICAgICAgICAgICB0aGlzLnNldFByb3BzKCk7XG4gICAgICAgICB9O1xuICAgICAgICB0aGlzLmluaXQoKTtcbiAgICB9O1xuICAgIG13LmVsZW1lbnQgPSBmdW5jdGlvbihvcHRpb25zKXtcbiAgICAgICAgcmV0dXJuIG5ldyBNV0VsZW1lbnQob3B0aW9ucyk7XG4gICAgfTtcbiAgICBtdy5lbGVtZW50Lm1vZHVsZSA9IGZ1bmN0aW9uIChuYW1lLCBmdW5jKSB7XG4gICAgICAgIE1XRWxlbWVudC5wcm90b3R5cGVbbmFtZV0gPSBmdW5jO1xuICAgIH07XG5cbn0pKCk7XG4iLCJtdy5yZXF1aXJlKCd1cmwuanMnKTtcblxubXcuaGFzaCA9IGZ1bmN0aW9uKGIpeyByZXR1cm4gYiA9PT0gdW5kZWZpbmVkID8gd2luZG93LmxvY2F0aW9uLmhhc2ggOiB3aW5kb3cubG9jYXRpb24uaGFzaCA9IGI7IH07XG5cbm13Lm9uID0gZnVuY3Rpb24oZXZlbnROYW1lLCBjYWxsYmFjayl7XG4gICAgZXZlbnROYW1lID0gZXZlbnROYW1lLnRyaW0oKVxuICAgICQuZWFjaChldmVudE5hbWUuc3BsaXQoJyAnKSwgZnVuY3Rpb24oKXtcbiAgICAgICAgbXcuJChtdy5fb24uX2V2ZW50c1JlZ2lzdGVyKS5vbih0aGlzLnRvU3RyaW5nKCksIGNhbGxiYWNrKTtcbiAgICB9KTtcbn07XG5tdy50cmlnZ2VyID0gZnVuY3Rpb24oZXZlbnROYW1lLCBwYXJhbXNBcnJheSl7XG4gICAgcmV0dXJuIG13LiQoW213dywgbXcuX29uLl9ldmVudHNSZWdpc3Rlcl0pLnRyaWdnZXIoZXZlbnROYW1lLCBwYXJhbXNBcnJheSk7XG59O1xuXG5cbm13Ll9vbiA9IHtcbiAgX2V2ZW50c1JlZ2lzdGVyOnt9LFxuICBvbm1vZHVsZXMgOiB7fSxcbiAgbW9kdWxlUmVsb2FkIDogZnVuY3Rpb24oaWQsIGMsIHRyaWdnZXIpe1xuICAgICAgdmFyIGV4aXN0cztcbiAgICAgaWYodHJpZ2dlcil7XG4gICAgICAgICAgZXhpc3RzID0gdHlwZW9mIG13Lm9uLm9ubW9kdWxlc1tpZF0gIT09ICd1bmRlZmluZWQnO1xuICAgICAgICAgIGlmKGV4aXN0cyl7XG4gICAgICAgICAgICB2YXIgaSA9IDAsIGwgPSBtdy5vbi5vbm1vZHVsZXNbaWRdLmxlbmd0aDtcbiAgICAgICAgICAgIGZvciggOyBpIDwgbDsgaSsrKXtcbiAgICAgICAgICAgICAgIG13Lm9uLm9ubW9kdWxlc1tpZF1baV0uY2FsbChtd2QuZ2V0RWxlbWVudEJ5SWQoaWQpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgfVxuICAgICBpZihtdy5pcy5mdW5jKGMpKXtcbiAgICAgICBleGlzdHMgPSB0eXBlb2YgbXcub24ub25tb2R1bGVzW2lkXSAhPT0gJ3VuZGVmaW5lZCc7XG4gICAgICAgaWYoZXhpc3RzKXtcbiAgICAgICAgICBtdy5vbi5vbm1vZHVsZXNbaWRdLnB1c2goYyk7XG4gICAgICAgfVxuICAgICAgIGVsc2V7XG4gICAgICAgICBtdy5vbi5vbm1vZHVsZXNbaWRdID0gW2NdO1xuICAgICAgIH1cbiAgICAgfVxuICAgICBlbHNlIGlmKGM9PT0nb2ZmJyl7XG4gICAgICAgIGV4aXN0cyA9IHR5cGVvZiBtdy5vbi5vbm1vZHVsZXNbaWRdICE9PSAndW5kZWZpbmVkJztcbiAgICAgICAgaWYoZXhpc3RzKXtcbiAgICAgICAgICBtdy5vbi5vbm1vZHVsZXNbaWRdID0gW107XG4gICAgICAgIH1cbiAgICAgfVxuICB9LFxuICBfaGFzaHJlYyA6IHt9LFxuICBfaGFzaHBhcmFtcyA6IHRoaXMuX2hhc2hwYXJhbXMgfHwgW10sXG4gIF9oYXNocGFyYW1fZnVuY3MgOiBbXSxcbiAgaGFzaFBhcmFtIDogZnVuY3Rpb24ocGFyYW0sIGNhbGxiYWNrLCB0cmlnZ2VyLCBpc01hbnVhbCl7XG5cbiAgICBpZihpc01hbnVhbCl7XG4gICAgICAgIHZhciBpbmRleCA9IG13Lm9uLl9oYXNocGFyYW1zLmluZGV4T2YocGFyYW0pO1xuICAgICAgICBpZiAobXcub24uX2hhc2hwYXJhbV9mdW5jc1tpbmRleF0gIT09IHVuZGVmaW5lZCl7XG4gICAgICAgICAgbXcub24uX2hhc2hwYXJhbV9mdW5jc1tpbmRleF0uY2FsbChmYWxzZSk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbiAgICBpZih0cmlnZ2VyID09PSB0cnVlKXtcbiAgICAgICAgdmFyIGluZGV4ID0gbXcub24uX2hhc2hwYXJhbXMuaW5kZXhPZihwYXJhbSk7XG5cbiAgICAgICAgaWYoaW5kZXggIT09IC0xKXtcbiAgICAgICAgICB2YXIgaGFzaCA9IG13Lmhhc2goKTtcbiAgICAgICAgICB2YXIgcGFyYW1zID0gbXcudXJsLmdldEhhc2hQYXJhbXMoaGFzaCk7XG5cbiAgICAgICAgICBpZih0eXBlb2YgcGFyYW1zW3BhcmFtXSA9PT0gJ3N0cmluZycgJiYgbXcub24uX2hhc2hwYXJhbV9mdW5jc1tpbmRleF0gIT09IHVuZGVmaW5lZCl7XG4gICAgICAgICAgICAgIG13Lm9uLl9oYXNocGFyYW1fZnVuY3NbaW5kZXhdLmNhbGwoZGVjb2RlVVJJQ29tcG9uZW50KHBhcmFtc1twYXJhbV0pKTtcblxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cbiAgICBlbHNle1xuICAgICAgICBtdy5vbi5faGFzaHBhcmFtcy5wdXNoKHBhcmFtKTtcbiAgICAgICAgbXcub24uX2hhc2hwYXJhbV9mdW5jcy5wdXNoKGNhbGxiYWNrKTtcbiAgICB9XG59LFxuaGFzaFBhcmFtRXZlbnRJbml0OmZ1bmN0aW9uKCl7XG4gIHZhciBoYXNoID0gbXcuaGFzaCgpO1xuICB2YXIgcGFyYW1zID0gbXcudXJsLmdldEhhc2hQYXJhbXMoaGFzaCk7XG5cbiAgaWYoaGFzaD09PScnIHx8IGhhc2g9PT0nIycgfHwgaGFzaCA9PT0nIz8nKXtcbiAgICB2YXIgbGVuID0gbXcub24uX2hhc2hwYXJhbXMubGVuZ3RoLCBpPTA7XG4gICAgZm9yKCA7IGkgPCBsZW47IGkrKyl7XG4gICAgICAgIG13Lm9uLmhhc2hQYXJhbShtdy5vbi5faGFzaHBhcmFtc1tpXSwgXCJcIiwgdHJ1ZSk7XG4gICAgfVxuICB9XG4gIGVsc2V7XG4gICAgZm9yKHZhciB4IGluIHBhcmFtcyl7XG4gICAgICAgIGlmKHBhcmFtc1t4XSAhPT0gbXcub24uX2hhc2hyZWNbeF0gfHwgdHlwZW9mIG13Lm9uLl9oYXNocmVjW3hdID09PSAndW5kZWZpbmVkJyl7XG4gICAgICAgICAgICBtdy5vbi5oYXNoUGFyYW0oeCwgXCJcIiwgdHJ1ZSk7XG4gICAgICAgIH1cbiAgICB9XG4gIH1cblxuICBtdy5vbi5faGFzaHJlYyA9IHBhcmFtcztcbn0sXG5ET01DaGFuZ2VQYXVzZTpmYWxzZSxcbkRPTUNoYW5nZVRpbWU6MTUwMCxcbkRPTUNoYW5nZTpmdW5jdGlvbihlbGVtZW50LCBjYWxsYmFjaywgYXR0ciwgYSl7XG4gICAgYXR0ciA9IGF0dHIgfHwgZmFsc2U7XG4gICAgYSA9IGEgfHwgZmFsc2U7XG5cbiAgICBlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJpbnB1dFwiLCBmdW5jdGlvbihlKXtcbiAgICAgICAgaWYoICFtdy5vbi5ET01DaGFuZ2VQYXVzZSApIHtcbiAgICAgICAgICAgIGlmKCFhKXtcbiAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKHRoaXMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICBjbGVhclRpbWVvdXQoZWxlbWVudC5faW50KTtcbiAgICAgICAgICAgICAgICBlbGVtZW50Ll9pbnQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgfSwgbXcub24uRE9NQ2hhbmdlVGltZSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfVxuICAgIH0sIGZhbHNlKTtcblxuICAgIHZhciBNdXRhdGlvbk9ic2VydmVyID0gd2luZG93Lk11dGF0aW9uT2JzZXJ2ZXIgfHwgd2luZG93LldlYktpdE11dGF0aW9uT2JzZXJ2ZXIgfHwgd2luZG93Lk1vek11dGF0aW9uT2JzZXJ2ZXI7XG5cbiAgICBpZih0eXBlb2YgTXV0YXRpb25PYnNlcnZlciA9PT0gJ2Z1bmN0aW9uJyl7XG4gICAgICAgIHZhciBvYnNlcnZlciA9IG5ldyBNdXRhdGlvbk9ic2VydmVyKGZ1bmN0aW9uKG11dGF0aW9ucykge1xuICAgICAgICAgIG11dGF0aW9ucy5mb3JFYWNoKGZ1bmN0aW9uKG11dGF0aW9uKXtcbiAgICAgICAgICAgIGlmKCAhbXcub24uRE9NQ2hhbmdlUGF1c2UgKSB7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChtdXRhdGlvbi50YXJnZXQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICAgICAgdmFyIGNvbmZpZyA9IHsgYXR0cmlidXRlczogYXR0ciwgY2hpbGRMaXN0OiB0cnVlLCBjaGFyYWN0ZXJEYXRhOiB0cnVlIH07XG4gICAgICAgIG9ic2VydmVyLm9ic2VydmUoZWxlbWVudCwgY29uZmlnKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICBlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01DaGFyYWN0ZXJEYXRhTW9kaWZpZWRcIiwgZnVuY3Rpb24oZSl7XG4gICAgICAgICAgICBpZiggIW13Lm9uLkRPTUNoYW5nZVBhdXNlICkge1xuICAgICAgICAgICAgICAgIGlmKCFhKXtcbiAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCh0aGlzKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KGVsZW1lbnQuX2ludCk7XG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQuX2ludCA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgICAgIH0sIG13Lm9uLkRPTUNoYW5nZVRpbWUpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfVxuICAgICAgICB9LCBmYWxzZSk7XG4gICAgICAgIGVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIkRPTU5vZGVJbnNlcnRlZFwiLCBmdW5jdGlvbihlKXtcblxuICAgICAgICAgICAgaWYoLyptdy50b29scy5oYXNDbGFzcyhlLnRhcmdldCwgJ2VsZW1lbnQnKSB8fCAqL213LnRvb2xzLmhhc0NsYXNzKGUudGFyZ2V0LCAnbW9kdWxlJykgfHwgbXcudG9vbHMuaGFzUGFyZW50c1dpdGhDbGFzcyhlLnRhcmdldCwgJ21vZHVsZScpKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiggIW13Lm9uLkRPTUNoYW5nZVBhdXNlICkge1xuICAgICAgICAgICAgICAgIGlmKCFhKXtcbiAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCh0aGlzKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KGVsZW1lbnQuX2ludCk7XG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQuX2ludCA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZWxlbWVudCk7XG4gICAgICAgICAgICAgICAgICAgIH0sIG13Lm9uLkRPTUNoYW5nZVRpbWUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSwgZmFsc2UpO1xuXG4gICAgICAgIGlmKGF0dHIpe1xuICAgICAgICAgICAgZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKFwiRE9NQXR0ck1vZGlmaWVkXCIsIGZ1bmN0aW9uKGUpe1xuXG4gICAgICAgICAgICAgICAgdmFyIGF0dHIgPSBlLmF0dHJOYW1lO1xuICAgICAgICAgICAgICAgIGlmKGF0dHIgIT09IFwiY29udGVudGVkaXRhYmxlXCIpe1xuICAgICAgICAgICAgICAgICAgICBpZiggIW13Lm9uLkRPTUNoYW5nZVBhdXNlICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoIWEpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dChlbGVtZW50Ll9pbnQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVsZW1lbnQuX2ludCA9IHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChlbGVtZW50KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCBtdy5vbi5ET01DaGFuZ2VUaW1lKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0sIGZhbHNlKTtcbiAgICAgICAgfVxuICAgIH1cblxuIH0sXG4gc3RvcFdyaXRpbmc6ZnVuY3Rpb24oZWwsIGMpe1xuICAgIGlmKGVsID09PSBudWxsIHx8IHR5cGVvZiBlbCA9PT0gJ3VuZGVmaW5lZCcpeyByZXR1cm4gZmFsc2U7IH1cbiAgICBpZighZWwub25zdG9wV3JpdGluZyl7XG4gICAgICBlbC5vbnN0b3BXcml0aW5nID0gbnVsbDtcbiAgICB9XG4gICAgY2xlYXJUaW1lb3V0KGVsLm9uc3RvcFdyaXRpbmcpO1xuICAgIGVsLm9uc3RvcFdyaXRpbmcgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgIGMuY2FsbChlbCk7XG4gICAgfSwgNDAwKTtcbiB9LFxuIHNjcm9sbEJhck9uQm90dG9tIDogZnVuY3Rpb24ob2JqLCBkaXN0YW5jZSwgY2FsbGJhY2spe1xuICAgIGlmKHR5cGVvZiBvYmogPT09ICdmdW5jdGlvbicpe1xuICAgICAgIGNhbGxiYWNrID0gb2JqO1xuICAgICAgIG9iaiA9ICB3aW5kb3c7XG4gICAgICAgZGlzdGFuY2UgPSAwO1xuICAgIH1cbiAgICBpZih0eXBlb2YgZGlzdGFuY2UgPT09ICdmdW5jdGlvbicpe1xuICAgICAgY2FsbGJhY2sgPSBkaXN0YW5jZTtcbiAgICAgIGRpc3RhbmNlID0gMDtcbiAgICB9XG4gICAgb2JqLl9wYXVzZUNhbGxiYWNrID0gZmFsc2U7XG4gICAgb2JqLnBhdXNlU2Nyb2xsQ2FsbGJhY2sgPSBmdW5jdGlvbigpeyBvYmouX3BhdXNlQ2FsbGJhY2sgPSB0cnVlO31cbiAgICBvYmouY29udGludWVTY3JvbGxDYWxsYmFjayA9IGZ1bmN0aW9uKCl7IG9iai5fcGF1c2VDYWxsYmFjayA9IGZhbHNlO31cbiAgICBtdy4kKG9iaikuc2Nyb2xsKGZ1bmN0aW9uKGUpe1xuICAgICAgdmFyIGggPSBvYmogPT09IHdpbmRvdyA/IG13ZC5ib2R5LnNjcm9sbEhlaWdodCA6IG9iai5zY3JvbGxIZWlnaHQ7XG4gICAgICB2YXIgY2FsYyA9IGggLSBtdy4kKG9iaikuc2Nyb2xsVG9wKCkgLSBtdy4kKG9iaikuaGVpZ2h0KCk7XG4gICAgICBpZihjYWxjIDw9IGRpc3RhbmNlICYmICFvYmouX3BhdXNlQ2FsbGJhY2spe1xuICAgICAgICBjYWxsYmFjay5jYWxsKG9iaik7XG4gICAgICB9XG4gICAgfSk7XG4gIH0sXG4gIHRyaXBsZUNsaWNrIDogZnVuY3Rpb24oZWwsIGNhbGxiYWNrKXtcbiAgICAgIHZhciB0LCB0aW1lb3V0ID0gMTk5O1xuICAgICAgZWwgPSBlbCB8fCB3aW5kb3c7XG4gICAgICBlbC5hZGRFdmVudExpc3RlbmVyKFwiZGJsY2xpY2tcIiwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgIHQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgdCA9IG51bGw7XG4gICAgICAgICAgfSwgdGltZW91dCk7XG4gICAgICB9KTtcbiAgICAgIGVsLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgIGlmICh0KSB7XG4gICAgICAgICAgICAgIGNsZWFyVGltZW91dCh0KTtcbiAgICAgICAgICAgICAgdCA9IG51bGw7XG4gICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZWwsIGUudGFyZ2V0KTtcbiAgICAgICAgICB9XG4gICAgICB9KTtcbiAgfSxcbiAgdHJhbnNpdGlvbkVuZDpmdW5jdGlvbihlbCxjYWxsYmFjayl7XG4gICAgbXcuJChlbCkuYmluZCgnd2Via2l0VHJhbnNpdGlvbkVuZCB0cmFuc2l0aW9uZW5kIG1zVHJhbnNpdGlvbkVuZCBvVHJhbnNpdGlvbkVuZCBvdHJhbnNpdGlvbmVuZCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgIGNhbGxiYWNrLmNhbGwoZWwpO1xuICAgIH0pO1xuICB9LFxuICBvbmVzOnsgfSxcbiAgb25lOmZ1bmN0aW9uKG5hbWUsIGMsIHRyaWdnZXIsIGlzRG9uZSl7XG4gICAgaWYodHJpZ2dlciAhPT0gdHJ1ZSl7XG4gICAgICBpZihtdy5vbi5vbmVzW25hbWVdID09PSB1bmRlZmluZWQpe1xuICAgICAgICAgbXcub24ub25lc1tuYW1lXSA9IFtjXVxuICAgICAgfVxuICAgICAgZWxzZXtcbiAgICAgICAgIG13Lm9uLm9uZXNbbmFtZV0ucHVzaChjKTtcbiAgICAgIH1cbiAgICB9XG4gICAgZWxzZXtcbiAgICAgICBpZihtdy5vbi5vbmVzW25hbWVdICE9PSB1bmRlZmluZWQpe1xuICAgICAgICAgIHZhciBpPTAsIGwgPSBtdy5vbi5vbmVzW25hbWVdLmxlbmd0aDtcbiAgICAgICAgICBmb3IoIDsgaTxsOyBpKyspe1xuICAgICAgICAgICAgICBpZihpc0RvbmUgPT09IHRydWUpe1xuICAgICAgICAgICAgICAgIG13Lm9uLm9uZXNbbmFtZV1baV0uY2FsbCgncmVhZHknLCAncmVhZHknKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgIG13Lm9uLm9uZXNbbmFtZV1baV0uY2FsbCgnc3RhcnQnLCAnc3RhcnQnKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICB9XG4gICAgfVxuICB9LFxuICB1c2VySXRlcmFjdGlvbkluaXRSZWdpc3RlcjogbmV3IERhdGUoKS5nZXRUaW1lKCksXG4gIHVzZXJJdGVyYWN0aW9uSW5pdDogZnVuY3Rpb24oKXtcbiAgICAgIHZhciBtYXggPSAzNzg7XG4gICAgICBtdy4kKG13ZCkub24oJ21vdXNlbW92ZSB0b3VjaHN0YXJ0IGNsaWNrIGtleWRvd24gcmVzaXplIGFqYXhTdG9wJywgZnVuY3Rpb24oKXtcbiAgICAgICAgICB2YXIgdGltZSA9IG5ldyBEYXRlKCkuZ2V0VGltZSgpO1xuICAgICAgICAgIGlmKCh0aW1lIC0gbXcuX29uLnVzZXJJdGVyYWN0aW9uSW5pdFJlZ2lzdGVyKSA+IG1heCl7XG4gICAgICAgICAgICAgIG13Ll9vbi51c2VySXRlcmFjdGlvbkluaXRSZWdpc3RlciA9IHRpbWU7XG4gICAgICAgICAgICAgIG13LnRyaWdnZXIoJ1VzZXJJbnRlcmFjdGlvbicpO1xuICAgICAgICAgIH1cbiAgICAgIH0pO1xuICB9XG59O1xuXG5mb3IodmFyIHggaW4gbXcuX29uKSBtdy5vblt4XSA9IG13Ll9vblt4XTtcblxuXG5cbm13Lmhhc2hIaXN0b3J5ID0gW3dpbmRvdy5sb2NhdGlvbi5oYXNoXVxuXG5tdy5wcmV2SGFzaCA9IGZ1bmN0aW9uKCl7XG4gIHZhciBwcmV2ID0gbXcuaGFzaEhpc3RvcnlbbXcuaGFzaEhpc3RvcnkubGVuZ3RoIC0gMl07XG4gIHJldHVybiBwcmV2ICE9PSB1bmRlZmluZWQgPyBwcmV2IDogJyc7XG59O1xuXG5cblxuJCh3aW5kb3cpLm9uKFwiaGFzaGNoYW5nZSBsb2FkXCIsIGZ1bmN0aW9uKGV2ZW50KXtcbiAgICBpZihldmVudC50eXBlID09PSAnbG9hZCcpe1xuICAgICAgICBtdy5fb24udXNlckl0ZXJhY3Rpb25Jbml0KCk7XG4gICAgfVxuXG4gICAgbXcub24uaGFzaFBhcmFtRXZlbnRJbml0KCk7XG5cbiAgIHZhciBoYXNoID0gIG13Lmhhc2goKTtcblxuICAgdmFyIGlzTVdIYXNoID0gaGFzaC5yZXBsYWNlKC9cXCMvZywgJycpLmluZGV4T2YoJ213QCcpID09PSAwO1xuICAgaWYgKGlzTVdIYXNoKSB7XG4gICAgICAgdmFyIE1XSGFzaCA9IGhhc2gucmVwbGFjZSgvXFwjL2csICcnKS5yZXBsYWNlKCdtd0AnLCAnJyk7XG4gICAgICAgdmFyIGVsID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoTVdIYXNoKTtcbiAgICAgICBpZihlbCkge1xuICAgICAgICAgICBtdy50b29scy5zY3JvbGxUbyhlbCk7XG4gICAgICAgfVxuICAgfVxuICAgaWYoaGFzaC5jb250YWlucyhcInNob3dwb3N0c2NhdFwiKSl7XG4gICAgICBtdy4kKFwiaHRtbFwiKS5hZGRDbGFzcyhcInNob3dwb3N0c2NhdFwiKTtcbiAgIH1cbiAgIGVsc2V7XG4gICAgICBtdy4kKFwiaHRtbFwiKS5yZW1vdmVDbGFzcyhcInNob3dwb3N0c2NhdFwiKTtcbiAgIH1cblxuXG4gICBpZiAoZXZlbnQudHlwZSA9PT0gJ2hhc2hjaGFuZ2UnKSB7XG4gICAgIG13Lmhhc2hIaXN0b3J5LnB1c2gobXcuaGFzaCgpKTtcbiAgICAgdmFyIHNpemUgPSBtdy5oYXNoSGlzdG9yeS5sZW5ndGg7XG4gICAgIHZhciBjaGFuZ2VzID0gbXcudXJsLndoaWNoSGFzaFBhcmFtc0hhc0JlZW5SZW1vdmVkKG13Lmhhc2hIaXN0b3J5W3NpemUtMV0sIG13Lmhhc2hIaXN0b3J5W3NpemUtMl0pLCBsPWNoYW5nZXMubGVuZ3RoLCBpPTA7XG4gICAgIGlmIChsPjApIHtcbiAgICAgICBmb3IoIDsgaSA8IGw7IGkrKyApe1xuICAgICAgICAgIG13Lm9uLmhhc2hQYXJhbShjaGFuZ2VzW2ldLCBcIlwiLCB0cnVlLCB0cnVlKTtcbiAgICAgICB9XG4gICAgIH1cbiAgIH1cbn0pO1xuXG5cbm13LmV2ZW50ID0ge1xuICAgIHdpbmRvd0xlYXZlOiBmdW5jdGlvbihjKSB7XG4gICAgICBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignbW91c2VvdXQnLCBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgaWYgKCFlLnJlbGF0ZWRUYXJnZXQgJiYgIWUudG9FbGVtZW50ICYmIGMpIHtcbiAgICAgICAgICAgICAgYy5jYWxsKGRvY3VtZW50LmJvZHksIGUpO1xuICAgICAgICAgIH1cbiAgICAgIH0pO1xuICAgIH0sXG4gICAgY2FuY2VsOmZ1bmN0aW9uKGUsIHByZXZlbnQpe1xuICAgIHByZXZlbnQgPT09IHRydWUgPyBlLnByZXZlbnREZWZhdWx0KCkgOiAnJztcbiAgICBlLmNhbmNlbEJ1YmJsZSA9IHRydWU7XG4gICAgaWYgKGUuc3RvcFByb3BhZ2F0aW9uKSBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgIH0sXG4gICAga2V5OmZ1bmN0aW9uKGUsa2V5KXtcbiAgICAgICAgcmV0dXJuIChlLmtleUNvZGUgPT09IHBhcnNlRmxvYXQoa2V5KSk7XG4gICAgfSxcbiAgICBwYWdlOiBmdW5jdGlvbiAoZSkge1xuICAgICAgZSA9IGUub3JpZ2luYWxFdmVudCB8fCBlO1xuICAgICAgaWYgKGUudHlwZS5pbmRleE9mKCd0b3VjaCcpICE9PSAwKSB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICB4OiBlLnBhZ2VYLFxuICAgICAgICAgICAgeTogZS5wYWdlWVxuICAgICAgICB9O1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICB4OiBlLmNoYW5nZWRUb3VjaGVzWzBdLnBhZ2VYLFxuICAgICAgICAgICAgICB5OiBlLmNoYW5nZWRUb3VjaGVzWzBdLnBhZ2VZXG4gICAgICAgICAgfTtcbiAgICAgIH1cbiAgICB9LFxuICAgIHRhcmdldElzRmllbGQ6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZSA9IGUub3JpZ2luYWxFdmVudCB8fCBlO1xuICAgICAgICB2YXIgdCA9IGUudGFyZ2V0O1xuICAgICAgICByZXR1cm4gdC5ub2RlTmFtZSA9PT0gJ0lOUFVUJyB8fFxuICAgICAgICAgICAgdC5ub2RlTmFtZSA9PT0gJ3RleHRhcmVhJyB8fFxuICAgICAgICAgICAgdC5ub2RlTmFtZSA9PT0gJ3NlbGVjdCc7XG4gICAgfSxcbiAgICBnZXQ6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgcmV0dXJuIGUub3JpZ2luYWxFdmVudCB8fCBlO1xuICAgIH0sXG4gICAga2V5Q29kZTogZnVuY3Rpb24oZSkge1xuICAgICAgICBlID0gbXcuZXZlbnQuZ2V0KGUpO1xuICAgICAgICByZXR1cm4gZS5rZXlDb2RlIHx8IGUud2hpY2g7XG4gICAgfSxcbiAgICBpc0tleUNvZGU6IGZ1bmN0aW9uKGUsIGNvZGUpe1xuICAgICAgICByZXR1cm4gdGhpcy5rZXlDb2RlKGUpID09PSBjb2RlO1xuICAgIH0sXG4gICAgaXM6IHtcbiAgICAgIGVudGVyOiBmdW5jdGlvbiAoZSkge1xuICAgICAgICBlID0gbXcuZXZlbnQuZ2V0KGUpO1xuICAgICAgICByZXR1cm4gZS5rZXkgPT09IFwiRW50ZXJcIiB8fCBtdy5ldmVudC5pc0tleUNvZGUoZSwgMTMpO1xuICAgICAgfSxcbiAgICAgIGVzY2FwZTogZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICBlID0gbXcuZXZlbnQuZ2V0KGUpO1xuICAgICAgICAgIHJldHVybiBlLmtleSA9PT0gXCJFc2NhcGVcIiB8fCBtdy5ldmVudC5pc0tleUNvZGUoZSwgMjcpO1xuICAgICAgfSxcbiAgICAgIGJhY2tTcGFjZSA6IGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUgPSBtdy5ldmVudC5nZXQoZSk7XG4gICAgICAgIHJldHVybiBlLmtleSA9PT0gXCJCYWNrc3BhY2VcIiB8fCBtdy5ldmVudC5pc0tleUNvZGUoZSwgOCk7XG4gICAgICB9LFxuICAgICAgZGVsZXRlOiBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgIGUgPSBtdy5ldmVudC5nZXQoZSk7XG4gICAgICAgICAgcmV0dXJuIGUua2V5ID09PSBcIkRlbGV0ZVwiIHx8IG13LmV2ZW50LmlzS2V5Q29kZShlLCA0Nik7XG4gICAgICB9XG4gICAgfVxufTtcblxuXG5cblxuXG5cblxuXG5cblxuXG5cbiIsIm13LnNldHRpbmdzLmxpYnMgPSB7XG4gICAganF1ZXJ5dWk6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdqcXVlcnl1aScgKyAnL2pxdWVyeS11aS5taW4uanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnanF1ZXJ5dWknICsgJy9qcXVlcnktdWkubWluLmNzcycpO1xuICAgICAgICB9XG4gICAgXSxcbiAgICBtb3JyaXM6IFsnbW9ycmlzLmNzcycsICdyYXBoYWVsLmpzJywgJ21vcnJpcy5qcyddLFxuICAgIHJhbmd5OiBbJ3Jhbmd5LWNvcmUuanMnLCAncmFuZ3ktY3NzY2xhc3NhcHBsaWVyLmpzJywgJ3Jhbmd5LXNlbGVjdGlvbnNhdmVyZXN0b3JlLmpzJywgJ3Jhbmd5LXNlcmlhbGl6ZXIuanMnXSxcbiAgICBoaWdobGlnaHQ6IFtcblxuICAgICAgICAnaGlnaGxpZ2h0Lm1pbi5qcycsXG4gICAgICAgICdoaWdobGlnaHQubWluLmNzcydcblxuICAgIF0sXG4gICAgYm9vdHN0cmFwMjogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgdiA9IG13ZC5xdWVyeVNlbGVjdG9yKCdtZXRhW25hbWU9XCJ2aWV3cG9ydFwiXScpO1xuICAgICAgICAgICAgaWYgKHYgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICB2ID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ21ldGEnKTtcbiAgICAgICAgICAgICAgICB2Lm5hbWUgPSBcInZpZXdwb3J0XCI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2LmNvbnRlbnQgPSBcIndpZHRoPWRldmljZS13aWR0aCwgaW5pdGlhbC1zY2FsZT0xLjBcIjtcbiAgICAgICAgICAgIG13aGVhZC5hcHBlbmRDaGlsZCh2KTtcbiAgICAgICAgfSxcbiAgICAgICAgJ2Nzcy9ib290c3RyYXAubWluLmNzcycsXG4gICAgICAgICdjc3MvYm9vdHN0cmFwLXJlc3BvbnNpdmUubWluLmNzcycsXG4gICAgICAgICdqcy9ib290c3RyYXAubWluLmpzJ1xuICAgIF0sXG4gICAgYm9vdHN0cmFwMzogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2ZvbnRhd2Vzb21lLTQuNy4wJyArICcvY3NzL2ZvbnQtYXdlc29tZS5taW4uY3NzJyk7XG4gICAgICAgICAgICB2YXIgdiA9IG13ZC5xdWVyeVNlbGVjdG9yKCdtZXRhW25hbWU9XCJ2aWV3cG9ydFwiXScpO1xuICAgICAgICAgICAgaWYgKHYgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICB2ID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ21ldGEnKTtcbiAgICAgICAgICAgICAgICB2Lm5hbWUgPSBcInZpZXdwb3J0XCI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2LmNvbnRlbnQgPSBcIndpZHRoPWRldmljZS13aWR0aCwgaW5pdGlhbC1zY2FsZT0xLjBcIjtcbiAgICAgICAgICAgIG13aGVhZC5hcHBlbmRDaGlsZCh2KTtcbiAgICAgICAgfSxcbiAgICAgICAgJ2Nzcy9ib290c3RyYXAubWluLmNzcycsXG4gICAgICAgICdqcy9ib290c3RyYXAubWluLmpzJ1xuICAgIF0sXG4gICAgYm9vdHN0cmFwNDogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2Jvb3RzdHJhcC00LjMuMScgKyAnL2Nzcy9ib290c3RyYXAubWluLmNzcycpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdib290c3RyYXAtNC4zLjEnICsgJy9qcy9wb3BwZXIubWluLmpzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2Jvb3RzdHJhcC00LjMuMScgKyAnL2pzL2Jvb3RzdHJhcC5taW4uanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnZm9udGF3ZXNvbWUtZnJlZS01LjEyLjAnICsgJy9jc3MvYWxsLm1pbi5jc3MnKTtcbiAgICAgICAgfVxuICAgIF0sXG4gICAgbWljcm93ZWJlcl91aTogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpL2dydW50L3BsdWdpbnMvdWkvY3NzL21haW4uY3NzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpL2Fzc2V0cy91aS9wbHVnaW5zL2Nzcy9wbHVnaW5zLm1pbi5jc3MnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnbXctdWkvYXNzZXRzL3VpL3BsdWdpbnMvanMvcGx1Z2lucy5qcycpO1xuICAgICAgICB9XG5cblxuICAgIF0sXG4gICAgbXd1aTogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAvLyBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpJyArICcvZ3J1bnQvcGx1Z2lucy91aS9jc3MvbWFpbi5jc3MnKTtcbiAgICAgICAgICAgIC8vIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnbXctdWknICsgJy9hc3NldHMvdWkvcGx1Z2lucy9jc3MvcGx1Z2lucy5taW4uY3NzJyk7XG4gICAgICAgICAgICAvLyBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpJyArICcvZ3J1bnQvcGx1Z2lucy91aS9jc3MvbXcuY3NzJyk7XG4gICAgICAgICAgICAvL1RoZSBmaWxlcyBhYm92ZSBhcmUgYWRkZWQgaW4gZGVmYXVsdC5jc3NcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnbXctdWknICsgJy9hc3NldHMvdWkvcGx1Z2lucy9qcy9wbHVnaW5zLmpzJyk7XG4gICAgICAgIH1cblxuXG4gICAgXSxcbiAgICBtd3VpX2luaXQ6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdtdy11aScgKyAnL2dydW50L3BsdWdpbnMvdWkvanMvdWkuanMnKTtcbiAgICAgICAgfVxuICAgIF0sXG4gICAgZmxhZ19pY29uczogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2ZsYWctaWNvbi1jc3MnICsgJy9jc3MvZmxhZy1pY29uLm1pbi5jc3MnKTtcblxuICAgICAgICB9XG4gICAgXSxcbiAgICBmb250X2F3ZXNvbWU6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdmb250YXdlc29tZS00LjcuMCcgKyAnL2Nzcy9mb250LWF3ZXNvbWUubWluLmNzcycpO1xuXG4gICAgICAgIH1cbiAgICBdLFxuICAgIGZvbnRfYXdlc29tZTU6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdmb250YXdlc29tZS1mcmVlLTUuMTIuMCcgKyAnL2Nzcy9hbGwubWluLmNzcycpO1xuXG4gICAgICAgIH1cbiAgICBdLFxuICAgIGJ4c2xpZGVyOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnYnhTbGlkZXIvanF1ZXJ5LmJ4c2xpZGVyLm1pbi5qcycsIHRydWUpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdieFNsaWRlci9qcXVlcnkuYnhzbGlkZXIuY3NzJywgdHJ1ZSk7XG5cbiAgICAgICAgfVxuICAgIF0sXG4gICAgY29sbGFwc2VfbmF2OiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnY29sbGFwc2UtbmF2L2Rpc3QvY29sbGFwc2VOYXYuanMnLCB0cnVlKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnY29sbGFwc2UtbmF2L2Rpc3QvY29sbGFwc2VOYXYuY3NzJywgdHJ1ZSk7XG5cbiAgICAgICAgfVxuICAgIF0sXG4gICAgc2xpY2s6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdzbGljaycgKyAnL3NsaWNrLmNzcycsIHRydWUpO1xuICAgICAgICAgICAgbXcubW9kdWxlQ1NTKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ3NsaWNrJyArICcvc2xpY2stdGhlbWUuY3NzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ3NsaWNrJyArICcvc2xpY2subWluLmpzJywgdHJ1ZSk7XG4gICAgICAgIH1cbiAgICBdLFxuICAgIGJvb3RzdHJhcF9kYXRlcGlja2VyOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnYm9vdHN0cmFwLWRhdGVwaWNrZXInICsgJy9jc3MvYm9vdHN0cmFwLWRhdGVwaWNrZXIzLmNzcycsIHRydWUpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdib290c3RyYXAtZGF0ZXBpY2tlcicgKyAnL2pzL2Jvb3RzdHJhcC1kYXRlcGlja2VyLmpzJywgdHJ1ZSk7XG4gICAgICAgIH1cbiAgICBdLFxuICAgIGJvb3RzdHJhcF9kYXRldGltZXBpY2tlcjogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2Jvb3RzdHJhcC1kYXRldGltZXBpY2tlcicgKyAnL2Nzcy9ib290c3RyYXAtZGF0ZXRpbWVwaWNrZXIubWluLmNzcycsIHRydWUpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdib290c3RyYXAtZGF0ZXRpbWVwaWNrZXInICsgJy9qcy9ib290c3RyYXAtZGF0ZXRpbWVwaWNrZXIubWluLmpzJywgdHJ1ZSk7XG4gICAgICAgIH1cbiAgICBdLFxuICAgIGJvb3RzdHJhcDNuczogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICAgIC8vdmFyIGJvb3RzdHJhcF9lbmFibGVkID0gKHR5cGVvZiAkKCkubW9kYWwgPT0gJ2Z1bmN0aW9uJyk7XG4gICAgICAgICAgICB2YXIgYm9vdHN0cmFwX2VuYWJsZWQgPSAodHlwZW9mICQgIT0gJ3VuZGVmaW5lZCcgJiYgdHlwZW9mICQuZm4gIT0gJ3VuZGVmaW5lZCcgJiYgdHlwZW9mICQuZm4uZW11bGF0ZVRyYW5zaXRpb25FbmQgIT0gJ3VuZGVmaW5lZCcpO1xuXG4gICAgICAgICAgICBpZiAoIWJvb3RzdHJhcF9lbmFibGVkKSB7XG4gICAgICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdib290c3RyYXAzJyArICcvanMvYm9vdHN0cmFwLm1pbi5qcycpO1xuICAgICAgICAgICAgICAgIC8vdmFyIGJvb3RzdHJhcF9lbmFibGVkID0gKHR5cGVvZiAkKCkubW9kYWwgPT0gJ2Z1bmN0aW9uJyk7XG4gICAgICAgICAgICAgICAgLy9pZiAoYm9vdHN0cmFwX2VuYWJsZWQgPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2Jvb3RzdHJhcDNucycgKyAnL2Jvb3RzdHJhcC5taW4uY3NzJyk7XG4gICAgICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdmb250YXdlc29tZS00LjcuMCcgKyAnL2Nzcy9mb250LWF3ZXNvbWUubWluLmNzcycpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy8gfVxuICAgICAgICB9XG4gICAgXSxcbiAgICBib290c3RyYXBfc2VsZWN0OiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIC8vdmFyIGJvb3RzdHJhcF9lbmFibGVkID0gKHR5cGVvZiAkKCkubW9kYWwgPT0gJ2Z1bmN0aW9uJyk7XG4gICAgICAgICAgICAvL2lmICghYm9vdHN0cmFwX2VuYWJsZWQgPT0gZmFsc2UpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnYm9vdHN0cmFwLXNlbGVjdC0xLjEzLjEyJyArICcvanMvYm9vdHN0cmFwLXNlbGVjdC5taW4uanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnYm9vdHN0cmFwLXNlbGVjdC0xLjEzLjEyJyArICcvY3NzL2Jvb3RzdHJhcC1zZWxlY3QubWluLmNzcycpO1xuICAgICAgICAgICAgLy99XG4gICAgICAgIH1cbiAgICBdLFxuICAgIGJvb3RzdHJhcF90YWdzOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgLy8gdmFyIGJvb3RzdHJhcF9lbmFibGVkID0gKHR5cGVvZiAkKCkubW9kYWwgPT0gJ2Z1bmN0aW9uJyk7XG4gICAgICAgICAgICAvL2lmICghYm9vdHN0cmFwX2VuYWJsZWQgPT0gZmFsc2UpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAndHlwZWFoZWFkJyArICcvdHlwZWFoZWFkLmpxdWVyeS5qcycpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICd0eXBlYWhlYWQnICsgJy90eXBlYWhlYWQuYnVuZGxlLm1pbi5qcycpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICd0eXBlYWhlYWQnICsgJy9ibG9vZGhvdW5kLmpzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpL2dydW50L3BsdWdpbnMvdGFncycgKyAnL2Jvb3RzdHJhcC10YWdzaW5wdXQuY3NzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ213LXVpL2dydW50L3BsdWdpbnMvdGFncycgKyAnL2Jvb3RzdHJhcC10YWdzaW5wdXQuanMnKTtcbiAgICAgICAgICAgIC8vfSBlbHNlIHtcbiAgICAgICAgICAgIC8vbXcubG9nKFwiWW91IG11c3QgbG9hZCBib290c3RyYXAgdG8gdXNlIGJvb3RzdHJhcF90YWdzXCIpO1xuICAgICAgICAgICAgLy99XG5cbiAgICAgICAgfVxuICAgIF0sXG4gICAgY2hvc2VuOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnY2hvc2VuJyArICcvY2hvc2VuLmpxdWVyeS5taW4uanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnY2hvc2VuJyArICcvY2hvc2VuLm1pbi5jc3MnLCB0cnVlKTtcbiAgICAgICAgfVxuICAgIF0sXG4gICAgdmFsaWRhdGlvbjogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ2pxdWVyeV92YWxpZGF0aW9uJyArICcvanMvanF1ZXJ5LnZhbGlkYXRpb25FbmdpbmUuanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnanF1ZXJ5X3ZhbGlkYXRpb24nICsgJy9qcy9sYW5ndWFnZXMvanF1ZXJ5LnZhbGlkYXRpb25FbmdpbmUtZW4uanMnKTtcbiAgICAgICAgICAgIG13LnJlcXVpcmUobXcuc2V0dGluZ3MubGlic191cmwgKyAnanF1ZXJ5X3ZhbGlkYXRpb24nICsgJy9jc3MvdmFsaWRhdGlvbkVuZ2luZS5qcXVlcnkuY3NzJyk7XG4gICAgICAgIH1cbiAgICBdLFxuXG4gICAgZml0dHk6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdmaXR0eScgKyAnL2Rpc3QvZml0dHkubWluLmpzJyk7XG4gICAgICAgICAgICAvKiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICBmaXR0eSgnLmZpdHR5LWVsZW1lbnQnKTtcbiAgICAgICAgICAgICB9KTsqL1xuICAgICAgICB9XG4gICAgXSxcbiAgICBmbGF0c3RyYXAzOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciB2ID0gbXdkLnF1ZXJ5U2VsZWN0b3IoJ21ldGFbbmFtZT1cInZpZXdwb3J0XCJdJyk7XG4gICAgICAgICAgICBpZiAodiA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHYgPSBtd2QuY3JlYXRlRWxlbWVudCgnbWV0YScpO1xuICAgICAgICAgICAgICAgIHYubmFtZSA9IFwidmlld3BvcnRcIjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHYuY29udGVudCA9IFwid2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMFwiO1xuICAgICAgICAgICAgbXdoZWFkLmFwcGVuZENoaWxkKHYpO1xuICAgICAgICB9LFxuICAgICAgICAnY3NzL2Jvb3RzdHJhcC5taW4uY3NzJyxcbiAgICAgICAgJ2pzL2Jvb3RzdHJhcC5taW4uanMnXG4gICAgXSxcbiAgICBkYXRlcGlja2VyOiBbXG4gICAgICAgICdkYXRlcGlja2VyLm1pbi5qcycsXG4gICAgICAgICdkYXRlcGlja2VyLm1pbi5jc3MnXG4gICAgXSxcbiAgICBkYXRldGltZXBpY2tlcjogW1xuICAgICAgICAnanF1ZXJ5LmRhdGV0aW1lcGlja2VyLmZ1bGwubWluLmpzJyxcbiAgICAgICAgJ2pxdWVyeS5kYXRldGltZXBpY2tlci5taW4uY3NzJ1xuICAgIF0sXG5cbiAgICBuZXN0ZWRTb3J0YWJsZTogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmxpYnNfdXJsICsgJ25lc3RlZHNvcnRhYmxlJyArICcvanF1ZXJ5Lm1qcy5uZXN0ZWRTb3J0YWJsZS5qcycpO1xuICAgICAgICB9XG4gICAgXSxcbiAgICBjb2xvcnBpY2tlcjogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKG13LnNldHRpbmdzLmluY2x1ZGVzX3VybCArICdhcGknICsgJy9jb2xvci5qcycpO1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdhY29sb3JwaWNrZXInICsgJy9hY29sb3JwaWNrZXIuanMnKTtcbiAgICAgICAgfVxuICAgIF0sXG4gICAgbWF0ZXJpYWxfaWNvbnM6IFtcbiAgICAgICAgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcucmVxdWlyZShtdy5zZXR0aW5ncy5saWJzX3VybCArICdtYXRlcmlhbF9pY29ucycgKyAnL21hdGVyaWFsX2ljb25zLmNzcycpO1xuICAgICAgICB9XG4gICAgXSxcbiAgICBtYXRlcmlhbERlc2lnbkljb25zOiBbXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LnJlcXVpcmUoJ2Nzcy9mb250cy9tYXRlcmlhbGRlc2lnbmljb25zL2Nzcy9tYXRlcmlhbGRlc2lnbmljb25zLm1pbi5jc3MnKTtcbiAgICAgICAgfVxuICAgIF0sXG4gICAgbXdfaWNvbnNfbWluZDogW1xuICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKCdmb250cy9tdy1pY29ucy1taW5kL2xpbmUvc3R5bGUuY3NzJyk7XG4gICAgICAgICAgICBtdy5yZXF1aXJlKCdmb250cy9tdy1pY29ucy1taW5kL3NvbGlkL3N0eWxlLmNzcycpO1xuICAgICAgICB9XG4gICAgXSxcbiAgICBhcGV4Y2hhcnRzOiBbXG4gICAgICAgICdhcGV4Y2hhcnRzLm1pbi5qcycsXG4gICAgICAgICdhcGV4Y2hhcnRzLmNzcydcbiAgICBdXG59O1xuXG5tdy5saWIgPSB7XG4gICAgX3JlcXVpcmVkOiBbXSxcbiAgICByZXF1aXJlOiBmdW5jdGlvbiAobmFtZSkge1xuICAgICAgICBpZiAobXcubGliLl9yZXF1aXJlZC5pbmRleE9mKG5hbWUpICE9PSAtMSkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIG13LmxpYi5fcmVxdWlyZWQucHVzaChuYW1lKTtcbiAgICAgICAgaWYgKHR5cGVvZiBtdy5zZXR0aW5ncy5saWJzW25hbWVdID09PSAndW5kZWZpbmVkJykgcmV0dXJuIGZhbHNlO1xuICAgICAgICBpZiAobXcuc2V0dGluZ3MubGlic1tuYW1lXS5jb25zdHJ1Y3RvciAhPT0gW10uY29uc3RydWN0b3IpIHJldHVybiBmYWxzZTtcbiAgICAgICAgdmFyIHBhdGggPSBtdy5zZXR0aW5ncy5saWJzX3VybCArIG5hbWUgKyAnLycsXG4gICAgICAgICAgICBhcnIgPSBtdy5zZXR0aW5ncy5saWJzW25hbWVdLFxuICAgICAgICAgICAgbCA9IGFyci5sZW5ndGgsXG4gICAgICAgICAgICBpID0gMCxcbiAgICAgICAgICAgIGMgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgKHR5cGVvZiBhcnJbaV0gPT09ICdzdHJpbmcnKSA/IG13LnJlcXVpcmUocGF0aCArIGFycltpXSwgdHJ1ZSkgOiAodHlwZW9mIGFycltpXSA9PT0gJ2Z1bmN0aW9uJykgPyBhcnJbaV0uY2FsbCgpIDogJyc7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGdldDogZnVuY3Rpb24gKG5hbWUsIGRvbmUsIGVycm9yKSB7XG4gICAgICAgIGlmIChtdy5saWIuX3JlcXVpcmVkLmluZGV4T2YobmFtZSkgIT09IC0xKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIGRvbmUgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICBkb25lLmNhbGwoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0eXBlb2YgbXcuc2V0dGluZ3MubGlic1tuYW1lXSA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybiBmYWxzZTtcbiAgICAgICAgaWYgKG13LnNldHRpbmdzLmxpYnNbbmFtZV0uY29uc3RydWN0b3IgIT09IFtdLmNvbnN0cnVjdG9yKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIG13LmxpYi5fcmVxdWlyZWQucHVzaChuYW1lKTtcbiAgICAgICAgdmFyIHBhdGggPSBtdy5zZXR0aW5ncy5saWJzX3VybCArIG5hbWUgKyAnLycsXG4gICAgICAgICAgICBhcnIgPSBtdy5zZXR0aW5ncy5saWJzW25hbWVdLFxuICAgICAgICAgICAgbCA9IGFyci5sZW5ndGgsXG4gICAgICAgICAgICBpID0gMCxcbiAgICAgICAgICAgIGMgPSAxO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgdmFyIHhociA9ICQuY2FjaGVkU2NyaXB0KHBhdGggKyBhcnJbaV0pO1xuICAgICAgICAgICAgeGhyLmRvbmUoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGMrKztcbiAgICAgICAgICAgICAgICBpZiAoYyA9PT0gbCkge1xuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRvbmUgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGRvbmUuY2FsbCgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB4aHIuZmFpbChmdW5jdGlvbiAoanF4aHIsIHNldHRpbmdzLCBleGNlcHRpb24pIHtcblxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZXJyb3IgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgZXJyb3IuY2FsbChqcXhociwgc2V0dGluZ3MsIGV4Y2VwdGlvbik7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cbn07XG4iLCJtdy5yZXNwb25zZSA9IGZ1bmN0aW9uKGZvcm0sIGRhdGEsIG1lc3NhZ2VzX2F0X3RoZV9ib3R0b20pe1xuICAgIG1lc3NhZ2VzX2F0X3RoZV9ib3R0b20gPSBtZXNzYWdlc19hdF90aGVfYm90dG9tIHx8IGZhbHNlO1xuICAgIGlmKGRhdGEgPT0gbnVsbCAgfHwgIHR5cGVvZiBkYXRhID09ICd1bmRlZmluZWQnICl7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG5cbiAgICBkYXRhID0gbXcudG9vbHMudG9KU09OKGRhdGEpO1xuICAgIGlmKHR5cGVvZiBkYXRhID09PSAndW5kZWZpbmVkJyl7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG5cbiAgICBpZih0eXBlb2YgZGF0YS5lcnJvciAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICBtdy5fcmVzcG9uc2UuZXJyb3IoZm9ybSwgZGF0YSwgbWVzc2FnZXNfYXRfdGhlX2JvdHRvbSk7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgZWxzZSBpZih0eXBlb2YgZGF0YS5zdWNjZXNzICE9PSAndW5kZWZpbmVkJyl7XG4gICAgICAgIG13Ll9yZXNwb25zZS5zdWNjZXNzKGZvcm0sIGRhdGEsIG1lc3NhZ2VzX2F0X3RoZV9ib3R0b20pO1xuICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICB9XG4gICAgZWxzZSBpZih0eXBlb2YgZGF0YS53YXJuaW5nICE9PSAndW5kZWZpbmVkJyl7XG4gICAgICAgIG13Ll9yZXNwb25zZS53YXJuaW5nKGZvcm0sIGRhdGEsIG1lc3NhZ2VzX2F0X3RoZV9ib3R0b20pO1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuICAgIGVsc2V7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG59O1xuXG5tdy5fcmVzcG9uc2UgPSB7XG4gICAgZXJyb3I6ZnVuY3Rpb24oZm9ybSwgZGF0YSwgX21zZyl7XG4gICAgICAgIGZvcm0gPSBtdy4kKGZvcm0pO1xuICAgICAgICB2YXIgZXJyX2hvbGRlciA9IG13Ll9yZXNwb25zZS5tc2dIb2xkZXIoZm9ybSwgJ2Vycm9yJyk7XG4gICAgICAgIG13Ll9yZXNwb25zZS5jcmVhdGVIVE1MKGRhdGEuZXJyb3IsIGVycl9ob2xkZXIpO1xuICAgIH0sXG4gICAgc3VjY2VzczpmdW5jdGlvbihmb3JtLCBkYXRhLCBfbXNnKXtcbiAgICAgICAgZm9ybSA9IG13LiQoZm9ybSk7XG4gICAgICAgIHZhciBlcnJfaG9sZGVyID0gbXcuX3Jlc3BvbnNlLm1zZ0hvbGRlcihmb3JtLCAnc3VjY2VzcycpO1xuICAgICAgICBtdy5fcmVzcG9uc2UuY3JlYXRlSFRNTChkYXRhLnN1Y2Nlc3MsIGVycl9ob2xkZXIpO1xuICAgIH0sXG4gICAgd2FybmluZzpmdW5jdGlvbihmb3JtLCBkYXRhLCBfbXNnKXtcbiAgICAgICAgZm9ybSA9IG13LiQoZm9ybSk7XG4gICAgICAgIHZhciBlcnJfaG9sZGVyID0gbXcuX3Jlc3BvbnNlLm1zZ0hvbGRlcihmb3JtLCAnd2FybmluZycpO1xuICAgICAgICBtdy5fcmVzcG9uc2UuY3JlYXRlSFRNTChkYXRhLndhcm5pbmcsIGVycl9ob2xkZXIpO1xuICAgIH0sXG4gICAgbXNnSG9sZGVyIDogZnVuY3Rpb24oZm9ybSwgdHlwZSwgbWV0aG9kKXtcbiAgICAgICAgbWV0aG9kID0gbWV0aG9kIHx8ICdhcHBlbmQnO1xuICAgICAgICB2YXIgZXJyX2hvbGRlciA9IGZvcm0uZmluZChcIi5tdy1jaGVja291dC1yZXNwb25zZTpmaXJzdFwiKTtcbiAgICAgICAgdmFyIGVycl9ob2xkZXIyID0gZm9ybS5maW5kKFwiLmFsZXJ0OmZpcnN0XCIpO1xuICAgICAgICBpZihlcnJfaG9sZGVyLmxlbmd0aCA9PT0gMCl7XG4gICAgICAgICAgICBlcnJfaG9sZGVyID0gZXJyX2hvbGRlcjI7XG4gICAgICAgIH1cbiAgICAgICAgaWYoZXJyX2hvbGRlci5sZW5ndGggPT09IDApe1xuICAgICAgICAgICAgZXJyX2hvbGRlciA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGZvcm1bbWV0aG9kXShlcnJfaG9sZGVyKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBib290cmFwX2Vycm9yX3R5cGUgPSAnZGVmYXVsdCc7XG4gICAgICAgIGlmKHR5cGUgPT09ICdlcnJvcicpe1xuICAgICAgICAgICAgYm9vdHJhcF9lcnJvcl90eXBlID0gJ2Rhbmdlcic7XG4gICAgICAgIH0gZWxzZSBpZih0eXBlID09PSAnZG9uZScpe1xuICAgICAgICAgICAgYm9vdHJhcF9lcnJvcl90eXBlID0gJ2luZm8nO1xuICAgICAgICB9XG5cblxuICAgICAgICAkKGVycl9ob2xkZXIpLmVtcHR5KCkuYXR0cihcImNsYXNzXCIsICdhbGVydCBhbGVydC0nICsgdHlwZSArICcgYWxlcnQtJyArIGJvb3RyYXBfZXJyb3JfdHlwZSArICcgJyk7XG4gICAgICAgIHJldHVybiBlcnJfaG9sZGVyO1xuICAgIH0sXG4gICAgY3JlYXRlSFRNTDpmdW5jdGlvbihkYXRhLCBob2xkZXIpe1xuICAgICAgICB2YXIgaSwgaHRtbCA9IFwiXCI7XG5cblxuICAgICAgICBpZih0eXBlb2YgZGF0YSA9PT0gJ3N0cmluZycpe1xuICAgICAgICAgICAgaHRtbCs9IGRhdGEudG9TdHJpbmcoKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICAgZm9yKCBpIGluIGRhdGEpe1xuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBkYXRhW2ldID09PSAnc3RyaW5nJyl7XG4gICAgICAgICAgICAgICAgICAgIGh0bWwrPSc8bGk+JytkYXRhW2ldKyc8L2xpPic7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKXtcbiAgICAgICAgICAgICAgICAgICAgJC5lYWNoKGRhdGFbaV0sIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBodG1sKz0nPGxpPicrdGhpcysnPC9saT4nO1xuICAgICAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBtdy4kKGhvbGRlcikuZXEoMCkuYXBwZW5kKCc8dWwgY2xhc3M9XCJtdy1lcnJvci1saXN0XCI+JytodG1sKyc8L3VsPicpO1xuICAgICAgICBtdy4kKGhvbGRlcikuZXEoMCkuc2hvdygpO1xuICAgIH1cbn1cbiIsIi8vIHN0YXJ0dXBcbi8vIExvYWQgZW50cnkgbW9kdWxlXG5fX3dlYnBhY2tfbW9kdWxlc19fW1wiLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29yZS9jb21wb25lbnRzLmpzXCJdKCk7XG5fX3dlYnBhY2tfbW9kdWxlc19fW1wiLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29yZS9lbGVtZW50LmpzXCJdKCk7XG4vLyBUaGlzIGVudHJ5IG1vZHVsZSBpcyByZWZlcmVuY2VkIGJ5IG90aGVyIG1vZHVsZXMgc28gaXQgY2FuJ3QgYmUgaW5saW5lZFxuX193ZWJwYWNrX21vZHVsZXNfX1tcIi4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2NvcmUvZXZlbnRzLmpzXCJdKCk7XG5fX3dlYnBhY2tfbW9kdWxlc19fW1wiLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29yZS9saWJzLmpzXCJdKCk7XG5fX3dlYnBhY2tfbW9kdWxlc19fW1wiLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29yZS9yZXNwb25zZS5qc1wiXSgpO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==