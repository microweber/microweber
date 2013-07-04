<?php only_admin_access(); ?>
<img class="xhidden" src="<?php print api_url('mw_cron') ?>?rand=<?php print rand() ?>" />
<?php	 
 // mw_cron();
	$check = get_log("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&user_ip=" . USER_IP);
	if(isset($check['value'])){
		  //mw_notif("Uploaded: ".file_size_nice($check['value']));
	}




	$check = get_log("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);
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