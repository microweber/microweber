<?php

require_once(dirname(__FILE__) . '/include/c_func_list.php');
// TODO: trigraph... does anyone use these?

class LuminousCppScanner extends LuminousSimpleScanner {

  function __construct($src=null) {
    parent::__construct($src);
    $this->add_filter('preprocessor', 'PREPROCESSOR',
      array($this, 'preprocessor_filter'));

    $this->add_identifier_mapping('FUNCTION',
      $GLOBALS['luminous_c_funcs']);
    $this->add_identifier_mapping('KEYWORD',
      $GLOBALS['luminous_c_keywords']);
    $this->add_identifier_mapping('TYPE',
      $GLOBALS['luminous_c_types']);
  }

  function init() {
    
    // http://www.lysator.liu.se/c/ANSI-C-grammar-l.html
    //     D                       [0-9]
    //     L                       [a-zA-Z_]
    //     H                       [a-fA-F0-9]
    //     E                       [Ee][+-]?{D}+
    //     FS                      (f|F|l|L)
    //     IS                      (u|U|l|L)*//     
    //     {L}({L}|{D})*           ident
    //     0[xX]{H}+{IS}?          hex
    //     0{D}+{IS}?              octal 
    //     {D}+{IS}?               int
    //     L?'(\\.|[^\\'])+'       char 
    //     {D}+{E}{FS}?            real/float
    //     {D}*"."{D}+({E})?{FS}?  real/float
    //     {D}+"."{D}*({E})?{FS}?  real/float
    //     L?\"(\\.|[^\\"])*\"     string, but we should exclude nl
    
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML);
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_SL);
    $this->add_pattern('STRING', "/L?\"(?: [^\\\\\"\n]+ | \\\\.)*(?:$|\")/xms");
    // if memory serves, a char looks like this:
    $this->add_pattern('CHARACTER', 
      "/L? ' (?: \\\\(?: x[A-F0-9]{1,2}| . ) | . ) (?: '|$)/ixm");
    
    $this->add_pattern('OPERATOR', '@[!%^&*\-/+=~:?.|<>]+@');
    
    $this->add_pattern('NUMERIC', '/0[xX][A-F0-9]+[uUlL]*/i');
    $this->add_pattern('NUMERIC',  '/
    (?: 
      (?: \d* \.\d+   |   \d+\.\d*) 
      ([eE][+-]?\d+)?
      ([fFlL]?)
    )
    /ix');
    $this->add_pattern('NUMERIC', '/
      \d+([uUlL]+ | ([eE][+-]?\d+)?[fFlL]? | ) #empty string on the end
    /x'); //inc octal

    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('PREPROCESSOR', '/^[ \t]*\#/m');
    $this->add_pattern('IDENT', '/[a-zA-Z_]+\w*/');

    $this->overrides['PREPROCESSOR'] = array($this, 'preprocessor_override');
  }


  function preprocessor_override() {
    $this->skip_whitespace();
    // #if 0s nest, according to Kate, which sounds reasonable
    $pattern = '/^\s*#\s*if\s+0\\b/m';
    if($this->check($pattern)) {
      $this->nestable_token('COMMENT', '/^\s*#\s*if(?:n?def)?\\b/m',
        '/^\s*#\s*endif\\b/m');
    }
    else {
      // a preprocessor statement may have nested comments and strings. We
      // go the lazy route and just zap the whole thing with a regex and let a
      // filter figure out any nested highlighting
      $this->scan("@ \#
        (?: [^/\n\\\\]+
          | /\* (?> [^\\*]+ | (?:\*(?!/))+ ) (?: $|\*/)    # nested ML comment
          | //.*   # nested SL comment
          | /
          | \\\\(?s:.) # escape, and newline
        )* @x");
     $this->record($this->match(), 'PREPROCESSOR');
    }
  }

  static function preprocessor_filter_cb($matches) {
  
    if (!isset($matches[0]) || !isset($matches[0][0])) 
      return ''; // shouldn't ever happen
    if ($matches[0][0] === '"') return LuminousUtils::tag_block('STRING', $matches[0]);
    else if ($matches[0][0] === '&')
      return '&lt;' . LuminousUtils::tag_block('STRING', $matches[1]) . '&gt;';
    else return LuminousUtils::tag_block('COMMENT', $matches[0]);
  }

  static function preprocessor_filter($token) {
    $token = LuminousUtils::escape_token($token);
    $token[1] = preg_replace_callback("@
      (?:\" (?> [^\\\\\n\"]+ | \\\\. )* (?: \"|$) | (?: &lt; (.*?) &gt;))
      | // .*
      | /\* (?s:.*?) (\*/ | $)
    @x",
      array('LuminousCppScanner', 'preprocessor_filter_cb'),
      $token[1]);
    return $token;
  }

  static function guess_language($src, $info) {
    // Obviously, C tends to look an awful lot like pretty much every other
    // language. Its only real pseudo-distinct feature is the ugly 
    // preprocessor and "char * ", so let's go with that

    $p = 0.0;
    if (preg_match('/^\s*+#\s*+(include\s++[<"]|ifdef|endif|define)\\b/m', 
      $src)
    ) 
      $p += 0.3;
    if (preg_match('/\\bchar\s*\\*\s*\w+/', $src)) $p += 0.05;
    if (preg_match('/\\bmalloc\s*\\(/', $src)) $p += 0.02;
    // TODO we could guess at some C++ stuff too
    return $p;

  }

}
