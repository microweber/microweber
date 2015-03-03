<?php
/** @file
 * This file contains a full implementation of the EventHandler interface.
 *
 * The tools in this package initiate a CSS selector parsing routine and then
 * handle all of the callbacks.
 *
 * The implementation provided herein adheres to the CSS 3 Selector specification
 * with the following caveats:
 *
 *  - The negation (:not()) and containment (:has()) pseudo-classes allow *full*
 *    selectors and not just simple selectors.
 *  - There are a variety of additional pseudo-classes supported by this
 *    implementation that are not part of the spec. Most of the jQuery
 *    pseudo-classes are supported. The :x-root pseudo-class is also supported.
 *  - Pseudo-classes that require a User Agent to function have been disabled.
 *    Thus there is no :hover pseudo-class.
 *  - All pseudo-elements require the double-colon (::) notation. This breaks
 *    backward compatibility with the 2.1 spec, but it makes visible the issue
 *    that pseudo-elements cannot be effectively used with most of the present
 *    library. They return <b>stdClass objects with a text property</b> (QP > 1.3)
 *    instead of elements.
 *  - The pseudo-classes first-of-type, nth-of-type and last-of-type may or may
 *    not conform to the specification. The spec is unclear.
 *  - pseudo-class filters of the form -an+b do not function as described in the
 *    specification. However, they do behave the same way here as they do in
 *    jQuery.
 *  - This library DOES provide XML namespace aware tools. Selectors can use
 *    namespaces to increase specificity.
 *  - This library does nothing with the CSS 3 Selector specificity rating. Of
 *    course specificity is preserved (to the best of our abilities), but there
 *    is no calculation done.
 *
 * For detailed examples of how the code works and what selectors are supported,
 * see the CssEventTests file, which contains the unit tests used for
 * testing this implementation.
 *
 * @author M Butcher <matt@aleph-null.tv>
 * @license MIT
 */

namespace QueryPath\CSS;

/**
 * Handler that tracks progress of a query through a DOM.
 *
 * The main idea is that we keep a copy of the tree, and then use an
 * array to keep track of matches. To handle a list of selectors (using
 * the comma separator), we have to track both the currently progressing
 * match and the previously matched elements.
 *
 * To use this handler:
 * @code
 * $filter = '#id'; // Some CSS selector
 * $handler = new QueryPathEventHandler(DOMNode $dom);
 * $parser = new Parser();
 * $parser->parse($filter, $handler);
 * $matches = $handler->getMatches();
 * @endcode
 *
 * $matches will be an array of zero or more DOMElement objects.
 *
 * @ingroup querypath_css
 */
class QueryPathEventHandler implements EventHandler, Traverser {
  protected $dom = NULL; // Always points to the top level.
  protected $matches = NULL; // The matches
  protected $alreadyMatched = NULL; // Matches found before current selector.
  protected $findAnyElement = TRUE;


  /**
   * Create a new event handler.
   */
  public function __construct($dom) {
    $this->alreadyMatched = new \SplObjectStorage();
    $matches = new \SplObjectStorage();

    // Array of DOMElements
    if (is_array($dom) || $dom instanceof \SplObjectStorage) {
      //$matches = array();
      foreach($dom as $item) {
        if ($item instanceof \DOMNode && $item->nodeType == XML_ELEMENT_NODE) {
          //$matches[] = $item;
          $matches->attach($item);
        }
      }
      //$this->dom = count($matches) > 0 ? $matches[0] : NULL;
      if ($matches->count() > 0) {
        $matches->rewind();
        $this->dom = $matches->current();
      }
      else {
        //throw new Exception("Setting DOM to Null");
        $this->dom = NULL;
      }
      $this->matches = $matches;
    }
    // DOM Document -- we get the root element.
    elseif ($dom instanceof \DOMDocument) {
      $this->dom = $dom->documentElement;
      $matches->attach($dom->documentElement);
    }
    // DOM Element -- we use this directly
    elseif ($dom instanceof \DOMElement) {
      $this->dom = $dom;
      $matches->attach($dom);
    }
    // NodeList -- We turn this into an array
    elseif ($dom instanceof \DOMNodeList) {
      $a = array(); // Not sure why we are doing this....
      foreach ($dom as $item) {
        if ($item->nodeType == XML_ELEMENT_NODE) {
          $matches->attach($item);
          $a[] = $item;
        }
      }
      $this->dom = $a;
    }
    // FIXME: Handle SimpleXML!
    // Uh-oh... we don't support anything else.
    else {
      throw new \QueryPath\Exception("Unhandled type: " . get_class($dom));
    }
    $this->matches = $matches;
  }

  /**
   * Generic finding method.
   *
   * This is the primary searching method used throughout QueryPath.
   *
   * @param string $filter
   *  A valid CSS 3 filter.
   * @return QueryPathEventHandler
   *  Returns itself.
   */
  public function find($filter) {
    $parser = new Parser($filter, $this);
    $parser->parse();
    return $this;
  }

