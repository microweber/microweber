/*
 * fadeSlideShow
 * v.2.0.0
 *
 * Copyright (c) 2010 Pascal Bajorat (http://www.pascal-bajorat.com)
 * Dual licensed under the MIT
 * and GPL (http://www.gnu.org/licenses/gpl.txt) licenses.
 *
 *
 * http://plugins.jquery.com/project/fadeslideshow
 * http://www.pascal-bajorat.com
 */
jQuery.fn.fadeSlideShow=function(options){return this.each(function(){settings=jQuery.extend({width:640,height:480,speed:'slow',interval:3000,PlayPauseElement:'fssPlayPause',PlayText:'Play',PauseText:'Pause',NextElement:'fssNext',NextElementText:'Next >',PrevElement:'fssPrev',PrevElementText:'< Prev',ListElement:'fssList',ListLi:'fssLi',ListLiActive:'fssActive',addListToId:false,allowKeyboardCtrl:true,autoplay:true},options);jQuery(this).css({width:settings.width,height:settings.height,position:'relative',overflow:'hidden'});jQuery('> *',this).css({position:'absolute',width:settings.width,height:settings.height});Slides=jQuery('> *',this).length;Slides=Slides-1;ActSlide=Slides;jQslide=jQuery('> *',this);fssThis=this;autoplay=function(){intval=setInterval(function(){jQslide.eq(ActSlide).fadeOut(settings.speed);if(settings.ListElement){setActLi=(Slides-ActSlide)+1;if(setActLi>Slides){setActLi=0;}
jQuery('#'+settings.ListElement+' li').removeClass(settings.ListLiActive);jQuery('#'+settings.ListElement+' li').eq(setActLi).addClass(settings.ListLiActive);}
if(ActSlide<=0){jQslide.fadeIn(settings.speed);ActSlide=Slides;}else{ActSlide=ActSlide-1;}},settings.interval);if(settings.PlayPauseElement){jQuery('#'+settings.PlayPauseElement).html(settings.PauseText);}}
stopAutoplay=function(){clearInterval(intval);intval=false;if(settings.PlayPauseElement){jQuery('#'+settings.PlayPauseElement).html(settings.PlayText);}}
jumpTo=function(newIndex){if(newIndex<0){newIndex=Slides;}
else if(newIndex>Slides){newIndex=0;}
if(newIndex>=ActSlide){jQuery('> *:lt('+(newIndex+1)+')',fssThis).fadeIn(settings.speed);}else if(newIndex<=ActSlide){jQuery('> *:gt('+newIndex+')',fssThis).fadeOut(settings.speed);}
ActSlide=newIndex;if(settings.ListElement){jQuery('#'+settings.ListElement+' li').removeClass(settings.ListLiActive);jQuery('#'+settings.ListElement+' li').eq((Slides-newIndex)).addClass(settings.ListLiActive);}}
if(settings.ListElement){i=0;li='';while(i<=Slides){if(i==0){li=li+'<li class="'+settings.ListLi+i+' '+settings.ListLiActive+'"><a href="#">'+(i+1)+'<\/a><\/li>';}else{li=li+'<li class="'+settings.ListLi+i+'"><a href="#">'+(i+1)+'<\/a><\/li>';}
i++;}
List='<ul id="'+settings.ListElement+'">'+li+'<\/ul>';if(settings.addListToId){jQuery('#'+settings.addListToId).append(List);}else{jQuery(this).after(List);}
jQuery('#'+settings.ListElement+' a').bind('click',function(){index=jQuery('#'+settings.ListElement+' a').index(this);stopAutoplay();ReverseIndex=Slides-index;jumpTo(ReverseIndex);return false;});}
if(settings.PlayPauseElement){if(!jQuery('#'+settings.PlayPauseElement).css('display')){jQuery(this).after('<a href="#" id="'+settings.PlayPauseElement+'"><\/a>');}
if(settings.autoplay){jQuery('#'+settings.PlayPauseElement).html(settings.PauseText);}else{jQuery('#'+settings.PlayPauseElement).html(settings.PlayText);}
jQuery('#'+settings.PlayPauseElement).bind('click',function(){if(intval){stopAutoplay();}else{autoplay();}
return false;});}
if(settings.NextElement){if(!jQuery('#'+settings.NextElement).css('display')){jQuery(this).after('<a href="#" id="'+settings.NextElement+'">'+settings.NextElementText+'<\/a>');}
jQuery('#'+settings.NextElement).bind('click',function(){nextSlide=ActSlide-1;stopAutoplay();jumpTo(nextSlide);return false;});}
if(settings.PrevElement){if(!jQuery('#'+settings.PrevElement).css('display')){jQuery(this).after('<a href="#" id="'+settings.PrevElement+'">'+settings.PrevElementText+'<\/a>');}
jQuery('#'+settings.PrevElement).bind('click',function(){prevSlide=ActSlide+1;stopAutoplay();jumpTo(prevSlide);return false;});}
if(settings.allowKeyboardCtrl){jQuery(document).bind('keydown',function(e){if(e.which==39){nextSlide=ActSlide-1;stopAutoplay();jumpTo(nextSlide);}else if(e.which==37){prevSlide=ActSlide+1;stopAutoplay();jumpTo(prevSlide);}else if(e.which==32){if(intval){stopAutoplay();}
else{autoplay();}
return false;}});}
if(settings.autoplay){autoplay();}else{intval=false;}});};