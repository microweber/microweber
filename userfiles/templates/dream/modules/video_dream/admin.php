<input
        name="prior"
        id="prior"
        class="semi_hidden mw_option_field"
        type="text"
        data-mod-name="<?php print $params['data-type'] ?>"
        value="<?php print get_option('prior', $params['id']) ?>"
/>

<div class="mw-ui-box-content">
    <style scoped="scoped">
        .tab {
            display: none;
        }

    </style>
    <script>
        $(mwd).ready(function () {
            mw.tabs({
                nav: '.mw-ui-btn-nav-tabs a',
                tabs: '.tab'
            })
        })

    </script>


    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a class="mw-ui-btn active" href="javascript:;"><?php _e("Embed Video"); ?></a>
        <a class="mw-ui-btn" href="javascript:;"><?php _e("Settings"); ?></a>
    </div>
    <div class="mw-ui-box mw-ui-box-content">
        <div class="tab" style="display: block">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Paste YouTube video Code"); ?></label>

                <textarea name="embed_url"
                          id="emebed_video_field"
                          class="mw-ui-field mw_option_field w100" data-mod-name="<?php print $params['data-type'] ?>"><?php print (get_option('embed_url', $params['id'])) ?></textarea>


            </div>
        </div>

        <div class="tab">

            <div class="mw-ui-row-nodrop mw-ui-row-fixed" style="width: auto">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-inline-label"><?php _e("Width"); ?></label>
                            <input
                                    name="width"
                                    style="width:50px;"
                                    placeholder="450"
                                    class="mw-ui-field mw_option_field"
                                    type="text" data-mod-name="<?php print $params['data-type'] ?>"
                                    value="<?php print get_option('width', $params['id']) ?>"
                            />
                        </div>
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-inline-label"><?php _e("Height"); ?></label>
                            <input
                                    name="height"
                                    placeholder="350"
                                    style="width:50px;"
                                    class="mw-ui-field mw_option_field"
                                    type="text" data-mod-name="<?php print $params['data-type'] ?>"
                                    value="<?php print get_option('height', $params['id']) ?>"
                            />

                        </div>
                    </div>
                </div>
            </div>


            <div class="mw-ui-field-holder">
                <label class="mw-ui-inline-label"><?php _e("Autoplay"); ?></label>
                <label class="mw-ui-check">
                    <input
                            name="autoplay"
                            class="mw-ui-field mw_option_field"
                            type="checkbox" data-mod-name="<?php print $params['data-type'] ?>"
                            value="y"
                        <?php if (get_option('autoplay', $params['id']) == 'y') { ?> checked='checked' <?php } ?>
                    /><span></span></label>

            </div>


        </div>
    </div>


    <script>
        mw.require("files.js");
    </script>
    <script>


        setprior = function (v, t) {
            var t = t || false;
            mwd.getElementById('prior').value = v;
            $(mwd.getElementById('prior')).trigger('change');
            if (!!t) {
                setTimeout(function () {
                    $(t).trigger('change');
                }, 70);
            }
        }

        $(document).ready(function () {

            var up = mw.files.uploader({
                multiple: false,
                filetypes: 'videos'
            });

            $(up).bind("error", function () {
                mw.notification.warning("<?php _e("Unsupported format"); ?>.")
            });

            $(up).bind("FileUploaded", function (a, b) {
                mw.notification.success("<?php _e("File Uploaded"); ?>");
                mwd.getElementById('upload_field').value = b.src;
                $(mwd.getElementById('upload_field')).trigger("change");
                setprior(2);
                $(status).hide();
            });

            var status = mwd.getElementById('upload_status');

            $(up).bind("progress", function (a, b) {
                $(status).show();
                status.querySelector('.mw-ui-progress-bar').style.width = b.percent + '%';
                status.querySelector('.mw-ui-progress-percent').innerHTML = b.percent + '%';
            });


            var btn = mwd.getElementById('upload_btn');

            $(btn).append(up);

            mw.$("#emebed_video_field").focus();
        })

    </script>
</div>