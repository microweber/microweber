<?php

/*

type: layout

name: Search

description: Search

*/
?>
<?php



$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>
<div class="module-posts-template-sidebar module-posts-template-search ">
  <?php if (!empty($data)): ?>
  <ul>
  <?php foreach ($data as $item): ?>



            <li class="media">

              <a href="<?php print $item['link'] ?>" class="pull-left">
              <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" class="img-rounded img-polaroid"  />
              <?php endif; ?>
              </a>
              <div class="media-body extra-wrap">
               <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <h5><a class="link media-heading" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h5>
               <?php endif; ?>
               <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                    <p><?php print $item['description'] ?></p>
               <?php endif; ?>

               </div>
            </li>
  <?php endforeach; ?>
   </ul> 
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
 <?php endif; ?>




