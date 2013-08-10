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

To run mw you must include the `\src\Microweber\bootstrap.php` file which loads up the system and includes other files. 

This file also contains the *autoloader* which load the classes of the app 

```php
//from index.php

require_once (MW_ROOTPATH . 'src/Microweber/bootstrap.php');

$application = new \Microweber\Application($path_to_config);

// get stuff
$content = $application->content->get("is_active=y");
var_dump($content);

```


 
## Routing

 
``` php
 
```