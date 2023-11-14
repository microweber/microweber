<?php

/*

type: layout

name: Search

description: Search

visible: no

*/
?>

<script>mw.moduleCSS("<?php print modules_url(); ?>posts/css/style.css"); </script>

<?php



$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>

<div class="module-posts-template-search">
  <?php if (!empty($data)): ?>
  <ul>
  <?php foreach ($data as $item): ?>
            <li>
                <div class="row ">

                    <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                      <div class="col-auto module-posts-template-search-image-holder">
                        <a href="<?php print $item['link'] ?>" class="module-posts-template-search-image">
                          <img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" width="50" height="50"/>
                        </a>
                      </div>
                    <?php endif; ?>
                    <div class="col">
                        <div class="module-posts-template-search-body">
                         <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                              <h6>
                                  <a class="link media-heading text-decoration-none" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a>
                              </h6>
                         <?php endif; ?>
                         </div>
                     </div>

                    <div class="col-auto">
                        <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                            <div class=" price">
                                <?php if (isset($item['prices']) and is_array($item['prices'])) : ?>
                                    <?php
                                    $vals2 = array_values($item['prices']);
                                    $val1 = array_shift($vals2); ?>
                                    <span><?php print currency_format($val1); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
               </div>
            </li>
  <?php endforeach; ?>
   </ul>
  <?php endif; ?>


  <?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
 <?php endif; ?>

</div>





