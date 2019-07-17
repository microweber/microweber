<?php only_admin_access(); ?>
<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="mw-ui mw-ui-box-content">
<div class="mw-ui-box mw-ui-box-content">
<h2><?php _e("Mail Templates"); ?></h2>
<br />
<div class="mw-ui-row">
    <div class="mw-ui-col">
        <div class="mw-ui-col-container">
			
		
			<module type="admin/mail_templates/list" id="list-mail-templates" />
			

		</div> 
	</div>
</div>
</div>
</div>