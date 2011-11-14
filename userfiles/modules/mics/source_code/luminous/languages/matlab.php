<?php

/*
 * Matlab's pretty simple. Hoorah
 */


class LuminousMATLABScanner extends LuminousSimpleScanner {
  
  // Comments can nest. This beats a PCRE recursive regex, because they are 
  // pretty flimsy and crash/stack overflow easily 
  function comment_override($matches) {
    $this->nestable_token('COMMENT', '/%\\{/', '/%\\}/');
  }

  function init() {
    // these can nest so we override this
    $this->add_pattern('COMMENT_ML', '/%\\{/');
    $this->add_pattern('COMMENT', '/%.*/');
    $this->add_pattern('IDENT', '/[a-z_]\w*/i');
    // stray single quotes are a unary operator when they're attached to 
    // an identifier or return value, or something. so we're going to 
    // use a lookbehind to exclude those
    $this->add_pattern('STRING', 
      "/(?<![\w\)\]\}']) ' (?: [^']+ | '')* ($|')/x");
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('OPERATOR', "@[¬!%^&*\-+=~;:|<>,./?]+|'@");
    
    $this->overrides = array('COMMENT_ML' => array($this, 'comment_override'));
    
    include(dirname(__FILE__) . '/include/matlab_func_list.php');
    $this->add_identifier_mapping('KEYWORD', $luminous_matlab_keywords);
    $this->add_identifier_mapping('VALUE', $luminous_matlab_values);
    $this->add_identifier_mapping('FUNCTION', $luminous_matlab_functions);
  }

  public static function guess_language($src, $info) {
    $p = 0;
    // matlab comments are quite distinctive
    if (preg_match('/%\\{.*%\\}/s', $src)) $p += 0.25;
    return $p;
  }
  
}
