Microweber Documentation
===

Microweber is a new generation drag and drop cms and application framework. 

It can be used to manage your websites or simply to power your custom applications. 


Working with templates
===

## Folder structure:

The templates are stored in the following folders
```
userfiles
-- templates                                              - the main directory for the templates
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
// example template config stored in userfiles/templates/My new theme/config.php

$config = array();
$config['name'] = "Cyborg";
$config['author'] = "Bootswatch";
$config['version'] = 0.1;
$config['url'] = "http://bootswatch.com/cyborg/";

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


PHP Documentation
===


## Folder structure:
```
src
-- Microweber  (this is the app folder)

userfiles
-- media  (folder to store the user pictures)
-- modules 
-- templates 
-- elements 
```


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


Functions and Classes
===

There are is a set of classes and functions that will help you do almost anything.

Microweber may be coded in the OOP way, but **we still love procedural programming** because it offers **short syntax** and **readability**. 

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


