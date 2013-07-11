<?php

/// @cond CORE

/**
 * @file 
 * @brief The base scanner classes
 *
 * This file contains the base scanning classes. All language scanners should
 * subclass one of these. They are all essentially abstract as far as Luminous
 * is concerned, but it may occasionally be useful to instantiate one.
 *
 * The classes defined here follow a simple hierarchy: Scanner defines basic
 * string scanning methods, LuminousScanner extends this with logic useful to 
 * highlighting. LuminousEmbeddedWebScript adds some helpers for web languages,
 * which may be nested in other languages. LuminousSimpleScanner is a 
 * generic flat scanner which is driven by token rules. 
 * LuminousStatefulScanner is a generic transition table driven scanner.
 */

require_once(dirname(__FILE__) . '/strsearch.class.php');
require_once(dirname(__FILE__) . '/utils.class.php');
require_once(dirname(__FILE__) . '/filters.class.php');
require_once(dirname(__FILE__) . '/tokenpresets.class.php');

if (!defined('LUMINOUS_DEBUG')) 
  define('LUMINOUS_DEBUG', false);

/**
 * @brief Base string scanning class
 * 
 * The Scanner class is the base class which handles traversing a string while
 * searching for various different tokens.
 * It is loosely based on Ruby's StringScanner.
 *
 * The rough idea is we keep track of the position (a string pointer) and
 * use scan() to see what matches at the current position.
 * 
 * It also provides some automation methods, but it's fairly low-level as 
 * regards string scanning.
 * 
 * Scanner is abstract as far as Luminous is concerned. LuminousScanner extends 
 * Scanner significantly with some methods which are useful for recording 
 * highlighting related data.
 * 
 * @see LuminousScanner
 */
class Scanner {
  /// @brief Our local copy of the input string to be scanned.
  private $src;
  
  /// @brief Length of input string (cached for performance)
  private $src_len;
  
  /// @brief The current scan pointer (AKA the offset or index)
  private $index;
  
  /**
   * @brief Match history
   * 
   * History of matches. This is an array (queue), which should have at most 
   * two elements. Each element consists of an array: 
   * 
   *  0 => Scan pointer when the match was found,
   *  1 => Match index (probably the same as scan pointer, but not necessarily),
   *  2 => Match data (match groups, as map, as returned by PCRE)
   * 
   * @note Numerical indices are used for performance.
   */
  private $match_history = array(null, null);
  
  /// @brief LuminousStringSearch instance (caches preg_* results)
  private $ss;
  
  /// @brief Caller defined patterns used by next_match()
  private $patterns = array(); 

  /// constructor
  function __construct($src=null) {
    $this->string($src);
  }
    
  /**
   * @brief Gets the remaining string
   * 
   * @return The rest of the string, which has not yet been consumed
   */
  function rest() {
    $rest = substr($this->src, $this->index);
    if ($rest === false) $rest = '';
    return $rest;
  }
  
  /** 
   * @brief Getter and setter for the current position (string pointer).
   * 
   * @param $new_pos The new position (leave \c NULL to use as a getter), note 
   * that this will be clipped to a legal string index if you specify a 
   * negative number or an index greater than the string's length.
   * @return the current string pointer
   */
  function pos($new_pos=null) {
    if ($new_pos !== null) {
      $new_pos = max(min((int)$new_pos, $this->src_len), 0);
      $this->index = $new_pos;
    }
    return $this->index;
  }

  /**
   * @brief Moves the string pointer by a given offset
   * 
   * @param $offset the offset by which to move the pointer. This can be positve
   * or negative, but using a negative offset is currently generally unsafe.
   * You should use unscan() to revert the last operation.
   * @see pos
   * @see unscan
   */
  function pos_shift($offset) {
    $this->pos( $this->pos() + $offset );
  }
  /**
   * @brief Beginning of line?
   * 
   * @return @c TRUE if the scan pointer is at the beginning of a line (i.e. 
   * immediately following a newline character), or at the beginning of the 
   * string, else @c FALSE
   */
  function bol() {
    return $this->index === 0 || $this->src[$this->index-1] === "\n";
  }
  
  /**
   * @brief End of line?
   * 
   * @return @c TRUE if the scan pointer is at the end of a line (i.e. 
   * immediately preceding a newline character), or at the end of the 
   * string, else @c FALSE
   */
  function eol() {
    return ($this->eos() || $this->src[$this->index] === "\n");
  }
  
  /**
   * @brief End of string?
   * 
   * @return @c TRUE if the scan pointer at the end of the string, else 
   * @c FALSE.
   */
  function eos() {
    return $this->index >= $this->src_len;
  }
  
  /**
   * @brief Reset the scanner
   * 
   * Resets the scanner: sets the scan pointer to 0 and clears the match
   * history.
   */
  function reset() {
    $this->pos(0);
    $this->match_history = array(null, null);    
    $this->ss = new LuminousStringSearch($this->src);
  }
  
  /**
   * @brief Getter and setter for the source string
   * 
   * @param $s The new source string (leave as @c NULL to use this method as a
   * getter)
   * @return The current source string
   * 
   * @note This method triggers a reset()
   * @note Any strings passed into this method are converted to Unix line 
   * endings, i.e. @c \\n
   */
  function string($s=null) {
    if ($s !== null) {
      $s = str_replace("\r\n", "\n", $s);
      $s = str_replace("\r", "\n", $s);
      $this->src = $s;
      $this->src_len = strlen($s);
      $this->reset();
    }
    return $this->src;
  }
  
  /**
   * @brief Ends scanning of a string
   * 
   * Moves the scan pointer to the end of the string, terminating the 
   * current scan.
   */
  function terminate() {
    $this->reset();
    $this->pos($this->src_len);
  }
  
  /**
   * @brief Lookahead into the string a given number of bytes
   * 
   * @param $n The number of bytes.
   * @return The given number of bytes from the string from the current scan 
   * pointer onwards. The returned string will be at most n bytes long, it may
   * be shorter or the empty string if the scanner is in the termination 
   * position.
   * 
   * @note This method is identitical to get(), but it does not consume the 
   * string.
   * @note neither get nor peek logs its matches into the match history.
   */
  function peek($n=1) {
    if ($n === 0 || $this->eos()) return '';
    elseif ($n === 1) return $this->src[$this->index];    
    else return substr($this->src, $this->index, $n);
  }
  
  /**
   * @brief Consume a given number of bytes
   * 
   * @param $n The number of bytes.
   * @return The given number of bytes from the string from the current scan 
   * pointer onwards. The returned string will be at most n bytes long, it may
   * be shorter or the empty string if the scanner is in the termination 
   * position.
   * 
   * @note This method is identitical to peek(), but it does consume the 
   * string.
   * @note neither get nor peek logs its matches into the match history.
   */ 
  function get($n=1) {
    $p = $this->peek($n);
    $this->index += strlen($p);
    return $p;
  }
  
