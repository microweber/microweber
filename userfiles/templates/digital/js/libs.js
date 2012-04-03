
getData = function(id){
    var el = $(id);

    fields = "input[type='text'], input[type='password'], textarea, select";

    var data = {}

    el.find(fields).each(function(){
        var val = $(this).val();
        var name = $(this).attr("name");
        data[name] = val;
    });
    return data;
}






PopUpDeafaults = {
  width:600,
  height:250,
  effect:'fade', //slide, none
  speed:400
}


isdefined = function(o){
  if(typeof o === "undefined"){
    return false;
  }
  else{return true}
}
SafeClone = function(obj){
    var obj = $(obj);
    if(obj.parents(".PopUp").length==0){
      var clone = obj.clone(true);
      var id = obj.attr("id");
      obj.after("<span style='position:absolute;left:-9999px' id='" + id + "_old'></span>");
      obj.remove();
      return clone;
    }
    else {return false};
}
ReClone = function(id){
  var obj = $(document.getElementById(obj));
  var clone = obj.clone(true);
  var id = obj.attr("id");
  $("#"+id+"_old").after(clone);
  obj.remove();
  $("#"+id+"_old").remove();
}


Pops = {
  /* Register Each Popup object :) */
}
popup = {
    CreateBox: function(){
        var box = ''
        + '<div class="popup">'
          + '<table cellspacing="0" cellpadding="0" width="100%" style="width:100%"><tbody>'
            + '<tr class="popup_top">'
              + '<td class="popup_top_left"></td>'
              + '<td class="popup_top_middle"></td>'
              + '<td class="popup_top_right"></td>'
            + '</tr>'
            + '<tr class="popup_mid">'
              + '<td class="popup_mid_left">&nbsp;</td>'
              + '<td class="popup_MAIN"><div class="popup_content"></div></td>'
              + '<td class="popup_mid_right">&nbsp;</td>'
            + '</tr>'
            + '<tr class="popup_bottom">'
              + '<td class="popup_bottom_left"></td>'
              + '<td class="popup_bottom_middle"></td>'
              + '<td class="popup_bottom_right"></td>'
            + '</tr>'
          + '</tbody></table>'
          + '<span class="ModalClose" onclick="popup.close(this)">Close</span>'
        + '</div>';
        var MainBox = document.createElement('div');
        var ContentBox = document.createElement('div');
        ContentBox.className = 'PopUpContentBox';
        MainBox.innerHTML = box;
        MainBox.className = "PopUp";
        $(MainBox).find(".popup_content").append(ContentBox);
        return {main:MainBox, content:ContentBox};
    },
    align:function(obj, width, height, position){
      var width = isdefined(width)?width:PopUpDeafaults.width;
      var height = isdefined(height)?height:PopUpDeafaults.height;
      if(position=='center' || position=='' || position == undefined){
         $(obj).css({
            left:$(window).width()>width?($(window).width()/2)-(width/2):0,
            top:($(window).height()-10)>height?($(window).scrollTop()+($(window).height())/2-height/2):$(window).scrollTop()+10
         });
         $(obj).find("table:first").width(width);
         $(obj).find("table:first").height(height);
      }
    },
    autoCenter:function(obj, position){
        if(!$(obj).hasClass("Autocenter")){
            setInterval(function(){
            var width = $(obj).outerWidth();
            var height = $(obj).outerHeight();
            var width = width>200?width:PopUpDeafaults.width;
            var height = height>150?height:PopUpDeafaults.height;
            popup.align(obj, width, height, position);
            }, 80);
        }
    },
    register:function(obj, name){
        if($(obj.main).attr("registered")!="true"){
           $(obj.main).attr("registered", true);
           $(obj.main).attr("id", name);
          Pops[name] = {
            main:obj.main,
            content:obj.content
          }
        }
        else{
          //
        }
    },
    initOverlay:function(){
        var overlay = document.createElement('div');
        return {main:overlay};
    },
    overlay:function(){
        var overlay = popup.initOverlay();
        popup.register(overlay, "PopupOverlay");
    },
    close:function(obj){
        if(typeof obj=="object"){
          if(obj.className=='ModalClose'){
            var obj = $(obj).parents(".PopUp");
          }
          else{
            var obj = $(obj);
          }
        }
        else{
           var obj = $(obj);
        }
        if(PopUpDeafaults.effect=='fade'){
          obj.fadeOut(PopUpDeafaults.speed);
          $("#PopupOverlay").fadeOut(PopUpDeafaults.speed);
        }
        else if(PopUpDeafaults.effect=='slide'){
          obj.slideUp(PopUpDeafaults.speed);
          $("#PopupOverlay").fadeOut(PopUpDeafaults.speed);
        }
        else if(PopUpDeafaults.effect=='none' || PopUpDeafaults.effect=='undefined'){
          obj.hide();
          $("#PopupOverlay").hide();
        }
    }
}

popup.overlay();

Popup = popup.CreateBox();

popup.register(Popup, "ModalBox");






Modal = {
  main:Pops.ModalBox.main,
  content:Pops.ModalBox.content,
  overlay:Pops.PopupOverlay.main
}

Modal.main.style.display = 'none';

$(document).ready(function(){
  popup.autoCenter(Modal.main);
  document.body.appendChild(Modal.overlay);
  document.body.appendChild(Modal.main);



  /*

  Docs: :)

  Objects:

  1. Modal.overlay - overlay object. Usage: $(Modal.overlay), Modal.overlay.style.display ...
  2. Modal.main - wrapper(holder) of the popup. Usage: $(Modal.main), Modal.main.style.display ...
  3. Modal.content - container of the popup. Usage: $(Modal.content).html() ... append(), Modal.content.innerHTML =  ...


  */


  //'Some cool text that will ... '.popup();




});



String.prototype.popup = function(){

  Modal.content.innerHTML = this;
   $(Modal.main).fadeIn();
   $(Modal.overlay).show();

}
String.prototype.alert = function(){

    Modal.content.innerHTML ="<table id='alertTable' cellpadding='0' cellspacing='0'><tr><td style='width:"+(PopUpDeafaults.width-50)+"px;height:"+(PopUpDeafaults.height-30)+"px'>"+this+"</td></tr></table>";


   $(Modal.main).fadeIn();
   $(Modal.overlay).show();

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