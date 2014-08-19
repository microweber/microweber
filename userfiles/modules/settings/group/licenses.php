<?php only_admin_access(); ?>
<?php $lic = mw()->update->get_licenses(); ?>
<?php if(is_array($lic) and !empty($lic)): ?>
<?php foreach($lic as $item): ?>
<?php d($item); ?>
<?php endforeach; ?>
<?php endif; ?>
