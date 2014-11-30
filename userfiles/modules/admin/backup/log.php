<?php
if (!defined('USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("USER_IP", '127.0.0.1');

    }
}
$check = mw()->log_manager->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&user_ip=" . USER_IP);
$job = mw('Utils\Backup')->cronjob(array('type' => 'full'));
$check = mw()->log_manager->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);
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

 
    $total = $remaining = count($job);
	if($total > 0){
		if(!isset($_COOKIE['mw_backup_total_files'])){
			setcookie('mw_backup_total_files',$total);
		} else {
			$total = $_COOKIE['mw_backup_total_files'];
		}
		
	} else {
	  setcookie('mw_backup_total_files',false);

	}
 $perc =   100 - mw()->format->percent($remaining, $total);
 
    ?>


             <div class="mw-ui-progress" id="resore-progress">
                  <div class="mw-ui-progress-bar" style="width: <?php print  $perc; ?>%;min-width:100px;"></div>
                  <div class="mw-ui-progress-info"><?php _e("Backup progress"); ?></div>
                  <span class="mw-ui-progress-percent"><?php print  $perc; ?>%</span>
              </div>



 
		
		<script type="text/javascript">

            $(document).ready(function () {

                mw.reload_module('admin/backup/manage');

            });

        </script>
		
		
		<?php 
		
		
    }
}
