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



<script type="text/javascript">


function votefor(t, tt, upd){

$.post("<?php print site_url(); ?>api/content/vote", { t: t, tt:tt },
   function(data){
     //alert("Data Loaded: " + data);
	 if(data == 'yes'){
		$v = $(upd).html();
		$v = $v+1;
		$(upd).html($v);
	 }
   });

}
</script>


<?php if(!empty($posts)): ?>

<div class="nbj_wraap<?php if(!empty($subcats)): ?> nbj_wraap_short<?php endif; ?>">
  <?php foreach ($posts as $the_post):
//var_dump($the_post);
?>
  <div class="music">
    <?php $media = $this->core_model->mediaGet('table_content', $to_table_id =$the_post['id']  , $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false, $id = false); ?>
    <? if(!empty($media["files"][0])): ?>
    <span class="dlike"> <strong id="qty_<? print $the_post['id']; ?>"><?  print $this->votes_model->votesGetCount($to_table= 'table_content', $to_table_id = $the_post['id'], $since_time = false);   ?></strong> <a href="javascript:votefor('<? print $this->core_model->securityEncryptString ( 'table_content'  ); ?>', '<? print $this->core_model->securityEncryptString ( $the_post['id']  ); ?>', 'qty_<? print $the_post['id']; ?>');">Vote</a> </span>
    
    
    
    <h2 class="title inTextTitle"><?php print $the_post['content_title']; ?></h2>
    <embed
src="<?php print TEMPLATE_URL; ?>player_mp3_maxi.swf"
FlashVars="mp3=<?php print $media["files"][0]["url"] ?>&showvolume=1&buttonovercolor=C5C5C5&sliderovercolor=C5C5C5&volumewidth=50&showstop=1&width=300"
type="application/x-shockwave-flash"
data="<?php print TEMPLATE_URL; ?>player_mp3_maxi.swf"
width="300"
height="20"
wmode="transparent"></embed>
    <div class="c" style="padding-bottom: 1px;">&nbsp;</div>
    <? /*
<a href="#" class="iLike">Like</a>
<span style="color: #B2B2B2;padding-left: 10px;">12 likes</span>
*/ ?>
  </div>
  <script>
 $(document).ready(function(){

 });

 </script>
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
<div class="txt news wrap"> </div>
<?php endif; ?>
<div class="clear" style="height:25px">
  <!--  -->
</div>
