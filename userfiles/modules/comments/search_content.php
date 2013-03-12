<?
	$comments_data = array();
$comments_data['in_table'] =  'table_comments';
$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
	$comments_data['keyword'] =  $params['search-keyword'];
	//	$comments_data['debug'] =  'comments/global';
}
$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>

<div>

  <? foreach($data  as $item){ ?>


  <?php

    $data = array(
        'content-id' => $item['id']
    );

    $comments = get_comments($data);



       // break;

    ?>

<div class="comment-post">
  <span class="img">
    <img src="<?php print thumbnail(get_picture($item['id']),67,67); ?>" alt="" />
    <span class="comments_number"><?php print sizeof($comments); ?></span>
  </span>
  <div class="comment-post-content-side">
    <h3><? print $item['title'] ?></h3>
    <a class="comment-post-url" href="<? print $item['url'] ?>"> <? print $item['url'] ?></a>
    <a class="mw-ui-link" href="<? print $item['url'] ?>/editmode:y">Live edit</a>
    <span class="mw-ui-btn">All</span>
    <span class="mw-ui-btn mw-ui-btn-green">New</span>
  </div>


  <?php  foreach ($comments as $comment){ ?>

    <div class="comment-of-apost">
           <?php

            print $comment['comment_name'];
            print $comment['comment_body'];
            print $comment['comment_email'];
            print $comment['comment_website'];
            print $comment['is_moderated'];


           ?>
        <?php  _d($comment); break; ?>

    </div>

  <?php } ?>

 </div>


    <?php // _d($item);  break;  ?>



  <? } ; ?>
</div>
<? endif; ?>
