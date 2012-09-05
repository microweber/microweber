
 

<?php 
  
   $get_categories_params = array(); 
   $get_categories_params['parent'] = 100002038; 
	$categories = get_categories($get_categories_params) ;
 
 
  ?>

 
  
  
   



<div class="scrollingbuts">
 
                <div id="footer-partners">
                <div class="bx-wrapper" style=" position: relative; float:left; ">
                    <div class="bx-window" style="position: relative; overflow: hidden;  ">
                       <ul id="mycarousel" style="width: 999999px; position: relative; padding:0px; margin:0px;">
                                    <li style=" float: left; list-style: none outside none;"> <a href="<? print category_url(100002047); ?>" class="scrollbut" id="scrollbut_1"><div class="text_div" style=""><img src="<? print TEMPLATE_URL.'images/icons/shopping_cart.png' ?>" class="cat_scroll_icon" />Shopping</div></a> </li>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                     <?   $i = 1;
  foreach($categories as $category): 
  //print_r($category);
  ?>
<? 
//id="scrollbut_<? print $i; "

if($i < 800):
 ?>

<li class="pager" > <a class="scrollbut" href="<? print category_url($category['id']); ?>" 

<?php 
$style=""; 
$con = str_ireplace("2Study",'',strtolower($category['taxonomy_value']));
$con = url_title($con);



$con_f = TEMPLATE_DIR.'images'.DS."icons".DS.$con.'.png';										 
$con_src = false;	 							 
										 if(is_file($con_f)){
											$con_src = TEMPLATE_URL.'images/icons/'.$con.'.png';	
										 }
										 
if(str_replace("2Study",'',$category['taxonomy_value'])=='Insure') {

//echo 'id="scrollbut_3"';

} else if(str_replace("2Study",'',$category['taxonomy_value'])=='Rent') {
//echo 'id="scrollbut_5"';
} else if(str_replace("2Study",'',$category['taxonomy_value'])=='Medical') {
//echo 'id="scrollbut_6"';
} else if(str_replace("2Study",'',$category['taxonomy_value'])=='Travel') {
//echo 'id="scrollbut_2"';
} else {

//echo 'id="scrollbut_'.$i.'"';
}


?>


> 
<div class="text_div" style="">
<? if($con_src != false): ?>
<img src="<? print $con_src ?>" class="cat_scroll_icon" />
<? endif; ?>
<?php

echo  str_replace("2Study",'',$category['taxonomy_value']); 
?>
</div>
 </a> </li>


<? endif; ?>
 
<? $i++; endforeach; ?>













 










                                    
       
                              
                                    
                                    
                                    
                                    
									
                                 <!--   <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_2"></a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_3"> </a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_4"></a> </li>
									
									 <li style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_5"></a> </li>
									 
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_6"></a> </li>
									
                                    <li class="pager" style="width: 125px; float: left; list-style: none outside none;"> <a href="#" id="scrollbut_7"> </a> </li>
									
                                   <li style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_1"> </a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_2"></a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_3"> </a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_4"></a> </li>
									
									 <li style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_5"></a> </li>
									 
                                    <li class="pager" style="width: 133px; float: left; list-style: none outside none;"> <a href="#" id="scrollbut_6"></a> </li>
									
                                    <li class="pager" style=" float: left; list-style: none outside none;"> <a href="#" id="scrollbut_7"> </a> </li>-->
                                    
                                  </ul>
                                </div>
							</div>
                               
			  </div>
                               
		 
  <!--<ul  id="mycarousel" class="jcarousel-skin-tango">-->
  
  
  <?   $i = 1;
  foreach($categories as $category): 
  ?>
<? if($i < 800): ?>

<!--<li class="pager" > <a href="<? print category_url($category['id']); ?>" id="scrollbut_<? print $i; ?>"> </a> </li>
-->

<? endif; ?>
 
<? $i++; endforeach; ?>
  
  
  
  
  
  
  
  
  
  
  
  
  <!--
    <li> <a href="#" id="scrollbut_1"> </a> </li>
    <li class="pager" > <a href="#" id="scrollbut_2"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_3"> </a> </li>
    <li class="pager" > <a href="#" id="scrollbut_4"></a> </li>
    <li > <a href="#" id="scrollbut_5"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_6"></a> </li>
    <li class="pager" > <a href="#" id="scrollbut_7"> </a> </li>-->
<!--  </ul>-->
</div>
