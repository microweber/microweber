mw.color = {
  rgbToHex : function(color) {
    if(color.contains('rgb')){
      var color = color.replace(/rgb/g, '').replace(/rgba/g, '').replace(/ *\([^)]*\) */g, "").replace(/\s/g, "");
    }
    var color = color.split(',');
    return "#" + ((1 << 24) + (color[0] << 16) + (color[1] << 8) + color[2]).toString(16).slice(1);
  }
}




