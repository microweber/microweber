

mw.elementRotator = {
    methods: {
        prepare: function(method, holder){ return  mw.elementRotator.methods[method]('prepare', holder); },
        cssFade: function(from, to){
          if(from === 'prepare'){
              $(to).addClass("rotator-method-cssFade").find('.RotatorItem').eq(0).css('opacity', 1);
          }
          else{
            $(from).css("opacity", 0);
            $(to).css("opacity", 1);
          }
        },
        cssSlide:function(from, to){
          if(from === 'prepare'){
              $(to).addClass("rotator-method-cssSlide").find('.RotatorItem').eq(0).css({left:0, opacity : 1});
          }
          else{
            $(from).css({left:"-101%", opacity :0});
            $(to).css({left:0, opacity : 1});
          }
        }
    },
    init : function(holder_node, method){
        var method = typeof mw.elementRotator.methods[method] !== 'undefined' ? method : 'css';
        holder_node.method = method;
        $(holder_node).find(".RotatorItem").eq(0).addClass("active");
        mw.elementRotator.methods.prepare(method, holder_node);
    },
    goto : function(selector, index){
        var rotator = $(selector);
        var active = rotator.find(".active");
        var indexed = typeof index === 'number' ? rotator.find(".RotatorItem").eq(index) : $(index);
        if(!indexed.hasClass("active")){
          active.removeClass("active");
          indexed.addClass("active");
           mw.elementRotator.methods[rotator[0].method](active, indexed);
           if(rotator[0].hasdots == 'true'){
              var index = typeof index === 'number' ? index : mw.tools.index(indexed[0], rotator[0], ".RotatorItem");
              mw.$(rotator[0].dots).removeClass("active").eq(index).addClass("active");
           }
           if(!!rotator[0].navigation){
             $(rotator[0].navigation).removeClass("active").eq(index).addClass("active");
           }
        }
    },
    next:function(selector){
        var rotator = $(selector);
        var active = rotator.find(".active");
        var next = active.next(".RotatorItem").length > 0 ? active.next(".RotatorItem") : rotator.find(".RotatorItem").eq(0);
        mw.elementRotator.goto(selector, next);
    },
    prev:function(selector){
        var rotator = $(selector);
        var active = rotator.find(".active");
        var prev = active.prev(".RotatorItem").length > 0 ? active.prev(".RotatorItem") : rotator.find(".RotatorItem:last");
        mw.elementRotator.goto(selector, prev);
    },
    dots:function(rotator, append_holder){
      if(rotator.hasdots == 'true'){return false;}
      rotator.hasdots = 'true';
      var dots = [];
      rotator.dots = dots;
      $(".RotatorItem", rotator).each(function(i){
        var dot = mwd.createElement('span');
        dot.className = 'dot';
        if(i===0) { dot.className += ' active'} ;
        dot.onclick = function(){
            if(dot.className.indexOf('active') === -1){
               mw.elementRotator.goto(rotator, i);
            }
        }
        dots.push(dot);
      });
      if(typeof append_holder !== 'undefined'){ $(append_holder).append(dots) }
      return dots;
    }
}


