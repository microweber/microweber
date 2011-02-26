// Happy April Fools Day 2008
// The code is good - read for inspiration, but
// please don't use this :-(

// Inspired by base2 and Prototype
(function(){
  var initializing = false,
    fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;
    
  jQuery.Class = function(){};

  // Create a new Class that inherits from this class
  jQuery.Class.create = function(prop) {
    var _super = this.prototype;
     
    // Instantiate a base class (but only create the instance,
    // don't run the init constructor)
    initializing = true;
    var prototype = new this();
    initializing = false;
     
    // Copy the properties over onto the new prototype
    for (var name in prop) {
      // Check if we're overwriting an existing function
      prototype[name] = typeof prop[name] == "function" &&
      typeof _super[name] == "function" && fnTest.test(prop[name]) ?
      (function(name, fn){
        return function() {
          var tmp = this._super;
           
          // Add a new ._super() method that is the same method
          // but on the super-class
          this._super = _super[name];
           
          // The method only need to be bound temporarily, so we
          // remove it when we're done executing
          var ret = fn.apply(this, arguments);     
          this._super = tmp;
           
          return ret;
        };
      })(name, prop[name]) :
      prop[name];
    }
     
    // The dummy class constructor
    function Class() {
      // All construction is actually done in the init method
      if ( !initializing && Class.prototype.init )
        return Class.prototype.init.apply(this, arguments);
    }
     
    // Populate our constructed prototype object
    Class.prototype = prototype;
     
    // Enforce the constructor to be what we expect
    Class.prototype.constructor = Class;
  
    // And make this class extendable
    Class.extend = arguments.callee;
     
    return Class;
  };
  
  jQuery.querySelectorAll = function(){
    return jQuery.apply(jQuery, arguments);
  };
  
  jQuery.querySelector = function(){
    return jQuery.querySelectorAll.apply(jQuery, arguments)[0];
  };
  
  jQuery.fn.forEach = function(fn){
    return this.each(function(i){
      fn(this, i);
    });
  };
  
  jQuery.fn.attach = function(fn){
    var attach = fn.attach || (new fn).attach || function(){};
    return this.forEach(function(elem){
      attach.call(fn, elem);
    });
  };
  
  jQuery.DOM = buildClass(["prepend", "append", ["before", "insertBefore"],
      ["after", "insertAfter"], "wrap",
      "wrapInner", "wrapAll", "clone", "empty", "remove", "replaceWith",
      ["removeAttr", "removeAttribute"], ["addClass", "addClassName"],
      ["hasClass", "hasClassName"], ["removeClass", "removeClassName"],
      ["offset", "getOffset"]],
    [["text", "Text"], ["html", "HTML"], ["attr", "Attribute"], 
      ["val", "Value"], ["height", "Height"], ["width", "Width"],
      ["css", "CSS"]]);
    
  jQuery.Traverse = buildClass([ ["children", "getChildElements"],
    ["find", "getDescendantElements"], ["next", "getNextSiblingElements"],
    ["nextAll", "getAllNextSiblingElements"], ["parent", "getParentElements"],
    ["parents", "getAncestorElements"], ["prev", "getPreviousSiblingElements"],
    ["prevAll", "getAllPreviousSiblingElements"],
    ["siblings", "getSiblingElements"], ["filter", "filterSelector"] ]);
  
  jQuery.Events = buildClass([["bind", "addEventListener"],
    ["unbind", "removeEventListener"], ["trigger", "triggerEvent"],
    "hover", "toggle"]);
  
  jQuery.fn.buildAnimation = function(options){
    var self = this;
    
    return {
      start: function(){
        self.animate(options);
      },
      stop: function(){
        self.stop();
      }
    };
  };
  
  jQuery.Effects = buildClass(["show", "hide", "toggle",
    "buildAnimation", "queue", "dequeue"]);
  
  jQuery.fn.ajax = jQuery.ajax;
  
  jQuery.Ajax = buildClass([["ajax", "request"], ["load", "loadAndInsert"],
    ["ajaxSetup", "setup"], ["serialize", "getSerializedString"],
    ["serializeArray", "getSerializedArray"]]);
  
  function buildClass(methods, getset){
    var base = {};
    
    jQuery.each(getset || [], function(i, name){
      if ( !(name instanceof Array) )
        name = [name, name];
      
      methods.push([name[0], "get" + name[1]], [name[0], "set" + name[1]]);
    });
    
    jQuery.each(methods, function(i, name){
      var showName = name;
      
      if ( name instanceof Array ) {
        showName = name[1];
        name = name[0];
      }
        
      base[showName] = jQuery.Class.create({
        init: function(){
          var args = Array.prototype.slice.call(arguments);
  
          if ( this.constructor == base[showName] )
            this.arguments = args;
          else
            return base[showName].prototype.attach.apply( base[showName], args );
        },
        arguments: [],
        attach: function(elem){
          var args = arguments.length == 1 ?
            this.arguments :
            Array.prototype.slice.call(arguments, 1);
          
          if ( args.length ) {
            var fn = args[ args.length - 1 ];
            if ( typeof fn == "function" ) {
              args[ args.length - 1 ] = function(){
                var args = Array.prototype.slice.call(arguments);
                return fn.apply( this, [this].concat(args) );
              };
            }
          }
          
          return jQuery.fn[name].apply( jQuery(elem), args );
        }
      });
    });
    
    return base;
  }
})();