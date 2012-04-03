/* 
Methods for resizing the flash stage at runtime.

setFlashWidth(divid, newW)
divid: id of the div containing the flash movie.
newW: new width for flash movie

setFlashWidth(divid, newH)
divid: id of the div containing the flash movie.
newH: new height for flash movie

setFlashSize(divid, newW, newH)
divid: id of the div containing the flash movie.
newW: new width for flash movie
newH: new height for flash movie

canResizeFlash()
returns true if browser supports resizing flash, false if not. 
*/
function setFlashRestore(divid, newW, newH){
/*	document.getElementById(divid).style.left = "0px";	
	document.getElementById(divid).style.top = "0px";	
*/
}

function setFlashVisibilityOff(divid){
	document.getElementById(divid).style.display = "none";
	document.getElementById(divid).style.visiblity = "false";
}

function setFlashSize(divid, newW, newH){
//	document.getElementById(divid).style.display = "none";	
/*	document.getElementById(divid).style.left = "0px";	
	document.getElementById(divid).style.top = "730px";	
	document.getElementById(divid).style.float = "Right";	
*/	
	document.getElementById(divid).style.width = newW +"px";
	document.getElementById(divid).style.height = newH +"px";		
	
//	document.getElementById(divid).style.position = "fixed";
}
function setFlashWidth(divid, newW){
	document.getElementById(divid).style.width = newW+"px";
}
function setFlashHeight(divid, newH){
	document.getElementById(divid).style.height = newH+"px";		
}

/*function setFlashSize(divid, newW, newH){
	setFlashWidth(divid, newW);
	setFlashHeight(divid, newH);
}*/
function canResizeFlash(){
	var ua = navigator.userAgent.toLowerCase();
	var opera = ua.indexOf("opera");
	if( document.getElementById ){
		if(opera == -1) return true;
		else if(parseInt(ua.substr(opera+6, 1)) >= 7) return true;
	}
	return false;
}