<?php must_have_access(); ?>
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

    .templates-screenshots {
        padding-left: 10px;
    }

    .templates-screenshots a {
        border: 3px solid #2b2b2b;
        display: block;
        float: left;
        margin: 10px 10px 10px 0;
    }

    .choose-title{
        font-size:24px;
        color: #2b2b2b;
        margin: 10px;
        font: 400 24px Arial;
    }

    .templates-screenshots a.active {
        border: 3px solid #0086db;
    }

    .templates-screenshots img {
        max-height: 250px;
    }
</style>

<?php if ($template['text'] != '' AND isset($template['text'])): ?>
    <div id="newsletter-template" style="height:0px; overflow:hidden;">
        <?php print $template['text']; ?>
    </div>
<?php else: ?>
    <?php if (isset($_GET['template'])): ?>
        <div id="newsletter-template" style="height:0px; overflow:hidden;">
            <?php if (isset($_GET['template']) AND $_GET['template'] == 'default'): ?>
                <?php include('predefined_templates/default.php'); ?>
            <?php elseif (isset($_GET['template']) AND $_GET['template'] == 1): ?>
                <?php include('predefined_templates/1.php'); ?>
            <?php elseif (isset($_GET['template']) AND $_GET['template'] == 2): ?>
                <?php include('predefined_templates/2.php'); ?>
            <?php else: ?>
                <?php include('predefined_templates/default.php'); ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <h3 class="choose-title">Choose template to start</h3>
        <div class="templates-screenshots">
            <a href="<?php print url_current(); ?>&template=default" class="<?php if (!isset($_GET['template']) OR $_GET['template'] == 'default'): ?>active<?php endif; ?>">
                <img src="<?php print modules_url(); ?>newsletter/predefined_templates/template_default.jpg" alt=""/>
            </a>
            <a href="<?php print url_current(); ?>&template=1" class="<?php if (isset($_GET['template']) AND $_GET['template'] == 1): ?>active<?php endif; ?>">
                <img src="<?php print modules_url(); ?>newsletter/predefined_templates/template_1.jpg" alt=""/>
            </a>
            <a href="<?php print url_current(); ?>&template=2" class="<?php if (isset($_GET['template']) AND $_GET['template'] == 2): ?>active<?php endif; ?>">
                <img src="<?php print modules_url(); ?>newsletter/predefined_templates/template_2.jpg" alt=""/>
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>


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
