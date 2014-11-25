<?php

$colors = 'transparent,transparent,transparent,transparent,transparent';
if(isset($_REQUEST['colors'])){
$colors = $_REQUEST['colors'];
}
$colors = explode(',',$colors);
header("Content-type: text/css", true);
 ?>/***************************************************

                    Blue Scheme

****************************************************/

<?php


$hex =  str_replace("#","",$colors[4]);; //Bg color in hex, without any prefixing #!


$r = hexdec(substr($hex,0,2));
$g = hexdec(substr($hex,2,2));
$b = hexdec(substr($hex,4,2));

if($r + $g + $b > 582){
    $textcolor = '#555566';
}else{
    $textcolor = 'white';
}



?>

body{ background-color: #<?php print $colors[0]; ?>; }

#header, #footer, #footer a, #header .edit, #header .edit a{ color: <?php print $colors[0]; ?>; }


#main-menu { background-color: #<?php print $colors[3]; ?>; sssscolor: <?php print $colors[1]; ?>; }

#main-menu a { color: <?php print $colors[1]; ?>; }




.box-container{  background-color: #<?php print $colors[2]; ?>; color: <?php print $textcolor; ?>; padding: 20px; }
.box-container .box-container{  padding: 0; }

/* Header */
#header{ background-color:#<?php print $colors[4]; ?>; }

#logo small{ color: #<?php print $colors[2]; ?>; }

/* /Header */

.mw-cart-action-holder, hr{ border-top-color: rgba(238, 238, 238, 0.38);}
#footer { background: #<?php print $colors[4]; ?>; }
#footer .container .mw-ui-row{ border-top: none; }
#powered, #powered a, #copyright{ color: rgba(255, 255, 255, 0.7); }
.inner-bottom-box{ background: transparent }


/* Modules */

.module-posts-template-sidebar .date{ color: #ABABAB; }
.module-posts-template-sidebar p, .module-posts-template-sidebar .btn-link{ color: #<?php print $colors[3]; ?>; }
.mw-cart-small{ background: #<?php print $colors[4]; ?>; }

.module-navigation-default > ul > li > ul{ padding-top: 0; }

.module-navigation-default li li a{
  border:none;
}

.module-navigation-default a{ background-color: transparent; }

.btn-default,
#header .pagination > a.active, #footer .pagination > a.active, .pagination > .active > a,
.pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover,
.pagination > .active > a:focus, .pagination > .active > span:focus,
.btn-action, .product-price-holder .btn, .btn-action-default,
.btn-action:hover, .product-price-holder .btn:hover, .btn-action-default:hover,
.btn-action:focus, .product-price-holder .btn:focus, .btn-action-default:focus,
.module-navigation-default li:hover a,
.module-navigation-default li a:hover,
.module-navigation-default li a:focus{
  background-color: #<?php print $colors[1]; ?>;
  aaacolor: #<?php print $colors[4]; ?>;
}

.mwcommentsmodule input[type="email"], .mwcommentsmodule textarea, .mwcommentsmodule input[type="text"],
.mwcommentsmodule .comments-template-stylish .comment .comment-content {
   border-color: #efefef;
   background-color: #<?php print $colors[4]; ?>;
}

.module-navigation-default a{
  background: #<?php print $colors[2]; ?>;
  color: #<?php print $colors[0]; ?>;
}

.module-navigation-default li:hover a.active,
.module-navigation-default a.active,
.module-navigation-default a.active:hover,
.module-navigation-default a.active:active,
.module-navigation-default a.active:focus{
  background: #<?php print $colors[1]; ?>;
  color: #<?php print $colors[4]; ?>;
}



