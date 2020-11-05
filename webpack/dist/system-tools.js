/******/ (() => { // webpackBootstrap
(() => {
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/base64.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/color.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.color = {
  rgbToHex : function(color) {
    if(typeof color == 'object'){
      color = color.r + ',' + color.g + ',' + color.b;
    }
    if(color.contains('rgb')){
      color = color.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "");
    }
    color = color.split(',');
    if(color !== 'transparent'){
      return "#" + ((1 << 24) + (parseInt(color[0]) << 16) + (parseInt(color[1]) << 8) + parseInt(color[2])).toString(16).slice(1);
    }
    else{
      return 'transparent';
    }
  },
  hexToRgb: function(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
  },
  colorParse:function(CSScolor){
    CSScolor = CSScolor || 'rgb(0,0,0,0)';
    var final = {r:0,g:0,b:0,alpha:0};
    if(CSScolor.contains('rgb')){
      var parse = CSScolor.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "").split(',');
      final.r = parseInt(parse[0], 10);
      final.g = parseInt(parse[1], 10);
      final.b = parseInt(parse[2], 10);
      final.alpha = Number((parse[3]||1));
      return final;
    }
    else{
      final = mw.color.hexToRgb(CSScolor);
      final.alpha = 1
      return final;
    }
  },
  getBrightness: function(color) {
      var rgb = this.colorParse(color);
      return {
          color: (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000,
          alpha: rgb.alpha * 100
      };
  },
  isDark: function(color) {
      var brightness = this.getBrightness(color);
      return brightness.color < 128 && brightness.alpha > 22;
  },
  isLight: function(color) {
      return !this.isDark(color);
  },
  hexToRgbaCSS: function(hex, alpha) {
    alpha = alpha || 1;
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? ('rgba('+parseInt(result[1], 16)+','+parseInt(result[2], 16)+','+parseInt(result[3], 16)+','+alpha+')') : '';
  },
  random: function(){
    return '#' + Math.floor( Math.random() * 16777215 ).toString(16);
  },
  decimalToHex: function(decimal){
    var hex = decimal.toString(16);
    if (hex.length == 1) hex = '0' + hex;
    return hex;
  },
  hexToDecimal: function(hex){
    return parseInt(hex,16);
  },
  oppositeColor: function(color) {
    color = !color.contains("#") ? color : color.replace("#", '');
    return mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(0,2)))
      + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(2,2)))
      + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(4,2)));
  }
}

















})();

(() => {
/*!**********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/css_parser.js ***!
  \**********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */




mw.CSSParser = function(el){
    if(!el || !el.nodeName) return false;
    if(el.nodeName === '#text') return false;


    try {
        var css = window.getComputedStyle(el, null);
    } catch(error) {
        return;
    }


    var f = {};

    f.display = function(){
      return css.display;
    };

    f.is = function(){
        return {
          bold: parseFloat(css.fontWeight)>600 || css.fontWeight === 'bold' || css.fontWeight === 'bolder',
          italic: css.fontStyle === 'italic'||css.fontStyle === 'oblique',
          underlined: css.textDecoration === 'underline',
          striked: css.textDecoration.indexOf('line-through') === 0,
        };
    };
    f.font = function(){
      if(css === null) return false;
      return {
        size:css.fontSize,
        weight:css.fontWeight,
        style:css.fontStyle,
        height:css.lineHeight,
        family:css.fontFamily,
        color:css.color
      };
    }
    f.alignNormalize = function(){
        if(!!css){
        var a = css.textAlign;
        var final = a.contains('left')?'left':a.contains('center')?'center':a.contains('justify')?'justify':a.contains('right')?'right':'left';
        return final;
      }
    }
    f.border = function(parse){
        if(!parse){
          return {
              top:{width:css.borderTopWidth, style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:css.borderLeftWidth, style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:css.borderRightWidth, style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:css.borderBottomWidth, style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }
        else{
          return {
              top:{width:parseFloat(css.borderTopWidth), style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:parseFloat(css.borderLeftWidth), style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:parseFloat(css.borderRightWidth), style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:parseFloat(css.borderBottomWidth), style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }

    }
    f.width = function(){
        return css.width;
    }
    f.position = function(){
        return css.position;
    }
    f.background = function(){
        return {
            image:css.backgroundImage,
            color:css.backgroundColor,
            position:css.backgroundPosition,
            repeat:css.backgroundRepeat
        }
    }
    f.margin = function(parse, actual){
        if(actual){
            var _parent = el.parentNode;
            var parentOff = mw.$(_parent).offset();
            var elOff = mw.$(el).offset();
            if(elOff.left > parentOff.left && css.marginLeft === css.marginRight && elOff.left - parentOff.left === parseInt(css.marginLeft, 10)){
                return {
                    top:css.marginTop,
                    left:'auto',
                    right:'auto',
                    bottom:css.marginBottom
                };
            }
      }
      if(!parse){
        return {
          top:css.marginTop,
          left:css.marginLeft,
          right:css.marginRight,
          bottom:css.marginBottom
        }
      }
      else{
        return {
          top:parseFloat(css.marginTop),
          left:parseFloat(css.marginLeft),
          right:parseFloat(css.marginRight),
          bottom:parseFloat(css.marginBottom)
        }
      }
    }
    f.padding = function(parse){
      if(!parse){
        return {
          top:css.paddingTop,
          left:css.paddingLeft,
          right:css.paddingRight,
          bottom:css.paddingBottom
        }
      }
      else{
         return {
          top:parseFloat(css.paddingTop),
          left:parseFloat(css.paddingLeft),
          right:parseFloat(css.paddingRight),
          bottom:parseFloat(css.paddingBottom)
        }
      }
    }
    f.opacity = function(){return css.opacity}

    f.radius = function(parse){
      if(!parse){
        return {
          tl:css.borderTopLeftRadius,
          tr:css.borderTopRightRadius,
          br:css.borderBottomRightRadius,
          bl:css.borderBottomLeftRadius
        }
      }
      else{
        return {
          tl:parseFloat(css.borderTopLeftRadius),
          tr:parseFloat(css.borderTopRightRadius),
          br:parseFloat(css.borderBottomRightRadius),
          bl:parseFloat(css.borderBottomLeftRadius)
        }
      }
    }

    f.transform = function(){
     var transform = mw.JSPrefix('transform');
     var transform = css[transform];
     if(transform==="" || transform==="none"){
       return [1, 0, 0, 1, 0, 0];
     }
     else{
       var transform = transform.substr(7, transform.length - 8).split(", ");
       return transform;
     }
    }

    f.shadow = function(){
      var shadow =  mw.JSPrefix('boxShadow');
      var shadow = css[shadow].replace(/, /g, ",").split(" ");
      return {
        color: shadow[0],
        left:shadow[1],
        top:shadow[2],
        strength:shadow[3]
      }
    }

    return {
        el:el,
        css:css,
        get:f
    }
}



})();

(() => {
/*!******************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/external_callbacks.js ***!
  \******************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
//todo: remove completely

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
                a = mwd.createElement('a');
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
                a = mwd.createElement('a');
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
/*!********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/external.js ***!
  \********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
    mw.tools.externalInstrument = {
        register: {},
        holder: function () {
            var div = mwd.createElement('div');
            div.className = 'mw-external-tool';
            return div;
        },
        prepare: function (name, params) {
            var frame = mwd.createElement('iframe');
            frame.name = name;
            /* for callbacks */
            var url = mw.external_tool(name);
            if (typeof params === 'object') {
                params = $.param(params);
            }
            else {
                params = "";
            }
            frame.src = url + "?" + params;
            frame.scrolling = 'no';
            frame.frameBorder = 0;
            frame.onload = function () {
                frame.contentWindow.thisframe = frame;
            };
            return frame;
        },
        init: function (name, callback, holder, params) {
            if (typeof mw.tools.externalInstrument.register[name] === 'undefined') {
                var frame = mw.tools.externalInstrument.prepare(name, params);
                frame.height = 300;
                mw.tools.externalInstrument.register[name] = frame;
                if (!holder) {
                    holder = mw.tools.externalInstrument.holder();
                    mw.$(mwd.body).append(holder);
                }
                mw.$(holder).append(frame);
            }
            else {
                mw.$(mw.tools.externalInstrument.register[name]).unbind('change');
            }
            mw.$(mw.tools.externalInstrument.register[name]).bind('change', function () {
                Array.prototype.shift.apply(arguments);
                callback.apply(this, arguments);
            });
            return mw.tools.externalInstrument.register[name];
        }
    };

    mw.tools.external = function (name, callback, holder, params) {
        return mw.tools.externalInstrument.init(name, callback, holder, params);
    };

    mw.tools._external = function (o) {
        return mw.tools.external(o.name, o.callback, o.holder, o.params);
    };


})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/files.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

mw.require('uploader.js');

mw.files = {
    settings:{
            filetypes:"png,gif,jpg,jpeg,tiff,bmp,svg",
            url: mw.settings.upload_url,
            type:'explorer', // ... or filedrag
            multiple:true
    },
    filetypes:function(a, normalize){
            var def = !!normalize ? a : mw.files.settings.filetypes;
            switch(a){
            case 'img':
            case 'image':
            case 'images':
                return mw.files.settings.filetypes;
            case 'video':
            case 'videos':
                return 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
            case 'file':
            case 'files':
                return 'doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv';
            case 'documents':
            case 'doc':
                return 'doc,docx,log,pdf,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
            case 'archives':
            case 'arc':
            case 'arch':
                return 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
             case 'audio':
                return 'mp3,wav,ogg,mp4,flac';
             case 'media':
                return (mw.files.filetypes('video') + ',' + mw.files.filetypes('audio'));
             case 'all':
                return '*';
             case '*':
                return '*';
             default:
                return def;
            }
    },
    normalize_filetypes:function(a){
        var str = '';
        a = a.replace(/\s/g, '');
        var arr = a.split(','), i=0, l=arr.length;
        for( ; i<l; i++){
            str+= mw.files.filetypes(arr[i], true) + ',';
        }
        str = str.substring(0, str.length - 1);
        return str;
    },
    safeFilename:function(url){
            if(!url) return;
            url = url.replace(/["]/g, "%22").replace(/[']/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29");
            return url;
    },
    urlAsBackgrund:function(url, el){
            url = this.safeFilename(url);
            var bg = 'url("'+ url +'")';
            if(!!el){
                    el.style.backgroundImage = bg;
            }
            return bg;
    },
    uploader: function (o) {
        return mw.upload(o);
    }
}


})();

(() => {
/*!*************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/form-controls.js ***!
  \*************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

mw.require('autocomplete.js');


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
    _label: function (conf){
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
                if(textField) textField.value = val.text;
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
                    id: mw.controlFields._id()
                });
                var el = this.element;
                $(li).find('input').on('click', function(){
                    mw.top().tools.scrollTo(el);
                    scope.link = mw.top().win.location.href.split('#')[0] + '#mw@' + el.id;

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
                if(textField) textField.value = val.text;
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
                if(textField) textField.value = val.text;
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
                if(textField) textField.value = val.text;
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
                    console.log(selection[0])
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
                if(textField) textField.value = val.text;
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
                if(textField) textField.value = val.text;
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
                if(textField) textField.value = val.text;
                if(urlField) urlField.value = val.url  ;
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
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/forms.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */

var getFieldValue = function(a){
  return typeof a === 'string' ? a : ( typeof a === 'object' && a.tagName !== undefined ? a.value : null);
};




mw.form = {
  typeNumber:function(el){
    el.value = el.value.replace(/[^0-9\.,]/g,'');
  },
  fixPrice:function(el){
    el.value = el.value.replace(/,/g,'');
    var arr = el.value.split('.');
    var len = arr.length;
    if(len>1){
      if(arr[len-1]===''){
          arr[len-1] = '.00';
      }
      else{
         arr[len-1] = '.' + arr[len-1];
      }
      el.value = arr.join('');
    }
  },
  post: function(selector, url_to_post, callback, ignorenopost, callback_error, callback_user_cancel, before_send){
    mw.session.checkPause = true;
    if(selector.constructor === {}.constructor){
      return mw.form._post(selector);
    }

    callback_error = callback_error || false;
    ignorenopost = ignorenopost || false;
    var is_form_valid = mw.form.validate.init(selector);

	if(!url_to_post){

		url_to_post = mw.settings.site_url + 'api/post_form';

	}

 // var is_form_valid = true;


    if(is_form_valid){

        var form = mw.$(selector)[0];
        var when = form.$beforepost ? form.$beforepost : function () {};
        $.when(when()).then(function() {
            setTimeout(function () {
                var obj = mw.form.serialize(selector, ignorenopost);
                var xhr = $.ajax({
                    url: url_to_post,
                    data: before_send ? before_send(obj) : obj,
                    method: 'post',
                    success: function(data){
                        mw.session.checkPause = false;
                        if(typeof callback === 'function'){
                            callback.call(data, mw.$(selector)[0]);
                        } else {
                            return data;
                        }
                    },
                    onExternalDataDialogClose: function() {
                        if(callback_user_cancel) {
                            callback_user_cancel.call();
                        }
                    }
                });
                xhr.fail(function(a,b) {
                    mw.session.checkPause = false;
                    if(typeof callback_error === 'function'){
                        callback_error.call(a,b);
                    }
                });
            }, 78)
        });


    }
	return false;
  },
  _post:function(obj){
    mw.form.post(obj.selector, obj.url, obj.done, obj.ignorenopost, obj.error, obj.error);
  },
  validate:{
    checkbox: function(obj){
        return obj.checked === true;
    },
    field:function(obj){
		return !!getFieldValue(obj).replace(/\s/g, '');
    },
    email:function(obj){
        var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
        return regexmail.test(getFieldValue(obj));
    },
    url:function(obj){
	  /* var rurl =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig; */
       var rurl = /^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
       return rurl.test(getFieldValue(getFieldValue(obj)));
    },
    radio:function(objname){
        var radios = document.getElementsByName(objname), i = 0, len = radios.length;
        this_radio_valid = false;
        for( ; i < len ; i++){
            if(radios[i].checked){
                this_radio_valid = true;
                break;
            }
        }
        var parent = mw.$(document.getElementsByName(objname)[0].parentNode);
        if(this_radio_valid){
           parent.removeClass("error");
        }
        else{
           parent.addClass("error");
        }
        return this_radio_valid;
    },
    image_url:function(url, valid, invalid){
        url = url.replace(/\s/gi,'');
        if(url.length<6){
            typeof invalid =='function'? invalid.call(url) : '';
            return false;
        }
        else{
          if(!url.contains('http')){url = 'http://'+url}
          if(!window.ImgTester){
              window.ImgTester = new Image();
              document.body.appendChild(window.ImgTester);
              window.ImgTester.className = 'semi_hidden';
              window.ImgTester.onload = function(){
                typeof valid =='function'? valid.call(url) : '';
              }
              window.ImgTester.onerror = function(){
                typeof invalid =='function'? invalid.call(url) : '';
              }
          }
          window.ImgTester.src = url;
        }
    },
    proceed:{
      checkbox:function(obj){
        if(mw.form.validate.checkbox(obj)){
            mw.$(obj).parents('.field').removeClass("error");
        }
        else{
            mw.$(obj).parents('.field').addClass("error");
        }
      },
      field:function(obj){
        if(mw.form.validate.field(obj)){
           mw.$(obj).parents('.field').removeClass("error");
         }
         else{
           mw.$(obj).parents('.field').addClass("error");
         }
      },
      email:function(obj){
        if(mw.form.validate.email(obj)){
           mw.$(obj).parents('.field').removeClass("error");
        }
        else{
           mw.$(obj).parents('.field').addClass("error");
        }
      }
    },
    checkFields:function(form){
        mw.$(form).find(".required,[required]").each(function(){
          var type = mw.$(this).attr("type");
          if(type=='checkbox'){
             mw.form.validate.proceed.checkbox(this);
          }
          else if(type=='radio'){
             mw.form.validate.radio(this.name);
          }
          else{
             mw.form.validate.proceed.field(this);
          }
        });
        mw.$(form).find(".required-email").each(function(){
            mw.form.validate.proceed.email(this);
        });
    },
    init:function(obj){
        mw.form.validate.checkFields(obj);
        if($(obj).find(".error").length>0){
            mw.$(obj).addClass("error submited");
            return false;
        }
        else{
           mw.$(obj).removeClass("error");
            return true;
        }
    }
  },
  serialize : function(id, ignorenopost){
    ignorenopost = ignorenopost || false;
    return mw.serializeFields(id, ignorenopost);
  }
}


mw.postForm = function(o){
  return mw.form._post(o);
}














})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/loading.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.progressDefaults = {
    skin: 'mw-ui-progress',
    action: mw.msg.loading + '...',
    progress: 0
};

mw.tools.progress = function (obj) {
    if (typeof obj.element === 'string') {
        obj.element = mw.$(obj.element)[0];
    }
    if (obj.element === null || !obj.element) return false;
    if (obj.element.querySelector('.mw-ui-progress-bar')) {
        obj.element.progressOptions.show()
        return obj.element.progressOptions;
    }
    obj = $.extend({}, mw.tools.progressDefaults, obj);
    if(obj.progress > 100 ) {
        obj.progress = 100;
    }
    if(obj.progress < 0 ) {
        obj.progress = 0;
    }
    var progress = mwd.createElement('div');
    progress.className = obj.skin;
    progress.innerHTML = '<div class="mw-ui-progress-bar" style="width: ' + obj.progress + '%;"></div><div class="mw-ui-progress-info">' + obj.action + '</div><span class="mw-ui-progress-percent">'+obj.progress+'%</span>';
    progress.progressInfo = obj;
    var options = {
        progress: progress,
        show: function () {
            this.progress.style.display = '';
        },
        hide: function () {
            this.progress.style.display = 'none';
        },
        remove: function () {
            progress.progressInfo.element.progressOptions = undefined;
            mw.$(this.progress).remove();
        },
        set: function (v, action) {
            if (v > 100) {
                v = 100;
            }
            if (v < 0) {
                v = 0;
            }
            action = action || this.progress.progressInfo.action;
            mw.$('.mw-ui-progress-bar', this.progress).css('width', v + '%');
            mw.$('.mw-ui-progress-percent', this.progress).html(v + '%');
            progress.progressInfo.element.progressOptions.show();
        }
    };
    progress.progressOptions = obj.element.progressOptions = options;
    obj.element.appendChild(progress);
    return options;
};

mw.tools.loading = function (element, progress, speed) {
    /*

     progress:number 0 - 100,
     speed:string, -> 'slow', 'normal, 'fast'

     mw.tools.loading(true) -> slowly animates to 95% on body
     mw.tools.loading(false) -> fast animates to 100% on body

     */
    function set(el, progress, speed) {
        speed = speed || 'normal';
        mw.tools.removeClass(el, 'mw-progress-slow');
        mw.tools.removeClass(el, 'mw-progress-normal');
        mw.tools.removeClass(el, 'mw-progress-fast');
        mw.tools.addClass(el, 'mw-progress-' + speed);
        element.__loadingTime = setTimeout(function () {
            el.querySelector('.mw-progress-index').style.width = progress + '%';
        }, 10)

    }


    if (typeof element === 'boolean') {
        progress = !!element;
        element = mwd.body;
    }
    if (typeof element === 'number') {
        progress = element;
        element = mwd.body;
    }
    if (element === document || element === mwd.documentElement) {
        element = mwd.body;
    }
    element = mw.$(element)[0]
    if (element === null || !element) return false;
    if (element.__loadingTime) {
        clearTimeout(element.__loadingTime)
    }
    mw.require('css_parser.js')

    var isLoading = mw.tools.hasClass(element, 'mw-loading');
    var el = element.querySelector('.mw-progress');

    if (!el) {
        el = document.createElement('div');
        el.className = 'mw-progress';
        el.innerHTML = '<div class="mw-progress-index"></div>';
        if (element === mwd.body) el.style.position = 'fixed';
        element.appendChild(el);
    }
    if (progress === 'hide') {
        el.remove();
        return;
    }
    var pos = mw.CSSParser(element).get.position();
    if (pos === 'static') {
        element.style.position = 'relative';
    }
    if (progress) {
        if (progress === true) {
            set(el, 95, speed || 'slow')
        }
        else if (typeof progress === 'number') {
            progress = progress <= 100 ? progress : 100;
            progress = progress >= 0 ? progress : 0;
            set(el, progress, speed)
        }
    }
    else {
        if (el) {
            set(el, 100, speed || 'fast')
        }
        element.__loadingTime = setTimeout(function () {
            mw.$(element).removeClass('mw-loading-defaults mw-loading');
            mw.$(el).remove()
        }, 700)
    }
};

})();

