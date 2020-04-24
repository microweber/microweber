<?php
/*
	Video thumbnail added to avoid loading base.js reduce page size by 400KB - 07/02/2018
	Remaining issues:
	1) refresh thumbnail image after upload in admin popup
	2) refresh chk_autoplay setting after thumbnail upload in admin popup
	3) reduce number of saved notifications in admin popup
	4) test with vimeo, metacafe, dailymotion, and facebook videos
*/
?>

<script>
    mw.require("files.js");

    mw.lib.require('font_awesome5');

    setprior = function (v, t) {
        t = t || false;
        mwd.getElementById('prior').value = v;
        $(mwd.getElementById('prior')).trigger('change');
        if (!!t) {
            setTimeout(function () {
                $(t).trigger('change');
            }, 70);
        }
    }

    $(document).ready(function () {
        var upVideo = mw.files.uploader({
            multiple: false,
            filetypes: 'videos'
        });
        var upThumb = mw.files.uploader({
            multiple: false,
            filetypes: 'images'
        });

        var all = $();
        all.push(upVideo);
        all.push(upThumb);

        all.on("error", function () {
            mw.notification.warning("<?php _ejs("Unsupported format"); ?>.")
        });

        $(upVideo).on("FileUploaded", function (a, b) {
            uploadFieldId = 'upload_field';
            uploadStatusId = 'upload_status';
            uploadBtnId = 'upload_btn';
            setprior(2);
            mwd.getElementById(uploadFieldId).value = b.src;
            $("#video-preview").attr("src", b.src).show();
            $("#remove-video-button").show();
            $(mwd.getElementById(uploadFieldId)).trigger("change");
        });

        $(upThumb).on("FileUploaded", function (a, b) {
            fileTypes = 'images';
            uploadFieldId = 'upload_thumb_field';
            uploadStatusId = 'upload_thumb_status';
            uploadBtnId = 'upload_thumb_btn';

            $("#thumb").attr("src", b.src);

            $("#upload_thumb_field").val(b.src).trigger('change');

            mw.tools.refresh_image(mwd.getElementById('thumb'));
            mw.tools.refresh(mwd.getElementById('chk_autoplay'));
        });

        all.on("FileUploaded", function (a, b) {
            mw.notification.success("<?php _ejs("File Uploaded"); ?>");

            $(status).hide();
        });

        $(upThumb).on("progress", function (a, b) {
            $("#upload_thumb_status").show();
            $("#upload_thumb_status").find('.mw-ui-progress-bar').width(b.percent + '%');
            $("#upload_thumb_status").find('.mw-ui-progress-percent').html(b.percent + '%');
        });

        $(upVideo).on("progress", function (a, b) {
            $("#upload_status").show();
            $("#upload_status").find('.mw-ui-progress-bar').width(b.percent + '%');
            $("#upload_status").find('.mw-ui-progress-percent').html(b.percent + '%');
        });
        $(upVideo).on("done", function (a, b) {
            $("#upload_status").hide();

        });

        $('#upload_btn').append(upVideo);
        $('#upload_thumb_btn').append(upThumb);

        mw.$("#emebed_video_field").focus().on('change', function () {
            setprior(1);
        });

        $('[name="width"], [name="height"], [name="autoplay"], #upload_thumb').on('change', function(){
            setprior(2);
        })
    })

</script>

<style scoped="scoped">
    #thumb {
        max-width: 100%;
        padding-top: 20px;
    }
    #video-preview{
        margin-bottom: 20px;
    }
</style>

<input name="prior" id="prior" class="semi_hidden mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('prior', $params['id']) ?>"/>


