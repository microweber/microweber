<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/c/class.geshiccodeparser.php
 *   Author: Netocrat
 *   E-mail: N/A
 * </pre>
 *
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 *
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package    geshi
 * @subpackage lang
 * @author     http://clc-wiki.net/wiki/User:Netocrat
 * @link       http://clc-wiki.net/wiki/Development:GeSHi_C Bug reports
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2006 Netocrat
 * @version    1.1.2alpha3
 *
 */

/** Get the GeSHiCodeParser class */
require_once GESHI_CLASSES_ROOT . 'class.geshicodeparser.php';
/** for geshi_c_get_non_std_preproc_directives_url() */
require_once GESHI_LANGUAGES_ROOT . 'c' . GESHI_DIR_SEP . 'common.php';

/**
 * Parsing states; should be powers of two due to the implications of the
 * comment below.
 */
define('GESHI_C_NORMAL'             ,    0);
/**
 * Defines for 2, 4, 512, 1024 and 2048 are skipped intentionally so that
 * GESHI_C_PP can be tested for using a bitwise-and even when GESHI_C_PPINCLUDE,
 * GESHI_C_PPNONSTD, GESHI_C_PPERROR, GESHI_C_PPPRAGMA or GESHI_C_PPLINE are
 * true - this is not really necessary because in the only place that the test
 * is used those directives will currently otherwise test false, but it doesn't
 * cost any extra code or processing to allow this as an abstraction.
 */
define('GESHI_C_PP'                 ,    1);
define('GESHI_C_PPINCLUDE'          ,    3);
define('GESHI_C_PPNONSTD'           ,    5);
define('GESHI_C_PPSTART'            ,    8);
define('GESHI_C_PPHDRSTART'         ,   16);
define('GESHI_C_PPIMPLHDRSTART'     ,   32);
define('GESHI_C_PPHDRPROV'          ,   64);
define('GESHI_C_PPINCLUDEMACROSTART',  128);
define('GESHI_C_PPINCLUDEMACRO'     ,  256);
define('GESHI_C_PPERROR'            ,  513);
define('GESHI_C_PPPRAGMA'           , 1025);
define('GESHI_C_PPLINE'             , 2049);

/**
 * The GeSHiCCodeParser class
 *
 * @package    geshi
 * @subpackage lang
 * @author     http://clc-wiki.net/wiki/User:Netocrat
 * @since      1.1.1
 * @version    1.1.2alpha3
 */
class GeSHiCCodeParser extends GeSHiCodeParser
{

    // {{{ properties

    /**#@+
     * @access private
     */

    /**
     * A flag that can be used for the "state" of parsing
     *
     * @var string
     */
    var $_state = GESHI_C_NORMAL;

    /**
     * Whether to highlight preprocessor sub-directives that are valid only
     * within a #if or #elif block - currently only "defined" applies.
     *
     * @var boolean
     */
    var $_hltIfElifPPkeyWords;

    /**
     * Whether we've yet found an initial hash for a preprocessor directive.
     *
     * @var boolean
     */
    var $_initial_hash;

    /**
     * The incrementally-built header filename within the <> of #include
     *
     * @var array
     */
    var $_provisional_hdr;

    /**
     * A list of the locations where "\\\n" occurs in the source (i.e. a
     * backslash followed by a newline).
     *
     * @var array
     */
    var $_escapedNewlineLocations = array();

    /**
     * The current location that we are up to when parsing the source. Used to
     * re-insert the "\\\n" occurrences
     *
     * @var int
     */
    var $_parseLocation = 0;

    /**#@-*/

    // }}}
    // {{{ parseToken()

