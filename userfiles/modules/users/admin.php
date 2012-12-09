<?
 if(is_admin() == false) { error("Must be admin"); }
 $rand = uniqid(); ?>
<script  type="text/javascript">



mw.require('forms.js');


$(document).ready(function(){
    

 

});


</script>
<script  type="text/javascript">
 


$(document).ready(function(){
  //mw_show_users_list();
});

function mw_show_users_list(){

var ui = mw.url.getHashParams(window.location.hash).ui;


 if(ui  == undefined){
	 
	ui  = mw.$('#mw_index_users input.mw_users_filter_show:checked').first().val();
	// mw.log(ui);
	 
 }



 if(ui  !== undefined){
    	mw.$('#users_admin_<? print $rand  ?>').attr('data-show-ui',ui);
    } else {
    	mw.$('#users_admin_<? print $rand  ?>').removeAttr('data-show-ui');
    }

 
 
 var search = mw.url.getHashParams(window.location.hash).search;
  if(search  !== undefined){
    	mw.$('#users_admin_<? print $rand  ?>').attr('data-search-keyword',search);
    } else {
    	mw.$('#users_admin_<? print $rand  ?>').removeAttr('data-search-keyword');
    }
	
	
	 var is_admin = mw.url.getHashParams(window.location.hash).is_admin;
	 if(is_admin  !== undefined && parseInt(is_admin)  !== 0){
  	mw.$('#users_admin_<? print $rand  ?>').attr('data-is_admin',is_admin);
  } else {
  	mw.$('#users_admin_<? print $rand  ?>').removeAttr('data-is_admin');
  }
  
  
  	 var installed = mw.url.getHashParams(window.location.hash).installed;
	 if(installed  !== undefined){
  	mw.$('#users_admin_<? print $rand  ?>').attr('data-installed',installed);
  } else {
  	mw.$('#users_admin_<? print $rand  ?>').removeAttr('data-installed');
  }
  
	

// mw.reload_module('#users_admin_<? print $rand  ?>');

mw.load_module('users/edit_user','#user_edit_admin_<? print $rand  ?>');


}


_mw_admin_users_manage = function(){

    var attrs = mw.url.getHashParams(window.location.hash);

    var holder = mw.$('#users_admin_<? print $rand ?>');

    var arr = ['data-show-ui','data-search-keyword','data-category','data-installed'], i=0, l=arr.length;

    var sync = ['ui','search','category','installed'];

    for(;i<l;i++){
        holder.removeAttr(arr[i]);
    }
    for (var x in attrs){
    if(x==='category' && (attrs[x]==='0' || attrs[x]===undefined)) continue;
        holder.attr(x, attrs[x]);
    }
    mw.load_module('users/manage','#users_admin_<? print $rand ?>');
}


$(window).bind("load", function(){
 _mw_admin_users_manage();
});





_mw_admin_user_edit = function(){


    var attrs = mw.url.getHashParams(window.location.hash);

    mw.$('#user_edit_admin_<? print $rand ?>').remove();

    $(".to_edit").html($(".to_edit").data('html'));
    $(".to_edit").removeClass('to_edit')


    var p = mw.$('#mw-admin-user-'+attrs['edit-user']).html();
     mw.$('#mw-admin-user-'+attrs['edit-user']).addClass('to_edit').data('html', p);

    mw.$('#mw-admin-user-'+attrs['edit-user']).empty().html('<td colspan="6"><div id="user_edit_admin_<? print $rand ?>"></div></td>');

    var holder = mw.$('#user_edit_admin_<? print $rand ?>');

    for (var x in attrs){
    	if(x=='edit-user'){
    	    holder.attr(x, attrs[x]);
    	}
    }

    mw.load_module('users/edit_user','#user_edit_admin_<? print $rand ?>');



}
 

mw.on.hashParam('is_admin', _mw_admin_users_manage);
mw.on.hashParam('search', _mw_admin_users_manage);
mw.on.hashParam('is_active', _mw_admin_users_manage);
mw.on.hashParam('sortby', _mw_admin_users_manage);
mw.on.hashParam('edit-user', function(){
  !!this ? _mw_admin_user_edit() : '';
});






</script>

<div id="mw_index_users">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px"> Role
    <ul class="mw-ui-inline-selector">
      <li>
        <label class="mw-ui-check">
          <input name="module_show" class="mw_users_filter_show" type="radio" value="n" checked="checked" onchange="mw.url.windowHashParam('is_admin', this.value)" />
          <span></span><span>User</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="module_show"  class="mw_users_filter_show"  type="radio" value="y" onchange="mw.url.windowHashParam('is_admin', this.value)" />
          <span></span><span>Admin</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="module_show"  class="mw_users_filter_show"  type="radio" value=""  onchange="mw.url.windowDeleteHashParam('is_admin')" />
          <span></span><span>Any</span></label>
      </li>
    </ul>
    Status
    <ul class="mw-ui-inline-selector">
      <li>
        <label class="mw-ui-check">
          <input name="is_active" class="mw_users_filter_show" type="radio" value="y" checked="checked" onchange="mw.url.windowHashParam('is_active', this.value)" />
          <span></span><span>Active users</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="is_active"  class="mw_users_filter_show"  type="radio" value="n" onchange="mw.url.windowHashParam('is_active', this.value)" />
          <span></span><span>Disabled users</span></label>
      </li>
      <li>
        <label class="mw-ui-check">
          <input name="is_active"  class="mw_users_filter_show"  type="radio" value="" onchange="mw.url.windowDeleteHashParam('is_active')"  />
          <span></span><span>All</span></label>
      </li>
    </ul>
    <div class="tree-show-hide-nav"><a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn mw-ui-btn-small">Add new user</a>
    <br>
<a href="javascript:mw.url.windowDeleteHashParam('edit-user');" class="mw-ui-btn mw-ui-btn-small">Show all</a></div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px 0 0 20px;">
    <div class="modules-index-bar"> <span class="mw-ui-label-help font-11 left">Sort modules:</span>
      <input name="module_keyword" class="mw-ui-searchfield right" type="text" default="Search for modules"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
      <ul class="mw-ui-inline-selector">
        <li>
          <label class="mw-ui-check">
            <input name="sortby" class="mw_users_filter_show" type="radio" value="created_on desc" checked="checked" onchange="mw.url.windowHashParam('sortby', this.value)" />
            <span></span><span>Date created</span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="sortby"  class="mw_users_filter_show"  type="radio" value="created_on desc" onchange="mw.url.windowHashParam('sortby', this.value)" />
            <span></span><span>Last login</span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="sortby"  class="mw_users_filter_show"  type="radio" value="username asc"  onchange="mw.url.windowHashParam('sortby', this.value)" />
            <span></span><span>Username</span></label>
        </li>
      </ul>
    </div>
    <div class="vSpace"></div>
     <div id="users_admin_<? print $rand  ?>" ></div>

     <div id="user_edit_admin_<? print $rand  ?>" ></div>
  </div>
</div>
