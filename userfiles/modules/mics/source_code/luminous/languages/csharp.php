<?php
require_once(dirname(__FILE__) . '/include/csharp_list.php');
class LuminousCSharpScanner extends LuminousSimpleScanner {

  public function init() {

    
    $this->add_pattern('PREPROCESSOR', "/\\#(?: [^\\\\\n]+ | \\\\. )*/sx");
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_SL);
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML);
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR);
    $this->add_pattern('CHARACTER', LuminousTokenPresets::$SINGLE_STR);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);
    $this->add_pattern('IDENT', '/[a-z_]\w+/i');
    $this->add_pattern('OPERATOR', '/[¬!%^&*\-=+~|?\\/><;:.,]+/i');
    

    
    $this->add_identifier_mapping('KEYWORD', array('abstract', 'as', 'base',
      'break', 'case', 'catch', 'checked', 'class', 'continue', 'default',
      'delegate', 'do', 'event', 'explicit', 'extern', 'else', 'finally',
      'false', 'fixed', 'for', 'foreach', 'goto', 'if', 'implicit', 'in',
      'interface', 'internal', 'is', 'lock', 'new', 'null', 'namespace',
      'object', 'operator', 'out', 'override', 'params', 'private',
      'protected', 'public', 'readonly', 'ref', 'return', 'struct', 'switch',
      'sealed', 'sizeof', 'stackalloc', 'static', 'this', 'throw', 'true',
      'try', 'typeof', 'unchecked', 'unsafe', 'using', 'var', 'virtual',
      'volatile', 'while', 'yield'));

    $this->add_identifier_mapping('TYPE', array_merge(array(
      // primatives
      'bool', 'byte', 'char',
      'const', 'double', 'decimal', 'enum', 'float', 'int', 'long',
      'sbyte', 'short', 'string', 'uint', 'ulong', 'ushort'),
      $GLOBALS['luminous_csharp_type_list']));
  }

  static function guess_language($src, $info) {
    $p = 0.0;
    if (preg_match('/^\s*#region\\b/m', $src)) $p += 0.10;
    if (preg_match('/^\s*using\s+System;/m', $src)) $p += 0.10;
    if (preg_match('/^\s*using\s+System\\..*;/m', $src)) $p += 0.10;
    if (preg_match('/partial\s+class\s+\w+/', $src)) $p += 0.05;
    return $p;
  }
}
