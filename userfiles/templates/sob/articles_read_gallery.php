<?php dbg(__FILE__); ?>
<?php if($post['content_subtype'] == 'trainings') :  ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_trainings.php') ?>
<?php else : ?>
<?php $short_sidebar = true;
 
$sidebar_file = ACTIVE_TEMPLATE_DIR.'sidebar_gallery.php';
 require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box.php') ?>

<div class="main-inner" style="padding-top: 0"> 
  <!--  <h2 class="in-content-title in-content-title-ico"> <span>Find <a href="#">John Charter</a> at:</span> <a href="#" class="youtube-ico profile-ico">Watch in YouTube </a> <a href="#" class="twitter-ico profile-ico">Follow me	on Twitter </a> <a href="#" class="facebook-ico profile-ico">Become a Fan</a> <b class="titleleft"></b> </h2>-->
  <div class="pad"> 
    <!--  <h2 class="title">From My Blog</h2>-->
    
    <div class="article-info">
      <?php /*
        <a href="#" class="article-back" onclick="history.back();">Back</a>
        */ ?>
      <a href="#" onclick="scrollto('#comments');" class="ainfo"> <b class="cmm">&nbsp;</b> <?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?> <span>see all</span> </a> <a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');" class="ainfo"> <b class="voteUp">&nbsp;</b> <strong class="content-votes-count-<?php print $post['id'] ?>"><?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?></strong> <span>like</span> </a> </div>
    <h2 class="post-title" style="width:490px"><?php print $post['content_title']; ?></h2>
    <div class="richtext"><?php print ($post['the_content_body']); ?><br />
      <!--<span class="uppercase">Please vote for this post</span> <span class="voteUp">2,470</span>--> </div>
  
  
  
  
 <script type="text/javascript">
    var hash = window.location.hash;
    var hash = hash.replace("#image-", "");
    var index = parseFloat(hash);

    maingallery = {
      next:function(){
        var hash = window.location.hash;
        if(hash==""){
            window.location.hash = "#image-2";
        }
        else{
           var index = parseFloat(hash.replace("#image-", ""));
           var max =  $("#maingallery img").length;

           if(!isNaN(index) && (index+1) <= max && index>0){
             window.location.hash = "#image-" + (index+1);
           }
        }
      },
      prev:function(){
        var hash = window.location.hash;
        var index = parseFloat(hash.replace("#image-", ""));
           var max =  $("#maingallery img").length;

           if(!isNaN(index) && (index-1) > 0 && index>0){
             window.location.hash = "#image-" + (index-1);
           }
      }
    }


    $(document).ready(function(){

      $(window).load(function(){
        if(window.location.hash.indexOf("#image-") !=-1){
          $("body, html").animate({scrollTop:$("#maingallery").offset().top-40}, 'slow');
        }
      });

      $(window).bind("load hashchange", function(){
            var hash = window.location.hash;
            if(hash!=""){
              var index = parseFloat(hash.replace("#image-", ""));
              var max = $("#maingallery img").length;
              if(!isNaN(index) && index <= max && index>0){
               $("#maingallery img").hide();
               $("#maingallery img").eq(index-1).show();
               $(".maingallery-next").show();
               $(".maingallery-prev").show();
               if((index) == max){
                 $(".maingallery-next").hide();
               }
               if((index-1) == 0){
                 $(".maingallery-prev").hide();
               }
              }
            }
            else{
               $("#maingallery img").hide();
               $("#maingallery img:first").show();
               $(".maingallery-prev").hide();
            }
      });

    });

 </script>

  
  
  
  <?php $image_ids = $this->content_model->mediaGetIdsForContentId($post['id'], $media_type = 'picture'); ?>
<?php if(!empty($image_ids  )) :?>
  <div class="c" style="padding-top: 10px;">&nbsp;</div>
  <a href="#" class="btn right maingallery-next" onclick="maingallery.next()">Next Image &raquo;</a>
  <a href="#" class="btn left maingallery-prev" onclick="maingallery.prev()">&laquo; Previous Image</a>
  <div class="c" style="padding-bottom: 10px;">&nbsp;</div>

<div id="maingallery">

  <?php foreach($image_ids as $img_id): ?>

  <?php $big = $this->core_model->mediaGetThumbnailForMediaId($img_id, $size = 600); ?>

<img src="<?php print $big; ?>" alt="" />

  <?php endforeach; ?>
</div>
 <script type="text/javascript">
    var imgs = document.getElementById('maingallery').getElementsByTagName('img');
    for(var i=0; i<imgs.length;i++){
      /*var src = imgs[i].src;
      imgs[i].title=src;
      imgs[i].src=''; */
      imgs[i].style.display='none';
    }
 </script>
<?php endif; ?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

    <br />
    <a name="status-nav"><!--  --></a>
    <div class="status-nav">
      <ul>
        <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($post['created_on'])); ?></span></li>
        <li><a title="<?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?> Votes"  id="content-votes-<?php print $post['id'] ?>" class="title-tip voteUp content-votes-count-<?php print $post['id'] ?>" href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">
          <?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?>
          </a></li>
        <?php /*
        <li><a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">Like</a></li>
        */ ?>
        <li><a id="content-comments-<?php print $post['id'] ?>" class="cmm title-tip" title="<?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?> Comments"  onclick="scrollto('#comments');" href="javascript:void(0)"><?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?></a></li>
        <li><a title="Report for spam. <?php print $this->reports_model->reportsGetCount('table_content', $post['id'], '1 year'); ?> reports so far"  id="content-reports-<?php print $post['id'] ?>" class="title-tip reportUp content-reports-count-<?php print $post['id'] ?>" href="javascript:mw.content.report('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-reports-count-<?php print $post['id'] ?>');">
          <?php print $this->reports_model->reportsGetCount('table_content', $post['id'], '1 year'); ?>
          </a></li>
        <li>
          <?php require (ACTIVE_TEMPLATE_DIR.'share.php') ?>
        </li>
      </ul>
    </div>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments_list.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments.php') ?>
    
    <br />
    <br />
    <a href="javascript:void(0)"><img alt="Learn from the best." src="<?php print TEMPLATE_URL; ?>img/learn_from_the_best.jpg" /></a> <br />
  </div>
</div>
<?php endif; ?>
<?php dbg(__FILE__, 1); ?>
