<?
 if(is_admin() == false) { error("Must be admin"); }
 //$rand = uniqid(); ?>
<script type="text/javascript">
    mw.require("events.js");
	 mw.require("forms.js");
    mw.require("url.js");
    mw.require("files.js");
</script>
<style type="text/css">
.mw-file-browser-popup .modules-index-bar {
	padding-top: 12px;
}

.file-preview-holder{
  text-align: center;
}

.file-preview-holder img{
  max-width: 470px;
  max-height: 330px;
  margin-bottom:20px;
}

.file-preview-holder .mw-ui-field{
  width: 450px;
  text-align: center;
}

.browser-ctrl-bar{
  float: left;
}

.browser-ctrl-bar .mw-ui-btn{
  float: left;
  margin-right: 12px;
}

</style>
<script  type="text/javascript">


 gchecked = function(){
   var l = mwd.querySelectorAll(".mw-browser-list input:checked").length;
   if( l > 0 ){
     mw.$(".delete_item").removeClass("disabled");
   }
   else{
     mw.$(".delete_item").addClass("disabled");
   }
 }




_mw_admin_files_manage = function(param, value){
    var holder = mw.$('#files_admin_{rand}');
    holder.removeAttr('search');
    holder.attr('sort_by', 'filemtime DESC');


    if(param === 'all'){
      var attrs = mw.url.getHashParams(window.location.hash);
      for (var x in attrs){
      	if(x=='path'){
      	    holder.attr(x, attrs[x]);
      	}
      	if(x=='search'){
      	    holder.attr(x, attrs[x]);
      	}
      	if(x=='sort_by'){
      	    holder.attr(x, attrs[x]);
      	}
      	if(x=='sort_order'){
      	    holder.attr(x, attrs[x]);
      	}
      }
    }
    else{
       holder.attr(param, value);
    }
    $(mwd.body).addClass("loading")
    mw.load_module('files/browser','#files_admin_{rand}', function(){
       $(mwd.body).removeClass("loading");
       $(".mw-ui-searchfield").removeClass("loading");
    });

}


$(window).bind("load", function(){
 _mw_admin_files_manage('all');
});






mw.on.hashParam('path', function(){

  _mw_admin_files_manage('path', this);

  mw.postMsg(Uploader.contentWindow, {
     path:this
  });


});

createPopHTML = function(img){
  var h = ""
      + "<div class='file-preview-holder'>"
      + "<img src='" + img +"' />"
      + "<input type='text' class='mw-ui-field' value='"+img+"' onfocus='this.select()' readonly>"
      + "<div class='vSpace'></div>"
      + "<span class='mw-ui-delete' onclick='deleteItem(\""+img+"\")'>Delete</span>"
      + "</div>";
  return h;
}

deleteItem = function(url){

  if(typeof url === 'string'){
    var obj = {path:[url]};
    var msg = "Are you sure you want to delete this file?";
  }
  else if(url.constructor === [].constructor){
     var obj = {path:url}
     var msg = "Are you sure you want to delete these files?";
  }
  else{
    return false;
  }

  mw.tools.confirm(msg, function(){ $(mwd.body).addClass("loading");
      $.post(mw.settings.api_url + "delete_media_file", obj, function(a){
           $(mwd.body).removeClass("loading");
           _mw_admin_files_manage('all');

           mw.notification.msg(a);
      });
  })

}


mw.on.hashParam('select-file', function(){
  if(this!=false){
      var type = this.split(".").pop();
      var type = type.toLowerCase();
      if(type =='jpg' || type == 'png' || type =='jpeg' || type == 'gif'){
          if(mw.$("#prfile").length == 0){
              mw.tools.modal.init({
                html:createPopHTML(this),
                template:"mw_modal_simple",
                width:500,
                height:460,
                name:"prfile",
                title:this.split("/").pop()
              });
          }
          else{
             mw.$("#prfile .mw_modal_container").html(createPopHTML(this))
             mw.$("#prfile .mw_modal_title").html(this.split("/").pop())
          }
      }
      else{
          if(mw.$("#prfile").length > 0){
               mw.$("#prfile").remove()
          }
      }
  }
  else{
    if(mw.$("#prfile").length > 0){
             mw.$("#prfile").remove();
        }
  }




});

