/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.EventManager
 * Registers event handlers that want to receive a normalized EventObject instead of the standard browser event and provides
 * several useful events directly.
 * See {@link Ext.EventObject} for more details on normalized event objects.
 * @singleton
 */
Ext.EventManager = function(){
    var docReadyEvent, docReadyProcId, docReadyState = false;
    var resizeEvent, resizeTask, textEvent, textSize;
    var E = Ext.lib.Event;
    var D = Ext.lib.Dom;
    // fix parser confusion
    var xname = 'Ex' + 't';

    var elHash = {};

    var addListener = function(el, ename, fn, wrap, scope){
        var id = Ext.id(el);
        if(!elHash[id]){
            elHash[id] = {};
        }
        var es = elHash[id];
        if(!es[ename]){
            es[ename] = [];
        }
        var ls = es[ename];
        ls.push({
            id: id,
            ename: ename,
            fn: fn,
            wrap: wrap,
            scope: scope
        });

         E.on(el, ename, wrap);

        if(ename == "mousewheel" && el.addEventListener){ // workaround for jQuery
            el.addEventListener("DOMMouseScroll", wrap, false);
            E.on(window, 'unload', function(){
                el.removeEventListener("DOMMouseScroll", wrap, false);
            });
        }
        if(ename == "mousedown" && el == document){ // fix stopped mousedowns on the document
            Ext.EventManager.stoppedMouseDownEvent.addListener(wrap);
        }
    }

    var removeListener = function(el, ename, fn, scope){
        el = Ext.getDom(el);
        var id = Ext.id(el), es = elHash[id], wrap;
        if(es){
            var ls = es[ename], l;
            if(ls){
                for(var i = 0, len = ls.length; i < len; i++){
                    l = ls[i];
                    if(l.fn == fn && (!scope || l.scope == scope)){
                        wrap = l.wrap;
                        E.un(el, ename, wrap);
                        ls.splice(i, 1);
                        break;
                    }
                }
            }
        }
        if(ename == "mousewheel" && el.addEventListener && wrap){
            el.removeEventListener("DOMMouseScroll", wrap, false);
        }
        if(ename == "mousedown" && el == document && wrap){ // fix stopped mousedowns on the document
            Ext.EventManager.stoppedMouseDownEvent.removeListener(wrap);
        }
    }

    var removeAll = function(el){
        el = Ext.getDom(el);
        var id = Ext.id(el), es = elHash[id], ls;
        if(es){
            for(var ename in es){
                if(es.hasOwnProperty(ename)){
                    ls = es[ename];
                    for(var i = 0, len = ls.length; i < len; i++){
                        E.un(el, ename, ls[i].wrap);
                        ls[i] = null;
                    }
                }
                es[ename] = null;
            }
            delete elHash[id];
        }
    }

     var fireDocReady = function(){
        if(!docReadyState){
            docReadyState = Ext.isReady = true;
            if(Ext.isGecko || Ext.isOpera) {
                document.removeEventListener("DOMContentLoaded", fireDocReady, false);
            }
        }
        if(docReadyProcId){
            clearInterval(docReadyProcId);
            docReadyProcId = null;
        }
        if(docReadyEvent){
            docReadyEvent.fire();
            docReadyEvent.clearListeners();
       }
    };

    var initDocReady = function(){
        docReadyEvent = new Ext.util.Event();

        if(Ext.isReady){
            return;
        }

        // no matter what, make sure it fires on load
        E.on(window, 'load', fireDocReady);

        if(Ext.isGecko || Ext.isOpera) {
            document.addEventListener('DOMContentLoaded', fireDocReady, false);
        }
        else if(Ext.isIE){
            docReadyProcId = setInterval(function(){
                try{
                    // throws errors until DOM is ready
                    Ext.isReady || (document.documentElement.doScroll('left'));
                }catch(e){
                    return;
                }
                fireDocReady();  // no errors, fire
            }, 5);

			document.onreadystatechange = function(){
				if(document.readyState == 'complete'){
					document.onreadystatechange = null;
					fireDocReady();
				}
            };
        }
        else if(Ext.isSafari){
            docReadyProcId = setInterval(function(){
                var rs = document.readyState;
                if(rs == 'complete') {
                    fireDocReady();
                 }
            }, 10);
        }
    };

    var createBuffered = function(h, o){
        var task = new Ext.util.DelayedTask(h);
        return function(e){
            // create new event object impl so new events don't wipe out properties
            e = new Ext.EventObjectImpl(e);
            task.delay(o.buffer, h, null, [e]);
        };
    };

    var createSingle = function(h, el, ename, fn, scope){
        return function(e){
            Ext.EventManager.removeListener(el, ename, fn, scope);
            h(e);
        };
    };

    var createDelayed = function(h, o){
        return function(e){
            // create new event object impl so new events don't wipe out properties
            e = new Ext.EventObjectImpl(e);
            setTimeout(function(){
                h(e);
            }, o.delay || 10);
        };
    };

    var listen = function(element, ename, opt, fn, scope){
        var o = (!opt || typeof opt == "boolean") ? {} : opt;
        fn = fn || o.fn; scope = scope || o.scope;
        var el = Ext.getDom(element);
        if(!el){
            throw "Error listening for \"" + ename + '\". Element "' + element + '" doesn\'t exist.';
        }
        var h = function(e){
            // prevent errors while unload occurring
            if(!window[xname]){
                return;
            }
            e = Ext.EventObject.setEvent(e);
            var t;
            if(o.delegate){
                t = e.getTarget(o.delegate, el);
                if(!t){
                    return;
                }
            }else{
                t = e.target;
            }
            if(o.stopEvent === true){
                e.stopEvent();
            }
            if(o.preventDefault === true){
               e.preventDefault();
            }
            if(o.stopPropagation === true){
                e.stopPropagation();
            }

            if(o.normalized === false){
                e = e.browserEvent;
            }

            fn.call(scope || el, e, t, o);
        };
        if(o.delay){
            h = createDelayed(h, o);
        }
        if(o.single){
            h = createSingle(h, el, ename, fn, scope);
        }
        if(o.buffer){
            h = createBuffered(h, o);
        }

        addListener(el, ename, fn, h, scope);
        return h;
    };

    var propRe = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/;
    var pub = {

    /**
     * Appends an event handler to an element.  The shorthand version {@link #on} is equivalent.  Typically you will
     * use {@link Ext.Element#addListener} directly on an Element in favor of calling this version.
     * @param {String/HTMLElement} el The html element or id to assign the event handler to
     * @param {String} eventName The type of event to listen for
     * @param {Function} handler The handler function the event invokes This function is passed
     * the following parameters:<ul>
     * <li>evt : EventObject<div class="sub-desc">The {@link Ext.EventObject EventObject} describing the event.</div></li>
     * <li>t : Element<div class="sub-desc">The {@link Ext.Element Element} which was the target of the event.
     * Note that this may be filtered by using the <tt>delegate</tt> option.</div></li>
     * <li>o : Object<div class="sub-desc">The options object from the addListener call.</div></li>
     * </ul>
     * @param {Object} scope (optional) The scope in which to execute the handler
     * function (the handler function's "this" context)
     * @param {Object} options (optional) An object containing handler configuration properties.
     * This may contain any of the following properties:<ul>
     * <li>scope {Object} : The scope in which to execute the handler function. The handler function's "this" context.</li>
     * <li>delegate {String} : A simple selector to filter the target or look for a descendant of the target</li>
     * <li>stopEvent {Boolean} : True to stop the event. That is stop propagation, and prevent the default action.</li>
     * <li>preventDefault {Boolean} : True to prevent the default action</li>
     * <li>stopPropagation {Boolean} : True to prevent event propagation</li>
     * <li>normalized {Boolean} : False to pass a browser event to the handler function instead of an Ext.EventObject</li>
     * <li>delay {Number} : The number of milliseconds to delay the invocation of the handler after te event fires.</li>
     * <li>single {Boolean} : True to add a handler to handle just the next firing of the event, and then remove itself.</li>
     * <li>buffer {Number} : Causes the handler to be scheduled to run in an {@link Ext.util.DelayedTask} delayed
     * by the specified number of milliseconds. If the event fires again within that time, the original
     * handler is <em>not</em> invoked, but the new handler is scheduled in its place.</li>
     * </ul><br>
     * <p>See {@link Ext.Element#addListener} for examples of how to use these options.</p>
     */
        addListener : function(element, eventName, fn, scope, options){
            if(typeof eventName == "object"){
                var o = eventName;
                for(var e in o){
                    if(propRe.test(e)){
                        continue;
                    }
                    if(typeof o[e] == "function"){
                        // shared options
                        listen(element, e, o, o[e], o.scope);
                    }else{
                        // individual options
                        listen(element, e, o[e]);
                    }
                }
                return;
            }
            return listen(element, eventName, options, fn, scope);
        },

        /**
         * Removes an event handler from an element.  The shorthand version {@link #un} is equivalent.  Typically
         * you will use {@link Ext.Element#removeListener} directly on an Element in favor of calling this version.
         * @param {String/HTMLElement} el The id or html element from which to remove the event
         * @param {String} eventName The type of event
         * @param {Function} fn The handler function to remove
         */
        removeListener : function(element, eventName, fn, scope){
            return removeListener(element, eventName, fn, scope);
        },

        /**
         * Removes all event handers from an element.  Typically you will use {@link Ext.Element#removeAllListeners}
         * directly on an Element in favor of calling this version.
         * @param {String/HTMLElement} el The id or html element from which to remove the event
         */
        removeAll : function(element){
            return removeAll(element);
        },

        /**
         * Fires when the document is ready (before onload and before images are loaded). Can be
         * accessed shorthanded as Ext.onReady().
         * @param {Function} fn The method the event invokes
         * @param {Object} scope (optional) An object that becomes the scope of the handler
         * @param {boolean} options (optional) An object containing standard {@link #addListener} options
         */
         onDocumentReady : function(fn, scope, options){
			if(!docReadyEvent){
                initDocReady();
			}
			if(docReadyState || Ext.isReady){ // if it already fired
				options || (options = {});
				fn.defer(options.delay||0, scope);
			}else{
				docReadyEvent.addListener(fn, scope, options);
			}
        },

        /**
         * Fires when the window is resized and provides resize event buffering (50 milliseconds), passes new viewport width and height to handlers.
         * @param {Function} fn        The method the event invokes
         * @param {Object}   scope    An object that becomes the scope of the handler
         * @param {boolean}  options
         */
        onWindowResize : function(fn, scope, options){
            if(!resizeEvent){
                resizeEvent = new Ext.util.Event();
                resizeTask = new Ext.util.DelayedTask(function(){
                    resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
                });
                E.on(window, "resize", this.fireWindowResize, this);
            }
            resizeEvent.addListener(fn, scope, options);
        },

        // exposed only to allow manual firing
        fireWindowResize : function(){
            if(resizeEvent){
                if((Ext.isIE||Ext.isAir) && resizeTask){
                    resizeTask.delay(50);
                }else{
                    resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
                }
            }
        },

        /**
         * Fires when the user changes the active text size. Handler gets called with 2 params, the old size and the new size.
         * @param {Function} fn        The method the event invokes
         * @param {Object}   scope    An object that becomes the scope of the handler
         * @param {boolean}  options
         */
        onTextResize : function(fn, scope, options){
            if(!textEvent){
                textEvent = new Ext.util.Event();
                var textEl = new Ext.Element(document.createElement('div'));
                textEl.dom.className = 'x-text-resize';
                textEl.dom.innerHTML = 'X';
                textEl.appendTo(document.body);
                textSize = textEl.dom.offsetHeight;
                setInterval(function(){
                    if(textEl.dom.offsetHeight != textSize){
                        textEvent.fire(textSize, textSize = textEl.dom.offsetHeight);
                    }
                }, this.textResizeInterval);
            }
            textEvent.addListener(fn, scope, options);
        },

        /**
         * Removes the passed window resize listener.
         * @param {Function} fn        The method the event invokes
         * @param {Object}   scope    The scope of handler
         */
        removeResizeListener : function(fn, scope){
            if(resizeEvent){
                resizeEvent.removeListener(fn, scope);
            }
        },

        // private
        fireResize : function(){
            if(resizeEvent){
                resizeEvent.fire(D.getViewWidth(), D.getViewHeight());
            }
        },
        /**
         * Url used for onDocumentReady with using SSL (defaults to Ext.SSL_SECURE_URL)
         */
        ieDeferSrc : false,
        /**
         * The frequency, in milliseconds, to check for text resize events (defaults to 50)
         */
        textResizeInterval : 50
    };
     /**
     * Appends an event handler to an element.  Shorthand for {@link #addListener}.
     * @param {String/HTMLElement} el The html element or id to assign the event handler to
     * @param {String} eventName The type of event to listen for
     * @param {Function} handler The handler function the event invokes
     * @param {Object} scope (optional) The scope in which to execute the handler
     * function (the handler function's "this" context)
     * @param {Object} options (optional) An object containing standard {@link #addListener} options
     * @member Ext.EventManager
     * @method on
     */
    pub.on = pub.addListener;
    /**
     * Removes an event handler from an element.  Shorthand for {@link #removeListener}.
     * @param {String/HTMLElement} el The id or html element from which to remove the event
     * @param {String} eventName The type of event
     * @param {Function} fn The handler function to remove
     * @return {Boolean} True if a listener was actually removed, else false
     * @member Ext.EventManager
     * @method un
     */
    pub.un = pub.removeListener;

    pub.stoppedMouseDownEvent = new Ext.util.Event();
    return pub;
}();
/**
  * Fires when the document is ready (before onload and before images are loaded).  Shorthand of {@link Ext.EventManager#onDocumentReady}.
  * @param {Function} fn        The method the event invokes
  * @param {Object}   scope    An  object that becomes the scope of the handler
  * @param {boolean}  override If true, the obj passed in becomes
  *                             the execution scope of the listener
  * @member Ext
  * @method onReady
 */
