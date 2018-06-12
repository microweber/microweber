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
    .tab{
      display: none;
    }

    #thumb{
        max-width: 100%;
        padding-top: 20px;
    }

</style>
<script>
$(mwd).ready(function(){
  mw.tabs({
    nav:'.mw-ui-btn-nav-tabs a',
    tabs:'.tab'
  })
})
</script>


<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
	<a class="mw-ui-btn active" href="javascript:;"><?php _e("Embed Video"); ?></a>
	<a class="mw-ui-btn" href="javascript:;"><?php _e("Upload Video"); ?></a>
	<a class="mw-ui-btn" href="javascript:;"><?php _e("Upload Thumbnail"); ?></a>
	<a class="mw-ui-btn" href="javascript:;"><?php _e("Settings"); ?></a>
</div>
<div class="mw-ui-box mw-ui-box-content">    <div class="tab" style="display: block">
			<div class="mw-ui-field-holder">
				<label class="mw-ui-label"><?php _e("Paste video URL or Embed Code"); ?></label>
				<textarea  name="embed_url"
				id="emebed_video_field"
				class="mw-ui-field mw_option_field w100"  data-mod-name="<?php print $params['data-type'] ?>"><?php print (get_option('embed_url', $params['id'])) ?></textarea>
			</div>
	</div>


	<div class="tab">
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e("Upload Video from your computer"); ?></label>
			<input onchange="setprior(2);" name="upload" id="upload_field" class="mw-ui-field mw_option_field semi_hidden"
				   type="text" data-mod-name="<?php print $params['data-type'] ?>"
				   value="<?php print get_option('upload', $params['id']) ?>" />
			<span class="mw-ui-btn" id="upload_btn"><span class="mw-icon-upload"></span><?php _e("Browse"); ?></span>
		</div>
		<div class="mw-ui-progress" id="upload_status" style="display: none">
			<div style="width: 0%" class="mw-ui-progress-bar"></div>
			<div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
		</div>
	</div>


	<div class="tab">
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e("Upload Video Thumbnail from your computer"); ?><br><small><?php _e("Optional setting to reduce page size for YouTube videos"); ?></small></label>
			<div class="row" style="margin-top:10px;">
				<div class="col-xs-6">
					<input name="upload_thumb" id="upload_thumb_field" class="mw-ui-field mw_option_field semi_hidden"
						   type="text" data-mod-name="<?php print $params['data-type'] ?>"
						   value="<?php print get_option('upload_thumb', $params['id']) ?>" />
					<span class="mw-ui-btn" id="upload_thumb_btn"><span class="mw-icon-upload"></span><?php _e("Browse"); ?></span>
				</div>
				<div class="col-xs-6">
					<img id="thumb" src="<?php print thumbnail(get_option('upload_thumb', $params['id']), 100, 100);?>" alt=""/>
					<input id="upload_thumb"
						   name="upload_thumb" class="mw-ui-field mw_option_field"
						   type="hidden" data-mod-name="<?php print $params['data-type'] ?>"
						   value="" />
				</div>
			</div>
		</div>
		<div class="mw-ui-progress" id="upload_thumb_status" style="display: none">
			<div style="width: 0%" class="mw-ui-progress-bar"></div>
			<div class="mw-ui-progress-info"><?php _e("Status"); ?>: <span class="mw-ui-progress-percent">0</span></div>
		</div>
	</div>


    <div class="tab">

        <?php _e("Options for your video. Not available for embed codes"); ?>.

        <hr>

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
                    id="chk_autoplay"
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
            mw.notification.warning("<?php _e("Unsupported format"); ?>.")
        });




        $(upVideo).on("FileUploaded", function (a, b) {
            uploadFieldId = 'upload_field';
            uploadStatusId = 'upload_status';
            uploadBtnId = 'upload_btn';
            mwd.getElementById(uploadFieldId).value = b.src;
            $(mwd.getElementById(uploadFieldId)).trigger("change");

                setprior(2);


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
            mw.notification.success("<?php _e("File Uploaded"); ?>");

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

        mw.$("#emebed_video_field").focus();
    })

</script>
</div>