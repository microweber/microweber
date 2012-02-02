<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   class.geshi.php
 *   Author: Nigel McNie
 *   E-mail: nigel@geshi.org
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 * This program is part of GeSHi.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 *
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

//
// Set error level to E_ALL. This stops strict warnings
// about syntax in PHP5, which we are not interested in
//
$geshi_old_reporting_level = error_reporting(E_ALL);

/** GeSHi Version */
define('GESHI_VERSION', '1.1.2alpha3');

/** Set the correct directory separator */
define('GESHI_DIR_SEP', ('WIN' != substr(PHP_OS, 0, 3)) ? '/' : '\\');

// Define the root directory for the GeSHi code tree
if (!defined('GESHI_ROOT')) {
    /** The root directory for GeSHi (where class.geshi.php is located) */
    define('GESHI_ROOT', dirname(__FILE__) . GESHI_DIR_SEP);
}

/**#@+
 * @access private
 */
/** The data directory for GeSHi */
define('GESHI_DATA_ROOT', GESHI_ROOT . 'geshi' . GESHI_DIR_SEP);
/** The classes directory for GeSHi */
define('GESHI_CLASSES_ROOT', GESHI_DATA_ROOT . 'classes' . GESHI_DIR_SEP);
/** The languages directory for GeSHi */
define('GESHI_LANGUAGES_ROOT', GESHI_DATA_ROOT . 'languages' . GESHI_DIR_SEP);
/** The context files directory for GeSHi */
define('GESHI_CONTEXTS_ROOT', GESHI_DATA_ROOT . 'contexts' . GESHI_DIR_SEP);
/** The theme files directory for GeSHi */
define('GESHI_THEMES_ROOT', GESHI_DATA_ROOT . 'themes' . GESHI_DIR_SEP);
/** The renderers directory for GeSHi */
define('GESHI_RENDERERS_ROOT', GESHI_CLASSES_ROOT . 'renderers' . GESHI_DIR_SEP);
/**#@-*/

/** Get required functions */
require GESHI_DATA_ROOT . 'functions.geshi.php';

/** Get styler class */
require GESHI_CLASSES_ROOT . 'class.geshistyler.php';

/** Get context class */
require GESHI_CLASSES_ROOT . 'class.geshicontext.php';

/** Get code context class */
require GESHI_CLASSES_ROOT . 'class.geshicodecontext.php';

//
// Although this classe may not be used by a particular language,
// there's a very good chance that it will be, so we include it
// now to save on require_once calls later. This improves performance
// when using an opcode cache/accelerator.
//

/** Get string context class */
require GESHI_CLASSES_ROOT . 'class.geshistringcontext.php';

//
// Constants
//

// These provide BACKWARD COMPATIBILITY ONLY
// Use New Method setStyles(mixed identifiers, string styles);
// e.g. setStyles('html/tag', 'styles');
//      setStyles(array('html/tag', 'html/css-delimiters'), 'style');

// @todo these may yet disappear, they're not used yet
/** Used to mark a context as having no equivalence in 1.0.X */
define('GESHI_STYLE_NONE', 0);
/** Used to mark a context as being like a number in 1.0.X */
define('GESHI_STYLE_NUMBERS', 1);
/** Used to mark a context as being like a comment in 1.0.X */
define('GESHI_STYLE_COMMENTS', 2);
/** Used to mark a context as being like a string in 1.0.X */
define('GESHI_STYLE_STRINGS', 3);
/** Used to mark a context as being like a symbol in 1.0.X */
define('GESHI_STYLE_SYMBOLS', 4);
/** Used to mark a context as being like a method in 1.0.X */
define('GESHI_STYLE_METHODS', 5);

// Security
if (!defined('GESHI_ALLOW_SYMLINK_PATHS')) {
    /** Whether to allow paths to contain symlinks */
    define('GESHI_ALLOW_SYMLINK_PATHS', false);
}

// Error related constants, not needed by users of GeSHi
// @todo won't be used anymore, just define the old ones for BC.
// Even they will disappear in 2.0

/** No error has occured */
define('GESHI_ERROR_NONE', 0);

/**#@+
 * @access private
 */

