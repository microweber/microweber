/**
 * reflex.js 1.21 (21-Mar-2009)
 * (c) by Christian Effenberger 
 * All Rights Reserved
 * Source: reflex.netzgesta.de
 * Distributed under Netzgestade Software License Agreement
 * http://www.netzgesta.de/cvi/LICENSE.txt
 * License permits free of charge
 * use on non-commercial and 
 * private web sites only 
**/

var tmp = navigator.appName == 'Microsoft Internet Explorer' && navigator.userAgent.indexOf('Opera') < 1 ? 1 : 0;
if(tmp) var isIE = document.namespaces ? 1 : 0;

if(isIE) {
	if(document.namespaces['v']==null) {
		var e=["shape","shapetype","group","background","path","formulas","handles","fill","stroke","shadow","textbox","textpath","imagedata","line","polyline","curve","roundrect","oval","rect","arc","image"],s=document.createStyleSheet(); 
		for(var i=0; i<e.length; i++) {s.addRule("v\\:"+e[i],"behavior: url(#default#VML);");} document.namespaces.add("v","urn:schemas-microsoft-com:vml");
	} 
}

function getImages(className){
	var children = document.getElementsByTagName('img'); 
	var elements = new Array(); var i = 0;
	var child; var classNames; var j = 0;
	for (i=0;i<children.length;i++) {
		child = children[i];
		classNames = child.className.split(' ');
		for (var j = 0; j < classNames.length; j++) {
			if (classNames[j] == className) {
				elements.push(child);
				break;
			}
		}
	}
	return elements;
}

function getClasses(classes,string){
	var temp = '';
	for (var j=0;j<classes.length;j++) {
		if (classes[j] != string) {
			if (temp) {
				temp += ' '
			}
			temp += classes[j];
		}
	}
	return temp;
}

