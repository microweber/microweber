### Installation via Command Line

If you haveven't downloaded the zip file get it from here [https://microweber.com/download.php](https://microweber.com/download.php "") 

You can also download Microweber via Composer

```
composer create-project microweber/microweber:dev-filament example_project 
```

You can install Microweber directly from the command line interface. This may be useful in shell scripts that automate the site creation process. 


Here's an example of what the command looks like:

```bash
php artisan microweber:install --email=admin@example.com --username=admin --password=password --db-name=storage/database.sqlite --db-password=nopass --db-driver=sqlite --db-prefix=site_ --template=Bootstrap --default-content=1
```



This would initialize the Microweber database on localhost in database "site_db" using user "root" with password "secret" for the connections. In case the database user doesn't have a password you can skip setting that argument (and also be ashamed of your attitude to security). All tables will be prefixed by "site_". After the schema initialization an admin user will be created with credentials "admin"/"pass" and email "admin@site.com".
All arguments until the database password are required and need to be present in that exact order.

 

#### Arguments:
| Argument | Description
|----------| ---
| email    | Admin account email
| username | Admin account username
| password | Admin account password
| db-host  | Database host address
| db-name  | Database schema name
| db-username  | Database username
| db-driver  | Database driver 
| db-password  | Database password (optional)
| db-prefix  | Database table prefix (optional)
| db-prefix  | Database table prefix (optional)
| template | Set the template name
| default-content | Set to install default content
| config-only | Set to prepare configuration without install
| language | Set the language of the install
 

#### Laravel Options:
|      Option  | Description
|          --- | ---
|--help (-h)   | Display this help message.
|--quiet (-q)  | Do not output any message.
|--env         | The environment the command should run under.



#### Install Examples 

### Sqlite
 
``` bash

php artisan microweber:install --email=admin@example.com --username=admin --password=mypassword --db-name=storage/database.sqlite --db-password=nopass --db-driver=sqlite --db-prefix=site_ --template=Bootstrap --default-content=1

```

### Mysql

``` bash
php artisan microweber:install --email=admin@example.com --username=admin --password=mypassword --db-host=127.0.0.1 --db-name=microweber --db-username=dbuser --db-password=dbpass --db-driver=mysql --db-prefix=site_ --template=Bootstrap --default-content=1
```




#### Config only, and let user to complete the install from browser

To let the user complete the intall from browser and select a template you must pass the parameter `--config-only=1` to the install script. 

``` bash
php artisan microweber:install --config-only=1 --email=admin@example.com --username=admin --password=mypassword --db-name=storage/database.sqlite --db-password=nopass --db-driver=sqlite --db-prefix=site_ --template=Bootstrap --default-content=1
```

#### Multi domain scripted installation
To make multi domain install you must create an empty folder within the `config` folder with the name of your domain and put empty file at `config/example.com/microweber.php`

Then on the scriptted install you must pass the domain name as a `--env` parameter. For example: 


``` bash
php artisan microweber:install --env=example.com  --config-only=1 --email=admin@example.com --username=admin --password=mypassword --db-name=storage/database.sqlite --db-password=nopass --db-driver=sqlite --db-prefix=site_ --template=Bootstrap --default-content=1
```

#### Update command

`php artisan microweber:update`

