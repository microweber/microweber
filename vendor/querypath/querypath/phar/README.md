# Phar Support

PHP 5.3 and onward supports the Phar format for archiving multiple PHP applications into a single file.

QueryPath, from version 2.1 onward, can be distributed as a Phar file that can be used like this:

  <?php
  require 'QueryPath.phar';
  
  // All of QueryPath is now available.
  qp('whatever.xml');
  ?>

The files in this folder are those required *specifically* by the phar system. The build script only includes these in the Phar distribution. They are ignored in other distributions.

## Building a Phar Package

Currently, phar packages are built by the master `build` target. They can also be built independently using the `pharBuild` target.