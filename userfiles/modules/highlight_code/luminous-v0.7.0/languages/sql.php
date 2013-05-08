<?php

class LuminousSQLScanner extends LuminousSimpleScanner {
  public function init() {
    $this->case_sensitive = false;
//    $this->remove_stream_filter('oo-syntax');
    $this->remove_filter('comment-to-doc');
    $this->remove_filter('constant');
    include(dirname(__FILE__) . '/include/sql.php');
    $this->add_identifier_mapping('KEYWORD', $keywords);
    $this->add_identifier_mapping('TYPE', $types);
    $this->add_identifier_mapping('VALUE', $values);
    $this->add_identifier_mapping('OPERATOR', $operators);
    $this->add_identifier_mapping('FUNCTION', $functions);


    $this->add_pattern('IDENT', '/[a-zA-Z_]+\w*/');
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_ML);
    // # is for MySQL.
    $this->add_pattern('COMMENT', '/(?:\#|--).*/');
    $this->add_pattern('STRING', LuminousTokenPresets::$SQL_SINGLE_STR_BSLASH);
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR);
    $this->add_pattern('STRING', '/ ` (?> [^\\\\`]+ | \\\\. )* (?: `|$)/x');
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_HEX);
    $this->add_pattern('NUMERIC', LuminousTokenPresets::$NUM_REAL);

    $this->add_pattern('OPERATOR', '/[¬!%^&*\\-=+~:<>\\|\\/]+/');

    $this->add_pattern('KEYWORD', '/\\?/');
  }

  public static function guess_language($src, $info) {
    // we have to be careful not to assign too much weighting to 
    // generic SQL keywords, which will often appear in other languages
    // when those languages are executing SQL statements
    //
    // All in all, SQL is pretty hard to recognise because generally speaking,
    // an SQL dump will probably contain only a tiny fraction of SQL keywords
    // with the majority of the text just being data. 
    $p = 0.0;
    // if we're lucky, the top line will be a comment containing the phrase
    // 'SQL' or 'dump'
    if (strpos($info['trimmed'], '--') === 0 && isset($info['lines'][0])
      && (
        stripos($info['lines'][0], 'sql') !== false)
        || stripos($info['lines'][0], 'dump' !== false)
      )
      $p = 0.5;
    

    foreach(array('SELECT', 'CREATE TABLE', 'INSERT INTO', 'DROP TABLE',
      'INNER JOIN', 'OUTER JOIN') as $str) 
    {
      if (strpos($src, $str) !== false) $p += 0.01;
    }
    // single line comments --
    if (preg_match_all('/^--/m', $src, $m) > 5)
      $p += 0.05;
    if (preg_match('/VARCHAR\(\d+\)/', $src)) $p += 0.05;
    return $p;
  }
}
