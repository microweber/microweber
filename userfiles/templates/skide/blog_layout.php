<?php

/*

type: layout

name: blog layout

description: blog layout









*/



?>
 
<?php include "header.php" ?>
    <div class="wrap">
      <div id="main_content">
        <div id="user_sidebar">
          <h3 class="user_sidebar_title nomargin"><a href="<? print page_link($page['id']); ?>"><? print ucwords($page['content_title']); ?></a></h3>
          <? 
		  $cat_params = array(); 
		  $cat_params['parent'] = intval($page['content_subtype_value']); //begin from this parent category
		  $cat_params['get_only_ids'] = false; //if true will return only the category ids
		  $categories = get_categories($cat_params); 
		  ?>
          <ul class="user_side_nav user_side_nav_nobg">
            <? foreach($categories as $category): ?>
            <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
            <? endforeach; ?>
          </ul>
           
        </div>
        <!-- /#user_sidebar -->
        <div id="main_side">
          <? /*
        Q & A Start
*/ ?>
          <? $questions = get_page('questions'); ?>
          <? if( $questions['id'] == $page['id']): ?>
          <? if(url_param('action') == 'add'): ?>
          <microweber module="posts/add" title="Post your question" submit_btn_text="Post your question" category="questions" redirect_on_success="<? print page_link($page['id']); ?>" title_label="Question title: " body_label="Question: ">
          <? else:?>
          <div class="c">&nbsp;</div>
          <h2>Questions and answers</h2>
          <br />
          <? if(empty($post)): ?>
          <div class="post_list">
            <microweber module="posts/list" category="<? print intval($questions['content_subtype_value']) ?>" display="questions_and_answers" >
          </div>
          <? else : ?>
          <microweber module="posts/read" post_id="<? print intval($post['id']) ?>" display="read_questions_and_answers">
          <? endif; ?>
          <? endif; ?>
          <? /*
        /Q & A END
*/ ?>
          <? else : ?>
          <? $cat = get_category($active_categories[1]);  ?>
          <? if(!empty($post)): ?>
          <? if($cat['taxonomy_content_type'] == 'videos'): ?>
          <? include(TEMPLATE_DIR.'education_inner_videos.php')	; ?>
          <? else : ?>
          <? include(TEMPLATE_DIR.'education_inner.php')	; ?>
          <? endif; ?>
          <? else : ?>
          <h2>
            <? breadcrumbs('/') ?>
          </h2>
          <br />
          
          
              
          
          <? $cat = get_category($active_categories[1]);  ?>
          <? if($cat['taxonomy_content_type'] == 'videos'): ?>
          <?
$i = 0;
foreach($posts as $the_post):
include(TEMPLATE_DIR.'post_item_video.php')	;?>
          <? $i++; endforeach; ?>
          <? else : ?>
          
 
            <mw module="posts/list" file="posts_list_wide" category="<? print  $active_category ?>" limit="15" />
            
            <mw module="posts/paging" />
            
            
          <? endif; ?>
          <br />
          <br />
          <? paging($display = 'divs') ?>
          <? endif; ?>
          <? endif; ?>
        </div>
      </div>
    </div>
    <?php include "footer.php" ?>
