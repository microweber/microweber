<?php

/**
 * This is a rename of the JavaScript scanner.
 * TODO Some of these things are JS specific and should be moved into
 * the new JS scanner.
 */

class LuminousECMAScriptScanner extends LuminousEmbeddedWebScript {

  public $script_tags = '</script>';
  // regular expressions in JavaScript are delimited by '/', BUT, the slash
  // character may appear unescaped within character classes
  // we can handle this fairly easily with a single regex because the classes 
  // do not nest
  // TODO:
  // I do not know if this is specific to Javascript or ECMAScript derivatives 
  // as a whole, I also don't know if multi-line regexen are legal (i.e. when
  // the definition spans multiple lines)  
  protected $regex_regex  = "%
    /
    (?: 
      [^\\[\\\\/]+                    # not slash, backslash, or [
      | \\\\.                           # escape char 
      | 
      (?:                             # char class [..]
        \\[
          (?: 
            [^\\]\\\\]+               # not slash or ]
            | \\\\.                     # escape
          )*
        (?: \\] | \$)
      )                             # close char class  
    )*
    (?: /[iogmx]* | \$)                           #delimiter or eof
  %sx";
    
    
  // logs a persistent token stream so that we can lookbehind to figure out
  // operators vs regexes.
  private $tokens_ = array(); 
  
  private $child_state = null;
  
  function __construct($src=null) {
    
    $this->rule_tag_map = array(
      'COMMENT_SL' => 'COMMENT',
      'SSTRING' => 'STRING',
      'DSTRING' => 'STRING',
      'OPENER' => null,
      'CLOSER' => null,
    );
    $this->dirty_exit_recovery = array(
      'COMMENT_SL' => '/.*/',
      'COMMENT' => '%.*?(\*/|$)%s',
      'SSTRING' => "/(?:[^\\\\']+|\\\\.)*('|$)/",
      'DSTRING' => '/(?:[^\\\\"]+|\\\\.)*("|$)/',
      // FIXME: Anyone using a server-side interruption to build a regex is
      // frankly insane, but we are wrong in the case that they were in a
      // character class when the server language interrupted, and we may
      // exit the regex prematurely with this
      'REGEX' => '%(?:[^\\\\/]+|\\\\.)*(?:/[iogmx]*|$)%',
    );
    
    parent::__construct($src);
    $this->add_identifier_mapping('KEYWORD', array('break', 'case', 'catch', 
      'comment', 'continue', 'do', 'default', 'delete', 'else', 'export', 
      'for', 'function', 'if', 'import', 'in', 'instanceof', 'label', 'new', 
      'null', 'return', 'switch', 'throw', 'try', 'typeof', 'var', 'void', 
      'while', 'with',
      'true', 'false', 'this'
      ));
    $this->add_identifier_mapping('FUNCTION', array('$', 'alert', 'confirm', 
      'clearTimeout', 'clearInterval',
      'encodeURI', 'encodeURIComponent', 'eval', 'isFinite', 'isNaN', 
      'parseInt', 'parseFloat', 'prompt',
      'setTimeout', 'setInterval',      
      'decodeURI', 'decodeURIComponent', 'jQuery'));
      
    $this->add_identifier_mapping('TYPE', array('Array', 'Boolean', 'Date', 
      'Error', 'EvalError', 'Infinity', 'Image', 'Math', 'NaN', 'Number', 
      'Object', 'Option', 'RangeError', 'ReferenceError', 'RegExp', 'String',
      'SyntaxError', 'TypeError', 'URIError', 
      
      'document',
      'undefined', 'window'));
  }
  
  function is_operand() {
    for ($i = count($this->tokens) -1 ; $i>= 0; $i--) {
      $tok = $this->tokens[$i][0];
      if ($tok === null || $tok === 'COMMENT' || $tok === 'COMMENT_SL') continue;
      return ($tok === 'OPERATOR' || $tok === 'OPENER');
    }
    return true;
  }
  
