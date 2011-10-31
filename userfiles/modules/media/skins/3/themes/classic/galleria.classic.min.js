/*
 Galleria Classic Theme 2011-06-07
 http://galleria.aino.se

 Copyright (c) 2011, Aino
 Licensed under the MIT license.
*/
(function(b){Galleria.addTheme({name:"classic",author:"Galleria",css:"galleria.classic.css",defaults:{transition:"slide",thumbCrop:"height",_toggleInfo:!0},init:function(c){this.addElement("info-link","info-close");this.append({info:["info-link","info-close"]});var d=this.$("info-link,info-close,info-text"),e=Galleria.TOUCH,f=e?"touchstart":"click";this.$("loader,counter").show().css("opacity",0.4);e||(this.addIdleState(this.get("image-nav-left"),{left:-50}),this.addIdleState(this.get("image-nav-right"),
{right:-50}),this.addIdleState(this.get("counter"),{opacity:0}));c._toggleInfo===!0?d.bind(f,function(){d.toggle()}):(d.show(),this.$("info-link, info-close").hide());this.bind("thumbnail",function(a){e?b(a.thumbTarget).css("opacity",a.index==c.show?1:0.6):(b(a.thumbTarget).css("opacity",0.6).parent().hover(function(){b(this).not(".active").children().stop().fadeTo(100,1)},function(){b(this).not(".active").children().stop().fadeTo(400,0.6)}),a.index===c.show&&b(a.thumbTarget).css("opacity",1))});
this.bind("loadstart",function(a){a.cached||this.$("loader").show().fadeTo(200,0.4);this.$("info").toggle(this.hasInfo());b(a.thumbTarget).css("opacity",1).parent().siblings().children().css("opacity",0.6)});this.bind("loadfinish",function(){this.$("loader").fadeOut(200)})}})})(jQuery);