  /**
   * @brief Get the result of the most recent match operation.
   * 
   * @return The return value is either a string or \c NULL depending on 
   * whether or not the most recent scanning function matched anything. 
   * 
   * @throw Exception if no matches have been recorded.
   * 
   */
  function match() {
//     $index = false;
    if (isset($this->match_history[0])) {
      return $this->match_history[0][2][0];
    }
    throw new Exception('match history empty');
  }
  
  /**
   * @brief Get the match groups of the most recent match operation.
   * 
   * @return The return value is either an array/map or \c NULL depending on
   * whether or not the most recent scanning function was successful. The map
   * is the same as PCRE returns, i.e. group_name => match_string, where 
   * group_name may be a string or numerical index.
   * 
   * @throw Exception if no matches have been recorded.
   */  
  function match_groups() {
    if (isset($this->match_history[0])) 
      return $this->match_history[0][2];    
    throw new Exception('match history empty');  
  }  
  
  /**
   * 
   * @brief Get a group from the most recent match operation
   * 
   * @param $g the group's numerical index or name, in the case of named 
   * subpatterns.
   * @return A string represeting the group's contents.
   * 
   * @see match_groups()
   * 
   * @throw Exception if no matches have been recorded.
   * @throw Exception if matches have been recorded, but the group does not 
   * exist.
   */   
  function match_group($g=0) {
    if (isset($this->match_history[0])) {
      if (isset($this->match_history[0][2])) {
        if (isset($this->match_history[0][2][$g]))
          return $this->match_history[0][2][$g];
        throw new Exception("No such group '$g'");
      }
    }
    throw new Exception('match history empty');
  }
  
  /**
   * @brief Get the position (offset) of the most recent match
   * 
   * @return The position, as integer. This is a standard zero-indexed offset 
   * into the string. It is independent of the scan pointer.
   * 
   * @throw Exception if no matches have been recorded.
   */     
  function match_pos() {
    if (isset($this->match_history[0])) 
      return $this->match_history[0][1];
    
    throw new Exception('match history empty');
  }
  /**
   * @brief Helper function to log a match into the history
   * 
   * @internal
   */
  private function __log_match($index, $match_pos, $match_data) {
    if (isset($this->match_history[0])) {
      $this->match_history[1] = $this->match_history[0];
    }
    $m = & $this->match_history[0];
    $m[0] = $index;
    $m[1] = $match_pos;
    $m[2] = $match_data;
  }
  
  /**
   * 
   * @brief Revert the most recent scanning operation.
   * 
   * Unscans the most recent match. The match is removed from the history, and
   * the scan pointer is moved to where it was before the match. 
   * 
   * Calls to get(), and peek() are not logged and are therefore not 
   * unscannable.
   *
   * @warning Do not call unscan more than once before calling a scanning 
   * function. This is not currently defined.
   */
  function unscan() {
    if (isset($this->match_history[0])) {
      $this->index = $this->match_history[0][0];
      if (isset($this->match_history[1])) {
        $this->match_history[0] = $this->match_history[1];
        $this->match_history[1] = null;
      } else 
        $this->match_history[0] = null;
    }
    else
      throw new Exception('match history empty');
  }
  
  /**
   * @brief Helper function to consume a match
   * 
   * @param $pos (int) The match position
   * @param $consume_match (bool) Whether or not to consume the actual matched
   * text
   * @param $match_data The matching groups, as returned by PCRE.
   * @internal
   */
  private function __consume($pos, $consume_match, $match_data) {
    $this->index = $pos;
    if ($consume_match) $this->index += strlen($match_data[0]);
  }
  
  /**
   * @brief The real scanning function
   * 
   * @internal
   * @param $pattern The pattern to scan for
   * @param $instant Whether or not the only legal match is at the 
   * current scan pointer or whether one beyond the scan pointer is also
   * legal.
   * @param $consume Whether or not to consume string as a result of matching
   * @param $consume_match Whether or not to consume the actual matched string.
   * This only has effect if $consume is @c TRUE. If $instant is @c TRUE, 
   * $consume is true and $consume_match is @c FALSE, the intermediate 
   * substring is consumed and the scan pointer moved to the beginning of the 
   * match, and the substring is recorded as a single-group match.
   * @param $log whether or not to log the matches into the match_register
   * @return The matched string or null. This is subsequently
   * equivalent to match() or match_groups()[0] or match_group(0).
   */
  private function __check($pattern, $instant=true, $consume=true, 
    $consume_match=true, $log=true) {
      $matches = null;
      $index = $this->index;
      $pos = null;
      if (($pos = $this->ss->match($pattern, $this->index, $matches)) !== false) {
        if ($instant && $pos !== $index) {
          $matches = null;
        }
        // don't consume match and not instant: the match we are interested in
        // is actually the substring between the start and the match.
        // this is used by scan_to
        if (!$consume_match && !$instant) {
          $matches = array(substr($this->src, $this->index, $pos-$this->index));
        }
      }
      else $matches = null;

      if ($log) {
        $this->__log_match($index, $pos, $matches);
      }      
      if ($matches !== null && $consume) {
        $this->__consume($pos, $consume_match, $matches);
      }      
      return ($matches === null)? null : $matches[0]; 
  }
  
  /**
   * @brief Scans at the current pointer
   * 
   * Looks for the given pattern at the current index and consumes and logs it
   * if it is found.
   * @param $pattern the pattern to search for
   * @return @c null if not found, else the full match.
   */
  function scan($pattern) {
    return $this->__check($pattern);
  }

  /**
  * @brief Scans until the start of a pattern
  * 
  * Looks for the given pattern anywhere beyond the current index and 
  * advances the scan pointer to the start of the pattern. The match is logged.
  * 
  * The match itself is not consumed.
  * 
  * @param $pattern the pattern to search for
  * @return The substring between here and the given pattern, or @c null if it 
  * is not found.
  */
  function scan_until($pattern) {
    return $this->__check($pattern, false, true, false, true);
  }

  
  /**
   * @brief Non-consuming lookahead
   * 
   * Looks for the given pattern at the current index and logs it
   * if it is found, but does not consume it. This is a look-ahead.
   * @param $pattern the pattern to search for
   * @return @c null if not found, else the matched string.
   */  
  function check($pattern) {
    return $this->__check($pattern, true, false, false, true);
  }
  
  /**
   * @brief Find the index of the next occurrence of a pattern
   * 
   * @param $pattern the pattern to search for
   * @return The next index of the pattern, or -1 if it is not found
   */
  function index($pattern) {
    $ret = $this->ss->match($pattern, $this->index, $dontcare_ref);
    return ($ret !== false)? $ret : -1;
  }

  /**
   * @brief Find the index of the next occurrence of a named pattern
   * @param $patterns A map of $name=>$pattern
   * @return An array: ($name, $index, $matches). If there is no next match,
   * name will be null, index will be -1 and matches will be null.
   *
   * @note consider using this method to build a transition table
   * 
   */

