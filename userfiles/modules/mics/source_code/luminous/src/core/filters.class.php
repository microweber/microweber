<?php

/**
 * @cond CORE
 * @brief A collection of useful common filters.
 *
 * Filters are either stream filters or individual filters.
 * Stream filters operate on the entire token stream, and return the new
 * token stream. Individual filters operate on individual tokens (bound by type),
 * and return the new token. Any publicly available member here is one of those,
 * therefore the return and param documents are omitted.
 * 
 */
// Poor man's namespace.
class LuminousFilters {
  
  /**
   * @brief Gets the expected number of arguments to a doc-comment command/tag
   * @param $command the name of the command
   * @returns The expected number of arguments for a command, this is either 
   * 0 or 1 at the moment
   * @internal
   */
  private static function doxygen_arg_length($command) {
    switch(strtolower($command)) {
      case "addtogroup":
      case "category":
      case "class":
      case "cond":
      case "def":
      case "defgroup":
      case "dir":
      case "elseif":
      case "enum":
      case "exception":
      case "example":
      case "extends":
      case "if":
      case "ifnot":
      case "file":
      case "headerfile":
      case "implements":
      case "ingroup":
      case "interface":
      case "memberof":
      case "namespace":
      case "package":
      case "page":
      case "par":
      case "param":
      case "relates":
      case "relatesalso":
      case "retval":
      case 'see':
      case 'since':
      case "tparam":
      case "throw":
      case "throws":
      case "weakgroup":
      case "xrefitem":
        return 1;
      default: return 0;
    }
  }
  
  /**
   * @brief callback to doxygenize
   * Highlights Doxygen-esque doc-comment syntax. 
   * This is a callback to doxygenize().
   * @return the highlighted string
   * @internal   
   */
  private static function doxygenize_cb($matches) {
    $lead = $matches[1];
    $tag_char = $matches[2];
    $tag = $matches[3];
    
    $line = "";
    if (isset($matches[4]))
      $line = $matches[4];
    
    $len = -1;
    // JSDoc-like
    $l_ = ltrim($line);
    if (isset($l_[0]) && $l_[0] === '{') {
      $line = preg_replace('/({[^}]*})/', "<DOCPROPERTY>$1</DOCPROPERTY>", $line);
      return "$lead<DOCTAG>$tag_char$tag</DOCTAG>$line";
    }    
    else      
      $len = self::doxygen_arg_length($tag);
    
    if($len === 0)
      return "$lead<DOCTAG>$tag_char$tag</DOCTAG>$line";    
    else {
      $l = explode(' ', $line);
      $start = "$lead<DOCTAG>$tag_char$tag</DOCTAG><DOCPROPERTY>";
      
      $j = 0;
      $c = count($l);
      for($i=0; $j<$len && $i<$c; $i++)
      {      
        $s = $l[$i];
        $start .= $s . ' ';
        unset($l[$i]);
        if (trim($s) !== '')
          $j++;
      }
      $start = preg_replace('/ $/', '', $start);
      $start .= "</DOCPROPERTY>";
      $l = array_values($l);
      if (!empty($l)) $start .= ' ';
      $start .= implode(' ', $l);    
      return $start;
    }
  }
  
  /**
   * @brief Highlights doc-comment tags inside a comment block.
   * 
   * @see generic_doc_comment
   * @internal
   */
  static function doxygenize($token) {
    $token = LuminousUtils::escape_token($token);
    $token[1] = preg_replace_callback("/(^(?>[\/\*#\s]*))([\@\\\])([^\s]*)([ \t]+.*?)?$/m",
        array('LuminousFilters', 'doxygenize_cb'),   $token[1]);    
    return $token;
    
  }
  /**
   * @brief Generic filter to highlight JavaDoc, PHPDoc, Doxygen, JSdoc, and similar doc comment syntax.
   *
   * A cursory check will be performed to try to validate that the token
   * really is a doc-comment, it does this by checking for common formats.
   *
   * If the check is successful, the token will be switched to type
   * 'DOCCOMMENT' and its doc-tags will be highlighted
   * 
   * This is a wrapper around doxygenize(). If the checks are not necessary, 
   * or incorrect for your situation, you may instead choose to use 
   * doxygenize() directly.
   */
  static function generic_doc_comment($token) {
    // checks if a comment is in the form:
    // xyyz where x may = y but y != z.
    // This matches, say, /** comment  but does not match /********/
    //  same with /// comment but not ///////////////
    $s = $token[1];
    if (isset($s[3])
      && ($s[2] === $s[1] || $s[2] === '!')
      && !ctype_space($s[0])
      && !ctype_space($s[1])
      && $s[3] !== $s[2]   
      )
    {
      $token[0] = 'DOCCOMMENT';
      $token = self::doxygenize($token);
    }
    return $token;    
  }
  
