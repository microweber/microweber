<?php
/**
 *Version. 1.6.5
    $Id: cls_fast_template.php 12712 2009-06-23 08:44:07Z lvalics $
 *
 * Original Perl module CGI::FastTemplate by Jason Moore jmoore@sober.com
 * PHP3 port by CDI cdi@thewebmasters.net
 * PHP3 Version Copyright (c) 1999 CDI, cdi@thewebmasters.net,
 * All Rights Reserved.
 * Perl Version Copyright (c) 1998 Jason Moore jmoore@sober.com.
 * All Rights Reserved.
 * This program is free software; you can redistribute it and/or modify it
 * under the GNU General Artistic License, with the following stipulations:
 * Changes or modifications must retain these Copyright statements. Changes
 * or modifications must be submitted to both AUTHORS.
 * This program is released under the General Artistic License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the Artistic
 * License for more details. This software is distributed AS-IS.
 */

/**
 * The <code>FastTemplate</code> class provides easy and quite fast
 * template handling functionality.
 * @author Jason Moore jmoore@sober.com.
 * @author CDI cdi@thewebmasters.net
 * @author Artyem V. Shkondin aka AiK artvs@clubpro.spb.ru
 * @author Allyson Francisco de Paula Reis ragen@oquerola.com
 * @author GraFX Software Solutions webmaster@grafxsoftware.com
 * @author Wilfried Trinkl wisl@gmx.at
 * projects based on Fast Templates at www.grafxsoftware.com
 */

#-------------------------------------------------------------------------------
# PLEASE READ BEFORE MAKING CHANGES GUIDLINE BY RR1024 AT NO SPAM GMAIL DOT COM
#-------------------------------------------------------------------------------
# 1. TURN OFF YOUR TABS OR CONVERT TABS TO WHITE SPACE
# 2. WHITE SPACE IS COUNTED FROM LEFT EDGE {6} MEANS 6 WHITE SPACES NOT TABS
# REASON IS EVERYONE HAS THEIR TABS SET UP DIFFERENTLY
/*******************************-EXAMPLE-***************************************
  {6}  function USE_CACHE( $fname = "" )
  {6}  {
  {10}     GLOBAL $php_errormsg; //GLOBAL
  {12}       $this->USE_CACHE = TRUE;//ASSIGN VAR
  {14}         if ( $fname ) { //BEGIN CODE
  {14}+{5}          $this->CACHING = $this->cache_path($fname);
  {14}         }

  {14}         $this->verify_cached_files($fname);
  {6}  }
 *******************************************************************************/
# SAMPLE function HEADER SHOULD BE AGAINST LEFT EDGE


/**
 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
 * @type     - public or private
 * @desc     - function description goes here
 * @param    - $pattern can be array or just a simple string which contains the patterns
 * @param    - $type $type = 1 we have defines, $type = 0(or any other number) we have vars; default is 1(defines)
 * @return   - what will it return
 * @vers     - the version of the function 1.0
 * @Mod by   - latest function change by whom
 * @Mod vers - what changed
 **/

/**
 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
 * @type     - private
 * @desc     - rewrite for the hrefs in the page, it is only called from rewrite_src_path function
 * @param    - $matches is an array with $contents from the earlier preg_replace
 * @return   - $content which is the rewritten template content
 * @vers     - 1.0
 * @Mod by   -
 * @Mod vers -
 **/

function rewrite_link_href_callback($matches) {
	$insideRegex = "/href\s*=(.*?)[\\\"']?([^\\\"' >]+)[\\\"'> ]/is";
	RETURN preg_replace ( $insideRegex, 'href="' . $GLOBALS ['REWRITE_SRC_PATH'] . '\\2"', implode ( ' ', $matches ) );
}

