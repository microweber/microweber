<?php
/** @file
 * XML extensions. See QPXML.
 */
namespace QueryPath\Extension;

use \QueryPath;
/**
 * Provide QueryPath with additional XML tools.
 *
 * @author M Butcher <matt@aleph-null.tv>
 * @author Xander Guzman <theshadow@shadowpedia.info>
 * @license MIT
 * @see QueryPath::Extension
 * @see QueryPath::ExtensionRegistry::extend()
 * @see QPXML
 * @ingroup querypath_extensions
 */
class QPXML implements \QueryPath\Extension {

  protected $qp;

  public function __construct(\QueryPath\Query $qp) {
    $this->qp = $qp;
  }

  public function schema($file) {
    $doc = $this->qp->branch()->top()->get(0)->ownerDocument;

    if (!$doc->schemaValidate($file)) {
      throw new \QueryPath\Exception('Document did not validate against the schema.');
    }
  }

  /**
   * Get or set a CDATA section.
   *
   * If this is given text, it will create a CDATA section in each matched element,
   * setting that item's value to $text.
   *
   * If no parameter is passed in, this will return the first CDATA section that it
   * finds in the matched elements.
   *
   * @param string $text
   *  The text data to insert into the current matches. If this is NULL, then the first
   *  CDATA will be returned.
   *
   * @return mixed
   *  If $text is not NULL, this will return a {@link QueryPath}. Otherwise, it will
   *  return a string. If no CDATA is found, this will return NULL.
   * @see comment()
   * @see QueryPath::text()
   * @see QueryPath::html()
   */
  public function cdata($text = NULL) {
    if (isset($text)) {
      // Add this text as CDATA in the current elements.
      foreach ($this->qp->get() as $element) {
        $cdata = $element->ownerDocument->createCDATASection($text);
        $element->appendChild($cdata);
      }
      return $this->qp;;
    }

    // Look for CDATA sections.
    foreach ($this->qp->get() as $ele) {
      foreach ($ele->childNodes as $node) {
        if ($node->nodeType == XML_CDATA_SECTION_NODE) {
          // Return first match.
          return $node->textContent;
        }
      }
    }
    return NULL;
    // Nothing found
  }

  /**
   * Get or set a comment.
   *
   * This function is used to get or set comments in an XML or HTML document.
   * If a $text value is passed in (and is not NULL), then this will add a comment
   * (with the value $text) to every match in the set.
   *
   * If no text is passed in, this will return the first comment in the set of matches.
   * If no comments are found, NULL will be returned.
   *
   * @param string $text
   *  The text of the comment. If set, a new comment will be created in every item
   *  wrapped by the current {@link QueryPath}.
   * @return mixed
   *  If $text is set, this will return a {@link QueryPath}. If no text is set, this
   *  will search for a comment and attempt to return the string value of the first
   *  comment it finds. If no comment is found, NULL will be returned.
   * @see cdata()
   */
  public function comment($text = NULL) {
    if (isset($text)) {
      foreach ($this->qp->get() as $element) {
        $comment = $element->ownerDocument->createComment($text);
        $element->appendChild($comment);
      }
      return $this->qp;
    }
    foreach ($this->qp->get() as $ele) {
      foreach ($ele->childNodes as $node) {
        if ($node->nodeType == XML_COMMENT_NODE) {
          // Return first match.
          return $node->textContent;
        }
      }
    }
  }

  /**
   * Get or set a processor instruction.
   */
  public function pi($prefix = NULL, $text = NULL) {
    if (isset($text)) {
      foreach ($this->qp->get() as $element) {
        $comment = $element->ownerDocument->createProcessingInstruction($prefix, $text);
        $element->appendChild($comment);
      }
      return $this->qp;
    }
    foreach ($this->qp->get() as $ele) {
      foreach ($ele->childNodes as $node) {
        if ($node->nodeType == XML_PI_NODE) {

          if (isset($prefix)) {
            if ($node->tagName == $prefix) {
              return $node->textContent;
            }
          }
          else {
            // Return first match.
            return $node->textContent;
          }
        }
      } // foreach
    } // foreach
  }
  public function toXml() {
      return $this->qp->document()->saveXml();
  }

  /**
   * Create a NIL element.
   *
   * @param string $text
   * @param string $value
   * @reval object $element
   */
  public function createNilElement($text, $value) {
    $value = ($value)? 'true':'false';
    $element = $this->qp->createElement($text);
    $element->attr('xsi:nil', $value);
    return $element;
  }

  /**
   * Create an element with the given namespace.
   *
   * @param string $text
   * @param string $nsUri
   *   The namespace URI for the given element.
   * @retval object
   */
  public function createElement($text, $nsUri = null) {
    if (isset ($text)) {
      foreach ($this->qp->get() as $element) {
        if ($nsUri === null && strpos($text, ':') !== false) {
          $ns = array_shift(explode(':', $text));
          $nsUri = $element->ownerDocument->lookupNamespaceURI($ns);

          if ($nsUri === null) {
            throw new \QueryPath\Exception("Undefined namespace for: " . $text);
          }
      }

      $node = null;
      if ($nsUri !== null) {
        $node = $element->ownerDocument->createElementNS(
          $nsUri,
          $text
        );
      } else {
        $node = $element->ownerDocument->createElement($text);
      }
        return QueryPath::with($node);
      }
    }
    return;
  }

  /**
   * Append an element.
   *
   * @param string $text
   * @retval object QueryPath
   */
  public function appendElement($text) {
    if (isset ($text)) {
      foreach ($this->qp->get() as $element) {
        $node = $this->qp->createElement($text);
        QueryPath::with($element)->append($node);
      }
    }
    return $this->qp;
  }
}
