
<input
    name="prior"
    id="prior"
    class="semi_hidden mw_option_field"
    type="text"
    data-reload="<? print $params['data-type'] ?>"
    value="<?php print get_option('prior', $params['id']) ?>"
  />


<style>

.mw-ui-label-inline{
  width: 60px;
}

</style>


<div class="mw_simple_tabs mw_tabs_layout_simple">
<ul style="margin: 0;" class="mw_simple_tabs_nav">
  <li><a class="active" href="javascript:;">Embed Video</a></li>
  <li><a href="javascript:;">Upload Video</a></li>
  <li><a href="javascript:;">Settings</a></li>
</ul>
  <div class="tab">
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Paste video URL or Embed Code</label>
        <div class="mw-ui-field mw-ico-field" style="width:370px;">
            <span class="ico iplay"></span>
            <input
                  name="embed_url"
                  style="width: 340px;"
                   class="mw-ui-invisible-field mw_option_field"
                  onchange="setprior(1);"
                  onpaste="setprior(1);"
                  type="text"
                  data-reload="<? print $params['data-type'] ?>"
                  value="<?php print get_option('embed_url', $params['id']) ?>"
            />
        </div>

      </div>
  </div>
  <div class="tab semi_hidden">
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Upload Video from your computer</label>
        <input  onchange="setprior(2);" name="upload" id="upload_field" class="mw-ui-field mw_option_field left"  type="text" data-reload="<? print $params['data-type'] ?>" value="<?php print get_option('upload', $params['id']) ?>" style="width:270px;" />
        <span class="mw-ui-btn right" id="upload_btn" style="width: 60px;">Browse</span>
      </div>

      <div class="mw_clear"></div>
      <div class="vSpace"></div>

      <div class="mw-ui-progress" id="upload_status" style="display: none">
            <div style="width: 0%" class="mw-ui-progress-bar"></div>
            <div class="mw-ui-progress-info">Status: <span class="mw-ui-progress-percent">0</span></div>
      </div>
  </div>
  <div class="tab">
   <label class="mw-ui-label"><small>Options for your video. Not available for embed codes.</small></label>

   <hr>


   <div class="mw-ui-field-holder">
       <label class="mw-ui-label-inline">Width</label>
       <input
            name="width"
            style="width:50px;"
            placeholder="450"
            class="mw-ui-field mw_option_field"
            type="text" data-reload="<? print $params['data-type'] ?>"
            value="<?php print get_option('width', $params['id']) ?>"
       />
   </div>
   <div class="mw-ui-field-holder">
       <label class="mw-ui-label-inline">Height</label>
       <input
            name="height"
            placeholder="350"
            style="width:50px;"
            class="mw-ui-field mw_option_field"
            type="text" data-reload="<? print $params['data-type'] ?>"
            value="<?php print get_option('height', $params['id']) ?>"
        />

    </div>

    <div class="mw-ui-field-holder">
       <label class="mw-ui-label-inline">Autoplay</label>
       <label class="mw-ui-check">
       <input
            name="autoplay"
            class="mw-ui-field mw_option_field"
            type="checkbox" data-reload="<? print $params['data-type'] ?>"
            value="y"
            <?php if(get_option('autoplay', $params['id']) == 'y'){ ?> checked='checked' <?php }?>
        /><span></span></label>

    </div>



  </div>
</div>

<script>
    mw.require("files.js");
</script>
<script>


setprior = function(v){
      mwd.getElementById('prior').value = v;
      $(mwd.getElementById('prior')).trigger('change');
}

$(document).ready(function(){

    var up = mw.files.uploader({
        multiple:false,
        filetypes:'videos'
    });

    $(up).bind("error", function(){
      mw.notification.warning("Unsupported format.")
    });

    $(up).bind("FileUploaded", function(a,b){
      mw.notification.success("File Uploaded");
      mwd.getElementById('upload_field').value = b.src;
      $(mwd.getElementById('upload_field')).trigger("change");
      setprior(2);
      $(status).hide();
    });

    var status = mwd.getElementById('upload_status');

    $(up).bind("progress", function(a,b){
        $(status).show();
        status.querySelector('.mw-ui-progress-bar').style.width = b.percent + '%';
        status.querySelector('.mw-ui-progress-percent').innerHTML = b.percent + '%';
    });


    var btn = mwd.getElementById('upload_btn');

    $(btn).append(up);


})

</script>
