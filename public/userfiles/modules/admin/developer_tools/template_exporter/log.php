<?php
if (!defined('USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("USER_IP", '127.0.0.1');

    }
}
$check = mw()->log_manager->get("order_by=created_at desc&one=true&no_cache=true&is_system=y&created_at=[mt]30 min ago&field=upload_size&rel_type=uploader&user_ip=" . USER_IP);
$job = mw('admin\developer_tools\template_exporter\Worker')->cronjob();
$check = mw()->log_manager->get("order_by=created_at desc&one=true&no_cache=true&is_system=y&created_at=[mt]30 min ago&field=action&rel_type=export&user_ip=" . USER_IP);

 

if (isset($check['value'])) {
    if ($check['value'] == 'reload' or $check['value'] == 'done') {
		
mw()->log_manager->delete("is_system=y&rel_type=export&user_ip=" . USER_IP);

		
		
        ?>
        <script type="text/javascript">

            $(document).ready(function () {

                mw.reload_module('admin/developer_tools/template_exporter/manage');

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
		if(!isset($_COOKIE['mw_export_total_files'])){
			setcookie('mw_export_total_files',$total);
		} else {
			$total = $_COOKIE['mw_export_total_files'];
		}
		
	} else {
	  setcookie('mw_export_total_files',false);

	}
 $perc =   100 - mw()->format->percent($remaining, $total);
 
    ?>


             <div class="mw-ui-progress" id="resore-progress">
                  <div class="mw-ui-progress-bar" style="width: <?php print  $perc; ?>%;min-width:100px;"></div>
                  <div class="mw-ui-progress-info"><?php _e("export progress"); ?></div>
                  <span class="mw-ui-progress-percent"><?php print  $perc; ?>%</span>
              </div>



 
		
		<script type="text/javascript">

            $(document).ready(function () {

                mw.reload_module('admin/developer_tools/template_exporter/manage');

            });

        </script>
		
		
		<?php 
		
		
    }
}
