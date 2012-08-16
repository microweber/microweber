
<script type="text/javascript">



    $(document).ready(function(){
        mw.simpletabs(document.getElementById('image_tabs'));


    Uploader = mw.files.browser({

    });



    mw.filechange(Uploader, function(){
        var is_valid = this.validate();
        if(is_valid){
            mw.files.upload(Uploader, {}, function(){
                console.log('file is uploaded - ' + this);
            }, function(){
                console.log('all files are uploaded - ' + this);
            });
         }
         else{
           alert("Only: " + Uploader.accepts + " are supported!");
         }

    });


    mw.files.browser_connector("#rte_image_upload", Uploader);


  });
</script>

<div class="mw_simple_tabs rte_tabs" id="image_tabs">
    <ul class="mw_simple_tabs_nav">
        <li><a href="#">My Computer</a></li>
        <li><a href="#">Image URL</a></li>
        <li><a href="#">Search</a></li>
    </ul>
    <div class="mw_clear"></div>
    <div class="tab">

        <center style="padding-top: 100px;">
            <span class="bluebtn" id="rte_image_upload">Upload image from my compyter</span>
            <i style="opacity:.5"><br /><br /><br />Drag your files here</i>
        </center>





    </div>



    <div class="tab">Enter the URL of an image somewhere on the web</div>
    <div class="tab">Search for Images</div>
</div>