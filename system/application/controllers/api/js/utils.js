mw.utils = {};
mw.utils.elementBlink =  function(elementId, duration) {
	if (!duration) {
		duration = 400;
	}
	$('#' + elementId).fadeOut(duration, function() {
		$('#' + elementId).fadeIn(duration);
	});
}

mw.browser = {
	msie : function() {
		return (document.all && window.external) ? true : false;
	},
	msie6 : function() {
		return (!window.XMLHttpRequest) ? true : false;
	},
	msie7 : function() {
		return (document.all && (!window.XDomainRequest) && window.XMLHttpRequest) ? true
				: false;
	},
	msie8 : function() {
		return window.XDomainRequest ? true : false;
	},
	opera : function() {
		return window.opera ? true : false;
	},
	firefox : function() {
		return (window.globalStorage && window.postMessage) ? true : false;
	},
	chrome : function() {
		return window.chrome ? true : false;
	},
	safari : function() {
		return ((document.childNodes) && (!document.all)
				&& (!navigator.taintEnabled) && (!navigator.accentColorName) && (!window.chrome)) ? true
				: false;
	},
	webkit : function() {
		return (typeof document.body.style.webkitBorderRadius != "undefined") ? true
				: false;
	},
	khtml : function() {
		return (navigator.vendor == "KDE") ? true : false;
	},
	konqueror : function() {
		return ((navigator.vendor == 'KDE') || (document.childNodes)
				&& (!document.all) && (!navigator.taintEnabled)) ? true : false;
	}

}

