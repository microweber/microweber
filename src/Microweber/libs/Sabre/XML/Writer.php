<?php

namespace Sabre\XML;

use
    XMLWriter,
    InvalidArgumentException;

/**
 * The XML Writer class.
 *
 * This class works exactly as PHP's built-in XMLWriter, with a few additions.
 *
 * Namespaces can be registered beforehand, globally. When the first element is
 * written, namespaces will automatically be declared.
 *
 * The writeAttribute, startElement and writeElement can now take a
 * clark-notation element name (example: {http://www.w3.org/2005/Atom}link).
 *
 * If, when writing the namespace is a known one a prefix will automatically be
 * selected, otherwise a random prefix will be generated.
 *
 * Instead of standard string values, the writer can take Element classes (as
 * defined by this library) to delegate the serialization.
 *
 * The write() method can take array structures to quickly write out simple xml
 * trees.
 *
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Writer extends XMLWriter {

    /**
     * This is a list of namespaces that you want to give default prefixes.
     *
     * You must make sure you create this entire list before starting to write.
     * They should be registered on the root element.
     *
     * @var array
     */
    public $namespaceMap = [];

    /**
     * Context information.
     *
     * This array has no pre-defined meaning. It can be used by the the user to
     * store arbitrary information.
     *
     * This is handy when a Property class needs access to this data. The only
     * direct relation they have back to other objects is the Writer itself.
     *
     * @var array
     */
    public $context = [];

    /**
     * This is used as a base uri.
     *
     * Serializers can optionally leverage this to expand relative urls.
     *
     * @var string
     */
    public $baseUri;

    /**
     * Any namespace that the writer is asked to write, will be added here.
     *
     * Any of these elements will get a new namespace definition *every single
     * time* they are used, but this array allows the writer to make sure that
     * the prefixes are consistent anyway.
     *
     * @var array
     */
    protected $adhocNamespaces = [];

    /**
     * When the first element is written, this flag is set to true.
     *
     * This ensures that the namespaces in the namespaces map are only written
     * once.
     *
     * @var bool
     */
    protected $namespacesWritten = false;

    /**
     * Writes a value to the output stream.
     *
     * The following values are supported:
     *   1. Scalar values will be written as-is, as text.
     *   2. Null values will be skipped (resulting in a short xml tag).
     *   3. If a value is an instance of an Element class, writing will be
     *      delegated to the object.
     *   4. If a value is an array, two formats are supported.
     *
     *  Array format 1:
     *  [
     *    "{namespace}name1" => "..",
     *    "{namespace}name2" => "..",
     *  ]
     *
     *  One element will be created for each key in this array. The values of
     *  this array support any format this method supports (this method is
     *  called recursively).
     *
     *  Array format 2:
     *
     *  [
     *    [
     *      "name" => "{namespace}name1"
     *      "value" => "..",
     *      "attributes" => [
     *          "attr" => "attribute value",
     *      ]
     *    ],
     *    [
     *      "name" => "{namespace}name1"
     *      "value" => "..",
     *      "attributes" => [
     *          "attr" => "attribute value",
     *      ]
     *    ]
     * ]
     *
     * @param mixed $value
     * @return void
     */
    public function write($value) {

        if (is_scalar($value)) {
            $this->text($value);
        } elseif ($value instanceof Element) {
            $value->serializeXml($this);
        } elseif (is_null($value)) {
            // noop
        } elseif (is_array($value)) {

            reset($value);
            if (is_int(key($value))) {

                // It's an array with numeric indices. We expect every item to
                // be an array with a name and a value.
                foreach($value as $subItem) {

                    if (!is_array($subItem) || !array_key_exists('name', $subItem) || !array_key_exists('value', $subItem)) {
                        throw new InvalidArgumentException('When passing an array to ->write with numeric indices, every item must have a "name" and a "value"');
                    }
                    $this->startElement($subItem['name']);
                    if (isset($subItem['attributes'])) {
                        $this->writeAttributes($subItem['attributes']);
                    }
                    $this->write($subItem['value']);
                    $this->endElement();

                }

            } else {

                // If it's an array with text-indices, we expect every item's
                // key to be an xml element name in clark notation.
                foreach($value as $name=>$subValue) {

                    $this->startElement($name);
                    $this->write($subValue);
                    $this->endElement();

                }

            }

        } elseif (is_object($value)) {

            throw new InvalidArgumentException('The writer cannot serialize objects of type: ' . get_class($value));

        }

    }

    /**
     * Starts an element.
     *
     * @param string $name
     * @return bool
     */
    public function startElement($name) {

        if ($name[0]==='{') {
            list($namespace, $localName) =
                Util::parseClarkNotation($name);

            if (array_key_exists($namespace, $this->namespaceMap)) {
                $result = $this->startElementNS($this->namespaceMap[$namespace], $localName, null);
            } else {

                if (!isset($this->adhocNamespaces[$namespace])) {
                    $this->adhocNamespaces[$namespace] = 'x' . (count($this->adhocNamespaces)+1);
                }
                $result = $this->startElementNS($this->adhocNamespaces[$namespace], $localName, $namespace);
            }
        } else {
            $result = parent::startElement($name);
        }
        if (!$this->namespacesWritten) {

            foreach($this->namespaceMap as $namespace => $prefix) {
                $this->writeAttribute(($prefix?'xmlns:' . $prefix:'xmlns'),$namespace);
            }
            $this->namespacesWritten = true;

        }

        return $result;

    }

    /**
     * Write a full element tag.
     *
     * This method automatically closes the element as well.
     *
     * @param string $name
     * @param string $content
     * @return bool
     */
    public function writeElement($name, $content = null) {

        $this->startElement($name);
        if (!is_null($content)) {
            $this->write($content);
        }
        $this->endElement();

    }
    /**
     * Writes a list of attributes.
     *
     * Attributes are specified as a key->value array.
     *
     * The key is an attribute name. If the key is a 'localName', the current
     * xml namespace is assumed. If it's a 'clark notation key', this namespace
     * will be used instead.
     *
     * @param array $attributes
     * @return void
     */
    public function writeAttributes(array $attributes) {

        foreach($attributes as $name=>$value) {
            $this->writeAttribute($name, $value);
        }

    }

    /**
     * Writes a new attribute.
     *
     * The name may be specified in clark-notation.
     *
     * Returns true when successful.
     *
     * @param string $name
     * @param string $value
     * @return bool
     */
    public function writeAttribute($name, $value) {

        if ($name[0] === '{') {
            list(
                $namespace,
                $localName
            ) = Util::parseClarkNotation($name);

            if (array_key_exists($namespace,$this->namespaceMap)) {

                // It's an attribute with a namespace we know
                $this->writeAttributeNS(
                    $this->namespaceMap[$namespace],
                    $localName,
                    null,
                    $value
                );
            } else {

                // We don't know the namespace, we must add it in-line
                if (!isset($this->adhocNamespaces[$namespace])) {
                    $this->adhocNamespaces[$namespace] = 'x' . (count($this->adhocNamespaces)+1);
                }
                $this->writeAttributeNS(
                    $this->adhocNamespaces[$namespace],
                    $localName,
                    $namespace,
                    $value
                );

            }
        } else {
            return parent::writeAttribute($name, $value);
        }

    }

}
