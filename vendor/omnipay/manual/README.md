# Omnipay: Manual

**Manual driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/omnipay/manual.png?branch=master)](https://travis-ci.org/omnipay/manual)
[![Latest Stable Version](https://poser.pugx.org/omnipay/manual/version.png)](https://packagist.org/packages/omnipay/manual)
[![Total Downloads](https://poser.pugx.org/omnipay/manual/d/total.png)](https://packagist.org/packages/omnipay/manual)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Manual support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "omnipay/manual": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Manual

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/omnipay/manual/issues),
or better yet, fork the library and submit a pull request.
