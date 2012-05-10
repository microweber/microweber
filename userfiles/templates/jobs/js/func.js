$(document).ready(function(){


JobsDropdown = "";

$("#category_dropdown li").each(function(){
    var val = $(this).attr("id");
    var val = val.replace("category_item_", "");
    var text = $(this).find("a").html();
    if($(this).find("a").hasClass("active")){
      JobsDropdown = JobsDropdown + "<option selected='selected' value='" + val + "'>" + text + "</option>";
    }
    else{
      JobsDropdown = JobsDropdown + "<option value='" + val + "'>" + text + "</option>";
    }

});

 JobsDropdown = "<select name='category' id='category_dropdown_rended'><option value=''>Select category</option>" + JobsDropdown + "</select>";

 $("#category_dropdown").remove();

 $("#category_area").html(JobsDropdown);


 $(".paging li").hide();



 var active_paging =  $(".paging a.active").parent();
 active_paging.show();
 active_paging.next().show();
 active_paging.next().next().show();
 active_paging.prev().show();
 active_paging.prev().prev().show();

 var next_url =   active_paging.next().find("a").attr("href");
 var prev_url =   active_paging.prev().find("a").attr("href");




 $("#next").attr("href", next_url);
 $("#prev").attr("href", prev_url);

 if($("#next").attr("href")=='#'){
       $("#next").hide();
 }
 if($("#prev").attr("href")=='#'){
       $("#prev").hide();
 }

 if(active_paging.nextAll("li").length>2){
    active_paging.next().next().after("<li>...</li>");
    active_paging.parent().parent().find("li:last").show();
 }
 if(active_paging.prevAll("li").length>2){
    active_paging.prev().prev().before("<li>...</li>");
    active_paging.parent().parent().find("li:first").show();
 }


if($("#footer-partners").length>0){
       var ul_width = 0;
       $("#footer-partners ul li").each(function(){
         var w = $(this).outerWidth(true);
         ul_width = ul_width + w;
       });
      $("#footer-partners ul").width(ul_width);
      $(".bx-window ul li").addClass("actual");
      scrollLogos();
}

 $(".jobsform").submit(function(){
      var keyword = $("input[name='keyword']");
      var location = $("#search_loc");
    if(keyword.val()=='Keyword'){
        keyword.val("")
    }
    if(location.val()=='Location'){
        location.val("")
    }

 });


 $(".applytothejob_but a").click(function(){

    var top = $("form:last").offset().top;

    $("html, body").animate({scrollTop:top}, 500);

 return false;
 });


$("#footer-partners").hover(function(){
  $(this).addClass("t_hover");
}, function(){
  $(this).removeClass("t_hover");
});


$(".footer .menu").removeClass("nav");


});  // end doc ready



scrollLogos = function(){
  setInterval(function(){
    if($(".t_hover").length==0){
        var curr = parseFloat($(".bx-window ul").css("left"));
        $(".bx-window ul").css({left:curr-1});
        var wrapper_left = $("#footer-partners").offset().left;
        var first_logo = $("#footer-partners li.actual:first");
        if((first_logo.offset().left+first_logo.width())<=wrapper_left){
          var clone = first_logo.clone(true);
          $(".bx-window ul").append(clone);

           $(".bx-window ul").width($(".bx-window ul").width()+first_logo.outerWidth());

         first_logo.removeClass('actual');
        }
    }

  }, 50);
}




getData=function(id){
      var el = $(id);
      fields = "input[type='text'], input[type='password'], textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
      var data = {}
      el.find(fields).each(function(){
          var val = $(this).val();
          var name = $(this).attr("name");
          data[name] = val;
      });
      return data;
}



isValid = {    /* Validation */
    notDefault:function(string){
      return (string != 'Your Name' && string != 'Your E-mail' && string != 'Your Phone' && string != 'Message');
    },

    checkbox: function(obj){
        return obj.checked == true;
    },
    field:function(obj){
        return obj.value != '' && isValid.notDefault(obj.value);
    },
    email:function(obj){
        var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
        return regexmail.test(obj.value);
    },
    proceed:{
      checkbox:function(obj){
        if(isValid.checkbox(obj)){
               $(obj).parent().removeClass("error");
        }
        else{
            $(obj).parent().addClass("error");
        }
      },
      field:function(obj){
        if(isValid.field(obj)){
           $(obj).parent().removeClass("error");
         }
         else{
           $(obj).parent().addClass("error");
         }
      },
      email:function(obj){
        if(isValid.email(obj)){
           $(obj).parent().removeClass("error");
        }
        else{
           $(obj).parent().addClass("error");
        }
      }
    },
    checkFields:function(form){
        $(form).find(".required").each(function(){
          var type = $(this).attr("type");
          if(type=='checkbox'){
             isValid.proceed.checkbox(this);
          }
          else{
            isValid.proceed.field(this);
          }
        });
        $(form).find(".required-email").each(function(){
            isValid.proceed.email(this);
        });
    },
    form:function(obj){
        isValid.checkFields(obj);
        if($(obj).find(".error").length>0){
            $(obj).addClass("error submited");
            return false;
        }
        else{
           $(obj).removeClass("error");
            return true;
        }
    }
}