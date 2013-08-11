<?php

/*

type: layout

name: Fancy kingpro_fancy_3d_banner

description: Fancy kingpro_fancy_3d_banner with CSS3


*/
?>
<style>
@import url(http://fonts.googleapis.com/css?family=Oswald:400);
.kingpro_fancy_3d_banner {
	position: relative;
	z-index: 1;
	margin: 80px auto;
	width: 330px;
	background: #444;
	 
}
.kingpro_fancy_3d_banner .kingpro_fancy_3d_line {
	margin: 0 0 10px;
	width: 100%;
	height: 78px;
	box-shadow: 10px 10px 10px rgba(0,0,0,0.05);
	text-align: center;
	text-transform: uppercase;
	font-size: 3em;
	line-height: 78px;
	transform: skew(0, -15deg);
}
.kingpro_fancy_3d_banner .kingpro_fancy_3d_line:after, .kingpro_fancy_3d_banner .kingpro_fancy_3d_line:first-child:before {
	position: absolute;
	top: 44px;
	left: 0;
	z-index: -1;
	display: block;
	width: 330px;
	height: 78px;
	border-radius: 4px;
	background: rgba(180,180,180,0.8);
	content: '';
	transform: skew(0, 15deg);
}
.kingpro_fancy_3d_banner .kingpro_fancy_3d_line:first-child:before {
	top: -10px;
	right: 0;
	left: auto;
}
.kingpro_fancy_3d_banner .kingpro_fancy_3d_line:first-child:before, .kingpro_fancy_3d_banner .kingpro_fancy_3d_line:last-child:after {
	width: 0;
	height: 0;
	border-width: 38px;
	border-style: solid;
	border-color: rgba(180,180,180,0.8) rgba(180,180,180,0.8) transparent transparent;
	background: transparent;
}
.kingpro_fancy_3d_banner .kingpro_fancy_3d_line:last-child:after {
	top: 12px;
	border-color: transparent transparent rgba(180,180,180,0.8) rgba(180,180,180,0.8);
}
.kingpro_fancy_3d_banner div {
	display: block;
	width: 100%;
	height: 100%;
	border-radius: 4px;
	background: rgba(255,255,255,0.9);
	color: #666;
	text-shadow: 1px 1px 0 #444;
}
</style>
<div class="edit" rel="global" field="layout_<?php print $params['id'] ?>">
  <div class="kingpro_fancy_3d_banner">
    <div class="kingpro_fancy_3d_line"> <div class="element">Fancy banner</div> </div>
    <div class="kingpro_fancy_3d_line"> <div class="element">Make It Look</div> </div>
    <div class="kingpro_fancy_3d_line"> <div class="element">Nice &amp; Classy</div> </div>
  </div>
</div>
