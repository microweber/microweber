<?php if(!isset($params['prefix'])): ?>
<?php return; ?>
<?php endif; ?>


<h2>Please enter the license key to activate</h2>
<h3><?php print $params['prefix'] ?></h3>

<module type="settings/group/license_edit" prefix="<?php print $params['prefix'] ?>" />

 