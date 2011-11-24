Function.prototype.bind = function() {
    if (arguments.length < 1 && typeof arguments[0] != "undefined") return this;
    var __method = this, args = [];
	for(var i=0;i<arguments.length;i++){ args.push(arguments[i]);}
	
	var object = args.shift();
    return function() {
	  var args_to_apply = []
	  
	  for(var i=0;i<args.length;i++){ args_to_apply.push(args[i]);}
	  for(var i=0;i<arguments.length;i++){ args_to_apply.push(arguments[i]);}
      return __method.apply(object, args_to_apply);
    }
  };