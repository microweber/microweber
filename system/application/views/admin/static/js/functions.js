







$(document).ready(function(){

$(".field_ctrl_plus").each(function(){
    if($(this).parents("li:first").find("ul").length==0){
        $(this).hide();
        $(this).parent().css({paddingLeft:"21px"})
    }
})

$(".field_ctrl_plus").click(function(){
   $(this).parents("li:first").find("ul:first").toggle();
   $(this).toggleClass("field_ctrl_minus");
});


$(".cms_help").click(function(){

    $(".helper").toggle();
    return false;
})

$(window).load(function(){



 $(".cat_tree li").each(function(){
   if($(this).find("li").length>0){
      $(this).addClass("parent_li");
      $(this).find("a:first").addClass("parent_a");
   }
   else{
     $(this).addClass("child_li");
      $(this).find("a:first").addClass("child_a");
   }
 });


   $(".cat_tree li .parent_a").prepend("<span class='tree_folder'>&nbsp;</span>");
   $(".cat_tree li .child_a").prepend("<span class='tree_file'>&nbsp;</span>");

   $(".cat_tree li .parent_a").prepend("<span class='tree_ctrl'>&nbsp;+&nbsp;</span>");

   $(".child_li").each(function(){
      if($(this).parents("li").length==0){
        $(this).addClass("no_parents")
      }
      else{
        $(this).addClass("has_parents")
      }
   });


   $(".tree_ctrl").click(function(event){
      $(this).parents("li:first").find("ul:first").toggle();
      $(this).toggleClass("tree_ctrl_plus")
      mw.prevent(event);
   });

});




    /* Module Info */

var m_info = document.createElement('div');
m_info.id = 'module_info';
m_info.innerHTML = '<h3>Modules Loaded:</h3><ol></ol>';
document.body.appendChild(m_info);
$(".module").each(function(){
   var module = $(this).attr("mw_params_module");
   $("#module_info ol").append("<li>" + module + "</li>")
});
$("#module_info li").click(function(){

  var html = $(this).html();
  $(".module").removeClass("active_m_find");
  $(".module").each(function(){
     var module = $(this).attr("mw_params_module");
     if(module==html){
       $(this).addClass("active_m_find");
     }
  });
});

$(".module").hover(function(){
    var module = $(this).attr("mw_params_module");
    $("#module_info li").removeClass("active_m_find_li")
    $("#module_info li").each(function(){
     var html = $(this).html();
     if(module==html){
       $(this).addClass("active_m_find_li");
     }
  });
}, function(){
     $("#module_info li").removeClass("active_m_find_li")
});

$("body").ajaxStop(function(){
  $("#module_info ol").empty();
$(".module").each(function(){
   var module = $(this).attr("mw_params_module");
   $("#module_info ol").append("<li>" + module + "</li>")
});
$("#module_info li").click(function(){

  var html = $(this).html();
  $(".module").removeClass("active_m_find");
  $(".module").each(function(){
     var module = $(this).attr("mw_params_module");
     if(module==html){
       $(this).addClass("active_m_find");
     }
  });
});

$(".module").hover(function(){
    var module = $(this).attr("mw_params_module");
    $("#module_info li").removeClass("active_m_find_li")
    $("#module_info li").each(function(){
     var html = $(this).html();
     if(module==html){
       $(this).addClass("active_m_find_li");
     }
  });
}, function(){
     $("#module_info li").removeClass("active_m_find_li")
});
})

     /* End Module Info */






 $("a[href='#']").attr("javascript:void(0)");

  $(".mw_box_header").click(function(){


  $(this).parents(".mw_box").find(".mw_box_content").toggleSlide();


  $(this).find(".mw_boxctrl").toggleClass("mw_boxctrl_open");

if($(this).find(".mw_boxctrl").html().indexOf('Open')!=-1){
  $(this).find(".mw_boxctrl").html( 'Close');
}
else if($(this).find(".mw_boxctrl").html().indexOf('Close')!=-1){
  $(this).find(".mw_boxctrl").html('Open');
}


});
  $(".createpages").sortable({
    handle:'.FieldHandle'
  });

  $(".createpages .field").append("<span class='FieldHandle radius'></span>");













$("a").live("mousedown", function(){
  var dis = $(this);
  var left = dis.offset().left;
  var top = dis.offset().top;
  var width = dis.outerWidth();
    $("#preloader").css({
         top:top,
         left:left+width+7
    });
});
$("input").live("mousedown", function(){
  var dis = $(this);
  var left = dis.offset().left;
  var top = dis.offset().top;
  var width = dis.outerWidth();
  var height = dis.outerHeight();
    $("#preloader").css({
         top:top + height + 7,
         left:left+7
    });
});

$("#preloader").ajaxStart(function(){
  $(this).show();
  
  
  setTimeout('$("#preloader").fadeOut()',3000)


})
$("#preloader").ajaxStop(function(){
  $(this).hide();
});



$("input[type='text'], textarea, input[type='password']").each(function(){

  var Default = $(this).attr("default");

        if(Default!=undefined && Default !=''){
          var curr_val = $(this).val();
          if(curr_val==''){
              $(this).val(Default);
          }
          $(this).blur(function(){
             var val = $(this).val();
             var Default = $(this).attr('default');
             if(val==''){
                $(this).val(Default);
              }
          });
          $(this).focus(function(){
              var val = $(this).val();
              var Default = $(this).attr('default');
              if(val==Default){
                $(this).val('');
              }
          });
        }

});

$("#d_bar ul:first").prepend("<li><a href='javascript:content_list(\"\",0);'>All categories</a></li>");

$(".drop").live("click", function(){
   var top = $(this).outerHeight();
   $(this).find(".drop_list").toggle().css("top", top);
   $(this).toggleClass("active");
});
$(".drop").hover(function(){
  $(this).addClass("drop_hover");
}, function(){
  $(this).removeClass("drop_hover");
});
$(document.body).click(function(){
   if($(".drop_hover").length==0){
     $(".drop_list").hide();
     $(".drop").removeClass("active");
   }
});
$(".drop a").live("click", function(){
   var html = $(this).html();
   $(this).parents(".drop").find(".val").html(html);
});

$(".drop li span").live("click", function(){
   var html = $(this).html();
   $(this).parents(".drop").find(".val").html(html);
});



$(document.body).ajaxStop(function(){
   //$(".checkselector").uncheck();
   //mw.ready2('refresh');
});
//$(".checkselector").uncheck();
   // $(".select_all_posts").uncheck();





});// end doc ready



