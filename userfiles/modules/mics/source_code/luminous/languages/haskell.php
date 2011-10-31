<?php

// Haskell scanner.
// We do not yet support TemplateHaskell because it looks INSANE.

/*
 * TODO: Some contextual awareness would be great, Kate seems to highlight
 * things differently depending on whether they're in [..] or (...) blocks,
 * but I don't understand Haskell enough to embark on that right now.
 * 
 * It would also be nice to distinguish between some different classes of
 * operator.
 */

require_once(dirname(__FILE__) . '/include/haskell.php');

class LuminousHaskellScanner extends LuminousSimpleScanner {

  // handles comment nesting of multiline comments.
  function comment_override() {
    $this->nestable_token('COMMENT', '/\\{-/', '/-\\}/');
  }

  function init() {
    // import from ./include/
    global $luminous_haskell_functions;
    global $luminous_haskell_types;
    global $luminous_haskell_values;
    global $luminous_haskell_keywords;
    $this->add_identifier_mapping('KEYWORD', $luminous_haskell_keywords);
    $this->add_identifier_mapping('TYPE', $luminous_haskell_types);
    $this->add_identifier_mapping('FUNCTION', $luminous_haskell_functions);
    $this->add_identifier_mapping('VALUE', $luminous_haskell_values);


    // shebang
    $this->add_pattern('COMMENT', '/^#!.*/');

    // Refer to the sections in
    // http://www.haskell.org/onlinereport/lexemes.html
    // for the rules implemented here.
    
    // 2.4
    $this->add_pattern('TYPE', '/[A-Z][\'\w]*/');
    $this->add_pattern('IDENT', '/[_a-z][\'\w]*/');

    // http://www.haskell.org/onlinereport/prelude-index.html
    $this->add_pattern('FUNCTION', '/
(?: !!|\\$!?|&&|\\|{1,2}|\\*{1,2}|\\+{1,2}|-(?!-)|\\.|\\/=?|<=?|==|=<<|>>?=?|\\^\\^? )
/x');

    $op_chars = '\\+%^\\/\\*\\?#<>:;=@\\[\\]\\|\\\\~\\-!$@%&\\|=';

    // ` is used to make a function call into an infix operator
    // CRAZY OR WHAT.
    $this->add_pattern('OPERATOR', '/`[^`]*`/');
    // some kind of function, lambda, maybe.
    $this->add_pattern('FUNCTION', "/\\\\(?![$op_chars])\S+/");
    
    // Comments are hard!
    // http://www.mail-archive.com/haskell@haskell.org/msg09019.html
    // According to this, we can PROBABLY, get away with checking either side
    // for non-operator chars followed by at least 2 dashes, but I could well
    // be wrong. It'll do for now.
    $this->add_pattern('COMMENT', "/(?<![$op_chars])---*(?![$op_chars]).*/");
    // nested comments are easy!
    $this->add_pattern('NESTED_COMMENT', '/\\{-/');
    $this->overrides['NESTED_COMMENT'] = array($this, 'comment_override');
    $this->rule_tag_map['NESTED_COMMENT'] = 'COMMENT';
    $this->add_pattern('OPERATOR', "/[$op_chars]+/");

    // FIXME: the char type is way more discriminating than this
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR_SL);
    $this->add_pattern('CHARACTER', LuminousTokenPresets::$SINGLE_STR_SL);

    // 2.5
    $this->add_pattern('NUMERIC', '/
      0[oO]\d+  #octal
      |
      0[xX][a-fA-F\d]+  #hex
      |
      # decimal and float can be done at once, according to the grammar
      \d+ (?: (?:\.\d+)? (?: [eE][+-]? \d+))?
      /x');
    
  }

  public static function guess_language($src, $info) {
    $p = 0.0;
    // comments
    if (preg_match('/\\{-.*\\-}/', $src)) $p += 0.05;
    // 'import qualified' seems pretty unique
    if (preg_match('/^import\s+qualified/m', $src)) $p += 0.05;
    // "data SomeType something ="
    if (preg_match('/data\s+\w+\s+\w+\s*=/', $src)) $p += 0.05;
    return $p;
  }

}
