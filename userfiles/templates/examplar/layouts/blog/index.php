<?php

/*

type: layout

name: advices layout

description: advices site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>


 
     
<? $th = get_pictures(PAGE_ID, $for = 'page');
//p($th);
$pic = $th[0] ["urls"]["original"];



if($pic == false){
	
	$par_page = get_page(PAGE_ID);
	if(!empty($par_page )){
		$th = get_pictures($par_page['content_parent'], $for = 'page');
		$pic = $th[0] ["urls"]["original"];
	}
}

if($pic == false){
$pic = 	  TEMPLATE_URL ."img/banner_blog.jpg";
}
//p($th);
?>


<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print  $pic ?>" /> </div>
</div>
      <!-- /#shadow -->
      <div id="main">
        <div id="sidebar">
          <div class="shadow" id="sidenav">
            <div class="shadow-content box">
            
            
            
            
            
            
              <h2 id="sidetitle"><a><? print $page['content_title'] ?></a></h2>
              <? category_tree("content_parent=".$page['content_subtype_value']); ?>
               
           <!--   <ul>
                <li><a href="#">Как да се грижим зазъбите си?</a></li>
                <li><a href="#">Симптоми за здрави зъби</a></li>
                <li><a href="#">Профелактични прегледи</a></li>
                <li><a href="#">Знаете ли че?</a></li>
                <li><a href="#">Дентална медицина и консумативи</a></li>
              </ul>-->
            </div>
          </div>
          <? //include   TEMPLATE_DIR.  "sidebar_second.php"; ?>
          </div>
        <!-- /#sidebar -->
        <div id="sidecontent">
          
          
          
          
          
          
      <? //p($posts); ?>    
          
          
          
          <? if(!empty($post)): ?>
           
             <div class="richtext">
            <h2><? print $post['content_title']; ?></h2>
             <? print ($post['the_content_body']); ?>
            <br /> <br />
 
          </div>
          
          <? else : ?>
          
          <? foreach($posts as $post): ?>
            <div class="richtext">
            <h2><? print $post['content_title']; ?></h2>
              <img src="<? print thumbnail($post['id'], 150); ?>" alt="<? print addslashes($post['content_title']); ?>" align="left" style="margin-right:5px; float:left;" />
            <? print character_limiter($post['content_body_nohtml'], 400); ?>
            <br /> <br />
          <a href="<? print post_link($post['id']); ?>" class="more bluebtn">Read More</a> 
          </div>
            <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
              <hr />
          <? endforeach; ?>
           <br />
          <? paging(); ?>
          
          <? endif; ?>
          
          
          
          
          
          
          
          
          
          
        </div>
        <!-- /#sidecontent -->
      </div>
      <!-- /#main -->
 

<? include   TEMPLATE_DIR.  "footer.php"; ?>