// Constants for specifying who (out of the parent or child) highlights the delimiter
// between the parent and the child. Note that if you view the numbers as two bit binary,
// a 1 indicates where the child parses and a 0 indicates where the parent should parse.
// The default is GESHI_CHILD_PARSE_BOTH
/** The child should parse neither delimiter (parent parses both) */
define('GESHI_CHILD_PARSE_NONE', 0);
/** The child should parse the right (end) delimiter, the parent should parse the left delimiter */
define('GESHI_CHILD_PARSE_RIGHT', 1);
/** The child should parse the left (beginning) delimiter, the parent should parse the right delimiter */
define('GESHI_CHILD_PARSE_LEFT', 2);
/** The child should parse both delimiters (default) */
define('GESHI_CHILD_PARSE_BOTH', 3);

// Tokenise levels
define('GESHI_COMPLEX_NO', 0);
define('GESHI_COMPLEX_PASSALL', 1);
define('GESHI_COMPLEX_TOKENISE', 2);
define('GESHI_COMPLEX_TOKENIZE', GESHI_COMPLEX_TOKENISE);

/**#@-*/


/**
 * The GeSHi class
 *
 * This class provides the public interface. The actual highligting is offloaded
 * to elsewhere.
 * 
 * @package    geshi
 * @subpackage core 
 * @author     Nigel McNie <nigel@geshi.org>
 * @version    1.1.2alpha3
 * @since      1.0.0
 * @todo       [blocking 1.1.9] Better documentation for this class
 */
class GeSHi
{
    
    // {{{ properties
    
    /**#@+
     * @access private
     */

    /**
     * The source code to parse
     * 
     * @var string
     */
    var $_source = '';

    /**
     * The name of the language to use when parsing the source
     * 
     * @var string
     */
    var $_language = '';

    /**
     * The humanised version of the language name
     * 
     * @todo used?
     */
    //var $_humanLanguageName = '';

    /**
     * The error code of any error that has occured
     * 
     * @var int
     */
    //var $_error = GESHI_ERROR_NONE;

    /**
     * The root context to use for parsing the source
     * 
     * @var GeSHiContext
     */
    var $_rootContext = null;
    
    /**
     * The GeSHiStyler object used by this class and all contexts for
     * assisting parsing.
     * 
     * @var GeSHiStyler
     */
    var $_styler = null;
    
    /**
     * Timing information for the last code parsing
     * 
     * @var array
     */
    var $_times = array();

    /**#@-*/
    
    // }}}
    // {{{ GeSHi()
    
    /**
     * Sets the source and language name of the source to parse
     *
     * Also sets up other defaults, such as the default encoding
     * 
     * <b>USAGE:</b>
     * 
     * <code> $geshi =& new GeSHi($source, $language);
     * // Various API calls... (todo: better examples)
     * $code = $geshi->parseCode();</code>
     *
     * @param string The source code to highlight
     * @param string The language to highlight the source with
     * @param string The path to the GeSHi data files. <b>This is no longer used!</b> The path is detected
     *               automatically by GeSHi, this paramters is only included for backward compatibility. If
     *               you want to set the path to the GeSHi data directories yourself, you should define the
     *               GESHI_ROOT constant before including class.geshi.php.
     * @since 1.0.0
     */
    function GeSHi ($source, $language_name, $path = '')
    {
        // Initialise timing
        // @todo [blocking 1.1.5] have to re-initialise timing if this object used many times
        $this->_initialiseTiming();

        // Create a new styler
        $this->_styler =& geshi_styler(true);

        // Set the initial source/language
        $this->setSource($source);
        $this->setLanguage($language_name);
        
        // @todo [blocking 1.1.5] Make third parameter an option array thing (maybe)
        //$this->setOutputFormat(GESHI_OUTPUT_HTML);
        //$this->setEncoding(GESHI_DEFAULT_ENCODING);

    }

    // }}}
    // {{{ setSource()
    
    /**
     * Sets the source code to highlight
     *
     * @param string The source code to highlight
     * @since 1.0.0
     */
    function setSource ($source)
    {
        $this->_source = strval($source);
    }

    // }}}
    // {{{ setLanguage()
    
    /**
     * Sets the language to use for highlighting
     *
     * @param string The language to use for highlighting
     * @since 1.0.0
     */
    function setLanguage ($language_name)
    {
        // Make a legal language name
        $this->_language = GeSHi::_cleanLanguageName($language_name);
        $this->_styler->language = $this->_language;
    }

    // }}}
    // {{{ getTime()
    
