<?php
/**
 * Basic example of QueryPath usage.
 *
 * This two-line example exhibits basic use of QueryPath. It creates a new 
 * HTML document and adds the typical 'Hello World' text to the body. It then writes
 * that information to standard out (which is flushed to a web browser in most cases.)
 *
 * The important methods covered here are {@link qp()}, which is the {@link QueryPath}
 * factory function, {@link QueryPath::find()}, which is the primary searching 
 * function, and {@link QueryPath::writeHTML()}, which is a utility function.
 *
 * This file is fully explained in the official QueryPath tutorial, located 
 * at {@link https://fedorahosted.org/querypath/wiki/QueryPathTutorial}
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see qp()
 * @see QueryPath::find()
 * @see QueryPath::writeHTML()
 * @see html.php
 * @see https://fedorahosted.org/querypath/wiki/QueryPathTutorial The Official Tutorial
 */
require_once '../src/QueryPath/QueryPath.php';
qp(QueryPath::HTML_STUB)->find('body')->text('Hello World')->writeHTML();

$qp = htmlqp(QueryPath::HTML_STUB, 'body');


   $qp->append('<div></div><p id="cool">Hello</p><p id="notcool">Goodbye</p>')
      ->children('p')
      ->after('<p id="new">new paragraph</p>');

         echo ($qp->find('p')->children('p')->html()) ? 'print' : 'dont print';;
         
//         ->writeHTML();
