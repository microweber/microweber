<?
 if(is_admin() == false) { error("Must be admin"); }
 //$rand = uniqid(); ?>
<script  type="text/javascript">



mw.require('forms.js');


$(document).ready(function(){
    

 

});


</script>
<script  type="text/javascript">

hash = function(a){

}


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
    	mw.$('#users_admin_panel').attr('data-show-ui',ui);
    }

    else {
    	mw.$('#users_admin_panel').removeAttr('data-show-ui');
    }

 
 
 var search = mw.url.getHashParams(window.location.hash).search;
  if(search  !== undefined){
    	mw.$('#users_admin_panel').attr('data-search-keyword',search);
    } else {
    	mw.$('#users_admin_panel').removeAttr('data-search-keyword');
    }
	
	
	 var is_admin = mw.url.getHashParams(window.location.hash).is_admin;
	 if(is_admin  !== undefined && parseInt(is_admin)  !== 0){
  	mw.$('#users_admin_panel').attr('data-is_admin',is_admin);
  } else {
  	mw.$('#users_admin_panel').removeAttr('data-is_admin');
  }

  
  	 var installed = mw.url.getHashParams(window.location.hash).installed;
	 if(installed  !== undefined){
  	mw.$('#users_admin_panel').attr('data-installed',installed);
  } else {
  	mw.$('#users_admin_panel').removeAttr('data-installed');
  }
  
	

// mw.reload_module('#users_admin_panel');

mw.load_module('users/edit_user','#user_edit_admin_panel');


}


_mw_admin_users_manage = function(){

    var attrs = mw.url.getHashParams(window.location.hash);

    var holder = mw.$('#users_admin_panel');

    var arr = ['data-show-ui','data-search-keyword','data-category','data-installed'], i=0, l=arr.length;

    var sync = ['ui','search','category','installed'];

    for( ; i<l; i++){
        holder.removeAttr(arr[i]);
    }
    for (var x in attrs){
    if(x==='category' && (attrs[x]==='0' || attrs[x]===undefined)) continue;
        holder.attr(x, attrs[x]);
    }



    mw.load_module('users/manage','#users_admin_panel', function(){
      TableLoadded = true;
      var params = mw.url.getHashParams(window.location.hash);
      if(params['edit-user'] !== undefined){
          _mw_admin_user_edit();
      }
    });



}

TableLoadded = false;

$(window).bind("load", function(){

  var hash = mw.url.getHashParams(window.location.hash);
  if(hash['edit-user'] === undefined){
    if(hash.sortby === undefined){
       mw.url.windowHashParam('sortby', 'created_on desc');
    }
  }
  else{

  }



});





_mw_admin_user_edit = function(){
    var attrs = mw.url.getHashParams(window.location.hash);
    var holder = mw.$('#user_edit_admin_panel');
    if(attrs['edit-user'] !== undefined && attrs['edit-user'] !== ''){
        holder.attr('edit-user', attrs['edit-user']);
        mw.load_module('users/edit_user','#user_edit_admin_panel', function(){
              mw.cache.save();
              if(typeof UsersRotator === 'undefined') {
                 UsersRotator = mw.tools.simpleRotator(mwd.getElementById('mw-users-manage-edit-rotattor'));
              }
              UsersRotator.go(1, function(){
                mw.tools.scrollTo(mwd.querySelector('#mw_toolbar_nav'));
              });
        });
    }
}


mw.on.hashParam('is_admin', function(){
    _mw_admin_users_manage();
    mw.url.hashParamToActiveNode('is_admin', 'mw-users-is-admin');
});
mw.on.hashParam('search', function(){
    _mw_admin_users_manage();
});
mw.on.hashParam('is_active', function(){
    _mw_admin_users_manage();
    mw.url.hashParamToActiveNode('is_active', 'mw-users-is-active');
});
mw.on.hashParam('sortby', function(){
    _mw_admin_users_manage();
});
mw.on.hashParam('edit-user', function(){
  if(this == false){
     _mw_admin_users_manage();
     UsersRotator.go(0);
     mw.$('.modules-index-bar, .manage-items').visible();
  }
  else if(this != false && TableLoadded){
      _mw_admin_user_edit();
      mw.$('.modules-index-bar, .manage-items').invisible();
  }
});



function mw_admin_delete_user_by_id($user_id){
	var r=confirm("Are you sure you want to delete this user?")
	if (r==true){
		data = {};
		data.id = $user_id
	   $.post("<? print api_url() ?>delete_user",data, function() {
		   _mw_admin_users_manage();
		});
	  }
 
}





</script>

<div id="mw_index_users">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 class="mw-side-main-title"><span class="ico iusers-big"></span><span>Users</span></h2>
    <div class="vSpace"></div>
    <a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn" style="width: 144px;margin-left: 12px;"> <span class="ico iplus"></span><span>Add new user</span> </a>
    <div class="vSpace"></div>
    <div class="manage-items">
      <label class="mw-side-nav-label">Sort Users by Roles</label>
      <ul class="mw-admin-side-nav">
        <li> <a class="mw-users-is-admin mw-users-is-admin-n" href="javascript:;" onclick="mw.url.windowHashParam('is_admin', 'n');">User</a> </li>
        <li> <a class="mw-users-is-admin mw-users-is-admin-y" href="javascript:;" onclick="mw.url.windowHashParam('is_admin', 'y');">Admin</a> </li>
        <li> <a class="mw-users-is-admin mw-users-is-admin-none active" href="javascript:;" onclick="mw.url.windowDeleteHashParam('is_admin');">All</a> </li>
      </ul>
      <label class="mw-side-nav-label">Sort Users by Status</label>
      <ul class="mw-admin-side-nav">
        <li> <a class="mw-users-is-active mw-users-is-active-y" href="javascript:;" onclick="mw.url.windowHashParam('is_active', 'y');">Active users</a> </li>
        <li> <a class="mw-users-is-active mw-users-is-active-n" href="javascript:;" onclick="mw.url.windowHashParam('is_active', 'n');">Disabled users</a> </li>
        <li> <a class="mw-users-is-active mw-users-is-active-none active" href="javascript:;" onclick="mw.url.windowDeleteHashParam('is_active');">All users</a> </li>
      </ul>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px 0 0 20px;width: 757px;">
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
    <div class="mw-simple-rotator" style="width: 757px">
      <div class='mw-simple-rotator-container' id="mw-users-manage-edit-rotattor">
        <div id="users_admin_panel" ></div>
        <div id="user_edit_admin_panel"></div>
      </div>
    </div>
  </div>
</div>
