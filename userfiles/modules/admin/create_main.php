
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/ui.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/components.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/install.css"/>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" id="bootstrap">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">


<div class="installholder">
    <div class="mw-ui-box">
        <div class="mw-ui-box-content">
            <form action="<?php echo admin_url('mw_install_create_user') ?>" method="post" class="mw_install_create_user">

                <div style="text-align: center"><img width="150px" src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo" align="center"></div>

                <br />
                <br />
                <h2>
                    <?php _e('Create Admin Account'); ?>
                </h2>
                <div class="hr"></div>

                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Username'); ?></div>
                    <input name="admin_username" type="text" required class="form-control  w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Email'); ?></div>
                    <input name="admin_email" type="text" required class="form-control  w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <div class="mw-ui-label"><?php _e('Password'); ?></div>
                    <input name="admin_password" type="password" required  class="form-control  w100"/>
                </div>
                <div class="mw-ui-field-holder">
                    <input type="submit" value="Create Account" class="btn btn-success"/>
                </div>
            </form>

        </div>
    </div>
</div>
