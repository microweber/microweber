InsertCode Plugin for TinyMCE is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 2 of the License,
or (at your option) any later version.

InsertCode Plugin for TinyMCE is distributed in the hope that it will
be useful,but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*******************************************************************************
Author : Maxime Lardenois <maxime.lardenois *AT* gmail *DOT* com>
Name : InsertCode Plugin for tinyMCE
Written in : Javascript
*******************************************************************************

Version : 1.0
Release Date : 2006/01/18
Description :
    By using this plugin, you will be able to insert a portion of code in your
    article (or any document edited with tinyMCE)
    The source code will be auto-highlighted using the PHP library Geshi
    (see Credits) (code indentation, keywords highlighting, etc..)

What do I need to run this? :
    * PHP
    * Geshi Library on your site

Limitations :
    * Not XHTML Compliant (due to Geshi)
    * The <pre> tag is recognized as a bit of code
    * Makes use of XMLHTTPRequest : some browser won't be able to run it
    * Lack of translation
           (en,fr,fr_ca... can you help me? if so, contact me, thx)
    * Hopefully that's all but if you find other issues, please let me know
      and I'll try to fix it.

Credits :
    I'd like to thank Nigel McNie <nigel *AT* geshi *DOT* org> for his
    great work on GeSHi - The Generic Syntax Highlighter
    Geshi is published under the terms of the GNU General Public License
    www.geshi.org

Installation :
    * Install Geshi on your site
        (see http://qbnz.com/highlighter/documentation.php)
    * Install tinyMCE
    * Extract this archive (already done? ;-) )
    * CSS :
        - move /insertcode/insertcode.css to your stylesheets directory
        - add the line :
          content_css : "styles/tinymce_content.css"
          in your tinyMCE config
        or
        - append the contents of /insertcode.css to your
          tinyMCE content CSS file
    * Move /insertcode in the /plugins directory of tinyMCE
    * Register this plugin in your tinyMCE config :
      plugins : "insertcode",
      theme_advanced_buttons1 : "insertcode"
    * Edit /insertcode/config/config.php
    * Ok, that's all folks

********** ENJOY ^^ ***********************************************************