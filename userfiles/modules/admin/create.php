<?php include(__DIR__ . DS . 'header.php'); ?>

<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/ui.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/components.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/install.css"/>
<div class="installholder">
    <div class="mw-ui-box">
        <div class="mw-ui-box-content">
            <form action="<?php echo admin_url('mw_install_create_user') ?>" method="post" class="mw_install_create_user">

                <div style="text-align: center"><img width="100%" src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo" align="center"></div>

                <h2><?php _e('Create Admin Account'); ?></h2>
                <div class="hr"></div>

                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Username'); ?></div>
                    <input name="admin_username" type="text" required class="mw-ui-field w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Email'); ?></div>
                    <input name="admin_email" type="text" required class="mw-ui-field w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Password'); ?></div>
                    <input name="admin_password" type="password" required  class="mw-ui-field w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <input type="submit" value="Create Account" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-info pull-right"/>
                </div>
            </form>

        </div>
    </div>
</div>
<?php include(__DIR__ . DS . 'footer.php'); ?>
