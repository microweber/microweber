<?

/**
 * 
 * 
 * Generic module to display a friends list for given user. Has the ability to search users too.
 * @author Peter Ivanov
 * @package users


Example:
 
<mw module="users/friends"  wrap_element='div'  wrap_element_items='div' wrap_element_class='field friend_request' wrap_element_items_class='fieldcontent' show_user_controls="true"  />

 
 
 
 //params for the data
 @param $user_id | the id of the user you want to get frends for | default:auto
 @param $limit | limit the results | default:auto
 
 //params for display
 @param $wrap_element | wraps the results in those html tags | default:'ul'
 @param $wrap_element_items | wraps the items in this html tag | default:'li'
 @param $wrap_element_class | use this class for the wrap elemet | default:'user_friends_list'
 @param $wrap_element_items_class | use this class for each of the result elements | default: false
 
 @param $format | if you set json it will return json | default: false
 
 @param $show_user_controls | if true will show the add/remove friend button and the send msg button| default: false
 



 */

?>





<? if($wrap_element == false){
	
$wrap_element = 'ul';	
}?>

<? if($wrap_element_items == false){
	
$wrap_element_items = 'li';	
}?>

<? if($wrap_element_class == false){
	
$wrap_element_class = 'user_friends_list';	
}?>

<? if($wrap_element_items_class == false){
	
$wrap_element_items_class = '';	
}?>








<? $user_id = user_id_from_url(); ?>
<? if($user_id == false){ $user_id = user_id(); } ?>
<?
 
$orig_params = $params;
?>
<? 
$query_options = array();
if($orig_params['limit']){
$query_options['limit'] = $orig_params['limit'];
}

if(trim($orig_params['keyword']) != ''){
$query_options['search_keyword'] = trim($orig_params['keyword']);


}

 
$users = CI::model('users')->realtionsGetFollowedIdsForUser($aUserId = $user_id, $special = false, $query_options );

 
?>







 
            
            
            





<? if(!empty($users)): ?>
<? if($format == 'json'){
	$items = array();
	foreach($users as $item){
		$urs1 = get_user($item);
		$urs1['picture'] = user_thumbnail($item, 20);
		 $items[] = $urs1;
	}
	print json_encode($items);
	exit();
	
}?>


<? if($show_results_count_title): ?>



 <h2 style="padding-bottom: 10px;"><strong>&nbsp;<?  print count($users);?></strong> search results</h2>




 <? endif; ?>
 
 
<<?php  print $wrap_element; ?> class="<?php  print $wrap_element_class; ?>">
  <? foreach($users as $item): ?>
  <? $user = get_user($item); 

  
  ?>

  <? if($show_user_controls != false) :  ?>

 <? $to_user =$user['id']  ?>
  <a href="javascript:mw.users.UserMessage.compose(<?php echo $user['id']; ?>);" class="mw_btn_s right" style="margin-right:5px;"><span   title="Send new message to <?php echo $user['first_name']; ?>" >Send a message</span></a>

  <?php if (CI::model('users')->realtionsCheckIfUserIsFollowedByUser(false,$user['id'] ) == false) : ?>
  <a href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>);" class="mw_btn_s_orange right"><span    title="Add as friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>">Add as friend</span></a>
  <?php  else : ?>
  <a id="unfollow_<?php echo $to_user?>" href="javascript:mw.users.FollowingSystem.unfollow(<?php echo $to_user?>, '#unfollow_<?php echo $to_user?>');" class="mw_btn_s right"><span    title="Remove friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>">Remove friend</span></a>
  <? endif; ?>





<? endif ?>
  <<?php  print $wrap_element_items; ?>  class="<?php  print $wrap_element_items_class; ?>" > <a href="<?php print site_url('userbase/action:profile/username:'.$user['username']) ?>" class="img"><span style="background-image: url(<? print user_thumbnail($item, 60) ?>)"></span>     <strong><? print user_name($item); ?></strong></a> 



  

  <div class="c"></div><br />

  
  </<?php  print $wrap_element_items; ?>>
  <? endforeach; ?>
</<?php  print $wrap_element; ?>>
<? else: ?>
Nothing found
<? endif; ?>
<br class="c" />
<br />



