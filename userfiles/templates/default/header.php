<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">
mw.require("<?php print( INCLUDES_URL); ?>js/jquery.js");
</script>
<meta charset="utf-8">
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">
<link href="{TEMPLATE_URL}/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header id="header">
  <div class="edit"   id="site_title" rel="global">New world</div>
  <div class="edit"   id="site_sub_title" rel="global">Welcome to my website</div>
  <div class="edit"   id="header_quick_links" rel="global">Socialize me</div>
  <div class="edit"   id="header_quick_links_cart" rel="global">
    <module type="shop/cart" id="cart_header" />
  </div>
</header>
<nav>
  <module type="nav" id="main_menu" />
</nav>
