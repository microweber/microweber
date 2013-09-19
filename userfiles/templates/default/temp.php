<?php




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