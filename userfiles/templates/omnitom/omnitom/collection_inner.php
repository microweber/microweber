


<?php include 'includes/header.php' ?>
<style type="text/css">
    #sub_right{
      display: none;
    }

</style>
<div id="collections" class="box" style="background:white">
   <div id="vision_wrap" style="background:url(site_img2/)">


   </div>

        <a href="javascript:;" class="aRight">
            <span class="arr_right"><!--  --></span>
        </a>
        <a href="javascript:;" class="aLeft">
            <span class="arr_left"><!--  --></span>
        </a>

 <span id="coll_loading"><!--  --></span>

<a href="#" class="logo_in">Omnitom</a>
<div id="cinfo">

  <span id="collections_title">Prana Power</span> <strong id="collections_date">2009</strong>

</div>

</div>

<div id="slides">
    <div id="slider">

<?php

$dir = opendir ("./site_img2");
	while (false !== ($file = readdir($dir))) {
		if (strpos($file, '.gif',1)||strpos($file, '.jpg',1)||strpos($file, '.png',1)||strpos($file, '.JPG',1)||strpos($file, '.jpeg',1)) {
			echo "<a href='site_img2/$file' class='box' style='background-image:url(site_img2/small/$file)'></a>";
		}
	}
?>


    </div>

</div>
<span id="slides_left">Back</span>
<span id="slides_right">Forward</span>
<div style="height:20px" class="clear"></div>

<ul id="bangnav">
    <li class="first">
        <span><!--  --></span>
        <a href="#">Prana Power</a>
    </li>
    <li class="second">
        <span><!--  --></span>
        <a href="#">Embrace</a>
    </li>
    <li class="third">
        <span><!--  --></span>
        <a href="#">Chackra Chick</a>
    </li>
</ul>

<div style="clear:both;height:10px"><!--  --></div>



































<?php include 'includes/footer.php' ?>























