<?php only_admin_access(); ?>

<style>

    #module-image-rollover-settings,
    #module-image-rollover-settings * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #font-and-text {
        width: 100%;
    }

    #font-and-text, #font-and-text * {
        vertical-align: middle;
    }

    .image-row,
    .image-row * {
        vertical-align: middle;
    }

    .the-image-holder {
        width: 200px;
    }

    .the-image, .the-image-rollover {
        margin-right: 12px;
        max-width: 170px;
        max-height: 110px;
        background-color: #eee;
    }

    .the-image[src=''], .the-image-rollover[src=''] {
        width: 133px;
        height: 100px;
    }

    #sizeslider,
    #fontsizeslider {
        width: 380px;
    }

    #module-image-rollover-settings .mw-ui-box-content {
        padding: 20px;
    }

</style>

<?php // image params can be set when module used in menu
if (isset($params['menu_rollover'])) {
	$default_image = isset($params['default_image'])? $params['default_image']:'';
	$rollover_image = isset($params['rollover_image'])? $params['rollover_image']:'';
	$size = isset($params['size'])? $params['size']:'';
} else {
	$default_image = get_option('default_image', $params['id']);
	$rollover_image = get_option('rollover_image', $params['id']);
	$text = get_option('text', $params['id']);
	$size = get_option('size', $params['id']);
}
if ($size == false or $size == '') {
    $size = 60;
}
?>

