<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">




    <div class="row">



        <div class="col-md-6">
            <div class="form-group">
                <?php
                $manifest_app_icon = get_option('manifest_app_icon', 'website');
                $nomanifest_app_icon = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.svg';
                if (!$manifest_app_icon) {
                    $manifest_app_icon = $nomanifest_app_icon;
                }
                ?>
                <script>
                    $(document).ready(function () {
                        websitemanifest_app_icon = mw.uploader({
                            element: document.getElementById('js-upload-manifest_app_icon-image'),
                            filetypes: 'images',
                            multiple: false
                        });
                        $(websitemanifest_app_icon).on('FileUploaded', function (a, b) {
                            mw.$("#manifest_app_icon-preview").val(b.src).trigger('change');
                            mw.$(".js-manifest_app_icon").attr('src', b.src);
                            // mw.$("link[rel*='icon']").attr('href', b.src);
                        });
                        mw.element('#remove-manifest_app_icon-btn').on('click', function(){
                            mw.element('#manifest_app_icon-preview').val('').trigger('change')
                            mw.element('.js-manifest_app_icon').attr('src', '<?php print $nomanifest_app_icon; ?>');
                        })
                    })

                </script>
                <br>

                <label class="form-label"><?php _e("Website manifest_app_icon"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e('Select an icon for your website.'); ?>
                    <br>
                    <?php _e('Must be PNG image'); ?>,
                    <?php _e('Must have exact size of:'); ?> 144x144 px
                </small>
                <div class="d-flex">
                    <div class="avatar img-absolute border-radius-0 border-silver me-3" >
                        <img src="<?php print $manifest_app_icon; ?>" class="js-manifest_app_icon" />
                        <input type="hidden" class="mw_option_field" name="manifest_app_icon" id="manifest_app_icon-preview" value="<?php print $manifest_app_icon; ?>" option-group="website"/>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="js-upload-manifest_app_icon-image"><?php _e("Upload"); ?></button>
                    <span class="tip mw-btn-icon" id="remove-manifest_app_icon-btn"   data-bs-toggle="tooltip" aria-label="Clear manifest_app_icon" data-bs-original-title="Clear manifest_app_icon">
                                                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/trash.svg" alt="">

                                             </span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php
                $maskable_icon = get_option('maskable_icon', 'website');
                $nofavicon = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.svg';
                if (!$maskable_icon) {
                    $maskable_icon = $nofavicon;
                }
                ?>

                <script>
                    $(document).ready(function () {
                        favUP = mw.uploader({
                            element: document.getElementById('upload-icoimage'),
                            filetypes: 'images',
                            multiple: false
                        });

                        $(favUP).on('FileUploaded', function (a, b) {
                            mw.$("#maskable_icon").val(b.src).trigger('change');
                            mw.$(".js-icoimage").attr('src', b.src);
                            mw.$("link[rel*='icon']").attr('href', b.src);
                        });

                        mw.element('#remove-favicon-btn').on('click', function(){
                            mw.element('#maskable_icon').val('').trigger('change')
                            mw.element('.js-icoimage').attr('src', '<?php print $nofavicon; ?>');
                        })
                    });
                </script>

                <br>

                <label class="form-label"><?php _e("Website Maskable icon"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e('Select an icon for your website.'); ?>

                    <br>
                    <?php _e('Must be PNG image'); ?>,
                    <?php _e('Must have exact size of:'); ?> 512x512 px
                </small>
                <div class="d-flex">
                    <div class="avatar img-absolute w-40 border-radius-0 border-silver me-3" >
                        <img src="<?php print $maskable_icon; ?>" class="js-icoimage"/>
                        <input type="hidden" class="mw_option_field" name="maskable_icon" id="maskable_icon" value="<?php print $maskable_icon; ?>" option-group="website"/>
                    </div>

                    <button type="button" class="btn btn-outline-primary" id="upload-icoimage"><?php _e("Upload maskable icon"); ?></button>
                    <span class="tip mw-btn-icon" id="remove-favicon-btn" data-bs-toggle="tooltip" aria-label="Clear manifest_app_icon" data-bs-original-title="Clear manifest_app_icon">
                                               <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/trash.svg" alt="">
                                            </span>


                </div>
            </div>
        </div>

    </div>





</div>
