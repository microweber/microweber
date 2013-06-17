<?php

/*

  type: layout
  content_type: static
  name: Mobile
  description: Mobile

*/

?>


<?php


if(array_key_exists('post', $_GET)){     ?>






<?php } else {   ?>

<?php include THIS_TEMPLATE_DIR. "head.php"; ?>
<div id="content" style="padding-top: 120px;">
  <div class="container">
  <h4>Add Content</h4>
  <hr>
  <label>Select content type</label>
    <div class="btn-group" data-toggle="buttons-radio">
      <button type="button" class="btn" name="page">Page</button>
      <button type="button" class="btn" name="product">Product</button>
      <button type="button" class="btn" name="post">Post</button>
      <button type="button" class="btn" name="category">Category</button>
    </div>
    <br><br>
  <input type="text" name="title" placeholder="Title" id="cctitle" style="width: 97%;" />
     <hr>

    <textarea name="content" style="width: 97%;height: 100px;"  placeholder="Content" id="cccontent"></textarea>

    <div id="mobup" class="btn" style="position: relative">Add Picture</div>

                                      <br>
                                      <br>

                                      <input type="hidden" name="thumbnail" id="thumbnail" />

    <span class="btn btn-info pull-right" onclick="___post()">Save</span>

  </div>
</div>


<script>
mw.require("files.js");
</script>
<script>


 var uploader = mw.files.uploader({
    filetypes:"images",
    name:'mobuploader'
 });

 $(document).ready(function(){

 $("#mobup").append(uploader);

 $(uploader).bind("FileUploaded", function(a,b){
     $("#thumbnail").val(b.src);
   /*  alert(JSON.stringify(this))
     alert(JSON.stringify(a))
     alert(JSON.stringify(b))   */
 });

  $(uploader).bind("progress", function(a,b){
     alert(JSON.stringify(this))
     alert(JSON.stringify(a))
     alert(JSON.stringify(b))
 });

 })




___post = function(){
  var data = {}
  data.id = 0;
  data.title = $("#cctitle").val();
  data.thumbnail = $("#thumbnail").val();
  data.content = $("#cccontent").val();
  data.content_type = $("button.active").attr("name");
  $.post("http://pecata/Microweber/api/save_content", data, function(){


  });
}



</script>

<?php include THIS_TEMPLATE_DIR. "foot.php"; ?>

<?php } ?>