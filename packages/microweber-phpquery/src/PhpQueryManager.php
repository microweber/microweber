<?php

namespace MicroweberPackages\PhpQuery;

use MicroweberPackages\PhpQuery\Facades\PhpQuery;


/**
 * PhpQuery Manager - provides an object-oriented API for the phpQuery library.
 * Can be used via PhpQuery:: or dependency injection.
 */
class PhpQueryManager
{
    /**
     * Create a new document from HTML markup.
     *
     * @param string|null $markup
     * @param string|null $contentType
     * @return PhpQueryObject
     */
    public function newDocument($markup = null, $contentType = null)
    {
        return PhpQuery::newDocument($markup, $contentType);
    }

    /**
     * Create a new document from HTML markup.
     *
     * @param string|null $markup
     * @param string|null $charset
     * @return PhpQueryObject
     */
    public function newDocumentHTML($markup = null, $charset = null)
    {
        return PhpQuery::newDocumentHTML($markup, $charset);
    }

    /**
     * Create a new document from XML markup.
     *
     * @param string|null $markup
     * @param string|null $charset
     * @return PhpQueryObject
     */
    public function newDocumentXML($markup = null, $charset = null)
    {
        return PhpQuery::newDocumentXML($markup, $charset);
    }

    /**
     * Create a new document from XHTML markup.
     *
     * @param string|null $markup
     * @param string|null $charset
     * @return PhpQueryObject
     */
    public function newDocumentXHTML($markup = null, $charset = null)
    {
        return PhpQuery::newDocumentXHTML($markup, $charset);
    }

    /**
     * Create a new document from a file.
     *
     * @param string $file
     * @param string|null $contentType
     * @return PhpQueryObject
     */
    public function newDocumentFile($file, $contentType = null)
    {
        return PhpQuery::newDocumentFile($file, $contentType);
    }

    /**
     * Run a CSS query using pq() syntax.
     *
     * @param mixed $arg1
     * @param mixed $context
     * @return PhpQueryObject
     */
    public function pq($arg1, $context = null)
    {
        return PhpQuery::pq($arg1, $context);
    }

    /**
     * Unload all or specified documents from memory.
     *
     * @param mixed|null $id
     */
    public function unloadDocuments($id = null)
    {
        PhpQuery::unloadDocuments($id);
    }

    /**
     * Get document by ID.
     *
     * @param string|null $id
     * @return PhpQueryObject
     */
    public function getDocument($id = null)
    {
        return PhpQuery::getDocument($id);
    }

    /**
     * Get the document ID from a source.
     *
     * @param mixed $source
     * @return string|null
     */
    public function getDocumentID($source)
    {
        return PhpQuery::getDocumentID($source);
    }
}