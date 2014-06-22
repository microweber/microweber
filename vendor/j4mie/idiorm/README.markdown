Idiorm
======

[![Build Status](https://travis-ci.org/j4mie/idiorm.png?branch=master)](https://travis-ci.org/j4mie/idiorm) [![Latest Stable Version](https://poser.pugx.org/j4mie/idiorm/v/stable.png)](https://packagist.org/packages/j4mie/idiorm) [![Total Downloads](https://poser.pugx.org/j4mie/idiorm/downloads.png)](https://packagist.org/packages/j4mie/idiorm)

[http://j4mie.github.com/idiormandparis/](http://j4mie.github.com/idiormandparis/)

---
### Feature complete

Idiorm is now considered to be feature complete as of version 1.5.0. Whilst it will continue to be maintained with bug fixes there will be no further new features added from this point on.

**Please do not submit feature requests or pull requests adding new features as they will be closed without ceremony.**

---

A lightweight nearly-zero-configuration object-relational mapper and fluent query builder for PHP5.

Tested on PHP 5.2.0+ - may work on earlier versions with PDO and the correct database drivers.

Released under a [BSD license](http://en.wikipedia.org/wiki/BSD_licenses).

**See Also: [Paris](http://github.com/j4mie/paris), an Active Record implementation built on top of Idiorm.**

Features
--------

* Makes simple queries and simple CRUD operations completely painless.
* Gets out of the way when more complex SQL is required.
* Built on top of [PDO](http://php.net/pdo).
* Uses [prepared statements](http://uk.php.net/manual/en/pdo.prepared-statements.php) throughout to protect against [SQL injection](http://en.wikipedia.org/wiki/SQL_injection) attacks.
* Requires no model classes, no XML configuration and no code generation: works out of the box, given only a connection string.
* Consists of one main class called `ORM`. Additional classes are prefixed with `Idiorm`. Minimal global namespace pollution.
* Database agnostic. Currently supports SQLite, MySQL, Firebird and PostgreSQL. May support others, please give it a try!
* Supports collections of models with method chaining to filter or apply actions to multiple results at once.
* Multiple connections supported
* PSR-1 compliant methods (any method can be called in camelCase instead of underscores eg. `find_many()` becomes `findMany()`) - you'll need PHP 5.3+

Documentation
-------------

The documentation is hosted on Read the Docs: [idiorm.rtfd.org](http://idiorm.rtfd.org)

### Building the Docs ###

You will need to install [Sphinx](http://sphinx-doc.org/) and then in the docs folder run:

    make html

The documentation will now be in docs/_build/html/index.html

Let's See Some Code
-------------------

```php
$user = ORM::for_table('user')
    ->where_equal('username', 'j4mie')
    ->find_one();

$user->first_name = 'Jamie';
$user->save();

$tweets = ORM::for_table('tweet')
    ->select('tweet.*')
    ->join('user', array(
        'user.id', '=', 'tweet.user_id'
    ))
    ->where_equal('user.username', 'j4mie')
    ->find_many();

foreach ($tweets as $tweet) {
    echo $tweet->text;
}
```

Changelog
---------

#### 1.5.0 - release 2014-06-22

* Multiple OR'ed conditions support [[lrlopez](https://github.com/lrlopez)] - [issue #201](https://github.com/j4mie/idiorm/issues/201)
* `where_id_in()` for selecting multiple records by primary key [[lrlopez](https://github.com/lrlopez)] - [issue #202](https://github.com/j4mie/idiorm/issues/202)
* Add compound primary key support [[lrlopez](https://github.com/lrlopez)] - [issue #171](https://github.com/j4mie/idiorm/issues/171)
* Add a RAW JOIN source to the query [[moiseevigor](https://github.com/moiseevigor)] - [issue #163](https://github.com/j4mie/idiorm/issues/163)
* offsetExists() should return true for null values, resolves [#181](https://github.com/j4mie/idiorm/issues/181) [[cainmi](https://github.com/cainmi)] - [issue #214](https://github.com/j4mie/idiorm/pull/214)
* Custom cache callback functions [[peter-mw](https://github.com/peter-mw)] - [issue #216](https://github.com/j4mie/idiorm/pull/216)
* Restrict null primary keys on update/delete, resolves [#203](https://github.com/j4mie/idiorm/issues/203) [[cainmi](https://github.com/cainmi)] - [issue #205](https://github.com/j4mie/idiorm/issues/205) 
* Ensure parameters treated by type correctly [[charsleysa](https://github.com/charsleysa)] & [[SneakyBobito](https://github.com/SneakyBobito)] - [issue #206](https://github.com/j4mie/idiorm/issues/206) & [issue #208](https://github.com/j4mie/idiorm/issues/208)
* Reduce the type casting on aggregate functions to allow characters [[herroffizier](https://github.com/herroffizier)] - [issue #150](https://github.com/j4mie/idiorm/issues/150)
* Prevent invalid method calls from triggering infinite recursion [[michaelward82](https://github.com/michaelward82)] - [issue #152](https://github.com/j4mie/idiorm/issues/152)
* Add time to query logging - adds query time parameter to external logger callback function [[AgelxNash](https://github.com/AgelxNash)] - [issue #180](https://github.com/j4mie/idiorm/issues/180)
* Changed database array access to ensure it's always properly setup [[falmp](https://github.com/falmp)] - [issue #159](https://github.com/j4mie/idiorm/issues/159)
* Allow unsetting the db (`ORM::set_db(null)`) to make the test work again [[borrel](https://github.com/borrel)] - [issue #160](https://github.com/j4mie/idiorm/issues/160)
* Correct [issue #176](https://github.com/j4mie/idiorm/issues/176): Ensure database setup before building select [[kendru](https://github.com/kendru)] - [issue #197](https://github.com/j4mie/idiorm/issues/197)
* Add HHVM to travis-ci build matrix [[ptarjan](https://github.com/ptarjan)] - [issue #168](https://github.com/j4mie/idiorm/issues/168)
* Improve where statement precendence documentation [[thomasahle](https://github.com/thomasahle)] - [issue #190](https://github.com/j4mie/idiorm/issues/190)
* Improve testing checks [[charsleysa](https://github.com/charsleysa)] - [issue #173](https://github.com/j4mie/idiorm/issues/173)

#### 1.4.1 - release 2013-12-12

**Patch update to remove a broken pull request** - may have consequences for users of 1.4.0 that exploited the "`find_many()` now returns an associative array with the databases primary ID as the array keys" change that was merged in 1.4.0.

* Back out pull request/issue [#133](https://github.com/j4mie/idiorm/pull/133) as it breaks backwards compatibility in previously unexpected ways (see [#162](https://github.com/j4mie/idiorm/pull/162), [#156](https://github.com/j4mie/idiorm/issues/156) and [#133](https://github.com/j4mie/idiorm/pull/133#issuecomment-29063108)) - sorry for merging this change into Idiorm - closes [issue 156](https://github.com/j4mie/idiorm/issues/156)

#### 1.4.0 - release 2013-09-05

* `find_many()` now returns an associative array with the databases primary ID as the array keys [[Surt](https://github.com/Surt)] - [issue #133](https://github.com/j4mie/idiorm/issues/133)
* Calls to `set()` and `set_expr()` return `$this` allowing them to be chained [[Surt](https://github.com/Surt)]
* Add PSR-1 compliant camelCase method calls to Idiorm (PHP 5.3+ required) [[crhayes](https://github.com/crhayes)] - [issue #108](https://github.com/j4mie/idiorm/issues/108)
* Add static method `get_config()` to access current configuration [[javierd](https://github.com/mikejestes)] - [issue #141](https://github.com/j4mie/idiorm/issues/141)
* Add logging callback functionality [[lalop](https://github.com/lalop)] - [issue #130](https://github.com/j4mie/idiorm/issues/130)
* Add support for MS SQL ``TOP`` limit style (automatically used for PDO drivers: sqlsrv, dblib and mssql) [[numkem](https://github.com/numkem)] - [issue #116](https://github.com/j4mie/idiorm/issues/116)
* Uses table aliases in `WHERE` clauses [[vicvicvic](https://github.com/vicvicvic)] - [issue #140](https://github.com/j4mie/idiorm/issues/140)
* Ignore result columns when calling an aggregate function [[tassoevan](https://github.com/tassoevan)] - [issue #120](https://github.com/j4mie/idiorm/issues/120)
* Improve documentation [[bruston](https://github.com/bruston)] - [issue #111](https://github.com/j4mie/idiorm/issues/111)
* Improve PHPDoc on `get_db()` [[mailopl](https://github.com/mailopl)] - [issue #106](https://github.com/j4mie/idiorm/issues/106)
* Improve documentation [[sjparsons](https://github.com/sjparsons)] - [issue #103](https://github.com/j4mie/idiorm/issues/103)
* Make tests/bootstrap.php HHVM compatible [[JoelMarcey](https://github.com/JoelMarcey)] - [issue #143](https://github.com/j4mie/idiorm/issues/143)
* Fix docblock [[ulrikjohansson](https://github.com/ulrikjohansson)] - [issue #147](https://github.com/j4mie/idiorm/issues/147)
* Fix incorrect variable name in querying documentation [[fridde](https://github.com/fridde)] - [issue #146](https://github.com/j4mie/idiorm/issues/146)

#### 1.3.0 - release 2013-01-31

* Documentation moved to [idiorm.rtfd.org](http://idiorm.rtfd.org) and now built using [Sphinx](http://sphinx-doc.org/)
* Add support for multiple database connections - closes [issue #15](https://github.com/j4mie/idiorm/issues/15) [[tag](https://github.com/tag)]
* Add in raw_execute - closes [issue #40](https://github.com/j4mie/idiorm/issues/40) [[tag](https://github.com/tag)]
* Add `get_last_statement()` - closes [issue #84](https://github.com/j4mie/idiorm/issues/84) [[tag](https://github.com/tag)]
* Add HAVING clause functionality - closes [issue #50](https://github.com/j4mie/idiorm/issues/50)
* Add `is_new` method - closes [issue #85](https://github.com/j4mie/idiorm/issues/85)
* Add `ArrayAccess` support to the model instances allowing property access via `$model['field']` as well as `$model->field` - [issue #51](https://github.com/j4mie/idiorm/issues/51)
* Add a result set object for collections of models that can support method chains to filter or apply actions to multiple results at once - issue [#51](https://github.com/j4mie/idiorm/issues/51) and [#22](https://github.com/j4mie/idiorm/issues/22)
* Add support for [Firebird](http://www.firebirdsql.org) with `ROWS` and `TO` result set limiting and identifier quoting [[mapner](https://github.com/mapner)] - [issue #98](https://github.com/j4mie/idiorm/issues/98)
* Fix last insert ID for PostgreSQL using RETURNING - closes issues [#62](https://github.com/j4mie/idiorm/issues/62) and [#89](https://github.com/j4mie/idiorm/issues/89) [[laacz](https://github.com/laacz)]
* Reset Idiorm after performing a query to allow for calling `count()` and then `find_many()` [[fayland](https://github.com/fayland)] - [issue #97](https://github.com/j4mie/idiorm/issues/97)
* Change Composer to use a classmap so that autoloading is better supported [[javierd](https://github.com/javiervd)] - [issue #96](https://github.com/j4mie/idiorm/issues/96)
* Add query logging to `delete_many` [[tag](https://github.com/tag)]
* Fix when using `set_expr` alone it doesn't trigger query creation - closes [issue #90](https://github.com/j4mie/idiorm/issues/90)
* Escape quote symbols in "_quote_identifier_part" - close [issue #74](https://github.com/j4mie/idiorm/issues/74)
* Fix issue with aggregate functions always returning `int` when is `float` sometimes required - closes [issue #92](https://github.com/j4mie/idiorm/issues/92)
* Move testing into PHPUnit to unify method testing and query generation testing

#### 1.2.3 - release 2012-11-28

* Fix [issue #78](https://github.com/j4mie/idiorm/issues/78) - remove use of PHP 5.3 static call

#### 1.2.2 - release 2012-11-15

* Fix bug where input parameters were sent as part-indexed, part associative

#### 1.2.1 - release 2012-11-15

* Fix minor bug caused by IdiormStringException not extending Exception

#### 1.2.0 - release 2012-11-14

* Setup composer for installation via packagist (j4mie/idiorm)
* Add `order_by_expr` method [[sandermarechal](http://github.com/sandermarechal)]
* Add support for raw queries without parameters argument [[sandermarechal](http://github.com/sandermarechal)]
* Add support to set multiple properties at once by passing an associative array to `set` method [[sandermarechal](http://github.com/sandermarechal)]
* Allow an associative array to be passed to `configure` method [[jordanlev](http://github.com/jordanlev)]
* Patch to allow empty Paris models to be saved ([[j4mie/paris](http://github.com/j4mie/paris)]) - [issue #58](https://github.com/j4mie/idiorm/issues/58)
* Add `select_many` and `select_many_expr` - closing issues [#49](https://github.com/j4mie/idiorm/issues/49) and [#69](https://github.com/j4mie/idiorm/issues/69)
* Add support for `MIN`, `AVG`, `MAX` and `SUM` - closes [issue #16](https://github.com/j4mie/idiorm/issues/16)
* Add `group_by_expr` - closes [issue #24](https://github.com/j4mie/idiorm/issues/24)
* Add `set_expr` to allow database expressions to be set as ORM properties - closes issues [#59](https://github.com/j4mie/idiorm/issues/59) and [#43](https://github.com/j4mie/idiorm/issues/43) [[brianherbert](https://github.com/brianherbert)]
* Prevent ambiguous column names when joining tables - [issue #66](https://github.com/j4mie/idiorm/issues/66) [[hellogerard](https://github.com/hellogerard)]
* Add `delete_many` method [[CBeerta](https://github.com/CBeerta)]
* Allow unsetting of ORM parameters [[CBeerta](https://github.com/CBeerta)]
* Add `find_array` to get the records as associative arrays [[Surt](https://github.com/Surt)] - closes [issue #17](https://github.com/j4mie/idiorm/issues/17)
* Fix bug in `_log_query` with `?` and `%` supplied in raw where statements etc. - closes [issue #57](https://github.com/j4mie/idiorm/issues/57) [[ridgerunner](https://github.com/ridgerunner)]

#### 1.1.1 - release 2011-01-30

* Fix bug in quoting column wildcard. j4mie/paris#12
* Small documentation improvements

#### 1.1.0 - released 2011-01-24

* Add `is_dirty` method
* Add basic query caching
* Add `distinct` method
* Add `group_by` method

#### 1.0.0 - released 2010-12-01

* Initial release
