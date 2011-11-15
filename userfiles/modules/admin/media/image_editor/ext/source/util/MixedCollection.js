/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.util.MixedCollection
 * @extends Ext.util.Observable
 * A Collection class that maintains both numeric indexes and keys and exposes events.
 * @constructor
 * @param {Boolean} allowFunctions True if the addAll function should add function references to the
 * collection (defaults to false)
 * @param {Function} keyFn A function that can accept an item of the type(s) stored in this MixedCollection
 * and return the key value for that item.  This is used when available to look up the key on items that
 * were passed without an explicit key parameter to a MixedCollection method.  Passing this parameter is
 * equivalent to providing an implementation for the {@link #getKey} method.
 */
Ext.util.MixedCollection = function(allowFunctions, keyFn){
    this.items = [];
    this.map = {};
    this.keys = [];
    this.length = 0;
    this.addEvents(
        /**
         * @event clear
         * Fires when the collection is cleared.
         */
        "clear",
        /**
         * @event add
         * Fires when an item is added to the collection.
         * @param {Number} index The index at which the item was added.
         * @param {Object} o The item added.
         * @param {String} key The key associated with the added item.
         */
        "add",
        /**
         * @event replace
         * Fires when an item is replaced in the collection.
         * @param {String} key he key associated with the new added.
         * @param {Object} old The item being replaced.
         * @param {Object} new The new item.
         */
        "replace",
        /**
         * @event remove
         * Fires when an item is removed from the collection.
         * @param {Object} o The item being removed.
         * @param {String} key (optional) The key associated with the removed item.
         */
        "remove",
        "sort"
    );
    this.allowFunctions = allowFunctions === true;
    if(keyFn){
        this.getKey = keyFn;
    }
    Ext.util.MixedCollection.superclass.constructor.call(this);
};

Ext.extend(Ext.util.MixedCollection, Ext.util.Observable, {
    allowFunctions : false,

/**
 * Adds an item to the collection. Fires the {@link #add} event when complete.
 * @param {String} key <p>The key to associate with the item, or the new item.</p>
 * <p>If you supplied a {@link #getKey} implementation for this MixedCollection, or if the key
 * of your stored items is in a property called <tt><b>id</b></tt>, then the MixedCollection
 * will be able to <i>derive</i> the key for the new item. In this case just pass the new item in
 * this parameter.</p>
 * @param {Object} o The item to add.
 * @return {Object} The item added.
 */
    add : function(key, o){
        if(arguments.length == 1){
            o = arguments[0];
            key = this.getKey(o);
        }
        if(typeof key == "undefined" || key === null){
            this.length++;
            this.items.push(o);
            this.keys.push(null);
        }else{
            var old = this.map[key];
            if(old){
                return this.replace(key, o);
            }
            this.length++;
            this.items.push(o);
            this.map[key] = o;
            this.keys.push(key);
        }
        this.fireEvent("add", this.length-1, o, key);
        return o;
    },

/**
  * MixedCollection has a generic way to fetch keys if you implement getKey.  The default implementation
  * simply returns <tt style="font-weight:bold;">item.id</tt> but you can provide your own implementation
  * to return a different value as in the following examples:
<pre><code>
// normal way
var mc = new Ext.util.MixedCollection();
mc.add(someEl.dom.id, someEl);
mc.add(otherEl.dom.id, otherEl);
//and so on

// using getKey
var mc = new Ext.util.MixedCollection();
mc.getKey = function(el){
   return el.dom.id;
};
mc.add(someEl);
mc.add(otherEl);

// or via the constructor
var mc = new Ext.util.MixedCollection(false, function(el){
   return el.dom.id;
});
mc.add(someEl);
mc.add(otherEl);
</code></pre>
 * @param {Object} item The item for which to find the key.
 * @return {Object} The key for the passed item.
 */
    getKey : function(o){
         return o.id;
    },

/**
 * Replaces an item in the collection. Fires the {@link #replace} event when complete.
 * @param {String} key <p>The key associated with the item to replace, or the replacement item.</p>
 * <p>If you supplied a {@link #getKey} implementation for this MixedCollection, or if the key
 * of your stored items is in a property called <tt><b>id</b></tt>, then the MixedCollection
 * will be able to <i>derive</i> the key of the replacement item. If you want to replace an item
 * with one having the same key value, then just pass the replacement item in this parameter.</p>
 * @param o {Object} o (optional) If the first parameter passed was a key, the item to associate
 * with that key.
 * @return {Object}  The new item.
 */
    replace : function(key, o){
        if(arguments.length == 1){
            o = arguments[0];
            key = this.getKey(o);
        }
        var old = this.item(key);
        if(typeof key == "undefined" || key === null || typeof old == "undefined"){
             return this.add(key, o);
        }
        var index = this.indexOfKey(key);
        this.items[index] = o;
        this.map[key] = o;
        this.fireEvent("replace", key, old, o);
        return o;
    },

/**
 * Adds all elements of an Array or an Object to the collection.
 * @param {Object/Array} objs An Object containing properties which will be added to the collection, or
 * an Array of values, each of which are added to the collection.
 */
    addAll : function(objs){
        if(arguments.length > 1 || Ext.isArray(objs)){
            var args = arguments.length > 1 ? arguments : objs;
            for(var i = 0, len = args.length; i < len; i++){
                this.add(args[i]);
            }
        }else{
            for(var key in objs){
                if(this.allowFunctions || typeof objs[key] != "function"){
                    this.add(key, objs[key]);
                }
            }
        }
    },

/**
 * Executes the specified function once for every item in the collection, passing the following arguments:
 * <div class="mdetail-params"><ul>
 * <li><b>item</b> : Mixed<p class="sub-desc">The collection item</p></li>
 * <li><b>index</b> : Number<p class="sub-desc">The item's index</p></li>
 * <li><b>length</b> : Number<p class="sub-desc">The total number of items in the collection</p></li>
 * </ul></div>
 * The function should return a boolean value. Returning false from the function will stop the iteration.
 * @param {Function} fn The function to execute for each item.
 * @param {Object} scope (optional) The scope in which to execute the function.
 */
    each : function(fn, scope){
        var items = [].concat(this.items); // each safe for removal
        for(var i = 0, len = items.length; i < len; i++){
            if(fn.call(scope || items[i], items[i], i, len) === false){
                break;
            }
        }
    },

/**
 * Executes the specified function once for every key in the collection, passing each
 * key, and its associated item as the first two parameters.
 * @param {Function} fn The function to execute for each item.
 * @param {Object} scope (optional) The scope in which to execute the function.
 */
    eachKey : function(fn, scope){
        for(var i = 0, len = this.keys.length; i < len; i++){
            fn.call(scope || window, this.keys[i], this.items[i], i, len);
        }
    },

    /**
     * Returns the first item in the collection which elicits a true return value from the
     * passed selection function.
     * @param {Function} fn The selection function to execute for each item.
     * @param {Object} scope (optional) The scope in which to execute the function.
     * @return {Object} The first item in the collection which returned true from the selection function.
     */
    find : function(fn, scope){
        for(var i = 0, len = this.items.length; i < len; i++){
            if(fn.call(scope || window, this.items[i], this.keys[i])){
                return this.items[i];
            }
        }
        return null;
    },

/**
 * Inserts an item at the specified index in the collection. Fires the {@link #add} event when complete.
 * @param {Number} index The index to insert the item at.
 * @param {String} key The key to associate with the new item, or the item itself.
 * @param {Object} o (optional) If the second parameter was a key, the new item.
 * @return {Object} The item inserted.
 */
    insert : function(index, key, o){
        if(arguments.length == 2){
            o = arguments[1];
            key = this.getKey(o);
        }
        if(index >= this.length){
            return this.add(key, o);
        }
        this.length++;
        this.items.splice(index, 0, o);
        if(typeof key != "undefined" && key != null){
            this.map[key] = o;
        }
        this.keys.splice(index, 0, key);
        this.fireEvent("add", index, o, key);
        return o;
    },

/**
 * Remove an item from the collection.
 * @param {Object} o The item to remove.
 * @return {Object} The item removed or false if no item was removed.
 */
    remove : function(o){
        return this.removeAt(this.indexOf(o));
    },

/**
 * Remove an item from a specified index in the collection. Fires the {@link #remove} event when complete.
 * @param {Number} index The index within the collection of the item to remove.
 * @return {Object} The item removed or false if no item was removed.
 */
    removeAt : function(index){
        if(index < this.length && index >= 0){
            this.length--;
            var o = this.items[index];
            this.items.splice(index, 1);
            var key = this.keys[index];
            if(typeof key != "undefined"){
                delete this.map[key];
            }
            this.keys.splice(index, 1);
            this.fireEvent("remove", o, key);
            return o;
        }
        return false;
    },

/**
 * Removed an item associated with the passed key fom the collection.
 * @param {String} key The key of the item to remove.
 * @return {Object} The item removed or false if no item was removed.
 */
    removeKey : function(key){
        return this.removeAt(this.indexOfKey(key));
    },

/**
 * Returns the number of items in the collection.
 * @return {Number} the number of items in the collection.
 */
    getCount : function(){
        return this.length;
    },

/**
 * Returns index within the collection of the passed Object.
 * @param {Object} o The item to find the index of.
 * @return {Number} index of the item.
 */
    indexOf : function(o){
        return this.items.indexOf(o);
    },

/**
 * Returns index within the collection of the passed key.
 * @param {String} key The key to find the index of.
 * @return {Number} index of the key.
 */
    indexOfKey : function(key){
        return this.keys.indexOf(key);
    },

/**
 * Returns the item associated with the passed key OR index. Key has priority over index.  This is the equivalent
 * of calling {@link #key} first, then if nothing matched calling {@link #itemAt}.
 * @param {String/Number} key The key or index of the item.
 * @return {Object} The item associated with the passed key.
 */
    item : function(key){
        var item = typeof this.map[key] != "undefined" ? this.map[key] : this.items[key];
        return typeof item != 'function' || this.allowFunctions ? item : null; // for prototype!
    },

/**
 * Returns the item at the specified index.
 * @param {Number} index The index of the item.
 * @return {Object} The item at the specified index.
 */
    itemAt : function(index){
        return this.items[index];
    },

/**
 * Returns the item associated with the passed key.
 * @param {String/Number} key The key of the item.
 * @return {Object} The item associated with the passed key.
 */
    key : function(key){
        return this.map[key];
    },

/**
 * Returns true if the collection contains the passed Object as an item.
 * @param {Object} o  The Object to look for in the collection.
 * @return {Boolean} True if the collection contains the Object as an item.
 */
    contains : function(o){
        return this.indexOf(o) != -1;
    },

/**
 * Returns true if the collection contains the passed Object as a key.
 * @param {String} key The key to look for in the collection.
 * @return {Boolean} True if the collection contains the Object as a key.
 */
    containsKey : function(key){
        return typeof this.map[key] != "undefined";
    },

/**
 * Removes all items from the collection.  Fires the {@link #clear} event when complete.
 */
    clear : function(){
        this.length = 0;
        this.items = [];
        this.keys = [];
        this.map = {};
        this.fireEvent("clear");
    },

/**
 * Returns the first item in the collection.
 * @return {Object} the first item in the collection..
 */
    first : function(){
        return this.items[0];
    },

/**
 * Returns the last item in the collection.
 * @return {Object} the last item in the collection..
 */
    last : function(){
        return this.items[this.length-1];
    },

    // private
    _sort : function(property, dir, fn){
        var dsc = String(dir).toUpperCase() == "DESC" ? -1 : 1;
        fn = fn || function(a, b){
            return a-b;
        };
        var c = [], k = this.keys, items = this.items;
        for(var i = 0, len = items.length; i < len; i++){
            c[c.length] = {key: k[i], value: items[i], index: i};
        }
        c.sort(function(a, b){
            var v = fn(a[property], b[property]) * dsc;
            if(v == 0){
                v = (a.index < b.index ? -1 : 1);
            }
            return v;
        });
        for(var i = 0, len = c.length; i < len; i++){
            items[i] = c[i].value;
            k[i] = c[i].key;
        }
        this.fireEvent("sort", this);
    },

    /**
     * Sorts this collection with the passed comparison function
     * @param {String} direction (optional) "ASC" or "DESC"
     * @param {Function} fn (optional) comparison function
     */
    sort : function(dir, fn){
        this._sort("value", dir, fn);
    },

    /**
     * Sorts this collection by keys
     * @param {String} direction (optional) "ASC" or "DESC"
     * @param {Function} fn (optional) a comparison function (defaults to case insensitive string)
     */
    keySort : function(dir, fn){
        this._sort("key", dir, fn || function(a, b){
            return String(a).toUpperCase()-String(b).toUpperCase();
        });
    },

    /**
     * Returns a range of items in this collection
     * @param {Number} startIndex (optional) defaults to 0
     * @param {Number} endIndex (optional) default to the last item
     * @return {Array} An array of items
     */
    getRange : function(start, end){
        var items = this.items;
        if(items.length < 1){
            return [];
        }
        start = start || 0;
        end = Math.min(typeof end == "undefined" ? this.length-1 : end, this.length-1);
        var r = [];
        if(start <= end){
            for(var i = start; i <= end; i++) {
        	    r[r.length] = items[i];
            }
        }else{
            for(var i = start; i >= end; i--) {
        	    r[r.length] = items[i];
            }
        }
        return r;
    },

    /**
     * Filter the <i>objects</i> in this collection by a specific property.
     * Returns a new collection that has been filtered.
     * @param {String} property A property on your objects
     * @param {String/RegExp} value Either string that the property values
     * should start with or a RegExp to test against the property
     * @param {Boolean} anyMatch (optional) True to match any part of the string, not just the beginning
     * @param {Boolean} caseSensitive (optional) True for case sensitive comparison (defaults to False).
     * @return {MixedCollection} The new filtered collection
     */
    filter : function(property, value, anyMatch, caseSensitive){
        if(Ext.isEmpty(value, false)){
            return this.clone();
        }
        value = this.createValueMatcher(value, anyMatch, caseSensitive);
        return this.filterBy(function(o){
            return o && value.test(o[property]);
        });
	},

    /**
     * Filter by a function. Returns a <i>new</i> collection that has been filtered.
     * The passed function will be called with each object in the collection.
     * If the function returns true, the value is included otherwise it is filtered.
     * @param {Function} fn The function to be called, it will receive the args o (the object), k (the key)
     * @param {Object} scope (optional) The scope of the function (defaults to this)
     * @return {MixedCollection} The new filtered collection
     */
    filterBy : function(fn, scope){
        var r = new Ext.util.MixedCollection();
        r.getKey = this.getKey;
        var k = this.keys, it = this.items;
        for(var i = 0, len = it.length; i < len; i++){
            if(fn.call(scope||this, it[i], k[i])){
				r.add(k[i], it[i]);
			}
        }
        return r;
    },

    /**
     * Finds the index of the first matching object in this collection by a specific property/value.
     * @param {String} property The name of a property on your objects.
     * @param {String/RegExp} value A string that the property values
     * should start with or a RegExp to test against the property.
     * @param {Number} start (optional) The index to start searching at (defaults to 0).
     * @param {Boolean} anyMatch (optional) True to match any part of the string, not just the beginning.
     * @param {Boolean} caseSensitive (optional) True for case sensitive comparison.
     * @return {Number} The matched index or -1
     */
    findIndex : function(property, value, start, anyMatch, caseSensitive){
        if(Ext.isEmpty(value, false)){
            return -1;
        }
        value = this.createValueMatcher(value, anyMatch, caseSensitive);
        return this.findIndexBy(function(o){
            return o && value.test(o[property]);
        }, null, start);
	},

    /**
     * Find the index of the first matching object in this collection by a function.
     * If the function returns <i>true</i> it is considered a match.
     * @param {Function} fn The function to be called, it will receive the args o (the object), k (the key).
     * @param {Object} scope (optional) The scope of the function (defaults to this).
     * @param {Number} start (optional) The index to start searching at (defaults to 0).
     * @return {Number} The matched index or -1
     */
    findIndexBy : function(fn, scope, start){
        var k = this.keys, it = this.items;
        for(var i = (start||0), len = it.length; i < len; i++){
            if(fn.call(scope||this, it[i], k[i])){
				return i;
            }
        }
        if(typeof start == 'number' && start > 0){
            for(var i = 0; i < start; i++){
                if(fn.call(scope||this, it[i], k[i])){
                    return i;
                }
            }
        }
        return -1;
    },

    // private
    createValueMatcher : function(value, anyMatch, caseSensitive){
        if(!value.exec){ // not a regex
            value = String(value);
            value = new RegExp((anyMatch === true ? '' : '^') + Ext.escapeRe(value), caseSensitive ? '' : 'i');
        }
        return value;
    },

    /**
     * Creates a duplicate of this collection
     * @return {MixedCollection}
     */
    clone : function(){
        var r = new Ext.util.MixedCollection();
        var k = this.keys, it = this.items;
        for(var i = 0, len = it.length; i < len; i++){
            r.add(k[i], it[i]);
        }
        r.getKey = this.getKey;
        return r;
    }
});
/**
 * Returns the item associated with the passed key or index.
 * @method
 * @param {String/Number} key The key or index of the item.
 * @return {Object} The item associated with the passed key.
 */
Ext.util.MixedCollection.prototype.get = Ext.util.MixedCollection.prototype.item;