var Base64 = {

	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function(input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

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

			output = output + this._keyStr.charAt(enc1)
					+ this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3)
					+ this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function(input) {
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

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function(string) {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";

		for ( var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if ((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			} else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function(utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while (i < utftext.length) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if ((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i + 1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			} else {
				c2 = utftext.charCodeAt(i + 1);
				c3 = utftext.charCodeAt(i + 2);
				string += String.fromCharCode(((c & 15) << 12)
						| ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

}

function serialize (mixed_value) {
    // http://kevin.vanzonneveld.net
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   improved by: Le Torbi (http://www.letorbi.de/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   bugfixed by: Ben (http://benblume.co.uk/)
    // -    depends on: utf8_encode
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'

	var _utf8Size = function (str) {
	    var size = 0, i = 0, l = str.length, code = '';
	    for (i = 0; i < l; i++) {
	        code = str.charCodeAt(i);
	        if (code < 0x0080) {
	            size += 1;
	        } else if (code < 0x0800) {
	            size += 2;
	        } else {
	        	size += 3;
			}
	    }
	    return size;
	};
    var _getType = function (inp) {
        var type = typeof inp, match;
        var key;

        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {
        case "function": 
            val = ""; 
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
			val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = this.serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
			    if (mixed_value.hasOwnProperty(key)) {
               	   ktype = _getType(mixed_value[key]);
	               if (ktype === "function") { 
	                   continue; 
	               }
               
	               okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
	               vals += this.serialize(okey) +
	                       this.serialize(mixed_value[key]);
	               count++;
		        }
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = "N";
            break;
    }
    if (type !== "object" && type !== "array") {
        val += ";";
    }
    return val;
}

function base64Encode(text){

    if (/([^\u0000-\u00ff])/.test(text)){
        throw new Error("Can't base64 encode non-ASCII characters.");
    } 

    var digits = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        i = 0,
        cur, prev, byteNum,
        result=[];      

    while(i < text.length){

        cur = text.charCodeAt(i);
        byteNum = i % 3;

        switch(byteNum){
            case 0: //first byte
                result.push(digits.charAt(cur >> 2));
                break;

            case 1: //second byte
                result.push(digits.charAt((prev & 3) << 4 | (cur >> 4)));
                break;

            case 2: //third byte
                result.push(digits.charAt((prev & 0x0f) << 2 | (cur >> 6)));
                result.push(digits.charAt(cur & 0x3f));
                break;
        }

        prev = cur;
        i++;
    }

    if (byteNum == 0){
        result.push(digits.charAt((prev & 3) << 4));
        result.push("==");
    } else if (byteNum == 1){
        result.push(digits.charAt((prev & 0x0f) << 2));
        result.push("=");
    }

    return result.join("");
}

mw.prevent = function(event){

    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    event.preventDefault();
}

mw.image = {}
mw.image.edit = {
  over:function(image){
    var left = $(image).offset().left;
    var top = $(image).offset().top;
    var edit = document.createElement('a');
        edit.innerHTML = 'Edit';
        edit.className = 'mw_image_edit mw_image';
        edit.style.left = left + 10 + 'px';
        edit.style.top = top + 10 + 'px';
        edit.style.position = 'absolute';
        edit.style.zIndex = '10';
    var del = document.createElement('a');
        del.innerHTML = 'Delete';
        del.className = 'mw_image_del mw_image';
        del.style.left = left + 60 + 'px';
        del.style.top = top + 10 + 'px';
        del.style.position = 'absolute';
        del.style.zIndex = '10';
    var url = $(image).attr("src");
    $(edit).click(function(){
       mw.image.edit.popup(url);
    });
    $(edit).hover(function(){
        $(this).addClass("mw_image_edit_hover")
    }, function(){
        $(this).removeClass("mw_image_edit_hover")
    });

    $(del).click(function(){
       mw.image.edit.del(image);
    });
    $(del).hover(function(){
        $(this).addClass("mw_image_edit_hover")
    }, function(){
        $(this).removeClass("mw_image_edit_hover")
    });

    document.body.appendChild(edit);
    document.body.appendChild(del);

  },
  del:function(image){
    mw.modal.confirm({
      yes:function(){
        $(image).remove()
        $(".mw_image").remove();
      },
      html:"Are you sure you want to delete this image?"
    })

  },
  out:function(image){
    setTimeout(function(){
      if($(".mw_image_edit_hover").length==0){
         $(".mw_image").remove();
      }
    }, 10)
  },
  popup:function(url){
    mw.modal.init({
      width: 700,
      height: 600,
      id:'mw_image_edit_pop',
      oninit:function(){
         $(".mw_image").remove();
      },
      html:"<img src='" + url + "' />"
    })
  },
  init:function(image){


     $(image).hover(function(){
        $(this).addClass("mw_image_edit_hover");
        if($(".mw_image").length==0){
          mw.image.edit.over(image);
        }
     }, function(){
        $(this).removeClass("mw_image_edit_hover");
        mw.image.edit.out(image)
     });
  },
  disable:function(image){
    $(image).unbind('mouseenter mouseleave');
  }
}

mw.outline ={
  init:function(elem, color, $size){
    $(elem).each(function(){
      var width = $(this).outerWidth();
	  
	  if($size != undefined){
		//   var width = $size+'px'; 
	  } else {
		$size = 2;  
	  }
	  
      var height = $(this).outerHeight();
      var left = $(this).offset().left;
      var top = $(this).offset().top;

      var line_top = document.createElement('div');
      var line_right = document.createElement('div');
      var line_bottom = document.createElement('div');
      var line_left = document.createElement('div');
      var cls = elem.replace("#", "");
      var cls = cls.replace(".", "");
      $(line_top).addClass("mw_outline mw_outline_" + cls);
      $(line_right).addClass("mw_outline mw_outline_" + cls);
      $(line_bottom).addClass("mw_outline mw_outline_" + cls);
      $(line_left).addClass("mw_outline mw_outline_" + cls);

      $(line_top).addClass("mw_outline_top").css({
        top:top,
        left:left,
        width:width,
        backgroundColor:color==undefined?"#0000CC":color
      });
      $(line_right).addClass("mw_outline_right").css({
        top:top,
        left:left+width-$size,
        height:height,
        backgroundColor:color==undefined?"#0000CC":color
      });
      $(line_bottom).addClass("mw_outline_bottom").css({
        top:top+height-$size,
        left:left,
        width:width,
        backgroundColor:color==undefined?"#0000CC":color
      });
      $(line_left).addClass("mw_outline_left").css({
        top:top,
        left:left,
        height:height,
        backgroundColor:color==undefined?"#0000CC":color
      });

      document.body.appendChild(line_top)
      document.body.appendChild(line_right)
      document.body.appendChild(line_bottom)
      document.body.appendChild(line_left)


    });
  },
  
  
  
  
   removeAll:function(){
	   
	     $(".mw_outline").remove();
	  },
  
  
  remove:function(elem){
    var cls = elem.replace("#", "");
      var cls = cls.replace(".", "");
     $(".mw_outline_" + cls).remove();
  }
}
mw.buildURL = function(string, elem){


    				var string = string.toLowerCase() // change everything to lowercase
    				.replace(/^\s+|\s+$/g, "") // trim leading and trailing spaces
    				.replace(/[_|\s]+/g, "-") // change all spaces and underscores to a hyphen
    				.replace(/[^a-z\u0400-\u04FF0-9-]+/g, "") // remove all non-cyrillic, non-numeric characters except the hyphen
    				.replace(/[-]+/g, "-") // replace multiple instances of the hyphen with a single instance
    				.replace(/^-+|-+$/g, "") // trim leading and trailing hyphens
    				.replace(/[-]+/g, "-");


    				var string = CLconvert(string);


    if(elem!=undefined && $(elem).val()==""){
      $(elem).val(string);
    }
    return string;
}





	var cyrillic = [
		"а", "б", "в", "г", "д", "е", "ж", "з", "и", "й", "к", "л", "м", "н", "о",
		"п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ь", "ю", "я",
		"А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О",
		"П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ь", "Ю", "Я",
		"Ї", "ї", "Є", "є", "Ы", "ы", "Ё", "ё"
	];

	var latin = [
		"a", "b", "v", "g", "d", "e", "zh", "z", "i", "y", "k", "l", "m", "n", "o",
		"p", "r", "s", "t", "u", "f", "h", "ts", "ch", "sh", "sht", "a", "y", "yu", "ya",
		"A", "B", "B", "G", "D", "E", "Zh", "Z", "I", "Y", "K", "L", "M", "N", "O",
		"P", "R", "S", "T", "U", "F", "H", "Ts", "Ch", "Sh", "Sht", "A", "Y", "Yu", "Ya",
		"I", "i", "Ye", "ye", "I", "i", "Yo", "yo"
	];



		function CLconvert (text) {
			string = str_replace(cyrillic, latin, text);
			return string;
		}

		function str_replace (search, replace, subject, count) {

		    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
		            f = [].concat(search),
		            r = [].concat(replace),
		            s = subject,
		            ra = r instanceof Array, sa = s instanceof Array;
		    s = [].concat(s);
		    if (count) {
		        this.window[count] = 0;
		    }

		    for (i=0, sl=s.length; i < sl; i++) {
		        if (s[i] === '') {
		            continue;
		        }
		        for (j=0, fl=f.length; j < fl; j++) {
		            temp = s[i]+'';
		            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
		            s[i] = (temp).split(f[j]).join(repl);
		            if (count && s[i] !== temp) {
		                this.window[count] += (temp.length-s[i].length)/f[j].length;}
		        }
		    }

		    return sa ? s : s[0];
		}






mw.isJSGenerated = function(elem, callback){
    $(elem).each(function(){
      if(!$(this).hasClass("js_generated")){
         var elem = this;
         callback.call(elem);
         $(this).addClass("js_generated")
      }
    });
}



mw.singleline = function(string){
    return string.replace("\n", "");
}

mw.randColor = function(){
   return 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')';
}











































