
# AdminManager

This class is managing the admin panel scripts and styles

## Adding scripts in admin

In your module you can add scrpts and styles with the following functions

```php
\MicroweberPackages\Admin\Facades\AdminManager::addScript($id, $src);
\MicroweberPackages\Admin\Facades\AdminManager::addStyle($id, $src);
```

Your `id` must be uniquie, styles with the same id will be overwriten 

```php
\MicroweberPackages\Admin\Facades\AdminManager::addScript('my-module-admin-js', module_url() . 'my-module/js/admin.js');
\MicroweberPackages\Admin\Facades\AdminManager::addStyle('my-module-admin-css', module_url() . 'my-module/css/admin.css');
```

## Printing scripts in admin layout 

For example in your layout you can print the scripts with the following code

 ```php
print \MicroweberPackages\Admin\Facades\AdminManager::scripts();    
print \MicroweberPackages\Admin\Facades\AdminManager::styles();    
```

 