deselect_post = function(id){
   $("#selected_posts #select_" + id).remove();
  // $("#post_" + id + " .checkselector").uncheck();
   if($(".selected_cat_item").length==0){
            $("#posts_cats_controller").hide();
        }
}



posts_categorize = function(elem){
    if($(elem).is(":checked")){
          $(elem).check();
           var title = $(elem).parent().find(".post_title").text();
              var id =  $(elem).parent().find(".post_id").text();
              if($("#selected_posts #select_" + id).length==0){
                 $("#selected_posts").prepend("<div class='selected_cat_item' id='select_" + id + "'><span onclick='deselect_post(" + id + ")'></span>" + title + "</div>");
              }
              $("#posts_cats_controller").show();
     }
     else{
       //$(elem).uncheck();
          var id =  $(elem).parent().find(".post_id").text();
          $("#selected_posts #select_" + id).remove();
          if($(".selected_cat_item").length==0){
              $("#posts_cats_controller").hide();
          }
     }
}


posts_categorize_all = function(elem){
    if(elem.checked==false){
      $("#selected_posts").empty();
      //$(".checkselector").uncheck();
      $("#posts_cats_controller").hide();
      $(elem).parent().find("span").html('Select none');
    }
    if(elem.checked==true){
      $(".checkselector").check();
      $(".checkselector").each(function(){
          posts_categorize(this);
      })
      $(elem).parent().find("span").html('Select all');
    }
}

