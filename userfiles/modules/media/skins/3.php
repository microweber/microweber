<link rel="stylesheet" type="text/css" href="<? print $skins_url;  ?>3/jquery.ad-gallery.css">
 
<script type="text/javascript" src="<? print $skins_url;  ?>3/jquery.ad-gallery.js"></script>
<script type="text/javascript">
  $(function() {
    $('img.image1').data('ad-desc', 'Whoa! This description is set through elm.data("ad-desc") instead of using the longdesc attribute.<br>And it contains <strong>H</strong>ow <strong>T</strong>o <strong>M</strong>eet <strong>L</strong>adies... <em>What?</em> That aint what HTML stands for? Man...');
    $('img.image1').data('ad-title', 'Title through $.data');
    $('img.image4').data('ad-desc', 'This image is wider than the wrapper, so it has been scaled down');
    $('img.image5').data('ad-desc', 'This image is higher than the wrapper, so it has been scaled down');
    var galleries = $('.ad-gallery').adGallery();
    $('#switch-effect').change(
      function() {
        galleries[0].settings.effect = $(this).val();
        return false;
      }
    );
    $('#toggle-slideshow').click(
      function() {
        galleries[0].slideshow.toggle();
        return false;
      }
    );
    $('#toggle-description').click(
      function() {
        if(!galleries[0].settings.description_wrapper) {
          galleries[0].settings.description_wrapper = $('#descriptions');
        } else {
          galleries[0].settings.description_wrapper = false;
        }
        return false;
      }
    );
  });
  </script>
 
<h1>AD Gallery, gallery plugin for jQuery</h1>
<p>A highly customizable gallery plugin for jQuery.</p>

<div id="gallery<? print $params['module_id'] ?>" class="ad-gallery">
  <div class="ad-image-wrapper"> </div>
  <div class="ad-controls"> </div>
  <div class="ad-nav">
    <div class="ad-thumbs">
      <ul class="ad-thumb-list">
      
      
      
      
      
      
 <?php $i = 1; if(!empty($media)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], $size);
	 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
//p($thumb);
?>
 
<li> <a href="<? print  $orig; ?>"> <img src="<? print  $thumb; ?>" title="<?php print addslashes($pic['media_name']); ?>" class="image<? print  $i; ?>"> </a> </li>

 
    <?php $i++; endforeach; ?>
    <?php endif; ?>
    
      
      
      </ul>
    </div>
  </div>
</div>
<div id="descriptions"> </div>
<p>Examples of how you can alter the behaviour on the fly;
  Effect:
  <select id="switch-effect">
    <option value="slide-hori">Slide horizontal</option>
    <option value="slide-vert">Slide vertical</option>
    <option value="resize">Shrink/grow</option>
    <option value="fade">Fade</option>
    <option value="">None</option>
  </select>
  <br>
  <a href="#" id="toggle-slideshow">Toggle slideshow</a> | <a href="#" id="toggle-description">Toggle having description outside of image</a> </p>
