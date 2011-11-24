<?php

/*

type: layout

name: Tube layout

description: Tube layout









*/



?>
<?php include "header.php" ?>
    <div class="wrap">
      <div id="main_content">
        <div id="user_sidebar">
          <h3 class="user_sidebar_title nomargin"><? print $page['content_title'] ?></h3>
          <? $categories = get_categories(); ?>
          <ul class="user_side_nav user_side_nav_nobg">
            <? foreach($categories as $category): ?>
            <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
            <? endforeach; ?>
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
			 file : 'video_list',  
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
      <input type="text"  class="content_search"  />
      <div id="results_holder"></div>
      <div id="main_side_content">
        
          <? if(!empty($post))  : ?>
          <? $cat = get_category($active_categories[1]);  ?>
          <? include(TEMPLATE_DIR.'education_inner_videos.php')	; ?>
          <? else : ?>
          <h2>
            <? breadcrumbs('/') ?>
          </h2>
          <br />
          <?
$i = 0;
foreach($posts as $the_post):
include(TEMPLATE_DIR.'post_item_video.php')	;?>
          <? $i++; endforeach; ?>
          <br />
          <br />
          <? paging($display = 'divs') ?>
          <? endif; ?>
        </div>
        </div>
      </div>
    </div>
    <?php include "footer.php" ?>
