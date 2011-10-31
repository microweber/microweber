function css_browser_selector(u) {
    var ua = u.toLowerCase(),
        is = function (t) {
            return ua.indexOf(t) > -1
        },
        g = 'gecko',
        w = 'webkit',
        s = 'safari',
        o = 'opera',
        m = 'mobile',
        h = document.documentElement,
        b = [(!(/opera|webtv/i.test(ua)) && /msie\s(\d)/.test(ua)) ? ('ie ie' + RegExp.$1) : is('firefox/2') ? g + ' ff2' : is('firefox/3.5') ? g + ' ff3 ff3_5' : is('firefox/3.6') ? g + ' ff3 ff3_6' : is('firefox/3') ? g + ' ff3'  : is('firefox/4') ? g + ' ff4'  : is('firefox/5') ? g + ' ff5'  : is('firefox/6') ? g + ' ff6' : is('gecko/') ? g : is('opera') ? o + (/version\/(\d+)/.test(ua) ? ' ' + o + RegExp.$1 : (/opera(\s|\/)(\d+)/.test(ua) ? ' ' + o + RegExp.$2 : '')) : is('konqueror') ? 'konqueror' : is('blackberry') ? m + ' blackberry' : is('android') ? m + ' android' : is('chrome') ? w + ' chrome' : is('iron') ? w + ' iron' : is('applewebkit/') ? w + ' ' + s + (/version\/(\d+)/.test(ua) ? ' ' + s + RegExp.$1 : '') : is('mozilla/') ? g : '', is('j2me') ? m + ' j2me' : is('iphone') ? m + ' iphone' : is('ipod') ? m + ' ipod' : is('ipad') ? m + ' ipad' : is('mac') ? 'mac' : is('darwin') ? 'mac' : is('webtv') ? 'webtv' : is('win') ? 'win' + (is('windows nt 6.0') ? ' vista' : '') : is('freebsd') ? 'freebsd' : (is('x11') || is('linux')) ? 'linux' : '', 'js'];
    c = b.join(' ');
    h.className += ' ' + c;
    return c;
};
var browser_info = css_browser_selector(navigator.userAgent);


isobj = function(obj){
    if( obj == undefined){
      return false;
    }
    else{return true}
}

$.fn.multiWrap = function(each, wrapString){
    var results =[];
    var elements = $(this);
    if(elements.length>0){
        $.map(elements, function(i, n){
            if(n%each === 0 ){
                results.push(n);
            }
        });
        $.each(results , function(i,v){
            elements.slice(v, v+each).wrapAll(wrapString);
        });
    }
};


slide = {
  init:function(obj){
    var elem = $(obj.elem);
    var ctrl_left = elem.find(".slide_left:first");
    ctrl_left.hide();
    var ctrl_right = elem.find(".slide_right:first");
    var items = isobj(obj.items)?elem.find(obj.items):elem.find("li");
    var width = (items.length)*(items.outerWidth(true));
    if(width<=items.outerWidth(true)){
        ctrl_left.hide();
        ctrl_right.hide();
    }
    var item_width = items.outerWidth(true);
    var step = isobj(obj.step)?obj.step*item_width:item_width;
    var speed = isobj(obj.speed)?obj.speed:400;
    var engine = elem.find(".slide_engine:first");
    engine.css({
      left:0,
      width:width,
      position:"relative"
    });
    ctrl_left.click(function(){
      var curr = parseFloat(engine.css("left"));
      if(curr<0){
        ctrl_right.show()
         engine.not(":animated").animate({left:curr+step}, speed, function(){
            var curr = parseFloat(engine.css("left"));
            if(curr>=0){
              ctrl_left.hide()
            }
         });
      }
    });
    ctrl_right.click(function(){
      var curr = parseFloat(engine.css("left"));
      var max = width + curr - engine.parent().outerWidth()-item_width-1;
      if(max>0){
         ctrl_left.show();
         engine.not(":animated").animate({left:curr-step}, speed, function(){
             var curr = parseFloat(engine.css("left"));
             var max = width + curr - engine.parent().width()-items.outerWidth(true);
             if(max<=0){
                ctrl_right.hide()
             }
         });
      }
    });
  }
}




// VALIDATE


//Change log:
// added events: valid:function(){....},
// error:function(){....},
// preventSubmit:true, false - the default form submit after validation

