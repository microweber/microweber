mw.keys = {
    8: "Backspace",
    9: "Tab",
    13: "Enter",
    16: "Shift",
    17: "Ctrl",
    18: "Alt",
    19: "Pause/Break",
    20: "CapsLock",
    27: "Esc",
    32: "Space",
    33: "PageUp",
    34: "PageDown",
    35: "End",
    36: "Home",
    37: "Left",
    38: "Up",
    39: "Right",
    40: "Down",
    45: "Insert",
    46: "Delete",
    48: "0",
    49: "1",
    50: "2",
    51: "3",
    52: "4",
    53: "5",
    54: "6",
    55: "7",
    56: "8",
    57: "9",
    65: "A",
    66: "B",
    67: "C",
    68: "D",
    69: "E",
    70: "F",
    71: "G",
    72: "H",
    73: "I",
    74: "J",
    75: "K",
    76: "L",
    77: "M",
    78: "N",
    79: "O",
    80: "P",
    81: "Q",
    82: "R",
    83: "S",
    84: "T",
    85: "U",
    86: "V",
    87: "W",
    88: "X",
    89: "Y",
    90: "Z",
    91: "Windows",
    93: "RightClick",
    96: "Numpad0",
    97: "Numpad1",
    98: "Numpad2",
    99: "Numpad3",
    100: "Numpad4",
    101: "Numpad5",
    102: "Numpad6",
    103: "Numpad7",
    104: "Numpad8",
    105: "Numpad9",
    106: "Numpad*",
    107: "Numpad+",
    109: "Numpad-",
    110: "Numpad.",
    111: "Numpad/",
    112: "F1",
    113: "F2",
    114: "F3",
    115: "F4",
    116: "F5",
    117: "F6",
    118: "F7",
    119: "F8",
    120: "F9",
    121: "F10",
    122: "F11",
    123: "F12",
    145: "ScrollLock",
    186: ";",
    187: "=",
    188: ",",
    189: "-",
    190: ".",
    191: "/",
    192: "`",
    219: "[",
    220: "\\",
    221: "]",
    222: "'"
};

mw.keyCombination = {}

$(document).ready(function(){
  $(window).bind("keydown keyup", function(event){
    var key =  event.keyCode;
    if(mw.is.defined(mw.keys[key])){
      $(window).trigger(mw.keys[key] + "-" + event.type, event);
    }
    if(event.type=='keydown'){
      mw.keyCombination[key] = event.keyCode;
    }
    else{
       delete mw.keyCombination[key];
       var collect = ""
       for(x in mw.keyCombination){
         var dis =  mw.keyCombination[x];
         if(mw.is.defined(mw.keys[dis])){
            collect+=mw.keys[dis];
            delete mw.keyCombination[x];
         }
       }
       if(collect!=""){
         $(window).trigger(collect+mw.keys[key], event);
       }
    }
  });
});