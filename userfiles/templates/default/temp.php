<?php
$params = array();
$params['parent'] = 0; //parent id
$params['link'] = '<a href="{link}" id="category-{id}">{title}</a>'; // the link on for the <a href
$params['active_ids'] = array(5,6); //ids of active categories
$params['active_code'] = "active"; //inserts this code for the active ids's
$params['remove_ids'] = array(1,2); //remove those caregory ids
$params['ul_class_name'] = 'nav'; //class name for the ul
$params['include_first'] = false; //if true it will include the main parent category
//$params['add_ids'] = array(10,11); //if you send array of ids it will add them to the category
$params['orderby'] = array('created_on','asc'); //you can order by ant field ;
$params['list_tag'] = 'ul';
$params['list_item_tag'] = "li";
  category_tree($params);


return;
$get_user = get_user_by_id(1);
var_dump($get_user); 


return;

/**
 * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array
 * @param int
 * @return array
 * @author      Peter Ivanov
 * @version 1.0
 * @since Version 1.0

function get_category_by_id($id = 0)
{
    return mw('category')->get_by_id($id);

}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false)
{

    return mw('category')->get_children($parent_id, $type, $visible_on_frontend);
}

function get_page_for_category($category_id)
{
    return mw('category')->get_page($category_id);


}

 */






$site_users = get_users();
var_dump($site_users); 


if(!empty($site_users)){
	foreach($site_users as $user){
	print  $category['id'];	
	print  $category['username'];	
	print  $category['email'];	
	
	print  $category['first_name'];	
	print  $category['last_name'];	
 
	print  $category['thumbnail'];	

	print  $category['is_active'];	
	print  $category['is_admin'];	

	}
}


return;

$categories = content_categories(4);

 
if(!empty($categories)){
	foreach($categories as $category){
	$category_url = category_link($category['id']);	
	$category_title = $category['title'];	
	print "<a href='".$category_url."'>".$category_title."</a>";	
	}
}


return;

$is_logged = is_logged();
var_dump($is_logged); 
$user_id = user_id();
var_dump($user_id); 
 
$is_admin = is_admin();
var_dump($is_admin); 
$get_user = get_user();
var_dump($get_user); 
/**
 * @function user_name
 * gets the user's FULL name
 *
 * @param $user_id  the id of the user. If false it will use the curent user (you)
 * @param string $mode full|first|last|username
 *  'full' //prints full name (first +last)
 *  'first' //prints first name
 *  'last' //prints last name
 *  'username' //prints username
 * @return string
 */
 
$user_name = user_name($user_id = false, $mode = 'full');

var_dump($user_name); 

 

/**
 * @function get_users
 *
 * @param $params array|string;
 * @params $params['username'] string username for user
 * @params $params['email'] string email for user
 * @params $params['password'] string password for user
 *
 *
 * @usage get_users('email=my_email');
 *
 *
 * @return array of users;
 */
 
$get_users = get_users();

 
var_dump($get_users); 
 





return;




$currency = currency_format(100, $curr = false);
var_dump($currency);
 
$currency = currency_format(100, "BGN");
var_dump($currency);
 
 return;
$url_segment = url_segment($k = -1, $page_url = 'http://localhost/Microweber/blog/post-title');
var_dump($url_segment);
 
$url_path = url_path($skip_ajax = false);
var_dump($url_path);

 
$url_string = url_string($skip_ajax = false);
var_dump($url_string);
 

$url_current = url_param('my_param', $skip_ajax = false);
var_dump($url_current);

$url_current = url_current($skip_ajax = false, $no_get = false);
var_dump($url_current);

 

 return;

 

$data_to_save = array();
$data_to_save['id'] = 0;
$data_to_save['title'] = 'My title';
$data_to_save['content'] = 'My content body';
$data_to_save['content_type'] = 'page';


$new_content = save_content($data_to_save);
var_dump($new_content);


$new_content = get_content_by_id($new_content);

print_r($new_content);



//get 5 posts
//$posts = get_content('content_type=post&limit=1');
//print_r($posts);
//get next 5 posts
//$posts = get_content('content_type=post&limit=1&page=2');
//print_r($posts);


//$content = get_content();
//print_r($content);
//$posts = get_content('content_type=post');

$products = get_content('content_type=post&subtype=product');
//print_r($products);
/*foreach ($content as $item) {
    print $item['id'];
    print $item['parent'];
    print $item['position'];
    print $item['title'];
    print $item['url'];
    print $item['description'];
    print $item['content'];
    print $item['content_type'];
    print $item['subtype'];
    print $item['created_on'];
    print $item['updated_on'];
    print $item['created_by'];
    print $item['edited_by'];
    print $item['layout_file'];
}*/