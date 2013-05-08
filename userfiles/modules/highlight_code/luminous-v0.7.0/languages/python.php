<?php
/*
 * Python scanner - includes Django
 *
 * TODO: Django does not respect {% comment  %} ... {% endcomment %}
 */
class LuminousPythonScanner extends LuminousScanner {

  public $django = false;
  
  public function init() {
    
    $this->remove_filter('comment-to-doc');
    
    // so it turns out this template isn't quite as readable as I hoped, but
    // it's a triple string, e.g:
    //  "{3} (?: [^"\\]+ | ""[^"\\]+ | "[^"\\]+  | \\.)* (?: "{3}|$)
    
    
    $triple_str_template = '%1$s{3} (?> [^%1$s\\\\]+ | %1$s%1$s[^%1$s\\\\]+ | %1$s[^%1$s\\\\]+ | \\\\. )* (?: %1$s{3}|$)';
    $str_template = '%1$s (?> [^%1$s\\\\]+ | \\\\. )* (?: %1$s|$)';
    $triple_dstr = sprintf($triple_str_template, '"');
    $triple_sstr = sprintf($triple_str_template, "'");    
    
    $this->add_pattern('IDENT', '/[a-zA-Z_](?>\w*)(?!["\'])/');
    // I *assume* that Django tags terminate these
    $this->add_pattern('COMMENT', sprintf('/\#.*%s/',
      $this->django? '(?=[%}]\})' : ''));
    
    
    // decorator
    $this->add_pattern('TYPE', '/@(\w+\.?)+/');
    
    // Python strings may be prefixed with r (raw) or u (unicode).
    // This affects how it handles backslashes, but I don't *think* it
    // affects escaping of quotes.... 
    $this->add_pattern('STRING', "/[RUru]?$triple_dstr/xs");
    $this->add_pattern('STRING', "/[RUru]?$triple_sstr/xs");
    $this->add_pattern('STRING', "/[RUru]?" . sprintf($str_template, '"') . '/sx');
    $this->add_pattern('STRING', "/[RUru]?" . sprintf($str_template, "'") . '/xs');
    
    // EPIC.
    $this->add_pattern('NUMERIC', '/
    #hex
    (?:0[xX](?>[0-9A-Fa-f]+)[lL]*)
    |
    # binary
    (?:0[bB][0-1]+)
    |
    #octal
    (?:0[oO0][0-7]+)
    |
    # regular number
    (?:
      (?>[0-9]+)
      (?: 
        # long identifier
        [lL]
        |
        # Or a fractional part, which may be imaginary
        (?:
          (?:\.?(?>[0-9]+)?        
            (?:(?:[eE][\+\-]?)?(?>[0-9]+))?
          )[jJ]?
        )
      )?
    )
    |
    (
      # or only after the point, float x = .1;
      \.(?>[0-9]+)(?:(?:[eE][\+\-]?)?(?>[0-9]+))?[jJ]?
    )
    /x');

    // %} and }} are django terminators
    if ($this->django) {
      $this->add_pattern('TERM', '/[%}]\}/');
    }

    // catch the colon separately so we can use $match === ':' in figuring out
    // where docstrs occur
    $this->add_pattern('OPERATOR', '/\+=|-=|\*=|\/=|>=|<=|!=|==|\*\*|[!%^*\-=+;<>\\\\(){}\[\],\\.:]/');

    if ($this->django) {
      // Django specific keywords
      // https://docs.djangoproject.com/en/1.3/ref/templates/builtins/
      $this->add_identifier_mapping('KEYWORD', array('autoescape',
        'endautoescape', 'cycle', 'filter', 'endfilter', 'include',
        'extends', 'firstof', 'empty', 'ifchanged', 'endifchanged',
        'ifequal', 'endifequal', 'ifnotequal', 'endifnotequal',
        'load',  'now',  'regroup', 'spaceless', 'endspaceless',
        'ssi', 'url',  'widthratio', 'endwith',
        'endfor', 'endif',
        'endwhile'));
    }

    $this->add_identifier_mapping('KEYWORD', array('assert', 'as', 'break',
    'class', 'continue', 'del', 'def', 'elif', 'else', 'except', 'exec',
    'finally', 'for', 'from', 'global', 'if', 'import', 'lambda', 
    'print', 'pass', 'raise', 'return', 'try', 'while', 'yield',
    'with',
    'and', 'not', 'in', 'is', 'or', 'print'));
    
    $this->add_identifier_mapping('FUNCTION', array('all', 'abs', 'any', 
    'basestring', 'bin', 'callable', 'chr', 'classmethod', 'cmp', 'compile',
    'dir', 'divmod', 'enumerate', 'eval', 'execfile', 'file', 'filter', 
    'format',
    'frozenset', 'getattr', 'globals', 'hasattr', 'hash', 'help', 'hex', 
    'id', 'input', 'isinstance', 'issubclass', 'iter', 'len', 'locals', 'map',
    'max', 'min', 'memoryview', 'next', 'object', 'oct', 'open', 'ord', 'pow',
     'property', 'range', 'raw_input', 'reduce', 'reload', 'repr', 'reversed',
     'round', 'setattr', 'slice', 'sorted', 'staticmethod', 'sum', 'super',
     'type', 'unichr', 'vars', 'xrange', 'zip', '__import__',

     'bytearray', 'complex', 'dict', 'float', 'int', 'list', 'long',
     'set', 'str', 'tuple', 'unicode', 'apply', 'buffer', 'coerce', 'intern'
   ));

    // http://docs.python.org/library/exceptions.html
    $this->add_identifier_mapping('TYPE', 
      array('BaseException', 'SystemExit', 
      'KeyboardInterrupt', 'GeneratorExit', 'Exception', 'StopIteration', 
      'StandardError', 'BufferError', 'ArithmeticError',
      'FloatingPointError', 'OverflowError', 'ZeroDivisionError', 
      'AssertionError',
      'AttributeError', 'EnvironmentError', 'IOError', 'OSError',
      'WindowsError(Windows)', 'VMSError(VMS)', 'EOFError', 'ImportError',
      'LookupError', 'IndexError', 'KeyError', 'MemoryError', 'NameError',
      'UnboundLocalError', 'ReferenceError', 'RuntimeError', 
      'NotImplementedError',
      'SyntaxError', 'IndentationError', 'TabError', 'SystemError', 'TypeError',
      'ValueError', 'UnicodeError', 'UnicodeDecodeError', 'UnicodeEncodeError',
      'UnicodeTranslateError', 'Warning', 'DeprecationWarning',
      'PendingDeprecationWarning', 'RuntimeWarning', 'SyntaxWarning', 
      'UserWarning',
      'FutureWarning', 'ImportWarning', 'UnicodeWarning', 'BytesWarning'));

    $this->add_identifier_mapping('VALUE', array('False', 'None', 'self', 
      'True'));
  }
  
  
  // mini-scanner to handle highlighting module names in import lines
  private function import_line() {
    $import = false;
    $from = false;
    while(!$this->eol()) {
      $c = $this->peek();
      $tok = null;
      $m = null;
      
      if ($c === '\\') $m = $this->get(2);
      elseif($this->scan('/[,\\.;\\*]+/')) $tok = 'OPERATOR';
      elseif($this->scan("/[ \t]+/")){}
      elseif(($m = $this->scan('/import\\b|from\\b/'))){
        if ($m === 'import') $import = true;
        elseif($m === 'from') $from = true;
        else assert(0);
        $tok = 'IDENT';
      }
      elseif($this->scan('/[_a-zA-Z]\w*/')) {
        assert($from || $import);
        // from module import *item*, or just import *item*
        if ($import) {
          $tok = 'USER_FUNCTION';
          $this->user_defs[$this->match()] = 'TYPE';
        }
        // from *module* ...[import item], the module is not imported
        else $tok = 'IDENT';
      }
      else break;
      $this->record(($m !== null)? $m : $this->match(), $tok);
    }
  }
  
  
  function main() {
    $definition = false;
    $doccstr = false;
    $expect = '';
    while (!$this->eos()) {
      $tok = null;
      $index = $this->pos();
      
      if (($rule = $this->next_match()) !== null) {
        $tok = $rule[0];
        if ($rule[1] > $index) {
          $this->record(substr($this->string(), $index, $rule[1] - $index), null);
        }
      } else {
        $this->record(substr($this->string(), $index), null);
        $this->terminate();
        break;
      }
      // Django terminator tag - break to superscanner
      if ($tok === 'TERM') {
        $this->unscan();
        break;
      }
      
      $m = $this->match();
      
      /* python doc strs are a pain because they're actually just strings. 
       * Also, I'm pretty sure a string in a non-interesting place just counts
       * as a no-op and is also used as a comment sometimes
       * So we've got something a bit complicated going on here: if we meet
       * a 'class' or a 'def' (function def) then we wait until the next ':' 
       * and say "we expect a doc-str now". If the next token is not a string,
       * we discard that state.
       * 
       * similarly, if we meet a string which isn't a doc-str, we look behind 
       * and expect to see an operator or open bracket, else it's a comment.
       * NOTE: we class ':' as a legal string preceding char because it's used
       * as dictionary key:value separators. This will fail on the case:
       * 
       * while 1: 
       *  "do something" 
       *  break
       * 
       * 
       * NOTE: note we're skipping whitespace.
       * NOTE: we disable the no-op detection for Django because the string
       * might be inside an output tag.
       * 
       */
      
      if ($definition && $doccstr) {
        if($tok === 'STRING')
          $tok = 'COMMENT';
      }
      
      elseif ($tok === 'STRING' && !$this->django) {
        $i = count($this->tokens);
        $tok = 'COMMENT';
        while ($i--) {
          $t = $this->tokens[$i][0];
          $s = $this->tokens[$i][1];
          if ($t === null || $t === 'COMMENT') continue;
          elseif ($t === 'OPERATOR' || $t === 'IDENT' || $t === 'NUMERIC') { 
            $tok = 'STRING';
          }
          break;
        }
        // finally, if we can look ahead to a binary operator, or so,
        // we concede it probably is a string
        if ($tok === 'COMMENT') {
          if ($this->check('/\s*(?: [+:&.,] | (?:and|or|is|not)\\b)/x'))
            $tok = 'STRING';
        }
      }
     
      // reset this; if it didn't catch above then it's not valid now.
      if ($definition && $doccstr) {
        $definition = false;
        $doccstr = false;
      }
      
      if ($tok === 'IDENT') {
        if ($m === 'import' || $m === 'from') {
          $this->unscan();
          $this->import_line();
          continue;
        }
        // these are definition keywords, the next token should be an 
        // identifier, which is a user-defined type or function
        if ($m === 'class' || $m === 'def') {
          $definition = true;
          $expect = 'user_def';
        }
        // this is caught on the next iteration
        elseif($expect === 'user_def') {
          $tok = 'USER_FUNCTION';
          $expect = false;
          $this->user_defs[$m] = 'FUNCTION';
        }
      }
      else { 
        // if this hasn't caught, it's not valid
        $expect = false; 
      }
      
      if ($definition && $m === ':') {
        $doccstr = true;
      }
      
      $this->record($m, $tok);
    }
  }


  public static function guess_language($src, $info) {
    if (strpos($info['shebang'], 'python') !== false) return 1.0;
    if ($info['shebang']) return 0.0;
    $p = 0.0;
    // let's look for some trademark pythonic constructs, although I 
    // have a feeling that recent versions of ECMA also impelment some
    // of this
    if (preg_match('/^\s*+ for \s++ \w++ \s++ in \s++ \w++ \s*+ :/xm', $src))
      $p += 0.05;
    if (preg_match('/True|False|None/', $src)) $p += 0.01;
    if (preg_match('/"{3}|\'{3}/', $src)) $p += 0.05;
    // class something(object)
    //
    if (preg_match('/^\s*+ class \s++ \w++ \s*+ \( \s*+ object \s*+ \)/xm', 
      $src)) $p += 0.1;
    // def __init__ (constructor)
    if (preg_match('/\\bdef \s++ __init__\\b/x', $src)) $p += 0.2;
    // method decorators
    if (preg_match("/^\s*+ @[\w\\.]++ .*+ [\n\r]++ \s*+ def\\b/mx", $src)) 
      $p += 0.1;
    // pmax = 0.41

    // common imports: import os|sys|re
    if (preg_match('/^import\s++(os|sys|re)\\b/m', $src))
      $p += 0.05;
    // from x import y
    if (preg_match('/^\s*+ from \s++ (?:\w++(?:\.\w++)*+) \s++ import \s/xm', 
      $src))
      $p += 0.10;


    return $p;
  }
}


class LuminousDjangoScanner extends LuminousScanner {
  // warning: some copying and pasting with the rails scanner here
  
