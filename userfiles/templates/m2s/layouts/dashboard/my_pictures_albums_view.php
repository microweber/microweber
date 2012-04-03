<? $the_post = get_post(url_param('id')); ?>
<? /*
            START GALLERY
 */ ?>
<script type="text/javascript">

  $(document).ready(function(){

  if($(".gallery_item").length<=1){
    $(".gall_nav").hide();

  }


 if(window.location.hash=='' || window.location.hash.indexOf('#gimage_')==-1){
        //window.location.hash = '#gimage_0';
        window.location.hash = '#display:gallery';



  }

  });

  window.onhash("#display:gallery", function(){
         $(".gallery").hide();
         $(".gal_thumbs").show();
         $(".gal_thumbs .video_list_item").each(function(){
            if($(this).find(".imgpreload").length>0){
               var src = $(this).find(".imgpreload").html();
               $(this).find(".imgpreload").replaceWith("<img src='" + src + "' />");
            }
         });
  });
  window.onhashSimilar("#gimage_", function(){
    var hash = window.location.hash;
    $(".gallery").show();
    $(".gal_thumbs").hide();

    if($(hash).length>0){

        $(".undefined").hide();

        $(".gallery_thumbs a").removeClass("active");
        $(hash).addClass("active");
        $(".gallery_item").hide();
        if($(hash).find(".imgpreload").length>0){
          var src = $(hash).find(".imgpreload").html();
          $(hash).find(".imgpreload").replaceWith("<img src='" + src + "' />");
        }
        $(hash).show();

        var curr =  $(".gallery_item:visible");
        var next = curr.next(".gallery_item").length>0?curr.next(".gallery_item").attr("id"): $(".gallery_item:first").attr("id");
        var prev = curr.prev(".gallery_item").length>0?curr.prev(".gallery_item").attr("id"): $(".gallery_item:last").attr("id");
        $(".gallery_next").attr("href", "#" + next);
        $(".gallery_prev").attr("href", "#" + prev);
    }
    else{
       $(".undefined").show();
       $(".gallery_item").hide();
    }
  });


  </script>

<div class="c">&nbsp;</div>
<div class="gal_thumbs" style="display: none">



  <? $media =  CI::model ( 'core' )->mediaGet('table_content', url_param('id'), $media_type = 'picture', $order = "ASC", $queue_id = false, $no_cache = false, $id = false);
	 
	$media = $media['pictures'];
	?>
  <? if(!empty($media)): ?>
  <? $i=0 ; foreach($media as $item):
	// var_dump($item);

	?>
  <div class="video_list_item video_list_item_imggal img_id_<? print $item['id']; ?>">
  <a href="#gimage_<? if($media[$i+1] != false): ?><? print  $i+1; ?><? else: ?>0<? endif; ?>">
  <span class="imgpreload"><? print  CI::model ( 'core' )->mediaGetThumbnailForMediaId($item['id'], $size_width = 120, $size_height = false); ?></span> </a>

  </div>
  <? $i++; endforeach; ?>
  <? endif; ?>






</div>

 

<div class="c">&nbsp;</div>
<div class="gallery">
  <div class="undefined richtext">
    <p>The image you have requested doesn't exists.</p>
  </div>
  <div align="right" style="padding: 10px" class="clear gall_nav"> <a href="#" class="gallery_prev">Previous</a>&nbsp;|&nbsp;<a href="#" class="gallery_next">Next</a> &nbsp;&nbsp;</div>
  <? $media =  CI::model ( 'core' )->mediaGet('table_content', url_param('id'), $media_type = 'picture', $order = "ASC", $queue_id = false, $no_cache = false, $id = false);
	 
	$media = $media['pictures'];
	?>
  <? if(!empty($media)): ?>
  <? $i=0 ; foreach($media as $item):
	// var_dump($item);

	?>
  <div class="gallery_item img_id_<? print $item['id']; ?>" id="gimage_<? print  $i ?>"> <a href="#gimage_<? if($media[$i+1] != false): ?><? print  $i+1; ?><? else: ?>0<? endif; ?>"> <span class="imgpreload"><? print  CI::model ( 'core' )->mediaGetThumbnailForMediaId($item['id'], $size_width = 500, $size_height = false); ?></span> </a>
    <div class="gallery_item_comments">
      <? $update_element = md5(serialize($item));
  $this->template ['comments_update_element'] = $update_element;
	$this->load->vars ( $this->template );
  ?>
      <? comment_post_form($item['id'],'dashboard/index_item_comments.php', 'media')  ?>
      <div id="<? print $update_element ?>">
        <? comments_list($item['id'], 'dashboard/index_item_comments_list.php', 'media')  ?>
      </div>
    </div>
  </div>
  <? $i++; endforeach; ?>
  <? endif; ?>
</div>
<? /*
            END GALLERY
 */ ?>