//check empty
function require(the_form){
    the_form.find(".required").each(function(){
          if($(this).attr("type")!="checkbox"){
              if($(this).val()=="" ||  $(this).val()==$(this).attr("default")){
                $(this).parent().addClass("error");
              }
              else{
                $(this).parent().removeClass("error");
              }
          }
          else{
            if($(this).attr("checked")==""){
              $(this).parent().addClass("error");
            }
          }
    });
}

//check email
function checkMail(the_form){
      the_form.find(".required-email").each(function(){
          var thismail = $(this);
          var thismailval = $(this).val();
          var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;

          if (regexmail.test(thismailval)){
              thismail.parent().removeClass("error");
          }
          else{
             thismail.parent().addClass("error");
          }
    })
}

function checkNumber(the_form){
      the_form.find(".required-number").each(function(){
          var thisnumber = $(this);
          var thismailval = $(this).val();
          var regexmail = /^[0-9]+$/;

          if (regexmail.test(thismailval)){
              thisnumber.parent().removeClass("error");
          }
          else{
             thisnumber.parent().addClass("error");
          }
    })
}
function checkEqual(the_form){
      the_form.find(".required-equal").each(function(){
          var equalto = $(this).attr("equalto");
          val1 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(0).val();
          val2 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(1).val();
          if(val1!=val2 || val1=='' || val2==''){
              $(this).parents("form").find("[equalto='" + equalto + "']").parent().addClass("error");
          }
          else{
              $(this).parents("form").find("[equalto='" + equalto + "']").parent().removeClass("error");
          }
      });
}


(function($) {
	$.fn.validate = function(options) {
        $(this).each(function(){
            $(this).submit(function(){
                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);
                  checkEqual(oform);

                  //Final check
                  if(oform.find(".error").length>0){
                      oform.addClass("error");
                      valid=false;
                  }
                  else{
                      oform.removeClass("error");
                      valid=true;
                  }
                  oform.addClass("submitet");

                  if(valid==true){
                    if(options.valid!=undefined && typeof options.valid == 'function'){
                       options.valid.call(this);
                       if(options.preventSubmit){
                         return false;
                       }
                    }
                    else{
                      if(options.preventSubmit){
                         return false;
                       }
                    }

                  }
                  else{
                    if(valid==false){
                      if( options.error!=undefined && typeof options.error == 'function'){
                        options.error.call(this);
                        return valid;
                      }
                      else{
                          return valid;
                      }
                    }
                    else{
                      return valid;
                    }

                  }


            });
            $(this).find(".required").bind("keyup blur change mouseup", function(){
                if($(this).parents("form").hasClass("submitet")){
                  if($(this).val()=="" || $(this).val()==$(this).attr("default")){
                    $(this).parent().addClass("error");
                  }
                  else{
                    $(this).parent().removeClass("error");
                  }
                }
            });
            $(this).find(".required-equal").bind("keyup blur change mouseup", function(){
              if($(this).parents("form").hasClass("submitet")){
                  var equalto = $(this).attr("equalto");
                  val1 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(0).val();
                  val2 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(1).val();
                  if(val1!=val2 || val1=='' || val2==''){
                      $(this).parents("form").find("[equalto='" + equalto + "']").parent().addClass("error");
                  }
                  else{
                      $(this).parents("form").find("[equalto='" + equalto + "']").parent().removeClass("error");
                  }
              }
            });

            $(this).find(".required-email").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thismail = $(this);
                  var thismailval = $(this).val();
                  var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                  if (regexmail.test(thismailval)){
                      thismail.parent().removeClass("error");
                  }
                  else{
                     thismail.parent().addClass("error");
                  }
                }
            });

            $(this).find(".required-number").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thisnumber = $(this);
                  var thisnumberval = $(this).val();
                  var regexmail = /^[0-9]+$/;
                  if (regexmail.test(thisnumberval)){
                      thisnumber.parent().removeClass("error");
                  }
                  else{
                     thisnumber.parent().addClass("error");
                  }
                }
            });
        });
    };
})(jQuery);

(function($) {
	$.fn.isValid = function(){
	  var valid=true;
	  $(this).each(function(){
        oform = $(this);
        require(oform);
        checkMail(oform);
        checkNumber(oform);
        checkEqual(oform);

        if(oform.find(".error").length>0){
            oform.addClass("error");
            valid=false;
        }
        else{
            oform.removeClass("error");
            valid=true;
        }
        oform.addClass("submitet");
	  });
      return valid;
    };
})(jQuery);



