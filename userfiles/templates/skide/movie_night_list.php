

 <div id="main_side" class="nopadding">
  <div class="block1">
  	<?php if(!empty($active_categories)): ?>
    <?php $cat_info = CI::model('taxonomy')->getSingleItem($active_categories[1]);?>
    <h2><?php print $cat_info['taxonomy_value'] ?></h2>
    <?php if(trim($cat_info['content_body']) != ''): ?>
    <p class="message message-info message-closable"><?php print $cat_info['content_body'] ?></p>
    <?php endif; ?>
    <?php endif; ?>


    <?php $kw_value =   CI::model('core')->getParamFromURL ( 'keyword' );
if($kw_value  != false){ ?>
<h2>Seach results for: <?php print $kw_value ?></h2>
<?php }
?>


    <br />
  </div>




  <?php if(!empty($posts)): ?>
  <?php foreach ($posts as $the_post): ?>
  <?php $more = false;
 $more = CI::model('core')->getCustomFields('table_content', $the_post['id']);
	$the_post['custom_fields'] = $more;
	?>
  <div class="portlet portlet-closable">
    <div class="portlet-header">
      <h2><?php print $the_post['content_title']; ?></h2>
      <br />

    </div>
    <!-- .portlet-header -->
    <div class="movienight_content">
      <div id="<?php print $the_post['id']; ?>tab1" class="movie_night_content"> <?php print html_entity_decode( $the_post['content_body'] );  ; ?> </div>


    </div>
    <!-- .portlet-content -->
  </div>
  <!-- .portlet -->
  <?php endforeach; ?>
  <?php endif; ?>
  <?php if(!empty($posts_pages_links)): ?>
  <?php print $page_link ;  ?>
  <br />
  <div class="paging">
    <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
    <span><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></span>
    <?php $i++; endforeach;  ?>
  </div>
  <br />
  <?php endif ; ?>
 <br />
 <h2 class="coment-title">Post your comment</h2>

    <? comment_post_form($the_post['id']); ?>
    <div class="c"> </div>
  <? comments_list($the_post['id'], 'default'); ?>

  <br /><br />
</div>
<div class="inner_video_side">

    <div class="bluebox">
        <div class="blueboxcontent">
            <div class="video_list_item" style="margin: 10px auto;">
                <a class="mw_blue_link" href="#">The worst ! ever ! American idol ! FUNNY</a>
                <a style="background-image: url(&quot;http://pecata/dev.microweber.com/public_html/skide/userfiles/media/pictures/150_150/no.gif&quot;);" class="img" href=""> </a>
                <a href="javascript:mw.content.Vote('','', '');" class="user_activity_likes left"><strong>0</strong><span></span></a>
                <a href="" class="user_activity_comments right"><strong id="post-likes-339">1</strong><span></span></a>
            </div>
            <div class="video_list_item" style="margin: 10px auto;">
                <a class="mw_blue_link" href="#">The worst ! ever ! American idol ! FUNNY</a>
                <a style="background-image: url(&quot;http://pecata/dev.microweber.com/public_html/skide/userfiles/media/pictures/150_150/no.gif&quot;);" class="img" href=""> </a>
                <a href="javascript:mw.content.Vote('','', '');" class="user_activity_likes left"><strong>0</strong><span></span></a>
                <a href="" class="user_activity_comments right"><strong id="post-likes-339">1</strong><span></span></a>
            </div>

        </div>
    </div>


</div>