    function parseToken ($token, $context_name, $data)
    {
        $flush_stdhdr = false;
        $ret = array(&$token, &$context_name, &$data);

        // Detect the start of a preprocessor directive and set state.
        if (($context_name == $this->_language.'/preprocessor/start' ||
          $context_name == $this->_language.'/preprocessor/directive') &&
          $this->_state == GESHI_C_NORMAL) {
            $this->_state = GESHI_C_PPSTART;
            $this->_initial_hash = false;
            $this->_hltIfElifPPkeyWords = false;
        }

        $skipfirst = false;
        // Highlight and link preprocessor directives; set preliminary state.
        if ($context_name == $this->_language.'/preprocessor/end') {
            $this->_state = GESHI_C_NORMAL;
        } else if ($this->_state == GESHI_C_PPSTART) {
            if ($token == '#') {
                $this->_initial_hash = true;
            } else if (in_array($token,
              geshi_c_get_start_of_line_PP_directives_hashsym())) {
                $skipfirst = true;
                $data['url'] =
                  geshi_c_get_start_of_line_PP_directives_hashsym_url(
                    $token);
                $this->_state =
                  ($token == 'include' ? GESHI_C_PPINCLUDE :
                  ($token == 'error'   ? GESHI_C_PPERROR   :
                  ($token == 'pragma'  ? GESHI_C_PPPRAGMA  :
                  ($token == 'line'    ? GESHI_C_PPLINE      :
                   GESHI_C_PP))));
                $this->_hltIfElifPPkeyWords = in_array($token,
                  geshi_c_get_if_elif_PP_directives());
                $context_name = $this->_language.'/preprocessor/directive';
            } else if (in_array($token,
              geshi_c_get_start_of_line_PP_directives_nohashsym()) &&
              !$this->_initial_hash) {
                $skipfirst = true;
                $data['url'] =
                  geshi_c_get_start_of_line_PP_directives_nohashsym_url(
                    $token);
                $context_name = $this->_language.'/preprocessor/directive';
                $this->_state = $token=='_Pragma'? GESHI_C_PPPRAGMA: GESHI_C_PP;
            } else if ($this->_initial_hash &&
              !geshi_is_whitespace($token) && $token != '\\') {
                $data['url'] = geshi_c_get_non_std_preproc_directives_url();
                $context_name = $this->_language.'/preprocessor/directive';
                $this->_state = GESHI_C_PPNONSTD;
            }
        }

        // Perform highlighting/context adjustments based on preliminary state;
        // adjust state as required.
        switch ($this->_state) {
        case GESHI_C_PPPRAGMA:
        case GESHI_C_PPERROR:
            // Remove from #pragma and #error directives the highlighting of
            // all sub-contexts except comments and the directives themselves
            $start = $this->_language.'/preprocessor/';
            $start_len = strlen($start);
            if (substr($context_name, 0, $start_len) == $start) {
                $sub_context = substr($context_name, $start_len);
                if (!in_array($sub_context, array('directive', 'multi_comment',
                  'multi_comment/start', 'multi_comment/end',
                  'single_comment', 'single_comment/start'))) {
                    $context_name = substr($start, 0, -1);
                    $data['url'] = null;
                }
            }
            break;
        case GESHI_C_PPNONSTD:
            // Mark everything following a non-standard preprocessor directive;
            // also mark the directive itself.
            $context_name = $this->_language.'/preprocessor/nonstd';
            break;
        case GESHI_C_PPINCLUDE:
            // Highlight and link standard headers; also concatenate tokenised
            // header names into a single token to remove symbol contexts.
            if ($token{0} == '<') {
                $this->_state = GESHI_C_PPHDRSTART;
                // special-case handling for e.g. </dir/file.h> where "</" will
                // be tokenised as a single symbol
                $this->_provisional_hdr = substr($token, 1);
                $token = '<';
                $context_name = $this->_language.
                  '/preprocessor/symbol/stdinclude';
            } else if ($token == '"') {
                // Override the highlighting as a string literal of
                // "headername.h" in #include "headername.h" (mostly to avoid
                // the highlighting of escape sequences such as \n and \t -
                // they aren't defined in that context)
                $this->_state = GESHI_C_PPIMPLHDRSTART;
                $context_name = $this->_language.'preprocessor/implheader';
            } else if (trim($token) != '' && !$skipfirst) {
                // String literals and < and > characters could occur as part of
                // a macro that's substituting for the header that follows
                // #include, so allow their highlighting in that context.
                $this->_state = GESHI_C_PPINCLUDEMACROSTART;
                // Don't highlight the macro name itself though; an exception
                // can be made of any standard macros that resolve to a quoted
                // string (TODO).
                $context_name = $this->_language.'/preprocessor';
                $data['url'] = null;
            }
            break;
        case GESHI_C_PPLINE:
            if (trim($token) != '' && !$skipfirst) {
                // Avoid highlighting when a macro substitutes for the #line
                // line number (optionally +filename) in the same way as when a
                // macro substitutes for a #include header.
                if ($context_name != $this->_language.'/preprocessor/num/int') {
                    $this->_state = GESHI_C_PPINCLUDEMACROSTART;
                    $context_name = $this->_language.'/preprocessor';
                    $data['url'] = null;
                } else $this->_state = GESHI_C_PP;
            }
            break;
        case GESHI_C_PPINCLUDEMACROSTART:
            if ($token == '(') $this->_state = GESHI_C_PPINCLUDEMACRO;
            break;
        case GESHI_C_PPINCLUDEMACRO:
            // Per the comments in c.php, restrict highlighting for the
            // arguments of macros that substitute for a #include's header or a
            // #line's line number (and filename).
            if ($context_name == $this->_language.
              '/preprocessor/declarator-keyword' ||
              $context_name==$this->_language.'/preprocessor/ctlflow-keyword') {
                $context_name = $this->_language.'/preprocessor';
                $data['url'] = null;
            }
            break;
        case GESHI_C_PPHDRSTART:
            if ($token == '>') {
                $context_name = $this->_language.
                  '/preprocessor/symbol/stdinclude';
                if (in_array($this->_provisional_hdr,
                  geshi_c_get_standard_headers())) {
                    $flush_stdhdr = 'STDLINK';
                } else $flush_stdhdr = true;
                $this->_state = GESHI_C_PP;
            } else {
                $this->_provisional_hdr .= $token;
                $ret = false;
            }
            break;
        case GESHI_C_PPIMPLHDRSTART:
            $context_name = $this->_language.'preprocessor/include/implheader';
            if ($token == '"') $this->_state = GESHI_C_PP;
            break;
        }

        if ($flush_stdhdr) {
            $hdrtoken[0] = $this->_provisional_hdr;
            $hdrtoken[1] = $this->_language.'/preprocessor/include';
            if ($flush_stdhdr === 'STDLINK') {
                $hdrtoken[1] .= '/stdheader';
                $hdrtoken[2]['url'] = geshi_c_get_standard_headers_url(
                  $this->_provisional_hdr);
            } else {
                $hdrtoken[1] .= '/nonstdheader';
                // consider: would it be appropriate to instead implement and
                // call a geshi_c_get_non_std_stdheader_url()?
                $hdrtoken[2]['url'] = null;
            }
            $tmp = array($hdrtoken, $ret);
            $ret = $tmp;
            $this->_provisional_hdr = ''; // redundant but included for safety
        }

        //
        // Highlight and link sub-directives that can only occur within a #if or
        // #elif preprocessor directive (i.e. "defined").
        //
        if (($this->_state & GESHI_C_PP) && !$skipfirst &&
          $this->_hltIfElifPPkeyWords) {
            if (in_array($token, geshi_c_get_if_elif_PP_subdirectives())) {
                $data['url'] = geshi_c_get_if_elif_PP_subdirectives_url($token);
                $context_name = 'c/c/preprocessor/directive';
            }
        }

        //
        // Now we look at the data we have from looking at the source before
        // parsing began.  This data tells us where "\\\n" occurs in the
        // source. We took them out then so that the parser could highlight
        // everything normally, but now we need to put them back in.
        //
        // The possibility that sometimes $ret may contain an array of tokens
        // complicates things somewhat, but hopefully this code is portable
        // enough to handle that.
        //

        // Firstly: if we are storing data for later, return
        if (false === $ret) {
            return false;
        }

        // Check that there is a location to search for. Code below removes
        // occurrences as they happen so once there are no more occurrences
        // this will never execute.
        if (isset($this->_escapedNewlineLocations[0])) {
            $result = array();
            $location = $this->_parseLocation;

            // Get a copy of the token (not a reference) that we can clobber as
            // we please, and put into the "array of tokens" form.
            if (!is_array($ret[0])) {
                $thetokens = array($ret);
                $length = strlen($thetokens[0][0]);
            } else {
                // This makes the assumption that returned array of arrays only
                // has two elements. This is true for the code parser as of
                // 2006/06/17, if it is not true in the future then a more
                // portable length gathering loop will have to be written
                $thetokens = $ret;
                $length = strlen($thetokens[0][0]) + strlen($thetokens[1][0]);
            }

            foreach ($thetokens as $eachtoken) {
                $sublocation = 0;
                // Check to see if the next occurrence happens inside this
                // token (the while loop allows the check to be done multiple
                // times for the same token)
                while (isset($this->_escapedNewlineLocations[0]) &&
                    $location + strlen($eachtoken[0]) - $sublocation >=
                    $this->_escapedNewlineLocations[0]) {
                    // If inside the loop then we found an occurrence, this
                    // gets the position of the occurrence inside the current
                    // token.
                    $pos=array_shift($this->_escapedNewlineLocations)-$location;
                    // Store the part before the occurrence
                    $result[] = array(substr($eachtoken[0], $sublocation, $pos),
                        $eachtoken[1], $eachtoken[2]);
                    // Store the occurrence
                    $result[] = array("\\\n", $this->_language.
                      '/symbol/line-continuation', array());
                    // Do some fancy math:
                    //   - the base location ($location) has increased
                    //   - the location inside the token ($sublocation) has
                    //     increased
                    //   - the length of the string has increased because of
                    //     the occurrence
                    $location += $pos + 2;
                    $sublocation += $pos;
                    $length += 2;
                }
                // Now we add what ever is left after adding occurrences. This
                // may be the entire token if no occurrences happened inside it.
                $result[] = array(substr($eachtoken[0], $sublocation),
                    $eachtoken[1], $eachtoken[2]);
            }

            // Increment where we are up to
            $this->_parseLocation += $length;
            return $result;
        }

        // No fancy "\\\n" replacement happening (note at this point we have
        // given up incrementing the parse location because it's no longer
        // needed).
        return $ret;
    }

    // }}}
    // {{{ sourcePreProcess()

    /**
     * Finds locations of "\\\n" and removes them from the source.
     *
     * They are re-inserted when parseToken is being called.
     *
     * @param  string $code The source code to use
     * @return string       The code with all instances of "\\\n" removed
     */
    function sourcePreProcess ($code)
    {
        // Find all places where "\\\n" occur, and store a list of those
        // locations
        $pos = -1;
        while (false !== ($pos = strpos($code, "\\\n", $pos + 1))) {
            $this->_escapedNewlineLocations[] = $pos;
        }

        // Remove all occurrences of "\\\n" so they don't interfere with parsing
        $code = str_replace("\\\n", '', $code);
        return $code;
    }

    // }}}

}

?>
