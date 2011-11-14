<?php

/*
 * Groovy is pretty much a cross between Python and Java.
 * It inherits all of Java's stuff
 * http://groovy.codehaus.org/jsr/spec/Chapter03Lexical.html
 */
require_once(dirname(__FILE__) . '/include/java_func_list.php');
class LuminousGroovyScanner extends LuminousSimpleScanner {



  public $interpolation = false;
  protected $brace_stack = 0;

  function regex_override($match) {
    assert($this->peek() === '/');
    assert($match === array(0=>'/'));
    $regex = false;
    
    $i = count($this->tokens);
    while ($i--) {
      list($tok, $contents) = $this->tokens[$i];
      if ($tok === 'COMMENT') continue;
      elseif ($tok === 'OPERATOR') $regex = true;
      elseif($tok !== null) $regex = false;
      else {
        $t = rtrim($contents);
        if ($t === '') continue;
        $c = $t[strlen($t)-1];
        $regex = ($c === '(' || $c === '[' || $c === '{');
      }
      break;
    }
    
    if (!$regex) {
      $this->record($this->get(), 'OPERATOR');      
    }
    else {
      $m = $this->scan('@ / (?: [^\\\\/]+ | \\\\. )* (?: /|$) @sx');
      assert($m !== null);
      $this->record($m, 'REGEX');
    }
  }

  // string interpolation is complex and it nests, so we do that in here
  function interp_string($m) {
    // this should only be called for doubly quoted strings 
    // and triple-double quotes
    //
    // interpolation is betwee ${ ... }
    $patterns = array('interp' => '/(?<!\\$)\\$\\{/');
    $start = $this->pos();
    if (preg_match('/^"""/', $m[0])) {
      $patterns['term'] = '/"""/';
      $this->pos_shift(3);
    }
    else {
      assert(preg_match('/^"/', $m[0]));
      $patterns['term'] = '/"/';
      $this->pos_shift(1);
    }
    while (1) {
      $p = $this->pos();
      list($name, $index, $matches) = $this->get_next_named($patterns);
      if ($name === null) {
        // no matches, terminate
        $this->record(substr($this->string(), $start), 'STRING');
        $this->terminate();
        break;
      }
      elseif($name === 'term') {
        // end of the string
        $range = $index + strlen($matches[0]);
        $this->record(substr($this->string(), 
          $start, $range-$start), 'STRING');
        $this->pos($range);
        break;
      } else {
        // interpolation, handle this with a subscanner
        $this->record(substr($this->string(), $start, $index-$start), 'STRING');
        $this->record($matches[0], 'DELIMITER');
        $subscanner = new LuminousGroovyScanner($this->string());
        $subscanner->interpolation = true;
        $subscanner->init();
        $subscanner->pos($index + strlen($matches[0]));
        $subscanner->main();
        
        $tagged = $subscanner->tagged();
        $this->record($tagged, 'INTERPOLATION', true);
        $this->pos($subscanner->pos());
        if ($this->scan('/\\}/')) $this->record($this->match(), 'DELIMITER');
        $start = $this->pos();
      }
      assert($p < $this->pos());
      
    }
  }

  // brace override halts scanning if the stack is empty and we hit a '}',
  // this is for interpolated code, the top-level scanner doesn't bind to this
  function brace($m) {
    if ($m[0] === '{') $this->brace_stack++;
    elseif($m[0] === '}') {
      if ($this->brace_stack <= 0)
        return true;
      $this->brace_stack--;
    }
    else assert(0);
    $this->record($m[0], null);
    $this->pos_shift(strlen($m[0]));
  }

  
  function init() {
    $this->add_identifier_mapping('KEYWORD',
      $GLOBALS['luminous_java_keywords']);
    $this->add_identifier_mapping('TYPE', $GLOBALS['luminous_java_types']);
    $this->add_identifier_mapping('KEYWORD', array('any', 'as', 'def', 'in',
      'with', 'do', 'strictfp',
      'println'));


    // C+P from python
    // so it turns out this template isn't quite as readable as I hoped, but
    // it's a triple string, e.g:
    //  "{3} (?: [^"\\]+ | ""[^"\\]+ | "[^"\\]+  | \\.)* (?: "{3}|$)
    $triple_str_template = '%1$s{3} (?> [^%1$s\\\\]+ | %1$s%1$s[^%1$s\\\\]+ | %1$s[^%1$s\\\\]+ | \\\\. )* (?: %1$s{3}|$)';
    $str_template = '%1$s (?> [^%1$s\\\\]+ | \\\\. )* (?: %1$s|$)';
    $triple_dstr = sprintf($triple_str_template, '"');
    $triple_sstr = sprintf($triple_str_template, "'");

    $this->add_pattern('COMMENT', '/^#!.*/');
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML);
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_SL);
    $this->add_pattern('INTERP_STRING', "/$triple_dstr/sx");
    $this->add_pattern('STRING', "/$triple_sstr/xs");
    $this->add_pattern('INTERP_STRING', LuminousTokenPresets::$DOUBLE_STR);
    $this->overrides['INTERP_STRING'] = array($this, 'interp_string');
    // differs from java:
    $this->add_pattern('STRING', LuminousTokenPresets::$SINGLE_STR);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('IDENT', '/[a-zA-Z_]\w*/');
    $this->add_pattern('OPERATOR', '/[~!%^&*\-=+:?|<>]+/');
    $this->add_pattern('SLASH', '%/%');    
    $this->overrides['SLASH'] = array($this, 'regex_override');
    if ($this->interpolation) {
      $this->add_pattern('BRACE', '/[{}]/');
      $this->overrides['BRACE'] = array($this, 'brace');
    }
  }

  static function guess_language($src, $info) {
    $p = 0.0;
    if (preg_match('/\\bdef\s+\w+\s*=/', $src)) $p += 0.04;
    if (preg_match('/println\s+[\'"\w]/', $src)) $p += 0.03;
    // Flawed check for interpolation, might match after a string
    // terminator
    if (preg_match("/\"[^\"\n\r]*\\$\\{/", $src)) $p += 0.05;
    // regex literal ~/regex/
    if (preg_match('%~/%', $src)) $p += 0.05;
    if (preg_match('/^import\s+groovy/m', $src)) $p += 0.2;
    return $p;
  }
}
