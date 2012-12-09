<?
 if(is_admin() == false) { error("Must be admin"); }
 $rand = uniqid(); ?>
<script type="text/javascript">
 mw.require("events.js");
     mw.require("url.js");
     mw.require("files.js");
   

</script>
<script  type="text/javascript">
 




 

_mw_admin_files_manage = function(param, value){
    var holder = mw.$('#files_admin_<? print $rand ?>');
    holder.removeAttr('search');
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
    mw.load_module('files/browser','#files_admin_<? print $rand ?>');

}


$(window).bind("load", function(){
 _mw_admin_files_manage('all');
});





 
mw.on.hashParam('path', function(){
  _mw_admin_files_manage('path', this);
});
mw.on.hashParam('search', function(){

    _mw_admin_files_manage('search', this);

});
mw.on.hashParam('sort_by', function(){

    _mw_admin_files_manage('sort_by', this);

});
mw.on.hashParam('sort_order', function(){

    _mw_admin_files_manage('sort_order', this);

});
 



</script>

<div id="mw_index_users">
  <div id="mw_edit_page_left" class="mw_edit_page_left"> Sort by
    <ul class="mw-ui-inline-selector">
      <li>
        <label class="mw-ui-check">
          <input name="module_show" class="mw_files_filter_show" type="radio" value="filemtime" checked="checked" onchange="mw.url.windowHashParam('sort_by', this.value)" />
          <span></span><span>Last modified</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="module_show"  class="mw_files_filter_show"  type="radio" value="filesize" onchange="mw.url.windowHashParam('sort_by', this.value)" />
          <span></span><span>Size</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="module_show"  class="mw_files_filter_show"  type="radio" value=""  onchange="mw.url.windowDeleteHashParam('sort_by')" />
          <span></span><span>Any</span></label>
      </li>
    </ul>
    Sort order
    <ul class="mw-ui-inline-selector">
      <li>
        <label class="mw-ui-check">
          <input name="is_active" class="mw_files_filter_show" type="radio" value="ASC" checked="checked" onchange="mw.url.windowHashParam('sort_order', this.value)" />
          <span></span><span>Up</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="is_active"  class="mw_files_filter_show"  type="radio" value="DESC" onchange="mw.url.windowHashParam('sort_order', this.value)" />
          <span></span><span>Down</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="is_active"  class="mw_files_filter_show"  type="radio" value="" onchange="mw.url.windowDeleteHashParam('sort_order')"  />
          <span></span><span>Any</span></label>
      </li>
    </ul>
  </div>
  <div style="padding: 20px;width: 750px;" class="mw_edit_page_right">
    <div class="modules-index-bar"> <span class="mw-ui-label-help font-11 left">Sort modules:</span>
      <input name="module_keyword" class="mw-ui-searchfield right" type="text" default="Search for modules"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
    </div>
    <div class="vSpace"></div>
    <div id="files_admin_<? print $rand  ?>" ></div>
    <div id="user_edit_admin_<? print $rand  ?>" ></div>
  </div>
</div>
<div class="mw_clear"></div>
<div class="vSpace"></div>
<center>
  <div class="mw-ui-btn-rect relative" style="width: 100px;height: 20px;">Upload Files
    <iframe scrolling="no" frameborder="0" src="<? print site_url('editor_tools/plupload') ?>" class="mw_upload_frame" id="upload_file_link" name="upload_file_link"></iframe>
  </div>
</center>
