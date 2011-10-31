/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.WindowGroup
 * An object that represents a group of {@link Ext.Window} instances and provides z-order management
 * and window activation behavior.
 * @constructor
 */
Ext.WindowGroup = function(){
    var list = {};
    var accessList = [];
    var front = null;

    // private
    var sortWindows = function(d1, d2){
        return (!d1._lastAccess || d1._lastAccess < d2._lastAccess) ? -1 : 1;
    };

    // private
    var orderWindows = function(){
        var a = accessList, len = a.length;
        if(len > 0){
            a.sort(sortWindows);
            var seed = a[0].manager.zseed;
            for(var i = 0; i < len; i++){
                var win = a[i];
                if(win && !win.hidden){
                    win.setZIndex(seed + (i*10));
                }
            }
        }
        activateLast();
    };

    // private
    var setActiveWin = function(win){
        if(win != front){
            if(front){
                front.setActive(false);
            }
            front = win;
            if(win){
                win.setActive(true);
            }
        }
    };

    // private
    var activateLast = function(){
        for(var i = accessList.length-1; i >=0; --i) {
            if(!accessList[i].hidden){
                setActiveWin(accessList[i]);
                return;
            }
        }
        // none to activate
        setActiveWin(null);
    };

    return {
        /**
         * The starting z-index for windows (defaults to 9000)
         * @type Number The z-index value
         */
        zseed : 9000,

        // private
        register : function(win){
            list[win.id] = win;
            accessList.push(win);
            win.on('hide', activateLast);
        },

        // private
        unregister : function(win){
            delete list[win.id];
            win.un('hide', activateLast);
            accessList.remove(win);
        },

        /**
         * Gets a registered window by id.
         * @param {String/Object} id The id of the window or a {@link Ext.Window} instance
         * @return {Ext.Window}
         */
        get : function(id){
            return typeof id == "object" ? id : list[id];
        },

        /**
         * Brings the specified window to the front of any other active windows.
         * @param {String/Object} win The id of the window or a {@link Ext.Window} instance
         * @return {Boolean} True if the dialog was brought to the front, else false
         * if it was already in front
         */
        bringToFront : function(win){
            win = this.get(win);
            if(win != front){
                win._lastAccess = new Date().getTime();
                orderWindows();
                return true;
            }
            return false;
        },

        /**
         * Sends the specified window to the back of other active windows.
         * @param {String/Object} win The id of the window or a {@link Ext.Window} instance
         * @return {Ext.Window} The window
         */
        sendToBack : function(win){
            win = this.get(win);
            win._lastAccess = -(new Date().getTime());
            orderWindows();
            return win;
        },

        /**
         * Hides all windows in the group.
         */
        hideAll : function(){
            for(var id in list){
                if(list[id] && typeof list[id] != "function" && list[id].isVisible()){
                    list[id].hide();
                }
            }
        },

        /**
         * Gets the currently-active window in the group.
         * @return {Ext.Window} The active window
         */
        getActive : function(){
            return front;
        },

        /**
         * Returns zero or more windows in the group using the custom search function passed to this method.
         * The function should accept a single {@link Ext.Window} reference as its only argument and should
         * return true if the window matches the search criteria, otherwise it should return false.
         * @param {Function} fn The search function
         * @param {Object} scope (optional) The scope in which to execute the function (defaults to the window
         * that gets passed to the function if not specified)
         * @return {Array} An array of zero or more matching windows
         */
        getBy : function(fn, scope){
            var r = [];
            for(var i = accessList.length-1; i >=0; --i) {
                var win = accessList[i];
                if(fn.call(scope||win, win) !== false){
                    r.push(win);
                }
            }
            return r;
        },

        /**
         * Executes the specified function once for every window in the group, passing each
         * window as the only parameter. Returning false from the function will stop the iteration.
         * @param {Function} fn The function to execute for each item
         * @param {Object} scope (optional) The scope in which to execute the function
         */
        each : function(fn, scope){
            for(var id in list){
                if(list[id] && typeof list[id] != "function"){
                    if(fn.call(scope || list[id], list[id]) === false){
                        return;
                    }
                }
            }
        }
    };
};


/**
 * @class Ext.WindowMgr
 * @extends Ext.WindowGroup
 * The default global window group that is available automatically.  To have more than one group of windows
 * with separate z-order stacks, create additional instances of {@link Ext.WindowGroup} as needed.
 * @singleton
 */
Ext.WindowMgr = new Ext.WindowGroup();