    /**
     * Returns various timings related to this object.
     *
     * For example, how long it took to load a specific context,
     * or parse the source code.
     *
     * You can pass a string to this method, it will return various timings based
     * on what string you pass:
     * 
     * <ul>
     *   <li>If you pass <b>'total'</b> (default), you will get the time it took to
     *   load, parse and post-process the last call to {@link GeSHi::parseCode()}.</li>
     *   <li>If you pass <b>'pre'</b>, you will get the time it took to load the last
     *   language. If caching of the root context is enabled, then this time will likely
     *   be close to zero if you are calling this method after second and subsequent calls
     *   to {@link GeSHi::parseCode()}.</li>
     *   <li>If you pass <b>'parse'</b>, you will get the time it took to parse the last
     *   time {@link GeSHi::parseCode()} was called.
     * </ul>
     *
     * @param  string What time you want to access
     * @return mixed The time if there is a time, else false if there was an error
     * @since  1.1.0
     */
    function getTime ($type = 'total')
    {
        if (isset($this->_times[$type])) {
            $start = explode(' ', $this->_times[$type][0]);
            $end   = explode(' ', $this->_times[$type][1]);
            return $end[0] + $end[1] - $start[0] - $start[1];
        } elseif ('total' == $type) {
            return $this->getTime('pre') + $this->getTime('parse') + $this->getTime('post');
        }
        trigger_error('GeSHi Error: type passed to getTime() is invalid');
        return false;
    }
    
    // }}}
    // {{{ setStyles()
    
    /**
     * Sets styles of contexts in the source code
     * 
     * @param string The selector to use, this is the style name of a context. Example: php/php
     * @param string The CSS styles to apply to the context
     * @since 1.1.1
     */
    function setStyles ($selector, $styles)
    {
        geshi_dbg('GeSHi::setStyles(' . $selector . ', ' . $styles . ')');
        $this->_styler->loadStyles('', true);
        $this->_styler->setRawStyle($selector, $styles);
    }
    
    // }}}
    // {{{ setTheme()
    
    /**
     * Sets the theme to use
     * 
     * This method can take a list of themes as well as an array or just one theme, e.g.:
     * 
     * <code> $geshi->setTheme('theme');
     * $geshi->setTheme(array('theme1', 'theme2'));
     * $geshi->setTheme('theme1', 'theme2');</code>
     * 
     * (note the difference between the second and third calls)
     * 
     * @param mixed The theme name(s)
     * @since 1.1.1
     */
    function setTheme ($themes)
    {
        // Passed in reverse order so priority is preserved
        for ($i = func_num_args() - 1; $i >= 0; $i--) {
            $this->_styler->useThemes(GeSHi::_clean(func_get_arg($i)));
        }
    }
    
    // }}}
    // {{{ getSupportedLanguages()

    /**
     * @todo document this function
     * @todo This and other methods share a lot of directory traversal
     * functionality, which could be split out somehow.
     * @todo actually, this should be implemented using a registry
     */
    function getSupportedLanguages ($return_human = false)
    {
        $languages = array();

        $ignore = array('.', '..', 'CVS');
        $dh = opendir(GESHI_LANGUAGES_ROOT);
        while (false !== ($dir = readdir($dh))) {
            if (in_array($dir, $ignore) || is_file(GESHI_LANGUAGES_ROOT . $dir)) continue;
            // Check the directory for the dialect files
            $ldh = opendir(GESHI_LANGUAGES_ROOT . $dir);
            while (false !== ($file = readdir($ldh))) {
                if (in_array($file, $ignore) || is_dir(GESHI_LANGUAGES_ROOT . "$dir/$file") || substr($file, -4) != '.php') continue;
                
                // Found a language file
                $file = substr($file, 0, -4);
                if ('common' == $file || 'class' == substr($file, 0, 5)) continue;

                if ($return_human) {
                    $languages["$dir/$file"] = GeSHi::getHumanLanguageName("$dir/$file");
                } else {
                    $languages[] = "$dir/$file";
                }
            }
        }

        return $languages;
    }

    // }}}  
    // {{{ getSupportedThemes()
    
    /**
     * Returns every theme supported by this installation of GeSHi
     * 
     * @param  bool $return_human  If <kbd>true</kbd>, the array returned is of
     *                             the form <kbd>theme_name => human name</kbd>,
     *                             otherwise it is an array of <kbd>theme_name</kbd>s
     * @return array A list of themes supported by GeSHi
     * @static
     * @since  1.1.1
     * @todo   This is expensive, possibly cache?
     */
    function getSupportedThemes ($return_human = false)
    {
        $themes = array();
        
        $ignore = array('.', '..', 'CVS');
        $dh = opendir(GESHI_THEMES_ROOT);
        while (false !== ($theme_folder = readdir($dh))) {
            if (in_array($theme_folder, $ignore) || is_file(GESHI_THEMES_ROOT . $theme_folder)) continue;
            if ($return_human) {
                $themes[$theme_folder] = GeSHi::getHumanThemeName($theme_folder);
            } else {
                $themes[] = $theme_folder;
            }
        }
        
        return $themes;
    }
    
