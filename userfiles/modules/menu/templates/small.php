<?php

/*

type: layout

name: Small

description: Small Navigation

*/


$menu_filter['ul_class'] = 'nav nav-small';
$menu_filter['maxdepth'] = 1;

$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	print mw('format')->lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
}



?>


<style type="text/css">

.nav-small {
  overflow: hidden;
  zoom:1;
}

.nav-small > li{
  display: inline;
  float: left;
}



.nav-small > li > a{
  font-size: 12px;
  line-height:16px;
  display: inline-block;
  padding: 0px 3px;
  margin: 0px 3px;
  position: relative;

}

.nav-small > li > a:hover,
#header .nav-small > li > a:hover,
#footer .nav-small > li > a:hover{
  color: #333333;
}


.nav-small li:first-child > a{
  margin-left: 0;
}

.nav-small li:last-child > a{
  margin-right: 0;
}
.nav-small > li:after{
  display: inline-block;
  width: 1px;
  height: 6px;
  content:"";
  position: relative;
  background: white;
}
.nav-small > li:last-child:after{
  display: none;
}

</style>