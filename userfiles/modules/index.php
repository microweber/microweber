 
<script  type="text/javascript">
 	$(document).ready(function(){
		init_antzz_antzz()
	/*		mw.require("<? print $config['url_to_module'] ?>jquery.snippet.css", function(){

 

			});
				
			 
			 
	mw.require("<? print $config['url_to_module'] ?>jquery.snippet.js", function(){

 $("#<? print $source_code_id ?>").snippet("<? print $source_code_language ?>");
 
			});
	
			*/
			
			
			
			
		 
			}); 

</script>
 
<style type="text/css">
.ant {
	height: 16px;
	position: absolute;
	visibility: hidden;
	width: 16px;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!-- Original:  Mike Hall (MHall75819@aol.com) -->
<!-- Web Site:  http://members.aol.com/MHall75819 -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://www.javascriptsource.com -->

<!-- Begin
var dir = "http://www.your-web-site-address-here.com/where-you-put-the-ant-images/";

// get the ant images in a single zip file from
// http://www.javascriptsource.com/img/ants/ants.zip

// Extract the images then upload them to your server and
// change the 'dir' variable to their location (end with a '/')

var images = new Array(
dir+"antdl.gif",
dir+"antdn.gif",
dir+"antdr.gif",
dir+"antlt.gif",
dir+"antrt.gif",
dir+"antul.gif",
dir+"antup.gif",
dir+"antur.gif"
);

var isMinNS4 = (document.layers) ? 1 : 0;
var isMinIE4 = (document.all)    ? 1 : 0;

var _LBimgList;
var _LBimgCount;
var _LBbase = "LBbase";
var _LBlow  = "LBlow";
var _LBhigh = "LBhigh";
var _LBwidth;
var _LBheight;
var _LBbaseLayer;
var _LBlowLayer;
var _LBhighLayer;
function createLoadBar(width, height, bdSize, bdColor, bgColor, fgColor, fontFace, fontSize, text) {
var txtLow, txtHigh, tblStart, tblEnd;
var str;
txtLow  = '<font color="' + fgColor + '" face="' + fontFace + '" size=' + fontSize + '>' + text + '</font>';
txtHigh = '<font color="' + bgColor  + '" face="' + fontFace + '" size=' + fontSize + '>' + text + '</font>';
tblStart = '<table border=0 cellpadding=0 cellspacing=0 height=100% width=100%><tr valign="center"><td align="center">';
tblEnd = '</td></tr></table>';
if (isMinNS4)
str = '<layer name="' + _LBbase + '" bgcolor="' + bdColor + '" width=' + width + ' height=' + height + ' visibility="hide">\n'
+ '  <layer name="' + _LBlow  + '" bgcolor="' + bgColor + '" left=' + bdSize + ' top=' + bdSize + ' width=' + (width - 2 * bdSize) + ' height=' + (height - 2 * bdSize) + '>' + tblStart + txtLow + tblEnd + '</layer>\n'
+ '  <layer name="' + _LBhigh + '" bgcolor="' + fgColor + '" left=' + bdSize + ' top=' + bdSize + ' width=' + (width - 2 * bdSize) + ' height=' + (height - 2 * bdSize) + '>' + tblStart + txtHigh + tblEnd + '</layer>\n'
+ '</layer>';
if (isMinIE4)
str = '<div id="' + _LBbase + '" style="position:absolute; background-color:' + bdColor + '; width:' + width + 'px; height:' + height + 'px; visibility:hidden;">\n'
+ '  <div id="' + _LBlow  + '" style="position:absolute; background-color=' + bgColor + '; left:' + bdSize + 'px; top:' + bdSize + 'px; width:' + (width - 2 * bdSize) + 'px; height:' + (height - 2 * bdSize) + 'px;">' + tblStart + txtLow + tblEnd + '</div>\n'
+ '  <div id="' + _LBhigh  + '" style="position:absolute; background-color=' + fgColor + '; left:' + bdSize + 'px; top:' + bdSize + 'px; width:' + (width - 2 * bdSize) + 'px; height:' + (height - 2 * bdSize) + 'px;">' + tblStart + txtHigh + tblEnd + '</div>\n'
+ '</div>';
document.writeln(str);
_LBwidth = width - 2 * bdSize;
_LBheight = height - 2 * bdSize;
}
function startLoadBar(srcList, x, y) {
var i, w, h;
if (isMinNS4) {
_LBbaseLayer = document.layers[_LBbase];
_LBlowLayer  = _LBbaseLayer.document.layers[_LBlow];
_LBhighLayer = _LBbaseLayer.document.layers[_LBhigh];
}
if (isMinIE4) {
_LBbaseLayer = eval('document.all.' + _LBbase);
_LBlowLayer  = eval('document.all.' + _LBlow);
_LBhighLayer = eval('document.all.' + _LBhigh);
}
if (isMinNS4) {
w = window.innerWidth;
h = window.innerHeight;
}
if (isMinIE4) {
w = document.body.clientWidth;
h = document.body.clientHeight;
}
if (x == null)
x = Math.round((w  - _LBwidth)  / 2);
if (y == null)
y = Math.round((h - _LBheight) / 2);
moveLayerTo(_LBbaseLayer, x, y);
clipLayer(_LBhighLayer, 0, 0, 0, _LBheight);
showLayer(_LBbaseLayer);
_LBimgCount = 0;
_LBimgList = new Array();
for (i = 0; i < srcList.length; i++) {
_LBimgList[i] = new Image();
_LBimgList[i].onabort = _LBupdate;
_LBimgList[i].onerror = _LBupdate;
_LBimgList[i].onload  = _LBupdate;
}
for (i = 0; i < srcList.length; i++)
_LBimgList[i].src = srcList[i];
}
function endLoadBar() { // empty (can be changed) function called when finished
}
function _LBupdate() {
var pct;
_LBimgCount++;
pct = _LBimgCount / _LBimgList.length;
clipLayer(_LBhighLayer, 0, 0, Math.round(pct * _LBwidth), _LBheight);
if (_LBimgCount == _LBimgList.length) {
setTimeout('hideLayer(_LBbaseLayer)', 500);
endLoadBar();
}
}
function moveLayerTo(layer, x, y) {
if (isMinNS4)
layer.moveTo(x, y);
if (isMinIE4) {
layer.style.left = x;
layer.style.top  = y;
}
}
function hideLayer(layer) {
if (isMinNS4)
layer.visibility = "hide";
if (isMinIE4)
layer.style.visibility = "hidden";
}
function getWindowWidth() {
if (isMinNS4)
return(window.innerWidth);
if (isMinIE4)
return(document.body.offsetWidth);
return(-1);
}
function getWindowHeight() {
if (isMinNS4)
return(window.innerHeight);
if (isMinIE4)
return(document.body.offsetHeight);
return(-1);
}
function getPageScrollX() {
if (isMinNS4)
return(window.pageXOffset);
if (isMinIE4)
return(document.body.scrollLeft);
return(-1);
}
function getPageScrollY() {
if (isMinNS4)
return(window.pageYOffset);
if (isMinIE4)
return(document.body.scrollTop);
return(-1);
}
function getHeight(layer) {
if (isMinNS4) {
if (layer.document.height)
return(layer.document.height);
else
return(layer.clip.bottom - layer.clip.top);
}
if (isMinIE4) {
if (false && layer.style.pixelHeight)
return(layer.style.pixelHeight);
else
return(layer.clientHeight);
}
return(-1);
}
function getWidth(layer) {
if (isMinNS4) {
if (layer.document.width)
return(layer.document.width);
else
return(layer.clip.right - layer.clip.left);
}
if (isMinIE4) {
if (layer.style.pixelWidth)
return(layer.style.pixelWidth);
else
return(layer.clientWidth);
}
return(-1);
}
function getLeft(layer) {
if (isMinNS4)
return(layer.left);
if (isMinIE4)
return(layer.style.pixelLeft);
return(-1);
}
function getTop(layer) {
if (isMinNS4)
return(layer.top);
if (isMinIE4)
return(layer.style.pixelTop);
return(-1);
}
function getRight(layer) {
if (isMinNS4)
return(layer.left + getWidth(layer));
if (isMinIE4)
return(layer.style.pixelLeft + getWidth(layer));
return(-1);
}
function getBottom(layer) {
if (isMinNS4)
return(layer.top + getHeight(layer));
else if (isMinIE4)
return(layer.style.pixelTop + getHeight(layer));
return(-1);
}
function moveLayerBy(layer, dx, dy) {
if (isMinNS4)
layer.moveBy(dx, dy);
if (isMinIE4) {
layer.style.pixelLeft += dx;
layer.style.pixelTop+= dy;
}
}
function showLayer(layer) {
if (isMinNS4)
layer.visibility = "show";
if (isMinIE4)
layer.style.visibility = "visible";
}
function clipLayer(layer, clipleft, cliptop, clipright, clipbottom) {
if (isMinNS4) {
layer.clip.left = clipleft;
layer.clip.top= cliptop;
layer.clip.right= clipright;
layer.clip.bottom = clipbottom;
}
if (isMinIE4)
layer.style.clip = 'rect(' + cliptop + ' ' +clipright + ' ' + clipbottom + ' ' + clipleft +')';
}
var mouseX = 0;
var mouseY = 0;
if (isMinNS4)
document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = getMousePosition;
function init_antzz() {
startLoadBar(images);
}
function getMousePosition(e) {
if (isMinNS4) {
mouseX = e.pageX;
mouseY = e.pageY;
}
if (isMinIE4) {
mouseX = event.clientX + document.body.scrollLeft;
mouseY = event.clientY + document.body.scrollTop;
}
return true;
}
var ants = new Array(8);
function endLoadBar() {
var i;
for (i = 0; i < ants.length; i++) {
if (isMinNS4) {
ants[i] = document.layers["ant" + (i + 1)];
ants[i].image = ants[i].document.images["antimg" + (i + 1)];
}
if (isMinIE4) {
ants[i] = eval('document.all.ant' + (i + 1));
ants[i].image = document.images["antimg" + (i + 1)];
}
init_antzzAnt(i);
showLayer(ants[i]);
}
updateAnts();
}
function init_antzzAnt(n) {
var s, x, y;
x = Math.floor(Math.random() * getWindowWidth());
y = Math.floor(Math.random() * getWindowHeight());
s = Math.floor(Math.random() * 4);
if (s == 0)
x = -getWidth(ants[n]);
if (s == 1)
x = getWindowWidth();
if (s == 2)
y = -getHeight(ants[n]);
if (s == 3)
y = getWindowHeight();
x += getPageScrollX();
y += getPageScrollY();
moveLayerTo(ants[n], x, y);
}
function updateAnts() {
var i, dx, dy, theta, d;
d = 3;
for (i = 0; i < ants.length; i++) {
dx = mouseX - getLeft(ants[i]);
dy = mouseY - getTop(ants[i]);
theta = Math.round(Math.atan2(-dy, dx) * 180 / Math.PI);
if (theta < 0)
theta += 360;
if (Math.abs(dx) < d && Math.abs(dy) < d)
init_antzzAnt(i);
else if (theta > 22.5 && theta <= 67.5) {
moveLayerBy(ants[i], d, -d);
ants[i].image.src = dir+"antur.gif";
}
else if (theta > 67.5 && theta <= 112.5) {
moveLayerBy(ants[i], 0, -d);
ants[i].image.src = dir+"antup.gif";
}
else if (theta > 112.5 && theta <= 157.5) {
moveLayerBy(ants[i], -d, -d);
ants[i].image.src = dir+"antul.gif";
}
else if (theta > 157.5 && theta <= 202.5) {
moveLayerBy(ants[i], -d, 0);
ants[i].image.src = dir+"antlt.gif";
}
else if (theta > 202.5 && theta <= 247.5) {
moveLayerBy(ants[i], -d, d);
ants[i].image.src = dir+"antdl.gif";
}
else if (theta > 247.5 && theta <= 292.5) {
moveLayerBy(ants[i], 0, d);
ants[i].image.src = dir+"antdn.gif";
}
else if (theta > 292.5 && theta <= 337.5) {
moveLayerBy(ants[i], d, d);
ants[i].image.src = dir+"antdr.gif";
}
else {
moveLayerBy(ants[i], d, 0);
ants[i].image.src = dir+"antrt.gif";
}
}
setTimeout('updateAnts()', 50);
return;
}
//  End -->
</script>
 

<!-- STEP THREE: Copy this code into the BODY of your HTML document  --> 

<script language="JavaScript">
createLoadBar(240, 20, 1, "#000000", "#cccccc", "#999999", "MS Sans serif,Arial,Helvetica", 1, "<b>Loading ants, please wait...</b>");
</script>
<div id="ant1" class="ant"><img name="antimg1" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant2" class="ant"><img name="antimg2" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant3" class="ant"><img name="antimg3" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant4" class="ant"><img name="antimg4" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant5" class="ant"><img name="antimg5" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant6" class="ant"><img name="antimg6" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant7" class="ant"><img name="antimg7" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>
<div id="ant8" class="ant"><img name="antimg8" src="<? print $config['url_to_module'] ?>img/transparent.gif" width=13 height=13></div>

<!-- Script Size:  10.57 KB -->