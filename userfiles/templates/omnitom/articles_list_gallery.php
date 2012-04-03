<?php /*
 <?php $subcats = CI::model ( 'taxonomy' )->getItems($active_categories[0], $taxonomy_type = 'category');   ?>
        <?php //var_dump($subcats); ?>
 <?php if(!empty($subcats)): ?>
  <div class="articles-categories-sidebar" id="side_nav">
  <!--<h2><?php print $page['content_title'] ?></h2>-->
<?php //thisp age
$link = false;
$link = CI::model ( 'content' )->getContentURLById($page['id']).'/category:{taxonomy_value}' ;
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
CI::model ( 'content' )->content_helpers_getCaregoriesUlTree($page['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false); ?>


</div>
<?php endif; ?>
*/ ?>








<?php if(!empty($posts)): ?>
<div class="nbj_wraap<?php if(!empty($subcats)): ?> nbj_wraap_short<?php endif; ?>">







<?php foreach ($posts as $the_post):
//var_dump($the_post);
?>


    <?php $pictures = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($the_post['id'], 'original');
				  $pictures2 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($the_post['id'], '600');
				  $pictures3 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($the_post['id'], '300');
				   ?>
<?php if(!empty($pictures)):
 //var_dump($pictures );
 ?>

 <h2 class="title inTextTitle"><?php print $the_post['content_title']; ?></h2>
 <script>
 $(document).ready(function(){
 $(".V2 a:first-child").css("visibility","visible");
 });

 </script>
<div id="slides" class="V2">
  <div id="slider">
    <?php $i =0; foreach($pictures as $pic): ?>
    <?php //if($i > 0): ?>
    <a  href="<?php print $pictures2[$i]; ?>" class="box active zoom"
        style="visibility:hidden;background-image:url('<?php print $pictures3[$i]; ?>')"></a>
    <?php //endif; ?>
    <?php $i++; endforeach; ?>
  </div>
</div>
<?php /*
<span id="slides_left">Back</span> <span id="slides_right">Forward</span>
*/ ?>
<div class="clear"></div>
<?php endif; ?>






<?php endforeach; ?>
</div>

<div style="height:12px"></div>


<?php if(!empty($posts_pages_links)): ?>
<?php print $page_link ;  ?>
<ul class="paging">
<?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
<li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
<?php $i++; endforeach;  ?>
</ul>
 <?php endif ; ?>



<?php else : ?>
<div class="txt news wrap">

</div>
<?php endif; ?>

<div class="clear" style="height:25px"><!--  --></div>