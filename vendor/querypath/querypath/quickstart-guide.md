# QueryPath QuickStart

This short guide is intended to help you get started with QueryPath 3.

## Using QueryPath in Your Project

To use QueryPath inside of your own application, you will need to make sure that PHP can find the QueryPath library. There are a few possible ways of doing this. The first is to use an autoloader. The second is to include QueryPath manually. We'll look briefly at each.

### Autoloaders and QueryPath

In recent time, PHP has standardized a method of automatically importing classes by name. This is often called [PSR-0 autoloading](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md). Symfony, Composer, and many other PHP projects use PSR-0 autoloaders, and QueryPath should work with those. In addition, QueryPath has its own autoloader in `qp.php`.

To use QueryPath's autoloader, all you need to do is include `qp.php`. This will detect if another autoloader is already in place, and if not, it will configure it's own autoloader:

```{php}
<?php
require 'qp.php';

print QueryPath::withHTML('http://technosophos.com', 'title')->text();

print htmlqp('http://technosophos.com', 'title')->text();
?>
```

The above illustrates the requiring of QueryPath's autoloader. Note that in that case we don't need to do anything else to get the `QueryPath` class or the `htmlqp()` functions.

QueryPath also ships with [Composer](http://getcomposer.org) support. Composer provides PSR-0 autoloading. To use Composer's autoloader, you can do this:

```{php}
<?php
// The composer autoloader.
require 'vender/autoload.php';

print QueryPath::withHTML('http://technosophos.com', 'title')->text();

// THIS DOESN'T WORK!
// print htmlqp('http://technosophos.com', 'title')->text();
?>
```

Notice, though, that the `qp()` and `htmlqp` functions *will not work* with this method. Why? Because PHP's autoloader does not know about functions. It operates on classes only. So you can use QueryPath's Object-Oriented API (`QueryPath::with()`, `QueryPath::withHTML()`, `QueryPath::withXML()`), but not the `qp()` and `qphtml()` functions. If you want to use those, too, simply include `qp.php`:

```{php}
<?php
// The composer autoloader.
require 'vender/autoload.php';
require 'qp.php';

print QueryPath::withHTML('http://technosophos.com', 'title')->text();

// This works because qp.php was imported
print htmlqp('http://technosophos.com', 'title')->text();
?>
```

## A Simple Example

So far, we have seen a few variations of the same program. Let's learn what it does. Here's the program:

```{php}
<?php
require 'qp.php';

print QueryPath::withHTML('http://technosophos.com', 'title')->text();

print htmlqp('http://technosophos.com', 'title')->text();
?>
```

This does the same thing two different ways. Let's look at line 3:

```{php}
<?php
print QueryPath::withHTML('http://technosophos.com', 'title')->text();
?>
```

This line does three things:

1. It loads and parses the HTML document it finds at `http://techosophos.com`. QueryPath can load documents locally and remotely. It can also load strings of HTML or XML, as well as `SimpleXML` objects and `DOMDocument` objects. It should be easy to get your HTML or XML loaded into QueryPath.
2. It performs a search for the tag named `title`. QueryPath uses CSS 4 Selectors (as the current draft stands) as a query language -- just like jQuery and CSS. (If you prefer XPath, check out the `xpath()` method on QueryPath). Of course, `title` is a very basic selector. You can do more advanced selectors like `#bar-one table>tr:odd td>a:first-of-type()`, which looks for the element with ID `bar-one` and then fetches every odd row from its table, then from each cell in the row, it finds the first hyperlink.
3. Finally, the example calls `text()`, which will fetch the text content of the first element it's found (in this case, the `title` tag in the HTML head). If not title is found, this will return an empty string. Otherwise it will return the text of that tag.

QueryPath has well over 60 methods like `text()`. Some are for navigating, like `top()`, `children()`, `next()`, and `prev()`. Some are for manipulating the parts of an HTML or XML element, like `attar()`. Others are for doing sophisticated finding and filtering operations (`find()`, `filter()`, `filterCallback()`, `map()`, and so on). And, of course, there are methods for modifying the document (`append()`, `before()`, `after()`, `attr()`, `text()`, and many more).

The goal of QueryPath is to make it easy for you to process XML and HTML documents. There may be a lot of methods to learn (just like jQuery), but those methods are there to make your life simpler.

## HTML vs XML

When QueryPath was first introduced, it did not distinguish between XML and HTML documents. At that time, momentum was behind XHTML, and it looked like the future was XML. But over time, it has become abundantly clear that HTML documents cannot be treated as XML during parsing and processing, or during output.

So there are now separate parser functions for HTML and XML -- as well as a generic parser function that inspects the document and attempts to determine whether it is XML or HTML:

* `QueryPath::withXML()`: This *only* handles XML documents. If you give it an HTML document, it will attempt to force XML parsing on that document.
* `htmlqp()`, `QueryPath::withHTML()`: This will force QueryPath to use the HTML parser. it will also make a number of adjustments to QueryPath to accommodate common HTML breakages.
* `qp()`, `QueryPath::with()`: This will attempt to guess whether the document is XML or HTML. In general, it favors XML slightly. Guessing may be done by… 
	- File extension
	- XML declaration
	- The suggestions made by any options passed into the document

###… And Character Encoding

XML suggests that all documents be encoded as UTF-8. Most HTML documents are encoded using one of the ISO specifications (typically ISO-8859-1). And web servers are often misconfigured to report that documents are using one character set when they are actually using another.

To work around all of these issues, QueryPath attempts to convert documents automatically. It does this using PHP's internal character detection libraries. But sometimes it guesses wrong. You can adjust this feature manually by passing in language settings in the `$options` array. See the documentation on `qp()` for details.


## Where to go from here

* [QueryPath.org](http://querypath.org) has pointers to other resources.
* [The API docs](http://api.querypath.org) have detailed explanations of every single part of QueryPath.

	