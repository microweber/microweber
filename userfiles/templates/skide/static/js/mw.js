


//fader
fader = {}

var fade_speed = 800;

fader.func = function(selector, time){
    setInterval(function(){
      var visible = selector.find(".randomize_image:visible");
      var isLast = visible.next(".randomize_image");
      if(isLast.length>0){
         visible.fadeOut(fade_speed);
         visible.next(".randomize_image").fadeIn(fade_speed);
      }
      else{
        visible.fadeOut(fade_speed);
        selector.find(".randomize_image:first").fadeIn(fade_speed);
      }
    }, time);
}
fader.init = function(selector, time){
    var elem = $(selector);
    elem.find(".randomize_image:first").show();
    elem.find(".randomize_image").bgpreload();
    fader.func(elem, time);
}

// window.onhash()
window.onhash = function(hash, callback){
    $(window).bind("load hashchange", function(){
          var wHash = window.location.hash;
          if(wHash==hash){
              callback.call(this);
          }
    });
    return hash;
}
window.onhashSimilar = function(hash, callback){
    $(window).bind("load hashchange", function(){
          var wHash = window.location.hash;
          if(wHash.indexOf(hash)!=-1){
              callback.call(this);
          }
    });
    return hash;
}



modal={}

modalcreate = function(content, height){
    var modal_content = "";
    var modal_obj = "";
    if(typeof content == 'object'){
      var content_obj = $(content).clone(true).css({display:"block",visibility:"visible"});
      modal_old = $(content);
      $(content).after('<modal />');
      $(content).destroy();
    }
    else{
      var modal_content = content;
    }
    var modal_source = ''
    +'<table cellspacing="0" cellpadding="0" width="100%">'
       + '<tr class="modal_header">'
           +'<td class="m_tl"></td>'
           +'<td class="m_tm"></td>'
           +'<td class="m_tr"></td>'
       + '</tr>'
       + '<tr class="modal_content">'
           +'<td class="m_cl"></td>'
           +'<td class="m_cl">'
             + '<div class="modal_main" style="height:' + height + 'px">'
              + '<span class="modalclose" title="Close" onclick="modal.close()"></span>'
              + modal_content
             + '</div>'
           +'</td>'
           +'<td class="m_cr"></td>'
       + '</tr>'
       + '<tr class="modal_footer">'
           +'<td class="m_bl"></td>'
           +'<td class="m_bm"></td>'
           +'<td class="m_br"></td>'
       + '</tr>'
    +'</table>';
    var modaler = document.createElement('div');
    modaler.innerHTML = modal_source;
    $(modaler).find(".modal_main").append(content_obj);
    return modaler;
}

modal.close = function(){
  $(".modal").destroy();
  $("#overlay").hide();
  $("#header_login_form").hide();
  $("#compose_suto").remove();
  try {
     $("modal").replaceWith(modal_old);
  }
  catch(err){}



}
mw.tip = function(elem){
  mw.ready(elem, function(){
      $(elem).each(function(){
       if($(this).attr("title")!=''){
          var title = $(this).attr("title");
          $(this).attr("tip", title);
          $(this).removeAttr("title");
        }
      });
      $(elem).hover(function(){
        if($(this).attr("tip")!=''){
          var title = $(this).attr("tip");
          document.getElementById('tip').innerHTML=title;
          document.getElementById('tip').style.display='inline-block';
        }
      }, function(){
         document.getElementById('tip').style.display='none';
         document.getElementById('tip').innerHTML='';
      });
  })
}
modal.overlay = function(color){
  var overlay = document.getElementById('overlay');
  if(overlay.style.display=='none'){
    if(color==undefined){
        overlay.style.backgroundColor = 'transparent';
        overlay.style.display = 'block';
    }
    else{
        overlay.style.backgroundColor = color;
        overlay.style.display = 'block';
    }
  }
}
isobj = function(obj){
    if(obj==undefined){
      return false;
    }
    else{return obj}
}


modal.init = function(obj){
  if($(".modal").length==0){
    if(typeof obj == 'object' ){
        var modal = document.createElement('div');

        modal.appendChild(modalcreate(obj.html, obj.height));
        modal.style.width = obj.width + 'px';
        if(!isobj(obj.customPosition)){
          modal.className='modal modalcentered ' + isobj(obj.skin);
          modal.style.top = $(window).scrollTop() + ($(window).height())/2-obj.height/2 + 'px';
          modal.style.left = $(window).width()/2 - obj.width /2 + 'px';
        }
        else{
          modal.className='modal ' + isobj(obj.skin);
          modal.style.top = obj.customPosition.top + 'px';
          modal.style.left = obj.customPosition.left != 'center'?obj.customPosition.left + 'px':$(window).width()/2 - obj.width/2 + 'px';
        }

        document.body.appendChild(modal);

        if(isobj(obj.oninit) && typeof obj.oninit=='function'){
           obj.oninit.call(modal);
        }

        return modal;
    }
  }
}
modal_path1 =  'ajaxmodals/';
modal_path = template_url + modal_path1;


