<? if($parent_id == false){
$parent_id = user_id(); 
	
}?>
<?
$data = array();
$data['parent_id'] = $parent_id;
$subusers = CI::model('users')->getUsers($data, $limit = false, $count_only = false);
 

 ?>
 
 <script type="text/javascript">
function ajax_profile_edit($user_id){
	$.ajax({
  url: '{SITE_URL}api/module',
   type: "POST",
      data: ({module : 'users/profile' ,user_id : $user_id, hide_selector_on_save: '#edit_profile' }),
     // dataType: "html",
      async:false,

  success: function(resp) {

    var main = $.dataFind(resp, "#wall");
    var html = main.html();

    var width =  main.outerWidth() + 250;
    var height = main.outerHeight() + 150;
    modal.init({
       html:html,
       width:width,
       height:height,
       customPosition:{
         left:'center',
         top:20
       },
       oninit:function(){
         $(this).find(".mw_btn_x").attr("class", "btn");
       }
    });
    modal.overlay();


  }
	});
}


function ajax_account_extend($user_id){
	$.ajax({
  url: '{SITE_URL}api/module',
   type: "POST",
      data: ({module : 'orders/user_account_upgrade' ,user_id : $user_id ,redirect_to : "<? print url(); ?>",redirect_to_on_cancel : "<? print url(); ?>"  }),
     // dataType: "html",
      async:false,
	  
  success: function(resp) {

    var width = $.dataFind(resp, "#plans").width() + 70;
    var height = $.dataFind(resp, ".bluebox:first").outerHeight() + 70;

   //$('#edit_profile<? print ($post['id']); ?>').html(resp);
    //$('#edit_profile<? print ($post['id']); ?>').fadeIn();
    modal.init({
       html:resp,
       width:width,
       height:height,
       oninit:function(){
         setTimeout(function(){
           $("#main_side").attr("id", "wall");
         }, 10);

       }
    });
    modal.overlay();

  }
	});
}
</script>
 <div id="edit_profile"></div>
 
<?  if(empty($subusers)): ?>

<h2><? print $no_subusers_text ?></h2>
<br />
<a class="mw_btn_x" href="<? print $no_subusers_link ?>"><span><? print $no_subusers_link_text ?></span></a>
<? else: ?>
<? foreach($subusers as $item): ?>
<div class="bluebox">
  <div class="blueboxcontent">

  <h3 style="padding-bottom: 5px"><? print user_name($item['id']) ?></h3>


  <? 
  $expires = $item['expires_on'];
  $expires = strtotime($expires);
   $expires2 = strtotime('now');
  if($expires < $expires2)  :?>
  <span class="red">account not active</span><br />
  <a href="<? print site_url('dashboard/action:extend-account/ns:true/user_id:'); ?><? print $item['id'] ?>" class="mw_btn_x_orange left"><span>Enable Account</span></a>
  <? else : ?>
  <span class="right">


 <a href="<? print site_url('dashboard/action:extend-account/ns:true/user_id:'); ?><? print $item['id'] ?>" class="btn left"><span>Extend Account</span></a>
 
 
  <a href="<? print site_url('api/user/loginas') ?>/id:<? print $item['id'] ?>" class="btn left" style="margin-left:7px"><span>Login</span></a>
  </span>
  <span class="green">account active until <? print $item['expires_on'] ?></span>
  <br />
  <a class="mw_blue_link" href="javascript: ajax_profile_edit(<? print $item['id'] ?>)"><span>Change</span></a>


<? /*
  | <a  href="#"><span>Disable</span></a>
*/ ?> <br />


  <? endif; ?>
  
  <div id="user_account_upgrade_<? print $item['id'] ?>">
  
  
  
  </div>

  </div>
</div>
<div class="c" style="padding-bottom: 10px;">&nbsp;</div>
<? endforeach; ?>
<? /*<h3 class="red">Acoount disabled</h3>
<br />
<h3 class="green">Acoount Enabled</h3>
<br />
<form class="form" id="manage_kids_form">
  <div class="bluebox">
    <div class="blueboxcontent">
      <label>Change Username of John Malkovich</label>
      <span>
      <input type="text" name="kid_name" />
      </span>
      <label>Set new password</label>
      <span>
      <input type="password" class="required-equal" name="kid_pass_1" equalto="change_kid_pass" />
      </span>
      <label>Retype new password</label>
      <span>
      <input type="password" class="required-equal" name="kid_pass_2" equalto="change_kid_pass" />
      </span> </div>
  </div>
  <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
  <a href="#" class="mw_btn_x right submit"><span>Change</span></a> <a href="#" class="mw_btn_x_orange left"><span>Disable Acoount</span></a>
</form>*/ ?>
<? endif; ?>
