<? $dashboard_user = user_id_from_url();
 
?>

<div id="sidebar">
  <microweber module="users/new" dashboard_user="<? mw_var($dashboard_user) ?>" limit="12"></microweber>
  <p align="right"><a class="mw_blue_link" href="<?php print site_url('dashboard/action:find-friends'); ?>">Find New Friends</a> | <a href="<?php print site_url('dashboard/action:my-friends'); ?>" class="mw_blue_link">See your friends</a></p>
  <br />
  <br />
    <h2>Invite your friends</h2>
<p align="left">
<img src="http://www.reeled.com/NewImages/invite_icon.jpg" alt="" height="95" align="left" hspace="2" onclick='window.open("http://skidekids.com/tell-a-friend/index.php", "invite","location=1,status=1,scrollbars=1, width=800,height=700");' style="cursor:pointer" />
Import your email addresses, then send invites to the friends you choose. <br /><br />
<a href="#" onclick='window.open("http://skidekids.com/tell-a-friend/index.php", "invite","location=1,status=1,scrollbars=1, width=800,height=700");' class="mw_blue_link"><strong>Click here to start!</strong></a></p>

  <br />
  <br />
  <br />
  <? include(TEMPLATE_DIR.'banner_dashboard_right_sidebar1.php')	; ?>
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
  
    <br />
   <? include(TEMPLATE_DIR.'banner_dashboard_right_sidebar2.php')	; ?> 
   
  <br />
</div>
