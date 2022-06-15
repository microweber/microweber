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


        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <?php
                $logo_name = $params['id'];

                if (isset($params['logo-name'])) {
                    $logo_name = $params['logo-name'];
                } else if (isset($params['logo_name'])) {
                    $logo_name = $params['logo_name'];
                }


                $logoimage = get_module_option('logoimage', $logo_name);
                $logoimage_inverse = get_module_option('logoimage_inverse', $logo_name);
                $text = get_module_option('text', $logo_name);
                $font_family = get_module_option('font_family', $logo_name);
                $font_size = get_module_option('font_size', $logo_name);
                if ($font_size == false) {
                    $font_size = 30;
                }
                $logotype = get_module_option('logotype', $logo_name);

                if (!$logotype) {
                    $logotype = 'image';
                }

                $size = get_module_option('size', $logo_name);
                if ($size == false or $size == '') {
                    $size = 60;
                }

                $alt_logo = false;
                if (isset($params['data-alt-logo'])) {
                    $alt_logo = $params['data-alt-logo'];
                }
                ?>

                <style>
                    .the-image-holder .upload-image {
                        width: 33px;
                        height: 33px;
                        -webkit-border-radius: 100%;
                        -moz-border-radius: 100%;
                        border-radius: 100%;
                        padding: 0;
                        margin-top: -65px;
                        margin-left: 5px;
                        text-align: center;
                    }

                    .the-image,
                    .the-image-inverse {
                        display: block;
                    }

                    .the-image,
                    .the-image-inverse {
                        max-width: 300px;
                        background-color: #e1e2e4;
                        padding: 15px;
                        min-height: 50px;
                        margin-bottom: 20px;
                    }

                    .the-image[src=''],
                    .the-image-inverse[src=''] {
                        width: 100%;
                        background-color: #e1e2e4;
                    }

                    #sizeslider {
                        width: 135px;
                    }
                </style>

                <script>mw.require('tools/images.js');</script>
                <script>
                    $(document).ready(function () {
                        UP = mw.uploader({
                            element: document.getElementById('upload-image'),
                            filetypes: 'images',
                            multiple: false
                        });

                        $(UP).on('FileUploaded', function (a, b) {
                            setNewImage(b.src);
                            setAuto();
                        });

                        UPInverse = mw.uploader({
                            element: document.getElementById('upload-image-inverse'),
                            filetypes: 'images',
                            multiple: false
                        });

                        $(UPInverse).on('FileUploaded', function (a, b) {
                            setNewImageInv(b.src);
                            setAuto();
                        });

                        // mw.$("[name=font_family] option").each(function () {
                        //     var val = $(this).attr('value');
                        //     if (val != '') {
                        //         mw.require('//fonts.googleapis.com/css?family=' + val + '&filetype=.css', true);
                        //         $(this).css('fontFamily', $(this).text());
                        //     }
                        // });

                        // mw.$("[name=font_family]").on("change", function () {
                        //
                        //     mw.$("#text").css('fontFamily', $(this.options[this.selectedIndex]).text())
                        // });


                        $(document).on('change', '[name=font_family]', function() {


                            var v =  $('[name=font_family] option:selected').first().val();

                            mw.$("#text").css('fontFamily',v)



                            setTimeout(function () {
                                mw.$("#text").trigger('change');

                            }, 78)



                        });



                    });

                    function setNewImage(s) {
                        mw.$("#logoimage").val(s);
                        mw.$(".the-image").show().attr('src', s);
                        setTimeout(function () {
                            mw.$("#logoimage").trigger('change');
                        }, 78)
                    }

                    function setNewImageInv(s) {
                        mw.$("#logoimage_inverse").val(s);
                        mw.$(".the-image-inverse").show().attr('src', s);
                        setTimeout(function () {
                            mw.$("#logoimage_inverse").trigger('change');
                        }, 78)
                    }

                    var mw_admin_logo_upload_browse_existing = function (inverse = false) {
                        var modal_id = 'mw_admin_logo_upload_browse_existing_modal<?php print $logo_name ?>' + (inverse ? '_inverse' : '');
                        var dialog = mw.top().dialogIframe({
                            url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_logo_upload_browse_existing_modal<?php print $logo_name ?>&from_url=<?php print site_url() ?>',
                            title: "Browse pictures",
                            id: modal_id,
                            onload: function () {
                                this.iframe.contentWindow.mw.on.hashParam('select-file', function (pval) {
                                    mw.notification.success('<?php _ejs('Logo image selected') ?>');
                                    if (inverse) {
                                        setNewImageInv(pval);
                                    } else {
                                        setNewImage(pval);
                                    }
                                    dialog.remove();
                                });
                            },
                            height: 'auto',
                            autoHeight: true
                        })
                    }

                </script>
                <script>
                    function showLogoType() {
                        if ($('input[name="logotype"]:checked').val() == 'image') {
                            $('.js-logo-image-holder').show();
                            $('.js-logo-text-holder').hide();
                        } else if ($('input[name="logotype"]:checked').val() == 'text') {
                            $('.js-logo-image-holder').hide();
                            $('.js-logo-text-holder').show();
                        }
                    }

                    $(document).ready(function () {
                        $('[name=font_family] option[value="<?php print $font_family; ?>"]').prop('selected', true);

                        showLogoType();
                        $('input[name="logotype"]').each(function () {
                            $(this).parent().parent().on("click", function () {
                                setTimeout(function () {
                                    showLogoType();
                                }, 78)
                            });
                        });
                    });
                </script>
                <script>
                    $(document).ready(function () {
                        var $size = $("#size"),
                            $size_slider = $("#size-slider"),
                            $imagesizeval = $("#imagesizeval");


                        if ("<?php print $size; ?>" == 'auto') {
                            $imagesizeval.html('auto');
                            $("#auto_scale_logo").attr("checked", true);
                        } else {
                            $imagesizeval.html($size_slider.val());
                            $("#auto_scale_logo").attr("checked", false);
                        }


                        $size_slider.on('input change', function () {
                            $size.val(this.value)
                            $("#auto_scale_logo")[0].checked = false;
                            $imagesizeval.html(this.value + 'px');
                            $size.trigger('change')
                        });

                        $("#auto_scale_logo").on('change', function () {
                            if (this.checked) {
                                setAuto();
                            } else {
                                var val1 = $size_slider.val();
                                $size.val(val1).trigger('change');
                                $imagesizeval.html(val1 + 'px');
                            }
                        });

                        setAuto = function () {
                            $imagesizeval.html('auto');
                            $size.val('auto');
                            $size.trigger('change');
                        };
                    });
                </script>
                <script>
                    $(document).ready(function () {
                        mw.top().on('imageSrcChanged', function (e, node, url) {
                            setNewImage(url);
                            setAuto();
                        });
                    });

                    mw.edit_logo_image_crop = function () {
                        mw.top().image.currentResizing = $('#logo-image-edit');
                        mw.top().image.settings();
                        return false;
                    }
                </script>

                <div class="module-live-edit-settings module-logo-settings" id="module-logo-settings">
                    <input type="hidden" class="mw_option_field" name="logoimage" id="logoimage" option-group="<?php print $logo_name ?>"  />
                    <input type="hidden" class="mw_option_field" name="font_size" option-group="<?php print $logo_name ?>" value="<?php print $font_size; ?>"  />
                    <input type="hidden" class="mw_option_field" name="logoimage_inverse" id="logoimage_inverse" option-group="<?php print $logo_name ?>" />

                    <div class="logo-module-types">
                        <div class="form-group">
                            <label class="control-label my-3"><?php _e("Choose Logo type"); ?></label>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="logotype1" option-group="<?php print $logo_name ?>" class="mw_option_field custom-control-input" <?php if ($logotype == 'image'){ ?>checked<?php } ?> name="logotype" value="image">
                                <label class="custom-control-label" for="logotype1"><?php _e('Image Logo'); ?><br/>
                                    <small class="text-muted"><?php _e("Upload your logo image in .JPG or .PNG format"); ?></small>
                                </label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="logotype2" option-group="<?php print $logo_name ?>"  class="mw_option_field custom-control-input" <?php if ($logotype == 'text'){ ?>checked<?php } ?> name="logotype" value="text">
                                <label class="custom-control-label" for="logotype2"><?php _e('Text Logo'); ?><br/>
                                    <small class="text-muted"><?php _e("Type your brand and choose font size and style"); ?></small>
                                </label>
                            </div>

                            <div class="custom-control custom-radio d-none">
                                <input type="radio" id="logotype3" option-group="<?php print $logo_name ?>"  class="mw_option_field custom-control-input" <?php if ($logotype == 'both' or $logotype == false){ ?>checked<?php } ?> name="logotype" value="both">
                                <label class="custom-control-label" for="logotype3"><?php _e('Both'); ?><br/>
                                    <small class="text-muted"><?php _e("Type your brand and choose font size") ;?></small>
                                </label>
                            </div>
                        </div>
                    </div>


                    <hr class="thin my-4"/>

                    <div class="js-logo-image-holder">
                        <div class="form-group">
                            <label class="control-label"><?php _e("Main Logo"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
                        </div>

                        <div class="image-row">
                            <div class="the-image-holder">
                                <img style="display: none;" src="<?php print $logoimage ?>" id="logo-image-edit">
                                <img src="<?php if ($logoimage): ?><?php echo thumbnail($logoimage, 200); ?><?php endif; ?>" class="the-image" alt="" <?php if ($logoimage != '' and $logoimage != false): ?><?php else: ?>style="display:block;"<?php endif; ?> />

                            </div>

                            <div>
                                <span class="btn btn-primary btn-rounded btn-sm" id="upload-image"><?php _e("Upload Image"); ?></span>
                                <a href="javascript:mw_admin_logo_upload_browse_existing()" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse Uploaded'); ?></a>
                                <?php if ($logotype == 'both' or $logotype == 'image' or $logotype == false): ?>
                                    <a class="btn btn-outline-primary btn-rounded btn-sm" onclick="mw.edit_logo_image_crop()" href="javascript:void(0);"><?php _e("Edit image"); ?></a>
                                <?php endif; ?>

                                <button type="button" class="btn btn-danger btn-rounded btn-sm js-remove-logoimage"><i class="mdi mdi-trash-can-outline"></i> <?php _e("Remove the logo"); ?></button>

                                <script>
                                    $('.js-remove-logoimage').on('click', function () {
                                        $('#logoimage').val('');
                                        $("#logoimage").trigger('change');
                                        $('img.the-image').attr('src', '');
                                    });
                                </script>

                            </div>
                        </div>

                        <?php if ($alt_logo == 'true'): ?>
                            <br/>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Alternative Logo"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("For example we are using the alternative logo when we have a sticky navigation"); ?></small>
                            </div>

                            <div class="image-row">
                                <div class="the-image-holder">
                                    <img src="<?php if ($logoimage_inverse): ?><?php echo thumbnail($logoimage_inverse, 200); ?><?php endif; ?>" class="the-image-inverse" alt="" <?php if ($logoimage_inverse != '' and $logoimage_inverse != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                                    <div class="js-remove-logo-alt-btn <?php if ($logoimage_inverse == ''): ?>d-none<?php endif; ?>">
                                        <button type="button" class="btn btn-link px-0 mb-3 text-danger js-remove-alt-logoimage"><i class="mdi mdi-trash-can-outline"></i> <?php _e("Remove the logo"); ?></button>

                                        <script>
                                            $('.js-remove-alt-logoimage').on('click', function () {
                                                $('#logoimage_inverse').val('');
                                                $("#logoimage_inverse").trigger('change');
                                                $('img.the-image-inverse').attr('src', '');
                                                $(this).parent().addClass('d-none');
                                            });
                                        </script>
                                    </div>
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-rounded btn-sm" id="upload-image-inverse"><?php _e("Upload Image"); ?></span>
                                    <a href="javascript:mw_admin_logo_upload_browse_existing('true')" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse Uploaded'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr class="thin"/>


                        <div class="form-group">
                            <label class="control-label"><?php _e("Scale the logo image"); ?></label>

                            <div>
                                <p class="mb-1"><?php _e('Image size'); ?> - <span id="imagesizeval"></span></p>
                                <div class="range-slider">
                                    <input name="size-slider" id="size-slider" class="mw-ui-field-range" max="200" min="20" type="range" option-group="<?php print $logo_name ?>"  value="<?php print $size; ?>">
                                </div>
                                <input name="size" id="size" type="hidden"  option-group="<?php print $logo_name ?>"  class="mw_option_field" value="<?php print $size; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked="" id="auto_scale_logo" value="pending">
                                <label class="custom-control-label" for="auto_scale_logo"><?php _e('Set auto logo size'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="js-logo-text-holder">
                        <div class="form-group">
                            <label class="control-label"><?php _e("Design your logo"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("Choose font size for your logo"); ?></small>


                            <module  id="google-fonts" type="editor/fonts/select_option" group="<?php print $logo_name ?>"  name="font_family"  show_more_link="true" />



                        </div>

                        <div class="form-group">
                            <script>mw.require('editor.js')</script>
                            <script>
                                $(document).ready(function () {
                                    var editor = mw.Editor({
                                        selector: '#text',
                                        mode: 'div',
                                        smallEditor: false,
                                        minHeight: 150,
                                        maxHeight: '70vh',
                                        controls: [
                                            [
                                                'undoRedo', '|',
                                                {
                                                    group: {
                                                        icon: 'mdi mdi-format-bold',
                                                        controls: ['bold', 'italic', 'underline', 'strikeThrough'],

                                                    }
                                                },
                                                    'fontSize',
                                                '|', 'wordPaste', 'textColor', 'textBackgroundColor',

                                                'removeFormat'

                                            ],
                                        ]
                                    });
                                    $(editor).on('change', function (e, val){
                                        console.log(val)
                                        var fs = $('[name="font_size"]');
                                        var area = this.$editArea.get(0);
                                        var curr = getComputedStyle(area).fontSize;
                                        if(curr !== fs.val()) {
                                            fs.val(curr).trigger('change');
                                        }
                                    })
                                });
                            </script>

                            <textarea class="mw_option_field form-control" option-group="<?php print $logo_name ?>"   placeholder="<?php _e('Enter Text'); ?>" row="5" name="text" id="text"><?php print $text; ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates" simple="true"/>
            </div>
        </div>
    </div>
</div>
