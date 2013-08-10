Microweber Documentation
===

Microweber is a new generation drag and drop cms and application framework. 

It can be used to manage your websites or simply to power your custom applications. 


Basics
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



## Autloader

The autoloader will require the needed classes automatically when you ask for them. 

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
You can think of this class as a container of your app and dependency injector. 

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