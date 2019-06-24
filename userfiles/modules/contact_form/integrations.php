<?php
only_admin_access();
include 'mail_providers.php';
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>
<?php
$mod_id = $params['id'];
if(isset($params['for_module_id'])){
    $mod_id = $params['for_module_id'];
}
?>

<div class="mw-accordion">

	<?php foreach(get_mail_providers() as $mailProvider): ?>
	<div class="mw-accordion-item">
		<div class="mw-ui-box-header mw-accordion-title">
			<div class="header-holder">
				<i class="mai-setting2"></i> <?php echo $mailProvider['title']; ?>
			</div>
		</div>
		<div class="mw-accordion-content mw-ui-box mw-ui-box-content">
		
			<?php foreach ($mailProvider['fields'] as $field): ?>
			<div class="demobox">
                <label class="mw-ui-label"><?php echo $field['title']; ?></label>
                <input type="text" option-group="<?php print $mod_id ?>" value="<?php print get_option('mail_field_' . $field['name'], $mod_id); ?>" name="<?php echo $field['name']; ?>" class="mw-ui-field w100 mw_option_field">
            </div>
            <br />
            <?php endforeach; ?>
		
		</div>
	</div>
	<?php endforeach; ?>
	
</div>