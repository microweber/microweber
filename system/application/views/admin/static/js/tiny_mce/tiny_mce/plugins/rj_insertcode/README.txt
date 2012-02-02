===============================================================================
License
  RJ_InsertCode Plugin for TinyMCE 3.x is free software; you can redistribute
  it and/or modify it under the terms of the GNU General Public License as
  published by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  RJ_InsertCode Plugin for TinyMCE 3.x is distributed in the hope that it will
  be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
===============================================================================

===============================================================================
Author
  Ryan Juckett
  http://www.ryanjuckett.com/
===============================================================================

===============================================================================
Required Installation Steps

  TinyMCE 3.x
    1) Unzip the .zip file containing the plugin.
    2) Copy the rj_insertcode folder into the TinyMCE plugins folder.
    3) Register the plugin during your call to tinyMCE.Init(). 
       a) Add 'rj_insertcode' to the plugins list. For example
          plugins: "myPlugin1, myPlugin2, rj_insertcode"
       b) Add 'rj_insertcode' to a toolbar row in the theme list. For example
          theme_advanced_buttons1: "myButton1, myButton2, rj_insertcode"

  JCE 1.5.x (Joomla Content Editor)
    1) Install the .zip file through the JCE administration menu.
    2) Prevent Joomla 1.5.8 from modifying highlighted code containing HTML. 
       a) In the Joomla administration menu, click the "Content" button in the
          top menu and select 'Article Manager'
       b) Click the 'Parameters' button in the top right of the Article
          Manager.
       c) Scroll down to the bottom of the parameters window and select
          'Registered' in the 'Filter Groups' section and check the
          'Blacklist (Default)' option for the 'Filter Type'
       d) Scroll to the top of the parameters window and press the save button.

  Other
    If you are installing to an environment not listed here and you should
    be able to use the basic TinyMCE installation above. You can also check
    http://www.ryanjuckett.com/ for any updated installation steps or help.
===============================================================================

===============================================================================
Optional Installation Steps

  These steps will let you tweak and customize the RJ_InsertCode plugin. They
  require knowing where you installed RJ_InsertCode to on your website. Lets
  quickly run through a scenario of where that might be.

  If you know where your TinyMCE installation is, then the RJ_InsertCode
  plugin will be in the "plugins/rj_insertcode" folder under your TinyMCE
  folder. If you are unsure of where your TinyMCE is located, here is one
  example.

  If you are using the JCE editor for Joomla 1.5, and you have Joomla
  installed at "http://www.example.com/MyJoomla", then you can find TinyMCE at
  "http://www.example.com/MyJoomla/plugins/editors/jce/tiny_mce".
	
  1) Change supported languages.

     This plugin uses GeSHi (http://qbnz.com/highlighter/) to perform the
     code highlighting. To remove a supported language, delete the
     "[RJ_INSERTCODE_FOLDER]/geshi/geshi/[LANGUAGE_NAME].php" file. You can
     also add support for new languages by uploading GeSHi language files
     to the same directory.
 
     If you wanted to disable highlighting for C++ and you would delete
     the "[RJ_INSERTCODE_FOLDER]/geshi/geshi/cpp.php" file.  

  2) Support class based style sheets.

     By default, code will be highlighted using inline style information. If
     you are highlighting large blocks of code, this can result in a large
     amount of generated HTML. To reduce the size of your HTML code, you can
     choose to use "class style sheets" from the code editing dialog. The only
     catch is that you need to link the style sheets into your website for it
     to do you much good.

     To create cascading style sheet files for your supported languages, you
     can load "[RJ_INSERTCODE_FOLDER]/php/rj_cssgen.php" in your browser. This
     will let you generate style sheets for any individual language or generate
     a style sheet combining all supported languages. You can then upload your
     new css file(s) and add them to your website.
===============================================================================

===============================================================================
Changelog:
  ver 1.1.1
    - French language packs are now UTF-8 encoded.
    - French translations have been updated thanks to Sarki (www.sarki.ch)
    - Editing html file now aligns the inputs in a pleasing manner thanks to
      Sarki (www.sarki.ch)

  ver 1.1.0
    - Made font size pixel based to improve consistency between different
      browsers.
    - Added 'verticle-align: top' spans to wrap each line of code. This fixed
      alignment issues between the line number cell and the code cell when
      code was using bold or italic font styles.
    - Removed rj_insertcode.css. The relevant styles now get baked into the
      language stylesheet files.
    - Removed the style sheet language files from the installation. Users
      desiring style sheet support can use rj_cssgen.php to generate the files
      they need.
    - Added option for turning off line numbers.
    - Updated README instructions to reflect the changes in using class based
      styles.
    - Added german language files thanks to Achim aka cybergurk
      (www.filmanleitungen.de)
  
  ver 1.0.0
    - Initial version
===============================================================================

===============================================================================
This plugin is derived from the following work:

  Nigel McNie, Benny Baumann, Milian Wolff
  http://qbnz.com/highlighter/
  Developers of GeSHi

  Mike Sullivan
  http://www.analyticsedge.com/
  Packaged InsertCode as a JCE 1.0.x plugin using bot repackaging from
  Alastair Patrick @ thinkpond.org.
  
  Maxime Lardenois
  http://www.jpnp.org/
  Created original InsertCode plugin for TinyMCE.
===============================================================================