// ColorBox v1.3.17.2 - a full featured, light-weight, customizable lightbox based on jQuery 1.3+
// Copyright (c) 2011 Jack Moore - jack@colorpowered.com
// Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
(function(a,b,c){function bc(b){if(!U){P=b,_(),y=a(P),Q=0,K.rel!=="nofollow"&&(y=a("."+g).filter(function(){var b=a.data(this,e).rel||this.rel;return b===K.rel}),Q=y.index(P),Q===-1&&(y=y.add(P),Q=y.length-1));if(!S){S=T=!0,r.show();if(K.returnFocus)try{P.blur(),a(P).one(l,function(){try{this.focus()}catch(a){}})}catch(c){}q.css({opacity:+K.opacity,cursor:K.overlayClose?"pointer":"auto"}).show(),K.w=Z(K.initialWidth,"x"),K.h=Z(K.initialHeight,"y"),X.position(),o&&z.bind("resize."+p+" scroll."+p,function(){q.css({width:z.width(),height:z.height(),top:z.scrollTop(),left:z.scrollLeft()})}).trigger("resize."+p),ba(h,K.onOpen),J.add(D).hide(),I.html(K.close).show()}X.load(!0)}}function bb(){var a,b=f+"Slideshow_",c="click."+f,d,e,g;K.slideshow&&y[1]?(d=function(){F.text(K.slideshowStop).unbind(c).bind(j,function(){if(Q<y.length-1||K.loop)a=setTimeout(X.next,K.slideshowSpeed)}).bind(i,function(){clearTimeout(a)}).one(c+" "+k,e),r.removeClass(b+"off").addClass(b+"on"),a=setTimeout(X.next,K.slideshowSpeed)},e=function(){clearTimeout(a),F.text(K.slideshowStart).unbind([j,i,k,c].join(" ")).one(c,d),r.removeClass(b+"on").addClass(b+"off")},K.slideshowAuto?d():e()):r.removeClass(b+"off "+b+"on")}function ba(b,c){c&&c.call(P),a.event.trigger(b)}function _(b){K=a.extend({},a.data(P,e));for(b in K)a.isFunction(K[b])&&b.substring(0,2)!=="on"&&(K[b]=K[b].call(P));K.rel=K.rel||P.rel||"nofollow",K.href=K.href||a(P).attr("href"),K.title=K.title||P.title,typeof K.href=="string"&&(K.href=a.trim(K.href))}function $(a){return K.photo||/\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i.test(a)}function Z(a,b){return Math.round((/%/.test(a)?(b==="x"?z.width():z.height())/100:1)*parseInt(a,10))}function Y(c,d,e){e=b.createElement("div"),c&&(e.id=f+c),e.style.cssText=d||"";return a(e)}var d={transition:"elastic",speed:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,opacity:.9,preloading:!0,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",open:!1,returnFocus:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:!1},e="colorbox",f="cbox",g=f+"Element",h=f+"_open",i=f+"_load",j=f+"_complete",k=f+"_cleanup",l=f+"_closed",m=f+"_purge",n=a.browser.msie&&!a.support.opacity,o=n&&a.browser.version<7,p=f+"_IE6",q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X;X=a.fn[e]=a[e]=function(b,c){var f=this;b=b||{};if(!f[0]){if(f.selector)return f;f=a("<a/>"),b.open=!0}c&&(b.onComplete=c),f.each(function(){a.data(this,e,a.extend({},a.data(this,e)||d,b)),a(this).addClass(g)}),(a.isFunction(b.open)&&b.open.call(f)||b.open)&&bc(f[0]);return f},X.init=function(){z=a(c),r=Y().attr({id:e,"class":n?f+(o?"IE6":"IE"):""}),q=Y("Overlay",o?"position:absolute":"").hide(),s=Y("Wrapper"),t=Y("Content").append(A=Y("LoadedContent","width:0; height:0; overflow:hidden"),C=Y("LoadingOverlay").add(Y("LoadingGraphic")),D=Y("Title"),E=Y("Current"),G=Y("Next"),H=Y("Previous"),F=Y("Slideshow").bind(h,bb),I=Y("Close")),s.append(Y().append(Y("TopLeft"),u=Y("TopCenter"),Y("TopRight")),Y(!1,"clear:left").append(v=Y("MiddleLeft"),t,w=Y("MiddleRight")),Y(!1,"clear:left").append(Y("BottomLeft"),x=Y("BottomCenter"),Y("BottomRight"))).children().children().css({"float":"left"}),B=Y(!1,"position:absolute; width:9999px; visibility:hidden; display:none"),a("body").prepend(q,r.append(s,B)),t.children().hover(function(){a(this).addClass("hover")},function(){a(this).removeClass("hover")}).addClass("hover"),L=u.height()+x.height()+t.outerHeight(!0)-t.height(),M=v.width()+w.width()+t.outerWidth(!0)-t.width(),N=A.outerHeight(!0),O=A.outerWidth(!0),r.css({"padding-bottom":L,"padding-right":M}).hide(),G.click(function(){X.next()}),H.click(function(){X.prev()}),I.click(function(){X.close()}),J=G.add(H).add(E).add(F),t.children().removeClass("hover"),q.click(function(){K.overlayClose&&X.close()}),a(b).bind("keydown."+f,function(a){var b=a.keyCode;S&&K.escKey&&b===27&&(a.preventDefault(),X.close()),S&&K.arrowKey&&y[1]&&(b===37?(a.preventDefault(),H.click()):b===39&&(a.preventDefault(),G.click()))})},X.remove=function(){r.add(q).remove(),a("."+g).removeData(e).removeClass(g)},X.position=function(a,c){function g(a){u[0].style.width=x[0].style.width=t[0].style.width=a.style.width,C[0].style.height=C[1].style.height=t[0].style.height=v[0].style.height=w[0].style.height=a.style.height}var d=0,e=0;z.unbind("resize."+f),r.hide(),K.fixed&&!o?r.css({position:"fixed"}):(d=z.scrollTop(),e=z.scrollLeft(),r.css({position:"absolute"})),K.right!==!1?e+=Math.max(z.width()-K.w-O-M-Z(K.right,"x"),0):K.left!==!1?e+=Z(K.left,"x"):e+=Math.round(Math.max(z.width()-K.w-O-M,0)/2),K.bottom!==!1?d+=Math.max(b.documentElement.clientHeight-K.h-N-L-Z(K.bottom,"y"),0):K.top!==!1?d+=Z(K.top,"y"):d+=Math.round(Math.max(b.documentElement.clientHeight-K.h-N-L,0)/2),r.show(),a=r.width()===K.w+O&&r.height()===K.h+N?0:a||0,s[0].style.width=s[0].style.height="9999px",r.dequeue().animate({width:K.w+O,height:K.h+N,top:d,left:e},{duration:a,complete:function(){g(this),T=!1,s[0].style.width=K.w+O+M+"px",s[0].style.height=K.h+N+L+"px",c&&c(),setTimeout(function(){z.bind("resize."+f,X.position)},1)},step:function(){g(this)}})},X.resize=function(a){if(S){a=a||{},a.width&&(K.w=Z(a.width,"x")-O-M),a.innerWidth&&(K.w=Z(a.innerWidth,"x")),A.css({width:K.w}),a.height&&(K.h=Z(a.height,"y")-N-L),a.innerHeight&&(K.h=Z(a.innerHeight,"y"));if(!a.innerHeight&&!a.height){var b=A.wrapInner("<div style='overflow:auto'></div>").children();K.h=b.height(),b.replaceWith(b.children())}A.css({height:K.h}),X.position(K.transition==="none"?0:K.speed)}},X.prep=function(b){function h(){K.h=K.h||A.height(),K.h=K.mh&&K.mh<K.h?K.mh:K.h;return K.h}function g(){K.w=K.w||A.width(),K.w=K.mw&&K.mw<K.w?K.mw:K.w;return K.w}if(!!S){var c,d=K.transition==="none"?0:K.speed;A.remove(),A=Y("LoadedContent").append(b),A.hide().appendTo(B.show()).css({width:g(),overflow:K.scrolling?"auto":"hidden"}).css({height:h()}).prependTo(t),B.hide(),a(R).css({"float":"none"}),o&&a("select").not(r.find("select")).filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one(k,function(){this.style.visibility="inherit"}),c=function(){function o(){n&&r[0].style.removeAttribute("filter")}var b,c,g,h,i=y.length,k,l;!S||(l=function(){clearTimeout(W),C.hide(),ba(j,K.onComplete)},n&&R&&A.fadeIn(100),D.html(K.title).add(A).show(),i>1?(typeof K.current=="string"&&E.html(K.current.replace("{current}",Q+1).replace("{total}",i)).show(),G[K.loop||Q<i-1?"show":"hide"]().html(K.next),H[K.loop||Q?"show":"hide"]().html(K.previous),b=Q?y[Q-1]:y[i-1],g=Q<i-1?y[Q+1]:y[0],K.slideshow&&F.show(),K.preloading&&(h=a.data(g,e).href||g.href,c=a.data(b,e).href||b.href,h=a.isFunction(h)?h.call(g):h,c=a.isFunction(c)?c.call(b):c,$(h)&&(a("<img/>")[0].src=h),$(c)&&(a("<img/>")[0].src=c))):J.hide(),K.iframe?(k=a("<iframe/>").addClass(f+"Iframe")[0],K.fastIframe?l():a(k).one("load",l),k.name=f+ +(new Date),k.src=K.href,K.scrolling||(k.scrolling="no"),n&&(k.frameBorder=0,k.allowTransparency="true"),a(k).appendTo(A).one(m,function(){k.src="//about:blank"})):l(),K.transition==="fade"?r.fadeTo(d,1,o):o())},K.transition==="fade"?r.fadeTo(d,0,function(){X.position(0,c)}):X.position(d,c)}},X.load=function(b){var c,d,e=X.prep;T=!0,R=!1,P=y[Q],b||_(),ba(m),ba(i,K.onLoad),K.h=K.height?Z(K.height,"y")-N-L:K.innerHeight&&Z(K.innerHeight,"y"),K.w=K.width?Z(K.width,"x")-O-M:K.innerWidth&&Z(K.innerWidth,"x"),K.mw=K.w,K.mh=K.h,K.maxWidth&&(K.mw=Z(K.maxWidth,"x")-O-M,K.mw=K.w&&K.w<K.mw?K.w:K.mw),K.maxHeight&&(K.mh=Z(K.maxHeight,"y")-N-L,K.mh=K.h&&K.h<K.mh?K.h:K.mh),c=K.href,W=setTimeout(function(){C.show()},100),K.inline?(Y().hide().insertBefore(a(c)[0]).one(m,function(){a(this).replaceWith(A.children())}),e(a(c))):K.iframe?e(" "):K.html?e(K.html):$(c)?(a(R=new Image).addClass(f+"Photo").error(function(){K.title=!1,e(Y("Error").text("This image could not be loaded"))}).load(function(){var a;R.onload=null,K.scalePhotos&&(d=function(){R.height-=R.height*a,R.width-=R.width*a},K.mw&&R.width>K.mw&&(a=(R.width-K.mw)/R.width,d()),K.mh&&R.height>K.mh&&(a=(R.height-K.mh)/R.height,d())),K.h&&(R.style.marginTop=Math.max(K.h-R.height,0)/2+"px"),y[1]&&(Q<y.length-1||K.loop)&&(R.style.cursor="pointer",R.onclick=function(){X.next()}),n&&(R.style.msInterpolationMode="bicubic"),setTimeout(function(){e(R)},1)}),setTimeout(function(){R.src=c},1)):c&&B.load(c,K.data,function(b,c,d){e(c==="error"?Y("Error").text("Request unsuccessful: "+d.statusText):a(this).contents())})},X.next=function(){!T&&y[1]&&(Q<y.length-1||K.loop)&&(Q=Q<y.length-1?Q+1:0,X.load())},X.prev=function(){!T&&y[1]&&(Q||K.loop)&&(Q=Q?Q-1:y.length-1,X.load())},X.close=function(){S&&!U&&(U=!0,S=!1,ba(k,K.onCleanup),z.unbind("."+f+" ."+p),q.fadeTo(200,0),r.stop().fadeTo(300,0,function(){r.add(q).css({opacity:1,cursor:"auto"}).hide(),ba(m),A.remove(),setTimeout(function(){U=!1,ba(l,K.onClosed)},1)}))},X.element=function(){return a(P)},X.settings=d,V=function(a){a.button!==0&&typeof a.button!="undefined"||a.ctrlKey||a.shiftKey||a.altKey||(a.preventDefault(),bc(this))},a.fn.delegate?a(b).delegate("."+g,"click",V):a("."+g).live("click",V),a(X.init)})(jQuery,document,this);