if (! class_exists ( 'FastTemplate' )) {
	
	class FastTemplate {
		/**
		 * @access private
		 * @Desc - holds time of start generation
		 */
		var $start;
		
		/**
		 * @access private
		 * @Desc - Holds the last error message
		 */
		var $ERROR = "";
		
		/**
		 * @access private
		 * @Desc - Holds the HANDLE to the last template parsed by parse()
		 */
		var $LAST = "";
		
		/**
		 * @access private
		 * @Desc - Holds path-to-templates
		 */
		var $ROOT = "";
		
		/**
		 * @access private
		 * @Desc - if the php code is executed inside html templates
		 */
		var $PHP_IN_HTML = FALSE;
		
		/**
		 * @access private
		 * @Desc - Set to TRUE if this is a WIN32 server
		 */
		var $WIN32 = FALSE;
		
		/**
		 * @access private
		 * @Desc - Strict template checking. Unresolved vars in templates
		 *         will generate a warning when found. used for debug.
		 */
		var $STRICT_DEBUG = FALSE;
		
		/**
		 * @access private
		 * @Desc - Strict template checking. Unresolved vars in templates will generate
		 *         a warning when found.
		 */
		var $STRICT = FALSE;
		
		/**
		 * @access private
		 * @Desc -  Enable caching mode. Default: FALSE
		 */
		var $USE_CACHE = FALSE;
		
		/**
		 * @access private
		 * @Desc -  Enable caching mode. Default: FALSE
		 */
		var $DELETE_CACHE = FALSE;
		
		/**
		 * @access private
		 * @Desc - Do comments deletion on template loading. Default: FALSE
		 */
		var $STRIP_COMMENTS = FALSE;
		
		/**
		 * @access private
		 * @Desc - Holds the array of filehandles FILELIST[HANDLE] == "fileName"
		 */
		var $FILELIST = array ();
		
		/**
		 * @access private
		 * @Desc - Holds the array of dynamic blocks, and the fileHandles they live in.
		 */
		var $DYNAMIC = array ();
		
		/**
		 * @access private
		 * @Desc - Holds the array of Variable handles. PARSEVARS[HANDLE] == "value"
		 */
		var $PARSEVARS = array ();
		
		/**
		 * @access private
		 * @Desc - We only want to load a template once - when it's used.
		 *         LOADED[FILEHANDLE] == 1 if loaded undefined if not loaded yet.
		 */
		var $LOADED = array ();
		/**
		 * @access private
		 * @Desc - Holds the handle names assigned
		 */
		var $HANDLE = array ();
		
		/**
		 * @access private
		 * @Desc - Holds the warnings by a call to parse()
		 */
		var $WARNINGS = array ();
		
		/**
		 * @access private
		 * @Desc - Time in seconds to expire cache files
		 */
		var $UPDT_TIME = '60';
		
		/**
		 * @access private
		 * @Desc -  Dir for save cached files default /cache/
		 */
		var $CACHE_PATH = './cache/';
		
		/**
		 * @access private
		 * @Desc - filename for caching
		 */
		var $CACHING = "";
		
		/**
		 * @access private
		 * @Desc -  Start of template comments
		 */
		var $COMMENTS_START = "{*";
		
		/**
		 * @access private
		 * @Desc -  End of template comments
		 */
		var $COMMENTS_END = "*}";
		
		/**
		 * @access public
		 * @Desc - Rewrite js, css, and img src from template to a custom path
		 */
		var $REWRITE_SRC_PATH = "";
		/**
		 * @access public
		 * @Desc - enables the srsc path rewrites
		 *         
		 */
		var $ENABLE_REWRITE_SRC_PATH = false;
		
		/**
		 * @access private
		 * @Desc - Patterns are the ones which are used by the multiple assigned
		 *         functions (this is for variable oriented language, config etc files)
		 */
		var $PATTERN_VARS_VARIABLE = array ();
		
		/**
		 * @access private
		 * @Desc - Patterns are the ones which are used by the multiple assigned
		 *         functions (this is for define oriented language files)
		 */
		var $PATTERN_VARS_DEFINE = array ();
		
		/**
		 * @access private
		 * @Desc - if utf file includes are done IE is not using base href as he should.
		 *         Set true if IE7 is not working
		 */
		var $IE_UTF_INCLUDE = true; //
		

		/**
		 * @access public
		 * @Desc - For visual editors, to be easy to work.
		 */
		var $REWRITE_TEMPLATE_PATH = array ("../", "" );
		//var $REWRITE_TEMPLATE_PATH = "";
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Class Constructor
		 * @param    - template root.
		 * @return   - FastTemplate FastTemplate object
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 **/
		function FastTemplate($pathToTemplates = "") {
			GLOBAL $php_errormsg; //if the track_errors configuration option is turned on (it defaults to off).
			

			if (! empty ( $pathToTemplates )) {
				$this->set_root ( $pathToTemplates );
			}
			
			$this->start = $this->utime (); //NEW by AiK
		} // end (new) FastTemplate ()
		

		/**
		 * @author   - Voituk Vadim
		 * @type     - private
		 * @desc     - Parse template & RETURN it
		 * @param    - $tpl_name - name of template to parse
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 **/
		function parse_and_return($tpl_name) {
			$HREF = 'TPL';
			$this->parse ( $HREF, $tpl_name );
			$result = trim ( $this->fetch ( $HREF ) );
			$this->clear_href ( $HREF );
			RETURN $result;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc     - Sets template root, All templates will be loaded from this "root"
		 *             directory Can be changed in mid-process by re-calling with a new
		 *             value.
		 * @param    - @param $root path to templates dir
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 **/
		function set_root($root) {
			$trailer = substr ( $root, - 1 );
			
			if (! $this->WIN32) {
				
				if ((ord ( $trailer )) != 47) {
					$root = "$root" . chr ( 47 );
				}
				
				if (is_dir ( $root )) {
					$this->ROOT = $root;
				} else {
					$this->ROOT = "";
					$this->error ( "Specified ROOT dir [$root] is not a directory" );
				}
			
			} else {
				if ((ord ( $trailer )) != 92) { # WIN32 box - no testing
					$root = "$root" . chr ( 92 );
				}
				
				$this->ROOT = $root;
			
			}
		} // End set_root()
		

		/**
		 * @author   - Voituk Vadim
		 * @type     - public
		 * @desc	 - Return value of ROOT templates directory
		 * @param    -
		 * @return   - root dir value with trailing slash
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function get_root() {
			RETURN $this->ROOT;
		} // End get_root()
		

		/**
		 * @author   - Artyem V. Shkondin aka AiK artvs@clubpro.spb.ru
		 * @type     - private
		 * @desc	 - just for benchmarking purposes at debugging
		 * @param    -
		 * @return   - $sec + $usec
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function utime() {
			$time = explode ( " ", microtime () );
			$usec = ( double ) $time [0];
			$sec = ( double ) $time [1];
			RETURN $sec + $usec;
		} // End utime ()
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc	 - Enable strict template checking
		 * 			   When strict() is on (it is on by default) all variables found during template parsing that are unresolved
		 *			   have a warning printed to STDERR;
		 * 			   Also, the variables will be left in the output document.
		 * 			   This was done for two reasons: to allow for parsing to be done in stages (i.e. multiple passes),
		 * 			   and to make it easier to identify undefined variables since they appear in the parsed output.
		 *
		 * @param    -
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function strict() {
			$this->STRICT = TRUE;
		}
		
		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc	 - Turns off warning messages about unresolved template variables.
		 * 			   A call to no_strict() is required to replace unknown variables with an empty string.
		 * 			   By default, all instances of FastTemplate behave as is strict() was called. Also, no_strict() must be set
		 * 			   for each instance of FastTemplate.
		 * 			   <code>
		 * 				$tpl = new FastTemplate("/path/to/templates");
		 * 				$tpl->no_strict();
		 * 			   </code>
		 * @param    -
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function no_strict() {
			$this->STRICT = FALSE;
		}
		
		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc	 - Set PHP_IN_HTML to TRUE/FALSE
		 * @param    - $value true or false
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function php_in_html($value) {
			$this->PHP_IN_HTML = $value;
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis
		 * @type     - public
		 * @desc	 - Rewrite js, css, and img src from template to a custom path
		 * 			   It is very helpfull when you want to edit the work template in a
		 * 			   visual editor without any relationship with the script path
		 * 			   that will summon the work template output content.
		 * @param    - $contents - the template source
		 * @return   - $contents the rewritten content
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function rewrite_src_path($contents) {
			#Rewrite src path regex using Heredoc
			$regexPattern [] = "/src\s*=(.*?)[\\\"']?([^\\\"' >]+)[\\\"'> ]/is";
			//$regexPattern[] = "/<\s*link\s+[^>]*href\s*=\s*[\\\"']?([^\\\"' >]+)[\\\"' >]/is"; //BUG in 1.5.1
			$regexPattern [] = "/<\s*link\s+[^>]*href\s*=\s*[\\\"']?[^\\\"' >]+[\\\"' >]/is";
			
			IF (sizeof ( $this->REWRITE_SRC_PATH ) > 0) {
				IF (strpos ( $contents, "'" )) {
					$contents = preg_replace ( $regexPattern [0], 'src=\'' . $this->REWRITE_SRC_PATH . '\\2\'', $contents );
				} else {
					$contents = preg_replace ( $regexPattern [0], 'src="' . $this->REWRITE_SRC_PATH . '\\2"', $contents );
				}
				// preg_reclace_callback RETURN his result to a function outside class body
				$GLOBALS ['REWRITE_SRC_PATH'] = $this->REWRITE_SRC_PATH;
				$contents = preg_replace_callback ( $regexPattern [1], 'rewrite_link_href_callback', $contents );
				unset ( $GLOBALS ['REWRITE_SRC_PATH'] );
			}
			
			RETURN $contents;
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis
		 * @type     - public
		 * @desc	 - Set up REWRITE_SRC_PATH to the paramter given value.
		 * @param    - $path - the path you need
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function set_output_rewrite_src_path($path) {
			$this->REWRITE_SRC_PATH = $path;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc	 - Grabs a template from the root dir and reads it into a (potentially REALLY) big string.
		 * @param    - $template - the template
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function get_template($template) {
			GLOBAL $php_errormsg; # if the track_errors configuration option is turned on (it defaults to off).
			

			if (empty ( $this->ROOT )) {
				$this->error ( "Cannot open template. Root not valid.", 1 );
				RETURN FALSE;
			}
			
			if (empty ( $template )) {
				$this->error ( "Cannot open template because the template name is empty.", 1 );
				RETURN FALSE;
			}
			;
			
			$filename = "$this->ROOT" . "$template";
			
			if ($this->PHP_IN_HTML) {
				ob_start (); #execute any php code from the template
				include ($filename);
				$contents = ob_get_contents ();
				ob_end_clean ();
			} else {
				$contents = ((function_exists ( 'file_get_contents' ))) ? file_get_contents ( $filename ) : implode ( "\n", file ( $filename ) );
			}
			
			if ($this->ENABLE_REWRITE_SRC_PATH)
				$contents = $this->rewrite_src_path ( $contents );
			
			if ((! $contents) or (empty ( $contents ))) {
				$this->error ( "get_template() failure: [$filename] $php_errormsg", 1 );
				RETURN FALSE;
			} else { # Strip template comments */
				

				if ($this->STRIP_COMMENTS) {
					$pattern = "/" . preg_quote ( $this->COMMENTS_START ) . "\s.*" . preg_quote ( $this->COMMENTS_END ) . "/sU";
					$contents = preg_replace ( $pattern, '', $contents );
				}
				
				$block = array ("/<!--\s(BEGIN|END)\sDYNAMIC\sBLOCK:\s([a-zA-Z\_0-9]*)\s-->/" );
				$corrected = array ("\r\n <!-- \\1 DYNAMIC BLOCK: \\2 --> \r\n" );
				$contents = preg_replace ( $block, $corrected, $contents );
				
				RETURN trim ( $contents );
			}
		} // end get_template
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc	 - Prints the warnings for unresolved variable references.in template files. Used if STRICT is TRUE.
		 * @param 	 - $Line string for variable references checking
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function show_unknowns($Line) {
			$unknown = array ();
			if (ereg ( "({[A-Z0-9_]+})", $Line, $unknown )) {
				$UnkVar = $unknown [1];
				if (! (empty ( $UnkVar ))) {
					
					if ($this->STRICT_DEBUG) {
						$this->WARNINGS [] = "[FastTemplate] Warning: no value found for variable: $UnkVar \n";
					}
					
					if ($this->STRICT) {
						@error_log ( "[FastTemplate] Warning: no value found for variable: $UnkVar ", 0 );
					}
				}
			}
		} // end show_unknowns()
		

		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc	 - Parse param string and replace simple variable
		 * @param 	 - string
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function parseParamString($string) {
			$matches = array ();
			if (preg_match_all ( '/\{([a-z0-9_]+)\}/i', $string, $matches )) {
				
				FOR($i = 0; $i < count ( $matches [0] ); $i ++) {
					$string = str_replace ( $matches [0] [$i], $this->PARSEVARS [$matches [1] [$i]], $string );
				}
			
			}
			RETURN $string;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc     - checking if values are defined
		 * @param    - $value
		 * @param    - $field
		 * @param    - $params
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function value_defined($value, $field = '', $params = '') {
			$var = $this->PARSEVARS [$value];
			if ($field {0} == '.') {
				$field = substr ( $field, 1 );
			}
			# echo "$value, $field, $params <BR>";
			if (is_object ( $var )) {
				
				if (method_exists ( $var, $field )) {
					eval ( '$return = $var->' . $field . '(' . $this->parseParamString ( $params ) . ');' );
					RETURN ((! empty ( $return )) || ($return === TRUE));
				
				} 

				ELSEif ((strcasecmp ( $field, 'id' ) != 0) && method_exists ( $var, 'get' )) {
					$result = $var->get ( $field );
					RETURN (! empty ( $result ) || $result === TRUE);
				} 

				ELSEif ((strcasecmp ( $field, 'id' ) == 0) && method_exists ( $var, 'getId' )) {
					$result = $var->getId ();
					RETURN (! empty ( $result ) || $result === TRUE);
				}
			
			} else {
				RETURN (! empty ( $var ) || $var === TRUE);
			}
		}
		
		/**
		 * @author   - Alex Tonkov
		 * @type     - private
		 * @desc     - This routine get's called by parse_template() and does the actual job.It is also removing define blocks.
		 * @param    - $template string to be parsed
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function parse_defined($template) {
			$lines = explode ( "\n", $template );
			$newTemplate = "";
			$ifdefs = FALSE;
			$depth = 0;
			$needparsedef [$depth] ["defs"] = FALSE;
			$needparsedef [$depth] ["parse"] = TRUE;
			
			WHILE ( list ( $num, $line ) = each ( $lines ) ) {
				//Added "necessary" lines to new string
				if (((! $needparsedef [$depth] ["defs"]) || ($needparsedef [$depth] ["parse"])) && (strpos ( $line, "IFDEF:" ) === FALSE) && (strpos ( $line, "IFNDEF:" ) === FALSE) && (strpos ( $line, "ELSE" ) === FALSE) && (strpos ( $line, "ENDIF" ) === FALSE))
					$newTemplate .= trim ( $line ) . "\n";
					
				//by Alex Tonkov: Parse the start of define block and check the condition
				if (preg_match ( "/<!--\s*IFDEF:\s*([a-zA-Z_][a-zA-Z0-9_]+)(\.|\-\>)?([a-zA-Z_][a-zA-Z0-9_]+)?\(?(\s*\,?\".*\"\s*\,?|\s*\,?[a-z0-9\_]*\s*\,?)\)?\s*-->/i", $line, $regs )) {
					$depth ++;
					$needparsedef [$depth] ["defs"] = TRUE;
					if ($this->value_defined ( $regs [1], $regs [3], $regs [4] ))
						$needparsedef [$depth] ["parse"] = $needparsedef [$depth - 1] ["parse"];
					else
						$needparsedef [$depth] ["parse"] = FALSE;
				}
				//by Alex Tonkov: IFNDEF block
				if (preg_match ( "/<!--\s*IFNDEF:\s*([a-zA-Z_][a-zA-Z0-9_]+)(\.|\-\>)?([a-zA-Z_][a-zA-Z0-9_]+)?\(?(\s*\,?\".*\"\s*\,?|\s*\,?[a-z0-9\_]*\s*\,?)\)?\s*-->/i", $line, $regs )) {
					$depth ++;
					$needparsedef [$depth] ["defs"] = TRUE;
					if (! $this->value_defined ( $regs [1], $regs [3], $regs [4] ))
						$needparsedef [$depth] ["parse"] = $needparsedef [$depth - 1] ["parse"];
					else
						$needparsedef [$depth] ["parse"] = FALSE;
				}
				//by Alex Tonkov: ELSE block
				if (preg_match ( "/<!--\s*ELSE\s*-->/i", $line )) {
					if ($needparsedef [$depth] ["defs"])
						$needparsedef [$depth] ["parse"] = (! ($needparsedef [$depth] ["parse"]) & $needparsedef [$depth - 1] ["parse"]);
				}
				//by Alex Tonkov: End of the define block
				if (preg_match ( "/<!--\s*ENDIF\s*-->/i", $line )) {
					$needparsedef [$depth] ["defs"] = FALSE;
					$depth --;
				}
			}
			if ($depth)
				$this->error ( 'Some nonclosed IDEFS blocks', 0 );
			
			RETURN $newTemplate;
		
		}
		
		/**
		 * @author   - CDI cdi@thewebmasters.net
		 * @type     - private
		 * @desc     - This routine get's called by parse() and does the actual {VAR} to VALUE conversion within the template.
		 * @param    - $template string to be parsed
		 * @param 	 - $ft_array array of variables
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   - Artyem V. Shkondin artvs@clubpro.spb.ru, Comments by GRAFX
		 * @Mod vers - 1.1.1
		 */
		function parse_template($template, $ft_array) {
			$matches = array ();
			/* Parsing and replacing object statements {Object.field} */
			if (preg_match_all ( '/\{([a-zA-Z_][a-zA-Z0-9_]+)(\.|\-\>)([a-zA-Z_][a-zA-Z0-9_]+)\(?(\s*\,?\".*?\"\s*\,?|\s*\,?[a-z0-9\_]*\s*\,?)\)?\}/i', $template, $matches )) {
				FOR($i = 0; $i < count ( $matches [0] ); ++ $i) {
					$obj = $ft_array [$matches [1] [$i]];
					if ((is_object ( $obj ) && method_exists ( $obj, $matches [3] [$i] ))) {
						eval ( '$return = $obj->' . $matches [3] [$i] . '(' . $this->parseParamString ( $matches [4] [$i] ) . ');' );
						$template = str_replace ( $matches [0] [$i], $return, $template );
					} else if (is_object ( $obj ) && ($matches [3] [$i] == 'id') && method_exists ( $obj, 'getId' ))
						$template = str_replace ( $matches [0] [$i], $obj->getId (), $template );
					else if (is_object ( $obj ) && method_exists ( $obj, 'get' ))
						$template = str_replace ( $matches [0] [$i], $obj->get ( $matches [3] [$i] ), $template );
					else if (! is_object ( $obj ))
						$template = str_replace ( $matches [0] [$i], '', $template );
				} //for
			} //echo $template;
			/* Parse Include blocks (like SSI) */
			if (preg_match_all ( '/<\!\-\-\s*#include\s+file="([\{\}a-zA-Z0-9_\.\-\/]+)"\s*\\-\->/i', $template, $matches )) {
				FOR($i = 0; $i < count ( $matches [0] ); $i ++) {
					$file_path = $matches [1] [$i];
					
					FOREACH ( $ft_array as $key => $value ) {
						if (! empty ( $key )) {
							$key = '{' . "$key" . '}';
							$file_path = str_replace ( "$key", "$value", "$file_path" );
						}
					} //foreach
					

					$content = '';
					
					if (! isset ( $ft_array [$file_path] )) {
						
						if (! file_exists ( $file_path ))
							$file_path = $this->ROOT . $file_path;
						
						if (! file_exists ( $file_path ))
							$file_path = $this->ROOT . basename ( $file_path );
						
						if (file_exists ( $file_path )) {
							$content = ((function_exists ( 'file_get_contents' ))) ? file_get_contents ( $file_path ) : implode ( "\n", file ( $file_path ) );
						} else
							$content = '';
					} else
						$content = $ft_array [$file_path];
					
					$template = str_replace ( $matches [0] [$i], $content, $template );
				
				} //for
			} //preg_match_all
			

			reset ( $ft_array );
			
			WHILE ( list ( $key, $val ) = each ( $ft_array ) ) {
				if (! (empty ( $key ))) {
		    // add by Lubos
                if(gettype ( $val ) == "object") continue;
				if (gettype ( $val ) != "string") {
                //echo gettype($val);
                //settype ( $val, "string" );
            }

					
					$key = '{' . "$key" . '}'; //php4 doesn't like '{$' combinations.
					$template = str_replace ( "$key", "$val", "$template" ); //Correct using str_replace insted ereg_replace
				}
			}
			//if ( !$this->STRICT && ($this->STRICT && !$this->STRICT_DEBUG))
			if (! $this->STRICT || ($this->STRICT && ! $this->STRICT_DEBUG)) { //Fixed error ^^ // by Voituk Vadim
				// Silently remove anything not already found
				$template = preg_replace ( "/{([A-Za-z0-9_\.]+)}/", "", $template ); // by Voituk Vadim correct using str_replace insted ereg_replace, small addition a-z, thanx to Gabe Alack, the regex used in the line to remove unset variables only checked for all caps, while lowercase variables are allowed by the class. (1.6.2)
				// by Alex Tonkov: paste each define block in one line
				$template = preg_replace ( "/(<!--\s*IFDEF:\s*([a-zA-Z_][a-zA-Z0-9_]+)(\.|\-\>)?([a-zA-Z_][a-zA-Z0-9_]+)?\(?(\s*\,?\".*?\"\s*\,?|\s*\,?[a-z0-9\_]*\s*\,?)\)?\s*-->)/i", "\n$0\n", $template );
				$template = preg_replace ( "/(<!--\s*IFNDEF:\s*([a-zA-Z_][a-zA-Z0-9_]+)(\.|\-\>)?([a-zA-Z_][a-zA-Z0-9_]+)?\(?(\s*\,?\".*?\"\s*\,?|\s*\,?[a-z0-9\_]*\s*\,?)\)?\s*-->)/i", "\n$0\n", $template );
				$template = preg_replace ( "/(<!--\s*ELSE\s*-->)/i", "\n\\0\n", $template );
				$template = preg_replace ( "/(<!--\s*ENDIF\s*-->)/i", "\n\\0\n", $template );
				
				//Correct using str_replace insted ereg_replace
				// Removed because it deletes newline in textareas.
				// TX to Martin Fasani
				//$template = ereg_replace("([\n]+)", "\n", $template);
				//by AiK: remove dynamic blocks
				$lines = explode ( "\n", $template );
				//$inside_block = FALSE;
				$inside_block=0;
				// by Voituk Vadim
				$ifdefs = FALSE;
				$needparsedef = FALSE;
				// end by Voituk Vadim
				$template = "";

				WHILE ( list ( $num, $line ) = each ( $lines ) ) {
					if (substr_count ( $line, "<!-- BEGIN DYNAMIC BLOCK:" ) > 0) {
						$inside_block++; // original: $inside_block=TRUE;
					}
					if ($inside_block <= 0){ // original: if (!$inside_block)
					// <=0 prevents unclean template design with a different number of BEGIN and END blocks
					    $template .= "$line\n";
					}
					if (substr_count ( $line, "<!-- END DYNAMIC BLOCK:" ) > 0){
						$inside_block--; // original: $inside_block=FALSE;
					}
				}
				
				$template = $this->parse_defined ( $template );
			
			} else {
				// Warn about unresolved template variables
				if (ereg ( "({[A-Z0-9_]+})", $template )) {
					$unknown = explode ( "\n", $template );
					WHILE ( list ( $Element, $Line ) = each ( $unknown ) ) {
						$UnkVar = $Line;
						if (! (empty ( $UnkVar ))) {
							$this->show_unknowns ( $UnkVar );
						}
					}
				}
			}
			
			RETURN $template;
		
		} // end parse_template();
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - This is the main function in FastTemplate
		 * 			   It accepts a new key value pair where the key is the TARGET and the values are the SOURCE templates.
		 * 			   There are three forms this can be in:
		 * 				<code>
		 * 					$tpl->parse(MAIN, "main");  // regular
		 * 					$tpl->parse(MAIN, array ( "table", "main") );  // compound
		 * 					$tpl->parse(MAIN, ".row");                     // append
		 * 				</code>
		 * 			  In the regular version, the template named ``main'' is loaded if it hasn't been
		 * 			  already, all the variables are interpolated, and the result is then stored in
		 * 			  FastTemplate as the value MAIN. if the variable '{MAIN}' shows up in a later
		 * 			  template, it will be interpolated to be the value of the parsed ``main'' template.
		 * 			  This allows you to easily nest templates, which brings us to the compound style.
		 * 			  The compound style is designed to make it easier to nest templates.
		 * 			  The following are equivalent:
		 * 				<code>
		 * 					$tpl->parse(MAIN, "table");
		 * 					$tpl->parse(MAIN, ".main");
		 * 				</code>
		 * 			  is the same as:
		 * 				<code>
		 * 					$tpl->parse(MAIN, array("table", "main"));
		 * 				</code>
		 * 			  this form saves function calls and makes your code cleaner.
		 * 			  It is important to note that when you are using the compound form, each template after
		 * 			  the first, must contain the variable that you are parsing the results into.
		 * 			  In the above example, 'main' must contain the variable '{MAIN}', as that is where the parsed results of 'table' is stored.
		 * 			  if 'main' does not contain the variable '{MAIN}' then the parsed results of 'table' will be lost.
		 *			  The append style allows you to append the parsed results to the target variable.
		 * 			  Placing a leading dot . before a defined file handle tells FastTemplate to append the parsed results of this template
		 * 			  to the returned results. This is most useful when building tables that have an dynamic number of rows - such as data
		 * 			  from a database query.
		 *
		 * @param    - $ReturnVar mixed
		 * @param 	 - $FileTags mixed
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function parse($ReturnVar, $FileTags) {
			// add multiple parse section
			// these are the define assigns
			FOREACH ( $this->PATTERN_VARS_DEFINE as $value )
				$this->multiple_assign_define ( "$value" );
				// these are the variable assigns
			FOREACH ( $this->PATTERN_VARS_VARIABLE as $value )
				$this->multiple_assign ( "$value" );
				// end multiple parse section
			

			$append = FALSE;
			
			$this->LAST = $ReturnVar;
			$this->HANDLE [$ReturnVar] = 1;
			//echo "startparse $ReturnVar";
			

			if (gettype ( $FileTags ) == "array") {
				unset ( $this->$ReturnVar ); // Clear any previous data
				WHILE ( list ( $key, $val ) = each ( $FileTags ) ) {
					if ((! isset ( $this->$val )) || (empty ( $this->$val ))) {
						$this->LOADED ["$val"] = 1;
						if (isset ( $this->DYNAMIC ["$val"] )) {
							$this->parse_dynamic ( $val, $ReturnVar );
						} else {
							$fileName = $this->FILELIST ["$val"];
							$this->$val = $this->get_template ( $fileName );
						}
					}
					//  Array context implies overwrite
					$this->$ReturnVar = $this->parse_template ( $this->$val, $this->PARSEVARS );
					//  For recursive calls.
					$this->assign ( array ($ReturnVar => $this->$ReturnVar ) );
				}
				// end if FileTags is array()
			} else {
				// FileTags is not an array
				$val = $FileTags;
				
				if ((substr ( $val, 0, 1 )) == '.') {
					// Append this template to a previous ReturnVar
					$append = TRUE;
					$val = substr ( $val, 1 );
				}
				
				if ((! isset ( $this->$val )) || (empty ( $this->$val ))) {
					$this->LOADED ["$val"] = 1;
					if (isset ( $this->DYNAMIC ["$val"] )) {
						$this->parse_dynamic ( $val, $ReturnVar );
					} else {
						$fileName = $this->FILELIST ["$val"];
						$this->$val = $this->get_template ( $fileName );
					}
				}
				
				if ($append) {
					// changed by AiK
					if (isset ( $this->$ReturnVar )) {
						$this->$ReturnVar .= $this->parse_template ( $this->$val, $this->PARSEVARS );
					} else {
						$this->$ReturnVar = $this->parse_template ( $this->$val, $this->PARSEVARS );
					}
				} else {
					$this->$ReturnVar = $this->parse_template ( $this->$val, $this->PARSEVARS );
				}
				//  For recursive calls.
				$this->assign ( array ($ReturnVar => $this->$ReturnVar ) );
			}
			
			RETURN;
		} //  End parse()
		

		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc     - This method is called by FastWrite and FastPrint.
		 * @param    - $template the template name
		 * @return   - template string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function getfast($template = "") {
			if (empty ( $template )) {
				$template = $this->LAST;
			}
			
			if ((! (isset ( $this->$template ))) || (empty ( $this->$template ))) {
				$this->error ( "Nothing parsed, nothing printed", 0 );
				RETURN;
			} else {
				
				if (! get_magic_quotes_gpc ())
					$this->$template = stripslashes ( $this->$template );
				
				if ($this->IE_UTF_INCLUDE) {
					$user_agent = $_SERVER ['HTTP_USER_AGENT'];
					// IE7.0
				if (preg_match ( "/msie/i", $user_agent ) && preg_match ( "/[7]\.[0]/i", $user_agent ) && preg_match ( "/Windows/i", $user_agent ))
						$this->$template = strstr ( $this->$template, '<' );
				}
				
				if ($this->USE_CACHE) {
					$this->cache_file ( $this->$template );
				} else {
					RETURN $this->$template;
				}
				
				RETURN;
			}
		} // end getfast()
		

		/**
		 * @author   - Wilfried Trinkl - wisl@gmx.at
		 * @type     - private
		 * @desc     - Output the HTML-Code to a file.
		 * 			   The method FastWrite() write the contents of the named variable into a file.
		 * 				<code>
		 *					$tpl->FastWrite("output.html"); // continuing from the last example, would
		 *					$tpl->FastWrite("MAIN","output.html"); // print the value of MAIN
		 * 				</code>
		 *
		 * 			   This method is provided for convenience. If you need to print somewhere else (a socket,
		 * 			   file handle) you would want to fetch() a reference to the data first:
		 * 				<code>
		 * 					$data = $tpl->fetch("MAIN");
		 * 					fwrite($fd, $data);     // save to a file
		 * 				</code>
		 * 			   To write into a folder, depend on server configuration.
		 * @param    - $template the template name
		 * @param    - $outputfile the filename in which teh template will be written.
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function FastWrite($template = "", $outputfile) {
			$fp = fopen ( $outputfile, 'w' );
			if ($fp) {
				fwrite ( $fp, $this->getfast ( $template ) );
				fclose ( $fp );
			}
			;
			RETURN;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc     - Prints parsed template.
		 *			   The method FastPrint() prints the contents of the named variable.
		 * 			   If no variable is given, then it prints the last variable that was used
		 * 			   in a call to parse() which I find is a reasonable default.
		 *				<code>
		 * 					$tpl->FastPrint();
		 * 					// print the value of MAIN
		 * 					$tpl->FastPrint("MAIN"); // ditto
		 *				</code>
		 * 			   This method is provided for convenience. if you need to print somewhere ELSE (a socket, file handle) you would want to fetch() a reference to the data first:
		 *				<code>
		 * 					$data = $tpl->fetch("MAIN");
		 * 					fwrite($fd, $data);     // save to a file
		 * 				</code>
		 * @param    - $template the template name
		 * @param    - $return if TRUE template is returned.
		 * @return   - template string
		 * @vers     - 1.1
		 * @Mod by   - GraFXltd, for introduce visual template possibilities in editors like Dreamweaver, etc.
		 * @Mod vers -
		 */
		
		function FastPrint($template = "", $return = "") {
			if (is_array ( $this->REWRITE_TEMPLATE_PATH ))
				$tmp = str_replace ( $this->REWRITE_TEMPLATE_PATH [0], $this->REWRITE_TEMPLATE_PATH [1], $this->getfast ( $template ) );
			else
				$tmp = $this->getfast ( $template );
			
			if (! $return) {
				echo $tmp;
				RETURN "";
			} else
				RETURN $tmp;
		} // end FastPrint()
		

		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Set up the cache file..
		 * @param    - $fname cache filename.
		 * @return   - template string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function USE_CACHE($fname = "") {
			$this->USE_CACHE = TRUE;
			if ($fname) {
				$this->CACHING = $this->cache_path ( $fname );
			}
			$this->verify_cached_files ( $fname );
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Set up UPDT_TIME for cache time.
		 * @param    - $time the time for cache.
		 * @return   - template string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function setCacheTime($time) {
			$this->UPDT_TIME = $time;
		}
		
		/**
		 * @author   - GraFX Software Solutions
		 * @type     - public
		 * @desc     - Try to delete used cached files.
		 * @param    -
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   - rr1024 at gmail
		 * @Mod by   - GraFX Software Solutions
		 * @Mod vers - 0.1 removed trailing slash when specifing cache dir script must
		 *             supply the trailing slash. old code follows:
		 *             #if ( filemtime($dir."/".$fname) < $expired && $fname != "." && $fname != ".." && $ext[1]=="ft") {
		 *                  #@unlink($dir."/".$fname);
		 *             #}
		 * @Mod vers - 1.1.6 WHILE ( ($fname = readdir ( $dirlisting ))!=true ) { //boolean value was expected here
		 **/
		
		function DELETE_CACHE() {
			$this->DELETE_CACHE = TRUE;
			$expired = time () - $this->UPDT_TIME;
			$dir = $this->CACHE_PATH;
			$dirlisting = opendir ( $dir );
			WHILE ( ($fname = readdir ( $dirlisting )) != true ) {
				$ext = explode ( ".", $fname );
				if (filemtime ( $dir . $fname ) < $expired && $fname != "." && $fname != ".." && $ext [1] == "ft") {
					@unlink ( $dir . $fname );
				}
			}
			
			closedir ( $dirlisting );
		
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Verify if cache files are updated then RETURN cached page and exit
		 *             self_script() - RETURN script as called Fast Template class
		 * @param    -
		 * @return   - RETURN script as called Fast Template class
		 * @vers     - 1.0
		 * @Mod by   - rr1024 at gmail
		 * @Mod vers - 0.1 removed trailing slash when specifing cache dir script must
		 *             supply the trailing slash. old code follows:
		 *             include $this->self_script();
		 **/
		
		function verify_cached_files() {
			if (($this->USE_CACHE) && ($this->cache_file_is_updated ())) {
				if (! $this->CACHING) {
					include $this->self_script () . ".ft";
				} else {
					include $this->CACHING . ".ft";
				}
				
				exit ( 0 );
			}
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com and P. Pavlovic: ppavlovic@mail.ru
		 * @type     - public
		 * @desc     - Return script as called Fast Template class
		 *             changed in 1.1.9 $fname var from SCRIPT_NAME into REQUEST_URI
		 * @param    -
		 * @return   - RETURN script as called Fast Template class
		 * @vers     - 1.0
		 * @Mod by   - rr1024 at gmail
		 * @Mod vers - 0.1 removed trailing slash when specifing cache dir script must
		 *             supply the trailing slash. old code follows:
		 *             RETURN $this->CACHE_PATH.'/'.$fname;
		 **/
		
		function self_script($relativePath = "") {
			$fname = $_SERVER ['REQUEST_URI'];
			//$fname = getenv('SCRIPT_NAME');
			if (count ( $_SERVER ['argv'] )) {
				FOREACH ( $_SERVER ['argv'] as $val ) {
					$q [] = $val;
				}
				
				$fname .= join ( "_and_", $q );
			}
			
			$fname = md5 ( $fname );
			
			if ($relativePath) {
				RETURN $this->CACHE_PATH . $fname; // Used by include to reclain cache
			} else {
				RETURN $this->cache_path ( $fname ); // Used to write and check/revalidate cache lifetime
			}
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Return the real path for write cache files
		 *                out from 1.4.0
		 *                  $fname = explode("/",$fname);
		 *                  $fname = $fname[count($fname) - 1];
		 *                  RETURN $this->CACHE_PATH."/".$fname;
		 *                into in 1.4.0
		 * @param    - $fname file name and path
		 * @return   - RETURN script as called Fast Template class
		 * @vers     - 1.0
		 * @Mod by   - rr1024 at gmail
		 * @Mod vers - 0.1 removed trailing slash when specifing cache dir script must
		 *             supply the trailing slash. old code follows:
		 *             RETURN $_SERVER['DOCUMENT_ROOT'].$this->CACHE_PATH.'/'.basename($fname);
		 **/

		function cache_path($fname) {
			RETURN $this->CACHE_PATH . basename ( $fname );
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Return the real path for write cache files
		 * @param    -
		 * @return   - RETURN script as called Fast Template class
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 **/
		function self_script_in_cache_path() {
			RETURN $this->cache_path ( basename ( $this->self_script () ) );
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Verify if cache file is updated or expired.
		 * @param    -
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 **/
		function cache_file_is_updated() {
			if (! $this->CACHING) {
				$fname = $this->self_script_in_cache_path ();
			} else {
				$fname = $this->CACHING;
			}
			
			if (! file_exists ( $fname . ".ft" )) {
				RETURN FALSE;
			}
			
			//			Verification of cache expiration
			//		    filemtime() -> RETURN unix time of last modification in file
			//		    time() -> RETURN unix time
			

			$expire_time = time () - filemtime ( $fname . ".ft" );
			
			if ($expire_time >= $this->UPDT_TIME) {
				RETURN FALSE;
			} else {
				RETURN TRUE;
			}
		}
		
		/**
		 * @author   - Allyson Francisco de Paula Reis ragen@oquerola.com
		 * @type     - public
		 * @desc     - Caching method for FastTemplate.
		 * @param    -
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   - GraFX Software Solutions
		 * @Mod vers - 1.1.1
		 **/
		
		function cache_file($content = "") {
			if (($this->USE_CACHE) && (! $this->cache_file_is_updated ())) {
				if (! $this->CACHING) {
					$fname = $this->self_script_in_cache_path ();
				} else {
					$fname = $this->CACHING;
				}
				
				$fname = $fname . ".ft";
				// Opening $fname in writing only mode
				if (! $fp = fopen ( $fname, 'w' )) {
					$this->error ( "Error WHILE opening cache file ($fname)", 0 );
					RETURN;
				}
				// Writing $content to open file.
				if (! fwrite ( $fp, $content )) {
					$this->error ( "Error WHILE writing cache file ($fname)", 0 );
					RETURN;
				} else {
					fclose ( $fp );
					include $fname;
					RETURN;
				}
				
				fclose ( $fp );
			
			}
		} // end cache_file()
		

		/**
		 * @author   - Jason Moore
		 * @type     - private
		 * @desc     - Returns the raw data from a parsed handle.
		 *				<code>
		 *					$tpl->parse(CONTENT, "main");
		 *					$content = $tpl->fetch("CONTENT");
		 *					print $content;        // print to STDOUT
		 *					fwrite($fd, $content); // write to filehandle
		 * 				</code>
		 * @param    - $template the template name
		 * @return   - string
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function fetch($template = "") {
			if (empty ( $template )) {
				$template = $this->LAST;
			}
			
			if ((! (isset ( $this->$template ))) || (empty ( $this->$template ))) {
				$this->error ( "Nothing parsed, nothing printed", 0 );
				RETURN "";
			}
			
			RETURN ($this->$template);
		
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - define dynamic content within a static template.
		 * 				<code>
		 * 					$tpl = new FastTemplate("./templates");
		 * 					$tpl->define(    array( main  =>  "main.html",table =>  "dynamic.html" ));
		 * 					$tpl->define_dynamic( "row" , "table" );
		 * 				</code>
		 * 			   This tells FastTemplate that buried in the ``table'' template is a dynamic block, named ``row''. In older verions of FastTemplate (pre 0.7) this ``row'' template would have been defined as it's own file. Here's how a dynamic block appears within a template file;
		 * 				<code>
		 * 					<!-- NAME: dynamic.html -->
		 *  				<table>
		 *  				<!-- BEGIN DYNAMIC BLOCK: row -->
		 *  				<tr>
		 *   					<td>{NUMBER}</td>
		 *   					<td>{BIG_NUMBER}</td>
		 *  				</tr>
		 *  				<!-- END DYNAMIC BLOCK: row -->
		 * 					</table>
		 * 					<!-- END: dynamic.html -->
		 *
		 * 					</code>
		 *
		 * 			 The syntax of your BEGIN and END lines needs to be VERY exact. It is case sensitive.
		 * 			 The code block begins on a new line all by itself. There cannot be ANY OTHER TEXT on the line
		 * 			 with the BEGIN or END statement. (although you can have any amount of whitespace before or after).
		 * 			 It must be in the format shown: <br>
		 * 		     <b><!-- BEGIN DYNAMIC BLOCK: handle_name --></b> <br>
		 * 			 The line must be exact, right down to the spacing of the characters. The same is TRUE for your END line.
		 * 			 The BEGIN and END lines cannot span multiple lines. Now when you call the parse() method, FastTemplate will
		 * 			 automatically spot the dynamic block, strip it out, and use it exactly as if you had defined it as a
		 * 			 stand-alone template. No additional work is required on your part to make it work - just define it, and FastTemplate
		 * 			 will do the rest. Included with this archive should have been a file named define_dynamic.phtml which shows a working
		 * 			 example of a dynamic block.<br>
		 *           There are a few rules when using dynamic blocks - dynamic blocks should not be nested inside other dynamic blocks -
		 * 			 strange things WILL occur. You -can- have more than one nested block of code in a page, but of course, no two blocks
		 *           can share the same defined handle. The error checking for define_dynamic() is miniscule at best. if you define a
		 * 			 dynamic block and FastTemplate fails to find it, no errors will be generated, just really weird output.
		 * 			 (FastTemplate will not append the dynamic data to the retured output) Since the BEGIN and END lines are stripped out of
		 * 			 the parsed results, if you ever see your BEGIN or END line in the parsed output, that means that FastTemplate failed
		 * 			 to find that dynamic block.
		 * @param    - $Macro the dynamic part's name
		 * @param    - $ParentName the template's name
		 * @return   - boolean true
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function define_dynamic($Macro, $ParentName) {
			$this->DYNAMIC ["$Macro"] = $ParentName;
			RETURN TRUE;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Parse the dynamic zones given by the parameters.
		 * @param    - $Macro the dynamic part's name
		 * @param    - $ParentName the template's name
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function parse_dynamic($Macro, $MacroName) {
			// The file must already be in memory.
			//echo "parse_dynamic $Macro::$MacroName";
			$ParentTag = $this->DYNAMIC ["$Macro"];
			if ((! isset ( $this->$ParentTag )) or (empty ( $this->$ParentTag ))) {
				$fileName = $this->FILELIST [$ParentTag];
				$this->$ParentTag = $this->get_template ( $fileName );
				$this->LOADED [$ParentTag] = 1;
			}
			
			if ($this->$ParentTag) {
				$template = $this->$ParentTag;
				$DataArray = explode ( "\n", $template );
				$newMacro = "";
				$newParent = "";
				$outside = TRUE;
				$start = FALSE;
				$end = FALSE;
				
				WHILE ( list ( $lineNum, $lineData ) = each ( $DataArray ) ) {
					
					$lineTest = trim ( $lineData );
					
					if ("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						$start = TRUE;
						$end = FALSE;
						$outside = FALSE;
					}
					
					if ("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						$start = FALSE;
						$end = TRUE;
						$outside = TRUE;
					}
					
					if ((! $outside) and (! $start) and (! $end)) {
						$newMacro .= "$lineData\n";
					} // Restore linebreaks
					

					if (($outside) and (! $start) and (! $end)) {
						$newParent .= "$lineData\n";
					} // end Restore linebreaks
					

					if ($end) {
						$newParent .= '{' . "$MacroName}\n";
					}
					
					if ($end) {
						$end = FALSE;
					} // Next line please
					

					if ($start) {
						$start = FALSE;
					}
				} // end While
				

				$this->$Macro = $newMacro;
				$this->$ParentTag = $newParent;
				
				RETURN TRUE;
			
			} else { // $ParentTag NOT loaded - MAJOR oopsie
				@error_log ( "ParentTag: [$ParentTag] not loaded!", 0 );
				$this->error ( "ParentTag: [$ParentTag] not loaded!", 0 );
			}
			
			RETURN FALSE;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Strips a dynamic block from a template.
		 * 			   This provides a method to remove the dynamic block definition from the parent macro provided
		 * 			   that you haven't already parsed the template. Using our example above:
		 *
		 * 				<code>
		 * 					$tpl->clear_dynamic("row");
		 * 				</code>
		 *
		 * 			   Would completely strip all of the unparsed dynamic blocks named ``row'' from the parent template. This method won't do a thing if the template has already been parsed! (Because the required BEGIN and END lines have been removed through the parsing) This method works well when you are accessing a database, and your ``rows'' may or may not RETURN anything to print to the template. if your database query doesn't RETURN anything, you can now strip out the rows you've set up for the results.
		 * @param    - $Macro the dynamic part's name
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function clear_dynamic($Macro = "") {
			if (empty ( $Macro )) {
				RETURN FALSE;
			}
			
			$ParentTag = $this->DYNAMIC ["$Macro"]; // The file must already be in memory.
			

			if ((! $this->$ParentTag) or (empty ( $this->$ParentTag ))) {
				$fileName = $this->FILELIST [$ParentTag];
				$this->$ParentTag = $this->get_template ( $fileName );
				$this->LOADED [$ParentTag] = 1;
			}
			
			if ($this->$ParentTag) {
				$template = $this->$ParentTag;
				$DataArray = explode ( "\n", $template );
				$newParent = "";
				$outside = TRUE;
				$start = FALSE;
				$end = FALSE;
				
				WHILE ( list ( $lineNum, $lineData ) = each ( $DataArray ) ) {
					$lineTest = trim ( $lineData );
					if ("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						$start = TRUE;
						$end = FALSE;
						$outside = FALSE;
					}
					
					if ("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						$start = FALSE;
						$end = TRUE;
						$outside = TRUE;
					}
					
					if (($outside) and (! $start) and (! $end)) {
						$newParent .= "$lineData\n";
					} // Restore linebreaks
					

					if ($end) {
						$end = FALSE;
					} // Next line please
					

					if ($start) {
						$start = FALSE;
					}
				} // end While
				

				$this->$ParentTag = $newParent;
				
				RETURN TRUE;
			
			} else { // $ParentTag NOT loaded - MAJOR oopsie
				@error_log ( "ParentTag: [$ParentTag] not loaded!", 0 );
				$this->error ( "ParentTag: [$ParentTag] not loaded!", 0 );
			}
			
			RETURN FALSE;
		
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - The method define() maps a template filename to a (usually shorter) name;
		 * 				<code>
		 * 					$tpl = new FastTemplate("/path/to/templates");
		 * 					$tpl->define( array(    main    => "main.html", footer  => "footer.html" ));
		 * 				</code>
		 * 			   This new name is the name that you will use to refer to the templates.
		 * 			   Filenames should not appear in any place other than a define().<br> (Note: This is a required step!
		 * 			   This may seem like an annoying extra step when you are dealing with a trivial example like the one above,
		 * 			   but when you are dealing with dozens of templates, it is very handy to refer to templates with names that
		 * 			   are indepandant of filenames.)<br>
		 * 			   TIP: Since define() does not actually load the templates, it is faster and more legible to define all the
		 * 			   templates with one call to define(). <br>
		 * 			   Would completely strip all of the unparsed dynamic blocks named ``row'' from the parent template.
		 * 			   This method won't do a thing if the template has already been parsed! (Because the required BEGIN and END lines
		 * 			   have been removed through the parsing) This method works well when you are accessing a database, and your ``rows''
		 * 			   may or may not RETURN anything to print to the template. if your database query doesn't RETURN anything, you can now
		 * 			   strip out the rows you've set up for the results.
		 * @param    - $fileList mixed array with the template mapings,
		 * @param    - $value if $fileList is only a simple string then this is his value
		 * @return   - boolean true
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function define($fileList, $value = null) {
			if ((gettype ( $fileList ) != "array") && ! is_null ( $value ))
				$fileList = array ($fileList => $value ); //added by Voituk Vadim
			

			WHILE ( list ( $FileTag, $FileName ) = each ( $fileList ) ) {
				$this->FILELIST ["$FileTag"] = $FileName;
			}
			
			RETURN TRUE;
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Does the same thing as the clear() function
		 * @see clear()
		 * @param    - $ReturnVar the template to clear
		 * @return   - boolean true
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function clear_parse($ReturnVar = "") {
			$this->clear ( $ReturnVar );
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Clears the internal references that store data passed to parse().
		 * 			   Accepts individual references, or array references as arguments.
		 * 			   Note: All of the clear() functions are for use anywhere where your scripts
		 * 			   are persistant. They generally aren't needed if you are writing CGI scripts.
		 *
		 * 			   Often clear() is at the end of a script:
		 *
		 *				<code>
		 *					$tpl->FastPrint("MAIN");
		 *					$tpl->clear("MAIN");
		 *				</code>
		 *			  or
		 *				<code>
		 *					$tpl->FastPrint("MAIN");
		 *					$tpl->FastPrint("CONTENT");
		 *					$tpl->clear(array("MAIN","CONTENT"));
		 *				</code>
		 *			  If called with no arguments, removes all references that have been set via parse().
		 * @param    - $ReturnVar the template to clear
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function clear($ReturnVar = "") {
			
			if (! empty ( $ReturnVar )) {
				if ((gettype ( $ReturnVar )) != "array") {
					
					unset ( $this->$ReturnVar );
					RETURN;
				
				} else {
					
					WHILE ( list ( $key, $val ) = each ( $ReturnVar ) ) {
						unset ( $this->$val );
					}
					
					RETURN;
				}
			}
			
			WHILE ( list ( $key, $val ) = each ( $this->HANDLE ) ) { // Empty - clear all of them
				$KEY = $key;
				unset ( $this->$KEY );
			}
			
			RETURN;
		} //  end clear()
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Cleans the module of any data, except for the ROOT directory.
		 *			   This is equivalent to:
		 *
		 *				<code>
		 *					$tpl->clear_define();
		 *					$tpl->clear_href();
		 *					$tpl->clear_tpl();
		 *					$tpl->clear_parse();
		 *				</code>
		 * @param    -
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function clear_all() {
			$this->clear ();
			$this->clear_assign ();
			$this->clear_define ();
			$this->clear_tpl ();
			
			RETURN;
		} //  end clear_all
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Clears the internal array that stores the contents of the templates (if they have been loaded)
		 *			   If you are having problems with template changes not being reflected, try
		 * 			   adding this method to your script.
		 *				<code>
		 *					$tpl->define(MAIN,"main.html" );
		 * 				//( assign(), parse() etc etc...)
		 *				$tpl->clear_tpl(MAIN);    // Loaded template now unloaded.
		 *				</code>
		 * @param    - $fileHandle the name of template to clear.
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function clear_tpl($fileHandle = "") {
			if (empty ( $this->LOADED )) { // Nothing loaded, nothing to clear
				RETURN TRUE;
			}
			
			if (empty ( $fileHandle )) {
				// Clear ALL fileHandles
				WHILE ( list ( $key, $val ) = each ( $this->LOADED ) ) {
					unset ( $this->$key );
				}
				
				unset ( $this->LOADED );
				
				RETURN TRUE;
			} else {
				if ((gettype ( $fileHandle )) != "array") {
					if ((isset ( $this->$fileHandle )) || (! empty ( $this->$fileHandle ))) {
						unset ( $this->LOADED [$fileHandle] );
						unset ( $this->$fileHandle );
						RETURN TRUE;
					}
				} else {
					WHILE ( list ( $Key, $Val ) = each ( $fileHandle ) ) {
						unset ( $this->LOADED [$Key] );
						unset ( $this->$Key );
					}
					RETURN TRUE;
				}
			}
			
			RETURN FALSE;
		
		} // end clear_tpl
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Clears the internal list that stores data passed to:
		 *				<kbd>
		 *					$tpl->define();
		 *				</kbd>
		 *
		 *			   Note: The hash that holds the loaded templates is not touched with this method.
		 *             (See: clear_tpl() ) Accepts a single file handle, an array of file handles,
		 * 			   or nothing as arguments. if no argument is given, it clears ALL file handles.
		 *
		 *				<code>
		 *					$tpl->define( array( "MAIN" => "main.html","BODY" => "body.html", "FOOT" => "foot.html"  ));
		 *					//(some code here)
		 *					$tpl->clear_define("MAIN");
		 *				</code>
		 * @param    - $FileTag clear the template with this name(s).
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   - GraFX Software Solutions
		 * @Mod vers - 1.6.0
		 */
		
		function clear_define($FileTag = "") {
			if (empty ( $FileTag )) {
				unset ( $this->FILELIST );
				RETURN;
			}
			
			if ((gettype ( $FileTag )) != "array") {
				unset ( $this->FILELIST [$FileTag] );
				RETURN;
			} else {
				WHILE ( list ( $Tag, $Val ) = each ( $FileTag ) ) {
					unset ( $this->FILELIST [$Tag] );
				}
				RETURN;
			}
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Clears all variables set by assign()
		 * @param    -
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function clear_assign() {
			if (! (empty ( $this->PARSEVARS ))) {
				WHILE ( list ( $Ref, $Val ) = each ( $this->PARSEVARS ) ) {
					unset ( $this->PARSEVARS ["$Ref"] );
				}
			}
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Removes a given reference from the list of refs that is built using:
		 * 				<kbd>
		 *					$tpl->assign(KEY = val);
		 *				</kbd>
		 *
		 *			   If it's called with no arguments, it removes all references from the array.
		 *
		 *				<code>
		 *					$tpl->assign(    array(    "MOVIE"  =>  "The Avengers", "RATE"   =>  "BadMovie"    ));
		 *					$tpl->clear_href("MOVIE");
		 *				</code>
		 * @param    - $href - tag value(s)
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function clear_href($href) {
			if (! empty ( $href )) {
				if ((gettype ( $href )) != "array") {
					unset ( $this->PARSEVARS [$href] );
					RETURN;
				} else {
					FOREACH ( $href as $value )
						unset ( $this->PARSEVARS [$value] );
					
					RETURN;
				}
			} else {
				// Empty - clear them all
				$this->clear_assign ();
			}
			
			RETURN;
		}
		
		/**
		 * @author   - Voituk Vadim
		 * @type     - public
		 * @desc     - Assign template variables with the same names from array by specfied keys.
		 * 			   NOTE: template variables will be in upper case
		 * @param    - $Arr - names
		 * @param    - $Keys - keys
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function assign_from_array($Arr, $Keys) {
			if (gettype ( $Arr ) == "array") {
				foreach ( $Keys as $k )
					if (! empty ( $k ))
						$this->PARSEVARS [strtoupper ( $k )] = str_replace ( '&amp;#', '&#', $Arr [$k] );
			
			}
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Assign variables
		 * 			   This method assigns values for variables.
		 * 			   In order for a variable in a template to be interpolated it must be assigned.
		 * 			   There are two forms which have some important differences. The simple form is to
		 *             accept an array and copy all the key/value pairs into an array in FastTemplate.
		 * 			   There is only one array in FastTemplate, so assigning a value for the same key
		 * 			   will overwrite that key.
		 *
		 * 				<code>
		 * 					$tpl->assign(TITLE    => "king kong");
		 * 					$tpl->assign(TITLE    => "gozilla");    // overwrites "king kong"
		 * 				</code>
		 * @param    - $ft_array - names
		 * @param    - $trailer - keys
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function assign($ft_array, $trailer = "") {
			if (gettype ( $ft_array ) == "array") {
				WHILE ( list ( $key, $val ) = each ( $ft_array ) ) {
					if (! (empty ( $key ))) {
						//  Empty values are allowed
						//  Empty Keys are NOT
						// ORIG $this->PARSEVARS["$key"] = $val;
						if (! is_object ( $val ))
							$this->PARSEVARS ["$key"] = str_replace ( '&amp;#', '&#', $val );
						else //GRAFX && Voituk
							$this->PARSEVARS ["$key"] = $val; //GRAFX && Voituk
					}
				}
			} else {
				// Empty values are allowed in non-array context now.
				if (! empty ( $ft_array )) {
					// ORIG $this->PARSEVARS["$ft_array"] = $trailer;
					if (! is_object ( $trailer ))
						$this->PARSEVARS ["$ft_array"] = str_replace ( '&amp;#', '&#', $trailer );
					else //GRAFX
						$this->PARSEVARS ["$ft_array"] = $trailer; //GRAFX && Voituk
				}
			}
		}
		
		/**
		 * @author   - Christian Brandel cbrandel@gmx.de
		 * @type     - public
		 * @desc     - Return the value of an assigned variable.
		 *			   This method will RETURN the value of a variable that has been set via assign().
		 * 			   This allows you to easily pass variables around within functions by using the
		 * 			   FastTemplate class to handle ``globalization'' of the variables.
		 *
		 *			   For example:
		 *
		 * 				<code>
		 * 					$tpl->assign(array( "TITLE" => $title, "BGCOLOR"  => $bgColor, "TEXT" => $textColor ));
		 * 					$bgColor = $tpl->get_assigned("BGCOLOR");
		 * 				</code>
		 * @param    - $ft_name - template name
		 * @return   - boolean
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function get_assigned($ft_name = "") {
			if (empty ( $ft_name )) {
				RETURN FALSE;
			}
			
			if (isset ( $this->PARSEVARS ["$ft_name"] )) {
				RETURN ($this->PARSEVARS ["$ft_name"]);
			} else {
				RETURN FALSE;
			}
		}
		
		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Put an error message and stop (if requested)
		 * @param    - $errorMsg - the error message
		 * @param    - $die if 1 then the script will stop
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function error($errorMsg, $die = 0) {
			$this->ERROR = $errorMsg;
			
			echo "ERROR: $this->ERROR <BR> \n";
			
			if ($die == 1) {
				exit ();
			}
			
			RETURN;
		
		} // end error()
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - Pattern Assign
		 * 			   When variables or constants are the same as the template keys,
		 * 			   these functions may be used as they are. Using these functions, can help you
		 * 			   reduce the number of the assign functions in the php files.
		 *
		 * 			   Useful for language files where all variables or constants have the same
		 * 			   prefix.i.e. <i>$LANG_SOME_VAR</i> or <i>LANG_SOME_CONST</i><br>
		 * 			   The $pattern is <i>LANG</i> in this case.
		 * @param    - $pattern - pattern string
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function multiple_assign($pattern) {
			WHILE ( list ( $key, $value ) = each ( $GLOBALS ) ) {
				if (substr ( $key, 0, strlen ( $pattern ) ) == $pattern) {
					$this->assign ( strtoupper ( $key ), $value );
				}
			}
			
			reset ( $GLOBALS );
		
		} // multiple_assign
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - multiple_assign(), but for constants (defines)
		 * @param    - $pattern - pattern string
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function multiple_assign_define($pattern) {
			$ar = get_defined_constants ();
			FOREACH ( $ar as $key => $def )
				if (substr ( $key, 0, strlen ( $pattern ) ) == $pattern)
					$this->assign ( strtoupper ( $key ), $def );
		} // multiple_assign_define
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - Helpful when we want to run some filter before the template is parsed.
		 * @param    - $pattern - pattern string
		 * @param    - $replace - pattern string
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function pre_filter($pattern, $replace) {
			$this->PRE_FILTER [0] = $pattern;
			$this->PRE_FILTER [1] = $replace;
		} // pre_filter
		

		/**
		 * @author   -  Artyem V. Shkondin aka AiK artvs@clubpro.spb.ru
		 * @type     - public
		 * @desc     - Prints debug info into console.
		 * 			   Level 1 is showing all info + added WARNINGS
		 *             Level 2 will popup the window only if WARNINGS are present,
		 *             very helpfull only when you want to see BUGS on your page
		 * @param    - $Debug_type
		 * @return   - void
		 * @vers     - 1.0
		 * @since 	 - 1.1.1
		 * @Mod by   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @Mod vers -
		 */
		
		function showDebugInfo($Debug_type = null) {
			$tm = $this->utime () - $this->start;
			if ($Debug_type != null) {
				if ($Debug_type == 1) {
					// print time
					print "
                             <SCRIPT language=javascript>
                                  _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\");
                                  _debug_console.document.write('<html><title>Debug Console</title><body bgcolor=#ffffff>');
                                  _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>');
                                  ";
					
					if ($this->STRICT_DEBUG)
						$this->printarray ( $this->WARNINGS, "Warnings" );
					
					$this->printarray ( $this->FILELIST, "Templates" );
					$this->printarray ( $this->DYNAMIC, "Dynamic bloks" );
					$this->printarray ( $this->PARSEVARS, "Parsed variables" );
					
					print " _debug_console.document.close();
                             </SCRIPT> ";
				}
			} else {
				if ($Debug_type == 2) {
					if ($this->STRICT_DEBUG && sizeof ( $this->WARNINGS ) != 0) {
						// print time
						print "
                                  <SCRIPT language=javascript>
                                        _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\");
                                        _debug_console.document.write('<html><title>Debug Console</title><body bgcolor=#ffffff>');
                                        _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>');
                                        ";
						$this->printarray ( $this->WARNINGS, "Warnings" );
						
						print " _debug_console.document.close();
                                  </SCRIPT> ";
					}
				}
			}
		} //end of showDebugInfo()
		

		/**
		 * @author   - Jason Moore
		 * @type     - public
		 * @desc     - Debug printarray
		 * @param    - $arr
		 * @param    - $caption
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		function printarray($arr, $caption) {
			if (count ( $arr ) != 0) {
				print "
                        _debug_console.document.write('<font face=Tahoma color=#0000FF size=2><b>$caption</b> </font>');\n
                        _debug_console.document.write('<table border=0 width=100%  cellspacing=1 cellpadding=2>');
                        _debug_console.document.write('<tr bgcolor=#CCCCCC><th width=175>key</th><th>value</th></tr>');\n ";
				
				$flag = TRUE;
				
				WHILE ( list ( $key, $val ) = each ( $arr ) ) {
					
					$flag = ! $flag;
					$val = htmlspecialchars ( mysql_escape_string ( $val ) );
					
					if (! $flag) {
						$color = "#EEFFEE";
					} else {
						$color = "#EFEFEF";
					}
					
					print "
                             _debug_console.document.write('<tr bgcolor=$color><td> $key</td><td valign=bottom><pre>$val</pre></td></tr>');\n ";
				
				}
				
				print "
                        _debug_console.document.write(\"</table>\");";
			}
		} //
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - Pattern Assign
		 * 			   This is an extension of the earlier pattern assign.
		 * 			   The main advantage is that this way we can deal with pattern assign in dynamic parts too.
		 * 			   The old version is still ok keeping the backward compatibility but the new strategy
		 * 			   is a GLOBAL assignment of the patterns.
		 *
		 * 			   First we should initialize the pattern arrays with setPattern.
		 * 			   Helpful to initialize all the patterns.initialization can be made for
		 * 			   defines or variables, but not both on the same time.
		 * 			   	<code>
		 * 					$this->setPattern("LANG_"); //would apply for all defines begining with LANG_
		 *   				$this->setPattern(array("LANG_","CONF_")); it is the same only we use 2 patterns now LANG_ and CONF_
		 *   				//The variable part is simple, too.
		 *   				$this->setPattern("conf_",0);
		 * 				</code>
		 * @param 	 - $pattern can be array or just a simple string which contains the patterns
		 * @param    - $type $type = 1 we have defines, $type = 0(or any other number) we have vars; default is 1(defines)
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function setPattern($pattern, $type = 1) {
			/*
              $type = 1 we have defines
              $type = 0(or any other number) we have vars
              */
			if (is_array ( $pattern )) {
				FOREACH ( $pattern as $value )
					if ($type)
						$this->PATTERN_VARS_DEFINE ["$value"] = "$value";
					else
						$this->PATTERN_VARS_VARIABLE ["$value"] = "$value";
			
			} else if ($type)
				$this->PATTERN_VARS_DEFINE ["$pattern"] = "$pattern";
			else
				$this->PATTERN_VARS_VARIABLE ["$pattern"] = "$pattern";
		}
		
		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - Clean up all patterns.
		 * @param    -
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function emptyPattern() {
			$this->PATTERN_VARS_DEFINE [] = array ();
			$this->PATTERN_VARS_VARIABLE [] = array ();
		}
		
		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - Delete the specified pattern.
		 * @param    - $pattern can be array or just a simple string which contains the patterns
		 * @param    - $type $type = 1 we have defines, $type = 0(or any other number) we have vars; default is 1(defines)
		 * @return   - void
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */
		
		function deletePattern($pattern, $type = 1) {
			if ($type)
				unset ( $this->PATTERN_VARS_DEFINE ["$pattern"] );
			else
				unset ( $this->PATTERN_VARS_VARIABLE ["$pattern"] );
		}
		

		/**
		 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
		 * @type     - public
		 * @desc     - returns in an array all the variable names from the template
		 * @param    - array or string  $prefix is the prefix of the template vars we want to retrive LANG_
		 * @param    - string $template is the template we are parsing
		 * @return   - array
		 * @vers     - 1.0
		 * @Mod by   -
		 * @Mod vers -
		 */		
		function getPrefPatternVariables($prefix, $template) {
			if (is_array ( $prefix ))
				$prefix = implode ( "|", $prefix );
			
			if (empty ( $prefix ) || empty ( $template )) {
				return array ();
			}
			
			$pattern = '/(' . $prefix . ')([a-zA-Z_][a-zA-Z0-9_]+)/i';
			if (preg_match_all ( $pattern, $template, $matches, PREG_SET_ORDER )) {
				$tmp = array ();
				foreach ( $matches as $value )
					$tmp [] = $value [0];
				return $tmp;
			
			}
			return array ();
		
		}
		
	//  ************************************************************
	} // End cls_fast_template.php
} // end of if defined


// End cls_fast_template.php
?>
