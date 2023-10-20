mw.color = {
    rgbaToHex: function (orig) {
        var a, isPercent,
            rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
            alpha = (rgb && rgb[4] || "").trim(),
            hex = rgb ?
                (rgb[1] | 1 << 8).toString(16).slice(1) +
                (rgb[2] | 1 << 8).toString(16).slice(1) +
                (rgb[3] | 1 << 8).toString(16).slice(1) : orig;

        if (alpha !== '') {
            a = alpha;
        } else {
            a = 01;
        }
        a = ((a * 255) | 1 << 8).toString(16).slice(1)
        hex = hex + a;

        return '#' + hex;
    },
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
    rgbOrRgbaToHex: function (color) {

        if(!color) return '';

      if(color.indexOf('#') !== -1) {
        return color
      }
        if(color.indexOf('rgb(') === 0) {
            return this.rgbToHex(color)
        } else {
            return this.rgbaToHex(color)
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
















