  <? 
		
	 
		if( trim($post["original_link"]) != ""){
			$url = $post["original_link"];
		} else {
		$url = cf_get($post['id'], 'url');
		}
 
	  ?>
      
<div class="each_cat_box" style="padding:25px; width:580px;">
     
    
      <div class="page_tit"><? print $post['content_title'] ?></div>
   
           <!-- <div class="page_tit">   <? if($url != false): ?>
        <a href="<? print prep_url($url) ?>" target="_blank">
        <? else : ?>
        <a href="<? print post_link($post['id']) ?>">
        <? endif; ?>
        
        
        <img src="<? print TEMPLATE_URL ?>images/<? print $btn_use; ?>" alt="offer" border="0" /></a></div>-->
      
      <div class="cat_text2"> 
	  
	  <editable rel="post" field="content_body">
	  
	  <? print   ( $post['content_body']) ?>
      </editable>
      
       </div>
      <div class="seethis_offer_but">
      
     
      
        <? if($url != false): ?>
        <a href="<? print prep_url($url) ?>" target="_blank">
        <? else : ?>
        <a href="<? print post_link($post['id']) ?>">
        <? endif; ?>
        
        
        <img src="<? print TEMPLATE_URL ?>images/<? print $btn_use; ?>" alt="offer" border="0" /></a></div>
    
    
    
  <div class="cat_text2">
  
  <div class="blog_entry_top">
    <div class="blog_title_inner">Would you like to comment?</div>
    <div class="blog_comments">
      <div class="comments_number"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></div>
      <div class="comments_lable">Comments</div>
    </div>
  </div>

<? require TEMPLATE_DIR. "blocks/comments.php"; ?>
</div>
  </div>
  
  
  

 

 