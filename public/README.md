# Public folder

The files and folders in Laravel's `public` folder are meant to be web accessible. 


For security, all other files and folders in the Laravel framework should not be web accessible.




## Setup your web server
You can read more detailed article on this link 
 [Laravel /public Folder: How to Configure Domains for in Apache/Nginx](https://quickadminpanel.com/blog/laravel-public-folder-how-to-configure-domains-for-in-apachenginx/) 

### PHP Build-in server

```
cd /path/to/public
php -S localhost:8080 index.php
```

### Apache Web server
Set the `DocumentRoot` property to point the `public` folder in your virtual host config 
```
<VirtualHost 127.0.0.9:80>
   DocumentRoot "C:/xampp/htdocs/microweber/public"
</VirtualHost>
```

### Nginx Web server
Set the `root` property to point the `public` folder in your virtual host config 
``` 
server {
    root /var/www/html/project1/public;
    index index.php index.html;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```


### Notes

Microweber has a folder called `userfiles` where users can upload images and files. 

In order to make Microweber running into the `public` folder we made a route inside Laravel to serve the files from the `userfiles` folder.

This can lead to performance degradation as static files are now served by PHP and not by the web server


#### Performance

Its highly recommended to symlink the `userfiles` folder inside `public` folder 

To avoid performance problem with serving the files from `userfiles` folder you can try the following solutions


##### Symlink to the `userfiles` folder
 Make a symlink to the `userfiles` folder inside the `public` folder with 

###### Linux
```cmd
cd /path/to/public
ln -s ../userfiles userfiles
```

###### Windows / Xampp
```
cd C:\xampp\htdocs\microweber\public
mklink /J "userfiles" "../userfiles"
```


##### Use CDN
 Use Content Delivery Network (CDN) to cache the static files.  
 
 
