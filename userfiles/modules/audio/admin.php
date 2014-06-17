
<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav" style="margin: 0;">
    <li><a href="javascript:;" class="active"><?php _e("Upload"); ?></a></li>
    <li><a href="javascript:;"><?php _e("URL"); ?></a></li>
  </ul>
  <div class="tab">

    <label class="mw-ui-label"><?php _e("Upload Track"); ?></label>
    <div class="relative inline-block" id="upload_holder">
        <input
                type="text"
                name="data-audio-upload"
                value="<?php print get_option('data-audio-upload', $params['id']) ?>"
                class="mw-ui-field mw_option_field left"
                style="margin: 0 -1px 0 0;width: 295px;"
                id="upload_value" />
        <span class="mw-ui-btn relative" id="upload"><?php _e("Upload"); ?></span>
    </div>

    


<div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%">
    <div class="mw-ui-progress-bar" style="width: 0%;"></div>
    <div class="mw-ui-progress-info"></div>
    <span class="mw-ui-progress-percent"></span>
</div>



  </div>
  <div class="tab semi_hidden">
         <label class="mw-ui-label"><?php _e("Paste URL"); ?></label>

         <input
               name="data-audio-url"
               class="mw-ui-field mw_option_field"
               style="width: 365px;"
               id="audio"
               type="text"
               value="<?php print get_option('data-audio-url', $params['id']) ?>" />

  </div>

  <input type="text" class="semi_hidden mw_option_field" name="prior" id="prior" value="<?php print get_option('prior', $params['id']) ?>"   />

</div>




<script>mw.require("files.js");</script>
<script>

    uploader = mw.files.uploader({
      multiple:false,
      filetypes:'audio'
    });




    mwd.getElementById('upload_holder').appendChild(uploader);
    Prior = mwd.getElementById('prior');



    $(document).ready(function(){

        mw.$("#audio").keyup(function(){
          if(Prior.value != '2'){
              Prior.value = '2';
              $(Prior).trigger('change');
          }


        });


        Progress =  mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;<?php _e("Done"); ?>! <?php _e("The File has been uploaded"); ?>.';
        ProgressErrorHTML = function(filename){return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;<?php _e("Error"); ?>! "'+filename+'" - <?php _e("Invalid filetype"); ?>.';}


    $(frame).bind("FilesAdded", function(frame, files_array, runtime){
              if(runtime == 'html4'){
                ProgressInfo.html('<?php _e("Uploading"); ?> - "' + files_array[0].name+'" ...');
              }
          });

       $(uploader).bind("FileUploaded", function(frame, item){
            mw.$("#upload_value").val(item.src);



            if(Prior.value != '1'){
              Prior.value = '1';
              $(Prior).trigger('change');
            }
            mw.$("#upload_value").trigger("change");

       });

         $(uploader).bind("error", function(frame, file){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressErrorHTML(file.name));
         });

          $(uploader).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);
           });

           $(uploader).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressPercent.html(file.percent+'%');
              ProgressInfo.html(file.name);
          });
    })



</script>


