<?php
/*
 * LaTeX scanner,
 * brief explanation: we're using the stateful scanner to handle marginally
 * different rulesets in math blocks.
 * We could add in an awful lot of detail, everything is pretty generic right
 * now, we don't look for any specific names or anything, but it'll suffice
 * for basic highlighting.
 */
class LuminousLatexScanner extends LuminousStatefulScanner {

  function init() {

    
    // math states
    $this->add_pattern('displaymath', '/\\$\\$/', '/\\$\\$/');
    // literal '\[' and '\]'
    $this->add_pattern('displaymath', '/\\\\\\[/', '/\\\\\\]/');
    $this->add_pattern('mathmode', '/\\$/', '/\\$/');
    
    // terminals
    $this->add_pattern('COMMENT', '/%.*/');
    $this->add_pattern('NUMERIC', '/\d+(\.\d+)?\w*/');
    $this->add_pattern('MATH_FUNCTION', '/\\\\(?:[a-z_]\w*|[^\]])/i');
    $this->add_pattern('MATHOP', '/[\\*^\\-=+]+/');

    $this->add_pattern('FUNCTION', '/\\\\(?:[a-z_]\w*|.)/i');
    $this->add_pattern('IDENT', '/[a-z_]\w*/i');

    $this->add_pattern('OPERATOR', '/[\[\]\{\}]+/');

    $math_transition = array('NUMERIC', 'MATH_FUNCTION', 'MATHOP');

    $this->transitions = array(
      'initial' => array('COMMENT', 'OPERATOR', 'displaymath', 'mathmode',
        'FUNCTION', 'IDENT'),
      // omitting initial state defn. makes it transition to everything
      'displaymath' => $math_transition,
      'mathmode' => $math_transition,
    );

    $this->rule_tag_map = array(
      'displaymath' => 'INTERPOLATION',
      'mathmode' => 'INTERPOLATION',
      'MATHOP' => 'OPERATOR',
      'MATH_FUNCTION' => 'VALUE', // arbitrary way to distinguish it from non
      // math mode functions
    );
  }

  public static function guess_language($src, $info) {
    $p = 0.0;
    foreach(array('documentclass', 'usepackage', 'title',
      'maketitle', 'end') as $cmd)
    {
      if (strpos($src, '\\' . $cmd) !== false) $p += 0.1;
    }
    // count the number of backslashes
    $bslashes = substr_count($src, '\\');
    if ($bslashes > $info['num_lines']) {
      $p += 0.1;
    }
    if (substr_count($src, '%') > $info['num_lines']/10) {
      $p += 0.02;
    }
    return $p;
  }
}
