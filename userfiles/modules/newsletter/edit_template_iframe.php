<?php only_admin_access(); ?>
<script type="text/javascript" src="<?php print(site_url()); ?>apijs_settings?id=<?php print CONTENT_ID; ?>"></script>

<script src="<?php print site_url('apijs') ?>" type="text/javascript"></script>
<?php
$template_id = $params['data-template_id'];
$template = newsletter_get_template(array("id" => $template_id));
?>

<link rel="stylesheet" href="<?php print $config['url_to_module']; ?>css/grapes.min.css">
<link rel="stylesheet" href="<?php print $config['url_to_module']; ?>css/grapesjs-preset-newsletter.css">

<script src="<?php print $config['url_to_module']; ?>js/grapes.min.js"></script>
<script src="<?php print $config['url_to_module']; ?>js/grapesjs-preset-newsletter.min.js"></script>

<style type="text/css">
    body,
    html {
        height: 100%;
        margin: 0;
    }

    .gjs-pn-panel {
        height: 42px;
    }

    .mw_modal_container {
        padding: 0px !important;
    }
</style>



<div id="newsletter-template" style="height:0px; overflow:hidden;">
    <?php if ($template['text'] != '' AND isset($template['text'])): ?>
        <?php print $template['text']; ?>
    <?php else: ?>
        <table width="100%" height="100%" bgcolor="rgb(234, 236, 237)" class="main-body c340" style="box-sizing: border-box; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; height: 100%; background-color: rgb(234, 236, 237);">
            <tbody id="ibxi" style="box-sizing: border-box;">
            <tr valign="top" class="row" style="box-sizing: border-box; vertical-align: top;">
                <td class="main-body-cell" style="box-sizing: border-box;">
                    <table width="90%" height="0" class="container"
                           style="box-sizing: border-box; font-family: Helvetica, serif; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-top: auto; margin-right: auto; margin-bottom: auto; margin-left: auto; height: 0px; width: 90%; max-width: 550px;">
                        <tbody id="iwih" style="box-sizing: border-box;">
                        <tr id="i31q" style="box-sizing: border-box;">
                            <td valign="top" class="container-cell" style="box-sizing: border-box; vertical-align: top; font-size: medium; padding-bottom: 50px;">
                                <table width="100%" height="0" class="table100 c1790"
                                       style="box-sizing: border-box; width: 100%; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 0px; min-height: 30px; border-collapse: separate; margin-top: 0px; margin-right: 0px; margin-bottom: 10px; margin-left: 0px;">
                                    <tbody id="iik9v" style="box-sizing: border-box;">
                                    <tr id="iope5" style="box-sizing: border-box;">
                                        <td id="c1793" align="right" class="top-cell" style="box-sizing: border-box; text-align: right; color: rgb(152, 156, 165);">
                                            <u id="c307" class="browser-link" style="box-sizing: border-box; font-size: 12px;"><a href="http://www.yovchevski.plumtex.store" target="_blank">View in browser</a>
                                            </u>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="c1766"
                                       style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: 0px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; min-height: 30px; text-align: center;" align="center">
                                    <tbody id="ig7wq" style="box-sizing: border-box;">
                                    <tr id="ipmpj" style="box-sizing: border-box;">
                                        <td width="33%" valign="middle" class="cell c1776" style="box-sizing: border-box; width: 33%; vertical-align: middle; text-align: center;" align="center">
                                        </td>
                                        <td width="33%" valign="middle" class="cell c1776" style="box-sizing: border-box; width: 33%; vertical-align: middle; text-align: center;" align="center">
                                            <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/logo.png" class="c5641" style="box-sizing: border-box; color: black; vertical-align: middle; text-align: center; width: 100%;">
                                        </td>
                                        <td width="33%" valign="middle" class="cell c1776" style="box-sizing: border-box; width: 33%; vertical-align: middle; text-align: center;" align="center">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="c1766"
                                       style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: 0px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; min-height: 30px; text-align: center;" align="center">
                                    <tbody id="ikd519" style="box-sizing: border-box;">
                                    <tr id="isafuc" style="box-sizing: border-box;">
                                        <td width="33%" valign="middle" class="cell c1776" style="box-sizing: border-box; width: 33%; vertical-align: middle; text-align: center;" align="center">
                                            <div class="c1144" style="box-sizing: border-box; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; font-size: 25px; font-weight: 300; text-align: center;">PlumTex Store Newsletter
                                                <br class="c2979" style="box-sizing: border-box;">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table height="0" class="card" style="box-sizing: border-box; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-bottom: 20px; height: 0px;">
                                    <tbody id="i952j" style="box-sizing: border-box;">
                                    <tr id="iifgw" style="box-sizing: border-box;">
                                        <td bgcolor="rgb(255, 255, 255)" align="center" class="card-cell" id="il4aw"
                                            style="box-sizing: border-box; background-color: rgb(255, 255, 255); overflow-x: hidden; overflow-y: hidden; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; text-align: center;">
                                            <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/tmp-header-txt.jpg" alt="Big image here" class="c1271"
                                                 style="box-sizing: border-box; width: 100%; margin-top: 0px; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; font-size: 50px; color: rgb(120, 197, 214); line-height: 250px; text-align: center;">
                                            <table width="100%" height="0" class="table100 c1357" id="izylt"
                                                   style="box-sizing: border-box; width: 100%; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; border-collapse: collapse;">
                                                <tbody id="igcm5" style="box-sizing: border-box;">
                                                <tr id="irklo" style="box-sizing: border-box;">
                                                    <td valign="top" class="card-content" id="iqsfn" style="box-sizing: border-box; font-size: 13px; line-height: 20px; color: rgb(111, 119, 125); padding-top: 10px; padding-right: 20px; padding-bottom: 0px; padding-left: 20px; vertical-align: top;">
                                                        <h1 class="card-title" id="i5n3m" style="box-sizing: border-box; font-size: 25px; font-weight: 300; color: rgb(68, 68, 68);">Build your newsletters faster than ever
                                                            <br id="i6nkt" style="box-sizing: border-box;">
                                                        </h1>
                                                        <p class="card-text" style="box-sizing: border-box;">Build and test responsive newsletter templates faster than ever using the GrapesJS Newsletter Builder.
                                                        </p>
                                                        <table width="100%" class="c1542" id="if13o"
                                                               style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%;">
                                                            <tbody id="il2pa" style="box-sizing: border-box;">
                                                            <tr id="iwtmt" style="box-sizing: border-box;">
                                                                <td id="c1545" align="center" class="card-footer" style="box-sizing: border-box; padding-top: 20px; padding-right: 0px; padding-bottom: 20px; padding-left: 0px; text-align: center;">
                                                                    <a href="http://www.yovchevski.plumtex.store" target="_blank" class="button"
                                                                       style="box-sizing: border-box; font-size: 12px; padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px; background-color: rgb(217, 131, 166); color: rgb(255, 255, 255); text-align: center; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; font-weight: 300;">Visit
                                                                        Website</a>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="list-item" id="itid7" style="box-sizing: border-box; height: auto; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px;">
                                    <tbody id="iza6g" style="box-sizing: border-box;">
                                    <tr id="ij5hv" style="box-sizing: border-box;">
                                        <td bgcolor="rgb(255, 255, 255)" class="list-item-cell" id="i1edf"
                                            style="box-sizing: border-box; background-color: rgb(255, 255, 255); border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; overflow-x: hidden; overflow-y: hidden; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                            <table width="100%" height="150" class="list-item-content" id="iodqa"
                                                   style="box-sizing: border-box; border-collapse: collapse; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 150px; width: 100%;">
                                                <tbody id="impb8" style="box-sizing: border-box;">
                                                <tr class="list-item-row" id="ikxtm" style="box-sizing: border-box;">
                                                    <td width="30%" class="list-cell-left" id="ipk4b" style="box-sizing: border-box; width: 30%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                                        <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/tmp-blocks.jpg" alt="Image1" class="list-item-image" id="ibo5k" style="box-sizing: border-box; color: rgb(217, 131, 166); font-size: 45px; width: 100%;">
                                                    </td>
                                                    <td width="70%" class="list-cell-right" id="id3po" style="box-sizing: border-box; width: 70%; color: rgb(111, 119, 125); font-size: 13px; line-height: 20px; padding-top: 10px; padding-right: 20px; padding-bottom: 0px; padding-left: 20px;">
                                                        <h1 class="card-title" id="itrbh" style="box-sizing: border-box; font-size: 25px; font-weight: 300; color: rgb(68, 68, 68);">Built-in Blocks
                                                        </h1>
                                                        <p class="card-text" id="ilxlm" style="box-sizing: border-box;">Drag and drop built-in blocks from the right panel and style them in a matter of seconds
                                                        </p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="list-item" id="iahts" style="box-sizing: border-box; height: auto; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px;">
                                    <tbody id="imyet" style="box-sizing: border-box;">
                                    <tr id="i4ahh" style="box-sizing: border-box;">
                                        <td bgcolor="rgb(255, 255, 255)" class="list-item-cell" id="iovx8"
                                            style="box-sizing: border-box; background-color: rgb(255, 255, 255); border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; overflow-x: hidden; overflow-y: hidden; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                            <table width="100%" height="150" class="list-item-content" id="igkq4"
                                                   style="box-sizing: border-box; border-collapse: collapse; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 150px; width: 100%;">
                                                <tbody id="i0e2g" style="box-sizing: border-box;">
                                                <tr class="list-item-row" id="i8q2k" style="box-sizing: border-box;">
                                                    <td width="30%" class="list-cell-left" id="ilmir" style="box-sizing: border-box; width: 30%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                                        <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/tmp-tgl-images.jpg" alt="Image2" class="list-item-image" id="iain2"
                                                             style="box-sizing: border-box; color: rgb(217, 131, 166); font-size: 45px; width: 100%;">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="grid-item-row" id="i8sf3" style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 0px; padding-bottom: 5px; padding-left: 0px; width: 100%;">
                                    <tbody id="i64ti" style="box-sizing: border-box;">
                                    <tr id="ibzqw" style="box-sizing: border-box;">
                                        <td width="50%" valign="top" class="grid-item-cell2-l" id="ibr7e" style="box-sizing: border-box; vertical-align: top; padding-right: 10px; width: 50%;">
                                            <table width="100%" class="grid-item-card" id="i7keh" style="box-sizing: border-box; width: 100%; padding-top: 5px; padding-right: 0px; padding-bottom: 5px; padding-left: 0px; margin-bottom: 10px;">
                                                <tbody id="i5r6f" style="box-sizing: border-box;">
                                                <tr id="i5ehg" style="box-sizing: border-box;">
                                                    <td bgcolor="rgb(255, 255, 255)" align="center" class="grid-item-card-cell" id="irzp7"
                                                        style="box-sizing: border-box; background-color: rgb(255, 255, 255); overflow-x: hidden; overflow-y: hidden; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; text-align: center; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                                        <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/tmp-send-test.jpg" alt="Image1" class="grid-item-image" id="iq5ca"
                                                             style="box-sizing: border-box; line-height: 150px; font-size: 50px; color: rgb(120, 197, 214); margin-bottom: 15px; width: 100%;">
                                                        <table class="grid-item-card-body" id="iq9jb" style="box-sizing: border-box;">
                                                            <tbody id="i9dji" style="box-sizing: border-box;">
                                                            <tr id="ivh57" style="box-sizing: border-box;">
                                                                <td width="100%" class="grid-item-card-content" id="i3dxp"
                                                                    style="box-sizing: border-box; font-size: 13px; color: rgb(111, 119, 125); padding-top: 0px; padding-right: 10px; padding-bottom: 20px; padding-left: 10px; width: 100%; line-height: 20px;">
                                                                    <h1 class="card-title" id="i87lh" style="box-sizing: border-box; font-size: 25px; font-weight: 300; color: rgb(68, 68, 68);">Test it
                                                                    </h1>
                                                                    <p class="card-text" id="itcru" style="box-sizing: border-box;">You can send email tests directly from the editor and check how are looking on your email clients
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="50%" valign="top" class="grid-item-cell2-r" id="i9fpa" style="box-sizing: border-box; vertical-align: top; padding-left: 10px; width: 50%;">
                                            <table width="100%" class="grid-item-card" id="iv6uh" style="box-sizing: border-box; width: 100%; padding-top: 5px; padding-right: 0px; padding-bottom: 5px; padding-left: 0px; margin-bottom: 10px;">
                                                <tbody id="ie4do" style="box-sizing: border-box;">
                                                <tr id="izxqh" style="box-sizing: border-box;">
                                                    <td bgcolor="rgb(255, 255, 255)" align="center" class="grid-item-card-cell" id="ioxyi"
                                                        style="box-sizing: border-box; background-color: rgb(255, 255, 255); overflow-x: hidden; overflow-y: hidden; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; text-align: center; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                                                        <img src="http://www.yovchevski.plumtex.store/administrator/templates/assets/newsletters/tmp-devices.jpg" alt="Image2" class="grid-item-image" id="ic0uz"
                                                             style="box-sizing: border-box; line-height: 150px; font-size: 50px; color: rgb(120, 197, 214); margin-bottom: 15px; width: 100%;">
                                                        <table class="grid-item-card-body" id="i1bz8" style="box-sizing: border-box;">
                                                            <tbody id="ipz6j" style="box-sizing: border-box;">
                                                            <tr id="iebyd" style="box-sizing: border-box;">
                                                                <td width="100%" class="grid-item-card-content" id="i8t9k"
                                                                    style="box-sizing: border-box; font-size: 13px; color: rgb(111, 119, 125); padding-top: 0px; padding-right: 10px; padding-bottom: 20px; padding-left: 10px; width: 100%; line-height: 20px;">
                                                                    <h1 class="card-title" id="ied8y" style="box-sizing: border-box; font-size: 25px; font-weight: 300; color: rgb(68, 68, 68);">Responsive
                                                                    </h1>
                                                                    <p class="card-text" id="iwksf" style="box-sizing: border-box;">Using the device manager you'll always send a fully responsive contents
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table align="center" class="footer" id="i5sti" style="box-sizing: border-box; margin-top: 50px; color: rgb(152, 156, 165); text-align: center; font-size: 11px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px;">
                                    <tbody id="iikn4" style="box-sizing: border-box;">
                                    <tr id="igzjz" style="box-sizing: border-box;">
                                        <td class="footer-cell" id="iycz8" style="box-sizing: border-box;">
                                            <div class="c2577" id="ivyce" style="box-sizing: border-box; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;">
                                                <p class="footer-info" style="box-sizing: border-box;">GrapesJS Newsletter Builder is a free and open source preset (plugin) <br/>used on top of the GrapesJS core library.</p>
                                                <p id="iyuqh" style="box-sizing: border-box;">
                                                    <a href="http://www.yovchevski.plumtex.store" class="link" style="box-sizing: border-box; color: rgb(217, 131, 166);">PlumTex Store</a>
                                                    <br id="iuxgpf" style="box-sizing: border-box;">
                                                </p>
                                            </div>
                                            <div class="c2421" style="box-sizing: border-box; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;">
                                                NEWSLETTER GENERATED BY
                                                <a href="https://www.plumtex.com" target="_blank" class="link" style="box-sizing: border-box; color: rgb(217, 131, 166);">PlumTex Store</a>
                                                <p class="c6625" style="box-sizing: border-box;">
                                                    <a href="http://www.yovchevski.plumtex.store/function/unsubscribe">Unsubscribe from this newsletter</a>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
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