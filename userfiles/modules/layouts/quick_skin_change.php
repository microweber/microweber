<?php only_admin_access(); ?>


<?php

if(!isset($params['data-parent-module']) or !isset($params['data-parent-module-id'])){
    return;
}


?>

<module type="admin/modules/templates_layouts" for-module="<?php print $params['data-parent-module'] ?>" for-module-id="<?php print $params['data-parent-module-id'] ?>" data-screenshots="true" data-search="true"     data-small-view="true"   data-skin-change-mode="true"     />