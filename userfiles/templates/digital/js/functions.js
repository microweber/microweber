

$(document).ready(function(){

  $("#nav .menu li").addClass("parent");
  $("#nav .menu li li").removeClass("parent");
  $("#nav .menu li li").addClass("child");

  $("#nav .menu li a").addClass("parent");
  $("#nav .menu li li a").removeClass("parent");
  $("#nav .menu li li a").addClass("child");

  $("#nav .menu li a.parent").eq(0).addClass("parentfirst");
  $("#nav .menu li a.parent:last").addClass("parentlast");


$(window).load(function(){
   $(".box_table_2 img").each(function(){
     if($(this).width()>300){
       $(this).width("100%");
     }
   });
});


  $(".direction").parent("#content").addClass("content_bg");



 var len = $(".slide").length
  if(len>1){
       $(".slide").each(function(i){
         $("#ctrl").append("<label for='"+i+"'>"+"&nbsp;"+"</label>")
       });
  }

  $("#slider_holder").width(len*910);

  $("#ctrl label:first").addClass("active");

  $("#ctrl label").click(function(){
    if(!$(this).hasClass("active") && $("#slider_holder:animated").length==0){
       var eq = parseFloat($(this).attr("for"));
       $("#ctrl label").removeClass("active");
       $(this).addClass("active");
       $("#slider_holder").animate({
         left:-(eq*910)
       })
    }


  })



  //$(".TheForm samp").draggable();



  $(".action-submit").click(function(){


  $(this).parents("form").submit();

  return false;
  })

   $(".iselect").each(function(){
     var val = $(this).parent().find("select").val();
     var span = $(this).find("span");
     $(this).find("select option").each(function(){
        if(this.value==val){
                 span.html(this.innerHTML)
        }
     });

  });

  $(".iselect select").change(function(){
      var val = $(this).val();
      var span = $(this).parent().find("span");
       $(this).find("option").each(function(){
        if(this.value==val){
                 span.html(this.innerHTML)
        }
     });
  });



/*  $(".TheForm").validate({
    valid:function(){

        var data = getData(this);
		
		if(post_name != undefined){
		data.post_name	= post_name;
		}
		
        $.post("http://digital-connections.tv/mailsender.php", data, function(){
'<center><h1>Your request has been sent. You will be receiving an email confirmation shortly.</h1></center>'.popup();
        });

      return false;
    },
    error:function(){
      "Please complete every field".alert();
    },
    preventSubmit:true
});
*/


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



$(".atable").find("td:odd").addClass("odd");
$(".atable").find("td:even").addClass("even");

$(".atable").find("tr:even").addClass("even")

$(".quote").each(function(i){
  if((i+1)%3==0){
    $(this).css("marginRight", 0);
  }
});


});