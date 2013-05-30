






team = {
      active : 0,
      flip:function(){
        var curr = _team[team.active], next;
        if(typeof _team[team.active + 1] != 'undefined'){
            team.active ++;
            var next = _team[team.active ];
        }
        else {
           team.active = 0;
           var next = _team[0];

        }
        team.newImage(curr, next);
        team.newContent(curr, next);
      },
      newImage:function(curr, next){
        var a = mwd.createElement("img");
        var b = mwd.createElement("img");
        a.src = mw.$("#mw-team-activator img")[0].src;
        b.src = next.img;
        mw.$("#mw-team-activator .mw-member-image").prepend(b);
        mw.$(".mwmember").eq(team.active).find(".mw-member-image").prepend(a);
        $(a).next().css({opacity:0});
        $(b).next().css({opacity:0});
        setTimeout(function(){
            $(a).next().remove();
            $(b).next().remove();
        }, 300);
      },
      newContent : function(curr, next){

      },
      init : function(){

        setInterval(function(){
            team.flip()
        }, 1000);
      }
};





$(document).ready(function(){



    $("#h-features .box").click(function(){
      if($(this).hasClass("box-deactivated")){
         $("#h-features .box").addClass("box-deactivated");
         $(this).removeClass("box-deactivated");
      }
    });

});