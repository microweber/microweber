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

$paging_curent_page = url_param('users-page');
if($paging_curent_page == false){
$paging_curent_page = 1;	
}
if(isAjax == true){
	//$paging_curent_page = 1;
}
  if(trim($orig_params['keyword']) != ''){
	  $paging_curent_page = 1;
  }
$query_options['limit'] = 30;

$query_options = array();
if($orig_params['limit']){
$query_options['limit'] = $orig_params['limit'];
} else {
$query_options['limit'] = 30;
}



if($orig_params['tn_size']){
	$tn_size = $orig_params['tn_size'];
 } else {
	 $tn_size = 70;
 }



$user_data = array();
foreach($orig_params as $k => $v){
 if(stristr('data_', $k)){
	$k = str_ireplace('data_', '',$k );
	$user_data[$k] = $v;
 }
}

 if(trim($orig_params['keyword']) != ''){
$user_data['search_keyword'] = trim($orig_params['keyword']);
}



 if(trim($orig_params['is_admin']) != ''){
$user_data['is_admin'] = 'y';
}



 if($ids){

 $ids = decode_var($ids); 
if(!empty($ids)){
	$user_data['ids'] = $ids;
}

 }
 
 
  if($users_ids){

 if(!empty($users_ids)){
	$user_data['ids'] = $users_ids;
}


// p($users_ids);
}

 




 
$limits = array();
$limits[0] = (intval($paging_curent_page) - 1) * $query_options['limit'] ;
$limits[1] =  (intval($paging_curent_page)) * $query_options['limit'] ;

 
$users = CI::model('users')->getUsers($user_data, $limits, $count_only = false);
//$users_count = CI::model('users')->getUsers($user_data, $limit = false, $count_only = true);
 
 
$pagenum = ceil($users_count/$query_options['limit']);
$paging = $this->content_model->pagingPrepareUrls(url(), $pagenum, 'users-page');

 
?>
 
 
            
            
            

 





<? if(!empty($users)): ?>
<? if($show_results_count_title): ?>



 



 <? endif; ?>
 
 
<<?php  print $wrap_element; ?> class="<?php  print $wrap_element_class; ?> users-list">
  <? foreach($users as $item): ?>
  <? $user = ($item); 

  
  ?>

<? // p($item); ?>
  <? if($show_user_controls != false) :  ?>

 <? $to_user =$user['id']  ?>




 <<?php  print $wrap_element_items; ?>  class="<?php  print $wrap_element_items_class; ?> inner_user inner_user_<?php echo $to_user?> is_admin_<?php echo $user['is_admin']?>" >
 



 
  <a href="<?php print user_link($user['id']) ?>" class="img is_admin_<?php echo $user['is_admin']?>" style="background-image: url(<? print user_thumbnail($user['id'], $tn_size) ?>)" ></a>


  <a href="<?php print user_link($user['id']) ?>"><strong><? print user_name($item['id']); ?></strong> <?php if($user['is_admin'] == 'y'):?>(admin)<? endif ?></a>
  
  
  <div class="inner_user_info">
 
  
  <? $a = cf_get_user($user['id'],'about'); ?>
  <? if( $a != '') : ?>
 <? print character_limiter($a, 350); ?>
 <? endif; ?>
 
</div>
  
  
  
   <a href="javascript:mw.users.UserMessage.compose(<?php echo $user['id']; ?>);" class="send_msg"  title="Send new message to <?php echo $user['first_name']; ?>"></a> 
  
 <div class="inner_user_links">
 <? if($user['id'] != user_id()) : ?>
   <a href="javascript:mw.users.UserMessage.compose(<?php echo $user['id']; ?>);" class="send_msg"><span   title="Send new message to <?php echo $user['first_name']; ?>" >Message</span></a>
   <? endif; ?>
 
<?php if (CI::model('users')->realtionsCheckIfUserHasFriendRequestToUser(user_id(),$to_user ) == false) : ?>


  <?php if (CI::model('users')->realtionsCheckIfUserIsFollowedByUser(false,$user['id'] ) == false) : ?>
  <a  id="follow_btn_<?php echo $to_user?>" href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>, 0, '.inner_user_<?php echo $to_user?>');" class="add_request" title="Add as friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>"><span>Add as friend</span></a>
  <?php  else : ?>

  <? endif; ?>
  <?php  else : ?>
  <? if($to_user != user_id()) : ?>
 <!-- <a title="Cancel request" id="unfollow_<?php echo $to_user?>" href="javascript:mw.users.FollowingSystem.unfollow(<?php echo $to_user?>, '.inner_user_<?php echo $to_user?>');" class="cancel_request">

  <span title="Remove friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>">Cancel request</span></a>-->
  <? endif; ?>
  <? if($to_user == user_id()) : ?>
  <a id="follow_btn_<?php echo $to_user?>" style="margin-top: 1px" href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>,0,'.inner_user_<?php echo $to_user?>');" class="confirm_request" title="Add as friend <?php print CI::model('users')->getPrintableName($to_user, 'first'); ?>"><span>Confirm friend</span></a>
<? endif; ?>


   <? endif; ?>
   
   
   

  </div>

 

  
  </<?php  print $wrap_element_items; ?>>
 


<? endif ?>
 
  <? endforeach; ?>
</<?php  print $wrap_element; ?>>




 <?php if(!$keyword): ?>

<?php if(!empty($paging)): ?>
 <div class="paging">
  <?php $i = 1; foreach($paging as $page_link) : ?>
  <span <?php if($paging_curent_page == $i) : ?>  class="active"  <?php endif; ?>><a  href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></span>
  <?php $i++; endforeach;  ?>
</div>
<?php endif ; ?>

       <?php endif ; ?>





<? else: ?>
Nothing found.
<? endif; ?>



