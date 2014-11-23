<?php

namespace Sabre\XML;

use XMLReader;

/**
 * The Reader class expands upon PHP's built-in XMLReader.
 *
 * The intended usage, is to assign certain xml elements to PHP classes. These
 * need to be registered using the $elementMap public property.
 *
 * After this is done, a single call to parse() will parse the entire document,
 * and delegate sub-sections of the document to element classes.
 *
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Reader extends XMLReader {

    /**
     * This is the element map. It contains a list of xml elements (in clark
     * notation) as keys and PHP class names as values.
     *
     * The PHP class names must implement Sabre\XML\Element.
     *
     * @var array
     */
    public $elementMap = [];

    /**
     * Context information.
     *
     * This array has no pre-defined meaning. It can be used by the the user to
     * store arbitrary information.
     *
     * This is handy when a Property class needs access to this data. The only
     * direct relation they have back to other objects is the Reader itself.
     *
     * @var array
     */
    public $context = [];

    /**
     * Returns the current nodename in clark-notation.
     *
     * For example: "{http://www.w3.org/2005/Atom}feed".
     * This method returns null if we're not currently on an element.
     *
     * @return string|null
     */
    public function getClark() {

        if (!$this->namespaceURI) {
            return null;
        }
        return '{' . $this->namespaceURI . '}' . $this->localName;

    }

    /**
     * Reads the entire document.
     *
     * This function returns an array with the following three elements:
     *    * name - The root element name.
     *    * value - The value for the root element.
     *    * attributes - An array of attributes.
     *
     * This function will also disable the standard libxml error handler (which
     * usually just results in PHP errors), and throw exceptions instead.
     *
     * @return array
     */
    public function parse() {

        $previousSetting = libxml_use_internal_errors(true);

        // Really sorry about the silence operator, seems like I have no
        // choice. See:
        //
        // https://bugs.php.net/bug.php?id=64230
        while($this->nodeType !== self::ELEMENT && @$this->read()) {
            // noop
        }
        $result = $this->parseCurrentElement();

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previousSetting);

        if ($errors) {
            return $errors;
        } else {
            return $result;
        }

    }

    /**
     * Parses all elements below the current element.
     *
     * This method will return a string if this was a text-node, or an array if
     * there were sub-elements.
     *
     * If there's both text and sub-elements, the text will be discarded.
     *
     * @return array|string
     */
    public function parseInnerTree() {

        $previousDepth = $this->depth;

        $text = null;
        $elements = [];
        $attributes = [];

        if ($this->nodeType === self::ELEMENT && $this->isEmptyElement) {
            // Easy!
            $this->next();
            return null;
        }

        // Really sorry about the silence operator, seems like I have no
        // choice. See:
        //
        // https://bugs.php.net/bug.php?id=64230
        if (!@$this->read()) return false;

        while(true) {

            switch($this->nodeType) {
                case self::ELEMENT :
                    $elements[] = $this->parseCurrentElement();
                    break;
                case self::TEXT :
                case self::CDATA :
                    $text .= $this->value;
                    $this->read();
                    break;
                case self::END_ELEMENT :
                    // Ensuring we are moving the cursor after the end element.
                    $this->read();
                    break 2;
                case self::NONE :
                    throw new ParseException('We hit the end of the document prematurely. This likely means that some parser "eats" too many elements.');
                default :
                    // Advance to the next element
                    $this->read();
                    break;
            }

        }

        return ($elements?$elements:$text);

    }

    /**
     * Parses the current XML element.
     *
     * This method returns arn array with 3 properties:
     *   * name - A clark-notation xml element name.
     *   * value - The parsed value.
     *   * attributes - A key-value list of attributes.
     *
     * @return array
     */
    public function parseCurrentElement() {

        $name = $this->getClark();

        $attributes = [];

        if ($this->hasAttributes) {
            $attributes = $this->parseAttributes();
        }


        if (isset($this->elementMap[$name])) {
            $value = call_user_func( [ $this->elementMap[$name], 'deserializeXml' ], $this);
        } else {
            $value = Element\Base::deserializeXml($this);
        }

        return [
            'name' => $name,
            'value' => $value,
            'attributes' => $attributes,
        ];
    }

    /**
     * Grabs all the attributes from the current element, and returns them as a
     * key-value array.
     *
     * If the attributes are part of the same namespace, they will simply be
     * short keys. If they are defined on a different namespace, the attribute
     * name will be retured in clark-notation.
     *
     * @return void
     */
    public function parseAttributes() {

        $attributes = [];

        while($this->moveToNextAttribute()) {
            if ($this->namespaceURI) {

                // Ignoring 'xmlns', it doesn't make any sense.
                if ($this->namespaceURI === 'http://www.w3.org/2000/xmlns/') {
                    continue;
                }

                $name = $this->getClark();
                $attributes[$name] = $this->value;

            } else {
                $attributes[$this->localName] = $this->value;
            }
        }
        $this->moveToElement();

        return $attributes;

    }

}
