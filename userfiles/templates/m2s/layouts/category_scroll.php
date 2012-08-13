
sdfsdfsdf

<?php 
  
   $get_categories_params = array(); 
   $get_categories_params['parent'] = 100002038; 
	$categories = get_categories($get_categories_params) ;
 
p($categories ,1);
  ?>

 
  
  
  
  
  
  <?   $i = 1;
  foreach($categories as $category): 
  ?>
<? if($i < 8): ?>
  
  <a href="<? print category_url($category['id']); ?>" id="money_icon<? print $i; ?>" class="tt"><span class="tooltip" style="margin-left:10px;"><span class="middle" ><strong><? print $category['taxonomy_value']; ?></strong> <br />
    <? print $category['taxonomy_description']; ?></span><span class="bottom"></span></span></a> 
    

 


<? endif; ?>
 
<? $i++; endforeach; ?>


sdfsdfsdfsd



<!--
<div class="scrollingbuts">
  <ul  id="mycarousel" class="jcarousel-skin-tango">
    <li> <a href="#" id="scrollbut_1"> </a> </li>
    <li class="pager" > <a href="#" id="scrollbut_2"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_3"> </a> </li>
    <li class="pager" > <a href="#" id="scrollbut_4"></a> </li>
    <li > <a href="#" id="scrollbut_5"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_6"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_7"> </a> </li>
  </ul>
</div>-->