<?php

/*

type: layout

name: services layout

description: services site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>



<? //if(is_file()) ?>

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
$pic = 	  TEMPLATE_URL ."img/services_banner.png";
}
//p($th);



?>





<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print  $pic ?>" alt="<? print addslashes($page['content_title']); ?>" /> </div>
</div>






<!-- /#shadow -->
<div id="main">
  <div id="sidebar">
<!--    <div class="shadow">
      <div class="shadow-content box">
        <iframe src="http://player.vimeo.com/video/22439234" width="314" height="220" frameborder="0" scrolling="no"></iframe>
      </div>
    </div>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>-->
    <div class="shadow" id="sidenav">
      <div class="shadow-content box">
        <h2 id="sidetitle">More information</h2>
        <mw module="content/pages_tree" />
      </div>
    </div>
    
    
      <? include   TEMPLATE_DIR.  "sidebar_second.php"; ?>
    
    </div>
  <!-- /#sidebar -->
  <div id="sidecontent">
    <div class="richtext"><h1>
     <editable  rel="page" field="content_title">
      <? print $page['content_title']; ?>
      </editable>
      </h1>
      <!--<img src="<? print TEMPLATE_URL ?>img/img.jpg" alt="" />-->
      <? if($inc_f): ?>
      <? include($inc_f); ?>
      <? else: ?>
       <editable  rel="page" field="content_body">
      <? print $page['content_body']; ?>
       </editable>
      <? endif; ?>
      
       </div>
  </div>
  <!-- /#sidecontent -->
</div>
<!-- /#main -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
