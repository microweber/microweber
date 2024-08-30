<?php

/*

  type: layout
  content_type: static
  name: Mobile
  description: Mobile

*/

?>


<?php

print load_module('pictures/admin');
if(array_key_exists('post', $_GET)){     ?>






<?php } else {   ?>

<div id="content" style="padding-top: 120px;">
  <div class="mw-ui-box" style="width: 800px;max-width: 80%;margin: auto;">
  <div class="mw-ui-box-header">
       <h2><?php _e('Add Content'); ?></h2>
  </div>
  <div class="mw-ui-box-content">


  <label><?php _e('Select content type'); ?></label>
<div class="mw-ui-field-holder">

    <div class="mw-ui-btn-nav">
      <span class="mw-ui-btn mw-ui-btn-big" data-name="page"><span class="ico ipage"></span><?php _e('Page'); ?></span>
      <span class="mw-ui-btn mw-ui-btn-big" data-name="product"><span class="ico iproduct"></span><?php _e('Product'); ?></span>
      <span class="mw-ui-btn mw-ui-btn-big" data-name="post"><span class="ico ipost"></span><?php _e('Post'); ?></span>
      <span class="mw-ui-btn mw-ui-btn-big" data-name="category"><span class="ico icategory"></span><?php _e('Category'); ?></span>
    </div>
</div>

<div class="mw-ui-field-holder">
  <input type="text" name="title" class="form-select  mw-ui-fieldtitle mw-ui-field-full" placeholder="<?php _e('Title'); ?>" id="title"  />
</div>

<div class="mw-ui-field-holder">
<div id="mobup" class="mw-ui-btn mw-ui-btn-blue right" style="position: relative"><?php _e('Add Media'); ?></div>



</div>
<div class="mw-ui-field-holder">
    <textarea name="content" class="form-select  mw-ui-field-full" style="height: 100px;"  placeholder="<?php _e('Content'); ?>" id="content"></textarea>
</div>
<div class="mw-ui-field-holder">




                                      <input type="hidden" name="thumbnail" id="thumbnail" />

    <span class="mw-ui-btn mw-ui-btn-green right" onclick="___post()"><?php _e('Save'); ?></span>

    </div>

  </div>
  </div>


  <!-- Content -->

   <div class="mw-ui-box" style="width: 800px;max-width: 80%;margin: auto;">
  <div class="mw-ui-box-header">
       <h2><?php _e('Posts'); ?></h2>
  </div>
  <div class="mw-ui-box-content">
        <?php

        $params = array();
        if(array_key_exists('type', $_GET)){
          $params['content_type'] = $_GET['type'];
        }
        else{

        }

       if(array_key_exists('subtype', $_GET)){
        $params['subtype'] = $_GET['subtype'];
       }

        $content = get_content($params);

if (!empty($content)) {
     foreach($content as $item){   ?>

       <div class="mob-post">
        <img width="50" src="<?php print thumbnail($item['image']); ?>" alt="" />  <?php print($item['title']); ?>
       </div>


  <?php     }
}


        ?>

  </div>
  </div>

  <div class="mw-ui-box" style="width: 800px;max-width: 80%;margin: auto;">
  <div class="mw-ui-box-header">
       <h2><?php _e('Orders'); ?></h2>
  </div>
  <div class="mw-ui-box-content">
       <?php

       $orders = get_orders();


        foreach($orders as $order){

       ?>

       <div>
        <?php print $order['first_name']; ?>
        <?php print $order['last_name']; ?>
        <?php print $order['email']; ?> -
         <?php print mw()->shop_manager->currency_format(floatval($order['amount'])+floatval($order['shipping']),$order['currency']); ?>
       </div>

       <?php } ?>

  </div>
  </div>



</div>


<style>

#mw-admin-content{
  width: auto;
  min-width: 0;
}

#mw_toolbar_nav{
  display: none;
}

</style>


<script>
    mw.require("files.js");
</script>
<script>








   someFUNC  = function(url){
     alert("Uploaded: " + url)
   }


mw.mobile = {
    temp:[],
    mediaUpload:function(callback){

        var up = mw.dialogIframe({
          url:"rte_image_editor#someFUNC",
          name:"mw_rte_image",
          width:430,
          height:230,
          template:'mw_modal_simple',
          overlay:true
        });
    }
}



 $(document).ready(function(){





 mw.ui.btn.radionav(document.querySelector('.mw-ui-btn-nav'));



 $(uploader).bind("FileUploaded", function(a,b){

     $("#thumbnail").val(b.src);

     var obj = {
        "for": "content",
        "for_id": 0,
        "media_type"  :	"picture",
        "src" : b.src
     }

     $.post("/Microweber/api/save_media", obj)
 });
 });









___post = function(url){
  var url = url || "/Microweber/api/save_content";
  var data = {}
  data.id = 0;
  data.title = $("#title").val();
  data.thumbnail = $("#thumbnail").val();
  data.content = $("#content").val();
  data.content_type = $("button.active").dataset("name");


  $.ajax({
    type: "POST",
    url: url,
    data: data,
    cache: false,
    success: function(a,b){

    },
    error:function(){

    }
  });




}



</script>




<?php } ?>