saveNewFolder = function(a){
    if(a!=''){
          var path = mw.url.windowHashParam("path") != undefined ? mw.url.windowHashParam("path") : "";
          var obj = {
            path:path,
            name:a
          }
          $.post(mw.settings.api_url + "create_media_dir", obj, function(data){
                mw.notification.msg(data);
                 _mw_admin_files_manage('all');
          });
    }
}


createFolder = function(){
  var html = '<li><a href="javascript:;"><span class="ico icategory"></span><span><input type="text" autofocus placeholder="New Folder" onblur="saveNewFolder(this.value)" onkeydown="event.keyCode === 13 ? saveNewFolder(this.value) : 1;" /></span></a> </li>';
  $(mwd.querySelector(".mw-browser-list")).prepend(html);
}


mw.on.hashParam('search', function(){

    _mw_admin_files_manage('search', this);

});
mw.on.hashParam('sort_by', function(){
     if(this!=false && this!=''){
        _mw_admin_files_manage('sort_by', this);
     }
});
mw.on.hashParam('sort_order', function(){
    if(this!=false && this!=''){
        _mw_admin_files_manage('sort_order', this);
    }

});


$(document).ready(function(){

    Uploader = mw.files.uploader({
		filetypes:"*",
		multiple:false
	});

	mw.$("#mw_uploader").append(Uploader);
	$(Uploader).bind("FileUploaded", function(obj, data){
	    //alert(data.src);
         _mw_admin_files_manage('all');
    });

    mw.$(".delete_item").click(function(){
      if(!$(this).hasClass("disabled")){
        var arr = [], c = mwd.querySelectorAll(".mw-browser-list input:checked"), i=0, l=c.length;
        for( ; i<l; i++){
          arr.push(c[i].value);
        }
        deleteItem(arr);
      }
    })

});

</script>
<?php

    $ui_order_control = 'dropdown';


    if(!isset($ui_order_control)){$ui_order_control = 'auto';}

 ?>

<div style="padding: 20px;max-width: 960px;" class="mw-file-browser mw-file-browser-<?php print $ui_order_control; ?>">
  <h2><span class="ico iupload"></span>&nbsp;File Manager</h2>
  <div>
    <!--<div class="mw-item-sorter left" style="margin-right: 10px;"> <span class="mw-ui-label-help">Sort by</span>
      <select name="module_show" class="mw-ui-simple-dropdown" style="width: 110px;" onchange="mw.url.windowHashParam('sort_by', this.value)">
        <option value="filemtime">Last modified</option>
        <option value="filesize">Size</option>
        <option value="">Auto</option>
      </select>
    </div>
    <div class="mw-item-sorter left"> <span class="mw-ui-label-help">Sort order</span>
      <select name="sort_order" class="mw-ui-simple-dropdown" style="width: 110px;" onchange="mw.url.windowHashParam('sort_order', this.value)">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
        <option value="">Auto</option>
      </select>
    </div>-->
    



    
    <div class="modules-index-bar">
    <div class="browser-ctrl-bar">
        <div id="mw_uploader" class="mw-ui-btn mw-ui-btn-green left">Upload File</div>
        <span class="mw-ui-btn mw-ui-btn-red delete_item disabled">Delete selected files</span>
        <span class="mw-ui-btn mw-ui-btn-blue" onclick="createFolder()">Create folder</span>
    </div>



      <input name="module_keyword" class="mw-ui-searchfield right" type="text" data-default="<?php _e("Search"); ?>" value="<?php _e("Search"); ?>" onfocus="mw.form.dstatic(event);" onblur="mw.form.dstatic(event);"  onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
    </div>
  </div>
  <div class="vSpace"></div>
  <div id="files_admin_{rand}" ></div>
  <div id="user_edit_admin_{rand}" ></div>
  <div class="vSpace"></div>

  <span class="mw-ui-btn right disabled delete_item">Delete Selected</span>

  <div class="mw_clear"></div>
  <div class="vSpace"></div>

</div>
<div class="mw_clear"></div>
