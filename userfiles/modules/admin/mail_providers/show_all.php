<?php must_have_access(); ?>

<div class="row px-0">
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

        <div class="alert alert-dismissible alert-primary d-flex justify-content-between align-items-center">
             <i class="mdi mdi-information"></i> <?php _e("We recommend to manually map fields of your contact forms, in order to get full integration with mail providers."); ?>
            <button type="button" class="btn btn-primary js-map-contact-form-fields"><?php _e("Map Fields"); ?></button>
        </div>

        <button type="button" class="btn btn-link mail-provider-sync text-end mt-3"> <?php _e("Sync Subscribers"); ?></button>

    </div>
</div>

<?php
$mail_providers = get_modules('type=mail_provider');
?>

<?php if (!empty($mail_providers)): ?>
    <?php foreach ($mail_providers as $key => $provider): ?>
        <div class="card p-3 shadow-sm hover-bg-light mb-4 card-collapse">
            <div class="card-header p-1 no-border" data-bs-toggle="collapse" data-bs-target="#mail_provider-<?php echo $key; ?>">
                <label class="form-label mb-0"><?php print $provider['name'] ?></label>
            </div>
            <div class="card-body collapse" id="mail_provider-<?php echo $key; ?>">
                <module type="<?php print $provider['module'] ?>" view="admin"/>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
