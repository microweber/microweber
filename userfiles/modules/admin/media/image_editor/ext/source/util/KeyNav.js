/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.KeyNav
 * <p>Provides a convenient wrapper for normalized keyboard navigation.  KeyNav allows you to bind
 * navigation keys to function calls that will get called when the keys are pressed, providing an easy
 * way to implement custom navigation schemes for any UI component.</p>
 * <p>The following are all of the possible keys that can be implemented: enter, left, right, up, down, tab, esc,
 * pageUp, pageDown, del, home, end.  Usage:</p>
 <pre><code>
var nav = new Ext.KeyNav("my-element", {
    "left" : function(e){
        this.moveLeft(e.ctrlKey);
    },
    "right" : function(e){
        this.moveRight(e.ctrlKey);
    },
    "enter" : function(e){
        this.save();
    },
    scope : this
});
</code></pre>
 * @constructor
 * @param {Mixed} el The element to bind to
 * @param {Object} config The config
 */
Ext.KeyNav = function(el, config){
    this.el = Ext.get(el);
    Ext.apply(this, config);
    if(!this.disabled){
        this.disabled = true;
        this.enable();
    }
};

Ext.KeyNav.prototype = {
    /**
     * @cfg {Boolean} disabled
     * True to disable this KeyNav instance (defaults to false)
     */
    disabled : false,
    /**
     * @cfg {String} defaultEventAction
     * The method to call on the {@link Ext.EventObject} after this KeyNav intercepts a key.  Valid values are
     * {@link Ext.EventObject#stopEvent}, {@link Ext.EventObject#preventDefault} and
     * {@link Ext.EventObject#stopPropagation} (defaults to 'stopEvent')
     */
    defaultEventAction: "stopEvent",
    /**
     * @cfg {Boolean} forceKeyDown
     * Handle the keydown event instead of keypress (defaults to false).  KeyNav automatically does this for IE since
     * IE does not propagate special keys on keypress, but setting this to true will force other browsers to also
     * handle keydown instead of keypress.
     */
    forceKeyDown : false,

    // private
    prepareEvent : function(e){
        var k = e.getKey();
        var h = this.keyToHandler[k];
        //if(h && this[h]){
        //    e.stopPropagation();
        //}
        if(Ext.isSafari2 && h && k >= 37 && k <= 40){
            e.stopEvent();
        }
    },

    // private
    relay : function(e){
        var k = e.getKey();
        var h = this.keyToHandler[k];
        if(h && this[h]){
            if(this.doRelay(e, this[h], h) !== true){
                e[this.defaultEventAction]();
            }
        }
    },

    // private
    doRelay : function(e, h, hname){
        return h.call(this.scope || this, e);
    },

    // possible handlers
    enter : false,
    left : false,
    right : false,
    up : false,
    down : false,
    tab : false,
    esc : false,
    pageUp : false,
    pageDown : false,
    del : false,
    home : false,
    end : false,

    // quick lookup hash
    keyToHandler : {
        37 : "left",
        39 : "right",
        38 : "up",
        40 : "down",
        33 : "pageUp",
        34 : "pageDown",
        46 : "del",
        36 : "home",
        35 : "end",
        13 : "enter",
        27 : "esc",
        9  : "tab"
    },

	/**
	 * Enable this KeyNav
	 */
	enable: function(){
		if(this.disabled){
            if(this.forceKeyDown || Ext.isIE || Ext.isSafari3 || Ext.isAir){
                this.el.on("keydown", this.relay,  this);
            }else{
                this.el.on("keydown", this.prepareEvent,  this);
                this.el.on("keypress", this.relay,  this);
            }
		    this.disabled = false;
		}
	},

	/**
	 * Disable this KeyNav
	 */
	disable: function(){
		if(!this.disabled){
		    if(this.forceKeyDown || Ext.isIE || Ext.isSafari3 || Ext.isAir){
                this.el.un("keydown", this.relay);
            }else{
                this.el.un("keydown", this.prepareEvent);
                this.el.un("keypress", this.relay);
            }
		    this.disabled = true;
		}
	}
};