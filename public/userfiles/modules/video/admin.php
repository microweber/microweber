<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body px-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <?php if (!isset($params['live_edit'])): ?>

        <div class="card-header">
            <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
        </div>
    <?php endif; ?>

    <div class=" ">
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
            mw.require("uploader.js");

            setprior = function (v, t) {
                t = t || false;
                document.getElementById('prior').value = v;
                $(document.getElementById('prior')).trigger('change');
                if (!!t) {
                    setTimeout(function () {
                        $(t).trigger('change');
                    }, 70);
                }
            }

            $(document).ready(function () {
                var upVideo = mw.upload({
                    multiple: false,
                    filetypes: 'videos',
                    element: '#upload_btn'
                });
                var upThumb = mw.upload({
                    multiple: false,
                    filetypes: 'images',
                    element: '#upload_thumb_btn'
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
                    document.getElementById(uploadFieldId).value = b.src;
                    $("#video-preview").attr("src", b.src).show();
                    $("#remove-video-button").show();
                    $(document.getElementById(uploadFieldId)).trigger("change");
                });

                $(upThumb).on("FileUploaded", function (a, b) {
                    fileTypes = 'images';
                    uploadFieldId = 'upload_thumb_field';
                    uploadStatusId = 'upload_thumb_status';
                    uploadBtnId = 'upload_thumb_btn';

                    $("#thumb").attr("src", b.src);

                    $("#upload_thumb_field").val(b.src).trigger('change');

                    mw.tools.refresh_image(document.getElementById('thumb'));
                    mw.tools.refresh(document.getElementById('chk_autoplay'));
                });

                $(".js-remove-thumb").on('click', function () {
                    $("#upload_thumb_field").val('').trigger('change');
                    $("#thumb").removeAttr('src')
                    $('.js-video-thumb-holder').hide();
                })

                all.on("FileUploaded", function (a, b) {
                    mw.notification.success("<?php _ejs("File Uploaded"); ?>");

                    $(status).hide();
                });

                $(upThumb).on("progress", function (a, b) {
                    $("#upload_thumb_status").find('.progress-bar').width('0%');
                    $("#upload_thumb_status").show();
                    $("#upload_thumb_status").find('.progress-bar').width(b.percent + '%');
                    $(".js-video-thumb-holder").show();
                });

                $(upVideo).on("progress", function (a, b) {
                    $("#upload_progress").find('.progress-bar').width('0%');
                    $("#upload_progress").show();
                    $("#upload_progress").find('.progress-bar').width(b.percent + '%');
                });

                $(upVideo).on("done", function (a, b) {
                    $("#upload_progress").hide();
                    $(".js-video-preview-label").show();

                });

                $(upThumb).on("done", function (a, b) {
                    $("#upload_thumb_status").hide();

                });



                mw.$("#emebed_video_field").focus().on('change', function () {
                    setprior(1);
                });

                $('[name="width"], [name="height"], [name="autoplay"], #upload_thumb').on('change', function () {
                    setprior(2);
                })
            })
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#list"> <?php _e('Video'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <input name="prior" id="prior" class="semi_hidden mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('prior', $params['id']) ?>"/>


                <!-- Settings Content -->
                <div class="module-live-edit-settings module-video-settings">
                    <div class="form-group">
                        <label class="form-label font-weight-bold"><?php _e("Embed Video"); ?></label>
                        <small class="text-muted mb-2 d-block"><?php _e("Paste video URL or Embed Code"); ?></small>
                        <textarea name="embed_url" rows="5" id="emebed_video_field" class="form-control mw_option_field" data-mod-name="<?php print $params['data-type'] ?>"><?php print (get_option('embed_url', $params['id'])) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-bold"><?php _e("Upload Video"); ?></label>
                        <small class="text-muted mb-2 d-block"><?php _e("Upload Video from your computer. Allowed file format are .MP4 .WEBM"); ?></small>
                    </div>

                    <input name="upload" id="upload_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload', $params['id']) ?>"/>
                    <?php $uploadedVideo = get_option('upload', $params['id']); ?>

                    <div class="d-flex align-items-center justify-content-between">
                        <button type="button" class="btn btn-dark" id="upload_btn"> &nbsp;<?php _e("Choose video file"); ?></button>
                        <button type="button" class="btn btn-link text-danger px-0" id="remove-video-button" style="<?php print !!$uploadedVideo ? '' : 'display:none;' ?>;" onclick="$('#video-preview').hide(); $(this).hide(); $('.js-video-preview-label').hide(); $('#upload_field').val('').trigger('change')" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete"> <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg></button>
                    </div>

                    <div class="form-group mt-3 mb-2 js-video-preview-label" style="display: <?php print !!$uploadedVideo ? 'block' : 'none' ?>;">
                        <label class="form-label font-weight-bold"><?php _e("Preview"); ?></label>
                    </div>

                    <video style="display: <?php print !!$uploadedVideo ? 'block' : 'none' ?>;"<?php print 'src="' . $uploadedVideo . '"'; ?> width="100%" height="200px" id="video-preview" controls></video>

                    <div class="progress mt-2" id="upload_progress" style="display: none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-video-settings">
                    <div class="form-group">
                        <label class="form-label font-weight-bold"><?php _e('Video settings'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('Set a width height in pixels'); ?></small>
                    </div>

                    <div class="row px-0">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label font-weight-bold"><?php _e("Width"); ?></label>
                                <input name="width" placeholder="Ex. 450" class="form-control mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('width', $params['id']) ?>"/>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label font-weight-bold"><?php _e("Height"); ?></label>
                                <input name="height" placeholder="Ex. 350" class="form-control mw_option_field" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('height', $params['id']) ?>"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <small class="d-block text-muted mb-2"><?php _e('The video will start automaticly if you check the option'); ?></small>

                        <div class="custom-control custom-checkbox my-2">
                            <input id="chk_autoplay" name="autoplay" class="mw_option_field form-check-input" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('autoplay', $params['id']) == 'y') { ?>checked<?php } ?>/>
                            <label class="custom-control-label" for="chk_autoplay"><?php _e("Autoplay"); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label mb-0 font-weight-bold"><?php _e("Upload Video Thumbnail from your computer"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Optional thumbnail image for use with uploaded or embedded videos. Required if Lazy Loading selected"); ?></small>
                    </div>

                    <div class="row d-flex px-0">
                        <div class="col-auto">
                            <div class="mb-2">
                                <div class="dropable-zone small-zone square-zone  " id="upload_thumb_btn">
                                    <div class="holder">
                                        <div class="dropable-zone-img img-media mb-2"></div>
                                        <button type="button" class="btn btn-dark"><?php _e('Upload image thumbnail'); ?></button>
                                    </div>
                                </div>
                            </div>

                            <input name="upload_thumb" id="upload_thumb_field" class="form-control mw_option_field semi_hidden" type="text" data-mod-name="<?php print $params['data-type'] ?>" value="<?php print get_option('upload_thumb', $params['id']) ?>"/>
                        </div>

                        <div class="col-auto js-video-thumb-holder" <?php if(!get_option('upload_thumb', $params['id'])): ?>style="display:none;"<?php endif; ?>>
                            <div class="mb2">
                                <img id="thumb" src="<?php print thumbnail(get_option('upload_thumb', $params['id']), 120, 120); ?>" alt="" style="max-width: 120px;"/><br/>
                                <span class="btn btn-link text-danger px-0 js-remove-thumb"><?php _e('Remove'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="progress mt-2" id="upload_thumb_status" style="display: none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-bold"><?php _e("Video Lazy Loading for SEO"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Optional setting for use with embedded YouTube videos to defer the downloading of video scripts. Thumbnail image required, see Thumbnail Upload section"); ?></small>

                        <div class="custom-control custom-checkbox my-2">
                            <input id="chk_lazyload" name="lazyload" class="mw_option_field form-check-input" type="checkbox" data-mod-name="<?php print $params['data-type'] ?>" value="y" <?php if (get_option('lazyload', $params['id']) == 'y') { ?>checked<?php } ?>/>
                            <label class="custom-control-label" for="chk_lazyload"><?php _e("Lazy load"); ?></label>
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
