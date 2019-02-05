# Microweber: Drag-and-Drop CMS

**Current version: 1.0 running on Laravel 5!** 


**[Download](https://microweber.com/download.php) |
[What is Microweber?](#what-is) |
[Core features of Microweber](#core-features) |
[Requirements](#requirements) |
[Installation](#installation) |
[Getting Started](#getting-started) |
[Contribute](#contribute)**


---

![Live edit](https://microweber.com/cdn/2019_version/1.jpg "")


## What is Microweber? <a name="what-is" />

Microweber is a Drag and Drop website builder and powerful CMS of new generation. It's based on PHP Laravel Framework. You can use Microweber to make a any kind of website, online store and blog. The Drag and Drop technology allows you to build your website without any technical knowledge.

The core idea of the software is to let you create your own website, online shop or blog. From this moment of creation on, your journey towards success begins. Tagging all along will be different modules, customizations and features of the CMS, among them many specifically tailored for e-commerce enthusiasts and bloggers.

The most important thing you need to know is that Microweber pairs the latest CMS trend � the unique Drag & Drop technology, with a revolutionary Real-Time Text Writing & Editing feature. Talking in user benefit, this pair of features means improved user experience, easier and quicker content management, visually highly appealing environment and flexibility.

## Core features of Microweber <a name="core-features" />

#### Powerful Admin Panel

You can add dynamic pages, posts, products. All of these can be organized in custom categories in order to achieve a better navigation and showcase of a website�s content. New pages can be created using different layouts and all pages, posts and products come with a preset number layouts and modules to get users started. These modules can be changed and you can of course add your own custom set of modules as to create the most suitable content for your needs.

![Live edit](https://microweber.com/cdn/2019_version/2.jpg "")

#### Drag & Drop

Microweber operates on the Drag & Drop technology. This means that users can manage their content and arrange elements with just a click of the mouse, dragging and dropping them across the screen. Drag & Drop applies to all types of content: images, text fields, videos, and the whole variety of modules and additional customization options you have as a user. The default template “Dream” comes with more than 75+ prepared layouts that you can use via drag and drop.

![Drag and Drop](https://microweber.com/cdn/2019_version/7.png "")

#### E-commerce Solution

Perhaps the main focus of Microweber CMS is E-commerce. A rising number of people have grown fond of the idea of online entrepreneurship and we aspire to cover their needs. The software has some built-in features that will help online shop founders see their business grow and excel.

![E-commerce solution](https://microweber.com/cdn/2019_version/3.jpg "")

#### Real Time Text Editing



![E-commerce solution](https://microweber.com/cdn/2019_version/6.png "")

Live Edit view is the manifestation of the Real-Time Text Writing & Editing core feature of Microweber CMS. Working in Live Edit view is in fact working on your website’s interface in real time. 

### [Microweber Live Demo](http://demo.microweber.org/?template=dream)

## Requirements <a name="requirements" />

* HTTP server ([Apache](http://httpd.apache.org/), [IIS](http://www.iis.net/downloads), [nginx](http://nginx.org/en/download.html), etc.)
* Database server
* PHP >= 5.6 or [HHVM](http://docs.hhvm.com/manual/en/install-intro.install.php). The following only apply to PHP as they're included in the HHVM core.
  * `lib-xml` must be enabled (with DOM support)
  * `GD` PHP extension
  * `Mcrypt` PHP extension

*Developer Note: Microweber runs great on HHVM. We highly recommend replacing PHP with HHVM (on Linux environments) or at least try it out if it's new to you.*

### HTTP Server

#### Apache
The `mod_rewrite` module must be enabled in your Apache configuration. Microweber creates the necessary `.htaccess` files during installation, including one with `Deny All` directive in each folder to ensure no entry points other than `index.php`.

#### nginx
Add this `location` directive to your `server` configuration block. The `root` directive must point to the base folder of your Microweber website (which by default is where this readme is located).
```
server {
  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }
}
```

### IIS
You can easily [import the `.htaccess` rewrite rules](http://www.iis.net/learn/extensions/url-rewrite-module/importing-apache-modrewrite-rules). Make sure you have enabled [the URL Rewrite module](http://www.iis.net/learn/extensions/url-rewrite-module/using-the-url-rewrite-module) for your website.

### Database
You have several choices for database engine: MySQL, SQLite, Microsoft SQL Server and PostgreSQL.
For small websites we highly recommend SQLite.
However, you can connect to more storage services (like [MongoDB](https://github.com/jenssegers/laravel-mongodb) or [Neo4j](https://github.com/Vinelab/NeoEloquent)) and really take advantage of the Laravel framework.

On the installation screen you can only choose from databases enabled in your PHP configuration.
If you don't see your server of choice in the list you have to enable the corresponding [PDO](http://php.net/manual/en/book.pdo.php) extension for your database server. [An example for Microsoft SQL Server](http://php.net/manual/en/mssql.installation.php). PHP usually comes with PDO enabled by default but you might have to uncomment or add `extension` directives to your `php.ini`.

## Installation <a name="installation" />

### The fast way: [Download](https://microweber.com/download.php) and unzip.

### Via Composer

#### Installing dependencies

You need to [have Composer installed](https://getcomposer.org/doc/00-intro.md) in order to download Microweber's dependencies.

You can clone and install Microweber with one command:
`composer create-project microweber/microweber my_site dev-master --prefer-dist --no-dev`
This will install Microweber in a folder named `my_site`.

Another way is to first clone the repository and then run `composer install` in the base directory.

#### File permissions
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
