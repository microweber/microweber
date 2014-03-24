<?php
if (!defined('USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("USER_IP", '127.0.0.1');

    }
}
$check = mw('log')->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&user_ip=" . USER_IP);
$job = mw('Utils\Backup')->cronjob(array('type' => 'full'));
$check = mw('log')->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);
if (isset($check['value'])) {
    if ($check['value'] == 'reload') {
        ?>
        <script type="text/javascript">

            $(document).ready(function () {

                mw.reload_module('admin/backup/manage');

            });

        </script>
    <?php
    } else {
        if ($check['value'] != '') {

            ?>
            <?php print html_entity_decode($check['value']) ?>
        <?php
        }
    }
} else {
    if (is_array($job) and !empty($job)) {
        print count($job) . '  items remaining. ';
    }
}
