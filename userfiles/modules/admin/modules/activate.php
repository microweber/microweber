<?php if(!isset($params['prefix'])): ?>
<?php return; ?>
<?php endif; ?>
<br />
<h1>Almost done!</h1>
<h4>Check your email and copy the license key you received on purchase</h4>
<br />
<h2>Enter the license key to activate <em><?php print $params['prefix'] ?></em></h2>
 
<module type="settings/group/license_edit" prefix="<?php print $params['prefix'] ?>" />

 