function getClassValue(classes,string){
	var temp = 0; var pos = string.length;
	for (var j=0;j<classes.length;j++) {
		if (classes[j].indexOf(string) == 0) {
			temp = Math.min(classes[j].substring(pos),100);
			break;
		}
	}
	return Math.max(0,temp);
}
function getClassColor(classes,string){
	var temp = 0; var str = ''; var pos = string.length;
	for (var j=0;j<classes.length;j++) {
		if (classes[j].indexOf(string) == 0) {
			temp = classes[j].substring(pos);
			str = '#' + temp.toLowerCase();
			break;
		}
	}
	if(str.match(/^#[0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f]$/i)) {return str; }else {return 0;}
}
function getClassAttribute(classes,string){
	var temp = 0; var pos = string.length;
	for (var j=0;j<classes.length;j++) {
		if (classes[j].indexOf(string) == 0) {
			temp = 1; 
			break;
		}
	}
	return temp;
}

function clipPolyRight(ctx,x,y,w,h,t,d,s) {
	var z = (h-t-t)/h;
	ctx.beginPath(); 
	ctx.moveTo(x,y); ctx.lineTo(w,y+t); ctx.lineTo(w,y+h-t); ctx.lineTo(x,y+h);
	if(d>0) {ctx.lineTo(x,y+h-s); ctx.lineTo(w,y+h-t-(z*s)); ctx.lineTo(w,y+h-t-(z*(s+d))); ctx.lineTo(x,y+h-s-d);}
	ctx.closePath();
}	

function clipPolyLeft(ctx,x,y,w,h,t,d,s) {
	var z = (h-t-t)/h;
	ctx.beginPath(); 
	ctx.moveTo(x,y+t); ctx.lineTo(w,y+1); ctx.lineTo(w,y+h); ctx.lineTo(x,y+h-t);
	if(d>0) {ctx.lineTo(x,y+h-t-(z*s)); ctx.lineTo(w,y+h-s); ctx.lineTo(w,y+h-s-d); ctx.lineTo(x,y+h-t-(z*(s+d))); }
	ctx.closePath();
}
	
function strokePolyRight(ctx,x,y,w,h,t,d,s,b) {
	var z = (h-t-t)/h; var n = (b>=1?1:0);
	ctx.beginPath(); 
	ctx.moveTo(x+b,y+b); ctx.lineTo(w-b,y+t+b-n); ctx.lineTo(w-b,y+h-t-(z*(s+d))-b); ctx.lineTo(x+b,y+h-s-d-b);
	ctx.closePath();
}
function strokePolyLeft(ctx,x,y,w,h,t,d,s,b) {
	var z = (h-t-t)/h; var n = (b>=1?1:0);
	ctx.beginPath(); 
	ctx.moveTo(x+b,y+t+b-n); ctx.lineTo(w-b,y+b); ctx.lineTo(w-b,y+h-s-d-b); ctx.lineTo(x+b,y+h-t-(z*(s+d))-b);
	ctx.closePath();
}

function clipReflex(ctx,x,y,w,h,t,d,s,o) {
	var z = (h-t-t)/h;
	ctx.beginPath();
	if(o=='r') {
		ctx.moveTo(x,y+h-s); ctx.lineTo(w,y+h-t-(z*s)); ctx.lineTo(w,y+h-t+2); ctx.lineTo(x,y+h+2);
	}else {
		ctx.moveTo(w,y+h+2); ctx.lineTo(w,y+h-s); ctx.lineTo(x,y+h-t-(z*s)); ctx.lineTo(x,y+h-t+2); 
	}
	ctx.closePath();
}

function clearReflex(ctx,x,y,w,h,t,d,s,o) {
	var z = (h-t-t)/h;
	ctx.beginPath();
	if(o=='r') {
		ctx.moveTo(x,y+h-1); ctx.lineTo(w,y+h-t-1); ctx.lineTo(w,y+h-t+1); ctx.lineTo(x,y+h+1);
	}else {
		ctx.moveTo(w,y+h-1); ctx.lineTo(x,y+h-t-1); ctx.lineTo(x,y+h-t+1); ctx.lineTo(w,y+h+1);
	}
	ctx.closePath();
}

function addIEReflex() {
	var theimages = getImages('reflex');
	var image, object, vml, display, flt, classes, newClasses, head, fill, flex, foot;  
	var i, j, z, q, p, dist, stl, iter, rest, radi, higt, divs, opac, colr, bord, wide, half, ih, iw, ww, hh, fb, xb;  
	var itiltright, itiltnone, itiltleft, iheight, iopacity, idistance, iborder, icolor, iradius;
	var children = document.getElementsByTagName('img'); var tilt = 'r';
	for(i=0;i<theimages.length;i++) {	
		image = theimages[i]; object = image.parentNode; j = 0;
		itiltright = 0; itiltnone = 0; itiltleft = 0; 
		iheight = 33; iopacity = 33; idistance = 0;
		iborder = 0; icolor = '#000000'; iradius = 0; 
		if(image.width>=32 && image.height>=32) {
			classes = image.className.split(' ');
			iradius = getClassValue(classes,"iradius");
			iborder = getClassValue(classes,"iborder");
			iheight = getClassValue(classes,"iheight");
			iopacity = getClassValue(classes,"iopacity");
			idistance = getClassValue(classes,"idistance");
			icolor = getClassColor(classes,"icolor");
			itiltleft = getClassAttribute(classes,"itiltleft");
			itiltright = getClassAttribute(classes,"itiltright");
			itiltnone = getClassAttribute(classes,"itiltnone");
			if(itiltright==true) tilt = 'r';
			if(itiltnone==true) tilt = 'n';
			if(itiltleft==true) tilt = 'l';
			newClasses = getClasses(classes,"reflex");	
			ih = image.height; iw = image.width; dist = idistance; 
			radi = Math.min(iradius,Math.max(iw,ih)/10);
			colr = (icolor!=0?icolor:'#000000');
			opac = (iopacity>0?iopacity:33);
			divs = 100/(iheight>=10?iheight:33); 
			p = (iheight>=10?iheight:33)/100;
			higt = Math.floor(ih/divs); wide = 12;
			if(iborder==1) { bord = 0; }else {
				iborder = Math.floor(Math.round(Math.min(Math.min(iborder,higt/4),Math.max(iw,ih)/20))/2)*2;
				bord = (iborder>0?iborder/2:0); 
			}
			ww = parseInt(iw/20); q = 1;
			iter = Math.floor((iw-ww-ww)/wide); 
			rest = ((iw-ww-ww)%wide); half = (((iw-ww-ww)/wide)-1)/2;
			hh = iter+(rest>0?1:0); z = (ih-hh-hh)/ih;
			display = (image.currentStyle.display.toLowerCase()=='block')?'block':'inline-block';
			vml = document.createElement(['<var style="overflow:hidden;display:' + display + ';width:' + iw + 'px;height:' + (ih+higt+dist) + 'px;padding:0;">'].join(''));
			flt = image.currentStyle.styleFloat.toLowerCase();
			display = (flt=='left'||flt=='right')?'inline':display;
			head = '<v:group style="zoom:1; display:' + display + '; margin:-1px 0 0 -1px; padding:0; position:relative; width:' + iw + 'px;height:' + (ih+higt+dist) + 'px;" coordsize="' + iw + ',' + (ih+higt+dist) + '">';
			if(tilt=='n') {
				fill = '<v:rect strokeweight="0" filled="t" stroked="f" fillcolor="#ffffff" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:0px; left:0px; width:' + iw + 'px;height:' + ih + 'px;"><v:fill src="' + image.src + '" type="frame" /></v:rect>';
				fb = '<v:rect strokeweight="'+iborder+'" strokecolor="'+colr+'" filled="f" stroked="'+(bord>0||iborder>0?'t':'f')+'" fillcolor="#ffffff" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+bord+'px; left:'+bord+'px; width:'+(iw-bord-bord)+'px;height:'+(ih-bord-bord)+'px;"></v:rect>';
				xb = '<v:rect strokeweight="'+iborder+'" strokecolor="'+colr+'" filled="f" stroked="'+(bord>0||iborder>0?'t':'f')+'" fillcolor="#ffffff" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih+dist+bord)+'px; left:'+bord+'px; width:'+(iw-bord-bord)+'px;height:'+(higt-bord-bord)+'px; filter: progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=0,starty=0,finishx=0,finishy='+parseInt(ih*0.66)+');"></v:rect>';
				flex = '<v:rect strokeweight="0" filled="t" stroked="f" fillcolor="#ffffff" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih+dist)+'px; left:0px; width:' + iw + 'px;height:' + higt + 'px; filter:flipv progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=0,starty=0,finishx=0,finishy='+ih+');"><v:fill origin="0,0" position="0,-'+(divs/2-0.5)+'" size="1,'+(1*divs)+'" src="' + image.src + '" type="frame" /></v:rect>';
			}else if(tilt=='r') {
				fill = '<v:rect strokeweight="0" filled="t" stroked="f" fillcolor="#808080" style="position:absolute; margin:-1px 0 0 -1px;padding:0 ;width:' + iw + 'px;height:' + (ih+higt+dist) + 'px;"><v:fill color="#808080" opacity="0.0" /></v:rect><v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#ffffff" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+ww+',0 l '+ww+','+ih+','+(iw-ww)+','+(ih-hh)+','+(iw-ww)+','+hh+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:0px; left:0px; width:' + iw + 'px;height:' + ih + 'px;"><v:fill src="' + image.src + '" type="frame" /></v:shape>';
				for(j=0;j<iter;j++) {
					if(j==(iter-1)) q = (rest>0?1:0);
					fill += '<v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+(j*wide))+','+j+' l '+(q+ww+((j+1)*wide))+','+(j+1)+','+(q+ww+((j+1)*wide))+','+(ih-1-j)+','+(ww+(j*wide))+','+(ih-j)+' x e" style="position:absolute; margin: -1px 0 0 -1px; padding:0px; top:0px; left:0px; width:' + iw + 'px; height:' + ih + 'px;"><v:fill origin="0,0" position="'+(half-j)+',0" size="'+((iw-ww-ww)/wide)+',1" type="frame" src="' + image.src + '" /></v:shape>';
				}
				if(rest>0) {
					fill += '<v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+(j*wide))+','+j+' l '+(ww+((j+1)*wide))+','+(j+1)+','+(ww+((j+1)*wide))+','+(ih-1-j)+','+(ww+(j*wide))+','+(ih-j)+' x e" style="position:absolute; margin: -1px 0 0 -1px; padding:0px; top:0px; left:0px; width:' + iw + 'px; height:' + ih + 'px;"><v:fill origin="0,0" position="'+(half-j)+',0" size="'+((iw-ww-ww)/wide)+',1" type="frame" src="' + image.src + '" /></v:shape>';
				}
				q = ((iter*z)/(ih/100))/2; 
				if(bord>0||iborder>0) {
					fb = '<v:shape strokeweight="'+iborder+'" strokecolor="'+colr+'" filled="f" stroked="'+(bord>0||iborder>0?'t':'f')+'" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+bord)+','+bord+' l '+(ww+bord)+','+(ih-bord)+','+(iw-ww-bord)+','+(ih-hh-bord)+','+(iw-ww-bord)+','+(hh+bord)+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:0px; left:0px; width:' + iw + 'px;height:' + ih + 'px;"></v:shape>';
					xb = '<v:shape strokeweight="'+iborder+'" strokecolor="'+colr+'" stroked="'+(bord>0||iborder>0?'t':'f')+'" filled="f" coordorigin="0,0" coordsize="'+iw+','+(hh+higt+dist)+'" path="m '+(ww+bord)+','+(hh+dist+bord)+' l '+(ww+bord)+','+(higt+hh+dist-bord)+','+(iw-ww-bord)+','+(parseInt((higt+dist)*z)-bord)+','+(iw-ww-bord)+','+(parseInt(dist*z)+bord)+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih-hh+dist)+'px; left:0px; width:' + iw + 'px;height:' + (hh+higt+dist) + 'px; flip: y; filter:flipv progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=0,starty=0,finishx='+q+',finishy=80);"></v:shape>';
				}else {fb = ''; xb = ''; }	
				flex = '<v:shape strokeweight="0" stroked="f" filled="t" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+(hh+higt+dist)+'" path="m '+ww+','+(hh+dist)+' l '+ww+','+(higt+hh+dist)+','+(iw-ww)+','+parseInt((higt+dist)*z)+','+(iw-ww)+','+parseInt(dist*z)+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih-hh+dist)+'px; left:0px; width:' + iw + 'px;height:' + (hh+higt+dist) + 'px; flip: y; filter:flipv progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=0,starty=0,finishx='+q+',finishy=90);"><v:fill origin="0,0" position="0,-'+((divs/2)-0.5)+'" size="1,'+(divs)+'" src="' + image.src + '" type="frame" /></v:shape>';
			}else if(tilt=='l') {
				fill = '<v:rect strokeweight="0" filled="t" stroked="f" fillcolor="#808080" style="position:absolute; margin:-1px 0 0 -1px;padding:0 ;width:' + iw + 'px;height:' + (ih+higt+dist) + 'px;"><v:fill color="#808080" opacity="0.0" /></v:rect><v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#ffffff" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+ww+','+hh+' l '+ww+','+(ih-hh)+','+(iw-ww)+','+ih+','+(iw-ww)+',0 x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:0px; left:0px; width:' + iw + 'px;height:' + ih + 'px;"><v:fill src="' + image.src + '" type="frame" /></v:shape>';
				for(j=0;j<iter;j++) {
					if(j==(iter-1)) q = (rest>0?1:0);
					fill += '<v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+(j*wide))+','+(iter-j)+' l '+(q+ww+((j+1)*wide))+','+(iter-1-j)+','+(q+ww+((j+1)*wide))+','+(ih-1-iter+j)+','+(ww+(j*wide))+','+(ih-iter+j)+' x e" style="position:absolute; margin: -1px 0 0 -1px; padding:0px; top:0px; left:0px; width:' + iw + 'px; height:' + ih + 'px;"><v:fill origin="0,0" position="'+(half-j)+',0" size="'+((iw-ww-ww)/wide)+',1" type="frame" src="' + image.src + '" /></v:shape>';
				}
				if(rest>0) {
					fill += '<v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+(j*wide))+','+(iter-j)+' l '+(ww+((j+1)*wide))+','+(iter-1-j)+','+(ww+((j+1)*wide))+','+(ih-1-iter+j)+','+(ww+(j*wide))+','+(ih-iter+j)+' x e" style="position:absolute; margin: -1px 0 0 -1px; padding:0px; top:0px; left:0px; width:' + iw + 'px; height:' + ih + 'px;"><v:fill origin="0,0" position="'+(half-j)+',0" size="'+((iw-ww-ww)/wide)+',1" type="frame" src="' + image.src + '" /></v:shape>';
				}
				q = 100-(((iter*z)/(ih/100))/2); 
				if(bord>0||iborder>0) {
					fb = '<v:shape strokeweight="'+iborder+'" strokecolor="'+colr+'" filled="f" stroked="'+(bord>0||iborder>0?'t':'f')+'" coordorigin="0,0" coordsize="'+iw+','+ih+'" path="m '+(ww+bord)+','+(hh+bord)+' l '+(ww+bord)+','+(ih-hh-bord)+','+(iw-ww-bord)+','+(ih-bord)+','+(iw-ww-bord)+','+bord+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:0px; left:0px; width:' + iw + 'px;height:' + ih + 'px;"></v:shape>';
					xb = '<v:shape strokeweight="'+iborder+'" strokecolor="'+colr+'" stroked="'+(bord>0||iborder>0?'t':'f')+'" filled="f" coordorigin="0,0" coordsize="'+iw+','+(hh+higt+dist)+'" path="m '+(ww+bord)+','+(parseInt(dist*z)+bord)+' l '+(ww+bord)+','+(parseInt((higt+dist)*z)-bord)+','+(iw-ww-bord)+','+(higt+hh+dist-bord)+','+(iw-ww-bord)+','+(hh+dist+bord)+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih-hh+dist)+'px; left:0px; width:' + iw + 'px;height:' + (hh+higt+dist) + 'px; flip: y; filter:flipv progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=100,starty=0,finishx='+q+',finishy=80);"></v:shape>';
				}else {fb = ''; xb = ''; }
				flex = '<v:shape strokeweight="0" filled="t" stroked="f" fillcolor="#808080" coordorigin="0,0" coordsize="'+iw+','+(hh+higt+dist)+'" path="m '+ww+','+parseInt(dist*z)+' l '+ww+','+parseInt((higt+dist)*z)+','+(iw-ww)+','+(higt+hh+dist)+','+(iw-ww)+','+(hh+dist)+' x e" style="position:absolute; margin:-1px 0 0 -1px; padding:0; top:'+(ih-hh+dist)+'px; left:0px; width:' + iw + 'px;height:' + (hh+higt+dist) + 'px; flip: y; filter:flipv progid:DXImageTransform.Microsoft.Alpha(opacity='+opac+',style=1,finishOpacity=0,startx=100,starty=0,finishx='+q+',finishy=90);"><v:fill origin="0,0" position="0,-'+((divs/2)-0.5)+'" size="1,'+(divs)+'" src="' + image.src + '" type="frame" /></v:shape>';
			}
			foot = '</v:group>';
			vml.innerHTML = head+flex+xb+fill+fb+foot;
			vml.className = newClasses;
			vml.style.cssText = image.style.cssText;
			vml.style.height = ih+higt+dist+'px'; vml.width = iw;
			vml.height = ih+higt+dist; vml.style.width = iw+'px';
			vml.src = image.src; vml.alt = image.alt;
			if(image.id!='') vml.id = image.id; 
			if(image.title!='') vml.title = image.title;
			if(image.getAttribute('onclick')!='') vml.setAttribute('onclick',image.getAttribute('onclick'));
			object.replaceChild(vml,image);
			if(tilt=='r') {tilt='n';}else if(tilt=='n') {tilt='l';}else if(tilt=='l') {tilt='r';}
			vml.style.visibility = 'visible';
		}
	}
}

