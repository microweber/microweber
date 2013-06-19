# Microweber is Drag-and-Drop system for making websites

Microweber is a content management system of new generation that allows you to make your website by drag and drop.

You can easily manipulate the content and the layout of your pages without the need to write code.

[Try the demo here](http://demo.microweber.org/admin?username=demo&password=demo)




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

After installation you can login in the admin panel from http://you_site_url/admin

For developers:
there is little [documentation available here](http://help.microweber.com/apidocs/ "")
