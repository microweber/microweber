<?php

require_once( dirname(__FILE__) . '/include/php_func_list.php');

/*
 * This is not a scanner called by an external interface, it's controlled
 * by LuminousPHPScanner (defined in this file).
 *
 * It should break when it sees a '?>', but it should assume it's in php
 * when it's called.
 */
class LuminousPHPSubScanner extends  LuminousScanner {
  
  protected $case_sensitive = false;
  public $snippet = false;
  
  function init() {
    $this->add_pattern('TERM', '/\\?>/'); 
    $this->add_pattern('COMMENT', '% (?://|\#) .*? (?=\\?>|$)  %xm');
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML); 
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    // this should be picked up by the LuminousPHPScanner, but in case
    // a user incorrectly calls the PHP-snippet scanner, we detect it.
    $this->add_pattern('DELIMITER', '/<\?(?:php)?/');
    $this->add_pattern('OPERATOR', '@[!%^&*\\-=+~:<>/\\|\\.;,]+|\\?(?!>)@');
    $this->add_pattern('VARIABLE', '/\\$\\$?[a-zA-Z_]\w*/');
    $this->add_pattern('IDENT', '/[a-zA-Z_]\w*/');
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR);
    $this->add_pattern('STRING', LuminousTokenPresets::$SINGLE_STR);
    $this->add_pattern('FUNCTION', '/`(?>[^`\\\\]+|\\\\.)*(`|$)/s');
    $this->add_identifier_mapping('FUNCTION', $GLOBALS['luminous_php_functions']);
    $this->add_identifier_mapping('KEYWORD', $GLOBALS['luminous_php_keywords']);

    $this->add_filter('STRING', array($this, 'str_filter'));
    $this->add_filter('HEREDOC', array($this, 'str_filter'));
    $this->add_filter('NOWDOC', array($this, 'nowdoc_filter'));
  }

  static function str_filter($token) {
    if ($token[1][0] !== '"' && $token[0] !== 'HEREDOC') return $token;
    elseif(strpos($token[1], '$') === false) return $token;
    
    $token = LuminousUtils::escape_token($token);
    // matches $var, ${var} and {$var} syntax
    $token[1] = preg_replace('/
      (?: \$\{ | \{\$ ) [^}]++ \}
      |
      \$\$?[a-zA-Z_]\w*
      /x', '<VARIABLE>$0</VARIABLE>',
    $token[1]);
    return $token;
  }
  
  static function nowdoc_filter($token) {
    $token[0] = 'HEREDOC';
    return $token;
  }
  
  function main() {
    $this->start();
    while (!$this->eos()) {
      $tok = null;

      $index = $this->pos();
      
      if (($match = $this->next_match()) !== null) {
        $tok = $match[0];
        if ($match[1] > $index) {
          $this->record(substr($this->string(), $index, $match[1] - $index), null);
        }
      } else {
        $this->record($this->rest(), null);
        $this->terminate();
        break;
      }
      
      if ($tok === 'TERM') {
        $this->unscan();
        break;
      }
      
      if($tok === 'IDENT') {
        // do the user defns here, i.e. class XYZ extends/implements ABC
        // or function XYZ
        $m = $this->match();
        $this->record($m, 'IDENT');
        if (($m === 'class' || $m === 'function' || $m === 'extends' || $m === 'implements')
          && $this->scan('/(\s+)([a-zA-Z_]\w*)/') )
        {
          $this->record($this->match_group(1), null);
          $this->record($this->match_group(2), 'USER_FUNCTION');
          $this->user_defs[$this->match_group(2)] = ($m === 'function')? 'FUNCTION'
            : 'TYPE';
        }
        continue;
      }

      elseif($tok === 'OPERATOR') {
        // figure out heredoc syntax here 
        if (strpos($this->match(), '<<<') !== false) {
          $this->record($this->match(), $tok);
          $this->scan('/([\'"]?)([\w]*)((?:\\1)?)/');
          $g = $this->match_groups();
          $nowdoc = false;
          if ($g[1]) {
            // nowdocs are delimited by single quotes. Heredocs MAY be 
            // delimited by double quotes, or not.
            $nowdoc = $g[1] === "'";
            $this->record($g[1], null);
          }
          $delimiter = $g[2];
          $this->record($delimiter, 'KEYWORD');
          if ($g[3]) $this->record($g[3], null);
          // bump us to the end of the line
          if (strlen($this->scan('/.*/')))
            $this->record($this->match(), null);
          if ($this->scan_until("/^$delimiter|\z/m")) {
            $this->record($this->match(), ($nowdoc)? 'NOWDOC' : 'HEREDOC');
            if ($this->scan('/\w+/')) 
              $this->record($this->match(), 'KEYWORD');
          }
          continue;
        }
      }

      assert($this->pos() > $index);
      $this->record($this->match(), $tok);
    }
  }
}




