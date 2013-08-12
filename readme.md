# Microweber: the Drag-and-Drop CMS

Microweber is a content management system of new generation that allows you to make your website by drag and drop.

You can easily manipulate the content and the layout of your pages without the need to write code.

[Try the demo here](http://demo.microweber.org/admin?username=demo&password=demo)

[Download the latest version from here](https://github.com/microweber/microweber/archive/master.zip "")



# How to install


The following server requirements are needed:

* Apache web server or IIS
* PHP 5.3 or above
* MySQL 5 or above
* short_open_tag must be set to "on" in php.ini
* mod_rewrite must be enabled
* lib-xml must be enabled, with DOM support
* GD php extension must be loaded



Unzip and upload the files in your server folder and
open your browser to the index.php file

If there is no config.php file in your folder
Microweber will make new config.php for you when you install it

Microweber will also create or modify your .htaccess file on install

After installation you can login in the admin panel from **http://you_site_url/admin**







For developers:
===

Microweber is a new generation drag and drop cms and application framework. 

It can be used to manage your websites or simply to power your custom applications. 


Working with templates
===

We use [Twitter's Bootstrap](http://getbootstrap.com/ "Bootstrap") framework as a base for our templates. If you know Bootstrap you can already make Microweber templates.

You can plug and play any existing bootstrap theme out there with [3 lines of code](https://github.com/microweber/microweber/blob/master/userfiles/templates/cyborg/header.php ""). Just copy [this folder](https://github.com/microweber/microweber/tree/master/userfiles/templates/cyborg "") and rename it, no need of futher coding.


Of course you can also use you own CSS code. 

## Folder structure:



The templates are stored in the following folders
```
src
-- Microweber  (this is the app folder)

userfiles
-- media  (folder to store the user pictures)
-- modules 
-- elements 
-- templates 

userfiles/templates
-- templates/My theme                                     - directory for your template
-- templates/My theme/layouts                             - the directory for your layouts
-- modules/{$module_name}/templates/                      - each module's default skins
-- templates/My theme/modules/{$module_name}/templates/   - custom modules skins for your template

```


**Requred template files**

Each template must have the following files under its directory
```
userfiles
-- templates
    --  My new theme
    	config.php
    	header.php
    	footer.php
    	index.php
    	layouts/clean.php
```

**Create template**

To create a template make a `config.php` file in its directory and put your details

```php
// example template config stored in userfiles/templates/new_theme/config.php

$config = array();
$config['name'] = "My new theme";
$config['author'] = "Me";
$config['version'] = 0.1;
$config['url'] = "http://example.com";

```

After that your template should be visible in the admin panel.
[See how default template is made](https://github.com/microweber/microweber/tree/master/userfiles/templates/default "")


####Sample header.php
```php
<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
    <title>{content_meta_title}</title>
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.css" type="text/css" media="all" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="{TEMPLATE_URL}js/functions.js" type="text/javascript"></script>
</head>
<body>
<div id="header" class="edit" field="header" rel="global">
     <module data-type="menu" data-template="navbar">
</div> 
    <div id="content">
```






####Sample footer.php
```php
    </div> <!-- /#content -->

<div id="footer" class="edit" field="footer" rel="global">
     <div class="row">
        <div class="span6">All rights reserved</div>
        <div class="span6"> </div>
    </div>
</div> <!-- /#footer -->

</body></html>
```


####Sample index.php
```php
<?php
/*
  type: layout
  content_type: static
  name: Home
  description: Home layout
  
*/
?>
<?php include TEMPLATE_DIR. "header.php"; ?>
    <div class="edit" field="content" rel="content">
    My editable content
    </div>
<?php include TEMPLATE_DIR. "footer.php"; ?>

```


## Live edit
You can define editable regions in your template where the user will be able to type text and *Drag and Drop* modules


Example:
```html
<div class="edit"  field="your_region_name" rel="content">
      <div class="element">Edit your content</div>
</div>
```

To define your editable region you must set few parameters on the html element in your template

* You must add class "edit"
* Add attribute *field="some name"* and set the name of your field.
* The main editable regionmust have  *field="content"*
* Add attribute *rel="content"* and set the scope of your field.
    * *rel="content"* this field changes for ever page or post
    * *rel="global"* this field changes for the whole site
    * *rel="page"* this field changes for every page
    * *rel="post"* this field changes for every post
    * *rel="inherit"* this field changes for every main page, but not is sup-pages and posts
    * *rel="your_cutom_rel"* you can define your own scope


# Modules


The Microweber modules will help you to make easy modifications and add functionality to your pages.

Every module is a PHP script or a program that executes when the user have dropped it into a page.
It can be very simple, or it can be hugely complex, there are no limitations.


The module works as a stand alone script, but it have access to all Microweber functions.

It can also have editable regions and the user can edit the text into it.


Modules are loaded with the \<module /> tag and each of them is located in `userfiles/modules/{$module_name}`: 
```html

      <module type="pictures" />

```


Functions reference
===


Microweber's core is coded in the OOP way, but **we still love procedural programming** because it offers **short syntax** and **readability**. 

The functions below are just short-cuts to the corresponding OOP object and method. You can override them by extending the class that is responsible for each function. 

## DB Functions


### function: *get($params)*


Allows you to get and filter data from the db, and caches the result.  

Usage of the `get($params)` function

 
```php
//you can pass params as string
$data = get('table=my_table&id=5');

//or as array 
$get_params = array('table'=>'content','id'=>5);
$data = get($get_params);

```

Parameters

|parameter  | description |  usage|
|--------------|--------------|--------------|
|`table`  |  your database table | `get('table=content')` |
|`single`  |  if set to true will return only the 1st row as array | `get('table=content&id=5&single=true')` |
|`orderby`  |  you can order by any field name | `get('table=content&orderby=id desc')` |
|`count`  | if set to true it will return the results count | `get('table=content&count=true')` |
|`limit`  | set limit of the returned dataset  | `get('table=content&limit=10')` |
|`curent_page`  | set offset of the returned dataset  | `get('table=content&limit=10&curent_page=2')` |
| $fieldname  |  you can filter data by passing your fields as params| `get('table=content&my_field=value')` |




### function: *save($table, $data)*


Allows you to save in the database

Usage of the `save($table, $data)` function

 
```php
$data = array();
$data['id'] = 0;
$data['title'] = 'My title';
$data['content'] = 'My content';
$saved_id = save('content',$data);
```

Parameters

|parameter  | description |  usage|
|--------------|--------------|--------------|
|`$table`  | the name of your database table | `save('my_table',$data)`, `save('users',$data)` |
|`$data`  | a key=>value array of your data to save | `$saved_id = save('content',array('id'=>5,'title'=>"My title"));` |
 







## Content Functions

Those functions will help you work with the items from the `content` db table.

### function: *get_content($params)*


Get array of content items (posts,pages,etc) from the content DB table

```php
//you can pass params as string
$data = get_content('is_active=y');

//or pass params as as array 
$params = array();
$params['is_active'] = 'y'; //get only active content
$params['parent'] = 2; //get by parent id
$params['created_by'] = 1; //get by author id
$params['content_type'] = 'post'; //get by content type
$params['subtype'] = 'product'; //get by subtype
$params['title'] = 'my title'; //get by title
$data = get_content($params);

//Order by position
$data = get_content('content_type=post&is_active=y&order_by=position desc');

//Order by date
$data = get_content('content_type=post&is_active=y&order_by=updated_on desc');
 
//Order by title
$data = $get_content('content_type=post&is_active=y&order_by=title asc'); 

//Get content from last week
$data = get_content('created_on=[mt]-1 week&is_active=y&order_by=title asc');
 
```

Parameters

|parameter  | description |  usage|
|--------------|--------------|--------------|
| id       | the id of the content|
| is_active | published or unpublished  | "y" or "n"
| parent    | get content with parent   | any id or 0
| created_by| get by author id| any user id
| created_on| the date of creation | `strtotime` compatible date
| updated_on| the date of last edit| `strtotime` compatible date
| content_type   | the type of the content   | "page" or "post", anything custom
| subtype   | subtype of the content    | "static","dynamic","post","product", anything custom
| url  | the link to the content   |
| title| Title of the content |
| content   | The html content saved in the database |
| description    | Description used for the content list |
| position  | The order position   |
| active_site_template   | Current template for the content |
| layout_file    | Current layout from the template directory |
| is_deleted| flag for deleted content  |  "n" or "y"
| is_home   | flag for homepage    |  "n" or "y"
| is_shop   | flag for shop page   |  "n" or "y"





### function: *get_content_by_id($id)*

Does what it says - get content by id from the content db table

```php
$single_content = get_content_by_id($id=5);
```

### function: *content_link($id)*
Return the url for a page or a post
```php
$link = content_link($id=5);
print $link;
```

### function: *content_parents($id)*
Returns array of parents ids
```php
$link = content_parents($id=5);
print $link;
```
### function: *pages_tree($params)*
Prints nested tree of pages and sub-pages

```php
// Example Usage:
$pt_opts = array();
$pt_opts['link'] = "<a href='{link}'>{title}</a>";
$pt_opts['list_tag'] = "ol";
$pt_opts['list_item_tag'] = "li";
pages_tree($pt_opts);

// Example Usage to make <select> with <option>:
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['active_ids'] = 5; //those items will have the selected attribute
$pt_opts['active_code_tag'] = '   selected="selected"  ';
$pt_opts['ul_class'] = 'nav';
$pt_opts['li_class'] = 'nav-item';
print '<select>';
pages_tree($pt_opts);
print '</select>';

// Other options
$pt_opts['parent'] = "8";
$pt_opts['include_first'] =  true; //includes the parent in the tree
$pt_opts['id_prefix'] = 'my_id';
$pt_opts['max_level'] =  2; //the max nesting level of the tree
$pt_opts['include_categories'] =  true; //includes the categories in the tree
$pt_opts['active_class'] =  'active'; // set your own class name of the active item


// Placeholders you can use
// {id}, {title}, {link}, {tn}, {active_class}, 
// {active_parent_class}, {exteded_classes}, {nest_level}
```

## Category Functions

Those functions works with the the `categories` db table.

### function: *get_categories_for_content($content_id)*
 
Returns array of category ids
```php
$categories = get_categories_for_content($content_id=5);
print_r($categories);
``` 


### function: *get_categories($params)*
 
Returns array of categories
```php
//get main categories for the content
$categories = get_categories('rel=content&parent_id=0&orderby=position asc');
 
//you can use the categories functuonality to for your custom data with the `rel` parameter
$modules_categories = get_categories('rel=modules&parent_id=0');

``` 

Parameters

|parameter  | description |  usage|
|--------------|--------------|--------------|
| id       | the id of the category| `get_categories('id=3');`
| parent_id | the id of the parent category | `get_categories('parent_id=0');`
| rel | the category relation to other db table | `get_categories('rel=content');`
| rel_id | the item from the related db table | `get_categories('rel=content&rel_id=5');` gets categories for the content with id 5
| created_by| get by author id|  `get_categories('created_by=1');`
| created_on| the date of creation | `strtotime` compatible date
| updated_on| the date of last edit| `strtotime` compatible date
| title| Title of the category |
| content   | The html content saved in the database |
| description    | Description used for the content list |
| position  | The order position   |
| users_can_create_content   | flag if users can add content in this category  |  "n" or "y"


### function: *get_category_by_id($id)*

Get single category by id

```php
$category = get_category_by_id($id=1);
```


### function: *get_category_children($id)*

Get all sub-categories

```php
$category_children = get_category_children($id=1);
```



### function: *category_link($id)*

Get link for a category

```php
$category_url = category_link($id=1);
```



### function: *category_tree($params)*

Prints nested tree of categories and sub-categories

[See also pages_tree()](#function-pages_treeparams "pages_tree")

```php

$params = array();
$params['parent'] = 0; //parent id
$params['link'] = '<a href="#{id}">{title}</a>'; // the link on for the <a href
$params['active_ids'] = array(5,6); //ids of active categories
$params['active_code'] = "active"; //inserts this code for the active ids's
$params['remove_ids'] = array(1,2); //remove those caregory ids
$params['ul_class_name'] = 'nav'; //class name for the ul
$params['include_first'] = false; //if true it will include the main parent category
$params['add_ids'] = array(10,11); //if you send array of ids it will add them to the category
$params['orderby'] = array('created_on','asc'); //you can order by ant field ;
$params['list_tag'] = 'select';
$params['list_item_tag'] = "option";
category_tree($params);
```








MVC Framework (For advanced users)
===
## Starting

To run mw you must include the `\src\Microweber\bootstrap.php` file which loads up the system. 

This file also contains the *autoloader* which load the classes of the app 

```php
//from index.php
 define('MW_ROOTPATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
require_once (MW_ROOTPATH . 'src/Microweber/bootstrap.php');

$application = new \Microweber\Application($path_to_config);

// get stuff
$content = $application->content->get("is_active=y");
var_dump($content);

```



## Autoloader

The autoloader will include the needed classes automatically when you ask for them. 

There is no need to create any class. It will be done for you on the fly.

By default the autoloader looks in the `\src\Microweber` folder, but you can change that.

```php
// You can add your custom classes folders
// and completely override and replace any mw class

$my_classes = MW_ROOTPATH . 'src/Microweber/examples/classes/';
autoload_add($my_classes);

//you can add as many directories as you want
autoload_add($dirname);

```


## The Application Class
You can think of this class as a constructor of your app and dependency injector. 

It provides properties of the application via the autoloader with the magic methods `__get()` and `__set()`

For example:

When you call the function `$application->content->get()` the class `Content` will be loaded which provides the `get()` method


 
## Controllers and Routing
Define a controller for your application and route to its methods
 
``` php

// Starting App
$application = new \Microweber\Application();

// Starting Controller
$controller = new \Microweber\Controller($application);

// Starting Router
$router = new \Microweber\Router();

// Automatically map the Router to all controller functions
$router->map($controller);

// Extend and override the Controller
$controller->hello_world = function () {
    echo "Hello world!";
};

// Map more complex routes with regex, the Router is using preg_match
$controller->functions['test/route/*'] = function () {
    echo "You can use wildcards!";
};

// Run the website
$router->run();

```


## Views 

As any MVC framework, Microweber allows you to separate the page layout from the "business logic" of your application with Views

The Views are simple php or html files that hold the layout of the information you want to display

You can see working example in the file [src/Microweber/examples/creating_views.php](https://github.com/microweber/microweber/blob/master/src/Microweber/examples/creating_views.php "")

**Creating a view**


```php

 $layout = new \Microweber\View('full_path_to_file.php');
 $layout->content = 'Hello world!';
 $layout->another_variable = array('test', 'test2');
  
 // display output with $layout->display(); or print it
 print $layout;

```

**Calling from controller**

You can call views from the *any file* or from your custom controller

```php

$controller->my_view = function () {
    $view = new  \Microweber\View($full_path_to_file);
    $view->set('content', 'I assigned variable to a view!');

    print $view;
}; 

```
 **PHP as a template language**
 
We use plain php for the templates and you have all needed the flexibility with it


Classes
===

There are is a set of classes and functions that will help you do almost anything.

Microweber's core is coded in the OOP way, but **we still love procedural programming** because it offers **short syntax** and **readability**. 

For this reason **we provide a procedural way of calling the same OOP methods** by alias functions.


###Database
 

Get and save data in the DB. You must configure your database access in index.php

You need to create your db table first. 




#### Get from the database 
```php
//get data

// OOP Way
$data = $application->db->get('table=my_table');

//filter data
$data = $application->db->get('table=my_table&url=my-url&order_by=title desc');

//limit and paging
$data = $application->db->get('table=my_table&limit=5&curent_page=1');

//Procedural
$data = get('table=my_table&id=5');

```


#### Save to the database 
```php
$data = array();
$data['title'] = 'My title';
$data['content'] = 'My content';
$data['url'] = 'my-link';

//OOP way
$saved_id = $application->db->save('my_table',$data);

//Procedural
$saved_id = save('my_table',$data);
```

You can see working example in the file [src/Microweber/examples/get_save_data.php](https://github.com/microweber/microweber/blob/master/src/Microweber/examples/get_save_data.php "")
 

#### Create database table
```php
//create db table
$table_name = MW_TABLE_PREFIX . 'my_table'

$fields_to_add = array();
$fields_to_add[] = array('title', 'longtext default NULL');
$fields_to_add[] = array('url', 'longtext default NULL');
$fields_to_add[] = array('content', 'TEXT default NULL');

//OOP way
$create = $application->db->build_table($table_name, $fields_to_add);


//Procedural
$data = db_build_table($table_name, $fields_to_add);
```

###Cache
Get and save data in the cache system. 

```php
$cache_id = 'my_id';
$cache_group 'my_group';


//get data - OOP way
$cached_data = $application->cache->get($cache_id,$cache_group);


//get data - Procedural
$data = cache_get($cache_id,$cache_group);


//save data
$data = array('something'=>'some value');

//OOP way
$application->cache->save($data, $cache_id, $cache_group);

//Procedural
$data = cache_save($data, $cache_id, $cache_group);

```


There is a lot more. We are constantly updating this document.

