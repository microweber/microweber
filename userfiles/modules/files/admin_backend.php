<div class="mw-module-admin-wrap<?php if(isset($params['from_admin'])): ?>-from-admin<?php endif; ?>">
  <?php if(isset($params['backend'])): ?>
  <module type="admin/modules/info" />
  <?php endif; ?>
  <?php
 if(is_admin() == false) {return; }
 //$rand = uniqid(); ?>
  <script type="text/javascript">

    if(self !== parent){
      document.body.className += ' browser-liveedit';
    }
</script> 
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

.file-preview-holder video{
  margin-bottom: 20px;
}

.file-preview-holder .mw-ui-field{
  width: 100%;
  text-align: center;
}

.mw-browser-list .mw-icon-category{
  font-size: 37px;
}







.posts-selector span:hover{
  text-decoration: underline
}


/* Live Edit */

body.browser-liveedit h2{
  display: none;
}

body.browser-liveedit #files_ctrl_holder{
  display: none;
}

body.browser-liveedit .mw-ui-box-content{
  height: auto;
}

body.browser-liveedit .delete_item{
  display: none;
}

body.browser-liveedit .mw-browser-list .mw-ui-check{
  display: none;
}


.mw-fileico{
  display: inline-block;
  font-size: 12px;
  font-weight: bold;
  text-align: center;
  padding: 10px 0;
  background-color: #CCCCCC;
  text-transform: lowercase;
  width: 100%;
  border-radius:2px;
}

.posts-selector{
  margin: 11px 11px 0 0;
}

#mw_files_admin{
  margin-bottom: 20px;
}

/* /Live Edit */


.browser-ctrl-bar{
  overflow: hidden;
  padding-bottom: 20px;
}

.image-item{
  max-height: 50px;
  max-width: 50px;
}

#progressbar{
  height: 8px;
  margin: 8px 0;
}




/* / View modes */


body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder{
  display:block;
}


body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder_select_all_holder,
body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder_title_text{

  display:none;
}