  function get_next_named($patterns) {
    $next = -1;
    $matches = null;
    $name = null;
    $m;
    foreach($patterns as $name_=>$p) {
      $index = $this->ss->match($p, $this->index, $m);
      if ($index === false) continue;
      if ($next === -1 || $index < $next) {
        $next = $index;
        $matches = $m;
        assert($m !== null);
        $name = $name_;
        if ($index === $this->index) break;
      }
    }
    return array($name, $next, $matches);
  }

  /**
   * @brief Look for the next occurrence of a set of patterns
   * 
   * Finds the next match of the given patterns and returns it. The
   * string is not consumed or logged.
   * Convenience function.
   * @param $patterns an array of regular expressions
   * @return an array of (0=>index, 1=>match_groups). The index may be -1 if
   * no pattern is found.
   */
  function get_next($patterns) {
    $next = -1;
    $matches = null;
    foreach($patterns as $p) {
      $m;
      $index = $this->ss->match($p, $this->index, $m);
      if ($index === false) continue;
      if ($next === -1 || $index < $next) {
        $next = $index;
        $matches = $m;
        assert($m !== null);
      }
    }
    return array($next, $matches);
  }

  /**
   * @brief Look for the next occurrence of a set of substrings
   * 
   * Like get_next() but uses strpos instead of preg_*
   * @return An array: 0 => index 1 => substring. If the substring is not found,
   *    index is -1 and substring is null
   * @see get_next()
   */
  function get_next_strpos($patterns) {
    $next = -1;
    $match = null;
    foreach($patterns as $p) {
      $index = strpos($this->src, $p, $this->index);
      if ($index === false) continue;
      if ($next === -1 || $index < $next) {
        $next = $index;
        $match = $p;
      }
    }
    return array($next, $match);
  }


  /**
   * @brief Allows the caller to add a predefined named pattern
   * 
   * Adds a predefined pattern which is visible to next_match.
   * 
   * @param $name A name for the pattern. This does not have to be unique.
   * @param $pattern A regular expression pattern.
   */  
  function add_pattern($name, $pattern) {
    $this->patterns[] = array($name, $pattern . 'S', -1, null);
  }
  /**
   * @brief Allows the caller to remove a named pattern
   *
   * @param $name the name of the pattern to remove, this should be as it was
   * supplied to add_pattern().
   * @warning If there are multiple patterns with the same name, they will all
   * be removed.
   */
  function remove_pattern($name) {
    foreach($this->patterns as $k=>$p) {
      if ($p[0] === $name) unset($this->patterns[$k]);
    }
  }
  
  /**
   * @brief Automation function: returns the next occurrence of any known patterns.
   * 
   * Iterates over the predefined patterns array (add_pattern) and consumes/logs
   * the nearest match, skipping unrecognised segments of string.
   * @return An array:
   *    0 => pattern name  (as given to add_pattern)
   *    1 => match index (although the scan pointer will have progressed to the 
   *            end of the match if the pattern is consumed).
   * When no more matches are found, return value is @c NULL and nothing is
   * logged.
   *
   *  
   * @param $consume_and_log If this is @c FALSE, the pattern is not consumed 
   * or logged.
   *
   * @warning this method is not the same as get_next. This does not return
   * the match groups, instead it returns a name. The ordering of the return
   * array is also different, but the array does in fact hold different data.
   */
  function next_match($consume_and_log=true) {
    $target = $this->index;
    
    $nearest_index = -1;
    $nearest_key = -1;
    $nearest_name = null;
    $nearest_match_data = null;

    foreach($this->patterns as &$p_data) {
      $name = $p_data[0];
      $pattern = $p_data[1];
      $index = &$p_data[2];
      $match_data = &$p_data[3];

      if ($index !== false && $index < $target) {
        $index = $this->ss->match($pattern, $target, $match_data);
      }

      if ($index === false) {
        unset($p_data);
        continue;
      }

      if ($nearest_index === -1 || $index < $nearest_index) {
        $nearest_index = $index;
        $nearest_name = $name;
        $nearest_match_data = $match_data;
        if ($index === $target) break;
      }
    }
    
    if ($nearest_index !== -1) {
      if ($consume_and_log) {
        $this->__log_match($nearest_index, $nearest_index, $nearest_match_data);
        $this->__consume($nearest_index, true, $nearest_match_data);
      }
      return array($nearest_name, $nearest_index);
    }
    else return null;
  }
}






/**
 * @brief the base class for all scanners
 * 
 * LuminousScanner is the base class for all language scanners. Here we 
 * provide a set of methods comprising a highlighting layer. This includes
 * recording a token stream, and ultimately being responsible for 
 * producing some XML representing the token stream.
 * 
 * We also define here some filters which rely on state information expected
 * to be recorded into the instance variables.
 * 
 * Highlighting a string at this level is a four-stage process:
 * 
 *      @li string() - set the string
 *      @li init() - set up the scanner
 *      @li main() - perform tokenization
 *      @li tagged() - build the XML
 * 
 * 
 * 
 * A note on tokens: Tokens are stored as an array with the following indices:
 *    @li 0:   Token name   (e.g. 'COMMENT'
 *    @li 1:   Token string (e.g. '// foo')
 *    @li 2:   escaped? (bool) Because it's often more convenient to embed nested
 *              tokens by tagging token string, we need to escape it. This 
 *              index stores whether or nto it has been escaped.
 * 
 */

class LuminousScanner extends Scanner {

  /// scanner version. 
  public $version = 'master';



  /**
   * @brief The token stream
   * 
   * The token stream is recorded as a flat array of tokens. A token is 
   * made up of 3 parts, and stored as an array:
   *  @li 0 => Token name 
   *  @li 1 => Token string (from input source code)
   *  @li 2 => XML-Escaped?
   */
  protected $tokens = array();

  /**
   * @brief State stack
   *
   * A stack of the scanner's state, should the scanner wish to use a stack
   * based state mechanism.
   * 
   * The top element can be retrieved (but not popped) with stack()
   * 
   * TODO More useful functions for manipulating the stack
   */  
  protected $state_ = array();

  /**
   * @brief Individual token filters
   * 
   * A list of lists, each filter is an array: (name, token_name, callback)
   */
  protected $filters = array();
  
  /**
   * @brief Token stream filters
   * 
   * A list of lists, each filter is an array: (name, callback)
   */
  protected $stream_filters = array();

  /**
   * @brief Rule remappings
   * 
   * A map to handle re-mapping of rules, in the form:
   * OLD_TOKEN_NAME => NEW_TOKEN_NAME
   * 
   * This is used by rule_mapper_filter()
   */
  protected $rule_tag_map = array();

  /**
   * @brief Identifier remappings based on definitions identified in the source code
   * 
   * A map of remappings of user-defined types/functions. This is a map of
   * identifier_string => TOKEN_NAME
   * 
   * This is used by user_def_filter()
   */
  protected $user_defs;
  
  
  /**
   * @brief A map of identifiers and their corresponding token names
   * 
   * A map of recognised identifiers, in the form 
   * identifier_string => TOKEN_NAME
   * 
   * This is currently used by map_identifier_filter
   */
  protected $ident_map = array();  