    // }}}
    // {{{ themesSupportedBy()
    
    /**
     * Returns the themes supported by the given language
     * 
     * The names returned are in the form that GeSHi reads them, i.e. they
     * are not nice human strings. If you want the human form, use
     * {@link GeSHi::getHumanThemeName()} on each name returned. 
     * 
     * @param  string  $language The language to get supported themes for
     * @param  boolean $return_human If <kbd>true</kbd>, returns an array of
     *                               theme name => human-readable name. Otherwise,
     *                               just return an array of theme names.
     * @return array A list of themes supported by the language. Note that
     *               they are _not_ in preferred order
     * @static
     * @since 1.1.1
     * @todo  Make them in preferred order?
     * @todo  Expensive, maybe cache?
     */
    function themesSupportedBy ($language, $return_human = false)
    {
        $themes = array();
        //geshi_dbg('GeSHi::themesSupportedBy(' . $language . ')', GESHI_DBG_API);
        $language = GeSHi::_cleanLanguageName($language);
        //geshi_dbg('  language now ' . $language, GESHI_DBG_API);

        $dh = opendir(GESHI_THEMES_ROOT);
        while (false !== ($theme_folder = readdir($dh))) {
            if ('.' == $theme_folder || '..' == $theme_folder) continue;
            if (is_readable(GESHI_THEMES_ROOT . $theme_folder
                . GESHI_DIR_SEP . $language . '.php')) {
                if ($return_human) {
                    $themes[$theme_folder] = GeSHi::getHumanThemeName($theme_folder);
                } else {
                    $themes[] = $theme_folder;
                }
                
                // Check for subthemes
                $dh2 = opendir(GESHI_THEMES_ROOT . $theme_folder);
                while (false !== ($subtheme_folder = readdir($dh2))) {
                    if ('.' == $subtheme_folder || '..' == $subtheme_folder
                        || !is_dir(GESHI_THEMES_ROOT . $theme_folder . GESHI_DIR_SEP . $subtheme_folder)) continue;
                    if (is_readable(GESHI_THEMES_ROOT . $theme_folder . GESHI_DIR_SEP . $subtheme_folder
                        . GESHI_DIR_SEP . $language . '.php')) {
                        $subtheme_name = "$theme_folder/$subtheme_folder";
                        if ($return_human) {
                            $themes[$subtheme_name] = GeSHi::getHumanThemeName($subtheme_name);
                        } else {
                            $themes[] = $subtheme_name;
                        }
                    }
                }
            }
        }
        
        return $themes;
    }
    
    // }}}
    // {{{ languagesSupportedBy()
    
    /**
     * Returns the languages supported by the given theme
     * 
     * @param  string $theme The theme to get supported languages for
     * @return array  A list of languages supported by the theme, in the form:
     * <pre> array(
     *      'language' => array('dialect', 'dialect', ...),
     *      'language' => array('dialect', ...)
     * );</pre>
     * 
     * @static
     * @since 1.1.1
     */
    function languagesSupportedBy ($theme)
    {
        //geshi_dbg('GeSHi::languagesSupportedBy(' . $theme . ')', GESHI_DBG_API);
        $languages = array();
        $theme = GeSHi::_clean($theme);
        //geshi_dbg('  theme now ' . $theme, GESHI_DBG_API);
        $theme_file = GESHI_THEMES_ROOT . $theme . GESHI_DIR_SEP . 'themeinfo.php';
        if (is_readable($theme_file)) {
            require $theme_file;
            return $languages;
        }
        return array();
    }
    
    // }}}
    // {{{ getHumanLanguageName()
    
    /**
     * Given a language name, return a human version of it
     * 
     * @param  string $language The language name to get the human version of
     * @return string The human language name, or <kbd>false</kbd> if the
     *                language does not exist
     * @static
     * @todo actually implement this function
     * @since 1.1.2
     */
    function getHumanLanguageName ($language)
    {
        $human_name = '';
        $language = GeSHi::_clean($language);
        return $language;
    }
    
    // }}}
    // {{{ getHumanThemeName()
    
