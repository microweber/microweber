<? $dashboard_user = user_id_from_url();
 
?>
<div id="sidebar">
  <microweber module="users/new" dashboard_user="<? mw_var($dashboard_user) ?>" limit="12"></microweber>

  
 
   <microweber module="users/new" dashboard_user="<? mw_var($dashboard_user) ?>" limit="3"></microweber>
  
  
    <p align="right"><a class="mw_blue_link" href="<?php print site_url('dashboard/action:find-friends'); ?>">Find New Friends</a> | <a href="<?php print site_url('dashboard/action:my-friends'); ?>" class="mw_blue_link">See your friends</a></p>
   

   
 <br />

  <br />
   <? include(TEMPLATE_DIR.'banner_side.php')	; ?> 
   
  <br />
   <br />
  <?
  
  $var_params= array();
 
 
 $var_params['selected_categories'] =  array(1);
$var_params['fields'] =  array('id', 'content_title');
$var_params['items_per_page'] = 6;
$var_params['curent_page'] = rand(1,5);

if($dashboard_user != user_id()){
	$try_by_user = true;
							$var_params['voted_by'] = $dashboard_user;
  unset($var_params['curent_page']);
							  }

 $games =  get_posts($var_params);
 if(empty($games)){
	 if($try_by_user == true){
		 unset($var_params['voted_by']);
		 $games =  get_posts($var_params);
	 }
 } else {
	 if($try_by_user == true){
	 $voted_by_user = true;
	 }
 }
  
  ?>
  <? if($voted_by_user == false): ?>
  <h2>New games</h2>
  <? else: ?>
  <h2>Liked games by <? print  user_name($dashboard_user, 'first'); ?></h2>
  <? endif; ?>
  <ul class="games cl">
    <?

  
 shuffle( $games);
 ?>
    <? foreach($games as $item): ?>
    <li> <a href="<? print post_link($item['id']); ?>" class="img" style="background-image: url(<? print thumbnail($item['id'], 120) ?>)"></a> <a  href="<? print post_link($item['id']); ?>"><? print $item['content_title']; ?></a></li>
    <? endforeach; ?>
  </ul>
  
   <?
$voted_by_user = false;
  $var_params= array();
 
 
 $var_params['selected_categories'] =  array(6);
$var_params['fields'] =  array('id');
$var_params['items_per_page'] = 4;
$var_params['curent_page'] = rand(1,5);


//var_dump($params);
if($dashboard_user != user_id()){
	$try_by_user = true;
							$var_params['created_by'] = $dashboard_user;
  unset($var_params['curent_page']);
							  }

 $games =  get_posts($var_params);
 if(empty($games)){
	 if($try_by_user == true){
		 unset($var_params['created_by']);
		 $games =  get_posts($var_params);
	 }
 } else {
	 if($try_by_user == true){
	 $voted_by_user = true;
	 }
 }
 

 
 @shuffle( $games);
 ?>
  
  
  
  <? if($voted_by_user == false): ?>
    <h2>New Toys</h2>
  <? else: ?>
  <h2>Toys by <? print  user_name($dashboard_user, 'first'); ?></h2>
  <? endif; ?>
  
  
  
  
  

  <ul class="new-toys">
   
    <? foreach($games as $item):
   $item = get_post($item['id']);
   ?>
    <li> <a class="img" href="<? print post_link($item['id']); ?>"><img src="<? print thumbnail($item['id'], 120) ?>" alt="" /></a>
      <div class="toy_desc">
        <h4><a href="<? print post_link($item['id']); ?>"><? print $item['content_title']; ?></a></h4>
        <span><? print character_limiter(codeClean($item['content_body']), 100); ?></span> <strong class="red">$<? print $item["custom_fields"]['price'];?> </strong>
        <div class="c" style="padding-bottom: 5px;"></div>
        <a href="<? print post_link($item['id']); ?>" class="right">See more</a> </div>
    </li>
    <? endforeach; ?>
  </ul>
</div>
