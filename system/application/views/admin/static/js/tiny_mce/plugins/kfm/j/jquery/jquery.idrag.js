
jQuery.iDrag={helper:null,dragged:null,destroy:function()
{return this.each(function()
{if(this.isDraggable){this.dragCfg.dhe.unbind('mousedown',jQuery.iDrag.draginit);this.dragCfg=null;this.isDraggable=false;if(jQuery.browser.msie){this.unselectable="off";}else{this.style.MozUserSelect='';this.style.KhtmlUserSelect='';this.style.userSelect='';}}});},draginit:function(e)
{if(jQuery.iDrag.dragged!=null){jQuery.iDrag.dragstop(e);return false;}
var elm=this.dragElem;jQuery(document).bind('mousemove',jQuery.iDrag.dragmove).bind('mouseup',jQuery.iDrag.dragstop);elm.dragCfg.pointer=jQuery.iUtil.getPointer(e);elm.dragCfg.currentPointer=elm.dragCfg.pointer;elm.dragCfg.init=false;elm.dragCfg.fromHandler=this!=this.dragElem;jQuery.iDrag.dragged=elm;if(elm.dragCfg.si&&this!=this.dragElem){parentPos=jQuery.iUtil.getPosition(elm.parentNode);sliderSize=jQuery.iUtil.getSize(elm);sliderPos={x:parseInt(jQuery.css(elm,'left'))||0,y:parseInt(jQuery.css(elm,'top'))||0};dx=elm.dragCfg.currentPointer.x-parentPos.x-sliderSize.wb/2-sliderPos.x;dy=elm.dragCfg.currentPointer.y-parentPos.y-sliderSize.hb/2-sliderPos.y;jQuery.iSlider.dragmoveBy(elm,[dx,dy]);}
return jQuery.selectKeyHelper||false;},dragstart:function(e)
{var elm=jQuery.iDrag.dragged;elm.dragCfg.init=true;var dEs=elm.style;elm.dragCfg.oD=jQuery.css(elm,'display');elm.dragCfg.oP=jQuery.css(elm,'position');if(!elm.dragCfg.initialPosition)
elm.dragCfg.initialPosition=elm.dragCfg.oP;elm.dragCfg.oR={x:parseInt(jQuery.css(elm,'left'))||0,y:parseInt(jQuery.css(elm,'top'))||0};elm.dragCfg.diffX=0;elm.dragCfg.diffY=0;if(jQuery.browser.msie){var oldBorder=jQuery.iUtil.getBorder(elm,true);elm.dragCfg.diffX=oldBorder.l||0;elm.dragCfg.diffY=oldBorder.t||0;}
elm.dragCfg.oC=jQuery.extend(jQuery.iUtil.getPosition(elm),jQuery.iUtil.getSize(elm));if(elm.dragCfg.oP!='relative'&&elm.dragCfg.oP!='absolute'){dEs.position='absolute';}
jQuery.iDrag.helper.empty();var clonedEl=elm.cloneNode(true);jQuery(clonedEl).css({display:'block',left:'0px',top:'0px'});clonedEl.style.marginTop='0';clonedEl.style.marginRight='0';clonedEl.style.marginBottom='0';clonedEl.style.marginLeft='0';jQuery.iDrag.helper.append(clonedEl);var dhs=jQuery.iDrag.helper.get(0).style;if(elm.dragCfg.autoSize){dhs.width='auto';dhs.height='auto';}else{dhs.height=elm.dragCfg.oC.hb+'px';dhs.width=elm.dragCfg.oC.wb+'px';}
dhs.display='block';dhs.marginTop='0px';dhs.marginRight='0px';dhs.marginBottom='0px';dhs.marginLeft='0px';jQuery.extend(elm.dragCfg.oC,jQuery.iUtil.getSize(clonedEl));if(elm.dragCfg.cursorAt){if(elm.dragCfg.cursorAt.left){elm.dragCfg.oR.x+=elm.dragCfg.pointer.x-elm.dragCfg.oC.x-elm.dragCfg.cursorAt.left;elm.dragCfg.oC.x=elm.dragCfg.pointer.x-elm.dragCfg.cursorAt.left;}
if(elm.dragCfg.cursorAt.top){elm.dragCfg.oR.y+=elm.dragCfg.pointer.y-elm.dragCfg.oC.y-elm.dragCfg.cursorAt.top;elm.dragCfg.oC.y=elm.dragCfg.pointer.y-elm.dragCfg.cursorAt.top;}
if(elm.dragCfg.cursorAt.right){elm.dragCfg.oR.x+=elm.dragCfg.pointer.x-elm.dragCfg.oC.x-elm.dragCfg.oC.hb+elm.dragCfg.cursorAt.right;elm.dragCfg.oC.x=elm.dragCfg.pointer.x-elm.dragCfg.oC.wb+elm.dragCfg.cursorAt.right;}
if(elm.dragCfg.cursorAt.bottom){elm.dragCfg.oR.y+=elm.dragCfg.pointer.y-elm.dragCfg.oC.y-elm.dragCfg.oC.hb+elm.dragCfg.cursorAt.bottom;elm.dragCfg.oC.y=elm.dragCfg.pointer.y-elm.dragCfg.oC.hb+elm.dragCfg.cursorAt.bottom;}}
elm.dragCfg.nx=elm.dragCfg.oR.x;elm.dragCfg.ny=elm.dragCfg.oR.y;if(elm.dragCfg.insideParent||elm.dragCfg.containment=='parent'){parentBorders=jQuery.iUtil.getBorder(elm.parentNode,true);elm.dragCfg.oC.x=elm.offsetLeft+(jQuery.browser.msie?0:jQuery.browser.opera?-parentBorders.l:parentBorders.l);elm.dragCfg.oC.y=elm.offsetTop+(jQuery.browser.msie?0:jQuery.browser.opera?-parentBorders.t:parentBorders.t);jQuery(elm.parentNode).append(jQuery.iDrag.helper.get(0));}
if(elm.dragCfg.containment){jQuery.iDrag.getContainment(elm);elm.dragCfg.onDragModifier.containment=jQuery.iDrag.fitToContainer;}
if(elm.dragCfg.si){jQuery.iSlider.modifyContainer(elm);}
dhs.left=elm.dragCfg.oC.x-elm.dragCfg.diffX+'px';dhs.top=elm.dragCfg.oC.y-elm.dragCfg.diffY+'px';dhs.width=elm.dragCfg.oC.wb+'px';dhs.height=elm.dragCfg.oC.hb+'px';jQuery.iDrag.dragged.dragCfg.prot=false;if(elm.dragCfg.gx){elm.dragCfg.onDragModifier.grid=jQuery.iDrag.snapToGrid;}
if(elm.dragCfg.zIndex!=false){jQuery.iDrag.helper.css('zIndex',elm.dragCfg.zIndex);}
if(elm.dragCfg.opacity){jQuery.iDrag.helper.css('opacity',elm.dragCfg.opacity);if(window.ActiveXObject){jQuery.iDrag.helper.css('filter','alpha(opacity='+elm.dragCfg.opacity*100+')');}}
if(elm.dragCfg.frameClass){jQuery.iDrag.helper.addClass(elm.dragCfg.frameClass);jQuery.iDrag.helper.get(0).firstChild.style.display='none';}
if(elm.dragCfg.onStart)
elm.dragCfg.onStart.apply(elm,[clonedEl,elm.dragCfg.oR.x,elm.dragCfg.oR.y]);if(jQuery.iDrop&&jQuery.iDrop.count>0){jQuery.iDrop.highlight(elm);}
if(elm.dragCfg.ghosting==false){dEs.display='none';}
return false;},getContainment:function(elm)
{if(elm.dragCfg.containment.constructor==String){if(elm.dragCfg.containment=='parent'){elm.dragCfg.cont=jQuery.extend({x:0,y:0},jQuery.iUtil.getSize(elm.parentNode));var contBorders=jQuery.iUtil.getBorder(elm.parentNode,true);elm.dragCfg.cont.w=elm.dragCfg.cont.wb-contBorders.l-contBorders.r;elm.dragCfg.cont.h=elm.dragCfg.cont.hb-contBorders.t-contBorders.b;}else if(elm.dragCfg.containment=='document'){var clnt=jQuery.iUtil.getClient();elm.dragCfg.cont={x:0,y:0,w:clnt.w,h:clnt.h};}}else if(elm.dragCfg.containment.constructor==Array){elm.dragCfg.cont={x:parseInt(elm.dragCfg.containment[0])||0,y:parseInt(elm.dragCfg.containment[1])||0,w:parseInt(elm.dragCfg.containment[2])||0,h:parseInt(elm.dragCfg.containment[3])||0};}
elm.dragCfg.cont.dx=elm.dragCfg.cont.x-elm.dragCfg.oC.x;elm.dragCfg.cont.dy=elm.dragCfg.cont.y-elm.dragCfg.oC.y;},hidehelper:function(dragged)
{if(dragged.dragCfg.insideParent||dragged.dragCfg.containment=='parent'){jQuery('body',document).append(jQuery.iDrag.helper.get(0));}
jQuery.iDrag.helper.empty().hide().css('opacity',1);if(window.ActiveXObject){jQuery.iDrag.helper.css('filter','alpha(opacity=100)');}},dragstop:function(e)
{jQuery(document).unbind('mousemove',jQuery.iDrag.dragmove).unbind('mouseup',jQuery.iDrag.dragstop);if(jQuery.iDrag.dragged==null){return;}
var dragged=jQuery.iDrag.dragged;jQuery.iDrag.dragged=null;if(dragged.dragCfg.init==false){return false;}
if(dragged.dragCfg.so==true){jQuery(dragged).css('position',dragged.dragCfg.oP);}
var dEs=dragged.style;if(dragged.si){jQuery.iDrag.helper.css('cursor','move');}
if(dragged.dragCfg.frameClass){jQuery.iDrag.helper.removeClass(dragged.dragCfg.frameClass);}
if(dragged.dragCfg.revert==false){if(dragged.dragCfg.fx>0){if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='horizontally'){var x=new jQuery.fx(dragged,{duration:dragged.dragCfg.fx},'left');x.custom(dragged.dragCfg.oR.x,dragged.dragCfg.nRx);}
if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='vertically'){var y=new jQuery.fx(dragged,{duration:dragged.dragCfg.fx},'top');y.custom(dragged.dragCfg.oR.y,dragged.dragCfg.nRy);}}else{if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='horizontally')
dragged.style.left=dragged.dragCfg.nRx+'px';if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='vertically')
dragged.style.top=dragged.dragCfg.nRy+'px';}
jQuery.iDrag.hidehelper(dragged);if(dragged.dragCfg.ghosting==false){jQuery(dragged).css('display',dragged.dragCfg.oD);}}else if(dragged.dragCfg.fx>0){dragged.dragCfg.prot=true;var dh=false;if(jQuery.iDrop&&jQuery.iSort&&dragged.dragCfg.so){dh=jQuery.iUtil.getPosition(jQuery.iSort.helper.get(0));}
jQuery.iDrag.helper.animate({left:dh?dh.x:dragged.dragCfg.oC.x,top:dh?dh.y:dragged.dragCfg.oC.y},dragged.dragCfg.fx,function()
{dragged.dragCfg.prot=false;if(dragged.dragCfg.ghosting==false){dragged.style.display=dragged.dragCfg.oD;}
jQuery.iDrag.hidehelper(dragged);});}else{jQuery.iDrag.hidehelper(dragged);if(dragged.dragCfg.ghosting==false){jQuery(dragged).css('display',dragged.dragCfg.oD);}}
if(jQuery.iDrop&&jQuery.iDrop.count>0){jQuery.iDrop.checkdrop(dragged);}
if(jQuery.iSort&&dragged.dragCfg.so){jQuery.iSort.check(dragged);}
if(dragged.dragCfg.onChange&&(dragged.dragCfg.nRx!=dragged.dragCfg.oR.x||dragged.dragCfg.nRy!=dragged.dragCfg.oR.y)){dragged.dragCfg.onChange.apply(dragged,dragged.dragCfg.lastSi||[0,0,dragged.dragCfg.nRx,dragged.dragCfg.nRy]);}
if(dragged.dragCfg.onStop)
dragged.dragCfg.onStop.apply(dragged);return false;},snapToGrid:function(x,y,dx,dy)
{if(dx!=0)
dx=parseInt((dx+(this.dragCfg.gx*dx/Math.abs(dx))/2)/this.dragCfg.gx)*this.dragCfg.gx;if(dy!=0)
dy=parseInt((dy+(this.dragCfg.gy*dy/Math.abs(dy))/2)/this.dragCfg.gy)*this.dragCfg.gy;return{dx:dx,dy:dy,x:0,y:0};},fitToContainer:function(x,y,dx,dy)
{dx=Math.min(Math.max(dx,this.dragCfg.cont.dx),this.dragCfg.cont.w+this.dragCfg.cont.dx-this.dragCfg.oC.wb);dy=Math.min(Math.max(dy,this.dragCfg.cont.dy),this.dragCfg.cont.h+this.dragCfg.cont.dy-this.dragCfg.oC.hb);return{dx:dx,dy:dy,x:0,y:0}},dragmove:function(e)
{if(jQuery.iDrag.dragged==null||jQuery.iDrag.dragged.dragCfg.prot==true){return;}
var dragged=jQuery.iDrag.dragged;dragged.dragCfg.currentPointer=jQuery.iUtil.getPointer(e);if(dragged.dragCfg.init==false){distance=Math.sqrt(Math.pow(dragged.dragCfg.pointer.x-dragged.dragCfg.currentPointer.x,2)+Math.pow(dragged.dragCfg.pointer.y-dragged.dragCfg.currentPointer.y,2));if(distance<dragged.dragCfg.snapDistance){return;}else{jQuery.iDrag.dragstart(e);}}
var dx=dragged.dragCfg.currentPointer.x-dragged.dragCfg.pointer.x;var dy=dragged.dragCfg.currentPointer.y-dragged.dragCfg.pointer.y;for(var i in dragged.dragCfg.onDragModifier){var newCoords=dragged.dragCfg.onDragModifier[i].apply(dragged,[dragged.dragCfg.oR.x+dx,dragged.dragCfg.oR.y+dy,dx,dy]);if(newCoords&&newCoords.constructor==Object){dx=i!='user'?newCoords.dx:(newCoords.x-dragged.dragCfg.oR.x);dy=i!='user'?newCoords.dy:(newCoords.y-dragged.dragCfg.oR.y);}}
dragged.dragCfg.nx=dragged.dragCfg.oC.x+dx-dragged.dragCfg.diffX;dragged.dragCfg.ny=dragged.dragCfg.oC.y+dy-dragged.dragCfg.diffY;if(dragged.dragCfg.si&&(dragged.dragCfg.onSlide||dragged.dragCfg.onChange)){jQuery.iSlider.onSlide(dragged,dragged.dragCfg.nx,dragged.dragCfg.ny);}
if(dragged.dragCfg.onDrag)
dragged.dragCfg.onDrag.apply(dragged,[dragged.dragCfg.oR.x+dx,dragged.dragCfg.oR.y+dy]);if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='horizontally'){dragged.dragCfg.nRx=dragged.dragCfg.oR.x+dx;jQuery.iDrag.helper.get(0).style.left=dragged.dragCfg.nx+'px';}
if(!dragged.dragCfg.axis||dragged.dragCfg.axis=='vertically'){dragged.dragCfg.nRy=dragged.dragCfg.oR.y+dy;jQuery.iDrag.helper.get(0).style.top=dragged.dragCfg.ny+'px';}
if(jQuery.iDrop&&jQuery.iDrop.count>0){jQuery.iDrop.checkhover(dragged);}
return false;},build:function(o)
{if(!jQuery.iDrag.helper){jQuery('body',document).append('<div id="dragHelper"></div>');jQuery.iDrag.helper=jQuery('#dragHelper');var el=jQuery.iDrag.helper.get(0);var els=el.style;els.position='absolute';els.display='none';els.cursor='move';els.listStyle='none';els.overflow='hidden';if(window.ActiveXObject){el.unselectable="on";}else{els.mozUserSelect='none';els.userSelect='none';els.KhtmlUserSelect='none';}}
if(!o){o={};}
return this.each(function()
{if(this.isDraggable||!jQuery.iUtil)
return;if(window.ActiveXObject){this.onselectstart=function(){return false;};this.ondragstart=function(){return false;};}
var el=this;var dhe=o.handle?jQuery(this).find(o.handle):jQuery(this);if(jQuery.browser.msie){dhe.each(function()
{this.unselectable="on";});}else{dhe.css('-moz-user-select','none');dhe.css('user-select','none');dhe.css('-khtml-user-select','none');}
this.dragCfg={dhe:dhe,revert:o.revert?true:false,ghosting:o.ghosting?true:false,so:o.so?o.so:false,si:o.si?o.si:false,insideParent:o.insideParent?o.insideParent:false,zIndex:o.zIndex?parseInt(o.zIndex)||0:false,opacity:o.opacity?parseFloat(o.opacity):false,fx:parseInt(o.fx)||null,hpc:o.hpc?o.hpc:false,onDragModifier:{},pointer:{},onStart:o.onStart&&o.onStart.constructor==Function?o.onStart:false,onStop:o.onStop&&o.onStop.constructor==Function?o.onStop:false,onChange:o.onChange&&o.onChange.constructor==Function?o.onChange:false,axis:/vertically|horizontally/.test(o.axis)?o.axis:false,snapDistance:o.snapDistance?parseInt(o.snapDistance)||0:0,cursorAt:o.cursorAt?o.cursorAt:false,autoSize:o.autoSize?true:false,frameClass:o.frameClass||false};if(o.onDragModifier&&o.onDragModifier.constructor==Function)
this.dragCfg.onDragModifier.user=o.onDragModifier;if(o.onDrag&&o.onDrag.constructor==Function)
this.dragCfg.onDrag=o.onDrag;if(o.containment&&((o.containment.constructor==String&&(o.containment=='parent'||o.containment=='document'))||(o.containment.constructor==Array&&o.containment.length==4))){this.dragCfg.containment=o.containment;}
if(o.fractions){this.dragCfg.fractions=o.fractions;}
if(o.grid){if(typeof o.grid=='number'){this.dragCfg.gx=parseInt(o.grid)||1;this.dragCfg.gy=parseInt(o.grid)||1;}else if(o.grid.length==2){this.dragCfg.gx=parseInt(o.grid[0])||1;this.dragCfg.gy=parseInt(o.grid[1])||1;}}
if(o.onSlide&&o.onSlide.constructor==Function){this.dragCfg.onSlide=o.onSlide;}
this.isDraggable=true;dhe.each(function(){this.dragElem=el;});dhe.bind('mousedown',jQuery.iDrag.draginit);})}};jQuery.fn.extend({DraggableDestroy:jQuery.iDrag.destroy,Draggable:jQuery.iDrag.build});