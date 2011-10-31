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
	
$wrap_element_class = 'users_list';
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



 if($ids){

 $ids = decode_var($ids); 
if(!empty($ids)){
	$user_data['ids'] = $ids;
}

 }


 
$limits = array();
$limits[0] = (intval($paging_curent_page) - 1) * $query_options['limit'] ;
$limits[1] =  (intval($paging_curent_page)) * $query_options['limit'] ;
 
$users = CI::model('users')->getUsers($user_data, $limits, $count_only = false);
$users_count = CI::model('users')->getUsers($user_data, $limit = false, $count_only = true);
 
$pagenum = ceil($users_count/$query_options['limit']);
$paging = CI::model('content')->pagingPrepareUrls(url(), $pagenum, 'users-page');


?>
<? //$edit_url = CI::model('core')->urlConstruct($base_url = false, $params = array('edit_user'=>0)) 

//P($users);
?>

<h2 style="padding-bottom: 10px;"><strong>&nbsp;
  <?  print ($users_count);?>
  </strong> users</h2>
<? if(!empty($users)): ?>
<table width="100%">
  <? foreach($users as $item): ?>
  <? $user = ($item); ?>
  <? $edit_url =   site_url('admin/action:users/edit_user:'); ?>
  <tr id="user_row_<? print $item['id'] ?>">
    <!--<td><img src="<? // print user_thumbnail($user['id'], 70) ?>" height="24" /></td>-->
    <td><a href="<?php print $edit_url ?>"><strong><? print user_name($item['id']); ?></strong></a></td>
    <td><a class="btn" href="<?php print $edit_url ?><?php print $item['id'] ?>">Edit</a></td>
    <td><a  href="javascript:mw.users.delete_user('<? print $item['id'] ?>', '#user_row_<? print $item['id'] ?>')">Delete</a></td>
  </tr> 
  <? endforeach; ?>
</table>
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