  /**
   * @brief Whether or not the language is case sensitive
   * 
   * Whether or not the scanner is dealing with a case sensitive language.
   * This currently affects map_identifier_filter
   */
  protected $case_sensitive = true;
  
  /// constructor
  function __construct($src=null) {
    parent::__construct($src);

    $this->add_filter('map-ident', 'IDENT', array($this,
      'map_identifier_filter'));
    
    $this->add_filter('comment-note', 'COMMENT', array('LuminousFilters', 'comment_note'));    
    $this->add_filter('comment-to-doc', 'COMMENT', array('LuminousFilters', 'generic_doc_comment'));
    $this->add_filter('string-escape', 'STRING', array('LuminousFilters', 'string'));
    $this->add_filter('char-escape', 'CHARACTER', array('LuminousFilters', 'string'));
    $this->add_filter('pcre', 'REGEX', array('LuminousFilters', 'pcre'));
    $this->add_filter('user-defs', 'IDENT', array($this, 'user_def_filter'));

    $this->add_filter('constant', 'IDENT', array('LuminousFilters', 'upper_to_constant'));

    $this->add_filter('clean-ident', 'IDENT', array('LuminousFilters', 'clean_ident'));
    
    $this->add_stream_filter('rule-map', array($this, 'rule_mapper_filter'));
    $this->add_stream_filter('oo-syntax', array('LuminousFilters', 'oo_stream_filter'));
  }


  /**
   * @brief Language guessing
   *
   * Each real language scanner should override this method and implement a
   * simple guessing function to estimate how likely the input source code
   * is to be the language which they recognise.
   * 
   * @param $src the input source code
   * @return The estimated chance that the source code is in the same language
   *  as the one the scanner tokenizes, as a real number between 0 (least 
   *  likely) and 1 (most likely), inclusive
   */
  public static function guess_language($src, $info) {
    return 0.0;
  }

  /**
   * @brief Filter to highlight identifiers whose definitions are in the source
   * 
   * maps anything recorded in LuminousScanner::user_defs to the recorded type.
   * This is called as the filter 'user-defs'
   * @internal
   */
  protected function user_def_filter($token) {
    if (isset($this->user_defs[$token[1]])) {
      $token[0] = $this->user_defs[$token[1]];
    }
    return $token;
  }

  /**
   * @brief Rule re-mapper filter
   * 
   * Re-maps token rules according to the LuminousScanner::rule_tag_map
   * map.
   * This is called as the filter 'rule-map'
   * @internal
   */
  protected function rule_mapper_filter($tokens) {
    foreach($tokens as &$t) {
      if (array_key_exists($t[0], $this->rule_tag_map))
        $t[0] = $this->rule_tag_map[$t[0]];
    }
    return $tokens;
  }




  /**
   * @brief Public convenience function for setting the string and highlighting it
   * 
   * Alias for:
   *   $s->string($src)
   *   $s->init();
   *   $s->main();
   *   return $s->tagged();
   * 
   * @returns the highlighted string, as an XML string
   */
  function highlight($src) {
    $this->string($src);
    $this->init();
    $this->main();
    return $this->tagged();
  }

  /**
   * @brief Set up the scanner immediately prior to tokenization.
   * 
   * The init method is always called prior to main(). At this stage, all
   * configuration variables are assumed to have been set, and it's now time
   * for the scanner to perform any last set-up information. This may include
   * actually finalizing its rule patterns. Some scanners may not need to
   * override this if they are in no way dynamic.
   */
  function init() {}
  
  
  /**
   * @brief the method responsible for tokenization
   * 
   * The main method is fully responsible for tokenizing the string stored
   * in string() at the time of its call. By the time main returns, it should
   * have consumed the whole of the string and populated the token array.
   * 
   */  
  function main() {}
  
  /**
   * @brief Add an individual token filter
   * 
   * Adds an indivdual token filter. The filter is bound to the given 
   * token_name. The filter is a callback which should take a token and return 
   * a token.
   * 
   * The arguments are: [name], token_name, filter
   * 
   * Name is an optional argument.
   */
  public function add_filter($arg1, $arg2, $arg3=null) {
    $filter = null;
    $name = null;
    $token = null;
    if ($arg3 === null) {
      $filter = $arg2; 
      $token = $arg1;
    } else {
      $filter = $arg3;
      $token = $arg2;
      $name = $arg1;
    }
    if (!isset($this->filters[$token])) $this->filters[$token] = array();
    $this->filters[$token][] = array($name, $filter);
  }

  /**
   * @brief Removes the individual filter(s) with the given name
   */
  public function remove_filter($name) {
    foreach($this->filters as $token=>$filters) {
      foreach($filters as $k=>$f) {
        if ($f[0] === $name) unset($this->filters[$token][$k]);
      }
    }
  }

  /**
   * @brief Removes the stream filter(s) with the given name
   */
  public function remove_stream_filter($name) {
    foreach($this->stream_filters as $k=>$f) {
      if ($f[0] === $name) unset($this->stream_filters[$k]);
    }
  }

  /**
   * @brief Adds a stream filter
   * 
   * A stream filter receives the entire token stream and should return it.
   *
   * The parameters are: ([name], filter). Name is an optional argument.
   * 
   */
  public function add_stream_filter($arg1, $arg2=null) {
    $filter = null;
    $name = null;
    if ($arg2 === null) {
      $filter = $arg1; 
    } else {
      $filter = $arg2;
      $name = $arg1;
    }
    $this->stream_filters[] = array($name, $filter);
  }

  /**
   * @brief Gets the top element on $state_ or null if it is empty
   */
  function state() {
    if (!isset($this->state_[0])) return null;
    return $this->state_[count($this->state_)-1];
  }
  /**
   * @brief Pushes some data onto the stack
   */
  function push($state) {
    $this->state_[] = $state;
  }
  /**
   * @brief Pops the top element of the stack, and returns it
   * @throw Exception if the state stack is empty
   */
  function pop() {
    if (empty($this->state_))
      throw new Exception('Cannot pop empty state stack');
    return array_pop($this->state_);
  }
  
  /**
   * @brief Flushes the token stream
   */
  function start() {
    $this->tokens = array();
  }
  
  /**
   * @brief Records a string as a given token type.
   * @param $string The string to record
   * @param $type The name of the token the string represents
   * @param $pre_escaped Luminous works towards getting this in XML and 
   * therefore at some point, the $string has to be escaped. If you have 
   * already escaped it for some reason (or if you got it from another scanner),
   * then you want to set this to @c TRUE
   * @see LuminousUtils::escape_string
   * @throw Exception if $string is @c NULL
   */
  function record($string, $type, $pre_escaped=false) {
    if ($string === null) throw new Exception('Tagging null string');
    $this->tokens[] = array($type, $string, $pre_escaped);
  }