modal.ajax = function(obj){
    var file = modal_path + obj.file;
    var module = modal_path1 + obj.file;
    var width = obj.width;
    var height = obj.height;



 $.ajax({
  url: site_url+'api/module',
   type: "POST",
      data: ({module : module }),
     // dataType: "html",
      async:false,

  success: function(resp) {

     modal.init({
            html:resp,
            width:width,
            height:height,
            oninit:obj.oninit
        });

  }
    });














}


modal.center = function(){
  if($(".modalcentered").length>0){
    $(".modalcentered").each(function(){
        var width = $(this).width();
        var height = $(this).height();
        this.style.top = $(window).scrollTop() + ($(window).height())/2-height/2 + 'px';
        this.style.left = $(window).width()/2 - width /2 + 'px';
    });
  }
}


//tabs

$(window).bind("hashchange load", function(){
    var hash = window.location.hash;
    if(hash.indexOf('#tab-') !=-1){
        $("a[href='"+ hash +"']").parents(".tabs").find(".tabnav li").removeClass("active");
        $("a[href='"+ hash +"']").parent().addClass("active");
        $("a[href='"+ hash +"']").parents(".tabs").find(".tab").hide()
        $(hash).show();
    }
    else{
        $(document.body).find(".tabs").each(function(){
            $(this).find(".tabnav li:first").addClass("active");
            $(this).find(".tab:first").show();
        });
    }
});



$(document).ready(function(){

mw.ready(".mw_comments_wrapper", function(){
      var length = $(this).find(".comment").length;
      if(length>2){
        $(this).find(".comment:gt(1)").hide();
        $(this).append('<div class="c" style="padding-bottom:5px;"></div><a href="javascript:;" class="right mw_blue_link" onclick="$(this).parent().find(\'.comment\').show();$(this).hide();">See all comments</a>');
      }
});



});

mw.ajaxEvent = function(event, callback){
    $(document.body).ajaxStop(function(){
          if(eventlistener==event){
            eventlistener='';
            callback.call(this);
            return false;
          }
    });
}
mw.fb = {
  login:function(selector, width, height){
    var iframe = document.createElement('iframe');
    iframe.src = site_url + 'fb_login';
    iframe.style.width = width!=undefined?width:'81px';
    iframe.style.height = height!=undefined?height:'22px';

    $(iframe).attr("frameborder", "0");
    $(iframe).attr("scrolling", "no");
    //$(iframe).attr("onload", "alert(1)");
    $(selector).empty().append(iframe)

  },
  loginPopup:function(){
    var iframe = document.createElement('iframe');
    iframe.src = site_url + 'fb_login';
    iframe.style.width = '81px';
    iframe.style.height = '22px';

    $(iframe).attr("frameborder", "0");
    $(iframe).attr("scrolling", "no");
    var div = document.createElement('div');
    $(div).append(iframe);
    modal.init({
      html:div,
      width:420,
      height:350
    })
  }
}


	$.fn.dataCollect = function() {
        var formData = '';
        var fields = 'input, select, textarea';
        var not = 'input[type="submit"], input[type="image"]';
        var length = $(this).find(fields).not(not).length;
        $(this).find(fields).not(not).each(function(i){
          var name = $(this).attr("name");
          var val = $(this).val();
          if(i<length){
            formData = formData + name + ':' + '"' + val + '",';
          }
          else{
            formData = formData + name + ':' + '"' + val + '"';
          }
        });
       return eval('({' + formData + '})');
    };




$.fn.extend({
  onStopWriting:function(callback){
      var setTime = setTimeout(function(){},1);
      var keydown_val='';
      $(this).keydown(function(){
        keydown_val = $(this).val();
      });
      $(this).keyup(function(){
         var el = this;
         var val = el.value;
         if(setTime){clearTimeout(setTime)}
         setTime = setTimeout(function(){
           if(keydown_val!=val){
              callback.call(el);
           }
         }, 500);
       });
      return this;
	},
    hasEmbed:function(){
      if($(this).find("object").length>0 || $(this).find("embed").length>0 || $(this).find("iframe").length>0){
        return true;
      }
      else{return false}
    }

});







    $.dataFind = function(data, findwhat){
       var div = document.createElement('div');
       div.innerHTML = data;
       div.className = 'xhidden';
       document.body.appendChild(div);
       setTimeout(function(){$(div).destroy()}, 5);
       return $(div).find(findwhat)
    }



