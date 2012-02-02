var mwtree = {
    createButtons:function(itemID){
        var btn = $(itemID);
        var span = document.createElement('span');
        span.className = 'treeControll';
        span.innerHTML = '&nbsp;';
        $(span).click(function(event){
          $(this).parents('li:first').find('ul:first').toggle();
          $(this).toggleClass('treeControllActive');
          event.preventDefault();
        });
        $(span).hover(function(){
          $(this).addClass("treeControllHover");
        }, function(){
          $(this).removeClass("treeControllHover")
        });
        btn.mouseover(function(){
          if($(this).hasClass('Activated')){

          }
          else{
            $(this).addClass('Activated');
            $(this).parent().find('ul:first').show();
            $(span).toggleClass('treeControllActive');
          }

        });
        btn.prepend(span);
        btn.addClass("hasChilds")
    },
    init:function(selector, Maxdepth){
        var tree = $(selector);
        tree.find('li:has(li)').find('a:first').each(function(){
          mwtree.createButtons(this);
        });
        tree.find('li').each(function(i){
           var depth = $(this).parents('li').length;
           if(Maxdepth!=undefined && Maxdepth!=""){
             if ((depth+1)>Maxdepth){
               $(this).parent().hide();
             }
           }
           if($(this).has('ul') && (depth+1)<Maxdepth){
             $(this).find('span:first').addClass('treeControllActive');
             $(this).find('a:first').addClass('Activated');
           }
           $(this).addClass('depth-'+(depth+1));
        });
        //find active
        tree.find("a.active").parents("ul").show();
        tree.find("a.active").parents("ul").prev("a").addClass("Activated").find("span").addClass("treeControllActive");

    }
 }


$(document).ready(function(){





$(window).load(function(){
  setTimeout(function(){
      $(".ooyes_ul_tree_container a").hover(function(){
        $(".bindContextNav").hide();
        var top = $(this).offset().top;
        var left = $(this).offset().left;
        var sub = $(this).parent().find(".bindContextNav:first");
        sub.show().css({
          top:top,
          left:left+230
        });

      }, function(){
        setTimeout(function(){
        $(this).parent().find(".bindContextNav:first").hide();
        }, 1000);
      });

      mwtree.init(".ooyes_ul_tree_container ul:first", 1)
  }, 2000);

})





});