  /**
   * @brief Helper function to record a range of the string
   * @param $from the start index
   * @param $to the end index
   * @param $type the type of the token
   * This is shorthand for
   * <code> $this->record(substr($this->string(), $from, $to-$from)</code>
   *
   * @throw RangeException if the range is invalid (i.e. $to < $from)
   *
   * An empty range (i.e. $to === $from) is allowed, but it is essentially a
   * no-op.
   */
  function record_range($from, $to, $type) {
    if ($to === $from)
      return;
    else if ($to > $from)
      $this->record(substr($this->string(), $from, $to-$from), $type);
    else
      throw new RangeException("Invalid range supplied [$from, $to]");
  }
  
  /**
   * @brief Returns the XML representation of the token stream
   * 
   * This function triggers the generation of the XML output. 
   * @return An XML-string which represents the tokens recorded by the scanner.
   */
  function tagged() {
    $out = '';

    // call stream filters.
    foreach($this->stream_filters as $f) {
      $this->tokens = call_user_func($f[1], $this->tokens);
    }
    foreach($this->tokens as $t) {
      $type = $t[0];
      
      // speed is roughly 10% faster if we process the filters inside this
      // loop instead of separately.
      if (isset($this->filters[$type])) {
        foreach($this->filters[$type] as $filter) {
          $t = call_user_func($filter[1], $t);
        }
      }
      list($type, $string, $esc) = $t;

      if (!$esc) $string = LuminousUtils::escape_string($string);
      if ($type !== null) 
        $out .= LuminousUtils::tag_block($type, $string);
      else $out .= $string;
    }
    return $out;
  }

  /**
   * @brief Gets the token array
   * @return The token array
   */
  function token_array() {
    return $this->tokens;
  }

  /**
   * @brief Identifier mapping filter
   * 
   * Tries to map any 'IDENT' token to a TOKEN_NAME in
   * LuminousScanner::$ident_map
   * This is implemented as the filter 'map-ident'
   */
  function map_identifier_filter($token) {
    $ident = $token[1];
    if (!$this->case_sensitive) $ident = strtolower($ident);
    foreach($this->ident_map as $n=>$hits) {
      if (isset($hits[$ident])) {
        $token[0] = $n;
        break;
      }
    }
    return $token;
  }

  /**
   * @brief Adds an identifier mapping which is later analysed by map_identifier_filter
   * @param $name The token name
   * @param $matches an array of identifiers which correspond to this token
   * name, i.e. add_identifier_mapping('KEYWORD', array('if', 'else', ...));
   *
   * This method observes LuminousScanner::$case_sensitive
   */
  function add_identifier_mapping($name, $matches) {
    $array = array();
    foreach($matches as $m) {
      if (!$this->case_sensitive) $m = strtolower($m);
      $array[$m] = true;
    }
    if (!isset($this->ident_map[$name]))
      $this->ident_map[$name] = array();
    $this->ident_map[$name] = array_merge($this->ident_map[$name], $array);
  }

  /**
   * Convenience function
   * @brief Skips whitespace, and records it as a null token.
   */
  function skip_whitespace() {
    if (ctype_space($this->peek())) {
      $this->record($this->scan('/\s+/'), null);
    }    
  }

  /**
   * @brief Handles tokens that may nest inside themselves
   *
   * Convenience function. It's fairly common for many languages to allow
   * things like nestable comments. Handling these is easy but fairly 
   * long winded, so this function will take an opening and closing delimiter
   * and consume the token until it is fully closed, or until the end of
   * the string in the case that it is unterminated.
   *
   * When the function returns, the token will have been consumed and appended
   * to the token stream.
   *
   * @param $token_name the name of the token
   * @param $open the opening delimiter pattern (regex), e.g. '% /\\* %x'
   * @param $close the closing delimiter pattern (regex), e.g. '% \\* /%x'
   *
   * @warning Although PCRE provides recursive regular expressions, this
   * function is far preferable. A recursive regex will easily crash PCRE
   * on garbage input due to it having a fairly small stack: this function
   * is much more resilient.
   *
   * @throws Exception if called at a non-matching point (i.e. 
   * <code>$this->scan($open)</code> does not match)
   *
   */
  function nestable_token($token_name, $open, $close) {
    if ($this->check($open) === null) {
      throw new Exception('Nestable called at a non-matching point');
      return;
    }
    $patterns = array('open'=>$open, 'close'=>$close);

    $stack = 0;
    $start = $this->pos();
    do {
      list($name, $index, $matches) = $this->get_next_named($patterns);
      if ($name === 'open') $stack++;
      elseif($name === 'close') $stack--;
      else {
        $this->terminate();
        break;
      }
      $this->pos($index + strlen($matches[0]));
    } while ($stack);
    $substr = substr($this->string(), $start, $this->pos()-$start);
    $this->record($substr, $token_name);
  }
}





/**
 * @brief Superclass for languages which may nest, i.e. web languages
 * 
 * Web languages get their own special class because they have to deal with
 * server-script code embedded inside them and the potential for languages
 * nested under them (PHP has HTML, HTML has CSS and JavaScript)
 * 
 * The relationship is strictly hierarchical, not recursive descent
 * Meeting a '\<?' in CSS bubbles up to HTML and then up to PHP (or whatever).
 * The top-level scanner is ultimately what should have sub-scanner code 
 * embedded in its own token stream.
 * 
 * The scanners should be persistent, so only one JavaScript scanner exists
 * even if there are 20 javascript tags. This is so they can keep persistent 
 * state, which might be necessary if they are interrupted by server-side tags. 
 * For this reason, the main() method might be called multiple times, therefore
 * each web sub-scanner should 
 *     \li Not rely on keeping state related data in main()'s function scope,
 *              make it a class variable 
 *      \li flush its token stream every time main() is called
 * 
 * The init method of the class should be used to set relevant rules based
 * on whether or not the embedded flags are set; and therefore the embedded
 * flags should be set before init is called.
 */
abstract class LuminousEmbeddedWebScript extends LuminousScanner {
  
  /**
   * @brief Is the source embedded in HTML?
   * 
   * Embedded in HTML? i.e. do we need to observe tag terminators like \</script\>
   */
  public $embedded_html = false;
  
  /**
   * @brief Is the source embedded in a server-side script (e.g. PHP)?
   * 
   * Embedded in a server side language? i.e. do we need to break at
   * (for example) \<? tags?
   */
  public $embedded_server = false;
  
  /**
   * @brief Opening tag for server-side code. This is a regular expression.
   */
  public $server_tags = '/<\?/';
  
  /// @brief closing HTML tag for our code, e.g \</script\>
  public $script_tags;
  
  
  /** @brief I think this is ignored and obsolete */
  public $interrupt = false;
  