  function init() {
    
    if ($this->embedded_server)
      $this->add_pattern('STOP_SERVER', $this->server_tags);
    if ($this->embedded_html)
      $this->add_pattern('STOP_SCRIPT', '%</script>%');
    
    $op_pattern = '[=!+*%\-&^|~:?\;,.>';
    if (!($this->embedded_server || $this->embedded_html)) 
      $op_pattern .= '<]+';
    else {
      // build an alternation with a < followed by a lookahead
      $op_pattern .= ']|<(?![';
      // XXX this covers <? and <% but not very well
      if ($this->embedded_server) $op_pattern .= '?%';
      if ($this->embedded_html) $op_pattern .= '/';
      $op_pattern .= '])'; // closes lookahead
      $op_pattern = "(?:$op_pattern)+";
    }
    $op_pattern = "@$op_pattern@";
    
    $this->add_pattern('IDENT', '/[a-zA-Z_$][_$\w]*/'); 
    // NOTE: slash is a special case, and </ may be a script close
    $this->add_pattern('OPERATOR', $op_pattern);
    // we care about openers for figuring out where regular expressions are
    $this->add_pattern('OPENER', '/[\[\{\(]+/');
    $this->add_pattern('CLOSER', '/[\]\}\)]+/');
    
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('SSTRING', LuminousTokenPresets::$SINGLE_STR_SL);
    $this->add_pattern('DSTRING', LuminousTokenPresets::$DOUBLE_STR_SL);
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML);
    $this->add_pattern('COMMENT_SL', LuminousTokenPresets::$C_COMMENT_SL);
    // special case
    $this->add_pattern('SLASH', '%/%');
    
    $stop_patterns = array();

    
    $xml_scanner = new LuminousHTMLScanner($this->string());
    $xml_scanner->xml_literal = true;
    $xml_scanner->scripts = false;
    $xml_scanner->embedded_server = $this->embedded_server;
    if ($this->embedded_server)
      $xml_scanner->server_tags = $this->server_tags;
    $xml_scanner->init();
    $xml_scanner->pos($this->pos());
    $this->add_child_scanner('xml', $xml_scanner);
  }
  
  
  
  
  // c+p from HTML scanner
  function scan_child($lang) {
    assert (isset($this->child_scanners[$lang]));
    $scanner = $this->child_scanners[$lang];
    $scanner->pos($this->pos());
    $substr = $scanner->main();
    $this->record($scanner->tagged(), 'XML', true);
    $this->pos($scanner->pos());
    if ($scanner->interrupt) {
      $this->child_state = array($lang, $this->pos());
    } else {
      $this->child_state = null;
    }
  } 

  
  function main() {
    $this->start();
    $this->interrupt = false;
    while (!$this->eos()) {
      $index = $this->pos();
      $tok = null;
      $m = null;
      $escaped = false;

      if (!$this->clean_exit) {
        try {
          $tok = $this->resume();
        } catch(Exception $e) {
          if (LUMINOUS_DEBUG) throw $e;
          else {
            $this->clean_exit = true;
            continue;
          }
        }
      }
      elseif ($this->child_state !== null && $this->child_state[1] < $this->pos()) {
        $this->scan_child($this->child_state[0]);
        continue;
      }
      
      elseif (($rule = $this->next_match()) !== null) {
        $tok = $rule[0];
        if ($rule[1] > $index) {
          $this->record(substr($this->string(), $index, $rule[1] - $index), null);
        }
      } else {
        $this->record(substr($this->string(), $index), null);
        $this->clean_exit = true;
        $this->interrupt = false;
        $this->terminate();
        break;
      }
      
      if ($tok === 'SLASH') {
        if ($this->is_operand()) {
          $tok = 'REGEX';
          $this->unscan();
          assert($this->peek() === '/');
          $m = $this->scan($this->regex_regex);
          if ($m === null) {
            assert(0);
            $m = $this->rest();
            $this->terminate();
          }
          
        } else {
          $tok = 'OPERATOR';
        }
      }
      elseif ($tok === 'OPERATOR' && $this->match() === '<') {
        if ($this->is_operand()) {
          $this->unscan();
          $this->scan_child('xml');
          continue;
        }
      }

      elseif ($tok === 'STOP_SERVER') {
        $this->interrupt = true;
        $this->unscan();
        break;
      }
      elseif ($tok === 'STOP_SCRIPT') {
        $this->unscan();
        break;
      }
      if ($m === null)
        $m = $this->match();
      
      if ($this->server_break($tok))
        break; 
      
      if ($tok === 'COMMENT_SL' && $this->script_break($tok)
      )
        break;
      assert($this->pos() > $index);
      
      $tag = $tok;

      $this->record($m, $tag, $escaped);

    }
  }
}
