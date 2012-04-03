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

 
$users_ids = CI::model('users')->realtionsGetFollowedIdsForUser($aUserId = $user_id, $special = false, $query_options );
 
 
?>







 
            
            
            





<? if(!empty($users_ids)): ?>
<? if($format == 'json'){
	$items = array();
	foreach($users_ids as $item){
		$urs1 = get_user($item);
		$urs1['picture'] = user_thumbnail($item, 20);
		 $items[] = $urs1;
	}
	print json_encode($items);
	exit();
	
}?>

<? include('list.php'); ?>
<? endif; ?>

