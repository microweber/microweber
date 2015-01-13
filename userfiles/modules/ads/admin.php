<?php
$providers = [
	3 => 'DoubleClick',
	5 => 'AdFly'
	];
?>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/admin.css" />
<div class="mw-module-admin-wrap">
<?php if(isset($params['live_edit'])): ?>
	<h2>Advertisement</h2>
	<label class="mw-ui-label"><?php _e("Instance Name"); ?></label>
    <input name="name" class="mw_option_field mw-ui-field" type="text"
	  	value="<?php echo get_option('name', $params['id']) ?>">

	<label class="mw-ui-label"><?php _e("Provider"); ?></label>
	<select name="provider" class="mw_option_field mw-ui-field">
		<?php foreach($providers as $providerId => $providerName): ?>
		<option value="<?php echo $providerId; ?>"
			<?php echo ($providerId == get_option('provider', $params['id'])) ? 'selected' : ''; ?>>
			<?php echo $providerName; ?>
		</option>
		<?php endforeach; ?>
	</select>

	<label class="mw-ui-label"><?php _e("Client ID"); ?></label>
    <input name="client_id" class="mw_option_field mw-ui-field" type="text"
	  	value="<?php echo get_option('client_id', $params['id']) ?>">
<?php else: ?>
	<?php
	$server = 'http://ads.dev';
	var_dump(file_get_contents($server));
	?>
<?php endif; ?>
</div>