  /** 
   * @brief Clean exit or inconvenient, mid-token forced exit
   * 
   * Signifies whether the program exited due to inconvenient interruption by 
   * a parent language (i.e. a server-side langauge), or whether it reached 
   * a legitimate break. A server-side language isn't necessarily a dirty exit,
   * but if it comes in the middle of a token it is, because we need to resume
   * from it later. e.g.:
   *
   * var x = "this is \<?php echo 'a' ?\> string";
   */
  public $clean_exit = true;
  
  
  
  /**
   * @brief Child scanners
   * 
   * Persistent storage of child scanners, name => scanner (instance)
   */
  protected $child_scanners = array();

  /**
   * @brief Name of interrupted token, in case of a dirty exit
   * 
   * exit state logs our exit state in the case of a dirty exit: this is the
   * rule that was interrupted.
   */
  protected $exit_state = null;
  
  
  /** 
   * @brief Recovery patterns for when we reach an untimely interrupt
   * 
   * If we reach a dirty exit, when we resume we need to figure out how to 
   * continue consuming the rule that was interrupted. So essentially, this 
   * will be a regex which matches the rule without start delimiters.
   *  
   * This is a map of rule => pattern
   */
  protected $dirty_exit_recovery = array();

  /**
   * @brief adds a child scanner
   * Adds a child scanner and indexes it against a name, convenience function
   */
  public function add_child_scanner($name, $scanner) {
    $this->child_scanners[$name] = $scanner;
  }
  
  // override string to hit the child scanners as well
  public function string($str=null) {
    if ($str !== null) {
      foreach($this->child_scanners as $s) {
        $s->string($str);
      }
    }
    return parent::string($str);
  }
  
  /**
   * @brief Sets the exit data to signify the exit is dirty and will need recovering from
   * 
   * @param $token_name the name of the token which is being interrupted
   * 
   * @throw Exception if no recovery data is associated with the given token.
   */
  function dirty_exit($token_name) {    
    if (!isset($this->dirty_exit_recovery[$token_name])) {
      throw new Exception('No dirty exit recovery data for '. $token_name);
      $this->clean_exit = true;
      return;
    }
    $this->exit_state = $token_name;
    $this->interrupt = true;
    $this->clean_exit = false;
  }
  
