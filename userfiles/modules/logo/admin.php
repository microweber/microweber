<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">

    <div class="">

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
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

                $alt_logo = true;
                ?>

                <style>

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

                        var dialog;
                        var picker = new mw.filePicker({
                            type: 'images',
                            label: false,
                            autoSelect: false,
                            footer: true,
                            _frameMaxHeight: true,
                            onResult: function (res) {
                                var url = res.src ? res.src : res;
                                if(!url) return;
                                url = url.toString();

                                mw.notification.success('<?php _ejs('Logo image selected') ?>');
                                
                               if (inverse) {
                                   setNewImageInv(url);
                               } else {
                                   setNewImage(url);
                               }

                                dialog.remove();
                            }
                        });
                        dialog = mw.top().dialog({
                            content: picker.root,
                            title: mw.lang('Select image'),
                            footer: false,
                            width: 860
                        });

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
                            <label class="form-label my-3 font-weight-bold"><?php _e("Choose Logo type"); ?></label>

                            <label class="form-check">
                                <input type="radio" id="logotype1" option-group="<?php print $logo_name ?>" class="mw_option_field form-check-input me-2" <?php if ($logotype == 'image'){ ?>checked<?php } ?> name="logotype" value="image">
                                <span class="form-check-label"><?php _e("Upload your logo image in .JPG or .PNG format"); ?></span>
                            </label>

                            <label class="form-check">
                                <input type="radio" id="logotype2" option-group="<?php print $logo_name ?>"  class="mw_option_field form-check-input me-2" <?php if ($logotype == 'text'){ ?>checked<?php } ?> name="logotype" value="text">
                                <span class="form-check-label"><?php _e("Type your brand and choose font size and style"); ?></span>
                            </label>

                            <label class="form-check">
                                <input type="radio" id="logotype3" option-group="<?php print $logo_name ?>"  class="mw_option_field form-check-input me-2" <?php if ($logotype == 'both' or $logotype == false){ ?>checked<?php } ?> name="logotype" value="both">
                                <span class="form-check-label"><?php _e("Type your brand and choose font size") ;?></span>
                            </label>

                        </div>
                    </div>

                    <div class="js-logo-image-holder">
                        <div class="form-group">
                            <label class="form-label font-weight-bold"><?php _e("Main Logo"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("This logo image will appear every time"); ?></small>
                        </div>

                        <div class="image-row">
                            <div class="the-image-holder">
                                <img style="display: none;" src="<?php print $logoimage ?>" id="logo-image-edit">
                                <img src="<?php if ($logoimage): ?><?php echo thumbnail($logoimage, 200); ?><?php endif; ?>" class="the-image" alt="" <?php if ($logoimage != '' and $logoimage != false): ?><?php else: ?>style="display:block;"<?php endif; ?> />

                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <a href="javascript:mw_admin_logo_upload_browse_existing()" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse media'); ?></a>
                                <?php if ($logotype == 'both' or $logotype == 'image' or $logotype == false): ?>
                                    <a class="btn btn-outline-primary btn-rounded btn-sm" onclick="mw.edit_logo_image_crop()" href="javascript:void(0);"><?php _e("Edit"); ?></a>
                                <?php endif; ?>

                                <button type="button" class="btn btn-danger btn-rounded btn-sm js-remove-logoimage"> <?php _e("Remove"); ?></button>

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
                                <label class="form-label"><?php _e("Alternative Logo"); ?></label>
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
                                    <a href="javascript:mw_admin_logo_upload_browse_existing('true')" class="btn btn-outline-primary btn-rounded btn-sm"><?php _e('Browse Media'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="form-group">
                            <label class="form-label"><?php _e("Scale the logo image"); ?></label>

                            <div>
                                <p class="mb-1"><?php _e('Image size'); ?> - <span id="imagesizeval"></span></p>
                                <div class="range-slider">
                                    <input name="size-slider" id="size-slider" class="mw-ui-field-range" max="200" min="20" type="range" option-group="<?php print $logo_name ?>"  value="<?php print $size; ?>">
                                </div>
                                <input name="size" id="size" type="hidden"  option-group="<?php print $logo_name ?>"  class="mw_option_field" value="<?php print $size; ?>">
                            </div>
                        </div>

                        <label class="form-check ms-1">
                            <input type="checkbox" class="form-check-input me-2" checked="" id="auto_scale_logo" value="pending">
                            <span class="form-check-label"><?php _e('Set auto logo size'); ?></span>
                        </label>

                    </div>

                    <div class="js-logo-text-holder">
                        <div class="form-group">
                            <label class="form-label"><?php _e("Design your logo"); ?></label>
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