  // HTML scanner has to be persistent.
  private $html_scanner;

  public function init() {
    $this->html_scanner = new LuminousHTMLScanner();
    $this->html_scanner->string($this->string());
    $this->html_scanner->embedded_server = true;
    $this->html_scanner->server_tags = '/\{[{%#]/';
    $this->html_scanner->init();
  }

  public function scan_html() {
    $this->html_scanner->pos($this->pos());
    $this->html_scanner->main();
    $this->record($this->html_scanner->tagged(), null, true);
    $this->pos($this->html_scanner->pos());
  }




  public function scan_python($short=false) {
    $python_scanner = new LuminousPythonScanner($this->string());
    $python_scanner->django = true;
    $python_scanner->init();
    $python_scanner->pos($this->pos());
    $python_scanner->main();
    $this->record($python_scanner->tagged(), $short? 'INTERPOLATION' : null, true);
    $this->pos($python_scanner->pos());
  }


  public function main() {
    while(!$this->eos()) {
      $p = $this->pos();
      // django's tags are {{ }} and {% %}
      // there's also a {#  #} comment tag but we can probably handle that here
      // more easily
      // same for {% comment %} ... {% endcomment %}
      if ($this->scan('/\{([{%])/')) {
        $match = $this->match();
        $m1 = $this->match_group(1);
        // {% comment %} ... {% endcomment %}
        if ($this->scan('/\s*comment\s*%\}/')) {
          $match .= $this->match();
          $end_pattern = '/\{%\s*endcomment\s*%\}/';
          if ($this->scan_until($end_pattern) !== null) {
            $match .= $this->match();
            $match .= $this->scan($end_pattern);
          }
          else {
            $match .= $this->rest();
            $this->terminate();
          }
          $this->record($match, 'COMMENT');
        }
        // {{ ... }} or {% ... %}
        else {
          $this->record($match, 'DELIMITER');
          $this->scan_python($m1 === '{');
          if ($this->scan('/[}%]\}/')) {
            $this->record($this->match(), 'DELIMITER');
          }
        }
      // {# ... #}
      } elseif($this->scan('/\{\# (?: [^\#]++ | \#(?! \} ) )*+ (?: \#\} | $)/x')) {
        $this->record($this->match(), 'COMMENT');
      }
      else {
        $this->scan_html();
      }
      assert($p < $this->pos());
    }
  }

  public static function guess_language($src, $info) {
    if (($html = LuminousHTMLScanner::guess_language($src, $info)) >= 0.2) {
      if (strpos($src, '{{') !== false || strpos($src, '{%') !== false)
        return $html + 0.01;
    }
    return 0.0;
  }
}
