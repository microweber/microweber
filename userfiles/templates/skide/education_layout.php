<?php

/*

type: layout

name: Education layout

description: Education layout









*/



?>
<? $edu = get_page('education'); ?>
<?php include "header.php" ?>

<div class="wrap">
  <div id="main_content">
    <div id="user_sidebar">
      <h3 class="user_sidebar_title nomargin"><a href="<? print page_link($edu['id']); ?>">Education</a></h3>
      <? 
		  $cat_params = array(); 
		  $cat_params['parent'] = intval($edu['content_subtype_value']); //begin from this parent category
		  $cat_params['get_only_ids'] = false; //if true will return only the category ids
		  $categories = get_categories($cat_params); 
		  ?>
      <ul class="user_side_nav user_side_nav_nobg">
        <? foreach($categories as $category): ?>
        <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> 
        <? 
		  $cat_params = array(); 
		  $cat_params['parent'] = intval($category['id']); //begin from this parent category
		  $cat_params['get_only_ids'] = false; //if true will return only the category ids
		  $sub_categories = get_categories($cat_params); 
		  ?>
        <? if(!empty($sub_categories)): ?>
        <ul>
          <? foreach($sub_categories as $sub_category): ?>
          <li><a href="<? print get_category_url($sub_category['id']); ?>"  class="<? is_active_category($sub_category['id'],' active') ?>"  ><? print $sub_category['taxonomy_value'] ?></a> </li>
          <? endforeach; ?>
        </ul>
        <? endif; ?>
        <? endforeach; ?>
        </li>
      </ul>
      <h3 class="user_sidebar_title">Questions and answers</h3>
      <ul class="user_side_nav user_side_nav_nobg">
        <? $q_page = get_page('questions');
		  if($page['id'] == $q_page['id']){
			if($post){
				if(url_param('action') != 'add'){
					$q_page_class = "active";
				}
				
			} else {
				if(url_param('action') == 'list'){
					$q_page_class = "active";
				}
			}
									
		  }
		  
		  ?>
        <li><a class="<? print $q_page_class; ?>" href="<? print page_link('questions'); ?>/action:list">Questions and answers</a></li>
        <li><a class="<? if(url_param('action') == 'add'): ?>active<? endif; ?>"  href="<? print page_link('questions'); ?>/action:add">Post question</a></li>
      </ul>
    </div>
    <!-- /#user_sidebar -->
    <div id="main_side">
      <script type="text/javascript">
function content_list($kw){
   
   if(($kw == false) || ($kw == '')){
	$kw = '';   
	   $('#results_holder').fadeOut();
	$('#main_side_content').fadeIn();
 
   } else {
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: ({module : 'posts/list' ,
			// user_id : $user_id, 
			 file : 'posts_list_wide', 
			 category : '<? print  $active_category ?>',  
			
			 keyword : $kw 

			 }),
     // dataType: "html",
      async:false,

  success: function(resp) {
     // alert(resp);
   $('#results_holder').html(resp);
    $('#results_holder').fadeIn();
	$('#main_side_content').fadeOut();
 
	
	$('#results_holder_title').html("Search results for: "+ $kw);
	
   // alert('Load was performed.');
  }
    });
   
   }
}

$(document).ready(function() {
  //users_list();

  $(".content_search").onStopWriting(function(){
       content_list(this.value);
  });

});

</script>
      <? $questions = get_page('questions'); ?>
      <? if(count($active_categories) == 1 and ( $questions['id'] != $page['id'])):  ?>
      <style type="text/css">
       .content_search, #main_side_content{
         display: none;
       }


       </style>
      <div id="eh">
        <h2>SKID-E-KIDS PROMOTES EDUCATION GLOBALLY</h2>
        <h3>WHEN IT COMES TO EDUCATION WE DO MORE THAN JUST TALK</h3>
      </div>
      <a style="font-size: 22px;padding: 28px 0;margin-left: 50px;" class="left" id="home_parents_reg" href="<? print site_url('education/categories:18') ?>">Articles</a> <a style="font-size: 22px;padding: 28px 0;margin-right: 50px;" class="right" id="home_parents_about" href="<? print site_url('questions/action:list') ?>">Questions and answers</a>
      <? endif; ?>
      <input type="text"  class="content_search"  />
      <div id="results_holder"></div>
      <div id="main_side_content">
        <? /*
        Q & A Start
*/ ?>
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
</div>
<?php include "footer.php" ?>
