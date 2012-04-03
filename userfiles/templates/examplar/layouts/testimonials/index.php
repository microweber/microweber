<?php

/*

type: layout

name: testimonials layout

description: testimonials site layout









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
$pic = 	  TEMPLATE_URL ."img/banner_testomonials.jpg";
}
//p($th);
?>

<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print  $pic ?>" /> </div>
</div>
<!-- /#shadow -->
<div id="main">
   
 
 
    <? //p($posts); ?>
    <? if(!empty($post)): ?>
    <div class="richtext">
      <h2><? print $post['content_title']; ?></h2>
      <? print ($post['the_content_body']); ?> <br />
      <br />
    </div>
    <? else : ?>
    <? foreach($posts as $post): ?>
    
    
    
    
    <div class="shadow">
 <div class="shadow-content box inner_top">
 <div class="richtext">
      
     <h4><? print $post['content_title']; ?></h4> <? print character_limiter($post['content_body_nohtml'], 4000); ?> <br />
     
        </div>
<i class="tl">&nbsp;</i>
<i class="tr">&nbsp;</i>
<i class="bl">&nbsp;</i>
<i class="br">&nbsp;</i>
</div>
</div>
    
    
   
    <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
     
    <? endforeach; ?>
    <br />
    <? paging(); ?>
    <? endif; ?>
   
  <!-- /#sidecontent -->
</div>
<!-- /#main -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
