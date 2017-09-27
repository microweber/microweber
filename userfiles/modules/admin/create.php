<?php include(__DIR__ . DS . 'header.php'); ?>
<style>
    .mw_install_create_user {
        margin: auto;
        width: 40%;
        border: 1px solid #666666;
        padding: 10px;
        margin-top: 30px;
    }
</style>

<form action="<?php echo admin_url('mw_install_create_user') ?>" method="post"
      class="mw_install_create_user">

    <div style="text-align: center"><img
            src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo"
            align="center"></div>
    <h2 style="text-align: center"><?php _e('Create Admin Account'); ?></h2>

    <div class="mw-ui-field-holder">
        <div class="label"><?php _e('Username'); ?></div>
        <input name="admin_username" type="text" required
               class="mw-ui-field w100"/>
    </div>
    <div class="mw-ui-field-holder">
        <div class="label"><?php _e('Email'); ?></div>
        <input name="admin_email" type="text" required
               class="mw-ui-field w100"/>
    </div>
    <div class="mw-ui-field-holder">
        <div class="label"><?php _e('Password'); ?></div>
        <input name="admin_password" type="password" required
               class="mw-ui-field w100"/>
    </div>
    <div class="mw-ui-field-holder">
        <input type="submit" value="Create Account"
               class="mw-ui-btn mw-ui-btn-notification pull-right"/>
    </div>
</form>
<?php include(__DIR__ . DS . 'footer.php'); ?>
