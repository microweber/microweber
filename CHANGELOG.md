# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).


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

