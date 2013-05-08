<?php
/**
  * CSS scanner. 
  * TODO: it would be nice if we could extend this somehow to handle
  * CSS dialects which allow rule nesting. 
  */
class LuminousCSSScanner extends LuminousEmbeddedWebScript {
  
  
  private $expecting;
  
  function __construct($src=null) {
    parent::__construct($src);
        
    $this->rule_tag_map = array(
      'TAG' => 'KEYWORD',
      'KEY' => 'TYPE',
      'SELECTOR' => 'VARIABLE',
      'ATTR_SELECTOR' => 'OPERATOR',
      'SSTRING' => 'STRING',
      'DSTRING' => 'STRING',
      'ROUND_BRACKET_SELECTOR' => 'OPERATOR',
    );
    
    $this->dirty_exit_recovery = array(
      'COMMENT' => '%.*?(?:\*/|$)%s',
      'SSTRING' => "/(?:[^\\\\']+|\\\\.)*(?:'|$)/",
      'DSTRING' => '/(?:[^\\\\"]+|\\\\.)*(?:"|$)/',
      'ATTR_SELECTOR' => '/(?: [^\\]\\\\]+ | \\\\.)* (?:\]|$)/xs',
      'ROUND_BRACKET_SELECTOR' => '/(?: [^\\)\\\\]+ | \\\\.)* (?:\)|$)/xs',
    );
    $this->state_ [] = 'global';
  }
  
  
  function init() {
    $this->expecting = null;
  }
    
  
  
  function main() {
    
    $comment_regex = '% /\* .*? \*/ %sx';
    
    
    $this->start();
    
    
    while (!$this->eos()) {
      
      if (!$this->clean_exit) {
        try {
          $tok = $this->resume();
          if ($this->server_break($tok)) break;        
          $this->record($this->match(), $tok);
        } catch(Exception $e) {      
          if (LUMINOUS_DEBUG) throw $e;
          else continue;
        }
      }
      $this->skip_whitespace();
      $pos = $this->pos();
      $tok = null;
      $m = null;
      $state = $this->state();
      $in_block = $state === 'block';
      $in_media = $state === 'media';
      $get = false;
      $c = $this->peek();

      if ($this->embedded_server && $this->check($this->server_tags)) {
        $this->interrupt = true;
        $this->clean_exit = true;
        break;
      }
      elseif ($c === '/' && $this->scan(LuminousTokenPresets::$C_COMMENT_ML)) 
        $tok = 'COMMENT';
      elseif($in_block && $c === '#' && 
        $this->scan('/#[a-fA-F0-9]{3}(?:[a-fA-F0-9]{3})?/'))
        $tok = 'NUMERIC';
      elseif($in_block && (ctype_digit($c) || $c === '-') 
        && $this->scan('/-?(?>\d+)(\.(?>\d+))?(?:em|px|ex|ch|mm|cm|in|pt|%)?/')
        !== null) {
        $tok = 'NUMERIC';
      }
      elseif(!$in_block && $this->scan('/(?<=[#\.:])[\w\-]+/') !== null)
        $tok = 'SELECTOR';
      // check for valid super-blocks, e.g. media {...} and @keyframes {}
      elseif(!$in_block && !$in_media && $c === '@'
        && $this->scan('/@
          (-(moz|ms|webkit|o)-)?keyframes\\b
          |
          media\\b/x')
      ) {
        $this->state_[] = 'media';
        $tok = 'TAG';
      }  
      elseif(( ctype_alpha($c) || $c === '!' || $c === '@' || $c === '_' || $c === '-' )
        &&  $this->scan('/(!?)[\-\w@]+/')) {
        if ($in_media)  $tok = 'VALUE';
        elseif (!$in_block || $this->match_group(1) !== '') $tok = 'TAG';
        elseif($this->expecting === 'key') $tok = 'KEY';
        elseif($this->expecting === 'value') {
          $m = $this->match();
          if ($m === 'url' || $m === 'rgb' || $m === 'rgba') $tok = 'FUNCTION';
          else $tok = 'VALUE';
        }
      }

      // TODO attr selectors should handle embedded strings, I think.
      elseif(!$in_block && $c === '[' 
        && $this->scan('/\[ (?> [^\\]\\\\]+ | \\\\.)* \]/sx'))
        $tok = 'ATTR_SELECTOR';
        
      elseif(!$in_block && $c === '('
        && $this->scan('/\( (?> [^\\)\\\\]+ | \\\\.)* \) /sx')) {
        $tok = 'ROUND_BRACKET_SELECTOR';
      }
      elseif($c === '}' || $c === '{') {

        $get = true;
        if ($c === '}' && ($in_block || $in_media)) {
          array_pop($this->state_);
          if ($in_media) {
            // @media adds a 'media' state, then the '{' begins a new global state.
            // We've just popped global, now we need to pop media. 
            array_pop($this->state_);
          }
        }
        elseif (!$in_block && $c === '{') {
          if ($in_media) { 
            $this->state_[] = 'global';
          }
          else {
            $this->state_[] = 'block';
            $this->expecting = 'key';
          }
        }
        
      }
      elseif($c === '"' && $this->scan(LuminousTokenPresets::$DOUBLE_STR))
        $tok = 'DSTRING';      
      elseif($c === "'" && $this->scan(LuminousTokenPresets::$SINGLE_STR))         
        $tok = 'SSTRING';
      elseif($c === ':' && $in_block) {
        $this->expecting = 'value';
        $get = true;
        $tok = 'OPERATOR';
      }
      elseif($c=== ';' && $in_block) {
        $this->expecting = 'key';
        $get = true;
        $tok = 'OPERATOR';
      }
      elseif($this->embedded_html && $this->check('%<\s*/\s*style%i')) {
        $this->interrupt = false;
        $this->clean_exit = true;
        
        break;
      }
      elseif($this->scan('/[:\\.#>*]+/')) $tok = 'OPERATOR';
      
      else {
        $get = true;
      }
      
      if ($this->server_break($tok)) break;
     
      
      $m = $get? $this->get() : $this->match();
      $this->record($m, $tok);
      assert($this->pos() > $pos || $this->eos());
      
    }
  }

  public static function guess_language($src, $info) {
    $p = 0;
    if (preg_match(
      "/(font-family|font-style|font-weight)\s*+:\s*+[^;\n\r]*+;/", $src))
      $p += 0.15;
    if (strpos($src, '!important') !== false) $p += 0.05;
    // generic rule
    if (preg_match("/\\b(div|span|table|body)\\b [^\n\r\{]*+ [\r\n]*+ \{/x", 
      $src))
      $p += 0.10;
    return $p;
  }
}
