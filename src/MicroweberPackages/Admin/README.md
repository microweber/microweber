
# AdminManager

This class manages the admin panel scripts and styles.

## Adding scripts and styles in admin

In your module, you can add scripts and styles with the following functions:

```php
\MicroweberPackages\Admin\Facades\AdminManager::addScript($id, $src);
\MicroweberPackages\Admin\Facades\AdminManager::addStyle($id, $src);
```

Your id must be unique. Styles with the same id will be overwritten.

```php
\MicroweberPackages\Admin\Facades\AdminManager::addScript('my-module-admin-js', module_url() . 'my-module/js/admin.js');
\MicroweberPackages\Admin\Facades\AdminManager::addStyle('my-module-admin-css', module_url() . 'my-module/css/admin.css');
```

### Adding custom tags

```php
\MicroweberPackages\Admin\Facades\AdminManager::addCustomHeadTag('<script>alert("ok")</script>');
```

## Printing scripts in admin layout 

For example, in your layout, you can print the scripts with the following code:

 ```php
print \MicroweberPackages\Admin\Facades\AdminManager::scripts();    
print \MicroweberPackages\Admin\Facades\AdminManager::styles();    
print \MicroweberPackages\Admin\Facades\AdminManager::customHeadTags();    
```


### Printing all head tags 
  
 ```php
print \MicroweberPackages\Admin\Facades\AdminManager::headTags();    
```



## Adding menu items in admin

```php
class MyModuleServiceProvider extends ServiceProvider
{

    public function registerMenu()
    {
        AdminManager::getMenuInstance('left_menu_top')->addChild('MyModule', [
            'attributes' => [
                'route' => 'admin.my-module.index',
                'icon' => ''
            ]
        ]);
    }

    public function register()
    {
        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);
    }
}
```

 




 

