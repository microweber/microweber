
animateSpeed = 300;

$(document).ready(function(){

  $(".createpages").sortable({
    handle:'.FieldHandle'
  });
  $(".field").addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all");
  $(".field").addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all");
  $(".field").append("<span class='FieldHandle radius'></span>");

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







});