fields = function(){
    var field_top = ''
    +'<table cellpadding="0" cellspacing="0">'
    +     '<tr>'
    +         '<td class="field_tl"></td>'
    +         '<td class="field_tm"></td>'
    +         '<td class="field_tr"></td>'
    +     '</tr>'
    +'</table>';

    var field_bot = ''
    +'<table cellpadding="0" cellspacing="0">'
    +     '<tr>'
    +         '<td class="field_bl"></td>'
    +         '<td class="field_bm"></td>'
    +         '<td class="field_br"></td>'
    +     '</tr>'
    +'</table>';


    $(".field").each(function(){
      if(!$(this).hasClass("executed")){
        $(this).width($(this).find("input, textarea, select").eq(0).outerWidth());
        $(this).prepend(field_top);
        $(this).append(field_bot);
        $(this).addClass("executed");
      }
    });
    }

  mw.wmode = function(string, elem){
      var elem = $(elem);
      var div = document.createElement('div');
      var xstring = string.replace(/&#039;/g, "'");

      div.innerHTML = xstring;


      div.className = 'xhidden fl_div';
      document.body.appendChild(div);

      //$(div).find("object").append('<param name="wmode" value="transparent" />');

    //$(div).find("embed").attr("wmode","transparent");

    alert($(div).html())

      elem.html($(div).html());

      $(div).remove();
  }


$.expr[':'].hasval = function(obj){
  var $this = $(obj);
  return ($this.val() != '' && $this.val() != undefined);
};
$.expr[':'].hasnoval = function(obj){
  var $this = $(obj);
  return ($this.val() == '' || $this.val() == undefined);
};




  mwurlSettings = {
    hashDelimitter:"&", //delimitter between each property
    hashStartWith:""  //how to begin after '#'
  }



  document.getUrlParam = function(prop){
    var prop = escape(prop);
    var hash = window.location.href;
    var hash = hash.replace(/\//g, '&');
    var hash = hash.replace(mwurlSettings.hashStartWith, "");
    var hashLength = hash.length;
    var prop = prop + ":";
    var propBegin = hash.search(prop);
    var propLength = prop.length;
    var valueBegin = propBegin + propLength;
    var valueEnd = 0;
    if(hash.indexOf(prop) != -1){
      for(var i = valueBegin; i<=hashLength; i++){
        if(hash.charAt(i)==mwurlSettings.hashDelimitter || i==hash.length){
          var valueEnd = i;
          break;
        }
      }
    }
    else{return false}
    var result = hash.substring(valueBegin, valueEnd);
    return result;
  }





mw.paging = {
  has:function(){
    if($(".paging").length>0){
      return true;
    }
    else{return false}
  },
  currentPage:function(){
    loc = window.location.href;
    var curpage=0;
    if(mw.paging.has()){
        if(loc.indexOf('users-page:')==-1){
            curpage=1;
        }
        else{
          curpage = parseFloat(document.getUrlParam('users-page'));
        }
    }
    return curpage;
  },
  set:function(){
    var cur = mw.paging.currentPage();
    $(".paging span").hide().removeClass("active");
    $(".paging span").eq(cur-1).addClass("active");
    if(cur<=5){
       $(".paging span:lt(10)").show();
    }
    else{
       $(".paging span:gt(" + (cur-6) + ")").show();
       $(".paging span:gt(" + (cur+3) + ")").hide();
    }
  },
  prev:function(){
    var cur = mw.paging.currentPage();
    if(mw.paging.has()){
      if(cur>1){
        var prev = $(".paging .active").prev().find("a").attr("href");
        $(".paging").prepend("<span><a href='" + prev + "'>Previous</a></a>");
      }
    }
  },
  next:function(){
    var cur = mw.paging.currentPage();
    if(mw.paging.has()){
      if($(".paging .active").next().length>0){
        var next = $(".paging .active").next().find("a").attr("href");
        $(".paging").append("<span><a href='" + next + "'>Next</a></a>");
      }
    }
  },
  init:function(){
     mw.paging.set();
     mw.paging.prev();
     mw.paging.next();
  }
}

mw.createuserslist = function(arr){
  var datalist = '';
  $.each(arr, function(i, val) {
    var obj = $(this);
    $.each(obj, function(i1, val1){
       datalist = datalist + "<li onclick='set_auto_id(this)' rel='" + val1.id + "'>" + val1.first_name +" "+ val1.last_name + " <small>(" + val1.username + ")</small></li>";

    });
  });
  datalist = "<ul class='datalist userslist'>" + datalist + "</ul>";


  return datalist;
}
set_auto_id = function(elem){
  var el = $(elem);
  var id = el.attr("rel");
  var name = el.text();
  $("#receiver_name").val(name);
 // $("input[name='from_user']").val(id);
$("input[name='receiver']").val(id);  
  
  
  
  $("#compose_suto").remove();
}

















































