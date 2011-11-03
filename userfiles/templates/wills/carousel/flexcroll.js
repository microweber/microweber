/*
This license text has to stay intact at all times:
fleXcroll Public License Version
Cross Browser Custom Scroll Bar Script by Hesido.
Public version - Free for non-commercial uses.

This script cannot be used in any commercially built
web sites, or in sites that relates to commercial
activities. This script is not for re-distribution.
For licensing options:
Contact Emrah BASKAYA @ www.hesido.com

Derivative works are only allowed for personal uses,
and they cannot be redistributed.

FleXcroll Public Key Code: 20050907122003339
MD5 hash for this license: 9ada3be4d7496200ab2665160807745d

End of license text---
*/
//fleXcroll v1.9.5f
var fleXenv={

fleXcrollInit:function(){this.addTrggr(window,'load',this.globalInit);},

fleXcrollMain:function(dDv){
//Main code beg
var dC=document,wD=window,nV=navigator;
if(!dC.getElementById||!dC.createElement)return;
if (typeof(dDv)=='string')dDv=document.getElementById(dDv);
if(dDv==null||nV.userAgent.indexOf('OmniWeb')!=-1||((nV.userAgent.indexOf('AppleWebKit')!=-1||nV.userAgent.indexOf('Safari')!=-1)&&!(typeof(HTMLElement)!="undefined"&&HTMLElement.prototype))||nV.vendor=='KDE'||(nV.platform.indexOf('Mac')!=-1&&nV.userAgent.indexOf('MSIE')!=-1))return;
if(dDv.scrollUpdate){dDv.scrollUpdate();return;};
if(!dDv.id||dDv.id==''){var sTid="flex__",c=1;while(document.getElementById(sTid+c)!=null){c++};dDv.id=sTid+c;}
var targetId=dDv.id;
dDv.fleXdata=new Object();var sC=dDv.fleXdata;
sC.keyAct={_37:['-1s',0],_38:[0,'-1s'],_39:['1s',0],_40:[0,'1s'],_33:[0,'-1p'],_34:[0,'1p'],_36:[0,'-100p'],_35:[0,'+100p']};
sC.wheelAct=["-2s","2s"];
sC.baseAct=["-2s","2s"];
var cDv=createDiv('contentwrapper',true),mDv=createDiv('mcontentwrapper',true),tDv=createDiv('scrollwrapper',true),pDv=createDiv('copyholder',true);
var iDv=createDiv('domfixdiv',true),fDv=createDiv('zoomdetectdiv',true),stdMode=false;
pDv.sY.border='1px solid blue';pDv.fHide();
dDv.style.overflow='hidden';
fDv.sY.fontSize="12px";fDv.sY.height="1em";fDv.sY.width="1em";fDv.sY.position="absolute";fDv.sY.zIndex="-999";fDv.fHide();
var brdHeight=dDv.offsetHeight,brdWidth=dDv.offsetWidth;
copyStyles(dDv,pDv,'0px',['border-left-width','border-right-width','border-top-width','border-bottom-width']);
var intlHeight=dDv.offsetHeight,intlWidth=dDv.offsetWidth,brdWidthLoss=brdWidth-intlWidth,brdHeightLoss=brdHeight-intlHeight;
var oScrollY=(dDv.scrollTop)?dDv.scrollTop:0,oScrollX=(dDv.scrollLeft)?dDv.scrollLeft:0;
var urlBase=document.location.href,uReg=/#([^#.]*)$/;
var focusProtectList=['textarea','input','select'];
sC.scroller=[];sC.forcedBar=[];sC.containerSize=sC.cntRSize=[];sC.contentSize=sC.cntSize=[];sC.edge=[false,false];
sC.reqS=[];sC.barSpace=[0,0];sC.forcedHide=[];sC.forcedPos=[];sC.paddings=[];
while (dDv.firstChild) {cDv.appendChild(dDv.firstChild)};
cDv.appendChild(iDv);dDv.appendChild(mDv);dDv.appendChild(pDv);
if(getStyle(dDv,'position')!='absolute') dDv.style.position="relative";

var dAlign=getStyle(dDv,'text-align');dDv.style.textAlign='left';
mDv.sY.width="100px";mDv.sY.height="100px";mDv.sY.top="0px";mDv.sY.left="0px";
copyStyles(dDv,pDv,"0px",['padding-left','padding-top','padding-right','padding-bottom']);
var postWidth=dDv.offsetWidth,postHeight=dDv.offsetHeight,mHeight;
mHeight=mDv.offsetHeight;mDv.sY.borderBottom="2px solid black";
if(mDv.offsetHeight>mHeight)stdMode=true;mDv.sY.borderBottomWidth="0px";
copyStyles(pDv,dDv,false,['padding-left','padding-top','padding-right','padding-bottom']);
findPos(mDv);findPos(dDv);
sC.paddings[0]=mDv.yPos-dDv.yPos;sC.paddings[2]=mDv.xPos-dDv.xPos;
dDv.style.paddingTop=getStyle(dDv,"padding-bottom");dDv.style.paddingLeft=getStyle(dDv,"padding-right");
findPos(mDv);findPos(dDv);
sC.paddings[1]=mDv.yPos-dDv.yPos;sC.paddings[3]=mDv.xPos-dDv.xPos;
dDv.style.paddingTop=getStyle(pDv,"padding-top");dDv.style.paddingLeft=getStyle(pDv,"padding-left");
var padWidthComp=sC.paddings[2]+sC.paddings[3],padHeightComp=sC.paddings[0]+sC.paddings[1];

mDv.style.textAlign=dAlign;
copyStyles(dDv,mDv,false,['padding-left','padding-right','padding-top','padding-bottom']);
tDv.sY.width=dDv.offsetWidth+'px';tDv.sY.height=dDv.offsetHeight+'px';
mDv.sY.width=postWidth+'px'; mDv.sY.height=postHeight+'px';
tDv.sY.position='absolute';tDv.sY.top='0px';tDv.sY.left='0px';
//tDv.fHide();

mDv.appendChild(cDv);dDv.appendChild(tDv);tDv.appendChild(fDv);

cDv.sY.position='relative';mDv.sY.position='relative';
cDv.sY.top="0";cDv.sY.width="100%";//fix IE7
mDv.sY.overflow='hidden';
mDv.sY.left="-"+sC.paddings[2]+"px";
mDv.sY.top="-"+sC.paddings[0]+"px";
sC.zTHeight=fDv.offsetHeight;

sC.getContentWidth=function(){
	var cChilds=cDv.childNodes,maxCWidth=compPad=0;
	for(var i=0;i<cChilds.length;i++){if(cChilds[i].offsetWidth){maxCWidth=Math.max(cChilds[i].offsetWidth,maxCWidth)}};
	sC.cntRSize[0]=((sC.reqS[1]&&!sC.forcedHide[1])||sC.forcedBar[1])?dDv.offsetWidth-sC.barSpace[0]:dDv.offsetWidth;
	sC.cntSize[0]=maxCWidth+padWidthComp;
	return sC.cntSize[0];
	};
sC.getContentHeight=function(){
	sC.cntRSize[1]=((sC.reqS[0]&&!sC.forcedHide[0])||sC.forcedBar[0])?dDv.offsetHeight-sC.barSpace[1]:dDv.offsetHeight;
	sC.cntSize[1]=cDv.offsetHeight+padHeightComp-2;
	return sC.cntSize[1];
	};

sC.fixIEDispBug=function(){cDv.sY.display='none';cDv.sY.display='block';};
sC.setWidth=function(){mDv.sY.width=(stdMode)?(sC.cntRSize[0]-padWidthComp-brdWidthLoss)+'px':sC.cntRSize[0]+'px';};
sC.setHeight=function(){mDv.sY.height=(stdMode)?(sC.cntRSize[1]-padHeightComp-brdHeightLoss)+'px':sC.cntRSize[1]+'px';};

sC.createScrollBars=function(){
	sC.getContentWidth();sC.getContentHeight();
	//vert
	tDv.vrt=new Array();var vrT=tDv.vrt;
	createScrollBars(vrT,'vscroller');
	vrT.barPadding=[parseInt(getStyle(vrT.sBr,'padding-top')),parseInt(getStyle(vrT.sBr,'padding-bottom'))];
	vrT.sBr.sY.padding='0px';vrT.sBr.curPos=0;vrT.sBr.vertical=true;
	vrT.sBr.indx=1; cDv.vBar=vrT.sBr;
	prepareScroll(vrT,0);sC.barSpace[0]=vrT.sDv.offsetWidth;sC.setWidth('17px');
	//horiz
	tDv.hrz=new Array();var hrZ=tDv.hrz;
	createScrollBars(hrZ,'hscroller');
	hrZ.barPadding=[parseInt(getStyle(hrZ.sBr,'padding-left')),parseInt(getStyle(hrZ.sBr,'padding-right'))];
	hrZ.sBr.sY.padding='0px';hrZ.sBr.curPos=0;hrZ.sBr.vertical=false;
	hrZ.sBr.indx=0; cDv.hBar=hrZ.sBr;
	if(wD.opera) hrZ.sBr.sY.position='relative';
	prepareScroll(hrZ,0);
	sC.barSpace[1]=hrZ.sDv.offsetHeight;sC.setHeight();
	tDv.sY.height=dDv.offsetHeight+'px';
	// jog
	hrZ.jBox=createDiv('scrollerjogbox');
	tDv.appendChild(hrZ.jBox);hrZ.jBox.onmousedown=function(){
		hrZ.sBr.scrollBoth=true;sC.goScroll=hrZ.sBr;hrZ.sBr.clicked=true;
		hrZ.sBr.moved=false;tDv.vrt.sBr.moved=false;
		fleXenv.addTrggr(dC,'selectstart',retFalse);fleXenv.addTrggr(dC,'mousemove',mMoveBar);fleXenv.addTrggr(dC,'mouseup',mMouseUp);
		return false;
	};
};

sC.goScroll=null;
sC.createScrollBars();
cDv.removeChild(iDv);

if(!this.addChckTrggr(dDv,'mousewheel',mWheelProc)||!this.addChckTrggr(dDv,'DOMMouseScroll',mWheelProc)){dDv.onmousewheel=mWheelProc;};
this.addChckTrggr(dDv,'mousewheel',mWheelProc);
this.addChckTrggr(dDv,'DOMMouseScroll',mWheelProc);
dDv.setAttribute('tabIndex','0');

this.addTrggr(dDv,'keydown',function(e){
	if(dDv.focusProtect) return;
	if(!e){var e=wD.event;};var pK=e.keyCode; sC.pkeY=pK;
	sC.mDPosFix();
	if(sC.keyAct['_'+pK]&&!window.opera){dDv.contentScroll(sC.keyAct['_'+pK][0],sC.keyAct['_'+pK][1],true);if(e.preventDefault) e.preventDefault();return false;};
	});
this.addTrggr(dDv,'keypress',function(e){//make Opera Happy
	if(dDv.focusProtect) return;
	if(!e){var e=wD.event;};var pK=e.keyCode;
	if(sC.keyAct['_'+pK]){dDv.contentScroll(sC.keyAct['_'+pK][0],sC.keyAct['_'+pK][1],true);if(e.preventDefault) e.preventDefault();return false;};
});

this.addTrggr(dDv,'keyup',function(){sC.pkeY=false});

this.addTrggr(dC,'mouseup',intClear);
this.addTrggr(dDv,'mousedown',function(e){
	if(!e) e=wD.event;
	var cTrgt=(e.target)?e.target:(e.srcElement)?e.srcElement:false;
	if(!cTrgt||(cTrgt.className&&cTrgt.className.match(RegExp("\\bscrollgeneric\\b")))) return;
	sC.inMposX=e.clientX;sC.inMposY=e.clientY;
	pageScrolled();findPos(dDv);intClear();
	fleXenv.addTrggr(dC,'mousemove',tSelectMouse);
	sC.mTBox=[dDv.xPos+10,dDv.xPos+sC.cntRSize[0]-10,dDv.yPos+10,dDv.yPos+sC.cntRSize[1]-10];
});

function tSelectMouse(e){if(!e) e=wD.event;
	var mX=e.clientX,mY=e.clientY,mdX=mX+sC.xScrld,mdY=mY+sC.yScrld;
	sC.mOnXEdge=(mdX<sC.mTBox[0]||mdX>sC.mTBox[1])?1:0;
	sC.mOnYEdge=(mdY<sC.mTBox[2]||mdY>sC.mTBox[3])?1:0;
	sC.xAw=mX-sC.inMposX;sC.yAw=mY-sC.inMposY;
	sC.sXdir=(sC.xAw>40)?1:(sC.xAw<-40)?-1:0;sC.sYdir=(sC.yAw>40)?1:(sC.yAw<-40)?-1:0;
	if((sC.sXdir!=0||sC.sYdir!=0)&&!sC.tSelectFunc) sC.tSelectFunc=wD.setInterval(function(){
		if(sC.sXdir==0&&sC.sYdir==0){wD.clearInterval(sC.tSelectFunc);sC.tSelectFunc=false;return;};pageScrolled();
		if(sC.mOnXEdge==1||sC.mOnYEdge==1) dDv.contentScroll((sC.sXdir*sC.mOnXEdge)+"s",(sC.sYdir*sC.mOnYEdge)+"s",true);
	},45)
};
function intClear(){
	fleXenv.remTrggr(dC,'mousemove',tSelectMouse);if(sC.tSelectFunc) wD.clearInterval(sC.tSelectFunc);sC.tSelectFunc=false;
	if(sC.barClickRetard) wD.clearTimeout(sC.barClickRetard); if(sC.barClickScroll) wD.clearInterval(sC.barClickScroll);
};
function pageScrolled(){
	sC.xScrld=(wD.pageXOffset)?wD.pageXOffset:(dC.documentElement&&dC.documentElement.scrollLeft)?dC.documentElement.scrollLeft:0;
	sC.yScrld=(wD.pageYOffset)?wD.pageYOffset:(dC.documentElement&&dC.documentElement.scrollTop)?dC.documentElement.scrollTop:0;
};

dDv.scrollUpdate=function(recurse){
	tDv.fShow();
	if(tDv.getSize[1]()===0||tDv.getSize[0]()===0) return;
	cDv.sY.padding='1px';var reqH=sC.reqS[0],reqV=sC.reqS[1],vBr=tDv.vrt,hBr=tDv.hrz,vUpReq,hUpReq,cPSize=[];
	tDv.sY.width=dDv.offsetWidth-brdWidthLoss+'px';tDv.sY.height=dDv.offsetHeight-brdHeightLoss+'px';
	cPSize[0]=sC.cntRSize[0];cPSize[1]=sC.cntRSize[1];
	sC.reqS[0]=sC.getContentWidth()>sC.cntRSize[0];
	sC.reqS[1]=sC.getContentHeight()>sC.cntRSize[1];
	var stateChange=(reqH!=sC.reqS[0]||reqV!=sC.reqS[1]||cPSize[0]!=sC.cntRSize[0]||cPSize[1]!=sC.cntRSize[1])?true:false;
	vBr.sDv.setVisibility(sC.reqS[1]);hBr.sDv.setVisibility(sC.reqS[0]);
	vUpReq=(sC.reqS[1]||sC.forcedBar[1]);hUpReq=(sC.reqS[0]||sC.forcedBar[0]);
	sC.getContentWidth();sC.getContentHeight();sC.setHeight();sC.setWidth();
	if(!sC.reqS[0]||!sC.reqS[1]||sC.forcedHide[0]||sC.forcedHide[1]) hBr.jBox.fHide();
	else hBr.jBox.fShow();
	if(vUpReq) updateScroll(vBr,(hUpReq&&!sC.forcedHide[0])?sC.barSpace[1]:0);else cDv.sY.top="0";
	if(hUpReq) updateScroll(hBr,(vUpReq&&!sC.forcedHide[1])?sC.barSpace[0]:0);else cDv.sY.left="0";
	if(stateChange&&!recurse) dDv.scrollUpdate(true);
	cDv.sY.padding='0px';
	sC.edge[0]=sC.edge[1]=false;
};

dDv.commitScroll=dDv.contentScroll=function(xPos,yPos,relative){
	var reT=[[false,false],[false,false]],Bar;
	if((xPos||xPos===0)&&sC.scroller[0]){xPos=calcCScrollVal(xPos,0);Bar=tDv.hrz.sBr;Bar.trgtScrll=(relative)?Math.min(Math.max(Bar.mxScroll,Bar.trgtScrll-xPos),0):-xPos;Bar.contentScrollPos();reT[0]=[-Bar.trgtScrll-Bar.targetSkew,-Bar.mxScroll]}
	if((yPos||yPos===0)&&sC.scroller[1]){yPos=calcCScrollVal(yPos,1);Bar=tDv.vrt.sBr;Bar.trgtScrll=(relative)?Math.min(Math.max(Bar.mxScroll,Bar.trgtScrll-yPos),0):-yPos;Bar.contentScrollPos();reT[1]=[-Bar.trgtScrll-Bar.targetSkew,-Bar.mxScroll]}
	if(!relative) sC.edge[0]=sC.edge[1]=false;
	return reT;
};

dDv.scrollToElement=function(tEM){
if(tEM==null||!isddvChild(tEM)) return;
var sPos=findRCpos(tEM);
dDv.contentScroll(sPos[0]+sC.paddings[2],sPos[1]+sC.paddings[0],false);
dDv.contentScroll(0,0,true);
};

copyStyles(pDv,dDv,'0px',['border-left-width','border-right-width','border-top-width','border-bottom-width']);

dDv.removeChild(pDv);
dDv.scrollTop=0;dDv.scrollLeft=0;
dDv.fleXcroll=true;
classChange(dDv,'flexcrollactive',false);
dDv.scrollUpdate();
dDv.contentScroll(oScrollX,oScrollY,true);
if(urlBase.match(uReg)) {dDv.scrollToElement(dC.getElementById(urlBase.match(uReg)[1]));};
//tDv.fShow();

sC.sizeChangeDetect=wD.setInterval(function(){
var n=fDv.offsetHeight;if(n!=sC.zTHeight){dDv.scrollUpdate();sC.zTHeight=n};
},2500);

function calcCScrollVal(v,i){
	var stR=v.toString();v=parseFloat(stR);
	return parseInt((stR.match(/p$/))?v*sC.cntRSize[i]*0.9:(stR.match(/s$/))?v*sC.cntRSize[i]*0.1:v);
}
function camelConv(spL){
	var spL=spL.split('-'),reT=spL[0],i;
	for(i=1;parT=spL[i];i++) {reT +=parT.charAt(0).toUpperCase()+parT.substr(1);}
	return reT;
}
function getStyle(elem,style){
	if(wD.getComputedStyle) return wD.getComputedStyle(elem,null).getPropertyValue(style);
	if(elem.currentStyle) return elem.currentStyle[camelConv(style)];
	return false;
};

function copyStyles(src,dest,replaceStr,sList){
	var camelList = new Array();
	for (var i=0;i<sList.length;i++){
		camelList[i]=camelConv(sList[i]);
		dest.style[camelList[i]] = getStyle(src,sList[i],camelList[i]);
		if(replaceStr) src.style[camelList[i]] = replaceStr;
	}
};
function createDiv(typeName,noGenericClass){
	var nDiv=dC.createElement('div');//,pTx=dC.createTextNode('\u00a0');
	nDiv.id=targetId+'_'+typeName;
	nDiv.className=(noGenericClass)?typeName:typeName+' scrollgeneric';
	nDiv.getSize=[function(){return nDiv.offsetWidth;},function(){return nDiv.offsetHeight;}];
	nDiv.setSize=[function(sVal){nDiv.sY.width=sVal;},function(sVal){nDiv.sY.height=sVal;}];
	nDiv.getPos=[function(){return getStyle(nDiv,"left");},function(){return getStyle(nDiv,"top");}];
	nDiv.setPos=[function(sVal){nDiv.sY.left=sVal;},function(sVal){nDiv.sY.top=sVal;}];
	nDiv.fHide=function(){nDiv.sY.visibility="hidden"};
	nDiv.fShow=function(coPy){nDiv.sY.visibility=(coPy)?getStyle(coPy,'visibility'):"visible"};
	nDiv.sY=nDiv.style;
//	if(!noGenericClass) nDiv.appendChild(pTx);
	return nDiv;
	
};
function createScrollBars(ary,bse){
	ary.sDv=createDiv(bse+'base');ary.sFDv=createDiv(bse+'basebeg');
	ary.sSDv=createDiv(bse+'baseend');ary.sBr=createDiv(bse+'bar');
	ary.sFBr=createDiv(bse+'barbeg');ary.sSBr=createDiv(bse+'barend');
	tDv.appendChild(ary.sDv);ary.sDv.appendChild(ary.sBr);
	ary.sDv.appendChild(ary.sFDv);ary.sDv.appendChild(ary.sSDv);
	ary.sBr.appendChild(ary.sFBr);ary.sBr.appendChild(ary.sSBr);
};
function prepareScroll(bAr,reqSpace){
	var sDv=bAr.sDv,sBr=bAr.sBr,i=sBr.indx;
	sBr.minPos=bAr.barPadding[0];
	sBr.ofstParent=sDv;
	sBr.mDv=mDv;
	sBr.scrlTrgt=cDv;
	sBr.targetSkew=0;
	updateScroll(bAr,reqSpace,true);
	
	sBr.doScrollPos=function(){
		sBr.curPos=(Math.min(Math.max(sBr.curPos,0),sBr.maxPos));
		sBr.trgtScrll=parseInt((sBr.curPos/sBr.sRange)*sBr.mxScroll);
		sBr.targetSkew=(sBr.curPos==0)?0:(sBr.curPos==sBr.maxPos)?0:sBr.targetSkew;
		sBr.setPos[i](sBr.curPos+sBr.minPos+"px");
		cDv.setPos[i](sBr.trgtScrll+sBr.targetSkew+"px");
	};
	
	sBr.contentScrollPos=function(){
		sBr.curPos=parseInt((sBr.trgtScrll*sBr.sRange)/sBr.mxScroll);
		sBr.targetSkew=sBr.trgtScrll-parseInt((sBr.curPos/sBr.sRange)*sBr.mxScroll);
		sBr.curPos=(Math.min(Math.max(sBr.curPos,0),sBr.maxPos));
		sBr.setPos[i](sBr.curPos+sBr.minPos+"px");
		sBr.setPos[i](sBr.curPos+sBr.minPos+"px");
		cDv.setPos[i](sBr.trgtScrll+"px");
	};
	
	sC.barZ=getStyle(sBr,'z-index');
	sBr.sY.zIndex=(sC.barZ=="auto"||sC.barZ=="0"||sC.barZ=='normal')?2:sC.barZ;
	mDv.sY.zIndex=getStyle(sBr,'z-index');

	sBr.onmousedown=function(){
		sBr.clicked=true;sC.goScroll=sBr;sBr.scrollBoth=false;sBr.moved=false;
		fleXenv.addTrggr(dC,'selectstart',retFalse);
		fleXenv.addTrggr(dC,'mousemove',mMoveBar);
		fleXenv.addTrggr(dC,'mouseup',mMouseUp);
		return false;
		};
	
	sBr.onmouseover=intClear;
	
	 sDv.onmousedown=sDv.ondblclick=function(e){
		if(!e){var e=wD.event;}
		if(e.target&&(e.target==bAr.sFBr||e.target==bAr.sSBr||e.target==bAr.sBr)) return;
		if(e.srcElement&&(e.srcElement==bAr.sFBr||e.srcElement==bAr.sSBr||e.srcElement==bAr.sBr)) return;
		var relPos,mV=[];pageScrolled();
		sC.mDPosFix();
		findPos(sBr);
		relPos=(sBr.vertical)?e.clientY+sC.yScrld-sBr.yPos:e.clientX+sC.xScrld-sBr.xPos;
		mV[sBr.indx]=(relPos<0)?sC.baseAct[0]:sC.baseAct[1];mV[1-sBr.indx]=0;
		dDv.contentScroll(mV[0],mV[1],true);
		if(e.type!="dblclick") {
		intClear();
		sC.barClickRetard=wD.setTimeout(function(){
		sC.barClickScroll=wD.setInterval(function(){
		dDv.contentScroll(mV[0],mV[1],true);},80)},425);
		}
		return false;
	};
	sDv.setVisibility=function(r){
		if(r){sDv.fShow(dDv);
		sC.forcedHide[i]=(getStyle(sDv,"visibility")=="hidden")?true:false;
		if(!sC.forcedHide[i]) sBr.fShow(dDv); else sBr.fHide();
		sC.scroller[i]=true;classChange(sDv,"","flexinactive");
		}
		else{sDv.fHide();sBr.fHide();
		sC.forcedBar[i]=(getStyle(sDv,"visibility")!="hidden")?true:false;
		sC.scroller[i]=false;sBr.curPos=0;cDv.setPos[i]('0px');
		classChange(sDv,"flexinactive","");}
		mDv.setPos[1-i]((sC.forcedPos[i]&&(r||sC.forcedBar[i])&&!sC.forcedHide[i])?sC.barSpace[1-i]-sC.paddings[i*2]+"px":"-"+sC.paddings[i*2]+"px");
	};
	sDv.onmouseclick = retFalse;
};

function updateScroll(bAr,reqSpace,firstRun){
	var sDv=bAr.sDv,sBr=bAr.sBr,sFDv=bAr.sFDv,sFBr=bAr.sFBr,sSDv=bAr.sSDv,sSBr=bAr.sSBr,i=sBr.indx;
	sDv.setSize[i](tDv.getSize[i]()-reqSpace+'px');sDv.setPos[1-i](tDv.getSize[1-i]()-sDv.getSize[1-i]()+'px');
	sC.forcedPos[i]=(parseInt(sDv.getPos[1-i]())===0)?true:false;
	bAr.padLoss=bAr.barPadding[0]+bAr.barPadding[1];bAr.baseProp=parseInt((sDv.getSize[i]()-bAr.padLoss)*0.75);
	sBr.aSize=Math.min(Math.max(Math.min(parseInt(sC.cntRSize[i]/sC.cntSize[i]*sDv.getSize[i]()),bAr.baseProp),45),bAr.baseProp);
	sBr.setSize[i](sBr.aSize+'px');sBr.maxPos=sDv.getSize[i]()-sBr.getSize[i]()-bAr.padLoss;
	sBr.curPos=Math.min(Math.max(0,sBr.curPos),sBr.maxPos);
	sBr.setPos[i](sBr.curPos+sBr.minPos+'px');sBr.mxScroll=mDv.getSize[i]()-sC.cntSize[i];
	sBr.sRange=sBr.maxPos;
	sFDv.setSize[i](sDv.getSize[i]()-sSDv.getSize[i]()+'px');
	sFBr.setSize[i](sBr.getSize[i]()-sSBr.getSize[i]()+'px');
	sSBr.setPos[i](sBr.getSize[i]()-sSBr.getSize[i]()+'px');
	sSDv.setPos[i](sDv.getSize[i]()-sSDv.getSize[i]()+'px');
	if(!firstRun) sBr.doScrollPos();
	sC.fixIEDispBug();
};

sC.mDPosFix=function(){mDv.scrollTop=0;mDv.scrollLeft=0;dDv.scrollTop=0;dDv.scrollLeft=0;};

this.addTrggr(wD,'load',function(){if(dDv.fleXcroll) dDv.scrollUpdate();});
this.addTrggr(wD,'resize',function(){
if(dDv.refreshTimeout) wD.clearTimeout(dDv.refreshTimeout);
dDv.refreshTimeout=wD.setTimeout(function(){if(dDv.fleXcroll) dDv.scrollUpdate();},80);
});

for(var j=0,inputName;inputName=focusProtectList[j];j++){
	var inputList=dDv.getElementsByTagName(inputName);
	for(var i=0,formItem;formItem=inputList[i];i++){
	fleXenv.addTrggr(formItem,'focus',function(){dDv.focusProtect=true;});
	fleXenv.addTrggr(formItem,'blur',onblur=function(){dDv.focusProtect=false;});
}};

function retFalse(){return false;};
function mMoveBar(e){
if(!e){var e=wD.event;};
var FCBar=sC.goScroll,movBr,maxx,xScroll,yScroll;
if(FCBar==null) return;
if(!fleXenv.w3events&&!e.button) mMouseUp();
maxx=(FCBar.scrollBoth)?2:1;
for (var i=0;i<maxx;i++){
	movBr=(i==1)?FCBar.scrlTrgt.vBar:FCBar;
	if(FCBar.clicked){
	if(!movBr.moved){
	sC.mDPosFix();
	findPos(movBr);findPos(movBr.ofstParent);movBr.pointerOffsetY=e.clientY-movBr.yPos;
	movBr.pointerOffsetX=e.clientX-movBr.xPos;movBr.inCurPos=movBr.curPos;movBr.moved=true;
	};
	movBr.curPos=(movBr.vertical)?e.clientY-movBr.pointerOffsetY-movBr.ofstParent.yPos-movBr.minPos:e.clientX-movBr.pointerOffsetX-movBr.ofstParent.xPos-movBr.minPos;
	if(FCBar.scrollBoth) movBr.curPos=movBr.curPos+(movBr.curPos-movBr.inCurPos);
	movBr.doScrollPos();
	} else movBr.moved=false;
	};
};

function mMouseUp(){
	if(sC.goScroll!=null){sC.goScroll.clicked=false;}
	sC.goScroll=null;
	fleXenv.remTrggr(dC,'selectstart',retFalse);
	fleXenv.remTrggr(dC,'mousemove',mMoveBar);
	fleXenv.remTrggr(dC,'mouseup',mMouseUp);
};

function mWheelProc(e){
	if(!e) e=wD.event;
	if(!this.fleXcroll) return;
	var scrDv=this,vEdge,hEdge,hoverH=false,delta=0,iNDx;
	sC.mDPosFix();
	hElem=(e.target)?e.target:(e.srcElement)?e.srcElement:this;
	if(hElem.id&&hElem.id.match(/_hscroller/)) hoverH=true;
	if(e.wheelDelta) delta=-e.wheelDelta;if(e.detail) delta=e.detail;
	delta=(delta<0)?-1:+1;iNDx=(delta<0)?0:1;sC.edge[1-iNDx]=false;
	if((sC.edge[iNDx]&&!hoverH)||(!sC.scroller[0]&&!sC.scroller[1])) return;
	if(sC.scroller[1]&&!hoverH) scrollState=dDv.contentScroll(false,sC.wheelAct[iNDx],true);
	vEdge=!sC.scroller[1]||hoverH||(sC.scroller[1]&&((scrollState[1][0]==scrollState[1][1]&&delta>0)||(scrollState[1][0]==0&&delta<0)));
	if(sC.scroller[0]&&(!sC.scroller[1]||hoverH)) scrollState=dDv.contentScroll(sC.wheelAct[iNDx],false,true);
	hEdge=!sC.scroller[0]||(sC.scroller[0]&&sC.scroller[1]&&vEdge&&!hoverH)||(sC.scroller[0]&&((scrollState[0][0]==scrollState[0][1]&&delta>0)||(scrollState[0][0]==0&&delta<0)));
	if(vEdge&&hEdge&&!hoverH) sC.edge[iNDx]=true; else sC.edge[iNDx]=false;
	if(e.preventDefault) e.preventDefault();
	return false;
};

function isddvChild(elem){while(elem.parentNode){elem=elem.parentNode;if(elem==dDv) return true;}	return false;};

function findPos(elem){ 
//modified from firetree.net
	var obj=elem,curleft=curtop=0;
	var monc="";
	if(obj.offsetParent){while(obj){curleft+=obj.offsetLeft;curtop+=obj.offsetTop;obj=obj.offsetParent; monc+=curtop+" ";}}
	else if(obj.x){curleft+=obj.x;curtop+=obj.y;}
	elem.xPos=curleft;elem.yPos=curtop;
};

function findRCpos(elem){
	var obj=elem;curleft=curtop=0;
	while(!obj.offsetHeight&&obj.parentNode&&obj!=cDv&&getStyle(obj,'display')=="inline"){obj=obj.parentNode;}
	if(obj.offsetParent){while(obj!=cDv){curleft+=obj.offsetLeft;curtop+=obj.offsetTop;obj=obj.offsetParent;}}
	return [curleft,curtop];
};

function classChange(elem,addClass,remClass) {
	if (!elem.className) elem.className = '';
	var clsnm = elem.className;
	if (addClass && !clsnm.match(RegExp("(^|\\s)"+addClass+"($|\\s)"))) clsnm = clsnm.replace(/(\S$)/,'$1 ')+addClass;
	if (remClass) clsnm = clsnm.replace(RegExp("((^|\\s)+"+remClass+")+($|\\s)","g"),'$2').replace(/\s$/,'');
	elem.className=clsnm;
	};
},
//main code end
globalInit:function(){
if(fleXenv.catchFastInit) window.clearInterval(fleXenv.catchFastInit);
var regg=/#([^#.]*)$/,urlExt=/(.*)#.*$/,matcH,i,anchoR,anchorList=document.getElementsByTagName("a"),urlBase=document.location.href;
if(urlBase.match(urlExt)) urlBase=urlBase.match(urlExt)[1];
for(i=0;anchoR=anchorList[i];i++){
if(anchoR.href&&anchoR.href.match(regg)&&anchoR.href.match(urlExt)&&urlBase===anchoR.href.match(urlExt)[1]) {
	anchoR.fleXanchor=true;
	fleXenv.addTrggr(anchoR,'click',function(e){
		if(!e) e=window.event;
		var clickeD=(e.srcElement)?e.srcElement:this;
		while(!clickeD.fleXanchor&&clickeD.parentNode){clickeD=clickeD.parentNode};
		if(!clickeD.fleXanchor) return;
		var tEL=document.getElementById(clickeD.href.match(regg)[1]),eScroll=false;
		if(tEL==null) tEL=(tEL=document.getElementsByName(clickeD.href.match(regg)[1])[0])?tEL:null;
		if(tEL!=null){
		var elem=tEL;
		while(elem.parentNode){
			elem=elem.parentNode;if(elem.scrollToElement){
				elem.scrollToElement(tEL);eScroll=elem;
				};
			};
		if(eScroll) {if(e.preventDefault) e.preventDefault();document.location.href="#"+clickeD.href.match(regg)[1];eScroll.fleXdata.mDPosFix();return false;}
		};
		});
	};
};
fleXenv.initByClass();
if(window.onfleXcrollRun) window.onfleXcrollRun();
},

initByClass:function(){
if(fleXenv.initialized) return;
fleXenv.initialized=true;
var fleXlist=fleXenv.getByClassName(document.getElementsByTagName("body")[0],"div",'flexcroll');
for (var i=0,tgDiv;tgDiv=fleXlist[i];i++) fleXenv.fleXcrollMain(tgDiv);
},

getByClassName:function(elem,elType,classString){
//v1.1fleX
	if(typeof(elem)=='string') elem=document.getElementById(elem);
	if(elem==null)return false;
	var regExer=new RegExp("(^|\\s)"+classString+"($|\\s)"),clsnm,retArray=[],key=0;
	var elems=elem.getElementsByTagName(elType);
	for(var i=0,pusher;pusher=elems[i];i++){
	if(pusher.className && pusher.className.match(regExer)){
		retArray[key]=pusher;key++;
		}
	};
return retArray;
},

catchFastInit:window.setInterval(function(){
	var dElem=document.getElementById('flexcroll-init');
	if(dElem!=null) {fleXenv.initByClass();window.clearInterval(fleXenv.catchFastInit);}
	},100),

addTrggr:function(elm,eventname,func){if(!fleXenv.addChckTrggr(elm,eventname,func)&&elm.attachEvent) {elm.attachEvent('on'+eventname,func);}},

addChckTrggr:function(elm,eventname,func){if(elm.addEventListener){elm.addEventListener(eventname,func,false);fleXenv.w3events=true;window.addEventListener("unload",function(){fleXenv.remTrggr(elm,eventname,func)},false);return true;} else return false;},

remTrggr:function(elm,eventname,func){if(!fleXenv.remChckTrggr(elm,eventname,func)&&elm.detachEvent) elm.detachEvent('on'+eventname,func);},

remChckTrggr:function(elm,eventname,func){if(elm.removeEventListener){elm.removeEventListener(eventname,func,false);return true;} else return false;}

};

function CSBfleXcroll(targetId){fleXenv.fleXcrollMain(targetId)};
fleXenv.fleXcrollInit();
