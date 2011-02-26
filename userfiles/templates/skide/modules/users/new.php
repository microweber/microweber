<?
 //var_dump($params);
$orig_params = $params;
if(!$limit){
$limit = 12;	
}
?>

<h2>New on Skid-e-Kids</h2>
<ul class="user_friends_list <? print $list_class ?>">
  <? $users = get_new_users('300 days', 50);  ?>
  <? if(!empty($users)): 
  shuffle($users);
  $users = array_slice($users, 0, $limit);
  ?>
  <? foreach($users as $item): ?>
  <? $user = get_user($item); ?>
  <li> <a href="<?php print profile_link($item); ?>"> <span style="background-image: url(<? print user_thumbnail($item, 75) ?>)"></span> <strong><? print user_name($item); ?></strong> </a> </li>
  <? endforeach; ?>
  <? endif; ?>
</ul>
<br class="c" />
<br />

