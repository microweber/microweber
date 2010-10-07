<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
* XML library for CodeIgniter
*
*    author: Woody Gilk
* copyright: (c) 2006
*   license: http://creativecommons.org/licenses/by-sa/2.5/
*      file: libraries/Xml.php
*/

class Xml {
  function Xml () {
  }

  private $document;
  private $filename;

  public function load ($file) {
    /***
     * @public
     * Load an file for parsing
     */
    $bad  = array('|//+|', '|\.\./|');
    $good = array('/', '');
    $file = APPPATH.preg_replace ($bad, $good, $file).'.xml';

    if (! file_exists ($file)) {
      return false;
    }

    $this->document = utf8_encode (file_get_contents($file));
    $this->filename = $file;

    return true;
  }  /* END load */

  public function parse () {
    /***
     * @public
     * Parse an XML document into an array
     */
    $xml = $this->document;
    if ($xml == '') {
      return false;
    }

    $doc = new DOMDocument ();
    $doc->preserveWhiteSpace = false;
    if ($doc->loadXML ($xml)) {
      $array = $this->flatten_node ($doc);
      if (count ($array) > 0) {
        return $array;
      }
    }

    return false;
  } /* END parse */

  private function flatten_node ($node) {
    /***
     * @private
     * Helper function to flatten an XML document into an array
     */

    $array = array();

    foreach ($node->childNodes as $child) {
      if ($child->hasChildNodes ()) {
        if ($node->firstChild->nodeName == $node->lastChild->nodeName && $node->childNodes->length > 1) {
          $array[$child->nodeName][] = $this->flatten_node ($child);
        }
        else {
          $array[$child->nodeName][] = $this->flatten_node($child);

          if ($child->hasAttributes ()) {
            $index = count($array[$child->nodeName])-1;
            $attrs = $array[$child->nodeName][$index]['__attrs'];
            foreach ($child->attributes as $attribute) {
              $attrs[$attribute->name] = $attribute->value;
            }
          }
        }
      }
      else {
        return $child->nodeValue;
      }
    }

    return $array;
  } /* END node_to_array */
}

?>