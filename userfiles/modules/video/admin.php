<input
    name="prior"
    id="prior"
    class="semi_hidden mw_option_field"
    type="text"
    data-mod-name="<?php print $params['data-type'] ?>"
    value="<?php print get_option('prior', $params['id']) ?>"
    />


<style>

    .mw-ui-label-inline {
        width: 60px;
    }

</style>


<div class="mw_simple_tabs mw_tabs_layout_simple">
    <ul style="margin: 0;" class="mw_simple_tabs_nav">
        <li><a class="active" href="javascript:;"><?php _e("Embed Video"); ?></a></li>
        <li><a href="javascript:;"><?php _e("Upload Video"); ?></a></li>
        <li><a href="javascript:;"><?php _e("Settings"); ?></a></li>
    </ul>
    <div class="tab">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Paste video URL or Embed Code"); ?></label>

            <div class="mw-ui-field mw-ico-field" style="width:370px;">
                <span class="ico iplay"></span>
                <input
                    name="embed_url"
                    id="emebed_video_field"
                    style="width: 340px;"
                    class="mw-ui-invisible-field mw_option_field"

                    type="text"
                    data-mod-name="<?php print $params['data-type'] ?>"
                    value="<?php print htmlentities(get_option('embed_url', $params['id'])) ?>"
                    />
            </div>
         </div>
    </div>
    <div class="tab semi_hidden">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Upload Video from your computer"); ?></label>
            <input onchange="setprior(2);" name="upload" id="upload_field" class="mw-ui-field mw_option_field left"
                   type="text" data-mod-name="<?php print $params['data-type'] ?>"
                   value="<?php print get_option('upload', $params['id']) ?>" style="width:270px;"/>
            <span class="mw-ui-btn left" id="upload_btn"
                  style="width: 60px;margin-left:-1px;"><?php _e("Browse"); ?></span>
        </div>

        <div class="mw_clear"></div>
        <div class="vSpace"></div>

        <div class="mw-ui-progress" id="upload_status" style="display: none">
            <div style="width: 0%" class="mw-ui-progress-bar"></div>
            <div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
        </div>
    </div>
    <div class="tab">
        <label class="mw-ui-label">
            <small><?php _e("Options for your video. Not available for embed codes"); ?>.</small>
        </label>

        <hr>


        <div class="mw-ui-field-holder">
            <label class="mw-ui-label-inline"><?php _e("Width"); ?></label>
            <input
                name="width"
                style="width:50px;"
                placeholder="450"
                class="mw-ui-field mw_option_field"
                type="text" data-mod-name="<?php print $params['data-type'] ?>"
                value="<?php print get_option('width', $params['id']) ?>"
                />
        </div>
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label-inline"><?php _e("Height"); ?></label>
            <input
                name="height"
                placeholder="350"
                style="width:50px;"
                class="mw-ui-field mw_option_field"
                type="text" data-mod-name="<?php print $params['data-type'] ?>"
                value="<?php print get_option('height', $params['id']) ?>"
                />

        </div>

        <div class="mw-ui-field-holder">
            <label class="mw-ui-label-inline"><?php _e("Autoplay"); ?></label>
            <label class="mw-ui-check">
                <input
                    name="autoplay"
                    class="mw-ui-field mw_option_field"
                    type="checkbox" data-mod-name="<?php print $params['data-type'] ?>"
                    value="y"
                    <?php if (get_option('autoplay', $params['id']) == 'y') { ?> checked='checked' <?php }?>
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
