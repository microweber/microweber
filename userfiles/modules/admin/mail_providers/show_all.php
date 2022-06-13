<?php must_have_access(); ?>

<div class="row">
    <div class="col-12 mb-2">

        <script>
            $(document).ready(function () {

                $('.js-map-contact-form-fields').click(function () {
                    mw.notification.warning('Loading...');

                    var dialog = mw.dialog({
                        title: 'Map Contact Form Fields With Mail Providers'
                    });
                    dialog.center();

                    mw.load_module("admin/mail_providers/map_fields", dialog.dialogContainer);
                });

                $('.mail-provider-sync').click(function () {
                    mw.notification.warning('Loading...');

                    var dialog = mw.dialog({
                        title: 'Mailerlite Sync'
                    });

                    mw.load_module("admin/mail_providers/sync", dialog.dialogContainer);

                });
            });
        </script>

        <div class="alert alert-dismissible alert-primary">
             <i class="mdi mdi-information"></i> <?php _e("We recommend to manually map fields of your contact forms, in order to get full integration with mail providers."); ?>
            <button type="button" class="btn btn-primary js-map-contact-form-fields"><i class="mdi mdi-sitemap"></i> <?php _e("Map Fields"); ?></button>
        </div>

        <button type="button" class="btn btn-primary mail-provider-sync pull-right"><i class="mdi mdi-cloud-sync"></i> <?php _e("Sync Subscribers"); ?></button>

    </div>
</div>

<?php
$mail_providers = get_modules('type=mail_provider');
?>

<?php if (!empty($mail_providers)): ?>
    <?php foreach ($mail_providers as $key => $provider): ?>
        <div class="card style-1 mb-3 card-collapse">
            <div class="card-header no-border" data-bs-toggle="collapse" data-bs-target="#mail_provider-<?php echo $key; ?>">
                <h6 class="font-weight-bold"><?php print $provider['name'] ?></h6>
            </div>
            <div class="card-body collapse" id="mail_provider-<?php echo $key; ?>">
                <module type="<?php print $provider['module'] ?>" view="admin"/>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
