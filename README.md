# Microweber: Drag-and-Drop CMS

**Current version: 1.3 running on Laravel 8!**


**[Download](https://microweber.com/download.php) |
[What is Microweber?](#what-is) |
[Core features of Microweber](#core-features) |
[Requirements](#requirements) |
[Installation](#installation) |
[Getting Started](#getting-started) |
[Contribute](#contribute)**


---


## What is Microweber?  


![admin panel](https://microweber.org/userfiles/media/microweber.org/dashboard-1_17.jpg "")


Microweber is a Drag and Drop website builder and a powerful next generation CMS. It's based on the PHP Laravel Framework. You can use Microweber to make any kind of website, online store, and blog. The Drag and Drop technology allows you to build your website without any technical knowledge.

The core idea of the software is to let you create your own website, online shop or blog. From this moment of creation, your journey towards success begins. Supporting you along the way will be different modules, customizations and features of the CMS. Many of them are specifically tailored for e-commerce enthusiasts and bloggers.

The most important thing you need to know is that Microweber pairs the latest Drag & Drop technology, with a revolutionary Real-Time Text Writing & Editing feature. This pair of features delivers an improved user experience, easier and quicker content management, a visually appealing environment, and flexibility.


## Core features of Microweber  


#### Drag & Drop

Microweber implements Drag & Drop technology. This means that users can manage their content and arrange elements with just a click of the mouse, dragging and dropping them across the screen. Drag & Drop applies to all types of content: images, text fields, videos, and the various modules and customization options you have as a user. The default template “Dream” comes with more than 75+ prepared layouts that you can use via drag and drop.

![Drag And Drop](https://microweber.com/cdn/2019_version/Drag_Drop_CMS_Microweber.gif "")



#### Real Time Text Editing

Live Edit view is the manifestation of the Real-Time Text Writing & Editing core feature of Microweber CMS. Live Edit view changes your website’s interface in real time.

![E-commerce solution](https://sitestatic.microweber.com/cdn/gh_readme/homepage-2018-third-section.gif "")



#### Powerful Admin Panel

You can add dynamic pages, posts, and products. All of these can be organized in custom categories in order to achieve a better navigation and showcase of a website's content. New pages can be created using different layouts. In addition, all pages, posts and products come with a number of preset layouts and modules to get users started. These modules can be changed and you can add your own custom set of modules in order to create the most suitable content for your needs.

![Powerful Admin Panel](https://microweber.com/cdn/2019_version/2.jpg "")



#### E-commerce Solution

The main focus of Microweber CMS is E-commerce. A rising number of people have grown fond of the idea of online entrepreneurship and we aspire to cover their needs. The software has some built-in features that will help online shop founders see their business grow and excel.

![E-commerce Solution](https://microweber.com/cdn/2019_version/3.jpg "")

![Give a star to Microweber](https://microweber.com/cdn/2019_version/Star-Microweber.gif "")


## See it in action

* [Microweber Live Demo](https://demo.microweber.org/?template=dream)
* [Microweber Video](https://sitestatic.microweber.com/userfiles/templates/mw/videos/1.mp4)
* [Deploy as DigitalOcean 1-Click App](https://marketplace.digitalocean.com/apps/microweber?action=deploy&refcode=83e0646738fe)
* [Deploy as alwaysdata 1-Click App](https://www.alwaysdata.com/en/marketplace/microweber/)


## Requirements  

* HTTP server  
* Database server
* PHP >= 7.3
  * `lib-xml` must be enabled (with DOM support)
  * `GD` PHP extension

### HTTP Server

#### Apache
The `mod_rewrite` module must be enabled in your Apache configuration. Microweber creates the necessary `.htaccess` files during installation, including one with `Deny All` directive in each folder to ensure that there are no entry points other than `index.php`.

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
However, you can connect to more storage services (like [MongoDB](https://github.com/jenssegers/laravel-mongodb) or [Neo4j](https://github.com/Vinelab/NeoEloquent)) and take advantage of the Laravel framework.

On the installation screen you can only choose from databases enabled in your PHP configuration.
If you don't see your server of choice in the list you have to enable the corresponding [PDO](http://php.net/manual/en/book.pdo.php) extension for your database server. [An example for Microsoft SQL Server](http://php.net/manual/en/mssql.installation.php). PHP usually comes with PDO enabled by default but you might have to uncomment or add `extension` directives to your `php.ini`.

## Installation  

### The fast way: [Download](https://microweber.com/download.php) and unzip.

### Via Composer

#### Installing dependencies

You need to [have Composer installed](https://getcomposer.org/doc/00-intro.md) in order to download Microweber's dependencies.

You can clone and install Microweber with one command:

```
composer create-project microweber/microweber my_site dev-master --prefer-dist --no-dev
```


This will install Microweber in a folder named `my_site`.

Another way is to first clone the repository and then run `composer install` in the base directory.

#### File permissions
Make sure these folders, and everything inside, is writeable by the user executing the PHP scripts:
* config/
* storage/
* userfiles/

## Getting Started  

See the [online guides](http://microweber.com/docs/guides/README.md) for developers.

## Contribute
We are looking for people who want to help us improve Microweber.

If you are a developer, submitting fixes is easy. Just fork the Microweber repository, make your changes, submit a pull request, and be sure all tests are passing.

## Build Status

![PHP Unit Tests](https://github.com/microweber/microweber/actions/workflows/ci.yml/badge.svg)
[![codecov](https://codecov.io/gh/microweber/microweber/branch/dev/graph/badge.svg?token=aLAgaSMcbZ)](https://codecov.io/gh/microweber/microweber)


## Contributors

### Code Contributors

This project exists thanks to all the people who contribute. [[Contribute](CONTRIBUTING.md)].
<a href="https://github.com/microweber/microweber/graphs/contributors"><img src="https://opencollective.com/microweber/contributors.svg?width=890&button=false" /></a>

### Financial Contributors

Become a financial contributor and help us sustain our community. [[Contribute](https://opencollective.com/microweber/contribute)]

#### Individuals

<a href="https://opencollective.com/microweber"><img src="https://opencollective.com/microweber/individuals.svg?width=890"></a>

#### Organizations

Support this project with your organization. Your logo will show up here with a link to your website. [[Contribute](https://opencollective.com/microweber/contribute)]

<a href="https://opencollective.com/microweber/organization/0/website"><img src="https://opencollective.com/microweber/organization/0/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/1/website"><img src="https://opencollective.com/microweber/organization/1/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/2/website"><img src="https://opencollective.com/microweber/organization/2/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/3/website"><img src="https://opencollective.com/microweber/organization/3/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/4/website"><img src="https://opencollective.com/microweber/organization/4/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/5/website"><img src="https://opencollective.com/microweber/organization/5/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/6/website"><img src="https://opencollective.com/microweber/organization/6/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/7/website"><img src="https://opencollective.com/microweber/organization/7/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/8/website"><img src="https://opencollective.com/microweber/organization/8/avatar.svg"></a>
<a href="https://opencollective.com/microweber/organization/9/website"><img src="https://opencollective.com/microweber/organization/9/avatar.svg"></a>
