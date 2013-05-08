<?php

/*
 * VB.NET
 *
 * Language spec:
 * http://msdn.microsoft.com/en-us/library/aa712050(v=vs.71).aspx
 *
 * TODO: IIRC vb can be embedded in asp pages like php or ruby on rails,
 * and XML literals: these are a little bit confusing, something
 * like "<xyz>.something" appears to be a valid XML fragment (i.e. the <xyz>
 * is a complete fragment), but at other times, the fragment would run until
 * the root tag is popped. Need to find a proper description of the grammar 
 * to figure it out
 */
class LuminousVBScanner extends LuminousSimpleScanner {

  public $case_sensitive = false;

  public function init() {

    $this->add_pattern('PREPROCESSOR', "/^[\t ]*#.*/m");
    $this->add_pattern('COMMENT', "/'.*/");

    $this->add_pattern('COMMENT', '/\\bREM\\b.*/i');
    // float
    $this->add_pattern('NUMERIC', '/ (?<!\d)
      \d+\.\d+ (?: e[+\\-]?\d+)?
      |\.\d+ (?: e[+\\-]?\d+)?
      | \d+ e[+\\-]?\d+
      /xi');
    // int
    $this->add_pattern('NUMERIC', '/ (?:
      &H[0-9a-f]+
      | &O[0-7]+
      | (?<!\d)\d+
    ) [SIL]*/ix');

    $this->add_pattern('CHARACTER', '/"(?:""|.)"c/i');

    $this->add_pattern('STRING', '/" (?> [^"]+ | "" )* ($|")/x');
    // in theory we should also match unicode quote chars
    // in reality, well, I read the php docs and I have no idea if it's
    // even possible.
    // The chars are:
    // http://www.fileformat.info/info/unicode/char/201c/index.htm
    // and 
    // http://www.fileformat.info/info/unicode/char/201d/index.htm

    // date literals, this isn't as discriminating as the grammar specifies.
    $this->add_pattern('VALUE', "/#[ \t][^#\n]*[ \t]#/");

    $this->add_pattern('OPERATOR', '/[&*+\\-\\/\\\\^<=>,\\.]+/');

    // http://msdn.microsoft.com/en-us/library/aa711645(v=VS.71).aspx
    // XXX: it warns about ! being ambiguous but I don't see how it can be 
    // ambiguous if we use this regex?
    $this->add_pattern('IDENT', '/[a-z_]\w*[%&@!#$]?/i');

    // we'll borrow C#'s list of types (ie modules, classes, etc)
    include(dirname(__FILE__) . '/include/vb.php');
    include(dirname(__FILE__) . '/include/csharp_list.php');
    $this->add_identifier_mapping('VALUE', $luminous_vb_values);
    $this->add_identifier_mapping('OPERATOR', $luminous_vb_operators);
    $this->add_identifier_mapping('TYPE', $luminous_vb_types);
    $this->add_identifier_mapping('KEYWORD', $luminous_vb_keywords);
    $this->add_identifier_mapping('TYPE', $luminous_csharp_type_list);
  }

  public static function guess_language($src, $info) {
    $p = 0.0;
    if (preg_match('/^Imports\s+System/i', $src)) $p += 0.1;
    if (preg_match('/Dim\s+\w+\s+As\s+/i', $src)) $p += 0.2;
    if (preg_match('/(Public|Private|Protected)\s+Sub\s+/i', $src)) $p += 0.1;
    return $p;
  }
}

