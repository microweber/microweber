<?php
/** @mainpage QueryPath: Find Your Way
 * @image html querypath-200x333.png
 * QueryPath is a PHP library for working with XML and HTML. It is a PHP implementation of jQuery's
 * traversal and modification libraries.
 *
 * @section getting_started Getting Started
 *
 * To being using QueryPath, you will probably want to take a look at these three pieces of 
 * documentation:
 *  - qp(): The main QueryPath function (like jQuery's $ function.)
 *  - htmlqp(): A specialized version of qp() for dealing with poorly formatted HTML.
 *  - QueryPath: The QueryPath class, which has all of the main functions.
 *
 * One substantial difference from jQuery is that QueryPath does not return a new object for 
 * each call (for performance reasons). Instead, the same object is mutated from call to call.
 * A chain, then, typically performs all methods on the same object.
 * When you need multiple objects, QueryPath has a {@link QueryPath::branch()} function that 
 * will return a cloned QueryPath object.
 *
 * QueryPath also has numerous functions that jQuery does not. Some (like QueryPath::top() and 
 * QueryPath::dataURL()) are extensions we find useful.
 * Most, however, are to either emphasize PHP features (QueryPath::filterPreg()) or adapt to 
 * server-side needs (QueryPathEntities::replaceAllEntities()).
 *
 * @subsection basic_example A Few Basic Examples
 *
 * Here is a basic example of QueryPath usage:
 *
 * @code
 * require 'QueryPath/QueryPath.php';
 * 
 * qp('<?xml version="1.0"?><root><foo/></root>', 'foo')->append('<bar>baz</bar>')->writeXML();
 * @endcode
 *
 * The above will create a new document from the XML string, find the <code>foo</code> element, and then 
 * append the <code>bar</code> element (complete with its text). Finally, the call to QueryPath::writeXML() will
 * print the entire finished XML document to standard out (usually the web browser).
 *
 * Here's an example using htmlqp():
 *
 * @code
 * require 'QueryPath/QueryPath.php';
 * 
 * // URL to fetch:
 * $url = 'http://technosophos.com';
 *
 * print htmlqp($url, 'title')->text();
 * @endcode
 *
 * The above will fetch the HTML from the given URL and then find the <code>title</code> tag. It will extract
 * the text (QueryPath::text()) from the title and print it.
 *
 * For more examples, check out the #Examples namespace (start with {@link examples/html.php}). Also, read about the 
 * qp() and htmlqp() functions.
 *
 * @subsection online_sources Online Sources
 *
 *   - The official QueryPath site http://querypath.org
 *   - The latest API docs http://api.querypath.org
 *   - IBM DeveloperWorks Intro to QueryPath http://www.ibm.com/developerworks/web/library/os-php-querypath/index.html 
 *   - QueryPath articles at TechnoSophos.Com http://technosophos.com/qp/articles
 *   - The QueryPath GitHub repository http://github.com/technosophos/querypath
 *
 * If you find a good online resource, please submit it as an issue in GitHub, and we will 
 * most likely add it here.
 *
 * @subsection more_examples A Larger Example
 *
 * @include examples/html.php
 * 
 * @page extensions Using and Writing Extensions
 *
 * Using an extension is as easy as including it in your code:
 * 
 * @code
 * <?php
 * require 'QueryPath/QueryPath.php';
 * require 'QueryPath/Extension/QPXML.php';
 *
 * // Now I have the QPXML methods available:
 * qp(QueryPath::HTML_STUB)->comment('This is an HTML comment.');
 * ?>
 * @endcode
 *
 * Like jQuery, QueryPath provides a simple mechanism for writing extensions.
 *
 * Check out QPXSL and QPXML for a few easy-to-read extensions. QPDB provides an example of
 * a more complex extension.
 *
 * QueryPathExtension is the master interface for all extensions.
 *
 */
 

