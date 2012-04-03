       <?php $dir = ACTIVE_TEMPLATE_DIR.'collections_pics/'. $page['id'];

			$pictures = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($page['id'], '1300');
$pictures1 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($page['id'], '200');
			?>













       <?php // $picture =  TEMPLATE_URL.'collections/'.$item['content_id'] .'.jpg' ?>
    <?php
          $pictures = array();
            $pictures1 = array();
    $dir = opendir (ACTIVE_TEMPLATE_DIR.'collections/'.$page['id']);
	while (false !== ($file = readdir($dir))) {
		if (strpos($file, '.gif',1)||strpos($file, '.jpg',1)||strpos($file, '.png',1)||strpos($file, '.JPG',1)||strpos($file, '.jpeg',1)) {
            $pictures[] =   TEMPLATE_URL.'collections/'.$page['id'].'/'.$file;
            $pictures1[] =   TEMPLATE_URL.'collections/tn/'.$page['id'].'/'.$file;


		}
	}

?>




 <script type="text/javascript">
    $(function(){
       var sliders_length = $("#slider a").length;
       if(sliders_length>7){
           $("#slides_left, #slides_right").css("display", "block");
       }


    })
 </script>






<div id="collections" class="box" style="background:white">
   <div id="vision_wrap" style="background:url('<?php print $pictures[1] ?>')">


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

  <span id="collections_title"><?php print $page['content_title'] ?></span> <strong id="collections_date"><?php print date('Y') ?></strong>

</div>

</div>

<div id="slides" class="obj-slide">
    <div id="slider">


 <?php //var_dump($pictures);

 if(!empty($pictures)): ?>
  <?php $i =0; foreach($pictures as $pic): ?>


 <a href='<?php print $pictures[$i]; ?>' class='box<?php if($i==1): ?> active<?php endif; ?>'
 style="background-image:url('<?php print $pictures1[$i]; ?>')"></a>


  <?php $i++; endforeach; ?>

                  <?php endif; ?>



     <div style="display: none">
         <?php if(!empty($pictures)): ?>
  <?php $i =0; foreach($pictures as $pic): ?>
<?php print $pictures1[$i]; ?> <br />
  <?php $i++; endforeach; ?>
                  <?php endif; ?>
     </div>



    </div>

</div>
<span id="slides_left">Back</span>
<span id="slides_right">Forward</span>
<div style="height:20px" class="clear"></div>

<ul id="bangnav">
<?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('collections_menu');?>


 <?php $i = 1 ; foreach($menu_items as $item):
 if($i == 1){
 $iclass = 'first';
 }
  else if($i == 2){
 $iclass = 'second';
 }

   else if($i == 3){
 $iclass = 'third';
 }

 else {
  $iclass = 'third';
 }
  ?>
 <li class="<?php print $iclass; ?><?php if($item['is_active'] == true): ?> active<?php endif; ?>">
        <span><!--  --></span>
        <a href="<?php print $item['the_url'] ?>"><?php print ucwords( $item['item_title'] ) ?></a>
    </li>
 <?php $i++; endforeach ;  ?>









</ul>

<div style="clear:both;height:10px"><!--  --></div>