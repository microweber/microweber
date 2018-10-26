<?php only_admin_access() ?>
<?php
if (isset($params['for-module-id'])) {
    $params['id'] = $params['for-module-id'];

}


?>



<script>




</script>



<a href="javascript:window.parent.mw.quick.edit('0','post', '', '0', '');" class="mw-ui-btn mw-ui-btn-medium"><span class="mai-website"></span> Add new</a>
<a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium"><span class="mai-website"></span> Manage posts</a>


<hr>
<?php

$is_shop = false;

if (isset($params['is_shop'])) {
    $is_shop = $params['is_shop'];
}

$dir_name = normalize_path(modules_path());

$posts_mod = $dir_name . 'content' . DS . 'admin_live_edit_tab1.php';
?>


<hr>
<?php include($posts_mod); ?>



<hr>

<module type="admin/modules/templates"  parent-module-id="<?php print $params['id'] ?>"    for-module="<?php print $params['type'] ?>"     />

