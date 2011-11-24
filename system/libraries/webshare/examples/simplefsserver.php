<?php

// !!!! Make sure the Sabre directory is in the include_path !!!
// example:
// set_include_path('lib/' . PATH_SEPARATOR . get_include_path()); 

/*

This example demonstrates a simple way to create your own virtual filesystems.
By extending the _File and Directory classes, you can easily create a tree
based on various datasources.

The most obvious example is the filesystem itself. A more complete and documented
example can be found in:

lib/Sabre/DAV/FS/Node.php
lib/Sabre/DAV/FS/Directory.php
lib/Sabre/DAV/FS/File.php

*/

// settings
date_default_timezone_set('Canada/Eastern');
$publicDir = 'public';

// Files we need
require_once 'C:\xampp\xampp\htdocs\cms\system\application\webshare\lib\Sabre.autoload.php';

class MyDirectory extends Sabre_DAV_Directory {

  private $myPath;

  function __construct($myPath) {

    $this->myPath = $myPath;

  }

  function getChildren() {

    $children = array();
    // Loop through the directory, and create objects for each node
    foreach(scandir($this->myPath) as $node) {

      // Ignoring files staring with .
      if ($node[0]==='.') continue;

      $children[] = $this->getChild($node);

    }

    return $children;

  }

    function getChild($name) {

        $path = $this->myPath . '/' . $name;

        // We have to throw a FileNotFound exception if the file didn't exist
        if (!file_exists($this->myPath)) throw new Sabre_DAV_Exception_FileNotFound('The file with name: ' . $name . ' could not be found');
        // Some added security

        if ($name[0]=='.')  throw new Sabre_DAV_Exception_FileNotFound('Access denied');

        if (is_dir($path)) {

            return new MyDirectory($name);

        } else {

            return new MyFile($path);

        }

    }

    function getName() {

        return basename($this->myPath);

    }

}

class MyFile extends Sabre_DAV_File {

  private $myPath;

  function __construct($myPath) {

    $this->myPath = $myPath;

  }

  function getName() {

      return basename($this->myPath);

  }

  function get() {

    return fopen($this->myPath,'r');

  }

  function getSize() {

      return filesize($this->myPath);

  }

}

// Make sure there is a directory in your current directory named 'public'. We will be exposing that directory to WebDAV
$rootNode = new MyDirectory($publicDir);

// The rootNode needs to be passed to the server object.
$server = new Sabre_DAV_Server($rootNode);

// And off we go!
$server->exec();

?>
