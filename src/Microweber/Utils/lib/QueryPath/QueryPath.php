<?php
/** @file
 * The Query Path package provides tools for manipulating a structured document.
 * Typically, the sort of structured document is one using a Document Object Model
 * (DOM).
 * The two major DOMs are the XML DOM and the HTML DOM. Using Query Path, you can
 * build, parse, search, and modify DOM documents.
 *
 * To use QueryPath, only one file must be imported: qp.php. This file defines
 * the `qp()` function, and also registers an autoloader if necessary.
 *
 * Standard usage:
 * @code
 * <?php
 * require 'qp.php';
 *
 * $xml = '<?xml version="1.0"?><test><foo id="myID"/></test>';
 *
 * // Procedural call a la jQuery:
 * $qp = qp($xml, '#myID');
 * $qp->append('<new><elements/></new>')->writeHTML();
 *
 * // Object-oriented version with a factory:
 * $qp = QueryPath::with($xml)->find('#myID')
 * $qp->append('<new><elements/></new>')->writeHTML();
 * ?>
 * @endcode
 *
 * The above would print (formatted for readability):
 * @code
 * <?xml version="1.0"?>
 * <test>
 *  <foo id="myID">
 *    <new>
 *      <element/>
 *    </new>
 *  </foo>
 * </test>
 * @endcode
 *
 * ## Discovering the Library
 *
 * To gain familiarity with QueryPath, the following three API docs are
 * the best to start with:
 *
 *- qp(): This function constructs new queries, and is the starting point
 *  for manipulating a document. htmlqp() is an alias tuned for HTML
 *  documents (especially old HTML), and QueryPath::with(), QueryPath::withXML()
 *  and QueryPath::withHTML() all perform a similar role, but in a purely
 *  object oriented way.
 *- QueryPath: This is the top-level class for the library. It defines the
 *  main factories and some useful functions.
 *- QueryPath::Query: This defines all of the functions in QueryPath. When
 *  working with HTML and XML, the QueryPath::DOMQuery is the actual
 *  implementation that you work with.
 *
 * Included with the source code for QueryPath is a complete set of unit tests
 * as well as some example files. Those are good resources for learning about
 * how to apply QueryPath's tools. The full API documentation can be generated
 * from these files using Doxygen, or you can view it online at
 * http://api.querypath.org.
 *
 * If you are interested in building extensions for QueryPath, see the
 * QueryPath and QueryPath::Extension classes. There you will find information on adding
 * your own tools to QueryPath.
 *
 * QueryPath also comes with a full CSS 3 selector implementation (now
 * with partial support for the current draft of the CSS 4 selector spec). If
 * you are interested in reusing that in other code, you will want to start
 * with QueryPath::CSS::EventHandler.php, which is the event interface for the parser.
 *
 * All of the code in QueryPath is licensed under an MIT-style license
 * license. All of the code is Copyright, 2012 by Matt Butcher.
 *
 * @author M Butcher <matt @aleph-null.tv>
 * @license MIT
 * @see QueryPath
 * @see qp()
 * @see http://querypath.org The QueryPath home page.
 * @see http://api.querypath.org An online version of the API docs.
 * @see http://technosophos.com For how-tos and examples.
 * @copyright Copyright (c) 2009-2012, Matt Butcher.
 * @version -UNSTABLE% (3.x.x)
 *
 */

/**
 *
 */
class QueryPath {
  /**
   * The version string for this version of QueryPath.
   *
   * Standard releases will be of the following form: <MAJOR>.<MINOR>[.<PATCH>][-STABILITY].
   *
   * Examples:
   * - 2.0
   * - 2.1.1
   * - 2.0-alpha1
   *
   * Developer releases will always be of the form dev-<DATE>.
   *
   * @since 2.0
   */
  const VERSION = '3.0.x';

  /**
   * Major version number.
   *
   * Examples:
   * - 3
   * - 4
   *
   * @since 3.0.1
   */
  const VERSION_MAJOR = 3;

  /**
   * This is a stub HTML 4.01 document.
   *
   * <b>Using {@link QueryPath::XHTML_STUB} is preferred.</b>
   *
   * This is primarily for generating legacy HTML content. Modern web applications
   * should use QueryPath::XHTML_STUB.
   *
   * Use this stub with the HTML familiy of methods (QueryPath::Query::html(),
   * QueryPath::Query::writeHTML(), QueryPath::Query::innerHTML()).
   */
  const HTML_STUB = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Untitled</title>
  </head>
  <body></body>
  </html>';

