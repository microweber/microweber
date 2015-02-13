# Microweber: Drag-and-Drop CMS
---

Now runs on Laravel 5!

![Live edit](http://microweber.com/cdn/microweber_screen_1.jpg "")

## What is Microweber?

Microweber is a new generation content management system that allows you to create a website using drag and drop.
You can easily manipulate the content and the layout of your pages. No coding skills are required.

### [Live Demo](http://demo.microweber.org/admin?username=demo&password=demo)
### [Download](https://microweber.com/download.php)

## Getting Started

Check out our [online guides](http://lab.microweber.com/l5/microweber-docs/guides/)

## Installation

You need to [have Composer installed](https://getcomposer.org/doc/00-intro.md) in order to download Microweber's dependencies (including [the Laravel framework](http://laravel.com/)).

* Via Composer

`composer create-project microweber/microweber my_site 1.0.x-dev --prefer-dist --no-dev`

* Clone The Repository

Clone this repository (or download as zip) on your server and run `composer install` in the base folder.

## Requirements

### General Requirements
* HTTP server ([Apache](http://httpd.apache.org/), [IIS](http://www.iis.net/downloads), [nginx](http://nginx.org/en/download.html), etc.)
* Database server
* PHP 5.4 or above
* The `lib-xml` must be enabled (with DOM support)
* The `GD` PHP extension
* The `Mcrypt` PHP extension 

### HTTP Server

#### Apache
The `mod_rewrite` module must be enabled in your Apache configuration. Microweber creates the necessary `.htaccess` files during installation if you're running on Apache.

### IIS
You can easily [import the `.htaccess` rewrite rules](http://www.iis.net/learn/extensions/url-rewrite-module/importing-apache-modrewrite-rules). Make sure you have enabled [the URL Rewrite module](http://www.iis.net/learn/extensions/url-rewrite-module/using-the-url-rewrite-module) for your website.

#### NginX
Add this `location` directive to your `server` configuration block. The `root` directive must point to the root of your Microweber website.
```
server {
  (...)
  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }
  (...)
}
```

### Database
You have several choices for database engine. For small websites we highly recommend SQLite.
* MySQL
* SQLite
* Microsoft SQL Server
* PostgreSQL

On the installation screen you can only choose from database drivers your PHP configuration already supports.
If you don't see your server of choice in the list youhave to enable the corresponding [PDO](http://php.net/manual/en/book.pdo.php) extension for your database server.
PHP usually comes with PDO enabled by default.

## Contribute
We are looking for people who want to help us improve Microweber. 

If you are a developer, submitting fixes is easy:

1. Log in to GitHub
2. Fork the Microweber repository
3. Make your changes and submit pull request
