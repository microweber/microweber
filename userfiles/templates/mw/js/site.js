
Dimension = 0;


team = {
      thematrix:{
        "0": [0, 1, 2],
        "1": [1, 0, 2],
        "2": [2, 0, 1]
      },
      active : 0,
      flip:function(){
        if(typeof team.thematrix[team.active + 1] !== 'undefined'){
            team.active ++ ;
            var next = team.thematrix[team.active];
        }
        else{
          var next = team.thematrix[0];
          team.active = 0;
        }
        team.newImages(next);
      },
      newImages:function(next){
        var i = 0, l = next.length;
        for( ; i < l; i++){
            var e = next[i];
            var image_holder = mw.$(".mw-member-image").eq(i);
            var src = _team[e].img;
            var img = mwd.createElement('img');
            img.src = src;
            image_holder.prepend(img);
            if( i === 0){
              team.newText(_team[e]);
            }
            $(img).next().css({opacity:0});
            setTimeout(function(){
                image_holder.find("img:last").eq(1).remove();
            }, 620);
        }
      },
      newText:function(i){
        var box = mw.$("#mw-team-activator .box-content");
        var text = i.text;
        box.html(text);
      },
      init : function(){
        setInterval(function(){
            team.flip()
        }, 7000);
      }
};




$(document).ready(function(){
    window.scrollTo(0,1);
    Dimension = $(window).width();

    $("#h-features .box").click(function(){
      if($(this).hasClass("box-deactivated")){
         $("#h-features .box").addClass("box-deactivated");
         $(this).removeClass("box-deactivated");
      }
    });


    mw.$('.action-submit').bind("click keyup", function(e){
      if(e.type == 'click' || e.keyCode == 13){
        $(mw.tools.firstParentWithTag(this, 'form')).submit();
      }
      return false;
    });

   mw.tools.dropdown();


   mw.$(".domain-search-form").bind("mouseup", function(e){
     if(e.target.nodeName == 'DIV' || e.target.nodeName == 'FORM'){
       this.getElementsByTagName('input')[0].focus();
     }
   });


});

$(window).bind("load", function(){
    Dimension = $(window).width();


    if('placeholder' in document.createElement('input') === false){
       mw.$("[placeholder]").each(function(){
          var el = $(this), p = el.attr("placeholder");
          el.val() == '' ? el.val(p) : '' ;
          el.bind('focus', function(e){ el.val() == p ? el.val('') : ''; });
          el.bind('blur', function(e){ el.val() == '' ? el.val(p) : '';});
       });
    }


});
$(window).bind("resize", function(){
    Dimension = $(window).width();
});






