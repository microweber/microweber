<?php only_admin_access(); ?>
<script type="text/javascript" src="<?php print(site_url()); ?>apijs_settings?id=<?php print CONTENT_ID; ?>"></script>

<script src="<?php print site_url('apijs') ?>" type="text/javascript"></script>
<?php
$template_id = $params['data-template_id'];
$template = newsletter_get_template(array("id" => $template_id));
?>

<link rel="stylesheet" href="<?php print modules_url(); ?>newsletter/css/grapes.min.css">
<link rel="stylesheet" href="<?php print modules_url(); ?>newsletter/css/grapesjs-preset-newsletter.css">

<script src="<?php print modules_url(); ?>newsletter/js/grapes.min.js"></script>
<script src="<?php print modules_url(); ?>newsletter/js/grapesjs-preset-newsletter.min.js"></script>

<style type="text/css">
    body,
    html {
        height: 100%;
        margin: 0;
    }

    .mw_modal_container {
        padding: 0px !important;
    }
</style>


<div id="newsletter-template" style="height:0px; overflow:hidden;">
    <?php if ($template['text'] != '' AND isset($template['text'])): ?>
        <?php print $template['text']; ?>
    <?php else: ?>
        <?php include('predefined_templates/1.php'); ?>
    <?php endif; ?>
</div>


<script>
    //Outside from Document ready
    var websiteURL = '';
    if ($('#newsletter-template').length > 0) {
        var editor = grapesjs.init({
            container: '#newsletter-template',
            fromElement: true,
            plugins: ['gjs-preset-newsletter'],
            pluginsOpts: {
                'gjs-preset-newsletter': {
                    //modalTitleImport: 'Import template',
                    // ... other options
                }
            },
            storageManager: {type: 'none'},
            assetManager: {
                // Url where uploads will be send, set false to disable upload
                upload: '' + websiteURL + '/administrator/functions.php?func=upload_image',
                // Text on upload input
                uploadText: 'Drop files here or click to upload',
                // Label for the add button
                addBtnText: 'Add image',
                // Custom uploadFile function
                // @example
                uploadFile: function (e) {
                    var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                    // ...send somewhere
                    console.log(files);

                    var formData = new FormData();

                    for (var i in files) {
                        formData.append('file-' + i, files[i])
                    }

                    $.ajax({
                        url: '' + websiteURL + '/administrator/functions.php?func=upload_image',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        crossDomain: true,
                        dataType: "json",
                        mimeType: "multipart/form-data",
                        processData: false,
                        success: function (result) {
                            var images = result.data; // <- should be an array of uploaded images
                            console.log(images);
                            editor.AssetManager.add(images);
                        }
                    });
                }
            }
        });

        editor.on('change', function () {
            var html = editor.runCommand('gjs-get-inlined-html');
            window.parent.$('.js-edit-template-text').val(html);
            setTimeout(function () {
                window.parent.$('.js-edit-template-text').trigger('sourceChanged', [html]);
            }, 30);
        });
    }
</script>