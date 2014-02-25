<?php

namespace Sabre\XML\Element;

use Sabre\XML;

/**
 * 'KeyValue' parses out all child elements from a single node, and outputs a
 * key=>value struct.
 *
 * Attributes will be removed, and duplicate child elements are discarded.
 * Complex values within the elements will be parsed by the 'standard' parser.

 * For example, KeyValue will parse:
 *
 * <?xml version="1.0"?>
 * <s:root xmlns:s="http://sabredav.org/ns">
 *   <s:elem1>value1</s:elem1>
 *   <s:elem2>value2</s:elem2>
 *   <s:elem3 />
 * </s:root>
 *
 * Into:
 *
 * [
 *   "{http://sabredav.org/ns}elem1" => "value1",
 *   "{http://sabredav.org/ns}elem2" => "value2",
 *   "{http://sabredav.org/ns}elem3" => null,
 * ];
 *
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class KeyValue implements XML\Element {

    /**
     * Value to serialize
     *
     * @var array
     */
    protected $value;

    /**
     * Constructor
     *
     * @param array $value
     */
    public function __construct(array $value = []) {

        $this->value = $value;

    }

    /**
     * The serialize method is called during xml writing.
     *
     * It should use the $writer argument to encode this object into XML.
     *
     * Important note: it is not needed to create the parent element. The
     * parent element is already created, and we only have to worry about
     * attributes, child elements and text (if any).
     *
     * Important note 2: If you are writing any new elements, you are also
     * responsible for closing them.
     *
     * @param XML\Writer $writer
     * @return void
     */
    public function serializeXml(XML\Writer $writer) {

        $writer->write($this->value);

    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * This method is called staticly, this is because in theory this method
     * may be used as a type of constructor, or factory method.
     *
     * Often you want to return an instance of the current class, but you are
     * free to return other data as well.
     *
     * Important note 2: You are responsible for advancing the reader to the
     * next element. Not doing anything will result in a never-ending loop.
     *
     * If you just want to skip parsing for this element altogether, you can
     * just call $reader->next();
     *
     * $reader->parseInnerTree() will parse the entire sub-tree, and advance to
     * the next element.
     *
     * @param XML\Reader $reader
     * @return mixed
     */
    static public function deserializeXml(XML\Reader $reader) {

        // If there's no children, we don't do anything.
        if ($reader->isEmptyElement) {
            $reader->next();
            return [];
        }

        $values = [];

        $reader->read();
        do {

            if ($reader->nodeType === XML\Reader::ELEMENT) {

                $clark = $reader->getClark();
                $values[$clark] = $reader->parseCurrentElement()['value'];

            } else {
                $reader->read();
            }

        } while ($reader->nodeType !== XML\Reader::END_ELEMENT);

        $reader->read();

        return $values;

    }

}

