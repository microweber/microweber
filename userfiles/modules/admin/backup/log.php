<div><iframe src="<?php print api_url('mw_cron') ?>?rand=<?php print rand() ?>" class="xhidden" style="visibility: hidden; display:inline" width="1" height="1"></iframe> </div>
 



<?php
 
       if (!defined('USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("USER_IP", '127.0.0.1');

    }
}
 // mw_cron();
	$check = mw('log')->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&user_ip=" . USER_IP);
	if(isset($check['value'])){
		  //mw_notif("Uploaded: ".file_size_nice($check['value']));
	}




	$check = mw('log')->get("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);
	if(isset($check['value'])){
		if($check['value'] == 'reload'){
			?>
<script type="text/javascript">

		$(document).ready(function(){

				mw.reload_module('admin/backup/manage');

		});

</script>
      <?php
		} else {    // mw_notif(html_entity_decode($check['value'])) ;
//delete_log("id=" . $check['id']);
        if($check['value'] != ''){



        ?>

<?php print html_entity_decode($check['value']) ?>

            <script>
               // mw.notification.success("<?php print html_entity_decode($check['value']) ?>");
            </script>


     <?php
		}}
	}

 ?>