Ext.onReady = Ext.EventManager.onDocumentReady;


// Initialize doc classes
(function(){
    var initExtCss = function(){
        // find the body element
        var bd = document.body || document.getElementsByTagName('body')[0];
        if(!bd){ return false; }
        var cls = [' ',
                Ext.isIE ? "ext-ie " + (Ext.isIE6 ? 'ext-ie6' : 'ext-ie7')
                : Ext.isGecko ? "ext-gecko " + (Ext.isGecko2 ? 'ext-gecko2' : 'ext-gecko3')
                : Ext.isOpera ? "ext-opera"
                : Ext.isSafari ? "ext-safari" : ""];

        if(Ext.isMac){
            cls.push("ext-mac");
        }
        if(Ext.isLinux){
            cls.push("ext-linux");
        }
        if(Ext.isBorderBox){
            cls.push('ext-border-box');
        }
        if(Ext.isStrict){ // add to the parent to allow for selectors like ".ext-strict .ext-ie"
            var p = bd.parentNode;
            if(p){
                p.className += ' ext-strict';
            }
        }
        bd.className += cls.join(' ');
        return true;
    }

    if(!initExtCss()){
        Ext.onReady(initExtCss);
    }
})();

/**
 * @class Ext.EventObject
 * EventObject exposes the Yahoo! UI Event functionality directly on the object
 * passed to your event handler. It exists mostly for convenience. It also fixes the annoying null checks automatically to cleanup your code
 * Example:
 * <pre><code>
 function handleClick(e){ // e is not a standard event object, it is a Ext.EventObject
    e.preventDefault();
    var target = e.getTarget();
    ...
 }
 var myDiv = Ext.get("myDiv");
 myDiv.on("click", handleClick);
 //or
 Ext.EventManager.on("myDiv", 'click', handleClick);
 Ext.EventManager.addListener("myDiv", 'click', handleClick);
 </code></pre>
 * @singleton
 */
