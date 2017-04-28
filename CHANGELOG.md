# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).


## [1.0.8] - 2017-04-26

- Added: testimonials module
- Added: twitter feed module
- Added: teamcard module
- Added: tabs module
- Added: favicon support
- Added: google analytics support
- Added: 'before/after' module
- Added: PDF Module
- Added: calendar module
- Added: facebook page module
- Added: category_images module
- Added: parallax module
- Added: rating module
- Added: updated to laravel 5.4
- Added: Some JS libs
- Added: Some PHP libs
- Added: Paste from word button
- Added: setting to allow admin to login only from some IP's
- Added: captcha setting to login form
- Added: more registration and login settings
- Added: payment methods voguepay.com, przelewy24.pl, mollie, etc 
- Added: start depth setting for breadcrumb
- Added: ability to edit link text in the popup
- Added: search by custom fields basic functionality
- Added: translation for Arabic language
- Added: translation for Bahasa Indonesia
- Added: btn module now has skins
- Added: link parameter in `pages_tree` now can be a closure
- Added: JS validation engine
- Added: override method for options
- Added: verify email function for registered users
- Added: htmlpurifier library
- Added: notification_data field to the notification table
- Added: api_expose_user function
- Added: allow keyword search in joined table
- Added: option to require captcha on login
- Added: option to disable registration with disposable email address
- Added: layouts on in the toolbar now have title  shown
- Added: jquery UI version updated
- Added: new function is_cli()
- Added: module for content revisions
- Added: force_https setting to force the site to be on https
- Added: way to change the admin panel url from the config file #277
- Added: option to set max quantity per product order
- Added: drag & drop improvements
- Added: wrapping laravel classes
- Fix: mail sending error when escapeshellcmd() has been disabled for security reasons
- Fix: reflected xss reported by Kacper Szurek
- Fix: check file mime type if finfo function exists
- Fix: live edit fixes
- Fix: table edit  
- Fix: support for postgres 
- Fix: fix when .env file does not exist
- Fix: menu module bugs
- Fix: UI fixes
- Fix of:  #380, #395, #388, #378, #377, #373, #372, #369, #368, #362, #360, #357, #353, #352, #346, #340, #334, #298, #277, #225
- [and many more changes....](https://github.com/microweber/microweber/compare/1.0.7...1.0.8 "")




## [1.0.7] - 2016-03-01

- Vendor: added Omnipay library
- Vendor: added coduo/php-humanizer library
- Vendor: added league/csv library
- Added: taxes support
- Added: caching with .htaccess on some folders
- Added: payment providers (Mollie,Stripe and Authorize.net)
- Added: taxes support
- Added: url support for categories
- Added: crud class
- Added: custom field type "property"
- Added: "max depth" setting to the categories module
- Added: Portuguese language
- Added: LinkedIn login
- Added: export orders to SCV
- Refactor: database, content manager, orders and shop classes
- Fix: general UI improvements
- Fix: security fixes
- Fix: bugfixes in custom fields
- Fix: bugfixes drag and drop
- Fix: thumbnail resize fix
- Fix: fixed dropdown bug when you click on the scroll
- Fix: mw.tooltip issues
- Fix: mw.modal issues
- Fix: sitemap and rss feed
- Fix: performance improvements when there are many categories
- Fix: cache files number reduced
- Fix: fix of notifications  
- Fix: fix of import module
- Various other fixes



## [1.0.6] - 2015-11-19

- Vendor: added Twig parser
- Added: taxes support
- Added: tabs module
- Added: twig parser to shop email template
- Added: content revisions (for new installs only)
- Added: data fields support for categories
- Added: country states class
- Added: .allow-drop css class on live-edit field to allow dropping inside modules
- Added: custom reset password email
- Added: custom register user email
- Refactor: cart moved to own class
- Refactor: orders moved to own class
- Refactor: content data fields moved to own class
- Refactor: content attributes moved to own class
- Fix: search function to exclude deleted content #313
- Fix: uploader makes all files lowercase
- Fix: live edit fixes on columns
- Fix: forms module export to excel
- Fix: Unzip was making all files lowercase
- Fix: URLs are now sequential
- Other fixes

## [1.0.5] - 2015-10-10

- Added: Custom content fields now can be added from the template config file
- Added: Session-less routes and Middleware
- Added: Various UI hooks
- Added: Support for custom data fields
- Added: Cache driver support (Files, APC)
- Added: Template class
- Added: Caching option for apijs and frontend assets
- Fix: create post/product now have parent page
- Fix: parser was outputting the same content in different edit fields
- Fix: module id is now generated with md5 instead with crc32
- Fix: is_home bug under sqlite
- Fix: install bug
- Fix: pqsql fix
- Fix: shipping to country bug with the select menu
- Fix: custom field default values were showing 0
- Fix: editor improvements
- Fix: menu reorder bug
- Fix: google fonts loading
- Fix: facebook login bug
- Fix: mw.front event was called before constants are defined
- Fix: bug in image cropping
- Fix: ConfigSave now replaces the storage_path
- Fix: shipping bug
- Fix:  undo is now working properly
- Fix: preg_replace bug
- Fix: icon picker
- Fix: save edit now uses MutationObserver
- Fix: category reordering
- Fix: live edit changes were not saved when editing an image
- Fix: pixum now caches the images
- Fix: drag and drop in row was appending the item on the wrong place
- Fix: upload image shows progress again
- Fix: resizable columns bug when no width is set
- Fix: redirect in views and modules was not working
- Fix: updated jquery-ui to 1.11.4
- Fix: captcha is now working on pages with multiple captchas
- Fix: upload file bug
- Fix: default controller's render logic now moved in the template class
- Many other fixes



## [1.0.4] - 2015-08-04
- Vendor: added Laravel HTML helper class
- Vendor: updated jquery ui
- Added: template manager class
- Added: XSS filter class
- Added: `artisan microwber:update` command
- Fix: security fix in uploader
- Fix: security fix user creation
- Fix: editing menus bug
- Fix: delete abandoned cart
- Fix: query pagination was skipping pages
- Fix: menu title was outputting html tags
- Fix: menu module bug when editing
- Fix: add to cart with quantity parameter
- Fix: cache now uses locale prefix
- Fix: updated raw SQL queries to use Eloquent
- Fix: redirect bug when using redirect from template
- Fix: UI in forms module in admin
- Fix: captcha validation when there are multiple catchas on one page
- Fix: module id was random when using module inside module
- Fix: other small bugs


## [1.0.3] - 2015-06-15

- Vendor: Updated Laravel to v5.0.33 (last version that supports php 5.4)
- Feature: Custom fonts support (only Google fonts for now)
- Feature: Color picker can set custom color from hash
- Feature: CSS Editor
- Feature: Icon picker
- Feature: Custom font size value can be set
- Fix: improvements in the live edit for Boostrap3 templates
- Fix: possible XSS in users
- Fix: fixes in shop orders management
- Fix: many other minor bugs


## [1.0.2] - 2015-06-01


- Added: get_category_items_count function
- Added: Some missing language entries
- Added: `sum` parameter to the `db_get` function
- Added: `connection_name` parameter to the `db_get` function
- Changed: replaced database class with database_manager
- Fix: shop currency function timeout
- Fix: modal initialization
- Fix: update check
- Fix: install problem under mysql 5.6
- Fix: drop in bootstrap columns
- Fix: improvements for bootsrap themes
- Fix: edit link from live edit
- Fix: cart shipping price display
- Fix: shipping cost per weight
- Fix: price bug
- Fix: shipping cost per weight
- Fix: locale now uses App::getLocale()



## [1.0.1] - 2015-05-12

- Vendor: added Omnipay for payments processing
- Vendor: Markdown provider
- Added: added option to change the currency sign position
- Fix: Custom environment name respects the `getenv` value
- Fix: Cache expiration
- Fix: Category delete
- Fix: Position db field is now converted to integer
- Fix: Delete categories bug
- Fix: Reorder categories bug
- Fix: Factored DB class
- Tests added
- Many other fixes

## [1.0.0] - 2015-02-16

- Moved to Laravel 5
