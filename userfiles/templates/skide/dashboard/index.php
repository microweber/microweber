<? $dashboard_user = user_id_from_url();

 

?>

  <? if($dashboard_user != $user_id) : ?>

  <? 	$is_friend = CI::model('users')->realtionsCheckIfUserIsConfirmedFriendWithUser($user_id, $dashboard_user, $is_special = false); ?>

  

  <? else:  ?>

  

  <? $is_friend = true; ?>

   <? endif; ?>

   

   

<script type="text/javascript">

    $('document').ready(function(){

      $('div.embedly').embedly({maxWidth: 200, wrapElement: 'div' });

	  refesh_dashboard(<? print $dashboard_user ?>);

    });

	

	

function refesh_dashboard($user_id, $hide_friends, $page){

 

 

 <? if($is_friend  == true): ?>

 if($user_id == false){

	  $user_id = '<? print user_id() ?>';

  }

if(($hide_friends === undefined) || ($hide_friends == 'false')){
$hide_friends = false;	
}
if(($page === undefined)){
	$page = 0;
	
} else {
$page = parseInt($page);	
}
$new_page = $page+1;

if($new_page == 1){
$new_page = 2;	
}

//alert($hide_friends);


  	  $("#dashboard_more_link").attr("href", "javascript:refesh_dashboard('"+$user_id+"', '"+$hide_friends+"', '"+$new_page+"');")
  


  $.ajax({

  url: '<? print site_url('api/module') ?>',

  type: "POST",

  data: ({module : 'users/dashboard' ,user_id : $user_id <? if($dashboard_user != user_id()) : ?>,hide_friends : true  <? endif; ?>, hide_friends:$hide_friends, page:$page  }),

  async:true,

	success: function(resp) {


	$('#dashboard_more_link').fadeIn();
	$('#dashboard_content').fadeIn();
if($page == 0){
	$('#dashboard_content').html(resp);
} else {
	$('#dashboard_content').append(resp);
}
	$('div.embedly').embedly({maxWidth: 400, wrapElement: 'div' });

	

	

	}

  });

  <? else: ?>

  $('#dashboard_content').fadeIn();

  <? endif; ?>

}

	 

	

  </script>



<div id="wall">



<?php $has_fr_req = (CI::model('users')->realtionsCheckIfUserHasFriendRequestToUser(user_id(),$dashboard_user )) ;

//p($has_fr_req );

?> 



  <? if(($dashboard_user == $user_id) or !$dashboard_user ): ?>

  <?  $statusRow = CI::model('statuses')->statusesLastByUserId ($dashboard_user);

//p($statusRow);

?>

  <form method="post" action="#" id="update-status">

    <input type="text" name="status" value="<? print $statusRow['status']; ?>" onfocus="this.value=='<? print $statusRow['status']; ?>'?this.value='':''" onblur="this.value==''?this.value='<? print $statusRow['status']; ?>':''" />

    <a onclick="mw.users.User.statusUpdate('#update-status', function() {refesh_dashboard()});" class="xsubmit">Say</a> <small id="update-status-done" style="display:none">Status updated...</small>

  </form>

  <br />

  <? endif; ?>

  <? if($dashboard_user != false) : ?>

  <? if($dashboard_user != $user_id) : ?>

  <?php 

  



$to_user = $dashboard_user;

$author  = CI::model('users')->getUserById($to_user); ?>

  <a href="javascript:mw.users.UserMessage.compose(<?php echo $author['id']; ?>);" class="mw_btn_x"><span class="box-ico box-ico-msg title-tip" title="Send new message to <?php echo $author['first_name']; ?>" >Send a message</span></a>

  <?php if (CI::model('users')->realtionsCheckIfUserIsFollowedByUser(false,$to_user ) == false) : ?>

  <a href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>);" class="mw_btn_x_orange"><span class="box-ico box-ico-follow title-tip"  title="Add as friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>">Add as friend</span></a>

  <?php  else : ?>

  

  

  <a href="javascript:mw.users.FollowingSystem.unfollow(<?php echo $to_user?>);" class="mw_btn_x"><span class="box-ico box-ico-unfollow title-tip"  title="Remove friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>"><? if($has_fr_req == false): ?>Remove friend<? else: ?>Cancel friend request<? endif; ?></span></a>

  

  

  

  <? endif; ?>

  <? endif; ?>

  <? endif; ?>

  <? if($dashboard_user != $user_id) : ?>

  <h2><? print user_name($dashboard_user) ?>'s dashboard</h2>

  <?php  else : ?>

  <?

$data = array();

$data['parent_id'] = $dashboard_user;

$subusers = CI::model('users')->getUsers($data, $limit = false, $count_only = false);

 $subusers_ids = CI::model('core')->dbExtractIdsFromArray($subusers); 



 ?>

  <?  if(empty($subusers)): ?>

  <h2>My dashboard</h2>

  <?php  else : ?> 

  <div class="whatis_tabs">

    <ul class="tabnav">

      <li class="active"><a href="javascript:refesh_dashboard(<? print $dashboard_user ?>);" style="width: 189px">My dashboard</a></li>

      <li><a href="javascript:refesh_dashboard('<? print implode(',',  $subusers_ids) ?>',1);" style="width: 189px">My kids</a></li>

    </ul>

  </div>

  <? endif; ?>

  <? endif; ?>

  <br />

  <div id="dashboard_content" style="display:none">

  <h3>You must be friend with <? print user_name($dashboard_user) ?> in order to view its dashboard.</h3>

  

  

  <? if($has_fr_req == false): ?><? else: ?>

  <br />



  <h3 class="green">You have sent a friend request that needs to be approved by <? print user_name($dashboard_user) ?>.</h3>

  <? endif; ?>

  

  </div>
  
  
  <br />

  <div id="dashboard_more"><a  id="dashboard_more_link" style="display:none" class="mw_blue_link" href="">Show more</a></div>



  

  

</div>

<!-- /#wall -->