<div class="module-live-edit-settings" id="module-image-rollover-settings">

    <div class="mw-ui-field-holder">

        <div class="mw-ui-box mw-ui-box-content">
            <div class="mw-ui-row-nodrop image-row">

                <div class="mw-ui-col">

                    <h3>Default image</h3>
                    <img src="<?php print $default_image; ?>" class="pull-left the-image"
                         alt="" <?php if ($default_image != '' and $default_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                    <br>
                    <div style="padding-top: 15px; clear: both"></div>
                    <span class="mw-ui-btn" id="upload-image"><span
                                class="mw-icon-upload"></span><?php _e('Upload Image'); ?></span><br/>
                    <?php _e('or'); ?> <a href="javascript:mw_admin_image_rollover_upload_browse_existing()" class="mw-ui-link mw-ui-btn-small"><?php _e('browse uploaded'); ?></a>

                </div>

                <div class="mw-ui-col">

                    <h3>Rollover image</h3>
                    <img src="<?php print $rollover_image; ?>" class="pull-left the-image-rollover"
                         alt="" <?php if ($rollover_image != '' and $rollover_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                    <br>
                    <div style="padding-top: 15px; clear: both"></div>
                    <span class="mw-ui-btn" id="upload-image-rollover"><span
                                class="mw-icon-upload"></span><?php _e('Upload Image'); ?></span><br/>
                    <?php _e('or'); ?> <a href="javascript:mw_admin_image_rollover_upload_browse_existing(true)" class="mw-ui-link mw-ui-btn-small"><?php _e('browse uploaded'); ?></a>

                </div>
			</div>

			<div class="mw-ui-row-nodrop image-row">

                <div class="mw-ui-col">

                    <hr/>

                    <label class="mw-ui-label" style="padding-top: 10px;"><span><?php _e('Image size'); ?></span> - <b id="imagesizeval"></b></label>
                    <div id="sizeslider" class="mw-slider"></div>
                    <br>
                    <label class="mw-ui-check"><input type="checkbox" checked="" id="size_auto"
                                                      value="pending"><span></span><span><?php _e('Auto'); ?></span></label>

				<?php if (!isset($params['menu_rollover'])) { ?>

					<div class="mw-ui-col-container" style="padding-top: 20px;">

						<div class="mw-ui-field-holder" style="padding-bottom: 20px;">
							<label class="mw-ui-label">Title:</label>
							<input type="text"
								  class="mw-ui-field mw-ui-filed-big mw_option_field w100"
								  placeholder="<?php _e('Enter title'); ?>"
								  value="<?php print $text; ?>"
								  name="text"
								  id="text"/>
						</div>

						<div class="mw-ui-field-holder" style="padding-bottom: 20px;">
							<label class="mw-ui-label">Links to:</label>
							<input type="text"
								  class="mw-ui-field mw-ui-filed-big mw_option_field w100"
								  placeholder="<?php _e('Enter URL'); ?>"
								  value="<?php print $text; ?>"
								  name="href-url"
								  id="href-url"/>
						</div>

						<module type="admin/modules/templates" simple=true/>
					</div>

				<?php } ?>

                </div>

             </div>

                <input type="hidden" class="mw_option_field" name="size" id="size" value="<?php print $size;?>"/>
                <script>
                    $(function () {
                        $("#sizeslider").slider({
                            change: function (event, ui) {
                                $('#size').val(ui.value).trigger('change');
                                $("#size_auto").attr("checked", false);
                            },
                            slide: function (event, ui) {
                                $("#imagesizeval").html(ui.value + "px");
                            },
                            min: 30,
                            max: 320,
                            value:<?php if ($size != 'auto') {
                            print $size;
                        } else {
                            print 60;
                        } ?>
                        });

                        if ("<?php print $size; ?>" == 'auto') {
                            $("#imagesizeval").html('auto');
                            $("#size_auto").attr("checked", true);
                        }
                        else {
                            $("#imagesizeval").html("<?php print $size; ?>px");
                            $("#size_auto").attr("checked", false);
                        }


                        $("#size_auto").bind('change', function () {
                            if (this.checked === true) {
                                setAuto()
                            }
                            else {
                                var val1 = $('#sizeslider').slider("option", "value");
                                $('#size').val(val1).trigger('change');
                                $("#imagesizeval").html(val1 + 'px');
                            }
                        });

                        setAuto = function () {
                            $('#size').val('auto').trigger('change');
                            $("#imagesizeval").html('auto');
                        };
                    });
                </script>

        </div>
    </div>
    <input type="hidden" class="mw_option_field" name="default_image" id="default_image" value="<?php print $default_image;?>"/>
    <input type="hidden" class="mw_option_field" name="rollover_image" id="rollover_image" value="<?php print $rollover_image;?>"/>
</div>


<script>

    $(document).ready(function () {
        UP = mw.uploader({
            element: mwd.getElementById('upload-image'),
            filetypes: 'images',
            multiple: false
        });

        $(UP).bind('FileUploaded', function (a, b) {
            setNewImage(b.src);
            setAuto();
        });

        UPRollover = mw.uploader({
            element: mwd.getElementById('upload-image-rollover'),
            filetypes: 'images',
            multiple: false
        });

        $(UPRollover).bind('FileUploaded', function (a, b) {
            setNewImageRollover(b.src);
            setAuto();
        });

    });

    function setNewImage(s) {
        mw.$("#default_image").val(s).trigger('change');
        mw.$(".the-image").show().attr('src', s);
    }

    function setNewImageRollover(s) {
        mw.$("#rollover_image").val(s).trigger('change');
        mw.$(".the-image-rollover").show().attr('src', s);
    }

    var mw_admin_image_rollover_upload_browse_existing = function (rollover=false) {

        var mw_admin_image_rollover_upload_browse_existing_modal = window.top.mw.modalFrame({
            url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_image_rollover_upload_browse_existing_modal<?php print $params['id'] ?>&from_url=<?php print site_url() ?>',
            title: "Browse pictures",
            id: 'mw_admin_image_rollover_upload_browse_existing_modal<?php print $params['id'] ?>',
            onload: function () {
                this.iframe.contentWindow.mw.on.hashParam('select-file', function () {
                    mw_admin_image_rollover_upload_browse_existing_modal.hide();
                    mw.notification.success('<?php _e('Image selected') ?>');
                    if(rollover) {
                    	setNewImageRollover(this);
                    } else {
                    	setNewImage(this);
                    }
                })
                this.iframe.contentWindow.document.body.style.padding = '15px';
            },
            height: 400
        })

    }
</script>