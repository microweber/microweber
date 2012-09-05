<?php

/*

type: layout

name:  layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>





<? $user_id = user_id(); ?>

<? if( $user_id == 0): ?>



<? include TEMPLATE_DIR. "must_reg_generic.php"; ?>


<? else: ?>





<script type="text/javascript">
function add_new_post(){
 
 mw.module({
		   module : 'posts/add' , 
		   display : 'add' ,
		   
		   title: "<? print $a = (!$a_title) ? 'Add new post' : $a_title;  ?>",
		   category: "<? print  CATEGORY_ID; ?>",
		   view:"blocks/my_posts_add.php",
		  // redirect_on_success : "<? print site_url('dashboard/action:toys'); ?>",
		  // display_1 : "add_pictures",
		  // display_2 : "add_pricing",

	 
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Title:",
		   submit_btn_text : "Save",
		   body_label:"Content: "
		   },'#post_dash');
  $('#post_dash').show();
  $('.forum_mid').hide();
  

}







function post_after_save(){
	mw.reload_module('posts/list') ;
	 $('.user_posts_hide_on_edit').show();
	  $('#post_dash').hide();
	  // $('.forum_mid').show();
}

 
</script>




<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left">
    
    
    
      <div class="howit_works_left_img"><img src="<? print TEMPLATE_URL ?>images/blog_left_img.jpg" width="210" height="172" /></div>
      <div class="blog_category_tit">Categories</div>
      <mw module="content/category_tree" include_first="0" content_parent="<? print $page['content_subtype_value'] ?>" />
      <? include   TEMPLATE_DIR.  "sponsors_sidebar.php"; ?>
    </div>
    <div class="inner_rt">
    
    
    
    <div class="contentpanel" id="post_dash" style="display:none"></div>
     <div class="user_posts_hide_on_edit" style="display:none">You have added a new post! Thank you.
     
     
     
     <a href="<? print category_url(CATEGORY_ID); ?>" class="add_new_post">Continue</a>
     
     </div>
    
    
    <div class="forum_mid">
     

      <div class="page_tit_left2"> 
	  <a href="<? print page_link($page['id']); ?>">
	  <? print $page['content_title']; ?>
     </a>
       <? if((count($active_categories)>  1)): ?>
       
       <? 
	   $c2 = $active_categories;
	   $c1 = array_shift($c2); ?>
        <? foreach($c2 as $c): ?> / <a href="<? print category_url($c); ?>"><? print category_name($c); ?></a>
      <? endforeach; ?>
      <? endif; ?>
      </div>
       <? if($post): ?>
      <? include     "inner.php"; ?>
      <? else: ?>
        <div class="page_tit_right">
        
        <?
		$can_post == true;
		
		if($post_by_ms_users == true){
			
			$can_post = false;
			
			 if(stristr($user_data['email'], 'money2study.com')){
				 $can_post = true;
			 }
		 
			 $can_post = true;
			
			
		}
		
		  $can_post = true;
		
		?>
        
        
        
         <? if($user > 0 and $can_post == true): ?>
         <a href="javascript:add_new_post()" class="add_new_post">Add new post </a>

    <? else:  ?>
          

    <? endif; ?>
        
        </div>
      
     <? if((count($active_categories) == 1) and url_param('curent_page') == false): ?>
      <div class="howit_content">
        <editable rel="page" field="content_body">Welcome to the Forums</editable>
      </div>
       <? endif; ?>
      
      
      <? foreach($posts as $post): ?>
      <?
  //$post_image_data = get_picture($post['id'], $for = 'post'); 
  //	$thumb = get_media_thumbnail($post_image_data['id'], 192);
  
  ?>
      <div class="speak_box">
      <? if($anon_users == false): ?>
        <div class="commentee_thumb" style="background-image:url('<? print user_thumbnail($post['created_by'], 90); ?>')">
      

        </div> <? endif; ?>
        <div class="commentee_text">
          <div class="blog_title2"><a href="<? print post_link($post['id']) ?>"><? print $post['content_title'] ?></a></div>
          <div class="blog_sub_title">Published: <? print ($post['created_on']) ?> </div>
          <? print   character_limiter( $post['content_body_nohtml'], 350 , "...") ?>  </div>
          <? if($anon_users == false): ?>
        <div class="commentee_name"><? print user_name($post['created_by']) ?></div>
         <? endif; ?>
        <div class="blog_comments">
          <div class="comments_number"><a href="<? print post_link($post['id']) ?>"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></a></div>
          <div class="comments_lable">Answers</div>
        </div>
        
      </div>
      <? endforeach; ?>
      <? paging(); ?>
      <? endif; ?>
      
      
      
      
      </div>
      
      
      
      
  <!--     <div class="inner_container_bot">
  
  
  <div class="fb-like" data-href="<? print url() ?>" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>
  
  
 
  </div>-->
      
    </div>
   
  </div>
  
</div>
 <? endif; ?>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
