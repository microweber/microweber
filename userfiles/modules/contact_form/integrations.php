<?php 
include 'mail_providers.php';
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
                <input type="text" name="<?php echo $field['name']; ?>" class="mw-ui-field" style="width:100%;">
            </div>
            <br />
            <?php endforeach; ?>
		
		</div>
	</div>
	<?php endforeach; ?>
	
</div>