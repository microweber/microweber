<?php
if (isset($params['for-module'])) {
    $params['parent-module'] = $params['for-module'];
}
if (!isset($params['parent-module'])) {
    return;
}

$v_mod = $params['parent-module'];

$module = mw()->module_manager->get('one=1&ui=any&module=' . $v_mod);
?>