    /**
     * Given a theme name, return a human version of it
     * 
     * @param  string $theme The theme name to get the human version of
     * @return string The human theme name, or <kbd>false</kbd> if the
     *                theme does not exist
     * @static
     * @since 1.1.1
     */
    function getHumanThemeName ($theme)
    {
        $human_name = '';
        $theme = GeSHi::_clean($theme);
        $theme_file = GESHI_THEMES_ROOT . $theme . GESHI_DIR_SEP . 'themeinfo.php';
        if (is_readable($theme_file)) {
            require $theme_file;
            return $human_name;
        }
        return false;
    }
    
    // }}}
    // {{{ themeSupportsLanguage()
    
    /**
     * Given a theme and language, returns whether the them
     * supports that language
     * 
     * @param  string $theme    The name of the theme to check
     * @param  string $language The name of the language to check
     * @return boolean          Whether the language supports the theme
     * @static
     * @since 1.1.1
     */
    function themeSupportsLanguage ($theme, $language)
    {
        $language = GeSHi::_cleanLanguageName($language);
        return geshi_can_include(GESHI_THEMES_ROOT . $theme . GESHI_DIR_SEP . $language . '.php');
    }
    
    // }}}
    // {{{ getVersion()
    
    /**
     * Returns the version of this GeSHi
     * 
     * @return string The version of this GeSHi
     * @static
     * @since  1.1.0
     */
    function getVersion ()
    {
        return GESHI_VERSION;
    }
    
    // }}}
    // {{{ parseCode()
    
    /**
     * Syntax-highlights the source code
     * 
     * @return string The source code, highlighted
     * @since 1.0.0
     */
    function parseCode ()
    {
        $this->_times['pre'][0] = microtime();
        $result = $this->_parsePreProcess();
        $this->_times['pre'][1] = $this->_times['parse'][0] = microtime();

        if ($result) {
            // The important bit - parse the code
            $this->_rootContext->parseCode($this->_source);
        }

        $this->_times['parse'][1] = $this->_times['post'][0] = microtime();
        $result = $this->_parsePostProcess();
        $this->_times['post'][1] = microtime();

        return $result;
    }

    // }}}
    
    //
    // Private Methods
    //

    /**#@+
     * @access private
     */
    
    // {{{ _initialiseTiming()
    
    /**
     * Resets timing for this GeSHi object
     */
    function _initialiseTiming ()
    {
        $initial_times = array(0 => '0 0', 1 => '0 0');
        $this->_times = array(
            'pre'   => $initial_times,
            'parse' => $initial_times,
            'post'  => $initial_times
        );
    }
    
    // }}}
    // {{{ _parsePreProcess ()
    
    /**
     * Prepare the source code for parsing
     */
    function _parsePreProcess ()
    {
        // Strip newlines to common form
        $this->_source = str_replace("\r\n", "\n", $this->_source);
        $this->_source = str_replace("\r", "\n", $this->_source);
        
        // Get data
        // This just defines a few functions (geshi_$langname_$dialectname[_$contextname])
        $file = $this->_getLanguageDataFile();
        if (geshi_can_include($file)) {
            require_once $file;
        } else {
            $file = GESHI_LANGUAGES_ROOT . 'default/default.php';
            if (geshi_can_include($file)) {
                require_once $file;
            } else {
                // @todo [blocking 1.1.2] graceful error handling when a
                // language does not exist
                trigger_error('Language does not exist', E_USER_ERROR);
            }
        }
        
        // Build the context tree. This creates a new context which calls a function which may
        // define children contexts etc. etc.
        $this->_rootContext =& new GeSHiCodeContext($this->_language);


        // Load the code parser if necessary
        $language_name   = substr($this->_language, 0, strpos($this->_language, '/'));
        $codeparser_name = 'geshi' . $language_name . 'codeparser';
        if (!class_exists($codeparser_name)) {
            $codeparser_file = GESHI_LANGUAGES_ROOT . $language_name . GESHI_DIR_SEP
                . "class.{$codeparser_name}.php";
            if (geshi_can_include($codeparser_file)) {
                /** Get the GeSHiCodeParser class */
                require_once GESHI_CLASSES_ROOT . 'class.geshicodeparser.php';
                /** Get the language code parser */
                require_once $codeparser_file;
            }
        }

        // Now the code parser (if existing) has been included, create it if it is defined
        if (class_exists($codeparser_name)) {
            // Get the code parser
            $codeparser =& new $codeparser_name($this->_language);
            // Call the source preprocessing method
            $this->_source = $codeparser->sourcePreProcess($this->_source);
            // Tell the styler about the code parser
            $this->_styler->setCodeParser($codeparser);
        }
        
        // Reset the styler parse data
        $this->_styler->resetParseData();
        // Remove contexts from the parse tree that aren't interesting
        $this->_rootContext->trimUselessChildren($this->_source);
        
        return true;
    }

