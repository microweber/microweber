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
    }
}
?>

<?php if ($show_inner): ?>
    <module type="<?php print $show_inner ?>"/>
    <?php return; ?>
<?php endif ?>


<div class="main-toolbar">
    <a href="#" class="btn btn-link text-silver"><i class="mdi mdi-chevron-left"></i> Websites</a>
</div>

<div class="card bg-none style-1 mb-0">
    <div class="card-header">
        <h5><i class="mdi mdi-cog text-primary mr-3"></i> <strong>Settings</strong></h5>
        <div>

        </div>
    </div>

    <div class="card-body pt-3">
        <h2 class="text-center mt-3 mb-4">Website settings</h2>
        
        <div class="card style-1 mb-3">
            <div class="card-body pt-3 px-5">
                <div class="row select-settings">
                    <div class="col-4">
                        <a href="?group=general" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-cog-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">General</span><br/>
                                <small class="text-muted">Make basic settings for your website</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=updates" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-flash-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Updates</span><br/>
                                <small class="text-muted">Check for the latest updates</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=email" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-email-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">E-mail</span><br/>
                                <small class="text-muted">Email settings</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=template" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-text-box-check-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Template</span><br/>
                                <small class="text-muted">Change or manage the theme you use</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=advanced" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-keyboard-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Advanced</span><br/>
                                <small class="text-muted">Additional settings</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=files" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-file-cabinet mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Files</span><br/>
                                <small class="text-muted">File management</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=login" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-login mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Login & Register</span><br/>
                                <small class="text-muted">Manage the access control to your website</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=language" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-translate mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Language</span><br/>
                                <small class="text-muted">Choice of language and translations</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-4">
                        <a href="?group=privacy" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-shield-edit-outline mdi-20px"></i></div>
                            <div class="info-holder">
                                <span class="text-primary font-weight-bold">Privacy Policy</span><br/>
                                <small class="text-muted">Privacy Policy and GDPR settings</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>