  /**
   * @brief Attempts to recover from a dirty exit.
   * 
   * This method should be called on @b every iteration of the main loop when
   * LuminousEmbeddedWebScript::$clean_exit is @b FALSE. It will attempt to 
   * recover from an interruption which left the scanner in the middle of a 
   * token. The remainder of the token will be in Scanner::match() as usual.
   * 
   * @return the name of the token which was interrupted
   * 
   * @note there is no reason why a scanner should fail to recover from this,
   * and failing is classed as an implementation error, therefore assertions 
   * will be failed and errors will be spewed forth. A failure can either be 
   * because no recovery regex is set, or that the recovery regex did not 
   * match. The former should never have been tagged as a dirty exit and the
   * latter should be rewritten so it must definitely match, even if the match
   * is zero-length or the remainder of the string.
   * 
   */
  function resume() {
    assert (!$this->clean_exit);
    $this->clean_exit = true;
    $this->interrupt = false;
    if (!isset($this->dirty_exit_recovery[$this->exit_state])) {
      throw new Exception("Not implemented error: The scanner was interrupted
mid-state (in state {$this->exit_state}), but there is no recovery associated
with this state");
      return null;
    }
    $pattern = $this->dirty_exit_recovery[$this->exit_state];
    $m = $this->scan($pattern);
    if ($m === null) throw new Exception('Implementation error: recovery
pattern for ' . $this->exit_state . ' failed to match');
    return $this->exit_state;
  }
  
  
  /**
   * @brief Checks for a server-side script inside a matched token
   * 
   * @param $token_name The token name of the matched text
   * @param $match The string from the last match. If this is left @c NULL then
   * Scanner::match() is assumed to hold the match.
   * @param $pos The position of the last match. If this is left @c NULL then
   * Scanner::match_pos() is assumed to hold the offset.
   * @return @c TRUE if the scanner should break, else @c FALSE
   * 
   * 
   * This method checks whether an interruption by a server-side script tag,
   * LuminousEmbeddedWebScript::server_tags, occurs within a matched token. 
   * If it does, this method records the substring up until that point as 
   * the provided $token_name, and also sets up a 'dirty exit'. This means that 
   * some type was interrupted and we expect to have to recover from it when
   * the server-side language's scanner has ended.
   * 
   * Returning @c TRUE is a signal that the scanner should break immediately 
   * and let its parent scanner take over.
   * 
   */  
  function server_break($token_name, $match=null, $pos=null) {
    if (!$this->embedded_server) {
      return false;
    }
    if ($match === null) $match = $this->match();
    if ($match === null) return false;
    
    if (preg_match($this->server_tags, $match, $m_, PREG_OFFSET_CAPTURE)) {
      $pos_ = $m_[0][1];
      $this->record(substr($match, 0, $pos_), $token_name);
      if ($pos === null) $pos = $this->match_pos();
      $this->pos($pos + $pos_);
      $this->dirty_exit($token_name);
      return true;
    }
    else return false;
  }
  
  /**
   * @brief Checks for a script terminator tag inside a matched token
   * 
   * @param $token_name The token name of the matched text
   * @param $match The string from the last match. If this is left @c NULL then
   * Scanner::match() is assumed to hold the match.
   * @param $pos The position of the last match. If this is left @c NULL then
   * Scanner::match_pos() is assumed to hold the offset.
   * @return @c TRUE if the scanner should break, else @c FALSE
   * 
   * This method checks whether the string provided as match contains the 
   * string in LuminousEmbeddedWebScript::script_tags. If yes, then it records
   * the substring as $token_name, advances the scan pointer to immediately 
   * before the script tags, and returns @c TRUE. Returning @c TRUE is a 
   * signal that the scanner should break immediately and let its parent 
   * scanner take over.
   * 
   * This condition is a 'clean_exit'.
   */
  function script_break($token_name, $match=null, $pos=null) {
    if (!$this->embedded_html) return false;
    if ($match === null) $match = $this->match();
    if ($match === null) return false;
    
    if (($pos_ = stripos($this->match(), $this->script_tags)) !== false) {
      $this->record(substr($match, 0, $pos_), $token_name);
      if ($pos === null) $pos = $this->match_pos();
      $this->pos($pos + $pos_);
      $this->clean_exit = true;      
      return true;
    }
    else return false;
  }
}





/**
 * @brief A largely automated scanner
 *
 * LuminousSimpleScanner implements a main() method and observes the
 * patterns added with Scanner::add_pattern()
 *
 * An overrides array allows the caller to override the handling of any token.
 * If an override is set for a token, the override is called when that token is
 * reached and the caller should consume it. If the callback fails to advance
 * the string pointer, an Exception is thrown.
 */
class LuminousSimpleScanner extends LuminousScanner {

  /**
   * @brief Overrides array.
   *
   * A map of TOKEN_NAME => callback.
   *
   * The callbacks are fired by main() when the TOKEN_NAME rule matches.
   * The callback receives the match_groups array, but the scanner is
   * unscan()ed before the callback is fired, so that the pos() is directly
   * in front of the match. The callback is responsible for consuming the
   * token appropriately.
   */
  protected $overrides = array();


  function main() {
    while (!$this->eos()) {
      $index = $this->pos();
      if (($match = $this->next_match()) !== null) {
        $tok = $match[0];
        if ($match[1] > $index) {
          $this->record(substr($this->string(), $index, $match[1] - $index), null);
        }
        $match = $this->match();
        if (isset($this->overrides[$tok])) {
          $groups = $this->match_groups();
          $this->unscan();
          $p = $this->pos();
          $ret = call_user_func($this->overrides[$tok], $groups);
          if ($ret === true) break;
          if ($this->pos() <= $p)
            throw new Exception('Failed to consume any string in override for ' . $tok);
        } else
          $this->record($match, $tok);
      } else {
        $this->record(substr($this->string(), $index), null);
        $this->terminate();
        break;
      }
    }
  }
}


/**
 *
 * @brief Experimental transition table driven scanner
 *
 * The stateful scanner follows a transition table and generates a hierarchical
 * token tree. As such, the states follow a hierarchical parent->child
 * relationship rather than a strict from->to
 * 
 * A node in the token tree looks like this:
 *
 * @code array('token_name' => 'name','children' => array(...)) @endcode
 *
 * Children is an ordered list and its elements may be either other token
 * nodes or just strings. We override tagged to try to collapse this into XML
 * while still applying filters.
 *
 *
 * We now store patterns as the following tuple:
 * @code ($name, $open_pattern, $teminate_pattern). @endcode
 * The termination pattern may be null, in which case the $open_pattern
 * is complete. No transitions can occur within a complete state because
 * the patterns' match is fixed.
 *
 * We have two stacks. One is LuminousStatefulScanner::$token_tree_stack,
 * which stores the token tree, and the other is a standard state stack which
 * stores the current state data. State data is currently a pattern, as the
 * above tuple.
 *
 *
 * @warning Currently 'stream filters' are not applied, because we at no point
 * end up with a flat stream of tokens. Although the rule name remapper is
 * applied.
 * 
 */
class LuminousStatefulScanner extends LuminousSimpleScanner {


  /// @brief Transition table
  protected $transitions = array();
  
  /**
   * @brief Legal transitions for the current state
   * 
   * @see LuminousStatefulScanner::load_transitions()
   */
  protected $legal_transitions = array();

  /**
   * @brief Pattern list
   * 
   * Pattern array. Each pattern is a tuple of
   * @code ($name, $open_pattern, $teminate_pattern) @endcode
   */
  protected $patterns = array();

  /**
   * @brief The token tree
   * 
   * The tokens we end up with are a tree which we build as we go along. The
   * easiest way to build it is to keep track of the currently active node on
   * top of a stack. When the node is completed, we pop it and insert it as
   * a child of the element which is now at the top of the stack.
   *
   * At the end of the process we end up with one element in here which is
   * the root node.
   */
  protected $token_tree_stack = array();

  /// Records whether or not the FSM has been set up for the first time.
  /// @see setup()
  private $setup = false;
  /// remembers the state on the last iteration so we know whether or not
  /// to load in a new transition-set
  private $last_state = null;

  /// Cache of transition rules
  /// @see next_start_data()
  private $transition_rule_cache = array();


  /**
   * Pushes a new token onto the stack as a child of the currently active
   * token
   *
   * @see push_state
   * @internal
   */
  function push_child($child) {
    assert(!empty($this->token_tree_stack));
    $this->token_tree_stack[] = $child;
  }

  /**
   * @brief Pushes a state
   * 
   * @param $state_data A tuple of ($name, $open_pattern, $teminate_pattern).
   * This should be as it is stored in LuminousStatefulScanner::patterns
   *
   * This actually causes two push operations. One is onto the token_tree_stack,
   * and the other is onto the actual stack. The former creates a new token,
   * the latter is used for state information
   */
  function push_state($state_data) {
    $token_node = array('token_name' => $state_data[0], 'children'=>array());
    $this->push_child($token_node);
    $this->push($state_data);
  }


  /**
   * @brief Pops a state from the stack.
   *
   * The top token on the token_tree_stack is popped and appended as a child to
   * the new top token.
   *
   * The top state on the state stack is popped and discarded.
   * @throw Exception if there is only the initial state on the stack
   * (we cannot pop the initial state, because then we have no state at all)
   */
  function pop_state() {
    $c = count($this->token_tree_stack);
    if ($c <= 1) {
      throw new Exception('Attempted to pop the initial state');
    }
    $s = array_pop($this->token_tree_stack);
    // -2 because we popped once since counting
    $this->token_tree_stack[$c-2]['children'][] = $s;
    $this->pop();
  }

  /**
   * @brief Adds a state transition
   * 
   * This is a helper function for LuminousStatefulScanner::transitions, you
   * can specify it directly instead
   * @param $from The parent state
   * @param $to The child state
   */
  function add_transition($from, $to) {
    if (!isset($this->transitions[$from])) $this->transitions[$from] = array();
    $this->transitions[$from][] = $to;
  }

  /**
   * @brief Gets the name of the current state
   * 
   * @returns The name of the current state
   */
  function state_name() {
    $state_data = $this->state();
    if ($state_data === null) return 'initial';
    $state_name = $state_data[0];
    return $state_name;
  }

  /**
   * @brief Adds a pattern
   * 
   * @param $name the name of the pattern/state
   * @param $pattern Either the entire pattern, or just its opening delimiter
   * @param $end If $pattern was just the opening delimiter, $end is the closing
   * delimiter. Separating the two delimiters like this makes the state flexible
   * length, as state transitions can occur inside it.
   * @param $consume Not currently observed. Might never be. Don't specify this yet.
   */
  function add_pattern($name, $pattern, $end=null, $consume=true) {
    $this->patterns[] = array($name, $pattern, $end, $consume);
  }

  
  /**
   * @brief Loads legal state transitions for the current state
   * 
   * Loads in legal state transitions into the legal_transitions array
   * according to the current state
   */
  function load_transitions() {
    $state_name = $this->state_name();
    if ($this->last_state === $state_name) return;
    $this->last_state = $state_name;
    if (isset($this->transitions[$state_name]))
      $this->legal_transitions = $this->transitions[$this->state_name()];
    else $this->legal_transitions = array();
  }

  /**
   * @brief Looks for the next state-pop sequence (close/end) for the current state
   * 
   * @returns Data in the same format as get_next: a tuple of (next, matches).
   * If no match is found, next is -1 and matches is null
   */
  function next_end_data() {
    $state_data = $this->state();
    if ($state_data === null) {
      return array(-1, null); // init/root state
    }
    $term_pattern = $state_data[2];
    assert($term_pattern !== null);
    $data = $this->get_next(array($term_pattern));
    return $data;
  }

  /**
   * @brief Looks for the next legal state transition
   * 
   * @returns A tuple of (pattern_data, next, matches).
   * If no match is found, next is -1 and pattern_data and matches is null
   */
  function next_start_data() {
    $patterns = array();
    $states = array();
    $sn = $this->state_name();
    // at the moment we are using get_next_named, so we have to convert
    // our patterns into key=>pattern so it can return to us a key. We use
    // numerical indices which also correspond with 'states' for full pattern
    // data. We are caching this.
    // TODO turns out get_next_named is pretty slow and we'd be better off
    // caching some results inside the pattern data
    if (isset($this->transition_rule_cache[$sn]))
      list($patterns,$states) = $this->transition_rule_cache[$sn];
    else {
      foreach($this->legal_transitions as $t) {
        foreach($this->patterns as $p) {
          if ($p[0] === $t) {
            $patterns[] = $p[1];
            $states[] = $p;
          }
        }
      }
      $this->transition_rule_cache[$sn] = array($patterns, $states);
    }
    $next = $this->get_next_named($patterns);
    // map to real state data
    if ($next[1] !== -1) {
      $next[0] = $states[$next[0]];
    }
    return $next;
  }

  /**
   * @brief Sets up the FSM
   *
   * If the caller has omitted to specify an initial state then one is created,
   * with valid transitions to all other known states. We also push the
   * initial state onto the tree stack, and add a type mapping from the initial
   * type to @c NULL.
   */
  protected function setup() {
    if ($this->setup) return;
    $this->setup = true;
    if (!isset($this->transitions['initial'])) {
      $initial = array();
      foreach($this->patterns as $p) $initial[] = $p[0];
      $this->transitions['initial'] = $initial;
    }
    $this->token_tree_stack[] = array('token_name' => 'initial',
      'children'=>array());
    $this->rule_tag_map['initial'] = null;
  }

  /**
   * Records a string as a child of the currently active token
   * @warning the second and third parameters are not applicable to this
   * method, they are only present to suppress PHP warnings. If you set them,
   * an exception is thrown.
   *
   */
  function record($str, $dummy1=null, $dummy2=null) {
    if ($dummy1 !== null || $dummy2 !== null) {
      throw new Exception('LuminousStatefulScanner::record does not currently'
        . ' observe its second and third parameters');
    }
    // NOTE to self: if ever this needs to change, don't call count on $c.
    // Dereference it first: http://bugs.php.net/bug.php?id=34540
    $c = &$this->token_tree_stack[count($this->token_tree_stack)-1]['children'];
    $c[] = $str;
  }
  /**
   * @brief Records a complete token
   * This is shorthand for pushing a new node onto the stack, recording its
   * text, and then popping it
   * 
   * @param $str the string
   * @param $type the token type
   */
  function record_token($str, $type) {
    $state_data = array($type);
    $this->push_state($state_data);
    $this->record($str);
    $this->pop_state();
  }

  /**
   * @brief Helper function to record a range of the string
   * @param $from the start index
   * @param $to the end index
   * @param $type dummy argument
   * This is shorthand for
   * <code> $this->record(substr($this->string(), $from, $to-$from)</code>
   *
   * @throw RangeException if the range is invalid (i.e. $to < $from)
   *
   * An empty range (i.e. $to === $from) is allowed, but it is essentially a
   * no-op.
   */
  function record_range($from, $to, $type=null) {
    if ($type !== null) throw new Exception('type argument not supported in '
      . ' LuminousStatefulScanner::record_range');
    if ($to === $from)
      return;
    else if ($to > $from)
      $this->record(substr($this->string(), $from, $to-$from), $type);
    else
      throw new RangeException("Invalid range supplied [$from, $to]");
  }


  /**
   * Generic main function which observes the transition table
   */
  function main() {
    $this->setup();
    while (!$this->eos()) {
      $p = $this->pos();
      $state = $this->state_name();

      $this->load_transitions();
      list($next_pattern_data,
           $next_pattern_index,
           $next_pattern_matches) = $this->next_start_data();
      list($end_index, $end_matches) = $this->next_end_data();


      if( ($next_pattern_index <= $end_index || $end_index === -1) && $next_pattern_index !== -1) {
        // we're pushing a new state
        if ($p < $next_pattern_index)
          $this->record_range($p, $next_pattern_index);
        $new_pos = $next_pattern_index;
        $this->pos($new_pos);
        $tok = $next_pattern_data[0];
        if (isset($this->overrides[$tok])) {
          // call override
          $ret = call_user_func($this->overrides[$tok], $next_pattern_matches);
          if ($ret === true) break;
          if ($this->state_name() === $state && $this->pos() <= $new_pos) {
            throw new Exception('Override failed to either advance the pointer'
              . ' or change the state');
          }
        } else {
          // no override
          $this->pos_shift(strlen($next_pattern_matches[0]));
          $this->push_state($next_pattern_data);
          $this->record($next_pattern_matches[0]);
          if ($next_pattern_data[2] === null) {
            // state was a full pattern, so pop now
            $this->pop_state();
          }
        }
      } elseif($end_index !== -1) {
        // we're at the end of a state, record what's left and pop it
        $to = $end_index + strlen($end_matches[0]);
        $this->record_range($this->pos(), $to);
        $this->pos($to);
        $this->pop_state();
      }
      else {
        // no more matches, consume the rest of the stirng and break
        $this->record($this->rest());
        $this->terminate();
        break;
      }
      if ($this->state_name() === $state && $this->pos() <= $p) {
        throw new Exception('Failed to advance pointer in state' 
          . $this->state_name());
      }
    }

    // unterminated states will have left some tokens open, we need to 
    // close these so there's just the root node on the stack
    assert(count($this->token_tree_stack) >= 1);
    while(count($this->token_tree_stack) > 1)
      $this->pop_state();
 
    return $this->token_tree_stack[0];
  }

  /**
   * Recursive function to collapse the token tree into XML
   * @internal
   */
  protected function collapse_token_tree($node) {
    $text = '';
    foreach($node['children'] as $c) {
      if (is_string($c)) $text .= LuminousUtils::escape_string($c);
      else $text .= $this->collapse_token_tree($c);
    }
    $token_name = $node['token_name'];
    $token = array($node['token_name'], $text, true);

    $token_ = $this->rule_mapper_filter(array($token));
    $token = $token_[0];

    if (isset($this->filters[$token_name])) {
      foreach($this->filters[$token_name] as $filter) {
        $token = call_user_func($filter[1], $token);
      }
    }
    list($token_name, $text,) = $token;
    return ($token_name === null)?
      $text : LuminousUtils::tag_block($token_name, $text);
  }

  function tagged() {
    $stream = $this->collapse_token_tree($this->token_tree_stack[0]);
    return $stream;
  }
}

/// @endcond
