
<script type="text/javascript">

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");



    $(document).ready(function(){

         mw.simpletabs(document.getElementById('image_tabs'));

         Uploader = mw.files.browser();

         mw.filechange(Uploader, function(){
        var is_valid = this.validate();
        if(is_valid){
            mw.files.upload(Uploader, {filetypes:'png,jpg'}, function(){
                console.log('file is uploaded - ' + this);
            }, function(){
                console.log('all files are uploaded - ' + this);
            });
         }
         else{
           alert("Only: " + Uploader.filetypes + " are supported!");
         }
    });



    mw.files.browser_connector("#rte_image_upload", Uploader);



    mw.files.drag_from_pc({
      selector:"#drag_files_here",
      filetypes:'jpg,png',
      filesadded:function(){
         console.log(this); // this is "object FileList" of added files
      },
      fileuploaded:function(){
        console.log(this); //this is json object returned from server
      },
      done:function(){
        console.log(this); //this is json object returned from server with all the files that are uploaded
      },
      skip:function(){
        console.log(this); // this is "object File" of the skipped file
      }


    });


   //tab get image by url







  });
</script>

<div class="mw_simple_tabs rte_tabs" id="image_tabs">
    <ul class="mw_simple_tabs_nav">
        <li><a href="#">My Computer</a></li>
        <li><a href="#">Image URL</a></li>
        <li><a href="#">Search</a></li>
    </ul>
    <div class="mw_clear"></div>
    <div class="tab" id="drag_files_here">

        <center style="padding-top: 100px;">
            <span class="bluebtn" id="rte_image_upload">Upload image from my computer</span>
            <div class="drag_files_label">Drag your files here</div>
        </center>





    </div>



    <div class="tab" id="get_image_from_url">

        <h3>Enter the URL of an image somewhere on the web</h3>

        <form method="post" action="?" id="form_get_image_from_url">
          <span class="fancy_input left">
              <input type="text" id="get_image_by_url" name="get_image_by_url" />
          </span>
          <input type="submit" class="bluebtn" value="Insert" />
        </form>

        <p id="image_types_desc">
          File must be a JPEG, GIF, PNG , BMP or TIFF <br />
          Example: http://mywebsite.com/image.jpg
        </p>

    </div>




    <div class="tab">Search for Images</div>
</div>


















