<?php

/*

type: layout

name: sidebar

description: sidebar

*/
?>
<?



$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>
<div class="module-posts-template-sidebar">
  <? if (!empty($data)): ?>
  <ul>
  <? foreach ($data as $item): ?>



            <li class="media">
              <a href="<? print $item['link'] ?>" class="pull-left">
              <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <img src="<? print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" class="img-rounded img-polaroid"  />
              <? endif; ?>
              </a>
              <div class="media-body extra-wrap">
               <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <h5><a class="link media-heading" href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h5>
               <? endif; ?>
                <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="date"><? print $item['created_on'] ?></small>
                <?php endif; ?>

               <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                    <p><? print $item['description'] ?></p>
               <? endif; ?>

                <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                    <a href="<? print $item['link'] ?>" class="btn btn-mini"><? $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?></a>
                <?php  endif; ?>
               </div>
            </li>
  <? endforeach; ?>
   </ul> 
  <? endif; ?>
</div>
<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
 <? endif; ?>




