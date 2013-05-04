<?php 
 
 $numia_success_msg = mw_var('numia_success_msg');
  $api_token = session_get('numia_token');
?>
<?php if($numia_success_msg != false): ?>

<div class="alert alert-success">
  <h3><?php print $numia_success_msg ?></h3>
</div>
<?php endif; ?>
<h2>Welcome to Numia</h2>
<ul>
  <li>Your access token is: <?php print $api_token  ?></li>
  <li>Your numia company id is: <?php print session_get('numia_company_id') ?></li>
</ul>
<form   method="post"    action="<?php print $config['url'] ?>"  >
  <input    name="numia_logout" type="hidden"  value="1"   />
  <input class="btn" type="submit" value="<?php _e("Logout"); ?>" />
</form>
<?php 
$numia_api = new \accounting\numia\api();
//GETTING THE CUSTOMER LIST//
$params = array(
	"api_token" 		=> $api_token,
	"limit"				=> 10,
	"country"			=> "United States",
	"page"				=> 1
);
$result = $numia_api -> call('customer_list', $params);

echo "<pre>";
print_r($result);
print('<hr>');