(() => {
/*!***************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/modules-dialogs.js ***!
  \***************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
        return mw.dialogIframe(settings);
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
/*!**********************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/responsive.js ***!
  \**********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/session.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.session = {
    checkPause: false,
    checkPauseExplicitly: false,
    check: function (callback) {
        if (!mw.session.checkPause) {
            mw.session.checkPause = true;
            if (mw.session.checkPauseExplicitly) {
                return false;
            }
            $.post(mw.settings.api_url + "is_logged", function (data) {
                if (data === null) {
                    return;
                }
                if (data !== false) {
                    if (typeof callback === 'function') {
                        callback.call(undefined, true);
                    }

                }
                else {
                    if (typeof callback === 'function') {
                        callback.call(undefined, false)
                    }

                }
                mw.session.checkPause = false;
            });
        }
    },
    logRequest: function () {
        var modal = mw.dialog({
            html: "<h3 style='margin:0;'>" + mw.msg.session_expired + ".</h3> <p style='margin:0;'>" + mw.msg.login_to_continue + ".</p> <br> <div id='session_popup_login'></div>",
            id: "session_modal",
            name: "session_modal",
            overlay: true,
            width: 400,
            height: 300,
            template: 'mw_modal_basic',
            callback: function () {
                mw.load_module("users/login", '#session_popup_login', false, {template: 'popup'});
            }
        });
    },
    checkInit: function () {
        if (self !== top) {
            return false;
        }
        setInterval(function () {
            mw.session.check(function (is_logged) {
                if (is_logged) {
                    var m = mw.dialog.get("#session_modal");
                    if (m) {
                        m.remove();
                    }
                }
                else {
                    mw.session.logRequest();
                }
            });
        }, 90000);
    }
}
$(document).ready(function () {

    mw.$(document).on("ajaxSend",function () {

        mw.session.checkPause = true;
    }).bind("ajaxComplete", function () {
            mw.session.checkPause = false;
        });
});

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/system-tools/state.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function (){
    if(mw.State) return;
    var State = function(options){

        var scope = this;
        var defaults = {
            maxItems: 1000
        };
        this.options = $.extend({}, defaults, (options || {}));
        this._state = this.options.state || [];
        this._active = null;
        this._activeIndex = -1;

        this.hasNext = false;
        this.hasPrev = false;

        this.state = function(state){
            if(!state){
                return this._state;
            }
            this._state = state;
            return this;
        };


        this.active = function(active){
            if(!active){
                return this._active;
            }
        };

        this.activeIndex = function(activeIndex){
            if(!activeIndex){
                return this._activeIndex;
            }
        };

        this._timeout = null;
        this.timeoutRecord = function(item){
            clearTimeout(this._timeout);
            this._timeout = setTimeout(function(scope, item){
                scope.record(item);
            }, 333, this, item);
        };

        var recentRecordIsEqual = function (item) {
            const curr = scope._state[0];
            if(!curr) return false;
            for (var n in item) {
                if(curr[n] !== item[n]) {
                    return false;
                }
            }
            return true;
        };

        this.record = function(item){
            if(this._activeIndex>-1) {
                var i = 0;
                while ( i <  this._activeIndex) {
                    this._state.shift();
                    i++;
                }
            }
            if (recentRecordIsEqual(item)) {
                return;
            }
            this._state.unshift(item);
            if(this._state.length >= this.options.maxItems) {
                this._state.splice(-1,1);
            }
            this._active = null;
            this._activeIndex = -1;
            this.afterChange(false);
            mw.$(this).trigger('stateRecord', [this.eventData()]);
            return this;
        };

        this.actionRecord = function(recordGenFunc, action){
            this.record(recordGenFunc());
            action.call();
            this.record(recordGenFunc());
        };

        this.redo = function(){
            this._activeIndex--;
            this._active = this._state[this._activeIndex];
            this.afterChange('stateRedo');
            return this;
        };

        this.undo = function(){
            if(this._activeIndex === -1) {
                this._activeIndex = 1;
            }
            else{
                this._activeIndex++;
            }
            this._active = this._state[this._activeIndex];
            this.afterChange('stateUndo');
            return this;
        };

        this.hasRecords = function(){
            return !!this._state.length;
        };

        this.eventData = function(){
            return {
                hasPrev: this.hasPrev,
                hasNext: this.hasNext,
                active: this.active(),
                activeIndex: this.activeIndex()
            };
        };
        this.afterChange = function(action){
            this.hasNext = true;
            this.hasPrev = true;

            if(action) {
                if(this._activeIndex >= this._state.length) {
                    this._activeIndex = this._state.length - 1;
                    this._active = this._state[this._activeIndex];
                }
            }

            if(this._activeIndex <= 0) {
                this.hasPrev = false;
            }
            if(this._activeIndex === this._state.length-1 || (this._state.length === 1 && this._state[0].$initial)) {
                this.hasNext = false;
            }

            if(action){

                mw.$(this).trigger(action, [this.eventData()]);
            }
            if(action !== false){
                mw.$(this).trigger('change', [this.eventData()]);
            }
            return this;
        };

        this.reset = function(){
            this._state = this.options.state || [];
            this.afterChange('reset');
            return this;
        };

        this.clear = function(){
            this._state = [];
            this.afterChange('clear');
            return this;
        };


    };
    mw.State = State;
})();

(function(){
    if(mw.liveEditState) return;
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = mw.$(mw.liveEditState);

    var ui = mw.$('<div class="mw-ui-btn-nav"></div>'),
        undo = mwd.createElement('span'),
        redo = mwd.createElement('span');
    undo.className = 'mw-ui-btn mw-ui-btn-medium';
    undo.innerHTML = '<span class="mw-icon-reply"></span>';
    redo.className = 'mw-ui-btn mw-ui-btn-medium';
    redo.innerHTML = '<span class="mw-icon-forward"></span>';

    undo.onclick = function(){
        mw.liveEditState.undo();
    };
    redo.onclick = function(){
        mw.liveEditState.redo();
    };

    ui.append(undo);
    ui.append(redo);

    mw.$(document).ready(function(){
        var idata = mw.liveEditState.eventData();

        mw.$(undo)[!idata.hasNext?'addClass':'removeClass']('disabled');
        mw.$(redo)[!idata.hasPrev?'addClass':'removeClass']('disabled');

        /*undo.disabled = !idata.hasNext;
        redo.disabled = !idata.hasPrev;*/

        var edits = document.querySelectorAll('.edit'), editstime = null;

        for ( var i = 0; i < edits.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(this, 'edit')) {
                edits[i].addEventListener('keydown', function (e) {
                    var sel = getSelection();
                    var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                    if(!target.__initialRecord) {
                        target.__initialRecord = true;

                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                    }
                });
                edits[i].addEventListener('input', function (e) {
                    clearTimeout(editstime);
                    editstime = setTimeout(function () {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                        this.__initialRecord = false;
                    }, 1234);
                });
            }
        }

        mw.$liveEditState.on('stateRecord', function(e, data){
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });
        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){



            if(!data.active || (!data.active.target && !data.active.action)) {
                mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
                mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
                return;
            }
            if(data.active.action) {
                data.active.action();
            } else if(document.body.contains(data.active.target)) {
                mw.$(data.active.target).html(data.active.value);
            } else{
                if(data.active.target.id) {
                    mw.$(document.getElementById(data.active.target.id)).html(data.active.value);
                }
            }
            if(data.active.prev) {
                mw.$(data.active.prev).html(data.active.prevValue);
            }
            mw.drag.load_new_modules();
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });

        mw.$('#history_panel_toggle,#history_dd,.mw_editor_undo,.mw_editor_redo').remove();
        mw.$('.wysiwyg-cell-undo-redo').eq(0).prepend(ui);





        mw.element(document.body).on('keydown', function(e) {
            if (e.ctrlKey && e.key === 'z') {
                e.preventDefault();
                mw.liveEditState.undo();
            } else if (e.ctrlKey && e.key === 'y') {
                e.preventDefault();
                mw.liveEditState.redo();
            }
        });

    });

})();



})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXRvb2xzL2Jhc2U2NC5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXRvb2xzL2NvbG9yLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0tdG9vbHMvY3NzX3BhcnNlci5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXRvb2xzL2V4dGVybmFsX2NhbGxiYWNrcy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXRvb2xzL2V4dGVybmFsLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0tdG9vbHMvZmlsZXMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3N5c3RlbS10b29scy9mb3JtLWNvbnRyb2xzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0tdG9vbHMvZm9ybXMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3N5c3RlbS10b29scy9sb2FkaW5nLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0tdG9vbHMvbW9kdWxlcy1kaWFsb2dzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9zeXN0ZW0tdG9vbHMvcmVzcG9uc2l2ZS5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvc3lzdGVtLXRvb2xzL3Nlc3Npb24uanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3N5c3RlbS10b29scy9zdGF0ZS5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiOzs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSx1QkFBdUIsbUJBQW1CO0FBQzFDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNB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QUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7O0FBRUw7O0FBRUEsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBOzs7Ozs7Ozs7Ozs7Ozs7OztBQ2pMQTtBQUNBLG9CQUFvQjtBQUNwQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7Ozs7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QTs7O0FBR0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhOztBQUViOztBQUVBOztBQUVBOztBQUVBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLGdEQUFnRCwwQkFBMEI7QUFDMUU7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxpQkFBaUI7QUFDakI7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FB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QWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTs7QUFFYjs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxDQUFDOzs7Ozs7Ozs7OztBQzlyQ0Q7QUFDQTtBQUNBOzs7OztBQUtBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0Esa0NBQWtDO0FBQ2xDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2IsU0FBUzs7O0FBR1Q7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0EsK0RBQStELElBQUk7QUFDbkU7QUFDQSxLQUFLO0FBQ0w7QUFDQSxtRUFBbUUsMkJBQTJCO0FBQzlGLG1IQUFtSCxFQUFFLGtCQUFrQixrckJBQWtyQixFQUFFLGtCQUFrQixvRkFBb0YsRUFBRSxrQkFBa0IsMkZBQTJGLEVBQUUsa0JBQWtCLDhHQUE4RyxFQUFFLGtCQUFrQjtBQUN0cUM7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsY0FBYyxVQUFVO0FBQ3hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esb0NBQW9DO0FBQ3BDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDOU1BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4RkFBOEY7QUFDOUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7Ozs7Ozs7Ozs7QUN0SUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EseUNBQXlDLFVBQVUsaUJBQWlCLFdBQVc7QUFDL0U7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFD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QTtBQUNBO0FBQ0EsU0FBUzs7QUFFVCxLQUFLOztBQUVMLENBQUMiLCJmaWxlIjoic3lzdGVtLXRvb2xzLmpzIiwic291cmNlc0NvbnRlbnQiOlsibXcudG9vbHMuYmFzZTY0ID0ge1xyXG4vLyBwcml2YXRlIHByb3BlcnR5XHJcbiAgICBfa2V5U3RyOiBcIkFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFlaYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXowMTIzNDU2Nzg5Ky89XCIsXHJcbi8vIHB1YmxpYyBtZXRob2QgZm9yIGVuY29kaW5nXHJcbiAgICBlbmNvZGU6IGZ1bmN0aW9uIChpbnB1dCkge1xyXG4gICAgICAgIHZhciBvdXRwdXQgPSBcIlwiO1xyXG4gICAgICAgIHZhciBjaHIxLCBjaHIyLCBjaHIzLCBlbmMxLCBlbmMyLCBlbmMzLCBlbmM0O1xyXG4gICAgICAgIHZhciBpID0gMDtcclxuICAgICAgICBpbnB1dCA9IG13LnRvb2xzLmJhc2U2NC5fdXRmOF9lbmNvZGUoaW5wdXQpO1xyXG4gICAgICAgIHdoaWxlIChpIDwgaW5wdXQubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIGNocjEgPSBpbnB1dC5jaGFyQ29kZUF0KGkrKyk7XHJcbiAgICAgICAgICAgIGNocjIgPSBpbnB1dC5jaGFyQ29kZUF0KGkrKyk7XHJcbiAgICAgICAgICAgIGNocjMgPSBpbnB1dC5jaGFyQ29kZUF0KGkrKyk7XHJcbiAgICAgICAgICAgIGVuYzEgPSBjaHIxID4+IDI7XHJcbiAgICAgICAgICAgIGVuYzIgPSAoKGNocjEgJiAzKSA8PCA0KSB8IChjaHIyID4+IDQpO1xyXG4gICAgICAgICAgICBlbmMzID0gKChjaHIyICYgMTUpIDw8IDIpIHwgKGNocjMgPj4gNik7XHJcbiAgICAgICAgICAgIGVuYzQgPSBjaHIzICYgNjM7XHJcbiAgICAgICAgICAgIGlmIChpc05hTihjaHIyKSkge1xyXG4gICAgICAgICAgICAgICAgZW5jMyA9IGVuYzQgPSA2NDtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChpc05hTihjaHIzKSkge1xyXG4gICAgICAgICAgICAgICAgZW5jNCA9IDY0O1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIG91dHB1dCA9IG91dHB1dCArXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9rZXlTdHIuY2hhckF0KGVuYzEpICsgdGhpcy5fa2V5U3RyLmNoYXJBdChlbmMyKSArXHJcbiAgICAgICAgICAgICAgICB0aGlzLl9rZXlTdHIuY2hhckF0KGVuYzMpICsgdGhpcy5fa2V5U3RyLmNoYXJBdChlbmM0KTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIG91dHB1dDtcclxuICAgIH0sXHJcbi8vIHB1YmxpYyBtZXRob2QgZm9yIGRlY29kaW5nXHJcbiAgICBkZWNvZGU6IGZ1bmN0aW9uIChpbnB1dCkge1xyXG4gICAgICAgIGlmICh0eXBlb2YgaW5wdXQgPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICB2YXIgb3V0cHV0ID0gXCJcIjtcclxuICAgICAgICB2YXIgY2hyMSwgY2hyMiwgY2hyMztcclxuICAgICAgICB2YXIgZW5jMSwgZW5jMiwgZW5jMywgZW5jNDtcclxuICAgICAgICB2YXIgaSA9IDA7XHJcbiAgICAgICAgaW5wdXQgPSBpbnB1dC5yZXBsYWNlKC9bXkEtWmEtejAtOVxcK1xcL1xcPV0vZywgXCJcIik7XHJcbiAgICAgICAgd2hpbGUgKGkgPCBpbnB1dC5sZW5ndGgpIHtcclxuICAgICAgICAgICAgZW5jMSA9IHRoaXMuX2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcclxuICAgICAgICAgICAgZW5jMiA9IHRoaXMuX2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcclxuICAgICAgICAgICAgZW5jMyA9IHRoaXMuX2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcclxuICAgICAgICAgICAgZW5jNCA9IHRoaXMuX2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcclxuICAgICAgICAgICAgY2hyMSA9IChlbmMxIDw8IDIpIHwgKGVuYzIgPj4gNCk7XHJcbiAgICAgICAgICAgIGNocjIgPSAoKGVuYzIgJiAxNSkgPDwgNCkgfCAoZW5jMyA+PiAyKTtcclxuICAgICAgICAgICAgY2hyMyA9ICgoZW5jMyAmIDMpIDw8IDYpIHwgZW5jNDtcclxuICAgICAgICAgICAgb3V0cHV0ID0gb3V0cHV0ICsgU3RyaW5nLmZyb21DaGFyQ29kZShjaHIxKTtcclxuICAgICAgICAgICAgaWYgKGVuYzMgIT0gNjQpIHtcclxuICAgICAgICAgICAgICAgIG91dHB1dCA9IG91dHB1dCArIFN0cmluZy5mcm9tQ2hhckNvZGUoY2hyMik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKGVuYzQgIT0gNjQpIHtcclxuICAgICAgICAgICAgICAgIG91dHB1dCA9IG91dHB1dCArIFN0cmluZy5mcm9tQ2hhckNvZGUoY2hyMyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgb3V0cHV0ID0gbXcudG9vbHMuYmFzZTY0Ll91dGY4X2RlY29kZShvdXRwdXQpO1xyXG4gICAgICAgIHJldHVybiBvdXRwdXQ7XHJcbiAgICB9LFxyXG4vLyBwcml2YXRlIG1ldGhvZCBmb3IgVVRGLTggZW5jb2RpbmdcclxuICAgIF91dGY4X2VuY29kZTogZnVuY3Rpb24gKHN0cmluZykge1xyXG4gICAgICAgIHN0cmluZyA9IHN0cmluZy5yZXBsYWNlKC9cXHJcXG4vZywgXCJcXG5cIik7XHJcbiAgICAgICAgdmFyIHV0ZnRleHQgPSBcIlwiO1xyXG4gICAgICAgIGZvciAodmFyIG4gPSAwOyBuIDwgc3RyaW5nLmxlbmd0aDsgbisrKSB7XHJcbiAgICAgICAgICAgIHZhciBjID0gc3RyaW5nLmNoYXJDb2RlQXQobik7XHJcbiAgICAgICAgICAgIGlmIChjIDwgMTI4KSB7XHJcbiAgICAgICAgICAgICAgICB1dGZ0ZXh0ICs9IFN0cmluZy5mcm9tQ2hhckNvZGUoYyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgZWxzZSBpZiAoKGMgPiAxMjcpICYmIChjIDwgMjA0OCkpIHtcclxuICAgICAgICAgICAgICAgIHV0ZnRleHQgKz0gU3RyaW5nLmZyb21DaGFyQ29kZSgoYyA+PiA2KSB8IDE5Mik7XHJcbiAgICAgICAgICAgICAgICB1dGZ0ZXh0ICs9IFN0cmluZy5mcm9tQ2hhckNvZGUoKGMgJiA2MykgfCAxMjgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgdXRmdGV4dCArPSBTdHJpbmcuZnJvbUNoYXJDb2RlKChjID4+IDEyKSB8IDIyNCk7XHJcbiAgICAgICAgICAgICAgICB1dGZ0ZXh0ICs9IFN0cmluZy5mcm9tQ2hhckNvZGUoKChjID4+IDYpICYgNjMpIHwgMTI4KTtcclxuICAgICAgICAgICAgICAgIHV0ZnRleHQgKz0gU3RyaW5nLmZyb21DaGFyQ29kZSgoYyAmIDYzKSB8IDEyOCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHV0ZnRleHQ7XHJcbiAgICB9LFxyXG4vLyBwcml2YXRlIG1ldGhvZCBmb3IgVVRGLTggZGVjb2RpbmdcclxuICAgIF91dGY4X2RlY29kZTogZnVuY3Rpb24gKHV0ZnRleHQpIHtcclxuICAgICAgICB2YXIgc3RyaW5nID0gXCJcIjtcclxuICAgICAgICB2YXIgaSA9IDA7XHJcbiAgICAgICAgdmFyIGMgPSBjMSA9IGMyID0gMDtcclxuICAgICAgICB3aGlsZSAoaSA8IHV0ZnRleHQubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIGMgPSB1dGZ0ZXh0LmNoYXJDb2RlQXQoaSk7XHJcbiAgICAgICAgICAgIGlmIChjIDwgMTI4KSB7XHJcbiAgICAgICAgICAgICAgICBzdHJpbmcgKz0gU3RyaW5nLmZyb21DaGFyQ29kZShjKTtcclxuICAgICAgICAgICAgICAgIGkrKztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIGlmICgoYyA+IDE5MSkgJiYgKGMgPCAyMjQpKSB7XHJcbiAgICAgICAgICAgICAgICBjMiA9IHV0ZnRleHQuY2hhckNvZGVBdChpICsgMSk7XHJcbiAgICAgICAgICAgICAgICBzdHJpbmcgKz0gU3RyaW5nLmZyb21DaGFyQ29kZSgoKGMgJiAzMSkgPDwgNikgfCAoYzIgJiA2MykpO1xyXG4gICAgICAgICAgICAgICAgaSArPSAyO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgYzIgPSB1dGZ0ZXh0LmNoYXJDb2RlQXQoaSArIDEpO1xyXG4gICAgICAgICAgICAgICAgYzMgPSB1dGZ0ZXh0LmNoYXJDb2RlQXQoaSArIDIpO1xyXG4gICAgICAgICAgICAgICAgc3RyaW5nICs9IFN0cmluZy5mcm9tQ2hhckNvZGUoKChjICYgMTUpIDw8IDEyKSB8ICgoYzIgJiA2MykgPDwgNikgfCAoYzMgJiA2MykpO1xyXG4gICAgICAgICAgICAgICAgaSArPSAzO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHJpbmc7XHJcbiAgICB9XHJcbn0iLCJtdy5jb2xvciA9IHtcclxuICByZ2JUb0hleCA6IGZ1bmN0aW9uKGNvbG9yKSB7XHJcbiAgICBpZih0eXBlb2YgY29sb3IgPT0gJ29iamVjdCcpe1xyXG4gICAgICBjb2xvciA9IGNvbG9yLnIgKyAnLCcgKyBjb2xvci5nICsgJywnICsgY29sb3IuYjtcclxuICAgIH1cclxuICAgIGlmKGNvbG9yLmNvbnRhaW5zKCdyZ2InKSl7XHJcbiAgICAgIGNvbG9yID0gY29sb3IucmVwbGFjZSgvcmdiYS9nLCAnJykucmVwbGFjZSgvcmdiL2csICcnKS5yZXBsYWNlKC9cXCh8XFwpL2csIFwiXCIpLnJlcGxhY2UoL1xccy9nLCBcIlwiKTtcclxuICAgIH1cclxuICAgIGNvbG9yID0gY29sb3Iuc3BsaXQoJywnKTtcclxuICAgIGlmKGNvbG9yICE9PSAndHJhbnNwYXJlbnQnKXtcclxuICAgICAgcmV0dXJuIFwiI1wiICsgKCgxIDw8IDI0KSArIChwYXJzZUludChjb2xvclswXSkgPDwgMTYpICsgKHBhcnNlSW50KGNvbG9yWzFdKSA8PCA4KSArIHBhcnNlSW50KGNvbG9yWzJdKSkudG9TdHJpbmcoMTYpLnNsaWNlKDEpO1xyXG4gICAgfVxyXG4gICAgZWxzZXtcclxuICAgICAgcmV0dXJuICd0cmFuc3BhcmVudCc7XHJcbiAgICB9XHJcbiAgfSxcclxuICBoZXhUb1JnYjogZnVuY3Rpb24oaGV4KSB7XHJcbiAgICB2YXIgcmVzdWx0ID0gL14jPyhbYS1mXFxkXXsyfSkoW2EtZlxcZF17Mn0pKFthLWZcXGRdezJ9KSQvaS5leGVjKGhleCk7XHJcbiAgICByZXR1cm4gcmVzdWx0ID8ge1xyXG4gICAgICAgIHI6IHBhcnNlSW50KHJlc3VsdFsxXSwgMTYpLFxyXG4gICAgICAgIGc6IHBhcnNlSW50KHJlc3VsdFsyXSwgMTYpLFxyXG4gICAgICAgIGI6IHBhcnNlSW50KHJlc3VsdFszXSwgMTYpXHJcbiAgICB9IDogbnVsbDtcclxuICB9LFxyXG4gIGNvbG9yUGFyc2U6ZnVuY3Rpb24oQ1NTY29sb3Ipe1xyXG4gICAgQ1NTY29sb3IgPSBDU1Njb2xvciB8fCAncmdiKDAsMCwwLDApJztcclxuICAgIHZhciBmaW5hbCA9IHtyOjAsZzowLGI6MCxhbHBoYTowfTtcclxuICAgIGlmKENTU2NvbG9yLmNvbnRhaW5zKCdyZ2InKSl7XHJcbiAgICAgIHZhciBwYXJzZSA9IENTU2NvbG9yLnJlcGxhY2UoL3JnYmEvZywgJycpLnJlcGxhY2UoL3JnYi9nLCAnJykucmVwbGFjZSgvXFwofFxcKS9nLCBcIlwiKS5yZXBsYWNlKC9cXHMvZywgXCJcIikuc3BsaXQoJywnKTtcclxuICAgICAgZmluYWwuciA9IHBhcnNlSW50KHBhcnNlWzBdLCAxMCk7XHJcbiAgICAgIGZpbmFsLmcgPSBwYXJzZUludChwYXJzZVsxXSwgMTApO1xyXG4gICAgICBmaW5hbC5iID0gcGFyc2VJbnQocGFyc2VbMl0sIDEwKTtcclxuICAgICAgZmluYWwuYWxwaGEgPSBOdW1iZXIoKHBhcnNlWzNdfHwxKSk7XHJcbiAgICAgIHJldHVybiBmaW5hbDtcclxuICAgIH1cclxuICAgIGVsc2V7XHJcbiAgICAgIGZpbmFsID0gbXcuY29sb3IuaGV4VG9SZ2IoQ1NTY29sb3IpO1xyXG4gICAgICBmaW5hbC5hbHBoYSA9IDFcclxuICAgICAgcmV0dXJuIGZpbmFsO1xyXG4gICAgfVxyXG4gIH0sXHJcbiAgZ2V0QnJpZ2h0bmVzczogZnVuY3Rpb24oY29sb3IpIHtcclxuICAgICAgdmFyIHJnYiA9IHRoaXMuY29sb3JQYXJzZShjb2xvcik7XHJcbiAgICAgIHJldHVybiB7XHJcbiAgICAgICAgICBjb2xvcjogKHJnYi5yICogMjk5ICsgcmdiLmcgKiA1ODcgKyByZ2IuYiAqIDExNCkgLyAxMDAwLFxyXG4gICAgICAgICAgYWxwaGE6IHJnYi5hbHBoYSAqIDEwMFxyXG4gICAgICB9O1xyXG4gIH0sXHJcbiAgaXNEYXJrOiBmdW5jdGlvbihjb2xvcikge1xyXG4gICAgICB2YXIgYnJpZ2h0bmVzcyA9IHRoaXMuZ2V0QnJpZ2h0bmVzcyhjb2xvcik7XHJcbiAgICAgIHJldHVybiBicmlnaHRuZXNzLmNvbG9yIDwgMTI4ICYmIGJyaWdodG5lc3MuYWxwaGEgPiAyMjtcclxuICB9LFxyXG4gIGlzTGlnaHQ6IGZ1bmN0aW9uKGNvbG9yKSB7XHJcbiAgICAgIHJldHVybiAhdGhpcy5pc0RhcmsoY29sb3IpO1xyXG4gIH0sXHJcbiAgaGV4VG9SZ2JhQ1NTOiBmdW5jdGlvbihoZXgsIGFscGhhKSB7XHJcbiAgICBhbHBoYSA9IGFscGhhIHx8IDE7XHJcbiAgICB2YXIgcmVzdWx0ID0gL14jPyhbYS1mXFxkXXsyfSkoW2EtZlxcZF17Mn0pKFthLWZcXGRdezJ9KSQvaS5leGVjKGhleCk7XHJcbiAgICByZXR1cm4gcmVzdWx0ID8gKCdyZ2JhKCcrcGFyc2VJbnQocmVzdWx0WzFdLCAxNikrJywnK3BhcnNlSW50KHJlc3VsdFsyXSwgMTYpKycsJytwYXJzZUludChyZXN1bHRbM10sIDE2KSsnLCcrYWxwaGErJyknKSA6ICcnO1xyXG4gIH0sXHJcbiAgcmFuZG9tOiBmdW5jdGlvbigpe1xyXG4gICAgcmV0dXJuICcjJyArIE1hdGguZmxvb3IoIE1hdGgucmFuZG9tKCkgKiAxNjc3NzIxNSApLnRvU3RyaW5nKDE2KTtcclxuICB9LFxyXG4gIGRlY2ltYWxUb0hleDogZnVuY3Rpb24oZGVjaW1hbCl7XHJcbiAgICB2YXIgaGV4ID0gZGVjaW1hbC50b1N0cmluZygxNik7XHJcbiAgICBpZiAoaGV4Lmxlbmd0aCA9PSAxKSBoZXggPSAnMCcgKyBoZXg7XHJcbiAgICByZXR1cm4gaGV4O1xyXG4gIH0sXHJcbiAgaGV4VG9EZWNpbWFsOiBmdW5jdGlvbihoZXgpe1xyXG4gICAgcmV0dXJuIHBhcnNlSW50KGhleCwxNik7XHJcbiAgfSxcclxuICBvcHBvc2l0ZUNvbG9yOiBmdW5jdGlvbihjb2xvcikge1xyXG4gICAgY29sb3IgPSAhY29sb3IuY29udGFpbnMoXCIjXCIpID8gY29sb3IgOiBjb2xvci5yZXBsYWNlKFwiI1wiLCAnJyk7XHJcbiAgICByZXR1cm4gbXcuY29sb3IuZGVjaW1hbFRvSGV4KDI1NSAtIG13LmNvbG9yLmhleFRvRGVjaW1hbChjb2xvci5zdWJzdHIoMCwyKSkpXHJcbiAgICAgICsgbXcuY29sb3IuZGVjaW1hbFRvSGV4KDI1NSAtIG13LmNvbG9yLmhleFRvRGVjaW1hbChjb2xvci5zdWJzdHIoMiwyKSkpXHJcbiAgICAgICsgbXcuY29sb3IuZGVjaW1hbFRvSGV4KDI1NSAtIG13LmNvbG9yLmhleFRvRGVjaW1hbChjb2xvci5zdWJzdHIoNCwyKSkpO1xyXG4gIH1cclxufVxyXG5cclxuXHJcblxyXG5cclxuXHJcblxyXG5cclxuXHJcblxyXG5cclxuXHJcblxyXG5cclxuXHJcblxyXG5cclxuIiwiXHJcblxyXG5cclxuXHJcbm13LkNTU1BhcnNlciA9IGZ1bmN0aW9uKGVsKXtcclxuICAgIGlmKCFlbCB8fCAhZWwubm9kZU5hbWUpIHJldHVybiBmYWxzZTtcclxuICAgIGlmKGVsLm5vZGVOYW1lID09PSAnI3RleHQnKSByZXR1cm4gZmFsc2U7XHJcblxyXG5cclxuICAgIHRyeSB7XHJcbiAgICAgICAgdmFyIGNzcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGVsLCBudWxsKTtcclxuICAgIH0gY2F0Y2goZXJyb3IpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG5cclxuICAgIHZhciBmID0ge307XHJcblxyXG4gICAgZi5kaXNwbGF5ID0gZnVuY3Rpb24oKXtcclxuICAgICAgcmV0dXJuIGNzcy5kaXNwbGF5O1xyXG4gICAgfTtcclxuXHJcbiAgICBmLmlzID0gZnVuY3Rpb24oKXtcclxuICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgYm9sZDogcGFyc2VGbG9hdChjc3MuZm9udFdlaWdodCk+NjAwIHx8IGNzcy5mb250V2VpZ2h0ID09PSAnYm9sZCcgfHwgY3NzLmZvbnRXZWlnaHQgPT09ICdib2xkZXInLFxyXG4gICAgICAgICAgaXRhbGljOiBjc3MuZm9udFN0eWxlID09PSAnaXRhbGljJ3x8Y3NzLmZvbnRTdHlsZSA9PT0gJ29ibGlxdWUnLFxyXG4gICAgICAgICAgdW5kZXJsaW5lZDogY3NzLnRleHREZWNvcmF0aW9uID09PSAndW5kZXJsaW5lJyxcclxuICAgICAgICAgIHN0cmlrZWQ6IGNzcy50ZXh0RGVjb3JhdGlvbi5pbmRleE9mKCdsaW5lLXRocm91Z2gnKSA9PT0gMCxcclxuICAgICAgICB9O1xyXG4gICAgfTtcclxuICAgIGYuZm9udCA9IGZ1bmN0aW9uKCl7XHJcbiAgICAgIGlmKGNzcyA9PT0gbnVsbCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICByZXR1cm4ge1xyXG4gICAgICAgIHNpemU6Y3NzLmZvbnRTaXplLFxyXG4gICAgICAgIHdlaWdodDpjc3MuZm9udFdlaWdodCxcclxuICAgICAgICBzdHlsZTpjc3MuZm9udFN0eWxlLFxyXG4gICAgICAgIGhlaWdodDpjc3MubGluZUhlaWdodCxcclxuICAgICAgICBmYW1pbHk6Y3NzLmZvbnRGYW1pbHksXHJcbiAgICAgICAgY29sb3I6Y3NzLmNvbG9yXHJcbiAgICAgIH07XHJcbiAgICB9XHJcbiAgICBmLmFsaWduTm9ybWFsaXplID0gZnVuY3Rpb24oKXtcclxuICAgICAgICBpZighIWNzcyl7XHJcbiAgICAgICAgdmFyIGEgPSBjc3MudGV4dEFsaWduO1xyXG4gICAgICAgIHZhciBmaW5hbCA9IGEuY29udGFpbnMoJ2xlZnQnKT8nbGVmdCc6YS5jb250YWlucygnY2VudGVyJyk/J2NlbnRlcic6YS5jb250YWlucygnanVzdGlmeScpPydqdXN0aWZ5JzphLmNvbnRhaW5zKCdyaWdodCcpPydyaWdodCc6J2xlZnQnO1xyXG4gICAgICAgIHJldHVybiBmaW5hbDtcclxuICAgICAgfVxyXG4gICAgfVxyXG4gICAgZi5ib3JkZXIgPSBmdW5jdGlvbihwYXJzZSl7XHJcbiAgICAgICAgaWYoIXBhcnNlKXtcclxuICAgICAgICAgIHJldHVybiB7XHJcbiAgICAgICAgICAgICAgdG9wOnt3aWR0aDpjc3MuYm9yZGVyVG9wV2lkdGgsIHN0eWxlOmNzcy5ib3JkZXJUb3BTdHlsZSwgY29sb3I6Y3NzLmJvcmRlclRvcENvbG9yfSxcclxuICAgICAgICAgICAgICBsZWZ0Ont3aWR0aDpjc3MuYm9yZGVyTGVmdFdpZHRoLCBzdHlsZTpjc3MuYm9yZGVyTGVmdFN0eWxlLCBjb2xvcjpjc3MuYm9yZGVyTGVmdENvbG9yfSxcclxuICAgICAgICAgICAgICByaWdodDp7d2lkdGg6Y3NzLmJvcmRlclJpZ2h0V2lkdGgsIHN0eWxlOmNzcy5ib3JkZXJSaWdodFN0eWxlLCBjb2xvcjpjc3MuYm9yZGVyUmlnaHRDb2xvcn0sXHJcbiAgICAgICAgICAgICAgYm90dG9tOnt3aWR0aDpjc3MuYm9yZGVyQm90dG9tV2lkdGgsIHN0eWxlOmNzcy5ib3JkZXJCb3R0b21TdHlsZSwgY29sb3I6Y3NzLmJvcmRlckJvdHRvbUNvbG9yfVxyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNle1xyXG4gICAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgICAgICB0b3A6e3dpZHRoOnBhcnNlRmxvYXQoY3NzLmJvcmRlclRvcFdpZHRoKSwgc3R5bGU6Y3NzLmJvcmRlclRvcFN0eWxlLCBjb2xvcjpjc3MuYm9yZGVyVG9wQ29sb3J9LFxyXG4gICAgICAgICAgICAgIGxlZnQ6e3dpZHRoOnBhcnNlRmxvYXQoY3NzLmJvcmRlckxlZnRXaWR0aCksIHN0eWxlOmNzcy5ib3JkZXJMZWZ0U3R5bGUsIGNvbG9yOmNzcy5ib3JkZXJMZWZ0Q29sb3J9LFxyXG4gICAgICAgICAgICAgIHJpZ2h0Ont3aWR0aDpwYXJzZUZsb2F0KGNzcy5ib3JkZXJSaWdodFdpZHRoKSwgc3R5bGU6Y3NzLmJvcmRlclJpZ2h0U3R5bGUsIGNvbG9yOmNzcy5ib3JkZXJSaWdodENvbG9yfSxcclxuICAgICAgICAgICAgICBib3R0b206e3dpZHRoOnBhcnNlRmxvYXQoY3NzLmJvcmRlckJvdHRvbVdpZHRoKSwgc3R5bGU6Y3NzLmJvcmRlckJvdHRvbVN0eWxlLCBjb2xvcjpjc3MuYm9yZGVyQm90dG9tQ29sb3J9XHJcbiAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgIH1cclxuICAgIGYud2lkdGggPSBmdW5jdGlvbigpe1xyXG4gICAgICAgIHJldHVybiBjc3Mud2lkdGg7XHJcbiAgICB9XHJcbiAgICBmLnBvc2l0aW9uID0gZnVuY3Rpb24oKXtcclxuICAgICAgICByZXR1cm4gY3NzLnBvc2l0aW9uO1xyXG4gICAgfVxyXG4gICAgZi5iYWNrZ3JvdW5kID0gZnVuY3Rpb24oKXtcclxuICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgICBpbWFnZTpjc3MuYmFja2dyb3VuZEltYWdlLFxyXG4gICAgICAgICAgICBjb2xvcjpjc3MuYmFja2dyb3VuZENvbG9yLFxyXG4gICAgICAgICAgICBwb3NpdGlvbjpjc3MuYmFja2dyb3VuZFBvc2l0aW9uLFxyXG4gICAgICAgICAgICByZXBlYXQ6Y3NzLmJhY2tncm91bmRSZXBlYXRcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICBmLm1hcmdpbiA9IGZ1bmN0aW9uKHBhcnNlLCBhY3R1YWwpe1xyXG4gICAgICAgIGlmKGFjdHVhbCl7XHJcbiAgICAgICAgICAgIHZhciBfcGFyZW50ID0gZWwucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgdmFyIHBhcmVudE9mZiA9IG13LiQoX3BhcmVudCkub2Zmc2V0KCk7XHJcbiAgICAgICAgICAgIHZhciBlbE9mZiA9IG13LiQoZWwpLm9mZnNldCgpO1xyXG4gICAgICAgICAgICBpZihlbE9mZi5sZWZ0ID4gcGFyZW50T2ZmLmxlZnQgJiYgY3NzLm1hcmdpbkxlZnQgPT09IGNzcy5tYXJnaW5SaWdodCAmJiBlbE9mZi5sZWZ0IC0gcGFyZW50T2ZmLmxlZnQgPT09IHBhcnNlSW50KGNzcy5tYXJnaW5MZWZ0LCAxMCkpe1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgICAgICAgICAgICB0b3A6Y3NzLm1hcmdpblRvcCxcclxuICAgICAgICAgICAgICAgICAgICBsZWZ0OidhdXRvJyxcclxuICAgICAgICAgICAgICAgICAgICByaWdodDonYXV0bycsXHJcbiAgICAgICAgICAgICAgICAgICAgYm90dG9tOmNzcy5tYXJnaW5Cb3R0b21cclxuICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgICBpZighcGFyc2Upe1xyXG4gICAgICAgIHJldHVybiB7XHJcbiAgICAgICAgICB0b3A6Y3NzLm1hcmdpblRvcCxcclxuICAgICAgICAgIGxlZnQ6Y3NzLm1hcmdpbkxlZnQsXHJcbiAgICAgICAgICByaWdodDpjc3MubWFyZ2luUmlnaHQsXHJcbiAgICAgICAgICBib3R0b206Y3NzLm1hcmdpbkJvdHRvbVxyXG4gICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgICBlbHNle1xyXG4gICAgICAgIHJldHVybiB7XHJcbiAgICAgICAgICB0b3A6cGFyc2VGbG9hdChjc3MubWFyZ2luVG9wKSxcclxuICAgICAgICAgIGxlZnQ6cGFyc2VGbG9hdChjc3MubWFyZ2luTGVmdCksXHJcbiAgICAgICAgICByaWdodDpwYXJzZUZsb2F0KGNzcy5tYXJnaW5SaWdodCksXHJcbiAgICAgICAgICBib3R0b206cGFyc2VGbG9hdChjc3MubWFyZ2luQm90dG9tKVxyXG4gICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgfVxyXG4gICAgZi5wYWRkaW5nID0gZnVuY3Rpb24ocGFyc2Upe1xyXG4gICAgICBpZighcGFyc2Upe1xyXG4gICAgICAgIHJldHVybiB7XHJcbiAgICAgICAgICB0b3A6Y3NzLnBhZGRpbmdUb3AsXHJcbiAgICAgICAgICBsZWZ0OmNzcy5wYWRkaW5nTGVmdCxcclxuICAgICAgICAgIHJpZ2h0OmNzcy5wYWRkaW5nUmlnaHQsXHJcbiAgICAgICAgICBib3R0b206Y3NzLnBhZGRpbmdCb3R0b21cclxuICAgICAgICB9XHJcbiAgICAgIH1cclxuICAgICAgZWxzZXtcclxuICAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgIHRvcDpwYXJzZUZsb2F0KGNzcy5wYWRkaW5nVG9wKSxcclxuICAgICAgICAgIGxlZnQ6cGFyc2VGbG9hdChjc3MucGFkZGluZ0xlZnQpLFxyXG4gICAgICAgICAgcmlnaHQ6cGFyc2VGbG9hdChjc3MucGFkZGluZ1JpZ2h0KSxcclxuICAgICAgICAgIGJvdHRvbTpwYXJzZUZsb2F0KGNzcy5wYWRkaW5nQm90dG9tKVxyXG4gICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgfVxyXG4gICAgZi5vcGFjaXR5ID0gZnVuY3Rpb24oKXtyZXR1cm4gY3NzLm9wYWNpdHl9XHJcblxyXG4gICAgZi5yYWRpdXMgPSBmdW5jdGlvbihwYXJzZSl7XHJcbiAgICAgIGlmKCFwYXJzZSl7XHJcbiAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgIHRsOmNzcy5ib3JkZXJUb3BMZWZ0UmFkaXVzLFxyXG4gICAgICAgICAgdHI6Y3NzLmJvcmRlclRvcFJpZ2h0UmFkaXVzLFxyXG4gICAgICAgICAgYnI6Y3NzLmJvcmRlckJvdHRvbVJpZ2h0UmFkaXVzLFxyXG4gICAgICAgICAgYmw6Y3NzLmJvcmRlckJvdHRvbUxlZnRSYWRpdXNcclxuICAgICAgICB9XHJcbiAgICAgIH1cclxuICAgICAgZWxzZXtcclxuICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgdGw6cGFyc2VGbG9hdChjc3MuYm9yZGVyVG9wTGVmdFJhZGl1cyksXHJcbiAgICAgICAgICB0cjpwYXJzZUZsb2F0KGNzcy5ib3JkZXJUb3BSaWdodFJhZGl1cyksXHJcbiAgICAgICAgICBicjpwYXJzZUZsb2F0KGNzcy5ib3JkZXJCb3R0b21SaWdodFJhZGl1cyksXHJcbiAgICAgICAgICBibDpwYXJzZUZsb2F0KGNzcy5ib3JkZXJCb3R0b21MZWZ0UmFkaXVzKVxyXG4gICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGYudHJhbnNmb3JtID0gZnVuY3Rpb24oKXtcclxuICAgICB2YXIgdHJhbnNmb3JtID0gbXcuSlNQcmVmaXgoJ3RyYW5zZm9ybScpO1xyXG4gICAgIHZhciB0cmFuc2Zvcm0gPSBjc3NbdHJhbnNmb3JtXTtcclxuICAgICBpZih0cmFuc2Zvcm09PT1cIlwiIHx8IHRyYW5zZm9ybT09PVwibm9uZVwiKXtcclxuICAgICAgIHJldHVybiBbMSwgMCwgMCwgMSwgMCwgMF07XHJcbiAgICAgfVxyXG4gICAgIGVsc2V7XHJcbiAgICAgICB2YXIgdHJhbnNmb3JtID0gdHJhbnNmb3JtLnN1YnN0cig3LCB0cmFuc2Zvcm0ubGVuZ3RoIC0gOCkuc3BsaXQoXCIsIFwiKTtcclxuICAgICAgIHJldHVybiB0cmFuc2Zvcm07XHJcbiAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGYuc2hhZG93ID0gZnVuY3Rpb24oKXtcclxuICAgICAgdmFyIHNoYWRvdyA9ICBtdy5KU1ByZWZpeCgnYm94U2hhZG93Jyk7XHJcbiAgICAgIHZhciBzaGFkb3cgPSBjc3Nbc2hhZG93XS5yZXBsYWNlKC8sIC9nLCBcIixcIikuc3BsaXQoXCIgXCIpO1xyXG4gICAgICByZXR1cm4ge1xyXG4gICAgICAgIGNvbG9yOiBzaGFkb3dbMF0sXHJcbiAgICAgICAgbGVmdDpzaGFkb3dbMV0sXHJcbiAgICAgICAgdG9wOnNoYWRvd1syXSxcclxuICAgICAgICBzdHJlbmd0aDpzaGFkb3dbM11cclxuICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIHJldHVybiB7XHJcbiAgICAgICAgZWw6ZWwsXHJcbiAgICAgICAgY3NzOmNzcyxcclxuICAgICAgICBnZXQ6ZlxyXG4gICAgfVxyXG59XHJcblxyXG5cclxuIiwiLy90b2RvOiByZW1vdmUgY29tcGxldGVseVxuXG5tdy5pZnJhbWVjYWxsYmFja3MgPSB7XG4gICAgbm9vcDogZnVuY3Rpb24oKSB7XG5cbiAgICB9LFxuICAgIGluc2VydF9saW5rOiBmdW5jdGlvbiAodXJsLCB0YXJnZXQsIGxpbmtfY29udGVudCkge1xuICAgICAgICBpZih1cmwubGVuZ3RoKXtcbiAgICAgICAgICAgIHRhcmdldCA9IHVybFsxXTtcbiAgICAgICAgICAgIGxpbmtfY29udGVudCA9IHVybFsyXTtcbiAgICAgICAgICAgIHVybCA9IHVybFswXTtcbiAgICAgICAgfSBlbHNlIGlmKHVybC51cmwgKSB7XG4gICAgICAgICAgICB1cmw9IHVybC51cmw7XG4gICAgICAgICAgICB0YXJnZXQgPSB0YXJnZXQgfHwgdXJsLnRhcmdldCB8fCAnX3NlbGYnO1xuICAgICAgICAgICAgbGlua19jb250ZW50ID0gbGlua19jb250ZW50IHx8IHVybC50ZXh0IHx8ICdfc2VsZic7XG4gICAgICAgIH1cbiAgICAgICAgdXJsID0gdXJsLnRyaW0oKTtcbiAgICAgICAgdmFyIGNvbnRhaW5zID0gZmFsc2U7XG4gICAgICAgIHZhciBhcnIgPSBbJ21haWx0bzonLCAndGVsOicsICdza3lwZTonLCAnc21zOicsICdnZW9wb2ludDonLCAnd2hhdHNhcHA6J10sXG4gICAgICAgICAgICBpID0gMDtcbiAgICAgICAgZm9yKCA7IGkgPCBhcnIubGVuZ3RoOyBpKysgKXtcbiAgICAgICAgICAgIGlmKHVybC5pbmRleE9mKGFycltpXSkgPT09IDApe1xuICAgICAgICAgICAgICAgIGNvbnRhaW5zID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZiAoIWNvbnRhaW5zICYmICEhdXJsKSB7XG4gICAgICAgICAgICAvL3VybCA9IHVybC5pbmRleE9mKFwiaHR0cFwiKSA9PT0gMCA/IHVybCA6IChsb2NhdGlvbi5wcm90b2NvbCArIFwiLy9cIiArIHVybCk7XG4gICAgICAgIH1cbiAgICAgICAgdGFyZ2V0ID0gdGFyZ2V0IHx8ICdfc2VsZic7XG5cbiAgICAgICAgdmFyIGxpbmtfaW5uZXJfdGV4dCA9IGZhbHNlO1xuICAgICAgICBpZihsaW5rX2NvbnRlbnQpe1xuICAgICAgICAgICAgbGlua19pbm5lcl90ZXh0ID0gbGlua19jb250ZW50O1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIHNlbCwgcmFuZ2UsIGE7XG5cbiAgICAgICAgc2VsID0gZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgIGlmKCFzZWwucmFuZ2VDb3VudCl7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KDApO1xuICAgICAgICB2YXIganFBY3Rpb24gPSB1cmwgPyAnYXR0cicgOiAncmVtb3ZlQXR0cic7XG5cbiAgICAgICAgbXcud3lzaXd5Zy5jaGFuZ2UocmFuZ2Uuc3RhcnRDb250YWluZXIpO1xuXG4gICAgICAgIGlmICghIW13LmN1cnJlbnRfZWxlbWVudCAmJiBtdy5jdXJyZW50X2VsZW1lbnQubm9kZU5hbWUgPT09ICdJTUcnKSB7XG4gICAgICAgICAgICBpZiAobXcuY3VycmVudF9lbGVtZW50LnBhcmVudE5vZGUubm9kZU5hbWUgIT09ICdBJykge1xuICAgICAgICAgICAgICAgIGEgPSBtd2QuY3JlYXRlRWxlbWVudCgnYScpO1xuICAgICAgICAgICAgICAgIGlmKHVybCl7XG4gICAgICAgICAgICAgICAgICAgIGEuaHJlZiA9IHVybDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgYS50YXJnZXQgPSB0YXJnZXQ7XG5cbiAgICAgICAgICAgICAgICBtdy4kKG13LmN1cnJlbnRfZWxlbWVudCkud3JhcChhKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIG13LiQobXcuY3VycmVudF9lbGVtZW50LnBhcmVudE5vZGUpW2pxQWN0aW9uXSgnaHJlZicsIHVybCk7XG5cbiAgICAgICAgICAgICAgICBtdy5jdXJyZW50X2VsZW1lbnQucGFyZW50Tm9kZS50YXJnZXQgPSB0YXJnZXQ7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuXG4gICAgICAgIGlmIChyYW5nZS5jb21tb25BbmNlc3RvckNvbnRhaW5lci5ub2RlTmFtZSA9PT0gJ0EnKSB7XG4gICAgICAgICAgICBtdy4kKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKVtqcUFjdGlvbl0oXCJocmVmXCIsIHVybCkuYXR0cihcInRhcmdldFwiLCB0YXJnZXQpO1xuICAgICAgICAgICAgaWYobGlua19pbm5lcl90ZXh0KXtcbiAgICAgICAgICAgICAgICBtdy4kKHJhbmdlLmNvbW1vbkFuY2VzdG9yQ29udGFpbmVyKS5odG1sKGxpbmtfaW5uZXJfdGV4dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuXG4gICAgICAgIHZhciBzdGFydCA9IHJhbmdlLnN0YXJ0Q29udGFpbmVyO1xuXG5cbiAgICAgICAgaWYgKHdpbmRvdy5nZXRTZWxlY3Rpb24oKS5pc0NvbGxhcHNlZCkge1xuXG4gICAgICAgICAgICBpZiAoISFtdy5jdXJyZW50X2VsZW1lbnQgJiYgbXcuY3VycmVudF9lbGVtZW50Lm5vZGVOYW1lICE9PSAnQScpIHtcbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2hpbGRyZW5XaXRoVGFnKG13LmN1cnJlbnRfZWxlbWVudCwgJ2EnKSkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0Q2hpbGRXaXRoVGFnKG13LmN1cnJlbnRfZWxlbWVudCwgJ2EnKSlbanFBY3Rpb25dKFwiaHJlZlwiLCB1cmwpLmF0dHIoXCJ0YXJnZXRcIiwgdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICAgICAgaWYobGlua19pbm5lcl90ZXh0KXtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQobXcudG9vbHMuZmlyc3RDaGlsZFdpdGhUYWcobXcuY3VycmVudF9lbGVtZW50LCAnYScpKS5odG1sKGxpbmtfaW5uZXJfdGV4dCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSBpZiAoISFtdy5jdXJyZW50X2VsZW1lbnQgJiYgbXcuY3VycmVudF9lbGVtZW50Lm5vZGVOYW1lID09PSAnQScpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13LmN1cnJlbnRfZWxlbWVudCkuYXR0cihcImhyZWZcIiwgdXJsKS5hdHRyKFwidGFyZ2V0XCIsIHRhcmdldCk7XG4gICAgICAgICAgICAgICAgaWYobGlua19pbm5lcl90ZXh0KXtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChtdy5jdXJyZW50X2VsZW1lbnQpLmh0bWwobGlua19pbm5lcl90ZXh0KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzUGFyZW50c1dpdGhUYWcoc3RhcnQsICdhJykpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhzdGFydCwgJ2EnKSlbanFBY3Rpb25dKFwiaHJlZlwiLCB1cmwpLmF0dHIoXCJ0YXJnZXRcIiwgdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBpZihsaW5rX2lubmVyX3RleHQpe1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhzdGFydCwgJ2EnKSkuaHRtbChsaW5rX2lubmVyX3RleHQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2hpbGRyZW5XaXRoVGFnKHN0YXJ0LCAnYScpKSB7XG4gICAgICAgICAgICAgICAgbXcuJChtdy50b29scy5maXJzdENoaWxkV2l0aFRhZyhzdGFydCwgJ2EnKSlbanFBY3Rpb25dKFwiaHJlZlwiLCB1cmwpLmF0dHIoXCJ0YXJnZXRcIiwgdGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBpZihsaW5rX2lubmVyX3RleHQpe1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0Q2hpbGRXaXRoVGFnKHN0YXJ0LCAnYScpKS5odG1sKGxpbmtfaW5uZXJfdGV4dCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICB9XG5cblxuICAgICAgICB2YXIgbGluayA9IG13Lnd5c2l3eWcuZmluZFRhZ0Fjcm9zc1NlbGVjdGlvbignYScpO1xuICAgICAgICBpZiAoISFsaW5rKSB7XG4gICAgICAgICAgICBtdy4kKGxpbmspW2pxQWN0aW9uXShcImhyZWZcIiwgdXJsKTtcbiAgICAgICAgICAgIG13LiQobGluaykuYXR0cihcInRhcmdldFwiLCB0YXJnZXQpO1xuICAgICAgICAgICAgaWYobGlua19pbm5lcl90ZXh0KXtcbiAgICAgICAgICAgICAgICBtdy4kKGxpbmspLmh0bWwobGlua19pbm5lcl90ZXh0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGlmICghd2luZG93LmdldFNlbGVjdGlvbigpLmlzQ29sbGFwc2VkKSB7XG4gICAgICAgICAgICAgICAgYSA9IG13ZC5jcmVhdGVFbGVtZW50KCdhJyk7XG4gICAgICAgICAgICAgICAgYS5ocmVmID0gdXJsO1xuICAgICAgICAgICAgICAgIGEudGFyZ2V0ID0gdGFyZ2V0O1xuICAgICAgICAgICAgICAgIHNlbCA9IHdpbmRvdy5nZXRTZWxlY3Rpb24oKTtcbiAgICAgICAgICAgICAgICByYW5nZSA9IHNlbC5nZXRSYW5nZUF0KDApO1xuICAgICAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgICAgIHJhbmdlLnN1cnJvdW5kQ29udGVudHMoYSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGNhdGNoIChlKSB7XG4gICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuZXhlY0NvbW1hbmQoXCJDcmVhdGVMaW5rXCIsIGZhbHNlLCB1cmwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgdmFyIGh0bWwgPSAnPGEgaHJlZj1cIicgKyB1cmwgKyAnXCIgdGFyZ2V0PVwiJyArIHRhcmdldCArICdcIj4nICsgKGxpbmtfaW5uZXJfdGV4dCA/IGxpbmtfaW5uZXJfdGV4dCA6IHVybCkgKyAnPC9hPic7XG4gICAgICAgICAgICAgICAgbXcud3lzaXd5Zy5pbnNlcnRfaHRtbChodG1sKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZihsaW5rX2NvbnRlbnQgJiYgYSkge1xuICAgICAgICAgICAgYS5pbm5lckhUTUwgPSBsaW5rX2NvbnRlbnRcbiAgICAgICAgfVxuICAgIH0sXG5cbiAgICBzZXRfYmdfaW1hZ2U6IGZ1bmN0aW9uICh1cmwpIHtcbiAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcuc2V0X2JnX2ltYWdlKHVybCk7XG4gICAgfSxcbiAgICBmb250Q29sb3I6IGZ1bmN0aW9uIChjb2xvcikge1xuICAgICAgICByZXR1cm4gbXcud3lzaXd5Zy5mb250Q29sb3IoY29sb3IpO1xuICAgIH0sXG4gICAgZm9udGJnOiBmdW5jdGlvbiAoY29sb3IpIHtcbiAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcuZm9udGJnKGNvbG9yKTtcbiAgICB9LFxuICAgIGNoYW5nZV9iZ19jb2xvcjogZnVuY3Rpb24gKGNvbG9yKSB7XG4gICAgICAgIHJldHVybiBtdy53eXNpd3lnLmNoYW5nZV9iZ19jb2xvcihjb2xvcik7XG4gICAgfSxcbiAgICBjaGFuZ2VfYm9yZGVyX2NvbG9yOiBmdW5jdGlvbiAoY29sb3IpIHtcbiAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcuY2hhbmdlX2JvcmRlcl9jb2xvcihjb2xvcik7XG4gICAgfSxcbiAgICBjaGFuZ2Vfc2hhZG93X2NvbG9yOiBmdW5jdGlvbiAoY29sb3IpIHtcbiAgICAgICAgcmV0dXJuIG13Lnd5c2l3eWcuY2hhbmdlX3NoYWRvd19jb2xvcihjb2xvcik7XG4gICAgfSxcblxuICAgIGFkZF9saW5rX3RvX21lbnU6IGZ1bmN0aW9uICgpIHtcblxuICAgIH0sXG4gICAgZWRpdGxpbms6IGZ1bmN0aW9uIChhLCBiKSB7XG4gICAgICAgIG13Lnd5c2l3eWcucmVzdG9yZV9zZWxlY3Rpb24oKTtcbiAgICAgICAgdmFyIGxpbmsgPSBtdy53eXNpd3lnLmZpbmRUYWdBY3Jvc3NTZWxlY3Rpb24oJ2EnKTtcbiAgICAgICAgbGluay5ocmVmID0gYTtcblxuICAgICAgICBtdy53eXNpd3lnLmNoYW5nZShsaW5rKTtcblxuICAgIH1cblxufVxuXG5cblxuXG5cblxuXG4iLCIgICAgbXcudG9vbHMuZXh0ZXJuYWxJbnN0cnVtZW50ID0ge1xuICAgICAgICByZWdpc3Rlcjoge30sXG4gICAgICAgIGhvbGRlcjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGRpdiA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGRpdi5jbGFzc05hbWUgPSAnbXctZXh0ZXJuYWwtdG9vbCc7XG4gICAgICAgICAgICByZXR1cm4gZGl2O1xuICAgICAgICB9LFxuICAgICAgICBwcmVwYXJlOiBmdW5jdGlvbiAobmFtZSwgcGFyYW1zKSB7XG4gICAgICAgICAgICB2YXIgZnJhbWUgPSBtd2QuY3JlYXRlRWxlbWVudCgnaWZyYW1lJyk7XG4gICAgICAgICAgICBmcmFtZS5uYW1lID0gbmFtZTtcbiAgICAgICAgICAgIC8qIGZvciBjYWxsYmFja3MgKi9cbiAgICAgICAgICAgIHZhciB1cmwgPSBtdy5leHRlcm5hbF90b29sKG5hbWUpO1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBwYXJhbXMgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgcGFyYW1zID0gJC5wYXJhbShwYXJhbXMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgcGFyYW1zID0gXCJcIjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGZyYW1lLnNyYyA9IHVybCArIFwiP1wiICsgcGFyYW1zO1xuICAgICAgICAgICAgZnJhbWUuc2Nyb2xsaW5nID0gJ25vJztcbiAgICAgICAgICAgIGZyYW1lLmZyYW1lQm9yZGVyID0gMDtcbiAgICAgICAgICAgIGZyYW1lLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBmcmFtZS5jb250ZW50V2luZG93LnRoaXNmcmFtZSA9IGZyYW1lO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIHJldHVybiBmcmFtZTtcbiAgICAgICAgfSxcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKG5hbWUsIGNhbGxiYWNrLCBob2xkZXIsIHBhcmFtcykge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBtdy50b29scy5leHRlcm5hbEluc3RydW1lbnQucmVnaXN0ZXJbbmFtZV0gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgdmFyIGZyYW1lID0gbXcudG9vbHMuZXh0ZXJuYWxJbnN0cnVtZW50LnByZXBhcmUobmFtZSwgcGFyYW1zKTtcbiAgICAgICAgICAgICAgICBmcmFtZS5oZWlnaHQgPSAzMDA7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuZXh0ZXJuYWxJbnN0cnVtZW50LnJlZ2lzdGVyW25hbWVdID0gZnJhbWU7XG4gICAgICAgICAgICAgICAgaWYgKCFob2xkZXIpIHtcbiAgICAgICAgICAgICAgICAgICAgaG9sZGVyID0gbXcudG9vbHMuZXh0ZXJuYWxJbnN0cnVtZW50LmhvbGRlcigpO1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5hcHBlbmQoaG9sZGVyKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgbXcuJChob2xkZXIpLmFwcGVuZChmcmFtZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmV4dGVybmFsSW5zdHJ1bWVudC5yZWdpc3RlcltuYW1lXSkudW5iaW5kKCdjaGFuZ2UnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIG13LiQobXcudG9vbHMuZXh0ZXJuYWxJbnN0cnVtZW50LnJlZ2lzdGVyW25hbWVdKS5iaW5kKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLnNoaWZ0LmFwcGx5KGFyZ3VtZW50cyk7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIG13LnRvb2xzLmV4dGVybmFsSW5zdHJ1bWVudC5yZWdpc3RlcltuYW1lXTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBtdy50b29scy5leHRlcm5hbCA9IGZ1bmN0aW9uIChuYW1lLCBjYWxsYmFjaywgaG9sZGVyLCBwYXJhbXMpIHtcbiAgICAgICAgcmV0dXJuIG13LnRvb2xzLmV4dGVybmFsSW5zdHJ1bWVudC5pbml0KG5hbWUsIGNhbGxiYWNrLCBob2xkZXIsIHBhcmFtcyk7XG4gICAgfTtcblxuICAgIG13LnRvb2xzLl9leHRlcm5hbCA9IGZ1bmN0aW9uIChvKSB7XG4gICAgICAgIHJldHVybiBtdy50b29scy5leHRlcm5hbChvLm5hbWUsIG8uY2FsbGJhY2ssIG8uaG9sZGVyLCBvLnBhcmFtcyk7XG4gICAgfTtcblxuIiwiXG5tdy5yZXF1aXJlKCd1cGxvYWRlci5qcycpO1xuXG5tdy5maWxlcyA9IHtcbiAgICBzZXR0aW5nczp7XG4gICAgICAgICAgICBmaWxldHlwZXM6XCJwbmcsZ2lmLGpwZyxqcGVnLHRpZmYsYm1wLHN2Z1wiLFxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy51cGxvYWRfdXJsLFxuICAgICAgICAgICAgdHlwZTonZXhwbG9yZXInLCAvLyAuLi4gb3IgZmlsZWRyYWdcbiAgICAgICAgICAgIG11bHRpcGxlOnRydWVcbiAgICB9LFxuICAgIGZpbGV0eXBlczpmdW5jdGlvbihhLCBub3JtYWxpemUpe1xuICAgICAgICAgICAgdmFyIGRlZiA9ICEhbm9ybWFsaXplID8gYSA6IG13LmZpbGVzLnNldHRpbmdzLmZpbGV0eXBlcztcbiAgICAgICAgICAgIHN3aXRjaChhKXtcbiAgICAgICAgICAgIGNhc2UgJ2ltZyc6XG4gICAgICAgICAgICBjYXNlICdpbWFnZSc6XG4gICAgICAgICAgICBjYXNlICdpbWFnZXMnOlxuICAgICAgICAgICAgICAgIHJldHVybiBtdy5maWxlcy5zZXR0aW5ncy5maWxldHlwZXM7XG4gICAgICAgICAgICBjYXNlICd2aWRlbyc6XG4gICAgICAgICAgICBjYXNlICd2aWRlb3MnOlxuICAgICAgICAgICAgICAgIHJldHVybiAnYXZpLGFzZixtcGcsbXBlZyxtcDQsZmx2LG1rdix3ZWJtLG9nZyx3bWEsbW92LHdtdic7XG4gICAgICAgICAgICBjYXNlICdmaWxlJzpcbiAgICAgICAgICAgIGNhc2UgJ2ZpbGVzJzpcbiAgICAgICAgICAgICAgICByZXR1cm4gJ2RvYyxkb2N4LHBkZixodG1sLGpzLGNzcyxodG0scnRmLHR4dCx6aXAsZ3ppcCxyYXIsY2FkLHhtbCxwc2QseGxzeCxjc3YnO1xuICAgICAgICAgICAgY2FzZSAnZG9jdW1lbnRzJzpcbiAgICAgICAgICAgIGNhc2UgJ2RvYyc6XG4gICAgICAgICAgICAgICAgcmV0dXJuICdkb2MsZG9jeCxsb2cscGRmLG1zZyxvZHQscGFnZXMscnRmLHRleCx0eHQsd3BkLHdwcyxwcHMscHB0LHBwdHgseG1sLGh0bSxodG1sLHhscix4bHMseGxzeCc7XG4gICAgICAgICAgICBjYXNlICdhcmNoaXZlcyc6XG4gICAgICAgICAgICBjYXNlICdhcmMnOlxuICAgICAgICAgICAgY2FzZSAnYXJjaCc6XG4gICAgICAgICAgICAgICAgcmV0dXJuICd6aXAsemlweCxnemlwLHJhcixneiw3eixjYnIsdGFyLmd6JztcbiAgICAgICAgICAgICBjYXNlICdhdWRpbyc6XG4gICAgICAgICAgICAgICAgcmV0dXJuICdtcDMsd2F2LG9nZyxtcDQsZmxhYyc7XG4gICAgICAgICAgICAgY2FzZSAnbWVkaWEnOlxuICAgICAgICAgICAgICAgIHJldHVybiAobXcuZmlsZXMuZmlsZXR5cGVzKCd2aWRlbycpICsgJywnICsgbXcuZmlsZXMuZmlsZXR5cGVzKCdhdWRpbycpKTtcbiAgICAgICAgICAgICBjYXNlICdhbGwnOlxuICAgICAgICAgICAgICAgIHJldHVybiAnKic7XG4gICAgICAgICAgICAgY2FzZSAnKic6XG4gICAgICAgICAgICAgICAgcmV0dXJuICcqJztcbiAgICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAgIHJldHVybiBkZWY7XG4gICAgICAgICAgICB9XG4gICAgfSxcbiAgICBub3JtYWxpemVfZmlsZXR5cGVzOmZ1bmN0aW9uKGEpe1xuICAgICAgICB2YXIgc3RyID0gJyc7XG4gICAgICAgIGEgPSBhLnJlcGxhY2UoL1xccy9nLCAnJyk7XG4gICAgICAgIHZhciBhcnIgPSBhLnNwbGl0KCcsJyksIGk9MCwgbD1hcnIubGVuZ3RoO1xuICAgICAgICBmb3IoIDsgaTxsOyBpKyspe1xuICAgICAgICAgICAgc3RyKz0gbXcuZmlsZXMuZmlsZXR5cGVzKGFycltpXSwgdHJ1ZSkgKyAnLCc7XG4gICAgICAgIH1cbiAgICAgICAgc3RyID0gc3RyLnN1YnN0cmluZygwLCBzdHIubGVuZ3RoIC0gMSk7XG4gICAgICAgIHJldHVybiBzdHI7XG4gICAgfSxcbiAgICBzYWZlRmlsZW5hbWU6ZnVuY3Rpb24odXJsKXtcbiAgICAgICAgICAgIGlmKCF1cmwpIHJldHVybjtcbiAgICAgICAgICAgIHVybCA9IHVybC5yZXBsYWNlKC9bXCJdL2csIFwiJTIyXCIpLnJlcGxhY2UoL1snXS9nLCBcIiUyN1wiKS5yZXBsYWNlKC9cXCgvZywgXCIlMjhcIikucmVwbGFjZSgvXFwpL2csIFwiJTI5XCIpO1xuICAgICAgICAgICAgcmV0dXJuIHVybDtcbiAgICB9LFxuICAgIHVybEFzQmFja2dydW5kOmZ1bmN0aW9uKHVybCwgZWwpe1xuICAgICAgICAgICAgdXJsID0gdGhpcy5zYWZlRmlsZW5hbWUodXJsKTtcbiAgICAgICAgICAgIHZhciBiZyA9ICd1cmwoXCInKyB1cmwgKydcIiknO1xuICAgICAgICAgICAgaWYoISFlbCl7XG4gICAgICAgICAgICAgICAgICAgIGVsLnN0eWxlLmJhY2tncm91bmRJbWFnZSA9IGJnO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGJnO1xuICAgIH0sXG4gICAgdXBsb2FkZXI6IGZ1bmN0aW9uIChvKSB7XG4gICAgICAgIHJldHVybiBtdy51cGxvYWQobyk7XG4gICAgfVxufVxuXG4iLCJcbm13LnJlcXVpcmUoJ2F1dG9jb21wbGV0ZS5qcycpO1xuXG5cbm13Lkljb25DbGFzc1Jlc29sdmVyID0gZnVuY3Rpb24gKCRmb3IpIHtcbiAgICBpZiAoISRmb3IpIHtcbiAgICAgICAgcmV0dXJuICcnO1xuICAgIH1cbiAgICBzd2l0Y2ggKCRmb3IpIHtcbiAgICAgICAgY2FzZSAnc2hvcCc6ICRmb3IgPSAnbWRpIG1kaS1zaG9wcGluZyc7IGJyZWFrO1xuICAgICAgICBjYXNlICd3ZWJzaXRlJzogJGZvciA9ICdtZGkgbWRpLWVhcnRoJzsgYnJlYWs7XG4gICAgICAgIGNhc2UgJ21vZHVsZSc6ICRmb3IgPSAnbWRpIG1kaS12aWV3LWdyaWQtcGx1cyc7IGJyZWFrO1xuICAgICAgICBjYXNlICdtYXJrZXRwbGFjZSc6ICRmb3IgPSAnbWRpIG1kaS1mcnVpdC1jaGVycmllcyc7IGJyZWFrO1xuICAgICAgICBjYXNlICd1c2Vycyc6ICRmb3IgPSAnbWRpIG1kaS1hY2NvdW50LW11bHRpcGxlJzsgYnJlYWs7XG4gICAgICAgIGNhc2UgJ3Bvc3QnOiAkZm9yID0gJ21kaSBtZGktdGV4dCc7IGJyZWFrO1xuICAgICAgICBjYXNlICdwYWdlJzogJGZvciA9ICdtZGkgbWRpLXNob3BwaW5nJzsgYnJlYWs7XG4gICAgICAgIGNhc2UgJ3N0YXRpYyc6ICRmb3IgPSAnbWRpIG1kaS1zaG9wcGluZyc7IGJyZWFrO1xuICAgICAgICBjYXNlICdjYXRlZ29yeSc6ICRmb3IgPSAnbWRpIG1kaS1mb2xkZXInOyBicmVhaztcbiAgICAgICAgY2FzZSAncHJvZHVjdCc6ICRmb3IgPSAnbWRpIG1kaS1zaG9wcGluZyc7IGJyZWFrO1xuXG4gICAgICAgIGRlZmF1bHQ6ICRmb3IgPSAnJztcbiAgICB9XG4gICAgcmV0dXJuICRmb3I7XG59O1xuXG5tdy5jb250cm9sRmllbGRzID0ge1xuICAgIF9faWQ6IG5ldyBEYXRlKCkuZ2V0VGltZSgpLFxuICAgIF9pZDogZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLl9faWQrKztcbiAgICAgICAgcmV0dXJuICdsZS0nICsgdGhpcy5fX2lkO1xuICAgIH0sXG4gICAgX2xhYmVsOiBmdW5jdGlvbiAoY29uZil7XG4gICAgICAgIHZhciBpZCA9IGNvbmYuaWQgfHwgdGhpcy5faWQoKTtcbiAgICAgICAgdmFyIGxhYmVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbGFiZWwnKTtcbiAgICAgICAgbGFiZWwuY2xhc3NOYW1lID0gY29uZi5jbGFzc05hbWUgfHwgJ213LXVpLWxhYmVsJztcbiAgICAgICAgbGFiZWwuaW5uZXJIVE1MID0gY29uZi5sYWJlbCB8fCBjb25mLmNvbnRlbnQgfHwgJyc7XG4gICAgICAgIGxhYmVsLmh0bWxGb3IgPSBpZDtcbiAgICAgICAgcmV0dXJuIGxhYmVsO1xuICAgIH0sXG4gICAgX2J1dHRvbjogZnVuY3Rpb24gKGNvbmYpe1xuICAgICAgICB2YXIgaWQgPSBjb25mLmlkIHx8IHRoaXMuX2lkKCk7XG4gICAgICAgIHZhciBidXR0b24gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdidXR0b24nKTtcbiAgICAgICAgYnV0dG9uLnR5cGUgPSBjb25mLnR5cGUgfHwgJ2J1dHRvbic7XG4gICAgICAgIGJ1dHRvbi5jbGFzc05hbWUgPSAnbXctdWktYnRuIGJ0bi0nICsgY29uZi5zaXplICsgJyBidG4tJyArIGNvbmYuY29sb3I7XG4gICAgICAgIGJ1dHRvbi5pbm5lckhUTUwgPSAoY29uZi5sYWJlbCB8fCBjb25mLmNvbnRlbnQpO1xuICAgICAgICByZXR1cm4gYnV0dG9uO1xuICAgIH0sXG4gICAgX3dyYXA6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGVsID0gIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBlbC5jbGFzc05hbWUgPSAnbXctdWktZmllbGQtaG9sZGVyJztcbiAgICAgICAgW10uZm9yRWFjaC5jYWxsKGFyZ3VtZW50cywgZnVuY3Rpb24gKGNvbnRlbnQpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgY29udGVudCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICBlbC5pbm5lckhUTUwgKz0gY29udGVudDtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgZWwuYXBwZW5kKGNvbnRlbnQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICAvLyByZXR1cm4gJzxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwXCI+JyArIGNvbnRlbnQgKyAnPC9kaXY+JztcbiAgICB9LFxuICAgIF9kZXNjcmlwdGlvbjogZnVuY3Rpb24gKGNvbmYpIHtcbiAgICAgICAgcmV0dXJuIGNvbmYuZGVzY3JpcHRpb24gPyAoJzxzbWFsbCBjbGFzcz1cInRleHQtbXV0ZWQgZC1ibG9jayBtYi0yXCI+JyArIGNvbmYuZGVzY3JpcHRpb24gKyAnPC9zbWFsbD4nKSA6ICcnO1xuICAgIH0sXG4gICAgZmllbGQ6IGZ1bmN0aW9uIChjb25mKSB7XG4gICAgICAgIGNvbmYgPSBjb25mIHx8IHt9O1xuICAgICAgICB2YXIgcGxhY2Vob2xkZXIgPSBjb25mLnBsYWNlaG9sZGVyID8gKCdwbGFjZWhvbGRlcj1cIicgKyBjb25mLnBsYWNlaG9sZGVyICsgJ1wiJykgOiAnJztcbiAgICAgICAgdmFyIGlkID0gKGNvbmYuaWQgfHwgdGhpcy5faWQoKSk7XG4gICAgICAgIGlkID0gICgnIGlkPVwiJyArIGlkICsgJ1wiICcpO1xuICAgICAgICB2YXIgbmFtZSA9IGNvbmYubmFtZSA/ICgnbmFtZT1cIicgKyBjb25mLm5hbWUgKyAnXCInKSA6ICcnO1xuICAgICAgICBjb25mLnR5cGUgPSBjb25mLnR5cGUgfHwgJ3RleHQnO1xuICAgICAgICB2YXIgcmVxdWlyZWQgPSBjb25mLnJlcXVpcmVkID8gKCdyZXF1aXJlZCcpIDogJyc7XG5cbiAgICAgICAgcmV0dXJuIHRoaXMuX3dyYXAoXG4gICAgICAgICAgICB0aGlzLl9sYWJlbChjb25mKSxcbiAgICAgICAgICAgIHRoaXMuX2Rlc2NyaXB0aW9uKGNvbmYpLFxuICAgICAgICAgICAgJzxpbnB1dCB0eXBlPVwiJytjb25mLnR5cGUrJ1wiICcrcGxhY2Vob2xkZXIgKyAnICAnICsgaWQgKyAnICcgKyBuYW1lICsgJyAnICsgcmVxdWlyZWQgKyAnIGNsYXNzPVwibXctdWktZmllbGQgdzEwMFwiPidcbiAgICAgICAgKTtcbiAgICB9LFxuICAgIGNoZWNrYm94OiBmdW5jdGlvbiAoY29uZikge1xuICAgICAgICBjb25mID0gY29uZiB8fCB7fTtcbiAgICAgICAgY29uZi5jbGFzc05hbWUgPSBjb25mLmNsYXNzTmFtZSB8fCAnY3VzdG9tLWNvbnRyb2wtbGFiZWwnO1xuICAgICAgICB2YXIgaWQgPSAoY29uZi5pZCB8fCB0aGlzLl9pZCgpKTtcbiAgICAgICAgY29uZi5pZCA9IGlkO1xuICAgICAgICBpZCA9ICAoJyBpZD1cIicgKyBpZCArICdcIiAnKTtcbiAgICAgICAgdmFyIG5hbWUgPSBjb25mLm5hbWUgPyAoJ25hbWU9XCInICsgY29uZi5uYW1lICsgJ1wiJykgOiAnJztcbiAgICAgICAgdmFyIHJlcXVpcmVkID0gY29uZi5yZXF1aXJlZCA/ICgncmVxdWlyZWQnKSA6ICcnO1xuICAgICAgICByZXR1cm4gIHRoaXMuX3dyYXAoXG4gICAgICAgICAgICAnPGxhYmVsIGNsYXNzPVwibXctdWktY2hlY2tcIj4nICtcbiAgICAgICAgICAgICc8aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgJyArIGlkICsgJyAnICsgbmFtZSArICcgJyArIHJlcXVpcmVkICsgJz4nICtcbiAgICAgICAgICAgICc8c3Bhbj48L3NwYW4+PHNwYW4+JyArIChjb25mLmxhYmVsIHx8IGNvbmYuY29udGVudCB8fCAnJykgKyAnPC9zcGFuPicgK1xuICAgICAgICAgICAgJzwvbGFiZWw+Jyk7XG4gICAgfSxcbiAgICByYWRpbzogZnVuY3Rpb24gKGNvbmYpIHtcbiAgICAgICAgY29uZiA9IGNvbmYgfHwge307XG4gICAgICAgIHZhciBpZCA9IChjb25mLmlkIHx8IHRoaXMuX2lkKCkpO1xuICAgICAgICBpZCA9ICAoJyBpZD1cIicgKyBpZCArICdcIiAnKTtcbiAgICAgICAgdmFyIHZhbHVlID0gICgnIHZhbHVlPVwiJyArIGNvbmYudmFsdWUgKyAnXCIgJyk7XG4gICAgICAgIHZhciBuYW1lID0gY29uZi5uYW1lID8gKCduYW1lPVwiJyArIGNvbmYubmFtZSArICdcIicpIDogJyc7XG4gICAgICAgIHZhciByZXF1aXJlZCA9IGNvbmYucmVxdWlyZWQgPyAoJ3JlcXVpcmVkJykgOiAnJztcbiAgICAgICAgcmV0dXJuICB0aGlzLl93cmFwKFxuICAgICAgICAgICAgJzxsYWJlbCBjbGFzcz1cIm13LXVpLWNoZWNrXCI+JyArXG4gICAgICAgICAgICAnPGlucHV0IHR5cGU9XCJyYWRpb1wiICcgKyBpZCArICcgJyArIG5hbWUgKyAnICcgKyByZXF1aXJlZCArICcgJyArIHZhbHVlICsgJz4nICtcbiAgICAgICAgICAgICAgICAnPHNwYW4+PC9zcGFuPjxzcGFuPicgKyAoY29uZi5sYWJlbCB8fCBjb25mLmNvbnRlbnQgfHwgJycpICsgJzwvc3Bhbj4nICtcbiAgICAgICAgICAgICc8L2xhYmVsPicpO1xuICAgIH0sXG4gICAgc2VsZWN0OiBmdW5jdGlvbiAoY29uZikge1xuICAgICAgICBjb25mID0gY29uZiB8fCB7fTtcbiAgICAgICAgdmFyIGlkID0gKGNvbmYuaWQgfHwgdGhpcy5faWQoKSk7XG4gICAgICAgIGlkID0gICgnIGlkPVwiJyArIGlkICsgJ1wiICcpO1xuICAgICAgICB2YXIgbmFtZSA9IGNvbmYubmFtZSA/ICgnbmFtZT1cIicgKyBjb25mLm5hbWUgKyAnXCInKSA6ICcnO1xuICAgICAgICB2YXIgcmVxdWlyZWQgPSBjb25mLnJlcXVpcmVkID8gKCdyZXF1aXJlZCcpIDogJyc7XG4gICAgICAgIHZhciBtdWx0aXBsZSA9IGNvbmYubXVsdGlwbGUgPyAoJ211bHRpcGxlJykgOiAnJztcblxuICAgICAgICB2YXIgb3B0aW9ucyA9IChjb25mLm9wdGlvbnMgfHwgW10pLm1hcChmdW5jdGlvbiAoaXRlbSl7XG4gICAgICAgICAgICByZXR1cm4gJzxvcHRpb24gdmFsdWU9XCInKyBpdGVtLnZhbHVlICsnXCI+JysoaXRlbS50aXRsZXx8aXRlbS5uYW1lfHxpdGVtLmxhYmVsfHxpdGVtLnZhbHVlKSsnPC9vcHRpb24+JztcbiAgICAgICAgfSkuam9pbignJyk7XG5cbiAgICAgICAgcmV0dXJuICB0aGlzLl93cmFwKFxuICAgICAgICAgICAgdGhpcy5fbGFiZWwoY29uZikgK1xuICAgICAgICAgICAgJzxzZWxlY3QgY2xhc3M9XCJzZWxlY3RwaWNrZXJcIiAnICsgbXVsdGlwbGUgKyAnICAnICsgaWQgKyAnICcgKyBuYW1lICsgJyAnICsgcmVxdWlyZWQgKyAnPicgK1xuICAgICAgICAgICAgb3B0aW9ucyArXG4gICAgICAgICAgICAnPC9zZWxlY3Q+JyApO1xuICAgIH1cbn07XG5cbm13LmVtaXR0ZXIgPSB7XG4gICAgX2V2ZW50czoge30sXG4gICAgX29uTmF0aXZlOiBmdW5jdGlvbiAobm9kZSwgdHlwZSwgY2FsbGJhY2spIHtcbiAgICAgICAgdHlwZS50cmltKCkuc3BsaXQoJyAnKS5mb3JFYWNoKGZ1bmN0aW9uIChldikge1xuICAgICAgICAgICAgbm9kZS5hZGRFdmVudExpc3RlbmVyKGV2LCBjYWxsYmFjayk7XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgb246IGZ1bmN0aW9uIChldmVudCwgY2FsbGJhY2ssIGMpIHtcbiAgICAgICAgaWYoIWV2ZW50KSByZXR1cm47XG4gICAgICAgIGlmKEFycmF5LmlzQXJyYXkoZXZlbnQpKSB7XG4gICAgICAgICAgICBmb3IodmFyIGkgPSAwOyBpIDwgZXZlbnQubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICB0aGlzLm9uKGV2ZW50W2ldLCBjYWxsYmFjaywgYyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgaWYoZXZlbnQubm9kZU5hbWUpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLl9vbk5hdGl2ZShldmVudCwgY2FsbGJhY2ssIGMpO1xuICAgICAgICB9XG4gICAgICAgIGlmICghdGhpcy5fZXZlbnRzW2V2ZW50XSl7XG4gICAgICAgICAgICB0aGlzLl9ldmVudHNbZXZlbnRdID0gW107XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5fZXZlbnRzW2V2ZW50XS5wdXNoKGNhbGxiYWNrKTtcbiAgICB9LFxuICAgIGRpc3BhdGNoOiBmdW5jdGlvbihldmVudCwgZGF0YSkge1xuICAgICAgICBpZiAodGhpcy5fZXZlbnRzW2V2ZW50XSkge1xuICAgICAgICAgICAgdGhpcy5fZXZlbnRzW2V2ZW50XS5mb3JFYWNoKGZ1bmN0aW9uKGhhbmRsZXIpIHtcbiAgICAgICAgICAgICAgICBoYW5kbGVyKGRhdGEpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG4oZnVuY3Rpb24oKXtcbiAgICB2YXIgVUlGb3JtQ29udHJvbGxlcnMgPSB7XG4gICAgICAgIF90aXRsZTogZnVuY3Rpb24gKGNvbmYsIHJvb3QpIHtcbiAgICAgICAgICAgIHZhciB0aXRsZSA9IG13LmVsZW1lbnQoe1xuICAgICAgICAgICAgICAgIHRhZzogJ2g1JyxcbiAgICAgICAgICAgICAgICBwcm9wczoge1xuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtdy11aS1mb3JtLWNvbnRyb2xsZXItdGl0bGUnLFxuICAgICAgICAgICAgICAgICAgICBpbm5lckhUTUw6ICc8c3Ryb25nPicgKyBjb25mLnRpdGxlICsgJzwvc3Ryb25nPidcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LmVsZW1lbnQocm9vdCkuYXBwZW5kKHRpdGxlKTtcbiAgICAgICAgfSxcbiAgICAgICAgZm9vdGVyOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgZGF0YSA9IHt9O1xuICAgICAgICAgICAgZGF0YS5vayA9ICBtdy5jb250cm9sRmllbGRzLl9idXR0b24oe2NvbnRlbnQ6IG13LmxhbmcoJ09LJyksIGNvbG9yOiAncHJpbWFyeSd9KTtcbiAgICAgICAgICAgIGRhdGEuY2FuY2VsID0gIG13LmNvbnRyb2xGaWVsZHMuX2J1dHRvbih7Y29udGVudDogbXcubGFuZygnQ2FuY2VsJyl9KTtcbiAgICAgICAgICAgIGRhdGEucm9vdCA9IG13LmNvbnRyb2xGaWVsZHMuX3dyYXAoZGF0YS5jYW5jZWwsIGRhdGEub2spO1xuICAgICAgICAgICAgZGF0YS5yb290LmNsYXNzTmFtZSA9ICdtdy11aS1mb3JtLWNvbnRyb2xsZXJzLWZvb3Rlcic7XG4gICAgICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgICAgfSxcbiAgICAgICAgdGl0bGU6IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgICAgIHRleHQ6IHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG13LmxhbmcoJ0xpbmsgdGV4dCcpLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogbXcubGFuZygnU2VsZWN0ZWQgdGV4dCBmb3IgdGhlIGxpbmsuJyksXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS12aWV3LWNvbXBhY3Qtb3V0bGluZScsXG4gICAgICAgICAgICAgICAgLy8gaWNvbjogJ21kaSBtZGktZm9ybWF0LXBhZ2UtYnJlYWsnLFxuICAgICAgICAgICAgICAgIHRpdGxlOiAnUGFnZSB0aXRsZSdcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBvcHRpb25zID0gIG13Lm9iamVjdC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCAob3B0aW9ucyB8fCB7fSkpO1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncyA9IG9wdGlvbnM7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0ID09PSB0cnVlKSBvcHRpb25zLnRleHQgPSBkZWZhdWx0cy50ZXh0O1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMubGluayA9PT0gdHJ1ZSkgb3B0aW9ucy5saW5rID0gZGVmYXVsdHMubGluaztcbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRhcmdldCA9PT0gdHJ1ZSkgb3B0aW9ucy50YXJnZXQgPSBkZWZhdWx0cy50YXJnZXQ7XG5cbiAgICAgICAgICAgIHZhciByb290ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICByb290LmNsYXNzTmFtZSA9ICdtdy11aS1mb3JtLWNvbnRyb2xsZXItcm9vdCc7XG4gICAgICAgICAgICB2YXIgX2xpbmtUZXh0ID0gJycsIF9saW5rVXJsID0gJycsIF90YXJnZXQgPSAnJztcblxuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGV4dCkge1xuICAgICAgICAgICAgICAgIF9saW5rVGV4dCA9IG13LmNvbnRyb2xGaWVsZHMuZmllbGQoe1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogb3B0aW9ucy50ZXh0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogb3B0aW9ucy50ZXh0LmRlc2NyaXB0aW9uLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGV4dCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgVUlGb3JtQ29udHJvbGxlcnMuX3RpdGxlKHRoaXMuc2V0dGluZ3MsIHJvb3QpXG5cblxuICAgICAgICAgICAgdmFyIGNvdW50ID0gMDtcbiAgICAgICAgICAgIHZhciBodG1sID0gW107XG4gICAgICAgICAgICB0aGlzLnVybCA9ICcnO1xuICAgICAgICAgICAgdmFyIGF2YWlsYWJsZV9lbGVtZW50cyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgYXZhaWxhYmxlX2VsZW1lbnRzLmNsYXNzTmFtZSA9ICdtdy11aS1ib3ggbXctdWktYm94LWNvbnRlbnQnO1xuICAgICAgICAgICAgdmFyIHJuYW1lID0gbXcuY29udHJvbEZpZWxkcy5faWQoKTtcbiAgICAgICAgICAgIG13LnRvcCgpLiQoXCJoMSxoMTIsaDMsaDQsaDUsaDZcIiwgbXcudG9wKCkud2luLmRvY3VtZW50LmJvZHkpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKCEhdGhpcy5pZCB8fCBtdy50b29scy5pc0VkaXRhYmxlKHRoaXMpKXtcbiAgICAgICAgICAgICAgICAgICAgaWYoIXRoaXMuaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaWQgPSBtdy5pZCgnbXctdGl0bGUtZWxlbWVudC0nKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBjb3VudCsrO1xuICAgICAgICAgICAgICAgICAgICBodG1sLnB1c2goe2lkOiB0aGlzLmlkLCB0ZXh0OiB0aGlzLnRleHRDb250ZW50fSk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBsaSA9IG13LmNvbnRyb2xGaWVsZHMucmFkaW8oe1xuICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICAnPHN0cm9uZz4nICsgdGhpcy5ub2RlTmFtZSArICc8L3N0cm9uZz4gLSAnICsgdGhpcy50ZXh0Q29udGVudCxcbiAgICAgICAgICAgICAgICAgICAgICAgIG5hbWU6IHJuYW1lLFxuICAgICAgICAgICAgICAgICAgICAgICAgaWQ6IG13LmNvbnRyb2xGaWVsZHMuX2lkKCksXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZTogJyMnICsgdGhpcy5pZFxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgbXcuZWxlbWVudChhdmFpbGFibGVfZWxlbWVudHMpLmFwcGVuZChsaSk7XG4gICAgICAgICAgICAgICAgICAgIGxpLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0Jykub25pbnB1dCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNjb3BlLnVybCA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgICAgICAgICBzY29wZS52YWxpZCgpO1xuICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtUZXh0KTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoYXZhaWxhYmxlX2VsZW1lbnRzKTtcblxuXG4gICAgICAgICAgICB2YXIgdGV4dEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidGV4dFwiXScpO1xuXG4gICAgICAgICAgICB0aGlzLnZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciByZXMgPSB0aGlzLmlzVmFsaWQoKTtcbiAgICAgICAgICAgICAgICBmb290ZXIub2suZGlzYWJsZWQgPSAhcmVzO1xuICAgICAgICAgICAgICAgIHJldHVybiByZXM7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLmlzVmFsaWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYodGV4dEZpZWxkICYmICF0ZXh0RmllbGQudmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZighdGhpcy51cmwpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIGZvb3RlciA9IFVJRm9ybUNvbnRyb2xsZXJzLmZvb3RlcigpO1xuXG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24gKHZhbCkge1xuICAgICAgICAgICAgICAgIHZhbCA9IHZhbCB8fCB7fTtcbiAgICAgICAgICAgICAgICBpZih0ZXh0RmllbGQpIHRleHRGaWVsZC52YWx1ZSA9IHZhbC50ZXh0O1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5nZXRWYWx1ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgdmFsID0ge307XG4gICAgICAgICAgICAgICAgaWYodGV4dEZpZWxkKSB2YWwudGV4dCA9IHRleHRGaWVsZC52YWx1ZTtcbiAgICAgICAgICAgICAgICB2YWwudXJsID0gdGhpcy51cmxcbiAgICAgICAgICAgICAgICByZXR1cm4gdmFsO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fb25DaGFuZ2UgPSBbXTtcbiAgICAgICAgICAgIHRoaXMub25DaGFuZ2UgPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLl9jb25maXJtID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ29uZmlybSA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY29uZmlybS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY2FuY2VsID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2FuY2VsID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9jYW5jZWwucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihbdGV4dEZpZWxkXSwgJ2lucHV0JywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLm9rLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NvbmZpcm0uZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihmb290ZXIuY2FuY2VsLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NhbmNlbC5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgZigpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGhvbGRlcik7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGZvb3Rlci5yb290KTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCgpO1xuXG4gICAgICAgICAgICB0aGlzLnJvb3QgPSByb290O1xuICAgICAgICB9LFxuICAgICAgICBsYXlvdXQ6IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgICAgIHRleHQ6IHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG13LmxhbmcoJ0xpbmsgdGV4dCcpLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogbXcubGFuZygnU2VsZWN0ZWQgdGV4dCBmb3IgdGhlIGxpbmsuJyksXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS12aWV3LWNvbXBhY3Qtb3V0bGluZScsXG4gICAgICAgICAgICAgICAgLy8gaWNvbjogJ21kaSBtZGktZm9ybWF0LXBhZ2UtYnJlYWsnLFxuICAgICAgICAgICAgICAgIHRpdGxlOiAnUGFnZSBibG9jaydcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBvcHRpb25zID0gIG13Lm9iamVjdC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCAob3B0aW9ucyB8fCB7fSkpO1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncyA9IG9wdGlvbnM7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0ID09PSB0cnVlKSBvcHRpb25zLnRleHQgPSBkZWZhdWx0cy50ZXh0O1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMubGluayA9PT0gdHJ1ZSkgb3B0aW9ucy5saW5rID0gZGVmYXVsdHMubGluaztcbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRhcmdldCA9PT0gdHJ1ZSkgb3B0aW9ucy50YXJnZXQgPSBkZWZhdWx0cy50YXJnZXQ7XG5cbiAgICAgICAgICAgIHZhciByb290ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICByb290LmNsYXNzTmFtZSA9ICdtdy11aS1mb3JtLWNvbnRyb2xsZXItcm9vdCc7XG4gICAgICAgICAgICB2YXIgX2xpbmtUZXh0ID0gJycsIF9saW5rVXJsID0gJycsIF90YXJnZXQgPSAnJztcblxuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGV4dCkge1xuICAgICAgICAgICAgICAgIF9saW5rVGV4dCA9IG13LmNvbnRyb2xGaWVsZHMuZmllbGQoe1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogb3B0aW9ucy50ZXh0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogb3B0aW9ucy50ZXh0LmRlc2NyaXB0aW9uLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGV4dCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIFVJRm9ybUNvbnRyb2xsZXJzLl90aXRsZSh0aGlzLnNldHRpbmdzLCByb290KVxuXG4gICAgICAgICAgICB2YXIgbGF5b3V0c0RhdGEgPSBbXTtcbiAgICAgICAgICAgIHZhciBsYXlvdXRzID0gbXcudG9wKCkuJCgnLm1vZHVsZVtkYXRhLXR5cGU9XCJsYXlvdXRzXCJdJyk7XG4gICAgICAgICAgICBsYXlvdXRzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGxheW91dHNEYXRhLnB1c2goe1xuICAgICAgICAgICAgICAgICAgICBuYW1lOiB0aGlzLmdldEF0dHJpYnV0ZSgndGVtcGxhdGUnKS5zcGxpdCgnLicpWzBdLFxuICAgICAgICAgICAgICAgICAgICBlbGVtZW50OiB0aGlzLFxuICAgICAgICAgICAgICAgICAgICBpZDogdGhpcy5pZFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB2YXIgbGlzdCA9ICQoJzxkaXYgLz4nKTtcbiAgICAgICAgICAgIHRoaXMubGluayA9ICcnO1xuICAgICAgICAgICAgdmFyIHJhZGlvTmFtZSA9IG13LmlkKCk7XG4gICAgICAgICAgICAkLmVhY2gobGF5b3V0c0RhdGEsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgdmFyIGxpID0gbXcuY29udHJvbEZpZWxkcy5yYWRpbyh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiB0aGlzLm5hbWUsXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6IHJhZGlvTmFtZSxcbiAgICAgICAgICAgICAgICAgICAgaWQ6IG13LmNvbnRyb2xGaWVsZHMuX2lkKClcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgZWwgPSB0aGlzLmVsZW1lbnQ7XG4gICAgICAgICAgICAgICAgJChsaSkuZmluZCgnaW5wdXQnKS5vbignY2xpY2snLCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICBtdy50b3AoKS50b29scy5zY3JvbGxUbyhlbCk7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmxpbmsgPSBtdy50b3AoKS53aW4ubG9jYXRpb24uaHJlZi5zcGxpdCgnIycpWzBdICsgJyNtd0AnICsgZWwuaWQ7XG5cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBsaXN0LmFwcGVuZChsaSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmKGxheW91dHNEYXRhLmxlbmd0aCA+IDApe1xuICAgICAgICAgICAgICAgICQoJy5wYWdlLWxheW91dC1idG4nKS5zaG93KCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtUZXh0KTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQobGlzdFswXSk7XG5cblxuICAgICAgICAgICAgdmFyIHRleHRGaWVsZCA9IGhvbGRlci5xdWVyeVNlbGVjdG9yKCdbbmFtZT1cInRleHRcIl0nKTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmVzID0gdGhpcy5pc1ZhbGlkKCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLm9rLmRpc2FibGVkID0gIXJlcztcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5pc1ZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCAmJiAhdGV4dEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYoIXRoaXMubGluaykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgZm9vdGVyID0gVUlGb3JtQ29udHJvbGxlcnMuZm9vdGVyKCk7XG5cbiAgICAgICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbiAodmFsKSB7XG4gICAgICAgICAgICAgICAgdmFsID0gdmFsIHx8IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdGV4dEZpZWxkLnZhbHVlID0gdmFsLnRleHQ7XG4gICAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuZ2V0VmFsdWUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdmFsLnRleHQgPSB0ZXh0RmllbGQudmFsdWU7XG4gICAgICAgICAgICAgICAgICByZXR1cm4gdmFsO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fb25DaGFuZ2UgPSBbXTtcbiAgICAgICAgICAgIHRoaXMub25DaGFuZ2UgPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLl9jb25maXJtID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ29uZmlybSA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY29uZmlybS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY2FuY2VsID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2FuY2VsID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9jYW5jZWwucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihbdGV4dEZpZWxkXSwgJ2lucHV0JywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLm9rLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgIHNjb3BlLl9jb25maXJtLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICBmKHNjb3BlLmdldFZhbHVlKCkpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLmNhbmNlbCwgJ2NsaWNrJywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIHNjb3BlLl9jYW5jZWwuZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICByb290LmFwcGVuZChob2xkZXIpO1xuXG4gICAgICAgICAgICByb290LmFwcGVuZChmb290ZXIucm9vdCk7XG5cbiAgICAgICAgICAgIHRoaXMudmFsaWQoKTtcblxuICAgICAgICAgICAgdGhpcy5yb290ID0gcm9vdDtcbiAgICAgICAgfSxcbiAgICAgICAgZW1haWw6IGZ1bmN0aW9uIChvcHRpb25zKSB7XG4gICAgICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICAgICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICAgICAgICAgIHRleHQ6IHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG13LmxhbmcoJ0xpbmsgdGV4dCcpLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogbXcubGFuZygnU2VsZWN0ZWQgdGV4dCBmb3IgdGhlIGxpbmsuJyksXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBsaW5rOiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdFbWFpbCcpLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogbXcubGFuZygnVHlwZSBlbWFpbCBhZGRyZXNzIGluIHRoZSBmaWVsZCcpLFxuICAgICAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogXCJoZWxsb0BleGFtcGxlLmNvbVwiLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdGFyZ2V0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdPcGVuIHRoZSBsaW5rIGluIGEgbmV3IHdpbmRvdycpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS1lbWFpbC1vdXRsaW5lJyxcbiAgICAgICAgICAgICAgICB0aXRsZTogJ0VtYWlsJ1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIG9wdGlvbnMgPSAgbXcub2JqZWN0LmV4dGVuZCh0cnVlLCB7fSwgZGVmYXVsdHMsIChvcHRpb25zIHx8IHt9KSk7XG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzID0gb3B0aW9ucztcbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRleHQgPT09IHRydWUpIG9wdGlvbnMudGV4dCA9IGRlZmF1bHRzLnRleHQ7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy5saW5rID09PSB0cnVlKSBvcHRpb25zLmxpbmsgPSBkZWZhdWx0cy5saW5rO1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGFyZ2V0ID09PSB0cnVlKSBvcHRpb25zLnRhcmdldCA9IGRlZmF1bHRzLnRhcmdldDtcblxuICAgICAgICAgICAgdmFyIHJvb3QgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHJvb3QuY2xhc3NOYW1lID0gJ213LXVpLWZvcm0tY29udHJvbGxlci1yb290JztcbiAgICAgICAgICAgIHZhciBfbGlua1RleHQgPSAnJywgX2xpbmtVcmwgPSAnJywgX3RhcmdldCA9ICcnO1xuICAgICAgICAgICAgVUlGb3JtQ29udHJvbGxlcnMuX3RpdGxlKHRoaXMuc2V0dGluZ3MsIHJvb3QpXG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0KSB7XG4gICAgICAgICAgICAgICAgX2xpbmtUZXh0ID0gbXcuY29udHJvbEZpZWxkcy5maWVsZCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLnRleHQubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIGRlc2NyaXB0aW9uOiBvcHRpb25zLnRleHQuZGVzY3JpcHRpb24sXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKG9wdGlvbnMubGluaykge1xuICAgICAgICAgICAgICAgIF9saW5rVXJsID0gbXcuY29udHJvbEZpZWxkcy5maWVsZCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLmxpbmsubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIGRlc2NyaXB0aW9uOiBvcHRpb25zLmxpbmsuZGVzY3JpcHRpb24sXG4gICAgICAgICAgICAgICAgICAgIHBsYWNlaG9sZGVyOiBvcHRpb25zLmxpbmsucGxhY2Vob2xkZXIsXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd1cmwnXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRhcmdldCkge1xuICAgICAgICAgICAgICAgIF90YXJnZXQgPSBtdy5jb250cm9sRmllbGRzLmNoZWNrYm94KHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG9wdGlvbnMudGFyZ2V0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGFyZ2V0J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtUZXh0KTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtVcmwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZChfdGFyZ2V0KTtcblxuXG4gICAgICAgICAgICB2YXIgdGV4dEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidGV4dFwiXScpO1xuICAgICAgICAgICAgdmFyIHVybEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidXJsXCJdJyk7XG4gICAgICAgICAgICB2YXIgdGFyZ2V0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0YXJnZXRcIl0nKTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmVzID0gdGhpcy5pc1ZhbGlkKCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLm9rLmRpc2FibGVkID0gIXJlcztcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5pc1ZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCAmJiAhdGV4dEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYodXJsRmllbGQgJiYgIXVybEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gdXJsRmllbGQudmFsaWRpdHk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgZm9vdGVyID0gVUlGb3JtQ29udHJvbGxlcnMuZm9vdGVyKCk7XG5cbiAgICAgICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbiAodmFsKSB7XG4gICAgICAgICAgICAgICAgdmFsID0gdmFsIHx8IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdGV4dEZpZWxkLnZhbHVlID0gdmFsLnRleHQ7XG4gICAgICAgICAgICAgICAgaWYodXJsRmllbGQpIHVybEZpZWxkLnZhbHVlID0gKHZhbC51cmwgfHwgJycpO1xuICAgICAgICAgICAgICAgIGlmKHRhcmdldEZpZWxkKSAgdGFyZ2V0RmllbGQuY2hlY2tlZCA9IHZhbC50YXJnZXQ7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLmdldFZhbHVlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSB7fTtcbiAgICAgICAgICAgICAgICBpZih0ZXh0RmllbGQpIHZhbC50ZXh0ID0gdGV4dEZpZWxkLnZhbHVlO1xuICAgICAgICAgICAgICAgIGlmKHVybEZpZWxkKSB2YWwudXJsID0gJ21haWx0bzonICsgdXJsRmllbGQudmFsdWU7XG4gICAgICAgICAgICAgICAgaWYodGFyZ2V0RmllbGQpIHZhbC50YXJnZXQgPSB0YXJnZXRGaWVsZC5jaGVja2VkO1xuICAgICAgICAgICAgICAgIHJldHVybiB2YWw7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLl9vbkNoYW5nZSA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNoYW5nZSA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fb25DaGFuZ2UucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX2NvbmZpcm0gPSBbXTtcbiAgICAgICAgICAgIHRoaXMub25Db25maXJtID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9jb25maXJtLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLl9jYW5jZWwgPSBbXTtcbiAgICAgICAgICAgIHRoaXMub25DYW5jZWwgPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX2NhbmNlbC5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuXG4gICAgICAgICAgICBtdy5lbWl0dGVyLm9uKFt0ZXh0RmllbGQsIHVybEZpZWxkLCB0YXJnZXRGaWVsZF0sICdpbnB1dCcsIGZ1bmN0aW9uIChlKXtcbiAgICAgICAgICAgICAgICBpZihzY29wZS52YWxpZCgpKSB7XG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLl9vbkNoYW5nZS5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBtdy5lbWl0dGVyLm9uKGZvb3Rlci5vaywgJ2NsaWNrJywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIHNjb3BlLl9jb25maXJtLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICBmKHNjb3BlLmdldFZhbHVlKCkpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLmNhbmNlbCwgJ2NsaWNrJywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIHNjb3BlLl9jYW5jZWwuZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICByb290LmFwcGVuZChob2xkZXIpO1xuXG4gICAgICAgICAgICByb290LmFwcGVuZChmb290ZXIucm9vdCk7XG5cbiAgICAgICAgICAgIHRoaXMudmFsaWQoKTtcblxuICAgICAgICAgICAgdGhpcy5yb290ID0gcm9vdDtcbiAgICAgICAgfSxcblxuICAgICAgICBwb3N0OiBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICB0ZXh0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdMaW5rIHRleHQnKSxcbiAgICAgICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IG13LmxhbmcoJ1NlbGVjdGVkIHRleHQgZm9yIHRoZSBsaW5rLicpLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdGFyZ2V0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdPcGVuIHRoZSBsaW5rIGluIGEgbmV3IHdpbmRvdycpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICB1cmw6IHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG13LmxhbmcoJ1NlYXJjaCBmb3IgY29udGVudCcpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS1mb3JtYXQtbGlzdC1idWxsZXRlZC1zcXVhcmUnLFxuICAgICAgICAgICAgICAgIHRpdGxlOiAnUG9zdC9jYXRlZ29yeScsXG4gICAgICAgICAgICAgICAgZGF0YVVybDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG13LnNldHRpbmdzLnNpdGVfdXJsICsgXCJhcGkvZ2V0X2NvbnRlbnRfYWRtaW5cIjtcbiAgICAgICAgICAgICAgICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgb3B0aW9ucyA9ICBtdy5vYmplY3QuZXh0ZW5kKHRydWUsIHt9LCBkZWZhdWx0cywgKG9wdGlvbnMgfHwge30pKTtcbiAgICAgICAgICAgIHRoaXMuc2V0dGluZ3MgPSBvcHRpb25zO1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGV4dCA9PT0gdHJ1ZSkgb3B0aW9ucy50ZXh0ID0gZGVmYXVsdHMudGV4dDtcbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRhcmdldCA9PT0gdHJ1ZSkgb3B0aW9ucy50YXJnZXQgPSBkZWZhdWx0cy50YXJnZXQ7XG5cbiAgICAgICAgICAgIHZhciByb290ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICByb290LmNsYXNzTmFtZSA9ICdtdy11aS1mb3JtLWNvbnRyb2xsZXItcm9vdCc7XG4gICAgICAgICAgICB2YXIgX2xpbmtUZXh0ID0gJycsIF9saW5rVXJsID0gJycsIF90YXJnZXQgPSAnJztcbiAgICAgICAgICAgIFVJRm9ybUNvbnRyb2xsZXJzLl90aXRsZSh0aGlzLnNldHRpbmdzLCByb290KTtcbiAgICAgICAgICAgIHZhciB0cmVlRWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHRyZWVFbC5jbGFzc05hbWUgPSAnZm9ybS1ncm91cCBtdy1saW5rLWVkaXRvci1wb3N0cy1zZWFyY2gnO1xuXG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0KSB7XG4gICAgICAgICAgICAgICAgX2xpbmtUZXh0ID0gbXcuY29udHJvbEZpZWxkcy5maWVsZCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLnRleHQubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIGRlc2NyaXB0aW9uOiBvcHRpb25zLnRleHQuZGVzY3JpcHRpb24sXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHVybCA9ICB0aGlzLnNldHRpbmdzLmRhdGFVcmw7XG4gICAgICAgICAgICB1cmwgPSB0eXBlb2YgdXJsID09PSAnZnVuY3Rpb24nID8gdXJsKCkgOiB1cmw7XG5cbiAgICAgICAgICAgIHRoaXMuYXV0b0NvbXBsZXRlID0gbmV3IG13LmF1dG9Db21wbGV0ZSh7XG4gICAgICAgICAgICAgICAgZWxlbWVudDogdHJlZUVsLFxuICAgICAgICAgICAgICAgIHRpdGxlRGVjb3JhdG9yOiBmdW5jdGlvbiAodGl0bGUsIGRhdGEpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHR5cGUgPSBkYXRhLnN1YnR5cGUgPT09ICdzdGF0aWMnID8gJ3BhZ2UnIDogZGF0YS5zdWJ0eXBlO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJzxzcGFuIGNsYXNzPVwiIHRpcCAnK213Lkljb25DbGFzc1Jlc29sdmVyKGRhdGEuc3VidHlwZSkrJ1wiIGRhdGEtdGlwPVwiJyArIHR5cGUgKyAnXCI+PC9zcGFuPicgKyB0aXRsZTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGFqYXhDb25maWc6IHtcbiAgICAgICAgICAgICAgICAgICAgbWV0aG9kOiAncG9zdCcsXG4gICAgICAgICAgICAgICAgICAgIHVybDogdXJsLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBsaW1pdDogJzUnLFxuICAgICAgICAgICAgICAgICAgICAgICAga2V5d29yZDogJyR7dmFsfScsXG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlcl9ieTogJ3VwZGF0ZWRfYXQgZGVzYycsXG4gICAgICAgICAgICAgICAgICAgICAgICBzZWFyY2hfaW5fZmllbGRzOiAndGl0bGUnLFxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cblxuICAgICAgICAgICAgdmFyIGxhYmVsID0gbXcuY29udHJvbEZpZWxkcy5fbGFiZWwoe1xuICAgICAgICAgICAgICAgIGNvbnRlbnQ6IG9wdGlvbnMudXJsLmxhYmVsXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKXtcbiAgICAgICAgICAgICAgICBtdy5lbGVtZW50KHRyZWVFbCkuYmVmb3JlKGxhYmVsKTtcbiAgICAgICAgICAgIH0sIDEwKVxuXG4gICAgICAgICAgICBpZiAob3B0aW9ucy50YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBfdGFyZ2V0ID0gbXcuY29udHJvbEZpZWxkcy5jaGVja2JveCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLnRhcmdldC5sYWJlbCxcbiAgICAgICAgICAgICAgICAgICAgbmFtZTogJ3RhcmdldCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKF9saW5rVGV4dCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKHRyZWVFbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKF90YXJnZXQpO1xuXG5cbiAgICAgICAgICAgIHZhciB0ZXh0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0ZXh0XCJdJyk7XG4gICAgICAgICAgICB2YXIgdGFyZ2V0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0YXJnZXRcIl0nKTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmVzID0gdGhpcy5pc1ZhbGlkKCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLm9rLmRpc2FibGVkID0gIXJlcztcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5pc1ZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCAmJiAhdGV4dEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgZm9vdGVyID0gVUlGb3JtQ29udHJvbGxlcnMuZm9vdGVyKCk7XG5cbiAgICAgICAgICAgIHRoaXMuZ2V0VmFsdWUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdmFsLnRleHQgPSB0ZXh0RmllbGQudmFsdWU7XG4gICAgICAgICAgICAgICAgdmFyIGdldFNlbGVjdGVkID0gdGhpcy5hdXRvQ29tcGxldGUuc2VsZWN0ZWRbMF07XG4gICAgICAgICAgICAgICAgdmFsLnVybCA9IGdldFNlbGVjdGVkID8gZ2V0U2VsZWN0ZWQudXJsIDogJyc7XG4gICAgICAgICAgICAgICAgdmFsLmRhdGEgPSBnZXRTZWxlY3RlZDtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdmFsLnRhcmdldCA9IHRhcmdldEZpZWxkLmNoZWNrZWQ7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbiAodmFsKSB7XG4gICAgICAgICAgICAgICAgaWYodGV4dEZpZWxkKSB0ZXh0RmllbGQudmFsdWUgPSB2YWwudGV4dDtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdGFyZ2V0RmllbGQuY2hlY2tlZCA9ICEhdmFsLnRhcmdldDtcbiAgICAgICAgICAgICAgICByZXR1cm4gdmFsO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fb25DaGFuZ2UgPSBbXTtcbiAgICAgICAgICAgIHRoaXMub25DaGFuZ2UgPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLl9jb25maXJtID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ29uZmlybSA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY29uZmlybS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY2FuY2VsID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2FuY2VsID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9jYW5jZWwucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgICQodGhpcy5hdXRvQ29tcGxldGUpLm9uKFwiY2hhbmdlXCIsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBtdy5lbWl0dGVyLm9uKFt0ZXh0RmllbGQsIHRhcmdldEZpZWxkXSwgJ2lucHV0JywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLm9rLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NvbmZpcm0uZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihmb290ZXIuY2FuY2VsLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NhbmNlbC5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgZigpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGhvbGRlcik7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGZvb3Rlci5yb290KTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCgpO1xuXG4gICAgICAgICAgICB0aGlzLnJvb3QgPSByb290O1xuICAgICAgICB9LFxuICAgICAgICBwYWdlOiBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICB0ZXh0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdMaW5rIHRleHQnKSxcbiAgICAgICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IG13LmxhbmcoJ1NlbGVjdGVkIHRleHQgZm9yIHRoZSBsaW5rLicpLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdGFyZ2V0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdPcGVuIHRoZSBsaW5rIGluIGEgbmV3IHdpbmRvdycpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS1maWxlLWxpbmstb3V0bGluZScsXG4gICAgICAgICAgICAgICAgdGl0bGU6ICdNeSB3ZWJzaXRlJyxcbiAgICAgICAgICAgICAgICBkYXRhVXJsOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbXcuc2V0dGluZ3MuYXBpX3VybCArICdjb250ZW50L2dldF9hZG1pbl9qc190cmVlX2pzb24nO1xuICAgICAgICAgICAgICAgICAgICB9IGNhdGNoIChlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBvcHRpb25zID0gIG13Lm9iamVjdC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCAob3B0aW9ucyB8fCB7fSkpO1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncyA9IG9wdGlvbnM7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0ID09PSB0cnVlKSBvcHRpb25zLnRleHQgPSBkZWZhdWx0cy50ZXh0O1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGFyZ2V0ID09PSB0cnVlKSBvcHRpb25zLnRhcmdldCA9IGRlZmF1bHRzLnRhcmdldDtcblxuICAgICAgICAgICAgdmFyIHJvb3QgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHJvb3QuY2xhc3NOYW1lID0gJ213LXVpLWZvcm0tY29udHJvbGxlci1yb290JztcbiAgICAgICAgICAgIHZhciBfbGlua1RleHQgPSAnJywgX2xpbmtVcmwgPSAnJywgX3RhcmdldCA9ICcnO1xuICAgICAgICAgICAgVUlGb3JtQ29udHJvbGxlcnMuX3RpdGxlKHRoaXMuc2V0dGluZ3MsIHJvb3QpXG4gICAgICAgICAgICB2YXIgdHJlZUVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICB0cmVlRWwuY2xhc3NOYW1lID0gJ2Zvcm0tZ3JvdXAnO1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGV4dCkge1xuICAgICAgICAgICAgICAgIF9saW5rVGV4dCA9IG13LmNvbnRyb2xGaWVsZHMuZmllbGQoe1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogb3B0aW9ucy50ZXh0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogb3B0aW9ucy50ZXh0LmRlc2NyaXB0aW9uLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGV4dCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB1cmwgPSAgdGhpcy5zZXR0aW5ncy5kYXRhVXJsO1xuICAgICAgICAgICAgdXJsID0gdHlwZW9mIHVybCA9PT0gJ2Z1bmN0aW9uJyA/IHVybCgpIDogdXJsO1xuICAgICAgICAgICAgbXcucmVxdWlyZSgndHJlZS5qcycpXG4gICAgICAgICAgICAkLmdldEpTT04odXJsLCBmdW5jdGlvbiAocmVzKXtcbiAgICAgICAgICAgICAgICBzY29wZS50cmVlID0gbmV3IG13LnRyZWUoe1xuICAgICAgICAgICAgICAgICAgICBkYXRhOiByZXMsXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRyZWVFbCxcbiAgICAgICAgICAgICAgICAgICAgc29ydGFibGU6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICBzZWxlY3RhYmxlOiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICBzaW5nbGVTZWxlY3Q6IHRydWVcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBzY29wZS50cmVlLm9uKFwic2VsZWN0aW9uQ2hhbmdlXCIsIGZ1bmN0aW9uKHNlbGVjdGlvbil7XG4gICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHNlbGVjdGlvblswXSlcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHRGaWVsZCAmJiBzZWxlY3Rpb24gJiYgc2VsZWN0aW9uWzBdKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0RmllbGQudmFsdWUgPSBzZWxlY3Rpb25bMF0udGl0bGU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgaWYoc2NvcGUudmFsaWQoKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBpZiAob3B0aW9ucy50YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBfdGFyZ2V0ID0gbXcuY29udHJvbEZpZWxkcy5jaGVja2JveCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLnRhcmdldC5sYWJlbCxcbiAgICAgICAgICAgICAgICAgICAgbmFtZTogJ3RhcmdldCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICB2YXIgaG9sZGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKF9saW5rVGV4dCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKHRyZWVFbCk7XG4gICAgICAgICAgICBob2xkZXIuYXBwZW5kKF90YXJnZXQpO1xuXG5cbiAgICAgICAgICAgIHZhciB0ZXh0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0ZXh0XCJdJyk7XG4gICAgICAgICAgICB2YXIgdGFyZ2V0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0YXJnZXRcIl0nKTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmVzID0gdGhpcy5pc1ZhbGlkKCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLm9rLmRpc2FibGVkID0gIXJlcztcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5pc1ZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCAmJiAhdGV4dEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB2YXIgZm9vdGVyID0gVUlGb3JtQ29udHJvbGxlcnMuZm9vdGVyKCk7XG5cbiAgICAgICAgICAgIHRoaXMuZ2V0VmFsdWUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdmFsLnRleHQgPSB0ZXh0RmllbGQudmFsdWU7XG4gICAgICAgICAgICAgICAgdmFyIGdldFNlbGVjdGVkID0gdGhpcy50cmVlLmdldFNlbGVjdGVkKClbMF07XG4gICAgICAgICAgICAgICAgdmFsLnVybCA9IGdldFNlbGVjdGVkID8gZ2V0U2VsZWN0ZWQudXJsIDogJyc7XG4gICAgICAgICAgICAgICAgdmFsLmRhdGEgPSBnZXRTZWxlY3RlZDtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdmFsLnRhcmdldCA9IHRhcmdldEZpZWxkLmNoZWNrZWQ7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuc2V0VmFsdWUgPSBmdW5jdGlvbiAodmFsKSB7XG4gICAgICAgICAgICAgICAgaWYodGV4dEZpZWxkKSB0ZXh0RmllbGQudmFsdWUgPSB2YWwudGV4dDtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdGFyZ2V0RmllbGQuY2hlY2tlZCA9IHZhbC50YXJnZXQ7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2hhbmdlID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9vbkNoYW5nZS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY29uZmlybSA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNvbmZpcm0gPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX2NvbmZpcm0ucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX2NhbmNlbCA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNhbmNlbCA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY2FuY2VsLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICBtdy5lbWl0dGVyLm9uKFt0ZXh0RmllbGQsIHRhcmdldEZpZWxkXSwgJ2lucHV0JywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLm9rLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NvbmZpcm0uZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihmb290ZXIuY2FuY2VsLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NhbmNlbC5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgZigpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGhvbGRlcik7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGZvb3Rlci5yb290KTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCgpO1xuXG4gICAgICAgICAgICB0aGlzLnJvb3QgPSByb290O1xuICAgICAgICB9LFxuICAgICAgICBmaWxlOiBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICB0ZXh0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdMaW5rIHRleHQnKSxcbiAgICAgICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IG13LmxhbmcoJ1NlbGVjdGVkIHRleHQgZm9yIHRoZSBsaW5rLicpLFxuICAgICAgICAgICAgICAgIH0sXG5cbiAgICAgICAgICAgICAgICB0YXJnZXQ6IHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG13LmxhbmcoJ09wZW4gdGhlIGxpbmsgaW4gYSBuZXcgd2luZG93JylcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGljb246ICdtZGkgbWRpLXBhcGVyY2xpcCcsXG4gICAgICAgICAgICAgICAgdGl0bGU6ICdGaWxlJyxcbiAgICAgICAgICAgICAgICBkYXRhVXJsOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbXcuc2V0dGluZ3MuYXBpX3VybCArICdjb250ZW50L2dldF9hZG1pbl9qc190cmVlX2pzb24nO1xuICAgICAgICAgICAgICAgICAgICB9IGNhdGNoIChlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICBvcHRpb25zID0gIG13Lm9iamVjdC5leHRlbmQodHJ1ZSwge30sIGRlZmF1bHRzLCAob3B0aW9ucyB8fCB7fSkpO1xuICAgICAgICAgICAgdGhpcy5zZXR0aW5ncyA9IG9wdGlvbnM7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0ID09PSB0cnVlKSBvcHRpb25zLnRleHQgPSBkZWZhdWx0cy50ZXh0O1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGFyZ2V0ID09PSB0cnVlKSBvcHRpb25zLnRhcmdldCA9IGRlZmF1bHRzLnRhcmdldDtcblxuICAgICAgICAgICAgdmFyIHJvb3QgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHJvb3QuY2xhc3NOYW1lID0gJ213LXVpLWZvcm0tY29udHJvbGxlci1yb290JztcbiAgICAgICAgICAgIHZhciBfbGlua1RleHQgPSAnJywgX2xpbmtVcmwgPSAnJywgX3RhcmdldCA9ICcnO1xuICAgICAgICAgICAgVUlGb3JtQ29udHJvbGxlcnMuX3RpdGxlKHRoaXMuc2V0dGluZ3MsIHJvb3QpXG4gICAgICAgICAgICB2YXIgdHJlZUVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICB0cmVlRWwuY2xhc3NOYW1lID0gJ2Zvcm0tZ3JvdXAnO1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGV4dCkge1xuICAgICAgICAgICAgICAgIF9saW5rVGV4dCA9IG13LmNvbnRyb2xGaWVsZHMuZmllbGQoe1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogb3B0aW9ucy50ZXh0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBkZXNjcmlwdGlvbjogb3B0aW9ucy50ZXh0LmRlc2NyaXB0aW9uLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGV4dCdcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB1cmwgPSAgdGhpcy5zZXR0aW5ncy5kYXRhVXJsO1xuICAgICAgICAgICAgdXJsID0gdHlwZW9mIHVybCA9PT0gJ2Z1bmN0aW9uJyA/IHVybCgpIDogdXJsO1xuICAgICAgICAgICAgc2NvcGUuZmlsZXBpY2tlciA9IG5ldyBtdy5maWxlUGlja2VyKHtcblxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRyZWVFbCxcbiAgICAgICAgICAgICAgICBuYXY6ICd0YWJzJyxcbiAgICAgICAgICAgICAgICBsYWJlbDogZmFsc2VcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdHJlZUVsLmFwcGVuZChtdy5jb250cm9sRmllbGRzLl9sYWJlbCh7Y29udGVudDogJ1NlbGVjdCBmaWxlJ30pKVxuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgX3RhcmdldCA9IG13LmNvbnRyb2xGaWVsZHMuY2hlY2tib3goe1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogb3B0aW9ucy50YXJnZXQubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd0YXJnZXQnXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgdmFyIGhvbGRlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZChfbGlua1RleHQpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZCh0cmVlRWwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZChfdGFyZ2V0KTtcblxuXG4gICAgICAgICAgICB2YXIgdGV4dEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidGV4dFwiXScpO1xuICAgICAgICAgICAgdmFyIHRhcmdldEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidGFyZ2V0XCJdJyk7XG5cbiAgICAgICAgICAgIHRoaXMudmFsaWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHJlcyA9IHRoaXMuaXNWYWxpZCgpO1xuICAgICAgICAgICAgICAgIGZvb3Rlci5vay5kaXNhYmxlZCA9ICFyZXM7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlcztcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuaXNWYWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBpZih0ZXh0RmllbGQgJiYgIXRleHRGaWVsZC52YWx1ZSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiAhIXRoaXMuZmlsZXBpY2tlci5nZXRWYWx1ZSgpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdmFyIGZvb3RlciA9IFVJRm9ybUNvbnRyb2xsZXJzLmZvb3RlcigpO1xuXG4gICAgICAgICAgICB0aGlzLmdldFZhbHVlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSB7fTtcbiAgICAgICAgICAgICAgICBpZih0ZXh0RmllbGQpIHZhbC50ZXh0ID0gdGV4dEZpZWxkLnZhbHVlO1xuICAgICAgICAgICAgICAgIHZhciB1cmwgPSB0aGlzLmZpbGVwaWNrZXIuZ2V0VmFsdWUoKTtcbiAgICAgICAgICAgICAgICB2YWwudXJsID0gdHlwZW9mIHVybCA9PT0gJ29iamVjdCcgPyAodXJsLnNyYyB8fCB1cmwudXJsKSA6IHVybDtcbiAgICAgICAgICAgICAgICB2YWwuZGF0YSA9ICh1cmwuc3JjIHx8IHVybC51cmwgfHwgbnVsbCk7XG4gICAgICAgICAgICAgICAgaWYodGFyZ2V0RmllbGQpIHZhbC50YXJnZXQgPSB0YXJnZXRGaWVsZC5jaGVja2VkO1xuICAgICAgICAgICAgICAgIHJldHVybiB2YWw7XG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLnNldFZhbHVlID0gZnVuY3Rpb24gKHZhbCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdGV4dEZpZWxkLnZhbHVlID0gdmFsLnRleHQ7XG4gICAgICAgICAgICAgICAgaWYodGFyZ2V0RmllbGQpIHRhcmdldEZpZWxkLmNoZWNrZWQgPSAhIXZhbC50YXJnZXQ7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2hhbmdlID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9vbkNoYW5nZS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY29uZmlybSA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNvbmZpcm0gPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX2NvbmZpcm0ucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX2NhbmNlbCA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNhbmNlbCA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY2FuY2VsLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG5cblxuICAgICAgICAgICAgJCh0aGlzLmZpbGVwaWNrZXIpLm9uKCdSZXN1bHQnLCBmdW5jdGlvbiAoZSwgcmVzKSB7XG4gICAgICAgICAgICAgICAgaWYoc2NvcGUudmFsaWQoKSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5fb25DaGFuZ2UuZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgICAgICBmKHNjb3BlLmdldFZhbHVlKCkpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oW3RleHRGaWVsZCwgdGFyZ2V0RmllbGRdLCAnaW5wdXQnLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgaWYoc2NvcGUudmFsaWQoKSkge1xuICAgICAgICAgICAgICAgICAgICBzY29wZS5fb25DaGFuZ2UuZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgICAgICBmKHNjb3BlLmdldFZhbHVlKCkpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihmb290ZXIub2ssICdjbGljaycsIGZ1bmN0aW9uIChlKXtcbiAgICAgICAgICAgICAgICBzY29wZS5fY29uZmlybS5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBtdy5lbWl0dGVyLm9uKGZvb3Rlci5jYW5jZWwsICdjbGljaycsIGZ1bmN0aW9uIChlKXtcbiAgICAgICAgICAgICAgICBzY29wZS5fY2FuY2VsLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICBmKCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgcm9vdC5hcHBlbmQoaG9sZGVyKTtcblxuICAgICAgICAgICAgcm9vdC5hcHBlbmQoZm9vdGVyLnJvb3QpO1xuXG4gICAgICAgICAgICB0aGlzLnZhbGlkKCk7XG5cbiAgICAgICAgICAgIHRoaXMucm9vdCA9IHJvb3Q7XG4gICAgICAgIH0sXG5cbiAgICAgICAgdXJsOiBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIHZhciBkZWZhdWx0cyA9IHtcbiAgICAgICAgICAgICAgICB0ZXh0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdMaW5rIHRleHQnKSxcbiAgICAgICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IG13LmxhbmcoJ1NlbGVjdGVkIHRleHQgZm9yIHRoZSBsaW5rLicpLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgbGluazoge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogbXcubGFuZygnV2Vic2l0ZSBVUkwnKSxcbiAgICAgICAgICAgICAgICAgICAgZGVzY3JpcHRpb246IG13LmxhbmcoJ1R5cGUgdGhlIHdlYnNpdGUgVVJMIHRvIGxpbmsgaXQnKSxcbiAgICAgICAgICAgICAgICAgICAgcGxhY2Vob2xkZXI6IFwiaHR0cDovL1wiLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdGFyZ2V0OiB7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBtdy5sYW5nKCdPcGVuIHRoZSBsaW5rIGluIGEgbmV3IHdpbmRvdycpXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBpY29uOiAnbWRpIG1kaS13ZWInLFxuICAgICAgICAgICAgICAgIHRpdGxlOiAnVVJMJ1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIG9wdGlvbnMgPSAgbXcub2JqZWN0LmV4dGVuZCh0cnVlLCB7fSwgZGVmYXVsdHMsIChvcHRpb25zIHx8IHt9KSk7XG4gICAgICAgICAgICB0aGlzLnNldHRpbmdzID0gb3B0aW9ucztcbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRleHQgPT09IHRydWUpIG9wdGlvbnMudGV4dCA9IGRlZmF1bHRzLnRleHQ7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy5saW5rID09PSB0cnVlKSBvcHRpb25zLmxpbmsgPSBkZWZhdWx0cy5saW5rO1xuICAgICAgICAgICAgaWYgKG9wdGlvbnMudGFyZ2V0ID09PSB0cnVlKSBvcHRpb25zLnRhcmdldCA9IGRlZmF1bHRzLnRhcmdldDtcblxuICAgICAgICAgICAgdmFyIHJvb3QgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIHJvb3QuY2xhc3NOYW1lID0gJ213LXVpLWZvcm0tY29udHJvbGxlci1yb290JztcbiAgICAgICAgICAgIHZhciBfbGlua1RleHQgPSAnJywgX2xpbmtVcmwgPSAnJywgX3RhcmdldCA9ICcnO1xuICAgICAgICAgICAgVUlGb3JtQ29udHJvbGxlcnMuX3RpdGxlKHRoaXMuc2V0dGluZ3MsIHJvb3QpXG4gICAgICAgICAgICBpZiAob3B0aW9ucy50ZXh0KSB7XG4gICAgICAgICAgICAgICAgX2xpbmtUZXh0ID0gbXcuY29udHJvbEZpZWxkcy5maWVsZCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLnRleHQubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIGRlc2NyaXB0aW9uOiBvcHRpb25zLnRleHQuZGVzY3JpcHRpb24sXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKG9wdGlvbnMubGluaykge1xuICAgICAgICAgICAgICAgIF9saW5rVXJsID0gbXcuY29udHJvbEZpZWxkcy5maWVsZCh7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiBvcHRpb25zLmxpbmsubGFiZWwsXG4gICAgICAgICAgICAgICAgICAgIGRlc2NyaXB0aW9uOiBvcHRpb25zLmxpbmsuZGVzY3JpcHRpb24sXG4gICAgICAgICAgICAgICAgICAgIHBsYWNlaG9sZGVyOiBvcHRpb25zLmxpbmsucGxhY2Vob2xkZXIsXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICd1cmwnXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChvcHRpb25zLnRhcmdldCkge1xuICAgICAgICAgICAgICAgIF90YXJnZXQgPSBtdy5jb250cm9sRmllbGRzLmNoZWNrYm94KHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6IG9wdGlvbnMudGFyZ2V0LmxhYmVsLFxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAndGFyZ2V0J1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgIHZhciBob2xkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtUZXh0KTtcbiAgICAgICAgICAgIGhvbGRlci5hcHBlbmQoX2xpbmtVcmwpO1xuICAgICAgICAgICAgaG9sZGVyLmFwcGVuZChfdGFyZ2V0KTtcblxuXG4gICAgICAgICAgICB2YXIgdGV4dEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidGV4dFwiXScpO1xuICAgICAgICAgICAgdmFyIHVybEZpZWxkID0gaG9sZGVyLnF1ZXJ5U2VsZWN0b3IoJ1tuYW1lPVwidXJsXCJdJyk7XG4gICAgICAgICAgICB2YXIgdGFyZ2V0RmllbGQgPSBob2xkZXIucXVlcnlTZWxlY3RvcignW25hbWU9XCJ0YXJnZXRcIl0nKTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgcmVzID0gdGhpcy5pc1ZhbGlkKCk7XG4gICAgICAgICAgICAgICAgZm9vdGVyLm9rLmRpc2FibGVkID0gIXJlcztcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5pc1ZhbGlkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCAmJiAhdGV4dEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYodXJsRmllbGQgJiYgIXVybEZpZWxkLnZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciBmb290ZXIgPSBVSUZvcm1Db250cm9sbGVycy5mb290ZXIoKTtcblxuICAgICAgICAgICAgdGhpcy5zZXRWYWx1ZSA9IGZ1bmN0aW9uICh2YWwpIHtcbiAgICAgICAgICAgICAgICBpZih0ZXh0RmllbGQpIHRleHRGaWVsZC52YWx1ZSA9IHZhbC50ZXh0O1xuICAgICAgICAgICAgICAgIGlmKHVybEZpZWxkKSB1cmxGaWVsZC52YWx1ZSA9IHZhbC51cmwgIDtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdGFyZ2V0RmllbGQuY2hlY2tlZCA9IHZhbC50YXJnZXQgIDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuZ2V0VmFsdWUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHt9O1xuICAgICAgICAgICAgICAgIGlmKHRleHRGaWVsZCkgdmFsLnRleHQgPSB0ZXh0RmllbGQudmFsdWU7XG4gICAgICAgICAgICAgICAgaWYodXJsRmllbGQpIHZhbC51cmwgPSB1cmxGaWVsZC52YWx1ZTtcbiAgICAgICAgICAgICAgICBpZih0YXJnZXRGaWVsZCkgdmFsLnRhcmdldCA9IHRhcmdldEZpZWxkLmNoZWNrZWQ7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX29uQ2hhbmdlID0gW107XG4gICAgICAgICAgICB0aGlzLm9uQ2hhbmdlID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9vbkNoYW5nZS5wdXNoKGMpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgdGhpcy5fY29uZmlybSA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNvbmZpcm0gPSBmdW5jdGlvbiAoYykge1xuICAgICAgICAgICAgICAgIHRoaXMuX2NvbmZpcm0ucHVzaChjKTtcbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHRoaXMuX2NhbmNlbCA9IFtdO1xuICAgICAgICAgICAgdGhpcy5vbkNhbmNlbCA9IGZ1bmN0aW9uIChjKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5fY2FuY2VsLnB1c2goYyk7XG4gICAgICAgICAgICB9O1xuXG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oW3RleHRGaWVsZCwgdXJsRmllbGQsIHRhcmdldEZpZWxkXSwgJ2lucHV0JywgZnVuY3Rpb24gKGUpe1xuICAgICAgICAgICAgICAgIGlmKHNjb3BlLnZhbGlkKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuX29uQ2hhbmdlLmZvckVhY2goZnVuY3Rpb24gKGYpe1xuICAgICAgICAgICAgICAgICAgICAgICAgZihzY29wZS5nZXRWYWx1ZSgpKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG13LmVtaXR0ZXIub24oZm9vdGVyLm9rLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NvbmZpcm0uZm9yRWFjaChmdW5jdGlvbiAoZil7XG4gICAgICAgICAgICAgICAgICAgIGYoc2NvcGUuZ2V0VmFsdWUoKSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgbXcuZW1pdHRlci5vbihmb290ZXIuY2FuY2VsLCAnY2xpY2snLCBmdW5jdGlvbiAoZSl7XG4gICAgICAgICAgICAgICAgc2NvcGUuX2NhbmNlbC5mb3JFYWNoKGZ1bmN0aW9uIChmKXtcbiAgICAgICAgICAgICAgICAgICAgZigpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGhvbGRlcik7XG5cbiAgICAgICAgICAgIHJvb3QuYXBwZW5kKGZvb3Rlci5yb290KTtcblxuICAgICAgICAgICAgdGhpcy52YWxpZCgpO1xuXG4gICAgICAgICAgICB0aGlzLnJvb3QgPSByb290O1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIG13LlVJRm9ybUNvbnRyb2xsZXJzID0gVUlGb3JtQ29udHJvbGxlcnM7XG59KSgpO1xuIiwiXG52YXIgZ2V0RmllbGRWYWx1ZSA9IGZ1bmN0aW9uKGEpe1xuICByZXR1cm4gdHlwZW9mIGEgPT09ICdzdHJpbmcnID8gYSA6ICggdHlwZW9mIGEgPT09ICdvYmplY3QnICYmIGEudGFnTmFtZSAhPT0gdW5kZWZpbmVkID8gYS52YWx1ZSA6IG51bGwpO1xufTtcblxuXG5cblxubXcuZm9ybSA9IHtcbiAgdHlwZU51bWJlcjpmdW5jdGlvbihlbCl7XG4gICAgZWwudmFsdWUgPSBlbC52YWx1ZS5yZXBsYWNlKC9bXjAtOVxcLixdL2csJycpO1xuICB9LFxuICBmaXhQcmljZTpmdW5jdGlvbihlbCl7XG4gICAgZWwudmFsdWUgPSBlbC52YWx1ZS5yZXBsYWNlKC8sL2csJycpO1xuICAgIHZhciBhcnIgPSBlbC52YWx1ZS5zcGxpdCgnLicpO1xuICAgIHZhciBsZW4gPSBhcnIubGVuZ3RoO1xuICAgIGlmKGxlbj4xKXtcbiAgICAgIGlmKGFycltsZW4tMV09PT0nJyl7XG4gICAgICAgICAgYXJyW2xlbi0xXSA9ICcuMDAnO1xuICAgICAgfVxuICAgICAgZWxzZXtcbiAgICAgICAgIGFycltsZW4tMV0gPSAnLicgKyBhcnJbbGVuLTFdO1xuICAgICAgfVxuICAgICAgZWwudmFsdWUgPSBhcnIuam9pbignJyk7XG4gICAgfVxuICB9LFxuICBwb3N0OiBmdW5jdGlvbihzZWxlY3RvciwgdXJsX3RvX3Bvc3QsIGNhbGxiYWNrLCBpZ25vcmVub3Bvc3QsIGNhbGxiYWNrX2Vycm9yLCBjYWxsYmFja191c2VyX2NhbmNlbCwgYmVmb3JlX3NlbmQpe1xuICAgIG13LnNlc3Npb24uY2hlY2tQYXVzZSA9IHRydWU7XG4gICAgaWYoc2VsZWN0b3IuY29uc3RydWN0b3IgPT09IHt9LmNvbnN0cnVjdG9yKXtcbiAgICAgIHJldHVybiBtdy5mb3JtLl9wb3N0KHNlbGVjdG9yKTtcbiAgICB9XG5cbiAgICBjYWxsYmFja19lcnJvciA9IGNhbGxiYWNrX2Vycm9yIHx8IGZhbHNlO1xuICAgIGlnbm9yZW5vcG9zdCA9IGlnbm9yZW5vcG9zdCB8fCBmYWxzZTtcbiAgICB2YXIgaXNfZm9ybV92YWxpZCA9IG13LmZvcm0udmFsaWRhdGUuaW5pdChzZWxlY3Rvcik7XG5cblx0aWYoIXVybF90b19wb3N0KXtcblxuXHRcdHVybF90b19wb3N0ID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyAnYXBpL3Bvc3RfZm9ybSc7XG5cblx0fVxuXG4gLy8gdmFyIGlzX2Zvcm1fdmFsaWQgPSB0cnVlO1xuXG5cbiAgICBpZihpc19mb3JtX3ZhbGlkKXtcblxuICAgICAgICB2YXIgZm9ybSA9IG13LiQoc2VsZWN0b3IpWzBdO1xuICAgICAgICB2YXIgd2hlbiA9IGZvcm0uJGJlZm9yZXBvc3QgPyBmb3JtLiRiZWZvcmVwb3N0IDogZnVuY3Rpb24gKCkge307XG4gICAgICAgICQud2hlbih3aGVuKCkpLnRoZW4oZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgb2JqID0gbXcuZm9ybS5zZXJpYWxpemUoc2VsZWN0b3IsIGlnbm9yZW5vcG9zdCk7XG4gICAgICAgICAgICAgICAgdmFyIHhociA9ICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHVybDogdXJsX3RvX3Bvc3QsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IGJlZm9yZV9zZW5kID8gYmVmb3JlX3NlbmQob2JqKSA6IG9iaixcbiAgICAgICAgICAgICAgICAgICAgbWV0aG9kOiAncG9zdCcsXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcuc2Vzc2lvbi5jaGVja1BhdXNlID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZih0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZGF0YSwgbXcuJChzZWxlY3RvcilbMF0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgb25FeHRlcm5hbERhdGFEaWFsb2dDbG9zZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZihjYWxsYmFja191c2VyX2NhbmNlbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrX3VzZXJfY2FuY2VsLmNhbGwoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIHhoci5mYWlsKGZ1bmN0aW9uKGEsYikge1xuICAgICAgICAgICAgICAgICAgICBtdy5zZXNzaW9uLmNoZWNrUGF1c2UgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgaWYodHlwZW9mIGNhbGxiYWNrX2Vycm9yID09PSAnZnVuY3Rpb24nKXtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrX2Vycm9yLmNhbGwoYSxiKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSwgNzgpXG4gICAgICAgIH0pO1xuXG5cbiAgICB9XG5cdHJldHVybiBmYWxzZTtcbiAgfSxcbiAgX3Bvc3Q6ZnVuY3Rpb24ob2JqKXtcbiAgICBtdy5mb3JtLnBvc3Qob2JqLnNlbGVjdG9yLCBvYmoudXJsLCBvYmouZG9uZSwgb2JqLmlnbm9yZW5vcG9zdCwgb2JqLmVycm9yLCBvYmouZXJyb3IpO1xuICB9LFxuICB2YWxpZGF0ZTp7XG4gICAgY2hlY2tib3g6IGZ1bmN0aW9uKG9iail7XG4gICAgICAgIHJldHVybiBvYmouY2hlY2tlZCA9PT0gdHJ1ZTtcbiAgICB9LFxuICAgIGZpZWxkOmZ1bmN0aW9uKG9iail7XG5cdFx0cmV0dXJuICEhZ2V0RmllbGRWYWx1ZShvYmopLnJlcGxhY2UoL1xccy9nLCAnJyk7XG4gICAgfSxcbiAgICBlbWFpbDpmdW5jdGlvbihvYmope1xuICAgICAgICB2YXIgcmVnZXhtYWlsID0gL15cXHcrKFtcXC4tXT9cXHcrKSpAXFx3KyhbXFwuLV0/XFx3KykqKFxcLlxcd3syLDZ9KSskLztcbiAgICAgICAgcmV0dXJuIHJlZ2V4bWFpbC50ZXN0KGdldEZpZWxkVmFsdWUob2JqKSk7XG4gICAgfSxcbiAgICB1cmw6ZnVuY3Rpb24ob2JqKXtcblx0ICAvKiB2YXIgcnVybCA9LyhcXGIoaHR0cHM/fGZ0cHxmaWxlKTpcXC9cXC9bLUEtWjAtOSsmQCNcXC8lPz1+X3whOiwuO10qWy1BLVowLTkrJkAjXFwvJT1+X3xdKS9pZzsgKi9cbiAgICAgICB2YXIgcnVybCA9IC9eKChodHRwcz98ZnRwKTpcXC9cXC8pPygoKChbYS16XXxcXGR8LXxcXC58X3x+fFtcXHUwMEEwLVxcdUQ3RkZcXHVGOTAwLVxcdUZEQ0ZcXHVGREYwLVxcdUZGRUZdKXwoJVtcXGRhLWZdezJ9KXxbIVxcJCYnXFwoXFwpXFwqXFwrLDs9XXw6KSpAKT8oKChcXGR8WzEtOV1cXGR8MVxcZFxcZHwyWzAtNF1cXGR8MjVbMC01XSlcXC4oXFxkfFsxLTldXFxkfDFcXGRcXGR8MlswLTRdXFxkfDI1WzAtNV0pXFwuKFxcZHxbMS05XVxcZHwxXFxkXFxkfDJbMC00XVxcZHwyNVswLTVdKVxcLihcXGR8WzEtOV1cXGR8MVxcZFxcZHwyWzAtNF1cXGR8MjVbMC01XSkpfCgoKFthLXpdfFxcZHxbXFx1MDBBMC1cXHVEN0ZGXFx1RjkwMC1cXHVGRENGXFx1RkRGMC1cXHVGRkVGXSl8KChbYS16XXxcXGR8W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pKFthLXpdfFxcZHwtfFxcLnxffH58W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pKihbYS16XXxcXGR8W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pKSlcXC4pKygoW2Etel18W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pfCgoW2Etel18W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pKFthLXpdfFxcZHwtfFxcLnxffH58W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pKihbYS16XXxbXFx1MDBBMC1cXHVEN0ZGXFx1RjkwMC1cXHVGRENGXFx1RkRGMC1cXHVGRkVGXSkpKVxcLj8pKDpcXGQqKT8pKFxcLygoKFthLXpdfFxcZHwtfFxcLnxffH58W1xcdTAwQTAtXFx1RDdGRlxcdUY5MDAtXFx1RkRDRlxcdUZERjAtXFx1RkZFRl0pfCglW1xcZGEtZl17Mn0pfFshXFwkJidcXChcXClcXCpcXCssOz1dfDp8QCkrKFxcLygoW2Etel18XFxkfC18XFwufF98fnxbXFx1MDBBMC1cXHVEN0ZGXFx1RjkwMC1cXHVGRENGXFx1RkRGMC1cXHVGRkVGXSl8KCVbXFxkYS1mXXsyfSl8WyFcXCQmJ1xcKFxcKVxcKlxcKyw7PV18OnxAKSopKik/KT8oXFw/KCgoW2Etel18XFxkfC18XFwufF98fnxbXFx1MDBBMC1cXHVEN0ZGXFx1RjkwMC1cXHVGRENGXFx1RkRGMC1cXHVGRkVGXSl8KCVbXFxkYS1mXXsyfSl8WyFcXCQmJ1xcKFxcKVxcKlxcKyw7PV18OnxAKXxbXFx1RTAwMC1cXHVGOEZGXXxcXC98XFw/KSopPyhcXCMoKChbYS16XXxcXGR8LXxcXC58X3x+fFtcXHUwMEEwLVxcdUQ3RkZcXHVGOTAwLVxcdUZEQ0ZcXHVGREYwLVxcdUZGRUZdKXwoJVtcXGRhLWZdezJ9KXxbIVxcJCYnXFwoXFwpXFwqXFwrLDs9XXw6fEApfFxcL3xcXD8pKik/JC87XG4gICAgICAgcmV0dXJuIHJ1cmwudGVzdChnZXRGaWVsZFZhbHVlKGdldEZpZWxkVmFsdWUob2JqKSkpO1xuICAgIH0sXG4gICAgcmFkaW86ZnVuY3Rpb24ob2JqbmFtZSl7XG4gICAgICAgIHZhciByYWRpb3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5TmFtZShvYmpuYW1lKSwgaSA9IDAsIGxlbiA9IHJhZGlvcy5sZW5ndGg7XG4gICAgICAgIHRoaXNfcmFkaW9fdmFsaWQgPSBmYWxzZTtcbiAgICAgICAgZm9yKCA7IGkgPCBsZW4gOyBpKyspe1xuICAgICAgICAgICAgaWYocmFkaW9zW2ldLmNoZWNrZWQpe1xuICAgICAgICAgICAgICAgIHRoaXNfcmFkaW9fdmFsaWQgPSB0cnVlO1xuICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHZhciBwYXJlbnQgPSBtdy4kKGRvY3VtZW50LmdldEVsZW1lbnRzQnlOYW1lKG9iam5hbWUpWzBdLnBhcmVudE5vZGUpO1xuICAgICAgICBpZih0aGlzX3JhZGlvX3ZhbGlkKXtcbiAgICAgICAgICAgcGFyZW50LnJlbW92ZUNsYXNzKFwiZXJyb3JcIik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgcGFyZW50LmFkZENsYXNzKFwiZXJyb3JcIik7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRoaXNfcmFkaW9fdmFsaWQ7XG4gICAgfSxcbiAgICBpbWFnZV91cmw6ZnVuY3Rpb24odXJsLCB2YWxpZCwgaW52YWxpZCl7XG4gICAgICAgIHVybCA9IHVybC5yZXBsYWNlKC9cXHMvZ2ksJycpO1xuICAgICAgICBpZih1cmwubGVuZ3RoPDYpe1xuICAgICAgICAgICAgdHlwZW9mIGludmFsaWQgPT0nZnVuY3Rpb24nPyBpbnZhbGlkLmNhbGwodXJsKSA6ICcnO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGVsc2V7XG4gICAgICAgICAgaWYoIXVybC5jb250YWlucygnaHR0cCcpKXt1cmwgPSAnaHR0cDovLycrdXJsfVxuICAgICAgICAgIGlmKCF3aW5kb3cuSW1nVGVzdGVyKXtcbiAgICAgICAgICAgICAgd2luZG93LkltZ1Rlc3RlciA9IG5ldyBJbWFnZSgpO1xuICAgICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHdpbmRvdy5JbWdUZXN0ZXIpO1xuICAgICAgICAgICAgICB3aW5kb3cuSW1nVGVzdGVyLmNsYXNzTmFtZSA9ICdzZW1pX2hpZGRlbic7XG4gICAgICAgICAgICAgIHdpbmRvdy5JbWdUZXN0ZXIub25sb2FkID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB0eXBlb2YgdmFsaWQgPT0nZnVuY3Rpb24nPyB2YWxpZC5jYWxsKHVybCkgOiAnJztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB3aW5kb3cuSW1nVGVzdGVyLm9uZXJyb3IgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHR5cGVvZiBpbnZhbGlkID09J2Z1bmN0aW9uJz8gaW52YWxpZC5jYWxsKHVybCkgOiAnJztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICB3aW5kb3cuSW1nVGVzdGVyLnNyYyA9IHVybDtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgcHJvY2VlZDp7XG4gICAgICBjaGVja2JveDpmdW5jdGlvbihvYmope1xuICAgICAgICBpZihtdy5mb3JtLnZhbGlkYXRlLmNoZWNrYm94KG9iaikpe1xuICAgICAgICAgICAgbXcuJChvYmopLnBhcmVudHMoJy5maWVsZCcpLnJlbW92ZUNsYXNzKFwiZXJyb3JcIik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgIG13LiQob2JqKS5wYXJlbnRzKCcuZmllbGQnKS5hZGRDbGFzcyhcImVycm9yXCIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgZmllbGQ6ZnVuY3Rpb24ob2JqKXtcbiAgICAgICAgaWYobXcuZm9ybS52YWxpZGF0ZS5maWVsZChvYmopKXtcbiAgICAgICAgICAgbXcuJChvYmopLnBhcmVudHMoJy5maWVsZCcpLnJlbW92ZUNsYXNzKFwiZXJyb3JcIik7XG4gICAgICAgICB9XG4gICAgICAgICBlbHNle1xuICAgICAgICAgICBtdy4kKG9iaikucGFyZW50cygnLmZpZWxkJykuYWRkQ2xhc3MoXCJlcnJvclwiKTtcbiAgICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBlbWFpbDpmdW5jdGlvbihvYmope1xuICAgICAgICBpZihtdy5mb3JtLnZhbGlkYXRlLmVtYWlsKG9iaikpe1xuICAgICAgICAgICBtdy4kKG9iaikucGFyZW50cygnLmZpZWxkJykucmVtb3ZlQ2xhc3MoXCJlcnJvclwiKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICBtdy4kKG9iaikucGFyZW50cygnLmZpZWxkJykuYWRkQ2xhc3MoXCJlcnJvclwiKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sXG4gICAgY2hlY2tGaWVsZHM6ZnVuY3Rpb24oZm9ybSl7XG4gICAgICAgIG13LiQoZm9ybSkuZmluZChcIi5yZXF1aXJlZCxbcmVxdWlyZWRdXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICB2YXIgdHlwZSA9IG13LiQodGhpcykuYXR0cihcInR5cGVcIik7XG4gICAgICAgICAgaWYodHlwZT09J2NoZWNrYm94Jyl7XG4gICAgICAgICAgICAgbXcuZm9ybS52YWxpZGF0ZS5wcm9jZWVkLmNoZWNrYm94KHRoaXMpO1xuICAgICAgICAgIH1cbiAgICAgICAgICBlbHNlIGlmKHR5cGU9PSdyYWRpbycpe1xuICAgICAgICAgICAgIG13LmZvcm0udmFsaWRhdGUucmFkaW8odGhpcy5uYW1lKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgZWxzZXtcbiAgICAgICAgICAgICBtdy5mb3JtLnZhbGlkYXRlLnByb2NlZWQuZmllbGQodGhpcyk7XG4gICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChmb3JtKS5maW5kKFwiLnJlcXVpcmVkLWVtYWlsXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIG13LmZvcm0udmFsaWRhdGUucHJvY2VlZC5lbWFpbCh0aGlzKTtcbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBpbml0OmZ1bmN0aW9uKG9iail7XG4gICAgICAgIG13LmZvcm0udmFsaWRhdGUuY2hlY2tGaWVsZHMob2JqKTtcbiAgICAgICAgaWYoJChvYmopLmZpbmQoXCIuZXJyb3JcIikubGVuZ3RoPjApe1xuICAgICAgICAgICAgbXcuJChvYmopLmFkZENsYXNzKFwiZXJyb3Igc3VibWl0ZWRcIik7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgbXcuJChvYmopLnJlbW92ZUNsYXNzKFwiZXJyb3JcIik7XG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgIH1cbiAgfSxcbiAgc2VyaWFsaXplIDogZnVuY3Rpb24oaWQsIGlnbm9yZW5vcG9zdCl7XG4gICAgaWdub3Jlbm9wb3N0ID0gaWdub3Jlbm9wb3N0IHx8IGZhbHNlO1xuICAgIHJldHVybiBtdy5zZXJpYWxpemVGaWVsZHMoaWQsIGlnbm9yZW5vcG9zdCk7XG4gIH1cbn1cblxuXG5tdy5wb3N0Rm9ybSA9IGZ1bmN0aW9uKG8pe1xuICByZXR1cm4gbXcuZm9ybS5fcG9zdChvKTtcbn1cblxuXG5cblxuXG5cblxuXG5cblxuXG5cblxuIiwibXcudG9vbHMucHJvZ3Jlc3NEZWZhdWx0cyA9IHtcbiAgICBza2luOiAnbXctdWktcHJvZ3Jlc3MnLFxuICAgIGFjdGlvbjogbXcubXNnLmxvYWRpbmcgKyAnLi4uJyxcbiAgICBwcm9ncmVzczogMFxufTtcblxubXcudG9vbHMucHJvZ3Jlc3MgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgaWYgKHR5cGVvZiBvYmouZWxlbWVudCA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgb2JqLmVsZW1lbnQgPSBtdy4kKG9iai5lbGVtZW50KVswXTtcbiAgICB9XG4gICAgaWYgKG9iai5lbGVtZW50ID09PSBudWxsIHx8ICFvYmouZWxlbWVudCkgcmV0dXJuIGZhbHNlO1xuICAgIGlmIChvYmouZWxlbWVudC5xdWVyeVNlbGVjdG9yKCcubXctdWktcHJvZ3Jlc3MtYmFyJykpIHtcbiAgICAgICAgb2JqLmVsZW1lbnQucHJvZ3Jlc3NPcHRpb25zLnNob3coKVxuICAgICAgICByZXR1cm4gb2JqLmVsZW1lbnQucHJvZ3Jlc3NPcHRpb25zO1xuICAgIH1cbiAgICBvYmogPSAkLmV4dGVuZCh7fSwgbXcudG9vbHMucHJvZ3Jlc3NEZWZhdWx0cywgb2JqKTtcbiAgICBpZihvYmoucHJvZ3Jlc3MgPiAxMDAgKSB7XG4gICAgICAgIG9iai5wcm9ncmVzcyA9IDEwMDtcbiAgICB9XG4gICAgaWYob2JqLnByb2dyZXNzIDwgMCApIHtcbiAgICAgICAgb2JqLnByb2dyZXNzID0gMDtcbiAgICB9XG4gICAgdmFyIHByb2dyZXNzID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgIHByb2dyZXNzLmNsYXNzTmFtZSA9IG9iai5za2luO1xuICAgIHByb2dyZXNzLmlubmVySFRNTCA9ICc8ZGl2IGNsYXNzPVwibXctdWktcHJvZ3Jlc3MtYmFyXCIgc3R5bGU9XCJ3aWR0aDogJyArIG9iai5wcm9ncmVzcyArICclO1wiPjwvZGl2PjxkaXYgY2xhc3M9XCJtdy11aS1wcm9ncmVzcy1pbmZvXCI+JyArIG9iai5hY3Rpb24gKyAnPC9kaXY+PHNwYW4gY2xhc3M9XCJtdy11aS1wcm9ncmVzcy1wZXJjZW50XCI+JytvYmoucHJvZ3Jlc3MrJyU8L3NwYW4+JztcbiAgICBwcm9ncmVzcy5wcm9ncmVzc0luZm8gPSBvYmo7XG4gICAgdmFyIG9wdGlvbnMgPSB7XG4gICAgICAgIHByb2dyZXNzOiBwcm9ncmVzcyxcbiAgICAgICAgc2hvdzogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5wcm9ncmVzcy5zdHlsZS5kaXNwbGF5ID0gJyc7XG4gICAgICAgIH0sXG4gICAgICAgIGhpZGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMucHJvZ3Jlc3Muc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgfSxcbiAgICAgICAgcmVtb3ZlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBwcm9ncmVzcy5wcm9ncmVzc0luZm8uZWxlbWVudC5wcm9ncmVzc09wdGlvbnMgPSB1bmRlZmluZWQ7XG4gICAgICAgICAgICBtdy4kKHRoaXMucHJvZ3Jlc3MpLnJlbW92ZSgpO1xuICAgICAgICB9LFxuICAgICAgICBzZXQ6IGZ1bmN0aW9uICh2LCBhY3Rpb24pIHtcbiAgICAgICAgICAgIGlmICh2ID4gMTAwKSB7XG4gICAgICAgICAgICAgICAgdiA9IDEwMDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICh2IDwgMCkge1xuICAgICAgICAgICAgICAgIHYgPSAwO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgYWN0aW9uID0gYWN0aW9uIHx8IHRoaXMucHJvZ3Jlc3MucHJvZ3Jlc3NJbmZvLmFjdGlvbjtcbiAgICAgICAgICAgIG13LiQoJy5tdy11aS1wcm9ncmVzcy1iYXInLCB0aGlzLnByb2dyZXNzKS5jc3MoJ3dpZHRoJywgdiArICclJyk7XG4gICAgICAgICAgICBtdy4kKCcubXctdWktcHJvZ3Jlc3MtcGVyY2VudCcsIHRoaXMucHJvZ3Jlc3MpLmh0bWwodiArICclJyk7XG4gICAgICAgICAgICBwcm9ncmVzcy5wcm9ncmVzc0luZm8uZWxlbWVudC5wcm9ncmVzc09wdGlvbnMuc2hvdygpO1xuICAgICAgICB9XG4gICAgfTtcbiAgICBwcm9ncmVzcy5wcm9ncmVzc09wdGlvbnMgPSBvYmouZWxlbWVudC5wcm9ncmVzc09wdGlvbnMgPSBvcHRpb25zO1xuICAgIG9iai5lbGVtZW50LmFwcGVuZENoaWxkKHByb2dyZXNzKTtcbiAgICByZXR1cm4gb3B0aW9ucztcbn07XG5cbm13LnRvb2xzLmxvYWRpbmcgPSBmdW5jdGlvbiAoZWxlbWVudCwgcHJvZ3Jlc3MsIHNwZWVkKSB7XG4gICAgLypcblxuICAgICBwcm9ncmVzczpudW1iZXIgMCAtIDEwMCxcbiAgICAgc3BlZWQ6c3RyaW5nLCAtPiAnc2xvdycsICdub3JtYWwsICdmYXN0J1xuXG4gICAgIG13LnRvb2xzLmxvYWRpbmcodHJ1ZSkgLT4gc2xvd2x5IGFuaW1hdGVzIHRvIDk1JSBvbiBib2R5XG4gICAgIG13LnRvb2xzLmxvYWRpbmcoZmFsc2UpIC0+IGZhc3QgYW5pbWF0ZXMgdG8gMTAwJSBvbiBib2R5XG5cbiAgICAgKi9cbiAgICBmdW5jdGlvbiBzZXQoZWwsIHByb2dyZXNzLCBzcGVlZCkge1xuICAgICAgICBzcGVlZCA9IHNwZWVkIHx8ICdub3JtYWwnO1xuICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhlbCwgJ213LXByb2dyZXNzLXNsb3cnKTtcbiAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoZWwsICdtdy1wcm9ncmVzcy1ub3JtYWwnKTtcbiAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MoZWwsICdtdy1wcm9ncmVzcy1mYXN0Jyk7XG4gICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGVsLCAnbXctcHJvZ3Jlc3MtJyArIHNwZWVkKTtcbiAgICAgICAgZWxlbWVudC5fX2xvYWRpbmdUaW1lID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBlbC5xdWVyeVNlbGVjdG9yKCcubXctcHJvZ3Jlc3MtaW5kZXgnKS5zdHlsZS53aWR0aCA9IHByb2dyZXNzICsgJyUnO1xuICAgICAgICB9LCAxMClcblxuICAgIH1cblxuXG4gICAgaWYgKHR5cGVvZiBlbGVtZW50ID09PSAnYm9vbGVhbicpIHtcbiAgICAgICAgcHJvZ3Jlc3MgPSAhIWVsZW1lbnQ7XG4gICAgICAgIGVsZW1lbnQgPSBtd2QuYm9keTtcbiAgICB9XG4gICAgaWYgKHR5cGVvZiBlbGVtZW50ID09PSAnbnVtYmVyJykge1xuICAgICAgICBwcm9ncmVzcyA9IGVsZW1lbnQ7XG4gICAgICAgIGVsZW1lbnQgPSBtd2QuYm9keTtcbiAgICB9XG4gICAgaWYgKGVsZW1lbnQgPT09IGRvY3VtZW50IHx8IGVsZW1lbnQgPT09IG13ZC5kb2N1bWVudEVsZW1lbnQpIHtcbiAgICAgICAgZWxlbWVudCA9IG13ZC5ib2R5O1xuICAgIH1cbiAgICBlbGVtZW50ID0gbXcuJChlbGVtZW50KVswXVxuICAgIGlmIChlbGVtZW50ID09PSBudWxsIHx8ICFlbGVtZW50KSByZXR1cm4gZmFsc2U7XG4gICAgaWYgKGVsZW1lbnQuX19sb2FkaW5nVGltZSkge1xuICAgICAgICBjbGVhclRpbWVvdXQoZWxlbWVudC5fX2xvYWRpbmdUaW1lKVxuICAgIH1cbiAgICBtdy5yZXF1aXJlKCdjc3NfcGFyc2VyLmpzJylcblxuICAgIHZhciBpc0xvYWRpbmcgPSBtdy50b29scy5oYXNDbGFzcyhlbGVtZW50LCAnbXctbG9hZGluZycpO1xuICAgIHZhciBlbCA9IGVsZW1lbnQucXVlcnlTZWxlY3RvcignLm13LXByb2dyZXNzJyk7XG5cbiAgICBpZiAoIWVsKSB7XG4gICAgICAgIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGVsLmNsYXNzTmFtZSA9ICdtdy1wcm9ncmVzcyc7XG4gICAgICAgIGVsLmlubmVySFRNTCA9ICc8ZGl2IGNsYXNzPVwibXctcHJvZ3Jlc3MtaW5kZXhcIj48L2Rpdj4nO1xuICAgICAgICBpZiAoZWxlbWVudCA9PT0gbXdkLmJvZHkpIGVsLnN0eWxlLnBvc2l0aW9uID0gJ2ZpeGVkJztcbiAgICAgICAgZWxlbWVudC5hcHBlbmRDaGlsZChlbCk7XG4gICAgfVxuICAgIGlmIChwcm9ncmVzcyA9PT0gJ2hpZGUnKSB7XG4gICAgICAgIGVsLnJlbW92ZSgpO1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIHZhciBwb3MgPSBtdy5DU1NQYXJzZXIoZWxlbWVudCkuZ2V0LnBvc2l0aW9uKCk7XG4gICAgaWYgKHBvcyA9PT0gJ3N0YXRpYycpIHtcbiAgICAgICAgZWxlbWVudC5zdHlsZS5wb3NpdGlvbiA9ICdyZWxhdGl2ZSc7XG4gICAgfVxuICAgIGlmIChwcm9ncmVzcykge1xuICAgICAgICBpZiAocHJvZ3Jlc3MgPT09IHRydWUpIHtcbiAgICAgICAgICAgIHNldChlbCwgOTUsIHNwZWVkIHx8ICdzbG93JylcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmICh0eXBlb2YgcHJvZ3Jlc3MgPT09ICdudW1iZXInKSB7XG4gICAgICAgICAgICBwcm9ncmVzcyA9IHByb2dyZXNzIDw9IDEwMCA/IHByb2dyZXNzIDogMTAwO1xuICAgICAgICAgICAgcHJvZ3Jlc3MgPSBwcm9ncmVzcyA+PSAwID8gcHJvZ3Jlc3MgOiAwO1xuICAgICAgICAgICAgc2V0KGVsLCBwcm9ncmVzcywgc3BlZWQpXG4gICAgICAgIH1cbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIGlmIChlbCkge1xuICAgICAgICAgICAgc2V0KGVsLCAxMDAsIHNwZWVkIHx8ICdmYXN0JylcbiAgICAgICAgfVxuICAgICAgICBlbGVtZW50Ll9fbG9hZGluZ1RpbWUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG13LiQoZWxlbWVudCkucmVtb3ZlQ2xhc3MoJ213LWxvYWRpbmctZGVmYXVsdHMgbXctbG9hZGluZycpO1xuICAgICAgICAgICAgbXcuJChlbCkucmVtb3ZlKClcbiAgICAgICAgfSwgNzAwKVxuICAgIH1cbn07XG4iLCIoZnVuY3Rpb24oKXtcbiAgICB2YXIgc3lzdGVtRGlhbG9ncyA9IHtcbiAgICAgICAgbW9kdWxlRnJhbWU6IGZ1bmN0aW9uKHR5cGUsIHBhcmFtcywgYXV0b0hlaWdodCl7XG4gICAgICAgICAgICBpZih0eXBlb2YgYXV0b0hlaWdodCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICBhdXRvSGVpZ2h0ID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHBhcmFtcyA9IHBhcmFtcyB8fCB7fTtcbiAgICAgICAgICAgIGlmKCF0eXBlKSByZXR1cm47XG5cbiAgICAgICAgICAgIHZhciBmcmFtZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lmcmFtZScpO1xuICAgICAgICAgICAgZnJhbWUuY2xhc3NOYW1lID0gJ213LWVkaXRvci1mcmFtZSc7XG4gICAgICAgICAgICBmcmFtZS5hbGxvdyA9ICdhY2NlbGVyb21ldGVyOyBhdXRvcGxheTsgZW5jcnlwdGVkLW1lZGlhOyBneXJvc2NvcGU7IHBpY3R1cmUtaW4tcGljdHVyZSc7XG4gICAgICAgICAgICBmcmFtZS5hbGxvd0Z1bGxzY3JlZW4gPSB0cnVlO1xuICAgICAgICAgICAgZnJhbWUuc2Nyb2xsaW5nID0gXCJ5ZXNcIjtcbiAgICAgICAgICAgIGZyYW1lLndpZHRoID0gXCIxMDAlXCI7XG4gICAgICAgICAgICBmcmFtZS5mcmFtZUJvcmRlciA9IFwiMFwiO1xuICAgICAgICAgICAgZnJhbWUuc3JjID0gbXcuZXh0ZXJuYWxfdG9vbCgnbW9kdWxlJykgKyAnP3R5cGU9JyArIHR5cGUgKyAnJnBhcmFtcz0nICsgJC5wYXJhbShwYXJhbXMpLnNwbGl0KCcmJykuam9pbignLCcpO1xuICAgICAgICAgICAgaWYoYXV0b0hlaWdodCkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQoZnJhbWUpXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gZnJhbWU7XG4gICAgICAgIH0sXG4gICAgICAgICAgY29uZmlybV9yZXNldF9tb2R1bGVfYnlfaWQ6IGZ1bmN0aW9uIChtb2R1bGVfaWQpIHtcbiAgICAgICAgaWYgKGNvbmZpcm0oXCJBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gcmVzZXQgdGhpcyBtb2R1bGU/XCIpKSB7XG4gICAgICAgICAgICB2YXIgaXNfYV9wcmVzZXQgPSBtdy4kKCcjJyttb2R1bGVfaWQpLmF0dHIoJ2RhdGEtbW9kdWxlLW9yaWdpbmFsLWlkJyk7XG4gICAgICAgICAgICB2YXIgaXNfYV9wcmVzZXRfYXR0cnMgPSBtdy4kKCcjJyttb2R1bGVfaWQpLmF0dHIoJ2RhdGEtbW9kdWxlLW9yaWdpbmFsLWF0dHJzJyk7XG4gICAgICAgICAgICBpZihpc19hX3ByZXNldCl7XG4gICAgICAgICAgICAgICAgdmFyIG9yaWdfYXR0cnNfZGVjb2RlZCA9IEpTT04ucGFyc2Uod2luZG93LmF0b2IoaXNfYV9wcmVzZXRfYXR0cnMpKTtcbiAgICAgICAgICAgICAgICBpZiAob3JpZ19hdHRyc19kZWNvZGVkKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoJyMnK21vZHVsZV9pZCkucmVtb3ZlQXR0cignZGF0YS1tb2R1bGUtb3JpZ2luYWwtaWQnKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCgnIycrbW9kdWxlX2lkKS5yZW1vdmVBdHRyKCdkYXRhLW1vZHVsZS1vcmlnaW5hbC1hdHRycycpO1xuICAgICAgICAgICAgICAgICAgICBtdy4kKCcjJyttb2R1bGVfaWQpLmF0dHIob3JpZ19hdHRyc19kZWNvZGVkKS5yZWxvYWRfbW9kdWxlKCk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYoICBtdy50b3AoKS53aW4ubW9kdWxlX3NldHRpbmdzX21vZGFsX3JlZmVyZW5jZV9wcmVzZXRfZWRpdG9yX3RoaXNtb2RhbCApe1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcudG9wKCkud2luLm1vZHVsZV9zZXR0aW5nc19tb2RhbF9yZWZlcmVuY2VfcHJlc2V0X2VkaXRvcl90aGlzbW9kYWwucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBkYXRhID0ge307XG4gICAgICAgICAgICBkYXRhLm1vZHVsZXNfaWRzID0gW21vZHVsZV9pZF07XG5cbiAgICAgICAgICAgIHZhciBjaGlsZHNfYXJyID0gW107XG5cbiAgICAgICAgICAgIG13LiQoJyMnK21vZHVsZV9pZCkuYW5kU2VsZigpLmZpbmQoJy5lZGl0JykuZWFjaChmdW5jdGlvbiAoaSkge1xuICAgICAgICAgICAgICAgIHZhciBzb21lX2NoaWxkID0ge307XG5cbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyh0aGlzLCAnY2hhbmdlZCcpXG4gICAgICAgICAgICAgICAgc29tZV9jaGlsZC5yZWwgPSBtdy4kKHRoaXMpLmF0dHIoJ3JlbCcpO1xuICAgICAgICAgICAgICAgIHNvbWVfY2hpbGQuZmllbGQgPSBtdy4kKHRoaXMpLmF0dHIoJ2ZpZWxkJyk7XG5cbiAgICAgICAgICAgICAgICBjaGlsZHNfYXJyLnB1c2goc29tZV9jaGlsZCk7XG5cbiAgICAgICAgICAgIH0pO1xuXG5cbiAgICAgICAgICAgIHdpbmRvdy5tdy5vbi5ET01DaGFuZ2VQYXVzZSA9IHRydWU7XG5cbiAgICAgICAgICAgIGlmIChjaGlsZHNfYXJyLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgICAgICAgIC8vIGRhdGFUeXBlOiBcImpzb25cIixcbiAgICAgICAgICAgICAgICAgICAgLy9wcm9jZXNzRGF0YTogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgIHVybDogbXcuc2V0dGluZ3MuYXBpX3VybCArIFwiY29udGVudC9yZXNldF9lZGl0XCIsXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IHtyZXNldDpjaGlsZHNfYXJyfVxuICAgICAgICAgICAgICAgICAgLy8gIHN1Y2Nlc3M6IHN1Y2Nlc3MsXG4gICAgICAgICAgICAgICAgICAvLyAgZGF0YVR5cGU6IGRhdGFUeXBlXG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgIH1cblxuXG4gICAgICAgICAgIC8vZGF0YS1tb2R1bGUtb3JpZ2luYWwtYXR0cnNcblxuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB0eXBlOiBcIlBPU1RcIixcbiAgICAgICAgICAgICAgICAvLyBkYXRhVHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICAgICAgLy9wcm9jZXNzRGF0YTogZmFsc2UsXG4gICAgICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJjb250ZW50L3Jlc2V0X21vZHVsZXNfc2V0dGluZ3NcIixcbiAgICAgICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuJCgnIycrbW9kdWxlX2lkKS5yZW1vdmVBdHRyKCdkYXRhLW1vZHVsZS1vcmlnaW5hbC1pZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcucmVsb2FkX21vZHVsZSgnIycrbW9kdWxlX2lkKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5tdy5vbi5ET01DaGFuZ2VQYXVzZSA9IGZhbHNlO1xuXG4gICAgICAgICAgICAgICAgICAgIH0sIDEwMDApO1xuXG4gICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgb3Blbl9yZXNldF9jb250ZW50X2VkaXRvcjogZnVuY3Rpb24gKHJvb3RfZWxlbWVudF9pZCkge1xuXG4gICAgICAgIHZhciBzcmMgPSBtdy5zZXR0aW5ncy5zaXRlX3VybCArICdhcGkvbW9kdWxlP2lkPW13X2dsb2JhbF9yZXNldF9jb250ZW50X2VkaXRvciZsaXZlX2VkaXQ9dHJ1ZSZtb2R1bGVfc2V0dGluZ3M9dHJ1ZSZ0eXBlPWVkaXRvci9yZXNldF9jb250ZW50JmF1dG9zaXplPXRydWUnO1xuXG4gICAgICAgIGlmKHR5cGVvZihyb290X2VsZW1lbnRfaWQpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICB2YXIgc3JjID0gc3JjICsgJyZyb290X2VsZW1lbnRfaWQ9Jytyb290X2VsZW1lbnRfaWQ7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBtdy5kaWFsb2dJZnJhbWUoe1xuICAgICAgICB2YXIgbW9kYWwgPSBtdy5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgdXJsOiBzcmMsXG4gICAgICAgICAgICAvLyB3aWR0aDogNTAwLFxuICAgICAgICAgICAgLy8gaGVpZ2h0OiBtdy4kKHdpbmRvdykuaGVpZ2h0KCkgLSAoMi41ICogbXcudG9vbHMuVGVtcGxhdGVTZXR0aW5nc01vZGFsRGVmYXVsdHMudG9wKSxcbiAgICAgICAgICAgIG5hbWU6ICdtdy1yZXNldC1jb250ZW50LWVkaXRvci1mcm9udCcsXG4gICAgICAgICAgICB0aXRsZTogJ1Jlc2V0IGNvbnRlbnQnLFxuICAgICAgICAgICAgdGVtcGxhdGU6ICdkZWZhdWx0JyxcbiAgICAgICAgICAgIGNlbnRlcjogZmFsc2UsXG4gICAgICAgICAgICByZXNpemU6IHRydWUsXG4gICAgICAgICAgICBhdXRvc2l6ZTogdHJ1ZSxcbiAgICAgICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXG4gICAgICAgICAgICBkcmFnZ2FibGU6IHRydWVcbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBvcGVuX2dsb2JhbF9tb2R1bGVfc2V0dGluZ3NfbW9kYWw6IGZ1bmN0aW9uIChtb2R1bGVfdHlwZSwgbW9kdWxlX2lkLCBtb2RhbE9wdGlvbnMsIGFkZGl0aW9uYWxfcGFyYW1zKSB7XG5cblxuICAgICAgICB2YXIgcGFyYW1zID0ge307XG4gICAgICAgIHBhcmFtcy5pZCA9IG1vZHVsZV9pZDtcbiAgICAgICAgcGFyYW1zLmxpdmVfZWRpdCA9IHRydWU7XG4gICAgICAgIHBhcmFtcy5tb2R1bGVfc2V0dGluZ3MgPSB0cnVlO1xuICAgICAgICBwYXJhbXMudHlwZSA9IG1vZHVsZV90eXBlO1xuICAgICAgICBwYXJhbXMuYXV0b3NpemUgPSBmYWxzZTtcblxuICAgICAgICB2YXIgcGFyYW1zX3VybCA9ICQuZXh0ZW5kKHt9LCBwYXJhbXMsIGFkZGl0aW9uYWxfcGFyYW1zKTtcblxuICAgICAgICB2YXIgc3JjID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyBcImFwaS9tb2R1bGU/XCIgKyBqc29uMnVybChwYXJhbXNfdXJsKTtcblxuXG4gICAgICAgIG1vZGFsT3B0aW9ucyA9IG1vZGFsT3B0aW9ucyB8fCB7fTtcblxuICAgICAgICB2YXIgZGVmYXVsdE9wdHMgPSB7XG4gICAgICAgICAgICB1cmw6IHNyYyxcbiAgICAgICAgICAgIC8vIHdpZHRoOiA1MDAsXG4gICAgICAgICAgICBoZWlnaHQ6ICdhdXRvJyxcbiAgICAgICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXG4gICAgICAgICAgICBuYW1lOiAnbXctbW9kdWxlLXNldHRpbmdzLWVkaXRvci1mcm9udCcsXG4gICAgICAgICAgICB0aXRsZTogJ1NldHRpbmdzJyxcbiAgICAgICAgICAgIHRlbXBsYXRlOiAnZGVmYXVsdCcsXG4gICAgICAgICAgICBjZW50ZXI6IGZhbHNlLFxuICAgICAgICAgICAgcmVzaXplOiB0cnVlLFxuICAgICAgICAgICAgZHJhZ2dhYmxlOiB0cnVlXG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIHNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRPcHRzLCBtb2RhbE9wdGlvbnMpO1xuXG4gICAgICAgIC8vIHJldHVybiBtdy5kaWFsb2dJZnJhbWUoc2V0dGluZ3MpO1xuICAgICAgICByZXR1cm4gbXcuZGlhbG9nSWZyYW1lKHNldHRpbmdzKTtcbiAgICB9LFxuICAgIG9wZW5fbW9kdWxlX21vZGFsOiBmdW5jdGlvbiAobW9kdWxlX3R5cGUsIHBhcmFtcywgbW9kYWxPcHRpb25zKSB7XG5cbiAgICAgICAgdmFyIGlkID0gbXcuaWQoJ21vZHVsZS1tb2RhbC0nKTtcbiAgICAgICAgdmFyIGlkX2NvbnRlbnQgPSBpZCArICctY29udGVudCc7XG4gICAgICAgIG1vZGFsT3B0aW9ucyA9IG1vZGFsT3B0aW9ucyB8fCB7fTtcblxuICAgICAgICB2YXIgc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwge1xuICAgICAgICAgICAgY29udGVudDogJzxkaXYgY2xhc3M9XCJtb2R1bGUtbW9kYWwtY29udGVudFwiIGlkPVwiJyArIGlkX2NvbnRlbnQgKyAnXCI+PC9kaXY+JyxcbiAgICAgICAgICAgIGlkOiBpZFxuICAgICAgICB9LCBtb2RhbE9wdGlvbnMsIHtza2luOiAnZGVmYXVsdCd9KTtcblxuICAgICAgICB2YXIgeGhyID0gZmFsc2U7XG4gICAgICAgIHZhciBvcGVuaWZyYW1lID0gZmFsc2U7XG4gICAgICAgIGlmICh0eXBlb2YgKHNldHRpbmdzLmlmcmFtZSkgIT0gJ3VuZGVmaW5lZCcgJiYgc2V0dGluZ3MuaWZyYW1lKSB7XG4gICAgICAgICAgICBvcGVuaWZyYW1lID0gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAob3BlbmlmcmFtZSkge1xuXG4gICAgICAgICAgICB2YXIgYWRkaXRpb25hbF9wYXJhbXMgPSB7fTtcbiAgICAgICAgICAgIGFkZGl0aW9uYWxfcGFyYW1zLnR5cGUgPSBtb2R1bGVfdHlwZTtcbiAgICAgICAgICAgIHZhciBwYXJhbXNfdXJsID0gJC5leHRlbmQoe30sIHBhcmFtcywgYWRkaXRpb25hbF9wYXJhbXMpO1xuICAgICAgICAgICAgdmFyIHNyYyA9IG13LnNldHRpbmdzLnNpdGVfdXJsICsgXCJhcGkvbW9kdWxlP1wiICsganNvbjJ1cmwocGFyYW1zX3VybCk7XG5cblxuICAgICAgICAgICAgdmFyIHNldHRpbmdzID0ge1xuICAgICAgICAgICAgICAgIHVybDogc3JjLFxuICAgICAgICAgICAgICAgIG5hbWU6ICdtdy1tb2R1bGUtc2V0dGluZ3MtZWRpdG9yLWZyb250JyxcbiAgICAgICAgICAgICAgICB0aXRsZTogJ1NldHRpbmdzJyxcbiAgICAgICAgICAgICAgICBjZW50ZXI6IGZhbHNlLFxuICAgICAgICAgICAgICAgIHJlc2l6ZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICBkcmFnZ2FibGU6IHRydWUsXG4gICAgICAgICAgICAgICAgaGVpZ2h0OidhdXRvJyxcbiAgICAgICAgICAgICAgICBhdXRvSGVpZ2h0OiB0cnVlXG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgcmV0dXJuIG13LnRvcCgpLmRpYWxvZ0lmcmFtZShzZXR0aW5ncyk7XG5cbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGRlbGV0ZSBzZXR0aW5ncy5za2luO1xuICAgICAgICAgICAgZGVsZXRlIHNldHRpbmdzLnRlbXBsYXRlO1xuICAgICAgICAgICAgc2V0dGluZ3MuaGVpZ2h0ID0gJ2F1dG8nO1xuICAgICAgICAgICAgc2V0dGluZ3MuYXV0b0hlaWdodCA9IHRydWU7XG4gICAgICAgICAgICBzZXR0aW5ncy5lbmNhcHN1bGF0ZSA9IGZhbHNlO1xuICAgICAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nKHNldHRpbmdzKTtcbiAgICAgICAgICAgIHhociA9IG13LmxvYWRfbW9kdWxlKG1vZHVsZV90eXBlLCAnIycgKyBpZF9jb250ZW50LCBmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgbW9kYWwuY2VudGVyKCk7XG4gICAgICAgICAgICAgICAgfSwzMzMpXG4gICAgICAgICAgICB9LCBwYXJhbXMpO1xuICAgICAgICB9XG5cblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgeGhyOiB4aHIsXG4gICAgICAgICAgICBtb2RhbDogbW9kYWwsXG4gICAgICAgIH1cbiAgICB9XG4gICAgfTtcblxuICAgIGZvciAodmFyIGkgaW4gc3lzdGVtRGlhbG9ncykge1xuICAgICAgICBtdy50b29sc1tpXSA9IHN5c3RlbURpYWxvZ3NbaV07XG4gICAgfVxufSkoKVxuIiwibXcucmVzcG9uc2l2ZSA9IHtcclxuICAgIHRhYmxlOiBmdW5jdGlvbiAoc2VsZWN0b3IsIG9wdGlvbnMpIHtcclxuICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcclxuICAgICAgICBtdy4kKHNlbGVjdG9yKS5lYWNoKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgdmFyIGNscyA9ICdyZXNwb25zaXZlLXRhYmxlLScgKyBtdy5yYW5kb20oKTtcclxuICAgICAgICAgICAgbXcudG9vbHMuYWRkQ2xhc3ModGhpcywgY2xzKTtcclxuICAgICAgICAgICAgdmFyIGVsID0gbXcuJCh0aGlzKTtcclxuICAgICAgICAgICAgZWwud3JhcCgnPGRpdiBjbGFzcz1cIm13LXJlc3BvbnNpdmUtdGFibGUtd3JhcHBlclwiPjwvZGl2PicpO1xyXG4gICAgICAgICAgICBpZiAob3B0aW9ucy5taW5XaWR0aCkge1xyXG4gICAgICAgICAgICAgICAgZWwuY3NzKCdtaW5XaWR0aCcsIG9wdGlvbnMubWluV2lkdGgpXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKCFlbC5oYXNDbGFzcygnbXctbW9iaWxlLXRhYmxlJykpIHtcclxuICAgICAgICAgICAgICAgIGVsLmFkZENsYXNzKCdtdy1tb2JpbGUtdGFibGUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG59O1xyXG4iLCJtdy5zZXNzaW9uID0ge1xuICAgIGNoZWNrUGF1c2U6IGZhbHNlLFxuICAgIGNoZWNrUGF1c2VFeHBsaWNpdGx5OiBmYWxzZSxcbiAgICBjaGVjazogZnVuY3Rpb24gKGNhbGxiYWNrKSB7XG4gICAgICAgIGlmICghbXcuc2Vzc2lvbi5jaGVja1BhdXNlKSB7XG4gICAgICAgICAgICBtdy5zZXNzaW9uLmNoZWNrUGF1c2UgPSB0cnVlO1xuICAgICAgICAgICAgaWYgKG13LnNlc3Npb24uY2hlY2tQYXVzZUV4cGxpY2l0bHkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAkLnBvc3QobXcuc2V0dGluZ3MuYXBpX3VybCArIFwiaXNfbG9nZ2VkXCIsIGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgaWYgKGRhdGEgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoZGF0YSAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCh1bmRlZmluZWQsIHRydWUpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodW5kZWZpbmVkLCBmYWxzZSlcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LnNlc3Npb24uY2hlY2tQYXVzZSA9IGZhbHNlO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGxvZ1JlcXVlc3Q6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIG1vZGFsID0gbXcuZGlhbG9nKHtcbiAgICAgICAgICAgIGh0bWw6IFwiPGgzIHN0eWxlPSdtYXJnaW46MDsnPlwiICsgbXcubXNnLnNlc3Npb25fZXhwaXJlZCArIFwiLjwvaDM+IDxwIHN0eWxlPSdtYXJnaW46MDsnPlwiICsgbXcubXNnLmxvZ2luX3RvX2NvbnRpbnVlICsgXCIuPC9wPiA8YnI+IDxkaXYgaWQ9J3Nlc3Npb25fcG9wdXBfbG9naW4nPjwvZGl2PlwiLFxuICAgICAgICAgICAgaWQ6IFwic2Vzc2lvbl9tb2RhbFwiLFxuICAgICAgICAgICAgbmFtZTogXCJzZXNzaW9uX21vZGFsXCIsXG4gICAgICAgICAgICBvdmVybGF5OiB0cnVlLFxuICAgICAgICAgICAgd2lkdGg6IDQwMCxcbiAgICAgICAgICAgIGhlaWdodDogMzAwLFxuICAgICAgICAgICAgdGVtcGxhdGU6ICdtd19tb2RhbF9iYXNpYycsXG4gICAgICAgICAgICBjYWxsYmFjazogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LmxvYWRfbW9kdWxlKFwidXNlcnMvbG9naW5cIiwgJyNzZXNzaW9uX3BvcHVwX2xvZ2luJywgZmFsc2UsIHt0ZW1wbGF0ZTogJ3BvcHVwJ30pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LFxuICAgIGNoZWNrSW5pdDogZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAoc2VsZiAhPT0gdG9wKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuc2Vzc2lvbi5jaGVjayhmdW5jdGlvbiAoaXNfbG9nZ2VkKSB7XG4gICAgICAgICAgICAgICAgaWYgKGlzX2xvZ2dlZCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbSA9IG13LmRpYWxvZy5nZXQoXCIjc2Vzc2lvbl9tb2RhbFwiKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKG0pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG0ucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIG13LnNlc3Npb24ubG9nUmVxdWVzdCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LCA5MDAwMCk7XG4gICAgfVxufVxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xuXG4gICAgbXcuJChkb2N1bWVudCkub24oXCJhamF4U2VuZFwiLGZ1bmN0aW9uICgpIHtcblxuICAgICAgICBtdy5zZXNzaW9uLmNoZWNrUGF1c2UgPSB0cnVlO1xuICAgIH0pLmJpbmQoXCJhamF4Q29tcGxldGVcIiwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuc2Vzc2lvbi5jaGVja1BhdXNlID0gZmFsc2U7XG4gICAgICAgIH0pO1xufSk7XG4iLCIoZnVuY3Rpb24gKCl7XG4gICAgaWYobXcuU3RhdGUpIHJldHVybjtcbiAgICB2YXIgU3RhdGUgPSBmdW5jdGlvbihvcHRpb25zKXtcblxuICAgICAgICB2YXIgc2NvcGUgPSB0aGlzO1xuICAgICAgICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgICAgICAgICBtYXhJdGVtczogMTAwMFxuICAgICAgICB9O1xuICAgICAgICB0aGlzLm9wdGlvbnMgPSAkLmV4dGVuZCh7fSwgZGVmYXVsdHMsIChvcHRpb25zIHx8IHt9KSk7XG4gICAgICAgIHRoaXMuX3N0YXRlID0gdGhpcy5vcHRpb25zLnN0YXRlIHx8IFtdO1xuICAgICAgICB0aGlzLl9hY3RpdmUgPSBudWxsO1xuICAgICAgICB0aGlzLl9hY3RpdmVJbmRleCA9IC0xO1xuXG4gICAgICAgIHRoaXMuaGFzTmV4dCA9IGZhbHNlO1xuICAgICAgICB0aGlzLmhhc1ByZXYgPSBmYWxzZTtcblxuICAgICAgICB0aGlzLnN0YXRlID0gZnVuY3Rpb24oc3RhdGUpe1xuICAgICAgICAgICAgaWYoIXN0YXRlKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5fc3RhdGU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLl9zdGF0ZSA9IHN0YXRlO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICAgIH07XG5cblxuICAgICAgICB0aGlzLmFjdGl2ZSA9IGZ1bmN0aW9uKGFjdGl2ZSl7XG4gICAgICAgICAgICBpZighYWN0aXZlKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5fYWN0aXZlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuYWN0aXZlSW5kZXggPSBmdW5jdGlvbihhY3RpdmVJbmRleCl7XG4gICAgICAgICAgICBpZighYWN0aXZlSW5kZXgpe1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLl9hY3RpdmVJbmRleDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLl90aW1lb3V0ID0gbnVsbDtcbiAgICAgICAgdGhpcy50aW1lb3V0UmVjb3JkID0gZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgICAgICBjbGVhclRpbWVvdXQodGhpcy5fdGltZW91dCk7XG4gICAgICAgICAgICB0aGlzLl90aW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbihzY29wZSwgaXRlbSl7XG4gICAgICAgICAgICAgICAgc2NvcGUucmVjb3JkKGl0ZW0pO1xuICAgICAgICAgICAgfSwgMzMzLCB0aGlzLCBpdGVtKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgcmVjZW50UmVjb3JkSXNFcXVhbCA9IGZ1bmN0aW9uIChpdGVtKSB7XG4gICAgICAgICAgICBjb25zdCBjdXJyID0gc2NvcGUuX3N0YXRlWzBdO1xuICAgICAgICAgICAgaWYoIWN1cnIpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIGZvciAodmFyIG4gaW4gaXRlbSkge1xuICAgICAgICAgICAgICAgIGlmKGN1cnJbbl0gIT09IGl0ZW1bbl0pIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMucmVjb3JkID0gZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgICAgICBpZih0aGlzLl9hY3RpdmVJbmRleD4tMSkge1xuICAgICAgICAgICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgICAgICAgICB3aGlsZSAoIGkgPCAgdGhpcy5fYWN0aXZlSW5kZXgpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fc3RhdGUuc2hpZnQoKTtcbiAgICAgICAgICAgICAgICAgICAgaSsrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChyZWNlbnRSZWNvcmRJc0VxdWFsKGl0ZW0pKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5fc3RhdGUudW5zaGlmdChpdGVtKTtcbiAgICAgICAgICAgIGlmKHRoaXMuX3N0YXRlLmxlbmd0aCA+PSB0aGlzLm9wdGlvbnMubWF4SXRlbXMpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9zdGF0ZS5zcGxpY2UoLTEsMSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSBudWxsO1xuICAgICAgICAgICAgdGhpcy5fYWN0aXZlSW5kZXggPSAtMTtcbiAgICAgICAgICAgIHRoaXMuYWZ0ZXJDaGFuZ2UoZmFsc2UpO1xuICAgICAgICAgICAgbXcuJCh0aGlzKS50cmlnZ2VyKCdzdGF0ZVJlY29yZCcsIFt0aGlzLmV2ZW50RGF0YSgpXSk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLmFjdGlvblJlY29yZCA9IGZ1bmN0aW9uKHJlY29yZEdlbkZ1bmMsIGFjdGlvbil7XG4gICAgICAgICAgICB0aGlzLnJlY29yZChyZWNvcmRHZW5GdW5jKCkpO1xuICAgICAgICAgICAgYWN0aW9uLmNhbGwoKTtcbiAgICAgICAgICAgIHRoaXMucmVjb3JkKHJlY29yZEdlbkZ1bmMoKSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5yZWRvID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuX2FjdGl2ZUluZGV4LS07XG4gICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0aGlzLl9zdGF0ZVt0aGlzLl9hY3RpdmVJbmRleF07XG4gICAgICAgICAgICB0aGlzLmFmdGVyQ2hhbmdlKCdzdGF0ZVJlZG8nKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMudW5kbyA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZih0aGlzLl9hY3RpdmVJbmRleCA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmVJbmRleCA9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNle1xuICAgICAgICAgICAgICAgIHRoaXMuX2FjdGl2ZUluZGV4Kys7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0aGlzLl9zdGF0ZVt0aGlzLl9hY3RpdmVJbmRleF07XG4gICAgICAgICAgICB0aGlzLmFmdGVyQ2hhbmdlKCdzdGF0ZVVuZG8nKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuaGFzUmVjb3JkcyA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICByZXR1cm4gISF0aGlzLl9zdGF0ZS5sZW5ndGg7XG4gICAgICAgIH07XG5cbiAgICAgICAgdGhpcy5ldmVudERhdGEgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgICAgICBoYXNQcmV2OiB0aGlzLmhhc1ByZXYsXG4gICAgICAgICAgICAgICAgaGFzTmV4dDogdGhpcy5oYXNOZXh0LFxuICAgICAgICAgICAgICAgIGFjdGl2ZTogdGhpcy5hY3RpdmUoKSxcbiAgICAgICAgICAgICAgICBhY3RpdmVJbmRleDogdGhpcy5hY3RpdmVJbmRleCgpXG4gICAgICAgICAgICB9O1xuICAgICAgICB9O1xuICAgICAgICB0aGlzLmFmdGVyQ2hhbmdlID0gZnVuY3Rpb24oYWN0aW9uKXtcbiAgICAgICAgICAgIHRoaXMuaGFzTmV4dCA9IHRydWU7XG4gICAgICAgICAgICB0aGlzLmhhc1ByZXYgPSB0cnVlO1xuXG4gICAgICAgICAgICBpZihhY3Rpb24pIHtcbiAgICAgICAgICAgICAgICBpZih0aGlzLl9hY3RpdmVJbmRleCA+PSB0aGlzLl9zdGF0ZS5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fYWN0aXZlSW5kZXggPSB0aGlzLl9zdGF0ZS5sZW5ndGggLSAxO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9hY3RpdmUgPSB0aGlzLl9zdGF0ZVt0aGlzLl9hY3RpdmVJbmRleF07XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZih0aGlzLl9hY3RpdmVJbmRleCA8PSAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5oYXNQcmV2ID0gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZih0aGlzLl9hY3RpdmVJbmRleCA9PT0gdGhpcy5fc3RhdGUubGVuZ3RoLTEgfHwgKHRoaXMuX3N0YXRlLmxlbmd0aCA9PT0gMSAmJiB0aGlzLl9zdGF0ZVswXS4kaW5pdGlhbCkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmhhc05leHQgPSBmYWxzZTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYoYWN0aW9uKXtcblxuICAgICAgICAgICAgICAgIG13LiQodGhpcykudHJpZ2dlcihhY3Rpb24sIFt0aGlzLmV2ZW50RGF0YSgpXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihhY3Rpb24gIT09IGZhbHNlKXtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnRyaWdnZXIoJ2NoYW5nZScsIFt0aGlzLmV2ZW50RGF0YSgpXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuICAgICAgICB0aGlzLnJlc2V0ID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMuX3N0YXRlID0gdGhpcy5vcHRpb25zLnN0YXRlIHx8IFtdO1xuICAgICAgICAgICAgdGhpcy5hZnRlckNoYW5nZSgncmVzZXQnKTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9O1xuXG4gICAgICAgIHRoaXMuY2xlYXIgPSBmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5fc3RhdGUgPSBbXTtcbiAgICAgICAgICAgIHRoaXMuYWZ0ZXJDaGFuZ2UoJ2NsZWFyJyk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfTtcblxuXG4gICAgfTtcbiAgICBtdy5TdGF0ZSA9IFN0YXRlO1xufSkoKTtcblxuKGZ1bmN0aW9uKCl7XG4gICAgaWYobXcubGl2ZUVkaXRTdGF0ZSkgcmV0dXJuO1xuICAgIG13LmxpdmVFZGl0U3RhdGUgPSBuZXcgbXcuU3RhdGUoKTtcbiAgICBtdy5saXZlRWRpdFN0YXRlLnJlY29yZCh7XG4gICAgICAgICB2YWx1ZTogbnVsbCxcbiAgICAgICAgICRpbml0aWFsOiB0cnVlXG4gICAgfSk7XG4gICAgbXcuJGxpdmVFZGl0U3RhdGUgPSBtdy4kKG13LmxpdmVFZGl0U3RhdGUpO1xuXG4gICAgdmFyIHVpID0gbXcuJCgnPGRpdiBjbGFzcz1cIm13LXVpLWJ0bi1uYXZcIj48L2Rpdj4nKSxcbiAgICAgICAgdW5kbyA9IG13ZC5jcmVhdGVFbGVtZW50KCdzcGFuJyksXG4gICAgICAgIHJlZG8gPSBtd2QuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgIHVuZG8uY2xhc3NOYW1lID0gJ213LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtJztcbiAgICB1bmRvLmlubmVySFRNTCA9ICc8c3BhbiBjbGFzcz1cIm13LWljb24tcmVwbHlcIj48L3NwYW4+JztcbiAgICByZWRvLmNsYXNzTmFtZSA9ICdtdy11aS1idG4gbXctdWktYnRuLW1lZGl1bSc7XG4gICAgcmVkby5pbm5lckhUTUwgPSAnPHNwYW4gY2xhc3M9XCJtdy1pY29uLWZvcndhcmRcIj48L3NwYW4+JztcblxuICAgIHVuZG8ub25jbGljayA9IGZ1bmN0aW9uKCl7XG4gICAgICAgIG13LmxpdmVFZGl0U3RhdGUudW5kbygpO1xuICAgIH07XG4gICAgcmVkby5vbmNsaWNrID0gZnVuY3Rpb24oKXtcbiAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWRvKCk7XG4gICAgfTtcblxuICAgIHVpLmFwcGVuZCh1bmRvKTtcbiAgICB1aS5hcHBlbmQocmVkbyk7XG5cbiAgICBtdy4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuICAgICAgICB2YXIgaWRhdGEgPSBtdy5saXZlRWRpdFN0YXRlLmV2ZW50RGF0YSgpO1xuXG4gICAgICAgIG13LiQodW5kbylbIWlkYXRhLmhhc05leHQ/J2FkZENsYXNzJzoncmVtb3ZlQ2xhc3MnXSgnZGlzYWJsZWQnKTtcbiAgICAgICAgbXcuJChyZWRvKVshaWRhdGEuaGFzUHJldj8nYWRkQ2xhc3MnOidyZW1vdmVDbGFzcyddKCdkaXNhYmxlZCcpO1xuXG4gICAgICAgIC8qdW5kby5kaXNhYmxlZCA9ICFpZGF0YS5oYXNOZXh0O1xuICAgICAgICByZWRvLmRpc2FibGVkID0gIWlkYXRhLmhhc1ByZXY7Ki9cblxuICAgICAgICB2YXIgZWRpdHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuZWRpdCcpLCBlZGl0c3RpbWUgPSBudWxsO1xuXG4gICAgICAgIGZvciAoIHZhciBpID0gMDsgaSA8IGVkaXRzLmxlbmd0aDsgaSsrICkge1xuICAgICAgICAgICAgaWYoIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3ModGhpcywgJ2VkaXQnKSkge1xuICAgICAgICAgICAgICAgIGVkaXRzW2ldLmFkZEV2ZW50TGlzdGVuZXIoJ2tleWRvd24nLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgc2VsID0gZ2V0U2VsZWN0aW9uKCk7XG4gICAgICAgICAgICAgICAgICAgIHZhciB0YXJnZXQgPSBtdy53eXNpd3lnLnZhbGlkYXRlQ29tbW9uQW5jZXN0b3JDb250YWluZXIoc2VsLmZvY3VzTm9kZSk7XG4gICAgICAgICAgICAgICAgICAgIGlmKCF0YXJnZXQuX19pbml0aWFsUmVjb3JkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXQuX19pbml0aWFsUmVjb3JkID0gdHJ1ZTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWNvcmQoe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRhcmdldDogdGFyZ2V0LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiB0YXJnZXQuaW5uZXJIVE1MXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIGVkaXRzW2ldLmFkZEV2ZW50TGlzdGVuZXIoJ2lucHV0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KGVkaXRzdGltZSk7XG4gICAgICAgICAgICAgICAgICAgIGVkaXRzdGltZSA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHNlbCA9IGdldFNlbGVjdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHRhcmdldCA9IG13Lnd5c2l3eWcudmFsaWRhdGVDb21tb25BbmNlc3RvckNvbnRhaW5lcihzZWwuZm9jdXNOb2RlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmxpdmVFZGl0U3RhdGUucmVjb3JkKHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IHRhcmdldCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZTogdGFyZ2V0LmlubmVySFRNTFxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl9faW5pdGlhbFJlY29yZCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9LCAxMjM0KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIG13LiRsaXZlRWRpdFN0YXRlLm9uKCdzdGF0ZVJlY29yZCcsIGZ1bmN0aW9uKGUsIGRhdGEpe1xuICAgICAgICAgICAgbXcuJCh1bmRvKVshZGF0YS5oYXNOZXh0PydhZGRDbGFzcyc6J3JlbW92ZUNsYXNzJ10oJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICBtdy4kKHJlZG8pWyFkYXRhLmhhc1ByZXY/J2FkZENsYXNzJzoncmVtb3ZlQ2xhc3MnXSgnZGlzYWJsZWQnKTtcbiAgICAgICAgfSk7XG4gICAgICAgIG13LiRsaXZlRWRpdFN0YXRlLm9uKCdzdGF0ZVVuZG8gc3RhdGVSZWRvJywgZnVuY3Rpb24oZSwgZGF0YSl7XG5cblxuXG4gICAgICAgICAgICBpZighZGF0YS5hY3RpdmUgfHwgKCFkYXRhLmFjdGl2ZS50YXJnZXQgJiYgIWRhdGEuYWN0aXZlLmFjdGlvbikpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHVuZG8pWyFkYXRhLmhhc05leHQ/J2FkZENsYXNzJzoncmVtb3ZlQ2xhc3MnXSgnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICBtdy4kKHJlZG8pWyFkYXRhLmhhc1ByZXY/J2FkZENsYXNzJzoncmVtb3ZlQ2xhc3MnXSgnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZihkYXRhLmFjdGl2ZS5hY3Rpb24pIHtcbiAgICAgICAgICAgICAgICBkYXRhLmFjdGl2ZS5hY3Rpb24oKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZihkb2N1bWVudC5ib2R5LmNvbnRhaW5zKGRhdGEuYWN0aXZlLnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBtdy4kKGRhdGEuYWN0aXZlLnRhcmdldCkuaHRtbChkYXRhLmFjdGl2ZS52YWx1ZSk7XG4gICAgICAgICAgICB9IGVsc2V7XG4gICAgICAgICAgICAgICAgaWYoZGF0YS5hY3RpdmUudGFyZ2V0LmlkKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoZGF0YS5hY3RpdmUudGFyZ2V0LmlkKSkuaHRtbChkYXRhLmFjdGl2ZS52YWx1ZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYoZGF0YS5hY3RpdmUucHJldikge1xuICAgICAgICAgICAgICAgIG13LiQoZGF0YS5hY3RpdmUucHJldikuaHRtbChkYXRhLmFjdGl2ZS5wcmV2VmFsdWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbXcuZHJhZy5sb2FkX25ld19tb2R1bGVzKCk7XG4gICAgICAgICAgICBtdy4kKHVuZG8pWyFkYXRhLmhhc05leHQ/J2FkZENsYXNzJzoncmVtb3ZlQ2xhc3MnXSgnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgIG13LiQocmVkbylbIWRhdGEuaGFzUHJldj8nYWRkQ2xhc3MnOidyZW1vdmVDbGFzcyddKCdkaXNhYmxlZCcpO1xuICAgICAgICB9KTtcblxuICAgICAgICBtdy4kKCcjaGlzdG9yeV9wYW5lbF90b2dnbGUsI2hpc3RvcnlfZGQsLm13X2VkaXRvcl91bmRvLC5td19lZGl0b3JfcmVkbycpLnJlbW92ZSgpO1xuICAgICAgICBtdy4kKCcud3lzaXd5Zy1jZWxsLXVuZG8tcmVkbycpLmVxKDApLnByZXBlbmQodWkpO1xuXG5cblxuXG5cbiAgICAgICAgbXcuZWxlbWVudChkb2N1bWVudC5ib2R5KS5vbigna2V5ZG93bicsIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIGlmIChlLmN0cmxLZXkgJiYgZS5rZXkgPT09ICd6Jykge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBtdy5saXZlRWRpdFN0YXRlLnVuZG8oKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5jdHJsS2V5ICYmIGUua2V5ID09PSAneScpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgbXcubGl2ZUVkaXRTdGF0ZS5yZWRvKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgfSk7XG5cbn0pKCk7XG5cblxuIl0sInNvdXJjZVJvb3QiOiIifQ==