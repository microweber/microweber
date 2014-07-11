<?php
/**
 * This file contains the QueryPathImpl, the main implementation of the 
 * QueryPath interface.
 * @see QueryPath
 * @package QueryPath
 * @subpackage Internals
 * @author M Butcher <matt@aleph-null.tv>
 * @license http://opensource.org/licenses/lgpl-2.1.php LGPL (The GNU Lesser GPL) or an MIT-like license.
 */

/**
 * This is the main implementation of the QueryPath interface.
 *
 * It provides core services for the Query Path. The class is final.
 *
 * @see QueryPath
 */
final class QueryPathImpl implements QueryPath, IteratorAggregate {
  
  const DEFAULT_PARSER_FLAGS = NULL;
  
  private $document = NULL;
  private $options = array();
  private $matches = array();
  private $last = array(); // Last set of matches.
  private $ext = array(); // Extensions array.
  
  /**
   * Take a list of DOMNodes and return a unique list.
   *
   * Constructs a new array of elements with no duplicate DOMNodes.
   */
  public static function unique($list) {
    return UniqueElementList::get($list);
  }
  
  public function __construct($document = NULL, $string = NULL, $options = array()) {
	$string = trim($string);
    $this->options = $options;
    
    $parser_flags = isset($options['parser_flags']) ? $options['parser_flags'] : self::DEFAULT_PARSER_FLAGS;
    
    // Empty: Just create an empty QP.
    if (empty($document)) {
      $this->document = new DOMDocument();
      $this->matches = array();
    }
    // Figure out if document is DOM, HTML/XML, or a filename
    elseif (is_object($document)) {
      if ($document instanceof QueryPath) {
	    $this->matches = $document->get();
        if (!empty($this->matches))
          $this->document = $this->matches[0]->ownerDocument;
      }
      elseif ($document instanceof DOMDocument) {
        $this->document = $document;
        $this->matches = array($document->documentElement);
      }
      elseif ($document instanceof DOMNode) {
        $this->document = $document->ownerDocument;
        $this->matches = array($document);
      }
      elseif ($document instanceof SimpleXMLElement) {
        $import = dom_import_simplexml($document);
        $this->document = $import->ownerDocument;
        $this->matches = array($import);
      }
      else {
        throw new QueryPathException('Unsupported class type: ' . get_class($document));
      }
    }
    elseif (is_array($document)) {
      if (!empty($document) && $document[0] instanceof DOMNode) {
        $this->matches = $document;
        $this->document = $this->matches[0]->ownerDocument;
      }
    }
    elseif ($this->isXMLish($document)) {
      // $document is a string with XML
      $this->document = $this->parseXMLString($document, 'UTF-8');
      $this->matches = array($this->document->documentElement);
    }
    else {
      // $document is a filename
      $context = empty($options['context']) ? NULL : $options['context'];
      $this->document = $this->parseXMLFile($document, $parser_flags, $context);
      $this->matches = array($this->document->documentElement);
    }
    
    // Do a find if the second param was set.
    if (isset($string) && strlen($string) > 0) {
      $this->find($string);
    }
    
    // Do extensions loading.
    /* Defer this until an extension method is actually called.
    if (QueryPathExtensionRegistry::$useRegistry) {
      $this->ext = QueryPathExtensionRegistry::getExtensions($this);
    }
    */
  }
  
  public function top() {
    $this->setMatches(array($this->document->documentElement));
    return $this;
  }
  
  public function find($selector) {
    
    // Optimize for ID/Class searches. These two take a long time
    // when a rdp is used. Using an XPath pushes work to C code.
    $ids = array();
    $regex = '/^#([\w-]+)$|^\.([\w-]+)$/'; // $1 is ID, $2 is class.
    //$regex = '/^#([\w-]+)$/';
    if (preg_match($regex, $selector, $ids) === 1) {
      // If $1 is a match, we have an ID.
      if (!empty($ids[1])) {
        $xpath = new DOMXPath($this->document);
        foreach ($this->matches as $item) {
          $nl = $xpath->query("//*[@id='{$ids[1]}']", $item);
          if ($nl->length > 0) {
            $this->setMatches(array($nl->item(0)));
            break;
          }
        }
      }
      // Quick search for class values. While the XPath can't do it
      // all, it is faster than doing a recusive node search.
      else {
        //$this->xpath("//*[@class='{$ids[2]}']");
        $xpath = new DOMXPath($this->document);
        $found = array();
        foreach ($this->matches as $item) {
          $nl = $xpath->query("//*[@class]", $item);
          for ($i = 0; $i < $nl->length; ++$i) {
            $vals = explode(' ', $nl->item($i)->getAttribute('class'));
            if (in_array($ids[2], $vals)) $found[] = $nl->item($i);
          }
        }
        $this->setMatches($found);
      }
      
      return $this;
    }
    
    $query = new QueryPathCssEventHandler($this->matches);
    $query->find($selector);
    //$this->matches = $query->getMatches();
    $this->setMatches($query->getMatches());
    return $this;
  }
  
