


$(document).ready(function(){



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

  $(".field").hover(function(){
    $(this).find(".FieldHandle").visible();
    if(!$(this).hasClass("active")){
      $(this).find("label").show();
    }

  }, function(){
    $(this).find(".FieldHandle").hidden();
    $(this).find("label").hide();
  });
  $(".field").click(function(){
    $(this).find("input[type='text'], textarea").focus();
  });


  $(".onoff").each(function(){
    var val = $(this).find(":checked").val();
    if(val!=undefined){
      if(val.indexOf('on')!=-1){
          $(this).find(".on").addClass("active");
      }
      else if(val.indexOf('off')!=-1){
          $(this).find(".off").addClass("active");
      }
    }

  });

  $(".onoff span").click(function(){
      var parent = $(this).parent();
      if($(this).hasClass("on")){
          parent.find("input[value*='on']").check();
          parent.find("span").removeClass("active");
          $(this).addClass("active");
      }
      else if($(this).hasClass("off")){
          parent.parent().find("input[value*='off']").check();
          parent.find("span").removeClass("active");
          $(this).addClass("active");
      }
  });

  $(".field input, .field textarea").focus(function(){
    $(".field").removeClass("active")
    $(this).parent().addClass("active");
    $(this).parent().find("label").hide();
  });
  $(".field input, .field textarea").blur(function(){
    $(this).parent().find("label").hide();
    $(this).parent().removeClass("active");
  });





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
   $(".checkselector").uncheck();
   //mw.ready2('refresh');
});
$(".checkselector").uncheck();
    $(".select_all_posts").uncheck();





});// end doc ready



deselect_post = function(id){
   $("#selected_posts #select_" + id).remove();
   $("#post_" + id + " .checkselector").uncheck();
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
       $(elem).uncheck();
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
      $(".checkselector").uncheck();
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

