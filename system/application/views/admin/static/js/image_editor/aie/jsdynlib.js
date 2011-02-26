/*-- ***** BEGIN LICENSE BLOCK *****
  -   Version: MPL 1.1/GPL 2.0/LGPL 2.1
  -
  - The contents of this file are subject to the Mozilla Public License Version
  - 1.1 (the "License"); you may not use this file except in compliance with
  - the License. You may obtain a copy of the License at
  - http://www.mozilla.org/MPL/
  - 
  - Software distributed under the License is distributed on an "AS IS" basis,
  - WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
  - for the specific language governing rights and limitations under the
  - License.
  -
  - The Original Code is AIE (Ajax Image Editor).
  -
  - The Initial Developer of the Original Code is
  - Julian Stricker.
  - Portions created by the Initial Developer are Copyright (C) 2006
  - the Initial Developer. All Rights Reserved.
  -
  - Contributor(s):
  -
  - Alternatively, the contents of this file may be used under the terms of
  - either the GNU General Public License Version 2 or later (the "GPL"), or
  - the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
  - in which case the provisions of the GPL or the LGPL are applicable instead
  - of those above. If you wish to allow use of your version of this file only
  - under the terms of either the GPL or the LGPL, and not to allow others to
  - use your version of this file under the terms of the MPL, indicate your
  - decision by deleting the provisions above and replace them with the notice
  - and other provisions required by the GPL or the LGPL. If you do not delete
  - the provisions above, a recipient may use your version of this file under
  - the terms of any one of the MPL, the GPL or the LGPL.
  - 
  - ***** END LICENSE BLOCK ***** */

// check the browser:
var ua = navigator.userAgent.toLowerCase(); 
var isGecko       = (ua.indexOf('gecko') != -1 && ua.indexOf('safari') == -1);
var isKonqueror   = (ua.indexOf('konqueror') != -1); 
var isSafari      = (ua.indexOf('safari') != - 1);
var isOpera       = (ua.indexOf('opera') != -1); 
var isIE          = (ua.indexOf('msie') != -1 && !isOpera && (ua.indexOf('webtv') == -1) ); 
var isMozilla     = (isGecko && ua.indexOf('gecko/') + 14 == ua.length);
var isFirefox    = (ua.indexOf('firefox/') != -1);
var isNS          = ( (isGecko) ? (ua.indexOf('netscape') != -1) : ( (ua.indexOf('mozilla') != -1) && !isOpera && !isSafari && (ua.indexOf('spoofer') == -1) && (ua.indexOf('compatible') == -1) && (ua.indexOf('webtv') == -1) && (ua.indexOf('hotjava') == -1) ) );
   

// gets the element by id: ;-)
function gebid(was){
	return document.getElementById(was);
}

// gets the absolute position of a html element from the left-upper point of the frame:
function getabsolutepos(wer){
	var xpos = wer.offsetLeft;
	var ypos = wer.offsetTop;
	var Eltern = wer.offsetParent;
	while ( Eltern ) {
		xpos = xpos + Eltern.offsetLeft;
		ypos = ypos + Eltern.offsetTop;
		Eltern = Eltern.offsetParent;
	} 
	var abs = {x:xpos, y:ypos};
	return abs;
}

// gets the real scrollpos:
function getscrollpos(){
	var scrtop=0;
	var scrleft=0;
	if (document.documentElement){
		scrtop=document.documentElement.scrollTop;
		scrleft=document.documentElement.scrollLeft;
	}else if (document.body){
		scrtop=document.body.scrollTop;
		scrleft=document.body.scrollLeft;
	}else{
		var derhtml=document.body.parentNode;
		scrtop=derhtml.scrollTop;
		scrleft=derhtml.scrollLeft;
	}
	var scrpos = {x:scrleft, y:scrtop};
	return scrpos;
}

//mouse-positon (with scroll-pos on ie)
var Mouse = {x:0, y:0};
document.onmousemove = function (evt) {
  var e = window.event || evt;
  Mouse.x = e.x || e.pageX || 0;
  Mouse.y = e.y || e.pageY || 0;
  if (isIE){
	  var scrpos=getscrollpos();
  	Mouse.y=Mouse.y+scrpos.y;	
  	Mouse.x=Mouse.x+scrpos.x;
  }
}

//
// addLoadEvent()
// Adds event to window.onload without overwriting currently assigned onload functions.
// Function found at Simon Willison's weblog - http://simon.incutio.com/
// example: addLoadEvent(init);
//
function addLoadEvent(func){	
	var oldonload = window.onload;
	if (typeof window.onload != 'function'){
    	window.onload = func;
	} else {
		window.onload = function(){
		oldonload();
		func();
		}
	}
}

function getElementByAttribute(aAttribute,aValue,aInElement){
  var ElementVerifier;
  var Elements=new Array();
  function SearchElement(aElement){ 
    if(aElement==null||aElement==undefined)return
    if(ElementVerifier(aElement)){ 
  	  Elements[Elements.length]=aElement;
  	}
  	SearchElement(aElement.firstChild);
  	SearchElement(aElement.nextSibling);
  }  
  if(aInElement==undefined)aInElement=document.body;
  str="if(Element."+aAttribute+"=='"+aValue+"'){return true;}else{return false}";
  ElementVerifier=function(aElement){
    Element=aElement;
  	if(aElement.nodeName=='#text')return false;
  	var E=new Function(str);
  	if(E()){return true;}else{return false};
  }
  SearchElement(aInElement);
  return Elements;
}

function getElementsByAttribute(oElm, strTagName, strAttributeName, strAttributeValue){
    var arrElements = (strTagName == "*" && document.all)? document.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    var oAttributeValue = (typeof strAttributeValue != "undefined")? new RegExp("(^|\\s)" + strAttributeValue + "(\\s|$)") : null;
    var oCurrent;
    var oAttribute;
    for(var i=0; i<arrElements.length; i++){
        oCurrent = arrElements[i];
        oAttribute = oCurrent.getAttribute(strAttributeName);
        if(typeof oAttribute == "string" && oAttribute.length > 0){
            if(typeof strAttributeValue == "undefined" || (oAttributeValue && oAttributeValue.test(oAttribute))){
                arrReturnElements.push(oCurrent);
            }
        }
    }
    return arrReturnElements;
}
