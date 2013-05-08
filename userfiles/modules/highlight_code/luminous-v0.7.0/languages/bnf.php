<?php

/*
 * BNF has a lot of different variants and matching them all is pretty much
 * impossible.
 *
 * We're going to match the standard BNF and extended BNF and hopefully a 
 * few very similar dialects
 */

class LuminousBNFScanner extends LuminousStatefulScanner {

  function user_def_ext($matches) {
    if ($matches[1] !== '')
      $this->record($matches[1], null);
    $this->record_token($matches[2], 'USER_FUNCTION');
    $this->user_defs[$matches[2]] = 'VALUE';
    $this->pos_shift(strlen($matches[1]) + strlen($matches[2]));
  }

  private function set_strict() {
    // no transition table necessary, I think
    $this->add_pattern('COMMENT', '/<![^>]*>/');
    $this->add_pattern('KEYWORD', '/(?<=^<)[^>]+(?=>)/m');
    $this->add_pattern('KEYWORD', '/(?<=^\\{)[^\\}]+(?=\\})/m');
    $this->add_pattern('VALUE', '/(?<=\\{)[^\\}]+(?=\\})/');
    $this->add_pattern('VALUE', '/[\\-\w]+/');

  }
  private function set_extended() {

    
    $this->add_pattern('COMMENT', '/\\(\\* .*? \\*\\)/sx');
    $this->add_pattern('OPTION', '/\\[/', '/\\]/');
    $this->add_pattern('REPETITION', '/\\{/', '/\\}/');
    $this->add_pattern('GROUP', '/\\(/', '/\\)/');
    $this->add_pattern('SPECIAL', '/\\?/', '/\\?/');

    $ident = '(?:[\w\\-]+)';
    $this->add_pattern('RULE', "/(^[ \t]*)($ident)(\s*(?![[:alnum:]\s]))/mi");
    $this->overrides['RULE'] = array($this, 'user_def_ext');
    $this->add_pattern('IDENT', "/$ident/");

    // technically I don't know if we really need to worry about a transition
    // table, but here we are anyway
    $all = array('COMMENT', 'OPTION', 'REPETITION', 'GROUP', 'SPECIAL', 
      'STRING', 'IDENT', 'OPERATOR');
    $almost_all = array_filter($all, create_function('$x', 
      'return $x !== "SPECIAL";'));
    $this->transitions = array(
      'initial' => array_merge(array('RULE'), $all),
      'OPTION' => $all,
      'REPETITION' => $all,
      'GROUP' => $all,
      'SPECIAL' => $almost_all
    );

    $this->rule_tag_map = array(
      'OPTION' => null,
      'REPETITION' => null,
      'GROUP' => null,
      'SPECIAL' => null
    );

  }


  function init() {

    // the original BNF uses <angle brackets> to delimit its 
    // production rule names
    if (preg_match('/<\w+>/', $this->string())) {
      $this->set_strict();
    }
    else {
      $this->set_extended();
    }
    $this->add_pattern('STRING', LuminousTokenPresets::$SINGLE_STR_SL);
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR_SL);
    $this->add_pattern('OPERATOR', '/[*\\-=+;:\\|,]+/');
    // assume a few chars at bol indicate a commented line
    $this->add_pattern('COMMENT', '/^[!%-;].*/m');

    $this->remove_filter('constant');
    $this->remove_filter('comment-to-doc');
 
  }

  static function guess_language($src, $info) {
    // being honest, BNF is going to be so rare that if we ever return 
    // anything other than 0, it's more likely that we're obscuring the 
    // correct scanner than correctly identifying BNF.
    return 0;
  }
}
