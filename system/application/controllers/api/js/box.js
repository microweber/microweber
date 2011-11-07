mw.id = function(){
  return Math.floor(Math.random()*99999);
}

mw.modal={
  create:function(content, height){
    var modal_content = "";
    var modal_obj = "";
    if(typeof content == 'object'){
      var modal_tag_id = false;
      if($(document.body).has(content).length>0){
        modal_tag_id = 'x_' + mw.id();
        var content_obj = $(content).clone(true).css({display:"block",visibility:"visible"});
        modal_old = $(content);
		//alert(modal_old);
		
		
        $(content).after('<mwmodal class="' + modal_tag_id + '" />');
        $(content).destroy();
      }
      else{
         var content_obj = content;
      }
    }
    else{
      //var modal_content = content;
      var modal_content = '';
      var content_obj = content;
    }
    var modal_source = ''
    +'<div class="mw_modal_box">'
       + '<div class="mw_modal_main" style="height:' + height + 'px">'
        + '<div class="mw_modal_header radius_t">'
            + '<h2><!-- Title Comes Here --></h2>'
			 + '<span class="mw_modal_buttons"></span>'
            + '<span class="mw_modalclose" title="Close">Close</span>'
        + '</div>'
        + '<div class="mw_modal_content_box">'
            + modal_content
        + '</div>' 
       + '</div>'
    +'</div>';
    var modaler = document.createElement('div');
    if(modal_tag_id!=false){
       modaler.id = modal_tag_id;
    }
	// alert(content_obj);
    modaler.innerHTML = modal_source;
    modaler.className = 'modal_data';
    $(modaler).find(".mw_modal_content_box").html(content_obj);

    return modaler;
  },
  
  overlay:function(color){
    if($("#mw_overlay").length==0){
          var overlay = document.createElement('div');
    overlay.id = 'mw_overlay';
      if(color==undefined){
          overlay.style.backgroundColor = '#000000';
          overlay.style.display = 'block';
      }
      else{
          overlay.style.backgroundColor = color;
          overlay.style.display = 'block';
      }
    }
    document.body.appendChild(overlay);
  },
  init:function(obj){
    var id = isobj(obj.id)?obj.id:mw.id();
	$(".mw_modal").show();
    if($("#mw_modal_" + id).length==0){

      if(typeof obj == 'object' ){
          var modal = document.createElement('div');
          if(isobj(obj.id)){
            var id = obj.id;
          }
          else{
            var id = mw.id();
          }
          modal.id = 'mw_modal_' + id;
          obj.width = !isobj(obj.width)?400:obj.width;
          obj.height = !isobj(obj.height)?250:obj.height;
          obj.title = !isobj(obj.title)?'':obj.title;
		  obj.buttons = !isobj(obj.buttons)?'':obj.buttons;



          modal.appendChild(mw.modal.create(obj.html, obj.height));
          modal.style.width = obj.width + 'px';
          if(!isobj(obj.customPosition)){
            var skin = !isobj(obj.skin)?'':obj.skin;
            modal.className='mw_modal mw_modalcentered ' + skin;
            if(obj.height<$(window).height()){
              modal.style.top = $(window).scrollTop() + ($(window).height())/2-obj.height/2 + 'px';
            }
            else{
               modal.style.top = ($(window).scrollTop() + 10) + 'px';
            }

            modal.style.left = $(window).width()/2 - obj.width /2 + 'px';
          }
          else{
            var skin = !isobj(obj.skin)?'':obj.skin;
            modal.className='mw_modal ' + skin;
            modal.style.top = obj.customPosition.top + 'px';
            modal.style.left = obj.customPosition.left != 'center'?obj.customPosition.left + 'px':$(window).width()/2 - obj.width/2 + 'px';
          }
          $(modal).find(".mw_modal_buttons").html(obj.buttons);
		  
		            $(modal).find("h2").html(obj.title);

		  
		  
          $(modal).find(".mw_modalclose").click(function(){
               if(isobj(obj.onclose) && typeof obj.onclose=='function'){
             obj.onclose.call(modal);
          }
		  
            mw.modal.close(id);
          });
          document.body.appendChild(modal);
          if(isobj(obj.oninit) && typeof obj.oninit=='function'){
             obj.oninit.call(modal);
          }
		  
		//   if(isobj(obj.onclose) && typeof obj.onclose=='function'){
//             obj.onclose.call(modal);
//          }
		  
		  
		  
		  
          if(obj.overlay==true){
            mw.modal.overlay();
          }
          modal.onmousedown = function(){
            if($(".mw_modal").length>1){
              this.style.zIndex = mw.modal.zindex();
            }
          }
          $(modal).draggable({handle:'.mw_modal_header', iframeFix:true});
          return modal;
      }
    }
  },
  zindex:function(){
     var mw_zindex = 0;
     $(".mw_modal").each(function(){
        var zindex = parseFloat($(this).css("zIndex"));
        if(zindex>mw_zindex){mw_zindex = zindex}
      });
      return mw_zindex+1;
  },
  close:function(id){
	  
 if(isobj(id)){
        
     $("#mw_modal_"+id).remove();  // 
    }
    else{
  //    $(".mw_modal").hide();
    }
	$(".mw_modal").remove();
    $("#mw_overlay").remove()
	
	
	
	
	   
    //if(isobj(id)){
//        if($("#mw_modal_"+id).find(".modal_data").attr("id") !="undefined" && $("#mw_modal_"+id).find(".modal_data").attr("id") != ""){
//          var el = $("#mw_modal_"+id);
//          el.find(".mw_modalclose").remove();
//          var clone = el.find(".mw_modal_main :first").clone(true);
//          clone.hide();
//          var dataid = el.find(".modal_data").attr("id");
//          $("." + dataid).replaceWith(clone);
//        }
//        $("#mw_modal_"+id).remove();
//    }
//    else{
//      $(".mw_modal").remove();
//    }
//    $("#mw_overlay").remove()
  },
  confirm:function(obj){
    var confirm = mw.modal.init({
      html:'<table width="400" height="150"><tr><td style="text-align:center">'+obj.html + '</td></tr></table><div class="c"></div><div class="okCancel"><a href="javascript:;" class="mw_confirm_yes">OK</a><a href="javascript:;" class="mw_confirm_no">Cancel</a></div>',
      oninit:function(){
        var el = this;
        $(this).find(".mw_confirm_yes").click(function(){
            mw.modal.close(obj.id);
            if(isobj(obj.yes)){
                obj.yes.call(this);
            }

        });
        $(this).find(".mw_confirm_no").click(function(){
            mw.modal.close(obj.id);
             if(isobj(obj.no)){
               obj.no.call(this);
             }

        });
        $(this).find(".mw_modalclose").mouseup(function(){
          mw.modal.close(obj.id);
             if(isobj(obj.no)){
               obj.no.call(this);
             }
        });
      },
      width:400,
      height:200,
      id:isobj(obj.id)?obj.id:mw.id()
    })
  },
  alert:function(obj){
      mw.modal.init({
      html:'<table width="380" height="130"><tr><td style="text-align:center">'+obj + '</td></tr></table><div class="c"></div><div class="okCancel"><a href="javascript:;" class="mw_confirm_yes">OK</a></div>',
      oninit:function(){
        el = this;
        $(this).find(".mw_confirm_yes").click(function(){
            mw.modal.close(obj.id);
        });
      },
      width:400,
      height:200,
      id:'mw_Alert'
    })
  },
  iframe:function(obj){
    var frame = document.createElement('iframe');
    frame.src = obj.src;
    frame.frameBorder = 0;
	frame.name = isobj(obj.id)?obj.id:mw.id();
	frame.id = isobj(obj.id)?obj.id:mw.id();
    frame.scrolling='auto';
    var width = isobj(obj.width)?(obj.width-20):380;
    var height = isobj(obj.height)?(obj.height-20):230;
    frame.style.width = width + 'px';
    frame.style.height = height + 'px';
	
	
	
	
	
    mw.modal.init({
        html:frame,
        width:isobj(obj.width)?obj.width:400,
        height:isobj(obj.height)?obj.height:250,
		 title:isobj(obj.title)?obj.title:'',
		 onclose:isobj(obj.onclose)?obj.onclose:'',
		 
		  buttons:isobj(obj.buttons)?obj.buttons:'',
		 
		 
        id:isobj(obj.id)?obj.id:mw.id()
		
    })
  },
  context:function(obj){
     var edit_pop =  mw.modal.init({
        html:obj.html,
        width:isobj(obj.width)?obj.width:200,
        height:isobj(obj.height)?obj.height:150,
        id:isobj(obj.id)?obj.id:mw.id(),
        customPosition:{
          top:obj.event.pageY,
          left:obj.event.pageX + 5
        },
        oninit:isobj(obj.oninit)?obj.oninit:''
     });
      $(obj.elem)[0].oncontextmenu = function() {
        return false;
      }
      mw.prevent(obj.event);
  }
}



isobj = function(obj){
    if( obj == undefined){
      return false;
    }
    else{return true}
}