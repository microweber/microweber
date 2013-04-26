
### Inside your module
When you load the module each module have at least *2 defined arrays in it*

The first is `$config`


If you can access various info from the `$config` array and use it inside the module

```php
<?php
//Here is the module $config, if you execute this code inside your module

print_r($config);


//example output displayed in the 'admin/backup' module
Array
(
    [path] => C:\xampp\htdocs\Microweber\userfiles\modules\admin\backup\
    [mp] => C:\xampp\htdocs\Microweber\userfiles\modules\admin\backup\
    [path_to_module] => C:\xampp\htdocs\Microweber\userfiles\modules\admin\backup\
    [the_module] => admin/backup/manage
    [module] => admin/backup/manage
    [module_name] => admin/backup
    [module_name_url_safe] => admin__backup__manage
    [url] => http://192.168.0.3/Microweber/admin/view:modules/load_module:admin__backup?backup_action=manage
    [url_base] => http://192.168.0.3/Microweber/admin/view:modules/load_module:admin__backup
    [url_main] => http://192.168.0.3/Microweber/admin/view:modules/load_module:admin__backup
    [module_api] => http://192.168.0.3/Microweber/api/admin/backup/manage
    [module_view] => http://192.168.0.3/Microweber/module/admin/backup/manage
    [ns] => admin\backup\manage
    [module_class] => module-admin-backup-manage
    [url_to_module] => http://192.168.0.3/Microweber/userfiles/modules/admin/backup/
   
)

?>
```


Modules directories are

    /userfiles/modules - the directory where the modules are installed
    /userfiles/modules/{$module_name} - the directory where each module is located


Other directories


    /application - the MW system directory where the system files are located.
    /application/functions - the main functions of MW
    /userfiles/modules - the directory where the modules are installed
    /userfiles/templates - the directory for the templates

