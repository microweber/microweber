mw.color = {
  rgbToHex : function(color) {
    if(color.contains('rgb')){
      var color = color.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "");
    }
    var color = color.split(',');
    if(color != 'transparent'){
      return "#" + ((1 << 24) + (parseInt(color[0]) << 16) + (parseInt(color[1]) << 8) + parseInt(color[2])).toString(16).slice(1);
    }
    else{
      return 'transparent';
    }
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
    var color = !color.contains("#") ? color : color.replace("#", '');
    return mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(0,2)))
      + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(2,2)))
      + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(4,2)));
  }
}
















