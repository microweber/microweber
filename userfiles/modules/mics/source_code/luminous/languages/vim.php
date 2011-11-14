<?php

// I can't find some formal definition of vimscript's grammar.
// I'm pretty sure it's more complex than this, but, who knows.

require_once(dirname(__FILE__) . '/include/vim_list.php');

class LuminousVimScriptScanner extends LuminousSimpleScanner {
  
  public function string_override() {
    $comment = $this->bol();
    $this->skip_whitespace();
    assert($this->peek() === '"');
    if ($comment) {
      $this->record($this->scan("/.*/"), 'COMMENT');
    } else {
      if ($this->scan("/ \" (?> [^\n\"\\\\]+ | \\\\. )*$ /mx")) {
        $this->record($this->match(), 'COMMENT');
      }
      else {
        $m = $this->scan(LuminousTokenPresets::$DOUBLE_STR);
        assert($m !== null);
        $this->record($m, 'STRING');
      }
    }
  }
  
  static function comment_filter($token) {
    $token = LuminousUtils::escape_token($token);
    $str = &$token[1];
    // It pays to run the strpos checks first.
    if (strpos(substr($str, 1), '"') !== false)
      $str = preg_replace('/(?<!^)"(?>[^"]*)"/', "<STRING>$0</STRING>", $str);
    
    if (strpos($str, ':') !== false)
      $str = preg_replace('/(?<=^")((?>\W*))((?>[A-Z]\w+(?>(?>\s+\w+)*)))(:\s*)(.*)/',
      '$1<DOCTAG>$2</DOCTAG>$3<DOCSTR>$4</DOCSTR>', $str);
    
    return $token;
  }
  
  function init() {
    
    $this->add_pattern('COMMENT_STRING', "/[\t ]*\"/");
    $this->add_pattern('STRING', "/'(?>[^\n\\\\']+ | \\\\. )*'/x");
    $this->add_pattern('NUMERIC','/\#[a-f0-9]+/i');    
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('IDENT', '/[a-z_]\w*/i');
    $this->add_pattern('OPERATOR', '@[~¬!%^&*\-=+;:,<.>/?\|]+@');
    
    $this->add_identifier_mapping('FUNCTION', 
      $GLOBALS['luminous_vim_functions']);
    $this->add_identifier_mapping('KEYWORD', 
      $GLOBALS['luminous_vim_keywords']);
    
    $this->remove_stream_filter('oo-syntax');
    $this->remove_filter('comment-to-doc');
    $this->add_filter('comment', 'COMMENT', array($this, 'comment_filter'));
    $this->overrides = array('COMMENT_STRING' => array($this, 'string_override'));
    
  }
}
