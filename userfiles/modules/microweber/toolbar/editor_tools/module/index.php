<?php
$uid = uniqid();

$type = '';
$params = array();


if (array_key_exists('type', $_GET)) {
    $type = $_GET['type'];
}
if (array_key_exists('params', $_GET)) {
    $params = explode(',', $_GET['params']);
}
$type = xss_clean($type);
$params = xss_clean($params);
?>




<div id="container">
    <module type="<?php print $type; ?>" <?php foreach($params as $key => $val){ print $key . '="' .  $val . '" '; } ?>   />
</div>
