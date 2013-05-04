<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div class="container edit"    rel="page" field="my_content">
    <p class="element">This is the default theme of (MW). </p>
</div>
 
 <h1>module_templates</h1>
<?php

  $template_files = module_templates('contact_form');
//prints array
var_dump($template_files);



$skin_file = module_templates('contact_form', 'dream');


//prints filename location such as C:\xampp\htdocs\Microweber\userfiles\modules\contact_form\templates\dream.php
var_dump($skin_file);


?>
<h1>pages_tree</h1>
Usage pages_tree($params); <br />
<?php
//usage with $params as sting
pages_tree('maxdepth=2'); //prints ul with li


//more complex usage with $params as string
pages_tree('maxdepth=1&ul_class=nav nav-list&include_categories=true');


//usage with params as array()
$pages_params = array();
// setting up the class names of the list
$pages_params['ul_class'] = 'nav nav-list';
$pages_params['ul_class_deep'] = 'nav-list-sub';
$pages_params['li_class'] = 'nav nav-list-item';
$pages_params['li_class_deep'] = 'nav-list-item-sub';


//set parent page
$pages_params['parent'] = '2';
//or you can also use
$pages_params['content_id'] = 2;


// how deep the tree goes
$pages_params['maxdepth'] = '2';
$pages_params['include_categories'] = true;

//if "true" returns the result as string instead of printig it
$pages_params['return_data'] = true;


//advanced params
$pages_params['link'] = "somewhere/{id}/{title}";
$pages_tree= pages_tree($pages_params);
print  $pages_tree;


?>


|parameter  | description |  usage|
|--------------|--------------|--------------|
|`content_id`  | the id of the parent page | ` <module type="pages" content_id="2" />` |
|`maxdepth`  | the depth of the navigation tree | ` <module type="pages" maxdepth="1" />` |
|`include_categories`  | if set will also show the categories for each page | ` <module type="pages" include_categories="1" />` |
|`ul_class`  | you can set the CSS class name for the UL  | ` <module type="pages" ul_class="nav" />` |
|`li_class`  | you can set the CSS class name for the LI  | ` <module type="pages" li_class="nav-item" />` |
|`link`  | allows you to set custom link of each page  | ` <module type="pages" link="{title}/{id}" />` |
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>


  