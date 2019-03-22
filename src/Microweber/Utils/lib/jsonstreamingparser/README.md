Streaming JSON parser for PHP
=============================

[![Build Status](https://travis-ci.org/salsify/jsonstreamingparser.png?branch=master)](https://travis-ci.org/salsify/jsonstreamingparser)
[![GitHub tag](https://img.shields.io/github/tag/salsify/jsonstreamingparser.svg?label=latest)](https://packagist.org/packages/salsify/jsonstreamingparser) 
[![Packagist](https://img.shields.io/packagist/dt/salsify/json-streaming-parser.svg)](https://packagist.org/packages/salsify/json-streaming-parser)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/salsify/json-streaming-parser.svg)](https://packagist.org/packages/salsify/json-streaming-parser)

This is a simple, streaming parser for processing large JSON documents.
Use it for parsing very large JSON documents to avoid loading the entire thing into memory, which is how just about
every other JSON parser for PHP works.

For more details, I've written up a longer explanation of the [JSON streaming parser](http://www.salsify.com/blog/json-streaming-parser-for-php/1056)
that talks about pros and cons vs. the standard PHP JSON parser.

If you've ever used a [SAX parser](http://en.wikipedia.org/wiki/Simple_API_for_XML) for XML (or even JSON) in another
language, that's what this is. Except for JSON in PHP.

This package is compliant with [PSR-4](http://www.php-fig.org/psr/4/), [PSR-1](http://www.php-fig.org/psr/1/), and
[PSR-2](http://www.php-fig.org/psr/2/).
If you notice compliance oversights, please send a patch via pull request.

Installation
-----

To install `JsonStreamingParser` you can either clone this repository or you can use composer

```
composer require salsify/json-streaming-parser
```

Usage
-----

To use the `JsonStreamingParser` you just have to implement the `\JsonStreamingParser\Listener` interface.
You then pass your `Listener` into the parser.

For example:

```php
$stream = fopen('doc.json', 'r');
$listener = new YourListener();
try {
  $parser = new \JsonStreamingParser\Parser($stream, $listener);
  $parser->parse();
  fclose($stream);
} catch (Exception $e) {
  fclose($stream);
  throw $e;
}
```

That's it! Your `Listener` will receive events from the streaming parser as it works.

There is a complete example of this in `example/example.php`.

Projects using this library
---------------------------

[JSON Collection Parser](https://github.com/MAXakaWIZARD/JsonCollectionParser)


License
-------

[MIT License](http://mit-license.org/) (c) Salsify, Inc.
