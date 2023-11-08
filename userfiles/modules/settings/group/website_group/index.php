<?php must_have_access();?>

<script>
    $(document).ready(function () {
        $('body .main > main').addClass('page-settings');
    });
</script>

<?php
$show_inner = false;

if (isset($_GET['group']) and $_GET['group']) {
    $group = $_GET['group'];

    if ($group == 'general') {
        $show_inner = 'settings/group/website';
    } elseif ($group == 'updates') {
        $show_inner = 'updates';
    } elseif ($group == 'email') {
        $show_inner = 'settings/group/email';
    } elseif ($group == 'seo') {
        $show_inner = 'settings/group/seo';
    } elseif ($group == 'users') {
        $show_inner = 'settings/group/users';
    } elseif ($group == 'template') {
        $show_inner = 'settings/group/template';
    } elseif ($group == 'advanced') {
        $show_inner = 'settings/group/advanced';
    } elseif ($group == 'files') {
        $show_inner = 'files/admin';
    } elseif ($group == 'login') {
        $show_inner = 'settings/group/users';
    } elseif ($group == 'language') {
        $show_inner = 'settings/group/language';
    } elseif ($group == 'privacy') {
        $show_inner = 'settings/group/privacy';
    }else{
        $show_inner = false;
        $show_inner = $group;
    }
}
?>

<?php if ($show_inner): ?>

       <module type="admin/modules/info" back_button_url="settings" />

        <module type="<?php print $show_inner ?>"/>
    <?php return; ?>
<?php endif ?>

<h1 class="main-pages-title"><?php _e('Website Settings'); ?></h1>


<div class="card mb-4">
    <div class="row card-body">

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=general" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-cog-outline fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('General'); ?></div>
                    <small class="text-muted"><?php _e('Make basic settings for your website'); ?></small>
                </div>
            </a>
        </div>


        <?php if (mw()->ui->disable_marketplace != true): ?>
        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=updates" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-flash-outline fs-1"></i>
                    <span class="mw-update-required"></span>

                </div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Updates'); ?></div>
                    <small class="text-muted"><?php _e('Check for the latest updates'); ?></small>
                </div>
            </a>
        </div>
        <?php endif; ?>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=email" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-email-outline fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('E-mail'); ?></div>
                    <small class="text-muted"><?php _e('Email settings'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=template" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-text-box-check-outline fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Template'); ?></div>
                    <small class="text-muted"><?php _e('Change or manage the theme you use'); ?></small>
                </div>
            </a>
        </div>


        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=seo" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-search-web fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('SEO'); ?></div>
                    <small class="text-muted"><?php _e('SEO settings'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=advanced" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-keyboard-outline fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Advanced'); ?></div>
                    <small class="text-muted"><?php _e('Additional settings'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=files" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-file-cabinet fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Files'); ?></div>
                    <small class="text-muted"><?php _e('File management'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=users" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-login fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Login & Register'); ?></div>
                    <small class="text-muted"><?php _e('Manage the access control to your website'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=language" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-translate fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Language'); ?></div>
                    <small class="text-muted"><?php _e('Choice of language and translations'); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
            <a href="<?php echo admin_url();?>settings?group=privacy" class=" d-flex js-website-settings-link settings-holder-wrapper">
                <div class="icon-holder"><i class="mdi mdi-shield-edit-outline fs-1"></i></div>
                <div class="info-holder card-title">
                    <div class="settings-info-holder-title"><?php _e('Privacy Policy'); ?></div>
                    <small class="text-muted"><?php _e('Privacy Policy and GDPR settings'); ?></small>
                </div>
            </a>
        </div>
    </div>
</div>