body .mw-file-browser.mw-file-browser-basic .modules-index-bar,
body .mw-file-browser.mw-file-browser-basic .browser-ctrl-bar
{
  padding:0px;
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




_mw_admin_files_manage = function(param, value, callback){
    var holder = mw.$('#mw_files_admin');
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
    mw.load_module('files/browser','#mw_files_admin', function(){
       $(mwd.body).removeClass("loading");
       $(".mw-ui-searchfield").removeClass("loading");
       if(typeof callback === 'function'){callback.call()}
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

createPopHTML = function(img, type){
  var type = type || 'image';
  if(type == 'image'){
    var h = ""
        + "<div class='file-preview-holder'>"
        + "<img src='" + img +"' />"
        + "<div class='mw-ui-row'><div class='mw-ui-col' style='width:80%'><input type='text' class='mw-ui-field' value='"+img+"' onfocus='this.select()' readonly></div><div class='mw-ui-col'>"
        + "<span class='mw-ui-btn' onclick='deleteItem(\""+img+"\", false, true)'><?php _e("Delete"); ?></span></div></div>"
        + "</div>";
  }
  else if(type == 'media'){
       var h = ""
        + "<div class='file-preview-holder'>"
        + '<video autoplay="true" class="w100" src="'+img+'" controls></video>'
        + "<div class='mw-ui-row'><div class='mw-ui-col' style='width:80%'><input type='text' class='mw-ui-field' value='"+img+"' onfocus='this.select()' readonly></div><div class='mw-ui-col'>"
        + "<span class='mw-ui-btn' onclick='deleteItem(\""+img+"\", false, true)'><?php _e("Delete"); ?></span></div></div>"
        + "</div>";
  }



  return h;
}

deleteItem = function(url, name, frommodal){

  if(typeof url === 'string'){
    var obj = {path:[url]};
    var name = name || 'this';
    var msg = "<?php _e("Are you sure you want to delete"); ?> "+name+"?";
  }
  else if(url.constructor === [].constructor){
     var obj = {path:url}
     var msg = "<?php _e("Are you sure you want to delete these files"); ?>?";
  }
  else{
    return false;
  }

  mw.tools.confirm(msg, function(){ $(mwd.body).addClass("loading");
      if(frommodal == true ){mw.$("#prfile").remove()}
      $.post(mw.settings.api_url + "media/delete_media_file", obj, function(a){
           $(mwd.body).removeClass("loading");
           _mw_admin_files_manage('all');
           mw.notification.msg(a);
      });
  })

}

if(self === parent){
mw.on.hashParam('select-file', function(){
  if(this!=false){
      var type = this.split(".").pop();
      var type = type.toLowerCase();
      if(type =='jpg' || type == 'png' || type =='jpeg' || type == 'gif'){
          if(mw.$("#prfile").length == 0){
              mw.tools.modal.init({
                html:createPopHTML(this),
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
      else if(type == 'mp3' || type ==  'avi' || type=='mp4' || type == 'wmv' || type == 'swf'){
        mw.tools.modal.init({
                html:createPopHTML(this, 'media'),
                width:500,
                height:460,
                name:"prfile",
                title:this.split("/").pop()
              });
      }
      else{
          if(mw.$("#prfile").length > 0){
               mw.$("#prfile").remove()
          }

          mw.modalFrame({
                url:this,
                width:500,
                height:460,
                name:"prfile",
                title:this.split("/").pop()
          });
		  
		  /*mw.tools.modal.init({
                html:createPopHTML(this, 'media'),
                width:500,
                height:460,
                name:"prfile",
                title:this.split("/").pop()
              });    */
		  
		  
      }
  }
  else{
    if(mw.$("#prfile").length > 0){
             mw.$("#prfile").remove();
        }
  }
});


}

saveNewFolder = function(a){
    if(a!=''){
          var path = mw.url.windowHashParam("path") != undefined ? mw.url.windowHashParam("path") : "";
          var obj = {
            path:path,
            name:a
          }
          $.post(mw.settings.api_url + "create_media_dir", obj, function(data){
                mw.notification.msg(data);
                _mw_admin_files_manage('all', false, function(){
                  mw.$(".mw-browser-list span").each(function(){
                     if(this.innerHTML == a){
                       mw.tools.highlight(this.parentNode, "#CDE1FB");
                       return false;
                     }
                  });
                });
          });
    }
}


createFolder = function(){
  var html = '<li><a href="javascript:;" style="background:#CDE1FB"><span class="mw-icon-category"></span><span><input type="text" autofocus placeholder="<?php _e("New Folder"); ?>" onblur="saveNewFolder(this.value)" onkeydown="event.keyCode === 13 ? saveNewFolder(this.value) : 1;" /></span></a> </li>';
  if(mwd.querySelector(".mw-browser-list") !== null){
    $(mwd.querySelector(".mw-browser-list")).prepend(html).find("input:first").focus();
  }
  else{
    var html = "<ul class='mw-browser-list'>"+html+"</ul>";
    mw.$("#mw-browser-list-holder").prepend(html).find("input:first").focus()
  }

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
_mw_admin_files_manage('all');

ProgressBar = mw.progress({
  action:'<?php _e("Uploading"); ?>...',
  element:mwd.getElementById('progressbar'),
  skin:'mw-ui-progress-small'
});

ProgressBar.hide()

    Uploader = mw.files.uploader({
		filetypes:"*",
		multiple:true
	});

    $(Uploader).bind("progress", function(frame, file){
        ProgressBar.show()
        ProgressBar.set(file.percent);
    });

    $(Uploader).bind("done", function(frame, item){
        ProgressBar.set(0);
        ProgressBar.hide();
     });

    $(Uploader).bind("responseError", function(e, json){
        Alert(json.error.message);
        ProgressBar.set(0);
        ProgressBar.hide();
    });
    $(Uploader).bind("error", function(frame, file){
        ProgressBar.set(0);
        ProgressBar.hide();

    });

    $(Uploader).bind("FilesAdded", function(frame, files_array, runtime){
        if(runtime == 'html4'){
          //ProgressInfo.html('<?php _e("Uploading"); ?> - "' + files_array[0].name+'" ...');
        }
    });



	mw.$("#mw_uploader").append(Uploader);
	$(Uploader).bind("FileUploaded", function(obj, data){
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
	
	if(isset($params['ui'])){
		 $ui_order_control = $params['ui'];
	}
	

 ?>
  <div class="mw-file-browser mw-file-browser-<?php print $ui_order_control; ?>">
    <h2 id="files_ctrl_holder_title_text"><a href="<?php print $config["url_main"]; ?>"><span class="ico iupload"></span>&nbsp;
      <?php _e("File Manager"); ?>
      </a></h2>
    <div id="files_ctrl_holder">
      <div class="modules-index-bar">
        <div class="browser-ctrl-bar"> <span id="files_ctrl_holder_select_all_holder" class="mw-ui-link-nav posts-selector pull-left"> <span onclick="mw.check.all('#mw-browser-list-holder');mw.$('.delete_item').removeClass('disabled');">
          <?php _e("Select All"); ?>
          </span> <span onclick="mw.check.none('#mw-browser-list-holder');mw.$('.delete_item').addClass('disabled');">
          <?php _e("Unselect All"); ?>
          </span> </span>
          <div class="mw-ui-btn-nav pull-left"> <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-notification"><span class="mw-icon-upload"></span>
            <?php _e("Upload File"); ?>
            </span> <span class="mw-ui-btn mw-ui-btn-red delete_item disabled">
            <?php _e("Delete selected files"); ?>
            </span> <span class="mw-ui-btn mw-ui-btn-blue" onclick="createFolder()">
            <?php _e("Create folder"); ?>
            </span> </div>
          <input
            name="module_keyword"
            class="mw-ui-searchfield pull-right"
            type="text" placeholder="<?php _e("Search"); ?>" onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"    />
        </div>
        <div id="progressbar" style=""></div>
      </div>
    </div>
    <div id="mw_files_admin"></div>
    <div id="mw_user_edit_admin" ></div>
    <span class="mw-ui-btn pull-right disabled delete_item">
    <?php _e("Delete Selected"); ?>
    </span> </div>
</div>
