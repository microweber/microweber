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
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
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

                $('[name="width"], [name="height"], [name="autoplay"], #upload_thumb').on('change', function () {
                    setprior(2);
                })
            })
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> List of Testimonials</a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <input name="prior" id="prior" class="semi_hidden mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('prior', $params['id']) ?>"/>


                <!-- Settings Content -->
                <div class="module-live-edit-settings module-video-settings">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Embed Video"); ?></label>
                        <small class="text-muted mb-2 d-block"><?php _e("Paste video URL or Embed Code"); ?></small>
                        <textarea name="embed_url" rows="8" id="emebed_video_field" class="form-control mw_option_field" data-mod-name="<?php print $params['data-type'] ?>"><?php print (get_option('embed_url', $params['id'])) ?></textarea>
                    </div>

                    <hr class="thin"/>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Upload Video"); ?></label>
                        <small class="text-muted mb-2 d-block"><?php _e("Upload Video from your computer. Allowed file format are .MP4 .WEBM"); ?></small>
                    </div>

                    <input name="upload" id="upload_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload', $params['id']) ?>"/>
                    <?php $uploadedVideo = get_option('upload', $params['id']); ?>

                    <div class="d-flex align-items-center justify-content-between">
                        <button type="button" class="btn btn-primary btn-rounded" id="upload_btn"><i class="mdi mdi-upload"></i> &nbsp;<?php _e("Choose video file"); ?></button>
                        <button type="button" class="btn btn-link text-danger px-0" id="remove-video-button" style="<?php print !!$uploadedVideo ? '' : 'display:none;' ?>;" onclick="$('#video-preview').hide(); $(this).hide(); $('.js-video-preview-label').hide(); $('#upload_field').val('').trigger('change')"><?php _e("Remove"); ?> Video</button>
                    </div>

                    <div class="form-group mt-3 js-video-preview-label" style="display: <?php print !!$uploadedVideo ? 'block' : 'none' ?>;">
                        <label class="control-label"><?php _e("Preview"); ?></label>
                    </div>

                    <video style="display: <?php print !!$uploadedVideo ? 'block' : 'none' ?>;"<?php print 'src="' . $uploadedVideo . '"'; ?> width="100%" height="200px" id="video-preview" controls></video>

                    <div class="mw-ui-progress" id="upload_status" style="display: none">
                        <div style="width: 0%" class="mw-ui-progress-bar"></div>
                        <div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
                    </div>

                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-video-settings">
                    <div class="mw-ui-row-nodrop mw-ui-row-fixed" style="width: auto">
                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <div class="form-group">
                                    <label class="mw-ui-inline-label"><?php _e("Width"); ?></label>
                                    <input name="width" style="width:50px;" placeholder="450" class="form-control mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('width', $params['id']) ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <div class="form-group">
                                    <label class="mw-ui-inline-label"><?php _e("Height"); ?></label>
                                    <input name="height" placeholder="350" style="width:50px;" class="form-control mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('height', $params['id']) ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="mw-ui-check">
                            <input id="chk_autoplay" name="autoplay" class="form-control mw_option_field" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('autoplay', $params['id']) == 'y') { ?> checked='checked' <?php } ?>/>
                            <span></span>
                            <span><?php _e("Autoplay"); ?></span>
                        </label>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Upload Video Thumbnail from your computer"); ?>
                            <br>
                            <small><?php _e("Optional thumbnail image for use with uploaded or embedded videos. Required if Lazy Loading selected."); ?>
                            </small>
                        </label>

                        <div class="row" style="margin-top:10px;">
                            <div class="col-xs-6">
                                <input name="upload_thumb" id="upload_thumb_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload_thumb', $params['id']) ?>"/>
                                <span class="mw-ui-btn mw-ui-btn-info" id="upload_thumb_btn"><span class="fas fa-upload"></span> &nbsp; <?php _e("Choose thumbnail image"); ?></span>
                            </div>
                            <div class="col-xs-6">
                                <img id="thumb" src="<?php print thumbnail(get_option('upload_thumb', $params['id']), 100, 100); ?>" alt=""/>
                                <input id="upload_thumb" name="upload_thumb" class="form-control mw_option_field" type="hidden" data-mod-name="<?php print $params['data-type'] ?>" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-progress" id="upload_thumb_status" style="display: none">
                        <div style="width: 0%" class="mw-ui-progress-bar"></div>
                        <div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Video Lazy Loading for SEO"); ?>
                            <br>
                            <small><?php _e("Optional setting for use with embedded YouTube videos to defer the downloading of video scripts. Thumbnail image required, see Thumbnail Upload section."); ?>
                            </small>
                        </label>
                        <div class="row" style="margin-top:10px;">
                            <label class="mw-ui-check col-xs-6">
                                <input id="chk_lazyload" name="lazyload" class="form-control mw_option_field" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('lazyload', $params['id']) == 'y') { ?> checked='checked' <?php } ?>/>
                                <span></span>
                                <span><?php _e("Lazy load"); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
