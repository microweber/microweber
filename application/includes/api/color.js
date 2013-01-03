mw.color = {
  rgbToHex : function(color) {
    if(color.contains('rgb')){
      var color = color.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "");
    }
    var color = color.split(',');
    return "#" + ((1 << 24) + (parseInt(color[0]) << 16) + (parseInt(color[1]) << 8) + parseInt(color[2])).toString(16).slice(1);
  }
}