<div class="mw-modules-tabs" >
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-video-settings">
                <div class="mw-accordion">
                    <div class="mw-accordion-item">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <div class="header-holder">
                                <i class="mai-setting2"></i>Embed Video
                            </div>
                        </div>
                        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Paste video URL or Embed Code"); ?></label>
                                <textarea name="embed_url" id="emebed_video_field" class="mw-ui-field mw_option_field w100" data-mod-name="<?php print $params['data-type'] ?>"><?php print (get_option('embed_url', $params['id'])) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mw-accordion-item">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <div class="header-holder">
                                <i class="mai-setting2"></i>Upload Video
                            </div>
                        </div>
                        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Upload Video from your computer"); ?></label>
                                <input name="upload" id="upload_field" class="mw-ui-field mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload', $params['id']) ?>"/>
                                <?php $uploadedVideo = get_option('upload', $params['id']);  ?>
                                <video
                                        style="display: <?php print !!$uploadedVideo ? 'block' : 'none' ?>;"
                                        <?php print 'src="'.$uploadedVideo.'"'; ?>
                                        width="100%"
                                        height="200px"
                                        id="video-preview"
                                        controls>

                                </video>



                                <span class="mw-ui-btn mw-ui-btn-info" id="upload_btn">
                                    <span class="fas fa-upload"></span> &nbsp;
                                    <?php _e("Choose video file"); ?>
                                </span>
                                <span
                                        class="mw-ui-btn"
                                        id="remove-video-button"
                                        style="<?php print !!$uploadedVideo ? '' : 'display:none;' ?>;"
                                        onclick="$('#video-preview').hide();$(this).hide(); $('#upload_field').val('').trigger('change')">
                                    <span class="mw-icon-bin"></span> &nbsp;
                                    <?php _e("Remove"); ?>
                                </span>
                            </div>

                            <div class="mw-ui-progress" id="upload_status" style="display: none">
                                <div style="width: 0%" class="mw-ui-progress-bar"></div>
                                <div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
                            </div>

                        </div>
                    </div>


                    <div class="mw-accordion-item">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <div class="header-holder">
                                <i class="mai-setting2"></i>Video Settings
                            </div>
                        </div>
                        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">

                            <div class="mw-ui-row-nodrop mw-ui-row-fixed" style="width: auto">
                                <div class="mw-ui-col">
                                    <div class="mw-ui-col-container">
                                        <div class="mw-ui-field-holder">
                                            <label class="mw-ui-inline-label"><?php _e("Width"); ?></label>
                                            <input name="width" style="width:50px;" placeholder="450" class="mw-ui-field mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('width', $params['id']) ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="mw-ui-col">
                                    <div class="mw-ui-col-container">
                                        <div class="mw-ui-field-holder">
                                            <label class="mw-ui-inline-label"><?php _e("Height"); ?></label>
                                            <input name="height" placeholder="350" style="width:50px;" class="mw-ui-field mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('height', $params['id']) ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-check">
                                    <input id="chk_autoplay" name="autoplay" class="mw-ui-field mw_option_field" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('autoplay', $params['id']) == 'y') { ?> checked='checked' <?php } ?>/>
                                    <span></span>
                                    <span><?php _e("Autoplay"); ?></span>
                                </label>
                            </div>

                            <hr />

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Upload Video Thumbnail from your computer"); ?>
                                    <br>
                                    <small><?php _e("Optional thumbnail image for use with uploaded or embedded videos. Required if Lazy Loading selected."); ?>
                                    </small>
                                </label>

                                <div class="row" style="margin-top:10px;">
                                    <div class="col-xs-6">
                                        <input name="upload_thumb" id="upload_thumb_field" class="mw-ui-field mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload_thumb', $params['id']) ?>"/>
                                        <span class="mw-ui-btn mw-ui-btn-info" id="upload_thumb_btn"><span class="fas fa-upload"></span> &nbsp; <?php _e("Choose thumbnail image"); ?></span>
                                    </div>
                                    <div class="col-xs-6">
                                        <img id="thumb" src="<?php print thumbnail(get_option('upload_thumb', $params['id']), 100, 100); ?>" alt=""/>
                                        <input id="upload_thumb" name="upload_thumb" class="mw-ui-field mw_option_field" type="hidden" data-mod-name="<?php print $params['data-type'] ?>" value=""/>
                                    </div>
                                </div>
                            </div>

                            <div class="mw-ui-progress" id="upload_thumb_status" style="display: none">
                                <div style="width: 0%" class="mw-ui-progress-bar"></div>
                                <div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
                            </div>

                            <hr />

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Video Lazy Loading for SEO"); ?>
                                    <br>
                                    <small><?php _e("Optional setting for use with embedded YouTube videos to defer the downloading of video scripts. Thumbnail image required, see Thumbnail Upload section."); ?>
                                    </small>
                                </label>
                                <div class="row" style="margin-top:10px;">
                                    <label class="mw-ui-check col-xs-6">
                                        <input id="chk_lazyload" name="lazyload" class="mw-ui-field mw_option_field" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('lazyload', $params['id']) == 'y') { ?> checked='checked' <?php } ?>/>
                                        <span></span>
                                        <span><?php _e("Lazy load"); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
