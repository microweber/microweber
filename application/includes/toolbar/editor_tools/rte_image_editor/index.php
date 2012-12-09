<script type="text/javascript">

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");

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

        Uploader = mw.files.browser();

        mw.filechange(Uploader, function(){
        var is_valid = this.validate();
        if(is_valid){
            mw.files.upload(Uploader, {}, function(){
              afterInput(this.src);
            }, function(){
                mw.log('all files are uploaded - ' + this);
            });
         }
         else{
           alert("Only: " + Uploader.filetypes + " are supported!");
         }
    });



    mw.files.browser_connector("#rte_image_upload", Uploader);



    mw.files.drag_from_pc({
      selector:"#drag_files_here",
      filetypes:'jpg,png,gif',
      filesadded:function(){
         mw.log(this); // this is "object FileList" of added files
      },
      fileuploaded:function(){
        afterInput(this.src);
      },
      done:function(){
        mw.log(this); //this is json object returned from server with all the files that are uploaded
      },
      skip:function(){
        mw.log(this); // this is "object File" of the skipped file
      }


    });


   //tab get image by url

     var img_status = document.getElementById('image_status');

     $("#get_image_by_url").bind("keyup change", function(){
            img_status.className = 'loading';
        mw.files.image_url_test(this.value, function(){
          //is valid
            img_status.className = 'valid';

        }, function(){
          //not valid
          img_status.className = 'error';

        });
     });

     $("#btn_inser_url_image").click(function(){
          if(img_status.className == 'valid'){
            var val =  $("#get_image_by_url").val();
            afterInput(val);
          }
     });



  });
</script>

<div class="mw_simple_tabs mw_tabs_layout_simple" id="image_tabs">
  <ul class="mw_simple_tabs_nav">
    <li><a href="#">My Computer</a></li>
    <li><a href="#">Image URL</a></li>
    <li><a href="#">Search</a></li>
  </ul>
  <div class="mw_clear"></div>
  <div class="tab" id="drag_files_here">
    <center style="padding-top: 100px;">
      <span class="mw-ui-btn-action" id="rte_image_upload">Upload image from my computer</span>
      <div class="drag_files_label">Drag your files here</div>
    </center>
  </div>
  <div class="tab" id="get_image_from_url">
    <h3>Enter the URL of an image somewhere on the web</h3>
    <span class="fancy_input left">
    <input type="text" id="get_image_by_url" name="get_image_by_url" />
    <span id="image_status"></span> </span>
    <button type="button" class="mw-ui-btn-action" id="btn_inser_url_image">Insert</button>
    <p id="image_types_desc"> File must be a JPEG, GIF, PNG , BMP or TIFF <br />
      Example: http://mywebsite.com/image.jpg </p>
  </div>
  <div class="tab">
    <? exec_action('rte_image_editor_image_search'); ?>
  </div>
</div>
