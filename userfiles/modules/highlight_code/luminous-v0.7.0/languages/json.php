<?php


class LuminousJSONScanner extends LuminousScanner {
  
  private $stack = array();
  
  
  public function init() {
    $this->add_identifier_mapping('KEYWORD', array('true', 'false', 'null'));
  
    
  }
  public function state() {
    if (!empty($this->stack)) return $this->stack[count($this->stack)-1][0];
    else return null;
  }
  
  private function expecting($x=null) {
    if ($x !== null) {
      if (!empty($this->stack)) $this->stack[count($this->stack)-1][1] = $x;
    }
    if (!empty($this->stack)) return $this->stack[count($this->stack)-1][1];
    else return null;
  }

  function main() {
    while (!$this->eos()) {
      $tok = null;
      $c = $this->peek();
      
      list($state, $expecting) = array($this->state(), $this->expecting());
      
      $this->skip_whitespace();
      if ($this->eos())  break;
      if ($this->scan(LuminousTokenPresets::$NUM_REAL) !== null) {
        $tok = 'NUMERIC';
      }
      elseif($this->scan('/[a-zA-Z]\w*/')) {
        $tok = 'IDENT';
      }
      elseif($this->scan(LuminousTokenPresets::$DOUBLE_STR)) {
        $tok = ($state === 'obj' && $expecting === 'key')? 'TYPE' : 'STRING';
      }
      elseif($this->scan('/\[/')) {
        $this->stack[] = array('array', null);
        $tok = 'OPERATOR';
      }
      elseif($this->scan('/\]/')) {
        if ($state === 'array') {
          array_pop($this->stack);
          $tok = 'OPERATOR';
        }
      }
      elseif($this->scan('/\{/')) {
        $this->stack[] = array('obj', 'key');
        $tok = 'OPERATOR';
      }
      elseif($state === 'obj' && $this->scan('/\}/')) {
        array_pop($this->stack);
        $tok = 'OPERATOR';
      }
      elseif($state === 'obj' && $this->scan('/:/')) {
        $this->expecting('value');
        $tok = 'OPERATOR';
      }
      elseif($this->scan('/,/')) {
        if ($state === 'obj') {
          $this->expecting('key');
          $tok = 'OPERATOR';
        }
        elseif($state === 'array') $tok = 'OPERATOR';
      }
      else $this->scan('/./');
      
      $this->record($this->match(), $tok);
    }
  }

  public static function guess_language($src, $info) {
    // JSON is fairly hard to guess
    $p = 0;
    $src_ = trim($src);
    if (!empty($src_)) {
      $char = $src_[0];
      $char2 = $src_[strlen($src_)-1];
      $str = '"(?>[^"\\\\]+|\\\\.)"';
      // looks like an object or array
      if ( ($char === '[' && $char2 === ']')
        || ($char === '{' && $char2 === '}')) 
      {
        $p += 0.05;
      } 
      elseif(preg_match("/^(?:$str|(\d+(\.\d+)?([eE]\d+)?)|true|false|null)$/",
        $src_)) 
      {
        // just a string or number or value
        $p += 0.1;
      } 
    }
    return $p;


  }
  
}
