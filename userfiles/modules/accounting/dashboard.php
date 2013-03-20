<? 
 
 $numia_success_msg = mw_var('numia_success_msg');
  
?>
<? if($numia_success_msg != false): ?>
<div class="alert alert-success">
<h3><? print $numia_success_msg ?></h3>
 </div>
<? endif; ?>


<h2>Welcome to Numia</h2>
<ul>
  <li>Your access token is: <? print session_get('numia_token') ?></li>
   <li>Your numia company id is: <? print session_get('numia_company_id') ?></li>
</ul>
<form   method="post"    action="<? print $config['url'] ?>"  >
  <input    name="numia_logout" type="hidden"  value="1"   />
  <input class="btn" type="submit" value="<?php _e("Logout"); ?>" />
</form>
