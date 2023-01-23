<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <style>
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
                width: 100%;
            }

            .the-image, .the-image-rollover {

                max-width: 100%;
                max-height: 110px;
                background-color: #eee;
                margin: 0;
            }

            .the-image[src=''], .the-image-rollover[src=''] {
                width: 100%;
                height: 100px;
            }


        </style>

        <?php // image params can be set when module used in menu
        if (isset($params['menu_rollover'])) {
            $default_image = isset($params['default_image']) ? $params['default_image'] : '';
            $rollover_image = isset($params['rollover_image']) ? $params['rollover_image'] : '';
            $size = isset($params['size']) ? $params['size'] : '';
        } else {
            $default_image = get_option('default_image', $params['id']);
            $rollover_image = get_option('rollover_image', $params['id']);
            $text = get_option('text', $params['id']);
            $size = get_option('size', $params['id']);
        }
        if ($size == false or $size == '') {
            $size = 60;
        }

        $no_image = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.jpg';

        if (!$default_image) {
            $default_image = $no_image;
        }
        if (!$rollover_image) {
            $rollover_image = $no_image;
        }
        ?>

        <script>
            mw.require('filepicker.js')

            function setNewImage(s) {
                mw.$("#default_image").val(s).trigger('change');
                mw.$(".the-image").show().attr('src', s);
            }

            function setNewImageRollover(s) {
                mw.$("#rollover_image").val(s).trigger('change');
                mw.$(".the-image-rollover").show().attr('src', s);
            }

            var chooseImage = function (rollover) {
                var dialog;
                var mwtarget = mw.top();
                var picker = new mwtarget.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    fileUploaded: function (file) {
                        handleResult(file.src);
                        dialog.remove()
                    },
                    onResult: handleResult,
                    cancel: function () {
                        dialog.remove()
                    }
                });
                dialog = mwtarget.dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 1200
                })

                function handleResult (pval) {
                    if(Array.isArray(pval)){
                        pval = pval[0]
                    }
                    if (rollover) {
                        setNewImageRollover(pval);
                    } else {
                        setNewImage(pval);
                    }
                    dialog.remove()
                }
            }

            $(function () {
                var sizeAutoCheckBox = document.querySelector("#size_auto");
                $("#sizeslider").slider({
                    change: function (event, ui) {
                        $('#size').val(ui.value).trigger('change');
                        if(sizeAutoCheckBox.checked) {
                            sizeAutoCheckBox.checked = false;
                        }
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


                $("#size_auto").on('change', function () {
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

        <?php if (!isset($params['menu_rollover'])) { ?>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _lang('Settings', "modules/image_rollover"); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _lang('Templates', "modules/image_rollover"); ?></a>
        </nav>
        <?php } ?>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings  module-image-rollover-settings" id="module-image-rollover-settings">
                    <div class="row image-row">
                        <div class="col ">
                        <div class="mw-ui-box mw-ui-box-content ">
                            <h6 class="font-weight-bold"><?php _lang("Default image") ?></h6>
                            <img src="<?php print $default_image; ?>" class="the-image d-block" alt="" <?php if ($default_image != '' and $default_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                            <br>
                            <div class="d-block d-md-flex justify-content-between align-items-center p-1">
                                <span class="mw-ui-btn" onclick="chooseImage()">Choose image</span>
                            </div>
                        </div>
                        </div>

                        <div class="col ">
                            <div class="mw-ui-box mw-ui-box-content ">
                            <h6 class="font-weight-bold"><?php _lang("Rollover image") ?></h6>
                            <img src="<?php print $rollover_image; ?>" class="the-image-rollover d-block" alt="" <?php if ($rollover_image != '' and $rollover_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                            <br>
                            <div class="d-block d-md-flex justify-content-between align-items-center p-1">
                                <span class="mw-ui-btn" onclick="chooseImage(true)">Choose image</span>
                            </div>
                        </div>
                        </div>
                    </div>

                    <hr class="thin"/>

                    <div class="form-group">
                        <label class="control-label" style="padding-top: 10px;"><span><?php _lang('Image size', "modules/image_rollover"); ?></span> - <b id="imagesizeval"></b></label>
                        <div id="sizeslider" class="mw-slider"></div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="size_auto" value="pending" checked="">
                            <label class="custom-control-label" for="size_auto"><?php _lang('Auto', "modules/image_rollover"); ?></label>
                        </div>
                    </div>

                    <?php if (!isset($params['menu_rollover'])) { ?>
                        <div class="form-group">
                            <label class="control-label"><?php _lang("Link title") ?></label>
                            <small class="text-muted mb-2 d-block"><?php _lang("Create a link below the image") ?></small>
                            <input type="text" class="mw_option_field form-control" value="<?php print $text; ?>" name="text" id="text"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _lang("Link URL") ?></label>
                            <small class="text-muted mb-2 d-block"><?php _lang("Type the URL for the link") ?></small>
                            <input type="text" class="mw_option_field form-control" placeholder="<?php _lang('http://', "modules/image_rollover"); ?>" value="<?php print $text; ?>" name="href-url" id="href-url"/>
                        </div>
                    <?php } ?>

                    <input type="hidden" class="mw_option_field" name="size" id="size" value="<?php print $size; ?>"/>
                    <input type="hidden" class="mw_option_field" name="default_image" id="default_image" value="<?php print $default_image; ?>"/>
                    <input type="hidden" class="mw_option_field" name="rollover_image" id="rollover_image" value="<?php print $rollover_image; ?>"/>
                </div>
                <!-- Settings Content - End -->
            </div>
            <?php if (!isset($params['menu_rollover'])) { ?>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