    // }}}
    // {{{ _parsePostProcess()
    
    /**
     * Recieves the result string from GeSHiStyler. The result string will
     * have gone through the renderer and so be ready to use.
     * 
     * This method makes sure error cases are handled, and frees any memory
     * used by the parse run
     *  
     * @return The code, post-processed.
     */
    function _parsePostProcess ()
    {
        // @todo [blocking 1.1.5] (bug 5) Evaluate feasability and get working if possible the functionality below...
        //$result = preg_replace('#([^"])(((https?)|(ftp))://[a-z0-9\-]+\.([a-z0-9\-\.]+)+/?([a-zA-Z0-9\.\-_%]+/?)*\??([a-zA-Z0-9=&\[\];%]+)?(\#[a-zA-Z0-9\-_]+)?)#', '\\1<a href="\\2">\\2</a>', $result);
        //$result = preg_replace('#([a-z0-9\._\-]+@[[a-z0-9\-\.]+[a-z]+)#si', '<a href="mailto:\\1">\\1</a>', $result);
        // Destroy root context, we don't need it anymore
        $this->_rootContext = null;
        // Get code
        $code = $this->_styler->getParsedCode();
        // Trash the old GeSHiStyler
        $this->_styler =& geshi_styler(true);
        return $code;        
    }
    
    // }}}
    // {{{ _getLanguageDataFile()
    
    /**
     * Helper function to convert a language name to the file name where its data will reside
     * 
     * @return The absolute path of the language file where the current language data will be sourced
     * @todo only used in one place, can be removed?
     */
    function _getLanguageDataFile ()
    {
        if ('/' == GESHI_DIR_SEP) {
            $language_file = $this->_language . '.php';
        } else {
            $language_file = explode('/', $this->_language);
            $language_file = implode(GESHI_DIR_SEP, $language_file) . '.php';
        }
        return GESHI_LANGUAGES_ROOT . $language_file;
    }
    
    // }}}
    // {{{ _clean()
    
    /**
     * Removes all characters other than a-z, 0-9 and / from the input
     * 
     * @param  mixed $data Input to clean, can be a string or array 
     * @return mixed The data in "clean" form
     */
    function _clean ($data)
    {
        return preg_replace('#[^a-z0-9/]#', '', $data);
    }
    
    // }}}
    // {{{ _cleanLanguageName()
    
    /**
     * Given a string, converts it into appropriate form for use as a
     * language name.
     * 
     * @param  string $language The string to convert
     * @return string The converted language name
     */
    function _cleanLanguageName ($language)
    {
        $language = strtolower(strval($language));
        if (false === strpos($language, '/')) {
            $language .= '/' . $language;
        }
        $language = GeSHi::_clean($language);
        if (substr($language, -6) == 'common') {
            trigger_error('Cannot use "common" as a language dialect');
            $language = substr($language, 0, strpos($language, '/'));
            $language = "$language/$language";
        }
        return $language;
    }
    
    // }}}
    
    /**#@-*/

    //
    // Deprecated methods
    //
    
    // {{{ error()
    
    /**
     * From 1.2.0, this method always returns false. This method is deprecated
     * and will disappear in the next major version of GeSHi.
     * 
     * @return false Always
     * @since  1.0.0
     * @deprecated
     */
    function error ()
    {
        return false;
    }

    // }}}
    // {{{ set_source()
    
    /**
     * Sets the source code to highlight. This method is deprecated, and will be
     * removed in 1.4/2.0.
     *
     * @param string The source code to highlight
     * @since 1.0.0
     * @deprecated In favour of {@link setSource()}
     */
    function set_source ($source)
    {
        $this->setSource($source);
    }

    // }}}
    // {{{ set_language()
    
    /**
     * Sets the language to use for highlighting. This method is deprecated, and
     * will be removed in the next major version of GeSHi.
     *
     * @param string The language to use for highlighting
     * @since 1.0.0
     * @deprecated In favour of {@link setLanguage()}
     */
    function set_language($language_name)
    {
        $this->setLanguage($language_name);
    }
    
    // }}}
    
}

// Reset error reporting level
error_reporting($geshi_old_reporting_level);

?>
