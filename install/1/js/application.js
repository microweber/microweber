function addEvent(elm,evType,fn,capture){JSL.dom(elm).on(evType,fn);}
function stopEvent(e){return JSL.event(e).stop();}
function findTarget(e){return JSL.event(e).getTarget();}
function getElementsByCSS(selector){return $(selector);}
function getElementsByClassName(classname,tag){return $(tag+"."+classname);}
function toggle(item,state){JSL.dom(item).toggle();}

function t(s) {
	if (typeof locale=="undefined") return s;
	if (sl=locale[s]) return sl;
	return s;
}

function zeroPad(amount) {
	if(amount < 10) amount = "0" + amount;
	return amount;
}

function showMessage(data) {
	if(data.success) {
		$("success-message").innerHTML = stripSlashes(data.success);
		$("success-message").show();
		$("error-message").hide();
	
	} else if(data.error) {
		$("error-message").innerHTML = stripSlashes(data.error);
		$("error-message").show();
		$("success-message").hide();
	}
}

function loading() {
	$("loading").show();
}
function loaded() {
	$("loading").hide();
}

function stripSlashes(text) {
	if(!text) return "";
	return text.replace(/\\([\'\"])/,"$1");
}
function siteInit() {
	//Enable the menu for IE
	if (document.all && document.getElementById && $("menu")) { //Only IE allowed(only it supports 'document.all')
		var navRoot = $("menu").getElementsByTagName("li");

		for (var i=0; i<navRoot.length; i++) {
			var node = navRoot[i];
			if (node.className.indexOf("dropdown")+1) {
				node.onmouseover=function() {
					this.className +=" over";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(" over", "");
				}
			}
		}
	}
	
	if(window.init) init(); //Auto execute the init() function on page load
}

JSL.dom(window).load(siteInit);
