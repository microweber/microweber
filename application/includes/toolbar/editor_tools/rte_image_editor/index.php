
<script type="text/javascript">



    $(document).ready(function(){
        mw.simpletabs(document.getElementById('image_tabs'));


    Uploader = mw.files.browser({
      open:false
    });



    mw.filechange(Uploader, function(){

         this.validate(function(){
            mw.files.upload(Uploader, {url:'http://pecata/Microweber/editor_tools/rte_image_editor/'}, function(){
                console.log(this);
            });
         });

    });

    $("#rte_image_upload").click(function(){
       $(Uploader).click();
    });
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
        </center>


    </div>
    <div class="tab">Enter the URL of an image somewhere on the web</div>
    <div class="tab">Search for Images</div>
</div>