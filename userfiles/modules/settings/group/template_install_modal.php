<?php must_have_access(); ?>

<script>
    function mw_change_template() {

        var selectedTemplate = $('.js-template-selector').find("[name='active_site_template']").val();
        var importType = $('input[name="import_type"]:checked').val();

        $('.js-button-change-template').attr('disabled','disabled');
        $('.js-button-change-template').html('Loading..');
 
        setTimeout(function () {
            $('.js-button-change-template').html('This can take some time..');
        }, 5000);

        setTimeout(function () {
            $('.js-button-change-template').html('Importing template files..');
        }, 10000);

        $.ajax({
            url: mw.settings.site_url + 'api/template/change?template=' + selectedTemplate + "&import_type=" + importType,
            type: "GET",
            success: function (json) {

                $('.js-button-change-template').html('Change Template');
                $('.js-button-change-template').removeAttr('disabled');
                $('.mw-backup-restore-options').css({
                    
                    opacity: 0.5;
                    pointerEvents: 'none'

                });

                if (json.data['done']) {
                    mw.notification.success('Template has been changed.');
                    changeTemplateDialog.remove();
                }
                 
            }
        });
    }
</script>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup/css/style.css?v=' .time(); ?>" type="text/css"/>

<div class="mw-backup-restore px-md-3 px-0">

    <div class="mw-backup-restore-options col-lg-11 col-12 mt-4">

        <h2 style="font-weight: bold"><?php _e("How to apply this template?") ?></h2>
        <br/>

        <div class="card bg-light mb-4 text-start">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-default">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="default" checked="checked" />

                    <span class="form-label font-weight-bold"><?php _e("Use template with current content") ?></span>
                    <small class="text-muted"><?php _e("Change only website template without any content changes") ?></small>
                </label>
            </div>
        </div>

        <div class="card bg-light mb-4 text-start">
           <div class="card-body">
               <label class="form-check py-2 active" id="js-template-import-type-full">
                   <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="full" />

                   <span class="form-label font-weight-bold"><?php _e("Import default content, media and css files") ?></span>
                   <small class="text-muted"><?php _e("Import the default content, media and css files from template") ?></small>
               </label>
           </div>
        </div>


        <div class="card bg-light mb-4 text-start">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-only-media">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="only_media" />

                    <span class="form-label font-weight-bold"><?php _e("Import only media and css") ?></span>
                    <small class="text-muted"><?php _e("This option will import only the media and css files") ?></small>
                </label>
            </div>
        </div>

        <div class="card bg-light mb-4 text-start">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-delete">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="delete" />

                    <span class="form-label font-weight-bold"><?php _e("Delete all website data") ?></span>
                    <small class="text-muted"><?php _e("This option will delete all website data and will import fresh content") ?>.</small>
                </label>
            </div>
        </div>

    </div>

    <div style="margin-bottom:20px;" class="js-backup-restore-installation-language-wrapper"></div>
    <div class="backup-restore-modal-log-progress"></div>

    <div class="mw-backup-restore-buttons">
        <button class="btn btn-primary js-button-change-template me-2" onclick="mw_change_template()" type="submit">
            <?php _e("Change Template") ?>
        </button>
        <a class="btn btn-link" style="font-weight: normal;" onclick="mw.dialog.get().remove()"><?php _e("Cancel") ?></a>
    </div>

</div>
