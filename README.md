# Microweber: Drag-and-Drop CMS

**Current version: 1.0 running on Laravel 5!** 

<center>
**[Download](https://microweber.com/download.php) |
[What is Microweber?](#what-is) |
[Requirements](#requirements) |
[Installation](#installation) |
[Getting Started](#getting-started) |
[Contribute](#contribute)**
</center>
---

![Live edit](https://microweber.com/cdn/microweber_screen_1.jpg "")


## What is Microweber? <a name="what-is" />

Microweber is a new generation content management system that allows you to create a website using drag and drop.
You can easily manipulate the content and the layout of your pages. No coding skills are required.

### [Live Demo](http://demo.microweber.org/admin?username=demo&password=demo)

## Requirements <a name="requirements" />

### General Requirements
* HTTP server ([Apache](http://httpd.apache.org/), [IIS](http://www.iis.net/downloads), [nginx](http://nginx.org/en/download.html), etc.)
* Database server
* PHP >= 5.4 or [HHVM](http://docs.hhvm.com/manual/en/install-intro.install.php). The following only apply to PHP as they're included in the HHVM core.
  * `lib-xml` must be enabled (with DOM support)
  * `GD` PHP extension
  * `Mcrypt` PHP extension

*Developer Note: Microweber runs great on HHVM. We highly recommend replacing PHP with HHVM (on Linux environments) or at least try it out if it's new to you.*

### HTTP Server

#### Apache
The `mod_rewrite` module must be enabled in your Apache configuration. Microweber creates the necessary `.htaccess` files during installation if you're running on Apache.

### IIS
You can easily [import the `.htaccess` rewrite rules](http://www.iis.net/learn/extensions/url-rewrite-module/importing-apache-modrewrite-rules). Make sure you have enabled [the URL Rewrite module](http://www.iis.net/learn/extensions/url-rewrite-module/using-the-url-rewrite-module) for your website.

#### nginX
Add this `location` directive to your `server` configuration block. The `root` directive must point to the base folder of your Microweber website (which by default is where this readme is located).
```
server {
  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }
}
```

### Database
You have several choices for database engine: MySQL, SQLite, Microsoft SQL Server and PostgreSQL.
For small websites we highly recommend SQLite.
However, you can connect to more storage services (like [MongoDB](https://github.com/jenssegers/laravel-mongodb) or [Neo4j](https://github.com/Vinelab/NeoEloquent)) and really take advantage of the Laravel framework.

On the installation screen you can only choose from databases enabled in your PHP configuration.
If you don't see your server of choice in the list you have to enable the corresponding [PDO](http://php.net/manual/en/book.pdo.php) extension for your database server. [An example for Microsoft SQL Server](http://php.net/manual/en/mssql.installation.php). PHP usually comes with PDO enabled by default but you might have to uncomment or add `extension` directives to your `php.ini`.

## Installation <a name="installation" />

### The fast way: [Download](https://microweber.com/download.php) and unzip.

### Via composer

#### Installing dependencies

You need to [have Composer installed](https://getcomposer.org/doc/00-intro.md) in order to download Microweber's dependencies.

You can clone and install Microweber with one command:
`composer create-project microweber/microweber my_site dev-master --prefer-dist --no-dev`
This will install Microweber in a folder named `my_ste`.

Another way is to first clone the repository and then run `composer install` in the base directory.

### File Permissions
Make sure these folders, and everything inside, is writeable by the user executing the PHP scripts:
* config/
* src/
* storage/
* userfiles/

## Getting Started <a name="getting-started" />

See the [online guides](http://microweber.com/docs/guides/README.md) for developers.

## Contribute <a name="contribute" />
We are looking for people who want to help us improve Microweber. 

If you are a developer, submitting fixes is easy. Just fork the Microweber repository, make your changes and submit a pull request.

## Build Status
[![Build Status](https://api.travis-ci.org/microweber/microweber.svg)](https://travis-ci.org/microweber/microweber)
