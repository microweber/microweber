<?php

/**
 * Scala
 *
 * Direct port of old luminous language file.
 *
 * TODO: The XML literals may contain embedded scala code. This is bad
 * because we ignore that currently, and we may, in rare circumstances, 
 * incorrectly pop a tag when in fact it's inside a scala expression
 *
 * Some comments reference section numbers of the scala spec:
 * http://www.scala-lang.org/sites/default/files/linuxsoft_archives/docu/files/ScalaReference.pdf
 *
 */

// scala inherits some stuff from Java
require_once(dirname(__FILE__) . '/include/java_func_list.php');
class LuminousScalaScanner extends LuminousSimpleScanner {

  /**
   * Multiline comments nest
   */
  function comment_override() {
    $this->nestable_token('COMMENT', '%/\\*%', '%\\*/%');
  }

  /**
   * Scala has XML literals. 
   */
  function xml_override($matches) {
    // this might just be an inequality, so we first need to disambiguate
    // that 
    
    // 1.5 - the disambiguation is pretty simple, an XML tag must 
    // follow either whitespace, (, or {, and the '<' must be followed
    // by '[!?_a-zA-Z]
    // I'm not sure if a comment is a special case, or if it's treated as
    // whitespace...
    $xml = false;
    for($i=count($this->tokens)-1; $i>=0; $i--) {
      $tok = $this->tokens[$i];
      $name = $tok[0];
      // ... but we're going treat it as a no-op and skip over it
      if ($name === 'COMMENT') continue;
      $last_char = $tok[1][strlen($tok[1])-1];
      if (!(ctype_space($last_char) || $last_char === '(' || 
        $last_char === '{')) break;
      if (!$this->check('/<[!?a-zA-Z0-9_]/')) break;
      $xml = true;
    }
    if (!$xml) {
      $this->record($matches[0], 'OPERATOR');
      $this->pos_shift(strlen($matches[0]));
      return;
    } 
    $subscanner = new LuminousXMLScanner();
    $subscanner->string($this->string());
    $subscanner->pos($this->pos());
    $subscanner->xml_literal = true;
    $subscanner->init();
    $subscanner->main();
    $tagged = $subscanner->tagged();
    $this->record($tagged, 'XML', true);
    $this->pos($subscanner->pos());
  }

  function init() {
    $this->add_pattern('COMMENT', LuminousTokenPresets::$C_COMMENT_SL);
    $this->add_pattern('COMMENT_ML', '%/\\*%');
    $this->overrides['COMMENT_ML'] = array($this, 'comment_override');


    // 1.3.1 integer literals, 1.3.2 floatingPointLiteral 
    // Do the float first so it takes precedence, our scanner does not follow 
    // the max-munch rule 
    $digit = '\d';
    $exp = '(?:[eE][+-]?\d+)';
    $suffix = '[FfDd]';
    $this->add_pattern('NUMERIC', "/(?: \d+\\.\d* | \\.\d+) $exp? $suffix? /x");
    $this->add_pattern('NUMERIC', "/\d+($exp $suffix? |$exp?$suffix)/x");
    $this->add_pattern('NUMERIC', '/(?:0x[a-fA-F0-9]+|\d+)[lL]?/');

    // 1.3.4 character literals
    // we can't really parse the unicode and work out what's printable,
    // so we'll just allow any unicode sequence
    $this->add_pattern('CHARACTER', 
      "/'
      (
        (?:\\\\ (?:u[a-f0-9]{1,4}|\d+|.))
        | .
      )'/sx");
    // 1.3.5 - 1.3.6
    // strings are kind of pythonic, triple quoting makes them multiline
    $this->add_pattern('STRING', '/""" 
      (?: [^"\\\\]+ | \\\\. | ""[^"] | "[^"])*
      (?:"""|$)/sx');
    $this->add_pattern('STRING', LuminousTokenPresets::$DOUBLE_STR_SL);

    $this->add_pattern('lt', '/</');
    $this->overrides['lt'] = array($this, 'xml_override');
    $this->add_pattern('OPERATOR', '/[¬!%^&*-=+~;:|>\\/?\\\\]+/');

    $this->add_pattern('IDENT', '/[a-z_]\w*/i');



    // 1.3.3 boolean literals
    $this->add_identifier_mapping('VALUE', array('true', 'false', 'null', 'None'));

    // from old luminous file
    $this->add_identifier_mapping('KEYWORD', array('abstract', 'case',
      'catch', 'class', 'def', 'do', 'else', 'extends', 'final', 'finally',
      'for', 'forSome', 'if', 'implicit', 'import', 'lazy', 'match',
      'new', 'object', 'override', 'package', 'private', 'protected',
      'return', 'sealed', 'super', 'this', 'throw', 'trait', 'try', 'type',
      'val', 'var', 'while', 'with', 'yield'));
    $this->add_identifier_mapping('TYPE', array('boolean', 'byte', 'char',
      'dobule', 'float', 'int', 'long', 'string', 'short', 'unit',
      'Boolean', 'Byte', 'Char', 'Double', 'Float', 'Int', 'Long', 'String',
      'Short', 'Unit'));
    // from Kate's syntax file
    $this->add_identifier_mapping('TYPE', array('ActorProxy', 'ActorTask', 
      'ActorThread', 'AllRef', 'Any', 'AnyRef', 'Application', 'AppliedType', 
      'Array', 'ArrayBuffer', 'Attribute', 'BoxedArray', 'BoxedBooleanArray', 
      'BoxedByteArray', 'BoxedCharArray', 'Buffer', 'BufferedIterator', 'Char',
      'Console', 'Enumeration', 'Fluid', 'Function', 'IScheduler', 
      'ImmutableMapAdaptor', 'ImmutableSetAdaptor', 'Int', 'Iterable', 'List',
      'ListBuffer', 'None', 'Option', 'Ordered', 'Pair', 'PartialFunction', 
      'Pid', 'Predef', 'PriorityQueue', 'PriorityQueueProxy', 'Reaction', 
      'Ref', 'Responder', 'RichInt', 'RichString', 'Rule', 'RuleTransformer', 
      'Script', 'Seq', 'SerialVersionUID', 'Some', 'Stream', 'Symbol', 
      'TcpService', 'TcpServiceWorker', 'Triple', 'Unit', 'Value', 
      'WorkerThread', 'serializable', 'transient', 'volatile'));

    $this->add_identifier_mapping('TYPE', $GLOBALS['luminous_java_types']);

  }

  public static function guess_language($src, $info) {
    $p = 0;
    // func def, a lot like python
    if (preg_match('/\\bdef\s+\w+\s*\(/', $src)) $p += 0.05;
    // val x = y
    if (preg_match('/\\bval\s+\w+\s*=/', $src)) $p += 0.1;
    // argument types
    if (preg_match('/\\(\s*\w+\s*:\s*(String|Int|Array)/', $src)) $p += 0.05;
    // tripled quoted strings, like python
    if (preg_match('/\'{3}|"{3}/', $src)) $p += 0.05;
    return $p;
  }
}
