<?php

namespace Sabre\XML\Element;

use Sabre\XML;

/**
 * The Base XML element is the standard parser & generator that's used by the
 * XML reader and writer.
 *
 * It spits out a simply PHP array structure during deserialization, that can
 * also be directly injected back into Writer::write.
 *
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Base implements XML\Element {

    /**
     * PHP value to serialize.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructor
     *
     * @param mixed $value
     */
    public function __construct($value = null) {

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
     * This method is called statictly, this is because in theory this method
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

        $subTree = $reader->parseInnerTree();
        return $subTree;

    }

}

