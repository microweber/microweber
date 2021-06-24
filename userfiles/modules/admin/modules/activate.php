<?php if (!isset($params['prefix'])): ?>
    <?php app()->content_manager ?>
<?php endif; ?>

<module type="settings/group/license_edit" prefix="<?php print $params['prefix'] ?>"/>

