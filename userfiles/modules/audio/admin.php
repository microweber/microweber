<div class="module-live-edit-settings">
<style scoped="scoped">

.tab{
  display: none;
}

</style>
  <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
    <a href="javascript:;" class="mw-ui-btn active"><?php _e("Upload"); ?></a>
    <a href="javascript:;" class="mw-ui-btn"><?php _e("URL"); ?></a>
  </div>

  <div class="mw-ui-box mw-ui-box-content">



  <div class="tab" style="display: block">


        <input
                type="hidden"
                name="data-audio-upload"
                value="<?php print get_option('data-audio-upload', $params['id']) ?>"
                class="mw_option_field"
                id="upload_value" />
        <span class="mw-ui-btn relative" id="upload"><span class="mw-icon-upload"></span><?php _e("Upload Track"); ?></span>


    




<div id="progress"></div>



  </div>
  <div class="tab">
         <label class="mw-ui-label"><?php _e("Paste URL"); ?></label>

         <input
               name="data-audio-url"
               class="mw-ui-field w100 mw_option_field"
               id="audio"
               type="text"
               value="<?php print get_option('data-audio-url', $params['id']) ?>" />

  </div>

  <input type="text" class="semi_hidden mw_option_field" name="prior" id="prior" value="<?php print get_option('prior', $params['id']) ?>"   />



</div>
</div>


<script>mw.require("files.js");</script>
<script>

    uploader = mw.files.uploader({
      multiple:false,
      filetypes:'audio'
    });




    mwd.getElementById('upload').appendChild(uploader);
    Prior = mwd.getElementById('prior');



    $(document).ready(function(){

        mw.$("#audio").keyup(function(){
          if(Prior.value != '2'){
              Prior.value = '2';
              $(Prior).trigger('change');
          }


        });


        Progress =  mw.progress({
          element:'#progress',
          action:'<?php _e("Uploading"); ?>'
        });
       Progress.hide();



    $(frame).bind("FilesAdded", function(frame, files_array, runtime){
              if(runtime == 'html4'){



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
              Progress.set(0)
              Progress.hide();
              mw.notification.error('<?php _e("Invalid filetype"); ?>');
         });

          $(uploader).bind("done", function(frame, item){
              Progress.set(0)
              Progress.hide();
           });

           $(uploader).bind("progress", function(frame, file){
              Progress.show()
              Progress.set(file.percent)
          });


          mw.tabs({
            nav:'.mw-ui-btn-nav-tabs a',
            tabs:'.tab'
          });

    });



</script>


