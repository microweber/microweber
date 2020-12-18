<?php only_admin_access(); ?>

<script type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>manager/forms_data_manager.js');
</script>

<?php
$last_messages_count = mw()->forms_manager->get_entires('count=true');
?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-mail"></i> <?php _e("Recent Messages") ?></span>
        <a href="<?php print admin_url('view:modules/load_module:contact_form'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print $last_messages_count; ?></strong> <?php print _e('Go to Messages'); ?></a>
    </div>
    <div class="dr-list">
    
		<?php
		$last_messages = mw()->forms_manager->get_entires('limit=5');
		
		$view = new \Microweber\View(__DIR__ . DIRECTORY_SEPARATOR . 'admin_messages_list.php');
		$view->assign('last_messages', $last_messages);
		echo $view->__toString();
		?>
		
    </div>
</div>
