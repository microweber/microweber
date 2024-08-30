<?php must_have_access(); ?>

<?php

$filter = '';
if(isset($params['template-filter'])){
    $filter =' template-filter='.trim($params['template-filter']);

}
?>

<module type="admin/modules/templates_layouts" parent-module="layouts" parent-module-id="<?php print $params['id'] ?>" live_edit="false" data-screenshots="true" data-search="true" <?php print $filter ?>   />
