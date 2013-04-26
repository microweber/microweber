
#PHP Api reference


##Content




---




###Function: get_content
--------
You can use this function get pages, posts or any other content from the database


The function works this way:
`get_content($params)`

The `$params` variable may be a string or array


Usage of `get_content()`

```php
<?php 
//usage with $params as string
$data =  get_content('id=1'); //gets the content with id 1 as array
$data =  get_content('keyword=PHP&author=2'); //search for content with keyword PHP and author with id 2


//usage with params as array()
$params = array();
$params['is_active'] = 'y'; //get only active content
$params['parent'] = 2; //get by parent id
$params['created_by'] = 1; //get by author id
$params['content_type'] = 'post'; //get by content type
$params['subtype'] = 'product'; //get by subtype
$params['title'] = 'my title'; //get by title
$data = get_content($params);
?>
```

Parameters and some of the DB table fields

|parameter  | description |  usage|
|--------------|--------------|--------------|
|`id`  | the id of the page or post | ` get_content('id=1');` |
|`content_type`  | the type of the content, by default supported are `page` or `post`   | ` get_content('content_type=post');` | 
|`parent`   | the id of the parent page | ` get_content('content_type=page&parent=0');` //gets all main pages |
| `category` | id or ids of category  | ` get_content('category=10,11&content_type=post');` | 
|`created_by`  | the id of author | ` get_content('created_by=2');` | 
|`title`  | the title of the content  | ` get_content('title=Home');` | 
|`url`  | the url of the content  | ` get_content('url=about-us');` | 
|`position`  | position of the content  | ` get_content('position=1');` | 
|`is_active`  | get the published content | ` get_content('is_active=y');` | 
|`is_home`  | get the home page | ` get_content('is_home=y');` | 
|`is_deleted`  | get the deleted content | ` get_content('is_deleted=y');` | 
|`subtype`  | get content by sub-type, `post` and `product` are available by default | ` get_content('subtype=product');` | 
|`limit`  | you can limit the number of results | ` get_content('limit=5');` | 
|`order_by`  | set the order of the results | ` get_content('order_by=position desc&limit=5');` | 
|`have`  | this parameter checks if the content is also in certain db tables, some values you can use are `comments`, `categories`, `custom_fields`, `media`, `cart`  and more... | ` get_content('have=comments');`  gets content with comments |


 

###Function: pages_tree
--------
You can use this function to print pages and subpages as UL tree.


The function works this way:
`pages_tree($params)`

the `$params` variable may be a string or array





Usage of `pages_tree()`

```php
<?php 
//usage with $params as string
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
```
 
 
	



##Modules

---
Those functions allows you to work with modules from PHP


###Function: `module_templates`
--------
`module_templates($module_name, $template_name = false)`

Returns all templates for given `$module_name`, 
or it can return the filename for single module template if the second parameter `$template_name` is set at the module template name.

Example to get all templates for the contact_form module

```php
<?php 
//returns array with all module skins
$template_files = module_templates('contact_form');
print_r($template_files);
?>
```

Example to get the filename of the "Dream" skin

```php
<?php 
//returns filename of the skin as string
$skin_file = module_templates('contact_form', 'dream');

//prints filename location such as C:\xampp\htdocs\Microweber\userfiles\modules\contact_form\templates\dream.php
var_dump($skin_file);
?>
```






















#PHP Contstants
--------
#### Directory constants
|constant  | description |   example value|
|--------------|--------------|--------------|
|`DS`  |  the directory separator | `\` - on Windows, `/` on Linux |
|`MW_ROOTPATH`  |  the full directory path of Microweber | `/home/your_user/public_html/` |
|`MW_APPPATH_FULL`  |  the full directory of the `application` folder | `/home/your_user/public_html/application/` |
|`MW_USERFILES`  |  path to the `userfiles` folder | `/home/your_user/public_html/userfiles/` |
|`MODULES_DIR`  |  path to the `userfiles/modules` folder | `/home/your_user/public_html/userfiles/modules/` |
|`TEMPLATEFILES`  |  path to the `userfiles/templates` folder | `/home/your_user/public_html/userfiles/templates/` 
|`CACHEDIR`  |  path to the `cache` folder | `/home/your_user/public_html/cache/` |

#### DB constants
|constant  | description |   example value|
|--------------|--------------|--------------|
|`MW_TABLE_PREFIX`  | database table prefix |  |
 



#### Other constants
|constant  | description |   example value|
|--------------|--------------|--------------|
|`MW_CRON_EXEC`  | it is set when the cronjob executes | not set |
|`MW_BARE_BONES`  | if you set this constant MW can be used just as a framework and be loaded in other files. No output will be generated.  | not set |



#Utility functions
--------

###Function: `get_file_extension`
--------

Gets the extension for given filename




Usage:

```php
<?php
$ext = get_file_extension($filename);
switch ($ext) {
    case 'zip' :
    //do something with zip
    break;
    
    case 'jpg' :
     //do something with jpg
    break;
    
    default :
    break;
}
?>
```


 
