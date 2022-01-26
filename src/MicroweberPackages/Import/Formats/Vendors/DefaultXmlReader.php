<?php
namespace MicroweberPackages\Import\Formats\Vendors;

use Microweber\Providers\UrlManager;

class DefaultXmlReader
{
    public function read($file)
    {

    }

    protected function getArrayFromDomNode($node)
    {
        $array = false;

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            if ($node->childNodes->length == 1) {
                $array[$node->firstChild->nodeName] = $node->firstChild->nodeValue;
            } else {
                foreach ($node->childNodes as $childNode) {
                    if ($childNode->nodeType != XML_TEXT_NODE) {
                        $array[$childNode->nodeName][] = $this->getArrayFromDomNode($childNode);
                    } else {
                        $array[$childNode->nodeName][] = $childNode->wholeText;
                    }
                }
            }
        }

        return $array;
    }
}
