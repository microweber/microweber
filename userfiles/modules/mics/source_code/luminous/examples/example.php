<?php
/*
  Copyright 2010 Mark Watkinson

  This file is part of Luminous.

  Luminous is free software: you can redistribute it and/or
  modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Luminous is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Luminous.  If not, see <http://www.gnu.org/licenses/>.

*/
/**
 * \file example.php
 * \brief A short example for calling Luminous
 */ 

require_once('helper.inc');


  
// Luminous shouldn't ever get caught in an infinite loop even on the most
// terrible and malformed input, but I think you'd be daft to run it with no
// safeguard. Also, if you allow your users to give it arbitrary inputs, large
// inputs are pretty much asking for a denial of service attack. Ideally you 
// would enforce your own byte-limit, but I think a time limit is also sensible.
set_time_limit(3);
  
$use_cache = !isset($_GET['nocache'])

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
                        "http://www.w3.org/TR/html4/loose.dtd">
<!-- Luminous is HTML4 strict/loose and HTML5 valid //-->
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title> Example </title>

  <?php 
  echo luminous::head_html();
  ?>
  
</head>

<body>

<h1> A simple usage example for Luminous </h1>
<p> Inspect the source code to see what's going on. Look at both the PHP
    code and the HTML markup. </p>

<p>
<?php if (!$use_cache)
  echo "Caching is <strong>disabled</strong>, click <a href='example.php'>here</a> to enable it";
  else
    echo "Caching is <strong>enabled</strong>. If you are seeing errors, you will need to make the directory: "
. realpath(dirname(__FILE__)  . "/../") . "/cache/, and make it writable to your server if you intend to use the caching system. Click <a href='example.php?nocache'>here</a> to view this page with caching disabled";
?>
</p>

<p style='margin-top:3em'> 
An unconstrained, uncontained code display. If you want to use it like this, 
set style='padding:0px; margin:0px;' on the body element to remove the white 
border to the left.
</p>
<?php echo luminous::highlight('cpp', <<<EOF
#include <stdio.h>
int main()
{
  printf("hello, world");
  return 0;
}
EOF
, $use_cache); ?>
  
  
<p>

But you'll find if you only want to display a short code excerpt, it looks a lot better like this, wrapped in a div with 
style= 'margin-left:auto; margin-right:auto; border:1px solid black;'. The margins make it centred. 

</p>

<div style='width:50%; margin-left:auto;margin-right:auto; border:1px solid black;'>
<?php
echo luminous::highlight('cpp', <<<EOF
#include <stdio.h>
int main()
{
  printf("hello, world");
  return 0;
}
EOF
, $use_cache);
  ?>
</div>


<p>
The style for the container div is: style='width:50%; height: 250px;  margin-left:auto; margin-right:auto; border:1px solid black; overflow:auto'.
You could also set the height using the max-height setting. Note that if you plan to restrict the height with containers, you should set
max-height=0 (Luminous will try to size itself by default).
</p>
<div style='width:50%; height: 250px; margin-left:auto; margin-right:auto; border:1px solid black; overflow:auto'>
<?php
luminous::set('max-height', 0);
echo luminous::highlight('php', <<<EOF
<?php
/**
 * \\ingroup LuminousUtils
 * \\internal
 * \\brief Decodes a PCRE error code into a string
 * \\param errcode The error code to decode (integer)
 * \\return A string which is simply the name of the constant which matches the
 *      error code (e.g. 'PREG_BACKTRACK_LIMIT_ERROR')
 * 
 * \\todo this should all be namespaced
 */ 
function pcre_error_decode(\$errcode)
{
  switch (\$errcode)
  {
    case PREG_NO_ERROR:
      return 'PREG_NO_ERROR';
    case PREG_INTERNAL_ERROR:
      return 'PREG_INTERNAL_ERROR';
    case PREG_BACKTRACK_LIMIT_ERROR:
      return 'PREG_BACKTRACK_LIMIT_ERROR';
    case PREG_RECURSION_LIMIT_ERROR:
      return 'PREG_RECURSION_LIMIT_ERROR';
    case PREG_BAD_UTF8_ERROR:
      return 'PREG_BAD_UTF8_ERROR';
    case PREG_BAD_UTF8_OFFSET_ERROR:
      return 'PREG_BAD_UTF8_OFFSET_ERROR';
    default:
      return 'Unknown error code';
  }
}
EOF
, $use_cache);
  ?>
</div>
</body>
</html>