  /**
   * @brief Highlights comment notes
   * Highlights keywords in comments, i.e. "NOTE", "XXX", "FIXME", "TODO",
   * "HACK", "BUG"
   */
  static function comment_note($token) {
      $token = LuminousUtils::escape_token($token);
      $token[1] = preg_replace('/\\b(?:NOTE|XXX|FIXME|TODO|HACK|BUG):?/',
        '<COMMENT_NOTE>$0</COMMENT_NOTE>', $token[1]);
      return $token;
  }
  
  /**
   * @brief Highlights generic escape sequences in strings
   * Highlights escape sequences in strings. There is no checking on which
   * sequences are legal, this is simply a generic function which checks for
   * \\u...  unicode, \\d... octal, \\x... hex and finally just any character
   * following a backslash.
   */
  static function string($token) {
    if (strpos($token[1], '\\') === false) return $token;
    
    $token = LuminousUtils::escape_token($token);    
    $token[1] = preg_replace('/
    \\\\
    (?:
      (?:u[a-f0-9]{4,8}) # unicode
      |\d{1,3}           # octal
      |x[a-fA-F0-9]{2}   # hex
      |.                 # whatever
    )
    /xi', '<ESC>$0</ESC>', $token[1]);
    return $token;
  }
  
  /**
   * @brief Tries to highlight PCRE style regular expression syntax
   */
  static function pcre($token, $delimited=true) {
    $token = self::string($token);
    $token = LuminousUtils::escape_token($token);
    $str = &$token[1];
    $flags = array();
    if ($delimited) {
      $str = preg_replace('/^[^[:alnum:]<>\s]/', '<DELIMITER>$0</DELIMITER>', $str);      
      if (preg_match("/[[:alpha:]]+$/", $str, $matches)){
        $m = $matches[0];
        $flags = str_split($m);
        $str = preg_replace("/((?<!\A)[^[:alnum:]\s<>])([[:alpha:]]+)$/",
          "<DELIMITER>$1</DELIMITER><KEYWORD>$2</KEYWORD>", $str);
      } else 
        $str = preg_replace('/[^[:alnum:]<>]$/', '<DELIMITER>$0</DELIMITER>', $str);

    }
    
    $str = preg_replace("/((?<!\\\)[\*\+\.|])|((?<![\(\\\])\?)/",
                          "<REGEX_OPERATOR>$0</REGEX_OPERATOR>", $str);  
    $str = preg_replace("/(?<=\()\?(?:(?:[a-zA-Z:!|=])|(?:(?:&lt;)[=!]))/", 
      "<REGEX_SUBPATTERN>$0</REGEX_SUBPATTERN>",  $str);
    $str = preg_replace("/(?<!\\\)[\(\)]/", 
      "<REGEX_SUBPATTERN_MARKER>$0</REGEX_SUBPATTERN_MARKER>", $str);
    $str = preg_replace("/(?<!\\\)[\[\]]/", 
      "<REGEX_CLASS_MARKER>$0</REGEX_CLASS_MARKER>",  $str);
    $str = preg_replace("/(?<!\\\)
      \{
        (
          ((?>\d+)(,(?>\d+)?)?)
          |
          (,(?>\d+))
        )
      \}/x", "<REGEX_REPEAT_MARKER>$0</REGEX_REPEAT_MARKER>",  $str);
      
    // extended regex: # signifies a comment
    if (in_array('x', $flags))
      $str = preg_replace('/(?<!\\\)#.*$/m', '<COMMENT>$0</COMMENT>',
        $str);
    return $token;
  }

  /**
   * @brief Translates any token type of an uppercase/numeric IDENT to 'CONSTANT'
   */
  static function upper_to_constant($token) {
    // check for this because it may have been mapped to a function or something
    if ($token[0] === 'IDENT' && preg_match('/^[A-Z_][A-Z_0-9]{3,}$/', $token[1]))
      $token[0] = 'CONSTANT';
    return $token;
  }

  /**
   * @brief Translates anything of type 'IDENT' to the null type
   */
  static function clean_ident($token) {
    if ($token[0] === 'IDENT') $token[0] = null;
    return $token;
  }



  /**
   * @brief Attempts to apply OO syntax highlighting
   * 
   * Tries to apply generic OO syntax highlighting. Any identifer immediately
   * preceding a '.', '::' or '->' token is mapped to an 'OO'.
   * Any identifer token immediatel following any of those tokens is mapped to
   * an 'OBJ'.
   * This is a stream filter.
   */
  static function oo_stream_filter($tokens) {
    $c = count($tokens);
    for($i=0; $i<$c; $i++) {
      if ($tokens[$i][0] !== 'IDENT') continue;
      if ($i > 0) {
        $s = $tokens[$i-1][1];
        if ($s === '.' || $s === '->' || $s === '::') {
          $tokens[$i][0] = 'OO';
          $i++;
          continue;
        }
      }
      if ($i < $c-1) {
        $s = $tokens[$i+1][1];
        if ($s === '.' || $s === '->' || $s === '::') {
          $tokens[$i][0] = 'OBJ';
          $i++;
        }
      }
    }
    return $tokens;
  }

}
  
/// @endcond
// end CORE