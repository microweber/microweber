<?php
/**
 * Urban Dictionary Random Word Generator
 *
 * 
 * @author Emily Brand
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://www.urbandictionary.com/
 */
require_once '../src/QueryPath/QueryPath.php';

print '<h3>Urban Dictionary Random Word Generator</h3>';

$page = rand(0, 288);
$qp = htmlqp('http://www.urbandictionary.com/?page='.$page, '#home');

$rand = rand(0, 7);
print $qp->find('.word')->eq($rand)->text().'<br />';
print $qp->top()->find('.definition')->eq($rand)->text();
