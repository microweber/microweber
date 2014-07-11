<?php
/**
 * Using QueryPath to Generate a Scalable Vector Graphic (SVG).
 *
 * This file contains an example of how QueryPath can be used
 * to generate an SVG image. SVG (Scalable Vector Graphics) is a W3C standard 
 * XML format for creating graphics. You can find out more about it 
 * here: {@link http://www.w3.org/TR/SVG11/}.
 *
 * If you would like to view the SVG file that is created, the file is available
 * at {@link http://querypath.org/svg.php}. (That URL actually runs the script displayed
 * here.)
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
 
require_once '../src/QueryPath/QueryPath.php';

// Let's stub out a basic SVG document.
$svg_stub = '<?xml version="1.0"?>
<svg
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   version="1.0"
   width="800"
   height="600"
   id="test">
  <desc>Created by QueryPath.</desc>
</svg>';

qp($svg_stub)
  ->attr(array('width' => 800, 'height' => 600))
  ->append('<rect id="first"/><rect id="second"/>')
  ->find('#second')
  ->attr(array('x' => 15, 'y' => 4, 'width' => 40, 'height' => 60, 'fill' => 'red'))
  ->prev()
  ->attr(array('x' => 2, 'y' => 2, 'width' => 40, 'height' => 60, 'fill' => 'navy'))
  ->writeXML();

