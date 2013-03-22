<? only_admin_access() ;
$comments_data = array();
if(isset($params['to_table'])){
$comments_data['to_table'] =  $params['to_table'];
} 

if(isset($params['to_table_id'])){
$comments_data['to_table_id'] =  $params['to_table_id'];
} 

 

    $comments = get_comments($comments_data);
 

	$item = module_info($config['module_name']);
 
   

    $moderation_is_required =  get_option('require_moderation', 'comments')=='y';
 
?>

<div class="comment-post">
  <div class="comment-info-holder" content-id="<? print $item['id']; ?>" onclick="mw.adminComments.toggleMaster(this, event)"> <span class="img"> <img src="<?php print thumbnail(($item['icon']),48,48); ?>" alt="" />
    <?php // $new = get_comments('count=1&is_moderated=n&to_table=table_content&to_table_id='.$content_id);
$comments_data2 = $comments_data;
$comments_data2['count'] =  1;
$comments_data2['is_new'] =  'y';
	 $new = get_comments($comments_data2);
	 
	 
	 
	 $comments_data3 = $comments_data;
 		$comments_data3['group_by'] =  'to_table,to_table_id,from_url';

	 $links = get_comments($comments_data3);
	
	 ?>
    <?php if($new > 0){ ?>
    <span class="comments_number"><?php print $new; ?></span>
    <?php } ?>
    </span>
    <div class="comment-post-content-side">
     <? if(isset( $comments[0]) and isset( $comments[0]['comment_subject'] ) and trim($comments[0]['comment_subject']) != ''): ?>
       <h3><a href="javascript:;" class="mw-ui-link"><? print $comments[0]['comment_subject'] ?></a></h3>
   
     <? else: ?>
      <h3><a href="javascript:;" class="mw-ui-link"><? print $item['name'] ?></a></h3>
     <? endif; ?>
     
      <? if(isarr($links )): ?>
       <small><? print $links[0]['to_table_id'] ?></small>
  <? foreach($links  as $link): ?> 
  <a class="comment-post-url" href="<? print $link['from_url'] ?>"><? print $link['from_url'] ?></a><br>

 <? endforeach ; ?>
<? endif; ?>
        </div>
  </div>
  <div class="comments-holder">
    <? include($config["path_to_module"].'admin_items.php'); ?>
  </div>
</div>
<? if(!empty($comments)): ?>
<div class="comments-show-btns"> <span class="mw-ui-btn comments-show-all" onclick="mw.adminComments.display(event,this, 'all');"><?php print ($count_old+$count_new); ?> All</span> <span class="mw-ui-btn mw-ui-btn-green comments-show-new" onclick="mw.adminComments.display(event,this, 'new');"><?php print $count_new; ?> New</span> </div>
<? endif; ?>
</div>