/** @page CSSReference CSS Selector Reference
 * QueryPath provides two query 'languages' that you can use to search through XML and HTML 
 * documents. The main query language is an implementation of the CSS3 Selectors specification. This
 * is the query language that jQuery and CSS use -- and more recently, FireFox itself supports it
 * via its JavaScript API. CSS3 should be familiar to developers and designers who have worked with
 * HTML and stylesheets.
 *
 * QueryPath also allows XPath selectors, which can be executed using QueryPath::xpath(). While 
 * fewer functions take XPath expressions, it is noless a powerful tool for querying DOM objects.
 *
 * @code
 * <?php
 * qp($xml)->xpath('//foo');
 * ?>
 * @endcode
 * 
 * QueryPath provides a full CSS3 selector implementation, including all of the specified operators,
 * robost not() and has() support, pseudo-class/elements, and XML namespace support.
 *
 * Selectors can be passed into a number of QueryPath functions including qp(), htmlqp(), 
 * QueryPath::find(), QueryPath::top(), QueryPath::children() and others.
 * @code
 * <?php
 * $qp = qp($html, 'body'); // Find the body
 * $another_qp = $qp->branch('p'); // Create another QP object that searches BODY for P tags.
 * $qp->find('strong>a'); // Find all of the A elements directly inside of STRONG elements.
 * $qp->top('head'); // Start over at the top of the document, and find the HEAD tag.
 * ?>
 * @endcode
 *
 * In all of the examples above, CSS selectors are used to locate specific things inside of the 
 * document.
 *
 * @section selector_examples Example Selectors
 * Example selectors:
 * - <code>p</code>: Select all P elements in a document.
 * - <code>strong a</code>: Select any A elements that are inside (children or descendants of) a STRONG 
 *    element.
 * - <code>strong>a</code>: Select only A elements that are directly beneath STRONG elements.
 * - <code>:root>head</code> select HEAD elements that are directly beneath the document root.
 * - <code>h1, h2</code>: Select all H1's and H2's.
 * - <code>a:link</code>: Select all A tags that have hrefs
 * - <code>div.content</code>: Select all DIV elements that have the class=content set.
 * - <code>#my-id</code>: Select the element that has id=my-id.
 * - <code>p:contains(Hello World)</code>: Select any P elements that have the text Hello World.
 * - <code>p:not(.nav)</code>: Select any elements in P that do not have the nav class.
 *
 * @section pseudo_reference Pseudo-class and pseudo-element selectors
 * QueryPath provides an implementation of the CSS3 spec, including the CSS3 pseudo-classes and 
 * pseudo-elements defined in the spec. Some of the CSS3 pseudo-classes require a user agent, and
 * so cannot be adequately captured on the server side, but all others have been implemented.
 *
 * Additionally, jQuery has added its own pseudo-classes, and jQuery users have come to expect those
 * to work. So for the sake of convenience, we have implemented those as well. These include the 
 * form pseudo-classes, along with several others.
 *
 * Finally, QueryPath has added a couple of useful pseudo-classes, namely :x-root and 
 * :contains-exactly.
 *
 * @subsection pseudoelement_ref Pseudo-Elements
 *
 * Pseudo-elements are new in CSS3, and are syntactically similar to pseudo-classes. To use a
 * pseudo-element in a selector, use the double-colon syntax: <code>::begins</code>. The following pseudo-
 * elements are defined in QueryPath:
 *
 * - first-line: Selects the first line -- everything up to the first LF character (\ n).
 * - first-letter: Slects the first letter of the element.
 *
 * These throw exceptions because they cannot be implemented without a user agent:
 * - before
 * - after
 * - selection
 *
 * Pseudo-elements should be used with care, as they act like elements, but are not.
 *
 * @code
 * <?php
 * $textNode = qp($xml, 'p::first-letter')->get();
 * ?>
 * @endcode
 *
 * @subsection pseudoclass_reference Pseudo-Classes
 *
 * Pseudo-classes are more familiar to CSS and jQuery users. They use a single-colon syntax, and
 * are used to narrow the set of selected elements.
 *
 * The following pseudo-classes are supported:
 * - link: Matches anything with the href attribute.
 * - root: The root element of the document
 * - x-root: The root element that was passed into QueryPath's constructor
 * - x-reset: Same as above.
 * - even: All even elements in a set. First element is odd.
 * - odd: Odd elements in a set. First element is odd.
 * - nth-child: Every nth child in a set.
 * - nth-last-child: Every nth child in a set, counting from the end.
 * - nth-of-type: Every nth tag in a set.
 * - nth-last-of-type: Every nth tag in a set, counting from the end.
 * - first-child: The first child in a set.
 * - last-child: The last child in a set.
 * - first-of-type: The first child of the specified tag in a set.
 * - last-of-type: The last child of the specified tag in a set.
 * - only-child: Matches only if this is the only child in a set.
 * - only-of-type: Matches only if it is the only child of the given tag in a set.
 * - empty: Selects only empty elements.
 * - not: The negation operator, takes a CSS3 selector, e.g. <code>:not(strong>a)</code>.
 * - lt: Items in a set whose index is less than the given integer, e.g. <code>lt(3)</code>
 * - gt: Items in a set whose index is greater than the given integer, e.g. <code>gt(3)</code>
 * - nth: The nth item in a set, e.g. <code>nth(3)</code>
 * - eq: The nth item in a set, e.g. <code>eq(3)</code>
 * - first: The first item in a set.
 * - last: The last item in a set.
 * - parent: Matches if the item is a parent of child elements.
 * - enabled: Matches (form) items that are enabled
 * - disabled: Matches form items that are disabled
 * - checked: Matches form items that are checked
 * - text: Matches form items that are text fields
 * - radio: Matches form items that are radio fields
 * - checkbox: Matches form items that are checkboxes.
 * - file: Matches form items that are file upload widgets.
 * - password: Mathces form items that are password entry boxes.
 * - submit: Matches submit buttons
 * - image: Matches image buttons
 * - reset: Matches reset buttons
 * - button: Matches buttons
 * - header: Matches header fields (h1-h6)
 * - has: Matches any items that have children that match the given selector, e.g. <code>:has(strong>a)</code>
 * - contains: Contains *text* that matches. This is a substring match.
 * - contains-exactly: Contains *exactly* the given text. This is NOT a substring match.
 * 
 * These generate errors because they are not implemented:
 * - indeterminate
 * - lang
 *
 * These are quietly ignored because they require a user agent to be meaningful.
 * - visited
 * - hover
 * - active
 * - focus
 * - animated
 * - visible
 * - hidden
 * - target
 *
 * Examples:
 * @code
 * <?php
 * qp($html, 'form input:text'); // Get all text input elements in a form.
 * ?>
 * @endcode
 * @section xml_namespaces XML Namespaces
 * QueryPath also supports the CSS3 namespace selection syntax. <b>This is syntactically different
 * than the XML namespace tag format</b>. To select a tag whose namespaced name is foo:bar, the 
 * CSS element selector would be <code>foo|bar</code> (note the vertical bar instead of a colon). While 
 * QueryPath does its best to resolve namespaces to short names, there is a possibility that a 
 * malformed namespace will prevent specific namespace queries.
 *
 * You can also query across namespaces with <code>*|tagname</code>.
 *
 * @code
 * <?php
 * qp($xml, 'atom|entry'); // Find all <atom:entry> elements.
 * qp($xml, 'atom|entry > xmedia|video'); // Find all <xmedia:video> elements directly inside <atom:entry> elements.
 * qp($xml, '*|entry'); // Find any namespaced tag that has `entry` as the tag name.
 * ?>
 * @endcode
 */