  public function xpath($query) {
    $xpath = new DOMXPath($this->document);
    $found = array();
    foreach ($this->matches as $item) {
      $nl = $xpath->query($query, $item);
      if ($nl->length > 0) {
        for ($i = 0; $i < $nl->length; ++$i) $found[] = $nl->item($i);
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function size() {
    return count($this->matches);
  }
  
  public function get($index = NULL) {
    if (isset($index)) {
      return ($this->size() > $index) ? $this->matches[$index] : NULL;
    }
    return $this->matches;
  }
  public function attr($name, $value = NULL) {
    // multi-setter
    if (is_array($name)) {
      foreach ($name as $k => $v) {
        foreach ($this->matches as $m) $m->setAttribute($k, $v);
      }
      return $this;
    }
    // setter
    if (isset($value)) {
      foreach ($this->matches as $m) $m->setAttribute($name, $value);
      return $this;
    }
    
    //getter
    if (empty($this->matches)) return NULL;
    
    // Special node type handler:
    if ($name == 'nodeType') {
      return $this->matches[0]->nodeType;
    }
    
    // Always return first match's attr.
    return $this->matches[0]->getAttribute($name);
  }
  
  public function css($name = NULL, $value = '') {
    if (empty($name)) {
      return $this->attr('style');
    }
    $format = '%s: %s';
    if (is_array($name)) {
      $buf = array();
      foreach ($name as $key => $val) {
        $buf[] = sprintf($format, $key, $val);
      }
      implode(';', $buf);
    }
    else {
      $css = sprintf($format, $name, $value);
    }
    $this->attr('style', $css);
    return $this;
  }
  
  public function removeAttr($name) {
    foreach ($this->matches as $m) {
      //if ($m->hasAttribute($name))
        $m->removeAttribute($name);
    }
    return $this;
  }
  
  public function eq($index) {
    $this->setMatches(array($this->matches[$index]));
    return $this;
  }
  
  public function is($selector) {
    foreach ($this->matches as $m) {
      $q = new QueryPathCssEventHandler($m);
      if (count($q->find($selector)->getMatches())) {
        return TRUE;
      }
    }
    return FALSE;
  }
  
  public function filter($selector) {
    $found = array();
    foreach ($this->matches as $m) if (qp($m)->is($selector)) $found[] = $m;
    $this->setMatches($found);
    return $this;
  }
  
  public function filterLambda($fn) {
    $function = create_function('$index, $item', $fn);
    $found = array();
    $count = count($this->matches);
    for ($i = 0; $i < $count; ++$i) {
      $item = $this->matches[$i];
      if ($function($i, $item) !== FALSE) $found[] = $item;
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function filterCallback($callback) {
    $found = array();
    if (is_array($callback)) {
      if (is_object($callback[0])) {
        // Object/func
        $obj = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          if ($obj->$func($i, $item) !== FALSE) $found[] = $item;
        }
      }
      else {
        // Class/func
        $class = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          // FIXME: This might only work on >= 5.2. Plus it's lame.
          if (${"$class::$func"}($i, $item) !== FALSE) $found[] = $item;
        }
      }
    }
    else {
      // function
      for ($i = 0; $i < $this->size(); ++$i) {
        $item = $this->matches[$i];
        if ($callback($i, $item) !== FALSE) $found[] = $item;
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function not($selector) {
    $found = array();
    if ($selector instanceof DOMElement) {
      foreach ($this->matches as $m) if ($m !== $selector) $found[] = $m; 
    }
    elseif (is_array($selector)) {
      foreach ($this->matches as $m) if (!in_array($m, $selector)) $found[] = $m; 
    }
    else {
      foreach ($this->matches as $m) if (!qp($m)->is($selector)) $found[] = $m;
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function index($subject) {
    for ($i = 0; $i < $this->size(); ++$i) {
      if ($this->matches[$i] === $subject) {
        return $i;
      }
    }
    return FALSE;
  }
  
  public function map($callback) {
    $found = array();
    if (is_array($callback)) {
      if (is_object($callback[0])) {
        // Object/func
        $obj = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          $c = $obj->$func($i, $item);
          if (isset($c)) {
            is_array($c) ? $found = array_merge($found, $c) : $found[] = $c;
          }
        }
      }
      else {
        // Class/func
        $class = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          // FIXME: This might only work on >= 5.2. Plus it's lame.
          $c = ${"$class::$func"}($i, $item);
          if (isset($c)) {
            is_array($c) ? $found = array_merge($found, $c) : $found[] = $c;
          }
        }
      }
    }
    else {
      // function
      for ($i = 0; $i < $this->size(); ++$i) {
        $item = $this->matches[$i];
        $c = $callback($i, $item); 
        if (isset($c)) {
          is_array($c) ? $found = array_merge($found, $c) : $found[] = $c;
        }
      }
    }
    $this->setMatches($found, FALSE);
    return $this;
  }
  
  public function slice($start, $end = NULL) {
    if ($start >= $this->size()) {
      $this->setMatches(array());
      return $this;
    }
    $this->setMatches(array_slice($this->matches, $start, $end));
    return $this;
  }
  
  public function each($callback) {
    if (is_array($callback)) {
      if (is_object($callback[0])) {
        // Object/func
        $obj = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          if ($obj->$func($i, $item) === FALSE) return $this;
        }
      }
      else {
        // Class/func
        $class = $callback[0];
        $func = $callback[1];
        for ($i = 0; $i < $this->size(); ++$i) {
          $item = $this->matches[$i];
          // FIXME: This might only work on >= 5.2. Plus it's lame.
          if (${"$class::$func"}($i, $item) === FALSE) return $this;
        }
      }
    }
    else {
      // function
      for ($i = 0; $i < $this->size(); ++$i) {
        $item = $this->matches[$i];
        if ($callback($i, $item) === FALSE) return $this; 
      }
    }
    return $this;
  }

  public function eachLambda($lambda) {
    for ($index = 0; $index < $this->size(); ++$index) {
      $fn = create_function('$index, &$item', $lambda);
      $item = $this->matches[$index];  
      if ($fn($index, $item) === FALSE) return $this;
    }
    return $this;
  }
  
  public function append($data) {
    $data = $this->prepareInsert($data);
    if (isset($data)) {
      //print empty($this->document->documentElement) ? 'empty' : 'ne';
      if (empty($this->document->documentElement) && empty($this->matches)) {
        // Then we assume we are writing to the doc root
        $this->document->appendChild($data);
        $this->matches = array($this->document->documentElement);
      }
      else {
        // You can only append in item once. So in cases where we
        // need to append multiple times, we have to clone the node.
        foreach ($this->matches as $m) { 
          // DOMDocumentFragments are even more troublesome, as they don't
          // always clone correctly. So we have to clone their children.
          if ($data instanceof DOMDocumentFragment) {
            foreach ($data->childNodes as $n)
              $m->appendChild($n->cloneNode(TRUE));
          }
          else {
            // Otherwise a standard clone will do.
            $m->appendChild($data->cloneNode(TRUE));
          }
          
        }
      }
        
    }
    return $this;
  }
  
  public function appendTo(QueryPath $dest) {
    foreach ($this->matches as $m) $dest->append($m);
    return $this;
  }
  
  public function prepend($data) {
    $data = $this->prepareInsert($data);
    if (isset($data)) {
      foreach ($this->matches as $m) {
        $ins = $data->cloneNode(TRUE);
        if ($m->hasChildNodes())
          $m->insertBefore($ins, $m->childNodes->item(0));
        else
          $m->appendChild($ins);
      }
    }
    return $this;
  }
  
  public function prependTo(QueryPath $dest) {
    foreach ($this->matches as $m) $dest->prepend($m);
    return $this;
  }

  
  public function before($data) {
    $data = $this->prepareInsert($data);
    foreach ($this->matches as $m) {
      $ins = $data->cloneNode(TRUE);
      $m->parentNode->insertBefore($ins, $m);
    }
    
    return $this;
  }
  public function insertBefore(QueryPath $dest) {
    foreach ($this->matches as $m) $dest->before($m);
    return $this;
  }
  
  public function insertAfter(QueryPath $dest) {
    foreach ($this->matches as $m) $dest->after($m);
    return $this;
  }
  
  public function after($data) {
    $data = $this->prepareInsert($data);
    foreach ($this->matches as $m) {
      $ins = $data->cloneNode(TRUE);
      if (isset($m->nextSibling)) 
        $m->parentNode->insertBefore($ins, $m->nextSibling);
      else
        $m->parentNode->appendChild($ins);
    }
    return $this;
  }
  
  public function replaceWith($new) {
    $data = $this->prepareInsert($new);
    $found = array();
    foreach ($this->matches as $m) {
      $parent = $m->parentNode;
      $parent->insertBefore($data->cloneNode(TRUE), $m);
      $found[] = $parent->removeChild($m);
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function wrap($markup) {
    $data = $this->prepareInsert($markup);
    
    foreach ($this->matches as $m) {
      $copy = $data->firstChild->cloneNode(TRUE);
      
      // XXX: Should be able to avoid doing this over and over.
      if ($copy->hasChildNodes()) {
        $deepest = $this->deepestNode($copy); 
        $bottom = $deepest[0];
      }
      else
        $bottom = $copy;

      $parent = $m->parentNode;
      $parent->insertBefore($copy, $m);
      $m = $parent->removeChild($m);
      $bottom->appendChild($m);
      //$parent->appendChild($copy);
    }
    return $this;  
  }
  
  public function wrapAll($markup) {
    if (empty($this->matches))
      return;
    
    $data = $this->prepareInsert($markup);
    if ($data->hasChildNodes()) {
      $deepest = $this->deepestNode($data); 
      $bottom = $deepest[0];
    }
    else
      $bottom = $data;

    $parent = $this->matches[0]->parentNode;
    $parent->insertBefore($data, $this->matches[0]);
    foreach ($this->matches as $m) {
      $bottom->appendChild($m->parentNode->removeChild($m));
    }
    return $this;
  }
  
  public function wrapInner($markup) {
    $data = $this->prepareInsert($markup);
    if ($data->hasChildNodes()) {
      $deepest = $this->deepestNode($data); 
      $bottom = $deepest[0];
    }
    else
      $bottom = $data;
      
    foreach ($this->matches as $m) {
      if ($m->hasChildNodes()) {
        while($m->firstChild) {
          $kid = $m->removeChild($m->firstChild);
          $bottom->appendChild($kid);
        }
      }
      $m->appendChild($data);
    }
    return $this; 
  }
  
  public function deepest() {
    $deepest = 0;
    $winner = array();
    foreach ($this->matches as $m) {
      $local_deepest = 0;
      $local_ele = $this->deepestNode($m, 0, NULL, $local_deepest);
      if ($local_deepest > $deepest) {
        $winner = $local_ele;
        $deepest = $local_deepest;
      }
      elseif ($local_deepest == $deepest) {
        $winner = array_merge($winner, $local_ele);
      }
    }
    $this->setMatches($winner);//array($winner);
    return $this;
  }
  
  /**
   * A depth-checking function. Typically, it only needs to be
   * invoked with the first parameter. The rest are used for recursion.
   * @see deepest();
   */
  protected function deepestNode(DOMNode $ele, $depth = 0, $current = NULL, &$deepest = NULL) {
    if (!isset($current)) $current = array($ele);
    if (!isset($deepest)) $deepest = $depth;
    if ($ele->hasChildNodes()) {
      foreach ($ele->childNodes as $child) {
        if ($child->nodeType === XML_ELEMENT_NODE) {
          $current = $this->deepestNode($child, $depth + 1, $current, $deepest);
        }
      }
    }
    elseif ($depth > $deepest) {
      $current = array($ele);
      $deepest = $depth;
    }
    elseif ($depth === $deepest) {
      $current[] = $ele;
    }
    return $current;
  }

	private function replace_entity ($entity) {
		$entity_array = array(
		  'nbsp' => 160, 'iexcl' => 161, 'cent' => 162, 'pound' => 163, 
		  'curren' => 164, 'yen' => 165, 'brvbar' => 166, 'sect' => 167, 
		  'uml' => 168, 'copy' => 169, 'ordf' => 170, 'laquo' => 171, 
		  'not' => 172, 'shy' => 173, 'reg' => 174, 'macr' => 175, 'deg' => 176, 
		  'plusmn' => 177, 'sup2' => 178, 'sup3' => 179, 'acute' => 180, 
		  'micro' => 181, 'para' => 182, 'middot' => 183, 'cedil' => 184, 
		  'sup1' => 185, 'ordm' => 186, 'raquo' => 187, 'frac14' => 188, 
		  'frac12' => 189, 'frac34' => 190, 'iquest' => 191, 'Agrave' => 192, 
		  'Aacute' => 193, 'Acirc' => 194, 'Atilde' => 195, 'Auml' => 196, 
		  'Aring' => 197, 'AElig' => 198, 'Ccedil' => 199, 'Egrave' => 200, 
		  'Eacute' => 201, 'Ecirc' => 202, 'Euml' => 203, 'Igrave' => 204, 
		  'Iacute' => 205, 'Icirc' => 206, 'Iuml' => 207, 'ETH' => 208, 
		  'Ntilde' => 209, 'Ograve' => 210, 'Oacute' => 211, 'Ocirc' => 212, 
		  'Otilde' => 213, 'Ouml' => 214, 'times' => 215, 'Oslash' => 216, 
		  'Ugrave' => 217, 'Uacute' => 218, 'Ucirc' => 219, 'Uuml' => 220, 
		  'Yacute' => 221, 'THORN' => 222, 'szlig' => 223, 'agrave' => 224, 
		  'aacute' => 225, 'acirc' => 226, 'atilde' => 227, 'auml' => 228, 
		  'aring' => 229, 'aelig' => 230, 'ccedil' => 231, 'egrave' => 232, 
		  'eacute' => 233, 'ecirc' => 234, 'euml' => 235, 'igrave' => 236, 
		  'iacute' => 237, 'icirc' => 238, 'iuml' => 239, 'eth' => 240, 
		  'ntilde' => 241, 'ograve' => 242, 'oacute' => 243, 'ocirc' => 244, 
		  'otilde' => 245, 'ouml' => 246, 'divide' => 247, 'oslash' => 248, 
		  'ugrave' => 249, 'uacute' => 250, 'ucirc' => 251, 'uuml' => 252, 
		  'yacute' => 253, 'thorn' => 254, 'yuml' => 255, 'quot' => 34, 
		  'amp' => 38, 'lt' => 60, 'gt' => 62, 'apos' => 39, 'OElig' => 338, 
		  'oelig' => 339, 'Scaron' => 352, 'scaron' => 353, 'Yuml' => 376, 
		  'circ' => 710, 'tilde' => 732, 'ensp' => 8194, 'emsp' => 8195, 
		  'thinsp' => 8201, 'zwnj' => 8204, 'zwj' => 8205, 'lrm' => 8206, 
		  'rlm' => 8207, 'ndash' => 8211, 'mdash' => 8212, 'lsquo' => 8216, 
		  'rsquo' => 8217, 'sbquo' => 8218, 'ldquo' => 8220, 'rdquo' => 8221, 
		  'bdquo' => 8222, 'dagger' => 8224, 'Dagger' => 8225, 'permil' => 8240, 
		  'lsaquo' => 8249, 'rsaquo' => 8250, 'euro' => 8364, 'fnof' => 402, 
		  'Alpha' => 913, 'Beta' => 914, 'Gamma' => 915, 'Delta' => 916, 
		  'Epsilon' => 917, 'Zeta' => 918, 'Eta' => 919, 'Theta' => 920, 
		  'Iota' => 921, 'Kappa' => 922, 'Lambda' => 923, 'Mu' => 924, 'Nu' => 925, 
		  'Xi' => 926, 'Omicron' => 927, 'Pi' => 928, 'Rho' => 929, 'Sigma' => 931,
		  'Tau' => 932, 'Upsilon' => 933, 'Phi' => 934, 'Chi' => 935, 'Psi' => 936,
		  'Omega' => 937, 'alpha' => 945, 'beta' => 946, 'gamma' => 947, 
		  'delta' => 948, 'epsilon' => 949, 'zeta' => 950, 'eta' => 951, 
		  'theta' => 952, 'iota' => 953, 'kappa' => 954, 'lambda' => 955, 
		  'mu' => 956, 'nu' => 957, 'xi' => 958, 'omicron' => 959, 'pi' => 960, 
		  'rho' => 961, 'sigmaf' => 962, 'sigma' => 963, 'tau' => 964, 
		  'upsilon' => 965, 'phi' => 966, 'chi' => 967, 'psi' => 968, 
		  'omega' => 969, 'thetasym' => 977, 'upsih' => 978, 'piv' => 982, 
		  'bull' => 8226, 'hellip' => 8230, 'prime' => 8242, 'Prime' => 8243, 
		  'oline' => 8254, 'frasl' => 8260, 'weierp' => 8472, 'image' => 8465, 
		  'real' => 8476, 'trade' => 8482, 'alefsym' => 8501, 'larr' => 8592, 
		  'uarr' => 8593, 'rarr' => 8594, 'darr' => 8595, 'harr' => 8596, 
		  'crarr' => 8629, 'lArr' => 8656, 'uArr' => 8657, 'rArr' => 8658, 
		  'dArr' => 8659, 'hArr' => 8660, 'forall' => 8704, 'part' => 8706, 
		  'exist' => 8707, 'empty' => 8709, 'nabla' => 8711, 'isin' => 8712, 
		  'notin' => 8713, 'ni' => 8715, 'prod' => 8719, 'sum' => 8721, 
		  'minus' => 8722, 'lowast' => 8727, 'radic' => 8730, 'prop' => 8733, 
		  'infin' => 8734, 'ang' => 8736, 'and' => 8743, 'or' => 8744, 'cap' => 8745, 
		  'cup' => 8746, 'int' => 8747, 'there4' => 8756, 'sim' => 8764, 
		  'cong' => 8773, 'asymp' => 8776, 'ne' => 8800, 'equiv' => 8801, 
		  'le' => 8804, 'ge' => 8805, 'sub' => 8834, 'sup' => 8835, 'nsub' => 8836, 
		  'sube' => 8838, 'supe' => 8839, 'oplus' => 8853, 'otimes' => 8855, 
		  'perp' => 8869, 'sdot' => 8901, 'lceil' => 8968, 'rceil' => 8969, 
		  'lfloor' => 8970, 'rfloor' => 8971, 'lang' => 9001, 'rang' => 9002, 
		  'loz' => 9674, 'spades' => 9824, 'clubs' => 9827, 'hearts' => 9829, 
		  'diams' => 9830
		);
		return $entity_array[$entity];
	}

	private function entities ($item) {
		$clean = '';
		$item_len = strlen($item);
		$text_start = 0;
		$next_ten = 0;
		$entity_test = '';
		$contains_semicolon = false;
		$next_start = 0;
		while (true) {
			//echo("while\n");
			$position = strpos($item, '&', $text_start);
			//echo('p: ' . $position . '(' . $text_start . ')' . "\n");
			if ($position === false) {
				break;
			}
			for ($i=$text_start; $i < $position; $i++) {
				$clean .= $item[$i];
			}
			$next_ten = $position + 11;
			if ($next_ten > $item_len) {
				$next_ten = $item_len;
			}
			$entity_test = '';
			$contains_semicolon = false;
			for ($i=($position + 1); $i < $next_ten; $i++) {
				if ($item[$i] == ';') {
					$contains_semicolon = true;
					$next_start = $i;
					break;
				}
				$entity_test .= $item[$i];
			}
			if ($contains_semicolon == true) {
				if (is_numeric(str_replace('#', '', $entity_test)) == true && $entity_test[0] == '#') {
					$clean .= '&' . $entity_test . ';';
					$text_start = $next_start + 1;
					continue;
				}
				if (preg_match('/[^a-zA-Z0-9]/', $entity_test)) {
					$clean .= '&#38;' . $entity_test . ';';
					$text_start = $next_start + 1;
					continue;
				} else {
					$replace_entity = '&#' . $this->replace_entity($entity_test) . ';';
					if ($replace_entity != '&#;') {
						$clean .= $replace_entity; 
					}
					$text_start = $next_start + 1;
					continue;
				}
			} else {
				$clean .= '&#38;' . $entity_test;
				$text_start = $next_ten;
				continue;
			}
		}
		for ($i=$text_start; $i < $item_len; $i++) {
			$clean .= $item[$i];
		}
		return $clean;
	}

  
  /**
   * Prepare an item for insertion into a DOM.
   *
   * This handles a variety of boilerplate tasks that need doing before an 
   * indeterminate object can be inserted into a DOM tree.
   * - If item is a string, this is converted into a document fragment and returned.
   * - If item is a QueryPath, then the first item is retrieved and this call function
   *   is called recursivel.
   * - If the item is a DOMNode, it is imported into the current DOM if necessary.
   * - If the item is a SimpleXMLElement, it is converted into a DOM node and then
   *   imported.
   */
  protected function prepareInsert($item) {
	if(empty($item)) {
      return;
    }
    elseif (is_string($item)) {
      /* This isn't what jQuery does, so we won't do it that way.
      if ($this->isXMLish($item)) {
        $frag = $this->document->createDocumentFragment();
        $frag->appendXML($item);
        return $frag;
      }
      else {
        return $this->document->createElement($item);
      }
      */
      $frag = $this->document->createDocumentFragment();
      if (strpos($item, '&') !== false) {
		$item = $this->entities($item);
	  }
	  $append = $frag->appendXML($item);
	  if ($append == false) {
		print_r(filter_errors('domdocumentfragment.appendxml'));
		print_r(explode("\n", $item));
		exit();
	  }
      return $frag;
    }
    elseif ($item instanceof QueryPath) {
      if ($item->size() == 0) 
        return;
        
      return $this->prepareInsert($item->get(0));
    }
    elseif ($item instanceof DOMNode) {
      if ($item->ownerDocument !== $this->document) {
        // Deep clone this and attach it to this document
        $item = $this->document->importNode($item, TRUE);
      }
      return $item;
    }
    elseif ($item instanceof SimpleXMLElement) {
      $element = dom_import_simplexml($item);
      return $this->document->importNode($element, TRUE);
    }
    // What should we do here?
    //var_dump($item);
    throw new QueryPathException("Cannot prepare item of unsupported type: " . gettype($item));
  }
  
  public function tag() {
    return ($this->size() > 0) ? $this->matches[0]->tagName : '';
  }
  
  public function remove($selector = NULL) {
    
    if(!empty($selector))
      $this->find($selector);
    
    $found = array();
    foreach ($this->matches as $item) {
      // The item returned is (according to docs) different from 
      // the one passed in, so we have to re-store it.
      $found[] = $item->parentNode->removeChild($item);
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function replaceAll($selector, DOMDocument $document) {
    $replacement = $this->size() > 0 ? $this->matches[0] : $this->document->createTextNode('');
    
    $c = new QueryPathCssEventHandler($document);
    $c->find($selector);
    $temp = $c->getMatches();
    foreach ($temp as $item)
      $item->parentNode->replaceChild($item, $replacement);
      
    return $this;
  }
  
  public function add($selector) {
    $found = qp($this->document, $selector)->get();
    // XXX: Need to test if this correctly handles duplicates.
    $this->setMatches(array_merge($this->matches, $found));
    return $this;
  }
  
  public function end() {
    // Note that this does not use setMatches because it must set the previous
    // set of matches to empty array.
    $this->matches = $this->last;
    $this->last = array();
    return $this;
  }
  public function andSelf() {
    $this->setMatches(array_merge($this->matches, $this->last));
    return $this;
  }
  
  public function removeChildren() {
    foreach ($this->matches as $m) {
      while($kid = $m->firstChild) {
        $m->removeChild($kid);
      }
    }
    return $this;
  }
  
  public function children($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      foreach($m->childNodes as $c) {
        if ($c->nodeType == XML_ELEMENT_NODE) $found[] = $c;
      }
    }
    if (empty($selector)) {
      $this->setMatches($found);
    }
    else {
      $this->matches = $found; // Don't buffer this. It is temporary.
      $this->filter($selector);
    }
    return $this;
  }
  
  public function contents() {
    $found = array();
    foreach ($this->matches as $m) {
      foreach ($m->childNodes as $c) {
        $found[] = $c;
      }
    }
    $this->setMatches(UniqueElementList::get($found));
    return $this;
  }
  
  public function siblings($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      $parent = $m->parentNode;
      foreach ($parent->childNodes as $n) {
        if ($n->nodeType == XML_ELEMENT_NODE && $n !== $m) {
          $found[] = $n;
        }
      }
    }
    if (empty($selector)) {
      $this->setMatches($found);
    }
    else {
      $this->matches = $found; // Don't buffer this. It is temporary.
      $this->filter($selector);
    }
    return $this;
  }
  
  public function parent($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while ($m->parentNode->nodeType !== XML_DOCUMENT_NODE) {
        $m = $m->parentNode;
        // Is there any case where parent node is not an element?
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector) > 0) {
              $found[] = $m;
              break;
            }
          }
          else {
            $found[] = $m;
            break;
          }
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function parents($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while ($m->parentNode->nodeType !== XML_DOCUMENT_NODE) {
        $m = $m->parentNode;
        // Is there any case where parent node is not an element?
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector) > 0)
              $found[] = $m;
          }
          else 
            $found[] = $m;
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function html($markup = NULL) {
    if (isset($markup)) {
      // Parse the HTML and insert it into the DOM
      //$doc = DOMDocument::loadHTML($markup);
      $doc = $this->document->createDocumentFragment();
      $doc->appendXML($markup);
      $this->removeChildren();
      $this->append($doc);
      return $this;
    }
    $length = $this->size();
    if ($length == 0) {
      return NULL;
    }
    // Only return the first item -- that's what JQ does.
    $first = $this->matches[0];
    if ($first instanceof DOMDocument || $first->isSameNode($first->ownerDocument->documentElement)) {
      return $this->document->saveHTML();
    }
    // saveHTML cannot take a node and serialize it.
    return $this->document->saveXML($this->matches[0]);
    
  }
  public function text($text = NULL) {
    if (isset($text)) {
      $this->removeChildren();
      $textNode = $this->document->createTextNode($text);
      foreach($this->matches as $m) $m->appendChild($textNode);
      return $this;
    }
    // Returns all text as one string:
    $buf = '';
    foreach ($this->matches as $m) $buf .= $m->textContent;
    return $buf;
  }
  
  public function val($value = NULL) {
    if (isset($value)) {
      foreach ($this->matches as $m) $m->attr('value', $value);
      return;
    }
    return empty($this->matches) ? NULL : $this->matches[0]->attr('value');
  }
  
  public function xml($markup = NULL) {
    if (isset($markup)) {
      $doc = $this->document->createDocumentFragment();
      $doc->appendXML($markup);
      $this->removeChildren();
      $this->append($doc);
      return $this;
    }
    $length = $this->size();
    if ($length == 0) {
      return NULL;
    }
    // Only return the first item -- that's what JQ does.
    $first = $this->matches[0];
    if ($first instanceof DOMDocument || $first->isSameNode($first->ownerDocument->documentElement)) {
      return $this->document->saveXML();
    }
    // saveHTML cannot take a node and serialize it.
    return $this->document->saveXML($this->matches[0]);
  }
  
  public function writeXML() {
    print $this->document->saveXML();
    return $this;
  }
  
  public function writeHTML($headers = array()) {
    print $this->document->saveHTML();
    return $this;
  }

  public function next($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while (isset($m->nextSibling)) {
        $m = $m->nextSibling;
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector) > 0) {
              $found[] = $m;
              break;
            }
          }
          else {
            $found[] = $m;
            break;
          }
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  public function nextAll($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while (isset($m->nextSibling)) {
        $m = $m->nextSibling;
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector) > 0) {
              $found[] = $m;
            }
          }
          else {
            $found[] = $m;
          }
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function prev($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while (isset($m->previousSibling)) {
        $m = $m->previousSibling;
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector)) {
              $found[] = $m;
              break;
            }
          }
          else {
            $found[] = $m;
            break;
          }
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  public function prevAll($selector = NULL) {
    $found = array();
    foreach ($this->matches as $m) {
      while (isset($m->previousSibling)) {
        $m = $m->previousSibling;
        if ($m->nodeType === XML_ELEMENT_NODE) {
          if (!empty($selector)) {
            if (qp($m)->is($selector)) {
              $found[] = $m;
            }
          }
          else {
            $found[] = $m;
          }
        }
      }
    }
    $this->setMatches($found);
    return $this;
  }
  
  public function addClass($class) {
    foreach ($this->matches as $m) {
      if ($m->hasAttribute('class')) {
        $val = $m->getAttribute('class');
        $m->setAttribute('class', $val . ' ' . $class);
      }
      else {
        $m->setAttribute('class', $class);
      }
    }
    return $this;
  }
  public function removeClass($class) {
    foreach ($this->matches as $m) {
      if ($m->hasAttribute('class')) {
        $vals = explode(' ', $m->getAttribute('class'));
        if (in_array($class, $vals)) {
          $buf = array();
          foreach ($vals as $v) {
            if ($v != $class) $buf[] = $v;
          }
          if (count($buf) == 0)
            $m->removeAttribute('class');
          else
            $m->setAttribute('class', implode(' ', $buf));
        }
      }
    }
    return $this;
  }
  public function hasClass($class) {
    foreach ($this->matches as $m) {
      if ($m->hasAttribute('class')) {
        $vals = explode(' ', $m->getAttribute('class'));
        if (in_array($class, $vals)) return TRUE;
      }
    }
    return FALSE;
  }


  public function branch() {
    return qp($this->matches);
  }
  
  public function cloneAll() {
    $found = array();
    foreach ($this->matches as $m) $found[] = $m->cloneNode(TRUE);
    $this->setMatches($found, FALSE);
    return $this;
  }
  
  /**
   * Clone the QueryPath.
   *
   * This makes a deep clone of the elements inside of the QueryPath.
   *
   * This clones only the QueryPathImpl, not all of the decorators. The
   * clone operator in PHP should handle the cloning of the decorators.
   */
  public function __clone() {
    //XXX: Should we clone the document?
    
    // Make sure we clone the kids.
    $this->cloneAll();
  }
  
  /////// PRIVATE FUNCTIONS ////////
  // Functions are declared private because nothing can subclass QueryPathImpl.
  // (It is, after all, final). Instead of extending this class, you 
  // should create a decorator for the class.
  
  // Subclasses may not implment this. Altering them may be altering
  // core assumptions about how things work. Instead, classes should 
  // override the constructor and pass in only one of the parsed types
  // that this class expects.
  private function isXMLish($string) {
    return preg_match(ML_EXP, $string) > 0;
  }
  
  private function parseXMLString($string, $flags = NULL) {
    $document = new DOMDocument();
    $lead = strtolower(substr($string, 0, 5)); // <?xml
    if ($lead == '<?xml') {
      //print htmlentities($string);
      $document->loadXML($string, $flags);
    }
    else {
      $document->loadHTML($string);
    }
    return $document;
  }
  
  /**
   * A utility function for setting the current set of matches.
   * It makes sure the last matches buffer is set (for end() and andSelf()).
   */
  private function setMatches($matches, $unique = TRUE) {
    // This causes a lot of overhead....
    if ($unique) $matches = self::unique($matches);
    
    $this->last = $this->matches;
    $this->matches = $matches;
  }
  
  /**
   * Parse just a fragment of XML.
   * This will automatically prepend an <?xml ?> declaration before parsing.
   * @param string $string 
   *   Fragment to parse.
   * @return DOMDocumentFragment 
   *   The parsed document fragment.
   */
  private function parseXMLFragment($string) {
    $frag = $this->document->createDocumentFragment();
    $frag->appendXML($string);
    return $frag;
  }
  
  /**
   * Parse an XML or HTML file.
   *
   * This attempts to autodetect the type of file, and then parse it.
   *
   * @param string $filename
   *  The file name to parse.
   * @param int $flags
   *  The OR-combined flags accepted by the DOM parser. See the PHP documentation
   *  for DOM or for libxml.
   * @param resource $context
   *  The stream context for the file IO. If this is set, then an alternate 
   *  parsing path is followed: The file is loaded by PHP's stream-aware IO
   *  facilities, read entirely into memory, and then handed off to 
   *  {@link parseXMLString()}. On large files, this can have a performance impact.
   */
  private function parseXMLFile($filename, $flags = NULL, $context = NULL) {
    
    // If a context is specified, we basically have to do the reading in 
    // two steps:
    if (!empty($context)) {
      $contents = file_get_contents($filename, FALSE, $context);
      return $this->parseXMLString($contents, $flags);
    }
    
    $document = new DOMDocument();
    $lastDot = strrpos($filename, '.');
    // FIXME: @ should be replaced with better error handling. 
    // We lose the real error.
    if ($lastDot !== FALSE && strtolower(substr($filename, $lastDot)) == '.html') {
      // Try parsing it as HTML.
      $r = @$document->loadHTMLFile($filename);
    }
    else {
      $r = @$document->load($filename, $flags);
    }
    if ($r == FALSE) {
      // FIXME: Need more info.
      throw new QueryPathException('Failed to load file ' . $filename);
    }
    return $document;
  }
  
  /**
   * Call extension methods.
   *
   * This function is used to invoke extension methods. It searches the
   * registered extenstensions for a matching function name. If one is found,
   * it is executed with the arguments in the $arguments array.
   * 
   * @throws QueryPathException
   *  An expcetion is thrown if a non-existent method is called.
   */
  public function __call($name, $arguments) {
    
    if (!QueryPathExtensionRegistry::$useRegistry) {
      throw new QueryPathException("No method named $name found (Extensions disabled).");      
    }
    
    // Loading of extensions is deferred until the first time a
    // non-core method is called. This makes constructing faster, but it
    // may make the first invocation of __call() slower (if there are 
    // enough extensions.)
    //
    // The main reason for moving this out of the constructor is that most
    // new QueryPath instances do not use extensions. Charging qp() calls
    // with the additional hit is not a good idea.
    //
    // Also, this will at least limit the number of circular references.
    if (empty($this->ext)) {
      // Load the registry
      $this->ext = QueryPathExtensionRegistry::getExtensions($this);
    }
    
    // Note that an empty ext registry indicates that extensions are disabled.
    if (!empty($this->ext) && QueryPathExtensionRegistry::hasMethod($name)) {
      $owner = QueryPathExtensionRegistry::getMethodClass($name);
      $method = new ReflectionMethod($owner, $name);
      return $method->invokeArgs($this->ext[$owner], $arguments);
    }
    throw new QueryPathException("No method named $name found.");
  }
  
  public function getIterator() {
    return new QueryPathIterator($this->matches);
  }
}

/**
 * An iterator for QueryPath.
 *
 * This provides iterator support for QueryPath. You do not need to construct
 * a QueryPathIterator. QueryPath does this when its {@link QueryPath::getIterator()}
 * method is called.
 */
class QueryPathIterator extends ArrayIterator {
  protected $a;
  public function __construct($array) {
    $this->a = $array;
    parent::__construct($array);
  }
  
  public function current() {
    return qp(parent::current($this->key()));
  }
}