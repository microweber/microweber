<?php

$config = array();
$config['name'] = "New World";
$config['author'] = "Microweber";
$config['version'] = 0.1;
$config['is_hidden_from_install_screen'] = true;
$config['standalone_module_skins'] = true;

$config['content'] = array();
$config['content'][] = array(
    'title' => "Home",
    'content_type' => "page",
    'layout_file' => "index.php",
    'is_home' => 1
);

$config['content'][] = array(
    'title' => "Blog",
    'content_type' => "page",
    'subtype' => "dynamic",
    'layout_file' => "layouts/blog.php",
    'is_shop' => 0
);
$config['content'][] = array(
    'title' => "Blog Post",
    'content_type' => "post",
    'layout_file' => "layouts/blog_inner.php"
);

$config['content'][] = array(
    'title' => "Product name",
    'content_type' => "product",
    'layout_file' => "layouts/shop_inner.php"
);

$config['content'][] = array(
    'title' => "Shop",
    'content_type' => "page",
    'subtype' => "dynamic",
    'layout_file' => "layouts/shop.php",
    'is_shop' => 1
);