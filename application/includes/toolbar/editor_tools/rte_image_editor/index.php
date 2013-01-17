
<?php $path = INCLUDES_URL . "toolbar/editor_tools/rte_image_editor/"; ?>

<script type="text/javascript">

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");
     mw.require("url.js");

</script>
<script type="text/javascript">



    hash = window.location.hash.replace(/#/g, '');

    afterInput = function(url){   //what to do after image is uploaded (depending on the hash in the url)


      if(hash!==''){
        if(hash=='editimage'){
          parent.mw.image.currentResizing.attr("src", url);
          parent.mw.tools.modal.remove('mw_rte_image');
        }
        else if(hash=='set_bg_image'){
          parent.mw.wysiwyg.set_bg_image(url);
          parent.mw.tools.modal.remove('mw_rte_image');
        }
        else{
          parent.mw.exec(hash, url);
        }
      }
      else{ /*
        parent.mw.wysiwyg.restore_selection();
        parent.mw.wysiwyg.insert_image(url, true);      */
      }
    }


    $(document).ready(function(){

        mw.simpletabs(document.getElementById('image_tabs'));

        Progress =  mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;Done! All files have been uploaded.';

        mw.$(".mw-upload-filetypes li").each(function(){
          var frame = mw.files.uploader();
          frame.width = $(this).width();
          frame.height = $(this).height();
          $(frame).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressPercent.html(file.percent+'%');
              ProgressInfo.html(file.name+'%');
          });
          $(frame).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);
              afterInput(item.src);
          });
          $(this).append(frame);
        });

        var fu = mw.$('#mw_folder_upload');
        var frame = mw.files.uploader({name:'upload_file_link'});
        frame.className += ' mw_upload_frame';
        frame.width = fu.width();
        frame.height = fu.height();
        fu.append(frame);

        $(frame).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressPercent.html(file.percent+'%');
        });
        $(frame).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);
              afterInput(item.src);
        });

    });








</script>


<style type="text/css">

.mw-o-box-content{
  height: 150px;
}

.mw-upload-filetypes{
  list-style: none;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.mw-upload-filetypes li{
  margin: 0 30px;
  font-size:11px;
  display: inline-block;
  position: relative;
  cursor: pointer;
  width: 80px;
  text-align: center;
  opacity:.4;
}

.mw-upload-filetypes .mw-upload-frame{
  display: block;
  width: 80px;
  height: 66px;
  background: url(<?php print $path; ?>buttons.png) no-repeat;

}
.mw-upload-filetypes li:hover{ opacity:1; }

.mw-upload-filetypes li.mw-upload-filetype-video .mw-upload-frame{ background-position: -143px 0; }
.mw-upload-filetypes li.mw-upload-filetype-file .mw-upload-frame{ background-position: -273px 0; }

.mw-upload-filetypes li span{
  display: block;
  padding-top:10px;
}

.mw-upload-filetypes li iframe{
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
}
.mw_tabs_layout_simple .mw_simple_tabs_nav{
  padding-top: 0;
}

</style>

<div class="mw_simple_tabs mw_tabs_layout_simple" id="image_tabs">
  <ul class="mw_simple_tabs_nav">
    <li><a href="#">My Computer</a></li>
    <li><a href="#">Image URL</a></li>
    <li><a href="#">Search</a></li>
  </ul>
  <div class="mw_clear"></div>
  <div class="tab" id="drag_files_here">
    <center style="padding-top: 25px;">


        <ul class="mw-upload-filetypes" id="">
            <li class="mw-upload-filetype-image">
                <div class="mw-upload-frame"></div>
                <span>Image</span>
            </li>
            <li class="mw-upload-filetype-video">
                <div class="mw-upload-frame"></div>
                <span>Video</span></li>
            <li class="mw-upload-filetype-file">
                <div class="mw-upload-frame"></div>
                <span>Files</span>
            </li>
        </ul>



      <div class="drag_files_label" style="display: none;">Drag your files here</div>
    </center>
  </div>
  <div class="tab" id="get_image_from_url">
    <h3>Enter the URL of an image somewhere on the web</h3>
    <span class="relative left">
    <input type="text" id="get_image_by_url" class="mw-ui-field" name="get_image_by_url" />
    <span id="image_status"></span> </span>
    <button type="button" class="mw-ui-btn-action" id="btn_inser_url_image">Insert</button>
    <p id="image_types_desc"> File must be a JPEG, GIF, PNG , BMP or TIFF <br />
      Example: http://mywebsite.com/image.jpg </p>
  </div>
  <div class="tab">

    <? exec_action('rte_image_editor_image_search'); ?>

  </div>

</div>

<div class="vSpace"></div>


<div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%">
    <div class="mw-ui-progress-bar" style="width: 0%;"></div>
    <div class="mw-ui-progress-info"></div>
    <span class="mw-ui-progress-percent"></span>
</div>