function addReflex() {
	var theimages = getImages('reflex');
	var image, object, canvas, context, classes, newClasses, resource, tmp;  
	var i, j, dist, stl, iter, rest, radi, higt, divs, opac, colr, bord, wide, ih, iw;  
	var itiltright, itiltnone, itiltleft, iheight, iopacity, idistance, iborder, icolor, iradius;
	var children = document.getElementsByTagName('img'); var tilt = 'r';
	var isWK = navigator.appVersion.indexOf('WebKit')!=-1?1:0; var isOP = window.opera?1:0;
	var isW5 = document.defaultCharset&&!window.execScript?1:0;
	for(i=0;i<theimages.length;i++) {	
		image = theimages[i]; object = image.parentNode; tmp = 0;
		itiltright = 0; itiltnone = 0; itiltleft = 0; 
		iheight = 33; iopacity = 33; idistance = 0;
		iborder = 0; icolor = '#000000'; iradius = 0; 
		canvas = document.createElement('canvas');
		if(canvas.getContext && image.width>=32 && image.height>=32) {
			classes = image.className.split(' ');
			iradius = getClassValue(classes,"iradius");
			iborder = getClassValue(classes,"iborder");
			iheight = getClassValue(classes,"iheight");
			iopacity = getClassValue(classes,"iopacity");
			idistance = getClassValue(classes,"idistance");
			icolor = getClassColor(classes,"icolor");
			itiltleft = getClassAttribute(classes,"itiltleft");
			itiltright = getClassAttribute(classes,"itiltright");
			itiltnone = getClassAttribute(classes,"itiltnone");
			if(itiltright==true) tilt = 'r';
			if(itiltnone==true) tilt = 'n';
			if(itiltleft==true) tilt = 'l';
			newClasses = getClasses(classes,"reflex");	
			ih = image.height; iw = image.width; dist = idistance; 
			radi = Math.min(iradius,Math.max(iw,ih)/10);
			colr = (icolor!=0?icolor:'#000000');
			opac = (100-(iopacity>0?iopacity:33))/100;
			divs = 100/(iheight>=10?iheight:33);
			higt = Math.floor(image.height/divs);
			iborder = Math.round(Math.min(Math.min(iborder,higt/4),Math.max(iw,ih)/20));
			wide = 12; bord = (iborder>0?iborder/2:0);
			canvas.className = newClasses;
			canvas.style.cssText = image.style.cssText;
			canvas.style.height = ih+higt+dist+'px'; canvas.width = iw;
			canvas.style.width = iw+'px'; canvas.height = ih+higt+dist;
			canvas.src = image.src; canvas.alt = image.alt;
			if(image.id!='') canvas.id = image.id;
			if(image.title!='') canvas.title = image.title;
			if(image.getAttribute('onclick')!='') canvas.setAttribute('onclick',image.getAttribute('onclick'));
			iter = Math.floor(canvas.width/wide); rest = (canvas.width%wide);
			if(tilt=='l'||tilt=='r') {
				resource = document.createElement('canvas');
				if(resource.getContext) {
					resource.style.position = 'fixed';
					resource.style.left = -9999+'px';
					resource.style.top = 0+'px';
					resource.height = canvas.height;
					resource.width = canvas.width;
					resource.style.height = canvas.height+'px';
					resource.style.width = canvas.width+'px';
					if(isWK&&!isW5) {object.appendChild(resource);}
				}
			}
			context = canvas.getContext("2d");
			object.replaceChild(canvas,image);
			context.clearRect(0,0,canvas.width,canvas.height);
			context.globalCompositeOperation = "source-over";
			context.fillStyle = 'rgba(0,0,0,0)';
			context.fillRect(0,0,canvas.width,canvas.height);
			context.save();
			context.translate(0,canvas.height);
			context.scale(1,-1);
			context.drawImage(image,0,-(canvas.height-higt-higt-dist),canvas.width,canvas.height-higt-dist);
			context.restore();
			if(iborder>0) {
				context.strokeStyle = colr;
				context.lineWidth = iborder;
				context.beginPath(); 
				context.rect(bord,canvas.height-higt+bord,canvas.width-iborder,higt);
				context.closePath();
				context.stroke();
			}
			if(!isWK||tilt=='n') {
				context.globalCompositeOperation = "destination-out";
				stl = context.createLinearGradient(0,canvas.height-higt,0,canvas.height);
				stl.addColorStop(1,"rgba(0,0,0,1.0)");
				stl.addColorStop(0,"rgba(0,0,0,"+opac+")");
				context.fillStyle = stl;
			}
			if(isWK) {
				context.beginPath(); 
				context.rect(0,canvas.height-higt,canvas.width,higt);
				context.closePath();
				context.fill();
			}else {
				context.fillRect(0,canvas.height-higt,canvas.width,higt);
			}
			context.globalCompositeOperation = "source-over";
			context.drawImage(image,0,0,iw,ih);
			context.save();
			if(isWK&&dist>0&&tilt!='n') {
				context.fillStyle = '#808080';
				context.fillRect(0,canvas.height-higt-dist,canvas.width,dist);
			}
			if(iborder>0) {
				if(tilt=='n') {
					context.beginPath(); 
					context.rect(bord,bord,canvas.width-iborder,canvas.height-higt-dist-iborder);
					context.closePath();
					context.stroke();
				}
			}
			if(tilt=='l'||tilt=='r') {
				if(resource.getContext) {
					context = resource.getContext("2d");
					context.globalCompositeOperation = "source-over";
					context.clearRect(0,0,resource.width,resource.height);					
					if(tilt=='r') {
						for(j=0;j<iter;j++) {
							context.drawImage(canvas,j*wide,0,wide,resource.height,j*wide,j*1,wide,resource.height-(j*2));
						}
						if(rest>0) {
							rest = canvas.width-(iter*wide);
							context.drawImage(canvas,j*wide,0,rest,resource.height,j*wide,j*1,rest,resource.height-(j*2));
						}
					}else {
						for(j=0;j<iter;j++) {
							context.drawImage(canvas,j*wide,0,wide,resource.height,j*wide,(iter-j)*1,wide,resource.height-((iter-j)*2));
						}
						if(rest>0) {
							rest = canvas.width-(iter*wide);
							context.drawImage(canvas,j*wide,0,rest,resource.height,j*wide,0,rest,resource.height);
						}
					}
					context.save();
					if(canvas.getContext) {
						context = canvas.getContext("2d");
						context.clearRect(0,0,canvas.width,canvas.height);						
						if(tilt=='r') {
							clipPolyRight(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:0),dist,higt);
						}else {
							clipPolyLeft(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:1),dist,higt);
						}
						context.clip();
						context.drawImage(resource,parseInt(canvas.width/20),0,parseInt(canvas.width*0.9),canvas.height);
						context.save();
						if(iborder>0) {
							context.lineWidth = iborder;
							if(tilt=='r') {
								strokePolyRight(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:0),dist,higt,bord);
								context.stroke();
							}else {
								strokePolyLeft(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:0),dist,higt,bord);
								context.stroke();
							}
						}
						if(isWK) {
							context.globalCompositeOperation = "destination-out";
							stl = context.createLinearGradient((tilt=='l'?canvas.width:0),canvas.height-higt,(tilt=='l'?canvas.width-parseInt(wide/divs):parseInt(wide/divs)),canvas.height);
							stl.addColorStop(1,"rgba(255,0,0,1.0)");
							stl.addColorStop(0,"rgba(255,0,0,"+opac+")");
							context.fillStyle = stl;
							clipReflex(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:0),dist,higt,tilt);
							context.fill();
							globalCompositeOperation = "source-in";
							clearReflex(context,canvas.width/20,0,canvas.width*0.95,canvas.height,iter+(rest>0?1:0),dist,higt,tilt);
							context.clip();
							context.clearRect(0,0,canvas.width,canvas.height);
							context.clearRect(0,0,canvas.width,canvas.height);
							context.clearRect(0,0,canvas.width,canvas.height);
							context.clearRect(0,0,canvas.width,canvas.height);
							if(isWK&&!isW5) {object.removeChild(resource);}
						}
					}
				}
			}
			if(tilt=='r') {tilt='n';}else if(tilt=='n') {tilt='l';}else if(tilt=='l') {tilt='r';}
			context.save();
			canvas.style.visibility = 'visible';
		}
	}
}

var reflexOnload = window.onload;
window.onload = function () { if(reflexOnload) reflexOnload(); if(isIE){addIEReflex(); }else {addReflex(); }}
