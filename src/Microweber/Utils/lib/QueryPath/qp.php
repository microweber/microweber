<?php
/**
 * @file
 *
 * QueryPath bootstrap.
 *
 * This file holds bootstrap code to load the QueryPath library.
 *
 * Usage:
 *
 * @code
 * <?php
 * require 'qp.php';
 *
 * qp($xml)->find('foo')->count();
 * ?>
 * @endcode
 *
 * If no autoloader is currently operating, this will use
 * QueryPath's default autoloader **unless** 
 * QP_NO_AUTOLOADER is defined, in which case all of the 
 * files will be statically required in.
 */

// This is sort of a last ditch attempt to load QueryPath if no
// autoloader is used.
if (!class_exists('\QueryPath')) {

  // If classloaders are explicitly disabled, load everything.
  if (defined('QP_NO_AUTOLOADER')) {
    // This is all (and only) the required classes for QueryPath.
    // Extensions are not loaded automatically.
    require __DIR__ . '/QueryPath/Exception.php';
    require __DIR__ . '/QueryPath/ParseException.php';
    require __DIR__ . '/QueryPath/IOException.php';
    require __DIR__ . '/QueryPath/CSS/ParseException.php';
    require __DIR__ . '/QueryPath/CSS/NotImplementedException.php';
    require __DIR__ . '/QueryPath/CSS/EventHandler.php';
    require __DIR__ . '/QueryPath/CSS/SimpleSelector.php';
    require __DIR__ . '/QueryPath/CSS/Selector.php';
    require __DIR__ . '/QueryPath/CSS/Traverser.php';
    require __DIR__ . '/QueryPath/CSS/DOMTraverser/PseudoClass.php';
    // require __DIR__ . '/QueryPath/CSS/DOMTraverser/PseudoElement.php';
    require __DIR__ . '/QueryPath/CSS/DOMTraverser/Util.php';
    require __DIR__ . '/QueryPath/CSS/DOMTraverser.php';
    require __DIR__ . '/QueryPath/CSS/Token.php';
    require __DIR__ . '/QueryPath/CSS/InputStream.php';
    require __DIR__ . '/QueryPath/CSS/Scanner.php';
    require __DIR__ . '/QueryPath/CSS/Parser.php';
    require __DIR__ . '/QueryPath/CSS/QueryPathEventHandler.php';
    require __DIR__ . '/QueryPath/Query.php';
    require __DIR__ . '/QueryPath/Entities.php';
    require __DIR__ . '/QueryPath/Extension.php';
    require __DIR__ . '/QueryPath/ExtensionRegistry.php';
    require __DIR__ . '/QueryPath/Options.php';
    require __DIR__ . '/QueryPath/QueryPathIterator.php';
    require __DIR__ . '/QueryPath/DOMQuery.php';
    require __DIR__ . '/QueryPath.php';
  }
  else {
    spl_autoload_register(function ($klass) {
      $parts = explode('\\', $klass);
      if ($parts[0] == 'QueryPath') {
        $path = __DIR__ . '/' . implode('/', $parts) . '.php';
        if (file_exists($path)) {
          require $path;
        }
      }
    });
  }
}

// Define qp() and qphtml() function.
if (!function_exists('qp')) {
    require __DIR__ . '/qp_functions.php';
}