  /**
   * Get the elements that match the evaluated selector.
   *
   * This should be called after the filter has been parsed.
   *
   * @return array
   *  The matched items. This is almost always an array of
   *  {@link DOMElement} objects. It is always an instance of
   *  {@link DOMNode} objects.
   */
  public function getMatches() {
    //$result = array_merge($this->alreadyMatched, $this->matches);
    $result = new \SplObjectStorage();
    foreach($this->alreadyMatched as $m) $result->attach($m);
    foreach($this->matches as $m) $result->attach($m);
    return $result;
  }

  public function matches() {
    return $this->getMatches();
  }

  /**
   * Find any element with the ID that matches $id.
   *
   * If this finds an ID, it will immediately quit. Essentially, it doesn't
   * enforce ID uniqueness, but it assumes it.
   *
   * @param $id
   *  String ID for an element.
   */
  public function elementID($id) {
    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      // Check if any of the current items has the desired ID.
      if ($item->hasAttribute('id') && $item->getAttribute('id') === $id) {
        $found->attach($item);
        break;
      }
    }
    $this->matches = $found;
    $this->findAnyElement = FALSE;
  }

  // Inherited
  public function element($name) {
    $matches = $this->candidateList();
    $this->findAnyElement = FALSE;
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      // Should the existing item be included?
      // In some cases (e.g. element is root element)
      // it definitely should. But what about other cases?
      if ($item->tagName == $name) {
        $found->attach($item);
      }
      // Search for matching kids.
      //$nl = $item->getElementsByTagName($name);
      //$found = array_merge($found, $this->nodeListToArray($nl));
    }

    $this->matches = $found;
  }

  // Inherited
  public function elementNS($lname, $namespace = NULL) {
    $this->findAnyElement = FALSE;
    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      // Looking up NS URI only works if the XMLNS attributes are declared
      // at a level equal to or above the searching doc. Normalizing a doc
      // should fix this, but it doesn't. So we have to use a fallback
      // detection scheme which basically searches by lname and then
      // does a post hoc check on the tagname.

      //$nsuri = $item->lookupNamespaceURI($namespace);
      $nsuri = $this->dom->lookupNamespaceURI($namespace);

      // XXX: Presumably the base item needs to be checked. Spec isn't
      // too clear, but there are three possibilities:
      // - base should always be checked (what we do here)
      // - base should never be checked (only children)
      // - base should only be checked if it is the root node
      if ($item instanceof \DOMNode
          && $item->namespaceURI == $nsuri
          && $lname == $item->localName) {
        $found->attach($item);
      }

      if (!empty($nsuri)) {
        $nl = $item->getElementsByTagNameNS($nsuri, $lname);
        // If something is found, merge them:
        //if (!empty($nl)) $found = array_merge($found, $this->nodeListToArray($nl));
        if (!empty($nl)) $this->attachNodeList($nl, $found);
      }
      else {
        //$nl = $item->getElementsByTagName($namespace . ':' . $lname);
        $nl = $item->getElementsByTagName($lname);
        $tagname = $namespace . ':' . $lname;
        $nsmatches = array();
        foreach ($nl as $node) {
          if ($node->tagName == $tagname) {
            //$nsmatches[] = $node;
            $found->attach($node);
          }
        }
        // If something is found, merge them:
        //if (!empty($nsmatches)) $found = array_merge($found, $nsmatches);
      }
    }
    $this->matches = $found;
  }

  public function anyElement() {
    $found = new \SplObjectStorage();
    //$this->findAnyElement = TRUE;
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      $found->attach($item); // Add self
      // See issue #20 or section 6.2 of this:
      // http://www.w3.org/TR/2009/PR-css3-selectors-20091215/#universal-selector
      //$nl = $item->getElementsByTagName('*');
      //$this->attachNodeList($nl, $found);
    }

    $this->matches = $found;
    $this->findAnyElement = FALSE;
  }
  public function anyElementInNS($ns) {
    //$this->findAnyElement = TRUE;
    $nsuri = $this->dom->lookupNamespaceURI($ns);
    $found = new \SplObjectStorage();
    if (!empty($nsuri)) {
      $matches = $this->candidateList();
      foreach ($matches as $item) {
        if ($item instanceOf \DOMNode && $nsuri == $item->namespaceURI) {
          $found->attach($item);
        }
      }
    }
    $this->matches = $found;//UniqueElementList::get($found);
    $this->findAnyElement = FALSE;
  }
  public function elementClass($name) {

    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      if ($item->hasAttribute('class')) {
        $classes = explode(' ', $item->getAttribute('class'));
        if (in_array($name, $classes)) $found->attach($item);
      }
    }

    $this->matches = $found;//UniqueElementList::get($found);
    $this->findAnyElement = FALSE;
  }

  public function attribute($name, $value = NULL, $operation = EventHandler::isExactly) {
    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      if ($item->hasAttribute($name)) {
        if (isset($value)) {
          // If a value exists, then we need a match.
          if($this->attrValMatches($value, $item->getAttribute($name), $operation)) {
            $found->attach($item);
          }
        }
        else {
          // If no value exists, then we consider it a match.
          $found->attach($item);
        }
      }
    }
    $this->matches = $found; //UniqueElementList::get($found);
    $this->findAnyElement = FALSE;
  }

  /**
   * Helper function to find all elements with exact matches.
   *
   * @deprecated All use cases seem to be covered by attribute().
   */
  protected function searchForAttr($name, $value = NULL) {
    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $candidate) {
      if ($candidate->hasAttribute($name)) {
        // If value is required, match that, too.
        if (isset($value) && $value == $candidate->getAttribute($name)) {
          $found->attach($candidate);
        }
        // Otherwise, it's a match on name alone.
        else {
          $found->attach($candidate);
        }
      }
    }

    $this->matches = $found;
  }

  public function attributeNS($lname, $ns, $value = NULL, $operation = EventHandler::isExactly) {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    if (count($matches) == 0) {
      $this->matches = $found;
      return;
    }

    // Get the namespace URI for the given label.
    //$uri = $matches[0]->lookupNamespaceURI($ns);
    $matches->rewind();
    $e = $matches->current();
    $uri = $e->lookupNamespaceURI($ns);

    foreach ($matches as $item) {
      //foreach ($item->attributes as $attr) {
      //  print "$attr->prefix:$attr->localName ($attr->namespaceURI), Value: $attr->nodeValue\n";
      //}
      if ($item->hasAttributeNS($uri, $lname)) {
        if (isset($value)) {
          if ($this->attrValMatches($value, $item->getAttributeNS($uri, $lname), $operation)) {
            $found->attach($item);
          }
        }
        else {
          $found->attach($item);
        }
      }
    }
    $this->matches = $found;
    $this->findAnyElement = FALSE;
  }

  /**
   * This also supports the following nonstandard pseudo classes:
   *  - :x-reset/:x-root (reset to the main item passed into the constructor. Less drastic than :root)
   *  - :odd/:even (shorthand for :nth-child(odd)/:nth-child(even))
   */
  public function pseudoClass($name, $value = NULL) {
    $name = strtolower($name);
    // Need to handle known pseudoclasses.
    switch($name) {
      case 'visited':
      case 'hover':
      case 'active':
      case 'focus':
      case 'animated': //  Last 3 are from jQuery
      case 'visible':
      case 'hidden':
        // These require a UA, which we don't have.
      case 'target':
        // This requires a location URL, which we don't have.
        $this->matches = new \SplObjectStorage();
        break;
      case 'indeterminate':
        // The assumption is that there is a UA and the format is HTML.
        // I don't know if this should is useful without a UA.
        throw new NotImplementedException(":indeterminate is not implemented.");
        break;
      case 'lang':
        // No value = exception.
        if (!isset($value)) {
          throw new NotImplementedException("No handler for lang pseudoclass without value.");
        }
        $this->lang($value);
        break;
      case 'link':
        $this->searchForAttr('href');
        break;
      case 'root':
        $found = new \SplObjectStorage();
        if (empty($this->dom)) {
          $this->matches = $found;
        }
        elseif (is_array($this->dom)) {
          $found->attach($this->dom[0]->ownerDocument->documentElement);
          $this->matches = $found;
        }
        elseif ($this->dom instanceof \DOMNode) {
          $found->attach($this->dom->ownerDocument->documentElement);
          $this->matches = $found;
        }
        elseif ($this->dom instanceof \DOMNodeList && $this->dom->length > 0) {
          $found->attach($this->dom->item(0)->ownerDocument->documentElement);
          $this->matches = $found;
        }
        else {
          // Hopefully we never get here:
          $found->attach($this->dom);
          $this->matches = $found;
        }
        break;

      // NON-STANDARD extensions for reseting to the "top" items set in
      // the constructor.
      case 'x-root':
      case 'x-reset':
        $this->matches = new \SplObjectStorage();
        $this->matches->attach($this->dom);
        break;

      // NON-STANDARD extensions for simple support of even and odd. These
      // are supported by jQuery, FF, and other user agents.
      case 'even':
        $this->nthChild(2, 0);
        break;
      case 'odd':
        $this->nthChild(2, 1);
        break;

      // Standard child-checking items.
      case 'nth-child':
        list($aVal, $bVal) = $this->parseAnB($value);
        $this->nthChild($aVal, $bVal);
        break;
      case 'nth-last-child':
        list($aVal, $bVal) = $this->parseAnB($value);
        $this->nthLastChild($aVal, $bVal);
        break;
      case 'nth-of-type':
        list($aVal, $bVal) = $this->parseAnB($value);
        $this->nthOfTypeChild($aVal, $bVal, FALSE);
        break;
      case 'nth-last-of-type':
        list($aVal, $bVal) = $this->parseAnB($value);
        $this->nthLastOfTypeChild($aVal, $bVal);
        break;
      case 'first-child':
        $this->nthChild(0, 1);
        break;
      case 'last-child':
        $this->nthLastChild(0, 1);
        break;
      case 'first-of-type':
        $this->firstOfType();
        break;
      case 'last-of-type':
        $this->lastOfType();
        break;
      case 'only-child':
        $this->onlyChild();
        break;
      case 'only-of-type':
        $this->onlyOfType();
        break;
      case 'empty':
        $this->emptyElement();
        break;
      case 'not':
        if (empty($value)) {
          throw new ParseException(":not() requires a value.");
        }
        $this->not($value);
        break;
      // Additional pseudo-classes defined in jQuery:
      case 'lt':
      case 'gt':
      case 'nth':
      case 'eq':
      case 'first':
      case 'last':
      //case 'even':
      //case 'odd':
        $this->getByPosition($name, $value);
        break;
      case 'parent':
        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        foreach ($matches as $match) {
          if (!empty($match->firstChild)) {
            $found->attach($match);
          }
        }
        $this->matches = $found;
        break;

      case 'enabled':
      case 'disabled':
      case 'checked':
        $this->attribute($name);
        break;
      case 'text':
      case 'radio':
      case 'checkbox':
      case 'file':
      case 'password':
      case 'submit':
      case 'image':
      case 'reset':
      case 'button':
        $this->attribute('type', $name);
        break;

      case 'header':
        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        foreach ($matches as $item) {
          $tag = $item->tagName;
          $f = strtolower(substr($tag, 0, 1));
          if ($f == 'h' && strlen($tag) == 2 && ctype_digit(substr($tag, 1, 1))) {
            $found->attach($item);
          }
        }
        $this->matches = $found;
        break;
      case 'has':
        $this->has($value);
        break;
      // Contains == text matches.
      // In QP 2.1, this was changed.
      case 'contains':
        $value = $this->removeQuotes($value);

        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        foreach ($matches as $item) {
          if (strpos($item->textContent, $value) !== FALSE) {
            $found->attach($item);
          }
        }
        $this->matches = $found;
        break;

      // Since QP 2.1
      case 'contains-exactly':
        $value = $this->removeQuotes($value);

        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        foreach ($matches as $item) {
          if ($item->textContent == $value) {
            $found->attach($item);
          }
        }
        $this->matches = $found;
        break;
      default:
        throw new ParseException("Unknown Pseudo-Class: " . $name);
    }
    $this->findAnyElement = FALSE;
  }

  /**
   * Remove leading and trailing quotes.
   */
  private function removeQuotes($str) {
    $f = substr($str, 0, 1);
    $l = substr($str, -1);
    if ($f === $l && ($f == '"' || $f == "'")) {
      $str = substr($str, 1, -1);
    }
    return $str;
  }

  /**
   * Pseudo-class handler for a variety of jQuery pseudo-classes.
   * Handles lt, gt, eq, nth, first, last pseudo-classes.
   */
  private function getByPosition($operator, $pos) {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    if ($matches->count() == 0) {
      return;
    }

    switch ($operator) {
      case 'nth':
      case 'eq':
        if ($matches->count() >= $pos) {
          //$found[] = $matches[$pos -1];
          foreach ($matches as $match) {
            // CSS is 1-based, so we pre-increment.
            if ($matches->key() + 1 == $pos) {
              $found->attach($match);
              break;
            }
          }
        }
        break;
      case 'first':
        if ($matches->count() > 0) {
          $matches->rewind(); // This is necessary to init.
          $found->attach($matches->current());
        }
        break;
      case 'last':
        if ($matches->count() > 0) {

          // Spin through iterator.
          foreach ($matches as $item) {};

          $found->attach($item);
        }
        break;
      // case 'even':
      //         for ($i = 1; $i <= count($matches); ++$i) {
      //           if ($i % 2 == 0) {
      //             $found[] = $matches[$i];
      //           }
      //         }
      //         break;
      //       case 'odd':
      //         for ($i = 1; $i <= count($matches); ++$i) {
      //           if ($i % 2 == 0) {
      //             $found[] = $matches[$i];
      //           }
      //         }
      //         break;
      case 'lt':
        $i = 0;
        foreach ($matches as $item) {
          if (++$i < $pos) {
            $found->attach($item);
          }
        }
        break;
      case 'gt':
        $i = 0;
        foreach ($matches as $item) {
          if (++$i > $pos) {
            $found->attach($item);
          }
        }
        break;
    }

    $this->matches = $found;
  }

  /**
   * Parse an an+b rule for CSS pseudo-classes.
   * @param $rule
   *  Some rule in the an+b format.
   * @return
   *  Array (list($aVal, $bVal)) of the two values.
   * @throws ParseException
   *  If the rule does not follow conventions.
   */
  protected function parseAnB($rule) {
    if ($rule == 'even') {
      return array(2, 0);
    }
    elseif ($rule == 'odd') {
      return array(2, 1);
    }
    elseif ($rule == 'n') {
      return array(1, 0);
    }
    elseif (is_numeric($rule)) {
      return array(0, (int)$rule);
    }

    $rule = explode('n', $rule);
    if (count($rule) == 0) {
      throw new ParseException("nth-child value is invalid.");
    }

    // Each of these is legal: 1, -1, and -. '-' is shorthand for -1.
    $aVal = trim($rule[0]);
    $aVal = ($aVal == '-') ? -1 : (int)$aVal;

    $bVal = !empty($rule[1]) ? (int)trim($rule[1]) : 0;
    return array($aVal, $bVal);
  }

  /**
   * Pseudo-class handler for nth-child and all related pseudo-classes.
   *
   * @param int $groupSize
   *  The size of the group (in an+b, this is a).
   * @param int $elementInGroup
   *  The offset in a group. (in an+b this is b).
   * @param boolean $lastChild
   *  Whether counting should begin with the last child. By default, this is false.
   *  Pseudo-classes that start with the last-child can set this to true.
   */
  protected function nthChild($groupSize, $elementInGroup, $lastChild = FALSE) {
    // EXPERIMENTAL: New in Quark. This should be substantially faster
    // than the old (jQuery-ish) version. It still has E_STRICT violations
    // though.
    $parents = new \SplObjectStorage();
    $matches = new \SplObjectStorage();

    $i = 0;
    foreach ($this->matches as $item) {
      $parent = $item->parentNode;

      // Build up an array of all of children of this parent, and store the
      // index of each element for reference later. We only need to do this
      // once per parent, though.
      if (!$parents->contains($parent)) {

        $c = 0;
        foreach ($parent->childNodes as $child) {
          // We only want nodes, and if this call is preceded by an element
          // selector, we only want to match elements with the same tag name.
          // !!! This last part is a grey area in the CSS 3 Selector spec. It seems
          // necessary to make the implementation match the examples in the spec. However,
          // jQuery 1.2 does not do this.
          if ($child->nodeType == XML_ELEMENT_NODE && ($this->findAnyElement || $child->tagName == $item->tagName)) {
            // This may break E_STRICT.
            $child->nodeIndex = ++$c;
          }
        }
        // This may break E_STRICT.
        $parent->numElements = $c;
        $parents->attach($parent);
      }

      // If we are looking for the last child, we count from the end of a list.
      // Note that we add 1 because CSS indices begin at 1, not 0.
      if ($lastChild) {
        $indexToMatch = $item->parentNode->numElements  - $item->nodeIndex + 1;
      }
      // Otherwise we count from the beginning of the list.
      else {
        $indexToMatch = $item->nodeIndex;
      }

      // If group size is 0, then we return element at the right index.
      if ($groupSize == 0) {
        if ($indexToMatch == $elementInGroup)
          $matches->attach($item);
      }
      // If group size != 0, then we grab nth element from group offset by
      // element in group.
      else {
        if (($indexToMatch - $elementInGroup) % $groupSize == 0
            && ($indexToMatch - $elementInGroup) / $groupSize >= 0) {
          $matches->attach($item);
        }
      }

      // Iterate.
      ++$i;
    }
    $this->matches = $matches;
  }

  /**
   * Reverse a set of matches.
   *
   * This is now necessary because internal matches are no longer represented
   * as arrays.
   * @since QueryPath 2.0
   *//*
  private function reverseMatches() {
    // Reverse the candidate list. There must be a better way of doing
    // this.
    $arr = array();
    foreach ($this->matches as $m) array_unshift($arr, $m);

    $this->found = new \SplObjectStorage();
    foreach ($arr as $item) $this->found->attach($item);
  }*/

  /**
   * Pseudo-class handler for :nth-last-child and related pseudo-classes.
   */
  protected function nthLastChild($groupSize, $elementInGroup) {
    // New in Quark.
    $this->nthChild($groupSize, $elementInGroup, TRUE);
  }

  /**
   * Get a list of peer elements.
   * If $requireSameTag is TRUE, then only peer elements with the same
   * tagname as the given element will be returned.
   *
   * @param $element
   *  A DomElement.
   * @param $requireSameTag
   *  Boolean flag indicating whether all matches should have the same
   *  element name (tagName) as $element.
   * @return
   *  Array of peer elements.
   *//*
  protected function listPeerElements($element, $requireSameTag = FALSE) {
    $peers = array();
    $parent = $element->parentNode;
    foreach ($parent->childNodes as $node) {
      if ($node->nodeType == XML_ELEMENT_NODE) {
        if ($requireSameTag) {
          // Need to make sure that the tag matches:
          if ($element->tagName == $node->tagName) {
            $peers[] = $node;
          }
        }
        else {
          $peers[] = $node;
        }
      }
    }
    return $peers;
  }
  */
  /**
   * Get the nth child (by index) from matching candidates.
   *
   * This is used by pseudo-class handlers.
   */
   /*
  protected function childAtIndex($index, $tagName = NULL) {
    $restrictToElement = !$this->findAnyElement;
    $matches = $this->candidateList();
    $defaultTagName = $tagName;

    // XXX: Added in Quark: I believe this should return an empty
    // match set if no child was found tat the index.
    $this->matches = new \SplObjectStorage();

    foreach ($matches as $item) {
      $parent = $item->parentNode;

      // If a default tag name is supplied, we always use it.
      if (!empty($defaultTagName)) {
        $tagName = $defaultTagName;
      }
      // If we are inside of an element selector, we use the
      // tag name of the given elements.
      elseif ($restrictToElement) {
        $tagName = $item->tagName;
      }
      // Otherwise, we skip the tag name match.
      else {
        $tagName = NULL;
      }

      // Loop through all children looking for matches.
      $i = 0;
      foreach ($parent->childNodes as $child) {
        if ($child->nodeType !== XML_ELEMENT_NODE) {
          break; // Skip non-elements
        }

        // If type is set, then we do type comparison
        if (!empty($tagName)) {
          // Check whether tag name matches the type.
          if ($child->tagName == $tagName) {
            // See if this is the index we are looking for.
            if ($i == $index) {
              //$this->matches = new \SplObjectStorage();
              $this->matches->attach($child);
              return;
            }
            // If it's not the one we are looking for, increment.
            ++$i;
          }
        }
        // We don't care about type. Any tagName will match.
        else {
          if ($i == $index) {
            $this->matches->attach($child);
            return;
          }
          ++$i;
        }
      } // End foreach
    }

  }*/

  /**
   * Pseudo-class handler for nth-of-type-child.
   * Not implemented.
   */
  protected function nthOfTypeChild($groupSize, $elementInGroup, $lastChild) {
    // EXPERIMENTAL: New in Quark. This should be substantially faster
    // than the old (jQuery-ish) version. It still has E_STRICT violations
    // though.
    $parents = new \SplObjectStorage();
    $matches = new \SplObjectStorage();

    $i = 0;
    foreach ($this->matches as $item) {
      $parent = $item->parentNode;

      // Build up an array of all of children of this parent, and store the
      // index of each element for reference later. We only need to do this
      // once per parent, though.
      if (!$parents->contains($parent)) {

        $c = 0;
        foreach ($parent->childNodes as $child) {
          // This doesn't totally make sense, since the CSS 3 spec does not require that
          // this pseudo-class be adjoined to an element (e.g. ' :nth-of-type' is allowed).
          if ($child->nodeType == XML_ELEMENT_NODE && $child->tagName == $item->tagName) {
            // This may break E_STRICT.
            $child->nodeIndex = ++$c;
          }
        }
        // This may break E_STRICT.
        $parent->numElements = $c;
        $parents->attach($parent);
      }

      // If we are looking for the last child, we count from the end of a list.
      // Note that we add 1 because CSS indices begin at 1, not 0.
      if ($lastChild) {
        $indexToMatch = $item->parentNode->numElements  - $item->nodeIndex + 1;
      }
      // Otherwise we count from the beginning of the list.
      else {
        $indexToMatch = $item->nodeIndex;
      }

      // If group size is 0, then we return element at the right index.
      if ($groupSize == 0) {
        if ($indexToMatch == $elementInGroup)
          $matches->attach($item);
      }
      // If group size != 0, then we grab nth element from group offset by
      // element in group.
      else {
        if (($indexToMatch - $elementInGroup) % $groupSize == 0
            && ($indexToMatch - $elementInGroup) / $groupSize >= 0) {
          $matches->attach($item);
        }
      }

      // Iterate.
      ++$i;
    }
    $this->matches = $matches;
  }

  /**
   * Pseudo-class handler for nth-last-of-type-child.
   * Not implemented.
   */
  protected function nthLastOfTypeChild($groupSize, $elementInGroup) {
    $this->nthOfTypeChild($groupSize, $elementInGroup, TRUE);
  }

  /**
   * Pseudo-class handler for :lang
   */
  protected function lang($value) {
    // TODO: This checks for cases where an explicit language is
    // set. The spec seems to indicate that an element should inherit
    // language from the parent... but this is unclear.
    $operator = (strpos($value, '-') !== FALSE) ? self::isExactly : self::containsWithHyphen;

    $orig = $this->matches;
    $origDepth = $this->findAnyElement;

    // Do first pass: attributes in default namespace
    $this->attribute('lang', $value, $operator);
    $lang = $this->matches; // Temp array for merging.

    // Reset
    $this->matches = $orig;
    $this->findAnyElement = $origDepth;

    // Do second pass: attributes in 'xml' namespace.
    $this->attributeNS('lang', 'xml', $value, $operator);


    // Merge results.
    // FIXME: Note that we lose natural ordering in
    // the document because we search for xml:lang separately
    // from lang.
    foreach ($this->matches as $added) $lang->attach($added);
    $this->matches = $lang;
  }

  /**
   * Pseudo-class handler for :not(filter).
   *
   * This does not follow the specification in the following way: The CSS 3
   * selector spec says the value of not() must be a simple selector. This
   * function allows complex selectors.
   *
   * @param string $filter
   *  A CSS selector.
   */
  protected function not($filter) {
    $matches = $this->candidateList();
    //$found = array();
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      $handler = new QueryPathEventHandler($item);
      $not_these = $handler->find($filter)->getMatches();
      if ($not_these->count() == 0) {
        $found->attach($item);
      }
    }
    // No need to check for unique elements, since the list
    // we began from already had no duplicates.
    $this->matches = $found;
  }

  /**
   * Pseudo-class handler for :has(filter).
   * This can also be used as a general filtering routine.
   */
  public function has($filter) {
    $matches = $this->candidateList();
    //$found = array();
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      $handler = new QueryPathEventHandler($item);
      $these = $handler->find($filter)->getMatches();
      if (count($these) > 0) {
        $found->attach($item);
      }
    }
    $this->matches = $found;
    return $this;
  }

  /**
   * Pseudo-class handler for :first-of-type.
   */
  protected function firstOfType() {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      $type = $item->tagName;
      $parent = $item->parentNode;
      foreach ($parent->childNodes as $kid) {
        if ($kid->nodeType == XML_ELEMENT_NODE && $kid->tagName == $type) {
          if (!$found->contains($kid)) {
            $found->attach($kid);
          }
          break;
        }
      }
    }
    $this->matches = $found;
  }

  /**
   * Pseudo-class handler for :last-of-type.
   */
  protected function lastOfType() {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      $type = $item->tagName;
      $parent = $item->parentNode;
      for ($i = $parent->childNodes->length - 1; $i >= 0; --$i) {
        $kid = $parent->childNodes->item($i);
        if ($kid->nodeType == XML_ELEMENT_NODE && $kid->tagName == $type) {
          if (!$found->contains($kid)) {
            $found->attach($kid);
          }
          break;
        }
      }
    }
    $this->matches = $found;
  }

  /**
   * Pseudo-class handler for :only-child.
   */
  protected function onlyChild() {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    foreach($matches as $item) {
      $parent = $item->parentNode;
      $kids = array();
      foreach($parent->childNodes as $kid) {
        if ($kid->nodeType == XML_ELEMENT_NODE) {
          $kids[] = $kid;
        }
      }
      // There should be only one child element, and
      // it should be the one being tested.
      if (count($kids) == 1 && $kids[0] === $item) {
        $found->attach($kids[0]);
      }
    }
    $this->matches = $found;
  }

  /**
   * Pseudo-class handler for :empty.
   */
  protected function emptyElement() {
    $found = new \SplObjectStorage();
    $matches = $this->candidateList();
    foreach ($matches as $item) {
      $empty = TRUE;
      foreach($item->childNodes as $kid) {
        // From the spec: Elements and Text nodes are the only ones to
        // affect emptiness.
        if ($kid->nodeType == XML_ELEMENT_NODE || $kid->nodeType == XML_TEXT_NODE) {
          $empty = FALSE;
          break;
        }
      }
      if ($empty) {
        $found->attach($item);
      }
    }
    $this->matches = $found;
  }

  /**
   * Pseudo-class handler for :only-of-type.
   */
  protected function onlyOfType() {
    $matches = $this->candidateList();
    $found = new \SplObjectStorage();
    foreach ($matches as $item) {
      if (!$item->parentNode) {
        $this->matches = new \SplObjectStorage();
      }
      $parent = $item->parentNode;
      $onlyOfType = TRUE;

      // See if any peers are of the same type
      foreach($parent->childNodes as $kid) {
        if ($kid->nodeType == XML_ELEMENT_NODE
            && $kid->tagName == $item->tagName
            && $kid !== $item) {
          //$this->matches = new \SplObjectStorage();
          $onlyOfType = FALSE;
          break;
        }
      }

      // If no others were found, attach this one.
      if ($onlyOfType) $found->attach($item);
    }
    $this->matches = $found;
  }

  /**
   * Check for attr value matches based on an operation.
   */
  protected function attrValMatches($needle, $haystack, $operation) {

    if (strlen($haystack) < strlen($needle)) return FALSE;

    // According to the spec:
    // "The case-sensitivity of attribute names in selectors depends on the document language."
    // (6.3.2)
    // To which I say, "huh?". We assume case sensitivity.
    switch ($operation) {
      case EventHandler::isExactly:
        return $needle == $haystack;
      case EventHandler::containsWithSpace:
        return in_array($needle, explode(' ', $haystack));
      case EventHandler::containsWithHyphen:
        return in_array($needle, explode('-', $haystack));
      case EventHandler::containsInString:
        return strpos($haystack, $needle) !== FALSE;
      case EventHandler::beginsWith:
        return strpos($haystack, $needle) === 0;
      case EventHandler::endsWith:
        //return strrpos($haystack, $needle) === strlen($needle) - 1;
        return preg_match('/' . $needle . '$/', $haystack) == 1;
    }
    return FALSE; // Shouldn't be able to get here.
  }

  /**
   * As the spec mentions, these must be at the end of a selector or
   * else they will cause errors. Most selectors return elements. Pseudo-elements
   * do not.
   */
  public function pseudoElement($name) {
    // process the pseudoElement
    switch ($name) {
      // XXX: Should this return an array -- first line of
      // each of the matched elements?
      case 'first-line':
        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        $o = new \stdClass();
        foreach ($matches as $item) {
          $str = $item->textContent;
          $lines = explode("\n", $str);
          if (!empty($lines)) {
            $line = trim($lines[0]);
            if (!empty($line))
              $o->textContent = $line;
              $found->attach($o);//trim($lines[0]);
          }
        }
        $this->matches = $found;
        break;
      // XXX: Should this return an array -- first letter of each
      // of the matched elements?
      case 'first-letter':
        $matches = $this->candidateList();
        $found = new \SplObjectStorage();
        $o = new \stdClass();
        foreach ($matches as $item) {
          $str = $item->textContent;
          if (!empty($str)) {
            $str = substr($str,0, 1);
            $o->textContent = $str;
            $found->attach($o);
          }
        }
        $this->matches = $found;
        break;
      case 'before':
      case 'after':
        // There is nothing in a DOM to return for the before and after
        // selectors.
      case 'selection':
        // With no user agent, we don't have a concept of user selection.
        throw new NotImplementedException("The $name pseudo-element is not implemented.");
        break;
    }
    $this->findAnyElement = FALSE;
  }
  public function directDescendant() {
    $this->findAnyElement = FALSE;

    $kids = new \SplObjectStorage();
    foreach ($this->matches as $item) {
      $kidsNL = $item->childNodes;
      foreach ($kidsNL as $kidNode) {
        if ($kidNode->nodeType == XML_ELEMENT_NODE) {
          $kids->attach($kidNode);
        }
      }
    }
    $this->matches = $kids;
  }
  /**
   * For an element to be adjacent to another, it must be THE NEXT NODE
   * in the node list. So if an element is surrounded by pcdata, there are
   * no adjacent nodes. E.g. in <a/>FOO<b/>, the a and b elements are not
   * adjacent.
   *
   * In a strict DOM parser, line breaks and empty spaces are nodes. That means
   * nodes like this will not be adjacent: <test/> <test/>. The space between
   * them makes them non-adjacent. If this is not the desired behavior, pass
   * in the appropriate flags to your parser. Example:
   * <code>
   * $doc = new DomDocument();
   * $doc->loadXML('<test/> <test/>', LIBXML_NOBLANKS);
   * </code>
   */
  public function adjacent() {
    $this->findAnyElement = FALSE;
    // List of nodes that are immediately adjacent to the current one.
    //$found = array();
    $found = new \SplObjectStorage();
    foreach ($this->matches as $item) {
      while (isset($item->nextSibling)) {
        if (isset($item->nextSibling) && $item->nextSibling->nodeType === XML_ELEMENT_NODE) {
          $found->attach($item->nextSibling);
          break;
        }
        $item = $item->nextSibling;
      }
    }
    $this->matches = $found;
  }

  public function anotherSelector() {
    $this->findAnyElement = FALSE;
    // Copy old matches into buffer.
    if ($this->matches->count() > 0) {
      //$this->alreadyMatched = array_merge($this->alreadyMatched, $this->matches);
      foreach ($this->matches as $item) $this->alreadyMatched->attach($item);
    }

    // Start over at the top of the tree.
    $this->findAnyElement = TRUE; // Reset depth flag.
    $this->matches = new \SplObjectStorage();
    $this->matches->attach($this->dom);
  }

  /**
   * Get all nodes that are siblings to currently selected nodes.
   *
   * If two passed in items are siblings of each other, neither will
   * be included in the list of siblings. Their status as being candidates
   * excludes them from being considered siblings.
   */
  public function sibling() {
    $this->findAnyElement = FALSE;
    // Get the nodes at the same level.

    if ($this->matches->count() > 0) {
      $sibs = new \SplObjectStorage();
      foreach ($this->matches as $item) {
        /*$candidates = $item->parentNode->childNodes;
        foreach ($candidates as $candidate) {
          if ($candidate->nodeType === XML_ELEMENT_NODE && $candidate !== $item) {
            $sibs->attach($candidate);
          }
        }
        */
        while ($item->nextSibling != NULL) {
          $item = $item->nextSibling;
          if ($item->nodeType === XML_ELEMENT_NODE) $sibs->attach($item);
        }
      }
      $this->matches = $sibs;
    }
  }

  /**
   * Get any descendant.
   */
  public function anyDescendant() {
    // Get children:
    $found = new \SplObjectStorage();
    foreach ($this->matches as $item) {
      $kids = $item->getElementsByTagName('*');
      //$found = array_merge($found, $this->nodeListToArray($kids));
      $this->attachNodeList($kids, $found);
    }
    $this->matches = $found;

    // Set depth flag:
    $this->findAnyElement = TRUE;
  }

  /**
   * Determine what candidates are in the current scope.
   *
   * This is a utility method that gets the list of elements
   * that should be evaluated in the context. If $this->findAnyElement
   * is TRUE, this will return a list of every element that appears in
   * the subtree of $this->matches. Otherwise, it will just return
   * $this->matches.
   */
  private function candidateList() {
    if ($this->findAnyElement) {
      return $this->getAllCandidates($this->matches);
    }
    return $this->matches;
  }

  /**
   * Get a list of all of the candidate elements.
   *
   * This is used when $this->findAnyElement is TRUE.
   * @param $elements
   *  A list of current elements (usually $this->matches).
   *
   * @return
   *  A list of all candidate elements.
   */
  private function getAllCandidates($elements) {
    $found = new \SplObjectStorage();
    foreach ($elements as $item) {
      $found->attach($item); // put self in
      $nl = $item->getElementsByTagName('*');
      //foreach ($nl as $node) $found[] = $node;
      $this->attachNodeList($nl, $found);
    }
    return $found;
  }
  /*
  public function nodeListToArray($nodeList) {
    $array = array();
    foreach ($nodeList as $node) {
      if ($node->nodeType == XML_ELEMENT_NODE) {
        $array[] = $node;
      }
    }
    return $array;
  }
  */

  /**
   * Attach all nodes in a node list to the given \SplObjectStorage.
   */
  public function attachNodeList(\DOMNodeList $nodeList, \SplObjectStorage $splos) {
    foreach ($nodeList as $item) $splos->attach($item);
  }

}
