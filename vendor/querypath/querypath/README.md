# QueryPath: Find your way.

**New development is happening on the `3.x` branch.**

Authors: Matt Butcher (lead), Emily Brand, and many others

[Website](http://querypath.org) | 
[API Docs](http://api.querypath.org) |
[VCS and Issue Tracking](http://github.com/technosophos/querypath) |
[Support List](http://groups.google.com/group/support-querypath) |
[Developer List](http://groups.google.com/group/devel-querypath) |
[Pear channel](http://pear.querypath.org) |

This package is licensed under an MIT license (COPYING-MIT.txt).

## At A Glance

QueryPath is a jQuery-like library for working with XML and HTML
documents in PHP.

Say we have a document like this:
```xml
<?xml version="1.0"?>
<table>
  <tr id="row1">
    <td>one</td><td>two</td><td>three</td>
  </tr>
  <tr id="row2">
    <td>four</td><td>five</td><td>six</td>
  </tr>
</table>
```

And say that the above is stored in the variable `$xml`. Now
we can use QueryPath like this:

```php
<?php
// Get all of the <td> elements in the document and add the
// attribute `foo='bar'`:
qp($xml, 'td')->attr('foo', 'bar');

// Or print the contents of the third TD in the second row:
print qp($xml, '#row2>td:nth(3)')->text();

// Or append another row to the XML and then write the 
// result to standard output:
qp($xml, 'tr:last')->after('<tr><td/><td/><td/></tr>')->writeXML();

?>
```

(This example is in `examples/at-a-glance.php`.)

With over 60 functions and robust support for chaining, you can 
accomplish sophisticated XML and HTML processing using QueryPath.

## QueryPath Installers

The following packages of QueryPath are available:

  * A PEAR package (`pear install querypath/QueryPath`): Installs the library and documentation.
  * A download from the [GitHub Tags page](https://github.com/technosophos/querypath/tags).
  * Via [Composer](http://getcomposer.org)

### Composer

To add QueryPath as a library in your project, add this to the 'require'
section of your `composer.json`:

```json
{
  "require": {
    "querypath/QueryPath": ">=2.0.0"
  }
}
```

The run `php composer.phar install` in that directory.

### Pear

To install QueryPath as a server-wide library, you may wish to use 
PEAR or Pyrus. See [pear.querypath.org](http://pear.querypath.org)
for more information, or simply run these commands:

```
$ pear channel-discover pear.querypath.org
$ pear install querypath/QueryPath
```

### Manual

You can either download a stable release from the 
[GitHub Tags page](https://github.com/technosophos/querypath/tags)
or you can use `git` to clone
[this repository](http://github.com/technosophos/querypath) and work from
the code. `master` typically has the latest stable, while `3.x` is where
active development is happening.

## Including QueryPath

As of QueryPath 3.x, QueryPath uses the Composer autoloader if you
installed with composer:
```php
<?php
require 'vendor/autoload.php';
?>
```

If you installed QueryPath as a PEAR package, use it like this:

```php
<?php
require 'QueryPath/qp.php';
?>
```

From the download or git clone:

```php
<?php
require 'QueryPath/src/QueryPath/qp.php';
?>
```

With the Phar archive, you can include QueryPath like this:

```php
<?php
require 'QueryPath.phar';
?>
```

From there, the main functions you will want to use are `qp()` 
(alias of `QueryPath::with()`) and `htmlqp()` (alias of
`QueryPath::withHTML()`). Start with the
[API docs](http://api.querypath.org/docs).