  /**
   * This is a stub XHTML document.
   *
   * Since XHTML is an XML format, you should use XML functions with this document
   * fragment. For example, you should use {@link xml()}, {@link innerXML()}, and
   * {@link writeXML()}.
   *
   * This can be passed into {@link qp()} to begin a new basic HTML document.
   *
   * Example:
   * @code
   * $qp = qp(QueryPath::XHTML_STUB); // Creates a new XHTML document
   * $qp->writeXML(); // Writes the document as well-formed XHTML.
   * @endcode
   * @since 2.0
   */
  const XHTML_STUB = '<?xml version="1.0"?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Untitled</title>
  </head>
  <body></body>
  </html>';


  public static function with($document = NULL, $selector = NULL, $options = array()) {
    $qpClass = isset($options['QueryPath_class']) ? $options['QueryPath_class'] : '\QueryPath\DOMQuery';

    $qp = new $qpClass($document, $selector, $options);
    return $qp;
  }

  public static function withXML($source = NULL, $selector = NULL, $options = array()) {
    $options += array(
      'use_parser' => 'xml',
    );
    return self::with($source, $selector, $options);
  }

  public static function withHTML($source = NULL, $selector = NULL, $options = array()) {
    // Need a way to force an HTML parse instead of an XML parse when the
    // doctype is XHTML, since many XHTML documents are not valid XML
    // (because of coding errors, not by design).

    $options += array(
      'ignore_parser_warnings' => TRUE,
      'convert_to_encoding' => 'ISO-8859-1',
      'convert_from_encoding' => 'auto',
      //'replace_entities' => TRUE,
      'use_parser' => 'html',
      // This is stripping actually necessary low ASCII.
      //'strip_low_ascii' => TRUE,
    );
    return @self::with($source, $selector, $options);
  }

  /**
   * Enable one or more extensions.
   *
   * Extensions provide additional features to QueryPath. To enable and 
   * extension, you can use this method.
   *
   * In this example, we enable the QPTPL extension:
   * @code
   * <?php
   * QueryPath::enable('\QueryPath\QPTPL');
   * ?>
   * @endcode
   *
   * Note that the name is a fully qualified class name.
   *
   * We can enable more than one extension at a time like this:
   *
   * @code
   * <?php
   * $extensions = array('\QueryPath\QPXML', '\QueryPath\QPDB');
   * QueryPath::enable($extensions);
   * ?>
   * @endcode
   *
   * @attention If you are not using an autoloader, you will need to
   * manually `require` or `include` the files that contain the
   * extensions.
   *
   * @param mixed $extensionNames
   *   The name of an extension or an array of extension names.
   *   QueryPath assumes that these are extension class names,
   *   and attempts to register these as QueryPath extensions.
   */
  public static function enable($extensionNames) {

    if (is_array($extensionNames)) {
      foreach ($extensionNames as $extension) {
        \QueryPath\ExtensionRegistry::extend($extension);
      }
    }
    else {
      \QueryPath\ExtensionRegistry::extend($extensionNames);
    }
  }

  /**
   * Get a list of all of the enabled extensions.
   *
   * This example dumps a list of extensions to standard output:
   * @code
   * <?php
   * $extensions = QueryPath::enabledExtensions();
   * print_r($extensions);
   * ?>
   * @endcode
   *
   * @retval array
   *   An array of extension names.
   *
   * @see QueryPath::ExtensionRegistry
   */
  public static function enabledExtensions() {
    return \QueryPath\ExtensionRegistry::extensionNames();
  }



  /**
   * A static function for transforming data into a Data URL.
   *
   * This can be used to create Data URLs for injection into CSS, JavaScript, or other
   * non-XML/HTML content. If you are working with QP objects, you may want to use
   * dataURL() instead.
   *
   * @param mixed $data
   *  The contents to inject as the data. The value can be any one of the following:
   *  - A URL: If this is given, then the subsystem will read the content from that URL. THIS
   *    MUST BE A FULL URL, not a relative path.
   *  - A string of data: If this is given, then the subsystem will encode the string.
   *  - A stream or file handle: If this is given, the stream's contents will be encoded
   *    and inserted as data.
   *  (Note that we make the assumption here that you would never want to set data to be
   *  a URL. If this is an incorrect assumption, file a bug.)
   * @param string $mime
   *  The MIME type of the document.
   * @param resource $context
   *  A valid context. Use this only if you need to pass a stream context. This is only necessary
   *  if $data is a URL. (See {@link stream_context_create()}).
   * @return
   *  An encoded data URL.
   */
  public static function encodeDataURL($data, $mime = 'application/octet-stream', $context = NULL) {
    if (is_resource($data)) {
      $data = stream_get_contents($data);
    }
    elseif (filter_var($data, FILTER_VALIDATE_URL)) {
      $data = file_get_contents($data, FALSE, $context);
    }

    $encoded = base64_encode($data);

    return 'data:' . $mime . ';base64,' . $encoded;
  }

}