/*
 * This is a controller class which handles alternating between PHP and some
 * other language (currently HTML only, TODO allow plain text as well)
 * PHP and the other language are handled by subscanners
 */
class LuminousPHPScanner extends LuminousScanner {

  /// the 'non-php' scanner
  protected $subscanner;
  /// the real php scanner
  protected $php_scanner;

  /// If it's a snippet, we assume we're starting in PHP mode.
  public $snippet = false;

  
  function __construct($src=null) {
    $this->subscanner = new LuminousHTMLScanner($src);
    $this->subscanner->embedded_server = true;
    $this->subscanner->init();

    $this->php_scanner = new LuminousPHPSubScanner($src);
    $this->php_scanner->init();
    parent::__construct($src);
  }

  function string($s=null) {
    if ($s !== null) {
      $this->subscanner->string($s);
      $this->php_scanner->string($s);
    }
    return parent::string($s);
  }

  protected function scan_php($delimiter) {
    if ($delimiter !== null)
      $this->record($delimiter, 'DELIMITER');
    $this->php_scanner->pos($this->pos());
    $this->php_scanner->main();
    $this->record($this->php_scanner->tagged(),
      ($delimiter === '<?=')? 'INTERPOLATION' : null, true);
      
    $this->pos($this->php_scanner->pos());
    assert($this->eos() || $this->check('/\\?>/'));
    if ($this->scan('/\\?>/'))
      $this->record($this->match(), 'DELIMITER');
  }

  protected function scan_child() {
    $this->subscanner->pos($this->pos());
    $this->subscanner->main();
    $this->pos($this->subscanner->pos());
    assert($this->eos() || $this->check('/<\\?/'));
    $this->record($this->subscanner->tagged(), null, true);
  }

  function main() {
    while (!$this->eos()) {
      $p = $this->pos();
      if ($this->snippet)
        $this->scan_php(null);
      elseif ($this->scan('/<\\?(?:php|=)?/'))
        $this->scan_php($this->match());
      else
        $this->scan_child();
      assert($this->pos() > $p);
    }
  }

  static function guess_language($src, $info) {
    // cache p because this function is hit by the snippet scanner as well
    static $p = 0.0;
    static $src_ = null;
    if ($src_ === $src) {
      return $p;
    }
    // look for delimiter tags
    if (strpos($src, '<?php') !== false) $p += 0.5;
    elseif (preg_match('/<\\?(?!xml)/', $src)) $p += 0.20;
    // check for $this, self:: parent::
    if (preg_match('/\\$this\\b|((?i: self|parent)::)/x', $src)) $p += 0.15;
    // check for PHP's OO notation: $somevar->something 
    if (preg_match('/\\$[a-z_]\w*+->[a-z_]/i', $src)) $p += 0.05;
    // check for some common functions:
    if (preg_match('/\\b(echo|require(_once)?|include(_once)?|preg_\w)/i',
      $src)) $p += 0.05;
    $src_ = $src;
    return $p;
  }

}


class LuminousPHPSnippetScanner extends LuminousPHPScanner {
  public $snippet = true;

  public static function guess_language($src, $info) {
    $p = parent::guess_language($src, $info);
    if ($p > 0.0) {
      // look for the close/open tags, if there is no open tag, or if 
      // there is a close tag before an open tag, then we guess we're
      // in a snippet
      // if we are in a snippet we need to come out ahead of php, and
      // if we're not then we need to be behind it.
      $open_tag = strpos($src, '<?');
      $close_tag = strpos($src, '?>');
      if ($open_tag === false || 
        ($close_tag !== false && $close_tag < $open_tag))
      {
        $p += 0.01;
      }
      else $p -= 0.01;
    }
    return $p;
  }
}