mw.utils = {
  stateloading:function(state){
    var state = state || false;
    if(state){
        mw.$("#stateloading").css("visibility", "visible");
        mw.$("#stateloading1,#stateloading2,#stateloading3").addClass("icon-spin");
        $(mwd.body).addClass("loading");
    }
    else{
       mw.$("#stateloading").css("visibility", "hidden");
       mw.$("#stateloading1,#stateloading2,#stateloading3").removeClass("icon-spin");
       $(mwd.body).removeClass("loading");
    }
  },
  supportsAnimation : function(){
    if( typeof mwd.body.style.animationName !== 'undefined' ) { return true; }
    var p = ["Webkit", "Moz", "O", "ms", "Khtml"], l = p.length, i=0;
    for( ; i<l; i++ ){  if( typeof mwd.body.style[p[i]] !== 'undefined' ){ return true; }}
    return false;
  },
  chatToggle:function(){
    if(typeof $zopim === 'undefined') { return false; }
    $zopim.livechat.window.toggle();
  },
  tipcreated:false,
  tip:function(node, html, pos){
    if(!mw.utils.tipcreated){
       mw.utils.tipcreated = true;
       mw.utils.tooltip = mwd.createElement('div');
       mw.utils.tooltip.className = 'tip';
       mw.utils.tooltip.show = function(){
          mw.utils.tooltip.style.display = 'inline-block';
       }
       mw.utils.tooltip.hide = function(){
          mw.utils.tooltip.style.display = 'none';
       }
       mw.utils.tooltip.hide();
       mwd.body.appendChild(mw.utils.tooltip);
    }
    if(node===null){return false;}
    if(node=='hide'){ mw.utils.tooltip.hide(); return false; }
    var html = html || $(node).dataset("tip");
    var pos = pos || $(node).dataset("pos");
    if(html=='' || typeof html=='undefined'){ return false; }
    if(pos=='' || typeof pos=='undefined'){ 'topcenter'; }
    var off = $(node).offset();
    mw.utils.tooltip.innerHTML = html;
    mw.utils.positionTooltip(mw.utils.tooltip, off, pos, node);
    mw.utils.tooltip.show();
  },
  positionTooltip:function(tip, off, pos, el){  //node must have methods .show() & .hide()
    var pos = pos || 'topcenter';
    var tip = tip || mw.utils.tooltip;
    tip.style.opacity = 0;
    tip.show();
    var w = $(tip).outerWidth();
    var h = $(tip).outerHeight();
    var ew = $(el).outerWidth();
    var eh = $(el).outerHeight();
    if(pos == 'topcenter'){
        $(tip).css({
          top:off.top - 12,
          left:off.left + w/2 - ew/2
        });
    }
    else if(pos == ''){

    }

    tip.style.opacity = 1;
    tip.hide();
  }
}




  //   Extending DOCS

  /*

    mw.elementRotator.methods.MyMethod = function(from, to, callback){
         if(from === 'prepare'){
            $(to).find('.RotatorItem').hide().eq(0).show();
        }
        else{
          $(from).hide();
          $(to).show();
        }
    }


  */


  $(mwd).ready(function(){

      mw.tools.dropdown();


      mw.$("#mobile-menu-toggle").bind("click", function(){
        if(mw.$("#header .nav").hasClass("hidden-phone")){
           mw.$("#header .nav").removeClass("hidden-phone")
        }
        else{
           mw.$("#header .nav").addClass("hidden-phone")
        }
      });


     mw.$(".tabs", mwd.body).each(function(){
        var nav = this;
        mw.$("a", nav).bind("click", function(){
          if(!$(this).hasClass("active")){
            mw.$(".active", nav).removeClass("active");
            $(this).addClass("active");
          }
        });
     });



    mw.$(".box-field").each(function(){
      var el = this;
      $(el).bind("mouseup", function(e){
        var c = e.target.className;
        if(mw.tools.hasClass(c, 'box-field') || mw.tools.hasClass(c, 'box') || mw.tools.hasClass(c, 'box-content')){
          mw.$('.invisible-field', e.target).focus();
        }
      });
      mw.$('.invisible-field', el).bind("focus", function(){
        $(el).addClass("focus");
      });
      mw.$('.invisible-field', el).bind("blur", function(){
        $(el).removeClass("focus");
      });
    });


   if('placeholder' in mwd.createElement('input') === false){
       mw.$("[placeholder]").each(function(){
          var el = $(this), p = el.attr("placeholder");
          el.val() == '' ? el.val(p) : '' ;
          el.bind('focus', function(e){ el.val() == p ? el.val('') : ''; });
          el.bind('blur', function(e){ el.val() == '' ? el.val(p) : '';});
       });
    }




   if (typeof CanvasRenderingContext2D === 'undefined'){
        $(mwd.body).addClass('old-browser');
        mw.require(mw.settings.template_url + "oldbrowsers.css");
   }

   if( !mw.utils.supportsAnimation() ){
        $(mwd.body).addClass('no-css-animation');
   }






  });












