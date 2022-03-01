<?php must_have_access(); ?>

<script>
    function mw_change_template() {

        var selectedTemplate = $('.mw-site-theme-selector').find("[name='current_template']").first().val();
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

                if (json.data['done']) {
                    mw.notification.success('Template has been changed.');
                    changeTemplateDialog.remove();
                }
                console.log(json.data['done']);
            }
        });
    }
</script>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup/css/style.css?v=' .time(); ?>" type="text/css"/>

<div class="mw-backup-restore">

    <div class="mw-backup-restore-options">

        <h2 style="font-weight: bold">How do you like to apply this template?</h2>
        <br/>

        <label class="mw-ui-check mw-backup-restore-option">
            <div class="option-radio">
                <input type="radio" name="import_type" value="default" checked="checked" />
                <span></span>
            </div>
            <h3>Use template with current content</h3>
            <p>Change the website template without any content changes</p>
        </label>

        <label class="mw-ui-check mw-backup-restore-option active">
            <div class="option-radio">
                <input type="radio" name="import_type" value="full" />
                <span></span>
            </div>
            <h3>Import default content, media and css files</h3>
            <p>Import the default content, media and css files from template</p>
        </label>


        <label class="mw-ui-check mw-backup-restore-option">
            <div class="option-radio">
                <input type="radio" name="import_type" value="only_media" />
                <span></span>
            </div>
            <h3>Import only media and css</h3>
            <p>This option will import only the media and css files</p>
        </label>

    </div>

    <div style="margin-bottom:20px;" class="js-backup-restore-installation-language-wrapper"></div>
    <div class="backup-restore-modal-log-progress"></div>

    <div class="mw-backup-restore-buttons">
        <a class="btn btn-link button-cancel">Close</a>
        <button class="btn btn-primary btn-rounded button-start js-button-change-template" onclick="mw_change_template()" type="submit">
            Change Template
        </button>
    </div>

</div>