Ext.EventObject = function(){

    var E = Ext.lib.Event;

    // safari keypress events for special keys return bad keycodes
    var safariKeys = {
        3 : 13, // enter
        63234 : 37, // left
        63235 : 39, // right
        63232 : 38, // up
        63233 : 40, // down
        63276 : 33, // page up
        63277 : 34, // page down
        63272 : 46, // delete
        63273 : 36, // home
        63275 : 35  // end
    };

    // normalize button clicks
    var btnMap = Ext.isIE ? {1:0,4:1,2:2} :
                (Ext.isSafari ? {1:0,2:1,3:2} : {0:0,1:1,2:2});

    Ext.EventObjectImpl = function(e){
        if(e){
            this.setEvent(e.browserEvent || e);
        }
    };

    Ext.EventObjectImpl.prototype = {
        /** The normal browser event */
        browserEvent : null,
        /** The button pressed in a mouse event */
        button : -1,
        /** True if the shift key was down during the event */
        shiftKey : false,
        /** True if the control key was down during the event */
        ctrlKey : false,
        /** True if the alt key was down during the event */
        altKey : false,

        /** Key constant @type Number */
        BACKSPACE: 8,
        /** Key constant @type Number */
        TAB: 9,
        /** Key constant @type Number */
        NUM_CENTER: 12,
        /** Key constant @type Number */
        ENTER: 13,
        /** Key constant @type Number */
        RETURN: 13,
        /** Key constant @type Number */
        SHIFT: 16,
        /** Key constant @type Number */
        CTRL: 17,
        CONTROL : 17, // legacy
        /** Key constant @type Number */
        ALT: 18,
        /** Key constant @type Number */
        PAUSE: 19,
        /** Key constant @type Number */
        CAPS_LOCK: 20,
        /** Key constant @type Number */
        ESC: 27,
        /** Key constant @type Number */
        SPACE: 32,
        /** Key constant @type Number */
        PAGE_UP: 33,
        PAGEUP : 33, // legacy
        /** Key constant @type Number */
        PAGE_DOWN: 34,
        PAGEDOWN : 34, // legacy
        /** Key constant @type Number */
        END: 35,
        /** Key constant @type Number */
        HOME: 36,
        /** Key constant @type Number */
        LEFT: 37,
        /** Key constant @type Number */
        UP: 38,
        /** Key constant @type Number */
        RIGHT: 39,
        /** Key constant @type Number */
        DOWN: 40,
        /** Key constant @type Number */
        PRINT_SCREEN: 44,
        /** Key constant @type Number */
        INSERT: 45,
        /** Key constant @type Number */
        DELETE: 46,
        /** Key constant @type Number */
        ZERO: 48,
        /** Key constant @type Number */
        ONE: 49,
        /** Key constant @type Number */
        TWO: 50,
        /** Key constant @type Number */
        THREE: 51,
        /** Key constant @type Number */
        FOUR: 52,
        /** Key constant @type Number */
        FIVE: 53,
        /** Key constant @type Number */
        SIX: 54,
        /** Key constant @type Number */
        SEVEN: 55,
        /** Key constant @type Number */
        EIGHT: 56,
        /** Key constant @type Number */
        NINE: 57,
        /** Key constant @type Number */
        A: 65,
        /** Key constant @type Number */
        B: 66,
        /** Key constant @type Number */
        C: 67,
        /** Key constant @type Number */
        D: 68,
        /** Key constant @type Number */
        E: 69,
        /** Key constant @type Number */
        F: 70,
        /** Key constant @type Number */
        G: 71,
        /** Key constant @type Number */
        H: 72,
        /** Key constant @type Number */
        I: 73,
        /** Key constant @type Number */
        J: 74,
        /** Key constant @type Number */
        K: 75,
        /** Key constant @type Number */
        L: 76,
        /** Key constant @type Number */
        M: 77,
        /** Key constant @type Number */
        N: 78,
        /** Key constant @type Number */
        O: 79,
        /** Key constant @type Number */
        P: 80,
        /** Key constant @type Number */
        Q: 81,
        /** Key constant @type Number */
        R: 82,
        /** Key constant @type Number */
        S: 83,
        /** Key constant @type Number */
        T: 84,
        /** Key constant @type Number */
        U: 85,
        /** Key constant @type Number */
        V: 86,
        /** Key constant @type Number */
        W: 87,
        /** Key constant @type Number */
        X: 88,
        /** Key constant @type Number */
        Y: 89,
        /** Key constant @type Number */
        Z: 90,
        /** Key constant @type Number */
        CONTEXT_MENU: 93,
        /** Key constant @type Number */
        NUM_ZERO: 96,
        /** Key constant @type Number */
        NUM_ONE: 97,
        /** Key constant @type Number */
        NUM_TWO: 98,
        /** Key constant @type Number */
        NUM_THREE: 99,
        /** Key constant @type Number */
        NUM_FOUR: 100,
        /** Key constant @type Number */
        NUM_FIVE: 101,
        /** Key constant @type Number */
        NUM_SIX: 102,
        /** Key constant @type Number */
        NUM_SEVEN: 103,
        /** Key constant @type Number */
        NUM_EIGHT: 104,
        /** Key constant @type Number */
        NUM_NINE: 105,
        /** Key constant @type Number */
        NUM_MULTIPLY: 106,
        /** Key constant @type Number */
        NUM_PLUS: 107,
        /** Key constant @type Number */
        NUM_MINUS: 109,
        /** Key constant @type Number */
        NUM_PERIOD: 110,
        /** Key constant @type Number */
        NUM_DIVISION: 111,
        /** Key constant @type Number */
        F1: 112,
        /** Key constant @type Number */
        F2: 113,
        /** Key constant @type Number */
        F3: 114,
        /** Key constant @type Number */
        F4: 115,
        /** Key constant @type Number */
        F5: 116,
        /** Key constant @type Number */
        F6: 117,
        /** Key constant @type Number */
        F7: 118,
        /** Key constant @type Number */
        F8: 119,
        /** Key constant @type Number */
        F9: 120,
        /** Key constant @type Number */
        F10: 121,
        /** Key constant @type Number */
        F11: 122,
        /** Key constant @type Number */
        F12: 123,

           /** @private */
        setEvent : function(e){
            if(e == this || (e && e.browserEvent)){ // already wrapped
                return e;
            }
            this.browserEvent = e;
            if(e){
                // normalize buttons
                this.button = e.button ? btnMap[e.button] : (e.which ? e.which-1 : -1);
                if(e.type == 'click' && this.button == -1){
                    this.button = 0;
                }
                this.type = e.type;
                this.shiftKey = e.shiftKey;
                // mac metaKey behaves like ctrlKey
                this.ctrlKey = e.ctrlKey || e.metaKey;
                this.altKey = e.altKey;
                // in getKey these will be normalized for the mac
                this.keyCode = e.keyCode;
                this.charCode = e.charCode;
                // cache the target for the delayed and or buffered events
                this.target = E.getTarget(e);
                // same for XY
                this.xy = E.getXY(e);
            }else{
                this.button = -1;
                this.shiftKey = false;
                this.ctrlKey = false;
                this.altKey = false;
                this.keyCode = 0;
                this.charCode = 0;
                this.target = null;
                this.xy = [0, 0];
            }
            return this;
        },

        /**
         * Stop the event (preventDefault and stopPropagation)
         */
        stopEvent : function(){
            if(this.browserEvent){
                if(this.browserEvent.type == 'mousedown'){
                    Ext.EventManager.stoppedMouseDownEvent.fire(this);
                }
                E.stopEvent(this.browserEvent);
            }
        },

        /**
         * Prevents the browsers default handling of the event.
         */
        preventDefault : function(){
            if(this.browserEvent){
                E.preventDefault(this.browserEvent);
            }
        },

        /** @private */
        isNavKeyPress : function(){
            var k = this.keyCode;
            k = Ext.isSafari ? (safariKeys[k] || k) : k;
            return (k >= 33 && k <= 40) || k == this.RETURN || k == this.TAB || k == this.ESC;
        },

        isSpecialKey : function(){
            var k = this.keyCode;
            return (this.type == 'keypress' && this.ctrlKey) || k == 9 || k == 13  || k == 40 || k == 27 ||
            (k == 16) || (k == 17) ||
            (k >= 18 && k <= 20) ||
            (k >= 33 && k <= 35) ||
            (k >= 36 && k <= 39) ||
            (k >= 44 && k <= 45);
        },

        /**
         * Cancels bubbling of the event.
         */
        stopPropagation : function(){
            if(this.browserEvent){
                if(this.browserEvent.type == 'mousedown'){
                    Ext.EventManager.stoppedMouseDownEvent.fire(this);
                }
                E.stopPropagation(this.browserEvent);
            }
        },

        /**
         * Gets the character code for the event.
         * @return {Number}
         */
        getCharCode : function(){
            return this.charCode || this.keyCode;
        },

        /**
         * Returns a normalized keyCode for the event.
         * @return {Number} The key code
         */
        getKey : function(){
            var k = this.keyCode || this.charCode;
            return Ext.isSafari ? (safariKeys[k] || k) : k;
        },

        /**
         * Gets the x coordinate of the event.
         * @return {Number}
         */
        getPageX : function(){
            return this.xy[0];
        },

        /**
         * Gets the y coordinate of the event.
         * @return {Number}
         */
        getPageY : function(){
            return this.xy[1];
        },

        /**
         * Gets the time of the event.
         * @return {Number}
         */
        getTime : function(){
            if(this.browserEvent){
                return E.getTime(this.browserEvent);
            }
            return null;
        },

        /**
         * Gets the page coordinates of the event.
         * @return {Array} The xy values like [x, y]
         */
        getXY : function(){
            return this.xy;
        },

        /**
         * Gets the target for the event.
         * @param {String} selector (optional) A simple selector to filter the target or look for an ancestor of the target
         * @param {Number/Mixed} maxDepth (optional) The max depth to
                search as a number or element (defaults to 10 || document.body)
         * @param {Boolean} returnEl (optional) True to return a Ext.Element object instead of DOM node
         * @return {HTMLelement}
         */
        getTarget : function(selector, maxDepth, returnEl){
            return selector ? Ext.fly(this.target).findParent(selector, maxDepth, returnEl) : (returnEl ? Ext.get(this.target) : this.target);
        },

        /**
         * Gets the related target.
         * @return {HTMLElement}
         */
        getRelatedTarget : function(){
            if(this.browserEvent){
                return E.getRelatedTarget(this.browserEvent);
            }
            return null;
        },

        /**
         * Normalizes mouse wheel delta across browsers
         * @return {Number} The delta
         */
        getWheelDelta : function(){
            var e = this.browserEvent;
            var delta = 0;
            if(e.wheelDelta){ /* IE/Opera. */
                delta = e.wheelDelta/120;
            }else if(e.detail){ /* Mozilla case. */
                delta = -e.detail/3;
            }
            return delta;
        },

        /**
         * Returns true if the control, meta, shift or alt key was pressed during this event.
         * @return {Boolean}
         */
        hasModifier : function(){
            return ((this.ctrlKey || this.altKey) || this.shiftKey) ? true : false;
        },

        /**
         * Returns true if the target of this event is a child of el.  If the target is el, it returns false.
         * Example usage:<pre><code>
// Handle click on any child of an element
Ext.getBody().on('click', function(e){
    if(e.within('some-el')){
        alert('Clicked on a child of some-el!');
    }
});

// Handle click directly on an element, ignoring clicks on child nodes
Ext.getBody().on('click', function(e,t){
    if((t.id == 'some-el') && !e.within(t, true)){
        alert('Clicked directly on some-el!');
    }
});
</code></pre>
         * @param {Mixed} el The id, DOM element or Ext.Element to check
         * @param {Boolean} related (optional) true to test if the related target is within el instead of the target
         * @return {Boolean}
         */
        within : function(el, related){
            var t = this[related ? "getRelatedTarget" : "getTarget"]();
            return t && Ext.fly(el).contains(t);
        },

        getPoint : function(){
            return new Ext.lib.Point(this.xy[0], this.xy[1]);
        }
    };

    return new Ext.EventObjectImpl();
}();